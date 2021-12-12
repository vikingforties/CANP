<?php
// Details: https://github.com/vikingforties/CANP
/* To Dos

*/
include("config.php");

error_reporting(E_ALL);
ini_set('display_errors', True);

$path = '/home/logansm/php';
$errorFlag = "";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Lets reject unwanted requests.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $errorflag = ("Exit, bad method.");
}
if (sizeof($_GET, 1) != 1) {
    $errorflag = ("Exit, too many params.");
}
if (!isset($_GET['Gridref'])) $errorflag = "No param provided.";

echo "3<br>";
// Format check of the OS grid ref
$inGR = test_input($_GET['Gridref']); // Check the link sent.
if (!preg_match("/[A-Z]{2}[0-9]{6}/", $inGR)) $errorFlag = "Unexpected grid ref format sent. $inGR";

// Let's handle submission errors if there are any.
if($errorFlag) {
    echo("$errorFlag");
    exit("");
}

// DB Read for error conditions, using credentials from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = sprintf("SELECT SiteName, Club, Environment1 FROM CANPsites WHERE OSgridref = '$inGR' LIMIT 1");

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["Environment1"] . " " . $row["Club"] . " " . $row["SiteName"] . "<br>";
    }
} else {
    echo "No matching site found.";
}
$conn->close();

/* Functions */
/*function connect($db, $user, $password){
    $link = @mysql_connect($db, $user, $password);
    if (!$link)
        die("Could not connect: ".mysql_error());
    else{
        $db = mysql_select_db(DB);
        if(!$db)
	    die("Could not select database: ".mysql_error());
	    else return $link;
    }
}*/

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
