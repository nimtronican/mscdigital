<?php
echo "I am outside<br>";
echo "Whats happening";
include("./lib/settings.php");
global $ERROR_MSG;
if($_REQUEST['action'] == NULL){
	if(isset($_SESSION['login'])){
		include("./html/index.html");
	}else{
		include("./html/login.html");
	}
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "register"){
	echo "I am here loop 2 <br>";
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
				$ERROR_MSG = "Your Account is created successfully.";
			}
		}
	}else{
		global $teamselect;
		global $jobselect;
		global $tlselect;
		global $managerselect;
		echo "I am in 3<br>";
		$_SESSION['AUTHCODE'] = md5(date("H i s"));
		echo "I am in 4<br>";
		$teams = $db->rawQuery('SELECT * from `msc_discipline` ORDER BY "discipline_name" ASC');
		//$teams = dbObject::table("msc_discipline")->orderBy("discipline_name","asc")->get();
		print_r($teams);
		echo "I am in 6<br>";
		$jobs = dbObject::table("msc_job")->orderBy("job_name","asc")->get();
		echo "I am in 7<br>";
		$employee = dbObject::table("msc_employee");
		echo "I am in 8<br>";
		$teamleads = $employee::where("emp_level","1")->orderBy("emp_name","asc")->get();
		echo "I am in 9<br>";
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
	echo "I am in 9<br>";
	include("./html/register.html");
}else{
	echo "Unauthenticated Access";
}
?>
