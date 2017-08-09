<?php
function getFreqValue($val){
	if($val == 100)
		$ret = "Daily";
	else if($val = 110)
		$ret = "Weekly";
	else if($val = 111)
		$ret = "Monthly";
	else
		$ret = "Adhoc";
	
	return $ret;
}
?>