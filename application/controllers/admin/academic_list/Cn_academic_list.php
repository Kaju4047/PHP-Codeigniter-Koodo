<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_academic_list extends CI_Controller {

    public function academic_list() {
        //For DrpoDown Data
        //Sport List    
        $table = "sport";
        $select = "sportname,pk_id";
        $condition = array(
            'status =' => '1',
            'type' => '1',
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails;
        
        //City List
        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status' => '1',
        );
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name asc', '');
        $data['cityDetails'] = $cityDetails;

        $table = "academy";
        $select = "pk_id,coach_name";
        $condition = array(
            'status<>' => '3',
        );
        $this->db->group_by('coach_name');
        // $this->db->distinct('coach_name');
        $this->db->order_by('coach_name', 'asc');
        $academyName = $this->Md_database->getData($table, $select, $condition, '', '');
        $data['academyName'] = $academyName;

      #--------------------------------------------------------------------------------------#
       
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "academy";
        $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.venue,name,user_id,primary_mobile_no,secondary_mobile_no,academy.email,description,website";
        $this->db->distinct();
        $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
        // $this->db->join('city', 'academy.city=city.pk_id'); 
        $this->db->join('user', 'academy.user_id=user.pk_id'); 
        $condition = array(
            'academy.status !=' => '3',
            'user.status ' => '1',
        );
        $academyDetails = $this->Md_database->getData($table,$select, $condition, 'academy.pk_id DESC', '');
        // echo "<pre>";
        // print_r($academyDetails);
        // die();
        $total_records=!empty($academyDetails) ? count($academyDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	      $table = "academy";
            $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.city,academy.venue,sport.sportname,user.name,academy.user_id,primary_mobile_no,secondary_mobile_no,academy.email,description,website";
            // $this->db->join('city', 'academy.city=city.pk_id');      
            $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
            $this->db->join('user', 'academy.user_id=user.pk_id'); 
            $condition = array(
                'academy.status !=' => '3',
                 'user.status ' => '1',
            );
            $academyDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
            $data['coachName'] = $academyDetails;
            $data['academyDetails'] = $academyDetails;

            $params["results"] = $academyDetails;             
            $config['base_url'] = base_url() . 'admin/academic-list';
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
        $data['academyDetails']= $params["results"] ;
        // echo "<pre>";
        // print_r($data['academyDetails']);
        // die();
       //End:: pagination::- 
         $data['totalcount']=$total_records;

         $this->load->view('admin/academic_list/vw_academic_list',$data);
    }

    public function StatusChange($id, $status,$user_id){
      //Send notification 
        $table = "privileges_notifications";
        $select = "notifications,chat_notification";
        $this->db->where('fk_uid',$user_id);
        $this->db->order_by('pk_id','ASC');
        $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

        $notification=!empty($chechprivilege[0]['notifications'])?$chechprivilege[0]['notifications']:'';
       
        if ($notification=='1' ){
            $table = "user";
            $select = "token,user.pk_id,name";
            $this->db->where('pk_id',$user_id);
            $this->db->order_by('user.pk_id','ASC');
            $this->db->distinct();
            $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
            $target=$order_token[0]['token'];

                if ($status == '1' ) {
                    $message="Congratulations,                                         
        Your Coaching Academy profile has been approved, You are now listed on Koodo";
                }
                else{
                    $message="Your Coaching Academy profile has been temporarily blocked. Kindly contact Team Koodo for more information..";
                }
            }
            if(!empty($message)){
                $resultarray = array('message' => $message,'redirect_type' =>'view_on_app_list','subject'=>'Coaching Academy status');
                    
                $this->Md_database->sendPushNotification($resultarray,$target);

                //store into database typewise
                $table = "custom_notification";
                $insert_data = array(
                    'from_uid'=>'',
                    'to_user_id'=>$user_id,
                    'redirect_type' => 'view_on_app_list',
                    'subject' => 'Coaching Academy status',
                    'message'=>$message,
                    'status' => '1',
                    'created_by ' =>$user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                );
                $result = $this->Md_database->insertData($table, $insert_data);
            } 

        //add database
        $table = "academy";
        $user_data = array(
            'status' => $status,
            'updatedDate' => date('Y-m-d H:i:s'),
        );
        $condition = array("pk_id" => $id);
        $ret = $this->Md_database->updateData($table, $user_data, $condition);

        $actionMsg = 'Inactive';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "Status has been updated successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }


        redirect(base_url() . 'admin/academic-list');
    }

    public function view_academic_list($id){
    	$table = "academy";
        $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.venue,sport.sportname,academy.img";
            
        $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
        // $this->db->join('city', 'academy.city=city.pk_id'); 
        $condition = array(
            'academy.status !=' => '3',
            'academy.pk_id'=>$id,
        );
        $academyViewDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
        // print_r($academyViewDetails);
        // die();
        $data['academyViewDetails'] = $academyViewDetails[0];
        $this->load->view('admin/academic_list/vw_view_academic_list',$data);
    }

    public function academyFilter(){
        //Sport List
        $table = "sport";
        $select = "sportname,pk_id";
        $condition = array(
            'status' => '1',
            'type' => '1',
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails;

         $table = "academy";
        $select = "pk_id,coach_name";
        $condition = array(
            'status<>' => '3',
        );
        $this->db->group_by('coach_name');
        // $this->db->distinct('coach_name');
        $this->db->order_by('coach_name', 'asc');
        $academyName = $this->Md_database->getData($table, $select, $condition, '', '');
        $data['academyName'] = $academyName;

        //City List
        $table = "city";
        $select = "pk_id,city_name";
        $condition = array(
            'status ' => '1',
        );
        $cityDetails = $this->Md_database->getData($table, $select, $condition, 'city_name asc', '');
        $data['cityDetails'] = $cityDetails;
 
        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $coach  = !empty($this->input->get('coach')) ? $this->input->get('coach') : '';
        $city  = !empty($this->input->get('city')) ? $this->input->get('city') : '';

        $data['type']=$type;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['coach']=$coach;
        $data['city']=$city;
              
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "academy";
        $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.venue,sport.sportname,academy.user_id,primary_mobile_no,secondary_mobile_no,academy.email,description,website,user.name";
        // $this->db->join('city', 'academy.city=city.pk_id');    
        $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
          $this->db->join('user', 'academy.user_id=user.pk_id'); 
        $condition = array(
            'academy.status !=' => '3',
            'user.status' => '1',
        );
       
        if(!empty($fromdatefilter)){
            $condition['date(koodo_academy.start_date)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_academy.end_date)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['sport.pk_id']=$type;
        }
        if(!empty($coach)){
            $condition['academy.pk_id']=$coach;
        }
        if(!empty($city)){
            $condition['academy.city']=$city;
        }
        $academyDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
        $data['academyDetails'] = $academyDetails;

        $total_records=!empty($academyDetails) ? count($academyDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) 
        {
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "academy";
            $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.venue,sport.sportname,academy.user_id,primary_mobile_no,secondary_mobile_no,academy.email,description,website,user.name";
            // $this->db->join('city', 'academy.city=city.pk_id');    
            $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
                  $this->db->join('user', 'academy.user_id=user.pk_id'); 
            $condition = array(
                'academy.status !=' => '3',
                'user.status' => '1',
            );
              
            if(!empty($fromdatefilter)){
                $condition['date(koodo_academy.start_date)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_academy.end_date)<=']=$todatefilter;
            }
            if(!empty($type)){
                $condition['sport.pk_id']=$type;
            }
            if(!empty($coach)){
                $condition['academy.pk_id']=$coach;
            }
            if(!empty($city)){
                $condition['academy.city']=$city;
            }

            $academyDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
            $data['academyDetails'] = $academyDetails;
            $data['coachName'] = $academyDetails;
            $data['academyDetails'] = $academyDetails;

            $params["results"] = $academyDetails;             
            $config['base_url'] = base_url() . 'admin/filter-academy';
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
        $data['academyDetails']= $params["results"] ;
       //End:: pagination::- 
       $data['totalcount']=$total_records;
          $this->load->view('admin/academic_list/vw_academic_list',$data);
    }
 
  /*[Start ::  function collection log report export excel :]*/
  public function export_to_excel(){
      $this->load->library('Excel');
      $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
      $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
      $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
      $coach  = !empty($this->input->get('coach')) ? $this->input->get('coach') : '';  
      
      $sportName="";
      if (!empty($type)) {
          $table = "sport";
          $select = "sportname";
          $condition = array(
              'status !=' => '3',
              'pk_id' => $type,
          );
          $sportN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
          $sportName=$sportN[0]['sportname'];
      }

      $academyName="";
      if (!empty($coach)) {
          $table = "academy";
          $select = "coach_name";
          $condition = array(
              'status !=' => '3',
              'pk_id' => $coach,
          );
          $sportN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
          $academyName=$sportN[0]['coach_name'];
      }
      $sportName="";
      if (!empty($type)) {
          $table = "sport";
          $select = "sportname";
          $condition = array(
              'status !=' => '3',
              'pk_id' => $type,
          );
          $sportN = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
          $sportName=$sportN[0]['sportname'];
      }
        $table = "academy";
        $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,academy.city,academy.venue,sport.sportname,academy.user_id,user.name,primary_mobile_no,secondary_mobile_no,academy.email,description,website";
        // $this->db->join('city', 'academy.city=city.pk_id');    
        $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
        $this->db->join('user', 'academy.user_id=user.pk_id'); 
        $condition = array(
            'academy.status !=' => '3',
             'user.status ' => '1',
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_academy.start_date)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_academy.end_date)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['sport.pk_id']=$type;
        }
        if(!empty($coach)){
            $condition['academy.pk_id']=$coach;
        }
        if(!empty($city)){
            $condition['academy.city']=$city;
        }
        $academyDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
        $data['academyDetails'] = $academyDetails;
        // echo "<pre>";
        // print_r($academyDetails);
        // die();
      /*[:: Start Collection report excel sheet  Name::]*/
      $comm_title ="Academy List";
      $date_title ="all_time";
      $user_title ="all";
        /*[:: End Collection report excel sheet  Name::]*/

      if (!empty($academyDetails)) {
            $finalsArray = $academyDetails;
            $this->excel->getActiveSheet()->setTitle('RemarksReport');
            $date = date('d-m-Y g:i A'); // get current date time
            $cnt = count($finalsArray);
              
            $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter,'Sport Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $sportName);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter,'Coach Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $academyName);
             
            $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Coaching Academy Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'User Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Sport Type');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'City');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Start Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'End Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Time ');
              $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'No. Of Students');
              $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Fees');
              $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Primary Mobile Number');
              $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Secondary Mobile Number');
              $this->excel->setActiveSheetIndex(0)->setCellValue('M'.$counter, 'Email');
              $this->excel->setActiveSheetIndex(0)->setCellValue('N'.$counter, 'Website');
              // venue,sportname            
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
              $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
              $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
             
              $from = "A1"; // or any value
              $to = "N1"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
              $from1 = "A2"; // or any value
              $to1 = "N2"; // or any value
              $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);

              $date = date('d-m-Y g:i A');
              $cnt = count($finalsArray);
            $counter = 3;

              if (!empty($finalsArray)) {
                  $j = 1;
                  foreach ($finalsArray as $arrayUser) {
                      $coach_name = !empty($arrayUser['coach_name']) ? $arrayUser['coach_name'] :'';
                      $name = !empty($arrayUser['name']) ? $arrayUser['name'] :'';
                      $start_date = !empty($arrayUser['start_date']) ?date('d-m-Y',strtotime( $arrayUser['start_date'])) :'';
                      $end_date = !empty($arrayUser['end_date']) ? date('d-m-Y',strtotime($arrayUser['end_date'])) :'-';
                      $student_number = !empty($arrayUser['student_number']) ? ucwords($arrayUser['student_number']):'-';
                      $academy_time = !empty($arrayUser['academy_time']) ? date(' h:i A',strtotime($arrayUser['academy_time'])):''; 
                      $fees = !empty($arrayUser['fees']) ? $arrayUser['fees']:'0';
                      $sportname = !empty($arrayUser['sportname']) ? $arrayUser['sportname']:'-';
                      $city =!empty($arrayUser['city_name']) ? $arrayUser['city_name'] : '-' ;
                      $primary_mobile_no =!empty($arrayUser['primary_mobile_no']) ? $arrayUser['primary_mobile_no'] : '-' ;
                      $secondary_mobile_no =!empty($arrayUser['secondary_mobile_no']) ? $arrayUser['secondary_mobile_no'] : '-' ;
                      $email =!empty($arrayUser['email']) ? $arrayUser['email'] : '-' ;
                      $website =!empty($arrayUser['website']) ? $arrayUser['website'] : '-' ;
                         
                      $this->excel->setActiveSheetIndex(0)
                          ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                          ->setCellValue('B' . $counter, (!empty($coach_name) ? $coach_name : "-"))
                          ->setCellValue('C' . $counter, (!empty($name) ? $name : "-"))

                          ->setCellValue('D' . $counter, (!empty($sportname) ? $sportname : "-"))
                          ->setCellValue('E' . $counter, (!empty($city) ? $city : "-"))
                          ->setCellValue('F' . $counter, (!empty($start_date) ? $start_date : "-"))
                          ->setCellValue('G' . $counter, (!empty($end_date) ? $end_date : "-"))
                          ->setCellValue('H' . $counter, (!empty($academy_time) ? $academy_time : "0"))
                          ->setCellValue('I' . $counter, (!empty($student_number) ? $student_number : "0"))
                          ->setCellValue('J' . $counter, (!empty($fees) ? $fees : "0"))
                          ->setCellValue('K' . $counter, (!empty($primary_mobile_no) ? $primary_mobile_no : "-"))
                          ->setCellValue('L' . $counter, (!empty($secondary_mobile_no) ? $secondary_mobile_no : "-"))
                          ->setCellValue('M' . $counter, (!empty($email) ? $email : "-"))
                          ->setCellValue('N' . $counter, (!empty($website) ? $website : "-"));
                                  
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
  /*[End ::  function collection log report export excel :]*/
   //For Download PDF
    // public function AgentOrderInvoice($order_id)
  //   public function AgentOrderInvoice()
  //   {   
  //       $data['order'] = 'Order';
  //       //echo $order_id;die;
  //       $order_id='13';
  //         $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
  //     $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
  //     $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
  //     $coach  = !empty($this->input->get('coach')) ? $this->input->get('coach') : '';
  //      //$order_id = $this->input->post('order_id') ? $this->input->post('order_id') : '';
  //       // if (!empty($order_id)) {
  //           // $orderDetails        = $this->Md_agent->getAgentOrderById($order_id);
  //           // $orderProductDetails = $this->Md_salesuser->getOrderProductByOrderId($order_id);
  //           // $arrayProduct        = array();
  //           // $orderdata=array();
  //           // if (!empty($orderDetails)) {
                
  //               // if (!empty($orderProductDetails)) {
  //               //     foreach ($orderProductDetails as $orderProduct) {
  //               //         $arrayProduct[] = array(
  //               //             'pname' => $orderProduct->pname,
  //               //             'product_id' => $orderProduct->product_id,
  //               //             'price' => $orderProduct->price,
  //               //             'qty' => $orderProduct->qty,
  //               //             'total_amt' => $orderProduct->total_amt
  //               //         );
  //               //     }
  //               // }
              
  //               // $img = base_url('assets/agents/') . $orderDetails->agent_store_image;
                
  //              // $invoice_header= base_url('assets/retailers/') .$orderDetails->invoice_header.'.jpg';
  //               $orderdata   = array(
  //                   "order_id" => '123',
  //                   "city" => 'pune',
  //                   // "order_id" => $orderDetails->order_id,
  //                  // "invoice_header"=>$invoice_header,
  //                  // "sales_user_name"=>$orderDetails->fullname,
  //                  //  "agent_name" => $orderDetails->agent_name,
  //                  //  "company_name" => $orderDetails->company_name,
  //                  //  "agent_store_image" => $img,
  //                  //  "agent_mobileno" => $orderDetails->agent_mobileno,
  //                  //  "agent_location" => $orderDetails->agent_location,
  //                  //  "payment_status" => $orderDetails->payment_status,
  //                  //  "paid_amt" => $orderDetails->paid_amt,
  //                  //  "paid_date" => !empty($orderDetails->paid_date) ? date('d-m-Y', strtotime($orderDetails->paid_date)) : '',
  //                  //  "due_amt" => $orderDetails->due_amt,
  //                  //  "total_qty" => $orderDetails->total_qty,
  //                  //  "total_amt" => $orderDetails->total_amt,
  //                  //  "created_date" => date('d-m-Y h:i:sa', strtotime($orderDetails->created_date)),
  //                  //  "date" => date('d', strtotime($orderDetails->created_date)),
  //                  //  "year" => date('M y', strtotime($orderDetails->created_date)),
  //                   // "products" => $arrayProduct
  //                   "sales_user_name"=>'abc',
  //                   "agent_name" => 'xyz',
  //                   "company_name" => 'pqr',
  //                   // "agent_store_image" => $img,
  //                   // "agent_mobileno" => $orderDetails->agent_mobileno,
  //                   // "agent_location" => $orderDetails->agent_location,
  //                   // "payment_status" => $orderDetails->payment_status,
  //                   // "paid_amt" => $orderDetails->paid_amt,
  //                   // "paid_date" => !empty($orderDetails->paid_date) ? date('d-m-Y', strtotime($orderDetails->paid_date)) : '',
  //                   // "due_amt" => $orderDetails->due_amt,
  //                   // "total_qty" => $orderDetails->total_qty,
  //                   // "total_amt" => $orderDetails->total_amt,
  //                   // "created_date" => date('d-m-Y h:i:sa', strtotime($orderDetails->created_date)),
  //                   // "date" => date('d', strtotime($orderDetails->created_date)),
  //                   // "year" => date('M y', strtotime($orderDetails->created_date)),
  //               );
  //           // }
  //                 $table = "academy";
  //     $select="academy.pk_id,academy.status,academy.coach_name,academy.sport_type,academy.start_date,academy.end_date,academy.student_number,academy.fees,academy.academy_time,city.city_name,academy.venue,sport.sportname";
  //     $this->db->join('sport', 'academy.sport_type=sport.pk_id'); 
  //     $this->db->join('city', 'academy.city=city.pk_id'); 
  //     $condition = array(
  //         'academy.status !=' => '3',
  //     );
       
  //     if(!empty($fromdatefilter)){
  //         $condition['date(koodo_academy.start_date)>=']=$fromdatefilter;
  //     }
  //     if(!empty($todatefilter)){
  //         $condition['date(koodo_academy.end_date)<=']=$todatefilter;
  //     }
  //     if(!empty($type)){
  //         $condition['sport.pk_id']=$type;
  //     }
  //     if(!empty($coach)){
  //         $condition['academy.pk_id']=$coach;
  //     }
  //     $academyDetails = $this->Md_database->getData($table, $select, $condition, 'academy.pk_id DESC', '');
  //               $resultarray = array(                   
  //                   'academyDetails' => $academyDetails                 
  //               );
  //               // echo "<pre>";
  //               // print_r($resultarray);
  //               // die();
  //       $url_link = $this->load->view('admin/academic_list/vw_academic_list', $resultarray,true);
  //        // print_r($htmls);die();
  //       // $created_date = date('d-m-Y h:i:sa', strtotime($orderDetails->created_date));
  // //        $created_date = date('Y-m-d H:i:s');
  // //         $this->load->library('M_pdf');

  // //         $mpdf = new mPDF('', 'Legal');
  // //         $mpdf->AddPage('P', // L - landscape, P - portrait
  // //                 '', '', '', '', 1, // margin_left
  // //                 1, // margin right
  // //                 1, // margin top
  // //                 1, // margin bottom
  // //                 1, // margin header
  // //                 1); // margin footer
  // // //            die;
  // //         ob_clean();
  // //         $mpdf->allow_charset_conversion = true;
  // //         $mpdf->charset_in = 'ISO-8859-2';
  // //         $mpdf->SetDisplayMode('fullpage');
  // //         $mpdf->WriteHTML($htmls);
  // //         $pdfFilePath = FCPATH."uploads/academy/" .$order_id."_".$created_date.".pdf";
  // //         // $mpdf->Output($path, 'F');
  // //         $this->m_pdf->pdf->Output($pdfFilePath, "D"); 

  //       //   $resultarray = array(
  //       //         'error_code' => '1',
  //       //          'agent_invoice_path' => $path,
  //       //     );
        
  //           $this->load->library('M_pdf');
  //           $this->m_pdf->pdf->AddPage(); // margin footer
  //             $pdfFilePath = "Order_Invoices.pdf";
  //             $this->m_pdf->pdf->WriteHTML($url_link);
             
  //             $this->m_pdf->pdf->Output($pdfFilePath, "D");     
  //       // echo json_encode($resultarray);
  //       // exit();
  //   }
}

