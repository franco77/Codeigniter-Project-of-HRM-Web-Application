<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = 'errors/page_missing';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| Added by CI Bootstrap 3
| Multilingual routing (use 2 characters (e.g. en, zh, cn, es) for switching languages)
| -------------------------------------------------------------------------
*/
$route['^(\w{2})/(.*)$'] = '$2';
$route['^(\w{2})$'] = $route['default_controller'];

/*
| -------------------------------------------------------------------------
| Added by CI Bootstrap 3
| Additional routes on top of codeigniter-restserver
| -------------------------------------------------------------------------
| Examples from rule: "api/(:any)/(:num)"
|	- [GET]		/api/users/1 ==> Users Controller's id_get($id)
|	- [POST]	/api/users/1 ==> Users Controller's id_post($id)
|	- [PUT]		/api/users/1 ==> Users Controller's id_put($id)
|	- [DELETE]	/api/users/1 ==> Users Controller's id_delete($id)
| 
| Examples from rule: "api/(:any)/(:num)/(:any)"
|	- [GET]		/api/users/1/subitem ==> Users Controller's subitem_get($parent_id)
|	- [POST]	/api/users/1/subitem ==> Users Controller's subitem_post($parent_id)
|	- [PUT]		/api/users/1/subitem ==> Users Controller's subitem_put($parent_id)
|	- [DELETE]	/api/users/1/subitem ==> Users Controller's subitem_delete($parent_id)
*/
$route['api/(:any)/(:num)']				= 'api/$1/id/$2';
$route['api/(:any)/(:num)/(:any)']		= 'api/$1/$3/$2';

/*
| -------------------------------------------------------------------------
| Added by CI Bootstrap 3
| Uncomment these if require API versioning (by module name like api_v1)
| -------------------------------------------------------------------------
*/
/*
$route['api/v1']						= "api_v1";
$route['api/v1/(:any)']					= "api_v1/$1";
$route['api/v1/(:any)/(:num)']			= "api_v1/$1/id/$2";
$route['api/v1/(:any)/(:num)/(:any)']	= "api_v1/$1/$3/$2";
$route['api/v1/(:any)/(:any)']			= "api_v1/$1/$2";
*/



$route['rfq/login']	= 'userloginrfq/login';
$route['rfq/userlist']	= 'userloginrfq/user_lists';
$route['rfq/employeelist']	= 'userloginrfq/employeelist';
$route['rfq/adduser']	= 'userloginrfq/adduser';
$route['rfq/deleteuser']	= 'userloginrfq/deleteuser';
$route['rfq/getrole']	= 'userloginrfq/roles';
$route['rfq/rfqusers']	= 'userloginrfq/rfqusers';

$route['rfq/lunch_request']	= 'userloginrfq/lunch_request';
//$route['rfq/sendmail']	= 'userloginrfq/mymail_send';

$route['rfq/project_list']	= 'userloginrfq/projectlist';
$route['rfq/save_lunch_estimate']	= 'userloginrfq/save_request_estimate';
$route['rfq/get_currency']	= 'userloginrfq/getCurrency_record';
$route['rfq/get_req_estimate']	= 'userloginrfq/get_request_estimate';
$route['rfq/delete_estimate']	= 'userloginrfq/delete_request_estimate';
$route['rfq/rfq_status']	= 'userloginrfq/getrfqstatus';
$route['rfq/get_softwr_estimate']	= 'userloginrfq/getsoftwareEstimate';
$route['rfq/get_hardwr_estimate']	= 'userloginrfq/gethardwareEstimate';
$route['rfq/get_effort_estimate']	= 'userloginrfq/getEffortestimate';
$route['rfq/get_manpower_estimate']	= 'userloginrfq/getmanpowerEstimate';
$route['rfq/get_outstation_estimate']	= 'userloginrfq/getoutstationEstimate';
$route['rfq/save_estimate']	= 'userloginrfq/saveEstimate';
$route['rfq/mail_estimate_approve']	= 'userloginrfq/approveEstimate_mail';
$route['rfq/editestimate_request']	= 'userloginrfq/edit_request_estimate';
$route['rfq/edit_estimate']	= 'userloginrfq/request_estimate_edit';
$route['rfq/search']	= 'userloginrfq/search_request';
//$route['rfq/estimate_mail/(:any)/(:any)/?(:any)']	= 'userloginrfq/sendEstimate_mail/$1/$2/$3';
$route['rfq/estimate_mail']	= 'userloginrfq/sendEstimate_mail';
$route['rfq/finance_proposal']	= 'userloginrfq/getFPrecord/$1';
$route['rfq/get_Currency']	= 'userloginrfq/getcurrency/$1';
$route['rfq/save_financeproposal']	= 'userloginrfq/FinancialProposal';
$route['rfq/submit_financeproposal']	= 'userloginrfq/approve_fp_submit';
$route['rfq/get_approve_proposal']	= 'userloginrfq/getApprove_proposal/$1';
$route['rfq/estimate_record']	= 'userloginrfq/getEstimate/$1';
$route['rfq/finance_record']	= 'userloginrfq/getFinancial';
$route['rfq/save_fp_record']	= 'userloginrfq/FinancialProposalsubmit';
$route['rfq/upload_proposal']	= 'userloginrfq/upload_proposal';
$route['rfq/clientlist']	= 'userloginrfq/clientlist';

//2018.11.09
$route['rfq/sendmail']	= 'userloginrfq/mymail_send';
$route['rfq/estimatelist']	= 'userloginrfq/get_estimate_sheet';
$route['rfq/estimate-clientlist']	= 'userloginrfq/get_estimate_client';
$route['rfq/add-client']	= 'userloginrfq/add_client';
$route['rfq/state']	= 'userloginrfq/get_state';

$route['rfq/chart']	= 'userloginrfq/get_chart';
$route['rfq/chart-client']	= 'userloginrfq/get_chart_client';
$route['rfq/test-email-launch-request']	= 'userloginrfq/test_email_launch_request';
$route['rfq/get_country']	= 'userloginrfq/get_country';
$route['rfq/project-list']	= 'userloginrfq/get_estimate_sheet_all_client';
$route['rfq/upload_proposal_multiple']	= 'userloginrfq/upload_multiple_file';
$route['rfq/request_proposal_estimate']	= 'userloginrfq/get_rfq_proposal';

//$route['rfq/approve_proposal']	= 'userloginrfq/getallApprove_proposal';
$route['rfq/estimate_record_list']	= 'userloginrfq/getEstimate_records/$1';

$route['rfq/rfqusers_tech']	= 'userloginrfq/rfqusers_technical';

$route['rfq/approve_proposal_list']	= 'userloginrfq/getallApprove_proposal';

$route['rfq/get_upload_request']	= 'userloginrfq/get_all_upload_request';

$route['rfq/upload_request']	= 'userloginrfq/upload_request';
$route['rfq/rfq_estimate_recordlist']	= 'userloginrfq/get_estimate_records';
$route['rfq/rfq_single_record']	= 'userloginrfq/save_single_request';
$route['rfq/employeelist_estimate']	= 'userloginrfq/get_employee_estimate';
$route['rfq/edit_clientlist/(:any)']	= 'userloginrfq/get_client_record/$1';
$route['rfq/save_client_record']	= 'userloginrfq/save_client_data';
$route['rfq/upload_proposal_list/(:any)']	= 'userloginrfq/get_upload_proposal/$1';
$route['rfq/get_all_project']	= 'userloginrfq/get_estimate_sheet_all_client';
$route['rfq/view_estimate_details/(:any)']	= 'userloginrfq/view_estimate_details/$1';

$route['rfq/get_pending_estimate/(:any)/(:any)']	= 'userloginrfq/get_pending_estimate/$1/$2';
$route['rfq/get_pending_estimate_list/(:any)/(:any)']	= 'userloginrfq/get_pending_estimate_record/$1/$2';
$route['rfq/get_current_estimate_list']	= 'userloginrfq/get_current_estimate_record';

$route['rfq/get_nop_list']	= 'userloginrfq/get_project_record';
$route['rfq/add_nop_project']	= 'userloginrfq/add_project_record';
$route['rfq/edit_nop_record/(:any)']	= 'userloginrfq/edit_nop_project_record/$1';
$route['rfq/update_nop_record']	= 'userloginrfq/update_nop_records';

$route['rfq/get_fp_international/(:any)']	= 'userloginrfq/get_fp_records/$1';


$route['rfq/upload_vendor_file_multiple']	= 'userloginrfq/upload_vendor_file_multiple';
$route['rfq/save_vendor_lunch_estimate']	= 'userloginrfq/save_vendor_request_estimate';