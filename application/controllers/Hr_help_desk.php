<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hr_help_desk extends MY_Controller 
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
		
		$this->load->helper('url'); 
		$this->load->helper('file');  
		$this->load->helper('form');  
		$this->load->library('session');
		$this->load->model('Hr_model');
		$this->load->model('event_model');
		$this->load->model('login_model');
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
		//$this->load_view();
	}
	
	public function payroll_help()
	{
		$this->mViewData['pageTitle']    = 'Payroll Help';
		$this->mViewData['scsmsg']    = '';
		$this->load->library('email');
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{ 
			//set validation rules 
			$this->form_validation->set_rules('txtMessage', 'text Message', 'trim|required');

			//run validation on form input
			if ($this->form_validation->run() == FALSE)
			{  
				//validation fails 
				//$this->render('hr_help_desk/payroll_help_view', 'full_width', $this->mViewData);
			}
			else
			{
				//get the form data
				$resAllEmp = $this->db->query("SELECT * FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'");
				//$rowAllEmp =  $resAllEmp->result_array();
				//var_dump($rowAllEmp);exit;
				if($rowAllEmp=  $resAllEmp->result_array()){
				$message = '';
				//$loginID = $rowAllEmp['login_id'];

				
				$message=<<<EOD
					<!DOCTYPE HTML>
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body><div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:100%; float: left; border: solid 1px cornflowerblue;min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                 {$this->input->post('txtMessage')}                                
                                 </div> 
                              </div>                              
                        </div>  
                      </div></body>
                                 </html>
EOD;
                                 

                                 
        $to ='SantiBhusan Mishra <hr@polosoftech.com>';
        $subject = 'Payroll Help Desk';      
        //echo $message;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Priority: 1 (Highest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n";
        $headers .= 'From:'.$rowAllEmp[0]['email'] . "\r\n";  
        $headers  .= 'Reply-To: '.$rowAllEmp[0]['email'] . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

    $mail = mail($to, $subject, $message, $headers);
    //echo $mail ? "Mail sent" : "Mail failed";
    //exit;
    
    
  $query=<<<EOD
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                       </head> <body><div style="width:100%; font-family: verdana; font-size: 13px;">
                        <div style="width:900px; margin: 0 auto; background: #fff;">
                             <div style="width:100%; float: left; border: solid 1px cornflowerblue;min-height: 190px;">
                                 <div style="padding: 7px 7px 14px 10px;">
                                <p>Dear {$rowAllEmp[0]["full_name"]},</p>
                                 <p>Thanks for your mail. We will get back to you soon.</p>
                                 <p>Thanks and Regards<br />
                                 HR Department<br />
                                 POLOSOFT TECHNOLOGIES Pvt. Ltd.</p>
                                 </div> 
                              </div>                              
                        </div>  
                      </div></body>
                                 </html>
EOD;
  
				$tto =$rowAllEmp[0]['email'];
				$subjectt = 'Payroll Help Desk';      
				//echo $message;
				$header  = 'MIME-Version: 1.0' . "\r\n";
				$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$header .= "X-Priority: 1 (Highest)\n"; 
				$header .= "X-MSMail-Priority: High\n"; 
				$header .= "Importance: High\n";
				$header .= 'From: Mr. SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";  
				$header  .= 'Reply-To: Mr. SantiBhusan Mishra <hr@polosoftech.com>' . "\r\n";
				$header .= 'X-Mailer: PHP/' . phpversion();

				$mail = mail($tto, $subjectt, $query, $header);
				$this->mViewData['scsmsg']    = 'Submitted Successfully';
			  }
			}
		}
		//Template view
		$this->render('hr_help_desk/payroll_help_view', 'full_width', $this->mViewData);		
		 
	}
	
	public function assign_shift()
	{
		$this->mViewData['pageTitle']    = 'Assign Shift';
		$result = $this->Hr_model->get_assignment_shift();
		$this->mViewData['employeeList'] = $result;
		//Template view
		$this->render('hr_help_desk/assign_shift_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/assign_shift_script');
	}
	
	public function update_asign_shift()
	{
		$shift = $this->input->post('shift');
		$login_id = $this->input->post('login_id');
		$result = $this->Hr_model->update_asign_shift($shift,$login_id);
		echo 1;
	}
	
	public function update_emp_division()
	{
		$division = $this->input->post('division');
		$login_id = $this->input->post('login_id');
		$result = $this->Hr_model->update_emp_division($division,$login_id);
		echo 1;
	}
	/*Start Ajax with angularjs function*/
	public function get_assign_shift()
	{  
		$result = $this->Hr_model->get_assignment_shift(); 
		echo json_encode($result); 
	}
	/*End*/
	public function download_salary_slip()
	{
		$this->mViewData['pageTitle'] = 'Download Salary Slip';
		
		$this->load->helper('download');
		$msg ='';
		$slip ='';
		
		if($this->input->post('mailSalarySlip') == 'Submit')
		{
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear'); 
			$login_id = $this->session->userdata('user_id');			
			$result = $this->Hr_model->get_download_salary_slip_emp($month,$year,$login_id);
			//print_r($result);exit;
			$count=  count($result);
			if($count >0)
			{
				$filename='salary_slip-'.$month.'-'.$year.'.pdf';
				$slip = $result[0]->content; 
				//echo $slip; exit;
				//$this->download_salary_slip_pdf($slip,$filename); 
				/* if($login_id == '11311'){
					//echo $slip; exit;
					$html = $slip;
					$this->load->library('Pdf');

					$pdf = $this->pdf->load();
					
					$pdf->WriteHTML($slip);
					//$pdf->writeHTML($html, true, false, true, false, '');
					$pdf->Output($filename, "D");
				}
				else{
					$html = $slip;
					$this->load->library('Pdf');

					$pdf = $this->pdf->load();
					
					$pdf->WriteHTML($slip);
					//$pdf->writeHTML($html, true, false, true, false, '');
					$pdf->Output($filename, "D");
				} */
			}
			else 
			{
				$msg ='Salary slip not found!';
			} 
		}
		
		$this->mViewData['slip'] =$slip;
		$this->mViewData['msg'] =$msg;
		//Template view
		$this->render('hr_help_desk/download_salary_slip_view', 'full_width', $this->mViewData); 
	}
	public function download_salary_slip_doenload()
	{
		$this->load->helper('download');
		$msg ='';
		$slip ='';
			$month = $this->input->get('m');
			$year = $this->input->get('y'); 
			$login_id = $this->session->userdata('user_id');			
			$result = $this->Hr_model->get_download_salary_slip_emp($month,$year,$login_id);
			//print_r($result);exit;
			$count=  count($result);
			if($count >0)
			{
				$filename='salary_slip-'.$month.'-'.$year.'.pdf';
				$slip = $result[0]->content; //echo $slip; exit;
				$this->download_salary_slip_pdf($slip,$filename); 
				/* if($login_id == '11311'){
					//echo $slip; exit;
					$html = $slip;
					$this->load->library('Pdf');

					$pdf = $this->pdf->load();
					
					$pdf->WriteHTML($slip);
					//$pdf->writeHTML($html, true, false, true, false, '');
					$pdf->Output($filename, "D");
				}
				else{
					$html = $slip;
					$this->load->library('Pdf');

					$pdf = $this->pdf->load();
					
					$pdf->WriteHTML($slip);
					//$pdf->writeHTML($html, true, false, true, false, '');
					$pdf->Output($filename, "D");
				} */
			}
	}
	
	public function download_salary_slip_pdf($slip,$filename)
	{
		$html = $slip;
		$this->load->library('Pdf');

		$pdf = $this->pdf->load();
		
		$pdf->WriteHTML($html);
		//$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output($filename, "D");
	}
	public function employee_suggestion()
	{
		$this->mViewData['pageTitle']    = 'Employee Suggestion';
		//Template view
		$this->render('hr_help_desk/employee_suggestion_view', 'full_width', $this->mViewData);	 
	}
	public function add_reimbursement()
	{
		$this->mViewData['pageTitle']    = 'Add Reimbursment';
		//Template view
		$this->render('hr_help_desk/add_reimbursement_view', 'full_width', $this->mViewData); 
	}
	public function income_tax()
	{
		$this->mViewData['pageTitle']    = 'Income Tax';
		//Template view
		$this->render('hr_help_desk/income_tax_view', 'full_width', $this->mViewData); 
	}
	public function resume_form()
	{
		$this->mViewData['pageTitle']    = 'Resume Form';
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		
		$qryResume = "SELECT * FROM `resume_form` WHERE login_id = '".$user_id."'";
		$resResume=$this->db->query($qryResume);
		$rowResume = $resResume->result_array();
		$this->mViewData['rowResume']    = $rowResume;
		
		$qryLang = "SELECT * FROM `resume_lang` WHERE login_id = '".$user_id."'";
        $resLang = $this->db->query($qryLang); 
		$rowLang = $resLang->result_array();
		$this->mViewData['rowLang']    = $rowLang;
		
		$qryComp = "SELECT * FROM `resume_comp` WHERE login_id = '".$user_id."'";
        $resComp = $this->db->query($qryComp);
		$rowComp_arry = $resComp->result_array();
		$rowComp = array();
		for($j=0; $j<count($rowComp_arry); $j++){   
		
			$qryCompProject = "SELECT * FROM `resume_comp_project` WHERE cid = '".$rowComp_arry[$j]['cid']."'";
            $resCompProject = $this->db->query($qryCompProject);
			$rowCompProject = $resCompProject->result_array();  
			$data = array(
				'cid' => $rowComp_arry[$j]['cid'],
				'login_id' => $rowComp_arry[$j]['login_id'],
				'comp_name' => $rowComp_arry[$j]['comp_name'],
				'designation' => $rowComp_arry[$j]['designation'],
				'year_exp' => $rowComp_arry[$j]['year_exp'],
				'rowCompProject' => $rowCompProject
			);
			array_push($rowComp, $data);
		}
		$this->mViewData['rowComp']    = $rowComp;
		//Template view
        $this->render('hr_help_desk/resume_form_view', 'full_width', $this->mViewData); 
	}
	public function resume_form_submit()
	{
		$this->mViewData['pageTitle']    = 'Resume Form';
		$user_id = $this->session->userdata('user_id');
		if (isset($_GET['id']))
		{
			$user_id = $_GET['id'];
		}
		 
			$resMessage = $this->db->query("SELECT * FROM `resume_form` WHERE login_id='".$user_id."'");
			$rowMessage = $resMessage->result_array();
			if(count($rowMessage)>0)
			{	
			   $insertSql ="UPDATE `resume_form` SET introduction = '".$this->input->post('introduction')."', 
							cad_software = '".$this->input->post('cad_software')."', gis_software= '".$this->input->post('gis_software')."',tech_languages = '".$this->input->post('tech_languages')."',
							operating_system = '".$this->input->post('operating_system')."', tech_others= '".$this->input->post('tech_others')."',functional_areas = '".$this->input->post('functional_areas')."',
							professional_skills_resp = '".$this->input->post('professional_skills_resp')."', education = '".$this->input->post('education')."', workshops= '".$this->input->post('workshops')."',
							awards_excellence = '".$this->input->post('awards_excellence')."' WHERE login_id='".$user_id."'";
			 }else{
				$insertSql ="INSERT INTO `resume_form` SET login_id='".$user_id."', introduction = '".$this->input->post('introduction')."', 
							cad_software = '".$this->input->post('cad_software')."', gis_software= '".$this->input->post('gis_software')."',tech_languages = '".$this->input->post('tech_languages')."',
							operating_system = '".$this->input->post('operating_system')."', tech_others= '".$this->input->post('tech_others')."',functional_areas = '".$this->input->post('functional_areas')."',
							professional_skills_resp = '".$this->input->post('professional_skills_resp')."', education = '".$this->input->post('education')."', workshops= '".$this->input->post('workshops')."',
							awards_excellence = '".$this->input->post('awards_excellence')."'";
			}  
			$this->db->query($insertSql);
			//print_r($_POST['cid']);
			//$company_name = $this->input->post('company_name');
			$cID = $_POST['cid'];
			for($i=0; $i<count($cID); $i++){
				
				$cid=$cID[$i];
				$resMessages=$this->db->query("SELECT * FROM `resume_comp` WHERE login_id='".$user_id."' AND cid='".$cid."'");
				$rowMessages = $resMessages->result_array();
				if(count($rowMessages)>0)
				{	
					$insCompSql ="UPDATE `resume_comp` SET comp_name = '".$_POST['company_name'][$i]."', designation = '".$_POST['designation'][$i]."', year_exp= '".$_POST['year_exp'][$i]."' WHERE login_id='".$user_id."' AND cid='".$_POST['cid'][$i]."'";
					$this->db->query($insCompSql);
					$cid=$_POST['cid'][$i];
				}else{
					$insCompSql ="INSERT INTO `resume_comp` SET login_id='".$user_id."', comp_name = '".$_POST['company_name'][$i]."', 
								designation = '".$_POST['designation'][$i]."', year_exp= '".$_POST['year_exp'][$i]."'";
					$this->db->query($insCompSql);
					$cid = $this->db->insert_id();
				}
				
				$pro_name = 'pro_name_'.$i;                 
				$pro_desc = 'pro_desc_'.$i;
				$product_usage = 'product_usage_'.$i;
				$duration = 'duration_'.$i;
				$role = 'role_'.$i;
				$team_size = 'team_size_'.$i;
				$pid = 'pid_'.$i;
				
				//print_r($_POST['cid']);
				$this->db->query("DELETE FROM `resume_comp_project` WHERE cid='".$cid."'");
				for($j=0;$j<count($_POST[$pro_desc]);$j++){
					
					$resMessagess=$this->db->query("SELECT * FROM `resume_comp_project` WHERE pid='".$_POST[$pid][$j]."' AND cid='".$cid."'");
					$rowMessagess = $resMessagess->result_array();
					if(count($rowMessagess)>0)
					{
						$insCompProSql ="UPDATE `resume_comp_project` SET pro_name = '".$_POST[$pro_name][$j]."', pro_desc = '".$_POST[$pro_desc][$j]."',  product_usage = '".$_POST[$product_usage][$j]."', duration= '".$_POST[$duration][$j]."', role = '".$_POST[$role][$j]."', team_size= '".$_POST[$team_size][$j]."' WHERE pid='".$_POST[$pid][$j]."' AND cid='".$cid."'";
					}else{
						$insCompProSql ="INSERT INTO `resume_comp_project` SET cid='".$cid."', pro_name = '".$_POST[$pro_name][$j]."', pro_desc = '".$_POST[$pro_desc][$j]."', product_usage = '".$_POST[$product_usage][$j]."', duration= '".$_POST[$duration][$j]."', role = '".$_POST[$role][$j]."', team_size= '".$_POST[$team_size][$j]."'";
					}
					 //echo $insCompProSql;
					$this->db->query($insCompProSql);
				}                
			} 
			
			$this->db->query("DELETE FROM `resume_lang` WHERE login_id='".$user_id."'"); 
			//print_r($_POST['speak']);exit;
			for($k=0;$k<count($_POST['languages']);$k++){
				$resMessagesss=$this->db->query("SELECT * FROM `resume_lang` WHERE login_id='".$user_id."' AND lid='".$_POST['lid'][$k]."'");
				$rowMessagesss = $resMessagesss->result_array();
				if(count($rowMessagesss)>0)
				{ 
					$insLangSql ="UPDATE `resume_lang` SET lname = '".$_POST['languages'][$k]."', lread = '".$_POST['read'][$k]."',
						  lwrite= '".$_POST['write'][$k]."', lspeak= '".$_POST['speak'][$k]."' WHERE login_id='".$user_id."' AND lid = '".$_POST['lid'][$k]."'";
				}else{
					$insLangSql ="INSERT INTO `resume_lang` SET login_id='".$user_id."', lname = '".$_POST['languages'][$k]."', 
						 lread = '".$_POST['read'][$k]."', lwrite= '".$_POST['write'][$k]."', lspeak= '".$_POST['speak'][$k]."'";
				}
				$this->db->query($insLangSql);
			}  //exit;
		
		redirect('hr_help_desk/resume_form');
	}
	
	
	public function apply_resignation()
	{
		$this->mViewData['pageTitle']    = 'Apply Resignation';
        // create the mViewData object
		$this->mViewData['separation'] = $this->Hr_model->get_separation_master();
		$messageForm ="";
		$messageFormErr ="";
		if($this->input->post('btnAddMessage') == "APPLY")
		{  
			$resMessage=$this->db->query("SELECT * FROM `resignation` WHERE login_id='".$this->session->userdata('user_id')."' AND ((emp_status = '0' AND  rm_status='0'))");
			$row = $resMessage->result_array(); 
			/* if($row > 0)
			{	
			   $updateSql = $this->db->query("UPDATE `resignation` SET subject = '".$this->input->post('subject')."',lwd = '".date('mm-dd-yyyy',strtotime($this->input->post('date')))."',separation= '".$this->input->post('selReaSep')."',message = '".$this->input->post('txtmessage')."',created_date=now() WHERE login_id='".$this->session->userdata('user_id')."'");
            //@mysql_query($updateSql);
			} */
			
			if(count($row) > 0){
				$messageForm ='<div class="col-md-12"><div class="alert alert-danger" role="alert"> Already Applied, please check you previous status.</div></div>';
			}
			else
			{
				//$messages= str_replace(array("'", "\"", "&quot;"), "'", htmlspecialchars($this->input->post('txtmessage') ) );
				$login_id = $this->session->userdata('user_id');
				$subject = $this->input->post('subject');
				$lwd = date('Y-m-d',strtotime($this->input->post('date')));
				$separation = $this->input->post('selReaSep');
				$message = $this->input->post('txtmessage');
				$insertSql = $this->Hr_model->insert_resignation_letter($login_id, $subject, $lwd, $separation, $message);
				//$insertSql =$this->db->query("INSERT INTO `resignation` SET login_id='".$this->session->userdata('user_id')."', subject = '".$this->input->post('subject')."',lwd = '".date('Y-m-d',strtotime($this->input->post('date')))."',separation= '".$this->input->post('selReaSep')."',message = '".$messages."', created_date=now()"); 
				$messageForm ='<div class="col-md-12"><div class="alert alert-success" role="alert"> Applied Successfully</div></div>';
			}
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."')";
			//exit;
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			//var_dump($repInfo);
			
			$empSql = "SELECT full_name,email FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
			//exit;
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			//var_dump($empInfo);exit;
		
        
			$message =$messageEmp ='';
			$site_base_url=base_url('hr_help_desk/resignation_approve_reject');
			$site_base_url_emp=base_url('hr_help_desk/my_resignation_application');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
                <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
            </div>';
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
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
                 <p>Dear {$repInfo[0]["full_name"]},</p>                                 
                 <p>{$empInfo[0]["full_name"]} has applied Resignation Letter. </p>  
                 <p>{$this->input->post('txtmessage')}</p>   
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
                 $messageEmp=<<<EOD
        <!DOCTYPE HTML>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
        </head>
        <body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
            <div style="width:900px; margin: 0 auto; background: #fff;">
             <div style="width:650px; float: left; min-height: 190px;">
                 <div style="padding: 7px 7px 14px 10px;">
                 <p>Dear {$empInfo[0]["full_name"]},</p>                                 
                 <p>Your Resignation Letter has been submitted successfully. </p>  
                 <p>{$this->input->post('txtmessage')}</p>   
                 <p><a href="{$site_base_url_emp}" style="text-decoration:none">Click here to view</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
                                 
			$to =$repInfo[0]['email'];
			$toEmp =$empInfo[0]['email'];
			$tohr ="hr@polosoftech.com";
			
			                     
			$subject = $this->input->post('subject');      
			//echo $message;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Notice & Announcements POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers); 
			mail($toEmp, $subject, $messageEmp, $headers); 
			$successMsg = TRUE; 
		} 
		$this->mViewData['messageForm']    = $messageForm;
		$this->render('hr_help_desk/apply_resignation_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/js/datepicker', $this->mViewData); 
	}
	public function my_resignation_application()
	{
		$this->mViewData['pageTitle']    = 'My Resignation Application';
		/* $this->mViewData['hr'] = $this->Hr_model->hr(); 
		$this->mViewData['phead'] = $this->Hr_model->production_head();
		$this->mViewData['my_resignation'] = $this->Hr_model->get_my_resignation_application(); */
		//Template view
		$this->render('hr_help_desk/my_resignation_application_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/my_resignation_application_script');
	}
	public function update_regination_emp_status()
	{  
		$rid = $this->input->post('rid');
		$result = $this->Hr_model->update_regination_emp_status($rid); 
	}
	public function get_my_resignation_application()
	{  
		$result = $this->Hr_model->get_my_resignation_application(); 
		echo json_encode($result); 
	}
	public function resignation_a_r()
	{  
		$result = $this->Hr_model->update_emp_action(); 
		echo json_encode($result); 
	} 
	public function resignation_approve_reject()
	{
		$this->mViewData['pageTitle']    = 'Resignation Approve or reject';
		//Template view
		$this->render('hr_help_desk/resignation_approve_reject_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/view_my_resignation_application_script');		
	}
	public function get_my_resignation_application_details()
	{  
		$result = $this->Hr_model->get_my_resignation_application_details(); 
		echo json_encode($result); 
	}
	public function get_my_resignation_application_details_search()
	{ 
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_my_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	public function get_rm_resignation_application_details()
	{  
		$result = $this->Hr_model->get_rm_resignation_application_details(); 
		echo json_encode($result); 
	}
	public function get_rm_resignation_application_details_search()
	{ 
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_rm_resignation_application_details_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	
    public function update_resignation_application_approved_rm()
    {
		$rid = $this->input->post('rid');
		$message = $this->input->post('message');
        $result = $this->Hr_model->update_resignation_application_approved_rm($rid, $message);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `resignation` WHERE rid = '".$rid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Resignation Request For Approved - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		$site_base_url=base_url('hr_help_desk/resignation_approve_reject');
		
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
			 <p>Resignation of {$empInfo[0]['full_name']} has approved by you. </p>  
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
			 <p>Your Resignation has approved by {$repInfo[0]['full_name']}. </p>  
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
		$site_base_urlhr=base_url('en/hr/resignation_approve_reject');
		$messagehr=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear Madam,</p>                                 
			 <p>Resignation of {$empInfo[0]['full_name']} has approved by {$repInfo[0]['full_name']}. </p>  
			 <p><a href="{$site_base_urlhr}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;

			$to =$repInfo[0]['email'];
			$toemp =$empInfo[0]['email'];
			$tohr ="hr@polosoftech.com";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($tohr, $subject, $messagehr, $headers); 
			mail($toemp, $subject, $messageemp, $headers); 
			mail($to, $subject, $message, $headers);
    }
	
	
	
    public function update_resignation_application_rejected_rm()
    {
		$rid = $this->input->post('rid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Hr_model->update_resignation_application_rejected_rm($rid, $reject_reason);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `resignation` WHERE rid = '".$rid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Resignation Request For Rejected - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		$site_base_url=base_url('hr_help_desk/resignation_approve_reject');
		
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
EOD;
		
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
			 <p>Your Resignation has rejected by {$repInfo[0]['full_name']}. </p>  
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

			$to =$repInfo[0]['email'];
			$toemp =$empInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($toemp, $subject, $messageemp, $headers); 
			mail($to, $subject, $message, $headers);
    }
	
	
	public function apply_loan_advance()
	{
		$this->mViewData['pageTitle']    = 'Apply loan Advance'; 
       
		$successMsg = FALSE;  
		$message = "";
		$message_scs = "";
		if($this->input->post('btnAddMessage') == "APPLY")
		{   
			
			$txtapplyfor = $this->input->post('txtapplyfor');
			$txtamountapplied = $this->input->post('txtamountapplied');
			$txtadvanceamount = $this->input->post('txtadvanceamount');
			$txtadvanceinstalment = $this->input->post('txtadvanceinstalment');
			$txtmessage = $this->input->post('txtmessage'); 
			$txtloanamount = $this->input->post('txtloanamount'); 
			$txtloaninstalment = $this->input->post('txtloaninstalment'); 
			if($this->input->post('txtapplyfor') == 'advance')
			{ 
				$resLoan = $this->Hr_model->check_advance_eligibility_latest(); 
			}  
			else
			{
				$resLoan = $this->Hr_model->check_loan_eligibility(); 
			} 
			
			
			if(count($resLoan) <= 0)
			{                  
				if($this->input->post('txtapplyfor') == 'advance')
				{ 
					$insertSql = $this->Hr_model->insert_loan_advance_apply($txtapplyfor,$txtamountapplied,$txtadvanceamount,$txtadvanceinstalment,$txtmessage);
				}  
				else
				{
					$insertSql = $this->Hr_model->insert_loan_advance_apply($txtapplyfor,$txtamountapplied,$txtloanamount,$txtloaninstalment,$txtmessage);
				}  
				
				$repSql = $this->db->query("SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."')"); 
				$repInfo = $repSql->row();
				
				$empSql = $this->db->query("SELECT full_name FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'");
				//exit;
				//$empRes = mysql_query($empSql);
				$empInfo = $empSql->row();
				
				$message_scs='Applied successfully';
				$site_base_url=base_url();
				//send mail pending....
				
				redirect('/hr_help_desk/my_loan_advance_application');
				
			}
			else{
				if($this->input->post('txtapplyfor') == 'advance')
				{ 
					$message='Advance already applied, please check previous advance status.';
				}  
				else
				{
					$message='Loan already applied, please check previous loan status.';
				} 
				
			}
		}
		$this->mViewData['apply_msg']=$message;
		$this->mViewData['apply_msg_scs']=$message_scs;
		$empSql = $this->db->query("SELECT join_date FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'"); 
		$empInfo = $empSql->row();
		//var_dump($empInfo);exit;
		$join_date = $empInfo->join_date;
		$this->mViewData['noofyears']=0;

		$this->mViewData['cur_date'] = date("Y-m-d");//current date

		$this->mViewData['diff'] = strtotime($this->mViewData['cur_date']) - strtotime($join_date);
		$this->mViewData['months'] = floor(floatval($this->mViewData['diff']) / (60 * 60 * 24 * 365 / 12));

		$this->mViewData['noofyears'] = $this->mViewData['months']/12;

		$this->mViewData['noofinstalment']='';
		$month  = date('m');
		if($month >= 4)
		{     
			$this->mViewData['noofinstalment']=12-$month+3;    
		}
		else
		{       
			$this->mViewData['noofinstalment']=(3-$month)+1;    
		} 

		$month=date("m");
		$year=date("Y");
		$totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year); 

		$this->mViewData['endDate'] = $year.'-'.$month.'-'.$totalDay;
		$this->mViewData['startDate'] = $year.'-'.$month.'-01';
		$this->mViewData['midDate'] =  $year.'-'.$month.'-15';
		//Template view 
		$this->render('hr_help_desk/apply_loan_advance_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/js/apply_loan_advance');
	}
	public function my_loan_advance_application()
	{
		$this->mViewData['pageTitle']    = 'My loan advance application';
		//Template view
		$this->render('hr_help_desk/my_loan_advance_application_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/my_loan_advance_application_script');
	}
	public function get_my_loan_advance_application()
	{
		$result = $this->Hr_model->get_my_loan_advance_application(); 
		echo json_encode($result);
	}
	public function loan_advance_approve_reject()
	{
		$this->mViewData['pageTitle']    = 'Loan advance approve or reject';
		//Template view
		$this->render('hr_help_desk/loan_advance_approve_reject_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/loan_advance_approve_reject_script'); 
	}
	public function get_all_loan_advance_approve_reject()
	{ 
		$result = $this->Hr_model->get_all_loan_advance_approve_reject(); 
		echo json_encode($result);
	}
	public function get_all_loan_advance_approve_reject_search()
	{ 
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->get_all_loan_advance_approve_reject_search($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result);
	}
	
	
    public function update_loan_advance_approved_rm()
    {
		$lid = $this->input->post('lid');
        $result = $this->Hr_model->update_loan_advance_approved_rm($lid);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `loan_advance_apply` WHERE lid = '".$lid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."' ";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user`  WHERE login_id = '".$empInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$depSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = (SELECT login_id FROM `department`  WHERE dept_id = '".$empInfo[0]['department']."')";
		$depRes = $this->db->query($depSql);
		$depInfo = $depRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Loan/Advance Request For Approved - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		$site_base_urlhr=base_url('hr_help_desk/my_loan_advance_application');
		$messagedept=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear {$depInfo[0]['full_name']},</p>                                 
			 <p>Loan/Advance of {$empInfo[0]['full_name']} has approved by {$repInfo[0]['full_name']}. </p>  
			 <p><a href="{$site_base_urlhr}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;
			$todept =$depInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($todept, $subject, $messagedept, $headers);
    }
	
    public function update_loan_advance_rejected_rm()
    {
		$lid = $this->input->post('lid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Hr_model->update_loan_advance_rejected_rm($lid, $reject_reason);
        echo json_encode($result);
		
		
    }
	
	
    public function update_loan_advance_approved_dh()
    {
		$lid = $this->input->post('lid');
        $result = $this->Hr_model->update_loan_advance_approved_dh($lid);
        echo json_encode($result);
		
		$rSql = "SELECT login_id FROM `loan_advance_apply` WHERE lid = '".$lid."'";
		$rRes = $this->db->query($rSql);
		$rInfo = $rRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$rInfo[0]['login_id']."' ";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user`  WHERE login_id = '".$empInfo[0]['login_id']."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$depSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = (SELECT login_id FROM `department`  WHERE dept_id = '".$empInfo[0]['department']."')";
		$depRes = $this->db->query($depSql);
		$depInfo = $depRes->result_array();
		
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		
		$subject = 'Loan/Advance Request For Approved - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
		$site_base_urlhr=base_url('en/hr/loan_advance_approve_reject');
		$messagedept=<<<EOD
	<!DOCTYPE HTML>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
	</head>
	<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
		<div style="width:900px; margin: 0 auto; background: #fff;">
		 <div style="width:650px; float: left; min-height: 190px;">
			 <div style="padding: 7px 7px 14px 10px;">
			 <p>Dear Madam,</p>                                 
			 <p>Loan/Advance of {$empInfo[0]['full_name']} has approved by {$depInfo[0]['full_name']}. </p>  
			 <p><a href="{$site_base_urlhr}" style="text-decoration:none">Click here to check status</a><br /><br /></p>
			 <p> In case of any Query, Please contact to HR Department.</p>                                 
			 <p>{$footer}</p>
			 </div> 
		  </div> 
		</div>  
		</div>
	</body>
	</html>
EOD;
			$todept ="hr@polosoftech.com";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($todept, $subject, $messagedept, $headers);
    }
	
    public function update_loan_advance_rejected_dh()
    {
		$lid = $this->input->post('lid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Hr_model->update_loan_advance_rejected_dh($lid, $reject_reason);
        echo json_encode($result);
    }
	
	
	public function goal_approve_reject()
	{
		$this->mViewData['pageTitle']    = 'Goal Approve Reject ';
		//Template view
		$this->render('hr_help_desk/goal_approve_reject_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/goal_approve_reject_script');
	}
	public function get_goal_approve_reject()
	{ 
		$result = $this->Hr_model->get_goal_approve_reject(); 
		echo json_encode($result);
	}
	public function update_goal_sheet_approved_rm()
	{ 
		$login_id = $this->input->post('login_id');
		$result = $this->Hr_model->update_goal_sheet_approved_rm($login_id); 
		echo json_encode($result);
	}
	
	public function probation_assessment_form()
	{
		$this->mViewData['pageTitle']    = 'probation assessment form';
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		$loginID = $this->session->userdata('user_id');
		
			
		
		if (isset($_GET['employee_id'])){
			$employee_id=$_GET['employee_id'];
			$this->mViewData['paRow'] = $this->Hr_model->probation_assessment($employee_id); 
		}
		else{
			$employee_id="";
			$this->mViewData['paRow'] =  array();
		}
		$this->mViewData['employee_id'] = $employee_id;

		if($employee_id !=""){
			$empSql = "SELECT full_name,email,loginhandle,join_date FROM `internal_user` WHERE login_id = '".$employee_id."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			$this->mViewData['doj'] = date("Y-m-d", strtotime($empInfo[0]['join_date']));
			$this->mViewData['fourweek'] = date("Y-m-d", strtotime("+1 month", strtotime($empInfo[0]['join_date'])));
			$this->mViewData['eightweek'] = date("Y-m-d", strtotime("+2 month", strtotime($empInfo[0]['join_date'])));
			$this->mViewData['twelveweek'] = date("Y-m-d", strtotime("+3 month", strtotime($empInfo[0]['join_date'])));
		}
		else{
			
			$this->mViewData['doj'] = '';
			$this->mViewData['fourweek'] = '';
			$this->mViewData['eightweek'] = '';
			$this->mViewData['twelveweek'] = '';
		}
		
		if($this->input->post('btnAddMessage') == "APPLY" && $loginID!='')
		{
			$probation_type = $this->input->post('probation_type');
			$fourweek = $this->input->post('fourweek');
			$eightweek = $this->input->post('eightweek');
			$twelveweek = $this->input->post('twelveweek');
			$quantity_work_4thweek = $this->input->post('quantity_work_4thweek');
			$quantity_work_8thweek = $this->input->post('quantity_work_8thweek');
			$quantity_work_12thweek = $this->input->post('quantity_work_12thweek');
			$problem_solving_4thweek = $this->input->post('problem_solving_4thweek');
			$problem_solving_8thweek = $this->input->post('problem_solving_8thweek');
			$problem_solving_12thweek = $this->input->post('problem_solving_12thweek');
			$motivation_employees_4thweek = $this->input->post('motivation_employees_4thweek');
			$motivation_employees_8thweek = $this->input->post('motivation_employees_8thweek');
			$motivation_employees_12thweek = $this->input->post('motivation_employees_12thweek');
			$responsibility_4thweek = $this->input->post('responsibility_4thweek');
			$responsibility_8thweek = $this->input->post('responsibility_8thweek');
			$responsibility_12thweek = $this->input->post('responsibility_12thweek');
			$quality_work_4thweek = $this->input->post('quality_work_4thweek');
			$quality_work_8thweek = $this->input->post('quality_work_8thweek');
			$quality_work_12thweek = $this->input->post('quality_work_12thweek');
			$knowledge_job_4thweek = $this->input->post('knowledge_job_4thweek');
			$knowledge_job_8thweek = $this->input->post('knowledge_job_8thweek');
			$knowledge_job_12thweek = $this->input->post('knowledge_job_12thweek');
			$relations_supervisor_4thweek = $this->input->post('relations_supervisor_4thweek');
			$relations_supervisor_8thweek = $this->input->post('relations_supervisor_8thweek');
			$relations_supervisor_12thweek = $this->input->post('relations_supervisor_12thweek');
			$cooperation_others_4thweek = $this->input->post('cooperation_others_4thweek');
			$cooperation_others_8thweek = $this->input->post('cooperation_others_8thweek');
			$cooperation_others_12thweek = $this->input->post('cooperation_others_12thweek');
			$attendance_reliability_4thweek = $this->input->post('attendance_reliability_4thweek');
			$attendance_reliability_8thweek = $this->input->post('attendance_reliability_8thweek');
			$attendance_reliability_12thweek = $this->input->post('attendance_reliability_12thweek');
			$initiative_creativity_4thweek = $this->input->post('initiative_creativity_4thweek');
			$initiative_creativity_8thweek = $this->input->post('initiative_creativity_8thweek');
			$initiative_creativity_12thweek = $this->input->post('initiative_creativity_12thweek');
			$capacity_develop_4thweek = $this->input->post('capacity_develop_4thweek');
			$capacity_develop_8thweek = $this->input->post('capacity_develop_8thweek');
			$capacity_develop_12thweek = $this->input->post('capacity_develop_12thweek');
			$employee_performance = $this->input->post('employee_performance');
			$your_expectations = $this->input->post('your_expectations');
			$need_improvement = $this->input->post('need_improvement');
			$additional_training = $this->input->post('additional_training');
			$employee_reaction = $this->input->post('employee_reaction');
			$set_employee_goals = $this->input->post('set_employee_goals');
			$expectations = $this->input->post('expectations');
			$improvement = $this->input->post('improvement');
			$training = $this->input->post('training');
			$reaction = $this->input->post('reaction');
			$satisfactorily = $this->input->post('satisfactorily');
			$employee_satisfactorily = $this->input->post('employee_satisfactorily');
			$additional_comments = $this->input->post('additional_comments');
			$employee_id = $this->input->post('employee_id');
			$res = $this->Hr_model->probation_assessment($employee_id);
			$resMessage=count($res);
			if($resMessage>0)
			{	
				$this->Hr_model->update_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,$cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id); 
			}
			else
			{
				$this->Hr_model->insert_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,$cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id); 
			}
			
			//Sending Email
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'PROBATION ASSESSMENT FORM - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('hr_help_desk/probation_assessment');
			$site_base_urlhr=base_url('hr/probation_assessment_all');
			
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
                 <p>{$empInfo[0]['full_name']} has applied for Probation Assessment. </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to View </a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
				
			$messagehr=<<<EOD
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
                 <p>{$empInfo[0]['full_name']} has applied for Probation Assessment. </p>  
                 <p><a href="{$site_base_urlhr}" style="text-decoration:none">Click here to View </a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				$to =$repInfo[0]['email'];
				$tohr = 'hr@polosoftech.com';
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				//echo $message; exit();
				mail($to, $subject, $message, $headers);
				mail($tohr, $subject, $messagehr, $headers);
				redirect('/hr_help_desk/probation_assessment');
		}
		if($this->input->post('btnSaveMessage') == "SAVE" && $loginID!='')
		{
			$probation_type = $this->input->post('probation_type');
			$fourweek = $this->input->post('fourweek');
			$eightweek = $this->input->post('eightweek');
			$twelveweek = $this->input->post('twelveweek');
			$quantity_work_4thweek = $this->input->post('quantity_work_4thweek');
			$quantity_work_8thweek = $this->input->post('quantity_work_8thweek');
			$quantity_work_12thweek = $this->input->post('quantity_work_12thweek');
			$problem_solving_4thweek = $this->input->post('problem_solving_4thweek');
			$problem_solving_8thweek = $this->input->post('problem_solving_8thweek');
			$problem_solving_12thweek = $this->input->post('problem_solving_12thweek');
			$motivation_employees_4thweek = $this->input->post('motivation_employees_4thweek');
			$motivation_employees_8thweek = $this->input->post('motivation_employees_8thweek');
			$motivation_employees_12thweek = $this->input->post('motivation_employees_12thweek');
			$responsibility_4thweek = $this->input->post('responsibility_4thweek');
			$responsibility_8thweek = $this->input->post('responsibility_8thweek');
			$responsibility_12thweek = $this->input->post('responsibility_12thweek');
			$quality_work_4thweek = $this->input->post('quality_work_4thweek');
			$quality_work_8thweek = $this->input->post('quality_work_8thweek');
			$quality_work_12thweek = $this->input->post('quality_work_12thweek');
			$knowledge_job_4thweek = $this->input->post('knowledge_job_4thweek');
			$knowledge_job_8thweek = $this->input->post('knowledge_job_8thweek');
			$knowledge_job_12thweek = $this->input->post('knowledge_job_12thweek');
			$relations_supervisor_4thweek = $this->input->post('relations_supervisor_4thweek');
			$relations_supervisor_8thweek = $this->input->post('relations_supervisor_8thweek');
			$relations_supervisor_12thweek = $this->input->post('relations_supervisor_12thweek');
			$cooperation_others_4thweek = $this->input->post('cooperation_others_4thweek');
			$cooperation_others_8thweek = $this->input->post('cooperation_others_8thweek');
			$cooperation_others_12thweek = $this->input->post('cooperation_others_12thweek');
			$attendance_reliability_4thweek = $this->input->post('attendance_reliability_4thweek');
			$attendance_reliability_8thweek = $this->input->post('attendance_reliability_8thweek');
			$attendance_reliability_12thweek = $this->input->post('attendance_reliability_12thweek');
			$initiative_creativity_4thweek = $this->input->post('initiative_creativity_4thweek');
			$initiative_creativity_8thweek = $this->input->post('initiative_creativity_8thweek');
			$initiative_creativity_12thweek = $this->input->post('initiative_creativity_12thweek');
			$capacity_develop_4thweek = $this->input->post('capacity_develop_4thweek');
			$capacity_develop_8thweek = $this->input->post('capacity_develop_8thweek');
			$capacity_develop_12thweek = $this->input->post('capacity_develop_12thweek');
			$employee_performance = $this->input->post('employee_performance');
			$your_expectations = $this->input->post('your_expectations');
			$need_improvement = $this->input->post('need_improvement');
			$additional_training = $this->input->post('additional_training');
			$employee_reaction = $this->input->post('employee_reaction');
			$set_employee_goals = $this->input->post('set_employee_goals');
			$expectations = $this->input->post('expectations');
			$improvement = $this->input->post('improvement');
			$training = $this->input->post('training');
			$reaction = $this->input->post('reaction');
			$satisfactorily = $this->input->post('satisfactorily');
			$employee_satisfactorily = $this->input->post('employee_satisfactorily');
			$additional_comments = $this->input->post('additional_comments');
			$employee_id = $this->input->post('employee_id');
			$res = $this->Hr_model->probation_assessment($employee_id);
			$resMessage=count($res);
			if($resMessage>0)
			{	
				$this->Hr_model->update_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,$cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id);
			}
			else
			{
				$this->Hr_model->insert_probation_assessment($probation_type,$fourweek,$eightweek,$twelveweek,$quantity_work_4thweek,$quantity_work_8thweek,$quantity_work_12thweek,$problem_solving_4thweek,$problem_solving_8thweek,$problem_solving_12thweek,$motivation_employees_4thweek,$motivation_employees_8thweek,$motivation_employees_12thweek,$responsibility_4thweek,$responsibility_8thweek,$responsibility_12thweek,$quality_work_4thweek,$quality_work_8thweek,$quality_work_12thweek,$knowledge_job_4thweek,$knowledge_job_8thweek,$knowledge_job_12thweek,$relations_supervisor_4thweek,$relations_supervisor_8thweek,$relations_supervisor_12thweek,$cooperation_others_4thweek,$cooperation_others_8thweek,$cooperation_others_12thweek,$attendance_reliability_4thweek,$attendance_reliability_8thweek,$attendance_reliability_12thweek,$initiative_creativity_4thweek,$initiative_creativity_8thweek,$initiative_creativity_12thweek,$capacity_develop_4thweek,$capacity_develop_8thweek,$capacity_develop_12thweek,$employee_performance,$your_expectations,$need_improvement,$additional_training,$employee_reaction,$set_employee_goals,$expectations,$improvement,$training,$reaction,$satisfactorily,$employee_satisfactorily,$additional_comments,$employee_id);
			} 
			redirect('/hr_help_desk/probation_assessment');
		}
		$this->mViewData['pdetails'] = $this->Hr_model->probation_employee_details(); 
		//Template view
		$this->render('hr_help_desk/probation_assessment_form_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/js/datepicker');
		$this->load->view('script/hr_help_desk/js/probation_assessment_form');
	}
	
	
	/************  Probation Assessment   ***********************/
	public function probation_assessment()
	{
		$this->mViewData['pageTitle']    = 'Probation Assessment';
		$this->mViewData['probation_details'] = $this->Hr_model->probation_assessment_details();
		$id = $this->input->get('id');
		$this->mViewData['rowPA'] = $this->Hr_model->probation_details($id);  
		
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		//Template view
		$this->render('hr_help_desk/probation_assessment_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/js/probation_assessment_js');
	}
	
	public function probation_assessment_filter()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$result = $this->Hr_model->probation_assessment_details_filter($searchDepartment , $searchName, $searchDesignation , $searchEmpCode); 
		echo json_encode($result); 
	}
	public function probation_assessment_update_dh_status()
	{
		$mid = $this->input->post('mid');
		$login_id = $this->input->post('login_id');
		$dh_status = $this->input->post('dh_status');
		$rejQry = "UPDATE `probation_assessment` SET `dh_status` = '".$dh_status."' WHERE `mid` = '".$mid."'";
		$rejRes = $this->db->query($rejQry);
		echo 1;
	}
	public function probation_assessment_print()
	{
		$this->mViewData['pageTitle']    = 'Probation Assessment';
		$loginID = $_GET['id'];
		$mid = $_GET['mid'];

		$sqlPA="SELECT p.*,i.full_name,i.loginhandle,r.full_name as rmName,r.loginhandle as rmEmpid FROM `probation_assessment` p LEFT JOIN `internal_user` i ON i.login_id=p.employee_id LEFT JOIN `internal_user` r ON r.login_id=p.login_id  WHERE mid = '".$mid."'";
		$revRes = $this->db->query($sqlPA);
		$this->mViewData['rowPA'] = $revRes->result_array();

		//Template view
		$this->load->view('hr_help_desk/probation_assessment_detail', $this->mViewData);
	}
	/************ END/  Probation Assessment   ***********************/
	
	
	/************ My Probation Assessment   ***********************/
	public function my_probation_assessment()
	{
		$this->mViewData['pageTitle']    = 'My probation assessment';
		$this->mViewData['mypdetails'] = $this->Hr_model->my_probation_details(); 
		
		//Template view
		$this->render('hr_help_desk/my_probation_assessment_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/js/my_probation_assessment_js'); 
	}
	
	
	public function midyear_review_form()
	{
		$this->mViewData['pageTitle']    = 'Mid year form';
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') =='' && $this->session->userdata('user_id') !='')
		{
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));

			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			
			//$comment = $this->input->post('comment'); 
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			
			if(count($row)>0)
			{
				$updateSql="UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."' WHERE login_id='".$this->session->user_id."' $condn"; 
				$this->db->query($updateSql);             
			}
			else
			{
				$insertSql ="INSERT INTO `midyear_review` SET login_id='".$this->session->user_id."', accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."', exceeding_expectation = '".$exceeding_expectation."',
					improvement = '".$improvement."', discussion= '".$discussion."', summary_expectation = '".$summary_expectation."',
					employee_development = '".$employee_development."', apply_date=now()";
				$this->db->query($insertSql); 
			}
			
			if(isset($_POST['mid'])){
				for($i=0;$i<count($_POST['mid']);$i++){      
					//print_r($_POST['mid'][$i]);
					$resMess=$this->db->query("SELECT * FROM `goal_sheet` WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'");
					$res = $resMess->result_array();
					if(count($res)>0){   
						$comment = addslashes($this->input->post('comment')[$i]);
					   $updGoalSql ="UPDATE `goal_sheet` SET comment = '".$comment."' WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'";
					   $this->db->query($updGoalSql); 
					 }
				}
			}
			
		}  
		 
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') !='' && $this->session->userdata('user_id') !='')
		{ 
			
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn ");
			$row = $resMessage->result_array();
			if(count($row)>0)
			{   
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$exceeding_expectation."',
									improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',
									employee_development = '".$employee_development."' WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
				if(isset($_POST['mid'])){
				for($i=0; $i<count($_POST['mid']); $i++){ 
					$rating = addslashes($this->input->post('rating')[$i]);
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$rating."' WHERE login_id='".$loginID."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql); 
				}
				}
			}
		}
		
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{
			$pin="";
			$warray=array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
			for($k=0;$k<=7;$k++)
			{
				$p=rand(0,35);
				$pin=$pin.strtoupper($warray[$p]);
			}
			$pin=trim($pin);
			
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			
				   
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			
			if(count($row)>0)
			{ 
				$updateSql="UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
							 contributions = '".$contributions."', unplanned_events= '".$unplanned_events."', unique_pin= '".$pin."',apply_date=now() WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
			}
			else
			{
				$insertSql ="INSERT INTO `midyear_review` SET login_id='".$loginID."', accomplishments = '".$accomplishments."', 
							   contributions = '".$contributions."', unplanned_events= '".$unplanned_events."',exceeding_expectation = '".$exceeding_expectation."',
							   improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',
							   employee_development = '".$employee_development."', unique_pin= '".$pin."',apply_date=now()";

				$this->db->query($insertSql);
			} 	
			
			if(isset($_POST['mid'])){
			for($i=0;$i<count($_POST['mid']);$i++){      
				//print_r($_POST['mid'][$i]);
				$resMess=$this->db->query("SELECT * FROM `goal_sheet` WHERE login_id='".$this->session->user_id."' AND mid='".$_POST['mid'][$i]."'");
				$res = $resMess->result_array();
				if(count($res)>0){   
					$comment = addslashes($this->input->post('comment')[$i]);
				   $updGoalSql ="UPDATE `goal_sheet` SET comment = '".$comment."' WHERE login_id='".$this->session->user_id."' AND mid='".$_POST['mid'][$i]."'";
				   $this->db->query($updGoalSql); 
				}
			}
			}
				
			$repMgrSql = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'");
			$repMgrInfo =$repMgrSql->result_array();

			//Send Email on Apply
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Mid Year Review Form - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_urlRM=base_url('hr_help_desk/midyear_review');
			$site_base_url=base_url('hr_help_desk/my_midyear_review');
			$messageRM=<<<EOD
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
				 <p>Your team member {$empInfo[0]['full_name']} has been submitted his/her mid-year self appraisal form. </p> 
                 <p><a href="{$site_base_urlRM}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

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
                 <p>Dear {$empInfo[0]['full_name']},</p>                                 
                 <p>Your mid-year review self appraisal form has been submitted successfully.</p>
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

				$toRM = $repInfo[0]['email'];
				$to= $empInfo[0]['email'];
				
				$headersRM  = 'MIME-Version: 1.0' . "\r\n";
				$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headersRM .= "X-Priority: 1\r\n"; 
				$headersRM .= 'X-Mailer: PHP/' . phpversion();
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
				mail($toRM, $subject, $messageRM, $headersRM);

				redirect('hr_help_desk/my_midyear_review');
			
		}

		if($this->input->post('btnAddMessage') == "APPROVE" && $this->session->userdata('user_id') !='')
		{ 
	
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE unique_pin= '".$this->input->post('unique_pin')."' AND login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			if(count($row)>0)
			{ 	
		
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$this->input->post('exceeding_expectation')."',
									improvement = '".$this->input->post('improvement')."', discussion= '".$this->input->post('discussion')."',summary_expectation = '".$this->input->post('summary_expectation')."',
									employee_development = '".$this->input->post('employee_development')."', rm_status='1', approved_date=now() WHERE login_id='".$this->input->post('login_id')."' $condn";

				$this->db->query($updateSql); 
				
				if(isset($_POST['objective'])){
				for($i=0;$i<count($_POST['rating']);$i++)
				{
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$this->input->post('rating')[$i]."' WHERE login_id='".$this->input->post('login_id')."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql);
				}     
				}     

				$repMgrSql = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'");
				$repMgrInfo =$repMgrSql->result_array();
				
				$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Mid Year Review Form - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('hr_help_desk/my_midyear_review');
			
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
                 <p>Dear {$repInfo[0]['full_name']},</p>                                 
				 <p>Your mid-year review application has been approved. </p>
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

			$to = $repInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headers);	
			}
		}
		
		
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}
        $qryAppraisal = "SELECT i.*, om.*, dp.* FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id  WHERE i.login_id = '".$loginID."' AND emp_type='F' $condn"; 
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		
		$qrys = "SELECT i.* FROM `internal_user` i WHERE i.login_id = '".$loginID."' ";
		$ress = $this->db->query($qrys);
		$rows = $ress->result_array();
		$cyr = date('Y');
		$date1 = strtotime(date('Y-m-d'));
		if(count($rows)>0){
			//echo $rows[0]['join_date'];
			$date1 = strtotime($rows[0]['join_date']);
		}
		$date2 = strtotime(date('Y-m-d', strtotime($cyr.'-09-30')));
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		$this->mViewData['noOfMonths'] = $months;
		
		if(count($rowAppraisal)>0){
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y', strtotime($rowAppraisal[0]['apply_date']))+1)."'";
		}
		else{
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y')+1)."'";
		}
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->render('hr_help_desk/midyear_review_form_view', 'full_width', $this->mViewData); 
	}
	
	
	public function midyear_review_form_edit()
	{
		$this->mViewData['pageTitle']    = 'Mid year form';
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') =='' && $this->session->userdata('user_id') !='')
		{
				   
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));

			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			
			//$comment = $this->input->post('comment'); 
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			
			if(count($row)>0)
			{
				$updateSql="UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."' WHERE login_id='".$this->session->user_id."' $condn"; 
				$this->db->query($updateSql);             
			}
			else
			{
				$insertSql ="INSERT INTO `midyear_review` SET login_id='".$this->session->user_id."', accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."', exceeding_expectation = '".$exceeding_expectation."',
					improvement = '".$improvement."', discussion= '".$discussion."', summary_expectation = '".$summary_expectation."',
					employee_development = '".$employee_development."', apply_date=now()";
				$this->db->query($insertSql); 
			}
			
			if(isset($_POST['mid'])){
				for($i=0;$i<count($_POST['mid']);$i++){      
					//print_r($_POST['mid'][$i]);
					$resMess=$this->db->query("SELECT * FROM `goal_sheet` WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'");
					$res = $resMess->result_array();
					if(count($res)>0){   
						$comment = addslashes($this->input->post('comment')[$i]);
					   $updGoalSql ="UPDATE `goal_sheet` SET comment = '".$comment."' WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'";
					   $this->db->query($updGoalSql); 
					 }
				}
			}
			
		}  
		 
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') !='' && $this->session->userdata('user_id') !='')
		{ 
			
				   
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn ");
			$row = $resMessage->result_array();
			if(count($row)>0)
			{   
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$exceeding_expectation."',
									improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',
									employee_development = '".$employee_development."' WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
				if(isset($_POST['mid'])){
				for($i=0; $i<count($_POST['mid']); $i++){ 
					$rating = addslashes($this->input->post('rating')[$i]);
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$rating."' WHERE login_id='".$loginID."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql); 
				}
				}
			}
		}
		
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{
			$pin="";
			$warray=array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
			for($k=0;$k<=7;$k++)
			{
				$p=rand(0,35);
				$pin=$pin.strtoupper($warray[$p]);
			}
			$pin=trim($pin);
			
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();	   
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			if(count($row)>0)
			{ 	   
				$updateSql="UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
							 contributions = '".$contributions."', unplanned_events= '".$unplanned_events."', unique_pin= '".$pin."',apply_date=now() WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
			}
			else
			{
				$insertSql ="INSERT INTO `midyear_review` SET login_id='".$loginID."', accomplishments = '".$accomplishments."', 
							   contributions = '".$contributions."', unplanned_events= '".$unplanned_events."',exceeding_expectation = '".$exceeding_expectation."',
							   improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',
							   employee_development = '".$employee_development."', unique_pin= '".$pin."',apply_date=now()";

				$this->db->query($insertSql);
			} 	
			
			if(isset($_POST['mid'])){
			for($i=0;$i<count($_POST['mid']);$i++){      
				//print_r($_POST['mid'][$i]);
				$resMess=$this->db->query("SELECT * FROM `goal_sheet` WHERE login_id='".$this->session->user_id."' AND mid='".$_POST['mid'][$i]."'");
				$res = $resMess->result_array();
				if(count($res)>0){   
					$comment = addslashes($this->input->post('comment')[$i]);
				   $updGoalSql ="UPDATE `goal_sheet` SET comment = '".$comment."' WHERE login_id='".$this->session->user_id."' AND mid='".$_POST['mid'][$i]."'";
				   $this->db->query($updGoalSql); 
				}
			}
			}
				
			$repMgrSql = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'");
			$repMgrInfo =$repMgrSql->result_array();

			//Send Email on Apply
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Mid Year Review Form - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_urlRM=base_url('hr_help_desk/midyear_review');
			$site_base_url=base_url('hr_help_desk/my_midyear_review');
			$messageRM=<<<EOD
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
				 <p>Your team member {$empInfo[0]['full_name']} has been submitted his/her mid-year self appraisal form. </p> 
                 <p><a href="{$site_base_urlRM}" style="text-decoration:none">Click here to Approve / Reject</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

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
                 <p>Dear {$empInfo[0]['full_name']},</p>                                 
                 <p>Your mid-year review self appraisal form has been submitted successfully.</p>
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

				$toRM = $repInfo[0]['email'];
				$to= $empInfo[0]['email'];
				
				$headersRM  = 'MIME-Version: 1.0' . "\r\n";
				$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headersRM .= "X-Priority: 1\r\n"; 
				$headersRM .= 'X-Mailer: PHP/' . phpversion();
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
				mail($toRM, $subject, $messageRM, $headersRM);

				redirect('hr_help_desk/my_midyear_review');
			
		}

		if($this->input->post('btnAddMessage') == "APPROVE" && $this->session->userdata('user_id') !='')
		{ 
	
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE unique_pin= '".$this->input->post('unique_pin')."' AND login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			if(count($row)>0)
			{ 	
		
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$this->input->post('exceeding_expectation')."',
									improvement = '".$this->input->post('improvement')."', discussion= '".$this->input->post('discussion')."',summary_expectation = '".$this->input->post('summary_expectation')."',
									employee_development = '".$this->input->post('employee_development')."', rm_status='1', approved_date=now() WHERE login_id='".$this->input->post('login_id')."' $condn";

				$this->db->query($updateSql); 
				
				if(isset($_POST['objective'])){
				for($i=0;$i<count($_POST['rating']);$i++)
				{
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$this->input->post('rating')[$i]."' WHERE login_id='".$this->input->post('login_id')."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql);
				}     
				}     

				$repMgrSql = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'");
				$repMgrInfo =$repMgrSql->result_array();
				
				$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Mid Year Review Form - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('hr_help_desk/my_midyear_review');
			
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
                 <p>Dear {$repInfo[0]['full_name']},</p>                                 
				 <p>Your mid-year review application has been approved. </p>
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

			$to = $repInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headers);	
			}
		}
		
		
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}
        $qryAppraisal = "SELECT i.*, om.*, dp.* FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id  WHERE i.login_id = '".$loginID."' $condn";
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $resAppraisal->result_array();
		
		 $qrys = "SELECT i.* FROM `internal_user` i WHERE i.login_id = '".$loginID."' ";
		$ress = $this->db->query($qrys);
		$rows = $ress->result_array();
		$cyr = date('Y');
		$date1 = strtotime(date('Y-m-d'));
		if(count($rows)>0){
			//echo $rows[0]['join_date'];
			$date1 = strtotime($rows[0]['join_date']);
		}
		$date2 = strtotime(date('Y-m-d', strtotime($cyr.'-09-30')));
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		$this->mViewData['noOfMonths'] = $months;
		
		if(count($rowAppraisal)>0){
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y', strtotime($rowAppraisal[0]['apply_date']))+1)."'";
		}
		else{
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y')+1)."'";
		}
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->render('hr_help_desk/midyear_review_form_edit', 'full_width', $this->mViewData); 
	}
	
	
	
	public function midyear_review_form_rm()
	{
		$this->mViewData['pageTitle']    = 'Mid year form';
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') =='' && $this->session->userdata('user_id') !='')
		{
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));

			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			
			//$comment = $this->input->post('comment'); 
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			
			if(count($row)>0)
			{
				$updateSql="UPDATE `midyear_review` SET accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."' WHERE login_id='".$this->session->user_id."' $condn"; 
				$this->db->query($updateSql);             
			}
			else
			{
				$insertSql ="INSERT INTO `midyear_review` SET login_id='".$this->session->user_id."', accomplishments = '".$accomplishments."', 
					contributions = '".$contributions."', unplanned_events= '".$unplanned_events."', exceeding_expectation = '".$exceeding_expectation."',
					improvement = '".$improvement."', discussion= '".$discussion."', summary_expectation = '".$summary_expectation."',
					employee_development = '".$employee_development."', apply_date=now()";
				$this->db->query($insertSql); 
			}
			
			if(isset($_POST['mid'])){
				for($i=0;$i<count($_POST['mid']);$i++){      
					//print_r($_POST['mid'][$i]);
					$resMess=$this->db->query("SELECT * FROM `goal_sheet` WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'");
					$res = $resMess->result_array();
					if(count($res)>0){   
						$comment = addslashes($this->input->post('comment')[$i]);
					   $updGoalSql ="UPDATE `goal_sheet` SET comment = '".$comment."' WHERE login_id='".$this->session->user_id."' AND mid='".$this->input->post('mid')[$i]."'";
					   $this->db->query($updGoalSql); 
					 }
				}
			}
			
		}  
		 
		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') !='' && $this->session->userdata('user_id') !='')
		{ 
			
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}	
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn ");
			$row = $resMessage->result_array();
			if(count($row)>0)
			{   
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$exceeding_expectation."',
									improvement = '".$improvement."', discussion= '".$discussion."',summary_expectation = '".$summary_expectation."',
									employee_development = '".$employee_development."' WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
				if(isset($_POST['mid'])){
				for($i=0; $i<count($_POST['mid']); $i++){ 
					$rating = addslashes($this->input->post('rating')[$i]);
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$rating."' WHERE login_id='".$loginID."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql); 
				}
				}
			}
		}
		

		if($this->input->post('btnAddMessage') == "APPROVE" && $this->session->userdata('user_id') !='')
		{ 
	
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessage=$this->db->query("SELECT * FROM `midyear_review` WHERE login_id='".$loginID."' $condn");
			$row = $resMessage->result_array();
			
			//$accomplishments = $this->input->post('accomplishments');
			$accomplishments = addslashes($this->input->post('accomplishments'));
			//$contributions   = $this->input->post('contributions');
			$contributions = addslashes($this->input->post('contributions'));
			//$unplanned_events = $this->input->post('unplanned_events');
			$unplanned_events = addslashes($this->input->post('unplanned_events'));
			//$exceeding_expectation = $this->input->post('exceeding_expectation');
			$exceeding_expectation = addslashes($this->input->post('exceeding_expectation'));
			//$improvement = $this->input->post('improvement');
			$improvement = addslashes($this->input->post('improvement'));
			//$discussion = $this->input->post('discussion');
			$discussion = addslashes($this->input->post('discussion'));
			//$summary_expectation = $this->input->post('summary_expectation');
			$summary_expectation = addslashes($this->input->post('summary_expectation'));
			//$employee_development = $this->input->post('employee_development');
			$employee_development = addslashes($this->input->post('employee_development'));
			
			if(count($row)>0)
			{ 	
		
				$updateSql="UPDATE `midyear_review` SET exceeding_expectation = '".$exceeding_expectation."',
									improvement = '".$improvement."', 
									discussion= '".$discussion."',
									summary_expectation = '".$summary_expectation."',
									employee_development = '".$employee_development."', 
									rm_status='1', approved_date=now() WHERE login_id='".$loginID."' $condn";

				$this->db->query($updateSql); 
				
				/* if(isset($_POST['objective'])){
				for($i=0;$i<count($_POST['rating']);$i++)
				{
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$this->input->post('rating')[$i]."' WHERE login_id='".$this->input->post('login_id')."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql);
				}     
				}   */

				if(isset($_POST['mid'])){
				for($i=0; $i<count($_POST['mid']); $i++){ 
					$rating = addslashes($this->input->post('rating')[$i]);
					$insGoalSql ="UPDATE `goal_sheet` SET rating = '".$rating."' WHERE login_id='".$loginID."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql); 
				}
				}

				$repMgrSql = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'");
				$repMgrInfo =$repMgrSql->result_array();
				
				$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
				$repRes = $this->db->query($repSql);
				$repInfo = $repRes->result_array();
				
				$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
				$empRes = $this->db->query($empSql);
				$empInfo = $empRes->result_array();
				
				$logo_URL = base_url('assets/images/logo.gif');
				$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
				</div>';
					
				$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
				
				$subject = 'Mid Year Review Form - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
				$site_base_url=base_url('hr_help_desk/my_midyear_review');
			
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
                 <p>Dear {$repInfo[0]['full_name']},</p>                                 
				 <p>Your mid-year review application has been approved. </p>
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

				$to = $repInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $messageApp, $headers);	
				redirect('hr_help_desk/midyear_review');
			}
		}
		
		
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}
        $qryAppraisal = "SELECT i.*, om.*, dp.* FROM `internal_user` i LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id  WHERE i.login_id = '".$loginID."' $condn"; 
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		
		 $qrys = "SELECT i.* FROM `internal_user` i WHERE i.login_id = '".$loginID."' ";
		$ress = $this->db->query($qrys);
		$rows = $ress->result_array();
		$cyr = date('Y');
		$date1 = strtotime(date('Y-m-d'));
		if(count($rows)>0){
			//echo $rows[0]['join_date'];
			$date1 = strtotime($rows[0]['join_date']);
		}
		$date2 = strtotime(date('Y-m-d', strtotime($cyr.'-09-30')));
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		$this->mViewData['noOfMonths'] = $months;
		
		if(count($rowAppraisal)>0){
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y', strtotime($rowAppraisal[0]['apply_date']))+1)."'";
		}
		else{
			$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y')+1)."'";
		}
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->render('hr_help_desk/midyear_review_form_view_rm', 'full_width', $this->mViewData); 
	}
	
	
	public function midyear_review()
	{
		$this->mViewData['pageTitle']    = 'Mid year review';
		//Template view
		$this->render('hr_help_desk/midyear_review_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/midyear_review_script'); 
	}
	/*Start Ajax with angularjs function*/
	public function get_midyear_review()
	{
		$result = $this->Hr_model->get_midyear_review(); 
		echo json_encode($result); 
	}
	public function get_midyear_review_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_midyear_review_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	public function update_mid_year_review_approved_dh()
	{
		$mid = $this->input->post('mid');
		$result = $this->Hr_model->update_mid_year_review_approved_dh($mid); 
		//mail to employee
		
		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name 
		FROM `midyear_review` om 
		JOIN `internal_user` i ON om.login_id = i.login_id 
		LEFT JOIN `user_desg` d ON d.desg_id = i.designation 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id  
		WHERE mid='".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array(); //print_r($rowAppraisal);
		if(count($rowAppraisal)>0){
			
			$logo_URL = base_url('assets/images/logo.gif');
				$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
				</div>';
					
				$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
				
				$subject = 'Mid Year Review Approved';
				$site_base_url=base_url('hr_help_desk/my_midyear_review');
			
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
                 <p>Dear {$rowAppraisal[0]['full_name']},</p>                                 
				 <p>Your mid-year review application has been approved. </p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Check</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
				//echo $messageApp;
				$to = $rowAppraisal[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: HR <hr@polosoftech.com>' . "\r\n";
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $messageApp, $headers);

		}
		
		echo json_encode($result); 
	}
	public function update_mid_year_review_rejected_dh()
	{
		$mid = $this->input->post('mid');
		$result = $this->Hr_model->update_mid_year_review_rejected_dh($mid); 
		
		//mail to employee
		
		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name 
		FROM `midyear_review` om 
		JOIN `internal_user` i ON om.login_id = i.login_id 
		LEFT JOIN `user_desg` d ON d.desg_id = i.designation 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id  
		WHERE mid='".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array(); //print_r($rowAppraisal);
		if(count($rowAppraisal)>0){
			
			$logo_URL = base_url('assets/images/logo.gif');
				$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
				<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
				</div>';
					
				$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
				<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
				<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
					&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
				</div>';
				
				$subject = 'Mid Year Review Rejected';
				$site_base_url=base_url('hr_help_desk/my_midyear_review');
			
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
                 <p>Dear {$rowAppraisal[0]['full_name']},</p>                                 
				 <p>Your mid-year review application has been rejected. </p>
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to Check</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
				//echo $messageApp;
				$to = $rowAppraisal[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: HR <hr@polosoftech.com>' . "\r\n";
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $messageApp, $headers);

		}
		
		echo json_encode($result); 
	}
	public function update_midyear_review_remark()
	{
		$remark = $this->input->post('remark');
		$mid = $this->input->post('mid');
		$result = $this->Hr_model->update_midyear_review_remark($remark,$mid); 
		echo json_encode($result); 
	}
	public function midyear_review_print()
	{
		$this->mViewData['pageTitle']    = 'My mid year appraisal';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$mid = $_GET['mid'];

		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name 
		FROM `internal_user` i 
		LEFT JOIN `user_desg` d ON d.desg_id = i.designation 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		RIGHT JOIN `midyear_review` om ON om.login_id = i.login_id 
		LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id  
		WHERE i.login_id = '".$loginID."' and mid='".$mid."'";
		$revRes = $this->db->query($qryAppraisal);
		$rowAppraisal = $revRes->result_array();
		$this->mViewData['rowAppraisal'] = $revRes->result_array();

		$cond = "DATE_FORMAT(created_date,'%Y')='".date('Y',strtotime($rowAppraisal[0]['apply_date']))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->load->view('hr_help_desk/midyear_review_detail', $this->mViewData);
	}
	/*End */
	public function my_midyear_review()
	{
		$this->mViewData['pageTitle']    = 'My mid year review';
		//Template view
		$this->render('hr_help_desk/my_midyear_review_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/my_midyear_review_script');
	}
	public function get_my_midyear_review_search()
	{
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_my_midyear_review_search($searchYear); 
		echo json_encode($result); 
	}
	/*Start Ajax with angularjs function*/
	public function get_my_midyear_review()
	{
		$result = $this->Hr_model->get_my_midyear_review(); 
		echo json_encode($result); 
	}
	/*End */
	
	
	/*Start Annual appraisal form */
	public function annual_appraisal_form()
	{
		$this->mViewData['pageTitle']    = 'Annual Appraisal form';		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		
		// SAVE ACTION FOR Employee

		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') =='' && $this->session->userdata('user_id') !='')
		{ 
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessages= $this->db->query("SELECT * FROM `annual_appraisal` WHERE login_id='".$loginID."' $condn");
			$resMessage = $resMessages->result_array();
			$knowledge_job_ind_comment = htmlspecialchars($this->input->post('knowledge_job_ind_comment') , ENT_QUOTES) ;
			$knowledge_job_ind_rating = $this->input->post('knowledge_job_ind_rating');
			$quality_work_ind_comment = htmlspecialchars($this->input->post('quality_work_ind_comment') , ENT_QUOTES);
			$quality_work_ind_rating = $this->input->post('quality_work_ind_rating');
			$quantity_work_ind_comment = htmlspecialchars($this->input->post('quantity_work_ind_comment') , ENT_QUOTES);
			$quantity_work_ind_rating = $this->input->post('quantity_work_ind_rating');
			$work_attitude_ind_comment = htmlspecialchars($this->input->post('work_attitude_ind_comment') , ENT_QUOTES);
			$work_attitude_ind_rating = $this->input->post('work_attitude_ind_rating');
			$teamwork_ind_comment = htmlspecialchars($this->input->post('teamwork_ind_comment') , ENT_QUOTES);
			$teamwork_ind_rating = $this->input->post('teamwork_ind_rating');
			$problem_solving_ind_comment = htmlspecialchars($this->input->post('problem_solving_ind_comment') , ENT_QUOTES);
			$problem_solving_ind_rating = $this->input->post('problem_solving_ind_rating');
			$responsibility_ind_comment = htmlspecialchars($this->input->post('responsibility_ind_comment') , ENT_QUOTES);
			$responsibility_ind_rating = $this->input->post('responsibility_ind_rating');
			$motivation_ind_comment = htmlspecialchars($this->input->post('motivation_ind_comment') , ENT_QUOTES);
			$motivation_ind_rating = $this->input->post('motivation_ind_rating');
			$delegation_work_ind_comment = htmlspecialchars($this->input->post('delegation_work_ind_comment') , ENT_QUOTES);
			$delegation_work_ind_rating = $this->input->post('delegation_work_ind_rating');
			$assignments_other = htmlspecialchars($this->input->post('assignments_other') , ENT_QUOTES);
			$key_improvement = htmlspecialchars($this->input->post('key_improvement') , ENT_QUOTES);
			$way_improvement = htmlspecialchars($this->input->post('way_improvement') , ENT_QUOTES);
			if(count($resMessage)>0)
			{
				$updateSql="UPDATE `annual_appraisal` SET 	
					knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."',
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."',
					way_improvement= '".$way_improvement."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$resMessage[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";
								//echo $updateSql; 
				$this->db->query($updateSql);
			} 
			else{             
					
				$insertSql = "INSERT INTO `annual_appraisal` SET 
					login_id='".$this->session->userdata('user_id')."', 
					knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."',
					apply_date=now()";
					
				$this->db->query($insertSql);
			}
			
			/* for($i=0;$i<count($this->input->post('objective'));$i++){
				$insGoalSql ="UPDATE `goal_sheet` SET 
					act_achievement = '".$this->input->post('act_achievement')[$i]."',
					achievement_per = '".$this->input->post('achievement_per')[$i]."', 
					score= '".$this->input->post('score')[$i]."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$this->input->post('mid')[$i]."'";
				$this->db->query($insGoalSql);
			} */	
		}
		
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
		
		//Apply for Employee
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{  	 
			$pin="";
			$warray=array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
			for($k=0;$k<=7;$k++)
			{
				$p=rand(0,35);
				$pin=$pin.strtoupper($warray[$p]);
			}
			$pin=trim($pin);        
			 
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessages=$this->db->query("SELECT * FROM `annual_appraisal` WHERE login_id='".$loginID."' $condn");
			$resMessage = $resMessages->result_array();
			
			$knowledge_job_ind_comment = htmlspecialchars($this->input->post('knowledge_job_ind_comment') , ENT_QUOTES) ;
			$knowledge_job_ind_rating = $this->input->post('knowledge_job_ind_rating');
			$quality_work_ind_comment = htmlspecialchars($this->input->post('quality_work_ind_comment') , ENT_QUOTES);
			$quality_work_ind_rating = $this->input->post('quality_work_ind_rating');
			$quantity_work_ind_comment = htmlspecialchars($this->input->post('quantity_work_ind_comment') , ENT_QUOTES);
			$quantity_work_ind_rating = $this->input->post('quantity_work_ind_rating');
			$work_attitude_ind_comment = htmlspecialchars($this->input->post('work_attitude_ind_comment') , ENT_QUOTES);
			$work_attitude_ind_rating = $this->input->post('work_attitude_ind_rating');
			$teamwork_ind_comment = htmlspecialchars($this->input->post('teamwork_ind_comment') , ENT_QUOTES);
			$teamwork_ind_rating = $this->input->post('teamwork_ind_rating');
			$problem_solving_ind_comment = htmlspecialchars($this->input->post('problem_solving_ind_comment') , ENT_QUOTES);
			$problem_solving_ind_rating = $this->input->post('problem_solving_ind_rating');
			$responsibility_ind_comment = htmlspecialchars($this->input->post('responsibility_ind_comment') , ENT_QUOTES);
			$responsibility_ind_rating = $this->input->post('responsibility_ind_rating');
			$motivation_ind_comment = htmlspecialchars($this->input->post('motivation_ind_comment') , ENT_QUOTES);
			$motivation_ind_rating = $this->input->post('motivation_ind_rating');
			$delegation_work_ind_comment = htmlspecialchars($this->input->post('delegation_work_ind_comment') , ENT_QUOTES);
			$delegation_work_ind_rating = $this->input->post('delegation_work_ind_rating');
			$assignments_other = htmlspecialchars($this->input->post('assignments_other') , ENT_QUOTES);
			$key_improvement = htmlspecialchars($this->input->post('key_improvement') , ENT_QUOTES);
			$way_improvement = htmlspecialchars($this->input->post('way_improvement') , ENT_QUOTES);
			if(count($resMessage)>0)
			{	
				   
				$updateSql="UPDATE `annual_appraisal` SET 
				    knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."', 
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."',  
					responsibility_ind_comment = '".$responsibility_ind_comment."', 
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."', 
					unique_pin= '".$pin."', 
					apply_date=now(), 
					rm_status= '0', 
					dh_status= '0' 
					WHERE login_id='".$loginID."' AND mid='".$resMessage[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";
			} else{
				$updateSql = "INSERT INTO `annual_appraisal` SET 
					login_id='".$loginID."', 
				    knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."', 
					unique_pin= '".$pin."', 
					apply_date=now()";
			}
			$this->db->query($updateSql);
			
			
			/* for($i=0;$i<count($this->input->post('objective'));$i++){
				$insGoalSql ="UPDATE `goal_sheet` SET 
					act_achievement = '".$this->input->post('act_achievement')[$i]."',
					achievement_per = '".$this->input->post('achievement_per')[$i]."', 
					score= '".$this->input->post('score')[$i]."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$this->input->post('mid')[$i]."'";
				$this->db->query($insGoalSql);
			} */	
				
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();    

			$empSql = "SELECT full_name,email FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
			//exit;
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			//var_dump($empInfo);exit;
		
			   
			//sending mail to reporting manager 
			$site_base_url_rm=base_url('hr_help_desk/my_annual_appraisal');
			//development pending
			$messageRM=<<<EOD
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
					 <p>Your team member {$repMgrInfo[0]['full_name']} ({$repMgrInfo[0]['loginhandle']}) has submitted his/her Annual appraisal form. </p>                                             
					 <p>Please <a href="{$site_base_url_rm}" style="text-decoration:none">Click here</a> to review his/her self appraisal form.<br /><br /></p>                
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;
			
			$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
					 <p>Dear {$repMgrInfo[0]['full_name']},</p>
					 <p>Your Annual appraisal form has been submitted successfully.</p>
					 <p>Your unique pin ID is {$pin}.</p>                                  
					 <p>Please keep it handing during the review with your reporting manager.</p>                                             
					 <p><a href="{$site_base_url}" style="text-decoration:none">Click here</a><br /><br /></p>                
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;
					 
			$subject = 'Annual Appraisal Review';    
			$toRM=$repMgrInfo[0]['rfull_name'].'<'.$repMgrInfo[0]['remail'].'>';
			$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
			//$to='pradeep.sahoo@polosoftech.com';
			//echo $message;
			$headers='';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'From:'.$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>'. "\r\n";      
		   
			mail($toRM, $subject, $messageRM, $headers); 
			mail($to, $subject, $message, $headers); 
			redirect('/hr_help_desk/my_annual_appraisal');
		}
		

		
		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}	
		
		$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y'))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		
        $qryAppraisal = "SELECT i.login_id,i.user_role,i.reporting_to, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.*, dp.dept_id, rev.full_name as rm_full_name FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id != '10010' AND i.login_id = '".$loginID."'  $condn ";
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		
		$this->mViewData['revInfo'] = array();
		if(count($rowAppraisal)>0){
		if($rowAppraisal[0]["reporting_to"] !=""){
			$revRes=$this->db->query("SELECT rev.full_name, rev.login_id FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$rowAppraisal[0]["reporting_to"]." LIMIT 1");
			$this->mViewData['revInfo'] = $revRes->result_array();
		}
		}
		
		$qrys = "SELECT i.* FROM `internal_user` i WHERE i.login_id = '".$loginID."' ";
		$ress = $this->db->query($qrys);
		$rows = $ress->result_array();
		$cyr = date('Y');
		$date1 = strtotime(date('Y-m-d'));
		if(count($rows)>0){
			//echo $rows[0]['join_date'];
			$date1 = strtotime($rows[0]['join_date']);
		}
		$date2 = strtotime(date('Y-m-d', strtotime($cyr.'-03-01')));
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		//echo $months;
		$this->mViewData['noOfMonths'] = $months;
		//Template view
		$this->render('hr_help_desk/annual_appraisal_form_view', 'full_width', $this->mViewData); 
	}
	
	/*Start Annual appraisal form */
	public function annual_appraisal_form_edit()
	{
		$this->mViewData['pageTitle']    = 'Annual Appraisal form';		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		
		// SAVE ACTION FOR Employee

		if($this->input->post('btnSaveMessage') == "SAVE" && $this->session->userdata('user_id') !='')
		{ 
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessages= $this->db->query("SELECT * FROM `annual_appraisal` WHERE login_id='".$loginID."' $condn");
			$resMessage = $resMessages->result_array();
			$knowledge_job_ind_comment = htmlspecialchars($this->input->post('knowledge_job_ind_comment') , ENT_QUOTES) ;
			$knowledge_job_ind_rating = $this->input->post('knowledge_job_ind_rating');
			$quality_work_ind_comment = htmlspecialchars($this->input->post('quality_work_ind_comment') , ENT_QUOTES);
			$quality_work_ind_rating = $this->input->post('quality_work_ind_rating');
			$quantity_work_ind_comment = htmlspecialchars($this->input->post('quantity_work_ind_comment') , ENT_QUOTES);
			$quantity_work_ind_rating = $this->input->post('quantity_work_ind_rating');
			$work_attitude_ind_comment = htmlspecialchars($this->input->post('work_attitude_ind_comment') , ENT_QUOTES);
			$work_attitude_ind_rating = $this->input->post('work_attitude_ind_rating');
			$teamwork_ind_comment = htmlspecialchars($this->input->post('teamwork_ind_comment') , ENT_QUOTES);
			$teamwork_ind_rating = $this->input->post('teamwork_ind_rating');
			$problem_solving_ind_comment = htmlspecialchars($this->input->post('problem_solving_ind_comment') , ENT_QUOTES);
			$problem_solving_ind_rating = $this->input->post('problem_solving_ind_rating');
			$responsibility_ind_comment = htmlspecialchars($this->input->post('responsibility_ind_comment') , ENT_QUOTES);
			$responsibility_ind_rating = $this->input->post('responsibility_ind_rating');
			$motivation_ind_comment = htmlspecialchars($this->input->post('motivation_ind_comment') , ENT_QUOTES);
			$motivation_ind_rating = $this->input->post('motivation_ind_rating');
			$delegation_work_ind_comment = htmlspecialchars($this->input->post('delegation_work_ind_comment') , ENT_QUOTES);
			$delegation_work_ind_rating = $this->input->post('delegation_work_ind_rating');
			$assignments_other = htmlspecialchars($this->input->post('assignments_other') , ENT_QUOTES);
			$key_improvement = htmlspecialchars($this->input->post('key_improvement') , ENT_QUOTES);
			$way_improvement = htmlspecialchars($this->input->post('way_improvement') , ENT_QUOTES);
			if(count($resMessage)>0)
			{
				$updateSql="UPDATE `annual_appraisal` SET 	
					knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."',
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."',
					way_improvement= '".$way_improvement."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$resMessage[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";
								//echo $updateSql; 
				$this->db->query($updateSql);
			} 
			else{             
					
				$insertSql = "INSERT INTO `annual_appraisal` SET 
					login_id='".$this->session->userdata('user_id')."', 
					knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."',
					apply_date=now()";
					
				$this->db->query($insertSql);
			}
			
			/* for($i=0;$i<count($this->input->post('objective'));$i++){
				$insGoalSql ="UPDATE `goal_sheet` SET 
					act_achievement = '".$this->input->post('act_achievement')[$i]."',
					achievement_per = '".$this->input->post('achievement_per')[$i]."', 
					score= '".$this->input->post('score')[$i]."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$this->input->post('mid')[$i]."'";
				$this->db->query($insGoalSql);
			} */	
		}
		
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
		
		//Apply for Employee
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{  	 
			$pin="";
			$warray=array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
			for($k=0;$k<=7;$k++)
			{
				$p=rand(0,35);
				$pin=$pin.strtoupper($warray[$p]);
			}
			$pin=trim($pin);        
			 
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$resMessages=$this->db->query("SELECT * FROM `annual_appraisal` WHERE login_id='".$loginID."' $condn");
			$resMessage = $resMessages->result_array();
			
			$knowledge_job_ind_comment = htmlspecialchars($this->input->post('knowledge_job_ind_comment') , ENT_QUOTES) ;
			$knowledge_job_ind_rating = $this->input->post('knowledge_job_ind_rating');
			$quality_work_ind_comment = htmlspecialchars($this->input->post('quality_work_ind_comment') , ENT_QUOTES);
			$quality_work_ind_rating = $this->input->post('quality_work_ind_rating');
			$quantity_work_ind_comment = htmlspecialchars($this->input->post('quantity_work_ind_comment') , ENT_QUOTES);
			$quantity_work_ind_rating = $this->input->post('quantity_work_ind_rating');
			$work_attitude_ind_comment = htmlspecialchars($this->input->post('work_attitude_ind_comment') , ENT_QUOTES);
			$work_attitude_ind_rating = $this->input->post('work_attitude_ind_rating');
			$teamwork_ind_comment = htmlspecialchars($this->input->post('teamwork_ind_comment') , ENT_QUOTES);
			$teamwork_ind_rating = $this->input->post('teamwork_ind_rating');
			$problem_solving_ind_comment = htmlspecialchars($this->input->post('problem_solving_ind_comment') , ENT_QUOTES);
			$problem_solving_ind_rating = $this->input->post('problem_solving_ind_rating');
			$responsibility_ind_comment = htmlspecialchars($this->input->post('responsibility_ind_comment') , ENT_QUOTES);
			$responsibility_ind_rating = $this->input->post('responsibility_ind_rating');
			$motivation_ind_comment = htmlspecialchars($this->input->post('motivation_ind_comment') , ENT_QUOTES);
			$motivation_ind_rating = $this->input->post('motivation_ind_rating');
			$delegation_work_ind_comment = htmlspecialchars($this->input->post('delegation_work_ind_comment') , ENT_QUOTES);
			$delegation_work_ind_rating = $this->input->post('delegation_work_ind_rating');
			$assignments_other = htmlspecialchars($this->input->post('assignments_other') , ENT_QUOTES);
			$key_improvement = htmlspecialchars($this->input->post('key_improvement') , ENT_QUOTES);
			$way_improvement = htmlspecialchars($this->input->post('way_improvement') , ENT_QUOTES);
			if(count($resMessage)>0)
			{	
				   
				$updateSql="UPDATE `annual_appraisal` SET 
				    knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."', 
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."',  
					responsibility_ind_comment = '".$responsibility_ind_comment."', 
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."', 
					unique_pin= '".$pin."', 
					apply_date=now(), 
					rm_status= '0', 
					dh_status= '0' 
					WHERE login_id='".$loginID."' AND mid='".$resMessage[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";
			} else{
				$updateSql = "INSERT INTO `annual_appraisal` SET 
					login_id='".$loginID."', 
				    knowledge_job_ind_comment = '".$knowledge_job_ind_comment."', 
					knowledge_job_ind_rating = '".$knowledge_job_ind_rating."', 
					quality_work_ind_comment= '".$quality_work_ind_comment."',
					quality_work_ind_rating = '".$quality_work_ind_rating."',                        
					quantity_work_ind_comment = '".$quantity_work_ind_comment."', 
					quantity_work_ind_rating= '".$quantity_work_ind_rating."',
					work_attitude_ind_comment = '".$work_attitude_ind_comment."',
					work_attitude_ind_rating = '".$work_attitude_ind_rating."', 
					teamwork_ind_comment= '".$teamwork_ind_comment."',
					teamwork_ind_rating = '".$teamwork_ind_rating."',                        
					problem_solving_ind_comment = '".$problem_solving_ind_comment."', 
					problem_solving_ind_rating= '".$problem_solving_ind_rating."', 
					responsibility_ind_comment = '".$responsibility_ind_comment."',
					responsibility_ind_rating = '".$responsibility_ind_rating."', 
					motivation_ind_comment= '".$motivation_ind_comment."',
					motivation_ind_rating = '".$motivation_ind_rating."',                        
					delegation_work_ind_comment = '".$delegation_work_ind_comment."', 
					delegation_work_ind_rating= '".$delegation_work_ind_rating."',
					assignments_other = '".$assignments_other."',
					key_improvement = '".$key_improvement."', 
					way_improvement= '".$way_improvement."', 
					unique_pin= '".$pin."', 
					apply_date=now()";
			}
			$this->db->query($updateSql);
			
			
			/* for($i=0;$i<count($this->input->post('objective'));$i++){
				$insGoalSql ="UPDATE `goal_sheet` SET 
					act_achievement = '".$this->input->post('act_achievement')[$i]."',
					achievement_per = '".$this->input->post('achievement_per')[$i]."', 
					score= '".$this->input->post('score')[$i]."' 
					WHERE login_id='".$this->session->userdata('user_id')."' AND mid='".$this->input->post('mid')[$i]."'";
				$this->db->query($insGoalSql);
			} */	
				
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();    

			$empSql = "SELECT full_name,email FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
			//exit;
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			//var_dump($empInfo);exit;
		
			   
			//sending mail to reporting manager 
			$site_base_url_rm=base_url('hr_help_desk/my_annual_appraisal');
			//development pending
			$messageRM=<<<EOD
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
					 <p>Your team member {$repMgrInfo[0]['full_name']} ({$repMgrInfo[0]['loginhandle']}) has submitted his/her Annual appraisal form. </p>                                             
					 <p>Please <a href="{$site_base_url_rm}" style="text-decoration:none">Click here</a> to review his/her self appraisal form.<br /><br /></p>                
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;
			
			$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
					 <p>Dear {$repMgrInfo[0]['full_name']},</p>
					 <p>Your Annual appraisal form has been submitted successfully.</p>
					 <p>Your unique pin ID is {$pin}.</p>                                  
					 <p>Please keep it handing during the review with your reporting manager.</p>                                             
					 <p><a href="{$site_base_url}" style="text-decoration:none">Click here</a><br /><br /></p>                
					 <p> In case of any Query, Please contact to HR Department.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;
					 
			$subject = 'Annual Appraisal Review';    
			$toRM=$repMgrInfo[0]['rfull_name'].'<'.$repMgrInfo[0]['remail'].'>';
			$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
			//$to='pradeep.sahoo@polosoftech.com';
			//echo $message;
			$headers='';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'From:'.$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>'. "\r\n";      
		   
			mail($toRM, $subject, $messageRM, $headers); 
			mail($to, $subject, $message, $headers); 
			redirect('/hr_help_desk/my_annual_appraisal');
		}
		

		
		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}	
		
		$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y'))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		
        $qryAppraisal = "SELECT i.login_id,i.user_role,i.reporting_to, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.full_name, om.*, dp.dept_id, rev.full_name as rm_full_name FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id != '10010' AND i.login_id = '".$loginID."'  $condn ";
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		
		$this->mViewData['revInfo'] = array();
		if(count($rowAppraisal)>0){
		if($rowAppraisal[0]["reporting_to"] !=""){
			$revRes=$this->db->query("SELECT rev.full_name, rev.login_id FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$rowAppraisal[0]["reporting_to"]." LIMIT 1");
			$this->mViewData['revInfo'] = $revRes->result_array();
		}
		}
		
		$qrys = "SELECT i.* FROM `internal_user` i WHERE i.login_id = '".$loginID."' ";
		$ress = $this->db->query($qrys);
		$rows = $ress->result_array();
		$cyr = date('Y');
		$date1 = strtotime(date('Y-m-d'));
		if(count($rows)>0){
			//echo $rows[0]['join_date'];
			$date1 = strtotime($rows[0]['join_date']);
		}
		$date2 = strtotime(date('Y-m-d', strtotime($cyr.'-02-28')));
		$months = 0;
		while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
			$months++;
		}
		$this->mViewData['noOfMonths'] = $months;
		//Template view
		$this->render('hr_help_desk/annual_appraisal_form_view_edit', 'full_width', $this->mViewData); 
	}
	
	/*Start Annual appraisal form Reporting */
	public function annual_appraisal_form_rm()
	{
		$this->mViewData['pageTitle']    = 'Annual Appraisal form';		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
		
		// SAVE FOR APPRAISER

		if($this->input->post('btnSaveMessage') == "SAVE" && $this->input->post('login_id') !='' && $this->session->userdata('user_id') !='')
		{ 
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$checkStartRESs = $this->db->query("SELECT * FROM `annual_appraisal` WHERE login_id='".$this->input->post('login_id')."' $condn");
			$checkStartRES = $checkStartRESs->result_array();
			
			$knowledge_job_sup_comment = htmlspecialchars($this->input->post('knowledge_job_sup_comment') , ENT_QUOTES) ;
			$quality_work_sup_comment = htmlspecialchars($this->input->post('quality_work_sup_comment') , ENT_QUOTES) ;
			$quantity_work_sup_comment = htmlspecialchars($this->input->post('quantity_work_sup_comment') , ENT_QUOTES) ;
			$work_attitude_sup_comment = htmlspecialchars($this->input->post('work_attitude_sup_comment') , ENT_QUOTES) ;
			$teamwork_sup_comment = htmlspecialchars($this->input->post('teamwork_sup_comment') , ENT_QUOTES) ;
			$problem_solving_sup_comment = htmlspecialchars($this->input->post('problem_solving_sup_comment') , ENT_QUOTES) ;
			$responsibility_sup_comment = htmlspecialchars($this->input->post('responsibility_sup_comment') , ENT_QUOTES) ;
			$motivation_sup_comment = htmlspecialchars($this->input->post('motivation_sup_comment') , ENT_QUOTES) ;
			$delegation_work_sup_comment = htmlspecialchars($this->input->post('delegation_work_sup_comment') , ENT_QUOTES) ;
			$additional_responsibilities = htmlspecialchars($this->input->post('additional_responsibilities') , ENT_QUOTES) ;
			$appraisee_strengths = htmlspecialchars($this->input->post('appraisee_strengths') , ENT_QUOTES) ;
			$areas_improvement = htmlspecialchars($this->input->post('areas_improvement') , ENT_QUOTES) ;
			$action_plans = htmlspecialchars($this->input->post('action_plans') , ENT_QUOTES) ;
			$recommendation = htmlspecialchars($this->input->post('recommendation') , ENT_QUOTES) ;
			$promotion = htmlspecialchars($this->input->post('promotion') , ENT_QUOTES) ;
			if(count($checkStartRES) > 0){    
				
				$updateSql="UPDATE `annual_appraisal` SET 
					knowledge_job_sup_comment= '".$knowledge_job_sup_comment."',
					knowledge_job_sup_rating = '".$this->input->post('knowledge_job_sup_rating')."',
					knowledge_job_fin_rating = '".$this->input->post('knowledge_job_fin_rating')."', 
					quality_work_sup_comment = '".$quality_work_sup_comment."', 
					quality_work_sup_rating= '".$this->input->post('quality_work_sup_rating')."',
					quality_work_fin_rating = '".$this->input->post('quality_work_fin_rating')."',
					quantity_work_sup_comment = '".$quantity_work_sup_comment."',
					quantity_work_sup_rating = '".$this->input->post('quantity_work_sup_rating')."',
					quantity_work_fin_rating= '".$this->input->post('quantity_work_fin_rating')."', 
					work_attitude_sup_comment= '".$work_attitude_sup_comment."',
					work_attitude_sup_rating = '".$this->input->post('work_attitude_sup_rating')."',
					work_attitude_fin_rating = '".$this->input->post('work_attitude_fin_rating')."', 
					teamwork_sup_comment = '".$teamwork_sup_comment."', 
					teamwork_sup_rating= '".$this->input->post('teamwork_sup_rating')."',
					teamwork_fin_rating = '".$this->input->post('teamwork_fin_rating')."', 
					problem_solving_sup_comment = '".$problem_solving_sup_comment."',
					problem_solving_sup_rating = '".$this->input->post('problem_solving_sup_rating')."', 
					problem_solving_fin_rating= '".$this->input->post('problem_solving_fin_rating')."',
					 responsibility_sup_comment= '".$responsibility_sup_comment."',
					 responsibility_sup_rating = '".$this->input->post('responsibility_sup_rating')."',
					responsibility_fin_rating = '".$this->input->post('responsibility_fin_rating')."', 
					motivation_sup_comment = '".$motivation_sup_comment."', 
					motivation_sup_rating= '".$this->input->post('motivation_sup_rating')."',
					motivation_fin_rating = '".$this->input->post('motivation_fin_rating')."', 
					delegation_work_sup_comment = '".$delegation_work_sup_comment."',
					delegation_work_sup_rating = '".$this->input->post('delegation_work_sup_rating')."', 
					delegation_work_fin_rating= '".$this->input->post('delegation_work_fin_rating')."',
					additional_responsibilities = '".$additional_responsibilities."',
					appraisee_strengths = '".$appraisee_strengths."', 
					areas_improvement= '".$areas_improvement."',
					action_plans = '".$action_plans."',
					recommendation = '".$recommendation."', 
					promotion= '".$promotion."'
					WHERE login_id='".$this->input->post('login_id')."' AND mid='".$checkStartRES[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";  
				
				$this->db->query($updateSql);

				for($i=0;$i<count($this->input->post('objective'));$i++){
					$insGoalSql ="UPDATE `goal_sheet` SET 
						act_achievement = '".$this->input->post('act_achievement')[$i]."',
						achievement_per = '".$this->input->post('achievement_per')[$i]."', 
						score= '".$this->input->post('score')[$i]."' 
						WHERE login_id='".$this->input->post('login_id')."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql);
				} 
			}
		}
		
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageRM') == "Reject" && $this->session->userdata('user_id') !='')
		{
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$updateSql="UPDATE `annual_appraisal` SET  rm_status='2' WHERE login_id='".$this->input->post('login_id')."' $condn";      
			$this->db->query($updateSql);
			
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$this->input->post('login_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();
				
				//sending mail is pending 
			$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
					 <p>Dear {$repMgrInfo[0]['full_name']},</p>                                 
					 <p>Your annual appraisal form has been rejected by your reporting authority. </p>                                             
					 <p><a href="{$site_base_url}" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
					 <p> In case of any Query, Please contact to your reporting manager.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;

			$subject = 'Annual Appraisal Review';    
			
			$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
			//$to='pradeep.sahoo@polosoftech.com';
			//echo $message;
			$headers='';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'From:'.$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>'. "\r\n";           
			mail($to, $subject, $messageApp, $headers); 
			redirect('/hr_help_desk/annual_appraisal');
		}
		

		// APPROVE ACTION FOR APPRAISER

		if($this->input->post('btnAddMessageRM') == "APPROVE" && $this->session->userdata('user_id') !='')
		{ 
			
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$checkStartRES = $this->db->query("SELECT * FROM `annual_appraisal` WHERE unique_pin= '".$this->input->post('unique_pin')."' AND login_id='".$this->input->post('login_id')."' $condn");
			$checkStart = $checkStartRES->result_array();
			$checkStartNUM = count($checkStart);
			$knowledge_job_sup_comment = htmlspecialchars($this->input->post('knowledge_job_sup_comment') , ENT_QUOTES) ;
			$quality_work_sup_comment = htmlspecialchars($this->input->post('quality_work_sup_comment') , ENT_QUOTES) ;
			$quantity_work_sup_comment = htmlspecialchars($this->input->post('quantity_work_sup_comment') , ENT_QUOTES) ;
			$work_attitude_sup_comment = htmlspecialchars($this->input->post('work_attitude_sup_comment') , ENT_QUOTES) ;
			$teamwork_sup_comment = htmlspecialchars($this->input->post('teamwork_sup_comment') , ENT_QUOTES) ;
			$problem_solving_sup_comment = htmlspecialchars($this->input->post('problem_solving_sup_comment') , ENT_QUOTES) ;
			$responsibility_sup_comment = htmlspecialchars($this->input->post('responsibility_sup_comment') , ENT_QUOTES) ;
			$motivation_sup_comment = htmlspecialchars($this->input->post('motivation_sup_comment') , ENT_QUOTES) ;
			$delegation_work_sup_comment = htmlspecialchars($this->input->post('delegation_work_sup_comment') , ENT_QUOTES) ;
			$additional_responsibilities = htmlspecialchars($this->input->post('additional_responsibilities') , ENT_QUOTES) ;
			$appraisee_strengths = htmlspecialchars($this->input->post('appraisee_strengths') , ENT_QUOTES) ;
			$areas_improvement = htmlspecialchars($this->input->post('areas_improvement') , ENT_QUOTES) ;
			$action_plans = htmlspecialchars($this->input->post('action_plans') , ENT_QUOTES) ;
			$recommendation = htmlspecialchars($this->input->post('recommendation') , ENT_QUOTES) ;
			$promotion = htmlspecialchars($this->input->post('promotion') , ENT_QUOTES) ;
			if($checkStartNUM > 0){    
			
			   $updateSql="UPDATE `annual_appraisal` SET 
				   knowledge_job_sup_comment= '".$knowledge_job_sup_comment."',
					knowledge_job_sup_rating = '".$this->input->post('knowledge_job_sup_rating')."',
					knowledge_job_fin_rating = '".$this->input->post('knowledge_job_fin_rating')."', 
					quality_work_sup_comment = '".$quality_work_sup_comment."', 
					quality_work_sup_rating= '".$this->input->post('quality_work_sup_rating')."',
					quality_work_fin_rating = '".$this->input->post('quality_work_fin_rating')."',
					quantity_work_sup_comment = '".$quantity_work_sup_comment."',
					quantity_work_sup_rating = '".$this->input->post('quantity_work_sup_rating')."',
					quantity_work_fin_rating= '".$this->input->post('quantity_work_fin_rating')."', 
					work_attitude_sup_comment= '".$work_attitude_sup_comment."',
					work_attitude_sup_rating = '".$this->input->post('work_attitude_sup_rating')."',
					work_attitude_fin_rating = '".$this->input->post('work_attitude_fin_rating')."', 
					teamwork_sup_comment = '".$teamwork_sup_comment."', 
					teamwork_sup_rating= '".$this->input->post('teamwork_sup_rating')."',
					teamwork_fin_rating = '".$this->input->post('teamwork_fin_rating')."', 
					problem_solving_sup_comment = '".$problem_solving_sup_comment."',
					problem_solving_sup_rating = '".$this->input->post('problem_solving_sup_rating')."', 
					problem_solving_fin_rating= '".$this->input->post('problem_solving_fin_rating')."',
					 responsibility_sup_comment= '".$responsibility_sup_comment."',
					 responsibility_sup_rating = '".$this->input->post('responsibility_sup_rating')."',
					responsibility_fin_rating = '".$this->input->post('responsibility_fin_rating')."', 
					motivation_sup_comment = '".$motivation_sup_comment."', 
					motivation_sup_rating= '".$this->input->post('motivation_sup_rating')."',
					motivation_fin_rating = '".$this->input->post('motivation_fin_rating')."', 
					delegation_work_sup_comment = '".$delegation_work_sup_comment."',
					delegation_work_sup_rating = '".$this->input->post('delegation_work_sup_rating')."', 
					delegation_work_fin_rating= '".$this->input->post('delegation_work_fin_rating')."',
					additional_responsibilities = '".$additional_responsibilities."',
					appraisee_strengths = '".$appraisee_strengths."', 
					areas_improvement= '".$areas_improvement."',
					action_plans = '".$action_plans."',
					recommendation = '".$recommendation."', 
					promotion= '".$promotion."', 
					rm_status='1', 
					total_score_a='".$this->input->post('section_a')."', 
					total_score_b='".$this->input->post('section_b')."', 
					approved_date=now()
					WHERE login_id='".$this->input->post('login_id')."' AND mid='".$checkStart[0]['mid']."' ORDER BY `mid` DESC LIMIT 1";        
				$this->db->query($updateSql);
				
				for($i=0;$i<count($this->input->post('objective'));$i++){
					$insGoalSql ="UPDATE `goal_sheet` SET 
						act_achievement = '".$this->input->post('act_achievement')[$i]."',
						achievement_per = '".$this->input->post('achievement_per')[$i]."', 
						score= '".$this->input->post('score')[$i]."' 
						WHERE login_id='".$this->input->post('login_id')."' AND mid='".$this->input->post('mid')[$i]."'";
					$this->db->query($insGoalSql);
				}      

				$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$this->input->post('login_id')."'";
				$repMgrRes = $this->db->query($repMgrSql);
				$repMgrInfo = $repMgrRes->result_array();
				
				$revInfo = array();
				if(count($repMgrInfo)>0){
					$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
					$revRes = $this->db->query($revSql);
					$revInfo = $revRes->result_array();
				}

				//sending mail development pending 
				$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
						 <p>Dear {$repMgrInfo[0]['full_name']},</p>                                 
						 <p>Your annual appraisal form has been approved by your reporting authority. </p>                                             
						 <p><a href="{$site_base_url}" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
						 <p> In case of any Query, Please contact to your reporting manager.</p>                                 
						 <p>{$footer}</p>
						 </div> 
					  </div> 
					</div>  
					</div>
				</body>
				</html>
EOD;
				$site_base_url_rv=base_url('hr_help_desk/annual_appraisal');
				$messageAppReview=<<<EOD
				<!DOCTYPE HTML>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />                       
				</head>
				<body>{$logoText}<div style="width:100%; font-family: verdana; font-size: 13px;">
					<div style="width:900px; margin: 0 auto; background: #fff;">
					 <div style="width:650px; float: left; min-height: 190px;">
						 <div style="padding: 7px 7px 14px 10px;">
						 <p>Dear {$revInfo[0]['full_name']},</p>                                 
						 <p>Annual Appraisal form of {$repMgrInfo[0]} has been approved by his/her reporting authority. </p>                                             
						 <p><a href="{$site_base_url_rv}" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
						 <p> In case of any Query, Please contact to your reporting manager.</p>                                 
						 <p>{$footer}</p>
						 </div> 
					  </div> 
					</div>  
					</div>
				</body>
				</html>
EOD;

				$subject = 'Annual Appraisal Review';    
				
				$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
				$toReview=$revInfo[0]['full_name'].'<'.$revInfo[0]['email'].'>';
				//echo $message;
				$headers='';
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
				$headers .= "X-Priority: 1\r\n"; 
				$headers .= 'From:'.$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>'. "\r\n";          
				mail($to, $subject, $messageApp, $headers); 
				mail($toReview, $subject, $messageAppReview, $headers); 
				redirect('/hr_help_desk/annual_appraisal');
			}
			else{
				$this->session->set_userdata('upin_msg', '<span style="color:red;">Invalid Unique Pin </span>');
				redirect("/hr_help_desk/annual_appraisal_form_rm?id=$loginID&mid=$mid");
			}
		}
		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		//echo $loginID;
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}	
		
		$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y'))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		
        $qryAppraisal = "SELECT i.login_id,i.user_role,i.reporting_to, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.full_name, om.*, dp.dept_id, rev.full_name as rm_full_name FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id != '10010' AND i.login_id = '".$loginID."'  $condn ";
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		//print_r($rowAppraisal);
		$this->mViewData['revInfo'] = array();
		if(count($rowAppraisal)>0){
		if($rowAppraisal[0]["reporting_to"] !=""){
			$revRes=$this->db->query("SELECT rev.full_name, rev.login_id FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$rowAppraisal[0]["reporting_to"]." LIMIT 1");
			$this->mViewData['revInfo'] = $revRes->result_array();
		}
		}
		//print_r($this->mViewData['revInfo']);
		//Template view
		$this->render('hr_help_desk/annual_appraisal_form_view_rm', 'full_width', $this->mViewData); 
	}
	
	/*Start Annual appraisal form Reviewer */
	public function annual_appraisal_form_dh()
	{
		$this->mViewData['pageTitle']    = 'Annual Appraisal form';		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		
		$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
		
		
		//FOR RECJECT OF REVIEWER 
		if($this->input->post('btnRejectMessageReview') == "Reject" && $this->session->userdata('user_id') !='')
		{
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			else{
				$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
			}
			$updateSql="UPDATE `annual_appraisal` SET  dh_status='2' WHERE login_id='".$this->input->post('login_id')."' $condn";      
			$this->db->query($updateSql);
			
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$this->input->post('login_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();
			
			$revInfo = array();
			if(count($repMgrInfo)>0){
				$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
				$revRes = $this->db->query($revSql);
				$revInfo = $revRes->result_array();
			}	
			
			//sending mail is pending
			$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
					 <p>Dear {$repMgrInfo[0]['full_name']},</p>                                 
					 <p>Your annual appraisal form has been rejected by your reviewing authority. </p>                                             
					 <p><a href="{$site_base_url}" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
					 <p> In case of any Query, Please contact to your reporting manager.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;

			$subject = 'Annual Appraisal Review';    
			
			$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
			//$to='pradeep.sahoo@polosoftech.com';
			//echo $message;
			$headers='';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'From:'.$revInfo[0]['full_name'].'<'.$revInfo[0]['email'].'>'. "\r\n";          
			mail($to, $subject, $messageApp, $headers);  
			 
			redirect('/hr_help_desk/annual_appraisal_reviewer');
			
		}
		
		//FOR Approve OF Reviewer
		if($this->input->post('btnAddMessageReview') == "APPROVE" && $this->session->userdata('user_id') !='')
		{
			$condn = "";
			if($mid !=""){
				$condn = " AND mid='".$mid."'";
			}
			$recommendation = htmlspecialchars($this->input->post('recommendation') , ENT_QUOTES) ;
			$updateSql="UPDATE `annual_appraisal` SET  
				recommendation = '".$recommendation."', 
				dh_status='1', 
				approved_date=now() 
				WHERE login_id='".$this->input->post('login_id')."' $condn";      
			$this->db->query($updateSql);
    
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$this->input->post('login_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();
			
			$revInfo = array();
			if(count($repMgrInfo)>0){
				$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
				$revRes = $this->db->query($revSql);
				$revInfo = $revRes->result_array();
			}
			
			$site_base_url=base_url('hr_help_desk/my_annual_appraisal');
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
					 <p>Dear {$repMgrInfo[0]['full_name']},</p>                                 
					 <p>Your annual appraisal form has been approved by your reviewing authority. </p>                                             
					 <p><a href="{$site_base_url}" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
					 <p> In case of any Query, Please contact to your reporting manager.</p>                                 
					 <p>{$footer}</p>
					 </div> 
				  </div> 
				</div>  
				</div>
			</body>
			</html>
EOD;

			$subject = 'Annual Appraisal Review';    
			
			
			$to=$repMgrInfo[0]['full_name'].'<'.$repMgrInfo[0]['email'].'>';
			//$to='pradeep.sahoo@polosoftech.com';
			//echo $message;
			$headers='';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$headers .= "X-Priority: 1\r\n"; 
			$headers .= 'From:'.$revInfo[0]['full_name'].'<'.$revInfo[0]['email'].'>'. "\r\n";          
			mail($to, $subject, $messageApp, $headers);  
			 
			redirect('/hr_help_desk/annual_appraisal_reviewer');
		}

		
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		//echo $loginID;
		$condn = "";
		if($mid !=""){
			$condn = " AND mid='".$mid."'";
		}
		else{
			$condn = " AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."' ";
		}	
		
		$cond = "DATE_FORMAT(annualdate,'%Y')='".(date('Y'))."'";
		
		
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		
        $qryAppraisal = "SELECT i.login_id,i.user_role,i.reporting_to, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, i.full_name, om.*, dp.dept_id, rev.full_name as rm_full_name FROM `internal_user` i RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id != '10010' AND i.login_id = '".$loginID."'  $condn ";
		$resAppraisal = $this->db->query($qryAppraisal);
		$rowAppraisal = $resAppraisal->result_array();
		$this->mViewData['rowAppraisal'] = $rowAppraisal;
		//print_r($rowAppraisal);
		$this->mViewData['revInfo'] = array();
		if(count($rowAppraisal)>0){
		if($rowAppraisal[0]["reporting_to"] !=""){
			$revRes=$this->db->query("SELECT rev.full_name, rev.login_id FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.reporting_to WHERE i.login_id = ".$rowAppraisal[0]["reporting_to"]." LIMIT 1");
			$this->mViewData['revInfo'] = $revRes->result_array();
		}
		}
		//print_r($this->mViewData['revInfo']);
		//Template view
		$this->render('hr_help_desk/annual_appraisal_form_view_dh', 'full_width', $this->mViewData); 
	}
	public function annual_appraisal()
	{ 
		$this->mViewData['pageTitle']    = 'Annual appraisal';
		$this->mViewData['pageTitle']    = 'Annual appraisal';
		//Template view
		$this->render('hr_help_desk/annual_appraisal_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/annual_appraisal_script');
	}
	
	/*Start Ajax with angularjs function*/
	public function get_annual_appraisal()
	{
		$result = $this->Hr_model->get_annual_appraisal(); 
		echo json_encode($result); 
	}
	public function update_annual_appraisal_remark()
	{
		$remark = $this->input->post('remark');
		$mid = $this->input->post('mid');
		$result = $this->Hr_model->update_annual_appraisal_remark($remark,$mid); 
		echo json_encode($result); 
	}
	public function get_annual_appraisal_search()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_annual_appraisal_search($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	
	public function annual_appraisal_reviewer()
	{ 
		$this->mViewData['pageTitle']    = 'Annual appraisal';
		$this->mViewData['pageTitle']    = 'Annual appraisal';
		//Template view
		$this->render('hr_help_desk/annual_appraisal_view_dh', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/annual_appraisal_script_dh');
	}
	/*Start Ajax with angularjs function*/
	public function get_annual_appraisal_dh()
	{
		$result = $this->Hr_model->get_annual_appraisal_dh(); 
		echo json_encode($result); 
	}
	public function get_annual_appraisal_search_dh()
	{
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		$searchYear = $this->input->post('searchYear');
		$result = $this->Hr_model->get_annual_appraisal_search_dh($searchDepartment,$searchName,$searchDesignation,$searchEmpCode,$searchYear); 
		echo json_encode($result); 
	}
	public function annual_appraisal_print()
	{
		$this->mViewData['pageTitle']    = 'My annual appraisal';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$mid = $_GET['mid'];

		$qryAppraisal = "SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `annual_appraisal` om ON om.login_id = i.login_id LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id  WHERE i.login_id = '".$loginID."'and mid='".$mid."'";
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
	/*End */
	
	public function my_annual_appraisal()
	{
		$this->mViewData['pageTitle']    = 'My annual appraisal';		
		//Template view
		$this->render('hr_help_desk/my_annual_appraisal_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/my_annual_appraisal_script');
	}
	public function get_my_annual_appraisal()
	{
		$result = $this->Hr_model->get_my_annual_appraisal(); 
		echo json_encode($result); 
	}
	
	public function online_mrf()
	{
		$this->mViewData['pageTitle']    = 'Online mrf';
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$loginID = $_GET['id'];
		}
		$mid = "";
		if (isset($_GET['mid'])) {
			$mid = $_GET['mid'];
		}
		if($this->input->post('btnAddMessage') == "APPLY")
		{
			$department 					= $this->input->post('department');
			$designation 					= $this->input->post('designation');
			$branch 						= $this->input->post('branch');
			$reason_recruitment 			= $this->input->post('reason_recruitment');
			$no_vacancies 					= $this->input->post('no_vacancies');
			$justification 					= $this->input->post('justification');
			$job_description 				= $this->input->post('job_description');
			$essential_qualification 		= $this->input->post('essential_qualification');
			$essential_length_experience 	= $this->input->post('essential_length_experience');
			$essential_kind_experience 		= $this->input->post('essential_kind_experience');
			$essential_other 				= $this->input->post('essential_other');
			$desirable_qualification 		= $this->input->post('desirable_qualification');
			$desirable_length_experience 	= $this->input->post('desirable_length_experience');
			$desirable_kind_experience 		= $this->input->post('desirable_kind_experience');
			$desirable_other 				= $this->input->post('desirable_other');
			$time_period 					= $this->input->post('time_period');
			
			$res = $this->Hr_model->get_online_mrf();
			//$count = count($res); 
			//if($count>0)
			if($mid != "")
			{	
				$this->Hr_model->update_online_mrf($department,$designation,$branch,$reason_recruitment,$no_vacancies,$justification,$job_description,$essential_qualification,$essential_length_experience,$essential_kind_experience,$essential_other,$desirable_qualification,$desirable_length_experience,$desirable_kind_experience,$desirable_other,$time_period,$loginID,$mid);
			}else{
				$this->Hr_model->insert_online_mrf($department,$designation,$branch,$reason_recruitment,$no_vacancies,$justification,$job_description,$essential_qualification,$essential_length_experience,$essential_kind_experience,$essential_other,$desirable_qualification,$desirable_length_experience,$desirable_kind_experience,$desirable_other,$time_period);
			}  
			
			//sending mail to reporting manager ---  
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."')";
			//exit;
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			//var_dump($repInfo);
			
			$empSql = "SELECT full_name,email FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
			//exit;
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$desgSql = "SELECT desg_name FROM `user_desg` WHERE desg_id = '".$designation."'";
			//exit;
			$desgRes = $this->db->query($desgSql);
			$desgInfo = $desgRes->result_array();
			
			$message =$messageEmp ='';
			$site_base_url=base_url('hr_help_desk/online_mrf_detail_all');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
                <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
            </div>';
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
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
                 <p>Dear {$repInfo[0]["full_name"]},</p>                                 
                 <p>Please find the required position in our organisation. </p>                
                 <p>{$empInfo[0]['full_name']} raised a MRF for position of  {$desgInfo[0]['desg_name']}. </p>  
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
            
			$site_base_url_hr=base_url('hr/online_mrf_detail');
			$message_hr =<<<EOD
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
                 <p>Please find the required position in our organisation. </p>                
                 <p>{$empInfo[0]['full_name']} raised a MRF for position of  {$desgInfo[0]['desg_name']}. </p>  
                 <p><a href="{$site_base_url_hr}" style="text-decoration:none">Click here to Check</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
			
			$to =$repInfo[0]['email'];
			$toEmp =$empInfo[0]['email'];
			
			//$to ="utkalika.biswal@polosoftech.com";                        
			$subject = "POLOSOFT New online MRF Applied";      
			//echo $message;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Online MRF POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n"; 			
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers); 
				
			redirect('hr_help_desk/online_mrf_detail_all');
		} 
		$this->mViewData['department'] = $this->Hr_model->get_department();
		
		$branchSQL = "SELECT branch_id, branch_name FROM company_branch WHERE status = 'A'";
		$branchRes = $this->db->query($branchSQL);
		$this->mViewData['branchInfo'] = $branchRes->result_array();
		
		$designationArr= array();
		$this->mViewData['rowMRF'] = array();
		if($mid !=""){
			$mysql_qry = "SELECT * FROM `online_mrf` WHERE mid = '".$mid."' AND login_id = '".$loginID."'";
			$resMRF = $this->db->query($mysql_qry);
			$rowMRF = $resMRF->result_array();
			$this->mViewData['rowMRF'] = $resMRF->result_array();
			if(count($rowMRF)>0){
				$designationArr = $this->event_model->get_designation_by_department($rowMRF[0]['department']); 
			}
		}
		$this->mViewData['designation'] = $designationArr;
		//Template view
		$this->render('hr_help_desk/online_mrf_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr_help_desk/js/online_mrf_js', $this->mViewData); 
	}
	
	public function online_mrf_detail_all()
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
		if($action == 'reject'){
			$del_qry = "UPDATE `online_mrf` SET dh_status='2' WHERE login_id = '".$loginID."' AND mid = '".$mid."'";
			$this->db->query($del_qry);
		}
		if($action == 'approve'){
			$del_qry = "UPDATE `online_mrf` SET dh_status='1' WHERE login_id = '".$loginID."' AND mid = '".$mid."'";
			$this->db->query($del_qry);
			
			$empSql = "SELECT full_name,email FROM `internal_user` WHERE login_id = '".$loginID."'";
			//exit;
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			
			$desgSql = "SELECT desg_name FROM `user_desg` WHERE desg_id = (SELECT designation from  `online_mrf` WHERE login_id = '".$loginID."' AND mid = '".$mid."')";
			//exit;
			$desgRes = $this->db->query($desgSql);
			$desgInfo = $desgRes->result_array();
			
			
			$message =$messageEmp ='';
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
                <img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.base_url().'assets/images/logo.gif" />
            </div>';
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			$site_base_url_hr=base_url('hr/online_mrf_detail');
			$message_hr=<<<EOD
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
                 <p>Please find the required position in our organisation. </p>                
                 <p>{$empInfo[0]['full_name']} raised a MRF for position of  {$desgInfo[0]['desg_name']}. </p>  
                 <p><a href="{$site_base_url_hr}" style="text-decoration:none">Click here to Check</a><br /><br /></p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
			$to_hr ="hr@polosoftech.com, lalit.tyagi@polosoftech.com";                       
			$subject = "POLOSOFT New online MRF Approved BY Department Head";      
			//echo $message;
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Online MRF POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n"; 			
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();

			$headers .= 'Cc: lalit.tyagi@polosoftech.com\r\n';
			mail($to_hr, $subject, $message_hr, $headers); 
			
			
			
		}
		
		$this->mViewData['department'] = $this->Hr_model->get_department();
		$loginID = $this->session->userdata('user_id');
		
		$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation  WHERE om.login_id = '".$loginID."' ORDER BY om.mid DESC";
		$resMRF = $this->db->query($mysql_qry);
		$this->mViewData['rowMRF'] = $resMRF->result_array(); 
		//Template view
		$this->render('hr_help_desk/online_mrf_detail_all_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/online_mrf_all_script', $this->mViewData); 
	}
	public function get_online_mrf_detail_all()
	{
		$loginID = $this->session->userdata('user_id');
		$cond = " i.login_id != '10010'";
		$check_isDepartmentHead = $this->login_model->check_isDepartmentHead($loginID); 
		if(count($check_isDepartmentHead)>0){
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation WHERE $cond ORDER BY om.mid DESC";
		}
		else{
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation  WHERE $cond AND om.login_id = '".$loginID."' ORDER BY om.mid DESC";
		}
		$resMRF = $this->db->query($mysql_qry);
		echo json_encode($resMRF->result_array()); 
	}
	public function online_mrf_detail_all_search()
	{
		$this->mViewData['pageTitle']    = 'online mrf details all';
		
		$loginID = $this->session->userdata('user_id');
		$searchDepartment = $this->input->post('searchDepartment');
		$searchName = $this->input->post('searchName');
		$searchDesignation = $this->input->post('searchDesignation');
		$searchEmpCode = $this->input->post('searchEmpCode');
		//$searchYear = $this->input->post('searchYear');
		$cond = " i.login_id != '10010'";
		if($searchDepartment != '') {
			$cond .= " AND om.department = '".$searchDepartment."' ";
		}
		if($searchDesignation != ''){
			$cond .= " AND om.designation = '".$designation."' ";
		} 
		 if($searchName <> "") {
			 $cond .= " AND  (i.name_first like '%".$searchName."%') ";
		 } 
		 if($searchEmpCode <> "") {
			 $cond .= " AND  i.loginhandle = '".$searchEmpCode."' ";
		 } 
		 
		$check_isDepartmentHead = $this->login_model->check_isDepartmentHead($loginID); 
		if(count($check_isDepartmentHead)>0){
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation WHERE $cond ORDER BY om.mid DESC";
		}
		else{
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation  WHERE om.login_id = '".$loginID."' ORDER BY om.mid DESC";
		}
		 
		 
		 
		$check_isDepartmentHead = $this->login_model->check_isDepartmentHead($loginID); 
		if(count($check_isDepartmentHead)>0){
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation WHERE $cond ORDER BY om.mid DESC";
		}
		else{
			$mysql_qry = "SELECT om.*, dp.login_id as dept_login_id, i.full_name, i.loginhandle, d.desg_name, dp.dept_name FROM `online_mrf` om LEFT JOIN `department` dp ON dp.login_id = om.login_id LEFT JOIN `internal_user` i ON i.login_id = om.login_id LEFT JOIN `user_desg` d ON d.desg_id = om.designation  WHERE om.login_id = '".$loginID."' AND $cond ORDER BY om.mid DESC ";
		}
		//echo $mysql_qry;exit;
		$resMRF = $this->db->query($mysql_qry);
		echo json_encode($resMRF->result_array()); 
		 
	}
	
	public function interview_candidate()
	{
		$this->mViewData['pageTitle']    = 'Interview Candidate';
		$loginID = $this->session->userdata('user_id');
		
		$bet ='';
		$cond = "WHERE interview_sch='1'";
		$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' $bet $cond"; 
		$empRES = $this->db->query($empSQL);
		$this->mViewData['rowMRF'] = $empRES->result_array();
		
		$jobQry = "SELECT * FROM `ap_posts` WHERE post_type='job' and post_status='publish'"; 
		$jobRes = $this->db->query($jobQry);
		$this->mViewData['jobRow'] = $jobRes->result_array();
		
		if($this->input->post('intervE') == "interVSchd"){
			$rm_rating = $this->input->post('rm_rating');
			$rm_desc = $this->input->post('rm_desc');
			$appID = $this->input->post('appID');
			$shtSql = "UPDATE ap_app_user_info SET  rm_rating='".$rm_rating."', rm_desc='".$rm_desc."' WHERE id = '".$appID."'";
			$shtRes = $this->db->query($shtSql);
		}
		
		//Template view
		$this->render('hr_help_desk/interview_candidate_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/js/interview_rating_js', $this->mViewData);
	}
	
	public function interview_candidate_rating()
	{
		$loginID = $this->session->userdata('user_id');
		$rm_rating = $this->input->post('rm_rating');
		$rm_desc = $this->input->post('rm_desc');
		$appID = $this->input->post('appID');
		$shtSql = "UPDATE ap_app_user_info SET  rm_rating='".$rm_rating."', rm_desc='".$rm_desc."' WHERE id = '".$appID."'";
		$shtRes = $this->db->query($shtSql);
	}
	
	public function interview_candidate_search()
	{
		$loginID = $this->session->userdata('user_id');
		$searchResumeType = $this->input->post('searchResumeType');
		$searchJobCode = $this->input->post('searchJobCode');
		$searchStartDate = $this->input->post('searchStartDate');
		$searchEndDate = $this->input->post('searchEndDate');
		
		$bet ='';
		$cond = " interview_sch='1'";
		
		if($searchStartDate !='' && $searchEndDate !='')
		{   
			$txtSdate = date('Y-m-d',strtotime($searchStartDate));
			$txtEdate = date('Y-m-d',strtotime($searchEndDate));   
			$cond = $cond." AND DATE_FORMAT(`request_date`, '%Y-%m-%d') BETWEEN '$txtSdate' AND '$txtEdate' ";
		}
		
		if($searchJobCode != '' && $bet != '') {
			$cond = $cond." AND jj.id = '".$searchJobCode."'";
		}
		
		if($searchResumeType =='applicants'){
			$empSQL = "SELECT ja.*,jj.*,ja.id as appid FROM ap_app_user_info ja INNER JOIN ap_posts as jj ON jj.id=ja.job_id and post_type='job' WHERE $cond"; 
		}
		else{
			$empSQL = "SELECT ja.*,ja.id as appid FROM ap_app_user_info ja  WHERE $cond "; 
		}
		$empRES = $this->db->query($empSQL);
		echo json_encode($empRES->result_array()); 
		
	}
	public function online_room_booking()
	{
		$this->mViewData['pageTitle']    = 'Online room booking';
		$loginID = $this->session->userdata('user_id');
		if($this->input->post('btnAddMessage') == "APPLY")
		{
			$book_date = $this->input->post('book_date');
			$book_time = $this->input->post('book_time');
			$room_name = $this->input->post('room_name');
			$res = $this->Hr_model->get_online_room_booking($book_date,$book_time);
			$resMessage = count($res);
			if($resMessage>0)
			{	
				$this->Hr_model->update_online_room_booking($book_date,$book_time,$room_name);
			}
			else
			{
				$this->Hr_model->insert_online_room_booking($book_date,$book_time,$room_name);
			} 

			//SEND EMAIL
			
			$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
			$repRes = $this->db->query($repSql);
			$repInfo = $repRes->result_array();
			
			$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
		
			$logo_URL = base_url('assets/images/logo.gif');
			$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
			<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
			</div>';
				
			$footer = '<a href="http://www.facebook.com/polostechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
            <a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
            <div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
                &copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
            </div>';
			
			$subject = 'Online Room Booking - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			
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
                 <p>{$empInfo[0]['full_name']} has requested for Room Booking. </p>  
				 <p>{$room_name} room booked for date - <strong>{$book_date}</strong> on Time <strong>{$book_time}</strong>.</p>
                 <p> In case of any Query, Please contact to HR Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

			$to =$repInfo[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			//echo $message; exit();
			mail($to, $subject, $message, $headers);
		}
		//Template view
		$this->render('hr_help_desk/online_room_booking_view', 'full_width', $this->mViewData); 
		$this->load->view('script/hr_help_desk/js/datepicker', $this->mViewData);
	}
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
	
	public function employee_reimbursement(){
		$this->mViewData['pageTitle']    = 'Reimbursment';
		
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$login_ID = $_GET['id'];
		} else {
			$login_ID = "";
		}
		
		if ($login_ID  != "") {
			$this->mViewData['access'] = '';
		} else {
			$this->mViewData['access'] = 'user';
		}	
		
		$success = "";
		$error = "";
		
		if($this->input->post('btnAddMessage') == "SUBMIT"){
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
			$txtMobileOffc = $this->input->post('txtmobile_official');
			$txtMobileldn = $this->input->post('txtmobile_landline');
			$txtFuel = $this->input->post('txtfuel');
			$vchlMain = $this->input->post('txtvehicle_maintenance');
			$txtenrtmnt = $this->input->post('txtentertainment');
			$txtPerio = $this->input->post('txtbook_periodical');
			$txtlta = $this->input->post('txtlta');
			$txtmediclaim = $this->input->post('txtmediclaim');
			$txtlunch = $this->input->post('txtlunch');
			$txtdrvrSlry = $this->input->post('txtdriver_salary');
			if($this->mViewData['access'] != 'user'){
				$resMessage= $this->db->query("SELECT * FROM reimbursements WHERE login_id='".$loginID."' AND reimbursement_year='$year' AND reimbursement_month=".$month);
				$resMes = $resMessage->result_array();
				$resMesCount = COUNT($resMes);
				if($resMesCount > 0){
					$updateSql="UPDATE `reimbursements` SET mobile_official = '".$txtMobileOffc."',
                         mobile_landline = '".$txtMobileldn."',
                         fuel = '".$txtfuel."',vehicle_maintenance = '".$vchlMain."',
                         entertainment = '".$txtenrtmnt."',book_periodical = '".$txtPerio."',
                         lta = '".$txtlta."',mediclaim = '".$txtmediclaim."',
                         lunch = '".$txtlunch."',driver_salary = '".$txtdrvrSlry."',modified_date=now()
                         WHERE login_id='".$loginID."' AND reimbursement_year='".$year."' AND reimbursement_month='".$month."'";
						 $this->db->query($updateSql);
						  $success = "Amount is Updated Successfully";
				} else {
					$insertSql ="INSERT INTO `reimbursements` SET mobile_official = '".$txtMobileOffc."',
                         login_id='".$loginID."',mobile_landline = '".$txtMobileldn."',
                         fuel = '".$txtFuel."',vehicle_maintenance = '".$vchlMain."',
                         entertainment = '".$txtenrtmnt."',book_periodical = '".$txtPerio."',
                         lta = '".$txtlta."',mediclaim = '".$txtPerio."',
                         lunch = '".$txtlunch."',driver_salary = '".$txtdrvrSlry."',reimbursement_year='".$year."', reimbursement_month='".$month."', created_date=now()";
						  $this->db->query($insertSql);
						    $success = "Amount is Inserted Successfully";
				}
			}
			if($this->mViewData['access'] == 'user'){
				$reimbursement=array("doc_mobile_official","doc_mobile_landline","doc_fuel","doc_vehicle_maintenance","doc_entertainment","doc_book_periodical","doc_lta","doc_mediclaim","doc_lunch","doc_driver_salary");
				for($j=0;$j<10;$j++){
					if($_FILES['rdoc_'.$j]['name'] != '' && $_FILES['rdoc_'.$j]['size'] > 0 ){
						$path = $_FILES['rdoc_'.$j]['name'];
						$fileName = strtolower(str_replace(' ','',$loginID."_rdoc_".$j."_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION)));
						$fileName = strtolower(str_replace('/','_',$fileName));
						$config['upload_path'] = APPPATH.'../assets/upload/reimbursement/';
						$config['allowed_types'] = 'pdf';
						$config['file_name'] = $fileName;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if($this->upload->do_upload('rdoc_'.$j)){
							$fileData = $this->upload->data();
							$sqlRep = $this->db->query("SELECT * FROM reimbursements_doc WHERE login_id='".$loginID."' AND reimbursement_year='".$year."' AND reimbursement_month=".$month);
							$sqlRepRes = $sqlRep->result_array();
							$sqlRepCOUNT = COUNT($sqlRepRes);
							if($sqlRepCOUNT > 0){
								$updateIUESql ="UPDATE `reimbursements_doc` SET ".$reimbursement[$j]."='".$fileName."'  WHERE login_id='".$loginID."'";
								$updateIUESqlRes = $this->db->query($updateIUESql);
								$success = "File is Updated Successfully";
							} else {
								$insertIUESql = "INSERT INTO `reimbursements_doc` (login_id, ".$reimbursement[$j].", reimbursement_year, reimbursement_month ) VALUES('".$loginID."','".$fileName."','".$year."','".$month."')";
								$insertIUESqlRes = $this->db->query($insertIUESql);
								$success = "File is Inserted Successfully";
							}
						}
					} else {
						$error = "Please update files";
					}
				}
			}
		}
		
		if($this->input->post('btnFind') == "FIND"){
			$this->mViewData['month'] = $this->input->post('selMonth');
			$this->mViewData['year'] = $this->input->post('selYear');
		} else {
			$this->mViewData['month'] = date('m');
			$this->mViewData['year'] = date('Y');
		}
		
		$empSql = $this->db->query("SELECT i.full_name,i.user_status,i.FnF_status, s.*, r.* FROM `internal_user` AS i LEFT JOIN `reimbursements` AS s ON s.login_id = i.login_id LEFT JOIN `reimbursements_doc` AS r ON r.login_id = s.login_id AND r.reimbursement_year=s.reimbursement_year AND r.reimbursement_month=s.reimbursement_month WHERE i.login_id = '".$loginID."' AND s.reimbursement_year='".$this->mViewData['year']."' AND s.reimbursement_month='".$this->mViewData['month']."'");
		$empInfo = array();
		if($empSql !== FALSE && $empSql->num_rows() > 0){
			$empInfo = $empSql->result_array();
		}

		$this->mViewData['empInfo'] = $empInfo;
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;

		$this->render('hr_help_desk/apply_reimbursement_view', 'full_width', $this->mViewData); 
	}
	
	public function income_tax_declaration(){
		$this->mViewData['pageTitle']    = 'Income Tax Declaration';
		$loginID = $this->session->userdata('user_id');
		if (isset($_GET['id'])) {
			$login_ID = $_GET['id'];
		} else {
			$login_ID = "";
		}
			
		if ($login_ID  != "") {
			$this->mViewData['access'] = '';
		} else {
			$this->mViewData['access'] = 'user';
		}	
		
		$success = "";
		$error = "";
		
		if($this->input->post('btnAddMessage') == "SUBMIT"){
			$txtconv_allowance = $this->input->post('txtconv_allowance');
			$txtchildreneducationalallowance = $this->input->post('txtchildreneducationalallowance');
			$txtmedicalexpensesperannum = $this->input->post('txtmedicalexpensesperannum');
			$txtpensionfund = $this->input->post('txtpensionfund');
			$txtlic = $this->input->post('txtlic');
			$txtprovidentfund = $this->input->post('txtprovidentfund');
			$txtnsc = $this->input->post('txtnsc');
			$txtchildreneducationfee = $this->input->post('txtchildreneducationfee');
			$txtmutualfund = $this->input->post('txtmutualfund');
			$txtnischint = $this->input->post('txtnischint');
			$txtrentpaid = $this->input->post('txtrentpaid');
			$txtulip = $this->input->post('txtulip');
			$txtpostofficetax = $this->input->post('txtpostofficetax');
			$txtelss = $this->input->post('txtelss');
			$txthousingloanprincipal = $this->input->post('txthousingloanprincipal');
			$txtfixeddeposit = $this->input->post('txtfixeddeposit');
			$txtselfsfamily = $this->input->post('txtselfsfamily');
			$txtparents = $this->input->post('txtparents');
			$txthighereducation = $this->input->post('txthighereducation');
			$txthousingloaninterest = $this->input->post('txthousingloaninterest');
			$txtddnormaldisability = $this->input->post('txtddnormaldisability');
			$ddseveredisability = $this->input->post('ddseveredisability');
			$txtddseveredisability = $this->input->post('txtddseveredisability');
			$txtunormaldisability = $this->input->post('txtunormaldisability');
			$txtuseveredisability = $this->input->post('txtuseveredisability');
			$txtspecifieddiseases = $this->input->post('txtspecifieddiseases');
			$txtsavingsaccountinterest = $this->input->post('txtsavingsaccountinterest');
			$txtdonation = $this->input->post('txtdonation');
			$txtleavetravelconcession = $this->input->post('txtleavetravelconcession');
			$fyear = $this->input->post('fyear');
			if($this->mViewData['access'] != 'user'){ 
				$resMessage=$this->db->query("SELECT * FROM income_tax WHERE login_id='".$loginID."' AND fyear = '".$fyear."'");
				$resMsgRes = $resMessage->result_array();
				if(COUNT($resMsgRes) > 0){
					$updateSql="UPDATE `income_tax` SET conv_allowance = '".$txtconv_allowance."',
                         childreneducationalallowance = '".$txtchildreneducationalallowance."',
                         medicalexpensesperannum = '".$txtmedicalexpensesperannum."',pensionfund = '".$txtpensionfund."',
                         lic = '".$txtlic."',providentfund = '".$txtprovidentfund."',
                         nsc = '".$txtnsc."',childreneducationfee = '".$txtchildreneducationfee."',
                         mutualfund = '".$txtmutualfund."',nischint = '".$txtnischint."',
                         rentpaid= '".$txtrentpaid."', ulip = '".$txtulip."',postofficetax = '".$txtpostofficetax."',
                         elss = '".$txtelss."',housingloanprincipal = '".$txthousingloanprincipal."',
                         fixeddeposit = '".$txtfixeddeposit."',selfsfamily = '".$txtselfsfamily."', 
                         parents = '".$txtparents."',highereducation = '".$txthighereducation."',  
                         housingloaninterest = '".$txthousingloaninterest."',ddnormaldisability = '".$txtddnormaldisability."',  
                         ddseveredisability = '".$txtddseveredisability."',unormaldisability = '".$txtunormaldisability."',
                         useveredisability = '".$txtuseveredisability."',specifieddiseases = '".$txtspecifieddiseases."',
                         savingsaccountinterest = '".$txtsavingsaccountinterest."',donation = '".$txtdonation."',
                         leavetravelconcession = '".$txtleavetravelconcession."',fyear = '".$fyear."',modified_date=now()
                         WHERE login_id='".$loginID."'";
						 $updateSqlRes = $this->db->query($updateSql);
						  $success = "Income Tax is Updated Successfully";
				} else {
					 $insertSql ="INSERT INTO `income_tax` SET login_id='".$loginID."',conv_allowance = '".$txtconv_allowance."',
                         childreneducationalallowance = '".$txtchildreneducationalallowance."',
                         medicalexpensesperannum = '".$txtmedicalexpensesperannum."',pensionfund = '".$txtpensionfund."',
                         lic = '".$txtlic."',providentfund = '".$txtprovidentfund."',
                         nsc = '".$txtnsc."',childreneducationfee = '".$txtchildreneducationfee."',
                         mutualfund = '".$txtmutualfund."',nischint = '".$txtnischint."',
                         rentpaid= '".$txtrentpaid."',ulip = '".$txtulip."',postofficetax = '".$txtpostofficetax."',
                         elss = '".$txtelss."',housingloanprincipal = '".$txthousingloanprincipal."',
                         fixeddeposit = '".$txtfixeddeposit."',selfsfamily = '".$txtselfsfamily."', 
                         parents = '".$txtparents."',highereducation = '".$txthighereducation."',  
                         housingloaninterest = '".$txthousingloaninterest."',ddnormaldisability = '".$txtddnormaldisability."',  
                         ddseveredisability = '".$txtddseveredisability."',unormaldisability = '".$txtunormaldisability."',
                         useveredisability = '".$txtuseveredisability."',specifieddiseases = '".$txtspecifieddiseases."',
                         savingsaccountinterest = '".$txtsavingsaccountinterest."',donation = '".$txtdonation."',
                         leavetravelconcession = '".$txtleavetravelconcession."',fyear = '".$fyear."',created_date=now()";
						 $insertSqlRes = $this->db->query($insertSql);
						 $success = "Income Tax is Inserted Successfully";
				}
			} else if($this->mViewData['access'] == 'user'){
				$reimbursement=array("doc_rentpaid","doc_conv_allowance",
								"doc_childreneducationalallowance",
								"doc_medicalexpensesperannum",                                
                                "doc_pensionfund",
                                "doc_lic",
                                "doc_providentfund",
                                "doc_nsc",
                                "doc_childreneducationfee",
                                "doc_mutualfund",
                                "doc_nischint",
                                "doc_ulip",
                                "doc_postofficetax",
                                "doc_elss",
                                "doc_housingloanprincipal",
                                "doc_fixeddeposit",
                                "doc_selfsfamily",
                                "doc_parents",
                                "doc_highereducation",
                                "doc_housingloaninterest",
                                "doc_ddnormaldisability",
                                "doc_ddseveredisability",
                                "doc_unormaldisability",
                                "doc_useveredisability",
                                "doc_specifieddiseases",
                                "doc_savingsaccountinterest",
                                "doc_leavetravelconcession",
                                "doc_donation");
				for($j=0;$j<10;$j++){
					 if($_FILES['rdoc_'.$j]['name'] != '' && $_FILES['rdoc_'.$j]['size'] > 0 ){
						  $path = $_FILES['rdoc_'.$j]['name'];
						  $fileName = strtolower(str_replace(' ','',$loginID."_itdoc_".$j."_".date("YmdHis") .".".pathinfo($path, PATHINFO_EXTENSION)));
						  $fileName = strtolower(str_replace('/','_',$fileName));
						  $config['upload_path'] = APPPATH.'../assets/upload/incometax/';
						  $config['allowed_types'] = 'pdf';
						  $config['file_name'] = $fileName;
						  $this->load->library('upload', $config);
						  $this->upload->initialize($config);
							 if($this->upload->do_upload('rdoc_'.$j)){
								   $docMessage=$this->db->query("SELECT * FROM income_tax_doc WHERE login_id='".$loginID."' AND fyear = '".$fyear."'");
								   $docMessageRes = $docMessage->result_array();
								   if(COUNT($docMessageRes) > 0){
									   $updateIUESql = "UPDATE `income_tax_doc` SET ".$reimbursement[$j]."='".$fileName."'  WHERE login_id='".$loginID."' AND fyear = '".$fyear."'";
									   $updateIUESql = $this->db->query($updateIUESql);
									   $success = "Files are updated Successfully";
								   } else {
									    $insertIUESql = "INSERT INTO `income_tax_doc` SET login_id='".$loginID."', ".$reimbursement[$j]."='".$fileName."',fyear = '".$fyear."'";
										$insertIUESqlRes = $this->db->query($insertIUESql);
										 $success = "Files are inserted Successfully";
								   }
							 } else {
								 $error = "File is Not Uploaded";
							 }
					 }  else {
						 $error = "Please upload files";
					 }
				}
			}			
		}
		
		if($this->input->post('btnFind') == "FIND"){
			$financialyear = $this->input->post('fyear');
		} else {
			$financialyear = date('Y');
		}
		$empSqlq = "SELECT i.full_name,i.user_status,i.FnF_status, s.*, r.* FROM `internal_user` AS i LEFT JOIN `income_tax` AS s ON s.login_id = i.login_id LEFT JOIN `income_tax_doc` AS r ON r.login_id = i.login_id AND s.fyear = r.fyear WHERE i.login_id = ".$loginID." AND s.fyear=".$financialyear;
		
		$empSqlR = $this->db->query($empSqlq);
		$empInfo = $empSqlR->result_array();
		$this->mViewData['empInfo'] = $empInfo;
		$this->mViewData['fyear'] = $financialyear;
		
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		
		$this->render('hr_help_desk/income_tax_declaration_view', 'full_width', $this->mViewData); 
	}
	
	
}
