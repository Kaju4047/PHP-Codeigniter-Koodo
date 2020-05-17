<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
  class Cn_sport_videos extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    public function sportVideosList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : '';

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
            $table = "sports_videos";
            $orderby = 'sports_videos.pk_id desc';
            $condition = array('sports_videos.status' => '1');
            $this->db->limit($limit, $offset);
            if (!empty($sport_id)) {
                 $this->db->where('sports_videos.type',$sport_id);
            }
            $col = array('sports_videos.pk_id','heading','description','url','sportname','skill_level');
            $this->db->join('sport','sport.pk_id = sports_videos.type');
            $sport_video_list = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $resultarray = array('error_code' => '1', 'message' => 'Sport video list','sport_video_list'=>$sport_video_list);
            echo json_encode($resultarray);
            exit();
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        }
    }
} 