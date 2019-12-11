<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_management extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function profile_listing()
    {
		$crud = $this->generate_crud('internal_user'); 
        $crud->set_subject('Employee');
		$crud->field_type('gender','dropdown',array('M' => 'Male', 'F' => 'Female'));
		$crud->field_type('marital_status','dropdown',array('S' => 'Single', 'M' => 'Married'));
		$crud->field_type('isShowOnSearch','dropdown',array('Y' => 'Yes', 'N' => 'No'));
        $crud->field_type('is_manager','dropdown',array('1' => 'Yes', '0' => 'No'));
		$crud->field_type('is_supervisor','dropdown',array('1' => 'Yes', '0' => 'No'));
		$crud->field_type('user_status','dropdown',array('0' => 'Active', '1' => 'Inactive','2' => 'Active', '3' => 'Inactive'));
		$crud->field_type('is_hr_admin','dropdown',array('1' => 'Yes', '0' => 'No'));
		$crud->field_type('ESI','dropdown',array('yes' => 'Yes', 'no' => 'No'));
		//$crud->field_type('employee_type','dropdown',array('permanent' => 'Permanent', 'non-permanent' => 'Non-permanent'));
		$crud->field_type('isAttndAllowance','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->field_type('isPerfomAllowance','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->field_type('emp_type','dropdown',array('F' => 'Full Time', 'C' => 'Contractual','I'=>'Intern'));
		$crud->field_type('shift','dropdown',array('GS' => 'General Shift', 'MS' => 'Morning Shift','ES' => 'Evening Shift', 'NS' => 'Night Shift'));
		$crud->field_type('remote_access','dropdown',array('Y' => 'Yes', 'N' => 'No'));
		$crud->field_type('is_assistant_admin','dropdown',array('1' => 'Yes', '0' => 'No')); 
		$crud->display_as('per_email','Personal Email');
		$crud->display_as('loginhandle','Employee Code');
		$crud->display_as('name_first','First Name');
		$crud->display_as('name_middle','Middle Name');
		$crud->display_as('name_last','Last Name');
		$crud->display_as('name_abbr','Abbrivation');
		$crud->display_as('father_name','Father Name');
		$crud->display_as('marital_status','Marital Status');
		$crud->display_as('join_date','Joining Date');
		$crud->display_as('passport_no','Passport No');
		$crud->display_as('pan_card_no','Pan Card No');
		$crud->display_as('voter_id','Voter Id');
		$crud->display_as('drl_no','Driving Lisence');
		$crud->display_as('address1','Permanent Address');
		$crud->display_as('address2','Correspondence Address');
		$crud->display_as('highest_qual','Highest Qualification');
		$crud->display_as('loc_highest_qual','Passed Out from');
		$crud->display_as('city_district1','City');
		$crud->display_as('city_district2','Correspondence City');
		$crud->set_relation('state_region1','state','state_name',NULL,'state_name ASC');
		$crud->set_relation('state_region2','state','state_name',NULL,'state_name ASC');
		$crud->set_relation('country1','country','country_name',NULL,'country_name ASC');
		$crud->set_relation('country2','country','country_name',NULL,'country_name ASC');
		$crud->set_relation('branch','company_branch','branch_name',NULL,'branch_name ASC');
		$crud->set_relation('designation','user_desg','desg_name',NULL,'desg_name ASC');
		$crud->set_relation('department','department','dept_name',NULL,'dept_name ASC');
		$crud->set_relation('grade','grade_mater','grade_name',NULL,'grade_name ASC');
		$crud->set_relation('level','level_master','level_name',NULL,'level_name ASC');
		$crud->set_relation('highest_qual','course_info','course_name',NULL,'course_name ASC');
		$crud->set_relation('loc_highest_qual','state','state_name',NULL,'state_name ASC');
		//$x=$crud->set_relation('reporting_to','internal_user','full_name',NULL,'full_name ASC');
		//var_dump($x);exit;
		//$crud->set_field_upload('user_photo_name','assets/uploads/profile');
		//$crud->set_field_upload('user_sign_name','assets/uploads/profile'); 
        $this->render_crud(); 
    }
	public function create_new_profile()
    {
		$crud = $this->generate_crud('internal_user'); 
        $crud->set_subject('Create New Employee'); 
        $crud->field_type('country_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->display_as('country_name','Country');
		$crud->display_as('country_sort_order','Order');
		$crud->display_as('	country_status','Status');
		$this->render_crud();
    }
	public function reset_password()
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
	public function employee_vintage()
    {  
        $crud = $this->generate_crud('company_branch'); 
        $crud->set_subject('location'); 
        $crud->field_type('status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$this->render_crud();
    }
	public function import_data()
    {
        $crud = $this->generate_crud('department'); 
        $crud->set_subject('department'); 
        $crud->field_type('dept_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->display_as('dept_name','Department');
		$crud->display_as('dept_status','Status');
		$crud->display_as('dept_sort_order','Order');
		$crud->display_as('login_id','Employee');
        $this->render_crud();
    }
	public function report()
    {
        $crud = $this->generate_crud('user_desg'); 
        $crud->set_subject('designation'); 
        $crud->field_type('desg_status','dropdown',array('1' => 'Active', '0' => 'Inactive'));
		$crud->set_relation('dept_id','department','dept_name',NULL,'dept_name ASC');
		$crud->display_as('desg_name','Designation');
		$crud->display_as('dept_id','Department');
		$crud->display_as('desg_status','Status');
		$crud->display_as('desg_sort_order','Order');
        $this->render_crud();
    }
	public function download_resume()
    {
        $crud = $this->generate_crud('skills_master');  
        $crud->set_subject('skills_master'); 
        $crud->field_type('status','dropdown',array('Y' => 'Active', 'N' => 'Inactive')); 
        $this->render_crud();
    } 
}
