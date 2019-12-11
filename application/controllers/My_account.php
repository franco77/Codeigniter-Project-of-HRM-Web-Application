<?php defined('BASEPATH') OR exit('No direct script access allowed');
class My_account extends MY_Controller 
{  
	public function __construct()
	{
		parent::__construct();
		/******************* Remote Restriction Control ************************/
		$ipAddr=array();
		for($p=1; $p<255;$p++){
			$ip='172.61.24.'.$p;
			array_push($ipAddr,$ip);
			$ip='172.61.25.'.$p;
			array_push($ipAddr,$ip);
		}
		array_push($ipAddr, '203.129.198.75');
		if(!in_array($_SERVER['REMOTE_ADDR'], $ipAddr) && $_SERVER['REMOTE_ADDR'] != '::1' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '111.93.166.70' && $_SERVER['REMOTE_ADDR'] != '220.225.24.37' && $_SERVER['REMOTE_ADDR'] != '123.63.170.114'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		if($_SERVER['REMOTE_ADDR'] == '203.129.198.75'){
			$remoteIcompassQRY = "SELECT `login_id`,`remote_access` FROM `internal_user` WHERE `remote_access` = 'Y' AND `login_id`='".$this->session->userdata('user_id')."'";
			$remoteIcompassRES = $this->db->query($remoteIcompassQRY);
			$remoteIcompassNUM = $remoteIcompassRES->result_array();
			if(count($remoteIcompassNUM) < 1){
				session_destroy();
				redirect(site_url('login/unauthorized'),'refresh');
				exit();
			}
		}
		/******************* END/ Remote Restriction Control ************************/
		
		$this->load->model('Myprofile_model');
		$this->load->model('Hr_model');
		$this->load->model('event_model');
		if(empty($this->session->userdata['user_id']))
		{
			redirect(site_url('login'),'refresh');
		}
		
		// create the data object
		$mViewData = new stdClass();
		//Today Event count 
		$eventToday = $this->event_model->get_news_and_events_today();
		$this->mViewData['countEventToday'] = count($eventToday);
		$this->mViewData['countRegularizationPending'] = $this->event_model->get_my_regularies_pending_count();
		$this->mViewData['countLeavePending'] = $this->event_model->get_my_leave_request_count();
		$this->mViewData['countRegularizationPendingRM'] = $this->event_model->get_all_regularization_request_rm_count();
		$this->mViewData['countLeavePendingRM'] = $this->event_model->get_all_leave_request_rm_count();
	}
	public function index()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'My profile';
		// Set Date of Resign
		if($this->input->post('setDOR') == 'Set DOR')
		{
			$resign_date = date("Y-m-d", strtotime($this->input->post('resign_date')));
			$resign_reason = $this->input->post('selReaSep');
			$this->Myprofile_model->update_date_of_resign($resign_date,$resign_reason);
		}

		// Set last working date
		if($this->input->post('setLWD') == 'Set LWD'){
			$lwdDate = date("Y-m-d", strtotime($this->input->post('lwd_date')));
			$this->Myprofile_model->update_last_working_date($lwdDate);
		}

		// Mark Inactive 
		if($this->input->post('btnSetInactive') == 'INACTIVE' || $this->input->post('btnSetInactive') == 'UPDATE')
		{
			$fnf_status = $this->input->post('FnF_status');
			$emp_status_type = $this->input->post('emp_status_type');
			$this->Myprofile_model->update_mark_inactive($fnf_status,$emp_status_type); 
			header("location:profile_readonly_emp?id=".$_REQUEST['id']);
			exit();
		} 
		
		$this->mViewData['empInfo'] = $this->Myprofile_model->get_general_information_of_user($user_id);
		//var_dump($this->mViewData['empInfo']);
		
		$empInfo = $this->Myprofile_model->get_general_information_of_user($user_id);
		//var_dump($empInfo);

		$gender = 'Female';
		if($empInfo[0]['gender'] == 'M')
		{
			$gender = 'Male';
		}
		$this->mViewData['gender'] = $gender;
		$mStatus = 'Single';
		if($empInfo[0]['marital_status'] == 'M')
		{
			$mStatus = 'Married';
		}
		$this->mViewData['mStatus'] = $mStatus;
		$this->mViewData['age'] = $this->getDifferenceFromNow($empInfo[0]['dob'], 6);
		$this->mViewData['year'] =  $this->getDifferenceFromNow($empInfo[0]['dob']);
		//$this->mViewData['locationHq] = $this->getValue(TAB_STATE,'state_name',"state_id='".$empInfo[0]['loc_highest_qual']."'");
		$this->render('myprofile/profile_readonly_emp_view', 'full_width',$this->mViewData);
	} 
	public function getDifferenceFromNow($date, $format = 0)
	{
		$secondsDifference = time() - strtotime($date) ;
		switch($format){
			// Difference in Seconds
			case 1: 
				return floor($secondsDifference);
			// Difference in Minutes
			case 2: 
				return floor($secondsDifference/60);
			// Difference in Hours    
			case 3:
				return floor($secondsDifference/60/60);
			// Difference in Days    
			case 4:
				return floor($secondsDifference/60/60/24);
			// Difference in Weeks    
			case 5:
				return floor($secondsDifference/60/60/24/7);
			// Difference in Months    
			case 6:            
				 // Assume YYYY-mm-dd - as is common MYSQL format
				$splitStart = explode('-', $date);
				$splitEnd = explode('-',date('Y-m-d') );

				if (is_array($splitStart) && is_array($splitEnd)) {
					$startYear = $splitStart[0];
					$startMonth = $splitStart[1];
					$endYear = $splitEnd[0];
					$endMonth = $splitEnd[1];

					$difYears = $endYear - $startYear;
					$difMonth = $endMonth - $startMonth;

					if (0 == $difYears && 0 == $difMonth) { // month and year are same
						return 0;
					}
					else if (0 == $difYears && $difMonth > 0) { // same year, dif months
						return $difMonth;
					}
					else if (1 == $difYears) {
						$startToEnd = 12 - $startMonth; // months remaining in start year(13 to include final month
						return ($startToEnd + $endMonth); // above + end month date
					}
					else if ($difYears > 1) {
						$startToEnd = 12 - $startMonth; // months remaining in start year 
						$yearsRemaing = $difYears - 1;  // minus the years of the start and the end year
						$remainingMonths = 12 * $yearsRemaing; // tally up remaining months
						$totalMonths = $startToEnd + $remainingMonths + $endMonth; // Monthsleft + full years in between + months of last year
						return $totalMonths;
					}
				}
				else {
					return false;
				}
				//return floor($secondsDifference/60/60/24/7/4);
			// Difference in Years
			default:
				return floor($secondsDifference/365/60/60/24);
		}     
	}
	public function getValue($table_name,$field_name,$where_cond='1',$debug='')
	{
		$value = '';
		$mysql = 'SELECT '.$field_name.' FROM '.$table_name.' WHERE '.$where_cond;
		if($debug == 1) echo($mysql);
		$result = mysql_query($mysql) or die(mysql_error());
		$rec = mysql_num_rows($result);
		if($rec > 0)
		{
		$row = mysql_fetch_array($result);
		$value = stripslashes($row[0]);
		}
		return $value;
	}
	
	public function comp_profile_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Company profile';
		
        $empSql = "SELECT i.*,ie.*,u.desg_name, d.dept_name, d.dept_head, r.full_name AS rmName, r.loginhandle AS rmECode, g.grade_name, l.level_name, (select hod.full_name from internal_user hod where d.dept_head = hod.login_id ) AS hod, sh.source_hire_name, b.branch_name 
                    FROM `internal_user` i
                    LEFT JOIN `internal_user` r ON r.login_id = i.reporting_to
                    LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
                    LEFT JOIN company_branch AS b ON b.branch_id = i.branch
                    LEFT JOIN `department` d ON d.dept_id = i.department
                    LEFT JOIN grade_mater AS g ON g.grade_id = i.grade
                    LEFT JOIN level_master AS l ON l.level_id = i.`level`
                    LEFT JOIN `internal_user_ext` ie ON  i.login_id =ie.login_id 
                    LEFT JOIN source_hire_master AS sh ON sh.source_hire_id = i.source_hire
                    WHERE i.login_id = '".$user_id."'";
        
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		
        $emplogSql = "SELECT i.*,ie.*,u.desg_name, d.dept_name, d.dept_head
                    FROM `internal_user_change_log` i
                    LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
                    LEFT JOIN `department` d ON d.dept_id = i.department 
                    LEFT JOIN `internal_user_ext` ie ON  i.login_id =ie.login_id 
                    WHERE i.login_id = '".$user_id."'";
        
		$emplogRes = $this->db->query($emplogSql);
		$emplogInfo = $emplogRes->result_array();
		$this->mViewData['emplogs'] = $emplogRes->result_array();
		
		$revSql ="SELECT rev.full_name FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$empInfo[0]["reporting_to"]." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		
		$this->render('myprofile/comp_profile_readonly_emp_view', 'full_width',$this->mViewData);
	}
	
	public function comp_profile_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Company profile';
		$successMsg = "";
		if($this->input->post('btnUpdateComp') == 'Update')
		{
			$resign_date_data  = $this->input->post('resign_date');
			if($resign_date_data  != ""){
				$resign_date = date('Y-m-d', strtotime($this->input->post('resign_date')));
			}
			else{
				$resign_date = "0000-00-00";
			}
			$join_date_data  = $this->input->post('txtdoj');
			if($join_date_data  != ""){
				$join_date = date('Y-m-d', strtotime($this->input->post('txtdoj')));
			}
			else{
				$join_date = "0000-00-00";
			}
			$lwd_date_data  = $this->input->post('lwd_date');
			if($lwd_date_data  != ""){
				$lwd_date = date('Y-m-d', strtotime($this->input->post('lwd_date')));
			}
			else{
				$lwd_date = "0000-00-00";
			}
			$employee_conform_data  = $this->input->post('txtConfm');
			if($employee_conform_data  != ""){
				$employee_conform = date('Y-m-d', strtotime($this->input->post('txtConfm')));
			}
			else{
				$employee_conform = "0000-00-00";
			}
			
			$updateIUSql = "UPDATE `internal_user` SET  
				`department` = '".$this->input->post('department')."', 
				`designation` = '".$this->input->post('designation')."',
				`level` = '".$this->input->post('level')."',
				`grade` = '".$this->input->post('grade')."', 
				`join_date` = '".$join_date."',
				`branch` = '".$this->input->post('branch')."', 
				`email` = '".$this->input->post('txtemail')."', 
				`skype` = '".$this->input->post('txtSkype')."', 
				`isPerfomAllowance` = '".$this->input->post('perofmEligb')."', 
				`isAttndAllowance` = '".$this->input->post('attndEligb')."', 
				`reporting_to` = '".$this->input->post('reporting')."', 
				`employee_conform` = '".$employee_conform."',
				`source_hire` = '".$this->input->post('ddlSrcHire')."',
				`confirm_status` = '".$this->input->post('confirm_status')."',
				`remote_access` = '".$this->input->post('rbRemote')."', 
				`emp_status_type`= '".$this->input->post('emp_status_type')."',
				`FnF_status`= '".$this->input->post('FnF_status')."',
				`lwd_date` = '".$lwd_date."',
				`resign_date` = '".$resign_date."'
				WHERE `login_id` = '".$user_id."'";
			//exit;
			$this->db->query($updateIUSql);
			
			$ff_date_data  = $this->input->post('txtff_date');
			if($ff_date_data  != ""){
				$ff_date = date('Y-m-d', strtotime($this->input->post('txtff_date')));
			}
			else{
				$ff_date = "0000-00-00";
			}
			
			$ff_handed_date_data  = $this->input->post('txtff_handed_date');
			if($ff_handed_date_data  != ""){
				$ff_handed_date = date('Y-m-d', strtotime($this->input->post('txtff_handed_date')));
			}
			else{
				$ff_handed_date = "0000-00-00";
			}
			$updateIUSqls = "UPDATE `internal_user_ext` SET  
				`offer_letter_issued` = '".$this->input->post('rboffer_letter_issued')."',
				`appoint_letter_issued` = '".$this->input->post('rbappoint_letter_issued')."', 
				`conf_letter_issued` = '".$this->input->post('rbconf_letter_issued')."',
				`last_promotion`= '".$this->input->post('txtlast_promotion')."',
				`miscunduct_issue`= '".$this->input->post('txtmiscunduct_issue')."',
				`ff_date` = '".$ff_date."', 
				`ff_amount` = '".$this->input->post('txtff_amount')."',
				`ff_handed_date` = '".$ff_handed_date."', 
				`ff_cheque_bank` = '".$this->input->post('txtff_cheque')."'
				WHERE `login_id` = '".$user_id."'";
			//exit;
			$this->db->query($updateIUSqls);

			$successMsg = "Updated Successfully";
		}
		$this->mViewData['successMsg'] = $successMsg;
        $empSql = "SELECT i.*,ie.*,u.desg_name, d.dept_name, r.full_name AS rmName, r.loginhandle AS rmECode, g.grade_name, l.level_name, hod.full_name AS hod, sh.source_hire_name, b.branch_name 
                    FROM `internal_user` i
                    LEFT JOIN `internal_user` r ON r.login_id = i.reporting_to
                    LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
                    LEFT JOIN company_branch AS b ON b.branch_id = i.branch
                    LEFT JOIN `department` d ON d.dept_id = i.department
                    LEFT JOIN `internal_user` hod ON hod.login_id = d.login_id
                    LEFT JOIN grade_mater AS g ON g.grade_id = i.grade
                    LEFT JOIN level_master AS l ON l.level_id = i.`level`
                    LEFT JOIN `internal_user_ext` ie ON  i.login_id =ie.login_id 
                    LEFT JOIN source_hire_master AS sh ON sh.source_hire_id = i.source_hire
                    WHERE i.login_id = '".$user_id."'";
        
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		
		$revSql ="SELECT rev.full_name FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$empInfo[0]["reporting_to"]." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		
		$desSql ="SELECT * FROM user_desg WHERE dept_id = ".$empInfo[0]["department"]."";
		$desRes = $this->db->query($desSql);
		$this->mViewData['desInfo'] = $desRes->result_array();
		
		$gradeSQL = "SELECT grade_id, grade_name FROM grade_mater WHERE `status` = 'Y'";
		$gradeRes = $this->db->query($gradeSQL);
		$this->mViewData['gradeInfo'] = $gradeRes->result_array();
		
		$branchSQL = "SELECT branch_id, branch_name FROM company_branch WHERE status = 'A'";
		$branchRes = $this->db->query($branchSQL);
		$this->mViewData['branchInfo'] = $branchRes->result_array();
		
		$levelSQL = "SELECT level_id, level_name FROM level_master WHERE `status` = 'Y'";
		$levelRes = $this->db->query($levelSQL);
		$this->mViewData['levelInfo'] = $levelRes->result_array();
		
		$source_hireSQL = "SELECT source_hire_id, source_hire_name FROM source_hire_master WHERE `status` = 'Y'";
		$source_hireRes = $this->db->query($source_hireSQL);
		$this->mViewData['source_hireInfo'] = $source_hireRes->result_array();
		 
		$this->mViewData['department'] = $this->event_model->get_department();  
		
		$this->render('myprofile/comp_profile_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/company_js');
	}
	
	public function get_designation()
	{
		$dept_id = $this->input->post('department');
		$result = $this->event_model->get_designation_by_department($dept_id); 
		echo json_encode($result);  		
	}
	
	public function get_specialization()
	{
		$qualification = $this->input->post('qualification');
		$result = $this->event_model->get_specialization_by_qualification($qualification); 
		echo json_encode($result);  		
	}
	
	public function salary_profile_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Salary Profile';
		$userSql = "SELECT i.*, r.full_name AS reporting, u.desg_name, d.dept_name FROM `internal_user` i LEFT JOIN `internal_user` r ON i.reporting_to = r.login_id LEFT JOIN `user_desg` u ON u.desg_id = i.designation LEFT JOIN `department` d ON d.dept_id = i.department WHERE i.login_id = '".$user_id."'";
		$userRes = $this->db->query($userSql);
		$this->mViewData['userInfo'] = $userRes->result_array();
		
		$encypt = $this->config->item('masterKey');

		$empSql = "SELECT i.full_name,i.user_status,i.FnF_status, AES_DECRYPT(fixed_basic, '".$encypt."') AS fixed_basic,
							   AES_DECRYPT(gross_salary, '".$encypt."') AS gross_salary,
							   AES_DECRYPT(basic, '".$encypt."') AS basic,
							   AES_DECRYPT(hra, '".$encypt."') AS hra,     
							   AES_DECRYPT(reimbursement, '".$encypt."') AS reimbursement,
							   AES_DECRYPT(conveyance_allowance, '".$encypt."') AS conveyance_allowance,                      
							   calculation_type, pf_no, uan_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name
					FROM `internal_user` AS i 
					LEFT JOIN `salary_info` AS s ON s.login_id = i.login_id 
					LEFT JOIN bank_master AS b ON b.bank_id = s.bank
					WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();


		$increSql = "SELECT AES_DECRYPT(increament, '".$encypt."') AS increament, year, increament_info_id FROM `emp_increament_info` WHERE login_id = '".$user_id."'";
		$increRes = $this->db->query($increSql);
		$this->mViewData['increRows'] = $increRes->result_array();

		$this->render('myprofile/salary_profile_readonly_emp_view', 'full_width',$this->mViewData); 
	}
	
	public function salary_profile_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Salary Profile';
		$userSql = "SELECT i.*, r.full_name AS reporting, u.desg_name, d.dept_name FROM `internal_user` i LEFT JOIN `internal_user` r ON i.reporting_to = r.login_id LEFT JOIN `user_desg` u ON u.desg_id = i.designation LEFT JOIN `department` d ON d.dept_id = i.department WHERE i.login_id = '".$user_id."'";
		$userRes = $this->db->query($userSql);
		$this->mViewData['userInfo'] = $userRes->result_array();
		
		$encypt = $this->config->item('masterKey');

		$empSql = "SELECT i.full_name,i.user_status,i.FnF_status, AES_DECRYPT(fixed_basic, '".$encypt."') AS fixed_basic,
							   AES_DECRYPT(gross_salary, '".$encypt."') AS gross_salary,
							   AES_DECRYPT(basic, '".$encypt."') AS basic,
							   AES_DECRYPT(hra, '".$encypt."') AS hra,     
							   AES_DECRYPT(reimbursement, '".$encypt."') AS reimbursement,
							   AES_DECRYPT(conveyance_allowance, '".$encypt."') AS conveyance_allowance,                      
							   calculation_type, pf_no, uan_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name, b.bank_id
					FROM `internal_user` AS i 
					LEFT JOIN `salary_info` AS s ON s.login_id = i.login_id 
					LEFT JOIN bank_master AS b ON b.bank_id = s.bank
					WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();

		$bankSql = "SELECT * FROM `bank_master` 
					WHERE status = 'Y'";
		$bankRes = $this->db->query($bankSql);
		$this->mViewData['bankInfo'] = $bankRes->result_array();

		$increSql = "SELECT AES_DECRYPT(increament, '".$encypt."') AS increament, year, increament_info_id FROM `emp_increament_info` WHERE login_id = '".$user_id."'";
		$increRes = $this->db->query($increSql);
		$this->mViewData['increRows'] = $increRes->result_array();

		$this->render('myprofile/salary_profile_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/salary_js');
	}
	
	public function salary_profile_update_emp_submit()
	{ 
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$encypt = $this->config->item('masterKey');
		$checkSql = "SELECT * FROM `salary_info` 
					WHERE login_id = '".$user_id."'";
		$checkRes = $this->db->query($checkSql);
		$checkCount = count($checkRes->result_array());
		if($checkCount > 0){
			$executeSQL = "UPDATE `salary_info` SET 
			`calculation_type` = '".$this->input->post('calculation_type')."', 
			`gross_salary` = AES_ENCRYPT('".$this->input->post('txtgross_salary')."','".$encypt."'), 
			`fixed_basic` = AES_ENCRYPT('".$this->input->post('txtfixed_basic')."','".$encypt."'), 
			`basic` = AES_ENCRYPT('".$this->input->post('txtbasic')."','".$encypt."'), 
			`hra` = AES_ENCRYPT('".$this->input->post('txthra')."','".$encypt."'), 
			`conveyance_allowance` = AES_ENCRYPT('".$this->input->post('txtconveyance_allowance')."','".$encypt."'), 
			`reimbursement` = AES_ENCRYPT('".$this->input->post('reimbursement')."','".$encypt."'), 
			`pf_no` = '".$this->input->post('txtpf_no')."',  
			`uan_no` = '".$this->input->post('txtuan_no')."',  
			`mediclaim_no` = '".$this->input->post('txtmediclaim_no')."',  
			`bank` = '".$this->input->post('selBank')."',  
			`bank_no` = '".$this->input->post('txtbank_no')."',  
			`payment_mode` = '".$this->input->post('payment_mode')."',
			`acc_holder_name` = '".$this->input->post('acc_holder_name')."'
			WHERE `login_id` = '".$user_id."'";
			$this->db->query($executeSQL);
		}
		else{
			$insertIUESql = "INSERT INTO `salary_info` (login_id, calculation_type, fixed_basic, gross_salary, basic, hra, conveyance_allowance, reimbursement, pf_no, uan_no, mediclaim_no, bank, bank_no, payment_mode, acc_holder_name )
			VALUES('$user_id', 
			'".$this->input->post('calculation_type')."', 
			AES_ENCRYPT('".$this->input->post('txtfixed_basic')."','".$encypt."'), 
			AES_ENCRYPT('".$this->input->post('txtgross_salary')."','".$encypt."'), 
			AES_ENCRYPT('".$this->input->post('txtbasic')."','".$encypt."'), 
			AES_ENCRYPT('".$this->input->post('txthra')."','".$encypt."'), 
			AES_ENCRYPT('".$this->input->post('txtconveyance_allowance')."','".$encypt."'), 
			AES_ENCRYPT('".$this->input->post('reimbursement')."','".$encypt."'), 
			'".$this->input->post('txtpf_no')."',  
			'".$this->input->post('txtuan_no')."',  
			'".$this->input->post('txtmediclaim_no')."',  
			'".$this->input->post('selBank')."',  
			'".$this->input->post('txtbank_no')."',  
			'".$this->input->post('payment_mode')."',
			'".$this->input->post('acc_holder_name')."'			
			)";

			$this->db->query($insertIUESql);
		}
		
		$executeSQL1 = "UPDATE `internal_user` SET   
		`ifsc_code` = '".$this->input->post('txtbank_ifsc_code')."' 
		WHERE `login_id` = '".$user_id."'";
		$this->db->query($executeSQL1);
		
		echo 1;
	}
	
	public function salary_profile_increment_add_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$encypt = $this->config->item('masterKey');
		
		$executeSQL1 = "INSERT INTO `emp_increament_info` (login_id,increament,year) VALUES ('$user_id', 
		AES_ENCRYPT('".$this->input->post('txtincreament')."','".$encypt."'), '".date("Y-m-d", 
		strtotime($this->input->post('txtyear')))."')";
		$this->db->query($executeSQL1);
		echo 1;
	}
	
	public function salary_profile_increment_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$encypt = $this->config->item('masterKey');
		
		if($this->input->post('types') == 'update'){
			$executeSQL1 = "UPDATE `emp_increament_info` SET 
			`increament` = AES_ENCRYPT('".$this->input->post('txtincreament')."','".$encypt."'), 
			`year` = '".date("Y-m-d", strtotime($this->input->post('txtyear')))."'  
			WHERE `login_id` = '".$user_id."' AND `increament_info_id` = '".$this->input->post('increament_info_id')."'";
			$this->db->query($executeSQL1);
			echo 1;
		}
		else if($this->input->post('types') == 'delete'){
			$executeSQL = "DELETE from `emp_increament_info`  WHERE `login_id` = '".$user_id."' AND `increament_info_id` = '".$this->input->post('increament_info_id')."'";
			$this->db->query($executeSQL);
			echo 2;
		}
	}
	
	public function education_profile_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Eduction profile';
		$empSql = "SELECT i.full_name,i.user_status, i.designation FROM `internal_user` AS i WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		//var_dump($empInfo );
		$this->mViewData['empInfo'] = $empRes->result_array();

		$eduSql = "SELECT e.*,i.full_name,i.user_status,c.course_name,c.course_type, b.board_university_name, s.specialization_name
				FROM `education_info` e  
				LEFT JOIN `internal_user` i ON e.login_id=i.login_id
				LEFT JOIN course_info c ON c.course_id=e.course_id 
				LEFT JOIN specialization_master s ON s.specialization_id = e.specialization_id 
				LEFT JOIN board_university_master AS b ON b.board_university_id = e.board_id
				WHERE e.login_id = '".$user_id."'";
		$eduRes = $this->db->query($eduSql);
		$eduInfo = $eduRes->result_array();
		//var_dump($eduInfo);
		$this->mViewData['eduInfo']= $eduRes->result_array();
		$eduRows=count($eduInfo);

		//Get Required Education for this employee
		$reqEduSQL = "SELECT c.course_name FROM minimum_requirement AS r INNER JOIN course_info AS c ON c.course_id = r.requirement_type_id WHERE r.designation_id = '".$empInfo[0]["designation"]."' AND r.requirement_type = 'E'";
		$reqEduRES = $this->db->query($reqEduSQL);
		$this->mViewData['reqEduINFO'] = $reqEduRES->result_array();
		$this->mViewData['reqEduNUM'] = count($reqEduRES->result_array());
		$this->render('myprofile/education_profile_readonly_emp_view', 'full_width',$this->mViewData);
	}
	
	public function family_profile_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Famile profile';
		$empSql = "SELECT i.full_name,i.user_status,i.FnF_status, f.* FROM `internal_user` AS i LEFT JOIN `family_info` AS f  ON f.login_id = i.login_id  WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();

		$fatherdob = $motherdob = $spousedob = $anniversarydate = "";
		if($empInfo[0]["fathers_dob"] != "" && $empInfo[0]["fathers_dob"] != "0000-00-00"){
			$this->mViewData['fatherdob'] = date("d M, Y", strtotime($empInfo[0]["fathers_dob"]));
		}
		else{
			$this->mViewData['fatherdob'] = "";
		}
		if($empInfo[0]["mother_dob"] != "" && $empInfo[0]["mother_dob"] != "0000-00-00"){
			$this->mViewData['motherdob'] = date("d M, Y", strtotime($empInfo[0]["mother_dob"]));
		}
		else{
			$this->mViewData['motherdob'] = "";
		}
		if($empInfo[0]["spouse_dob"] != "" && $empInfo[0]["spouse_dob"] != "0000-00-00"){
			$this->mViewData['spousedob'] = date("d M, Y", strtotime($empInfo[0]["spouse_dob"]));
		}
		else{
			$this->mViewData['spousedob'] = "";
		}
		if($empInfo[0]["anniversary_date"] != "" && $empInfo[0]["anniversary_date"] != "0000-00-00"){
			$this->mViewData['anniversarydate'] = date("d M, Y", strtotime($empInfo[0]["anniversary_date"]));
		}
		else{
			$this->mViewData['anniversarydate'] = "";
		}

		$childSql  = "SELECT * FROM `child_info` WHERE login_id = '".$user_id."' ORDER BY child_id DESC";
		$childRes  = $this->db->query($childSql);
		$this->mViewData['childRows'] = count($childRes->result_array());
		$this->mViewData['childInfo'] = $childRes->result_array();
		
		$this->render('myprofile/family_profile_readonly_emp_view', 'full_width',$this->mViewData); 
	}
	
	
	public function job_description_readonly_emp()
	{   
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'JD/Goal';
	
		$empSql = "SELECT i.full_name, i.user_status, i.designation, ie.jd_document, ie.kpi_document, ie.skills FROM `internal_user`
		i LEFT JOIN internal_user_ext AS ie ON ie.login_id = i.login_id  WHERE i.login_id = '".$user_id."' LIMIT 1";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empInfo;
		
		$myArray = array();
		if($empInfo[0]['skills'] !=""){
			$myArray = explode(',',$empInfo[0]['skills']);
		}
		//print_r($myArray);
		$skills_arr = array();
		for($i=0;$i<COUNT($myArray);$i++){	
			$skillSQL = "SELECT skill_name FROM skills_master WHERE skill_id = ".$myArray[$i];
			$skillRes = $this->db->query($skillSQL);
			$skillInfo = $skillRes->result_array();
			array_push($skills_arr, $skillInfo);
		}
		$this->mViewData['skillInfo'] = $skills_arr; //var_dump($skills_arr);
	
		if(empty($this->input->post('year'))){  
			$m = date('m');
			$y = date('Y');
			if($m >=4){
				$dyear = date('Y')+1;
			}
			else{
				$dyear = $y;
			}
			//$dyear = date("Y")+1; 
		} else{ 
			$dyear = $this->input->post('year'); 
		}
		
			$qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$user_id."' AND DATE_FORMAT(annualdate, '%Y') = ".$dyear;
			$qryRes = $this->db->query($qryGoal);
			$qryInfo = $qryRes->result_array();
			$this->mViewData['qryInfo'] = $qryInfo;
			$this->mViewData['dyear'] = $dyear;
			
		$reqSkillSQL = "SELECT s.skill_name FROM minimum_requirement AS r INNER JOIN skills_master AS s ON s.skill_id = r.requirement_type_id WHERE r.designation_id = '".$empInfo[0]['designation']."' AND r.requirement_type = 'S'";
		$reqSkillRes = $this->db->query($reqSkillSQL);
		$regSkillInfo = $reqSkillRes->result_array();
		$this->mViewData['regSkillInfo'] = $regSkillInfo;
		
		$this->render('myprofile/job_description_readonly_emp_view', 'full_width',$this->mViewData); 
	}
	
	public function job_description_update_emp()
	{
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'JD/Goal';
		
		$empSql = "SELECT i.full_name, i.user_status, ie.jd_document, ie.kpi_document, ie.skills FROM `internal_user` i
		LEFT JOIN internal_user_ext AS ie ON ie.login_id = i.login_id  WHERE i.login_id = '".$user_id."' LIMIT 1";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empInfo;
		
		$error = "";
		$success = "";
		if($this->input->post('btnUpdateJD') == 'Update') {
			$jd = '';
			if($_FILES['flJobDesc']['name'] != '' && $_FILES['flJobDesc']['size'] > 0 && ($_FILES["flJobDesc"]["type"]=='application/msword' || $_FILES["flJobDesc"]["type"]=='application/pdf' || $_FILES["flJobDesc"]["type"]=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'))
			{
				if(($_FILES['flJobDesc']['name']) !=""){
					$path = $_FILES['flJobDesc']['name'];
					$filename = strtolower(str_replace(' ','',$empInfo[0]["full_name"] ."_jd_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION))); 
					$config['upload_path'] = APPPATH.'../assets/upload/jd_goal/';
					$config['allowed_types'] = 'pdf|doc|docx|xls';
					$config['file_name'] = $filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('flJobDesc')){
						$fileData = $this->upload->data();
						$jd = $filename;
					}else {
						$error = "PDF,MSWORD,EXCEL files are allowed";
					}
				}
			}
			$kt = '';
			if($_FILES['flKPI']['name'] != '' && $_FILES['flKPI']['size'] > 0 && ($_FILES["flKPI"]["type"]=='application/msword' || $_FILES["flKPI"]["type"]=='application/pdf' || $_FILES["flKPI"]["type"]=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'))
			{
				if(($_FILES['flKPI']['name']) !=""){
					$path = $_FILES['flKPI']['name'];
					$filename = strtolower(str_replace(' ','',$empInfo[0]["full_name"] ."_kpi_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION))); 
					$config['upload_path'] = APPPATH.'../assets/upload/jd_goal/';
					$config['allowed_types'] = 'pdf|doc|docx|xls';
					$config['file_name'] = $filename;
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('flKPI')){
						$fileData = $this->upload->data();
						$kt = $filename;
					}else {
						$error = "PDF,MSWORD,EXCEL files are allowed";
					}
				}
			} 
			$skills = $this->input->post('selSkills');
			$skill = implode(',', (array) $skills);
			// $jd $kt $skill
			$chkSql="SELECT internal_user_ext_id FROM `internal_user_ext` WHERE `login_id` =".$user_id;
			$chkRes = $this->db->query($chkSql);
			if($chkRes->num_rows() == 0){
				  $insertSQL = "INSERT INTO internal_user_ext (login_id, jd_document, kpi_document, skills)
                    VALUES ('".$user_id."','".$jd."', '".$kt."', '".$skill."')";
					$this->db->query($insertSQL);
					//header("Refresh:0");
					$success = "JD, KPA and Skills are Updated";
			} else {
				if($jd !=""){
					$updateSQL = "UPDATE internal_user_ext SET jd_document = '".$jd."' WHERE login_id = ".$user_id." LIMIT 1";
					$this->db->query($updateSQL);
					$success = "JD File and Skills is Updated";
				}
				if($kt !=""){
					$updateSQL = "UPDATE internal_user_ext SET  kpi_document = '".$kt."' WHERE login_id = ".$user_id." LIMIT 1";
					$this->db->query($updateSQL);
					$success = "KRA or KPI file and Skills is Updated";
				}
				if($skill !=""){
					$updateSQL = "UPDATE internal_user_ext SET  skills = '".$skill."' WHERE login_id = ".$user_id." LIMIT 1";
					$this->db->query($updateSQL);
					//$success = "Skills is Updated";
				}
				//header("Refresh:0");
			}
			redirect('/my_account/job_description_readonly_emp');
		}
		
		$skillSQL = "SELECT skill_id, skill_name, skill_category FROM skills_master WHERE `status` = 'Y' ORDER BY skill_category";
		$skillRes = $this->db->query($skillSQL);
		$skillInfo = $skillRes->result_array();
		$this->mViewData['skillInfo']= $skillInfo;
		
		if(empty($this->input->post('year'))){ 
			$m = date('m');
			$y = date('Y');
			if($m >=4){
				$dyear = date('Y')+1;
			}
			else{
				$dyear = $y;
			}
			//$dyear = date("Y")+1; 
		} 
		else { 
			$dyear = $this->input->post('year'); 
		}
		$this->mViewData['dyear'] = $dyear;
		//echo  'Current'.$dyear;    
		$qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$user_id."' AND DATE_FORMAT(annualdate, '%Y') = ".$dyear;
		$qryRes = $this->db->query($qryGoal);
		$qryInfo = $qryRes->result_array();
		$this->mViewData['qryInfo'] = $qryInfo;
	
		if($this->input->post('btnUpdateGoal') == "Update"){
			$midc=0;
			
			for($i=0;$i<count($this->input->post('mid'));$i++){
				
				
				//echo $this->input->post('mid')[$i].',';
				if($this->input->post('mid')[$i] == ""){
					if($this->input->post('objective')[$i] != ""){
						 $insGoalSql ='INSERT INTO `goal_sheet` SET login_id="'.$user_id.'",
						 objective = "'.addslashes($this->input->post('objective')[$i]).'",
						 target = "'.addslashes($this->input->post('target')[$i]).'",
						 weightage="'.$this->input->post('weightage')[$i].'",
						 progress="'.$this->input->post('progress')[$i].'",
						 annualdate= "'.date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d'))).'"';
						 $insGoalRes = $this->db->query($insGoalSql);
						 $mid = $this->db->insert_id();
					}
					 
				 }else {
					 $midc++;
					 $mid = $this->input->post('mid')[$i];
					if($this->input->post('objective')[$i] != ""){
						$insGoalSql ='UPDATE `goal_sheet` SET objective = "'.addslashes($this->input->post('objective')[$i]).'", 
							target = "'.addslashes($this->input->post('target')[$i]).'", 
							weightage= "'.$this->input->post('weightage')[$i].'", 
							progress= "'.$this->input->post('progress')[$i].'" 
							WHERE login_id="'.$user_id.'" AND mid="'.$this->input->post('mid')[$i].'"';
						$insGoalRes = $this->db->query($insGoalSql);
					}
					else{
						$insGoalSql ='UPDATE `goal_sheet` SET 
							progress= "'.$this->input->post('progress')[$i].'" 
							WHERE login_id="'.$user_id.'" AND mid="'.$this->input->post('mid')[$i].'"';
						$insGoalRes = $this->db->query($insGoalSql);
					}
				}
				
				if($this->input->post('progress')[$i] > 0){
					$mid = $this->input->post('mid')[$i];
					
					if($this->input->post('objective')[$i] != ""){
						$insGoalLogSql ='INSERT INTO `goal_sheet_log` SET mid="'.$mid.'", 
						login_id="'.$user_id.'",
						 objective = "'.addslashes($this->input->post('objective')[$i]).'",
						 target = "'.addslashes($this->input->post('target')[$i]).'",
						 weightage="'.$this->input->post('weightage')[$i].'",
						 progress="'.$this->input->post('progress')[$i].'",
						 annualdate= "'.date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d'))).'"';
						 $insGoalLogRes = $this->db->query($insGoalLogSql);
					}
					else{
						$qryGoals = "SELECT * FROM `goal_sheet` WHERE login_id = '".$user_id."' AND mid='".$this->input->post('mid')[$i]."'";
						$qryRess = $this->db->query($qryGoals);
						$qryInfos = $qryRess->result_array();
						if(count($qryInfos)>0){
							$insGoalLogSql ='INSERT INTO `goal_sheet_log` SET mid="'.$mid.'", 
							login_id="'.$user_id.'",
							 objective = "'.$qryInfos[0]['objective'].'",
							 target = "'.$qryInfos[0]['target'].'",
							 weightage="'.$qryInfos[0]['weightage'].'",
							 progress="'.$this->input->post('progress')[$i].'",
							 annualdate= "'.date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d'))).'"';
							 $insGoalLogRes = $this->db->query($insGoalLogSql);
						}
						else{
							$insGoalLogSql ='INSERT INTO `goal_sheet_log` SET mid="'.$mid.'", 
							login_id="'.$user_id.'",
							 progress="'.$this->input->post('progress')[$i].'",
							 annualdate= "'.date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d'))).'"';
							 $insGoalLogRes = $this->db->query($insGoalLogSql);
						}
						
					}
				}
					
				
				//header("Refresh:0");
			}
			
			if(count($this->input->post("mids")) < count($qryInfo)){
				$delGoalSql = $this->db->query("DELETE from `goal_sheet` WHERE login_id='".$user_id."' AND mid NOT IN (".implode(",",$this->input->post("mids")).")");
			}
			//exit;
			redirect('/my_account/job_description_readonly_emp');
		}
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->render('myprofile/job_description_update_emp_view', 'full_width',$this->mViewData);
	}
	
	public function document_readonly_emp()
	{
		$this->mViewData['pageTitle']    = 'Document';
		$empSql = "SELECT i.full_name,i.user_status FROM `internal_user` i  WHERE i.login_id = '".$this->session->userdata('user_id')."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();

		$joinSql = "SELECT d.*,j.kit_name FROM emp_document d JOIN  joining_kit_master j ON j.joining_kit_id =d.joining_kit_id WHERE d.login_id = '".$this->session->userdata('user_id')."' AND j.status='Y'";
		$joinRes = $this->db->query($joinSql); 
		$this->mViewData['joinInfo'] = $joinRes->result_array(); 
		
		$this->render('myprofile/document_readonly_emp_view', 'full_width',$this->mViewData);  
	}
	public function profile_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Edit profile';
		if($this->input->post('btnUpdateProfile') == 'Update')
		{
			$profilePhoto = '';
			if($_FILES['txtPhoto']['name'] != '' && $_FILES['txtPhoto']['size'] > 0 && ($_FILES["txtPhoto"]["type"]=='image/jpeg' || $_FILES["txtPhoto"]["type"]=='image/jpg' || $_FILES["txtPhoto"]["type"]=='image/bmp' || $_FILES["txtPhoto"]["type"]=='image/png' || $_FILES["txtPhoto"]["type"]=='image/gif'))
			{
				/* $fileName = @md5($this->session->userdata('empCode')).basename( $_FILES['txtPhoto']['name']);
				//$target_path = 'E:/icompass-new/upload/profile/'. $fileName;
				$target_path = $_SERVER["DOCUMENT_ROOT"].'/upload/profile/'. $fileName;
				if(move_uploaded_file($_FILES['txtPhoto']['tmp_name'], $target_path))
				{
					$profilePhoto = "`user_photo_name` = '$fileName',";
				} */
				if(($_FILES['txtPhoto']['name']) !=""){
					$path = $_FILES['txtPhoto']['name'];
					$filename = time().'-profile-image.'.pathinfo($path, PATHINFO_EXTENSION); 
					$config['upload_path'] = APPPATH.'../assets/upload/profile/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('txtPhoto')){
						$fileData = $this->upload->data();
						$profilePhoto = "`user_photo_name` = '$filename',";
					}
				}
			}

			$profileSign = '';
			if($_FILES['txtSign']['name'] != '' && $_FILES['txtSign']['size'] > 0 && ($_FILES["txtSign"]["type"]=='image/jpeg' || $_FILES["txtSign"]["type"]=='image/jpg' || $_FILES["txtSign"]["type"]=='image/bmp' || $_FILES["txtSign"]["type"]=='image/png' || $_FILES["txtSign"]["type"]=='image/gif'))
			{
				/* $fileName = @md5($_SESSION['empCode']).basename( $_FILES['txtSign']['name']);
				//$target_path = 'E:/icompass-new/upload/profile/'. $fileName;
				$target_path = $_SERVER["DOCUMENT_ROOT"].'/upload/profile/'. $fileName;
				if(move_uploaded_file($_FILES['txtSign']['tmp_name'], $target_path))
				{
					$profileSign = "`user_sign_name` = '$fileName',";
				} */
				if(($_FILES['txtSign']['name']) !=""){
					$path = $_FILES['txtSign']['name'];
					$filename = time().'-profile-signature.'.pathinfo($path, PATHINFO_EXTENSION); 
					$config['upload_path'] = APPPATH.'../assets/upload/profile/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('txtSign')){
						$fileData = $this->upload->data();
						$profileSign = "`user_sign_name` = '$filename',";
					}
				}
			}

			$dob = date("Y-m-d", strtotime($this->input->post('txtdob')));
			$dobWithCY = date("m-d", strtotime($dob));
			$isShowOnSearch = 'N';
			if($this->input->post('showMobileOnSearch') == 'Y')
			{
				$isShowOnSearch = 'Y';
			}

			// Update internal_user
			$updateIUSql = "UPDATE `internal_user` SET  `per_email` = '".$this->input->post('txtPEmailID')."', 
			`email` = '".$this->input->post('txtemail')."',
			`skype` = '".$this->input->post('txtSkype')."',
						`name_first` = '".$this->input->post('txtFirstName')."', 
						`name_middle` = '".$this->input->post('txtMiddleName')."',
						`name_last` = '".$this->input->post('txtLastName')."', 
						`full_name` = '".$this->input->post('txtFullName')."', 
						`gender` = '".$this->input->post('rdGender')."', 
						`marital_status` = '".$this->input->post('rdMStatus')."', 
						`passport_no` = '".$this->input->post('txtPassportNo')."', 
						`pan_card_no` = '".$this->input->post('txtPanNo')."', 
						`voter_id` = '".$this->input->post('txtVoterID')."',
						`ifsc_code` = '".$this->input->post('ifsc_code')."',
						`drl_no` = '".$this->input->post('txtDLicense')."',
						`address1` = '".$this->input->post('perAddr')."', 
						`city_district1` = '".$this->input->post('perDist')."',
						`state_region1` = '".$this->input->post('perState')."', 
						`pin_zip1` = '".$this->input->post('perPin')."', 
						`country1` = '".$this->input->post('perCountry')."',
						`phone1`= '".$this->input->post('txtper_landline_no')."',
						`mobile1`= '".$this->input->post('txt_mobile1')."',
						`phone2`= '".$this->input->post('txtcorsp_landline_no')."',
						`mobile`= '".$this->input->post('txtcorsp_mobile_no')."',
						`address2` = '".$this->input->post('txtaddress2')."', 
						`city_district2` = '".$this->input->post('txtcity_district2')."',
						`state_region2` = '".$this->input->post('ddstate_region2')."', 
						`pin_zip2` = '".$this->input->post('txtpin_zip2')."', 
						`country2` = '".$this->input->post('ddcountry2')."',
						 $profilePhoto 
						 $profileSign 
						 `dob` = '".$dob."', 
						 `isShowOnSearch` = '$isShowOnSearch' , 
						`dob_with_current_year` = '".$dobWithCY."', 
						`blood_group` = '".$this->input->post('txtBGroup')."',
						`highest_qual` = '".$this->input->post('selHgstEdu')."',
						`specialization` = '".$this->input->post('specialization')."',
						`loc_highest_qual` = '".$this->input->post('perLoc')."', 
						`email_signature` = '".$this->input->post('emailSign')."' 
						WHERE `login_id` = '".$user_id."'";
			//exit;
			$this->db->query($updateIUSql);

			$chkSql="SELECT * FROM `internal_user_ext` WHERE `login_id` = '".$user_id."'";
			$chkRes = $this->db->query($chkSql);
			$chkRows = count($chkRes->result_array());
			if($chkRows >0  )
			{
				$updateIUESql = "UPDATE `internal_user_ext` SET 					
									   official_mobile= '".$this->input->post('txtofficial_mobile')."',adharcard_no= '".$this->input->post('txtadharcard_no')."'
									   WHERE `login_id` = '".$user_id."'";

				$this->db->query($updateIUESql);
			}
			else
			{
				$insertIUESql = "INSERT INTO `internal_user_ext` (login_id,official_mobile,adharcard_no)
						VALUES('$user_id','".$this->input->post('txtofficial_mobile')."','".$this->input->post('txtadharcard_no')."')";

				$this->db->query($insertIUESql);

			}

			// Update compass_user
			$updateCUSql = "UPDATE `compass_user` SET  `name` = '".$this->input->post('txtFullName')."' WHERE `ref_id` = '".$user_id."'";
			$this->db->query($updateCUSql);

			$successMsg = TRUE;
		}

		$empSql = "SELECT i.*,ie.*,u.desg_name, d.dept_name, r.full_name AS rmName, r.loginhandle AS rmECode, c.country_name AS country_name1, s.specialization_name
		FROM `internal_user` i 
		JOIN `internal_user` r ON r.login_id = i.reporting_to 
		LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
		LEFT JOIN `department` d ON d.dept_id = i.department 
		LEFT JOIN `specialization_master` s ON s.specialization_id = i.specialization 
		LEFT JOIN `internal_user_ext` ie ON  i.login_id =ie.login_id 
		LEFT JOIN `country` c ON  c.country_id =i.country1 
		WHERE i.login_id = '".$user_id."'";

		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();
		
		$this->mViewData['country'] = $this->Hr_model->get_country();
		$this->mViewData['state'] = $this->Hr_model->get_state();
		$this->mViewData['grade'] = $this->Hr_model->get_grade();
		$this->mViewData['level'] = $this->Hr_model->get_level();
		$this->mViewData['qualification'] = $this->Hr_model->get_qualification();
		$this->mViewData['sourcehire'] = $this->Hr_model->get_source_of_hire();
		$this->mViewData['location'] = $this->Hr_model->get_company_location_branch(); 
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one();
		$this->mViewData['location_of_highest_qualification'] = $this->Hr_model->location_of_highest_qualification();

		/* query for country */
		$mysql_country = "SELECT country_id,country_name FROM country WHERE country_status = '1' ORDER BY country_sort_order";

		/* query for state */
		$mysql_state = "SELECT state_id,state_name FROM state WHERE state_status = '1' ORDER BY state_sort_order";

		/* query for Qualification */
		$courseSQL = "SELECT course_id, course_name FROM course_info WHERE `status` = 'Y' ORDER BY course_type DESC";
		
		$this->render('myprofile/profile_update_emp_view', 'full_width',$this->mViewData);
		//$this->load->view('script/myprofile/datepicker');
	}
	
	
	public function exp_profile_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Experience profile';
		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		$this->mViewData['expInAABSyS'] = $this->getDifferenceFromNow($empInfo[0]['join_date'], 6);
		$expInAABSyS = $this->getDifferenceFromNow($empInfo[0]['join_date'], 6);
		$this->mViewData['expTotal'] = $expInAABSyS + $empInfo[0]['exp_others'];

		$expSql = "SELECT e.*  FROM `exp_info` e WHERE e.login_id = '".$user_id."'";
		$expRes = $this->db->query($expSql);
		$expRes_arr = $expRes->result_array();
		//var_dump($expRes_arr);
		$this->mViewData['expRes_arr'] = $expRes->result_array();
		$this->mViewData['expRows'] = count($expRes_arr);

		//Get Required Work Experience for this employee
		$this->mViewData['reqExperience'] = "Not Defined";
		/* $reqExperience = "Not Defined";
		$reqExpSQL = "SELECT e.experience_name FROM minimum_requirement AS r INNER JOIN experience_master AS e ON e.experience_id = r.requirement_type_id WHERE r.designation_id = ".$empInfo[0]['designation']." AND r.requirement_type = 'W' LIMIT 1";
		$reqExpRES = $this->db->query($reqExpSQL);
		//echo $reqExpSQL;
		$reqExpINFO = $reqExpRES->result_array();
		$reqExpNUM = count($reqExpINFO); 
		//var_dump($reqExpINFO);
		$this->mViewData['reqExpINFO'] = $reqExpRES->result_array(); */
		//$this->mViewData['reqExperience'] = $reqExpINFO[0]["experience_name"];
		/* if($reqExpNUM == 1)
		{
			$this->mViewData['reqExpINFO'] = $reqExpRES->result_array();
			$this->mViewData['reqExperience'] = $reqExpINFO[0]["experience_name"];
		} */
		
		$skillSql = "select * from exp_skill es where es.login_id = '".$user_id."'";
		$querySql = $this->db->query($skillSql);
		$res_Skill = $querySql->result_array(); 
		$this->mViewData['reqSkill'] = $res_Skill;
		
		$this->render('myprofile/exp_profile_readonly_emp_view', 'full_width',$this->mViewData); 
	}
	
	public function exp_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Edit experience';
		if($this->input->post('btnAddPrevExp') == 'Add')
		{
			if($this->input->post('txtcomp_name') !="" && $this->input->post('txtdesignation') !="" && $this->input->post('txtexperince') !=""){
				$insertSQL = "INSERT INTO `exp_info` (login_id,comp_name,designation,experince)
						VALUES('".$user_id."','".$this->input->post('txtcomp_name')."','".$this->input->post('txtdesignation')."','".$this->input->post('txtexperince')."')";

				$this->db->query($insertSQL);

				//Get Total Experience Prior to Polosoft
				$expOutSQL = "SELECT SUM(experince) AS outTotal FROM exp_info WHERE login_id = '".$user_id."'";
				$expOutRES = $this->db->query($expOutSQL);
				$expOutINFO = $expOutRES->result_array();
				$expOutTotal = $expOutINFO[0]['outTotal'];

				$empSql = "SELECT internal_user_ext_id FROM `internal_user_ext` WHERE login_id = '".$user_id."'";
				$empRes = $this->db->query($empSql);
				$empRows = count($empRes->result_array());
				if($empRows > 0)
				{
					$executeSQL = "UPDATE `internal_user_ext` SET exp_others= '".$expOutTotal."' WHERE `login_id` = '".$user_id."'";
					$this->db->query($executeSQL);
				} 
				else
				{
					$executeSQL = "INSERT INTO `internal_user_ext` (login_id, exp_others) VALUES ('".$user_id."','".$expOutTotal."')";
					$this->db->query($executeSQL);
				}  
			}
		}

		$empSql = "SELECT i.full_name, i.join_date, i.user_status, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();

		$this->mViewData['expInAABSyS'] = $this->getDifferenceFromNow($empInfo[0]['join_date'], 6);

		$expSql = "SELECT e.*  FROM `exp_info` e WHERE e.login_id = '".$user_id."'";
		$expRes = $this->db->query($expSql);
		$this->mViewData['expRes_arr'] = $expRes->result_array();
		$expRows=count($expRes->result_array());
		
		$expSkill = "select * from exp_skill where login_id =".$user_id;
		$expskillRes = $this->db->query($expSkill);	
		$this->mViewData['expRes_skill'] = $expskillRes->result_array();
		$expskillRows=count($expskillRes->result_array());
			
		
		
		$this->render('myprofile/exp_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/experience_js');
	}
	
	public function exp_add_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		if($this->input->post('txtcomp_name') !="" && $this->input->post('txtdesignation') !="" && $this->input->post('txtexperince') !=""){
			$insertSQL = "INSERT INTO `exp_info` (login_id,comp_name,designation,experince)
					VALUES('".$user_id."','".$this->input->post('txtcomp_name')."','".$this->input->post('txtdesignation')."','".$this->input->post('txtexperince')."')";

			$this->db->query($insertSQL);

			//Get Total Experience Prior to Polosoft
			$expOutSQL = "SELECT SUM(experince) AS outTotal FROM exp_info WHERE login_id = '".$user_id."'";
			$expOutRES = $this->db->query($expOutSQL);
			$expOutINFO = $expOutRES->result_array();
			$expOutTotal = $expOutINFO[0]['outTotal'];

			$empSql = "SELECT internal_user_ext_id FROM `internal_user_ext` WHERE login_id = '".$user_id."'";
			$empRes = $this->db->query($empSql);
			$empRows = count($empRes->result_array());
			if($empRows > 0)
			{
				$executeSQL = "UPDATE `internal_user_ext` SET exp_others= '".$expOutTotal."' WHERE `login_id` = '".$user_id."'";
				$this->db->query($executeSQL);
			} 
			else
			{
				$executeSQL = "INSERT INTO `internal_user_ext` (login_id, exp_others) VALUES ('".$user_id."','".$expOutTotal."')";
				$this->db->query($executeSQL);
			} 
			echo 1;
		}
		else{
			echo 2;
		}
	}
	
	public function exp_update_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		if($this->input->post('types') == 'update'){
			$executeSQL = "UPDATE `exp_info` SET `comp_name` = '".$this->input->post('comp_name')."', `designation` = '".$this->input->post('designation')."', `experince` = '".$this->input->post('experince')."'  WHERE `login_id` = '".$user_id."' AND `exp_id` = '".$this->input->post('exp_id')."'";
			$this->db->query($executeSQL);
			
			//Get Total Experience Prior to Polosoft
			$expOutSQL = "SELECT SUM(experince) AS outTotal FROM exp_info WHERE login_id = '".$user_id."'";
			$expOutRES = $this->db->query($expOutSQL);
			$expOutINFO = $expOutRES->result_array();
			$expOutTotal = $expOutINFO[0]['outTotal'];
			$executeSQL = "UPDATE `internal_user_ext` SET exp_others= '".$expOutTotal."' WHERE `login_id` = '".$user_id."'";
			$this->db->query($executeSQL);
			
			echo 1;
		}
		else if($this->input->post('types') == 'delete'){
			$executeSQL = "DELETE from `exp_info`  WHERE `login_id` = '".$user_id."' AND `exp_id` = '".$this->input->post('exp_id')."'";
			$this->db->query($executeSQL);
			
			//Get Total Experience Prior to Polosoft
			$expOutSQL = "SELECT SUM(experince) AS outTotal FROM exp_info WHERE login_id = '".$user_id."'";
			$expOutRES = $this->db->query($expOutSQL);
			$expOutINFO = $expOutRES->result_array();
			$expOutTotal = $expOutINFO[0]['outTotal'];
			$executeSQL = "UPDATE `internal_user_ext` SET exp_others= '".$expOutTotal."' WHERE `login_id` = '".$user_id."'";
			$this->db->query($executeSQL);
		
			echo 2;
		}
	}
	
	public function education_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Edit education';
		if($this->input->post('btnAddEdu') == 'Add')
		{
			if($this->input->post('ddl_coursetype') !="" &&  $this->input->post('ddl_specialization') !="" &&  $this->input->post('txtpassing_year') !="" &&  $this->input->post('txtpercentage') !="" &&  $this->input->post('selBorU') !=""  ){
				$insertIUESql = "INSERT INTO `education_info` (login_id,course_id,specialization_id,passing_year,percentage,board_id)
							VALUES('".$user_id."','".$this->input->post('ddl_coursetype')."','".$this->input->post('ddl_specialization')."','".$this->input->post('txtpassing_year')."','".$this->input->post('txtpercentage')."','".$this->input->post('selBorU')."')";
				$this->db->query($insertIUESql);
			}
		}

		$empSql = "SELECT i.full_name,i.user_status FROM `internal_user` AS i WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();
		 
		$eduSql = "SELECT e.*,i.full_name,i.user_status,c.course_name,c.course_type FROM `education_info` e  
				  JOIN `internal_user` i ON e.login_id=i.login_id
				  JOIN course_info c ON c.course_id=e.course_id WHERE e.login_id = '".$user_id."'";
		$eduRes = $this->db->query($eduSql);
		$eduRows = $eduRes->result_array();
		$eduRows_arr = array();
		for($i=0; $i<count($eduRows); $i++){
			$course_type = $eduRows[$i]['course_type'];
			$change_course = $this->Myprofile_model->education_change_course($course_type);
			$course_id = $eduRows[$i]['course_id'];
			$change_specialization = $this->Myprofile_model->education_change_specialization($course_id);
			$datas = array(
				'education_id' => $eduRows[$i]['education_id'] ,
				'login_id' => $eduRows[$i]['login_id'] ,
				'specialization_id' => $eduRows[$i]['specialization_id'] ,
				'course_id' => $eduRows[$i]['course_id'] ,
				'passing_year' => $eduRows[$i]['passing_year'] ,
				'percentage' => $eduRows[$i]['percentage'] ,
				'board_id' => $eduRows[$i]['board_id'] ,
				'full_name' => $eduRows[$i]['full_name'] ,
				'user_status' => $eduRows[$i]['user_status'] ,
				'course_name' => $eduRows[$i]['course_name'] ,
				'course_type' => $eduRows[$i]['course_type'] ,
				'course_type_arr' => $change_course ,
				'specialization_arr' => $change_specialization
			);
			array_push($eduRows_arr, $datas);
		}
		
		
		$this->mViewData['eduRows'] = $eduRows_arr;
		

		/* query for Board/University */
		 $bOrUSQL = "SELECT board_university_id, board_university_name FROM board_university_master WHERE status = 'Y' ORDER BY board_university_name ASC";
		 $borRes = $this->db->query($bOrUSQL);
		$this->mViewData['bordRows'] = $borRes->result_array();
		$this->mViewData['courseType'] = $this->Myprofile_model->education_change_course('Graduation');
		 
		$this->render('myprofile/education_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/education_js');
	}
	
	public function education_update_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		if($this->input->post('types') == 'update'){
			$executeSQL = "UPDATE `education_info` SET `course_id` = '".$this->input->post('ddl_coursetype')."', `specialization_id` = '".$this->input->post('ddl_specialization')."', `passing_year` = '".$this->input->post('txtpassing_year')."', `percentage` = '".$this->input->post('txtpercentage')."', `board_id` = '".$this->input->post('selBorU')."'  WHERE `login_id` = '".$user_id."' AND `education_id` = '".$this->input->post('education_id')."'";
			$this->db->query($executeSQL);
			
			echo 1;
		}
		else if($this->input->post('types') == 'delete'){
			$executeSQL = "DELETE from `education_info`  WHERE `login_id` = '".$user_id."' AND `education_id` = '".$this->input->post('education_id')."'";
			$this->db->query($executeSQL);
			
			echo 2;
		}
	}
	
	public function education_change_course()
	{
		$coursetype = $this->input->post('coursetype');
		$result = $this->Myprofile_model->education_change_course($coursetype);
		echo json_encode($result, TRUE);
	}
	
	public function education_change_specialization()
	{
		$specialization = $this->input->post('specialization');
		$result = $this->Myprofile_model->education_change_specialization($specialization);
		echo json_encode($result, TRUE);
	}
	public function family_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Edit Family';
		$successMsg = FALSE;
		if($this->input->post('btnUpdateFamily') == 'Update')
		{
			$empSql = "SELECT * FROM `family_info` WHERE login_id = '".$user_id."'";
			$empRes = $this->db->query($empSql);
			$empRows = count($empRes->result_array());
			$father_dob = date("Y-m-d", strtotime($this->input->post('txtfather_dob')));
			$mother_dob = date("Y-m-d", strtotime($this->input->post('txtmother_dob')));
			$spouse_dob = $anniversary_date = "0000-00-00";
			if($this->input->post('txtspouse_dob') != "")
				$spouse_dob = date("Y-m-d", strtotime($this->input->post('txtspouse_dob')));
			if($this->input->post('txtanniversary_date') != "")
				$anniversary_date = date("Y-m-d", strtotime($this->input->post('txtanniversary_date')));
			if($empRows >0)
			{
			   $updateIUESql = "UPDATE `family_info` SET fathers_name= '".$this->input->post('txtfather_name')."', fathers_dob = '".$father_dob."',
							mother_name= '".$this->input->post('txtmother_name')."',mother_dob= '".$mother_dob."',spouse_name= '".$this->input->post('txtspouse_name')."',
							spouse_dob= '".$spouse_dob."',anniversary_date= '".$anniversary_date."'
							WHERE `login_id` = '".$user_id."'";
			   $this->db->query($updateIUESql);
			}
			else
			{
			   $insertIUESql = "INSERT INTO `family_info` (login_id,fathers_name,fathers_dob,mother_name,mother_dob,spouse_name,spouse_dob ,anniversary_date)
							VALUES('".$user_id."','".$this->input->post('txtfather_name')."','".$father_dob."','".$this->input->post('txtmother_name')."','".$mother_dob."','".$this->input->post('txtspouse_name')."','".$spouse_dob."', '".$anniversary_date."')";

			  $this->db->query($insertIUESql); 
			}
				$successMsg = TRUE;
		}
		 if($this->input->post('btnAddChild') == 'Add')
		 {
			$child_dob = date("Y-m-d", strtotime($this->input->post('txtchild_dob')));
			$insertIUESql = "INSERT INTO `child_info` (login_id,child_name,child_gender,child_dob) VALUES ('".$user_id."','".$this->input->post('txtchild_name')."','".$this->input->post('ddl_childgender')."','".$child_dob."')";
			$this->db->query($insertIUESql);
		 }
		 
		$empSql = "SELECT i.full_name,i.user_status,i.FnF_status,i.marital_status, f.* FROM `internal_user` AS i LEFT JOIN `family_info` AS f  ON f.login_id = i.login_id  WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();

		$fatherdob = $motherdob = $spousedob = $anniversarydate = "";
		
		if($empInfo[0]["fathers_dob"] != "" && $empInfo[0]["fathers_dob"] != "0000-00-00"){
			$this->mViewData['fatherdob'] = date("d M, Y", strtotime($empInfo[0]["fathers_dob"]));
		}
		else{
			$this->mViewData['fatherdob'] = "";
		}
		if($empInfo[0]["mother_dob"] != "" && $empInfo[0]["mother_dob"] != "0000-00-00"){
			$this->mViewData['motherdob'] = date("d M, Y", strtotime($empInfo[0]["mother_dob"]));
		}
		else{
			$this->mViewData['motherdob'] = "";
		}
		if($empInfo[0]["spouse_dob"] != "" && $empInfo[0]["spouse_dob"] != "0000-00-00"){
			$this->mViewData['spousedob'] = date("d M, Y", strtotime($empInfo[0]["spouse_dob"]));
		}
		else{
			$this->mViewData['spousedob'] = "";
		}
		if($empInfo[0]["anniversary_date"] != "" && $empInfo[0]["anniversary_date"] != "0000-00-00"){
			$this->mViewData['anniversarydate'] = date("d M, Y", strtotime($empInfo[0]["anniversary_date"]));
		}
		else{
			$this->mViewData['anniversarydate'] = "";
		}

		$childSql  = "SELECT * FROM `child_info` WHERE login_id = '".$user_id."' ORDER BY child_id DESC";
		$childRes  = $this->db->query($childSql);
		$this->mViewData['childRows'] = count($childRes->result_array());
		$this->mViewData['childInfo_arr'] = $childRes->result_array();
		if($this->input->post('btnUpdateChild') == 'Update')
		{
			extract($this->input->post());
			$child_dob= date("Y-m-d", strtotime($child_dob));
			$updateIUESql = "UPDATE `child_info` SET child_name= '".$child_name."',
                      child_dob= '".$child_dob."' WHERE `child_id` =".$child_id;
		}
					  
		$this->render('myprofile/family_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/family_js');
	}
	
	
	public function family_add_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$child_dob = date("Y-m-d", strtotime($this->input->post('txtchild_dob')));
		$insertIUESql = "INSERT INTO `child_info` (login_id,child_name,child_gender,child_dob) VALUES ('".$user_id."','".$this->input->post('txtchild_name')."','".$this->input->post('ddl_childgender')."','".$child_dob."')";
		$this->db->query($insertIUESql);
		echo 1;
	}
	
	
	public function family_update_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		if($this->input->post('types') == 'update'){
			$txtchild_dob = '';
			if($this->input->post('txtchild_dob') !=""){
				$txtchild_dob = date("Y-m-d", strtotime($this->input->post('txtchild_dob')));
			}
			$executeSQL = "UPDATE `child_info` SET `child_name` = '".$this->input->post('txtchild_name')."', `child_gender` = '".$this->input->post('ddl_childgender')."', `child_dob` = '". $txtchild_dob."'  WHERE `login_id` = '".$user_id."' AND `child_id` = '".$this->input->post('child_id')."'";
			$this->db->query($executeSQL);
			
			echo 1;
		}
		else if($this->input->post('types') == 'delete'){
			$executeSQL = "DELETE from `child_info`  WHERE `login_id` = '".$user_id."' AND `child_id` = '".$this->input->post('child_id')."'";
			$this->db->query($executeSQL);
			
			echo 2;
		}
	}
	
	
	public function reference_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Reference';
		$empSql = "SELECT i.full_name,i.user_status FROM `internal_user` i  WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();

		$refSql = "SELECT r.* FROM `emp_reference_info` AS r WHERE r.login_id = '".$user_id."'";
		$refRes = $this->db->query($refSql);
		$this->mViewData['refInfo'] = $refRes->result_array(); 

		$this->render('myprofile/reference_readonly_emp_view', 'full_width',$this->mViewData); 
	}
	
	public function reference_update_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$this->mViewData['pageTitle']    = 'Edit reference';
		
		$refSql = "SELECT r.* FROM `emp_reference_info` AS r WHERE r.login_id = '".$user_id."'";
		$refRes = $this->db->query($refSql);
		$this->mViewData['refInfo'] = $refRes->result_array(); 
		$this->render('myprofile/reference_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/reference_js');		
	}
	
	public function reference_update_single_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		if($this->input->post('txtref_id_1') != ""){
			$executeSQL = "UPDATE `emp_reference_info` SET `ref_name` = '".$this->input->post('txtref_name_1')."', `comp_name` = '".$this->input->post('txtcomp_name_1')."', `designation` = '".$this->input->post('txtdesignation_1')."' , `cont_no` = '".$this->input->post('txtcont_no_1')."'  WHERE `login_id` = '".$user_id."' AND `ref_id` = '".$this->input->post('txtref_id_1')."'";
			$this->db->query($executeSQL);
		}
		else{	
			$insertIUESql = "INSERT INTO `emp_reference_info` (login_id,ref_name,comp_name,designation,cont_no)
							VALUES('".$user_id."','".$this->input->post('txtref_name_1')."','".$this->input->post('txtcomp_name_1')."','".$this->input->post('txtdesignation_1')."','".$this->input->post('txtcont_no_1')."')";
			$this->db->query($insertIUESql); 
		}
		
		if($this->input->post('txtref_id_2') != ""){
			$executeSQL = "UPDATE `emp_reference_info` SET `ref_name` = '".$this->input->post('txtref_name_2')."', `comp_name` = '".$this->input->post('txtcomp_name_2')."', `designation` = '".$this->input->post('txtdesignation_2')."' , `cont_no` = '".$this->input->post('txtcont_no_2')."'  WHERE `login_id` = '".$user_id."' AND `ref_id` = '".$this->input->post('txtref_id_2')."'";
			$this->db->query($executeSQL);
		}
		else{	
			$insertIUESql = "INSERT INTO `emp_reference_info` (login_id,ref_name,comp_name,designation,cont_no)
							VALUES('".$user_id."','".$this->input->post('txtref_name_2')."','".$this->input->post('txtcomp_name_2')."','".$this->input->post('txtdesignation_2')."','".$this->input->post('txtcont_no_2')."')";
			$this->db->query($insertIUESql); 
		}
		
		if($this->input->post('txtref_id_3') != ""){
			$executeSQL = "UPDATE `emp_reference_info` SET `ref_name` = '".$this->input->post('txtref_name_3')."', `comp_name` = '".$this->input->post('txtcomp_name_3')."', `designation` = '".$this->input->post('txtdesignation_3')."' , `cont_no` = '".$this->input->post('txtcont_no_3')."'  WHERE `login_id` = '".$user_id."' AND `ref_id` = '".$this->input->post('txtref_id_3')."'";
			$this->db->query($executeSQL);
		}
		else{	
			$insertIUESql = "INSERT INTO `emp_reference_info` (login_id,ref_name,comp_name,designation,cont_no)
							VALUES('".$user_id."','".$this->input->post('txtref_name_3')."','".$this->input->post('txtcomp_name_3')."','".$this->input->post('txtdesignation_3')."','".$this->input->post('txtcont_no_3')."')";
			$this->db->query($insertIUESql); 
		}
		echo 1;
	}
	
	
	public function document_update_emp()
	{
		$empSql = "SELECT i.loginhandle,i.full_name,i.user_status FROM `internal_user` i  WHERE i.login_id = '".$this->session->userdata('user_id')."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$kitSQL = "Select joining_kit_id,kit_name FROM joining_kit_master WHERE status = 'Y'";
		$kitRes = $this->db->query($kitSQL);
		$kitInfo = $kitRes->result_array();
		$this->mViewData['kitInfo'] = $kitInfo;
		
		
		$success = "";
		$error = "";
		if($this->input->post('btnUpdateDoc') == 'Save')
		{   
				$docid='';
				$fname = explode(".", $_FILES['file']['name']); 
				$docid = $this->input->post('docid');
				if($_FILES['file']['error'] == 0 && $_FILES['file']['type'] =='application/pdf')
				{                            					
					if(($_FILES['file']['name']) !=""){
						$path = $_FILES['file']['name'];
						$filename = strtolower(str_replace(' ','',$empInfo[0]["loginhandle"] ."_".$fname[0]."_".date("YmdHis").'.'.pathinfo($path, PATHINFO_EXTENSION)));
						$config['upload_path'] = APPPATH.'../assets/upload/document/';
						$config['allowed_types'] = 'pdf';
						$config['file_name'] = $filename;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if($this->upload->do_upload('file')){
							$fileData = $this->upload->data();
							$jKitSql = "SELECT * FROM `emp_document` where joining_kit_id='".$docid."' and login_id='".$this->session->userdata('user_id')."'";
							$res = $this->db->query($jKitSql);
							$jKitnum = count($res->result_array());
							if($jKitnum > 0)
							{
								$updateIUESql = "UPDATE `emp_document` SET document_name='".$filename."' ,status='Y' WHERE joining_kit_id='".$docid."' AND login_id =".$this->session->userdata('user_id');
								$this->db->query($updateIUESql);
							}
							else
							{
								$insertIUESql = "INSERT INTO `emp_document` (login_id,joining_kit_id,document_name,status) VALUES('".$this->session->userdata('user_id')."','".$docid."','".$filename."','Y')";
								$this->db->query($insertIUESql);
							}
							$success = 'Uploaded Successfully';
						}
					}
				}
				else
				{
					echo $this->UploadException($_FILES['file']['error']); 
					$error = 'Something went Wrong';
				}
		   } 
		
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->mViewData['pageTitle']    = 'Edit Documents';
		$this->render('myprofile/document_update_emp_view', 'full_width',$this->mViewData);
	}
	public function get_file_extension($filename)
	{
		$filenameitems = explode(".", $filename);
		return $filenameitems[count($filenameitems) - 1];
	}
	public function UploadException($code)
	{
		switch ($code) 
		{
			case UPLOAD_ERR_INI_SIZE:
				$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = "The uploaded file was only partially uploaded";
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = "No file was uploaded";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = "Missing a temporary folder";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = "Failed to write file to disk";
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = "File upload stopped by extension";
				break;

			default:
				$message = "Unknown upload error";
				break;
		}
		return $message;
	} 
	
	
	
	public function letter_issued_readonly_emp()
	{
		$this->mViewData['pageTitle']    = 'Letter Issued';
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.full_name,i.user_status FROM `internal_user` i  WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$letterSQL = "SELECT l.letter_id, l.letter_name, l.letter_document, l.issued_date FROM emp_letter AS l WHERE l.login_id = " . $user_id;
		$letterRES = $this->db->query($letterSQL);
		$letterRow = $letterRES->result_array(); 
		$this->mViewData['joinInfo'] = $letterRow; 
		
		$this->render('myprofile/letter_issued_readonly_emp_view', 'full_width',$this->mViewData);  
	}
	
	public function letter_issued_update_emp()
	{
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.loginhandle,i.full_name,i.user_status FROM `internal_user` i  WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$kitSQL = "Select joining_kit_id,kit_name FROM joining_kit_master WHERE status = 'Y'";
		$kitRes = $this->db->query($kitSQL);
		$kitInfo = $kitRes->result_array();
		$this->mViewData['kitInfo'] = $kitInfo;
		
		
		$success = "";
		$error = "";
		if($this->input->post('btnUpdateDoc') == 'Save')
		{   
				$docid='';
				$fname = 'letter_issued'; 
				if($_FILES['file']['error'] == 0 && $_FILES['file']['type'] =='application/pdf')
				{                            					
					if(($_FILES['file']['name']) !=""){
						$path = $_FILES['file']['name'];
						$filename = strtolower(str_replace(' ','',$empInfo[0]["loginhandle"] ."_".$fname[0]."_".date("YmdHis").'.'.pathinfo($path, PATHINFO_EXTENSION)));
						$config['upload_path'] = APPPATH.'../assets/upload/document/';
						$config['allowed_types'] = 'pdf';
						$config['file_name'] = $filename;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if($this->upload->do_upload('file')){
							$fileData = $this->upload->data();
							$issueDate = "";
							if($this->input->post("txtIssuedDate") != ""){
								$issueDate = date("Y-m-d", strtotime($this->input->post("txtIssuedDate")));
							}else{
								$issueDate = date("Y-m-d");
							}
							$insertIUESql = "INSERT INTO emp_letter (login_id, letter_name, letter_document, issued_date) VALUES
                        ('".$user_id."', '".$this->input->post("txtLetterTittle")."', '".$filename."', '".$issueDate."')";
							$this->db->query($insertIUESql);
							$success = 'Uploaded Successfully';
						}
					}
				}
				else
				{
					echo $this->UploadException($_FILES['file']['error']); 
					$error = 'Something went Wrong';
				}
		   } 
		
		$letterSQL = "SELECT l.letter_id, l.letter_name, l.letter_document, l.issued_date FROM emp_letter AS l WHERE l.login_id = " . $user_id;
		$letterRES = $this->db->query($letterSQL);
		$letterRow = $letterRES->result_array(); 
		$this->mViewData['joinInfo'] = $letterRow; 
		
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->mViewData['pageTitle']    = 'Edit Letter Issued';
		$this->render('myprofile/letter_issued_update_emp_view', 'full_width',$this->mViewData);
	}
	
	function exp_save_skills()
	{
		$skill = $this->input->post('skill');
		$experience = $this->input->post('experience');
		$user_id = $this->session->userdata('user_id');
		if($this->input->post('type') == "insert")
		{
			$query = "insert into exp_skill (login_id, skill_name, skill_exp) values ('".$user_id."', '".$skill."', '".$experience."')";	
		}
		else if($this->input->post('type') == "update")
		{
			$query = "update exp_skill set skill_name = '".$skill."', skill_exp = '".$experience."', login_id = '".$user_id."' where id = '".$this->input->post('id')."'";
		}
		else if($this->input->post('type') == "delete")
		{
			$query = "delete from exp_skill where id= '".$this->input->post('id')."' ";
		}
		
		$this->db->query($query);
		
		echo true;
	}
	
	
}
