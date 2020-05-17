<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_career extends CI_Controller {

    public function career() {
        // $table = "usertype";
        // $select = "*";
        // $condition = array(
        //     'status !=' => '3'
        // );
        // $this->db->order_by('pk_id', 'ASC');
        // $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        // $data['usertypeDetails'] = $usertypeDetails; 
         // print_r( $data['usertypeDetails']);

        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "career";
        $select="career.pk_id,career.user_id,career.status,career.expected_salary,career.cv,user.name,user.name,user.mob,user.regdate,user.email,user.edudetails,career.createdDate,career.qualification,career.profile";
        $this->db->distinct();  
        $this->db->join('user ', 'career.user_id=user.pk_id'); 
        // $this->db->join('usertype ', 'usertype.pk_id=user.usertype'); 
        $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
         // $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
        $condition = array(
            'career.status !=' => '3',
            'user.status !=' => '3',
        );
        $this->db->order_by('pk_id', 'DESC');
        $careerDetails1 = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $careerDetails=array();
        foreach ($careerDetails1 as $careerDetails){
            $uid= $careerDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";             
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
            $condition = array(
                'UA.status !=' => '3',
                'profile_type.user_id'=>$uid,
            );

            $careerDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $addmoredetails[]=$careerDetails;
        }
        $data['addmoredetails'] = $addmoredetails; 

        $total_records=!empty($addmoredetails) ? count($addmoredetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
    	      $table = "career";
            $select="career.pk_id,career.user_id,career.status,career.expected_salary,career.cv,user.name,user.name,user.mob,user.regdate,user.email,user.edudetails,career.createdDate,career.qualification,career.profile";
            $this->db->distinct();  
            $this->db->join('user ', 'career.user_id=user.pk_id'); 
        // $this->db->join('usertype ', 'usertype.pk_id=user.usertype'); 
            $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
         // $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
            $condition = array(
                'career.status !=' => '3',
                'user.status !=' => '3',
            );
            $this->db->order_by('pk_id', 'DESC');
            $careerDetails1 = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', ''); 
          
            $user = array();
            $careerDetails=array();
            foreach ($careerDetails1 as $careerDetails)
            {
                $uid= $careerDetails['user_id'];
                $table = "profile_type";
                $select = "usertype.usertype,profile_type.usertype as userid";             
                $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 

                $condition = array(
                    'UA.status !=' => '3',
                    'profile_type.user_id'=>$uid,
                );
                $careerDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
                   $user[]=$careerDetails;
            }
            $data['careerDetails'] = $user;
            //   echo "<pre>"; 
            // print_r($data['careerDetails']);
            // exit();
            $params["results"] = $user;             
            $config['base_url'] = base_url() . 'admin/career';
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
        $data['careerDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;
       //  echo "<pre>";
       // print_r(  $data['careerDetails']);
       // die();

        $this->load->view('admin/career/vw_career',$data);
    }
    
    public function StatusChange($id, $status) {

        $table = "career";
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
        }else {
            $this->session->set_flashdata('error', "Status $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
        redirect(base_url() . 'admin/career');
    }

    public function filterCareer(){

        // $table = "usertype";
        // $select = "*";
        // $condition = array(
        //     'status !=' => '3'
        // );
        // $this->db->order_by('pk_id', 'ASC');
        // $usertypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        // $data['usertypeDetails'] = $usertypeDetails;
 
        $profile = !empty($this->input->get('profile')) ? trim($this->input->get('profile')) : '';
        $datefilter = !empty($this->input->get('date')) ? date("Y-m-d" ,strtotime($this->input->get('date')) ): '';
              
        $data['profile']=$profile;
        $data['datefilter']=$datefilter;

        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "career";
        $select="career.pk_id,career.user_id,career.status,career.expected_salary,career.cv,user.name,user.name,user.mob,user.regdate,user.email,user.edudetails,career.createdDate,career.qualification,career.profile";
        $this->db->distinct();  
        $this->db->join('user ', 'career.user_id=user.pk_id'); 
        // $this->db->join('usertype ', 'usertype.pk_id=user.usertype'); 
        $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
         // $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
        $condition = array(
            'career.status !=' => '3',
            'user.status !=' => '3',
             // 'PT.usertype'=>$type,
            'date(koodo_career.createdDate)='=>$datefilter,
        );
        if (!empty($profile)) {
            $this->db->where("career.profile LIKE '%$profile%'");             
        }
        $this->db->order_by('pk_id', 'DESC');
        $careerDetails1 = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
        $user=array();
        $careerDetails=array();
        foreach ($careerDetails1 as $careerDetails){
            $uid= $careerDetails['user_id'];
            $table = "profile_type";
            $select = "usertype.usertype";             
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 

            $condition = array(
                'UA.status !=' => '3',
                'profile_type.user_id'=>$uid,
            );

            $careerDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $user[]=$careerDetails;
        }
          // $data['careerDetails'] = $user;
        $data['addmoredetails'] = $user; 
 
        $total_records=!empty($user) ? count($user) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "career";
            $select="career.pk_id,career.user_id,career.status,career.expected_salary,career.cv,user.name,user.name,user.mob,user.regdate,user.email,user.edudetails,career.createdDate,career.qualification,career.profile";
            $this->db->distinct();  
            $this->db->join('user ', 'career.user_id=user.pk_id'); 
            // $this->db->join('usertype ', 'usertype.pk_id=user.usertype'); 
            $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
            // $this->db->join('usertype as UT','PT.usertype = UT.pk_id'); 
            $condition = array(
                'career.status !=' => '3',
                'user.status !=' => '3',
                // 'PT.usertype'=>$type,
                'date(koodo_career.createdDate)='=>$datefilter,
            );
            if (!empty($profile)) {
                $this->db->where("career.profile LIKE '%$profile%'");             
            }
            $this->db->order_by('pk_id', 'DESC');
            $careerDetails1 = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

            $user=array();
            $careerDetails=array();
            foreach ($careerDetails1 as $careerDetails){
                $uid= $careerDetails['user_id'];
              
                $table = "profile_type";
                $select = "usertype.usertype,profile_type.usertype as userid";             
                $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
                $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 
                $condition = array(
                    'UA.status !=' => '3',
                    'profile_type.user_id'=>$uid,
                );
                $careerDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
                $user[]=$careerDetails;
            }
            $data['careerDetails'] = $user;
 
            $params["results"] = $user;             
            $config['base_url'] = base_url() . 'admin/filter-career';
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
        $data['careerDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;        
        $this->load->view('admin/career/vw_career',$data);
    }
     
    public function userView($pk_id,$userid){
        $table = "career";
        $select = "career.pk_id ,user.name,user.mob,user.regdate,user.email,user.city,user.status,koodo_user.createdDate,UT.usertype,user.dob,user.address,user.gender,user.age,user.edudetails,user.img";
        $this->db->join('user ','career.user_id = user.pk_id'); 
        // $this->db->join('usertype ','user.usertype = usertype.pk_id');
        $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
        $this->db->join('usertype as UT','PT.usertype = UT.pk_id');  
        $condition = array(
            'user.status !=' => '3',
            'career.pk_id'=>$pk_id,
        );
        $usersDetails = $this->Md_database->getData($table, $select, $condition, 'career.pk_id DESC', '');
        $data['career']=$pk_id;
        $data['usersDetails'] = $usersDetails; 
        // print_r($data['career']);
        // exit();
        if ($userid=='Player') {
           $this->load->view('admin/users/vw_view_user_player',$data);
        }
        if ($userid=='Dealer') {
            $this->load->view('admin/users/vw_view_user_others',$data); 
        }
        if ($userid =='Coach') {
            $this->load->view('admin/users/vw_view_user_coach',$data);
        }
    } 

    public function career_export_to_excel(){

        $this->load->library('Excel');

         $profile = !empty($this->input->get('profile')) ? trim($this->input->get('profile')) : '';
        $datefilter = !empty($this->input->get('date')) ? date("Y-m-d" ,strtotime($this->input->get('date')) ): '';
       
        $data['profile']=$profile;
        $data['datefilter']=$datefilter;

        // $usertype='';
        // if (!empty($type)) {
        //     if ($type==1) {
        //         $usertype='Player';
        //     }
        //     if ($type==2) {
        //         $usertype='Coach';
        //     }
        //     if ($type==3) {
        //         $usertype='Other';
        //     }
        // }

        $table = "career";
        $select="career.pk_id,career.user_id,career.status,career.expected_salary,career.cv,user.name,user.name,user.mob,user.regdate,user.email,user.edudetails,career.createdDate,career.qualification,career.profile,user.age,user.gender,user.dob,user.address,city.city_name";
        $this->db->distinct();  
        $this->db->join('user ', 'career.user_id=user.pk_id'); 
        // $this->db->join('usertype ', 'usertype.pk_id=user.usertype'); 
        $this->db->join('profile_type as PT','PT.user_id = user.pk_id'); 
        $this->db->join('city','city.pk_id = user.city'); 
        $condition = array(
            'career.status!=' => '3',
            'user.status !=' => '3',
             // 'PT.usertype'=>$type,
            'date(koodo_career.createdDate)='=>$datefilter,
        );
        $this->db->order_by('pk_id', 'DESC');
        $careerDetails1 = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');

        $user=array();
        $careerDetails=array();
        foreach ($careerDetails1 as $careerDetails){
            $uid= $careerDetails['user_id'];
              
            $table = "profile_type";
            $select = "usertype.usertype";             
            $this->db->join('user as UA','profile_type.user_id = UA.pk_id');
            $this->db->join('usertype ','profile_type.usertype = usertype.pk_id'); 

            $condition = array(
                'UA.status !=' => '3',
                'profile_type.user_id'=>$uid,
            );
            $careerDetails[] = $this->Md_database->getData($table, $select, $condition, 'UA.pk_id DESC', '');         
            $user[]=$careerDetails;
        }
        $data['careerDetails'] = $user;
        
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="Career User List";
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($user)) {
            $finalsArray = $user;
            $this->excel->getActiveSheet()->setTitle('RemarksReport');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
          $counter = 1; 
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $datefilter);
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Profile');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $profile);
 
          $counter = 2;
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Name');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Mobile Number');
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Email Id');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Profile  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Qualification');
            $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Expected Salary  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Edu Details');
            $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'Usertype');
            $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Age  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Gender  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('M'.$counter, 'DOB  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('N'.$counter, 'City  ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('O'.$counter, 'Address  ');

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
            $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
             
            $from = "A1"; // or any value
            $to = "O1"; // or any value
            $this->excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
            $from1 = "A2"; // or any value
            $to1 = "O2"; // or any value
            $this->excel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);

            $date = date('d-m-Y g:i A');
            $cnt = count($finalsArray);
            $counter = 3;

            if (!empty($finalsArray)){
                $j = 1;
                foreach ($finalsArray as $arrayUser) {                       
                    $name = !empty($arrayUser['name']) ? ucwords($arrayUser['name']) :'';
                    $email = !empty($arrayUser['email']) ?$arrayUser['email']:'';
                    $regdate = !empty($arrayUser['createdDate']) ? date('d-m-Y h:ia',strtotime($arrayUser['createdDate'])):'-';
                    $edudetails = !empty($arrayUser['edudetails']) ? ucfirst($arrayUser['edudetails']):'-';
                    $qualification = !empty($arrayUser['qualification']) ? $arrayUser['qualification']:'-';
                    $profile = !empty($arrayUser['profile']) ? $arrayUser['profile']:'';
                    $age = !empty($arrayUser['age']) ?$arrayUser['age']:'-';
                    $gender =!empty($arrayUser['gender']) ? ucwords($arrayUser['gender']) : '-' ;
                    $mobile =!empty($arrayUser['mob']) ? ucwords($arrayUser['mob']) : '-' ;
                    $dob = !empty($arrayUser['dob']) ? date('d-m-Y h:ia',strtotime($arrayUser['dob'])):'-';
                    $address =!empty($arrayUser['address']) ? ucfirst($arrayUser['address']) : '-' ;
                    $city =!empty($arrayUser['city_name']) ? ucfirst($arrayUser['city_name']) : '-' ;
                    $expected_salary =!empty($arrayUser['expected_salary']) ? $arrayUser['expected_salary'] : '-' ;
                    $usertype2=array_column($arrayUser[0],'usertype');
                    $usertype= implode(",",$usertype2);
                         

                    $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($regdate) ? $regdate : "-"))
                        ->setCellValue('C' . $counter, (!empty($name) ? $name : "-"))
                        ->setCellValue('D' . $counter, (!empty($mobile) ? $mobile : "-"))
                        ->setCellValue('E' . $counter, (!empty($email) ? $email : "-"))
                        ->setCellValue('F' . $counter, (!empty($profile) ? $profile : "-"))
                        ->setCellValue('G' . $counter, (!empty($qualification) ? $qualification : "-"))
                        ->setCellValue('H' . $counter, (!empty($expected_salary) ? $expected_salary : "-"))
                        ->setCellValue('I' . $counter, (!empty($edudetails) ? $edudetails : "-"))
                        ->setCellValue('J' . $counter, (!empty($usertype) ? $usertype : "-"))
                        ->setCellValue('K' . $counter, (!empty($age) ? $age : "-"))
                        ->setCellValue('L' . $counter, (!empty($gender) ? $gender : "-"))
                        ->setCellValue('M' . $counter, (!empty($dob) ? $dob : "-"))
                        ->setCellValue('N' . $counter, (!empty($city) ? $city : "-"))
                        ->setCellValue('O' . $counter, (!empty($address) ? $address : "-"));
       
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
            redirect(base_url() . 'admin/buy-sub-export-to-excel');
        }
    }
}
