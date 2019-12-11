<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Anniversary extends MY_Controller 
{ 
	var $data = array('visit_type' => '','pageTitle' => '','file' =>''); 
	public function __construct()
	{
		parent::__construct();
		/******************* Remote Restriction Control ************************/
		$ipAddr=array();
		for($p=1; $p<255;$p++){
			$ip='172.61.24.'.$p;
			array_push($ipAddr,$ip);
			$ip='172.61.25.'.$p;
			array_push($ipAddr,$ip);
		}
		array_push($ipAddr, '203.129.198.75');
		if(!in_array($_SERVER['REMOTE_ADDR'], $ipAddr) && $_SERVER['REMOTE_ADDR'] != '::1' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '111.93.166.70' && $_SERVER['REMOTE_ADDR'] != '220.225.24.37' && $_SERVER['REMOTE_ADDR'] != '123.63.170.114'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		if($_SERVER['REMOTE_ADDR'] == '203.129.198.75'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		/******************* END/ Remote Restriction Control ************************/
		
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->model('event_model');
		if(empty($this->session->userdata['user_id']))
		{
			redirect(site_url('login'),'refresh');
		}
		
		// create the data object
		$mViewData = new stdClass();
		//Today Event count 
		$eventToday = $this->event_model->get_news_and_events_today();
		$this->mViewData['countEventToday'] = count($eventToday);
		$this->mViewData['countRegularizationPending'] = $this->event_model->get_my_regularies_pending_count();
		$this->mViewData['countLeavePending'] = $this->event_model->get_my_leave_request_count();
		$this->mViewData['countRegularizationPendingRM'] = $this->event_model->get_all_regularization_request_rm_count();
		$this->mViewData['countLeavePendingRM'] = $this->event_model->get_all_leave_request_rm_count();
		error_reporting(0);
	}

	public function index()
	{
		$this->mViewData['pageTitle'] = 'Anniversary';
		
		$currentURL = current_url();
		$params   = $_SERVER['QUERY_STRING'];  
		$this->mViewData['fullURL'] = $currentURL . '?' . $params;
		// create the data object
		$mViewData = new stdClass();
		//logic of news and events
		$type = $_GET['type']; 
		$showSearchBox = FALSE;
		if($type == 'Anniversary')
		{
			$subheader='Upcoming Anniversary';
			//$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Upcoming Anniversary';
		}
		elseif($type == 'All')
		{
			$type = 'All';
			$showSearchBox = TRUE;
			$subheader='View All Anniversary';
			$this->mViewData['title'] = 'View All Anniversary';
		}
		elseif($type == 'ThisMonth')
		{
			$subheader='Anniversary of this month';
			$this->mViewData['title'] = 'Anniversary of this month';
			$subTitle = 'this month';
		}
		else
		{
			$type = 'All';
			$showSearchBox = TRUE;
			$subheader='View All Anniversary';
			$this->mViewData['title'] = 'All Anniversary';
		}

		$mm = date("m");
		$yy = date("Y");

		if($this->input->post('btnSearch') == 'Search')
		{
			$dd_month = $_POST['dd_month'];
			$dd_year = $_POST['dd_year'];
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['anniversary'] = $this->get_anniversary($type, $dd_month, $dd_year);
		} 
		else
		{
			$dd_month = $mm;
			$dd_year = $yy;
			if($type == 'All' || $type == 'ThisMonth' || $type == 'Anniversary')
			{
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			$this->mViewData['anniversary'] = $this->get_anniversary($type);
			//var_dump($this->mViewData['anniversary']);
		}  
		$this->mViewData['dd_month'] = $dd_month;
		//usort($anniversary,'DateCmp');
		//Template view
		$this->render('news_and_events/anniversary_view', 'full_width', $this->mViewData); 
		$this->load->view('script/news_and_events/anniversary_js');
	}
	public function get_anniversary($for = 'Anniversary', $month = '', $year = '')
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
		if($for == 'Anniversary')
		{
			$nextMonth = $mm + 1;
			$nextYear = $yy;
			if($mm > 11)
			{
				$nextMonth = '01';
				$nextYear = $yy + 1;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$dateMFirst = date("m-d",strtotime('+1 days'));
			$dateMLast = date("m-d",strtotime($year.'-'.$month.'-'.$days));
			$date = date("Y-m-d",strtotime('+1 days'));
			$cdate = date("m-d", strtotime($date));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$cdate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(f.`anniversary_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'"; 
		}
		else if($for == 'All')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateMFirst = date("m-d",strtotime($year.'-'.$month.'-01'));
				$dateMLast = date("m-d",strtotime($year.'-'.$month.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
				$dateMFirst = date("m-d",strtotime($yy.'-'.$mm.'-01'));
				$dateMLast = date("m-d",strtotime($yy.'-'.$mm.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime($yy.'-'.$mm.'-'.$days));
			}
			$bCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$dateMFirst' AND '$dateMLast'";
			$eCond = "DATE_FORMAT(f.anniversary_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
			//$bCond = "f.`anniversary_date` BETWEEN '$currentDate' AND '$lastDayMonth'";
			//$eCond = "DATE_FORMAT(f.`anniversary_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		elseif($for == 'ThisMonth')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateMFirst = date("m-d",strtotime($year.'-'.$month.'-01'));
				$dateMLast = date("m-d",strtotime($year.'-'.$month.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
				$dateMFirst = date("m-d",strtotime($yy.'-'.$mm.'-01'));
				$dateMLast = date("m-d",strtotime($yy.'-'.$mm.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime($yy.'-'.$mm.'-'.$days));
			}
			$bCond = "DATE_FORMAT(anniversary_date, '%m-%d') BETWEEN '$dateMFirst' AND '$dateMLast'";
			$eCond = "DATE_FORMAT(f.anniversary_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
			//$bCond = "f.`anniversary_date` BETWEEN '$currentDate' AND '$lastDayMonth'";
			//$eCond = "DATE_FORMAT(f.`anniversary_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		$anniversary = array();
		if($for == 'Anniversary')
		{
			// Get anniverasary Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $bCond ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
			//echo $sQry;
			$sRes = $this->db->query($sQry);
			$get_arr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($get_arr as $sInfo)
				{
					$ann = array((date("Y").'-'.$sInfo['anniversary_date']),
										 'AN',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender'],
										 $sInfo['anniversary_date']
										);
					$anniversary[] = $ann;
				}
			}
		}
		else if($for == 'ThisMonth')
		{
			// Get anniverasary Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $bCond ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
			//echo $sQry;
			$sRes = $this->db->query($sQry);
			$arr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($arr as $sInfo)
				{
					$ann = array((date("Y").'-'.$sInfo['anniversary_date']),
										 'AN',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender'],
										 $sInfo['anniversary_date']
										);
					$anniversary[] = $ann;
				}
			}
		}
		else if($for == 'All')
		{
			// Get anniverasary Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' AND $bCond ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
			//echo $sQry;
			$sRes = $this->db->query($sQry);
			$sInfo_arr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($sInfo_arr as $sInfo)
				{
					$ann = array((date("Y").'-'.$sInfo['anniversary_date']),
										 'AN',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender'],
										 $sInfo['anniversary_date']
										);
					$anniversary[] = $ann;
				}
			}
		}
		else
		{
			// Get anniverasary Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name`,f.`anniversary_date` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation LEFT JOIN `family_info` f ON f.login_id = i.login_id WHERE  i.`user_status` = '1' AND f.`anniversary_date` != '0000-00-00' ORDER BY DATE_FORMAT(anniversary_date, '%m-%d') ASC"; 
			//echo $sQry;
			$sRes = $this->db->query($sQry);
			$sInfoarr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($sInfoarr as $sInfo)
				{
					$ann = array((date("Y").'-'.$sInfo['anniversary_date']),
										 'AN',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender'],
										 $sInfo['anniversary_date']
										);
					$anniversary[] = $ann;
				}
			}
		}
		return $anniversary;
	}
	public function get_anniversary_one($for = 'Anniversary', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6){
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'All')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateMFirst = date("m-d",strtotime($year.'-'.$month.'-01'));
				$dateMLast = date("m-d",strtotime($year.'-'.$month.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
				$dateMFirst = date("m-d",strtotime($yy.'-'.$mm.'-01'));
				$dateMLast = date("m-d",strtotime($yy.'-'.$mm.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime($yy.'-'.$mm.'-'.$days));
			}
			$bCond = "i.`dob_with_current_year` BETWEEN '$dateMFirst' AND '$dateMLast'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
		}
		elseif($for == 'Today')
		{
			$bCond = "i.`dob_with_current_year` = '$currentDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') = '$date'";
		}
		elseif($for == 'ThisWeek')
		{
			$currentDate = date("m-d",strtotime('+1 days'));
			$lastWeekDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			$date = date("Y-m-d",strtotime('+1 days'));
			$lastWeekYDate = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastWeekDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastWeekYDate'";
		}
		elseif($for == 'ThisMonth')
		{
			$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
			$noOfDaysToAddToGetThisWeekNextDay = $noOfDaysToAddToGetLastDayOfWeek;
			$currentDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$date = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		elseif($for == 'UpComing')
		{
			$nextMonth = $mm + 1;
			$nextYear = $yy;
			if($mm > 11)
			{
				$nextMonth = '01';
				$nextYear = $yy + 1;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		elseif($for == 'Archive')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime('-1 days'));
			}
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
		}
		elseif($for == 'Week')
		{
			$weekFDate = date("m-d",strtotime($month));
			$weekEDate = date("m-d",strtotime($year));
			$weekFDateMonth = date("Y-m-d",strtotime($month));
			$weekEDateMonth = date("Y-m-d", strtotime($year));
			$bCond = "i.`dob_with_current_year` BETWEEN '$weekFDate' AND '$weekEDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$weekFDateMonth' AND '$weekEDateMonth'";
		}
		elseif($for == 'Day')
		{
			$cDate = date("m-d",$month);
			$cMDate = date("Y-m-d",$month);
			$bCond = "i.`dob_with_current_year` = '$cDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') = '$cMDate'";
		}
		
		if($for != 'Archive')
		{
			// Get Birthday Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`designation`, i.`gender`, d.`desg_name` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`";
			$sRes = $this->db->query($sQry);
			$arr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($arr as $sInfo)
				{
					$birthdayArray = array((date("Y").'-'.$sInfo['dob_with_current_year']),
										 'B',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender']
										);
					$newsAndEvents[] = $birthdayArray;
				}
			}
		}
		
		if($for == 'ThisMonth' && $dd > 01)
		{
			$firstDayYMonth = date("Y-m-d", strtotime($mm.'/01/'.$yy.' 00:00:00'));
			$toDayM1 = date("Y-m-d",strtotime('-1 days'));
			$eCond1 = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$firstDayYMonth' AND '$toDayM1'";
			// Get News and Events
			$newsEventQry = "SELECT * FROM `news_event` WHERE `n_disp_flag` = 1 AND $eCond1 ORDER BY `on_date` ASC";
			$newsEventRes = $this->db->query($newsEventQry);
			$array = $newsEventRes->result_array();
			$newsEventNum = count($newsEventRes);
			
			if($newsEventNum > 0)
			{
				foreach($array as $newsEventInfo)
				{
					$eventArray = array(($newsEventInfo['on_date']),
										 $newsEventInfo['type'],
										 $newsEventInfo['title'],
										 $newsEventInfo['body'],
										 $newsEventInfo['to_date'],
										 $newsEventInfo['file'],
										 $newsEventInfo['id']
										);
					$newsAndEvents[] = $eventArray;
				}
			}
		}
		
		// Get News and Events
		$newsEventQry = "SELECT * FROM `news_event` WHERE `n_disp_flag` = 1 AND $eCond ORDER BY `on_date` ASC";
		$newsEventRes = $this->db->query($newsEventQry);
		$arra = $newsEventRes->result_array();
		$newsEventNum = count($newsEventRes);
		
		if($newsEventNum > 0)
		{
			foreach($arra as $newsEventInfo)
			{
				$eventArray = array(($newsEventInfo['on_date']),
									 $newsEventInfo['type'],
									 $newsEventInfo['title'],
									 $newsEventInfo['body'],
									 $newsEventInfo['to_date'],
									 $newsEventInfo['file'],
									 $newsEventInfo['id']
									);
				$newsAndEvents[] = $eventArray;
			}
		} 	   
		return $newsAndEvents;
	}
	
	
	public function search_anniversary()
	{
		$dd_month = $this->input->post('dd_month');
		$dd_year = $this->input->post('dd_year');
		$searchData = $this->event_model->get_anniversary_search($dd_year, $dd_month);
		echo json_encode($searchData);
	}
}
