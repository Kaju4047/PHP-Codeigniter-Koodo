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
$route['admin/login'] = 'admin/login/Cn_login';

#Login
$route['admin/login-action'] = 'admin/login/Cn_login/login_action';
$route['admin/logout'] = 'admin/login/Cn_login/logout';

#Forgot Passowrds
$route['admin/forgot'] = 'admin/forgot/Cn_forgotpsw';
$route['admin/forget-password-action'] = 'admin/forgot/Cn_forgotpsw/forget_password_action';

#email verify
$route['register/verify_email/(:any)/(:any)']  = 'admin/registration_verified/Cn_registration_verified/verifyEmailAddress/$1/$2';
$route['other-register/verify-email/(:any)/(:any)']  = 'admin/registration_verified/Cn_registration_verified/verifyOtherEmailAddress/$1/$2';

#organisation
$route['admin/organisation'] = 'admin/organisation/Cn_organisation';
$route['organisation-master-action'] = 'admin/organisation/Cn_organisation/organization_master_action';

#dashboard
$route['admin/dashboard'] = 'admin/dashboard/Cn_dashboard';

#sub-user
$route['admin/sub-user'] = 'admin/subuser/Cn_subuser';
$route['admin/sub-user/(:num)'] = 'admin/subuser/Cn_subuser';

$route['admin/view-sub-user/(:any)'] = 'admin/subuser/Cn_subuser/subuser/$1';
$route['admin/add-sub-user'] = 'admin/subuser/Cn_subuser/addSubUser';
// $route['admin/add-sub-user'] = 'admin/subuser/Cn_subuser/addSubUser';
$route['admin/edit-sub-user/(:num)'] = 'admin/subuser/Cn_subuser/addSubUser/$1';
$route['admin/add-sub-user-action'] = 'admin/subuser/Cn_subuser/action';
$route['admin/sub-user-delete/(:any)'] = 'admin/subuser/Cn_subuser/delete/$1';
$route['admin/sub-user-delete/(:any)/(:num)'] = 'admin/subuser/Cn_subuser/delete/$1/$2';
$route['admin/sub-user-changeStatus/(:any)/(:any)'] = 'admin/subuser/Cn_subuser/changeStatus/$1/$2';

#setting URLS
$route['admin/setting'] = 'admin/setting/Cn_setting';
$route['user-administration-setting-action'] = 'admin/setting/Cn_setting/setting_action';

//cms
$route['admin/cms'] = 'admin/cms/Cn_cms/cms';

//sports-master
$route['admin/sport'] = 'admin/master/Cn_master/sport';
$route['admin/sport/(:any)'] = 'admin/master/Cn_master/sport/$1';
$route['admin/sport-action'] = 'admin/master/Cn_master/sportAction';
$route['admin/sport-status/(:any)/(:any)'] = 'admin/master/Cn_master/StatusChange/$1/$2';
// $route['admin/sport/(:any)'] = 'admin/master/Cn_master/sport/$1';
//tax-master
$route['admin/tax'] = 'admin/master/Cn_master/tax';
$route['admin/tax/(:any)'] = 'admin/master/Cn_master/tax/$1';
$route['admin/tax-action'] = 'admin/master/Cn_master/taxAction';
$route['admin/tax-status/(:any)/(:any)'] = 'admin/master/Cn_master/TaxStatusChange/$1/$2';

//city-master
$route['admin/city'] = 'admin/master/Cn_master/city';
$route['admin/city-action'] = 'admin/master/Cn_master/cityAction';
$route['admin/city-status/(:any)/(:any)'] = 'admin/master/Cn_master/cityStatusChange/$1/$2';
$route['admin/city/(:any)'] = 'admin/master/Cn_master/city/$1';

//service-master
$route['admin/services'] = 'admin/master/Cn_master/services';
$route['admin/services-action'] = 'admin/master/Cn_master/servicesAction';
$route['admin/services-status/(:any)/(:any)'] = 'admin/master/Cn_master/serviceStatusChange/$1/$2';
$route['admin/services/(:any)'] = 'admin/master/Cn_master/services/$1';

//admin/academy-status
$route['admin/academic-list'] = 'admin/academic_list/Cn_academic_list/academic_list';
$route['admin/academic-list/(:any)'] = 'admin/academic_list/Cn_academic_list/academic_list/$1';
$route['admin/view-academic-list/(:any)'] = 'admin/academic_list/Cn_academic_list/view_academic_list/$1';
$route['admin/academy-status/(:any)/(:any)/(:any)'] = 'admin/academic_list/Cn_academic_list/StatusChange/$1/$2/$3';
$route['admin/filter-academy'] = 'admin/academic_list/Cn_academic_list/academyFilter';
$route['admin/filter-academy/(:any)'] = 'admin/academic_list/Cn_academic_list/academyFilter/$1';
$route['admin/export-to-excel'] = 'admin/academic_list/Cn_academic_list/export_to_excel';
$route['admin/pdf'] = 'admin/academic_list/Cn_academic_list/AgentOrderInvoice';

//subscription
$route['admin/subscription'] = 'admin/subscription/Cn_subscription/subscription';
$route['admin/subscription-export-to-excel'] = 'admin/subscription/Cn_subscription/subscription_export_to_excel';
$route['admin/subscription/(:any)'] = 'admin/subscription/Cn_subscription/subscription/$1';

$route['admin/buy-subscription'] = 'admin/subscription/Cn_subscription/buy_subscription';
$route['admin/buy-sub-export-to-excel'] = 'admin/subscription/Cn_subscription/export_to_excel';
$route['admin/buy-subscription/(:any)'] = 'admin/subscription/Cn_subscription/buy_subscription/$1';
$route['admin/filter-buysub'] = 'admin/subscription/Cn_subscription/filterBuySub';
$route['admin/filter-buysub/(:any)'] = 'admin/subscription/Cn_subscription/filterBuySub/$1';
$route['admin/subscription-action'] = 'admin/subscription/Cn_subscription/subscription_action';
$route['admin/subscrip-status/(:any)/(:any)'] = 'admin/subscription/Cn_subscription/StatusChange/$1/$2';
$route['admin/subscription/(:any)'] = 'admin/subscription/Cn_subscription/subscription/$1';
$route['admin/delete-subscription/(:any)'] = 'admin/subscription/Cn_subscription/delete/$1';

//users
$route['admin/users-list'] = 'admin/users/Cn_users/users_list';
$route['admin/users-list-export-to-excel'] = 'admin/users/Cn_users/export_to_excel';
$route['admin/users-list/(:any)'] = 'admin/users/Cn_users/users_list/$1';

$route['admin/view-user-player'] = 'admin/users/Cn_users/view_user_player';
$route['admin/view-user-coach'] = 'admin/users/Cn_users/view_user_coach';
$route['admin/coach-category'] = 'admin/users/Cn_users/add_coach_category';
$route['admin/view-user-others'] = 'admin/users/Cn_users/view_user_others';
$route['admin/filter-user'] = 'admin/users/Cn_users/filterUser';
$route['admin/filter-user/(:any)'] = 'admin/users/Cn_users/filterUser/$1';

$route['admin/user-view/(:any)/(:any)'] = 'admin/users/Cn_users/userView/$1/$2';
$route['admin/user-view-tab/(:any)/(:any)'] = 'admin/users/Cn_users/userViewTab/$1/$2';
$route['admin/user-status/(:any)/(:any)'] = 'admin/users/Cn_users/StatusChange/$1/$2';
$route['admin/document-status/(:any)/(:any)/(:any)'] = 'admin/users/Cn_users/docStatusChange/$1/$2/$3';
$route['admin/other-list-status/(:any)/(:any)/(:any)'] = 'admin/users/Cn_users/viewOnAppListStatusChange/$1/$2/$3';

//user-review 
$route['admin/user-reviews-list'] = 'admin/user-reviews/Cn_user_reviews/user_reviews_list';
$route['admin/review-export-to-excel'] = 'admin/user-reviews/Cn_user_reviews/review_export_to_excel';
$route['admin/user-reviews-list/(:any)'] = 'admin/user-reviews/Cn_user_reviews/user_reviews_list/$1';
$route['admin/review-status/(:any)/(:any)'] = 'admin/user-reviews/Cn_user_reviews/StatusChange/$1/$2';
$route['admin/filter-review'] = 'admin/user-reviews/Cn_user_reviews/filterUserReview';
$route['admin/filter-review/(:any)'] = 'admin/user-reviews/Cn_user_reviews/filterUserReview/$1';
$route['admin/career-list']='admin/career/Cn_career/career';

//Career
$route['admin/career-export-to-excel']='admin/career/Cn_career/career_export_to_excel';
$route['admin/career'] = 'admin/career/Cn_career/career';
$route['admin/career/(:any)'] = 'admin/career/Cn_career/career/$1';
$route['admin/career-status/(:any)/(:any)'] = 'admin/career/Cn_career/StatusChange/$1/$2';
$route['admin/filter-career'] = 'admin/career/Cn_career/filterCareer';
$route['admin/filter-career/(:any)'] = 'admin/career/Cn_career/filterCareer/$1';
// $route['admin/career-view/(:any)/(:any)'] = 'admin/career/Cn_career/userView/$1/$2';
$route['admin/career-list/(:any)'] = 'admin/career/Cn_career/career';
 
//products 
$route['admin/dealer-products'] = 'admin/products/Cn_products/dealer_products';
$route['admin/dealer-products/(:any)'] = 'admin/products/Cn_products/dealer_products/$1';
$route['admin/filter-dealerProduct'] = 'admin/products/Cn_products/filterdDealer';
$route['admin/filter-dealerProduct/(:any)'] = 'admin/products/Cn_products/filterdDealer/$1';
$route['admin/delete-dealer/(:any)'] = 'admin/products/Cn_products/delete_dealerProducts/$1';
$route['admin/delete-used/(:any)'] = 'admin/products/Cn_products/delete_usedProducts/$1';
$route['admin/dealer-status/(:any)/(:any)'] = 'admin/products/Cn_products/StatusChange/$1/$2'; 
$route['admin/usedProduct-status/(:any)/(:any)'] = 'admin/products/Cn_products/StatusUsed/$1/$2'; 
$route['admin/used-products'] = 'admin/products/Cn_products/used_products';
$route['admin/used-products/(:any)'] = 'admin/products/Cn_products/used_products/$1';
$route['admin/filter-usedProduct'] = 'admin/products/Cn_products/filterdUsed';
$route['admin/filter-usedProduct/(:any)'] = 'admin/products/Cn_products/filterdUsed/$1';
$route['admin/product-export-to-excel/(:any)'] = 'admin/products/Cn_products/export_to_excel/$1';

//custom notifications
$route['admin/notification'] = 'admin/custom-notification/Cn_custom_notification/notification';
$route['admin/notification/(:any)'] = 'admin/custom-notification/Cn_custom_notification/notification/$1';
$route['admin/notification-action'] = 'admin/custom-notification/Cn_custom_notification/notificationAction';
$route['admin/cust-note-export-to-excel'] = 'admin/custom-notification/Cn_custom_notification/cust_note_export_to_excel';

//sports-book
$route['admin/sports-book'] = 'admin/sports-book/Cn_sports_book/sports_book';
$route['admin/report-post-export-to-excel'] = 'admin/sports-book/Cn_sports_book/sportbookExportToExcel';
$route['admin/view-comment'] = 'admin/sports-book/Cn_sports_book/viewComment';
$route['admin/report-users'] = 'admin/sports-book/Cn_sports_book/reportUserList';
$route['admin/report-post/(:any)'] = 'admin/sports-book/Cn_sports_book/reportPost/$1';
$route['admin/delete-report-post/(:any)'] = 'admin/sports-book/Cn_sports_book/deleteReportPost/$1';
$route['admin/sportbook-report-status/(:any)/(:any)'] = 'admin/sports-book/Cn_sports_book/reportPostStatus/$1/$2';

//tournaments  
$route['admin/tournaments-list'] = 'admin/tournaments/Cn_tournaments/tournaments_list';
$route['admin/tournaments-export-to-excel'] = 'admin/tournaments/Cn_tournaments/tournaments_export_to_excel';
$route['admin/tournaments-list/(:any)'] = 'admin/tournaments/Cn_tournaments/tournaments_list/$1';
$route['admin/view-tournaments/(:any)'] = 'admin/tournaments/Cn_tournaments/view_tournaments/$1';
$route['admin/tournament-status/(:any)/(:any)/(:any)'] = 'admin/tournaments/Cn_tournaments/StatusChange/$1/$2/$3'; 
$route['admin/filterTournament'] = 'admin/tournaments/Cn_tournaments/filterTournament';
$route['admin/filterTournament/(:any)'] = 'admin/tournaments/Cn_tournaments/filterTournament/$1';

//enquiries
$route['admin/enquiries'] = 'admin/enquiries/Cn_enquiries/enquiries';
$route['admin/enquiry-export-to-excel'] = 'admin/enquiries/Cn_enquiries/enquiry_export_to_excel';
$route['admin/enquiries/(:any)'] = 'admin/enquiries/Cn_enquiries/enquiries/$1';
$route['admin/filter-enq'] = 'admin/enquiries/Cn_enquiries/filterEnq';
$route['admin/filter-enq/(:any)'] = 'admin/enquiries/Cn_enquiries/filterEnq/$1';
$route['admin/private-coaching-enquiry'] = 'admin/enquiries/Cn_enquiries/private_coaching_enquiry';
$route['admin/private-coaching-enquiry/(:any)'] = 'admin/enquiries/Cn_enquiries/private_coaching_enquiry/$1';
$route['admin/private-enquiry-export-to-excel'] = 'admin/enquiries/Cn_enquiries/private_enquiry_export_to_excel';

//sports-news
$route['admin/sports-news-list'] = 'admin/sports-news/Cn_sports_news/sports_news_list';
$route['admin/add-sports-news'] = 'admin/sports-news/Cn_sports_news/add_sports_news';

//admin/advertise-with-us
$route['admin/advertise-with-us'] = 'admin/sports-news/Cn_sports_news/advertise_with_us';

//sports-videos
$route['admin/sports-videos-list/(:any)'] = 'admin/sports-videos/Cn_sports_videos/sports_videos_list/$1';
$route['admin/sports-videos-list'] = 'admin/sports-videos/Cn_sports_videos/sports_videos_list';
$route['admin/sport-video-export-to-excel'] = 'admin/sports-videos/Cn_sports_videos/sport_video_export_to_excel';
$route['admin/add-sports-videos'] = 'admin/sports-videos/Cn_sports_videos/add_sports_videos';
$route['admin/sport-video-action'] = 'admin/sports-videos/Cn_sports_videos/sport_video_action';
$route['admin/sports-videos-status/(:any)/(:any)'] = 'admin/sports-videos/Cn_sports_videos/StatusChange/$1/$2';
$route['admin/add-sports-videos/(:any)'] = 'admin/sports-videos/Cn_sports_videos/add_sports_videos/$1';
$route['admin/delete-sports-videos/(:any)'] = 'admin/sports-videos/Cn_sports_videos/delete_sports_videos/$1';
$route['admin/view-sport-videos/(:any)'] = 'admin/sports-videos/Cn_sports_videos/view/$1';

//sports-clubs
$route['admin/sports-clubs-list'] = 'admin/sports-clubs/Cn_sports_clubs/sportsClubsList';
$route['admin/sports-clubs-list/(:any)'] = 'admin/sports-clubs/Cn_sports_clubs/sportsClubsList/$1';
$route['admin/add-sports-clubs'] = 'admin/sports-clubs/Cn_sports_clubs/addSportsClubs';
$route['admin/add-sports-clubs/(:any)'] = 'admin/sports-clubs/Cn_sports_clubs/addSportsClubs/$1';
$route['admin/view-sports-clubs'] = 'admin/sports-clubs/Cn_sports_clubs/viewSportsClubs';
$route['admin/sports-club-action'] = 'admin/sports-clubs/Cn_sports_clubs/sportClubAction';
$route['admin/sports-club-status-change/(:any)/(:any)'] = 'admin/sports-clubs/Cn_sports_clubs/sportClubStatusChange/$1/$2';
$route['admin/delete-sport-club/(:any)'] = 'admin/sports-clubs/Cn_sports_clubs/deleteSportClub/$1';
$route['admin/sport-clubs-export-to-excel'] = 'admin/sports-clubs/Cn_sports_clubs/sportClubExportToExcel';

//transaction-history
$route['admin/transaction-history'] = 'admin/transaction-history/Cn_transaction_history/transaction_history';
$route['admin/transaction-export-to-excel'] = 'admin/transaction-history/Cn_transaction_history/transaction_export_to_excel';
$route['admin/transaction-history/(:any)'] = 'admin/transaction-history/Cn_transaction_history/transaction_history/$1';
$route['admin/filter-transacation'] = 'admin/transaction-history/Cn_transaction_history/filterTransaction';
$route['admin/filter-transacation/(:any)'] = 'admin/transaction-history/Cn_transaction_history/filterTransaction/$1';

//advertisement
$route['admin/advertisement-list'] = 'admin/advertisement/Cn_advertisement/advertisement_list';
$route['admin/advertisement-export-to-excel'] = 'admin/advertisement/Cn_advertisement/advertisement_export_to_excel';
$route['admin/advertisement-list/(:any)'] = 'admin/advertisement/Cn_advertisement/advertisement_list/$1';
$route['admin/add-advertisement'] = 'admin/advertisement/Cn_advertisement/add_advertisement';
$route['admin/advertisement-action'] = 'admin/advertisement/Cn_advertisement/advAction';
$route['admin/adv-status/(:any)/(:any)'] = 'admin/advertisement/Cn_advertisement/StatusChange/$1/$2';
$route['admin/delete-adv/(:any)'] = 'admin/advertisement/Cn_advertisement/deleteAdv/$1';
$route['admin/add-advertisement/(:any)'] = 'admin/advertisement/Cn_advertisement/add_advertisement/$1';
$route['admin/filter-adv'] = 'admin/advertisement/Cn_advertisement/filter_list';
$route['admin/filter-adv/(:any)'] = 'admin/advertisement/Cn_advertisement/filter_list/$1';

//Invitation 
$route['admin/user-invitation-list'] = 'admin/user-invitations/Cn_user_invitation/user_invitation_list';
$route['admin/user-invitation-list/(:any)'] = 'admin/user-invitations/Cn_user_invitation/user_invitation_list/$1';
$route['admin/view-user-invitation'] = 'admin/user-invitations/Cn_user_invitation/view_user_invitation';
$route['admin/view-user-invitation/(:any)'] = 'admin/user-invitations/Cn_user_invitation/view_user_invitation/$1';

/********************************Android****************************************/
$route['android/registration'] = 'android/user/Cn_registration/registration';
$route['android/verify-otp'] = 'android/user/Cn_registration/OTPVerify';
$route['android/resend-otp'] = 'android/user/Cn_registration/ResendOtp';
$route['android/login'] = 'android/user/Cn_registration/login';
$route['android/google-login'] = 'android/user/Cn_registration/googleLogin';
$route['android/logout'] = 'android/user/Cn_registration/logout';
$route['android/delete-account'] = 'android/user/Cn_registration/deleteAccount';
$route['android/update-password'] = 'android/user/Cn_registration/updatePassword';
$route['android/forgot-pass-otp'] = 'android/user/Cn_registration/forgotpassGetOTP';
$route['android/forgot-pass-otp-verify'] = 'android/user/Cn_registration/forgotPassOTPVerify';
$route['android/update-online-status'] = 'android/user/Cn_registration/updateOnlineStatus';
$route['android/update-latitude-longitude'] = 'android/user/Cn_registration/updateLatLong';
$route['android/update-offline-status'] = 'android/user/Cn_registration/updateOfflineStatus';

//Player Profile
$route['android/profile-sport-list'] = 'android/user_profile/Cn_user_profile/sportListProfile';
// $route['android/email-verify/(:any)'] = 'android/user_profile/Cn_user_profile/verifyEmailAddress/$1';
// $route['android/email-verify/(:any)'] = 'admin/dashboard/Cn_dashboard/$1';
$route['android/selected-state-city-list'] = 'android/user_profile/Cn_user_profile/selectedState_cityList';
$route['android/add-basic-profile'] = 'android/user_profile/Cn_user_profile/addBasicProfile';
$route['android/add-career-document'] = 'android/user_profile/Cn_user_profile/career_document';
$route['android/add-personal-details'] = 'android/user_profile/Cn_user_profile/addPersonalDetails';
$route['android/add-personal-document'] = 'android/user_profile/Cn_user_profile/personalDocument';
$route['android/add-personal-coach-certificate'] = 'android/user_profile/Cn_user_profile/personalCoachCertificate';
$route['android/view-profile'] = 'android/user_profile/Cn_user_profile/viewProfile';
$route['android/add-profile'] = 'android/user_profile/Cn_user_profile/addPlayerProfile';
$route['android/pro-player-sport'] = 'android/user_profile/Cn_user_profile/proPlayerSport';
$route['android/update-profile'] = 'android/user_profile/Cn_user_profile/update_profile';
$route['android/list-player-profile-sportwise'] = 'android/user_profile/Cn_user_profile/listPlayerSportwise';
$route['android/update-status-proplayer-sport'] = 'android/user_profile/Cn_user_profile/playerSportStatusUpdate';
$route['android/list-pro-player-sportwise'] = 'android/user_profile/Cn_user_profile/listProPlayerSportwise';
$route['android/update-mobile-email-status'] = 'android/user_profile/Cn_user_profile/updateMobileEmailStatus';
$route['android/looking-for-coach'] = 'android/user_profile/Cn_user_profile/lookingforCoach';
$route['android/user-doc-cert-list'] = 'android/user_profile/Cn_user_profile/doc_certificate_list';
$route['android/upload-doc-certificate'] = 'android/user_profile/Cn_user_profile/upload_doc_certificate';
$route['android/upload-doc-delete'] = 'android/user_profile/Cn_user_profile/delete_doc_certificate';

//Rating
$route['android/rating'] = 'android/user_profile/Cn_user_profile/rating';

//Other Profile
$route['android/add-other-profile'] = 'android/user_profile/Cn_other_profile/addOtherProfile';
$route['android/other-sport-list-profile'] = 'android/user_profile/Cn_other_profile/othersportListProfile';
$route['android/spa-service-other-profile'] = 'android/user_profile/Cn_other_profile/addSpaService';
$route['android/list-spa-service-other-profile'] = 'android/user_profile/Cn_other_profile/listSpaService';
$route['android/delete-spa-service'] = 'android/user_profile/Cn_other_profile/deleteSpaService';
$route['android/add-dealer-product'] = 'android/user_profile/Cn_other_profile/add_dealer_product';
$route['android/other-list'] = 'android/user_profile/Cn_other_profile/dealerList';

//Coach Profile
$route['android/add-coach-profile'] = 'android/user_profile/Cn_coach_user_profile/addCoachProfile';
$route['android/add-batches'] = 'android/user_profile/Cn_coach_user_profile/addBatches';
$route['android/delete-coach-batch'] = 'android/user_profile/Cn_coach_user_profile/deleteBatch';
$route['android/list-coach-sportwise'] = 'android/user_profile/Cn_coach_user_profile/listCoachSportwise';
$route['android/coach-document-list'] = 'android/user_profile/Cn_coach_user_profile/coachDocumentList';

//Career
$route['android/career-profile'] = 'android/user_profile/Cn_career/addCareer';
$route['android/career-list'] = 'android/user_profile/Cn_career/careerList';
$route['android/delete-career'] = 'android/user_profile/Cn_career/deleteCareer';

$route['android/update-previleges-profile'] = 'android/user_previleges/Cn_privileges/updateSetting';
$route['android/list-previleges-profile'] = 'android/user_previleges/Cn_privileges/settingList';
$route['android/reset-password'] = 'android/user_previleges/Cn_privileges/resetPassword';

//Subscription 
$route['android/my-subscription-list'] = 'android/subscription/Cn_subscription/mySubscriptionPlansList';
$route['android/add-plan'] = 'android/subscription/Cn_subscription/addPlan';
$route['android/check-amount'] = 'android/subscription/Cn_subscription/checkAmount';
$route['android/review-plan'] = 'android/subscription/Cn_subscription/reviewPlan';
$route['android/delete-selected-plan'] = 'android/subscription/Cn_subscription/deleteSelectedPlan';
$route['android/buy-subscription-plan'] = 'android/subscription/Cn_subscription/buySubscriptionPlan';
$route['android/pay-subscription-plan'] = 'android/subscription/Cn_subscription/paySubscriptionPlan';
$route['android/first-subscription-list'] = 'android/subscription/Cn_subscription/firstSubscriptionList';
$route['android/transaction-successfully'] = 'android/subscription/Cn_subscription/transactionSuccessfully';
$route['android/plan-reminder-msg'] = 'android/subscription/Cn_subscription/plan_reminder_msg';

//Academy
$route['android/add-academy'] = 'android/coaching_academy/Cn_coaching_academy/addAcademy';
$route['android/list-academy'] = 'android/coaching_academy/Cn_coaching_academy/listAcademy';
$route['android/delete-academy'] = 'android/coaching_academy/Cn_coaching_academy/deleteAcademy';

//tournaments
$route['android/add-tournaments'] = 'android/tournaments/Cn_tournaments/addTournaments';
$route['android/list-tournaments'] = 'android/tournaments/Cn_tournaments/listTournament';
$route['android/delete-tournaments'] = 'android/tournaments/Cn_tournaments/deleteTournaments';

//Notification
$route['android/view-notification'] = 'android/notification/Cn_notification/viewNotification';
$route['android/delete-notification'] = 'android/notification/Cn_notification/deleteNotification';
$route['android/delete-single-notification'] = 'android/notification/Cn_notification/deleteSingleNotification';
$route['android/check-read-unread'] = 'android/notification/Cn_notification/checkReadUnread';

//For terms and conditions  
$route['android/view-terms-condition'] = 'android/terms_&_condition/Cn_terms_condition/showTermsAddCondition';

//Products list
$route['android/add-products-list'] = 'android/products_list/Cn_products_list/addProductsList';
$route['android/view-products-list'] = 'android/products_list/Cn_products_list/viewProductsList';
$route['android/delete-products-list'] = 'android/products_list/Cn_products_list/deleteProductsList';

//payment history
$route['android/payment-view-list'] = 'android/payment_history/Cn_payment_history/paymentViewList';

//dashboard
$route['android/dashboard-list'] = 'android/sports/Cn_sports/viewSports';
$route['android/list-addview'] = 'android/sports/Cn_sports/listAddview';
$route['android/list-icon'] = 'android/sports/Cn_sports/listIcon';

//Enquiry
$route['android/enquiry-dropdown'] = 'android/enquiry/Cn_enquiry/enquiryFor';
$route['android/add-enquiry'] = 'android/enquiry/Cn_enquiry/addEnquiry';
$route['android/private-coach-enquiry'] = 'android/enquiry/Cn_enquiry/privateCoachEnquiry';

//Gallery
$route['android/add-gallary-video'] = 'android/gallery/Cn_gallery/addVideo';
$route['android/add-gallary-image'] = 'android/gallery/Cn_gallery/addImage';
$route['android/gallery-image-list'] = 'android/gallery/Cn_gallery/galleryImageList';

//Friends
$route['android/friend-request'] = 'android/friends/Cn_friends/sendFriendRequest';
$route['android/accept-reject-friend-request'] = 'android/friends/Cn_friends/acceptRejectFriendRequest';
$route['android/favourite-status'] = 'android/friends/Cn_friends/favouriteStatus';
$route['android/friend-list'] = 'android/friends/Cn_friends/friendList';
$route['android/friend-favourite-list'] = 'android/friends/Cn_friends/friendFavouriteList';
$route['android/friend-request-list'] = 'android/friends/Cn_friends/friendRequestList';
$route['android/delete-friends'] = 'android/friends/Cn_friends/deleteFriends';
$route['android/mutual-friends'] = 'android/friends/Cn_friends/mutualFriends';

//Chat
$route['android/chat-user-list'] = 'android/chat/Cn_chat/chatUserList';
$route['android/chat'] = 'android/chat/Cn_chat/chat';
$route['android/chat-message-list'] = 'android/chat/Cn_chat/chatMessageList';
$route['android/delete-chat-message-list'] = 'android/chat/Cn_chat/deleteChatMessageList';
// $route['android/chat-send-pdf'] = 'android/chat/Cn_chat/chat_send_pdf';
$route['android/block-status'] = 'android/chat/Cn_chat/blockStatus';
$route['android/block-user-list'] = 'android/chat/Cn_chat/blockUserList';
$route['android/create-group'] = 'android/chat/Cn_chat/createGroup';
$route['android/group-list'] = 'android/chat/Cn_chat/groupList';
$route['android/group-leave'] = 'android/chat/Cn_chat/groupLeave';
$route['android/group-delete'] = 'android/chat/Cn_chat/groupDelete';
$route['android/clear-group-chat'] = 'android/chat/Cn_chat/clearGropuChat';
$route['android/friend-user-list'] = 'android/chat/Cn_chat/userList';
$route['android/delete-chat-user'] = 'android/chat/Cn_chat/deleteChatUser';
$route['android/group-members-list'] = 'android/chat/Cn_chat/groupMembersList';
$route['android/update-group-members'] = 'android/chat/Cn_chat/updateGroupMembers';

//SportBook
$route['android/post'] = 'android/sportbook/Cn_sportbook/post';
$route['android/post-list'] = 'android/sportbook/Cn_sportbook/postList';
$route['android/post-like'] = 'android/sportbook/Cn_sportbook/postLike';
$route['android/post-comment'] = 'android/sportbook/Cn_sportbook/postComment';
$route['android/post-comment-list'] = 'android/sportbook/Cn_sportbook/postCommentList';
$route['android/check-new-post'] = 'android/sportbook/Cn_sportbook/checkNewPost';
$route['android/report-post'] = 'android/sportbook/Cn_sportbook/reportPost';

//Invitation Referral code
$route['android/send-invitation'] = 'android/invitation/Cn_user_invitation/sendInvitation';
$route['android/update-invitation-status'] = 'android/invitation/Cn_user_invitation/updateInvitationStatus';
$route['android/invitation-list'] = 'android/invitation/Cn_user_invitation/invitationList';
$route['android/save-free-subscription-plan'] = 'android/invitation/Cn_user_invitation/saveFreeSubscriptionPlan';

//Sport Videos
$route['android/sport-videos-list'] = 'android/sport_videos/Cn_sport_videos/sportVideosList';

//Sport Clubs
$route['android/sport-clubs-list'] = 'android/sport_clubs/Cn_sport_clubs/sportClubsList';

//Advertise Enquiry
$route['android/add-advertise'] = 'android/advertise/Cn_advertise/addAdvertise';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



