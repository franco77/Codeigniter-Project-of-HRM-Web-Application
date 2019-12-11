<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| CI Bootstrap 3 Configuration
| -------------------------------------------------------------------------
| This file lets you define default values to be passed into views 
| when calling MY_Controller's render() function. 
| 
| See example and detailed explanation from:
| 	/application/config/ci_bootstrap_example.php
*/

$config['ci_bootstrap'] = array(

	// Site name
	'site_name' => 'POLOSOFT TECHNOLOGIES Pvt. Ltd.',

	// Default page title prefix
	'page_title_prefix' => '',

	// Default page title
	'page_title' => '',

	// Default meta data
	'meta_data'	=> array(
		'author'		=> '',
		'description'	=> '',
		'keywords'		=> ''
	),
	
	// Default scripts to embed at page head or end
	'scripts' => array(
		'head'	=> array(
			'assets/dist/admin/adminlte.min.js',
			'assets/dist/admin/lib.min.js',
			'assets/dist/admin/app.min.js'
		),
		'foot'	=> array(
		),
	),

	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'assets/dist/admin/adminlte.min.css',
			'assets/dist/admin/lib.min.css',
			'assets/dist/admin/app.min.css'
		)
	),

	// Default CSS class for <body> tag
	'body_class' => '',
	
	// Multilingual settings
	'languages' => array(
	),
	
	// Menu items
	'menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> '',
			'icon'		=> 'fa fa-home',
		),
		'user' => array(
			'name'		=> 'Users',
			'url'		=> 'user',
			'icon'		=> 'fa fa-user',
			'children'  => array(
				'List'			=> 'user',
				'Create'		=> 'user/create',
				'User Groups'	=> 'user/group',
			)
		),
		'timesheet' => array(
			'name'		=> 'Time sheet',
			'url'		=> 'timesheet',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array(
				'Attendance'				=> 'timesheet',
				'Regularise request'		=> 'timesheet/regularise_request',
				'Leave Application'			=> 'timesheet/leave_application',
				'Leave Carry Forward'		=> 'timesheet/leave_carry_forward',
				'leave_credited_history'	=> 'timesheet/leave_credited_history',
				'leave_info'				=> 'timesheet/leave_info',
				'leave_type'				=> 'timesheet/leave_type',
				'leave_master'				=> 'timesheet/leave_master',				
			)
		), 
		'master' => array(
			'name'		=> 'Master',
			'url'		=> 'master',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array(
				'Master Paroll Access'		=> 'master/master_paroll_access',
				'Country'					=> 'master/country',
				'State'						=> 'master/state',
				'Location'					=> 'master/location',
				'Department'				=> 'master/department',
				'Designation'				=> 'master/designation',
				'Skills'					=> 'master/skills',
				'Grade'						=> 'master/grade',
				'Level'						=> 'master/level',
				'Education'					=> 'master/education',
				'Specialization'			=> 'master/specialization',
				'Board university'			=> 'master/board_university',
				'Experience'				=> 'master/experience',
				'Joining_kit'				=> 'master/joining_kit',
				'Requirement'				=> 'master/requirement',
				'Define hod'				=> 'master/define_hod',
				'Reason of separation'		=> 'master/reason_of_separation',
				'Source of hire'			=> 'master/source_of_hire',
				'Bank'						=> 'master/bank',
				'Define miscellaneous'		=> 'master/define_miscellaneous',
				'Email template master'		=> 'master/email_template_master',
				'Email category'			=> 'master/email_category',
			)
		),
		'employee_management' => array(
			'name'		=> 'Employee Management',
			'url'		=> 'employee_management',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Employee Listing'		=> 'employee_management/profile_listing',
				'Create New Employee'	=> 'employee_management/create_new_profile',
				'Reset Password'		=> 'employee_management/reset_password',
				'Employee Vintage'		=> 'employee_management/employee_vintage',
				'Import Data'			=> 'employee_management/import_data',
				'Report'				=> 'employee_management/report',
				'View/Download Resume'	=> 'employee_management/download_resume',				
			)
		),
		'attendance_entry' => array(
			'name'		=> 'Attendance Entry',
			'url'		=> 'attendance_entry',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Biometric Entry'				=> 'attendance_entry/biometric_entry',
				'LWH Report'					=> 'attendance_entry/lwh_report',
				'Employee Attendance Summary'	=> 'attendance_entry/employee_attendance_summary', 
			)
		),
		'Payroll_management' => array(
			'name'		=> 'Payroll Management',
			'url'		=> 'payroll_management',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Multiple Allowance/deduction'	=> 'payroll_management/allowance_deduction_list',
				'Generate Salary'				=> 'payroll_management/generate_salary',
				'Salary Slip'					=> 'payroll_management/salary_slip',
				'Mail Salary Slip'				=> 'payroll_management/mail_salary_slip',
				'Payroll Report'				=> 'payroll_management/payroll_report',
				'Increment Report'				=> 'payroll_management/increment_report',
				'EPF Report'					=> 'payroll_management/epf_report',
				'ESI Report'					=> 'payroll_management/esi_report',
				'CTC Graph'						=> 'payroll_management/ctc_graph',
				'Incentive'						=> 'payroll_management/incentive',
				'Generate Incentive'			=> 'payroll_management/generate_incentive',
			)
		),
		'benifit_administration' => array(
			'name'		=> 'Benifit Administration',
			'url'		=> 'benifit_administration',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Perfomance Benifit'			=> 'benifit_administration/performance_benifit',
				'Attendance Benifit'			=> 'benifit_administration/attendance_benifit',
				'Add Extra Hrs'					=> 'benifit_administration/add_extra_hrs', 
			)
		),
		'leave' => array(
			'name'		=> 'Leave',
			'url'		=> 'leave',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Leave Provision'		=> 'leave/leave_provision',
				'Employee Leave Info'	=> 'leave/employee_leave_info',
				'Leave Status Info'		=> 'leave/leave_status_info',
				'Late Comming Info'		=> 'leave/late_comming_info',
				'Absent Info'			=> 'leave/absent_info',
			)
		),
		'expenses_reimbrusements' => array(
			'name'		=> 'Expenses Reimbrusements',
			'url'		=> 'expenses_reimbrusements',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Reimbursement'				=> 'expenses_reimbrusements/reimbursement',
				'Reimbursement Report'		=> 'expenses_reimbrusements/reimbursement_report',
				'Gratuity'					=> 'expenses_reimbrusements/gratuity',
				'Bonus'						=> 'expenses_reimbrusements/bonus',
				'F&F'						=> 'expenses_reimbrusements/F_F', 
			)
		),
		'loans' => array(
			'name'		=> 'Loans',
			'url'		=> 'loans',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Loan/Advance'				=> 'loans/loan_advance',
				'Loan Applied'				=> 'loans/loan_applied',
				'Advance Applied'			=> 'loans/advance_applied',
				'Loan/Advance Report'		=> 'loans/loan_advance_report', 
			) 
		),
		'recruitment' => array(
			'name'		=> 'Recruitment',
			'url'		=> 'recruitment',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Resume Databank'					=> 'recruitment/resume_databank',
				'Shortisted Candidate'				=> 'recruitment/shortisted_candidate',
				'Interview Scheduled Candidate'		=> 'recruitment/interview_scheduled_candidate',
				'Interview Rating'					=> 'recruitment/interview_rating',
				'Placement Consultant'				=> 'recruitment/placement_consultant',
				'Manpower Requisition'				=> 'recruitment/manpower_requisition',
				'Recruitment Report'				=> 'recruitment/recruitment_report', 
			)
		),
		'performance_management' => array(
			'name'		=> 'Performance Management',
			'url'		=> 'performance_management',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Probation Assessment'			=> 'performance_management/probation_assessment',
				'Mid-Year Review'				=> 'performance_management/mid_year_review',
				'Mid-Year Review Report'		=> 'performance_management/mid_year_review_report',
				'Annual Appraisal'				=> 'performance_management/annual_appraisal',
				'Annual Appraisal Report'		=> 'performance_management/Annual_appraisal_report', 
			)
		),
		'events' => array(
			'name'		=> 'Events',
			'url'		=> 'events',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Retired Employee Details'		=> 'events/retired_employee_details',
				'Terminated Employee Details'	=> 'events/terminated_employee_details',
				'Transfer Employee Details'		=> 'events/transfer_employee_details',
				'Employees On Hold'				=> 'events/employees_on_hold',
				'Contract based Employees'		=> 'events/contract_based_mployees',
				'Ex Employee Details'			=> 'events/ex_employee_details',
				'General Alert'					=> 'events/general_alert', 
			)
		),
		'utilities' => array(
			'name'		=> 'Utilities',
			'url'		=> 'utilities',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Birth Day Reminder'				=> 'utilities/birth_day_reminder',
				'Online Room Booking	'			=> 'utilities/online_room_booking	', 
			)
		),
		'hr_information_portal_module' => array(
			'name'		=> 'Hr information portal module',
			'url'		=> 'hr_information_portal_module',
			'icon'		=> 'fa fa-step-forward',
			'children'  => array( 
				'Directors Message'				=> 'hr_information_portal_module/directors_message',
				'HR Policies'					=> 'hr_information_portal_module/hr_policies',
				'List of Holidays'				=> 'hr_information_portal_module/list_of_holidays',
				'In House Monthly Magazine'		=> 'hr_information_portal_module/in_house_monthly_magazine',
				'List of Contact Details'		=> 'hr_information_portal_module/list_of_contact_details',
				'List of Offices'				=> 'hr_information_portal_module/list_of_offices',
				'News/Circulars'				=> 'hr_information_portal_module/news_circulars',
			)
		),
		'account_master' => array(
			'name' =>'Account Master',
			'url'  =>'account_master',
			'icon' =>'fa fa-step-forward',
			'children' => array(
				'Define Pt Slab' 			=> 'account_master/define_pt_slab',
				'Define Income tax Slab' 	=> 'account_master/define_income_tax_slab',
				'Tax Dedudction Limit' 		=> 'account_master/tax_deduction_limit'
			)
		), 
		'panel' => array(
			'name'		=> 'Admin Panel',
			'url'		=> 'panel',
			'icon'		=> 'fa fa-cog',
			'children'  => array(
				'Admin Users'			=> 'panel/admin_user',
				'Create Admin User'		=> 'panel/admin_user_create',
				'Admin User Groups'		=> 'panel/admin_user_group',
			)
		),
		'util' => array(
			'name'		=> 'Utilities',
			'url'		=> 'util',
			'icon'		=> 'fa fa-cogs',
			'children'  => array(
				'Database Versions'		=> 'util/list_db',
			)
		),
		'hall_of_fame' => array(
			'name'		=> 'Hall Of Fame',
			'url'		=> 'hall_of_fame/get_hall_of_fame',
			'icon'		=> 'fa fa-cogs', 
		),
		'hall_of_fame' => array(
			'name'		=> 'Cricket',
			'url'		=> 'cricket/cricket_all',
			'icon'		=> 'fa fa-cogs', 
		),
		'logout' => array(
			'name'		=> 'Sign Out',
			'url'		=> 'panel/logout',
			'icon'		=> 'fa fa-sign-out',
		)
	),

	// Login page
	'login_url' => 'admin/login',

	// Restricted pages
	'page_auth' => array(
		'user/create'										=> array('administrator','hr'),
		'user/group'										=> array('administrator','hr'),
		'user'												=> array('administrator','hr'),
		'panel'												=> array('administrator','hr'),
		'panel/admin_user'									=> array('administrator','hr'),
		'panel/admin_user_create'							=> array('administrator','hr'),
		'panel/admin_user_group'							=> array('administrator','hr'),
		'util'												=> array('administrator','hr'),
		'util/list_db'										=> array('administrator','hr'),
		'util/backup_db'									=> array('administrator','hr'),
		'util/restore_db'									=> array('administrator','hr'),
		'util/remove_db'									=> array('administrator','hr'),
		'timesheet' 										=> array('administrator','hr'),
		'timesheet/regularise_request'  					=> array('administrator','hr'),
		'timesheet/leave_application' 						=> array('administrator','hr'),
		'timesheet/leave_carry_forward' 					=> array('administrator','hr'),
		'timesheet/leave_credited_history' 					=> array('administrator','hr'),
		'timesheet/leave_info'     							=> array('administrator','hr'),
		'timesheet/leave_type'     							=> array('administrator','hr'),
		'timesheet/leave_master'   							=> array('administrator','hr'),
		'master'											=> array('administrator','hr'),
		'master/master_paroll_access'						=> array('administrator','hr'),
		'master/country'									=> array('administrator','hr'),
		'master/state'										=> array('administrator','hr'),
		'master/location'									=> array('administrator','hr'),
		'master/department'									=> array('administrator','hr'),
		'master/designation'								=> array('administrator','hr'),
		'master/skills'										=> array('administrator'),
		'master/grade'										=> array('administrator'),
		'master/level'										=> array('administrator'),
		'master/education'									=> array('administrator'),
		'master/specialization'								=> array('administrator'),
		'master/board_university'							=> array('administrator'),
		'master/experience'									=> array('administrator'),
		'master/joining_kit'								=> array('administrator'),
		'master/requirement'								=> array('administrator'),
		'master/define_hod'									=> array('administrator'),
		'master/reason_of_separation'						=> array('administrator'),
		'master/source_of_hire'								=> array('administrator'),
		'master/bank' 										=> array('administrator'),
		'master/define_miscellaneous'						=> array('administrator'),
		'master/email_template_master'						=> array('administrator'),
		'master/email_category' 							=> array('administrator'),
		'employee_management'  								=> array('administrator'),
		'employee_management/profile_listing'  				=> array('administrator'),
		'employee_management/create_new_profile' 			=> array('administrator'),
		'employee_management/reset_password' 				=> array('administrator'),
		'employee_management/employee_vintage' 				=> array('administrator'),
		'employee_management/import_data' 					=> array('administrator'),
		'employee_management/report' 						=> array('administrator'),
		'employee_management/download_resume' 				=> array('administrator'),
		'attendance_entry' 									=> array('administrator'),
		'attendance_entry/biometric_entry' 					=> array('administrator'),
		'attendance_entry/lwh_report' 						=> array('administrator'),
		'attendance_entry/employee_attendance_summary' 		=> array('administrator'),
		'payroll_management' 								=> array('administrator'),
		'payroll_management/allowance_deduction_list' 		=> array('administrator'),
		'payroll_management/generate_salary' 				=> array('administrator'),
		'payroll_management/salary_slip' 					=> array('administrator'),
		'payroll_management/mail_salary_slip' 				=> array('administrator'),
		'payroll_management/payroll_report' 				=> array('administrator'),
		'payroll_management/increment_report' 				=> array('administrator'),
		'payroll_management/epf_report' 					=> array('administrator'),
		'payroll_management/esi_report' 					=> array('administrator'),
		'payroll_management/ctc_graph' 						=> array('administrator'),
		'payroll_management/incentive' 						=> array('administrator'),
		'payroll_management/generate_incentive' 			=> array('administrator'),
		'benifit_administration' 							=> array('administrator'),
		'benifit_administration/performance_benifit' 		=> array('administrator'),
		'benifit_administration/attendance_benifit' 		=> array('administrator'),
		'benifit_administration/add_extra_hrs' 				=> array('administrator'),
		'leave' 											=> array('administrator'),
		'leave/leave_provision' 							=> array('administrator'),
		'leave/employee_leave_info'  						=> array('administrator'),
		'leave/leave_status_info' 							=> array('administrator'),
		'leave/late_comming_info' 							=> array('administrator'),
		'leave/absent_info' 								=> array('administrator'),
		'expenses_reimbrusements' 							=> array('administrator'),
		'expenses_reimbrusements/reimbursement' 			=> array('administrator'),
		'expenses_reimbrusements/reimbursement_report' 		=> array('administrator'),
		'expenses_reimbrusements/gratuity' 					=> array('administrator'),
		'expenses_reimbrusements/bonus' 					=> array('administrator'),
		'expenses_reimbrusements/F_F'  						=> array('administrator'),
		'loans' 											=> array('administrator'),
		'loans/loan_advance' 								=> array('administrator'),
		'loans/loan_applied' 								=> array('administrator'),
		'loans/advance_applied' 							=> array('administrator'),
		'loans/loan_advance_report' 						=> array('administrator'),
		'recruitment' 										=> array('administrator'),
		'recruitment/resume_databank' 						=> array('administrator'),
		'recruitment/shortisted_candidate' 					=> array('administrator'),
		'recruitment/interview_scheduled_candidate' 		=> array('administrator'),
		'recruitment/interview_rating' 						=> array('administrator'),
		'recruitment/placement_consultant' 					=> array('administrator'),
		'recruitment/manpower_requisition' 					=> array('administrator'),
		'recruitment/recruitment_report' 					=> array('administrator'),
		'performance_management' 							=> array('administrator'),
		'performance_management/probation_assessment' 		=> array('administrator'),
		'performance_management/mid-year_review' 			=> array('administrator'),
		'performance_management/mid-year_review_report' 	=> array('administrator'),
		'performance_management/annual_appraisal' 			=> array('administrator'),
		'performance_management/Annual_appraisal_report' 	=> array('administrator'),
		'events' 											=> array('administrator'),
		'events/retired_employee_details' 					=> array('administrator'),
		'events/terminated_employee_details' 				=> array('administrator'),
		'events/transfer_employee_details' 					=> array('administrator'),
		'events/employees_on_hold' 							=> array('administrator'),
		'events/contract_based_mployees' 					=> array('administrator'),
		'events/ex-employee_details' 						=> array('administrator'),
		'events/general_alert' 								=> array('administrator'),
		'utilities' 										=> array('administrator'),		
		'utilities/birth_day_reminder' 						=> array('administrator'),
		'utilities/online_room_booking' 					=> array('administrator'),
		'hr_information_portal_module' 								=> array('administrator'),
		'hr_information_portal_module/directors_message' 			=> array('administrator'),
		'hr_information_portal_module/hr_policies' 					=> array('administrator'),
		'hr_information_portal_module/list_of_holidays' 			=> array('administrator'),
		'hr_information_portal_module/in_house_monthly_magazine' 	=> array('administrator'),
		'hr_information_portal_module/list_of_contact_details' 		=> array('administrator'),
		'hr_information_portal_module/list_of_offices' 				=> array('administrator'),
		'hr_information_portal_module/news_circulars' 				=> array('administrator'), 
		
	),

	// AdminLTE settings
	'adminlte' => array(
		'body_class' => array(
				'administrator'	=> 'skin-red',
				'hr'			=> 'skin-purple',
				'account'		=> 'skin-black',
				'management'	=> 'skin-blue',
		)
	),

	// Useful links to display at bottom of sidemenu
	'useful_links' => array(
		array(
			'auth'		=> array('administrator', 'admin', 'manager', 'staff'),
			'name'		=> 'Frontend Website',
			'url'		=> '',
			'target'	=> '_blank',
			'color'		=> 'text-aqua'
		),
		array(
			'auth'		=> array('administrator', 'admin'),
			'name'		=> 'API Site',
			'url'		=> 'api',
			'target'	=> '_blank',
			'color'		=> 'text-orange'
		),
		/*array(
			'auth'		=> array('administrator', 'admin', 'manager', 'staff'),
			'name'		=> 'Github Repo',
			'url'		=> CI_BOOTSTRAP_REPO,
			'target'	=> '_blank',
			'color'		=> 'text-green'
		),*/
	),

	// Debug tools
	'debug' => array(
		'view_data'	=> FALSE,
		'profiler'	=> FALSE
	),
);

/*
| -------------------------------------------------------------------------
| Override values from /application/config/config.php
| -------------------------------------------------------------------------
*/
$config['sess_cookie_name'] = 'ci_session_admin';