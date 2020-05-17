<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_registration_verified extends CI_Controller {
    public function verifyEmailAddress(){
      	$this->load->library('encryption');//load this library. 
        $email = $this->uri->segment(4);
        $uid = $this->uri->segment(3);

		$decode_email=base64_decode($email);
            $table="user";    
            if (!empty($uid)) {
                $condition = array("pk_id" => $uid);
            }else{
                $condition = array();
            }
            $update = array(                    
                    'status' => 1,   
                    'verifyEmail' => $decode_email,     
                    'updatedBy' => $uid, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']       
            );
            $ret = $this->Md_database->updateData($table, $update, $condition);

            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','pk_id' => $uid); 
            $col = array('pk_id','email');
            $checkEmail = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            if (!empty($checkEmail) && $checkEmail[0]['email'] == $decode_email) {
                // print_r($checkEmail);
                // die();
                $table="user";    
                if (!empty($uid)) {
                    $condition = array("pk_id" => $uid);
                }else{
                    $condition = array();
                }
                $update = array(                    
                        'verify_status' => "1",     
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']       
                );
                $updatestatus = $this->Md_database->updateData($table, $update, $condition);             
            }


        $this->load->view('admin/registration_verified/vw_registration_verified');
    }

    public function verifyOtherEmailAddress(){
        $this->load->library('encryption');//load this library. 
        $email = $this->uri->segment(4);
        $uid = $this->uri->segment(3);

        $decode_email=base64_decode($email);
            $table="koodo_user_profile_detail";    
            if (!empty($uid)) {
                $condition = array("koodo_user_profile_detail.user_id" => $uid);
                $this->db->where('usertype',3);
            }else{
                $condition = array();
            }
            $update = array(                    
                    'status' => 1,   
                    'verifyOtherEmail' => $decode_email,     
                    'updatedBy' => $uid, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']       
            );
            $ret = $this->Md_database->updateData($table, $update, $condition);



        $this->load->view('admin/registration_verified/vw_registration_verified');
    }
}