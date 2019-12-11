<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Performance_model extends CI_Model {

	/**
	 * __construct function. 
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	} 
	public function get_probation_assessment_all() 
	{
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.department, p.* FROM `internal_user` i RIGHT JOIN `probation_assessment` p ON p.employee_id = i.login_id WHERE i.login_id != '10010' ORDER BY p.apply_date DESC";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	}
	
	public function get_midyear_review_all() 
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$cond =" AND DATE_FORMAT(om.apply_date,'%Y')=$fyear";
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.user_status = '1' $cond ";
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		return $result; 
	} 
	
	public function get_midyear_review_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		$cond = " i.user_status = '1'";
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
			$cond = $cond." AND DATE_FORMAT(om.apply_date,'%Y')=$s_year";
		}
		
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
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."loginhandle = '".$searchEmpCode."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE $cond ");
		$result = $sql->result_array(); 
		return $result;
	}
	 
	public function update_midyear_review_remark($remark,$mid)
	{
		$data = array(
			'remark' => $remark
		);
		$this->db->where('mid', $mid);
		$this->db->update('midyear_review', $data);
		return $mid;
	}
	/**
	 * get_user function. 
	*/
	public function get_midyear_appraisal_report() 
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$cond =" AND DATE_FORMAT(m.apply_date,'%Y')=$fyear";
		
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS full_name, i.full_name as name, i.designation, m.*, u.* FROM `internal_user` i RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE m.login_id != '10010' AND m.rm_status='1' AND i.user_status='1' $cond ");  
		$res = $sql->result_array(); 
		$result = array(); 
		for($i=0; $i<count($res); $i++){
			$s_year=$res[$i]['apply_date']+1;
			$cond2 = " AND DATE_FORMAT(annualdate,'%Y')='".$s_year."'"; 
			$regsal = $this->db->query("SELECT * FROM `goal_sheet` WHERE login_id = '".$res[$i]['login_id']."' $cond2");
			$rowM = $regsal->result_array(); //print_r($rowM);
			$m = 0;$progresssive=0;
			for($k=0; $k<count($rowM); $k++){
				$m++;
				if($rowM[$k]['rating']=='Progressing'){
					$progresssive++;
				}
			}
			$progress = 0;
			if($progresssive > 0){
				$progress = ($progresssive/$m)*100 ;
			}
			$per_progress = number_format((float)($progress), 2, '.', '') ;
			$data = array(
				'login_id' => $res[$i]['login_id'],
				'loginhandle' => $res[$i]['loginhandle'],
				'name' => $res[$i]['name'],
				'designation' => $res[$i]['designation'],
				'desg_id' => $res[$i]['desg_id'],
				'desg_name' => $res[$i]['desg_name'],
				'accomplishments' => $res[$i]['accomplishments'],
				'contributions' => $res[$i]['contributions'],
				'unplanned_events' => $res[$i]['unplanned_events'],
				'exceeding_expectation' => $res[$i]['exceeding_expectation'],
				'improvement' => $res[$i]['improvement'],
				'discussion' => $res[$i]['discussion'],
				'summary_expectation' => $res[$i]['summary_expectation'],
				'employee_development' => $res[$i]['employee_development'],
				'unique_pin' => $res[$i]['unique_pin'],
				'apply_date' => date('d-m-Y H:i:s', strtotime($res[$i]['apply_date'])),
				'approved_date' => $res[$i]['approved_date'],
				'rm_status' => $res[$i]['rm_status'],
				'rm_desc' => $res[$i]['rm_desc'],
				'dh_status' => $res[$i]['dh_status'],
				'per_progress' => $per_progress,
				'remark' => $res[$i]['remark']
			);
			array_push($result, $data);
		} 
		return $result;
	}
	
	public function get_midyear_appraisal_report_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		$cond = " AND i.user_status = '1'";
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
			$cond = $cond." AND DATE_FORMAT(m.apply_date,'%Y')=$s_year";
		}
		
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
			$cond = $cond."full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."loginhandle = '".$searchEmpCode."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS full_name, i.full_name as name, i.designation, m.*, u.* FROM `internal_user` i RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE m.login_id != '10010' AND m.rm_status='1' $cond ");
		$res = $sql->result_array(); 
		$result = array(); 
		for($i=0; $i<count($res); $i++){
			$cond2 = " AND DATE_FORMAT(adate,'%Y')='".$res[$i]['apply_date']."'"; 
			$regsal = $this->db->query("SELECT * FROM `goal_sheet` WHERE login_id = '".$res[$i]['login_id']."' $cond2");
			$rowM = $regsal->result_array(); 
			$m = 0;$progresssive=0;
			for($k=0; $k<count($rowM); $k++){
				$m++;
				if($rowM[$k]['rating']=='Progressing'){
					$progresssive++;
				}
			}
			$progress = 0;
			if($progresssive > 0){
				$progress = ($progresssive/$m)*100 ;
			}
			$per_progress = number_format((float)($progress), 2, '.', '') ;
			$data = array(
				'login_id' => $res[$i]['login_id'],
				'loginhandle' => $res[$i]['loginhandle'],
				'name' => $res[$i]['name'],
				'designation' => $res[$i]['designation'],
				'desg_id' => $res[$i]['desg_id'],
				'desg_name' => $res[$i]['desg_name'],
				'accomplishments' => $res[$i]['accomplishments'],
				'contributions' => $res[$i]['contributions'],
				'unplanned_events' => $res[$i]['unplanned_events'],
				'exceeding_expectation' => $res[$i]['exceeding_expectation'],
				'improvement' => $res[$i]['improvement'],
				'discussion' => $res[$i]['discussion'],
				'summary_expectation' => $res[$i]['summary_expectation'],
				'employee_development' => $res[$i]['employee_development'],
				'unique_pin' => $res[$i]['unique_pin'],
				'apply_date' => date('d-m-Y H:i:s', strtotime($res[$i]['apply_date'])),
				'approved_date' => $res[$i]['approved_date'],
				'rm_status' => $res[$i]['rm_status'],
				'rm_desc' => $res[$i]['rm_desc'],
				'dh_status' => $res[$i]['dh_status'],
				'per_progress' => $per_progress,
				'remark' => $res[$i]['remark']
			);
			array_push($result, $data);
		} 
		return $result;
	}
	
	
	
	public function get_annual_appraisal_all() 
	{
		$yy = date("Y");
		$e_year=$yy;
		$cond ="i.user_status = '1' AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE $cond"); 
		return $sql->result_array(); 
	}
	
	public function update_annual_appraisal_remark($remark,$mid)
	{
		$data = array(
			'remark' => $remark
		);
		$this->db->where('mid', $mid);
		$this->db->update('annual_appraisal', $data);
		return $mid;
	}
	
	public function get_annual_appraisal_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		$cond = " i.user_status = '1'";
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
			$cond = $cond." AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
		}
		
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
			$cond = $cond."full_name LIKE '%".$searchName."%'";
		}
		if($searchDesignation  !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."loginhandle = '".$searchEmpCode."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE $cond ");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_annual_appraisal_report() 
	{
		$yy = date("Y");
		$e_year=$yy;
		$cond =" AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, m.*, u.* FROM `internal_user` i RIGHT JOIN `annual_appraisal` m ON m.login_id=i.login_id LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE m.login_id != '10010' AND m.rm_status='1' AND user_status='1' AND m.dh_status='1' $cond"); 
		$result = $sql->result_array();
		return $result;  
	}
	
	public function get_annual_appraisal_report_search($searchYear)
	{
		$yy = date("Y");
		$cond = "";
		if($searchYear == ""){
			$e_year=$yy; 
			$cond = $cond." AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
			$cond = $cond." AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, m.*, u.* FROM `internal_user` i RIGHT JOIN `annual_appraisal` m ON m.login_id=i.login_id LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE m.login_id != '10010' AND m.rm_status='1' AND user_status='1' AND m.dh_status='1' $cond");
		$result = $sql->result_array(); 
		return $result;
	}
		
}
