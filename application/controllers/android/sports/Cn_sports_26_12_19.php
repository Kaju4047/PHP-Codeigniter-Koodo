<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
  class Cn_sports extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
  /********************Home Page Web Services ****************************/
  function viewSports(){
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
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
            $data = array();
            $table = "profile_type";
            $orderby = 'usertype asc';
            $condition = array('user_id'=>$uid);
            $col = array('user_id,usertype');             
            $checkProfile = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // print_r($checkProfile);
            // die();

            $table = "profie_player_sport";
            $orderby = 'pk_id desc';
            if ((!empty($checkProfile[0]['usertype']) && $checkProfile[0]['usertype'] ==1 )) {
          
                $condition = array('profie_player_sport.type' =>'1','user_id'=>$uid,'sport.status'=>'1');
            }elseif ( (!empty($checkProfile[0]['usertype']) && $checkProfile[0]['usertype'] ==2 )){
                $condition = array('profie_player_sport.type' =>'2','user_id'=>$uid,'sport.status'=>'1');
            }
            elseif((!empty($checkProfile[0]['usertype']) && $checkProfile[0]['usertype'] ==3 )){
                $condition = array('sport.type' =>'1','sport.status'=>'1');
            }
            $col = array('sport.pk_id,sport.sportname,sport.sportimg');
            $this->db->join('sport', 'profie_player_sport.sportname=sport.pk_id');
            $selectecdSport = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $getSport=array();
            if (!empty($selectecdSport)) {
                foreach ($selectecdSport as $key => $value){
                    $table = "sport";
                    $orderby = 'pk_id desc';
                    $condition = array('type' =>'1','pk_id<>'=>$value['pk_id'],'status'=>1);
                    $col = array('pk_id,sportname','sportimg');
                    $getSport= $this->Md_database->getData($table, $col, $condition,'', '');
                }
                $getSportData=array_merge($selectecdSport,$getSport);
                $tempArr = array_unique(array_column($getSportData, 'pk_id'));
                $getSportData1=array();
                foreach ($tempArr as $key => $value){
                    $table = "sport";
                    $condition = array('type' =>'1','pk_id'=>$value,'status'=>1);
                    $col = array('pk_id,sportname','sportimg');
                    $getSportData2= $this->Md_database->getData($table, $col, $condition,'', '');
                    $getSportData1[]=$getSportData2;
                }
            }else{
                $table = "sport";
                $orderby = 'pk_id desc';
                $condition = array('type' =>'1','status'=>1);
                $col = array('pk_id,sportname','sportimg');
                $getSport = $this->Md_database->getData($table, $col, $condition,'', '');
                $getSportData1[] =$getSport;
            } 
            $table = "custom_notification";
            $orderby = 'pk_id desc';
            $condition = array('status' => '1','to_user_id' => $uid);
            $col = array('pk_id,read_status');
            $read_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $readStatus= !empty($read_status[0]['read_status'])?$read_status[0]['read_status']:''; 
            if (!empty($readStatus)) {       
                if ($readStatus == '1') {
                    $status='read';
                }elseif ($readStatus == '2') {
                    $status='unread';
                }  
           }  
           $resultarray = array('error_code' => '1','getSportData1'=> $getSportData1,'notifcation_readUnread_status'=>!empty($status)?$status:'' ,'path' => base_url().'uploads/master/sportimage/');         
            echo json_encode($resultarray);
            exit();                  	
      }else {
          $table = "sport";
          $orderby = 'pk_id desc';
          $condition = array('type' =>'1','status'=>1);
          $col = array('pk_id,sportname','sportimg');
          $getSport = $this->Md_database->getData($table, $col, $condition,'', '');
                // $getSportData1[]=$getSport;
          $table = "sport";
          $orderby = 'pk_id desc';
          $condition = array('type' =>'1','status'=>1);
          $col = array('pk_id,sportname','sportimg');
          $selectecdSport = $this->Md_database->getData($table, $col, $condition,'', '');
          $getSportData=array_merge($selectecdSport,$getSport);
          $tempArr = array_unique(array_column($getSportData, 'pk_id'));
          foreach ($tempArr as $key => $value){
              $table = "sport";
              $condition = array('type' =>'1','pk_id'=>$value,'status'=>1);
              $col = array('pk_id,sportname','sportimg');
              $getSportData2= $this->Md_database->getData($table, $col, $condition,'', '');
              $getSportData1[]=$getSportData2;
          }
          $resultarray = array('error_code' => '1', 'getSportData1'=> $getSportData1,'path' => base_url().'uploads/master/sportimage/');
          echo json_encode($resultarray);
          exit();                     	
      }    
  }
  
 function listAddview(){    
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      $cityid = !empty($this->input->post('cityid')) ? $this->input->post('cityid') : '';
  	  if (!empty($uid) ) {
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

            if (!empty($cityid)) {
                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'1','city'=>$cityid);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
        
                $col = array('advname','advimg');
                $getAdd1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'2','city'=>$cityid);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'3','city'=>$cityid);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd3 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            }else{
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1','pk_id'=>$uid);
                $col = array('city');
                $city = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $cityiduser=!empty($city[0]['city'])?$city[0]['city']:'';



                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'1','city'=>$cityiduser);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d'));   
                $col = array('advname','advimg');
                $getAdd1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'2','city'=>$cityiduser);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'3','city'=>$cityiduser);
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd3 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            }
               
            $resultarray = array('error_code' => '1','getadvrties1'=> $getAdd1,'getadvrties2'=> $getAdd2,'getadvrties3'=> $getAdd3,'path' => base_url().'uploads/master/advimg/');
            echo json_encode($resultarray);
                    exit();                  	
      }else {
                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'1');
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d'));   
                $col = array('advname','advimg');
                $getAdd1 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'2');
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd2 = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "advertisement";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1','place' =>'3');
                $this->db->where('date(fromdate)<=', date('Y-m-d'));
                $this->db->where('date(todate)>=',date('Y-m-d')); 
                $col = array('advname','advimg');
                $getAdd3 = $this->Md_database->getData($table, $col, $condition, $orderby, '');
          $resultarray = array('error_code' => '1', 'getadvrties1'=> $getAdd1,'getadvrties2'=> $getAdd2,'getadvrties3'=> $getAdd3,'path' => base_url().'uploads/master/advimg/');
          echo json_encode($resultarray);
          exit();                     	
      }  
  }
    public function listIcon(){
        // $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        // if (!empty($uid)) {
        //     $table = "user";
        //     $orderby = 'pk_id asc';
        //     $condition = array('status' => '2', 'pk_id' => $uid);
        //     $col = array('pk_id','name');
        //     $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
        //     if (!empty($checkUser)){
        //         $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
        //         echo json_encode($resultarray);
        //         exit();
        //     }

            $table = "sport";
            $orderby = 'pk_id desc';
            $condition = array('type<>' =>'1');
            $col = array('pk_id,sportname','sportimg');
            $getOtherIcon = $this->Md_database->getData($table, $col, $condition,'', '');
             // print_r($getOtherIcon);
             // die();
            $resultarray = array('error_code' => '1',
                // 'icon_list'=> $getOtherIcon,
                 $getOtherIcon[0]['sportname'] =>$getOtherIcon[0]['sportimg'] ,
                 $getOtherIcon[1]['sportname'] =>$getOtherIcon[1]['sportimg'] ,
                 $getOtherIcon[2]['sportname'] =>$getOtherIcon[2]['sportimg'] ,
                 $getOtherIcon[3]['sportname'] =>$getOtherIcon[3]['sportimg'] ,
                 $getOtherIcon[4]['sportname'] =>$getOtherIcon[4]['sportimg'] ,
                 $getOtherIcon[5]['sportname'] =>$getOtherIcon[5]['sportimg'] ,
                 $getOtherIcon[6]['sportname'] =>$getOtherIcon[6]['sportimg'] ,
                 $getOtherIcon[7]['sportname'] =>$getOtherIcon[7]['sportimg'] ,
                 $getOtherIcon[8]['sportname'] =>$getOtherIcon[8]['sportimg'] ,
                 $getOtherIcon[9]['sportname'] =>$getOtherIcon[9]['sportimg'] ,
                 $getOtherIcon[10]['sportname'] =>$getOtherIcon[10]['sportimg'] ,
                 $getOtherIcon[11]['sportname'] =>$getOtherIcon[11]['sportimg'] ,
                 $getOtherIcon[12]['sportname'] =>$getOtherIcon[12]['sportimg'] ,
                 $getOtherIcon[13]['sportname'] =>$getOtherIcon[13]['sportimg'] ,
                 $getOtherIcon[14]['sportname'] =>$getOtherIcon[14]['sportimg'] ,
                 $getOtherIcon[15]['sportname'] =>$getOtherIcon[15]['sportimg'] ,
                 $getOtherIcon[16]['sportname'] =>$getOtherIcon[16]['sportimg'] ,
                 $getOtherIcon[17]['sportname'] =>$getOtherIcon[17]['sportimg'] ,
                 $getOtherIcon[18]['sportname'] =>$getOtherIcon[18]['sportimg'] ,
                 $getOtherIcon[19]['sportname'] =>$getOtherIcon[19]['sportimg'] ,
                 $getOtherIcon[20]['sportname'] =>$getOtherIcon[20]['sportimg'] ,
                 $getOtherIcon[21]['sportname'] =>$getOtherIcon[21]['sportimg'] ,
                 'path' => base_url().'uploads/master/sportimage/');
            echo json_encode($resultarray);
            exit();                       
        // }else{
        //     $resultarray = array('error_code' => '3', 'message' => 'Uid is empty');
        //     echo json_encode($resultarray);
        //     exit();     
        // } 

    }

} 