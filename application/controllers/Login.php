<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
		
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->helper('cookie');
		if($this->session->userdata('user_id') !="")
		{
			redirect(site_url('home'),'refresh');
		} 
		$this->mViewData['countEventToday'] = 0;
		$this->mViewData['countRegularizationPending'] = 0;
		$this->mViewData['countLeavePending'] = 0;
		$this->mViewData['countRegularizationPendingRM'] = 0;
		$this->mViewData['countLeavePendingRM'] = 0;
	}
	public function index()
	{
		$this->mViewData['pageTitle']    = 'Login';
		// create the data object
		$mViewData = new stdClass();
		$tok_str = "3d6e89c63cab98b4d95e4ebb908c5cec";
		// Check whether user is loged in
		if($this->session->userdata('user_id') > 0)
		{
			//redirect(site_url('en/home'),'refresh');
			exit();
		}

		foreach($_POST AS $i => $val)
		{
			$$i = $val;
		}

		if($this->input->post('hid_status') == 'login')
		{
			// load form helper and validation library
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// set validation rules
			$this->form_validation->set_rules('txt_email', 'Employee ID', 'trim|required');
			$this->form_validation->set_rules('txt_pwd','Password','trim|required|min_length[4]|max_length[40]'); 
			
			if($this->form_validation->run() == TRUE)
			{
				// set variables from the form
				$txt_email = $this->input->post('txt_email');
				$txt_pwd = $this->input->post('txt_pwd');
				// Get data from user table if exists 
				$res = $this->login_model->get_login_id($txt_email);
				$rowcount = count($res); //get no. of records
				//$row = $res->result_array();
				//var_dump($row); 
				 
				$mysql_qryrr = $this->login_model->get_block_login_id($txt_email);
				$rowcountrr = count($mysql_qryrr); //get no. of records
						
				if($rowcount <= 0)
				{
					if($rowcountrr == 1)
						$this->mViewData['error'] = 'Your ID has blocked. Please contact to HR Dept. ';                      
					else   
						$this->mViewData['error'] = 'User Name / Password is incorrect';                    
				}
				else
				{
					
					$txt_pwds = stripslashes($res[0]['password']);
					if($txt_pwds != md5(trim($txt_pwd)))
					{
						$this->mViewData['error'] = 'User Name / Password is incorrect';
					}
					else
					{
						//Get data from internal_user table 
						$user_login_id = $res[0]['login_id'];
						$user_data = $this->login_model->get_user($user_login_id);  
						$check_isAReportingManager = $this->login_model->check_isAReportingManager($user_login_id); 
						$isAReportingManager = 'NO';
						if(count($check_isAReportingManager)>0){
							$isAReportingManager = 'YES';
						}
						
						$check_isDepartmentHead = $this->login_model->check_isDepartmentHead($user_login_id); 
						$isDepartmentHead = 'NO';
						if(count($check_isDepartmentHead)>0){
							$isDepartmentHead = 'YES';
						}
						 
						$_SESSION['is_valid_user'] = @urlencode(@md5($tok_str.SITE_BASE_URL));
						$array_session = array( 
									'is_valid_user'      	=> (string)$user_data[0]['is_valid_user'],
									'user_id'      			=> (string)$user_data[0]['login_id'],
									'user_role'     		=> (string)$user_data[0]['user_role'],
									'emp_type'     		    => (string)$user_data[0]['emp_type'],
									'user_rlname'    		=> (string)$user_data[0]['user_role_name'],
									'user_name'   			=> (string)$user_data[0]['name_first'],
									'user_group'			=> (string)$user_data[0]['user_group'],
									'user_desg'    			=> (string)$user_data[0]['desg_name'],
									'is_assistant_admin' 	=> (string)$user_data[0]['is_assistant_admin'],
									'is_manager' 			=> (string)$user_data[0]['is_manager'],
									'is_supervisor' 		=> (string)$user_data[0]['is_supervisor'],
									'parent_id' 			=> (string)$user_data[0]['parent_id'],
									'empCode' 				=> (string)$user_data[0]['loginhandle'],
									'user_dept' 			=> (string)$user_data[0]['dept_name'],
									'department' 			=> (string)$user_data[0]['department'],
									'remote_access' 		=> (string)$user_data[0]['remote_access'],
									'branch'     			=> (string)$user_data[0]['branch'],
									'user_photo_name'     	=> (string)$user_data[0]['user_photo_name'],
									'production_role'		=> 'M',
									'user_type'             => (string)$user_data[0]['user_type'],
									'isAReportingManager'             => (string)$isAReportingManager,
									'isDepartmentHead'             => (string)$isDepartmentHead,
									'logged_in' 			=> TRUE 
								);
						$this->session->set_userdata($array_session);
						/* $_SESSION['user_role'] = $data[0]['user_role'];
						$_SESSION['user_id'] = $data[0]['login_id'];
						$_SESSION['user_rlname'] = $data[0]['user_role_name']; // Not Used in this Application, Check it again
						$_SESSION['user_name'] = $data[0]['name_first'];
						$_SESSION['user_group'] = $data[0]['user_group'];
						$_SESSION['user_desg'] = $data[0]['desg_name']; // Not Used in this Application, Check it again
						$_SESSION['is_assistant_admin'] = $data[0]['is_assistant_admin'];
						$_SESSION['is_manager'] = $data[0]['is_manager'];
						$_SESSION['is_supervisor'] = $data[0]['is_supervisor'];
						$_SESSION['parent_id'] = $data[0]['parent_id']; // Check the Use of this value
						$_SESSION['empCode'] = $data[0]['loginhandle'];
						$_SESSION['user_dept'] = $data[0]['dept_name'];			
						$_SESSION['remote_access'] = $data[0]['remote_access'];
						$_SESSION['branch'] = $data[0]['branch'];
						$_SESSION['user_type'] = $data[0]['user_type'];
						$_SESSION['logged_in'] 	= TRUE; */
						//if($_SESSION['user_dept'] == 'Production'){
						$_SESSION['production_role'] = 'M';
						// Get Position
						$posSQL = "SELECT `position` FROM `production_resources` WHERE `user_id` = '".$row[0]['login_id']."' LIMIT 1";
						$posRES = $this->db->query($posSQL);
						$posNUM = count($posRES);
						if($posNUM > 0)
						{
							$posINFO = $posRES->result_array();
							$_SESSION['production_role'] = $posINFO[0]['position'];
						}
						//}
						
						if($_SESSION['empCode'] == 'administrator')
						{
							$_SESSION['user_dept'] = 'Administrator';
							$_SESSION['user_desg'] = 'Administrator'; // Not Used in this Application, Check it again
						}
						
						$_SESSION['showAttendanceBox'] = TRUE;
						$_SESSION['showKeyBox'] = TRUE;
						setcookie("loginOpen", FALSE, time()+1440); 
										
						$remember_me = 	$this->input->post('remember_me');			
						if($remember_me)
						{
							// Set cookie to last 1 year
							$str1 = $this->input->post('txt_email');
							$str2 = $this->input->post('txt_pwd');

							setcookie('cookname', $str1, time()+60*60*24*365);
							setcookie('cookpass', $str2, time()+60*60*24*365);
							setcookie('cookremember', 1, time()+60*60*24*365);
						}
						else
						{
							setcookie('cookname', '', time()+60*60*24*365);
							setcookie('cookpass', '', time()+60*60*24*365);
							setcookie('cookremember', '', time()+60*60*24*365);
						}
						 
						if(trim($url_to_go) == '')
						{
							$url_to_go = FL_HOME;
						}
						if(trim($attach_str) != '') $url_to_go .= "?".$attach_str;
						header("location:".$url_to_go);
						exit();
					}
				}
			}
		}
		else
		{
			$this->mViewData['txt_email'] = set_cookie('cookname');
			$this->mViewData['txt_pwd'] = set_cookie('cookpass');
			$this->mViewData['remember_me'] =set_cookie('cookremember');
		}
		// Get Birthday Details
		$sDate = date('m-d');
		$sQry = "SELECT `login_id`,`full_name`, `dob_with_current_year`, `user_photo_name` FROM internal_user WHERE `dob_with_current_year` > '$sDate' AND `user_status` = '1' ORDER BY `dob_with_current_year` LIMIT 4";
		$sRes = $this->db->query($sQry); 
		$this->mViewData['sInfo_arr'] = $sRes->result_array();
		
		// Get Birthday Details
		$sQrys = "SELECT `login_id`,`full_name`, `dob`, `user_photo_name` FROM internal_user WHERE DATE_FORMAT(dob, '%m-%d') > '$sDate' AND `user_status` = '1' ORDER BY `dob_with_current_year` LIMIT 4";
		$sRess = $this->db->query($sQrys); 
		$this->mViewData['birthdayInfo'] = $sRess->result_array();
		$this->render('login/login_view', 'empty'); 
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
			redirect(base_url('')); 
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
}
