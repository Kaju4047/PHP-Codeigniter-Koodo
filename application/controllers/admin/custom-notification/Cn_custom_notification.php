<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_custom_notification extends CI_Controller {

    public function notification() { 
        $table = "notification_type";
        $select = "pk_id,custtype,usertype";
        $condition = array(
            'status !=' => '3'
        );
        $this->db->order_by('pk_id', 'ASC');
        $notificationTypeDetails = $this->Md_database->getData($table, $select, $condition, 'pk_id ASC', '');
        $data['notificationTypeDetails'] = $notificationTypeDetails;
      
        //start:: pagination:- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        
        $total_records = "";
        $table = "notification";
        $select = "*";
        $condition = array(
            'notification.status !=' => '3'
        );
        $this->db->join('notification_type', 'notification_type.pk_id = notification.type','left');
        $this->db->order_by('notification.pk_id', 'DESC');
        $notificationDetails = $this->Md_database->getData($table, $select, $condition, 'notification.pk_id DESC', '');

        $total_records=!empty($notificationDetails) ? count($notificationDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "notification";
            $select = "*";
            $condition = array(
                'notification.status !=' => '3'
             );
            $this->db->order_by('notification.pk_id', 'DESC');
            $this->db->join('notification_type', 'notification_type.pk_id = notification.type','left');
            $notificationDetails = $this->Md_database->getData($table, $select, $condition, 'notification.pk_id DESC', '');
            $data['notificationDetails'] = $notificationDetails;
            // echo "<pre>";
            // print_r($notificationDetails);
            // die();
            $params["results"] = $notificationDetails;             
            $config['base_url'] = base_url() . 'admin/notification';
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
        $data['notificationDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/custom-notification/vw_notification',$data);
    }

    public function notificationAction(){
    
    	$type = !empty($this->input->post('type')) ? $this->input->post('type') : '';//type is depond on profile player, coach,other list..etc
    	$subject = !empty($this->input->post('subject')) ? $this->input->post('subject') : '';
    	$message = !empty($this->input->post('message')) ? $this->input->post('message') : '';

   	    $this->form_validation->set_rules('type', 'Type', 'required|trim');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|max_length[500]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }
         
        //Store Notification in table with user type wise..
        $table = "notification";
        $insert_data = array(
            'type' => $type,
            'redirect_type' => 'cust_note',
            'subject' => $subject,
            'message'=>$message,
            'status' => '1',
            'created_by ' => $this->session->userdata['UID'],
            'created_date' => date('Y-m-d H:i:s')
        );
        $result = $this->Md_database->insertData($table, $insert_data);
         
        //Type 4 for All Users send Notification
        if ($type=='4') {
            $table = "user";
            $select = "token,user.pk_id";
            $this->db->join('profile_type','user.pk_id = profile_type.user_id');
            $this->db->order_by('user.pk_id','ASC');
            $this->db->where('user.status','1');
            $this->db->distinct();
            $order_mobile_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');

            foreach ($order_mobile_token as $key => $value) {
                $target=$value['token'];
                $pk_id=$value['pk_id'];
            // echo "<pre>";
            // print_r($pk_id);
            // print_r($target);

                $resultarray = array('message' => $message, 'redirect_type' => 'cust_note', 'subject' => $subject);
              
                $table = "privileges_notifications";
                $select = "notifications,chat_notification";
                $this->db->where('fk_uid',$pk_id);
                $this->db->order_by('pk_id','ASC');
                $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                $notification=$chechprivilege[0]['notifications'];
                if ($notification == '1') {
                    $this->Md_database->sendPushNotification($resultarray,$target);
                } 
            }  

                // die();
            $table = "profile_type";
            $orderby = 'pk_id asc';
            $condition = array('status' => '1');
            $col = array('user_id','usertype');
            $users = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status<>' => '3');
            $col = array('pk_id');
            $user = $this->Md_database->getData($table, $col, $condition, $orderby, '');
               
            foreach ($user  as $key => $value) {
                $table = "custom_notification";
                $insert_data = array(
                    'to_user_id'=>$value['pk_id'],
                    'usertype' => '4',
                    'subject' => $subject,
                    'redirect_type' => 'cust_note',
                    'message'=>$message,
                    'status' => '1',
                    'created_by ' => $this->session->userdata['UID'],
                    'created_date' => date('Y-m-d H:i:s')                
                );
                $result = $this->Md_database->insertData($table, $insert_data);
            }
        }else{             
            if ($type == '5' ||  $type == '6' || $type == '7' || $type == '8' ||$type == '8'||$type == '10') {
                $usertype = ' 3';
            }elseif ($type == '1') {
               $usertype = '1';
            }elseif ($type == '2') {
               $usertype = '2';
            }
            $table = "user";
            $select = "token,user.pk_id,user.name";
            $condition =array('profile_type.status'=>'1');
            $this->db->join('profile_type','user.pk_id = profile_type.user_id');
            $this->db->join('profie_player_sport','user.pk_id = profie_player_sport.user_id');
            $this->db->where('profile_type.usertype',$usertype);
            $this->db->where('user.status','1');
            if ($type == '5') {//Sport Dealer
                $this->db->where('profie_player_sport.sportname','24');
            }elseif ($type == '6') { // Physiotherapist
                $this->db->where('profie_player_sport.sportname','2');
            }elseif ($type == '7') { //Orthopedic
                $this->db->where('profie_player_sport.sportname','16');
            }elseif ($type == '8') { //Dietitian
                $this->db->where('profie_player_sport.sportname','21');
            }elseif ($type == '9') { //Treatment & Spa
                $this->db->where('profie_player_sport.sportname','22');
            }elseif ($type == '10') { //Guest User
                $this->db->where('profie_player_sport.sportname','40');
            }
            $this->db->group_by('profie_player_sport.user_id');
            $this->db->order_by('user.pk_id','ASC');
            $order_mobile_token = $this->Md_database->getData($table, $select, $condition, 'user.pk_id ASC','');
     
            foreach ($order_mobile_token as $key => $value){
                $resultarray = array('message' => $message, 'redirect_type' => 'cust_note', 'subject' => $subject);
                $target=$value['token'];
                $pk_id=$value['pk_id'];
                
                $table = "privileges_notifications";
                $select = "notifications,chat_notification";
                $this->db->where('fk_uid',$pk_id);
                $this->db->order_by('pk_id','ASC');
                $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');
                $notification=$chechprivilege[0]['notifications'];
                
                if ($notification == '1'){
                    $table = "custom_notification";
                    $insert_data = array(
                        'to_user_id'=>$value['pk_id'],
                        'usertype' => $type,
                        'subject' => $subject,
                        'message'=>$message,
                        'redirect_type' => 'cust_note',
                        'status' => '1',
                        'created_by ' => $this->session->userdata['UID'],
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $result = $this->Md_database->insertData($table, $insert_data);


                    $this->Md_database->sendPushNotification($resultarray,$target);
                    // exit();
                }
            }      
        }
      
       
        $this->session->set_flashdata('success', 'Notification has been inserted successfully.');
         redirect(base_url() .'admin/notification');
     
    }

  public function cust_note_export_to_excel(){
    $this->load->library('Excel');
    $table = "notification";
    $select = "*";
    $condition = array(
        'notification.status ' => '1'
    );
    $this->db->order_by('notification.pk_id', 'DESC');
    $this->db->join('notification_type', 'notification_type.pk_id = notification.type','left');
    $notificationDetails = $this->Md_database->getData($table, $select, $condition, 'notification.pk_id DESC', '');
    $data['notificationDetails'] = $notificationDetails;
                    /*[:: Start Collection report excel sheet  Name::]*/
    $comm_title ="Custom Notification List";
    $date_title ="all_time";
    $user_title ="all";

    /*[:: End Collection report excel sheet  Name::]*/

    if (!empty($notificationDetails)) {
        $finalsArray = $notificationDetails;
        $this->excel->getActiveSheet()->setTitle('Custom Notification List');
        $date = date('d-m-Y g:i A'); // get current date time
        $cnt = count($finalsArray);

        $counter = 1;
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date & Time ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Usertype ');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, '  Subject');
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Message');
                       
            // set auto size for columns
            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
             
             
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
                    $createdDate = !empty($arrayUser['created_date']) ? date('d-m-Y H:i:sa',strtotime($arrayUser['created_date'])) :'';
                    if (!empty($arrayUser['type'])) {
                        if ($arrayUser['type'] == 1) {
                            $type = 'Player';
                        }
                        elseif ($arrayUser['type'] == 2) {
                            $type = 'Coach';
                        }
                        elseif ($arrayUser['type'] == 3) {
                            $type = 'Other';
                        }
                        elseif ($arrayUser['type'] == 4) {
                            $type = 'All';
                        }
                    }
                    $subject = !empty($arrayUser['subject']) ? $arrayUser['subject']:'';
                    $message = !empty($arrayUser['message']) ? ucfirst($arrayUser['message']):'-';

                  $this->excel->setActiveSheetIndex(0)
                      ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                      ->setCellValue('B' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                      ->setCellValue('C' . $counter, (!empty($type) ? $type : "-"))
                      ->setCellValue('D' . $counter, (!empty($subject) ? $subject : "-"))
                      ->setCellValue('E' . $counter, (!empty($message) ? $message : "-"));
                              
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
          redirect(base_url() . 'admin/cust-note-export-to-excel');
         }
    }
}
