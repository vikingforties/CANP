<?php
// Details: https://github.com/vikingforties/CANP
/* To Dos
*/
//error_reporting(E_ALL);
//ini_set('display_errors', True);

//$path = '/home/logansm/php';

//set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// Lets reject unwanted requests.
include("config.php");

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

$sql = sprintf('SELECT Status FROM FlagCANP');

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo($row['Status']);
    }
} else {
    echo "0";
}
$conn->close();

/* Functions */

?>
