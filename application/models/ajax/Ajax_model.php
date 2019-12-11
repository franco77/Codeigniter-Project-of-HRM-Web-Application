<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Ajax_model extends CI_Model
{ 
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	} 
	/**
	 * resolve_user_login function. 
	*/
	public function add_task_more() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	
	/**
	 * get_user_id_from_username function. 
	*/
	public function add_to_team() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function ajax_login() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function annual_check_pin() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function annual_remark() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function assign_task() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function attendance_reg_post() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_course() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_coursebyid() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_password() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_reporting() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_specialization() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function change_specializationbyid() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_avl_leave() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_client_code() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_date() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_leave_date() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_old_password() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function check_project_name() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function delete_from_team() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function delete_task() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function email_history_remark() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function get_email_template() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function get_proj_detail() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getDesignation() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getEmpDesignation() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getEmpGrade() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getEmpSearchDesignation() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getform() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getGrade() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getMember() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getmember_vendor() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getMgrTeam() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getOrderName() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getQcTeam() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getSpecializationGrad() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function getSpecializationProf() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function goal_a_r() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function leave_request_action() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function loan_advance_a_r() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function log_time_production() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function midyear_check_pin() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function midyear_remark() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function move_member() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function multiple_assign_task_subtask() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function multiple_assignment_content() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function multiple_rename_task_subtask() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function order_management_content() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function post_feedback() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function project_team_content() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function punch_log_time() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function punch_logout_time() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function reg_request_action() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function rename_task() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function reprocess_regularize() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function resignation_a_r() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function show_direct_leave_app_emp() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function show_emp_name() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function show_phone_directory() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function split_task() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function task_management_content() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function update_child_emp() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function update_edu_emp() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function update_exp_emp($username, $password) 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function update_sal_emp() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	}
	public function update_shift() 
	{
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	} 
	
}
