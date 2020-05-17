<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_user_reviews extends CI_Controller {

    public function user_reviews_list() {
    	  $table = "usertype";
        $select = "*";
        $condition = array(
            'status !=' => '3',
        );
        $this->db->order_by('pk_id', 'ASC');
        $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['usertypeDetails'] = $usertypeDetails; 

         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "user_review";
        $select="user_review.pk_id,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,a.pk_id as forPk_id,b.name as givenName,usertype.usertype";
            
        $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
        $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id');
        $this->db->join('usertype', 'user_review.type=usertype.pk_id');
        
        $condition = array(
            'user_review.status !=' => '3',
        );

        $reviewDetails = $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');

        $data['reviewDetails'] = $reviewDetails;

        $total_records=!empty($data['reviewDetails']) ? count($data['reviewDetails']) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	      $table = "user_review";
            $select="user_review.pk_id,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,a.pk_id as forPk_id,b.name as givenName,usertype.usertype";
            
            $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
            $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id');
            $this->db->join('usertype', 'user_review.type=usertype.pk_id');
            $condition = array(
                'user_review.status !=' => '3',
            );
            $reviewDetails = $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');
         
            $data['user'] = $reviewDetails;
            $params["results"] = $reviewDetails;             
            $config['base_url'] = base_url() . 'admin/user-reviews-list';
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
        $data['reviewDetails']= $params["results"];
        //End:: pagination::- 
        $data['totalcount']=$total_records;
        // echo "<pre>";
        // print_r($data['reviewDetails']);
        // die();
        $this->load->view('admin/user-reviews/vw_user_reviews_list',$data);
    }

    public function StatusChange($id, $status){
        $table = "user_review";
        $user_data = array(
            'status' => $status,
            'updatedDate' => date('Y-m-d H:i:s'),
            // 'createdBy' => $this->session->userdata['UID'],
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
        redirect(base_url() . 'admin/user-reviews-list');
    }

    public function view(){
        $id = $this->input->post('id');
        $table = "user_review";
        $select="user_review.pk_id,a.pk_id as id ,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,b.name as givenName,c.usertype";
            
        $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
        $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id');
        $this->db->join('usertype as c','c.pk_id=user_review.type'); 
        $condition = array(
            'user_review.status !=' => '3',
             'user_review.pk_id'=>$id,
        );
        $ArrayViewDetails= $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');
        $ArrayView = !empty($ArrayViewDetails[0]) ? $ArrayViewDetails[0] :"";
        echo json_encode($ArrayView);
        exit();
    }
    public function filterUserReview(){
        $table = "usertype";
        $select = "*";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'ASC');
        $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['usertypeDetails'] = $usertypeDetails; 


        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $approved  = !empty($this->input->get('approved')) ? $this->input->get('approved') : '';

        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['approved']=$approved;
         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $reviewDetails=array();
        
        $table = "user_review";
        $select="user_review.pk_id,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,a.pk_id as forPk_id,b.name as givenName,c.usertype";
        $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
        $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id'); 
        $this->db->join('usertype as c','c.pk_id=user_review.type');
        $this->db->distinct(); 
        $condition = array(
            'user_review.status !=' => '3',
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_user_review.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_user_review.createdDate)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['user_review.type']=$type;
        }
        if(!empty($approved)){
            $condition['user_review.status']=$approved;
        }else{
            $condition['user_review.status<>']=3;
        }

        $reviewDetails = $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');
        $data['reviewDetails'] = !empty($reviewDetails)?$reviewDetails:"";

        $total_records=!empty($data['reviewDetails']) ? count($data['reviewDetails']) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "user_review";
            $select="user_review.pk_id,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,a.pk_id as forPk_id,b.name as givenName,c.usertype";
            $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
            $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id');
            $this->db->join('usertype as c','c.pk_id=user_review.type');
            $this->db->distinct(); 
            $condition = array(
                'user_review.status !=' => '3',
            );

            if(!empty($fromdatefilter)){
                $condition['date(koodo_user_review.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_user_review.createdDate)<=']=$todatefilter;
            }
            if(!empty($type)){
                $condition['user_review.type']=$type;
            }
            if(!empty($approved)){
                $condition['user_review.status']=$approved;
            }else{
                $condition['user_review.status<>']=3;
            }

            $reviewDetails = $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');
     
            $data['reviewDetails'] = $reviewDetails;

            $params["results"] = $reviewDetails;             
            $config['base_url'] = base_url() . 'admin/filter-review';
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
        $data['reviewDetails']= $params["results"];
        //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/user-reviews/vw_user_reviews_list',$data);
    }
    public function review_export_to_excel(){
        $this->load->library('Excel');
        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $approved  = !empty($this->input->get('approved')) ? $this->input->get('approved') : '';

        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['approved']=$approved;
        $usertype='';
        if (!empty($type)) {
            if($type =='1') {
                $usertype='Player';
            } 
            if ($type =='2') {
                $usertype='Coach';
            } 
            if ($type =='3') {
                $usertype='Otner';
            }           
        }
        $approve='';
        if (!empty($approved)) {
            if($approved =='1') {
                $approve='Active';
            } 
            if ($approved =='2') {
                $approve='Inactive';
            }             
        }
        $table = "user_review";
        $select="user_review.pk_id,user_review.status,user_review.rate,user_review.feedback,user_review.createdDate,a.name as forName,a.pk_id as forPk_id,b.name as givenName,c.usertype";            
        $this->db->join('user as a', 'user_review.fk_for=a.pk_id');
        $this->db->join('user as b', 'user_review.fk_given_by=b.pk_id'); 
        $this->db->join('usertype as c','c.pk_id=user_review.type');
        $this->db->distinct(); 
        $condition = array(
            'user_review.status!=' => '3',
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_user_review.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_user_review.createdDate)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['user_review.type']=$type;
        }
        if(!empty($approved)){
            $condition['user_review.status']=$approved;
        }else{
            $condition['user_review.status<>']=3;
        }

        $reviewDetails = $this->Md_database->getData($table, $select, $condition, 'user_review.pk_id DESC', '');
        $data['reviewDetails'] = !empty($reviewDetails)?$reviewDetails:"";
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="User Review List";
        $date_title ="all_time";
        $user_title ="all";

        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($reviewDetails)) {
            $finalsArray = $reviewDetails;
            $this->excel->getActiveSheet()->setTitle('User Review List');
            $date = date('d-m-Y g:i A'); // get current date time
            $cnt = count($finalsArray);
              

          $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Usertype');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, $usertype);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'From date ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'To date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Status');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter,  $approve);
                         
          $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Usertype');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Review For');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Review Given By');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Rate');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Feedback');
          
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

              $date = date('d-m-Y g:i A');
              $cnt = count($finalsArray);
              $counter = 3;

              if (!empty($finalsArray)) {
                  $j = 1;
                  foreach ($finalsArray as $arrayUser) {
                      $createdDate = !empty($arrayUser['createdDate']) ? date('d-m-Y',strtotime($arrayUser['createdDate'])) :'';
                      $forName = !empty($arrayUser['forName']) ? ucwords($arrayUser['forName']) :'';
                      $givenName = !empty($arrayUser['givenName']) ? ucwords($arrayUser['givenName']) :'';                        
                      $rate = !empty($arrayUser['rate']) ?$arrayUser['rate']:'-';
                      $usertype = !empty($arrayUser['usertype']) ? ucwords($arrayUser['usertype']):'';
                      $feedback = !empty($arrayUser['feedback']) ? ucfirst($arrayUser['feedback']):''; 

                    $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                        ->setCellValue('C' . $counter, (!empty($usertype) ? $usertype : "-"))
                        ->setCellValue('D' . $counter, (!empty($forName) ? $forName : "-"))
                        ->setCellValue('E' . $counter, (!empty($givenName) ? $givenName : "-"))
                        ->setCellValue('F' . $counter, (!empty($rate) ? $rate : "-"))
                        ->setCellValue('G' . $counter, (!empty($feedback) ? $feedback : ""));
            
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
}
