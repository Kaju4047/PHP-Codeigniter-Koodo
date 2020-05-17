<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_advertise extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    public function addAdvertise(){
       $name = !empty($this->input->post('name')) ? $this->input->post('name') : '';
       $mobile_no = !empty($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : '';
       $email_id = !empty($this->input->post('email_id')) ? $this->input->post('email_id') : '';
       $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';
       $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
       // print_r($name);
       // die();
      
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
            if(empty($name) || empty($email_id) || empty($description)){
                $resultarray = array('error_code' => '2', 'message' => 'name or email id or description is required');
                echo json_encode($resultarray);
                exit();
            }else{  
                    $table = "advertise_enquiry";
                    $insert_data = array(
                        'name'=> $name,
                        'mobile_no'=> $mobile_no,
                        'email_id'=>$email_id,                        
                        'city'=> $city,                             
                        'description'=> $description,                             
                        'status' => '1',
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                    $academy_id = $this->db->insert_id();
                    $resultarray = array('error_code' => '1','message' => 'Advertise enquiry data insert successfully');
                    echo json_encode($resultarray);
                    exit();   
                                              
            }         
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                       
        } 
    }
   
    

}
