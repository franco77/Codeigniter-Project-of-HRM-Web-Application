<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Resources extends MY_Controller 
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
		
		$this->load->model('Resource_model');
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
	}

	public function index()
	{
		$this->mViewData['pageTitle']    = 'Resources';
		$this->render('resources/general_resources_view', 'full_width',$this->mViewData); 
		$this->load->view('script/resources/get_aabsys_info_script'); 
	} 
	public function general_resources()
	{
		$this->mViewData['pageTitle']    = 'General resources';
		$this->render('resources/general_resources_view', 'full_width',$this->mViewData);
		$this->load->view('script/resources/get_aabsys_info_script');
	}
	public function photo_gallery()
	{
		$this->mViewData['pageTitle']    = 'Photo gallery';
		$this->mViewData['get_photo'] = $this->Resource_model->get_all_photo_gallery();  
		$this->render('resources/photo_gallery_view', 'full_width',$this->mViewData); 
	}
	public function phone_directory()
	{
		$this->mViewData['pageTitle']    = 'Phone directory';
		$this->render('resources/phone_directory_view', 'full_width',$this->mViewData); 
		$this->load->view('script/resources/phone_directory_script');
	}
	public function official_holidays()
	{
		$this->mViewData['pageTitle']    = 'Official holidays';
		$this->render('resources/official_holidays_view', 'full_width',$this->mViewData); 
		$this->load->view('script/resources/official_holidays_script');
	} 
	public function cricket_team()
	{
		$this->mViewData['pageTitle']    = 'Cricket Team';
		$choose_year = $this->input->post('choose_year');
		if (!$choose_year) $choose_year = date("Y");
		$this->mViewData['no_of_player'] = $this->Resource_model->get_cricket_team($choose_year); 
		$this->render('resources/cricket_team_view', 'full_width',$this->mViewData); 
	}
	/*Start Ajax with angularjs function*/ 
	public function get_aabsys_info()
	{  
		$result = $this->Resource_model->get_aabsys_info(); 
		echo json_encode($result); 
	}
	public function get_staff_format_rules()
	{  
		$result = $this->Resource_model->get_staff_format_rules(); 
		echo json_encode($result); 
	}
	public function get_guidelines()
	{  
		$result = $this->Resource_model->get_guidelines(); 
		echo json_encode($result); 
	} 
	/*End*/
	/*Start Ajax with angularjs function*/
	public function get_official_holidays()
	{
		$choose_year = $this->input->post('choose_year');
		if (!$choose_year) $choose_year = date("Y");
		$result = $this->Resource_model->get_resource($choose_year); 
		echo json_encode($result); 
	}
	/*End*/
	/*Start Ajax with angularjs function*/
	public function get_phone_directory()
	{
		$result = $this->Resource_model->get_all_phone_no(); 
		echo json_encode($result); 
	}
	/*End */
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
}
