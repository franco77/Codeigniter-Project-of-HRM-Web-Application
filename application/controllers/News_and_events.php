<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News_and_events extends MY_Controller 
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
		$this->mViewData['pageTitle'] = 'news_and_events';
		//logic of news and events
		
		$currentURL = current_url();
		$params   = $_SERVER['QUERY_STRING'];  
		$this->mViewData['fullURL'] = $currentURL . '?' . $params;
        //var_dump($this->mViewData['fullURL']);

		$choose_year = $this->input->post('dd_year');
		if (!$choose_year) 
		{
			$choose_year = date("Y");
		}
		$type = $this->input->get('type');
		$showSearchBox = FALSE;
		if($type == 'Today')
		{
			$subheader='News &amp; Events Today';
			$this->mViewData['title']= 'News &amp; Events Today';
			$subTitle = 'today';
		}
		elseif($type == 'ThisWeek')
		{
			$subheader='News &amp; Events of this week';
			$this->mViewData['title'] = 'News &amp; Events This Week';
			$subTitle = 'this week';
		}
		elseif($type == 'ThisMonth')
		{
			$subheader='News &amp; Events of this month';
			$this->mViewData['title'] = 'News &amp; Events This Month';
			$subTitle = 'this month';
		}
		elseif($type == 'Archive')
		{
			$subheader='Archive';
			$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Archive News &amp; Events';
		}
		elseif($type == 'Upcoming')
		{
			$subheader='Upcoming News &amp; Events';
			$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Upcoming News &amp; Events';
		}
		else
		{
			$type = 'All';
			$showSearchBox = TRUE;
			$subheader='All News &amp; Events';
			$this->mViewData['title'] = 'News &amp; Events';
		} 
		$mm = date("m");
		$yy = date("Y");

		if($this->input->post('btnSearch') == 'Search' && $type == 'Upcoming')
		{
			$dd_month = $this->input->post('dd_month'); 
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		if($this->input->post('btnSearch') == 'Search')
		{
			$dd_month = $this->input->post('dd_month'); 
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else if($this->input->post('type') == 'Upcoming')
		{
			$dd_month = $mm + 1;
			$dd_year = $yy;
			if($mm > 11)
			{
				$dd_month = '01';
				$dd_year = $yy + 1;
			}
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else
		{
			$dd_month = $mm;
			$dd_year = $yy;
			if($type == 'All' OR $type == 'Archive')
			{
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			else if($type == 'Upcoming'){
				$dd_month = $mm + 1;
				$dd_year = $yy;
				if($mm > 11)
				{
					$dd_month = '01';
					$dd_year = $yy + 1;
				}
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type);
		}
		$this->mViewData['dd_month'] = $dd_month;
		$this->mViewData['newsAndEventsMonth'] = $subTitle;
		//usort($newsAndEvents,'DateCmp');
		//Template view
		$this->render('news_and_events/news_and_events_view', 'full_width', $this->mViewData); 
	} 
	public function news_and_events()
	{
		$this->mViewData['pageTitle'] = 'news_and_events';
		//logic of news and events
		$type = $this->input->get('type');
		$showSearchBox = FALSE;
		if($type == 'Today')
		{
			$subheader='News &amp; Events Today';
			$this->mViewData['title']= 'News &amp; Events Today';
			$subTitle = 'today';
		}
		elseif($type == 'ThisWeek')
		{
			$subheader='News &amp; Events of this week';
			$this->mViewData['title'] = 'News &amp; Events This Week';
			$subTitle = 'this week';
		}
		elseif($type == 'ThisMonth')
		{
			$subheader='News &amp; Events of this month';
			$this->mViewData['title'] = 'News &amp; Events This Month';
			$subTitle = 'this month';
		}
		elseif($type == 'Archive')
		{
			$subheader='Archive';
			$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Archive News &amp; Events';
		}
		else
		{
			$type = 'All';
			$showSearchBox = TRUE;
			$subheader='All News &amp; Events';
			$this->mViewData['title'] = 'News &amp; Events';
		} 
		$mm = date("m");
		$yy = date("Y");
		
		if($this->input->post('btnSearch') == 'Search')
		{
			$dd_month = $this->input->post('dd_month');
			$this->mViewData['dd_month'] = $this->input->post('dd_month');
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else if($this->input->post('btnSearch') == 'Search')
		{
			$dd_month = $this->input->post('dd_month');
			$this->mViewData['dd_month'] = $this->input->post('dd_month');
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		elseif($this->input->post('type') == 'Upcoming')
		{
			$dd_month = $mm + 1;
			$dd_year = $yy;
			if($mm > 11)
			{
				$dd_month = '01';
				$dd_year = $yy + 1;
			}
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else
		{
			$dd_month = $mm;
			$dd_year = $yy;
			if($type == 'All' OR $type == 'Archive')
			{
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type);
		} 
		//@usort($newsAndEvents,'DateCmp');
		//Template view
		$this->render('news_and_events/news_and_events_view', 'full_width', $this->mViewData); 
	} 
	public function get_news_and_events($for = 'All', $month = '', $year = '')
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
			$currentDate = date("m-d");
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
			//$currentDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$currentDate = date("m-d");
			$date = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			//echo $lastDayMonth;
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		else if($for == 'Upcoming')
		{
			if($month == '' && $year == '')
			{
				$nextMonth = $mm + 1;
				$nextYear = $yy;
				if($mm > 11){
					$nextMonth = '01';
					$nextYear = $yy + 1;
				}
			}
			else{
				$nextMonth = $month;
				$nextYear = $year;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			//$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			//$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
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
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`dob`, i.`designation`, i.`gender`, d.`desg_name` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`";
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
			
			if($newsEventNum > 0){
				foreach($array as $newsEventInfo){
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
		
		if($newsEventNum > 0){
			foreach($arra as $newsEventInfo){
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
	//check birtday greetings
	public function submit_greetings_check(){
		$login_id = $this->input->post('user_id');
		$post_id = $this->input->post('loginID');
		$result = $this->event_model->wish_birthday_check($login_id,$post_id);
		echo json_encode($result);
	}
	
	//submit birtday greetings
	public function submit_greetings(){
		$login_id = $this->input->post('user_id');
		$post_id = $this->input->post('loginID');
		$message = $this->input->post('message');
		$this->event_model->wish_birthday($login_id,$post_id,$message);
	}
	
	public function fetch_events(){
		$date = $this->input->post('date');
		$result = $this->event_model->get_news_and_events_Day($date);
		echo json_encode($result, true); 
	}
	
	public function fetch_events_weekly(){
		$edate = $this->input->post('edate');
		$sdate = $this->input->post('sdate');
		$result = $this->event_model->get_news_and_events_Weekly($sdate,$edate);
		echo json_encode($result, true); 
	}
	
}
