<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Timesheet_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database(); 
	} 
	
	public function insert_attendance_on_regularize($login_id, $date, $att_status, $in_time, $reason)
	{ 	 
		$data = array(
			'login_id' => $login_id,
			'date' => $date,
			'att_status' => $att_status,
			'in_time' => $in_time,
			'reason' => $reason
		);
		$this->db->insert('attendance_new',$data);
	} 
	
	public function update_attendance_on_regularize_H($in_time, $attendance_id)
	{ 	 
		$data = array(
			'in_time' => $in_time
		);
		$this->db->where('attendance_id',$attendance_id);
		$this->db->update('attendance_new',$data);
	} 
	
	public function update_attendance_on_regularize_p($att_status, $reason, $attendance_id)
	{ 	 
		$data = array(
			'att_status' => $att_status,
			'reason' => $reason
		);
		$this->db->where('attendance_id',$attendance_id);
		$this->db->update('attendance_new',$data);
	}
	
	public function insert_attendance_on_leaver_apply($login_id, $date, $att_status, $in_time, $reason, $leave_type)
	{ 	 
		$data = array(
			'login_id' => $login_id,
			'date' => $date,
			'att_status' => $att_status,
			'in_time' => $in_time,
			'reason' => $reason,
			'leave_type' => $leave_type
		);
		$this->db->insert('attendance_new',$data);
	} 
	
	public function update_attendance_on_leaver_apply($att_status, $reason, $leave_type, $login_id, $date)
	{ 	 
		$data = array(
			'att_status' => $att_status,
			'reason' => $reason,
			'leave_type' => $leave_type
		);
		$this->db->where('login_id',$login_id);
		$this->db->where('date',$date);
		$this->db->update('attendance_new',$data);
	}
	
	//get reporting manager 
	public function get_reporting_manager() 
	{
		$sql = "SELECT i.department, i.reporting_to, u.full_name, u.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` u ON i.reporting_to = u.login_id  WHERE i.login_id = '".$this->session->userdata('user_id')."'"; 
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	
	public function insert_apply_for_regularise($reporting_to, $from_date, $to_date, $reason, $no_of_days=0, $date_regularize, $reason_time=0)
	{
		$data = array( 
			'user_id'    => $this->session->userdata('user_id'),
			'rm_id'      => $reporting_to,
			'from_date'  => $from_date,
			'to_date'  	 => $to_date,
			'type'       => 'R',
			'reason'     => $reason,
			'no_of_days'     => $no_of_days,
			'status'   	 => 'P',
			'rej_reason' => '',
			'act_date'   => '',
			'reason_date' => $date_regularize,
			'reason_hour' => $reason_time,
			'req_date'   => date('Y-m-d H:i:s')
		); 
		return $this->db->insert('attendance_request', $data);
	}
	
	public function get_my_regularies()
	{
		$currentDate = date("Y-m",strtotime(date('Y').'-'.date('m').'-01'));
		$sql = "SELECT r.*, i.full_name as rep_full_name, ii.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`type` = 'R' AND r.`user_id` = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(r.from_date, '%Y-%m')) = '".$currentDate."' ORDER BY req_date DESC";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	public function get_my_regularise_application_search($searchMonth, $searchYear)
	{
		//echo $searchYear.'-'.$searchMonth;
		if($searchMonth !="" && $searchYear !=""){
			$currentDate = date("Y-m",strtotime($searchYear.'-'.$searchMonth.'-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT r.*, i.full_name as rep_full_name, ii.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`type` = 'R' AND r.`user_id` = '".$this->session->userdata('user_id')."'  AND (DATE_FORMAT(r.from_date, '%Y-%m')) = '".$currentDate."'  ORDER BY req_date DESC";
		}
		else if($searchYear !=""){
			$currentDate = date("Y",strtotime($searchYear.'-01-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT r.*, i.full_name as rep_full_name, ii.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`type` = 'R' AND r.`user_id` = '".$this->session->userdata('user_id')."'  AND (DATE_FORMAT(r.from_date, '%Y')) = '".$currentDate."'  ORDER BY req_date DESC";
		}
		else if($searchMonth !=""){
			$currentDate = date("m",strtotime(date("Y").'-'.$searchMonth.'-01'));
			$leaveAppSql = "SELECT r.*, i.full_name as rep_full_name, ii.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`type` = 'R' AND r.`user_id` = '".$this->session->userdata('user_id')."'  AND (DATE_FORMAT(r.from_date, '%m')) = '".$currentDate."'  ORDER BY req_date DESC";
		}
		else{
			$leaveAppSql = "SELECT r.*, i.full_name as rep_full_name, ii.full_name, a.attendance_id FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id LEFT JOIN `attendance_new` a ON a.login_id = r.user_id AND a.date = r.from_date WHERE r.`type` = 'R' AND r.`user_id` = '".$this->session->userdata('user_id')."'  ORDER BY req_date DESC";
		}
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function get_leave_balance()
	{
		$sql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$this->session->userdata('user_id')."'";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	public function get_leave_balance_details($login_id)
	{
		$sql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$login_id."'";
		$result = $this->db->query($sql,array($login_id))->result_array();
		return $result;
	}
	
	
	public function insert_apply_for_leave($leave_type, $leave_from, $leavefromhalfday,$leave_to,$leavetohalfday,$absence_reason,$reportingTo, $contact_details, $no_of_days)
	{
		if($leave_type == "M")
		{
			$absence_reason = "ML";
		}
		$data = array( 
			'user_id'    		=> $this->session->userdata('user_id'),
			'rp_mgr_id'      	=> $reportingTo,
			'leave_type'  		=> $leave_type,
			'leave_from'  	 	=> $leave_from,
			'leavefromhalfday'  => $leavefromhalfday,
			'leave_to'     		=> $leave_to,
			'leavetohalfday'   	=> $leavetohalfday,
			'absence_reason' 	=> $absence_reason,
			'contact_details'   => $contact_details,
			'no_of_days'   => $no_of_days,
			'app_dt' 			=> date('Y-m-d H:i:s')
		);
		//var_dump($data);exit;
		return $this->db->insert('leave_application', $data);
	}
	public function get_my_leave_details($cond)
	{
		$sql = "SELECT l.*, i.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id WHERE l.user_id = '".$this->session->userdata('user_id')."' $cond";
		 
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	public function get_employee_type_count()
	{
		$sql = "SELECT `emp_type`,`join_date` FROM  `internal_user` WHERE `login_id` = '".$this->session->userdata('user_id')."'";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->num_rows();
		return $result;
	}
	public function get_employee_type()
	{
		$sql = "SELECT `emp_type`,`join_date` FROM  `internal_user` WHERE `login_id` = '".$this->session->userdata('user_id')."'";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	public function get_all_declared_holidays($year)
	{
		$sql = "SELECT `dt_event_date`, `s_event_name` FROM `declared_leave` WHERE (`branch`='0' OR `branch`='".$this->session->userdata('branch')."') AND `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y') = $year";
		$result = $this->db->query($sql,array($this->session->userdata('branch')))->result_array();
		return $result;
	}
	public function get_present_leave_regularize($year)
	{  	
		$sql = "SELECT date,att_status,in_time,out_time,reason FROM attendance_new WHERE login_id = '".$this->session->userdata('user_id')."' AND DATE_FORMAT(date, '%Y') = $year";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->result_array();
		return $result;
	}
	public function get_attendance_id()
	{ 	 
		$sql = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$this->session->userdata('user_id')."' AND `date` = '".date("Y-m-d")."'";
		$result = $this->db->query($sql,array($this->session->userdata('user_id')))->num_rows();
		return $result;
	}
	function getWhichHalfLeave($userID, $date)
	{
		$half    = 'F';
		$halfSQL = "SELECT `application_id`, `leavefromhalfday`, `leavetohalfday`, `leave_from`, `leave_to` FROM `leave_application` WHERE `user_id` = '$userID' AND (`leave_from` = '$date' OR `leave_to` = '$date')";
		$half_info = $this->db->query($sql,array($userID,$date))->result_array();
		$half_num = count($result);
		if ($half_num > 0) 
		{ 	 
			if ($half_info['leavefromhalfday'] == 'Y' && $half_info['leave_from'] == $date)
			{
				$half = 'F';
			}
			elseif ($half_info['leavetohalfday'] == 'Y' && $half_info['leave_to'] == $date)
			{
				$half = 'S';
			}
		}
    return $half;
	}
	public function update_withdraw_leave_application($txtReason,$appid)
	{
		// Withdraw Leave Request
		$rejQry = "UPDATE `leave_application` SET `status` = 'W', `w_c_reason` = '".$txtReason."', `w_c_dt` = '".date("Y-m-d H:i:s")."' WHERE `application_id` = '".$appid."' AND `user_id` = '".$this->session->userdata('user_id')."' LIMIT 1";
		//echo $rejQry;exit;
		$this->db->query($rejQry); 
	}
	public function get_regularize_attendance_request()
	{
		// Get My Regularize Application
		$regAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."'  ORDER BY attd_req_id DESC";
		$sql = $this->db->query($regAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function get_regularize_attendance_request_search($searchMonth, $searchYear, $searchStatus)
	{
		//echo $searchYear.'-'.$searchMonth;
		$cond = "";
		if($searchStatus !=""){
			$cond = " AND r.status = '".$searchStatus."'";
		}
		
		if($searchMonth !="" && $searchYear !=""){
			$currentDate = date("Y-m",strtotime($searchYear.'-'.$searchMonth.'-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(r.from_date, '%Y-%m')) = '".$currentDate."' $cond  ORDER BY attd_req_id DESC";
		}
		else if($searchYear !=""){
			$currentDate = date("Y",strtotime($searchYear.'-01-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(r.from_date, '%Y')) = '".$currentDate."' $cond  ORDER BY attd_req_id DESC";
		}
		else if($searchMonth !=""){
			$currentDate = date("m",strtotime(date("Y").'-'.$searchMonth.'-01'));
			$leaveAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(r.from_date, '%m')) = '".$currentDate."' $cond  ORDER BY attd_req_id DESC";
		}
		else{
			$leaveAppSql = "SELECT r.*, (DATE_FORMAT(r.from_date, '%d-%m-%Y')) as from_dates, (DATE_FORMAT(r.to_date, '%d-%m-%Y')) as to_dates, (DATE_FORMAT(r.req_date, '%d-%m-%Y %H:%i:%s')) as req_dates, i.full_name as rep_full_name, ii.full_name FROM `attendance_request` r INNER JOIN `internal_user` i ON r.rm_id = i.login_id LEFT JOIN `internal_user` ii ON r.user_id = ii.login_id WHERE r.`type` = 'R' AND r.`rm_id` = '".$this->session->userdata('user_id')."' $cond  ORDER BY attd_req_id DESC";
		}
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function get_my_leave_application() { // Get My Leave Application 
		$leaveAppSql = "SELECT l.*, i.full_name,j.full_name as emp_name, (CASE WHEN (DATE_FORMAT(l.leave_from,'%m') = '".date('m')."') THEN 1 ELSE 0 END) AS 'applied_month' FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id INNER JOIN `internal_user` j ON l.user_id = j.login_id WHERE l.user_id ='".$this->session->userdata('user_id')."' ORDER BY `application_id` DESC"; $sql = $this->db->query($leaveAppSql); $result = $sql->result_array(); 
		return($result); 
	}
	public function get_my_leave_application_search($searchMonth, $searchYear)
	{
		if($searchMonth !="" && $searchYear !=""){
			$currentDate = date("Y-m",strtotime($searchYear.'-'.$searchMonth.'-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT l.*, i.full_name,j.full_name as emp_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id INNER JOIN `internal_user` j ON l.user_id = j.login_id WHERE l.user_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%Y-%m')) = '".$currentDate."'  ORDER BY `application_id` DESC";
		}
		else if($searchYear !=""){
			$currentDate = date("Y",strtotime($searchYear.'-01-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT l.*, i.full_name,j.full_name as emp_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id INNER JOIN `internal_user` j ON l.user_id = j.login_id WHERE l.user_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%Y')) = '".$currentDate."' ORDER BY `application_id` DESC";
		}
		else if($searchMonth !=""){
			$currentDate = date("m",strtotime(date("Y").'-'.$searchMonth.'-01'));
			$leaveAppSql = "SELECT l.*, i.full_name,j.full_name as emp_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id INNER JOIN `internal_user` j ON l.user_id = j.login_id WHERE l.user_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%m')) = '".$currentDate."' ORDER BY `application_id` DESC";
		}
		else{
			$leaveAppSql = "SELECT l.*, i.full_name,j.full_name as emp_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id INNER JOIN `internal_user` j ON l.user_id = j.login_id WHERE l.user_id = '".$this->session->userdata('user_id')."' ORDER BY `application_id` DESC";
		}
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function get_my_leave_request()
	{
		// Get My Leave Application
		$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' ORDER BY application_id DESC";
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function get_my_leave_request_search($searchMonth, $searchYear, $searchStatus)
	{
		//echo $searchYear.'-'.$searchMonth;
		$cond = "";
		if($searchStatus !=""){
			$cond = " AND l.status = '".$searchStatus."'";
		}
		
		if($searchMonth !="" && $searchYear !=""){
			$currentDate = date("Y-m",strtotime($searchYear.'-'.$searchMonth.'-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%Y-%m')) = '".$currentDate."' $cond  ORDER BY application_id DESC";
		}
		else if($searchYear !=""){
			$currentDate = date("Y",strtotime($searchYear.'-01-01'));
			// Get My Leave Application
			$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%Y')) = '".$currentDate."' $cond  ORDER BY application_id DESC";
		}
		else if($searchMonth !=""){
			$currentDate = date("m",strtotime(date("Y").'-'.$searchMonth.'-01'));
			$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' AND (DATE_FORMAT(l.leave_from, '%m')) = '".$currentDate."' $cond  ORDER BY application_id DESC";
		}
		else{
			$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name FROM `leave_application` l INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.rp_mgr_id = '".$this->session->userdata('user_id')."' $cond  ORDER BY application_id DESC";
		}
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function check_leave_request_status_employee($application_id)
	{
		$leaveAppSql = "SELECT l.*, i.full_name as rep_full_name, ii.full_name 
		FROM `leave_application` l 
		INNER JOIN `internal_user` i ON l.rp_mgr_id = i.login_id 
		LEFT JOIN `internal_user` ii ON l.user_id = ii.login_id WHERE l.application_id = '".$application_id."' AND l.status='P'  ORDER BY application_id DESC";
		
		$sql = $this->db->query($leaveAppSql);
		$result = $sql->result_array();
		return($result);
	}
	public function update_leave_request_approved($application_id)
	{
		$data = array(
			'status' => 'A',
			'action_dt' => date('Y-m-d H:i:s')
		);
		$this->db->where('application_id', $application_id);
		$this->db->update('leave_application', $data);
		$result = 1;
		return($result);
	}
	public function update_leave_request_rejected($application_id, $reject_reason)
	{
		$data = array(
			'status' => 'R',
			'reject_reason' => $reject_reason,
			'action_dt' => date('Y-m-d H:i:s')
		);
		$this->db->where('application_id', $application_id);
		$this->db->update('leave_application', $data);
		$result = 1;
		return($result);
	}
	/**
	 * Get All Employee's Name.
	 * @param	none
	 * @return	string
	*/
	public function get_all_employee()
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		$reportingArray = '';
		while($reportingInfoSelect)
		{
			$reportingArray .= ",['". $reportingInfoSelect['dispName']."', ".$reportingInfoSelect['login_id']."]";
		}
		$reportingArray = substr($reportingArray, 1);
		
		return $reportingArray;
	}
	
	public function update_regularise_request_rejected($application_id, $reject_reason) { 
		$data = array( 'status' => 'R', 'rej_reason' => $reject_reason, 'act_date' => date('Y-m-d H:i:s') ); $this->db->where('attd_req_id', $application_id); $this->db->update('attendance_request', $data); 
		$result = 1; return($result); 
	}
	
}
