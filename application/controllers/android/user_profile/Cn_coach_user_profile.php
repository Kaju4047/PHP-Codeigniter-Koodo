<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_coach_user_profile extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
   
    public function addCoachProfile(){
        // $sport='[{"sportname":"sport" ,"pk_id":"30","primary_id":"1"},{"sportname":"sport" ,"pk_id":"15","primary_id":"1"},{"sportname":"sport" ,"pk_id":"30","primary_id":"1"}]';

        $sport = !empty($this->input->post('sport')) ? $this->input->post('sport') : '';   
        $sports = json_decode($sport);
        $achivement = !empty($this->input->post('achivement')) ? $this->input->post('achivement') : '';
        $coach_playing_time = !empty($this->input->post('coach_playing_time')) ? $this->input->post('coach_playing_time') : '';
        $clubDetail = !empty($this->input->post('club_detail')) ? $this->input->post('club_detail') : '';
        $experience = !empty($this->input->post('experience')) ? $this->input->post('experience') : '';
        $coaching_tech = !empty($this->input->post('coaching_tech')) ? $this->input->post('coaching_tech') : '';
        $profile_id = !empty($this->input->post('profile_id')) ? $this->input->post('profile_id') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
            if (!empty($uid)){
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
                $orderby = 'user.pk_id asc';
                $condition = array('user.status' => '1', 'user.pk_id' => $uid);
                $col = array('user.pk_id');
                $this->db->join('profile_type as PT', 'PT.user_id = user.pk_id');
                $this->db->join('usertype as UT', 'UT.pk_id = PT.usertype');
                $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                if (empty($checkUserProfile)) {
                    $resultarray = array('error_code' => '4', 'message' => 'User Not register for Coach Profile ');
                    echo json_encode($resultarray);
                    exit();
                }
                if (empty($profile_id)){
                    // if(empty($achivement) || empty($clubDetail) || empty($experience) || empty($coaching_tech)) {
                    //     $resultarray = array('error_code' => '2', 'message' => ' achivement or clubDetail or experience is empty');
                    //     echo json_encode($resultarray);
                    //     exit();
                    // }else{
                        $table = "profile_type";
                        $orderby = 'pk_id asc';
                        $condition = array('status' => '1', 'user_id' => $uid,'usertype' => 2);
                        $col = array('pk_id');
                        // $this->db->join('profile_type as PT', 'PT.user_id = user.pk_id');
                        // $this->db->join('usertype as UT', 'UT.pk_id = PT.usertype');
                        $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        // print_r($checkUserProfile);
                        // die();
                        if (empty($checkUserProfile)) {
                            $table = "profile_type";
                            $insert_data = array( 
                                'usertype'=>'2',
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $type_id = $this->db->insert_id();
                        }

                       
                        if (!empty($sports)){

                            $table = "user_profile_detail";
                            $insert_data = array(
                                'usertype'=>'2',
                                'achivement'=> $achivement,
                                'club_detail'=> $clubDetail,
                                'playing_time' => $coach_playing_time, 
                                'experience'=> $experience,                        
                                'club_technique'=> $coaching_tech,                        
                                'status' => '1',
                                'user_id' => $uid,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),                
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                            $profile_id = $this->db->insert_id();


                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'2');
                            $resultarray = $this->Md_database->deleteData($table, $condition);
                            foreach ($sports as $key => $value){
                                $sportname= $value->pk_id;
                                $primary_id= $value->primary_id;
                                $fees_hr= !empty($value->skill)?$value->skill:'';

                                $table = "profie_player_sport";
                                $insert_data2 = array(                            
                                    'status' => '2',
                                    'user_id' => $uid,
                                    'type'=>'2',
                                    'createdBy' => $uid,
                                    'createdDate' => date('Y-m-d H:i:s'),                
                                    'created_ip_address' => $_SERVER['REMOTE_ADDR']              
                                );
                                $insert_data2['sportname']=$sportname;
                                $insert_data2['primary_id']=$primary_id;
                                $insert_data2['fees_hr']=$fees_hr;
                                $resultarray = $this->Md_database->insertData($table, $insert_data2);                        
                            } 
                        }else{
                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'2');
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            $table = "user_profile_detail";
                            $update_data = array(                        
                               'status' => '3',
                               'updatedBy' => $uid,
                               'updatedDate' => date('Y-m-d H:i:s'),
                               'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array(
                                'user_id' => $uid,
                                // 'pk_id'=>$profile_id,
                                'usertype'=>2
                            );
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                            $table = "profile_type";                         
                            $condition = array("user_id" => $uid,'usertype'=>2);
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            // $table = "profile_type";
                            // $update_data = array(                        
                            //    'status' => '3',
                            //    'updatedBy' => $uid,
                            //    'updatedDate' => date('Y-m-d H:i:s'),
                            //    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            // );
                            // $condition = array(
                            //     'user_id' => $uid,
                            //     // 'pk_id'=>$profile_id,
                            //     'usertype'=>2
                            // );
                            // $resultarray = $this->Md_database->updateData($table, $update_data, $condition);

                        }
                        $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>!empty($sports)?$profile_id:'','Coach'=>!empty($sports)?'1':'0' ,'message' => 'profile data insert successfully');
                        echo json_encode($resultarray);
                        exit();                       
                    // } 
                }else{
                    // if(empty($achivement) || empty($clubDetail) || empty($experience) || empty($coaching_tech)){
                    //     $resultarray = array('error_code' => '2', 'message' => 'skilLevel or achivement or clubDetail or experience is empty');
                    //     echo json_encode($resultarray);
                    //     exit();
                    // }else{               
                        

                        if (!empty($sports)) {

                            $table1 = "user_profile_detail";
                            $insert_data1 = array(
                                'achivement'=> $achivement,
                                'club_detail'=> $clubDetail,
                                'playing_time' => $coach_playing_time, 
                                'experience'=> $experience,                        
                                'club_technique'=> $coaching_tech,                        
                                'status' => '1',
                                'updatedBy' => $uid, 
                                'updatedDate' => date('Y-m-d H:i:s'),
                                'updated_ip_address' => $_SERVER['REMOTE_ADDR']             
                            );
                            $condition1 = array("user_id" => $uid,"pk_id" => $profile_id);
                            $resultarray = $this->Md_database->updateData($table1, $insert_data1, $condition1);
                        
                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'2');
                            $resultarray = $this->Md_database->deleteData($table, $condition);
                            foreach ($sports as $key => $value){
                                $sportname= $value->pk_id;
                                $primary_id= $value->primary_id;
                                $fees_hr= !empty($value->skill)?$value->skill:'';

                                $table = "profie_player_sport";
                                $insert_data2 = array(                            
                                    'status' => '2',
                                    'user_id' => $uid,
                                    'type'=>'2',
                                    'createdBy' => $uid,
                                    'createdDate' => date('Y-m-d H:i:s'),                
                                    'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                                );
                                $insert_data2['sportname']=$sportname;
                                $insert_data2['primary_id']=$primary_id;
                                $insert_data2['fees_hr']=$fees_hr;
                                $resultarray = $this->Md_database->insertData($table, $insert_data2);                        
                            } 
                        }else{
                            $table = "profie_player_sport";                         
                            $condition = array("user_id" => $uid,'type'=>'2');
                            $resultarray = $this->Md_database->deleteData($table, $condition);

                            $table = "user_profile_detail";
                            $update_data = array(                        
                               'status' => '3',
                               'updatedBy' => $uid,
                               'updatedDate' => date('Y-m-d H:i:s'),
                               'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array(
                                'user_id' => $uid,
                                'pk_id'=>$profile_id,
                                'usertype'=>2
                            );
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                            $table = "profile_type";                         
                            $condition = array("user_id" => $uid,'usertype'=>2);
                            $resultarray = $this->Md_database->deleteData($table, $condition);
                            // $table = "profile_type";
                            // $update_data = array(                        
                            //    'status' => '3',
                            //    'updatedBy' => $uid,
                            //    'updatedDate' => date('Y-m-d H:i:s'),
                            //    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            // );
                            // $condition = array(
                            //     'user_id' => $uid,
                            //     // 'pk_id'=>$profile_id,
                            //     'usertype'=>2
                            // );
                            // $resultarray = $this->Md_database->updateData($table, $update_data, $condition);
                        }
                        $resultarray = array('error_code' => '1', 'uid'=>$uid,'profile_id'=>!empty($sports)?$profile_id:'','Coach'=>!empty($sports)?'1':'0','message' => 'Personal data update successfully');
                        echo json_encode($resultarray);
                        exit();                                   
                    // }

                }
            }else {
                $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                echo json_encode($resultarray);
                exit();                       
            } 
    }

    public function addBatches(){
        // $day='[{"day":"sun"},{"day":"mon"}]';
        $batchsport = !empty($this->input->post('batch_sport')) ? $this->input->post('batch_sport') : '';   
        $days = !empty($this->input->post('day')) ? $this->input->post('day') : '';   
        // $days = json_decode($day);
        $starttime = !empty($this->input->post('start_time')) ? $this->input->post('start_time') : '';   
        $endtime = !empty($this->input->post('end_time')) ? $this->input->post('end_time') : '';
        $venue = !empty($this->input->post('venue')) ? $this->input->post('venue') : '';
        $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';   
        $no_students = !empty($this->input->post('no_students')) ? $this->input->post('no_students') : '';   
        $fees = !empty($this->input->post('fees')) ? $this->input->post('fees') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
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
            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'user.pk_id' => $uid);
            $col = array('user.pk_id');
            $this->db->join('profile_type as PT', 'PT.user_id = user.pk_id');
            $this->db->join('usertype as UT', 'UT.pk_id = PT.usertype');
            $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (empty($checkUserProfile)){
                $resultarray = array('error_code' => '4', 'message' => 'User Not register for Coach Profile ');
                echo json_encode($resultarray);
                exit();
            }
            if(empty($batchsport) || empty($days) || empty($starttime) || empty($endtime) || empty($venue) || empty($city) || empty($no_students) || empty($fees)) {
                $resultarray = array('error_code' => '2', 'message' => ' batchsport or day or starttime or endtime or venue or city or no_students or fees is empty');
                echo json_encode($resultarray);
                exit();
            }else{
                $table = "profile_type";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1', 'user_id' => $uid,'usertype' => 2);
                $col = array('pk_id');
                $checkUserProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                                                 
                $table = "koodo_coach_batches";
                $insert_data = array(                            
                    'start_time' => $starttime,
                    'end_time' => $endtime,
                    'venue' => $venue,
                    'batch_sport' => $batchsport,
                    'place' => $city,
                    'days'=>$days,
                    'studentNo' => $no_students,
                    'fees' => $fees,
                    'status' => '1',
                    'user_id' => $uid,
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),                
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']                
                );
                $resultarray = $this->Md_database->insertData($table, $insert_data);
                $batch_id = $this->db->insert_id();            
                         
                $resultarray = array('error_code' => '1', 'uid'=>$uid,'batch_id'=>$batch_id,'Coach'=>'1' ,'message' => 'batch data insert successfully');
                echo json_encode($resultarray);
                exit();                       
            }                    
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }

    public function deleteBatch(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $batch_id = !empty($this->input->post('batch_id')) ? $this->input->post('batch_id') : '';
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

                if(!empty($batch_id)) {
                    $inserted_data = array(                    
                        'status' => '3',   
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                    );    
                    $condition = array("pk_id" => $batch_id);
                    $resultarray = $this->Md_database->updateData('coach_batches', $inserted_data, $condition); 

                    $resultarray = array('error_code' => '1', 'message' => 'batch  delete');
                    echo json_encode($resultarray);
                    exit();              
               }else{
                $resultarray = array('error_code' => '2', 'message' => 'batch_id empty');
                    echo json_encode($resultarray);
                    exit();   
               }
               
          }else {
              $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                       
          } 
    }

    public function listCoachSportwise(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '111';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "0";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "0";
        $sportid = !empty($this->input->post('sportid')) ? $this->input->post('sportid') : "";
        $verified = !empty($this->input->post('verified')) ? $this->input->post('verified') : "";
        $available = !empty($this->input->post('available')) ? $this->input->post('available') : "";
        $ageFrom = !empty($this->input->post('ageFrom')) ? $this->input->post('ageFrom') : "";
        $ageTo = !empty($this->input->post('ageTo')) ? $this->input->post('ageTo') : "";
        $feesFrom = !empty($this->input->post('feesFrom')) ? $this->input->post('feesFrom') : "";
        $feesTo = !empty($this->input->post('feesTo')) ? $this->input->post('feesTo') : "";       
        $gender = !empty($this->input->post('gender')) ? $this->input->post('gender') : "";
          // $rating='{"one_star":"" ,"two_star":"","three_star":"","four_star":"","five_star":""}';
        $rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : "";
        $distanceFrom = !empty($this->input->post('distance_from')) ? $this->input->post('distance_from') : "";
        $distanceTo = !empty($this->input->post('distance_to')) ? $this->input->post('distance_to') : "";
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : "";
        $subscription_plan = !empty($this->input->post('subscription_plan')) ? $this->input->post('subscription_plan') : ""; //Platinum Gold
        $online_status = !empty($this->input->post('online_status')) ? $this->input->post('online_status') : "";// 1- online 2- offline
        $experience = !empty($this->input->post('experience')) ? $this->input->post('experience') : "";
        if (!empty($rating)) {
            $rate=json_decode($rating);
            $one_star=$rate->one_star;
            $two_star=$rate->two_star;
            $three_star=$rate->three_star;
            $four_star=$rate->four_star;
            $five_star=$rate->five_star;
        }
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

              // frined List of UID
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
            foreach ($List as $key => $value){
                if ($value['uid']==$uid){
                    $table = "friends";
                    $orderby = '';
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
    

            $subquery =',(SELECT  COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=2 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=koodo_user.pk_id and r.type=2 and r.status=1) as count';
            if (empty($sportid)) {
                $fees_hr =',(SELECT  COALESCE(fees_hr,0) as fees FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id and r.primary_id=1 and r.type=2 ORDER BY pk_id DESC LIMIT 1) as fees';
            }else{
                $fees_hr =',(SELECT  COALESCE(fees_hr,0) as fees FROM `koodo_profie_player_sport` as r WHERE r.user_id=koodo_user.pk_id  and r.type=2 and r.sportname='.$sportid.' ORDER BY pk_id DESC LIMIT 1) as fees';
            }
            
            $table = "user";
            $this->db->join('privileges_notifications','user.pk_id = privileges_notifications.fk_uid');
            $this->db->where('privileges_notifications.display_profile',1);
            $this->db->where('user.pk_id!=',$uid);
            $this->db->distinct();
            $this->db->join('koodo_user_profile_detail','koodo_user_profile_detail.user_id = user.pk_id AND koodo_user_profile_detail.status = 1 AND koodo_user_profile_detail.usertype = "1"','LEFT');  
                if (!empty($experience)){
                    // print_r($experience);
                    // die();
                    $this->db->where("koodo_user_profile_detail.experience",$experience); 
                }
                if (!empty($search)) { 
                    $this->db->where("user.name LIKE '%$search%'");  
                }
                if (!empty($online_status)){
                    $this->db->where("user.online_status",$online_status); 
                }
                if (!empty($available)) {
                    // $this->db->join('privileges_notifications as PN','user.pk_id=PN.fk_uid','LEFT');
                    $this->db->where("privileges_notifications.available",$available); 
                    // $condition['PN.available']=$available;
                }
                if(!empty($ageFrom)){
                    $this->db->where('user.age>=',$ageFrom);
                }   
                if(!empty($ageTo)){
                    $this->db->where('user.age<=',$ageTo);
                }  
                if(!empty($verified)){
                    $this->db->where('user.verify_status',$verified);
                }  
                if(!empty($gender)){
                    $this->db->where('user.gender',$gender);
                } 
                if(!empty($one_star)){
                    $this->db->having('average',$one_star);
                } 
                if(!empty($two_star)){
                    $this->db->or_having('average',$two_star);                             
                }
                if(!empty($three_star)){
                    $this->db->or_having('average',$three_star);
                }
                if(!empty($four_star)){
                    $this->db->or_having('average',$four_star);
                }
                if(!empty($five_star)){
                    $this->db->or_having('average',$five_star);
                }
                if(!empty($feesFrom)){
                    $this->db->having('fees>=',$feesFrom);
                }
                if(!empty($feesTo)){
                    $this->db->having('fees<=',$feesTo);
                }
                if (!empty($city_id)) {
                    $this->db->where('user.city',$city_id);
                } 
                if (!empty($subscription_plan)) {
                    $this->db->where('koodo_buy_subscription.category',$subscription_plan);
                }

                $condition = array('user.status' => '1');
                $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
                // $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id  AND buy_subscription.status = 1 AND buy_subscription.list_status = 1 AND buy_subscription.listtype = "Coach"','LEFT'); 
                $this->db->join('buy_subscription','buy_subscription.user_id = user.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Coach"','LEFT'); 
                $this->db->where('PT.usertype',2);
                $this->db->where('PT.status',1);
                // $this->db->order_by('FIELD ( koodo_buy_subscription.category,"Gold","Platinum") DESC');
                $this->db->order_by("koodo_buy_subscription.category","DESC");
                $this->db->order_by("PT.list_at_top","ASC");
                $this->db->order_by("user.online_date","DESC");


            if (!empty($sportid)) {
                $this->db->where('profie_player_sport.sportname',$sportid);
                $this->db->join('profie_player_sport','user.pk_id =profie_player_sport.user_id'); 
                $this->db->where('profie_player_sport.type',2);
            }
            $col = "user.pk_id,user.name,COALESCE(age,'') as age,COALESCE(koodo_user.address,'') as address,PT.usertype,mob,img,email,COALESCE(koodo_user_profile_detail.playing_time,'') as playing_time,verifyEmail,user.doc_verify,verify_status,category,gender,online_status,experience".$subquery2.$subquery.$fees_hr;
            // $this ->db->order_by("FIELD(koodo_user.pk_id,$uid) DESC");
            $coachList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // echo "<pre>";
            // print_r($coachList);
            // die();
            $new_array=array();
            if (!empty($coachList)) {
                foreach ($coachList as $key => $value){
                    $id = $value['pk_id'];
                    $type= $value['usertype'];

                    if ($value['doc_verify'] =='1' && ($value['email'] == $value['verifyEmail'])){
                        $value['verify_tick'] = '1';//yes
                    }else{
                         $value['verify_tick'] = '2';//No
                    }


                    $table = "profie_player_sport";
                    $orderby = 'profie_player_sport.pk_id asc';
                    $condition = array('user_id' => $id, 'profie_player_sport.type'=>'2' );
                    $this->db->join('sport','sport.pk_id = profie_player_sport.sportname');
                    $col = array('sport.sportname');
                    $sportList = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['sportList'] =$sportList;


                    $table = "user";
                    $orderby = 'user.pk_id asc';
                    $condition = array('user.pk_id' => $id);
                    $col = array('user.pk_id','latitude','longitude','online_status','doc_verify' );
                    $this->db->where('status',1);
                    //'COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category'
                    // $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id and buy_subscription.status = 1 AND buy_subscription.listtype = "Coach"','LEFT');
                    // $this->db->where('buy_subscription.status','1')
                    $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    // $value['online_status']=$latlong[0]['online_status'];
                    $value['doc_verify']=$latlong[0]['doc_verify'];
                    // $value['category']=$latlong[0]['category'];
                    
                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id' => $uid);
                     $this->db->where('status',1);
                    $col = array('pk_id','latitude','longitude');
                    $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                    $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                    $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                    $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';
                        //Calculate distance from latitude and longitude
                    $value['distance']="";
                    if (!empty($latitudeFrom) && !empty($longitudeFrom) && !empty($latitudeTo) &&!empty($longitudeTo)) {
                        # code...
                        $theta = $longitudeFrom - $longitudeTo;
                        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;

                        $distance = ($miles * 1.609344);
                        $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';
                    }

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
                        $orderby = '';
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
                        $orderby = '';
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

                //Distance filter
                $final_array=array();
                $array=array();
                if (!empty($distanceFrom) && !empty($distanceTo)) {
                    foreach ($new_array as $key => $value) {
                        $distance=$value['distance'];

                        if($distance >=$distanceFrom  && $distance <=$distanceTo){
                            array_push($final_array,$value);
                        }else{
                            array_push($array,$value);  
                        }
                    }
                } 
            }
           
            $table = "advertisement";
            $orderby = 'pk_id DESC';
            $condition = array('status' => '1','place' =>'5');
            $col = array('advimg');
            $adv = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $advimg=!empty($adv[0]['advimg'])? $adv[0]['advimg']:'';  
            $empty=array();
            $resultarray = array('error_code' => '1', 'message' => 'coachList ','coachList' => !empty($distanceFrom && $distanceTo )? array_slice($final_array,$offset,$limit): array_slice($new_array,$offset,$limit),'advimg' => !empty($advimg)?$advimg:'','img_path' => base_url().'uploads/users/','adv_path' => base_url().'uploads/master/advimg/');
            echo json_encode($resultarray);
            exit();              

        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }
    public function coachDocumentList(){
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
            $doc_list=array();
            $table = "coach_certificate";
            $orderby = 'pk_id asc';
            $condition = array('user_id' => $uid);
            $col = array('pk_id','certificate');
            $doc_list = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
            $resultarray = array('error_code' => '1', 'message' => 'Document  List','doc_list'=>$doc_list,'doc_path' => base_url().'uploads/users/document/');
            echo json_encode($resultarray);
            exit();               
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

}

