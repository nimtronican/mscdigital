<?php
include("./modlib/settings.php");
global $ERROR_MSG;
if(!isset($_REQUEST['action']) || isset($_SESSION['LOGIN'])){
	if(isset($_SESSION['LOGIN'])){
		if(isset($_REQUEST['logout'])){
			unset($_SESSION['LOGIN']);
			header("location:./index.php");
		}
		include("./html/index.html");
	}else if(isset($_SESSION['LOGINAUTH']) && isset($_POST['lauthcode']) && $_SESSION['LOGINAUTH'] == $_POST['lauthcode']){
		$loginret = getLoginDetails($db,$_POST['uname'],$_POST['psw']);
		//$discjob = getDiscJob($db,$loginret['emp_discipline'],$loginret['emp_job']);//DBlink,team,jobrole
		//print_r($loginret);
		//print_r($discjob);
		//exit();
		if($loginret['id']>0){
			$_SESSION['LOGIN'] = 1;
			$_SESSION['UID'] = $loginret['id'];
			$_SESSION['ULEVEL'] = $loginret['emp_level'];
			$_SESSION['USER'] = array("name"=>$loginret['emp_name'],
										 "team"=>$loginret['discipline_name'],
										 "role"=>$loginret['job_name']);
			include("./html/index.html");
		}else{
			$ERROR_MSG = "LOGIN FAILED... Please check your Credentials";
			include("./html/login.html");
		}
	}else{
		$_SESSION['USER'] = "";
		$_SESSION['UID'] = "";
		$_SESSION['ULEVEL'] = "";
		$_SESSION['LOGINAUTH'] = md5(date("H i s")).rand();
		unset($_SESSION['LOGIN']);
		include("./html/login.html");
	}
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "register"){
	if(isset($_SESSION['AUTHCODE']) && isset($_POST['authcode']) && $_POST['authcode'] == $_SESSION['AUTHCODE']){
		//print_r($_POST);
		$currID = $_POST['empid'];
		$employee = dbObject::table("msc_employee");
		$datacheck = $employee::where("emp_ibmid",$currID)->get();
		if($datacheck){
			$ERROR_MSG = "You have already Registered, Please login with your details";
		}else{
			//echo "I am in";
			//print_r($_POST);
			if($_POST['teamlead']=="")$_POST['teamlead']=0;
			$empdata = Array(
			'emp_ibmid' => $_POST['empid'],
			'emp_name' => $_POST['flname'],
			'emp_email' => $_POST['emailid'],
			'emp_level' => $_POST['emplevel'],
			'emp_discipline' => $_POST['discipline'],
			'emp_job' => $_POST['jobrole'],
			'emp_teamlead' => $_POST['teamlead'],
			'emp_manager' => $_POST['manager'],
			);
			//print_r($empdata);
			$insemp = new $employee($empdata);
			$id = $insemp->save();
			//print_r($insemp);
			//echo $db->getLastError;
			if($id == null){
				echo "Error";
				print_r($insemp->errors);
			}else{
				//echo "User created with ID:".$id;
				$_SESSION['UNAME'] = $_POST['flname'];
				$ERROR_MSG = "Your Account is created successfully. Please <a href='./'>LOGIN</a>";
			}
		}
	}else{
		global $teamselect;
		global $jobselect;
		global $tlselect;
		global $managerselect;
		$_SESSION['AUTHCODE'] = md5(date("H i s"));
		$teams = dbObject::table("msc_discipline")->orderBy("discipline_name","asc")->get();
		$jobs = dbObject::table("msc_job")->orderBy("job_name","asc")->get();
		$employee = dbObject::table("msc_employee");
		$teamleads = $employee::where("emp_level","1")->orderBy("emp_name","asc")->get();
		$managers = $employee::where("emp_level","2")->orderBy("emp_name","asc")->get();
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