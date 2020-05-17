<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_coaching_academy extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    public function addAcademy(){
       $image = !empty($this->input->post('image')) ? $this->input->post('image') : '';
       $academyName = !empty($this->input->post('academy_name')) ? $this->input->post('academy_name') : '';
       $sport = !empty($this->input->post('sport')) ? $this->input->post('sport') : '';
       $start_date = !empty($this->input->post('start_date')) ? $this->input->post('start_date') : '';
       $end_date = !empty($this->input->post('end_date')) ? $this->input->post('end_date') :null;
       $start_time = !empty($this->input->post('start_time')) ? $this->input->post('start_time') : '';
       $noStudent = !empty($this->input->post('no_student')) ? $this->input->post('no_student') : '';
       $venue = !empty($this->input->post('venue')) ? $this->input->post('venue') : '';
       $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';
       $fees = !empty($this->input->post('fees')) ? $this->input->post('fees') : '';
       $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
       $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
       $academy_id = !empty($this->input->post('academy_id')) ? $this->input->post('academy_id') : '';
       $primary_mobile_no = !empty($this->input->post('primary_mobile_no')) ? $this->input->post('primary_mobile_no') : '';
       $secondary_mobile_no = !empty($this->input->post('secondary_mobile_no')) ? $this->input->post('secondary_mobile_no') : null;
       $email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
       $website = !empty($this->input->post('website')) ? $this->input->post('website') : '';
       $location = !empty($this->input->post('location')) ? $this->input->post('location') : '';
       $latitude = !empty($this->input->post('latitude')) ? $this->input->post('latitude') : '';
       $longitude = !empty($this->input->post('longitude')) ? $this->input->post('longitude') : '';
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

            // if(empty($academyName) || empty($sport) || empty($start_date) || empty($end_date) || empty($start_time) || empty($noStudent) || empty($venue) || empty($fees) || empty($description)){
            //     $resultarray = array('error_code' => '2', 'message' => 'All fields required');
            //     echo json_encode($resultarray);
            //     exit();
            // }else{  
                if (empty($academy_id)){
                    $photoDoc = "";
                    if (!empty($_FILES['image']['name'])) {
                        $rename_name = uniqid(); //get file extension:
                        $arr_file_info = pathinfo($_FILES['image']['name']);
                        $file_extension = $arr_file_info['extension'];
                        $newname = $rename_name . '.' . $file_extension;
                        $old_name = $_FILES['image']['name'];
                        $path = "uploads/academy/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";
                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname); 
                    }            
                    $table = "academy";
                    $insert_data = array(
                        'coach_name'=> $academyName,
                        'sport_type'=> $sport,
                        'start_date'=>$start_date,                        
                        'end_date'=> $end_date,                        
                        'student_number'=> $noStudent,                        
                        'academy_time'=> $start_time,                        
                        'fees'=> $fees,                        
                        // 'city'=> $city,                        
                        'venue'=> $venue,                        
                        'img'=> $photoDoc,                        
                        'primary_mobile_no'=> $primary_mobile_no,                        
                        'secondary_mobile_no'=> $secondary_mobile_no,                        
                        'email'=> $email,                        
                        'website'=> $website,                        
                        'location'=> $location,                        
                        'latitude'=> $latitude,                        
                        'longitude'=> $longitude,                        
                        'description'=> $description,                        
                        'status' => '2',
                        'user_id' => $uid,
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $resultarray = $this->Md_database->insertData($table, $insert_data);
                    $academy_id = $this->db->insert_id();
                    $resultarray = array('error_code' => '1','academy_id'=>$academy_id, 'uid'=>$uid ,'message' => 'Academy data insert successfully');
                    echo json_encode($resultarray);
                    exit();   
                }else{
                    $photoDoc = "";
                    if (!empty($_FILES['image']['name'])) {
                        $rename_name = uniqid(); //get file extension:
                        $arr_file_info = pathinfo($_FILES['image']['name']);
                        $file_extension = $arr_file_info['extension'];
                        $newname = $rename_name . '.' . $file_extension;
                        $old_name = $_FILES['image']['name'];
                        $path = "uploads/academy/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";

                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname); 
                    }            
                    $table = "academy";
                    $update_data = array(
                        'coach_name'=> $academyName,
                        'sport_type'=> $sport,
                        'start_date'=>$start_date,                        
                        'end_date'=> $end_date,                        
                        'student_number'=> $noStudent,                        
                        'academy_time'=> $start_time,                        
                        'fees'=> $fees,                        
                        // 'city'=> $city,                        
                        'venue'=> $venue,
                        'primary_mobile_no'=> $primary_mobile_no,                        
                        'secondary_mobile_no'=> $secondary_mobile_no,                        
                        'email'=> $email,                        
                        'website'=> $website,                        
                        'location'=> $location,                        
                        'latitude'=> $latitude,                        
                        'longitude'=> $longitude,                        
                        'img'=> $photoDoc,                        
                        'description'=> $description,                        
                        'status' => '2',
                        'user_id' => $uid,
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
                    );
                    $condition = array(
                        'user_id' => $uid,
                        'pk_id'=>$academy_id
                    );
                    $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                    $resultarray = array('error_code' => '4','message' => 'Academy data update successfully');
                    echo json_encode($resultarray);
                    exit(); 
                }                                    
            // }         
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                       
        } 
    }
    public function listAcademy(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "";
        $sportid = !empty($this->input->post('sportid')) ? $this->input->post('sportid') : "";
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
            if (!empty($uid)) {
                $table = "user";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '2', 'pk_id' => $uid);
                $col = array('pk_id','name');
                $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                if (!empty($checkUser)){
                    $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to '.SITE_TITLE);
                    echo json_encode($resultarray);            
                    exit();
                }  
                $academyDetails=array();   
                
                // $table = "academy";
                // $select = "academy.pk_id,end_date";
                // $this->db->order_by("FIELD(user_id,$uid ) DESC");
                // $this->db->join('sport as s','s.pk_id = academy.sport_type');
                // $this->db->join('user as UA', 'UA.pk_id = academy.user_id');
                // $this->db->where('academy.status','1');
                // $this->db->where('UA.status','1');                   
                // $end_date= $this->Md_database->getData($table, $select, '', 'academy.pk_id DESC', '');
                // echo "<pre>";
                // print_r($end_date);
                // die();

                // foreach ($end_date as $key => $value){
                    $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_academy=koodo_academy.pk_id and r.type=4 and r.status=1) as rate';
                    $subquery2 =',(SELECT count(fk_academy)  FROM `koodo_user_review` as r WHERE r.fk_academy=koodo_academy.pk_id and r.type=4 and r.status=1) as count';

                    $table = "academy";
                    $select = "academy.pk_id,coach_name,s.sportname,start_date,end_date,student_number,academy_time,fees,venue,description,UA.email,UA.mob,academy.img,user_id,primary_mobile_no,COALESCE(secondary_mobile_no,'') as secondary_mobile_no,academy.email,academy.website,location,COALESCE(DATE_ADD(end_date, INTERVAL 3 MONTH),'0' )as expire_date".$subquery.$subquery2;
                    $this->db->order_by("FIELD(user_id,$uid ) DESC");
                    // if (!empty($value['end_date'])) {
                    //     $this->db->where('DATE_ADD(`end_date`, INTERVAL 3 MONTH) >=',date('Y-m-d'));
                    // }
                    $this->db->join('sport as s','s.pk_id = academy.sport_type');
                    $this->db->join('user as UA', 'UA.pk_id = academy.user_id');
                    $this->db->join('user_review as UR', 'UR.fk_academy = academy.pk_id','LEFT');
                    $this->db->where('academy.status','1');
                    $this->db->where('UA.status','1');
                    if (!empty($sportid)){
                        $this->db->where('academy.sport_type',$sportid);
                    }                 
                    if (!empty($user_id)){
                       // $condition['user_id']=$user_id;
                       $this->db->where('academy.user_id',$user_id);
                    }else{
                       $this->db->where('academy.user_id!=',$uid);

                    }
                    if (!empty($search)){ 
                        $this->db->where("academy.coach_name LIKE '%$search%'");
                    }                       
                    $academyDetail= $this->Md_database->getData($table, $select, '', 'academy.pk_id DESC', '');
                    // $academyDetail= $academyDetail1;
                // }
                   
                // echo "<pre>";
                // print_r($academyDetail);die();
                foreach ($academyDetail as $key => $value){
                    $id= !empty($value['user_id'])?$value['user_id']:'';
                    $pk_id= !empty($value['pk_id'])?$value['pk_id']:'';

                    $table = "academy";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id' => $pk_id);
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
                    $value['distance']="0";
                    if (!empty($longitudeFrom) && !empty($longitudeTo) &&!empty($latitudeFrom) && !empty($latitudeTo)) {
                        $theta = $longitudeFrom - $longitudeTo;
                        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;

                        $distance = ($miles * 1.609344);
                        $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';
                    }

                    //privilege Status
                    $table = "privileges_notifications";
                    $orderby = 'pk_id DESC';
                    $condition = array('fk_uid' => $id);
                    $col = array('display_profile','available','notifications','chat_notification','location');
                    $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                    $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                    $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';

                    //Friend Request Status
                    $table = "friends";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('request_status');
                    $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';
                    
                    //Email and Mobile Status
                    $table = "user";
                    $orderby = 'pk_id DESC';
                    $condition = array('pk_id' => $id);
                    $col = array('emailStatus,mobStatus');
                    $hide_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['emailStatus'] =!empty($hide_status[0]['emailStatus'])?$hide_status[0]['emailStatus']:'';
                    $value['mobStatus'] =!empty($hide_status[0]['mobStatus'])?$hide_status[0]['mobStatus']:'';

                    //Friend Favourite Status     
                    $table = "friends_favourite";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('favourite_status');
                    $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';
                          // print_r( $value['favourite_status']);

                    //Profile Usertype Player,Coach,Other
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

                    $academy_time=!empty($value['academy_time'])?date('H:i A',strtotime($value['academy_time'])):'';
                    $start_date=!empty($value['start_date'])?date('j M ',strtotime($value['start_date'])):'';
                    $end_date=!empty($value['end_date'])?date('j M Y',strtotime($value['end_date'])):'';
                    if (!empty($value['end_date'])) {
                        # code...
                    $expireDate = date('j M Y', strtotime("+3 months", strtotime($value['end_date'])));
                    }else{
                         $expireDate="";
                    }
                    $value['expire_date']=$expireDate; 
  
                    $today =date('j M Y');
                    if ( !empty($end_date) && strtotime($end_date) < strtotime($today)){
                        $value['expire_status']='expire';
                    }else{
                     $value['expire_status']=''; 
                    }
                 
                    $value['academy_time']=$academy_time;
                    $value['date']=$start_date."- ".$end_date;
                    
                    $academyDetails[]= $value;
                }
                $final_array=array();
                $array =array();
                foreach ($academyDetails as $key => $val) {    
                    $expire_date=date('d-m-Y',strtotime($val['expire_date']));
                    if( $expire_date == '01-01-1970'){
                        array_push($final_array,$val);                     
                    }  
                    if( strtotime($val['expire_date']) > strtotime(date('j M Y')) ){
                        array_push($final_array,$val);                     
                    }
                    else{
                        array_push($array,$val);  
                    }
                }
                $resultarray = array('error_code' => '1', 'message' => 'Academy List','academy_list' => $final_array,'img_path' => base_url().'uploads/academy/');
                echo json_encode($resultarray);
                exit();              

            }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
            }            
    }

    function deleteAcademy(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $academy_id = !empty($this->input->post('academy_id')) ? $this->input->post('academy_id') : '';    
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
            $table= 'academy';
            $updated_data = array(
                'status'=> '3',               
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']                                
            );    
            $condition = array("pk_id" => $academy_id,'user_id'=>$uid);                    
            $result = $this->Md_database->updateData($table, $updated_data,$condition); 
              $resultarray = array('error_code' => '1', 'message' => 'academy delete ');
              echo json_encode($resultarray);
                    exit();                                
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }

}
