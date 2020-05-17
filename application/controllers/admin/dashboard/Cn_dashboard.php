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
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '1',
            'profie_player_sport.status' => '1',
            'user.status!=' => '3',
        );
        $this->db->join('user','profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $pro_player = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['pro_player'] = count($pro_player);
       
        //Players Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '1',
            'profie_player_sport.status' => '2',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $player = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['player'] = count($player);

        //Coach Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '2',
            // 'profie_player_sport.status' => '2',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $coach = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['coach'] = count($coach);

        //Tournaments Count
        $table = "tournaments";
        $select = "tournaments.pk_id";
        $condition = array(
            'tournaments.status <>' => '3',
            'user.status <>' => '3',
        );
         $this->db->join('user','tournaments.user_id = user.pk_id');
        $this->db->order_by('pk_id', 'DESC');
        $tournaments = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['tournaments'] = count($tournaments);
       
       //Dealers Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '24',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $dealer = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
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
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '1',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $Physicians = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['Physicians'] = count($Physicians);
       
        //Physo_Therpist Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '2',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $Physo_Therpist = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['Physo_Therpist'] = count($Physo_Therpist);

        //Dietitians Count
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '21',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $Dietitians = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['Dietitians'] = count($Dietitians);

        //Orthopedic
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '16',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        // $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $Orthopedic = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['Orthopedic'] = count($Orthopedic);
        // print_r($data['Orthopedic']);
        // die();

        //Treatment and Spa
        $table = "profie_player_sport";
        $select = "profie_player_sport.user_id";
        $this->db->distinct();
        $condition = array(
            'profie_player_sport.type' => '3',
            'profie_player_sport.sportname' => '22',
            'user.status!=' => '3',
        );
        $this->db->join('user', 'profie_player_sport.user_id = user.pk_id');
        // $this->db->order_by('profie_player_sport.pk_id', 'DESC');
        $Treatment = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
        $data['Treatment'] = count($Treatment);

        $this->load->view('admin/dashboard/vw_dashboard',$data);
    }
	
}