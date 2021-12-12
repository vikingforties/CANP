<?php
// Details: https://github.com/vikingforties/CANP
// Dedicated live version - emails go to LFC
//
/* To Dos
Items to check on change to Live:
Where the form posts to
SQl statement DB name
<title>
<h1> page heading
<li> menu Send in CANP destination
<li> menu Bookings destination
emails destinations for test and live
*/
//Set environment
include("validation.php");
include("config.php");
$test = false;
$live = true;
$dev = false;

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Lets reject unwanted requests.
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['recaptcha_response'])) {
    $methodwas = $_SERVER['REQUEST_METHOD'];
    $sizewas = sizeof($_POST, 1);
    exit("Exit without return. $methodwas , $sizewas");
}
if (sizeof($_POST) != 9) {
    $methodwas = $_SERVER['REQUEST_METHOD'];
    $sizewas = sizeof($_POST);
    exit("Exit without return. $methodwas , $sizewas");
}

// Check whether we have a spoofed POST or bad data sent.array_push($validdates, $newday);
$errorFlag = "";
$inenv = test_input($_POST["environment"]); // Check the right environment was sent.
$validenv = array("Hill","Power","Aerotow","Tow/Winch");
if (!in_array($inenv, $validenv)) $errorFlag = "Unexpected environment sent. $inenv";

$inclub = test_input($_POST["club"]);  // Check that one from the list of clubs has been used.
// $validclubs brought in from include file.
if (!in_array($inclub, $validclubs)) $errorFlag = "Unexpected club sent. $inclub";
// if (!$inclub || !preg_match("/^[A-Za-z ]{4,40}$/", $inclub) || !in_array($inclub, $validclubs)) $errorFlag = "Unexpected club sent. $inclub";

$inlocation = test_input($_POST["location"]);
// $validlocation brought in from include file.
if (!$inlocation || !in_array($inlocation, $validlocation)) $errorFlag = "Site format error. $inlocation";
// Check that one from the list of sites has been used.

$indate = test_input($_POST["date"]);
$validdates = array();
$daysahead = 0;
while ($daysahead < 7) {
    $daystring = ("+" . $daysahead . "days");
    $newday = date('Y-m-d',strtotime($daystring));
    //if ((date('w', strtotime($newday)) % 6 == 0) == false) array_push($validdates, $newday);
    array_push($validdates, $newday);
    $daysahead ++;
}
$somedates = json_encode($validdates);
$week = date('w', strtotime($newday)) % 6 == 0;
if (!$indate || !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $indate) || !in_array($indate, $validdates)) $errorFlag = "Date format error. $indate";
// Check that its today or a few days in the future that's been used and not a weekend.

if ($_POST["starttime"]) {
    $instarttime = test_input($_POST["starttime"]);
    if (!$instarttime || !is_object(DateTime::createFromFormat('H:i', $_POST["starttime"]))) $errorFlag = "Invalid start time given. $instarttime";
}

if ($_POST["endtime"]) {
    $inendtime = test_input($_POST["endtime"]);
    if (!$inendtime || !is_object(DateTime::createFromFormat('H:i', $_POST["endtime"]))) $errorFlag = "Invalid end time given. $inendtime";
}

if ($_POST["starttime"] > $_POST["endtime"]) $errorFlag = "Start time is later than end time. " . $_POST["starttime"] . " & " . $_POST["endtime"];

//  Check that the number sent matches the list of options given.
$inphone = test_input($_POST["phone"]);
$inphone = preg_replace('/\s+/', '', $inphone); // Remove middle white space.
if (!$inphone || !preg_match("/^\+*(0|44)7[0-9]{9}$/", $inphone)) $errorFlag = "Mobile phone format error. $inphone";
// Check there's a 7 near the front and then ten digits after it. Preceded by 0 or 44.

$inemail = test_input($_POST["email"]);
if (!$inemail || !filter_var($inemail, FILTER_VALIDATE_EMAIL)) $errorFlag = "Email format error. $inemail";
// Now got past the regex matches.

// Send in the reCAPTCHA request - left until last because this is a harder check.
// Build POST request:
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_response = $_POST['recaptcha_response'];
// Make and decode POST request. file_get_contents don't work on ISP
// $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
$verifydata = array(
            'secret' => "$recaptcha_secret",
            'response' => "$recaptcha_response"
        );
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $recaptcha_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifydata));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$recaptcha = curl_exec($ch);
curl_close ($ch);
$recaptcha = json_decode($recaptcha);
// Take action based on the score returned:
if ($recaptcha->score <= 0.59) { // tune this value if start getting bot posts
    $errorFlag = "reCAPTCHA score low. Contact the site owners if you're not a bot.";
}

// We've got past checking. Replace plusses with ampersands to be human readable.
$_POST["club"] = str_replace("+", "&", $_POST["club"]);
$_POST["location"] = str_replace("+", "&", $_POST["location"]);

// DB Read for error conditions, using credentials from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check flag mechanism - we're not under attack
$flagsql = sprintf("SELECT Status FROM FlagCANP");
$status = $conn->query($flagsql);
$flag = $status->fetch_assoc();
$timenow = time();
$notifications = array();
if ($flag['Status'] == 1) {
    $errorFlag = "Too many posts. Service halted to prevent attack.";
} else {
    $sql = sprintf("SELECT * FROM CANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) LIMIT 25");
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($notif = $result->fetch_assoc()) {
            if ($notif['Site'] == $_POST["location"] && $notif['Scheduled'] == $_POST["date"]) {
                $errorFlag = "SNAP! It looks like someone else already CANPed that site. <br>Check the Current/Future CANPs menu option for the full listing.";
                array_push($notifications, $notif);
            }
        }
    }
    /* Check for time period for latest three
        if there have been 3 in the last minute
            do Submit shutdown with flag
            set $error
        elif 3 in the last hour send me a warning email
        plus put flag check on submit button in form
    $shallflag = 0;
    foreach($notifications as $entry) {
        $timediff = strtotime($entry['EntryTime']) - $timenow;
        if ($timediff <= 60) {
            $shallflag += 1;
        }
    }
    if ($shallflag >= 2) {
        $updatesql = sprintf('UPDATE `FlagCANP` SET `Status`= 1');
        $conn->query($updatesql);
        $errorFlag = "Entries too frequent. Possible DoS. Temporary shutdown of servce.";
    }  */

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
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
  <link rel="stylesheet" href="styles/style.css">
  <link rel="shortcut icon" href="favicon.ico" />
  <script src="https://www.google.com/recaptcha/api.js?render=6LfwdtkUAAAAAF9TBVjv1AUUTkTxuOsctzus2Ho3"></script>
  <script>
      grecaptcha.ready(function () {
          grecaptcha.execute("6LfwdtkUAAAAAF9TBVjv1AUUTkTxuOsctzus2Ho3", { action: "contact" }).then(function (token) {
              var recaptchaResponse = document.getElementById("recaptchaResponse");
              recaptchaResponse.value = token;
          });
      });
  </script>
</head>
<body>
  <div class="container">
    <h1 class="brand"><span>CANP</span> for free fliers</h1>
    <div class="wrapper animated fadeInLeft">
      <div class="company-info">
	    <h3>Civil Aircraft Notification Procedure</h3>
		<h5>What to do:</h5>
        <ul>
		  <div class="coinfo-links">
            <li><i class="fas fa-envelope"></i><a href="index.html">&nbsp;Send in CANP</a></li>
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
            <li><i class="fas fa-list"></i><a href="bookings.php">&nbsp;Current/Future CANPs</a></li>
		  </div>
        </ul>
      </div>
      <div class="contact">
');

// Let's handle submission errors if there are any.
if($errorFlag) {
    echo("<p>The form contained answers with one or more errors or duplicated an existing entry.</p>
    <p>$errorFlag</p>
    <p>Please try again with different options.</p>
      </div>
    </div>
  </div>
</body>
</html>"
);
    $conn->close();
    exit("Exit without send.");
}

require('Mail.php');

$location = $_POST['location'];
// Retreive full site details from CANPsites
$sitesql = sprintf("SELECT * FROM CANPsites WHERE UniqueSite = \"$location\" LIMIT 1");
$siteresult = $conn->query($sitesql);
if ($result->num_rows > 0) {
    $row = $siteresult->fetch_assoc();
    if (is_array($row)) {  // just testing we got something back we can work with.
        $location = ($row["SiteAddress"]);
    }
}
$op_area = "2nm radius up to 2000ft agl";
$dateparts = explode("-", $_POST['date']);
$flipdate = $dateparts[2] . "-" . $dateparts[1] . "-" . $dateparts[0];
if ($_POST["starttime"]) {
   $timing = $_POST["starttime"] . " to " . $_POST["endtime"];
	$fromtime = $_POST["starttime"];  // for DB entry.
   $totime = $_POST["endtime"];
} else {
	$timing = "Dawn to dusk";
	$fromtime = "Dawn";  // for DB entry.
   $totime = "dusk";
}
$gliders = "5+";
$winchtext = "Winch-launched hang/paragliding will take place";
if($_POST['location'] == "Mendlesham TM135635") $winchtext = "Winch-launched hang gliding will take place";
$powertext = "Foot-launched powered flying will take place";
$towtext = "Aerotow-launched hang gliding will take place";
$hilltext = "Hill-launched hang/paragliding will take place";

// Email to LFC
$from = "logansm@viking.eukhosting.net";
/* Do specify the sender/from Email Address in above <**> field */
//$to = "peter.logan@intel.com";
//$to = $_POST['email'];
$to = "Swk-mamclfcoord@mod.gov.uk";
if ($live && $_POST['email'] == 'pete@logans.me.uk' && $_POST['location'] == 'Baildon SE145404') {
    $to = "pete@logans.me.uk";
}
/* Do specify the Recipient's Email Address in above <**> field */
$subject = "CANP booking";
$body = "New CANP notification for:\n";
$body .= "Club:                 ".$_POST['club']."\n";
if($inenv == "Tow/Winch") {
    $body .= "Activity:             ".$winchtext."\n";
} else if ($inenv == "Power") {
    $body .= "Activity:             ".$powertext."\n";
} else if ($inenv == "Aerotow") {
    $body .= "Activity:             ".$towtext."\n";
} else {
    $body .= "Activity:             ".$hilltext."\n";
}
$body .= "Location:             ".$location."\n";
$body .= "Area of operation:    ".$op_area."\n";
$body .= "Flying date:          ".$flipdate."\n";
$body .= "Schedule:             ".$timing."\n";
$body .= "Expected gliders:     ".$gliders."\n";
$body .= "Contact telephone:    ".$_POST['phone']."\n";
$body .= "Confirmation email:     ".$_POST['email']."\n";
$body .= "To the LFC operator, please confirm this via email reply. Approval has been given by OC Low Flying for notifications to come through in this format for hang/paragliders, not the Excel sheet.";

// Extract a grid ref
preg_match("/[A-Z][A-Z][0-9]{6}/", $_POST['location'], $gridref);
// To screen for pilot to cut and paste.
$bodyhtm = '<table style="width:100%">';
$bodyhtm .= "<tr><td><b>Club:              </b></td><td>".$_POST['club']."</td></tr>";
if($inenv == "Tow/Winch") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>".$winchtext."</td></tr>";
} else if ($inenv == "Power") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>".$powertext."</td></tr>";
} else if ($inenv == "Aerotow") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>".$towtext."</td></tr>";
} else {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>".$hilltext."</td></tr>";
}
$bodyhtm .= "<tr><td><b>Location:          </b></td><td>".$location."</td></tr>";
$bodyhtm .= "<tr><td><b>Area of operation: </b></td><td>".$op_area."</td></tr>";
$bodyhtm .= "<tr><td><b>Flying date:       </b></td><td>".$flipdate."</td></tr>";
$bodyhtm .= "<tr><td><b>Local start time:  </b></td><td>".$timing."</td></tr>";
$bodyhtm .= "<tr><td><b>Expected gliders:  </b></td><td>".$gliders."</td></tr>";
$bodyhtm .= "<tr><td><b>Contact telephone: </b></td><td>".$_POST['phone']."</td></tr>";
//$bodyhtm .= "<tr><td><b>reCAPTCHA score:   </b></td><td>".$Returntext."</td></tr>";
$bodyhtm .= "<tr><td><b>Confirmation email:</b></td><td>".$_POST['email']."</td></tr>";
$bodyhtm .= "</table>";
$bodyhtm .= "<p>Check your submitted grid ref location here: ".'<i class="fas fa-map-marker"></i><a href=https://gridreferencefinder.com?gr=' . $gridref[0] . ' target="_blank">  map link</a></p>';

echo("<h3>CANP booking sent: </h3>"."\n"."$bodyhtm"." ");

// Send the email here.
// Credentials from config.php file.
if ($_POST['email']) {
    $headers = array ('From' => $from,
                      'To' => $to,
                      'Subject' => $subject,
                      'Reply-To' => $_POST['email']);
} else {
    $headers = array ('From' => $from,
                      'To' => $to,
                      'Subject' => $subject);
}

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
echo("<p>Look out for your confirmation from the Low Flying Coord.</p>
      <p>Copy & paste this to your Telegram or WhatsApp group to let others know.</p>
      </div>
    </div>
  </div>
</body>
</html>"
);
}

/* Send to DB */
$club = $_POST['club'];
$site = $_POST['location'];
$scheduled = $_POST['date'];
$mobhash = hash("sha256", $_POST['phone']);
$emailhash = hash("sha256", $_POST['email']);
$bodydb = "";
$bodydb .= "Club:              ".$_POST['club']."\n";
if($inenv == "Tow/Winch") {
    $bodydb .= "Activity:             ".$winchtext."\n";
} else if ($inenv == "Power") {
    $bodydb .= "Activity:             ".$powertext."\n";
} else if ($inenv == "Aerotow") {
    $bodydb .= "Activity:             ".$towtext."\n";
} else {
    $bodydb .= "Activity:             ".$hilltext."\n";
}
$bodydb .= "Location:          ".$location."\n";
$bodydb .= "Area of operation: ".$op_area."\n";
$bodydb .= "Flying date:       ".$_POST['date']."\n";
$bodydb .= "Schedule:          ".$timing."\n";
$bodydb .= "Expected gliders:  ".$gliders."\n";

$sql = sprintf("INSERT INTO CANP (Club, Site, Scheduled, MobileHash, EmailHash, FullCANPtext, FromTime, ToTime) VALUES (\"$club\", \"$site\", \"$scheduled\", \"$mobhash\", \"$emailhash\", \"$bodydb\", \"$fromtime\", \"$totime\");");

// Don't store to DB if it's the tester.
if ($live && $_POST['email'] == 'pete@logans.me.uk' && $_POST['location'] == 'Baildon SE145404') {
    // do nothing
} else {
    if ($conn->query($sql) === TRUE) {
        echo ""; // Don't add msg to screen on success.
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();

/* Functions */

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
