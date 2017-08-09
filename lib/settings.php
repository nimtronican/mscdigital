<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include("./classes/MysqliDb.php");
include("./classes/dbObject.php");
include("./lib/common.php");
session_start();
// db instance
$db = new Mysqlidb('43.255.154.43:3306', 'mscd_super', 'MSCD12#$@digi', 'mscdigital_taskanalysis');
// enable class autoloading
//dbObject::autoload("models");


?>