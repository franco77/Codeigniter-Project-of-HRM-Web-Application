<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_entry extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function biometric_entry()
	{
		//$crud = $this->generate_crud('internal_user');
		//$crud->set_relation_n_n('login_id','leave_carry_ forward'); 
		//$crud->set_relation('user_id','leave_carry_forward');
		$form = $this->form_builder->create_form();
		$this->mPageTitle = 'Biometric Data Upload';

		$this->mViewData['form'] = $form;
		$this->render('attendace/biometric_entry');
	}
	public function lwh_report()
	{
		$crud = $this->generate_crud('attendance_new');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->order_by('date','desc');
		$crud->display_as('login_id','Employee Name');
		$crud->display_as('att_status','Attendance Status');
		$crud->field_type('att_status','dropdown',array('P' => 'Present','R'=> 'Regulisation','H' => 'Half Day','L'=>'Leave','W' => 'Low Working Day')); 
		$crud->field_type('leave_type','dropdown',array('P' => 'Planned Leave','S'=> 'Sick Leave'));
		$crud->unset_read();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$this->render_crud();
	}
	public function employee_attendance_summary()
	{
		$crud = $this->generate_crud('leave_carry_forward'); 
        $crud->set_subject('Employee Attendance Summary'); 
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
}
