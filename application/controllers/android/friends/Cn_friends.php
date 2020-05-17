<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_friends extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    public function deleteFriends(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        // $user_id='[{"id":"59"}]';
        $del_id = json_decode($user_id);
        if (!empty($uid )&&!empty($user_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid,'pk_id'=>$user_id);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            } 
            foreach($del_id as $key => $value){
                $id=$value->id;
                $table = "friends";
                $updated_data = array(                                
                    'request_status'=>'2',
                    'updatedBy' => $uid,
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                );
                $condition = array("status" => '1'); 
                $this->db->where("(uid=$uid AND user_id=$id)");
                $this->db->or_where("(uid=$id AND user_id=$uid)");   
                $result = $this->Md_database->updateData($table, $updated_data,$condition);
            }
            $resultarray = array('error_code' => '1', 'message' => 'Friend delete Successfully');
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or user_id is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
   
    public function sendFriendRequest(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        if (!empty($uid )&&!empty($user_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid,'pk_id'=>$user_id);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            } 
            $table = "friends";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1');
            $this->db->where("request_status<>",'2');
            $this->db->group_start();
            $this->db->where("(uid=$user_id AND user_id=$uid)");
            $this->db->group_end();
            $col = array('pk_id');
            $checkRequest = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkRequest)){
                $resultarray = array('error_code' => '2', 'message' => 'You have already got request from this user');
                echo json_encode($resultarray);            
                exit();
            } 
            $table = "friends";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1');
            $this->db->where("request_status<>",'2');
            $this->db->where("(uid=$uid AND user_id=$user_id)");          
            $col = array('pk_id');
            $checkRequest = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkRequest)){
                $resultarray = array('error_code' => '2', 'message' => 'Already sent request');
                echo json_encode($resultarray);            
                exit();
            } 
            $table = "friends";
            $insert_data = array(
                'uid'=> $uid,
                'user_id'=> $user_id,
                'createdBy' => $uid,
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
            );
            $resultarray = $this->Md_database->insertData($table,$insert_data);

            //Push notification
            $table = "user";
            $select = "name,pk_id";
            $this->db->where('pk_id',$uid);
            $this->db->order_by('user.pk_id','ASC');
            $this->db->distinct();
            $userName = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                  
            $table = "user";
            $select = "token,user.pk_id";
            $this->db->where('pk_id',$user_id);
            $this->db->order_by('user.pk_id','ASC');
            $this->db->distinct();
            $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                
            $target=$order_token[0]['token'];
            $resultarray = array('message' => $userName[0]['name'].' has sent you a friend request', 'redirect_type' =>'friend_request', 'subject' =>'Friend Request');

            $table = "privileges_notifications";
            $select = "notifications,chat_notification";
            $this->db->where('fk_uid',$user_id);
            $this->db->order_by('pk_id','ASC');
            $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
            $notification=($chechprivilege[0]['notifications']);
            if ($notification == '1') { 
                $this->Md_database->sendPushNotification($resultarray,$target);
            }

            //store into database typewise
            $table = "custom_notification"; 
            $insert_data = array(
                  'from_uid'=>$uid,
                  'to_user_id'=>$user_id,
                  'redirect_type' => 'friend_request',
                  'subject' => 'Friend Request',
                  'message' =>  $userName[0]['name'].' has sent you a friend request',
                  'status' => '1',
                  'created_by ' => $uid,
                  'created_date' => date('Y-m-d H:i:s'),
                  'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
            );
            $result = $this->Md_database->insertData($table, $insert_data);
            $resultarray = array('error_code' => '1', 'message' => 'Request sent Successfully');
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or user_id is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
    public function acceptRejectFriendRequest(){
        $request_response = !empty($this->input->post('request_response')) ? $this->input->post('request_response') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        if (!empty($uid ) && !empty($user_id ) && !empty($request_response )){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);            
                exit();
            }       
            //if request send from one user
            $table = "friends";
            $updated_data = array(                                
                'request_status'=> $request_response,
                'updatedBy' => $uid,
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
            );
            $condition = array("status" => '1','user_id'=> $uid,'uid'=> $user_id);    
            $result = $this->Md_database->updateData($table, $updated_data,$condition); 

            //Push notification if only friend request accept..
            if ($request_response=='1') {
                $table = "user";
                $select = "name,pk_id";
                $this->db->where('pk_id',$uid);
                $this->db->order_by('user.pk_id','ASC');
                $this->db->distinct();
                $userName = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                
                $table = "user";
                $select = "token,user.pk_id";
                $this->db->where('pk_id',$user_id);
                $this->db->order_by('user.pk_id','ASC');
                $this->db->distinct();
                $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                $target=$order_token[0]['token'];
                $resultarray1 = array('message' => $userName[0]['name'].' accepted your friend request', 'redirect_type' => 'friend_accept', 'subject' =>'Friend request');
                     
                $table = "privileges_notifications";
                $select = "notifications,chat_notification";
                $this->db->where('fk_uid',$user_id);
                $this->db->order_by('pk_id','ASC');
                $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                $notification=($chechprivilege[0]['notifications']);

                if ($notification == '1') {
                    $this->Md_database->sendPushNotification($resultarray1,$target);
                }
                $table = "custom_notification"; 
                $insert_data = array(
                    'from_uid'=>$uid,
                    'to_user_id'=>$user_id,
                    'redirect_type' => 'friend_accept',
                    'subject' => 'Friend Accept',
                    'message' =>  $userName[0]['name'].' accepted your friend request',
                    'status' => '1',
                    'created_by ' => $uid,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                );
                $result = $this->Md_database->insertData($table, $insert_data);
            }
            $resultarray = array('error_code' => '1', 'message' => 'Request response insert Successfully');
            echo json_encode($resultarray);
            exit(); 
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid or user_id or request_response is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
    public function favouriteStatus(){
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        if (!empty($uid ) && !empty($user_id )) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);             
                exit();
            }  
            $table = "friends_favourite";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','uid' => $uid,'user_id' => $user_id);
            $col = array('pk_id');
            $checkUserExist = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            if (!empty($checkUserExist)){
                $table = "friends_favourite";
                $orderby = 'pk_id asc';
                $condition = array('uid' => $uid,'user_id' => $user_id);
                $col = array('pk_id,favourite_status');
                $checkFavStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                // print_r($checkFavStatus[0]['favourite_status']);
                if (($checkFavStatus[0]['favourite_status'])=='1') {
                    $table = "friends_favourite";
                    $updated_data = array(                                
                        'favourite_status'=>'2',
                        'uid'=> $uid,
                        'user_id'=> $user_id,
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                    );
                    $condition = array("status" => '1','user_id'=> $user_id,'uid'=> $uid);
                    $result = $this->Md_database->updateData($table, $updated_data,$condition);
                }elseif (($checkFavStatus[0]['favourite_status'])=='2') {
                    $table = "friends_favourite";
                    $updated_data = array(                                
                        'favourite_status'=>'1',
                        'uid'=> $uid,
                        'user_id'=> $user_id,
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                    );
                    $condition = array("status" => '1','user_id'=> $user_id,'uid'=> $uid);
                             
                    $result = $this->Md_database->updateData($table, $updated_data,$condition);

                    // $table = "friends_favourite";
                    // $orderby = 'pk_id asc';
                    // $condition = array('uid' => $uid,'user_id' => $user_id);
                    // $col = array('pk_id,favourite_status');
                    // $checkstatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    // if (($checkstatus[0]['favourite_status'])=='1') {
                    //     $table = "user";
                    //     $select = "name,pk_id";
                    //     $this->db->where('pk_id',$uid);
                    //     $this->db->order_by('user.pk_id','ASC');
                    //     $this->db->distinct();
                    //     $userName = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');

                    //     $table = "user";
                    //     $select = "token,user.pk_id";
                    //     $this->db->where('pk_id',$user_id);
                    //     $this->db->order_by('user.pk_id','ASC');
                    //     $this->db->distinct();
                    //     $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                                  
                    //     $target=$order_token[0]['token'];
                    //     $resultarray1 = array('message' => $userName[0]['name'].' added you as favourite', 'redirect_type' => 'favourite', 'subject' =>'Add favourite');

                    //     $table = "privileges_notifications";
                    //     $select = "notifications,chat_notification";
                    //     $this->db->where('fk_uid',$user_id);
                    //     $this->db->order_by('pk_id','ASC');
                    //     $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                    //     $notification=($chechprivilege[0]['notifications']);
                    //     if ($notification == '1') {
                    //         $this->Md_database->sendPushNotification($resultarray1,$target);
                    //     }
                    //     $table = "custom_notification"; 
                    //     $insert_data = array(
                    //         'from_uid'=>$uid,
                    //         'to_user_id'=>$user_id,
                    //         'redirect_type' => 'favourite',
                    //         'subject' => 'Add favourite',
                    //         'message' =>$userName[0]['name'].' added you as favourite',
                    //         'status' => '1',
                    //         'created_by ' => $uid,
                    //         'created_date' => date('Y-m-d H:i:s'),
                    //         'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                    //     );
                    //     $result = $this->Md_database->insertData($table, $insert_data);
                    // }
                }
                $resultarray = array('error_code' => '1', 'message' => 'favourite status update Successfully');
                echo json_encode($resultarray);
                exit();
            }else{
                $table = "friends_favourite";
                $insert_data = array(                                
                    'favourite_status'=> '1',
                    'uid'=> $uid,
                    'user_id'=> $user_id,
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']               
                );                  
                $resultarray = $this->Md_database->insertData($table,$insert_data);

                //Push notification if only Add favourite..
                // $table = "friends_favourite";
                // $orderby = 'pk_id asc';
                // $condition = array('uid' => $uid,'user_id' => $user_id);
                // $col = array('pk_id,favourite_status');
                // $checkstatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                // if (($checkstatus[0]['favourite_status'])=='1') {
                //     $table = "user";
                //     $select = "name,pk_id";
                //     $this->db->where('pk_id',$uid);
                //     $this->db->order_by('user.pk_id','ASC');
                //     $this->db->distinct();
                //     $userName = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');

                //     $table = "user";
                //     $select = "token,user.pk_id";
                //     $this->db->where('pk_id',$user_id);
                //     $this->db->order_by('user.pk_id','ASC');
                //     $this->db->distinct();
                //     $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                    
                //     $target=$order_token[0]['token'];
                //     $resultarray1 = array('message' => $userName[0]['name'].' added you as favourite', 'redirect_type' => 'favourite', 'subject' =>'Add favourite');
                //     $table = "privileges_notifications";
                //     $select = "notifications,chat_notification";
                //     $this->db->where('fk_uid',$user_id);
                //     $this->db->order_by('pk_id','ASC');
                //     $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                //     $notification=($chechprivilege[0]['notifications']);
                //     if ($notification == '1') {
                //         $this->Md_database->sendPushNotification($resultarray1,$target);
                //     }
                //     $table = "custom_notification"; 
                //     $insert_data = array(
                //         'from_uid'=>$uid,
                //         'to_user_id'=>$user_id,
                //         'redirect_type' => 'favourite',
                //         'subject' => 'Add favourite',
                //         'message' =>$userName[0]['name'].' added you as favourite',
                //         'status' => '1',
                //         'created_by ' => $uid,
                //         'created_date' => date('Y-m-d H:i:s'),
                //         'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                //     );
                //     $result = $this->Md_database->insertData($table, $insert_data);
                // }
                $resultarray = array('error_code' => '1', 'message' => 'favourite status insert Successfully');
                echo json_encode($resultarray);
                exit();
            }
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or user_id or favourite_status is empty');
            echo json_encode($resultarray);
            exit();                       
        }

    }
    public function friendList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
        // $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        // $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);             
                exit();
            }
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
                if ($value['uid']==$uid) {
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','uid'=>$uid,'user.status'=>1);
                    $this->db->join('user','user.pk_id = friends.user_id');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct();
                    if (!empty($search)) {
                        $this->db->where("user.name LIKE '%$search%'"); 
                    }  
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
                    if (!empty($search)) {
                        $this->db->where("user.name LIKE '%$search%'");  
                    }  
                    $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                    $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                $friendList=array();
                $friendList=array_merge($List1,$List2);
            }     
            $empty=array();
            $friends =!empty($friendList)?$friendList:$empty;
            $new_array=array();
            foreach ($friends as $key => $value){
                $uid2=$value['id'];
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $uid2);
                $col = array('pk_id','latitude','longitude','online_status','email','verifyEmail','doc_verify');
                $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['online_status']=$latlong[0]['online_status'];
                $value['email']=$latlong[0]['email'];


                if ($latlong[0]['doc_verify'] =='1' && ($latlong[0]['email'] == $latlong[0]['verifyEmail'])){
                    $value['verify_tick'] = '1';//yes
                }else{
                    $value['verify_tick'] = '2';//No
                }


                $table = "privileges_notifications";
                $orderby = 'pk_id DESC';
                $condition = array('fk_uid' => $uid2);
                $col = array('display_profile','available','notifications','chat_notification','location');
                $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';


                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $uid);
                $col = array('pk_id','latitude','longitude');
                $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                 
                $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';
                $value['distance']='0';

                if (!empty($longitudeFrom) && !empty($longitudeTo) && !empty($latitudeFrom) && !empty($latitudeTo) ) {
                    //Calculate distance from latitude and longitude
                    $theta = $longitudeFrom - $longitudeTo;
                    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    $distance = ($miles * 1.609344);
                    $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';
                }

                //Mutual Friends
                    $friendfriendList=array();
                    $table = "friends";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','request_status'=>'1');
                    $col = array('user_id','uid');
                    $this->db->distinct();
                    // $this->db->limit($limit, $offset);
                    $this->db->group_start();
                    $this->db->where('uid',$uid2);
                    $this->db->or_where('user_id', $uid2); 
                    $this->db->group_end();
                    $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $List2 =array();
                    $List1 =array();
                    foreach ($List as $key => $val) {
                        if ($val['uid']==$uid2){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','uid'=>$uid2,'user.status'=>1);
                            $this->db->join('user','user.pk_id = friends.user_id');
                            $this->db->join('city','city.pk_id = user.city');
                            $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                            $this->db->distinct(); 
                            $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                            $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        if($val['user_id']==$uid2){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','user_id'=>$uid2,'user.status'=>1);
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


                $table = "profile_type";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','user_id' => $uid2);
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

                $table = "friends_favourite";
                $orderby = '';
                $condition = array('user_id' => $uid2, 'uid' => $uid,);
                $col = array('favourite_status');
                $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
              
                if (!empty($favourite_status[0]['favourite_status']) && $favourite_status[0]['favourite_status']==1) {
                    $value['favourite_status'] ='1' ;
                }else{
                    $value['favourite_status'] ='2' ;
                } 
                $new_array[] = $value;
            }             
            $empty=array();
            $resultarray = array('error_code' => '1', 'message' => 'Friend List','friendList'=>!empty( $new_array)? $new_array:$empty,'profile_path' => base_url().'uploads/users/');
            echo json_encode($resultarray);
            exit();                                 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
    public function friendFavouriteList(){
      /***Save Friends***/
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
      // $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
      // $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
       if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
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


            $table = "friends_favourite";
            $orderby = 'friends_favourite.pk_id asc';
            $condition = array('friends_favourite.status' => '1','favourite_status'=>'1','uid'=>$uid,'user.status'=>'1');
            $col = array('friends_favourite.user_id as id','user.name','user.img','user.address','city.city_name'); 
            // $this->db->limit($limit, $offset);           
            $this->db->join('user','user.pk_id = friends_favourite.user_id');
            $this->db->join('city','city.pk_id = user.city');
            if (!empty($search)) {
                $this->db->where("user.name LIKE '%$search%'");   
            }
            $favouriteList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $empty=array();
            $friends =!empty($favouriteList)?$favouriteList:$empty;
            foreach ($friends as $key => $value) {
                $id=$value['id'];
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $id);
                $col = array('pk_id','latitude','longitude','online_status','email','verifyEmail','doc_verify');
                $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['online_status']=$latlong[0]['online_status'];
                $value['email']=$latlong[0]['email'];

                if ($latlong[0]['doc_verify'] =='1' && ($latlong[0]['email'] == $latlong[0]['verifyEmail'])){
                    $value['verify_tick'] = '1';//yes
                }else{
                    $value['verify_tick'] = '2';//No
                }


                $table = "friends";
                $orderby = 'pk_id DESC';
                $condition = array('user_id' => $id,'uid'=>$uid);
                $col = array('request_status');
                $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';
                


                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $uid);
                $col = array('pk_id','latitude','longitude');
                $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';


                if (!empty($longitudeFrom) && !empty($longitudeTo) && !empty($latitudeFrom) && !empty($latitudeTo) ) {
                    //Calculate distance from latitude and longitude
                    $theta = $longitudeFrom - $longitudeTo;
                    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    $distance = ($miles * 1.609344);
                    $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';
                }



                $table = "privileges_notifications";
                $orderby = 'pk_id DESC';
                $condition = array('fk_uid' => $id);
                $col = array('display_profile','available','notifications','chat_notification','location');
                $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';



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
            $empty =array();            
            $resultarray = array('error_code' => '1', 'message' => 'Favourite List','favouriteList'=>!empty( $new_array)? $new_array:$empty,'img_path' => base_url().'uploads/users/');
            echo json_encode($resultarray);
            exit();                                 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }

    public function friendRequestList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
        // $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        // $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
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

            /*************************/
            $table = "friends";
            $orderby = 'friends.pk_id asc';
            $condition = array('user_id'=>$uid,'request_status'=>'3','user.status'=>'1');
            $col = array('friends.uid as id','user.name','user.address','city.city_name','user.img','');
            // $this->db->limit($limit, $offset);    
            if (!empty($search)) {
                $this->db->where("user.name LIKE '%$search%'");   
            }        
            $this->db->join('user','user.pk_id = friends.uid');
            $this->db->join('city','user.city=city.pk_id');
            $requestList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $empty=array();
            $friends =!empty($requestList)?$requestList:$empty;
            foreach ($friends as $key => $value) {
                $user_id=$value['id'];
                // $table = "user";
                // $orderby = 'pk_id asc';
                // $condition = array('pk_id' => $user_id);
                // $col = array('pk_id','latitude','longitude','online_status');
                // $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                // $value['online_status']=$latlong[0]['online_status'];

                 $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $user_id);
                $col = array('pk_id','latitude','longitude','online_status','email','verifyEmail','doc_verify');
                $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['online_status']=$latlong[0]['online_status'];
                $value['email']=$latlong[0]['email'];

                if ($latlong[0]['doc_verify'] =='1' && ($latlong[0]['email'] == $latlong[0]['verifyEmail'])){
                    $value['verify_tick'] = '1';//yes
                }else{
                    $value['verify_tick'] = '2';//No
                }


                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('pk_id' => $uid);
                $col = array('pk_id','latitude','longitude');
                $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';



                $table = "privileges_notifications";
                $orderby = 'pk_id DESC';
                $condition = array('fk_uid' => $user_id);
                $col = array('display_profile','available','notifications','chat_notification','location');
                $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';


                //Mutual Friends
                    $friendfriendList=array();
                    $table = "friends";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','request_status'=>'1');
                    $col = array('user_id','uid');
                    $this->db->distinct();
                    // $this->db->limit($limit, $offset);
                    $this->db->group_start();
                    $this->db->where('uid',$user_id);
                    $this->db->or_where('user_id', $user_id); 
                    $this->db->group_end();
                    $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $List2 =array();
                    $List1 =array();
                    foreach ($List as $key => $val) {
                        if ($val['uid']==$user_id){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','uid'=>$user_id,'user.status'=>1);
                            $this->db->join('user','user.pk_id = friends.user_id');
                            $this->db->join('city','city.pk_id = user.city');
                            $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                            $this->db->distinct(); 
                            $col = array('friends.user_id as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');                    
                            $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        }
                        if($val['user_id']==$user_id){
                            $table = "friends";
                            $orderby = 'friends.pk_id asc';
                            $condition = array('request_status' => '1','user_id'=>$user_id,'user.status'=>1);
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





                //Calculate distance from latitude and longitude
                $theta = $longitudeFrom - $longitudeTo;
                $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $distance = ($miles * 1.609344);
                $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';

                $table = "friends_favourite";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','uid'=>$uid,'user_id' => $user_id);
                $col = array('favourite_status');
                $fav_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $value['favourite_status']=!empty($fav_status[0]['favourite_status'])?$fav_status[0]['favourite_status']:'2';

                
                $table = "profile_type";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','user_id' => $user_id);
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
                                  
            $empty =array();              
            $resultarray = array('error_code' => '1', 'message' => 'Request List','requestList'=>!empty( $new_array)? $new_array:$empty,'img_path' => base_url().'uploads/users/');
            echo json_encode($resultarray);
            exit();                                 
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
    public function mutualFriends(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
        // $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        // $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2','pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);             
                exit();
            }
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
                $friendfriendList=array();
                $final_list=array();
                foreach ($friendList as $key => $val) {
                // $f[] = $val;
                // die();
                    $id = $val['id'];
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
                    foreach ($List as $key => $value) {
                        if ($value['uid']==$id){
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
                        if($value['user_id']==$id){
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
                            $mutual_array =array();
                            $single_array =array();
                            $mergeFriendList =array();
                         
                        if (!empty($friendfriendList) && !empty($friendList)){
                            foreach ($friendList as $key => $v) {
                                foreach ($friendfriendList as $key => $v2) {
                                    $fk_uid=$v['id'];
                                    $id=$v2['id'];
                                    if($id !=$fk_uid){
                                        array_push($single_array, $v);
                                    }
                                    elseif ($v2['id']==$v['id']) {
                                        if ($v['id'] != $val['id']) {
                                            array_push($mutual_array,$v);
                                        }
                                    }
                                }
                            }     
                        $final_list= $val;
                     print_r($final_list);
                     die();
                        }
                    }
                }
                
            $resultarray = array('error_code' => '3', 'message' => '','data'=>$final_list);
            echo json_encode($resultarray);
            exit();   
                // die();                           
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }

    }
}
