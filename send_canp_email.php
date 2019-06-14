<?php
// Could really do with sorting out code reuse here.

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Check whether we have a spoofed POST or bad data sent. 
// Safety First
$errorFlag = "";
$inclub = test_input($_POST["club"]);
if (!$inclub || !preg_match("/^[A-Za-z ]{5,40}$/", $inclub)) $errorFlag = "Club format error. $inclub";
$inlocation = test_input($_POST["location"]);
if (!$inlocation || !preg_match("/^[A-Za-z0-9 \.\(\)]{5,60}$/", $inlocation)) $errorFlag = "Site format error. $inlocation";
$indate = test_input($_POST["date"]);
if (!$indate || !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $indate)) $errorFlag = "Date format error. $indate";
$instarttime = test_input($_POST["starttime"]);
if (!$instarttime || !preg_match("/^[0-9]{2}:[0-9]{2}$/", $instarttime)) $errorFlag = "Start time format error. $instarttime";
$inendtime = test_input($_POST["endtime"]);
if (!$inendtime || !preg_match("/^[0-9]{2}:[0-9]{2}$/", $inendtime)) $errorFlag = "End time format error. $inendtime";
$ingliders = test_input($_POST["gliders"]);
if (!$ingliders || !preg_match("/^[0-9]{1,2}\+$/", $ingliders)) $errorFlag = "Glider format error. $ingliders";
$inphone = test_input($_POST["phone"]);
if (!$inphone || !preg_match("/^\+*[0-9 ]{11,15}$/", $inphone)) $errorFlag = "Phone format error. $inphone";
if ($_POST['email']) {
  $inemail = test_input($_POST["email"]);
  if (!$inemail || !filter_var($inemail, FILTER_VALIDATE_EMAIL)) $errorFlag = "Email format error. $inemail";
}

// Now get past the regex matches.


echo(' <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Weekday alerting form for free flying activity to military and civil pilots" />
  <meta name="keywords" content="CANP,NOTAM,RAF,Paragliding,HangGliding" />
  <meta name="author" content="Peter Logan" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CANP for free fliers</title>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
  <link rel="stylesheet" href="styles/style.css">
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
  <div class="container">
    <h1 class="brand"><span>CANP</span> for free fliers</h1>
    <div class="wrapper animated bounceInLeft">
      <div class="company-info">
	    <h3>Civil Aircraft Notification Procedure</h3>
		<h5>What to do:</h5>
        <ul>
		  <div class="coinfo-links">
            <li><i class="fa fa-envelope"></i><a href="index.html">&nbsp;Send in CANP</a></li>
            <li><i class="fa fa-fighter-jet"></i><a href="https://notaminfo.com/ukmap/" target="_blank"> NOTAMs</a></li>
            <li><i class="fa fa-edit"></i><a href="http://www.bhpa.co.uk/documents/safety/canp/" target="_blank"> BHPA Policy</a></li>
		    <li><i class="fa fa-question-circle-o"></i><a href="about.html"> About & Help</a></li>
			<li><i class="fa fa-phone"></i> 0800 515544 Call it in</li>
		  </div>
        </ul>
      </div>
      <div class="contact">
');

// Let's handle submission errors if there are any.
if($errorFlag) {
    echo("<p>The form contained answers with one or more errors or was used maliciously.</p>
    <p>$errorFlag</p>
    <p>Please try again with cleaner data formatting if this was a genuine attempt to submit.</p>
      </div>
    </div>
  </div>
</body>
</html>"
);
    exit("Exit without send.");
}

require('Mail.php');

// Put some handling in to auto select the Area Of Operation based on whether a site code is passed in the Location field. Not needed right now but could be used in the future.
if(preg_match("/\d{1,2}[.]\d{2,3}$/", $_POST['location'])) { 
  // $op_area = "1nm diameter & up to 1000ft agl temporary avoidance zone"; // set the same since thelarger area rarely gets turned down.
  $op_area = "2nm radius notification up to 2000ft";
} else {
  $op_area = "2nm radius notification up to 2000ft";
}


$from = "sender@somewhere";
/* Do specify the sender/from Email Address in above <**> field */
$to = "recipient@somewhere";
//$to = "pete@logans.me.uk";
/* Do specify the Recipient's Email Address in above <**> field */
$subject = "CANP booking";
$body = "New CANP notification for:\n";
$body .= "Club:              ".$_POST['club']."\n";
$body .= "Activity:          "."Hang/Paragliding"."\n";
$body .= "Location:          ".$_POST['location']."\n";
$body .= "Area of operation: ".$op_area."\n";
$body .= "Flying date:       ".$_POST['date']."\n";
$body .= "Local start time:  ".$_POST['starttime']."\n";
$body .= "Local finish time: ".$_POST['endtime']."\n";
$body .= "Expected gliders:  ".$_POST['gliders']."\n";
$body .= "Contact telephone: ".$_POST['phone']."\n";
if ($_POST['email']) {
  $body .= "Confirmation email:".$_POST['email']."\n";
}
$body .= "To the LFBC operator, please confirm this via mobile text or email, if supplied. \nIt should be noted that free fliers commonly operate up to cloud base in altitude.";

// Extract a grid ref
preg_match("/[A-Z][A-Z][0-9]{6}/", $_POST['location'], $gridref);

$bodyhtm = '<table style="width:100%">';
$bodyhtm .= "<tr><td><b>Club               </b></td><td>".$_POST['club']."</td></tr>";
$bodyhtm .= "<tr><td><b>Activity:          </b></td><td>"."Hang/Paragliding"."</td></tr>";
$bodyhtm .= "<tr><td><b>Location:          </b></td><td>".$_POST['location']. '   <i class="fa fa-map-marker"></i><a href=https://gridreferencefinder.com?gr=' . $gridref[0] . 'target="_blank">  map link</a></td></tr>';
$bodyhtm .= "<tr><td><b>Area of operation: </b></td><td>".$op_area."</td></tr>";
$bodyhtm .= "<tr><td><b>Flying date:       </b></td><td>".$_POST['date']."</td></tr>";
$bodyhtm .= "<tr><td><b>Local start time:  </b></td><td>".$_POST['starttime']."</td></tr>";
$bodyhtm .= "<tr><td><b>Local finish time: </b></td><td>".$_POST['endtime']."</td></tr>";
$bodyhtm .= "<tr><td><b>Expected gliders:  </b></td><td>".$_POST['gliders']."</td></tr>";
$bodyhtm .= "<tr><td><b>Contact telephone: </b></td><td>".$_POST['phone']."</td></tr>";
if ($_POST['email']) {
  $bodyhtm .= "<tr><td><b>Confirmation email:</b></td><td>".$_POST['email']."</td></tr>";
}
$bodyhtm .= "</table>";
echo("<h3>CANP booking sent: </h3>"."\n"."$bodyhtm"." ");


$host = "yourmailhost";
/* If your domain is using the same server as MX then keep it to localhost and if its using remote MX then change localhost to the server name */
$username = "someaccount";
/* Do specify the actual email account from the SMTP server */
$password = "someemail";

$headers = array ('From' => $from,
                  'To' => $to,
                  'Subject' => $subject);
$smtp = Mail::factory('smtp',
array ('host' => $host,
       'auth' => true,
       'username' => $username,
       'password' => $password));
$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
echo("<p>" . $mail->getMessage() . "</p>" . "
      </div>
    </div>
  </div>
</body>
</html>"
);
} else {
echo("<p>Look out for your confirmation from the Low Flying Booking Cell.</p>
      <p>Copy & paste this to your Telegram or WhatsApp group to let others know.</p>
      </div>
    </div>
  </div>
</body>
</html>"
);
}

/* Create a record of successfully sent entries to local text file. */
/* Cannot do this due to local file write security issues. Use DB instead. */
$club = $_POST['club'];
$site = $_POST['location'];
$mobhash = hash("sha256", $_POST['phone']);
$bodydb = "";
$bodydb .= "Club:              ".$_POST['club']."\n";
$bodydb .= "Activity:          "."Hang/Paragliding"."\n";
$bodydb .= "Location:          ".$_POST['location']."\n";
$bodydb .= "Area of operation: ".$op_area."\n";
$bodydb .= "Flying date:       ".$_POST['date']."\n";
$bodydb .= "Local start time:  ".$_POST['starttime']."\n";
$bodydb .= "Local finish time: ".$_POST['endtime']."\n";
$bodydb .= "Expected gliders:  ".$_POST['gliders']."\n";

$servername = "localhost";
$username = "username";
$password = "somepassword";
$dbname = "dbname";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = sprintf("INSERT INTO CANP (Club, Site, MobileHash, FullCANPtext) VALUES (\"$club\", \"$site\", \"$mobhash\", \"$bodydb\");");

if ($conn->query($sql) === TRUE) {
    echo ""; // Don't add msg to screen on success.
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

// And send a confirmation email to Pete just during testing phase.
// Redefine the To field to my address.
$to = "recipient@somewhere";
// Construct the email.
//$mail = $smtp->send($to, $headers, $body);
// Pass email to PEAR. 
/*if (PEAR::isError($mail)) {
    echo("<p>" . $mail->getMessage() . "</p>");
}
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

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?> 