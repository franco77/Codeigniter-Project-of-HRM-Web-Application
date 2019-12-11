<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_information_portal_module extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function directors_message()
	{
		$crud = $this->generate_crud('internal_user');
		//$crud->set_relation_n_n('login_id','leave_carry_ forward'); 
		//$crud->set_relation('user_id','leave_carry_forward'); 
		$this->render_crud();
	}
	public function hr_policies()
	{
		$crud = $this->generate_crud('hr_policies');
		$crud->set_subject('leave application');
		$this->render_crud();
	}
	public function list_of_holidays()
	{
		$crud = $this->generate_crud('declared_leave'); 
        $crud->set_subject('Declared Leave'); 
		$this->render_crud();
	}
	public function in_house_monthly_magazine()
	{
		$crud = $this->generate_crud('company_telephone_directory'); 
        //$crud->set_subject('leave credited history');
		$this->render_crud();
	}
	public function list_of_contact_details()
	{
		$crud = $this->generate_crud('company_telephone_directory'); 
		$this->render_crud();
	}
	public function list_of_offices()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
	public function news_circulars()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	}
}
