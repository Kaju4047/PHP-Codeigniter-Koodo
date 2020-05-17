<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_tournaments extends CI_Controller {


    function __construct() {
        parent::__construct();
    }
    public function addTournaments(){
        $image = !empty($this->input->post('image')) ? $this->input->post('image') : '';
        $name = !empty($this->input->post('tournament_name')) ? $this->input->post('tournament_name') : '';
        $sport = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : '';
        $start_date = !empty($this->input->post('start_date')) ? $this->input->post('start_date') : '';
        $end_date = !empty($this->input->post('end_date')) ? $this->input->post('end_date') : '';
        $noStudent = !empty($this->input->post('no_student')) ? $this->input->post('no_student') : '';
        $venue = !empty($this->input->post('venue')) ? $this->input->post('venue') : '1';
        $entryForm = !empty($this->input->post('entry_form')) ? $this->input->post('entry_form') : '';
        $draws_doc = !empty($this->input->post('draws_doc')) ? $this->input->post('draws_doc') : '';
        $entryFee = !empty($this->input->post('entry_fee')) ? $this->input->post('entry_fee') : '';
        $pricemoney = !empty($this->input->post('price_money')) ? $this->input->post('price_money') : '';
        $description = !empty($this->input->post('description')) ? $this->input->post('description') : '';
        // $city = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $tournaments_id = !empty($this->input->post('tournament_id')) ? $this->input->post('tournament_id') : '';
        $primary_mobile_no = !empty($this->input->post('primary_mobile_no')) ? $this->input->post('primary_mobile_no') : '';
        $secondary_mobile_no = !empty($this->input->post('secondary_mobile_no')) ? $this->input->post('secondary_mobile_no') : '';
        $email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
        $website = !empty($this->input->post('website')) ? $this->input->post('website') : '';
        $time = !empty($this->input->post('time')) ? $this->input->post('time') : '';

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
                if(empty($name) || empty($sport) || empty($start_date) || empty($end_date)  || empty($noStudent) || empty($venue)|| empty($pricemoney) || empty($description) || empty($entryFee)){
                      $resultarray = array('error_code' => '2', 'message' => 'All Filed required');
                    echo json_encode($resultarray);
                    exit();
                }else{  
                    if (empty($tournaments_id)){
                        $photoDoc = "";
                        if (!empty($_FILES['image']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['image']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['image']['name'];
              
                            $path = "uploads/tournaments/images/";

                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";

                            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname); 
                        }  

                        $photoDoc2 = "";
                        if (!empty($_FILES['entry_form']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['entry_form']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['entry_form']['name'];
                
                            $path = "uploads/tournaments/entryform/";

                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";

                            $photoDoc2 = $this->Md_database->uploadFile($path, $upload_type, "entry_form", "", $newname); 
                        } 

                        $photoDoc3 = "";
                        if (!empty($_FILES['draws_doc']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['draws_doc']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['draws_doc']['name'];
                
                            $path = "uploads/tournaments/drawsDoc/";

                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";

                            $photoDoc3 = $this->Md_database->uploadFile($path, $upload_type, "draws_doc", "", $newname); 
                        }       
                        $table = "tournaments";
                        $insert_data = array(
                            'name'=> $name,
                            'sport'=> $sport,
                            'start_date'=>$start_date,                        
                            'end_date'=> $end_date,                        
                            'entery_number'=> $noStudent,                      
                            'price_money'=> $pricemoney,                        
                            'primary_mobile_no'=> $primary_mobile_no,                        
                            'secondary_mobile_no'=> $secondary_mobile_no,                        
                            'email'=> $email,                        
                            'website'=> $website,                        
                            'time'=> $time,                        
                            // 'city'=> $city,                        
                            'address'=> $venue,                        
                            'img'=> $photoDoc,                        
                            'entery_fees'=> $entryFee,                        
                            'entry_form'=> $photoDoc2,                        
                            'draws_doc'=> $photoDoc3,                        
                            'description'=> $description,                        
                            'status' => '2',
                            'user_id' => $uid,
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                        $tournaments_id = $this->db->insert_id();
                        if (!empty($tournaments_id)) {
                            //admin notification
                            $table = "user";
                            $orderby = 'pk_id asc';
                            $condition = array('status' => '1', 'pk_id' => $uid);
                            $col = array('pk_id','name');
                            $userName = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                            $table = "admin_notifications";
                            $insert_data = array(
                                'notifications'=> $userName[0]['name'].' added tournament as name '.$name.'.',                        
                                'status' => '1',
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),                
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $resultarray = $this->Md_database->insertData($table, $insert_data);
                        }
                                                                                 
                        $resultarray = array('error_code' => '1','tournaments_id'=>$tournaments_id, 'uid'=>$uid ,'message' => 'Tournaments added successfully');
                        echo json_encode($resultarray);
                        exit();   
                    }else{
                        $update_data = array(
                           'name'=> $name,
                           'sport'=> $sport,
                           'start_date'=>$start_date,                        
                           'end_date'=> $end_date,                        
                           'entery_number'=> $noStudent,                        
                           'price_money'=> $pricemoney,  
                           'primary_mobile_no'=> $primary_mobile_no,                        
                           'secondary_mobile_no'=> $secondary_mobile_no,                        
                           'email'=> $email,                        
                           'website'=> $website,                        
                           'time'=> $time,                       
                           // 'city'=> $city,  
                           'entery_fees'=> $entryFee,                       
                           'address'=> $venue,                                              
                           'description'=> $description,                        
                           'status' => '2',
                           'user_id' => $uid,
                           'updatedBy' => $uid, 
                           'updatedDate' => date('Y-m-d H:i:s'),
                           'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $photoDoc = "";
                        if (!empty($_FILES['image']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['image']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['image']['name'];
                            $path = "uploads/tournaments/images/";
                            if (!is_dir($path)) {
                               mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";
                            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $newname); 
                            $update_data['img']=$photoDoc;   
                        }  
                           
                        $photoDoc2 = "";
                        if (!empty($_FILES['entry_form']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['entry_form']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['entry_form']['name'];
                            $path = "uploads/tournaments/entryform/";
                            if (!is_dir($path)) {
                               mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";
                            $photoDoc2 = $this->Md_database->uploadFile($path, $upload_type, "entry_form", "", $newname); 
                            $update_data['entry_form']=$photoDoc2;   
                        } 
                         $photoDoc3 = "";
                        if (!empty($_FILES['draws_doc']['name'])) {
                            $rename_name = uniqid(); //get file extension:
                            $arr_file_info = pathinfo($_FILES['draws_doc']['name']);
                            $file_extension = $arr_file_info['extension'];
                            $newname = $rename_name . '.' . $file_extension;
                            $old_name = $_FILES['draws_doc']['name'];
                
                            $path = "uploads/tournaments/drawsDoc/";

                            if (!is_dir($path)) {
                                mkdir($path, 0777, true);
                            }
                            $upload_type = "jpg|png|jpeg|pdf|doc|docx";

                            $photoDoc3 = $this->Md_database->uploadFile($path, $upload_type, "draws_doc", "", $newname); 
                            $update_data['draws_doc']=$photoDoc3;   
                        }           
                        $table = "tournaments";
                        
                        $condition = array(
                           'user_id' => $uid,
                           'pk_id'=>$tournaments_id
                        );
                        $resultarray = $this->Md_database->updateData($table,$update_data, $condition);
                                               
                        $resultarray = array('error_code' => '4','message' => 'Tournament  update successfully');
                        echo json_encode($resultarray);
                        exit(); 
                    }                                    
                }         
        }else {
            $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    public function listTournament(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : "";
        $sportid = !empty($this->input->post('sportid')) ? $this->input->post('sportid') : "";
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : "";
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
            $table = "tournaments";
            $select = "tournaments.name,s.sportname,start_date,end_date,entery_number,price_money,tournaments.address,tournaments.img,entry_form,description,UA.email,UA.mob,user_id,draws_doc,primary_mobile_no,secondary_mobile_no,tournaments.email as tournaments_email,website,time,entery_fees";
            $this->db->where("tournaments.name LIKE '%$search%'");  
            $condition = array(
                'tournaments.status' => '1', 
                'UA.status' => '1', 
                'tournaments.sport' =>$sportid               
            );
            if(!empty($user_id)){
                $condition['user_id']=$user_id;
            }else{
                // $condition['user_id']=$user_id;
                $this->db->where("tournaments.user_id!=",$uid); 

            }
            if (!empty($search)) {
                $this->db->where("tournaments.name LIKE '%$search%'");  
            }
            $this->db->having('DATE_ADD(`end_date`, INTERVAL 3 MONTH) >=',date('Y-m-d'));
            $this->db->limit($limit, $offset);
            // $this->db->join('city as c', 'c.pk_id = tournaments.city');    
            $this->db->join('sport as s', 's.pk_id = tournaments.sport');    
            $this->db->join('user as UA', 'UA.pk_id = tournaments.user_id');    
            $tournamentsDetails= $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
            $new_array=array();
            foreach ($tournamentsDetails as $key => $value){
                $id = $value['user_id']; 
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
                // print_r($latitudeTo);
                // print_r($longitudeTo);
                // print_r($latitudeFrom);
                // print_r($latitudeFrom);
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
                if ((!empty($usertype[0]['usertype']) && $usertype[0]['usertype']==3)||(!empty($usertype[1]['usertype']) && $usertype[1]['usertype']==3)||(!empty($usertype[2]['usertype']) && $usertype[2]['usertype']==3)){
                    $value['Other'] ='1' ;
                }else{
                    $value['Other'] ='0' ;
                }

                $start_date=!empty($value['start_date'])?date('j M ',strtotime($value['start_date'])):'';
                $end_date=!empty($value['end_date'])?date('j M Y',strtotime($value['end_date'])):'';
                $expireDate = date('j M Y', strtotime("+3 months", strtotime($value['end_date'])));
                 unset($value['start_date']);
                 unset($value['end_date']);
                 $value['Other'] ='0' ;
                $today =date('j M Y');
                if ( strtotime($end_date) < strtotime($today)){
                    $value['expire_status']='expire';
                }else{
                 $value['expire_status']=''; 
                }             
                $value['date']=$start_date."- ".$end_date;
             
                $new_array[] = $value;
                        // 
            }
            // print_r($new_array);
            // die();

            $resultarray = array('error_code' => '1', 'message' => 'tournaments List','tournamentsDetails' => $new_array,'img_path' => base_url().'uploads/tournaments/images/','entryForm_path'=>base_url().'uploads/tournaments/entryform/','draws_doc'=>base_url().'uploads/tournaments/drawsDoc/');
            echo json_encode($resultarray);
            exit();              

        }else{
              $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
              echo json_encode($resultarray);
              exit();                       
        }            
    }

    function deleteTournaments(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $tournaments_id = !empty($this->input->post('tournaments_id')) ? $this->input->post('tournaments_id') : '';    
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
              $table= 'tournaments';
              $updated_data = array(
                  'status'=> '3',               
                  'updatedBy' => $uid, 
                  'updatedDate' => date('Y-m-d H:i:s'),
                  'updated_ip_address' => $_SERVER['REMOTE_ADDR']                                
              );    
              $condition = array("pk_id" => $tournaments_id,'user_id'=>$uid);                    
              $result = $this->Md_database->updateData($table, $updated_data,$condition); 
              $resultarray = array('error_code' => '1', 'message' => 'tournaments delete ');
              echo json_encode($resultarray);
                    exit();                       
          }else{
              $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
              echo json_encode($resultarray);
                    exit();                       
          }
    }
  
}
