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


function getWorkingDays($startDate,$endDate,$holidays){
	//The function returns the no. of business days between two dates and it skips the holidays
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
	//Example:
	//$holidays=array("2008-12-25","2008-12-26","2009-01-01");
	//echo getWorkingDays("2008-12-22","2009-01-02",$holidays)
	// => will return 7
}

function date_check_in_range($start_date, $end_date, $date_from_user)
{
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function getDayValue(){
	//$date = 
	$dayofweek = date('w');
	//$res = date('Y-m-d', strtotime(($day - $dayofweek).' day', strtotime($date)));
	return $dayofweek;
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