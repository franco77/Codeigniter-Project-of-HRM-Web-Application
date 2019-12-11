<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Myprofile_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database(); 
	}
	public function get_general_information_of_user($user_id) 
	{ 	 
		$sql = $this->db->query("SELECT i.*,ie.*,u.desg_name, d.dept_name, r.full_name AS rmName, r.loginhandle AS rmECode, c.course_name, cc.country_name AS country_name1, ccc.country_name AS country_name2, ct.state_name AS state_name1, ctt.state_name AS state_name2, lhq.state_name AS loc_highest_qual_name, s.specialization_name
			FROM `internal_user` i 
			LEFT JOIN `internal_user` r ON r.login_id = i.reporting_to 
			LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
			LEFT JOIN `department` d ON d.dept_id = i.department 
			LEFT JOIN `specialization_master` s ON s.specialization_id = i.specialization 
			LEFT JOIN `internal_user_ext` ie ON  i.login_id =ie.login_id 
			LEFT JOIN course_info AS c ON c.course_id = i.highest_qual 
			LEFT JOIN `country` cc ON  cc.country_id = i.country1 
			LEFT JOIN `country` ccc ON  ccc.country_id = i.country2 
			LEFT JOIN `state` lhq ON  lhq.state_id = i.loc_highest_qual 
			LEFT JOIN `state` ct ON  ct.state_id = i.state_region1 
			LEFT JOIN `state` ctt ON  ctt.state_id = i.state_region2 
			WHERE i.login_id = '".$user_id."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function update_last_working_date($lwdDate,$user_id)
	{ 
		$sql = $this->db->query("UPDATE `internal_user` SET `lwd_date` = '".$lwdDate."' WHERE `login_id` = '".$user_id."'");
	}
	public function update_mark_inactive($fnf_status,$emp_status_type,$HR_remark,$user_id)
	{  
		$sql = $this->db->query("UPDATE `internal_user` SET `user_status` = '2', `FnF_status` = '".$fnf_status."', `HR_remark` = '".$HR_remark."', `emp_status_type` = '".$emp_status_type."' WHERE `login_id` = '".$user_id."'");
	}
	
	//Set Resignation date
	public function update_date_of_resign($resign_date,$resign_reason,$user_id)
	{ 
		$sql = $this->db->query("UPDATE `internal_user` SET `resign_date` = '".$resign_date."' , `resign_reason` = '".$resign_reason."' WHERE `login_id` = '".$user_id."'");
	}
	
	//Set Employee Type
	public function update_emp_type($employeeID,$ddlTypeEmp,$department,$designation,$ddlDoj,$user_id)
	{ 
		$sql = $this->db->query("SELECT i.* FROM `internal_user` i  WHERE i.login_id = '".$user_id."'"); 
		$result = $sql->result_array(); 
		
		$insertSQL = "INSERT INTO internal_user_change_log (login_id, loginhandle, designation, department, join_date, emp_type, type)
                    VALUES ('".$user_id."','".$result[0]['loginhandle']."','".$result[0]['designation']."','".$result[0]['department']."','".$result[0]['join_date']."','".$result[0]['emp_type']."','0')";
		$this->db->query($insertSQL);
		
		$sqls = $this->db->query("UPDATE `internal_user` SET `loginhandle` = '".$employeeID."' , `designation` = '".$designation."', `department` = '".$department."', `join_date` = '".$ddlDoj."', `emp_type` = '".$ddlTypeEmp."' WHERE `login_id` = '".$user_id."'");
	}
	
	//Set Employee Promotion
	public function update_emp_promotion($department,$designation,$promotion_date,$user_id)
	{ 
		$sql = $this->db->query("SELECT i.* FROM `internal_user` i  WHERE i.login_id = '".$user_id."'"); 
		$result = $sql->result_array(); 
		
		$insertSQL = "INSERT INTO internal_user_change_log (login_id, loginhandle, designation, department, promotion_date, emp_type, type)
                    VALUES ('".$user_id."','".$result[0]['loginhandle']."','".$result[0]['designation']."','".$result[0]['department']."','".$promotion_date."','".$result[0]['emp_type']."','1')";
		$this->db->query($insertSQL);
		
		$sqls = $this->db->query("UPDATE `internal_user` SET `designation` = '".$designation."', `department` = '".$department."' WHERE `login_id` = '".$user_id."'");
	}
	
	public function education_change_course($coursetype)
	{ 
		$this->db->select('course_info.*');		
		$this->db->from('course_info');					
		$this->db->where('course_type',$coursetype);		
		$query = $this->db->get();		
		$result = $query->result(); 
		return $result; 
	}
	public function education_change_specialization($specialization)
	{ 
		$this->db->select('specialization_master.*');		
		$this->db->from('specialization_master');					
		$this->db->where('course_id',$specialization);		
		$query = $this->db->get();		
		$result = $query->result(); 
		return $result; 
	}
}