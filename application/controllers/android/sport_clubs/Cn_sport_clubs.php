<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
  class Cn_sport_clubs extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    public function sportClubsList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $name = !empty($this->input->post('name')) ? $this->input->post('name') : '';

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
            $sport_video_list=array();         
            $table = "sports_club";
            $orderby = 'sports_club.pk_id desc';
            $condition = array('sports_club.status' => '1');
            $this->db->limit($limit, $offset);
            if (!empty($name)) {
              
                $this->db->where("koodo_sports_club.name LIKE '%$name%'");                
            }
            $col = array('name','address','email','mobile','website','sport','description','image');
            $sport_club_list = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $resultarray = array('error_code' => '1', 'message' => 'Sport clubs list','sport_club_list'=>$sport_club_list,'club_path'=>base_url() .'uploads/clubs/');
            echo json_encode($resultarray);
            exit();
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
} 