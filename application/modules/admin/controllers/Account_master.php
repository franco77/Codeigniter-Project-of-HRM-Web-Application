<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_master extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function define_pt_slab()
	{
		$crud = $this->generate_crud('pt_slab_master'); 
		$crud->field_type('status','dropdown',array('Y' => 'Yes','N'=> 'No')); 		
		$this->render_crud();
	}
	public function define_income_tax_slab()
	{
		$crud = $this->generate_crud('it_slab_master');
		$crud->field_type('status','dropdown',array('Y' => 'Yes','N'=> 'No'));
		$this->render_crud();
	}
	public function tax_deduction_limit()
	{
		$crud = $this->generate_crud('income_tax_limit'); 
        //$crud->set_subject('leave application');
		//$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
}
