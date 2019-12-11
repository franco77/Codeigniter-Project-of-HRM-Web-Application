<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Loan_model extends CI_Model {

	/**
	 * __construct function. 
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	} 
	public function get_loan() 
	{
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, l.rm_status,l.dh_status,l.hr_status,l.ac_status FROM `internal_user` i RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' AND l.applyfor = 'loan'";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	}
	public function get_advance_aaplied() 
	{
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, l.rm_status,l.dh_status,l.hr_status,l.ac_status FROM `internal_user` i RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' AND l.applyfor = 'advance'";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	} 
	 
	/**
	 * get_user function. 
	*/
	public function get_loan_advance_approve_reject() 
	{
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.*,AES_DECRYPT(s.gross_salary, '".$this->config->item('masterKey')."') AS gross_salary,  
		CASE
			WHEN (l.applyfor = 'advance')    THEN round(((AES_DECRYPT(s.gross_salary, '".$this->config->item('masterKey')."'))*(l.eligibleamount))/100)
			WHEN (l.applyfor = 'loan')    THEN round((AES_DECRYPT(s.gross_salary, '".$this->config->item('masterKey')."'))*(l.eligibleamount))
			END AS eligibilityAMount
		FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' ORDER BY `l`.`created_date` DESC"); 
		return $sql->result_array(); 
	}
	
	public function get_loan_advance_approve_reject_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$cond = "i.login_id != '10010'";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."department = '".$searchDepartment."'";
		}
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = "full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = "designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = "loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, i.full_name, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle,l.lid,l.applyfor, l.rm_status,l.dh_status,l.hr_status,l.ac_status FROM `internal_user` i RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND $cond  ORDER BY `l`.`created_date` DESC"); 
		return $sql->result_array(); 
	}
}
