<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_products extends CI_Controller {

    public function dealer_products() {
       //  //dealer data     
        $table = "user";
        $select = "name,user.pk_id";       
        $condition = array(
            'user.status !=' => '3',
            'profile_type.usertype'=>'3',
        );
        $this->db->join('profile_type', 'profile_type.user_id=user.pk_id');
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'user.pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "sport";
        $select = "sportname,pk_id";
        $condition = array(
            'status !=' => '3',         
            'type' => '1',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'sportname ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "dealer_product";
        $select = "brand_name,pk_id";
        $this->db->distinct();
        $condition = array(
            'status !=' => '3'
        );
        $this->db->group_by('brand_name');
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'brand_name ASC', '');
        $data['brandDetails'] = $brandDetails;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name DESC', '');
        $data['cityDetails'] = $cityDetails;


        $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

        $data['dealer']=$dealer;
        $data['category']=$category;
        $data['brand']=$brand;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['city']=$city;
    
          //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
        $condition = array(
            'dealer_product.status !=' => '3', 'dealer_product.type'=>'1'
        );    
        $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
        $data['dealarProductDetails'] = $dealarProductDetails;
        $total_records=!empty($dealarProductDetails) ? count($dealarProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "dealer_product";
            $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
              $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT'); 
            $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
            $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
            $condition = array(
                'dealer_product.status !=' => '3', 'dealer_product.type'=>'1'
            );    

            $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
            $data['dealarProductDetails'] = $dealarProductDetails;

            $params["results"] = $dealarProductDetails;             
            $config['base_url'] = base_url() . 'admin/filter-dealerProduct';
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
        $data['dealarProductDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/products/vw_dealer_products',$data);

        // $this->load->view('admin/products/vw_dealer_products',$data);
    }
    public function StatusChange($id, $status) {
        $table = "dealer_product";
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
        redirect(base_url() . 'admin/dealer-products');
    }

    public function delete_dealerProducts($pk_id){
    	  $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret = $this->Md_database->updateData('dealer_product', $update_data, $condition);
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "advertisement details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);    
       }
    }
    
    public function view(){
        $id = $this->input->get('id');  
        $table = "dealer_product";
        $select = "dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address";
        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');        
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id');
        $condition = array('dealer_product.status!=' => '3',
            'dealer_product.pk_id'=>$id 
        );
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
    
        echo json_encode($ArrayView);
        exit();
    }
    public function filterdDealer(){

    	$table = "user";
        $select = "name,user.pk_id";
       
        $condition = array(
            'user.status !=' => '3',
            'profile_type.usertype'=>'3',
        );
           $this->db->join('profile_type', 'profile_type.user_id=user.pk_id');
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'user.name ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "sport";
        $select = "sportname,pk_id";
       
        $condition = array(
            'status !=' => '3',         
            'type' => '1',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'sportname ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "dealer_product";
        $select = "brand_name,pk_id";
        $this->db->distinct();
        $condition = array(
            'status !=' => '3'
        );
        $this->db->group_by('brand_name');
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'brand_name ASC', '');
        $data['brandDetails'] = $brandDetails;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name DESC', '');
        $data['cityDetails'] = $cityDetails;


        $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

        $data['dealer']=$dealer;
        $data['category']=$category;
        $data['brand']=$brand;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['city']=$city;    
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
        $condition = array(
            'dealer_product.status !=' => '3', 'dealer_product.type'=>'1'
        );    

        if(!empty($fromdatefilter)){
            $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
        }
        if(!empty($brand)){
            $condition['dealer_product.brand_name']=$brand;
        } 
        if(!empty($dealer)){
            $condition['dealer_product.dealer_id']=$dealer;
        }
        if(!empty($category)){
            $condition['dealer_product.category']=$category;
        }
        if(!empty($city)){
            $condition['dealer_product.fk_city']=$city;
        }

        $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
        $data['dealarProductDetails'] = $dealarProductDetails;


        $total_records=!empty($dealarProductDetails) ? count($dealarProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "dealer_product";
            $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
              $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT'); 
            $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
            $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
            $condition = array(
                'dealer_product.status !=' => '3', 'dealer_product.type'=>'1'
            );    

            if(!empty($fromdatefilter)){
                $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
            }
            if(!empty($brand)){
                $condition['dealer_product.brand_name']=$brand;
            } 
            if(!empty($dealer)){
                $condition['dealer_product.dealer_id']=$dealer;
            }
            if(!empty($category)){
                $condition['dealer_product.category']=$category;
            }
            if(!empty($city)){
                 $condition['dealer_product.fk_city']=$city;
            }

            $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
            $data['dealarProductDetails'] = $dealarProductDetails;

            $params["results"] = $dealarProductDetails;             
            $config['base_url'] = base_url() . 'admin/filter-dealerProduct';
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
        $data['dealarProductDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/products/vw_dealer_products',$data);
    }


    public function used_products(){
     	 //dealer data
    	  $table = "user";
        $select = "name,user.pk_id";
       
        $condition = array(
            'user.status !=' => '3',
            'profile_type.usertype'=>'3',
        );
        $this->db->join('profile_type', 'profile_type.user_id=user.pk_id');
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'user.pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "sport";
        $select = "sportname,pk_id";
        $condition = array(
            'status !=' => '3',         
            'type' => '1',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'sportname ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "dealer_product";
        $select = "brand_name,pk_id";
        $this->db->group_by('brand_name');
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'brand_name ASC', '');
        $data['brandDetails'] = $brandDetails;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name DESC', '');
        $data['cityDetails'] = $cityDetails; 	
        
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
        $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT');
        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id');
              
        $condition = array(
            'dealer_product.status !=' => '3','dealer_product.type' =>'2' 
        );
        $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');

        $total_records=!empty($usedProductDetails) ? count($usedProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "dealer_product";
            $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
            $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT');
            $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id');
            $condition = array(
                'dealer_product.status !=' => '3','dealer_product.type' =>'2'
            );
            $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
            $data['usedProductDetails'] = $usedProductDetails;

            $params["results"] = $usedProductDetails;             
            $config['base_url'] = base_url() . 'admin/used-products';
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
        $data['usedProductDetails']= $params["results"] ;
       //End:: pagination::- 
       $data['totalcount']=$total_records;

        $this->load->view('admin/products/vw_used_products',$data);
    }
    public function StatusUsed($id, $status) {   	
        $table = "dealer_product";
        $sport_data = array(
            'status' => $status,
            'updatedDate' => date('Y-m-d H:i:s'),
            );
        $condition = array("pk_id" => $id );
        $ret = $this->Md_database->updateData($table, $sport_data, $condition);
       
        $actionMsg = 'Inactive';
        if (!empty($ret)){
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        }else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/used-products');
    }
     public function delete_usedProducts($pk_id){
    	 $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret = $this->Md_database->updateData('dealer_product', $update_data, $condition);
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "advertisement details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);    
       }
    }
    public function viewUsedProduct(){
        $id = $this->input->get('id');  
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";
        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id');
        $condition = array('dealer_product.status!=' => '3',
            'dealer_product.pk_id'=>$id,
          );
              
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';

        echo json_encode($ArrayView);
        exit();
    }

    public function filterdUsed(){
    	  $table = "user";
        $select = "name,user.pk_id";
       
        $condition = array(
            'user.status !=' => '3',
            'profile_type.usertype'=>'3',
        );
        $this->db->join('profile_type', 'profile_type.user_id=user.pk_id');
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'user.name ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

        //category Data
        $table = "sport";
        $select = "sportname,pk_id";
       
        $condition = array(
            'status !=' => '3',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'sportname ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "dealer_product";
        $select = "brand_name,pk_id";
        $this->db->group_by('brand_name');
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'brand_name ASC', '');
        $data['brandDetails'] = $brandDetails;

        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );

        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name DESC', '');
        $data['cityDetails'] = $cityDetails;

        $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
       
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

        $data['dealer']=$dealer;
        $data['category']=$category;
        $data['brand']=$brand;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['city']=$city;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
        $condition = array(
            'dealer_product.status !=' => '3','dealer_product.type' =>'2'
        );    

        if(!empty($fromdatefilter)){
            $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
        }
         
        if(!empty($brand)){
            $condition['dealer_product.brand_name']=$brand;
        } 
        if(!empty($dealer)){
            $condition['dealer_product.dealer_id']=$dealer;
        }
        if(!empty($category)){
            $condition['dealer_product.category']=$category;
        }
        if(!empty($city)){
            $condition['dealer_product.fk_city']=$city;
        }

        $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
        $data['usedProductDetails'] = $usedProductDetails;


        $total_records=!empty($usedProductDetails) ? count($usedProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "dealer_product";
            $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.mrp,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
            $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT');
            $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
            $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
            $condition = array(
                'dealer_product.status !=' => '3','dealer_product.type' =>'2'
            );    

            if(!empty($fromdatefilter)){
                $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
            }
            if(!empty($brand)){
                $condition['dealer_product.brand_name']=$brand;
            } 
            if(!empty($dealer)){
                $condition['dealer_product.dealer_id']=$dealer;
            }
            if(!empty($category)){
                $condition['dealer_product.category']=$category;
            }
            if(!empty($city)){
                $condition['dealer_product.fk_city']=$city;
            }

            $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
            $data['usedProductDetails'] = $usedProductDetails;

            $params["results"] = $usedProductDetails;             
            $config['base_url'] = base_url() . 'admin/filter-usedProduct';
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
        $data['usedProductDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/products/vw_used_products',$data);
    }

    public function export_to_excel($type){
        $this->load->library('Excel');
        $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
        $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
        $city = !empty($this->input->get('city')) ? $this->input->get('city') : '';
        $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
       
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

        $data['dealer']=$dealer;
        $data['category']=$category;
        $data['brand']=$brand;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['city']=$city;
              $sportName="";
        if (!empty($category)) {
            $table = "sport";
            $select = "sportname";
            $condition = array(
                'status !=' => '3',
                'pk_id' => $category,
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
        $dealerName="";
            if (!empty($dealer)) {
                $table = "user";
                $select = "name";
                $condition = array(
                    'status !=' => '3',
                    'pk_id' => $dealer,
                );
                $dealerN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
                $dealerName=$dealerN[0]['name'];
            }
            $brandName="";
            if (!empty($brand)) {
                $table = "dealer_product";
                $select = "brand_name";
                $condition = array(
                    'status !=' => '3',
                    'brand_name' => $brand,
                );
                $brandN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
                $brandName=$brandN[0]['brand_name'];
            }
            $table = "dealer_product";
            $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,dealer_product.mrp";

            $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
            $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
            if ($type == 1) {
                $condition = array(
                'dealer_product.status!=' => '3','dealer_product.type' =>'1'
                ); 
            } 
            if ($type == 2) {
                $condition = array(
                    'dealer_product.status!=' => '3','dealer_product.type' =>'2'
                ); 
            }  

            if(!empty($fromdatefilter)){
                $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
            }
            if(!empty($brand)){
                $condition['dealer_product.brand_name']=$brand;
            } 
            if(!empty($dealer)){
                $condition['dealer_product.dealer_id']=$dealer;
            }
            if(!empty($category)){
                $condition['dealer_product.category']=$category;
            }
            if(!empty($city)){
                $condition['dealer_product.fk_city']=$city;
            }
            $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
            // $data['usedProductDetails'] = $usedProductDetails;

            // $total_records=!empty($usedProductDetails) ? count($usedProductDetails) : '0';
            // $data['totalcount']=!empty($total_records) ? $total_records : '0';
            // if ($total_records > 0){
            //     $table = "dealer_product";
            //     $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,dealer_product.brand_name,pc.sportname,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status,city.city_name";
            //     $this->db->join('city ','dealer_product.fk_city = city.pk_id','LEFT');
            //     $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
            //     // $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
            //     $this->db->join('sport as pc', 'dealer_product.category=pc.pk_id'); 
            //     if ($type == 1) {
            //         $condition = array(
            //             'dealer_product.status !=' => '3','dealer_product.type' =>'1'
            //         );    
            //     }elseif ($type == 2) {
            //         $condition = array(
            //             'dealer_product.status !=' => '3',
            //             'dealer_product.type' =>'2'
            //         );
            //     }
            //     if(!empty($fromdatefilter)){
            //         $condition['date(koodo_dealer_product.createdDate)>=']=$fromdatefilter;
            //     }
            //     if(!empty($todatefilter)){
            //         $condition['date(koodo_dealer_product.createdDate)<=']=$todatefilter;
            //     }
            //     if(!empty($brand)){
            //         $condition['dealer_product.brand_name']=$brand;
            //     } 
            //     if(!empty($dealer)){
            //         $condition['dealer_product.dealer_id']=$dealer;
            //     }
            //     if(!empty($category)){
            //         $condition['dealer_product.category']=$category;
            //     }
            //     if(!empty($city)){
            //         $condition['dealer_product.fk_city']=$city;
            //     }

            //     $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
                $data['usedProductDetails'] = $usedProductDetails;

                /*[:: Start Collection report excel sheet  Name::]*/
                if ($type ==1 ) {
                    $comm_title ="Dealer Product List";
                } 
                if ($type ==2 ) {
                    $comm_title ="Used Product List";
                }                    # code...
                /*[:: End Collection report excel sheet  Name::]*/

                if (!empty($usedProductDetails)) {
                    $finalsArray = $usedProductDetails;
                if ($type ==1 ) {
                    $this->excel->getActiveSheet()->setTitle('Dealer Product List');
                } 
                if ($type ==2 ) {
                    $this->excel->getActiveSheet()->setTitle('Used Product List');
                } 
                // $this->excel->getActiveSheet()->setTitle('User List');
                $date = date('d-m-Y g:iA'); // get current date time
                $cnt = count($finalsArray);
            $counter = 1; 
                $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter,'From Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, $fromdatefilter);
                $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter,'To Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $todatefilter);
                $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Dealer ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $dealerName);
                $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Sport Type');
                $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $sportName);
                // $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter,'City');
                // $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $cityName);
                $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter,'Brand Name ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $brandName);
                   
            $counter = 2;
                $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
                $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Product Name');
                $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Brand Name');
                $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Sport Type');
                $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'MRP');
                $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Cost');
                $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Name ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Mobile ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Email ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Address ');
                $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Description ');
               
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
                $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
              
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
                        $product_name = !empty($arrayUser['product_name']) ? ucfirst($arrayUser['product_name']) :'';
                        $date =  !empty($arrayUser['createdDate'])?date('d-m-Y',strtotime($arrayUser['createdDate'])):'';
                        $brand_name = !empty($arrayUser['brand_name']) ? ucfirst($arrayUser['brand_name']):'';
                        $city_name = !empty($arrayUser['city_name']) ? ucfirst($arrayUser['city_name']):'-';
                        $sportname = !empty($arrayUser['sportname']) ? ucfirst($arrayUser['sportname']):'-';
                        $MRP = !empty($arrayUser['mrp']) ? $arrayUser['mrp']:'0';
                        $cost = !empty($arrayUser['cost']) ? $arrayUser['cost']:'0';
                        $name = !empty($arrayUser['name']) ? ucfirst($arrayUser['name']):'';
                        $mob = !empty($arrayUser['mob']) ? $arrayUser['mob']:'';
                        $email = !empty($arrayUser['email']) ? $arrayUser['email']:'';
                        $address = !empty($arrayUser['address']) ? ucfirst($arrayUser['address']):'';
                        $description = !empty($arrayUser['description']) ? ucfirst($arrayUser['description']):'';

                        $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                            ->setCellValue('B' . $counter, (!empty($date) ? $date : "-"))
                            ->setCellValue('C' . $counter, (!empty($product_name) ? $product_name : "-"))
                            ->setCellValue('D' . $counter, (!empty($brand_name) ? $brand_name : "-"))
                            ->setCellValue('E' . $counter, (!empty($sportname) ? $sportname : "-"))
          
                            ->setCellValue('F' . $counter, (!empty($MRP) ? $MRP : "-"))
                            ->setCellValue('G' . $counter, (!empty($cost) ? $cost : "-"))
                            ->setCellValue('H' . $counter, (!empty($name) ? $name : ""))
                            ->setCellValue('I' . $counter, (!empty($mob) ? $mob : "-"))
                            ->setCellValue('J' . $counter, (!empty($email) ? $email : "-"))
                            ->setCellValue('K' . $counter, (!empty($address) ? $address : "-"))
                            ->setCellValue('L' . $counter, (!empty($description) ? $description : "-"));
                                  
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
                redirect(base_url() . 'admin/product-export-to-excel');
           }
        // }

    }
  /*[End ::  function collection log report export excel :]*/
}
