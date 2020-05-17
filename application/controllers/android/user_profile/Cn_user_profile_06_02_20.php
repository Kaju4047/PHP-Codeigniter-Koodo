<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_user_profile extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function addPersonalDetails(){
        $this->load->library('encryption');
        $userName = !empty($this->input->post('userName')) ? $this->input->post('userName') : '';
        $profilepic = !empty($this->input->post('profilepic')) ? $this->input->post('profilepic') : '';
        $document = !empty($this->input->post('document')) ? $this->input->post('document') : '';
        $cerificate = !empty($this->input->post('cerificate')) ? $this->input->post('cerificate') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $mobileNo = !empty($this->input->post('mobileNo')) ? $this->input->post('mobileNo') : '';
        // $mobileStatus = !empty($this->input->post('mobileStatus')) ? $this->input->post('mobileStatus') : '';
        $email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
        // $emailStatus = !empty($this->input->post('emailStatus')) ? $this->input->post('emailStatus') : '';
        $gender = !empty($this->input->post('gender')) ? $this->input->post('gender') : '';
        $playing_time = !empty($this->input->post('playing_time')) ? $this->input->post('playing_time') : '';
        $dob = !empty($this->input->post('dob')) ? $this->input->post('dob') : '';
        $eduDetail = !empty($this->input->post('eduDetail')) ? $this->input->post('eduDetail') : '';
        $occupation = !empty($this->input->post('occupation')) ? $this->input->post('occupation') : '';
        // $address = !empty($this->input->post('address')) ? $this->input->post('address') : '';
        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : '';
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : '';

        if (!empty($uid)){
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
            if(empty($userName) || empty($mobileNo) || empty($email) || empty($mobileNo) || empty($gender) || empty($dob)) {
                $resultarray = array('error_code' => '2', 'message' => 'userName or mobileNo or email or gender or dob is empty');
                echo json_encode($resultarray);
                exit();
            }else{
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('status<>' => '3', 'mob' => $mobileNo);
                $this->db->where('pk_id!= ',$uid);
                $col = array('pk_id','name');
                $checkExistMobile = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('status<>' => '3', 'email' => $email);
                $this->db->where('pk_id!= ',$uid);
                $col = array('pk_id','name');
                $checkExistEmail = $this->Md_database->getData($table, $col, $condition, $orderby, '');
 
                if (empty($checkExistMobile)){
                    if (empty($checkExistEmail)){                    
                        $encode_email = base64_encode($this->input->post('email'));
                        $encrypted_email = str_replace('=', '', $encode_email);
                        //Send link on email to verifyemail
                       
                        // $this->email->from(SITE_MAIL, SITE_TITLE); //sender's email
                        // $address = $email;   //receiver's email
                        // $subject="Email Verification";    //subject
                        // $message= /*-----------email body starts-----------*/
                        //     'Dear User,
                        //     Please click on below URL or paste into your browser to verify your Email
                        //       ' . base_url() .'register/verify_email/'.$uid.'/'.$encrypted_email ;                            
                                                         
                        //     /*-----------email body ends-----------*/             
                        // $this->email->to($email);
                        // $this->email->subject($subject);
                        // $this->email->message($message);
                        // $this->email->send();


                        $recipeinets = strtolower($email);
                        $from = array(
                            "email" => SITE_MAIL,
                            "name" => SITE_TITLE
                        );
                        $reserved_words = array(
                            // "||USER_NAME||" => ucwords($fullname),
                            "||SITE_TITLE||" => SITE_TITLE,
                            "||EMAIL_ID||" => strtolower($email),
                            "||LINK||" =>  base_url() .'register/verify_email/'.$uid.'/'.$encrypted_email,
                            "||YEAR||" => date('Y'),
                        );
                        $email_data = $this->Md_database->getEmailInfo('email_veification', $reserved_words);
                        $subject = SITE_TITLE . '-' . 'Email Verification';
                        $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']); 


                        $diff = (date('Y') - date('Y',strtotime($dob)));
                        $inserted_data = array(
                            'email'=> $email,
                            'mob'=> $mobileNo,
                            'name'=>$userName,
                            'gender'=>$gender,
                            'age'=>$diff,
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'playing_time'=>$playing_time,
                            'dob'=>$dob,
                            'edudetails'=>$eduDetail,
                            'occupation'=>$occupation,
                            // 'address'=>$address,                    
                            'status' => 1,   
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']      
                        );
                        if (!empty($_FILES['profilepic']['name'])){
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['profilepic']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            // print_r($newname);die();
                            $old_name = $_FILES['profilepic']['name'];
                            // print_r($old_name);die();
                            $path = "uploads/users/";
                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg";
                            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "profilepic", "", $newname);
                            $inserted_data['img'] = $photoDoc;
                        }

                        $photoDoc2 = "";
                        if (!empty($_FILES['document']['name'])){
                            $rename_name2 = uniqid(); //get file extension:
                            $arr_file_info2 = pathinfo($_FILES['document']['name']);
                            $file_extension2 = $arr_file_info2['extension'];
                            $newname2 = $rename_name2 . '.' . $file_extension2;
                            // print_r($newname2);die();
                            $old_name = $_FILES['document']['name'];
                            // print_r($old_name);die();
                            $path2 = "uploads/users/document";

                            if (!is_dir($path2)) {
                                mkdir($path2, 0777, true);
                            }
                            $upload_type2 = "jpg|png|jpeg";

                            $photoDoc2 = $this->Md_database->uploadFile($path2, $upload_type2, "document", "", $newname2);  
                            $inserted_data['document'] = $photoDoc2;                       
                        }
                        $insertData = array(                    
                            'status' => 1,   
                            'user_id' => $uid,   
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'), 
                            'createdBy' => $uid, 
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']    
                        );

                        $photoDoc3 = "";
                        if (!empty($_FILES['certificate']['name'])) {
                            $rename_name3 = uniqid(); //get file extension:
                            $arr_file_info3 = pathinfo($_FILES['certificate']['name']);
                            $file_extension3 = $arr_file_info3['extension'];
                            // $newname3 = $rename_name3 . '.' . $file_extension3;
                            $old_name3 = $_FILES['certificate']['name'];
                            // print_r($old_name3);die();
                            $path3 = "uploads/users/document";

                            if (!is_dir($path3)) {
                                mkdir($path3, 0777, true);
                            }
                            $upload_type3 = "jpg|png|jpeg";
                               
                            $condition = array('status' => '1', 'certificate' => $old_name3);
                            $col = array('pk_id','certificate');
                            $checkexistDocument = $this->Md_database->getData('coach_certificate', $col, $condition, 'pk_id', '');
                           
                            if (!empty($checkexistDocument)) {
                                $resultarray = array('error_code' => '4','message' => 'Selected document already exist');
                                echo json_encode($resultarray);
                                exit(); 
                            }
                            $photoDoc3 = $this->Md_database->uploadFile($path3, $upload_type3, "certificate", "", $old_name3); 

                            if (empty($checkexistDocument)) {
                                $table1 = "coach_certificate"; 
                                $insertData['certificate'] = $photoDoc3;      
                                $resultarray = $this->Md_database->insertData($table1, $insertData);
                                $type_id = $this->db->insert_id();
                            }
                        }
                  
                        $table="user";     
                        $condition = array("pk_id" => $uid);
                        $ret = $this->Md_database->updateData($table, $inserted_data, $condition);
                    

                        $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>$uid,'message' => 'Personal data insert successfully');
                        echo json_encode($resultarray);
                        exit(); 
                    }else{
                        $resultarray = array('error_code' => '2', 'message' => 'Email Id already Exist');
                        echo json_encode($resultarray);
                        exit();                             
                    }  
                }else{
                    $resultarray = array('error_code' => '2', 'message' => 'Mobile number already Exist');
                    echo json_encode($resultarray);
                    exit();                             
                } 
            }
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }        
    }
  
    // public function updateMobileOTP(){
    //     $mobileNo = !empty($this->input->post('mobileNo')) ? $this->input->post('mobileNo') : '';
    //     $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';

    //     if (!empty($uid)) {
    //         $table = "user";
    //         $orderby = 'pk_id asc';
    //         $condition = array('status' => '2', 'pk_id' => $uid);
    //         $col = array('pk_id','name');
    //         $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //         if (!empty($checkUser)) {
    //             $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
    //             echo json_encode($resultarray);
    //             exit();
    //         }
    //         $registered_chk = $this->Md_database->getData('user', '*', array('mob' => $mobileNo,'status!=' => '3','pk_id!='=>$uid));

    //         if (!empty($registered_chk)){
    //              $resultarray = array('error_code' => '4', 'message' => 'This mobile number is already registered');
    //             echo json_encode($resultarray);
    //             exit(); 
    //         }else{
    //             $otp = rand(1000, 9999);
    //             $message = 'Hi, ' . $otp.'  is your one time password (OTP) is access in Koodo. Thank you.';
    //             $this->Md_database->sendSMS($message, $mobileno);
    //             $resultarray = array('error_code' => '1', 'otp' => $otp, 'message' => 'otp send on register mobile no.');
    //             echo json_encode($resultarray);
    //             exit(); 
    //         }
    //     }else{
    //         $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
    //         echo json_encode($resultarray);
    //         exit();                       
    //     }        
    // }

    public function personalDocument(){
        $document = !empty($this->input->post('document')) ? $this->input->post('document') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';

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
               // if (!empty($_FILES['document']){
            $photoDoc2 = "";
            if (!empty($_FILES['document']['name'])) {
          //echo ;exit();
                $rename_name2 = uniqid(); //get file extension:
                $arr_file_info2 = pathinfo($_FILES['document']['name']);
                $file_extension2 = $arr_file_info2['extension'];
                // $newname2 = $rename_name2 . '.' . $file_extension2;
                 // print_r($newname2);die();
                $old_name = $_FILES['document']['name'];
                // print_r($old_name);die();
                $path2 = "uploads/users/document";
                if (!is_dir($path2)) {
                    mkdir($path2, 0777, true);
                }
                $upload_type2 = "pdf|doc|docx";

                $photoDoc2 = $this->Md_database->uploadFile($path2, $upload_type2, "document", "", $old_name);   

                $inserted_data = array(                          
                    'document'=>$photoDoc2,                    
                    'status' => 1,   
                    'updatedBy' => $uid, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']    
                );
                $table="user";     
                $condition = array("pk_id" => $uid);
                $ret = $this->Md_database->updateData($table, $inserted_data, $condition);
                $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>$uid,'message' => 'Personal document insert successfully');
                echo json_encode($resultarray);
                exit();                            
            }else{
                $resultarray = array('error_code' => '2','message' => 'Personal document empty');
                echo json_encode($resultarray);
                exit();  
            }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }  
    }

    public function personalCoachCertificate(){
        $document = !empty($this->input->post('certificate')) ? $this->input->post('certificate') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '123';
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
            $insertData = array(                    
                'status' => 1,   
                'user_id' => $uid,   
                'createdBy' => $uid,
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']    
            );
            $photoDoc3 = "";
            if (!empty($_FILES['certificate']['name'])) {
                $rename_name3 = uniqid(); //get file extension:
                $arr_file_info3 = pathinfo($_FILES['certificate']['name']);
                $file_extension3 = $arr_file_info3['extension'];
                $newname3 = $rename_name3 . '.' . $file_extension3;
                 // print_r($newname3);die();
                $old_name = $_FILES['certificate']['name'];
                // print_r($old_name);die();
                $path3 = "uploads/users/document";

                if (!is_dir($path3)) {
                    mkdir($path3, 0777, true);
                }
                $upload_type3 = "pdf|doc|docx";
                // $condition = array('status' => '1', 'certificate' => $newname3,'user_id'=>$uid);
                // $col = array('pk_id');
                // $checkexistDocument = $this->Md_database->getData('coach_certificate', $col, $condition, 'pk_id', '');
                // if (!empty($checkexistDocument)) {
                //     $resultarray = array('error_code' => '4','message' => 'Selected document already exist');
                //     echo json_encode($resultarray);
                //     exit();
                // }
                $photoDoc3 = $this->Md_database->uploadFile($path3, $upload_type3, "certificate", "", $newname3); 
                // print_r($photoDoc3);
                // die();
                // if (empty($checkexistDocument)) {
                    $table1 = "coach_certificate";
                    $insertData['certificate'] = $photoDoc3;        
                    $resultarray = $this->Md_database->insertData($table1, $insertData);
                    $type_id = $this->db->insert_id(); 
                                     
                // }                      
            }
            if (!empty($type_id)) {
                $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>$uid,'message' => 'Personal cerificate of coach  insert successfully');
                echo json_encode($resultarray);
                exit(); 
            }else{
                $resultarray = array('error_code' => '2','message' => 'Something is wrong');
                echo json_encode($resultarray);
                exit(); 
            }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }

    public function sportListProfile(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
            if (!empty($uid)) {
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
                //Sport type 1 List
                $table = "sport";
                $select = "sportname,sportimg,pk_id";
                $condition = array(
                    'status' => '1',
                    'type'=>'1',
                );
                $this->db->order_by('pk_id','DESC');
                $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
               
                //State List
                $table = "state";
                $select = "state_name,pk_id";
                $condition = array(
                    'status' => '1',
                );
                $this->db->order_by('pk_id', 'ASC');
                $state = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
                
                //Sport List type 3
                $table = "sport";
                $select = "sportname,sportimg,pk_id";
                $condition = array(
                    'status' => '1',
                    'type'=>'3',
                );
                $this->db->order_by('pk_id','ASC');
                $otherDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                $resultarray = array('error_code' => '1', 'message' => 'Sport List','sport_list' =>  $sportDetails,'state'=>$state,'otherDetails'=>$otherDetails,'img_path' => base_url().'uploads/master/sportimage/');
                    echo json_encode($resultarray);
                    exit(); 
            }else {
                $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                echo json_encode($resultarray);
                exit();                       
            }            
    }

    public function selectedState_cityList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $state = !empty($this->input->post('state')) ? $this->input->post('state') : '';
            // if (!empty($uid)) {
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
                if (!empty($state)){
                    $table = "city";
                    $select = "city_name,pk_id";
                    $condition = array(
                        'status ' => '1',
                        'state_id'=>$state
                    );

                    $this->db->order_by('city_name', 'ASC');
                    $city = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
                }else{
                    $table = "city";
                    $select = "city_name,pk_id";
                    $condition = array(
                        'status ' => '1',                  
                    );
                    $this->db->order_by('city_name', 'ASC');
                    $Allcity = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
                }
                $table = "user";
                $select = "C.city_name,user.city as cityid";
                $condition = array(
                    'user.status !=' => '3', 
                    'user.pk_id'=>$uid                 
                );
                $this->db->join('city as C', 'C.pk_id = user.city');
                $this->db->order_by('user.pk_id', 'ASC');
                $registerCity = $this->Md_database->getData($table, $select, $condition, 'user.pk_id ASC', '');
                $empty=array();
                 // print_r($Allcity);

                $resultarray = array('error_code' => '1', 'message' => 'City List','city'=>!empty($city)?$city:$empty,'Allcity'=>!empty($Allcity)?$Allcity:$empty,'registerCity'=>$registerCity,'img_path' => base_url().'uploads/master/sportimage/');
                echo json_encode($resultarray);
                exit(); 
                 

            // }else {
            //     $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            //         echo json_encode($resultarray);
            //     exit();                       
            // }            
    }
    public function addBasicProfile(){
        // $playersports='[{"sportname":"sport","pk_id":10,"primary_id":"1","skill":""},{"sportname":"sport2" ,"pk_id":"12","primary_id":"0","skill":"","pro_player_fee":100}]';

        //$playersports="";
        // print_r($playersports1);  
        // die();
        //$coachsports='[{"sportname":"sport","pk_id":18,"primary_id":"1","fee":100},{"sportname":"sport2" ,"pk_id":"19","primary_id":"0","fee":200}]';
        // $type='[{"type":"1"},{"type":"2"},{"type":"3"}]';
     
        $playersports = !empty($this->input->post('playersports')) ? $this->input->post('playersports') : '';
        $coachsports = !empty($this->input->post('coachsports')) ? $this->input->post('coachsports') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
        $type1 = json_decode($type);
        $playersports1 = json_decode($playersports);          
        $coachsports1 = json_decode($coachsports);
        $othersport = !empty($this->input->post('othersport_pkid')) ? $this->input->post('othersport_pkid') : '';
        $primary_id = !empty($this->input->post('othersport_primary_id')) ? $this->input->post('othersport_primary_id') : '';
        $state = !empty($this->input->post('state')) ? $this->input->post('state') : '';
        $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $dob = !empty($this->input->post('dob')) ? $this->input->post('dob') : '';
        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : '';
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : '';
        $address = !empty($this->input->post('address')) ? $this->input->post('address') : '';
        $diff = (date('Y') - date('Y',strtotime($dob)));
        // print_r($diff);
        // die();
        if (!empty($uid)  && !empty($type)) {
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
            $typecount=count($type1);
            $Player ='';
            $Coach ='';
            $Other =''; 
                    
            if ($typecount=="1"){
                $t=$type1[0]->type;
                if ($t=='1'){
                    if(empty($playersports1) || empty($state)||empty($city) || empty($dob) || empty($latitude) || empty($longitude)){
                        $resultarray = array('error_code' => '2','message' => 'playersports1 or state or city or dob or latitude or longitude is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='1';
                        $Coach ='0';
                        $Other ='0';
                        foreach ($type1 as $key => $value){
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>$t,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                        }
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $type_id = $this->db->insert_id();

                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                      
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                             
                        foreach ($playersports1 as $key => $value){
                            $sportname= $value->pk_id;
                            $primary_id= $value->primary_id;
                            $skill= $value->skill;
                            $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';

                            $table="profie_player_sport";
                            $insert_data = array(                            
                                'sportname' => $sportname,
                                'skill' => $skill,
                                'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                                'status' => !empty($pro_player_fee)?'1':'2',
                                'type'=>$type,
                                'primary_id'=>$primary_id,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                          
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $insert_id = $this->db->insert_id();
                        }
                        $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport, 'uid'=>$uid ,'message' => 'Basic data insert successfully');
                        echo json_encode($resultarray);
                        exit();  
                    }
                }elseif ($t=='2'){
                    if(empty($coachsports1)||empty($state)||empty($city) || empty($dob) || empty($latitude) || empty($longitude)){
                        $resultarray = array('error_code' => '2','message' => 'coachsports1 or state or city or dob or latitude or longitude is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='0';
                        $Coach ='1';
                        $Other ='0';
                        foreach ($type1 as $key => $value){
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>$t,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),                
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                        }
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $type_id = $this->db->insert_id();

                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                     
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                             
                        foreach ($coachsports1 as $key => $value){
                            $sportname= $value->pk_id;
                            $primary_id= $value->primary_id;
                            $fee= $value->fee;
                            $table = "profie_player_sport";
                            $insert_data = array(                            
                                'sportname' => $sportname,
                                'status' => '2',
                                'type'=>$type,
                                'fees_hr'=>$fee,
                                'primary_id'=>$primary_id,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $insert_id = $this->db->insert_id();
                        }
                        $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport, 'uid'=>$uid ,'message' => 'Basic data insert successfully');
                        echo json_encode($resultarray);
                         exit();
                    }  
                }elseif ($t=='3'){
                    if(empty($othersport)||empty($state)||empty($city)  || empty($dob) || empty($latitude) || empty($longitude)){
                        $resultarray = array('error_code' => '2','message' => 'othersport or state or city or latitude or longitude or dob is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='0';
                        $Coach ='0';
                        $Other ='1';
                        foreach ($type1 as $key => $value){
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>$t,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                        }
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $type_id = $this->db->insert_id();

                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                      
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          

                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $othersport,
                            'status' => '2',
                            'type'=>$type,
                            'primary_id'=>$primary_id,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $insert_id = $this->db->insert_id();
                        $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport,'uid'=>$uid ,'message' => 'Basic data insert successfully');
                        echo json_encode($resultarray);
                        exit(); 
                    }
                }
            }elseif($typecount=="2"){     
                $t1=$type1[0]->type;
                $t2=$type1[1]->type;
                if (($t1=='1' && $t2=='2')||($t1=='2' && $t2=='1')) {
                    if(empty($playersports1) || empty($coachsports1)||empty($state)||empty($city) || empty($dob) || empty($latitude) || empty($longitude) ){
                        $resultarray = array('error_code' => '2','message' => 'playersports1 or coachsports1 or state or city or dob or latitude or longitude is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='1';
                        $Coach ='1';
                        $Other ='0';
                        foreach ($type1 as $key => $value){
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>$type,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $type_id = $this->db->insert_id();
                        }
                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                    
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                            foreach ($playersports1 as $key => $value){
                                $sportname= $value->pk_id;
                                $primary_id= $value->primary_id;
                                $skill= $value->skill;
                                $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';
                                $table="profie_player_sport";
                                $insert_data = array(                            
                                    'sportname' => $sportname,
                                    'skill' => $skill,
                                    'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                                    'status' => !empty($pro_player_fee)?'1':'2',
                                    'type'=>'1',
                                    'primary_id'=>$primary_id,
                                    'user_id' => $uid,
                                    'createdBy' => $uid,
                                    'createdDate' => date('Y-m-d H:i:s'),
                                    'created_ip_address' => $_SERVER['REMOTE_ADDR']
                                );
                                $resultarray = $this->Md_database->insertData($table, $insert_data);
                                $insert_id = $this->db->insert_id();
                            }
                            foreach ($coachsports1 as $key => $value){
                                $sportname= $value->pk_id;
                                $primary_id= $value->primary_id;
                                $fee= $value->fee;
                                $table = "profie_player_sport";
                                $insert_data = array(                            
                                    'sportname' => $sportname,
                                    'status' => '2',
                                    'type'=>'2',
                                    'fees_hr'=>$fee,
                                    'primary_id'=>$primary_id,
                                    'user_id' => $uid,
                                    'createdBy' => $uid,
                                    'createdDate' => date('Y-m-d H:i:s'),
                                    'created_ip_address' => $_SERVER['REMOTE_ADDR']
                                );
                                $resultarray = $this->Md_database->insertData($table, $insert_data);
                                $insert_id = $this->db->insert_id();
                            }
                            $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport,'uid'=>$uid ,'message' => 'Basic data insert successfully');
                            echo json_encode($resultarray);
                            exit();  
                    }
                }elseif (($t1=='3' && $t2=='2')||($t1=='2' && $t2=='3')) {
                    if(empty($coachsports1)|| empty($othersport) ||empty($state)||empty($city) || empty($dob) || empty($latitude) || empty($longitude)){
                        $resultarray = array('error_code' => '2','message' => 'coachsports1 or othersport or state or city or latitude or longitude or dob is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='0';
                        $Coach ='1';
                        $Other ='1';
                        foreach ($type1 as $key => $value) {
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>$type,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $type_id = $this->db->insert_id();
                        }
                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                      
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                        foreach ($coachsports1 as $key => $value) {
                            $sportname= $value->pk_id;
                            $primary_id= $value->primary_id;
                            $fee= $value->fee;
                            $table = "profie_player_sport";
                            $insert_data = array(                            
                                'sportname' => $sportname,
                                'status' => '2',
                                'type'=>'2',
                                'fees_hr'=>$fee,
                                'primary_id'=>$primary_id,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $insert_id = $this->db->insert_id();
                        }
                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $othersport,
                            'status' => '2',
                            'type'=>'3',
                            'primary_id'=>$primary_id,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $insert_id = $this->db->insert_id();
                          
                    
                        $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport, 'uid'=>$uid ,'message' => 'Basic data insert successfully');
                        echo json_encode($resultarray);
                    }exit(); 
                }elseif (($t1=='3' && $t2=='1')||($t1=='1' && $t2=='3')){
                    if(empty($othersport) || empty($playersports1)||empty($state)||empty($city)  || empty($dob) || empty($latitude) || empty($longitude)){
                        $resultarray = array('error_code' => '2','message' => 'othersport or playersports1 or city or state or latitude or longitude or dob is empty');
                        echo json_encode($resultarray);
                        exit();                           
                    }else{
                        $Player ='1';
                        $Coach ='0';
                        $Other ='1';
                        foreach ($type1 as $key => $value) {
                            $type= $value->type;
                            $table = "profile_type";
                            $insert_data = array( 
                                 'usertype'=>$type,
                                 'user_id' => $uid,
                                 'createdBy' => $uid,
                                 'createdDate' => date('Y-m-d H:i:s'),
                                 'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $type_id = $this->db->insert_id();
                        }
                        $table = "user";
                        $insert_data = array(
                            'latitude'=>$latitude,
                            'longitude'=>$longitude,
                            'dob'=>$dob,
                            'address'=>$address,
                            'age'=>$diff,
                            'city'=> $city,
                            'state'=> $state,                    
                            'status' => '1',
                            'updatedBy' => $uid, 
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                        );
                        $condition = array(
                            'pk_id'=>$uid
                        );
                        $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                             
                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $othersport,
                            'status' => '2',
                            'type'=>'3',
                            'primary_id'=>$primary_id,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $insert_id = $this->db->insert_id();
                        foreach ($playersports1 as $key => $value){
                            $sportname= $value->pk_id;
                            $primary_id= $value->primary_id;
                            $skill= $value->skill;
                            $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';
                            
                            $table = "profie_player_sport";
                            $table = "profie_player_sport";
                            $insert_data = array(                            
                                'sportname' => $sportname,
                                'skill' => $skill,
                                'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                                'status' => !empty($pro_player_fee)?'1':'2',
                                'type'=>'3',
                                'primary_id'=>$primary_id,
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $insert_id = $this->db->insert_id();
                        }
                        $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other, 'other_id'=>$othersport,'uid'=>$uid ,'message' => 'Basic data insert successfully');
                        echo json_encode($resultarray);
                        exit(); 
                    }
                }
            }elseif ($typecount=="3") {
                $t1=$type1[0]->type;
                $t2=$type1[1]->type;
                $t2=$type1[2]->type;

                if(empty($playersports1) || empty($coachsports1) || empty($othersport) ||empty($state)||empty($city)  || empty($dob) || empty($latitude) || empty($longitude)){
                    $resultarray = array('error_code' => '2','message' => 'playersports1 or coachsports1 or othersport or city or state or latitude or longitude or dob is empty');
                    echo json_encode($resultarray);
                    exit();                           
                }else{
                    $Player ='1';
                    $Coach ='1';
                    $Other ='1';
                    foreach ($type1 as $key => $value) {
                        $type= $value->type;
                        $table = "profile_type";
                        $insert_data = array( 
                            'usertype'=>$type,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $type_id = $this->db->insert_id();
                    }
                    $table = "user";
                    $insert_data = array(
                        'latitude'=>$latitude,
                        'longitude'=>$longitude,
                        'dob'=>$dob,
                        'address'=>$address,
                        'age'=>$diff,
                        'city'=> $city,
                        'state'=> $state,                    
                        'status' => '1',
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                    );
                    $condition = array(
                        'pk_id'=>$uid
                    );
                    $resultarray = $this->Md_database->updateData($table, $insert_data, $condition);                          
                    foreach ($playersports1 as $key => $value) {
                        $sportname= $value->pk_id;
                        $primary_id= $value->primary_id;
                        $skill= $value->skill;
                        $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';
                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $sportname,
                            'skill' => $skill,
                            'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                            'status' => !empty($pro_player_fee)?'1':'2',
                            'type'=>'1',
                            'primary_id'=>$primary_id,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $insert_id = $this->db->insert_id();
                    }
                    foreach ($coachsports1 as $key => $value) {
                        $sportname= $value->pk_id;
                        $primary_id= $value->primary_id;
                        $fee= $value->fee;
                        $table = "profie_player_sport";
                        $insert_data = array(                            
                            'sportname' => $sportname,
                            'status' => '2',
                            'fees_hr'=>$fee,
                            'type'=>'2',
                            'primary_id'=>$primary_id,
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $insert_id = $this->db->insert_id();
                    }
                    $table = "profie_player_sport";
                    $insert_data = array(                            
                        'sportname' => $othersport,
                        'status' => '2',
                        'type'=>'3',
                        'primary_id'=>$primary_id,
                        'user_id' => $uid,
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                    $insert_id = $this->db->insert_id();
                    
                    $resultarray = array('error_code' => '1','Player'=>$Player,'Coach'=>$Coach,'Other'=>$Other,'other_id'=>$othersport, 'uid'=>$uid ,'message' => 'Basic data insert successfully');
                    echo json_encode($resultarray);
                    exit();                           
                }                
            }                     
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or type is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function addPlayerProfile(){
        // $sport='[{"sportname":"10" ,"pk_id":"10","primary_id":"0","skill":"yg","pro_player_fee":100},{"sportname":"11" ,"pk_id":"11","primary_id":"0","skill":"yu"}]';

        $sport = !empty($this->input->post('sports')) ? $this->input->post('sports') : '';
        $sports = json_decode($sport);

        $achivement = !empty($this->input->post('achivement')) ? $this->input->post('achivement') : '';
        $clubDetail = !empty($this->input->post('club_detail')) ? $this->input->post('club_detail') : '';
        $experience = !empty($this->input->post('experience')) ? $this->input->post('experience') : '';
        $aboutMe = !empty($this->input->post('about_me')) ? $this->input->post('about_me') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $profile_id = !empty($this->input->post('profile_id')) ? $this->input->post('profile_id') : '';
        $looking_for_coach = !empty($this->input->post('looking_for_coach')) ? $this->input->post('looking_for_coach') : '';
 // print_r($sports);
 //        die();
        if (!empty($uid)){
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
             
            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'user.pk_id' => $uid);
            $col = array('user.pk_id');
            $this->db->join('profile_type as PT', 'PT.user_id = user.pk_id');
            $this->db->join('usertype as UT', 'UT.pk_id = PT.usertype');
            $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            if (empty($checkUserProfile)) {
                $resultarray = array('error_code' => '4', 'message' => 'User Not register for Player Profile ');
                echo json_encode($resultarray);
                exit();
            }
                if (empty($profile_id)) {
                    // if(empty($achivement) || empty($clubDetail) || empty($experience) || empty($aboutMe)) {
                    //     $resultarray = array('error_code' => '2', 'message' => 'skilLevel or achivement or clubDetail or experience is empty');
                    //     echo json_encode($resultarray);
                    //     exit();
                    // }else{  
                        $table = "profile_type";
                        $orderby = 'pk_id asc';
                        $condition = array('status' => '1', 'user_id' => $uid,'usertype' => 1);
                        $col = array('pk_id');
                        $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        if (empty($checkUserProfile)) {
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>'1',
                                'user_id' => $uid,
                                'searching_for_sport_partner'=> $aboutMe, 
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $type_id = $this->db->insert_id();
                        }
                        $table = "user_profile_detail";
                        $insert_data = array(
                            'usertype'=>'1',
                            'achivement'=> $achivement,
                            'club_detail'=> $clubDetail,
                            'experience'=> $experience,                        
                            'looking_for_coach'=> $looking_for_coach,                    
                                                   
                            'status' => '1',
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $profile_id = $this->db->insert_id();
                        
                        //looking for coach notification
                            $table = "privileges_notifications";
                            $select = "notifications,chat_notification";
                            $this->db->join('user','privileges_notifications.fk_uid = user.pk_id');
                            $this->db->order_by('privileges_notifications.pk_id','ASC');
                            $chechprivilege = $this->Md_database->getData($table, $select, '', 'privileges_notifications.pk_id ASC', '');
                            $notification=($chechprivilege[0]['notifications']);

                            if ($notification == '1') {
                                if (!empty($looking_for_coach) && $looking_for_coach==1){
                                    $table = "user";
                                    $orderby = 'user.pk_id asc';
                                    $condition = array('user.status' => '1', 'PT.usertype' =>'2');
                                    $col = array('user.pk_id,token','name');
                                    $this->db->join('profile_type as PT','PT.user_id = 
                                        user.pk_id');
                                    $targets= $this->Md_database->getData($table, $col, $condition, $orderby, '');

                                    $table = "user";
                                    $orderby = 'user.pk_id asc';
                                    $condition = array('pk_id'=>$uid);
                                    $col = array('user.pk_id,token','name');
                                    $user_name= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                                    $name=$user_name[0]['name'];
                                    $message=ucwords($name)." looking for Coach";
                                                          
                                    $resultarray = array('message' => $message,'redirect_type' =>'looking_for_coach','subject'=>'Looking for coach');
                                    
                                    foreach ($targets as $key => $value) {
                                        $target=$value['token'];
                                        $user_id=$value['pk_id'];
                                        $this->Md_database->sendPushNotification($resultarray,$target);

                                        $table = "custom_notification"; 
                                        $insert_data = array(
                                            // 'type' => $type,
                                           'from_uid'=>$uid,
                                           'to_user_id'=>$user_id,
                                           'usertype'=>2,
                                            'redirect_type' => 'looking_for_coach',
                                            'subject' => 'Looking for coach',
                                            'message' =>  $message,
                                            'status' => '1',
                                            'created_by ' => $uid,
                                            'created_date' => date('Y-m-d H:i:s'),
                                            'created_ip_address'=>$_SERVER['REMOTE_ADDR']                                         
                                        );
                                        $result = $this->Md_database->insertData($table, $insert_data);
                                    }
                                }
                            }
                            //update sports
                            if (!empty($sports )){
                                $table = "profie_player_sport";                         
                                $condition = array("user_id" => $uid,'type'=>'1');
                                $resultarray = $this->Md_database->deleteData($table, $condition);
                                foreach($sports as $key => $value) {
                                    $sportname= $value->pk_id;
                                    $primary_id= $value->primary_id;
                                    $skill= $value->skill;
                                    $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';
                                    // print_r($pro_player_fee);
                                    // die();
                                    $table = "profie_player_sport";
                                    $insert_data = array(                            
                                        'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                                        'status' => !empty($pro_player_fee)?'1':'2',
                                        'type'=>'1',
                                        'user_id' => $uid,
                                        'createdBy' => $uid,
                                        'createdDate' => date('Y-m-d H:i:s'),                
                                        'created_ip_address' => $_SERVER['REMOTE_ADDR']            
                                    );
                                    $insert_data['sportname']=$sportname;
                                    $insert_data['primary_id']=$primary_id;
                                    $insert_data['skill']=$skill;

                                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                                    $insert_id = $this->db->insert_id();
                            }
                        }else{
                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'1');
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
                                // 'pk_id'=>$profile_id,
                                'usertype'=>1
                            );
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                            $table = "profile_type";                         
                            $condition = array("user_id" => $uid,'usertype'=>1);
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            // $table = "profile_type";
                            // $update_data = array(                        
                            //    'status' => '3',
                            //    'updatedBy' => $uid,
                            //    'updatedDate' => date('Y-m-d H:i:s'),
                            //    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            // );
                            // $condition = array(
                            //     'user_id' => $uid,
                            //     // 'pk_id'=>$profile_id,
                            //     'usertype'=>1
                            // );
                            // $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                        }       
                        $resultarray = array('error_code' => '1','profile_id'=>!empty($sports)?$profile_id:'','Player'=>!empty($sports)?'1':'0', 'uid'=>$uid ,'looking_for_coach'=>$looking_for_coach,'message' => 'Personal data insert successfully');
                        echo json_encode($resultarray);
                        exit();                       
                    // }    
                }else{
                    // if(empty($achivement) || empty($clubDetail) || empty($experience) || empty($aboutMe)) {
                    //     $resultarray = array('error_code' => '2', 'message' => 'achivement or clubDetail or experience or about_me is empty');
                    //     echo json_encode($resultarray);
                    //     exit();
                    // }else{
                        $table = "user_profile_detail";
                        $update_data = array(
                           'looking_for_coach'=> $looking_for_coach,
                           'achivement'=> $achivement,
                           'club_detail'=> $clubDetail,
                           // 'about_me'=>$aboutMe,
                           'experience'=> $experience,                        
                           'status' => '1',
                           'updatedBy' => $uid,
                           'updatedDate' => date('Y-m-d H:i:s'),
                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'pk_id'=>$profile_id,
                            'usertype'=>1
                        );
                        $resultarray = $this->Md_database->updateData($table, $update_data, $condition);


                        $tablename = "profile_type";
                        $update_data = array( 
                            'usertype'=>'1',
                            'user_id' => $uid,
                            'searching_for_sport_partner'=> $aboutMe, 
                            'updatedBy' => $uid,
                            'status' => 1,
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $condition = array(
                            'user_id' => $uid,
                            'usertype'=>1
                        );
                        $resultarray = $this->Md_database->updateData($tablename, $update_data, $condition);
                        
                        //looking for coach
                        $table = "privileges_notifications";
                        $select = "notifications,chat_notification";
                        $this->db->join('user','privileges_notifications.fk_uid = user.pk_id');
                        $this->db->order_by('privileges_notifications.pk_id','ASC');
                        $chechprivilege = $this->Md_database->getData($table, $select, '', 'privileges_notifications.pk_id ASC', '');
                        $notification=($chechprivilege[0]['notifications']);

                        if ($notification == '1') {
                            if (!empty($looking_for_coach) &&$looking_for_coach==1){
                                $table = "user";
                                $orderby = 'user.pk_id asc';
                                $condition = array('user.status' => '1', 'PT.usertype' =>'2');
                                $col = array('user.pk_id,token');
                                $this->db->join('profile_type as PT', 'PT.user_id = user.pk_id');
                                $target= $this->Md_database->getData($table, $col, $condition, $orderby, '');

                                $table = "user";
                                $orderby = 'user.pk_id asc';
                                $condition = array('pk_id'=>$uid);
                                $col = array('user.pk_id,token','name');
                                $user_name= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                                $name=$user_name[0]['name'];
                                $message=ucwords($name)." looking for Coach";
                                
                                //$message="Looking for coach";
                                                      
                                foreach ($target as $key => $value) {
                                    $resultarray = array('message' => $message,'redirect_type' =>'looking_for_coach','subject'=>'Looking for coach');
                                    $tokens=$value['token'];
                             
                                    $user_id=$value['pk_id'];
                                    $result= $this->Md_database->sendPushNotification($resultarray,$tokens);

                                    $table = "custom_notification"; 
                                    $insert_data = array(
                                       'from_uid'=>$uid,
                                       'to_user_id'=>$user_id,
                                       'usertype'=>2,
                                        'redirect_type' => 'looking_for_coach',
                                        'subject' => 'Looking for coach',
                                        'message' =>  $message,
                                        'status' => '1',
                                        'created_by ' => $uid,
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                                    );
                                    $result = $this->Md_database->insertData($table, $insert_data);
                                }
                            }
                        }
                 
                        if (!empty($sports)) {
                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'1');
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            foreach ($sports as $key => $value){
                                $sportname= $value->pk_id;
                                $primary_id= $value->primary_id;
                                $skill= !empty($value->skill)?$value->skill:'';
                                $pro_player_fee= !empty($value->pro_player_fee)?$value->pro_player_fee:'';
                                $table = "profie_player_sport";
                                $insert_data2 = array(                            
                                    'fees_hr' => !empty($pro_player_fee)?$pro_player_fee:'0',
                                    'status' => !empty($pro_player_fee)?'1':'2',
                                    'user_id' => $uid,
                                    'type'=>'1',
                                    'createdBy' => $uid,
                                    'createdDate' => date('Y-m-d H:i:s'),                
                                    'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                                );
                                $insert_data2['sportname']=$sportname;
                                $insert_data2['primary_id']=$primary_id;
                                $insert_data2['skill']=$skill;
                                $resultarray = $this->Md_database->insertData($table, $insert_data2);                        
                            } 
                        }else{
                             $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'1');
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
                                'usertype'=>1
                            );
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                            $table = "profile_type";                         
                            $condition = array("user_id" => $uid,'usertype'=>1);
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            // $table = "profile_type";
                            // $update_data = array(                        
                            //    'status' => '3',
                            //    'updatedBy' => $uid,
                            //    'updatedDate' => date('Y-m-d H:i:s'),
                            //    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            // );
                            // $condition = array(
                            //     'user_id' => $uid,
                            //     // 'pk_id'=>$profile_id,
                            //     'usertype'=>1
                            // );
                            // $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                        }
                                               
                        $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>!empty($sports)?$profile_id:'','Player'=>!empty($sports)?'1':'0','looking_for_coach'=>
                        $looking_for_coach ,'message' => 'Profile data update successfully');
                        echo json_encode($resultarray);
                        exit();                       
                    // }     
                }
            }else {
                $resultarray = array('error_code' => '3', 'message' => 'Uid  is empty');
                echo json_encode($resultarray);
                exit();                       
            } 
    }


    public function  viewProfile(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $Player = !empty($this->input->post('Player')) ? $this->input->post('Player') : '';
        $Coach = !empty($this->input->post('Coach')) ? $this->input->post('Coach') : '';
        $Other = !empty($this->input->post('Other')) ? $this->input->post('Other') : '';
     
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User  is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $cityList=array();
            $table = "city";
            $orderby = 'city.pk_id asc';
            $this->db->join('user','user.state = city.state_id');
            $condition = array('city.status' => '1');
            $this->db->group_by("city.pk_id");
            $col = array('city.pk_id','city.city_name');
            $cityList = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            if ($uid){
                $table = "user";
                $orderby = 'user.pk_id asc';
                $condition = array('user.status' => '1', 'user.pk_id' => $uid);
                $this->db->join('city','user.city = city.pk_id');
                $this->db->join('privileges_notifications as PN','user.pk_id = PN.fk_uid','left');
                $this->db->join('buy_subscription as BS','user.pk_id = BS.user_id','left');
                $this->db->join('state','user.state = state.pk_id');
                $col = array('user.pk_id','mobStatus','emailStatus','name','regdate','email','city.city_name','state.state_name','dob','gender','age','address','edudetails','document','occupation','img','mob','BS.category','PN.available','COALESCE(playing_time,"") as playing_time','contact_detail','latitude','longitude','doc_verify','verifyEmail');
                $userData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                if (!empty($userData[0]['doc_verify']) && $userData[0]['doc_verify'] =='1' && ($userData[0]['email'] == $userData[0]['verifyEmail'])) {
                    $tick_on_app = '1';//yes
                }else{
                     $tick_on_app = '2';//No
                }
            }
            $table = "sport";
            $select = 'pk_id,sportname,sportimg';        
            $condition = array(
                'status' => '1', 
                'type'=>'1'        
            );
            $sportList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            $table = "sport";
            $select = 'pk_id,sportname,sportimg';        
            $condition = array(
                'status' => '1', 
                'type'=>'3'        
            );
            $otherList = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');

            $table = "career";
            $select = 'expected_salary,cv,qualification,profile';        
            $condition = array(
                'status ' => '1', 
                'user_id'=>$uid        
            );
            $careerDetail = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            $table = "user_profile_detail";
            $select = 'user_id,pk_id,usertype';        
            $condition = array(
                'status !=' => '3', 
                'user_id'=>$uid ,
                'usertype'=>'1'       
            );
            $profile_idPlayer = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            
            $table = "user_profile_detail";
            $select = 'user_id,pk_id,usertype';        
            $condition = array(
                'status !=' => '3', 
                'user_id'=>$uid ,
                'usertype'=>'2'       
            );
            $profile_idCoach = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            
            $table = "user_profile_detail";
            $select = 'user_id,pk_id,usertype';        
            $condition = array(
                'status !=' => '3', 
                'user_id'=>$uid ,
                'usertype'=>'3'       
            );
            $profile_idOther = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            if (!empty($Player)||  !empty($Coach)  ||  !empty($Other)) {
                if ($Player == "1") {
                    $type="1";
                    $table = "profile_type";
                    $orderby = 'profile_type.pk_id asc';
                    $condition = array('profile_type.status' => '1', 'profile_type.user_id' => $uid,'profile_type.usertype'=>'1');
                    // $this->db->join('user_profile_detail', 'profile_type.user_id = user_profile_detail.user_id');
                    $col = array('profile_type.pk_id','profile_type.searching_for_sport_partner');
                    $chackuserreg = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                    // print_r($chackuserreg)  ;die();                 
                    if (empty($chackuserreg )) {
                        $resultarray = array('error_code' => '6', 'message' => 'User Not register for Player Profile');
                        echo json_encode($resultarray);
                        exit();  
                    }
                    $table = "profie_player_sport";
                    $select = 'COALESCE(skill," ") as skill';       
                    $condition = array(
                         'status !=' => '3',
                         'user_id'=>$uid,
                         'type'=>'1',       
                         'primary_id'=>'1',       
                    );
                    $player_skill_primary_sport = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            
                    $table = "profie_player_sport";
                    $select = 'sport.sportname,primary_id,sport.pk_id,sport.sportimg,COALESCE(skill," ") as skill,fees_hr'; 
                    $this->db->join('sport', 'sport.pk_id = profie_player_sport.sportname');       
                    $condition = array(
                         'profie_player_sport.status !=' => '3',
                         'user_id'=>$uid,
                         'profie_player_sport.type'=>'1'         
                    );
                    $playerselectedsport = $this->Md_database->getData($table, $select, $condition, 'primary_id DESC', '');

                                                     
                     $table = "user_profile_detail";
                     $orderby = 'pk_id asc';
                     $condition = array('status' => '1', 'user_id' => $uid,'usertype'=>'1');
                     $col = array('experience','achivement','about_me','club_detail');
                     $profiledata3 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $table = "user_review";
                    $select = 'AVG(rate) as average, count(fk_for) as count';        
                    $condition = array(
                         'status' => '1',
                         'fk_for'=>$uid,
                         'type'=> '1'        
                    );
                     $this->db->group_by('fk_for');
                     $ratingPlayer = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                
                    $PlayerDeatail=array( 'experience' => (!empty($profiledata3[0]['experience']) ? $profiledata3[0]['experience'] : ''),'achivement' => (!empty($profiledata3[0]['achivement']) ? $profiledata3[0]['achivement'] : ''),'about_me' => (!empty($chackuserreg[0]['searching_for_sport_partner']) ? $chackuserreg[0]['searching_for_sport_partner'] : ''),'club_detail' => (!empty($profiledata3[0]['club_detail']) ? $profiledata3[0]['club_detail'] : ''),'rating_user_no' => (!empty($ratingPlayer[0]['count']) ? $ratingPlayer[0]['count'] : 0),'rating_average' => (!empty($ratingPlayer[0]['average']) ? $ratingPlayer[0]['average'] : 0),'skill' => (!empty($player_skill_primary_sport[0]['skill']) ? $player_skill_primary_sport[0]['skill'] : ''));

                }else{
                $PlayerDeatail=array( 'experience' => '','achivement' => '','about_me' => '','skill' => '','club_detail' => '','rating_user_no' => '0','rating_average' => '0');
                }
                if ($Coach == "1"){
                    $type="2";   
                    $table = "profile_type";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1', 'user_id' => $uid,'usertype'=>'2');
                    $col = array('pk_id');
                    $chackuserreg = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    if (empty($chackuserreg )){
                        $resultarray = array('error_code' => '6', 'message' => 'User Not register for Coach Profile');
                        echo json_encode($resultarray);
                        exit();                        
                    }  
                    $table = "profie_player_sport";
                    $select = 'COALESCE(skill," ") as skill';       
                    $condition = array(
                        'status !=' => '3',
                        'user_id'=>$uid,
                        'type'=>'2',       
                        'primary_id'=>'1',       
                    );
                    $coach_skill_primary_sport = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                    $table = "profie_player_sport";
                    $select = 'sport.sportname,primary_id,sport.pk_id,sport.sportimg,COALESCE(fees_hr," ") as skill'; 
                    $this->db->join('sport','sport.pk_id = profie_player_sport.sportname');       
                    $condition = array(
                         'profie_player_sport.status !=' => '3',
                         'user_id'=>$uid,
                         'profie_player_sport.type'=>'2'         
                    );
                    $coachselectedsport = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                    $table = "user_review";
                    $select = 'AVG(rate) as average, count(fk_for) as count';        
                    $condition = array(
                        'status' => '1',
                        'fk_for'=>$uid,
                        'type'=> '2'        
                    );
                    $this->db->group_by('fk_for');
                    $ratingCoach = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                    $table = "user_profile_detail";
                    $orderby = 'user_profile_detail.pk_id asc';
                    $condition = array('user_profile_detail.status' => '1', 'user_profile_detail.user_id' => $uid,'usertype'=>'2');
                    $col = array('experience','achivement','user_profile_detail.skill','club_detail','club_technique','user.coach_category','user.coach_category_level');
                    $this->db->join('user', 'user.pk_id = user_profile_detail.user_id');
                    $profiledata4 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $CoachDeatail=array('coach_category' => (!empty($profiledata4[0]['coach_category']) ? $profiledata4[0]['coach_category'] : ''),'coach_category_level' => (!empty($profiledata4[0]['coach_category_level']) ? $profiledata4[0]['coach_category_level'] : ''),'experience' => (!empty($profiledata4[0]['experience']) ? $profiledata4[0]['experience'] : ''),'achivement' => (!empty($profiledata4[0]['achivement']) ? $profiledata4[0]['achivement'] : ''),'about_me' => (!empty($profiledata4[0]['about_me']) ? $profiledata4[0]['about_me'] : ''),'skill' => (!empty($profiledata4[0]['skill']) ? $profiledata4[0]['skill'] : ''),'club_detail' => (!empty($profiledata4[0]['club_detail']) ? $profiledata4[0]['club_detail'] : ''),'club_technique' => (!empty($profiledata4[0]['club_technique']) ? $profiledata4[0]['club_technique'] : ''),'rating_user_no' => (!empty($ratingCoach[0]['count']) ? $ratingCoach[0]['count'] : '0'),'rating_average' => (!empty($ratingCoach[0]['average']) ? $ratingCoach[0]['average'] : '0'),'skill' => (!empty($coach_skill_primary_sport[0]['skill']) ? $coach_skill_primary_sport[0]['skill'] : ''));
                 
                        $days=array();
                        $coachbatchDays=array();
                        $table = "coach_batches";
                        $orderby = 'coach_batches.pk_id DESC';
                        $condition = array('coach_batches.status' => '1', 'coach_batches.user_id' => $uid);
                        $col = array('coach_batches.pk_id','DATE_FORMAT(start_time, "%h:%i %p") as start_time','DATE_FORMAT(end_time, "%h:%i %p") as end_time','venue','place','fees','studentNo as no_students','days','batch_sport');
                        $coachbatchDays = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }else{
                $CoachDeatail=array( 'experience' =>  '','achivement' => '','about_me' =>  '','club_detail' => '','club_technique' =>  '','rating_user_no' => '0','rating_average' => '0','skill'=>'');
                }
                if ($Other == "1"){
                    $type="3";
                    $table = "profile_type";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1', 'user_id' => $uid,'usertype'=>'3');
                    $col = array('pk_id');
                    $chackuserreg = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    if (empty($chackuserreg )){
                        $resultarray = array('error_code' => '6', 'message' => 'User Not register for Other Profile');
                        echo json_encode($resultarray);
                        exit();                         
                    }

                    $table = "profie_player_sport";
                    $select = 'sport.sportname,primary_id,sport.pk_id,sport.sportimg,COALESCE(skill," ") as skill'; 
                    $this->db->join('sport', 'sport.pk_id = profie_player_sport.sportname');       
                    $condition = array(
                         'profie_player_sport.status !=' => '3',
                         'user_id'=>$uid,
                         'profie_player_sport.type'=>'3'         
                    );
                    $otherselectedsports = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
                    $otherselectedsport = (!empty($otherselectedsports[0]['pk_id']) ? $otherselectedsports[0]['pk_id'] : '');
                    // $otherselectedsport=array( 'sportname' => (!empty($otherselectedsports[0]['sportname']) ? $otherselectedsports[0]['sportname'] : ''),'primary_id' => (!empty($otherselectedsports[0]['primary_id']) ? $otherselectedsports[0]['primary_id'] : ''),'pk_id' => (!empty($otherselectedsports[0]['pk_id']) ? $otherselectedsports[0]['pk_id'] : ''),'sportimg' => (!empty($otherselectedsports[0]['sportimg']) ? $otherselectedsports[0]['sportimg'] : ''),'sportimg' => (!empty($otherselectedsports[0]['sportimg']) ? $otherselectedsports[0]['sportimg'] : ''));

                       
                        $table = "user_profile_detail";
                        $orderby = 'pk_id asc';
                        $condition = array('status' => '1', 'user_id' => $uid,'usertype'=>'3');
                        $col = array('experience','achivement','about_me','visting_fees','website');
                        $profiledata5 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                        $table = "user_review";
                        $select = 'AVG(rate) as average, count(fk_for) as count';        
                        $condition = array(
                           'status' => '1',
                           'fk_for'=>$uid,
                           'type'=> '1'        
                        );
                        $this->db->group_by('fk_for');
                        $ratingOther = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                        $OtherDeatail=array( 'experience' => (!empty($profiledata5[0]['experience']) ? $profiledata5[0]['experience'] : ''),'achivement' => (!empty($profiledata5[0]['achivement']) ? $profiledata5[0]['achivement'] : ''),'website' => (!empty($profiledata5[0]['website']) ? $profiledata5[0]['website'] : ''),'about_me' => (!empty($profiledata5[0]['about_me']) ? $profiledata5[0]['about_me'] : ''),'visting_fees' => (!empty($profiledata5[0]['visting_fees']) ? $profiledata5[0]['visting_fees'] : ''),'rating_user_no' => (!empty($ratingCoach[0]['count']) ? $ratingCoach[0]['count'] : '0'),'rating_average' => (!empty($ratingCoach[0]['average']) ? $ratingCoach[0]['average'] : '0'));

                }else{
                $OtherDeatail=array( 'experience' =>'','achivement' => '','about_me' => '','visting_fees' => '','rating_user_no' => '0','rating_average' =>'0','website'=>'');
                }
            }               
            $empty=array();
            $resultarray = array('error_code' => '1', 'message' => 'profile Data ','pk_id' => (!empty($userData[0]['pk_id']) ? $userData[0]['pk_id'] : ''),'name' => (!empty($userData[0]['name']) ? $userData[0]['name'] : ''),'mobile' => (!empty($userData[0]['mob']) ? $userData[0]['mob'] : ''),'email' => (!empty($userData[0]['email']) ? $userData[0]['email'] : ''),'available' => (!empty($userData[0]['available']) ? $userData[0]['available'] : ''),'plan' => (!empty($userData[0]['category']) ? $userData[0]['category'] : ''),'mobStatus' => (!empty($userData[0]['mobStatus']) ? $userData[0]['mobStatus'] : ''),'emailStatus' => (!empty($userData[0]['emailStatus']) ? $userData[0]['emailStatus'] : ''),'regdate' => (!empty($userData[0]['regdate']) ? $userData[0]['regdate'] : ''),'dob' => (!empty($userData[0]['dob']) ? $userData[0]['dob'] : ''),'gender' => (!empty($userData[0]['gender']) ? $userData[0]['gender'] : ''),'age' => (!empty($userData[0]['age']) ? $userData[0]['age'] : ''),'latitude' => (!empty($userData[0]['latitude']) ? $userData[0]['latitude'] : ''),'longitude' => (!empty($userData[0]['longitude']) ? $userData[0]['longitude'] : ''),'contact_detail' => (!empty($userData[0]['contact_detail']) ? $userData[0]['contact_detail'] : ''),'playing_time' => (!empty($userData[0]['playing_time']) ? $userData[0]['playing_time'] : ''),'address' => (!empty($userData[0]['address']) ? $userData[0]['address'] : ''),'edudetails' => (!empty($userData[0]['edudetails']) ? $userData[0]['edudetails'] : ''),'document' => (!empty($userData[0]['document']) ? $userData[0]['document'] : ''),'occupation' => (!empty($userData[0]['occupation']) ? $userData[0]['occupation'] : ''),'profile_img' => (!empty($userData[0]['img']) ? $userData[0]['img'] : ''),'searching_for_sport_partner' => (!empty($userData[0]['searching_for_sport_partner']) ? $userData[0]['searching_for_sport_partner'] : ''),'city' => (!empty($userData[0]['city_name']) ? $userData[0]['city_name'] : ''),'state_name' => (!empty($userData[0]['state_name']) ? $userData[0]['state_name'] : ''),'doc_verify' => (!empty($tick_on_app) ? $tick_on_app : ''),'profile_path' => base_url().'uploads/users/','document_path' => base_url().'uploads/document/','PlayerDetail'=>!empty($PlayerDeatail)?$PlayerDeatail:[] ,'CoachDetail'=>!empty($CoachDeatail)?$CoachDeatail:[],'BatchDetail'=>(!empty($coachbatchDays) ? $coachbatchDays : $empty),'OtherDetail'=>!empty($OtherDeatail)?$OtherDeatail:'','sportList'=>(!empty($sportList) ? $sportList : $empty),'playerselectedsport'=>(!empty($playerselectedsport) ? $playerselectedsport : $empty),'coachselectedsport'=>(!empty($coachselectedsport) ? $coachselectedsport : $empty),'otherselectedoption'=>(!empty($otherselectedsport) ? $otherselectedsport :''),'careerDetail'=>(!empty($careerDetail) ? $careerDetail : $empty),'otherList'=>(!empty($otherList) ? $otherList : $empty),'profile_idPlayer' => (!empty($profile_idPlayer[0]['pk_id']) ? $profile_idPlayer[0]['pk_id'] : ''),'profile_idCoach' => (!empty($profile_idCoach[0]['pk_id']) ? $profile_idCoach[0]['pk_id'] : ''),'profile_idOther' => (!empty($profile_idOther[0]['pk_id']) ? $profile_idOther[0]['pk_id'] : ''),'city_list'=>$cityList,'career_path' => base_url().'uploads/career/','sport_path' => base_url().'uploads/master/sportimage/');
                  echo json_encode($resultarray);
                    exit();
        }else {
            $resultarray = array('error_code' => '5', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }  
    }

    public function listPlayerSportwise(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $sportid = !empty($this->input->post('sportid')) ? $this->input->post('sportid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "0";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "0";
        $verified = !empty($this->input->post('verified')) ? $this->input->post('verified') : "";
        $available = !empty($this->input->post('available')) ? $this->input->post('available') : "";
        $ageFrom = !empty($this->input->post('ageFrom')) ? $this->input->post('ageFrom') : "";
        $ageTo = !empty($this->input->post('ageTo')) ? $this->input->post('ageTo') : "";
        $feesFrom = !empty($this->input->post('feesFrom')) ? $this->input->post('feesFrom') : "";
        $feesTo = !empty($this->input->post('feesTo')) ? $this->input->post('feesTo') : "";
        $distanceTo = !empty($this->input->post('distance_to')) ? $this->input->post('distance_to') : "";
        $distanceFrom = !empty($this->input->post('distance_from')) ? $this->input->post('distance_from') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
          // $rating='{"one_star":"" ,"two_star":"2","three_star":"","four_star":"4","five_star":""}';
        $rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : "";
        $subscription_plan = !empty($this->input->post('subscription_plan')) ? $this->input->post('subscription_plan') : ""; //Platinum Gold
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
        $gender = !empty($this->input->post('gender')) ? $this->input->post('gender') : "";
        $searching_for_sport_partner = !empty($this->input->post('searching_for_sport_partner')) ? $this->input->post('searching_for_sport_partner') : "";//check setting on(1)/off(2)
        if (!empty($rating)){
            $rate=json_decode($rating);
            $one_star=$rate->one_star;
            $two_star=$rate->two_star;
            $three_star=$rate->three_star;
            $four_star=$rate->four_star;
            $five_star=$rate->five_star;
        }

        if (!empty($uid)) {
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
            $table = "user";
            $orderby = '';
            $this->db->order_by("pk_id", "desc");
            $condition = array('status' => '1', 'pk_id' => $uid);
            $col = array('pk_id');
            $chackuserreg = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (empty($chackuserreg )){
                $resultarray = array('error_code' => '3', 'message' => 'User Not exist');
                      echo json_encode($resultarray);
                exit();                         
            }
            // frined List of UID
            $friendList=array();
            $table = "friends";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','request_status'=>'1');
            $col = array('user_id','uid');
            $this->db->distinct();
            // $this->db->limit($limit, $offset);
            $this->db->group_start();
            $this->db->where('uid',$uid);
            $this->db->or_where('user_id', $uid); 
            $this->db->group_end();
            $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $List2 =array();
            $List1 =array();
            foreach ($List as $key => $value){
                if ($value['uid']==$uid){
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','uid'=>$uid,'user.status'=>1);
                    $this->db->join('user','user.pk_id = friends.user_id');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct(); 
                    $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                    $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                if($value['user_id']==$uid){
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','user_id'=>$uid,'user.status'=>1);
                    $this->db->join('user','user.pk_id = friends.uid');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct(); 
                    $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                    $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                $friendList=array_merge($List1,$List2);
            }

            $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=1 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=1 and r.status=1) as count';
            // $subquery3 =",(SELECT * FROM koodo_buy_subscription as buy WHERE category IN ('Gold','Platinum') ORDER BY FIELD(category,'Platinum','Gold')) as id";
            // $subquery3 =",(SELECT category FROM koodo_buy_subscription u  WHERE u.user_id=koodo_user.pk_id AND u.list_status = 1 ORDER BY list_status DESC) as plan";
            // $subquery3 =',(SELECT pk_id FROM `koodo_profie_player_sport` as PS WHERE PS.user_id=koodo_user.pk_id and PS.type=1 and PS.status!=1  ) as order';
            
            $table = "user";
            $col = "user.pk_id,user.name,COALESCE(age,'') as age,COALESCE(koodo_user.address,'') as address,PT.usertype,mob,img,email,playing_time,COALESCE(PT.searching_for_sport_partner,'') as searching_for_sport_partner_data ,COALESCE(koodo_user.playing_time,'') as playing_time,privileges_notifications.searching_for_sport_partner as searching_for_sport_partner_status,verifyEmail,doc_verify,category as plan,gender".$subquery2.$subquery;
            //GROUP_CONCAT(koodo_buy_subscription.category SEPARATOR ',') as category
            $this->db->join('privileges_notifications','user.pk_id = privileges_notifications.fk_uid');
            // $this->db->join('privileges_notifications','user.pk_id = privileges_notifications.fk_uid');
            $this->db->where('privileges_notifications.display_profile',1);
            $this->db->distinct();
            $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
            $this->db->where('PT.usertype',1);
            // $this->db->limit($limit, $offset);
            $this->db->join('profie_player_sport','user.pk_id =profie_player_sport.user_id'); 
            // $this->db->where('buy_subscription.list_status !=',0);
            $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.list_status = 1 AND buy_subscription.listtype = "Players"','LEFT'); 
            // $this->db->where('buy_subscription.status',1);
            $this->db->where('profie_player_sport.type',1);
            $this->db->where('profie_player_sport.status',2);
            $condition = array('user.status' => '1');  
                     
            // $this->db->order_by('FIELD ( koodo_buy_subscription.list_status,"2","1") DESC');
            // $this->db->order_by('FIELD ( koodo_buy_subscription.category,"Gold","Platinum") DESC');
            $this->db->order_by("koodo_buy_subscription.category","DESC");
            $this->db->order_by("PT.list_at_top","ASC");
            $this->db->order_by("user.online_date","DESC");
           

            if (!empty($search)){
                $this->db->where("user.name LIKE '%$search%'"); 
            }
            if (!empty($available)){
                // $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                $condition['privileges_notifications.available']=$available;
            }                
            if(!empty($ageFrom)){
                $condition['user.age>=']=$ageFrom;
            }  
            if(!empty($ageTo)){
                $condition['user.age<=']=$ageTo;
            }   
            if(!empty($verified)){
                    $condition['user.verify_status']=$verified;
            }  
            if(!empty($one_star)){
                $this->db->having('average',$one_star);
            } 
            if(!empty($two_star)){
                $this->db->or_having('average',$two_star);
            }
            if(!empty($three_star)){
                $this->db->or_having('average',$three_star);
            }
            if(!empty($four_star)){
                $this->db->or_having('average',$four_star);
            }
            if(!empty($five_star)){
                $this->db->or_having('average',$five_star);
            }
            if(!empty($gender)){
                $this->db->where('user.gender',$gender);
                // $condition['user.gender']=$gender;
            } 
            if (!empty($sportid)) {
                $this->db->where('profie_player_sport.sportname',$sportid);
                // $this->db->where('buy_subscription.fk_sport',$sportid);
            } 
            if (!empty($city_id)) {
                $this->db->where('user.city',$city_id);
            }
            if (!empty($subscription_plan)) {
                $this->db->where('koodo_buy_subscription.category',$subscription_plan);
            } 
            if (!empty($searching_for_sport_partner)){
                $this->db->where('privileges_notifications.searching_for_sport_partner',$searching_for_sport_partner);
            }                                                
            // $this ->db->order_by("FIELD(koodo_user.pk_id,$uid) DESC");
            $playerList = $this->Md_database->getData($table, $col, $condition, '', '');
         // print_r($playerList);
         // die();
            // print_r($this->db->last_query());
            // die();

            $new_array= array();
            foreach ($playerList as $key => $value) {
                $id = $value['pk_id'];
                $type= $value['usertype'];

                if ($value['doc_verify'] =='1' && ($value['email'] == $value['verifyEmail'])){
                    $value['verify_tick'] = '1';//yes
                }else{
                    $value['verify_tick'] = '2';//No
                }

                $table = "profie_player_sport";
                $orderby = 'profie_player_sport.pk_id ASC';
                $condition = array('user_id' => $id, 'profie_player_sport.type'=>'1' ,'profie_player_sport.status'=>'2','profie_player_sport.primary_id<>'=>'2');
                $this->db->join('sport','sport.pk_id = profie_player_sport.sportname');
                $col = array('sport.sportname','skill','profie_player_sport.pk_id','primary_id');
                $sportList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['sportList'] =$sportList;
                // print_r($sportList);
                // die();

                $table = "profie_player_sport";
                $orderby = 'profie_player_sport.pk_id asc';
                $condition = array('user_id' => $id, 'profie_player_sport.type'=>'1' ,'profie_player_sport.status'=>'2');
                if (!empty($sportid)) {
                    $this->db->where('sportname',$sportid);
                }
                $col = array('skill');
                $skillSportwise = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['skill'] =!empty($skillSportwise[0]['skill'])?$skillSportwise[0]['skill']:'';

                $table = "user";
                $orderby = 'user.pk_id asc';
                $condition = array('user.pk_id' => $id);
                $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id and buy_subscription.status = 1 AND buy_subscription.listtype = "Players"','LEFT');
                $this->db->where('buy_subscription.status','1');
                if (!empty($sportid)) {
                    $this->db->where('fk_sport',$sportid);
                }
                $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category');
                $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['online_status']=$latlong[0]['online_status'];
                $value['doc_verify']=$latlong[0]['doc_verify'];
                $value['category']=$latlong[0]['category'];


                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $uid);
                $col = array('pk_id','latitude','longitude');
                $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');
              
                $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';

                //Calculate distance from latitude and longitude
                $theta = $longitudeFrom - $longitudeTo;
                $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $distance = ($miles * 1.609344);
                $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';

                $table = "friends_favourite";
                $orderby = 'pk_id DESC';
                $condition = array('user_id' => $id,'uid'=>$uid);
                $col = array('favourite_status');
                $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';

                $table = "privileges_notifications";
                $orderby = 'pk_id DESC';
                $condition = array('fk_uid' => $id);
                $col = array('display_profile','available','notifications','chat_notification','location');
                $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';

                $table = "friends";
                $orderby = 'pk_id DESC';
                $condition = array('user_id' => $id,'uid'=>$uid);
                $col = array('request_status');
                $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';

                $table = "user_profile_detail";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','user_id' => $id,'usertype'=>'1');
                $col = array('pk_id,user_id,usertype,visting_fees,skill');
                $player = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['visting_fees'] =!empty($player[0]['visting_fees'])?$player[0]['visting_fees']:'';

                //Mutual Friends
                $friendfriendList=array();
                $table = "friends";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','request_status'=>'1');
                $col = array('user_id','uid');
                $this->db->distinct();
                // $this->db->limit($limit, $offset);
                $this->db->group_start();
                $this->db->where('uid',$id);
                $this->db->or_where('user_id', $id); 
                $this->db->group_end();
                $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $List2 =array();
                $List1 =array();
                foreach ($List as $key => $val) {
                    if ($val['uid']==$id){
                        $table = "friends";
                        $orderby = 'friends.pk_id asc';
                        $condition = array('request_status' => '1','uid'=>$id,'user.status'=>1);
                        $this->db->join('user','user.pk_id = friends.user_id');
                        $this->db->join('city','city.pk_id = user.city');
                        $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                        $this->db->distinct(); 
                        $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                        $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    }
                    if($val['user_id']==$id){
                        $table = "friends";
                        $orderby = 'friends.pk_id asc';
                        $condition = array('request_status' => '1','user_id'=>$id,'user.status'=>1);
                        $this->db->join('user','user.pk_id = friends.uid');
                        $this->db->join('city','city.pk_id = user.city');
                        $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                        $this->db->distinct(); 
                        $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                        $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    }
                    $friendfriendList=array_merge($List1,$List2);
                }
                $mutual_friends=array();
                $array3=array();
                foreach ($friendList as $v) {
                    if (in_array($v, $friendfriendList)) {
                        $array3[] = $v;
                    }
                    $mutual_friends= $array3;
                }
                $value['mutual_friends_count']=count($mutual_friends);
                $value['mutual_friends'] =$mutual_friends ;

                //User Type
                $id = $value['pk_id'];
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
                // if(!empty($verified)){
                //     print_r("sdf");
                //     die();
                //     $verifiedfilter = array();
                //     $array = array();
                //     // if ($value['verify_tick'] ==$verified) {
                //          foreach ($value as $key => $val) {    
                //         // foreach ($final_array as $key => $value) {    
                //             $distance=$value['distance'];
                //             if($val['verify_tick'] ==$verified){
                //                  // die();
                //                 array_push($verifiedfilter,$val);
                              
                //             }else{
                //                 array_push($array,$value);  
                //             }
                //         }                       
                // }


                $new_array[] = $value;
                        // 
            }
            //Check user is pro player or not
            // $status_array=array();
            // foreach ($new_array as $key => $value){
            //     $id=$value['pk_id'];
            //     $table = "profie_player_sport";
            //     $orderby = 'pk_id DESC';
            //     $condition = array('user_id' =>$id,'type'=>'1');
            //     $col = array('primary_id','status');
            //     $status = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            //     $final_status=2;
            //     foreach ($status as $key => $val) {                    
            //         if($val['status']==1 || $final_status == 1){
            //             $final_status =1;
            //         }
            //     }
            //     $value['final_status'] =  $final_status;
            //     $status_array[] = $value;
            // }
            // $final_array=array();
            // $final_array2=array();
            // $array=array();

            // foreach ($status_array as $key => $value) {
            //     if($value['final_status']==1){ 
            //         array_push($array,$value);
                        
            //     }else{
            //         array_push($final_array,$value);
            //     }
            // }

            //filter Distance
            $array =array();
            $final_array2 =array();
            if (!empty($distanceFrom) && !empty($distanceTo)) {
                foreach ($new_array as $key => $value) {    
                // foreach ($final_array as $key => $value) {    
                    $distance=$value['distance'];
                    if($distance >=$distanceFrom  && $distance <=$distanceTo){
                        array_push($final_array2,$value);
                      
                    }else{
                        array_push($array,$value);  
                    }
                }
            }

            $table = "advertisement";
            $orderby = 'pk_id DESC';
            $condition = array('status' => '1','place' =>'5');
            $col = array('advimg');
            $adv = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $advimg=!empty($adv[0]['advimg'])? $adv[0]['advimg']:'';

            $empty= array();           
             // array_merge($final_array2,$final_array2);        
            $resultarray = array('error_code' => '1', 'message' => 'playerList ','playerList' =>  !empty($distanceFrom)?array_slice($final_array2,$offset,$limit):array_slice($new_array,$offset,$limit),'img_path' => base_url().'uploads/users/','advimg' =>  !empty($advimg)?$advimg:'','adv_path' => base_url().'uploads/master/advimg/');
            echo json_encode($resultarray);
            exit();                                                    
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }

    public function listProPlayerSportwise(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $sportid = !empty($this->input->post('sportid')) ? $this->input->post('sportid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "0";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "0";
        $verified = !empty($this->input->post('verified')) ? $this->input->post('verified') : "";
        $available = !empty($this->input->post('available')) ? $this->input->post('available') : "";
        $ageFrom = !empty($this->input->post('ageFrom')) ? $this->input->post('ageFrom') : "";
        $ageTo = !empty($this->input->post('ageTo')) ? $this->input->post('ageTo') : "";
        $feesFrom = !empty($this->input->post('feesFrom')) ? $this->input->post('feesFrom') : "";
        $feesTo = !empty($this->input->post('feesTo')) ? $this->input->post('feesTo') : "";
        $gender = !empty($this->input->post('gender')) ? $this->input->post('gender') : "";
            // $rating='{"one_star":"" ,"two_star":"","three_star":"3","four_star":"4","five_star":""}';
        $rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : "";
        $distanceFrom = !empty($this->input->post('distance_from')) ? $this->input->post('distance_from') : "";
        $distanceTo = !empty($this->input->post('distance_to')) ? $this->input->post('distance_to') : "";
        $subscription_plan = !empty($this->input->post('subscription_plan')) ? $this->input->post('subscription_plan') : ""; //Platinum Gold
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
        $searching_for_sport_partner = !empty($this->input->post('searching_for_sport_partner')) ? $this->input->post('searching_for_sport_partner') : "";//ON-1,off-2

        if (!empty($rating)){
            $rate=json_decode($rating);
            $one_star=$rate->one_star;
            $two_star=$rate->two_star;
            $three_star=$rate->three_star;
            $four_star=$rate->four_star;
            $five_star=$rate->five_star;
        }
        if (!empty($uid)) {
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

            //Friend List of UID
            $friendList=array();
            $table = "friends";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','request_status'=>'1');
            $col = array('user_id','uid');
            $this->db->distinct();
            // $this->db->limit($limit, $offset);
            $this->db->group_start();
            $this->db->where('uid',$uid);
            $this->db->or_where('user_id', $uid); 
            $this->db->group_end();
            $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $List2 =array();
            $List1 =array();
            foreach ($List as $key => $value) {
                if ($value['uid']==$uid){
                    $table = "friends";
                    $orderby = '';
                    // $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','uid'=>$uid,'user.status'=>1);
                    $this->db->join('user','user.pk_id = friends.user_id');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct(); 
                    $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                    $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                if($value['user_id']==$uid){
                    $table = "friends";
                    $orderby = '';
                    // $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','user_id'=>$uid,'user.status'=>1);
                    $this->db->join('user','user.pk_id = friends.uid');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct(); 
                    $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                    $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                $friendList=array_merge($List1,$List2);
            }

            $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=1 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=1 and r.status=1) as count';
            $table = "user";
            
             if (empty($sportid)) {
                $fees_hr =',(SELECT  COALESCE(fees_hr,0) as fees FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id and r.primary_id=1 and r.type=1 and status = 1) as fees';
                $skill =',(SELECT  COALESCE(skill," ") as skill FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id and r.type=1 and status = 1 limit 1) as skill';
            }else{
                $fees_hr =',(SELECT  COALESCE(fees_hr,0) as fees FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id and r.sportname='.$sportid.' and r.type=1 and status = 1) as fees';
                $skill =',(SELECT  COALESCE(skill,"") as fees FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id and r.sportname='.$sportid.' and r.type=1 and status = 1) as skill';
            }

            $col = "user.pk_id,user.name,COALESCE(age,'') as age,user.address,PT.usertype,mob,img,email,COALESCE(playing_time,'') as playing_time,user.verifyEmail,doc_verify,category as plan,gender".$subquery2.$subquery.$fees_hr.$skill ;
            $this->db->join('privileges_notifications','user.pk_id = privileges_notifications.fk_uid');
            $this->db->join('profie_player_sport','user.pk_id =profie_player_sport.user_id'); 
            $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
            // $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.list_status = 1 AND buy_subscription.listtype = "Pro-players"','LEFT');
            $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 ','LEFT');

            $this->db->where('privileges_notifications.display_profile',1);
            $condition = array('user.status' => '1');
            $this->db->where('PT.usertype',1);
            $this->db->where('profie_player_sport.type',1);
            $this->db->where('profie_player_sport.status',1);
            // $this->db->order_by('FIELD ( koodo_buy_subscription.category,"Gold","Platinum") DESC');
            $this->db->order_by("koodo_buy_subscription.category","DESC");
            $this->db->order_by("PT.list_at_top","ASC");
            $this->db->order_by("user.online_date","DESC");
            // $this ->db->order_by("FIELD(koodo_user.pk_id,$uid) DESC");
            $this->db->distinct();

            if (!empty($search)) {
                $this->db->where("user.name LIKE '%$search%'"); 
            }
            if (!empty($available)) {
                $this->db->join('privileges_notifications as PN','PN.fk_uid = user.pk_id','RIGHT');
                $condition['PN.available']=$available;
            }
            if(!empty($ageFrom)){
                $condition['user.age>=']=$ageFrom;
            }   
            if(!empty($ageTo)){
                $condition['user.age<=']=$ageTo;
            }  
            if(!empty($verified)){
                $condition['user.verify_status']=$verified;
            }  
            if(!empty($one_star)){
                $this->db->having('average',$one_star);
            } 
            if(!empty($two_star)){
                $this->db->or_having('average',$two_star);
            }
            if(!empty($three_star)){
                $this->db->or_having('average',$three_star);
            }
            if(!empty($four_star)){
                $this->db->or_having('average',$four_star);
            }
            if(!empty($five_star)){
                $this->db->or_having('average',$five_star);
            } 
            if(!empty($feesFrom)){
                $this->db->having('fees>=',$feesFrom);
            }
            if(!empty($feesTo)){
                $this->db->having('fees<=',$feesTo);
            }
            if(!empty($gender)){
                $this->db->where('user.gender',$gender);
            }
            if (!empty($sportid)) {
                $this->db->where('profie_player_sport.sportname',$sportid);  
            }
            if (!empty($city_id)) {
                $this->db->where('user.city',$city_id);
            }
            if (!empty($searching_for_sport_partner)) {
                $this->db->where('PT.searching_for_sport_partner',$searching_for_sport_partner);
            } 
            if (!empty($subscription_plan)) {
                $this->db->where('koodo_buy_subscription.category',$subscription_plan);
            }
            $playerList = $this->Md_database->getData($table, $col, $condition, $orderby, '');

             // print_r($playerList);
             // die();
            $new_array=array();
            if (!empty($playerList)) {
                foreach ($playerList as $key => $value) {
                    $id = $value['pk_id'];
                    $type= $value['usertype'];

                    if ($value['doc_verify'] =='1' && ($value['email'] == $value['verifyEmail'])) {
                        $value['verify_tick'] = '1';//yes
                    }else{
                         $value['verify_tick'] = '2';//No
                    }

                    $table = "profie_player_sport";
                    $orderby = 'profie_player_sport.pk_id asc';
                    $condition = array('user_id' => $id, 'profie_player_sport.type'=>'1','profie_player_sport.status'=>'1');
                    $this->db->join('sport','sport.pk_id = profie_player_sport.sportname');
                    $col = array('sport.sportname','skill');
                    $sportList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['sportList'] =$sportList;
                    // $value['skill'] =!empty($sportList[0]['skill'])?$sportList[0]['skill']:'';

                    $table = "user";
                    $orderby = 'user.pk_id asc';
                    $condition = array('user.pk_id' => $id);
                    $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category');
                    $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Pro-players"','LEFT');
                    $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['online_status']=$latlong[0]['online_status'];
                    $value['doc_verify']=$latlong[0]['doc_verify'];
                    $value['category']=$latlong[0]['category'];
                     
                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id' => $uid);
                    $col = array('pk_id','latitude','longitude');
                    $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                     $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                    $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                    $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                    $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';

                    //Calculate distance from latitude and longitude
                    $theta = $longitudeFrom - $longitudeTo;
                    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    $distance = ($miles * 1.609344);
                    $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';

                    $table = "friends_favourite";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('favourite_status');
                    $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';


                    $table = "privileges_notifications";
                    $orderby = 'pk_id DESC';
                    $condition = array('fk_uid' => $id);
                    $col = array('display_profile','available','notifications','chat_notification','location');
                    $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                    $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                    $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';
                    $table = "friends";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('request_status');
                    $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';

                    $table = "user_profile_detail";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','user_id' => $id,'usertype'=>'1');
                    $col = array('pk_id,user_id,usertype,visting_fees,skill');
                    $player = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['visting_fees'] =!empty($player[0]['visting_fees'])?$player[0]['visting_fees']:'';
                   

                    //Mutual Friends
                    $friendfriendList=array();
                    $table = "friends";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','request_status'=>'1');
                    $col = array('user_id','uid');
                    $this->db->distinct();
                    // $this->db->limit($limit, $offset);
                    $this->db->group_start();
                    $this->db->where('uid',$id);
                    $this->db->or_where('user_id', $id); 
                    $this->db->group_end();
                    $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $List2 =array();
                    $List1 =array();
                    foreach ($List as $key => $val) {
                        if ($val['uid']==$id){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','uid'=>$id,'user.status'=>1);
                            $this->db->join('user','user.pk_id = friends.user_id');
                            $this->db->join('city','city.pk_id = user.city');
                            $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                            $this->db->distinct(); 
                            $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                            $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        if($val['user_id']==$id){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','user_id'=>$id,'user.status'=>1);
                            $this->db->join('user','user.pk_id = friends.uid');
                            $this->db->join('city','city.pk_id = user.city');
                            $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                            $this->db->distinct(); 
                            $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                            $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        $friendfriendList=array_merge($List1,$List2);
                    }
                    $mutual_friends=array();
                    $array3=array();
                    foreach ($friendList as $v) {
                        if (in_array($v, $friendfriendList)) {
                            $array3[] = $v;
                        }
                        $mutual_friends= $array3;
                    }
                    $value['mutual_friends_count']=count($mutual_friends);
                    $value['mutual_friends'] =$mutual_friends ;


 
                   //User Type
                    $id = $value['pk_id'];
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
            
            //filter distance
            $final_array=array();
            $array=array();
            if (!empty($distanceFrom) && !empty($distanceTo)) {
                foreach ($new_array as $key => $value) {
                    $distance=$value['distance'];

                    if($distance >=$distanceFrom  && $distance <=$distanceTo){
                        array_push($final_array,$value);
                    }else{
                        array_push($array,$value);  
                    }
                }
            } 

            $table = "advertisement";
            $orderby = 'pk_id DESC';
            $condition = array('status' => '1','place' =>'5');
            $col = array('advimg');
            $adv = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $advimg=!empty($adv[0]['advimg'])? $adv[0]['advimg']:'';              
            
            $empty =array();
            $resultarray = array('error_code' => '1', 'message' => 'proplayerList ','proplayerList' => !empty($distanceFrom && $distanceTo )?array_slice($final_array,$offset,$limit):array_slice($new_array,$offset,$limit),'advimg' => !empty($advimg)?$advimg:'','img_path' => base_url().'uploads/users/','adv_path' => base_url().'uploads/master/advimg/');
            echo json_encode($resultarray);
            exit();              
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }

    public function proPlayerSport(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
          if (!empty($uid)) {
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
              $table = "profie_player_sport";
              $orderby = 'profie_player_sport.pk_id asc';
              $condition = array('profie_player_sport.user_id' => $uid,'profie_player_sport.type'=>'1');
              $col = array('sport.sportname','profie_player_sport.status','sport.pk_id','profie_player_sport.fees_hr');
              $this->db->join('sport', 'sport.pk_id = profie_player_sport.sportname');
              $pro_playerList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                 // print_r($pro_playerList);
                 // die();
              $resultarray = array('error_code' => '1', 'pro_playerList' =>$pro_playerList, 'message' => 'pro_playerList');
              echo json_encode($resultarray);
              exit();                       
          }else {
              $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
              echo json_encode($resultarray);
              exit();                       
          } 
    }
    public function playerSportStatusUpdate(){
        // $sports='[{"sportstatus":"1","pk_id":"13"},{"sportstatus":"1","pk_id":"11"},{"sportstatus":"1","pk_id":"10"}]';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        // $fees_hr = !empty($this->input->post('fees_hr')) ? $this->input->post('fees_hr') : '';
        $sports = !empty($this->input->post('sports')) ? $this->input->post('sports') : '';
        $sportstatus = json_decode($sports);

        if (!empty($uid)) {
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

            foreach ($sportstatus as $key) {
                // print_r($key);
                $status= $key->sportstatus;
                $sport= $key->pk_id;
                $fees_hr= $key->fees_hr;
            
                $table = "profie_player_sport";
                $sport_data = array(
                    'fees_hr'=>$fees_hr,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updatedBy' => $uid,
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR'],
                    'status' => $status,
                );
                $condition = array("user_id" => $uid,'sportname'=>$sport);
                $updatestatus = $this->Md_database->updateData($table, $sport_data, $condition);
             }
               $resultarray = array('error_code' => '1', 'message' => 'Update status');
                    echo json_encode($resultarray);
                    exit();                  
            }else {
              $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                       
            } 
    }

    public function rating(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $profile_uid = !empty($this->input->post('profile_uid')) ? $this->input->post('profile_uid') : '';
        $academy_id = !empty($this->input->post('academy_id')) ? $this->input->post('academy_id') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
        $rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : '';
        $review = !empty($this->input->post('review')) ? $this->input->post('review') : '';

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
                if(empty($rating) || empty($review) || (empty($profile_uid)&& empty($academy_id)  ) || empty($type) || empty($review)) {
                    $resultarray = array('error_code' => '3', 'message' => '(profile_uid and academy_id)  or type or rating or review is empty');
                    echo json_encode($resultarray);
                    exit();
                }else{

                    $table = "user_review";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1');
                    $col = array('pk_id','fk_for','fk_given_by');
                    $this->db->where('fk_given_by',$uid);
                    $this->db->where('fk_for',$profile_uid);
                    $checkAlreadyRating = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    // print_r($checkAlreadyRating);
                    // die();
                    if (!empty($checkAlreadyRating)) {
                        $resultarray = array('error_code' => '3', 'message' => 'Already given rate');
                        echo json_encode($resultarray);
                        exit();                      
                    }else{
                        $insert_data = array(
                            'rate'=> $rating,                                             
                            'type'=> $type,                                             
                            'feedback'=> $review,                                             
                            'fk_given_by'=> $uid,                                             
                            'fk_for'=> !empty($profile_uid)?$profile_uid:NULL,                                        
                            'fk_academy'=> !empty($academy_id)?$academy_id:NULL,                                            
                            'status' => 1,   
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']   
                        );
                        $resultarray = $this->Md_database->insertData('koodo_user_review', $insert_data);
                        $resultarray = array('error_code' => '1', 'message' => 'rating done');
                        echo json_encode($resultarray);
                        exit(); 
                    }

                }
            }else {
                $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                echo json_encode($resultarray);
                exit();                       
            } 
    }

    public function updateMobileEmailStatus(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $mobileStatus = !empty($this->input->post('mobileStatus')) ? $this->input->post('mobileStatus') : '';
        $emailStatus = !empty($this->input->post('emailStatus')) ? $this->input->post('emailStatus') : '';

        if (!empty($uid) && !empty($mobileStatus) && !empty($emailStatus)){
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
                $table="user";
                $inserted_data = array(                          
                    'emailStatus'=> $emailStatus,                        
                    'mobStatus'=> $mobileStatus,
                    'updatedBy' => $uid, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
                );    
                $condition = array("pk_id" => $uid);
                $ret = $this->Md_database->updateData($table,$inserted_data, $condition);
            
                $resultarray = array('error_code' => '1', 'uid'=>$uid ,'message' => 'Status update successfully');
                echo json_encode($resultarray);
                exit();  
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid or mobileStatus or emailStatus is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function lookingforCoach(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';

        if (!empty($uid) ){
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
                $table="buy_subscription";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1');
                $col = array('pk_id','user_id');
                $this->db->distinct();
                $buyPlanUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
              
                foreach ($buyPlanUser as $key => $value) {
                    $id = $value['user_id'];
                    $table="profile_type";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','usertype' =>'2');
                    $this->db->where('user_id',$id);
                    $col = array('pk_id','user_id');
                    $this->db->distinct();
                    $checkCoach = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    
                    // print_r($id);
                    // die();
                    if (!empty($checkCoach)){
                        $table = "privileges_notifications";
                        $select = "notifications,chat_notification";
                        $this->db->where('fk_uid',$checkCoach[0]['user_id']);
                        $this->db->order_by('pk_id','ASC');
                        $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                        $notification=($chechprivilege[0]['notifications']);
                        if ($notification=='1') {
                            $table = "user";
                            $select = "token,user.pk_id,name";
                            $this->db->where('pk_id',$checkCoach[0]['user_id']);
                            $this->db->order_by('user.pk_id','ASC');
                            $this->db->distinct();
                            $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                            $target=$order_token[0]['token'];
                            
                            $table = "user";
                            $select = "user.pk_id,name";
                            $this->db->where('pk_id',$uid);
                            $this->db->order_by('user.pk_id','ASC');
                            $this->db->distinct();
                            $username = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                            $name=$username[0]['name'];
                            $subject ="Looking for Coach";
                            $message = $name. " want to touch with you.";
                            
                            if(!empty($message)){
                               
                                $resultarray = array('message' => $message,'from_uid'=>$uid,'to_user_id'=>$checkCoach[0]['user_id'] ,'redirect_type' =>'looking_for_coach','subject'=>$subject);
                                    
                                $this->Md_database->sendPushNotification($resultarray,$target);

                                //store into database 
                                $table = "custom_notification";
                                $insert_data = array(
                                    'from_uid'=>$uid,
                                    'to_user_id'=>$checkCoach[0]['user_id'],
                                    'redirect_type' => 'looking_for_coach',
                                    'subject' => $subject,
                                    'message'=>$message,
                                    'status' => '1',
                                    'created_by ' =>$uid,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                                );
                                $result = $this->Md_database->insertData($table, $insert_data);
                            }                           
                        }                                   
                    }
                }
                
                $resultarray = array('error_code' => '1','message' => 'Send Notification  successfully');
                echo json_encode($resultarray);
                exit();  
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 

    }
}

