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



#default URLS
$route['default_controller'] = 'Cn_Default';
$route['sessionExpire'] = 'Cn_Default/sessionExpire';


# URLS
$route['admin'] = 'admin/login/Cn_login';
$route['login'] = 'admin/login/Cn_login';

#Login
$route['login-action'] = 'admin/login/Cn_login/login_action';
$route['logout'] = 'admin/login/Cn_login/logout';

#Forgot Passowrds
$route['forgot'] = 'admin/forgot/Cn_forgotpsw';
$route['forget-password-action'] = 'admin/forgot/Cn_forgotpsw/forget_password_action';

#organisation
$route['organisation'] = 'admin/organisation/Cn_organisation';
$route['organisation-master-action'] = 'admin/organisation/Cn_organisation/organization_master_action';

#dashboard
$route['dashboard'] = 'admin/dashboard/Cn_dashboard';

#sub-user
$route['sub-user'] = 'admin/subuser/Cn_subuser';
$route['sub-user/(:num)'] = 'admin/subuser/Cn_subuser';
$route['view-sub-user/(:any)'] = 'admin/subuser/Cn_subuser/subuser/$1';
$route['add-sub-user'] = 'admin/subuser/Cn_subuser/addSubUser';
$route['edit-sub-user/(:num)'] = 'admin/subuser/Cn_subuser/addSubUser/$1';
$route['add-sub-user-action'] = 'admin/subuser/Cn_subuser/action';
$route['sub-user-delete/(:any)'] = 'admin/subuser/Cn_subuser/delete/$1';
$route['sub-user-delete/(:any)/(:num)'] = 'admin/subuser/Cn_subuser/delete/$1/$2';
$route['sub-user-changeStatus/(:any)/(:any)'] = 'admin/subuser/Cn_subuser/changeStatus/$1/$2';


#setting URLS
$route['setting'] = 'admin/setting/Cn_setting';
$route['user-administration-setting-action'] = 'admin/setting/Cn_setting/setting_action';

//cms
$route['cms'] = 'admin/cms/Cn_cms/cms';


#master
$route['master-listing'] = 'admin/master/Cn_master/listing';
$route['add-master'] = 'admin/master/Cn_master/add';
$route['view-master'] = 'admin/master/Cn_master/view';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
