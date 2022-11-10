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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'front/index/home';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = FALSE;

// Base URL
$base_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// Dynamic Route Path
$pos = strpos($base_url,"admin");

if(strpos($base_url,"/admin/")){
	$expo1 = explode("admin/",$base_url);
	$config['base_url'] = $expo1[0];	
	
	$expp = !empty($expo1[1])?$expo1[1]:'';
	
	$expo = explode("/",$expp);
	$conntrol = !empty($expo['0'])?$expo['0']:'';
	
	$flag = '1';
	
}elseif(strpos($base_url,"/ws/")){
	$expo1 = explode("ws/",$base_url);
	$config['base_url'] = $expo1[0];	
	
	$expp = !empty($expo1[1])?$expo1[1]:'';
	
	$expo = explode("/",$expp);
	$conntrol = !empty($expo['0'])?$expo['0']:'';
	
	$flag = '2';
}
else{
	$config['base_url'] = $base_url;	
	$flag = '3';
	
	if(!empty($_SERVER['ORIG_PATH_INFO'])){
		$expo1 = explode("/",$_SERVER['ORIG_PATH_INFO']);
	}elseif(!empty($_SERVER['PATH_INFO'])){
		$expo1 = explode("/",$_SERVER['PATH_INFO']);
	}else{
		$expo1 = explode("/",$_SERVER['REQUEST_URI']);
	}
    $conntrol = !empty($expo1['1'])?$expo1['1']:'';
}

if($flag==1) {
	$route['admin/'.$conntrol]                          = "admin/".$conntrol."/".$conntrol."_control";
        $route['admin/'.$conntrol.'/add_record']            = 'admin/'.$conntrol.'/'.$conntrol.'_control/add_record';
    
	$route['admin/'.$conntrol.'/check_email']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_email';
	$route['admin/'.$conntrol.'/check_slug']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_slug';
	$route['admin/'.$conntrol.'/check_username']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_username';
	$route['admin/'.$conntrol.'/insert_data']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/insert_data';
	$route['admin/'.$conntrol.'/edit_record/(:num)']    = 'admin/'.$conntrol.'/'.$conntrol.'_control/edit_record';
	$route['admin/'.$conntrol.'/checkout_success/(:num)'] 	= 'admin/'.$conntrol.'/'.$conntrol.'_control/checkout_success';
	
	$route['admin/'.$conntrol.'/deep_link/(:num)'] 	= 'admin/'.$conntrol.'/'.$conntrol.'_control/deep_link';

	$route['admin/'.$conntrol.'/view_record/(:num)']    = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['admin/'.$conntrol.'/view_record/(:num)/(:num)']    = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_record';
	$route['admin/'.$conntrol.'/status_update/(:num)']  = 'admin/'.$conntrol.'/'.$conntrol.'_control/status_update';
	$route['admin/'.$conntrol.'/renew_plan/(:num)']  = 'admin/'.$conntrol.'/'.$conntrol.'_control/renew_plan';
	$route['admin/'.$conntrol.'/send_mail/(:num)']  = 'admin/'.$conntrol.'/'.$conntrol.'_control/send_mail';
	$route['admin/'.$conntrol.'/update_data']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/update_data';
	$route['admin/'.$conntrol.'/ajax_delete_all']       = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_delete_all';
	$route['admin/'.$conntrol.'/delete_record']       = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_record';
	$route['admin/'.$conntrol.'/ajax_status_all']       = 'admin/'.$conntrol.'/'.$conntrol.'_control/ajax_status_all';
	$route['admin/'.$conntrol.'/dummy_records']           = 'admin/'.$conntrol.'/'.ucfirst($conntrol).'_control/dummy_records';
	$route['admin/'.$conntrol.'/check_category']        = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_category';
	$route['admin/'.$conntrol.'/get_cat_size_detail']    = 'admin/'.$conntrol.'/'.$conntrol.'_control/get_cat_size_detail';
	$route['admin/'.$conntrol.'/check_condition']        = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_condition';
	$route['admin/'.$conntrol.'/delete_product_image']        = 'admin/'.$conntrol.'/'.$conntrol.'_control/delete_product_image';
	$route['admin/'.$conntrol.'/get_size_list']        = 'admin/'.$conntrol.'/'.$conntrol.'_control/get_size_list';
	$route['admin/'.$conntrol.'/check_plan_detail']        = 'admin/'.$conntrol.'/'.$conntrol.'_control/check_plan_detail';
	$route['admin/'.$conntrol.'/excel_export']          	= 'admin/'.$conntrol.'/'.$conntrol.'_control/excel_export';
	$route['admin/' . $conntrol . '/admin_change_password'] = 'admin/' . $conntrol . '/' . ucfirst($conntrol) . '_control/admin_change_password';
	
    $route['admin/'.$conntrol.'/select_form']           = 'admin/'.$conntrol.'/'.$conntrol.'_control/select_form';
    $route['admin/'.$conntrol.'/view_form/(:num)']      = 'admin/'.$conntrol.'/'.$conntrol.'_control/view_form';
    
    $route['admin/'.$conntrol.'/(:num)']                = 'admin/'.$conntrol.'/'.$conntrol.'_control';
	$route['admin/'.$conntrol.'/msg/(:any)']            = 'admin/'.$conntrol.'/'.$conntrol.'_control';
    $route['admin/'.$conntrol.'/(:any)']                = "admin/".$conntrol."/".$conntrol."_control";
    
    
    $route['admin/'.$conntrol.'/product_detail/(:num)'] = 'admin/'.$conntrol.'/'.$conntrol.'_control/product_detail';
}
elseif ($flag == 2)
{
    $route['ws/employee_login'] 				= "ws/Employee_control/employee_login";
    $route['ws/forget_password'] 				= "ws/Employee_control/forget_password";
    $route['ws/change_password'] 				= "ws/Employee_control/change_password";
    $route['ws/user_verify'] 					= "ws/Employee_control/user_verify";
    $route['ws/history_sync'] 					= "ws/History_control/history_sync";
    
    $route['ws/logout/id/(:num)'] 			= "ws/Others_control/logout/id/$1";
    $route['ws/location_details'] 			= "ws/Others_control/location_details";
    $route['ws/apiVersionNew'] 				= "ws/Others_control/apiVersionNew";
} 
elseif($flag == 3)
{
	//$route['index']   = "front/index/home";
	$route[$conntrol] = "front/".$conntrol."/".$conntrol."_control";
	$route[$conntrol.'/list_data'] = "front/".$conntrol."/".$conntrol."_control/list_data";
}
// End


/*------------ END  Reset Password  URL------------------*/

/*----------- Reset Password Mobile URL ----------- */

$route['reset_password_mobile']                                     = 'reset_password/reset_password_mobile_control';
$route['reset_password_mobile/change_password']                     = 'reset_password/reset_password_mobile_control/change_password';
$route['reset_password_mobile/reset_password_template/(:any)']      = 'reset_password/reset_password_mobile_control/reset_password_template/';

/*------------ END  Reset Password Mobile URL------------------*/


$route['verify_management/verify_user'] = 'verify_management/Verify_management_control/verify_user';

//For Admin Redirection 
$route['admin']                    = "admin/login/login";
$route['admin/login']              = "admin/login/login";
$route['admin/logout']             = "admin/login/logout";
$route['admin/dashboard']          = "admin/index/dashboard";


/*----------- Reset Password URL ----------- */

$route['reset_password']                                     = 'reset_password/reset_password_control';
$route['reset_password/change_password']                      = 'reset_password/reset_password_control/change_password';
$route['reset_password/reset_password_front']                = 'reset_password/reset_password_control/reset_password_front';
$route['reset_password/reset_password_template/(:any)']      = 'reset_password/reset_password_control/reset_password_template/';
$route['reset_password/reset_password_template_front/(:any)']= 'reset_password/reset_password_control/reset_password_template_front/';
$route['reset_password/reset_password_template_company/(:any)']= 'reset_password/reset_password_control/reset_password_template_company/';
$route['reset_password/change_password_company']               = 'reset_password/reset_password_control/change_password_company';