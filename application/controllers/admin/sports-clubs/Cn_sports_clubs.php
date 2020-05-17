<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_sports_clubs extends CI_Controller {

    public function sportsClubsList() {
        $filtersport = !empty($this->input->get('filtersport')) ? trim($this->input->get('filtersport')): '';
        $filteraddress = !empty($this->input->get('filteraddress')) ? trim($this->input->get('filteraddress')): '';
        $filtername = !empty($this->input->get('filtername')) ? trim($this->input->get('filtername')): '';
     
        $data['filtersport']=$filtersport;
        $data['filteraddress']=$filteraddress;
        $data['filtername']=$filtername;
    
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $condition = "";
        $table = "sports_club";
        $select = "sports_club.pk_id,sports_club.name,sports_club.address,sports_club.email,sports_club.mobile,sports_club.website,sports_club.createdDate,sports_club.status,sport as sportname,image";
        $condition = array(
            'sports_club.status !=' => '3'
        );
        if(!empty($filtersport)){
            $this->db->where("sports_club.sport LIKE '%$filtersport%'"); 
        }
        if(!empty($filteraddress)){ 
            $this->db->where("sports_club.address LIKE '%$filteraddress%'");
        } if(!empty($filtername)){
             $this->db->where("sports_club.name LIKE '%$filtername%'");
        }
        $this->db->order_by('sports_club.pk_id', 'DESC');
        $sportClubList = $this->Md_database->getData($table, $select, $condition, 'sports_club.pk_id DESC', '');
        $total_records=!empty($sportClubList) ? count($sportClubList) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $condition = "";
            $table = "sports_club";
	        $select = "sports_club.pk_id,sports_club.name,sports_club.address,sports_club.email,sports_club.mobile,sports_club.website,sports_club.createdDate,sports_club.status,sport as sportname,image";
	        $condition = array(
	            'sports_club.status !=' => '3'
	        );
	        if(!empty($filtersport)){
            $this->db->where("sports_club.sport LIKE '%$filtersport%'"); 
        }
        if(!empty($filteraddress)){ 
            $this->db->where("sports_club.address LIKE '%$filteraddress%'");
        } if(!empty($filtername)){
             $this->db->where("sports_club.name LIKE '%$filtername%'");
        }
	        $this->db->order_by('sports_club.pk_id', 'DESC');
	        $sportClubList = $this->Md_database->getData($table, $select, $condition, 'sports_club.pk_id DESC', '');
	        $data['sportClubList'] = $sportClubList;
            
            $params["results"] = $sportClubList;             
            $config['base_url'] = base_url() . 'admin/sports-clubs-list';
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
        $data['sportClubList']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;   
        $this->load->view('admin/sports-clubs/vw_sports_clubs_list',$data);
    }

    public function addSportsClubs() {
    	$data = array();
        $data['title'] = 'Sport Videos List';
        $data['edit'] = "";
        if (!empty($this->uri->segment(3))) {
            $id = $this->uri->segment(3);
            $table = "sports_club";
            $select = "*";
            $condition = array(
                'pk_id' => $id,
            );
            $sportEditDetails = $this->Md_database->getData($table, $select, $condition, '', '');
            if (empty($sportEditDetails)) {
                $this->session->set_userdata('msg', '<div class="alert alert-danger ErrorsMsg">
                 Sorry, something went wrong.
            </div>');

                redirect(base_url() . 'admin/add-sports-videos');
            }
             $data['edit'] = $sportEditDetails[0];
        }   	
        $this->load->view('admin/sports-clubs/vw_add_sports_clubs',$data);

    }
    public function sportClubAction(){
      
      	$sport = !empty($this->input->post('sport')) ? $this->input->post('sport') : '';
      	$name = !empty($this->input->post('name')) ? $this->input->post('name') : '';
      	$txteditor = !empty($this->input->post('txteditor')) ? $this->input->post('txteditor') : '';
      	$website = !empty($this->input->post('website')) ? $this->input->post('website') : '';
      	$address = !empty($this->input->post('event_location')) ? $this->input->post('event_location') : '';
      	$mobile = !empty($this->input->post('mobile')) ? $this->input->post('mobile') : '';
      	$email = !empty($this->input->post('email')) ? $this->input->post('email') : '';
     	$txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
   
 	  	$this->form_validation->set_rules('sport', 'Sport Name', 'required|trim');
 	  	$this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[50]');
 	  	$this->form_validation->set_rules('txteditor', 'Description', 'required|trim|max_length[300]');
 	  	// $this->form_validation->set_rules('website', 'Website', 'required|trim');
 	  	$this->form_validation->set_rules('event_location', 'Address', 'required|trim');
 	  	$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');
 	  	$this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
        $photoDoc = "";
        if (!empty($_FILES['facilityimage']['name'])) {
            $rename_name = uniqid(); //get file extension:
            $arr_file_info = pathinfo($_FILES['facilityimage']['name']);
            $file_extension = $arr_file_info['extension'];
            $newname = $rename_name . '.' . $file_extension;
            $old_name = $_FILES['facilityimage']['name'];
            $path = "uploads/clubs/";

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $upload_type = "jpg|png|jpeg";
            $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "facilityimage", "", $newname); 
        }    
        if (empty($txtid)) {
            $table = "sports_club";
            $insert_data = array(
                'name' => $name,
                'address' => $address,
                'description' => $txteditor,
                'mobile' => $mobile,
                'email' => $email,
                'website' => $website,
                'sport' => $sport,       
                'image' => $photoDoc,       
                'status' => '1',
                'createdBy' => $this->session->userdata['UID'],
                'createdDate' => date('Y-m-d H:i:s'),
                'created_ip_address' => $_SERVER['REMOTE_ADDR']
            );
            $result = $this->Md_database->insertData($table, $insert_data);
            $this->session->set_flashdata('success', 'Sport has been inserted successfully.');
            
            redirect(base_url() . 'admin/add-sports-clubs');
        }else{
         	// update data code
            $table = "sports_club";
            $update_data = array(
                'name' => $name,
                'address' => $address,
                'description' => $txteditor,
                'mobile' => $mobile,
                'email' => $email,
                'website' => $website,
                'sport' => $sport,
                'status' => '1',
                'updatedBy'=> $this->session->userdata['UID'],
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']
               
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            if (!empty($photoDoc)) {
                 $update_data ['image']=$photoDoc; 
            }
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'Sport has been updated successfully.');
        
            redirect(base_url() . 'admin/sports-clubs-list');
        }
    }

    public function viewClubs(){
        $id = $this->input->get('id');
        //get hostel states ::-
        $table = "sports_club";
        $select = "*";
        $condition = array('sports_club.status' => '1',
           'sports_club.pk_id'=>$id,
        );
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        echo json_encode($ArrayView);
        exit();       
    }
   
    public function sportClubStatusChange($id, $status) {
        $table = "sports_club";
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
        redirect(base_url() . 'admin/sports-clubs-list');
    }

    public function deleteSportClub($id){     
        $table="sports_club";     
        $condition=array("pk_id"=>$id); 
       
        $deleteData= $this->Md_database->deleteData($table,$condition);
        if($deleteData){
         $this->session->set_flashdata('success', 'Sport Club  deleted successfully.');
         }else{
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
         }
         redirect(base_url('admin/sports-clubs-list'));
    }
    public function sportClubExportToExcel(){
    	$this->load->library('Excel');
        $filtersport = !empty($this->input->get('filtersport')) ? $this->input->get('filtersport'): '';
        $filteraddress = !empty($this->input->get('filteraddress')) ? $this->input->get('filteraddress'): '';
        $Sport_Facility_Name = !empty($this->input->get('filteraddress')) ? $this->input->get('filteraddress'): '';
         $filtername = !empty($this->input->get('filtername')) ? trim($this->input->get('filtername')): '';
     
        $data['filtersport']=$filtersport;
        $data['filteraddress']=$filteraddress;
        $data['filtername']=$filtername;
    
        $table = "sports_club";
        $select = "sports_club.pk_id,sports_club.name,sports_club.address,sports_club.email,sports_club.mobile,sports_club.website,sports_club.createdDate,sports_club.status,sport as sportname,sports_club.description";
        $condition = array(
            'sports_club.status!=' => '3'
        );
        if(!empty($filtersport)){
            $this->db->like('sports_club.sport',$filtersport); 
        }
        if(!empty($filteraddress)){
            $this->db->like('sports_club.address',$filteraddress); 
        }
         if(!empty($filtername)){
             $this->db->where("sports_club.name LIKE '%$filtername%'");
        }
        $this->db->order_by('sports_club.pk_id', 'DESC');
        $sportClubList = $this->Md_database->getData($table, $select, $condition, 'sports_club.pk_id DESC', '');

                    /*[:: Start Collection report excel sheet  Name::]*/
           $comm_title ="Sport Facility List";
              
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($sportClubList)) {
              $finalsArray = $sportClubList;

             
              $this->excel->getActiveSheet()->setTitle('Sport Club List');
              $date = date('d-m-Y g:iA'); // get current date time
              $cnt = count($finalsArray);
            $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sport Facility Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, $filtername);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Sport');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $filtersport);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Address');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $filteraddress);
                        
            $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Sport Facility Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Address');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Email');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Mobile');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Website');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Sport');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Description');
                          
              // set auto size for columns
              $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            
            
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
                        $name = !empty($arrayUser['name']) ? ucfirst($arrayUser['name']) :'';
                        $address = !empty($arrayUser['address']) ? $arrayUser['address']:'';
                        $email = !empty($arrayUser['email']) ? ucfirst($arrayUser['email']):'-';
	                    $mobile = !empty($arrayUser['mobile']) ? $arrayUser['mobile']:'-';
	                    $website = !empty($arrayUser['website']) ? $arrayUser['website']:'-';
	                    $sportname = !empty($arrayUser['sportname']) ? $arrayUser['sportname']:'-';
	                    $description = !empty($arrayUser['description']) ? $arrayUser['description']:'-';

	                        $this->excel->setActiveSheetIndex(0)
                              ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                              ->setCellValue('B' . $counter, (!empty($name) ? $name : "-"))
                              ->setCellValue('C' . $counter, (!empty($address) ? $address : "-"))
                              ->setCellValue('D' . $counter, (!empty($email) ? $email : "-"))
                              ->setCellValue('E' . $counter, (!empty($mobile) ? $mobile : "-"))
                              ->setCellValue('F' . $counter, (!empty($website) ? $website : "-"))
                              ->setCellValue('G' . $counter, (!empty($sportname) ? $sportname : "-"))
                              ->setCellValue('H' . $counter, (!empty($description) ? $description : "-"));                                 
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
   
                  redirect(base_url() . 'admin/sport-club-export-to-excel');
          }
  }

  /*[End ::  function collection log report export excel :]*/

}

