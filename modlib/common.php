<?php
function getFreqValue($val){
	if($val == 100)
		$ret = "Daily";
	else if($val == 110)
		$ret = "Weekly";
	else if($val == 111)
		$ret = "Monthly";
	else
		$ret = "Adhoc";
	
	return $ret;
}
function getEmpLevel($val){
	if($val == 0)
		$ret = "Individual Contributor";
	else if($val == 1)
		$ret = "Team Leader";
	else if($val == 2)
		$ret = "Manager";
	else
		$ret = "Unknown";
	
	return $ret;
}

//DB Functions
function getLoginDetails($db,$uname,$psw){
	$ret = $db->rawQuery('SELECT me.*,md.`discipline_name`,mj.`job_name` FROM `msc_employee` me,`msc_discipline` md,`msc_job` mj WHERE me.emp_ibmid ="'.$psw.'" AND me.emp_email = "'.$uname.'" AND md.id =me.emp_discipline AND mj.id = me.emp_job');
	return $ret[0];
}
function getDiscJob($db,$discid,$jobid){
	$ret = $db->rawQuery('SELECT md.`discipline_name`,mj.`job_name` FROM `msc_discipline` md,`msc_job` mj WHERE md.id ="'.$discid.'" AND mj.id = "'.$jobid.'"');
	return $ret[0];
}
function getTlMngrNames($db,$tlid,$mngid){
	$ret = $db->rawQuery('SELECT me.`emp_name` FROM `msc_employee` me WHERE me.`id` in ('.$tlid.','.$mngid.')');
	//print_r($ret);
	return $ret;
}
?>