<?php
include("./modlib/settings.php");
global $ERROR_MSG;
global $SUCCESS_MSG;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == NULL){
	if(isset($_SESSION['LOGIN'])){
		include("./html/index.html");
	}else{
		header("location:./index.php");
		exit();
	}
}else if($_REQUEST['action'] == "addmetrics"){
	if(isset($_SESSION['AUTHCODE']) && isset($_POST['authcode']) && $_POST['authcode'] == $_SESSION['AUTHCODE']){
		//print_r($_POST);
		$currmetrics = $_POST['metrics'];
		$metrics = dbObject::table("msc_metrics");
		//$empmetricsrel = dbObject::table("msc_data");
		$datacheck = $metrics::where("metrics_text",$currmetrics)->get();
		if($datacheck){
			$ERROR_MSG = "This metrics is already available.<br>Please verify list of Metrics before adding!";
		}else{
			$metricsdata = Array(
			'metrics_text' => $_POST['metrics'],
			'metrics_freq' => $_POST['freq']
			);
			$insmetrics = new $metrics($metricsdata);
			$id = $insmetrics->save();
			if($id == null){
				print_r($insmetrics->errors);
			}else{
				//echo "User created with ID:".$id;
				$SUCCESS_MSG = "Metrics Added Successfully!";
			}
		}
	}
	global $metricslist;
	$_SESSION['AUTHCODE'] = md5(date("H i s"));
	$metrics = dbObject::table("msc_metrics")->get();
	 $num = 1;
	 foreach($metrics as $m){
		$metricslist .= '<tr><td>'.$num.'</td><td>'.$m->metrics_text.'</td><td>'.getFreqValue($m->metrics_freq).'</td></tr>';
		$num++;
	 }
	include("./html/metrics.html");
}else if($_REQUEST['action'] == "attachmetrics"){
	if(isset($_SESSION['AUTHCODE']) && isset($_POST['authcode']) && $_POST['authcode'] == $_SESSION['AUTHCODE']){
		//print_r($_POST);
		$metricrel = dbObject::table("msc_empmetrics_rel");
		$reldata = Array(
		'employee_id' => $_POST['employee'],
		'metrics_id' => $_POST['metrics']
		);
		$insrel = new $metricrel($reldata);
		$id = $insrel ->save();
		if($id == null){
			print_r($insrel ->errors);
		}else{
			//echo "User created with ID:".$id;
			$SUCCESS_MSG = "Metrics Attached Successfully!";
		}
	}
	global $employeelist;
	global $metricslist;
	global $empmetricslist;
	//Employee list
	$_SESSION['AUTHCODE'] = md5(date("H i s"));
	$employee = $db->rawQuery('SELECT me.id,me.emp_name FROM `msc_employee` me WHERE me.id = '.$_SESSION["UID"].' OR me.emp_teamlead = '.$_SESSION["UID"].' OR me.emp_manager = '.$_SESSION["UID"].' ORDER BY me.emp_name ASC');
	//dbObject::table("msc_employee")->get();
	 foreach($employee as $emp){
		$employeelist .= '<option value="'.$emp['id'].'">'.$emp['emp_name'].'</option>';
	 }
	//Metrics list - Dynamic
	$metrics = dbObject::table("msc_metrics")->get();
	 $num = 1;
	 foreach($metrics as $m){
		$metricslist .= '<option value="'.$m->id.'">'.$m->metrics_text.'</option>';
		$num++;
	 }
	 $lsdata = $db->rawQuery('SELECT me.emp_name AS "empname",mm.metrics_text AS "metricstext",mm.metrics_freq AS "freq" FROM `msc_empmetrics_rel` mer,`msc_employee` me, `msc_metrics` mm WHERE me.id = mer.employee_id AND mm.id = mer.metrics_id');
	 foreach ($lsdata as $lst) {
		$empmetricslist = '<tr><td>'.$lst['empname'].'</td><td>'.$lst['metricstext'].'</td><td>'.getFreqValue($lst['freq']).'</td></tr>';
	 }
	 
	include("./html/empmetrics.html");
}else if($_REQUEST['action'] == "teamview"){
	global $teamlist;
	//$_SESSION['AUTHCODE'] = md5(date("H i s"));
	$employee = $db->rawQuery('SELECT me.emp_email,me.emp_name FROM `msc_employee` me WHERE me.id = '.$_SESSION["UID"].' OR me.emp_teamlead = '.$_SESSION["UID"].' OR me.emp_manager = '.$_SESSION["UID"].' ORDER BY me.emp_name ASC');
	 $num = 1;
	 foreach($employee as $emp){
		$teamlist .= '<tr><td>'.$num.'</td><td>'.$emp['emp_name'].'</td><td>'.$emp['emp_email'].'</td></tr>';
		$num++;
	 }
	include("./html/teamlist.html");
}else{
	echo "Unauthenticated Access";
}
?>