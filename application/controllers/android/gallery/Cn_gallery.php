<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_gallery extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function addImage(){
      	$uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      	$image = !empty($this->input->post('image')) ? $this->input->post('image') : '';
      	$type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
        $number = !empty($this->input->post('number')) ? $this->input->post('number') : '';
    	
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
            $table = "gallery";
            $orderby = 'pk_id asc';
            $condition = array('type' => $type,'user_id' => $uid,'position'=>$number);
            $col = array('pk_id','image');
            $checkImage = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $old_file_name=!empty($checkImage[0]['image'])?$checkImage[0]['image']:'';

            if (!empty($image) || !empty($type)) {
                if (empty($checkImage)) {
                    if (!empty($_FILES['image']['name'])) {
                        $old_name = $_FILES['image']['name'];
                        // print_r($old_name);die();
                        $path = "uploads/gallery/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";
                     
                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $old_name);                         
                    }
                  	$table ="gallery";
                    $inserted_data = array(
                        'image'=> $photoDoc,                                  
                        'user_id'=> $uid,                                  
                        'type'=> $type,                                  
                        'position'=> $number,                                  
                        'status' => '1',   
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']   
                    );     
                    $resultarray = $this->Md_database->insertData($table, $inserted_data, $condition);
                    // $gallary_id = $this->db->insert_id();
                    $resultarray = array('error_code' => '1', 'message' => "Insert Image successfully");
                    echo json_encode($resultarray);
                    exit();
                }else{
                    if (!empty($_FILES['image']['name'])) {
                        // $rename_name = uniqid(); //get file extension:
                        // $arr_file_info = pathinfo($_FILES['image']['name']);
                        // $file_extension = $arr_file_info['extension'];
                        // $newname = $rename_name . '.' . $file_extension;
                        // print_r($newname);die();
                        $old_name = $_FILES['image']['name'];
                        $path = "uploads/gallery/";

                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $upload_type = "jpg|png|jpeg";
                     
                        // $condition = array('status' => '1', 'image' => $old_name);
                        // $col = array('pk_id');
                        // $checkexistImage = $this->Md_database->getData('gallery', $col, $condition, 'pk_id', '');
                        // if (!empty($checkexistImage)) {
                        //   $resultarray = array('error_code' => '4', 'message' => "Image already Exist");
                        //      echo json_encode($resultarray);
                        //      exit();  
                        // }

                        $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "image", "", $old_name);                         
                    }
                    $table ="gallery";
                    $inserted_data = array(
                        'image'=> $photoDoc,
                        'updatedBy' => $uid, 
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']   
                    ); 
                    $condition = array('type' => $type,'user_id' => $uid,'position'=>$number);
                    $resultarray = $this->Md_database->updateData($table, $inserted_data, $condition);
                    if(is_file($old_file_name)){
                        unlink(FCPATH.'uploads/gallery/'.$old_file_name); // delete fil
                    }
                    $resultarray = array('error_code' => '1', 'message' => "Update Image successfully");
                    echo json_encode($resultarray);
                    exit();
                } 
            }else{
                $resultarray = array('error_code' => '3', 'message' => "Image Or type is empty");
                echo json_encode($resultarray);
                exit();    
            }
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function addVideo(){
      	$uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      	$video_link = !empty($this->input->post('video_link')) ? $this->input->post('video_link') : '';
      	$type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
     
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
                if ((!empty($video_link)) && !empty($type)) {
                   	$table ="gallery";
                    $inserted_data = array(
                        'video_link'=> $video_link,                                  
                        'user_id'=> $uid,                                  
                        'type'=> $type,                                  
                        'position'=> '1',                                  
                        'status' => '1',   
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']    
                    );     
                    $resultarray = $this->Md_database->insertData($table, $inserted_data, $condition);
                    $resultarray = array('error_code' => '1', 'message' => "Insert Link successfully");
                    echo json_encode($resultarray);
                    exit(); 
                }else{
                   	$resultarray = array('error_code' => '3', 'message' => "Link Or type is empty");
                    echo json_encode($resultarray);
                    exit();    
                }
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }

    public function galleryImageList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';

        if (!empty($uid) && !empty($type)) {
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
            $table = "gallery";
            $orderby = 'position asc';
            $condition = array('status' => '1', 'user_id' => $uid,'type'=>$type,'image<>'=>'null','image<>'=>' ');
            $col = array('pk_id','image','position');
            $Images = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            $table = "gallery";
            $orderby = 'position asc';
            $condition = array('status' => '1', 'user_id' => $uid,'type'=>$type,'video_link<>'=>'null','video_link<>'=>' ');
            $col = array('pk_id','video_link');
            $video = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $video_link =!empty($video[0]['video_link']) ?$video[0]['video_link']:'';

            $resultarray = array('error_code' => '1', 'message' => 'Images','video'=>$video_link,'images'=>$Images,'img_path' => base_url().'uploads/gallery/',);
            echo json_encode($resultarray);
            exit();
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid or Type is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
}
