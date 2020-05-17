<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_dashboard extends CI_Controller {


    public function index(){
        //Sports Count
	    $table = "sport";
        $select = "sportname,pk_id";
        $condition = array(
            'status !=' => '3',
            'type' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['sportDetails'] = count($sportDetails);
        
        //Pro-Players Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.pk_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.status' => '1',
            'type' => '1',
            'user.status !=' => '3',
        );
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $pro_player = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['pro_player'] = count($pro_player);
       
        //Players Count
        $table = "profile_type";
        $select = "profile_type.pk_id";
        $this->db->distinct();
        $condition = array(
            'profile_type.usertype' => '1',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profile_type.user_id = user.pk_id');
        $this->db->order_by('profile_type.pk_id', 'DESC');
        $player = $this->Md_database->getData($table, $select, $condition, 'profile_type.pk_id DESC', '');
        $data['player'] = count($player)-count($pro_player);

        //Coach Count
        $table = "profile_type";
        $select = "profile_type.pk_id,user_id";
        $this->db->distinct();
        $condition = array(
            'profile_type.status !=' => '3',
            'user.status !=' => '3',
            'profile_type.usertype' => '2',
        );
        $this->db->order_by('profile_type.pk_id', 'DESC');
        $this->db->join('user', 'profile_type.user_id = user.pk_id');
        $coach = $this->Md_database->getData($table, $select, $condition, 'profile_type.pk_id DESC', '');
        $data['coach'] = count($coach);
        // echo "<pre>";
        // print_r($coach );
        // die();
        
        //Tournaments Count
        $table = "tournaments";
        $select = "pk_id";
        $condition = array(
            'status <>' => '3',
            // 'usertype' => '2',
        );
        $this->db->order_by('pk_id', 'DESC');
        $tournaments = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['tournaments'] = count($tournaments);
       
       //Dealers Count
        $table = "profie_player_sport";
        $select = "pk_id";
        $condition = array(
            'status !=' => '3',
            'type' => '3',
            'sportname' => '16',
        );
        $this->db->order_by('pk_id', 'DESC');
        $dealer = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['dealer'] = count($dealer);
        
        //Product Count
        $table = "dealer_product";
        $select = "pk_id";
        $condition = array(
            'status !=' => '3',           
        );
        $this->db->order_by('pk_id', 'DESC');
        $dealer_product = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['dealer_product'] = count($dealer_product)-1;

        //Physicians Count
        $table = "profie_player_sport";
        $select = "pk_id";
        $condition = array(
            'status !=' => '3',
            'type' => '3',
            'sportname' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $Physicians = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['Physicians'] = count($Physicians);
        
        //Physo_Therpist Count
        $table = "profie_player_sport";
        $select = "pk_id";
        $condition = array(
            'status !=' => '3',
            'type' => '3',
            'sportname' => '21',
        );
        $this->db->order_by('pk_id', 'DESC');
        $Physo_Therpist = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['Physo_Therpist'] = count($Physo_Therpist);

        //Dietitians Count
        $table = "profie_player_sport";
        $select = "pk_id";
        $condition = array(
            'status !=' => '3',
            'type' => '3',
            'sportname' => '2',
        );
        $this->db->order_by('pk_id', 'DESC');
        $Dietitians = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['Dietitians'] = count($Dietitians);

        $this->load->view('admin/dashboard/vw_dashboard',$data);
    }
	
}