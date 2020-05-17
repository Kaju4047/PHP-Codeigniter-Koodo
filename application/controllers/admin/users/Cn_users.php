<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_users extends CI_Controller {

    public function users_list() {  

        $table = "usertype";
        $select = "*";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'ASC');
        $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['usertypeDetails'] = $usertypeDetails;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
  
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name asc', '');
        $data['cityDetails'] = $cityDetails;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
              $table = "user";
              $select = "user.pk_id,user.name,user.mob,user.regdate,user.email,city.city_name,user.status,koodo_user.createdDate,user.verifyEmail";           
              $this->db->join('city ','user.city = city.pk_id','LEFT'); 
           
              $condition = array(
              // 'user.status !=' => '3',
              );

        $userDetails1 = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
          
          $userDetails=array();
           foreach ($userDetails1 as $userDetails)
          {
              $uid= $userDetails['pk_id'];

              $table = "profile_type";
              $select = "usertype.usertype,profile_type.usertype as userid";
              $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
              $this->db->join('usertype','profile_type.usertype = usertype.pk_id');
              $this->db->distinct();
              $condition = array(
              'UA.status !=' => '3',
              'profile_type.user_id'=>$uid,
              );
              $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');


              $table = "user_profile_detail";
              $select = "club_detail";                      
              $condition = array(
              'user_id'=>$uid,
              'usertype'=>'1',
              );
              $player_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

              $table = "user_profile_detail";
              $select = "club_detail";                      
              $condition = array(
              'user_id'=>$uid,
              'usertype'=>'2',
              );
              $coach_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

              $userDetails[]=$player_club;
              $userDetails[]=$coach_club;
              $addmoredetails[]=$userDetails;

          }
 
          $data['addmoredetails'] = $addmoredetails;

          $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
          $data['totalcount']=!empty($total_records) ? $total_records : '0';
          if ($total_records > 0){
              $this->db->limit($limit_per_page,$page * $limit_per_page);
              $table = "user";
              $select = "user.pk_id,user.name,user.mob,user.regdate,user.email,city.city_name,user.status,koodo_user.createdDate,user.verifyEmail";           
              $this->db->join('city ','user.city = city.pk_id','LEFT'); 
              $condition = array(
                  // 'user.status !=' => '3',
              );
              $userDetails1 = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
              $userDetails=array();
              $addmoredetails=array();
              foreach ($userDetails1 as $userDetails){
                  $uid= $userDetails['pk_id'];
                  $table = "profile_type";
                  $select = "usertype.usertype,profile_type.usertype as userid";
                  $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                  $this->db->join('usertype','profile_type.usertype = usertype.pk_id');    
                  $this->db->distinct();  
                  $condition = array(
                      'UA.status !=' => '3',
                      'profile_type.user_id'=>$uid,
                  );
                  
                  $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');

                  $table = "user_profile_detail";
                  $select = "club_detail";                      
                  $condition = array(
                      'user_id'=>$uid,
                      'usertype'=>'1',
                  );
                  $player_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                  $table = "user_profile_detail";
                  $select = "club_detail";                      
                  $condition = array(
                      'user_id'=>$uid,
                      'usertype'=>'2',
                  );
                  $coach_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                  $userDetails[]=$player_club;
                  $userDetails[]=$coach_club;
                  $addmoredetails[]=$userDetails;
              }
              $data['addmoredetails'] = $addmoredetails;

              $params["results"] = $addmoredetails;                          
              // $params["results2"] = $usertype[0];                          
              $config['base_url'] = base_url() . 'admin/users-list';
              $config['total_rows'] = $total_records;
              $config['per_page'] = $limit_per_page;
              $config["uri_segment"] = 3;
              $config['num_links'] = 2;
              $config['use_page_numbers'] = TRUE;
              $config['reuse_query_string'] = TRUE;
              $config['num_tag_open'] = '<li>';
              $config['num_tag_close'] = '</li>';
              $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
              $config['cur_tag_close'] = '</a></li>';
              $config['next_link'] = 'Next';
              $config['prev_link'] = 'Prev';
              $config['next_tag_open'] = '<li class="pg-next">';
              $config['next_tag_close'] = '</li>';
              $config['prev_tag_open'] = '<li class="pg-prev">';
              $config['prev_tag_close'] = '</li>';
              $config['first_tag_open'] = '<li>';
              $config['first_tag_close'] = '</li>';
              $config['last_tag_open'] = '<li>';
              $config['last_tag_close'] = '</li>';
              $this->pagination->initialize($config);
              $params["links"] = $this->pagination->create_links();
        }        
        $data['follow_links']=$params['links'];
        $data['userDetails']= $params["results"] ;

       //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/users/vw_users_list',$data);
    }
    

    public function filterUser(){
        $table = "usertype";
        $select = "*";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'ASC');
        $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['usertypeDetails'] = $usertypeDetails; 


        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['cityDetails'] = $cityDetails;
        $otherid = !empty($this->input->get('otherid')) ? $this->input->get('otherid') : '';
        $pro_player = !empty($this->input->get('pro_player')) ? $this->input->get('pro_player') : '';
       // print_r($pro_player);
       // die();

        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $sport_club = !empty($this->input->get('sport_club')) ? trim($this->input->get('sport_club')): '';
        $city = !empty($this->input->get('city')) ?$this->input->get('city'): '';
        $deleted = !empty($this->input->get('deleted')) ?$this->input->get('deleted'): '';
        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;

        $data['sport_club']=$sport_club;
        $data['city']=$city;
        $data['deleted']=$deleted;
        
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;

        $total_records = "";           
       
        $table = "user";
        $select = "user.pk_id,user.name,user.mob,user.regdate,user.email,city.city_name,user.status,koodo_user.createdDate,user.verifyEmail";   

        $this->db->join('city ','user.city = city.pk_id'); 
        $this->db->join('profile_type','profile_type.user_id = user.pk_id'); 
        $this->db->distinct();
        $condition = array(
            // 'profile_type.usertype'=>$type,
            'profile_type.status'=>1,
            'user.status !=' => '3',
            'date(koodo_user.createdDate)>='=>$fromdatefilter,
            'date(koodo_user.createdDate)<='=>$todatefilter,
        );
        if (!empty($otherid)) {
             $this->db->join('profie_player_sport ','profie_player_sport.user_id = user.pk_id'); 
             $this->db->where('profie_player_sport.sportname',$otherid);
        }
        if (!empty($pro_player)) {
            $this->db->join('profie_player_sport ','profie_player_sport.user_id = user.pk_id'); 
            $this->db->where('profie_player_sport.type','1');
            $this->db->where('profie_player_sport.status','1');
        }

        if(!empty($city)){
            $condition['user.city']=$city;
        }
         if(!empty($type)){
            $condition['profile_type.usertype']=$type;
        }
        if(!empty($deleted) && $deleted == 'Yes'){
           // print_r($deleted);
           //          die();
            $condition['user.status']='3';

        }elseif(!empty($deleted) && $deleted == 'No'){
            // $condition['user.status']='1';
          $this->db->group_start();
            $this->db->where('user.status','1');
            $this->db->or_where('user.status','2');
          $this->db->group_end();
        }
        if(!empty($sport_club)){
            $this->db->join('koodo_user_profile_detail','user_profile_detail.user_id = user.pk_id');
              $this->db->where("koodo_user_profile_detail.club_detail LIKE '%$sport_club%'");
        }
        $userDetails1 = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
// print_r($userDetails1);
// die();

        $user=array();
        foreach ($userDetails1 as $userDetails){
            $uid= $userDetails['pk_id'];
            $table = "profile_type";
            $select = "usertype.usertype";
            $this->db->join('user','profile_type.user_id = user.pk_id');
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                // 'user.status !=' => '3',
                'profile_type.user_id'=>$uid,
                'profile_type.status'=>1,
            );          
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');         
            $user[]=$userDetails;
        }
        
        $data['userDetails'] = $user;

        $total_records=!empty($user) ? count($user) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';

        if ($total_records > 0){
          
            $table = "user";
            $select = "user.pk_id,user.name,user.mob,user.regdate,user.email,city.city_name,user.status,koodo_user.createdDate,user.verifyEmail";           
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $this->db->join('city ','user.city = city.pk_id'); 
            $this->db->join('profile_type','profile_type.user_id = user.pk_id'); 
            $this->db->distinct();
            $condition = array(
                'user.status !=' => '3',
                 'profile_type.status'=>1,
                // 'profile_type.usertype'=>$type,
                'date(koodo_user.createdDate)>='=>$fromdatefilter,
                'date(koodo_user.createdDate)<='=>$todatefilter,
            );
             if (!empty($otherid)) {
                 $this->db->join('profie_player_sport ','profie_player_sport.user_id = user.pk_id'); 
                 $this->db->where('profie_player_sport.sportname',$otherid);
            }

              if (!empty($pro_player)) {
                  $this->db->join('profie_player_sport ','profie_player_sport.user_id = user.pk_id'); 
                  $this->db->where('profie_player_sport.type','1');
                  $this->db->where('profie_player_sport.status','1');
              }

                
             if(!empty($deleted) && $deleted == 'Yes'){
                  $condition['user.status']='3';
              }elseif(!empty($deleted) && $deleted == 'No'){
                  $this->db->group_start();
                    $this->db->where('user.status','1');
                    $this->db->or_where('user.status','2');
                  $this->db->group_end();
              }
            if(!empty($city)){
                $condition['user.city']=$city;
            }
             if(!empty($type)){
            $condition['profile_type.usertype']=$type;
        }
            if(!empty($sport_club)){
                $this->db->join('user_profile_detail','user_profile_detail.user_id = user.pk_id','RIGHT'); 
                $this->db->where("user_profile_detail.club_detail LIKE '%$sport_club%'");
            }
            $userDetails1 = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
            $userDetails=array();
            $addmoredetails=array();
            foreach ($userDetails1 as $userDetails){
                $uid= $userDetails['pk_id'];
                $table = "profile_type";
                $select = "usertype.usertype,profile_type.usertype as userid";
                $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                $this->db->join('usertype','profile_type.usertype = usertype.pk_id');
                $condition = array(
                    'UA.status !=' => '3',
                    'profile_type.user_id'=>$uid,
                    'profile_type.status'=>1,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');
                $table = "user_profile_detail";
                $select = "club_detail";                      
                $condition = array(
                    'user_id'=>$uid,
                    'usertype'=>'1',
                );
                $player_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                $table = "user_profile_detail";
                $select = "club_detail";                      
                $condition = array(
                    'user_id'=>$uid,
                    'usertype'=>'2',
                );
                $coach_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

                $userDetails[]=$player_club;
                $userDetails[]=$coach_club;
                $addmoredetails[]=$userDetails;
            }
            $data['userDetails'] = $addmoredetails;

            $params["results"] = $addmoredetails;                          
            // $params["results2"] = $usertype[0];                          
            $config['base_url'] = base_url() . 'admin/filter-user';
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;
            $config['num_links'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
            $config['cur_tag_close'] = '</a></li>';
            $config['next_link'] = 'Next';
            $config['prev_link'] = 'Prev';
            $config['next_tag_open'] = '<li class="pg-next">';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li class="pg-prev">';
            $config['prev_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $params["links"] = $this->pagination->create_links();
        }        
        $data['follow_links']=$params['links'];
        $data['userDetails']= $params["results"] ;

        //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/users/vw_users_list',$data);
    }

    public function StatusChange($id, $status) {
        $table = "user";
        $user_data = array(
            'status' => $status,
            'updatedDate' => date('Y-m-d H:i:s'),
        );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $user_data, $condition);
       
        $actionMsg = 'Inactive';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/users-list');
    }

    public function userView($pk_id,$user){
        $table = "profile_type";
        $select = "usertype"; 
        $condition = array(
            'status !=' => '3',
            'user_id'=>$pk_id,
        );
        $usertype1 = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['id']=$usertype1[0]['usertype'];
        $usertype= array_column( $usertype1,'usertype');
       
        $table = "user";
        $select = "user.pk_id ,PT.usertype,user.name,user.mob,user.regdate,user.email,user.status,koodo_user.createdDate,usertype.usertype,user.dob,user.address,user.gender,user.age,user.edudetails,user.img,city.city_name,user.occupation,user.doc_verify,user.pk_id";
        $this->db->join('profile_type as PT','PT.user_id = user.pk_id');
        $this->db->join('usertype ','PT.usertype = usertype.pk_id'); 
        $this->db->join('city','user.city = city.pk_id'); 
        $condition = array(
            'user.status !=' => '3',
            'user.pk_id'=>$pk_id,
        );
        $usersDetails = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
     
        $data['usersDetails'] = $usersDetails; 
 
        if (!empty($data['usersDetails'][0]['pk_id'])) {
            $i = $data['usersDetails'][0]['pk_id'];
                if ($pk_id== $i&&$user=='1'){
                    $id = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 0;
                    $table = "user";
                    $select = "user.contact_detail,user.pk_id,PT.usertype";        
                    $condition = array(
                        'user.status !=' => '3',
                        'user.pk_id'=>$pk_id,
                        'PT.status'=>'1',
                    );
                    $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
                    $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
                    $contact_detail = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', ''); 
                    $data['contact_detail'] = $contact_detail;
                    // print_r($contact_detail);
                    // die();
                    $table = "user_review";
                    $select = 'AVG(rate) as average, count(fk_for) as count';        
                    $condition = array(
                        'status' => '1',
                        'fk_for'=>$pk_id,
                        'type'=>'1'                        
                    );
                    $this->db->group_by('fk_for');
                    $rating = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                    $data['rating'] = $rating;


                    $table = "koodo_user_certificate_document";
                    $select = 'type,doc_certificate,file_name';        
                    $condition = array(
                        'status' => '1',
                        'user_id'=>$pk_id,
                        'type'=>'player_certificate'                        
                    );
                    $player_certificate = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                    $data['player_certificate'] = $player_certificate;$table = "koodo_user_certificate_document";

                    $select = 'type,doc_certificate,file_name';        
                    $condition = array(
                        'status' => '1',
                        'user_id'=>$pk_id,
                        'type'=>'player_doc'                        
                    );
                    $player_doc = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                    $data['player_doc'] = $player_doc;

                    $table = "profie_player_sport";
                    $select = "profie_player_sport.pk_id,s.sportname,skill";
                    $this->db->join('sport as s','profie_player_sport.sportname = s.pk_id'); 
                 
                    $condition = array(
                       'profie_player_sport.status !=' => '3',
                        'profie_player_sport.user_id'=>$id,
                        'profie_player_sport.type'=>'1'
                    );
                    $usersPlayerSport = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.primary_id DESC', '');
               
                    $data['usersPlayerSport'] = $usersPlayerSport;    

                    $table = "user_profile_detail";
                    $select = "achivement,user_id,club_detail,skill,doc_verify,experience,about_me,user_id,pk_id,playing_time";
                    $condition = array(
                        'user_profile_detail.status !=' => '3',
                        'user_profile_detail.user_id'=>$id,
                        'user_profile_detail.usertype'=>'1',
                    );
                    $usersPlayerView = $this->Md_database->getData($table, $select, $condition, 'user_profile_detail.pk_id DESC', '');

                    $table = "profile_type";
                    $select = "list_at_top,user_id";
                    $condition = array(
                        'profile_type.user_id'=>$id,
                        'profile_type.usertype'=>'1',
                    );
                    $list_at_top = $this->Md_database->getData($table, $select, $condition, 'profile_type.pk_id DESC', '');
                    $usersPlayerView['list_at_top']= !empty($list_at_top[0]['list_at_top'])?$list_at_top[0]['list_at_top']:'';
                    $usersPlayerView['user_id']= !empty($list_at_top[0]['user_id'])?$list_at_top[0]['user_id']:'';     
                    $data['usersPlayerView'] = $usersPlayerView; 
                    // echo "<pre>";   
                    // print_r($data);
                    // die(); 
                    $this->load->view('admin/users/vw_view_user_player',$data);
                }
        }
        if (!empty($data['usersDetails'][0]['pk_id'])){
            $i = $data['usersDetails'][0]['pk_id'];
            // if ($pk_id== $i && in_array("3", $usertype)&&$user=='3') {
            if ($pk_id== $i&&$user=='3') {
                $id = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 0;
                $table = "profie_player_sport";
                $select = "profie_player_sport.pk_id,s.sportname,profie_player_sport.sportname as otherid";
                $this->db->join('sport as s','profie_player_sport.sportname = s.pk_id'); 
                $condition = array(
                    'profie_player_sport.status !=' => '3',
                    'profie_player_sport.user_id'=>$id,
                    'profie_player_sport.type'=>'3'
                );
                $usersDealer = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
     
                $data['usersDealer'] = $usersDealer; 
                $table = "dealer_service";
                $select = "dealer_service.pk_id,service_name,price,description";
                $this->db->join('profie_player_sport as s','s.user_id = dealer_service.user_id');
                $this->db->where('s.sportname','22');
                $condition = array(
                    'dealer_service.status !=' => '3',
                    'dealer_service.user_id'=>$id,
                );
                $serviceDealer = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
     
                $data['serviceDealer'] = $serviceDealer;    

                $table = "user_review";
                $select = 'AVG(rate) as average, count(fk_for) as count';        
                $condition = array(
                    'status' => '1',
                    'fk_for'=>$pk_id,
                     'type'=>'3'     
                );
                $this->db->group_by('fk_for');
                $rating = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                $data['rating'] = $rating;

                $table = "user";
                $select = "contact_detail,user.pk_id,PT.usertype,view_on_app_list";        
                $condition = array(
                    'user.status !=' => '3',
                    'user.pk_id'=>$pk_id,
                    'PT.status'=>'1',
                );
                $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
                $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
                $contact_detail = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');    
                $data['contact_detail'] = $contact_detail;

                $table = "user_profile_detail";
                

                $select = "achivement,about_me,doc_verify,address,experience,user_id,pk_id,
                website,playing_time,other_email_id,other_city,other_mobile_no,other_alter_mobile_no,other_location,other_company_name,other_clinic_name,other_image,other_consultation_fees";
                $condition = array(
                    'user_profile_detail.status !=' => '3',
                    'user_profile_detail.user_id'=>$id,
                    'user_profile_detail.usertype'=>'3',
                );
                $usersDealerView = $this->Md_database->getData($table, $select, $condition, 'user_profile_detail.pk_id DESC', '');
        
                $table = "profile_type";
                $select = "list_at_top,user_id";
                $condition = array(
                    'profile_type.user_id'=>$id,
                    'profile_type.usertype'=>'3',
                );
                $list_at_top = $this->Md_database->getData($table, $select, $condition, 'profile_type.pk_id DESC', '');
                $usersDealerView['list_at_top']= !empty($list_at_top[0]['list_at_top'])?$list_at_top[0]['list_at_top']:'';
                $usersDealerView['user_id']= !empty($list_at_top[0]['user_id'])?$list_at_top[0]['user_id']:'';
     
                $data['usersDealerView'] = $usersDealerView;


                $table = "koodo_user_certificate_document";
                $select = 'type,doc_certificate,file_name';        
                $condition = array(
                    'status' => '1',
                    'user_id'=>$pk_id,
                    'type'=>'other_certificate'                        
                );
                $other_certificate = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                $data['other_certificate'] = $other_certificate;
                $table = "koodo_user_certificate_document";

                $select = 'type,doc_certificate,file_name';        
                $condition = array(
                    'status' => '1',
                    'user_id'=>$pk_id,
                    'type'=>'other_doc'                        
                );
                $other_doc = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                $data['other_doc'] = $other_doc;

                $this->load->view('admin/users/vw_view_user_others',$data);
            }
        }
        if (!empty($data['usersDetails'][0]['pk_id'])) {
            $i = $data['usersDetails'][0]['pk_id'];
            if ($pk_id== $i &&$user=="2") {
                $id = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 0;
        
                $table = "profie_player_sport";
                $select = "profie_player_sport.pk_id,s.sportname";
                $this->db->join('sport as s','profie_player_sport.sportname = s.pk_id'); 
           
                $condition = array(
                    'profie_player_sport.status !=' => '3',
                    'profie_player_sport.user_id'=>$id,
                    'profie_player_sport.type'=>'2'
                );
                $usersCoachSport = $this->Md_database->getData($table, $select, $condition, 'profie_player_sport.pk_id DESC', '');
         
                $data['usersCoachSport'] = $usersCoachSport;    

                $table = "user_review";
                $select = 'AVG(rate) as average, count(fk_for) as count';        
                $condition = array(
                    'status' => '1',
                    'fk_for'=>$pk_id,
                    'type'=>'2'           
                );
                $this->db->group_by('fk_for');
                $rating = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                $data['rating'] = $rating;

                $table = "user";
                $select = "contact_detail,user.pk_id,PT.usertype,coach_category_level,coach_category";        
                $condition = array(
                    'user.status !=' => '3',
                    'user.pk_id'=>$pk_id,
                    'PT.status'=>'1',
                );
                $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
                $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
                $contact_detail = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');    
                $data['contact_detail'] = $contact_detail;
      
                $table = "dealer_service";
                $select = "pk_id,service_name,price,description";             
                $condition = array(
                    'status !=' => '3',
                    'user_id'=>$id,
                );
                $serviceDealer = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');    
                $data['serviceDealer'] = $serviceDealer;    

                $coachbatchDays=array();
                $table = "coach_batches";
                $select = 'coach_batches.pk_id,DATE_FORMAT(koodo_coach_batches.start_time, "%h:%i %p") as start_time,DATE_FORMAT(koodo_coach_batches.end_time, "%h:%i %p") as end_time,place as city_name,coach_batches.fees,venue,coach_batches.user_id,days,batch_sport,studentNo';  
                $condition = array(
                    'coach_batches.status !=' => '3',
                    'coach_batches.user_id'=>$id,              
                );
                $coachbatchDays = $this->Md_database->getData($table, $select, $condition, 'coach_batches.pk_id DESC', '');

                $data['batches'] = $coachbatchDays;  
                $table = "user_profile_detail";
                $select = "achivement,club_detail,doc_verify,experience,user_id,pk_id,club_technique,playing_time";
                $condition = array(
                    'user_profile_detail.status !=' => '3',
                    'user_profile_detail.user_id'=>$id,
                    'user_profile_detail.usertype'=>'2',
                );
                $usersCoachView = $this->Md_database->getData($table, $select, $condition, 'user_profile_detail.pk_id DESC', '');
           
                $table = "profile_type";
                $select = "list_at_top,user_id";       
                $condition = array(
                    'profile_type.user_id'=>$id,
                    'profile_type.usertype'=>'2',
                );
                $list_at_top = $this->Md_database->getData($table, $select, $condition, 'profile_type.pk_id DESC', '');
                $usersCoachView['list_at_top']= !empty($list_at_top[0]['list_at_top'])?$list_at_top[0]['list_at_top']:'';
                $usersCoachView['user_id']= !empty($list_at_top[0]['user_id'])?$list_at_top[0]['user_id']:'';
         
                $data['usersCoachView'] = $usersCoachView;

                $table = "koodo_user_certificate_document";
                $select = "doc_certificate,file_name";
                $condition = array(
                    'status' => '1',
                    'user_id'=>$id,
                    'type'=>'coach_certificate',
                );
                $coach_certificate = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
             
                $data['coach_certificate'] = $coach_certificate; $table = "koodo_user_certificate_document";
              
                $select = "doc_certificate,file_name";
                $condition = array(
                    'status' => '1',
                    'user_id'=>$id,
                    'type'=>'coach_doc',
                );
                $coach_doc = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
             
                $data['coach_doc'] = $coach_doc;
                  
                $data['usersCoachView'] = $usersCoachView;
                // echo "<pre>";
                // print_r($data);
                // die();
                $this->load->view('admin/users/vw_view_user_coach',$data);
            }
        }
    } 

    public function list_at_top(){
        $cid=!empty($this->input->post('cid'))?$this->input->post('cid'):'2';
        $id=!empty($this->input->post('id'))?$this->input->post('id'):'';       
        $usertype=!empty($this->input->post('usertype'))?$this->input->post('usertype'):'';       
        $update_data = array(
            'list_at_top' => $cid,
            'updatedDate'=>date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata('UID'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
        );
        $condition1 = array("user_id" =>$id ,  'usertype' => $usertype,);
        $ret1 = $this->Md_database->updateData('profile_type', $update_data, $condition1);    
    }
     
    public function doc_verify(){
        $cid=!empty($this->input->post('cid'))?$this->input->post('cid'):'';
        $id = $this->input->post('id');

        $update_data = array(             
            'doc_verify' => $cid,
            'updatedDate'=>date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata('UID'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
        );
        $condition1 = array("pk_id" =>$id );
        $ret1 = $this->Md_database->updateData('user', $update_data, $condition1);     
    }
   
    public function docStatusChange($id,$type,$status) {
        $id = ($this->uri->segment(3)) ? ($this->uri->segment(3)) : 0;
        $type = ($this->uri->segment(4)) ? ($this->uri->segment(4)) : 0;
        
        $table = "usertype";
        $select = "pk_id,usertype";
        $condition = array(
            'status !=' => '3',
            'pk_id'  =>$type,           
        );
        $usertype = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        
        $data['usertype'] = $usertype;

        $table = "user";
        $user_data = array(
            'contact_detail' => $status,
            'updatedDate'=>date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata('UID'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
        );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $user_data, $condition);
        redirect(base_url() . 'admin/user-view/'.$id.'/'.$type);
    }
    public function viewOnAppListStatusChange($id,$status,$sportname) {
        $table = "user";
        $user_data = array(
            'view_on_app_list ' => $status,
            'updatedDate'=>date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata('UID'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
        );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $user_data, $condition);

                    $table = "privileges_notifications";
                    $select = "notifications,chat_notification";
                    $this->db->where('fk_uid',$id);
                    $this->db->order_by('pk_id','ASC');
                    $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

                    $notification=!empty($chechprivilege[0]['notifications'])?$chechprivilege[0]['notifications']:'';
                   
                    if ($notification=='1' ){
                        $table = "user";
                        $select = "token,user.pk_id,name";
                        $this->db->where('pk_id',$id);
                        $this->db->order_by('user.pk_id','ASC');
                        $this->db->distinct();
                        $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                        $target=$order_token[0]['token'];
                
                            if ($status == '1' ) {
                                $message="Your ".$sportname." profile Active from admin";
                            }else{
                                $message="Your ".$sportname." profile Inactive from admin";
                            }
                        }
                        if(!empty($message)){
                            $resultarray = array('message' => $message,'redirect_type' =>'view_on_app_list','subject'=>'Admin Change View Status ');
                                
                            $this->Md_database->sendPushNotification($resultarray,$target);

                            //store into database typewise
                            $table = "custom_notification";
                            $insert_data = array(
                                'from_uid'=>'',
                                'to_user_id'=>$id,
                                'redirect_type' => 'view_on_app_list',
                                'subject' => 'Admin Change View Status',
                                'message'=>$message,
                                'status' => '1',
                                'created_by ' =>$id,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                            );
                            $result = $this->Md_database->insertData($table, $insert_data);
                        }                     
                    
        redirect(base_url() . 'admin/user-view/'.$id.'/'.'3');
    }

    public function add_coach_category(){
        $category_level=!empty($this->input->post('category_level'))?$this->input->post('category_level'):'';
        $category=!empty($this->input->post('category'))?$this->input->post('category'):'';
        $pk_id=!empty($this->input->post('pk_id'))?$this->input->post('pk_id'):'';
        $update_data = array(
            'coach_category_level' => $category_level,
            'coach_category' => $category,
            'updatedDate'=>date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata('UID'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'] 
        );
        $condition1 = array("pk_id" =>$pk_id);
        $ret1 = $this->Md_database->updateData('user', $update_data, $condition1);  
        $data['category_level']=$category_level;
        $data['category']=$category;

        redirect(base_url() . 'admin/user-view/'.$pk_id.'/'.'2');
    }

    public function export_to_excel(){
        $this->load->library('Excel');

        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $sport_club = !empty($this->input->get('sport_club')) ? $this->input->get('sport_club'): '';
        $city = !empty($this->input->get('city')) ?$this->input->get('city'): '';
        
        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['sport_club']=$sport_club;
        $cityName="";
        
        if (!empty($city)) {
            $table = "city";
            $select = "city_name";
            $condition = array(
                'status !=' => '3',
                'pk_id' => $city,
            );
            $cityN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $cityName=$cityN[0]['city_name'];
        }
        $usertype='';
        if (!empty($type)) {
            if ($type==1) {
                $usertype='Player';
            }
            if ($type==2) {
                $usertype='Coach';
            }
            if ($type==3) {
                $usertype='Other';
            }
        }

        $table = "user";
        $select = "user.pk_id,user.name,user.mob,user.regdate,user.email,city.city_name,user.status,koodo_user.createdDate";           
        $this->db->join('city ','user.city = city.pk_id'); 
        $this->db->join('profile_type','profile_type.user_id = user.pk_id'); 
        $this->db->distinct();
        $condition = array(
            'user.status!= ' => '3',
            'profile_type.usertype'=>$type,
            'date(koodo_user.createdDate)>='=>$fromdatefilter,
            'date(koodo_user.createdDate)<='=>$todatefilter,
        );
        if(!empty($city)){
            $condition['user.city']=$city;
        }
        if(!empty($sport_club)){
            $this->db->join('user_profile_detail','user_profile_detail.user_id = user.pk_id','RIGHT'); 
            $this->db->where("user_profile_detail.club_detail LIKE '%$sport_club%'");
        }
        $userDetails1 = $this->Md_database->getData($table, $select, $condition, 'user.pk_id DESC', '');
        $userDetails=array();
        $addmoredetails=array();
        foreach ($userDetails1 as $userDetails){
            $uid= $userDetails['pk_id'];

            $table = "profile_type";
            $select = "usertype.usertype,profile_type.usertype as userid";
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype','profile_type.usertype = usertype.pk_id');
            $condition = array(
                'UA.status !=' => '3',
                'profile_type.user_id'=>$uid,
                'profile_type.status'=>1,
                // 'user'=>1,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');

            $table = "user_profile_detail";
            $select = "club_detail";                      
            $condition = array(
                'user_id'=>$uid,
                'usertype'=>'1',
            );
            $player_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            $table = "user_profile_detail";
            $select = "club_detail";                      
            $condition = array(
                'user_id'=>$uid,
                'usertype'=>'2',
            );
            $coach_club= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
  
            $userDetails[]=$player_club;
            $userDetails[]=$coach_club;
            $addmoredetails[]=$userDetails; 
        }
        $data['userDetails'] = $addmoredetails;
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="User List";
        /*[:: End Collection report excel sheet  Name::]*/
        if (!empty($addmoredetails)) {
            $finalsArray = $addmoredetails;
            $this->excel->getActiveSheet()->setTitle('User List');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
            $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter,'User Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $usertype);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter,'City ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $cityName);
              $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter,'Sport Club');
              $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $sport_club);                      
            $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, ' Reg Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'User Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Mobile No');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Email Id');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'City ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Player Club');
              $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Coach Club');   // venue,sportname
              // set auto size for columns
              $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
   
              $from = "A1"; // or any value
              $to = "P1"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
              $from1 = "A2"; // or any value
              $to1 = "P2"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);

              $date = date('d-m-Y g:iA');
              $cnt = count($finalsArray);
              $counter = 3;

              if (!empty($finalsArray)) {
                  $j = 1;
                  foreach ($finalsArray as $arrayUser) {                       
                      $name = !empty($arrayUser['name']) ? $arrayUser['name'] :'';
                      $mob = !empty($arrayUser['mob']) ? $arrayUser['mob']:'';
                      $city_name = !empty($arrayUser['city_name']) ? ucfirst($arrayUser['city_name']):'-';
                      $email = !empty($arrayUser['email']) ? $arrayUser['email']:'-';
                      $regdate = !empty($arrayUser['regdate']) ? date('d-m-Y h:ia',strtotime($arrayUser['regdate'])):'-';
                      $city_name = !empty($arrayUser['city_name']) ? $arrayUser['city_name']:'';

                      $user_array = array();
                      $usertype2=array_column($arrayUser[0],'usertype');

                      $usertype= implode(",",$usertype2);
                      $player_club =!empty($arrayUser[1][0]['club_detail']) ? ucwords($arrayUser[1][0]['club_detail']) : '-' ;
                      $Coach_club =!empty($arrayUser[2][0]['club_detail']) ? ucwords($arrayUser[2][0]['club_detail']) : '-' ;

                      $this->excel->setActiveSheetIndex(0)
                          ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                          ->setCellValue('B' . $counter, (!empty($regdate) ? $regdate : "-"))
                          ->setCellValue('C' . $counter, (!empty($usertype) ? $usertype : "-"))
                          ->setCellValue('D' . $counter, (!empty($name) ? $name : "-"))
                          ->setCellValue('E' . $counter, (!empty($mob) ? $mob : "-"))
                          ->setCellValue('F' . $counter, (!empty($email) ? $email : ""))
                          ->setCellValue('G' . $counter, (!empty($city_name) ? $city_name : ""))
                          ->setCellValue('H' . $counter, (!empty($player_club) ? $player_club : "-"))
                          ->setCellValue('I' . $counter, (!empty($Coach_club) ? $Coach_club : "-"));  
                          $counter++;
                          $j++;
                  }
                  $this->excel->setActiveSheetIndex(0);
              }
              // Download code for excel
              header('Content-Encoding: UTF-8');
              header('Content-type: text/csv; charset=UTF-8');
              header('Content-Type: application/vnd.ms-excel charset=UTF-8');
              header('Content-Disposition: attachment;filename='.$comm_title.'.xls');
              header('Cache-Control: max-age=0');
              // If you're serving to IE 9, then the following may be needed
              header('Cache-Control: max-age=1');
              //If you're serving to IE over SSL, then the following may be needed
              header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
              header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
              header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
              header('Pragma: public'); // HTTP/1.0
              ob_start();
              $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
              ob_end_clean();
              $objWriter->save('php://output');
              exit;

        }else{
            redirect(base_url() . 'admin/users-list-export-to-excel');
        }
    } 
  /*[End ::  function collection log report export excel :]*/
}
