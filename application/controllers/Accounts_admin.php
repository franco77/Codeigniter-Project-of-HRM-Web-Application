<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_admin extends MY_Controller 
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
		
		$this->load->model('Accounts_model');
		$this->load->model('Hr_model');
		$this->load->model('event_model');
		$this->load->model('Myprofile_model');
		$this->load->model('Loan_model');
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
	 
	public function estimated_tax_declaration()
	{
		$this->mViewData['pageTitle'] = 'All Estimated Tax Declaration';
		//Template view
		$this->render('accounts/estimated_tax_declaration_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/estimated_tax_declaration_script');		
	}
	/*Start Ajax with angularjs function*/
	public function get_all_estimated_declaration()
	{
		$result = $this->Accounts_model->get_all_estimated_declaration(); 
		echo json_encode($result); 
	}
	public function get_all_estimated_declaration_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_all_estimated_declaration_fy($fyear); 
		echo json_encode($result); 
	}
	/*End */
	 
	public function final_tax_declaration()
	{
		$this->mViewData['pageTitle'] = 'All Estimated Tax Declaration';
		
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		//echo $fyear;
		$this->mViewData['dd_year'] = $fyear;
		//Template view
		$this->render('accounts/final_tax_declaration_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/final_tax_declaration_script');		
	}
	/*Start Ajax with angularjs function*/
	public function get_all_final_declaration()
	{
		$result = $this->Accounts_model->get_all_final_declaration(); 
		echo json_encode($result); 
	}
	public function get_all_final_declaration_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_all_final_declaration_fy($fyear); 
		echo json_encode($result); 
	}
	/*End */
	
	public function estimated_declaration_form()
	{
		$this->mViewData['pageTitle'] = 'Estimated Declaration Form';
		//load form helper and validation library
		$loginID = $this->input->get('lid');
		$tid = $this->input->get('id');
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empDetails'] = $empInfo;
	
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosof-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageReview') == "APPROVE" )
		{  
			$rent_paid_by_employee  					= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 			= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 							= $this->input->post('conv_allowance');
			$eligible_conv_allowance 					= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 				= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 		= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 					= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 			= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 						= $this->input->post('');
			$eligible_leavetravelconcession 			= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 					= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 			= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 							= $this->input->post('professional_tax');
			$eligible_professional_tax 					= $this->input->post('eligible_professional_tax');
			$self_occupied_property 					= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 			= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 					= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 			= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 				= $this->input->post('contribution_provident_fund');
			$lic 										= $this->input->post('lic');
			$public_provident_fund 						= $this->input->post('public_provident_fund');
			$nsc 										= $this->input->post('nsc');
			$childreneducationfee 						= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 							= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 		= $this->input->post('contribution_notified_pension_fund');
			$ulip 										= $this->input->post('ulip');
			$postofficetax 								= $this->input->post('postofficetax');
			$elss 										= $this->input->post('elss');
			$housingloanprincipal 						= $this->input->post('housingloanprincipal');
			$fixeddeposit 								= $this->input->post('fixeddeposit');
			$any_other_tax 								= $this->input->post('any_other_tax');
			$total_deduction80c 						= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 				= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD						= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 			= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 			= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 	= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 				= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 		= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 			= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 	= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 		= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 							= $this->input->post('normal_disability');
			$eligible_normal_disability 				= $this->input->post('eligible_normal_disability');
			$severe_disability 							= $this->input->post('severe_disability');
			$eligible_severe_disability 				= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 						= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 				= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 				= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 	= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 							= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 				= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 					= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 			= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 						= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 			= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 		= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e = $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 					= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 			= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 						= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 				= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 		= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 		= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 					= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 				= $this->input->post('eligible_deduction_under_80U');
			//$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			//$result = $this->Accounts_model->get_income_tax_declaration_estimation();
			
			if($tid !="")
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear,$tid,$loginID); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();

			$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
			$revRes = $this->db->query($revSql);
			$revInfo = $revRes->result_array();
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Estimation form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
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
                  <p>Your Income Tax Estimation form has been submitted successfully.</p> 
				  <p>Please keep it handing during the review with account's manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Account's Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

			//$toRM =$repInfo[0]['email'];
			$to =$empInfo[0]['email'];
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			//mail($toRM, $subject, $messageRM, $headersRM);
			mail($to, $subject, $message, $headers);
			redirect('/accounts_admin/estimated_tax_declaration');
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageReview') == "Reject" )
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$loginID."' AND tid='".$tid."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
             <p>Dear {$repMgrInfo[0]},</p>                                 
             <p>Your Income Tax Declaration(Estimation) form has been rejected by aacount's authority. </p>                                             
             <p><a href="{$site_base_url}/script/estimated_declaration_form.php" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
             <p> In case of any Query, Please contact to accounts department.</p>                                 
             <p>{$footer}</p>
             </div> 
          </div> 
        </div>  
        </div>
    </body>
    </html>
EOD;
			$to =$empInfo[0]['email']; 
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headersRM);
			redirect('/accounts_admin/estimated_tax_declaration');
		}
		//END REJECT
		
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); 
		$empInfo = $this->Accounts_model->get_income_tax_declaration_estimation_emp($tid,$loginID);
		$this->mViewData['empInfo'] = $empInfo;
		//print_r($this->mViewData['empInfo']);
		$fyear = $empInfo[0]['fyear'];
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$loginID."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		//var_dump($repMgrInfo);
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		$this->render('accounts/estimated_declaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);		
		$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	public function estimated_computation_form()
	{
		$this->mViewData['pageTitle'] = 'Estimated Declaration Form';
		//load form helper and validation library
		$loginID = $this->input->get('lid');
		$tid = $this->input->get('id');
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empDetails'] = $empInfo;
	
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageReview') == "APPROVE" )
		{  
			$rent_paid_by_employee  					= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 			= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 							= $this->input->post('conv_allowance');
			$eligible_conv_allowance 					= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 				= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 		= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 					= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 			= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 						= $this->input->post('');
			$eligible_leavetravelconcession 			= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 					= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 			= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 							= $this->input->post('professional_tax');
			$eligible_professional_tax 					= $this->input->post('eligible_professional_tax');
			$self_occupied_property 					= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 			= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 					= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 			= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 				= $this->input->post('contribution_provident_fund');
			$lic 										= $this->input->post('lic');
			$public_provident_fund 						= $this->input->post('public_provident_fund');
			$nsc 										= $this->input->post('nsc');
			$childreneducationfee 						= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 							= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 		= $this->input->post('contribution_notified_pension_fund');
			$ulip 										= $this->input->post('ulip');
			$postofficetax 								= $this->input->post('postofficetax');
			$elss 										= $this->input->post('elss');
			$housingloanprincipal 						= $this->input->post('housingloanprincipal');
			$fixeddeposit 								= $this->input->post('fixeddeposit');
			$any_other_tax 								= $this->input->post('any_other_tax');
			$total_deduction80c 						= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 				= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD						= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 			= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 			= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 	= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 				= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 		= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 			= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 	= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 		= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 							= $this->input->post('normal_disability');
			$eligible_normal_disability 				= $this->input->post('eligible_normal_disability');
			$severe_disability 							= $this->input->post('severe_disability');
			$eligible_severe_disability 				= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 						= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 				= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 				= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 	= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 							= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 				= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 					= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 			= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 						= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 			= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 		= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e = $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 					= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 			= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 						= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 				= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 		= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 		= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 					= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 				= $this->input->post('eligible_deduction_under_80U');
			//$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			//$result = $this->Accounts_model->get_income_tax_declaration_estimation();
			
			if($tid !="")
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear,$tid,$loginID); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();

			$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
			$revRes = $this->db->query($revSql);
			$revInfo = $revRes->result_array();
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Estimation form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
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
                  <p>Your Income Tax Estimation form has been submitted successfully.</p> 
				  <p>Please keep it handing during the review with account's manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Account's Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

			//$toRM =$repInfo[0]['email'];
			$to =$empInfo[0]['email'];
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			//mail($toRM, $subject, $messageRM, $headersRM);
			mail($to, $subject, $message, $headers);
			redirect('/accounts_admin/estimated_tax_computation');
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageReview') == "Reject" )
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$loginID."' AND tid='".$tid."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
             <p>Dear {$repMgrInfo[0]},</p>                                 
             <p>Your Income Tax Declaration(Estimation) form has been rejected by aacount's authority. </p>                                             
             <p><a href="{$site_base_url}/script/estimated_declaration_form.php" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
             <p> In case of any Query, Please contact to account;s department.</p>                                 
             <p>{$footer}</p>
             </div> 
          </div> 
        </div>  
        </div>
    </body>
    </html>
EOD;
			$to =$empInfo[0]['email']; 
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headersRM);
			redirect('/accounts_admin/estimated_tax_computation');
		}
		//END REJECT
		
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); 
		$empInfo = $this->Accounts_model->get_income_tax_declaration_estimation_emp($tid,$loginID);
		$this->mViewData['empInfo'] = $empInfo;
		//print_r($this->mViewData['empInfo']);
		$fyear = $empInfo[0]['fyear'];
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$loginID."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		//var_dump($repMgrInfo);
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		$this->render('accounts/estimated_declaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);		
		$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	public function final_computation_form()
	{
		$this->mViewData['pageTitle'] = 'Final Declaration Form';
		//load form helper and validation library
		$loginID = $this->input->get('lid');
		$tid = $this->input->get('id');
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empDetails'] = $empInfo;
	
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageReview') == "APPROVE" )
		{  
			$rent_paid_by_employee  					= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 			= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 							= $this->input->post('conv_allowance');
			$eligible_conv_allowance 					= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 				= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 		= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 					= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 			= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 						= $this->input->post('');
			$eligible_leavetravelconcession 			= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 					= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 			= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 							= $this->input->post('professional_tax');
			$eligible_professional_tax 					= $this->input->post('eligible_professional_tax');
			$self_occupied_property 					= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 			= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 					= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 			= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 				= $this->input->post('contribution_provident_fund');
			$lic 										= $this->input->post('lic');
			$public_provident_fund 						= $this->input->post('public_provident_fund');
			$nsc 										= $this->input->post('nsc');
			$childreneducationfee 						= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 							= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 		= $this->input->post('contribution_notified_pension_fund');
			$ulip 										= $this->input->post('ulip');
			$postofficetax 								= $this->input->post('postofficetax');
			$elss 										= $this->input->post('elss');
			$housingloanprincipal 						= $this->input->post('housingloanprincipal');
			$fixeddeposit 								= $this->input->post('fixeddeposit');
			$any_other_tax 								= $this->input->post('any_other_tax');
			$total_deduction80c 						= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 				= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD						= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 			= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 			= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 	= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 				= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 		= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 			= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 	= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 		= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 							= $this->input->post('normal_disability');
			$eligible_normal_disability 				= $this->input->post('eligible_normal_disability');
			$severe_disability 							= $this->input->post('severe_disability');
			$eligible_severe_disability 				= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 						= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 				= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 				= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 	= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 							= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 				= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 					= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 			= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 						= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 			= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 		= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e = $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 					= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 			= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 						= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 				= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 		= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 		= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 					= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 				= $this->input->post('eligible_deduction_under_80U');
			//$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			//$result = $this->Accounts_model->get_income_tax_declaration_estimation();
			
			if($tid !="")
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear,$tid,$loginID); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();

			$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
			$revRes = $this->db->query($revSql);
			$revInfo = $revRes->result_array();
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Estimation form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
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
                  <p>Your Income Tax Estimation form has been submitted successfully.</p> 
				  <p>Please keep it handing during the review with account's manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Account's Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

			//$toRM =$repInfo[0]['email'];
			$to =$empInfo[0]['email'];
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			//mail($toRM, $subject, $messageRM, $headersRM);
			mail($to, $subject, $message, $headers);
			redirect('/accounts_admin/final_tax_computation');
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageReview') == "Reject" )
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$loginID."' AND tid='".$tid."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
             <p>Dear {$repMgrInfo[0]},</p>                                 
             <p>Your Income Tax Declaration(Estimation) form has been rejected by aacount's authority. </p>                                             
             <p><a href="{$site_base_url}/script/estimated_declaration_form.php" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
             <p> In case of any Query, Please contact to accounts department.</p>                                 
             <p>{$footer}</p>
             </div> 
          </div> 
        </div>  
        </div>
    </body>
    </html>
EOD;
			$to =$empInfo[0]['email']; 
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headersRM);
			redirect('/accounts_admin/final_tax_computation');
		}
		//END REJECT
		
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); 
		$empInfo = $this->Accounts_model->get_income_tax_declaration_final_emp($tid,$loginID);
		$this->mViewData['empInfo'] = $empInfo;
		//print_r($this->mViewData['empInfo']);
		$fyear = $empInfo[0]['fyear'];
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$loginID."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		//var_dump($repMgrInfo);
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		$this->render('accounts/estimated_declaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);		
		$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	public function final_declaration_form()
	{
		$this->mViewData['pageTitle'] = 'Estimated Declaration Form';
		//load form helper and validation library
		$loginID = $this->input->get('lid');
		$tid = $this->input->get('id');
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		$this->mViewData['empDetails'] = $empInfo;
	
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="https://www.linkedin.com/company/polosoft-technologies-pvt-ltd/" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageReview') == "APPROVE" )
		{  
			$rent_paid_by_employee  					= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 			= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 							= $this->input->post('conv_allowance');
			$eligible_conv_allowance 					= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 				= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 		= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 					= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 			= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 						= $this->input->post('');
			$eligible_leavetravelconcession 			= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 					= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 			= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 							= $this->input->post('professional_tax');
			$eligible_professional_tax 					= $this->input->post('eligible_professional_tax');
			$self_occupied_property 					= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 			= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 					= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 			= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 				= $this->input->post('contribution_provident_fund');
			$lic 										= $this->input->post('lic');
			$public_provident_fund 						= $this->input->post('public_provident_fund');
			$nsc 										= $this->input->post('nsc');
			$childreneducationfee 						= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 							= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 		= $this->input->post('contribution_notified_pension_fund');
			$ulip 										= $this->input->post('ulip');
			$postofficetax 								= $this->input->post('postofficetax');
			$elss 										= $this->input->post('elss');
			$housingloanprincipal 						= $this->input->post('housingloanprincipal');
			$fixeddeposit 								= $this->input->post('fixeddeposit');
			$any_other_tax 								= $this->input->post('any_other_tax');
			$total_deduction80c 						= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 				= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD						= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 			= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 			= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 	= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 				= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 		= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 			= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 	= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 		= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 							= $this->input->post('normal_disability');
			$eligible_normal_disability 				= $this->input->post('eligible_normal_disability');
			$severe_disability 							= $this->input->post('severe_disability');
			$eligible_severe_disability 				= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 						= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 				= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 				= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 	= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 							= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 				= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 					= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 			= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 						= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 			= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 		= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e = $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 					= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 			= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 						= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 				= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 		= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 		= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 					= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 				= $this->input->post('eligible_deduction_under_80U');
			//$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			//$result = $this->Accounts_model->get_income_tax_declaration_estimation();
			
			if($tid !="")
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear,$tid,$loginID); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();

			$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
			$revRes = $this->db->query($revSql);
			$revInfo = $revRes->result_array();
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Estimation form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
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
                  <p>Your Income Tax Estimation form has been submitted successfully.</p> 
				  <p>Please keep it handing during the review with account's manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Account's Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

			//$toRM =$repInfo[0]['email'];
			$to =$empInfo[0]['email'];
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			//mail($toRM, $subject, $messageRM, $headersRM);
			mail($to, $subject, $message, $headers);
			redirect('/accounts_admin/final_tax_declaration');
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageReview') == "Reject" )
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$loginID."' AND tid='".$tid."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
             <p>Dear {$repMgrInfo[0]},</p>                                 
             <p>Your Income Tax Declaration(Estimation) form has been rejected by aacount's authority. </p>                                             
             <p><a href="{$site_base_url}/script/estimated_declaration_form.php" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
             <p> In case of any Query, Please contact to account;s department.</p>                                 
             <p>{$footer}</p>
             </div> 
          </div> 
        </div>  
        </div>
    </body>
    </html>
EOD;
			$to =$empInfo[0]['email']; 
			$headersRM  = 'MIME-Version: 1.0' . "\r\n";
			$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
			$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headersRM .= 'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $messageApp, $headersRM);
			redirect('/accounts_admin/final_tax_declaration');
		}
		//END REJECT
		
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); //print_r($this->mViewData['get_pf']);
		$empInfo = $this->Accounts_model->get_income_tax_declaration_final_emp($tid,$loginID);
		$this->mViewData['empInfo'] = $empInfo;
		//print_r($this->mViewData['empInfo']);
		$fyear = $empInfo[0]['fyear'];
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$loginID."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$loginID."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		//var_dump($repMgrInfo);
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		$this->render('accounts/final_declaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/final_tax_calculation', $this->mViewData);		
		$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	
	
	
	public function estimated_tax_computation()
	{
		$this->mViewData['pageTitle'] = 'All Estimated Tax Computation';
		
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		//echo $fyear;
		$this->mViewData['dd_year'] = $fyear;
		//Template view 
		$this->render('accounts/estimated_tax_computation_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/estimated_tax_computation_script');
	}
	
	
	/*Start Ajax with angularjs function*/
	public function get_all_estimated_tax_computation()
	{
		$result = $this->Accounts_model->get_all_estimated_tax_computation(); 
		echo json_encode($result); 
	}
	public function get_all_estimated_tax_computation_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_all_estimated_tax_computation_fy($fyear); 
		echo json_encode($result); 
	}
	public function final_tax_computation()
	{
		$this->mViewData['pageTitle'] = 'All Final Tax Computation';
		//Template view 
		$this->render('accounts/final_tax_computation_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/final_tax_computation_script');
	}
	/*Start Ajax with angularjs function*/
	public function get_all_final_tax_computation()
	{
		$result = $this->Accounts_model->get_all_final_tax_computation(); 
		echo json_encode($result); 
	}
	public function get_all_final_tax_computation_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_all_final_tax_computation_fy($fyear); 
		echo json_encode($result); 
	}
	/*End */
	
	/* START PAYROLL MANAGEMENT */
	
	public function emp_leave_provision()
	{
		$this->mViewData['pageTitle'] = 'All Estimated Tax Computation';
		//Template view 
		$this->render('accounts/emp_leave_provision_view', 'full_width', $this->mViewData); 
		$this->load->view('script/accounts/emp_leave_provision_script');
	}
	
	public function get_leave_provision()
	{
		$result = $this->Accounts_model->get_leave_provision();  
		echo json_encode($result); 
	}
	
	public function get_leave_provision_search()
	{
		$yy = $this->input->post('searchYear');
		$searchName = $this->input->post('searchName');
		$result = $this->Accounts_model->get_leave_provision_search($yy,$searchName);  
		echo json_encode($result); 
	}
	
	public function get_leave_provision_export()
	{
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
	
	
	public function allowance_deduction_list()
	{
		$this->mViewData['pageTitle'] = 'Mutiple Allowance/Deduction';
		//Template view 
		$this->render('accounts/allowance_deduction_list_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/profile_list_script');
	}
	public function allowance_deduction()
	{
		$this->mViewData['pageTitle']    = 'Allowance Deduction';
		$encypt = $this->config->item('masterKey');
		if($_SESSION['user_dept']=='Human Resources' || $_SESSION['user_dept']=='Management' || $_SESSION['user_dept']=='Administrator')
		{
			$title = 'Multiple Allowance/Deduction';
			$Header = 'HR';
			$subheader = 'Payroll Management';
			$nested_link = 'Multiple Allowance/Deduction';
		}
		elseif($_SESSION['user_dept']=='Finance and Accounts')
		{ 
			$title = 'Multiple Allowance/Deduction';
			$Header = 'Accounts';
			$subheader= 'Payroll Management';
			$nested_link = 'Multiple Allowance/Deduction';
		}

		$loginID = $this->input->get('id');  
		$successMsg = FALSE;  
		
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{ 
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');

			$resMessage = $this->db->query("SELECT * FROM `allowance_deduction` WHERE login_id='".$loginID."' AND lyear='$year' AND lmonth='$month'"); 
			$count = count($resMessage);
			if($count > 0)
			{
				$updateSql = $this->db->query("UPDATE `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$this->input->post('txtperformance_bonus')."', '".$encypt."'),
								city_allowance = AES_ENCRYPT('".$this->input->post('txtcity_allowance')."', '".$encypt."'),arrear= AES_ENCRYPT('".$this->input->post('txtarrear')."', '".$encypt."'),
								food_allowance = AES_ENCRYPT('".$this->input->post('txtfood_allowance')."', '".$encypt."'),
								personal_allowance= AES_ENCRYPT('".$this->input->post('txtpersonal_allowance')."', '".$encypt."'),referal_bonus = AES_ENCRYPT('".$this->input->post('txtreferal_bonus')."', '".$encypt."'),
								leave_encashment= AES_ENCRYPT('".$this->input->post('txtleave_encashment')."', '".$encypt."'),
								relocation_allowance= AES_ENCRYPT('".$this->input->post('txtrelocation_allowance')."', '".$encypt."'),
								donation = AES_ENCRYPT('".$this->input->post('txtdonation')."', '".$encypt."'),recovery=AES_ENCRYPT('".$this->input->post('txtrecovery')."', '".$encypt."'),
								income_tax = AES_ENCRYPT('".$this->input->post('txtincometax')."', '".$encypt."'),incentive=AES_ENCRYPT('".$this->input->post('txtincentive')."', '".$encypt."'),
								other_deduction= AES_ENCRYPT('".$this->input->post('txtother_deduction')."', '".$encypt."')                    
						 WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'"); 
			}
			else
			{
			   $insertSql = $this->db->query("INSERT INTO `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$this->input->post('txtperformance_bonus')."', '".$encypt."'),
								city_allowance = AES_ENCRYPT('".$this->input->post('txtcity_allowance')."', '".$encypt."'),arrear= AES_ENCRYPT('".$this->input->post('txtarrear')."', '".$encypt."'),
								food_allowance = AES_ENCRYPT('".$this->input->post('txtfood_allowance')."', '".$encypt."'),
								personal_allowance= AES_ENCRYPT('".$this->input->post('txtpersonal_allowance')."', '".$encypt."'),referal_bonus = AES_ENCRYPT('".$this->input->post('txtreferal_bonus')."', '".$encypt."'),
								leave_encashment= AES_ENCRYPT('".$this->input->post('txtleave_encashment')."', '".$encypt."'),
								relocation_allowance= AES_ENCRYPT('".$this->input->post('txtrelocation_allowance')."', '".$encypt."'),
								donation = AES_ENCRYPT('".$this->input->post('txtdonation')."', '".$encypt."'),recovery=AES_ENCRYPT('".$this->input->post('txtrecovery')."', '".$encypt."'),
								income_tax = AES_ENCRYPT('".$this->input->post('txtincometax')."', '".$encypt."'),incentive=AES_ENCRYPT('".$this->input->post('txtincentive')."', '".$encypt."'),
								other_deduction= AES_ENCRYPT('".$this->input->post('txtother_deduction')."', '".$encypt."'),
								login_id='".$loginID."', lyear='".$year."', lmonth='".$month."'"); 
			}
			$successMsg = TRUE;  
		}
		if($this->input->post('btnFind') == "FIND")
		{  
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
		}
		$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
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
		$this->render('accounts/allowance_deduction_view', 'full_width',$this->mViewData); 
	}
	public function multiple_allowance(){
		$this->mViewData['pageTitle']    = 'Multiple Allowance/Deduction';
		$empid = $this->input->get('empid');
		$this->mViewData['empInfo'] = array();
		if($this->input->post('btnFind') == 'FIND'){
				$encypt = $this->config->item('masterKey');
				$month = $this->input->post('searchmonth');
				$year = $this->input->post('searchYear');
				$empSql = "SELECT i.full_name,i.user_status,i.FnF_status,
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
						   FROM `internal_user` AS i LEFT JOIN `allowance_deduction` AS s ON s.login_id = i.login_id WHERE i.login_id = '$empid' AND lyear='$year' AND lmonth='$month'";
				$empRes = $this->db->query($empSql);
				$this->mViewData['empInfo'] = $empRes->result_array();
				$this->mViewData['month'] = $month;
				$this->mViewData['year'] = $year;
		}		
		$sql = $this->db->query("SELECT full_name,loginhandle FROM `internal_user`  WHERE login_id = '".$empid."'"); 
		$result = $sql->result_array(); 
		$this->mViewData['empDetails'] = $result;
		$this->render('accounts/multiple_allowance_deduction_view', 'full_width',$this->mViewData);
	}
	
	public function addMultiple_allowance(){
		$performance_incentive = $this->input->post('performance_incentive');
		$txtincometax = $this->input->post('txtincometax');
		$txtreferal_bonus = $this->input->post('txtreferal_bonus');
		$txtarrear = $this->input->post('txtarrear');
		$txtfood_allowance = $this->input->post('txtfood_allowance');
		$txtrelocation_allowance = $this->input->post('txtrelocation_allowance');
		$txtleave_encashment = $this->input->post('txtleave_encashment');
		$txtcity_allowance = $this->input->post('txtcity_allowance');
		$txtpersonal_allowance = $this->input->post('txtpersonal_allowance');
		$txtincentive = $this->input->post('txtincentive');
		$txtdonation = $this->input->post('txtdonation');
		$txtother_deduction = $this->input->post('txtother_deduction');
		$txtrecovery = $this->input->post('txtrecovery');
		$loginID = $this->input->post('empid');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$resMessage=$this->db->query("SELECT * FROM `allowance_deduction` WHERE login_id='".$loginID."' AND lyear='$year' AND lmonth='$month'");
		$resMes = $resMessage->result_array();
		$encypt = $this->config->item('masterKey');
		if(COUNT($resMes) == 0){
			$insertSql ="INSERT INTO `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$performance_incentive."','".$encypt."'),city_allowance = AES_ENCRYPT('".$txtcity_allowance."','".$encypt."'),arrear= AES_ENCRYPT('".$txtarrear."','".$encypt."'),food_allowance = AES_ENCRYPT('".$txtfood_allowance."','".$encypt."'), personal_allowance= AES_ENCRYPT('".$txtpersonal_allowance."','".$encypt."'),referal_bonus = AES_ENCRYPT('".$txtreferal_bonus."','".$encypt."'),leave_encashment= AES_ENCRYPT('".$txtleave_encashment."','".$encypt."'),relocation_allowance= AES_ENCRYPT('".$txtrelocation_allowance."','".$encypt."'),donation = AES_ENCRYPT('".$txtdonation."','".$encypt."'),recovery=AES_ENCRYPT('".$txtrecovery."','".$encypt."'),income_tax = AES_ENCRYPT('".$txtincometax."','".$encypt."'),incentive=AES_ENCRYPT('".$txtincentive."','".$encypt."'),other_deduction= AES_ENCRYPT('".$txtother_deduction."','".$encypt."'),login_id='".$loginID."', lyear='".$year."', lmonth='".$month."'";
			$this->db->query($insertSql);
		} else{
			$updateSql="UPDATE `allowance_deduction` SET performance_incentive=AES_ENCRYPT('".$performance_incentive."', '".$encypt."'), city_allowance = AES_ENCRYPT('".$txtcity_allowance."', '".$encypt."'),arrear= AES_ENCRYPT('".$txtarrear."', '".$encypt."'),food_allowance = AES_ENCRYPT('".$txtfood_allowance."', '".$encypt."'),personal_allowance= AES_ENCRYPT('".$txtpersonal_allowance."', '".$encypt."'),referal_bonus = AES_ENCRYPT('".$txtreferal_bonus."', '".$encypt."'),leave_encashment= AES_ENCRYPT('".$txtleave_encashment."', '".$encypt."'),relocation_allowance= AES_ENCRYPT('".$txtrelocation_allowance."', '".$encypt."'),donation = AES_ENCRYPT('".$txtdonation."', '".$encypt."'),recovery=AES_ENCRYPT('".$txtrecovery."', '".$encypt."'),income_tax = AES_ENCRYPT('".$txtincometax."', '".$encypt."'),incentive=AES_ENCRYPT('".$txtincentive."', '".$encypt."'),other_deduction= AES_ENCRYPT('".$txtother_deduction."', '".$encypt."')WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'";
			$this->db->query($updateSql);
		}
	}
	
	/***************   Other income   *********************/
	
	public function other_income_list()
	{
		$this->mViewData['pageTitle'] = 'Mutiple Allowance/Deduction';
		//Template view 
		$this->render('accounts/other_income_list_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/profile_list_script');
	}
	public function other_income(){
		$this->mViewData['pageTitle']    = 'Other Income';
		$empid = $this->input->get('empid');
		$this->mViewData['empInfo'] = array();
		if($this->input->post('btnFind') == 'FIND'){
				//$encypt = $this->config->item('masterKey');
				//$month = $this->input->post('searchmonth');
				
				$year = $this->input->post('searchYear');
				$lyear = $year+1;
				$dateFirst = date("Y-m-d",strtotime($year.'-04-01'));
				$dateLast = date("Y-m-d",strtotime($lyear.'-03-31'));
				$empSql = "SELECT * FROM `other_income` WHERE login_id = '$empid' AND DATE_FORMAT(apply_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$dateLast'";
				$empRes = $this->db->query($empSql);
				$this->mViewData['empInfo'] = $empRes->result_array();
				//$this->mViewData['month'] = $month;
				$this->mViewData['year'] = $year;
		}		
		$sql = $this->db->query("SELECT full_name,loginhandle FROM `internal_user`  WHERE login_id = '".$empid."'"); 
		$result = $sql->result_array(); 
		$this->mViewData['empDetails'] = $result;
		$this->render('accounts/other_income_view', 'full_width',$this->mViewData);
	}
	
	public function addOtherIncome(){
		$project_allowance_date = $this->input->post('project_allowance_date');
		if($project_allowance_date !=""){
			$project_allowance_date = date("Y-m-d",strtotime($project_allowance_date));
		}
		$project_allowance_amount = $this->input->post('project_allowance_amount');
		
		$statutory_bonus_date = $this->input->post('statutory_bonus_date');
		if($statutory_bonus_date !=""){
			$statutory_bonus_date = date("Y-m-d",strtotime($statutory_bonus_date));
		}
		$statutory_bonus_amount = $this->input->post('statutory_bonus_amount');
		
		$performance_bonus_date = $this->input->post('performance_bonus_date');
		if($performance_bonus_date !=""){
			$performance_bonus_date = date("Y-m-d",strtotime($performance_bonus_date));
		}
		$performance_bonus_amount = $this->input->post('performance_bonus_amount');
		
		$incentive_payment_date = $this->input->post('incentive_payment_date');
		if($incentive_payment_date !=""){
			$incentive_payment_date = date("Y-m-d",strtotime($incentive_payment_date));
		}
		$incentive_payment_amount = $this->input->post('incentive_payment_amount');
		
		$leave_incashment_date = $this->input->post('leave_incashment_date');
		if($leave_incashment_date !=""){
			$leave_incashment_date = date("Y-m-d",strtotime($leave_incashment_date));
		}
		$leave_incashment_amount = $this->input->post('leave_incashment_amount');
		
		$other_payment_date = $this->input->post('other_payment_date');
		if($other_payment_date !=""){
			$other_payment_date = date("Y-m-d",strtotime($other_payment_date));
		}
		$other_payment_amount = $this->input->post('other_payment_amount');
		
		$loginID = $this->input->post('empid');
		$year = $this->input->post('year');
		$lyear = $year+1;
		$dateFirst = date("Y-m-d",strtotime($year.'-04-01'));
		$dateLast = date("Y-m-d",strtotime($lyear.'-03-31'));
		$resMessage=$this->db->query("SELECT * FROM `other_income` WHERE login_id = '$loginID' AND DATE_FORMAT(apply_date, '%Y-%m-%d') BETWEEN '$dateFirst' AND '$dateLast'");
		$resMes = $resMessage->result_array();
		if(COUNT($resMes) == 0){
			$insertSql ="INSERT INTO `other_income` SET login_id='".$loginID."', 
			project_allowance_date='".$project_allowance_date."', 
			project_allowance_amount='".$project_allowance_amount."', 
			statutory_bonus_date='".$statutory_bonus_date."', 
			statutory_bonus_amount='".$statutory_bonus_amount."', 
			performance_bonus_date='".$performance_bonus_date."', 
			performance_bonus_amount='".$performance_bonus_amount."', 
			incentive_payment_date='".$incentive_payment_date."', 
			incentive_payment_amount='".$incentive_payment_amount."', 
			leave_incashment_date='".$leave_incashment_date."', 
			leave_incashment_amount='".$leave_incashment_amount."', 
			other_payment_date='".$other_payment_date."', 
			other_payment_amount='".$other_payment_amount."', 
			fyear='".$year."', apply_date='".date('Y-m-d H:i:s')."'";
			$this->db->query($insertSql);
		} else{
			$updateSql="UPDATE `other_income` 
			SET project_allowance_date='".$project_allowance_date."',
			project_allowance_amount='".$project_allowance_amount."', 
			statutory_bonus_date='".$statutory_bonus_date."', 
			statutory_bonus_amount='".$statutory_bonus_amount."', 
			performance_bonus_date='".$performance_bonus_date."', 
			performance_bonus_amount='".$performance_bonus_amount."', 
			incentive_payment_date='".$incentive_payment_date."', 
			incentive_payment_amount='".$incentive_payment_amount."', 
			leave_incashment_date='".$leave_incashment_date."', 
			leave_incashment_amount='".$leave_incashment_amount."', 
			other_payment_date='".$other_payment_date."', 
			other_payment_amount='".$other_payment_amount."' 
			WHERE login_id='".$loginID."' AND id='".$resMes[0]['id']."'";
			$this->db->query($updateSql);
		}
	}
	/***************  END/ Other income   *********************/
	
	
	
	
	public function loan_advance_profile_list()
	{
		$this->mViewData['pageTitle'] = 'All Advance/Loan';
		//Template view 
		$this->render('accounts/loan_advance_profile_list_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/loan_advance_profile_list_script');
	}
	
	public function get_loan_advance_approve_reject()
	{
		$result = $this->Loan_model->get_loan_advance_approve_reject(); 
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
	/*Start Ajax with angularjs function*/
	public function get_active_employee()
	{
		$result = $this->Accounts_model->get_active_employee(); 
		echo json_encode($result); 
	}
	/*End */ 
	public function add_loan_advance()
	{
		$this->mViewData['pageTitle'] = 'Add loan advance';
		$encypt = $this->config->item('masterKey');
		$loginID = $this->input->get('id');
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{
			$loginID = $this->input->get('id');
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
			
			$resMessage = $this->Accounts_model->check_load_advance_of_user($loginID,$year,$month);
			//var_dump($resMessage);
			if(count($resMessage) > 0)
			{	
				$updateSql="UPDATE `loan_advance` SET loan_fig ='".$this->input->post('txtloan')."', advance_fig ='".$this->input->post('txtadvance')."', loan = AES_ENCRYPT('".$this->input->post('txtloan')."', '".$encypt."'), advance = AES_ENCRYPT('".$this->input->post('txtadvance')."', '".$encypt."')   WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'";
				//echo $updateSql;exit;
				$this->db->query($updateSql);
			}
			else
			{
			   $insertSql ="INSERT INTO `loan_advance` SET loan_fig ='".$this->input->post('txtloan')."', advance_fig ='".$this->input->post('txtadvance')."', loan = AES_ENCRYPT('".$this->input->post('txtloan')."', '".$encypt."'), advance = AES_ENCRYPT('".$this->input->post('txtadvance')."', '".$encypt."'),login_id='".$loginID."', lyear='".$year."', lmonth='".$month."'";
			   //echo $insertSql;exit;
				$this->db->query($insertSql);
			}
			//$successMsg = TRUE;  
		}
		if($this->input->post('btnFind') == "FIND")
		{  
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear'); 
		}
		$month = $this->input->post('selMonth');
		$year = $this->input->post('selYear');
		$empSql = "SELECT i.full_name,i.user_status,i.FnF_status, AES_DECRYPT(s.loan, '".$encypt."') AS loan,
							   AES_DECRYPT(s.advance, '".$encypt."') AS advance FROM `internal_user` AS i LEFT JOIN `loan_advance` AS s ON s.login_id = i.login_id WHERE i.login_id = '$loginID' AND lyear='$year' AND lmonth='$month'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->row_array();
		 
		//var_dump($this->mViewData['empInfo']);
		
		//Template view 
		$this->render('accounts/add_loan_advance_view', 'full_width', $this->mViewData); 
	}
	
	public function update_loan_advance_approved_accounts()
    {
		$lid = $this->input->post('lid');
        $result = $this->Accounts_model->update_loan_advance_approved_accounts($lid);
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
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
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
			 <p>Loan/Advance of {$empInfo[0]['full_name']} has approved by ACCOUNTS. </p>  
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
		
	

			$to ='hr@polosoftech.com';
			$toEMP =$empInfo[0]['email'];
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ACCOUNTS Department <hr@polosoftech.com>' . "\r\n";  
			$headers .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers); 
			mail($toEMP, $subject, $message, $headers); 
    }
	
	public function update_loan_advance_rejected_accounts()
    {
		$lid = $this->input->post('lid');
		$reject_reason = $this->input->post('reject_reason');
        $result = $this->Accounts_model->update_loan_advance_rejected_accounts($lid, $reject_reason);
        echo json_encode($result);
    }
	
	function print_loan_adv(){
		$this->mViewData['pageTitle']    = 'Print_load_adv';
		$login_id=$this->input->get('login_id');
		$lid=$this->input->get('lid');
		$encypt = $this->config->item('masterKey');
		 $mysql_qry = "SELECT i.*, d.*,dp.*,l.*,b.*,AES_DECRYPT(s.gross_salary, '".$encypt."') AS gross_salary FROM `internal_user` i LEFT JOIN `salary_info` s ON s.login_id = i.login_id LEFT JOIN `company_branch` b ON b.branch_id = i.branch LEFT JOIN `department` dp ON dp.dept_id = i.department LEFT JOIN `user_desg` d ON d.desg_id = i.designation RIGHT JOIN `loan_advance_apply` l ON l.login_id = i.login_id WHERE i.login_id = '".$login_id."' AND l.lid = '".$lid."'";
		 
		$resEmpinfo = $this->db->query($mysql_qry);
	    $prinfEmpInfo = $resEmpinfo->result_array();
		//print_r($prinfEmpInfo); exit;
		$month="";
		switch($prinfEmpInfo[0]["lmonth"]){
			case "1":$month="January";
					 break;
			case "2":$month="February";
					 break;
			case "3":$month="March";
					 break;
			case "4":$month="April";
					 break;
			case "5":$month="May";
					 break;
			case "6":$month="June";
					 break;
			case "7":$month="July";
					 break;
			case "8":$month="August";
					 break;
			case "9":$month="September";
					 break;
			case "10":$month="October";
					 break;
			case "11":$month="November";
					 break;
			case "12":$month="December";
					 break;
		}
		
		$hrSql = "SELECT user_sign_name from internal_user where login_id=(SELECT login_id FROM `department` WHERE dept_id = '2')";
		$hrRes = $this->db->query($hrSql);
		$hr = $hrRes->result_array();
		
		$acSql = "SELECT user_sign_name from internal_user where login_id=(SELECT login_id FROM `department` WHERE dept_id = '4')";
		$acRes = $this->db->query($acSql);
		$ac = $acRes->result_array();
		
		$dhSql = "SELECT user_sign_name from internal_user where login_id=(SELECT login_id FROM `department` WHERE dept_id = '".$prinfEmpInfo[0]["department"]."')";
		$dhRes = $this->db->query($dhSql);
		$dh = $dhRes->result_array();
		
		$rpSql = "SELECT user_sign_name from internal_user where login_id='".$prinfEmpInfo[0]["reporting_to"]."'";
		$rpRes = $this->db->query($rpSql);
		$rp = $rpRes->result_array();
		
		$this->mViewData['prinfEmpInfo'] = $prinfEmpInfo;
		$this->mViewData['hr'] = $hr;
		$this->mViewData['ac'] = $ac;
		$this->mViewData['dh'] = $dh;
		$this->mViewData['rp'] = $rp;
		$this->mViewData['month'] = $month;
		$this->mViewData['wordssss'] = $this->convert_number_to_words($prinfEmpInfo[0]['amountapplied']);
		
		if($prinfEmpInfo[0]["applyfor"] == 'advance'){
			$this->load->view('accounts/print_adv_view', $this->mViewData);
		}else {
			$this->load->view('accounts/print_loan_view', $this->mViewData);
		}
		
		//$this->load->view('accounts/print_loan_adv_view');
		//$this->render('accounts/print_loan_adv_view', 'full_width');
	}
	
	public function convert_number_to_words($number) {

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
	
	public function loan_advance_add(){
		$this->mViewData['pageTitle'] = 'Loan Advance Add';
		//Template view 
		$this->render('accounts/loan_advance_add_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/profile_list_script');
	}
	
	public function loan_advance_loan(){
		$this->mViewData['pageTitle'] = 'Loan Advance Add';
		$encypt = $this->config->item('masterKey');
		$empInfo = array();
		if($this->input->post('btnFind') == 'FIND'){
			
			$month = $this->input->post('searchmonth');
			$year = $this->input->post('searchYear');
			$loginID = $this->input->get('empid');
			
			$empSql = "SELECT i.full_name,i.user_status,i.FnF_status, AES_DECRYPT(s.loan, '".$encypt."') AS loan,
                       AES_DECRYPT(s.advance, '".$encypt."') AS advance FROM `internal_user` AS i LEFT JOIN `loan_advance` AS s ON s.login_id = i.login_id WHERE i.login_id = '$loginID' AND lyear='$year' AND lmonth='$month'";
			$empRes = $this->db->query($empSql);
			$empInfo = $empRes->result_array();
			$this->mViewData['month'] = $month;
			$this->mViewData['year'] = $year;			
		}
		$this->mViewData['empInfo'] = $empInfo;
		
		$loginID = $this->input->get('empid');
		$sql = $this->db->query("SELECT full_name,loginhandle FROM `internal_user`  WHERE login_id = '".$loginID."'"); 
		$result = $sql->result_array(); 
		$this->mViewData['empDetails'] = $result;
		$this->render('accounts/loan_advance_add_emp_view', 'full_width', $this->mViewData);
	}
	
	public function addLoan_Advance(){
		//print_r($_POST);
		$encypt = $this->config->item('masterKey');
		$txtadvance = $this->input->post('txtadvance');
		$txtloan = $this->input->post('txtloan');
		$loginID = $this->input->post('empid');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		
		$resMe = $this->db->query("SELECT * FROM `loan_advance` WHERE login_id='".$loginID."' AND lyear='$year' AND lmonth='$month'");
		$resMessage = $resMe->result_array();
		if(COUNT($resMessage)>0){
			$updateSql="UPDATE `loan_advance` SET loan_fig ='".$txtloan."', advance_fig ='".$txtadvance."', loan = AES_ENCRYPT('".$txtloan."', '".$encypt."'), advance = AES_ENCRYPT('".$txtadvance."', '".$encypt."') WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'";
			$this->db->query($updateSql);
		} else {
			$insertSql ="INSERT INTO `loan_advance` SET loan_fig ='".$txtloan."', advance_fig ='".$txtadvance."', loan = AES_ENCRYPT('".$txtloan."', '".$encypt."'), advance = AES_ENCRYPT('".$txtadvance."', '".$encypt."'), login_id='".$loginID."', lyear='".$year."', lmonth='".$month."'";
			$this->db->query($insertSql);
		}
	}
	
	
	public function reimbrusement()
	{
		$this->mViewData['pageTitle']    = 'loan';
		$this->render('accounts/reimbrusement_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/expenses_reimbrusement/reimbrusement_script');
	}
	public function add_reimbursement()
	{
		$this->mViewData['pageTitle'] = 'Add Reimbursement';
		$loginID = $this->input->get('id');
		$errmsg = "";
		$success_msg = "";
		if($this->input->post('btnAddMessage') == "SUBMIT")
		{        
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear'); 
			$resMessage = $this->Accounts_model->check_reimbursements($loginID,$year,$month);
			//var_dump($resMessage);
			if(count($resMessage) > 0)
			{	
				$updateSql ="UPDATE `reimbursements` SET mobile_official = '".$this->input->post('txtmobile_official')."',
						 mobile_landline = '".$this->input->post('txtmobile_landline')."',
						 fuel = '".$this->input->post('txtfuel')."',vehicle_maintenance = '".$this->input->post('txtvehicle_maintenance')."',
						 entertainment = '".$this->input->post('txtentertainment')."',book_periodical = '".$this->input->post('txtbook_periodical')."',
						 lta = '".$this->input->post('txtlta')."',mediclaim = '".$this->input->post('txtmediclaim')."',
						 lunch = '".$this->input->post('txtlunch')."',driver_salary = '".$this->input->post('txtdriver_salary')."',modified_date=now()
						 WHERE login_id='".$loginID."' AND reimbursement_year='".$year."' AND reimbursement_month='".$month."'";
						 //echo $updateSql; exit;
				$this->db->query($updateSql);
			}
			else
			{
				if($this->input->post('txtmobile_official') !="" || $this->input->post('txtmobile_landline') !="" || $this->input->post('txtfuel') !="" || $this->input->post('txtvehicle_maintenance') !="" || $this->input->post('txtentertainment') !="" || $this->input->post('txtbook_periodical') !="" || $this->input->post('txtlta') !="" || $this->input->post('txtmediclaim') !="" || $this->input->post('txtlunch') !="" || $this->input->post('txtdriver_salary') !="" ){
				   $insertSql ="INSERT INTO `reimbursements` SET mobile_official = '".$this->input->post('txtmobile_official')."',
							 login_id='".$loginID."',mobile_landline = '".$this->input->post('txtmobile_landline')."',
							 fuel = '".$this->input->post('txtfuel')."',vehicle_maintenance = '".$this->input->post('txtvehicle_maintenance')."',
							 entertainment = '".$this->input->post('txtentertainment')."',book_periodical = '".$this->input->post('txtbook_periodical')."',
							 lta = '".$this->input->post('txtlta')."',mediclaim = '".$this->input->post('txtmediclaim')."',
							 lunch = '".$this->input->post('txtlunch')."',driver_salary = '".$this->input->post('txtdriver_salary')."',reimbursement_year='".$year."', reimbursement_month='".$month."', created_date=now()";
							 //echo $insertSql; exit;
					$this->db->query($insertSql);
				}
				else{
					$errmsg = "Please fill at least one field";
				}
			}
			 
			/* if($access == 'user')
			{
				$reimbursement=array("doc_mobile_official","doc_mobile_landline","doc_fuel","doc_vehicle_maintenance","doc_entertainment","doc_book_periodical","doc_lta","doc_mediclaim","doc_lunch","doc_driver_salary");
				for($j=0;$j<10;$j++)
				{ 
					if($_FILES['rdoc_'.$j]['name'] != '' && $_FILES['rdoc_'.$j]['size'] > 0 )
					{                            
						$fileName = strtolower(str_replace(' ','',$loginID."_rdoc_".$j."_".date("YmdHis") .".". get_file_extension($_FILES['rdoc_'.$j]['name'])));
						$fileName = strtolower(str_replace('/','_',$fileName));
						$target_path = SITE_DOCUMENT_PATH.'/upload/reimbursement/'. $fileName;
						if(move_uploaded_file($_FILES['rdoc_'.$j]['tmp_name'], $target_path))
						{
							$docMessage = $this->db->query("SELECT * FROM reimbursements_doc WHERE login_id='".$loginID."' AND reimbursement_year='$year' AND reimbursement_month='$month'");
							if(count($docMessage)>0)
							{
								$updateIUESql = "UPDATE `reimbursements_doc` SET $reimbursement[$j]='".$fileName."'  WHERE login_id='".$loginID."'"; 
								$this->db->query($updateIUESql);
							}
							else
							{
								$insertIUESql = "INSERT INTO `reimbursements_doc` (login_id, $reimbursement[$j], reimbursement_year, reimbursement_month ) VALUES('$loginID','".$fileName."','".$year."','".$month."')"; 
								$this->db->query($insertIUESql);
							}
						} 
					}
				}
			}  */
			//$successMsg = TRUE;  
		}
		$this->mViewData['error_msg'] = $errmsg;
		$this->mViewData['success_msg'] = $success_msg;
		if($this->input->post('btnFind') == "FIND")
		{  
			$month = $this->input->post('selMonth');
			$year = $this->input->post('selYear');
		}      
		else
		{
			$month = date('m');
			$year = date('Y');
		}
		$month = $this->input->post('selMonth');
		$year = $this->input->post('selYear');
		$empSql = "SELECT i.full_name,i.loginhandle,i.user_status,i.FnF_status, s.*, r.* FROM `internal_user` AS i LEFT JOIN `reimbursements` AS s ON s.login_id = i.login_id LEFT JOIN `reimbursements_doc` AS r ON r.login_id = s.login_id AND r.reimbursement_year=s.reimbursement_year AND r.reimbursement_month=s.reimbursement_month WHERE i.login_id = '$loginID' AND s.reimbursement_year='".$year."' AND s.reimbursement_month='".$month."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->row_array();
		//var_dump($this->mViewData['empInfo']);
		$sql = $this->db->query("SELECT full_name,loginhandle FROM `internal_user`  WHERE login_id = '".$loginID."'"); 
		$result = $sql->result_array();
		$this->mViewData['empDetails'] = $result;
		//Template view 
		$this->render('accounts/add_reimbursement_view', 'full_width', $this->mViewData); 
	}
	public function emp_gratuity()
	{
		$this->mViewData['pageTitle']    = 'Gratuity';
		$this->render('accounts/emp_gratuity_view', 'full_width',$this->mViewData);
		$this->load->view('script/hr/expenses_reimbrusement/emp_gratuity_script');
	}
	public function emp_gratuity_export()
	{
		// Create new PHPExcel object
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
		$this->render('accounts/emp_bonus_view', 'full_width',$this->mViewData);
		$this->load->view('script/accounts/emp_bonus_script');
	}
	
	public function emp_bonus_export()
	{
		// Create new PHPExcel object
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
				$months = 0;
				$nodays= $bonus = $bank_no =0;
				while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2){
					$months++;
					$nodays++;
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
					$bonus=ceil($total_bonus*(8.33/100));
				}
				
				if(substr($empDetailsInfo['sal_account_no'], 0, 1)==0){
					$bank_no ='@'.$empDetailsInfo['bank_no'];
				} else {
					$bank_no =$empDetailsInfo['bank_no']; 
				}
			   
				if($processEmpSummaryArray)
				{ 
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
	/*Start Ajax with angularjs function*/
	public function get_emp_bonus()
	{
		$result = $this->Accounts_model->get_emp_bonus(); 
		echo json_encode($result); 
	}
	/*End */
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
		$regAppRes = $this->db->query($regAppSql);
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
		$this->render('accounts/emp_fnf_view', 'full_width',$this->mViewData); 
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
									->setLastModifiedBy("Rangaballava swain")
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
			$selCols .= ", AES_DECRYPT(sal.gross_salary, '".$encypt."') AS gross_salary";
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
			
			
			$empDetailsRes = $this->db->query($empDetailsQry);
			$empDetailsInfo = $empDetailsRes->result_array();
			//print_r($empDetailsInfo); exit;
			$empDetailsNum = count($empDetailsInfo);
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$ai =$totalWages =$totalEpf =$totalEps =$totalExepf  =0;
				for($i = 0; $i< $empDetailsNum; $i++){
					$processEmpSummaryArray = true;

					$DOJ = date("d-m-Y", strtotime($empDetailsInfo[$i]['join_date']));

					if($empDetailsInfo[$i]['join_date'] != "")
					{
						$month_diff = $this->getDifferenceFromNow($empDetailsInfo[$i]['join_date'], 6);					
					}       
					
					$totalWages = $totalWages+$empDetailsInfo[$i]['earned_basic'];             
					$totalEpf = $totalEpf+$empDetailsInfo[$i]['earned_pf'];
				   
					if($processEmpSummaryArray)
					{
						$empSummary = array();
						array_push($empSummary,$i);
						array_push($empSummary, $empDetailsInfo[$i]['pf_no']);                            
						array_push($empSummary, $empDetailsInfo[$i]['full_name']);                 
						array_push($empSummary, date('d-m-Y',strtotime($empDetailsInfo[$i]['join_date'])));                 
						array_push($empSummary, $empDetailsInfo[$i]['paid_days']);            
						array_push($empSummary, $empDetailsInfo[$i]['gross_salary']);  
						//array_push($empSummary, $empDetailsInfo[$i]['earned_basic']); 
						array_push($empSummary, $totalWages); 
						array_push($empSummary, $totalEpf); 
						//array_push($empSummary, $empDetailsInfo[$i]['earned_pf']); 
						$empSummaryArray[$ai++] = $empSummary;
					}
					  
				}
			}
			
			//print_r($empSummaryArray); exit;

			  
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

			// $k=$i+3;
			// $objPHPExcel->getActiveSheet()->setCellValue('E'.$k,'TOTAL');
			// $objPHPExcel->getActiveSheet()->setCellValue('F'.$k, $totalWages);
			// $objPHPExcel->getActiveSheet()->setCellValue('G'.$k, $totalEpf);
			 

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
		$this->render('accounts/epf_report_view', 'full_width', $this->mViewData); 
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
									->setLastModifiedBy("Rangaballava Swain")
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
			$empDetailsNum = count($empDetailsRes);
			$empAttDetailsResult = $empDetailsRes->result_array();
			//var_dump($empAttDetailsResult);
			//Employee details array
			$empSummaryArray = array();
			if($empDetailsNum >0)
			{
				$i = $ai =$totalWages =$totalEsi =$total_employer_esi   =0;
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
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$m, round($totalWages*(4.75/100)));

			$n=$m+1;
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,'TOTAL');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n, (round($totalWages*(4.75/100))+$totalEsi));

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
		$this->render('accounts/esi_report_view', 'full_width', $this->mViewData);
	}
	
	public function increment_report()
	{
		$this->mViewData['pageTitle']    = 'Increament Report';
		if($this->input->post('incrementExport') == "GENERATE")
		{
		  
			// Create new PHPExcel object
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
		$this->render('accounts/increment_report_view', 'full_width', $this->mViewData); 
	}
	
	
	public function payroll_report(){
		$this->mViewData['pageTitle']    = 'Payroll report';
		$this->mViewData['department'] = $this->Hr_model->get_department();  
		$this->mViewData['designation'] = $this->Hr_model->get_designation_status_one(); 
		$this->mViewData['bank'] = $this->Hr_model->bank();
		//payroll logic 
		
		if($this->input->post('exportEmployee') == "Generate")
		{
			//print_r($_POST); exit;
			$encypt = $this->config->item('masterKey');			
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("Rangaballava Swain")
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
					//$pmt = implode(",",$this->input->post("hdnPaymentMode"));
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
						if(null !== $this->input->post('loginhandle'))
						array_push($empSummary,$empDetailsInfo['loginhandle']);
						if(null !== $this->input->post('full_name'))
						array_push($empSummary, $empDetailsInfo['full_name']);
						if(null !== $this->input->post('father_name'))	
						array_push($empSummary, $empDetailsInfo['father_name']);
					
						array_push($empSummary, $DOB);

						if(null !== $this->input->post('doj'))
						array_push($empSummary,$DOJ);

						if(null !== $this->input->post('dept_name'))
						array_push($empSummary,  $empDetailsInfo['dept_name']);
						if(null !== $this->input->post('desg_name'))
						array_push($empSummary, $empDetailsInfo['desg_name']); 
						array_push($empSummary, $empDetailsInfo['working_days']);
						array_push($empSummary, $empDetailsInfo['weekly_off']);
						array_push($empSummary, $empDetailsInfo['holidays']);
						array_push($empSummary, $empDetailsInfo['paid_days']);
						array_push($empSummary, $empDetailsInfo['absent_days']);
						array_push($empSummary, $empDetailsInfo['arrear_days']);
						if(null !== $this->input->post('ctc'))
						array_push($empSummary, $empDetailsInfo['ctc']);

						if(null !== $this->input->post('reimbursement')){
						array_push($empSummary, $empDetailsInfo['reimbursement']);
						}
						if(null !== $this->input->post('ctc') && null !== $this->input->post('reimbursement'))
						array_push($empSummary, ($empDetailsInfo['reimbursement']+$empDetailsInfo['ctc']));

						if(null !== $this->input->post('gross_salary'))
						array_push($empSummary, $empDetailsInfo['gross']); 
						if(null !== $this->input->post('arrear'))
						array_push($empSummary, $empDetailsInfo['arrear']); 

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
		$this->render('accounts/payroll_report_view','full_width',$this->mViewData);
		$this->load->view('script/accounts/js/hr_report', $this->mViewData);
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
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("Rangaballava Swain")
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
				$empDetailsQry = "SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.dept_head WHERE 1" . $cond;
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
		$this->render('accounts/loan_advance_report_view', 'full_width',$this->mViewData);
		$this->load->view('script/accounts/js/hr_report', $this->mViewData);
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
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("POLOSOFT TECHNOLOGIES Pvt Ltd")
									->setLastModifiedBy("Rangaballava Swain")
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
					$empDetailsQry = $this->db->query("SELECT d.dept_id".$selCols." FROM `department` d LEFT JOIN `internal_user` AS h ON h.login_id = d.dept_head WHERE 1" . $cond);
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
		$this->render('accounts/reimbrusement_report_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/hr_report', $this->mViewData);
	}
	
	/*END PAYROLL MANAGEMNT */
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
	
	
	/**********************   MASTER   **************************/
	
	public function define_pt_slab()
	{
		$this->mViewData['pageTitle'] = 'Define PT Slab';
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fy = date('Y');
		}
		else{
			$fy = $y - 1;
		}
		$this->mViewData['fyear'] = $fy;
		$sel_qry = $this->db->query("SELECT * FROM pt_slab_master WHERE status = 'Y' AND fyear = '".$fy."'"); 
		$this->mViewData['sel_qryinfo'] = $sel_qry->result_array();
		//Template view 
		$this->render('accounts/define_pt_slab_view', 'full_width', $this->mViewData);
	}
	public function get_proffessional_tax_slab_fy()
	{
		$fyear = $this->input->post('searchYear');
		$result = $this->Accounts_model->get_proffessional_tax_slab_fy($fyear); 
		echo json_encode($result);
	}
	
	public function delete_pt_slab(){
		$pt_id = $this->input->post('pt_id');
		$deleteSQL = $this->db->query('DELETE FROM pt_slab_master where pt_id = "'.$pt_id.'"');
		echo '<div class="col-md-12"><div class="alert alert-success" role="alert">PT SLAB is Successfully Deleted</div>';
	}
	
	public function add_edit_ptslab(){
		$fyear = $this->input->post('searchYear');
		$from_range = $this->input->post('from_range');
		$to_range = $this->input->post('to_range');
		$range = $from_range."-".$to_range;
		$controller = $this->input->post('controller');
		$pt_value = $this->input->post('pt_value');
		if($controller == 0){
			$sql = $this->db->query("INSERT INTO  pt_slab_master SET `range` = '".$range."', `pt_value` = '".$pt_value."', `fyear` = '".$fyear."'");
		}
		else 
		{
			$sql = $this->db->query("UPDATE `pt_slab_master` SET `range` = '".$range."' , `pt_value` = '".$pt_value."' WHERE `pt_id` = ".$controller."");
		}
	}
	
	
	public function define_income_tax_slab()
	{
		$this->mViewData['pageTitle'] = 'Define Income Tax Slab';
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fy = date('Y');
		}
		else{
			$fy = $y - 1;
		}
		$this->mViewData['fyear'] = $fy;
		
		$sel_qry = $this->db->query("SELECT m.* FROM it_slab_master AS m WHERE m.status = 'Y'  AND fyear = '".$fy."'");
		$this->mViewData['sel_qryinfo'] = $sel_qry->result_array();
		
		//Template view 
		$this->render('accounts/define_income_tax_slab_view', 'full_width', $this->mViewData);
	}
	public function get_income_tax_slab_define_fy()
	{
		$fyear = $this->input->post('searchYear');
		$result = $this->Accounts_model->get_income_tax_slab_define_fy($fyear); 
		echo json_encode($result);
	}
	
	public function delete_incomeslab(){
		$pt_id = $this->input->post('pt_id');
		$deleteSQL = $this->db->query('DELETE FROM it_slab_master where it_id = "'.$pt_id.'"');
	}
	
	public function add_edit_incomeslab(){
		$fyear = $this->input->post('searchYear');
		$from_range = $this->input->post('from_range');
		$to_range = $this->input->post('to_range');
		$range = $from_range."-".$to_range;
		$controller = $this->input->post('controller');
		$pt_value = $this->input->post('pt_value');
		if($controller == 0){
			$sql = $this->db->query("INSERT INTO  it_slab_master SET `range` = '".$range."', `it_value` = '".$pt_value."', `fyear` = '".$fyear."'");
		}
		else 
		{
			$sql = $this->db->query("UPDATE `it_slab_master` SET `range` = '".$range."' , `it_value` = '".$pt_value."' WHERE `it_id` = ".$controller."");
		}
	}
	
	public function tax_deduction_limit()
	{
		$this->mViewData['pageTitle'] = 'Tax Deduction Limit';
		//Template view 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$this->mViewData['fyear'] = $fyear;
		
		$empSql = $this->db->query("SELECT * FROM `income_tax_limit` WHERE fyear='".$fyear."'");
		$empin = $empSql->result_array(); //print_r($empin);
		$this->mViewData['empInfo'] = $empSql->result_array();
		
		$this->render('accounts/tax_deduction_limit_view', 'full_width', $this->mViewData);
		$this->load->view('script/hr/allowance_deduction_list_script');
	}
	public function tax_deduction_limit_fy()
	{
		$fyear = $this->input->post('searchYear');
		$empSql = $this->db->query("SELECT * FROM `income_tax_limit` WHERE fyear='".$fyear."'");
		$result = $empSql->result_array();
		echo json_encode($result);
	}
	
	public function manage_income_tax($fy){
			$rent_paid_by_employee  						= $this->input->post('rent_paid_by_employee');
			$conv_allowance  						     	= $this->input->post('conv_allowance');
			$childreneducationalallowance  					= $this->input->post('childreneducationalallowance');
			$medicalexpensesperannum  						= $this->input->post('medicalexpensesperannum');
			$leavetravelconcession  						= $this->input->post('leavetravelconcession');
			$entertainment_allowance  						= $this->input->post('entertainment_allowance');
			$professional_tax  								= $this->input->post('professional_tax');
			$self_occupied_property  						= $this->input->post('self_occupied_property');
			$let_out_rented_property  						= $this->input->post('let_out_rented_property');
			$deduction80C  									= $this->input->post('deduction80C');
			$deduction80CCD_contribution_nps  				= $this->input->post('deduction80CCD_contribution_nps');
			$selfsfamily  									= $this->input->post('selfsfamily');
			$parents  										= $this->input->post('parents');
			$deductions_senior_citizen  					= $this->input->post('deductions_senior_citizen');
			$dependants_normal_disability  					= $this->input->post('dependants_normal_disability');
			$dependants_severe_disability  					= $this->input->post('dependants_severe_disability');
			$meducal_norman_case  							= $this->input->post('meducal_norman_case');
			$senior_citizen60  								= $this->input->post('senior_citizen60');
			$super_senior_citizen80  						= $this->input->post('super_senior_citizen80');
			$interest_loan_higher_education_80E 			= $this->input->post('interest_loan_higher_education_80E');
			$interest_home_loan_80E  						= $this->input->post('interest_home_loan_80E');
			$actual_donation  								= $this->input->post('actual_donation');
			$max_amount  									= $this->input->post('max_amount');
			$self_normal_disability  						= $this->input->post('self_normal_disability');
			$self_severe_disability  						= $this->input->post('self_severe_disability');
			$tid  											= $this->input->post('tid');
			$standard_deduction  							= $this->input->post('standard_deduction');
			
			$result=$this->db->query("SELECT tid FROM `income_tax_limit` WHERE tid='".$tid."'");
			$resultRes = $result->result_array();
			if(count($resultRes)>0){
				$updateSql="UPDATE income_tax_limit SET 
					rent_oaid_by_employee				='".$rent_paid_by_employee."',
					conv_allowance						='".$conv_allowance."',
					childreneducationalallowance		='".$childreneducationalallowance."',
					medicalexpensesperannum				='".$medicalexpensesperannum."',
					leavetravelconcession				='".$leavetravelconcession."',
					entertainment_allowance				='".$entertainment_allowance."',
					professional_tax					='".$professional_tax."',
					self_occupied_property				='".$self_occupied_property."',
					let_out_rented_property				='".$let_out_rented_property."',
					deduction80C						='".$deduction80C."',
					deduction80CCD_contribution_nps		='".$deduction80CCD_contribution_nps."',
					selfsfamily							='".$selfsfamily."',
					parents								='".$parents."',
					deductions_senior_citizen			='".$deductions_senior_citizen."',
					dependants_normal_disability		='".$dependants_normal_disability."',
					dependants_severe_disability		='".$dependants_severe_disability."',
					meducal_norman_case					='".$meducal_norman_case."',
					senior_citizen60					='".$senior_citizen60."',
					super_senior_citizen80				='".$super_senior_citizen80."',
					interest_loan_higher_education_80E	='".$interest_loan_higher_education_80E."',
					interest_home_loan_80E				='".$interest_home_loan_80E."',
					actual_donation						='".$actual_donation."',
					max_amount							='".$max_amount."',
					self_normal_disability				='".$self_normal_disability."',
					self_severe_disability				='".$self_severe_disability."',
					standard_deduction				='".$standard_deduction."',
					created_date=NOW() 
					WHERE tid='".$resultRes[0]['tid']."' ORDER BY `tid` DESC LIMIT 1";
					$this->db->query($updateSql);
			} else {
				$insertSql = "INSERT INTO income_tax_limit
					(tid, rent_oaid_by_employee, conv_allowance, childreneducationalallowance, medicalexpensesperannum, leavetravelconcession, entertainment_allowance, professional_tax, self_occupied_property, let_out_rented_property, deduction80C, deduction80CCD_contribution_nps, selfsfamily, parents, deductions_senior_citizen, dependants_normal_disability, dependants_severe_disability, meducal_norman_case, senior_citizen60, super_senior_citizen80, interest_loan_higher_education_80E, interest_home_loan_80E, actual_donation, max_amount, self_normal_disability, self_severe_disability, standard_deduction, fyear, created_date)
					VALUES ('', '".$rent_paid_by_employee."', '".$conv_allowance."', '".$childreneducationalallowance."', '".$medicalexpensesperannum."', '".$leavetravelconcession."', '".$entertainment_allowance."', '".$professional_tax."', '".$self_occupied_property."', '".$let_out_rented_property."', '".$deduction80C."', '".$deduction80CCD_contribution_nps."', '".$selfsfamily."','".$parents."', '".$deductions_senior_citizen."', '".$dependants_normal_disability."', '".$dependants_severe_disability."', '".$meducal_norman_case."', '".$senior_citizen60."','".$super_senior_citizen80."','".$interest_loan_higher_education_80E."', '".$interest_home_loan_80E."', '".$actual_donation."', '".$max_amount."', '".$self_normal_disability."', '".$self_severe_disability."', '".$standard_deduction."', '".$fy."' , '".date('Y-m-d H:i:s')."')";
					$this->db->query($insertSql);
					//print_r($resultRes); exit;
			}
			
	}
	/*END MASTER */
	
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
		$this->render('accounts/employee_management/profile_readonly_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/profile_update_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/comp_profile_readonly_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/comp_profile_update_emp_view', 'full_width',$this->mViewData); 
		$this->load->view('script/myprofile/company_js');
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
							   calculation_type, pf_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name
					FROM `internal_user` AS i 
					LEFT JOIN `salary_info` AS s ON s.login_id = i.login_id 
					LEFT JOIN bank_master AS b ON b.bank_id = s.bank
					WHERE i.login_id = '".$user_id."'";
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();


		$increSql = "SELECT AES_DECRYPT(increament, '".$encypt."') AS increament, year, increament_info_id FROM `emp_increament_info` WHERE login_id = '".$user_id."'";
		$increRes = $this->db->query($increSql);
		$this->mViewData['increRows'] = $increRes->result_array();

		$this->render('accounts/employee_management/salary_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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
							   calculation_type, pf_no, mediclaim_no, bank, bank_no,ifsc_code, payment_mode , b.bank_name, b.bank_id
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

		$this->render('accounts/employee_management/salary_profile_update_emp_view', 'full_width',$this->mViewData); 
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
		$this->render('accounts/employee_management/education_profile_readonly_emp_view', 'full_width',$this->mViewData);
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
		 
		$this->render('accounts/employee_management/education_update_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/family_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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
					  
		$this->render('accounts/employee_management/family_update_emp_view', 'full_width',$this->mViewData);
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

		$this->render('accounts/employee_management/reference_readonly_emp_view', 'full_width',$this->mViewData); 
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
		$this->render('accounts/employee_management/reference_update_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/job_description_readonly_emp_view', 'full_width',$this->mViewData); 
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
					}
				}
			} else {
				$error = "PDF,MSWORD,EXCEL files are allowed";
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
					}
				}
			} else {
				$error = "PDF,MSWORD,EXCEL files are allowed";
			}
			$skills = $this->input->post('selSkills');
			$skill = implode(',', (array) $skills);
			// $jd $kt $skill
			$chkSql="SELECT internal_user_ext_id FROM `internal_user_ext` WHERE `login_id` =".$user_id;
			$chkRes = $this->db->query($chkSql);
			if($chkRes->num_rows() == 0){
				  $insertSQL = "INSERT INTO internal_user_ext (login_id, jd_document, kpi_document, skills)
                    VALUES ('".$jd."', '".$kt."', '".$skill."')";
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
					 annualdate= '".date('Y-m-d',strtotime($this->input->post('year').'-'.date('m').'-'.date('d')))."'";
				 }else {
					 $insGoalSql ="UPDATE `goal_sheet` SET objective = '".$this->input->post('objective')[$i]."', 
                            target = '".$this->input->post('target')[$i]."', 
							weightage= '".$this->input->post('weightage')[$i]."' 
							WHERE login_id='".$user_id."' AND mid='".$this->input->post('mid')[$i]."'";
				}
				$insGoalRes = $this->db->query($insGoalSql);
				header("Refresh:0");
			} 
		}
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->render('accounts/employee_management/job_description_update_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/document_readonly_emp_view', 'full_width',$this->mViewData);  
	}
	
	public function document_update_emp()
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
					echo $this->UploadException($_FILES['file']['error']); 
					$error = 'Something went Wrong';
				}
		   } 
		
		$this->mViewData['success_msg'] = $success;
		$this->mViewData['error_msg'] = $error;
		$this->mViewData['pageTitle']    = 'Edit Documents';
		$this->render('accounts/employee_management/document_update_emp_view', 'full_width',$this->mViewData);
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
		
		$this->render('accounts/employee_management/exp_profile_readonly_emp_view', 'full_width',$this->mViewData); 
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
		
		$this->render('accounts/employee_management/exp_update_emp_view', 'full_width',$this->mViewData); 
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
		
		$this->render('accounts/employee_management/letter_issued_readonly_emp_view', 'full_width',$this->mViewData);  
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
		$this->render('accounts/employee_management/letter_issued_update_emp_view', 'full_width',$this->mViewData);
	}
	
}
