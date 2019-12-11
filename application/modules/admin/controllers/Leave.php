<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	} 
	public function leave_provision()
	{
		$crud = $this->generate_crud('internal_user');
		//$crud->set_relation_n_n('login_id','leave_carry_ forward'); 
		//$crud->set_relation('user_id','leave_carry_forward'); 
		$this->render_crud();
	}
	public function employee_leave_info()
	{
		$crud = $this->generate_crud('leave_carry_forward');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC'); 
		$crud->display_as('user_id','Employee Name');
		$crud->display_as('ob_pl','Personal Leave');
		$crud->display_as('ob_sl','Sick Leave');
		$crud->display_as('cf_pl','Carry Forword PL');
		$crud->order_by('year','desc');
		$crud->unset_read();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$this->render_crud();
	}
	public function leave_status_info()
	{
		$crud = $this->generate_crud('leave_application'); 
        $crud->set_subject('leave application'); 
		$crud->display_as('user_id','Employee Name');
		$crud->display_as('rp_mgr_id','Reporing Manager');
		$crud->display_as('leave_type','Leave Type');
		$crud->display_as('leave_from','Leave From');
		$crud->display_as('leavefromhalfday','Leave From Half Day');
		$crud->display_as('leave_to','Leave To');
		$crud->display_as('leavetohalfday','Leave To Half Day');
		$crud->display_as('absence_reason','Absence Reason');
		$crud->display_as('contact_details','Contact Details');
		$crud->display_as('reject_reason','Reject Reason');
		$crud->display_as('action_dt','Apply Date');
		$crud->display_as('app_dt','Approv Date');
		//$crud->display_as('w_c_dt','w_c_dt');
		//$crud->display_as('w_c_reason','w_c_reason');
		$crud->order_by('leave_from','desc');
		$crud->order_by('leave_to','desc');
		$crud->order_by('action_dt','desc');
		$crud->order_by('app_dt','desc');
		$crud->set_relation('user_id','internal_user','full_name',NULL,'full_name ASC');
		$crud->field_type('leave_type','dropdown',array('P' => 'Personal Leave','S'=> 'Sick Leave'));
		$crud->field_type('leavefromhalfday','dropdown',array('Y' => 'Yes','N'=> 'No'));
		$crud->field_type('leavetohalfday','dropdown',array('Y' => 'Yes','N'=> 'No'));
		$crud->field_type('status','dropdown',array('A' => 'Approved','R'=> 'Rejected','P' => 'Pending','W'=> 'Withdraw','CA' => 'Cancel Approved','CR'=> 'Cancel Rejected','CP' => 'Cancel Pending'));
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_read();
		$this->render_crud();
	}
	public function late_comming_info()
	{
		$crud = $this->generate_crud('leave_credited_history'); 
        $crud->set_subject('leave credited history');
		$this->render_crud();
	}
	public function absent_info()
	{
		$crud = $this->generate_crud('leave_info');
		$crud->set_subject('leave info');
		$crud->set_relation('login_id','internal_user','full_name',NULL,'full_name ASC'); 
		$this->render_crud();
	} 
}
