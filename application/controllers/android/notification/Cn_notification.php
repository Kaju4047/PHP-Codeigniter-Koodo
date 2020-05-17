<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_notification extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
  function viewNotification(){
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
      $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
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
          //change read status
          $table="custom_notification";
          $updated_data = array(
              'read_status'=>'1', 
              'updatedBy' => $uid, 
              'updatedDate' => date('Y-m-d H:i:s'),
              'updated_ip_address' => $_SERVER['REMOTE_ADDR']
          );     
          $condition = array("status" => '1','to_user_id'=>$uid);    
          $result = $this->Md_database->updateData($table, $updated_data,$condition);

          //Notification List
          $table = "custom_notification";
          $orderby = 'custom_notification.pk_id DESC';
          $this->db->limit($limit, $offset);
          $condition = array('custom_notification.status' => '1','to_user_id' =>$uid,'delete_status'=>'2');
          $this->db->join('user','custom_notification.from_uid = user.pk_id','LEFT');
          $col = array('custom_notification.pk_id','subject',"COALESCE(message,'') as message",'custom_notification.created_date','redirect_type',"COALESCE(image,'') as image","COALESCE(img,'') as profile_image");
           // $col = array('custom_notification.pk_id','subject',"COALESCE(message,' ') as message",'redirect_type',"COALESCE(image,' ') as image","COALESCE(image,' ') as profile_image",'CASE WHEN koodo_custom_notification.created_date > date("Y-m-d H:i:s") THEN koodo_custom_notification.created_date END AS created_date','CASE WHEN koodo_custom_notification.created_date = date("Y-m-d H:i:s") THEN date(H:i:s,strtotime(koodo_custom_notification.created_date)) END AS created_date,');
          $notification = $this->Md_database->getData($table, $col, $condition, $orderby, '');

          $resultarray = array('error_code' => '1','message'=>'Notification','profile_path' => base_url().'uploads/users/','getNotificationData'=> $notification);
          echo json_encode($resultarray);
          exit();   
      }else {
          $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
          echo json_encode($resultarray);
          exit();                     	
      } 
  }
  function deleteNotification(){
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
          $table="custom_notification";
          $updated_data = array('delete_status'=>'1', 'updatedBy' => $uid, 
              'updatedDate' => date('Y-m-d H:i:s'),
              'updated_ip_address' => $_SERVER['REMOTE_ADDR']);     
          $condition = array("status" => '1','to_user_id'=>$uid);    
          $result = $this->Md_database->updateData($table, $updated_data,$condition);                
          $resultarray = array('error_code' => '1', 'uid'=>$uid ,'message' => 'Notification  delete  successfully');             
          echo json_encode($resultarray);
          exit(); 
      } else {
          $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
          echo json_encode($resultarray);
          exit();                       
      }
}

function deleteSingleNotification(){
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      $pk_id = !empty($this->input->post('pk_id')) ? $this->input->post('pk_id') : '';
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
          $table="custom_notification";
          $updated_data = array('delete_status'=>'1', 'updatedBy' => $uid, 
              'updatedDate' => date('Y-m-d H:i:s'),
              'updated_ip_address' => $_SERVER['REMOTE_ADDR']);     
          $condition = array("status" => '1','to_user_id'=>$uid,'pk_id'=>$pk_id);    
          $result = $this->Md_database->updateData($table, $updated_data,$condition);                
          $resultarray = array('error_code' => '1', 'uid'=>$uid ,'message' => 'Notification  delete  successfully');             
          echo json_encode($resultarray);
          exit(); 
      } else {
          $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
          echo json_encode($resultarray);
          exit();                       
      }
}


// function update_notification(){
//      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
//      $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';    
//      $subject = !empty($this->input->post('subject')) ? $this->input->post('subject') : '';
//      $message = !empty($this->input->post('message')) ? $this->input->post('message') : '';
//   if (!empty($uid)) {
//               $table = "user";
//               $orderby = 'pk_id asc';
//               $condition = array('status' => '2', 'pk_id' => $uid);
//               $col = array('pk_id','name');
//               $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
//               if (!empty($checkUser)) {
//                 $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
//                 echo json_encode($resultarray);
//                 exit();
//                }
//                  $table="notification";
//                 $updated_data = array('type'=> $type,'subject'=> $subject,'message'=> $message);     
//                 $condition = array("status" => '1','type'=>$type);    
//                     $result = $this->Md_database->updateData($table, $updated_data,$condition);                
//                     $resultarray = array('error_code' => '1', 'uid'=>$uid ,'message' => 'Notification  updated  successfully');             
//                     echo json_encode($resultarray);
//                     exit(); 
//                      } else {
//               $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
//                     echo json_encode($resultarray);
//                     exit();                       
//             }
// }
    public function checkReadUnread(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $checkNotificationStatus = !empty($this->input->post('notification_status')) ? $this->input->post('notification_status') : '';
    	  if (!empty($uid) && !empty($checkNotificationStatus)) {
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
            $table = "custom_notification";
            $condition = array('to_user_id' => $uid);
            $updated_data = array('read_status'=> $checkNotificationStatus,
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']
            );

            $getNotificationData = $this->Md_database->updateData($table,$updated_data,$condition);  
          
            $resultarray = array('error_code' => '1','message'=>'read notification');
            echo json_encode($resultarray);  
            exit();     	
        }else{
            $resultarray = array('error_code' => '2','message' => 'Uid or notification_status is empty');
            echo json_encode($resultarray);
            exit();                     	
        }  
    }
}