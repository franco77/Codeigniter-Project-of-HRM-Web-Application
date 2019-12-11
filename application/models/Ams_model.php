<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Ams_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database(); 
	} 
	
	
	public function getOwnershipWithAllotMachine($emp_code = '') 
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		
		if($emp_code != ''){
			$emp_code = "AND (e.ix_corp_id LIKE '%".$emp_code."%' OR ee.ix_corp_id LIKE '%".$emp_code."%' OR eee.ix_corp_id LIKE '%".$emp_code."%')";
		}
		$query = $this->db1->query("SELECT e.ix_emp_id, e.ix_corp_id, e.s_emp_name, e.e_user_status, ee.ix_emp_id as ix_emp_id_o, ee.ix_corp_id as ix_corp_id_o, ee.s_emp_name as s_emp_name_o, ee.e_user_status as e_user_status_o, eee.ix_emp_id as ix_emp_id_oo, eee.ix_corp_id as ix_corp_id_oo, eee.s_emp_name as s_emp_name_oo, eee.e_user_status as e_user_status_oo,  m.s_machine_name, m.ix_machine, (select s_emp_name from employee_info where employee_info.ix_emp_id = a.source_id) as emp_morning, (select ix_emp_id from employee_info where employee_info.ix_emp_id = a.source_id) as ix_emp_id_morning, (select s_emp_name from employee_info where employee_info.ix_emp_id = aa.source_id) as emp_general, (select ix_emp_id from employee_info where employee_info.ix_emp_id = aa.source_id) as ix_emp_id_general, (select s_emp_name from employee_info where employee_info.ix_emp_id = aaa.source_id) as emp_evening, (select ix_emp_id from employee_info where employee_info.ix_emp_id = aaa.source_id) as ix_emp_id_evening  
		FROM `machine` m 
		LEFT JOIN `employee_info` e ON m.ownership_morning = e.ix_emp_id 
		LEFT JOIN `employee_info` ee ON m.ownership_general = ee.ix_emp_id 
		LEFT JOIN `employee_info` eee ON m.ownership_evening = eee.ix_emp_id 
		LEFT JOIN `allotment` a ON a.product_id = m.ix_machine AND a.shift='Morning'  
		LEFT JOIN `allotment` aa ON aa.product_id = m.ix_machine AND aa.shift='General'  
		LEFT JOIN `allotment` aaa ON aaa.product_id = m.ix_machine AND aaa.shift='Evening'  
		WHERE m.n_disp_flag = '1'  $emp_code ORDER BY m.ix_machine DESC");
		
		$result = $query->result_array();
		$this->db = $this->load->database('default', TRUE); 
		return $result; 
	}
	
	
	public function releaseMachineRM($ix_machine, $ownership)
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$employeesInfo = $this->db1->query("SELECT e.ix_emp_id, e.ix_corp_id 
		FROM `employee_info` e 
		WHERE e.ix_corp_id = '$ownership' ");
		$result = $employeesInfo->result_array();
		$ownershipID = $result[0]['ix_emp_id'];
		$this->db1->query("UPDATE `machine` SET `ownership_morning` = '0' WHERE `ix_machine` = '$ix_machine' AND `ownership_morning` = '$ownershipID'");
		$this->db1->query("UPDATE `machine` SET `ownership_general` = '0' WHERE `ix_machine` = '$ix_machine' AND `ownership_general` = '$ownershipID'");
		$this->db1->query("UPDATE `machine` SET `ownership_evening` = '0' WHERE `ix_machine` = '$ix_machine' AND `ownership_evening` = '$ownershipID'");
		
		$this->db = $this->load->database('default', TRUE); 
		return $result; 
	}
	
	
	public function deleteEMployeeFromMachineMorningRM($ix_machine, $employee)
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$this->db1->query("DELETE from `allotment` WHERE `product_id` = '$ix_machine' AND `source_id` = '$employee' AND `shift` = 'Morning'");
		
		$this->db = $this->load->database('default', TRUE); 
		return 1; 
	}
	
	
	public function deleteEMployeeFromMachineGeneralRM($ix_machine, $employee)
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$this->db1->query("DELETE from `allotment` WHERE `product_id` = '$ix_machine' AND `source_id` = '$employee' AND `shift` = 'General'");
		
		$this->db = $this->load->database('default', TRUE); 
		return 1; 
	}
	
	
	public function deleteEMployeeFromMachineEveningRM($ix_machine, $employee)
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$this->db1->query("DELETE from `allotment` WHERE `product_id` = '$ix_machine' AND `source_id` = '$employee' AND `shift` = 'Evening'");
		
		$this->db = $this->load->database('default', TRUE); 
		return 1; 
	}
	
	
	public function get_active_employee()
	{
		$this->db1 = $this->load->database('amsdb', TRUE); 
		/* query for reporting Offices */
		$sql = $this->db1->query("SELECT i.ix_emp_id, i.ix_corp_id, i.s_emp_name FROM `employee_info` i WHERE  i.e_user_status = '1'  ORDER BY i.s_emp_name ASC");
		$result = $sql->result_array();
		$this->db = $this->load->database('default', TRUE); 
		return $result;
	}
	
	
	
	public function addEMployeeToMachineRM($ownership, $product_id, $shift_type, $source_id) 
	{ 	 
		$this->db1 = $this->load->database('amsdb', TRUE); 
		$employeesInfo = $this->db1->query("SELECT e.ix_emp_id, e.ix_corp_id 
		FROM `employee_info` e 
		WHERE e.ix_corp_id = '$ownership' ");
		$result = $employeesInfo->result_array();
		$ownershipID = $result[0]['ix_emp_id'];
		
		$this->db1->query("INSERT INTO `allotment` (`source_id`, `product_id`, `shift`, `added_by`) VALUES (".$source_id.", ".$product_id.", '".$shift_type."', '".$ownershipID."')");

		$result = $this->db1->insert_id();
		$this->db = $this->load->database('default', TRUE); 
		return $result; 
	}
	
	
	
}
