<?php
/* To Dos

*/
include("config.php");

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
if (sizeof($_POST, 1) != 1) {
    $methodwas = $_SERVER['REQUEST_METHOD'];
    $sizewas = sizeof($_POST, 1);
    exit("Exit without return. $methodwas , $sizewas");
}

$instate = test_input($_POST["state"]);
//UPDATE FlagCANP SET Status = 'Off' WHERE keyunique = 1
// DB Read for error conditions, using credentials from config.php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
// NO NEED TO SEND AN ACTUAL DB ENTRY
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


echo(' <!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
');
echo "Received " . $instate . "<br>";

$sql = sprintf("UPDATE FlagCANP SET Status='$instate' WHERE keyunique = 1");
if ($conn->query($sql) === TRUE) {
    echo "Change applied.<br>"; 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$sql = sprintf("SELECT Status FROM FlagCANP WHERE keyunique = 1");
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
    echo "Status now " . $row["Status"];
} else {
    echo "No status found";
}

echo('<br><a href="chop.html">Go back.</a></body></html>');
$conn->close();

/* Functions */
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
