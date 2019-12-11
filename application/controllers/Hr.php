<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(0);
class Hr extends MY_Controller 
{ 
	var $data = array('visit_type' => '','pageTitle' => '','file' =>''); 
	public function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', 600); // 10 mins
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
		
		$this->load->library('session');
		$this->load->model('Hr_model');
		$this->load->model('Loan_model');
		$this->load->model('Performance_model');
		$this->load->model('Expenses_reimbrusement_model');
		$this->load->model('Myprofile_model');
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
	//Start employee manament
	
	public function profile_list()
	{
		$this->mViewData['pageTitle'] = 'Profile Search&View';
		$this->render('hr/employee_management/profile_list_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/profile_list_script');
	}
	
	
	public function get_active_employee()
	{
		$result = $this->Hr_model->get_active_employee(); 
		echo json_encode($result);       
	}

	
	public function inactive_profile_list()
	{
		$this->mViewData['pageTitle'] = 'Inactive employee';
		$this->render('hr/employee_management/inactive_profile_list_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/inactive_profile_list_script');
	}
	
	
	public function get_inactive_employee()
	{
		$result = $this->Hr_model->get_inactive_employee(); 
		//echo json_encode($result);
		echo json_encode($result);
	}
	
	
	public function  add_employee()
	{
		$this->mViewData['pageTitle'] = 'Create New Employee';
		$this->load->helper(array('form', 'url')); 
        $this->load->library('form_validation');
		//define model
		$this->mViewData['country'] = $this->Hr_model->get_country();
		//var_dump($this->mViewData['country']);
		$this->mViewData['state'] = $this->Hr_model->get_state();
		$this->mViewData['grade'] = $this->Hr_model->get_grade();
		$this->mViewData['level'] = $this->Hr_model->get_level();
		$this->mViewData['qualification'] = $this->Hr_model->get_qualification();
		$this->mViewData['location_of_highest_qualification'] = $this->Hr_model->location_of_highest_qualification();
		$this->mViewData['sourcehire'] = $this->Hr_model->get_source_of_hire(); 
		$this->mViewData['location'] = $this->Hr_model->get_company_location_branch(); 
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one();
		$this->mViewData['reporting'] = $this->Hr_model->get_all_reporting_manager();
		$this->mViewData['employeeDeatils'] = $this->Hr_model->get_active_employee();
		
		$employeeID = "";
		
		$this->mViewData['employeeID'] = $employeeID;
		$this->render('hr/employee_management/add_employee_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/js/create_employee');
		$this->load->view('script/hr/js/reporting_manager');
		
	}
	public function  add_employee_submit()
	{
		$this->load->helper(array('form', 'url')); 
        $this->load->library('form_validation');
		$employeeID = "";
		$doj = date("Y-m-d", strtotime($this->input->post('txtdoj')));
		
		$dConfOrCeDateMonth = "";	
		$dConfOrCeDate = "";
		if($this->input->post('ddlTypeEmp') == 'F')
		{
			$dConfOrCeDateMonth = $this->input->post('due_conf');	
			$dConfOrCeDate = strtotime ( '+'.$dConfOrCeDateMonth.' month' , strtotime ( $doj ) ) ;
		}
		if($this->input->post('ddlTypeEmp') == 'CO')
		{
			$dConfOrCeDateMonth = 0;	
			$dConfOrCeDate = strtotime(date("Y-m-d")) ;
		}
		else
		{
			$dConfOrCeDateMonth = $this->input->post('contract_EndDate');	
			$dConfOrCeDate = strtotime ( '+'.$dConfOrCeDateMonth.' month' , strtotime ( $doj ) ) ;
		}
		$dConfOrCeDate = date("Y-m-d", $dConfOrCeDate);
		
		if($this->input->post('ddlTypeEmp') == 'CO')
		{
			$employeeID = $this->generate_employee_code_consultant($doj, $this->input->post('ddlTypeEmp'), $this->input->post('department'));
		}
		else
		{
			$employeeID = $this->generate_employee_code($doj, $this->input->post('ddlTypeEmp'));
		}
		
		$dob = date("Y-m-d", strtotime($this->input->post('txtdob')));
		$dobWithCY = date("m-d", strtotime($dob));

		// Get Last Salary Sheet ID
		$salSheetIDSql = "SELECT `sal_sheet_sl_no` FROM `internal_user` WHERE `emp_type` = '".$this->input->post('ddlTypeEmp')."' ORDER BY `sal_sheet_sl_no` DESC LIMIT 1";
		$salSheetIDRes = $this->db->query($salSheetIDSql);
		$salSheetIDInfo = $salSheetIDRes->result_array();
		$newSalSheetID=0;
		if(count($salSheetIDInfo)>0){
			$newSalSheetID = $salSheetIDInfo[0]['sal_sheet_sl_no'] + 1;
		}
		
		$i=0;
		do
		{
			$nameAbbr = substr($this->input->post('txtFirstName'),0,1) . substr($this->input->post('txtFirstName'),$i,1). substr($this->input->post('txtLastName'),0,1);//nameAbbr should be 3 chars
			$nameAbbr = strtoupper($nameAbbr);
			$i++;
		}
		while($this->check_unique_name_abbr($nameAbbr));
		//print_r($salSheetIDInfo);
		// Insert Into internal_user
		$insIUQry ="INSERT INTO `internal_user` (`per_email`,`loginhandle`,`name_first`,`name_middle`,`name_last`,
			`full_name`,`name_abbr`,`gender`,`marital_status`,`join_date`,`address1`,
			`city_district1`,`state_region1`,`pin_zip1`,`country1`,`phone1`,
			`mobile`,`branch`,`designation`,`department`,`grade`,`level`,`dob`,
			`highest_qual`,`loc_highest_qual`,`dob_with_current_year`,`blood_group`,`reporting_to`,
			`source_hire`,`refered_employee`,`isAttndAllowance`,`isPerfomAllowance`,`sal_sheet_sl_no`,`due_conform`,`employee_conform`,`emp_type`,`emp_joining_type`,`ex_employee_code`,`leaveCreditedDate`,`leaveCreditedDateSl`)
		VALUES
			('".$this->input->post('txtPEmailID')."', '".$employeeID."', '".$this->input->post('txtFirstName')."', '".$this->input->post('txtMiddleName')."', '".$this->input->post('txtLastName')."',
			'".$this->input->post('txtFullName')."','".$nameAbbr."', '".$this->input->post('rdGender')."', '".$this->input->post('rdMStatus')."', '".$doj."', '".$this->input->post('perAddr')."',
			'".$this->input->post('perDist')."', '".$this->input->post('perState')."', '".$this->input->post('perPin')."', '".$this->input->post('perCountry')."', '".$this->input->post('txtEContNo')."', 
			'".$this->input->post('txtContNo')."', '".$this->input->post('branch')."', '".$this->input->post('designation')."', '".$this->input->post('department')."', '".$this->input->post('grade')."', '".$this->input->post('level')."', '".$dob."', 
			'".$this->input->post('selHgstEdu')."', '".$this->input->post('perLoc')."', '".$dobWithCY."', '".$this->input->post('txtBGroup')."', '".$this->input->post('reporting')."',
			'".$this->input->post('ddlSrcHire')."','".$this->input->post('refered_employee')."','".$this->input->post('attndEligb')."', '".$this->input->post('perofmEligb')."', '".$newSalSheetID."' ,'".$dConfOrCeDateMonth."', '".$dConfOrCeDate."', '".$this->input->post('ddlTypeEmp')."', '".$this->input->post('emp_joining_type')."', '".$this->input->post('ex_employee_code')."', '".$doj."', '".$doj."' )";
			//echo $insIUQry;exit;
		$this->db->query($insIUQry);
		$newLoginID = $this->db->insert_id();;

		// Insert Into compass_user
		$insCUQry = "INSERT INTO `compass_user` (`login_id`, `email`, `name`, `name_abbr`, `ref_id`) VALUES('".$employeeID."', '".$this->input->post('txtPEmailID')."', '".$this->input->post('txtFullName')."', '".$nameAbbr."', '".$newLoginID."')";
		$this->db->query($insCUQry);
		
		if($this->input->post('ddlTypeEmp') == 'F')
		{          
			$insLeaves = "INSERT INTO `leave_carry_ forward` (user_id, year, ob_pl, ob_sl) values ('".$newLoginID."', '".date("Y")."', 0, '1')";
			$this->db->query($insLeaves);
		}
		$this->employeeCreated($this->session->userdata('user_id'), $newLoginID);
		redirect(base_url().'en/hr/general_readonly?id='.$newLoginID);
	}
	
	
	function generate_employee_code($joiningDate,$ddlEmpType)
	{
		// Calculate Financial Year
		$joiningMonth = date("m", strtotime($joiningDate));
		$joiningYear = date("Y", strtotime($joiningDate));
		$joiningyear = date("y", strtotime($joiningDate));

		if($joiningDate < '2012-03-31'){
			$startYear = '2011-08-03';
			$endYear = '2012-03-31';
			$yearCode = '1112';
			
		}
		else
		{
			if($joiningMonth > 3)
			{
				$startYear = $joiningYear.'-04-01';
				$endYear = ($joiningYear + 1).'-03-31';
				
				$plusJoiningyear = $joiningyear + 1;
				$plusJoiningyear = ($plusJoiningyear < 10)?'0'.$plusJoiningyear:$plusJoiningyear;
				$yearCode = $joiningyear.$plusJoiningyear;
			}
			else
			{
				$startYear = ($joiningYear - 1).'-04-01';
				$endYear = $joiningYear.'-03-31';
				
				$minusJoiningyear = $joiningyear - 1;
				$minusJoiningyear = ($minusJoiningyear < 10)?'0'.$minusJoiningyear:$minusJoiningyear;
				$yearCode = $minusJoiningyear.$joiningyear;
			}
		}

		$empQuery = "SELECT `login_id` FROM `internal_user` WHERE `join_date` BETWEEN '$startYear' AND '$endYear' AND `emp_type` = '$ddlEmpType'";
		$empRes = $this->db->query($empQuery);
		$empRow = $empRes->result_array();
		//$empNum = $empRes->row();
		$empNum = count($empRow);
		$newEmpNo = $empNum + 1;

		if($newEmpNo < 10)
		{
			$newEmpNo = '0'.$newEmpNo;
		}

		if($ddlEmpType == 'F'){
			$empCode = 'PTPL-'.$yearCode.$newEmpNo;
		} elseif($ddlEmpType == 'C'){
			$empCode = 'PTPL-C'.$yearCode.$newEmpNo;
		}elseif($ddlEmpType == 'CO'){
			$empCode = 'CONSULTANT-'.$yearCode.$newEmpNo;
		}else {
			$empCode = 'PTPL-I'.$yearCode.$newEmpNo;
		}
		
		$checkEmpCode = $empCode;
		$i=1;
		$j=1;
		while($i == 1){
			$j++;
			$empQuery1 = "SELECT `login_id` FROM `internal_user` WHERE loginhandle='$empCode'";
			$empQuery2 = "SELECT `login_id` FROM `internal_user_change_log` WHERE loginhandle='$empCode'";
			$empRes = $this->db->query($empQuery1." UNION ".$empQuery2);
			$empRow = $empRes->result_array();
			$empNum1 = count($empRow);
			if($empNum1>0){
				$newEmpNo = $empNum + $j;

				if($newEmpNo < 10)
				{
					$newEmpNo = '0'.$newEmpNo;
				}

				if($ddlEmpType == 'F'){
					$empCode = 'PTPL-'.$yearCode.$newEmpNo;
				} elseif($ddlEmpType == 'C'){
					$empCode = 'PTPL-C'.$yearCode.$newEmpNo;
				}elseif($ddlEmpType == 'CO'){
					$empCode = 'CONSULTANT-'.$yearCode.$newEmpNo;
				}else {
					$empCode = 'PTPL-I'.$yearCode.$newEmpNo;
				}
				//$i++;
				//echo $empCode;
			}
			else{
				$i=0;
			}
		}
		
		return $empCode;
	}
	
	function generate_employee_code_consultant($joiningDate,$ddlEmpType,$department)
	{
		// Calculate Financial Year
		$joiningMonth = date("m", strtotime($joiningDate));
		$joiningYear = date("Y", strtotime($joiningDate));
		$joiningyear = date("y", strtotime($joiningDate));

		if($joiningDate < '2012-03-31'){
			$startYear = '2011-08-03';
			$endYear = '2012-03-31';
			$yearCode = '1112';
			
		}
		else
		{
			if($joiningMonth > 3)
			{
				$startYear = $joiningYear.'-04-01';
				$endYear = ($joiningYear + 1).'-03-31';
				
				$plusJoiningyear = $joiningyear + 1;
				$plusJoiningyear = ($plusJoiningyear < 10)?'0'.$plusJoiningyear:$plusJoiningyear;
				$yearCode = $joiningyear.$plusJoiningyear;
			}
			else
			{
				$startYear = ($joiningYear - 1).'-04-01';
				$endYear = $joiningYear.'-03-31';
				
				$minusJoiningyear = $joiningyear - 1;
				$minusJoiningyear = ($minusJoiningyear < 10)?'0'.$minusJoiningyear:$minusJoiningyear;
				$yearCode = $minusJoiningyear.$joiningyear;
			}
		}
		
		$empQuery = "SELECT `login_id` FROM `internal_user` WHERE `join_date` BETWEEN '$startYear' AND '$endYear' AND `emp_type` = '$ddlEmpType'";
		$empRes = $this->db->query($empQuery);
		$empRow = $empRes->result_array();
		//$empNum = $empRes->row();
		$empNum = count($empRow);
		$newEmpNo = $empNum + 1;

		if($newEmpNo < 10)
		{
			$newEmpNo = '0'.$newEmpNo;
		}

		if($department == '1'){
			$empCode = 'CONSULTANT-MANAGEMENT-'.$yearCode.$newEmpNo;
		} else if($department == '2'){
			$empCode = 'CONSULTANT-HR-'.$yearCode.$newEmpNo;
		} else if($department == '3'){
			$empCode = 'CONSULTANT-BD-'.$yearCode.$newEmpNo;
		} else if($department == '4'){
			$empCode = 'CONSULTANT-ACCOUNT-'.$yearCode.$newEmpNo;
		} else if($department == '5'){
			$empCode = 'CONSULTANT-ADMIN-'.$yearCode.$newEmpNo;
		} else if($department == '6'){
			$empCode = 'CONSULTANT-PRODUCTION-'.$yearCode.$newEmpNo;
		} else if($department == '7'){
			$empCode = 'CONSULTANT-SSD-'.$yearCode.$newEmpNo;
		} else if($department == '8'){
			$empCode = 'CONSULTANT-IT-'.$yearCode.$newEmpNo;
		} else{
			$empCode = 'CONSULTANT-'.$yearCode.$newEmpNo;
		}
		return $empCode;
	}

	/**
	* Check For Name Abbr
	*
	*
	* @param	string
	* @return	bool
	*/

	function check_unique_name_abbr($abbrText)
	{
		$nameQuery = "SELECT `login_id` FROM `internal_user` WHERE `name_abbr` = '$abbrText' LIMIT 1";
		$nameRes = $this->db->query($nameQuery);
		$nameRow = $nameRes->result_array();
		$nameNum = count($nameRow);
		if($nameNum == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	function employeeCreated($userID, $empID)
	{
		
		$hrSql = "SELECT `email`, `full_name` FROM `internal_user` WHERE `login_id` = '$userID'";
		$hrRes = $this->db->query($hrSql);
		$hrInfo = $hrRes->result_array();
		
		// get details of new employee
		$newEmpSql = "SELECT i.login_id, i.email, i.loginhandle, i.full_name, i.join_date, d.dept_name, u.desg_name FROM internal_user i INNER JOIN `department` d ON d.dept_id = i.department INNER JOIN `user_desg` u ON u.desg_id = i.designation WHERE i.login_id = '$empID'";
		$newEmpRes = $this->db->query($newEmpSql);
		$newEmpInfo = $newEmpRes->result_array();
			
		// subject
			$subject = "New Employee Created - ".$newEmpInfo[0]['full_name']." (".$newEmpInfo[0]['loginhandle'].")";
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$userID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$userID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$site_base_url=base_url('hr/profile_list');
				
				$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$empInfo [0]['full_name']},</p>                                 
				 <p>You created a new employee in our AABSyS iCompass System. Below are the details of that employee.</p>
				 <p><strong>Name :</strong></td><td>{$newEmpInfo[0]['full_name']}</td></p>
				 <p><strong>Employee Code :</strong>{$newEmpInfo[0]['loginhandle']}</p>
				 <p><strong>Date of Joining :</strong>{$newEmpInfo[0]['join_date']}</p>
				 <p><strong>Department :</strong>{$newEmpInfo[0]['dept_name']}</p>
				 <p><strong>Designation :</strong>{$newEmpInfo[0]['desg_name']}</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;


			$messageADMIN=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear Sir,</p>                                 
				 <p>A new employee is joined in our AABSyS iCompass System. Below are the details of that employee.</p>
				 <p><strong>Name :</strong></td><td>{$newEmpInfo[0]['full_name']}</td></p>
				 <p><strong>Employee Code :</strong>{$newEmpInfo[0]['loginhandle']}</p>
				 <p><strong>Date of Joining :</strong>{$newEmpInfo[0]['join_date']}</p>
				 <p><strong>Department :</strong>{$newEmpInfo[0]['dept_name']}</p>
				 <p><strong>Designation :</strong>{$newEmpInfo[0]['desg_name']}</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
			$to = $hrInfo[0]['full_name'].'<'.$hrInfo[0]['email'].'>';
			$topradeepta = 'SantiBhusan Mishra <hr@polosoftech.com>';
			$torajesh = 'Saurav Mohapatra <saurav.mohapatra@polosoftech.com>';
			$toadmin = 'POLOHRM Admin <saurav.mohapatra@polosoftech.com>';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOHRM <hr@polosoftech.com>' . "\r\n";
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'Reply-To: hr@polosoftech.com,saurav.mohapatra@polosoftech.com'. "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
			mail($topradeepta, $subject, $messageADMIN, $headers);
			mail($torajesh, $subject, $messageADMIN, $headers);
			mail($toadmin, $subject, $messageADMIN, $headers);
		
	}
	
	/**
	* Get Reporting Manager's Name.
	*
	*
	* @param	none
	* @return	string
	*/
	function get_all_reporting_manager()
	{
		$json = [];
		$json = $this->Hr_model->get_all_reporting_manager(); 
		echo json_encode($json);
	}
	//pending work
	public function reset_emp_pwd()
	{
		$this->mViewData['pageTitle'] = 'Reset Password';
		if($this->input->post('btnResetEmpPwd') == 'Reset')
		{
			$upPwdQry = "UPDATE `internal_user` SET `password` = '3d6e89c63cab98b4d95e4ebb908c5cec' WHERE `login_id` = '".$this->input->post('empLoginID')."'";
			$this->input->post($upPwdQry);
			$showPwdReset = TRUE;

			// Mail to Employee
			$this->resetPasswordSuccess($this->input->post('empName'), $this->input->post('empEmail'));

			$_SESSION['showPwdReset'] = TRUE;
			$_SESSION['pwdResetEmpName'] = $this->input->post('empName');

			//header("Location:reset_emp_pwd.php");
			//redirect('hr/reset_emp_pwd');
			//exit();
		}
		$this->render('hr/employee_management/reset_emp_pwd_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/reset_password_script');
	}
	public function show_emp_name()
	{
		$result = $this->Hr_model->show_emp_name(); 
		echo json_encode($result);
	}
	public function reset_emp_pwd_check()
	{
		$txtEmpSearch = strtoupper($this->input->post('txtEmpSearch'));
		$user_id = $this->session->userdata('user_id');
		$check = $this->Hr_model->check_employee($txtEmpSearch, $user_id);
		$datas = array();
		if(count($check) > 0){
			$res = array('emp_name' => $check[0]->full_name);
			array_push($datas , $res);
		}
		echo json_encode($datas);
	}
	
	public function reset_emp_pwd_submit()
	{
		$txtEmpSearch = strtoupper($this->input->post('txtEmpSearch'));
		$user_id = $this->session->userdata('user_id');
		$check = $this->Hr_model->check_employee($txtEmpSearch, $user_id);
		$datas = array();
		if(count($check) > 0){
			$upPwdQry = "UPDATE `internal_user` SET `password` = '3d6e89c63cab98b4d95e4ebb908c5cec' WHERE `loginhandle` = '".$txtEmpSearch."'";
			$upPwdQrys = $this->db->query($upPwdQry);
			$res = array('emp_name' => $check[0]->full_name);
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE loginhandle = '".$txtEmpSearch."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Your Password has been Reset';
			$site_base_url=base_url();
			$messageApp=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$empInfo[0]['full_name']},</p>                                 
				 <p>Your Password has been reset. </p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Login</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;


			$to = $empInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: HR <hr@polosoftech.com>' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headers);
			
			array_push($datas , $res);
		}
		echo json_encode($datas);
	}
	
	
	public function emp_vintage_list()
	{
		$this->mViewData['pageTitle'] = 'Employee Vintage';
		$this->mViewData['dd_vintage_type'] = $this->input->post('dd_vintage_type');
		$this->mViewData['vType'] = '3';
		if($this->input->post('searchVintageEmployee') == 'Find')
		{
			$this->mViewData['vType'] = $this->input->post('dd_vintage_type');
		} 
		$toDay = date("Y-m-d");
		if($this->mViewData['vType'] == '3')
		{
			$fromDate = date("Y-m-d", strtotime("-90 day"));
			$toDate = date("Y-m-d", strtotime("-70 day"));
		}
		elseif($this->mViewData['vType'] == '6')
		{
			$fromDate = date("Y-m-d", strtotime("-190 day"));
			$toDate = date("Y-m-d", strtotime("-170 day"));
		}
		elseif($this->mViewData['vType'] == '12')
		{
			$fromDate = date("Y-m-d", strtotime("-1 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-1 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '24')
		{
			$fromDate = date("Y-m-d", strtotime("-2 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-2 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '36')
		{
			$fromDate = date("Y-m-d", strtotime("-3 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-3 year +10 days"));
		}
		elseif($this->mViewData['vType'] == '60')
		{
			$fromDate = date("Y-m-d", strtotime("-5 year -10 days"));
			$toDate = date("Y-m-d", strtotime("-5 year +10 days"));
		}
		else
		{
			$fromDate = $toDay;
			$toDate = date("Y-m-d", strtotime(" +20 days"));
		} 
		// Get Active Employees
		if($this->mViewData['vType'] == 'C')
		{
			$this->mViewData['num_rows'] = $this->Hr_model->get_emp_vintage_c($fromDate,$toDate);
		}
		else
		{
			$this->mViewData['num_rows'] = $this->Hr_model->get_emp_vintage($fromDate,$toDate);
		}  
		$this->render('hr/employee_management/emp_vintage_list_view', 'full_width',$this->mViewData);
	}
	public function emp_details_import()
	{
		$this->mViewData['pageTitle'] = 'Import Data';
		$this->render('hr/employee_management/emp_details_import_view', 'full_width',$this->mViewData);
	}
	public function emp_report()
	{
		$this->mViewData['pageTitle'] = 'Employee Report';
		//define model 
		$this->mViewData['state'] = $this->Hr_model->get_state();
		$this->mViewData['grade'] = $this->Hr_model->get_grade();
		$this->mViewData['level'] = $this->Hr_model->get_level();
		$this->mViewData['qualification'] = $this->Hr_model->get_qualification();
		$this->mViewData['sourcehire'] = $this->Hr_model->get_source_of_hire();
		$this->mViewData['location'] = $this->Hr_model->get_company_location_branch(); 
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one();
		$this->mViewData['reporting'] = $this->Hr_model->reporting();
		$this->mViewData['reviewing'] = $this->Hr_model->reviewing();
		$this->mViewData['bank'] = $this->Hr_model->bank();
		$this->mViewData['graduation'] = $this->Hr_model->graduation_level_courses();
		$this->mViewData['specialization_grade'] = $this->Hr_model->specialization_grade();
		$this->mViewData['specialization_professional'] = $this->Hr_model->specialization_professional();
		$this->mViewData['board_university'] = $this->Hr_model->board_university();
		$this->mViewData['professional_qualification'] = $this->Hr_model->professional_qualification();
		$this->mViewData['specialization_professional'] = $this->Hr_model->specialization_professional();
		$this->mViewData['separation'] = $this->Hr_model->get_separation_master();
		$this->mViewData['hod'] = $this->Hr_model->hod();
		$this->mViewData['location_of_highest_qualification'] = $this->Hr_model->location_of_highest_qualification();
		//end model
		if($this->input->post('exportEmployee') == "Generate")
		{
			///print_r($_POST['selLevel']);exit;
			$encypt = $this->config->item('masterKey');
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel(); 
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
				->setLastModifiedBy("SantiBhusan Mishra")
				->setTitle("Online HR Master")
				->setSubject("Online HR Master")
				->setDescription("Online HR Master.")
				->setKeywords("Online HR Master")
				->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			if(null !== $this->input->post('loginhandle')){
				array_push($header,"Employee Code");
				$selCols .= ", i.loginhandle";
				$noOfColumnsSelected++;
			}

			if(null !== $this->input->post('full_name')){
				array_push($header, "Name");
				$selCols .= ", i.full_name";
				$noOfColumnsSelected++;
			}
			  
			if(null !== $this->input->post('father_name')){
				array_push($header, "Fathers Name");
				$selCols .= ", f.fathers_name";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mother_name')){
				array_push($header, "Mothers Name");
				$selCols .= ", f.mother_name";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('gender')){
				array_push($header,"Gender");
				$selCols .= ", i.gender";
				$noOfColumnsSelected++;
				if($this->input->post("selGender") != ""){
					$cond .= " AND i.gender = '" . $this->input->post("selGender") . "'";
				}
			}
			if(null !== $this->input->post('dob')){
				array_push($header, "Emp D.O.B");
				$selCols .= ", i.dob";
				$noOfColumnsSelected++;
				if($this->input->post("dobFrom") != ""){
					$cond .= " AND i.dob >= '" . date("Y-m-d", strtotime($this->input->post("dobFrom"))) . "'";
				}
				if($this->input->post("dobTo") != ""){
					$cond .= " AND i.dob <= '" .date("Y-m-d", strtotime($this->input->post("dobTo"))) . "'";
				}
			}

			if(null !== $this->input->post('doj')){
				array_push($header,"DOJ");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
				if($this->input->post("dojFrom") != ""){
					$cond .= " AND i.join_date >= '" . date("Y-m-d", strtotime($this->input->post("dojFrom"))) . "'";
				}
				if($this->input->post("dojTo") != ""){
					$cond .= " AND i.join_date <= '" .date("Y-m-d", strtotime($this->input->post("dojTo"))) . "'";
				}
			}
			if(null !== $this->input->post('doc')){
				array_push($header,"DOC");
				$selCols .= ", i.employee_conform";
				$noOfColumnsSelected++;
				if($this->input->post("docFrom") != ""){
					$cond .= " AND i.employee_conform >= '" . date("Y-m-d", strtotime($this->input->post("docFrom"))) . "'";
				}
				if($this->input->post("docTo") != ""){
					$cond .= " AND i.employee_conform <= '" . date("Y-m-d", strtotime($this->input->post("docTo"))) . "'";
				}
			}
			if(null !== $this->input->post('grade')){
				array_push($header,"Grade");
				$selCols .= ", g.grade_name";
				$noOfColumnsSelected++;
				if($this->input->post("selGrade") != ""){
					$selGrade = implode(", ",$this->input->post("selGrade"));
					$cond .= " AND i.grade IN (" . $selGrade . ")";
				}
			}
			if(null !== $this->input->post('level')){
				array_push($header,"Level");
				$selCols .= ", l.level_name";
				$noOfColumnsSelected++;
				if($this->input->post("selLevel") != ""){
					$cond .= " AND i.level IN (" . $this->input->post("selLevel") . ")";
				}
			}
			if(null !== $this->input->post('dept_name')){
				array_push($header,"Department");
				$selCols .= ", d.dept_name";
				$noOfColumnsSelected++;
				if($this->input->post("selDepartment") != ""){
					$selDepartment = implode(", ",$this->input->post("selDepartment"));
					$cond .= " AND i.department IN (" . $selDepartment . ")";
				}
			}
			if(null !== $this->input->post('desg_name')){
				array_push($header, "Designation");
				$selCols .= ", u.desg_name, i.designation";
				$noOfColumnsSelected++;
				if($this->input->post("selDesignation") != ""){
					$cond .= " AND i.designation IN (" . implode(", ",$this->input->post("selDesignation")) . ")";
				}
			}
			  
			if(null !== $this->input->post('loc')){
				array_push($header, "Location");
				$selCols .= ", b.branch_name";
				if($this->input->post("selLocation") != ""){
					$cond .= " AND i.branch IN (" . implode(", ",$this->input->post("selLocation"))  . ")";
				}
			}
			if(null !== $this->input->post('reporting')){
				array_push($header,  "Reporting Officer");
				$selCols .= ", r.full_name AS reporting";
				$noOfColumnsSelected++;
				if($this->input->post("selReporting") != ""){
					$cond .= " AND i.reporting_to IN (" . implode(", ",$this->input->post("selReporting")) . ")";
				}
			}
			if(null !== $this->input->post('rev_officer')){
				array_push($header, "Reviewing Officer");
				$selCols .= ", rev.full_name AS reviewing";
				$noOfColumnsSelected++;
				if($this->input->post("selReviewing") != ""){
					$cond .= " AND rev.login_id IN (" . implode(", ",$this->input->post("selReviewing")) . ")";
				}
			}
			if(null !== $this->input->post('hod')){
				array_push($header,"HOD Name");
				$selCols .= ", h.full_name AS hod";
				$noOfColumnsSelected++;
				if($this->input->post("selHOD") != ""){
					$cond .= " AND d.login_id IN (" . implode(", ",$this->input->post("selHOD")) . ")";
				}
			}

			if(null !== $this->input->post('exp_aabsys')){
				array_push($header, "Experience in Polosoft");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('exp_others')){
				array_push($header, "Experience Prior to Polosoft");
				$selCols .= ", ie.exp_others";
				$noOfColumnsSelected++;
				if($this->input->post("expOFrom") != ""){
					$cond .= " AND ie.exp_others >= '" . $this->input->post("expOFrom") . "'";
				}
				if($this->input->post("expOTo") != ""){
					$cond .= " AND ie.exp_others <= '" . $this->input->post("expOTo") . "'";
				}
			}
			if(null !== $this->input->post('exp_total')){
				array_push($header,  "Total Exp.");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('age')){
				array_push($header,"Age");
				$selCols .= ", i.dob";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('basic')){
				array_push($header,"Basic");
				$selCols .= ", AES_DECRYPT(sal.basic, '".$encypt."') AS basic";
				$noOfColumnsSelected++;
				if($this->input->post("basicFrom") != ""){
					$cond .= " AND AES_DECRYPT(sal.basic, '".$encypt."') >= " . $this->input->post("basicFrom");
				}
				if($this->input->post("basicTo") != ""){
					$cond .= " AND AES_DECRYPT(sal.basic, '".$encypt."') <= " . $this->input->post("basicTo");
				}
			}
			if(null !== $this->input->post('hra')){
				array_push($header,"HRA");
				$selCols .= ", AES_DECRYPT(sal.hra, '".$encypt."') AS hra";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('conv')){
				array_push($header,  "Conv.");
				$selCols .= ", AES_DECRYPT(sal.conveyance_allowance, '".$encypt."') AS conveyance_allowance";
				$noOfColumnsSelected++;
			}

			if(null !== $this->input->post('gross_salary')){
				array_push($header, "Gross Salary");
				$selCols .= ", AES_DECRYPT(sal.gross_salary, '".$encypt."') AS gross_salary";
				$noOfColumnsSelected++;
				if($this->input->post("gSalFrom") != ""){
					$cond .= " AND AES_DECRYPT(sal.gross_salary, '".$encypt."') >= " . $this->input->post("gSalFrom");
				}
				if($this->input->post("gSalTo") != ""){
					$cond .= " AND AES_DECRYPT(sal.gross_salary, '".$encypt."') <= " . $this->input->post("gSalTo");
				}
			}

			if(null !== $this->input->post('official_mobile')){
				array_push($header,"Official Mobile No");
				$selCols .= ", ie.official_mobile";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('email')){
				array_push($header,  "Official Email Id"); 
				$selCols .= ", i.email";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_comp1')){
				array_push($header, "Previous Company 1");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_deg1')){
				array_push($header, "Previous Designation 1");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('no_exp1')){
				array_push($header, "No. of Years of Experience 1");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_comp2')){
				array_push($header, "Previous Company 2");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_deg2')){
				array_push($header, "Previous Designation 2");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('no_exp2')){
				array_push($header, "No. of Years of Experience 2");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_comp3')){
				array_push($header, "Previous Company 3");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prev_deg3')){
				array_push($header, "Previous Designation 3");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('no_exp3')){
				array_push($header, "No. of Years of Experience 3");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('graduation')){
				array_push($header,"Graduation");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('specializationGrad')){
				array_push($header, "Specialization(Grad)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('grad_passing_year')){
				array_push($header,"Year Of Passing(G)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('grad_percentage')){
				array_push($header, "%age(G)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('grad_board')){
				array_push($header,"Board/University(G)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('edu_catG')){
				array_push($header,"Category of Education(G)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('professional')){
				array_push($header,"Professional Qualification");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('specializationProf')){
				array_push($header, "Specialization(Prof)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prof_passing_year')){
				array_push($header,"Year Of Passing(P)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prof_percentage')){
				array_push($header,  "%age(P)"); 
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('prof_board')){
				array_push($header, "Board/University(P)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('edu_catP')){
				array_push($header,"Category of Education(P)");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('State')){
				array_push($header,"Native State");
				$selCols .= ", s.state_name as State";
				$noOfColumnsSelected++;
				if($this->input->post("selState") != ""){
					$cond .= " AND i.state_region1 IN (" . implode(", ",$this->input->post("selState")) . ")";
				}
			}

			if(null !== $this->input->post('spouse_name')){
				array_push($header,"Spouse Name");
				$selCols .= ", f.spouse_name";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('spouse_dob')){
				array_push($header, "Spouse DOB");
				$selCols .= ", f.spouse_dob";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('anniversary_date')){
				array_push($header,"Anniversary Date");
				$selCols .= ", f.anniversary_date";
				$noOfColumnsSelected++;
				if($this->input->post("selAnniversaryDate") != ""){
					$cond .= " AND DATE_FORMAT(f.anniversary_date, '%m') IN ('" . implode(", ",$this->input->post("selAnniversaryDate")) . "')";
				}
			}
			if(null !== $this->input->post('child1')){
				array_push($header,"Child1");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('child_dob1')){
				array_push($header, "Child1 DOB");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('child2')){
				array_push($header, "Child2");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('child_dob2')){
				array_push($header,"Child2 DOB");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('child3')){
				array_push($header,"Child3");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('child_dob3')){
				array_push($header,"Child3 DOB");
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('per_add')){
				array_push($header,  "Permanent Address");
				$selCols .= ", i.address1, i.city_district1, c.country_name as perCountry, s.state_name as State, i.pin_zip1";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('corr_add')){
				array_push($header, "Correspondence Address");
				$selCols .= ", i.address2, i.city_district2, sc.state_name as CurrentState, cp.country_name, i.pin_zip2";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('phone1')){
				array_push($header, "Contact No. Permanent Address (Landline)");
				$selCols .= ", i.phone1";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mobile1')){
				array_push($header,"Contact No. Permanent  Address (Mobile)");
				$selCols .= ", i.mobile1";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('phone2')){
				array_push($header,"Contact No. Correspondence Address (Landline)");
				$selCols .= ", i.phone2";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mobile')){
				array_push($header,"Contact No. Correspondence Address (Mobile)"); 
				$selCols .= ", i.mobile";
				$noOfColumnsSelected++;
			}
			   
			if(null !== $this->input->post('per_email')){
				array_push($header,"Personal EMail ID");
				$selCols .= ", i.per_email";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('pan_card_no')){
				array_push($header,"PAN Card No.");
				$selCols .= ", i.pan_card_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('drl_no')){
				array_push($header,"Driving License No.");
				$selCols .= ", i.drl_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('voter_id')){
				array_push($header,"Voter ID Card No.");
				$selCols .= ", i.voter_id";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('adharcard_no')){
				array_push($header, "Aadhar Card No.");
				$selCols .= ", ie.adharcard_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('passport_no')){
				array_push($header,"Passport No.");
				$selCols .= ", i.passport_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mediclaim_no')){
				array_push($header, "Mediclaim/ESI No.");
				$selCols .= ", sal.mediclaim_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('blood_group')){
				array_push($header, "Blood Group");
				$selCols .= ", i.blood_group";
				$noOfColumnsSelected++;
				if($this->input->post("selBGroup") != ""){
					$cond .= " AND i.blood_group IN ('" . implode("', '",$this->input->post("selBGroup")) . "')";
				}
			}
			if(null !== $this->input->post('bank')){
				array_push($header, "Bank Name");
				$selCols .= ", ba.bank_name";
				$noOfColumnsSelected++;
				if($this->input->post("selBank") != ""){
					$cond .= " AND sal.bank IN ('" . implode("', '",$this->input->post("selBank")) . "')";
				}
			}
			if(null !== $this->input->post('bank_no')){
				array_push($header,"Bank Account No.");
				$selCols .= ", sal.bank_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('pf_no')){
				array_push($header,"PF No.");
				$selCols .= ", sal.pf_no";
				$noOfColumnsSelected++;
			}

			if(null !== $this->input->post('offer_letter_issued')){
				array_push($header,"Offer Letter Issued Status");
				$selCols .= ", ie.offer_letter_issued";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('appoint_letter_issued')){
				array_push($header,"Appointment Letter Issued Status");
				$selCols .= ", ie.appoint_letter_issued";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('conf_letter_issued')){
				array_push($header,"Confirmation Letter Issued Status");
				$selCols .= ", ie.conf_letter_issued";
				$noOfColumnsSelected++;
			}
			 if(null !== $this->input->post('increment')){
				 array_push($header,"Last Increment");
				$noOfColumnsSelected++;
			 }
			 if(null !== $this->input->post('dop')){
				 array_push($header, "Date of Promotion");
				 $selCols .= ", ie.last_promotion";
				 $noOfColumnsSelected++;
				 if($this->input->post("dopFrom") != ""){
					$cond .= " AND ie.last_promotion >= '" . date("Y-m-d", strtotime($this->input->post("dopFrom"))) . "'";
				 }
				 if($this->input->post("dopTo") != ""){
					$cond .= " AND ie.last_promotion <= '" .date("Y-m-d", strtotime($this->input->post("dopTo"))) . "'";
				 }
			 }
			if(null !== $this->input->post('miscunduct_issue')){
				array_push($header,"Misconduct/Integrity Issues Details");
				$selCols .= ", ie.miscunduct_issue";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('DOR')){
				array_push($header,"Resignation Date");
				$selCols .= ", i.resign_date";
				$noOfColumnsSelected++;
				if($this->input->post("dorFrom") != ""){
					$cond .= " AND i.resign_date >= '" . date("Y-m-d", strtotime($this->input->post("dorFrom"))) . "'";
				 }
				 if($this->input->post("dorTo") != ""){
					$cond .= " AND i.resign_date <= '" .date("Y-m-d", strtotime($this->input->post("dorTo"))) . "'";
				 }
			}
			if(null !== $this->input->post('LWD')){
				array_push($header,"Full & Final Date");
				$selCols .= ", i.lwd_date";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('ff_date')){
				 array_push($header, "Date Of F&F");
				 $selCols .= ", ie.ff_date";
				$noOfColumnsSelected++;
			} 
			if(null !== $this->input->post('ff_amount')){
				array_push($header,"Amount Of F&F");
				$selCols .= ", ie.ff_amount";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('ff_handed_date')){
				array_push($header,"F&F Amount Handed Over Date");
				$selCols .= ", ie.ff_handed_date";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('hrRemark')){
				array_push($header,"Remarks");
				$selCols .= ", i.HR_remark";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('source_hire')){
				array_push($header, "Source of Hire");
				$selCols .= ", sh.source_hire_name";
				$noOfColumnsSelected++;
				if($this->input->post("selHire") != ""){
					$cond .= " AND i.source_hire IN (" . implode(", ",$this->input->post("selHire")) . ")";
				}
			}
			if(null !== $this->input->post('marital_status')){
				array_push($header, "Marital Status");
				$selCols .= ", i.marital_status";
				$noOfColumnsSelected++;
				if($this->input->post("selMarital_status") != ""){
					$cond .= " AND i.marital_status = '" . $this->input->post("selMarital_status") . "'";
				 }
			}
			if(null !== $this->input->post('highest_qual')){
				array_push($header,"Highest Qualification");
				$selCols .= ", i.highest_qual, hq.course_name";
				$noOfColumnsSelected++;
				if($this->input->post("selHQ") != ""){
					$cond .= " AND i.highest_qual IN (" . implode(", ",$this->input->post("selHQ")) . ")";
				 }
			}
			if(null !== $this->input->post('loc_highest_qualActual')){
				array_push($header,"Location of Highest Qualification");
				$selCols .= ", sl.state_name AS loc_highest_qualActual";
				$noOfColumnsSelected++;
				if($this->input->post("selLocationHQ") != ""){
					$cond .= " AND i.loc_highest_qual IN (" . implode(", ",$this->input->post("selLocationHQ")) . ")";
				 }
			}
			if(null !== $this->input->post('confirm_status')){
				array_push($header,"Confirmation Status");
				$selCols .= ", i.confirm_status";
				$noOfColumnsSelected++;
				if($this->input->post("selConfStatus") != ""){
					$cond .= " AND i.confirm_status = '" . $this->input->post("selConfStatus")."'";
				}
			}
			if(null !== $this->input->post('skype')){
				array_push($header,"Skype ID");
				$selCols .= ", i.skype";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('resignReson')){
				array_push($header, "Reason of Separation");
				$selCols .= ", sp.separation_name";
				$noOfColumnsSelected++;
				if($this->input->post("selReaSep") != ""){
					$cond .= " AND i.resign_reason IN (" . implode(", ",$this->input->post("selReaSep")) . ")";
				}
			}

			if(null !== $this->input->post('FnF_status')){
				array_push($header, "FnF Status");
				$selCols .= ", i.FnF_status";
				$noOfColumnsSelected++;
				if($this->input->post("selFnFStatus") != ""){
					$cond .= " AND i.FnF_status = '" . $this->input->post("selFnFStatus")."'";
				}
			}
			if(null !== $this->input->post('emp_type')){
				array_push($header,"Employee Type");
				$selCols .= ", i.emp_type";
				$noOfColumnsSelected++;
				if($this->input->post("selEmpType") != ""){
					$cond .= " AND i.emp_type IN ('" . implode("', '",$this->input->post("selEmpType")) . "')";
				}
			}
			if(null !== $this->input->post('emp_status_type')){
				array_push($header,"Employee Status Type");
				$selCols .= ", i.emp_status_type";
				$noOfColumnsSelected++;
				if($this->input->post("selEmpStatusType") != ""){
					$cond .= " AND i.emp_status_type IN ('" . implode("', '",$this->input->post("selEmpStatusType")) . "')";
				}
			}
			if(null !== $this->input->post('user_status')){
				array_push($header,"Active/Inactive");
				$selCols .= ", i.user_status";
				$noOfColumnsSelected++;
				if($this->input->post("selEmpStatus") != ""){
					$cond .= " AND i.user_status = '" . $this->input->post("selEmpStatus") . "'";
				 }
			}
			if(null !== $this->input->post('actual_skill')){
				array_push($header, "Actual Skill");
				$selCols .= ", ie.skills";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('required_skill')){
				array_push($header, "Required Skill");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('actual_exp')){
				array_push($header, "Actual Exp.");
				$selCols .= ", i.designation, i.join_date";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('required_exp')){
				array_push($header, "Required Exp.");
				$selCols .= ", i.designation, i.join_date";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('actual_edu')){
				array_push($header, "Actual Edu.");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('required_edu')){
				array_push($header, "Required Edu.");
				$selCols .= ", i.designation";
				$noOfColumnsSelected++;
			}

			foreach($header AS $i => $head){
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
			
			if($noOfColumnsSelected == 2)
			{
				if(null !== $this->input->post('dept_name') && null !== $this->input->post('hod'))
				{
					$empDetailsQry = "SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond;
				}
			}
			
			$empDetailsInfo_one = $this->Hr_model->empDetails($selCols,$cond);
			//print_r($empDetailsInfo_one);exit;
			//$empDetailsRes = $this->db->query($empDetailsQry);
			//$empDetailsInfo = $empDetailsRes->result_array();
			//var_dump($empDetailsInfo_one);
			//return $empDetailsInfo;
			$empDetailsNum = count($empDetailsInfo_one);

			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai = 0;
				foreach($empDetailsInfo_one as $empDetailsInfo)
				{ 
					$i++;
					$processEmpSummaryArray = true;
					if(null !== $this->input->post('doj')){
						$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
					}
					
					$month_diff = 0;
					if(null !== $this->input->post('doj'))
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					
					if($this->input->post("expAABSySFrom") != "" && $processEmpSummaryArray){
						if($month_diff < $this->input->post("expAABSySFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("expAABSySTo") != "" && $processEmpSummaryArray){
						if($month_diff > $this->input->post("expAABSySTo")){
							$processEmpSummaryArray = false;
						}
					}
					$totalExp = 0;
					if(null !== $this->input->post('exp_others')){
						$totalExp = $month_diff + $empDetailsInfo['exp_others'];
					}
					if($this->input->post("expFrom") != "" && $processEmpSummaryArray){
						if($totalExp < $this->input->post("expFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("expTo") != "" && $processEmpSummaryArray){
						if($totalExp > $this->input->post("expTo")){
							$processEmpSummaryArray = false;
						}
					}
					
					$DOB = $age = "";
					if(null !== $this->input->post('age')){
						if($empDetailsInfo['dob'] != "0000-00-00")
						{
							$DOB = date("d-m-Y", strtotime($empDetailsInfo['dob']));
							$age = $this->getDifferenceFromNow($empDetailsInfo['dob'], 6);
						}
					}
					
					if($this->input->post("ageFrom") != "" && $processEmpSummaryArray){
						if($age < $this->input->post("ageFrom")){
							$processEmpSummaryArray = false;
						}
					}
					if($this->input->post("ageTo") != "" && $processEmpSummaryArray){
						if($age > $this->input->post("ageTo")){
							$processEmpSummaryArray = false;
						}
					}
					
					$CONFORM =" ";
					if(null !== $this->input->post('doc')){
						if($empDetailsInfo['employee_conform'] != "0000-00-00" && $empDetailsInfo['employee_conform'] != NULL)
						{
							$CONFORM = date("d-m-Y", strtotime($empDetailsInfo['employee_conform']));
						}
					}
					
					$DOR ="";
					if(null !== $this->input->post('DOR')){
						if($empDetailsInfo['resign_date'] != "0000-00-00" && $empDetailsInfo['resign_date'] != NULL)
						{
							$DOR = date("d-m-Y", strtotime($empDetailsInfo['resign_date']));
						}	
					}
					
					$LWD ="";
					if(null !== $this->input->post('LWD')){
						if($empDetailsInfo['lwd_date'] != "0000-00-00" && $empDetailsInfo['lwd_date'] != NULL)
						{
							$LWD = date("d-m-Y", strtotime($empDetailsInfo['lwd_date']));
						}
					}
					
					$emp_type = "";
					if(null !== $this->input->post('emp_type')){
						if($empDetailsInfo['emp_type'] == "C")
						{
							$emp_type = "Contractual";
						}
						elseif($empDetailsInfo['emp_type'] == "I")
						{
							$emp_type = "Interns";
						}
						else
						{
							$emp_type = "Full Time";
						}
					}
					
					$emp_status_type ="";
					if(null !== $this->input->post('emp_status_type')){
						$emp_status_type = $empDetailsInfo['emp_status_type'];
					}
					
					$status="Inactive";
					if(null !== $this->input->post('user_status')){
						if($empDetailsInfo['user_status'] == 1)
						{
								$status="Active";
						}
					}
					
					$gender ="Male";
					if(null !== $this->input->post('gender')){
						if($empDetailsInfo['gender'] == 'F')
						{
								$gender ="Female";
						}
					}
					
					$marital_status ="Single";
					if(null !== $this->input->post('marital_status')){
						if($empDetailsInfo['marital_status'] == 'M')
						{
								$marital_status ="Married";
						}
					}
					
					if(null !== $this->input->post('per_add')){
						$permanentAddress = str_replace(array(" ",'%26','%3A','+','%2F','%29','%28','%2C','x','<br>',"\r\n","\n","\r")," ",$empDetailsInfo['address1']." ".$empDetailsInfo['city_district1'].",".$empDetailsInfo['State'].",".$empDetailsInfo['perCountry']." ".$empDetailsInfo['pin_zip1'] );
					}
					if(null !== $this->input->post('corr_add')){
						$currentAdress = str_replace(array(" ",'%26','%3A','+','%2F','%29','%28','%2C','x','<br>',"\r\n","\n","\r")," ",$empDetailsInfo['address2']." ".$empDetailsInfo['city_district2'].",".$empDetailsInfo['CurrentState'].",".$empDetailsInfo['country_name']." ".$empDetailsInfo['pin_zip2'] );
					}
					if(null !== $this->input->post('hrRemark')){
						$hrRemark = str_replace(array(" ",'+','%26','%3A','%2F','%29','%28','%2C','x',"\r\n","\n","\r")," ",$empDetailsInfo['HR_remark']);
					}

					$spouse_dob = $anniversary_date = $ff_date = $ff_handed_date = $dop = "";
					if(null !== $this->input->post('spouse_dob')){
						if($empDetailsInfo['spouse_dob'] != "" && $empDetailsInfo['spouse_dob'] != "0000-00-00"){
							$spouse_dob = date("d-m-Y", strtotime($empDetailsInfo['spouse_dob']));
						}
					}
					
					if(null !== $this->input->post('anniversary_date')){
					if($empDetailsInfo['anniversary_date'] != "" && $empDetailsInfo['anniversary_date'] != "0000-00-00"){
						$anniversary_date = date("d-m-Y", strtotime($empDetailsInfo['anniversary_date']));
					}
					}

					if(null !== $this->input->post('ff_date')){
					if($empDetailsInfo['ff_date'] != "" && $empDetailsInfo['ff_date'] != "0000-00-00"){
						$ff_date = date("d-m-Y", strtotime($empDetailsInfo['ff_date']));
					}
					}

					if(null !== $this->input->post('ff_handed_date')){
					if($empDetailsInfo['ff_handed_date'] != "" && $empDetailsInfo['ff_handed_date'] != "0000-00-00"){
						$ff_handed_date = date("d-m-Y", strtotime($empDetailsInfo['ff_handed_date']));
					}
					}
					
					if(null !== $this->input->post('dop')){
					if($empDetailsInfo['last_promotion'] != "" && $empDetailsInfo['last_promotion'] != "0000-00-00"){
						$dop = date("d-m-Y", strtotime($empDetailsInfo['last_promotion']));
					}
					}
					
					if(null !== $this->input->post('prev_comp1') OR null !== $this->input->post('prev_deg1') OR null !== $this->input->post('no_exp1') OR null !== $this->input->post('prev_comp2') OR null !== $this->input->post('prev_deg2') OR null !==$this->input->post('no_exp2') OR null !== $this->input->post('prev_comp3') OR null !== $this->input->post('prev_deg3') OR null !== $this->input->post('no_exp3'))
					{
						
						//Get Experience Information
						$experience = array();
						//$expSQL = "SELECT comp_name, designation, experince FROM exp_info WHERE login_id = ".$empDetailsInfo['login_id']." LIMIT 3";
						$expSQL = "SELECT comp_name, designation, experince FROM exp_info WHERE login_id = '".$empDetailsInfo["login_id"]."' LIMIT 3";
						//echo $expSQL;
						$expRES = $this->db->query($expSQL);
						$expINFO = $expRES->result_array();
						$expNUM = count($expINFO);
						if($expNUM > 0)
						{ 
							$ec = 0; 
							foreach($expINFO as $row){ 
								$experience[$ec++] = array($row["comp_name"], $row["designation"], $row["experince"]);
								//var_dump($experience);
							}
						}
					}
					
					$graduation = array();
					if((null !== $this->input->post('graduation') OR null !== $this->input->post('actual_edu') OR null !== $this->input->post('required_edu') OR null !== $this->input->post('grad_passing_year') OR null !==$this->input->post('grad_percentage') OR null !==$this->input->post('grad_board')) && $processEmpSummaryArray)
					{ 
						//Get Graduation Details
						$graduation = array();
						$eduCond = "";
						if($this->input->post("selGraduation") != ""){
							$eduCond .= " AND c.course_id IN (".implode(", ",$this->input->post("selGraduation")).")";
						}
						 if($this->input->post("selSpecializationGrad") != ""){
							$eduCond .= " AND spl.specialization_id  IN ('".implode("', '",$this->input->post("selSpecializationGrad"))."')";
						}
						if($this->input->post("gYOPFrom") != ""){
							$eduCond .= " AND e.passing_year >= " . $this->input->post("gYOPFrom");
						}
						if($this->input->post("gYOPTo") != ""){
							$eduCond .= " AND e.passing_year <= " . $this->input->post("gYOPTo");
						}
						if($this->input->post("selgBorU") != ""){
							$eduCond .= " AND b.board_university_id IN ('".implode("', '",$this->input->post("selgBorU"))."')";
						}
						if($this->input->post("selEduCategoryG") != ""){
							$eduCond .= " AND c.course_category  = '".$this->input->post("selEduCategoryG")."'";
						}
						if($this->input->post("grad_perFrom") != ""){
							$eduCond .= " AND e.percentage >= " . $this->input->post("grad_perFrom");
						}
						if($this->input->post("grad_perTo") != ""){
							$eduCond .= " AND e.percentage <= " . $this->input->post("grad_perTo");
						}
						
						/* $educationSQL = "SELECT c.course_name, spl.specialization_name, e.passing_year, e.percentage, c.course_type, c.course_category, b.board_university_name
								FROM education_info AS e INNER JOIN course_info AS c ON e.course_id = c.course_id 
								INNER JOIN board_university_master AS b ON b.board_university_id = e.board_id 
								LEFT JOIN `specialization_master` spl ON spl.specialization_id = e.specialization_id 
								WHERE e.login_id = ".$empDetailsInfo['login_id']." AND c.course_type = 'Graduation' ".$eduCond." ORDER BY e.education_id DESC LIMIT 1"; */
						$educationSQL = "SELECT c.course_name, spl.specialization_name, e.passing_year, e.percentage, c.course_type, c.course_category, b.board_university_name
								FROM education_info AS e INNER JOIN course_info AS c ON e.course_id = c.course_id 
								INNER JOIN board_university_master AS b ON b.board_university_id = e.board_id 
								LEFT JOIN `specialization_master` spl ON spl.specialization_id = e.specialization_id 
								WHERE e.login_id = '".$empDetailsInfo["login_id"]."' AND c.course_type = 'Graduation' ".$eduCond." ORDER BY e.education_id DESC LIMIT 1";
								//echo $educationSQL;
						$educationRES = $this->db->query($educationSQL);
						$educationINFO = $educationRES->result_array();
						$educationNUM = count($educationINFO);
						if($educationNUM > 0)
						{
							
							$graduation[] = $educationINFO[0]["course_name"];
							$graduation[] = $educationINFO[0]["passing_year"];
							$graduation[] = $educationINFO[0]["percentage"];
							$graduation[] = $educationINFO[0]["board_university_name"];
							$graduation[] = $educationINFO[0]["course_category"];
							$graduation[] = $educationINFO[0]["specialization_name"];
							/* foreach($educationINFO as $row){
								$graduation = array($row["course_name"],$row["passing_year"],$row["percentage"],$row["board_university_name"],$row["course_category"],$row["specialization_name"]);
							} */ 
							//var_dump($course_name);
						}
						if($eduCond != "" && $educationNUM == 0)
						{
							$processEmpSummaryArray = false;
						}
					}
					
						$professional = array();
					if((null !== $this->input->post('professional') OR null !== $this->input->post('actual_edu') OR null !== $this->input->post('required_edu') OR null !== $this->input->post('prof_passing_year') OR null !== $this->input->post('prof_percentage') OR null !== $this->input->post('prof_board')) && $processEmpSummaryArray)
					{
						//Get Professional Details
						$eduCond = "";
						if($this->input->post("selProfessional") != "")
						{
							$eduCond .= " AND c.course_id IN (".implode(", ",$this->input->post("selProfessional")).")";
						}
						if($this->input->post("selSpecializationProf") != "")
						{
							$eduCond .= " AND spl.specialization_id  = '".$this->input->post("selSpecializationProf")."'";
						}
						if($this->input->post("pYOPFrom") != "")
						{
							$eduCond .= " AND e.passing_year >= " . $this->input->post("pYOPFrom");
						}
						if($this->input->post("pYOPTo") != "")
						{
							$eduCond .= " AND e.passing_year <= " . $this->input->post("pYOPTo");
						} 
						if($this->input->post("selpBorU") != "")
						{
							$eduCond .= " AND b.board_university_id IN (".implode(", ",$this->input->post("selpBorU")).")";
						}
						if($this->input->post("selEduCategoryP") != "")
						{
							$eduCond .= " AND c.course_category  = '".$this->input->post("selEduCategoryP")."'";
						}
						if($this->input->post("prof_perFrom") != ""){
							$eduCond .= " AND e.percentage >= " . $this->input->post("prof_perFrom");
						}
						if($this->input->post("prof_perTo") != "")
						{
							$eduCond .= " AND e.percentage <= " . $this->input->post("prof_perTo");
						}
						$educationSQL = "SELECT c.course_name, e.passing_year, spl.specialization_name, e.percentage, c.course_type, c.course_category, b.board_university_name
								FROM education_info AS e INNER JOIN course_info AS c ON e.course_id = c.course_id 
								INNER JOIN board_university_master AS b ON b.board_university_id = e.board_id 
								LEFT JOIN `specialization_master` spl ON spl.specialization_id = e.specialization_id 
								WHERE e.login_id = '".$empDetailsInfo["login_id"]."' AND c.course_type = 'Professional' ".$eduCond." ORDER BY e.education_id DESC LIMIT 1";
						//exit;
						$educationRES = $this->db->query($educationSQL);
						$educationINFO = $educationRES->result_array();
						$educationNUM = count($educationINFO);
						if($educationNUM > 0)
						{
							/* foreach($educationINFO as $row){
								$professional = array($row["course_name"],$row["passing_year"],$row["percentage"],$row["board_university_name"],$row["course_category"],$row["specialization_name"]);
							} */ 
							$professional[] = $educationINFO[0]["course_name"];
							$professional[] = $educationINFO[0]["passing_year"];
							$professional[] = $educationINFO[0]["percentage"];
							$professional[] = $educationINFO[0]["board_university_name"];
							$professional[] = $educationINFO[0]["course_category"];
							$professional[] = $educationINFO[0]["specialization_name"]; 
						}
						if($eduCond != "" && $educationNUM == 0)
						{
						   $processEmpSummaryArray = false;
						}
					}
					
						$child = array();
					if(null !== $this->input->post('child1') OR null !== $this->input->post('child_dob1') OR
						null !== $this->input->post('child2') OR null !== $this->input->post('child_dob2') OR
						null !== $this->input->post('child3') OR null !== $this->input->post('child_dob3'))
					{
						//Get Child Information
						$childSQL = "SELECT child_name, child_dob FROM child_info WHERE login_id = '".$empDetailsInfo["login_id"]."' LIMIT 3";
						//echo $childSQL;
						$childRES = $this->db->query($childSQL);
						$childINFO = $childRES->result_array();
						$childNUM = count($childINFO);
						if($childNUM > 0)
						{ 
							foreach($childINFO as $row)
							{
								$cc = 0;
								$childDOB = "";
								if($row['child_dob'] != "" && $row['child_dob'] != "0000-00-00")
								$childDOB = date("d-m-Y", strtotime($row['child_dob'])); 
								$child[] = array($row["child_name"], $childDOB);
							}
						}
					}
					
					if(null !== $this->input->post('increment'))
					{
						//Get Last Increment
						$increment = 0;
						$incrementSQL = "SELECT increament FROM emp_increament_info WHERE login_id = '".$empDetailsInfo["login_id"]."' LIMIT 1";
						$incrementRES = $this->db->query($incrementSQL);
						$incrementINFO = $incrementRES->result_array();
						$incrementNUM = count($incrementINFO);
						if($incrementNUM == 1)
						{
							foreach($incrementINFO as $row)
							{
								$increment = $row["increament"];
							} 
						}
					}
					
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);
						if(null !== $this->input->post('loginhandle'))
							array_push($empSummary,$empDetailsInfo['loginhandle']);
						if(null !== $this->input->post('full_name'))
							array_push($empSummary, $empDetailsInfo['full_name']);
						if(null !== $this->input->post('father_name'))
							array_push($empSummary, $empDetailsInfo['fathers_name']);
						if(null !== $this->input->post('mother_name'))
							array_push($empSummary, $empDetailsInfo['mother_name']);
						if(null !== $this->input->post('gender'))
							array_push($empSummary, $gender); 
						if(null !== $this->input->post('dob'))
							array_push($empSummary, $DOB); 
						if(null !== $this->input->post('doj'))
							array_push($empSummary,$DOJ);
						if(null !== $this->input->post('doc'))
							array_push($empSummary,$CONFORM);
						if(null !== $this->input->post('grade'))
							array_push($empSummary,  $empDetailsInfo['grade_name']);
						if(null !== $this->input->post('level'))
							array_push($empSummary,  $empDetailsInfo['level_name']);
						if(null !== $this->input->post('dept_name'))
							array_push($empSummary,  $empDetailsInfo['dept_name']);
						if(null !== $this->input->post('desg_name'))
							array_push($empSummary, $empDetailsInfo['desg_name']);
						if(null !== $this->input->post('loc'))
							array_push($empSummary, $empDetailsInfo['branch_name']);
						if(null !== $this->input->post('reporting'))
							array_push($empSummary, $empDetailsInfo['reporting']);
						if(null !== $this->input->post('rev_officer'))				  
							array_push($empSummary, $empDetailsInfo['reviewing']);
						if(null !== $this->input->post('hod'))
							array_push($empSummary,$empDetailsInfo['hod']); 
						if(null !== $this->input->post('exp_aabsys'))
							array_push($empSummary, round(($month_diff/12),1));
						if(null !== $this->input->post('exp_others'))
							array_push($empSummary, round(($empDetailsInfo['exp_others']/12),1));
						if(null !== $this->input->post('exp_total')){
							array_push($empSummary, $totalExp);
						}
						if(null !== $this->input->post('age'))
							array_push($empSummary,round(($age/12),1));
						if(null !== $this->input->post('basic'))
							array_push($empSummary, $empDetailsInfo['basic']);
						if(null !== $this->input->post('hra'))
							array_push($empSummary, $empDetailsInfo['hra']);
						if(null !== $this->input->post('conv'))
							array_push($empSummary, $empDetailsInfo['conveyance_allowance']); 
						if(null !== $this->input->post('gross_salary'))
							array_push($empSummary, $empDetailsInfo['gross_salary']); 
						if(null !== $this->input->post('official_mobile'))
							array_push($empSummary, $empDetailsInfo['official_mobile']);
						if(null !== $this->input->post('email'))
							array_push($empSummary, $empDetailsInfo['email']); 
						if(null !== $this->input->post('prev_comp1')){
							if(count($experience)>0){
								array_push($empSummary,$experience[0][0]);
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('prev_deg1')){
							if(count($experience)>0){
								array_push($empSummary, $experience[0][1]); 
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('no_exp1')){
							if(count($experience)>0){
								array_push($empSummary, $experience[0][2]); 
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('prev_comp2')){
							if(count($experience)>1){
								array_push($empSummary,$experience[1][0]);
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('prev_deg2')){
							if(count($experience)>1){
								array_push($empSummary,$experience[1][1]); 
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('no_exp2')){
							if(count($experience)>1){
								array_push($empSummary,$experience[1][2]); 
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('prev_comp3')){
							if(count($experience)>2){
								array_push($empSummary,$experience[2][0]);
							} else{
								array_push($empSummary,'');
							}
						}
						if(null !== $this->input->post('prev_deg3')){
							if(count($experience)>2){
								array_push($empSummary,$experience[2][1]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('no_exp3')){
							if(count($experience)>2){
								array_push($empSummary,$experience[2][2]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('graduation')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[0]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('specializationGrad')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[5]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('grad_passing_year')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[1]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('grad_percentage')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[2]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('grad_board')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[3]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('edu_catG')){
							if(count($graduation)>0){
								array_push($empSummary, $graduation[4]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('professional')){
							if(count($professional)>0){
								array_push($empSummary, $professional[0]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('specializationProf')){
							if(count($professional)>0){
								array_push($empSummary, $professional[5]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('prof_passing_year')){
							if(count($professional)>0){
								array_push($empSummary, $professional[1]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('prof_percentage')){
							if(count($professional)>0){
								array_push($empSummary, $professional[2]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('prof_board')){
							if(count($professional)>0){
								array_push($empSummary, $professional[3]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('edu_catP')){
							if(count($professional)>0){
								array_push($empSummary, $professional[4]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('State'))
							array_push($empSummary, $empDetailsInfo['State']);                       

						if(null !== $this->input->post('spouse_name'))
							array_push($empSummary, $empDetailsInfo['spouse_name']);
						if(null !== $this->input->post('spouse_dob'))
							array_push($empSummary, $spouse_dob);
						if(null !== $this->input->post('anniversary_date'))
							array_push($empSummary, $anniversary_date);
						if(null !== $this->input->post('child1')){
							if(count($child)>0){
								array_push($empSummary, $child[0][0]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !== $this->input->post('child_dob1')){
							if(count($child)>0){
								array_push($empSummary,  $child[0][1]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !==$this->input->post('child2')){
							if(count($child)>1){
								array_push($empSummary, $child[1][0]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !==$this->input->post('child_dob2')){
							if(count($child)>1){
								array_push($empSummary,  $child[1][1]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !==$this->input->post('child3')){
							if(count($child)>2){
								array_push($empSummary, $child[2][0]);
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !==$this->input->post('child_dob3')){
							if(count($child)>2){
								array_push($empSummary,  $child[2][1]); 
							} else{
								array_push($empSummary,'');
							}
						} 
						if(null !==$this->input->post('per_add'))
							array_push($empSummary,  $permanentAddress);
						if(null !==$this->input->post('corr_add'))
							array_push($empSummary,  $currentAdress);
						if(null !==$this->input->post('phone1'))
							array_push($empSummary,  $empDetailsInfo['phone1']);
						if(null !==$this->input->post('mobile1'))
							array_push($empSummary, $empDetailsInfo['mobile1']);	
						if(null !==$this->input->post('phone2'))
							array_push($empSummary,  $empDetailsInfo['phone2']);
						if(null !==$this->input->post('mobile'))
							array_push($empSummary, $empDetailsInfo['mobile']); 
						if(null !==$this->input->post('per_email'))
							array_push($empSummary, $empDetailsInfo['per_email']);
						if(null !==$this->input->post('pan_card_no'))
							array_push($empSummary, $empDetailsInfo['pan_card_no']);
						if(null !==$this->input->post('drl_no'))
							array_push($empSummary, $empDetailsInfo['drl_no']);
						if(null !==$this->input->post('voter_id'))
							array_push($empSummary, $empDetailsInfo['voter_id']);
						if(null !==$this->input->post('adharcard_no'))
							array_push($empSummary, $empDetailsInfo['adharcard_no']);
						if(null !==$this->input->post('passport_no'))
							array_push($empSummary, $empDetailsInfo['passport_no']);
						if(null !==$this->input->post('mediclaim_no'))
							array_push($empSummary, $empDetailsInfo['mediclaim_no']);
						if(null !==$this->input->post('blood_group'))
							array_push($empSummary, $empDetailsInfo['blood_group']);
						if(null !==$this->input->post('bank'))
							array_push($empSummary, $empDetailsInfo['bank_name']);
						if(null !==$this->input->post('bank_no'))
							array_push($empSummary, $empDetailsInfo['bank_no']);
						if(null !==$this->input->post('pf_no'))
							array_push($empSummary, $empDetailsInfo['pf_no']);
						 
						if(null !==$this->input->post('offer_letter_issued'))
							array_push($empSummary, $empDetailsInfo['offer_letter_issued']);
						if(null !==$this->input->post('appoint_letter_issued'))
							array_push($empSummary, $empDetailsInfo['appoint_letter_issued']);
						if(null !==$this->input->post('conf_letter_issued'))
							array_push($empSummary, $empDetailsInfo['conf_letter_issued']);
						if(null !==$this->input->post('increment'))
							array_push($empSummary,$increment);
						if(null !==$this->input->post('dop'))
							array_push($empSummary, $dop);
						if(null !==$this->input->post('miscunduct_issue'))
							array_push($empSummary, $empDetailsInfo['miscunduct_issue']);
						if(null !==$this->input->post('DOR'))
							array_push($empSummary, $DOR);
						if(null !==$this->input->post('LWD'))
							array_push($empSummary, $LWD);
						if(null !==$this->input->post('ff_date'))
							array_push($empSummary, $ff_date);
						if(null !==$this->input->post('ff_amount'))
							array_push($empSummary, $empDetailsInfo['ff_amount']);
						if(null !==$this->input->post('ff_handed_date'))
							array_push($empSummary, $ff_handed_date);
						if(null !==$this->input->post('hrRemark'))
							array_push($empSummary,$hrRemark);
						if(null !==$this->input->post('source_hire'))
							array_push($empSummary, $empDetailsInfo['source_hire_name']);
						if(null !==$this->input->post('marital_status'))
							array_push($empSummary, $marital_status);
						if(null !==$this->input->post('highest_qual'))
							array_push($empSummary, $empDetailsInfo['course_name']);
						if(null !==$this->input->post('loc_highest_qualActual'))
							array_push($empSummary, $empDetailsInfo['loc_highest_qualActual']);
						if(null !==$this->input->post('confirm_status'))
							array_push($empSummary, $empDetailsInfo['confirm_status']);
						if(null !==$this->input->post('skype'))
							array_push($empSummary, $empDetailsInfo['skype']);
						if(null !==$this->input->post('resignReson'))
							array_push($empSummary, $empDetailsInfo['separation_name']);                    
						if(null !==$this->input->post('FnF_status'))
							array_push($empSummary, $empDetailsInfo['FnF_status']);
						if(null !==$this->input->post('emp_type'))
							array_push($empSummary,$emp_type);
						if(null !==$this->input->post('emp_status_type'))
							array_push($empSummary,$emp_status_type);
						if(null !==$this->input->post('user_status'))
							array_push($empSummary,$status);
						
						
						if(null !==$this->input->post('actual_skill'))
						{
							$actuallSkillList = "";                       
								/* query for skills */
								$skillSQL = "SELECT skill_name FROM skills_master";
								$skillRES = $this->db->query($skillSQL);
								$skillINFO = $skillRES->result_array();
								foreach($skillINFO as $row)
								{
									$actuallSkillList .= $row['skill_name'].', ';
								}  
								array_push($empSummary, $actuallSkillList);    
						}
						if(null !==$this->input->post('required_skill')){
							$reqSkillList='';
							$reqSkillSQL = "SELECT s.skill_name FROM minimum_requirement AS r INNER JOIN skills_master AS s ON s.skill_id = r.requirement_type_id WHERE r.designation_id = '".$empDetailsInfo["designation"]."' AND r.requirement_type = 'S'";
							$reqSkillRES = $this->db->query($reqSkillSQL);
							$reqSkillINFO = $reqSkillRES->result_array();
							foreach($reqSkillINFO as $row)
							{
								$reqSkillList .= $row["skill_name"].', '; 
							}
							array_push($empSummary, $reqSkillList);
						}
						if(null !==$this->input->post('actual_exp'))
						{
							array_push($empSummary,$totalExp);
						}
						if(null !==$this->input->post('required_exp')){
							$reqExperience = "Not Defined";
							$reqExpSQL = "SELECT e.experience_name FROM minimum_requirement AS r INNER JOIN experience_master AS e ON e.experience_id = r.requirement_type_id WHERE r.designation_id = '".$empDetailsInfo["designation"]."' AND r.requirement_type = 'W' LIMIT 1";
							$reqExpRES = $this->db->query($reqExpSQL);                        
							$reqExpINFO = $reqExpRES->result_array();
							foreach($reqExpINFO as $row)
							{
								$reqExperience = $row["experience_name"];
							} 
							array_push($empSummary,$reqExperience);
						}
						
						if(null !==$this->input->post('actual_edu'))
						{
							if(count($graduation)>0 && count($professional)>0 ){
								array_push($empSummary, ($graduation[0]!='')?$graduation[0]:$professional[0]); 
							} else{
								array_push($empSummary,'');
							}							
						}
						if(null !==$this->input->post('required_edu'))
						{
							$reqEduINFO='';
							$reqEduSQL = "SELECT c.course_name FROM minimum_requirement AS r INNER JOIN course_info AS c ON c.course_id = r.requirement_type_id WHERE r.designation_id = '".$empDetailsInfo["designation"]."' AND r.requirement_type = 'E'";
							$reqEduRES = $this->db->query($reqEduSQL);
							$reqEduINFO_arr = $reqEduRES->result_array();
							foreach($reqEduINFO_arr as $row)
							{
								$reqEduINFO =  $row["course_name"] ;
							} 
							array_push($empSummary,$reqEduINFO);                          
						}
						$empSummaryArray[$ai++] = $empSummary;
					} 
				}
			} 
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "employee_report_".date("dmYHis").".xls"; 
			
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));
		 
			$objPHPExcel->getActiveSheet()->getStyle("A1:CV1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Employee Report ' . date("M, Y"));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		} 
		$this->render('hr/employee_management/emp_report_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/js/hr_report', $this->mViewData);
	} 
	public function getNameFromNumber($num) 
	{
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0)
		{
			return $this->getNameFromNumber($num2 - 1) . $letter;
		} 
		else
		{
			return $letter;
		}
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
	public function resume_format()
	{
		$this->mViewData['pageTitle'] = 'Resume Format';
		$this->render('hr/employee_management/resume_format_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/resume_format_script');
	}
	
	public function download_resume_format_emp()
	{
		$loginID = "";
		if(isset($_GET['id'])){
			$loginID = $_GET['id'];
		}
		
		$sqlAllEmp = $this->db->query("SELECT r.*,i.*,d.* FROM `resume_form` r LEFT JOIN `internal_user` i ON r.login_id=i.login_id LEFT JOIN `user_desg` d ON d.desg_id = i.designation  WHERE r.login_id = '".$loginID."'");
		$resAllEmp = $sqlAllEmp->result_array(); 
		$datas['rowResume']=$resAllEmp;
		
		$qryComp = $this->db->query("SELECT * FROM `resume_comp` WHERE login_id = '".$resAllEmp[0]['login_id']."'");
		$rowComp_arr = $qryComp->result_array();
		$rowComp = array();
		for($i=0; $i<count($rowComp_arr); $i++){
			$qryCompProject = $this->db->query("SELECT * FROM `resume_comp_project` WHERE cid = '".$rowComp_arr[$i]['cid']."'");
            $rowCompProjects = $qryCompProject->result_array();
			$data = array(
				'rowComps' => $rowComp_arr[$i],
				'rowCompProjects' => $rowCompProjects
			);
			array_push($rowComp, $data);
		}
		$datas['rowComp']=$rowComp;
		
		$qryLang = $this->db->query("SELECT * FROM `resume_lang` WHERE login_id = '".$resAllEmp[0]['login_id']."'");
        $resLang = $qryLang->result_array();                                            
		$datas['rowLang']=$resLang;
		
		$html=$this->load->view('hr/employee_management/download_resume_format', $datas, true); 
		//echo $html;
		
		$pdfFilePath = "resume_form-" .date('dmYHis'). ".pdf";
		$this->load->library('Pdf');

		$pdf = $this->pdf->load();

		$pdf->WriteHTML($html); // write the HTML into the PDF

		$pdf->Output($pdfFilePath, "D");	
	}
	public function get_active_emp_resume()
	{
		$result = $this->Hr_model->get_active_emp_resume(); 
		echo json_encode($result); 
	}
	//end employee managemnt
	
	//Start Attendance entry
	
	public function gtd($t1,$t2) {
        //if (($t1 = strtotime($t1)) === false) die ("Input string 1 unrecognized");
        //if (($t2 = strtotime($t2)) === false) die ("Input string 2 unrecognized");

        $t1 = strtotime($t1);
        $t2 = strtotime($t2);
        
        if($t1 < $t2) 
                {
                $d1 = getdate($t2);
                $d2 = getdate($t1); 
                }
        else
                {
                $d1 = getdate($t1);
                $d2 = getdate($t2);
                }

        foreach ($d1 as $k => $v) 
                {
					$d1[$k] =  (int)$d1[$k] - (int)$d2[$k] ;  
                }


        if ($d1['seconds'] < 0 ) 
                {
                $d1['seconds'] +=60 ;
                $d1['minutes'] -=1;
                }

        if ($d1['minutes'] < 0 ) 
                {
                $d1['minutes'] +=60 ;
                $d1['hours'] -=1;
                }

        if ($d1['hours'] < 0 ) 
                {
                $d1['hours'] +=24 ;
                $d1['yday'] -=1;
                }

        if ($d1['yday'] < 0 ) 
                {
                $d1['yday'] +=365 ;
                $d1['year'] -=1;
                }
        return ($d1);
	}
	
	
	public function resignation_approve_reject()
	{
		$this->mViewData['pageTitle']    = 'Resignation Approve or reject';
		//Template view
		$this->render('hr/employee_management/resignation_approve_reject_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr/view_my_resignation_application_script');		
	}
	
	public function get_all_resignation_application_details()
	{  
		$result = $this->Hr_model->get_all_resignation_application_details(); 
		echo json_encode($result); 
	}
	
	public function get_all_resignation_application_details_search()
	{ 
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_all_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode, $searchYear); 
		echo json_encode($result); 
	}
    public function update_resignation_application_approved()
    {
		$rid = $this->input->post('rid');
		$remarks = $this->input->post('remarks');
        $result = $this->Hr_model->update_resignation_application_approved($rid,$remarks);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `resignation` WHERE rid = '".$rid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		/* $repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array(); */
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Resignation Request For Approved - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		/* $site_base_url=base_url('hr/resignation_approve_reject');
		
		$message=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear {$repInfo[0]['full_name']},</p>                                 
			 <p>Resignation of {$empInfo[0]['full_name']} has rejected by you. </p>  
			 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD; */
		
		$site_base_urlemp=base_url('hr_help_desk/my_resignation_application');
		$messageemp=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear {$empInfo[0]['full_name']},</p>                                 
			 <p>Your Resignation has approved by HR. </p>  
			 <p><a href="{$site_base_urlemp}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;

			//$to =$repInfo[0]['email'];
			$toemp =$empInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($toemp, $subject, $messageemp, $headers); 
			//mail($to, $subject, $message, $headers);
    }
    public function update_resignation_application_rejected()
    {
		$rid = $this->input->post('rid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Hr_model->update_resignation_application_rejected($rid, $reject_reason);
        echo json_encode($result);
		
		
		$rSql = "SELECT login_id FROM `resignation` WHERE rid = '".$rid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		/* $repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array(); */
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Resignation Request For Rejected - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		/* $site_base_url=base_url('hr/resignation_approve_reject');
		
		$message=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear {$repInfo[0]['full_name']},</p>                                 
			 <p>Resignation of {$empInfo[0]['full_name']} has rejected by you. </p>  
			 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD; */
		
		$site_base_urlemp=base_url('hr_help_desk/my_resignation_application');
		$messageemp=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear {$empInfo[0]['full_name']},</p>                                 
			 <p>Your Resignation has rejected by HR. </p>  
			 <p><a href="{$site_base_urlemp}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;

			//$to =$repInfo[0]['email'];
			$toemp =$empInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($toemp, $subject, $messageemp, $headers); 
			//mail($to, $subject, $message, $headers);
		
    }
	
	
	public function update_loan_advance_approved_hr()
    {
		$lid = $this->input->post('lid');
		$approveamount = $this->input->post('approveamount');
		$approveinstalment = $this->input->post('approveinstalment');
		$selMonth = $this->input->post('selMonth');
		$selYear = $this->input->post('selYear');
        $result = $this->Hr_model->update_loan_advance_approved_hr($lid, $approveamount, $approveinstalment, $selMonth, $selYear);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `loan_advance_apply` WHERE lid = '".$lid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Loan/Advance Request For Approved - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		$site_base_url=base_url('hr_help_desk/loan_advance_approve_reject');
		
		$message=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">                       
			 <p>Loan/Advance of {$empInfo[0]['full_name']} has approved by HR. </p>  
			 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;
		
			$to ='accounts@polosoftech.com';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].' <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers); 
    }
	
	public function update_loan_advance_rejected_hr()
    {
		$lid = $this->input->post('lid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Hr_model->update_loan_advance_rejected_hr($lid, $reject_reason);
        echo json_encode($result);
    }
	//************** END/ EMployee Management     ***************//
	
	/*public function biometric_data_upload()
	{
		$this->mViewData['pageTitle'] = 'Biometric Data Upload';
		$successMsg = FALSE;
		$errMsg = "";
		$this->load->library('PHPExcel');  
		if($this->input->post('btnUploadBiometricData') == 'Upload'){
			//if($_FILES['flBiometricSheet']['name'] != '' && $_FILES['flBiometricSheet']['size'] > 0 && $_FILES['flBiometricSheet']['type'] == 'application/octet-stream')
			if($_FILES['flBiometricSheet']['name'] != '' && $_FILES['flBiometricSheet']['size'] > 0 )
			{
				//$path = $_FILES['flBiometricSheet']['name'];
				//$fileName = $_FILES['flBiometricSheet']['name'];
				 $fileName = strtolower(str_replace(array(' ', '-'),'',date("YmdHis") ."_". $_FILES['flBiometricSheet']['name']));
				 // $config['upload_path'] = APPPATH.'../assets/upload/document/';
				 // $config['allowed_types'] = 'xlsx|xls|ods';
				 // $config['file_name'] = $fileName;
				 // $this->load->library('upload', $config);
				 // $this->upload->initialize($config);
				 // $target_path = $config['upload_path'].$fileName;
				 $target_path = APPPATH.'../assets/upload/document/'. $fileName;	
				 //if($this->upload->do_upload('flBiometricSheet')){ 
				 if (move_uploaded_file($_FILES['flBiometricSheet']['tmp_name'], $target_path)) {
					$inputFileName = $target_path;
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray();
					$attendanceDate = "";
					
					$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
						<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
					</div>';
					$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
					<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
					<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
						&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
					</div>';
					$site_base_url= base_url();
				
					$subject = 'Low Working Hour';      
					//echo $message;
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
					 $headers .= "Importance: High\n";
					 $headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
					 $headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
					 $headers .= 'X-Mailer: PHP/' . phpversion();
					//echo '<pre>';print_r($sheetData); echo "</pre>";
					for($i=1;$i<sizeof($sheetData);$i++)
					{
						
						$inOutDataOfEmp = $sheetData[$i];
						
						if(sizeof($inOutDataOfEmp) == 7)
						{
							if($inOutDataOfEmp[1] != "")
							{
								$attendanceDate = date("Y-m-d", strtotime($inOutDataOfEmp[1]));
								$chkDeclLeaveSQL = "SELECT `ix_declared_leave` FROM `declared_leave` WHERE `dt_event_date` = '".$attendanceDate."' AND (branch='0' OR branch='".$this->session->userdata('branch')."') LIMIT 1";
								$chkDeclLeaveRES = $this->db->query($chkDeclLeaveSQL);
								$chkDeclLeaveINFO = $chkDeclLeaveRES->result_array();
								$chkDeclLeaveNUM = COUNT($chkDeclLeaveINFO);
							}
							if($attendanceDate != "" && $chkDeclLeaveNUM == 0)
							{
								//Get Emp ID
								$empIDSQL = "SELECT login_id,shift,department,user_role FROM internal_user WHERE loginhandle = '".$inOutDataOfEmp[2]."' AND user_status = '1' LIMIT 1";
								$empIDRES = $this->db->query($empIDSQL);
								$empIDInfo = $empIDRES->result_array(); 
								$empIDNUM = COUNT($empIDInfo); 
								if($empIDNUM == 1)
								{
									//$empIDINFO = mysql_fetch_row($empIDRES);
									$checkEntrySQL = "SELECT attendance_id, att_status FROM attendance_new WHERE login_id = ".$empIDInfo[0]['login_id']."  AND date = '".$attendanceDate."' LIMIT 1";
									$checkEntryRES = $this->db->query($checkEntrySQL);
									$checkEntryINFO = $checkEntryRES->result_array();
									$checkEntryNUM = COUNT($checkEntryINFO);   // 0
									$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$empIDInfo[0]['login_id']."'";
									$repMgrRes = $this->db->query($repMgrSql);
									$repMgrInfo = $repMgrRes->result_array();	
									
									$message=<<<EOD
										<!DOCTYPE HTML>
										<html xmlns="http://www.w3.org/1999/xhtml">
										<head>
											<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
										</head>
										<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
											<div style="width:900px; margin: 0 auto; background: #fff;">
											 <div style="width:650px; float: left; min-height: 190px;">
												 <div style="padding: 7px 7px 14px 10px;">
												 <p>Dear {$repMgrInfo[0]['rfull_name']},</p>
												 <p>Your team member {$repMgrInfo[0]['rfull_name']} is having LWH on dated {$attendanceDate}.</p>                                                          
												 <p> In case of any Query, Please contact to HR Department.</p>                                 
												 <p>{$footer}</p>
												 </div> 
											  </div> 
											</div>  
											</div>
										</body>
										</html>
EOD;
									//Calculate Total Time Spent in Office
												 
									$sqlEmail = "select email,loginhandle,full_name from internal_user where login_id= '".$empIDInfo[0]['login_id']."'";
									//$resEmail = mysql_query($sqlEmail);
									$resEmail = $this->db->query($sqlEmail);
									//$rowEmail = mysql_fetch_row($resEmail); 
									$rowEmail = $resEmail->result_array();  //email
										
									$messageEmail=<<<EOD
									<!DOCTYPE HTML>
									<html xmlns="http://www.w3.org/1999/xhtml">
									<head>
										<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
									</head>
									<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
										<div style="width:900px; margin: 0 auto; background: #fff;">
										 <div style="width:650px; float: left; min-height: 190px;">
											 <div style="padding: 7px 7px 14px 10px;">
											 <p>Dear {$rowEmail[0]['full_name']},</p>                                 
											 <p>You had not punched the outtime against your task yesterday. </p>                                 
											 <p> In case of any Query, Please contact to HR Department.</p>                                 
											 <p>{$footer}</p>
											 </div> 
										  </div> 
										</div>  
										</div>
									</body>
									</html>
EOD;
									 $subjectEmail = 'Regarding Time Discrepancy in Production Report'; 
									 $toEmail=$rowEmail[0]['full_name'].'<'.$rowEmail[0]['email'].'>';
				 
									//print_r($empIDInfo); exit();
									$cond =""; $OutTime = $totTime = $lwh=0;
									if(($empIDInfo[0]['department']==6 || $empIDInfo[0]['department']==7))
									{
																		   
										if($empIDInfo[0]['shift']=='MS' || $empIDInfo[0]['shift']=='ES' || $empIDInfo[0]['shift']=='NS')
										{
											$totTime='28800';
										} 	
										else
										{
											$totTime='34200';
										} 
										if($inOutDataOfEmp[6]=='-')
										{
											//$OutTime = $inOutDataOfEmp[5]+$totTime;
											//mail($toEmail, $subjectEmail, $messageEmail, $headers);
										}
										else
										{
											$OutTime = $inOutDataOfEmp[6];                                
											$totalTime = $this->gtd($OutTime , $inOutDataOfEmp[5]);
										}
										
										if(($totalTime['0'] < $totTime) || $inOutDataOfEmp[6]=='-')
										{ 
											if($empIDInfo[0]['user_role']==5 || $empIDInfo[0]['user_role']==4 || $empIDInfo[0]['user_role']==3) {
												$cond=", att_status='W', shift='".$empIDInfo[0]['shift']."'";
											   $lwh=1;
											}
										}
									}
									
									if($inOutDataOfEmp[6] != '-')
									{
										$chkProductionSQL = "SELECT * FROM `task_subtask_time_log` WHERE DATE_FORMAT(start_date,'%Y-%m-%d') = '".$attendanceDate."' AND user_id=".$empIDInfo[0]['login_id']." AND end_date='0000-00-00 00:00:00' LIMIT 1";
										$chkProductionRES = $this->db->query($chkProductionSQL);
										$chkProductionROW = $chkProductionRES->result_array();
										$chkProductionNUM = count($chkProductionROW);
										if($chkProductionNUM == 1)
										{
											$end_date=$attendanceDate.' '.$OutTime;
											$updateQryRTimeLogres = $this->db->query("UPDATE `task_subtask_time_log` SET end_date='".$end_date."', spent_time='".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' AND user_id=".$empIDInfo[0]['login_id']."");
											$updateQryAssignRes = $this->db->query("UPDATE `tast_subtask_assignment` SET `actual_time` = `actual_time` + '".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' LIMIT 1");

										}
									}
									
									
									if($checkEntryNUM == 1)
									{
										if($checkEntryINFO[0]['att_status']=='L' || $checkEntryINFO[0]['att_status']=='R' || $checkEntryINFO[0]['att_status']=='H')
										{
											$attndSQL = "UPDATE attendance_new SET in_time = '".$inOutDataOfEmp[5]."', out_time = '".$inOutDataOfEmp[6]."' WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
										}
										else
										{
											$attndSQL = "UPDATE attendance_new SET in_time = '".$inOutDataOfEmp[5]."', out_time = '".$inOutDataOfEmp[6]."'".$cond." WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
										}
									}
									else
									{
										if($lwh)
										{
											//if(mail($repMgrInfo[0]['email'], $subject, $message, $headers)){
												
											//}
											//else{
												
											//}
											//echo "Reached In Email";
										}
										$attndSQL = "INSERT INTO attendance_new SET login_id='".$empIDInfo[0]['login_id']."', date='".$attendanceDate."', in_time='".$inOutDataOfEmp[5]."', out_time='".$inOutDataOfEmp[6]."'".$cond;
									}
									$attndRes = $this->db->query($attndSQL);
									$successMsg = "File is Uploaded Successfully ";
								}
							}
						}
						else
						{
							$errMsg = "Sorry!!! We are unable to process as file has been manually modified!";
						}
					}
					
				}
				else
				{
					//echo $this->upload->display_errors();
					$errMsg = "We are unable to upload the sheet! Please try later";
				}
			}
			else
			{
				$errMsg = "Please upload a valid file!";
			}
		}
		$this->mViewData['success_msg'] = $successMsg;
		$this->mViewData['error_msg'] = $errMsg;
		$this->render('hr/attendance_entry/biometric_data_upload_view', 'full_width',$this->mViewData);
	} */
	
	public function lwh_report()
	{
		$this->mViewData['pageTitle']    = 'LWD Report';
		$this->mViewData['errMsg'] = '';
		if($this->input->post('LHWReport') == "Download")
		{
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
			if($month !="" && $year !=""){
				// Create new PHPExcel object
				$this->load->library('PHPExcel');
				// Create new PHPExcel object
				$objPHPExcel = new PHPExcel();

				// Set document properties
				$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
										->setLastModifiedBy("Manoj Mahapatra")
										->setTitle("Online HR Master")
										->setSubject("Online HR Master")
										->setDescription("Online HR Master.")
										->setKeywords("Online HR Master")
										->setCategory("Online HR Master Export");

				//setting the values of the headers and data of the excel file 
				$header = array();
				$header = array("Sl No");
				$noOfColumnsSelected = 0;
				$selCols = "";
				$cond = "";
				
				array_push($header, "Emp Code");     
				$noOfColumnsSelected++;

				array_push($header, "Name");    
				$noOfColumnsSelected++;

				array_push($header, "DOJ");      
				$noOfColumnsSelected++;

				array_push($header, "Total Working Days");      
				$noOfColumnsSelected++;

				array_push($header, "Total LWH Days");     
				$noOfColumnsSelected++;

				array_push($header, "Total Regularise Days");     
				$noOfColumnsSelected++;

				array_push($header, "Total Leave Days");     
				$noOfColumnsSelected++;

				array_push($header, "Total Balance LWHDays");   
				$noOfColumnsSelected++;    


				foreach($header AS $i => $head)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
				}

				$encypt = $this->config->item('masterKey');
				$empDetailsQry = "SELECT login_id, loginhandle,full_name,join_date  FROM `internal_user` WHERE user_status = '1' AND login_id != '10010' AND (department='6' OR department='7') AND user_role ='5' ORDER BY login_id DESC";
				//echo $empDetailsQry; exit();
				$empDetailsRes = $this->db->query($empDetailsQry);
				$empDetailsNum = count($empDetailsRes->result_array());
				$empDetailsInfo_res = $empDetailsRes->result_array();
				//var_dump($empDetailsInfo_res);exit;
				//Employee details array
				$empSummaryArray = array();
				if($empDetailsNum >0)
				{
					$i = $ai =$totalWages =$totalEpf =$totalEps =$totalExepf  =0;
					foreach($empDetailsInfo_res as $empDetailsInfo)
					{ 
						$i++;
						$processEmpSummaryArray = true;

						$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));

						$totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

						$fromDate = $year.'-'.$month.'-01';
						//$toDate = $year.'-'.$month.'-'.$totalDay;
						
						$toDate = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));

						$attLWHQry = "select count(login_id) as counts from attendance_new where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '".$fromDate."' AND '".$toDate."' AND att_status='W' AND login_id='".$empDetailsInfo['login_id']."'";  
						$attLWHRes = $this->db->query($attLWHQry);
						$attLWHRow = $attLWHRes->result_array();
						$attLWH = 0;
						if(count($attLWHRow)>0){
							$attLWH = $attLWHRow[0]['counts'];
						}
						//var_dump($attLWHRow);
						$attRegQry = "select count(login_id) as counts from attendance_new where DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '".$fromDate."' AND '".$toDate."' AND att_status='R' AND out_time!='00:00:00' AND login_id='".$empDetailsInfo['login_id']."'";                   
						$attRegRes = $this->db->query($attRegQry);
						$attRegRow = $attRegRes->result_array();
						$attReg = 0;
						if(count($attRegRow)>0){
							$attReg = $attRegRow[0]['counts'];
						}
						$lwd_days = 0;
						$lwd_days = $attLWH+$attReg; 
						if($processEmpSummaryArray && $lwd_days > 0)
						{ 
							//Employee summary array
							$empSummary = array();
							array_push($empSummary,$i); 
							array_push($empSummary, $empDetailsInfo['loginhandle']);                            
							array_push($empSummary, $empDetailsInfo['full_name']);                 
							array_push($empSummary, date('d-m-Y',strtotime($empDetailsInfo['join_date'])));                 
							array_push($empSummary, $totalDay);                         
							array_push($empSummary, $lwd_days); 
							array_push($empSummary, $attReg); 
							array_push($empSummary, 0); 
							array_push($empSummary, $attLWH); 
							$empSummaryArray[$ai++] = $empSummary;
						}

					}
				}

				//print_r($empSummaryArray);exit;
				$totalKS = count($empSummaryArray);
				$row = 2;
				for($i=0; $i< $totalKS; $i++){
					foreach($empSummaryArray[$i] AS $col => $empInfo){
							$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
					}
					$row++;
				}
				
				$filename = "less_working_hours_report_".date('YmdHis').".xls";
				// Rename worksheet
				$objPHPExcel->getDefaultStyle()->applyFromArray(array(
						'borders' => array(
							'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
						)
					));

				$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
				$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

				$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->setTitle('LWH Report ' .date('Y'));


				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);


				// Redirect output to a client’s web browser (Excel5)
				header('ntent-Type: application/vnd.ms-excel');
				header("Content-Disposition: attachment; filename=\"$filename\""); 
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
			}
			else{
				$this->mViewData['errMsg'] = 'Please select month & year';
			}
		}
		$this->render('hr/attendance_entry/lwh_report_view', 'full_width',$this->mViewData);
	} 
	
	public function emp_attendance_summary()
	{
		$this->mViewData['monthArray'] = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
		
		$this->mViewData['pageTitle']    = 'Employee Attendance Summary';	
		$this->render('hr/attendance_entry/emp_attendance_summary_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/attendance_entry/emp_attendance_summary');
	}
	
	public function get_emp_attendance_summary(){
		$mm = date("m");
		$yy = date("Y");
		$result = $this->Hr_model->get_attendance($yy,$mm);
		echo json_encode($result); 
	}
	
	public function get_attendance_search(){
		$mm = $this->input->post('dd_month');
		$yy = $this->input->post('dd_year');
		$emp_code = $this->input->post('emp_code');
		$result = $this->Hr_model->get_attendance_search($yy,$mm,$emp_code);
		echo json_encode($result); 
	}
	
	public function emp_attendance_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$empSummaryArray = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Employee Code");
		$noOfColumnsSelected++;
		
		array_push($header,"Date of Joining");
		$noOfColumnsSelected++; 
		
		array_push($header, "Attend. Days");
		$noOfColumnsSelected++;
		
		array_push($header, "Reg. Days");
		$noOfColumnsSelected++;   

		array_push($header, "PL");
		$noOfColumnsSelected++;

		array_push($header, "SL");
		$noOfColumnsSelected++;

		array_push($header, "Absent Days");
		$noOfColumnsSelected++;

		array_push($header, "Pay Days");
		$noOfColumnsSelected++;
		
		array_push($header, "Av. PL");
		$noOfColumnsSelected++;
		
		array_push($header, "Av. SL");
		$noOfColumnsSelected++;
		
		array_push($header, "Cur. PL");
		$noOfColumnsSelected++;
		
		array_push($header, "Cur. SL");
		$noOfColumnsSelected++;
		
		array_push($header, "Attendance Pay");
		$noOfColumnsSelected++;

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		$dd_year = $this->input->get('searchYear');
		$dd_month = $this->input->get('searchMonth');
	
		if($dd_month == ''){
			$dd_month = date('m');
		} 
		
		if($dd_year == ''){
			$dd_year = date('Y');
		} 
		
		$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
		$enddateintime = strtotime('+' . (date('t',$startdateintime) - 1). ' days',$startdateintime);
		$totalDays = intval((date('t',$startdateintime)),10);
		$startdate = date("Y-m-d", $startdateintime);
		$enddate = date("Y-m-d", $enddateintime);
		$YYmm = $dd_year.'-'.$dd_month;
		// Get Employees Attendance Details
		
		$empAttDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.isAttndAllowance, i.lwd_date, i.user_status , i.emp_type 
								FROM `internal_user` i WHERE i.user_status != '0' AND i.user_status !='2' AND i.sal_sheet_sl_no != '0' AND  i.emp_type= 'F'";
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
			$i = $ai  =0;
			foreach($empAttDetailsRes_arr as $empAttDetailsInfo)
			{
				$i++;
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
				
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empAttDetailsInfo['loginhandle']);
					array_push($empSummary, date("d-m-Y", strtotime($empAttDetailsInfo['join_date'])));
					array_push($empSummary, $attndDays);
					array_push($empSummary, $regDays);
					array_push($empSummary, $PLDays);
					array_push($empSummary, $SLDays);
					array_push($empSummary, $absentdays);
					array_push($empSummary, $payDays);
					array_push($empSummary, $avlPL);
					array_push($empSummary, $avlSL);
					array_push($empSummary, $curPL);
					array_push($empSummary, $curSL);
					array_push($empSummary, $attndPay);
					$empSummaryArray[$ai++] = $empSummary;
			}
		}
		
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "attendance_report".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	public function calculateHolidaysDaysInMonth($year = '', $month = '', $declaredHolidayArray, $joinDate = '', $lwDate = '')
	{

		//create a start and an end datetime value based on the input year 
		$startdate = strtotime($year . '-' . $month . '-01');
		$enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
		
		// calculate 1st and 3rd saturday and make as holiday....
		//$fistSat = date("Y-m-d",strtotime("First Saturday ".date('F',$startdate)." ".date('Y',$startdate))) ;
		//$thirdSat = date("Y-m-d",strtotime("Third Saturday ".date('F',$startdate)." ".date('Y',$startdate)));
		
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
		
		//loop through the dates, from the start date to the end date
		while ($currentdate <= $enddate){
			$YYmmdd = date("Y-m-d", $currentdate);
			if(array_search($YYmmdd, $declaredHolidayArray)){
				$noofHolidays++;
			}/*
			elseif (date('D',$currentdate) == 'Sun'){
						$noofHolidays++;
					}elseif ( ($YYmmdd  >= NEW_SATURDAY_LEAVE) && (date('D',$currentdate) == 'Sat')){
						if(date("Y-m", $currentdate) == "2013-06"){
							$dtanshu = date("Y-m-d", $currentdate);
							if($dtanshu == "2013-06-01" || $dtanshu == "2013-06-15"){
								$noofHolidays++;
								if($_SESSION['user_name'] == "Administrator"){
									//echo $dtanshu . "<br/><br/><br/>";
								}
							}
						}else if(($YYmmdd == $fistSat || $YYmmdd == $thirdSat)){
							$noofHolidays++;
						}
					}*/

		 $currentdate = strtotime('+1 day', $currentdate);
		} //end date walk loop
		
		//return the number of working days
		return $noofHolidays;
	}
	
	//End Attendance entry
	
	//Start Payroll managment
	
	public function allowance_deduction_list()
	{
		$this->mViewData['pageTitle']    = 'Allowance Deduction';
		$this->render('hr/allowance_deduction_list_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/allowance_deduction_list_script');
	}
	public function allowance_deduction()
	{
		$this->mViewData['pageTitle']    = 'Allowance Deduction'; 
		$loginID = $this->input->get('id');   
		$encypt = $this->config->item('masterKey');
		$month = $this->input->post('selMonth');
		$year = $this->input->post('selYear');
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{ 
			$resMessage = $this->db->query("SELECT * FROM `allowance_deduction` WHERE login_id='".$loginID."' AND lyear='$year' AND lmonth='$month'"); 
			$resAllEmp=$resMessage->result_array();
			$count = count($resAllEmp);
			if($count>0)
			{
				$updateSql = $this->db->query("UPDATE `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$this->input->post('txtperformance_bonus')."', '".$encypt."'),
								city_allowance = AES_ENCRYPT('".$this->input->post('txtcity_allowance')."', '".$encypt."'),arrear= AES_ENCRYPT('".$this->input->post('txtarrear')."', '".$encypt."'),food_allowance = AES_ENCRYPT('".$this->input->post('txtfood_allowance')."', '".$encypt."'),personal_allowance= AES_ENCRYPT('".$this->input->post('txtpersonal_allowance')."', '".$encypt."'),referal_bonus = AES_ENCRYPT('".$this->input->post('txtreferal_bonus')."', '".$encypt."'),leave_encashment= AES_ENCRYPT('".$this->input->post('txtleave_encashment')."', '".$encypt."'),relocation_allowance= AES_ENCRYPT('".$this->input->post('txtrelocation_allowance')."', '".$encypt."'),donation = AES_ENCRYPT('".$this->input->post('txtdonation')."', '".$encypt."'),recovery=AES_ENCRYPT('".$this->input->post('txtrecovery')."', '".$encypt."'),income_tax = AES_ENCRYPT('".$this->input->post('txtincometax')."', '".$encypt."'),incentive=AES_ENCRYPT('".$this->input->post('txtincentive')."', '".$encypt."'),other_deduction= AES_ENCRYPT('".$this->input->post('txtother_deduction')."', '".$encypt."')                    
						 WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'"); 
			}
			else
			{
			   $insertSql = $this->db->query("INSERT INTO `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$this->input->post('txtperformance_bonus')."', '".$encypt."'),
								city_allowance = AES_ENCRYPT('".$this->input->post('txtcity_allowance')."', '".$encypt."'),arrear= AES_ENCRYPT('".$this->input->post('txtarrear')."', '".$encypt."'),food_allowance = AES_ENCRYPT('".$this->input->post('txtfood_allowance')."', '".$encypt."'),personal_allowance= AES_ENCRYPT('".$this->input->post('txtpersonal_allowance')."', '".$encypt."'),referal_bonus = AES_ENCRYPT('".$this->input->post('txtreferal_bonus')."', '".$encypt."'),
								leave_encashment= AES_ENCRYPT('".$this->input->post('txtleave_encashment')."', '".$encypt."'),relocation_allowance= AES_ENCRYPT('".$this->input->post('txtrelocation_allowance')."', '".$encypt."'),donation = AES_ENCRYPT('".$this->input->post('txtdonation')."', '".$encypt."'),recovery=AES_ENCRYPT('".$this->input->post('txtrecovery')."', '".$encypt."'),income_tax = AES_ENCRYPT('".$this->input->post('txtincometax')."', '".$encypt."'),incentive=AES_ENCRYPT('".$this->input->post('txtincentive')."', '".$encypt."'),other_deduction= AES_ENCRYPT('".$this->input->post('txtother_deduction')."', '".$encypt."'),login_id='".$loginID."', lyear='".$year."', lmonth='".$month."'"); 
			}
			$successMsg = TRUE;  
		}
		if($this->input->post('btnFind') == "FIND")
		{  
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
		}
		$empSql = $this->db->query("SELECT i.full_name,i.user_status,i.FnF_status,
					   AES_DECRYPT(s.performance_incentive, '".$encypt."') AS performance_incentive,    
					   AES_DECRYPT(s.arrear, '".$encypt."') AS arrear,
					   AES_DECRYPT(s.food_allowance, '".$encypt."') AS food_allowance,
					   AES_DECRYPT(s.personal_allowance, '".$encypt."') AS personal_allowance,
					   AES_DECRYPT(s.relocation_allowance, '".$encypt."') AS relocation_allowance,
					   AES_DECRYPT(s.city_allowance, '".$encypt."') AS city_allowance,
					   AES_DECRYPT(s.referal_bonus, '".$encypt."') AS referal_bonus,
					   AES_DECRYPT(s.leave_encashment, '".$encypt."') AS leave_encashment,                       
					   AES_DECRYPT(s.donation, '".$encypt."') AS donation,                      
					   AES_DECRYPT(s.recovery, '".$encypt."') AS recovery,
					   AES_DECRYPT(s.other_deduction, '".$encypt."') AS other_deduction,
					   AES_DECRYPT(s.income_tax, '".$encypt."') AS income_tax,
					   AES_DECRYPT(s.incentive, '".$encypt."') AS incentive
		 FROM `internal_user` AS i LEFT JOIN `allowance_deduction` AS s ON s.login_id = i.login_id WHERE i.login_id = '$loginID' AND lyear='$year' AND lmonth='$month'");  
		$this->mViewData['empInfo'] = $empSql->row_array();
		//var_dump($this->mViewData['empInfo']); 
		$this->render('hr/allowance_deduction_view', 'full_width',$this->mViewData); 
	}  
	
	public function GetMonths($sStartDate, $sEndDate){
	   
		$date1 = strtotime($sStartDate);
		$date2 = strtotime($sEndDate);
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		 return $months;           
	}
	public function generate_arrears()
	{
		$this->mViewData['pageTitle']    = 'Generate Arrears';
		$this->mViewData['successMsg']    = '';
		if($this->input->post('btnAddPhone') == "GENERATE")
		{
			$encypt = $this->config->item('masterKey');
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
			
			$totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			
			$fromDate = $year.'-'.$month.'-01';
			$toDate = $year.'-'.$month.'-'.$totalDay;
			//echo $fromDate.' '.$toDate; exit;
			$resAllEmp = $this->db->query("SELECT * FROM `internal_user` WHERE login_id != '10010' AND (user_status ='1' OR (DATE(`lwd_date`) BETWEEN '$fromDate' AND '$toDate'))");
			$rowAllEmp=$resAllEmp->result_array();
			//echo count($rowAllEmp);
			//echo '<pre>';print_r($rowAllEmp);echo '</pre>';exit;
			$p=1;
			for($k=0; $k<count($rowAllEmp); $k++)
			{
				//echo mysql_num_rows($resAllEmp);exit;
				$paidDays=$absentDay=$WorkingDay=$WorkingDayH=$WorkingDayP=$WorkingDayHH=$numWeeklyOff=$numDeclaredHoliday=$numSalarySheet=0;

				$sqlSalaryInfo= "Select AES_DECRYPT(fixed_basic, '".$encypt."') AS fixed_basic,
				AES_DECRYPT(gross_salary, '".$encypt."') AS gross_salary,
				AES_DECRYPT(basic, '".$encypt."') AS basic,
				AES_DECRYPT(hra, '".$encypt."') AS hra, 
				AES_DECRYPT(reimbursement, '".$encypt."') AS reimbursement,
				AES_DECRYPT(conveyance_allowance, '".$encypt."') AS conveyance_allowance,                      
				calculation_type, pf_no, mediclaim_no, bank, bank_no, payment_mode
				From `salary_info` WHERE login_id='".$rowAllEmp[$k]['login_id']."'";
					
				//echo $sqlSalaryInfo;exit;
				$resSalaryInfo = $this->db->query($sqlSalaryInfo);
				$rowSalaryInfo=  $resSalaryInfo->result_array();
				//print_r($rowSalaryInfo);exit;


				$WorkingDayResP = $this->db->query("Select * From `attendance_new` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND date BETWEEN '$fromDate' AND '$toDate' AND att_status!='W'" );
				$WorkingDayP = count($WorkingDayResP->result_array());

				$WorkingDaySqlH="Select * From `attendance_new` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND date BETWEEN '$fromDate' AND '$toDate' AND att_status='H' AND `out_time` = '00:00:00' AND `in_time` = '00:00:00'";
				$WorkingDayResH = $this->db->query($WorkingDaySqlH);
				$WorkingDayHH = count($WorkingDayResH->result_array());      
					  
				$WorkingDayH=($WorkingDayHH/2);
				$WorkingDay=$WorkingDayP-$WorkingDayH;
			   
				//if($WorkingDay){    

				if($rowAllEmp[$k]['lwd_date']=='0000-00-00' || $rowAllEmp[$k]['lwd_date']=='1970-01-01'){
					$pEndDate=$toDate;
				}
				else{
					$pEndDate=$rowAllEmp[$k]['lwd_date'];
				}

				$numWeeklyOffRes = $this->db->query("Select * From `declared_leave` WHERE leave_type='G' AND dt_event_date BETWEEN '$fromDate' AND '$pEndDate' AND dt_event_date > '".$rowAllEmp[$k]['join_date']."'" );
				$numWeeklyOff = count($numWeeklyOffRes->result_array());

				$numDeclaredHolidayRes = $this->db->query("Select * From declared_leave WHERE (branch = '0' OR branch = '".$rowAllEmp[$k]['branch']."') AND leave_type = 'D' AND IsDecalredDayInGeneral = 'N' AND dt_event_date BETWEEN '$fromDate' AND '$pEndDate' AND dt_event_date > '".$rowAllEmp[$k]['join_date']."'" );
				$numDeclaredHoliday = count($numDeclaredHolidayRes->result_array());

				if($WorkingDay>=1){
					//$paidDays=$WorkingDay+$numWeeklyOff;
					$paidDays=$WorkingDay+$numWeeklyOff+$numDeclaredHoliday;  
				}		

				$absentDay = $totalDay-$paidDays;
				
				$fixed_gross = 0;
				if(count($rowSalaryInfo)>0){
					$fixed_gross = $rowSalaryInfo[0]['gross_salary']; 
					//$earned_gross_current = ($fixed_gross/$totalDay)* $paidDays;
				} 
				
				$month_prev = (int)$month - 1;
				$totalDay_prev = cal_days_in_month(CAL_GREGORIAN, $month_prev, $year);
				$resSalarySheet = $this->db->query("Select salary_sheet.*,AES_DECRYPT(gross, '".$encypt."') AS gross_salary From `salary_sheet` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND salary_year='$year' AND salary_month='$month_prev'");
				$rowSalarySheet=  $resSalarySheet->result_array();           
				$earned_gross_previous = 0;
				$earned_gross_current = 0;
				$current_arrears = 0;
				if(count($rowSalarySheet)>0){
					//if($rowAllEmp[$k]['login_id'] == '11018'){
						$fixed_gross_prev = $rowSalarySheet[0]['gross_salary']; 
						$paidDays_prev = $rowSalarySheet[0]['paid_days']; 
						$earned_gross_previous = ($fixed_gross_prev/$totalDay_prev)* $paidDays_prev;
						$earned_gross_current = ($fixed_gross/$totalDay_prev)* $paidDays_prev;
						$current_arrears = $earned_gross_current - $earned_gross_previous;
						$current_arrears = round($current_arrears);
						/* echo 'Previous Gross='.$fixed_gross_prev.'<br/>';
						echo 'Previous PaidDays='.$paidDays_prev.'<br/>';
						echo 'Previous Earned Gross='.$earned_gross_previous.'<br/>';
						echo 'Current Earned Gross='.$earned_gross_current.'<br/>';
						echo 'Arrears='.$current_arrears.'<br/>'; exit; */
					//}
					
				}
				
				
				
				if($current_arrears > 0){
					$resMessage = $this->db->query("SELECT * FROM `allowance_deduction` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND lyear='$year' AND lmonth='$month'"); 
					$resAllEmp=$resMessage->result_array();
					$count = count($resAllEmp);
					if($count>0)
					{
						$updateSql = $this->db->query("UPDATE `allowance_deduction` SET 
							arrear= AES_ENCRYPT('".$current_arrears."', '".$encypt."')                  
							WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND lyear='".$year."' AND lmonth='".$month."'"); 
					}
					else
					{
					   $insertSql = $this->db->query("INSERT INTO `allowance_deduction` SET 
							performance_incentive=AES_ENCRYPT('0', '".$encypt."'),
							city_allowance = AES_ENCRYPT('0', '".$encypt."'),
							arrear= AES_ENCRYPT('".$current_arrears."', '".$encypt."'),
							food_allowance = AES_ENCRYPT('0', '".$encypt."'),
							personal_allowance= AES_ENCRYPT('0', '".$encypt."'),
							referal_bonus = AES_ENCRYPT('0', '".$encypt."'),
							leave_encashment= AES_ENCRYPT('0', '".$encypt."'),
							relocation_allowance= AES_ENCRYPT('0', '".$encypt."'),
							donation = AES_ENCRYPT('0', '".$encypt."'),
							recovery=AES_ENCRYPT('0', '".$encypt."'),
							income_tax = AES_ENCRYPT('0', '".$encypt."'),
							incentive=AES_ENCRYPT('0', '".$encypt."'),
							other_deduction= AES_ENCRYPT('0', '".$encypt."'),
							login_id='".$rowAllEmp[$k]['login_id']."', lyear='".$year."', lmonth='".$month."'"); 
					}
				}
			}
			$successMsg = TRUE;
			$this->mViewData['successMsg']    = 'Arrears Generated Successfully';
		}
		$this->render('hr/generate_arrears_view', 'full_width',$this->mViewData);
	}
	public function generate_salary()
	{
	
		
		$this->mViewData['pageTitle']    = 'Generate Salary';
		$this->mViewData['successMsg']    = '';
		if($this->input->post('btnAddPhone') == "GENERATE")
		{
			$encypt = $this->config->item('masterKey');
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
			
			$totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			
			$fromDate = $year.'-'.$month.'-01';
			$toDate = $year.'-'.$month.'-'.$totalDay;
			
			$resAllEmp = $this->db->query("SELECT * FROM `internal_user` WHERE login_id != '10010' AND (user_status ='1' OR (DATE(`lwd_date`) BETWEEN '$fromDate' AND '$toDate'))");
			$rowAllEmp=$resAllEmp->result_array();
			
			$p=1;
			
			//------------------------ General Leave Count Start--------------------//
						//$month  = date('m');
						//$year  = date('Y');
						$days = cal_days_in_month(CAL_GREGORIAN, $month,$year);
						$sunday_count=0;						
						
						for($i = 1; $i<= $days; $i++){
						   //$day  = date('Y-m-'.$i);
						   $date_of_month= $year."-".$month."-".$i;
						   $day  = date('Y-m-d',strtotime($date_of_month));
						   $result = date("l", strtotime($day));
						   if($result == "Sunday"){
						   //echo date("Y-m-d", strtotime($day)). " ".$result."<br>";
						   $sunday_count++;
						   }
						}
						
						$saturday_as_holidya_count=2;
						
						$tot_genral_holidays=$sunday_count+$saturday_as_holidya_count;
					
					//------------------------ General Leave Count End---------------------//					
					
			
			for($k=0; $k<count($rowAllEmp); $k++)
			{
				$paidDays=$absentDay=$WorkingDay=$WorkingDayH=$WorkingDayP=$WorkingDayHH=$numWeeklyOff=$numDeclaredHoliday=$numSalarySheet=0;

				$sqlSalaryInfo= "Select AES_DECRYPT(fixed_basic, '".$encypt."') AS fixed_basic,
				AES_DECRYPT(gross_salary, '".$encypt."') AS gross_salary,
				AES_DECRYPT(basic, '".$encypt."') AS basic,
				AES_DECRYPT(hra, '".$encypt."') AS hra, 
				AES_DECRYPT(reimbursement, '".$encypt."') AS reimbursement,
				AES_DECRYPT(conveyance_allowance, '".$encypt."') AS conveyance_allowance,                      
				calculation_type, pf_no, mediclaim_no, bank, bank_no, payment_mode
				From `salary_info` WHERE login_id='".$rowAllEmp[$k]['login_id']."'";
					
				
				$resSalaryInfo = $this->db->query($sqlSalaryInfo);
				$rowSalaryInfo=  $resSalaryInfo->result_array();
				

				$sqlAllowInfo= "Select AES_DECRYPT(performance_incentive, '".$encypt."') AS performance_incentive,    
					   AES_DECRYPT(arrear, '".$encypt."') AS arrear,
					   AES_DECRYPT(food_allowance, '".$encypt."') AS food_allowance,
					   AES_DECRYPT(personal_allowance, '".$encypt."') AS personal_allowance,
					   AES_DECRYPT(relocation_allowance, '".$encypt."') AS relocation_allowance,
					   AES_DECRYPT(city_allowance, '".$encypt."') AS city_allowance,
					   AES_DECRYPT(referal_bonus, '".$encypt."') AS referal_bonus,
					   AES_DECRYPT(leave_encashment, '".$encypt."') AS leave_encashment,                       
					   AES_DECRYPT(donation, '".$encypt."') AS donation,                      
					   AES_DECRYPT(recovery, '".$encypt."') AS recovery,
					   AES_DECRYPT(other_deduction, '".$encypt."') AS other_deduction,
					   AES_DECRYPT(income_tax, '".$encypt."') AS income_tax,
					   AES_DECRYPT(incentive, '".$encypt."') AS incentive
					   From `allowance_deduction` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND lyear='$year' AND lmonth='$month'";

					//echo $sqlAllowInfo;exit;             
					$resAllowInfo = $this->db->query($sqlAllowInfo);
					$rowAllowInfo=  $resAllowInfo->result_array();
					//print_r($rowAllowInfo);exit;

					$WorkingDayResP = $this->db->query("Select * From `attendance_new` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND date BETWEEN '$fromDate' AND '$toDate' AND att_status!='W' AND attend_as_per_work_duration_calc='P' " );
					$WorkingDayP = count($WorkingDayResP->result_array());

					$WorkingDaySqlH="Select * From `attendance_new` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND date BETWEEN '$fromDate' AND '$toDate' AND att_status='H' AND `out_time` = '00:00:00' AND `in_time` = '00:00:00'";
					$WorkingDayResH = $this->db->query($WorkingDaySqlH);
					$WorkingDayHH = count($WorkingDayResH->result_array());      
						  
					$WorkingDayH=($WorkingDayHH/2);
					$WorkingDay=$WorkingDayP-$WorkingDayH;
				   
					//if($WorkingDay){    

					if($rowAllEmp[$k]['lwd_date']=='0000-00-00' || $rowAllEmp[$k]['lwd_date']=='1970-01-01'){
						$pEndDate=$toDate;
					}
					else{
						$pEndDate=$rowAllEmp[$k]['lwd_date'];
					}

					//$numWeeklyOffRes = $this->db->query("Select * From `declared_leave` WHERE leave_type='G' AND (dt_event_date BETWEEN '$fromDate' AND '$pEndDate') AND dt_event_date >= '".$rowAllEmp[$k]['join_date']."'" );
					//$numWeeklyOff = count($numWeeklyOffRes->result_array());
					
					$numWeeklyOff=$tot_genral_holidays;
						
					$numDeclaredHolidayRes = $this->db->query("Select * From declared_leave WHERE (branch = '0' OR branch = '".$rowAllEmp[$k]['branch']."') AND leave_type = 'D' AND IsDecalredDayInGeneral = 'N' AND (dt_event_date BETWEEN '$fromDate' AND '$pEndDate') AND dt_event_date >= '".$rowAllEmp[$k]['join_date']."'" );
					$numDeclaredHoliday = count($numDeclaredHolidayRes->result_array());

					
					if($WorkingDay>=1){
						//$paidDays=$WorkingDay+$numWeeklyOff;
						$paidDays=$WorkingDay+$numWeeklyOff+$numDeclaredHoliday;  
					}
					
					

					$absentDay = $totalDay-$paidDays;

					$basic=$hra=$ctc=$gross=$hra_percent=$basic_percent=0;
					$earned_basic= $earned_hra=0;
					$arrear_basic=$arrear_hra=$arrear_conveyance_allowance=$arrear_special_allowance=0;
					// For Gross
					$gross= 0; 
					if(count($rowSalaryInfo)>0){
						$gross= $rowSalaryInfo[0]['gross_salary'];  
						
						// For Basic
						if($rowSalaryInfo[0]['calculation_type']=='A')
						{ 
							//For Automatic Salary Calculation
							$basic_percent = $rowSalaryInfo[0]['basic'];
							$basic=round($gross*($basic_percent/100));   
						}
						else
						{
							$basic = $rowSalaryInfo[0]['basic'];
							if($gross > 0){
								$basic_percent = round(($basic/$gross)*100,1);
							}
							else{
								$basic_percent =0;
							}
							
						}
						// For HRA
						if($rowSalaryInfo[0]['calculation_type']=='A')
						{ 
							//For Automatic Salary Calculation
							$hra_percent = $rowSalaryInfo[0]['hra'];
							$hra=round($basic*($hra_percent/100));                      
						}
						else
						{
							$hra = $rowSalaryInfo[0]['hra'];
							if($gross > 0){
								$hra_percent = round(($hra/$gross)*100,1);
							}
							else{
								$hra_percent =0;
							}
							
						}
					}					
						
					// Arrear in Days              
					//$arrear_days=$rowAllowInfo['arrear'];
					//$arrear=round(($gross/$totalDay)*$arrear_days);

					// Arrear in Figure 
					$arrear = 0;
					
					// All Allowances 
					$arrear_days=$leave_encashment= $referal_bonus = $personal_allowance= $relocation_allowance=  $food_allowance= $city_allowance= 0;
					if(count($rowAllowInfo)>0){
						$arrear=$rowAllowInfo[0]['arrear'];  
						$city_allowance=round(($rowAllowInfo[0]['city_allowance']/$totalDay)*$paidDays);
						$food_allowance=round(($rowAllowInfo[0]['food_allowance']/$totalDay)*$paidDays);                                
						$relocation_allowance=round(($rowAllowInfo[0]['relocation_allowance']/$totalDay)*$paidDays);
						$personal_allowance=round(($rowAllowInfo[0]['personal_allowance']/$totalDay)*$paidDays);

						$leave_encashment=$rowAllowInfo[0]['leave_encashment'];    
						$referal_bonus=$rowAllowInfo[0]['referal_bonus'];
						$arrear_days=$rowAllowInfo[0]['arrear'];
					}
					
					$arrear_basic = round($arrear*($basic_percent/100));
					$earned_basic = round(($basic/$totalDay)*$paidDays)+$arrear_basic;

					
					$arrear_hra = round($arrear*($hra_percent/100));
					$earned_hra =round(($hra/$totalDay)*$paidDays)+$arrear_hra;

					$attendance_incentive=$performance_incentive=$conveyance_allowance_percent=$conveyance_allowance=$earned_conveyance_allowance=0;
					$medical_allowance=$earned_medical_allowance=$special_allowance=$earned_special_allowance=0; 
						
					// For Conveyance Allowance
					if(count($rowSalaryInfo)>0){
						if($rowSalaryInfo[0]['calculation_type']=='A')
						{
							//For Automatic Salary Calculation
							$conveyance_allowance_percent=$rowSalaryInfo[0]['conveyance_allowance'];
							$conveyance_allowance=round($basic*(@$conveyance_allowance_percent/100));
							  
						}
						else
						{
							$conveyance_allowance = $rowSalaryInfo[0]['conveyance_allowance'];
							if($gross > 0){
								@$conveyance_allowance_percent = round((@$conveyance_allowance/$gross)*100,1);
							}
							else{
								$conveyance_allowance_percent = 0;
							}
						}
					}
					$arrear_conveyance_allowance = round($arrear*($conveyance_allowance_percent/100));
					@$earned_conveyance_allowance =round((@$conveyance_allowance/$totalDay)*$paidDays)+$arrear_conveyance_allowance;

					// For Medical Allowance
					
					$employer_esi=$earned_employer_esi=$medical_allowance=$earned_esi=$esi=0;
					/*if($gross > 21000)
					{
						$medical_allowance = 1250;
						$earned_medical_allowance =round(($medical_allowance/$totalDay)*$paidDays);
						$esi= $earned_esi=$employer_esi=$earned_employer_esi=0;
					}
					else
					{
						// For ESI
						$esi= $earned_esi=$employer_esi=0;
						$esi =round(($gross)*(0.75/100));                        
						$earned_esi =round(((($gross/$totalDay)*$paidDays)+$arrear+$city_allowance+$food_allowance+$relocation_allowance+$personal_allowance)*(0.75/100));
						$employer_esi=round(($gross)*(3.25/100));
						//$earned_employer_esi =ceil(((($gross+$arrear+$city_allowance+$food_allowance+$relocation_allowance+$personal_allowance)*(4.75/100))/$totalDay)*$paidDays);
						$earned_employer_esi =round(((($gross/$totalDay)*$paidDays)+$arrear+$city_allowance+$food_allowance+$relocation_allowance+$personal_allowance)*(3.25/100));
						$medical_allowance =$earned_medical_allowance=0;
					}*/
						
					// For Special Allowance      
					$special_allowance_per=0;
					@$special_allowance=$gross-($basic+$hra+$conveyance_allowance+$medical_allowance);
					$special_allowance_per=100-($basic_percent+$hra_percent+$conveyance_allowance_percent);

					$arrear_special_allowance = round($arrear*($special_allowance_per/100));
						
					$earned_special_allowance =round(($special_allowance/$totalDay)*$paidDays)+$arrear_special_allowance;


					// For Performance Incentive 
					$performance_incentive=$performance_incentive_slab=$range_pi=0;
					/*if(@count(@$rowAllowInfo)>0){
						$performance_incentive_slab= $rowAllowInfo[0]['performance_incentive'];
					}
					*/

					$reSper_inc=$this->db->query("SELECT * from `performance_slab_master`");
					$roWper_inc=$reSper_inc->result_array();
					//print_r($roWper_inc);exit;
					for($a=0; $a<count($roWper_inc); $a++)
					{
						$range_pi=explode('-',$roWper_inc[$a]['range']);
						if($performance_incentive_slab >= $range_pi[0] && $performance_incentive_slab <= $range_pi[1]){
							$performance_incentive=$roWper_inc[$a]['pi_value'];
						}
					}
					//echo  $performance_incentive;exit;
						
					// For Attendance Incentive
					if($paidDays==$totalDay)  {
						$attendance_incentive=500;
					}
					
					// For PF
					$earned_employer_pf=$employer_pf=$epf=$earned_pf=$pf=0;
					
					/*
					if(count($rowSalaryInfo)>0){
						if($rowSalaryInfo[0]['fixed_basic']>0)
						{
							$pf=round($rowSalaryInfo[0]['fixed_basic']*(12/100));
							$earned_pf =round(($pf/$totalDay)*$paidDays);
							$employer_pf=round($rowSalaryInfo[0]['fixed_basic']*(12/100));
							$earned_employer_pf =round(($employer_pf/$totalDay)*$paidDays);
						}
						else
						{
							$pf=round(($basic)*(12/100));
							$earned_pf =round($earned_basic*(12/100));
							$employer_pf=round(($basic)*(12/100));
							$earned_employer_pf =round($earned_basic*(12/100));
						}
					}	
					*/
						
					// For Advance & Loan
					$resLoanInfo = $this->db->query("Select AES_DECRYPT(advance, '".$encypt."') AS advance,AES_DECRYPT(loan, '".$encypt."') AS loan From `loan_advance` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND lyear='$year' AND lmonth='$month'");
					$rowLoanInfo=  $resLoanInfo->result_array();
					//print_r($rowLoanInfo);exit;
					
					// For Advance
					$advance=0;
					if(count($rowLoanInfo)>0){
						$advance=$rowLoanInfo[0]['advance'];   
					}					
					// For Loan
					$loan=0;
					if(count($rowLoanInfo)>0){
						$loan=$rowLoanInfo[0]['loan'];
					}
					
					//Recovery
					$recovery=0;
					if(count($rowAllowInfo)>0){
						$recovery=$rowAllowInfo[0]['recovery'];
					}
					
					// Other Deduction
					$other_deduction=0;
					if(count($rowAllowInfo)>0){
						$other_deduction=$rowAllowInfo[0]['other_deduction'];
					}
					
					// For Donation 
					$donation =$reimbursement =0;
					if(count($rowAllowInfo)>0){
						$donation=$rowAllowInfo[0]['donation'];  
					}					
					
					$ctc = $gross+$employer_pf+$employer_esi;
					if(count($rowSalaryInfo)>0){
						$reimbursement =$rowSalaryInfo[0]['reimbursement'];
					}
					$earned_ctc=$earned_gross=$total_deduction=$net_salary=$financialyear=0;
						
					if($month >= 4)
					{
						$financialyear = $year.'-'.($year+1);
					}
					else
					{
						$financialyear = ($year-1).'-'.$year;
					}
					$resTaxableInfo = $this->db->query("Select * From `income_tax` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND fyear='".$financialyear."'");
					
					$rowTaxableInfo=  $resTaxableInfo->result_array();  

					$childreneducationalallowance=0;
					if(count($rowTaxableInfo)>0){
						$childreneducationalallowance= round(($rowTaxableInfo[0]['childreneducationalallowance']/$totalDay)*$paidDays);
					}
					if($childreneducationalallowance > 0 )
					{
						$special_allowance=$special_allowance-$rowTaxableInfo[0]['childreneducationalallowance'];
						$earned_special_allowance = $earned_special_allowance -$childreneducationalallowance;
					}
						
					$earned_gross=$earned_basic+$earned_hra+$earned_conveyance_allowance+$earned_medical_allowance+$earned_special_allowance+$city_allowance+$food_allowance+$referal_bonus +$relocation_allowance+$personal_allowance+$childreneducationalallowance;
						 
					$earned_ctc = $earned_gross+$earned_employer_pf+$earned_employer_esi;

					// Professional Tax
					$pt_slab=$pt=$range_pt=$excesPT=0;
					$pt_slab= $earned_gross-$referal_bonus;
					//exit;
					$reSpt=$this->db->query("SELECT * from `pt_slab_master`");
					$roWpt=$reSpt->result_array();
					/*
					for($b=0; $b<count($roWpt); $b++)
					{                    
						$range_pt=explode('-',$roWpt[$b]['range']);
						if($pt_slab >= $range_pt[0] && $pt_slab <= $range_pt[1])
						$pt=$roWpt[$b]['pt_value'];
					}
					
					if($month=='03')
					{
						if($pt=='200')
						$pt='300';
						$excesPT=0;
					}
					else
					{
						$excesPT=100;
					}
					*/
					 // For Income Tax or TDS
					$totEarnedGross= $totPT= $actualEarnedHRA = $totEarnedChildEduAllow =$totEarnedBasic= $income_tax= $taxableIncome= $totEarnedPF= $taxableSalary=0;
					$rentpaidExcess10perofsalary= $basic50per= $minValueHRA= $ActualDonation=0;               
						 
					
					$sfyear= $efyear= $nummonth= $bpercent= $month_diff=0;
						 
						if($rowAllEmp[$k]['branch']=='2'){
							$bpercent=50;  
						}							
						else{
							$bpercent=40;
						}
						
						if($month >= 4)
						{
							$efyear=$sfyear=$year;
							$nummonth=12-$month+3;
							$sEndDate=($year+1)."-03-31";
							$cond = " AND ((salary_month > 3 AND salary_year=$sfyear) AND (salary_month < $month AND salary_year=$efyear))";
						}
						else
						{
							$efyear=$year;  
							$sfyear=($year-1);
							$nummonth=3-$month;
							$sEndDate=($year)."-03-31";
							$cond = " AND ((salary_month > 3 AND salary_year=$sfyear) OR (salary_month < $month AND salary_year=$efyear))";
						}                               
					   //echo $nummonth; exit;
						$month_diff = $this->GetMonths($rowAllEmp[$k]['join_date'],$sEndDate);  
						$extract_join_date= explode('-',$rowAllEmp[$k]['join_date']);
						if($extract_join_date[1]==4){
							$month_diff=$month_diff+1;
						}
						if($month_diff > 12){
							$month_diff =12;
						}
						//echo $month_diff;exit;
						//$nummonth=11;
						//$month_diff =12;
						$itSql="SELECT SUM(AES_DECRYPT(earned_basic, '".$encypt."')) AS totEarnedBasic, SUM(AES_DECRYPT(earned_hra, '".$encypt."')) AS totEarnedHRA, SUM(AES_DECRYPT(earned_conveyance_allowance, '".$encypt."')) AS totEarnedConveyance, SUM(AES_DECRYPT(earned_medical_allowance, '".$encypt."')) AS totEarnedMedical, SUM(AES_DECRYPT(earned_special_allowance, '".$encypt."')) AS totEarnedSpecial, SUM(AES_DECRYPT(pt, '".$encypt."')) AS totPT, SUM(AES_DECRYPT(earned_pf, '".$encypt."')) AS totEarnedPF, SUM(AES_DECRYPT(income_tax, '".$encypt."')) AS totIncomeTax, SUM(AES_DECRYPT(child_edu_allowance, '".$encypt."')) AS totChildEduAllow, SUM(AES_DECRYPT(otherincome, '".$encypt."')) AS totOtherincome FROM salary_sheet WHERE login_id = '".$rowAllEmp[$k]['login_id']."'" . $cond ;                
						$itRES = $this->db->query($itSql);             
						$itINFO = $itRES->result_array();
						//print_r($itINFO);exit;
						//if($rowAllEmp[$k]['login_id']==10024){echo $itSql; exit;}
						
						$totEarnedBasic =$itINFO[0]['totEarnedBasic']+$earned_basic+($basic*$nummonth);            
						//if($rowAllEmp[$k]['login_id']==10024){echo $itINFO[0];exit;}
						// Actual HRA Received
						$actualEarnedHRA = $itINFO[0]['totEarnedHRA']+$earned_hra+($hra*$nummonth);  
						if(count($rowTaxableInfo)>0){						
							$rentpaidExcess10perofsalary= $rowTaxableInfo[0]['rentpaid']-($totEarnedBasic*(10/100)); 
						}						
						
						$basic50per=$totEarnedBasic*($bpercent/100);
						
						$minValueHRA = min($actualEarnedHRA,$rentpaidExcess10perofsalary,$basic50per);
						if($minValueHRA<0){
							$minValueHRA =0;
						}
						
						$totEarnedConveyance=$totEarnedMedical= $totEarnedSpecial= $exempt=$totOtherincome=$otherincome=0;
						
						$deduction80C=$deduction80Dselfsfamily=$deduction80Dparents=$deduction80Ehousingloaninterest=$deduction80DDnormaldisability=$deduction80DDseveredisability=0;
						$eligible80C=$eligible80Dselfsfamily=$eligible80Dparents=$eligible80Ehousingloaninterest=$eligible80DDnormaldisability=$eligible80DDseveredisability=0;
						
						$deduction80Unormaldisability=$deduction80Useveredisability=$deduction80DDBspecifieddiseases=$deduction80TTAsavingsaccountinterest=0;
						$eligible80Unormaldisability=$eligible80Useveredisability=$eligible80DDBspecifieddiseases=$eligible80TTAsavingsaccountinterest=0;
						
						@$totEarnedConveyance =$itINFO[0]['totEarnedConveyance']+$earned_conveyance_allowance+($conveyance_allowance*$nummonth);
						$totEarnedMedical =$itINFO[0]['totEarnedConveyance']+$earned_medical_allowance+($medical_allowance*$nummonth);
						$totEarnedSpecial =$itINFO[0]['totEarnedMedical']+$earned_special_allowance+($special_allowance*$nummonth);
						$totPT = $itINFO[0]['totPT']+$pt+$excesPT+($pt*$nummonth);                
						$totEarnedPF = $itINFO[0]['totEarnedPF']+$earned_pf+($pf*$nummonth); 
						if(count($rowTaxableInfo)>0){		
							$totEarnedChildEduAllow =$itINFO[0]['totChildEduAllow']+$childreneducationalallowance+($rowTaxableInfo[0]['childreneducationalallowance']*$nummonth);
						}
						
						if(count($rowAllowInfo)>0){
						$otherincome=$rowAllowInfo[0]['incentive'];
						}
						$totOtherincome=$otherincome+$itINFO[0]['totOtherincome'];
						
						$taxlimitSql = "SELECT * FROM `income_tax_limit`";
						$taxlimitRes = $this->db->query($taxlimitSql);
						$taxlimitInfo = $taxlimitRes->result_array();
						//print_r($taxlimitInfo);
						
						if(count($rowTaxableInfo)>0){
							$deduction80C=$totEarnedPF+$rowTaxableInfo[0]['lic']+$rowTaxableInfo[0]['providentfund']+$rowTaxableInfo[0]['nsc']+$rowTaxableInfo[0]['childreneducationfee']+$rowTaxableInfo[0]['mutualfund']+$rowTaxableInfo[0]['nischint']+$rowTaxableInfo[0]['ulip']+$rowTaxableInfo[0]['postofficetax']+$rowTaxableInfo[0]['elss']+$rowTaxableInfo[0]['housingloanprincipal']+$rowTaxableInfo[0]['fixeddeposit'];
						}
						if($deduction80C > $taxlimitInfo[0]['deduction80C']){
							$eligible80C=$taxlimitInfo[0]['deduction80C'];
						}
						else{
							$eligible80C=$deduction80C;
						}
						
						if(count($rowTaxableInfo)>0){
							$deduction80Dselfsfamily=$rowTaxableInfo[0]['selfsfamily']; 
						}						
						if($deduction80Dselfsfamily > $taxlimitInfo[0]['selfsfamily']){
							$eligible80Dselfsfamily=$taxlimitInfo[0]['selfsfamily'];
						}
						 else{
							$eligible80Dselfsfamily=$deduction80Dselfsfamily;
						 }
						if(count($rowTaxableInfo)>0){
							$deduction80Dparents=$rowTaxableInfo[0]['parents']; 
						}						
						if($deduction80Dparents > $taxlimitInfo[0]['parents']){
							$eligible80Dparents=$taxlimitInfo[0]['parents'];
						}
						else{
							$eligible80Dparents=$deduction80Dparents;
						}
						
						if(count($rowTaxableInfo)>0){
							$deduction80Ehousingloaninterest=$rowTaxableInfo[0]['housingloaninterest']; 
						}						
						if($deduction80Ehousingloaninterest > $taxlimitInfo[0]['interest_home_loan_80E']){
							$eligible80Ehousingloaninterest=$taxlimitInfo[0]['interest_home_loan_80E'];
						}
						else{
							$eligible80Ehousingloaninterest=$deduction80Ehousingloaninterest;
						}
						
						if(count($rowTaxableInfo)>0){
							$deduction80DDnormaldisability=$rowTaxableInfo[0]['ddnormaldisability']; 
						}						
						if($deduction80DDnormaldisability > $taxlimitInfo[0]['dependants_normal_disability']){
							$eligible80DDnormaldisability=$taxlimitInfo[0]['dependants_normal_disability'];
						}
						else{
							$eligible80DDnormaldisability=$deduction80DDnormaldisability;
						}
						
						if(count($rowTaxableInfo)>0){
							$deduction80DDseveredisability=$rowTaxableInfo[0]['ddseveredisability']; 
						}						
						if($deduction80DDseveredisability > $taxlimitInfo[0]['dependants_severe_disability']){
							$eligible80DDseveredisability=$taxlimitInfo[0]['dependants_severe_disability'];  
						}							
						else{
							$eligible80DDseveredisability=$deduction80DDseveredisability;
						}
						
						if(count($rowTaxableInfo)>0){
						$deduction80Unormaldisability=$rowTaxableInfo[0]['unormaldisability']; 
						}						
						if($deduction80Unormaldisability > $taxlimitInfo[0]['self_normal_disability']){
							$eligible80Unormaldisability=$taxlimitInfo[0]['self_normal_disability'];
						}
						else{
							$eligible80Unormaldisability=$deduction80Unormaldisability;
						}
						
						if(count($rowTaxableInfo)>0){
						$deduction80Useveredisability=$rowTaxableInfo[0]['useveredisability'];  
						}						
						if($deduction80Useveredisability > $taxlimitInfo[0]['self_severe_disability']){
							$eligible80Useveredisability=$taxlimitInfo[0]['self_severe_disability'];
						}
						else{
							$eligible80Useveredisability=$deduction80Useveredisability;
						}
						
						if(count($rowTaxableInfo)>0){
						$deduction80DDBspecifieddiseases=$rowTaxableInfo[0]['specifieddiseases'];  
						}						
						if($deduction80DDBspecifieddiseases > $taxlimitInfo[0]['meducal_norman_case']){
							$eligible80DDBspecifieddiseases=$taxlimitInfo[0]['meducal_norman_case'];
						}
						else{
							$eligible80DDBspecifieddiseases=$deduction80DDBspecifieddiseases;
						}
						
						/* if(count($rowTaxableInfo)>0){
						$deduction80TTAsavingsaccountinterest=$rowTaxableInfo[0]['savingsaccountinterest'];  
						}						
						if($deduction80TTAsavingsaccountinterest > $taxlimitInfo[0]['savingsaccountinterest']){
							$eligible80TTAsavingsaccountinterest=$taxlimitInfo[0]['savingsaccountinterest'];
						}
						else{
							$eligible80TTAsavingsaccountinterest=$deduction80TTAsavingsaccountinterest;
						} */
						
						if(count($rowTaxableInfo)>0){
						$taxableSalary=$eligible80C+$eligible80Dselfsfamily+$eligible80Dparents+$rowTaxableInfo[0]['highereducation']+$eligible80Ehousingloaninterest+$eligible80DDnormaldisability+$eligible80DDseveredisability+$eligible80Unormaldisability+$eligible80Useveredisability+$eligible80DDBspecifieddiseases+$eligible80TTAsavingsaccountinterest+$rowTaxableInfo[0]['leavetravelconcession'];
						}
						
						$totEarnedGross =  $totOtherincome+$totEarnedBasic+$actualEarnedHRA+$totEarnedConveyance+$totEarnedMedical+$totEarnedSpecial+$totEarnedChildEduAllow;
						
						$exempt= $taxable= $exemptChildEduAllow= $exemptConv= $exemptMedical=0;
						
						if(count($rowTaxableInfo)>0){
							$exemptMedical=min($totEarnedMedical,($rowTaxableInfo[0]['medicalexpensesperannum']*$month_diff));                
							$exemptConv=$rowTaxableInfo[0]['conv_allowance']*$month_diff;
							$exemptChildEduAllow=min($totEarnedChildEduAllow,($rowTaxableInfo[0]['childreneducationalallowance']*$month_diff)); 
						}						
						
						$exempt = $exemptMedical+$exemptConv+$exemptChildEduAllow+$minValueHRA;
						
						$taxable=$totEarnedGross-$exempt;                    
						
						$taxableIncome = $taxable-$totPT-$taxableSalary;
						$donation50per =0;
						if(count($rowTaxableInfo)>0){
						$donation50per =($rowTaxableInfo[0]['donation']*(50/100));
						}
						$taxableSalary10per =($taxableIncome*(10/100));
						$ActualDonation=min($donation50per,$taxableSalary10per);
						
						$taxableIncome=$taxableIncome-$ActualDonation;
						
						$it_value=$taxonTotalIncome=$RoundupTax=$educationCess=0;
						
						$reSit=$this->db->query("SELECT * from `it_slab_master`");
						$roWit=$reSit->result_array();
						//print_r($roWit);exit;
						for($c=0; $c<count($roWit); $c++)
						{                    
							$range_it=explode('-',$roWit[$c]['range']);
							if($taxableIncome >= $range_it[0] && $taxableIncome <= $range_it[1]){                       
								$it_value=explode('+',$roWit[$c]['it_value']);
								if(count($it_value)>1){
								$taxonTotalIncome=((($taxableIncome-($range_it[0]-1))*($it_value[0]/100)))+$it_value[1];
								}
							}
						}
						
						$educationCess=$taxonTotalIncome*(3/100);
						$RoundupTax=$taxonTotalIncome+$educationCess;                
						
						$totPaidTax = $itINFO[0]['totIncomeTax'];
						$income_tax=0;	
						if(count($rowAllowInfo)>0){					
							if($month=='03')
							{
								//$income_tax=round(($RoundupTax-$totPaidTax));
								$income_tax=$rowAllowInfo[0]['income_tax'];
							}
							else if($rowAllowInfo[0]['income_tax']>0)
							{
								$income_tax=$rowAllowInfo[0]['income_tax'];
							}
						}
						//  else{
							//  //$income_tax=$rowAllowInfo[0]['income_tax'];
							// 	//$income_tax=round(($RoundupTax-$totPaidTax)/($nummonth+1));
							//  if($rowAllEmp[$k]['login_id']=='10024')
							//  $income_tax=$rowAllowInfo[0]['income_tax'];
							//  else
							//$income_tax=0;
						//  }
						
						$total_deduction=$earned_pf+$pt+$earned_esi+$advance+$loan+$recovery+$other_deduction+$donation+$income_tax;
						$net_salary=round($earned_gross-$total_deduction);
									  
						//echo "Select * From `salary_sheet` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND salary_year='$year' AND salary_month='$month'";
					  
						$resSalarySheet = $this->db->query("Select * From `salary_sheet` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND salary_year='$year' AND salary_month='$month'");
						$numSalarySheet=  count($resSalarySheet->result_array());
						if($numSalarySheet > 0)
						{                   
							$upQuery="UPDATE `salary_sheet` SET                                    
												working_days = '".$totalDay."',                                  
												weekly_off = '".$numWeeklyOff."',
												holidays = '".$numDeclaredHoliday."',                                    
												paid_days = '".$paidDays."',
												absent_days = '".$absentDay."',
												arrear_days = '".$arrear_days."',
												ctc = AES_ENCRYPT('".$ctc."', '".$encypt."'),
												reimbursement = AES_ENCRYPT('".$reimbursement."', '".$encypt."'),    
												gross = AES_ENCRYPT('".$gross."', '".$encypt."'),                                    
												basic = AES_ENCRYPT('".$basic."', '".$encypt."'), 
												hra = AES_ENCRYPT('".$hra."', '".$encypt."'),
												medical_allowance = AES_ENCRYPT('".$medical_allowance."', '".$encypt."'),
												conveyance_allowance = AES_ENCRYPT('".$conveyance_allowance."', '".$encypt."'),
												special_allowance = AES_ENCRYPT('".$special_allowance."', '".$encypt."'),                                    
												earned_basic = AES_ENCRYPT('".$earned_basic."', '".$encypt."'),
												earned_hra = AES_ENCRYPT('".$earned_hra."', '".$encypt."'),
												earned_medical_allowance = AES_ENCRYPT('".$earned_medical_allowance."', '".$encypt."'),
												earned_conveyance_allowance = AES_ENCRYPT('".$earned_conveyance_allowance."', '".$encypt."'),
												earned_special_allowance = AES_ENCRYPT('".$earned_special_allowance."', '".$encypt."'),                                             
												arrear = AES_ENCRYPT('".$arrear."', '".$encypt."'),
												arrear_basic = AES_ENCRYPT('".$arrear_basic."', '".$encypt."'),
												arrear_hra = AES_ENCRYPT('".$arrear_hra."', '".$encypt."'),                                        
												arrear_conveyance_allowance = AES_ENCRYPT('".$arrear_conveyance_allowance."', '".$encypt."'),
												arrear_special_allowance = AES_ENCRYPT('".$arrear_special_allowance."', '".$encypt."'),                                             
												performance_incentive = AES_ENCRYPT('".$performance_incentive."', '".$encypt."'),
												attendance_incentive = AES_ENCRYPT('".$attendance_incentive."', '".$encypt."'),
												earned_city_allowance = AES_ENCRYPT('".$city_allowance."', '".$encypt."'),
												earned_food_allowance = AES_ENCRYPT('".$food_allowance."', '".$encypt."'),
												referal_bonus = AES_ENCRYPT('".$referal_bonus."', '".$encypt."'),
												leave_encashment = AES_ENCRYPT('".$leave_encashment."', '".$encypt."'),
												earned_relocation_allowance = AES_ENCRYPT('".$relocation_allowance."', '".$encypt."'),
												earned_personal_allowance = AES_ENCRYPT('".$personal_allowance."', '".$encypt."'),
												child_edu_allowance = AES_ENCRYPT('".$childreneducationalallowance."', '".$encypt."'),    
												pf = AES_ENCRYPT('".$pf."', '".$encypt."'),
												earned_pf = AES_ENCRYPT('".$earned_pf."', '".$encypt."'),                                        
												employer_pf = AES_ENCRYPT('".$employer_pf."', '".$encypt."'),
												earned_employer_pf = AES_ENCRYPT('".$earned_employer_pf."', '".$encypt."'),
												pt = AES_ENCRYPT('".$pt."', '".$encypt."'),                                     
												esi = AES_ENCRYPT('".$esi."', '".$encypt."'),
												earned_esi = AES_ENCRYPT('".$earned_esi."', '".$encypt."'),
												employer_esi = AES_ENCRYPT('".$employer_esi."', '".$encypt."'),
												otherincome = AES_ENCRYPT('".$otherincome."', '".$encypt."'),                                            
												earned_employer_esi = AES_ENCRYPT('".$earned_employer_esi."', '".$encypt."'),
												advance = AES_ENCRYPT('".$advance."', '".$encypt."'),
												loan = AES_ENCRYPT('".$loan."', '".$encypt."'),
												recovery = AES_ENCRYPT('".$recovery."', '".$encypt."'),
												other_deduction = AES_ENCRYPT('".$other_deduction."', '".$encypt."'),
												income_tax = AES_ENCRYPT('".$income_tax."', '".$encypt."'),
												donation = AES_ENCRYPT('".$donation."', '".$encypt."'),
												earned_gross = AES_ENCRYPT('".$earned_gross."', '".$encypt."'),
												earned_ctc = AES_ENCRYPT('".$earned_ctc."', '".$encypt."'),
												total_deduction = AES_ENCRYPT('".$total_deduction."', '".$encypt."'),
												net_salary = AES_ENCRYPT('".$net_salary."', '".$encypt."')
												WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND salary_year='".$year."' AND salary_month='".$month."'";
						//if($rowAllEmp[$k]['login_id']==10204){echo $insertQuery; exit;}
						   $this->db->query($upQuery);
						}
						else
						{                 
							$insertQuery="INSERT INTO `salary_sheet` SET login_id='".$rowAllEmp[$k]['login_id']."',                                   
												working_days = '".$totalDay."',                                  
												weekly_off = '".$numWeeklyOff."',
												holidays = '".$numDeclaredHoliday."',                                    
												paid_days = '".$paidDays."',
												absent_days = '".$absentDay."',
												arrear_days = '".$arrear_days."',
												ctc = AES_ENCRYPT('".$ctc."', '".$encypt."'),
												reimbursement = AES_ENCRYPT('".$reimbursement."', '".$encypt."'),
												gross = AES_ENCRYPT('".$gross."', '".$encypt."'),                                    
												basic = AES_ENCRYPT('".$basic."', '".$encypt."'), 
												hra = AES_ENCRYPT('".$hra."', '".$encypt."'),
												medical_allowance = AES_ENCRYPT('".$medical_allowance."', '".$encypt."'),
												conveyance_allowance = AES_ENCRYPT('".$conveyance_allowance."', '".$encypt."'),
												special_allowance = AES_ENCRYPT('".$special_allowance."', '".$encypt."'),                                    
												earned_basic = AES_ENCRYPT('".$earned_basic."', '".$encypt."'),
												earned_hra = AES_ENCRYPT('".$earned_hra."', '".$encypt."'),
												earned_medical_allowance = AES_ENCRYPT('".$earned_medical_allowance."', '".$encypt."'),
												earned_conveyance_allowance = AES_ENCRYPT('".$earned_conveyance_allowance."', '".$encypt."'),
												earned_special_allowance = AES_ENCRYPT('".$earned_special_allowance."', '".$encypt."'),                                             
												arrear = AES_ENCRYPT('".$arrear."', '".$encypt."'),
												arrear_basic = AES_ENCRYPT('".$arrear_basic."', '".$encypt."'),
												arrear_hra = AES_ENCRYPT('".$arrear_hra."', '".$encypt."'),                                        
												arrear_conveyance_allowance = AES_ENCRYPT('".$arrear_conveyance_allowance."', '".$encypt."'),
												arrear_special_allowance = AES_ENCRYPT('".$arrear_special_allowance."', '".$encypt."'),                                             
												performance_incentive = AES_ENCRYPT('".$performance_incentive."', '".$encypt."'),
												attendance_incentive = AES_ENCRYPT('".$attendance_incentive."', '".$encypt."'),
												earned_city_allowance = AES_ENCRYPT('".$city_allowance."', '".$encypt."'),
												earned_food_allowance = AES_ENCRYPT('".$food_allowance."', '".$encypt."'),
												referal_bonus = AES_ENCRYPT('".$referal_bonus."', '".$encypt."'),
												leave_encashment = AES_ENCRYPT('".$leave_encashment."', '".$encypt."'),
												earned_relocation_allowance = AES_ENCRYPT('".$relocation_allowance."', '".$encypt."'),
												earned_personal_allowance = AES_ENCRYPT('".$personal_allowance."', '".$encypt."'),
												child_edu_allowance = AES_ENCRYPT('".$childreneducationalallowance."', '".$encypt."'),    
												pf = AES_ENCRYPT('".$pf."', '".$encypt."'),
												earned_pf = AES_ENCRYPT('".$earned_pf."', '".$encypt."'),                                        
												employer_pf = AES_ENCRYPT('".$employer_pf."', '".$encypt."'),
												earned_employer_pf = AES_ENCRYPT('".$earned_employer_pf."', '".$encypt."'),
												pt = AES_ENCRYPT('".$pt."', '".$encypt."'),                                     
												esi = AES_ENCRYPT('".$esi."', '".$encypt."'),
												earned_esi = AES_ENCRYPT('".$earned_esi."', '".$encypt."'),
												employer_esi = AES_ENCRYPT('".$employer_esi."', '".$encypt."'),
												earned_employer_esi = AES_ENCRYPT('".$earned_employer_esi."', '".$encypt."'),
												advance = AES_ENCRYPT('".$advance."', '".$encypt."'),
												loan = AES_ENCRYPT('".$loan."', '".$encypt."'),
												recovery = AES_ENCRYPT('".$recovery."', '".$encypt."'),
												other_deduction = AES_ENCRYPT('".$other_deduction."', '".$encypt."'),
												otherincome = AES_ENCRYPT('".$otherincome."', '".$encypt."'),    
												income_tax = AES_ENCRYPT('".$income_tax."', '".$encypt."'),
												donation = AES_ENCRYPT('".$donation."', '".$encypt."'),
												earned_gross = AES_ENCRYPT('".$earned_gross."', '".$encypt."'),
												earned_ctc = AES_ENCRYPT('".$earned_ctc."', '".$encypt."'),    
												total_deduction = AES_ENCRYPT('".$total_deduction."', '".$encypt."'),
												net_salary = AES_ENCRYPT('".$net_salary."', '".$encypt."'),
												salary_year='".$year."',
												salary_month='".$month."'";
							//if($rowAllEmp[$k]['login_id']==10204){echo $insertQuery; exit;}
							$this->db->query($insertQuery);
						} 
					  
						$resTaxSheet = $this->db->query("Select * From `income_tax_details` WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND tax_year='$year' AND tax_month='$month'");
						$numTaxSheet=  count($resTaxSheet->result_array());

						if($numTaxSheet > 0)
						{
							$updatetaxSql="UPDATE `income_tax_details` SET child_edu_allow = AES_ENCRYPT('".$totEarnedChildEduAllow."', '".$encypt."'), 
												tot_basic = AES_ENCRYPT('".$totEarnedBasic."', '".$encypt."'),
													actualearnedhra = AES_ENCRYPT('".$actualEarnedHRA."', '".$encypt."'), 
													rentpaidexcess10perofsalary = AES_ENCRYPT('".$rentpaidExcess10perofsalary."', '".$encypt."'),
													basic50per = AES_ENCRYPT('".$basic50per."', '".$encypt."'),
													tot_hra = AES_ENCRYPT('".$minValueHRA."', '".$encypt."'),
													tot_conv = AES_ENCRYPT('".$totEarnedConveyance."', '".$encypt."'),
													tot_medical = AES_ENCRYPT('".$totEarnedMedical."', '".$encypt."'),
													tot_special = AES_ENCRYPT('".$totEarnedSpecial."', '".$encypt."'),
													tot_gross_salary = AES_ENCRYPT('".$totEarnedGross."', '".$encypt."'),
													tot_pt = AES_ENCRYPT('".$totPT."', '".$encypt."'),
													tot_pf = AES_ENCRYPT('".$totEarnedPF."', '".$encypt."'),                             
													deduction80C = AES_ENCRYPT('".$deduction80C."', '".$encypt."'),
													eligible80C = AES_ENCRYPT('".$eligible80C."', '".$encypt."'),
													deduction80Dselfsfamily = AES_ENCRYPT('".$deduction80Dselfsfamily."', '".$encypt."'),
													eligible80Dselfsfamily = AES_ENCRYPT('".$eligible80Dselfsfamily."', '".$encypt."'),
													deduction80Dparents = AES_ENCRYPT('".$deduction80Dparents."', '".$encypt."'),
													eligible80Dparents = AES_ENCRYPT('".$eligible80Dparents."', '".$encypt."'),
													deduction80Ehousingloaninterest = AES_ENCRYPT('".$deduction80Ehousingloaninterest."', '".$encypt."'),
													eligible80Ehousingloaninterest = AES_ENCRYPT('".$eligible80Ehousingloaninterest."', '".$encypt."'),
													deduction80DDnormaldisability = AES_ENCRYPT('".$deduction80DDnormaldisability."', '".$encypt."'),
													eligible80DDnormaldisability = AES_ENCRYPT('".$eligible80DDnormaldisability."', '".$encypt."'),
													deduction80DDseveredisability = AES_ENCRYPT('".$deduction80DDseveredisability."', '".$encypt."'),
													eligible80DDseveredisability = AES_ENCRYPT('".$eligible80DDseveredisability."', '".$encypt."'),
													deduction80Unormaldisability = AES_ENCRYPT('".$deduction80Unormaldisability."', '".$encypt."'),
													eligible80Unormaldisability = AES_ENCRYPT('".$eligible80Unormaldisability."', '".$encypt."'),
													deduction80Useveredisability = AES_ENCRYPT('".$deduction80Useveredisability."', '".$encypt."'),
													eligible80Useveredisability = AES_ENCRYPT('".$eligible80Useveredisability."', '".$encypt."'),
													deduction80DDBspecifieddiseases = AES_ENCRYPT('".$deduction80DDBspecifieddiseases."', '".$encypt."'),
													eligible80DDBspecifieddiseases = AES_ENCRYPT('".$eligible80DDBspecifieddiseases."', '".$encypt."'),
													deduction80TTAsavingsaccountinterest = AES_ENCRYPT('".$deduction80TTAsavingsaccountinterest."', '".$encypt."'),
													eligible80TTAsavingsaccountinterest = AES_ENCRYPT('".$eligible80TTAsavingsaccountinterest."', '".$encypt."'),
													tot_taxablesalary = AES_ENCRYPT('".$taxableSalary."', '".$encypt."'),
													taxable = AES_ENCRYPT('".$taxable."', '".$encypt."'), 
													exemptchildeduallow = AES_ENCRYPT('".$exemptChildEduAllow."', '".$encypt."'),
													exemptConv = AES_ENCRYPT('".$exemptConv."', '".$encypt."'), 
													exemptMedical = AES_ENCRYPT('".$exemptMedical."', '".$encypt."'),    
													exempt = AES_ENCRYPT('".$exempt."', '".$encypt."'), 
													otherincome = AES_ENCRYPT('".$totOtherincome."', '".$encypt."'),                                                 
													donation50per  = AES_ENCRYPT('".$donation50per."', '".$encypt."'),
													taxablesalary10per = AES_ENCRYPT('".$taxableSalary10per."', '".$encypt."'),
													actualdonation = AES_ENCRYPT('".$ActualDonation."', '".$encypt."'),
													taxableincome = AES_ENCRYPT('".$taxableIncome."', '".$encypt."'),
													taxontotalincome = AES_ENCRYPT('".$taxonTotalIncome."', '".$encypt."'),
													educationcess = AES_ENCRYPT('".$educationCess."', '".$encypt."'),
													rounduptax = AES_ENCRYPT('".$RoundupTax."', '".$encypt."'),
													totpaidtax = AES_ENCRYPT('".$totPaidTax."', '".$encypt."'), 
													income_tax = AES_ENCRYPT('".$income_tax."', '".$encypt."')                                          
									 WHERE login_id='".$rowAllEmp[$k]['login_id']."' AND tax_month = '".$month."' AND tax_year = '".$year."'";
							//if($rowAllEmp[$k]['login_id']==10427){echo $updatetaxSql; exit;}
								$this->db->query($updatetaxSql);
						}    
						else
						{
							$inserttaxQuery="INSERT INTO `income_tax_details` SET child_edu_allow = AES_ENCRYPT('".$totEarnedChildEduAllow."', '".$encypt."'), 
												tot_basic = AES_ENCRYPT('".$totEarnedBasic."', '".$encypt."'),
													actualearnedhra = AES_ENCRYPT('".$actualEarnedHRA."', '".$encypt."'), 
													rentpaidexcess10perofsalary = AES_ENCRYPT('".$rentpaidExcess10perofsalary."', '".$encypt."'),
													basic50per = AES_ENCRYPT('".$basic50per."', '".$encypt."'),
													tot_hra = AES_ENCRYPT('".$minValueHRA."', '".$encypt."'),
													tot_conv = AES_ENCRYPT('".$totEarnedConveyance."', '".$encypt."'),
													tot_medical = AES_ENCRYPT('".$totEarnedMedical."', '".$encypt."'),
													tot_special = AES_ENCRYPT('".$totEarnedSpecial."', '".$encypt."'),
													tot_gross_salary = AES_ENCRYPT('".$totEarnedGross."', '".$encypt."'),
													tot_pt = AES_ENCRYPT('".$totPT."', '".$encypt."'),
													tot_pf = AES_ENCRYPT('".$totEarnedPF."', '".$encypt."'),
													deduction80C = AES_ENCRYPT('".$deduction80C."', '".$encypt."'),
													eligible80C = AES_ENCRYPT('".$eligible80C."', '".$encypt."'),
													deduction80Dselfsfamily = AES_ENCRYPT('".$deduction80Dselfsfamily."', '".$encypt."'),
													eligible80Dselfsfamily = AES_ENCRYPT('".$eligible80Dselfsfamily."', '".$encypt."'),
													deduction80Dparents = AES_ENCRYPT('".$deduction80Dparents."', '".$encypt."'),
													eligible80Dparents = AES_ENCRYPT('".$eligible80Dparents."', '".$encypt."'),
													deduction80Ehousingloaninterest = AES_ENCRYPT('".$deduction80Ehousingloaninterest."', '".$encypt."'),
													eligible80Ehousingloaninterest = AES_ENCRYPT('".$eligible80Ehousingloaninterest."', '".$encypt."'),
													deduction80DDnormaldisability = AES_ENCRYPT('".$deduction80DDnormaldisability."', '".$encypt."'),
													eligible80DDnormaldisability = AES_ENCRYPT('".$eligible80DDnormaldisability."', '".$encypt."'),
													deduction80DDseveredisability = AES_ENCRYPT('".$deduction80DDseveredisability."', '".$encypt."'),
													eligible80DDseveredisability = AES_ENCRYPT('".$eligible80DDseveredisability."', '".$encypt."'),
													deduction80Unormaldisability = AES_ENCRYPT('".$deduction80Unormaldisability."', '".$encypt."'),
													eligible80Unormaldisability = AES_ENCRYPT('".$eligible80Unormaldisability."', '".$encypt."'),
													deduction80Useveredisability = AES_ENCRYPT('".$deduction80Useveredisability."', '".$encypt."'),
													eligible80Useveredisability = AES_ENCRYPT('".$eligible80Useveredisability."', '".$encypt."'),
													deduction80DDBspecifieddiseases = AES_ENCRYPT('".$deduction80DDBspecifieddiseases."', '".$encypt."'),
													eligible80DDBspecifieddiseases = AES_ENCRYPT('".$eligible80DDBspecifieddiseases."', '".$encypt."'),
													deduction80TTAsavingsaccountinterest = AES_ENCRYPT('".$deduction80TTAsavingsaccountinterest."', '".$encypt."'),
													eligible80TTAsavingsaccountinterest = AES_ENCRYPT('".$eligible80TTAsavingsaccountinterest."', '".$encypt."'),
													tot_taxablesalary = AES_ENCRYPT('".$taxableSalary."', '".$encypt."'),
													taxable = AES_ENCRYPT('".$taxable."', '".$encypt."'), 
													exemptchildeduallow = AES_ENCRYPT('".$exemptChildEduAllow."', '".$encypt."'),
													exemptConv = AES_ENCRYPT('".$exemptConv."', '".$encypt."'), 
													exemptMedical = AES_ENCRYPT('".$exemptMedical."', '".$encypt."'),    
													exempt = AES_ENCRYPT('".$exempt."', '".$encypt."'),
													otherincome = AES_ENCRYPT('".$totOtherincome."', '".$encypt."'),
													donation50per  = AES_ENCRYPT('".$donation50per."', '".$encypt."'),
													taxablesalary10per = AES_ENCRYPT('".$taxableSalary10per."', '".$encypt."'),
													actualdonation = AES_ENCRYPT('".$ActualDonation."', '".$encypt."'),
													taxableincome = AES_ENCRYPT('".$taxableIncome."', '".$encypt."'),
													taxontotalincome = AES_ENCRYPT('".$taxonTotalIncome."', '".$encypt."'),
													educationcess = AES_ENCRYPT('".$educationCess."', '".$encypt."'),
													rounduptax = AES_ENCRYPT('".$RoundupTax."', '".$encypt."'),
													totpaidtax = AES_ENCRYPT('".$totPaidTax."', '".$encypt."'), 
													income_tax = AES_ENCRYPT('".$income_tax."', '".$encypt."'),
													tax_month = '".$month."',tax_year = '".$year."',                     
													login_id='".$rowAllEmp[$k]['login_id']."'";
								 //if($rowAllEmp[$k]['login_id']==10427){echo $inserttaxQuery; exit;}                   
								$this->db->query($inserttaxQuery);
						}    
					 
					//For PDF Generate Salary Slip
					
				   // }
					  // echo $p++.'<br/>';exit; 
				}
				$successMsg = TRUE;
				$this->mViewData['successMsg']    = 'Salary Generated Successfully';
		}
		$this->render('hr/generate_salary_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/salary_sheet_list_script');
	
	
}
	public function salary_sheet()
	{
		$this->mViewData['pageTitle']    = 'Salary Sheet';
		$this->render('hr/salary_sheet_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/salary_sheet_list_script');
	}
	public function get_all_employee()
	{ 
		$result = $this->Hr_model->get_all_employee(); 
		echo json_encode($result);       
	}
	
	public function salary_slip()
	{
		$this->mViewData['pageTitle']    = 'Salary Slip';
		
		$loginID = $this->session->userdata('user_id');
		if(isset($_GET['id'])){
			$loginID = $_GET['id'];
		}
		if ($loginID != "")
		{   
			$actionURL = base_url().'?id='.$loginID;
		}
		else
		{  
			$actionURL = base_url();
		} 

		
		$month = date('m');
		$year = date('Y'); 
		if($this->input->post('searchEmployee') == 'Find')
		{
			$month=$this->input->post('selMonth');
			$year=$this->input->post('selYear');
		}
		$userSql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'";
		$userRes = $this->db->query($userSql);
		$userInfo = $userRes->result_array();
		$maxPL =0;
		$maxSL = 0;
		$maxLeave =0;

		$leaveINFO = 0;
		$avlPL =0;
		$avlSL =0;
		if(@$userInfo[0]['emp_type'] =='F')
		{
			$maxPL = $this->getMaxLeave($loginID, 'P', $year);
			$maxSL = $this->getMaxLeave($loginID, 'S', $year);
			$maxLeave = $maxPL + $maxSL;

			$leaveINFO = $this->getLeaveTaken($loginID, $month, $year, 'A');
			$avlPL = $maxPL - $leaveINFO['ob_pl'];
			$avlSL = $maxSL - $leaveINFO['ob_sl'];
			$totAvlleave = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
		}
		else
		{
			

			$yearr = date("Y");
			$contLeaveSql = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '".$yearr."' WHERE i.`login_id` = '".$loginID."'";
			$contLeaveRes = $this->db->query($contLeaveSql);
			$contLeaveInfo = $contLeaveRes->result_array();
			$maxPL  = $contLeaveInfo[0]['ob_pl'];
			$leaveINFO = $this->getLeaveTaken($loginID, $month, $year, 'A');
			$avlPL = $maxPL - $leaveINFO['ob_pl'];
			$totAvlleave =$maxPL > $leaveINFO['ob_pl'] ;
		}
		$this->mViewData['avlPL'] = $avlPL;
		$this->mViewData['avlSL'] = $avlSL;
		if($month >= 4)
		{
			$financialyear = $year.'-'.($year+1);
		}
		else
		{
			$financialyear = ($year-1).'-'.$year;
		}
		$encypt = $this->config->item('masterKey');
		$empSql="SELECT i.*, b.bank_name,it.childreneducationalallowance,
				si.bank_no,d.dept_name,si.mediclaim_no,si.pf_no,AES_DECRYPT(ad.food_allowance, '".$encypt."') AS food_allowance,
				AES_DECRYPT(ad.personal_allowance, '".$encypt."') AS personal_allowance, AES_DECRYPT(ad.relocation_allowance, '".$encypt."') AS relocation_allowance,
				AES_DECRYPT(ad.city_allowance, '".$encypt."') AS city_allowance, u.desg_name,
				s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days,s.salary_month,s.salary_year, 
				AES_DECRYPT(s.ctc, '".$encypt."') AS ctc,
				AES_DECRYPT(s.gross, '".$encypt."') AS gross,                                    
				AES_DECRYPT(s.basic, '".$encypt."') AS basic, 
				AES_DECRYPT(s.hra, '".$encypt."') AS hra,
				AES_DECRYPT(s.medical_allowance, '".$encypt."') AS medical_allowance,
				AES_DECRYPT(s.conveyance_allowance, '".$encypt."') AS conveyance_allowance,
				AES_DECRYPT(s.special_allowance, '".$encypt."') AS special_allowance,                                    
				AES_DECRYPT(s.earned_basic, '".$encypt."') AS earned_basic,
				AES_DECRYPT(s.earned_hra, '".$encypt."') AS earned_hra,
				AES_DECRYPT(s.earned_medical_allowance, '".$encypt."') AS earned_medical_allowance,
				AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."') AS earned_conveyance_allowance,
				AES_DECRYPT(s.earned_special_allowance, '".$encypt."') AS earned_special_allowance,                                             
				AES_DECRYPT(s.arrear, '".$encypt."') AS arrear,
				AES_DECRYPT(s.arrear_basic, '".$encypt."') AS arrear_basic,
				AES_DECRYPT(s.arrear_hra, '".$encypt."') AS arrear_hra,                                        
				AES_DECRYPT(s.arrear_conveyance_allowance, '".$encypt."') AS arrear_conveyance_allowance,
				AES_DECRYPT(s.arrear_special_allowance, '".$encypt."') AS arrear_special_allowance,                                             
				AES_DECRYPT(s.performance_incentive, '".$encypt."') AS performance_incentive,
				AES_DECRYPT(s.attendance_incentive, '".$encypt."') AS attendance_incentive,
				AES_DECRYPT(s.earned_city_allowance, '".$encypt."') AS earned_city_allowance,
				AES_DECRYPT(s.earned_food_allowance, '".$encypt."') AS earned_food_allowance,
				AES_DECRYPT(s.referal_bonus, '".$encypt."') AS referal_bonus,
				AES_DECRYPT(s.leave_encashment, '".$encypt."') AS leave_encashment,
				AES_DECRYPT(s.earned_relocation_allowance, '".$encypt."') AS earned_relocation_allowance,
				AES_DECRYPT(s.earned_personal_allowance, '".$encypt."') AS earned_personal_allowance,
				AES_DECRYPT(s.child_edu_allowance, '".$encypt."') AS child_edu_allowance,    
				AES_DECRYPT(s.pf, '".$encypt."') AS pf,
				AES_DECRYPT(s.earned_pf, '".$encypt."') AS earned_pf,                                        
				AES_DECRYPT(s.employer_pf, '".$encypt."') AS employer_pf,
				AES_DECRYPT(s.earned_employer_pf, '".$encypt."') AS earned_employer_pf,
				AES_DECRYPT(s.pt, '".$encypt."') AS pt,                                     
				AES_DECRYPT(s.esi, '".$encypt."') AS esi,
				AES_DECRYPT(s.earned_esi, '".$encypt."') AS earned_esi,
				AES_DECRYPT(s.employer_esi, '".$encypt."') AS employer_esi,
				AES_DECRYPT(s.earned_employer_esi, '".$encypt."') AS earned_employer_esi,
				AES_DECRYPT(s.advance, '".$encypt."') AS advance,
				AES_DECRYPT(s.loan, '".$encypt."') AS loan,
				AES_DECRYPT(s.recovery, '".$encypt."') AS recovery,
				AES_DECRYPT(s.other_deduction, '".$encypt."') AS other_deduction,
				AES_DECRYPT(s.income_tax, '".$encypt."') AS income_tax,
				AES_DECRYPT(s.donation, '".$encypt."') AS donation,
				AES_DECRYPT(s.earned_gross, '".$encypt."') AS earned_gross,
				AES_DECRYPT(s.total_deduction, '".$encypt."') AS total_deduction,
				AES_DECRYPT(s.net_salary, '".$encypt."') AS net_salary 
				FROM `internal_user` AS i 
					LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id 
					LEFT JOIN `income_tax` AS it ON it.login_id = i.login_id AND it.fyear = '".$financialyear."'
					LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
					LEFT JOIN `department` AS d ON d.dept_id = i.department
					LEFT JOIN `allowance_deduction` AS ad ON ad.login_id = i.login_id AND ad.lyear = s.salary_year AND ad.lmonth = s.salary_month
					LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id 
					LEFT JOIN bank_master AS b ON b.bank_id = si.bank
					WHERE i.login_id = $loginID  AND s.salary_year = $year AND s.salary_month = $month";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		//echo "<pre>";print_r($this->mViewData['empInfo']);echo "</pre>";
		$this->mViewData['count'] = count($empInfo);
		$net_salary = 0;
		if(count($empInfo)>0){
			$net_salary = $empInfo[0]['net_salary'];
		}
		$this->mViewData['net_salary'] = $net_salary;
		$this->mViewData['net_salary_words'] = $this->convert_number_to_words($net_salary);
		//var_dump($this->mViewData['empInfo']);
		$this->render('hr/salary_slip_view', 'full_width',$this->mViewData); 
	} 
	public function convert_number_to_words($number) 
	{ 
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			100000              => 'Lakh',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}
	
	public function mail_salary_slip()
	{	
		$this->mViewData['pageTitle']    = 'Mail Salary Slip';
		$successMsg    = '';
		if($this->input->post('mailSalarySlip') == 'SEND' || null !== $this->input->post('submit'))
		{
			$encypt = $this->config->item('masterKey');
			$resAllEmp = $this->db->query("SELECT * FROM `internal_user` WHERE login_id != '10010' AND user_status ='1' AND department !='1'");
			
			$resAllEmp_arr = $resAllEmp->result_array();
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');  
			if($month !="" && $year !=""){
			switch($month)
			{
				case 1: $mon='January';
					break;
				case 2: $mon='February';
					break;
				case 3: $mon='March';
					break;
				case 4: $mon='April';
					break;
				case 5: $mon='May';
					break;
				case 6: $mon='June';
					break;
				case 7: $mon='July';
					break;
				case 8: $mon='August';
					break;
				case 9: $mon='September';
					 break;
				case 10: $mon='October';
					  break;
				case 11: $mon='November';
					  break;
				case 12: $mon='December';
					  break;
			}
			//echo $mon; exit;

			foreach($resAllEmp_arr as $rowAllEmp)
			{ 	
				$content = $count ='';
				$loginID = $rowAllEmp['login_id'];

				$userSql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'";
				$userRes = $this->db->query($userSql);
				$userInfo = $userRes->result_array();
			if($userInfo[0]['emp_type'] =='F')
			{
				$maxPL = $this->getMaxLeave($loginID, 'P');
				$maxSL = $this->getMaxLeave($loginID, 'S');
				$maxLeave = $maxPL + $maxSL;

				$leaveINFO = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
				$avlPL = $maxPL - $leaveINFO['ob_pl'];
				$avlSL = $maxSL - $leaveINFO['ob_sl'];
				$totAvlleave = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
			}
			else
			{
				$maxPL =0;
				$maxSL = 0;
				$maxLeave =0;

				$leaveINFO = 0;
				$avlPL =0;
				$avlSL =0;

				$yearr = date("Y");
				$contLeaveSql = "SELECT i.`join_date`, f.`ob_pl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '".$yearr."' WHERE i.`login_id` = '".$loginID."'";
				$contLeaveRes = $this->db->query($contLeaveSql);
				$contLeaveInfo = $contLeaveRes->result_array();
				$maxPL  = $contLeaveInfo[0]['ob_pl'];
				$leaveINFO = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
				$avlPL = $maxPL - $leaveINFO['ob_pl'];
				$totAvlleave =$maxPL > $leaveINFO['ob_pl'] ;
			}

			$leavethisSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$loginID' AND `month` = '$month' AND `year` = '$year'";
			$leavethisRES = $this->db->query($leavethisSQL);
			$leavethisINFO = $leavethisRES->result_array();

			if($leavethisINFO[0]['ob_pl']>0)
				$leavethisPL = $leavethisINFO[0]['ob_pl'];
			else
			$leavethisPL =0;
			if($leavethisINFO[0]['ob_sl']>0)
				$leavethisSL = $leavethisINFO[0]['ob_sl'];
			else
				$leavethisSL=0;

			if($month >= 4)
			{
				$financialyear = $year.'-'.($year+1);
			}
			else
			{
				$financialyear = ($year-1).'-'.$year;
			}

		$empSql="SELECT i.*, b.bank_name,d.dept_name,si.bank_no,si.mediclaim_no,si.pf_no,si.uan_no,AES_DECRYPT(ad.food_allowance, '".$encypt."') AS food_allowance,AES_DECRYPT(ad.personal_allowance, '".$encypt."') AS personal_allowance,AES_DECRYPT(ad.relocation_allowance, '".$encypt."') AS relocation_allowance,AES_DECRYPT(ad.city_allowance, '".$encypt."') AS city_allowance,u.desg_name, it.*,
				s.salary_month, s.salary_year, s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days, 
				AES_DECRYPT(s.ctc, '".$encypt."') AS ctc,
				AES_DECRYPT(s.gross, '".$encypt."') AS gross,                                    
				AES_DECRYPT(s.basic, '".$encypt."') AS basic, 
				AES_DECRYPT(s.hra, '".$encypt."') AS hra,
				AES_DECRYPT(s.medical_allowance, '".$encypt."') AS medical_allowance,
				AES_DECRYPT(s.conveyance_allowance, '".$encypt."') AS conveyance_allowance,
				AES_DECRYPT(s.special_allowance, '".$encypt."') AS special_allowance,                                    
				AES_DECRYPT(s.earned_basic, '".$encypt."') AS earned_basic,
				AES_DECRYPT(s.earned_hra, '".$encypt."') AS earned_hra,
				AES_DECRYPT(s.earned_medical_allowance, '".$encypt."') AS earned_medical_allowance,
				AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."') AS earned_conveyance_allowance,
				AES_DECRYPT(s.earned_special_allowance, '".$encypt."') AS earned_special_allowance,                                             
				AES_DECRYPT(s.arrear, '".$encypt."') AS arrear,
				AES_DECRYPT(s.arrear_basic, '".$encypt."') AS arrear_basic,
				AES_DECRYPT(s.arrear_hra, '".$encypt."') AS arrear_hra,                                        
				AES_DECRYPT(s.arrear_conveyance_allowance, '".$encypt."') AS arrear_conveyance_allowance,
				AES_DECRYPT(s.arrear_special_allowance, '".$encypt."') AS arrear_special_allowance,                                             
				AES_DECRYPT(s.performance_incentive, '".$encypt."') AS performance_incentive,
				AES_DECRYPT(s.attendance_incentive, '".$encypt."') AS attendance_incentive,
				AES_DECRYPT(s.earned_city_allowance, '".$encypt."') AS earned_city_allowance,
				AES_DECRYPT(s.earned_food_allowance, '".$encypt."') AS earned_food_allowance,
				AES_DECRYPT(s.referal_bonus, '".$encypt."') AS referal_bonus,
				AES_DECRYPT(s.leave_encashment, '".$encypt."') AS leave_encashment,
				AES_DECRYPT(s.earned_relocation_allowance, '".$encypt."') AS earned_relocation_allowance,
				AES_DECRYPT(s.earned_personal_allowance, '".$encypt."') AS earned_personal_allowance,
				AES_DECRYPT(s.child_edu_allowance, '".$encypt."') AS child_edu_allowance,    
				AES_DECRYPT(s.pf, '".$encypt."') AS pf,
				AES_DECRYPT(s.earned_pf, '".$encypt."') AS earned_pf,                                        
				AES_DECRYPT(s.employer_pf, '".$encypt."') AS employer_pf,
				AES_DECRYPT(s.earned_employer_pf, '".$encypt."') AS earned_employer_pf,
				AES_DECRYPT(s.pt, '".$encypt."') AS pt,                                     
				AES_DECRYPT(s.esi, '".$encypt."') AS esi,
				AES_DECRYPT(s.earned_esi, '".$encypt."') AS earned_esi,
				AES_DECRYPT(s.employer_esi, '".$encypt."') AS employer_esi,
				AES_DECRYPT(s.earned_employer_esi, '".$encypt."') AS earned_employer_esi,
				AES_DECRYPT(s.advance, '".$encypt."') AS advance,
				AES_DECRYPT(s.loan, '".$encypt."') AS loan,
				AES_DECRYPT(s.recovery, '".$encypt."') AS recovery,
				AES_DECRYPT(s.other_deduction, '".$encypt."') AS other_deduction,
				AES_DECRYPT(s.income_tax, '".$encypt."') AS income_tax,
				AES_DECRYPT(s.donation, '".$encypt."') AS donations,
				AES_DECRYPT(s.earned_gross, '".$encypt."') AS earned_gross,
				AES_DECRYPT(s.total_deduction, '".$encypt."') AS total_deduction,
				AES_DECRYPT(s.net_salary, '".$encypt."') AS net_salary,        
				AES_DECRYPT(itd.child_edu_allow, '".$encypt."') AS child_edu_allow, 
				AES_DECRYPT(itd.tot_basic, '".$encypt."') AS tot_basic,
				AES_DECRYPT(itd.actualearnedhra, '".$encypt."') AS actualearnedhra, 
				AES_DECRYPT(itd.rentpaidexcess10perofsalary, '".$encypt."') AS rentpaidexcess10perofsalary,
				AES_DECRYPT(itd.basic50per, '".$encypt."') AS basic50per,
				AES_DECRYPT(itd.tot_hra, '".$encypt."') AS tot_hra,
				AES_DECRYPT(itd.tot_conv, '".$encypt."') AS tot_conv,
				AES_DECRYPT(itd.tot_medical, '".$encypt."') AS tot_medical,
				AES_DECRYPT(itd.tot_special, '".$encypt."') AS tot_special,
				AES_DECRYPT(itd.tot_gross_salary, '".$encypt."') AS tot_gross_salary,
				AES_DECRYPT(itd.tot_pt, '".$encypt."') AS tot_pt,
				AES_DECRYPT(itd.tot_pf, '".$encypt."') AS tot_pf,
				AES_DECRYPT(itd.deduction80C, '".$encypt."') AS deduction80C,
				AES_DECRYPT(itd.eligible80C, '".$encypt."') AS eligible80C,
				AES_DECRYPT(itd.deduction80Dselfsfamily, '".$encypt."') AS deduction80Dselfsfamily,
				AES_DECRYPT(itd.eligible80Dselfsfamily, '".$encypt."') AS eligible80Dselfsfamily,
				AES_DECRYPT(itd.deduction80Dparents, '".$encypt."') AS deduction80Dparents,
				AES_DECRYPT(itd.eligible80Dparents, '".$encypt."') AS eligible80Dparents,
				AES_DECRYPT(itd.deduction80Ehousingloaninterest, '".$encypt."') AS deduction80Ehousingloaninterest,
				AES_DECRYPT(itd.eligible80Ehousingloaninterest, '".$encypt."') AS eligible80Ehousingloaninterest,
				AES_DECRYPT(itd.deduction80DDnormaldisability, '".$encypt."') AS deduction80DDnormaldisability,
				AES_DECRYPT(itd.eligible80DDnormaldisability, '".$encypt."') AS eligible80DDnormaldisability,
				AES_DECRYPT(itd.deduction80DDseveredisability, '".$encypt."') AS deduction80DDseveredisability,
				AES_DECRYPT(itd.eligible80DDseveredisability, '".$encypt."') AS eligible80DDseveredisability,
				AES_DECRYPT(itd.deduction80Unormaldisability, '".$encypt."') AS deduction80Unormaldisability,
				AES_DECRYPT(itd.eligible80Unormaldisability, '".$encypt."') AS eligible80Unormaldisability,
				AES_DECRYPT(itd.deduction80Useveredisability, '".$encypt."') AS deduction80Useveredisability,
				AES_DECRYPT(itd.eligible80Useveredisability, '".$encypt."') AS eligible80Useveredisability,
				AES_DECRYPT(itd.deduction80DDBspecifieddiseases, '".$encypt."') AS deduction80DDBspecifieddiseases,
				AES_DECRYPT(itd.eligible80DDBspecifieddiseases, '".$encypt."') AS eligible80DDBspecifieddiseases,
				AES_DECRYPT(itd.deduction80TTAsavingsaccountinterest, '".$encypt."') AS deduction80TTAsavingsaccountinterest,
				AES_DECRYPT(itd.eligible80TTAsavingsaccountinterest, '".$encypt."') AS eligible80TTAsavingsaccountinterest,
				AES_DECRYPT(itd.tot_taxablesalary, '".$encypt."') AS tot_taxablesalary,
				AES_DECRYPT(itd.taxable, '".$encypt."') AS taxable, 
				AES_DECRYPT(itd.exemptchildeduallow, '".$encypt."') AS exemptchildeduallow,
				AES_DECRYPT(itd.exemptConv, '".$encypt."') AS exemptConv, 
				AES_DECRYPT(itd.exemptMedical, '".$encypt."') AS exemptMedical,    
				AES_DECRYPT(itd.exempt, '".$encypt."') AS exempt,
				AES_DECRYPT(itd.otherincome, '".$encypt."') AS otherincome,
				AES_DECRYPT(itd.donation50per, '".$encypt."') AS donation50per,
				AES_DECRYPT(itd.taxablesalary10per, '".$encypt."') AS taxablesalary10per,
				AES_DECRYPT(itd.actualdonation, '".$encypt."') AS actualdonation,
				AES_DECRYPT(itd.taxableincome, '".$encypt."') AS taxableincome,
				AES_DECRYPT(itd.taxontotalincome, '".$encypt."') AS taxontotalincome,
				AES_DECRYPT(itd.educationcess, '".$encypt."') AS educationcess,
				AES_DECRYPT(itd.rounduptax, '".$encypt."') AS rounduptax,
				AES_DECRYPT(itd.totpaidtax, '".$encypt."') AS totpaidtax, 
				AES_DECRYPT(itd.income_tax, '".$encypt."') AS income_taxx
		FROM `internal_user` AS i 
			LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id
			LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
			LEFT JOIN `income_tax_details` AS itd ON itd.login_id = i.login_id AND itd.tax_year = s.salary_year AND itd.tax_month = s.salary_month
			LEFT JOIN `income_tax` AS it ON it.login_id = i.login_id AND it.fyear = '".$financialyear."' 
			LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
			LEFT JOIN `department` AS d ON d.dept_id = i.department            
			LEFT JOIN `allowance_deduction` AS ad ON ad.login_id = i.login_id AND ad.lyear = s.salary_year AND ad.lmonth = s.salary_month            
			LEFT JOIN bank_master AS b ON b.bank_id = si.bank
			WHERE i.login_id = '$loginID'  AND s.salary_year = '".$year."' AND s.salary_month = '".$month."'";

		//if($rowAllEmp['login_id']==10024){echo $empSql; exit;}

		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();


		if($month >= 4)
		{
			$sfyear="01/04/".$year; 
			$efyear="31/03/".($year+1); 
		}
		else
		{
			$efyear="31/03/".$year;  
			$sfyear="01/04/".($year-1);                   
		}   
		//
		$count= 0;
		if(count($empInfo)>0){
			$count= $empInfo[0]['net_salary'];
		}
		if($count>0)
		{ 
			// echo $empSql; 
			$content ='<!DOCTYPE html><html>
			<head><base href="http://111.93.162.167/">  
			<style>
			@page { sheet-size: A4-L; }
			body{
				margin:0;
				padding:0;
				font-family:Arial, Helvetica, sans-serif;
				font-size:12px;
			}
			#page{
				width:910px;
				display:block;
				margin: 0 auto;	
			}
			#content{
				display:block;
				clear:both;
				padding:10px;
			}
			h2{
				text-align:center;
				font-size: 15px;
				border-bottom: 1px solid #ccc;
				line-height: 25px;
			}
			.empheaderhalf{
				display:block;
				float:left;
				width:100%;
				margin-bottom: 20px;
			}
			#footer{	
				height: 15px;
				color: #000;
				padding:5px;
				clear:both;
				font-size:10px;	
			}
			#statement{
				clear:both;
				margin:10px 0 10px 0;
			}
			.rowhead{	
				height: 20px;
				font-weight: bold;
				border-collapse:collapse;
				text-align:center;
			}
			.row{
				line-height:20px;
			}
			.earning .col1{
				width:158px;
				display:block;
				float:left;
				border-left:1px solid #666;
				border-right:1px solid #666;
				text-indent:10px;
				min-height:235px;	
			}
			.earning .col2{
				width:94px;
				display:block;
				float:left;
				border-right:1px solid #666;
				text-align:right;
				padding-right:5px;	
				min-height:235px;	
			}
			.earning .col3{
				width:94px;
				border-right:1px solid #666;
				text-align:right;
				padding-right:5px;	
				display:block;
				float:left;
				min-height:235px;	
			}
			.earning .col4{
				width:94px;
				border-right:1px solid #666;
				text-align:right;
				padding-right:5px;	
				display:block;
				float:left;
				min-height:235px;	
			}
			.earning .col5{
				width:94px;
				border-right:1px solid #666;
				text-align:right;
				padding-right:5px;	
				display:block;
				float:left;
				min-height:235px;	
			}
			.earning .col6{
				width:190px;
				border-right:1px solid #666;
				text-align:left;
				text-indent:5px;
				display:block;
				float:left;
				min-height:235px;	
			}
			.earning .col7{
				width:130px;
				border-right:1px solid #666;
				text-align:right;
				padding-right:5px;	
				display:block;
				float:left;
				min-height:235px;	
			}
			.net{
				margin:5px 0 0 0;
				display:block;
				clear:both;
				padding:5px 0 5px 0;
			}
			.net h4{
				font-style:italic;
			}
			#gross{
					clear:both;       
					height:30px;
					border: 1px solid #999;        
					line-height:30px;
					width: 100%;
			}
			#gross .grosspay{
				width:150px;
				display:block;
				float:left;
				text-indent:5px;
				font-weight:bold;
					background-color:#ccc;
				text-transform:uppercase;
			}
			#gross .rate{
				width:100px;
				display:block;
					background-color:#ccc;
				float:left;
					font-weight:bold;
					text-align: right;
					padding-right: 5px;
			}
			#gross .monthly{
				width:95px;
				display:block;
				float:left;
				font-weight:bold;
					background-color:#ccc;
				text-align:right;
				padding-right:5px;	
			}
			#gross .arrear{
				width:100px;
				display:block;
					background-color:#ccc;
				float:left;
				text-align:right;	
				font-weight:bold;
					padding-right: 5px;
			}
			#gross .total{
				width:95px;
				display:block;
					background-color:#ccc;
				float:left;
				text-align:right;
				font-weight:bold;
				padding-right:5px;	
			}
			#gross .grossdeduction{
				width:210px;
				display:block;
					background-color:#ccc;
				float:left;
				text-indent:15px;
				font-weight:bold;
				text-transform:uppercase;			
			}
			#gross .amount{
				width:112px;
				display:block;
				float:left;
					background-color:#ccc;
				text-align:right;
				font-weight:bold;
					padding-right: 5px;
			}
			#net_sal{
				clear:both;
					background-color:#ccc;
					height:25px;
					border: 1px solid #999;
					line-height:25px;
					text-indent: 10px;
					font-style:italic;	
			}
			.earnhead{
				width:258px;
				background-color:#CCCCCC;
				display:block;
				float:left;
				border-left:1px solid #999;	
				border-top:1px solid #999;
				border-bottom:1px solid #999;	
				height:59px;
			}
			.earnheada{
				width:158px;
				display:block;
				float:left;
				border-right:1px solid #999;	
				height:59px;
				line-height:60px;
			}
			.earnheadb{
				width:98px;
				display:block;
				float:left;
				height:59px;
				line-height:60px;	
			}
			.earnhead2{
				width:299px;
				background-color:#CCCCCC;
				display:block;
				float:left;
				height:60px;
				border-right:1px solid #999;
				border-left:1px solid #999;	
				border-top:1px solid #999;	
			}
			.earnhead2r1{
				width:310px;
				background-color:#CCCCCC;
				display:block;
				float:left;
				line-height:30px;
				border-bottom:1px solid #999;
			}
			.earnhead2r2{
				width:310px;
				background-color:#CCCCCC;
				display:block;
				float:left;
				line-height:30px;
				border-bottom:1px solid #999;	
			}
			.earnhead2r2a{
				width:99px;
				display:block;
				float:left;
				line-height:28px;
				border-right:1px solid #999;
				height:28px;	
			}
			.earnhead2r2b{
				width:99px;
				display:block;
				float:left;
				line-height:28px;
				border-right:1px solid #999;	
				height:28px;	
			}
			.earnhead2r2c{
				width:99px;
				display:block;
				float:left;
				line-height:28px;
				height:28px;	
			}
			.deducthead{
				width:325px;
				background-color:#ccc;
				display:block;
				float:left;
				height:px;
				border-right:1px solid #999;
				border-top:1px solid #999;	
				border-bottom:1px solid #999;				
			}
			.deductheada{
				width:190px;
				display:block;
				float:left;
				height:59px;
				border-right:1px solid #999;
				line-height:60px;
			}
			.deductheadb{
				width:105px;
				display:block;
				float:left;
				height:59px;
				line-height:60px;	
			}
			#taxworksheet{
				width:910px;
				background-color:#CCCCCC;
				clear:both;
				margin:1px;
			}
			#taxworksheetcol1{
				width:280px;
				display:block;
				float:left;
				min-height:300px;
			}
			.taxworksheetcol1a{
				width:79px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;	
				padding:0 4px;
			}
			.taxworksheetcol1ad{
				width:280px;
				display:block;
				float:left;
				border:1px solid #000;
				padding:0 4px;
				text-align:center;
				border-top:none;
				height:45px;
			}
			.taxworksheetcol1b{
				width:58px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;		
			}
			.taxworksheetcol1c{
				width:48px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;	
			}
			.taxworksheetcol1d{
				width:58px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;
				border-right:1px solid #000;				
				padding:0 4px;	
			}
			.taxworksheetcol1ar{
				width:79px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;	
			}
			.taxworksheetcol1br{
				width:58px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;		
				text-align:right;
				padding:0 4px;	
			}
			.taxworksheetcol1cr{
				width:48px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;		
				text-align:right;	
				padding:0 4px;	
			}
			.taxworksheetcol1dr{
				width:58px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;
				border-right:1px solid #000;	
				text-align:right;	
				padding:0 4px;	
			}
			.taxworksheetcol1a3r{
				width:192px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;	
			}
			.taxworksheetcol1d3r{
				width:68px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;
				border-right:1px solid #000;	
				text-align:right;	
				padding:0 4px;	
			}
			#taxworksheetcol2{
				width:333px;
				display:block;
				float:left;
				min-height:300px;
			}
			.taxworksheetcol2a{
				width:162px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				padding:0 4px;
			}
			.taxworksheetcolfull{
				width:322px;
				display:block;
				float:left;
				border:1px solid #000;
				padding:0 4px;
				text-align:center;
				border-left:none;
				border-bottom:none;	
			}
			.taxworksheetcol2b{
				width:71px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;		
			}
			.taxworksheetcol2c{
				width:71px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-top:1px solid #000;
				border-left:1px solid #000;
				border-right:1px solid #000;				
				padding:0 4px;	
			}
			.taxworksheetcol2ar{
				width:162px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				padding:0 4px;
			}
			.taxworksheetcol2br{
				width:71px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;		
				padding:0 4px;
				text-align:right;		
			}
			.taxworksheetcol2cr{
				width:71px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;
				border-right:1px solid #000;				
				padding:0 4px;	
				text-align:right;	
			}
			.taxworksheetcolabc{
				width:322px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-right:1px solid #000;	
				padding:0 4px;
				font-weight:bold;
			}
			.taxworksheetcolab{
				width:242px;
				display:block;
				float:left;
				border-bottom:1px solid #000;	
				border-right:1px solid #000;	
				padding:0 4px;
			}
			.taxworksheetcolac{
				width:71px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-right:1px solid #000;	
				padding:0 4px;
			}
			#taxworksheetcol3{
				width:275px;
				display:block;
				float:left;
				min-height:300px;
			}
			.taxworksheetcol3a{
				width:190px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				padding:0 4px;
			}
			.taxworksheetcol3ab{
				width:267px;
				display:block;
				float:left;
				padding:0 4px;
				text-align:center;
				border:1px solid #000;
				border-left:none;
			}
			.taxworksheetcol3b{
				width:65px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-left:1px solid #000;	
				border-right:1px solid #000;		
				padding:0 4px;
				text-align:right;
			}
			.taxworksheetcol3abc{
				width:275px;
				display:block;
				float:left;
				border-bottom:1px solid #000;
				border-right:1px solid #000;		
				padding:0 4px;;
			}
			.taxworksheetcol3abc2{
				width:275px;
				display:block;
				float:left;		
				border-right:1px solid #000;		
				padding:0 4px;;
			}
			#personalnote{
				border:1px solid #000 !important;
				min-height: 20px;
				clear:both;
				padding:0 10px;
				border-left:none;
				border-right:none;
				border-bottom:none;	
			}

			#intax{
				display:block;
				text-align:center;
				font-weight:bold;
				padding:10px 0 5px 0;
			}
			#itax{
				clear:both;
				margin:8px;
				border:1px solid #000;
			}
			.company{
				font-weight:bold;
				font-size:16px;
			}
			.logo{
				display:block;
				float:left;
				margin:10px 0;
				width:650px;
				min-height: 100px;
			}
			.address{
				display:block;		
				font-size:12px;
				margin:15px 20px 0 0;
			}
			.row2{
				line-height:20px;
			}
			.head{
				display:block;
				float:left;
				font-weight:bold;
				width:200px;
			}
			.headleave{
				display:block;
				float:left;
				font-weight:bold;
				width:290px;
			}
			</style> </head> <body>';

			$content .='<div id="page">
			<div id="header">
			  <div class="logo"><img src="'.base_url().'assets/images/logo.gif" alt="" border="0"> </div>
			  <div class="address">
			  <span class="company">POLOSOFT TECHNOLOGIES Pvt. Ltd.</span><br />
			DCB :- 102, 1st Floor, DLF Cyber City,<br />
			Infocity, Chandaka Industrial Estate,<br />
			Patia, Bhubaneswar, Odisha - 751024<br />
			Sales: +91 9776993750<br />
			USA : 503-928-5984<br />
			Email:- info@polosoftech.com
			  </div>
			  </div>
			<div id="content">
			<h2>Pay Slip for the month of '.$mon.' '.$empInfo[0]["salary_year"].'</h2>
			<div id="empheader">
			<div class="empheaderhalf">
			<div class="head">
				<div class="row">Name:</div>
				 <div class="row">Designation:</div>
				<div class="row">Department:</div> 
				<div class="row">Bank A/C No:</div>
				<div class="row">PF A/C No:</div>
				<div class="row">ESI A/C No:</div>
				<div class="row">UAN No:</div>    
			</div>
			<div class="head">
			   <div class="row">'.$empInfo[0]['full_name'].'&nbsp;</div>
				<div class="row">'.$empInfo[0]['desg_name'].'&nbsp;</div>
				<div class="row">'.$empInfo[0]['dept_name'].'&nbsp;</div>    
				<div class="row">'.$empInfo[0]['bank_no'].'&nbsp;</div>   
				<div class="row">'.strtoupper($empInfo[0]["pf_no"]).'&nbsp;</div>
				<div class="row">'.strtoupper($empInfo[0]["mediclaim_no"]).'&nbsp;</div>
				<div class="row">'.strtoupper($empInfo[0]["uan_no"]).'&nbsp;</div>    
			</div>
			<div class="headleave">    
			   <div class="row">Employee ID:</div>
			   <div class="row">Date of Joining:</div>    
				<div class="row">Working Days:</div>
				<div class="row">Days Worked:</div>
				<div class="row">Days Arrear:</div>
				<div class="row">Net Available Leaves at the end of this month:</div>    
			</div>
			<div class="head">   
				<div class="row">'.$empInfo[0]['loginhandle'].'&nbsp;</div>
				<div class="row">'.date('jS F Y',strtotime($empInfo[0]["join_date"])).'&nbsp;</div>    
				<div class="row">'.$empInfo[0]['working_days'].'&nbsp;</div>
				<div class="row">'.$empInfo[0]['paid_days'].'&nbsp;</div>
				<div class="row">'.$empInfo[0]['arrear_days'].'&nbsp;</div>
				<div class="row">PL&nbsp;-&nbsp;'.$avlPL.'&nbsp;&nbsp;&nbsp;SL&nbsp;-&nbsp;'.$avlSL.'</div>  
			</div>
			</div>

			</div>
			<div id="statement">
			<div class="rowhead">

			<div class="earnhead">
			<div class="earnheada">Description</div>
			<div class="earnheadb">Monthly rate</div>
			</div>

			<div class="earnhead2">
			<div class="earnhead2r1">Earning</div>
			<div class="earnhead2r2">
			<div class="earnhead2r2a">Monthly</div>
			<div class="earnhead2r2b">Arrear</div>
			<div class="earnhead2r2c">Total</div>
			</div>
			</div>

			<div class="deducthead">
			<div class="deductheada">Deduction</div>
			<div class="deductheadb">Amount</div>
			</div>

			</div>
			<div class="earning">
			<div class="col1">
			<div class="row">Basic</div>
			<div class="row">HRA</div>
			<div class="row">Conv. Allowance</div>
			<div class="row">Special Allowance</div>';
			 if($empInfo[0]['earned_medical_allowance']>0){ $content .='<div class="row">Medical Allowance</div>'; } 
			 if($empInfo[0]['earned_food_allowance']>0){ $content .='<div class="row">Food Allowance</div>'; } 
			 if($empInfo[0]['referal_bonus']>0){ $content .='<div class="row">Buddy Referal Bonus</div>'; } 
			 if($empInfo[0]['earned_relocation_allowance']>0){ $content .='<div class="row">Relocation Allowance</div>'; } 
			 if($empInfo[0]['earned_city_allowance']>0){ $content .='<div class="row">City Allowance</div>'; } 
			 if($empInfo[0]['earned_personal_allowance']>0){ $content .='<div class="row">Personal Allowance</div>'; } 
			 if($empInfo[0]['child_edu_allowance']>0){ $content .='<div class="row">ChildEdu. Allowance</div>'; } 
			$content .='</div>

			<div class="col2">
			<div class="row">'.number_format((float)($empInfo[0]['basic']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['hra']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['conveyance_allowance']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['special_allowance']), 2, '.', '').'</div>';
			 if($empInfo[0]['medical_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['medical_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['food_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['food_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['referal_bonus']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['referal_bonus']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['relocation_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['relocation_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['city_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['city_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['personal_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['personal_allowance']), 2, '.', '').'</div>'; } 
			  if($empInfo[0]['childreneducationalallowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['childreneducationalallowance']), 2, '.', '').'</div>'; } 
			$content .='</div>

			<div class="col3">
			<div class="row">'.number_format((float)($empInfo[0]['earned_basic']-$empInfo[0]['arrear_basic']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_hra']-$empInfo[0]['arrear_hra']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_conveyance_allowance']-$empInfo[0]['arrear_conveyance_allowance']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_special_allowance']-$empInfo[0]['arrear_special_allowance']), 2, '.', '').'</div>';
			 if($empInfo[0]['earned_medical_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_medical_allowance']), 2, '.', '').'</div>'; }         
			 if($empInfo[0]['earned_food_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_food_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['referal_bonus']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['referal_bonus']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_relocation_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_relocation_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_city_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_city_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_personal_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_personal_allowance']), 2, '.', '').'</div>'; } 
			  if($empInfo[0]['child_edu_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['child_edu_allowance']), 2, '.', '').'</div>'; } 
			$content .='</div>

			<div class="col4">';
			 if($empInfo[0]['arrear_basic']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['arrear_basic']), 2, '.', '').'</div>'; }else{ $content .='<div class="row">0.00</div>'; } 
			 if($empInfo[0]['arrear_hra']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['arrear_hra']), 2, '.', '').'</div>'; } else{ $content .='<div class="row">0.00</div>'; }
			 if($empInfo[0]['arrear_conveyance_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['arrear_conveyance_allowance']), 2, '.', '').'</div>'; } else{ $content .='<div class="row">0.00</div>'; }
			 if($empInfo[0]['arrear_special_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['arrear_special_allowance']), 2, '.', '').'</div>'; } else{ $content .='<div class="row">0.00</div>'; }
			$content .='</div>

			<div class="col5">
			<div class="row">'.number_format((float)($empInfo[0]['earned_basic']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_hra']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_conveyance_allowance']), 2, '.', '').'</div>
			<div class="row">'.number_format((float)($empInfo[0]['earned_special_allowance']), 2, '.', '').'</div>';
			 if($empInfo[0]['earned_medical_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_medical_allowance']), 2, '.', '').'</div>'; }
			 if($empInfo[0]['earned_food_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_food_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['referal_bonus']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['referal_bonus']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_relocation_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_relocation_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_city_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_city_allowance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_personal_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_personal_allowance']), 2, '.', '').'</div>'; } 
			  if($empInfo[0]['child_edu_allowance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['child_edu_allowance']), 2, '.', '').'</div>'; } 
			$content .='</div>

			<div class="col6">';
			 if($empInfo[0]['earned_esi']>0){ $content .='<div class="row">ESI</div>'; } 
			 if($empInfo[0]['earned_pf']>0){ $content .='<div class="row">PF</div>'; } 
			 if($empInfo[0]['pt']>0){ $content .='<div class="row">PT</div>'; } 
			 if($empInfo[0]['income_tax']>0){ $content .='<div class="row">TDS</div>'; } 
			 if($empInfo[0]['recovery']>0){ $content .='<div class="row">Recovery</div>'; } 
			 if($empInfo[0]['advance']>0){ $content .='<div class="row">Advance</div>'; } 
			 if($empInfo[0]['loan']>0){ $content .='<div class="row">Loan</div>'; } 
			 if($empInfo[0]['donation']>0){ $content .='<div class="row">Donation</div>'; } 
			$content .='</div>

			<div class="col7">';
			 if($empInfo[0]['earned_esi']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_esi']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['earned_pf']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['earned_pf']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['pt']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['pt']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['income_tax']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['income_tax']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['recovery']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['recovery']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['advance']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['advance']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['loan']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['loan']), 2, '.', '').'</div>'; } 
			 if($empInfo[0]['donation']>0){ $content .='<div class="row">'.number_format((float)($empInfo[0]['donation']), 2, '.', '').'</div>'; } 
			$content .='</div>

			</div>

			</div>
			<div id="gross">
			<div class="grosspay">gross pay</div><div class="rate">'.number_format((float)($empInfo[0]['gross']), 2, '.', '').'</div><div class="monthly">'.number_format((float)($empInfo[0]['earned_gross']), 2, '.', '').'</div><div class="arrear">&nbsp;</div><div class="total">'.number_format((float)($empInfo[0]['earned_gross']), 2, '.', '').'</div><div class="grossdeduction">Gross Deduction</div><div class="amount">'.number_format((float)($empInfo[0]['total_deduction']), 2, '.', '').'</div>
			</div>

			<div id="net_sala">
			<div id="net_sal">Net Salary: Rs. '.number_format((float)($empInfo[0]['net_salary']), 2, '.', '').' (Rupees '.ucfirst($this->convert_number_to_words(round($empInfo[0]['net_salary']))).')</div>
			</div>
			</div><div style="clear:both;">&nbsp;</div>';
			if($empInfo[0]['income_tax'] > 0){
			$content .='<div id="itaxx">
			<div id="itax">
			<div id="intax">Income Tax Worksheet for the period '.$sfyear.' - '.$efyear.'</div>
			<div id="taxworksheet">
			<div id="taxworksheetcol1">
			<div class="row">
			<div class="taxworksheetcol1a">Description</div>
			<div class="taxworksheetcol1b">Gross</div>
			<div class="taxworksheetcol1c">Exempt</div>
			<div class="taxworksheetcol1d">Taxable</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1ar">Basic</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['tot_basic']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">0.00</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['tot_basic']), 0, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1ar">HRA</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['actualearnedhra']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">'.number_format((int)($empInfo[0]['tot_hra']), 0, '.', '').'</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['actualearnedhra']-$empInfo[0]['tot_hra']), 0, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1ar">Medical Exp.</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['tot_medical']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">'.number_format((int)($empInfo[0]['exemptMedical']), 0, '.', '').'</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['tot_medical']-$empInfo[0]['exemptMedical']), 0, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1ar">Conv Allow.</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['tot_conv']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">'.number_format((int)($empInfo[0]['exemptConv']), 0, '.', '').'</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['tot_conv']-$empInfo[0]['exemptConv']), 0, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1ar">Special Allow.</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['tot_special']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">0.00</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['tot_special']), 0, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1ar">ChEdu. Allow.</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['child_edu_allow']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">'.number_format((int)($empInfo[0]['exemptchildeduallow']), 0, '.', '').'</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['child_edu_allow']-$empInfo[0]['exemptchildeduallow']), 0, '.', '').'</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol1ar">Other Income</div>
			<div class="taxworksheetcol1br">'.number_format((float)($empInfo[0]['otherincome']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">0.00</div>
			<div class="taxworksheetcol1dr">'.number_format((float)($empInfo[0]['otherincome']), 0, '.', '').'</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol1ar">Total</div>
			<div class="taxworksheetcol1br">'.number_format((int)($empInfo[0]['tot_gross_salary']), 0, '.', '').'</div>
			<div class="taxworksheetcol1cr">'.number_format((int)($empInfo[0]['exempt']), 0, '.', '').'</div>
			<div class="taxworksheetcol1dr">'.number_format((int)($empInfo[0]['taxable']), 0, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1ad">
			<h3>HRA Calculation</h3>
			</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol1a3r">Actual Rent Paid by Employee</div>
			<div class="taxworksheetcol1d3r">'.number_format((int)($empInfo[0]['rentpaid']), 0, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1a3r">From</div>
			<div class="taxworksheetcol1d3r">'.$sfyear.'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1a3r">To</div>
			<div class="taxworksheetcol1d3r">'.$efyear.'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1a3r">1. Actual HRA</div>
			<div class="taxworksheetcol1d3r">'.$empInfo[0]['actualearnedhra'].'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1a3r">2. 40% or 50% of Basic</div>
			<div class="taxworksheetcol1d3r">'.$empInfo[0]['basic50per'].'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol1a3r">3. Rent > 10% Basic</div>
			<div class="taxworksheetcol1d3r">'.$empInfo[0]['rentpaidexcess10perofsalary'].'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1a3r">Least of Above is exempt</div>
			<div class="taxworksheetcol1d3r">'.$empInfo[0]['tot_hra'].'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol1a3r">Taxable HRA</div>
			<div class="taxworksheetcol1d3r">'.number_format((float)($empInfo[0]['actualearnedhra']-$empInfo[0]['tot_hra']), 2, '.', '').'</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol1a3r">&nbsp;</div>
			<div class="taxworksheetcol1d3r">&nbsp;</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol1a3r">&nbsp;</div>
			<div class="taxworksheetcol1d3r">&nbsp;</div>
			</div>
			</div>

			<div id="taxworksheetcol2">
			<div class="taxworksheetcolfull">
			<h3>Deduction Under Chapter VI-A</h3>
			</div>



			<div class="row">
			<div class="taxworksheetcol2a">Description</div>
			<div class="taxworksheetcol2b">Declared</div>
			<div class="taxworksheetcol2c">Eligible</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">A. Sec 80C, 80CCC, 80CCD</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol2ar">a. U/S 80 C (PF)</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['deduction80C']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['eligible80C']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">b. U/S 80CCC</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">c. U/S 80CCD</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">B. Oth Sec.(e.g. 80E/G etc.)</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol2ar">(1) Section 80CCF</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">(2) Section 80D</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['deduction80Dselfsfamily']+$empInfo[0]['deduction80Dparents']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['eligible80Dselfsfamily']+$empInfo[0]['eligible80Dparents']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">(3) Section 80DD</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['deduction80DDnormaldisability']+$empInfo[0]['deduction80DDseveredisability']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['eligible80DDnormaldisability']+$empInfo[0]['eligible80DDseveredisability']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">(4) Section 80DDB</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['specifieddiseases']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['specifieddiseases']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">(5) Section 80E</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['highereducation']+$empInfo[0]['deduction80Ehousingloaninterest']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['highereducation']+$empInfo[0]['eligible80Ehousingloaninterest']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">(6) Section 80G</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['donation']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol2ar">10% of Taxable Salary</div>
			<div class="taxworksheetcol2br">'.number_format((float)($empInfo[0]['taxablesalary10per']), 2, '.', '').'</div>
			<div class="taxworksheetcol2cr">'.number_format((float)($empInfo[0]['actualdonation']), 2, '.', '').'</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol2ar">&nbsp;</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">&nbsp;</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>
			<div class="row">
			<div class="taxworksheetcol2ar">&nbsp;</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">&nbsp;</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol2ar">&nbsp;</div>
			<div class="taxworksheetcol2br">&nbsp;</div>
			<div class="taxworksheetcol2cr">&nbsp;</div>
			</div>
			</div>

			<div id="taxworksheetcol3">
			<div class="taxworksheetcol3ab">
			<h3>Tax Calculation</h3>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Professionla Tax (PT)</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['tot_pt']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Under Chapter VI-A</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['actualdonation']+$empInfo[0]['tot_taxablesalary']), 2, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol3a">Any other income</div>
			<div class="taxworksheetcol3b">0.00</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Agricultural Income</div>
			<div class="taxworksheetcol3b">0.00</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol3a">Perquisite Value</div>
			<div class="taxworksheetcol3b">0.00</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Taxable Income</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['taxableincome']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Total Tax</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['taxontotalincome']), 2, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol3a">Tax Rebate</div>
			<div class="taxworksheetcol3b">0.00</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Tax Due</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['taxontotalincome']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Educational Cess</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['educationcess']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Net Tax</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['rounduptax']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Tax Deducted Previous Employer</div>
			<div class="taxworksheetcol3b">0.00</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Tax Deducted till date</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['totpaidtax']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Tax to be deducted</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['rounduptax']-$empInfo[0]['totpaidtax']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3a">Tax/Month</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['rounduptax']/12), 2, '.', '').'</div>
			</div>


			<div class="row">
			<div class="taxworksheetcol3a">Tax Deducted for this month</div>
			<div class="taxworksheetcol3b">'.number_format((float)($empInfo[0]['income_tax']), 2, '.', '').'</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3abc">Loan Balances</div>
			</div>

			<div class="row">
			<div class="taxworksheetcol3abc">No Balances</div>
			</div>

			</div>
			</div>
			<div id="personal">
			<div id="personalnote">&nbsp;</div>
			</div>
			</div>
			</div>';
			}
			$content .='<div id="footer">This is a computer generated pay slip, hence no signature is required.</div>

			</div></body>
			</html>';
			$content = trim($content);     
			//if($rowAllEmp['login_id']==10427){echo $content; exit;}
					 
			//$loginhandle =strtolower(str_replace(' ','_',$empInfo[0]["loginhandle"]));
			//$filename=$_SERVER['DOCUMENT_ROOT'].'/upload/salary_slip/'.md5(time()).'.pdf';
			//D: download the PDF file
			//I: serves in-line to the browser
			//S: returns the PDF document as a string
			//F: save as file file_out

			//$mpdf = new mPDF('utf-8',    // mode - default ''
			// 'A3',    // format - A4, for example, default ''
			// 12,     // font size - default 0
			// 'verdana',    // default font family
			// 1,    // margin_left
			// 1,    // margin right
			// 5,     // margin top
			// 5,    // margin bottom
			// 5,     // margin header
			// 5,     // margin footer
			// '');  // L - landscape, P - portrait

			//$mpdf = new mPDF('','A3',9,'',5,5,5,5,'','','P');
			//$mpdf->text_input_as_HTML = true;
			//$mpdf->WriteHTML($content);

			//$mpdf->WriteHTML($content,0);
			//$mpdf->Output($filename,'F'); 
			
			$resSalaryDL = $this->db->query("Select * From `salary_slip_download` WHERE login_id='".$loginID."' AND year='$year' AND month='$month'");
			$numSalarySheet=  count($resSalaryDL->result_array());
			if($numSalarySheet > 0){ 
				$this->db->query("UPDATE `salary_slip_download` SET content='".$content."' WHERE login_id='".$loginID."' AND year='$year' AND month='$month'");
			}
			else{
			   $this->db->query("INSERT INTO `salary_slip_download` SET content='".$content."', login_id='".$loginID."', year='$year', month='$month'");
			}

			//For email section

				//$filePath = $filename; //Full Path
				
				$subject = "Salary Slip For the Month of ".$mon.' '.$empInfo[0]["salary_year"];
				$attachmentNameoriginal = "salary_slip.pdf";
				$to = $rowAllEmp['email'];
			       
				$message = '<p>Dear '.$rowAllEmp["full_name"].', </p><p>Please Find the salary slip for the month of '.$mon.' '.$empInfo[0]["salary_year"].'.</p>';
					 
				// boundary
				$semi_rand = md5(time());
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
				
				/* $headers  = "From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>" . "\r\n"; 
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";	
				$headers  .= "X-Mailer: PHP/". phpversion().">\r\n";
				$headers  .= "X-Originating-IP: ". $_SERVER['SERVER_ADDR'].">\r\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\""; */
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"";
				$headers  = "From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>" . "\r\n"; 
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";	
				$headers .= 'X-Mailer: PHP/' . phpversion();
				 
				// multipart boundary
				//$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
				//$message .= "--{$mime_boundary}\n";
				 
				// preparing attachments
			   /* $file = fopen($filePath,"rb");
			   $data = fread($file,filesize($filePath));
			   fclose($file);
			   $data = chunk_split(base64_encode($data));
			   $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$attachmentNameoriginal\"\n" .
			   "Content-Disposition: attachment;\n" . " filename=\"$attachmentNameoriginal\"\n" .
			   "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			   $message .= "--{$mime_boundary}\n"; */
			   
			   //$mail = mail($to, $subject, $message, $headers);
			   
			    $this->load->library('email');
				$this->email->set_newline("\r\n");
				$this->email->from('hr@polosoftech.com', 'HR Department, Polosoft Technologies');
				$this->email->to($to);
				$this->email->subject($subject);
				//$message = $this->load->view('email/email_view.php',$this->arr_global,TRUE);
				$this->email->message($content);	
				$mail=$this->email->send();
				
			   if($mail) unlink($filename);
				
			}
			
		}
			$successMsg    = 'Salary mailed successfully';
			//$successMsg=TRUE;
			}
			else{
				$successMsg    = 'Please select month & year';
			}
		}
		$this->mViewData['successMsg']    = $successMsg;
		$this->render('hr/mail_salary_slip_view', 'full_width',$this->mViewData);
	}
	
	
	public function payroll_report(){
		$this->mViewData['pageTitle']    = 'Payroll report';
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one(); 
		$this->mViewData['location'] = $this->Hr_model->get_locations(); 
		$this->mViewData['bank'] = $this->Hr_model->bank();
		//payroll logic 
		
		if($this->input->post('exportEmployee') == "Generate")
		{
			//print_r($_POST); exit;
			$encypt = $this->config->item('masterKey');	
			$this->load->library('PHPExcel');			
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			
			if(null !== $this->input->post('loginhandle'))
			{
				array_push($header,"Employee Code");
				$selCols .= ", i.loginhandle";
				$noOfColumnsSelected++;
			}
		 
			if(null !== $this->input->post('full_name'))
			{
				array_push($header, "Name");
				$selCols .= ", i.full_name";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('father_name')){
				array_push($header, "Father's Name");
				$selCols .= ", i.father_name";
				$noOfColumnsSelected++;
			}
			

			array_push($header, "DOB");
			$selCols .= ", i.dob";
			$noOfColumnsSelected++;
			  
			if(null !== $this->input->post('doj'))
			{
				array_push($header,"DOJ");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
				if($this->input->post('dojFrom') != "")
				{
					$cond .= " AND i.join_date >= '" . date("Y-m-d", strtotime($this->input->post('dojFrom'))) . "'";
				}
				if($this->input->post('dojTo') != "")
				{
					$cond .= " AND i.join_date <= '" .date("Y-m-d", strtotime($this->input->post('dojTo'))) . "'";
				}
			}
		 
			if(null !== $this->input->post('dept_name'))
			{
				array_push($header,"Department");
				$selCols .= ", d.dept_name";
				$noOfColumnsSelected++;
				if($this->input->post('hdnDept') != "")
				{
						$dept = implode(",",$this->input->post("hdnDept"));
						$cond .= " AND i.department IN (" . $dept . ")";
				}
			}
			if(null !== $this->input->post('desg_name'))
			{
				array_push($header, "Designation");
				$selCols .= ", u.desg_name";
				$noOfColumnsSelected++;
				if($this->input->post('hdnDesg') != "")
				{
					$cond .= " AND i.designation IN (" . implode(",",$this->input->post("hdnDesg")) . ")"; //here check
				}
			}
			
			if(null !== $this->input->post('location'))
			{
				array_push($header, "Location");
				$selCols .= ", b.branch_name";
				$noOfColumnsSelected++;
				if($this->input->post('hdnLocation') != "")
				{
					$cond .= " AND i.branch IN (" . implode(",",$this->input->post("hdnLocation")) . ")"; //here check
				}
			}
			  
			array_push($header, "Total Days");
			$selCols .= ", sas.working_days";
			$noOfColumnsSelected++;

			array_push($header, "Weekly Off");
			$selCols .= ", sas.weekly_off";
			$noOfColumnsSelected++;

			array_push($header, "Holidays");
			$selCols .= ", sas.holidays";
			$noOfColumnsSelected++;

			array_push($header, "Paid Days");
			$selCols .= ", sas.paid_days";
			$noOfColumnsSelected++;

			array_push($header, "Absent Days");
			$selCols .= ", sas.absent_days";
			$noOfColumnsSelected++;
			
			array_push($header, "Arrear Days");
			$selCols .= ", sas.arrear_days";
			$noOfColumnsSelected++;

			if(null !== $this->input->post('ctc'))
			{
				array_push($header,"CTC");
				$selCols .= ", AES_DECRYPT(sas.ctc, '".$encypt."') AS ctc";
				$noOfColumnsSelected++;
				if($this->input->post('ctcFrom') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.ctc, '".$encypt."') >= " . $this->input->post('ctcFrom');
				}
				if($this->input->post('ctcTo') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.ctc, '".$encypt."') <= " . $this->input->post('ctcTo');
				}
			}
			if(null !== $this->input->post('reimbursement'))
			{
				array_push($header,"Reimbursement");      
				$selCols .= ", AES_DECRYPT(sas.reimbursement, '".$encypt."') AS reimbursement";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('reimbursement') && null !== $this->input->post('ctc'))
			{
				array_push($header, "CTC with Reimbursement"); 
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('gross_salary'))
			{
				array_push($header, "Gross Salary");
				$selCols .= ", AES_DECRYPT(sas.gross, '".$encypt."') AS gross";
				$noOfColumnsSelected++;
				if($this->input->post('gSalFrom') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.gross, '".$encypt."') >= " . $this->input->post('gSalFrom');
				}
				if($this->input->post('gSalTo') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.gross, '".$encypt."') <= " . $this->input->post('gSalTo');
				}
			}
			if(null !== $this->input->post('arrear')){
				array_push($header, "Arrear");
				$selCols .= ", AES_DECRYPT(sas.arrear, '".$encypt."') AS arrear";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('basic'))
			{
				array_push($header,"Basic");     
				$selCols .= ", AES_DECRYPT(sas.basic, '".$encypt."') AS basic";
				$noOfColumnsSelected++;
				if($this->input->post('basicFrom') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.basic, '".$encypt."') >= " . $this->input->post('basicFrom');
				}
				if($this->input->post('basicTo') != "")
				{
					$cond .= " AND AES_DECRYPT(sas.basic, '".$encypt."') <= " . $this->input->post('basicTo');
				}
			}
		  
			if(null !== $this->input->post('hra'))
			{
				array_push($header,"HRA");      
				$selCols .= ", AES_DECRYPT(sas.hra, '".$encypt."') AS hra";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('conv'))
			{
				array_push($header,  "Conv.Allow");     
				$selCols .= ", AES_DECRYPT(sas.conveyance_allowance, '".$encypt."') AS conveyance_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('medical'))
			{
				array_push($header, "Medical Allowance");    
				$selCols .= ", AES_DECRYPT(sas.medical_allowance, '".$encypt."') AS medical_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('special_allownce'))
			{
				array_push($header,"Special Allowance");     
				$selCols .= ", AES_DECRYPT(sas.special_allowance, '".$encypt."') AS special_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('basic'))
			{
				array_push($header, "Earned Basic");
				$selCols .= ", AES_DECRYPT(sas.earned_basic, '".$encypt."') AS earned_basic";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('hra'))
			{
				array_push($header,"Earned HRA");
				$selCols .= ", AES_DECRYPT(sas.earned_hra, '".$encypt."') AS earned_hra";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('conv'))
			{
				array_push($header,  "Earned Conv. Allow");
				$selCols .= ", AES_DECRYPT(sas.earned_conveyance_allowance, '".$encypt."') AS earned_conveyance_allowance";
				$noOfColumnsSelected++;
			} 
			if(null !== $this->input->post('medical'))
			{
				array_push($header, "Earned Med. Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_medical_allowance, '".$encypt."') AS earned_medical_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('special_allownce'))
			{
				array_push($header,"Earned Spe. Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_special_allowance, '".$encypt."') AS earned_special_allowance";
				$noOfColumnsSelected++;    
			}     
			 
			if(null !== $this->input->post('relocation_allowance'))
			{     
				array_push($header, "Relocation Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_relocation_allowance, '".$encypt."') AS earned_relocation_allowance";
				$noOfColumnsSelected++;    
			}
			if(null !== $this->input->post('food_allowance'))
			{     
				array_push($header, "Food Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_food_allowance, '".$encypt."') AS earned_food_allowance";
				$noOfColumnsSelected++;
			}   
			if(null !== $this->input->post('city_allownce'))
			{
				array_push($header, "City Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_city_allowance, '".$encypt."') AS earned_city_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('personal_allownce'))
			{
				array_push($header,"Personal Allowance");
				$selCols .= ", AES_DECRYPT(sas.earned_personal_allowance, '".$encypt."') AS earned_personal_allowance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('referal_bonus'))
			{
				array_push($header, "Referal Bonus");
				$selCols .= ", AES_DECRYPT(sas.referal_bonus, '".$encypt."') AS referal_bonus";
				$noOfColumnsSelected++;
			} 

			if(null !== $this->input->post('pf'))
			{
				array_push($header, "PF");    
				$selCols .= ", AES_DECRYPT(sas.earned_pf, '".$encypt."') AS earned_pf";
				$noOfColumnsSelected++;    
			}
			if(null !== $this->input->post('pt'))
			{
				array_push($header, "PT");    
				$selCols .= ", AES_DECRYPT(sas.pt, '".$encypt."') AS pt";
				$noOfColumnsSelected++;    
			}
			if(null !== $this->input->post('esi'))
			{      
				array_push($header,"ESI");     
				$selCols .= ", AES_DECRYPT(sas.earned_esi, '".$encypt."') AS earned_esi";
				$noOfColumnsSelected++;     
			}

			if(null !== $this->input->post('loan'))
			{
				array_push($header,"Loan");     
				$selCols .= ", AES_DECRYPT(sas.loan, '".$encypt."') AS loan";
				$noOfColumnsSelected++;       
			}
			if(null !== $this->input->post('advance'))
			{  
				array_push($header,"Advance");     
				$selCols .= ", AES_DECRYPT(sas.advance, '".$encypt."') AS advance";
				$noOfColumnsSelected++;     
			}
			if(null !== $this->input->post('recovery'))
			{ 
				array_push($header,"Recovery");     
				$selCols .= ", AES_DECRYPT(sas.recovery, '".$encypt."') AS recovery";
				$noOfColumnsSelected++; 
			}
			if(null !== $this->input->post('donation'))
			{ 
				array_push($header,"Donation");     
				$selCols .= ", AES_DECRYPT(sas.donation, '".$encypt."') AS donation";
				$noOfColumnsSelected++; 
			}
			if(null !== $this->input->post('income_tax'))
			{ 
				array_push($header,"Income Tax");     
				$selCols .= ", AES_DECRYPT(sas.income_tax, '".$encypt."') AS income_tax";
				$noOfColumnsSelected++; 
			}    
			if(null !== $this->input->post('other_deduction'))
			{  
				array_push($header,"Other Deduction");     
				$selCols .= ", AES_DECRYPT(sas.other_deduction, '".$encypt."') AS other_deduction";
				$noOfColumnsSelected++; 
			}
			if(null !== $this->input->post('gross'))
			{
				array_push($header, "Earned Gross");
				$selCols .= ", AES_DECRYPT(sas.earned_gross, '".$encypt."') AS earned_gross";
				$noOfColumnsSelected++;
			}  
			if(null !== $this->input->post('ctc'))
			{
				array_push($header, "Earned CTC");
				$selCols .= ", AES_DECRYPT(sas.earned_ctc, '".$encypt."') AS earned_ctc";
				$noOfColumnsSelected++;
			} 
			if(null !== $this->input->post('total_deduction'))
			{
				array_push($header, "Total Deduction");
				$selCols .= ", AES_DECRYPT(sas.total_deduction, '".$encypt."') AS total_deduction";
				$noOfColumnsSelected++;
			}
			if(null !==$this->input->post('net_salary'))
			{
				array_push($header, "Net Salary");
				$selCols .= ", AES_DECRYPT(sas.net_salary, '".$encypt."') AS net_salary";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('pf_no'))
			{
				array_push($header,"PF No.");
				$selCols .= ", sal.pf_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('uan_no'))
			{
				array_push($header,"UAN No.");
				$selCols .= ", sal.uan_no";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mediclaim_no'))
			{
				array_push($header, "Mediclaim Card No.");
				$selCols .= ", sal.mediclaim_no";
				$noOfColumnsSelected++;
			}
			
			if(null !== $this->input->post('payment_mode'))
			{
				array_push($header, "Payment Mode");
				$selCols .= ", sal.payment_mode";
				$noOfColumnsSelected++;
				if($this->input->post('hdnPaymentMode') != "")
				{
					$pmt = "'".implode("', '", $this->input->post("hdnPaymentMode"))."'";
					$cond .= " AND sal.payment_mode IN (" . $pmt . ")";
				}
			}
			
			if(null !== $this->input->post('emp_type'))
			{
				array_push($header, "Emp Type");
				$selCols .= ", i.emp_type";
				$noOfColumnsSelected++;
				if($this->input->post('hdnEmployeeType') != "")
				{
					$cond .= " AND i.emp_type IN ('".implode("', '", $this->input->post("hdnEmployeeType"))."')";
				}
			}
			if(null !== $this->input->post('user_status'))
			{
				array_push($header,"Active/Inactive");
				$selCols .= ", i.user_status";
				$noOfColumnsSelected++;
				if($this->input->post('selEmpStatus') != "")
				{
					$cond .= " AND i.emp_type IN (" . implode(",",$this->input->post("selEmpStatus")) . ")";
				}
			}
			if(null !== $this->input->post('bank'))
			{
				array_push($header, "Bank Name");
				$selCols .= ", ba.bank_name";
				$noOfColumnsSelected++;
				
				if($this->input->post('hdnBank') != "")
				{
					$cond .= " AND sal.bank IN (" . implode(",",$this->input->post("hdnBank")) . ")";
				}
			}
			if(null !== $this->input->post('bank_no'))
			{
				array_push($header,"Bank Account No.");
				$selCols .= ", sal.bank_no";
				$noOfColumnsSelected++;
			
				array_push($header, "IFSC Code");
				$selCols .= ", i.ifsc_code";
				$noOfColumnsSelected++;
			}
			  
			if(null !== $this->input->post('selMonth'))
			{
				$cond .= " AND sas.salary_month = '" . $this->input->post('selMonth') . "'";
			}
			if(null !== $this->input->post('selYear'))
			{
				$cond .= " AND sas.salary_year = '" . $this->input->post('selYear') . "'";
			}
			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}

			// if($noOfColumnsSelected == 2)
			// {
				// if(null !== $this->input->post('dept_name') && null !== $this->input->post('hod'))
				// {
					// $empDetailsQry = $this->db->query("SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond);
				// }
			// } 
			 
			$emp_payroll_arr = $this->Hr_model->payroll_report_empDetails($selCols,$cond);
			$empDetailsNum = count($emp_payroll_arr);  
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai = 0;
				foreach($emp_payroll_arr as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;
					if(null !== $this->input->post('doj'))
					{
						$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
					}
					$DOB = date("d-m-Y", strtotime($empDetailsInfo['dob']));
					
					if(null !== $this->input->post('doj'))
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					if($processEmpSummaryArray)
					{

						//Employee summary array
						$empSummary = array();
						$status="Inactive";
						if(null !== $this->input->post('user_status')){
							if($empDetailsInfo['user_status'] == 1)
							{
								$status="Active";
							}
						}
						
						array_push($empSummary,$i);
						if(null !== $this->input->post('loginhandle')){
						array_push($empSummary,$empDetailsInfo['loginhandle']);
						}
						if(null !== $this->input->post('full_name')){
						array_push($empSummary, $empDetailsInfo['full_name']);
						}
						if(null !== $this->input->post('father_name')){
						array_push($empSummary, $empDetailsInfo['father_name']);
						}
					
						array_push($empSummary, $DOB);

						if(null !== $this->input->post('doj')){
						array_push($empSummary,$DOJ);
						}

						if(null !== $this->input->post('dept_name')){
						array_push($empSummary,  $empDetailsInfo['dept_name']);
						}
						if(null !== $this->input->post('desg_name')){
						array_push($empSummary, $empDetailsInfo['desg_name']); 
						}
						if(null !== $this->input->post('location')){
						array_push($empSummary, $empDetailsInfo['branch_name']); 
						}
						array_push($empSummary, $empDetailsInfo['working_days']);
						array_push($empSummary, $empDetailsInfo['weekly_off']);
						array_push($empSummary, $empDetailsInfo['holidays']);
						array_push($empSummary, $empDetailsInfo['paid_days']);
						array_push($empSummary, $empDetailsInfo['absent_days']);
						array_push($empSummary, $empDetailsInfo['arrear_days']);
						if(null !== $this->input->post('ctc')){
						array_push($empSummary, $empDetailsInfo['ctc']);
						}

						if(null !== $this->input->post('reimbursement')){
						array_push($empSummary, $empDetailsInfo['reimbursement']);
						}
						if(null !== $this->input->post('ctc') && null !== $this->input->post('reimbursement')){
						array_push($empSummary, ($empDetailsInfo['reimbursement']+$empDetailsInfo['ctc']));
						}

						if(null !== $this->input->post('gross_salary')){
						array_push($empSummary, $empDetailsInfo['gross']);
						}						
						if(null !== $this->input->post('arrear')){
						array_push($empSummary, $empDetailsInfo['arrear']); 
						}

						if(null !== $this->input->post('basic'))
						{
							array_push($empSummary, $empDetailsInfo['basic']); 
						}
						if(null !== $this->input->post('hra'))
						{
							array_push($empSummary, $empDetailsInfo['hra']);
						}
						if(null !== $this->input->post('conv'))
						{
							array_push($empSummary, $empDetailsInfo['conveyance_allowance']); 
						}
							   
						if(null !== $this->input->post('medical'))
						{
							array_push($empSummary, $empDetailsInfo['medical_allowance']); 
						}

						if(null !== $this->input->post('special_allownce'))
						{
							array_push($empSummary, $empDetailsInfo['special_allowance']); 
						}
						if(null !== $this->input->post('basic'))
							array_push($empSummary, $empDetailsInfo['earned_basic']);
						if(null !== $this->input->post('hra'))  
							array_push($empSummary, $empDetailsInfo['earned_hra']);
						if(null !== $this->input->post('conv')) 
							array_push($empSummary, $empDetailsInfo['earned_conveyance_allowance']);
						if(null !== $this->input->post('medical'))
							array_push($empSummary, $empDetailsInfo['earned_medical_allowance']); 
						if(null !== $this->input->post('special_allownce'))
							array_push($empSummary, $empDetailsInfo['earned_special_allowance']); 
							   
						if(null !== $this->input->post('relocation_allowance'))  
							array_push($empSummary, $empDetailsInfo['earned_relocation_allowance']);
						if(null !== $this->input->post('food_allowance'))                        
							array_push($empSummary, $empDetailsInfo['earned_food_allowance']);                      
						if(null !== $this->input->post('city_allownce'))
							array_push($empSummary, $empDetailsInfo['earned_city_allowance']); 
						if(null !== $this->input->post('personal_allownce'))
							array_push($empSummary, $empDetailsInfo['earned_personal_allowance']);

						if(null !== $this->input->post('referal_bonus'))
						array_push($empSummary, $empDetailsInfo['referal_bonus']);
						   
						if(null !== $this->input->post('pf'))
						{                                
							array_push($empSummary, $empDetailsInfo['earned_pf']); 
						}  
						if(null !== $this->input->post('pt'))
						{                                
							array_push($empSummary, $empDetailsInfo['pt']);                               
						} 
						if(null !== $this->input->post('esi'))
						{             
							array_push($empSummary, $empDetailsInfo['earned_esi']);                               
						}                        
						if(null !== $this->input->post('loan'))   
							array_push($empSummary, $empDetailsInfo['loan']);
						if(null !== $this->input->post('advance'))
							array_push($empSummary, $empDetailsInfo['advance']);
						if(null !== $this->input->post('recovery'))
							array_push($empSummary, $empDetailsInfo['recovery']);
						if(null !== $this->input->post('donation'))
							array_push($empSummary, $empDetailsInfo['donation']);
						if(null !== $this->input->post('income_tax'))
							array_push($empSummary, $empDetailsInfo['income_tax']);
						if(null !== $this->input->post('other_deduction'))
							array_push($empSummary, $empDetailsInfo['other_deduction']);

						if(null !== $this->input->post('gross'))
							array_push($empSummary, $empDetailsInfo['earned_gross']);
						if(null !== $this->input->post('ctc'))
							array_push($empSummary, $empDetailsInfo['earned_ctc']);
						  
						if(null !== $this->input->post('total_deduction'))
							array_push($empSummary, $empDetailsInfo['total_deduction']);
						if(null !== $this->input->post('net_salary'))
							array_push($empSummary, $empDetailsInfo['net_salary']);

						if(null !== $this->input->post('pf_no'))
							array_push($empSummary, $empDetailsInfo['pf_no']);

						if(null !== $this->input->post('uan_no'))
							array_push($empSummary, $empDetailsInfo['uan_no']);

						if(null !== $this->input->post('mediclaim_no'))
							array_push($empSummary, $empDetailsInfo['mediclaim_no']);    

						if(null !== $this->input->post('payment_mode'))
							array_push($empSummary, $empDetailsInfo['payment_mode']);
						if(null !== $this->input->post('emp_type'))
							array_push($empSummary, $empDetailsInfo['emp_type']);
						if(null !== $this->input->post('user_status'))
							array_push($empSummary,$status);
						if(null !== $this->input->post('bank'))
							array_push($empSummary, $empDetailsInfo['bank_name']);
						if(null !== $this->input->post('bank_no'))
						{
							if(substr($empDetailsInfo['bank_no'], 0, 1)==0)
								array_push($empSummary, '@'.$empDetailsInfo['bank_no']);
							else
							  array_push($empSummary, $empDetailsInfo['bank_no']);
						}
						if(null !== $this->input->post('bank_no'))
						{
							array_push($empSummary, $empDetailsInfo['ifsc_code']); 
						}
						$empSummaryArray[$ai++] = $empSummary;
					
					}
				}
			}
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			$filename = "salary_sheet_".$this->input->post('selMonth').$this->input->post('selYear').".xls"; 
			
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			
			if(null !== $this->input->post('chooseall'))
			{
				$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->mergeCells('Q1:U1');
				$objPHPExcel->getActiveSheet()->getStyle('Q1:U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ACTUAL GROSS');

				$objPHPExcel->getActiveSheet()->mergeCells('V1:AD1');
				$objPHPExcel->getActiveSheet()->getStyle('V1:AD1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('V1', 'EARNED GROSS');

				$objPHPExcel->getActiveSheet()->mergeCells('AF1:AN1');
				$objPHPExcel->getActiveSheet()->getStyle('AF1:AN1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('AF1', 'DEDUCTIONS');
			}
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			$objPHPExcel->getActiveSheet()->setTitle('Salary Sheet ' .$this->input->post('selMonth').$this->input->post('selYear')); 


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		} 
		$this->render('hr/payroll_report_view','full_width',$this->mViewData);
		$this->load->view('script/hr/js/hr_report', $this->mViewData);
	}
	
	
	public function cellColor($cells,$color)
	{
		global $objPHPExcel;
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
		->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
		'startcolor' => array('rgb' => $color)
		));
	}
	
	public function increment_report()
	{
		$this->mViewData['pageTitle']    = 'Increament Report';
		if($this->input->post('incrementExport') == "GENERATE")
		{
		  
			// Create new PHPExcel object
			$this->load->library('PHPExcel');
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
								->setLastModifiedBy("SantiBhusan Mishra")
								->setTitle("Online HR Master")
								->setSubject("Online HR Master")
								->setDescription("Online HR Master.")
								->setKeywords("Online HR Master")
								->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			

			array_push($header,"Employee Code");
			$selCols .= ", i.loginhandle";
			$noOfColumnsSelected++; 

			array_push($header, "Name");
			$selCols .= ", i.full_name";
			$noOfColumnsSelected++;

			array_push($header, "Father's Name");
			$selCols .= ", i.father_name";
			$noOfColumnsSelected++;    

			array_push($header, "Previous CTC");
			$selCols .= ", sas.ctc";
			$noOfColumnsSelected++;

			array_push($header, "Increment %");
			$selCols .= ", inc.increament";
			$noOfColumnsSelected++;

			array_push($header, "Current CTC");      
			$noOfColumnsSelected++;

			if(null !== $this->input->post('selYear')){
			   $cond .= " AND inc.year = '" . $this->input->post("selYear") . "'";
			}
			foreach($header AS $i => $head){
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
 
			$empDetailsQry = "SELECT i.login_id".$selCols." FROM `internal_user` i 
							LEFT JOIN `internal_user_ext` AS ie ON ie.login_id = i.login_id
							LEFT JOIN company_branch AS b ON b.branch_id = i.branch               
							LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id
							LEFT JOIN `emp_increament_info` AS inc ON inc.login_id = i.login_id
							LEFT JOIN `salary_sheet` AS sas ON sas.login_id = i.login_id                   
							LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
							LEFT JOIN `department` d ON d.dept_id = i.department                    
							LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id             
							LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank                                  
							WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond." AND inc.increament > 0 ORDER BY i.login_id";
			 
			//echo $empDetailsQry; exit();

			$empDetailsRes = $this->db->query($empDetailsQry);
			$emp_details_res = $empDetailsRes->result_array();
			$empDetailsNum = count($emp_details_res);
			//var_dump($emp_details_res);
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
				foreach($emp_details_res as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));

					if($empDetailsInfo['join_date'] != "")
					{
						$month_diff = getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i); 
						array_push($empSummary,$empDetailsInfo['loginhandle']);

						array_push($empSummary, $empDetailsInfo['full_name']);

						array_push($empSummary, $empDetailsInfo['father_name']);  

						array_push($empSummary, $empDetailsInfo['ctc']);

						array_push($empSummary, $empDetailsInfo['increament']);
									 
						array_push($empSummary, round($empDetailsInfo['ctc']+($empDetailsInfo['ctc']*$empDetailsInfo['increament']/100)));

						$empSummaryArray[$ai++] = $empSummary;
					} 
				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++){
				foreach($empSummaryArray[$i] AS $col => $empInfo){
						$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "increment_report_".$this->input->post('selYear').".xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));

			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
			$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('ntent-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
		$this->render('hr/increment_report_view', 'full_width', $this->mViewData); 
	}
	public function epf_report()
	{
		$this->mViewData['pageTitle']    = 'Epf Report';
		if($this->input->post('epfExport') == "GENERATE")
		{
			$encypt = $this->config->item('masterKey');
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";

			array_push($header,"PF No.");
			$selCols .= ", sal.pf_no";
			$noOfColumnsSelected++;
			
			array_push($header, "Name");
			$selCols .= ", i.full_name";
			$noOfColumnsSelected++;

			array_push($header, "DOJ");
			$selCols .= ", i.join_date";
			$noOfColumnsSelected++;

			array_push($header, "Paid Days");
			$selCols .= ", sas.paid_days";
			$noOfColumnsSelected++;

			array_push($header,"Gross Salary");
			//$selCols .= ", sal.gross_salary";
			$selCols .= ", AES_DECRYPT(sas.earned_gross, '".$encypt."') AS earned_gross";
			$noOfColumnsSelected++;
					
			array_push($header, "Wages");
			$selCols .= ", AES_DECRYPT(sas.earned_basic, '".$encypt."') AS earned_basic";
			$noOfColumnsSelected++;

			array_push($header, "EPF");
			// array_push($header, "EPF");
			// array_push($header, "EPS");    
			$selCols .= ", AES_DECRYPT(sas.earned_pf, '".$encypt."') AS earned_pf";
			$noOfColumnsSelected++;    
			 
		  
			if(null !== $this->input->post('selMonth'))
			{
				$cond .= " AND sas.salary_month = '" .$this->input->post('selMonth'). "'";
			}
			if(null !== $this->input->post('selYear'))
			{
				$cond .= " AND sas.salary_year = '" . $this->input->post('selYear') . "'";
			}
			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}


			 $empDetailsQry = "SELECT i.login_id".$selCols." FROM `internal_user` i 
								LEFT JOIN `internal_user_ext` AS ie ON ie.login_id = i.login_id
								LEFT JOIN company_branch AS b ON b.branch_id = i.branch               
								LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id
								LEFT JOIN `salary_sheet` AS sas ON sas.login_id = i.login_id                   
								LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
								LEFT JOIN `department` d ON d.dept_id = i.department                    
								LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id             
								LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank                                  
								WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond." AND AES_DECRYPT(sas.earned_pf, '".$encypt."') >0 ORDER BY i.sal_sheet_sl_no";
			
			//echo $empDetailsQry; exit();

			$empDetailsRes = $this->db->query($empDetailsQry);
			$empDetailsInfores = $empDetailsRes->result_array();
			$empDetailsNum = count($empDetailsInfores);
			$totalWages=0;
			$totalEpf=0;
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai =$totalWages =$totalEpf =$totalEps =$totalExepf  =0;
				foreach($empDetailsInfores as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));

					if($empDetailsInfo['join_date'] != "")
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					if($processEmpSummaryArray)
					{

						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);
			
						array_push($empSummary, $empDetailsInfo['pf_no']);                            
						array_push($empSummary, $empDetailsInfo['full_name']);                 
						array_push($empSummary, date('d-m-Y',strtotime($empDetailsInfo['join_date'])));                 
						array_push($empSummary, $empDetailsInfo['paid_days']);            
						array_push($empSummary, $empDetailsInfo['earned_gross']);  
						array_push($empSummary, $empDetailsInfo['earned_basic']); 
						//$epf=round($empDetailsInfo['earned_basic']*(8.33/100));
							
						array_push($empSummary, $empDetailsInfo['earned_pf']); 
						//array_push($empSummary, ($empDetailsInfo['earned_pf']-$epf));                    
						//array_push($empSummary, $epf);
							
						$totalWages = $totalWages+$empDetailsInfo['earned_basic'];             
						$totalEpf = $totalEpf+$empDetailsInfo['earned_pf'];
						//$totalExepf = $totalExepf+($empDetailsInfo['earned_pf']-$epf);
						//$totalEps = $totalEps+$epf;
						$empSummaryArray[$ai++] = $empSummary;
					}
					  
				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "epf_report_".$this->input->post('selMonth').$this->input->post('selYear').".xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));

			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('EPF Report ' .$this->input->post('selMonth').$this->input->post('selYear'));

			$k=$i+3;
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$k,'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$k, $totalWages);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$k, $totalEpf);
			 

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('ntent-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
		$this->render('hr/epf_report_view', 'full_width',$this->mViewData);
	} 
	public function esi_report()
	{
		$this->mViewData['pageTitle']    = 'Esi Report';
		if($this->input->post('esiExport') == "GENERATE")
		{
			$encypt = $this->config->item('masterKey');
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";

			array_push($header,"ESI No.");
			$selCols .= ", sal.mediclaim_no";
			$noOfColumnsSelected++;

			array_push($header, "Name");
			$selCols .= ", i.full_name";
			$noOfColumnsSelected++;

			array_push($header, "DOJ");
			$selCols .= ", i.join_date";
			$noOfColumnsSelected++;

			array_push($header, "Pay Days");
			$selCols .= ", sas.paid_days";
			$noOfColumnsSelected++;

			array_push($header, "Wages");
			$selCols .= ", AES_DECRYPT(sas.earned_gross, '".$encypt."') AS earned_gross";
			$noOfColumnsSelected++; 

			array_push($header, "ESI");    
			$selCols .= ", AES_DECRYPT(sas.earned_esi, '".$encypt."') AS earned_esi";
			$noOfColumnsSelected++; 
		  
			if(null !== $this->input->post('selMonth')){
				$cond .= " AND sas.salary_month = '" . $this->input->post('selMonth') . "'";
			}
			if(null !== $this->input->post('selYear')){
				$cond .= " AND sas.salary_year = '" . $this->input->post('selYear') . "'";
			}
			foreach($header AS $i => $head){
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}


			
			 $empDetailsQry = "SELECT i.login_id".$selCols." FROM `internal_user` i 
								LEFT JOIN `internal_user_ext` AS ie ON ie.login_id = i.login_id
								LEFT JOIN company_branch AS b ON b.branch_id = i.branch               
								LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id
								LEFT JOIN `salary_sheet` AS sas ON sas.login_id = i.login_id                   
								LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
								LEFT JOIN `department` d ON d.dept_id = i.department                    
								LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id             
								LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank                                  
								WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond." AND AES_DECRYPT(sas.earned_esi, '".$encypt."') > 0 ORDER BY i.sal_sheet_sl_no";
			
			//echo $empDetailsQry; 

			$empDetailsRes = $this->db->query($empDetailsQry);
			$empAttDetailsResult = $empDetailsRes->result_array();
			$empDetailsNum = count($empAttDetailsResult);
			//var_dump($empAttDetailsResult);
			//Employee details array
			$empSummaryArray = array();
			$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
			if($empDetailsNum >0)
			{
				foreach($empAttDetailsResult as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
						
				   
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i); 
						array_push($empSummary, $empDetailsInfo['mediclaim_no']);                            
						array_push($empSummary, $empDetailsInfo['full_name']);  
						array_push($empSummary, $DOJ); 
						array_push($empSummary, $empDetailsInfo['paid_days']);
						array_push($empSummary, $empDetailsInfo['earned_gross']);                         
						array_push($empSummary, $empDetailsInfo['earned_esi']); 
						$totalWages = $totalWages+$empDetailsInfo['earned_gross'];             
						$totalEsi = $totalEsi+$empDetailsInfo['earned_esi'];                    
						
						$empSummaryArray[$ai++] = $empSummary;
					}
						  
				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "esi_report_".$this->input->post('selMonth').$this->input->post('selYear').".xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));
		 
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
			$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('ESI Report ' .$this->input->post('selMonth').$this->input->post('selYear'));

			$k=$i+3;
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$k,'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$k, $totalWages);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$k, $totalEsi);

			$l=$k+3;
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'Emp Share');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$l, $totalEsi);

			$m=$l+1;
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$m,'Epr Share');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$m, round($totalWages*(3.25/100)));

			$n=$m+1;
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n, (round($totalWages*(3.25/100))+$totalEsi));

			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('ntent-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
		$this->render('hr/esi_report_view', 'full_width',$this->mViewData);
	} 
	public function graph_profile_list()
	{
		$this->mViewData['pageTitle'] = 'Graph Employee Profile';
		$this->render('hr/graph_profile_list_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/graph_profile_list_script');
	}
	
	public function ctc_graph(){
		$this->mViewData['pageTitle'] = 'CTC Graph';
		$loginID = $this->input->get('id');
		$sql = $this->db->query("SELECT full_name,loginhandle FROM `internal_user`  WHERE login_id = '".$loginID."'"); 
		$result = $sql->result_array(); 
		$this->mViewData['empInfo'] = $result;
		$this->render('hr/ctc_graph_view', 'full_width',$this->mViewData);
	}
	
	
	public function graphdata(){
		$loginID = $this->input->get('id');
		$encypt = $this->config->item('masterKey');
		//$query = "SELECT AES_DECRYPT(increament, '".$encypt."') AS increament,year FROM `emp_increament_info` WHERE login_id='".$loginID."' ORDER BY year ASC";
		$query = "SELECT AES_DECRYPT(s.ctc, '".$encypt."') AS increament,s.salary_year as year,i.designation,d.desg_name FROM `salary_sheet` s LEFT JOIN internal_user i ON i.login_id=s.login_id LEFT JOIN user_desg d ON d.desg_id=i.designation WHERE s.login_id='".$loginID."' AND s.salary_month='5'  GROUP BY s.salary_year ORDER BY year ASC ";
		$queryR = $this->db->query($query);
		$queryRes = $queryR->result_array();
		if(count($queryRes) == 0){
			$query = "SELECT AES_DECRYPT(s.ctc, '".$encypt."') AS increament,s.salary_year as year,i.designation,d.desg_name FROM `salary_sheet` s LEFT JOIN internal_user i ON i.login_id=s.login_id LEFT JOIN user_desg d ON d.desg_id=i.designation WHERE s.login_id='".$loginID."' GROUP BY s.salary_year ORDER BY year ASC LIMIT 1 ";
			$queryR = $this->db->query($query);
			$queryRes = $queryR->result_array();
		}
		$prefix = '';
		
		echo "[\n";
		$cumulative =0;
		$noYr = 0;
		for($i = 0; $i < COUNT($queryRes); $i++ ){
			$noYr++; 
			if($i==0){
				$initSal = $queryRes[$i]['increament'];
				$curSal = $queryRes[$i]['increament'];
				$fst = $queryRes[$i]['increament'];
				$lst = $queryRes[$i]['increament'];
				if($initSal > 0){
					$cumulative = round( (((($curSal - $initSal)/$initSal)*100)/$noYr) , 2);
				}
				else{
					$cumulative =0;
				}
			}
			else{
				$fst = $lst;
				$lst = $queryRes[$i]['increament'];
				$curSal = $queryRes[$i]['increament'];
				if($initSal > 0){
					$cumulative = round( (((($curSal - $initSal)/$initSal)*100)/$noYr) , 2);
				}
				else{
					$cumulative =0;
				}
			}
			
			if($fst > 0){
				$perc = round(((($lst - $fst)/$fst)*100), 2);
			}
			else{
				$perc = 0.00;
			}
			//$cumulative = round( ($cumulative + $perc), 2);
			//$cumulative = round( (((($curSal - $initSal)% $initSal)*100)%$noYr) , 2);
			
			echo $prefix . " {\n";
			echo '  "country": "' . (string)$queryRes[$i]['year'] .'-'.(string)( $queryRes[$i]['year']+1) . '",' . "\n";
			echo '  "visits": ' . $queryRes[$i]['increament'] . ',' . "\n";
			echo '  "designation": "' . $queryRes[$i]['desg_name'] . '",' . "\n";
			echo '  "cumulative": "' . $cumulative . '",' . "\n";
			echo '  "percentage": '.$perc.' ,' . "\n";
			//echo '  "desg": '.$queryRes[$i]['desg_name'].' ' . "\n";
			echo " }";
			$prefix = ",\n";
		}
		echo "\n]";
	} 
	public function general_resources()
	{
		$this->mViewData['pageTitle']    = 'General Resources';
		$this->render('resources/general_resources_view', 'full_width',$this->mViewData);
		$this->load->view('script/resources/general_resources_script');
	}
	/**
	* Get Max Leave
	*
	* @param	int
	* @param	char
	* @param	int
	* @return	int
	*/
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
			if($year <=2013)
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

	/**
	* Get Leave Taken
	*
	* @param	int
	* @param	int
	* @param	int
	* @param	char ('C' = Given month, 'A' = From first month to given month)
	* @return	int
	*/
	public function getLeaveTaken($userID, $month, $year, $type = 'C')
    {
        if ($type == 'C') {
            $leaveSQL = "SELECT SUM(`ob_pl`) as ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` = '$month' AND `year` = '$year'";
        } else if ($type == 'A') {
            $leaveSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` <= '$month' AND `year` = '$year'";
        }
        $leaveRES = $this->db->query($leaveSQL)->result_array();
        $leaveNUM = count($leaveRES);
        if ($leaveNUM > 0) {
            $leaveINFO = Array(
                    "ob_pl" => $leaveRES[0]['ob_pl'],
                    "ob_sl" => $leaveRES[0]['ob_sl']
                );
            if ($leaveINFO['ob_pl'] == "") {
                $leaveINFO = Array(
                    "ob_pl" => 0,
                    "ob_sl" => 0
                );
            }
        } else {
            $leaveINFO = Array(
                "ob_pl" => 0,
                "ob_sl" => 0
            );
        }
        return $leaveINFO;
    }

	//End Payroll managment

	//Starting benifit administration
	public function performance_incentive_slab()
	{
		$this->mViewData['pageTitle']    = 'Performance Incentive Slab';
  
		/*if($_POST['btnUpdateItem'] == 'Update')
		{
		$range=$_POST['txtItemValue1'].'-'.$_POST['txtItemValue2'];
		$upSQL = "UPDATE `performance_slab_master` SET `range` = '".$range."' , `pi_value` = '".$_POST['txtItemValue']."' WHERE `pi_id` = ".$_POST['pi_id']." LIMIT 1";
		@mysql_query($upSQL);
		}
		if($_POST['btnUpdateItem'] == 'Add'){
		$range=$_POST['txtItemValue1'].'-'.$_POST['txtItemValue2'];
		$inSQL = "INSERT INTO  `performance_slab_master` SET `range` = '".$range."', `pi_value` = '".$_POST['txtItemValue']."'";
		@mysql_query($inSQL);

		}*/
		$action = "Add";
		$pi_id = "";
		if(isset($_GET['id'])){
			$pi_id=$_GET['id'];
		}
		$rowedit = array();
		if(isset($_GET['action'])){
			if($_GET['action']=='delete'){
				$this->db->query("DELETE FROM `performance_slab_master` WHERE `pi_id` = ".$pi_id."");
			}
			if($_GET['action']=='edit'){
				$resedit = $this->db->query("SELECT m.* FROM performance_slab_master AS m WHERE m.pi_id = '".$pi_id."'");
				$rowedit= $resedit->result_array();
				$action = "Update";
			}
		}
		$this->mViewData['rowedit']    = $rowedit;
		$this->mViewData['actionBtn']    = $action;
		
		$sel_qry = "SELECT m.* FROM performance_slab_master AS m WHERE m.status = 'Y'";
		$result_sel = $this->db->query($sel_qry);
		$performance_details = $result_sel->result_array();
		$this->mViewData['performance_details']    = $performance_details;
		
		$this->render('hr/benifit_admibistration/performance_incentive_slab_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/js/performance_incentive_slab_js');
	}
	public function performance_incentive_slab_submit()
	{
		$pi_id = $this->input->post('pi_id');
		$rangeFrom = $this->input->post('rangeFrom');
		$rangeTo = $this->input->post('rangeTo');
		$pi_value = $this->input->post('pi_value');
		$range=$rangeFrom.'-'.$rangeTo;
		if($pi_id !=""){
			$upSQL = "UPDATE `performance_slab_master` SET `range` = '".$range."' , `pi_value` = '".$pi_value."' WHERE `pi_id` = ".$pi_id." ";
			$this->db->query($upSQL);
			echo 1;
		}
		else{
			$inSQL = "INSERT INTO  `performance_slab_master` SET `range` = '".$range."', `pi_value` = '".$pi_value."'";
			$this->db->query($inSQL);
			echo 0;
		}
		
	}
	public function attendance_benefit()
	{
		$this->mViewData['pageTitle']    = 'Attendance Benifit';
		$this->render('hr/benifit_admibistration/attendance_benefit_view', 'full_width',$this->mViewData);
	}
	public function add_extra_hours()
	{
		$this->mViewData['pageTitle']    = 'add extra hours';
		$this->render('hr/benifit_admibistration/add_extra_hours_view', 'full_width',$this->mViewData);
	}
	//end benifit administration
	
	/*----Start Leave section---*/
	public function emp_leave_provision()
	{
		$this->mViewData['pageTitle']    = 'Employee Leave Provision'; 
		$result = $this->Hr_model->get_leave_credit_history(); 
		//var_dump($result);
		$this->render('hr/leave/emp_leave_provision_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/leave/emp_leave_provision_script');
	}
	/***** Start Ajax with angularjs *****/
	public function get_leave_provision()
	{
		$result = $this->Hr_model->get_leave_provision();  
		echo json_encode($result); 
	}
	
	public function get_leave_provision_search()
	{
		$yy = $this->input->post('searchYear');
		$searchName = $this->input->post('searchName');
		$result = $this->Hr_model->get_leave_provision_search($yy,$searchName);  
		echo json_encode($result); 
	}
	
	public function get_leave_provision_export()
	{
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Date of Joining");
		$noOfColumnsSelected++;
		
		array_push($header, "Take Leave");
		$noOfColumnsSelected++; 
		
		array_push($header, "Jan");
		$noOfColumnsSelected++;
		
		array_push($header, "Feb");
		$noOfColumnsSelected++;
		
		array_push($header, "Mar");
		$noOfColumnsSelected++;
		
		array_push($header, "This Yr. Tot Leave");
		$noOfColumnsSelected++;
		
		array_push($header, "Leave Availed This Yr");
		$noOfColumnsSelected++;
		
		array_push($header, "Cumu. as on ");
		$noOfColumnsSelected++;   
		
		array_push($header, "Gross");
		$noOfColumnsSelected++; 
		
		array_push($header, "Leave Amt");
		$noOfColumnsSelected++; 

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$yy = $this->input->get('aaYear');
		$d_year=explode('-',$yy);
		$s_year=$d_year[0];
		$e_year=$d_year[1];
		$sDate = $e_year.'-03-31';
		$encypt = $this->config->item('masterKey');
		$sql = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.user_status, l.cf_pl, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary FROM `internal_user` i LEFT JOIN `leave_carry_ forward` l ON l.user_id = i.login_id LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.user_status='1' AND i.emp_type = 'F' AND i.join_date <= '$sDate' AND i.login_id != '10010' AND l.year = '$e_year'";		
		$sqlRes = $this->db->query($sql);
		$result = $sqlRes->result_array();	
		$empSummaryArray = array();
		$l = $ai  =0;
		for($k=0; $k<count($result); $k++){
			$l++;
			$processEmpSummaryArray = true;
			$feb =0;
			$reFeb=$this->db->query("SELECT * from `leave_credited_history` where DATE_FORMAT(creditedDate,'%Y-%m')='".$e_year."-02'");
			$roFebRes =  $reFeb->result_array();
			if(COUNT($roFebRes) > 0) {
				$feb2 = $feb1 = array();
				$febex2 = explode(',',$roFebRes[0]['userIDsOf2Leave']);
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
			if(COUNT($roMarRes) > 0){
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
			
			if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$l); 
					array_push($empSummary, $result[$k]['full_name']);
					array_push($empSummary, $result[$k]['loginhandle']);
					array_push($empSummary, $result[$k]['join_date']);
					array_push($empSummary, $result[$k]['cf_pl']);
					array_push($empSummary, 0);
					array_push($empSummary, $feb);
					array_push($empSummary, $mar);
					array_push($empSummary, $tot_leave_yr);
					array_push($empSummary, $leave_availed);
					array_push($empSummary, $cumu);
					array_push($empSummary, $result[$k]['gross_salary']);
					array_push($empSummary, $leave_amt);
					$empSummaryArray[$ai++] = $empSummary;
				}
		}
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "leave_track_provission_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	public function get_leave_credit_history()
	{  
		$result = $this->Hr_model->get_leave_credit_history(); 
		echo json_encode($result); 
	}
	/***** End Angularjs*****/
	public function emp_leave_details()
	{
		$this->mViewData['pageTitle']    = 'Employee Leave Details';
		$this->render('hr/leave/emp_leave_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/leave/emp_leave_info_script');
	}
	public function get_emp_leave_info()
	{
		$yy = date("Y"); 
		$dd_year = $yy;
		$cond = '';
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date FROM `internal_user` i WHERE i.user_status != '0' AND i.sal_sheet_sl_no != '0' $cond ORDER BY i.login_id ASC"); 
		$empINFO = $sql->result_array(); 
		$startYear = 2017;
		$yearCompletedAsPerThis = 0;
		$results = array();
		for($i=0; $i<count($empINFO); $i++){
			$joinDate = $empINFO[$i]['join_date'];
			$joinYear = date('Y',strtotime($joinDate));
			
			$maxPL = $this->getMaxLeave($empINFO[$i]['login_id'], 'P', $dd_year);
			$maxSL = $this->getMaxLeave($empINFO[$i]['login_id'], 'S', $dd_year);
			$curLeave = $this->getLeaveTaken($empINFO[$i]['login_id'], '12', $dd_year, 'A');
			$takenPL = 0;
			$takenSL = 0;
			if($curLeave['ob_pl'] > 0){
			 $takenPL = $curLeave['ob_pl'];
			}
			if($curLeave['ob_sl'] > 0){
				$takenSL = $curLeave['ob_sl'];
			}
			$avlPL = $maxPL - $takenPL;
			$avlSL = $maxSL - $takenSL;
			$cfPL = $avlPL;
			$cashPL = 0;
			
			$startYear = 2017;
			if($joinYear > $startYear){
				$startYear = $joinYear;
			}
			$yearCompletedAsPerThis = $dd_year - $startYear;
			
			if($yearCompletedAsPerThis == 0){
				if($avlPL > 10){
					$cashPL = $avlPL - 10;
					$cfPL = 10;
				}
			}else if($yearCompletedAsPerThis == 1){
				if($avlPL > 20){
					$cashPL = $avlPL - 20;
					$cfPL = 20;
				}
			}else{
			   if($avlPL > 22){
				$cashPL = $avlPL - 22;
				$cfPL = 22;
				}
			}
			// Maximum Encash PL is 15
			if($cashPL > 15){
				$cashPL = 15;
			}
			
			$datas = array(
				'login_id' => $empINFO[$i]['login_id'],
				'full_name' => $empINFO[$i]['full_name'],
				'loginhandle' => $empINFO[$i]['loginhandle'],
				'maxPL' => $maxPL,
				'maxSL' => $maxSL,
				'takenPL' => $takenPL,
				'takenSL' => $takenSL,
				'avlPL' => $avlPL,
				'avlSL' => $avlSL,
				'cfPL' => $cfPL,
				'cashPL' => $cashPL
			);
			array_push($results , $datas);
		}
		echo json_encode($results); 
	}
	public function get_emp_leave_info_search()
	{
		$searchYear = $this->input->post('searchYear');
		$searchName = $this->input->post('searchName');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$yy = date("Y"); 
		$dd_year = $yy;
		if($searchYear !=""){
			$dd_year = $searchYear;
		}
		$cond = '';
		if($searchName !=""){
			$cond = $cond." AND i.full_name LIKE '%".$searchName."%'";
		}
		if($searchEmpCode !=""){
			$cond = $cond." AND i.loginhandle = '".$searchEmpCode."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, i.full_name, i.join_date FROM `internal_user` i WHERE i.user_status != '0' AND i.sal_sheet_sl_no != '0' $cond ORDER BY i.login_id ASC"); 
		$empINFO = $sql->result_array(); 
		$startYear = 2017;
		$yearCompletedAsPerThis = 0;
		$results = array();
		for($i=0; $i<count($empINFO); $i++){
			$joinDate = $empINFO[$i]['join_date'];
			$joinYear = date('Y',strtotime($joinDate));
			
			$maxPL = $this->getMaxLeave($empINFO[$i]['login_id'], 'P', $dd_year);
			$maxSL = $this->getMaxLeave($empINFO[$i]['login_id'], 'S', $dd_year);
			$curLeave = $this->getLeaveTaken($empINFO[$i]['login_id'], '12', $dd_year, 'A');
			$takenPL = 0;
			$takenSL = 0;
			if($curLeave['ob_pl'] > 0){
			 $takenPL = $curLeave['ob_pl'];
			}
			if($curLeave['ob_sl'] > 0){
				$takenSL = $curLeave['ob_sl'];
			}
			$avlPL = $maxPL - $takenPL;
			$avlSL = $maxSL - $takenSL;
			$cfPL = $avlPL;
			$cashPL = 0;
			
			$startYear = 2017;
			if($joinYear > $startYear){
				$startYear = $joinYear;
			}
			$yearCompletedAsPerThis = $dd_year - $startYear;
			
			if($yearCompletedAsPerThis == 0){
				if($avlPL > 10){
					$cashPL = $avlPL - 10;
					$cfPL = 10;
				}
			}else if($yearCompletedAsPerThis == 1){
				if($avlPL > 20){
					$cashPL = $avlPL - 20;
					$cfPL = 20;
				}
			}else{
			   if($avlPL > 22){
				$cashPL = $avlPL - 22;
				$cfPL = 22;
				}
			}
			// Maximum Encash PL is 15
			if($cashPL > 15){
				$cashPL = 15;
			}
			
			$datas = array(
				'login_id' => $empINFO[$i]['login_id'],
				'full_name' => $empINFO[$i]['full_name'],
				'loginhandle' => $empINFO[$i]['loginhandle'],
				'maxPL' => $maxPL,
				'maxSL' => $maxSL,
				'takenPL' => $takenPL,
				'takenSL' => $takenSL,
				'avlPL' => $avlPL,
				'avlSL' => $avlSL,
				'cfPL' => $cfPL,
				'cashPL' => $cashPL
			);
			array_push($results , $datas);
		}
		echo json_encode($results); 
	}
	
	public function emp_leave_info_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Starting PL");
		$noOfColumnsSelected++;
		
		array_push($header, "Starting SL");
		$noOfColumnsSelected++; 
		
		array_push($header, "Taken PL");
		$noOfColumnsSelected++;
		
		array_push($header, "Taken SL");
		$noOfColumnsSelected++;
		
		array_push($header, "Current PL");
		$noOfColumnsSelected++;
		
		array_push($header, "Current SL");
		$noOfColumnsSelected++;
		
		array_push($header, "PL CF");
		$noOfColumnsSelected++;
		
		array_push($header, "PL CASH");
		$noOfColumnsSelected++;   
		
		array_push($header, "Gross");
		$noOfColumnsSelected++;   
		
		array_push($header, "Amount");
		$noOfColumnsSelected++;   
		
		array_push($header, "Status");
		$noOfColumnsSelected++;   

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		//$empDetailsQry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.join_date, i.father_name, i.dob, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic,s.calculation_type FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id != '10010' AND user_status='1'";
		$yy = date("Y"); 
		$dd_year = $yy;
		if(isset($_GET['aaYear'])){
			$dd_year=$_GET['aaYear'];  
		}
		$cond = "";
		$sql = "SELECT i.login_id,i.user_status, i.loginhandle, i.full_name, i.join_date, i.sal_sheet_sl_no FROM `internal_user` i WHERE i.user_status != '0' AND i.sal_sheet_sl_no != '0' $cond ORDER BY i.login_id ASC"; 
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		$startYear = 2011;
		$yearCompletedAsPerThis = 0;
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				
				$processEmpSummaryArray = true;
				$i++;
				$joinDate = $empDetailsInfo['join_date'];
				$joinYear = date('Y',strtotime($joinDate));
				
				$maxPL = $this->getMaxLeave($empDetailsInfo['login_id'], 'P', $dd_year);
				$maxSL = $this->getMaxLeave($empDetailsInfo['login_id'], 'S', $dd_year);
				$curLeave = $this->getLeaveTaken($empDetailsInfo['login_id'], '12', $dd_year, 'A');
				$takenPL = 0;
				$takenSL = 0;
				if($curLeave['ob_pl'] > 0){
				 $takenPL = $curLeave['ob_pl'];
				}
				if($curLeave['ob_sl'] > 0){
					$takenSL = $curLeave['ob_sl'];
				}
				$avlPL = $maxPL - $takenPL;
				$avlSL = $maxSL - $takenSL;
				$cfPL = $avlPL;
				$cashPL = 0;
				
				$startYear = 2017;
				if($joinYear > $startYear){
					$startYear = $joinYear;
				}
				$yearCompletedAsPerThis = $dd_year - $startYear;
				
				if($yearCompletedAsPerThis == 0){
					if($avlPL > 10){
						$cashPL = $avlPL - 10;
						$cfPL = 10;
					}
				}else if($yearCompletedAsPerThis == 1){
					if($avlPL > 20){
						$cashPL = $avlPL - 20;
						$cfPL = 20;
					}
				}else{
				   if($avlPL > 22){
					$cashPL = $avlPL - 22;
					$cfPL = 22;
					}
				}
				// Maximum Encash PL is 15
				if($cashPL > 15){
					$cashPL = 15;
				}
				
				
				
				$empDetailsQry = $this->db->query("SELECT i.login_id, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id = '".$empDetailsInfo['login_id']."'");
				$emp_salary_info = $empDetailsQry->result_array();
				$gross = 0.00;
				$amount = 0.00;
				if(count($emp_salary_info) > 0){
					$gross = $emp_salary_info[0]['gross_salary'];
					$amount = number_format((($gross/30)*$avlPL), 2, '.', '');
				}
				
				if($empDetailsInfo['user_status'] == '1'){
					$status = "Active";
				}
				else{
					$status = "Inactive";
				}
			   
				if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['loginhandle']);
					array_push($empSummary, $maxPL);
					array_push($empSummary, $maxSL);
					array_push($empSummary, $takenPL);
					array_push($empSummary, $takenSL);
					array_push($empSummary, $avlPL);
					array_push($empSummary, $avlSL);
					array_push($empSummary, $cfPL);
					array_push($empSummary, $cashPL);
					array_push($empSummary, $gross);
					array_push($empSummary, $amount);
					array_push($empSummary, $status);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "employee_leave_info_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	public function leave_status_info()
	{
		$this->mViewData['pageTitle']    = 'Leave Status Info';
		$this->render('hr/leave/leave_status_info_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/leave/get_leave_status_info');
	}
	/***** Start Ajax with angularjs *****/
	public function get_leave_status_info()
	{
		$result = $this->Hr_model->get_leave_status_info(); 
		echo json_encode($result); 
	}
	public function get_leave_status_info_search()
	{
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		$searchName = $this->input->post('searchName');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchStatus = $this->input->post('searchStatus');
		$result = $this->Hr_model->get_leave_status_info_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode, $searchStatus); 
		echo json_encode($result); 
	}
	public function get_leave_status_info_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Leave From");
		$noOfColumnsSelected++;
		
		array_push($header, "Leave To");
		$noOfColumnsSelected++; 
		
		array_push($header, "Status");
		$noOfColumnsSelected++;  

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		//$empDetailsQry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.join_date, i.father_name, i.dob, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic,s.calculation_type FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id != '10010' AND user_status='1'";
		$txtSdate = $txtEdate =date('Y-m-d');
		$cond = " AND i.login_id != '10010' ";
		if(isset($_GET['sdate']) && isset($_GET['edate']))
		{   
			$txtSdate = date('Y-m-d',strtotime($_GET['sdate']));
			$txtEdate = date('Y-m-d',strtotime($_GET['edate'])); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."l.leave_from BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		$sql = "SELECT i.login_id, i.loginhandle, i.full_name, l.leave_from, l.leave_to, l.status FROM `internal_user` i INNER JOIN `leave_application` l ON i.login_id = l.user_id WHERE i.user_status != '0' $cond ORDER BY l.leave_from DESC"; 
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		$startYear = 2017;
		$yearCompletedAsPerThis = 0;
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				$processEmpSummaryArray = true;
				$i++;
				$status = $empDetailsInfo['status'];
				if($status == 'A'){
					$status = "Approved";
				}
				else if($status == 'P'){
					$status = "Pending";
				}
				else if($status == 'R'){
					$status = "Rejected";
				}
				
				if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['loginhandle']);
					array_push($empSummary, $empDetailsInfo['leave_from']);
					array_push($empSummary, $empDetailsInfo['leave_to']);
					array_push($empSummary, $status);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "employee_leave_status_info_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');
		
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Leave Status Info');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Leave Report ');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	/***** End Angularjs*****/
	public function late_comming()
	{
		$this->mViewData['pageTitle']    = 'Late Coming Info';
		$this->render('hr/leave/late_comming_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/leave/get_late_coming_script');
	}
	/***** Start Ajax with angularjs *****/
	public function get_all_late_coming()
	{
		$result = $this->Hr_model->get_all_late_coming(); 
		echo json_encode($result); 
	}
	
	public function get_all_late_coming_search()
	{
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		$searchName = $this->input->post('searchName');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchMonth = $this->input->post('searchMonth');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_all_late_coming_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode, $searchMonth, $searchYear); 
		echo json_encode($result); 
	}
	public function get_all_late_coming_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Date");
		$noOfColumnsSelected++;
		
		array_push($header, "In Time");
		$noOfColumnsSelected++;  

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		
		$txtSdate = $txtEdate =date('Y-m-d');
		$cond = " AND i.login_id != '10010' ";
		if(isset($_GET['sdate']) && isset($_GET['edate']))
		{   
			$txtSdate = date('Y-m-d',strtotime($_GET['sdate']));
			$txtEdate = date('Y-m-d',strtotime($_GET['edate'])); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."a.date BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		
		if(isset($_GET['month']))
		{   
			$txtMonth = date('m',strtotime(date('Y').'-'.$_GET['month'].'-01')); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond." DATE_FORMAT(a.date, '%m') =  '$txtMonth'";
		}
		if(isset($_GET['year']))
		{   
			$txtYear = date('Y',strtotime($_GET['year'].'-'.date('m').'-01')); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond." DATE_FORMAT(a.date, '%Y') =  '$txtYear'";
		}
		$sql = "SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' $cond ORDER BY a.date DESC"; 
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		$startYear = 2018;
		$yearCompletedAsPerThis = 0;
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				$processEmpSummaryArray = true;
				$i++;
				if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['loginhandle']);
					array_push($empSummary, $empDetailsInfo['date']);
					array_push($empSummary, $empDetailsInfo['in_time']);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "employee_leave_late_coming_info_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');
		

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Leave Report ');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	/***** End Angularjs*****/
	public function late_coming_export()
	{
		$this->load->library('PHPExcel');
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		//setting the values of the headers and data of the excel file 
		$header = array("Sl. No.","Name","Employee Code","Date","Time");

		$now = date("d-m-Y"); 
			
		$filename = "emp_late_comming_".$now.".xls";
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$nextTab = "\t";
		$newLine = "\n";


		echo "Export Date: ".$now.$newLine;
		echo $newLine.$nextTab."  Employee Late Comming Information".$newLine.$newLine;

		foreach($header AS $head)
		{
			echo $head;
			echo $nextTab;
		}
		echo $newLine;


		if($_REQUEST['emp_code'] <> '' && $_REQUEST['emp_code'] <> 'Enter Employee Code') $cond = " AND i.loginhandle = '".$_REQUEST['emp_code']."'";
		if($_REQUEST['name'] <> '' && $_REQUEST['name'] <> 'Enter Name') $cond = " AND i.full_name LIKE '%".$_REQUEST['name']."%'";

		if($_REQUEST['txtSdate'] !='' && $_REQUEST['txtEdate'] !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($_REQUEST['txtSdate']));
			$txtEdate = date('Y-m-d',strtotime($_REQUEST['txtEdate']));   
			$bet = "a.date BETWEEN '$txtSdate' AND '$txtEdate' AND";
		}   
			

		// Get Late Comming Details 
		 
		$chkHoliDayRes = $this->db->query("SELECT * FROM `declared_leave` WHERE `f_disp_flag` = 1 AND `dt_event_date` = '$txtSdate'");
		$chkHoliDayNum = count($chkHoliDayRes);
		if($chkHoliDayNum < 1)
		{
			// Get Employees Absent Details
			$empSQL = "SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date FROM `internal_user` AS i
					LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
					WHERE $bet i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' AND i.login_id != 10010 AND i.join_date <= '$txtSdate' $cond ";
		}
		  
		$empRES = $this->db->query($empSQL);
		$empNUM = count($empRES);
		$empINFO = $empRES->result_array();
		if($empNUM >0)
		{
		    $i=1;
			while($empINFO)
			{	
				echo $i;
				echo $nextTab;
				echo $empINFO['full_name'];
				echo $nextTab;
				echo $empINFO['loginhandle'];
				echo $nextTab;
				echo date('d/m/Y',strtotime($empINFO['date']));
				echo $nextTab;
				echo $empINFO['in_time'];	
				echo $newLine;
				$i++;
			}
		}
		echo $newLine; 
		exit();
	}
	public function absent_details()
	{
		$this->mViewData['pageTitle']    = 'Absent Details'; 
		$this->render('hr/leave/absent_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/leave/get_absent_details_script');
	}
	/***** Start Ajax with angularjs *****/
	public function get_all_absent_details()
	{
		$result = $this->Hr_model->get_all_absent_details(); 
		echo json_encode($result); 
	}
	
	public function get_all_absent_details_search()
	{
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		$searchName = $this->input->post('searchName');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_all_absent_details_search($searchStartDate, $searchEndDate, $searchName, $searchEmpCode); 
		echo json_encode($result); 
	}
	
	
	public function get_all_absent_details_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Date");
		$noOfColumnsSelected++;
		
		array_push($header, "Reason");
		$noOfColumnsSelected++;  

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		
		$txtSdate = $txtEdate =date('Y-m-d');
		$cond = " AND i.login_id != '10010' AND  DATE_FORMAT(date, '%Y') = '".date('Y')."' ";
		if(isset($_GET['sdate']) && isset($_GET['edate']))
		{   
			$txtSdate = date('Y-m-d',strtotime($_GET['sdate']));
			$txtEdate = date('Y-m-d',strtotime($_GET['edate'])); 
			if($cond !=""){
				$cond = $cond." AND ";
			}
			$cond = $cond."date BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		$sql = "SELECT i.login_id, i.loginhandle, i.full_name, a.in_time, a.date, a.att_status FROM `internal_user` AS i
            LEFT JOIN `attendance_new` AS a ON  i.login_id = a.login_id
            WHERE i.user_status = '1' AND a.in_time > '09:15:00' AND a.in_time !='NULL' AND i.login_id != 10010  $cond ORDER BY a.date DESC"; 
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		$startYear = 2011;
		$yearCompletedAsPerThis = 0;
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				$processEmpSummaryArray = true;
				$i++;
				$status = $empDetailsInfo['att_status'];
				if($status == 'W'){
					$status = "LWH";
				}
				else {
					$status = "ABSENT";
				}
				if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['loginhandle']);
					array_push($empSummary, $empDetailsInfo['date']);
					array_push($empSummary, $status);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "employee_leave_absent_info_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');
		
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Leave Absent Information');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Leave Report ');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	/***** End Angularjs*****/
	/*---- END Leave section---*/
	
	
	public function get_months($sStartDate, $sEndDate)
	{ 
		$date1 = strtotime($sStartDate);
		$date2 = strtotime($sEndDate);
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
		$months++; 
		return $months;           
	}
	//START EXPENSES 
	public function reimbrusement()
	{
		$this->mViewData['pageTitle']    = 'loan';
		$this->render('hr/expenses_reimbrusement/reimbrusement_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/expenses_reimbrusement/reimbrusement_script');
	}
	
	public function reimbrusement_report()
	{
		
		$this->mViewData['pageTitle'] = 'Reimbrusement report';
		
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one(); 
		$this->mViewData['bank'] = $this->Hr_model->bank();
		
		/*Business logic of reimbrusement report*/
		
		if($this->input->post('exportEmployee') == "Generate")
		{
			$encypt = $this->config->item('masterKey');
			// Create new PHPExcel object
			$this->load->library('PHPExcel');
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			if(null !== $this->input->post('loginhandle'))
			{
				array_push($header,"Employee Code");
				$selCols .= ", i.loginhandle";
				$noOfColumnsSelected++;
			}
		 
			if(null !== $this->input->post('full_name'))
			{
				array_push($header, "Name");
				$selCols .= ", i.full_name";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('father_name'))
			{
				array_push($header, "Father's Name");
				$selCols .= ", i.father_name";
				$noOfColumnsSelected++;
			}
			
			if(null !== $this->input->post('doj'))
			{
				array_push($header,"DOJ");
				$selCols .= ", i.join_date";
				$noOfColumnsSelected++;
				if($this->input->post('dojFrom') != "")
				{
					$cond .= " AND i.join_date >= '" . date("Y-m-d", strtotime($this->input->post('dojFrom'))) . "'";
				}
				if($this->input->post('dojTo') != "")
				{
					$cond .= " AND i.join_date <= '" .date("Y-m-d", strtotime($this->input->post('dojTo'))) . "'";
				}
			}
			
			if(null !== $this->input->post('dept_name'))
			{
				array_push($header, "Department");
				$selCols .= ",d.dept_name";
				$noOfColumnsSelected++;
				
				if($this->input->post('selDepartment') != "")
				{
					$cond .= " AND i.department IN (" . implode(",",$this->input->post("selDepartment")) . ")";
				}
			}
			
			if(null !== $this->input->post('desg_name'))
			{
				array_push($header, "Designation");
				$selCols .= ",u.desg_name";
				$noOfColumnsSelected++;
				
				if($this->input->post('selDesignation') != "")
				{
					$cond .= " AND i.designation IN (" . implode(",",$this->input->post("selDesignation")) . ")";
				}
			}
			
			if(null !== $this->input->post('mobile_official'))
			{
				array_push($header,"Mobile Official");
				$selCols .= ", r.mobile_official";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mobile_landline'))
			{
				array_push($header,"Mobile Landline");
				$selCols .= ", r.mobile_landline";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('fuel'))
			{
				array_push($header,"Fuel");
				$selCols .= ", r.fuel";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('vehicle_maintenance'))
			{
				array_push($header,"Vehicle Maintenance");
				$selCols .= ", r.vehicle_maintenance";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('entertainment'))
			{
				array_push($header,"Entertainment");
				$selCols .= ", r.entertainment";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('book_periodical'))
			{
				array_push($header,"Book Periodical");
				$selCols .= ", r.book_periodical";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('lta'))
			{
				array_push($header,"LTA");
				$selCols .= ", r.lta";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('mediclaim'))
			{
				array_push($header,"Mediclaim");
				$selCols .= ", r.mediclaim";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('lunch'))
			{
				array_push($header,"Lunch");
				$selCols .= ", r.lunch";
				$noOfColumnsSelected++;
			}
			if(null !== $this->input->post('driver_salary'))
			{
				array_push($header,"Driver Salary");
				$selCols .= ", r.driver_salary";
				$noOfColumnsSelected++;
			}
			
			if(null !== $this->input->post('payment_mode'))
			{
				array_push($header, "Payment Mode");
				$selCols .= ",sal.payment_mode";
				$noOfColumnsSelected++;
				
				if($this->input->post('hdnPaymentMode') != "")
				{
					$cond .= " AND sal.payment_mode IN (" . implode(",",$this->input->post("hdnPaymentMode")) . ")";
				}
			}
			
			if(null !== $this->input->post('bank'))
			{
				array_push($header, "Bank Name");
				$selCols .= ",ba.bank_name";
				$noOfColumnsSelected++;
				
				if($this->input->post('hdnBank') != "")
				{
					$cond .= " AND sal.bank IN (" . implode(",",$this->input->post("hdnBank")) . ")";
				}
			}
			
			
			if(null !== $this->input->post('bank_no'))
			{
				array_push($header,"Bank Account No.");
				$selCols .= ", sal.bank_no";
				$noOfColumnsSelected++;
			}
			
			
			if(null !== $this->input->post('selMonth'))
			{
				$cond .= " AND r.reimbursement_month = '" . $this->input->post('selMonth') . "'";
			}
			if(null !== $this->input->post('selYear'))
			{
				$cond .= " AND r.reimbursement_year = '" . $this->input->post("selYear") . "'";
			}
			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}

			if($noOfColumnsSelected == 2)
			{
				if(null !== $this->input->post('dept_name') && null !== $this->input->post('hod'))
				{
					$empDetailsQry = $this->db->query("SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond);
				}
			}

			 
			$empDetailsQry = "SELECT i.login_id".$selCols." FROM `reimbursements` r 
							LEFT JOIN `internal_user` AS i ON i.login_id = r.login_id
							LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id
							LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
							LEFT JOIN `department` d ON d.dept_id = i.department          
							LEFT JOIN bank_master AS ba ON ba.bank_id = sal.bank                                  
							WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond."  ORDER BY i.sal_sheet_sl_no";
			 
			//echo $empDetailsQry; exit();

			$empDetailsRes = $this->db->query($empDetailsQry);
			$empDetailsInfo_arr = $empDetailsRes->result_array();
			$empDetailsNum = count($empDetailsInfo_arr);

			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai = 0;
				foreach($empDetailsInfo_arr as $empDetailsInfo)
				{
					$i++;
					$processEmpSummaryArray = true;
					if(null !== $this->input->post('doj')){
						$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
					}
					

					if(null !== $this->input->post('doj'))
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
					}       
				   
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);
						if(null !== $this->input->post('loginhandle'))
							array_push($empSummary,$empDetailsInfo['loginhandle']);
						
						if(null !== $this->input->post('full_name'))
							array_push($empSummary, $empDetailsInfo['full_name']);
						
						if(null !== $this->input->post('father_name'))
							array_push($empSummary, $empDetailsInfo['father_name']);

						if(null !== $this->input->post('doj'))
							array_push($empSummary,$DOJ);

						if(null !== $this->input->post('dept_name'))
							array_push($empSummary,  $empDetailsInfo['dept_name']);
						if(null !== $this->input->post('desg_name'))
							array_push($empSummary, $empDetailsInfo['desg_name']);
								 
						if(null !== $this->input->post('mobile_official'))   
							array_push($empSummary, $empDetailsInfo['mobile_official']);
						if(null !== $this->input->post('mobile_landline'))
							array_push($empSummary, $empDetailsInfo['mobile_landline']); 

						if(null !== $this->input->post('fuel'))   
							array_push($empSummary, $empDetailsInfo['fuel']);
						if(null !== $this->input->post('vehicle_maintenance'))
							array_push($empSummary, $empDetailsInfo['vehicle_maintenance']);                       

						if(null !== $this->input->post('entertainment'))   
							array_push($empSummary, $empDetailsInfo['entertainment']);
						
						if(null !== $this->input->post('book_periodical'))
							array_push($empSummary, $empDetailsInfo['book_periodical']);                       

						if(null !== $this->input->post('lta'))   
							array_push($empSummary, $empDetailsInfo['lta']);
						
						if(null !== $this->input->post('mediclaim'))
							array_push($empSummary, $empDetailsInfo['mediclaim']);  
							  
						if(null !== $this->input->post('lunch'))   
							array_push($empSummary, $empDetailsInfo['lunch']);
						
						if(null !== $this->input->post('driver_salary'))
							array_push($empSummary, $empDetailsInfo['driver_salary']);                       

						if(null !== $this->input->post('payment_mode'))
							array_push($empSummary, $empDetailsInfo['payment_mode']);
						
						if(null !== $this->input->post('bank'))
							array_push($empSummary, $empDetailsInfo['bank_name']);
						
						if(null !== $this->input->post('bank_no'))
							array_push($empSummary, $empDetailsInfo['bank_no']); 
						
							$empSummaryArray[$ai++] = $empSummary;
					}
						  
				}
			} 
			
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}

			$filename = "reimbursement_report_".$this->input->post('selMonth').$this->input->post('selYear').".xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
			'borders' => array(
			'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
			)
			));

			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		 
			function cellColor($cells,$color)
			{
				global $objPHPExcel;
				$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
				->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array('rgb' => $color)
				));
			}
			
			if(null !== $this->input->post('chooseall'))
			{
				$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true); 
				//cellColor('A1:AZ1', 'FFF58C'); 
			}    
		   
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

			$objPHPExcel->getActiveSheet()->setTitle(' Reimbrusement Report ' .$this->input->post('selMonth').$this->input->post('selYear'));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
 		//Template view 
		$this->render('hr/expenses_reimbrusement/reimbrusement_report_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr/js/hr_report', $this->mViewData);
	}
	
	public function emp_gratuity()
	{
		$this->mViewData['pageTitle']    = 'Gratuity';
		$this->render('hr/expenses_reimbrusement/emp_gratuity_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/expenses_reimbrusement/emp_gratuity_script');
	}
	public function emp_gratuity_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header, "DOJ");
		$noOfColumnsSelected++;
		
		array_push($header, "DOB");
		$noOfColumnsSelected++;

		array_push($header, "Father's Name");
		$noOfColumnsSelected++;   
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 

		array_push($header, "Amount");
		$noOfColumnsSelected++;

		array_push($header, "Year");
		$noOfColumnsSelected++;

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		$empDetailsQry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.join_date, i.father_name, i.dob, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic,s.calculation_type FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id != '10010' AND user_status='1'";
		//echo $empDetailsQry; exit();

		$empDetailsRes = $this->db->query($empDetailsQry);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		//var_dump($emp_details_res);
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				
				$processEmpSummaryArray = true;

				$date1 = strtotime($empDetailsInfo['join_date']);
				$date2 = strtotime(date('Y-m-d'));
				$months = 0;
				while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
					$months++;
				} 
				if($months >= 60){
					$i++;
					$gross= $empDetailsInfo['gross_salary'];
					if($empDetailsInfo['calculation_type'] == 'A'){ //For Automatic Salary Calculation
						$basic_percent = $empDetailsInfo['basic'];
						$basic=round($gross*($basic_percent/100));   
					}
					else{
						$basic = $empDetailsInfo['basic'];                                                    
					}
					$gratuity = ceil(($basic/26)*15*($months/12));   
			   
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i); 
						array_push($empSummary, $empDetailsInfo['name']);
						array_push($empSummary, $empDetailsInfo['join_date']);
						array_push($empSummary, $empDetailsInfo['dob']);
						array_push($empSummary, $empDetailsInfo['father_name']); 
						array_push($empSummary,$empDetailsInfo['loginhandle']); 
						array_push($empSummary, $gratuity);
						array_push($empSummary, number_format((float)($months/12), 2, '.', ''));
						$empSummaryArray[$ai++] = $empSummary;
					}
				}				
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "gratuity_report_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	public function emp_bonus()
	{
		$this->mViewData['pageTitle']    = 'Bonus';
		$yy = date("Y");
		$dd_year = $yy;
		$this->mViewData['dd_year'] = $dd_year;
		$this->render('hr/expenses_reimbrusement/emp_bonus_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/expenses_reimbrusement/emp_bonus_script');
	}
	
	public function emp_bonus_export()
	{
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Employee Code");
		$noOfColumnsSelected++; 
		
		array_push($header, "Date of Joining");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of.Days");
		$noOfColumnsSelected++;   

		array_push($header, "Amount");
		$noOfColumnsSelected++;

		array_push($header, "Bank No");
		$noOfColumnsSelected++;

		array_push($header, "IFS Code");
		$noOfColumnsSelected++;

		array_push($header, "Status");
		$noOfColumnsSelected++;

		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		$encypt = $this->config->item('masterKey');
		//$empDetailsQry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.join_date, i.father_name, i.dob, AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary,AES_DECRYPT(s.basic, '".$encypt."') AS basic,s.calculation_type FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id WHERE i.login_id != '10010' AND user_status='1'";
		$toDay=date("d-m-Y");
		$yy = date("Y");
		$s_year=$yy;
        $e_year=$yy+1;
		if(isset($_GET['aaYear'])){
			$d_year=explode('-',$_GET['aaYear']);  
			$s_year=$d_year[0];
			$e_year=$d_year[1];
		}
		$sDate = $e_year.'-03-31';
		$eDate = $e_year.'-03-01';
		$cond = " AND ((salary_month >= 4 AND salary_year=$s_year) OR (salary_month <= 3 AND salary_year=$e_year))";
		$empDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.user_status, i.ifsc_code, s.bank_no, i.sal_account_no FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id=i.login_id WHERE i.login_id != '10010' AND i.join_date <= '$eDate' AND user_status=2  GROUP BY i.login_id ORDER BY i.login_id ";	
		
		//echo $empDetailsQry; exit();

		$empDetailsRes = $this->db->query($empDetailsQry);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		//var_dump($emp_details_res);
		//Employee details array
		$empSummaryArray = array();
		$date = date('Y-m-d');
		if($empDetailsNum >0)
		{
			$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				
				$processEmpSummaryArray = true;

				$date1 = strtotime($empDetailsInfo['join_date']);
				$date2 = strtotime(date('Y-m-d'));
				$nodays= $bonus = $bank_no =0;
				$date3 = strtotime($sDate);
				while (($date1 = strtotime('+1 day', $date1)) <= $date3){
					$nodays++;
				}
				$months = 0;
				$date11 = strtotime($empDetailsInfo['join_date']);
				$date3 = strtotime($sDate);
				while (($date11 = strtotime('+1 MONTH', $date11)) <= $date2){
					$months++;
				} 
				$i++;               
				$total_bonus =$monthly_bonus =0;
				$bonusLimit =$this->db->query("Select miscellaneous_value from miscellaneous_mater where miscellaneous_id='1'");
				$bonusLimitRow = $bonusLimit->result_array();
				$bonus_basic =$bonusLimitRow[0]['miscellaneous_value'];

				$sqlsal = "SELECT AES_DECRYPT(basic, '".$encypt."') AS basic, working_days, paid_days FROM `salary_sheet` WHERE login_id = '".$empDetailsInfo['login_id']."' $cond";
				$regsal = $this->db->query($sqlsal);  
				$rowSal = $regsal->result_array();				
				$ress = $regsal->result_array();	
				for($j=0; $j<count($ress); $j++){
					$basic = $ress[$j]['basic'];    
					if($basic <= 21000){
						if($basic < $bonus_basic){
							$bonus_basic= $bonus_basic;
						}
						else{
							$bonus_basic= $bonus_basic;
						}
					}else{
						$bonus_basic =0;
					}
					$monthly_bonus=($bonus_basic/ $ress[$j]['working_days'])*$ress[$j]['paid_days'];
					$total_bonus = $total_bonus + $monthly_bonus;
				}
				if($total_bonus > 84000)
				{
					$bonus = $bonus_basic;
				}
				else
				{
					$bonus=ceil($total_bonus*(8.333/100));
				}
				//$bonus=ceil($total_bonus*(8.333/100));
				
				/* if($empDetailsInfo['login_id'] == '11311'){
					echo 'nodays:' .$nodays.'<br/>';
					echo 'months:' .$months.'<br/>';
					exit;
				} */
				
				if(substr($empDetailsInfo['sal_account_no'], 0, 1)==0){
					$bank_no ='@'.$empDetailsInfo['bank_no'];
				} else {
					$bank_no =$empDetailsInfo['bank_no']; 
				}
			   
				if($processEmpSummaryArray)
				{ 
					//Employee summary array
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['loginhandle']);
					array_push($empSummary, date("jS M Y", strtotime($empDetailsInfo['join_date'])));
					array_push($empSummary, $nodays);
					array_push($empSummary, $bonus);
					array_push($empSummary, $bank_no);
					array_push($empSummary, $empDetailsInfo['ifsc_code']); 
					array_push($empSummary,$empDetailsInfo['user_status']);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "bonus_report_".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	
	public function get_emp_bonus()
	{
		$result = $this->Expenses_reimbrusement_model->get_emp_bonus(); 
		echo json_encode($result); 
	} 
	public function get_emp_bonus_search()
	{
		$searchYear = $this->input->post('searchYear');
		$searchName = $this->input->post('searchName');
		$result = $this->Expenses_reimbrusement_model->get_emp_bonus_search($searchYear, $searchName); 
		echo json_encode($result); 
	} 
	
	public function emp_fnf()
	{
		$this->mViewData['pageTitle']    = 'Employee F&F';
		$encypt = $this->config->item('masterKey');
		$mm = date("m");
		$yy = date("Y");
		$cond = '';

		if($this->input->post('searchRegularize') == 'Find')
		{
			$dd_month = $this->input->post('dd_month');
			if($dd_month < 10)
			{
				$dd_month = "0$dd_month";
			} 
			$dd_year = $this->input->post('dd_year');
			$cond = "AND DATE_FORMAT(`lwd_date`, '%Y-%m') = '$dd_year-$dd_month'";
		}
		else
		{
			$dd_month = $mm;
			$dd_year = $yy;
			$cond = "AND DATE_FORMAT(`lwd_date`, '%Y-%m') = '$dd_year-$dd_month'";
		}
		$month = date("F", strtotime($dd_year."-".$dd_month."-01"));
		$this->mViewData['ff_cur_monthStr'] = $month;
		$this->mViewData['ff_cur_month'] = $dd_month;
		$this->mViewData['ff_cur_year'] = $dd_year;
		// Get full and final doc

		$regAppSql = "SELECT i.login_id, i.loginhandle, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name,AES_DECRYPT(s.basic, '".$encypt."') AS basic,AES_DECRYPT(s.net_salary, '".$encypt."') AS net_salary, i.resign_date, i.lwd_date, r.*  FROM `internal_user` i LEFT JOIN `resignation` r ON r.login_id = i.login_id LEFT JOIN `salary_sheet` s ON s.login_id = i.login_id LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND s.salary_month='".$dd_month."' AND s.salary_year='".$dd_year."' $cond";
		//$regAppSql = "SELECT i.login_id, i.loginhandle, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name,AES_DECRYPT(s.basic, '".$encypt."') AS basic,AES_DECRYPT(s.net_salary, '".$encypt."') AS net_salary, i.resign_date, i.lwd_date, r.*  FROM `internal_user` i LEFT JOIN `resignation` r ON r.login_id = i.login_id LEFT JOIN `salary_sheet` s ON s.login_id = i.login_id LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' $cond";
		$regAppRes = $this->db->query($regAppSql);
		//$this->mViewData['result'] = $regAppRes->result_array();
		$result = $regAppRes->result_array();
		$results = array();
		
		$count = count($result); 
		if($count > 0)
		{
			for($i=0; $i<$count; $i++)
			{
				$maxPL = $this->getMaxLeave($result[$i]['login_id'], 'P', $dd_year);			
				$leaveINFO = $this->getLeaveTaken($result[$i]['login_id'], $dd_month, $dd_year, 'A');
				$ob_pl =$leaveINFO['ob_pl']; 
				$avlPL = $maxPL - $ob_pl; 
				//$notice_shortfall=($result[$i]['notice_period']-$result[$i]['notice_served']);
				$notice_shortfall=0;
				$notice_pay_days = $avlPL + $result[$i]['notice_waiver']-$notice_shortfall;
				$basic = $result[$i]['basic'];
				$leave_encash =round(($basic/30*$notice_pay_days));
								 
				$date1 = strtotime($result[$i]['resign_date']);
				$date2 = strtotime($result[$i]['lwd_date']);
				$notice_served =0;
				while(($date1 = strtotime('+1 DAY', $date1)) <= $date2)
				{
                    $notice_served++;
				}
				
				$datas = array(
					'name' => $result[$i]['name'] ,
					'notice_period' => $result[$i]['notice_period'] ,
					'notice_served' => $notice_served ,
					'notice_waiver' => $result[$i]['notice_waiver'] ,
					'basic' => $result[$i]['basic'] ,
					'net_salary' => $result[$i]['net_salary'] ,
					'extra_hour' => $result[$i]['extra_hour'] ,
					'extra_hour' => $result[$i]['extra_hour'] ,
					'notice_shortfall' => $notice_shortfall ,
					'avlPL' => $avlPL ,
					'avlPL' => $avlPL ,
				);
				array_push($results, $datas);
			}
		}	 
		$this->mViewData['result'] = $results;	
		$this->render('hr/expenses_reimbrusement/emp_fnf_view', 'full_width',$this->mViewData); 
	}
	//START AJAX
	public function get_reimbrusement()
	{
		$result = $this->Expenses_reimbrusement_model->get_reimbrusement(); 
		echo json_encode($result); 
	}
	public function get_reimbrusement_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Expenses_reimbrusement_model->get_reimbrusement_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result);
	}
	public function get_reimbrusement_report()
	{
		$result = $this->Expenses_reimbrusement_model->get_reimbrusement_report(); 
		echo json_encode($result); 
	}
	public function get_emp_gratuity()
	{
		$result = $this->Expenses_reimbrusement_model->get_emp_gratuity(); 
		echo json_encode($result); 
	}
	//END AJAX
	//END EXPENSES
	// Start Loan 
	public function loan_advance_approve_reject()
	{
		$this->mViewData['pageTitle']    = 'Loan/Advance';
		$this->render('hr/loan/loan_advance_approve_reject_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/loan/loan_advance_approve_reject_script');
	}
	
	public function get_departments()
	{
		$result = $this->Hr_model->get_department(); 
		echo json_encode($result); 		
	}
	
	public function get_designation()
	{
		$dept_id = $this->input->post('searchDepartment');
		$result = $this->Hr_model->get_designation_by_department($dept_id); 
		echo json_encode($result); 		
	}
	
	public function get_loan_advance_approve_reject_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Loan_model->get_loan_advance_approve_reject_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	public function loan()
	{
		$this->mViewData['pageTitle']    = 'Loan';
		$this->render('hr/loan/loan_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/loan/loan_script');
	}
	public function advance_aaplied()
	{
		$this->mViewData['pageTitle']    = 'loan';
		$this->render('hr/loan/advance_aaplied_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/loan/advance_aaplied_script');
	}
	
	
	public function loan_advance_report()
	{
		$this->mViewData['pageTitle']    = 'loan';
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one(); 
		$this->mViewData['bank'] = $this->Hr_model->bank();
		//business logic for loan & advance report
		if($this->input->post('exportEmployee') == "Generate")
		{
			$encypt = $this->config->item('masterKey');
			$this->load->library('PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");
		  
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		if(null !== $this->input->post('loginhandle'))
		{
			array_push($header,"Employee Code");
			$selCols .= ", i.loginhandle";
			$noOfColumnsSelected++;
		}

		if(null !== $this->input->post('full_name'))
		{
			array_push($header, "Name");
			$selCols .= ", i.full_name";
			$noOfColumnsSelected++;
		}
		
		if(null !== $this->input->post('father_name')){
			array_push($header, "Father's Name");
			$selCols .= ", i.father_name";
			$noOfColumnsSelected++;
		}
		
			  
		if(null !== $this->input->post('doj'))
		{
			array_push($header,"DOJ");
			$selCols .= ", i.join_date";
			$noOfColumnsSelected++;
			if($this->input->post('dojFrom') != "")
			{
				$cond .= " AND i.join_date >= '" . date("Y-m-d", strtotime($this->input->post('dojFrom'))) . "'";
			}
			if($this->input->post('dojTo') != "")
			{
				$cond .= " AND i.join_date <= '" .date("Y-m-d", strtotime($this->input->post('dojTo'))) . "'";
			}
		}
		
		if(null !== $this->input->post('dept_name'))
		{
			array_push($header, "Department");
			$selCols .= ",d.dept_name";
			$noOfColumnsSelected++;
			
			if($this->input->post('selDepartment') != "")
			{
				$cond .= " AND i.department IN (" . implode(",",$this->input->post("selDepartment")) . ")";
			}
		}
		
		if(null !== $this->input->post('desg_name'))
		{
			array_push($header, "Designation");
			$selCols .= ",u.desg_name";
			$noOfColumnsSelected++;
			
			if($this->input->post('selDesignation') != "")
			{
				$cond .= " AND i.designation IN (" . implode(",",$this->input->post("selDesignation")) . ")";
			}
		}
		
		// array_push($header,"Applied Amount");
		// $selCols .= ", i.father_name";		
		// $noOfColumnsSelected++; 
			  
		if(null !== $this->input->post('loan'))
		{
			array_push($header,"Deducted Loan Amount for this month");     
			$selCols .= ", AES_DECRYPT(l.loan, '".$encypt."') AS loan";
			$noOfColumnsSelected++;       
		}
		
		if(null !== $this->input->post('advance'))
		{    
			array_push($header,"Deducted Advance Amount for this month");     
			$selCols .= ", AES_DECRYPT(l.advance, '".$encypt."') AS advance";
			$noOfColumnsSelected++;     
		} 

		if(null !== $this->input->post('selMonth'))
		{
		   $cond .= " AND l.lmonth = '" . $this->input->post('selMonth') . "'";
		}
		if(null !== $this->input->post('selYear'))
		{
		   $cond .= " AND l.lyear = '" . $this->input->post('selYear') . "'";
		}
		
		foreach($header AS $i => $head)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}

		if($noOfColumnsSelected == 2)
		{
			if(null !== $this->input->post('dept_name') && null !== $this->input->post('hod'))
			{
				$empDetailsQry = "SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.login_id WHERE 1" . $cond;
			}
		}
 
		 $empDetailsQry = "SELECT l.*, i.login_id".$selCols." FROM `loan_advance` l 
							LEFT JOIN `internal_user` AS i ON i.login_id = l.login_id                    
							LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
							LEFT JOIN `department` d ON d.dept_id = i.department   
							LEFT JOIN `salary_info` AS sal ON sal.login_id = i.login_id                                 
							WHERE i.user_status != '0' AND i.login_id != '10010' ".$cond."";
		$empDetailsRes = $this->db->query($empDetailsQry);
		$empDetailsInfo_arr = $empDetailsRes->result_array();
		$empDetailsNum = count($empDetailsInfo_arr);
	
		//Employee details array
		$empSummaryArray = array();
		if($empDetailsNum >0)
		{
			$i = $ai = 0;
			foreach($empDetailsInfo_arr as $empDetailsInfo)
			{
				$i++;
				$processEmpSummaryArray = true;
				if(null !== $this->input->post('doj')){
					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
				}
				

				if(null !== $this->input->post('doj'))
				{
					$month_diff = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);					
				}       
			   
				if($processEmpSummaryArray){

				//Employee summary array
				$empSummary = array();
				array_push($empSummary,$i);
				
					if(null !== $this->input->post('loginhandle'))
						array_push($empSummary,$empDetailsInfo['loginhandle']);
					if(null !== $this->input->post('full_name'))
						array_push($empSummary, $empDetailsInfo['full_name']);
					if(null !== $this->input->post('father_name')){
						array_push($empSummary, $empDetailsInfo['father_name']);
					}
					

					if(null !== $this->input->post('doj'))
						array_push($empSummary,$DOJ);

					if(null !== $this->input->post('dept_name'))
						array_push($empSummary,  $empDetailsInfo['dept_name']);
					if(null !== $this->input->post('desg_name'))
						array_push($empSummary, $empDetailsInfo['desg_name']); 
					if(null !== $this->input->post('approvedamount'))
					array_push($empSummary, $empDetailsInfo['approvedamount']);
					
					if(null !== $this->input->post('loan'))   
						array_push($empSummary, $empDetailsInfo['loan']);
					if(null !== $this->input->post('advance'))
						array_push($empSummary, $empDetailsInfo['advance']);                       

					
					$empSummaryArray[$ai++] = $empSummary;
				}
				  
			}
		}

		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++)
		{
			foreach($empSummaryArray[$i] AS $col => $empInfo)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
			 
		$filename = "loan_advance_".$this->input->post('selMonth').$this->input->post('selYear').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
			'borders' => array(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
			)
		));
		
		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);  
			
		if(null !== $this->input->post('chooseall'))
		{
			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true); 
			//cellColor('A1:AZ1', 'FFF58C'); 
		}    
		   
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

		$objPHPExcel->getActiveSheet()->setTitle(' Loan Advance ' .$this->input->post('selMonth').$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
		}
		//end 
		//template view
		$this->render('hr/loan/loan_advance_report_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/js/hr_report', $this->mViewData);
	}
	/*Start Ajax with angularjs function*/
	public function get_loan()
	{
		$result = $this->Loan_model->get_loan(); 
		echo json_encode($result); 
	}
	public function get_advance_aaplied()
	{
		$result = $this->Loan_model->get_advance_aaplied(); 
		echo json_encode($result); 
	}
	public function get_loan_advance_approve_reject()
	{
		$result = $this->Loan_model->get_loan_advance_approve_reject(); 
		echo json_encode($result); 
	}
	/*End */
	//End Loan
	//Start performance management
	public function probation_assessment_all()
	{
		$this->mViewData['pageTitle']    = 'Probation assessment all';
		//template view
		$this->render('hr/performance_management/probation_assessment_all_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/performance_management/probation_assessment_all_script');
	}
	
	/*************** Mid year review all  **************/
	public function midyear_review_all()
	{
		$this->mViewData['pageTitle']    = 'Mid year review all';
		//template view
		$this->render('hr/performance_management/midyear_review_all_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/performance_management/midyear_review_all_script');
	}
	
	public function get_midyear_review_all()
	{
		$result = $this->Performance_model->get_midyear_review_all(); 
		echo json_encode($result); 
	}
	
	/********* Mid-Year Review Report *************/
	public function midyear_review_report_export(){
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";	

		array_push($header, "Name");
		$noOfColumnsSelected++;	

		array_push($header,"Employee Code");
		$noOfColumnsSelected++;

		array_push($header, "Date of Report");
		$noOfColumnsSelected++;	
		
		array_push($header, "Designation");
		$noOfColumnsSelected++;
		
		array_push($header, "% Of Progressive Report");
		$noOfColumnsSelected++;
		
		array_push($header, "Emp Development");
		$noOfColumnsSelected++;
		
		array_push($header, "Status");
		$noOfColumnsSelected++;
		
		array_push($header, "Remarks");
		$noOfColumnsSelected++;
		
		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$encypt = $this->config->item('masterKey');
		
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y', strtotime());
		}
		else{
			$fyear = $y - 1;
		}
		if($_GET['review_year'] != "" && isset($_GET['review_year']))
		{
			$Myear = explode('-', $_GET['review_year']);
			$cond .=" AND DATE_FORMAT(om.apply_date,'%Y')=$Myear[0]";
		}
		
		if($_GET['name'] != "" && isset($_GET['name']))	
		{
			$cond .=" AND i.full_name like "."'%".$_GET['name']."%'";
		}
		if($_GET['department'] != "" && isset($_GET['department']))	
		{
			$cond .=" AND i.department=".$_GET['department'];
		}
		if($_GET['designation'] != "" && isset($_GET['designation']))	
		{
			$cond .=" AND i.designation=".$_GET['designation'];
		}
		
		if($_GET['emp_code'] != "" && isset($_GET['emp_code']))	
		{
			$cond .=" AND i.loginhandle="."'".$_GET['emp_code']."'";
		}
		
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS full_name, i.full_name as name, i.designation, om.*, u.desg_name FROM `internal_user` i RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE i.user_status = '1' $cond");
		
		$empDetailsInfo = $sql->result_array();
		$resCOUNT = COUNT($empDetailsInfo);
		
		$empSummaryArray = array();
		$j=0;
		for($i=0;$i<$resCOUNT;$i++){ 
			$s_year1=$empDetailsInfo[$i]['apply_date']+1;
			$cond2 = " AND DATE_FORMAT(annualdate,'%Y')='".$s_year1."'"; 
			//$cond2 = " AND DATE_FORMAT(adate,'%Y')='".$empDetailsInfo[$i]['apply_date']."'"; 
			$regsal = $this->db->query("SELECT * FROM `goal_sheet` WHERE login_id = '".$empDetailsInfo[$i]['login_id']."' $cond2");
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
			$j=$i+1;
			$empSummary = array();
			array_push($empSummary,$j); 
			array_push($empSummary, $empDetailsInfo[$i]['name']);
			array_push($empSummary, $empDetailsInfo[$i]['loginhandle']);
			array_push($empSummary, date("jS M Y", strtotime($empDetailsInfo[$i]['apply_date'])));
			array_push($empSummary, $empDetailsInfo[$i]['desg_name']);
			array_push($empSummary, $per_progress);
			array_push($empSummary, $empDetailsInfo[$i]['employee_development']); 
			if($empDetailsInfo[$i]['rm_status'] == 0)
			{
				array_push($empSummary, 'Pending'); 
			}
			else if($empDetailsInfo[$i]['rm_status'] == 1)
			{
				array_push($empSummary, 'Approved'); 
			}
			else if($empDetailsInfo[$i]['rm_status'] == 2)
			{
				array_push($empSummary, 'Rejected'); 
			}
			array_push($empSummary, $empDetailsInfo[$i]['remark']);
			$empSummaryArray[$i] = $empSummary;
		}
		
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "midyear_review_detail_report_".date('YmdHis').".xls";
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ');


		$objPHPExcel->setActiveSheetIndex(0);
	
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	public function get_midyear_review_all_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Performance_model->get_midyear_review_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	public function update_midyear_review_remark()
	{
		$remark = $this->input->post('remark');
		$mid = $this->input->post('mid');
		$result = $this->Performance_model->update_midyear_review_remark($remark,$mid); 
		echo json_encode($result); 
	}
	public function midyear_review_print()
	{
		$this->mViewData['pageTitle']    = 'Mid year review';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$mid = $_GET['mid'];

		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id  WHERE i.login_id = '".$loginID."' and mid='".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array();
		$this->mViewData['rowAppraisal'] = $revRes->result_array();

		$cond = "DATE_FORMAT(annualdate,'%Y')='".date('Y',strtotime($rowAppraisal[0]['apply_date']))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->load->view('hr_help_desk/midyear_review_detail', $this->mViewData);
	}
	/*************** END/ Mid year review all  **************/
	
	
	
	public function midyear_appraisal_report()
	{
		$this->mViewData['pageTitle']    = 'Mid year review report';
		//template view
		$this->render('hr/performance_management/midyear_appraisal_report_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/performance_management/midyear_appraisal_report_script');
	}
	
	public function midyear_appraisal_report_export(){
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");
		$header = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";	

		array_push($header, "Name");
		$noOfColumnsSelected++;	

		array_push($header,"Employee Code");
		$noOfColumnsSelected++;

		array_push($header, "Date of Report");
		$noOfColumnsSelected++;	
		
		array_push($header, "Designation");
		$noOfColumnsSelected++;
		
		array_push($header, "% Of Progressive Report");
		$noOfColumnsSelected++;
		
		array_push($header, "Emp Development");
		$noOfColumnsSelected++;
		
		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$cond = "";
		
		$encypt = $this->config->item('masterKey');
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$e_year=$fyear; 
		if(isset($_GET['aaYear'])){
			$d_year=explode('-',$_GET['aaYear']);  
			$e_year=$d_year[0];
			$s_year=$d_year[1];
			
			$cond .= " AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
		}
		if($_GET['name'] != "" && isset($_GET['name']))	
		{
			$cond .=" AND i.full_name like "."'%".$_GET['name']."%'";
		}
		if($_GET['department'] != "" && isset($_GET['department']))	
		{
			$cond .=" AND i.department=".$_GET['department'];
		}
		if($_GET['designation'] != "" && isset($_GET['designation']))	
		{
			$cond .=" AND i.designation=".$_GET['designation'];
		}
		
		if($_GET['emp_code'] != "" && isset($_GET['emp_code']))	
		{
			$cond .=" AND i.loginhandle="."'".$_GET['emp_code']."'";
		}
		
		
		//$cond = " AND DATE_FORMAT(m.apply_date,'%Y')=$s_year";
		$sql = $this->db->query("SELECT i.login_id, i.loginhandle, CONCAT(name_first, ' ', name_last) AS full_name, i.full_name as name, i.designation, m.*, u.* FROM `internal_user` i RIGHT JOIN `midyear_review` m ON m.login_id=i.login_id LEFT JOIN `user_desg` u ON u.desg_id=i.designation WHERE m.login_id != '10010' AND m.rm_status='1' AND i.user_status='1' $cond"); 
		$empDetailsInfo = $sql->result_array(); 
		$resCOUNT = COUNT($empDetailsInfo);
		
		$empSummaryArray = array();
		$j=0;
		for($i=0;$i<$resCOUNT;$i++){ 
			$s_year1=$empDetailsInfo[$i]['apply_date']+1;
			$cond2 = " AND DATE_FORMAT(annualdate,'%Y')='".$s_year1."'"; 
			//$cond2 = " AND DATE_FORMAT(adate,'%Y')='".$empDetailsInfo[$i]['apply_date']."'"; 
			$regsal = $this->db->query("SELECT * FROM `goal_sheet` WHERE login_id = '".$empDetailsInfo[$i]['login_id']."' $cond2");
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
			$j=$i+1;
			$empSummary = array();
			array_push($empSummary,$j); 
			array_push($empSummary, $empDetailsInfo[$i]['name']);
			array_push($empSummary, $empDetailsInfo[$i]['loginhandle']);
			array_push($empSummary, date("jS M Y", strtotime($empDetailsInfo[$i]['apply_date'])));
			array_push($empSummary, $empDetailsInfo[$i]['desg_name']);
			array_push($empSummary, $per_progress);
			array_push($empSummary, $empDetailsInfo[$i]['employee_development']); 
			$empSummaryArray[$i] = $empSummary;
		}
		
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "midyear_appraisal_report_".date('YmdHis').".xls";
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ');


		$objPHPExcel->setActiveSheetIndex(0);
	
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	/********  Annual appraisal all ********/
	public function annual_appraisal_all()
	{
		$this->mViewData['pageTitle']    = 'Annual appraisal all';
		//template view
		$this->render('hr/performance_management/annual_appraisal_all_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/performance_management/annual_appraisal_all_script');
	}
	
	public function get_annual_appraisal_all()
	{
		$result = $this->Performance_model->get_annual_appraisal_all(); 
		echo json_encode($result); 
	}
	public function update_annual_appraisal_remark()
	{
		$remark = $this->input->post('remark');
		$mid = $this->input->post('mid');
		$result = $this->Performance_model->update_annual_appraisal_remark($remark,$mid); 
		echo json_encode($result); 
	}
	public function get_annual_appraisal_all_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Performance_model->get_annual_appraisal_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	public function annual_appraisal_print()
	{
		$this->mViewData['pageTitle']    = 'My annual appraisal';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$mid = $_GET['mid'];

		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `internal_user` ii ON ii.reporting_to = '".$this->session->user_id."'  WHERE i.login_id = '".$loginID."'and mid='".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array();
		$this->mViewData['rowAppraisal'] = $revRes->result_array();

		$cond = "DATE_FORMAT(annualdate,'%Y')='".date('Y',strtotime($rowAppraisal[0]['apply_date']))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->load->view('hr_help_desk/annual_appraisal_detail', $this->mViewData);
	}
	
	public function annual_appraisal_export()
	{
		//print_r($_GET); exit;
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online Annual Appraisal")
									->setSubject("Online Annual Appraisal")
									->setDescription("Online Annual Appraisal.")
									->setKeywords("Online Annual Appraisal")
									->setCategory("Online Annual Appraisal Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;

			array_push($header,"Employee Name.");
			$noOfColumnsSelected++;

			array_push($header, "Employee Code.");
			$noOfColumnsSelected++;

			array_push($header, "Date Of Application");
			$noOfColumnsSelected++;

			array_push($header, "Date of Review");
			$noOfColumnsSelected++;

			array_push($header, "RM Status");
			$noOfColumnsSelected++; 
			
			array_push($header, "Review Status");
			$noOfColumnsSelected++; 

			array_push($header, "Remark");
			$noOfColumnsSelected++; 
		  
			
			foreach($header AS $i => $head){
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
			
			
			$cond = " i.user_status = '1'";
			$yy = date("Y");
			if($_GET['year'] == ""){
				$e_year=$yy; 
			}
			else{
				$d_year=explode('-',$_GET['year']);  
				$s_year=$d_year[0];
				$e_year=$d_year[1];
				$cond = $cond." AND DATE_FORMAT(om.apply_date,'%Y')=$e_year";
			}
			
			if($_GET['department'] !=""){
				if($cond !=""){
					$cond = $cond." AND ";
				}
				$cond = $cond."department = '".$_GET['department']."'";
			}
			if($_GET['name'] !=""){
				if($cond !=""){
					$cond = $cond." AND ";
				}
				$cond = $cond."full_name LIKE '%".$_GET['name']."%'";
			}
			if($_GET['designation'] !=""){
				if($cond !=""){
					$cond = $cond." AND ";
				}
				$cond = $cond."designation = '".$_GET['designation']."'";
			}
			if($_GET['emp_code'] !=""){
				if($cond !=""){
					$cond = $cond." AND ";
				}
				$cond = $cond."loginhandle = '".$_GET['emp_code']."'";
			}
										
			
			$empDetailsRes = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.dh_status, om.rm_status, om.mid, om.apply_date, om.approved_date, om.remark, dp.dept_id FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE $cond ");
			$empAttDetailsResult = $empDetailsRes->result_array();
			$empDetailsNum = count($empAttDetailsResult);
			
			//Employee details array
			$empSummaryArray = array();
			$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
			$rm_status = ""; $dh_status = "";
			if($empDetailsNum >0)
			{
				foreach($empAttDetailsResult as $empDetailsInfo)
				{
					$i++;
					//$processEmpSummaryArray = true;

						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i); 
						array_push($empSummary, $empDetailsInfo['name']);                            
						array_push($empSummary, $empDetailsInfo['loginhandle']);  
						array_push($empSummary, $empDetailsInfo['apply_date']); 
						array_push($empSummary, $empDetailsInfo['approved_date']);
						if($empDetailsInfo['rm_status'] == "0")
						{
							$rm_status = "Pending";	
						}else if($empDetailsInfo['rm_status'] == "1")
						{
							$rm_status = "Approved";	
						}else if($empDetailsInfo['rm_status'] == "2"){
							$rm_status = "Rejected";	
						}
						
						array_push($empSummary, $rm_status);                         
						if($empDetailsInfo['dh_status'] == "0")
						{
							$dh_status = "Pending";	
						}else if($empDetailsInfo['dh_status'] == "1")
						{
							$dh_status = "Approved";	
						}else if($empDetailsInfo['dh_status'] == "2"){
							$dh_status = "Rejected";	
						}
						array_push($empSummary, $dh_status); 
						array_push($empSummary, $empDetailsInfo['remark']); 
						
						$empSummaryArray[$ai++] = $empSummary;
						  
				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "Annual_Apprisal_report.xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));
		 
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
			$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Annual Appraisal Report');


			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('ntent-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
	}
	/******** END/  Annual appraisal all ********/
	
	/********  Annual appraisal report ********/
	public function annual_appraisal_report()
	{
		$this->mViewData['pageTitle']    = 'Annual appraisal Report';
		
		if(isset($_GET['type']) && isset($_GET['aaYear']))
		{
			$encypt = $this->config->item('masterKey');
			$empDetailsQry = "";
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";

			array_push($header, "Name");    
			$noOfColumnsSelected++;
			
			array_push($header, "Emp Code");     
			$noOfColumnsSelected++;

			array_push($header, "Designation");      
			$noOfColumnsSelected++;

			array_push($header, "Department");      
			$noOfColumnsSelected++;

			array_push($header, "Reporting Name");      
			$noOfColumnsSelected++;

			array_push($header, "Reviewer Name");      
			$noOfColumnsSelected++;

			array_push($header, "Section A Score");      
			$noOfColumnsSelected++;

			array_push($header, "Actual Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Round Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Fixed Basic");      
			$noOfColumnsSelected++;

			array_push($header, "Fixed HRA");      
			$noOfColumnsSelected++;

			array_push($header, "Fixed Conv");      
			$noOfColumnsSelected++;

			array_push($header, "Fixed Special");      
			$noOfColumnsSelected++;

			array_push($header, "Fixed Medical");      
			$noOfColumnsSelected++;

			array_push($header, "Gross");      
			$noOfColumnsSelected++;

			array_push($header, "CTC");      
			$noOfColumnsSelected++;

			array_push($header, "Exp in POLOSOFT");      
			$noOfColumnsSelected++;

			array_push($header, "Exp Prior to POLOSOFT");      
			$noOfColumnsSelected++;

			array_push($header, "Total Exp");      
			$noOfColumnsSelected++;

			array_push($header, "Promotion");      
			$noOfColumnsSelected++;

			array_push($header, "Emp. Development");      
			$noOfColumnsSelected++;

			array_push($header, "CTC");      
			$noOfColumnsSelected++;

			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
			
			
			$yy = date("Y");
			$e_year=$yy;
			$searchYear = $_GET['aaYear'];
			if($searchYear != ""){
				$d_year=explode('-',$searchYear);  
				$s_year=$d_year[0];
				$e_year=$d_year[1];
			}
			$cond =" AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
			if($empDetailsQry == "")
			{
				$empDetailsQry = "SELECT i.login_id, i.loginhandle, i.reporting_to, i.join_date, ie.exp_others, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, dp.dept_name,
				AES_DECRYPT(s.ctc, '".$encypt."') AS ctc,
				AES_DECRYPT(s.gross, '".$encypt."') AS gross,                                    
				AES_DECRYPT(s.basic, '".$encypt."') AS basic, 
				AES_DECRYPT(s.hra, '".$encypt."') AS hra,
				AES_DECRYPT(s.medical_allowance, '".$encypt."') AS medical_allowance,
				AES_DECRYPT(s.conveyance_allowance, '".$encypt."') AS conveyance_allowance,
				AES_DECRYPT(s.special_allowance, '".$encypt."') AS special_allowance,  m.*, u.* 
				FROM `internal_user` i 
				RIGHT JOIN `annual_appraisal` m ON m.login_id=i.login_id 
				LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id 
				LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id 
				LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
				LEFT JOIN `department` dp ON dp.dept_id=i.department WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' AND i.user_status='1'  AND s.salary_year = '".$e_year."' AND s.salary_month = '3' $cond";
			}
			//echo $empDetailsQry; exit();

			$empDetailsRes = $this->db->query($empDetailsQry);
			$empDetailsNum = count($empDetailsRes->result_array());
			$empDetailsInfo_res = $empDetailsRes->result_array();
			//var_dump($empDetailsInfo_res);exit;
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai =$total_score_b =$divisible =$totalEps =$totalExepf  =0;
				foreach($empDetailsInfo_res as $empDetailsInfo)
				{ 
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
					$expInAABSyS = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);
					$expTotal = $expInAABSyS + $empDetailsInfo['exp_others'];

					//Reporting Name
					$repMgrSql = "SELECT iu.full_name as rfull_name FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$empDetailsInfo['login_id']."'";
					$repMgrRes = $this->db->query($repMgrSql);
					$repMgrInfo = $repMgrRes->result_array();
					 // Reviewer Name
					$revSql="SELECT rev.full_name FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$empDetailsInfo["reporting_to"]." LIMIT 1";
					$revRes = $this->db->query($revSql);
					$revInfo = $revRes->result_array();
					
					if($empDetailsInfo['user_role'] < 5){
						$divisible =9;
					} else{
						$divisible =5;
					}
					
					$total_score_b =substr(($empDetailsInfo['knowledge_job_fin_rating']+$empDetailsInfo['quality_work_fin_rating']+$empDetailsInfo['quantity_work_fin_rating']+$empDetailsInfo['work_attitude_fin_rating']+$empDetailsInfo['teamwork_fin_rating']+$empDetailsInfo['problem_solving_fin_rating']+$empDetailsInfo['responsibility_fin_rating']+$empDetailsInfo['motivation_fin_rating']+$empDetailsInfo['delegation_work_fin_rating'])/$divisible, 0, 4); 
					
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);                            
						array_push($empSummary, $empDetailsInfo['name']);   
						array_push($empSummary, $empDetailsInfo['loginhandle']);               
						array_push($empSummary, $empDetailsInfo['desg_name']);                
						array_push($empSummary, $empDetailsInfo['dept_name']);                
						array_push($empSummary, $repMgrInfo[0]['rfull_name']);                
						array_push($empSummary, $revInfo[0]['full_name']);                
						array_push($empSummary, $empDetailsInfo['total_score_a']);                
						array_push($empSummary, $total_score_b);                
						array_push($empSummary, round($total_score_b));                
						array_push($empSummary, $empDetailsInfo['basic']);                
						array_push($empSummary, $empDetailsInfo['hra']);                
						array_push($empSummary, $empDetailsInfo['conveyance_allowance']);                
						array_push($empSummary, $empDetailsInfo['special_allowance']);                
						array_push($empSummary, $empDetailsInfo['medical_allowance']);                
						array_push($empSummary, $empDetailsInfo['gross']);                
						array_push($empSummary, $empDetailsInfo['ctc']);                
						array_push($empSummary, substr(($expInAABSyS/12), 0, 3).' Years');                
						array_push($empSummary, substr(($empDetailsInfo['exp_others']/12), 0, 3).' Years');                
						array_push($empSummary, substr(($expTotal/12), 0, 3).' Years');                
						array_push($empSummary, $empDetailsInfo['promotion']);                
						array_push($empSummary, $this->nl2html(str_replace('=','',trim($empDetailsInfo['action_plans']))));                
						$empSummaryArray[$ai++] = $empSummary;
					}

				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo){
						$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "annual_appraisal_report_".date("dmYHis").".xls"; 
			
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));
		 
			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Employee Report ' . date("M, Y"));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			//exit;
		} 
		
		$row = $this->Performance_model->get_annual_appraisal_report(); 
		//var_dump($result);
		$count = count($row);
		$progresssive=0;
		for($i=0; $i<$count; $i++)
		{  
		
			$regsal = $this->db->query("SELECT * FROM `goal_sheet` WHERE login_id = '".$row[$i]['login_id']."'");
			$res = $regsal->result_array(); 
			$mcount = count($res); 
			$progresssive = 0;
			for($m=0; $m < $mcount; $m++)
			{  
				if($res[$m]['rating']=='Progressing')
				{
					$progresssive++;
				} 
			} 
			if($row[$i]['user_role'] < 5)
			{
				$divisible = 9;
			} 
			else
			{
				$divisible = 5;
			} 
			$this->mViewData['total_score_b'] = substr(($row[$i]['knowledge_job_fin_rating']+$row[$i]['quality_work_fin_rating']+$row[$i]['quantity_work_fin_rating']+$row[$i]['work_attitude_fin_rating']+$row[$i]['teamwork_fin_rating']+$row[$i]['problem_solving_fin_rating']+$row[$i]['responsibility_fin_rating']+$row[$i]['motivation_fin_rating']+$row[$i]['delegation_work_fin_rating'])/$divisible, 0, 4); 
		}
		//template view
		$this->render('hr/performance_management/annual_appraisal_report_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/performance_management/annual_appraisal_report_script');
	}
	
	
	
	/**************************************  Annual appraisal report fro export year wise *********************************/
	/**************************************  Annual appraisal report fro export year wise *********************************/
	public function annual_appraisal_report_year_wise($e_year)
	{
			$encypt = $this->config->item('masterKey');
			$empDetailsQry = "";
			$this->load->library('PHPExcel');
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			//setting the values of the headers and data of the excel file 
			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";

			array_push($header, "Name");    
			$noOfColumnsSelected++;
			
			array_push($header, "Emp Code");     
			$noOfColumnsSelected++;

			array_push($header, "Designation");      
			$noOfColumnsSelected++;

			array_push($header, "Section A Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section A Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section A Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section A Score");      
			$noOfColumnsSelected++;

			array_push($header, "Section B Score");      
			$noOfColumnsSelected++;

			array_push($header, "Exp in POLOSOFT");      
			$noOfColumnsSelected++;

			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}
			
			
			$yy = date("Y");
			//$e_year= '2018';
			$cond =" AND DATE_FORMAT(m.apply_date,'%Y')=$e_year";
			if($empDetailsQry == "")
			{/* 
				$empDetailsQry = "SELECT i.login_id, i.loginhandle, i.reporting_to, i.join_date, ie.exp_others, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, dp.dept_name,
				AES_DECRYPT(s.ctc, '".$encypt."') AS ctc,
				AES_DECRYPT(s.gross, '".$encypt."') AS gross,                                    
				AES_DECRYPT(s.basic, '".$encypt."') AS basic, 
				AES_DECRYPT(s.hra, '".$encypt."') AS hra,
				AES_DECRYPT(s.medical_allowance, '".$encypt."') AS medical_allowance,
				AES_DECRYPT(s.conveyance_allowance, '".$encypt."') AS conveyance_allowance,
				AES_DECRYPT(s.special_allowance, '".$encypt."') AS special_allowance,  m.*, u.* 
				FROM `internal_user` i 
				RIGHT JOIN `annual_appraisal` m ON m.login_id=i.login_id 
				LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id 
				LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id 
				LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
				LEFT JOIN `department` dp ON dp.dept_id=i.department WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' 
				AND i.user_status='1'  AND s.salary_year = '".$e_year."' AND s.salary_month = '3' $cond  ORDER BY i.login_id ASC"; */
				/* $empDetailsQry = "SELECT i.login_id, i.loginhandle, i.reporting_to, i.join_date, ie.exp_others, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, dp.dept_name,
				 m.*, u.* 
				FROM `internal_user` i 
				RIGHT JOIN `annual_appraisal` m ON m.login_id=i.login_id 
				LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id 
				LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
				LEFT JOIN `department` dp ON dp.dept_id=i.department WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' 
				AND i.user_status='1' $cond  ORDER BY i.login_id ASC"; */
				
				$empDetailsQry = "SELECT i.login_id, i.loginhandle, i.reporting_to, i.join_date, ie.exp_others, CONCAT(name_first, ' ', name_last) AS name, i.designation, i.user_role, dp.dept_name,
				 u.* 
				FROM `internal_user` i 
				LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id 
				LEFT JOIN `user_desg` u ON u.desg_id=i.designation 
				LEFT JOIN `department` dp ON dp.dept_id=i.department WHERE  i.user_status='1'  AND i.login_id != '10010'  ORDER BY i.login_id ASC";
			}
			//echo $empDetailsQry; exit();

			$empDetailsRes = $this->db->query($empDetailsQry);
			$empDetailsNum = count($empDetailsRes->result_array());
			$empDetailsInfo_res = $empDetailsRes->result_array();
			//var_dump($empDetailsInfo_res);exit;
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai =$total_score_b =$divisible =$totalEps =$totalExepf  =0;
				foreach($empDetailsInfo_res as $empDetailsInfo)
				{ 
					$i++;
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo['join_date']));
					$expInAABSyS = $this->getDifferenceFromNow($empDetailsInfo['join_date'], 6);
					$expTotal = $expInAABSyS + $empDetailsInfo['exp_others'];

					//Reporting Name
					$repMgrSql = "SELECT iu.full_name as rfull_name FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$empDetailsInfo['login_id']."'";
					$repMgrRes = $this->db->query($repMgrSql);
					$repMgrInfo = $repMgrRes->result_array();
					 // Reviewer Name
					$revSql="SELECT rev.full_name FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$empDetailsInfo["reporting_to"]." LIMIT 1";
					$revRes = $this->db->query($revSql);
					$revInfo = $revRes->result_array();
					
					
					if($empDetailsInfo['user_role'] < 5){
						$divisible =9;
					} else{
						$divisible =5;
					}
					
					//$total_score_b =substr(($empDetailsInfo['knowledge_job_fin_rating']+$empDetailsInfo['quality_work_fin_rating']+$empDetailsInfo['quantity_work_fin_rating']+$empDetailsInfo['work_attitude_fin_rating']+$empDetailsInfo['teamwork_fin_rating']+$empDetailsInfo['problem_solving_fin_rating']+$empDetailsInfo['responsibility_fin_rating']+$empDetailsInfo['motivation_fin_rating']+$empDetailsInfo['delegation_work_fin_rating'])/$divisible, 0, 4); 
					
					if($processEmpSummaryArray)
					{ 
						//Employee summary array
						$empSummary = array();
						array_push($empSummary,$i);                            
						array_push($empSummary, $empDetailsInfo['name']);   
						array_push($empSummary, $empDetailsInfo['loginhandle']);               
						array_push($empSummary, $empDetailsInfo['desg_name']);  
						
						$login_id=$empDetailsInfo['login_id'];
						$empDetailsQry1 = "SELECT m.*
							FROM `annual_appraisal` m  WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' AND m.login_id = '$login_id' AND DATE_FORMAT(m.apply_date,'%Y')='2016'";
						$empDetailsRes1 = $this->db->query($empDetailsQry1);
						$empDetailsData = $empDetailsRes1->result_array();
						if(count($empDetailsData)>0){
							array_push($empSummary, $empDetailsData[0]['total_score_a']);                
							array_push($empSummary, $empDetailsData[0]['total_score_b']); 
						}
						else{
							array_push($empSummary, '0');                
							array_push($empSummary, '0');
						}
						
						$empDetailsQry1 = "SELECT m.*
							FROM `annual_appraisal` m  
							WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' AND m.login_id = '$login_id' AND DATE_FORMAT(m.apply_date,'%Y')='2017'";
						$empDetailsRes1 = $this->db->query($empDetailsQry1);
						$empDetailsData = $empDetailsRes1->result_array();
						if(count($empDetailsData)>0){
							array_push($empSummary, $empDetailsData[0]['total_score_a']);                
							array_push($empSummary, $empDetailsData[0]['total_score_b']); 
						}
						else{
							array_push($empSummary, '0');                
							array_push($empSummary, '0');
						}
						
						$empDetailsQry1 = "SELECT m.*
							FROM `annual_appraisal` m  
							WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' AND m.login_id = '$login_id' AND DATE_FORMAT(m.apply_date,'%Y')='2018'";
						$empDetailsRes1 = $this->db->query($empDetailsQry1);
						$empDetailsData = $empDetailsRes1->result_array();
						if(count($empDetailsData)>0){
							array_push($empSummary, $empDetailsData[0]['total_score_a']);                
							array_push($empSummary, $empDetailsData[0]['total_score_b']); 
						}
						else{
							array_push($empSummary, '0');                
							array_push($empSummary, '0');
						}

						
						$empDetailsQry1 = "SELECT m.*
							FROM `annual_appraisal` m  
							WHERE m.dh_status = '1' AND m.rm_status = '1' AND m.login_id != '10010' AND m.login_id = '$login_id' AND DATE_FORMAT(m.apply_date,'%Y')='2019'";
						$empDetailsRes1 = $this->db->query($empDetailsQry1);
						$empDetailsData = $empDetailsRes1->result_array();
						if(count($empDetailsData)>0){
							array_push($empSummary, $empDetailsData[0]['total_score_a']);                
							array_push($empSummary, $empDetailsData[0]['total_score_b']); 
						}
						else{
							array_push($empSummary, '0');                
							array_push($empSummary, '0');
						}

						
						array_push($empSummary, substr(($expInAABSyS/12), 0, 3).' Years');                
						$empSummaryArray[$ai++] = $empSummary;
					}

				}
			}

			  
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo){
						$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			 
			$filename = "annual_appraisal_report_".date("dmYHis").".xls"; 
			
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));
		 
			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('Employee Report ' . date("M, Y"));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			//exit;
		
	}
	public function get_annual_appraisal_report()
	{
		$result = $this->Performance_model->get_annual_appraisal_report(); 
		echo json_encode($result); 
	}
	public function get_annual_appraisal_report_search()
	{
		$searchYear = $this->input->post('searchYear');
		$result = $this->Performance_model->get_annual_appraisal_report_search($searchYear); 
		echo json_encode($result, true); 
	}
	/*End */
	/******** END/ Annual appraisal report ********/
	function nl2html($text)
{
    //$text =str_replace("="," ",$text);
    return '' . preg_replace(array('/(\r\n\r\n|\r\r|\n\n)(\s+)?/', '/\r\n|\r|\n/'),
            array('', ''), $text) . '';
}
	
	/*Start Ajax with angularjs function*/
	public function get_probation_assessment_all()
	{
		$result = $this->Performance_model->get_probation_assessment_all(); 
		echo json_encode($result); 
	}
	
	public function get_probation_assessment_search() 
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$cond = "";
		if($searchDepartment != ""){
			$cond .= "AND i.department = $searchDepartment ";
		}
		if($searchName != ""){
			$cond .= "AND i.name_first LIKE '%$searchName%' ";
		}
		if($searchDesignation != ""){
			$cond .= "AND i.designation = $searchDesignation ";
		}
		if($searchEmpCode != ""){
			$cond .= "AND i.loginhandle = '$searchEmpCode' ";
		}
		$sql = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.department, p.*,i.designation FROM `internal_user` i RIGHT JOIN `probation_assessment` p ON p.employee_id = i.login_id WHERE i.login_id != '10010' $cond";
		
		$qry_result = $this->db->query($sql);
		$result = $qry_result->result_array();
		echo json_encode($result); 
	}
	
	public function probation_assessment_print()
	{
		$this->mViewData['pageTitle']    = 'My annual appraisal';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$mid = $_GET['mid'];
		
		$qryAppraisal = "SELECT p.*,i.full_name,i.loginhandle,r.full_name as rmName,r.loginhandle as rmEmpid FROM `probation_assessment` p LEFT JOIN `internal_user` i ON i.login_id=p.employee_id LEFT JOIN `internal_user` r ON r.login_id=p.login_id  WHERE mid = '".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array();
		$this->mViewData['rowPA'] = $revRes->result_array();

		/* $cond = "DATE_FORMAT(annualdate,'%Y')='".date('Y',strtotime($rowAppraisal[0]['apply_date']))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array(); */
		//Template view
		$this->load->view('hr/performance_management/probation_detail', $this->mViewData);
	}
	
	/*******  Mid year appraisal report **********/
	public function get_midyear_appraisal_report()
	{
		$result = $this->Performance_model->get_midyear_appraisal_report(); 
		echo json_encode($result, true); 
	}
	public function get_midyear_appraisal_report_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Performance_model->get_midyear_appraisal_report_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	/*******  Mid year appraisal report **********/
	
	
	//End performance management
	/*Start Ajax with angularjs function*/
	public function get_phone_directory()
	{
		$result = $this->Loan_model->get_all_phone_no(); 
		echo json_encode($result); 
	}
	/*End */
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
	
	/***** training & developemnt ************/
	public function training_and_development()
	{
		$this->mViewData['pageTitle']    = 'Training & Development';
		//template view
		$this->render('hr/training_and_development_view', 'full_width',$this->mViewData);
	}
	
	
	/*****  employee details view/update   ****/
	
	public function general_readonly()
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
		
		$this->mViewData['separation'] = $this->Hr_model->get_separation_master();
		//$this->mViewData['locationHq] = $this->getValue(TAB_STATE,'state_name',"state_id='".$empInfo[0]['loc_highest_qual']."'");
		$this->render('hr/employee_management/profile_readonly_emp_view', 'full_width',$this->mViewData);
	}
	public function general_readonly_mark_inactive()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$fnf_status = $this->input->post('FnF_status');
		$emp_status_type = $this->input->post('emp_status_type');
		$HR_remark = $this->input->post('HR_remark');
		$this->Myprofile_model->update_mark_inactive($fnf_status,$emp_status_type,$HR_remark,$user_id); 
		
	}
	
	//Set Employee Last working Date
	public function general_readonly_set_lwd()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$lwdDate = date("Y-m-d", strtotime($this->input->post('lwd_date')));
		$this->Myprofile_model->update_last_working_date($lwdDate,$user_id);
	}
	
	//Set Employee Date of Resignation
	public function general_readonly_set_dor()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$resign_date = date("Y-m-d", strtotime($this->input->post('resign_date')));
		$resign_reason = $this->input->post('selReaSep');
		$this->Myprofile_model->update_date_of_resign($resign_date,$resign_reason,$user_id);
	}
	
	//Set EMployee Type
	public function general_readonly_set_emp_type()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$ddlTypeEmp = $this->input->post('ddlTypeEmp');
		$department = $this->input->post('department');
		$designation = $this->input->post('designation');
		$ddlDoj = date("Y-m-d", strtotime($this->input->post('ddlDoj')));
		
		if($this->input->post('ddlTypeEmp') == 'CO')
		{
			$employeeID = $this->generate_employee_code_consultant($ddlDoj, $ddlTypeEmp, $department);
		}
		else
		{
			$employeeID = $this->generate_employee_code($ddlDoj, $ddlTypeEmp);
		}
		
		
		$this->Myprofile_model->update_emp_type($employeeID,$ddlTypeEmp,$department,$designation,$ddlDoj,$user_id);
		
		//add email to Predeer & Rajesh
		// get details of new employee
		$newEmpSql = "SELECT i.login_id, i.email, i.loginhandle, i.full_name, i.join_date, d.dept_name, u.desg_name FROM internal_user i INNER JOIN `department` d ON d.dept_id = i.department INNER JOIN `user_desg` u ON u.desg_id = i.designation WHERE i.login_id = '$user_id'";
		$newEmpRes = $this->db->query($newEmpSql);
		$newEmpInfo = $newEmpRes->result_array();
			
		// subject
			$subject = " Employee Type Changes - ".$newEmpInfo[0]['full_name']." (".$newEmpInfo[0]['loginhandle'].")";
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$user_id."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$user_id."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$site_base_url=base_url('hr/profile_list');
				
				$message=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear HR,</p>                                 
				 <p>You created a new employee type in our POLOHRM System. Below are the details of that employee.</p>
				 <p><strong>Name :</strong></td><td>{$newEmpInfo[0]['full_name']}</td></p>
				 <p><strong>Employee Code :</strong>{$newEmpInfo[0]['loginhandle']}</p>
				 <p><strong>Date of Joining :</strong>{$newEmpInfo[0]['join_date']}</p>
				 <p><strong>Department :</strong>{$newEmpInfo[0]['dept_name']}</p>
				 <p><strong>Designation :</strong>{$newEmpInfo[0]['desg_name']}</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;


			$messageADMIN=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear Sir,</p>                                 
				 <p>A new employee is joined in our POLOHRM System. Below are the details of that employee.</p>
				 <p><strong>Name :</strong></td><td>{$newEmpInfo[0]['full_name']}</td></p>
				 <p><strong>Employee Code :</strong>{$newEmpInfo[0]['loginhandle']}</p>
				 <p><strong>Date of Joining :</strong>{$newEmpInfo[0]['join_date']}</p>
				 <p><strong>Department :</strong>{$newEmpInfo[0]['dept_name']}</p>
				 <p><strong>Designation :</strong>{$newEmpInfo[0]['desg_name']}</p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
			$to = 'HR <hr@polosoftech.com>';
			$tolalit = 'Lalit Tyagi <lalit.tyagi@polosoftech.com>';
			$tosaurav = 'Saurav Mohapatra <saurav.mohapatra@polosoftech.com>';
			$toadmin = 'POLOHRM Admin <polohrm@polosoftech.com>';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOHRM <polohrm@polosoftech.com>' . "\r\n";
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'Reply-To: polohrm@polosoftech.com,hr@polosoftech.com'. "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
			mail($tolalit, $subject, $messageADMIN, $headers);
			mail($tosaurav, $subject, $messageADMIN, $headers);
			mail($toadmin, $subject, $messageADMIN, $headers);
		
		
	}
	
	
	//Set Employee Promotion
	public function general_readonly_set_emp_promotion()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$department = $this->input->post('department');
		$designation = $this->input->post('designation');
		$promotion_date = date("Y-m-d", strtotime($this->input->post('promotion_date')));
		$this->Myprofile_model->update_emp_promotion($department,$designation,$promotion_date,$user_id);
	}
	
	//Update EMployee Profile(General Information)
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
						VALUES('$loginID','".$this->input->post('txtofficial_mobile')."','".$this->input->post('txtadharcard_no')."')";

				$this->db->query($insertIUESql);

			}

			// Update compass_user
			$updateCUSql = "UPDATE `compass_user` SET  `name` = '".$this->input->post('txtFullName')."' WHERE `ref_id` = '".$user_id."'";
			$this->db->query($updateCUSql);

			$successMsg = TRUE;
		}

		$empSql = "SELECT i.*,ie.*,u.desg_name, d.dept_name, r.full_name AS rmName, r.loginhandle AS rmECode, c.country_name AS country_name1
		FROM `internal_user` i 
		JOIN `internal_user` r ON r.login_id = i.reporting_to 
		LEFT JOIN `user_desg` u ON u.desg_id = i.designation 
		LEFT JOIN `department` d ON d.dept_id = i.department 
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
		
		$this->render('hr/employee_management/profile_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/datepicker');
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
		
		$desSql ="SELECT * FROM user_desg WHERE dept_id = ".$empInfo[0]["department"]."";
		$desRes = $this->db->query($desSql);
		$this->mViewData['desInfo'] = $desRes->result_array();
		
		$this->mViewData['department'] = $this->event_model->get_department(); 
		
		$this->render('hr/employee_management/comp_profile_readonly_emp_view', 'full_width',$this->mViewData);
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
				`confirm_status` = '".$this->input->post('rbConfm')."',
				`employee_conform` = '".$employee_conform."',
				`source_hire` = '".$this->input->post('ddlSrcHire')."',
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
		$this->mViewData['getReportingManager'] = $this->getReportingManager();		
		
		$this->render('hr/employee_management/comp_profile_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/company_js');
	}
	
	public function getReportingManager()
	{
		$reportingQrySelect = "SELECT i.login_id, CONCAT(i.full_name, ' (', i.loginhandle, ')') AS dispName FROM `internal_user` i WHERE i.login_id != '10010' AND i.user_status = '1' AND i.user_role < '5' ORDER BY i.full_name ASC";
		$reportingResSelect = $this->db->query($reportingQrySelect);
		$reportingInfoSelect = $reportingResSelect->result_array();
		$reportingArray = '';
		
		$reportingArray = substr($reportingArray, 1);

		return $reportingInfoSelect;
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
							   calculation_type, pf_no, uan_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name, s.acc_holder_name
					FROM `internal_user` AS i 
					LEFT JOIN `salary_info` AS s ON s.login_id = i.login_id 
					LEFT JOIN bank_master AS b ON b.bank_id = s.bank
					WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();


		$increSql = "SELECT AES_DECRYPT(increament, '".$encypt."') AS increament, year, increament_info_id FROM `emp_increament_info` WHERE login_id = '".$user_id."'";
		$increRes = $this->db->query($increSql);
		$this->mViewData['increRows'] = $increRes->result_array();

		$this->render('hr/employee_management/salary_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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
							   calculation_type, pf_no, uan_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name, b.bank_id, s.acc_holder_name
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

		$this->render('hr/employee_management/salary_profile_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/salary_js');
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
		$empSql = "SELECT i.full_name,i.user_status, i.designation FROM `internal_user` i WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		//print_r($empInfo );exit;
		$this->mViewData['empInfo'] = $empInfo;

		$eduSql = "SELECT e.*,i.full_name,i.user_status,c.course_name,c.course_type, b.board_university_name, s.specialization_name
				FROM `education_info` e  
				LEFT JOIN `internal_user` i ON e.login_id=i.login_id
				LEFT JOIN course_info c ON c.course_id=e.course_id 
				LEFT JOIN specialization_master s ON s.specialization_id = e.specialization_id 
				LEFT JOIN board_university_master  b ON b.board_university_id = e.board_id
				WHERE e.login_id = '".$user_id."'";
		$eduRes = $this->db->query($eduSql);
		$eduInfo = $eduRes->result_array();
		//var_dump($eduInfo);
		$this->mViewData['eduInfo']= $eduInfo;
		$eduRows=count($eduInfo);
		//print_r($eduInfo );exit;

		//Get Required Education for this employee
		$reqEduSQL = "SELECT c.course_name FROM minimum_requirement r LEFT JOIN course_info  c ON c.course_id = r.requirement_type_id WHERE r.designation_id = '".$empInfo[0]["designation"]."' AND r.requirement_type = 'E'";
		$reqEduRES = $this->db->query($reqEduSQL);
		$resEduRES = $reqEduRES->result_array();
		$this->mViewData['reqEduINFO'] = $resEduRES;
		$this->mViewData['reqEduNUM'] = count($resEduRES);
		//print_r($resEduRES );exit;
		$this->render('hr/employee_management/education_profile_readonly_emp_view', 'full_width',$this->mViewData);
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
			  $insertIUESql = "INSERT INTO `education_info` (login_id,course_id,specialization_id,passing_year,percentage,board_id)
							VALUES('".$user_id."','".$this->input->post('ddl_coursetype')."','".$this->input->post('ddl_specialization')."','".$this->input->post('txtpassing_year')."','".$this->input->post('txtpercentage')."','".$this->input->post('selBorU')."')";
			  $this->db->query($insertIUESql);
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
		 
		$this->render('hr/employee_management/education_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/education_js');
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
		
		$this->render('hr/employee_management/family_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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
					  
		$this->render('hr/employee_management/family_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/family_js');
	}
	
	public function reference_readonly_emp()
	{
		//session user_id store with page user_id
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();

		$refSql = "SELECT r.* FROM `emp_reference_info` AS r WHERE r.login_id = '".$user_id."'";
		$refRes = $this->db->query($refSql);
		$this->mViewData['refInfo'] = $refRes->result_array(); 

		$this->render('hr/employee_management/reference_readonly_emp_view', 'full_width',$this->mViewData); 
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
		$this->render('hr/employee_management/reference_update_emp_view', 'full_width',$this->mViewData);
		$this->load->view('script/myprofile/reference_js');		
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
			$dyear = date("Y")+1; 
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
		
		$this->render('hr/employee_management/job_description_readonly_emp_view', 'full_width',$this->mViewData); 
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
			redirect('/en/hr/job_description_readonly_emp?id='.$user_id);
		}
		
		$skillSQL = "SELECT skill_id, skill_name, skill_category FROM skills_master WHERE `status` = 'Y' ORDER BY skill_category";
		$skillRes = $this->db->query($skillSQL);
		$skillInfo = $skillRes->result_array();
		$this->mViewData['skillInfo']= $skillInfo;
		
		if(empty($this->input->post('year')))  $dyear = date("Y")+1; else $dyear = $this->input->post('year');
		$this->mViewData['dyear'] = $dyear;
		//echo  'Current'.$dyear;    
		$qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$user_id."' AND DATE_FORMAT(annualdate, '%Y') = ".$dyear;
		$qryRes = $this->db->query($qryGoal);
		$qryInfo = $qryRes->result_array();
		$this->mViewData['qryInfo'] = $qryInfo;
	
		if($this->input->post('btnUpdateGoal') == "Update"){
			for($i=0;$i<count($this->input->post('mid'));$i++){
				 if($this->input->post('mid')[$i] == ""){
					 $insGoalSql ="INSERT INTO `goal_sheet` SET login_id='".$user_id."',
					 objective = '".$this->input->post('objective')[$i]."',
                     target = '".$this->input->post('target')[$i]."',
					 weightage='".$this->input->post('weightage')[$i]."',
					 progress='".$this->input->post('progress')[$i]."',
					 annualdate= '".date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d')))."'";
				 }else {
					 $insGoalSql ="UPDATE `goal_sheet` SET objective = '".$this->input->post('objective')[$i]."', 
                            target = '".$this->input->post('target')[$i]."', 
							weightage= '".$this->input->post('weightage')[$i]."', 
							progress= '".$this->input->post('progress')[$i]."' 
							WHERE login_id='".$user_id."' AND mid='".$this->input->post('mid')[$i]."'";
				}
				$insGoalRes = $this->db->query($insGoalSql);
				header("Refresh:0");
			} 
			$insGoalSql = $this->db->query("DELETE from `goal_sheet` WHERE login_id='".$user_id."' AND mid NOT IN (".implode(",",$this->input->post("mid")).")");
		}
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->render('hr/employee_management/job_description_update_emp_view', 'full_width',$this->mViewData);
	}
	
	public function document_readonly_emp()
	{
		$this->mViewData['pageTitle']    = 'Document';
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();

		$joinSql = "SELECT d.*,j.kit_name FROM emp_document d JOIN  joining_kit_master j ON j.joining_kit_id =d.joining_kit_id WHERE d.login_id = '".$user_id."' AND j.status='Y'";
		$joinRes = $this->db->query($joinSql); 
		$this->mViewData['joinInfo'] = $joinRes->result_array(); 
		
		$this->render('hr/employee_management/document_readonly_emp_view', 'full_width',$this->mViewData);  
	}
	
	public function document_update_emp()
	{
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.loginhandle, i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		
		$kitSQL = "Select joining_kit_id,kit_name FROM joining_kit_master WHERE status = 'Y'";
		$kitRes = $this->db->query($kitSQL);
		$kitInfo = $kitRes->result_array();
		$this->mViewData['kitInfo'] = $kitInfo;
		
		
		$success = "";
		$error = "";
		if($this->input->post('btnUpdateDoc') == 'Save')
		{   for($j=0;$j<count($_FILES['file']['name']);$j++)
			{
				$docid='';
				$fname = explode(".", $_FILES['file']['name'][$j]); 
				$docid = $this->input->post('docid');
                for($k=0; $k<count($kitInfo); $k++)            
                {
                    if(strtolower($kitInfo[$k]['kit_name'])==strtolower($fname[0])){
                        $docid = $kitInfo[$k]['joining_kit_id'];
					}						
                }
				
				if($docid !=""){
					if($_FILES['file']['error'][$j] == 0 && $_FILES['file']['type'][$j] =='application/pdf')
					{                            					
						if(($_FILES['file']['name'][$j]) !=""){
							$_FILES['files']['name']     = $_FILES['file']['name'][$j];
							$_FILES['files']['type']     = $_FILES['file']['type'][$j];
							$_FILES['files']['tmp_name'] = $_FILES['file']['tmp_name'][$j];
							$_FILES['files']['error']     = $_FILES['file']['error'][$j];
							$_FILES['files']['size']     = $_FILES['file']['size'][$j];
							
							$path = $_FILES['file']['name'][$j];
							$filename = strtolower(str_replace(' ','',$empInfo[0]["loginhandle"] ."_".$fname[0]."_".date("YmdHis").'.'.pathinfo($path, PATHINFO_EXTENSION)));
							$config['upload_path'] = APPPATH.'../assets/upload/document/';
							$config['allowed_types'] = 'pdf';
							$config['file_name'] = $filename;
							// Load and initialize upload library
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							if($this->upload->do_upload('files')){
								$fileData = $this->upload->data();
								$jKitSql = "SELECT * FROM `emp_document` where joining_kit_id='".$docid."' and login_id='".$user_id."'";
								$res = $this->db->query($jKitSql);
								$jKitnum = count($res->result_array());
								if($jKitnum > 0)
								{
									$updateIUESql = "UPDATE `emp_document` SET document_name='".$filename."' ,status='Y' WHERE joining_kit_id='".$docid."' AND login_id =".$user_id;
									$this->db->query($updateIUESql);
								}
								else
								{
									$insertIUESql = "INSERT INTO `emp_document` (login_id,joining_kit_id,document_name,status) VALUES('".$user_id."','".$docid."','".$filename."','Y')";
									$this->db->query($insertIUESql);
								}
								$success = 'Uploaded Successfully';
							}
						}
					}
					else
					{
						echo $this->UploadException($_FILES['file']['error'][$j]); 
						$error = 'Something went Wrong';
					}
				}
		    } 
		} 
		
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->mViewData['pageTitle']    = 'Edit Documents';
		$this->render('hr/employee_management/document_update_emp_view', 'full_width',$this->mViewData);
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
		$reqExperience = "Not Defined";
		$reqExpSQL = "SELECT e.experience_name FROM minimum_requirement AS r INNER JOIN experience_master AS e ON e.experience_id = r.requirement_type_id WHERE r.designation_id = ".$empInfo[0]['designation']." AND r.requirement_type = 'W' LIMIT 1";
		$reqExpRES = $this->db->query($reqExpSQL);
		//echo $reqExpSQL;
		$reqExpINFO = $reqExpRES->result_array();
		$reqExpNUM = count($reqExpINFO); 
		//var_dump($reqExpINFO);
		$this->mViewData['reqExpINFO'] = $reqExpRES->result_array();
		//$this->mViewData['reqExperience'] = $reqExpINFO[0]["experience_name"];
		/* if($reqExpNUM == 1)
		{
			$this->mViewData['reqExpINFO'] = $reqExpRES->result_array();
			$this->mViewData['reqExperience'] = $reqExpINFO[0]["experience_name"];
		} */
		
		$this->render('hr/employee_management/exp_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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

		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();

		$this->mViewData['expInAABSyS'] = $this->getDifferenceFromNow($empInfo[0]['join_date'], 6);

		$expSql = "SELECT e.*  FROM `exp_info` e WHERE e.login_id = '".$user_id."'";
		$expRes = $this->db->query($expSql);
		$this->mViewData['expRes_arr'] = $expRes->result_array();
		$expRows=count($expRes->result_array());
		
		$this->render('hr/employee_management/exp_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/experience_js');
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
		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		
		$letterSQL = "SELECT l.letter_id, l.letter_name, l.letter_document, l.issued_date FROM emp_letter AS l WHERE l.login_id = " . $user_id;
		$letterRES = $this->db->query($letterSQL);
		$letterRow = $letterRES->result_array(); 
		$this->mViewData['joinInfo'] = $letterRow; 
		
		$this->render('hr/employee_management/letter_issued_readonly_emp_view', 'full_width',$this->mViewData);  
	}
	
	public function letter_issued_update_emp()
	{
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		$empSql = "SELECT i.full_name, i.join_date, i.user_status, i.designation, ie.exp_others FROM `internal_user` AS i LEFT JOIN `internal_user_ext` AS ie ON i.login_id = ie.login_id WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empInfo'] = $empRes->result_array();
		
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
		$this->render('hr/employee_management/letter_issued_update_emp_view', 'full_width',$this->mViewData);
	}
	
	
	
	/****************************  Master Module ********************************/
	public function master_payroll_access(){
		$this->mViewData['pageTitle']    = 'Master Payroll Access ';
		$employee = $this->Hr_model->get_all_active_employee();
		$this->mViewData['employee'] = $employee;
		$PayRollSQL = $this->db->query('SELECT * FROM master_paroll_access WHERE status = "1"');
		$PayRollRes = $PayRollSQL->result_array();
		$this->mViewData['payroll_access'] = $PayRollRes;
		$this->render('hr/master/payroll_access_view', 'full_width',$this->mViewData);
	}
	
	public function add_payroll_access(){
		$employee = $this->input->post('employee');
		if($this->input->post('controller') == 1){
			$sql = $this->db->query("INSERT INTO `master_paroll_access` (emp_code) values ('".$employee."')");
		} else if($this->input->post('controller') > 1){
			$sql = $this->db->query("UPDATE `master_paroll_access` SET emp_code = '".$employee."' WHERE aid = '".$this->input->post('controller')."'");
		}
	}
	
	public function delete_payroll_access(){
		$payroll_id = $this->input->post('payroll');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM master_paroll_access WHERE aid = '.$payroll_id);
	}
	
	public function master_country(){
		$this->mViewData['pageTitle']    = 'Master Country';
		$countrySQL = $this->db->query('SELECT * FROM country WHERE country_status = "1" ORDER BY country_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['country'] = $countryRes;
		$this->render('hr/master/country_view', 'full_width',$this->mViewData);
	}
	
	public function add_country(){
		$country_name = $this->input->post('country_name');
		if($this->input->post('country_id') == 0){
			$sql = $this->db->query("INSERT INTO `country` (country_name,country_sort_order,country_status) values ('".$country_name."','0','1')");
		} else if($this->input->post('country_id') > 0){
			$sql = $this->db->query("UPDATE `country` SET country_name = '".$country_name."' WHERE country_id = '".$this->input->post('country_id')."'");
		}
	}
	
	public function delete_country(){
		$country_id = $this->input->post('country_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM country WHERE country_id = '.$country_id);
	}
	
	public function master_state(){
		$this->mViewData['pageTitle']    = 'Master State';
		$countrySQL = $this->db->query('SELECT * FROM country WHERE country_status = "1" ORDER BY country_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['country'] = $countryRes;
		$countrySQL = $this->db->query('SELECT state.*,country.country_name  FROM state INNER JOIN country ON country.country_id = state.country_id WHERE state.state_status = "1" ORDER BY state.state_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['state'] = $countryRes;
		$this->render('hr/master/state_view', 'full_width',$this->mViewData);
	}
	
	public function add_state(){
		$state_name = $this->input->post('state_name');
		$country_id = $this->input->post('country_id');
		if($this->input->post('state_id') == 0){
			$sql = $this->db->query("INSERT INTO `state` (country_id,state_name,state_sort_order,state_status) values ('".$country_id."','".$state_name."','0','1')");
		} else if($this->input->post('state_id') > 0){
			$sql = $this->db->query("UPDATE `state` SET country_id = '".$country_id."', state_name = '".$state_name."' WHERE state_id = '".$this->input->post('state_id')."'");
		}
	}
	
	public function delete_state(){
		$state_id = $this->input->post('state_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM state WHERE state_id = '.$state_id);
	}
	
	public function master_company_location(){
		$this->mViewData['pageTitle']    = 'Master Company Location';
		$countrySQL = $this->db->query('SELECT * FROM company_branch WHERE status = "A" ORDER BY branch_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/location_view', 'full_width',$this->mViewData);
	}
	
	public function add_company_location(){
		$branch_name = $this->input->post('branch_name');
		if($this->input->post('branch_id') == 0){
			$sql = $this->db->query("INSERT INTO `company_branch` (branch_name,status) values ('".$branch_name."','A')");
		} else if($this->input->post('branch_id') > 0){
			$sql = $this->db->query("UPDATE `company_branch` SET branch_name = '".$branch_name."' WHERE branch_id = '".$this->input->post('branch_id')."'");
		}
	}
	
	public function delete_company_location(){
		$branch_id = $this->input->post('branch_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM company_branch WHERE branch_id = '.$branch_id);
	}
	
	public function master_department(){
		$this->mViewData['pageTitle']    = 'Master Department';
		$countrySQL = $this->db->query('SELECT * FROM department WHERE dept_status = "1" ORDER BY dept_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['departments'] = $countryRes;
		$this->render('hr/master/department_view', 'full_width',$this->mViewData);
	}
	
	public function add_department(){
		$dept_name = $this->input->post('dept_name');
		if($this->input->post('dept_id') == 0){
			$sql = $this->db->query("INSERT INTO `department` (dept_name,dept_status,dept_sort_order,login_id) values ('".$dept_name."','1','0','')");
		} else if($this->input->post('dept_id') > 0){
			$sql = $this->db->query("UPDATE `department` SET dept_name = '".$dept_name."' WHERE dept_id = '".$this->input->post('dept_id')."'");
		}
	}
	
	public function delete_department(){
		$dept_id = $this->input->post('dept_id');
		$sqlRes = $this->db->query('DELETE FROM department WHERE dept_id = '.$dept_id);
	}
	
	public function master_designation(){
		$this->mViewData['pageTitle']    = 'Master State';
		$deptSQL = $this->db->query('SELECT * FROM department WHERE dept_status = "1" ORDER BY dept_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['departments'] = $deptRes;
		$deptSQL = $this->db->query('SELECT * FROM grade_mater WHERE status = "Y" ORDER BY grade_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['grades'] = $deptRes;
		$countrySQL = $this->db->query('SELECT user_desg.*,department.dept_name  FROM user_desg INNER JOIN department ON department.dept_id = user_desg.dept_id WHERE user_desg.desg_status = "1" ORDER BY department.dept_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['designations'] = $countryRes;
		$this->render('hr/master/designation_view', 'full_width',$this->mViewData);
	}
	
	public function add_designation(){
		$desg_name = $this->input->post('desg_name');
		$dept_id = $this->input->post('dept_id');
		if($this->input->post('desg_id') == 0){
			$sql = $this->db->query("INSERT INTO `user_desg` (desg_name,dept_id,desg_status,desg_sort_order) values ('".$desg_name."','".$dept_id."','1','0')");
		} else if($this->input->post('desg_id') > 0){
			$sql = $this->db->query("UPDATE `user_desg` SET dept_id = '".$dept_id."', desg_name = '".$desg_name."' WHERE desg_id = '".$this->input->post('desg_id')."'");
		}
	}
	
	public function delete_designation(){
		$desg_id = $this->input->post('desg_id');
		$sqlRes = $this->db->query('DELETE FROM user_desg WHERE desg_id = '.$desg_id);
	}
	
	public function master_skills(){
		$this->mViewData['pageTitle']    = 'Master Skills';
		$countrySQL = $this->db->query('SELECT skills_master.*  FROM skills_master WHERE skills_master.status = "Y" ORDER BY skills_master.skill_category ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['skills'] = $countryRes;
		$this->render('hr/master/skills_view', 'full_width',$this->mViewData);
	}
	
	public function add_skills(){
		$skill_name = $this->input->post('skill_name');
		$skill_category = $this->input->post('skill_category');
		if($this->input->post('skill_id') == 0){
			$sql = $this->db->query("INSERT INTO `skills_master` (skill_name,skill_category,status) values ('".$skill_name."','".$skill_category."','Y')");
		} else if($this->input->post('skill_id') > 0){
			$sql = $this->db->query("UPDATE `skills_master` SET skill_name = '".$skill_name."', skill_category = '".$skill_category."' WHERE skill_id = '".$this->input->post('skill_id')."'");
		}
	}
	
	public function delete_skills(){
		$skill_id = $this->input->post('skill_id');
		$sqlRes = $this->db->query('DELETE FROM skills_master WHERE skill_id = '.$skill_id);
	}
	
	public function master_grade(){
		$this->mViewData['pageTitle']    = 'Master Grade';
		$deptSQL = $this->db->query('SELECT * FROM level_master WHERE status = "Y" ORDER BY level_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['levels'] = $deptRes;
		$countrySQL = $this->db->query('SELECT grade_mater.*,level_master.level_name  FROM grade_mater INNER JOIN level_master ON level_master.level_id = grade_mater.level_id WHERE grade_mater.status = "Y" ORDER BY level_master.level_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['grades'] = $countryRes;
		$this->render('hr/master/grade_view', 'full_width',$this->mViewData);
	}
	
	public function add_grade(){
		$grade_name = $this->input->post('grade_name');
		$level_id = $this->input->post('level_id');
		if($this->input->post('grade_id') == 0){
			$sql = $this->db->query("INSERT INTO `grade_mater` (grade_name,level_id,status) values ('".$grade_name."','".$level_id."','Y')");
		} else if($this->input->post('grade_id') > 0){
			$sql = $this->db->query("UPDATE `grade_mater` SET grade_name = '".$grade_name."', level_id = '".$level_id."' WHERE grade_id = '".$this->input->post('grade_id')."'");
		}
	}
	
	public function delete_grade(){
		$grade_id = $this->input->post('grade_id');
		$sqlRes = $this->db->query('DELETE FROM grade_mater WHERE grade_id = '.$grade_id);
	}
	
	public function master_level(){
		$this->mViewData['pageTitle']    = 'Master Level';
		$countrySQL = $this->db->query('SELECT level_master.*  FROM level_master WHERE level_master.status = "Y" ORDER BY level_master.level_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['levels'] = $countryRes;
		$this->render('hr/master/level_view', 'full_width',$this->mViewData);
	}
	
	public function add_level(){
		$level_name = $this->input->post('level_name');
		if($level_name  !=""){
			if($this->input->post('level_id') == 0){
				$sql = $this->db->query("INSERT INTO `level_master` (level_name,status) values ('".$level_name."','Y')");
			} else if($this->input->post('level_id') > 0){
				$sql = $this->db->query("UPDATE `level_master` SET level_name = '".$level_name."' WHERE level_id = '".$this->input->post('level_id')."'");
			}
		}
	}
	
	public function delete_level(){
		$level_id = $this->input->post('level_id');
		$sqlRes = $this->db->query('DELETE FROM level_master WHERE level_id = '.$level_id);
	}
	
	public function master_education(){
		$this->mViewData['pageTitle']    = 'Master Grade';
		$countrySQL = $this->db->query('SELECT course_info.*  FROM course_info WHERE course_info.status = "Y" ORDER BY course_info.course_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['grades'] = $countryRes;
		$this->render('hr/master/education_view', 'full_width',$this->mViewData);
	}
	
	public function add_education(){
		$course_name = $this->input->post('course_name');
		$course_type = $this->input->post('course_type');
		$course_category = $this->input->post('course_category');
		$level_id = $this->input->post('level_id');
		if($this->input->post('course_id') == 0){
			$sql = $this->db->query("INSERT INTO `course_info` (course_name,course_type,course_category,status) values ('".$course_name."','".$course_type."','".$course_category."','Y')");
		} else if($this->input->post('course_id') > 0){
			$sql = $this->db->query("UPDATE `course_info` SET course_name = '".$course_name."', course_type = '".$course_type."', course_category = '".$course_category."' WHERE course_id = '".$this->input->post('course_id')."'");
		}
	}
	
	public function delete_education(){
		$course_id = $this->input->post('course_id');
		$sqlRes = $this->db->query('DELETE FROM course_info WHERE course_id = '.$course_id);
	}
	
	public function master_specialization(){
		$this->mViewData['pageTitle']    = 'Master Specialization';
		$deptSQL = $this->db->query('SELECT course_id,course_name FROM course_info WHERE status = "Y" ORDER BY course_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['courses'] = $deptRes;
		$countrySQL = $this->db->query('SELECT specialization_master.*,course_info.course_name  FROM specialization_master INNER JOIN course_info ON course_info.course_id = specialization_master.course_id WHERE specialization_master.status = "Y" ORDER BY specialization_master.specialization_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['specialization'] = $countryRes;
		$this->render('hr/master/specialization_view', 'full_width',$this->mViewData);
	}
	
	public function add_specialization(){
		$specialization_name = $this->input->post('specialization_name');
		$course_id = $this->input->post('course_id');
		if($this->input->post('specialization_id') == 0){
			$sql = $this->db->query("INSERT INTO `specialization_master` (course_id,specialization_name,status) values ('".$course_id."','".$specialization_name."','Y')");
		} else if($this->input->post('specialization_id') > 0){
			$sql = $this->db->query("UPDATE `specialization_master` SET specialization_name = '".$specialization_name."', course_id = '".$course_id."' WHERE specialization_id = '".$this->input->post('specialization_id')."'");
		}
	}
	
	public function delete_specialization(){
		$specialization_id = $this->input->post('specialization_id');
		$sqlRes = $this->db->query('DELETE FROM specialization_master WHERE specialization_id = '.$specialization_id);
	}
	
	public function master_board_university(){
		$this->mViewData['pageTitle']    = 'Master Board/University';
		$countrySQL = $this->db->query('SELECT board_university_master.*  FROM board_university_master WHERE board_university_master.status = "Y" ORDER BY board_university_master.board_university_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['boards'] = $countryRes;
		$this->render('hr/master/board_university_view', 'full_width',$this->mViewData);
	}
	
	public function add_board_university(){
		$board_university_name = $this->input->post('board_university_name');
		if($board_university_name  !=""){
			if($this->input->post('board_university_id') == 0){
				$sql = $this->db->query("INSERT INTO `board_university_master` (board_university_name,status) values ('".$board_university_name."','Y')");
			} else if($this->input->post('board_university_id') > 0){
				$sql = $this->db->query("UPDATE `board_university_master` SET board_university_name = '".$board_university_name."' WHERE board_university_id = '".$this->input->post('board_university_id')."'");
			}
		}
	}
	
	public function delete_board_university(){
		$board_university_id = $this->input->post('board_university_id');
		$sqlRes = $this->db->query('DELETE FROM board_university_master WHERE board_university_id = '.$board_university_id);
	}
	
	public function master_experience(){
		$this->mViewData['pageTitle']    = 'Master Experience';
		$countrySQL = $this->db->query('SELECT experience_master.*  FROM experience_master WHERE experience_master.status = "Y" ORDER BY experience_master.experience_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['experiences'] = $countryRes;
		$this->render('hr/master/experience_view', 'full_width',$this->mViewData);
	}
	
	public function add_experience(){
		$experience_name = $this->input->post('experience_name');
		if($experience_name  !=""){
			if($this->input->post('experience_id') == 0){
				$sql = $this->db->query("INSERT INTO `experience_master` (experience_name,status) values ('".$experience_name."','Y')");
			} else if($this->input->post('experience_id') > 0){
				$sql = $this->db->query("UPDATE `experience_master` SET experience_name = '".$experience_name."' WHERE experience_id = '".$this->input->post('experience_id')."'");
			}
		}
	}
	
	public function delete_experience(){
		$experience_id = $this->input->post('experience_id');
		$sqlRes = $this->db->query('DELETE FROM experience_master WHERE experience_id = '.$experience_id);
	}
	
	public function master_joining_kit(){
		$this->mViewData['pageTitle']    = 'Master Joining Kit';
		$countrySQL = $this->db->query('SELECT joining_kit_master.*  FROM joining_kit_master WHERE joining_kit_master.status = "Y" ORDER BY joining_kit_master.kit_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['experiences'] = $countryRes;
		$this->render('hr/master/joining_kit_view', 'full_width',$this->mViewData);
	}
	
	public function add_joining_kit(){
		$kit_name = $this->input->post('joining_kit_name');
		if($kit_name  !=""){
			if($this->input->post('joining_kit_id') == 0){
				$sql = $this->db->query("INSERT INTO `joining_kit_master` (kit_name,status) values ('".$kit_name."','Y')");
			} else if($this->input->post('joining_kit_id') > 0){
				$sql = $this->db->query("UPDATE `joining_kit_master` SET kit_name = '".$kit_name."' WHERE joining_kit_id = '".$this->input->post('joining_kit_id')."'");
			}
		}
	}
	
	public function delete_joining_kit(){
		$joining_kit_id = $this->input->post('joining_kit_id');
		$sqlRes = $this->db->query('DELETE FROM joining_kit_master WHERE joining_kit_id = '.$joining_kit_id);
	}
	
	public function emp_minrequirement(){
		$this->mViewData['pageTitle']    = 'Master Requirement';
		$deptSQL = $this->db->query('SELECT * FROM department WHERE dept_status = "1" ORDER BY dept_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['departments'] = $deptRes;
		$countrySQL = $this->db->query('SELECT user_desg.*,department.dept_name  FROM user_desg INNER JOIN department ON department.dept_id = user_desg.dept_id WHERE user_desg.desg_status = "1" ORDER BY department.dept_name ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['designations'] = $countryRes;
		$refSQL = $this->db->query('SELECT * FROM skills_master where status="Y"');
		$refRes = $refSQL->result_array();
		$this->mViewData['references'] = $refRes;
		$countrySQL = $this->db->query('SELECT r.*,u.desg_name,d.dept_name,u.desg_id,d.dept_id 
		FROM minimum_requirement r LEFT JOIN user_desg u ON u.desg_id=r.designation_id LEFT JOIN department d ON d.dept_id=u.dept_id ORDER BY r.required_id DESC');
		$countryRes = $countrySQL->result_array();
		$data_arr = array();
		foreach($countryRes as $v1){
			$requirement_type = $v1['requirement_type'];
			if($requirement_type =='S')
			{
				 $refqry = "SELECT skill_id as id,skill_name as name FROM skills_master where skill_id IN (".$v1['requirement_type_id'].") ";
			}
			else if($requirement_type =='E')
			{
				 $refqry = "SELECT course_id as id,course_name as name FROM course_info  where course_id IN (".$v1['requirement_type_id'].")";
			}
			else if($requirement_type =='W')
			{
				 $refqry = "SELECT experience_id as id,experience_name as name FROM experience_master  where experience_id IN (".$v1['requirement_type_id'].") ";
			}
			$sql = $this->db->query($refqry); 
			$result = $sql->result_array();
			
			$data_arr[] = array(
				'dept_id' => $v1['dept_id'],
				'dept_name' => $v1['dept_name'],
				'desg_id' => $v1['desg_id'],
				'desg_name' => $v1['desg_name'],
				'required_id' => $v1['required_id'],
				'designation_id' => $v1['designation_id'],
				'requirement_type' => $v1['requirement_type'],
				'requirement_type_id' => $v1['requirement_type_id'],
				'requirement_types' => $result,
			
			);
			
		}
		$this->mViewData['requirements'] = $data_arr;
		$this->render('hr/master/emp_minrequirement_view', 'full_width',$this->mViewData);
	}
	
	public function add_emp_minrequirement(){
		$dept_id = $this->input->post('dept_id');
		$desg_id = $this->input->post('desg_id');
		$ddl_reftype = $this->input->post('ddl_reftype');
		$required_id = $this->input->post('required_id');
		//$ddl_ref = $this->input->post('ddl_ref');
		$ddl_ref = implode(",",$this->input->post("ddl_ref"));
		if($desg_id  !="" && $ddl_reftype  !="" && $ddl_ref  !=""){
			if($this->input->post('required_id') == 0){
				$sql = $this->db->query("INSERT INTO `minimum_requirement` (designation_id,requirement_type,requirement_type_id)
				    VALUES('".$desg_id."','".$ddl_reftype."','".$ddl_ref."')");
			} else if($this->input->post('required_id') > 0){
				$sql = $this->db->query("UPDATE `minimum_requirement` SET designation_id='".$desg_id."',
						requirement_type='".$ddl_reftype."',requirement_type_id='".$ddl_ref."'
						WHERE required_id='".$required_id."'");
			}
		}
	}
	
	public function delete_emp_minrequirement(){
		$required_id = $this->input->post('required_id');
		$sqlRes = $this->db->query('DELETE FROM minimum_requirement WHERE required_id = '.$required_id);
	}
	public function get_requirement_types(){
		$requirement_type = $this->input->post('requirement_type');
		$result = $this->Hr_model->get_requirement_types($requirement_type);
		echo json_encode($result); 
	}
	
	
	public function master_hod(){
		$this->mViewData['pageTitle']    = 'Master Payroll Access ';
		$deptSQL = $this->db->query('SELECT * FROM department WHERE dept_status = "1" ORDER BY dept_name ASC');
		$deptRes = $deptSQL->result_array();
		$this->mViewData['departments'] = $deptRes;
		$employee = $this->Hr_model->get_all_active_employee();
		$this->mViewData['employee'] = $employee;
		$PayRollSQL = $this->db->query("SELECT d.dept_id, d.dept_name,i.login_id, i.loginhandle,i.full_name FROM `department` d LEFT JOIN `internal_user` i ON d.login_id = i.login_id WHERE d.dept_status = '1'");
		$PayRollRes = $PayRollSQL->result_array();
		$this->mViewData['payroll_access'] = $PayRollRes;
		$this->render('hr/master/hod_view', 'full_width',$this->mViewData);
	}
	
	public function add_hod(){
		$login_id = $this->input->post('login_id');
		$dept_id = $this->input->post('dept_id');
		if($this->input->post('dept_id') != "" && $this->input->post('login_id') != "" ){
			$sql = $this->db->query("UPDATE `department` SET dept_head = '".$login_id."' , login_id = '".$login_id."' WHERE dept_id = '".$dept_id."'");
		} 
	}
	
	public function delete_hod(){
		$dept_id = $this->input->post('dept_id');
		//echo $payroll_id; exit;
		//$sqlRes = $this->db->query('DELETE FROM department WHERE dept_id = '.$dept_id);
		$sql = $this->db->query("UPDATE `department` SET dept_head = '0' , login_id = '0' WHERE dept_id = '".$dept_id."'");
	}
	
	public function master_separation(){
		$this->mViewData['pageTitle']    = 'Master Company Location';
		$countrySQL = $this->db->query('SELECT * FROM separation_master WHERE status = "Y" ORDER BY separation_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/separation_view', 'full_width',$this->mViewData);
	}
	
	public function add_separation(){
		$separation_name = $this->input->post('separation_name');
		if($separation_name !=""){
			if($this->input->post('separation_id') == 0){
				$sql = $this->db->query("INSERT INTO `separation_master` (separation_name,status) values ('".$separation_name."','Y')");
			} else if($this->input->post('separation_id') > 0){
				$sql = $this->db->query("UPDATE `separation_master` SET separation_name = '".$separation_name."' WHERE separation_id = '".$this->input->post('separation_id')."'");
			}
		}
	}
	
	public function delete_separation(){
		$separation_id = $this->input->post('separation_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM separation_master WHERE separation_id = '.$separation_id);
	}
	
	public function master_source_hire(){
		$this->mViewData['pageTitle']    = 'Master source hire';
		$countrySQL = $this->db->query('SELECT * FROM source_hire_master WHERE status = "Y" ORDER BY source_hire_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/source_hire_view', 'full_width',$this->mViewData);
	}
	
	public function add_source_hire(){
		$source_hire_name = $this->input->post('source_hire_name');
		if($source_hire_name !=""){
			if($this->input->post('source_hire_id') == 0){
				$sql = $this->db->query("INSERT INTO `source_hire_master` (source_hire_name,status) values ('".$source_hire_name."','Y')");
			} else if($this->input->post('source_hire_id') > 0){
				$sql = $this->db->query("UPDATE `source_hire_master` SET source_hire_name = '".$source_hire_name."' WHERE source_hire_id = '".$this->input->post('source_hire_id')."'");
			}
		}
	}
	
	public function delete_source_hire(){
		$source_hire_id = $this->input->post('source_hire_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM source_hire_master WHERE source_hire_id = '.$source_hire_id);
	}
	
	public function master_bank(){
		$this->mViewData['pageTitle']    = 'Master bank';
		$countrySQL = $this->db->query('SELECT * FROM bank_master WHERE status = "Y" ORDER BY bank_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/bank_view', 'full_width',$this->mViewData);
	}
	
	public function add_bank(){
		$bank_name = $this->input->post('bank_name');
		if($bank_name !=""){
			if($this->input->post('bank_id') == 0){
				$sql = $this->db->query("INSERT INTO `bank_master` (bank_name,status) values ('".$bank_name."','Y')");
			} else if($this->input->post('bank_id') > 0){
				$sql = $this->db->query("UPDATE `bank_master` SET bank_name = '".$bank_name."' WHERE bank_id = '".$this->input->post('bank_id')."'");
			}
		}
	}
	
	public function delete_bank(){
		$bank_id = $this->input->post('bank_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM bank_master WHERE bank_id = '.$bank_id);
	}
	
	public function master_miscellaneous(){
		$this->mViewData['pageTitle']    = 'Master miscellaneous';
		$countrySQL = $this->db->query('SELECT * FROM miscellaneous_mater WHERE status = "Y" ORDER BY miscellaneous_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/miscellaneous_view', 'full_width',$this->mViewData);
	}
	
	public function add_miscellaneous(){
		//$miscellaneous_item = $this->input->post('miscellaneous_item');
		$miscellaneous_value = $this->input->post('miscellaneous_value');
		/* if($this->input->post('miscellaneous_id') == 0){
			$sql = $this->db->query("INSERT INTO `miscellaneous_mater` (miscellaneous_value,status) values ('".$miscellaneous_value."','Y')");
		} else */ if($this->input->post('miscellaneous_id') > 0){
			$sql = $this->db->query("UPDATE `miscellaneous_mater` SET  miscellaneous_value = '".$miscellaneous_value."' WHERE miscellaneous_id = '".$this->input->post('miscellaneous_id')."'");
		}
	}
	
	public function delete_miscellaneous(){
		$miscellaneous_id = $this->input->post('miscellaneous_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM miscellaneous_mater WHERE miscellaneous_id = '.$miscellaneous_id);
	}
	
	public function email_category(){
		$this->mViewData['pageTitle']    = 'Master Email Template Category';
		$countrySQL = $this->db->query('SELECT * FROM email_category WHERE cstatus = "1" ORDER BY cid ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/email_category_view', 'full_width',$this->mViewData);
	}
	
	public function add_email_category(){
		$cname = $this->input->post('cname');
		if($cname != ""){
			if($this->input->post('cid') == 0){
				$sql = $this->db->query("INSERT INTO `email_category` (cname,cstatus) values ('".$cname."','1')");
			} else if($this->input->post('cid') > 0){
				$sql = $this->db->query("UPDATE `email_category` SET  cname = '".$cname."' WHERE cid = '".$this->input->post('cid')."'");
			}
		}
	}
	
	public function delete_email_category(){
		$cid = $this->input->post('cid');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM email_category WHERE cid = '.$cid);
	}
	
	public function email_template(){
		$this->mViewData['pageTitle']    = 'Master Email Templates';
		$countrySQL = $this->db->query('SELECT * FROM email_category WHERE cstatus = "1" ORDER BY cid ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['category'] = $countryRes;
		$countrySQL = $this->db->query('SELECT t.*,c.cname FROM email_templates t LEFT JOIN email_category c ON c.cid=t.cid WHERE t.status = "Y" ORDER BY t.form_id ASC');
		$countryRes = $countrySQL->result_array();
		$this->mViewData['branches'] = $countryRes;
		$this->render('hr/master/email_template_view', 'full_width',$this->mViewData);
	}
	
	public function add_email_template(){
		//print_r($_POST);
		$cid = $this->input->post('cid');
		$form_name = $this->input->post('form_name');
		$content = $this->input->post('content');
		if($this->input->post('form_id') == 0){
			$sql = $this->db->query("INSERT INTO `email_templates` (cid,form_name,content,status) values ('".$cid."','".$form_name."','".$content."','Y')");
		} else if($this->input->post('form_id') > 0){
			$sql = $this->db->query("UPDATE `email_templates` SET  cid = '".$cid."' , form_name = '".$form_name."'  content = '".$content."' WHERE form_id = '".$this->input->post('form_id')."'");
		}
	}
	
	public function delete_email_template(){
		$form_id = $this->input->post('form_id');
		//echo $payroll_id; exit;
		$sqlRes = $this->db->query('DELETE FROM email_templates WHERE form_id = '.$form_id);
	}
	/****************************  END/ Master Module ********************************/
	
	/****************************  Events ********************************/
	public function emp_retired_details()
	{
		$this->mViewData['pageTitle']    = 'Retired Employee Details';
		$this->render('hr/events/emp_retired_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/emp_retired_details_script');
	}
	
	public function get_emp_retired_details(){
		$result = $this->Hr_model->get_emp_retired_details();
		echo json_encode($result); 
	}
	
	public function get_emp_retired_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_emp_retired_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function emp_terminated_details()
	{
		$this->mViewData['pageTitle']    = 'Terminated Employee Details';
		$this->render('hr/events/emp_terminated_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/emp_terminated_details_script');
	}
	
	public function get_emp_terminated_details(){
		$result = $this->Hr_model->get_emp_terminated_details();
		echo json_encode($result); 
	}
	
	public function get_emp_terminated_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_emp_terminated_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function emp_transfer_details()
	{
		$this->mViewData['pageTitle']    = 'Transfer Employee Details';
		$this->render('hr/events/emp_transfer_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/emp_transfer_details_script');
	}
	
	public function get_emp_transfer_details(){
		$result = $this->Hr_model->get_emp_transfer_details();
		echo json_encode($result); 
	}
	
	public function get_emp_transfer_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_emp_transfer_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function emp_onhold_details()
	{
		$this->mViewData['pageTitle']    = 'Employee On Hold';
		$this->render('hr/events/emp_onhold_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/emp_onhold_details_script');
	}
	
	public function get_emp_onhold_details(){
		$result = $this->Hr_model->get_emp_onhold_details();
		echo json_encode($result); 
	}
	
	public function get_emp_onhold_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_emp_onhold_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function emp_contract_details()
	{
		$this->mViewData['pageTitle']    = 'Contact Employee Details';
		$this->render('hr/events/emp_contract_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/emp_contract_details_script');
	}
	
	public function get_emp_contract_details(){
		$result = $this->Hr_model->get_emp_contract_details();
		echo json_encode($result); 
	}
	
	public function get_emp_contract_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_emp_contract_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function ex_emp_details()
	{
		$this->mViewData['pageTitle']    = 'Ex-Employee Details';
		$this->render('hr/events/ex_emp_details_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/events/ex_emp_details_script');
	}
	
	public function get_ex_emp_details(){
		$result = $this->Hr_model->get_ex_emp_details();
		echo json_encode($result); 
	}
	
	public function get_ex_emp_details_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_ex_emp_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function alert_general()
	{
		$this->mViewData['pageTitle']    = 'General Alert';
		$this->load->library('email');
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{ 
			//set validation rules 
			$this->form_validation->set_rules('txtMessage', 'text Message', 'trim|required');

			//run validation on form input
			if ($this->form_validation->run() == FALSE)
			{  
				//validation fails 
				//$this->render('hr/alert_general_view', 'full_width', $this->mViewData);
			}
			else
			{
				$resAllEmp = $this->db->query("SELECT * FROM `alert_general`");
				$rowAllEmp=  $resAllEmp->result_array();
				if(count($rowAllEmp)>0)
				{	
					$updateSql="UPDATE alert_general SET  `message`= '".$this->input->post('txtMessage')."'";
					$this->db->query($updateSql);
				}else{
					$this->db->query("INSERT INTO alert_general (message) VALUES ('".$this->input->post('txtMessage')."')" );
				}
			}
		}
		//get the form data
		$resAllEmp = $this->db->query("SELECT * FROM `alert_general`");
		$rowAllEmp=  $resAllEmp->result_array();
		$this->mViewData['rowAllEmp']    = $rowAllEmp;
		$this->render('hr/events/alert_general_view', 'full_width',$this->mViewData);
	}
	
	/**************************** END/ Events ********************************/
	
	
	/**************************** Recuitment ********************************/
	public function shortisted_candidate(){
		$this->mViewData['pageTitle']= "Short Listed Candidate";
		$loginID = $this->session->userdata('user_id');
		
		$bet ='';
		$cond = "WHERE shortlisted='1'";
		$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' $bet $cond"; 
		$empRES = $this->db->query($empSQL);
		$this->mViewData['rowMRF'] = $empRES->result_array();
		
		$jobQry = "SELECT * FROM `ap_posts` WHERE post_type='job' and post_status='publish'"; 
		$jobRes = $this->db->query($jobQry);
		$this->mViewData['jobRow'] = $jobRes->result_array();
		
		$this->render('hr/recruitment/shortisted_candidate_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/recruitment/shortisted_candidate_js', $this->mViewData);
		$this->load->view('script/hr/recruitment/shortisted_candidate_script', $this->mViewData);
	}
	public function get_shortisted_candidate(){
		$loginID = $this->session->userdata('user_id');
		$bet ='';
		$cond = "WHERE shortlisted='1'";
		$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' $bet $cond"; 
		$empRES = $this->db->query($empSQL);
		$result = $empRES->result_array();
		echo json_encode($result); 
	}
	
	public function interview_candidate_search()
	{
		$loginID = $this->session->userdata('user_id');
		$searchResumeType = $this->input->post('searchResumeType');
		$searchJobCode = $this->input->post('searchJobCode');
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		
		$bet ='';
		if($searchStartDate !='' && $searchEndDate !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($_POST['searchStartDate']));
			$txtEdate = date('Y-m-d',strtotime($_POST['searchEndDate']));   
			$bet .= "WHERE DATE(`request_date`) BETWEEN '$txtSdate' AND '$txtEdate' AND shortlisted='1'";
		}
		
		if($searchJobCode != '' && $bet != '') {
			$cond = " AND jj.id = '".$searchJobCode."'";
		}
		else if($searchJobCode == '' && $bet != '') {
			$cond = "";
		}
		else if($searchJobCode != ''){
			$cond = " WHERE jj.id = '".$searchJobCode."' AND shortlisted='1'";
		} 
		else {
			$cond = "WHERE shortlisted='1'";
		}
	
		if($searchResumeType =='applicants'){
			$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' $bet $cond"; 
		}
		else{
			$empSQL = "SELECT ja.*,ja.id as appid FROM ap_app_user_info ja  $bet "; 
		}
		$empRES = $this->db->query($empSQL);
		echo json_encode($empRES->result_array()); 
	}
	
	public function shortlisted_candidate_rating(){
		$description = $this->input->post('rm_desc');
		$emp_id = $this->input->post('emp_id');
		$appID = $this->input->post('appID');
		$sDate = $this->input->post('sDate');
		$shtSql = "UPDATE ap_app_user_info SET  interviewer='".$emp_id."',interview_desc='".$description."',interview_sch='1', interview_date='".date("Y-m-d",strtotime($sDate))."' WHERE id = '".$appID."'";
		$shtSqlR = $this->db->query($shtSql);
	}
	
	public function interview_scheduled_candidate(){
		$this->mViewData['pageTitle']= "Interview Scheduled Candidate";
		$loginID = $this->session->userdata('user_id');
		
		$jobQry = "SELECT * FROM `ap_posts` WHERE post_type='job' and post_status='publish'"; 
		$jobRes = $this->db->query($jobQry);
		$this->mViewData['jobRow'] = $jobRes->result_array();
		
		
		$this->render('hr/recruitment/interview_scheduled_candidate_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/recruitment/interview_candidate_script', $this->mViewData);
		$this->load->view('script/hr/recruitment/shortisted_candidate_js', $this->mViewData);
	}
	
	public function get_interview_candidate(){
		$loginID = $this->session->userdata('user_id');
		$bet ='';
		$cond = "WHERE shortlisted='1'";
		$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' $bet $cond"; 
		$empRES = $this->db->query($empSQL);
		$result = $empRES->result_array();
		echo json_encode($result); 
	}
	
	public function interview_scheduled_candidate_rating(){
		$rm_desc = $this->input->post('rm_desc');
		$appID = $this->input->post('appID');
		$hr_rating = $this->input->post('hr_rating');
		$type = $this->input->post('type');
		$shtSql = "UPDATE ap_app_user_info SET  shortlisted='".$type."', hr_rating='".$hr_rating."', hr_desc='".$rm_desc."', interview_date='".date('Y-m-d')."' WHERE id = '".$appID."'";
		$shtSqlR = $this->db->query($shtSql);
	}
	
	public function interview_rating(){
		$this->mViewData['pageTitle']= "Interview Rating";
		$loginID = $this->session->userdata('user_id');
		
		$jobQry = "SELECT * FROM `ap_posts` WHERE post_type='job' and post_status='publish'"; 
		$jobRes = $this->db->query($jobQry);
		$this->mViewData['jobRow'] = $jobRes->result_array();
		
		$this->render('hr/recruitment/interview_rating_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/recruitment/interview_rating_script', $this->mViewData);
		$this->load->view('script/hr/recruitment/shortisted_candidate_js', $this->mViewData);
	}
	
	public function get_interview_candidate_info(){
		$id = $this->input->post('id');
		$jobQry = $this->db->query("SELECT * FROM ap_app_user_info WHERE id='".$id."'");
		$jobQryRes = $jobQry->result_array();
		echo json_encode($jobQryRes, true); 
	}

	public function submit_offer_letter_issue_date(){
		$appID = $this->input->post('appID');
		$issuedate = $this->input->post('issuedate');
		$shtSql = $this->db->query("UPDATE ap_app_user_info SET  offer_date='".date('Y-m-d',strtotime($issuedate))."' WHERE id = '".$appID."'");
		
	}
	
	public function placement_consultant(){
		$this->mViewData['pageTitle']= "Placement Consultant";
		$placement = $this->Hr_model->get_all_placement_consultant();
		$this->mViewData['placement'] = $placement;
		$this->render('hr/recruitment/placement_consultant_view', 'full_width',$this->mViewData);
	}
	
	public function add_placement_consultant(){
		$company = $this->input->post('company');
		$controller = $this->input->post('controller');
		if($controller == 0){
			$sql = $this->db->query('INSERT INTO `placement_consultant` (consultant_name) values ("'.$company.'")');
			echo "Placement Consultant is Successfully Added";
		} else {
			$sqlU = "UPDATE `placement_consultant` set consultant_name= '".$company."' WHERE pid= '".$controller."'";	
			$sqlR = $this->db->query($sqlU);
			echo "Placement Consultant is Successfully Updated";
		}	
	}
	
	public function delete_placement_consultant(){
		$consultant_name = $this->input->post('consultant_name');
		$sql = 'DELETE FROM `placement_consultant` WHERE pid ='.$consultant_name;
		$sqlR = $this->db->query($sql);
		echo "Placement Consultant is Successfully Deleted";
	}
	
	public function online_mrf_detail()
	{
		$this->mViewData['pageTitle']    = 'online mrf details all';
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		$action = "";
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		if($action == 'delete'){
			$del_qry = "DELETE FROM `online_mrf` WHERE login_id = '".$loginID."' AND mid = '".$mid."'";
			$this->db->query($del_qry);
		}
		
		$this->mViewData['department'] = $this->Hr_model->get_department();
		$loginID = $this->session->userdata('user_id');
		
		 
		//Template view
		$this->render('hr/recruitment/online_mrf_detail_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr/recruitment/online_mrf_js', $this->mViewData); 
		$this->load->view('script/hr/recruitment/online_mrf_script', $this->mViewData); 
	}
	
	public function get_online_mrf_detail(){
		$mysql_qry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name, om.mid, om.no_vacancies, om.mrf_apply_date, om.mrf_status FROM `internal_user` i RIGHT JOIN `online_mrf` om ON om.login_id = i.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation WHERE i.login_id != '10010' AND om.dh_status='1' AND om.mrf_status='1' ORDER by mrf_apply_date DESC";
		$resMRF = $this->db->query($mysql_qry);
		$resMRFS = $resMRF->result_array();
		echo json_encode($resMRFS); 
	}
	
	function close_mrf_form(){
		$mrf_status = $this->input->post('mrf_status');
		$mrf_action_date = $this->input->post('mrf_action_date');
		$hr_description = $this->input->post('hr_description');
		$no_of_resume = $this->input->post('no_of_resume');
		$cleared_hr = $this->input->post('cleared_hr');
		$no_of_cand_cleard = $this->input->post('no_of_cand_cleard');
		$no_of_cand_joind = $this->input->post('no_of_cand_joind');
		$appID = $this->input->post('appID');
		$sqlR = "UPDATE `online_mrf` SET hr_desc='".$hr_description."', mrf_status='".$mrf_status."', 	mrf_close_date='".date('Y-m-d',strtotime($mrf_action_date))."',
                resume_receive='".$no_of_resume."', clear_hr_round='".$cleared_hr."', shortlisted='".$no_of_cand_cleard."', joined='".$no_of_cand_joind."' WHERE mid = '".$appID."'";
		$sqlRes = $this->db->query($sqlR);
	}
	
	public function online_mrf_search(){
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$designation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$mrf_status = $this->input->post('mrf_status');
		$cond = "";
		if($searchDepartment != '') {
			$cond .= " AND i.department = '".$searchDepartment."' ";
		}
		if($designation != ''){
			$cond .= " AND i.designation = '".$designation."' ";
		} 
		 if($searchName <> "") {
			 $cond .= " AND  (i.name_first like '%".$searchName."%') ";
		 } 
		 if($searchEmpCode <> "") {
			 $cond .= " AND  i.loginhandle = '".$searchEmpCode."' ";
		 } 
		 if($mrf_status != ''){
			$cond .= " AND om.mrf_status = '".$mrf_status."' "; 
		 }
		 
		$mysql_qry = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name, om.mid, om.no_vacancies, om.mrf_apply_date, om.mrf_status FROM `internal_user` i RIGHT JOIN `online_mrf` om ON om.login_id = i.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation WHERE i.login_id != '10010' AND om.dh_status='1' $cond ORDER by mrf_apply_date DESC";
		$resMRF = $this->db->query($mysql_qry);
		$resMRFS = $resMRF->result_array();
		echo json_encode($resMRFS); 
	}
	
	public function recruitment_report(){
		$this->mViewData['pageTitle']    = 'Recuritment Report';
		$this->mViewData['location'] = $this->Hr_model->get_all_location();
		$this->mViewData['department'] = $this->Hr_model->get_department();
		$this->render('hr/recruitment/recruitment_report_view', 'full_width', $this->mViewData); 
	}
	
	public function monthly_recuritment_export()
	{
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("Pradeep Sahoo")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		$header = array();
		$empSummaryArray = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Position");
		$noOfColumnsSelected++;
		
		array_push($header,"No. of Position");
		$noOfColumnsSelected++; 
		
		array_push($header, "Raised By");
		$noOfColumnsSelected++;
		
		array_push($header, "MRF Received");
		$noOfColumnsSelected++;   

		array_push($header, "MRF Close Date");
		$noOfColumnsSelected++;

		array_push($header, "Lead Time Taken");
		$noOfColumnsSelected++;

		array_push($header, "No. of Resume Received");
		$noOfColumnsSelected++;

		array_push($header, "No. of Candidate Cleared HR Round");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of Candidate Shortlisted");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of Candidate Joined");
		$noOfColumnsSelected++;
		
		array_push($header, "Status");
		$noOfColumnsSelected++;
		
		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$empSummaryArray[] = array();
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$cond =''; 
		$bet='';
		
		$d_dept = $this->input->post('dept');
		$d_loc = $this->input->post('loc');
		$d_status = $this->input->post('status');
		$Sdate = $this->input->post('Sdate');
		$Edate = $this->input->post('Edate');
		
		$and='AND ';
		$cond =' '; 
		
		 if($d_dept !='' ) {
			$cond .="$and om.department= '$d_dept'"; 
		 }
		
		if($d_loc !=''){
			$cond .="$and om.branch= '$d_loc'";	
		}
		
		if($d_status !='all') {
			$cond .="$and om.mrf_status= '$d_status'";
		}
		
		
		if($Sdate !='' && $Edate !=''){ 
			$Sdate = date('Y-m-d',strtotime($Sdate));
			$Edate = date('Y-m-d',strtotime($Edate));
			$cond .="$and DATE(`mrf_apply_date`) BETWEEN '$Sdate' AND '$Edate'";  
		}
       
        
		$toDay=date("d-m-Y");
       
		$sql = "SELECT dg.*, dp.*, om.*, b.*,i.* FROM `online_mrf` om left JOIN department as dp ON dp.dept_id=om.department left JOIN user_desg as dg ON dg.desg_id=om.designation left JOIN company_branch as b ON b.branch_id=om.branch inner JOIN `internal_user` as i ON i.login_id=om.login_id WHERE dh_status='1' $cond";
		
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				
				$processEmpSummaryArray = true;
				$i++;
				$date1 = strtotime($empDetailsInfo['mrf_date']);
                $date2 = strtotime($empDetailsInfo['mrf_close_date']);
				$days = 0;
				while (($date1 = strtotime('+1 day', $date1)) <= $date2){
					$days++;
				}
                if($empDetailsInfo['mrf_status']==0){ $statusNEW =  "Close"; }elseif($empDetailsInfo['mrf_status']==1)  { $statusNEW = "Open"; } else { $statusNEW =  "Hold"; }
				if($processEmpSummaryArray)
				{ 
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['desg_name']);
					array_push($empSummary, $empDetailsInfo['no_vacancies']);
					array_push($empSummary, $empDetailsInfo['full_name']);
					array_push($empSummary, $empDetailsInfo['mrf_date']);
					array_push($empSummary, $empDetailsInfo['mrf_close_date']);
					array_push($empSummary, $days.' days');
					array_push($empSummary, $empDetailsInfo['resume_receive']);
					array_push($empSummary, $empDetailsInfo['clear_hr_round']);
					array_push($empSummary, $empDetailsInfo['shortlisted']);
					array_push($empSummary, $empDetailsInfo['joined']);
					array_push($empSummary, $statusNEW);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		
		$row = 2;
		
		$filename = "monthly_report_mrf_".date('YmdHis').".xls";
		for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Import Employee Details');


		$objPHPExcel->setActiveSheetIndex(0);


		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	
	public function ytd_recuritment_export()
	{
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		$header = array();
		$empSummaryArray = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		array_push($header, "Department");
		$noOfColumnsSelected++;
		
		array_push($header,"Location");
		$noOfColumnsSelected++; 
		
		array_push($header, "Position");
		$noOfColumnsSelected++;
		
		array_push($header, "MRF Date");
		$noOfColumnsSelected++;   

		array_push($header, "Dept Head Desc");
		$noOfColumnsSelected++;

		array_push($header, "MRF Close Date");
		$noOfColumnsSelected++;

		array_push($header, "HR Desc");
		$noOfColumnsSelected++;

		array_push($header, "No. of Resume Received");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of Candidate Cleared HR Round");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of Candidate Shortlisted");
		$noOfColumnsSelected++;
		
		array_push($header, "No. of Candidate Joined");
		$noOfColumnsSelected++;
		
		array_push($header, "MRF Status");
		$noOfColumnsSelected++;
		
		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$empSummaryArray[] = array();
		$totalKS = count($empSummaryArray);
		$row = 2;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$cond =''; 
		$bet='';
		
		$d_dept = $this->input->post('searchDepartment');
		$d_loc = $this->input->post('loc');
		$d_status = $this->input->post('status');
		$Sdate = $this->input->post('Sdate');
		$Edate = $this->input->post('Edate');
		$and='AND ';
		$cond =' '; 
		
		if($Sdate !='' && $Edate !=''){ 
			$Sdate = date('Y-m-d',strtotime($Sdate));
			$Edate = date('Y-m-d',strtotime($Edate));
			$cond .="$and DATE(om.`mrf_apply_date`) BETWEEN '$Sdate' AND '$Edate'";  
		}
		
		 if($d_dept !='' ) {
			$cond .="$and om.department= '$d_dept'"; 
		 }
		
		if($d_loc !=''){
			$cond .="$and om.branch= '$d_loc'";	
		}
		
		if($d_status !='all') {
			$cond .="$and om.mrf_status= '".$d_status."'";
		}
		
		
       
        
		$toDay=date("d-m-Y");
       
		$sql = "SELECT dg.*, dp.*, om.*, b.* FROM `online_mrf` om left JOIN department as dp ON dp.dept_id=om.department left JOIN user_desg as dg ON dg.desg_id=om.designation left JOIN company_branch as b ON b.branch_id=om.branch  WHERE dh_status='1' $cond ";
		//echo $sql; exit;
		
		$empDetailsRes = $this->db->query($sql);
		$empDetailsNum = count($empDetailsRes->result_array());
		$emp_details_res = $empDetailsRes->result_array();
		
		
		if($empDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				
				$processEmpSummaryArray = true;
				$i++;
				$date1 = strtotime($empDetailsInfo['mrf_date']);
                $date2 = strtotime($empDetailsInfo['mrf_close_date']);
				$days = 0;
				while (($date1 = strtotime('+1 day', $date1)) <= $date2){
					$days++;
				}
                if($empDetailsInfo['mrf_status']==0){ $statusNEW =  "Close"; }elseif($empDetailsInfo['mrf_status']==1)  { $statusNEW = "Open"; } else { $statusNEW =  "Hold"; }
				
				if($processEmpSummaryArray)
				{ 
					$empSummary = array();
					array_push($empSummary,$i); 
					array_push($empSummary, $empDetailsInfo['dept_name']);
					array_push($empSummary, $empDetailsInfo['branch_name']);
					array_push($empSummary, $empDetailsInfo['desg_name']);
					array_push($empSummary, $empDetailsInfo['mrf_date']);
					array_push($empSummary, $empDetailsInfo['dh_desc']);
					array_push($empSummary, $empDetailsInfo['mrf_close_date']);
					array_push($empSummary, $empDetailsInfo['hr_desc']);
					array_push($empSummary, $empDetailsInfo['resume_receive']);
					array_push($empSummary, $empDetailsInfo['clear_hr_round']);
					array_push($empSummary, $empDetailsInfo['shortlisted']);
					array_push($empSummary, $empDetailsInfo['joined']);
					array_push($empSummary, $statusNEW);
					$empSummaryArray[$ai++] = $empSummary;
				}			
			}
		}

		  
		$totalKS = count($empSummaryArray);
		
		$row = 2;
		
		$filename = "ytd_report_mrf_".date('YmdHis').".xls";
		for($i=0; $i< $totalKS; $i++)
			{
				foreach($empSummaryArray[$i] AS $col => $empInfo)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Import Employee Details');


		$objPHPExcel->setActiveSheetIndex(0);


		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	function resume_databank(){
		$this->mViewData['pageTitle']    = 'Resume Databank';
		$loginID = $this->session->userdata('user_id');
		$etpl = $this->db->query("SELECT form_id, form_name FROM `email_templates` WHERE status = 'Y' ORDER BY form_name");
		$emailCat = $this->db->query("SELECT * FROM `email_category` where cstatus = '1' ORDER BY cname");
		$jobQry = $this->db->query("SELECT * FROM `ap_posts` WHERE post_type='job' and post_status='publish'");
		$cur_companyQry = $this->db->query("SELECT DISTINCT cur_company FROM ap_app_user_info order by cur_company");
		$cur_desgQry = $this->db->query("SELECT DISTINCT cur_designation FROM ap_app_user_info order by cur_designation");
	
		$this->mViewData['etpl'] = $etpl->result_array();
		$this->mViewData['jobRow'] = $jobQry->result_array();
		$this->mViewData['emailCat'] = $emailCat->result_array();
		$this->mViewData['cur_companyQry'] = $cur_companyQry->result_array();
		$this->mViewData['cur_desgQry'] = $cur_desgQry->result_array();
	
		$this->render('hr/recruitment/resume_databank_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr/recruitment/resume_databank_script', $this->mViewData);
	
	}
	
	public function get_resume_databank(){
		$loginID = $this->session->userdata('user_id');
		$empSQL = "SELECT ja.*,jj.post_title as post_title,ja.id as appid,DATEDIFF(CURRENT_TIMESTAMP,ja.request_date) as date_diff FROM ap_app_user_info ja LEFT JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' WHERE ja.job_id !='' ORDER BY ja.request_date DESC";
	
		$empRES = $this->db->query($empSQL);
		$result = $empRES->result_array();
		//$resumedata[] = array();
		for($i = 0; $i < COUNT($result); $i ++){
			if($result[$i]['contact_status'] == 1){
				$resR=$this->db->query("select * from ap_app_email_history where app_id='".$result[$i]['id']."'");
				$resRR = $resR->result_array();
				$noof_time = COUNT($resRR);
			} else {
				$noof_time = '0';
			}
				
				
			$resumedata[] = array('full_name' => $result[$i]['first_name'].' '.$result[$i]['last_name'],
					'cur_designation' => $result[$i]['cur_designation'],
					'tel' => $result[$i]['tel'],
					'post_title' => $result[$i]['post_title'],
					'highest_qualification' => $result[$i]['highest_qualification'],
					'cv' => $result[$i]['cv'],
					'noof_time' => $noof_time,
					'appid' => $result[$i]['appid'],
					'email' => $result[$i]['email'],
					'gender' => $result[$i]['gender'],
					'maritial' => $result[$i]['marital_status'],
					'relocation' => $result[$i]['open_for_relocation'],
					'location' => $result[$i]['cur_location'],
					'exp' => $result[$i]['tot_yr_exp'],
					'desg' => $result[$i]['cur_designation'],
					'employr' => $result[$i]['cur_company'],
					'cctc' => $result[$i]['cur_ctc'],
					'ectc' => $result[$i]['exp_ctc'],
					'nperiod' => $result[$i]['notice_period'],
					'ejoingdate' => $result[$i]['ea_from_date'], //check here
					'high_qual' => $result[$i]['highest_qualification'],
					'year_of_pass' => $result[$i]['passing_year'],
					'spclization' => $result[$i]['specialization'],
					'instName' => $result[$i]['institution_name'],
					'keySkills' => $result[$i]['key_skills'],
					'inrviewd' => $result[$i]['interviewed_aabsys'],
					'employedbefr' => $result[$i]['employed_aabsys'],
					'coverNote' => $result[$i]['cover_note'],
					'employeeR' => $result[$i]['employee_name'],
					'hr_rating' => $result[$i]['hr_rating'],
					'hr_desc' => $result[$i]['hr_desc'],
					'date_diff' => round($result[$i]['date_diff']/30)
			);
		}
		echo json_encode($resumedata);
	}
	
	public function resume_databank_search(){
		$loginID = $this->session->userdata('user_id');
		$searchResumeType = $this->input->post('searchResumeType');
		$searchJobCode = $this->input->post('searchJobCode');
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		$contact_status = $this->input->post('contact_status');
		$full_name = $this->input->post('full_name');
		$skills = $this->input->post('skills');
		$btnSearch = $this->input->post('btnSearch');
		$searchcurremployee = $this->input->post('searchcurremployee');
		$searchcurrdesignation = $this->input->post('searchcurrdesignation');
		$cond = "";
	
		if($searchResumeType == "applicants"){
			$cur_company='';
			$cur_designation='';
		} else {
			$searchJobCode='';
		}
		if($searchStartDate !='' && $searchEndDate !=''){
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate));
			$cond .= " AND DATE(`request_date`) BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		if($searchJobCode != ''){
			$cond .= " AND ja.job_id = '".$searchJobCode."'";
		}
		if($searchcurremployee != ''){
			$cond .= " AND ja.cur_company LIKE '%".substr($searchcurremployee,0,4)."%'";
		}
		if($searchcurrdesignation != ''){
			$cond .= " AND ja.cur_designation LIKE '%".substr($searchcurrdesignation,0,4)."%'";
		}
		if($full_name != ''){
			$cond .= " AND ja.first_name  LIKE '%$full_name%'";
		}
		if($skills != ''){
			$cond .= " AND ja.key_skills  LIKE '%".$skills."%'";
		}
		if($contact_status != ''){
			$cond .= " AND ja.contact_status ='".$contact_status."'";
		}
		//echo $cond; exit;
		if($searchResumeType =='applicants'){
			$empSQL = "SELECT ja.*,jj.post_title as post_title,ja.id as appid,DATEDIFF(CURRENT_TIMESTAMP,ja.request_date) as date_diff  FROM ap_app_user_info ja LEFT JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' WHERE ja.job_id !='' $cond ORDER BY date_diff ASC";
		} else {
		$empSQL = "SELECT ja.*,ja.id as appid,DATEDIFF(CURRENT_TIMESTAMP,ja.request_date) as date_diff  FROM ap_app_user_info ja WHERE ja.job_id='' $cond  ORDER BY date_diff ASC" ;
		}
		$empRES = $this->db->query($empSQL);
		$result = $empRES->result_array();
		$resumedata = "";
		if(COUNT($result) != 0){
				for($i = 0; $i < COUNT($result); $i ++){
					if($result[$i]['contact_status'] == 1){
							$resR=$this->db->query("select * from ap_app_email_history where app_id='".$result[$i]['id']."'");
									$resRR = $resR->result_array();
									$noof_time = COUNT($resRR);
					} else {
					$noof_time = '0';
					}
					if($searchResumeType =='applicants'){
					$resumedata[] = array('full_name' => $result[$i]['first_name'].' '.$result[$i]['last_name'],
									'cur_designation' => $result[$i]['cur_designation'],
									'tel' => $result[$i]['tel'],
									'post_title' => $result[$i]['post_title'],
									'highest_qualification' => $result[$i]['highest_qualification'],
									'cv' => $result[$i]['cv'],
									'noof_time' => $noof_time,
									'appid' => $result[$i]['appid'],
									'email' => $result[$i]['email'],
									'gender' => $result[$i]['gender'],
									'maritial' => $result[$i]['marital_status'],
									'relocation' => $result[$i]['open_for_relocation'],
									'location' => $result[$i]['cur_location'],
									'exp' => $result[$i]['tot_yr_exp'],
									'desg' => $result[$i]['cur_designation'],
									'employr' => $result[$i]['cur_company'],
									'cctc' => $result[$i]['cur_ctc'],
									'ectc' => $result[$i]['exp_ctc'],
									'nperiod' => $result[$i]['notice_period'],
									'ejoingdate' => $result[$i]['ea_from_date'], 
									'high_qual' => $result[$i]['highest_qualification'],
									'year_of_pass' => $result[$i]['tot_yr_exp'],
									'spclization' => $result[$i]['specialization'],
									'instName' => $result[$i]['institution_name'],
									'keySkills' => $result[$i]['key_skills'],
									'inrviewd' => $result[$i]['interviewed_aabsys'],
									'employedbefr' => $result[$i]['employed_aabsys'],
									'coverNote' => $result[$i]['cover_note'],
									'employeeR' => $result[$i]['employee_name'],
									'hr_rating' => $result[$i]['hr_rating'],
									'hr_desc' => $result[$i]['hr_desc'],
									'date_diff' => round($result[$i]['date_diff']/30)
								);
					} else {
						$resumedata[] = array('full_name' => $result[$i]['first_name'].' '.$result[$i]['last_name'],
							'cur_designation' => $result[$i]['cur_designation'],
							'tel' => $result[$i]['tel'],
							'highest_qualification' => $result[$i]['highest_qualification'],
							'cv' => $result[$i]['cv'],
							'noof_time' => $noof_time,
							'appid' => $result[$i]['appid'],
							'email' => $result[$i]['email'],
							'gender' => $result[$i]['gender'],
							'maritial' => $result[$i]['marital_status'],
							'relocation' => $result[$i]['open_for_relocation'],
							'location' => $result[$i]['cur_location'],
							'exp' => $result[$i]['tot_yr_exp'],
							'desg' => $result[$i]['cur_designation'],
							'employr' => $result[$i]['cur_company'],
							'cctc' => $result[$i]['cur_ctc'],
							'ectc' => $result[$i]['exp_ctc'],
							'nperiod' => $result[$i]['notice_period'],
							'ejoingdate' => $result[$i]['ea_from_date'], 
							'high_qual' => $result[$i]['highest_qualification'],
							'year_of_pass' => $result[$i]['tot_yr_exp'],
							'spclization' => $result[$i]['specialization'],
							'instName' => $result[$i]['institution_name'],
							'keySkills' => $result[$i]['key_skills'],
							'inrviewd' => $result[$i]['interviewed_aabsys'],
							'employedbefr' => $result[$i]['employed_aabsys'],
							'coverNote' => $result[$i]['cover_note'],
							'employeeR' => $result[$i]['employee_name'],
							'hr_rating' => $result[$i]['hr_rating'],
							'hr_desc' => $result[$i]['hr_desc'],
							'date_diff' => round($result[$i]['date_diff']/30)
						);
					}
				}
		}
		echo json_encode($resumedata);
	}
	
	public function resume_databank_export(){
			$this->load->library('PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("SantiBhusan Mishra")
									->setTitle("Online HR Master")
									->setSubject("Online HR Master")
									->setDescription("Online HR Master.")
									->setKeywords("Online HR Master")
									->setCategory("Online HR Master Export");

			$header = array();
			$header = array("Sl No");
			$noOfColumnsSelected = 0;
			$selCols = "";
			$cond = "";
			
			array_push($header, "Name");     
			$noOfColumnsSelected++;
			
			array_push($header, "Job Title");      
			$noOfColumnsSelected++;

			array_push($header, "Email");    
			$noOfColumnsSelected++;

			array_push($header, "Telephone");      
			$noOfColumnsSelected++;

			array_push($header, "Gender");      
			$noOfColumnsSelected++;

			array_push($header, "Marital Status");     
			$noOfColumnsSelected++;

			array_push($header, "Open for Relocation");     
			$noOfColumnsSelected++;
			
			array_push($header, "Current Designation");     
			$noOfColumnsSelected++;
			
			array_push($header, "Current Company");      
			$noOfColumnsSelected++;
			
			array_push($header, "Current Location");      
			$noOfColumnsSelected++;
			
			array_push($header, "Current CTC");      
			$noOfColumnsSelected++;
			
			array_push($header, "Expected CTC");      
			$noOfColumnsSelected++;
			
			array_push($header, "Notice Period");      
			$noOfColumnsSelected++;
			
			array_push($header, "Total Year Exp");      
			$noOfColumnsSelected++;
			
			array_push($header, "Highest Qualification");      
			$noOfColumnsSelected++;
			
			array_push($header, "Passing Year Specialization");      
			$noOfColumnsSelected++;
			
			array_push($header, "Institution Name");      
			$noOfColumnsSelected++;
			
			array_push($header, "Key Skills");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employed Aabsys");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employed From Date");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employed To Date");      
			$noOfColumnsSelected++;
			
			array_push($header, "Interviewed AABSyS");      
			$noOfColumnsSelected++;
			
			array_push($header, "Interview Date");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employee Name");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employee Code");      
			$noOfColumnsSelected++;
			
			array_push($header, "Employee Join Date");      
			$noOfColumnsSelected++;
			
			foreach($header AS $i => $head)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
			}

			$loginID = $this->session->userdata('user_id');
			$searchResumeType = $this->input->get('resume_type');
			$searchJobCode = $this->input->get('searchJobCode');
			$searchStartDate = $this->input->get('searchStartDate');
			$searchEndDate = $this->input->get('searchEndDate');
			$contact_status = $this->input->get('contact_status');
			$searchcurremployee = $this->input->get('searchcurremployee');
			$searchcurrdesignation = $this->input->get('searchcurrdesignation');
			$cond = "";
			
			if($searchResumeType == "applicants"){
				$cur_company='';
				$cur_designation='';
			} else {
				$searchJobCode='';
			}
			if($searchStartDate !='' && $searchEndDate !=''){
				$txtSdate = date('Y-m-d',strtotime($searchStartDate));
				$txtEdate = date('Y-m-d',strtotime($searchEndDate));
				$cond .= " AND DATE(`request_date`) BETWEEN '$txtSdate' AND '$txtEdate'";
			}
			if($searchJobCode != ''){
				$cond .= " AND ja.job_id = '".$searchJobCode."'";
			}
			if($searchcurremployee != ''){
				$cond .= " AND ja.cur_company LIKE '%".substr($searchcurremployee,0,4)."%'";
			}
			if($searchcurrdesignation != ''){
				$cond .= " AND ja.cur_designation LIKE '%".substr($searchcurrdesignation,0,4)."%'";
			}
			
			if($contact_status != ''){
				$cond .= " AND ja.contact_status ='".$contact_status."'";
			}
		
			if($searchResumeType =='applicants'){
				$empSQL = "SELECT ja.*,jj.post_title as post_title,ja.id as appid,DATEDIFF(CURRENT_TIMESTAMP,ja.request_date) as date_diff  FROM ap_app_user_info ja LEFT JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' WHERE ja.job_id !='' $cond ORDER BY date_diff ASC";
			} else {
			$empSQL = "SELECT ja.*,ja.id as appid,DATEDIFF(CURRENT_TIMESTAMP,ja.request_date) as date_diff  FROM ap_app_user_info ja WHERE ja.job_id='' $cond  ORDER BY date_diff ASC" ;
			}
			
			$empRES = $this->db->query($empSQL);
			$result = $empRES->result_array();
			$empSummaryArray = array();
			$k = $ai = 0;
			for($i = 0; $i < COUNT($result); $i ++){
					if($result[$i]['contact_status'] == 1){
					$resR=$this->db->query("select * from ap_app_email_history where app_id='".$result[$i]['id']."'");
					$resRR = $resR->result_array();
					$noof_time = COUNT($resRR);
					} else {
					$noof_time = '0';
					}
					
					if($result[$i]['post_title'] != "" ) $post_title = $result[$i]['post_title']; else	$post_title = "";
					if($result[$i]['email'] = 'M')	$gender = 'Male'; else 	if($result[$i]['email'] = 'F') $gender = 'Female';
					if($result[$i]['marital_status'] = 'S') $mStatus = "Single"; else if($result[$i]['marital_status'] = 'M') $mStatus = "Married";		
						if($searchResumeType !='')
						{ 
							$k++;
							$empSummary = array();
							array_push($empSummary,$k); 
							array_push($empSummary, $result[$i]['first_name'].' '.$result[$i]['last_name']);                            
							array_push($empSummary, $post_title);   
							array_push($empSummary, $result[$i]['email']);  	
							array_push($empSummary, $result[$i]['tel']);                 
							array_push($empSummary, $gender);                         
							array_push($empSummary, $mStatus); 
							array_push($empSummary, $result[$i]['open_for_relocation']);  
							array_push($empSummary, $result[$i]['cur_designation']);  
							array_push($empSummary, $result[$i]['cur_company']);  
							array_push($empSummary, $result[$i]['cur_location']);  
							array_push($empSummary, $result[$i]['cur_ctc']);  
							array_push($empSummary, $result[$i]['exp_ctc']);  
							array_push($empSummary, $result[$i]['notice_period']);  
							array_push($empSummary, $result[$i]['tot_yr_exp']);  
							array_push($empSummary, $result[$i]['highest_qualification']);  
							array_push($empSummary, $result[$i]['passing_year']);  
							array_push($empSummary, $result[$i]['specialization']);  
							array_push($empSummary, $result[$i]['institution_name']);  
							array_push($empSummary, $result[$i]['key_skills']);  
							array_push($empSummary, $result[$i]['employed_aabsys']);  
							array_push($empSummary, $result[$i]['ea_from_date']);  
							array_push($empSummary, $result[$i]['ea_to_date']);  
							array_push($empSummary, $result[$i]['ia_date']);  
							array_push($empSummary, $result[$i]['employee_name']);  
							array_push($empSummary, $result[$i]['employee_code']);  
							array_push($empSummary, $result[$i]['emp_joining_date']);  
							$empSummaryArray[$ai++] = $empSummary;
						}
			}
			$totalKS = count($empSummaryArray);
			$row = 2;
			for($i=0; $i< $totalKS; $i++){
				foreach($empSummaryArray[$i] AS $col => $empInfo){
						$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
				}
				$row++;
			}
			
			$filename = "resume_databank_".date('YmdHis').".xls";
			// Rename worksheet
			$objPHPExcel->getDefaultStyle()->applyFromArray(array(
					'borders' => array(
						'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
					)
				));

			$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
			$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd');

			$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setTitle('LWH Report ' .date('Y'));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('ntent-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=\"$filename\""); 
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		
	}
	
	public function resume_databank_submit_rating(){
		$rm_desc = $this->input->post('');
		$appID = $this->input->post('appID');
		$hr_rating = $this->input->post('hr_rating');
		$type = $this->input->post('type');
		$shtSql = "UPDATE ap_app_user_info SET  shortlisted='".$type."', hr_rating='".$hr_rating."', hr_desc='".$rm_desc."' WHERE id = '".$appID."'";
		$shtSqlR = $this->db->query($shtSql);
	}

	public function getemailTemplate(){
		$form_id = $this->input->post('form_id');
		$rowEmailTpl= $this->db->query("SELECT form_name,form_id FROM `email_templates` where cid = '".$form_id."'");
		$rowEmailTpls = $rowEmailTpl->result_array();
		echo json_encode($rowEmailTpls);
	}

	public function getEmailDetails(){
		$appID = $this->input->post('appID');
		$emailRes = $this->db->query("SELECT * FROM `ap_app_email_history` WHERE app_id= $appID");
		$emailR = $emailRes->result_array();
		echo json_encode($emailR);
	}

	public function get_email_view(){
		$cid = $this->input->post('cid');
		$form_id = $this->input->post('form_id');
		$emailContentQ = $this->db->query('SELECT content FROM `email_templates` where cid = "'.$cid.'" and form_id = "'.$form_id.'"');
		$emailContent = $emailContentQ->result_array();
		echo json_encode($emailContent);
	}

	public function sendtoALLemail(){
		$loginID = $this->session->userdata('user_id');
		$searchResumeType = $this->input->post('searchResumeType');
		$searchJobCode = $this->input->post('searchJobCode');
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		$contact_status = $this->input->post('contact_status');
		$full_name = $this->input->post('full_name');
		$skills = $this->input->post('skills');
		$btnSearch = $this->input->post('btnSearch');
		$searchcurremployee = $this->input->post('searchcurremployee');
		$searchcurrdesignation = $this->input->post('searchcurrdesignation');
		$email_template = $this->input->post('email_template');
		$email_category = $this->input->post('email_category');
		$cond = "";

		if($searchResumeType == "applicants"){
			$cur_company='';
			$cur_designation='';
		} else {
			$searchJobCode='';
		}
		if($searchStartDate !='' && $searchEndDate !=''){
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate));
			$cond .= " AND DATE(`request_date`) BETWEEN '$txtSdate' AND '$txtEdate'";
		}
		if($searchJobCode != ''){
			$cond .= " AND ja.job_id = '".$searchJobCode."'";
		}
		if($searchcurremployee != ''){
			$cond .= " AND ja.cur_company LIKE '%".substr($searchcurremployee,0,4)."%'";
		}
		if($searchcurrdesignation != ''){
			$cond .= " AND ja.cur_designation LIKE '%".substr($searchcurrdesignation,0,4)."%'";
		}
		if($full_name != ''){
			$cond .= " AND ja.first_name  LIKE '%".$full_name."%'";
		}
		if($skills != ''){
			$cond .= " AND ja.key_skills  LIKE '%".$skills."%'";
		}
		if($contact_status != ''){
			$cond .= " AND ja.contact_status ='".$contact_status."'";
		}

		if($searchResumeType =='applicants'){
			$empSQL = "SELECT ja.*,jj.post_title as post_title,ja.id as appid FROM ap_app_user_info ja LEFT JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' WHERE ja.job_id !='' $cond";
		} else {
			$empSQL = "SELECT ja.*,ja.id as appid FROM ap_app_user_info ja WHERE ja.job_id='' $cond";
		}
		$empRES = $this->db->query($empSQL);
		$result = $empRES->result_array();
	  
		$sqlTo = $this->db->query("SELECT * FROM internal_user WHERE login_id='". $loginID."'");
		$rowTo= $sqlTo->result_array();
	 
		$emailContentQ = $this->db->query('SELECT content,form_name FROM `email_templates` where cid = "'.$email_category.'" and form_id = "'.$email_template.'"');
		$emailContent = $emailContentQ->result_array();
	  
		$subject = $emailContent[0]['form_name'];
		$content_new = $emailContent[0]['content'];

		for($i = 0; $i< COUNT($result);$i++){
			$name_full=$content='';
			$name_full=$result[$i]['first_name'].' '.$result[$i]['last_name'];
			$content =str_replace('{NAME}',strtoupper($name_full),$content_new);
			$content= trim($content);
			$to =$result[$i]['email'];
		
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "X-Priority: 1 (Highest)\n";
			$headers .= "X-MSMail-Priority: High\n";
			$headers .= 'From:'.$rowTo[0]['email'] . "\r\n";
			$headers  .= 'Reply-To: '.$rowTo[0]['email'] . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			//echo $content;
			if(mail($to, $subject, $content, $headers)){
				echo 'Email Sent';
			} else {
				echo 'Email Not Sent';
			}
		}
	}

	public function sendemailToSelected(){
		$cid = $this->input->post('cid');
		$form_id = $this->input->post('form_id');
		$param = json_decode($this->input->post('params'));
		
		$emp_user_query = "select * from internal_user where login_id='".$_SESSION['user_id']."'";
		$emp_res = $this->db->query($emp_user_query);
		$res = $emp_res->result_array();
		
		$emp_query = "select * from email_templates where form_id='".$form_id."' and cid = '".$cid."'";
		$empRES = $this->db->query($emp_query);
		$result = $empRES->result_array();
		if(count($result) > 0)
		{
			$subject = $result[0]['form_name'];
			$content_new = $result[0]['content'];
			if($res[0]['email_signature'] !=""){
				$signature = $res[0]['email_signature'];
			}
			else{
				$signature = "Polosoft HR Team";
			}
			$content_new =str_replace('{SENDER}',$signature,$content_new);

			for($i = 1; $i<= count($param);$i++){
				$user_data = explode(',',$param->{$i});
				
				$name_full=$content='';
				$content =str_replace('{NAME}',strtoupper($user_data[1]),$content_new);
				
				$content= trim($content); //echo $content; exit;
				$to =$user_data[0];
			
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "X-Priority: 1 (Highest)\n";
				$headers .= "X-MSMail-Priority: High\n";
				$headers .= 'From: hr@polosoftech.com' . "\r\n";
				$headers  .= 'Reply-To: no-reply@polosoftech.com'. "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				if(mail($to, $subject, $content, $headers)){
					echo 'Email Sent';
				} else {
					echo 'Email Not Sent';
				}
			}
		}
	}
	
	/**************************** END/ Recuitment ********************************/
	
	
	/**************************** Utilities ********************************/
	public function birthday_reminder()
	{
		$this->mViewData['pageTitle']= "Birth Day Reminder";
		$currentURL = current_url();
		$params   = $_SERVER['QUERY_STRING'];  
		$this->mViewData['fullURL'] = $currentURL . '?' . $params;
        //var_dump($this->mViewData['fullURL']);

		$choose_year = $this->input->post('dd_year');
		if (!$choose_year) 
		{
			$choose_year = date("Y");
		}
		$type = $this->input->get('type');
		$showSearchBox = FALSE;
		if($type == 'Today')
		{
			$subheader='News &amp; Events Today';
			$this->mViewData['title']= 'News &amp; Events Today';
			$subTitle = 'today';
		}
		elseif($type == 'ThisWeek')
		{
			$subheader='News &amp; Events of this week';
			$this->mViewData['title'] = 'News &amp; Events This Week';
			$subTitle = 'this week';
		}
		elseif($type == 'ThisMonth')
		{
			$subheader='News &amp; Events of this month';
			$this->mViewData['title'] = 'News &amp; Events This Month';
			$subTitle = 'this month';
		}
		elseif($type == 'Archive')
		{
			$subheader='Archive';
			$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Archive News &amp; Events';
		}
		elseif($type == 'Upcoming')
		{
			$subheader='Upcoming News &amp; Events';
			$showSearchBox = TRUE;
			$this->mViewData['title'] = 'Upcoming News &amp; Events';
		}
		else
		{
			$type = 'All';
			$showSearchBox = TRUE;
			$subheader='All News &amp; Events';
			$this->mViewData['title'] = 'News &amp; Events';
		} 
		$mm = date("m");
		$yy = date("Y");

		if($this->input->post('btnSearch') == 'Search' && $type == 'Upcoming')
		{
			$dd_month = $this->input->post('dd_month'); 
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		if($this->input->post('btnSearch') == 'Search')
		{
			$dd_month = $this->input->post('dd_month'); 
			$this->mViewData['dd_year'] = $this->input->post('dd_year');
			$dd_year = $this->input->post('dd_year');
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else if($this->input->post('type') == 'Upcoming')
		{
			$dd_month = $mm + 1;
			$dd_year = $yy;
			if($mm > 11)
			{
				$dd_month = '01';
				$dd_year = $yy + 1;
			}
			$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type, $dd_month, $dd_year);
		}
		else
		{
			$dd_month = $mm;
			$dd_year = $yy;
			if($type == 'All' OR $type == 'Archive')
			{
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			else if($type == 'Upcoming'){
				$dd_month = $mm + 1;
				$dd_year = $yy;
				if($mm > 11)
				{
					$dd_month = '01';
					$dd_year = $yy + 1;
				}
				$subTitle = date("F", strtotime($dd_year.'-'.$dd_month.'-01'));
			}
			$this->mViewData['newsAndEvents'] = $this->get_news_and_events($type);
		}
		$this->mViewData['dd_month'] = $dd_month;
		$this->mViewData['newsAndEventsMonth'] = $subTitle;
		//Template view
		$this->render('hr/utilities/news_and_events_view', 'full_width',$this->mViewData);
	} 
	public function get_news_and_events($for = 'All', $month = '', $year = '')
	{
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("m-d", strtotime($date));

		$currentWeekDayNo = date("w", strtotime($date));
		$noOfDaysToAddToGetLastDayOfWeek = 0;
		if($currentWeekDayNo < 6)
		{
			$noOfDaysToAddToGetLastDayOfWeek = 6 - $currentWeekDayNo ;
		}
		$bCond = $eCond = '';
		if($for == 'All')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateMFirst = date("m-d",strtotime($year.'-'.$month.'-01'));
				$dateMLast = date("m-d",strtotime($year.'-'.$month.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
				$dateMFirst = date("m-d",strtotime($yy.'-'.$mm.'-01'));
				$dateMLast = date("m-d",strtotime($yy.'-'.$mm.'-'.$days));
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime($yy.'-'.$mm.'-'.$days));
			}
			$bCond = "i.`dob_with_current_year` BETWEEN '$dateMFirst' AND '$dateMLast'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
		}
		elseif($for == 'Today')
		{
			$bCond = "i.`dob_with_current_year` = '$currentDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') = '$date'";
		}
		elseif($for == 'ThisWeek')
		{
			$currentDate = date("m-d");
			$lastWeekDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			$date = date("Y-m-d",strtotime('+1 days'));
			$lastWeekYDate = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetLastDayOfWeek.' days'));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastWeekDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastWeekYDate'";
		}
		elseif($for == 'ThisMonth')
		{
			$days = cal_days_in_month(CAL_GREGORIAN,$mm,$yy);
			$noOfDaysToAddToGetThisWeekNextDay = $noOfDaysToAddToGetLastDayOfWeek;
			//$currentDate = date("m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$currentDate = date("m-d");
			$date = date("Y-m-d",strtotime('+'.$noOfDaysToAddToGetThisWeekNextDay.' days'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			//echo $lastDayMonth;
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($mm.'/01/'.$yy.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		else if($for == 'Upcoming')
		{
			if($month == '' && $year == '')
			{
				$nextMonth = $mm + 1;
				$nextYear = $yy;
				if($mm > 11){
					$nextMonth = '01';
					$nextYear = $yy + 1;
				}
			}
			else{
				$nextMonth = $month;
				$nextYear = $year;
			}
			$currentDate = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			$date = date("Y-m-d",strtotime($nextYear.'-'.$nextMonth.'-01'));
			//$lastDayMonth = date("m-d",strtotime($nextYear.'-'.$nextMonth.'-30'));
			//$lastDayYMonth = date("Y-m-d", strtotime($nextYear.'-'.$nextMonth.'-30'));
			$lastDayMonth = date("m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
			$lastDayYMonth = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($nextMonth.'/01/'.$nextYear.' 00:00:00'))));
			$bCond = "i.`dob_with_current_year` BETWEEN '$currentDate' AND '$lastDayMonth'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$date' AND '$lastDayYMonth'";
		}
		elseif($for == 'Archive')
		{
			if($month != '')
			{
				$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
				$dateFirst = date("Y-m-d",strtotime($year.'-'.$month.'-01'));
				$date = date("Y-m-d",strtotime($year.'-'.$month.'-'.$days));
			}
			else
			{
				$dateFirst = date("Y-m-d",strtotime($yy.'-'.$mm.'-01'));
				$date = date("Y-m-d",strtotime('-1 days'));
			}
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$date'";
		}
		elseif($for == 'Week')
		{
			$weekFDate = date("m-d",strtotime($month));
			$weekEDate = date("m-d",strtotime($year));
			$weekFDateMonth = date("Y-m-d",strtotime($month));
			$weekEDateMonth = date("Y-m-d", strtotime($year));
			$bCond = "i.`dob_with_current_year` BETWEEN '$weekFDate' AND '$weekEDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$weekFDateMonth' AND '$weekEDateMonth'";
		}
		elseif($for == 'Day')
		{
			$cDate = date("m-d",$month);
			$cMDate = date("Y-m-d",$month);
			$bCond = "i.`dob_with_current_year` = '$cDate'";
			$eCond = "DATE_FORMAT(on_date, '%Y-%m-%d') = '$cMDate'";
		}
		
		if($for != 'Archive')
		{
			// Get Birthday Details
			$sQry = "SELECT i.`login_id`, i.`name_first`, i.`name_last`, i.`dob_with_current_year`, i.`user_photo_name`, i.`dob`, i.`designation`, i.`gender`, d.`desg_name` FROM internal_user i LEFT JOIN user_desg d ON d.desg_id = i.designation WHERE  i.`user_status` = '1' AND $bCond ORDER BY i.`dob_with_current_year`";
			$sRes = $this->db->query($sQry);
			$arr = $sRes->result_array();
			$sNum = count($sRes);
			
			if($sNum > 0)
			{
				foreach($arr as $sInfo)
				{
					$birthdayArray = array((date("Y").'-'.$sInfo['dob_with_current_year']),
										 'B',
										 $sInfo['name_first'].' '.$sInfo['name_last'],
										 $sInfo['desg_name'],
										 $sInfo['login_id'],
										 $sInfo['user_photo_name'],
										 $sInfo['gender']
										);
					$newsAndEvents[] = $birthdayArray;
				}
			}
		}
		
		if($for == 'ThisMonth' && $dd > 01)
		{
			
			$firstDayYMonth = date("Y-m-d", strtotime($mm.'/01/'.$yy.' 00:00:00'));
			$toDayM1 = date("Y-m-d",strtotime('-1 days'));
			$eCond1 = "DATE_FORMAT(on_date, '%Y-%m-%d') BETWEEN '$firstDayYMonth' AND '$toDayM1'";
			// Get News and Events
			$newsEventQry = "SELECT * FROM `news_event` WHERE `n_disp_flag` = 1 AND $eCond1 ORDER BY `on_date` ASC";
			$newsEventRes = $this->db->query($newsEventQry);
			$array = $newsEventRes->result_array();
			$newsEventNum = count($newsEventRes);
			
			if($newsEventNum > 0){
				foreach($array as $newsEventInfo){
					$eventArray = array(($newsEventInfo['on_date']),
										 $newsEventInfo['type'],
										 $newsEventInfo['title'],
										 $newsEventInfo['body'],
										 $newsEventInfo['to_date'],
										 $newsEventInfo['file'],
										 $newsEventInfo['id']
										);
					$newsAndEvents[] = $eventArray;
				}
			}
		}
		
		// Get News and Events
		$newsEventQry = "SELECT * FROM `news_event` WHERE `n_disp_flag` = 1 AND $eCond ORDER BY `on_date` ASC";
		$newsEventRes = $this->db->query($newsEventQry);
		$arra = $newsEventRes->result_array();
		$newsEventNum = count($newsEventRes);
		
		if($newsEventNum > 0){
			foreach($arra as $newsEventInfo){
				$eventArray = array(($newsEventInfo['on_date']),
									 $newsEventInfo['type'],
									 $newsEventInfo['title'],
									 $newsEventInfo['body'],
									 $newsEventInfo['to_date'],
									 $newsEventInfo['file'],
									 $newsEventInfo['id']
									);
				$newsAndEvents[] = $eventArray;
			}
		} 	   
		return $newsAndEvents;
	}
	
	
	public function room_booking(){
		$this->mViewData['pageTitle']= "Online Room Booking";
		$loginID = $this->session->userdata('user_id');
		
		$this->render('hr/utilities/room_booking_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/utilities/room_booking_script', $this->mViewData);
	}
	
	public function get_room_booking(){
		$loginID = $this->session->userdata('user_id');
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("Y-m", strtotime($date));
		$cond = " AND DATE_FORMAT(book_date, '%Y-%m') = '$currentDate'";
		$jobQry = $this->db->query("SELECT i.loginhandle, i.full_name, l.* FROM `internal_user` i INNER JOIN `online_room_booking` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' $cond");
		$jobQryRes = $jobQry->result_array();
		echo json_encode($jobQryRes);
	}
	
	public function get_room_booking_search(){
		$loginID = $this->session->userdata('user_id');
		$date = date("Y-m-d");
		$dd = date("d");
		$mm = date("m");
		$yy = date("Y");

		$currentDate = date("Y-m", strtotime($this->input->post('searchYear').'-'.$this->input->post('searchMonth').'-01'));
		$cond = " AND DATE_FORMAT(book_date, '%Y-%m') = '$currentDate'";
		$jobQry = $this->db->query("SELECT i.loginhandle, i.full_name, l.* FROM `internal_user` i INNER JOIN `online_room_booking` l ON l.login_id = i.login_id WHERE i.user_status = '1' AND i.login_id != '10010' $cond");
		$jobQryRes = $jobQry->result_array();
		echo json_encode($jobQryRes);
	}
	
	public function get_room_booking_staus_approved_update(){
		$loginID = $this->session->userdata('user_id');
		$id = $this->input->post('id');
		$datas = array(
			'status' => 'A'
		);
		$this->db->where('online_room_booking.id', $id);
		$this->db->update('online_room_booking', $datas);
	}
	
	public function get_room_booking_staus_rejected_update(){
		$loginID = $this->session->userdata('user_id');
		$id = $this->input->post('id');
		$datas = array(
			'status' => 'R'
		);
		$this->db->where('online_room_booking.id', $id);
		$this->db->update('online_room_booking', $datas);
	}
	/**************************** END/ Utilities ********************************/
	
	
	/**************************** HR Information Module ********************************/
	public function directors_message(){
	    $this->mViewData['pageTitle'] = 'Director Message';
	    $checkData = $this->db->query('SELECT * FROM `director_message` where mid = 1');
	    $checkDataRes = $checkData->result_array($checkData);
	    $this->mViewData['message'] =  $checkDataRes;
	    $this->render('hr/hr_information/director_message_view', 'full_width',$this->mViewData);
	}
	
	public function add_director_message(){
	    $editor1 = $this->input->post('data');
	    $checkData = $this->db->query('SELECT * FROM `director_message` where mid = 1');
	    $checkDataRes = $checkData->result_array($checkData);
	    if(COUNT($checkDataRes) > 0){
	        $sql = $this->db->query("UPDATE `director_message` SET  `message`= '".$editor1."' where mid = 1");
	    } else {
	        $sql = $this->db->query("INSERT INTO `director_message`(message) VALUES ('".$editor1."')");
	    }
	    
	}
	
	public function hr_policies(){
	    $this->mViewData['pageTitle'] = 'HR Policies';
	    $checkData = $this->db->query('SELECT * FROM `hr_policies` where pid = 1');
	    $checkDataRes = $checkData->result_array($checkData);
	    $this->mViewData['checkDataRes'] =  $checkDataRes;
	    $this->render('hr/hr_information/hr_policies_view', 'full_width',$this->mViewData);
	}
	
	public function add_hr_policies(){
	    $editor1 = $this->input->post('data');
	    $checkData = $this->db->query('SELECT * FROM `hr_policies` where pid = 1');
	    $checkDataRes = $checkData->result_array($checkData);
	    if(COUNT($checkDataRes) > 0){
	        $sql = $this->db->query("UPDATE `hr_policies` SET  `policies`= '".$editor1."' where pid = 1");
	    } else {
	        $sql = $this->db->query("INSERT INTO `hr_policies`(policies) VALUES ('".$editor1."')");
	    }
	}
	
	public function list_holiday(){
	    $this->mViewData['pageTitle'] = "List of Holidays";
		$success = "";
		if($this->input->post('editSubmit') == 'SUBMIT'){
			$s_event_name = $this->input->post('s_event_name');
			$ix_declared_leave = $this->input->post('ix_declared_leave');
			$dt_event_date = date_format(date_create($this->input->post('dt_event_date')),"Y-m-d");
			$branch = $this->input->post('branch');
			
			$query = "UPDATE  `declared_leave` SET s_event_name = '".$s_event_name."',branch = '".$branch."',dt_event_date='".$dt_event_date."' WHERE ix_declared_leave = '".$ix_declared_leave."'";
			$querys = $this->db->query($query);
			$success = "Event Date is Successfully Updated";
		}
		$this->mViewData['success_msg'] = $success;
	    
	    $this->render('hr/hr_information/list_holiday_view', 'full_width',$this->mViewData);
	    $this->load->view('script/hr/hr_information/list_holiday_script');
	}
	
	public function get_list_holiday(){
	    $choose_year = date('Y');
	    $query = "SELECT d.*,DATE_FORMAT(d.dt_event_date,'%D %b %Y') as new_DATE FROM declared_leave d LEFT JOIN company_branch c ON c.branch_id=d.branch WHERE dt_event_date LIKE '$choose_year%' AND leave_type='D'  ORDER BY dt_event_date";
	    $leave_listss = $this->db->query($query);
	    $leave_list = $leave_listss->result_array();
	    echo json_encode($leave_list);
		
	}
	
	public function get_list_holiday_search(){
	    $choose_year = $this->input->post('searchyear');
	    $query = "SELECT d.*,DATE_FORMAT(d.dt_event_date,'%D %b %Y') as new_DATE FROM declared_leave d LEFT JOIN company_branch c ON c.branch_id=d.branch WHERE dt_event_date LIKE '$choose_year%' AND leave_type='D'  ORDER BY dt_event_date";
	    $leave_listss = $this->db->query($query);
	    $leave_list = $leave_listss->result_array();
	    echo json_encode($leave_list); 
	}
	
	public function edit_holiday_date(){
		$s_event_name = $this->input->post('s_event_name');
		$ix_declared_leave = $this->input->post('ix_declared_leave');
		$dt_event_date = date_format(date_create($this->input->post('dt_event_date')),"Y-m-d");
		$branch = $this->input->post('branch');
		
		$query = "UPDATE  `declared_leave` SET s_event_name = '".$s_event_name."',branch = '".$branch."',dt_event_date='".$dt_event_date."' WHERE ix_declared_leave = '".$ix_declared_leave."'";
		$querys = $this->db->query($query);
		$this->render('hr/hr_information/list_holiday_view', 'full_width',$this->mViewData);
	    $this->load->view('script/hr/hr_information/list_holiday_script');
	}
	
	public function add_holiday(){
		$s_event_name = $this->input->post('s_event_name');
		$dt_event_date = date_format(date_create($this->input->post('dt_event_date')),"Y-m-d");
		$branch = $this->input->post('branch');
		
		$sql = "INSERT INTO declared_leave (s_event_name, dt_event_date, branch) VALUES ('".$s_event_name."','".$dt_event_date."','".$branch."')";
		$this->db->query($sql);
	}
	
	public function delete_holiday(){
		$id = $this->input->post('id');
		$sql = "DELETE FROM declared_leave WHERE ix_declared_leave = '".$id."'";
		$this->db->query($sql);
		echo '<div class="col-md-12"><div class="alert alert-success" role="alert">Holiday is Successfully Deleted</div>';
	}
	
	public function view_phn(){
		$this->mViewData['pageTitle'] = "List of Holidays";
		$employee = $this->Hr_model->get_all_active_employee();
		$success = "";
		
		if(($this->session->flashdata('message_name'))){
			$success = $this->session->flashdata('message_name');
		}
		$this->mViewData['employee'] = $employee;
		$this->mViewData['success_msg'] = $success;
		$this->render('hr/hr_information/list_phn_view', 'full_width',$this->mViewData);
	    $this->load->view('script/hr/hr_information/list_phn_script');
	}
	
	public function edit_phn_submit(){
		if($this->input->post('editSubmit') == "SUBMIT"){
			$name = $this->input->post('name');
			$phone = $this->input->post('phone');
			$id = $this->input->post('id');
			$employee = $this->input->post('employee');
			$telQry = "SELECT c.id AS ids, c.name AS name, c.tel_no_with_ext AS phone,i.login_id,i.loginhandle,i.full_name  FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1' and c.id = '".$id."' ";
			$res = $this->db->query($telQry);
			$telQryRes = $res->result_array();
			//print_r($telQryRes);exit;
			
			$sqlU = $this->db->query("UPDATE `internal_user` SET company_telephone_id = '0' WHERE login_id = '".$telQryRes[0]['login_id']."'"); 
			
			$sql = "UPDATE `company_telephone_directory` SET name = '".$name."',tel_no_with_ext = '".$phone."' WHERE id = '".$id."'";
			$this->db->query($sql);
			
			$sqlU = $this->db->query("UPDATE `internal_user` SET company_telephone_id = '".$id."' WHERE login_id = '".$employee."'"); 
			$success = "The Contact Detail is successfully updated";
			
			$this->session->set_flashdata('message_name', $success);
		}
		redirect('/en/hr/view_phn');
	}
	
	public function get_list_phn(){
		$telQry = "SELECT c.id AS id, c.name AS name, c.tel_no_with_ext AS phone,i.login_id,i.loginhandle  FROM `company_telephone_directory` c INNER JOIN `internal_user` i ON c.id = i.company_telephone_id WHERE c.`n_disp_flag` = '1'  ORDER BY c.tel_no_with_ext ";
		$res = $this->db->query($telQry);
		$telQryRes = $res->result_array();
		echo json_encode($telQryRes); 
	}
	
	public function getDepartments_details(){
		$mid = $this->input->post('mid');
		$qry = "SELECT om.*,dp.dept_name,d.desg_name,c.branch_name, i.loginhandle, i.full_name, department.dept_name as department_name FROM `online_mrf` om LEFT JOIN `user_desg` d ON d.desg_id = om.designation LEFT JOIN `department` dp ON dp.dept_id = om.department LEFT JOIN `company_branch` c ON c.branch_id = om.branch left join internal_user i on i.login_id=om.login_id left join department on department.dept_id=i.department WHERE om.mid='".$mid."'";
		$resMRF = $this->db->query($qry);
		$resMRFS = $resMRF->result_array();
		echo json_encode($resMRFS, true); 
	}
	
	
	public function add_contact_details(){
		$name = $this->input->post('name');
		$phone = $this->input->post('phone');
		$employee = $this->input->post('employee');
		$sql = "INSERT INTO company_telephone_directory(name,tel_no_with_ext,tel_show_order) VALUES ('".$name."','".$phone."',34)";
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		echo $insert_id ;
		$sqlU = $this->db->query("UPDATE `internal_user` SET company_telephone_id = '".$insert_id."' WHERE login_id = '".$employee."'"); 
	}
	
	public function list_offices(){
		$this->mViewData['pageTitle'] = "List of Offices";
		$officesSQL = $this->db->query("SELECT * FROM company_branch WHERE status = 'A'");
		$this->mViewData['offices'] = $officesSQL->result_array();
		$this->render('hr/hr_information/list_offices_view', 'full_width',$this->mViewData);
	}
	
	public function add_location(){
		$location = $this->input->post('location');
		$controller = $this->input->post('controller');
		if($controller == 0){
			$qry = $this->db->query("INSERT INTO company_branch(branch_name) VALUES ('".$location."')");
		} else {
			$qry = $this->db->query("UPDATE company_branch SET branch_name = '".$location."' WHERE branch_id = '".$controller."'");
		}
	}
	
	public function delete_location(){
		$location_id = $this->input->post('location_id');
		$qry = $this->db->query('DELETE FROM company_branch WHERE branch_id = "'.$location_id.'"');
	}
	
	public function resources_general(){
		$this->mViewData['pageTitle']    = 'General resources';
		$this->render('hr/hr_information/list_resources_general', 'full_width',$this->mViewData);
		$this->load->view('script/hr/hr_information/resources_general_script');
	}
	
	public function resource_submit(){
		
		if($_FILES['file']['error'] == 0 && $_FILES['file']['type'] =='application/pdf')
		{                            					
			if(($_FILES['file']['name']) !=""){
				$path = $_FILES['file']['name'];
				$insertIUESql = "INSERT INTO resource_doc (topic_id, doc_title, doc_name, dttime) VALUES
				('".$this->input->post("topic_id")."', '".$this->input->post("doc_title")."', '".$path."', '".date('Y-m-d H:i:s')."')";
					$this->db->query($insertIUESql);
					$insert_id = $this->db->insert_id();
					
					
				
				$filename = strtolower(str_replace(' ','', 'rd_'.$insert_id.'_'.$this->input->post("doc_title")."_".date("YmdHis").'.'.pathinfo($path, PATHINFO_EXTENSION)));
				$config['upload_path'] = APPPATH.'../assets/share/docs/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
					
					
				if($this->upload->do_upload('file')){
					$fileData = $this->upload->data();
					$issueDate = "";
					
					$success = 'Uploaded Successfully';
				}
			}
		}
		redirect('/en/hr/resources_general');
	}
	
	public function get_aabsys_info()
	{  
		$result = $this->Hr_model->get_aabsys_info(); 
		echo json_encode($result); 
	}
	
	public function get_guidelines()
	{  
		$result = $this->Hr_model->get_guidelines(); 
		echo json_encode($result); 
	}  
	public function get_staff_format_rules()
	{  
		$result = $this->Hr_model->get_staff_format_rules(); 
		echo json_encode($result); 
	}
	
	public function delete_aabsys_docs_info(){
		$id = $this->input->post('id');
		$result = $this->Hr_model->delete_aabsys_docs_info($id); 
		echo '<div class="col-md-12"><div class="alert alert-success" role="alert">Document is Successfully Deleted</div>';
	}
	
	public function delete_staff_format_rules(){
		$id = $this->input->post('id');
		$result = $this->Hr_model->delete_staff_format_rules($id); 
		echo '<div class="col-md-12"><div class="alert alert-success" role="alert">Document is Successfully Deleted</div>';
	}
	
	public function policy_approval(){
		$this->mViewData['pageTitle']    = 'Policy approval';
		$success = "";
		if($this->input->post('editSubmit') == 'SUBMIT'){
			$s_event_name = $this->input->post('s_event_name');
			$ix_declared_leave = $this->input->post('ix_declared_leave');
			$dt_event_date = date_format(date_create($this->input->post('dt_event_date')),"Y-m-d");
			$branch = $this->input->post('branch');
			
			$query = "UPDATE  `declared_leave` SET s_event_name = '".$s_event_name."',branch = '".$branch."',dt_event_date='".$dt_event_date."' WHERE ix_declared_leave = '".$ix_declared_leave."'";
			$querys = $this->db->query($query);
			$success = "Event Date is Successfully Updated";
		}
		$this->mViewData['success_msg'] = $success;
		$this->render('hr/hr_information/list_policy_approval_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/hr_information/policy_approval_script');
	}
	
	public function get_list_policy_approval(){
	    $choose_year = date('Y');
	    $query = "SELECT p.*,DATE_FORMAT(p.created_at,'%D %b %Y') as new_DATE FROM emp_policy p WHERE p.created_at LIKE '$choose_year%' ORDER BY p.policy_id DESC";
	    $leave_listss = $this->db->query($query);
	    $leave_list = $leave_listss->result_array();
	    echo json_encode($leave_list);
		
	}
	
	public function add_policy_approval(){
		//set validation rules 
		$this->form_validation->set_rules('txtMessage', 'text Message', 'trim|required');
		$this->form_validation->set_rules('policy_title', 'policy_title', 'trim|required');

		//run validation on form input
		if ($this->form_validation->run() == FALSE)
		{  
			//validation fails 
			//$this->render('hr/alert_general_view', 'full_width', $this->mViewData);
		}
		else
		{
			$policy_content = $this->input->post('txtMessage');
			$policy_title = $this->input->post('policy_title');
			
			$sql = "INSERT INTO emp_policy (policy_title,policy_content, policy_status) VALUES ('".$policy_title."','".$policy_content."','1')";
			$this->db->query($sql);
		}
		redirect('/en/hr/policy_approval');
	}
	
	public function get_list_policy_approval_search(){
	    $choose_year = $this->input->post('searchyear');
	    $query = "SELECT p.*,DATE_FORMAT(p.created_at,'%D %b %Y') as new_DATE FROM emp_policy p WHERE p.created_at LIKE '$choose_year%' ORDER BY p.policy_id DESC";
	    $leave_listss = $this->db->query($query);
	    $leave_list = $leave_listss->result_array();
	    echo json_encode($leave_list); 
	}
	
	public function edit_policy_approval(){
		$s_event_name = $this->input->post('s_event_name');
		$ix_declared_leave = $this->input->post('ix_declared_leave');
		$dt_event_date = date_format(date_create($this->input->post('dt_event_date')),"Y-m-d");
		$branch = $this->input->post('branch');
		
		$query = "UPDATE  `declared_leave` SET s_event_name = '".$s_event_name."',branch = '".$branch."',dt_event_date='".$dt_event_date."' WHERE ix_declared_leave = '".$ix_declared_leave."'";
		$querys = $this->db->query($query);
		$this->render('hr/hr_information/list_holiday_view', 'full_width',$this->mViewData);
	    $this->load->view('script/hr/hr_information/list_holiday_script');
	}
	
	public function delete_policy_approval(){
		$id = $this->input->post('id');
		$sql = "DELETE FROM emp_policy WHERE policy_id = '".$id."'";
		$this->db->query($sql);
		echo '<div class="col-md-12"><div class="alert alert-success" role="alert">Policy is Successfully Deleted</div>';
	}
	/**************************** END/ HR Information Module  ********************************/
	
	
	/**************************** Leave carry forward Module  ********************************/
	public function carry_forward_contractual_leave(){
		
		$yy = date("Y"); 
		$prevYear = $yy - 1;

		$empSQL = "SELECT i.login_id , i.join_date, i.emp_type FROM `internal_user` i WHERE i.emp_type='C' AND i.user_status = '1' AND i.sal_sheet_sl_no != '0'";
			$empRes = $this->db->query($empSQL);
			$empINFOs = $empRes->result_array();
			$empNUM = COUNT($empINFOs);
			if($empNUM >0){
				$i = 0;
				$startYear = 2011;
				$yearCompletedAsPerThis = 0;
				foreach($empINFOs as $empINFO){
					 $joinDate = $empINFO['join_date'];
					 $joinYear = date('Y',strtotime($joinDate));
					 
					 $maxPL = $this->getMaxLeave($empINFO['login_id'], 'P', $prevYear);
					 $maxSL = $this->getMaxLeave($empINFO['login_id'], 'S', $prevYear);
					 $curLeave = $this->getLeaveTaken($empINFO['login_id'], '12', $prevYear, 'A');
					 $takenPL = 0;
					 $takenSL = 0;
					 if($curLeave['ob_pl'] > 0){
						 $takenPL = $curLeave['ob_pl'];
					 }
					 if($curLeave['ob_sl'] > 0){
						 $takenSL = $curLeave['ob_sl'];
					 }
					 $avlPL = $maxPL - $takenPL;
					 $avlSL = $maxSL - $takenSL;
					 $cfPL = $avlPL;
					 $cashPL = 0;
					/* if($empINFO['emp_type']=='C'){	 
						 if($joinYear > $startYear){
							$startYear = $joinYear;
						 }
						 $yearCompletedAsPerThis = $prevYear - $startYear;
						 
						 if($yearCompletedAsPerThis == 0){
							 if($avlPL > 10){
								$cashPL = $avlPL - 10;
								$cfPL = 10;
							}
						 }else if($yearCompletedAsPerThis == 1){
							 if($avlPL > 20){
								$cashPL = $avlPL - 20;
								$cfPL = 20;
							}
						 }else{
							   if($avlPL > 22){
								$cashPL = $avlPL - 22;
								$cfPL = 22;
								}
						}
						
						// Maximum Encash PL is 15
						if($cashPL > 15){
							$cashPL = 15;
						}
					} */
						
					 if($cfPL > 0){
						 $cfSQL = "SELECT `id` FROM `leave_carry_ forward` WHERE `year` = '".$yy."' AND `user_id` = '".$empINFO['login_id']."' LIMIT 1";
						 $cfRES = $this->db->query($cfSQL);
						$cefINFO = $cfRES->result_array();
						$cfNUM = COUNT($cefINFO);
						 if($cfNUM == 1){
							 $this->db->query("UPDATE `leave_carry_ forward` SET `ob_pl` = '".$cfPL."', `cf_pl` = '".$cfPL."' WHERE `user_id` = '".$empINFO['login_id']."' AND `year` = '".$yy."' LIMIT 1");
						 }
						 else{
							 //$this->db->query("UPDATE `leave_carry_ forward` SET `ob_pl` = '".$cfPL."', `cf_pl` = '".$cfPL."' WHERE `user_id` = '".$empINFO['login_id']."' AND `year` = '".$yy."' LIMIT 1");
							 $this->db->query("INSERT INTO `leave_carry_ forward` (user_id,year, ob_pl,ob_sl,cf_pl) VALUES ('".$empINFO['login_id']."','".$yy."','".$cfPL."','0.0','".$cfPL."')");
						 }
						echo $empINFO['login_id'].' = '.$cfPL.'<br/>';
					 }
				}
			}
			exit();
		
	}
	
	
	public function emp_attendance_report_export()
	{
		$dd_year = $this->input->get('searchYear');
		$dd_month = $this->input->get('searchMonth');
		$date_search = $_GET['aaYear'].'-'.$_GET['month'].'-01';
		
		$numberof_days = date('t', strtotime($date_search)); 
		
		$letter = array("", 'D2','E2','F2','G2','H2','I2','J2','K2','L2','M2','N2','O2','P2','Q2','R2','S2','T2','U2','V2','W2','X2','Y2','Z2','AA2','AB2','AC2','AD2','AE2','AF2','AG2','AH2','AI2');
		// Create new PHPExcel object
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("SantiBhusan Mishra")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		//setting the values of the headers and data of the excel file 
		$header = array();
		$empSummaryArray = array();
		$header = array("Sl No");
		$noOfColumnsSelected = 0;
		$selCols = "";
		$cond = "";
		
		
		array_push($header, "Employee Name");
		$noOfColumnsSelected++;
		
		array_push($header,"Designation");
		$noOfColumnsSelected++; 
		
		array_push($header, "ATTENDANCE");
		$noOfColumnsSelected++;
		
		
		for($i=1; $i <= $numberof_days; $i++)
		{
			array_push($header, $i);
			$noOfColumnsSelected++;	
		}
 
		foreach($header AS $i => $head){
			if($head == "Sl No")
			{
				$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);	
			}
			if($head == "Employee Name")
			{
				$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);	
			}
			if($head == "Designation")
			{
				$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);	
			}
			if($head == "ATTENDANCE")
			{
				$objPHPExcel->getActiveSheet()->mergeCells('D1:AH1');
				$objPHPExcel->getActiveSheet()->getStyle('D1:AH1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);	
			}
			for($i=1; $i <= $numberof_days; $i++)
			{	
				if($head == $i)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($letter[$i], $head);	
				}
			}
		}
		$dd_year = $this->input->get('aaYear');
		$dd_month = $this->input->get('month');
	
		if($dd_month == ''){
			$dd_month = date('m');
		} 
		
		if($dd_year == ''){
			$dd_year = date('Y');
		} 
		
		$startdateintime = strtotime($dd_year . '-' . $dd_month . '-01');
		$enddateintime = strtotime('+' . (date('t',$startdateintime) - 1). ' days',$startdateintime);
		$totalDays = intval((date('t',$startdateintime)),10);
		$startdate = date("Y-m-d", $startdateintime);
		$enddate = date("Y-m-d", $enddateintime);
		$YYmm = $dd_year.'-'.$dd_month;
		// Get Employees Attendance Details
		
		$empAttDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.isAttndAllowance, i.lwd_date, i.user_status , i.emp_type, i.branch, ud.desg_name 
								FROM `internal_user` i inner join `user_desg` ud on ud.desg_id = i.designation WHERE i.user_status != '0' AND i.user_status !='2' AND i.sal_sheet_sl_no != '0' AND  i.emp_type= 'F'";
		$empAttDetailsRes = $this->db->query($empAttDetailsQry);
		
		$empAttDetailsRes_arr = $empAttDetailsRes->result_array();
		 
		
	
		
		$array_filter = array();
		//$noofHolidays = $this->calculateHolidaysDaysInMonth($dd_year, $dd_month, $declaredHolidayArray);
		
		$empAttDetailsNum = COUNT($empAttDetailsRes_arr);
		$empAttSummary = Array();
		if($empAttDetailsNum >0)
		{
			$i = $ai  =0;
			foreach($empAttDetailsRes_arr as $empAttDetailsInfo)
			{
				
				$i++;
				
				$declaredHolidayArray = array();
				$holidaySql = "SELECT `dt_event_date` FROM `declared_leave` WHERE `f_disp_flag` = 1 AND DATE_FORMAT(`dt_event_date`, '%Y-%m') = '$YYmm' AND ( branch =0 OR branch = ".$empAttDetailsInfo['branch']." )";
				$holidayRes = $this->db->query($holidaySql);
				$holidayRes_arr = $holidayRes->result_array();
				foreach($holidayRes_arr as $holidayInfo)
				{
					//$declaredHolidayArray[] = $holidayInfo['dt_event_date'];
					array_push($declaredHolidayArray, $holidayInfo['dt_event_date']);
				}
				
				//Employee summary array
				$empSummary = array();
				array_push($empSummary,$i); 
				//array_push($empSummary,$empAttDetailsInfo['login_id']); 
				array_push($empSummary, $empAttDetailsInfo['full_name']);
				array_push($empSummary, $empAttDetailsInfo['desg_name']);
				
				$attndDays = $regDays = $PLDays = $SLDays = 0;
				$attendanceSql = "SELECT `att_status`, COUNT(`attendance_id`) AS total, `leave_type`, `date` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm' GROUP BY `att_status`, `leave_type`";
				
				$attendanceSql1 = "SELECT `date` FROM `attendance_new` WHERE `login_id` = '".$empAttDetailsInfo['login_id']."' AND DATE_FORMAT(`date`, '%Y-%m') = '$YYmm'";
				
				$attendanceRes = $this->db->query($attendanceSql);
				$attendanceRes_arr = $attendanceRes->result_array();
				
				$attendanceRes1 = $this->db->query($attendanceSql1);
				$attendanceRes_arr1 = $attendanceRes1->result_array();
				//print_r($attendanceRes_arr1);
				$attendanceNum = count($attendanceRes_arr);
				
				if($attendanceNum > 0)
				{
					for($j=1; $j<=$numberof_days;$j++)
					{
					$date = date('Y-m-d', strtotime($YYmm.'-'.$j));
						//$array_val = $this->search($attendanceRes_arr1, 'date', $date);
						if(in_array($date, $declaredHolidayArray)){
							array_push($empSummary, 'HD');
						}else if(date('D', strtotime($YYmm.'-'.$j)) == "Sun")
						{	
							array_push($empSummary, 'HD');
						}else if(date("Y-m-d", strtotime("first saturday ".$YYmm)) == $date){
							array_push($empSummary, 'HD');
						}else if(date("Y-m-d", strtotime("third saturday ".$YYmm)) == $date){
							array_push($empSummary, 'HD');
						//}else if(!empty($array_val))
						}else if(in_array($date, array_column($attendanceRes_arr1, 'date')))
						{
							$sql_attend = "select * from internal_user i right join attendance_new an on i.login_id = an.login_id where i.user_status != '0' AND i.user_status !='2' AND i.sal_sheet_sl_no != '0' AND i.emp_type= 'F' AND an.`date`='".$date."' and an.login_id = '".$empAttDetailsInfo['login_id']."' group by an.attendance_id order by an.login_id desc";	
							$attendanceResult = $this->db->query($sql_attend);
							$Res = $attendanceResult->result_array();
							
							//if($date == $attendanceInfo['date'])
							//{
								array_push($empSummary, $Res[0]['att_status']);
							//}	
						}else {
							array_push($empSummary, 'A');
						}
							
					}
					
				}

				$empSummaryArray[$ai++] = $empSummary;
			}
		}
		$totalKS = count($empSummaryArray);
		$row = 3;
		for($i=0; $i< $totalKS; $i++){
			foreach($empSummaryArray[$i] AS $col => $empInfo){
					$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$filename = "attendance_report".date('YmdHis').".xls";
		// Rename worksheet
		$objPHPExcel->getDefaultStyle()->applyFromArray(array(
				'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
				)
			));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:AE1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:AE1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd'.' - '.date('M', strtotime($YYmm)).'/'.date('Y', strtotime($YYmm)));

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A3:AZ3")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Increament Report ' .$this->input->post('selYear'));


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	public function get_user_info()
	{	
		/*if($_GET['department'] != "")
		{
			$department = $_GET['department'];
		}
		if($_GET['name'] != "")
		{
			
		}
		if($_GET[''] != "")
		{
			
		}
		if($_GET[''] != "")
		{
			
		}*/
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
							->setLastModifiedBy("HR Team")
							->setTitle("Online HR Master")
							->setSubject("Online HR Master")
							->setDescription("Online HR Master.")
							->setKeywords("Online HR Master")
							->setCategory("Online HR Master Export");

		$header = array();
		//$header = array("Sl No");
		
		
		array_push($header, "EmployeeCode");
		$noOfColumnsSelected = 0;
		
		array_push($header, "EmployeeName");
		$noOfColumnsSelected++;  
		
		array_push($header, "DeviceCode");
		$noOfColumnsSelected++;   

		array_push($header, "Company");
		$noOfColumnsSelected++;
		
		array_push($header, "Department");
		$noOfColumnsSelected++;
		
		array_push($header, "Location");
		$noOfColumnsSelected++;
		
		array_push($header, "Designation");
		$noOfColumnsSelected++;
		
		array_push($header, "Grade");
		$noOfColumnsSelected++;
		
		array_push($header, "Team");
		$noOfColumnsSelected++;
		
		array_push($header, "Category");
		$noOfColumnsSelected++;
		
		array_push($header, "EmploymentType");
		$noOfColumnsSelected++;
		
		array_push($header, "Gender");
		$noOfColumnsSelected++;
		
		array_push($header, "DOJ");
		$noOfColumnsSelected++;
		
		array_push($header, "DOC");
		$noOfColumnsSelected++;
		
		array_push($header, "CardNumber");
		$noOfColumnsSelected++;
		
		array_push($header, "ShiftRoaster");
		$noOfColumnsSelected++;
		
		array_push($header, "Status");
		$noOfColumnsSelected++;
		
		array_push($header, "DOR");
		$noOfColumnsSelected++;
		
		foreach($header AS $i => $head){
			$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($i).'1', $head);
		}
		
		$empSummaryArray = array();
		
		$empAttDetailsQry = "SELECT i.login_id, i.loginhandle, i.full_name, i.join_date, i.isAttndAllowance, i.lwd_date, i.user_status , i.emp_type, i.branch, ud.desg_name, dp.dept_name, i.gender, i.join_date 
								FROM `internal_user` i inner join `user_desg` ud on ud.desg_id = i.designation left join department dp on dp.dept_id = i.department WHERE i.user_status = '1' group by i.login_id order by i.login_id desc";
		$empAttDetailsRes = $this->db->query($empAttDetailsQry);
		
		$emp_details_res = $empAttDetailsRes->result_array();
		
		if(count($emp_details_res) >0)
		{
			$i = $ai  =0;
			foreach($emp_details_res as $empDetailsInfo)
			{
				$i++;
				$empSummary = array();
				//array_push($empSummary,$i); 
				
				array_push($empSummary, $empDetailsInfo['loginhandle']);
				array_push($empSummary, $empDetailsInfo['full_name']);
							
				array_push($empSummary, $empDetailsInfo['login_id']);
				array_push($empSummary, 'AABSYS');
				array_push($empSummary, $empDetailsInfo['dept_name']);
				array_push($empSummary, 'Default');				
				array_push($empSummary, $empDetailsInfo['desg_name']);	
				array_push($empSummary, 'Default');
				array_push($empSummary, 'Default');
				array_push($empSummary, 'Default');
				array_push($empSummary, 'Permanent');
				if($empDetailsInfo['gender'] == 'M')
				{
					$gender = 'Male';
				}else{
					$gender = 'FeMale';
				}
				array_push($empSummary, $gender);
				array_push($empSummary, date('d-m-Y', strtotime($empDetailsInfo['join_date'])));
				array_push($empSummary, date('d-m-Y', strtotime('01-04-2030')));
				array_push($empSummary, $i);
				array_push($empSummary, 'Default');
				array_push($empSummary, 'Working');
				array_push($empSummary, '');
				
				$empSummaryArray[$ai++] = $empSummary;
			}
		}
		
		$totalKS = count($empSummaryArray);
		$row = 2;
		$filename = "polosoft_emploee_records_".date('YmdHis').".xls";
		for($i=0; $i< $totalKS; $i++)
		{
			foreach($empSummaryArray[$i] AS $col => $empInfo)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->getNameFromNumber($col).$row, $empInfo);
			}
			$row++;
		}
		
		$objPHPExcel->getDefaultStyle()->applyFromArray( array(
			'borders' => array(
				'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )
			)
		));

		$objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1); 
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'POLOSOFT TECHNOLOGIES Pvt Ltd (Active Employee List)');

		$objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AZ2")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle('Active Employee List ' .date('Y'));

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel5)
		header('ntent-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	public function biometric_data_upload()
	{
		$this->mViewData['pageTitle'] = 'Biometric Data Upload';
		$successMsg = FALSE;
		$errMsg = "";
		$this->load->library('PHPExcel');  
		if($this->input->post('btnUploadBiometricData') == 'Upload'){
			//if($_FILES['flBiometricSheet']['name'] != '' && $_FILES['flBiometricSheet']['size'] > 0 && $_FILES['flBiometricSheet']['type'] == 'application/octet-stream')
			if($_FILES['flBiometricSheet']['name'] != '' && $_FILES['flBiometricSheet']['size'] > 0 )
			{
				//$path = $_FILES['flBiometricSheet']['name'];
				//$fileName = $_FILES['flBiometricSheet']['name'];
				 $fileName = strtolower(str_replace(array(' ', '-'),'',date("YmdHis") ."_". $_FILES['flBiometricSheet']['name']));
				 // $config['upload_path'] = APPPATH.'../assets/upload/document/';
				 // $config['allowed_types'] = 'xlsx|xls|ods';
				 // $config['file_name'] = $fileName;
				 // $this->load->library('upload', $config);
				 // $this->upload->initialize($config);
				 // $target_path = $config['upload_path'].$fileName;
				 $target_path = APPPATH.'../assets/upload/document/'. $fileName;	
				 //if($this->upload->do_upload('flBiometricSheet')){ 
				 if (move_uploaded_file($_FILES['flBiometricSheet']['tmp_name'], $target_path)) {
					$inputFileName = $target_path;
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray();
					$attendanceDate = "";
					
					$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
						<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
					</div>';
					$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
					<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
					<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
						&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
					</div>';
					$site_base_url= base_url();
				
					$subject = 'Low Working Hour';      
					//echo $message;
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";        
					 $headers .= "Importance: High\n";
					 $headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
					 $headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
					 $headers .= 'X-Mailer: PHP/' . phpversion();
					//echo '<pre>';print_r($sheetData); echo "</pre>";
					for($i=1;$i<sizeof($sheetData);$i++)
					{
						
						$inOutDataOfEmp = $sheetData[$i];
						
						if(sizeof($inOutDataOfEmp) == 7)
						{
							if($inOutDataOfEmp[1] != "")
							{
								$attendanceDate = date("Y-m-d", strtotime($inOutDataOfEmp[1]));
								$chkDeclLeaveSQL = "SELECT `ix_declared_leave` FROM `declared_leave` WHERE `dt_event_date` = '".$attendanceDate."' AND (branch='0' OR branch='".$this->session->userdata('branch')."') LIMIT 1";
								$chkDeclLeaveRES = $this->db->query($chkDeclLeaveSQL);
								$chkDeclLeaveINFO = $chkDeclLeaveRES->result_array();
								$chkDeclLeaveNUM = COUNT($chkDeclLeaveINFO);
							}
							if($attendanceDate != "" && $chkDeclLeaveNUM == 0)
							{
								//Get Emp ID
								$empIDSQL = "SELECT login_id,shift,department,user_role FROM internal_user WHERE loginhandle = '".$inOutDataOfEmp[2]."' AND user_status = '1' LIMIT 1";
								$empIDRES = $this->db->query($empIDSQL);
								$empIDInfo = $empIDRES->result_array(); 
								$empIDNUM = COUNT($empIDInfo); 
								if($empIDNUM == 1)
								{
									//$empIDINFO = mysql_fetch_row($empIDRES);
									$checkEntrySQL = "SELECT attendance_id, att_status FROM attendance_new WHERE login_id = ".$empIDInfo[0]['login_id']." AND date = '".$attendanceDate."' LIMIT 1";
									$checkEntryRES = $this->db->query($checkEntrySQL);
									$checkEntryINFO = $checkEntryRES->result_array();
									$checkEntryNUM = COUNT($checkEntryINFO);   // 0
									$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$empIDInfo[0]['login_id']."'";
									$repMgrRes = $this->db->query($repMgrSql);
									$repMgrInfo = $repMgrRes->result_array();	
									
									$message=<<<EOD
										<!DOCTYPE HTML>
										<html xmlns="http://www.w3.org/1999/xhtml">
										<head>
											<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
										</head>
										<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
											<div style="width:900px; margin: 0 auto; background: #fff;">
											 <div style="width:650px; float: left; min-height: 190px;">
												 <div style="padding: 7px 7px 14px 10px;">
												 <p>Dear {$repMgrInfo[0]['rfull_name']},</p>
												 <p>Your team member {$repMgrInfo[0]['rfull_name']} is having LWH on dated {$attendanceDate}.</p>                                                          
												 <p> In case of any Query, Please contact to HR Department.</p>                                 
												 <p>{$footer}</p>
												 </div> 
											  </div> 
											</div>  
											</div>
										</body>
										</html>
EOD;
									//Calculate Total Time Spent in Office
												 
									$sqlEmail = "select email,loginhandle,full_name from internal_user where login_id= '".$empIDInfo[0]['login_id']."'";
									//$resEmail = mysql_query($sqlEmail);
									$resEmail = $this->db->query($sqlEmail);
									//$rowEmail = mysql_fetch_row($resEmail); 
									$rowEmail = $resEmail->result_array();  //email
										
									$messageEmail=<<<EOD
									<!DOCTYPE HTML>
									<html xmlns="http://www.w3.org/1999/xhtml">
									<head>
										<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
									</head>
									<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
										<div style="width:900px; margin: 0 auto; background: #fff;">
										 <div style="width:650px; float: left; min-height: 190px;">
											 <div style="padding: 7px 7px 14px 10px;">
											 <p>Dear {$rowEmail[0]['full_name']},</p>                                 
											 <p>You had not punched the outtime against your task yesterday. </p>                                 
											 <p> In case of any Query, Please contact to HR Department.</p>                                 
											 <p>{$footer}</p>
											 </div> 
										  </div> 
										</div>  
										</div>
									</body>
									</html>
EOD;
									 $subjectEmail = 'Regarding Time Discrepancy in Production Report'; 
									 $toEmail=$rowEmail[0]['full_name'].'<'.$rowEmail[0]['email'].'>';
				 
									//print_r($empIDInfo); exit();
									$cond =""; $OutTime = $totTime = $lwh=0;
									if(($empIDInfo[0]['department']==6 || $empIDInfo[0]['department']==7))
									{
																		   
										if($empIDInfo[0]['shift']=='MS' || $empIDInfo[0]['shift']=='ES' || $empIDInfo[0]['shift']=='NS')
										{
											$totTime='28800';
										} 	
										else
										{
											$totTime='34200';
										} 
										if($inOutDataOfEmp[6]=='-')
										{
											//$OutTime = $inOutDataOfEmp[5]+$totTime;
											//mail($toEmail, $subjectEmail, $messageEmail, $headers);
										}
										else
										{
											$OutTime = $inOutDataOfEmp[6];                                
											$totalTime = $this->gtd($OutTime , $inOutDataOfEmp[5]);
										}
										
										if(($totalTime['0'] < $totTime) || $inOutDataOfEmp[6]=='-')
										{ 
											if($empIDInfo[0]['user_role']==5 || $empIDInfo[0]['user_role']==4 || $empIDInfo[0]['user_role']==3) {
												$cond=", att_status='W', shift='".$empIDInfo[0]['shift']."'";
											   $lwh=1;
											}
										}
									}
									
									if($inOutDataOfEmp[6] != '-')
									{
										$chkProductionSQL = "SELECT * FROM `task_subtask_time_log` WHERE DATE_FORMAT(start_date,'%Y-%m-%d') = '".$attendanceDate."' AND user_id=".$empIDInfo[0]['login_id']." AND end_date='0000-00-00 00:00:00' LIMIT 1";
										$chkProductionRES = $this->db->query($chkProductionSQL);
										$chkProductionROW = $chkProductionRES->result_array();
										$chkProductionNUM = count($chkProductionROW);
										if($chkProductionNUM == 1)
										{
											$end_date=$attendanceDate.' '.$OutTime;
											$updateQryRTimeLogres = $this->db->query("UPDATE `task_subtask_time_log` SET end_date='".$end_date."', spent_time='".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' AND user_id=".$empIDInfo[0]['login_id']."");
											$updateQryAssignRes = $this->db->query("UPDATE `tast_subtask_assignment` SET `actual_time` = `actual_time` + '".$totalTime['0']."' WHERE `id` = '".$chkProductionROW[0]['id']."' LIMIT 1");

										}
									}
									
									
									if($checkEntryNUM == 1)
									{
										if($checkEntryINFO[0]['att_status']=='L' || $checkEntryINFO[0]['att_status']=='R' || $checkEntryINFO[0]['att_status']=='H')
										{
											$attndSQL = "UPDATE attendance_new SET in_time = '".$inOutDataOfEmp[5]."', out_time = '".$inOutDataOfEmp[6]."' WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
										}
										else
										{
											$attndSQL = "UPDATE attendance_new SET in_time = '".$inOutDataOfEmp[5]."', out_time = '".$inOutDataOfEmp[6]."'".$cond." WHERE attendance_id = ".$checkEntryINFO[0]['attendance_id']." LIMIT 1";
										}
									}
									else
									{
										if($lwh)
										{
											/*if(mail($repMgrInfo[0]['email'], $subject, $message, $headers)){
												
											}
											else{
												
											}*/
											//echo "Reached In Email";
										}
										$attndSQL = "INSERT INTO attendance_new SET login_id='".$empIDInfo[0]['login_id']."', date='".$attendanceDate."', in_time='".$inOutDataOfEmp[5]."', out_time='".$inOutDataOfEmp[6]."'".$cond;
									}
									$attndRes = $this->db->query($attndSQL);
									$successMsg = "File is Uploaded Successfully ";
								}
							}
						}
						else
						{
							$errMsg = "Sorry!!! We are unable to process as file has been manually modified!";
						}
					}
					
				}
				else
				{
					//echo $this->upload->display_errors();
					$errMsg = "We are unable to upload the sheet! Please try later";
				}
			}
			else
			{
				$errMsg = "Please upload a valid file!";
			}
		}
		$this->mViewData['success_msg'] = $successMsg;
		$this->mViewData['error_msg'] = $errMsg;
		$this->render('hr/attendance_entry/biometric_data_upload_view', 'full_width',$this->mViewData);
		
		$this->load->view('script/hr/profile_list_script');
	}
	
	
}
