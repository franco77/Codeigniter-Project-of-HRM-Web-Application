<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_model extends CI_Model { 
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	public function get_proffessional_tax_slab() 
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fy = date('Y');
		}
		else{
			$fy = $y - 1;
		}
		$sql = $this->db->query("SELECT * FROM pt_slab_master WHERE status = 'Y' AND fyear = '".$fy."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_proffessional_tax_slab_fy($fyear) 
	{
		$sql = $this->db->query("SELECT * FROM pt_slab_master WHERE status = 'Y' AND fyear = '".$fyear."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_income_tax_slab_define() 
	{	 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fy = date('Y');
		}
		else{
			$fy = $y - 1;
		}
		$query = $this->db->query("SELECT * FROM it_slab_master WHERE status = 'Y'  AND fyear = '".$fy."'"); 
		$result = $query->result_array();  
		return $result; 
	}
	
	public function get_income_tax_slab_define_fy($fyear) 
	{
		$sql = $this->db->query("SELECT * FROM it_slab_master WHERE status = 'Y' AND fyear = '".$fyear."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_tax_deduction_limit_define($fyear ='')
	{ 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fy = date('Y');
		}
		else{
			$fy = $y - 1;
		}
		if($fyear !=""){
			$fy = $fyear;
		}
		$query = $this->db->query("SELECT * FROM `income_tax_limit` WHERE fyear = '".$fy."'"); 
		$result = $query->result_array();  
		return $result;
	}
	
	public function get_tax_deduction_limit_define_fy($fyear) 
	{
		$sql = $this->db->query("SELECT * FROM income_tax_limit WHERE fyear = '".$fyear."'"); 
		$result = $sql->result_array(); 
		return $result; 
	}
	
	public function get_income_tax_declaration_estimation_check($fyear)
	{
		$con = " AND fyear='".$fyear."'";
		$sql = $this->db->query("SELECT tid FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND type='E' $con ");
		$result = $sql->result_array();
		
		return $result; 
	}
	
	public function get_income_tax_declaration_estimation($tid)
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if($tid !=""){
			$con = " AND tid='".$tid."'";
			$sql = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND type='E' $con ");
			$result = $sql->result_array();
		}
		else{
			$con = " AND fyear = '".$fyear."'";
			$result = array();
		}
		
		return $result; 
	}
	
	public function get_income_tax_declaration_final($tid)
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if($tid !=""){
			$con = " AND tid='".$tid."'";
			$sql = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND type='F' $con ");
			$result = $sql->result_array();
		}
		else{
			$con = " AND fyear = '".$fyear."'";
			$sql = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND type='F' $con ");
			$result = $sql->result_array();
			//$result = array();
			//echo "SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND type='F' $con ";
		}
		
		return $result; 
	}
	
	public function get_income_tax_declaration_estimation_emp($tid,$loginID)
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if($tid !=""){
			$con = " AND tid='".$tid."'";
			$sql = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$loginID."' AND type='E' $con ");
			$result = $sql->result_array();
		}
		else{
			$con = " AND fyear = '".$fyear."'";
			$result = array();
		}
		
		return $result; 
	}
	
	public function get_income_tax_declaration_final_emp($tid,$loginID)
	{
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		if($tid !=""){
			$con = " AND tid='".$tid."'";
			$sql = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$loginID."' AND type='F' $con ");
			$result = $sql->result_array();
		}
		else{
			$con = " AND fyear = '".$fyear."'";
			$result = array();
		}
		
		return $result; 
	}
	
	public function update_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$tid)
	{
		//$query = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'"); 
		$query = $this->db->query("UPDATE income_tax_declaration_estimation
				SET 
		rent_paid_by_employee  						= '".$rent_paid_by_employee."',
		eligible_rent_paid_by_employee 				= '".$eligible_rent_paid_by_employee."',
		conv_allowance 								= '".$conv_allowance."',
		eligible_conv_allowance 					= '".$eligible_conv_allowance."',
		childreneducationalallowance 				= '".$childreneducationalallowance."',
		eligible_childreneducationalallowance 		= '".$eligible_childreneducationalallowance."',
		medicalexpensesperannum 					= '".$medicalexpensesperannum."',
		eligible_medicalexpensesperannum 			= '".$eligible_medicalexpensesperannum."',
		leavetravelconcession 						= '".$leavetravelconcession."',
		eligible_leavetravelconcession 				= '".$eligible_leavetravelconcession."',
		entertainment_allowance 					= '".$entertainment_allowance."',
		eligible_entertainment_allowance 			= '".$eligible_entertainment_allowance."',
		professional_tax 							= '".$professional_tax."',
		eligible_professional_tax 					= '".$eligible_professional_tax."',
		self_occupied_property 						= '".$self_occupied_property."',
		eligible_self_occupied_property 			= '".$eligible_self_occupied_property."',
		let_our_rented_property 					= '".$let_our_rented_property."',
		eligible_let_our_rented_property 			= '".$eligible_let_our_rented_property."',
		contribution_provident_fund 				= '".$contribution_provident_fund."',
		lic 										= '".$lic."',
		public_provident_fund 						= '".$public_provident_fund."',
		nsc 										= '".$nsc."',
		childreneducationfee 						= '".$childreneducationfee."',
		mutualfund_or_uti 							= '".$mutualfund_or_uti."',
		contribution_notified_pension_fund 			= '".$contribution_notified_pension_fund."',
		ulip 										= '".$ulip."',
		postofficetax 								= '".$postofficetax."',
		elss 										= '".$elss."',
		housingloanprincipal 						= '".$housingloanprincipal."',
		fixeddeposit 								= '".$fixeddeposit."',
		any_other_tax 								= '".$any_other_tax."',
		total_deduction80c 							= '".$total_deduction80c."',
		eligible_total_deduction80c 				= '".$eligible_total_deduction80c."',
		deduction_under_80CCD						= '".$deduction_under_80CCD."',
		eligible_deduction_under_80CCD 				= '".$eligible_deduction_under_80CCD."',
		deduction_under_80D_selffamily 				= '".$deduction_under_80D_selffamily."',
		eligible_deduction_under_80D_selffamily 	= '".$eligible_deduction_under_80D_selffamily."',
		deduction_under_80D_parents 				= '".$deduction_under_80D_parents."',
		eligible_deduction_under_80D_parents 		= '".$eligible_deduction_under_80D_parents."',
		deduction_incase_senior_citizen 			= '".$deduction_incase_senior_citizen."',
		eligible_deduction_incase_senior_citizen 	= '".$eligible_deduction_incase_senior_citizen."',
		total_deduction_incase_senior_citizen 		= '".$total_deduction_incase_senior_citizen."',
		eligible_total_deduction_incase_senior_citizen = '".$eligible_total_deduction_incase_senior_citizen."',
		normal_disability 							= '".$normal_disability."',
		eligible_normal_disability 					= '".$eligible_normal_disability."',
		severe_disability 							= '".$severe_disability."',
		eligible_severe_disability 					= '".$eligible_severe_disability."',
		total_deduction_80dd 						= '".$total_deduction_80dd."',
		total_eligible_deduction_80dd 				= '".$total_eligible_deduction_80dd."',
		medical_treatment_normal_case 				= '".$medical_treatment_normal_case."',
		eligible_medical_treatment_normal_case 		= '".$eligible_medical_treatment_normal_case."',
		senior_citizen_60 							= '".$senior_citizen_60."',
		eligible_senior_citizen_60 					= '".$eligible_senior_citizen_60."',
		super_senior_citizen_80 					= '".$super_senior_citizen_80."',
		eligible_super_senior_citizen_80 			= '".$eligible_super_senior_citizen_80."',
		total_deduction_80ddb 						= '".$total_deduction_80ddb."',
		total_eligible_deduction_80ddb 				= '".$total_eligible_deduction_80ddb."',
		interest_loan_higher_education_80e 			= '".$interest_loan_higher_education_80e."',
		eligible_interest_loan_higher_education_80e = '".$eligible_interest_loan_higher_education_80e."',
		interest_home_loan_80ee 					= '".$interest_home_loan_80ee."',
		eligible_interest_home_loan_80ee 			= '".$eligible_interest_home_loan_80ee."',
		actual_donation_80g 						= '".$actual_donation_80g."',
		eligible_actual_donation_80g 				= '".$eligible_actual_donation_80g."',
		deduction_under_80U_noraml_disability 		= '".$deduction_under_80U_noraml_disability."',
		eligible_deduction_under_80U_noraml_disability = '".$eligible_deduction_under_80U_noraml_disability."',
		deduction_under_80U_severe_disability 		= '".$deduction_under_80U_severe_disability."',
		eligible_deduction_under_80U_severe_disability = '".$eligible_deduction_under_80U_severe_disability."',
		total_deduction_under_80U 					= '".$total_deduction_under_80U."',
		eligible_deduction_under_80U 				= '".$eligible_deduction_under_80U."',
		apply_date =NOW(),
		ac_status = '0'
		WHERE tid='".$tid."' ");
	}
	
	
	public function update_income_tax_declaration_estimation_by_accounts($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear,$tid,$loginID)
	{
		//echo $contribution_provident_fund;exit;
		//$query = $this->db->query("SELECT * FROM `income_tax_declaration_estimation` WHERE login_id='".$this->session->userdata('user_id')."' AND DATE_FORMAT(apply_date,'%Y')='".date('Y')."'"); 
		$query = $this->db->query("UPDATE income_tax_declaration_estimation
				SET 
		rent_paid_by_employee  						= '".$rent_paid_by_employee."',
		eligible_rent_paid_by_employee 				= '".$eligible_rent_paid_by_employee."',
		conv_allowance 								= '".$conv_allowance."',
		eligible_conv_allowance 					= '".$eligible_conv_allowance."',
		childreneducationalallowance 				= '".$childreneducationalallowance."',
		eligible_childreneducationalallowance 		= '".$eligible_childreneducationalallowance."',
		medicalexpensesperannum 					= '".$medicalexpensesperannum."',
		eligible_medicalexpensesperannum 			= '".$eligible_medicalexpensesperannum."',
		leavetravelconcession 						= '".$leavetravelconcession."',
		eligible_leavetravelconcession 				= '".$eligible_leavetravelconcession."',
		entertainment_allowance 					= '".$entertainment_allowance."',
		eligible_entertainment_allowance 			= '".$eligible_entertainment_allowance."',
		professional_tax 							= '".$professional_tax."',
		eligible_professional_tax 					= '".$eligible_professional_tax."',
		self_occupied_property 						= '".$self_occupied_property."',
		eligible_self_occupied_property 			= '".$eligible_self_occupied_property."',
		let_our_rented_property 					= '".$let_our_rented_property."',
		eligible_let_our_rented_property 			= '".$eligible_let_our_rented_property."',
		contribution_provident_fund 				= '".$contribution_provident_fund."',
		lic 										= '".$lic."',
		public_provident_fund 						= '".$public_provident_fund."',
		nsc 										= '".$nsc."',
		childreneducationfee 						= '".$childreneducationfee."',
		mutualfund_or_uti 							= '".$mutualfund_or_uti."',
		contribution_notified_pension_fund 			= '".$contribution_notified_pension_fund."',
		ulip 										= '".$ulip."',
		postofficetax 								= '".$postofficetax."',
		elss 										= '".$elss."',
		housingloanprincipal 						= '".$housingloanprincipal."',
		fixeddeposit 								= '".$fixeddeposit."',
		any_other_tax 								= '".$any_other_tax."',
		total_deduction80c 							= '".$total_deduction80c."',
		eligible_total_deduction80c 				= '".$eligible_total_deduction80c."',
		deduction_under_80CCD						= '".$deduction_under_80CCD."',
		eligible_deduction_under_80CCD 				= '".$eligible_deduction_under_80CCD."',
		deduction_under_80D_selffamily 				= '".$deduction_under_80D_selffamily."',
		eligible_deduction_under_80D_selffamily 	= '".$eligible_deduction_under_80D_selffamily."',
		deduction_under_80D_parents 				= '".$deduction_under_80D_parents."',
		eligible_deduction_under_80D_parents 		= '".$eligible_deduction_under_80D_parents."',
		deduction_incase_senior_citizen 			= '".$deduction_incase_senior_citizen."',
		eligible_deduction_incase_senior_citizen 	= '".$eligible_deduction_incase_senior_citizen."',
		total_deduction_incase_senior_citizen 		= '".$total_deduction_incase_senior_citizen."',
		eligible_total_deduction_incase_senior_citizen = '".$eligible_total_deduction_incase_senior_citizen."',
		normal_disability 							= '".$normal_disability."',
		eligible_normal_disability 					= '".$eligible_normal_disability."',
		severe_disability 							= '".$severe_disability."',
		eligible_severe_disability 					= '".$eligible_severe_disability."',
		total_deduction_80dd 						= '".$total_deduction_80dd."',
		total_eligible_deduction_80dd 				= '".$total_eligible_deduction_80dd."',
		medical_treatment_normal_case 				= '".$medical_treatment_normal_case."',
		eligible_medical_treatment_normal_case 		= '".$eligible_medical_treatment_normal_case."',
		senior_citizen_60 							= '".$senior_citizen_60."',
		eligible_senior_citizen_60 					= '".$eligible_senior_citizen_60."',
		super_senior_citizen_80 					= '".$super_senior_citizen_80."',
		eligible_super_senior_citizen_80 			= '".$eligible_super_senior_citizen_80."',
		total_deduction_80ddb 						= '".$total_deduction_80ddb."',
		total_eligible_deduction_80ddb 				= '".$total_eligible_deduction_80ddb."',
		interest_loan_higher_education_80e 			= '".$interest_loan_higher_education_80e."',
		eligible_interest_loan_higher_education_80e = '".$eligible_interest_loan_higher_education_80e."',
		interest_home_loan_80ee 					= '".$interest_home_loan_80ee."',
		eligible_interest_home_loan_80ee 			= '".$eligible_interest_home_loan_80ee."',
		actual_donation_80g 						= '".$actual_donation_80g."',
		eligible_actual_donation_80g 				= '".$eligible_actual_donation_80g."',
		deduction_under_80U_noraml_disability 		= '".$deduction_under_80U_noraml_disability."',
		eligible_deduction_under_80U_noraml_disability = '".$eligible_deduction_under_80U_noraml_disability."',
		deduction_under_80U_severe_disability 		= '".$deduction_under_80U_severe_disability."',
		eligible_deduction_under_80U_severe_disability = '".$eligible_deduction_under_80U_severe_disability."',
		total_deduction_under_80U 					= '".$total_deduction_under_80U."',
		eligible_deduction_under_80U 				= '".$eligible_deduction_under_80U."',
		modified_date =NOW(),
		ac_status = '1'
		WHERE login_id='".$loginID."' AND tid='".$tid."' ORDER BY `tid` DESC LIMIT 1");
	}
	public function insert_income_tax_declaration_estimation($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear)
	{
		$query = $this->db->query("INSERT INTO income_tax_declaration_estimation
					(login_id, rent_paid_by_employee, eligible_rent_paid_by_employee, conv_allowance, eligible_conv_allowance, childreneducationalallowance, eligible_childreneducationalallowance, medicalexpensesperannum, eligible_medicalexpensesperannum, leavetravelconcession, eligible_leavetravelconcession, entertainment_allowance, eligible_entertainment_allowance, professional_tax, eligible_professional_tax, self_occupied_property, eligible_self_occupied_property, let_our_rented_property, eligible_let_our_rented_property, contribution_provident_fund, lic, public_provident_fund, nsc, childreneducationfee, mutualfund_or_uti, contribution_notified_pension_fund, ulip, postofficetax, elss, housingloanprincipal, fixeddeposit, any_other_tax, total_deduction80c, eligible_total_deduction80c, deduction_under_80CCD, eligible_deduction_under_80CCD, deduction_under_80D_selffamily, eligible_deduction_under_80D_selffamily, deduction_under_80D_parents, eligible_deduction_under_80D_parents, deduction_incase_senior_citizen, eligible_deduction_incase_senior_citizen, total_deduction_incase_senior_citizen, eligible_total_deduction_incase_senior_citizen, normal_disability, eligible_normal_disability, severe_disability, eligible_severe_disability, total_deduction_80dd, total_eligible_deduction_80dd, medical_treatment_normal_case, eligible_medical_treatment_normal_case, senior_citizen_60, eligible_senior_citizen_60, super_senior_citizen_80, eligible_super_senior_citizen_80, total_deduction_80ddb, total_eligible_deduction_80ddb, interest_loan_higher_education_80e, eligible_interest_loan_higher_education_80e, interest_home_loan_80ee, eligible_interest_home_loan_80ee, actual_donation_80g, eligible_actual_donation_80g, deduction_under_80U_noraml_disability, eligible_deduction_under_80U_noraml_disability, deduction_under_80U_severe_disability, eligible_deduction_under_80U_severe_disability, total_deduction_under_80U, eligible_deduction_under_80U,fmonth,fyear,finacial_year,apply_date,ac_status)
					VALUES ('".$this->session->userdata('user_id')."', '".$rent_paid_by_employee."','".$eligible_rent_paid_by_employee."','".$conv_allowance."', '".$eligible_conv_allowance."', '".$childreneducationalallowance."','".$eligible_childreneducationalallowance."', '".$medicalexpensesperannum."', '".$eligible_medicalexpensesperannum."', '".$leavetravelconcession."', '".$eligible_leavetravelconcession."', '".$entertainment_allowance."', '".$eligible_entertainment_allowance."', '".$professional_tax."', '".$eligible_professional_tax."', '".$self_occupied_property."', '".$eligible_self_occupied_property."', '".$let_our_rented_property."', '".$eligible_let_our_rented_property."', '".$contribution_provident_fund."', '".$lic."', '".$public_provident_fund."', '".$nsc."', '".$childreneducationfee."', '".$mutualfund_or_uti."', '".$contribution_notified_pension_fund."', '".$ulip."', '".$postofficetax."', '".$elss."','".$housingloanprincipal."', '".$fixeddeposit."', '".$any_other_tax."','".$total_deduction80c."', '".$eligible_total_deduction80c."', '".$deduction_under_80CCD."', '".$eligible_deduction_under_80CCD."', '".$deduction_under_80D_selffamily."', '".$eligible_deduction_under_80D_selffamily."', '".$deduction_under_80D_parents."', '".$eligible_deduction_under_80D_parents."', '".$deduction_incase_senior_citizen."', '".$eligible_deduction_incase_senior_citizen."', '".$total_deduction_incase_senior_citizen."', '".$eligible_total_deduction_incase_senior_citizen."', '".$normal_disability."', '".$eligible_normal_disability."', '".$severe_disability."', '".$eligible_severe_disability."', '".$total_deduction_80dd."', '".$total_eligible_deduction_80dd."', '".$medical_treatment_normal_case."', '".$eligible_medical_treatment_normal_case."', '".$senior_citizen_60."', '".$eligible_senior_citizen_60."', '".$super_senior_citizen_80."', '".$eligible_super_senior_citizen_80."', '".$total_deduction_80ddb."', '".$total_eligible_deduction_80ddb."', '".$interest_loan_higher_education_80e."', '".$eligible_interest_loan_higher_education_80e."', '".$interest_home_loan_80ee."', '".$eligible_interest_home_loan_80ee."', '".$actual_donation_80g."', '".$eligible_actual_donation_80g."', '".$deduction_under_80U_noraml_disability."', '".$eligible_deduction_under_80U_noraml_disability."', '".$deduction_under_80U_severe_disability."', '".$eligible_deduction_under_80U_severe_disability."', '".$total_deduction_under_80U."', '".$eligible_deduction_under_80U."','".date('m')."','".$fyear."','".date('Y')."',NOW(),'0')");
	}
	public function insert_income_tax_declaration_final($rent_paid_by_employee,$eligible_rent_paid_by_employee,$conv_allowance,$eligible_conv_allowance,$childreneducationalallowance,$eligible_childreneducationalallowance,$medicalexpensesperannum,$eligible_medicalexpensesperannum,$leavetravelconcession,$eligible_leavetravelconcession,$entertainment_allowance,$eligible_entertainment_allowance,$professional_tax,$eligible_professional_tax,$self_occupied_property,$eligible_self_occupied_property,$let_our_rented_property,$eligible_let_our_rented_property,$contribution_provident_fund,$lic,$public_provident_fund,$nsc,$childreneducationfee,$mutualfund_or_uti,$contribution_notified_pension_fund,$ulip,$postofficetax,$elss,$housingloanprincipal,$fixeddeposit,$any_other_tax,$total_deduction80c,$eligible_total_deduction80c,$deduction_under_80CCD,$eligible_deduction_under_80CCD,$deduction_under_80D_selffamily,$eligible_deduction_under_80D_selffamily,$deduction_under_80D_parents,$eligible_deduction_under_80D_parents,$deduction_incase_senior_citizen,$eligible_deduction_incase_senior_citizen,$total_deduction_incase_senior_citizen,$eligible_total_deduction_incase_senior_citizen,$normal_disability,$eligible_normal_disability,$severe_disability,$eligible_severe_disability,$total_deduction_80dd,$total_eligible_deduction_80dd,$medical_treatment_normal_case,$eligible_medical_treatment_normal_case,$senior_citizen_60,$eligible_senior_citizen_60,$super_senior_citizen_80,$eligible_super_senior_citizen_80,$total_deduction_80ddb,$total_eligible_deduction_80ddb,$interest_loan_higher_education_80e,$eligible_interest_loan_higher_education_80e,$interest_home_loan_80ee,$eligible_interest_home_loan_80ee,$actual_donation_80g,$eligible_actual_donation_80g,$deduction_under_80U_noraml_disability,$eligible_deduction_under_80U_noraml_disability,$deduction_under_80U_severe_disability,$eligible_deduction_under_80U_severe_disability,$total_deduction_under_80U,$eligible_deduction_under_80U,$fyear)
	{
		$query = $this->db->query("INSERT INTO income_tax_declaration_estimation
					(login_id, rent_paid_by_employee, eligible_rent_paid_by_employee, conv_allowance, eligible_conv_allowance, childreneducationalallowance, eligible_childreneducationalallowance, medicalexpensesperannum, eligible_medicalexpensesperannum, leavetravelconcession, eligible_leavetravelconcession, entertainment_allowance, eligible_entertainment_allowance, professional_tax, eligible_professional_tax, self_occupied_property, eligible_self_occupied_property, let_our_rented_property, eligible_let_our_rented_property, contribution_provident_fund, lic, public_provident_fund, nsc, childreneducationfee, mutualfund_or_uti, contribution_notified_pension_fund, ulip, postofficetax, elss, housingloanprincipal, fixeddeposit, any_other_tax, total_deduction80c, eligible_total_deduction80c, deduction_under_80CCD, eligible_deduction_under_80CCD, deduction_under_80D_selffamily, eligible_deduction_under_80D_selffamily, deduction_under_80D_parents, eligible_deduction_under_80D_parents, deduction_incase_senior_citizen, eligible_deduction_incase_senior_citizen, total_deduction_incase_senior_citizen, eligible_total_deduction_incase_senior_citizen, normal_disability, eligible_normal_disability, severe_disability, eligible_severe_disability, total_deduction_80dd, total_eligible_deduction_80dd, medical_treatment_normal_case, eligible_medical_treatment_normal_case, senior_citizen_60, eligible_senior_citizen_60, super_senior_citizen_80, eligible_super_senior_citizen_80, total_deduction_80ddb, total_eligible_deduction_80ddb, interest_loan_higher_education_80e, eligible_interest_loan_higher_education_80e, interest_home_loan_80ee, eligible_interest_home_loan_80ee, actual_donation_80g, eligible_actual_donation_80g, deduction_under_80U_noraml_disability, eligible_deduction_under_80U_noraml_disability, deduction_under_80U_severe_disability, eligible_deduction_under_80U_severe_disability, total_deduction_under_80U, eligible_deduction_under_80U,fmonth,fyear,finacial_year,apply_date,ac_status,type)
					VALUES ('".$this->session->userdata('user_id')."', '".$rent_paid_by_employee."','".$eligible_rent_paid_by_employee."','".$conv_allowance."', '".$eligible_conv_allowance."', '".$childreneducationalallowance."','".$eligible_childreneducationalallowance."', '".$medicalexpensesperannum."', '".$eligible_medicalexpensesperannum."', '".$leavetravelconcession."', '".$eligible_leavetravelconcession."', '".$entertainment_allowance."', '".$eligible_entertainment_allowance."', '".$professional_tax."', '".$eligible_professional_tax."', '".$self_occupied_property."', '".$eligible_self_occupied_property."', '".$let_our_rented_property."', '".$eligible_let_our_rented_property."', '".$contribution_provident_fund."', '".$lic."', '".$public_provident_fund."', '".$nsc."', '".$childreneducationfee."', '".$mutualfund_or_uti."', '".$contribution_notified_pension_fund."', '".$ulip."', '".$postofficetax."', '".$elss."','".$housingloanprincipal."', '".$fixeddeposit."', '".$any_other_tax."','".$total_deduction80c."', '".$eligible_total_deduction80c."', '".$deduction_under_80CCD."', '".$eligible_deduction_under_80CCD."', '".$deduction_under_80D_selffamily."', '".$eligible_deduction_under_80D_selffamily."', '".$deduction_under_80D_parents."', '".$eligible_deduction_under_80D_parents."', '".$deduction_incase_senior_citizen."', '".$eligible_deduction_incase_senior_citizen."', '".$total_deduction_incase_senior_citizen."', '".$eligible_total_deduction_incase_senior_citizen."', '".$normal_disability."', '".$eligible_normal_disability."', '".$severe_disability."', '".$eligible_severe_disability."', '".$total_deduction_80dd."', '".$total_eligible_deduction_80dd."', '".$medical_treatment_normal_case."', '".$eligible_medical_treatment_normal_case."', '".$senior_citizen_60."', '".$eligible_senior_citizen_60."', '".$super_senior_citizen_80."', '".$eligible_super_senior_citizen_80."', '".$total_deduction_80ddb."', '".$total_eligible_deduction_80ddb."', '".$interest_loan_higher_education_80e."', '".$eligible_interest_loan_higher_education_80e."', '".$interest_home_loan_80ee."', '".$eligible_interest_home_loan_80ee."', '".$actual_donation_80g."', '".$eligible_actual_donation_80g."', '".$deduction_under_80U_noraml_disability."', '".$eligible_deduction_under_80U_noraml_disability."', '".$deduction_under_80U_severe_disability."', '".$eligible_deduction_under_80U_severe_disability."', '".$total_deduction_under_80U."', '".$eligible_deduction_under_80U."','".date('m')."','".$fyear."','".date('Y')."',NOW(),'0','F')");
	}
	public function get_reporting_maanager()
	{ 
		$query = $this->db->query("SELECT i.full_name, i.email, iu.full_name as rfull_name,iu.email as remail, i.loginhandle FROM `internal_user` i LEFT JOIN `internal_user` iu ON iu.login_id=i.reporting_to WHERE i.login_id ='".$this->session->userdata('user_id')."'"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_pf($fyear = "")
	{
		$encypt = $this->config->item('masterKey');
		$con = "";
		if($fyear !=""){
			$con = " AND salary_year = '$fyear'";
		}
		$query = $this->db->query("SELECT MAX(sheet_id),MAX(AES_DECRYPT(pf, '".$encypt."')) AS pf
		FROM `salary_sheet`   
		WHERE login_id = '".$this->session->userdata('user_id')."' $con ORDER BY sheet_id DESC LIMIT 1"); 
		$result = $query->result_array();  
		return $result;
		
	}
	public function get_pf_emp($loginID)
	{
		$yr = date('Y');
		$m = date('m');
		if($m > 3){
			$minyr = $yr;
		}
		else{
			$minyr = $yr-1;
		}
		$maxyr = $minyr+1;
		
		$con = " AND ((salary_year = '$minyr' AND salary_month > '3') OR (salary_year = '$maxyr' AND salary_month < '4')) ";
		
		$encypt = $this->config->item('masterKey');
		$query = $this->db->query("SELECT sheet_id, (AES_DECRYPT(earned_pf, '".$encypt."')) AS pf
		FROM `salary_sheet`   
		WHERE login_id = '".$loginID."' $con ORDER BY sheet_id DESC LIMIT 1"); 
		$query1 = $this->db->query("SELECT SUM(AES_DECRYPT(earned_pf, '".$encypt."')) AS pf_total
		FROM `salary_sheet`   
		WHERE login_id = '".$loginID."' $con ORDER BY sheet_id DESC LIMIT 1"); 
		/* $query = $this->db->query("SELECT sheet_id,(AES_DECRYPT(pf, '".$encypt."')) AS pf
		FROM `salary_sheet`   
		WHERE login_id = '".$loginID."' $con ORDER BY sheet_id DESC");  */
		$result = $query->result_array();  //print_r($result);
		$result1 = $query1->result_array();  //print_r($result1);
		$pf_sum = 0;
		if(count($result)>0){
			$pf_total = $result1[0]['pf_total'];
			$pf = $result[0]['pf'];
			$m=date('m');
			$month=$m-1;
			if($month <= 3){
				$no = 3 - $month;
			}
			else{
				$no = (12 - $month) + 3;
			}
			$pf_sum = $pf_total + ( $pf * $no); 
		}
		//echo $pf_sum;
		return number_format((float)$pf_sum, 2, '.', '');
		
	}
	public function get_my_estimated_declaration()
	{  
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid,om.apply_date, om.remark, om.fyear FROM `internal_user` i LEFT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE  om.login_id = '".$this->session->userdata('user_id')."' AND om.type='E' ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_my_final_declaration()
	{  
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid,om.apply_date, om.remark, om.fyear FROM `internal_user` i LEFT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE  om.login_id = '".$this->session->userdata('user_id')."' AND om.type='F' ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_my_other_income()
	{  
		$query = $this->db->query("SELECT i.*,i.login_id as loginID, om.*, dp.*,d.* FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation LEFT JOIN `department` dp ON dp.dept_id = i.department RIGHT JOIN `other_income` om ON om.login_id = i.login_id  WHERE i.login_id = '".$this->session->userdata('user_id')."' ORDER BY om.id DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_estimated_declaration()
	{ 
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid, om.apply_date, om.modified_date, om.remark,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE i.user_status = '1' AND om.type = 'E'    AND om.fyear = '".$fyear."'  ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_estimated_declaration_fy($fyear)
	{  
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid, om.apply_date, om.modified_date, om.remark,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE i.user_status = '1' AND om.type = 'E'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_final_declaration()
	{  
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid, om.apply_date, om.modified_date, om.remark,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE i.user_status = '1' AND om.type = 'F'   AND om.fyear = '".$fyear."'  ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_final_declaration_fy($fyear)
	{  
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status, om.tid, om.apply_date, om.modified_date, om.remark,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id WHERE i.user_status = '1' AND om.type = 'F'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_estimated_tax_computation()
	{   
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status,om.tid, om.fyear, om.apply_date, om.modified_date, om.remark, dp.dept_id,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.user_status = '1'  AND om.type = 'E'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_estimated_tax_computation_fy($fyear)
	{
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status,om.tid, om.fyear, om.apply_date, om.modified_date, om.remark, dp.dept_id,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.user_status = '1'  AND om.type = 'E'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_final_tax_computation()
	{   
		$m = date('m');
		$y = date('Y');
		if($m >=4){
			$fyear = date('Y');
		}
		else{
			$fyear = $y - 1;
		}
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status,om.tid, om.fyear, om.apply_date, om.modified_date, om.remark, dp.dept_id,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.user_status = '1'  AND om.type = 'F'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_all_final_tax_computation_fy($fyear)
	{   
		$query = $this->db->query("SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, om.ac_status,om.tid, om.fyear, om.apply_date, om.modified_date, om.remark, dp.dept_id,om.fyear , (om.fyear + 1) as fyearS FROM `internal_user` i RIGHT JOIN `income_tax_declaration_estimation` om ON om.login_id = i.login_id LEFT JOIN `department` dp ON dp.dept_id = i.department WHERE i.user_status = '1'  AND om.type = 'F'  AND om.fyear = '".$fyear."'   ORDER BY om.tid DESC"); 
		$result = $query->result_array();  
		return $result;
	}
	public function get_active_employee()
	{
		$query = "SELECT i.login_id, i.email, CONCAT(i.name_first, ' ', i.name_last) AS name, i.loginhandle, d.desg_name, i.user_status FROM `internal_user` i LEFT JOIN `user_desg` d ON d.desg_id = i.designation WHERE i.login_id != '10010' AND i.user_status='1'";
		$result = $query->result_array();
		return $result;
	}
	public function check_load_advance_of_user($loginID,$year,$month)
	{
		$query = $this->db->query("SELECT * FROM `loan_advance` WHERE login_id='".$loginID."' AND lyear='".$year."' AND lmonth='".$month."'");
		$result = $query->result_array();
		return $result;
	}
	
	public function check_reimbursements($loginID,$year,$month)
	{
		$query = $this->db->query("SELECT * FROM reimbursements WHERE login_id='".$loginID."' AND reimbursement_year='".$year."' AND reimbursement_month='".$month."'");
		$result = $query->result_array();
		return $result;
	}
	
	public function get_emp_bonus()
	{
		$query = $this->db->query("SELECT login_id, loginhandle, full_name, join_date, user_status FROM `internal_user` WHERE login_id != '10010' AND user_status=2");
		$result = $query->result_array();
		return $result;
	}
	
	public function get_other_incomes($loginID)
	{
		$query = $this->db->query("SELECT * FROM `other_income` WHERE login_id = '".$loginID."'");
		$result = $query->result_array();
		return $result;
	}
	
	public function get_other_incomes_details($loginID, $fyear)
	{
		$fyear = $fyear+1;
		$query = $this->db->query("SELECT * FROM `other_income` WHERE login_id = '".$loginID."' AND DATE_FORMAT(apply_date, '%Y') = '".$fyear."'");
		$result = $query->result_array();
		return $result;
	}
	
	
	public function get_leave_provision() 
	{
		$yy = date("Y");
		$e_year = $yy+1; 
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
	
	
	public function update_loan_advance_approved_accounts($lid)
	{
		$data = array(
			'ac_status' => '1'
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
	
	public function update_loan_advance_rejected_accounts($lid, $reject_reason)
	{
		$data = array(
			'ac_status' => '2',
			'ac_rej_msg' => $reject_reason
		);
		$this->db->where('lid', $lid);
		$this->db->update('loan_advance_apply', $data);
		$result = 1;
		return($result);
	}
}