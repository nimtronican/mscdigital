<?php
include("./lib/settings.php");
global $ERROR_MSG;
if($_REQUEST['action'] == NULL){
	if(isset($_SESSION['login'])){
		include("./html/index.html");
	}else{
		include("./html/login.html");
	}
}else if($_REQUEST['action'] == "register"){
	if(isset($_SESSION['AUTHCODE']) && $_POST['authcode'] == $_SESSION['AUTHCODE']){
		//print_r($_POST);
		$currID = $_POST['empid'];
		$employee = dbObject::table("msc_employee");
		$datacheck = $employee::where("emp_ibmid",$currID)->get();
		if($datacheck){
			$ERROR_MSG = "You have already Registered, Please login with your details";exit();
		}else{
			$empdata = Array(
			'emp_ibmid' => $_POST['empid'],
			'emp_name' => $_POST['flname'],
			'emp_email' => $_POST['emailid'],
			'emp_level' => $_POST['emplevel'],
			'emp_discipline' => $_POST['discipline'],
			'emp_job' => $_POST['jobrole'],
			'emp_teamlead' => $_POST['teamlead'],
			'emp_manager' => $_POST['manager']
			);
			$insemp = new $employee($empdata);
			$id = $insemp->save();
			if($id == null){
				print_r($insemp->errors);
			}else{
				//echo "User created with ID:".$id;
				$ERROR_MSG = "Your Account is created successfully.";
			}
		}
	}else{
		global $teamselect;
		global $jobselect;
		global $tlselect;
		global $managerselect;
		$_SESSION['AUTHCODE'] = md5(date("H i s"));
		$teams = dbObject::table("msc_discipline")->get();
		$jobs = dbObject::table("msc_job")->get();
		$employee = dbObject::table("msc_employee");
		$teamleads = $employee::where("emp_level","1")->get();
		$managers = $employee::where("emp_level","2")->get();
		foreach($teams as $t){
			$teamselect .= '<option value="'.$t->id.'">'.$t->discipline_name.'</option>';
		}
		foreach($jobs as $j){
			$jobselect .= '<option value="'.$j->id.'">'.$j->job_name.'</option>';
		}
		if($teamleads != NULL){
			foreach ($teamleads as $tls){
				$tlselect .= '<option value="'.$tls->id.'">'.$tls->emp_name.'</option>';
			}
		}
		foreach ($managers as $m){
				$managerselect .= '<option value="'.$m->id.'">'.$m->emp_name.'</option>';
		}
	}
	include("./html/register.html");
}else{
	echo "Unauthenticated Access";
}
?>