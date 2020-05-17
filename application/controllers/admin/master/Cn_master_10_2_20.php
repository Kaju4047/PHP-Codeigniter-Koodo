<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_master extends CI_Controller {

    public function sport() {
    	$data = array();

        $data['title'] = 'Sport List';
        $data['edit'] = "";
        $edit_id = !empty($this->input->get('edit'))?$this->input->get('edit'):"";
        if (!empty($edit_id)) {
            $id = $edit_id;
            $table = "sport";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $sportDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($sportDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
						     Sorry, something went wrong.
						</div>');
                redirect(base_url() . 'admin/sport');
            }
            $data['edit'] = $sportDetails[0];
        }
        $sport = !empty($this->input->get('sport')) ? $this->input->get('sport') : '';
        $data['sport']=$sport;
          //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "sport";
        $select = "*";
        $condition = array(
            'status !=' => '3',
             'type'=>'1',
        );
        if(!empty($sport)){
            $this->db->where("sport.sportname LIKE '%$sport%'");            
        }
        $this->db->order_by('pk_id', 'DESC');
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $total_records=!empty($sportDetails) ? count($sportDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $condition = "";
            $table = "sport";
            $select = "*";
            $condition = array(
                'status !=' => '3',
                'type'=>'1'
            );
            if(!empty($sport)){
                $this->db->where("sport.sportname LIKE '%$sport%'");              
            }
            $this->db->order_by('pk_id', 'DESC');
            $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $data['sportDetails'] = $sportDetails;

            $params["results"] = $sportDetails;             
            $config['base_url'] = base_url() . 'admin/sport';
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
        $data['sportDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/master/vw_sport',$data);
    }

    public function sportAction(){
   	    $sport_name = !empty($this->input->post('sportName')) ? trim($this->input->post('sportName')) : '';
   	    $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
   
   	    $this->form_validation->set_rules('sportName', 'Sport Name', 'required|trim|max_length[25]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        $photoDoc = "";
        if (!empty($_FILES['sportimage']['name'])) {
            $rename_name = uniqid(); //get file extension:
            $arr_file_info = pathinfo($_FILES['sportimage']['name']);
            $file_extension = $arr_file_info['extension'];
            $newname = $rename_name . '.' . $file_extension;
            // print_r($newname);die();
            $old_name = $_FILES['sportimage']['name'];
            // print_r($old_name);die();
            $path = "uploads/master/sportimage/";

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $upload_type = "jpg|png|jpeg";

            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "sportimage", "", $newname);
        }
        $photoDoc = !empty($photoDoc) ? $photoDoc : $this->input->post('fileold');

        if (empty($txtid)) {
            $table = "sport";
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
            $this->session->set_flashdata('success', 'Sport has been inserted successfully.');
            
            redirect(base_url() . 'admin/sport');
        }else{
         	// update data code
            $table = "sport";
            $update_data = array(
                'sportname' => $sport_name,
                'sportimg' => $photoDoc,
                'type'=>'1',
                'status' => '1',
                'createdBy' => $this->session->userdata['UID'],
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR'],
               
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'Sport has been updated successfully.');
        
            redirect(base_url() . 'admin/sport');
        }
    }

    public function StatusChange($id, $status) {
        $table = "sport";
        $sport_data = array(
            'status' => $status,
            'createdBy' => $this->session->userdata['UID'],
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
        redirect(base_url() . 'admin/sport');
    }

    public function tax() {
        $tax = !empty($this->input->get('tax')) ? $this->input->get('tax') : '';
        $data['tax']=$tax;

         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "tax";
        $select = "*";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'DESC');
        $taxDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
      
        $total_records=!empty($taxDetails) ? count($taxDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
           $this->db->limit($limit_per_page,$page * $limit_per_page);

            $table = "tax";
            $select = "*";
            $condition = array(
                'status !=' => '3'
            );
            if(!empty($tax)){
                $condition['tax.tax']=$tax;
            }
            $this->db->order_by('pk_id', 'DESC');
            $taxDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $data['taxDetails'] = $taxDetails;

            $params["results"] = $taxDetails;             
            $config['base_url'] = base_url() . 'admin/tax';
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
        $data['taxDetails']= $params["results"] ;
       //End:: pagination::- 
       $data['totalcount']=$total_records;

        $this->load->view('admin/master/vw_tax',$data);
    }

    public function taxAction(){
    	$tax = !empty($this->input->post('tax')) ? $this->input->post('tax') : '';

   	    $this->form_validation->set_rules('tax', 'Tax', 'required|trim|numeric|max_length[5]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
        $table = "tax";
        $insert_data = array(
            'tax' => $tax,
            'createdBy' => $this->session->userdata['UID'],
            'createdDate' => date('Y-m-d H:i:s'),
            'created_ip_address' => $_SERVER['REMOTE_ADDR']
        );
        $result = $this->Md_database->insertData($table, $insert_data);
            $this->session->set_flashdata('success', 'Tax has been inserted successfully.');
            
            redirect(base_url() . 'admin/tax');
    }

    public function TaxStatusChange($id, $status) {

        $table = "tax";
        $sport_data = array(
            'status' => $status,
            // 'updatedDate' => date('Y-m-d H:i:s'),
            'updatedBy' => $this->session->userdata['UID'],
            'updatedDate' => date('Y-m-d H:i:s'),
            'updated_ip_address' => $_SERVER['REMOTE_ADDR']
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
        redirect(base_url() . 'admin/sport');
    }

    public function city() {

    	$data = array();
        $city = !empty($this->input->get('city')) ? trim($this->input->get('city')) : '';
        $data['city']=$city;


        $data['title'] = 'City List';
        $data['edit'] = "";
        $edit_id = !empty($this->input->get('edit'))?$this->input->get('edit'):"";
        if (!empty($edit_id)) {
            $id = $edit_id;
            $table = "city";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $cityEditDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($cityEditDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
						     Sorry, something went wrong.
						</div>');

                redirect(base_url() . 'admin/city');
            }
            $data['edit'] = $cityEditDetails[0];          
        }
        $table = "state";
        $select = "state_name,pk_id";
        // $condition = array(
        //     'status !=' => '3'
        // );
        $this->db->order_by('pk_id', 'ASC');
        $stateDetails = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
        $data['stateDetails'] = $stateDetails;

         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "city";
        $select = "pk_id,state_name,city_name,status";
        $condition = array(
            'status !=' => '3'
        );
        if(!empty($city)){  
            $this->db->where("city.city_name LIKE '%$city%'");             
        }
        $this->db->order_by('pk_id', 'DESC');
        $cityDetails = $this->Md_database->getData($table, $select, '', 'pk_id DESC', '');
        $data['cityDetails'] = $cityDetails;

        $total_records=!empty($cityDetails) ? count($cityDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) {
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "city";
            $select = "pk_id,state_name,city_name,status";
            $condition = array(
                'status !=' => '3'
            );
            if(!empty($city)){
                $this->db->where("city.city_name LIKE '%$city%'");             
            }
            $this->db->order_by('pk_id', 'DESC');
            $cityDetails = $this->Md_database->getData($table, $select, '', 'pk_id DESC', '');
            $data['cityDetails'] = $cityDetails;

            $params["results"] = $cityDetails;             
            $config['base_url'] = base_url() . 'admin/city';
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
        $data['cityDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/master/vw_city',$data);
    }

    public function check_city(){
        $city='';$state='';$pk_id='';
        $city= $this->input->post('city');
        $state= $this->input->post('state');
        $pk_id= $this->input->post('pk_id');

        if(!empty($pk_id)){
            $this->db->where('pk_id!=', $pk_id);
            $this->db->where('state_name', $state);
            $this->db->where('city_name', $city);
          
            $this->db->where('status!=', 3);
            $query = $this->db->get('city');
            $res=$query->result_array();
            
            if(!empty($res)){
                echo json_encode(FALSE);
            }else { 
                echo json_encode(TRUE);
            }
        }else{
            $this->db->where('state_name', $state);
            $this->db->where('city_name', $city);
            $this->db->where('status!=', 3);
            $query = $this->db->get('city');
            $res=$query->result_array();
            if(!empty($res)){
                echo json_encode(FALSE);
            }else{ 
                echo json_encode(TRUE);
            }
        }
    }

    public function cityAction(){
   	    $state_name = !empty($this->input->post('state')) ? $this->input->post('state') : '';
   	    $city_name = !empty($this->input->post('city')) ? $this->input->post('city') : '';
   	    $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
   
   	    $this->form_validation->set_rules('state', 'Sport Name', 'required|trim');
   	    $this->form_validation->set_rules('city', 'Sport Name', 'required|trim|max_length[25]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        } 
        if (empty($txtid)) {
            $table = "state";
            $select = array('pk_id,status,state_name');
            $condition = array(
                'status !=' => '3',
                'state_name'=> $state_name,
            );
            $ArraystateDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            $state_id  = $ArraystateDetails[0]['pk_id'];


            $table = "city";
            $insert_data = array(
            	'state_id'    =>$state_id,
                'state_name' => $state_name,
                'city_name' => $city_name,
                'status' => '1',
                'created_by' => $this->session->userdata['UID'],
                'created_date' => date('Y-m-d H:i:s'),
                 'created_ip_address' => $_SERVER['REMOTE_ADDR']
            );
           $result = $this->Md_database->insertData($table, $insert_data);
           $this->session->set_flashdata('success', 'Sport has been inserted successfully.');
            
            redirect(base_url() . 'admin/city');
        }else{
         	$table = "state";
            $select = array('pk_id,status,state_name');
            $condition = array(
                'status !=' => '3',
                'state_name'=> $state_name,
            );
            $ArraystateDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            $state_id  = $ArraystateDetails[0]['pk_id'];
         	// update data code
            $table = "city";
            $update_data = array(
            	'state_id'    =>$state_id,
                'state_name' => $state_name,
                'city_name' => $city_name,
                'status' => '1',
                'updated_by' => $this->session->userdata['UID'],
                'updated_ip_address' => $_SERVER['REMOTE_ADDR'],
                'updated_date' => date('Y-m-d H:i:s'),
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'City has been updated successfully.');
        
            redirect(base_url() . 'admin/city');
        }
    }
    
    public function cityStatusChange($id, $status) {
        $table = "city";
        $city_data = array(
            'status' => $status,
            'updated_by' => $this->session->userdata['UID'],
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'],
            'updated_date' => date('Y-m-d H:i:s'),
        );
    	$condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $city_data, $condition);
       
        $actionMsg = 'Inactive';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/city');
    }

    public function services() {
        $data = array();

        $data['title'] = 'Services List';
        $data['edit'] = "";
        $edit_id = !empty($this->input->get('edit'))?$this->input->get('edit'):"";
        if (!empty($edit_id)) {
            $id = $edit_id;
            $table = "sport";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $servicesEditDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($servicesEditDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
                             Sorry, something went wrong.
                        </div>');

                redirect(base_url() . 'admin/services');
            }
            $data['edit'] = $servicesEditDetails[0];
        }

         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        ;
        $table = "sport";
        $select = "pk_id,sportname,sportimg,status,type";
        $this->db->where_not_in('type', 1);
        $this->db->where_not_in('status', 3);
        $this->db->order_by('pk_id', 'DESC');
        $seviceDetails = $this->Md_database->getData($table, $select, '', 'pk_id DESC', '');
      
        $total_records=!empty($seviceDetails) ? count($seviceDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "sport";
            $select = "pk_id,sportname,sportimg,status,type";
            $this->db->where_not_in('type', 1);
            $this->db->where_not_in('status', 3);
            $this->db->order_by('pk_id', 'DESC');
            $seviceDetails = $this->Md_database->getData($table, $select, '', 'pk_id DESC', '');
            $data['seviceDetails'] = $seviceDetails;

            $params["results"] = $seviceDetails;             
            $config['base_url'] = base_url() . 'admin/services';
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
        $data['seviceDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/master/vw_services',$data);
    
    }

    public function servicesAction(){
        $serviceName = !empty($this->input->post('serviceName')) ? trim($this->input->post('serviceName')) : '';
        $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
   
        $this->form_validation->set_rules('serviceName', 'Service Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
               $photoDoc = "";
        if (!empty($_FILES['serviceimage']['name'])) {
            $rename_name = uniqid(); //get file extension:
            $arr_file_info = pathinfo($_FILES['serviceimage']['name']);
            $file_extension = $arr_file_info['extension'];
            $newname = $rename_name . '.' . $file_extension;
            // print_r($newname);die();
            $old_name = $_FILES['serviceimage']['name'];
            // print_r($old_name);die();
            $path = "uploads/master/sportimage/";

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $upload_type = "jpg|png|jpeg";

            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "serviceimage", "", $newname);
                 
            // if (!empty($this->input->post('fileold'))) {
            //     unlink(FCPATH . 'uploads/master/sportimage/' . $this->input->post('fileold'));
            // }
        }
        $photoDoc = !empty($photoDoc) ? $photoDoc : $this->input->post('fileold');
        
        //Update data Code
        if (!empty($txtid)) {
            $table = "sport";
            $update_data = array(
                'sportname'    =>$serviceName,
                'sportimg' => $photoDoc,
                'status' => '1',
                'updatedBy' => $this->session->userdata['UID'],
                'updated_ip_address' => $_SERVER['REMOTE_ADDR'],
                'updatedDate' => date('Y-m-d H:i:s'),               
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'Service has been updated successfully.');
        
            redirect(base_url() . 'admin/services');
         }else{
            redirect(base_url() . 'admin/services');
         }
        
      }
       
    public function serviceStatusChange($id, $status){
        $table = "sport";
        $city_data = array(
            'status' => $status,
            'updatedBy' => $this->session->userdata['UID'],
            'updated_ip_address' => $_SERVER['REMOTE_ADDR'],
            'updatedDate' => date('Y-m-d H:i:s'),
        );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $city_data, $condition);
       
        $actionMsg = 'Inactive';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/services');
    }

}
