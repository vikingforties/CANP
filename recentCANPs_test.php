<?php
// Details: https://github.com/vikingforties/CANP
/* To Dos
Items to check on change to Live:
SQl statement DB name

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

$sql = sprintf('SELECT Site, Scheduled FROM testCANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) ORDER BY Scheduled, Site LIMIT 6');

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo('<table><caption><b>Next CANPs:</b></caption>');
    while($row = $result->fetch_assoc()) {
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]);
        $site = $sitebits[0];
        if (strlen("$site") >= 20) {
            $site = substr($site, 0, 19) . "...";
        }
        $datebits = explode("-", $row["Scheduled"]);
        $orddate = date('D jS', mktime(0, 0, 0, $datebits[1], $datebits[2], $datebits[0]));
        echo('<tr><td>' . $site . '</td><td>' . "- " . $orddate . '</td></tr>');
    }
    echo('</table>');
} else {
    echo "No upcoming CANPs";
}

$conn->close();
?>
