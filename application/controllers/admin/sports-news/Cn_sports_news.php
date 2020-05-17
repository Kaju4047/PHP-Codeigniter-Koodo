<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_sports_news extends CI_Controller {

    public function sports_news_list() {



        $this->load->view('admin/sports-news/vw_sports_news_list');
    }

    public function add_sports_news() {
        $this->load->view('admin/sports-news/vw_add_sports_news');
    }
    
    public function advertise_with_us() {
    	$search_term = !empty($this->input->get('search_term'))?trim($this->input->get('search_term')):"";
       // print_r("xfv");
       // die();

    	 //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "advertise_enquiry";
        $select="name,mobile_no,email_id,pk_id,description,city,status";
        if(!empty($search_term)){
        	$this->db->group_start();
            $this->db->where("name LIKE '%$search_term%'");
            $this->db->or_where("mobile_no LIKE '%$search_term%'");
            $this->db->or_where("email_id LIKE '%$search_term%'");
            $this->db->or_where("city LIKE '%$search_term%'");
            $this->db->group_end();              
        }   
        $condition = array(
            'status !=' => '3',
        );
        $advertiseEnquiryList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

        $total_records=!empty($advertiseEnquiryList) ? count($advertiseEnquiryList) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	    $table = "advertise_enquiry";
	        $select="name,mobile_no,email_id,pk_id,description,city,status";
	        if(!empty($search_term)){
	        	$this->db->group_start();
	            $this->db->where("name LIKE '%$search_term%'");
	            $this->db->or_where("mobile_no LIKE '%$search_term%'");
	            $this->db->or_where("email_id LIKE '%$search_term%'");
	            $this->db->or_where("city LIKE '%$search_term%'");
	            $this->db->group_end();              
	        }   
	        $condition = array(
	            'status !=' => '3',
	        );
	        $advertiseEnquiryList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            $params["results"] = !empty($advertiseEnquiryList)?$advertiseEnquiryList:'';             
            $config['base_url'] = base_url() . 'admin/advertise-with-us';
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
        $data['advertiseEnquiryList']= $params["results"] ;
       //End:: pagination::- 
         $data['totalcount']=$total_records;
         // print_r($data['advertiseEnquiryList']);
         // die();
        $this->load->view('admin/sports-news/vw_advertise_with_us',$data);
    }
    public function view(){
        $id = $this->input->get('id'); 

        $table = "advertise_enquiry";
        $select = "name,mobile_no,email_id,pk_id,description,city,status";
        $condition = array(
            'status !=' => '3',
            'pk_id'=>$id,
        );
        $advertiseEnquiryDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $ArrayView = !empty($advertiseEnquiryDetails[0])?$advertiseEnquiryDetails[0]:'';
       // print_r($ArrayView);die();
        echo json_encode($ArrayView);
        exit();
    }
}


