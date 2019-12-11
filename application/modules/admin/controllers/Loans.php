<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loans extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function loan_advance()
	{
		$crud = $this->generate_crud('internal_user');
		//$crud->set_relation_n_n('login_id','leave_carry_ forward'); 
		//$crud->set_relation('user_id','leave_carry_forward'); 
		$this->render_crud();
	}
	public function loan_applied()
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
	public function advance_applied()
	{
		$crud = $this->generate_crud('leave_carry_forward'); 
        $crud->set_subject('leave application');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function loan_advance_report()
	{
		$crud = $this->generate_crud('leave_credited_history'); 
        $crud->set_subject('leave credited history');
		$this->render_crud();
	} 
}
