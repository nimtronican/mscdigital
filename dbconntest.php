<?php
ini_set('xmlrpc_errors',1);
ini_set('track_errors',1);
ini_set('error_reporting',1);
ini_set('display_startup_errors',1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 

$dbname = 'mscdigital_taskanalysis';
$dbuser = 'mscd_super';
$dbpass = 'MSCD12#$@digi';
$dbhost = 'sg2plcpnl0234.prod.sin2.secureserver.net';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
//$dblink = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost' ".mysqli_errno($dblink));
//mysqli_select_db($dblink, $dbname) or die("Could not open the db '$dbname' ".mysqli_errno($dblink));
$test_query = 'SELECT * from `msc_discipline` md ORDER BY md.`discipline_name` ASC';
$result = $conn->query($test_query);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["discipline_name"]. "<br>";
    }
} 
?>
