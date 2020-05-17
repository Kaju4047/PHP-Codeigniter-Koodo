<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_advertisement extends CI_Controller {

    public function advertisement_list() {
       //City List
        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status ' => '1'
        );
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name ASC', '');
        $data['cityDetails'] = $cityDetails;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "advertisement";
        $select = "advertisement.pk_id,advname,state,city.city_name,fromdate,todate,price,advertisement.status,advertisement.place";
        $this->db->join('city','advertisement.city = city.pk_id');
        $condition = array(
            'advertisement.status !=' => '3'
        );
        $this->db->order_by('advertisement.pk_id', 'DESC');
        $advDetails = $this->Md_database->getData($table, $select, $condition, 'advertisement.pk_id DESC', '');
        $data['advDetails'] = $advDetails;

        $total_records=!empty($advDetails) ? count($advDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) {
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "advertisement";
            $select = "advertisement.pk_id,advname,state,city.city_name,fromdate,todate,price,advertisement.status,advertisement.place";
            $condition = array(
                'advertisement.status !=' => '3'
            );
            $this->db->join('city','advertisement.city = city.pk_id');
            $this->db->order_by('advertisement.pk_id', 'DESC');
            $advDetails = $this->Md_database->getData($table, $select, $condition, 'advertisement.pk_id DESC', '');
            $data['advDetails'] = $advDetails;
 
            $params["results"] = $advDetails;             
            $config['base_url'] = base_url() . 'admin/advertisement-list';
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
        $data['advDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/advertisement/vw_advertisement_list',$data);
    }

    public function filter_list(){
       //City List
        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status ' => '1'
        );
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name ASC', '');
        $data['cityDetails'] = $cityDetails;

        $placefilter = !empty($this->input->get('placefilter')) ? $this->input->get('placefilter') : '';
        $fromdatefilter = !empty($this->input->get('fromdatefilter')) ? date("Y-m-d" ,strtotime($this->input->get('fromdatefilter')) ): '';
        $todatefilter = !empty($this->input->get('todatefilter')) ? date("Y-m-d" ,strtotime($this->input->get('todatefilter') )): '';
        $cityfilter = !empty($this->input->get('cityfilter')) ? $this->input->get('cityfilter') : '';
        $data['placefilter']=$placefilter;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['cityfilter']=$cityfilter;
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "advertisement";
        $select = "advertisement.pk_id,advname,state,advertisement.place,city.city_name,advertisement.fromdate,advertisement.todate,advertisement.price,advertisement.status,advertisement.createdDate,advertisement.place";
        $this->db->join('city','advertisement.city = city.pk_id');
        $condition = array(
            'advertisement.status !=' => '3',            
        );
         if(!empty($fromdatefilter)){
              $condition['date(koodo_advertisement.fromdate)>=']=$fromdatefilter;
          }
          if(!empty($todatefilter)){
              $condition['date(koodo_advertisement.todate)<=']=$todatefilter;
          }
          if(!empty($placefilter)){
              $condition['advertisement.place']=$placefilter;
          }
          if(!empty($cityfilter)){
              $condition['advertisement.city']=$cityfilter;
          }
          $this->db->order_by('advertisement.pk_id', 'DESC');
          $advDetails = $this->Md_database->getData($table, $select, $condition, 'advertisement.pk_id DESC', '');
          $data['advDetails'] = $advDetails;

          $total_records=!empty($advDetails) ? count($advDetails) : '0';
          $data['totalcount']=!empty($total_records) ? $total_records : '0';
          if ($total_records > 0){
              $this->db->limit($limit_per_page,$page * $limit_per_page);
              $table = "advertisement";
              $select = "advertisement.pk_id,advname,state,advertisement.place,city.city_name,advertisement.fromdate,advertisement.todate,advertisement.price,advertisement.status,advertisement.createdDate,advertisement.place";
              $this->db->join('city','advertisement.city = city.pk_id');
              $condition = array(
                  'advertisement.status !=' => '3',            
              );
              if(!empty($fromdatefilter)){
                  $condition['date(koodo_advertisement.fromdate)>=']=$fromdatefilter;
              }
              if(!empty($todatefilter)){
                  $condition['date(koodo_advertisement.todate)<=']=$todatefilter;
              }
              if(!empty($placefilter)){
                  $condition['advertisement.place']=$placefilter;
              }
              if(!empty($cityfilter)){
                  $condition['advertisement.city']=$cityfilter;
              }
              $this->db->order_by('advertisement.pk_id', 'DESC');
              $advDetails = $this->Md_database->getData($table, $select, $condition, 'advertisement.pk_id DESC', '');
              $data['advDetails'] = $advDetails;

              $params["results"] = $advDetails;             
              $config['base_url'] = base_url() . 'admin/filter-adv';
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
          $data['advDetails']= $params["results"] ;
       //End:: pagination::- 
          $data['totalcount']=$total_records;         
          $this->load->view('admin/advertisement/vw_advertisement_list',$data);
    }

    public function add_advertisement(){
        $data = array();
        $data['title'] = '';
        $data['edit'] = "";
        if (!empty($this->uri->segment(3))) {
            $id = $this->uri->segment(3);
            $table = "advertisement";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $advEditDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($advEditDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
                 Sorry, something went wrong.
                </div>');

                redirect(base_url() . 'admin/add-sports-videos');
            }
            $data['edit'] = $advEditDetails[0];
		        $state_id=!empty($data['edit']['state']) ? $data['edit']['state']: '' ;
		        $data['city']=$this->Md_database->getData('city','pk_id,city_name',array('state_id' => $state_id));
        }
      
        $table = "state";
        $select = "pk_id,state_name,status";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'ASC');
        $stateDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['stateDetails'] = $stateDetails;

        $this->load->view('admin/advertisement/vw_add_advertisement',$data);
    }

    public function getCityById(){
    	  $state_id= $this->input->post('Id');
    	
    	  $table = "city";
        $select = "*";
        $condition = array('status' => '1',
            'state_id'=>$state_id,
        );     
        $city_name = $this->Md_database->getData($table, $select, $condition, '', ''); 
        $data=!empty($city_name)?$city_name:''; 
        echo json_encode($data);
        exit();
    }

    public function advAction(){
      // print_r($_FILES);
      // die();
        $place = !empty($this->input->post('place')) ? $this->input->post('place') : '';
        $advname = !empty($this->input->post('advname')) ? $this->input->post('advname') : '';
        $mob = !empty($this->input->post('mob')) ? $this->input->post('mob') : '';
        $state = !empty($this->input->post('state')) ? $this->input->post('state') : '';
        $city = !empty($this->input->post('city')) ? $this->input->post('city') : '';
        $fromdate = !empty($this->input->post('fromdate')) ? date('Y-m-d',strtotime($this->input->post('fromdate'))) : '';
        $todate = !empty($this->input->post('todate')) ? date('Y-m-d',strtotime($this->input->post('todate'))) : '';
        $price = !empty($this->input->post('price')) ? $this->input->post('price') : '';
   	    $url = !empty($this->input->post('url')) ? $this->input->post('url') : '';
   	    $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';

   	    $this->form_validation->set_rules('advname', 'advnamee', 'required|trim|max_length[50]');
   	    $this->form_validation->set_rules('mob', 'mob', 'required|trim|max_length[13]');
   	    $this->form_validation->set_rules('state', 'state', 'required|trim');
   	    $this->form_validation->set_rules('city', 'city', 'required|trim');
   	    $this->form_validation->set_rules('fromdate', 'fromdate ', 'required|trim');
   	    $this->form_validation->set_rules('todate', 'todate', 'required|trim');
   	    $this->form_validation->set_rules('price', 'price', 'required|trim');
   	    $this->form_validation->set_rules('url', 'url', 'required|trim');
   	          
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
          
        $photoDoc = "";
        if (!empty($_FILES['advimg']['name'])) {
            $rename_name = uniqid(); //get file extension:
            $arr_file_info = pathinfo($_FILES['advimg']['name']);
            $file_extension = $arr_file_info['extension'];
            $newname = $rename_name . '.' . $file_extension;
            // print_r($newname);die();
            $old_name = $_FILES['advimg']['name'];
            // print_r($old_name);die();
            $path = "uploads/master/advimg/";

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $upload_type = "jpg|png|jpeg";

            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "advimg", "", $newname);
            //if (!empty($this->input->post('fileold'))) {
               //unlink(FCPATH . 'uploads/master/advimg/' . $this->input->post('fileold'));
            // }
            }

            if (empty($txtid)) {                
                  $table = "advertisement";
                  $insert_data = array(
                      'place' => $place,
                      'advname' => $advname,
                      'mob' => $mob,
                      'state' => $state,
                      'city' => $city,
                      'fromdate' => $fromdate,
                      'todate' => $todate,
                      'fromdate' => $fromdate,
                      'url' => $url,
                      'price' => $price,
                      'advimg' => $photoDoc,
                      'status' => '1',
                      'createdBy' => $this->session->userdata['UID'],
                      'createdDate' => date('Y-m-d H:i:s'),
                      'created_ip_address' => $_SERVER['REMOTE_ADDR']                  
                  );
                  $result = $this->Md_database->insertData($table, $insert_data);
                  $this->session->set_flashdata('success', 'advertisement has been inserted successfully.');   
                       
                redirect(base_url() . 'admin/add-advertisement');
            }else{
         	    // update data code
                $table = "advertisement";
                $update_data = array(
                    'place' => $place,
                    'advname' => $advname,
                    'mob' => $mob,
                    'state' => $state,
                    'city' => $city,
                    'fromdate' => $fromdate,
                    'todate' => $todate,
                    'fromdate' => $fromdate,
                    'url' => $url,
                    'price' => $price,
                     // 'advimg' => $photoDoc,
                    'status' => '1',
                    'updatedBy' => $this->session->userdata['UID'],
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address'=> $_SERVER['REMOTE_ADDR'] 
               
                );
                $condition = array(
                    'pk_id' => $txtid,
                );
                if(!empty($photoDoc)){
                    $update_data['advimg'] = !empty($photoDoc) ? $photoDoc :'';
                }
                $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
                $this->session->set_flashdata('success', 'Advertisement has been updated successfully.');
        
                redirect(base_url() . 'admin/advertisement-list');
            }
    }

    public function StatusChange($id, $status) {
        $table = "advertisement";
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
        }else{
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/add-advertisement');
    }

    public function deleteAdv($pk_id){
        $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
        $ret = $this->Md_database->updateData('advertisement', $update_data, $condition);
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "advertisement details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);    
        }
    }


    public function advertisement_export_to_excel(){
        $this->load->library('Excel');
        $placefilter = !empty($this->input->post('placefilter')) ? $this->input->post('placefilter') : '';
        $fromdatefilter = !empty($this->input->post('fromdatefilter')) ? date("Y-m-d" ,strtotime($this->input->post('fromdatefilter')) ): '';
        $todatefilter = !empty($this->input->post('todatefilter')) ? date("Y-m-d" ,strtotime($this->input->post('todatefilter') )): '';
        $cityfilter = !empty($this->input->post('cityfilter')) ? $this->input->post('cityfilter') : '';
       // print_r($city);
       // die();
        $city="";
        if (!empty($cityfilter)){
            $table = "city";
            $select = "city_name";
            $condition = array(
                'pk_id'=> $cityfilter,            
            );
            $city_name = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $city=$city_name[0]['city_name'];
        }
        $place="";
        if (!empty($placefilter)) {
            if ($placefilter == 1) {
                $place = 'Section 1';
            }
            if ($placefilter == 2) {
                $place = 'Section 2';
            }
            if ($placefilter == 3) {
                $place = 'Section 3';
            }
            if ($placefilter == 4) {
                $place = 'Listing';
            }
        }

        $table = "advertisement";
        $select = "advertisement.pk_id,advname,state,advertisement.place,city.city_name,advertisement.fromdate,advertisement.todate,advertisement.price,advertisement.status,advertisement.createdDate";
        $this->db->join('city','advertisement.city = city.pk_id');
        $condition = array(
            'advertisement.status!=' => '3',            
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_advertisement.fromdate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_advertisement.todate)<=']=$todatefilter;
        }
        if(!empty($placefilter)){
            $condition['advertisement.place']=$placefilter;
        }
        if(!empty($cityfilter)){
            $condition['advertisement.city']=$cityfilter;
        }
        $this->db->order_by('advertisement.pk_id', 'DESC');
        $advDetails = $this->Md_database->getData($table, $select, $condition, 'advertisement.pk_id DESC', '');
        $data['advDetails'] = $advDetails;
        /*[:: Start Collection report excel sheet  Name::]*/
      $comm_title ="Advertisement List";
      $date_title ="all_time";
      $user_title ="all";         
        /*[:: End Collection report excel sheet  Name::]*/

      if (!empty($advDetails)) {
          $finalsArray = $advDetails;
          $this->excel->getActiveSheet()->setTitle('Advertisement List');
          $date = date('d-m-Y g:i A'); // get current date time
          $cnt = count($finalsArray);
              
          $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Place');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $place);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'City');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter,  $city);
              
          $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Advertisement Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, '  From');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, '  To');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'City');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Price (Rs.)');
              
              // set auto size for columns
              $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
             
             
              $from = "A1"; // or any value
              $to = "M1"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
              $from1 = "A2"; // or any value
              $to1 = "M2"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);

              $date = date('d-m-Y g:i A');
              $cnt = count($finalsArray);
          $counter = 3;
              if (!empty($finalsArray)) {
                  $j = 1;
                  foreach ($finalsArray as $arrayUser) {
                      $fromdate = !empty($arrayUser['fromdate']) ? date('d-m-Y',strtotime($arrayUser['fromdate'])) :'';
                      $todate = !empty($arrayUser['todate']) ? date('d-m-Y',strtotime($arrayUser['todate'])) :'';
                      $advname = !empty($arrayUser['advname']) ? ucwords($arrayUser['advname']):'-';
                      $price = !empty($arrayUser['price']) ? ucwords($arrayUser['price']):'';
                      $city_name = !empty($arrayUser['city_name']) ? $arrayUser['city_name']:'';
                         
                   $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($advname) ? $advname : "-"))
                        ->setCellValue('C' . $counter, (!empty($fromdate) ? $fromdate : "-"))
                        ->setCellValue('D' . $counter, (!empty($todate) ? $todate : "-"))
                        ->setCellValue('E' . $counter, (!empty($city_name) ? $city_name : "-"))
                        ->setCellValue('F' . $counter, (!empty($price) ? $price : "-"));
                        
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
          redirect(base_url() . 'admin/advertisement-export-to-excel');
      }
  }

}
