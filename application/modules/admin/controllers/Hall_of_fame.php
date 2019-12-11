<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hall of fame management, includes: 
 */
class Hall_of_fame extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	}

	// Admin Users CRUD
	public function get_hall_of_fame()
	{
		$crud = $this->generate_crud('hall_of_fame'); 
        $crud->set_subject('Hall Of Fame');
		$crud->set_relation('user_id','internal_user','{full_name} ({loginhandle})');
		$crud->display_as('user_id','Employee Name');
		$crud->display_as('h_order','Order');
		$crud->set_field_upload('image','assets/upload/halloffame');
		//$crud->set_relation('emp_code','internal_user','full_name',NULL,'full_name ASC');
        $crud->field_type('status','dropdown',array('1' => 'Active', '0' => 'Inactive')); 
        $this->render_crud();
	} 
}
