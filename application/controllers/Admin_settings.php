<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_settings extends MY_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Admin_settings_model');
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
	
	public function hall_of_fame(){
		$this->mViewData['pageTitle'] = 'Hall of Fame';
		$employee = $this->Admin_settings_model->get_all_employee();
		$success = "";
		$error = "";
		if($this->input->get('id') != ""){
			$halloffame = $this->Admin_settings_model->get_all_hall_of_fame_id($this->input->get('id'));
		} else {
			$halloffame = array();
		}
		
		$this->mViewData['halloffame'] = $halloffame;
		
		if($this->input->post('Update') == 'Update'){
			$empID = $this->input->post('empID');
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$login_id = $this->session->userdata('user_id');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$id = $this->input->get('id');
			$order = $this->input->post('order');
			$result = $this->Admin_settings_model->update_fall_of_fame($empID,$title,$description,$login_id,$month,$year,$order,$id);
			$success = "Successfully Updated";
			if($_FILES['photo']['name'] != '' && $_FILES['photo']['size'] > 0)
			{
				if(($_FILES['photo']['name']) !=""){
					$path = $_FILES['photo']['name'];
					$filename = strtolower(str_replace(' ','',$empID."_fame_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION))); 
					$config['upload_path'] = APPPATH.'../assets/upload/hall_of_fame/';
					$config['allowed_types'] = 'jpg|png|jpeg';
					$config['file_name'] = $filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('photo')){
						$fileData = $this->upload->data();
						$result = $this->Admin_settings_model->update_fall_of_fame_img($filename,$id);
						$success = "Successfully Updated";
					} else {
						$error = "Something went Wrong";
					}
				}
			} 
		}
		
		if($this->input->post('Submit') == 'Submit'){
			$empID = $this->input->post('empID');
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$login_id = $this->session->userdata('user_id');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$order = $this->input->post('order');
			if($_FILES['photo']['name'] != '' && $_FILES['photo']['size'] > 0)
			{
				if(($_FILES['photo']['name']) !=""){
					$path = $_FILES['photo']['name'];
					$filename = strtolower(str_replace(' ','',$empID."_fame_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION))); 
					$config['upload_path'] = APPPATH.'../assets/upload/hall_of_fame/';
					$config['allowed_types'] = 'jpg|png|jpeg';
					$config['file_name'] = $filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('photo')){
						$fileData = $this->upload->data();
						$result = $this->Admin_settings_model->submit_fall_of_fame($empID,$title,$description,$login_id,$filename,$month,$year,$order);
						$success = "Successfully Submitted";
					} else {
						$error = "Something went Wrong";
					}
				}
			} else {
				$error = "Please Upload Images";
			} 
		}
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->mViewData['employee'] = $employee;
		$this->render('admin_settings/hall_of_fame_view', 'full_width', $this->mViewData);
	}
	
	public function hall_of_fame_list(){
		$this->mViewData['pageTitle'] = 'Hall of Fame';
		$monthh = "";
		$yearr = "";
		$result = "";
		if($this->input->post('btnSearch') == "Search" ){
			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$this->mViewData['yearr'] = $year;
			$this->mViewData['monthh'] = $month;
			$result = $this->Admin_settings_model->get_all_hall_of_fame_search($year,$month);
		} else {
			$last_monthSQL = $this->db->query('SELECT month FROM hall_of_fame ORDER by month DESC LIMIT 1');
			$last_month = $last_monthSQL->row_array();
			$last_yearSQL = $this->db->query('SELECT year FROM hall_of_fame ORDER by month DESC LIMIT 1');
			$last_year = $last_yearSQL->row_array(); 
			$this->mViewData['yearr'] = $last_year['year'];
			$this->mViewData['monthh'] = $last_month['month'];
			$result = $this->Admin_settings_model->get_all_hall_of_fame($last_month['month'],$last_year['year']);
		}
		
		$this->mViewData['famess'] = $result;
		$this->render('admin_settings/hall_of_fame_list_view', 'full_width', $this->mViewData);
	}
	
}