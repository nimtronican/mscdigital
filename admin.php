<?php
include("./modlib/settings.php");
global $ERROR_MSG;
global $SUCCESS_MSG;
if((isset($_REQUEST['action']) && $_REQUEST['action'] == NULL) || !isset($_SESSION['LOGIN'])){
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
		$metrics = dbObject::table("msc_empmetrics_rel");
		$wherecheck = "employee_id = ".$_POST['employee']." AND metrics_id = ".$_POST['metrics'];
		$datacheck = $metrics::where($wherecheck)->get();
		if($datacheck){
			$ERROR_MSG = "This metrics is already available.<br>Please verify Employee list of Metrics before adding!";
		}else{
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
	 $lsdata = $db->rawQuery('SELECT me.emp_name AS "empname",mm.metrics_text AS "metricstext",mm.metrics_freq AS "freq" FROM `msc_empmetrics_rel` mer,`msc_employee` me, `msc_metrics` mm WHERE me.id = mer.employee_id AND mm.id = mer.metrics_id AND (me.emp_teamlead = '.$_SESSION["UID"].' OR me.emp_manager='.$_SESSION["UID"].' OR me.id='.$_SESSION["UID"].') ORDER BY me.emp_name ASC');
	 //print_r($lsdata);
	 foreach ($lsdata as $lst) {
		$empmetricslist .= '<tr><td>'.$lst['empname'].'</td><td>'.$lst['metricstext'].'</td><td>'.getFreqValue($lst['freq']).'</td></tr>';
	 }
	 
	include("./html/empmetrics.html");
}else if($_REQUEST['action'] == "mastermetrics"){
	if(isset($_SESSION['AUTHCODE']) && isset($_POST['authcode']) && $_POST['authcode'] == $_SESSION['AUTHCODE']){
		//print_r($_POST);
		$metrics = dbObject::table("msc_empmetrics_rel");
		$wherecheck = "employee_id = ".$_POST['employee']." AND metrics_id = ".$_POST['metrics'];
		$datacheck = $metrics::where($wherecheck)->get();
		if($datacheck){
			$ERROR_MSG = "This metrics is already available.<br>Please verify Employee list of Metrics before adding!";
		}else{
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
	}
	global $employeelist;
	global $metricslist;
	global $empmetricslist;
	//Employee list
	$_SESSION['AUTHCODE'] = md5(date("H i s"));
	$employee = $db->rawQuery('SELECT me.id,me.emp_name FROM `msc_employee` me ORDER BY me.emp_name ASC');
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
	 $lsdata = $db->rawQuery('SELECT me.emp_name AS "empname",mm.metrics_text AS "metricstext",mm.metrics_freq AS "freq" FROM `msc_empmetrics_rel` mer,`msc_employee` me, `msc_metrics` mm WHERE me.id = mer.employee_id AND mm.id = mer.metrics_id ORDER BY me.emp_name ASC');
	 //print_r($lsdata);
	 foreach ($lsdata as $lst) {
		$empmetricslist .= '<tr><td>'.$lst['empname'].'</td><td>'.$lst['metricstext'].'</td><td>'.getFreqValue($lst['freq']).'</td></tr>';
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
}else if($_REQUEST['action'] == "fulllist"){
	global $fulllist;
	//$_SESSION['AUTHCODE'] = md5(date("H i s"));
	$employee = $db->rawQuery('SELECT me . * , md.`discipline_name` , mj.`job_name` , tl_name.emp_name AS TL_Name, mg_name.emp_name AS MG_Name
							FROM `msc_employee` me
							LEFT OUTER JOIN `msc_discipline` md ON md.`id` = me.`emp_discipline`
							LEFT OUTER JOIN `msc_job` mj ON mj.`id` = me.`emp_job`
							LEFT OUTER JOIN `msc_employee` tl_name ON tl_name.id = me.emp_teamlead
							LEFT OUTER JOIN `msc_employee` mg_name ON mg_name.id = me.emp_manager
							ORDER BY me.emp_name ASC');
	//print_r($db);
	 $num = 1;
	 foreach($employee as $emp){
		//echo $num."&nbsp".getEmpLevel($emp['emp_level'])."<br>";
		//$tms = getTlMngrNames($db,$emp['emp_teamlead'],$emp['emp_manager']);
		//if($emp['emp_teamlead'] > 0)$tlname = $tms[1]['emp_name'];
		//else $tlname = "NA";
		$fulllist .= '<tr><td>'.$num.'</td><td>'.$emp['id'].'</td><td>'.$emp['emp_ibmid'].'</td><td>'.$emp['emp_name'].'</td><td>'.$emp['emp_email'].'</td><td>'.getEmpLevel($emp['emp_level']).'</td><td>'.$emp['discipline_name'].'</td><td>'.$emp['job_name'].'</td><td>'.$emp['TL_Name'].'</td><td>'.$emp['MG_Name'].'</td></tr>';
		$num++;
	 }
	include("./html/fulllist.html");
}else if($_REQUEST['action'] == "editdetails"){
	if(isset($_SESSION['AUTHCODE']) && isset($_POST['authcode']) && $_POST['authcode'] == $_SESSION['AUTHCODE'] && $_POST['dbid'] == $_SESSION['UID']){
		//print_r($_POST);
		$currID = $_SESSION['UID'];
		//$employee = dbObject::table("msc_employee");
		//print_r($_POST);
		if($_POST['teamlead']=="")$_POST['teamlead']=0;
		$empdata = Array(
			'emp_name' => $_POST['flname'],
			'emp_level' => $_POST['emplevel'],
			'emp_discipline' => $_POST['discipline'],
			'emp_job' => $_POST['jobrole'],
			'emp_teamlead' => $_POST['teamlead'],
			'emp_manager' => $_POST['manager']
		);
		//print_r($empdata);
		$db->where('id',$currID);
		$insemp = $db->update("msc_employee",$empdata);
		//print_r($insemp);
		//print_r($insemp);
		//echo $db->getLastError;
		if($db->count < 0){
			echo "Error";
			print_r($insemp->errors);
		}else{
			$ERROR_MSG = "Your Account Updated successfully.";
		}
	}else{
		global $teamselect;
		global $jobselect;
		global $tlselect;
		global $managerselect;
		global $empdata;
		$employee = dbObject::table("msc_employee");
		$emparr = $employee::where("id",$_SESSION['UID'])->get();
		foreach ($emparr as $empdata){
			$empdata = $empdata;
		}
		$_SESSION['AUTHCODE'] = md5(date("H i s"));
		$teams = dbObject::table("msc_discipline")->orderBy("discipline_name","asc")->get();
		$jobs = dbObject::table("msc_job")->orderBy("job_name","asc")->get();
		
		$teamleads = $employee::where("emp_level","1")->orderBy("emp_name","asc")->get();
		$managers = $employee::where("emp_level","2")->orderBy("emp_name","asc")->get();
		foreach($teams as $t){
			$teamselect .= '<option value="'.$t->id.'" '.(($empdata->emp_discipline == $t->id) ? 'selected':'').'>'.$t->discipline_name.'</option>';
		}
		foreach($jobs as $j){
			$jobselect .= '<option value="'.$j->id.'" '.(($empdata->emp_job == $j->id) ? 'selected':'').'>'.$j->job_name.'</option>';
		}
		if($teamleads != NULL){
			foreach ($teamleads as $tls){
				$tlselect .= '<option value="'.$tls->id.'" '.(($empdata->emp_teamlead == $tls->id) ? 'selected':'').'>'.$tls->emp_name.'</option>';
			}
		}
		foreach ($managers as $m){
				$managerselect .= '<option value="'.$m->id.'" '.(($empdata->emp_manager == $m->id) ? 'selected':'').'>'.$m->emp_name.'</option>';
		}
	}
	include("./html/editemployee.html");
}else{
	echo "Unauthenticated Access";
}
?>