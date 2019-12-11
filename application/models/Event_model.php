<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Event_model extends CI_Model {
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	public function upcoming_birthday() 
	{
		$sDate = date('m-d');
		$sql = $this->db->query("SELECT `login_id`,`full_name`, `dob_with_current_year`, `user_photo_name` FROM `internal_user` WHERE `dob_with_current_year` > '$sDate' AND `user_status` = '1' ORDER BY `dob_with_current_year` LIMIT 4"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_my_assets()
	{
		$empCode = $this->session->userdata('empCode');
		//$empCode = 'PTPL-10010';
		$ret=array();
		
		/* if($this->db1 = $this->load->database('amsdb', TRUE)){
			//Get My Assets
			$my_asset_sql = "SELECT UCASE(m.s_machine_type) AS AssetName, m.s_machine_name AS AssetCode, DATE_FORMAT(a.added_date, '%d %b %Y') AS AssignedDate
			FROM `employee_info` AS e INNER JOIN `allotment` AS a ON a.source_id = e.ix_emp_id AND a.source_type = 'E'
			INNER JOIN `machine` AS m ON m.ix_machine = a.product_id
			WHERE e.ix_corp_id = '".$empCode."'
			UNION
			SELECT UCASE(t.s_accessories_name) AS AssetName, a.s_asset_no AS AssetCode, DATE_FORMAT(a.d_assigned_date, '%d %b %Y') AS AssignedDate
			FROM accessories AS a INNER JOIN accessories_type AS t ON t.ix_accessories_type = a.ix_accessories_type 
			INNER JOIN employee_info AS e ON e.ix_emp_id = a.n_assign_to_id
			WHERE c_assign_to = 'E' AND e.ix_corp_id = '".$empCode."'";
			$result = $this->db1->query($my_asset_sql);
			$this->db = $this->load->database('default', TRUE); 
			return $result->result_array(); 
		}
		else{
			return $ret=array();
		} */
		return $ret;
	}
	
	public function get_telephone_no()
	{
		// Get Telephone Nos
		//$tel_sql = "SELECT name AS name, tel_no_with_ext AS phone FROM `company_telephone_directory` WHERE `n_disp_flag` = '1' ORDER BY `name` ASC LIMIT 5";
		$tel_sql = $this->db->query("SELECT c.name AS name, c.tel_no_with_ext AS phone FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1'
		   UNION
		   SELECT CONCAT(i.name_first, ' ', i.name_last) AS name, i.mobile  FROM `internal_user` i WHERE i.user_status = '1' AND i.isShowOnSearch = 'Y'
		   ORDER BY name LIMIT 5");
		$result = $tel_sql->result_array();
		return $result;
	}
	
	public function get_latest_classified()
	{
		// Get Latest 5 classified
		$classified_sql = "SELECT c.*, i.name_first, i.name_last, i.mobile FROM `classified` c INNER JOIN `internal_user` i ON i.login_id = c.posted_by WHERE `n_disp_flag` = 1 ORDER BY `ix_classified` DESC LIMIT 5"; 
		$result = $this->db->query($classified_sql)->result_array();
		return $result;
	}
	
	public function get_birthday_message()
	{
		//Birthday Messages
		$birthday_sql = "SELECT b.*, i.* FROM `birthday_message` b INNER JOIN `internal_user` i ON i.login_id = b.post_id WHERE b.login_id = '".$_SESSION['user_id']."' ORDER BY `cdate` DESC"; 
		$result = $this->db->query($birthday_sql)->result_array();
		return $result;
	}
	
	public function check_usrer_password()
	{
		// Check User Password 
		// if polohrm@123 then show changePasswordBox
		$password_sql = "SELECT `login_id` FROM `internal_user` WHERE `login_id` = '".$this->session->userdata('user_id')."' AND `password` = '3d6e89c63cab98b4d95e4ebb908c5cec' LIMIT 1";
		$result = $this->db->query($password_sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	
	public function get_director_message()
	{
		//Director's Message
		$sql = "SELECT `message` FROM  director_message";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function get_general_alert()
	{
		//General Alert
		$sql = "SELECT `message` FROM  alert_general limit 1";
		$result = $this->db->query($sql)->result_array();
		return $result; 
	}
	
	public function get_resource_marquee()
	{
		//For Resource Marquee
		$marqueRes = "SELECT * FROM  `resource_doc` where topic_id='1' AND flag='1' order by dttime desc";
		$result = $this->db->query($marqueRes)->result_array();
		return $result;
	}
	
	public function download_resource_marquee($doc_id,$doc_name)
	{
		//For Resource Marquee
		$sql = "SELECT * FROM  `resource_doc` where doc_id='".$doc_id."' AND doc_name='".$doc_name."' AND topic_id='1' AND flag='1'";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function get_hall_of_fame()
	{ 
		//for hall of fame
		$sqls = $this->db->query("SELECT c.id, c.user_id,c.image,c.title,c.description,c.status,c.month,c.year, i.loginhandle, i.full_name FROM `hall_of_fame` c INNER JOIN `internal_user` i ON i.login_id = c.user_id where c.status='1' ORDER BY c.id DESC  LIMIT 1"); 
		$results = $sqls->result_array();
		//var_dump($results);
		$cond = "";
		if(count($results)>0){
			$month = $results[0]['month'];
			$year = $results[0]['year'];
			$cond = " AND c.month='$month' AND c.year='$year'";
		}
		$sql = $this->db->query("SELECT c.id, c.user_id,c.image,c.title,c.description,c.status,c.month,c.year, i.loginhandle, i.full_name FROM `hall_of_fame` c INNER JOIN `internal_user` i ON i.login_id = c.user_id where c.status='1' $cond ORDER BY c.h_order ASC"); 
		$result = $sql->result_array();
		//var_dump($result);
		return $result; 
	} 
	
	public function get_news_and_events_calender($for = 'All', $month = '', $year = '')
	{
		
	}
	
	public function get_news_and_events_today($for = 'Today', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'Today')
		{
			$bCond = "i.`dob_with_current_year` = '$currentDate'";
			$eCond = "DATE_FORMAT(i.dob, '%m-%d') = '$currentDate'";
		} 
		// Get Birthday Details
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $eCond ORDER BY i.`dob`"; 
		$sRes = $this->db->query($sQry); 
		$result = $sRes->result_array(); 
		return $result; 
	}
	
	public function get_news_and_event_weekly($for = 'ThisWeek', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");
		$tomorrow = date("Y-m-d", strtotime("+ 1 day"));
		$currentDate = date("m-d", strtotime($tomorrow));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'ThisWeek')
		{
			$currentDate = date("m-d",strtotime($tomorrow));
			$lastWeekDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			$date = date("Y-m-d",strtotime('+1 days'));
			$lastWeekYDate = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			//$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastWeekDate'";
			$bCond = "DATE_FORMAT(i.dob, '%m-%d') BETWEEN '$currentDate' AND '$lastWeekDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastWeekYDate'";
		}
		$sql = "SELECT i.`login_id`, i.`name_first`,i.`dob`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`";
		$result = $this->db->query($sql); 
		$weekly = $result->result_array();
		return $weekly; 
	}
	
	public function get_news_and_event_monthly($for = 'ThisMonth', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$tomorrow = date("Y-m-d", strtotime("+ 1 day"));
		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'ThisMonth')
		{
			/* $days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
			$noOfDaysToAddToGetThisWeekNextDay = $noOfDaysToAddToGetLastDayOfWeek;
			$currentDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$date = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'"; */
			$cdate = date("m-d", strtotime($tomorrow));
			
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "`dob_with_current_year` BETWEEN '$cdate' AND '$lastDayMonth'";
		}
		$sql = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`dob`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $eCond ORDER BY i.`dob_with_current_year`"; 
		$result = $this->db->query($sql); 
		$monthly = $result->result_array();
		return $monthly; 
		
	}
	
	public function get_news_and_event_upcoming($for = 'UpComing', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'UpComing')
		{
			$nextMonth = $mm + 1;
			$nextYear = $yy;
			if($mm > 11){
				$nextMonth = '01';
				$nextYear = $yy + 1;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			//$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		$sql = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`dob`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`"; 
		$result = $this->db->query($sql); 
		$upcoming = $result->result_array();
		return $upcoming;
		
	}
	
	public function get_news_and_event_week($for = 'Week', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'Week')
		{
			$weekFDate = date("m-d",strtotime($month));
			$weekEDate = date("m-d",strtotime($year));
			$weekFDateMonth = date("Y-m-d",strtotime($month));
			$weekEDateMonth = date("Y-m-d", strtotime($year));
			$bCond = "i.`dob_with_current_year` BETWEEN '$weekFDate' AND '$weekEDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$weekFDateMonth' AND '$weekEDateMonth'";
		}
		$sql = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`"; 
		$result = $this->db->query($sql); 
		$week = $result->result_array();
		return $week;
		
	}
	
	public function get_news_and_event_day($for = 'UpComing', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'Day')
		{
			$cDate = date("m-d",$month);
			$cMDate = date("Y-m-d",$month);
			$bCond = "i.`dob_with_current_year` = '$cDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') = '$cMDate'";
		}
		$sql = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`"; 
		$result = $this->db->query($sql); 
		$day = $result->result_array();
		return $day;
		
	}
	
	public function get_news_and_events_archive($for = 'Archive', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'Archive')
		{
			if($month != ''){
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}else{
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime('-1 days'));
			}
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
		}
	}
	
	
	public function check_old_password($oldPassword, $user_id)
	{
		$this->db->select('internal_user.name_first');		
		$this->db->from('internal_user');				
		$this->db->where('password', md5($oldPassword));		
		$this->db->where('internal_user.login_id', $user_id);		
		$query = $this->db->get();		
		$result = $query->result(); 
		return $result; 
	}
	
	public function update_new_password($newPassword, $user_id)
	{
		$data = array(
			'password' => md5($newPassword)
		);
		$this->db->where('internal_user.login_id', $user_id);
		$this->db->update('internal_user', $data);	
	}
	
	
	public function get_aniversarry_this_month()
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		
		$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
			$noOfDaysToAddToGetThisWeekNextDay = $noOfDaysToAddToGetLastDayOfWeek;
			$currentDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$date = date("Y-m-d",strtotime('+1 days'));
						
			$string = $yy.'-'.$mm.'-01';
			$cdate = date("m-d", strtotime($string));
			
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$cdate' AND '$lastDayMonth'";
		
		// Get anniverasary Details
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $eCond ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
		//echo $sQry;
		$sRes = $this->db->query($sQry);
		$monthly = $sRes->result_array();
		return $monthly; 
	}
	
	
	public function get_upcoming_aniversarry()
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		
			$nextMonth = $mm + 1;
			$nextYear = $yy;
			if($mm > 11){
				$nextMonth = '01';
				$nextYear = $yy + 1;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			//$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$currentDate' AND '$lastDayMonth'";
		
		// Get anniverasary Details
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $eCond ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
		//echo $sQry;
		$sRes = $this->db->query($sQry);
		$monthly = $sRes->result_array();
		return $monthly; 
	}
	
	public function get_anniversary_search($dd_year, $dd_month)
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));
		$eCond = '';
		$date = date("Y-m-d",strtotime('+1 days'));	
		$string = $dd_year.'-'.$dd_month.'-01';
		$cdate = date("m-d", strtotime($string));
		$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($dd_month.'/01/'.$dd_year.' 00:00:00'))));
		$eCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$cdate' AND '$lastDayMonth'";
		
		// Get anniverasary Details
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date`, DATE_FORMAT(f.anniversary_date, '%d') as annv_day, DATE_FORMAT(f.anniversary_date, '%D %M') as annv_date FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $eCond ORDER BY DATE_FORMAT(f.anniversary_date, '%m-%d') ASC"; 
		//echo $sQry;
		$sRes = $this->db->query($sQry);
		$monthly = $sRes->result_array();
		return $monthly; 
	}
	
	public function get_my_regularies_pending_count()
	{
		$leaveAppSql = "SELECT r.*, i.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`status` = 'P' AND r.`user_id` = '".$this->session->userdata('user_id')."' ORDER BY req_date DESC";
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return count($result);
	}
	
	public function get_my_leave_request_count()
	{
		// Get My Leave Application
		$leaveAppSql = "SELECT l.*, i.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.user_id = i.login_id WHERE l.status = 'P' AND l.user_id = '".$this->session->userdata('user_id')."'";
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return count($result);
	}
	
	
	public function get_telephone_no_search($search_key)
	{
		// Get Telephone Nos
		if($search_key !=""){
			//$tel_sql = "SELECT name AS name, tel_no_with_ext AS phone FROM `company_telephone_directory` WHERE `n_disp_flag` = '1' AND name LIKE '%".$search_key."%' ORDER BY `name` ASC LIMIT 5";
			$tel_sql = "SELECT c.name AS name, c.tel_no_with_ext AS phone FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1'  AND c.name LIKE '%".$search_key."%'
		   UNION
		   SELECT CONCAT(i.name_first, ' ', i.name_last) AS name, i.mobile  FROM `internal_user` i WHERE i.user_status = '1' AND i.isShowOnSearch = 'Y' AND full_name LIKE '%".$search_key."%'
		   ORDER BY name LIMIT 5";
		}
		else{
			$tel_sql = "SELECT c.name AS name, c.tel_no_with_ext AS phone FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1'
		   UNION
		   SELECT CONCAT(i.name_first, ' ', i.name_last) AS name, i.mobile  FROM `internal_user` i WHERE i.user_status = '1' AND i.isShowOnSearch = 'Y'
		   ORDER BY name ASC LIMIT 5";
		}
		$result = $this->db->query($tel_sql)->result_array();
		return $result;
	}
	
	//advance filter
	public function get_department()
	{
		/* query for department */
		$sql = $this->db->query("SELECT dept_id,dept_name FROM department WHERE dept_status = '1' ORDER BY dept_name");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_designation()
	{
		/* query for designation */
		$sql = $this->db->query("SELECT desg_id,desg_name FROM user_desg WHERE desg_status = '1' ORDER BY desg_sort_order");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_designation_by_department($dept_id)
	{
		/* query for designation */
		$sql = $this->db->query("SELECT desg_id,desg_name FROM user_desg WHERE desg_status = '1' AND dept_id = '".$dept_id."' ORDER BY desg_sort_order");
		$result = $sql->result_array(); 
		return $result;
	}
	
	public function get_specialization_by_qualification($qualification)
	{
		/* query for designation */
		$sql = $this->db->query("SELECT specialization_id,specialization_name FROM specialization_master WHERE status = 'Y' AND course_id = '".$qualification."' ORDER BY specialization_name");
		$result = $sql->result_array(); 
		return $result;
	}
	
	public function get_all_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$cond = "i.login_id != '10010'";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.user_status , dp.dept_id, dp.dept_name, ds.desg_id, ds.desg_name FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` ds ON ds.desg_id = i.designation WHERE i.user_status != '0' AND $cond ORDER BY i.login_id DESC"); 
		return $sql->result_array(); 
	}
	
	
	public function get_all_emp_advance_search_filter($searchDepartment , $searchName, $searchDesignation , $searchEmpCode, $searchStatus)
	{
		$cond = "i.login_id != '10010'";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		if($searchStatus !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.user_status = '".$searchStatus."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.user_status , dp.dept_id, dp.dept_name, ds.desg_id, ds.desg_name FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` ds ON ds.desg_id = i.designation WHERE i.user_status != '0' AND $cond ORDER BY i.login_id DESC"); 
		return $sql->result_array(); 
	}
	
	public function get_all_active_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$cond = " i.login_id != '10010'";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.user_status , dp.dept_id, dp.dept_name, ds.desg_id, ds.desg_name FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` ds ON ds.desg_id = i.designation WHERE i.user_status = '1' AND $cond ORDER BY i.login_id DESC"); 
		return $sql->result_array(); 
	}
	
	public function get_all_inactive_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$cond = "i.login_id != '10010'";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.user_status , dp.dept_id, dp.dept_name, ds.desg_id, ds.desg_name FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` ds ON ds.desg_id = i.designation WHERE i.user_status = '2' AND $cond ORDER BY i.login_id DESC"); 
		return $sql->result_array(); 
	}
	
	public function wish_birthday($login_id,$post_id,$message){
		$year = date('Y');
		$wishedSQL = 'SELECT * FROM birthday_message WHERE login_id ='.$post_id.' AND post_id='.$login_id.' AND EXTRACT(year FROM cdate) = '.$year;
		$wishedRes = $this->db->query($wishedSQL);
		$wishedInfo = $wishedRes->result_array();
		$countWishes = COUNT($wishedInfo);
		if($countWishes == 0 ){
			$cdate = date("Y-m-d H:m:s");
			$wishSQL = 'INSERT INTO `birthday_message`(login_id,post_id,message,cdate) VALUES ("'.$post_id.'","'.$login_id.'","'.$message.'","'.$cdate.'")';
			$wishRes = $this->db->query($wishSQL);
			echo "1";
		} else {
			$wishSQL = "UPDATE `birthday_message` SET message= '".$message."' WHERE `login_id` = '".$post_id."' AND `post_id` = '".$login_id."'";
			$wishRes = $this->db->query($wishSQL);
			echo "2";
		}
	}
	
	public function wish_birthday_check($login_id,$post_id){
		$year = date('Y');
		$wishedSQL = 'SELECT * FROM birthday_message WHERE login_id ='.$post_id.' AND post_id='.$login_id.' AND EXTRACT(year FROM cdate) = '.$year;
		$wishedRes = $this->db->query($wishedSQL);
		$wishedInfo = $wishedRes->result_array();
		$countWishes = COUNT($wishedInfo);
		return $wishedInfo;
	}
	
	public function get_birthday_wishes($userID){
		$getWishSQl = "SELECT b.*, i.* FROM `birthday_message` b INNER JOIN `internal_user` i ON i.login_id = b.post_id WHERE b.login_id = '".$userID."' ORDER BY `bid` DESC";
		$getwishRes = $this->db->query($getWishSQl);
		return $getwishRes->result_array();
	}
	
	public function get_news_and_events_Day($date)
	{
		$day = date('m-d',$date);
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`,MONTHNAME(i.`dob`) as Month,DATE_FORMAT(i.`dob`,'%Y') as year,DATE_FORMAT(i.`dob`,'%b') as mon,DATE_FORMAT(i.`dob`,'%d') as date, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.`user_status` = '1' AND DATE_FORMAT(i.dob, '%m-%d') = '".$day."' ORDER BY DATE_FORMAT(i.dob, '%m-%d') ASC"; 
		$sRes = $this->db->query($sQry); 
		$result = $sRes->result_array();
		return $result;
	}

	public function get_news_and_events_Weekly($sdate,$edate)
	{
		$newsdate = date("m-d", strtotime($sdate));
		$newedate = date("m-d", strtotime($edate));
		$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`,MONTHNAME(i.`dob`) as Month,DATE_FORMAT(i.`dob`,'%Y') as year,DATE_FORMAT(i.`dob`,'%b') as mon,DATE_FORMAT(i.`dob`,'%d') as date, d.`desg_name` FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.`user_status` = '1' AND DATE_FORMAT(i.dob, '%m-%d') BETWEEN '".$newsdate."' AND '".$newedate."' ORDER BY DATE_FORMAT(i.dob, '%m-%d') ASC"; 
		$sRes = $this->db->query($sQry); 
		$result = $sRes->result_array();
		return $result;
	}
	
	
	public function get_all_leave_request_rm_count()
	{
		// Get My Leave Application
		$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' AND l.status='P' ORDER BY application_id DESC";
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return count($result);
	}
	
	public function get_all_regularization_request_rm_count()
	{
		$regAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."' AND r.status='P'  ORDER BY attd_req_id DESC";
		$sql = $this->db->query($regAppSql);
		$result = $sql->result_array();
		return count($result);
	}
	
	public function get_active_employee()
	{
		// Get Active Employees
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.user_status = 2  ORDER BY i.login_id DESC");
		$result = $sql->result_array();
		return $result;
	}
	
	public function get_repoting_manager_for_emp($login_id)
	{
		// Get Active Employees
		$sql = $this->db->query("SELECT rev.loginhandle, rev.login_id, rev.full_name  FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$login_id."");
		$result = $sql->result_array();
		return $result;
	}
	
	
	
}
