<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_sports_videos extends CI_Controller {

    public function sports_videos_list() {     
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $sport = !empty($this->input->get('type')) ? $this->input->get('type'): '';
     
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['sport']=$sport;
     
        $table = "sport";
        $select = "sportname,pk_id,status";
        $condition = array(
            'status ' => '1',
            'type'=>'1'
        );
        // $this->db->order_by('pk_id', 'DESC');
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['sportDetails'] = $sportDetails;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $condition = "";
        $table = "sports_videos";
        $select = "sports_videos.pk_id,heading,sport.sportname,description,url,sports_videos.createdDate,sports_videos.status,skill_level";
         $this->db->join('sport','sports_videos.type = sport.pk_id');
        $condition = array(
            'sports_videos.status !=' => '3'
        );
        if(!empty($sport)){
            $condition['sports_videos.type']=$sport;
        }
        if(!empty($fromdatefilter)){         
            $condition['date(koodo_sports_videos.createdDate)>=']=$fromdatefilter;
        }
           if(!empty($todatefilter)){         
            $condition['date(koodo_sports_videos.createdDate)<=']=$todatefilter;
        }
        $this->db->order_by('sports_videos.pk_id', 'DESC');
        $sportList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['sportList'] = $sportList;

        $total_records=!empty($sportList) ? count($sportList) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $condition = "";
            $table = "sports_videos";
            $select = "sports_videos.pk_id,heading,sport.sportname,description,url,sports_videos.createdDate,sports_videos.status,skill_level";
            $this->db->join('sport','sports_videos.type = sport.pk_id');
            $condition = array(
                'sports_videos.status !=' => '3'
            );
            if(!empty($sport)){
                $condition['sports_videos.type']=$sport;
            }
            if(!empty($fromdatefilter)){         
                $condition['date(koodo_sports_videos.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){         
                $condition['date(koodo_sports_videos.createdDate)<=']=$todatefilter;
            }
            $this->db->order_by('sports_videos.pk_id', 'DESC');
            $sportList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $data['sportList'] = $sportList;
        
            $params["results"] = $sportList;             
            $config['base_url'] = base_url() . 'admin/sports-videos-list';
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
        $data['sportList']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;      
        $this->load->view('admin/sports-videos/vw_sports_videos_list',$data);
    }


    public function view(){
        $id = $this->input->get('id');
        //get hostel states ::-
        $table = "sports_videos";
        $select = "*";
        $condition = array('sports_videos.status' => '1',
           'sports_videos.pk_id'=>$id,
        );
        $this->db->join('sport','sports_videos.type = sport.pk_id');
        // $this->db->where('pk_id', $this->input->get('id'));
        // if (!empty($id)) {
        //    $condition['sports_videos.pk_id like'] = $id;
        // }
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
        // print_r( $ArrayView );
        // die();
       
        echo json_encode($ArrayView);
        exit();
        
    }

    public function sport_video_action(){
      	// echo "string";
      	$type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
      	$heading = !empty($this->input->post('heading')) ? $this->input->post('heading') : '';
      	$txteditor = !empty($this->input->post('txteditor')) ? $this->input->post('txteditor') : '';
      	$url = !empty($this->input->post('url')) ? $this->input->post('url') : '';
        $skill_level = !empty($this->input->post('skill_level')) ? $this->input->post('skill_level') : '';
     	  $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
   
     	  $this->form_validation->set_rules('type', 'Sport Name', 'required|trim');
     	  $this->form_validation->set_rules('heading', 'Heading', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('skill_level', 'skill_level', 'required|trim');
     	  $this->form_validation->set_rules('txteditor', 'Sport Name', 'required|trim|max_length[300]');
     	  $this->form_validation->set_rules('url', 'Sport Name', 'required|trim|valid_url');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
        if (empty($txtid)) {
            $table = "sports_videos";
            $insert_data = array(
                'type' => $type,
                'heading' => $heading,
                'description' => $txteditor,
                'url' => $url,
                'skill_level' => $skill_level,
                'status' => '1',
                'createdBy' => $this->session->userdata['UID'],
                'createdDate' => date('Y-m-d H:i:s'),
            );
            $result = $this->Md_database->insertData($table, $insert_data);
            $this->session->set_flashdata('success', 'Sport has been inserted successfully.');
            
            redirect(base_url() . 'admin/add-sports-videos');
        }else{
         	// update data code
            $table = "sports_videos";
            $update_data = array(
                'type' => $type,
                'heading' => $heading,
                'description' => $txteditor,
                'skill_level' => $skill_level,
                'url' => $url,
                'status' => '1',
                'updatedDate'=> date('Y-m-d H:i:s'),
               
            );
            $condition = array(
                'pk_id' => $txtid,
            );
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);      
            $this->session->set_flashdata('success', 'Sport has been updated successfully.');
        
            redirect(base_url() . 'admin/add-sports-videos');
        }
    }

    public function add_sports_videos(){
        $data = array();
        $data['title'] = 'Sport Videos List';
        $data['edit'] = "";
        if (!empty($this->uri->segment(3))) {
            $id = $this->uri->segment(3);
            $table = "sports_videos";
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
    	  $condition = "";
        $table = "sport";
        $select = "pk_id,sportname";
        $condition = array(
            'status ' => '1',
            'type'=>'1'
        );
        $this->db->order_by('pk_id', 'DESC');
        $sportName = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $data['sportName'] = $sportName;

        $this->load->view('admin/sports-videos/vw_add_sports_videos',$data);
    }

    public function StatusChange($id, $status) {

        $table = "sports_videos";
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
        redirect(base_url() . 'admin/add-sports-videos');
    }

    public function delete_sports_videos($id){
    { 
        $table="sports_videos";     
        $condition=array("pk_id"=>$id); 
       
        $deleteData= $this->Md_database->deleteData($table,$condition);
        if($deleteData){
         $this->session->set_flashdata('success', 'Sport data deleted successfully.');
         }else{
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
         }
         redirect(base_url('admin/sports-videos-list'));
    }  
 }
  public function sport_video_export_to_excel(){
      $this->load->library('Excel');
      $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
      $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
      $sport = !empty($this->input->get('type')) ? $this->input->get('type'): '';

      $data['fromdatefilter']=$fromdatefilter;
      $data['todatefilter']=$todatefilter;
      $data['sport']=$sport;
      $sportName="";
      if (!empty($sport)) {

          $table = "sport";
          $select = "sportname";
          $condition = array(
              'status !=' => '3',
              'pk_id' => $sport,
          );
          $sportN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
          $sportName=$sportN[0]['sportname'];
      }
      $table = "sports_videos";
      $select = "sports_videos.pk_id,heading,sport.sportname,description,url,sports_videos.createdDate,sports_videos.status,skill_level";
      $this->db->join('sport','sports_videos.type = sport.pk_id');
      $condition = array(
          'sports_videos.status!=' => '3'
      );
      if(!empty($sport)){
          $condition['sports_videos.type']=$sport;
      }
      if(!empty($fromdatefilter)){         
          $condition['date(koodo_sports_videos.createdDate)>=']=$fromdatefilter;
      }
      if(!empty($todatefilter)){         
          $condition['date(koodo_sports_videos.createdDate)<=']=$todatefilter;
      }
      $this->db->order_by('sports_videos.pk_id', 'DESC');
      $sportList = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
      $data['sportList'] = $sportList;
      
      /*[:: Start Collection report excel sheet  Name::]*/
      $comm_title ="Sport video List";         
      /*[:: End Collection report excel sheet  Name::]*/
      if (!empty($sportList)) {
          $finalsArray = $sportList;             
          $this->excel->getActiveSheet()->setTitle('Sport Video List');
          $date = date('d-m-Y g:iA'); // get current date time
          $cnt = count($finalsArray);
          $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter,'Sport Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $sportName);
                          
          $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date Time');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Sport Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Skill Level');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Video Heading');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Description');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Youtube Video');

              // set auto size for columns
              $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
              $from = "A1"; // or any value
              $to = "G1"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
              $from1 = "A2"; // or any value
              $to1 = "G2"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);
              $date = date('d-m-Y g:iA');
              $cnt = count($finalsArray);
              $counter = 3;

               if (!empty($finalsArray)) {
                      $j = 1;
                      foreach ($finalsArray as $arrayUser) {
                        // print_r($arrayUser);
                        // die();
                                                      
                          $heading = !empty($arrayUser['heading']) ? ucfirst($arrayUser['heading']) :'';
                          $sportname = !empty($arrayUser['sportname']) ? $arrayUser['sportname']:'';
                          $skill_level = !empty($arrayUser['skill_level']) ? $arrayUser['skill_level']:'';
                          $description = !empty($arrayUser['description']) ? ucfirst($arrayUser['description']):'-';
                          $newDate = date("d-m-Y g:i a", strtotime($arrayUser['createdDate']));
                          $url = !empty($arrayUser['url']) ? $arrayUser['url']:'-';
                        
                          $this->excel->setActiveSheetIndex(0)
                              ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                              ->setCellValue('B' . $counter, (!empty($newDate) ? $newDate : "-"))
                              ->setCellValue('C' . $counter, (!empty($sportname) ? $sportname : "-"))
                              ->setCellValue('D' . $counter, (!empty($skill_level) ? $skill_level : "-"))
                              ->setCellValue('E' . $counter, (!empty($heading) ? $heading : "-"))
                              ->setCellValue('F' . $counter, (!empty($description) ? $description : "-"))
                              ->setCellValue('G' . $counter, (!empty($url) ? $url : "-"));
               
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
   
                  redirect(base_url() . 'admin/sport-video-export-to-excel');
          }
  }

  /*[End ::  function collection log report export excel :]*/
    
}
