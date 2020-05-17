<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    class Cn_privileges extends CI_Controller {
        function __construct() {
            parent::__construct();
    }
    function updateSetting(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';    
        $display_profile = !empty($this->input->post('display_profile')) ? $this->input->post('display_profile') : '';
        $display_email = !empty($this->input->post('display_email')) ? $this->input->post('display_email') : '';
        $display_mobile = !empty($this->input->post('display_mobile')) ? $this->input->post('display_mobile') : '';
        $available = !empty($this->input->post('available')) ? $this->input->post('available') : '';
        $notifications = !empty($this->input->post('notifications')) ? $this->input->post('notifications') : '';
        $chat_notification = !empty($this->input->post('chat_notification')) ? $this->input->post('chat_notification') : '';
        $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';  
        $searching_for_sport_partner = !empty($this->input->post('searching_for_sport_partner')) ? $this->input->post('searching_for_sport_partner') : '';  
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
                if(!empty($display_profile) || !empty($display_email) || !empty($display_mobile)|| !empty($available)|| !empty($notifications) || !empty($chat_notification)|| !empty($location) || !empty($searching_for_sport_partner)) {
                    $table="privileges_notifications";
                    $updated_data = array(
                        'display_profile'=> $display_profile,
                        'available'=> $available,
                        'notifications'=> $notifications,
                        'chat_notification'=> $chat_notification,
                        'location'=> $location,
                        'searching_for_sport_partner'=> $searching_for_sport_partner,
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
                    );   
                    $condition = array("fk_uid" => $uid);                    
                    $result = $this->Md_database->updateData($table, $updated_data,$condition); 

                    $table="user";
                    $updated_data = array(
                        'mobStatus'=> $display_mobile,
                        'emailStatus'=> $display_email,
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
                    );   
                    $condition = array("pk_id" => $uid);                    
                    $result = $this->Md_database->updateData($table, $updated_data,$condition);         
                    $resultarray = array('error_code' => '1', 'uid'=>$uid ,'message' => 'Personal data updated  successfully');             
                    echo json_encode($resultarray);
                    exit();                       
                }else{ 
                    $resultarray = array('error_code' => '2', 'message' => 'Data Empty');
                    echo json_encode($resultarray);
                    exit();           
                }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }      
    }

    public function settingList(){
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
          $table = "user";
          $orderby = 'pk_id asc';
          $condition = array('status' => '1', 'pk_id' => $uid);
          $col = array('pk_id','mobStatus','emailStatus');
          $mobEmail = $this->Md_database->getData($table, $col, $condition, $orderby, '');

          $table = "privileges_notifications";
          $orderby = 'pk_id asc';
          $condition = array('status' => '1', 'fk_uid' => $uid);
          $col = array('pk_id','display_profile','available','notifications','chat_notification','location','searching_for_sport_partner');
          $privilage = $this->Md_database->getData($table, $col, $condition, $orderby, '');

          $resultarray = array('error_code' => '1', 'message' => 'Privilage Data','mobile' => (!empty($mobEmail[0]['mobStatus']) ? $mobEmail[0]['mobStatus'] : ''),'email' => (!empty($mobEmail[0]['emailStatus']) ? $mobEmail[0]['emailStatus'] : ''),'display_profile' => (!empty($privilage[0]['display_profile']) ? $privilage[0]['display_profile'] : ''),'notifications' => (!empty($privilage[0]['notifications']) ? $privilage[0]['notifications'] : ''),'available' => (!empty($privilage[0]['available']) ? $privilage[0]['available'] : ''),'chat_notification' => (!empty($privilage[0]['chat_notification']) ? $privilage[0]['chat_notification'] : ''),'location' => (!empty($privilage[0]['location']) ? $privilage[0]['location'] : ''),'searching_for_sport_partner' => (!empty($privilage[0]['searching_for_sport_partner']) ? $privilage[0]['searching_for_sport_partner'] : ''));
              echo json_encode($resultarray);
              exit();                       
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 


    }
    public function resetPassword(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $oldpass = !empty($this->input->post('oldpass')) ? $this->input->post('oldpass') : '';
        $newpass = !empty($this->input->post('newpass')) ? $this->input->post('newpass') : '';    
        if (empty($uid) || empty($oldpass)|| empty($newpass)){
            $resultarray = array('error_code' => '2', 'message' => 'uid or oldpass or newpass  is  empty');
            echo json_encode($resultarray);
            exit();
        }
        if (!empty($oldpass)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('pk_id' =>$uid, 'password' => base64_encode($oldpass));
            $col = array('pk_id,password');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            
            if (empty($checkUser)) {
                $resultarray = array('error_code' => '5', 'message' => 'oldpassword  not in database.');
                echo json_encode($resultarray);
                exit();
            }
            if ($oldpass == $newpass) {
                $resultarray = array('error_code' => '6', 'message' => 'oldpassword and newpassword is same.Please change password.');
                echo json_encode($resultarray);
                exit();
            }

            $insert_data = array(
                'pk_id' => $uid,
                'password' =>base64_encode($newpass),
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
            );
            $condition = array('pk_id' => $uid);
            $this->Md_database->updateData('user', $insert_data, $condition);
            $resultarray = array('error_code' => '1', 'message' => 'password change  successfully');
            echo json_encode($resultarray);
            exit();
       
        }         
    }
}