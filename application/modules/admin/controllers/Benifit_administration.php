<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benifit_administration extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function performance_benifit()
	{
		$crud = $this->generate_crud('performance_slab_master');
		$crud->display_as('pi_value','Point Value');
		$this->render_crud();
	}
	public function attendance_benifit()
	{
		$crud = $this->generate_crud('performance_slab_master');
		$crud->display_as('pi_value','Point Value');
		$this->render_crud(); 
	}
	public function add_extra_hrs()
	{
		$crud = $this->generate_crud('performance_slab_master');
		$crud->display_as('pi_value','Point Value');
		$this->render_crud();
	} 
}
