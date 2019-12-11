<?php
class Admin_model  extends CI_Model  
{
	function __construct()
	{
		parent::__construct();  
	} 
	public function resolve_user_login($username, $password) 
	{
		
		$sql = "SELECT * FROM internal_user WHERE loginhandle = '$username' AND password = md5('$password')";
		//$this->db->query($sql,array($username,$password))->result_array(); 
		return $this->db->query($sql,array($username,$password))->result_array(); 
	} 
	public function get_login_id_from_username($username) {
		
		$this->db->select('login_id');
		$this->db->from('internal_user');
		$this->db->where('loginhandle', $username);

		return $this->db->get()->row('login_id');
		
	} 
	public function get_user($login_id) {
		
		// $this->db->from('internal_user');
		// $this->db->where('login_id', $user_id);
		// return $this->db->get()->row();
		$sql = $this->db->query("SELECT i.login_id, i.branch, i.loginhandle, i.remote_access, i.name_first, i.department, d.dept_name, i.designation, u.desg_name, i.user_role, r.user_role_name, c.user_group, i.is_assistant_admin, i.is_manager, i.is_supervisor,i.user_type, c.parent_id FROM `internal_user` i INNER JOIN `compass_user` c ON i.login_id = c.ref_id LEFT JOIN `user_desg` u ON u.desg_id = i.designation LEFT JOIN `department` d ON d.dept_id = i.department LEFT JOIN `user_role` r ON r.user_role_id = i.user_role WHERE i.user_status = '1' AND i.login_id = '".$login_id."'"); 
		return $sql->row();
		
	}
	 
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	private function verify_admin_password_hash($password, $hash) {
		
		return password_verify($password, $hash); 
	} 
	private function get_password($email)
	{
		$this->db->select('email','password');
		$this->db->from('admin_tokens');
		$this->db->where('email',$email);
		return $this->db->get()->row('email');
	}  
	public function error_message($msg)
	{
		return '<div class="alert alert-danger">'.$msg.'</div>';
	}
	public function success_message($msg)
	{
		return '<div class="alert alert-success">'.$msg.'</div>';
	} 
}
?>