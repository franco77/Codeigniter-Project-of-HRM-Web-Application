<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_help_desk extends MY_Controller 
{   
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Accounts_model');
	}
	 
	public function professional_tax_slab()
	{
		$this->mViewData['pageTitle'] = 'Professional Tax Slab';
		//Template view
		$this->render('accounts_help_desk/professional_tax_slab_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/professional_tax_slab_script');
	}
	public function get_professional_tax_slab()
	{
		$result = $this->Accounts_model->get_proffessional_tax_slab(); 
		$this->json_response($result);
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
		$this->json_response($result);
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
		$this->json_response($result);
	}
	public function estimated_declaration_form()
	{
		$this->mViewData['pageTitle'] = 'Estimated Declaration Form';
		$mViewData = new stdClass();
		//load form helper and validation library
		$loginID = $this->input->get('id');
		if ($loginID == "") 
		{
			$loginID = $this->session->userdata('user_id');
			$actionURL = base_url();
		} 
		else
		{
			$actionURL = base_url().'?id='.$loginID;
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		if($this->input->post('btnAddMessage') == "SUBMIT" && $this->session->userdata('user_id') !='')
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
			//var_dump($result);
			if(count($result)>0)
			{
				//update 
				$update = $this->Accounts_model->update_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U);
				//var_dump($update);exit;
			}
			else
			{
				//insert 
				$insert = $this->Accounts_model->insert_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,										$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,														$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U);
				//var_dump($insert);exit;
			}
			//get reporting manager
			$repMgrInfo = $this->Accounts_model->get_reporting_maanager(); 
			//sending mail to reporting manager
			//pending
		} 
		//get pf from salary 
		$this->mViewData['get_pf'] = $this->Accounts_model->get_pf(); 
		$this->mViewData['empInfo'] = $this->Accounts_model->get_income_tax_declaration_estimation();
		
		$qry = "SELECT * FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."'";
		//echo $qry;
		$resform=$this->db->query($qry);
		$this->mViewData['result'] = $resform->result_array();
		//Template view 
		$this->render('accounts_help_desk/estimated_declaration_form_view', 'full_width', $this->mViewData);
		//$this->render('script/accounts/estimated_declaration_form', 'full_width', $this->mViewData);		
	}
	
	public function final_delcaration_form()
	{ 
		//Template view 
		$this->render('accounts_help_desk/final_delcaration_form_view', 'full_width', $this->mViewData); 
	} 
	public function my_estimated_declaration()
	{
		$this->mViewData['pageTitle'] = 'My Estimated Declaration';
		$this->mViewData['get_my_estimate'] = $this->Accounts_model->get_my_estimated_declaration();
		//Template view 
		$this->render('accounts_help_desk/my_estimated_declaration_view', 'full_width', $this->mViewData);
		$this->load->view('script/accounts/my_estimated_declaration_script');
	}
	public function get_my_estimated_declaration()
	{
		
	}
	public function my_final_declation()
	{ 
		//Template view 
		$this->render('accounts_help_desk/my_final_declaration_view', 'full_width', $this->mViewData); 
	}
	public function my_other_income()
	{
		$this->mViewData['pageTitle'] = 'My other Income';
		$this->mViewData['my_other_income'] = $this->Accounts_model->get_my_estimated_declaration(); 
		//Template view 
		$this->render('accounts_help_desk/my_other_income_view', 'full_width', $this->mViewData); 
	}
	public function my_other_income_details()
	{
		$this->mViewData['pageTitle'] = 'My Other Income details';
		//Query for select 
		$empSql = "SELECT i.*, om.*, dp.*,d.* FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `other_income` om ON om.login_id = i.login_id  WHERE i.login_id = '".$this->session->userdata('user_id')."'"; 
		$empRes = $this->db->query($empSql);
		$this->mViewData['empInfo'] = $empRes->result_array();  

		$repMgrSql = "SELECT login_id, full_name, email FROM `internal_user` WHERE login_id =(SELECT reporting_to FROM `internal_user` WHERE login_id = '".$this->session->userdata('user_id')."')";
		$repMgrRes = $this->db->query($repMgrSql);
		$this->mViewData['repMgrInfo'] = $repMgrRes->result_array(); 
		//Template view
		$this->render('accounts_help_desk/my_other_income_details_view', 'full_width', $this->mViewData); 
	}
	public function estimated_tax_compution_sheet()
	{
		$this->mViewData['pageTitle'] = 'Estimated tax computation sheet';
		//Template view 
		$this->render('accounts_help_desk/estimated_tax_compution_sheet_view', 'full_width', $this->mViewData); 
	}
	public function estimated_tax_compution_sheet_result()
	{
		$loginID = $this->session->userdata('user_id');
		$encypt = 'HHGSH362sgHHG';
		$userSql = "SELECT i.loginhandle AS myEmpCode, i.email AS myEmail, i.full_name AS myName, i.reporting_to, u.loginhandle AS rmEmpCode, u.email AS rmEmail, u.full_name AS rmName,i.join_date, i.`state_region2`, i.lwd_date,i.emp_type FROM `internal_user` i JOIN `internal_user` u ON i.reporting_to = u.login_id WHERE i.`login_id` = '".$loginID."'";
		$userRes = $this->db->query($userSql);
		$this->mViewData['userInfo'] = $userRes->result_array();
		//var_dump($this->mViewData['userInfo']);
		$userInfo = $userRes->result_array();
		
		if( $userInfo[0]['emp_type'] =='F')
		{
			$maxPL = $this->getMaxLeave($loginID, 'P');
			$maxSL = $this->getMaxLeave($loginID, 'S');
			$maxLeave = $maxPL + $maxSL;
			
			$leaveINFO = $this->getLeaveTaken($loginID, date("m"), date("Y"), 'A');
			$avlPL = $maxPL - $leaveINFO[0]['ob_pl'];
			$avlSL = $maxSL - $leaveINFO[0]['ob_sl'];
			$totAvlleave = $maxLeave > $leaveINFO[0]['ob_pl'] + $leaveINFO[0]['ob_sl'];
			
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
		   $avlPL = $maxPL - $leaveINFO[0]['ob_pl'];
		   $totAvlleave =$maxPL > $leaveINFO[0]['ob_pl'] ;
		}


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
				LEFT JOIN `salary_sheet` AS s ON s.login_id = i.login_id AND s.salary_year = itd.tax_year AND s.salary_month = itd.tax_month
				LEFT JOIN bank_master AS b ON b.bank_id = si.bank
				WHERE i.login_id = '$loginID' AND itd.tax_year='$year'";
				//WHERE i.login_id = '$loginID' AND itd.tax_year='$year' AND itd.tax_month='$month'";
		//echo $empSql; exit;
		$empRes = $this->db->query($empSql);
		$this->mViewData['count'] = count($empRes);
		//var_dump($this->mViewData['count']);
		$this->mViewData['empInfo'] = $empRes->result_array();
		///var_dump($this->mViewData['empInfo']);
		
		$sql="SELECT * FROM other_income where login_id='".$loginID."'";
		$res = $this->db->query($sql);  
		$this->mViewData['res_arr'] = $res->row_array(); 
		 
		//var_dump($this->mViewData['res_arr']); 

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
		$this->render('accounts_help_desk/estimated_tax_compution_sheet_result_view', 'empty', $this->mViewData); 
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
		if($type == 'C'){
			$leaveSQL = "SELECT `ob_pl`, `ob_sl` FROM `leave_info` WHERE `login_id` = '$userID' AND `month` = '$month' AND `year` = '$year'";
		}elseif($type == 'A'){
			$leaveSQL = "SELECT SUM(`ob_pl`) AS ob_pl, SUM(`ob_sl`) AS ob_sl FROM `leave_info` WHERE `login_id` = '$userID' AND `month` <= '$month' AND `year` = '$year'";
		}
		
		$leaveRES = $this->db->query($leaveSQL);
		$leaveNUM = count($leaveRES);
		
		if($leaveNUM > 0)
		{
			$leaveINFO = $leaveRES->result_array();
			if($leaveINFO[0]['ob_pl'] == "")
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
		//Template view 
		$this->render('accounts_help_desk/final_tax_computation_sheet_view', 'full_width', $this->mViewData); 
	}
	public function json_response($return)
	{
		ob_start("ob_gzhandler");
		header("Content-type: application/json");
		echo json_encode($return);
		ob_end_flush(); 
	}
}
