<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cronjob page
 */
class Cronjob extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	
	/****************  Add Contractual Leave ******************/
	public function add_contractual_leave(){
		$mm = date("m");
		$yy = date("Y");
		$dd_month = $mm;
		$dd_year = $yy;
		$YYmm = $dd_year.'-'.$dd_month;
		$enddate = date('Y-m-d', strtotime('-1 day'));

		//get all the Active contractual employee
		$contactualActiveEmpQry = "SELECT login_id,loginhandle, join_date, leaveCreditedDate, lwd_date FROM `internal_user` WHERE user_status = '1' AND emp_type = 'C'";
		$contactualActiveEmpRes = $this->db->query($contactualActiveEmpQry);
		$contactualActiveEmpResult = $contactualActiveEmpRes->result_array();
		$contactualActiveEmpNum = COUNT($contactualActiveEmpResult);
		for($i = 1; $i< $contactualActiveEmpNum ; $i++){
			set_time_limit(10.316666126251);
			$attndDays = $regDays = $PLDays = $SLDays = 0;
			$startdate = $contactualActiveEmpResult[$i]['leaveCreditedDate'];
			
			// Get All Declared Holidays
			$declaredHolidayArray[] = '';
			$holidaySql = "SELECT `dt_event_date` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date`  >= '".$startdate."'";
			$holidayRes = $this->db->query($holidaySql);
			$holidayInfo = $holidayRes->result_array();
			for($j = 0; $j<COUNT($holidayInfo);$j++)
			{
				$declaredHolidayArray[] = $holidayInfo[$j]['dt_event_date'];
			}
			$noofHolidays = $this->contractualLeaveHolidays($startdate, $enddate, $declaredHolidayArray);
		 
			$attendanceSql = "SELECT `att_status`, COUNT(`attendance_id`) AS total, `leave_type` FROM `attendance_new` WHERE `login_id` = '".$contactualActiveEmpResult[$i]['login_id']."' AND  `date` >= '".$startdate."' AND `date` <= '".$enddate."' GROUP BY `att_status`, `leave_type`";
			$attendanceRes = $this->db->query($attendanceSql);
			$attendanceResult = $attendanceRes->result_array();
			$attendanceNum = COUNT($attendanceResult);
			if($attendanceNum > 0)
			{
				for($k=0;$k<$attendanceNum;$k++)
				{
				
					if($attendanceResult[$k]['att_status'] == 'P')
					{
						$attndDays = $attendanceResult[$k]['total'];
					}
					elseif($attendanceResult[$k]['att_status'] == 'R')
					{
						$regDays = $attendanceResult[$k]['total'];
					}
					elseif($attendanceResult[$k]['att_status'] == 'H')
					{
						$hDSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$contactualActiveEmpResult[$i]['login_id']."' AND `date` >= '".$startdate."' AND `att_status` = 'H' AND `in_time` = '00:00:00'";
						$hDRES = $this->db->query($hDSQL);
						$hDNUM = COUNT($hDRES->result_array());
						$hDayAbsent = 0;
						if($hDNUM > 0)
						{
							$hDayAbsent = $hDNUM / 2;
						}
						$totHDays = $attendanceResult[$k]['total'] / 2;
						$attndDays = $attndDays + $totHDays - $hDayAbsent;
						if($attendanceResult[$k]['leave_type'] == 'P')
						{
							$PLDays = $PLDays + $totHDays;
						}
						else
						{
							$SLDays = $SLDays + $totHDays;
						}
					}
					elseif($attendanceResult[$k]['att_status'] == 'L')
					{
						if($attendanceResult[$k]['leave_type'] == 'P')
						{
							$PLDays = $PLDays + $attendanceResult[$k]['total'];
						}
						else
						{
							$SLDays = $SLDays + $attendanceResult[$k]['total'];
						}
					}
				}
			} 
			if($contactualActiveEmpResult[$i] ['join_date'] > $startdate || ($contactualActiveEmpResult[$i]['lwd_date'] != '0000-00-00' && $contactualActiveEmpResult[$i]['lwd_date'] < $enddate))
			{
				$newNoofHolidays = $this->contractualLeaveHolidays($startdate, $enddate, $declaredHolidayArray, $contactualActiveEmpResult[$i] ['join_date'], $contactualActiveEmpResult[$i] ['lwd_date']);
				$attndDays = $attndDays + $regDays + $newNoofHolidays;
			}
			else
			{
				$attndDays = $attndDays + $regDays + $noofHolidays;
			} 
			
			$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
			$totalDays = intval((date('t',$startdateintime)),10);
			$payDays = $attndDays + $PLDays + $SLDays;
			$absentdays = $totalDays - $payDays;
			if($attndDays > 20)
			{
				$carryforwadSql="SELECT * FROM `leave_carry_ forward` where `user_id` = '".$contactualActiveEmpResult[$i]['login_id']."' AND `year` = '".date("Y")."'" ;
				$carryforwadRes = $this->db->query($carryforwadSql);
				$carryforwadInfo=$carryforwadRes->result_array();
				$carryforwadNum = COUNT($carryforwadInfo);
				if($carryforwadNum >0)
				{                    
					$ob_pl= $carryforwadInfo[0]['ob_pl'] +1;
					$carryLeaveSql="UPDATE `leave_carry_ forward` Set `ob_pl`='".$ob_pl."'  where `user_id` = '".$contactualActiveEmpResult[$i]['login_id']."' AND `year` = '".date("Y")."' LIMIT 1";
				}
				else
				{
					$carryLeaveSql="INSERT Into`leave_carry_ forward` (`user_id`,`year`,`ob_pl`,`ob_sl`) values('".$contactualActiveEmpResult[$i]['login_id']."','".date("Y")."','1','0.0')" ;
				}
				if($carryLeaveRes = $this->db->query($carryLeaveSql))
				{
					$creditedSql="Update `internal_user` Set `leaveCreditedDate` = '".date('Y-m-d')."'  where `login_id` = '".$contactualActiveEmpResult[$i] ['login_id']."'";
					$creditedRes = $this->db->query($creditedSql);
				}
				echo $contactualActiveEmpResult[$i] ['loginhandle']."<br/>";
			}
		 
		 }

		$to      = 'hr@polosoftech.com';
		$subject = 'Mail for Add Contractual PL : '. date("Y-m-d H:i:s");
		$message = 'Add Contractual PL Date date : '. date("Y-m-d H:i:s");
		$headers = 'From: hr@polosoftech.com' . "\r\n" .
			'Reply-To: hr@polosoftech.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		//mail($to, $subject, $message, $headers);
	}
	
	public function contractualLeaveHolidays($startdate, $enddate, $declaredHolidayArray, $joinDate = '', $lwDate = ''){
		if($joinDate != '' && strtotime($joinDate) > $startdate){
			$startdate = $joinDate;
		}
		
		if($lwDate != '' && $lwDate != '0000-00-00' && strtotime($lwDate) < $enddate){
			$enddate =$lwDate;
		}
		
		$todaydate = date("Y-m-d");
		if($todaydate < $enddate){
			$enddate = $todaydate;
		}
		$currentdate = $startdate;

		$noofHolidays = 0;
		
		while ($currentdate <= $enddate){
			$YYmmdd =  $currentdate;
			if(array_search($YYmmdd, $declaredHolidayArray)){
				$noofHolidays++;
			}
			 $currentdate =date("Y-m-d", strtotime('+1 day', strtotime($currentdate)));
		} 
		return $noofHolidays;
	}
	/**************** END/ Add Contractual Leave ******************/
	
	/**************** Add Contractual Leave Manual******************/
	public function add_contractual_leave_manual(){
		$mm = date("m");
		$yy = date("Y");
		$dd_month = $mm;
		$dd_year = $yy;
		$YYmm = $dd_year.'-'.$dd_month;
		echo $enddate = date('Y-m-d', strtotime('-1 day'));
		echo "<br/>";
		//get all the Active contractual employee
		$contactualActiveEmpQry = "SELECT login_id, loginhandle , join_date, leaveCreditedDate, lwd_date FROM `internal_user` WHERE user_status = '1' AND emp_type = 'C'";
		$contactualActiveEmpRes = $this->db->query($contactualActiveEmpQry);
		$contactualActiveEmp = $contactualActiveEmpRes->result_array();
		$contactualActiveEmpNum = COUNT($contactualActiveEmp);
		for($i=0;$i<$contactualActiveEmpNum;$i++){
			
		// while($contactualActiveEmpInfo = mysql_fetch_array($contactualActiveEmpRes)){
			$attndDays = $regDays = $PLDays = $SLDays = 0;
			$startdate = $contactualActiveEmp[$i]['leaveCreditedDate'];
			
			// Get All Declared Holidays
			$declaredHolidayArray[] = '';
			$holidaySql = "SELECT `dt_event_date` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date`  >= '".$startdate."'";
			$holidayRes = $this->db->query($holidaySql);
			$holidayResult = $holidayRes->result_array();
			for($j=0;$j<COUNT($holidayResult);$j++){
				$declaredHolidayArray[] = $holidayResult[$j]['dt_event_date'];
			}

			 $noofHolidays = $this->contractualLeaveHolidays($startdate, $enddate, $declaredHolidayArray);
		 
			$attendanceSql = "SELECT `att_status`, COUNT(`attendance_id`) AS total, `leave_type` FROM `attendance_new` WHERE `login_id` = '".$contactualActiveEmp[$i] ['login_id']."' AND  `date` >= '".$startdate."' AND `date` <= '".$enddate."' GROUP BY `att_status`, `leave_type`";
			$attendanceRes = $this->db->query($attendanceSql);
			$attendanceResult = $attendanceRes->result_array();
			$attendanceNum = COUNT($attendanceResult);
			if($attendanceNum > 0){
				for($k = 0 ;$k < $attendanceNum; $k++) {
				//while($attendanceInfo = mysql_fetch_assoc($attendanceRes)){
				
					if($attendanceResult[$k]['att_status'] == 'P'){
					$attndDays = $attendanceResult[$k]['total'];
						
					}elseif($attendanceResult[$k]['att_status'] == 'R'){
						$regDays = $attendanceResult[$k]['total'];
					
					}elseif($attendanceResult[$k]['att_status'] == 'H'){
						// Get Half Day without intime
						$hDSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$contactualActiveEmp[$i] ['login_id']."' AND `date` >= '".$startdate."' AND `att_status` = 'H' AND `in_time` = '00:00:00'";
						$hDRES = $this->db->query($hDSQL);
						$hDNUM = COUNT($hDRES->result_array());
						$hDayAbsent = 0;
						if($hDNUM > 0){
							$hDayAbsent = $hDNUM / 2;
						}
						$totHDays = $attendanceResult[$k]['total'] / 2;
						$attndDays = $attndDays + $totHDays - $hDayAbsent;
						if($attendanceResult[$k]['leave_type'] == 'P'){
								$PLDays = $PLDays + $totHDays;
						}else{
								$SLDays = $SLDays + $totHDays;
						}
					}elseif($attendanceResult[$k]['att_status'] == 'L'){
						if($attendanceResult[$k]['leave_type'] == 'P'){
								$PLDays = $PLDays + $attendanceResult[$k]['total'];

						}else{
								$SLDays = $SLDays + $attendanceResult[$k]['total'];
						}
					}
				}
			}
				
			
			if($contactualActiveEmp[$i] ['join_date'] > $startdate || ($contactualActiveEmp[$i] ['lwd_date'] != '0000-00-00' && $contactualActiveEmp[$i] ['lwd_date'] < $enddate)){
				$newNoofHolidays = $this->contractualLeaveHolidays($startdate, $enddate, $declaredHolidayArray, $contactualActiveEmp[$i] ['join_date'], $contactualActiveEmp[$i] ['lwd_date']);
				$attndDays = $attndDays + $regDays + $newNoofHolidays;
			}else{
				$attndDays = $attndDays + $regDays + $noofHolidays;
			}
			
				
			$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
			$totalDays = intval((date('t',$startdateintime)),10);
			$payDays = $attndDays + $PLDays + $SLDays;
			$absentdays = $totalDays - $payDays;
			echo  $contactualActiveEmp[$i] ['loginhandle'] . " " . $attndDays . "<br/>";
			echo "<br/>";
		}
	}
	/**************** END/ Add Contractual Leave ******************/
	
	
	/**************** END/ Add Holidays ******************/
	public function add_holidays(){
		$year = date("Y");
		$year = $year + 1;
		$yeardup = $year;
		$intdate = strtotime($year.'-01-01');
		$day = date('w', $intdate);
		$mon = date('n', $intdate);
		$mondup = $mon;
		$count = 0;

		$query = "SELECT ix_declared_leave FROM declared_leave WHERE DATE_FORMAT(dt_event_date,'%Y') = $year AND leave_type = 'G' LIMIT 1";
		$queryRES = $this->db->query($query);
		$queryResult = $queryRES->result_array();
		$queryNUM = COUNT($queryResult);

		if($queryNUM == 0)
		{
			
			while($yeardup == $year)
			{
				if($day == 0)
				{
					$chkDateSql = "INSERT INTO declared_leave (dt_event_date,s_event_name,leave_type) VALUES ('".date('Y-m-d',$intdate)."','Sunday','G')";
					$chkDateRes = $this->db->query($chkDateSql);
				}
				else if($day == 6 ){
					if($mondup == $mon)
					{
						$count +=1;
						if($count ==1)
						{
							$chkDateSql = "Insert Into declared_leave (dt_event_date,s_event_name,leave_type) Values('".date('Y-m-d',$intdate)."','First Saturday','G')";
							$chkDateRes = $this->db->query($chkDateSql);
						}
						elseif($count ==3)
						{
							$chkDateSql = "Insert Into declared_leave (dt_event_date,s_event_name,leave_type) Values('".date('Y-m-d',$intdate)."','Third Saturday','G')";
							$chkDateRes = $this->db->query($chkDateSql);
						}
					} 

				}
				echo date('Y-m-d',$intdate)."<br/>";
				$intdate=strtotime(date('Y-m-d', strtotime('+1 day', $intdate)));
				$day = date('w', $intdate);
				$mon=date('n', $intdate);
				$year=date('Y', $intdate);

				if($mondup != $mon){
					$count=0;
					$mondup=$mon;
				}
			}
		}


		$to      = 'hr@polosoftech.com';
		$subject = 'Mail for Holiday Add : '. date("Y-m-d H:i:s");
		$message = 'Holiday Add date : '. date("Y-m-d H:i:s");
		$headers = 'From: hr@polosoftech.com' . "\r\n" .
			'Reply-To: hr@polosoftech.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		//mail($to, $subject, $message, $headers);
	}
	/**************** END/ Add Holidays ******************/
	
	
	/**************** Add Intern Leave ******************/
	 public function add_intern_leave(){
		$empSQL = "SELECT login_id, full_name, join_date, loginhandle, leaveCreditedDate_intern FROM internal_user WHERE user_status = '1' AND emp_type = 'I' AND user_role != 1";

		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);

		if ($empNUM > 0) 
		{
			 
			$userIDsOf2LeaveCredited = $userIDsOf1LeaveCredited = "";
			$d = date("d");  
			$forMonth = date("Y") . "-" . $d;
			 for($i = 0 ;$i < $empNUM ; $i++)
			{
				 
				$numSLeave = '';
				
				$leaveCreditedDate = $empINFO[$i]['leaveCreditedDate_intern'];
				
				$PLdate = strtotime('+30 day', strtotime($leaveCreditedDate));
				
				$ENDYear = date("Y-m-d", strtotime(date("Y", strtotime($leaveCreditedDate)) . "-12-31"));
				if (date("Y-m-d", $PLdate) <= date("Y-m-d") && date("Y-m-d", $PLdate) <= $ENDYear) 
				{
					//echo hi;exit;
					$userIDsOf2LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
					$numPLeave = 1;
				} 
				else
				{
					 
					$userIDsOf1LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
					$numPLeave = 0;
				} 
				if ($numPLeave > 0) 
				{
					 
					$upSQL = "UPDATE `leave_carry_ forward` SET ob_pl = ob_pl + " . $numPLeave . " WHERE user_id = " . $empINFO[$i]["login_id"] . " AND year = '" . date("Y") . "' LIMIT 1";
					if ($this->db->query($upSQL)) 
					{
						$updateSQL = "UPDATE `internal_user` SET leaveCreditedDate_intern = '" . date("Y-m-d", $PLdate) . "' WHERE login_id = '" . $empINFO[$i]["login_id"] . "' LIMIT 1";
						$this->db->query($updateSQL);
						echo  $empINFO[$i]['loginhandle']."  -  ".$empINFO[$i]['leaveCreditedDate_intern']."<br/>";
					} 
				} 
			}
			if(date("Y-m-d", $PLdate) <= date("Y-m-d") && date("Y-m-d", $PLdate) <= $ENDYear)
			{
				$inSQL = "INSERT INTO leave_credited_history (creditedDate, userIDsOf2Leave, userIDsOf1Leave, forMonth) VALUES ('".date("Y-m-d H:i:s")."', '".$userIDsOf2LeaveCredited."', '".$userIDsOf1LeaveCredited."', '".$forMonth."')";
				$this->db->query($inSQL);
			} 
		}


		$to      = 'hr@polosoftech.com';
		$subject = 'Mail for Add Leave for Interns: '. date("Y-m-d H:i:s");
		$message = 'Add Leave for Interns Date date : '. date("Y-m-d H:i:s");
		$headers = 'From: hr@polosoftech.com' . "\r\n" .
			'Reply-To: hr@polosoftech.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		//mail($to, $subject, $message, $headers);
	}
	/**************** END/ Add Intern Leave ******************/
	
	
	/**************** Add PL ******************/
	function add_pl(){
		$empSQL = "SELECT login_id, full_name, join_date, loginhandle, leaveCreditedDate FROM internal_user WHERE user_status = '1' AND emp_type = 'F' AND user_role != 1";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);

		if($empNUM > 0) 
		{
			 
			$userIDsOf2LeaveCredited = $userIDsOf1LeaveCredited = "";
			$d = date("d");  
			$forMonth = date("Y") . "-" . $d;
			for($i = 0;$i<$empNUM; $i++){ 
				$numSLeave = '';
				
				$leaveCreditedDate = $empINFO[$i]['leaveCreditedDate'];
				
				$PLdate = strtotime('+16 day', strtotime($leaveCreditedDate));
				
				$ENDYear = date("Y-m-d", strtotime(date("Y", strtotime($leaveCreditedDate)) . "-12-31"));
				
				$toDate = date('Y-m-d');
				$empWdSQL = "SELECT login_id FROM attendance_new WHERE login_id = '" . $empINFO[$i]["login_id"] . "' AND (DATE(`date`) BETWEEN '$leaveCreditedDate' AND '$toDate')";
				$empWdRES = $this->db->query($empWdSQL);
				$empWdNUM = count($empWdRES->result_array());

				if($empWdNUM > 10){
					if(date("Y-m-d", $PLdate) <= date("Y-m-d") && date("Y-m-d", $PLdate) <= $ENDYear) 
					{
						$userIDsOf2LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
						$numPLeave = 1;
					} 
					else
					{  
						$userIDsOf1LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
						$numPLeave = 0;
					}
				} 
				else
				{  
					$userIDsOf1LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
					$numPLeave = 0;
				}
				
				if($numPLeave > 0) 
				{
					 
					$upSQL = "UPDATE `leave_carry_ forward` SET ob_pl = ob_pl + " . $numPLeave . " WHERE user_id = " . $empINFO[$i]["login_id"] . " AND year = '" . date("Y") . "' LIMIT 1";
					if ($this->db->query($upSQL)) 
					{
						$updateSQL = "UPDATE `internal_user` SET leaveCreditedDate = '" . date("Y-m-d", $PLdate) . "' WHERE login_id = '" . $empINFO[$i]["login_id"] . "' LIMIT 1";
						$this->db->query($updateSQL);
						echo  $empINFO[$i]['loginhandle']."  -  ".$empINFO[$i]['leaveCreditedDate']."<br/>";
					} 
				} 
			}
			if(date("Y-m-d", $PLdate) <= date("Y-m-d") && date("Y-m-d", $PLdate) <= $ENDYear)
			{
				$inSQL = "INSERT INTO leave_credited_history (creditedDate, userIDsOf2Leave, userIDsOf1Leave, forMonth) VALUES ('".date("Y-m-d H:i:s")."', '".$userIDsOf2LeaveCredited."', '".$userIDsOf1LeaveCredited."', '".$forMonth."')";
				$this->db->query($inSQL);
			} 
		}
		$to      = 'hr@polosoftech.com';
		$subject = 'Mail for Add PL : '. date("Y-m-d H:i:s");
		$message = 'Add PL Date date : '. date("Y-m-d H:i:s");
		$headers = 'From: hr@polosoftech.com' . "\r\n" .
			'Reply-To: hr@polosoftech.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		//mail($to, $subject, $message, $headers);
	}
	/**************** END/ Add PL ******************/ 
	
	
	/**************** Add PL Manual ******************/ 
	 public function add_pl_manual(){
		$empSQL = "SELECT login_id, name_first, join_date, loginhandle FROM internal_user WHERE user_status = '1' AND emp_type = 'F' AND user_role != 1";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);
		
		if($empNUM > 0){
			$userIDsOf2LeaveCredited = $userIDsOf1LeaveCredited = "";
			$m = date("m") - 1 ;
			$lastMonth16  = date("Y-m-d",strtotime(date("Y") . "-" . $m . "-16"));
			
			$forMonth = date("Y") . "-" . $m;
			$chkSQL = "SELECT creditedID FROM leave_credited_history WHERE forMonth = '". $forMonth ."' LIMIT 1";
			$chkRES = $this->db->query($chkSQL);
			$chkNUM = COUNT($chkRES->result_array());
			if($chkNUM == 0){
				for($i = 0; $i < $chkNUM; $i++) {
					$numLeave = 0;
					if(date("Y-m-d",strtotime($empINFO[$i]["join_date"])) < $lastMonth16){
						$userIDsOf2LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
						$numLeave = 2;
					}else{
						$userIDsOf1LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
						$numLeave = 1;
					}
					$upSQL = "UPDATE `leave_carry_ forward` SET ob_pl = ob_pl + ".$numLeave." WHERE user_id = ".$empINFO[$i]["login_id"]." AND year = '".date("Y")."' LIMIT 1";
					$this->db->query($upSQL);
				}

				$inSQL = "INSERT INTO leave_credited_history (creditedDate, userIDsOf2Leave, userIDsOf1Leave, forMonth) VALUES ('".date("Y-m-d H:i:s")."', '".$userIDsOf2LeaveCredited."', '".$userIDsOf1LeaveCredited."', '".$forMonth."')";
				$this->db->query($inSQL);

				echo $userIDsOf2LeaveCredited;
				echo "<br/>second<br/>"; 
				echo $userIDsOf1LeaveCredited;
			}else{
				echo "Already Credited";
			}
		}
	 }
	/*************** END/ Add PL Manual **************/
	
	
	/****************  Add SL ******************/
	public function add_sl()
	{
		//Get All Permanent Employee
		$empSQL = "SELECT login_id, full_name, join_date, loginhandle, leaveCreditedDateSl FROM internal_user WHERE user_status = '1' AND emp_type = 'F' AND user_role != 1";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = count($empINFO); 
		if($empNUM > 0)
		{    
			//Check For Leave Credited Entry
			$userIDsOf2LeaveCredited = $userIDsOf1LeaveCredited = "";
			$d = date("d");  
			$forMonth = date("Y") . "-" . $d;
			
			for($i=0; $i<count($empINFO); $i++)
			{ 
				$numSLeave=''; 
				$empJoinDate = $empINFO[$i]['join_date']; 
				$yearStart = date("Y-m-d",strtotime(date("Y")."-01-01"));
				
				$leaveCreditedDateSl = $empINFO[$i]['leaveCreditedDateSl']; 
				$SLdate = strtotime('+45 day', strtotime($leaveCreditedDateSl));  
				$ENDYear=date("Y-m-d",strtotime(date("Y",strtotime($leaveCreditedDateSl))."-12-31"));
				$selSQL = "SELECT * FROM `leave_carry_ forward` WHERE user_id = ".$empINFO[$i]["login_id"]." AND year = '".date("Y")."'";
				$resSQL = $this->db->query($selSQL);
				$resrow =  $empRES->result_array();
				$numrow =  count($resrow);
				if($numrow == 0)
				{
					$selSQL = "INSERT INTO `leave_carry_ forward` SET ob_sl = 1, user_id = ".$empINFO[$i]["login_id"].", year = '".date("Y")."'"; 
					$this->db->query($selSQL);
					$updateSQL = "UPDATE `internal_user` SET leaveCreditedDateSl = '".date("Y-m-d")."' WHERE login_id = '".$empINFO[$i]["login_id"]."' LIMIT 1";
					$this->db->query($updateSQL);
				}
				else
				{
					if($empJoinDate  <= $yearStart  && $yearStart == date("Y-m-d")){
						$userIDsOf2LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
						$numSLeave = 1;
					}
					else{
						if(date("Y-m-d",$SLdate) <= date("Y-m-d") && date("Y-m-d",$SLdate) <= $ENDYear)
						{
							$userIDsOf2LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
							$numSLeave = 1;
						}
						else
						{
							$userIDsOf1LeaveCredited .= $empINFO[$i]["login_id"] . ", ";
							$numSLeave = 0;
						} 
					}
					if($numSLeave > 0)
					{
						$upSQL = "UPDATE `leave_carry_ forward` SET ob_sl = ob_sl + ".$numSLeave." WHERE user_id = ".$empINFO[$i]["login_id"]." AND year = '".date("Y")."' AND ob_sl < 8  LIMIT 1";
						$this->db->query($upSQL);
						$updateSQL = "UPDATE `internal_user` SET leaveCreditedDateSl = '".date("Y-m-d",$SLdate)."' WHERE login_id = '".$empINFO[$i]["login_id"]."'  LIMIT 1";
						$this->db->query($updateSQL);
						echo  $empINFO[$i]['loginhandle']."  -  ".$empINFO[$i]['leaveCreditedDateSl']."<br/>";
					}
				}   
			}
			//Insert Into Leave Credited History Table
			if($userIDsOf2LeaveCredited !="")
			{
				$inSQL = "INSERT INTO leave_credited_history (creditedDate, userIDsOf2Leave, userIDsOf1Leave, forMonth) VALUES ('".date("Y-m-d H:i:s")."', '".$userIDsOf2LeaveCredited."', '".$userIDsOf1LeaveCredited."', '".$forMonth."')";
				$this->db->query($inSQL);
			}
		}


		$to      = 'hr@polosoftech.com';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Priority: 1 (Highest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n";
        $headers .= 'From: hr@polosoftech.com' . "\r\n";  
        $headers  .= 'Reply-To:  hr@polosoftech.com' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
		
		$subject = 'Mail for Add SL : '. date("Y-m-d H:i:s");
		$message = 'Add SL Date date : '. date("Y-m-d H:i:s");

		//mail($to, $subject, $message, $headers);
	}
	/**************** END/  Add SL ******************/
	
	/****************  Add SL Manual ******************/
	public function add_sl_manual()
	{
		//Get All Permanent Employee
		$empSQL = "SELECT login_id, full_name, join_date, loginhandle, leaveCreditedDate FROM internal_user WHERE user_status = '1' AND emp_type = 'F' AND user_role != 1";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = count($empINFO); 
		if($empNUM > 0){
			
			for($i=0; $i<count($empINFO); $i++)
			{ 
				$numSLeave='';
				$leaveCreditedDate = $empINFO[$i]['leaveCreditedDate'];
				$SLdate = strtotime('+45 day', strtotime($leaveCreditedDate)); 
				$ENDYear=date("Y-m-d",strtotime(date("Y",strtotime($leaveCreditedDate))."-12-31"));
				
				$selSQL = "Select * from `leave_carry_ forward` WHERE user_id = ".$empINFO[$i]["login_id"]." AND year = '".date("Y")."'";
				$resSQL = $this->db->query($selSQL);
				$numrow=  count($resSQL->result_array());
				if($numrow == 0){
					$selSQL = "INSERT INTO `leave_carry_ forward` SET ob_sl = 1, user_id = ".$empINFO[$i]["login_id"].", year = '".date("Y")."'";
					$this->db->query($selSQL);
					
					$updateSQL = "UPDATE `internal_user` SET leaveCreditedDate = '".date("Y-m-d")."' WHERE login_id = '".$empINFO[$i]["login_id"]."' LIMIT 1";
					$this->db->query($updateSQL);
				}
				else{            
					if(date("Y-m-d",$SLdate) <= date("Y-m-d") && date("Y-m-d",$SLdate) <= $ENDYear){
						 $numSLeave = 1;
					 }
					 else
						 $numSLeave = 0;

					if($numSLeave > 0) {

						$upSQL = "UPDATE `leave_carry_ forward` SET ob_sl = ob_sl + ".$numSLeave." WHERE user_id = ".$empINFO[$i]["login_id"]." AND year = '".date("Y")."' AND ob_sl < 8 LIMIT 1";
						$this->db->query($upSQL);
						$updateSQL = "UPDATE `internal_user` SET leaveCreditedDate = '".date("Y-m-d",$SLdate)."' WHERE login_id = '".$empINFO[$i]["login_id"]."' LIMIT 1";
						$this->db->query($updateSQL);
						echo  $empINFO[$i]['loginhandle']."  -  ".$empINFO[$i]['leaveCreditedDate']."<br/>";
					}
				
			   }
			}
		}			
		$to      = 'hr@polosoftech.com';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Priority: 1 (Highest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n";
        $headers .= 'From: hr@polosoftech.com' . "\r\n";  
        $headers  .= 'Reply-To:  hr@polosoftech.com' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
		
		$subject = 'Mail for Add SL Manual : '. date("Y-m-d H:i:s");
		$message = 'Add SL Manual Date date : '. date("Y-m-d H:i:s");

		//mail($to, $subject, $message, $headers);
	}
	/**************** END/  Add SL Manual ******************/
	
	
	/****************  AP App Email History ******************/
	public function ap_app_email_history()
	{
		//aabsys db connection
		/* $DB_HOST = '72.3.216.40';
		$DB_NAME = 'aabsys_wordpress';
		$DB_USER = 'aabsyswpdbuser';
		$DB_PASSWORD = 'Fyp4v22*A@BSyS';  */ 
		// Create connection for aabsys
		$this->db1 = $this->load->database('otherdb', TRUE); 
		$today = date('Y-m-d'); 
		$query = $this->db1->query("SELECT * FROM `ap_app_email_history` WHERE DATE(email_date) = '".$today."'");
		$row = $query->result_array(); 
		//print_r($row);
		echo count($row);  
		 if(count($row) > 0)
		{ 
			for ($i=0; $i<count($row); $i++) 
			{
				$app_id = $row[$i]['app_id'];
				$email_template_name = $row[$i]['email_template_name'];
				$email_remark = $row[$i]['email_remark'];
				$email_date = $row[$i]['email_date']; 

				$this->db = $this->load->database('default', TRUE); 
				
				$sql = "INSERT INTO `ap_app_email_history`(`app_id`, `email_template_name`, `email_remark`, `email_date`)
				VALUES ('$app_id', '$email_template_name', '$email_remark', '$email_date')";
				$this->db->query($sql);
			}
		}  
	}
	/**************** END/  AP App Email History ******************/
	
	/****************  AP App User Info ******************/
	/* public function ap_app_user_info()
	{
		// Create connection for aabsys
		$this->db1 = $this->load->database('otherdb', TRUE); 
		$today = date('Y-m-d'); 
		$query = $this->db1->query("SELECT * FROM `ap_app_user_info` WHERE DATE(request_date) = '".$today."'");
		$row = $query->result_array(); 
		//print_r($row);
		echo count($row);  
		 if(count($row) > 0)
		{ 
			for ($i=0; $i<count($row); $i++) 
			{
				$request_date 			= $row[$i]['request_date'];
				$last_updated 			= $row[$i]['last_updated'];
				$job_id 				= $row[$i]['job_id'];
				$first_name 			= $row[$i]['first_name'];
				$last_name 				= $row[$i]['last_name'];
				$email 					= $row[$i]['email'];
				$tel 					= $row[$i]['tel'];
				$gender 				= $row[$i]['gender'];
				$marital_status 		= $row[$i]['marital_status'];
				$open_for_relocation 	= $row[$i]['open_for_relocation'];
				$cur_designation 		= $row[$i]['cur_designation'];
				$cur_company 			= $row[$i]['cur_company'];
				$cur_location 			= $row[$i]['cur_location'];
				$cur_ctc 				= $row[$i]['cur_ctc'];
				$exp_ctc 				= $row[$i]['exp_ctc'];
				$notice_period 			= $row[$i]['notice_period'];
				$tot_yr_exp 			= $row[$i]['tot_yr_exp'];
				$highest_qualification 	= $row[$i]['highest_qualification'];
				$passing_year 			= $row[$i]['passing_year'];
				$specialization 		= $row[$i]['specialization'];
				$institution_name 		= $row[$i]['institution_name'];
				$key_skills 			= $row[$i]['key_skills'];
				$employed_aabsys 		= $row[$i]['employed_aabsys'];
				$ea_from_date 			= $row[$i]['ea_from_date'];
				$ea_to_date 			= $row[$i]['ea_to_date'];
				$interviewed_aabsys 	= $row[$i]['interviewed_aabsys'];
				$ia_date 				= $row[$i]['ia_date'];
				$employee_name 			= $row[$i]['employee_name'];
				$employee_code 			= $row[$i]['employee_code'];
				$emp_joining_date 		= $row[$i]['emp_joining_date'];
				$title 					= $row[$i]['title'];
				$filename 				= $row[$i]['filename'];
				$cv 					= $row[$i]['cv'];
				$file_hash 				= $row[$i]['file_hash'];
				$cover_note 			= $row[$i]['cover_note'];
				$admin_notes 			= $row[$i]['admin_notes'];
				$notify 				= $row[$i]['notify'];
				$notify_admin 			= $row[$i]['notify_admin'];
				$status 				= $row[$i]['status'];
				$contact_status 		= $row[$i]['contact_status'];
				$shortlisted 			= $row[$i]['shortlisted'];
				$hr_rating 				= $row[$i]['hr_rating'];
				$hr_desc 				= $row[$i]['hr_desc'];
				$interview_sch 			= $row[$i]['interview_sch'];
				$interview_date 		= $row[$i]['interview_date'];
				$interview_desc 		= $row[$i]['interview_desc'];
				$interviewer 			= $row[$i]['interviewer'];
				$rm_rating 				= $row[$i]['rm_rating'];
				$rm_desc 				= $row[$i]['rm_desc'];
				$dh_rating 				= $row[$i]['dh_rating'];
				$dh_desc 				= $row[$i]['dh_desc'];
				$offer_date 			= $row[$i]['offer_date'];

				$this->db = $this->load->database('default', TRUE); 
				
				$sql = "INSERT INTO ap_app_user_info
						(`request_date`, `last_updated`, `job_id`, `first_name`, `last_name`, `email`, `tel`, `gender`, `marital_status`, `open_for_relocation`, `cur_designation`, `cur_company`, `cur_location`, `cur_ctc`, `exp_ctc`, `notice_period`, `tot_yr_exp`, `highest_qualification`, `passing_year`, `specialization`, `institution_name`, `key_skills`, `employed_aabsys`, `ea_from_date`, `ea_to_date`, `interviewed_aabsys`, `ia_date`, `employee_name`, `employee_code`, `emp_joining_date`, `title`, `filename`, `cv`, `file_hash`, `cover_note`, `admin_notes`, `notify`, `notify_admin`, `status`, `contact_status`, `shortlisted`, `hr_rating`, `hr_desc`, `interview_sch`, `interview_date`, `interview_desc`, `interviewer`, `rm_rating`, `rm_desc`, `dh_rating`, `dh_desc`, `offer_date`)
						VALUES ('$request_date', '$last_updated', '$job_id', '$first_name', '$last_name', '$email', '$tel', '$gender', '$marital_status', '$open_for_relocation', '$cur_designation', '$cur_company', '$cur_location', '$cur_ctc', '$exp_ctc', '$notice_period', '$tot_yr_exp', '$highest_qualification', '$passing_year', '$specialization', '$institution_name', '$key_skills', '$employed_aabsys', '$ea_from_date', '$ea_to_date', '$interviewed_aabsys', '$ia_date', '$employee_name', '$employee_code', '$emp_joining_date', '$title', '$filename', '$cv', '$file_hash', '$cover_note', '$admin_notes', '$notify', '$notify_admin', '$status', '$contact_status', '$shortlisted', '$hr_rating', '$hr_desc', '$interview_sch', '$interview_date', '$interview_desc', '$interviewer', '$rm_rating', '$rm_desc', '$dh_rating', '$dh_desc', '$offer_date')";
				$this->db->query($sql);
			}
		}  
	} */
	/**************** END/  AP App User Info ******************/
	
	/****************  AP App Posts ******************/
	public function ap_posts()
	{
		// Create connection for aabsys
		$this->db1 = $this->load->database('otherdb', TRUE); 
		$today = date('Y-m-d'); 
		$query = $this->db1->query("SELECT * FROM `ap_posts` WHERE DATE_FORMAT(post_date, '%Y-%m-%d') = '".$today."'");
		$row = $query->result_array(); 
		print_r($row);
		echo count($row);  
		 if(count($row) > 0)
		{ 
			for ($i=0; $i<count($row); $i++) 
			{
				$post_author = $row[$i]['post_author'];
				$post_date = $row[$i]['post_date'];
				$post_date_gmt = $row[$i]['post_date_gmt'];
				$post_content = $row[$i]['post_content'];
				$post_title = $row[$i]['post_title'];
				$post_excerpt = $row[$i]['post_excerpt'];
				$post_status = $row[$i]['post_status'];
				$comment_status = $row[$i]['comment_status'];
				$ping_status = $row[$i]['ping_status'];
				$post_password 	= $row[$i]['post_password'];
				$post_name = $row[$i]['post_name'];
				$to_ping = $row[$i]['to_ping'];
				$pinged = $row[$i]['pinged'];
				$post_modified 	= $row[$i]['post_modified'];
				$post_modified_gmt 	= $row[$i]['post_modified_gmt'];
				$post_content_filtered 	= $row[$i]['post_content_filtered'];
				$post_parent = $row[$i]['post_parent'];
				$guid = $row[$i]['guid'];
				$menu_order = $row[$i]['menu_order'];
				$post_type = $row[$i]['post_type'];
				$post_mime_type = $row[$i]['post_mime_type'];
				$comment_count = $row[$i]['comment_count'];

				$this->db = $this->load->database('default', TRUE); 
				
				$sql = "INSERT INTO `ap_posts`(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
				VALUES ('$post_author', '$post_date', '$post_date_gmt', '$post_content', '$post_title', '$post_excerpt', '$post_status', '$comment_status', '$ping_status', '$post_password', '$post_name', '$to_ping', '$pinged', '$post_modified', '$post_modified_gmt', '$post_content_filtered', '$post_parent', '$guid', '$menu_order', '$post_type', '$post_mime_type', '$comment_count')";
				$this->db->query($sql);
			}
		}

		$message = "This is Resume update in POLOHRM";
		$to = "hr@polosoftech.com";
		$subject = 'Resume update in POLOHRM';      
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Support <saurav.mohapatra@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: No Reply <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
	}
	public function ap_app_user_info()
	{
		// Create connection for aabsys
		$this->db1 = $this->load->database('otherdb', TRUE); 
		$today = date('Y-m-d'); 
		$query = $this->db1->query("SELECT * FROM `ap_app_user_info` WHERE DATE_FORMAT(request_date, '%Y-%m-%d') = '".$today."'");
		$row = $query->result_array(); 
		print_r($row);
		echo count($row);  
		 if(count($row) > 0)
		{ 
			for ($i=0; $i<count($row); $i++) 
			{
				$request_date 			= mysql_real_escape_string($row[$i]['request_date']);
				$last_updated 			= mysql_real_escape_string($row[$i]['last_updated']);
				$job_id 				= mysql_real_escape_string($row[$i]['job_id']);
				$first_name 			= mysql_real_escape_string($row[$i]['first_name']);
				$last_name 				= mysql_real_escape_string($row[$i]['last_name']);
				$email 					= mysql_real_escape_string($row[$i]['email']);
				$tel 					= mysql_real_escape_string($row[$i]['tel']);
				$gender 				= mysql_real_escape_string($row[$i]['gender']);
				$marital_status 		= mysql_real_escape_string($row[$i]['marital_status']);
				$open_for_relocation 	= mysql_real_escape_string($row[$i]['open_for_relocation']);
				$cur_designation 		= mysql_real_escape_string($row[$i]['cur_designation']);
				$cur_company 			= mysql_real_escape_string($row[$i]['cur_company']);
				$cur_location 			= mysql_real_escape_string($row[$i]['cur_location']);
				$cur_ctc 				= mysql_real_escape_string($row[$i]['cur_ctc']);
				$exp_ctc 				= mysql_real_escape_string($row[$i]['exp_ctc']);
				$notice_period 			= mysql_real_escape_string($row[$i]['notice_period']);
				$tot_yr_exp 			= mysql_real_escape_string($row[$i]['tot_yr_exp']);
				$highest_qualification 	= mysql_real_escape_string($row[$i]['highest_qualification']);
				$passing_year 			= mysql_real_escape_string($row[$i]['passing_year']);
				$specialization 		= mysql_real_escape_string($row[$i]['specialization']);
				$institution_name 		= mysql_real_escape_string($row[$i]['institution_name']);
				$key_skills 			= mysql_real_escape_string($row[$i]['key_skills']);
				$employed_aabsys 		= mysql_real_escape_string($row[$i]['employed_aabsys']);
				$ea_from_date 			= mysql_real_escape_string($row[$i]['ea_from_date']);
				$ea_to_date 			= mysql_real_escape_string($row[$i]['ea_to_date']);
				$interviewed_aabsys 	= mysql_real_escape_string($row[$i]['interviewed_aabsys']);
				$ia_date 				= mysql_real_escape_string($row[$i]['ia_date']);
				$employee_name 			= mysql_real_escape_string($row[$i]['employee_name']);
				$employee_code 			= mysql_real_escape_string($row[$i]['employee_code']);
				$emp_joining_date 		= mysql_real_escape_string($row[$i]['emp_joining_date']);
				$title 					= mysql_real_escape_string($row[$i]['title']);
				$filename 				= mysql_real_escape_string($row[$i]['filename']);
				$cv 					= mysql_real_escape_string($row[$i]['cv']);
				$file_hash 				= mysql_real_escape_string($row[$i]['file_hash']);
				$cover_note 			= mysql_real_escape_string($row[$i]['cover_note']);
				$admin_notes 			= mysql_real_escape_string($row[$i]['admin_notes']);
				$notify 				= mysql_real_escape_string($row[$i]['notify']);
				$notify_admin 			= mysql_real_escape_string($row[$i]['notify_admin']);
				$status 				= mysql_real_escape_string($row[$i]['status']);
				$contact_status 		= mysql_real_escape_string($row[$i]['contact_status']);
				$shortlisted 			= mysql_real_escape_string($row[$i]['shortlisted']);
				$hr_rating 				= mysql_real_escape_string($row[$i]['hr_rating']);
				$hr_desc 				= mysql_real_escape_string($row[$i]['hr_desc']);
				$interview_sch 			= mysql_real_escape_string($row[$i]['interview_sch']);
				$interview_date 		= mysql_real_escape_string($row[$i]['interview_date']);
				$interview_desc 		= mysql_real_escape_string($row[$i]['interview_desc']);
				$interviewer 			= mysql_real_escape_string($row[$i]['interviewer']);
				$rm_rating 				= mysql_real_escape_string($row[$i]['rm_rating']);
				$rm_desc 				= mysql_real_escape_string($row[$i]['rm_desc']);
				$dh_rating 				= mysql_real_escape_string($row[$i]['dh_rating']);
				$dh_desc 				= mysql_real_escape_string($row[$i]['dh_desc']);
				$offer_date 			= mysql_real_escape_string($row[$i]['offer_date']);

				$this->db = $this->load->database('default', TRUE); 
				
				$sql = "INSERT INTO ap_app_user_info
		(`request_date`, `last_updated`, `job_id`, `first_name`, `last_name`, `email`, `tel`, `gender`, `marital_status`, `open_for_relocation`, `cur_designation`, `cur_company`, `cur_location`, `cur_ctc`, `exp_ctc`, `notice_period`, `tot_yr_exp`, `highest_qualification`, `passing_year`, `specialization`, `institution_name`, `key_skills`, `employed_aabsys`, `ea_from_date`, `ea_to_date`, `interviewed_aabsys`, `ia_date`, `employee_name`, `employee_code`, `emp_joining_date`, `title`, `filename`, `cv`, `file_hash`, `cover_note`, `admin_notes`, `notify`, `notify_admin`, `status`, `contact_status`, `shortlisted`, `hr_rating`, `hr_desc`, `interview_sch`, `interview_date`, `interview_desc`, `interviewer`, `rm_rating`, `rm_desc`, `dh_rating`, `dh_desc`, `offer_date`)
		VALUES ('$request_date', '$last_updated', '$job_id', '$first_name', '$last_name', '$email', '$tel', '$gender', '$marital_status', '$open_for_relocation', '$cur_designation', '$cur_company', '$cur_location', '$cur_ctc', '$exp_ctc', '$notice_period', '$tot_yr_exp', '$highest_qualification', '$passing_year', '$specialization', '$institution_name', '$key_skills', '$employed_aabsys', '$ea_from_date', '$ea_to_date', '$interviewed_aabsys', '$ia_date', '$employee_name', '$employee_code', '$emp_joining_date', '$title', '$filename', '$cv', '$file_hash', '$cover_note', '$admin_notes', '$notify', '$notify_admin', '$status', '$contact_status', '$shortlisted', '$hr_rating', '$hr_desc', '$interview_sch', '$interview_date', '$interview_desc', '$interviewer', '$rm_rating', '$rm_desc', '$dh_rating', '$dh_desc', '$offer_date')";
				$this->db->query($sql);
			}
		}

		$message = "This is Resume update user info in icompass";
		$to = "hr@polosoftech.com";
		$subject = 'Resume update user info in icompass';      
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Support <saurav.mohapatra@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: No Reply <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
	}
	/**************** END/  AP App Posts ******************/
	
	/****************  Check Unique Abbr ******************/
	public function check_unique_abbr()
	{
		$Sql = "SELECT * FROM `internal_user` where name_abbr=''";
		$Res = $this->db->query($Sql);
		$Info = $Res->result_array(); 
		for ($i=0; $i<count($Info); $i++) {
			$mid='';
			$i=1;
			$all_name='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			//echo $full_name;exit;
			do{ 
				$nameAbbr = substr($Info[$i]['name_first'],0,1).substr($all_name,$i,1).substr($Info[$i]['name_last'],0,1);//nameAbbr should be 3 chars
				$nameAbbr = strtoupper($nameAbbr);
				$i++;
			   echo $nameAbbr.'<br/>';
			}while($this->checkUniqueName($nameAbbr));
				
			$this->db->query("UPDATE `internal_user` SET name_abbr='$nameAbbr' WHERE login_id='".$Info[$i]['login_id']."'"); 
		} 
	}
	function checkUniqueName($abbrText)
	{
		$nameQuery = "SELECT `login_id` FROM `internal_user` WHERE `name_abbr` = '$abbrText' LIMIT 1";
		$nameRes = $this->db->query($nameQuery);
		$nameNum = count($nameRes->result_array());
		if($nameNum == 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	/**************** END/  Check Unique Abbr ******************/
	
	
	/****************  Contractual Leave Carry ******************/
	public function contratual_leave_cary(){
		$yy = date("Y"); 
		$prevYear = $yy - 1;
		$empSQL = "SELECT i.login_id , i.join_date, i.emp_type,i.loginhandle FROM `internal_user` i WHERE i.user_status = '1' AND i.emp_type='C' AND i.sal_sheet_sl_no != '0'";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);
		if($empNUM >0){
			$i = 0;
			$startYear = 2011;
			$yearCompletedAsPerThis = 0;
			for($i =0;$i<$empNUM;$i++){
				$avlPL =$avlSL =$cfPL =0;
				 $joinDate = $empINFO[$i]['join_date'];
				 $joinYear = date('Y',strtotime($joinDate));
				 
				 $maxPL = $this->getMaxLeave($empINFO[$i]['login_id'], 'P', $prevYear);
				 $maxSL = $this->getMaxLeave($empINFO[$i]['login_id'], 'S', $prevYear);
				 $curLeave = $this->getLeaveTaken($empINFO[$i]['login_id'], '12', $prevYear, 'A');
				 $takenPL = 0;
				 $takenSL = 0;
				 if($curLeave['ob_pl'] > 0){
					 $takenPL = $curLeave['ob_pl'];
				 }
				 if($curLeave['ob_sl'] > 0){
					 $takenSL = $curLeave['ob_sl'];
				 }
				 $avlPL = $maxPL - $takenPL;
				 $avlSL = $maxSL - $takenSL;
				 $cfPL = $avlPL;
				 $cashPL = 0;
				if($empINFO[$i]['emp_type']=='F'){	 
					if($joinYear > $startYear){
						$startYear = $joinYear;
					}
					$yearCompletedAsPerThis = $prevYear - $startYear;
					 
					if($yearCompletedAsPerThis == 0){
						 if($avlPL > 10){
							$cashPL = $avlPL - 10;
							$cfPL = 10;
						}
					}else if($yearCompletedAsPerThis == 1){
						 if($avlPL > 20){
							$cashPL = $avlPL - 20;
							$cfPL = 20;
						}
					}else{
						   if($avlPL > 22){
							$cashPL = $avlPL - 22;
							$cfPL = 22;
							}
					}
					
					if($cashPL > 15){
						$cashPL = 15;
					}
				}
					
				 if($cfPL > 0){
					$cfSQL = "SELECT `id` FROM `leave_carry_ forward` WHERE `year` = '".$yy."' AND `user_id` = '".$empINFO[$i]['login_id']."' LIMIT 1";
					$cfRES = $this->db->query($cfSQL);
					$cfNUM = COUNT($cfRES->result_array());
					if($cfNUM == 1){
						$ssql="UPDATE `leave_carry_ forward` SET `ob_pl` = '".$cfPL."', `cf_pl` = '".$cfPL."' WHERE `user_id` = '".$empINFO[$i]['login_id']."' AND `year` = '".$yy."' LIMIT 1";
						$this->db->query($ssql);
					}
					else {
						$ssql="INSERT INTO `leave_carry_ forward` SET `ob_pl` = '".$cfPL."', `cf_pl` = '".$cfPL."', `user_id` = '".$empINFO[$i]['login_id']."', `year` = '".$yy."'";         
						$this->db->query($ssql); 
					}                 
					echo $empINFO[$i]['loginhandle']."<br/>";
				 }
			}
		}
	}
		
	public function getMaxLeave($userID, $type = 'A', $year = ''){
		if($year == '')
		{
			$year = date("Y");
		}
		$joinDtSql = "SELECT i.`join_date`, f.`ob_pl`, f.`ob_sl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '".$year."' WHERE i.`login_id` = '".$userID."'";
		$joinDtRes = $this->db->query($joinDtSql);
		$joinInfo = $joinDtRes->result_array();
		
		if(count($joinInfo)>0){
			$joinDate  = date("d", strtotime($joinInfo[0]['join_date']));
			$joinMonth = date("m", strtotime($joinInfo[0]['join_date']));
			$joinYear  = date("Y", strtotime($joinInfo[0]['join_date']));
			if($year <=2013)
			{
				if($type == 'P')
				{
					$maxLeave = 22;
					$carryForwardLeave = $joinInfo['ob_pl'];
				}
				elseif($type == 'S')
				{
					$maxLeave = 8;
					$carryForwardLeave = $joinInfo['ob_sl'];
				}
				else
				{
					$maxLeave = 30;
					$carryForwardLeave = $joinInfo['ob_pl'] + $joinInfo['ob_sl'];
				}
				if($year > $joinYear)
				{
					$maxLeaveForThisYear = $maxLeave + $carryForwardLeave;
				}
				else
				{
					if($joinDate <= 15)
					{
						$remainingMonth =  12 - ($joinMonth - 1);
					}
					else
					{
						$remainingMonth = 12 - $joinMonth;
					}
					$maxLeaveForThisYear = ceil(($maxLeave / 12 ) * $remainingMonth);
				}
			}
			else
			{
				if($type == 'P')
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_pl'];
				}
				elseif($type == 'S')
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_sl'];
				}
				else
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_pl'] + $joinInfo[0]['ob_sl'];
				}
			}
		}
		else{
			$maxLeaveForThisYear = 0;
		}

		return $maxLeaveForThisYear;
	}
	
	public function getLeaveTaken($userID, $month, $year, $type = 'C'){
		if($type == 'C')
		{
			$leaveSQL = "SELECT `ob_pl`, `ob_sl` FROM `leave_info` WHERE `login_id` = '$userID' AND `month` = '$month' AND `year` = '$year'";
		}
		elseif($type == 'A')
		{
			$leaveSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` <= '$month' AND `year` = '$year'";
		}

		$leaveRES = $this->db->query($leaveSQL);
		$leaveNUM = count($leaveRES->result_array());

		if($leaveNUM > 0)
		{
			$leaveINFO = $leaveRES->result_array();
			if(count($leaveINFO) > 0)
			{
				$leaveINFO = Array ( "ob_pl" => $leaveINFO[0]['ob_pl'], "ob_sl" => $leaveINFO[0]['ob_sl']) ;
			}
			else
			{
				$leaveINFO = Array ( "ob_pl" => 0, "ob_sl" => 0 ) ;
			}
		}
		else
		{
			$leaveINFO = Array ( "ob_pl" => 0, "ob_sl" => 0 ) ;
		}

		return $leaveINFO;
	}
	/************* END/  Contractual Leave Carry ***************/
	
	
	/************* Reminder  ***************/
	public function reminder(){
		$todayP7  = date("Y-m-d",strtotime('+15 days'));
		$empSQL = "SELECT i.full_name, i.gender, i.designation, i.employee_conform, i.emp_type, d.desg_name, r.email, r.full_name AS repName FROM internal_user AS i 
		INNER JOIN user_desg AS d ON d.desg_id = i.designation
		INNER JOIN internal_user AS r ON i.reporting_to = r.login_id
		WHERE i.user_status = '1' AND i.confirm_status != 'Confirmed' AND i.employee_conform = '".$todayP7."'";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);
		if($empNUM > 0)
		{
			for($i = 0; $i < $empNUM; $i++){
				$endDate = date("jS F, Y", strtotime($empINFO[$i]['employee_conform']));
				$this->reminderForProbationOrContract($empINFO[$i]['repName'], $empINFO[$i]['email'], $empINFO[$i]['full_name'], $empINFO[$i]['desg_name'], $endDate, $empINFO[$i]['emp_type'], $empINFO[$i]['gender']);
			}
		}

		$birthDayP  = date("m-d");
		$bDateText = date("F j, Y");
		$subject = "Wish you many many happy returns of the day!";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'Bcc: hr@polosoftech.com' . "\r\n";
		$headers .= 'Reply-To: SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		$birthdaySQL = "SELECT login_id, email, user_photo_name FROM internal_user WHERE dob_with_current_year = '".$birthDayP."' AND user_status = '1'";
		$birthdayRES = $this->db->query($birthdaySQL);
		$birthdayINFO = $birthdayRES->result_array();
		$birthdayNUM = COUNT($birthdayINFO);
		$sitepath = base_url();
		if($birthdayNUM > 0){
			for($j = 0; $j < $birthdayNUM ; $j++){
				if($birthdayINFO[$j]["email"] == "hr@polosoftech.com"){
					$profileImage = $sitepath . "assets/images/wishes/pic.jpg";
				}elseif($birthdayINFO[$j]["user_photo_name"] != ""){
					$profileImage = $sitepath . "assets/upload/profile/" . $birthdayINFO[$j]["user_photo_name"];
				}else{
					$profileImage = $sitepath . "assets/images/no-image.jpg";
				}
				
				$encodedUserId = base64_encode($birthdayINFO[$j]["login_id"]);
				
				$message = <<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Define Charset -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Responsive Meta Tag -->
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

<!-- Page Title -->
<title> Birthday Mailer </title>

<!-- Responsive Styles and Valid Styles -->
<style type="text/css">

body
{width: 100%; background-color: #282828; margin:0; padding:0; }
html
{width: 100%; }
@media only screen and (max-width: 640px) {

body{ width:auto!important; }

/* Box Wrap */
.BoxWrap { width:440px !important;  }

/* Head  */
.HeaderTable { width:440px !important; margin:0 auto !important; }

.SizeHeadTable  {  width:440px !important; }
.HeadWrap  {  width:400px !important; }
.HeadTitle { font-size:22px !important;  }
.HeadTxt { font-size:15px !important; }
/* Section 1  */
.SecTable1 { width:440px !important; }
.SecTxt1 { width:400px !important;   padding:0 0 10px !important;  margin:0 auto !important; } 
.SecTitle1 { font-size:17px !important; }
.SecPrg1 { font-size:15px !important;  line-height:1.9 !important; } 


/* Footer  */
.Footer1 { height:100px !important; }
.Footer1Wrap {width:440px !important;}
.Footer1Title {width:440px !important; text-align:center !important;}
.Footer1Social { width:280px !important; margin:0 auto !important; }
.Footer1SocialW { width:280px !important; margin:0 auto !important; }
.Footer2W {width:440px !important;}

/* Hide  */
.RespoHide { display:none !important; }


}
		


@media only screen and (max-width: 479px)  {

body{width:auto!important;}

/* Box Wrap */
.BoxWrap { width:280px !important;  }

/* Head  */
.HeaderTable { width:280px !important; margin:0 auto !important; }
.SizeHeadTable img {  width:280px !important; height:140px !important; }
.SizeHeadTable  {  width:280px !important; }
.HeadWrap  {  width:260px !important; }
.HeadTitle { font-size:18px !important;  }
.HeadTxt { font-size:13px !important; }
.date p img { width:15px; }
.date p { font-size:11px!important; line-height:14px !important; }
.Head2Txt { width:260px !important; }
/* Section 1  */
.SecTable1 { width:280px !important; }
.SecTxt1 { width:260px !important;   padding:0 0 10px !important;  margin:0 auto !important; } 
.SecTitle1 { font-size:15px !important; }
.SecPrg1 { font-size:20px !important; line-height:1.9 !important; } 

/* Footer  */
.Footer1 { height:160px !important; }
.Footer1Td { text-align:center !important; line-height: 3.2 !important;  }
.Footer1Wrap {width:280px !important;}
.Footer1Title {width:280px !important; text-align:center !important;}
.Footer1Social { width:200px !important; margin:0 auto !important; }
.Footer1SocialW { width:200px !important; margin:0 auto !important; }
.Footer2W {width:280px !important;}
.Footer2T1 { font-size:9px !important; }
.Footer2T2 { }

.RespoShow { width:100% !important; height:20px !important; }
.BoxWrap2 { padding:0 0 30px !important; }
/* Hide  */
.RespoHide { display:none !important; }	

}
</style>

</head>

<body topmargin="0" marginheight="0" marginwidth="0" leftmargin="0">


<!-- Main Container -->
<table bgcolor="#343434" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td width="100%" valign="top">
		   <!-- Header Start -->
		   <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td width="100%" bgcolor="#282828">
					
						<!-- Start Header Content -->
						<table width="580" class="HeaderTable" border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
								<td width="580">
									
									<!-- Logo -->
									<table border="0" cellpadding="0" cellspacing="0" align="left" >
										<tr>
											<td height="14"></td>
										</tr>
										<tr>
											<td height="50">
												<a href="#"><img mc:edit="logo" src="{$sitepath}assets/images/aabsys_logo.png" alt="" border="0"></a>
											</td>
										</tr>
										<tr>
											<td height="12" class="RespoHide"></td>
										</tr>
									</table>
									
									<!-- Date -->
									<table border="0" cellpadding="0" cellspacing="0" align="right">
										<tr>
											<td height="13" ></td>
										</tr>
										<tr>
											<td mc:edit="date" height="50" class="date" style="font-size: 13px; color: #ffffff; font-weight: light; text-align: right; font-family: Helvetica, Arial, sans-serif; line-height: 20px; vertical-align: middle;">
											<p> <img src="{$sitepath}assets/images/wishes/icon.jpg" style="float:left;" alt="" border="0">{$bDateText}</p>	 
											</td>
										</tr>
										<tr>
											<td height="12"></td>
										</tr>
									</table>
																	
								</td>
							</tr>
						</table><!-- End Header Wrapper -->
						
					</td>
				</tr>
			</table><!-- End Header -->
			
			<!-- Head2 Section -->
			<table class="WideWrap" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style=" border-top:1px solid #383838; ">
				<tr>
					<td width="100%" valign="top" >
					
						<!-- Box Wrapper -->
						<table class="BoxWrap" width="580" border="0" cellpadding="0" cellspacing="0" align="center" style="margin:24px auto 0; background-color:#282828; border:1px solid #383838;">
							
								<tr>
																<td class="SizeHeadTable" style="background-image:url('{$profileImage}'); background-repeat: no-repeat; background-position: left top; background-size:26%;">
								  <img src="{$sitepath}assets/images/wishes/happybirthday_frame_2.png"></td>
							</tr>
							
						</table><!-- End Box Wrapper -->
		
					</td>
				</tr>
					 <tr><td height="20"></td></tr>
			</table><!-- End Head Section -->

			
			 <!-- Footer Wide Wrap -->
			<table class="WideWrap" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td width="100%" valign="top" >
					
						<!-- Footer1 Background -->						<!-- Footer1 Background -->
		
							<!-- Footer2 Background -->
						<table height="96" class="Footer2" width="100%" border="0" style="background-color:#222222;" cellpadding="0" cellspacing="0" align="center" >
							 <tr>
							 <td>
							 <table width="580" border="0"  cellpadding="0" cellspacing="0" align="center" class="Footer2W">
								<tr>
									 <td>
									  <table  border="0" cellpadding="0" cellspacing="0" align="center" >
										<tr>
								<td valign="top" height="20" align="center"  ></td>
							</tr>
									<tr>
										<td mc:edit="Footer2T" align="center" class="Footer2T1" style=" font-weight:400; font-size: 12px; color: #b2b3b6; font-weight: bold; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 1; vertical-align: top;">
															 
										<p  style=" font-weight:400; text-decoration: none; color: #b2b3b6">  <img alt="" src="{$sitepath}assets/images/wishes/icon4.jpg" /> <b style="color:#b2b3b6;  font-weight:600;"> Having problems? </b>  <a href="{$sitepath}/en/news_and_events" target="_blank" style="font-weight:600; text-decoration: none;  color:#ffa422"> view it online</a></p>
										</td>
							
									</tr>
								</table>
								  <table  border="0" cellpadding="0" cellspacing="0" align="center" >
									<tr>
										<td class="Footer2T2" mc:edit="copyright" style="font-weight:400; font-size: 12px; color: #b2b3b6; font-weight: bold; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 1; vertical-align: top;">
										 <p>&nbsp;</p>
										</td>
							
									</tr>
								</table>
									 </td>
								</tr>
							 </table>
							 </td>
							 </tr>							 
						</table><!-- Footer1 Background -->
						
					</td>
				</tr>
					 
			</table><!-- End Footer Basic Wrapper -->
			
		</td>
	</tr>	
</table>

</body>
</html>
EOD;
																						
echo $message;
				//mail($birthdayINFO[$j]["email"], $subject, $message, $headers); 
			}
		}
	}
	
	public function  reminderForProbationOrContract($reportingName, $reportingEmail, $empName, $empDesg, $probContDate, $empType, $sex){
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
            <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
			</div>';
			$footer = '<a href="https://www.facebook.com/polosoftechnologies/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
			$fromAABSySiCompass = "<p>For necessary technical support, please contact POLOSOFT TECHNOLOGIES Software Team.</p>";
			$fromAABSySiCompass .= '<p style="color:#FF6600;font-size:13px;font-weight:bold;">
							Best Regards,
							<br /><br />
							The POLOSOFT TECHNOLOGIES POLOHRM Team</p>'; 	
			if($empType == "F")
			{
				$probCont = "probation";
			}else{
				$probCont = "contract";
			}
			
			if($sex == "M")
			{
				$mrMs = "Mr";
				$hisHer = "his";
			}else{
				$mrMs = "Ms";
				$hisHer = "her";
			}

			$to = $reportingName.'<'.$reportingEmail.'>';
			// subject
			$subject = 'Reminder for '.$probCont.' end date of ' . $empName;

			$message = '
			<html>
			<head>
			  <title>POLOHRM '.$subject.'</title>
			</head>
			<body>
			<div id="icompass" style="width:615px;margin:0 auto;">';
			$message .=$logoText;
			$message .= '<div>
						<br />
						<span style="color:#FF6600;font-size:14px;font-weight:bold;line-height:20px">Dear '.$reportingName.',</span>
						<br />
						<p>
						This is to bring your notice that '. $mrMs .' '.$empName.', '.$empDesg.' 
						is completing '.$hisHer.' '.$probCont.' on '.$probContDate.'
						Kindly revert to HR Department with your recommendation on '.$hisHer.' confirmation by
						above mentioned date for further HR actions.

						<br /><br/></p>
					</div>';
					
			$message .=$fromAABSySiCompass;
			$message .=$footer;
			$message .= '
			</div>
			</body>
			</html>
			';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			$headers .= 'CC: HR Department <santibhusan@polosoftech.com>' . "\r\n";
			$headers .= 'From: Polohrm <hr@polosoftech.com>' . "\r\n";
			if(mail($to, $subject, $message, $headers)){
				//appendToSuccessMailLog($to,"Mail Sent by I-Compass for reminder confirmation to reporting manager");
			}
	}
	/************* END/  Reminder ***************/
	
	
	/************* END/  Reminder Missed Attendence ***************/
	public function reminder_missed_attendance(){
		$cur_date=date("Y-m-d"); 
		$month=date("m");
		$year=date("Y");

		$totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);  

		$End_Date = $year.'-'.$month.'-'.$totalDay;
		$EndDate2= strtotime('-2 DAY', strtotime($End_Date)); 
		$EndDate=date("Y-m-d",$EndDate2);     

		if($EndDate == $cur_date || $cur_date==$End_Date){
		$message = '';
	
$message=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body><div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:650px; float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                 <p>Dear All,</p>                                 
                                 <p> Kindly update your attendance to avoid Loss of Pay as the attendance data will be captured on the last day of this month.</p>
                                 <p> If already updated, Please ignore it.</p>
                                 <p> In case of any Query, Please contact to HR Department.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                 </div> 
                              </div> 
                              <div style="width:240px; float: left; background: cornflowerblue; min-height: 287px;">
                                  <div style="padding: 15px; color: #fff;">
                                  <div style="width:100%; font-weight: bold;">ICompass Profile Details</div> 
                                  <ul>
                                      <li>General Information</li>                                      
                                      <li>Experience Details</li>
                                      <li>Education Details</li>
                                      <li>Family Details</li>
                                      <li>Reference</li>
                                  </ul>
                                  </div>
                              </div> 

                        </div>  
                      </div></body>
                                 </html>
EOD;
                                 
        
        $to ='Polosoftech <hr@polosoftech.com>';
        $subject = 'Important Announcement';      
        //echo $message;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
        $headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

		$this_mail=mail($to, $subject, $message, $headers);  
			if($this_mail) {
				echo 'sent!';
			} else { 
				echo $to.'<br/>';
				print_r(error_get_last());
			} 
		}
	}
	/************* END/  Reminder Missed Attendence ***************/
	
	
	/*************  Reminder Missed Info ***************/
	public function reminder_missed_info(){
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
            <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
        </div>';
		$footer = '<a href="https://www.facebook.com/polosoftechnologies/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
		$site_base_url=base_url();
			
		$resAllEmp = $this->db->query("SELECT email,full_name FROM `internal_user` WHERE login_id != '10010' AND user_status ='1' AND email !='' ");
		$resAllEmpRes = $resAllEmp->result_array();
		for($i=1;$i<COUNT($resAllEmpRes);$i++){
		$message = '';
			  
$message=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:650px; float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                 <p>Dear All,</p>      
                                 <p> Please update your Aadhar Card No. mandatorily. </p>
                                 <p> This is essential for all kind of record purpose including PF & ESI.</p>                              
                                 <p> If already updated, Please ignore it.</p>
                                 <p><a href="{$site_base_url}my_account/profile_update_emp" style="text-decoration:none">Click here to Update</a><br /><br /></p> 
                                 <p> In case of any Query, Please contact to HR Department.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                 <p>{$footer}</p>
                                 </div> 
                              </div> 
                              <div style="width:240px; float: left; background: cornflowerblue; min-height: 287px;">
                                  <div style="padding: 15px; color: #fff;">
                                  <div style="width:100%; font-weight: bold;">POLOHRM Profile Details</div> 
                                  <ul>
                                      <li><a style="color: #fff !important;text-decoration: none;" href="{$site_base_url}my_account">General Information</a></li>                                      
                                      <li><a style="color: #fff !important;text-decoration: none;" href="{$site_base_url}my_account/exp_profile_readonly_emp">Experience Details</li>
                                      <li><a style="color: #fff !important;text-decoration: none;" href="{$site_base_url}my_account/education_profile_readonly_emp">Education Details</li>
                                      <li><a style="color: #fff !important;text-decoration: none;" href="{$site_base_url}my_account/family_profile_readonly_emp">Family Details</li>
                                      <li><a style="color: #fff !important;text-decoration: none;" href="{$site_base_url}my_account/reference_readonly_emp">Reference</li>
                                  </ul>
                                  </div>
                              </div> 

                        </div>  
                      </div></body>
                                 </html>
EOD;
                                 
			$to ="POLOSOFT TECHNOLOGIES <hr@polosoftech.com>";
			$subject = 'Important Announcement';      
			echo $message;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
			$headers .= "Importance: High\n";
			$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			$mail = mail($to, $subject, $message, $headers);    
			if($mail)
				echo $resAllEmpRes[$i]['email']."Sent<br/>";
			else
				echo print_r(error_get_last());
		}  
	}
	/************* END/  Reminder Missed Attendence ***************/
	
	
	/*************  Reminder Pradeep ***************/
	public function reminder_pradeep(){
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
            <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="http://icompass.polosoftech.com/images/logo.gif" />
        </div>';
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
		$site_base_url=base_url();
			
		$resAllEmp = $this->db->query("SELECT email,full_name FROM `internal_user` WHERE login_id != '10010' AND user_status ='1' AND email !='' ");
		$resAllEmpRes = $resAllEmp->result_array();
		for($i=1;$i<COUNT($resAllEmpRes);$i++){
		$message = '';

$message = <<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body><div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:650px; float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                 <p>Dear Polosoftechsian,</p>      
                                 <p> Time to say Goodbye & Ta Ta now... </p>                                                              
                                 <p>This is one of the toughest  letter to write. After 2 and half wonderful  years at POLOSOFT TECHNOLOGIES PVT. LTD., 
                                    its time to say goodbye. I must confess its not easy to leave a place that one has helped create/build/grow 
                                    and to leave such great colleagues. But I guess certain things in life are written in the stars. POLOSOFT TECHNOLOGIES PVT. LTD. 
                                    has not only been an integral part of my life for almost every waking minute these past years, but more important, 
                                    working with you has been a joy and an honour. During the last 2 and half years that I have worked here, I have 
                                    learned a lot from you all. It was because of your constant support and encouragement that I was able to 
                                    perform my duties so well. In my position as a Sr.Software Developer , I have gained considerable knowledge 
                                    and thus I shall always cherish this, as one of the most satisfying phases in my career. So Good bye 
                                    is an unhappy word. I prefer you to say see you all..until we meet again.</p>
                                 
                              <p>Gmail : hr@polosoftech.com</p>
                              <p>Facebook : www.facebook.com/polosoftech</p> 
                                <p>Linkedin : www.linkedin.com/in/polosoftech</p>
                                <p>Twitter : www.twitter.com/polosoftech</p>                                
                                <p>Cell : +91 9437334371</p>
                             </div> 
                              </div> 
                              <div style="width:240px; float: left; background: cornflowerblue; min-height: 287px;">
                                  <div style="padding: 15px; color: #fff;">
                                  <div style="width:100%; font-weight: bold;">Polosoft Administrator</div> 
                                  <p>Santibhusan Mishra</p>
                                  <p>Hr Director</p> 
                                  <p>POLOSOFT TECHNOLOGIES</p>
                                  </div>
                              </div> 

                        </div>  
                      </div></body>
                                 </html>
EOD;

			$to ="POLOSOFT TECHNOLOGIES <hr@polosoftech.com>";
			
			$subject = 'Resgination From POLOSOFT';
			echo $message;
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Importance: High\n";
			$headers .= 'From: SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";
			$headers .= 'Reply-To: SantiBhusan Mishra <hr@polosoftech.om>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			$mail = mail($to, $subject, $message, $headers);
			if ($mail)
				echo $resAllEmpRes[$i] . "Sent<br/>";
			else
				echo print_r(error_get_last());
		}
	}
	/************* END/  Reminder Pradeep ***************/
	
	
	/************* Reminder Probation ***************/
	public function reminder_probation(){
		$resAllEmp = $this->db->query("SELECT * FROM `internal_user` WHERE login_id != '10010' AND user_status ='1'");
		$rowAllEmp = $resAllEmp->result_array();
		for($i = 0;$i < COUNT($rowAllEmp); $i++){
			
		$reportRes = $this->db->query("SELECT email FROM `internal_user` WHERE login_id ='".$rowAllEmp[$i]['reporting_to']."'");
		$reportInfo =  $reportRes->result_array();

		$confirm3rd= date("Y-m-d",strtotime('+21 DAY', strtotime($rowAllEmp[$i]['join_date'])));
		$confirm7th= date("Y-m-d",strtotime('+49 DAY', strtotime($rowAllEmp[$i]['join_date'])));   
		$confirm11th= date("Y-m-d",strtotime('+77 DAY', strtotime($rowAllEmp[$i]['join_date'])));
		$cur_date=date("Y-m-d");
		$EndDate2= strtotime('-15 DAY', strtotime($rowAllEmp[$i]['employee_conform']));
		$EndDate=date("Y-m-d",$EndDate2);  

		$content = $week= '';  

		if($cur_date==$confirm3rd){
			$week="4th";
		}elseif($cur_date==$confirm7th){
			$week="8th";
		}elseif($cur_date==$confirm11th){
			$week="12th";
		}

		$confirm=date('d/m/Y',strtotime($rowAllEmp[$i]['employee_conform']));
			
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
			</div>';
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
				&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';
		$footer = '';
		if($rowAllEmp[$i]['emp_type']=='F' && ($cur_date==$confirm3rd || $cur_date==$confirm7th || $cur_date==$confirm11th)){                                 
$content=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="margin: 0 auto; background: #fff;">
                             <div style="float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 30px 10px;">
                                 <p>Dear Reporting Manager,</p>
                                 <p>{$rowAllEmp[$i]['full_name']} is going to complete {$week} weeks of probation period and confirm on {$confirm}. Kindly complete the probation assessment as per the policy.</p>
                                 <p> In case of any Query, Please contact to HR Department.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                  <p>{$footer}</p>
                                 </div> 
                              </div>                           
                        </div>  
                      </div></body>
                    </html>
EOD;
        $too =$reportInfo[0]['email'];
        $subject = 'Reminder for probation end date of '.$rowAllEmp[$i]['full_name'];  
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
        $headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
        $headers .= 'Reply-To: SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";
        $headers .= 'CC: hr@polosoftech.com <hr@polosoftech.com>' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $mail = mail($too, $subject, $content, $headers);
                                 
                                 
} elseif(($rowAllEmp[$i]['emp_type']=='C' || $rowAllEmp[$i]['emp_type']=='I') && $cur_date==$EndDate) {
    
$content=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="margin: 0 auto; background: #fff;">
                             <div style="float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 30px 10px;">
                                 <p>Dear Reporting Manager,</p>
                                 <p>{$rowAllEmp[$i]['full_name']} is going to complete the contract period on {$confirm}.Kindly recommend for Extension/Termination of contract.</p>
                                 <p> In case of any Query, Please contact to HR Department.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                 <p>{$footer}</p>
                                 </div> 
                              </div>                               

                        </div>  
                      </div></body>
                    </html>
EOD;
				$too =$reportInfo[0]['email'];
				$subject = 'Reminder for contract end date of '.$rowAllEmp[$i]['full_name'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";            
				$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES IT Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
				$headers .= 'Reply-To: SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";
				$headers .= 'CC: hr@polosoftech.com <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				$mail = mail($too, $subject, $content, $headers);
			}                              
   
		}
	}
	/************* END/  Reminder Probation ***************/
	
	
	/*************  Reminder Stop Task ***************/
	public function reminder_stop_task(){
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
			</div>';
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
				&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';
		$site_base_url=base_url();


		$resAllEmp = $this->db->query("SELECT login_id,email,full_name FROM `internal_user` WHERE user_status ='1'");
		$AllEmpRow = $resAllEmp->result_array();
		$AllEmpNum=  COUNT($AllEmpRow);
		for($i = 0; $i < $AllEmpNum; $i++){
		//while($AllEmpRow = mysql_fetch_array($resAllEmp)){
		//echo $AllEmpRow[$i]['login_id'];
        $empInquiryQry =  "SELECT i.full_name, i.loginhandle, i.login_id, pr.prd_name, o.order_title, l.start_date, l.end_date, l.id as logid, a.*, t.* 
                                                    FROM `task_subtask_time_log` l
                                                    LEFT JOIN `tast_subtask_assignment` a ON a.id=l.assignment_id                                                                               
                                                    LEFT JOIN `task_new` t ON t.task_id = a.ts_id
                                                    LEFT JOIN `product_order` o ON o.order_id = t.order_id
                                                    LEFT JOIN `product` pr ON pr.id = o.prd_id
                                                    LEFT JOIN `internal_user` i ON i.login_id = l.user_id 
                                                    WHERE user_id = '".$AllEmpRow[$i]['login_id']."' AND DATE_FORMAT(l.end_date, '%Y-%m-%d') = '0000-00-00'";

        $empInquiryRes = $this->db->query($empInquiryQry);
        $empInquiryInfo = $empInquiryRes->result_array();
        $empInquiryNum = COUNT($empInquiryInfo);
        if($empInquiryNum >0){
        $idate = date('Y-m-d',strtotime($empInquiryInfo[0]['start_date']));
        $message =<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body><div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
				<p>{$logoText}</p>
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$AllEmpRow[$i]['full_name']},</p>                                 
                 <p>You had not punched the outtime against your task yesterday. </p>                                 
                 <p><a href="{$site_base_url}en/timesheet" style="text-decoration:none">Click here to view details</a><br /><br /></p>
                 <p> In case of any Query, Please contact to your Repotee.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
			$subject = 'Regarding Time Discrepancy in Production Report'; 
			echo $message;
            $to=$AllEmpRow[$i]['full_name'].'<'.$AllEmpRow[$i]['email'].'>';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";   
            $headers .= 'X-Mailer: PHP/' . phpversion();


			
			  //mail($to, $subject, $message, $headers);  
			}
		}
	}
	/************* END/  Reminder Stop Task ***************/
	
	
	/*************  Reminder Working Hour ***************/
	public function reminder_working_hour(){
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
			</div>';
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
				&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';
			$site_base_url=base_url();

			$cur_date= date('Y-m-d', strtotime('-1 day'));
			$curDate=date('d/m/Y',strtotime($cur_date));
			$attSQL = "SELECT * FROM attendance_new WHERE att_status='W' AND DATE_FORMAT(date,'%Y-%m-%d')='".$cur_date."'";
			$attRES = $this->db->query($attSQL);
			$rowAllEmp = $attRES->result_array();
			$attNUM = COUNT($rowAllEmp);
			
			if($attNUM > 0){
				for($i = 0; $i< $attNUM; $i++){
				$message = '';
				$empSQL = "SELECT * FROM internal_user WHERE login_id='".$rowAllEmp[$i]['login_id']."'";
				$empRES = $this->db->query($empSQL);
				$empRow = $empRES->result_array();

$message=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:650px; float: left; min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                 <p>Dear {$empRow[0]['full_name']},</p>                                 
                                 <p>You have not put in your required working hour on dated $curDate.</p>                                 
                                  <p><a href="{$site_base_url}/script/attendance_reg.php" style="text-decoration:none">Click here to Regularise</a><br /><br /></p> 
                                  <p> In case of any Query, Please contact to HR Department.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                 <p>{$footer}</p>
                                 </div> 
                              </div> 
                              <div style="width:240px; float: left; background: cornflowerblue; min-height: 287px;">
                                  <div style="padding: 15px; color: #fff;">
                                  <div style="width:100%; font-weight: bold;">ICompass Profile Details</div> 
                                  <ul>
                                      <li>General Information</li>                                      
                                      <li>Experience Details</li>
                                      <li>Education Details</li>
                                      <li>Family Details</li>
                                      <li>Reference</li>
                                  </ul>
                                  </div>
                              </div> 

                        </div>  
                      </div></body>
                                 </html>
EOD;
                                 
				$to = $empRow[0]['email'];
				$subject = 'Important Announcement';      
				echo $message;
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();

				//mail($to, $subject, $message, $headers);  
			}
		}
	}
	/************* END/  Reminder Working Hour ***************/
	
	
	/*************  samplewishes Mail ***************/
	public function samplewishes(){
		
$sitepath = base_url();
//$birthDayP1  = date("m-d", strtotime('+1 days'));
//$bDateText = date("F j, Y", strtotime('+1 days'));
$birthDayP  = date("d-m");
$bDateText = date("F j, Y");
$subject = "Wish you many many happy returns of the day!";

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To: Anshuman <anshuman.nayak@polosoftech.com>' . "\r\n";
$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt Ltd <lalit.tyagi@polosoftech.com>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
$headers .= 'Bcc: hr@polosoftech.com' . "\r\n";
$headers .= 'Reply-To: lalit.tyagi@polosoftech.com' . "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();



//Get Email Ids for Birthday Wishes
$birthdaySQL = "SELECT login_id, email, user_photo_name,full_name FROM internal_user WHERE DATE_FORMAT(dob , '%d-%m') = '".$birthDayP."' AND user_status = '1'";
$birthdayRES = $this->db->query($birthdaySQL);
$birthdayINFO = $birthdayRES->result_array();
if(count($birthdayINFO) > 0){
    for($i=0; $i<count($birthdayINFO); $i++){
        //$subject .= $birthdayINFO[$i]["email"];
        //Get Image
        if($birthdayINFO[$i]["email"] == "saurav.mohapatra@polosoftech.com"){
            $profileImage = $sitepath . "assets/images/wishes/pic.jpg";
        }else if($birthdayINFO[$i]["user_photo_name"] != ""){
            $profileImage = $sitepath . "assets/upload/profile/" . $birthdayINFO[$i]["user_photo_name"];
        }else{
            $profileImage = $sitepath . "assets/images/no-image.jpg";
        }
        
        $encodedUserId = base64_encode($birthdayINFO[$i]["login_id"]);
        
        $message = <<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Define Charset -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Responsive Meta Tag -->
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

<!-- Page Title -->
<title> Birthday Mailer </title>

<!-- Responsive Styles and Valid Styles -->
<style type="text/css">

body
{width: 100%; background-color: #282828; margin:0; padding:0; }
html
{width: 100%; }
@media only screen and (max-width: 640px) {

body{ width:auto!important; }

/* Box Wrap */
.BoxWrap { width:440px !important;  }

/* Head  */
.HeaderTable { width:440px !important; margin:0 auto !important; }

.SizeHeadTable  {  width:440px !important; }
.HeadWrap  {  width:400px !important; }
.HeadTitle { font-size:22px !important;  }
.HeadTxt { font-size:15px !important; }
/* Section 1  */
.SecTable1 { width:440px !important; }
.SecTxt1 { width:400px !important;   padding:0 0 10px !important;  margin:0 auto !important; } 
.SecTitle1 { font-size:17px !important; }
.SecPrg1 { font-size:15px !important;  line-height:1.9 !important; } 


/* Footer  */
.Footer1 { height:100px !important; }
.Footer1Wrap {width:440px !important;}
.Footer1Title {width:440px !important; text-align:center !important;}
.Footer1Social { width:280px !important; margin:0 auto !important; }
.Footer1SocialW { width:280px !important; margin:0 auto !important; }
.Footer2W {width:440px !important;}

/* Hide  */
.RespoHide { display:none !important; }


}
		


@media only screen and (max-width: 479px)  {

body{width:auto!important;}

/* Box Wrap */
.BoxWrap { width:280px !important;  }

/* Head  */
.HeaderTable { width:280px !important; margin:0 auto !important; }
.SizeHeadTable img {  width:280px !important; height:140px !important; }
.SizeHeadTable  {  width:280px !important; }
.HeadWrap  {  width:260px !important; }
.HeadTitle { font-size:18px !important;  }
.HeadTxt { font-size:13px !important; }
.date p img { width:15px; }
.date p { font-size:11px!important; line-height:14px !important; }
.Head2Txt { width:260px !important; }
/* Section 1  */
.SecTable1 { width:280px !important; }
.SecTxt1 { width:260px !important;   padding:0 0 10px !important;  margin:0 auto !important; } 
.SecTitle1 { font-size:15px !important; }
.SecPrg1 { font-size:20px !important; line-height:1.9 !important; } 

/* Footer  */
.Footer1 { height:160px !important; }
.Footer1Td { text-align:center !important; line-height: 3.2 !important;  }
.Footer1Wrap {width:280px !important;}
.Footer1Title {width:280px !important; text-align:center !important;}
.Footer1Social { width:200px !important; margin:0 auto !important; }
.Footer1SocialW { width:200px !important; margin:0 auto !important; }
.Footer2W {width:280px !important;}
.Footer2T1 { font-size:9px !important; }
.Footer2T2 { }

.RespoShow { width:100% !important; height:20px !important; }
.BoxWrap2 { padding:0 0 30px !important; }
/* Hide  */
.RespoHide { display:none !important; }	

}
</style>

</head>

<body topmargin="0" marginheight="0" marginwidth="0" leftmargin="0">


<!-- Main Container -->
<table bgcolor="#343434" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
		<td width="100%" valign="top">
		   <!-- Header Start -->
	 	   <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td width="100%" bgcolor="#282828">
					
						<!-- Start Header Content -->
						<table width="580" class="HeaderTable" border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
								<td width="580">
									
									<!-- Logo -->
									<table border="0" cellpadding="0" cellspacing="0" align="left" >
										<tr>
											<td height="14"></td>
										</tr>
										<tr>
											<td height="50">
												<a href="#"><img mc:edit="logo" src="{$sitepath}/images/wishes/logo.gif" alt="" border="0"></a>
											</td>
										</tr>
										<tr>
											<td height="12" class="RespoHide"></td>
										</tr>
									</table>
									
									<!-- Date -->
									<table border="0" cellpadding="0" cellspacing="0" align="right">
										<tr>
											<td height="13" ></td>
										</tr>
										<tr>
											<td mc:edit="date" height="50" class="date" style="font-size: 13px; color: #ffffff; font-weight: light; text-align: right; font-family: Helvetica, Arial, sans-serif; line-height: 20px; vertical-align: middle;">
									        <p> <img src="{$sitepath}/images/wishes/logo.gif" style="float:left;" alt="" border="0">{$bDateText}</p>	 
											</td>
										</tr>
										<tr>
											<td height="12"></td>
										</tr>
									</table>
																	
								</td>
							</tr>
						</table><!-- End Header Wrapper -->
						
					</td>
				</tr>
			</table><!-- End Header -->
		 	
		    <!-- Head2 Section -->
			<table class="WideWrap" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style=" border-top:1px solid #383838; ">
				<tr>
					<td width="100%" valign="top" >
					
						<!-- Box Wrapper -->
						<table class="BoxWrap" width="580" border="0" cellpadding="0" cellspacing="0" align="center" style="margin:24px auto 0; background-color:#282828; border:1px solid #383838;">
							
							 	<tr>
                                                                <td class="SizeHeadTable" style="background-image:url({$profileImage}); background-repeat: no-repeat; background-position: left top; background-size:26%;">
                                  <img src="{$sitepath}assets/images/wishes/happybirthday_frame_2.png"></td>
							</tr>
							
						</table><!-- End Box Wrapper -->
        
					</td>
				</tr>
					 <tr><td height="20"></td></tr>
			</table><!-- End Head Section -->

			
			 <!-- Footer Wide Wrap -->
			<table class="WideWrap" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td width="100%" valign="top" >
					
						<!-- Footer1 Background -->						<!-- Footer1 Background -->
        
		                	<!-- Footer2 Background -->
						<table height="96" class="Footer2" width="100%" border="0" style="background-color:#222222;" cellpadding="0" cellspacing="0" align="center" >
						     <tr>
							 <td>
							 <table width="580" border="0"  cellpadding="0" cellspacing="0" align="center" class="Footer2W">
							    <tr>
								     <td>
									  <table  border="0" cellpadding="0" cellspacing="0" align="center" >
									  	<tr>
								<td valign="top" height="20" align="center"  ></td>
							</tr>
								    <tr>
									    <td mc:edit="Footer2T" align="center" class="Footer2T1" style=" font-weight:400; font-size: 12px; color: #b2b3b6; font-weight: bold; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 1; vertical-align: top;">
															 
										<p  style=" font-weight:400; text-decoration: none; color: #b2b3b6">  <img alt="" src="{$sitepath}assets/images/wishes/icon4.jpg" /> <b style="color:#b2b3b6;  font-weight:600;"> Having problems? </b>  </p>
										</td>
							
									</tr>
								</table>
								  <table  border="0" cellpadding="0" cellspacing="0" align="center" >
								    <tr>
									    <td class="Footer2T2" mc:edit="copyright" style="font-weight:400; font-size: 12px; color: #b2b3b6; font-weight: bold; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 1; vertical-align: top;">
										 <p>&nbsp;</p>
										</td>
							
									</tr>
								</table>
									 </td>
								</tr>
							 </table>
							 </td>
                             </tr>							 
                        </table><!-- Footer1 Background -->
						
					</td>
				</tr>
					 
			</table><!-- End Footer Basic Wrapper -->
			
		</td>
	</tr>	
</table>

</body>
</html>
EOD;
		$to = $birthdayINFO[$i]["email"];																			
		//$to = "hr@polosoftech.com";																			
		// Mail it
		mail($to, $subject, $message, $headers);
				
			}
		}
		echo $message;
	}
	/************* END/  samplewishes Mail ***************/
	
	
	/*************  Reminder Mail ***************/
	public function reminder_mail(){
		$todayP7  = date("Y-m-d",strtotime('+15 days'));
		$empSQL = 	"SELECT i.full_name, i.gender, i.designation, i.employee_conform, i.emp_type, d.desg_name, r.email, r.full_name AS repName FROM internal_user AS i 
					INNER JOIN user_desg AS d ON d.desg_id = i.designation
					INNER JOIN internal_user AS r ON i.reporting_to = r.login_id
					WHERE i.user_status = '1' AND i.confirm_status != 'Confirmed' AND i.employee_conform = '".$todayP7."'";
		$empRES = $this->db->query($empSQL);
		$empINFO = $empRES->result_array();
		$empNUM = COUNT($empINFO);
		if($empNUM > 0)
		{
			for($i = 0; $i< $empNUM; $i ++){
				$endDate = date("jS F, Y", strtotime($empINFO[$i]['employee_conform']));		
				$this->reminderForProbationOrContract($empINFO[$i]['repName'], $empINFO[$i]['email'], $empINFO[$i]['full_name'], $empINFO[$i]['desg_name'], $endDate, $empINFO[$i]['emp_type'], $empINFO[$i]['gender']);
			}
		}
		echo "Mail Sent Succesfully : ".$todayP7;
	}
	/************* END/  Reminder Mail ***************/
	
	
	/*************  Test Mail ***************/
	public function test_mail(){
		$message = "This is Test Email Local";
		$to = "saurav.mohapatra@polosoftech.com";
		$subject = 'Test Email Local';      
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Support <saurav.mohapatra@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: HR Department <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
	}
	/************* END/  Test Mail ***************/
	
	
	/*************  Update AMS employee details ***************/
	public function update_employee_into_ams(){
		$today = date('Y-m-d'); 
		$query = $this->db->query("SELECT * FROM `internal_user`");
		$row = $query->result_array(); 
		
		//print_r($row);
		echo count($row);  
		 if(count($row) > 0)
		{ 
			for ($i=0; $i<count($row); $i++) 
			{
				$this->db1 = $this->load->database('amsdb', TRUE); 
				$query = $this->db1->query("SELECT ix_corp_id FROM `employee_info` WHERE ix_corp_id = '".$row[$i]['loginhandle']."'");
				$rows = $query->result_array();
				if(count($rows)>0){
					$updateSql ="UPDATE `employee_info` 
						SET s_emp_name = '".$row[$i]['full_name']."',
						ix_department = '".$this->input->post('txtmobile_landline')."',
						ix_designation = '".$this->input->post('txtfuel')."', 
						d_emp_dob = '".$this->input->post('txtvehicle_maintenance')."',
						s_emp_type = '".$this->input->post('txtentertainment')."'
						WHERE ix_corp_id='".$row[$i]['loginhandle']."'";
					$this->db1->query($updateSql);
				}
				else{
					$sql = "INSERT INTO employee_info
						(`ix_corp_id`, `s_emp_name`, `ix_department`, `ix_designation`, `s_emp_email`, `d_date_of_joining`, `d_emp_dob`, `s_emp_addr`, `s_emp_per_addr`, `s_emp_type`, `n_prob_period`, `d_notice_date`, `n_notice_period`, `s_contact_no`, `e_user_status`, `n_disp_flag`)
						VALUES ('$row[$i]['loginhandle']', 
						'$row[$i]['full_name']', 
						'$row[$i]['department']', 
						'$row[$i]['designation']', 
						'$row[$i]['email']', 
						'$row[$i]['join_date']', 
						'$row[$i]['dob']', 
						'', 
						'', 
						'$row[$i]['emp_type']', 
						'3', 
						'', 
						'', 
						'$row[$i]['phone1']', 
						'$row[$i]['user_status']', 
						'1' )";
					$this->db1->query($sql);
				}
				
			}
		}  
	}
	/************* END/  Update AMS employee details ***************/
	
	
	
	/************* Update Biometric attendance details ***************/
	public function cron_biometric_attendance_auto_update()
	{
		$this->mViewData['pageTitle'] = 'Biometric Data Upload';
		$successMsg = FALSE;
		$errMsg = "";
		
		//$current_date = date('Y-m-d', strtotime('2019-06-12'));
		$current_date = date('Y-m-d');
		
		$DB2 = $this->load->database('biomericdb', true);
		$connected = $DB2->initialize();
		
		$query = $DB2->query("select * from parallel where LogDate = '".$current_date."' group by EmployeeID order by LogTime ASC");
		$result = $query->result_array();		
		
		if(count($result)>0)
		{
			for($i=0; $i<count($result); $i++)
			{
				if($current_date != "")
				{
					$attendanceDate = $current_date;
					$login_id = $result[$i]['EmployeeID'];
					$chkDeclLeaveSQL = "SELECT `ix_declared_leave` FROM `declared_leave` WHERE `dt_event_date` = '".$current_date."' AND (branch='0' OR branch='".$this->session->userdata('branch')."') LIMIT 1";
					$chkDeclLeaveRES = $this->db->query($chkDeclLeaveSQL);
					$chkDeclLeaveINFO = $chkDeclLeaveRES->result_array();
					$chkDeclLeaveNUM = COUNT($chkDeclLeaveINFO);
					
					if($attendanceDate != "" && $chkDeclLeaveNUM == 0)
					{
						$empIDSQL = "SELECT login_id,shift,department,user_role FROM internal_user WHERE login_id = '".$login_id."' AND user_status = '1' LIMIT 1";
						$empIDRES = $this->db->query($empIDSQL);
						$empIDInfo = $empIDRES->result_array(); 
						if(count($empIDInfo) > 0)
						{
							$in_time = "";
							$out_time = "";
							if($login_id > 0)
							{
								$in_time = date('H:i:s', strtotime($result[$i]['LogTime']));	
								
								$checkEntrySQL = "SELECT attendance_id, att_status FROM attendance_new WHERE login_id = ".$login_id." AND date = '".$attendanceDate."' LIMIT 1";
								$checkEntryRES = $this->db->query($checkEntrySQL);
								$checkEntryINFO = $checkEntryRES->result_array();
								$checkEntryNUM = COUNT($checkEntryINFO);
								
								if($checkEntryNUM > 0){
									
									$query_out = $DB2->query("select * from parallel where LogDate= '".$current_date."' and EmployeeID='".$login_id."' order by LogTime desc");
									$result_out = $query_out->result_array();
									if(count($result_out) > 1)
									{
										$out_time = date('H:i:s', strtotime($result_out[0]['LogTime']));
									}
									
									
									$totalTime = $this->gtd($out_time , $in_time);
									$cond =""; $OutTime = $totTime = $lwh=0;
									if(($empIDInfo[0]['department']==6 || $empIDInfo[0]['department']==7))
									{
																		   
										if($empIDInfo[0]['shift']=='MS' || $empIDInfo[0]['shift']=='ES' || $empIDInfo[0]['shift']=='NS')
										{
											$totTime='28800';
										} 	
										else
										{
											$totTime='34200';
										}
										
										//$totalTime = $this->gtd($out_time , $in_time);
										if(($totalTime['0'] < $totTime) || $out_time == '')
										{
											if($empIDInfo[0]['user_role']==5 || $empIDInfo[0]['user_role']==4 || $empIDInfo[0]['user_role']==3) {
												$cond=", att_status='W', shift='".$empIDInfo[0]['shift']."'";
											   $lwh=1;
											}
										}
										else{
											$cond=", att_status='P', shift='".$empIDInfo[0]['shift']."'";
										}
									}
									else{
										$cond=", att_status='P', shift='".$empIDInfo[0]['shift']."'";
									}
									
									if($out_time != '')
									{
										$chkProductionSQL = "SELECT * FROM `task_subtask_time_log` WHERE DATE_FORMAT(start_date,'%Y-%m-%d') = '".$attendanceDate."' AND user_id=".$empIDInfo[0]['login_id']." AND end_date='0000-00-00 00:00:00' LIMIT 1";
										$chkProductionRES = $this->db->query($chkProductionSQL);
										$chkProductionROW = $chkProductionRES->result_array();
										$chkProductionNUM = count($chkProductionROW);
										if($chkProductionNUM == 1)
										{
											$end_date=$attendanceDate.' '.$out_time;
											$updateQryRTimeLogres = $this->db->query("UPDATE `task_subtask_time_log` SET end_date='".$end_date."', spent_time='".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' AND user_id=".$empIDInfo[0]['login_id']."");
											$updateQryAssignRes = $this->db->query("UPDATE `tast_subtask_assignment` SET `actual_time` = `actual_time` + '".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' LIMIT 1");

										}
									}
								
									if($checkEntryINFO[0]['att_status']=='L' || $checkEntryINFO[0]['att_status']=='R' || $checkEntryINFO[0]['att_status']=='H')
									{
										$attndSQL = "UPDATE attendance_new SET out_time = '".$out_time."' WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
									}
									else
									{
										$attndSQL = "UPDATE attendance_new SET out_time = '".$out_time."'".$cond." WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
									}
								}else{
									$attndSQL = "INSERT INTO attendance_new SET login_id='".$empIDInfo[0]['login_id']."', date='".$attendanceDate."', att_status='W', in_time='".$in_time."', out_time='".$out_time."'";
								}
								$attndRes = $this->db->query($attndSQL);
								$successMsg = "Cron Process Successful";
							}
						}
					}
				}
			}
		}
		else{
			$errMsg = "Sorry!!! Some Error occurred while process the cron.";
		}
		$this->mViewData['success_msg'] = $successMsg;
		$this->mViewData['error_msg'] = $errMsg;
		//$this->render('hr/attendance_entry/biometric_data_upload_view', 'full_width',$this->mViewData);
		
		
		$message = "Attendance Update successfully.";
		$to = "saurav.mohapatra@polosoftech.com";
		$subject = 'Attendance Update';      
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Support <saurav.mohapatra@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: HR Department <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
		
		
	}
	
	/************* Update Biometric attendance details ***************/
	public function cron_biometric_attendance_update()
	{
		$this->mViewData['pageTitle'] = 'Biometric Data Upload';
		$successMsg = FALSE;
		$errMsg = "";
		
		//$current_date = date('Y-m-d', strtotime('2019-05-24'));
		$current_date = date('Y-m-d');
		
		$DB2 = $this->load->database('biomericdb', true);
		$connected = $DB2->initialize();
		if (!$connected) {
		  $error = $DB2->error();
		} 
		$query = $DB2->query("select * from parallel where LogDate = '".$current_date."' group by EmployeeID order by LogTime ASC");
		$result = $query->result_array();		
		print_r($result);exit;
		if(count($result)>0)
		{
			for($i=0; $i<count($result); $i++)
			{
				if($current_date != "")
				{
					$attendanceDate = $current_date;
					$login_id = $result[$i]['EmployeeID'];
					$chkDeclLeaveSQL = "SELECT `ix_declared_leave` FROM `declared_leave` WHERE `dt_event_date` = '".$current_date."' AND (branch='0' OR branch='".$this->session->userdata('branch')."') LIMIT 1";
					$chkDeclLeaveRES = $this->db->query($chkDeclLeaveSQL);
					$chkDeclLeaveINFO = $chkDeclLeaveRES->result_array();
					$chkDeclLeaveNUM = COUNT($chkDeclLeaveINFO);
					
					if($attendanceDate != "" && $chkDeclLeaveNUM == 0)
					{
						$empIDSQL = "SELECT login_id,shift,department,user_role FROM internal_user WHERE login_id = '".$login_id."' AND user_status = '1' LIMIT 1";
						$empIDRES = $this->db->query($empIDSQL);
						$empIDInfo = $empIDRES->result_array(); 
						if(count($empIDInfo) > 0)
						{
							$in_time = "";
							$out_time = "";
							if($login_id > 0)
							{
								$in_time = date('H:i:s', strtotime($result[$i]['LogTime']));	
								
								$checkEntrySQL = "SELECT attendance_id, att_status FROM attendance_new WHERE login_id = ".$login_id." AND date = '".$attendanceDate."' LIMIT 1";
								$checkEntryRES = $this->db->query($checkEntrySQL);
								$checkEntryINFO = $checkEntryRES->result_array();
								$checkEntryNUM = COUNT($checkEntryINFO);
								
								if($checkEntryNUM > 0){
									
									$query_out = $DB2->query("select * from parallel where LogDate= '".$current_date."' and EmployeeID='".$login_id."' order by LogTime desc");
									$result_out = $query_out->result_array();
									if(count($result_out) > 1)
									{
										$out_time = date('H:i:s', strtotime($result_out[0]['LogTime']));
									}
									
									
									$totalTime = $this->gtd($out_time , $in_time);
									$cond =""; $OutTime = $totTime = $lwh=0;
									if(($empIDInfo[0]['department']==6 || $empIDInfo[0]['department']==7))
									{
																		   
										if($empIDInfo[0]['shift']=='MS' || $empIDInfo[0]['shift']=='ES' || $empIDInfo[0]['shift']=='NS')
										{
											$totTime='28800';
										} 	
										else
										{
											$totTime='34200';
										}
										
										//$totalTime = $this->gtd($out_time , $in_time);
										if(($totalTime['0'] < $totTime) || $out_time == '')
										{
											if($empIDInfo[0]['user_role']==5 || $empIDInfo[0]['user_role']==4 || $empIDInfo[0]['user_role']==3) {
												$cond=", att_status='W', shift='".$empIDInfo[0]['shift']."'";
											   $lwh=1;
											}
										}
										else{
											$cond=", att_status='P', shift='".$empIDInfo[0]['shift']."'";
										}
									}
									else{
										$cond=", att_status='P', shift='".$empIDInfo[0]['shift']."'";
									}
									
									if($out_time != '')
									{
										$chkProductionSQL = "SELECT * FROM `task_subtask_time_log` WHERE DATE_FORMAT(start_date,'%Y-%m-%d') = '".$attendanceDate."' AND user_id=".$empIDInfo[0]['login_id']." AND end_date='0000-00-00 00:00:00' LIMIT 1";
										$chkProductionRES = $this->db->query($chkProductionSQL);
										$chkProductionROW = $chkProductionRES->result_array();
										$chkProductionNUM = count($chkProductionROW);
										if($chkProductionNUM == 1)
										{
											$end_date=$attendanceDate.' '.$out_time;
											$updateQryRTimeLogres = $this->db->query("UPDATE `task_subtask_time_log` SET end_date='".$end_date."', spent_time='".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' AND user_id=".$empIDInfo[0]['login_id']."");
											$updateQryAssignRes = $this->db->query("UPDATE `tast_subtask_assignment` SET `actual_time` = `actual_time` + '".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' LIMIT 1");

										}
									}
								
									if($checkEntryINFO[0]['att_status']=='L' || $checkEntryINFO[0]['att_status']=='R' || $checkEntryINFO[0]['att_status']=='H')
									{
										$attndSQL = "UPDATE attendance_new SET out_time = '".$out_time."' WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
									}
									else
									{
										$attndSQL = "UPDATE attendance_new SET out_time = '".$out_time."'".$cond." WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
									}
								}else{
									$attndSQL = "INSERT INTO attendance_new SET login_id='".$empIDInfo[0]['login_id']."', date='".$attendanceDate."', att_status='W', in_time='".$in_time."', out_time='".$out_time."'";
								}
								$attndRes = $this->db->query($attndSQL);
								$successMsg = "Cron Process Successful";
							}
						}
					}
				}
			}
		}
		else{
			$errMsg = "Sorry!!! Some Error occurred while process the cron.";
		}
		$this->mViewData['success_msg'] = $successMsg;
		$this->mViewData['error_msg'] = $errMsg;
		//$this->render('hr/attendance_entry/biometric_data_upload_view', 'full_width',$this->mViewData);
		
		
		/* $message = "Attendance Update successfully.";
		$to = "hr@polosoftech.com";
		$subject = 'Attendance Update';      
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Support <support@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: HR Department <no-reply@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers); */
		
		
		
	}
	
	
	
	public function gtd($t1,$t2) {
        //if (($t1 = strtotime($t1)) === false) die ("Input string 1 unrecognized");
        //if (($t2 = strtotime($t2)) === false) die ("Input string 2 unrecognized");

        $t1 = strtotime($t1);
        $t2 = strtotime($t2);
        
        if($t1 < $t2) 
                {
                $d1 = getdate($t2);
                $d2 = getdate($t1); 
                }
        else
                {
                $d1 = getdate($t1);
                $d2 = getdate($t2);
                }

        foreach ($d1 as $k => $v) 
                {
					$d1[$k] =  (int)$d1[$k] - (int)$d2[$k] ;  
                }


        if ($d1['seconds'] < 0 ) 
                {
                $d1['seconds'] +=60 ;
                $d1['minutes'] -=1;
                }

        if ($d1['minutes'] < 0 ) 
                {
                $d1['minutes'] +=60 ;
                $d1['hours'] -=1;
                }

        if ($d1['hours'] < 0 ) 
                {
                $d1['hours'] +=24 ;
                $d1['yday'] -=1;
                }

        if ($d1['yday'] < 0 ) 
                {
                $d1['yday'] +=365 ;
                $d1['year'] -=1;
                }
        return ($d1);
	}
	/************* END/  Update Biometric attendance details  ***************/
	
	
	/********************************User data upload by cronjob start*******************************************/
	
		/*public function usrdata_upload()
		{
			$this->load->library('zklibrary');
			$zk = new ZKLibrary('172.61.25.78', 4370);
			$zk->connect();
			$zk->disableDevice();

			$users = $zk->getUser();
			
			foreach($users as $key=>$user)
			{
				$user_role='';
				if($user[2]==0)
				{$user_role='NORMAL USER';}
				elseif($user[2]==2)
				{$user_role='ENROLLER';}
				elseif($user[2]==12)
				{$user_role='MANAGER';}
				elseif($user[2]==14)
				{$user_role='SUPERMANAGER';}
				
				$data=array(							
							'user_UID'=>$key,
							'biomtrc_id'=>$user[0],
							'user_name'=>$user[1],
							'user_role'=>$user_role,
							);
							
				$this->db->insert('biomatrics_user_data',$data);
				
							
			}
				
		} */
		
		/********************************User data upload by cronjob end*********************************************/
		
		public function Upload_InOut_Attendance()
		{ 
			$this->load->library('zklibrary');
			$zk = new ZKLibrary('172.61.25.78', 4370);
			$zk->connect();
			$zk->disableDevice();
			$user_attend = $zk->getAttendance();
			
			
			foreach($user_attend as $key=>$userattnd)
			{
				$user_id = $userattnd[1];
				$qr_sql =  "SELECT user_name FROM biomatrics_user_data WHERE biomtrc_id = ?";
				
				$qr = $this->db->query($qr_sql, array($user_id));
				
				$uname= $qr->row()->user_name;
				$attenlog_arr=explode(' ',$userattnd[3]);
				
				$qr_inoutchecksql="SELECT attend_sqlid FROM biomatrics_in_out_attendance WHERE  biomtrc_id = ? AND attend_date = ? AND attend_time = ? ";
				$qr_inoutcheck = $this->db->query($qr_inoutchecksql, array($userattnd[1],$attenlog_arr[0],$attenlog_arr[1]))->result_array();	
				
					if(count($qr_inoutcheck)==0)
					{		
						$data=array(
									'biomatric_slno'=>$userattnd[0],
									'biomtrc_id'=>$userattnd[1],
									'user_name'=>$uname,
									'sensing_type'=>$userattnd[2],
									'attend_date' => $attenlog_arr[0],
									'attend_time' => $attenlog_arr[1],							
									'attendlog_dtm'=>$userattnd[3],
									);
									
						$this->db->insert('biomatrics_in_out_attendance',$data);
					}
							
			}
			
				$zk->enableDevice();
				$zk->disconnect();			
				
				//$curdate=date('Y-m-d');	
				$curdate='2019-12-10';		
				
				$qr_attend="SELECT * FROM biomatrics_in_out_attendance WHERE attend_date = ? GROUP BY biomtrc_id";				
				$qr_attendresult = $this->db->query($qr_attend,array($curdate))->result_array();
				
				if(count($qr_attendresult)>0)
				{
						foreach($qr_attendresult as $res_attnd)
						{
							$qr_attendchk="SELECT * FROM biomatrics_in_out_attendance WHERE attend_date = ? AND biomtrc_id = ? ";				
							$qr_attendresult = $this->db->query($qr_attendchk, array($curdate,$res_attnd['biomtrc_id']))->result_array();
							
							$attr=1;
							$first_punch_in = '';
							$last_punch_out='';
							$tot_office_outside_duration=0;
							
							$tot_usrattnd=count($qr_attendresult);
							
							if($tot_usrattnd>0)
							{		
									$out_time_arr=array();
									$in_time_arr=array();
										
									foreach($qr_attendresult as $res_attend)
									{												
												if($attr==1)
												{$first_punch_in=$res_attend['attend_time'];}
												if($attr==$tot_usrattnd)
												{$last_punch_out=$res_attend['attend_time'];}
												
													$attend_sqlid = $res_attend['attend_sqlid'];
												
													if(($attr % 2)==0)
													{
														$attend_sts="Out";														
														$out_time_arr[] = $res_attend['attend_time'];
													
													}
													else
													{
														$attend_sts="In";
														$in_time_arr[] = $res_attend['attend_time'];
													}
													
														/*if($attr>1)
														{
															$office_absent_duration=(strtotime($out_time) - strtotime($in_time) ) / 3600 ;
														
															$tot_office_outside_duration =  $tot_office_outside_duration + $office_present_duration;
														}
														*/
														
													if($res_attend['attend_status']=='')
													{
														$data=array('attend_status'=>$attend_sts);
														
														$this->db->where('attend_sqlid',$attend_sqlid);
														$this->db->update('biomatrics_in_out_attendance',$data);
													}
													
												$attr++;
										
										
											
									}
							} // if condition end	
							
							
							// --------- Attendance summary data entry start ------------//
							$qr_usersql =  "SELECT * FROM biomatrics_user_data WHERE biomtrc_id = ?";
				
							$qr = $this->db->query($qr_usersql, array($res_attnd['biomtrc_id']));
							
							$uname= $qr->row()->user_name;
							
							$login_id = $qr->row()->login_id;
							$biomtrc_id = $res_attnd['biomtrc_id'];
							$attend_date = $curdate;
							
							$tot_in_ctr=count($in_time_arr);
							$tot_out_ctr=count($out_time_arr);
							
							$start = strtotime($first_punch_in);
							
							
							if($tot_in_ctr==$tot_out_ctr)
							{
								$end = strtotime($last_punch_out);
							}
							else
							{
								$end = strtotime(date('H:i:s'));
								$last_punch_out = date("H:i:s");	
							}
							
							$hrs = ($end - $start) / 3600;
							$tot_office_duration =  number_format((float)$hrs, 2, '.', '');
							
							$tot_present_in_officedesk_arr=array();
							
							
								if($tot_in_ctr==$tot_out_ctr)
								{
									foreach($in_time_arr as $ky=>$val)
									{
										$prsentIn_office =  (strtotime($out_time_arr[$ky]) - strtotime($in_time_arr[$ky]))/3600;
										$tot_present_in_officedesk_arr[] = $prsentIn_office;
									}
									
								}
								else
								{
									array_push($out_time_arr,date('H:i:s'));
									
									foreach($out_time_arr as $ky=>$val)
									{
										$prsentIn_office =  (strtotime($out_time_arr[$ky]) - strtotime($in_time_arr[$ky]))/3600;
										$tot_present_in_officedesk_arr[] = $prsentIn_office;
									}
								}
								
								
							
							$tot_present_in_officedesk=array_sum($tot_present_in_officedesk_arr);
							
							$tot_attend_duration = number_format((float)$tot_present_in_officedesk, 2, '.', '');
							$tot_not_attend_duration =   number_format((float)$tot_office_duration -  $tot_attend_duration, 2, '.', '');
							
							
							$final_offduration = floor($tot_office_duration) . '.' . (($tot_office_duration * 60) % 60);
							$final_tot_attend_duration = floor($tot_attend_duration) . '.' . (($tot_attend_duration * 60) % 60);
							
							
						   $date_of_month= $curdate;
						   $day  = date('Y-m-d',strtotime($date_of_month));
						   $result = date("l", strtotime($day));
						   if($result == "Saturday")
						   {
							  if($final_offduration < 7)
									{
										$attendance_status='A';									
									}
									else
									{
										if($final_tot_attend_duration<6.30)
										{$attendance_status='A';}
										else
										{$attendance_status='P';}
									} 
						   }
						   else
						   {
							  		if($final_offduration < 8.55)
									{
										$attendance_status='A';									
									}
									else
									{
										if($final_tot_attend_duration<8)
										{$attendance_status='A';}
										else
										{$attendance_status='P';}
									} 
							}
									
							
							
							$data_attnd=array(
												'login_id' => $login_id,
												'biomtrc_id' => $biomtrc_id,
												'user_name' => $uname, 
												'attend_date' => $curdate,
												'first_punch_in' => $first_punch_in,
												'last_punch_out' => $last_punch_out,
												'tot_office_duration' =>floor($tot_office_duration) . '.' . (($tot_office_duration * 60) % 60),
												'tot_attend_duration' => floor($tot_attend_duration) . '.' . (($tot_attend_duration * 60) % 60),
												'tot_not_attend_duration' => floor($tot_not_attend_duration) . '.' . (($tot_not_attend_duration * 60) % 60),
												'attendance_status' => $attendance_status
											  );
											  
							$attnd_duration_sql="SELECT bdw_sqlid FROM biomatrics_daily_workduration WHERE login_id= ? AND biomtrc_id = ? AND attend_date = ? ";
							
							$attnd_duration_query=$this->db->query($attnd_duration_sql,array($login_id,$biomtrc_id,$curdate))->result_array();
							
							if(count($attnd_duration_query)==0)
							{
								$this->db->insert('biomatrics_daily_workduration',$data_attnd);	
							}
							else
							{
								$this->db->where('biomtrc_id',$biomtrc_id);
								$this->db->where('attend_date',$curdate);
								$this->db->update('biomatrics_daily_workduration',$data_attnd);	
							}
							
							$daily_attendance_sql = "SELECT login_id , date FROM attendance_new WHERE login_id= ? AND date = ? ";
							$daily_attendance_query = $this->db->query($daily_attendance_sql, array($login_id,$curdate))->result_array();
							
							//------------ Data Insert Or Update in attendance_new Table Start------------// 
							
								if($attendance_status=='P')
								{$attend_as_per_work_duration_calc='P';}
								else
								{$attend_as_per_work_duration_calc='A';}
								
								
								
							if(count($daily_attendance_query)==0)
							{
								$data_attnd_insrt = array();
								
								if($tot_in_ctr!=$tot_out_ctr)
								{
									$last_punch_out2="00:00:00";
								}
								else
								{
									$last_punch_out2=$last_punch_out;	
								}
								
								
								$data_attnd_insrt=array(	'login_id'=>$login_id,
															'date'=>$curdate,
															'att_status'=>'P',
															'in_time' =>$first_punch_in,
															'out_time' => $last_punch_out2,
															'leave_type' => 'P',
															'attend_as_per_work_duration_calc'=>$attend_as_per_work_duration_calc														
														);
								 $this->db->insert('attendance_new',$data_attnd_insrt);						   
							}
							else
							{
								$data_insrt2=array();
								
								if($tot_in_ctr!=$tot_out_ctr)
								{
									$last_punch_out2="00:00:00";
								}
								else
								{
									$last_punch_out2=$last_punch_out;	
								}
								
								$data_insrt2 = array(
														'in_time' =>$first_punch_in,
														'out_time' => $last_punch_out2,
											  			'attend_as_per_work_duration_calc'=>$attend_as_per_work_duration_calc
											  		); 
								
								$this->db->where('login_id',$login_id);	
								$this->db->where('date',$curdate);
								$this->db->update('attendance_new',$data_insrt2);
							}
							
							//------------ Data Insert Or Update in attendance_new Table End------------// 
											  
							// --------- Attendance summary data entry end --------------//
							
							
														
						
						} // main for  loop end
				} // if condition end
			
		} // function end
		
		
		
		/*public function Upload_Punch_out_Attendance()
		{
			$this->load->library('zklibrary');
			$zk = new ZKLibrary('172.61.25.79', 4370);
			$zk->connect();
			$zk->disableDevice();
			$user_attend = $zk->getAttendance();
			
			
			foreach($user_attend as $key=>$userattnd)
			{
				$user_id = $userattnd[1];
				$qr_sql =  "SELECT user_name FROM biomatrics_user_data WHERE biomtrc_id = ?";
				
				$qr = $this->db->query($qr_sql, array($user_id));
				
				$uname= $qr->row()->user_name;
				
				$data=array(
							'biomatric_slno'=>$userattnd[0],
							'biomtrc_id'=>$userattnd[1],
							'user_name'=>$uname,
							'sensing_type'=>$userattnd[2],
							'attendlog_dtm'=>$userattnd[3],
							);
							
				$this->db->insert('biomatrics_punch_out_attend',$data);			
							
			}			
				$zk->enableDevice();
				$zk->disconnect();			
			
		}
		*/
		
	
	
}
