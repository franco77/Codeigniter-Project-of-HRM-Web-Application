<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hr_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	public function insert_resignation_letter($login_id, $subject, $lwd, $separation, $message)
	{ 	 
		$data = array(
			'login_id' => $login_id,
			'subject' => $subject,
			'lwd' => $lwd,
			'separation' => $separation,
			'message' => $message,
			'created_date' => date('Y-m-d')
		);
		$this->db->insert('resignation',$data);
	}
	
	
	public function get_assignment_shift() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift,i.division FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.login_id != '10010' AND reporting_to = '".$this->session->userdata('user_id')."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function update_asign_shift($shift,$login_id)
	{ 	 
		$data = array(
			'shift' => $shift
		);
		$this->db->where('login_id', $login_id);
		$this->db->update('internal_user', $data);
	}
	
	public function update_emp_division($division,$login_id)
	{ 	 
		$data = array(
			'division' => $division
		);
		$this->db->where('login_id', $login_id);
		$this->db->update('internal_user', $data);
	}
	public function get_download_salary_slip($month,$year) 
	{ 	 
		$query = $this->db->query("SELECT * FROM `salary_slip_download` WHERE login_id = '".$this->session->userdata('user_id')."' AND year ='".$year."' AND month='".$month."'"); 
		$result = $query->result_array();  
		return $result; 
	}
	public function get_download_salary_slip_emp($month,$year,$login_id)
	{ 	 
		/* $query = $this->db->query("SELECT * FROM `salary_slip_download` WHERE login_id = '".$login_id."' AND year ='".$year."' AND month='".$month."'"); 
		$result = $query->result_array();  */
		$this->db->select("*");
		$this->db->from("salary_slip_download");
		$this->db->where("login_id", $login_id);
		$this->db->where("month", $month);
		$this->db->where("year", $year);
		$query = $this->db->get();
		$result = $query->result();
		//echo $this->db->last_query();
		return $result; 
	}
	public function get_separation_master() 
	{ 	 
		$sql = $this->db->query("SELECT separation_id, separation_name FROM separation_master WHERE status = 'Y'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_apply_resignation() 
	{ 	 
		$sql = $this->db->query("SELECT * FROM `resignation` WHERE login_id='".$loginID."' AND rm_status = '0'");
		$row = $sql->row();
		if($row() >0)
		{
			
		}
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_my_resignation_application() 
	{ 	 
		$sql = $this->db->query("SELECT i.full_name,i.loginhandle, d.desg_name,dp.dept_name,l.*, DATE_FORMAT(l.created_date, '%d-%m-%Y') as apply_date,s.separation_name FROM `resignation` l 
		LEFT JOIN `internal_user` i ON l.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		LEFT JOIN `user_desg` d ON d.desg_id = i.designation 
		LEFT JOIN `separation_master` s ON s.separation_id = l.separation 
		WHERE i.login_id = '".$this->session->userdata('user_id')."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function update_regination_emp_status($rid)
	{ 	 
		$data = array(
			'emp_status' => '1' 
		);
		$this->db->where('rid', $rid);
		$this->db->update('resignation', $data);
	}
	public function hr()
	{
		$sql = $this->db->query("SELECT login_id FROM `department` WHERE dept_id = '2'");
		$result = $sql->row();
		return $result;
		
	}
	public function production_head()
	{
		$sql = $this->db->query("SELECT login_id FROM `department` WHERE dept_id = '6'");
		$result = $sql->row();
		return $result;
	}
	public function update_emp_action()
	{
		extract($this->input->post()); 
		$empSql = $this->db->query("SELECT i.email,i.full_name,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON i.login_id = l.login_id WHERE l.rid = '$rid'");
		$empInfo = $empSql->row();
		//$empRes = mysql_query($empSql);
		//$empInfo = mysql_fetch_row($empRes); 
		$messageReject=$messageApprove='';
		$site_base_url=base_url();
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.SITE_ADDR.'/images/logo.gif" />
			</div>';
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.SITE_ADDR.'/images/icon/facebook.png" /> Find us on Facebook</a><br />
			<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.SITE_ADDR.'/images/icon/linkedin.png" /> Find us on Linkedin</a>
			<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
				&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
			</div>';
				
		$messageApprove=<<<EOD
			<!DOCTYPE HTML>
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
			</head>
			<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
				<div style="width:900px; margin: 0 auto; background: #fff;">
				 <div style="width:650px; float: left; min-height: 190px;">
					 <div style="padding: 7px 7px 14px 10px;">
					 <p>Dear Manager,</p>                                 
					 <p>{$empInfo[1]} has applied for Resignation Letter. </p>                                 
					 <p><a href="{$site_base_url}/script/resignation_approve_reject.php" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;
		$messageReject=<<<EOD
			<!DOCTYPE HTML>
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
			</head>
			<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
				<div style="width:900px; margin: 0 auto; background: #fff;">
				 <div style="width:650px; float: left; min-height: 190px;">
					 <div style="padding: 7px 7px 14px 10px;">
					 <p>Dear {$empInfo[1]},</p>                                 
					 <p>Your Resignation Letter has been rejected. </p>                                 
					 <p><a href="{$site_base_url}/script/my_resignation_application.php" style="text-decoration:none">Click here to view details</a><br /><br /></p>
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;

		$messageCancel=<<<EOD
		<!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
		</head>
		<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
			<div style="width:900px; margin: 0 auto; background: #fff;">
			 <div style="width:650px; float: left; min-height: 190px;">
				 <div style="padding: 7px 7px 14px 10px;">
				 <p>Dear {$empInfo[1]},</p>                                 
				 <p>Your Resignation Letter has been Cancelled. </p>                                 
				 <p><a href="{$site_base_url}/script/my_resignation_application.php" style="text-decoration:none">Click here to view details</a><br /><br /></p>
				 <p> In case of any Query, Please contact to HR Department.</p>                                 
				 <p>{$footer}</p>
				 </div> 
			  </div> 
			</div>  
			</div>
		</body>
		</html>
EOD;
						 
		$subject = 'Resignation Letter';      
		//echo $message;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
		$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
				
		$empDpSql = $this->db->query("SELECT d.dept_head,i.email FROM `department` d LEFT JOIN `internal_user` i ON i.login_id = d.dept_head WHERE d.dept_id = '$dept_id'");
		$empDpInfo = $empDpSql->row();
		//$empDpRes = mysql_query($empDpSql);
		//$empDpInfo = mysql_fetch_row($empDpRes);
		//echo $empInfo[0].'=='.$loginID;exit;  
			
		if($action=='approve')
		{ 
			$approveSQL = $this->db->query("UPDATE `resignation` SET ".$status."= '1'  WHERE `rid` = '".$rid."'");
			//@mysql_query($approveSQL);
			if($status=='rm_status')           
				$to ="hr@polosoftech.com";
			else
				$to =$empInfo[0];    
			// $to="pradeep.sahoo@polosoftech.com";
			mail($to, $subject, $messageApprove, $headers);  
		}
		if($action=='reject')
		{   
				$rejectSql = $this->db->query("UPDATE `resignation` SET ".$status."= '2' WHERE `rid` = '".$rid."'");
				//@mysql_query($rejectSql);
				
				$to =$empInfo[0];        
				mail($to, $subject, $messageReject, $headers);  
		}
		if($action=='cancel')
		{   
				$rejectSql = $this->db->query("UPDATE `resignation` SET ".$status."= '1' WHERE `rid` = '".$rid."'");
				//@mysql_query($rejectSql);
				
				$to =$empInfo[0];        
				mail($to, $subject, $messageCancel, $headers);  
		}
	}
	
	
	public function get_all_resignation_application_details()
	{
		$date = date('Y');
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.*,s.separation_name FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `separation_master` s ON s.separation_id = l.separation WHERE i.login_id != '10010' and DATE_FORMAT(`l`.`created_date`, '%Y') = '$date' ORDER BY l.rid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	
	
	public function get_all_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode, $year)
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
		if($year !=""){
			
			$cond .= " AND ";
			$cond = $cond."DATE_FORMAT(`l`.`created_date`, '%Y') = '$year'";
		}
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE $cond order by l.rid DESC"); 
		$result = $sql->result_array(); 
		return $result;
	}
	
	
	public function update_resignation_application_approved($rid,$remarks)
	{
		$data = array(
			'hr_status' => '1',
			'hr_desc' => $remarks
		);
		$this->db->where('rid', $rid);
		$this->db->update('resignation', $data);
		$result = 1;
		return($result);
	}
	
	
	public function update_resignation_application_rejected($rid, $reject_reason)
	{
		$data = array(
			'hr_status' => '2',
			'hr_desc' => $reject_reason,
			//'action_dt' => date('Y-m-d H:i:s')
		);
		$this->db->where('rid', $rid);
		$this->db->update('resignation', $data);
		$result = 1;
		return($result);
	}
	
	
	public function update_resignation_application_approved_rm($rid, $message)
	{
		$data = array(
			'rm_status' => '1',
			'rm_desc' => $message
		);
		$this->db->where('rid', $rid);
		$this->db->update('resignation', $data);
		$result = 1;
		return($result);
	}
	
	
	public function update_resignation_application_rejected_rm($rid, $reject_reason)
	{
		$data = array(
			'rm_status' => '2',
			'rm_desc' => $reject_reason,
			//'action_dt' => date('Y-m-d H:i:s')
		);
		$this->db->where('rid', $rid);
		$this->db->update('resignation', $data);
		$result = 1;
		return($result);
	}
	
	public function update_loan_advance_approved_rm($lid)
	{
		$data = array(
			'rm_status' => '1'
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	public function update_loan_advance_rejected_rm($lid, $reject_reason)
	{
		$data = array(
			'rm_status' => '2',
			'rm_rej_msg' => $reject_reason,
			//'action_dt' => date('Y-m-d H:i:s')
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	
	public function update_loan_advance_approved_dh($lid)
	{
		$data = array(
			'dh_status' => '1'
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	public function update_loan_advance_rejected_dh($lid, $reject_reason)
	{
		$data = array(
			'dh_status' => '2',
			'dh_rej_msg' => $reject_reason
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	
	
	public function update_loan_advance_approved_hr($lid, $approveamount, $approveinstalment, $selMonth, $selYear)
	{
		$rSql = "SELECT * FROM `loan_advance_apply` WHERE lid = '".$lid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		$data = array(
			'hr_status' => '1',
			'approvedamount' => $approveamount,
			'approvedinstalment' => $approveinstalment,
			'lmonth' => $selMonth,
			'lyear' => $selYear
		);
		//print_r($data);exit;
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	
	public function update_loan_advance_rejected_hr($lid, $reject_reason)
	{
		$data = array(
			'hr_status' => '2',
			'hr_rej_msg' => $reject_reason
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	
	public function get_my_resignation_application_details()
	{
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE (i.login_id = '".$this->session->userdata('user_id')."' OR reporting_to = '".$this->session->user_id."') ORDER BY l.rid DESC"); 
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_my_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
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
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE (i.login_id = '".$this->session->userdata('user_id')."' OR reporting_to = '".$this->session->user_id."') AND $cond  ORDER BY l.rid DESC"); 
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_rm_resignation_application_details()
	{
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE ( reporting_to = '".$this->session->user_id."')  ORDER BY l.rid DESC"); 
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_rm_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
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
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `resignation` l LEFT JOIN `internal_user` i ON l.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE (reporting_to = '".$this->session->user_id."') AND $cond  ORDER BY l.rid DESC"); 
		$result = $sql->result_array(); 
		return $result;
	}
/* Start online MRF*/
	public function get_online_mrf()
	{
		$sql = $this->db->query("SELECT * FROM `online_mrf` WHERE login_id='".$this->session->userdata('user_id')."'"); 
		$result = $sql->row(); 
		return $result;
	}
	public function update_online_mrf($department,$designation,$branch,$reason_recruitment,$no_vacancies,$justification,$job_description,$essential_qualification,$essential_length_experience,$essential_kind_experience,$essential_other,$desirable_qualification,$desirable_length_experience,$desirable_kind_experience,$desirable_other,$time_period,$loginID,$mid)
	{
		$sql = $this->db->query("UPDATE `online_mrf` SET department = '".$department."', 
								designation = '".$designation."', branch= '".$branch."',reason_recruitment = '".$reason_recruitment."',
								no_vacancies = '".$no_vacancies."', justification= '".$justification."',job_description = '".$job_description."',
								essential_qualification = '".$essential_qualification."', essential_length_experience= '".$essential_length_experience."',essential_kind_experience = '".$essential_kind_experience."',
								essential_other = '".$essential_other."', desirable_qualification= '".$desirable_qualification."',desirable_length_experience = '".$desirable_length_experience."',    
								desirable_kind_experience = '".$desirable_kind_experience."', mrf_apply_date= '".date('Y-m-d')."', desirable_other= '".$desirable_other."',time_period = '".date("Y-m-d",strtotime($time_period))."'
								WHERE login_id='".$loginID."' AND mid='".$mid."'"); 
		//$result = $sql->result_array(); 
		//return $result;
	}
	public function insert_online_mrf($department,$designation,$branch,$reason_recruitment,$no_vacancies,$justification,$job_description,$essential_qualification,$essential_length_experience,$essential_kind_experience,$essential_other,$desirable_qualification,$desirable_length_experience,$desirable_kind_experience,$desirable_other,$time_period)
	{
		$sql = $this->db->query("INSERT INTO `online_mrf` SET login_id='".$this->session->userdata('user_id')."', department = '".$department."', 
								designation = '".$designation."', branch= '".$branch."',reason_recruitment = '".$reason_recruitment."',
								no_vacancies = '".$no_vacancies."', justification= '".$justification."',job_description = '".$job_description."',
								essential_qualification = '".$essential_qualification."', essential_length_experience= '".$essential_length_experience."',essential_kind_experience = '".$essential_kind_experience."',
								essential_other = '".$essential_other."', desirable_qualification= '".$desirable_qualification."',desirable_length_experience = '".$desirable_length_experience."',    
								desirable_kind_experience = '".$desirable_kind_experience."', mrf_apply_date= '".date('Y-m-d')."', desirable_other= '".$desirable_other."',time_period = '".date("Y-m-d",strtotime($time_period))."'"); 
		//$result = $sql->result_array(); 
		//return $result;
	}
	public function get_reporting_manager($department)
	{
		/* query for reporting manager */
		$sql = $this->db->query("SELECT full_name, email FROM `internal_user` WHERE login_id =(SELECT dept_head FROM `department` WHERE dept_id = '".$department."')");
		$result = $sql->result_array(); 
		return $result;
	} 
	public function get_branch()
	{
		/* query for company locations/branches */
		$sql = $this->db->query("SELECT branch_id, branch_name FROM branch WHERE status = 'A'");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_department()
	{
		/* query for department */
		$sql = $this->db->query("SELECT dept_id,dept_name FROM department WHERE dept_status = '1' ORDER BY dept_name");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_designation()
	{
		/* query for designation */
		$sql = $this->db->query("SELECT desg_id,desg_name FROM user_desg WHERE desg_status = '1' ORDER BY desg_sort_order");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_designation_by_department($dept_id)
	{
		/* query for designation */
		$sql = $this->db->query("SELECT desg_id,desg_name FROM user_desg WHERE desg_status = '1' AND dept_id = '".$dept_id."' ORDER BY desg_sort_order");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_online_room_booking($book_date,$book_time)
	{
		/* query for designation */
		$sql = $this->db->query("SELECT * FROM `online_room_booking` WHERE login_id='".$this->session->userdata('user_id')."' AND book_date='".date('Y-m-d',strtotime($book_date))."' AND book_time='".$book_time."'");
		$result = $sql->result_array(); 
		return $result;
	}
	public function update_online_room_booking($book_date,$book_time,$room_name)
	{
		/* query for designation */
		$sql = $this->db->query("UPDATE `online_room_booking` SET book_date = '".date('Y-m-d',strtotime($book_date))."', 
                        book_time = '".$book_time."', room_name= '".$room_name."'
                        WHERE login_id='".$this->session->userdata('user_id')."'");
		//$result = $sql->result_array(); 
		return 1;
	}
	public function insert_online_room_booking($book_date,$book_time,$room_name)
	{
		/* query for designation */
		$sql = $this->db->query("INSERT INTO `online_room_booking` SET login_id='".$this->session->userdata('user_id')."', book_date = '".date('Y-m-d',strtotime($book_date))."', book_time = '".$book_time."', room_name= '".$room_name."'");
		//$result = $sql->result_array(); 
		return 1;
	}
/* End online MRF*/

/* Start probation period*/ 
	/* query for probation Employee details */
	public function probation_employee_details()
	{
		/* query for designation */
		$sql = $this->db->query("SELECT login_id,loginhandle,full_name FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."' AND confirm_status='Not Confirmed' AND user_status='1' ORDER BY full_name");
		//echo $this->db->last_query();exit;
		$result = $sql->result_array(); 
		return $result;
	}
	public function probation_assessment_details()
	{
		/* query for designation */
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.department, p.*, d.dept_name 
		FROM `internal_user` i 
		RIGHT JOIN `probation_assessment` p ON p.employee_id = i.login_id 
		LEFT JOIN `department` d ON d.dept_id = i.department 
		WHERE p.login_id='".$this->session->userdata('user_id')."' AND i.login_id != '10010'");
		//echo $this->db->last_query();exit;
		$result = $sql->result_array(); 
		return $result;
	}
	public function probation_assessment_details_filter($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
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
		/* query for designation */
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.department, p.*, DATE_FORMAT(apply_date, '%d-%m-%Y') as apply_dates, d.login_id  FROM `internal_user` i RIGHT JOIN `probation_assessment` p ON p.employee_id = i.login_id LEFT JOIN `department` d ON d.dept_id = i.department  WHERE p.login_id='".$this->session->userdata('user_id')."' AND $cond");
		//echo $this->db->last_query();exit;
		$result = $sql->result_array(); 
		return $result;
	}
	public function probation_details($id)
	{
		/* query for probation Employee details */ 
		$sql = $this->db->query("SELECT p.*,i.full_name,i.loginhandle,r.full_name as rmName,r.loginhandle as rmEmpid FROM `probation_assessment` p LEFT JOIN `internal_user` i ON i.login_id=p.employee_id LEFT JOIN `internal_user` r ON r.login_id=p.login_id  WHERE mid = '32'");
		//echo $this->db->last_query();exit;
		$result = $sql->row_array(); 
		return $result;
	}
	public function my_probation_details()
	{
		/* query for probation Employee details */ 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.department, p.* FROM `internal_user` i RIGHT JOIN `probation_assessment` p ON p.employee_id = i.login_id WHERE p.employee_id='".$this->session->user_id."'");
		//echo $this->db->last_query();exit;
		$result = $sql->result_array(); 
		return $result;
	}
	//probation_assessment
	public function probation_assessment($employee_id)
	{
		/* query for designation */
		$sql = $this->db->query("SELECT * FROM `probation_assessment` WHERE login_id='".$this->session->userdata('user_id')."' AND employee_id='".$employee_id."' ORDER BY mid DESC");
		$result = $sql->result_array(); 
		return $result;
	} 
	public function insert_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,    $cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id)
	{
		/* query for designation */
		$sql = $this->db->query("INSERT INTO `probation_assessment` 
						SET probation_type= '".$probation_type."',4thweek = '".date("Y-m-d",strtotime($fourweek))."',8thweek = '".date("Y-m-d",strtotime($eightweek))."',12thweek = '".date("Y-m-d",strtotime($twelveweek))."', 
                        quantity_work_4thweek = '".$quantity_work_4thweek."', quantity_work_8thweek= '".$quantity_work_8thweek."',quantity_work_12thweek = '".$quantity_work_12thweek."',
                        problem_solving_4thweek = '".$problem_solving_4thweek."', problem_solving_8thweek= '".$problem_solving_8thweek."',problem_solving_12thweek = '".$problem_solving_12thweek."',
                        motivation_employees_4thweek = '".$motivation_employees_4thweek."', motivation_employees_8thweek= '".$motivation_employees_8thweek."',motivation_employees_12thweek = '".$motivation_employees_12thweek."',
                        responsibility_4thweek = '".$responsibility_4thweek."', responsibility_8thweek= '".$responsibility_8thweek."',responsibility_12thweek = '".$responsibility_12thweek."',
                        quality_work_4thweek = '".$quality_work_4thweek."', quality_work_8thweek= '".$quality_work_8thweek."',quality_work_12thweek = '".$quality_work_12thweek."',
                        knowledge_job_4thweek = '".$knowledge_job_4thweek."', knowledge_job_8thweek= '".$knowledge_job_8thweek."',knowledge_job_12thweek = '".$knowledge_job_12thweek."',
                        relations_supervisor_4thweek = '".$relations_supervisor_4thweek."', relations_supervisor_8thweek= '".$relations_supervisor_8thweek."',relations_supervisor_12thweek = '".$relations_supervisor_12thweek."',    
                        cooperation_others_4thweek = '".$cooperation_others_4thweek."', cooperation_others_8thweek= '".$cooperation_others_8thweek."',cooperation_others_12thweek = '".$cooperation_others_12thweek."',
                        attendance_reliability_4thweek = '".$attendance_reliability_4thweek."', attendance_reliability_8thweek= '".$attendance_reliability_8thweek."',attendance_reliability_12thweek = '".$attendance_reliability_12thweek."',
                        initiative_creativity_4thweek = '".$initiative_creativity_4thweek."', initiative_creativity_8thweek= '".$initiative_creativity_8thweek."',initiative_creativity_12thweek = '".$initiative_creativity_12thweek."',
                        capacity_develop_4thweek = '".$capacity_develop_4thweek."', capacity_develop_8thweek= '".$capacity_develop_8thweek."',capacity_develop_12thweek = '".$capacity_develop_12thweek."',
                        employee_performance = '".$employee_performance."', your_expectations= '".$your_expectations."',need_improvement = '".$need_improvement."',
                        additional_training = '".$additional_training."', employee_reaction= '".$employee_reaction."',set_employee_goals = '".$set_employee_goals."',
                        expectations = '".$expectations."',improvement = '".$improvement."',training = '".$training."',reaction = '".$reaction."',satisfactorily = '".$satisfactorily."',
                        employee_satisfactorily = '".$employee_satisfactorily."', additional_comments= '".$additional_comments."', employee_id= '".$employee_id."', login_id='".$this->session->userdata('user_id')."'");
		//$result = $sql->result_array(); 
		//return $result;
	}
	public function update_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,    $cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id)
	{
		/* query for designation */
		$sql = $this->db->query("UPDATE `probation_assessment` SET probation_type= '".$probation_type."',4thweek = '".date("Y-m-d",strtotime($fourweek))."',8thweek = '".date("Y-m-d",strtotime($eightweek))."',12thweek = '".date("Y-m-d",strtotime($twelveweek))."', 
                        quantity_work_4thweek = '".$quantity_work_4thweek."', quantity_work_8thweek= '".$quantity_work_8thweek."',quantity_work_12thweek = '".$quantity_work_12thweek."',
                        problem_solving_4thweek = '".$problem_solving_4thweek."', problem_solving_8thweek= '".$problem_solving_8thweek."',problem_solving_12thweek = '".$problem_solving_12thweek."',
                        motivation_employees_4thweek = '".$motivation_employees_4thweek."', motivation_employees_8thweek= '".$motivation_employees_8thweek."',motivation_employees_12thweek = '".$motivation_employees_12thweek."',
                        responsibility_4thweek = '".$responsibility_4thweek."', responsibility_8thweek= '".$responsibility_8thweek."',responsibility_12thweek = '".$responsibility_12thweek."',
                        quality_work_4thweek = '".$quality_work_4thweek."', quality_work_8thweek= '".$quality_work_8thweek."',quality_work_12thweek = '".$quality_work_12thweek."',
                        knowledge_job_4thweek = '".$knowledge_job_4thweek."', knowledge_job_8thweek= '".$knowledge_job_8thweek."',knowledge_job_12thweek = '".$knowledge_job_12thweek."',
                        relations_supervisor_4thweek = '".$relations_supervisor_4thweek."', relations_supervisor_8thweek= '".$relations_supervisor_8thweek."',relations_supervisor_12thweek = '".$relations_supervisor_12thweek."',    
                        cooperation_others_4thweek = '".$cooperation_others_4thweek."', cooperation_others_8thweek= '".$cooperation_others_8thweek."',cooperation_others_12thweek = '".$cooperation_others_12thweek."',
                        attendance_reliability_4thweek = '".$attendance_reliability_4thweek."', attendance_reliability_8thweek= '".$attendance_reliability_8thweek."',attendance_reliability_12thweek = '".$attendance_reliability_12thweek."',
                        initiative_creativity_4thweek = '".$initiative_creativity_4thweek."', initiative_creativity_8thweek= '".$initiative_creativity_8thweek."',initiative_creativity_12thweek = '".$initiative_creativity_12thweek."',
                        capacity_develop_4thweek = '".$capacity_develop_4thweek."', capacity_develop_8thweek= '".$capacity_develop_8thweek."',capacity_develop_12thweek = '".$capacity_develop_12thweek."',
                        employee_performance = '".$employee_performance."', your_expectations= '".$your_expectations."',need_improvement = '".$need_improvement."',
                        additional_training = '".$additional_training."', employee_reaction= '".$employee_reaction."',set_employee_goals = '".$set_employee_goals."',
                        expectations = '".$expectations."',improvement = '".$improvement."',training = '".$training."',reaction = '".$reaction."',satisfactorily = '".$satisfactorily."',
                        employee_satisfactorily = '".$employee_satisfactorily."', additional_comments= '".$additional_comments."'
                        WHERE login_id='".$this->session->userdata('user_id')."' AND employee_id= '".$employee_id."'");
		//$result = $sql->result_array(); 
		//return $result;
	}
/* End probation_assessment*/
/* Start midyear*/
	public function get_midyear_review_data()
	{
		/* select midyear data according to employee login id*/
		$sql = $this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$this->session->userdata('user_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'");
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
	
	public function update_midyear_review_data($accomplishments,$contributions,$unplanned_events)
	{
		//its for save the data
		$sql = $this->db->query("UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
                             contributions = '".$contributions."', unplanned_events= '".$unplanned_events."' WHERE login_id='".$this->session->userdata('user_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'");
		$result = $sql->result_array(); 
		return $result;
	} 
	public function insert_midyear_review_data($accomplishments,$contributions,$unplanned_events,$exceeding_expectation,$improvement,$discussion,$summary_expectation,$employee_development)
	{
		//its for save the data
		$sql = $this->db->query("INSERT INTO `midyear_review` SET login_id='".$this->session->userdata('user_id')."', accomplishments = '".$accomplishments."', contributions = '".$contributions."', unplanned_events= '".$unplanned_events."',exceeding_expectation = '".$exceeding_expectation."',improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',employee_development = '".$employee_development."', apply_date=now()");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_midyear_review()
	{ 
		$s_year=date("Y"); 
		$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$s_year";
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE om.unique_pin != '' AND i.login_id != '10010' AND i.reporting_to='".$this->session->user_id."' $cond ORDER BY om.apply_date DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_midyear_review_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		$yy = date("Y");
		if($searchYear == ""){
			$s_year=$yy; 
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$s_year";
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
		
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE om.unique_pin != '' AND i.login_id != '10010' AND i.reporting_to='".$this->session->user_id."' $cond");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_my_midyear_review()
	{ 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.rm_status, om.dh_status, om.mid,om.apply_date, om.remark, dp.dept_id 
		FROM `internal_user` i 
		RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.login_id != '10010' AND om.unique_pin !='' AND om.login_id = '".$this->session->user_id."'");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_my_midyear_review_search($searchYear)
	{
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.rm_status, om.dh_status, om.mid,om.apply_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.login_id != '10010' AND om.unique_pin !='' AND om.login_id = '".$this->session->user_id."' $cond ");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_annual_appraisal()
	{
		$yy = date("Y"); 
		$e_year=$yy; 
		$cond = "";
		/* if($this->session->userdata('isDepartmentHead') == 'YES'){
			$cond = " AND i.department='".$this->session->userdata('department')."'";
		}
		else{
			$cond = " AND (i.reporting_to = '".$this->session->userdata('user_id')."' OR (i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."'))))";
		} */
		//$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
		/* $sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id 
		FROM `internal_user` i 
		RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		WHERE om.unique_pin != '' AND i.login_id != '10010' AND (i.reporting_to = '".$this->session->userdata('user_id')."' OR (i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."')))) $cond ORDER BY om.mid DESC"); */
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id 
		FROM `internal_user` i 
		RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		WHERE om.unique_pin != '' AND i.login_id != '10010' AND i.reporting_to = '".$this->session->userdata('user_id')."'  $cond ORDER BY om.mid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_annual_appraisal_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
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
		
		
		/* $sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE om.unique_pin != '' AND i.login_id != '10010' AND (i.reporting_to = '".$this->session->user_id."' OR (i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->user_id."')))) $cond "); */
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE om.unique_pin != '' AND i.login_id != '10010' AND i.reporting_to = '".$this->session->user_id."'  $cond ORDER BY om.mid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_annual_appraisal_dh()
	{
		$yy = date("Y"); 
		$e_year=$yy; 
		$cond = "";
		/* if($this->session->userdata('isDepartmentHead') == 'YES'){
			$cond = " AND i.department='".$this->session->userdata('department')."'";
		}
		else{
			$cond = " AND (i.reporting_to = '".$this->session->userdata('user_id')."' OR (i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."'))))";
		} */
		//$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id 
		FROM `internal_user` i 
		RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		WHERE om.unique_pin != '' AND i.login_id != '10010' AND ((i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."'))))  $cond ORDER BY om.mid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	public function get_annual_appraisal_search_dh($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear)
	{
		
		$yy = date("Y");
		if($searchYear == ""){
			$e_year=$yy; 
		}
		else{
			$d_year=explode('-',$searchYear);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$cond = " AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
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
		
		
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id 
		FROM `internal_user` i 
		RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		WHERE om.unique_pin != '' AND i.login_id != '10010'   AND ((i.login_id IN ( SELECT login_id FROM `internal_user` WHERE reporting_to IN ( SELECT login_id FROM `internal_user` WHERE reporting_to = '".$this->session->userdata('user_id')."'))))  $cond ORDER BY om.mid DESC");
		$result = $sql->result_array(); 
		return $result;
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
	public function get_my_annual_appraisal()
	{ 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.rm_status, om.dh_status, om.mid,om.apply_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.login_id != '10010' AND om.login_id = '".$this->session->user_id."' ORDER BY om.mid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
/* End midyear*/

/* Start loan/advance */
	// check loan/advance less than 2
	public function check_loan_advance_less_than_two()
	{
		$sql = $this->db->query("Select * From `loan_advance_apply` WHERE login_id='".$this->session->userdata('user_id')."' AND rm_status='1' AND dh_status='1' AND hr_status='1' AND ac_status='1'");
		$row = $sql->result_array();
		return $row;
	}
	public function check_loan_eligibility()
	{
		$sql = $this->db->query("Select * From `loan_advance_apply` WHERE login_id='".$this->session->userdata('user_id')."' AND (rm_status ='0' OR (rm_status ='1' AND dh_status ='0') OR (dh_status ='1' AND hr_status ='0') OR (hr_status ='1' AND ac_status ='0')) AND applyfor='loan' ");
		$row = $sql->result_array();
		return $row;
	}
	public function check_advance_eligibility()
	{
		$sql = $this->db->query("Select * From `loan_advance_apply` WHERE login_id='".$this->session->userdata('user_id')."' AND (rm_status ='0' OR (rm_status ='1' AND dh_status ='0') OR (dh_status ='1' AND hr_status ='0') OR (hr_status ='1' AND ac_status ='0')) AND applyfor='advance' ");
		$row = $sql->result_array();
		return $row;
	}
	public function check_advance_eligibility_latest()
	{
		$sql = $this->db->query("Select * From `loan_advance_apply` WHERE login_id='".$this->session->userdata('user_id')."' AND (rm_status ='0' OR (rm_status !='2' AND dh_status ='0') OR (rm_status !='2' AND dh_status !='2' AND hr_status ='0') OR (rm_status !='2' AND dh_status !='2' AND hr_status !='2' AND ac_status ='0')) AND applyfor='advance' ");
		$row = $sql->result_array();
		return $row;
	}
	
	public function insert_loan_advance_apply($txtapplyfor,$txtamountapplied,$txtadvanceamount,$txtadvanceinstalment,$txtmessage)
	{
		$sql = $this->db->query("INSERT INTO `loan_advance_apply` (`login_id`,`applyfor`,`amountapplied`,`eligibleamount`,`eligibleinstalment`,`message`,`created_date`) VALUES('".$this->session->userdata('user_id')."', '".$txtapplyfor."', '".$txtamountapplied."',  '".$txtadvanceamount."', '".$txtadvanceinstalment."', '".$txtmessage."', now())");
		//$row = $sql->row();
		//return $row();
	}
	
	public function update_loan_advance_apply()
	{
		$sql = $this->db->query("Select * From `loan_advance_apply` WHERE login_id='".$this->session->userdata('user_id')."' AND rm_status='1' AND dh_status='1' AND hr_status='1' AND ac_status='1'");
		$row = $sql->row();
		return $row();
	}
	
	public function get_my_loan_advance_application()
	{ 
		$sql = $this->db->query("SELECT i.login_id, l.applyfor, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, l.rm_status,l.dh_status,l.hr_status,l.ac_status,l.lid FROM `internal_user` i RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' AND i.login_id = '".$this->session->user_id."' order by l.lid DESC");
		$result = $sql->result_array(); 
		return $result;
	}
	
	public function get_all_loan_advance_approve_reject()
	{
		$loginID = $this->session->userdata('user_id');
		$dhSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '6'"); 
		$dh = $dhSql->result_array();
		$reporting_head = $dh[0]['login_id']; 

		$hrSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '2'"); 
		$hr = $hrSql->result_array();
		$hr_manager = $hr[0]['login_id']; 
		
		$acSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '4'"); 
		$ac = $acSql->result_array();  
		$ac_manager = $ac[0]['login_id']; 
		
		$adSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '5'"); 
		$ad = $adSql->result_array();  
		$ad_manager = $ad[0]['login_id']; 
		
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* 
		FROM `internal_user` i 
		LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND (i.login_id = '".$loginID."' OR reporting_to = '".$loginID."' OR $loginID='".$reporting_head."' OR $loginID='".$hr_manager."' OR $loginID='".$ad_manager."' OR $loginID='".$ac[0]['login_id']."' OR $loginID ='10010' OR $loginID ='11018' OR $loginID ='11153') ORDER BY l.lid DESC");
		 
		$result = $sql->result_array();
		return $result;
		
	}
	
	public function get_all_loan_advance_approve_reject_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
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
		
		$loginID = $this->session->userdata('user_id');
		$dhSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '6'"); 
		$dh = $dhSql->result_array();
		$reporting_head = $dh[0]['login_id']; 

		$hrSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '2'"); 
		$hr = $hrSql->result_array();
		$hr_manager = $hr[0]['login_id']; 
		
		$acSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '4'"); 
		$ac = $acSql->result_array();  
		$ac_manager = $ac[0]['login_id']; 
		
		$adSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '5'"); 
		$ad = $adSql->result_array();  
		$ad_manager = $ad[0]['login_id']; 
		
		$sql = $this->db->query("SELECT i.*, d.*,dp.*,l.* FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND (i.login_id = '".$loginID."' OR reporting_to = '".$loginID."' OR $loginID='".$reporting_head."' OR $loginID='".$hr_manager."' OR $loginID='".$ad_manager."' OR $loginID='".$ac[0]['login_id']."' OR $loginID ='10010' OR $loginID ='11018' OR $loginID ='11153') AND $cond  ORDER BY l.lid DESC");
		 
		$result = $sql->result_array();
		return $result;
		
	}
	/* end loan/advance */

	/* Start get goal view */
	public function get_goal_approve_reject()
	{
		$loginID = $this->session->userdata('user_id');
		$dhSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '6'"); 
		$dh = $dhSql->result_array();
		$reporting_head = $dh[0]['login_id']; 

		$hrSql = $this->db->query("SELECT * FROM `department` WHERE dept_id = '2'"); 
		$hr = $hrSql->result_array();
		$hr_manager = $hr[0]['login_id']; 
		$cur_year =date('Y') + 1;
		
		$sql = $this->db->query("SELECT * FROM `goal_sheet` g LEFT JOIN `internal_user` i ON i.login_id=g.login_id WHERE DATE_FORMAT(annualdate, '%Y') ='".$cur_year."' AND ( i.reporting_to = '".$this->session->user_id."' OR $loginID='".$reporting_head."' OR $loginID='".$hr_manager."' OR $loginID ='10010' OR $loginID ='11018' OR $loginID ='11153') GROUP BY g.login_id");
		$result = $sql->result_array(); 
		$returnarr = array();
		for($i=0; $i<count($result); $i++){
			$login_id = $result[$i]['login_id'];
			$sqls = $this->db->query("SELECT * FROM `goal_sheet` g  WHERE DATE_FORMAT(annualdate, '%Y') ='".$cur_year."' AND login_id='$login_id' ");
			$goals = $sqls->result_array(); 
			$returnarr[$i] = array(
				'mid' => $result[$i]['mid'],
				'login_id' => $result[$i]['login_id'],
				'loginhandle' => $result[$i]['loginhandle'],
				'created_date' => $result[$i]['created_date'],
				'rm_status' => $result[$i]['rm_status'],
				'full_name' => $result[$i]['full_name'],
				'goals' => $goals
			);
		} 
		return $returnarr;
	}
	
	
	public function update_goal_sheet_approved_rm($login_id)
	{	 
		$cur_year =date('Y') + 1;
		$data = array(
			'rm_status' => '1' 
		);
		$this->db->where('login_id', $login_id);
		$this->db->where("DATE_FORMAT(annualdate, '%Y') ='".$cur_year."'");
		$this->db->update('goal_sheet', $data);
	}
	/* End get goal view */

	/*start profile list*/ 
	public function get_desg($cond)
	{
	   $sql = $this->db->query("SELECT `desg_id`, `desg_name` FROM user_desg WHERE `desg_status` = '1' $cond ORDER BY `desg_name`");
	   $result = $sql->result_array();
	   return $result;
	}
	public function get_active_employee()
	{
		// Get Active Employees
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.user_status = 2  ORDER BY i.login_id DESC");
		$result = $sql->result_array();
		return $result;
	}
	public function get_inactive_employee()
	{
		// Get Active Employees
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.user_status != 2  ORDER BY i.login_id DESC");
		$result = $sql->result_array();
		return $result;
	}
	public function get_all_employee()
	{
		// Get all Active and inactive Employees
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.user_status , dp.dept_id, dp.dept_name, ds.desg_id, ds.desg_name FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` ds ON ds.desg_id = i.designation WHERE i.user_status != '0' AND i.login_id != '10010' ORDER BY i.login_id DESC");
		$result = $sql->result_array();
		return $result;
	}
	/*public function db_query_loop($query, $prefix, $suffix, $found_str, $default="")
	{
		$output = '';
		$result = $this->db->query($query);
		$output1 = $result->result_array();
		$output .= '<'.$prefix.' value=""> - Select - </'.$prefix.'>';
		while (list($val, $label) = $output1)
		{
			if (is_array($default))
				$selected = !in_array($val,$default) ? '' : $found_str; //$selected = empty($default[$val]) ? '' : $found_str;
			else
				$selected = $val == $default ? $found_str : '';               
			$output .= '<'.$prefix.' value="'.$val.'" '.$selected.'>'.stripslashes($label).$suffix; // .'</'.$prefix.'>'
		}
		return $output;
	}
	public function db_listbox($query, $default="", $suffix="\n")
	{
		return $this->Hr_model->db_query_loop($query, 'option', $suffix, 'selected', $default);
	} */
	public function get_country()
	{
		// Get country
		$sql = $this->db->query("SELECT country_id,country_name FROM country WHERE country_status = '1' ORDER BY country_sort_order");
		$result = $sql->result_array();
		return $result;
	}
	public function get_state()
	{
		// Get state
		$sql = $this->db->query("SELECT state_id,state_name FROM state WHERE state_status = '1' ORDER BY state_sort_order");
		$result = $sql->result_array();
		return $result;
	}
	public function get_grade()
	{
		// Get grade
		$sql = $this->db->query("SELECT grade_id, grade_name FROM grade_mater WHERE `status` = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_level()
	{
		// Get level
		$sql = $this->db->query("SELECT level_id, level_name FROM level_master WHERE `status` = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_qualification()
	{
		// Get qualification
		$sql = $this->db->query("SELECT course_id, course_name FROM course_info WHERE `status` = 'Y' ORDER BY course_type DESC");
		$result = $sql->result_array();
		return $result;
	}
	public function get_source_of_hire()
	{
		// Get source of hire
		$sql = $this->db->query("SELECT source_hire_id, source_hire_name FROM source_hire_master WHERE `status` = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_company_location_branch()
	{
		// Get location and branch
		$sql = $this->db->query("SELECT branch_id, branch_name FROM company_branch WHERE status = 'A'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_designation_status_one()
	{
		// Get country
		$sql = $this->db->query("SELECT desg_id,desg_name FROM user_desg WHERE desg_status = '1' ORDER BY desg_sort_order");
		$result = $sql->result_array();
		return $result;
	}
	public function get_locations()
	{
		// Get country
		$sql = $this->db->query("SELECT branch_id,branch_name FROM company_branch WHERE status = 'A' ORDER BY branch_name");
		$result = $sql->result_array();
		return $result;
	}
	public function show_emp_name()
	{
		//$sql = $this->db->query("SELECT `login_id`, `email`, `full_name` FROM `internal_user` WHERE `loginhandle` = '".$this->input->post('keyword')."'");
		$sql = $this->db->query("SELECT `login_id`, `email`, `full_name` FROM `internal_user` ");
		$result = $sql->result_array();
		return $result;
	}
	public function get_emp_vintage_c($fromDate,$toDate)
	{
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, d.desg_name, i.employee_conform FROM `internal_user` i INNER JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.employee_conform BETWEEN '$fromDate' AND '$toDate' AND i.login_id != '10010' AND i.emp_type = 'C'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_emp_vintage($fromDate,$toDate)
	{
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, d.desg_name FROM `internal_user` i INNER JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status = '1' AND i.join_date BETWEEN '$fromDate' AND '$toDate' AND i.login_id != '10010' AND i.emp_type = 'F'");
		$result = $sql->result_array();
		return $result;
	}
	public function get_active_emp_resume()
	{
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.user_status FROM `internal_user` i RIGHT JOIN `resume_form` rf ON rf.login_id = i.login_id LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status='1'");
		$result = $sql->result_array();
		return $result;
	} 
/*end profile list */

/*Start Reporting page */
	public function board_university()
	{
		/* query for Board/University */
		$sql = $this->db->query("SELECT board_university_id, board_university_name FROM board_university_master WHERE status = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function graduation_level_courses()
	{
		/* query for graduation level courses */
		$sql = $this->db->query("SELECT course_id, course_name FROM course_info WHERE status = 'Y' AND course_type = 'Graduation'");
		$result = $sql->result_array();
		return $result;
	}
	public function highest_qualification()
	{
		/* query for highest Qualification level courses */
		$sql = $this->db->query("SELECT course_id, course_name FROM course_info WHERE status = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function professional_qualification()
	{
		/* query for Professional Qualification */
		$sql = $this->db->query("SELECT course_id, course_name FROM course_info WHERE status = 'Y' AND course_type = 'Professional'");
		$result = $sql->result_array();
		return $result;
	}
	public function reporting()
	{
		/* query for reporting Offices */
		$sql = $this->db->query("SELECT i.login_id, i.full_name FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.user_role < '5' ORDER BY i.full_name ASC");
		$result = $sql->result_array();
		return $result;
	}
	public function reviewing()
	{
		/* query for reviewing Offices */
		$sql = $this->db->query("SELECT DISTINCT(rev.login_id), rev.full_name FROM `internal_user` i
			INNER JOIN `internal_user` r ON i.reporting_to = r.login_id
			INNER JOIN `internal_user` AS rev ON rev.login_id = r.reporting_to
			WHERE i.login_id != '10010' AND i.user_status = '1'
			ORDER BY rev.full_name ASC");
		$result = $sql->result_array();
		return $result;
	}
	public function hod()
	{
		/* query for HOD */
		$sql = $this->db->query("SELECT i.login_id, i.full_name FROM department AS d INNER JOIN internal_user AS i ON d.login_id = i.login_id WHERE d.dept_status = '1' ORDER BY i.full_name ASC");
		$result = $sql->result_array();
		return $result;
	} 
	public function specialization_grade()
	{
		/* query for specialization(Grade) */
		$sql = $this->db->query("SELECT s.specialization_id, s.specialization_name FROM specialization_master AS s INNER JOIN course_info AS c ON c.course_id = s.course_id WHERE s.`status` = 'Y' AND c.course_type = 'Graduation'");
		$result = $sql->result_array();
		return $result;
	}
	public function specialization_professional()
	{
		/* query for specialization (Professional) */
		$sql = $this->db->query("SELECT s.specialization_id, s.specialization_name FROM specialization_master AS s INNER JOIN course_info AS c ON c.course_id = s.course_id WHERE s.`status` = 'Y' AND c.course_type = 'Professional'");
		$result = $sql->result_array();
		return $result;
	}
	public function bank()
	{
		/* query for Source of Bank */
		$sql = $this->db->query("SELECT bank_id, bank_name FROM bank_master WHERE `status` = 'Y'");
		$result = $sql->result_array();
		return $result;
	}
	public function location_of_highest_qualification ()
	{
		/* query for Source of Bank */
		$sql = $this->db->query("SELECT state_id, state_name FROM state WHERE state_status = '1' ORDER BY state_name");
		$result = $sql->result_array();
		return $result;
	}
	public function empDetails_hod_dp($selCols,$cond)
	{ 	 
		/* select hod and deprtment*/
		$sql = $this->db->query("SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond);
		$result = $sql->result_array();
		return $result;
	}
	public function empDetails($selCols,$cond)
	{
		//employee details of reporting
		$sql = $this->db->query("SELECT i.login_id".$selCols." FROM `internal_user` i 
							LEFT JOIN `internal_user_ext` AS ie ON ie.login_id = i.login_id
							LEFT JOIN company_branch AS b ON b.branch_id = i.branch
							LEFT JOIN `internal_user` r ON i.reporting_to = r.login_id
							LEFT JOIN `internal_user` AS rev ON rev.login_id = r.reporting_to
							LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id                         
							LEFT JOIN `family_info` AS f ON f.login_id = i.login_id
							LEFT JOIN `user_desg` u ON u.desg_id = i.designation
							LEFT JOIN `department` d ON d.dept_id = i.department                    
							LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id
							LEFT JOIN `state` sc ON sc.state_id = i.state_region2
							LEFT JOIN `state` s ON s.state_id = i.state_region1
							LEFT JOIN `country` cp ON cp.country_id = i.country1
							LEFT JOIN `country` c ON c.country_id = i.country2
							LEFT JOIN `state` sl ON sl.state_id = i.loc_highest_qual
							LEFT JOIN `grade_mater` AS g ON g.grade_id = i.grade
							LEFT JOIN `level_master` AS l ON l.level_id = i.`level`
							LEFT JOIN source_hire_master AS sh ON sh.source_hire_id = i.source_hire
							LEFT JOIN separation_master AS sp ON sp.separation_id = i.resign_reason
							LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank
							LEFT JOIN course_info AS hq ON hq.course_id = i.highest_qual                    
							WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond."  ORDER BY i.sal_sheet_sl_no");
							//echo $this->db->last_query();
		$result = $sql->result_array();
		//print_r($result);exit;
		return $result;
	}
	
	public function payroll_report_empDetails($selCols,$cond)
	{
		//employee details of reporting
		$sql = $this->db->query("SELECT i.login_id".$selCols." FROM `internal_user` i 
							LEFT JOIN `internal_user_ext` AS ie ON ie.login_id = i.login_id
							LEFT JOIN company_branch AS b ON b.branch_id = i.branch               
							LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id
							LEFT JOIN `salary_sheet` AS sas ON sas.login_id = i.login_id                   
							LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
							LEFT JOIN `department` d ON d.dept_id = i.department                    
							LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id             
							LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank                                  
							WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond."  ORDER BY i.sal_sheet_sl_no");
							//echo $this->db->last_query();
		$result = $sql->result_array();
		return $result;
	}
	/**
	* Check For Name Abbr
	*
	*
	* @param	string
	* @return	bool
	*/
	public function check_unique_name_abbr($abbrText)
	{
		$sql = $this->db->query("SELECT `login_id` FROM `internal_user` WHERE `name_abbr` = '$abbrText' LIMIT 1"); 
		$nameNum = $sql->row();
		if($nameNum == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	public function get_all_reporting_manager()
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.user_role < '5' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		//$reportingArray = '';
		/* foreach($reportingInfoSelect as $row)
		{
			$reportingArray .= ",['". $row['dispName']."', ".$row['login_id']."]";
		}
		$r *///eportingArray = substr($reportingArray, 1); 
		return $reportingInfoSelect;
	}
	
	/*end Reporting page */

	
	public function check_employee($txtEmpSearch, $user_id)
	{
		$this->db->select('internal_user.full_name');		
		$this->db->from('internal_user');				
		$this->db->where('loginhandle', $txtEmpSearch);			
		$query = $this->db->get();		
		$result = $query->result(); 
		return $result; 
	}
	
	public function get_all_late_coming() 
	{ 
		$txtYearMonth = date('m',strtotime(date('Y').'-'.date('m').'-01')); 
		$cond = " AND DATE_FORMAT(`date`, '%Y-%m') =  '$txtYearMonth'";
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' AND i.login_id != 10010 $cond ORDER BY a.date DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	
	public function get_all_late_coming_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode, $searchMonth, $searchYear)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		if($searchStartDate !='' && $searchEndDate !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate));   
			$bet = "date BETWEEN '$txtSdate' AND '$txtEdate'";
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."date BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		if($searchMonth !='')
		{   
			$txtMonth = date('m',strtotime(date('Y').'-'.$searchMonth.'-01')); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond." DATE_FORMAT(`date`, '%m') =  '$txtMonth'";
		}
		if($searchYear !='')
		{   
			$txtYear = date('Y',strtotime($searchYear.'-'.date('m').'-01')); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond." DATE_FORMAT(`date`, '%Y') =  '$txtYear'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' $cond ORDER BY a.date DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	
	public function get_all_absent_details() 
	{ 
		$txtSdate = $txtEdate =date('Y-m-d');
        //$cond = " AND date BETWEEN '$txtSdate' AND '$txtEdate'";
        $cond = " AND DATE_FORMAT(a.date, '%Y') = '".date('Y')."' ";
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date, a.att_status FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' AND i.login_id != 10010  $cond ORDER BY a.date DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_all_absent_details_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = "i.login_id != '10010' AND  DATE_FORMAT(date, '%Y') = '".date('Y')."' ";
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		if($searchStartDate !='' && $searchEndDate !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate));   
			$bet = "date BETWEEN '$txtSdate' AND '$txtEdate'";
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."date BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date, a.att_status FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' AND $cond ORDER BY a.date DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_leave_status_info() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, l.leave_from, l.leave_to, l.status FROM `internal_user` i INNER JOIN `leave_application` l ON i.login_id = l.user_id WHERE i.user_status != '0' ORDER BY l.leave_from DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	public function get_leave_status_info_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode, $searchStatus)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		$cond = " AND i.login_id != '10010' ";
		//$cond = "i.login_id != '10010' ";
		if($searchName !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.full_name LIKE '%".$searchName."%'";
		}
		if($searchStatus !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."l.status ='".$searchStatus."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		if($searchStartDate !='' && $searchEndDate !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate)); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."l.leave_from BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, l.leave_from, l.leave_to, l.status FROM `internal_user` i INNER JOIN `leave_application` l ON i.login_id = l.user_id WHERE i.user_status != '0' $cond ORDER BY l.leave_from DESC"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_leave_provision() 
	{
		$yy = date("Y");
		$e_year = $yy; 
		//$e_year = $yy+1; 
		$sDate = $e_year.'-03-31'; 
		$encypt = $this->config->item('masterKey');
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.user_status, l.cf_pl, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary FROM `internal_user` i LEFT JOIN `leave_carry_ forward` l ON l.user_id = i.login_id LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.user_status='1' AND i.emp_type = 'F' AND i.join_date <= '$sDate' AND i.login_id != '10010' AND l.year = '$e_year'"); 
		$result = $sql->result_array();  
		$return_data = array();
		for($k=0; $k<count($result); $k++){
			$feb =0;
			$reFeb=$this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='".$e_year."-02'");
			$roFebRes =  $reFeb->result_array();
			if(COUNT($roFebRes) > 0){
				$feb2 = $feb1= array();
				$febex2 = explode(', ',$roFebRes[0]['userIDsOf2Leave']);
				 for($i =0; $i< count($febex2) ;$i++){
					  array_push($feb2,$febex2[$i]);
				 }
				 $febex1 = explode(', ',$roFebRes[0]['userIDsOf1Leave']);
				 for($j =0; $j< count($febex1) ;$j++){
					 array_push($feb1,$febex1[$j]);
				 }
				 if(in_array($result[$k]['login_id'],$feb2)){
					$feb=2;
				} elseif(in_array($result[$k]['login_id'],$feb1)){
					$feb=1;
				}
			} else {
				$feb = 0;
			}
			#---> FEB	
			$mar =0;
			$reMar=$this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='".$e_year."-03'"); 
			$roMarRes=$reMar->result_array();
			if(COUNT($roFebRes) > 0){
				$mar2 = $mar1= array();                                    
				$marex2 = explode(', ',$roMarRes[0]['userIDsOf2Leave']);
				for($i =0; $i< count($marex2) ;$i++){
					 array_push($mar2,$marex2[$i]);
				}
				$marex1 = explode(', ',$roMarRes[0]['userIDsOf1Leave']);
				 for($j =0; $j< count($marex1) ;$j++){
					 array_push($mar1,$marex1[$j]);
				 }    
				 if(in_array($result[$k]['login_id'],$mar2)){
					$mar=2;
				}elseif(in_array($result[$k]['login_id'],$mar1)){
					$mar=1;
				}
			} else {
				$mar = 0;
			}		
			 #---->MAR	
			$leave_availed =0;
			$reSpt=$this->db->query("SELECT * from `leave_info` where login_id= '".$result[$k]['login_id']."' AND (month='1' OR month='2' OR month='3') AND year='".$e_year."'");
			$roWpt = $reSpt->result_array();
			for($i=0;$i<COUNT($roWpt);$i++){
				$leave_availed = $leave_availed + $roWpt[0]['ob_pl'];
			}
			#----> LEAVE APPLIED THIS YEAR
			$tot_leave_yr = $feb + $mar;
			$cumu = $result[$k]['cf_pl'] + $tot_leave_yr - $leave_availed;
			$leave_amt = round(($result[$k]['gross_salary']/30)*($result[$k]['cf_pl']+$tot_leave_yr-$leave_availed));
			$return_data[] = array(
				'login_id' => $result[$k]['login_id'],
				'name' => $result[$k]['full_name'],
				'loginhandle' => $result[$k]['loginhandle'],
				'doj' => $result[$k]['join_date'],
				'tot_leave' => $result[$k]['cf_pl'],
				'feb' => $feb,
				'mar' => $mar,
				'tot_leave_yr'=> $tot_leave_yr,
				'leave_availed'=>$leave_availed,
				'cumu'=>$cumu,
				'gross'=>$result[$k]['gross_salary'],
				'leave_amt'=> $leave_amt
			);
		}
		return $return_data; 
	}
	
	public function get_leave_provision_search($yy,$searchName) 
	{
		$d_year=explode('-',$yy);
		$s_year=$d_year[0];
		$e_year=$d_year[1];
		#echo $e_year; exit;
		if($searchName != ""){
			$sName  = $searchName;
		} else {
			$sName = "";
		}
		$sDate = $e_year.'-03-31'; 
		$encypt = $this->config->item('masterKey');
		$sql = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.user_status, l.cf_pl, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary FROM `internal_user` i LEFT JOIN `leave_carry_ forward` l ON l.user_id = i.login_id LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.user_status='1' AND i.emp_type = 'F' AND i.join_date <= '$sDate' AND i.login_id != '10010' AND l.year = '$e_year' AND i.full_name LIKE '%".$sName."%'";
		$sqlRes =  $this->db->query($sql);
		$result = $sqlRes->result_array();  
		$return_data = array();
		for($k=0; $k<count($result); $k++){
			$feb =0;
			$reFeb=$this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='".$e_year."-02'");
			$roFebRes =  $reFeb->result_array();
			if(COUNT($roFebRes) > 0){
				$feb2 = $feb1= array();
				$febex2 = explode(', ',$roFebRes[0]['userIDsOf2Leave']);
				 for($i =0; $i< count($febex2) ;$i++){
					  array_push($feb2,$febex2[$i]);
				 }
				 $febex1 = explode(', ',$roFebRes[0]['userIDsOf1Leave']);
				 for($j =0; $j< count($febex1) ;$j++){
					 array_push($feb1,$febex1[$j]);
				 }
				 if(in_array($result[$k]['login_id'],$feb2)){
					$feb=2;
				} elseif(in_array($result[$k]['login_id'],$feb1)){
					$feb=1;
				}
			} else {
				$feb = 0;
			}
			#---> FEB	
			$mar =0;
			$reMar=$this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='".$e_year."-03'"); 
			$roMarRes=$reMar->result_array();
			if(COUNT($roMarRes) > 0) {
				$mar2 = $mar1= array();                                    
				$marex2 = explode(', ',$roMarRes[0]['userIDsOf2Leave']);
				for($i =0; $i< count($marex2) ;$i++){
					 array_push($mar2,$marex2[$i]);
				}
				$marex1 = explode(', ',$roMarRes[0]['userIDsOf1Leave']);
				 for($j =0; $j< count($marex1) ;$j++){
					 array_push($mar1,$marex1[$j]);
				 }    
				 if(in_array($result[$k]['login_id'],$mar2)){
					$mar=2;
				}elseif(in_array($result[$k]['login_id'],$mar1)){
					$mar=1;
				}
				} else {
				$mar = 0;
			}			
			 #---->MAR	
			$leave_availed =0;
			$reSpt=$this->db->query("SELECT * from `leave_info` where login_id= '".$result[$k]['login_id']."' AND (month='1' OR month='2' OR month='3') AND year='".$e_year."'");
			$roWpt = $reSpt->result_array();
			for($i=0;$i<COUNT($roWpt);$i++){
				$leave_availed = $leave_availed + $roWpt[0]['ob_pl'];
			}
			#----> LEAVE APPLIED THIS YEAR
			$tot_leave_yr = $feb + $mar;
			$cumu = $result[$k]['cf_pl'] + $tot_leave_yr - $leave_availed;
			$leave_amt = round(($result[$k]['gross_salary']/30)*($result[$k]['cf_pl']+$tot_leave_yr-$leave_availed));
			$return_data[] = array(
				'login_id' => $result[$k]['login_id'],
				'name' => $result[$k]['full_name'],
				'loginhandle' => $result[$k]['loginhandle'],
				'doj' => $result[$k]['join_date'],
				'tot_leave' => $result[$k]['cf_pl'],
				'feb' => $feb,
				'mar' => $mar,
				'tot_leave_yr'=> $tot_leave_yr,
				'leave_availed'=>$leave_availed,
				'cumu'=>$cumu,
				'gross'=>$result[$k]['gross_salary'],
				'leave_amt'=> $leave_amt
			);
		}
		return $return_data; 
	}
	public function get_leave_credit_history()
	{
		$yy = date("Y");
		$e_year = $yy-1; 
		$sql = $this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='2017-03'");
		$roMar = $sql->result_array(); 
		$mar2 = $mar1= array();                                    
		$marex2 = explode(', ',@$roMar[0]['userIDsOf2Leave']);
		for($i =0; $i< count($marex2) ;$i++)    
			array_push($mar2,$marex2[$i]);
		//print_r($mar2);
		$marex1 = explode(', ',@$roMar[0]['userIDsOf1Leave']);
		for($j =0; $j< count($marex1) ;$j++)    
			array_push($mar1,$marex1[$j]); 
		
		$sDate = $e_year.'-03-31'; 
		$encypt = $this->config->item('masterKey');
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.user_status, l.cf_pl, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary FROM `internal_user` i LEFT JOIN `leave_carry_ forward` l ON l.user_id = i.login_id LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.user_status='1' AND i.emp_type = 'F' AND i.join_date <= '$sDate' AND i.login_id != '10010' AND l.year = '$e_year'"); 
		$row = $sql->result_array();
		$mar=0;
		if(in_array(@$row[0]['login_id'],$mar2))
		{
			$mar=2;
		}
		else if(in_array($row[0]['login_id'],$mar1))
		{
			$mar=1;
		} 
		return $mar; 
		//return $result; 
	}
	
	
	public function get_attendance($dd_year,$dd_month){
		$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
		$enddateintime = strtotime('+' . (date('t',$startdateintime) - 1). ' days',$startdateintime);
		$totalDays = intval((date('t',$startdateintime)),10);
		$startdate = date("Y-m-d", $startdateintime);
		$enddate = date("Y-m-d", $enddateintime);
		$YYmm = $dd_year.'-'.$dd_month;
		// Get Employees Attendance Details
		
		$empAttDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.isAttndAllowance, i.lwd_date, i.user_status , i.emp_type 
								FROM `internal_user` i WHERE i.user_status != '0' AND i.user_status !='2' AND i.sal_sheet_sl_no != '0' AND  i.emp_type= 'F' ";
		$empAttDetailsRes = $this->db->query($empAttDetailsQry);
		
		$empAttDetailsRes_arr = $empAttDetailsRes->result_array();
		 
		
	
		$declaredHolidayArray[] = '';
		$holidaySql = "SELECT `dt_event_date` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y-%m') = '$YYmm'";
		$holidayRes = $this->db->query($holidaySql);
		$holidayRes_arr = $holidayRes->result_array();
		foreach($holidayRes_arr as $holidayInfo)
		{
			$declaredHolidayArray[] = $holidayInfo['dt_event_date'];
		}

		$noofHolidays = $this->calculateHolidaysDaysInMonth($dd_year, $dd_month, $declaredHolidayArray);
		$empAttDetailsNum = COUNT($empAttDetailsRes_arr);
		$empAttSummary = Array();
		if($empAttDetailsNum >0)
		{
			foreach($empAttDetailsRes_arr as $empAttDetailsInfo)
			{
				$attndDays = $regDays = $PLDays = $SLDays = 0;
				$attendanceSql = "SELECT `att_status`, COUNT(`attendance_id`) AS total, `leave_type` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm' GROUP BY `att_status`, `leave_type`";
				$attendanceRes = $this->db->query($attendanceSql);
				$attendanceRes_arr = $attendanceRes->result_array();
				$attendanceNum = count($attendanceRes_arr);
				
				if($attendanceNum > 0)
				{
					foreach($attendanceRes_arr as $attendanceInfo)
					{
						if($attendanceInfo['att_status'] == 'P')
						{
							$attndDays = $attendanceInfo['total'];
						}
						elseif($attendanceInfo['att_status'] == 'R')
						{
							$regDays = $attendanceInfo['total'];
						}
						elseif($attendanceInfo['att_status'] == 'H')
						{
							// Get Half Day without intime
							$hDSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm' AND `att_status` = 'H' AND `in_time` = '00:00:00'";
							$hDRES = $this->db->query($hDSQL);
							$hDNUM = count($hDRES);
							$hDayAbsent = 0;
							if($hDNUM > 0){
								$hDayAbsent = $hDNUM / 2;
							}
							$totHDays = $attendanceInfo['total'] / 2;
							$attndDays = $attndDays + $totHDays - $hDayAbsent;
							if($attendanceInfo['leave_type'] == 'P'){
									$PLDays = $PLDays + $totHDays;
							}else{
								  $SLDays = $SLDays + $totHDays;
							}
						}
						elseif($attendanceInfo['att_status'] == 'L')
						{
							if($attendanceInfo['leave_type'] == 'P'){
								  $PLDays = $PLDays + $attendanceInfo['total']; //0
							}else{
								  $SLDays = $SLDays + $attendanceInfo['total'];  //0
							}
						}
					}
				}
				
				if($empAttDetailsInfo['join_date'] > $startdate || (($empAttDetailsInfo['lwd_date'] != '0000-00-00' || $empAttDetailsInfo['lwd_date'] != '1970-01-01') && $empAttDetailsInfo['lwd_date'] < $enddate))
				{
					$newNoofHolidays = $this->calculateHolidaysDaysInMonth($dd_year, $dd_month, $declaredHolidayArray, $empAttDetailsInfo['join_date'], $empAttDetailsInfo['lwd_date']);
						if(($attndDays + $regDays) > 0)
						$attndDays = $attndDays + $regDays + $newNoofHolidays;
				}
				else
				{
					if(($attndDays + $regDays) > 0)
					$attndDays = $attndDays + $regDays + $noofHolidays;
				}
				
				$payDays = $attndDays + $PLDays + $SLDays;
				$absentdays = $totalDays - $payDays;
				$maxPL = $this->getMaxLeave($empAttDetailsInfo['login_id'], 'P', $dd_year);
				$maxSL = $this->getMaxLeave($empAttDetailsInfo['login_id'], 'S', $dd_year);
				$curLeave = $this->getLeaveTaken($empAttDetailsInfo['login_id'], $dd_month, $dd_year, 'A');
				$curPL = $maxPL - $curLeave['ob_pl'];
				$curSL = $maxSL - $curLeave['ob_sl'];
				$avlPL = $curPL + $PLDays;
				$avlSL = $curSL + $SLDays;
				
				$attndPay = 0;			
				if($empAttDetailsInfo['isAttndAllowance'] == 'Y' && $absentdays == '0')
				{
					$leavedays = $PLDays + $SLDays;
					if($leavedays == 0 ){
						$attndPay = 500;
					}else{
						$attndPay = 0;
					}		
				}
				
				$empAttSummary[] = array(
					'loginhandle' => $empAttDetailsInfo['loginhandle'],
					'join_date' =>  date("d-m-Y", strtotime($empAttDetailsInfo['join_date'])),
					'attndDays' =>  $attndDays,
					'regDays' => $regDays,
					'PLDays' => $PLDays,
					'SLDays' => $SLDays,
					'absentdays' => $absentdays,
					'payDays' => $payDays,
					'avlPL' => $avlPL,
					'avlSL' => $avlSL,
					'curPL' => $curPL,
					'curSL' => $curSL,
					'attndPay' => $attndPay,
					'full_name' => $empAttDetailsInfo['full_name'],
					'user_status' => $empAttDetailsInfo['user_status']
									);
									//var_dump($empAttSummary[]);
			}
			
		}
		return $empAttSummary;
	}
	
	public function get_attendance_search($dd_year,$dd_month,$emp_code){
		$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
		$enddateintime = strtotime('+' . (date('t',$startdateintime) - 1). ' days',$startdateintime);
		$totalDays = intval((date('t',$startdateintime)),10);
		$startdate = date("Y-m-d", $startdateintime);
		$enddate = date("Y-m-d", $enddateintime);
		$YYmm = $dd_year.'-'.$dd_month;
		if($emp_code != ''){
			$cond = " AND i.loginhandle = '".$emp_code."'";
		} else {
			$cond = "";
		}
		// Get Employees Attendance Details
		
		$empAttDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.isAttndAllowance, i.lwd_date, i.user_status , i.emp_type 
								FROM `internal_user` i WHERE i.user_status != '0' AND i.user_status !='2' AND i.sal_sheet_sl_no != '0' AND  i.emp_type= 'F' $cond";
		$empAttDetailsRes = $this->db->query($empAttDetailsQry);
		
		$empAttDetailsRes_arr = $empAttDetailsRes->result_array();
		 
		
	
		$declaredHolidayArray[] = '';
		$holidaySql = "SELECT `dt_event_date` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y-%m') = '$YYmm'";
		$holidayRes = $this->db->query($holidaySql);
		$holidayRes_arr = $holidayRes->result_array();
		foreach($holidayRes_arr as $holidayInfo)
		{
			$declaredHolidayArray[] = $holidayInfo['dt_event_date'];
		}

		$noofHolidays = $this->calculateHolidaysDaysInMonth($dd_year, $dd_month, $declaredHolidayArray);
		$empAttDetailsNum = COUNT($empAttDetailsRes_arr);
		$empAttSummary = Array();
		if($empAttDetailsNum >0)
		{
			foreach($empAttDetailsRes_arr as $empAttDetailsInfo)
			{
				$attndDays = $regDays = $PLDays = $SLDays = 0;
				$attendanceSql = "SELECT `att_status`, COUNT(`attendance_id`) AS total, `leave_type` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm' GROUP BY `att_status`, `leave_type`";
				$attendanceRes = $this->db->query($attendanceSql);
				$attendanceRes_arr = $attendanceRes->result_array();
				$attendanceNum = count($attendanceRes_arr);
				
				if($attendanceNum > 0)
				{
					foreach($attendanceRes_arr as $attendanceInfo)
					{
						if($attendanceInfo['att_status'] == 'P')
						{
							$attndDays = $attendanceInfo['total'];
						}
						elseif($attendanceInfo['att_status'] == 'R')
						{
							$regDays = $attendanceInfo['total'];
						}
						elseif($attendanceInfo['att_status'] == 'H')
						{
							// Get Half Day without intime
							$hDSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm' AND `att_status` = 'H' AND `in_time` = '00:00:00'";
							$hDRES = $this->db->query($hDSQL);
							$hDNUM = count($hDRES);
							$hDayAbsent = 0;
							if($hDNUM > 0){
								$hDayAbsent = $hDNUM / 2;
							}
							$totHDays = $attendanceInfo['total'] / 2;
							$attndDays = $attndDays + $totHDays - $hDayAbsent;
							if($attendanceInfo['leave_type'] == 'P'){
									$PLDays = $PLDays + $totHDays;
							}else{
								  $SLDays = $SLDays + $totHDays;
							}
						}
						elseif($attendanceInfo['att_status'] == 'L')
						{
							if($attendanceInfo['leave_type'] == 'P'){
								  $PLDays = $PLDays + $attendanceInfo['total']; //0
							}else{
								  $SLDays = $SLDays + $attendanceInfo['total'];  //0
							}
						}
					}
				}
				
				if($empAttDetailsInfo['join_date'] > $startdate || (($empAttDetailsInfo['lwd_date'] != '0000-00-00' || $empAttDetailsInfo['lwd_date'] != '1970-01-01') && $empAttDetailsInfo['lwd_date'] < $enddate))
				{
					$newNoofHolidays = $this->calculateHolidaysDaysInMonth($dd_year, $dd_month, $declaredHolidayArray, $empAttDetailsInfo['join_date'], $empAttDetailsInfo['lwd_date']);
						if(($attndDays + $regDays) > 0)
						$attndDays = $attndDays + $regDays + $newNoofHolidays;
				}
				else
				{
					if(($attndDays + $regDays) > 0)
					$attndDays = $attndDays + $regDays + $noofHolidays;
				}
				
				$payDays = $attndDays + $PLDays + $SLDays;
				$absentdays = $totalDays - $payDays;
				$maxPL = $this->getMaxLeave($empAttDetailsInfo['login_id'], 'P', $dd_year);
				$maxSL = $this->getMaxLeave($empAttDetailsInfo['login_id'], 'S', $dd_year);
				$curLeave = $this->getLeaveTaken($empAttDetailsInfo['login_id'], $dd_month, $dd_year, 'A');
				$curPL = $maxPL - $curLeave['ob_pl'];
				$curSL = $maxSL - $curLeave['ob_sl'];
				$avlPL = $curPL + $PLDays;
				$avlSL = $curSL + $SLDays;
				
				$attndPay = 0;			
				if($empAttDetailsInfo['isAttndAllowance'] == 'Y' && $absentdays == '0')
				{
					$leavedays = $PLDays + $SLDays;
					if($leavedays == 0 ){
						$attndPay = 500;
					}else{
						$attndPay = 0;
					}		
				}
				
				$empAttSummary[] = array(
					'loginhandle' => $empAttDetailsInfo['loginhandle'],
					'join_date' =>  date("d-m-Y", strtotime($empAttDetailsInfo['join_date'])),
					'attndDays' =>  $attndDays,
					'regDays' => $regDays,
					'PLDays' => $PLDays,
					'SLDays' => $SLDays,
					'absentdays' => $absentdays,
					'payDays' => $payDays,
					'avlPL' => $avlPL,
					'avlSL' => $avlSL,
					'curPL' => $curPL,
					'curSL' => $curSL,
					'attndPay' => $attndPay,
					'full_name' => $empAttDetailsInfo['full_name'],
					'user_status' => $empAttDetailsInfo['user_status']
									);
									//var_dump($empAttSummary[]);
			}
			
		}
		return $empAttSummary;
	}
	
	public function calculateHolidaysDaysInMonth($year = '', $month = '', $declaredHolidayArray, $joinDate = '', $lwDate = '')
	{

		//create a start and an end datetime value based on the input year 
		$startdate = strtotime($year . '-' . $month . '-01');
		$enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
		
		
		if($joinDate != '' && strtotime($joinDate) > $startdate){
			$startdate = strtotime($joinDate);
		}
		
		if($lwDate != '' && $lwDate != '0000-00-00' && strtotime($lwDate) < $enddate){
			$enddate = strtotime($lwDate);
		}
		
		$todaydate = strtotime(date("Y-m-d"));
		if($todaydate < $enddate){
			$enddate = $todaydate;
		}
		$currentdate = $startdate;
		$noofHolidays = 0;
		
		while ($currentdate <= $enddate){
			$YYmmdd = date("Y-m-d", $currentdate);
			if(array_search($YYmmdd, $declaredHolidayArray)){
				$noofHolidays++;
			}

		 $currentdate = strtotime('+1 day', $currentdate);
		} 
		return $noofHolidays;
	}
	
	public function getMaxLeave($userID, $type = 'A', $year = '')
	{
		if($year == '')
		{
			$year = date("Y");
		}
		$joinDtSql = "SELECT i.`join_date`, f.`ob_pl`, f.`ob_sl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '".$year."' WHERE i.`login_id` = '".$userID."'";
		$joinDtRes = $this->db->query($joinDtSql);
		$joinInfo = $joinDtRes->result_array();
		
		if(count($joinInfo)>0){
			$joinDate  = date("d", strtotime($joinInfo[0]['join_date']));
			$joinMonth = date("m", strtotime($joinInfo[0]['join_date']));
			$joinYear  = date("Y", strtotime($joinInfo[0]['join_date']));
			if($year <=2017)
			{
				if($type == 'P')
				{
					$maxLeave = 22;
					$carryForwardLeave = $joinInfo['ob_pl'];
				}
				elseif($type == 'S')
				{
					$maxLeave = 8;
					$carryForwardLeave = $joinInfo['ob_sl'];
				}
				else
				{
					$maxLeave = 30;
					$carryForwardLeave = $joinInfo['ob_pl'] + $joinInfo['ob_sl'];
				}
				if($year > $joinYear)
				{
					$maxLeaveForThisYear = $maxLeave + $carryForwardLeave;
				}
				else
				{
					if($joinDate <= 15)
					{
						$remainingMonth =  12 - ($joinMonth - 1);
					}
					else
					{
						$remainingMonth = 12 - $joinMonth;
					}
					$maxLeaveForThisYear = ceil(($maxLeave / 12 ) * $remainingMonth);
				}
			}
			else
			{
				if($type == 'P')
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_pl'];
				}
				elseif($type == 'S')
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_sl'];
				}
				else
				{
					$maxLeaveForThisYear = $joinInfo[0]['ob_pl'] + $joinInfo[0]['ob_sl'];
				}
			}
		}
		else{
			$maxLeaveForThisYear = 0;
		}

		return $maxLeaveForThisYear;
	}
	
	public function getLeaveTaken($userID, $month, $year, $type = 'C')
	{
		if($type == 'C')
		{
			$leaveSQL = "SELECT `ob_pl`, `ob_sl` FROM `leave_info` WHERE `login_id` = '$userID' AND `month` = '$month' AND `year` = '$year'";
		}
		elseif($type == 'A')
		{
			$leaveSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` <= '$month' AND `year` = '$year'";
		}

		$leaveRES = $this->db->query($leaveSQL);
		$leaveNUM = count($leaveRES->result_array());

		if($leaveNUM > 0)
		{
			$leaveINFO = $leaveRES->result_array();
			if(count($leaveINFO) > 0)
			{
				$leaveINFO = Array ( "ob_pl" => $leaveINFO[0]['ob_pl'], "ob_sl" => $leaveINFO[0]['ob_sl']) ;
			}
			else
			{
				$leaveINFO = Array ( "ob_pl" => 0, "ob_sl" => 0 ) ;
			}
		}
		else
		{
			$leaveINFO = Array ( "ob_pl" => 0, "ob_sl" => 0 ) ;
		}

		return $leaveINFO;
	}
	
	/************   Master   *******************/
	public function get_all_active_employee(){
		$empSQL = $this->db->query("SELECT login_id,full_name,loginhandle From internal_user WHERE login_id != 10010 AND user_status = '1'");
		$empSQLRes = $empSQL->result_array();
		return $empSQLRes;
	}
	/************   END/ Master   *******************/
	
	
	/************   Events   *******************/
	public function get_emp_retired_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.emp_status_type='Retired'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_retired_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.emp_status_type='Retired' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_terminated_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.emp_status_type='Terminated'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_terminated_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.emp_status_type='Terminated' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_transfer_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.emp_status_type='Transferred'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_transfer_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.emp_status_type='Transferred' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_onhold_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.FnF_status='Pending'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_onhold_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.FnF_status='Pending' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_contract_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.emp_type='C'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_emp_contract_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.emp_type='C' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_ex_emp_details() 
	{ 	 
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.user_status !='1' AND i.emp_status_type !='Transferred' AND i.FnF_status ='Cleared'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_ex_emp_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode)
	{
		$txtSdate = $txtEdate =date('Y-m-d');
		//$cond = "i.login_id != '10010'  AND date BETWEEN '$txtSdate' AND '$txtEdate'";
		$cond = " AND i.login_id != '10010' ";
		if($searchDepartment !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.department = '".$searchDepartment."'";
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
			$cond = $cond."i.designation = '".$searchDesignation."'";
		}
		if($searchEmpCode !=""){
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."i.loginhandle = '".$searchEmpCode."'";
		}
		$sql = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name,i.shift ,i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.user_status !='1' AND i.emp_status_type !='Transferred' AND i.FnF_status ='Cleared' $cond"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	/************  END / Events   *******************/
	
	/************  Recuitment   *******************/
	public function get_all_placement_consultant(){
		$place = $this->db->query('SELECT * FROM `placement_consultant` where consultant_status = "1" ORDER BY consultant_name');
		$placeRes = $place->result_array();
		return $placeRes;
	}
	
	public function get_all_location(){
		$sql = $this->db->query("SELECT branch_id, branch_name FROM company_branch WHERE status = 'A'");
		$sqlR = $sql->result_array();
		return $sqlR;
	}
	/************  END / Recuitment   *******************/
	
	
	/************  HR Information   *******************/
	public function get_aabsys_info() 
	{ 	 
		$sql = $this->db->query("SELECT resource_link.*, DATE_FORMAT(resource_link.dttime, '%d/%m/%Y %r') as dttimes FROM resource_link"); 
		$result = $sql->result_array();
		return $result; 
	} 
	
	public function delete_aabsys_docs_info($idd) 
	{ 	 
		$sql = $this->db->query("DELETE FROM resource_link WHERE link_id = '".$idd."'"); 
	}
	public function get_guidelines() 
	{ 	 
		$sql = $this->db->query("SELECT * FROM resource_topic"); 
		$result = $sql->result_array();
		return $result; 
	}
	
	public function get_staff_format_rules() 
	{ 	 
		$sql = $this->db->query("SELECT resource_doc.*, DATE_FORMAT(resource_doc.dttime, '%d/%m/%Y %r') as dttimes FROM  `resource_doc` where topic_id='1' AND flag='1' order by dttime desc"); 
		$result = $sql->result_array();
		return $result; 
	}
	
	public function delete_staff_format_rules($id) 
	{ 	 
		$sql = $this->db->query("DELETE FROM  `resource_doc` where doc_id = '".$id."'"); 
	}
	/************  END / HR Information   *******************/
	
	
	public function get_requirement_types($requirement_type)
	{ 	 
		
		if($requirement_type == ''){
			$refqry = "SELECT * FROM skills_master where status='Y' ";
		}
		else if($requirement_type =='S')
		{
			 $refqry = "SELECT * FROM skills_master where status='Y' ";
		}
		else if($requirement_type =='E')
		{
			 $refqry = "SELECT * FROM course_info ";
		}
		else if($requirement_type =='W')
		{
			 $refqry = "SELECT * FROM experience_master where status='Y' ";
		}
		$sql = $this->db->query($refqry); 
		$result = $sql->result_array();
		return $result;
	}
	
	public function update_mid_year_review_approved_dh($mid)
	{
		$data = array(
			'dh_status' => '1'
		);
		$this->db->where('mid', $mid);
		$this->db->update('midyear_review', $data);
		return $mid;
	}
	
	public function update_mid_year_review_rejected_dh($mid)
	{
		$data = array(
			'dh_status' => '2'
		);
		$this->db->where('mid', $mid);
		$this->db->update('midyear_review', $data);
		return $mid;
	}
	
	
}
