<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_chat extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function chat_user_list(){
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

                   $table = "chat";
                    // $condition = array('from_uid'=>$uid);
                    // $this->db->or_where("to_user_id",$uid);
                   $this->db->where("(from_uid=$uid OR to_user_id=$uid)");
                   $this->db->group_start();
                   $this->db->where("delete_status<>",'2');
                   $this->db->where("delete_status<>",$uid);
                   $this->db->group_end();
                   $col = array('to_user_id','from_uid');
                   $this->db->distinct();
                   $List = $this->Md_database->getData($table,$col, '','', '');
                 // print_r($List);
                         // print_r($student_arr1);
                   foreach($List as $key => $value) {
                       if ($value['to_user_id']== $uid) {
  
                             $List1[]=$value['from_uid'];
                                  $array[]  =$List1;                                
                         }
                        if ($value['from_uid']== $uid) {
  
                             $List1[]=($value['to_user_id']);
                                        $array[]  =$List1;                                       
                         }
                   }
                   $empty=array();
                   $l=!empty($List1)?$List1:$empty;
                   $id=array_unique($l);

             foreach ($id as $key => $value) {
	
	               $table = "chat";
                   $orderby = 'createdDate DESC';
                    $condition = array('status' => '1');
                    $this->db->where("(from_uid=$value AND to_user_id=$uid)");
                   $this->db->or_where("(from_uid=$uid AND to_user_id=$value)");            
                   $this->db->limit(1);

                   $col = array('pk_id,to_user_id,from_uid,message,createdDate');
                   $message = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $Chat['message']=$message[0]['message'];
                    $Chat['createdDate']=$message[0]['createdDate'];
                    // $Chat['image']=$message[0]['image'];
                    // $Chat['location']=!empty($message[0]['location'])?$message[0]['location']:'';
                    $table = "user";
                   $orderby = 'pk_id DESC';
                    $condition = array('status' => '1','pk_id'=>$value);
                   $col = array('pk_id,name,img');
                   $user = $this->Md_database->getData($table, $col, $condition, $orderby, '');
  //                 $a[]= $message;
                   $Chat['name']=!empty($user[0]['name'])?$user[0]['name']:'';
                  $Chat['img']=!empty($user[0]['img'])?$user[0]['img']:'';
                   // die();
                      $Chat_list[] =  $Chat;
                   // print_r($Chat_list);
               } 
                                    
                    $resultarray = array('error_code' => '1','Chat_list'=>!empty($Chat_list)?$Chat_list:$empty,'img_path' => base_url().'uploads/users/','message' => 'Message List');
                    echo json_encode($resultarray);
                    exit(); 

                }else {
                   $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                               
                }

    }
    public function chat(){
    	 $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    	 $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
    	 $message = !empty($this->input->post('message')) ? $this->input->post('message') : '';
    	     // $img='[{"image":"5d725d2c2925c.png"},{"image":"5d725d2c2925c.png"},{"image":"5d725d2c2925c.png"}]'; 
    	 $img = !empty($this->input->post('image')) ? $this->input->post('image') : '';
    	     // print_r($img);
    	     // die();
    	 $image = json_decode($img);
    	 // $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';
    	   if ((!empty($uid) && !empty($user_id))&&(!empty($message) ||!empty($image))) {
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
             if (!empty($image)) {

              foreach ($image as $key => $value) {
             	 $img=$value->image;
             	// die();
             
                 if (!empty($_FILES['image']['size']) && ($_FILES['image']['size'] > 0)){
               //   if (!empty($_FILES['image']['name'])) {
               //      	// print_r($_FILES);
               // 	$rename_name = uniqid(); //get file extension:
               //  $arr_file_info = pathinfo($_FILES['image']['name']);
               //  $file_extension = $arr_file_info['extension'];
               //  $newname = $rename_name . '.' . $file_extension;
               //   // print_r($newname);die();
               //  $old_name = $_FILES['image']['name'];
               //  // print_r($old_name);die();
               // $path = "uploads/chat/images/";

               //  if (!is_dir($path)) {
               //      mkdir($path, 0777, true);
               //  }
               //  $upload_type = "jpg|png|jpeg";

               //  $chat_images = $this->Md_database->uploadFile($path, $upload_type, "image", "image", $newname);
                // print_r( $chat_images);
                // die();

                        $images_path = "uploads/chat/images/";
                        $images_upload_type = "jpg|png|jpeg";
                        $images_name=uniqid();
                       
                         $chat_images = $this->Md_database->multiUploadFile($images_path, $images_upload_type, "image", "image",$images_name);

                      $table = "chat";
                      $insert_data = array(
                           'from_uid'=> $uid,                       
                           'to_user_id'=> $user_id,                       
                           'message'=> $chat_images,                     
                           'status' => '1',
                           'createdBy' => $uid,
                           'createdDate' => date('Y-m-d H:i:s'),                
                           'created_ip_address' => $_SERVER['REMOTE_ADDR'] 

                        );
                       $resultarray = $this->Md_database->insertData($table, $insert_data);

                    }        
                     
                  }
                    $resultarray = array('error_code' => '1','message' => 'Send Images');
                    echo json_encode($resultarray);
                    exit(); 
                }
                   if (!empty($message)) {
                   	

                      $table = "chat";
                      $insert_data = array(
                           'from_uid'=> $uid,                       
                           'to_user_id'=> $user_id,                       
                           'message'=> $message,
                           'status' => '1',
                           'createdBy' => $uid,
                           'createdDate' => date('Y-m-d H:i:s'),
                           'created_ip_address' => $_SERVER['REMOTE_ADDR']                 
                        );
                       $resultarray = $this->Md_database->insertData($table, $insert_data);

                    $resultarray = array('error_code' => '1','message' => 'Send Message or Location');
                    echo json_encode($resultarray);
                    exit(); 
                       }                                   

                }else {
                   $resultarray = array('error_code' => '2', 'message' => 'from_uid or to_user_id or message/image is empty');
                    echo json_encode($resultarray);
                    exit();                       
          
                }
            
   }
   public function chat_message_list(){
    	 $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    	 $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
    	 // $message = !empty($this->input->post('message')) ? $this->input->post('message') : '';
    	   if (!empty($uid) || !empty($user_id)) {
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
                   $table = "chat";
                   $orderby = 'createdDate asc';
                   // $condition = array("delete_status"=>'1');
                   // $this->db->group_start();
                   //  // $this->db->where("delete_status",'1');
                   // // $this->db->or_where("delete_status!=",$uid);
                   //  $this->db->where("delete_status!=",$uid);
                   //  $this->db->or_where("delete_status!=",'2');
                   // $this->db->group_end();

                   // $this->db->where("(delete_status=1 OR delete_status=$user_id) OR (delete_status!=2 OR delete_status!=$uid)");
                   $this->db->group_start();
                   $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                   $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                   $this->db->group_end();
            		 $where_cheque = '((delete_status!= '.$uid.' and delete_status!=2) or (delete_status=1) )';
        			$this->db->where($where_cheque);
               
                   $col = array('from_uid','to_user_id','message','createdDate','delete_status');
                   $messageList = $this->Md_database->getData($table,$col,'', $orderby, '');
                                         
                    $resultarray = array('error_code' => '1','messageList'=>$messageList,'message' => 'Message List','img_path' => base_url().'uploads/chat/images/');
                    echo json_encode($resultarray);
                    exit(); 

                }else {
                   $resultarray = array('error_code' => '3', 'message' => 'to_user_id or from_uid  is empty');
                    echo json_encode($resultarray);
                    exit();                               
                }
    }
     public function delete_chat_message_list(){
    	 $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    	 $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
    	   if (!empty($uid) || !empty($user_id)){
            $table = "user";
              $orderby = 'pk_id asc';
              $condition = array('status' => '2','pk_id' => $uid);
              $col = array('pk_id','name');
              $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
              if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
               }

                       $table = "chat";
                       $orderby = 'pk_id asc';
                       $condition = array();
                        $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                           $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                       $col = array('pk_id','delete_status','from_uid');
                       $checkDeleteStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                       //   print_r($checkDeleteStatus);
                       // die();
                        foreach ($checkDeleteStatus as $key => $value) {
                        	# code...
                       //    print_r($value);
                       // die();


                          
                          if ($value['delete_status']=='1' && $value['delete_status']!='2' && $value['delete_status']!=$uid  && $value['delete_status']!=$user_id){
                        	echo "1";
                         
                         //    print_r($value['delete_status']);
                            // die();
                          	$update_data = array(                                              
                           'delete_status' =>$uid,                       
                           'updatedBy' => $uid,
                           'updatedDate' => date('Y-m-d H:i:s'),
                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                            );
                             $table = "chat";
                          
                           $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                           $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                         
                             $condition = array('delete_status'=>'1');
                            
                           // $this->db->where("delete_status!=",'2');
                           // $this->db->or_where("delete_status<>",$uid);
                            $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                          }elseif ($value['delete_status']==$uid && $value['delete_status']=='1' && $value['delete_status']!=$user_id &&$value['delete_status']!='2' ){
                            print_r('43');
                          	 $update_data = array(                                              
	                           'delete_status' =>$uid,                       
	                           'updatedBy' => $uid,
	                           'updatedDate' => date('Y-m-d H:i:s'),
	                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
	                        );
                          	 $table = "chat";
                             $condition = array(
		                           'delete_status'=> $uid                         
		                        );
                              $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                             $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                              $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                          }
                        	  elseif ($value['delete_status']=='2'&& $value['delete_status']!=$uid  && $value['delete_status']==$user_id &&$value['delete_status']!='1' ){
                            print_r('2');
                            // die();
                          	 $update_data = array(                                              
	                           'delete_status' =>'2',                       
	                           'updatedBy' => $uid,
	                           'updatedDate' => date('Y-m-d H:i:s'),
	                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
	                        );
                          	 $table = "chat";
                             $condition = array(
		                           'delete_status'=> '2'                         
		                        );
                              $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                             $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                              $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                          }
                          if ($value['delete_status']!=$uid && $value['delete_status']!='1'&& $value['delete_status']!='2') {
                            print_r('59');
                         
                          	     $update_data = array(                                              
	                           'delete_status' =>'2',                       
	                           'updatedBy' => $uid,
	                           'updatedDate' => date('Y-m-d H:i:s'),
	                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
	                             );
                          	 $table = "chat";
                             $condition = array( 'delete_status!='=> $uid,'delete_status!='=> '1','delete_status!='=> '2' );
                           // $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                              // $this->db->where("(delete_status !=$uid OR to_user_id=$user_id)");
                              $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                           $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                              $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                             }

                       }
                        
                                         
                    $resultarray = array('error_code' => '1','message' => 'Message List deleted');
                    echo json_encode($resultarray);
                    exit(); 
                }else {
                   $resultarray = array('error_code' => '3', 'message' => 'from_uid or to_user_id is empty');
                    echo json_encode($resultarray);
                    exit();                               
                }
    }

}
