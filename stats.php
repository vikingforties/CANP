<?php
/*
List:
Bar graph of frequency through over time (days). jpgraph.net?
*/
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

$servername = "localhost";
$username = "------------";
// CHANGE ME when password rolls over!!
$password = "------------";
$dbname = "-------------";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo(' <!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta name="description" content="Weekday alerting form for free flying activity to military and civil pilots" />
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
            <li><i class="fa fa-envelope"></i><a href="index.html">&nbsp;Send in CANP</a></li>
            <li><i class="fas fa-vial"></i><a href="index_test.html">&nbsp;Try sending a test</a></li>
            <li><i class="fa fa-question-circle " ></i><a href="about.html"> About & Help</a></li>
            <li><i class="fa fa-info-circle"></i><a href="instructions.html">&nbsp;Instructions</a></li>
            <li><i class="fa fa-fighter-jet"></i><a href="https://notaminfo.com/ukmap/" target="_blank">&nbsp;NOTAMs</a></li>
            <li><i class="fa fa-edit"></i><a href="http://www.bhpa.co.uk/documents/safety/canp/" target="_blank">&nbsp;BHPA Policy</a></li>
			<li><i class="fa fa-phone"></i>&nbsp;0800 515544 Call it in</li>
			<li><i class="fa fa-file " ></i><a href="terms.html">&nbsp;Terms & Conditions</a></li>
			<li><i class="fa fa-file " ></i> <a href="privacy.html">&nbsp;Privacy Policy</a></li>
			<li><i class="fas fa-chart-bar"></i> <a href="stats.php">&nbsp;Usage Statistics</a></li>
			<li><i class="fas fa-map-signs"></i> <a href="coverage.html">&nbsp;Coverage</a></li>
			<li><i class="fa fa-list"></i><a href="bookings.php"> Current/Future CANPs</a></li>
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
echo('<br><br>');

// Let's get a list of unique clubs
$sql = sprintf('SELECT DISTINCT Club, COUNT( Club ) AS Count FROM CANP GROUP BY Club ORDER BY Count DESC');
$result = $conn->query($sql);
// Then output
if ($result->num_rows > 0) {
    echo('<table><caption><b>CANPs per Club</b></caption>');
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
    echo('<table><caption><b>Top ten CANPs per Site</b></caption>');
    while($row = $result->fetch_assoc()){
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]);
        echo('<tr><td>' . $sitebits[0] . '</td><td>' . "&nbsp;&nbsp;&nbsp;" . $row["Count"] . '</td></tr>');
        //$counter ++; 
    }
    echo('</table>');
} else {
    echo "No sites to show.";
}

echo('	<br><small></small>
      </div>
    </div>
  </div>
</body>
</html>
');

$conn->close();

/* Functions */
function connect($db, $user, $password){
    $link = @mysql_connect($db, $user, $password);
    if (!$link)
        die("Could not connect: ".mysql_error());
    else{
        $db = mysql_select_db(DB);
        if(!$db)
	    die("Could not select database: ".mysql_error());
	    else return $link;
    }
} 
?>