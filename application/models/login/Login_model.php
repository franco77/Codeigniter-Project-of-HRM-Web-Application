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
	public function resolve_user_login($username, $password) 
	{
		// $sql = $this->db->query("SELECT login_id, loginhandle, password FROM internal_user WHERE user_status = '1' AND loginhandle = '".$username."' AND password='".$password."'");
		// return $sql->result_array();
		$this->db->select('password');
		$this->db->from('internal_user');
		$this->db->where('loginhandle', $username); 
		$hash = $this->db->get()->row('password');
		return $this->verify_password_hash($password, $hash);
		
		
	}
	
	/**
	 * get_user_id_from_username function. 
	*/
	public function get_user_id_from_username($username)
	{
		
		$this->db->select('login_id');
		$this->db->from('internal_user');
		$this->db->where('loginhandle', $username); 
		return $this->db->get()->row('login_id'); 
	}
	
	/**
	 * get_user function. 
	*/
	public function get_user($login_id) 
	{
		$sql = $this->db->query("SELECT i.login_id, i.branch, i.loginhandle, i.remote_access, i.name_first, i.department, d.dept_name, i.designation, u.desg_name, i.user_role, r.user_role_name, c.user_group, i.is_assistant_admin, i.is_manager, i.is_supervisor,i.user_type, c.parent_id FROM `internal_user` i INNER JOIN `compass_user` c ON i.login_id = c.ref_id LEFT JOIN `user_desg` u ON u.desg_id = i.designation LEFT JOIN `department` d ON d.dept_id = i.department LEFT JOIN `user_role` r ON r.user_role_id = i.user_role WHERE i.user_status = '1' AND i.login_id = '".$login_id."'"); 
		return $sql->row(); 
	}
	
	private function verify_password_hash($password, $hash)
	{
		return password_verify($password, $hash);
	} 
	/**
	* forgot password 
	*/
	private function get_password($email)
	{
		$this->db->select('email','password');
		$this->db->from('users');
		$this->db->where('email',$email);
		return $this->db->get()->row('email');
	}
	
}
