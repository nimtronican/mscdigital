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
		//Discipline Select box
		$teamsquery = 'SELECT * from `msc_discipline` md ORDER BY md.`discipline_name` ASC';
		$teamsres = $conn->query($teamsquery);
		if ($teamsres->num_rows > 0) {
			// output data of each row
			while($row = $teamsres->fetch_assoc()) {
				$teamselect .= '<option value="'.$row['id'].'">'.$row['discipline_name'].'</option>';
			}
		}
		//Job role Select box
		$jobsquery = 'SELECT * from `msc_job` mj ORDER BY mj.`job_name` ASC';
		$jobsres = $conn->query($jobsquery);
		if ($jobsres->num_rows > 0) {
			// output data of each row
			while($row = $jobsres->fetch_assoc()) {
				$jobselect .= '<option value="'.$row['id'].'">'.$row['job_name'].'</option>';
			}
		}
		//Team leads Select box
		$tlsquery = 'SELECT * from `msc_employee` me where me.`emp_level`=1 ORDER BY me.`emp_name` ASC';
		$tlsres = $conn->query($tlsquery);
		if ($tlsres->num_rows > 0) {
			// output data of each row
			while($row = $tlsres->fetch_assoc()) {
				$tlselect .= '<option value="'.$row['id'].'">'.$row['emp_name'].'</option>';
			}
		}
		//Managers Select box
		$managersquery = 'SELECT * from `msc_employee` me where me.`emp_level`=2 ORDER BY me.`emp_name` ASC';
		$managersres = $conn->query($managersquery);
		if ($managersres->num_rows > 0) {
			// output data of each row
			while($row = $managersres->fetch_assoc()) {
				$managerselect .= '<option value="'.$row['id'].'">'.$row['emp_name'].'</option>';
			}
		}
		//print_r($teamsres);
		echo "I am in 6<br>";
		/*$jobs = dbObject::table("msc_job")->orderBy("job_name","asc")->get();
		echo "I am in 7<br>";
		$employee = dbObject::table("msc_employee");
		echo "I am in 8<br>";
		$teamleads = $employee::where("emp_level","1")->orderBy("emp_name","asc")->get();
		echo "I am in 9<br>";
		$managers = $employee::where("emp_level","2")->orderBy("emp_name","asc")->get();
		/*foreach($teams as $t){
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
		}*/
	}
	$conn->close();
	echo "I am in 9<br>";
	include("./html/register.html");
}else{
	echo "Unauthenticated Access";
}
?>
