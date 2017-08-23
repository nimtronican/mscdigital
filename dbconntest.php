<?php
ini_set('xmlrpc_errors',1);
ini_set('track_errors',1);
ini_set('error_reporting',1);
ini_set('display_startup_errors',1);
ini_set('display_errors', 1);
error_reporting(E_ALL); 
# Fill our vars and run on cli
# $ php -f db-connect-test.php
$dbname = 'mscdigital_taskanalysis';
$dbuser = 'mscd_super';
$dbpass = 'MSCD12#$@digi';
$dbhost = 'sg2plcpnl0234.prod.sin2.secureserver.net';
$link = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost' ".mysqli_errno($connect));
mysqli_select_db($link, $dbname) or die("Could not open the db '$dbname' ".mysqli_errno($connect));
$test_query = "SHOW TABLES FROM $dbname";
$result = mysqli_query($link, $test_query);
$tblCnt = 0;
while($tbl = mysqli_fetch_array($result)) {
  $tblCnt++;
  #echo $tbl[0]."<br />\n";
}
if (!$tblCnt) {
  echo "There are no tables<br />\n";
} else {
  echo "There are $tblCnt tables<br />\n";
}