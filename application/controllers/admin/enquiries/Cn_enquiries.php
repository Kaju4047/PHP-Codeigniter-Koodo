<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_enquiries extends CI_Controller {

    public function enquiries() {
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "enquiry";
        $select = "enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,t.enqtype";
        $this->db->join('enqtype as t', 'enquiry.enqid = t.pk_id'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id'); 
        $this->db->distinct();
        $condition = array(
            'enquiry.status !=' => '3',
            'enquiry.enqid !=' => '5',
        );
        $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($enqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');
            $addmoredetails[]=$userDetails;
        }
        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "enquiry";
            $select = "enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,t.enqtype";
            $this->db->join('enqtype as t', 'enquiry.enqid = t.pk_id'); 
            $this->db->join('user', 'enquiry.user_id = user.pk_id'); 
            $this->db->distinct();
            $condition = array(
                'enquiry.status !=' => '3',
                'enquiry.enqid !=' => '5',
            );
            $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
      
            $addmoredetails=array();
            foreach ($enqDetails1 as $userDetails){
                $uid= $userDetails['user_id'];
                $table = "profile_type";
                $select = "usertype.usertype";           
                $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                  'profile_type.user_id' => $uid,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');         
                $addmoredetails[]=$userDetails;
            }
            $params["results"] = $addmoredetails;             
            $config['base_url'] = base_url() . 'admin/enquiries';
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
        $data['enqDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
       
        $this->load->view('admin/enquiries/vw_enquiries',$data);
    }
    public function filterEnq(){
    	$type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        
        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
           
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "enquiry";
        $select="enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,enqtype.enqtype";
        $this->db->join('enqtype ', 'enqtype.pk_id=enquiry.enqid'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id');
        $condition = array(
            'enquiry.status !=' => '3',
        );
       
        if(!empty($fromdatefilter)){
            $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['enqtype.pk_id']=$type;
        }
        $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($enqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');
            $addmoredetails[]=$userDetails;
        }
        $data['enqDetails'] = $addmoredetails;
         
        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "enquiry";
            $select="enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,enqtype.enqtype";
            $this->db->join('enqtype ', 'enqtype.pk_id=enquiry.enqid'); 
            $this->db->join('user', 'enquiry.user_id = user.pk_id');
            $condition = array(
                'enquiry.status !=' => '3',
            );
       
            if(!empty($fromdatefilter)){
                $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
            }
            if(!empty($type)){
                $condition['enqtype.pk_id']=$type;
            }

            $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
            $addmoredetails=array();
            foreach ($enqDetails1 as $userDetails){
                $uid= $userDetails['user_id'];
                $table = "profile_type";
                $select = "usertype.usertype";           
                $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                    'profile_type.user_id' => $uid,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');         
                $addmoredetails[]=$userDetails;
            }
            $data['enqDetails'] = $addmoredetails;
      
            $params["results"] = $addmoredetails;             
            $config['base_url'] = base_url() . 'admin/filter-enq';
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
        $data['enqDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
        
        $this->load->view('admin/enquiries/vw_enquiries',$data);
    }

    public function enquiry_export_to_excel(){
        $this->load->library('Excel');
        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        
        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $enquiryName="";

        if (!empty($type)) {
            if ($type == 1) {
                $enquiryName = 'Advertisement';
            }
            if ($type == 2) {
                $enquiryName = 'Bulk requirements';
            }
            if ($type == 3) {
                $enquiryName = 'Sponsors';
            }
            if ($type == 4) {
                $enquiryName = 'Feedback';
            }
        }
    
        $table = "enquiry";
        $select="enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,enqtype.enqtype";
      
        $this->db->join('enqtype ', 'enqtype.pk_id=enquiry.enqid'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id');
        $condition = array(
            'enquiry.status ' => '1',
        );
       
        if(!empty($fromdatefilter)){
            $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
           $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['enqtype.pk_id']=$type;
        }

        $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($enqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', ''); 
            $addmoredetails[]=$userDetails;
        }
        $data['enqDetails'] = $addmoredetails;
          
        $comm_title ="Enquiry List";
        $date_title ="all_time";
        $user_title ="all";
         
      
         
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($addmoredetails)) {
            $finalsArray = $addmoredetails;

             
            $this->excel->getActiveSheet()->setTitle('Enquiry List');
            $date = date('d-m-Y g:i A'); // get current date time
            $cnt = count($finalsArray);
              

          $counter = 1; 
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Enquiry type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $enquiryName);
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'From Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $fromdatefilter);
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'To Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $todatefilter);
           
              
          $counter = 2;
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Enquiry Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Enquiry Type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Usertype');
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Name');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Mobile No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Email Id');
            $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Comment');
                      
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
                    $createdDate = !empty($arrayUser['createdDate']) ? date('d-m-Y',strtotime($arrayUser['createdDate'])) :'';
                         
                    $name = !empty($arrayUser['name']) ? ucwords($arrayUser['name']):'-';
                    $mob = !empty($arrayUser['mob']) ? ucwords($arrayUser['mob']):'';
                    $email = !empty($arrayUser['email']) ? $arrayUser['email']:'';
                    $comment = !empty($arrayUser['comment']) ? ucfirst($arrayUser['comment']):'-';
                    $enqtype =!empty($arrayUser['enqtype']) ?$arrayUser['enqtype']: '-' ;
                    $usertype2=array_column($arrayUser[0],'usertype');
                    $usertype= implode(",",$usertype2);
                       
                         

                    $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                        ->setCellValue('C' . $counter, (!empty($enqtype) ? $enqtype : "-"))
                        ->setCellValue('D' . $counter, (!empty($usertype) ? $usertype : "-"))
                        ->setCellValue('E' . $counter, (!empty($name) ? $name : "-"))
                        ->setCellValue('F' . $counter, (!empty($mob) ? $mob : "-"))
                        ->setCellValue('G' . $counter, (!empty($email) ? $email : ""))
                        ->setCellValue('H' . $counter, (!empty($comment) ? $comment : ""));
                              

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
    
    public function private_coaching_enquiry() {
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';

        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "enquiry";
        $select = "enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,t.enqtype";
        $this->db->join('enqtype as t', 'enquiry.enqid = t.pk_id'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id'); 
        $this->db->distinct();
        $condition = array(
            'enquiry.status !=' => '3',
            'enquiry.enqid ' => '5',
        );
         if(!empty($fromdatefilter)){
            $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
           $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
        }
        $privateenqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($privateenqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');
            $addmoredetails[]=$userDetails;
        }
        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "enquiry";
            $select = "enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,t.enqtype,enquiry.pk_id";
            $this->db->join('enqtype as t', 'enquiry.enqid = t.pk_id'); 
            $this->db->join('user', 'enquiry.user_id = user.pk_id'); 
            $this->db->distinct();
            $condition = array(
                'enquiry.status !=' => '3',
                 'enquiry.enqid ' => '5',
            );
            if(!empty($fromdatefilter)){
                $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
               $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
            }
            $privateenqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
      
            $addmoredetails=array();
            foreach ($privateenqDetails1 as $userDetails){
                $uid= $userDetails['user_id'];
                $table = "profile_type";
                $select = "usertype.usertype";           
                $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                  'profile_type.user_id' => $uid,
                );
                $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', '');         
                $addmoredetails[]=$userDetails;
            }
            $params["results"] = $addmoredetails;             
            $config['base_url'] = base_url() . 'admin/enquiries';
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
        $data['privateEnqDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
       

        $this->load->view('admin/enquiries/vw_private_coaching_enquiry',$data);
    }

    public function view(){
        $id = $this->input->get('id');  
        $table = "enquiry";
        $select="enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,enqtype.enqtype,coach_level,no_session,location,sport_id,sportname";
      
        $this->db->join('enqtype ', 'enqtype.pk_id=enquiry.enqid'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id');
        $this->db->join('sport', 'enquiry.sport_id = sport.pk_id');
        $condition = array(
            'enquiry.status ' => '1',
            'enquiry.enqid ' => '5',
        );

        $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($enqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', ''); 
            $addmoredetails[]=$userDetails;
        }
        $data['enqDetails'] = $addmoredetails;
        $ArrayView = !empty($addmoredetails[0])?$addmoredetails[0]:'';
    
        echo json_encode($ArrayView);
        exit();
    }



     public function private_enquiry_export_to_excel(){
        $this->load->library('Excel');
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $enquiryName="";

    
        $table = "enquiry";
        $select="enquiry.user_id,enquiry.enqid ,user.name,user.mob,enquiry.createdDate,user.email,enquiry.comment,enquiry.status,enqtype.enqtype,coach_level,no_session,location,sport_id,sportname";
      
        $this->db->join('enqtype ', 'enqtype.pk_id=enquiry.enqid'); 
        $this->db->join('user', 'enquiry.user_id = user.pk_id');
        $this->db->join('sport', 'enquiry.sport_id = sport.pk_id');
        $condition = array(
            'enquiry.status ' => '1',
            'enquiry.enqid ' => '5',
        );
       
        if(!empty($fromdatefilter)){
            $condition['date(koodo_enquiry.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
           $condition['date(koodo_enquiry.createdDate)<=']=$todatefilter;
        }

        $enqDetails1 = $this->Md_database->getData($table, $select, $condition, 'enquiry.pk_id DESC', '');
        $addmoredetails=array();
        foreach ($enqDetails1 as $userDetails){
            $uid= $userDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";           
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'profile_type.user_id' => $uid,
            );
            $userDetails[] = $this->Md_database->getData($table, $select, $condition, '', ''); 
            $addmoredetails[]=$userDetails;
        }
        $data['enqDetails'] = $addmoredetails;
          
        $comm_title ="Private Coaching Enquiry List";
        $date_title ="all_time";
        $user_title ="all";
         
      
         
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($addmoredetails)) {
            $finalsArray = $addmoredetails;

             
            $this->excel->getActiveSheet()->setTitle('Private Coachiong Enquiry List');
            $date = date('d-m-Y g:i A'); // get current date time
            $cnt = count($finalsArray);
              

          $counter = 1; 
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromdatefilter);
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $todatefilter);
           
              
          $counter = 2;
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Enquiry Date');
            // $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Enquiry Type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Usertype');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Name');
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Mobile No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Email Id');
            $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Sport Type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Coach Level');
            $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Number of sessions');
            $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Location');
            $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Description');
                      
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
                    $createdDate = !empty($arrayUser['createdDate']) ? date('d-m-Y',strtotime($arrayUser['createdDate'])) :'';
                         
                    $name = !empty($arrayUser['name']) ? ucwords($arrayUser['name']):'-';
                    $mob = !empty($arrayUser['mob']) ? ucwords($arrayUser['mob']):'';
                    $email = !empty($arrayUser['email']) ? $arrayUser['email']:'';
                    $comment = !empty($arrayUser['comment']) ? ucfirst($arrayUser['comment']):'-';
                    $sportname = !empty($arrayUser['sportname']) ? ucfirst($arrayUser['sportname']):'-';
                    $coach_level = !empty($arrayUser['coach_level']) ? ucfirst($arrayUser['coach_level']):'-';
                    $no_session = !empty($arrayUser['no_session']) ? ucfirst($arrayUser['no_session']):'-';
                    $location = !empty($arrayUser['location']) ? ucfirst($arrayUser['location']):'-';
                    $usertype2=array_column($arrayUser[0],'usertype');
                    $usertype= implode(",",$usertype2);
                       
                         

                    $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                        // ->setCellValue('C' . $counter, (!empty($enqtype) ? $enqtype : "-"))
                        ->setCellValue('C' . $counter, (!empty($usertype) ? $usertype : "-"))
                        ->setCellValue('D' . $counter, (!empty($name) ? $name : "-"))
                        ->setCellValue('E' . $counter, (!empty($mob) ? $mob : "-"))
                        ->setCellValue('F' . $counter, (!empty($email) ? $email : "-"))
                        ->setCellValue('G' . $counter, (!empty($sportname) ? $sportname : ""))
                        ->setCellValue('H' . $counter, (!empty($coach_level) ? $coach_level : ""))
                        ->setCellValue('I' . $counter, (!empty($no_session) ? $no_session : ""))
                        ->setCellValue('J' . $counter, (!empty($location) ? $location : ""))
                        ->setCellValue('K' . $counter, (!empty($comment) ? $comment : ""));
                              

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
            redirect(base_url() . 'admin/private-enquiry-export-to-excel');
        }
    }
    
}
