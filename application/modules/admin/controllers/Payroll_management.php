<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_management extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function allowance_deduction_list()
	{
		$crud = $this->generate_crud('allowance_deduction');
		//$crud = $this->set_relation('login_id','internal_user');
		//$crud->set_relation_n_n('login_id','leave_carry_ forward'); 
		//$crud->set_relation('user_id','leave_carry_forward'); 
		$this->render_crud();
	}
	public function generate_salary()
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
	public function salary_slip()
	{
		$crud = $this->generate_crud('leave_carry_forward'); 
        $crud->set_subject('leave application');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function mail_salary_slip()
	{
		$crud = $this->generate_crud('leave_credited_history'); 
        $crud->set_subject('leave credited history');
		$this->render_crud();
	}
	public function payroll_report()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function increment_report()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
	public function epf_report()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
	public function esi_report()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
	public function ctc_graph()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
	public function incentive()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
	public function generate_incentive()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}  
}