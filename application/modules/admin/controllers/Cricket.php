<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cricket extends Admin_Controller {

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
	public function cricket_all()
    {
		$crud = $this->generate_crud('cricket_team'); 
        $crud->set_subject('Cricket');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->set_relation('position','cricket_team','position',NULL,'position ASC');
        
		$this->render_crud();
    } 
}
