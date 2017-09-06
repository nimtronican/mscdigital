<?php
include("./modlib/settings.php");
global $ERROR_MSG;
if(!isset($_REQUEST['action']) || isset($_SESSION['LOGIN'])){
	if(isset($_SESSION['LOGIN'])){
		if(isset($_REQUEST['logout'])){
			unset($_SESSION['LOGIN']);
			header("location:./index.php");
		}
		if(isset($_POST['totinputs']) && $_POST['totinputs']>0 ) {
			$dmetrics = dbObject::table("msc_data");
			//Date format: 2017-08-22 02:59:48
			$indate = $_POST['seldate'];
			$updatedate = $indate.' '.date('H:i:s');
			for($i=0;$i<$_POST['totinputs'];$i++){
				$currid = $_SESSION['METRICS'][$i];
				$mval = $_POST['val-'.$currid];
				$insdata = Array(
								'empmetrics_rel_id' => $currid,
								'data_value' => $mval,
								'data_date' => $updatedate
							);
				$insobj = new $dmetrics($insdata);
				$id = $insobj->save();
				//print_r($insemp);
				//echo $db->getLastError;
				if($id == null){
					echo "Error";
					print_r($insobj->errors);
				}else{
					//echo "User created with ID:".$id;
					$SUCCESS_MSG = "Metrics Added Successfully";
				}
			}
			
		}
		$dispdata = $db->rawQuery('SELECT mer.id,mm.metrics_text,mm.metrics_freq FROM `msc_empmetrics_rel` mer,`msc_metrics` mm WHERE mer.employee_id = '.$_SESSION['UID'].' AND mm.id = mer.metrics_id ORDER BY mer.id');
		 global $displist;
		 global $datepanel;
		 global $historypanel;
		 $noentries = 0;
		 $nodays = 2;
		 $dval = 1;
		 $stt = "";
		 $idarr = array();
		 //$toddate = date('d-m-Y D',strtotime());
		 //$yesdate = date('d-m-Y D',strtotime("-1 days"));
		 foreach ($dispdata as $val) {
			$dmetrics = dbObject::table("msc_data");
			$dmetricsres = $dmetrics::where("empmetrics_rel_id",$val['id'])->orderBy("data_date","asc")->get();
			//print_r($dmetricsres);
			$freqvalue = getFreqValue($val['metrics_freq']);
			$dateslist = "";
			$valuelist = "";
			$entrytoday = date('d-M',strtotime("Today"));
			$entryyest = date('d-M',strtotime("-1 days"));
			$DONEFLG_T = 0;
			$DONEFLG_Y = 0;
			
			if($dmetricsres){
				foreach($dmetricsres as $dmr){
					$dd = date('d-M',strtotime($dmr->data_date));
					$dateslist .= '<td scope="col">'.$dd.'</td>';
					if($dd == $entrytoday)$DONEFLG_T = 1;
					if($dd == $entryyest)$DONEFLG_Y = 1;
					$valuelist .= '<td>'.$dmr->data_value.'</td>';
				}
			}
			$historypanel .= 
					  '<table data-widget="datatable" data-scrollaxis="x" class="ibm-data-table" cellspacing="0" cellpadding="0" border="0">
						  <thead>
							<tr>
							  <th scope="col">Frequency:'.$freqvalue.'</th>
							  '.$dateslist.'
							</tr>
						  </thead>
						  <tbody>
							<tr>
							  <th scope="row">'.$val['metrics_text'].'</th>
							  '.$valuelist.'
							</tr>
						  </tbody>
						</table>';
			if($DONEFLG_T > 0 && $DONEFLG_Y > 0){
				$displist .= '<div class="ibm-columns ibm-padding-top-1 ibm-alternate-background" data-widget="setsameheight" data-items="> div">
							<div class="ibm-col-5-1"></div>
							<div class="ibm-col-5-2 ibm-background-blue-60">
							  <h2 style="margin-top:5px;">'.$val['metrics_text'].'('.$freqvalue.')</h2>
							</div>
							<div class="ibm-col-5-1 ibm-background-blue-20">
							  <span style="display:block;margin-top:12px;color:#F00;">ALREADY ENTERED</span>
							</div>
							<div class="ibm-col-5-1"></div>
						  </div>';
			}else{
			$displist .= '<div class="ibm-columns ibm-padding-top-1 ibm-alternate-background" data-widget="setsameheight" data-items="> div">
							<div class="ibm-col-5-1"></div>
							<div class="ibm-col-5-2 ibm-background-blue-60">
							  <h2 style="margin-top:5px;">'.$val['metrics_text'].'('.$freqvalue.')</h2>
							</div>
							<div class="ibm-col-5-1 ibm-background-blue-20">
							  <input type="number" placeholder="Enter Number" name="val-'.$val['id'].'" required style="margin-top:8px;" />
							</div>
							<div class="ibm-col-5-1"></div>
						  </div>';
			$idarr[] = $val['id'];
			$noentries++;
			}
			$historypanel .= "<hr>";
			$noentries++;
		 }
		 for($i=0;$i<$nodays;$i++){
			 $dval = $dval - 1;
			 $datepanel .= '<option value="'.date('Y-m-d').'">'.date('d-M D',strtotime($dval." days")).'</option>';
		 }
		 if($noentries>0){
			 $noentries = '<input type="hidden" name="totinputs" id="totinputs" value="'.$noentries.'" />';
			 $_SESSION['METRICS'] = $idarr;
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
			header("location:./index.php");
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
			'emp_manager' => $_POST['manager']
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