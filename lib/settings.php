<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include("./classes/MysqliDb.php");
include("./classes/dbObject.php");
include("./lib/common.php");
session_start();
// db instance
$db = new Mysqlidb('148.72.232.138', 'mscd_super', 'MSCD12#$@digi', 'mscdigital_taskanalysis');
$db->port = 3306;
// enable class autoloading
//dbObject::autoload("models");


?>