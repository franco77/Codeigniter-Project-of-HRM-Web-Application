<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_help_desk extends MY_Controller 
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
	 
	public function professional_tax_slab()
	{
		$this->mViewData['pageTitle'] = 'Professional Tax Slab';
		
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
		$this->render('accounts_help_desk/professional_tax_slab_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/professional_tax_slab_script');
	}
	public function get_professional_tax_slab()
	{
		$result = $this->Accounts_model->get_proffessional_tax_slab(); 
		echo json_encode($result);
	}
	public function get_proffessional_tax_slab_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_proffessional_tax_slab_fy($fyear); 
		echo json_encode($result);
	}
	public function income_tax_slab_define()
	{
		$this->mViewData['pageTitle'] = 'Income Tax Slab';
		//Template view 
		$this->render('accounts_help_desk/income_tax_slab_define_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/income_tax_slab_define_script');
	}
	public function get_income_tax_slab_define()
	{
		$result = $this->Accounts_model->get_income_tax_slab_define(); 
		echo json_encode($result);
	}
	public function get_income_tax_slab_define_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_income_tax_slab_define_fy($fyear); 
		echo json_encode($result);
	}
	public function tax_deduction_limit_define()
	{
		$this->mViewData['pageTitle'] = 'Tax Deduction Limit';
		//Template view 
		$this->render('accounts_help_desk/tax_deduction_limit_define_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/tax_deduction_limit_define_script');
	}
	public function get_tax_deduction_limit_define()
	{
		$result = $this->Accounts_model->get_tax_deduction_limit_define(); 
		echo json_encode($result);
	}
	public function get_tax_deduction_limit_define_fy()
	{
		$searchYear = explode('-', $this->input->post('searchYear'));
		$fyear = $searchYear[0];
		$result = $this->Accounts_model->get_tax_deduction_limit_define_fy($fyear); 
		echo json_encode($result);
	}
	
	public function estimated_declaration_form()
	{
		$this->mViewData['pageTitle'] = 'Estimated Declaration Form';
		//load form helper and validation library
		$loginID =$this->session->userdata('user_id');
		if(isset($_Get['lid']))
		{
			$loginID = $this->input->get('lid');
			$actionURL = base_url();
		} 
		else
		{
			$actionURL = base_url().'?id='.$loginID;
		}
		
		
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		
		if(isset($_Get['fyear'])){
			$fyear = $_Get['fyear'];
		}
		$this->mViewData['fyear'] = $fyear;
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		//print_r($this->mViewData['estimatedValue']);		
		
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
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{
			$rent_paid_by_employee  						= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 				= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 								= $this->input->post('conv_allowance');
			$eligible_conv_allowance 						= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 					= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 			= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 						= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 				= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 							= $this->input->post('');
			$eligible_leavetravelconcession 				= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 						= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 				= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 								= $this->input->post('professional_tax');
			$eligible_professional_tax 						= $this->input->post('eligible_professional_tax');
			$self_occupied_property 						= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 				= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 						= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 				= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 					= $this->input->post('contribution_provident_fund');
			$lic 											= $this->input->post('lic');
			$public_provident_fund 							= $this->input->post('public_provident_fund');
			$nsc 											= $this->input->post('nsc');
			$childreneducationfee 							= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 								= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 			= $this->input->post('contribution_notified_pension_fund');
			$ulip 											= $this->input->post('ulip');
			$postofficetax 									= $this->input->post('postofficetax');
			$elss 											= $this->input->post('elss');
			$housingloanprincipal 							= $this->input->post('housingloanprincipal');
			$fixeddeposit 									= $this->input->post('fixeddeposit');
			$any_other_tax 									= $this->input->post('any_other_tax');
			$total_deduction80c 							= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 					= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD							= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 				= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 				= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 		= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 					= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 			= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 				= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 		= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 			= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 								= $this->input->post('normal_disability');
			$eligible_normal_disability 					= $this->input->post('eligible_normal_disability');
			$severe_disability 								= $this->input->post('severe_disability');
			$eligible_severe_disability 					= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 							= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 					= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 					= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 		= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 								= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 					= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 						= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 				= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 							= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 				= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 			= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e 	= $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 						= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 				= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 							= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 					= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 			= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 			= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 						= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 					= $this->input->post('eligible_deduction_under_80U');
			$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			$check_fyear = $this->Accounts_model->get_income_tax_declaration_estimation_check($fyear);
			//echo '<pre>';var_dump($check_fyear);echo "</pre>";exit;
			if(count($check_fyear) > 0)
			{
				$tid= $check_fyear[0]['tid'];
				//update 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$tid);
				//var_dump($update);exit;
			}
			else
			{
				//insert 
				$insert = $this->Accounts_model->insert_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear);
				//var_dump($insert);exit;
			}
			//get reporting manager
			$repMgrInfo = $this->Accounts_model->get_reporting_maanager(); 
			//sending mail to reporting manager
			$subject = 'Income Tax Declaration(Estimation) - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
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
                 <p>Dear Accounts,</p>                                 
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

				

				$toRM ='hr@polosoftech.com';
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
				
				mail($toRM, $subject, $messageRM, $headers);
				mail($to, $subject, $message, $headers);
				redirect('/accounts_help_desk/my_estimated_declaration');
		}
		//end estimated form apply
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageRM') == "APPROVE" && $this->session->userdata('user_id') !='')
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
			
			$result = $this->Accounts_model->get_income_tax_declaration_estimation();
			
			if(count($result)>0)
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->session->userdata('user_id')."'";
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
                  <p>Your Income Tax Estimation form has been Approved successfully.</p> 
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
				$to =$empInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
			 
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageRM') == "Reject" && $this->session->userdata('user_id') !='')
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$this->input->post('login_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->input->post('login_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Estimation)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/estimated_declaration_form');
			
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
                  <p>Your Income Tax Estimation form has been Rejected successfully.</p> 
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
				$to =$empInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
		}
		//END REJECT
		
		
		$tid ='';
		if($this->input->get('id') != ''){
			$tid = $this->input->get('id');
		}
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); 
		$this->mViewData['empInfo'] = $this->Accounts_model->get_income_tax_declaration_estimation($tid);
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->session->userdata('user_id')."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		//var_dump($repMgrInfo);
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		$this->render('accounts_help_desk/estimated_declaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);		
		$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	public function final_delcaration_form()
	{
		$this->mViewData['pageTitle'] = 'Final Declaration Form';
		//load form helper and validation library
		$loginID =$this->session->userdata('user_id');
		if(isset($_Get['lid']))
		{
			$loginID =$_Get['lid'];
			$actionURL = base_url();
		}
		else
		{
			$actionURL = base_url().'?id='.$loginID;
		}
		
		
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		
		if(isset($_Get['fyear'])){
			$fyear = $_Get['fyear'];
		}
		$this->mViewData['fyear'] = $fyear;
		$this->mViewData['estimatedValue'] = $this->Accounts_model->get_tax_deduction_limit_define($fyear);
		
		$repSql = "SELECT email,full_name FROM `internal_user` WHERE login_id = (SELECT reporting_to FROM `internal_user` WHERE login_id = '".$loginID."')";
		$repRes = $this->db->query($repSql);
		$repInfo = $repRes->result_array();
		
		$empSql = "SELECT full_name,email,loginhandle FROM `internal_user` WHERE login_id = '".$loginID."'";
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->result_array();
		
		// Get status of income_tax declaration
		$incomeSql = "select * from income_tax_declaration_estimation where login_id = '".$loginID."' and fyear = '".$fyear."' and type = 'F' and ac_status = '0'";
		$incomeQuery = $this->db->query($incomeSql);
		$incomeRes = $incomeQuery->num_rows();
		$this->mViewData['declaration_count'] = $incomeRes;
		
	
		$logo_URL = base_url('assets/images/logo.gif');
		$logoText = '<div id="icompassMailTop" style="border-bottom:1px solid #ddd;padding:10px 0 20px;">
		<img alt="POLOSOFT TECHNOLOGIES Pvt Ltd Logo" src="'.$logo_URL.'" />
		</div>';
			
		$footer = '<a href="http://www.facebook.com/polosoftechnologies" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/facebook.png" /> Find us on Facebook</a><br />
		<a href="http://www.linkedin.com/company/polosoft-technologies-pvt-ltd" style="text-decoration:none"><img width="14" border="0" alt="" src="'.base_url().'assets/images/icon/linkedin.png" /> Find us on Linkedin</a>
		<div id="icompassMailBottom" style="margin-top:20px;padding-top:10px;color:#999;border-top:1px solid #ddd;font-size:12px;">
			&copy; <strong>'.date("Y").' POLOSOFT TECHNOLOGIES Pvt. Ltd.</strong> All rights reserved.
		</div>';		
		
		// Estimated form apply by employee
		$this->load->helper('form');
		$this->load->library('form_validation');
		if($this->input->post('btnAddMessage') == "APPLY" && $this->session->userdata('user_id') !='')
		{
			$rent_paid_by_employee  						= $this->input->post('rent_paid_by_employee');
			$eligible_rent_paid_by_employee 				= $this->input->post('eligible_rent_paid_by_employee');
			$conv_allowance 								= $this->input->post('conv_allowance');
			$eligible_conv_allowance 						= $this->input->post('eligible_conv_allowance');
			$childreneducationalallowance 					= $this->input->post('childreneducationalallowance');
			$eligible_childreneducationalallowance 			= $this->input->post('eligible_childreneducationalallowance');
			$medicalexpensesperannum 						= $this->input->post('medicalexpensesperannum');
			$eligible_medicalexpensesperannum 				= $this->input->post('eligible_medicalexpensesperannum');
			$leavetravelconcession 							= $this->input->post('');
			$eligible_leavetravelconcession 				= $this->input->post('eligible_leavetravelconcession');
			$entertainment_allowance 						= $this->input->post('entertainment_allowance');
			$eligible_entertainment_allowance 				= $this->input->post('eligible_entertainment_allowance');
			$professional_tax 								= $this->input->post('professional_tax');
			$eligible_professional_tax 						= $this->input->post('eligible_professional_tax');
			$self_occupied_property 						= $this->input->post('self_occupied_property');
			$eligible_self_occupied_property 				= $this->input->post('eligible_self_occupied_property');
			$let_our_rented_property 						= $this->input->post('let_our_rented_property');
			$eligible_let_our_rented_property 				= $this->input->post('eligible_let_our_rented_property');
			$contribution_provident_fund 					= $this->input->post('contribution_provident_fund');
			$lic 											= $this->input->post('lic');
			$public_provident_fund 							= $this->input->post('public_provident_fund');
			$nsc 											= $this->input->post('nsc');
			$childreneducationfee 							= $this->input->post('childreneducationfee');
			$mutualfund_or_uti 								= $this->input->post('mutualfund_or_uti');
			$contribution_notified_pension_fund 			= $this->input->post('contribution_notified_pension_fund');
			$ulip 											= $this->input->post('ulip');
			$postofficetax 									= $this->input->post('postofficetax');
			$elss 											= $this->input->post('elss');
			$housingloanprincipal 							= $this->input->post('housingloanprincipal');
			$fixeddeposit 									= $this->input->post('fixeddeposit');
			$any_other_tax 									= $this->input->post('any_other_tax');
			$total_deduction80c 							= $this->input->post('total_deduction80c');
			$eligible_total_deduction80c 					= $this->input->post('eligible_total_deduction80c');
			$deduction_under_80CCD							= $this->input->post('deduction_under_80CCD');
			$eligible_deduction_under_80CCD 				= $this->input->post('eligible_deduction_under_80CCD');
			$deduction_under_80D_selffamily 				= $this->input->post('deduction_under_80D_selffamily');
			$eligible_deduction_under_80D_selffamily 		= $this->input->post('eligible_deduction_under_80D_selffamily');
			$deduction_under_80D_parents 					= $this->input->post('deduction_under_80D_parents');
			$eligible_deduction_under_80D_parents 			= $this->input->post('eligible_deduction_under_80D_parents');
			$deduction_incase_senior_citizen 				= $this->input->post('deduction_incase_senior_citizen');
			$eligible_deduction_incase_senior_citizen 		= $this->input->post('eligible_deduction_incase_senior_citizen');
			$total_deduction_incase_senior_citizen 			= $this->input->post('total_deduction_incase_senior_citizen');
			$eligible_total_deduction_incase_senior_citizen = $this->input->post('eligible_total_deduction_incase_senior_citizen');
			$normal_disability 								= $this->input->post('normal_disability');
			$eligible_normal_disability 					= $this->input->post('eligible_normal_disability');
			$severe_disability 								= $this->input->post('severe_disability');
			$eligible_severe_disability 					= $this->input->post('eligible_severe_disability');
			$total_deduction_80dd 							= $this->input->post('total_deduction_80dd');
			$total_eligible_deduction_80dd 					= $this->input->post('total_eligible_deduction_80dd');
			$medical_treatment_normal_case 					= $this->input->post('medical_treatment_normal_case');
			$eligible_medical_treatment_normal_case 		= $this->input->post('eligible_medical_treatment_normal_case');
			$senior_citizen_60 								= $this->input->post('senior_citizen_60');
			$eligible_senior_citizen_60 					= $this->input->post('eligible_senior_citizen_60');
			$super_senior_citizen_80 						= $this->input->post('super_senior_citizen_80');
			$eligible_super_senior_citizen_80 				= $this->input->post('eligible_super_senior_citizen_80');
			$total_deduction_80ddb 							= $this->input->post('total_deduction_80ddb');
			$total_eligible_deduction_80ddb 				= $this->input->post('total_eligible_deduction_80ddb');
			$interest_loan_higher_education_80e 			= $this->input->post('interest_loan_higher_education_80e');
			$eligible_interest_loan_higher_education_80e 	= $this->input->post('eligible_interest_loan_higher_education_80e');
			$interest_home_loan_80ee 						= $this->input->post('interest_home_loan_80ee');
			$eligible_interest_home_loan_80ee 				= $this->input->post('eligible_interest_home_loan_80ee');
			$actual_donation_80g 							= $this->input->post('actual_donation_80g');
			$eligible_actual_donation_80g 					= $this->input->post('eligible_actual_donation_80g');
			$deduction_under_80U_noraml_disability 			= $this->input->post('deduction_under_80U_noraml_disability');
			$eligible_deduction_under_80U_noraml_disability = $this->input->post('eligible_deduction_under_80U_noraml_disability');
			$deduction_under_80U_severe_disability 			= $this->input->post('deduction_under_80U_severe_disability');
			$eligible_deduction_under_80U_severe_disability = $this->input->post('eligible_deduction_under_80U_severe_disability');
			$total_deduction_under_80U 						= $this->input->post('total_deduction_under_80U');
			$eligible_deduction_under_80U 					= $this->input->post('eligible_deduction_under_80U');
			$tid 					= $this->input->post('tid');
			$fyear 					= $this->input->post('fyear');
			
			//$result = $this->Accounts_model->get_income_tax_final_estimation();
			//print_r($result); exit;
			if($tid !="")
			{
				//update 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$tid);
				//var_dump($update);exit;
			}
			else
			{
				//insert 
				$insert = $this->Accounts_model->insert_income_tax_declaration_final($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear);
				//var_dump($insert);exit;
			}
			//get reporting manager
			$repMgrInfo = $this->Accounts_model->get_reporting_maanager(); 
			//sending mail to reporting manager
			$subject = 'Income Tax Declaration(Final) - '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/final_delcaration_form');
			
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
                 <p>Dear Accounts,</p>                                 
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Final form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Accounts Department.</p>                                 
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
                  <p>Your Income Tax Final form has been submitted successfully.</p> 
				  <p>Please keep it for review with Accounts manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Accounts Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

				//$toRM =$repInfo[0]['email'];
				$toRM ='hr@polosoftech.com';
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
				
				mail($toRM, $subject, $messageRM, $headersRM);
				mail($to, $subject, $message, $headers);
				redirect('/accounts_help_desk/my_final_declaration');
		} 
		//end estimated form apply
	
		//APPROVE BY ACCOUNTS HEAD
		if($this->input->post('btnAddMessageRM') == "APPROVE" && $this->session->userdata('user_id') !='')
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
			
			$result = $this->Accounts_model->get_income_tax_final_estimation();
			
			if(count($result)>0)
			{ 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U); 
			}
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->session->userdata('user_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = $repMgrRes->result_array();

			$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = 10816 WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
			$revRes = $this->db->query($revSql);
			$revInfo = $revRes->result_array();
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Final)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/final_delcaration_form');
			
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
                 <p>Dear Accounts,</p>                                 
                 <p>{$empInfo[0]['full_name']} has submitted his/her Income tax Final form.  </p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Accounts Department.</p>                                 
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
				  <p>Please keep it for review with Accounts manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Accounts Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;

				

				$toRM =$repInfo[0]['email'];
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
			 
		}
		//END 
		
		//REJECT FROM ACCOUNT HEAD
		//FOR RECJECT OF APPRAISER
		if($this->input->post('btnRejectMessageRM') == "Reject" && $this->session->userdata('user_id') !='')
		{
			$updateSql="UPDATE `income_tax_declaration_estimation` SET  ac_status='2' WHERE login_id='".$this->input->post('login_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'";      
			$this->db->query($updateSql);
		
			$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->input->post('login_id')."'";
			$repMgrRes = $this->db->query($repMgrSql);
			$repMgrInfo = mysql_fetch_row($repMgrRes);
			
			//SENDING EMAIL
			$subject = 'Income Tax Declaration(Final)- '.$empInfo[0]['loginhandle'].' ('.$empInfo[0]['full_name'].')';
			$site_base_url=base_url('accounts_help_desk/final_declaration_form');
			
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
             <p>Dear Accounts,</p>                                 
             <p>Your Income Tax Declaration(Final) form has been rejected by account's authority. </p>                                             
             <p><a href="{$site_base_url}/script/estimated_declaration_form.php" style="text-decoration:none">Click here for more details</a><br /><br /></p>                
             <p> In case of any Query, Please contact to Account's department.</p>                                 
             <p>{$footer}</p>
             </div> 
          </div> 
        </div>  
        </div>
    </body>
    </html>
EOD;
				$toRM =$repInfo[0]['email']; 
				$headersRM  = 'MIME-Version: 1.0' . "\r\n";
				$headersRM .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headersRM .= 'From: '.$empInfo[0]['full_name'].'. <'.$empInfo[0]['email'].'>' . "\r\n";  
				$headersRM .= 'Reply-To: HR Department <hr@polosoftech.com>' . "\r\n";
				$headersRM .= 'X-Mailer: PHP/' . phpversion();
				//mail($toRM, $subject, $messageApp, $headersRM);
				
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
                  <p>Your Income Tax Final form has been Rejected successfully.</p> 
				  <p>Please keep it for review with Accounts manager.</p>  
                 <p><a href="{$site_base_url}" style="text-decoration:none">Click here to review his/her self Income Tax form.</a><br /><br /></p>
                 <p> In case of any Query, Please contact to Accounts Department.</p>                                 
                 <p>{$footer}</p>
                 </div> 
              </div> 
            </div>  
            </div>
        </body>
        </html>
EOD;
				$to =$empInfo[0]['email'];
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: POLOSOFT TECHNOLOGIES Pvt. Ltd. <no-reply@polosoftech.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
		}
		//END REJECT
		
		
		$tid ='';
		if($this->input->get('id') != ''){
			$tid = $this->input->get('id');		
		}
		
		//echo $tid;
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID);; 
		$this->mViewData['empInfo'] = $this->Accounts_model->get_income_tax_declaration_final($tid);
		//echo "<pre>";print_r($this->mViewData['empInfo']);echo "</pre>";
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//get account id
		$repMgrSql = "SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle, iu.login_id FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id='10816' WHERE i.login_id ='".$this->session->userdata('user_id')."'";
		$repMgrRes = $this->db->query($repMgrSql);
		$repMgrInfo = $repMgrRes->result_array();
		
		$revSql="SELECT rev.full_name, rev.login_id, rev.email FROM internal_user AS i LEFT JOIN internal_user AS rev ON rev.login_id = i.login_id WHERE i.login_id = ".$repMgrInfo[0]['login_id']." LIMIT 1";
		$revRes = $this->db->query($revSql);
		$this->mViewData['revInfo'] = $revRes->result_array();
		//Template view 
		
		$this->render('accounts_help_desk/final_delcaration_form_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/js/final_tax_calculation', $this->mViewData);		
		//$this->load->view('script/accounts/js/left_sidebar', $this->mViewData);		
	}
	
	
	public function my_estimated_declaration()
	{
		$this->mViewData['pageTitle'] = 'My Estimated Declaration';
		$this->mViewData['get_my_estimate'] = $this->Accounts_model->get_my_estimated_declaration();
		//Template view 
		$this->render('accounts_help_desk/my_estimated_declaration_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/my_estimated_declaration_script');
	}
	
	
	public function my_estimated_declaration_print()
	{
		$this->mViewData['pageTitle']    = 'My estimated declaration appraisal';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$tid = $_GET['tid'];
		
		$revRes = $this->db->query("SELECT i.*, om.*, dp.*,d.*, ii.full_name as reporting_manager_full_name 
		FROM `internal_user` i 
		LEFT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id 
		LEFT JOIN `internal_user` ii ON i.reporting_to = ii.login_id 
		LEFT JOIN `user_desg` d ON d.desg_id = i.designation 
		LEFT JOIN `department` dp ON dp.dept_id = i.department 
		WHERE i.login_id != '10010' AND om.login_id = '".$loginID."' and tid='".$tid."'");
		$rowAppraisal = $revRes->result_array();
		$this->mViewData['rowAppraisal'] = $revRes->result_array();
		
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf_emp($loginID); 

		$cond = "DATE_FORMAT(annualdate,'%Y')='".date('Y',strtotime($rowAppraisal[0]['apply_date']))."'";
        $qryGoal = "SELECT * FROM `goal_sheet` WHERE login_id = '".$loginID."' AND $cond";
		$goalRes = $this->db->query($qryGoal);
		$this->mViewData['rowGoal'] = $goalRes->result_array();
		//Template view
		$this->load->view('accounts_help_desk/my_income_tax_declaration_details', $this->mViewData);
	}
	
	
	public function get_my_estimated_declaration()
	{
		
	}
	public function my_final_declaration()
	{ 
		$this->mViewData['pageTitle'] = 'My Final Income Declaration';
		$this->mViewData['get_my_estimate'] = $this->Accounts_model->get_my_final_declaration();
		
		//Template view 
		$this->render('accounts_help_desk/my_final_declaration_view', 'full_width', $this->mViewData); 
		//$this->load->view('script/accounts/my_estimated_declaration_script');
	}
	public function my_other_income()
	{
		$this->mViewData['pageTitle'] = 'My Other Income';
		$this->mViewData['my_other_income'] = $this->Accounts_model->get_my_other_income(); 
		//Template view 
		$this->render('accounts_help_desk/my_other_income_view', 'full_width', $this->mViewData); 
	}
	public function my_other_income_details()
	{
		$this->mViewData['pageTitle'] = 'My Other Income details';
		$loginID = $_GET['id'];
		//$annualdate = $_GET['applydate'];
		$id = $_GET['tid'];
		//Query for select 
		$empSql = "SELECT i.*,i.login_id as loginID, om.*, dp.*,d.* FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `other_income` om ON om.login_id = i.login_id  WHERE i.login_id = '".$loginID."' AND om.id = '".$id."'"; 
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();  

		$repMgrSql = "SELECT login_id, full_name, email FROM `internal_user` WHERE login_id =(SELECT reporting_to FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."')";
		$repMgrRes = $this->db->query($repMgrSql);
		$this->mViewData['repMgrInfo'] = $repMgrRes->result_array(); 
		//Template view
		$this->load->view('accounts_help_desk/my_other_income_details_view', $this->mViewData); 
	}
	public function estimated_tax_compution_sheet()
	{
		$this->mViewData['pageTitle'] = 'Estimated tax computation sheet';
		$loginID = $this->session->userdata('user_id');
		$res_arr = $this->Accounts_model->get_other_incomes($loginID); 
		$this->mViewData['res_arrs'] = $res_arr; 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if(null !== ($this->input->post('searchYear'))){
			$fyear = $this->input->post('searchYear');
		}
		$this->mViewData['fyear'] = $fyear;
		
		//Template view 
		$this->render('accounts_help_desk/estimated_tax_compution_sheet_view', 'full_width', $this->mViewData); 
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);	
	}
	public function estimated_tax_compution_sheet_result()
	{
		$loginID = $this->session->userdata['user_id'];
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if(null !== ($this->input->get('fyear'))){
			$fyear = $this->input->get('fyear');
		}
		$this->mViewData['fyear'] = $fyear;
		$encypt = $this->config->item('masterKey');
		$userSql = $this->db->query("SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.`state_region2`, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'");
		$userInfo = $userSql->result_array();
		$this->mViewData['userInfo'] = $userInfo;
		if(@$userInfo[0]['emp_type'] =='F'){
			$maxPL = $this->getMaxLeave($loginID, 'P');
			$maxSL = $this->getMaxLeave($loginID, 'S');
			$maxLeave = $maxPL + $maxSL;

			$leaveINFO = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
			$avlPL = $maxPL - $leaveINFO['ob_pl'];
			$avlSL = $maxSL - $leaveINFO['ob_sl'];
			$totAvlleave = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
		} else {
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
		//$ugData = $this->input->get('value');
		$ugData = "E";
		$month = date('m');
		$year = date('Y');
		 if($month >= 4){
			 $financialyear = $year.'-'.($year+1);
		}
		else{
			$financialyear = ($year-1).'-'.$year;
		}
		
		$empSql="SELECT i.*, b.bank_name,d.dept_name,si.bank_no,si.mediclaim_no,si.pf_no,u.desg_name, it.*,
        s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days,itd.tax_month,itd.tax_year, 
        AES_DECRYPT(si.fixed_basic, '".$encypt."') AS fixed_basic_final,
        AES_DECRYPT(si.gross_salary, '".$encypt."') AS gross_salary_final,
        AES_DECRYPT(si.basic, '".$encypt."') AS basic_final,
        AES_DECRYPT(si.hra, '".$encypt."') AS hra_final,
        AES_DECRYPT(si.conveyance_allowance, '".$encypt."') AS conveyance_allowance_final,
        AES_DECRYPT(si.reimbursement, '".$encypt."') AS reimbursement_final,
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
		LEFT JOIN `income_tax_details` AS itd ON itd.login_id = i.login_id
		LEFT JOIN `income_tax_declaration_estimation` AS it ON it.login_id = i.login_id  
		LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
		LEFT JOIN `department` AS d ON d.dept_id = i.department
		LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		LEFT JOIN bank_master AS b ON b.bank_id = si.bank
		WHERE i.login_id = '$loginID' AND it.fyear='$fyear'  AND it.type='E' ORDER BY s.sheet_id DESC limit 1";
		//echo $empSql; exit;
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->row_array(); 
		//echo "<pre>"; print_r($empInfo); echo "</pre>";exit;
		$this->mViewData['empInfo'] = $empInfo;
		$count = COUNT($empInfo);
		$this->mViewData['count'] = $count;
		//echo $count; exit;
		if($month >= 4){
			$sfyear="01/04/".$year; 
			$efyear="31/03/".($year+1); 
		}else{
			$efyear="31/03/".$year;  
			$sfyear="01/04/".($year-1);                   
		}

		$res_arr = $this->Accounts_model->get_other_incomes_details($loginID, $fyear); 
		$this->mViewData['res_arr'] = $res_arr;              
		//Template view 
		$this->render('accounts_help_desk/estimated_tax_compution_sheet_result_view', 'empty', $this->mViewData); 
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);	
	}
	public function estimated_tax_compution_sheet_result_print()
	{
		$this->mViewData['pageTitle'] = 'Tax Declaration Details';
		$loginID = $this->session->userdata('user_id');
		if(null !== ($this->input->get('id'))){
			$loginID = $this->input->get('id');
		}
		if(null !== ($this->input->get('tid'))){
			$tid = $this->input->get('tid');
		}
		 
		$encypt = $this->config->item('masterKey');
		$userSql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.`state_region2`, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'";
		$userRes = $this->db->query($userSql);
		$this->mViewData['userInfo'] = $userRes->result_array();
		//var_dump($this->mViewData['userInfo']);
		$userInfo = $userRes->result_array();

		$month = date('m')-1;
		$year = date('Y');

		if($month >= 4)
		{
			$financialyear = $year.'-'.($year+1);
		}
		else
		{
			$financialyear = ($year-1).'-'.$year;
		}
		
		
		$empSql="SELECT i.*, b.bank_name,d.dept_name,si.bank_no,si.mediclaim_no,si.pf_no,u.desg_name, it.*,
        s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days,itd.tax_month,itd.tax_year, 
        AES_DECRYPT(si.fixed_basic, '".$encypt."') AS fixed_basic_final,
        AES_DECRYPT(si.gross_salary, '".$encypt."') AS gross_salary_final,
        AES_DECRYPT(si.basic, '".$encypt."') AS basic_final,
        AES_DECRYPT(si.hra, '".$encypt."') AS hra_final,
        AES_DECRYPT(si.conveyance_allowance, '".$encypt."') AS conveyance_allowance_final,
        AES_DECRYPT(si.reimbursement, '".$encypt."') AS reimbursement_final,
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
		LEFT JOIN `income_tax_details` AS itd ON itd.login_id = i.login_id
		LEFT JOIN `income_tax_declaration_estimation` AS it ON it.login_id = i.login_id  
		LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
		LEFT JOIN `department` AS d ON d.dept_id = i.department
		LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		LEFT JOIN bank_master AS b ON b.bank_id = si.bank
		WHERE i.login_id = '$loginID' AND it.tid='$tid'  AND it.type='E' ORDER BY s.sheet_id DESC limit 1";
				//WHERE i.login_id = '$loginID' AND it.tid='$tid'  AND it.type='E'";
				//WHERE i.login_id = '$loginID' AND itd.tax_year='$year' AND itd.tax_month='$month'";
		//echo $empSql; exit;
		$empRes = $this->db->query($empSql);
		$this->mViewData['count'] = $empRes->num_rows();
		//var_dump($this->mViewData['count']);
		$empInfo = $empRes->row_array();
		$this->mViewData['empInfo'] = $empRes->row_array();
		//var_dump($this->mViewData['empInfo']);
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if(count($this->mViewData['empInfo']) > 0 ){
			$fyear = $empInfo['fyear'];
		}
		$this->mViewData['fyear'] = $fyear;
		
		/* $sql="SELECT * FROM other_income where login_id='".$loginID."'";
		$res = $this->db->query($sql);  
		$this->mViewData['res_arr'] = $res->row_array();  */
		$res_arr = $this->Accounts_model->get_other_incomes_details($loginID, $fyear); 
		$this->mViewData['res_arr'] = $res_arr; 

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
		//Template view 
		$this->render('accounts_help_desk/estimated_tax_compution_sheet_result_view_print', 'empty', $this->mViewData); 
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);	
	}
	public function final_tax_compution_sheet_result_print()
	{
		$this->mViewData['pageTitle'] = 'Tax Declaration Details';
		$loginID = $this->session->userdata('user_id');
		if(null !== ($this->input->get('id'))){
			$loginID = $this->input->get('id');
		}
		if(null !== ($this->input->get('tid'))){
			$tid = $this->input->get('tid');
		}
		 
		$encypt = $this->config->item('masterKey');
		$userSql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.`state_region2`, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'";
		$userRes = $this->db->query($userSql);
		$this->mViewData['userInfo'] = $userRes->result_array();
		//var_dump($this->mViewData['userInfo']);
		$userInfo = $userRes->result_array();

		$month = date('m')-1;
		$year = date('Y');

		if($month >= 4)
		{
			$financialyear = $year.'-'.($year+1);
		}
		else
		{
			$financialyear = ($year-1).'-'.$year;
		}
		
		
		$empSql="SELECT i.*, b.bank_name,d.dept_name,si.bank_no,si.mediclaim_no,si.pf_no,u.desg_name, it.*,
        s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days,itd.tax_month,itd.tax_year, 
        AES_DECRYPT(si.fixed_basic, '".$encypt."') AS fixed_basic_final,
        AES_DECRYPT(si.gross_salary, '".$encypt."') AS gross_salary_final,
        AES_DECRYPT(si.basic, '".$encypt."') AS basic_final,
        AES_DECRYPT(si.hra, '".$encypt."') AS hra_final,
        AES_DECRYPT(si.conveyance_allowance, '".$encypt."') AS conveyance_allowance_final,
        AES_DECRYPT(si.reimbursement, '".$encypt."') AS reimbursement_final,
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
		LEFT JOIN `income_tax_details` AS itd ON itd.login_id = i.login_id
		LEFT JOIN `income_tax_declaration_estimation` AS it ON it.login_id = i.login_id  
		LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
		LEFT JOIN `department` AS d ON d.dept_id = i.department
		LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		LEFT JOIN bank_master AS b ON b.bank_id = si.bank
		WHERE i.login_id = '$loginID' AND it.tid='$tid'  AND it.type='F' ORDER BY s.sheet_id DESC limit 1";
				//WHERE i.login_id = '$loginID' AND it.tid='$tid'  AND it.type='E'";
				//WHERE i.login_id = '$loginID' AND itd.tax_year='$year' AND itd.tax_month='$month'";
		//echo $empSql; exit;
		$empRes = $this->db->query($empSql);
		$this->mViewData['count'] = $empRes->num_rows();
		//var_dump($this->mViewData['count']);
		$empInfo = $empRes->row_array();
		$this->mViewData['empInfo'] = $empRes->row_array();
		
		
		//echo "<pre>"; print_r($empInfo); echo "</pre>";
		
		
		
		//var_dump($this->mViewData['empInfo']);
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = $y;
		}
		else{
			$fyear = $y - 1;
		}
		if(count($this->mViewData['empInfo']) > 0 ){
			$fyear = $empInfo['fyear'];
		}
		$this->mViewData['fyear'] = $fyear;
		
		
		
		$fyearNext = $fyear +1;
		$empSql1="SELECT                             
        SUM(AES_DECRYPT(s.earned_basic, '".$encypt."')) AS earned_basic_total, 
		SUM(AES_DECRYPT(s.earned_hra, '".$encypt."')) AS earned_hra_total, 
		SUM(AES_DECRYPT(s.earned_medical_allowance, '".$encypt."')) AS earned_medical_allowance_total, 
		SUM(AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."')) AS earned_conveyance_allowance_total, 
		SUM(AES_DECRYPT(s.earned_special_allowance, '".$encypt."')) AS earned_special_allowance_total, 
		SUM(AES_DECRYPT(s.child_edu_allowance, '".$encypt."')) AS child_edu_allowance_total, 
		SUM(AES_DECRYPT(s.otherincome, '".$encypt."')) AS otherincome_total, 
		COUNT(*) as months
		FROM `internal_user` AS i 
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		WHERE i.login_id = '$loginID' AND ((s.salary_month >='4' AND s.salary_year ='$fyear') OR (s.salary_month <='3' AND s.salary_year ='$fyearNext'))";
		//echo $empSql1; exit;
		$empRes1 = $this->db->query($empSql1);
		$empInfo1 = $empRes1->result_array();
		//echo "<pre>"; print_r($empInfo1); echo "</pre>";exit;
		$earned_basic_total = $empInfo1[0]['earned_basic_total'];
		$earned_hra_total = $empInfo1[0]['earned_hra_total'];
		$earned_hra_total = $empInfo1[0]['earned_hra_total'];
		$earned_medical_allowance_total = $empInfo1[0]['earned_medical_allowance_total'];
		$earned_conveyance_allowance_total = $empInfo1[0]['earned_conveyance_allowance_total'];
		$earned_special_allowance_total = $empInfo1[0]['earned_special_allowance_total'];
		$child_edu_allowance_total = $empInfo1[0]['child_edu_allowance_total'];
		$otherincome_total = $empInfo1[0]['otherincome_total'];
		
		$total_months = $empInfo1[0]['months'];
		
		$empSql11="SELECT                             
        AES_DECRYPT(s.earned_basic, '".$encypt."') AS earned_basic, 
        AES_DECRYPT(s.earned_hra, '".$encypt."') AS earned_hra, 
        AES_DECRYPT(s.earned_medical_allowance, '".$encypt."') AS earned_medical_allowance, 
        AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."') AS earned_conveyance_allowance, 
        AES_DECRYPT(s.earned_special_allowance, '".$encypt."') AS earned_special_allowance, 
        AES_DECRYPT(s.child_edu_allowance, '".$encypt."') AS child_edu_allowance, 
        AES_DECRYPT(s.otherincome, '".$encypt."') AS otherincome, 
		s.sheet_id
		FROM `internal_user` AS i 
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		WHERE i.login_id = '$loginID' AND ((s.salary_month >='4' AND s.salary_year ='$fyear') OR (s.salary_month <='3' AND s.salary_year ='$fyearNext')) ORDER BY s.sheet_id DESC LIMIT 1";
		//echo $empSql1; exit;
		$empRes11 = $this->db->query($empSql11);
		$empInfo11 = $empRes11->result_array(); 
		$earned_basic = $empInfo11[0]['earned_basic'];
		$earned_hra = $empInfo11[0]['earned_hra'];
		$earned_medical_allowance = $empInfo11[0]['earned_medical_allowance'];
		$earned_conveyance_allowance = $empInfo11[0]['earned_conveyance_allowance'];
		$earned_special_allowance = $empInfo11[0]['earned_special_allowance'];
		$child_edu_allowance = $empInfo11[0]['child_edu_allowance'];
		$otherincome = $empInfo11[0]['otherincome'];
		
		$m=date('m');
		$month=$m-1;
		if($month <= 3){
			$mm = 3 - $month;
		}
		else{
			$mm = (12 - $month) + 3;
		}
		$earned_basic_total = $earned_basic_total + ($earned_basic * $mm);
		$earned_hra_total = $earned_hra_total + ($earned_hra * $mm);
		$earned_medical_allowance_total = $earned_medical_allowance_total + ($earned_medical_allowance * $mm);
		$earned_conveyance_allowance_total = $earned_conveyance_allowance_total + ($earned_conveyance_allowance * $mm);
		$earned_special_allowance_total = $earned_special_allowance_total + ($earned_special_allowance * $mm);
		$child_edu_allowance_total = $child_edu_allowance_total + ($child_edu_allowance * $mm);
		$otherincome_total = $otherincome_total + ($otherincome * $mm);
		
		$this->mViewData['earned_basic_total'] = $earned_basic_total;
		$this->mViewData['earned_hra_total'] = $earned_hra_total;
		$this->mViewData['earned_medical_allowance_total'] = $earned_medical_allowance_total;
		$this->mViewData['earned_conveyance_allowance_total'] = $earned_conveyance_allowance_total;
		$this->mViewData['earned_special_allowance_total'] = $earned_special_allowance_total;
		$this->mViewData['child_edu_allowance_total'] = $child_edu_allowance_total;
		$this->mViewData['otherincome_total'] = $otherincome_total;
		
		
		
		/* $sql="SELECT * FROM other_income where login_id='".$loginID."'";
		$res = $this->db->query($sql);  
		$this->mViewData['res_arr'] = $res->row_array();  */
		$res_arr = $this->Accounts_model->get_other_incomes_details($loginID, $fyear); 
		$this->mViewData['res_arr'] = $res_arr; 

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
		//Template view 
		$this->render('accounts_help_desk/final_tax_computation_sheet_result_view', 'empty', $this->mViewData); 
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);	
	}
	public function getMaxLeave($userID, $type = 'A', $year = '')
	{
		if($year == ''){
			$year = date("Y");
		}
		$joinDtSql = "SELECT i.`join_date`, f.`ob_pl`, f.`ob_sl` FROM `internal_user` i LEFT JOIN `leave_carry_ forward` f ON f.user_id = i.login_id AND f.`year` = '".$year."' WHERE i.`login_id` = '".$userID."'";
		$joinDtRes = $this->db->query($joinDtSql);
		$joinInfo = $joinDtRes->result_array();
		
		$joinDate  = date("d", strtotime($joinInfo[0]['join_date']));
		$joinMonth = date("m", strtotime($joinInfo[0]['join_date']));
		$joinYear  = date("Y", strtotime($joinInfo[0]['join_date']));
		
		if($year <=2013)
		{
		if($type == 'P'){
			
			$maxLeave = 22;
			$carryForwardLeave = $joinInfo[0]['ob_pl'];
		}elseif($type == 'S'){
			$maxLeave = 8;
			$carryForwardLeave = $joinInfo[0]['ob_sl'];
		}else{
			$maxLeave = 30;
			$carryForwardLeave = $joinInfo[0]['ob_pl'] + $joinInfo[0]['ob_sl'];
		}
		
		if($year > $joinYear){
				$maxLeaveForThisYear = $maxLeave + $carryForwardLeave;
		}else{
			if($joinDate <= 15){
					$remainingMonth =  12 - ($joinMonth - 1);
			}else{
					$remainingMonth = 12 - $joinMonth;
			}
			$maxLeaveForThisYear = ceil(($maxLeave / 12 ) * $remainingMonth);
		}
		}
		else {
		if($type == 'P'){
			$maxLeaveForThisYear = $joinInfo[0]['ob_pl'];
		}elseif($type == 'S'){
			$maxLeaveForThisYear = $joinInfo[0]['ob_sl'];
		}else{
			$maxLeaveForThisYear = $joinInfo[0]['ob_pl'] + $joinInfo[0]['ob_sl'];
		}
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
			return $negative . convert_number_to_words(abs($number));
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
					$string .= $conjunction . convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= convert_number_to_words($remainder);
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
	
	public function final_tax_computation_sheet()
	{ 
		$this->mViewData['pageTitle'] = 'Final tax computation sheet';
		
		$loginID = $this->session->userdata('user_id');
		$res_arr = $this->Accounts_model->get_other_incomes($loginID); 
		$this->mViewData['res_arrs'] = $res_arr; 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if(null !== ($this->input->post('searchYear'))){
			$fyear = $this->input->post('searchYear');
		}
		$this->mViewData['fyear'] = $fyear;
		
		
		//Template view 
		$this->render('accounts_help_desk/final_tax_computation_sheet_view', 'full_width', $this->mViewData); 
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);	
	}
	
	public function final_tax_computation_sheet_result(){
		$loginID = $this->session->userdata['user_id'];
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if(null !== ($this->input->get('fyear'))){
			$fyear = $this->input->get('fyear');
		}
		$this->mViewData['fyear'] = $fyear;
		$encypt = $this->config->item('masterKey');
		$userSql = $this->db->query("SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.`state_region2`, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'");
		$userInfo = $userSql->result_array();
		$this->mViewData['userInfo'] = $userInfo;
		if(@$userInfo[0]['emp_type'] =='F'){
			$maxPL = $this->getMaxLeave($loginID, 'P');
			$maxSL = $this->getMaxLeave($loginID, 'S');
			$maxLeave = $maxPL + $maxSL;

			$leaveINFO = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
			$avlPL = $maxPL - $leaveINFO['ob_pl'];
			$avlSL = $maxSL - $leaveINFO['ob_sl'];
			$totAvlleave = $maxLeave > $leaveINFO['ob_pl'] + $leaveINFO['ob_sl'];
		} else {
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
		//$ugData = $this->input->get('value');
		$ugData = "E";
		$month = date('m');
		$year = date('Y');
		 if($month >= 4){
			 $financialyear = $year.'-'.($year+1);
		}
		else{
			$financialyear = ($year-1).'-'.$year;
		}
		
		$empSql="SELECT i.*, b.bank_name,d.dept_name,si.bank_no,si.mediclaim_no,si.pf_no,u.desg_name, it.*,
        s.working_days, s.weekly_off, s.holidays, s.paid_days, s.absent_days, s.arrear_days,itd.tax_month,itd.tax_year, 
        AES_DECRYPT(si.fixed_basic, '".$encypt."') AS fixed_basic_final,
        AES_DECRYPT(si.gross_salary, '".$encypt."') AS gross_salary_final,
        AES_DECRYPT(si.basic, '".$encypt."') AS basic_final,
        AES_DECRYPT(si.hra, '".$encypt."') AS hra_final,
        AES_DECRYPT(si.conveyance_allowance, '".$encypt."') AS conveyance_allowance_final,
        AES_DECRYPT(si.reimbursement, '".$encypt."') AS reimbursement_final,
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
		LEFT JOIN `income_tax_details` AS itd ON itd.login_id = i.login_id
		LEFT JOIN `income_tax_declaration_estimation` AS it ON it.login_id = i.login_id  
		LEFT JOIN `user_desg` AS u ON i.designation = u.desg_id 
		LEFT JOIN `department` AS d ON d.dept_id = i.department
		LEFT JOIN `salary_info` AS si ON si.login_id = i.login_id
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		LEFT JOIN bank_master AS b ON b.bank_id = si.bank
		WHERE i.login_id = '$loginID' AND it.fyear='$fyear'  AND it.type='F' ORDER BY s.sheet_id DESC limit 1";
		//echo $empSql; exit;
		$empRes = $this->db->query($empSql);
		$empInfo = $empRes->row_array(); 
		//echo "<pre>"; print_r($empInfo); echo "</pre>";
		
		
		$fyearNext = $fyear +1;
		$empSql1="SELECT                             
        SUM(AES_DECRYPT(s.earned_basic, '".$encypt."')) AS earned_basic_total, 
		SUM(AES_DECRYPT(s.earned_hra, '".$encypt."')) AS earned_hra_total, 
		SUM(AES_DECRYPT(s.earned_medical_allowance, '".$encypt."')) AS earned_medical_allowance_total, 
		SUM(AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."')) AS earned_conveyance_allowance_total, 
		SUM(AES_DECRYPT(s.earned_special_allowance, '".$encypt."')) AS earned_special_allowance_total, 
		SUM(AES_DECRYPT(s.child_edu_allowance, '".$encypt."')) AS child_edu_allowance_total, 
		SUM(AES_DECRYPT(s.otherincome, '".$encypt."')) AS otherincome_total, 
		COUNT(*) as months
		FROM `internal_user` AS i 
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		WHERE i.login_id = '$loginID' AND ((s.salary_month >='4' AND s.salary_year ='$fyear') OR (s.salary_month <='3' AND s.salary_year ='$fyearNext'))";
		//echo $empSql1; exit;
		$empRes1 = $this->db->query($empSql1);
		$empInfo1 = $empRes1->result_array();
		//echo "<pre>"; print_r($empInfo1); echo "</pre>";exit;
		$earned_basic_total = $empInfo1[0]['earned_basic_total'];
		$earned_hra_total = $empInfo1[0]['earned_hra_total'];
		$earned_medical_allowance_total = $empInfo1[0]['earned_medical_allowance_total'];
		$earned_conveyance_allowance_total = $empInfo1[0]['earned_conveyance_allowance_total'];
		$earned_special_allowance_total = $empInfo1[0]['earned_special_allowance_total'];
		$child_edu_allowance_total = $empInfo1[0]['child_edu_allowance_total'];
		$otherincome_total = $empInfo1[0]['otherincome_total'];
		
		$total_months = $empInfo1[0]['months'];
		
		$empSql11="SELECT                             
        AES_DECRYPT(s.earned_basic, '".$encypt."') AS earned_basic, 
        AES_DECRYPT(s.earned_hra, '".$encypt."') AS earned_hra, 
        AES_DECRYPT(s.earned_medical_allowance, '".$encypt."') AS earned_medical_allowance, 
        AES_DECRYPT(s.earned_conveyance_allowance, '".$encypt."') AS earned_conveyance_allowance, 
        AES_DECRYPT(s.earned_special_allowance, '".$encypt."') AS earned_special_allowance, 
        AES_DECRYPT(s.child_edu_allowance, '".$encypt."') AS child_edu_allowance, 
        AES_DECRYPT(s.otherincome, '".$encypt."') AS otherincome, 
		s.sheet_id
		FROM `internal_user` AS i 
		LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id
		WHERE i.login_id = '$loginID' AND ((s.salary_month >='4' AND s.salary_year ='$fyear') OR (s.salary_month <='3' AND s.salary_year ='$fyearNext')) ORDER BY s.sheet_id DESC LIMIT 1";
		//echo $empSql1; exit;
		$empRes11 = $this->db->query($empSql11);
		$empInfo11 = $empRes11->result_array(); 
		$earned_basic = $empInfo11[0]['earned_basic'];
		$earned_hra = $empInfo11[0]['earned_hra'];
		$earned_medical_allowance = $empInfo11[0]['earned_medical_allowance'];
		$earned_conveyance_allowance = $empInfo11[0]['earned_conveyance_allowance'];
		$earned_special_allowance = $empInfo11[0]['earned_special_allowance'];
		$child_edu_allowance = $empInfo11[0]['child_edu_allowance'];
		$otherincome = $empInfo11[0]['otherincome'];
		
		
		$m=date('m');
		$month=$m-1;
		if($month <= 3){
			$mm = 3 - $month;
		}
		else{
			$mm = (12 - $month) + 3;
		}
		
		$earned_basic_total = $earned_basic_total + ($earned_basic * $mm);
		$earned_hra_total = $earned_hra_total + ($earned_hra * $mm);
		$earned_medical_allowance_total = $earned_medical_allowance_total + ($earned_medical_allowance * $mm);
		$earned_conveyance_allowance_total = $earned_conveyance_allowance_total + ($earned_conveyance_allowance * $mm);
		$earned_special_allowance_total = $earned_special_allowance_total + ($earned_special_allowance * $mm);
		$child_edu_allowance_total = $child_edu_allowance_total + ($child_edu_allowance * $mm);
		$otherincome_total = $otherincome_total + ($otherincome * $mm);
		
		$this->mViewData['earned_basic_total'] = $earned_basic_total;
		$this->mViewData['earned_hra_total'] = $earned_hra_total;
		$this->mViewData['earned_medical_allowance_total'] = $earned_medical_allowance_total;
		$this->mViewData['earned_conveyance_allowance_total'] = $earned_conveyance_allowance_total;
		$this->mViewData['earned_special_allowance_total'] = $earned_special_allowance_total;
		$this->mViewData['child_edu_allowance_total'] = $child_edu_allowance_total;
		$this->mViewData['otherincome_total'] = $otherincome_total;
		
		
		$this->mViewData['empInfo'] = $empInfo;
		$count = COUNT($empInfo);
		$this->mViewData['count'] = $count;
		//echo $count; exit;
		if($month >= 4){
			$sfyear="01/04/".$year; 
			$efyear="31/03/".($year+1); 
		}else{
			$efyear="31/03/".$year;  
			$sfyear="01/04/".($year-1);                   
		}

		$res_arr = $this->Accounts_model->get_other_incomes_details($loginID, $fyear); 
		$this->mViewData['res_arr'] = $res_arr;              
		//Template view 
		$this->render('accounts_help_desk/final_tax_computation_sheet_result_view', 'empty', $this->mViewData);
		$this->load->view('script/accounts/js/tax_calculation', $this->mViewData);
		
		
		
		
		
		
		
	}
	
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
	
	
	public function reimbursement()
	{ 
		$this->mViewData['pageTitle'] = 'Reimbursement';
		//Template view 
		$this->render('accounts_help_desk/reimbursement_view', 'full_width', $this->mViewData); 
	}
}
