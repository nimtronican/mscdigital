<?php
ini_set('xmlrpc_errors',1);
ini_set('track_errors',1);
ini_set('error_reporting',1);
ini_set('display_startup_errors',1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 
include("./classes/MysqliDb.php");
include("./classes/dbObject.php");
include("./lib/common.php");
session_start();
// db instance 
$db = new MysqliDb('sg2plcpnl0234.prod.sin2.secureserver.net', 'mscd_super', 'MSCD12#$@digi', 'mscdigital_taskanalysis');
$db->port = 3306;
/*
/////CLEARDB CONNECTION/////
$url = parse_url(getenv("mysql://b2ad3d49dad29b:d54eb81f@us-cdbr-sl-dfw-01.cleardb.net:3306/ibmx_903feb6feb9b242?reconnect=true"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

//$conn = new mysqli($server, $username, $password, $db);
$db = new Mysqlidb($server, $username, $password, $db);
$db->port = 3306;
// enable class autoloading
//dbObject::autoload("models");*/
?>
