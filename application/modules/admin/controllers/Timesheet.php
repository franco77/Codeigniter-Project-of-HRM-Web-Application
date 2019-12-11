<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheet extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	}

	// Frontend User CRUD
	public function index()
	{
		$crud = $this->generate_crud('attendance_new');
		$crud->set_subject('Attendance');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC');
		
		$this->render_crud();
	}
	public function regularise_request()
	{
		$crud = $this->generate_crud('attendance_request');
		$crud->set_subject('Attendance Request');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->set_relation('rm_id','internal_user','full_name',NULL,'full_name ASC');
        $crud->field_type('type','dropdown',array('R' => 'Regularization', 'L' => 'Leave'));
		$crud->field_type('status','dropdown',array('P' => 'Pending', 'A' => 'Approved', 'R'=>'Rejected'));
		
		$this->render_crud();
	}
	public function leave_application()
	{
		$crud = $this->generate_crud('leave_application');
		$crud->set_subject('leave application');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->set_relation('rp_mgr_id','internal_user','full_name',NULL,'full_name ASC');
        $crud->field_type('leave_type','dropdown',array('P' => 'Planned Leave', 'S' => 'Sick Leave'));
		$crud->field_type('leavefromhalfday','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->field_type('leavetohalfday','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->field_type('status','dropdown',array('A' => 'Absent','R'=> 'Regulisation','P' => 'Present','W' => 'Low Working Day','CP' => '','CR' => '','CA'=>'')); 
		$this->render_crud();
	}
	public function leave_carry_forward()
	{
		$crud = $this->generate_crud('leave_carry_forward'); 
        $crud->set_subject('leave application');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function leave_credited_history()
	{
		$crud = $this->generate_crud('leave_credited_history'); 
        $crud->set_subject('leave credited history');
		$this->render_crud();
	}
	public function leave_info()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function leave_type()
	{
		$crud = $this->generate_crud('leave_type');
		$crud->set_subject('leave type'); 
		$this->render_crud();
	}
	public function leave_master()
	{
		$crud = $this->generate_crud('level_master');
		$crud->set_subject('level master'); 
		$crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$this->render_crud();
	} 
}
