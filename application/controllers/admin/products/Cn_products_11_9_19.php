<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_products extends CI_Controller {

    public function dealer_products() {
        //dealer data
    	$table = "user";
        $select = "name,pk_id";
       
        $condition = array(
            'status !=' => '3',
            'usertype'=>'2',
        );
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "product_category";
        $select = "category_name,pk_id";
       
        $condition = array(
            'status !=' => '3',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "product_brand";
        $select = "brand_name,pk_id";
       
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['brandDetails'] = $brandDetails;


         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,pb.brand_name,pc.category_name,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'dealer_product.category=pc.pk_id');
        
       
        $condition = array(
            'dealer_product.status !=' => '3',
        );
        $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
        $data['dealarProductDetails'] = $dealarProductDetails;


        $total_records=!empty($dealarProductDetails) ? count($dealarProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) 
        {
          $this->db->limit($limit_per_page,$page * $limit_per_page);


        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,pb.brand_name,pc.category_name,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'dealer_product.category=pc.pk_id');
        
       
        $condition = array(
            'dealer_product.status !=' => '3',
        );
        $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
        $data['dealarProductDetails'] = $dealarProductDetails;
        // echo "<pre>";
        // print_r($data['dealarProductDetails']);
        // die();
        $params["results"] = $dealarProductDetails;             
            $config['base_url'] = base_url() . 'admin/dealer-products';
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
       // echo "<pre>";
       // print_r($data['totalcount']);
       // die();

        $this->load->view('admin/products/vw_dealer_products',$data);
    }
    public function StatusChange($id, $status) {

        $table = "dealer_product";
        $sport_data = array(
            'status' => $status,
             'updatedDate' => date('Y-m-d H:i:s'),
            // 'createdBy' => $this->session->userdata['UID'],
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
       // echo $id;
       // die();
    
      
        $table = "dealer_product";
        $select = "dealer_product.img,dealer_product.product_name,dealer_product.brand_name,dealer_product.category,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $condition = array('dealer_product.status!=' => '3',
            'dealer_product.pk_id'=>$id,
          );
         // $this->db->where('pk_id', $this->input->get('id'));
      
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        // print_r( $ArrayView );
        // die();
       
        echo json_encode($ArrayView);
        exit();
    }
    public function filterdDealer(){

    	$table = "user";
        $select = "name,pk_id";
       
        $condition = array(
            'status !=' => '3',
            'usertype'=>'2',
        );
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "product_category";
        $select = "category_name,pk_id";
       
        $condition = array(
            'status !=' => '3',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "product_brand";
        $select = "brand_name,pk_id";
       
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['brandDetails'] = $brandDetails;
    	 //    echo "string";;
      // exit();


         $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
         $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
         $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

          $data['dealer']=$dealer;
          $data['category']=$category;
          $data['brand']=$brand;
          $data['fromdatefilter']=$fromdatefilter;
           $data['todatefilter']=$todatefilter;
       //  print_r($data['dealer']);
       //  print_r( $data['category']);
       // print_r( $data['brand']);
       //  print_r( $data['fromdatefilter']);
       //  print_r( $data['todatefilter']);     
       // exit();
        $table = "dealer_product";
        $select = "dealer_product.pk_id,dealer_product.createdDate,dealer_product.img,dealer_product.product_name,pb.brand_name,pc.category_name,dealer_product.mrp,dealer_product.cost,dealer_product.description,u.name,u.mob,u.email,u.address,dealer_product.status";

        $this->db->join('user as u', 'dealer_product.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'dealer_product.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'dealer_product.category=pc.pk_id'); 
        $condition = array(
            'dealer_product.status !=' => '3',
        );    

         if(!empty($fromdatefilter)){
           // echo $datefilter;
           //  exit();
            $condition['date(temp_dealer_product.createdDate)>=']=$fromdatefilter;
          }
           if(!empty($todatefilter)){
           // echo $datefilter;
           //  exit();
            $condition['date(temp_dealer_product.createdDate)<=']=$todatefilter;
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

        $dealarProductDetails = $this->Md_database->getData($table, $select, $condition, 'dealer_product.pk_id DESC', '');
         $data['dealarProductDetails'] = $dealarProductDetails;
        // echo "<pre>";
        //   print_r($data['dealarProductDetails']);
        // die(); 

        // print_r($condition);
        // die();
      $this->load->view('admin/products/vw_dealer_products',$data);
    }


     public function used_products() {
     	 //dealer data
    	$table = "user";
        $select = "name,pk_id";
       
        $condition = array(
            'status !=' => '3',
            'usertype'=>'2',
        );
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "product_category";
        $select = "category_name,pk_id";
       
        $condition = array(
            'status !=' => '3',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "product_brand";
        $select = "brand_name,pk_id";
       
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['brandDetails'] = $brandDetails;
        // print_r($data['brandDetails']);  
        // die(); 	
        


        //start:: pagination::- 
         $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "product_used";
        $select = "product_used.pk_id,product_used.createdDate,product_used.img,product_used.product_name,pb.brand_name,pc.category_name,product_used.cost,product_used.description,u.name,u.mob,u.email,u.address,product_used.status";

        $this->db->join('user as u', 'product_used.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'product_used.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'product_used.category=pc.pk_id');
        
       
        $condition = array(
            'product_used.status !=' => '3',
        );
        $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'product_used.pk_id DESC', '');


        $total_records=!empty($usedProductDetails) ? count($usedProductDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) 
        {
          $this->db->limit($limit_per_page,$page * $limit_per_page);
        $table = "product_used";
        $select = "product_used.pk_id,product_used.createdDate,product_used.img,product_used.product_name,pb.brand_name,pc.category_name,product_used.cost,product_used.description,u.name,u.mob,u.email,u.address,product_used.status";

        $this->db->join('user as u', 'product_used.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'product_used.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'product_used.category=pc.pk_id');
        
       
        $condition = array(
            'product_used.status !=' => '3',
        );
        $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'product_used.pk_id DESC', '');
        $data['usedProductDetails'] = $usedProductDetails;
        // echo "<pre>";
        // print_r($data['usedProductDetails']);
        // die();
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
       // echo "<pre>";
       // print_r($data['totalcount']);
       // die();

        $this->load->view('admin/products/vw_used_products',$data);
    }
    public function StatusUsed($id, $status) {
    	// print_r($id);
    	// print_r($status);
    	// die();

        $table = "product_used";
        $sport_data = array(
            'status' => $status,
             'updatedDate' => date('Y-m-d H:i:s'),
            // 'createdBy' => $this->session->userdata['UID'],
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
        redirect(base_url() . 'admin/used-products');
    }
     public function delete_usedProducts($pk_id){

    	  $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret = $this->Md_database->updateData('product_used', $update_data, $condition);
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "advertisement details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);    
       }
    }
     public function viewUsedProduct(){
       $id = $this->input->get('id');
       // echo $id;
       // die();
  
        $table = "product_used";
        $select = "product_used.pk_id,product_used.createdDate,product_used.img,product_used.product_name,pb.brand_name,pc.category_name,product_used.cost,product_used.description,u.name,u.mob,u.email,u.address,product_used.status";

        $this->db->join('user as u', 'product_used.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'product_used.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'product_used.category=pc.pk_id');
        $condition = array('product_used.status!=' => '3',
            'product_used.pk_id'=>$id,
          );
         // $this->db->where('pk_id', $this->input->get('id'));
      
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        // print_r( $ArrayView );
        // die();
       
        echo json_encode($ArrayView);
        exit();
    }

    public function filterdUsed(){

    	$table = "user";
        $select = "name,pk_id";
       
        $condition = array(
            'status !=' => '3',
            'usertype'=>'2',
        );
        $dealerNameDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['dealerNameDetails'] = $dealerNameDetails;

         //category Data
        $table = "product_category";
        $select = "category_name,pk_id";
       
        $condition = array(
            'status !=' => '3',         
        );
        $categoryDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['categoryDetails'] = $categoryDetails;

        //brand data
        $table = "product_brand";
        $select = "brand_name,pk_id";
       
        $condition = array(
            'status !=' => '3',
        );
        $brandDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['brandDetails'] = $brandDetails;
    	 //    echo "string";;
      // exit();


         $dealer = !empty($this->input->get('dealer')) ? $this->input->get('dealer') : '';
         $category = !empty($this->input->get('category')) ? $this->input->get('category') : '';
         $brand = !empty($this->input->get('brand')) ? $this->input->get('brand') : '';
       
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

          $data['dealer']=$dealer;
          $data['category']=$category;
          $data['brand']=$brand;
          $data['fromdatefilter']=$fromdatefilter;
           $data['todatefilter']=$todatefilter;
       //  print_r($data['dealer']);
       //  print_r( $data['category']);
       // print_r( $data['brand']);
       //  print_r( $data['fromdatefilter']);
       //  print_r( $data['todatefilter']);     
       // exit();
        $table = "product_used";
        $select = "product_used.pk_id,product_used.createdDate,product_used.img,product_used.product_name,pb.brand_name,pc.category_name,product_used.cost,product_used.description,u.name,u.mob,u.email,u.address,product_used.status";

        $this->db->join('user as u', 'product_used.dealer_id=u.pk_id');
        $this->db->join('product_brand as pb', 'product_used.brand_name=pb.pk_id');
        $this->db->join('product_category as pc', 'product_used.category=pc.pk_id'); 
        $condition = array(
            'product_used.status !=' => '3',
        );    

         if(!empty($fromdatefilter)){
           // echo $datefilter;
           //  exit();
            $condition['date(temp_product_used.createdDate)>=']=$fromdatefilter;
          }
           if(!empty($todatefilter)){
           // echo $datefilter;
           //  exit();
            $condition['date(temp_product_used.createdDate)<=']=$todatefilter;
          }
         
          if(!empty($brand)){
            $condition['product_used.brand_name']=$brand;
          } 
          if(!empty($dealer)){
            $condition['product_used.dealer_id']=$dealer;
          }

          if(!empty($category)){
            $condition['product_used.category']=$category;
          }

        $usedProductDetails = $this->Md_database->getData($table, $select, $condition, 'product_used.pk_id DESC', '');
         $data['usedProductDetails'] = $usedProductDetails;
        // echo "<pre>";
        //   print_r($data['usedProductDetails']);
        // die(); 

        // print_r($condition);
        // die();
        $this->load->view('admin/products/vw_used_products',$data);
    }

}
