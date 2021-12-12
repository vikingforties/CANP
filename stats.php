<?php
/*
List:
Bar graph of frequency through over time (days). jpgraph.net?
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
  <meta name="author" content="Peter Logan" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CANP for free fliers</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
  <link rel="stylesheet" href="styles/style.css">
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <div class="container">
    <h1 class="brand"><span>CANP</span> for free fliers</h1>
    <div class="wrapper animated fadeInLeft">
      <div class="company-info">
	    <h3>Civil Aircraft Notification Procedure</h3>
		<h5>Stats from the live database.</h5>
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
        <h3>Some Useful Statistics:</h3>
');

// Count the entries
$sql = sprintf('SELECT COUNT(*) AS Count FROM CANP');
$result = $conn->query($sql);
echo('Total number of CANPs is ');
$row = mysqli_fetch_assoc($result);
printf ("%s", $row["Count"]);
echo('<br>');

// Count the sites
$sql = sprintf('SELECT COUNT(*) AS Count FROM CANPsites');
$result = $conn->query($sql);
echo('Total number of sites is ');
$row = mysqli_fetch_assoc($result);
$sitecount = $row["Count"];
printf ("%s", $sitecount);
echo('<br>');

// Count the clubs
$sql = sprintf('SELECT COUNT( DISTINCT Club ) AS Count FROM CANPsites');
$result = $conn->query($sql);
echo('Total number of clubs is ');
$row = mysqli_fetch_assoc($result);
printf ("%s", $row["Count"]);
echo('<br>');

$sql = sprintf('SELECT DISTINCT Scheduled, COUNT( Scheduled ) AS Count FROM CANP GROUP BY Scheduled ORDER BY Count DESC LIMIT 1');
$result = $conn->query($sql);
echo('Most CANPs for one day is ');
$row = mysqli_fetch_assoc($result);
printf ("%s", $row["Count"]);
echo('<br>');

$sql = sprintf('SELECT round(avg(count), 1) FROM ( SELECT DISTINCT Scheduled, COUNT( Scheduled ) AS Count FROM CANP GROUP BY Scheduled ) as counts');
$result = $conn->query($sql);
echo('Mean CANPs per day is ');
$row = mysqli_fetch_assoc($result);
printf ("%s", $row['round(avg(count), 1)']);
echo('<br>');

$sql = sprintf('SELECT DATEDIFF(MAX(Scheduled), MIN(Scheduled)) / (COUNT(DISTINCT Scheduled) - 1) AS Count FROM CANP ');
$result = $conn->query($sql);
echo('Mean days between CANPs ');
$row = mysqli_fetch_assoc($result);
printf ("%s", round($row['Count'], 1));
echo('<br>');

$sql = sprintf('SELECT COUNT( DISTINCT MobileHash ) AS Count FROM CANP');
$result = $conn->query($sql);
echo('Number of users estimate ');
$row = mysqli_fetch_assoc($result);
printf ("%s", $row['Count']);
echo('<br>');

$sql = sprintf('SELECT COUNT( DISTINCT Site) AS Count FROM CANP');
$result = $conn->query($sql);
echo('Proportion of sites CANPed ');
$row = mysqli_fetch_assoc($result);
$proportion = round((100 / $sitecount) * $row['Count']);
printf ("%s", $proportion . "%");
echo('<br>');

$sqlall = sprintf('SELECT count(SCHEDULED) from CANP where EntryTime > "2020-12-31 12:48:50"'); // needs the % working out.
$resultall = $conn->query($sqlall);
$sqlrecon = sprintf('SELECT count(SCHEDULED) from CANP where Reconciled > "2020-12-31 12:48:50"');
$resultrecon = $conn->query($sqlrecon);
$rowall = mysqli_fetch_assoc($resultall);
$rowrecon = mysqli_fetch_assoc($resultrecon);
if ($rowall['count(SCHEDULED)'] > 0 && $rowrecon['count(SCHEDULED)'] > 0) {
    echo('NOTAM reconcile rate is ');
    printf ("%s", round((100 / $rowall['count(SCHEDULED)']) * $rowrecon['count(SCHEDULED)'], 0) . "%");
}
echo('<br><br>');

// Let's get a list of unique clubs
$sql = sprintf('SELECT DISTINCT Club, COUNT( Club ) AS Count FROM CANP GROUP BY Club ORDER BY Count DESC');
$result = $conn->query($sql);
// Then output
if ($result->num_rows > 0) {
    echo('<table><caption><b>CANPs per Club or School</b></caption>');
    while($row = $result->fetch_assoc()){
        echo('<tr><td>' . $row["Club"] . '</td><td>' . "&nbsp;&nbsp;&nbsp;" . $row["Count"] . '</td></tr>');
    }
    echo('</table>');
} else {
    echo "No clubs to show.";
}
echo('<br><br>');

// Let's get a list of unique sites
$sql = sprintf('SELECT DISTINCT Site, COUNT( Site ) AS Count FROM CANP GROUP BY Site ORDER BY Count DESC LIMIT 10');
$result = $conn->query($sql);
// Then output
if ($result->num_rows > 0) {
    //$counter = 0;
    echo('<table><caption><b>Top Ten CANPs per Site</b></caption>');
    while($row = $result->fetch_assoc()){
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]);
        echo('<tr><td>' . $sitebits[0] . '</td><td>' . "&nbsp;&nbsp;&nbsp;" . $row["Count"] . '</td></tr>');
        //$counter ++;
    }
    echo('</table>');
} else {
    echo "No sites to show.";
}

// Historical figures
echo "<h3>Historical Data</h3>";
$sql = sprintf('SELECT YEAR(Scheduled) As Year, COUNT(*) AS Tally FROM CANP GROUP BY YEAR(Scheduled) ');
$result = $conn->query($sql);
// Then output
if ($result->num_rows > 0) {
    //$counter = 0;
    echo('<table style="width:150px"><caption><b>Yearly Tally</b></caption>');
    while($row = $result->fetch_assoc()){
        echo('<tr><td>' . $row["Year"] . '</td><td>' . "&nbsp;&nbsp;&nbsp;" . $row["Tally"] . '</td></tr>');
    }
    echo('</table>');
} else {
    echo "No years to show.";
}

echo('
        <p><br><b>Monthly usage graph</b>
        <p><img src=statgraph.php alt="Monthly usage graph">
        <br><small></small>
      </div>
    </div>
  </div>
</body>
</html>
');

$conn->close();
?>
