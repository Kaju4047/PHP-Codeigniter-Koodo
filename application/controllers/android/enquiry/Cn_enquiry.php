<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_enquiry extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function enquiryFor(){
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

          $table = "enqtype";
          $orderby = 'pk_id asc';
          $condition = array('status' => '1');
          $col = array('pk_id','enqtype');
          $enquiryFor = $this->Md_database->getData($table, $col, $condition, $orderby, '');

          $resultarray = array('error_code' => '1', 'enquiryFor'=>$enquiryFor,'message' => 'enquiryFor');
          echo json_encode($resultarray);
          exit(); 

      }else{
          $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
          echo json_encode($resultarray);
          exit();                       
      }        
  }

  public function addEnquiry(){
    	$enquiry_for = !empty($this->input->post('enquiry_for')) ? $this->input->post('enquiry_for') : '';
    	$comment = !empty($this->input->post('comment')) ? $this->input->post('comment') : '';
      $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : '';
      $coach_level = !empty($this->input->post('coach_level')) ? $this->input->post('coach_level') : '';
      $no_session = !empty($this->input->post('no_session')) ? $this->input->post('no_session') : '';
      $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
      $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';
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

              if(empty($enquiry_for) ) {
                  $resultarray = array('error_code' => '2', 'message' => 'enquiry_for is empty');
                  echo json_encode($resultarray);
                  exit();
              }else{
                  $table ="enquiry";
                  $inserted_data = array(
                      'enqid'=> $enquiry_for,
                      // 'comment'=>$comment,                                  
                      'sport_id'=>$sport_id,                                  
                      'coach_level'=>$coach_level,                                  
                      'no_session'=>$no_session,                                  
                      // 'comment'=>$description,                                  
                      'location'=>$location,                                  
                      'status' => '1', 
                      'user_id' => $uid,    
                      'createdBy' => $uid,
                      'createdDate' => date('Y-m-d H:i:s'),                
                      'created_ip_address' => $_SERVER['REMOTE_ADDR']    
                  );    
                  if (!empty($comment)) {
                     $inserted_data['comment']=$comment;
                  }
                   if (!empty($description)) {
                     $inserted_data['comment']=$description;
                  }
                  $resultarray = $this->Md_database->insertData($table, $inserted_data, $condition);
                  $enquiry_id = $this->db->insert_id();
                  if ($enquiry_for==5) {
                       //Notification for Coach only who buy platinum plan.
                      $table = "buy_subscription";
                      $select = "buy_subscription.user_id,category,usertype";
                      $this->db->join('profile_type','profile_type.user_id=buy_subscription.user_id ');
                      $this->db->distinct();
                      $this->db->where('usertype','2');
                      $this->db->where('profile_type.user_id!=',$uid);
                      $this->db->where('buy_subscription.category','Platinum');
                      // $this->db->order_by('pk_id','ASC');
                      $chechPlatinumPlanCoach = $this->Md_database->getData($table, $select, '', 'buy_subscription.user_id ASC', '');
                      // print_r($chechPlatinumPlanCoach);
                      // die();
                      foreach ($chechPlatinumPlanCoach as $key => $value){
                          $id= $value['user_id'];

                          $table = "privileges_notifications";
                          $select = "notifications,chat_notification";
                          $this->db->where('fk_uid',$id);
                          $this->db->order_by('pk_id','ASC');
                          $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                          $notification=!empty($chechprivilege[0]['notifications'])?$chechprivilege[0]['notifications']:'';

                          $message = '' ;
                          if ($notification=='1'){
                              $table = "user";
                              $select = "user.token,user.pk_id,name";
                              $this->db->join('profie_player_sport','profie_player_sport.user_id=user.pk_id');
                              $this->db->where('user.pk_id',$id);
                              $this->db->where('sportname',$sport_id);
                              $this->db->where('user.pk_id!=',$uid);
                              $this->db->order_by('user.pk_id','ASC');
                              $this->db->distinct();
                              $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                              $target=!empty($order_token[0]['token'])?$order_token[0]['token']:'';   
                              $table = "user";
                              $orderby = 'user.pk_id asc';
                              $condition = array('pk_id'=>$uid);
                              $col = array('user.pk_id,token','name');
                              $user_name= $this->Md_database->getData($table, $col,$condition, $orderby, '');
                              $name=$user_name[0]['name'];
                              $message=ucwords($name)." enquiry for Private Coach";

                              $resultarray = array('message' => $message,'redirect_type' =>'private_coach_enquiry','subject'=>'Private Coach Enquiry');        
                              $this->Md_database->sendPushNotification($resultarray,$target);

                              $table = "custom_notification"; 
                              $insert_data = array(
                                 'from_uid'=>$uid,
                                 'to_user_id'=>$id,
                                 'usertype'=>3,
                                 'redirect_type' => 'private_coach_enquiry',
                                  'subject' => 'Looking for coach',
                                  'message' => $message,
                                  'status' => '1',
                                  'created_by ' => $uid,
                                  'created_date' => date('Y-m-d H:i:s'),
                                  'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                              );
                              $result = $this->Md_database->insertData($table, $insert_data);
                          }
                      }
                  }            
                  $resultarray = array('error_code' => '1', 'uid'=>$uid,'enquiry_id'=>$enquiry_id ,'message' => 'Enquiry submit successfully');
                  echo json_encode($resultarray);
                  exit();                     	
              }         
          }else {
            	$resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
              echo json_encode($resultarray);
              exit();                     	
          }        
    }

    // public function privateCoachEnquiry(){
    //     $sport = !empty($this->input->post('sport')) ? $this->input->post('sport') : '';
    //     $coach_level = !empty($this->input->post('coach_level')) ? $this->input->post('coach_level') : '';
    //     $noSession = !empty($this->input->post('noSession')) ? $this->input->post('noSession') : '';
    //     $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
    //     $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';
    //     $name = !empty($this->input->post('name')) ? $this->input->post('name') : '';
    //     $mob = !empty($this->input->post('mob')) ? $this->input->post('mob') : '';
    //     $email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
    //     $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      

    //     if (!empty($uid)) {
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
    //         if (!empty($sport) && !empty($coach_level) &&!empty($noSession) &&!empty($description) &&!empty($location) &&!empty($name) &&!empty($mob) &&!empty($email  )) {
    //             $table ="private_coach_enquiry";
    //             $inserted_data = array(
    //                 'sport'=> $sport,
    //                 'coach_level'=> $coach_level,
    //                 'noSession'=> $noSession,
    //                 'description'=>$description,                                  
    //                 'location'=>$location,                                  
    //                 'name'=>$name,                                  
    //                 'mob'=>$mob,                                  
    //                 'email'=>$email,                                  
    //                 'status' => '1',   
    //                 'user_id' => 'uid',   
    //                 'createdBy' => $uid,
    //                 'createdDate' => date('Y-m-d H:i:s'),                
    //                 'created_ip_address' => $_SERVER['REMOTE_ADDR']   
    //             );     
    //             $resultarray = $this->Md_database->insertData($table, $inserted_data, $condition);
    //             $enquiry_id = $this->db->insert_id();
    //             $resultarray = array('error_code' => '1', 'uid'=>$uid,'enquiry_id'=>$enquiry_id ,'message' => 'Enquiry successfully Insert');
    //             echo json_encode($resultarray);
    //             exit(); 
    //         }else{
    //             $resultarray = array('error_code' => '2','message' => 'sport or coach_level or noSession or description or location or name or mob or email is empty ');
    //             echo json_encode($resultarray);
    //             exit(); 
    //         }
    //     }else {
    //         $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
    //         echo json_encode($resultarray);
    //         exit();                       
    //     }  
    // } 
}

