<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends Admin_Controller {

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
	public function master_paroll_access()
    {
		$crud = $this->generate_crud('master_paroll_access'); 
        $crud->set_subject('master paroll access');
		$crud->set_relation('emp_code','internal_user','{full_name} ({loginhandle})');
		$crud->display_as('emp_code','Employee Name');
		//$crud->set_relation('emp_code','internal_user','full_name',NULL,'full_name ASC');
        $crud->field_type('status','dropdown',array('1' => 'Active', '0' => 'Inactive')); 
        $this->render_crud(); 
    }
	public function country()
    {
		$crud = $this->generate_crud('country'); 
        $crud->set_subject('country'); 
        $crud->field_type('country_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->display_as('country_name','Country');
		$crud->display_as('country_sort_order','Order');
		$crud->display_as('	country_status','Status');
		$this->render_crud();
    }
	public function state()
    {
        $crud = $this->generate_crud('state'); 
        $crud->set_subject('state');
		$crud->set_relation('country_id','country','country_name',NULL,'country_name ASC');
        $crud->field_type('state_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->display_as('country_id','Country');
		$crud->display_as('state_name','State');
		$crud->display_as('state_sort_order','Order');
		$crud->display_as('state_status','Status');
        $this->render_crud();
    }
	public function location()
    {  
        $crud = $this->generate_crud('company_branch'); 
        $crud->set_subject('location'); 
        $crud->field_type('status','dropdown',array('A' => 'Active', 'I' => 'Inactive', 'D'=>''));
		$this->render_crud();
    }
	public function department()
    {
        $crud = $this->generate_crud('department'); 
        $crud->set_subject('department'); 
        $crud->field_type('dept_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->display_as('dept_name','Department Name');
		$crud->display_as('dept_status','Status');
		$crud->display_as('dept_sort_order','Order');
		$crud->display_as('login_id','Employee');
        $this->render_crud();
    }
	public function designation()
    {
        $crud = $this->generate_crud('user_desg'); 
        $crud->set_subject('designation'); 
        $crud->field_type('desg_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->set_relation('dept_id','department','dept_name',NULL,'dept_name ASC');
		$crud->set_relation('level_id','grade_mater','grade_name',NULL,'grade_name ASC');
		$crud->display_as('desg_name','Designation');
		$crud->display_as('dept_id','Department');
		$crud->display_as('desg_status','Status');
		$crud->display_as('desg_sort_order','Order');
		$crud->display_as('level_id','Level');
        $this->render_crud();
    }
	public function skills()
    {
        $crud = $this->generate_crud('skills_master');  
        $crud->set_subject('skills_master'); 
        $crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No')); 
        $this->render_crud();
    }
	public function grade()
    {
		$crud = $this->generate_crud('grade_mater'); 
        $crud->set_subject('grade'); 
        $crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->set_relation('level_id','level_master','level_name',NULL,'level_name ASC');
		$crud->display_as('level_id','Level');
        $this->render_crud();
    }
	public function level()
    {
		$crud = $this->generate_crud('level_master'); 
        $crud->set_subject('level'); 
        $crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No')); 
        $this->render_crud();
    }
	public function education()
    {
        $crud = $this->generate_crud('course_info'); 
        $crud->set_subject('education'); 
        $crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No')); 
        $this->render_crud();
    }
	public function specialization()
    {
        $crud = $this->generate_crud('specialization_master'); 
        $crud->set_subject('specialization'); 
        $crud->field_type('status','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->set_relation('course_id','course_info','course_name',NULL,'course_name ASC');		
        $this->render_crud();
    }
	public function board_university()
    {
        $crud = $this->generate_crud('board_university_master'); 
        $crud->set_subject('board_university'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function experience()
    {
        $crud = $this->generate_crud('experience_master'); 
        $crud->set_subject('experience'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function joining_kit()
    {
        $crud = $this->generate_crud('joining_kit_master'); 
        $crud->set_subject('joining kit master'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function requirement()
    {
        $crud = $this->generate_crud('joining_kit_master'); 
        $crud->set_table('country');
        $crud->set_subject('country'); 
        $crud->field_type('status','dropdown',array('1' => 'Active', '0' => 'Inactive')); 
        $this->render_crud();
    }
	public function define_hod()
    {
        $crud = $this->generate_crud('department'); 
        $crud->set_subject('department'); 
        $crud->field_type('dept_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->display_as('dept_name','Department Name');
		$crud->display_as('login_id','Department Head');
		$crud->display_as('dept_status','Status');
		$crud->display_as('dept_sort_order','Order');
        $this->render_crud();
    }
	public function reason_of_separation()
    {
        $crud = $this->generate_crud('separation_master'); 
        $crud->set_subject('reason of separation'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function source_of_hire()
    {
        $crud = $this->generate_crud('source_hire_master'); 
        $crud->set_subject('source from Hire'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function bank()
    {
        $crud = $this->generate_crud('bank_master'); 
        $crud->set_subject('Bank'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function define_miscellaneous()
    {
        $crud = $this->generate_crud('miscellaneous_mater'); 
        $crud->set_subject('miscellaneous'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    }
	public function email_template_master()
    {
        $crud = $this->generate_crud('email_templates'); 
        $crud->set_subject('email templates'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive'));
		//$crud->set_relation('emp_code','internal_user','full_name',NULL,'full_name ASC');
		$crud->set_relation('cid','email_category','cname',NULL,'cname ASC');
        $this->render_crud();
    }
	public function email_category()
    {
        $crud = $this->generate_crud('email_category'); 
        $crud->set_subject('email category'); 
        $crud->field_type('cstatus','dropdown',array('1' => 'Active', '0' => 'Inactive')); 
        $this->render_crud();
    }
	// end hr module
}
