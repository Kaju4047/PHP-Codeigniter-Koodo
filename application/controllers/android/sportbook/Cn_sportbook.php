<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_sportbook extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('date');
    } 

    public function post(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $text = !empty($this->input->post('text')) ? $this->input->post('text') : '';
        $image = !empty($this->input->post('image')) ? $this->input->post('image') :'';
        if (!empty($uid)) {
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array( 'user.pk_id' => $uid);
            $this->db->join('city','user.city = city.pk_id');
            $col = array('user.pk_id','img as user_profile_image','name','city_name','state_name','user.status');
            $userProfileImg = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            $photoDoc = "";
            if (!empty($_FILES['image']['name'])){
                $rename_name = uniqid(); //get file extension:
                $arr_file_info = pathinfo($_FILES['image']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                 // print_r($newname);die();
                $old_name = $_FILES['image']['name'];
                // print_r($old_name);die();
                $path = "uploads/sportbook/post/";
                if (!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                $upload_type = "jpg|png|jpeg";
                $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname);
            }             
            $table = "sportbook";
            $insert_data = array(
                'fk_uid'=> $uid,
                'text'=> $text,
                'image'=> $photoDoc,
                'createdBy' => $uid,
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
            );
            $resultarray = $this->Md_database->insertData($table,$insert_data);
            $post_id = $this->db->insert_id(); 
            if (!empty($post_id)){
                //Get Like count
                $table = "sportbook_post_like";
                $orderby = 'pk_id asc';
                $condition = array('like_status' => '1', 'fk_post' => $post_id);
                $col = array('pk_id');
                $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $likeCount = !empty(count($like))?count($like):'0';
               
                if ($likeCount >= 1000) {
                  $likeCount=number_format(($likeCount / 1000), 1) .'K';
                }

                //Get Comment count
                $table = "sportbook_post_comment";
                $orderby = 'pk_id asc';
                $condition = array('fk_post' => $post_id);
                $col = array('pk_id');
                $comment= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $commentCount = !empty(count($comment))?count($comment):'0';
               
                if ($commentCount >= 1000){
                    $commentCount=number_format(($commentCount / 1000), 1) .'K';
                }           
            }
            if(!empty($post_id)){
                $resultarray = array('error_code' => '1', 'message' => 'Post Successfully Done ','pk_id'=>$post_id,'fk_uid'=>$uid,'text'=>$text,'post_image'=>$photoDoc,'name'=>!empty($userProfileImg[0]['name'])?$userProfileImg[0]['name']:'','user_profile_image'=>!empty($userProfileImg[0]['user_profile_image'])?$userProfileImg[0]['user_profile_image']:null,'city_name'=>!empty($userProfileImg[0]['city_name'])?$userProfileImg[0]['city_name']:'','state_name'=>!empty($userProfileImg[0]['state_name'])?$userProfileImg[0]['state_name']:'','status'=>!empty($userProfileImg[0]['status'])?$userProfileImg[0]['status']:'','likeCount'=>$likeCount,'commentCount'=>$commentCount,'profile_path' => base_url().'uploads/users/','post_path' => base_url().'uploads/sportbook/post/');
                echo json_encode($resultarray);
                exit();                       
            }  
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    
    public function postList(){    
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '0';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '0';
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : '0';
        $my_friends = !empty($this->input->post('my_friends')) ? $this->input->post('my_friends') : ''; //only for my friend post list   1-for my friend post list  empty for all post  
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
        if (!empty($uid)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $friendList =array();         
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
            $friendList =array();
            foreach ($List as $key => $value){
                if ($value['uid']==$uid){
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','uid'=>$uid);
                    $this->db->join('user','user.pk_id = friends.user_id');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct();
                    $col = array('friends.user_id as id','user.name','user.img as user_profile_image','user.address','city.city_name,privileges_notifications.available');                    
                    $List1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                if($value['user_id']==$uid){
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','user_id'=>$uid);
                    $this->db->join('user','user.pk_id = friends.uid');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct();
                    $col = array('uid as id','user.name','user.img as user_profile_image','user.address','city.city_name,privileges_notifications.available');
                    $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                $friendList=array_merge($List1,$List2);
            }
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1', 'pk_id' => $uid);
            $col = array('pk_id','img as user_profile_image');
            $userProfileImg = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $userProfile =  !empty($userProfileImg[0]['user_profile_image'])?$userProfileImg[0]['user_profile_image']:'';

            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('pk_id' => $uid);
            $col = array('pk_id','name','createdDate');
            $createdDate = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            $userregdate= $createdDate[0]['createdDate'];
            $postList=array();

            $table = "sportbook_report";
            $orderby = 'pk_id asc';
            $condition = array('fk_uid_given_by' => $uid);
            $col = array('pk_id','fk_uid_for','remove_post_of_report');
            $reportPostHideStatus = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // print_r($reportPostHideStatus);
            // die();

            $table = "sportbook";
            $orderby = 'sportbook.pk_id DESC';
            $condition = array('sportbook.status' => '1','user.status' => '1');
            if (!empty($search)) {
                $this->db->where("user.name LIKE '%$search%'");
            }
            if (!empty($city_id)){
                $this->db->where("user.city",$city_id);                
            }
            if (!empty($reportPostHideStatus[0]['fk_uid_for']) && $reportPostHideStatus[0]['remove_post_of_report'] == 1) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[0]['fk_uid_for']);                
            }elseif(!empty($reportPostHideStatus[1]['fk_uid_for']) && $reportPostHideStatus[1]['remove_post_of_report'] == 1 ) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[1]['fk_uid_for']);                
            }elseif (!empty($reportPostHideStatus[2]['fk_uid_for'])&& $reportPostHideStatus[2]['remove_post_of_report'] == 1) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[2]['fk_uid_for']);                
            }elseif (!empty($reportPostHideStatus[3]['fk_uid_for']) && $reportPostHideStatus[3]['remove_post_of_report'] == 1) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[3]['fk_uid_for']);                
            }elseif (!empty($reportPostHideStatus[4]['fk_uid_for'])&& $reportPostHideStatus[4]['remove_post_of_report'] == 1) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[4]['fk_uid_for']);                
            }elseif (!empty($reportPostHideStatus[5]['fk_uid_for'])&& $reportPostHideStatus[5]['remove_post_of_report'] == 1) {
                $this->db->where("sportbook.fk_uid!=",$reportPostHideStatus[5]['fk_uid_for']);                
            }
            $this->db->where('sportbook.createdDate>=',$userregdate); 
            $col = array('sportbook.pk_id','fk_uid','text','image as post_image','name','img as user_profile_image','city_name','state_name','user.status','DATE_FORMAT(koodo_sportbook.createdDate, "%b %d %Y %h:%i %p") AS date');

            $this->db->join('user','user.pk_id = sportbook.fk_uid');
            $this->db->join('city','user.city = city.pk_id');
            $postList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
           // print_r($postList);
           // die();
            // $postList_staus=1;
            // if ($postList_staus==1){


            // }
            $postList2=array();
            $array=array();
            if (!empty($my_friends)){
                foreach ($postList as $key => $val) {
                    foreach ($friendList as $key => $value) {
                    $id=$value['id'];
                    $fk_uid=$val['fk_uid'];
                        if($id !=$fk_uid){
                            array_push($postList2, $val);
                        }
                        elseif ($value['id']==$val['fk_uid']) {
                            array_push($array,$val);
                        }
                    }
                }           
            }
            if (!empty($my_friends)){
                $postList=$array;
            }
            $allpostList=array();
      
            foreach ($postList as $key => $value){
                $post_date=$value['date'];

                $date = date("d.m.Y");
                $match_date = date('d.m.Y', strtotime($post_date));
                $time = date('h:i A', strtotime($post_date));

                if($date == $match_date) { 
                    $value['date']='Today at '.$time ;
                }elseif(date('d.m.Y',strtotime("-1 days"))==$match_date) {
                    $value['date']='Yesterday at '.$time;
                }else{
                    $value['date']=$post_date;
                }
                $post_id=!empty($value['pk_id'])?$value['pk_id']:'';
                if (!empty($post_id)){

                    //post like status
                    $table = "sportbook_post_like";
                    $orderby = 'pk_id asc';
                    $condition = array('fk_uid' => $uid, 'fk_post' => $post_id);
                    $col = array('pk_id,like_status');
                    $likestatus= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['likeStatus'] = !empty($likestatus[0]['like_status'])?$likestatus[0]['like_status']:'2';//1-like,2-dislike

                    //Get Like count
                    $table = "sportbook_post_like";
                    $orderby = 'pk_id asc';
                    $condition = array('like_status' => '1', 'fk_post' => $post_id);
                    $col = array('pk_id');
                    $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $likeCount = !empty(count($like))?count($like):'0';
                   
                    if ($likeCount >= 1000){
                       $likeCount=number_format(($likeCount / 1000), 1) .'K';
                    }
                    $value['likeCount'] =$likeCount; 

                    //Get Comment count
                    $table = "sportbook_post_comment";
                    $orderby = 'pk_id asc';
                    $condition = array('fk_post' => $post_id);
                    $col = array('pk_id');
                    $comment= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $commentCount = !empty(count($comment))?count($comment):'0';
                   
                    if($commentCount >= 1000){
                      $commentCount=number_format(($commentCount / 1000), 1) .'K';
                    }
                    $value['commentCount'] =$commentCount;             
                }
                $allpostList[] = $value;
            }
            //remove post who user report for particular post
            $postListReport=array();
            foreach ($allpostList as $key => $value) {
                $post_id=!empty($value['pk_id'])?$value['pk_id']:'';
                $table = "sportbook_report";
                $orderby = 'pk_id asc';
                $condition = array('fk_post_id' => $post_id, 'fk_uid_given_by' => $uid);
                $col = array('pk_id');
                $reportPost = $this->Md_database->getData($table, $col, $condition, $orderby, '');                 
                if(empty($reportPost)){
                    array_push($postListReport, $value);
                }
                elseif (!empty($reportPost)) {
                    array_push($array,$value);
                }
            }         
            $resultarray = array('error_code' => '1', 'message' => 'Post List ','userProfileImg'=>$userProfile,'profile_path' => base_url().'uploads/users/','postList'=>array_slice($postListReport,$offset,$limit),'post_path' => base_url().'uploads/sportbook/post/');
            echo json_encode($resultarray);
            exit();                       
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function postLike(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $post_id = !empty($this->input->post('post_id')) ? $this->input->post('post_id') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';

        if (!empty($uid) && !empty($post_id) &&  !empty($user_id)  ){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "sportbook_post_like";
            $orderby = 'pk_id asc';
            $condition = array('fk_uid' => $uid,'fk_post'=>$post_id);
            $col = array('pk_id','like_status');
            $checkExitLike = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $like_status=!empty($checkExitLike[0]['like_status'])?$checkExitLike[0]['like_status']:'';
            if (empty($checkExitLike)){
                $table = "sportbook_post_like";
                $insert_data = array(
                    'fk_uid'=> $uid,
                    'fk_post'=> $post_id,
                    'like_status'=>'1',
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                );
                $resultarray = $this->Md_database->insertData($table,$insert_data);

                $table = "privileges_notifications";
                $select = "notifications,chat_notification";
                $this->db->where('fk_uid',$user_id);
                $this->db->order_by('privileges_notifications.pk_id','ASC');
                $chechprivilege = $this->Md_database->getData($table, $select, '', '', '');
                $notification=($chechprivilege[0]['notifications']);
                if ($notification=='1'){
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
                    $this->db->distinct();
                    $order_token2 = $this->Md_database->getData($table, $select, '', '', '');
                    $name=$order_token2[0]['name'];
                    //not send notification when user like self post
                    if ($uid!=$user_id) {
                        if(!empty($post_id)){
                            $resultarray = array('redirect_type' =>'sportbook','subject'=>'Like post','message'=>$name.' liked your post');
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>$uid,
                                'to_user_id'=>$user_id,
                                'redirect_type' => 'sportbook',
                                'subject' => 'Like post',
                                'message'=>$name.' liked your post',
                                'status' => '1',
                                'created_by ' =>$uid,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }
                    }                                          
                }
                if (!empty($resultarray)){
                    $resultarray = array('error_code' => '1', 'message' => 'Liked ');
                    echo json_encode($resultarray);
                    exit();                       
                }  
            }else{
                if ($like_status==1){
                    $table = "sportbook_post_like";
                    $updated_data = array(                                
                        'like_status'=>'2',
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                    );
                    $condition = array('fk_uid'=> $uid,'fk_post'=>$post_id);    
                    $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);
                    $resultarray = array('error_code' => '1', 'message' => 'Dislike ');
                      echo json_encode($resultarray);
                      exit();                       
                }elseif ($like_status==2){
                    $table = "sportbook_post_like";
                    $updated_data = array(                                
                        'like_status'=>'1',
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                    );
                    $condition = array('fk_uid'=> $uid,'fk_post'=>$post_id);    
                    $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);
                    $table = "privileges_notifications";
                    $select = "notifications,chat_notification";
                    $this->db->where('fk_uid',$user_id);
                    $this->db->order_by('privileges_notifications.pk_id','ASC');
                    $chechprivilege = $this->Md_database->getData($table, $select, '', '', '');
                    $notification=($chechprivilege[0]['notifications']);

                    if ($notification=='1'){
                        $table = "user";
                        $select = "token,user.pk_id,name";
                        $this->db->where('pk_id',$user_id);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token = $this->Md_database->getData($table, $select, '', '', '');
                        $target=$order_token[0]['token'];
                        
                        $table = "user";
                        $select = "user.pk_id,name";
                        $this->db->where('pk_id',$uid);
                        $this->db->distinct();
                        $order_token2 = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $name=$order_token2[0]['name'];
                        //not send notification when user like self post
                        if ($uid!=$user_id) {
                            if(!empty($post_id)){
                                $resultarray = array('redirect_type' =>'sportbook','subject'=>'Like post','message'=>$name.' liked your post');
                                $this->Md_database->sendPushNotification($resultarray,$target);

                                //store into database
                                $table = "custom_notification";
                                $insert_data = array(
                                    'from_uid'=>$uid,
                                    'to_user_id'=>$user_id,
                                    'redirect_type' => 'sportbook',
                                    'subject' => 'Like post',
                                    'message'=>$name.' liked your post',
                                    'status' => '1',
                                    'created_by ' =>$uid,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                                );
                                $result = $this->Md_database->insertData($table, $insert_data);
                            }
                        }                       
                    }
                    $resultarray = array('error_code' => '1', 'message' => 'Liked ');
                    echo json_encode($resultarray);
                    exit();                       
                }
            }
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid or Post Id or user_id is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function postComment(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $post_id = !empty($this->input->post('post_id')) ? $this->input->post('post_id') : '';
        $comment = !empty($this->input->post('comment')) ? $this->input->post('comment') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
    
        if (!empty($uid) && !empty($post_id) && !empty($comment) && !empty($user_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }          
            $table = "sportbook_post_comment";
            $insert_data = array(
                'fk_uid'=> $uid,
                'fk_post'=> $post_id,
                'comment'=> $comment,
                'createdBy' => $uid,
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
            );
            $resultarray = $this->Md_database->insertData($table,$insert_data);

            $table = "privileges_notifications";
            $select = "notifications,chat_notification";
            $this->db->where('fk_uid',$user_id);
            $chechprivilege = $this->Md_database->getData($table, $select, '', '', '');
            $notification=($chechprivilege[0]['notifications']);
            if($notification=='1'){
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
                //not send notification when user like self post
                if ($uid!=$user_id){
                    if(!empty($post_id)){
                        $resultarray = array('redirect_type' =>'sportbook','subject'=>'Post Comment','message'=>$name.' commited on your post');                                
                        $this->Md_database->sendPushNotification($resultarray,$target);

                        //store into database
                        $table = "custom_notification";
                        $insert_data = array(
                            'from_uid'=>$uid,
                            'to_user_id'=>$user_id,
                            'redirect_type' => 'sportbook',
                            'subject' => 'Post Comment',
                            'message'=>$name.'  commited on your post',
                            'status' => '1',
                            'created_by ' =>$uid,
                            'created_date' => date('Y-m-d H:i:s'),
                            'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                        );
                        $result = $this->Md_database->insertData($table, $insert_data);
                    } 
                }                      
            }
            $resultarray = array('error_code' => '1', 'message' => 'Comment Send Successfully ');
            echo json_encode($resultarray);
            exit();                        
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid or Post Id or comment or user_id is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    public function postCommentList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $post_id = !empty($this->input->post('post_id')) ? $this->input->post('post_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';

        if (!empty($uid) && !empty($post_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            if (!empty($post_id)){
                //Get Like count
                $table = "sportbook_post_like";
                $orderby = 'pk_id asc';
                $condition = array('like_status' => '1', 'fk_post' => $post_id);
                $col = array('pk_id');
                $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $likeCount = !empty(count($like))?count($like):'0';
               
                if ($likeCount >= 1000) {
                  $likeCount=number_format(($likeCount / 1000), 1) .'K';
                }

                //Get Comment count
                $table = "sportbook_post_comment";
                $orderby = 'pk_id asc';
                $condition = array('fk_post' => $post_id);
                $col = array('pk_id');
                $comment= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $commentCount = !empty(count($comment))?count($comment):'0';
               
                if ($commentCount >= 1000) {
                  $commentCount=number_format(($commentCount / 1000), 1) .'K';
                }

                $table = "sportbook_post_like";
                $orderby = 'pk_id asc';
                $condition = array('fk_post' => $post_id,'fk_uid'=>$uid);
                $col = array('like_status');
                $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $likestatus = !empty($like[0]['like_status'])?$like[0]['like_status']:'2';
                      
            }

            //Post Details
            $table = "sportbook";
            $orderby = 'sportbook.pk_id asc';
            $condition = array('sportbook.pk_id'=>$post_id);
            $col = array('name','img','city_name','state_name','image','user.status','fk_uid','text','DATE_FORMAT(koodo_sportbook.createdDate, "%b %d %Y %h:%i %p") AS createdDate');
            $this->db->join('user','sportbook.fk_uid = user.pk_id');
            $this->db->join('city','user.city = city.pk_id');
            $postUser=$this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($postUser[0]['status']) && $postUser[0]['status']==1){
                $userName=!empty($postUser[0]['name'])?$postUser[0]['name']:'';
                $userProfile=!empty($postUser[0]['img'])?$postUser[0]['img']:'';                
            }elseif(!empty($postUser[0]['status']) && $postUser[0]['status']==2){
                $userName='Inactive User';
                $userProfile='';
            }elseif(!empty($postUser[0]['status'])&& $postUser[0]['status']==3){
                $userName='Deleted User';
                $userProfile='';
            }
            $cityName=!empty($postUser[0]['city_name'])?$postUser[0]['city_name']:'';
            $stateName=!empty($postUser[0]['state_name'])?$postUser[0]['state_name']:'';
            $postImage=!empty($postUser[0]['image'])?$postUser[0]['image']:'';
            $post_date=!empty($postUser[0]['createdDate'])?$postUser[0]['createdDate']:'';
            $date = date("d.m.Y");
            $match_date = date('d.m.Y', strtotime($post_date));
            $time = date('h:i A', strtotime($post_date));

            if($date == $match_date) { 
                $postDate='Today at '.$time ;
            }elseif(date('d.m.Y',strtotime("-1 days"))==$match_date) {
                $postDate='Yesterday at '.$time;
            }else{
                $postDate=$post_date;
            }
            
            //comment List
            $commentList=array();
            $commentList2=array();
            $table = "sportbook_post_comment";
            $orderby = 'sportbook_post_comment.pk_id DESC';
            $condition = array('fk_post' => $post_id);
            $this->db->limit($limit, $offset); 
            $col = array('comment','name','img as comment_user_profile_image','user.status','user.pk_id','DATE_FORMAT(koodo_sportbook_post_comment.createdDate, "%M %d %Y %h:%i %p") AS comment_date');
            $this->db->join('user','user.pk_id = sportbook_post_comment.fk_uid');
            $commentList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // print_r($commentList);
            // die();
            if (!empty($commentList)){            
                foreach ($commentList as $key => $value) {
                    if (!empty($value) && $value['status']==2){
                        $value['name']='Inactive User';
                        $value['comment_user_profile_image']='';
                    }elseif (!empty($value) && $value['status']==3){
                       $value['name']='Deleted User';
                       $value['comment_user_profile_image']='';
                    }
                    $commentList2[]=$value;
                }
            }
            // print_r($commentList2);
            // die();
            foreach ($commentList2 as $key => $value) {
                $id = $value['pk_id']; 
                $comment_date=$value['comment_date'];
                $last = new DateTime($comment_date);
                $now = new DateTime( date( 'Y-m-d H:i:s', time() )) ; 

                // Find difference
                $interval = $last->diff($now);

                // Store in variable to be used for calculation etc
                $years = (int)$interval->format('%Y');
                $months = (int)$interval->format('%m');
                $days = (int)$interval->format('%d');
                $hours = (int)$interval->format('%H');
                $minutes = (int)$interval->format('%i');

                if($years > 0){
                    $value['comment_date']=  $years.' yr ago' ;
                }else if($months > 0){
                    $value['comment_date']=  $months.' month ago';
                }else if($days > 0){
                    $value['comment_date']=  $days.' day ago ';
                }else if($hours > 0){
                    $value['comment_date']=   $hours.' hr ago ';
                }else{
                    $value['comment_date']= $minutes.' min ago' ;
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
                $value['skill'] =!empty($player[0]['skill'])?$player[0]['skill']:'';
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
                        // 
            }    
            $empty=array();
            $resultarray = array('error_code' => '1', 'message' => 'Comment List','fk_uid'=>!empty($postUser[0]['fk_uid'])?$postUser[0]['fk_uid']:'','name'=>!empty($userName)?$userName:'','post_user_profile_image'=>!empty($userProfile)?$userProfile :null,'profile_path' => base_url().'uploads/users/','city_name'=>$cityName,'state_name'=>$stateName,'post_date'=>$postDate,'text'=>!empty($postUser[0]['text'])?$postUser[0]['text']:'','post_image'=>$postImage,'post_path' => base_url().'uploads/sportbook/post/','likeStatus'=>!empty($likestatus)?$likestatus:'','likeCount'=>!empty($likeCount)?$likeCount:'0','commentCount'=>!empty($commentCount)?$commentCount:'0','comment_list'=>!empty($new_array)?$new_array:$empty);
            echo json_encode($resultarray);
            exit(); 
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid or Post Id or comment is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    public function checkNewPost(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $latest_post_id = !empty($this->input->post('latest_post_id')) ? $this->input->post('latest_post_id') : ''; // post id
        $my_friends = !empty($this->input->post('my_friends')) ? $this->input->post('my_friends') : ''; //only for my friend post list   1-for my friend post list    empty for all post 
        if (!empty($uid) && !empty($latest_post_id)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $friendList =array();         
            $table = "friends";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1','request_status'=>'1');
            $col = array('user_id','uid');
            $this->db->distinct();
            $this->db->group_start();
            $this->db->where('uid',$uid);
            $this->db->or_where('user_id', $uid); 
            $this->db->group_end();
            $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $List2 =array();
            $List1 =array();
            $friendList =array();
            foreach ($List as $key => $value){
                if ($value['uid']==$uid) {
                    $table = "friends";
                    $orderby = 'friends.pk_id asc';
                    $condition = array('request_status' => '1','uid'=>$uid);
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
                    $condition = array('request_status' => '1','user_id'=>$uid);
                    $this->db->join('user','user.pk_id = friends.uid');
                    $this->db->join('city','city.pk_id = user.city');
                    $this->db->join('privileges_notifications','privileges_notifications.fk_uid = user.pk_id');
                    $this->db->distinct();
                    $col = array('uid as id','user.name','user.img','user.address','city.city_name,privileges_notifications.available');
                    $List2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
                $friendList=array_merge($List1,$List2);
            }
            $postList=array();
            $table = "sportbook";
            $orderby = 'sportbook.pk_id DESC';
            $condition = array('status' => '1');
            $col = array('pk_id,fk_uid');           
            $new_post_id = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $postList2=array();
            $array=array();
            if (!empty($my_friends)) {
                foreach ($new_post_id as $key => $val) {
                    foreach ($friendList as $key => $value) {
                    $id=$value['id'];
                    $fk_uid=$val['fk_uid'];
                        if($id !=$fk_uid){
                            array_push($postList2, $val);
                        }
                        elseif ($value['id']==$val['fk_uid']) {
                            array_push($array,$val);
                        }
                    }
                }           
            }
            if (!empty($my_friends)) {
                $new_post_id=$array;
            }
            $new_post='no';
            if (!empty($new_post_id)) {
                if ($new_post_id[0]['pk_id']>$latest_post_id) {
                    $new_post='yes';
                    $resultarray = array('error_code' => '1', 'message' => 'New Post Status','new_post'=>$new_post);
                    echo json_encode($resultarray);
                    exit();
                }elseif ($new_post_id[0]['pk_id']==$latest_post_id) {
                    $new_post='no';
                    $resultarray = array('error_code' => '2', 'message' => 'New Post Status','new_post'=>$new_post);
                    echo json_encode($resultarray);
                }else{
                    $new_post='no';
                    $resultarray = array('error_code' => '2', 'message' => 'New Post Status','new_post'=>'no');
                    echo json_encode($resultarray);
                }   
            }                         
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or latest_post_id is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    public function reportPost(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $post_id = !empty($this->input->post('post_id')) ? $this->input->post('post_id') : '';
        $report_for_uid = !empty($this->input->post('report_for_uid')) ? $this->input->post('report_for_uid') : '';
        $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
        if (!empty($uid) && !empty($post_id) && !empty($report_for_uid) && !empty($description)){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if(!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "sportbook_report";
            $insert_data = array(
                'fk_uid_given_by'=> $uid,
                'fk_post_id'=> $post_id,
                'fk_uid_for'=> $report_for_uid,
                'description'=> $description,
                'createdBy' => $uid,
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
            );
            $resultarray = $this->Md_database->insertData($table,$insert_data);
            $report_id = $this->db->insert_id();
            if (!empty($report_id)) {
                $resultarray = array('error_code' => '1', 'message' => 'Reported Successfully ');
                echo json_encode($resultarray);
                exit();
            }                                     
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid or post_id or report_for_uid or description is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
} 