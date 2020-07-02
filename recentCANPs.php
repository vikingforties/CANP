<?php
// Details: https://github.com/vikingforties/CANP 
/* To Dos

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
$username = "-----------";
// CHANGE ME when password rolls over!!
$password = "---------------";
$dbname = "------------------";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = sprintf('SELECT Site, Scheduled FROM CANP WHERE Scheduled >= DATE_ADD(CURDATE(), INTERVAL 0 DAY) ORDER BY Scheduled, Site LIMIT 6');

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo('<table><caption><b>Next CANPs:</b></caption>');
    while($row = $result->fetch_assoc()) {
        $sitebits = preg_split('/[A-Z]{2}/', $row["Site"]); 
        $datebits = explode("-", $row["Scheduled"]);
        $orddate = date('D jS', mktime(0, 0, 0, $datebits[1], $datebits[2], $datebits[0]));
        echo('<tr><td>' . $sitebits[0] . '</td><td>' . "- " . $orddate . '</td></tr>');
    }
    echo('</table>');
} else {
    echo "No upcoming CANPs";
}

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