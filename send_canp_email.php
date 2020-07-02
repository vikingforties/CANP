<?php
// Details: https://github.com/vikingforties/CANP 
// Dedicated live version - emails go to LFBC
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
$live = true;

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Lets reject unwanted requests.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $methodwas = $_SERVER['REQUEST_METHOD'];
    $sizewas = sizeof($_POST, 1);
    exit("Exit without return. $methodwas , $sizewas");
}
if (sizeof($_POST, 1) != 6) {
    $methodwas = $_SERVER['REQUEST_METHOD'];
    $sizewas = sizeof($_POST, 1);
    exit("Exit without return. $methodwas , $sizewas");
}
/*
// Do reCAPTCHA first
function getCaptcha($SecretKey){
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret="somelongstring"&response={$SecretKey}';
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $contents = curl_exec($ch);
    if (curl_errno($ch)) {
        $errorflag = "Could not reach reCAPTCHA for a response. ";
        $contents = '';
    } else {
      curl_close($ch);
    }
   
    if (!is_string($contents) || !strlen($contents)) {
        $errorflag = "Could not understand reCAPTCHA response. ";
        $contents = '';
    }
    // not supported by allow fopen in php.ini $Response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret="somelongstring"&response={$SecretKey}');
    $Return = json_decode($contents);
    return $Return;
}
$Return = getCaptcha($_POST['g-recaptcha-response']);
$Returntext = var_dump($Return);
if($Return->success == false && $Return->score < 0.5){
     $errorFlag = "Looks like you're a bot, sending duplicates or just attempting something malicious.";
}
*/

// Check whether we have a spoofed POST or bad data sent. 

$errorFlag = "";
$inenv = test_input($_POST["environment"]); // Check the right environment was sent.
$validenv = array("Hill","Power","Aerotow","Tow/Winch");
if (!in_array($inenv, $validenv)) $errorFlag = "Unexpected environment sent. $inenv"; 

$inclub = test_input($_POST["club"]);  // Check that one from the list of clubs has been used.
$validclubs = array("Avon","Cayley","Condors","Cumbria","Dales","Derbyshire","Dover + Folkestone","East Anglia Coastal","Lanarkshire + Lothian","Long Mynd","Malvern","Mid Wales","North Devon","North Yorks","Pennine","Peak","SE Wales","Sky Surfing","Snowdonia","South Devon","Southern","Thames Valley","Welsh Borders","Wessex","Cambridgeshire","Green Dragons","Midland","Rutland","Suffolk","Ufly4fun","XClent");
if (!in_array($inclub, $validclubs)) $errorFlag = "Unexpected club sent. $inclub";
// if (!$inclub || !preg_match("/^[A-Za-z ]{4,40}$/", $inclub) || !in_array($inclub, $validclubs)) $errorFlag = "Unexpected club sent. $inclub";

$inlocation = test_input($_POST["location"]);
$validlocation = array("Cam Long Down ST773995","Frocester SO794012","Long Knoll ST796377","Mere Coward's Bowl ST806345","Mere Morgan's Ridge ST803354","Mere Rifle Range ST811344","Mere Spencer's Bowl ST802350","Selsley Common SO827032",
    "Westbury/Bratton Camp ST899516","Sutton Meadows TL404784","Horcum SE846940","Millington SE834514","Batcombe (Telegraph Hill) ST642051","Beer Head SY227878","Bossington SS903485","Countisbury SS748501","East Hill SY112926","Eype SY451916",
    "North Hill SS952478","Selworthy SS904489","Barkin Fell SD675870","Barton Fell NY463212","Bewaldeth NY211362","Black Combe (East) SD149850","Black Combe (South) SD131828","Black Combe (West) SD119862","Bleansley SD204895",
    "Blease Fell NY306261","Brigsteer SD488892","Burlington SD251844","Burnbank NY112213","Burney Fell SD259855","Buttermere Moss NY187169","Carrock Fell NY348338","Clough Head NY328231","Coniston Old Man SD276974","Corney Fell SD138920",
    "Ellerside SD354805","Farleton Knott SD538804","Great Langdale NY285073","Haig Pit NX965177","Humphrey Head SD392735","Jenkin Hill NY279264","Latrigg NY277246","Lowca NX980215","Murton Pike NY729219","Rebecca SD230784",
    "Sandbeds/West Fell NY326364","Silecroft SD118815","Souther Fell NY355291","St. Bees NX962117","Swinside NY174243","Troutbeck NY423032","Ullock Pike NY240304","Walla Crags NY277214","Wastwater Screes NY135025","Whinfell SD588999",
    "Whitbarrow Scar SD455850","Whitestones SD635984","Wolf Crags NY362223","Wrynose Pass NY279030","Addingham Moorside SE078471","Addlebrough SD948880","Baildon SE145404","Bishopdale SD950803","Brant Side SD778866","Cow Close Fell SD890732",
    "Cowling + Sutton Pinnacles SD988430","Dodd Fell + Grove Head SD829838","Fremington Edge NZ046003","Great Whernside SD998751","Ilkley Moor SE135464","Kettlewell SD967711","Kilnsey SD971679","Nappa Scar SD960925","Semer Water SD909880",
    "Stags Fell SD870927","Tailbridge NY804050","Wether Fell SD868867","Whernside SD725788","Windbank/Hawkswick SD966704","Black Knoll SO393887","Camlo SO028705","Clatter SO004964","Clunbury SO375801","Corndon SO310967","Lanfawr SO295967",
    "Lawley SO493974","Llandinam SO045884","Long Mynd SO404919","Sarn SO227901","Shepherds Tump SO154653","Wrekin SJ631085","Black Hill SO767407","Builth Wells SO081523","Castlemorton SO762386","Croft Farm SO908430","Leckhampton SO955185",
    "Much Marcle SO630329","Perseverance Hill SO769435","Pinnacle Hill (Kettle Sings) SO768421","Worcestershire Beacon SO769451","Sibson SK364010","Borth SN595868","Cardigan (Poppit) SN138493","Cemmaes SH865054","Constitution Hill SN584828",
    "Craig y Pistyll SN715863","Disgwylfa Fawr SN734845","Elan Valley SN909718","Fairbourne SH612117","Ffarmers SN651479","Frenni Fawr SN200350","Llanddewi Brefi SN653541","Moel Fadian SN 832951","Pennant SN869963","Pen Dinas SN584802",
    "Busby Moor (Model Ridge) NZ525033","Captain Cooks Monument NZ598101","Carlton Bank NZ519025","Cringle Moor NZ535034","Huntcliffe (Saltburn) NZ671215","Marske Sand Dunes NZ640228","Summer Hill NZ593114","Sutton Bank SE514830","Edenfield SD818179",
    "Nont Sarahs SE018137","Parlick SD596451","Pendle SD782403","Pule SE033104","Abertysswg SO141059","Bedlinog SO100035","Bleanavon SO218098","Blorenge SO280129","Cwmbran Mountain Air ST268978","Cwmbran Ty-canol ST262955","Cwmparc SS932951",
    "Ebbw Vale SO168065","Fochriw SO098048","Garth Hill ST113837","Garway SO436250","Gelligaer SO116015","Hay Bluff SO245365","Machen ST225900","Merthyr SO078036","Mynydd Meio ST114883","Nant-y-Moel SS944938","Pandy SO319243","Pontlottyn SO116051",
    "Pen Cerrig Calch SO220216","Rhigos SN926031","Skirrid Fawr SO330180","Sugar Loaf SO271166","Talybont SO058191","Butser SU712203","Harting SU805184","Mercury SU675197","Whitewool SU650204","Arenig Fawr SH824368","Bochlwyd/Cwm Idwal SH655595",
    "Conwy Mountain SH760778","Elidir Fach (NW/W) SH604613","Elidir Fach (W/SW) SH604613","Foel Lus SH733763","Foel Lus (NE) SH733763","Glyderau SH663566","Harlech SH595315","Harlech Cliff (Allt y Mor) SH574295","Ice cream (Viewpoint) SH659542",
    "Moel Berfedd SH652557","Moel Eilio (Conwy Valley) SH749658","Moel Eilio (N/NW) SH566577","Moel Eilio (W/SW) SH566577","Moel Siabod SH705546","Moel Wnion SH649697","Moel y Ci SH591661","Nant Gwrtheyrn SH350444","Pen yr Helgi Du (E) SH698630",
    "Pen yr Helgi Du (W) SH698630","Penmaenbach SH748781","Station 4 SH648548","Trefor SH388474","Tremadog SH555408","Battern Cliffs SX330541","Beesands SX817400","Belstone SX613906","Chinkwell SX729780","Corn Ridge SX553895","Cox Tor SX529764",
    "Dizzard SX175994","Freathy SX401518","King Tor SX709815","Maker SX438515","Meldon Hill SX695862","Polhawn Fields SX422497","Sandymouth, Bude SS204098","Sourton Tor SX546896","Strete SX835458","Struddick Farm SX295545","Trethill Cliffs SX371536",
    "Widgery Cross SX539857","Beachy Head TV591958","Bo Peep TQ500043","Devils Dyke TQ258111","Ditchling TQ325132","Firle TQ478059","High and Over TQ510011","Mount Caburn TQ444089","Newhaven TQ445001","Combe Gibbet SU362623","Golden Ball SU127638",
    "Liddington SU208798","Milk Hill SU101644","Milk Hill White Horse SU100637","Rybury SU084637","Sugar SU238786","Tan SU085646","Uffington White Horse SU302868","White Hill SU518566","Ballard Down/Cliff SZ038812","Barton on Sea SZ241929",
    "Bell Hill ST798085","Bulbarrow ST772058","Corton Denham ST633233","Cowdown Hill SY639999","Friar Waddon SY641854","Kimmeridge SY926795","Knitson SZ006812","Maiden Castle SY669886","Marleycombe SU023226","Monk’s Down ST939207",
    "Okeford Hill ST813095","Portland East SY703718","Portland West SY685728","Ringstead Bay SY758823","Southbourne SZ130913","St.Aldhelm’s Head SY958769","Swallowcliffe Down ST975256","White Horse SY715844","Whitesheet ST936237","Winkelbury ST950214",
    "Hundred House SO085527","Cravens Gorse SP052240","Wingland Airfield TF426261","North Luffenham SK943045","Crete Road TR199382","Herne Bay TR212689","Minster TQ958736","The Warren TR259385","Thurnham TQ806582","Broadlee Bank SK112860",
    "Cats Tor SJ996750","Cocking Tor SK347607","Curbar SK260750","Dale Head SK096838","Eyam + Bradwell SK195790","Lords Seat + Rushup SK113840","Mam Tor, Treak + Long Cliff SK141831","Stanage SK242843","Cats Tor SJ994758","Shining Tor SJ994737",
    "Bunster SK140514","High Wheeldon SK100659","Roaches SK001640","Edge top SK055657","Ecton Hill SK099579","High Edge SK060690","Wetton Hill SK112566","Chelmorton SK116708","Winter Hill SD659149","Longridge Fell SD657410","Chalton SU735150",
    "Mendlesham TM135635","Woldingham TQ376564","Corton TM547961","Hunstanton TF678423","Julie's Field, Mundesley TG318362","Mundesley TG300377","Rocky Bottoms TG190429","Weybourne TG112436","Codden Hill SS584296","Cornborough SS417288",
    "Putsborough SS440407","Trentishoe SS628479","Woolacombe SS458426", "Abington NS933236","Aonach Mor NN188762","Bishop Hill NO177029","Broughton Heights NT122414","Campsie Fells NS620800","Dumyat NS832983","Dungavel NS931305","Fairlie NS221534",
    "Glencoe NN258521","Hillend NT243666","Moorfoots NT348544","Myreton NS856979","Stronend NS613899","Thornhill NX920985","Tinto - North NS924345","Tinto - South NS929343","Meall nan Tarmachan NN590382","Beinn Ghlas NN622397","Ben Ledi NN561098");
if (!$inlocation || !in_array($inlocation, $validlocation)) $errorFlag = "Site format error. $inlocation";
// Check that one from the list of sites has been used.

$indate = test_input($_POST["date"]);
$validdates = array();
$daysahead = 0;
while ($daysahead < 7) {
    $daystring = ("+" . $daysahead . "days");
    $newday = date('Y-m-d',strtotime($daystring));
    if ((date('w', strtotime($newday)) % 6 == 0) == false) array_push($validdates, $newday);
    $daysahead ++;
}
$somedates = json_encode($validdates);
$week = date('w', strtotime($newday)) % 6 == 0;
if (!$indate || !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $indate) || !in_array($indate, $validdates)) $errorFlag = "Date format error. $indate";
// Check that its today or a few days in the future that's been used and not a weekend.

//  Check that the number sent matches the list of options given.
$inphone = test_input($_POST["phone"]);
$inphone = preg_replace('/\s+/', '', $inphone); // Remove middle white space.
if (!$inphone || !preg_match("/^\+*(0|44)7[0-9]{9}$/", $inphone)) $errorFlag = "Mobile phone format error. $inphone";
// Check there's a 7 near the front and then ten digits after it. Preceded by 0 or 44.

$inemail = test_input($_POST["email"]);
if (!$inemail || !filter_var($inemail, FILTER_VALIDATE_EMAIL)) $errorFlag = "Email format error. $inemail";
// Now got past the regex matches.

// DB Read for error conditions
$servername = "localhost";
$username = "----------------";
// CHANGE ME when password rolls over!!
$password = "--------------------";
$dbname = "-------------";
$conn = new mysqli($servername, $username, $password, $dbname);
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
                $errorFlag = "SNAP! It looks like someone else already CANPed that site. <br>Check the All Upcoming CANPs menu option for the full listing.";
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
  
  <meta name="description" content="Weekday alerting form for free flying activity to military and civil pilots" />
  <meta name="keywords" content="CANP,NOTAM,RAF,Paragliding,HangGliding" />
  <meta name="author" content="Peter Logan" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CANP for free fliers</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
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
            <li><i class="fas fa-map-signs"></i> <a href="coverage.html">&nbsp;Coverage</a></li>
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

$op_area = "2nm radius up to 2000ft agl";
$dateparts = explode("-", $_POST['date']);
$flipdate = $dateparts[2] . "-" . $dateparts[1] . "-" . $dateparts[0];
$timing = "Sunrise to sunset";
$gliders = "5+";

// Email to LFBC
$from = "-----------";
/* Do specify the sender/from Email Address in above <**> field */
//$to = "------------------";
//$to = $_POST['email'];
$to = "--------------";
if ($live && $_POST['email'] == '--------------' && $_POST['location'] == 'Baildon SE145404') {
    $to = "------------------";
}
/* Do specify the Recipient's Email Address in above <**> field */
$subject = "CANP booking";
$body = "New CANP notification for:\n";
$body .= "Club:                 ".$_POST['club']."\n";
if($inenv == "Tow/Winch") {
    $body .= "Activity:             "."Winch-launched hang/paragliding will take place"."\n";
} else if ($inenv == "Power") {
    $body .= "Activity:             "."Foot-launched powered flying will take place"."\n";
} else if ($inenv == "Aerotow") {
    $body .= "Activity:             "."Aerotow-launched hang gliding will take place"."\n";
} else {
    $body .= "Activity:             "."Hill-launched hang/paragliding will take place"."\n";
}
$body .= "Location:             ".$_POST['location']."\n";
$body .= "Area of operation:    ".$op_area."\n";
$body .= "Flying date:          ".$flipdate."\n";
$body .= "Schedule:             ".$timing."\n";
$body .= "Expected gliders:     ".$gliders."\n";
$body .= "Contact telephone:    ".$_POST['phone']."\n";
$body .= "Confirmation email:     ".$_POST['email']."\n";
$body .= "To the LFBC operator, please confirm this via email reply. Approval has been given by OC LFOF for notifications to come through in this format for hang/paragliders, not the Excel sheet.";

// Extract a grid ref
preg_match("/[A-Z][A-Z][0-9]{6}/", $_POST['location'], $gridref);
// To screen for pilot to cut and paste. 
$bodyhtm = '<table style="width:100%">';
$bodyhtm .= "<tr><td><b>Club:              </b></td><td>".$_POST['club']."</td></tr>";
if($inclub == "Tow/Winch") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>"."Winch-launched hang/paragliding will take place"."</td></tr>";
} else if ($inclub == "Power") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>"."Foot-launched powered flying will take place"."</td></tr>";
} else if ($inclub == "Aerotow") {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>"."Aerotow-launched hang gliding will take place"."</td></tr>";
} else {
    $bodyhtm .= "<tr><td><b>Activity:          </b></td><td>"."Hill-launched hang/paragliding will take place"."</td></tr>";
}
$bodyhtm .= "<tr><td><b>Location:          </b></td><td>".$_POST['location']."</td></tr>";
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
$host = "mail.logans.me.uk";
/* If your domain is using the same server as MX then keep it to localhost and if its using remote MX then change localhost to the server name */
$username = "----------------";
/* Do specify the actual email account from the SMTP server */
$password = "--------------------";
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
echo("<p>Look out for your confirmation from the Low Flying Booking Cell.</p>
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
$fromtime = "Sunrise";
$totime = "Sunset";
$bodydb = "";
$bodydb .= "Club:              ".$_POST['club']."\n";
if($inclub == "Tow/Winch") {
    $bodydb .= "Activity:             "."Winch-launched hang/paragliding will take place"."\n";
} else if ($inclub == "Power") {
    $bodydb .= "Activity:             "."Foot-launched powered flying will take place"."\n";
} else if ($inclub == "Aerotow") {
    $bodydb .= "Activity:             "."Aerotow-launched hang gliding will take place"."\n";
} else {
    $bodydb .= "Activity:             "."Hill-launched hang/paragliding will take place"."\n";
}
$bodydb .= "Location:          ".$_POST['location']."\n";
$bodydb .= "Area of operation: ".$op_area."\n";
$bodydb .= "Flying date:       ".$_POST['date']."\n";
$bodydb .= "Schedule:          ".$timing."\n";
$bodydb .= "Expected gliders:  ".$gliders."\n";

$servername = "-------";
$username = "-------------";
// CHANGE ME when password rolls over!!
$password = "------------";
$dbname = "-----------------";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
// NO NEED TO SEND AN ACTUAL DB ENTRY
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = sprintf("INSERT INTO CANP (Club, Site, Scheduled, MobileHash, EmailHash, FullCANPtext, FromTime, ToTime) VALUES (\"$club\", \"$site\", \"$scheduled\", \"$mobhash\", \"$emailhash\", \"$bodydb\", \"$fromtime\", \"$totime\");");

// Don't store to DB if it's the tester.
if ($live && $_POST['email'] == '----------------' && $_POST['location'] == 'Baildon SE145404') {
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