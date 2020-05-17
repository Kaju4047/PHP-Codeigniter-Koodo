<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_user_invitation extends CI_Controller {
    
    public function user_invitation_list() {
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
       $search_term = !empty($this->input->get('search_term'))?$this->input->get('search_term'):"";

        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
    	 //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "invitation";
        $select="user.name,user.mob,user.pk_id";
        $this->db->join('user','user.pk_id=invitation.fk_uid');
        $this->db->group_by('fk_uid');
         if(!empty($fromdatefilter)){
        	$condition['date(koodo_invitation.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_invitation.createdDate)<=']=$todatefilter;
        }
        if(!empty($search_term)){
        	// $this->db->like('koodo_user.name',$search_term); 
        	// $this->db->like('koodo_user.mob',$search_term);
        	$this->db->group_start();
            $this->db->where("koodo_user.name LIKE '%$search_term%'");
            $this->db->or_where("koodo_user.mob LIKE '%$search_term%'");
            $this->db->group_end();              
        } 
   
        $condition = array(
            // 'status !=' => '3',
        );
        $invitationList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

        $total_records=!empty($invitationList) ? count($invitationList) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	    $table = "invitation";
	        $select="user.name,user.mob,user.pk_id";
	        $this->db->join('user','user.pk_id=invitation.fk_uid'); 
	        $this->db->group_by('fk_uid');  
	        $condition = array(
	            // 'status !=' => '3',
	        );
	        if(!empty($fromdatefilter)){
            	$condition['date(koodo_invitation.createdDate)>=']=$fromdatefilter;
	        }
	        if(!empty($todatefilter)){
	            $condition['date(koodo_invitation.createdDate)<=']=$todatefilter;
	        }
	        if(!empty($search_term)){
	        	// $this->db->like('koodo_user.name',$search_term); 
	        	// $this->db->like('koodo_user.mob',$search_term);
	        	$this->db->group_start();
	            $this->db->where("koodo_user.name LIKE '%$search_term%'");
	            $this->db->or_where("koodo_user.mob LIKE '%$search_term%'");
	            $this->db->group_end();              
            }
        
	        $invitationList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
	        foreach ($invitationList as $key => $value) {
	        	// print_r($value['pk_id']);
                $count='';
	        	$fk_uid=$value['pk_id'];
		        $table = "invitation";
		        $select="pk_id";
		        $condition = array(
		            'reg_status' => 'registered',
		            'fk_uid' => $fk_uid,
		        );
		        // $this->db->where('fk_uid',$value['fk_uid'])
		        $totalrecord = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
		        $count=count($totalrecord);
	        	// die();

		        $value['count']=$count;
		        $finalarray[]=$value;
	        }
            $params["results"] = !empty($finalarray)?$finalarray:'';             
            $config['base_url'] = base_url() . 'admin/academic-list';
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
        $data['invitationList']= $params["results"] ;
       //End:: pagination::- 
         $data['totalcount']=$total_records;

        $this->load->view('admin/user-invitations/vw_user_invitation_list',$data);
    }
    
    public function view_user_invitation() {
    	 //start:: pagination::- 
    	$id = !empty($this->input->get('id'))?$this->input->get('id'):"";
    	$search_term = !empty($this->input->get('search_term'))?$this->input->get('search_term'):"";
    	$status = !empty($this->input->get('status'))?$this->input->get('status'):"";
    	// print_r($id);
    	// die();
    	$fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';

        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['status']=$status;

        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "invitation";
        $select="invitation.pk_id,mobile,invitee_name,reg_status,invitation.createdDate,user.name";
        $this->db->join('user','user.pk_id=invitation.fk_uid','RIGHT');
        // $this->db->group_by('fk_uid');  
        $condition = array(
             'fk_uid ' => $id,
             'user.pk_id ' => $id,
        );
     	if(!empty($fromdatefilter)){
        	$condition['date(koodo_invitation.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_invitation.createdDate)<=']=$todatefilter;
        }
        if(!empty($status)){
            $condition['koodo_invitation.reg_status']=$status;
        }
       
        if(!empty($search_term)){
	        	// $this->db->like('koodo_user.name',$search_term); 
	        	// $this->db->like('koodo_user.mob',$search_term);
	        	$this->db->group_start();
	            $this->db->where("koodo_invitation.invitee_name LIKE '%$search_term%'");
	            $this->db->or_where("koodo_invitation.mobile LIKE '%$search_term%'");
	            $this->db->group_end();              
            }
        $invitationViewList = $this->Md_database->getData($table, $select, $condition, 'invitation.pk_id DESC', '');
        // print_r($invitationViewList);
        // die();
        $total_records=!empty($invitationViewList) ? count($invitationViewList) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	    $table = "invitation";
	        $select="invitation.pk_id,mobile,invitee_name,reg_status,invitation.createdDate,user.name,fk_uid";
	        $this->db->join('user','user.pk_id=invitation.fk_uid','RIGHT');
	        $condition = array(
	             'fk_uid ' => $id,
	             'user.pk_id ' => $id,
	        );
	        if(!empty($fromdatefilter)){
            	$condition['date(koodo_invitation.createdDate)>=']=$fromdatefilter;
	        }
	        if(!empty($todatefilter)){
	            $condition['date(koodo_invitation.createdDate)<=']=$todatefilter;
	        }
	        if(!empty($status)){
	            $condition['koodo_invitation.reg_status']=$status;
	        }
	        if(!empty($search_term)){
	        	// $this->db->like('koodo_user.name',$search_term); 
	        	// $this->db->like('koodo_user.mob',$search_term);
	        	$this->db->group_start();
	            $this->db->where("koodo_invitation.invitee_name LIKE '%$search_term%'");
	            $this->db->or_where("koodo_invitation.mobile LIKE '%$search_term%'");
	            $this->db->group_end();              
            }
	        $invitationViewList = $this->Md_database->getData($table, $select, $condition, 'invitation.pk_id DESC', '');
	     //    $table = "user";
		    // $select="pk_id,name";
		    // $condition = array(
		    //       'pk_id' => $id,
		    // );
		    // $username = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
		    // $invitationViewList['name']=$username[0]['name'];
	        //     echo "<pre>";
         // print_r($invitationViewList);
         // die(); 
            $params["results"] = $invitationViewList;             
            $config['base_url'] = base_url() . 'admin/view-user-invitation';
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
        $data['invitationViewList']= $params["results"] ;
       //End:: pagination::- 
         $data['totalcount']=$total_records;
         // echo "<pre>";
         // print_r($data['invitationViewList']);
         // die();
        
        $this->load->view('admin/user-invitations/vw_view_user_invitation',$data);
    }

}
