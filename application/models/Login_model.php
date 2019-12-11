<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login_model class.
 * 
 * @extends CI_Model
 */
class Login_model extends CI_Model {

	/**
	 * __construct function. 
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	} 
	/**
	 * resolve_user_login function. 
	*/
	public function get_login_id($txt_email) 
	{
		//$sql = "SELECT login_id, password FROM internal_user WHERE user_status = '1' AND loginhandle = '$txt_email' AND (loginhandle = 'PTPL-10010' OR loginhandle = 'PTPL-10010' OR loginhandle = 'PTPL-10010' OR loginhandle = 'administrator' )";
		$sql = "SELECT login_id, password FROM internal_user WHERE user_status = '1' AND loginhandle = '$txt_email'";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	}
	public function get_block_login_id($txt_email) 
	{
		$sql = "SELECT login_id FROM internal_user WHERE user_status = '3' AND loginhandle = '$txt_email'";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	} 
	 
	/**
	 * get_user function. 
	*/
	public function get_user($login_id) 
	{
		$sql = $this->db->query("SELECT i.login_id, i.user_photo_name, i.branch, i.loginhandle, i.remote_access, i.name_first, i.department, d.dept_name, i.designation, u.desg_name, i.user_role, i.emp_type, r.user_role_name, c.user_group, i.is_assistant_admin, i.is_manager, i.is_supervisor,i.user_type, c.parent_id FROM `internal_user` i INNER JOIN `compass_user` c ON i.login_id = c.ref_id LEFT JOIN `user_desg` u ON u.desg_id = i.designation LEFT JOIN `department` d ON d.dept_id = i.department LEFT JOIN `user_role` r ON r.user_role_id = i.user_role WHERE i.user_status = '1' AND i.login_id = '".$login_id."'"); 
		return $sql->result_array(); 
	}
	
	public function check_isAReportingManager($login_id) 
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.login_id = '".$login_id."' AND i.user_role < '5' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		return $reportingInfoSelect;
	}
	
	public function check_isAReviewer($login_id) 
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.login_id = '".$login_id."' AND i.user_role < '5' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		return $reportingInfoSelect;
	}
	
	public function check_isDepartmentHead($login_id) 
	{
		$reportingQrySelect = "SELECT d.login_id FROM `department` d WHERE d.login_id = '".$login_id."'";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		return $reportingInfoSelect;
	}
}
