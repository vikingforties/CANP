<?php
/*
Reconcile NOTAMs once they're published to the CANP submissions.
Source is RSS: https://notaminfo.com/feed?u=dalesflyer
This is a live only service. I don't want to hit the NOTAM info service too much pulling back 3MB XML each time.
cron entry for $this:
9,39 7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22 * * 1-5 /usr/local/bin/php -q /home/logansm/public_html/canp/reconcile.php >/dev/null 2>&1
i.e. run every half hour during weekdays but not overnight.
example entry...
<item>
  <title>H4942/20: Parachute jumping will take place </title>
  <description>EGPX/QWPLW/IV/M/W/000/044/5535N00342W003&lt;br&gt;&lt;br&gt;&lt;pre&gt;CIVIL AIRCRAFT NOTIFICATION PROCEDURE -
  MULTIPLE PARAGLIDERS&lt;br&gt;OPERATING IN LOW FLYING AREA 20T WI 2NM RADIUS &lt;br&gt;OF 553526N 0034203W (TINTO, SOUTH LANARKSHIRE).
  &lt;br&gt;2000FT AGL. CTC 07552 604900. 20/11/032/LFBC&lt;/pre&gt;&lt;br&gt;LOWER: Surface&lt;br&gt;UPPER: 4,350 Feet AMSL&lt;br&gt;FROM: 07 Nov 2020 07:00 GMT &lt;br&gt;TO: 07 Nov 2020 16:30 GMT </description>
  <guid>https://notaminfo.com/localarea?notam=1357966/0</guid>
  <link>https://notaminfo.com/localarea?notam=1357966/0</link>
  <pubDate>Fri, 06 Nov 2020 11:05 GMT</pubDate>
  <dc:creator>NATS Briefing 2011230935 on 23rd Nov 2020 09:35</dc:creator>
</item>
Fire from crontab
pull back all future submissions that don't have a reconciled time.
if there are submissions without a reconciled time
    request the rss feed
    foreach submissions
        match based on the site name AND the activation date (just in case two submissions for same site)
        remove html formatting & write the title & NOTAM description to the NOTAM db column
        write the time to the reconciled column & write the link to the NOTAM link column
*/
include("config.php");

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// DB Read for error conditions, using credentials from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// See if there's any work to do - unreconciled submissions.
$sql = sprintf('SELECT NOTAM FROM CANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) AND NOTAM IS NULL');
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    exit();
}

// We have some things to do so pull back the big RSS feed from NOTAMinfo.
$url = 'https://notaminfo.com/feed?u=dalesflyer'; //live
//$url = 'http://localhost/canp/testing/NOTAMfeed.xml'; //test
// Simple XML load doesn't work on eukhost because fopen is denied, try Curl and use simple xml load string.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xmlstring = curl_exec($ch) or die ("Can't connect to NOTAMinfo.");
curl_close ($ch);

$xml = simplexml_load_string($xmlstring) or die("Can't parse NOTAM RSS.");

// The feed is too big so prune out what we don't need.
$allglidingNOTAMs = array();
foreach ($xml->channel->item as $item){
    if (strpos($item->title, "Parachute jumping")  == true && strpos($item->description, "LI")  == true && strtotime($item->pubDate) > strtotime("-2 days")) { // tighten up second test.
        $glidingitem = array("title" => $item->title,
                        "description" => $item->description,
                        "guid" => $item->guid,
                        "link" => $item->link,
                        "pubdate" => $item->pubDate,
                        "creator" => $item->creator); // dc:creator might be broken. pubDate is the one that matters.
        array_push($allglidingNOTAMs, $glidingitem);
        //printf($item->title . "<br>");
        //printf($item->description . "<br>");
    }
}

// Now we have a manageable multidimensional array.
// Get all the unreconciled submissions to work with.
$sql = sprintf('SELECT Site, Scheduled, FromTime, ToTime, Reconciled, NOTAM, NOTAMlink FROM CANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) AND NOTAM IS NULL ORDER BY Scheduled, Site');
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    foreach($allglidingNOTAMs as $NOTAM) {
//        match based on the site name AND the activation date (just in case two submissions for same site)
        $uppersite =  preg_split('/ [A-Z]{2}/', $row["Site"]); // matching the space & start of grid ref.
        // Get rid of slashes / and apostrophes ' because they mangle them at the LFC with manual copy over on CANP to NOTAM. 
        $uppersite[0] = str_replace("'", "", $uppersite[0]);
        $uppersite[0] = str_replace(".", " ", $uppersite[0]); // Full stops with space as well in case St is changed.
        $NOTAM = str_replace("<br>", "", $NOTAM); // For site names which have a line break added to them.
        $demangledsite = preg_split('~/~', $uppersite[0]); // Remove the slash in the two part site name, using weird delimiters.
        $mangledNOTAMdesc = str_replace("'", "", $NOTAM["description"]);
        $scheddaymnth = date_format(date_create($row["Scheduled"]), "j M");
/*        echo $mangledNOTAMdesc;
        echo "<br>";
        echo strtoupper($demangledsite[0]);
        echo "<br>";
        echo $scheddaymnth;
        echo "<p>";*/
        if (strpos($mangledNOTAMdesc, strtoupper($demangledsite[0])) && strpos($NOTAM["description"], $scheddaymnth)) {
            // i.e. the upper case site name from the database has a match in the NOTAM description and both dates for the booking match.
            // remove html formatting & write the title & NOTAM description to the NOTAM db column
            $formatnotam = (strip_tags($NOTAM["description"]));
            // now undo the damage we just did :-(
            $formatnotam = str_replace('CIV', ' CIV', $formatnotam);
            $formatnotam = str_replace('ERSOP', 'ERS OP', $formatnotam);
            $formatnotam = str_replace('LFBC', ' LFBC ', $formatnotam);
            $formatnotam = str_replace('LFC', ' LFC ', $formatnotam);
            $formatnotam = str_replace('aceUP', 'ace UP', $formatnotam);
            $formatnotam = str_replace('SLFR', 'SL FR', $formatnotam);
            // and add some emboldening for ease of reading
            $formatnotam = str_replace('W (', 'W <b>(', $formatnotam);
            $formatnotam = str_replace('E (', 'E <b>(', $formatnotam);
            $formatnotam = str_replace(').', ')</b>.', $formatnotam);
            $formatnotam = $NOTAM["title"] . " " . $formatnotam;
            //format the pubDate for the database.
            $notamlink = $NOTAM['link'];
            $fmtpubdate = date_format(date_create($NOTAM['pubdate']), "Y-m-d H:i:s");
            //        write the time to the reconciled column, write the link to the NOTAM link etc.
            $rowsched = $row["Scheduled"];
            $rowsite = str_replace("'", "''", $row["Site"]); // Crazy! dbl apostrophe in any site name to get a single ' in the SQL below.
            $sql = sprintf("UPDATE CANP SET NOTAM='$formatnotam', NOTAMlink='$notamlink', Reconciled='$fmtpubdate' WHERE Scheduled = '$rowsched' AND Site = '$rowsite'"); //`EntryTime`=[value-1],(NOTAM, NOTAMlink, Reconciled) VALUES (\"$formatnotam\", \"$notamlink\", \"$fmtpubDate\");");
            if ($conn->query($sql) === TRUE) {
                echo ""; // Don't add msg to screen on success.
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
//stretch goal - we've got all the upcoming NOTAMs - why not add in the bookings for the ones that have been phoned in manually?
// i.e. make entries for them in the CANP table that show up as bookings.
}

$conn->close();
?>

<!--
Q) EGTT/QWPLW/IV/M/W/000/027/5036N00204W003
B) FROM: 21/03/09 07:30C) TO: 21/03/09 18:30
E) CIVIL AIRCRAFT NOTIFICATION PROCEDURE - MULTIPLE PARAGLIDERS
OPERATING IN LOW FLYING AREA 2 WI 2NM RADIUS 
OF PSN 503530N 0020338W (ST ALDHELMS HEAD, DORSET). 
2000FT AGL. CTC 07958439483. 21/03/031/LFBC

LOWER: SFC
UPPER: 2700FT AMSL
-->