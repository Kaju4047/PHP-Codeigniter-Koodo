<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_other_profile extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    public function addOtherProfile(){
       $sports = !empty($this->input->post('sports')) ? $this->input->post('sports') : ''; //Other ID*****
       // $achivement = !empty($this->input->post('achivement')) ? $this->input->post('achivement') : '';
       // $visiting_fees = !empty($this->input->post('visiting_fees')) ? $this->input->post('visiting_fees') : '';
       // $experience = !empty($this->input->post('experience')) ? $this->input->post('experience') : '';
       $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
       $profile_id = !empty($this->input->post('profile_id')) ? $this->input->post('profile_id') : '';
       // $city = !empty($this->input->post('other_city')) ? $this->input->post('other_city') : '';

       $latitude = !empty($this->input->post('other_latitude')) ? $this->input->post('other_latitude') : '';
       $longitude = !empty($this->input->post('other_longitude')) ? $this->input->post('other_longitude') : '';
       $aboutMe = !empty($this->input->post('about_me')) ? $this->input->post('about_me') : '';
       $website = !empty($this->input->post('website')) ? $this->input->post('website') : '';
       $email_id = !empty($this->input->post('email_id')) ? $this->input->post('email_id') : '';
       $mobile_no = !empty($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : '';
       $alter_mobile_no = !empty($this->input->post('alter_mobile_no')) ? $this->input->post('alter_mobile_no') : '';
       $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';       
       //Sport Dealer,Treatment and spa
       $company_name = !empty($this->input->post('company_name')) ? $this->input->post('company_name') : '';
                                
       //Sport Dealers
       $dealer_sports_array = !empty($this->input->post('dealer_sports')) ? $this->input->post('dealer_sports') : '';
       $dealer_sports =json_decode($dealer_sports_array);//[{sport_id:'1'}]

       //Physiotherapist,Orthopaedist,Dietitian
       $clinic_name = !empty($this->input->post('clinic_name')) ? $this->input->post('clinic_name') : '';
       $consultation_fees = !empty($this->input->post('consultation_fees')) ? $this->input->post('consultation_fees') : '';

        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $other_photoDoc="";
            if (!empty($_FILES['other_image']['name'])){
                $rename_name = uniqid(); //get file extension:
                $arr_file_info = pathinfo($_FILES['other_image']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                // print_r($newname);die();
                $old_name = $_FILES['other_image']['name'];
                // print_r($old_name);die();
                $path = "uploads/other_profile_image/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $upload_type = "jpg|png|jpeg";
                $other_photoDoc = $this->Md_database->uploadFile($path, $upload_type, "other_image", "", $newname);
                // $inserted_data['img'] = $photoDoc;
            }
            
            if (empty($profile_id)) {                                      
                      if (!empty($sports)) {
                        $table = "profile_type";                         
                        $condition = array("user_id" => $uid,'usertype'=>3);
                        $resultarray = $this->Md_database->deleteData($table, $condition);

                        $table = "profile_type";
                          $insert_data = array(                            
                              'usertype'=>'3',
                              'user_id' => $uid,
                              'createdBy' => $uid,
                              'createdDate' => date('Y-m-d H:i:s'),                
                              'created_ip_address' => $_SERVER['REMOTE_ADDR']
                          );
                          $resultarray = $this->Md_database->insertData($table, $insert_data);

                          $table = "profie_player_sport";
                          $insert_data = array(                            
                              'sportname' => $sports,
                               // 'primary_id' => '1',
                              'type'=>'3',
                              'user_id' => $uid,
                              'createdBy' => $uid,
                              'createdDate' => date('Y-m-d H:i:s'),                
                              'created_ip_address' => $_SERVER['REMOTE_ADDR']
                          );
                             // $insert_data['sportname']=$sports;
                          $insert_data['primary_id']='1';
                          $resultarray = $this->Md_database->insertData($table, $insert_data);
                           if ($sports == '18' || $sports == '24' ||$sports == '22' ) {
                            //treatment and Spa , coachimg academy, Sport dealer register  do inactive user
                              $table = "user";
                              $update_data = array(                        
                                 'view_on_app_list' => '2',
                                 'updatedBy' => $uid,
                                 'updatedDate' => date('Y-m-d H:i:s'),
                                 'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                              );
                              $condition = array(
                                  'pk_id'=>$uid,
                              );
                              $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                           }
                            $table = "user_profile_detail";
                            $insert_data = array(
                                  'other_latitude'=> $latitude,
                                  'other_longitude'=> $longitude,
                                  'other_email_id'=> $email_id,
                                  'website'=> $website,
                                  'usertype'=>'3',                      
                                  'about_me'=> $aboutMe,
                                  'other_mobile_no'=> $mobile_no,                        
                                  'other_alter_mobile_no'=> $alter_mobile_no,                  
                                  'other_location'=> $location,                        
                                  'other_company_name'=> $company_name,                        
                                  'other_clinic_name'=> $clinic_name,                        
                                  'other_consultation_fees'=> $consultation_fees,               
                                  'other_image'=> $other_photoDoc,               
                                  'status' => '1',
                                  'user_id' => $uid,
                                  'createdBy' => $uid,
                                  'createdDate' => date('Y-m-d H:i:s'),                
                                  'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $profile_id = $this->db->insert_id();
                    
                            
                            if (!empty($dealer_sports_array)) {
                              $table = "koodo_dealer_sports";                         
                              $condition = array("user_id" => $uid);
                              $resultarray = $this->Md_database->deleteData($table, $condition);
                              
                                foreach ($dealer_sports as $key => $value){
                                    $sport_id=$value->sport_id;
                                    $table = "koodo_dealer_sports";
                                    $insert_data = array(                            
                                          'fk_sport_id' => $sport_id,
                                          'user_id' => $uid,
                                          'createdBy' => $uid,
                                          'createdDate' => date('Y-m-d H:i:s'),                
                                          'created_ip_address' => $_SERVER['REMOTE_ADDR']
                                    );
                                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                                }
                            }


                      }else{
                        $table = "profie_player_sport";                         
                        $condition = array("user_id" => $uid,'type'=>'3');
                        $resultarray = $this->Md_database->deleteData($table, $condition);

                        $table = "user_profile_detail";
                        $update_data = array(                        
                           'status' => '3',
                           'updatedBy' => $uid,
                           'updatedDate' => date('Y-m-d H:i:s'),
                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'pk_id'=>$profile_id,
                            'usertype'=>3
                        );
                        $resultarray = $this->Md_database->updateData($table, $update_data, $condition);


                        $table = "profile_type";                         
                        $condition = array("user_id" => $uid,'usertype'=>3);
                        $resultarray = $this->Md_database->deleteData($table, $condition);
                    }

                    $encode_email = base64_encode($this->input->post('email_id'));
                    $encrypted_email = str_replace('=', '', $encode_email);
                      //Send link on email to verifyemail

                    $recipeinets = strtolower($email_id);
                    $from = array(
                          "email" => SITE_MAIL,
                          "name" => SITE_TITLE
                    );
                    $reserved_words = array(
                          // "||USER_NAME||" => ucwords($fullname),
                          "||SITE_TITLE||" => SITE_TITLE,
                          "||EMAIL_ID||" => strtolower($email_id),
                          "||LINK||" =>  base_url() .'other-register/verify-email/'.$uid.'/'.$encrypted_email,
                          "||YEAR||" => date('Y'),
                    );
                    $email_data = $this->Md_database->getEmailInfo('email_veification', $reserved_words);
                    $subject = SITE_TITLE . '-' . 'Email Verification';
                    $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']); 
                                                   
                    $resultarray = array('error_code' => '1','profile_id'=>!empty($sports)?$profile_id:'','Other'=>!empty($sports)?'1':'0','uid'=>$uid ,'other_id'=>$sports,'message' => 'Personal data insert successfully');
                    echo json_encode($resultarray);
                    exit();                        
            }else{
                    
                    if (!empty($sports)){
                        $table = "profile_type";                         
                        $condition = array("user_id" => $uid,'usertype'=>3);
                        $resultarray = $this->Md_database->deleteData($table, $condition);

                        $table = "profile_type";
                        $insert_data = array(                            
                              'usertype'=>'3',
                              'user_id' => $uid,
                              'createdBy' => $uid,
                              'createdDate' => date('Y-m-d H:i:s'),                
                              'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                          
                        $table = "profie_player_sport";                         
                        $condition = array("user_id" => $uid,'type'=>'3');
                        $resultarray = $this->Md_database->deleteData($table, $condition);
                            
                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $sports,
                            // 'primary_id' => '1',
                            'type'=>'3',
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                             // $insert_data['sportname']=$sports;
                        $insert_data['primary_id']='1';
                        $resultarray = $this->Md_database->insertData($table, $insert_data);

                        $table = "user_profile_detail";
                        $update_data = array(
                            'other_latitude'=> $latitude,
                            'other_longitude'=> $longitude,
                            'website'=> $website,
                            'usertype'=>'3',                        
                            'about_me'=> $aboutMe,
                            'other_email_id'=> $email_id,
                            'other_mobile_no'=> $mobile_no,                        
                            'other_alter_mobile_no'=> $alter_mobile_no,                  
                            'other_location'=> $location,                        
                            'other_company_name'=> $company_name,                        
                            'other_clinic_name'=> $clinic_name,                        
                            'other_consultation_fees'=> $consultation_fees,               
                            'status' => '1',
                            'user_id' => $uid,
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'pk_id'=>$profile_id
                        );
                        if (!empty($other_photoDoc)) {
                            $update_data['other_image']=$other_photoDoc;
                        }
                        $resultarray = $this->Md_database->updateData($table, $update_data, $condition);

                        if (!empty($dealer_sports_array)){
                              $table = "koodo_dealer_sports";                         
                              $condition = array("user_id" => $uid);
                              $resultarray = $this->Md_database->deleteData($table, $condition);
                              
                                foreach ($dealer_sports as $key => $value){
                                    $sport_id=$value->sport_id;
                                    $table = "koodo_dealer_sports";
                                    $insert_data = array(                            
                                          'fk_sport_id' => $sport_id,
                                          'user_id' => $uid,
                                          'createdBy' => $uid,
                                          'createdDate' => date('Y-m-d H:i:s'),                
                                          'created_ip_address' => $_SERVER['REMOTE_ADDR']
                                    );
                                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                                }
                            }


                          if ($sports == '18' || $sports == '24' ||$sports == '22') {
                            //treatment and Spa , coachimg academy, Sport dealer register  do inactive user in admin panel
                              $table = "user";
                              $update_data = array(                        
                                 'view_on_app_list' => '2',
                                 'updatedBy' => $uid,
                                 'updatedDate' => date('Y-m-d H:i:s'),
                                 'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                              );
                              $condition = array(
                                  'pk_id'=>$uid,
                              );
                              $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                           }
                    }else{
                        $table = "profie_player_sport";                         
                        $condition = array("user_id" => $uid,'type'=>'3');
                        $resultarray = $this->Md_database->deleteData($table, $condition);

                        $table = "koodo_dealer_sports";                         
                        $condition = array("user_id" => $uid);
                        $resultarray = $this->Md_database->deleteData($table, $condition);

                        $table = "user_profile_detail";
                        $update_data = array(                        
                           'status' => '3',
                           'updatedBy' => $uid,
                           'updatedDate' => date('Y-m-d H:i:s'),
                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'pk_id'=>$profile_id,
                            'usertype'=>3
                        );
                        $resultarray = $this->Md_database->updateData($table, $update_data, $condition);

                        $table = "profile_type";                         
                        $condition = array("user_id" => $uid,'usertype'=>3);
                        $resultarray = $this->Md_database->deleteData($table, $condition);
                    }

                    $encode_email = base64_encode($this->input->post('email_id'));
                    $encrypted_email = str_replace('=', '', $encode_email);
                      //Send link on email to verifyemail

                    $recipeinets = strtolower($email_id);
                    $from = array(
                          "email" => SITE_MAIL,
                          "name" => SITE_TITLE
                    );
                    $reserved_words = array(
                          // "||USER_NAME||" => ucwords($fullname),
                          "||SITE_TITLE||" => SITE_TITLE,
                          "||EMAIL_ID||" => strtolower($email_id),
                          "||LINK||" =>  base_url() .'other-register/verify-email/'.$uid.'/'.$encrypted_email,
                          "||YEAR||" => date('Y'),
                    );
                    $email_data = $this->Md_database->getEmailInfo('email_veification', $reserved_words);
                    $subject = SITE_TITLE . '-' . 'Email Verification';
                    $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']); 
               
                    $resultarray = array('error_code' => '1','Other'=>!empty($sports)?'1':'0', 'uid'=>$uid,'profile_id'=>!empty($sports)?$profile_id:'','other_id'=>$sports,'message' => 'Personal data update successfully');
                    echo json_encode($resultarray);
                    exit();                               
            }                   
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function addSpaService(){
        $image = !empty($this->input->post('image')) ? $this->input->post('image') : '';
        $service_name = !empty($this->input->post('service_name')) ? $this->input->post('service_name') : '';
        $price = !empty($this->input->post('mrp')) ? $this->input->post('mrp') : '';
        $offer = !empty($this->input->post('offer')) ? $this->input->post('offer') : NULL;
        $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $service_id = !empty($this->input->post('service_id')) ? $this->input->post('service_id') : '';

        if (!empty($uid)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }

            if(empty($description) || empty($service_name) || empty($price)){
                $resultarray = array('error_code' => '2', 'message' => 'description or service_name or mrp  is empty');
                echo json_encode($resultarray);
                exit();
            }else{
                if (empty($service_id)) {
                    $photoDoc = "";
                    if (!empty($_FILES['image']['name'])) {
                        $rename_name = uniqid(); //get file extension:
                        $arr_file_info = pathinfo($_FILES['image']['name']);
                        $file_extension = $arr_file_info['extension'];
                        $newname = $rename_name . '.' . $file_extension;
                        $old_name = $_FILES['image']['name'];
              
                        $path = "uploads/users/other_service/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";

                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname);
                    }
                    $table = "dealer_service";
                    $insert_data = array(
                        'service_name'=> $service_name,
                        'price'=> $price,  
                        'offer'=>$offer, 
                        'image'=>$photoDoc,                     
                        'description'=> $description,                        
                        'status' => '1',
                        'user_id' => $uid,
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                    $service_id = $this->db->insert_id();

                    $resultarray = array('error_code' => '1','service_id'=>$service_id, 'message' => 'service add');
                    echo json_encode($resultarray);
                    exit(); 
                }else{
                    $photoDoc = "";
                    if (!empty($_FILES['image']['name'])) {
                        $rename_name = uniqid(); //get file extension:
                        $arr_file_info = pathinfo($_FILES['image']['name']);
                        $file_extension = $arr_file_info['extension'];
                        $newname = $rename_name . '.' . $file_extension;
                        $old_name = $_FILES['image']['name'];
              
                        $path = "uploads/users/other_service/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";

                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname);
                
                    }
                    $table = "dealer_service";
                    $update_data = array(
                        'service_name'=> $service_name,
                        'price'=> $price,  
                        'offer'=>$offer,
                        'image'=>$photoDoc,                       
                        'description'=> $description,                        
                        'status' => '1',
                        'user_id' => $uid,
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'pk_id'=>$service_id
                        );
                      $resultarray = $this->Md_database->updateData($table, $update_data, $condition);    
                      $resultarray = array('error_code' => '1','service_id'=>$service_id, 'message' => 'service update');
                         echo json_encode($resultarray);
                         exit();
                      }                 
                  }                      
                          
        }else {
              $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit(); 
        }

    }
    public function listSpaService(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "";
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : "";
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            }
            $subquery =',(SELECT AVG(rate)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_dealer_service.user_id and r.status=1 and r.status=1 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_dealer_service.user_id and r.status=1 and r.status=1 and r.status=1) as count';

            $table = "dealer_service";
            $select = "dealer_service.service_name,dealer_service.price,dealer_service.offer,dealer_service.description,dealer_service.image,UA.address,city.city_name,UA.mob,UA.email,dealer_service.pk_id".$subquery.$subquery2;
            $condition = array(
                'dealer_service.status' => '1',                 
            );
            if (!empty($user_id)) {
                $condition['dealer_service.user_id'] =$user_id;
            }
            $this->db->limit($limit, $offset);
            $this->db->join('user as UA', 'UA.pk_id = dealer_service.user_id');    
            $this->db->join('city', 'city.pk_id = UA.city');    
            $serviceDetails= $this->Md_database->getData($table, $select, $condition, 'dealer_service.pk_id DESC', '');

            $resultarray = array('error_code' => '1', 'message' => 'Service List','service_list' =>  $serviceDetails,'img_path' => base_url().'uploads/users/other_service/');
            echo json_encode($resultarray);
            exit(); 
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }

    public function deleteSpaService(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $pk_id = !empty($this->input->post('pk_id')) ? $this->input->post('pk_id') : '';
        
        if (!empty($uid) && !empty($pk_id)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            }
            $table = "dealer_service";
            $update_data = array(
                'status'=> 3,
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']
            );
            $condition = array(
                'user_id' => $uid,
                'pk_id'=>$pk_id
            );
            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
            $resultarray = array('error_code' => '1', 'message' => 'Spa Service delete successfully' );
            echo json_encode($resultarray);
            exit(); 
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid or pk_id is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }
    public function dealerList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "";
        $otherid = !empty($this->input->post('otherid')) ? $this->input->post('otherid') : "";//24-Sport_Dealers,2-Physo_Therpist,16-Outpedic,40-visitor,21-Dietitian,22-Treatments & Spa
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
        $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : "";
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            }
            $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=3 and r.status=1 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=3 and r.status=1 and r.status=1) as count';
            $subquery3 =',(SELECT COALESCE(website," ") FROM `koodo_user_profile_detail` as 
              PD WHERE PD.user_id=koodo_user.pk_id and PD.usertype=3 ORDER by pk_id DESC LIMIT 1) as website';
            $subquery4 =',(SELECT about_me  FROM `koodo_user_profile_detail` as 
              UPD WHERE UPD.user_id=koodo_user.pk_id and UPD.usertype=3 ORDER by pk_id DESC LIMIT 1) as about_me';
            
            $table = "user";
            $this->db->order_by("FIELD(koodo_user.pk_id,$uid) DESC");
            $this->db->limit($limit, $offset);
            $this->db->where('user.pk_id!=',$uid);
            if (!empty($city_id)){
                $this->db->where('user.city',$city_id); 
            }
            if (!empty($search)) {
                $this->db->where("user.name LIKE '%$search%'");  
            }
                $col = "user.pk_id,user.name,user.age,user.address,PT.usertype,mob,user.img,email,privileges_notifications.available,city.city_name,profie_player_sport.sportname,verifyEmail,doc_verify,category".$subquery2.$subquery.$subquery3.$subquery4;
                $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
               // $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Orthopedic"','LEFT'); 
                 // $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Sport Dealers"','LEFT'); 
                if ($otherid==24) {
                  $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND koodo_buy_subscription.reference_listtype = "sport-dealers"','LEFT'); 
                  // $this->db->like('buy_subscription.listtype',"%Dealers");
                  $this->db->where('user.view_on_app_list',1);
                   // $this->db->where('koodo_buy_subscription.listtype LIKE','%Dealers');
                }
                elseif($otherid==2){
                  $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.reference_listtype = "physio-therapy"','LEFT'); 

                }elseif($otherid==16){
                  $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Orthopedic"','LEFT'); 

                }elseif($otherid==21){
                  $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Dietitian"','LEFT'); 

                }elseif($otherid==22){
                  $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.reference_listtype = "treatment-and-spa"','LEFT'); 
                  $this->db->where('user.view_on_app_list',1);
                }

                $this->db->join('privileges_notifications','user.pk_id =privileges_notifications.fk_uid');
                $this->db->join('profie_player_sport','user.pk_id =profie_player_sport.user_id');
                $this->db->join('city','city.pk_id =user.city');
                $condition = array('user.status' => '1');
                $this->db->where('profie_player_sport.type',3);
                $this->db->where('PT.usertype',3);
                // $this->db->where('user.view_on_app_list',1);
                $this->db->where('user.pk_id!=',$uid);
                $this->db->where('PT.status',1);
                $this->db->where('privileges_notifications.display_profile',1);
                // $this->db->order_by('FIELD ( koodo_buy_subscription.category,"Gold","Platinum") DESC');
                // $this->db->order_by("PT.list_at_top","ASC");
                // $this->db->order_by("user.online_date","DESC");
                $this->db->order_by("koodo_buy_subscription.category","DESC");
                $this->db->order_by("PT.list_at_top","ASC");
                $this->db->order_by("user.online_date","DESC");
                $this->db->distinct();
            if (!empty($otherid)){
                $this->db->where('profie_player_sport.sportname',$otherid);
                if (!empty($sport_id)) {
                    $this->db->join('dealer_product','user.pk_id =koodo_dealer_product.dealer_id');
                    $this->db->where('dealer_product.category',$sport_id); 
                }
            }
            $otherList1 = $this->Md_database->getData($table, $col, $condition, '', '');

            $otherList = array();
             foreach($otherList1 as $key=>$value){

               if(!isset($otherList[$value['pk_id']])){
                 $otherList[$value['pk_id']] = $value;
               }

             }
             $otherList = array_values($otherList);

            $new_array= array();
                if (!empty($otherList)) {
                    foreach ($otherList as $key => $value){
                        $id = $value['pk_id'];
                        $type= $value['usertype'];

                        $table = "user_profile_detail";
                        $orderby = 'pk_id DESC';
                        $condition = array('status' => '1','user_id' => $id,'usertype'=>'3');
                        // if (!empty($sport_id)) {
                        //     $this->db->where('',$sport_id);
                        // }
                        $col = array('pk_id,user_id,usertype,visting_fees,other_mobile_no,other_alter_mobile_no,other_location,other_company_name,other_clinic_name,other_consultation_fees,other_image,other_email_id,verifyOtherEmail');
                        $other = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                          if (!empty($value['doc_verify']) && $value['doc_verify'] =='1' && !empty($other[0]['other_email_id']) &&  !empty($other[0]['verifyOtherEmail']) && ($other[0]['other_email_id'] == $other[0]['verifyOtherEmail'])){
                              $value['verify_tick'] = '1';//yes
                          }else{
                              $value['verify_tick'] = '2';//No
                          }

                        $value['visting_fees'] =!empty($other[0]['visting_fees'])?$other[0]['visting_fees']:'';
                        // $value['skill'] =!empty($other[0]['skill'])?$other[0]['skill']:'';
                        $value['other_alter_mobile_no'] =!empty($other[0]['other_alter_mobile_no'])?$other[0]['other_alter_mobile_no']:'';
                        $value['other_mobile_no'] =!empty($other[0]['other_mobile_no'])?$other[0]['other_mobile_no']:'';
                        $value['other_location'] =!empty($other[0]['other_location'])?$other[0]['other_location']:'';
                        $value['other_company_name'] =!empty($other[0]['other_company_name'])?$other[0]['other_company_name']:'';
                        $value['other_clinic_name'] =!empty($other[0]['other_clinic_name'])?$other[0]['other_clinic_name']:'';
                        $value['other_consultation_fees'] =!empty($other[0]['other_consultation_fees'])?$other[0]['other_consultation_fees']:'';
                        $value['other_image'] =!empty($other[0]['other_image'])?$other[0]['other_image']:'';
                        $value['other_email_id'] =!empty($other[0]['other_email_id'])?$other[0]['other_email_id']:'';

                        $table = "privileges_notifications";
                        $orderby = 'pk_id DESC';
                        $condition = array('fk_uid' => $id);
                        $col = array('display_profile','available','notifications','chat_notification','location');
                        $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                        $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                        $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';


                        $table = "user";
                        $orderby = 'user.pk_id asc';
                        $condition = array('user.pk_id' => $id);
                        $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category');
                        $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id','LEFT');
                        $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['online_status']=$latlong[0]['online_status'];
                        $value['doc_verify']=$latlong[0]['doc_verify'];
                        // $value['category']=$latlong[0]['category'];

                        $table = "user_profile_detail";
                        $orderby = 'user_profile_detail.pk_id asc';
                        $condition = array('user_profile_detail.pk_id' => $uid);
                        $this->db->join('city','user_profile_detail.other_city=city.pk_id');
                        $col = array('user_profile_detail.pk_id','other_latitude','other_longitude','city_name');
                        $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['distance'] ='0';
                        if (!empty($latlong_from)) {
                        
                          $value['other_city_name'] =  !empty($latlong_from[0]['city_name'])?$latlong_from[0]['city_name']:'';


                          $latitudeFrom =  !empty($latlong_from[0]['other_longitude'])?$latlong_from[0]['other_longitude']:'';
                          $longitudeFrom = !empty($latlong_from[0]['other_longitude'])?$latlong_from[0]['other_longitude']:'';

                          $latitudeTo = !empty($latlong[0]['other_longitude'])?$latlong[0]['other_longitude']:'';
                          $longitudeTo = !empty($latlong[0]['other_longitude'])? $latlong[0]['other_longitude']:'';

                          //Calculate distance from latitude and longitude
                          $theta = $longitudeFrom - $longitudeTo;
                          $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                          $dist = acos($dist);
                          $dist = rad2deg($dist);
                          $miles = $dist * 60 * 1.1515;

                          $distance = ($miles * 1.609344);
                          $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';
                      }
                        
                        $table = "friends";
                        $orderby = 'pk_id DESC';
                        $condition = array('user_id' => $id,'uid'=>$uid);
                        $col = array('request_status');
                        $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';

                        $table = "user";
                        $orderby = 'pk_id DESC';
                        $condition = array('pk_id' => $id);
                        $col = array('emailStatus,mobStatus');
                        $hide_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['emailStatus'] =!empty($hide_status[0]['emailStatus'])?$hide_status[0]['emailStatus']:'';
                        $value['mobStatus'] =!empty($hide_status[0]['mobStatus'])?$hide_status[0]['mobStatus']:'';

                        $table = "friends_favourite";
                        $orderby = 'pk_id DESC';
                        $condition = array('user_id' => $id,'uid'=>$uid);
                        $col = array('favourite_status');
                        $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';

                        $table = "profile_type";
                        $orderby = 'pk_id asc';
                        $condition = array('status' => '1','user_id' => $id);
                        $col = array('usertype');
                        $usertype = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                        if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==1)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==1)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==1)) {
                            $value['Player'] ='1' ;
                        }else{
                            $value['Player'] ='0' ;
                        }
                        if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==2)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==2)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==2)) {
                            $value['Coach'] ='1' ;
                        }else{
                            $value['Coach'] ='0' ;
                        }
                        if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==3)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==3)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==3)) {
                            $value['Other'] ='1' ;
                        }else{
                            $value['Other'] ='0' ;
                        }
                        $new_array[] = $value;
                    }
                }
                $empty=array();
                $resultarray = array('error_code' => '1', 'message' => 'otherList ','otherList' =>  !empty($new_array)?$new_array:$empty,'img_path' => base_url().'uploads/users/','other_image_path' => base_url().'uploads/other_profile_image/');
                echo json_encode($resultarray);
                exit();              
        }else{
                $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                echo json_encode($resultarray);
                exit();                       
        }     
    }

    // public function dealerList(){
    //     $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
    //     $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
    //     $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "";
    //     $otherid = !empty($this->input->post('otherid')) ? $this->input->post('otherid') : "";
    //     $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
    //     $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : "";
    //     $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
    //     if (!empty($uid)){
    //         $table = "user";
    //         $orderby = 'pk_id asc';
    //         $condition = array('status' => '2', 'pk_id' => $uid);
    //         $col = array('pk_id','name');
    //         $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //         if (!empty($checkUser)){
    //             $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
    //             echo json_encode($resultarray);            
    //             exit();
    //         }
    //         $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=3 and r.status=1 and r.status=1) as average';
    //         $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=3 and r.status=1 and r.status=1) as count';
    //         $subquery3 =',(SELECT COALESCE(website," ") FROM `koodo_user_profile_detail` as 
    //           PD WHERE PD.user_id=koodo_user.pk_id and PD.usertype=3 ORDER by pk_id DESC LIMIT 1) as website';
    //         $subquery4 =',(SELECT about_me  FROM `koodo_user_profile_detail` as 
    //           UPD WHERE UPD.user_id=koodo_user.pk_id and UPD.usertype=3 ORDER by pk_id DESC LIMIT 1) as about_me';
            
    //         $table = "user";
    //         $this->db->order_by("FIELD(koodo_user.pk_id,$uid) DESC");
    //         $this->db->limit($limit, $offset);
    //         if (!empty($city_id)){
    //             $this->db->where('user.city',$city_id); 
    //         }
    //         if (!empty($search)) {
    //             $this->db->where("user.name LIKE '%$search%'");  
    //         }
    //             $col = "user.pk_id,user.name,user.age,user.address,PT.usertype,mob,user.img,email,privileges_notifications.available,city.city_name,profie_player_sport.sportname,verifyEmail,doc_verify,doc_verify".$subquery2.$subquery.$subquery3.$subquery4;
    //             $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
    //             $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id','LEFT'); 
    //             $this->db->join('privileges_notifications','user.pk_id =privileges_notifications.fk_uid');
    //             $this->db->join('profie_player_sport','user.pk_id =profie_player_sport.user_id');
    //             $this->db->join('city','city.pk_id =user.city');
    //             $condition = array('user.status' => '1');
    //             $this->db->where('profie_player_sport.type',3);
    //             $this->db->where('PT.usertype',3);
    //             $this->db->where('user.view_on_app_list',1);
    //             $this->db->where('PT.status',1);
    //             $this->db->where('privileges_notifications.display_profile',1);
    //             $this->db->order_by('FIELD ( koodo_buy_subscription.category,"Gold","Platinum") DESC');
    //             $this->db->order_by("PT.list_at_top","ASC");
    //             $this->db->order_by("user.online_date","DESC");
    //             $this->db->distinct();
    //         if (!empty($otherid)){
    //             $this->db->where('profie_player_sport.sportname',$otherid);
    //             if (!empty($sport_id)) {
    //                 $this->db->join('dealer_product','user.pk_id =koodo_dealer_product.dealer_id');
    //                 $this->db->where('dealer_product.category',$sport_id); 
    //             }
    //         }
    //         $otherList = $this->Md_database->getData($table, $col, $condition, '', '');
    //         $new_array= array();
    //             if (!empty($otherList)) {
    //                 foreach ($otherList as $key => $value){
    //                     $id = $value['pk_id'];
    //                     $type= $value['usertype'];


    //                   if ($value['doc_verify'] =='1' && ($value['email'] == $value['verifyEmail'])) {
    //                       $value['verify_tick'] = '1';//yes
    //                   }else{
    //                        $value['verify_tick'] = '2';//No
    //                   }

    //                     $table = "user_profile_detail";
    //                     $orderby = 'pk_id asc';
    //                     $condition = array('status' => '1','user_id' => $id,'usertype'=>'3');
    //                     // if (!empty($sport_id)) {
    //                     //     $this->db->where('',$sport_id);
    //                     // }
    //                     $col = array('pk_id,user_id,usertype,visting_fees,skill');
    //                     $other = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['visting_fees'] =!empty($other[0]['visting_fees'])?$other[0]['visting_fees']:'';
    //                     $value['skill'] =!empty($other[0]['skill'])?$other[0]['skill']:'';

    //                     $table = "privileges_notifications";
    //                     $orderby = 'pk_id DESC';
    //                     $condition = array('fk_uid' => $id);
    //                     $col = array('display_profile','available','notifications','chat_notification','location');
    //                     $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
    //                     $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
    //                     $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';


    //                     $table = "user";
    //                     $orderby = 'user.pk_id asc';
    //                     $condition = array('user.pk_id' => $id);
    //                     $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category');
    //                     $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id','LEFT');
    //                     $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['online_status']=$latlong[0]['online_status'];
    //                     $value['doc_verify']=$latlong[0]['doc_verify'];
    //                     $value['category']=$latlong[0]['category'];

    //                     $table = "user";
    //                     $orderby = 'pk_id asc';
    //                     $condition = array('pk_id' => $uid);
    //                     $col = array('pk_id','latitude','longitude');
    //                     $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');

    //                     $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
    //                     $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

    //                     $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
    //                     $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';

    //                     //Calculate distance from latitude and longitude
    //                     $theta = $longitudeFrom - $longitudeTo;
    //                     $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    //                     $dist = acos($dist);
    //                     $dist = rad2deg($dist);
    //                     $miles = $dist * 60 * 1.1515;

    //                     $distance = ($miles * 1.609344);
    //                     $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';

    //                     $table = "friends";
    //                     $orderby = 'pk_id DESC';
    //                     $condition = array('user_id' => $id,'uid'=>$uid);
    //                     $col = array('request_status');
    //                     $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';

    //                     $table = "user";
    //                     $orderby = 'pk_id DESC';
    //                     $condition = array('pk_id' => $id);
    //                     $col = array('emailStatus,mobStatus');
    //                     $hide_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['emailStatus'] =!empty($hide_status[0]['emailStatus'])?$hide_status[0]['emailStatus']:'';
    //                     $value['mobStatus'] =!empty($hide_status[0]['mobStatus'])?$hide_status[0]['mobStatus']:'';

    //                     $table = "friends_favourite";
    //                     $orderby = 'pk_id DESC';
    //                     $condition = array('user_id' => $id,'uid'=>$uid);
    //                     $col = array('favourite_status');
    //                     $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //                     $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';

    //                     $table = "profile_type";
    //                     $orderby = 'pk_id asc';
    //                     $condition = array('status' => '1','user_id' => $id);
    //                     $col = array('usertype');
    //                     $usertype = $this->Md_database->getData($table, $col, $condition, $orderby, '');

    //                     if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==1)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==1)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==1)) {
    //                         $value['Player'] ='1' ;
    //                     }else{
    //                         $value['Player'] ='0' ;
    //                     }
    //                     if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==2)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==2)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==2)) {
    //                         $value['Coach'] ='1' ;
    //                     }else{
    //                         $value['Coach'] ='0' ;
    //                     }
    //                     if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==3)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==3)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==3)) {
    //                         $value['Other'] ='1' ;
    //                     }else{
    //                         $value['Other'] ='0' ;
    //                     }
    //                     $new_array[] = $value;
    //                 }
    //             }
    //             $empty=array();
    //             $resultarray = array('error_code' => '1', 'message' => 'otherList ','otherList' =>  !empty($new_array)?$new_array:$empty,'img_path' => base_url().'uploads/users/');
    //             echo json_encode($resultarray);
    //             exit();              
    //     }else{
    //             $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
    //             echo json_encode($resultarray);
    //             exit();                       
    //     }     
    // }
}
