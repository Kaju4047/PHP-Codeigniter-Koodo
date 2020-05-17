<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_chat extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function chatUserList(){
        // In chat Table  status  replace 1-user-added for  not deleted , 2-user-deleted
        // block_status    1-unblocked  , 2-blocked
        //delete_status   1-msg-added ,2-msg-deleted 
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
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "chat";               
            $this->db->limit($limit, $offset);
            $this->db->where("(from_uid=$uid OR to_user_id=$uid)");
            $this->db->where("(status=1 OR status!=$uid)");
            $this->db->where("individual_group_status",1);
            $col = array('to_user_id','from_uid');
            $this->db->order_by("pk_id", "DESC");
            $this->db->distinct();
            $List = $this->Md_database->getData($table,$col, '','', '');
            foreach($List as $key => $value) {
                if ($value['to_user_id']== $uid){
                    $List1[]=$value['from_uid'];
                    $array[]  =$List1;                                
                }
                if ($value['from_uid']== $uid){
                    $List1[]=($value['to_user_id']);
                    $array[]  =$List1;                                       
                }
            }
            $empty=array();
            $l=!empty($List1)?$List1:$empty;
            $id=array_unique($l);
            foreach ($id as $key => $value){
	            $table = "chat";
                $orderby = 'createdDate DESC';
                $condition = array('status' => '1','individual_group_status'=>1); 
                $this->db->where("(from_uid=$value AND to_user_id=$uid)");
                $this->db->or_where("(from_uid=$uid AND to_user_id=$value)");       
                $this->db->limit(1);
                $col = array('pk_id,to_user_id,from_uid,message,image,createdDate','delete_status');
                $message = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                if (!empty($message[0]['message']) && ($message[0]['delete_status'] == 1 ||$message[0]['delete_status'] != $uid)) {                    
                    $Chat['message']=$message[0]['message'];
                }else{
                    $Chat['message']='';
                } 
                if (empty($message[0]['message']) && !empty($message[0]['image'])) {                    
                    $icon= json_decode('"\ud83d\udcf7"');
                    $Chat['message']=$icon.' Photo';
                }
                $Chat['id']=$value;

                $date=!empty($message[0]['createdDate'])?$message[0]['createdDate']:'';
                $dateFormate=date("d-m-Y",strtotime($date));
                $timeFormate=date("h.i A",strtotime($date));
                $Chat['createdDate']=$dateFormate;
                $Chat['createdtime']=$timeFormate;

                $table = "user";
                $orderby = 'pk_id DESC';
                $condition = array('pk_id'=>$value);
                $col = array('pk_id,name,img','status','online_status');
                $user = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $Chat['online_status']=!empty($user[0]['online_status'])?$user[0]['online_status']:'2';
                if (!empty($user[0]['status']) && $user[0]['status']==2){
                    $Chat['name']='Inactive User';
                    $Chat['img']='';
                }elseif (!empty($user[0]['status']) && $user[0]['status']==3) {
                    $Chat['name']='Deleted User';
                    $Chat['img']='';
                }else{
                    $Chat['name']=!empty($user[0]['name'])?$user[0]['name']:'';
                    $Chat['img']=!empty($user[0]['img'])?$user[0]['img']:'';
                }
                $Chat['delete_status']=!empty($user[0]['status'])?$user[0]['status']:'';
                $Chat_list[] =  $Chat;
            } 
            $resultarray = array('error_code' => '1','Chat_list'=>!empty($Chat_list)?$Chat_list:$empty,'img_path' => base_url().'uploads/users/','message' => 'Message List');
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }

    public function chat(){
    	$uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    	$user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
    	$message = !empty($this->input->post('message')) ? $this->input->post('message') : '';
    	// $image = !empty($this->input->post('image')) ? $this->input->post('image') : '';
        $group_status = !empty($this->input->post('group_status')) ? $this->input->post('group_status') : '';
    	  if (!empty($uid) && !empty($user_id)){
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
            $checkAvailableStatus=array();
            $table = "privileges_notifications";
            $orderby = 'pk_id asc';
            $condition = array();
            $this->db->group_by('fk_uid');                          
            $this->db->where("fk_uid",$user_id);
            $col = array('pk_id,available');
            $checkAvailableStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $availableStatus= !empty($checkAvailableStatus[0]['available'])?$checkAvailableStatus[0]['available']:'';

            $checkFriendStatus=array();
            $table = "friends";
            $orderby = 'pk_id DESC';
            $condition = array();
            $this->db->group_start();                          
            $this->db->where("(uid=$uid AND user_id=$user_id)");
            $this->db->or_where("(uid=$user_id AND user_id=$uid)");
            $this->db->group_end();
            $col = array('pk_id,request_status');
            $checkFriendStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $friendStatus= !empty($checkFriendStatus[0]['request_status'])?$checkFriendStatus[0]['request_status']:'0';

            if ($availableStatus==2 && $friendStatus!= 1 && empty($group_status) ){
                $table = "user";
                $select = "user.pk_id,name";
                $this->db->where('pk_id',$user_id);
                $this->db->order_by('user.pk_id','ASC');
                $this->db->distinct();
                $name = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                $name=$name[0]['name'];    
                $resultarray = array('error_code' => '4','message' =>$name.' is unavailable for chat');
                echo json_encode($resultarray);
                exit();
            }
            if (!empty($group_status)) {
                $photoDoc='';
                if (!empty($_FILES['image']['name'])) {
                    $rename_name = uniqid(); //get file extension:
                    $arr_file_info = pathinfo($_FILES['image']['name']);
                    $file_extension = $arr_file_info['extension'];
                    $newname = $rename_name . '.' . $file_extension;
                    $old_name = $_FILES['image']['name'];
                    $path = "uploads/chat/images/";
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $upload_type = "jpg|png|jpeg";
                    $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname);                            
                }
                $table = "chat";
                $insert_data = array(
                    'from_uid'=> $uid,                       
                    'to_user_id'=> $user_id,                       
                    'status' => '1',
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']    
                );
                if ($message) {
                    $insert_data['message'] =$message;
                }
                if (!empty($photoDoc)){
                    $insert_data['image'] =$photoDoc;
                }
                if (!empty($group_status)){
                    $insert_data['individual_group_status'] =$group_status;
                }
                        
                $resultarray = $this->Md_database->insertData($table, $insert_data);
           
                #-------------------------------------------------------------#
                //push Notification group chat for all members
                $table = "chat_group";
                $select = "pk_id,subject";
                $this->db->where('pk_id',$user_id);
                $this->db->order_by('pk_id','ASC');
                $group_name = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

                $table = "koodo_chat_group_members";
                $select = "pk_id,fk_user_id";
                $this->db->where('fk_group_id',$user_id);
                $this->db->where_not_in('fk_user_id',$uid);
                $this->db->order_by('pk_id','ASC');
                $group_members = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

                foreach ($group_members as $key => $value){
                    $member_id=$value['fk_user_id'];
                    $table = "privileges_notifications";
                    $select = "notifications,chat_notification";
                    $this->db->where('fk_uid',$member_id);
                    $this->db->order_by('pk_id','ASC');
                    $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

                    $notification=!empty($chechprivilege[0]['notifications'])?$chechprivilege[0]['notifications']:'';
                    $chat_notification=!empty($chechprivilege[0]['chat_notification'])?$chechprivilege[0]['chat_notification']:'';

                    if ($notification=='1' && $chat_notification=='1'){
                        $table = "user";
                        $select = "token,user.pk_id,name";
                        $this->db->where('pk_id',$member_id);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $target=$order_token[0]['token'];
                
                        $table = "user";
                        $select = "user.pk_id,name";
                        $this->db->where('pk_id',$uid);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token2 = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $name=$order_token2[0]['name'];
                
                        if(!empty($message)){
                            // print_r($group_name);
                            // die();
                            $resultarray = array('message' => $message,'image' => $photoDoc ,'redirect_type' =>'group_chat_insert','subject'=>'You have a new message in group '.$group_name[0]['subject'].' from '.$name,'group_id'=>$user_id,'group_name'=>!empty($group_name[0]['subject'])?$group_name[0]['subject']:'');
                                
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database typewise
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>$uid,
                                'to_user_id'=>$member_id,
                                'redirect_type' => 'group_chat_insert',
                                'subject' => 'You have new message from '.$name,
                                'message'=>$message,
                                'status' => '1',
                                'created_by ' =>$uid,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }
                        $icon= json_decode('"\ud83d\udcf7"');

                        if(!empty($photoDoc)){
                            $resultarray = array('message' => $icon.'photo','image' => $photoDoc,'redirect_type' =>'group_chat_insert','subject'=>'You have a new image in'.$group_name[0]['subject'].' from '.$name,'group_id'=>$user_id);
                            // $resultarray = array('message' => $icon.'photo','redirect_type' =>'group_chat_insert','subject'=>'You have a new image in'.$group_name.' from '.$name,'group_id'=>$user_id);
                            // print_r($icon);
                            // print_r($group_name);
                            // print_r($name);
                            // print_r($user_id);
                            // die();
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database typewise
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>$uid,
                                'to_user_id'=>$member_id,
                                'redirect_type' => 'group_chat_insert',
                                'subject' => 'You have new image from '.$name,
                                'image' => $photoDoc,
                                'status' => '1',
                                'created_by ' => $uid,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }
                    }
                } 
                $resultarray = array('error_code' => '1','message' => 'Send Message or image or Location  Successfully','chat_img_path'=>base_url().'uploads/chat/images/'.$photoDoc,'group_id'=>$user_id,'group_name'=>!empty($group_name[0]['subject'])?$group_name[0]['subject']:'');
                  echo json_encode($resultarray);
                  exit();           
            }else{
                $checkBlockStatus=array();
                $table = "chat";
                $orderby = 'pk_id asc';
                $condition = array();
                $this->db->group_start();                          
                $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                $this->db->group_end();
                $col = array('pk_id,block_status');
                $checkBlockStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                if (empty($checkBlockStatus)||(!empty($checkBlockStatus) && ($checkBlockStatus[0]['block_status'] == '1' || $checkBlockStatus[0]['block_status'] == $user_id))){      
                    $photoDoc='';
                    if (!empty($_FILES['image']['name'])) {
                        $rename_name = uniqid(); //get file extension:
                        $arr_file_info = pathinfo($_FILES['image']['name']);
                        $file_extension = $arr_file_info['extension'];
                        $newname = $rename_name . '.' . $file_extension;
                        $old_name = $_FILES['image']['name'];
                        $path = "uploads/chat/images/";
                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";
                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname);                            
                    }
                    $table = "chat";
                    if (!empty($checkBlockStatus) && $checkBlockStatus[0]['block_status'] == $uid) {
                        $insert_data = array(
                            'from_uid'=> $uid,                       
                            'to_user_id'=> $user_id,                       
                            'status' => '1',
                            'createdBy' => $uid,
                            'block_status' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']    
                        );
                        if ($message) {
                            $insert_data['message'] =$message;
                        }
                        if ($photoDoc){
                            $insert_data['image'] =$photoDoc;
                        }    
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                    }elseif(empty($checkBlockStatus)||(!empty($checkBlockStatus) && $checkBlockStatus[0]['block_status'] == '1')){
                        $insert_data = array(
                            'from_uid'=> $uid,                       
                            'to_user_id'=> $user_id,                       
                            'status' => '1',
                            'block_status' => '1',
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        if ($message) {
                            $insert_data['message'] =$message;
                        }
                        if ($photoDoc){
                            $insert_data['image'] =$photoDoc;
                        }
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                    }elseif (!empty($checkBlockStatus) && $checkBlockStatus[0]['block_status'] == $user_id) {
                        $insert_data = array(
                            'from_uid'=> $uid,                       
                            'to_user_id'=> $user_id,                       
                            'status' => '1',
                            'createdBy' => $uid,
                            'block_status' => $user_id,
                            'createdDate' => date('Y-m-d H:i:s'),
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']   
                        );
                        if ($message) {
                            $insert_data['message'] =$message;
                        }
                        if ($photoDoc){
                            $insert_data['image'] =$photoDoc;
                        }
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                    }
                    #-------------------------------------------------------------#
                    //push Notification  of individual chat
                    $table = "privileges_notifications";
                    $select = "notifications,chat_notification";
                    $this->db->where('fk_uid',$user_id);
                    $this->db->order_by('pk_id','ASC');
                    $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                    $notification=($chechprivilege[0]['notifications']);
                    $chat_notification=($chechprivilege[0]['chat_notification']);
                    
                    if ($notification=='1' && $chat_notification=='1') {
                        $table = "user";
                        $select = "token,user.pk_id,name";
                        $this->db->where('pk_id',$user_id);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $target=$order_token[0]['token'];
                        
                        $table = "user";
                        $select = "user.pk_id,name";
                        $this->db->where('pk_id',$uid);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token2 = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $name=$order_token2[0]['name'];
                        
                        if(!empty($message)){
                            $resultarray = array('message' => $message,'image' => $photoDoc,'from_uid'=>$uid,'to_user_id'=>$user_id ,'redirect_type' =>'chat_insert','subject'=>'You have a new message from '.$name);
                                
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database typewise
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>$uid,
                                'to_user_id'=>$user_id,
                                'redirect_type' => 'chat_insert',
                                'subject' => 'You have new message from '.$name,
                                'message'=>$message,
                                'status' => '1',
                                'created_by ' =>$uid,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }
                        $icon= json_decode('"\ud83d\udcf7"');
                        if(!empty($photoDoc)){
                            $resultarray = array('message' => $icon.'photo','image' => $photoDoc,'from_uid'=>$uid,'to_user_id'=>$user_id ,'redirect_type' =>'chat_insert','subject'=>'You have a new image from '.$name);
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database typewise
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>$uid,
                                'to_user_id'=>$user_id,
                                'redirect_type' => 'chat_insert',
                                'subject' => 'You have new image from '.$name,
                                'image' => $photoDoc,
                                'status' => '1',
                                'created_by ' => $uid,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }
                    }

                    $resultarray = array('error_code' => '1','message' => 'Send Message or image or Location  Successfully','chat_img_path'=>base_url().'uploads/chat/images/'.$photoDoc);
                      echo json_encode($resultarray);
                      exit();
                }else{
                    if ($checkBlockStatus[0]['block_status'] == $uid){
                        $table = "chat";
                        $select = "chat.from_uid,user.name";
                        $this->db->where('from_uid',$user_id);
                        $this->db->join('user', 'user.pk_id = chat.from_uid');
                        $this->db->distinct();
                        $name = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');

                        $resultarray = array('error_code' => '3','message' => 'Unblock '.$name[0]['name'].' to send a message');
                        echo json_encode($resultarray);
                        exit(); 
                    }
                }
            }
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'from_uid or to_user_id or message or image is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
    public function chatMessageList(){
    	  $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    	  $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $group_status = !empty($this->input->post('group_status')) ? $this->input->post('group_status') : '';
    	  if (!empty($uid) || !empty($user_id)){
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
            $table = "chat_group_members";
            $orderby = 'pk_id asc';
            $condition = array('fk_group_id' => $user_id, 'fk_user_id' => $uid);
            $col = array('pk_id','clear_chat_date');
            $checkDeleteDate = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $chatClearDate=!empty($checkDeleteDate)?$checkDeleteDate[0]['clear_chat_date']:'';

            $table = "chat";
            $orderby = 'chat.createdDate DESC';
            $this->db->limit($limit, $offset);
            if(!empty($group_status)){
                $this->db->where('individual_group_status','2');
                $this->db->where('to_user_id',$user_id);
                if (!empty($chatClearDate)){
                    $this->db->where('chat.createdDate >',$chatClearDate);
                }
                $this->db->join('user','user.pk_id = chat.from_uid');
                $col = array('chat.from_uid','chat.to_user_id','user.name','message','image','chat.createdDate','chat.pk_id');
            }else{
                $this->db->where('individual_group_status','1');
                $this->db->group_start();
                $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                $this->db->group_end();
                $where_cheque = '((delete_status!= '.$uid.' and delete_status!=2) or (delete_status=1))';
            		$this->db->where($where_cheque);
                $col = array('from_uid','to_user_id','message','image','createdDate','delete_status','pk_id');
            }
            $List = $this->Md_database->getData($table,$col,'', $orderby, '');               
            $messageList=array_reverse($List);
            if (empty($group_status)){                   
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $user_id);
                $col = array('pk_id','name','img','status');
                $UserInfo = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                if (!empty($UserInfo[0]['status']) && $UserInfo[0]['status']==2){
                    $name='Inactive User';
                    $image='';
                }elseif (!empty($UserInfo[0]['status']) && $UserInfo[0]['status']==3) {
                    $name='Deleted User';
                    $image='';
                }else{                   
                    $image = !empty($UserInfo[0]['img'])?$UserInfo[0]['img']:'';
                    $name = !empty($UserInfo[0]['name'])?$UserInfo[0]['name']:'';
                }

                //Check Block Status
                $table = "chat";
                $orderby = 'pk_id asc';
                $condition = array();
                $this->db->group_start();
                $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                $this->db->group_end();
                $col = array('block_status');
                $BlockStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $my_block_status='2';
                $other_block_status='2';
                if (!empty($BlockStatus[0]['block_status']) && $BlockStatus[0]['block_status']==1){
                    $my_block_status='2';
                    $other_block_status='2';
                }
                if (!empty($BlockStatus[0]['block_status'])&& $BlockStatus[0]['block_status']==$uid) {
                    $my_block_status='2';
                    $other_block_status='1';
                }
                if (!empty($BlockStatus[0]['block_status'])&&$BlockStatus[0]['block_status']==$user_id) {
                    $my_block_status='1';
                    $other_block_status='2';
                }
                if (!empty($BlockStatus[0]['block_status'])&&$BlockStatus[0]['block_status']==2) {
                    $my_block_status='1';
                    $other_block_status='1';
                }
                $resultarray = array('error_code' => '1','my_block_status'=>$my_block_status,'other_block_status'=>$other_block_status,'messageList'=>$messageList,'name'=>$name,'image'=>$image,'message' => 'Message List','img_path' => base_url().'uploads/users/','chat_img_path'=>base_url().'uploads/chat/images/');
                echo json_encode($resultarray);
                exit(); 
            }else{
                $table = "chat_group";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $user_id);
                $col = array('pk_id','subject','profile_img');
                $UserInfo = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $group_id = !empty($UserInfo[0]['pk_id'])?$UserInfo[0]['pk_id']:'';
                $image = !empty($UserInfo[0]['profile_img'])?$UserInfo[0]['profile_img']:'';
                $subject = $UserInfo[0]['subject'];

                $table = "chat_group_members";
                $orderby = 'pk_id desc';
                $condition = array('fk_group_id'=>$group_id,'status'=>'1');                
                $this->db->distinct();
                $col = array('fk_user_id','pk_id');
                $group_members = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 

                //Group Admin
                $admin_id=!empty($group_members[0]['fk_user_id'])?$group_members[0]['fk_user_id']:'';
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id'=>$admin_id);                
                $this->db->distinct();
                $col = array('name','pk_id');
                $admin_name = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                $admin_name=!empty($admin_name[0]['name'])?$admin_name[0]['name']:'';


                $resultarray = array('error_code' => '1','message' => 'Message List','admin_id'=>$admin_id,'admin_name'=>$admin_name,'messageList'=>$messageList,'group_id'=>$group_id,'subject'=>$subject,'image'=>$image,'profile_img' => base_url().'uploads/chat/group_chat/','chat_img_path'=>base_url().'uploads/chat/images/');
                echo json_encode($resultarray);
                exit(); 
            }
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'to_user_id or from_uid  is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }

    public function deleteChatMessageList(){
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

            foreach ($checkDeleteStatus as $key => $value){
                $update_data=array();
                if ($value['delete_status']=='1') {
                    $update_data = array(
                        'delete_status' =>$uid,                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                    );                          
                    $condition = array('delete_status' =>'1');
                    $condition['individual_group_status']='1';  
                    $table = "chat";
                    $this->db->group_start();                          
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }elseif ($value['delete_status']==$uid){
                    $update_data = array(                                              
                        'delete_status' =>$uid,                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                    );                          
                    $condition = array('delete_status' =>$uid);
                    $condition['individual_group_status']='1';  
                    $table = "chat";
                    $this->db->group_start();                          
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }elseif ($value['delete_status']==$user_id){
                    $update_data = array(  
                        'delete_status' =>'2',                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $condition['individual_group_status']='1';
                    $condition = array('delete_status' =>$user_id);
                    $table = "chat";
                    $this->db->group_start();                          
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }elseif($value['delete_status']=='2'){
                    $update_data = array(  
                        'delete_status' =>'2',                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                    );                          
                    $condition = array('delete_status' =>'2');
                    $condition['individual_group_status']='1';  
                    $table = "chat";
                    $this->db->group_start();                          
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
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


    //  public function chat_send_pdf(){
         
    //   $document = !empty($this->input->post('certificate')) ? $this->input->post('certificate') : '';
    //     $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
    //    $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';

    //     if (!empty($uid) || !empty($user_id) || !empty($certificate) ) {
    //       // print_r("expression");die();
    //         $table = "user";
    //           $orderby = 'pk_id asc';
    //           $condition = array('status' => '2', 'pk_id' => $uid);
    //           $col = array('pk_id','name');
    //           $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //           if (!empty($checkUser)) {
    //             $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
    //             echo json_encode($resultarray);
    //             exit();
    //            }
           
    //             $insertData = array(                    
    //                         'status' => 1,   
    //                         'from_uid' => $uid,   
    //                         'to_user_id' => $user_id,   
    //                         // 'message' => $document,   
    //                         'createdBy' => $uid,
    //                         'createdDate' => date('Y-m-d H:i:s'),
    //                         'created_ip_address' => $_SERVER['REMOTE_ADDR']    
    //                    );
    //          $photoDoc3 = "";
    //        if (!empty($_FILES['certificate']['name'])) {
    //      // print_r($_FILES);

    //       //echo ;exit();
    //             $rename_name3 = uniqid(); //get file extension:
    //             $arr_file_info3 = pathinfo($_FILES['certificate']['name']);
    //             $file_extension3 = $arr_file_info3['extension'];
    //             // $newname3 = $rename_name3 . '.' . $file_extension3;
    //              // print_r($newname3);die();
    //             $old_name = $_FILES['certificate']['name'];
    //             // print_r($old_name);die();
    //            $path3 = "uploads/chat/document/";

    //             if (!is_dir($path3)) {
    //                 mkdir($path3, 0777, true);
    //             }
    //             $upload_type3 = "pdf|doc|docx";

    //             $photoDoc3 = $this->Md_database->uploadFile($path3, $upload_type3, "certificate", "", $old_name); 
    //             // print_r($photoDoc3) ;
    //             // die();
                 
                  
    //                 $table1 = "chat";
    //                  $insertData['message'] = $photoDoc3;        
    //                  $resultarray = $this->Md_database->insertData($table1, $insertData);
    //                  $type_id = $this->db->insert_id(); 
                                     
                                    
    //         }
            
    //          $resultarray = array('error_code' => '1','message' => ' cerificate send successfully');
    //                 echo json_encode($resultarray);
    //                 exit(); 
                         
    //       }else {
    //           $resultarray = array('error_code' => '3', 'message' => 'from_uid or to_user_id or certificate   is empty');
    //                 echo json_encode($resultarray);
    //                 exit();                       
    //       }

    // }
    public function blockStatus(){
        $uid = !empty($this->input->post('from_uid')) ? $this->input->post('from_uid') : '';
        $user_id = !empty($this->input->post('to_user_id')) ? $this->input->post('to_user_id') : '';
        $block_status = !empty($this->input->post('block_status')) ? $this->input->post('block_status') : '';//2=block, 1=unblock
        if (!empty($uid) || !empty($user_id) || !empty($block_status)){
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
            $col = array('pk_id','block_status');
            $checkBlockStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');        
            if (!empty($block_status)&&$block_status==2){
                //Block
                //till not chat but block user
                 if(empty($checkBlockStatus)){
                    $table ="chat";
                    $insert_data = array(
                       'from_uid'=> $uid ,
                       'to_user_id'=>$user_id ,              
                       'block_status'=> $uid,
                       'createdBy' => $uid,
                       'createdDate' => date('Y-m-d H:i:s'),                
                       'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                }
                //already chat update block status
                if($checkBlockStatus[0]['block_status'] ==1){
                    $table ="chat";
                    $update_data = array(
                        'block_status' =>$uid,                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                    );                          
                    $condition = array();   
                    $this->db->group_start();      
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }elseif ($checkBlockStatus[0]['block_status'] ==$user_id){
                    $table ="chat";
                    $update_data = array(
                        'block_status' =>2,                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                    );                          
                    $condition = array();   
                    $this->db->group_start();      
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }
                $resultarray = array('error_code' => '1','message' => 'User has been blocked');
                echo json_encode($resultarray);
                exit();
            }else{
                //UnBlock
                if ($checkBlockStatus[0]['block_status']==2) {
                    $table ="chat";
                    $update_data = array(                                              
                        'block_status' =>$user_id,                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                    );                          
                    $condition = array();   
                    $this->db->group_start();     
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                              
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }elseif($checkBlockStatus[0]['block_status']==$uid){
                    $table ="chat";
                    $update_data = array(                                              
                        'block_status' =>'1',                       
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                    );                          
                    $condition = array();   
                    $this->db->group_start();     
                    $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                    $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                    $this->db->group_end();
                              
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                }
                $resultarray = array('error_code' => '1','message' => 'user has been unblocked ');
                echo json_encode($resultarray);
                exit();
            }                                                                   
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'from_uid or to_user_id or block_status is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }
    public function blockUserList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        if (!empty($uid)){
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
            $orderby = 'chat.pk_id asc';
            $condition = array('block_status'=>$uid,'from_uid'=>$uid);
            $this->db->distinct();
            $this->db->join('user', 'user.pk_id = chat.to_user_id');
            $col = array('to_user_id as id','img');
            $blockuserList1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            $table = "chat";
            $orderby = 'chat.pk_id asc';
            $condition = array('block_status'=>$uid,'to_user_id'=>$uid);
            $this->db->distinct();
            $this->db->join('user', 'user.pk_id = chat.to_user_id');
            $col = array('from_uid as id');
            $blockuserList2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $array_merge=array();
            $b=array();
            $array_merge =array_merge($blockuserList1,$blockuserList2);
            $array_column=array_column($array_merge,'id');
            $array_unique= array_unique($array_column);
            $row=array();
            $final_array=array();
            foreach ( $array_unique as $key => $value) {
                $uid = $value;
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id'=>$uid);
                $this->db->distinct();
                $col = array('name','img','pk_id');
                $uname= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $final_array['name']=$uname[0]['name'];
                $final_array['img']=$uname[0]['img'];
                $final_array['pk_id']=$uname[0]['pk_id'];
                $List[]=$final_array;
            }
            $empty=array();
            $resultarray = array('error_code' => '1','message' => 'User block List','blockuserList'=>!empty($List)?$List:$empty,'img_path' => base_url().'uploads/users/');
            echo json_encode($resultarray);
            exit(); 
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }

    public function createGroup(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $subject = !empty($this->input->post('subject')) ? $this->input->post('subject') : '';
        $profile_img = !empty($this->input->post('profile_img')) ? $this->input->post('profile_img') : '';
         // $user_id='[{"user_id":"53"},{"user_id":"49"}]';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $users = json_decode($user_id);
        // print_r($users);die();

        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }   
            if (!empty($subject)|| !empty($users)) { 
                $photoDoc = "";
                if (!empty($_FILES['profile_img']['name'])){
                    $rename_name = uniqid(); //get file extension:
                    $arr_file_info = pathinfo($_FILES['profile_img']['name']);
                    $file_extension = $arr_file_info['extension'];
                    $newname = $rename_name . '.' . $file_extension;
                    // print_r($newname);die();
                    $old_name = $_FILES['profile_img']['name'];
                    // print_r($old_name);die();
                    $path = "uploads/chat/group_chat";
                    if (!is_dir($path)){
                        mkdir($path, 0777, true);
                    }
                    $upload_type = "jpg|png|jpeg";
                    $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "profile_img", "", $newname);  
                }
                $table = "chat_group";
                $insert_data = array(                  
                   'subject'=> $subject,                                                
                   'profile_img'=> $photoDoc,                                           
                   'createdBy' => $uid,
                   'createdDate' => date('Y-m-d H:i:s'),                
                   'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
                );
                $resultarray = $this->Md_database->insertData($table, $insert_data);
                $group_id = $this->db->insert_id();
                foreach ($users as $key => $value){
                    $user_id= $value->user_id;
                    $table = "chat_group_members";
                    $insert_data = array(
                      'fk_group_id'=> $group_id,                 
                      'fk_user_id'=> $user_id,                                           
                      'createdBy' => $uid,
                      'createdDate' => date('Y-m-d H:i:s'),                
                      'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);


                    $table = "privileges_notifications";
                    $select = "notifications,chat_notification";
                    $this->db->where('fk_uid',$user_id);
                    $this->db->order_by('pk_id','ASC');
                    $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                    $notification=($chechprivilege[0]['notifications']);
                    $chat_notification=($chechprivilege[0]['chat_notification']);
                    
                    if ($notification=='1') {
                        if ($user_id != $uid) {
                            $table = "user";
                            $select = "token,user.pk_id,name";
                            $this->db->where('pk_id',$user_id);
                            $this->db->order_by('user.pk_id','ASC');
                            $this->db->distinct();
                            $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                            $target=$order_token[0]['token'];
                        
                        
                            $table = "user";
                            $select = "user.pk_id,name";
                            $this->db->where('pk_id',$uid);
                            $this->db->order_by('user.pk_id','ASC');
                            $this->db->distinct();
                            $order_token2 = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                            $name=$order_token2[0]['name'];

                            $message = 'You have been added to '.$subject.' by '.$name;

                            if(!empty($message)){
                                $resultarray = array('message' => $message,'from_uid'=>$uid,'to_user_id'=>$user_id ,'redirect_type' =>'chta_group_create','subject'=>'You have a new message from '.$subject.' group');
                                    
                                $this->Md_database->sendPushNotification($resultarray,$target);

                                //store into database typewise
                                $table = "custom_notification";
                                $insert_data = array(
                                    'from_uid'=>$uid,
                                    'to_user_id'=>$user_id,
                                    'redirect_type' => 'chta_group_create',
                                    'subject' => 'You have new message from '.$subject.' group',
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
                $resultarray = array('error_code' => '1', 'message' => 'Created group Successfully' ,'group_id'=>$group_id);
                echo json_encode($resultarray);
                exit(); 
            }else {
                $resultarray = array('error_code' => '2', 'message' => 'Subject or users is empty');
                echo json_encode($resultarray);
                exit();                               
            }                                                               
        }else {
          $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
          echo json_encode($resultarray);
          exit();                               
        }
    }

    public function groupList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';

        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $final_array=array();           
            $table = "chat_group";
            $col = array('chat_group.pk_id','subject','profile_img');
            $this->db->limit($limit,$offset);
            $this->db->group_by('chat_group.pk_id');
            $this->db->where('chat_group.status','1');
            $this->db->join('chat_group_members','chat_group.pk_id = chat_group_members.fk_group_id','LEFT');        
            $this->db->where('chat_group_members.fk_user_id',$uid);
            $this->db->where('chat_group_members.status','1');
            $groupList = $this->Md_database->getData($table, $col,'','','');

            $array_pass=array();
            foreach ($groupList as $key => $value){
                $id= $value['pk_id'];

                $table = "chat_group_members";
                $col = array('clear_chat_date');
                $orderby = 'pk_id DESC';
                $this->db->where("fk_group_id",$id);            
                $this->db->where('fk_user_id',$uid);  
                $chat_clear_date = $this->Md_database->getData($table, $col,'','','');
                $clearChatDate=$chat_clear_date[0]['clear_chat_date'];
              
                $table = "chat";
                $col = array('chat.pk_id','message','to_user_id','from_uid','user.name','image','chat.createdDate');
                $orderby = 'chat.pk_id DESC';
                $this->db->order_by("pk_id","desc");
                $this->db->where('individual_group_status','2');              
                $this->db->where("to_user_id",$id);   
                $this->db->join('user','user.pk_id = chat.from_uid'); 
                $msg = $this->Md_database->getData($table, $col,'','','');
                $date=!empty($msg[0]['createdDate'])?$msg[0]['createdDate']:'';

                if (!empty($clearChatDate) && $date < $clearChatDate){
                    $value['message']='';
                    $value['name']='';
                    $value['image']='';
                    $value['createdDate']='';
                    $value['createdtime']=''; 
                }else{               
                    $timeFormate=date("h.i A",strtotime($date));
                    $dateFormate=date("d-m-Y",strtotime($date));
                    $value['createdDate']=$dateFormate;
                    $value['createdtime']=$timeFormate;
                    if (!empty($msg[0]['message'])) {
                        $value['message']=(!empty($msg[0]['message']))?$msg[0]['message']:'';
                    }
                    if (empty($msg[0]['message']) && !empty($msg[0]['image'])) { 
                        $icon= json_decode('"\ud83d\udcf7"');
                        $value['message']=$icon.' Photo';
                    }elseif (empty($msg[0]['message']) && empty($msg[0]['image'])) {
                        $value['message']='';
                        $value['createdDate']="";
                        $value['createdtime']="";
                    }                    
                    $value['name']=(!empty($msg[0]['name']))?$msg[0]['name']:'';
                }  
                $final_array[]=$value;
            }
            $array_pass=$final_array;
            $resultarray = array('error_code' =>'1','message' => 'Group List','group_list'=>$array_pass,'path' => base_url().'uploads/chat/group_chat/');
            echo json_encode($resultarray);
            exit();                                                       
        }else {
          $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
          echo json_encode($resultarray);
          exit();                               
        }
    }

    public function groupLeave(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            if (!empty($group_id)) {                
                $table = "chat_group_members";
                $update_data = array(                                              
                    'status' =>'3',                       
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                );                          
                $condition = array('fk_user_id' =>$uid,'fk_group_id' =>$group_id);
                $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                $resultarray = array('error_code' =>'1','message' => 'Group Leave Successfully');
                 echo json_encode($resultarray);
                exit();                                                       
            }else{
               $resultarray = array('error_code' =>'2','message' => 'Group Id is empty');
                 echo json_encode($resultarray);
                exit();
            }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }
    public function groupDelete(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            if (!empty($group_id)){                
                $table = "chat_group";
                $update_data = array(                                              
                    'status' =>'3',                       
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                );                          
                $condition = array('pk_id' =>$group_id);
                $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                $resultarray = array('error_code' =>'1','message' => 'Group Delete Successfully');
                 echo json_encode($resultarray);
                exit();                                                       
            }else{
               $resultarray = array('error_code' =>'2','message' => 'Group Id is empty');
                 echo json_encode($resultarray);
                exit();
            }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }

    public function clearGropuChat(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            if (!empty($group_id)){                
                $table = "chat_group_members";
                $update_data = array(                                              
                    'clear_chat_date' =>date('Y-m-d H:i:s'),                       
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                );                          
                $condition = array('fk_group_id' =>$group_id,'fk_user_id' =>$uid);
                $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                $resultarray = array('error_code' =>'1','message' => 'Group message Clear Successfully');
                echo json_encode($resultarray);
                exit();                                                       
            }else{
                $resultarray = array('error_code' =>'2','message' => 'Group Id is empty');
                echo json_encode($resultarray);
                exit();
            }
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }

    public function userList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            if (!empty($group_id)){
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
                            $condition = array('request_status' => '1','uid'=>$uid);
                            $this->db->join('user','user.pk_id = friends.user_id');
                            $this->db->distinct();
                            $col = array('friends.user_id as id','user.name','user.img');                    
                            $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        if($value['user_id']==$uid){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','user_id'=>$uid);
                            $this->db->join('user','user.pk_id = friends.uid');
                            $this->db->distinct();
                            $col = array('uid as id','user.name','user.img');
                            $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        $friendList=array();
                        $friendList=array_merge($List1,$List2);
                    } 
                $userList=array();

                $table = "chat_group_members";
                $orderby = 'pk_id asc';
                $condition = array('fk_group_id'=>$group_id,'status'=>'1');                
                $this->db->distinct();
                $col = array('fk_user_id','pk_id');

                $group_members = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                //Group Admin
                $admin_id=!empty($group_members[0]['fk_user_id'])?$group_members[0]['fk_user_id']:'';
                if (!empty($admin_id)){
                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id'=>$admin_id);                
                    $this->db->distinct();
                    $col = array('name','pk_id');
                    $admin_name = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                }else{
                    $admin_name=array();
                }
                
                //add status alredy in group(1) or not(2)              
                foreach ($friendList as $key => $val1){
                    $val1['status']=2;
                    foreach ($group_members as $key => $val2){
                        if ($val1['id']==$val2['fk_user_id']){
                          // print_r("hii");
                            $val1['status']=1;
                            $val1['sequence']=$val2['pk_id'];
                        }
                    }
                    $userList[]=$val1;
                }
                $table = "chat_group";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','pk_id' => $group_id);
                $this->db->limit($limit, $offset);
                $col = array('subject','profile_img','pk_id');
                $group_details = $this->Md_database->getData($table, $col, $condition, $orderby,'');
            }else{
                $table = "friends";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','request_status'=>'1');
                $col = array('user_id','uid');
                $this->db->distinct();
                $this->db->limit($limit, $offset);
                $this->db->group_start();
                $this->db->where('uid',$uid);
                $this->db->or_where('user_id', $uid); 
                $this->db->group_end();
                $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $List2 =array();
                $List1 =array();
                $friendList=array();
                $admin_name=array();
                $userList=array();
             
                foreach ($List as $key => $value){
                    if ($value['uid']==$uid){
                        $table = "friends";
                        $orderby = 'friends.pk_id asc';
                        $condition = array('request_status' => '1','uid'=>$uid,'user.status'=>1);
                        $this->db->join('user','user.pk_id = friends.user_id');
                        $this->db->limit($limit, $offset);
                        $this->db->distinct();
                        $col = array('friends.user_id as id','user.name','user.img');          
                        $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    }
                    if($value['user_id']==$uid){
                        $table = "friends";
                        $orderby = 'friends.pk_id asc';
                        $condition = array('request_status' => '1','user_id'=>$uid,'user.status'=>1);
                        $this->db->join('user','user.pk_id = friends.uid');
                        $this->db->limit($limit, $offset);
                        $this->db->distinct();
                        $col = array('uid as id','user.name','user.img');
                        $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    }
                    $userList=array_merge($List1,$List2);
                }                   
            }

            $resultarray = array('error_code' => '1', 'message' => 'User List','admin'=>!empty($admin_name[0]['name'])?$admin_name[0]['name']:'','user_list'=>$userList,'img_path' => base_url().'uploads/users/','group_subject'=>!empty($group_details[0]['subject'])?$group_details[0]['subject']:'','profile_img'=>!empty($group_details[0]['profile_img'])?$group_details[0]['profile_img']:'','group_profile_path' => base_url().'uploads/chat/group_chat/');
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'uid is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }
    public function deleteChatUser(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $id = !empty($this->input->post('users_id')) ? $this->input->post('users_id') : '';
        $users_id=json_decode($id);

        if (!empty($uid) && !empty($users_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            foreach ($users_id as $key => $val){
                $user_id=$val->id;
                $table = "chat";
                $orderby = 'pk_id desc';
                $condition = array();
                $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                $col = array('pk_id','delete_status','message','from_uid','status');
                $checkDeleteStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            
                foreach ($checkDeleteStatus as $key => $value){
                    $update_data=array();
                    if ($value['status']=='1'){
                        $update_data = array(
                            'status' =>$uid,                       
                            'updatedBy' => $uid,
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                        );                          
                        $condition = array('status' =>'1');
                        $condition['individual_group_status']='1';  
                        $table = "chat";
                        $this->db->group_start();                          
                        $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                        $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                        $this->db->group_end();
                        $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                    }elseif ($value['status']==$uid) {
                        $update_data = array(                                              
                            'status' =>$uid,                       
                            'updatedBy' => $uid,
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']              
                        );                          
                        $condition = array('status' =>$uid);
                        $condition['individual_group_status']='1';  
                        $table = "chat";
                        $this->db->group_start();                          
                        $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                        $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                        $this->db->group_end();
                        $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                    }elseif ($value['status']==$user_id) {
                        $update_data = array(  
                            'status' =>'2',                       
                            'updatedBy' => $uid,
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $condition['individual_group_status']='1';
                        $condition = array('status' =>$user_id);
                        $table = "chat";
                        $this->db->group_start();                          
                        $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                        $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                        $this->db->group_end();
                        $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                    }elseif($value['status']=='2') {
                        $update_data = array(  
                            'status' =>'2',                       
                            'updatedBy' => $uid,
                            'updatedDate' => date('Y-m-d H:i:s'),
                            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                        );                          
                        $condition = array('status' =>'2');
                        $condition['individual_group_status']='1';  
                        $table = "chat";
                        $this->db->group_start();                          
                        $this->db->where("(from_uid=$uid AND to_user_id=$user_id)");
                        $this->db->or_where("(from_uid=$user_id AND to_user_id=$uid)");
                        $this->db->group_end();
                        $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                    }
                }
            }
            $resultarray = array('error_code' =>'1','message' => 'Chat user deleted Successfully');
            echo json_encode($resultarray);
            exit(); 
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'uid or users_id is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }
    public function groupMembersList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';

        if (!empty($uid) && !empty($group_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "chat_group";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','pk_id' => $group_id);
            $this->db->limit($limit, $offset);
            $col = array('subject','profile_img');
            $group_details = $this->Md_database->getData($table, $col, $condition, $orderby,'');


            $table = "chat_group_members";
            $orderby = 'chat_group_members.pk_id asc';
            $condition = array('chat_group_members.status' => '1','fk_group_id' => $group_id,'user.status' => '1');
            $this->db->join('user', 'user.pk_id = chat_group_members.fk_user_id');
            $this->db->limit($limit, $offset);
            $col = array('chat_group_members.pk_id','fk_user_id','img','name');
            $group_members = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            $new_array=array();
            if (!empty($group_members)){
                foreach ($group_members as $key => $value){
                    $id = $value['fk_user_id'];
                    if ($key == 0){
                        $value['admin']='1';
                    }else{
                        $value['admin']='2'; 
                    }
                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id' => $id);
                    $col = array('pk_id','latitude','longitude');
                    $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');

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

                    $table = "friends";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('request_status');
                    $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';
                         

                    // $table = "user_profile_detail";
                    // $orderby = 'pk_id asc';
                    // $condition = array('status' => '1','user_id' => $id,'usertype'=>'1');
                    // $col = array('pk_id,user_id,usertype,visting_fees,skill,address');
                    // $coach = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    // $value['visting_fees'] =!empty($coach[0]['visting_fees'])?$coach[0]['visting_fees']:'';
                    // $value['skill'] =!empty($coach[0]['skill'])?$coach[0]['skill']:'';

                    $table = "profile_type";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','user_id' => $id);
                    $col = array('usertype');
                    $usertype = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $table = "privileges_notifications";
                    $orderby = 'pk_id DESC';
                    $condition = array('fk_uid' => $id);
                    $col = array('display_profile','available','notifications','chat_notification','location');
                    $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                    $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                    $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';

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
            
            $resultarray = array('error_code' => '1', 'message' => 'Group Member List','group_members_list'=>$new_array,'img_path' => base_url().'uploads/users/','group_profile'=>!empty($group_details[0]['profile_img'])?$group_details[0]['profile_img']:'','group_subject'=>!empty($group_details[0]['subject'])?$group_details[0]['subject']:'','group_profile_path' => base_url().'uploads/chat/group_chat/');
            echo json_encode($resultarray);
            exit(); 

        }else{
            $resultarray = array('error_code' => '2', 'message' => 'uid or group_id is empty');
            echo json_encode($resultarray);
            exit();                               
        }

    }

    public function updateGroupMembers(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $group_id = !empty($this->input->post('group_id')) ? $this->input->post('group_id') : '';
        $group_subject = !empty($this->input->post('group_subject')) ? $this->input->post('group_subject') : '';
        $group_profile = !empty($this->input->post('group_profile')) ? $this->input->post('group_profile') : '';
        //user_id = [{"user_id":"101","add_status":"2"}];
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $users_id=json_decode($user_id);

        if (!empty($uid) && !empty($user_id) && !empty($group_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            } 
            if (!empty($group_subject)) {
                $update_data = array(
                    'subject' =>$group_subject,                       
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                );                          
                $condition = array('pk_id' =>$group_id); 
                $table = "chat_group";
                $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
            }
            $photoDoc = "";
            if (!empty($_FILES['group_profile']['name'])){
                $rename_name = uniqid(); //get file extension:
                $arr_file_info = pathinfo($_FILES['group_profile']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                // print_r($newname);die();
                $old_name = $_FILES['group_profile']['name'];
                // print_r($old_name);die();
                $path = "uploads/chat/group_chat";
                if (!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                $upload_type = "jpg|png|jpeg";
                $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "group_profile", "", $newname);  

                $update_data = array(
                    'profile_img' =>$photoDoc,                       
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                );                          
                $condition = array('pk_id' =>$group_id); 
                $table = "chat_group";
                $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
            }
            // $table = "chat_group_members";                          
            // $condition = array('fk_group_id' =>$group_id); 
            // $resultarray = $this->Md_database->deleteData($table, $condition);

            foreach ($users_id as $key => $value){
                if ($value->add_status==1){
                    $user_id=$value->user_id;

                    $table = "chat_group_members";
                    $orderby = 'pk_id asc';
                    $condition = array('fk_user_id' => $user_id,'fk_group_id'=>$group_id,'status'=>1);
                    $col = array('pk_id');
                    $checkExistMember = $this->Md_database->getData($table, $col, $condition, $orderby,'');

                    if (empty($checkExistMember)) {
                        $table = "chat_group_members";
                        $insert_data = array(
                            'fk_group_id'=> $group_id,                 
                            'fk_user_id'=> $user_id,                                           
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR'] 
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                    

                        $table = "privileges_notifications";
                        $select = "notifications,chat_notification";
                        $this->db->where('fk_uid',$user_id);
                        $this->db->order_by('pk_id','ASC');
                        $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                        $notification=($chechprivilege[0]['notifications']);
                        $chat_notification=($chechprivilege[0]['chat_notification']);
                        
                        if ($notification=='1') {
                            if ($user_id != $uid) {
                                $table = "user";
                                $select = "token,user.pk_id,name";
                                $this->db->where('pk_id',$user_id);
                                $this->db->order_by('user.pk_id','ASC');
                                $this->db->distinct();
                                $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                                $target=$order_token[0]['token'];
                            
                            
                                $table = "user";
                                $select = "user.pk_id,name";
                                $this->db->where('pk_id',$uid);
                                $this->db->order_by('user.pk_id','ASC');
                                $this->db->distinct();
                                $order_token2 = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                                $name=$order_token2[0]['name'];
                                $message = $name. ' added you in '.$group_subject.' group';
                                if(!empty($message)){
                                    $resultarray = array('message' => $message,'from_uid'=>$uid,'to_user_id'=>$user_id ,'redirect_type' =>'chat_group_create','subject'=>'You have a new message from '.$group_subject.' group');
                                        
                                    $this->Md_database->sendPushNotification($resultarray,$target);

                                    //store into database typewise
                                    $table = "custom_notification";
                                    $insert_data = array(
                                        'from_uid'=>$uid,
                                        'to_user_id'=>$user_id,
                                        'redirect_type' => 'chat_group_create',
                                        'subject' => 'You have new message from '.$group_subject.' group',
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
                }elseif($value->add_status==2){
                    $user_id=$value->user_id;
                    $table = "chat_group_members";                          
                    $condition = array('fk_group_id' =>$group_id);
                    $condition['fk_user_id']=$user_id;  
                    $resultarray = $this->Md_database->deleteData($table, $condition);
                }                
            }
            $resultarray = array('error_code' =>'1','message' => 'Group members updated Successfully','group_id'=>$group_id);
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'uid or user_id or group_id is empty');
            echo json_encode($resultarray);
            exit();                               
        }
    }
}
