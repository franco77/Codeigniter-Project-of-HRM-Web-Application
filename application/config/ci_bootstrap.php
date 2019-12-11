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
	'site_name' => 'POLOSOFT',

	// Default page title prefix
	'page_title_prefix' => '',

	// Default page title
	'page_title' => 'POLOSOFT HRM::Home',

	// Default meta data
	'meta_data'	=> array(
		'author'		=> '',
		'description'	=> '',
		'keywords'		=> ''
	),

	// Default scripts to embed at page head or end
	'scripts' => array(
		'head'	=> array(
			'https://code.jquery.com/jquery-1.12.4.js',
			'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
			'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
			'assets/dist/frontend/angular.min.js',
			'assets/dist/frontend/ui-bootstrap-tpls-0.10.0.min.js',
			'assets/dist/frontend/multiselect.js'
		),
		'foot'	=> array(
			
		),
	),

	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'assets/dist/frontend/lib.min.css',
			'assets/dist/frontend/app.min.css', 
			'assets/dist/frontend/styles.css',
			'assets/dist/frontend/jquery-ui.css',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'
		)
	), 
	// Default CSS class for <body> tag
	'body_class' => '',
	
	// Multilingual settings
	'languages' => array(
		'default'		=> 'en',
		'autoload'		=> array('general'),
		'available'		=> array(
			'en' => array(
				'label'	=> 'English',
				'value'	=> 'english'
			),
			'zh' => array(
				'label'	=> '繁體中文',
				'value'	=> 'traditional-chinese'
			),
			'cn' => array(
				'label'	=> '简体中文',
				'value'	=> 'simplified-chinese'
			),
			'es' => array(
				'label'	=> 'Español',
				'value' => 'spanish'
			)
		)
	),
	// sidebar menu items
	'timesheet_sidebar' => array( 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Yearly'	=> 'timesheet/yearly',
				'Monthly'	=> 'timesheet/monthly', 
			)
		),
		'Regularise' => array(
			'name'		=> 'Regularise',
			'url'		=> 'Regularise',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Apply for regularise'		=> 'timesheet/apply_for_regularise',
				'My regularise application'	=> 'timesheet/my_regularise_application',
				'Regularise request'		=> 'timesheet/regularise_request',
				'Direct Regularise'		=> 'timesheet/direct_regularise',
			)
		),
		'Leave_Management' => array(
			'name'		=> 'Leave Management',
			'url'		=> 'Leave_Management',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Apply for leave'			=> 'timesheet/apply_for_leave',
				'My leave application'		=> 'timesheet/my_leave_application',
				'Leave request'				=> 'timesheet/leave_request',
				'My leave status'			=> 'timesheet/my_leave_status',
				'Direct Apply For Leave'	=> 'timesheet/leave_app_mgt_emp'
			)
		),
		'View Members' => array(
			'name'		=> 'View Members',
			'url'		=> 'View Members',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'View Members'		=> 'timesheet/view_members', 
			)
		),
		
		'Employee Timesheet' => array(
				'name'		=> 'Employee Timesheet',
				'url'		=> 'timesheet/employee_timesheet',
				'icon'		=> 'fa fa-users', 
		),
	),
	'management_menu' => array( 
		'Employee Management' => array(
			'name'    => 'Employee Management', 
			'icon'    => 'fa fa-users',
			'url'		=> 'management/profile_list',
			'children'=> array(
			'Search/View' 			=> 'management/profile_list',
			'Inactive Employee' 	=> 'management/inactive_profile_list'
			)
		),
		'Payroll Management' => array(
			'name'		=> 'Payroll Management', 
			'icon'		=> 'fa fa-users',
			'url'		=> 'management/allowance_deduction_list',
			'children'  => array(
				'CTC Graph'  => 'management/graph_profile_list'
			)
		),
	),
	'hr_menu' => array( 
		'Employee Management' => array(
			'name'    => 'Employee Management', 
			'icon'    => 'fa fa-users',
			'url'		=> 'hr/profile_list',
			'children'=> array(
			'Search/View' 			=> 'hr/profile_list',
			'Inactive Employee' 	=> 'hr/inactive_profile_list',
			'Create New Employee' 	=> 'hr/add_employee',
			'Reset Password' 		=> 'hr/reset_emp_pwd',
			'Employee Vintage' 		=> 'hr/emp_vintage_list',
			'Import Data' 			=> 'hr/emp_details_import',
			'Report' 				=> 'hr/emp_report',
			'View/Download Resume' 	=> 'hr/resume_format'
			)
		),
		'Attendance Entry' => array(
			'name'		=> 'Attendance Entry',
			'url'		=> 'hr/biometric_data_upload',
			'icon'		=> 'fa fa-users', 
			'children'  => array(
				'Biometric Data Upload'			=> 'hr/biometric_data_upload', 
				'LWH Report'					=> 'hr/lwh_report',
				'Employee Attendance Summary'	=> 'hr/emp_attendance_summary' 
			)
		), 
		'Payroll Management' => array(
			'name'		=> 'Payroll Management', 
			'icon'		=> 'fa fa-users',
			'url'		=> 'hr/allowance_deduction_list',
			'children'  => array(
				'Multiple Allowance/Deduction'	=> 'hr/allowance_deduction_list',
				'Generate Salary'				=> 'hr/generate_salary',
				'Salary Slip'					=> 'hr/salary_sheet',
				'Mail Salary Slip'				=> 'hr/mail_salary_slip',
				'Payroll Report'				=> 'hr/payroll_report', 
				'Increment Report'				=> 'hr/increment_report',
				'EPF Report'					=> 'hr/epf_report', 
				'ESi Report'					=> 'hr/esi_report',
				'CTC Graph' 			        => 'hr/graph_profile_list'
			)
		),
		'Benifits Administration' =>array(
			'name' => 'Benifits Administration',
			'url'  => 'hr/performance_incentive_slab',
			'icon' => 'fa fa-users',
			'children' =>array(
			'Performance Incentive Slab' 	=> 'hr/performance_incentive_slab',
			'Attendance Benefit' 			=> 'hr/attendance_benefit',
			'Add Extra Hours' 				=> 'hr/add_extra_hours'
			)
		),
		'Leave' =>array(
			'name' => 'Leave',
			'url' => 'hr/emp_leave_provision',
			'icon' => 'fa fa-users',
			'children' => array(
				'leave Provision' =>'hr/emp_leave_provision',
				'Employee Leave Info' =>'hr/emp_leave_details',
				'Leave Status Info' =>'hr/leave_status_info',
				'Late Comming' =>'hr/late_comming',
				'Absent Details' =>'hr/absent_details'
			)
		),
		'Expenses Reimbrusement' =>array(
			'name' => 'Expense Reimbrusement',
			'url' => 'hr/reimbrusement',
			'icon' => 'fa fa-users',
			'children' => array(
				'Reimbrusement' =>'hr/reimbrusement',
				'Reimbrusement Report' =>'hr/reimbrusement_report',
				'Gratuity' =>'hr/emp_gratuity',
				'Bonus' =>'hr/emp_bonus',
				'F&F' =>'hr/emp_fnf'
			)
		),
		'Loans' =>array(
			'name' => 'Loans',
			'url' => 'hr/loan_advance_approve_reject',
			'icon' => 'fa fa-users',
			'children' => array(
				'Loan/Advance' =>'hr/loan_advance_approve_reject',
				'Loan Applied' =>'hr/loan',
				'Advance Applied' =>'hr/advance_aaplied',
				'Loan/Advance Report' =>'hr/loan_advance_report', 
			)
		),
		'Recruitment' =>array(
			'name' => 'Recruitment',
			'url' => 'hr/resume_databank',
			'icon' => 'fa fa-users',
			'children' => array(
				'Resume Databank' =>'hr/resume_databank',
				'Shortisted Candidate' =>'hr/shortisted_candidate',
				'Interview Scheduled Candidate' =>'hr/interview_scheduled_candidate',
				'Interview Rating' =>'hr/interview_rating',
				'Placement Consultant' =>'hr/placement_consultant',
				'Manpower Requisition' =>'hr/online_mrf_detail',
				'Recruitment Report' =>'hr/recruitment_report',
			)
		),
		'Performance Management' =>array(
			'name' => 'Performance Management',
			'url' => 'hr/probation_assessment_all',
			'icon' => 'fa fa-users',
			'children' => array(
				'Probation Assessment All' =>'hr/probation_assessment_all',
				'Midyear Review All' =>'hr/midyear_review_all',
				'Midyear Appraisal Report' =>'hr/midyear_appraisal_report',
				'Annual Appraisal All' =>'hr/annual_appraisal_all',
				'Annual Appraisal Report' =>'hr/annual_appraisal_report'
			)
		), 
	),
	'hr_help_desk_menu' => array( 
		'Payroll_Help' => array(
			'name'		=> 'Payroll Help',
			'url'		=> 'hr_help_desk',
			'icon'		=> 'fa fa-users',
			'url'		=> 'payroll_help',
			'url'		=> 'hr_help_desk/assign_shift',
			
			'children'  => array(
				'Payroll Help'			=> 'hr_help_desk/payroll_help', 
				'Assign Shift'			=> 'hr_help_desk/assign_shift',
				'Download Salary Slip'	=> 'hr_help_desk/download_salary_slip',
				'Employee Suggestion'	=> 'hr_help_desk/employee_suggestion', 
			)
		), 
		'Employee_Management' => array(
			'name'		=> 'Employee Management',
			'url'		=> 'Employee_Management',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Resume Format'					=> 'hr_help_desk/resume_form',
				'Apply Resignation'				=> 'hr_help_desk/apply_resignation',
				'My Resignation Application'	=> 'hr_help_desk/my_resignation_application',
				'View Resignation Letter'		=> 'hr_help_desk/resignation_approve_reject',
				'Apply Loan / Advance'			=> 'hr_help_desk/apply_loan_advance', 
				'My Loan / Advance Application'	=> 'hr_help_desk/my_loan_advance_application',
				'View Loan/Advance'					=> 'hr_help_desk/loan_advance_approve_reject', 
				'Goal/JD'						=> 'hr_help_desk/goal_approve_reject',				
			)
		), 
		'Performance Management' => array(
			'name'		=> 'Performance Management',
			'url'		=> 'Performance Management',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Probation Assessment Form'		=> 'hr_help_desk/probation_assessment_form',
				'Probation Assessment (Approval)'			=> 'hr_help_desk/probation_assessment',
				'My Probation Assessment'		=> 'hr_help_desk/my_probation_assessment',
				'Midyear Review Form'			=> 'hr_help_desk/midyear_review_form',
				'Midyear Review'				=> 'hr_help_desk/midyear_review', 
				'My Midyear Review'				=> 'hr_help_desk/my_midyear_review',
				'Annual Appraisal Form'			=> 'hr_help_desk/annual_appraisal_form', 
				'Annual Appraisal'				=> 'hr_help_desk/annual_appraisal',
				'My Annual Appraisal'			=> 'hr_help_desk/my_annual_appraisal',				
			)
		), 
		'Recruitment' => array(
			'name'		=> 'Recruitment',
			'url'		=> 'Recruitment',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'Online mrf'				=> 'hr_help_desk/online_mrf',
				'Online Mrf Detail All'		=> 'hr_help_desk/online_mrf_detail_all',
				'Interview Candidate'		=> 'hr_help_desk/interview_candidate',
				'Online Room Booking'		=> 'hr_help_desk/online_room_booking', 
			)
		),
	),
	'accounts' => array(
		'professional_tax_slab' => array(
			'name'		=> 'Professional Tax Slab',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		),
		'income_tax_slab_define' => array(
			'name'		=> 'Income Tax Slab Define',
			'url'		=> 'accounts_help_desk/income_tax_slab_define',
		),
		'tax_deduction_limit_define' => array(
			'name'		=> 'Tax Deduction Limit Define',
			'url'		=> 'accounts_help_desk/tax_deduction_limit_define',
		),
		'income_tax_declaration_form' => array(
			'name'		=> 'Income Tax Declaration Form',
			'url'		=> 'Income Tax Declaration Form', 
			'children'  => array(
				'Estimated Declaration Form'	=> 'accounts_help_desk/estimated_declaration_form',
				'Final Delcaration Form'		=> 'accounts_help_desk/final_delcaration_form', 
			)
		),
		'my_income_tax_declaration' => array(
			'name'		=> 'My Income Tax Declaration',
			'url'		=> 'Income Tax Declaration', 
			'children'  => array(
				'My Estimated Declaration'	=> 'accounts_help_desk/my_estimated_declaration',
				'My Final Declation'		=> 'accounts_help_desk/my_final_declaration',
				'My Other Income'			=> 'accounts_help_desk/my_other_income',
			)
		),
		'my_tax_computation_sheet' => array(
			'name'		=> 'My Tax Computation Sheet',
			'url'		=> 'My Tax Computation Sheet', 
			'children'  => array(
				'Estimated Tax Compution Sheet'	=> 'accounts_help_desk/estimated_tax_compution_sheet',
				'Final Tax Computation Sheet'	=> 'accounts_help_desk/final_tax_computation_sheet', 
			)
		), 
	),
	'events' => array(
		'news_and_events' => array(
			'name'		=> 'News and events',
			'url'		=> 'news_and_events/news_and_events',
			'icon'		=> 'fa fa-users',
		),
		'news_and_events_today' => array(
			'name'		=> 'News and events today',
			'url'		=> 'news_and_events?type=Today',
			'icon'		=> 'fa fa-users',
		),
		'news_and_events_weekly' => array(
			'name'		=> 'News and events weekly',
			'url'		=> 'news_and_events?type=ThisWeek',
			'icon'		=> 'fa fa-users',
		),
		'news_and_events_monthly' => array(
			'name'		=> 'News and events monthly',
			'url'		=> 'news_and_events?type=ThisMonth',
			'icon'		=> 'fa fa-users',
		),
		'news_and_events_archive' => array(
			'name'		=> 'News and events archive',
			'url'		=> 'news_and_events?type=Archive',
			'icon'		=> 'fa fa-users',
		),
		'anniversary' => array(
			'name'		=> 'Anniversary Of This Month',
			'url'		=> 'anniversary?type=ThisMonth',
			'icon'		=> 'fa fa-users',
		),
		'view all anniversary' => array(
			'name'		=> 'View All Anniversary',
			'url'		=> 'anniversary',
			'icon'		=> 'fa fa-users',
		),
		'upcoming anniversary' => array(
			'name'		=> 'Upcoming Anniversary',
			'url'		=> 'anniversary?type=Anniversary',
			'icon'		=> 'fa fa-users',
		),
		
	),
	'resources' => array(
		'general_resources' => array(
			'name'		=> 'General Resources',
			'url'		=> 'resources/general_resources',
			'icon'		=> 'fa fa-users',
		),
		'photo_gallery' => array(
			'name'		=> 'Photo Gallery',
			'url'		=> 'resources/photo_gallery',
			'icon'		=> 'fa fa-users',
		),
		'phone_directory' => array(
			'name'		=> 'Phone Directory',
			'url'		=> 'resources/phone_directory',
			'icon'		=> 'fa fa-users',
		),
		'official_holidays' => array(
			'name'		=> 'Official Holidays',
			'url'		=> 'resources/official_holidays',
			'icon'		=> 'fa fa-users',
		),
		'cricket_team' => array(
			'name'		=> 'Cricket Team',
			'url'		=> 'resources/cricket_team',
			'icon'		=> 'fa fa-users',
		),
	),
	'icompass_help' => array(
		'learning_center' => array(
			'name'		=> 'Learning Center',
			'url'		=> 'icompass_help/help',
			'icon'		=> 'fa fa-users',
		),
		'faq' => array(
			'name'		=> 'Faq',
			'url'		=> 'icompass_help/faq',
			'icon'		=> 'fa fa-users',
		), 
	), 
	'aabsys_classified_menu' => array(
		'classified' => array(
			'name'		=> 'Classified',
			'url'		=> 'aabsys_classified/classified',
			'icon'		=> 'fa fa-users',
		),
		'My Classified' => array(
			'name'		=> 'My Classified',
			'url'		=> 'aabsys_classified/my_classified',
			'icon'		=> 'fa fa-users',
		),
	),
	'my_account' => array(
		'general' => array(
			'name'		=> 'General',
			'url'		=> 'my_account',
			'icon'		=> 'fa fa-users',
		),
		'company' => array(
			'name'		=> 'Company',
			'url'		=> 'my_account/comp_profile_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'salary' => array(
			'name'		=> 'Salary',
			'url'		=> 'my_account/salary_profile_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'experience' => array(
			'name'		=> 'Experience',
			'url'		=> 'my_account/exp_profile_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'education' => array(
			'name'		=> 'Education',
			'url'		=> 'my_account/education_profile_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'family' => array(
			'name'		=> 'Family',
			'url'		=> 'my_account/family_profile_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'reference' => array(
			'name'		=> 'Reference',
			'url'		=> 'my_account/reference_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'jd/goal' => array(
			'name'		=> 'Jd/Goal',
			'url'		=> 'my_account/job_description_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'letter issued' => array(
			'name'		=> 'Letter Issued',
			'url'		=> 'my_account/letter_issued_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
		'Document' => array(
			'name'		=> 'Document',
			'url'		=> 'my_account/document_readonly_emp',
			'icon'		=> 'fa fa-users',
		),
	),
	'accounts_admin' => array(
		'Master' => array(
			'name'		=> 'Master', 
			'icon'		=> 'fa fa-users',
			'url'		=> 'accounts_admin/define_pt_slab',
			'children'  => array(
				'Define PT Slab'				=> 'accounts_admin/define_pt_slab',
				'Define Income Tax Slab'	=> 'accounts_admin/define_income_tax_slab',
				'Tax Deduction Limit'					=> 'accounts_admin/tax_deduction_limit',
			)
		),
		'Payroll Management' => array(
			'name'		=> 'Payroll Management', 
			'icon'		=> 'fa fa-users',
			'url'		=> 'accounts_admin/emp_leave_provision',
			'children'  => array(
				'Leave Provision'				=> 'accounts_admin/emp_leave_provision',
				'Multiple Allowance/Deduction'	=> 'accounts_admin/allowance_deduction_list',
				'Loan/Advance'					=> 'accounts_admin/loan_advance_profile_list',
				'Reimbrusement'					=> 'accounts_admin/reimbrusement',
				'Gratuity'						=> 'accounts_admin/emp_gratuity', 
				'Bonus'							=> 'accounts_admin/emp_bonus',
				'F&F'							=> 'accounts_admin/emp_fnf', 
				'EPF Report'					=> 'accounts_admin/epf_report',
				'ESI Report' 			        => 'accounts_admin/esi_report',
				'Increment Report' 			    => 'accounts_admin/increment_report',
				'Payroll Report' 			    => 'accounts_admin/payroll_report',
				'Loan/Advance Report' 			=> 'accounts_admin/loan_advance_report',
				'Reimbrusement Report' 			=> 'accounts_admin/reimbrusement_report', 
			)
		),
	),
	// Google Analytics User ID
	'ga_id' => '',
	
	// Menu items
	'menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		),
		'hr' => array(
			'name'		=> 'Hr',
			'url'		=> 'hr/profile_list',
		),
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		),*/
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	// Admin Menu items
	'administrator_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		),
		'Admin' => array(
			'name'		=> 'Admin',
			'url'		=> 'admin_settings/hall_of_fame',
		),
		'hr' => array(
			'name'		=> 'Hr',
			'url'		=> 'hr/profile_list',
		),
		'management' => array(
			'name'		=> 'Management',
			'url'		=> 'management/profile_list',
		),
		'accounts_admin' => array(
			'name'		=> 'Accounts',
			'url'		=> 'accounts_admin/emp_leave_provision',
		),
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
	),
	// Management Menu items
	'managementt_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		),
		'management' => array(
			'name'		=> 'Management',
			'url'		=> 'management/profile_list',
		),
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	// HR Menu items
	'hrr_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		),
		'hr' => array(
			'name'		=> 'Hr',
			'url'		=> 'hr/profile_list',
		),
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	// Account Menu items
	'ac_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		),
		'accounts_admin' => array(
			'name'		=> 'Accounts',
			'url'		=> 'accounts_admin/allowance_deduction_list',
		),
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		), 
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	// Admin Menu items
	'emp_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		), 
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		),*/
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	// IT Menu items
	'it_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		), 
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/*'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),
	
	// ADMIN Menu items
	'admin_menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> 'home',
		), 
		'timesheet' => array(
			'name'		=> 'Timesheet',
			'url'		=> 'timesheet',
		), 
		'hr_help_desk' => array(
			'name'		=> 'Hr Help Desk',
			'url'		=> 'hr_help_desk/payroll_help',
		),
		/* 'production' => array(
			'name'		=> 'Production',
			'url'		=> 'production',
		), */
		/* 'accounts_help_desk' => array(
			'name'		=> 'A/C Help Desk',
			'url'		=> 'accounts_help_desk/professional_tax_slab',
		), */
		'news_and_events' => array(
			'name'		=> 'News & Events',
			'url'		=> 'news_and_events',
		),
		'resources' => array(
			'name'		=> 'Resources',
			'url'		=> 'resources/general_resources',
		),
		/*'icompass_help' => array(
			'name'		=> 'Polosoft Help',
			'url'		=> 'icompass_help/help',
		), */ 
		/* 'aabsys_classified' => array(
			'name'		=> 'Polosoft Classified',
			'url'		=> 'aabsys_classified/classified',
		), */
		/*'help_desk' => array(
			'name'		=> 'Help Desk',
			'url'		=> 'https://polosoftech.sdpondemand.manageengine.com/Login.jsp?serviceurl=%2Fjsp%2Findex.jsp',
		),  */
	),


	// Login page
	'login_url' => '',

	// Restricted pages
	'page_auth' => array( 
						 
					),

	// Email config
	'email' => array(
		'from_email'		=> '',
		'from_name'			=> '',
		'subject_prefix'	=> '',
		
		// Mailgun HTTP API
		'mailgun_api'		=> array(
			'domain'			=> '',
			'private_api_key'	=> ''
		),
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
$config['sess_cookie_name'] = 'ci_session_frontend';