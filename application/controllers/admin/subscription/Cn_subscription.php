<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_subscription extends CI_Controller {

    public function subscription() {        
    	$data = array();
        $data['title'] = 'subscription List';

        $data['edit'] = "";
        $edit_id = !empty($this->input->get('edit'))?$this->input->get('edit'):"";
        if (!empty($edit_id)) {
            $id = $edit_id;
            $table = "subscription";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $sportDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($sportDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
						     Sorry, something went wrong.
						</div>');

                redirect(base_url() . 'admin/subscription');
            }
             $data['edit'] = $sportDetails[0];
        }
        
      
        $table = "sport";
        $select = "sportname,pk_id,status";
        $condition = array(
            'status ' => '1',
            'type'=>'1'
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails;
        // print_r($sportDetails);
        // die();

   
        $table = "city";
        $select = "city_name,pk_id,status";
        $condition = array(
            'status ' => '1'
        );
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name asc', '');
        $data['cityDetails'] = $cityDetails;

        $plan = !empty($this->input->get('plan')) ? trim($this->input->get('plan')): '';
        $data['plan']=!empty($this->input->get('plan')) ? trim($this->input->get('plan')): '';

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        
        $table = "subscription";
        $select = "*";
        $condition = array(
            'status !=' => '3'
        );
        if(!empty($plan)){
            $this->db->where("subscription.plan_name LIKE '%$plan%'");              
        }
        $subscriptionDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['subscriptionDetails'] = $subscriptionDetails;

        $total_records=!empty($subscriptionDetails) ? count($subscriptionDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
       
            $table = "subscription";
            $select = "*";
            $condition = array(
                'status !=' => '3'
            );
            if(!empty($plan)){
                $this->db->where("subscription.plan_name LIKE '%$plan%'");               
             }
            $subscriptionDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $data['subscriptionDetails'] = $subscriptionDetails;
            $params["results"] = $subscriptionDetails;             
            $config['base_url'] = base_url() .'admin/subscription';
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
        $data['subscriptionDetails']= $params["results"] ;
       //End:: pagination::- 
       $data['totalcount']=$total_records;
       // echo "<pre>";

        $this->load->view('admin/subscription/vw_subscription',$data);
    }

     public function checkbox(){
        $view_on_android = !empty($this->input->get('view_on_android')) ? trim($this->input->get('view_on_android')): '';
        $category = !empty($this->input->get('category')) ? trim($this->input->get('category')): '';
        $table = "subscription";
        $insert_data = array(
            'sportname' => $sport_name,
            'sportimg' => $photoDoc,
            'type'=>'1',
            'status' => '1',
            'createdBy' => $this->session->userdata['UID'],
            'createdDate' => date('Y-m-d H:i:s'),
            'created_ip_address' => $_SERVER['REMOTE_ADDR']
            
        );
        $result = $this->Md_database->insertData($table, $insert_data);

    }

    public function subscription_action(){
        $plan = !empty($this->input->post('plan')) ? $this->input->post('plan') : '';
        $catlist = !empty($this->input->post('catlist')) ? $this->input->post('catlist') : '';
        $category = !empty($this->input->post('category')) ? $this->input->post('category') : '';
        $listtype = !empty($this->input->post('listtype')) ? $this->input->post('listtype') : '';
        $duration = !empty($this->input->post('duration')) ? $this->input->post('duration') : '';
        $sport = !empty($this->input->post('sport')) ? $this->input->post('sport') : '';
        $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';
        $mrp = !empty($this->input->post('mrp')) ? $this->input->post('mrp') : '';
        $offer = !empty($this->input->post('offer')) ? $this->input->post('offer') : '';
        $desc = !empty($this->input->post('desc')) ? $this->input->post('desc') : '';
     	$txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
        $view_on_android = !empty($this->input->post('view_on_android')) ? $this->input->post('view_on_android') : '';
        // print_r($view_on_android);
        
          // die();
     	if ($plan=="listing-plan") {
            $this->form_validation->set_rules('catlist', 'catlist', 'required|trim');
       	    $this->form_validation->set_rules('listtype', 'listtype', 'required|trim');
       	    $this->form_validation->set_rules('duration', 'Duration', 'required|trim');
            // $this->form_validation->set_rules('sport', 'Sport Name', 'required|trim');
            $this->form_validation->set_rules('city', 'City Name', 'required|trim|max_length[25]');
            $this->form_validation->set_rules('mrp', 'MRP', 'required|trim|max_length[10]');
            $this->form_validation->set_rules('desc', 'Description', 'required|trim|max_length[300]');
            $this->form_validation->set_rules('offer', 'Offer', 'required|trim|max_length[10]');
        }elseif ($plan=="contact-plan") {
          	$this->form_validation->set_rules('duration', 'Duration', 'required|trim');
          	$this->form_validation->set_rules('category', 'category', 'required|trim');
            $this->form_validation->set_rules('mrp', 'MRP', 'required|trim|max_length[10]');
            $this->form_validation->set_rules('desc', 'Description', 'required|trim|max_length[300]');
            $this->form_validation->set_rules('offer', 'Offer', 'required|trim|max_length[10]');          
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
        if ($view_on_android== "on") {
            // print_r("df");die();
                $table1 = "subscription"; 
                $update_checkbox = array(
                    'view_on_android' => 'No',
                    'updatedBy' => $this->session->userdata['UID'],
                    'updatedDate'=> date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                );
                $condition_checkbox = array(
                   
                );
                if (!empty($category)) {
                   $this->db->where('plan','contact-plan');
                }elseif(!empty($catlist)){
                   $this->db->where('category',$catlist);
                }
                $update_id = $this->Md_database->updateData($table1, $update_checkbox, $condition_checkbox);
        }


        if (empty($txtid)) {
            $table = "subscription";
            $insert_data = array(
                'plan' => $plan,
                'category' => !empty($catlist)?$catlist:$category,
                'listtype' => $listtype,
                'duration' => $duration,
                'sport' => $sport,
                'city' => $city,
                'mrp' => $mrp,
                'offer' => $offer,
                'desc' => $desc,
                'status' => '1',
                'createdBy' => $this->session->userdata['UID'],
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']                
            );
            if ($plan=="listing-plan") {
                $insert_data['plan_name'] = 'Listing Plan';
            }elseif($plan=="contact-plan"){
                $insert_data['plan_name'] = 'Contact Detail Plan' ; 
            }  
            if($view_on_android == "on"){
                $insert_data['view_on_android'] = 'Yes';
               
            }elseif ($view_on_android == " ") {
                $insert_data['view_on_android'] = 'No';
            }   
            $result = $this->Md_database->insertData($table, $insert_data);
            $this->session->set_flashdata('success', 'Subscription has been inserted successfully.');
        
            redirect(base_url() . 'admin/subscription');
        }else{
         	// update data code
            $table = "subscription";
            $update_data = array(
                'plan' => $plan,
                'category' => !empty($catlist)?$catlist:$category,
                'listtype' => $listtype,
                'duration' => $duration,
                'sport' => $sport,
                'city' => $city,
                'mrp' => $mrp,
                'offer' => $offer,
                'desc' => $desc,
                'status' => '1',
                'updatedBy' => $this->session->userdata['UID'],
                'updatedDate'=> date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            if ($plan=="listing-plan") {
                $update_data['plan_name'] = 'Listing Plan';
            }elseif($plan=="contact-plan"){
                $update_data['plan_name'] = 'Contact Detail Plan' ; 
            }
             if($view_on_android == "on"){
                $update_data['view_on_android'] = 'Yes';
               
            }elseif ($view_on_android == " ") {
                $update_data['view_on_android'] = 'No';
            }  
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'Subscription has been updated successfully.');        
            redirect(base_url() . 'admin/subscription');
         }
    }

    public function StatusChange($id, $status) {
        $table = "subscription";
        $sport_data = array(
            'status' => $status,
            'updatedDate' => date('Y-m-d H:i:s'),
            );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $sport_data, $condition);
       
        $actionMsg = 'Inactive';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/subscription');
    }    

    public function delete($pk_id) {
        $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret = $this->Md_database->updateData('subscription', $update_data, $condition);
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Subscription details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        
    }

    public function view(){
       $id = $this->input->get('id');
        $table = "subscription";
        $select = "category,duration,mrp,offer,desc,listtype,sportname,city_name,plan";
        $condition = array('subscription.pk_id' => $id );
        $this->db->join('city','subscription.city = city.pk_id','LEFT'); 
        $this->db->join('sport','subscription.sport = sport.pk_id','LEFT');
        $this->db->where('subscription.status<>',3);
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        echo json_encode($ArrayView);
        exit();
    }

    public function buy_subscription(){
        $table = "sport";
        $select = "pk_id,sportname";
        $condition = array(
            'status' => '1',
            'type' => '1',
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails; 

        $table = "user";
        $select = "pk_id,name";
        $condition = array(
            'status <>' => '3',         
        );
        $users = $this->Md_database->getData($table, $select, $condition, 'name asc', '');
        $data['users'] = $users; 

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
        $table ="buy_subscription";
        $select = " buy_subscription.pk_id,buy_subscription.user_id,buy_subscription.plan,buy_subscription.category,buy_subscription.listtype,buy_subscription.sub_id,buy_subscription.expDate,buy_subscription.status,buy_subscription.createdDate,buy_subscription.cost,sport.sportname,city.city_name,user.name,";
        $this->db->join('user as u','buy_subscription.user_id = u.pk_id','LEFT'); 
        $this->db->join('user','buy_subscription.refered_by = user.pk_id','LEFT'); 
        $this->db->join('city','buy_subscription.fk_city = city.pk_id'); 
        $this->db->join('sport','buy_subscription.fk_sport = sport.pk_id'); 
        $condition = array(
            'buy_subscription.status' => '1',
        );
        $buySubDetails = $this->Md_database->getData($table, $select, $condition, 'buy_subscription.pk_id DESC', '');
        // print_r($buySubDetails);die();
        $addmoredetails=array();
        foreach ($buySubDetails as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $this->db->distinct();
            $select = "usertype.usertype,profile_type.usertype as userid";
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
               'UA.status !=' => '3',
               'profile_type.user_id'=>$uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $addmoredetails[]=$userDetails;
        }

        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table ="buy_subscription";
            $select = " buy_subscription.pk_id,buy_subscription.user_id,buy_subscription.plan,buy_subscription.category,buy_subscription.listtype,buy_subscription.sub_id,buy_subscription.expDate,buy_subscription.status,buy_subscription.createdDate,buy_subscription.cost,sport.sportname,city.city_name,user.name,";
            $this->db->join('user as u','buy_subscription.user_id = u.pk_id','LEFT'); 
            $this->db->join('user','buy_subscription.refered_by = user.pk_id','LEFT'); 
            $this->db->join('city','buy_subscription.fk_city = city.pk_id'); 
            $this->db->join('sport','buy_subscription.fk_sport = sport.pk_id'); 
            $condition = array(
                'buy_subscription.status' => '1',
            );
            $buySubDetails = $this->Md_database->getData($table, $select, $condition, 'buy_subscription.pk_id DESC', '');
            $addmoredetails=array();
            foreach ($buySubDetails as $userDetails){
                $uid= $userDetails['user_id'];
                $table = "profile_type";
                $this->db->distinct();
                $select = "usertype.usertype,profile_type.usertype as userid";
                $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                $this->db->join('usertype','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                   'UA.status !=' => '3',
                   'profile_type.user_id'=>$uid,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
                $addmoredetails[]=$userDetails;
            }
            // print_r($addmoredetails);
            // die();
            $params["results"] = $addmoredetails;             
            $config['base_url'] = base_url() . 'admin/buy-subscription';
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
        $data['buySubDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/subscription/vw_buy_subscription',$data);
    }

    public function  viewBuy(){
        $id = $this->input->get('id');
        $table ="buy_subscription";
        $select="buy_subscription.pk_id,u.name,buy_subscription.sub_id,buy_subscription.category,buy_subscription.listtype,buy_subscription.createdDate,buy_subscription.expDate,buy_subscription.cost,buy_subscription.description,u.name,u.mob,u.img,u.email";

        $this->db->join('user as u','buy_subscription.user_id = u.pk_id');      
        $condition = array(
            'buy_subscription.status !=' => '3',
            'buy_subscription.pk_id'=>$id,
        );       
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        echo json_encode($ArrayView);
        exit();
    }
    
    public function filterBuySub(){
        $table = "sport";
        $select = "pk_id,sportname";
        $condition = array(
            'status ' => '1',
              'type' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['sportDetails'] = $sportDetails; 

        $table = "user";
        $select = "pk_id,name";
        $condition = array(
            'status <>' => '3',         
        );
        $this->db->order_by('pk_id', 'DESC');
        $users = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['users'] = $users;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['cityDetails'] = $cityDetails;

        $plan = !empty($this->input->get('plan')) ? $this->input->get('plan') : '';
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $listtype = !empty($this->input->get('listtype')) ? $this->input->get('listtype') : '';
        $sport = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        $refered_by = !empty($this->input->get('refered_by')) ? $this->input->get('refered_by') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';
       
        $data['plan']=$plan;
        $data['category']=$category;
        $data['listtype']=$listtype;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['city']=$city;
        $data['type']=$sport;
        $data['refered_by']=$refered_by;

        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        
        $table ="buy_subscription";
        $select = " buy_subscription.pk_id,buy_subscription.user_id,buy_subscription.plan,buy_subscription.category,buy_subscription.listtype,buy_subscription.sub_id,buy_subscription.expDate,buy_subscription.status,buy_subscription.createdDate,buy_subscription.cost,sport.sportname,city.city_name,user.name,";
        $this->db->join('user as u','buy_subscription.user_id = u.pk_id','LEFT'); 
        $this->db->join('user','buy_subscription.refered_by = user.pk_id','LEFT'); 
        $this->db->join('city','buy_subscription.fk_city = city.pk_id'); 
        $this->db->join('sport','buy_subscription.fk_sport = sport.pk_id'); 
         $condition = array(
            'buy_subscription.status ' => '1',
        );    
        if(!empty($fromdatefilter)){
            $condition['date(koodo_buy_subscription.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_buy_subscription.createdDate)<=']=$todatefilter;
        }
        if(!empty($plan)){
            $condition['buy_subscription.plan']=$plan;
        } 
        if(!empty($listtype)){
            $condition['buy_subscription.listtype']=$listtype;
        }
        if(!empty($category)){
            $condition['buy_subscription.category']=$category;
        }
        if(!empty($sport)){
            $condition['buy_subscription.fk_sport']=$sport;
        }
        if(!empty($city)){
            $condition['buy_subscription.fk_city']=$city;
        }
        if(!empty($refered_by)){
            $condition['buy_subscription.refered_by']=$refered_by;
        }
        $buySubDetails = $this->Md_database->getData($table, $select, $condition, 'buy_subscription.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($buySubDetails as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype,profile_type.usertype as userid";
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
               'UA.status !=' => '3',
               'profile_type.user_id'=>$uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $addmoredetails[]=$userDetails;
        }
        $data['buySubDetails'] = $addmoredetails;

        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);

            $table ="buy_subscription";
            $select = " buy_subscription.pk_id,buy_subscription.user_id,buy_subscription.plan,buy_subscription.category,buy_subscription.listtype,buy_subscription.sub_id,buy_subscription.expDate,buy_subscription.status,buy_subscription.createdDate,buy_subscription.cost,sport.sportname,city.city_name,user.name,";
            $this->db->join('user as u','buy_subscription.user_id = u.pk_id','LEFT'); 
            $this->db->join('user','buy_subscription.refered_by = user.pk_id','LEFT'); 
            $this->db->join('city','buy_subscription.fk_city = city.pk_id'); 
            $this->db->join('sport','buy_subscription.fk_sport = sport.pk_id'); 
             $condition = array(
                'buy_subscription.status ' => '1',
            );    
            if(!empty($fromdatefilter)){
                $condition['date(koodo_buy_subscription.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_buy_subscription.createdDate)<=']=$todatefilter;
            }
            if(!empty($plan)){
                $condition['buy_subscription.plan']=$plan;
            } 
            if(!empty($listtype)){
                $condition['buy_subscription.listtype']=$listtype;
            }
            if(!empty($category)){
                $condition['buy_subscription.category']=$category;
            }
            if(!empty($sport)){
                $condition['buy_subscription.fk_sport']=$sport;
            }
            if(!empty($city)){
                $condition['buy_subscription.fk_city']=$city;
            }
            if(!empty($refered_by)){
                $condition['buy_subscription.refered_by']=$refered_by;
            }
            $buySubDetails = $this->Md_database->getData($table, $select, $condition, 'buy_subscription.pk_id DESC', '');
            $addmoredetails=array();
            foreach ($buySubDetails as $userDetails){
                $uid= $userDetails['user_id'];
                $table = "profile_type";
                $select = "usertype.usertype,profile_type.usertype as userid";
                $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                $this->db->join('usertype','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                   'UA.status !=' => '3',
                   'profile_type.user_id'=>$uid,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
                $addmoredetails[]=$userDetails;
            }
            $data['buySubDetails'] = $addmoredetails;
            $params["results"] = $addmoredetails;             
            $config['base_url'] = base_url() . 'admin/filter-buysub';
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
        $data['buySubDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/subscription/vw_buy_subscription',$data);
    }


    public function export_to_excel(){
        $this->load->library('Excel');
        $plan = !empty($this->input->get('plan')) ? $this->input->get('plan') : '';
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $listtype = !empty($this->input->get('listtype')) ? $this->input->get('listtype') : '';
        $sport = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        // $refered_by = !empty($this->input->get('refered_by')) ? $this->input->get('refered_by') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate'))): '';  
          
        $sportName="";
        if (!empty($sport)){
            $table = "sport";
            $select = "sportname";
            $condition = array(
                'status !=' => '3',
                'pk_id' => $sport,
            );
            $sportN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $sportName=$sportN[0]['sportname'];
        }
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

        $table ="buy_subscription";
        $select = " buy_subscription.pk_id,buy_subscription.user_id,buy_subscription.plan,buy_subscription.category,buy_subscription.listtype,buy_subscription.sub_id,buy_subscription.expDate,buy_subscription.status,buy_subscription.createdDate,buy_subscription.cost,sport.sportname,city.city_name,user.name,";
        $this->db->join('user as u','buy_subscription.user_id = u.pk_id','LEFT'); 
        $this->db->join('user','buy_subscription.refered_by = user.pk_id','LEFT'); 
        $this->db->join('city','buy_subscription.fk_city = city.pk_id'); 
        $this->db->join('sport','buy_subscription.fk_sport = sport.pk_id'); 
        $condition = array(
            'buy_subscription.status ' => '1',
        );    
        if(!empty($fromdatefilter)){
            $condition['date(koodo_buy_subscription.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_buy_subscription.createdDate)<=']=$todatefilter;
        }
        if(!empty($plan)){
            $condition['buy_subscription.plan']=$plan;
        } 
        if(!empty($listtype)){
            $condition['buy_subscription.listtype']=$listtype;
        }
        if(!empty($category)){
            $condition['buy_subscription.category']=$category;
        }
        if(!empty($sport)){
            $condition['buy_subscription.fk_sport']=$sport;
        }
        if(!empty($city)){
            $condition['buy_subscription.fk_city']=$city;
        }
        if(!empty($refered_by)){
            $condition['buy_subscription.refered_by']=$refered_by;
        }
        $buySubDetails = $this->Md_database->getData($table, $select, $condition, 'buy_subscription.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($buySubDetails as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype,profile_type.usertype as userid";
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
               'UA.status !=' => '3',
               'profile_type.user_id'=>$uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $addmoredetails[]=$userDetails;
        }
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="Buy Subscription List";         
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($addmoredetails)) {
            $finalsArray = $addmoredetails;

             
            $this->excel->getActiveSheet()->setTitle('RemarksReport');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
            $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter,'Plan');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $plan);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter,'Listtype ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $listtype);
              $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter,'Category');
              $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $category);
              $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter,'Sport');
              $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, $sportName);
              $this->excel->setActiveSheetIndex(0)->setCellValue('M'.$counter,'City ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('N'.$counter, $cityName);
              // $this->excel->setActiveSheetIndex(0)->setCellValue('O'.$counter,'Refered By ');
              // $this->excel->setActiveSheetIndex(0)->setCellValue('P'.$counter, $refered_by);        
              
            $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Subscription Id');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Subscription Plan');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Category');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Listing Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Cost(Rs.)');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Start Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'User Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'City');
              $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Sport');
              $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Expiry Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Description');
              // $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Refered By');

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
              $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
              // $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
             
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
                        $plan = !empty($arrayUser['plan']) ? $arrayUser['plan'] :'';
                        $category = !empty($arrayUser['category']) ? ucwords($arrayUser['category']):'';
                        $listtype = !empty($arrayUser['listtype']) ? ucfirst($arrayUser['listtype']):'-';
                        $sub_id = !empty($arrayUser['sub_id']) ? $arrayUser['sub_id']:'-';
                        $expDate = !empty($arrayUser['expDate']) ? date('d-m-Y h:ia',strtotime($arrayUser['expDate'])):'-';
                        $cost = !empty($arrayUser['cost']) ? $arrayUser['cost']:'0';
                        $sportname = !empty($arrayUser['sportname']) ? ucwords($arrayUser['sportname']):'-';
                        $city_name =!empty($arrayUser['city_name']) ? ucwords($arrayUser['city_name']) : '-' ;
                        $start_date =!empty($arrayUser['start_date']) ? ucfirst($arrayUser['start_date']) : '-' ;
              
                        $description =!empty($arrayUser['description']) ? ucfirst($arrayUser['description']) : '-' ;
                         

                        $this->excel->setActiveSheetIndex(0)
                              ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                              ->setCellValue('B' . $counter, (!empty($sub_id) ? $sub_id : "-"))
                              ->setCellValue('C' . $counter, (!empty($plan) ? $plan : "-"))
                              ->setCellValue('D' . $counter, (!empty($category) ? $category : "-"))
                              ->setCellValue('E' . $counter, (!empty($listtype) ? $listtype : "-"))
                              ->setCellValue('F' . $counter, (!empty($cost) ? $cost : "0"))
                              ->setCellValue('G' . $counter, (!empty($start_date) ? $start_date : "0"))
                              ->setCellValue('H' . $counter, (!empty($city_name) ? $city_name : "-"))
                              ->setCellValue('I' . $counter, (!empty($sportname) ? $sportname : "0"))
                              ->setCellValue('J' . $counter, (!empty($expDate) ? $expDate : "0"))
                              ->setCellValue('K' . $counter, (!empty($description) ? $description : "-"));
                              // ->setCellValue('K' . $counter, (!empty($refered_by) ? $refered_by : "-"))
                                                              
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
                redirect(base_url() . 'admin/buy-sub-export-to-excel');
          }
    }
    public function subscription_export_to_excel(){
        $this->load->library('Excel');
        $plan = !empty($this->input->get('plan')) ? $this->input->get('plan') : '';
         $data['plan']=$plan;
        $table = "subscription";
        $select = "*";
        $condition = array(
            'subscription.status !=' => '3'
        );
        if(!empty($plan)){
            $this->db->where("subscription.plan_name LIKE '%$plan%'");               
         }
          $this->db->join('city ','subscription.city = city.pk_id','LEFT');
          $this->db->join('sport ','subscription.sport = sport.pk_id','LEFT');
        $subscriptionDetails = $this->Md_database->getData($table, $select, $condition, 'subscription.pk_id DESC', '');
        // echo "<pre>";
        // print_r($subscriptionDetails);
        // die();

        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="Subscription List";
        $date_title ="all_time";
        $user_title ="all";         
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($subscriptionDetails)) {
            $finalsArray = $subscriptionDetails;             
            $this->excel->getActiveSheet()->setTitle('Subscription List');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
              

            $counter = 1; 
               $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Plan');
               $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $plan);
           
              
            $counter = 2;
               $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
               $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Subscription Plan');
               $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Catagory');
               $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Duration');
               $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'MRP');
               $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Offer');
               $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'View on android');
               $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Plan name');
               $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Sport');
               $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'City');
               $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Description');

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
               $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
          
             
             
              $from = "A1"; // or any value
              $to = "L1"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
              $from1 = "A2"; // or any value
              $to1 = "L2"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);
              $date = date('d-m-Y g:i A');
              $cnt = count($finalsArray);
              $counter = 3;

               if (!empty($finalsArray)) {
                    $j = 1;
                    foreach ($finalsArray as $arrayUser) {
                        $plan = !empty($arrayUser['plan']) ? ucwords($arrayUser['plan']) :'';
                        $category = !empty($arrayUser['category']) ? ucwords($arrayUser['category']) :'';                         
                        $duration = !empty($arrayUser['duration']) ? ucwords($arrayUser['duration']):'-';
                        $city = !empty($arrayUser['city']) ? ucwords($arrayUser['city']):'';
                        $mrp = !empty($arrayUser['mrp']) ? $arrayUser['mrp']:'0';
                        $offer = !empty($arrayUser['offer']) ? $arrayUser['offer']:'-';
                        $desc =!empty($arrayUser['desc']) ? ucfirst($arrayUser['desc']) : '-' ;
                        $view_on_android =!empty($arrayUser['view_on_android']) ? ucfirst($arrayUser['view_on_android']) : '-' ;
                        $plan_name =!empty($arrayUser['plan_name']) ? ucfirst($arrayUser['plan_name']) : '-' ;
                        $category =!empty($arrayUser['category']) ? ucfirst($arrayUser['category']) : '-' ;
                        $sport =!empty($arrayUser['sport']) ? ucfirst($arrayUser['sport']) : '-' ;
                        $city =!empty($arrayUser['city_name']) ? ucfirst($arrayUser['city_name']) : '-' ;
                        $sport =!empty($arrayUser['sportname']) ? ucfirst($arrayUser['sportname']) : '-' ;
                        $desc =!empty($arrayUser['desc']) ? ucfirst($arrayUser['desc']) : '-' ;
                    
                        $this->excel->setActiveSheetIndex(0)
                              ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                              ->setCellValue('B' . $counter, (!empty($plan) ? $plan : "-"))
                              ->setCellValue('C' . $counter, (!empty($category) ? $category : "-"))
                              ->setCellValue('D' . $counter, (!empty($duration) ? $duration : "-"))
                              ->setCellValue('E' . $counter, (!empty($mrp) ? $mrp : "-"))
                              ->setCellValue('F' . $counter, (!empty($offer) ? $offer : "0"))
                              ->setCellValue('G' . $counter, (!empty($view_on_android) ? $view_on_android : "0"))
                              ->setCellValue('H' . $counter, (!empty($plan_name) ? $plan_name : "0"))
                        
                              ->setCellValue('I' . $counter, (!empty($sport) ? $sport : "0"))
                              ->setCellValue('J' . $counter, (!empty($city) ? $city : "0"))
                              ->setCellValue('K' . $counter, (!empty($desc) ? $desc : "0"));
                        

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
                redirect(base_url() . 'admin/export-to-excel');
          }
    }
    public function checkExistPlan(){
        $plan='';$category='';$listtype='';$sport='';$city='';$duration='';$catlist='';$pk_id='';
       
        $plan= $this->input->post('plan');
        $catlist= $this->input->post('catlist');
        $catagorycon= $this->input->post('catagorycon');
        $pk_id= $this->input->post('pk_id');
        $listtype= $this->input->post('listtype');
        $sport= $this->input->post('sport');
        $city= $this->input->post('city');
        $duration= $this->input->post('planmonths');
        // print_r($plan);
        // print_r($duration);
        // print_r($catagorycon);
        // die();

            $select= 'pk_id';
            $table= 'subscription';
            $this->db->where('status!=', 3);
        if(!empty($pk_id)){
            $this->db->where('pk_id!=', $pk_id);
            if (!empty($plan) && $plan == "listing-plan"){
                $this->db->where('category', $catlist);
                $this->db->where('listtype', $listtype);
                $this->db->where('sport', $sport);
                $this->db->where('city', $city);
                $this->db->where('duration', $duration);
                $this->db->where('plan', $plan);
            }elseif(!empty($plan) && $plan == "contact-plan"){
                $this->db->where('duration', $duration);
                 $this->db->where('category', $catagorycon);
                 $this->db->where('plan', $plan);
            }          
              $res = $this->Md_database->getData($table, $select,'', 'pk_id DESC', '');
            
            if(!empty($res)){
                echo json_encode(FALSE);
            }else { 
                echo json_encode(TRUE);
            }
        }else{
            if (!empty($plan) && $plan == "listing-plan"){
                $this->db->where('category', $catlist);
                $this->db->where('listtype', $listtype);
                $this->db->where('sport', $sport);
                $this->db->where('city', $city);
                $this->db->where('duration', $duration);
                $this->db->where('plan', $plan);
            }elseif(!empty($plan) && $plan == "contact-plan"){
                $this->db->where('duration', $duration);
                $this->db->where('category', $catagorycon);
                $this->db->where('plan', $plan);
            }  
            // $this->db->where('status!=', 3);
            $res = $this->Md_database->getData($table, $select,'', 'pk_id DESC', '');
            // print_r($res);
            // die();
            if(!empty($res)){
                // echo json_encode($res);
                // exit();
                echo json_encode(FALSE);
            }
            else{ 
                echo json_encode(TRUE);
            }
        }

    }
}
