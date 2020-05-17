<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_career extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    public function addCareer(){
    	$profile = !empty($this->input->post('profile')) ? $this->input->post('profile') : '';
    	$qualification = !empty($this->input->post('qualification')) ? $this->input->post('qualification') : '';
    	$exp_salary = !empty($this->input->post('exp_salary')) ? $this->input->post('exp_salary') : '';
        $experience = !empty($this->input->post('experience')) ? $this->input->post('experience') : '';
        $document = !empty($this->input->post('document')) ? $this->input->post('document') : '';
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
            if(empty($profile) || empty($qualification) || empty($exp_salary) ) {
                $resultarray = array('error_code' => '2', 'message' => 'profile or qualification or exp_salary  is empty');
                echo json_encode($resultarray);
                exit();
            }else{
                $table = "career";
                $orderby = 'user_id';
                $condition = array('user_id' => $uid);
                $col = array('pk_id');
                $existuser = $this->Md_database->getData($table, $col, $condition, $orderby, '');   

                if (empty($existuser)) {
                    $inserted_data = array(
                        'profile'=>$profile,
                        'qualification'=>$qualification,
                        'expected_salary'=> $exp_salary,
                        'experience'=> $experience,
                          // 'cv'=>$photoDoc,                                  
                        'status' => 1,   
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR'] ,     
                        'user_id' => $uid,   
                    );  
                    $photoDoc = "";
             
                    if (!empty($_FILES['document']['name'])) {
                       
                        $rename_name3 = uniqid(); //get file extension:
                        $arr_file_info3 = pathinfo($_FILES['document']['name']);
                        $file_extension3 = $arr_file_info3['extension'];
                        $newname3 = $rename_name3 . '.' . $file_extension3;
                         // print_r($newname3);die();
                        $old_name = $_FILES['document']['name'];
                        // print_r($old_name);die();
                        $path3 = "uploads/career";

                        if (!is_dir($path3)) {
                            mkdir($path3, 0777, true);
                        }
                        $upload_type3 = "pdf|doc|docx";
                        $photoDoc3 = $this->Md_database->uploadFile($path3, $upload_type3, "document", "", $newname3);                      
                        $inserted_data['cv']=$photoDoc3 ;
                    } 
                    $resultarray = $this->Md_database->insertData('career',$inserted_data, $condition);
                    $career_id = $this->db->insert_id();
                }else{
                    $inserted_data = array(
                        'profile'=>$profile,
                        'qualification'=>$qualification,
                        'expected_salary'=> $exp_salary,
                        // 'cv'=>$photoDoc,  
                        'experience'=> $experience,                                
                        'status' => 1,   
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR'],  
                        'user_id' => $uid,   
                    );  
                    $photoDoc = "";
                    if (!empty($_FILES['document']['name'])) {
                       
                        $rename_name3 = uniqid(); //get file extension:
                        $arr_file_info3 = pathinfo($_FILES['document']['name']);
                        $file_extension3 = $arr_file_info3['extension'];
                        $newname3 = $rename_name3 . '.' . $file_extension3;
                         // print_r($newname3);die();
                        $old_name = $_FILES['document']['name'];
                        // print_r($old_name);die();
                        $path3 = "uploads/career";

                        if (!is_dir($path3)) {
                            mkdir($path3, 0777, true);
                        }
                        $upload_type3 = "pdf|doc|docx";
                        $photoDoc3 = $this->Md_database->uploadFile($path3, $upload_type3, "document", "", $newname3);                      
                        $inserted_data['cv']=$photoDoc3 ;
                    }   
                    $condition1 = array("user_id" => $uid);
                    $resultarray = $this->Md_database->updateData('career',$inserted_data, $condition1);
                }
                $resultarray = array('error_code' => '1', 'uid'=>$uid,'message' => 'Career data insert successfully');
                echo json_encode($resultarray);
                exit();                     	
            }
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                     	
        }        
    }

    public function  careerList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
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
            $subquery =',(SELECT COALESCE(ROUND(AVG(rate) ,0),0)  FROM `koodo_user_review` as r WHERE r.fk_for=UA.pk_id and r.type=5 and r.status=1) as average';
            $subquery2 =',(SELECT count(fk_for)  FROM `koodo_user_review` as r WHERE r.fk_for=UA.pk_id and r.type=5 and r.status=1) as count';
            $table = "career";
            $select = "career.cv,career.expected_salary,UA.img,UA.name,UA.age,UA.edudetails,UA.address,profile,career.user_id,COALESCE(experience ,'') as experience,UA.email,UA.verifyEmail,UA.doc_verify,UA.pk_id,COALESCE(category,'') as category".$subquery.$subquery2;
            $condition = array(
                'career.status' => '1',                 
                'UA.status' => '1',                 
            );

            if (!empty($search)){ 
                $this->db->where("UA.name LIKE '%$search%'");  
            }
            $this->db->where("UA.pk_id!=",$uid);  

            $this->db->order_by("koodo_buy_subscription.category","DESC");
            // $this->db->order_by("PT.list_at_top","ASC");
            $this->db->order_by("UA.online_date","DESC");

            $this->db->limit($limit, $offset);
            $this->db->join('user as UA', 'UA.pk_id = career.user_id');   
            $this->db->join('buy_subscription','buy_subscription.user_id = UA.pk_id AND buy_subscription.status = 1 AND buy_subscription.listtype = "Career"','LEFT');  
            $careerList1= $this->Md_database->getData($table, $select, $condition, 'career.pk_id DESC', '');

            $careerList = array();
             foreach($careerList1 as $key=>$value){

               if(!isset($careerList[$value['pk_id']])){
                 $careerList[$value['pk_id']] = $value;
               }

             }
             $careerList = array_values($careerList);


            if (!empty($careerList)) {
                foreach ($careerList as $key => $value) {
                    $id = $value['user_id'];

                    $table = "user";
                    $orderby = 'user.pk_id asc';
                    $condition = array('user.pk_id' => $id);
                    $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id','LEFT');
                    $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category');
                    $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['online_status'] = $latlong[0]['online_status'];
                    // $value['doc_verify'] = $latlong[0]['doc_verify'];
                    // $value['category'] = $latlong[0]['category'];

                    if ($value['doc_verify'] =='1' && ($value['email'] == $value['verifyEmail'])){
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
                         

                    $table = "user_profile_detail";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '1','user_id' => $id,'usertype'=>'1');
                    $col = array('pk_id,user_id,usertype,visting_fees,skill,address');
                    $coach = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                                 // print_r($player);
                    $value['visting_fees'] =!empty($coach[0]['visting_fees'])?$coach[0]['visting_fees']:'';
                    $value['skill'] =!empty($coach[0]['skill'])?$coach[0]['skill']:'';
                                  

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
                        // 
                }
            }
            $empty=array();
            $resultarray = array('error_code' => '1', 'message' => 'Career List','career_list' =>  !empty($new_array)?$new_array:$empty,'profile_path' => base_url().'uploads/users/','cv_path' => base_url().'uploads/career/');
            echo json_encode($resultarray);
            exit();               
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }            
    }
    public function deleteCareer(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        // print_r($uid);
        // die();
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
            $table = "career";
            $update_data = array(                        
               'status' => '3',
               'updatedBy' => $uid,
               'updatedDate' => date('Y-m-d H:i:s'),
               'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
            );
            $condition = array(
                'user_id' => $uid,
            );
            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);

            $resultarray = array('error_code' => '1', 'message' => 'Delete Career Successfully');
            echo json_encode($resultarray);
            exit();
                           
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }

    }
}

