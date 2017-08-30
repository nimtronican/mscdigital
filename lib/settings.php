<?php
ini_set('xmlrpc_errors',1);
ini_set('track_errors',1);
ini_set('error_reporting',1);
ini_set('display_startup_errors',1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 
//include("./classes/MysqliDb.php");
//include("./classes/dbObject.php");
include("./lib/common.php");
session_start();

$dbname = 'mscdigital_taskanalysis';
$dbuser = 'mscd_super';
$dbpass = 'MSCD12#$@digi';
$dbhost = 'sg2plcpnl0234.prod.sin2.secureserver.net';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
?>
