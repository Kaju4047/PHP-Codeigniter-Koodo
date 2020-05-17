<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_registration extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
   
    public function registration(){
        $emailid = !empty($this->input->post('emailid')) ? $this->input->post('emailid') : '';
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : '';
        $fullname = !empty($this->input->post('fullname')) ? $this->input->post('fullname') : '';
        $password = !empty($this->input->post('password')) ? $this->input->post('password') : '';       
        $registered_email_chk = $this->Md_database->getData('user', '*', array('email' => $emailid,'status!=' => '3'));
        $registered_chk = $this->Md_database->getData('user', '*', array('mob' => $mobileno,'status!=' => '3'));

        if((!empty($emailid))&&(!empty($mobileno))&&(!empty($fullname))&&(!empty($password)) ){
            if (!empty($registered_email_chk)) {
                $resultarray = array('error_code' => '5', 'message' => 'This Email Id  is already registered');
                echo json_encode($resultarray);
                exit();                
            }
            if (isset($registered_chk) && count($registered_chk) > 0) {
                $resultarray = array('error_code' => '4', 'message' => 'This mobile number is already registered');
                echo json_encode($resultarray);
                exit();                
            }else{
                $otp = rand(1000, 9999);
                $message = 'Hi, ' . $otp.'  is your one time password (OTP) is access in Koodo. Thank you.';
                $this->Md_database->sendSMS($message, $mobileno);
                $resultarray = array('error_code' => '1', 'otp' => $otp, 'message' => 'otp send on register mobile no.');
                echo json_encode($resultarray);
                exit();              
            }
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'All fields are required');
            echo json_encode($resultarray);
            exit();
        }
    }

    public function OTPVerify() {
        $this->load->library('encrypt');
        $emailid = !empty($this->input->post('emailid')) ? $this->input->post('emailid') : '';
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : '';
        $fullname = !empty($this->input->post('fullname')) ? $this->input->post('fullname') : '';
        $password = !empty($this->input->post('password')) ? $this->input->post('password') : '';
        $otp_enter = !empty($this->input->post('otp_enter')) ? $this->input->post('otp_enter') : '';
        $otp_send = !empty($this->input->post('otp_send')) ? $this->input->post('otp_send') : '';
        $token = !empty($this->input->post('token')) ? $this->input->post('token') : 'sd';

        if (empty($emailid) || empty($mobileno) || empty($fullname) || empty($password))  {
            $resultarray = array('error_code' => '2', 'message' => 'email id , mobile no or fullname or password is empty');
            echo json_encode($resultarray);
            exit();
        }
        if (empty($otp_enter) || empty($otp_send)) {
            $resultarray = array('error_code' => '3', 'message' => 'otp filed is empty.');
            echo json_encode($resultarray);
            exit();
        }
        if ($otp_enter !== $otp_send){
            $resultarray = array('error_code' => '4', 'message' => 'OTP is missmatch.');
            echo json_encode($resultarray);
            exit();
        }else {
             
            $inserted_data = array(
                'email'=> $emailid,
                'mob'=> $mobileno,
                'name'=>$fullname,
                'password' => base64_encode($password),                           
                'status' => 1,
                'regdate'=>date('Y-m-d'),
                'token'=>$token,
                'createdDate' => date('Y-m-d H:i:s'),                
                'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
            );
            $ret = $this->Md_database->insertData('user', $inserted_data);
            $uid = $this->db->insert_id();
            
            $encode_email = base64_encode($this->input->post('emailid'));
               $encrypted_email = str_replace('=', '', $encode_email);

            //Send link on email to verifyemail
            // $this->email->from(SITE_MAIL, SITE_TITLE); //sender's email
            // $address = $emailid;   //receiver's email
            // $subject="Email Verification";    //subject
            // $message= /*-----------email body starts-----------*/
            //     'Dear User,
            //     Please click on below URL or paste into your browser to verify your Email
            //       ' . base_url() .'register/verify_email/'.$uid.'/'.$encrypted_email ;
                                             
            //     /*-----------email body ends-----------*/             
            // $this->email->to($emailid);
            // $this->email->subject($subject);
            // $this->email->message($message);
            // $this->email->send();

            $recipeinets = strtolower($emailid);
                        $from = array(
                            "email" => SITE_MAIL,
                            "name" => SITE_TITLE
                        );
                        $reserved_words = array(
                            // "||USER_NAME||" => ucwords($fullname),
                            "||SITE_TITLE||" => SITE_TITLE,
                            "||EMAIL_ID||" => strtolower($emailid),
                            "||LINK||" =>  base_url() .'register/verify_email/'.$uid.'/'.$encrypted_email,
                            "||YEAR||" => date('Y'),
                        );
                        $email_data = $this->Md_database->getEmailInfo('email_veification', $reserved_words);
                        $subject = SITE_TITLE . '-' . 'Email Verification';
                        $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']); 
                        

             // entery in privileges_notifications
            $table = "privileges_notifications";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','fk_uid' => $uid); 
            $col = array('pk_id');
            $check_privileges = $this->Md_database->getData($table, $col, $condition, $orderby, '');
           
            if (empty($check_privileges)){
                $inserted_data = array(
                    'fk_uid'=> $uid,                        
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),                
                    'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
                );
                $privilages = $this->Md_database->insertData('privileges_notifications',$inserted_data);
            }
           //Send Email for Registration
            $recipeinets = strtolower($emailid);
            $from = array(
                "email" => SITE_MAIL,
                "name" => SITE_TITLE
            );
            $reserved_words = array(
                "||USER_NAME||" => ucwords($fullname),
                "||SITE_TITLE||" => SITE_TITLE,
                "||EMAIL_ID||" => strtolower($emailid),
                "||PASSWORD||" => $password,
                "||YEAR||" => date('Y'),
                "||MOBILE||" => $mobileno
            );
            $email_data = $this->Md_database->getEmailInfo('registration', $reserved_words);
            $subject = SITE_TITLE . '-' . $email_data['subject'];
            $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']);   

            //check invitaion status
            $table = "invitation";
            $orderby = 'pk_id desc';
            $condition = array('mobile' => $mobileno); 
            $this->db->where('from_date<=',date('Y-m-d H:i:s'));
            $this->db->where('to_date>=',date('Y-m-d H:i:s'));
            $col = array('pk_id');
            $activeInvitation = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (empty($activeInvitation)) {                
                $table = "invitation";
                $orderby = 'pk_id desc';
                $condition = array('mobile' => $mobileno); 
                $col = array('pk_id');
                $check_invitation = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                if (!empty($check_invitation)){
                    $update_data = array(
                        'reg_status' => 'registered', 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']   );         
                    $condition = array('pk_id'=> !empty($check_invitation[0]['pk_id'])?$check_invitation[0]['pk_id']:'');
                    $this->Md_database->updateData('invitation', $update_data, $condition);
                }
            }elseif(!empty($activeInvitation)){
                    $update_data = array(
                        'reg_status' => 'registered', 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
                    );
                    $condition = array('pk_id'=> !empty($activeInvitation[0]['pk_id'])?$activeInvitation[0]['pk_id']:'');
                    $this->Md_database->updateData('invitation', $update_data, $condition);
            }
                    
            //For get data
            $table = "profie_player_sport";
            $orderby = 'pk_id asc';
            $condition = array('type' => '3','user_id' => $uid); 
            $col = array('sportname');
            $other = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $other_id= !empty($other[0]['sportname'])? $other[0]['sportname']:'';

            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'mob' => $mobileno, 'email' => $emailid);
            $col = array('pk_id,img');
            $profileImage = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $resultarray = array('error_code' => '1', 'uid' => $uid, 'emailid' => strtolower($emailid), 'mobile' => $mobileno,'name' => $fullname,'profileImg' => $profileImage[0]['img'],'profile_path' => base_url().'uploads/users/', 'message' => 'registration done successfully.','other_id'=>$other_id);
            echo json_encode($resultarray);
            exit();           
        }
    }

    public function login(){   
        $username = !empty($this->input->post('username')) ? $this->input->post('username') : '';
        $password = !empty($this->input->post('password')) ? trim($this->input->post('password'))  : '';
        $token = !empty($this->input->post('token')) ? trim($this->input->post('token'))  : '';
        if (!empty($username) || !empty($password)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'mob' => $username);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
        }
        if (empty($username) || empty($password)) {
            $resultarray = array('error_code' => '2', 'message' => 'username or password is empty.');
            echo json_encode($resultarray);
            exit();
        }else{
            //update token
            $insert_data = array(
                'token' => $token, 
                'online_date' => date('Y-m-d H:i:s'),
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
             );
            $condition = array('mob'=> $username);
            $this->db->where('status','1');

            $this->Md_database->updateData('user', $insert_data, $condition);
             $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'mob' => $username, 'password' => base64_encode($password)); 
            $col = array('name,mob,img,user.pk_id,email');
            $checkCreaditial = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // print_r($checkCreaditial);
            // die();
            if (!empty($checkCreaditial)) {
                    $table = "user";
                    $orderby = 'user.pk_id asc';
                    $condition = array('user.status' => '1', 'mob' => $username, 'password' => base64_encode($password));
                    // $this->db->where('user_profile_detail.status',1);
                    $this->db->where('PT.status',1);
                    $this->db->join('profile_type as PT','user.pk_id = PT.user_id','left'); 
                    // $this->db->join('user_profile_detail','user.pk_id = koodo_user_profile_detail.user_id','left'); 
                    $col = array('name,mob,img,user.pk_id,email,PT.usertype');
                    $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    // print_r($checkUser);
                    // die();
                    if (!empty($checkUser)) {
                        if ((!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype']=='1' )||(!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype']=='1' )||(!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype']=='1' )) {
                            $Player = '1';
                        }else{
                            $Player = '0';
                        }
                        if ((!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype'] =='2')||(!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype'] =='2')||(!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype'] =='2')) {
                            $Coach = '1';
                        }else{
                            $Coach = '0';
                        }
                        if ((!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype'] =='3')||(!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype'] =='3')||(!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype'] =='3')) {
                            $Other = '1';
                        }else{
                            $Other = '0';
                        }           
                    }

                    if (!empty($checkUser)) {
                        $table = "profie_player_sport";
                        $orderby = 'pk_id asc';
                        $condition = array('type' => '3','user_id' => $checkUser[0]['pk_id']); 
                        $col = array('sportname');
                        $other = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        $other_id= !empty($other[0]['sportname'])? $other[0]['sportname']:'';

                        $resultarray = array('error_code' => '1', 'uid' => $checkUser[0]['pk_id'], 'fullname' => $checkUser[0]['name'], 'mailid' => $checkUser[0]['email'], 'mobile' => $checkUser[0]['mob'],'profileImg' => $checkUser[0]['img'], 'Player' => $Player, 'Coach' => $Coach, 'Other' => $Other,'profile_path' => base_url().'uploads/users/', 'message' => 'Login successfully!','other_id'=>$other_id);
                        echo json_encode($resultarray);
                        exit();
                    }else{
                        $resultarray = array('error_code' => '1', 'uid' => $checkCreaditial[0]['pk_id'], 'fullname' => $checkCreaditial[0]['name'], 'mailid' => $checkCreaditial[0]['email'], 'mobile' => $checkCreaditial[0]['mob'],'profileImg' => $checkCreaditial[0]['img'], 'Player' => '0', 'Coach' => '0', 'Other' => '0','profile_path' => base_url().'uploads/users/', 'message' => 'Login successfully!','other_id'=>'');
                        echo json_encode($resultarray);
                        exit();
                        
                    }
            }else{
                $resultarray = array('error_code' => '3', 'message' => 'Enter valid credentials!');
                echo json_encode($resultarray);
                exit();
             }  
            //check validation
        }    
        
    }
    public function ResendOtp(){
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : '';
        if (!empty($mobileno)) {
            $otp = rand(1000, 9999);
            $message = 'Hi, ' . $otp.'  is your one time password (OTP) is access in Koodo. Thank you.';
            $this->Md_database->sendSMS($message, $mobileno);
            $resultarray = array('error_code' => '1', 'otp' => $otp, 'message' => 'otp resend on register mobile no.');
            echo json_encode($resultarray);
            exit();
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'mobile no is empty.');
            echo json_encode($resultarray);
            exit();
        }
    }
    public function forgotpassGetOTP() {
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : '';
        if (!empty($mobileno)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1', 'mob' => $mobileno);
            $col = array('mob',);
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (empty($checkUser)) {
                $resultarray = array('error_code' => '5', 'message' => 'mobile no not in registered.');
                echo json_encode($resultarray);
                exit();
            }
            $otp = rand(1000, 9999);
            $message = 'Hi, ' . $otp.' is your one time password (OTP) is send in user registered mobile. Thank you.';
            $this->Md_database->sendSMS($message, $mobileno);
            $resultarray = array('error_code' => '1', 'otp' => $otp, 'message' => 'otp send on register mobile no.');
            echo json_encode($resultarray);
            exit();
                  
        }else{
            $resultarray = array('error_code' => '2','mobileno'=>$mobileno, 'message' => 'mobile no is empty.');
            echo json_encode($resultarray);
            exit();
        }
    }

    public function forgotPassOTPVerify(){
        $otp_enter = !empty($this->input->post('otp_enter')) ? $this->input->post('otp_enter') : '';
        $otp_send = !empty($this->input->post('otp_send')) ? $this->input->post('otp_send') : '';
      
        $otp = rand(1000, 9999);
        if (empty($otp_enter) || empty($otp_send)) {
            $resultarray = array('error_code' => '2', 'message' => 'enter opt , otp is  empty');
            echo json_encode($resultarray);
            exit();
        }     
        if ($otp_enter != $otp_send) {
            $resultarray = array('error_code' => '4', 'message' => 'OTP is missmatch.');
            echo json_encode($resultarray);
            exit();
        } else{
            $resultarray = array('error_code' => '1', 'message' => 'OTP veify successfully');
            echo json_encode($resultarray);
            exit();
        }                  
    }
      
    public function updatePassword() {
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : '';
        $token = !empty($this->input->post('token')) ? $this->input->post('token') : '';
        $password = !empty($this->input->post('password')) ? strtolower($this->input->post('password')) : '';    
        if (empty($mobileno) || empty($password)) {
            $resultarray = array('error_code' => '2', 'message' => 'password or mobile  is  empty');
            echo json_encode($resultarray);
            exit();
        }else{
            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1','mob' => $mobileno);
            $col = array('user.pk_id,name,img');
            $check = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (empty($check)) {
               $resultarray = array('error_code' => '3','message' => 'Mobile Not register');
                echo json_encode($resultarray);
                exit();
            }
            foreach ($check as $key => $checkUser) {
                $id=$checkUser['pk_id'];
                $table = "profile_type";
                $orderby = 'profile_type.pk_id asc';
                $condition = array('status' => '1','user_id' => $id);
                $col = array('usertype');
                $typeuser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $type[] =$typeuser;
            }
            if ((!empty($type[0][0]['usertype']) && $type[0][0]['usertype']=='1')||(!empty($type[0][1]['usertype']) && $type[0][1]['usertype']=='1')||(!empty($type[0][2]['usertype']) && $type[0][2]['usertype']=='1')) {
                $Player = '1';
            }else{
                $Player = '0';
            }
            if ((!empty($type[0][0]['usertype']) && $type[0][0]['usertype']=='2')||(!empty($type[0][1]['usertype']) && $type[0][1]['usertype']=='2')||(!empty($type[0][2]['usertype']) && $type[0][2]['usertype']=='2')) {
                $Coach = '1';
            }else{
                $Coach = '0';
            }
            if ((!empty($type[0][0]['usertype']) && $type[0][0]['usertype']=='3')||(!empty($type[0][1]['usertype']) && $type[0][1]['usertype']=='3')||(!empty($type[0][2]['usertype']) && $type[0][2]['usertype']=='3')) {
                $Other = '1';
            }else{
                $Other = '0';
            }

            $insert_data = array(
                'mob' => $mobileno,
                'password' =>base64_encode($password),
                'token' =>$token,
                'online_date' => date('Y-m-d H:i:s'),
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
            );
            $this->db->where('status','1');
            $condition = array('mob'=> $mobileno);
            $this->Md_database->updateData('user', $insert_data, $condition);

            $table = "profie_player_sport";
            $orderby = 'pk_id asc';
            $condition = array('type' => '3','user_id' => $check[0]['pk_id']); 
            $col = array('sportname');
            $other = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $other_id= !empty($other[0]['sportname'])? $other[0]['sportname']:'';

            $resultarray = array('error_code' => '1','uid' =>!empty($check[0]['pk_id'])?$check[0]['pk_id']:'' ,'Player' => $Player,'Coach' => $Coach,'Other' => $Other,'name'=>!empty($check[0]['name'])?$check[0]['name']:'','profileImg'=>!empty($check[0]['img'])?$check[0]['img']:'','profile_path' => base_url().'uploads/users/','message' => 'password updated successfully','other_id'=>$other_id,'mobile'=>$mobileno);
            echo json_encode($resultarray);
            exit();
        }          
    }
    public function logout(){    
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : ''; 
        if (!empty($uid)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive.Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $insert_data = array(
                'token' => '',
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
            );
            $condition = array('pk_id'=> $uid);
            $this->Md_database->updateData('user',$insert_data,$condition);
            $resultarray = array('error_code' => '1','message' => 'Logout successfully');
            echo json_encode($resultarray);
            exit();
        }else{
            $resultarray = array('error_code' => '3','message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();
        }     
    }
    public function deleteAccount(){    
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : ''; 
        $mobileno = !empty($this->input->post('mobileno')) ? $this->input->post('mobileno') : ''; 
        if (!empty($uid) && !empty($mobileno)) {
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
            $insert_data = array(
                'token'=>'',
                'status' => '3',
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
            );
            $condition = array();
            $this->db->where('mob',$mobileno);
            $this->Md_database->updateData('user',$insert_data,$condition);
            $resultarray = array('error_code' => '1','message' => 'Delete Account successfully');
            echo json_encode($resultarray);
            exit();
        }else{
            $resultarray = array('error_code' => '3','message' => 'uid or mobileno is empty');
            echo json_encode($resultarray);
            exit();
        }     
    }
    public function googleLogin(){
        $email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
        $token = !empty($this->input->post('token')) ? $this->input->post('token') : '';

        if (!empty($email) ) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'email' => $email);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
        }
        if (empty($email)|| empty($token)) {
            $resultarray = array('error_code' => '2', 'message' => 'Email or token is empty.');
            echo json_encode($resultarray);
            exit();
        }else{
            //update token
            $insert_data = array(
                'token' => $token, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
             );
            $condition = array('email'=> $email);

            $this->Md_database->updateData('user', $insert_data, $condition);

            //check validation
            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'email' => $email);
            $this->db->join('profile_type as PT','user.pk_id = PT.user_id','left'); 
            $col = array('name,mob,img,user.pk_id,email,PT.usertype');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            if (!empty($checkUser)) {
                if ((!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype']=='1' )||(!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype']=='1' )||(!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype']=='1' )) {
                    $Player = '1';
                }else{
                    $Player = '0';
                }
                if ((!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype'] =='2')||(!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype'] =='2')||(!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype'] =='2')) {
                    $Coach = '1';
                }else{
                    $Coach = '0';
                }
                if ((!empty($checkUser[2]['usertype']) && $checkUser[2]['usertype'] =='3')||(!empty($checkUser[1]['usertype']) && $checkUser[1]['usertype'] =='3')||(!empty($checkUser[0]['usertype']) && $checkUser[0]['usertype'] =='3')) {
                    $Other = '1';
                }else{
                    $Other = '0';
                }
            
            }
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '1', 'uid' => $checkUser[0]['pk_id'], 'fullname' => $checkUser[0]['name'], 'mailid' => $checkUser[0]['email'], 'mobile' => $checkUser[0]['mob'],'profileImg' => $checkUser[0]['img'], 'Player' => $Player, 'Coach' => $Coach, 'Other' => $Other,'profile_path' => base_url().'uploads/users/', 'message' => 'Login successful!');
                echo json_encode($resultarray);
                exit();
            }else {
                $resultarray = array('error_code' => '3', 'message' => 'Enter valid credentials!');
                echo json_encode($resultarray);
                exit();
            }
        }
    }
    public function updateOfflineStatus(){
        //Chron Job
        $update_data = array(
            'online_status' =>2, //offline
            'updatedDate' => date('Y-m-d H:i:s'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
        );               
        $condition = array();
        $this->Md_database->updateData('user', $update_data, $condition);   
        $resultarray = array('error_code' => '1', 'message' => 'offline Status updated Successfully');
        echo json_encode($resultarray);
        exit();        
    }

    public function updateLatLong(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : ''; 
        $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : ''; 
        $online_status = !empty($this->input->post('online_status')) ? $this->input->post('online_status') : ''; // 1-online, 2-offline

        if (!empty($uid) ) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'email' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
        }
        if ((empty($latitude) || empty($longitude)) && empty($online_status) ){
            $resultarray = array('error_code' => '2', 'message' => 'latitude or longitude or online status is empty.');
            echo json_encode($resultarray);
            exit();
        }else{
            if (!empty($latitude) && !empty($longitude)){
                $update_data = array(
                    'longitude' => $longitude, 
                    'latitude' => $latitude, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
                );               
                $condition = array('pk_id'=> $uid);
                $this->Md_database->updateData('user', $update_data, $condition);   
            }
            if (!empty($online_status)){
                $update_data = array(
                    'online_status' => $online_status, 
                    'online_date' => date('Y-m-d H:i:s'),
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']           
                );               
                $condition = array('pk_id'=> $uid);
                $this->Md_database->updateData('user', $update_data, $condition);  
                $resultarray = array('error_code' => '1', 'message' => 'Data updated Successfully');
                 echo json_encode($resultarray);
                exit();  
            }
        }    
    }
}





