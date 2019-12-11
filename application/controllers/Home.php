<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home page
 */
class Home extends MY_Controller {

	var $mViewData = array('visit_type' => '','pageTitle' => '','file' =>'','reporting_manager'=>'');
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
		
		$this->load->model('event_model');
		ini_set('memory_limit', '1024M');
		if(empty($this->session->userdata('user_id')))
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
	
	
	/**
	 * logout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logout() 
	{ 
		// create the data object
		$data = new stdClass();
		
		if ($_SESSION['logged_in'] && $_SESSION['logged_in'] === true) 
		{ 
			// remove session datas
			foreach ($_SESSION as $key => $value) 
			{
				unset($_SESSION[$key]);
			} 
			// user logout ok
			/*$this->load->view('include/header');
			$this->load->view('user/logout/logout_success', $data);
			$this->load->view('include/footer');*/
			redirect(site_url('login'),'refresh');
		}
		else 
		{ 
			// there user was not logged in, we cannot logged him out,
			// redirect him to site root
			redirect('/'); 
		} 
	}	
	
	public function unauthorized() 
	{ 
		$this->load->view('unauthorized');
	}	

	public function index()
	{
		$this->mViewData['pageTitle'] = 'Home';
		//get resource marquee
		$this->mViewData['assets'] = $this->event_model->get_my_assets(); 
		$this->mViewData['resource_marque'] = $this->event_model->get_resource_marquee(); 
		$this->mViewData['director'] = $this->event_model->get_director_message();
		$this->mViewData['general_alert'] = $this->event_model->get_general_alert();
		$this->mViewData['telephone_no'] = $this->event_model->get_telephone_no();
		$this->mViewData['latest_classified'] = $this->event_model->get_latest_classified();
		$this->mViewData['hall_of_fame'] = $this->event_model->get_hall_of_fame();
		//get news and events
		$this->mViewData['calender'] = $this->event_model->get_news_and_events_calender(); 
		$this->mViewData['today'] = $this->event_model->get_news_and_events_today(); 
		$this->mViewData['weekly'] = $this->event_model->get_news_and_event_weekly();
		$this->mViewData['monthly'] = $this->event_model->get_news_and_event_monthly();
		$this->mViewData['upcoming'] = $this->event_model->get_news_and_event_upcoming();
		$this->mViewData['archive'] = $this->event_model->get_news_and_events_archive(); 
		//$this->data['wStartDate'] = $this->week_start_date($wk_num, $yr, $first = 0, $format = 'm/d/Y');
		 //get aniversarry
		$mm = date("m");
		$yy = date("Y");
		$this->mViewData['aniversarry_this_month'] = $this->event_model->get_aniversarry_this_month(); 
		//var_dump($this->mViewData['aniversarry_this_month']);
		$this->mViewData['aniversarry'] = $this->event_model->get_upcoming_aniversarry(); 
		
		//get_greetings
		$this->mViewData['birthDayWish'] = $this->event_model->get_birthday_wishes($this->session->userdata('user_id')); 
		
		$user_id = $this->session->userdata('user_id');
		$chkDuplicateSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$user_id."' AND `date` = '".date("Y-m-d")."' LIMIT 1";
        $chkDuplicateRES = $this->db->query($chkDuplicateSQL);
        $chkDuplicateRow = $chkDuplicateRES->result_array();
        if(count($chkDuplicateRow) == 0){
           // mysql_query("INSERT INTO `attendance_new` (`login_id`, `date`, `in_time`) VALUES ('".$_SESSION['user_id']."', '".date("Y-m-d")."', '".date("H:i:s")."')");
            $this->session->set_userdata('showAttendanceBox', 'YES');
        }
		else{
			$this->session->set_userdata('showAttendanceBox', 'NO');
		}
		//get_greetings
		$unique_pi = $this->db->query("select unique_pin from annual_appraisal where login_id='".$user_id."' ORDER BY `mid` DESC LIMIT 1");
		$get_unique_pin = $unique_pi->result_array();
		$this->mViewData['get_unique_pin'] = "";
		if(count($get_unique_pin)>0){
			$this->mViewData['get_unique_pin'] = $get_unique_pin[0]['unique_pin']; 
		}
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$dyear = date("Y")+1;
		}
		else{
			$dyear = date("Y");
		}
		
		
		$qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$user_id."' AND DATE_FORMAT(annualdate, '%Y') = ".$dyear;
		$qryRes = $this->db->query($qryGoal);
		$qryInfo = $qryRes->result_array();
		$goalsheetDatas = array();
		for($i=0; $i< count($qryInfo); $i++) {
			$mid = $qryInfo[$i]['mid'];
			$qryGoalLog = "SELECT * FROM `goal_sheet_log` WHERE login_id = '".$user_id."' AND mid = ".$mid." group by progress order by progress asc";
			$qryResLog = $this->db->query($qryGoalLog);
			$qryInfoLog = $qryResLog->result_array();
			$goalsheetDatas[] = array(
				'mid' => $mid,
				'login_id' => $qryInfo[$i]['login_id'],
				'objective' => $qryInfo[$i]['objective'],
				'target' => $qryInfo[$i]['target'],
				'weightage' => $qryInfo[$i]['weightage'],
				'progress' => $qryInfo[$i]['progress'],
				'annualdate' => $qryInfo[$i]['annualdate'],
				'goalLog' => $qryInfoLog
			);
		}
		//echo '<pre>';print_r($goalsheetDatas);echo '</pre>';
		$this->mViewData['qryInfo'] = $goalsheetDatas;
		
		$resPolicy = array();
		if($user_id == '11180'){
			$qryPolicy = "SELECT p.*, pa.approve_status, pa.approved_date 
			FROM `emp_policy` p  
			LEFT JOIN emp_policy_approval pa ON pa.policy_id=p.policy_id AND pa.login_id = '".$user_id."' 
			WHERE p.policy_status='1' ORDER BY p.policy_id DESC";
			//echo $qryPolicy;
			$qryPol = $this->db->query($qryPolicy);
			$resPolicy = $qryPol->result_array();
		}
		$this->mViewData['policyRes'] = $resPolicy; 
		//Template view 
		$this->render('home', 'full_width',$this->mViewData);
		$this->load->view('script/home/home_js');
	} 

	public function punch_attendance()
	{
		$user_id = $this->session->userdata('user_id');
		$chkDuplicateSQL = "SELECT `attendance_id` FROM `attendance_new` WHERE `login_id` = '".$user_id."' AND `date` = '".date("Y-m-d")."' LIMIT 1";
        $chkDuplicateRES = $this->db->query($chkDuplicateSQL);
        $chkDuplicateRow = $chkDuplicateRES->result_array();
        if(count($chkDuplicateRow) == 0){
           $this->db->query("INSERT INTO `attendance_new` (`login_id`, `date`, `in_time`) VALUES ('".$user_id."', '".date("Y-m-d")."', '".date("H:i:s")."')");
            $this->session->set_userdata('showAttendanceBox', 'NO');
        }
		else{
			$this->session->set_userdata('showAttendanceBox', 'YES');
		}
	} 

	public function approv_policy_emp()
	{
		$user_id = $this->session->userdata('user_id');
		$policy_id = $this->input->post('policy_id');
        $this->db->query("INSERT INTO `emp_policy_approval` (`login_id`, `policy_id`, `approve_status`) VALUES ('".$user_id."', '".$policy_id."', '1')");
		
		$qryPolicy = "SELECT p.*, pa.approve_status, pa.approved_date, i.full_name, i.user_sign_name 
			FROM `emp_policy` p  
			INNER JOIN emp_policy_approval pa ON pa.policy_id=p.policy_id 
			INNER JOIN internal_user i ON i.login_id=pa.login_id 
			WHERE pa.policy_id='".$policy_id."' AND p.policy_status='1' AND pa.login_id = '".$user_id."'  ORDER BY p.policy_id DESC";
			//echo $qryPolicy;
			$qryPol = $this->db->query($qryPolicy);
			$resPolicy = $qryPol->result_array();
			$datas['resPolicy'] = $resPolicy;
			//print_r($datas['resPolicy']);
			
        
		$html=$this->load->view('myprofile/create_policy_pdf', $datas, true); 
		//echo $html;exit;
		
		
		$pdfFilePath = $user_id."-policy-" .date('dmYHis'). ".pdf";
		
		$this->db->query("INSERT INTO `emp_letter` (`login_id`, `letter_name`, `letter_document`, `issued_date`) VALUES ('".$user_id."', '".$resPolicy[0]['policy_title']."', '".$pdfFilePath."', '".date("Y-m-d")."')");
		$this->load->library('Pdf');

		$pdf = $this->pdf->load();

		$pdf->WriteHTML($html); // write the HTML into the PDF
		$pdf->Output('./assets/upload/document/'.$pdfFilePath ,'F');
		//$pdf->Output($pdfFilePath, "D");
		echo 1;
	} 
	
	
	public function download_generate_res()
	{
		$this->load->helper('download');
		$doc_id = $this->input->get('doc_id');
		$doc_name = $this->input->get('doc_name');      
		$result = $this->event_model->download_resource_marquee($doc_id,$doc_name); 
		$count=  count($result);
		if($count >0)
		{
			$filename='rd_'.$doc_id.'_'.$doc_name;
			//$file = $result[0]['doc_id'].$result[0]['doc_name']; 
			$file = file_get_contents(base_url('assets/share/docs/'.$filename)); 
			force_download($filename, $file); 
			
		}
	} 	 
	/**
	 * Reset Password function.
	 * 
	 */
	public function reset_password() 
	{ 
		$newPassword = $this->input->post('newPassword');
		$oldPassword = $this->input->post('oldPassword');
		$user_id = $this->session->userdata('user_id');
		$check_old_password = $this->event_model->check_old_password($oldPassword, $user_id);
		if(count($check_old_password) > 0){
			$new_password = $this->event_model->update_new_password($newPassword, $user_id);
			echo 2;
		}
		else{
			echo 1;
		}
	}
	public function check_old_password() 
	{ 
		$oldPassword = $this->input->post('oldPassword');
		$user_id = $this->session->userdata('user_id');
		$check_old_password = $this->event_model->check_old_password($oldPassword, $user_id);
		if(count($check_old_password) > 0){
			echo 2;
		}
		else{
			echo 1;
		}
	}
	
	
	public function search_staff_directory()
	{
		$search_key = $this->input->post('search_key');
		$searchData = $this->event_model->get_telephone_no_search($search_key);
		echo json_encode($searchData);
	}
	
	//submit feedback form
	public function submit_feedback()
	{
		$user_id = $this->session->userdata('user_id');

		if($user_id !=""){
			$insertFeedbackSql = "INSERT INTO `employee_feedback` (`posted_by`,`title`,`desc`,`read`,`date`,`n_disp_flag`)
							VALUES('".$user_id."','".$this->input->post('feedback_title')."','".$this->input->post('feedback_description')."','N','".date('Y-m-d H:i:s')."','1')";
			$x = $this->db->query($insertFeedbackSql);	
			
			/* $empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$user_id."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/AABSyS" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/336775" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			$title = $this->input->post('feedback_title');
			$subject = $title.' - From - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('hr_help_desk/my_midyear_review');
			$description = $this->input->post('feedback_description');
			
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
                 <p>Dear Sir,</p>                                 
				 <p>{$empInfo[0]['full_name']} has submitted the Feedback Form</p>
				 <p> Title :{$title} </p>
				 <p> Description :{$description} </p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

			$to = 'hr@polosoftech.com';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'X-Mailer: PHP/' . phpversion(); */
			//mail($to, $subject, $messageApp, $headers);
			
			//print $x;exit;
			echo 1;
		}
		else{
			echo 0;
		}
	}
	
	//advance filter
	public function get_departments()
	{
		$result = $this->event_model->get_department(); 
		echo json_encode($result); 		
	}
	
	public function get_designation()
	{
		$dept_id = $this->input->post('searchDepartment');
		$result = $this->event_model->get_designation_by_department($dept_id); 
		echo json_encode($result);  		
	}
	
	public function get_all_emp_advance_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->event_model->get_all_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function get_all_emp_advance_search_filter()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchStatus = $this->input->post('searchStatus');
		$result = $this->event_model->get_all_emp_advance_search_filter($searchDepartment , $searchName, $searchDesignation , $searchEmpCode, $searchStatus); 
		echo json_encode($result); 
	}
	
	public function get_all_active_emp_advance_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->event_model->get_all_active_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
	public function get_all_inactive_emp_advance_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->event_model->get_all_inactive_emp_advance_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result);  
	}
	
}
