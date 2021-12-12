<?php
// Details: https://github.com/vikingforties/CANP
/* To Dos

*/
include("config.php");

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Lets reject unwanted requests.
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    exit("Exit without return.");
}
if (sizeof($_GET, 1) > 0) {
    exit("Exit without return.");
}

// DB Read for error conditions, using credentials from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo(' <!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta name="description" content="Alerting form for free flying activity to military and civil pilots" />
  <meta name="keywords" content="CANP,NOTAM,RAF,Paragliding,HangGliding" />
  <meta name="author" content="Pete Logan" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Civil Aircraft Notification Procedure (CANP plus NOTAMs) for paragliders & hang gliders</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
  <link rel="stylesheet" href="styles/style.css">
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <div class="container">
    <h1 class="brand"><span>test CANP</span> for free fliers</h1>
    <div class="wrapper animated fadeInLeft">
      <div class="company-info">
	    <h3>Civil Aircraft Notification Procedure</h3>
		<h5>Always worth checking this page just to make sure you&#39;re not sending a duplicate booking in.</h5>
        <ul>
		  <div class="coinfo-links">
            <li><i class="fas fa-envelope"></i><a href="index.html">&nbsp;Send in CANP</a></li>
			<li><i class="fas fa-phone"></i>&nbsp;...or call it in:  0800 515544</li>
			<li><i class="fas fa-at"></i>&nbsp;...or email it:</li>  
			<li>Swk-mamclfcoord{at}mod.gov.uk</li>
            <li><i class="fas fa-vial"></i><a href="index_test.html">&nbsp;Try sending a test</a></li>
            <li><i class="fas fa-question-circle "></i><a href="about.html">&nbsp;About & Help</a></li>
            <li><i class="fas fa-info-circle"></i><a href="instructions.html">&nbsp;Instructions</a></li>
            <li><i class="fas fa-fighter-jet"></i><a href="https://notaminfo.com/ukmap/" title="See NOTAMs that have been published." target="_blank">&nbsp;NOTAMs</a></li>
            <li><i class="fas fa-edit"></i><a href="http://www.bhpa.co.uk/documents/safety/canp/" title="More detail at the BHPA on why CANP is there." target="_blank">&nbsp;BHPA Policy</a></li>
            <li><i class="fas fa-file"></i><a href="terms.html">&nbsp;Terms & Conditions</a></li>
            <li><i class="fas fa-user-secret"></i> <a href="privacy.html">&nbsp;Privacy Policy</a></li>
            <li><i class="fas fa-chart-bar"></i> <a href="stats.php">&nbsp;Usage Statistics</a></li>
            <li><i class="fas fa-map-signs"></i> <a href="coverage.html">&nbsp;Map with site guides</a></li>
            <li><i class="fas fa-list"></i><a href="bookings.php">&nbsp;Current/Future CANPs</a></li>
		  </div>
        </ul>
      </div>
      <div class="contact">
        <h3>All Upcoming CANP bookings</h3>
');

$sql = sprintf('SELECT Site, Scheduled, FromTime, ToTime, Reconciled, NOTAM, NOTAMlink FROM testCANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) ORDER BY Scheduled, Site');

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo('<table><caption><b>From today onward:</b></caption>');
    $notamlist = array();
    while($row = $result->fetch_assoc()) {
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]);
        $datebits = explode("-", $row["Scheduled"]);
        $frombits = $row["FromTime"];
        $tobits = $row["ToTime"];
        $orddate = date('D jS', mktime(0, 0, 0, $datebits[1], $datebits[2], $datebits[0]));
        if ($row["NOTAM"]) {
            // prep NOTAM field
            $notamentry = '<a href="' . $row["NOTAMlink"] . '" target="blank"> NOTAM</a>';
            echo('<tr><td>' . $sitebits[0] . '</td><td>' . $orddate . '</td><td>' . '  &nbsp;&nbsp;<i class="far fa-clock"></i> ' . $frombits . "  - " . $tobits . "</td><td>$notamentry</td></tr>");
            //echo('<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>');
            array_push($notamlist, $row["NOTAM"]);
            //move NOTAM to to new table at the bottom and anchor tag it.
            //add column for font awesome calendar check https://fontawesome.com/icons/calendar-check?style=regular or
            //https://fontawesome.com/icons?d=gallery&q=stamp
        } else {
            echo('<tr><td>' . $sitebits[0] . '</td><td>' . $orddate . '</td><td>' . '  &nbsp;&nbsp;<i class="far fa-clock"></i> ' . $frombits . "  - " . $tobits . '</td><td></td></tr>');
            //echo('<tr><td>&nbsp;</td><td>&nbsp;</td><td&nbsp;></td><td>&nbsp;</td></tr>');
        }

    }
    if (sizeof($notamlist) > 0) {
        foreach ($notamlist as $notam) {
            echo('<tr><td>&nbsp;</td><td>&nbsp;</td><td&nbsp;></td><td>&nbsp;</td></tr>');
            echo('<tr><td colspan="4"><small>' . $notam . '</small></td></tr>');
        }
    }
    echo('</table>');
} else {
    echo "No upcoming CANPs";
}

echo('	<br><br><small>This is a list of what has been sent through this utility/website. It does not include what may have been phoned through to Low Flying Coord. You&#39;d need to check <a href="https://notaminfo.com/ukmap/" target="_blank"> NOTAM Info</a> for that.</small>
      </div>
    </div>
  </div>
</body>
</html>
');

$conn->close();
?>
