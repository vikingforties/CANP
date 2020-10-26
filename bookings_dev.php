<?php
// Details: https://github.com/vikingforties/CANP
/* To Dos
Items to check on change to Live:
SQl statement DB name
<title>
<h1> page heading
<li> menu Send in CANP destination
<li> menu Bookings destination
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
  <title>dev CANP for free fliers</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
  <link rel="stylesheet" href="styles/style.css">
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <div class="container">
    <h1 class="brand"><span>dev CANP</span> for free fliers</h1>
    <div class="wrapper animated fadeInLeft">
      <div class="company-info">
	    <h3>Civil Aircraft Notification Procedure</h3>
		<h5>Always worth checking this page just to make sure you&#39;re not sending a duplicate booking in.</h5>
        <ul>
		  <div class="coinfo-links">
            <li><i class="fas fa-envelope"></i><a href="index_dev.html">&nbsp;Send in CANP</a></li>
            <li><i class="fas fa-vial"></i><a href="index_test.html">&nbsp;Try sending a test</a></li>
            <li><i class="fas fa-question-circle "></i><a href="about.html">&nbsp;About & Help</a></li>
            <li><i class="fas fa-info-circle"></i><a href="instructions.html">&nbsp;Instructions</a></li>
            <li><i class="fas fa-fighter-jet"></i><a href="https://notaminfo.com/ukmap/" target="_blank">&nbsp;NOTAMs</a></li>
            <li><i class="fas fa-edit"></i><a href="http://www.bhpa.co.uk/documents/safety/canp/" target="_blank">&nbsp;BHPA Policy</a></li>
            <li><i class="fas fa-phone"></i>&nbsp;0800 515544 Call it in</li>
            <li><i class="fas fa-file"></i><a href="terms.html">&nbsp;Terms & Conditions</a></li>
            <li><i class="fas fa-user-secret"></i> <a href="privacy.html">&nbsp;Privacy Policy</a></li>
            <li><i class="fas fa-chart-bar"></i> <a href="stats.php">&nbsp;Usage Statistics</a></li>
            <li><i class="fas fa-map-signs"></i> <a href="coverage.html">&nbsp;Map with site guides</a></li>
            <li><i class="fas fa-list"></i><a href="bookings_dev.php">&nbsp;Current/Future CANPs</a></li>
		  </div>
        </ul>
      </div>
      <div class="contact">
        <h3>All Upcoming CANP bookings</h3>
');

$sql = sprintf('SELECT Site, Scheduled, FromTime, ToTime FROM devCANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) ORDER BY Scheduled, Site');

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo('<table><caption><b>From today onward:</b></caption>');
    while($row = $result->fetch_assoc()) {
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]);
        $datebits = explode("-", $row["Scheduled"]);
        $frombits = $row["FromTime"];
        $tobits = $row["ToTime"];
        $orddate = date('D jS', mktime(0, 0, 0, $datebits[1], $datebits[2], $datebits[0]));
        echo('<tr><td>' . $sitebits[0] . '</td><td>' . "  - " . $orddate . '</td><td>' . '  &nbsp;&nbsp;<i class="far fa-clock"></i> ' . $frombits . "  - " . $tobits . '</td></tr>');
    }
    echo('</table>');
} else {
    echo "No upcoming CANPs";
}

echo('	<br><br><small>This is a list of what has been sent through this utility/website. It does not include what may have been phoned through to the Low Flying Booking Cell. You&#39;d need to check <a href="https://notaminfo.com/ukmap/" target="_blank"> NOTAM Info</a> for that.</small>
      </div>
    </div>
  </div>
</body>
</html>
');

$conn->close();
?>
