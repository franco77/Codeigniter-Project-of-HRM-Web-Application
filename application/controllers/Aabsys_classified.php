<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aabsys_classified extends MY_Controller 
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
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Aabsys_classified_model');
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
		//$this->load_view();
	} 
	public function classified()
	{
		$this->mViewData['pageTitle']    = 'Classified';
		$classifiedQry = "SELECT c.*, i.name_first, i.name_last, i.mobile FROM `classified` c INNER JOIN `internal_user` i ON i.login_id = c.posted_by WHERE `n_disp_flag` = 1 ORDER BY `ix_classified` DESC";
		$classifiedRes = $this->db->query($classifiedQry);
		//$this->mViewData['classifiedNum'] = count($classifiedRes);
		$this->mViewData['classifiedInfo'] = $classifiedRes->result_array(); 
		
		//Template view
		$this->render('aabsys_classified/classified_view', 'full_width', $this->mViewData); 
	}
	public function classified_view()
	{
		$this->mViewData['pageTitle']    = 'Indivisual classified view';
		
		//popup logic 
		$id = $this->input->get('id');
		$classifiedQry_popup = "SELECT c.ix_classified, c.classified_header, c.classified_body, c.classified_file, c.posted_by, i.name_first, i.name_last, i.mobile FROM `classified` c INNER JOIN `internal_user` i ON i.login_id = c.posted_by WHERE ix_classified = '".$id."'";
		$classifiedRes = $this->db->query($classifiedQry_popup);
		$this->mViewData['classified_Info'] = $classifiedRes->row_array();
		//var_dump($this->mViewData['classified_Info']);
		//Template view
		$this->render('aabsys_classified/item_classified_view', 'full_width', $this->mViewData); 
	} 
	public function fetch_classified_details()
	{
		//popup logic 
		$id = $this->input->post('ix_classified');
		$classifiedQry_popup = "SELECT c.ix_classified, c.classified_header, c.classified_body, c.classified_file, c.posted_by, i.name_first, i.name_last, i.mobile FROM `classified` c INNER JOIN `internal_user` i ON i.login_id = c.posted_by WHERE ix_classified = '".$id."'";
		$classifiedRes = $this->db->query($classifiedQry_popup);
		$classified_Info = $classifiedRes->row_array();
		echo json_encode($classified_Info);
	} 
	
	public function my_classified()
	{
		$user_id = $this->session->userdata('user_id');
		$this->mViewData['pageTitle']    = 'My classified';
		
		$this->mViewData['myClassifiedRes'] = $this->Aabsys_classified_model->get_my_classified($user_id);
		
		//Template view
		$this->render('aabsys_classified/my_classified_view', 'full_width', $this->mViewData); 
	} 
	
	
	
	public function submit_my_classified()
	{
		$user_id = $this->session->userdata('user_id');
		if($this->input->post('btnClassifiedPost') == 'POST')
		{
			$classifiedHeader = $this->input->post('classifiedHeader');
			$classifiedDesc = $this->input->post('classifiedDesc');
			if($classifiedHeader !="" && $classifiedDesc !=""){
				$file = '';
				if(($_FILES['txtClassifiedFile']['name']) !=""){
					$path = $_FILES['txtClassifiedFile']['name'];
					$filename = time().'-classified.'.pathinfo($path, PATHINFO_EXTENSION);
					//echo $filename;exit;
					$config['upload_path'] = APPPATH.'../assets/upload/classified/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					chmod($config['upload_path'], 777);
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('txtClassifiedFile')){
						$fileData = $this->upload->data();
						$file = $filename;
					}
					else
					{
						$error = array('error' => $this->upload->display_errors());
						/* print_r($error);
						print_r($this->upload->data());
						exit; */
					}
				}
				$insert = $this->Aabsys_classified_model->insert_my_classified($classifiedHeader, $classifiedDesc, $file,$user_id);
			}
		}
		redirect('/aabsys_classified/my_classified'); 
	} 
}
