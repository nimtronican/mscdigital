<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include("./classes/MysqliDb.php");
include("./classes/dbObject.php");
include("./lib/common.php");
session_start();
// db instance
$db = new Mysqlidb('sg2plcpnl0234.prod.sin2.secureserver.net', 'mscd_super', 'MSCD12#$@digi', 'mscdigital_taskanalysis');
$db->port = 2083;
// enable class autoloading
//dbObject::autoload("models");
?>