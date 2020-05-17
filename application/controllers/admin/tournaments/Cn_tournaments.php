<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_tournaments extends CI_Controller {

    public function tournaments_list() {
    	$table = "sport";
        $select = "pk_id,sportname";
        $condition = array(
            'status !=' => '3',
              'type' => '1',
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails; 
        $venue = !empty($this->input->get('venue')) ? $this->input->get('venue') : '';
           $data['venue']=$venue;

        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "tournaments";
        $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,u.mob,a.sportname,u.name as created_by,tournaments.entry_form,tournaments.status,tournaments.address,tournaments.email,website,time,draws_doc,entery_number,user_id";

        $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
        $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
        $condition = array(
            'tournaments.status !=' => '3',
            'u.status !=' => '3',
        );
        $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');

        $total_records=!empty($tounamentsDetails) ? count($tounamentsDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "tournaments";
            $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,u.mob,a.sportname,u.name as created_by,tournaments.entry_form,tournaments.status,tournaments.address,tournaments.email,website,time,draws_doc,entery_number,user_id";
            $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
            $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
            // $this->db->join('city as b', 'tournaments.city=b.pk_id');
            $condition = array(
                'tournaments.status !=' => '3',
                'u.status !=' => '3',
            );
            $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
            $data['tounamentsDetails'] = $tounamentsDetails;

            $params["results"] = $tounamentsDetails;             
            $config['base_url'] = base_url() . 'admin/tournaments-list';
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
        $data['tounamentsDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;

        $this->load->view('admin/tournaments/vw_tournaments_list',$data);
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

                if ($status == '1' ){
                    $message="Congratulations,                                         
        Your Tournament profile has been approved, You are now listed on Koodo";
                }
                else{
                    $message="Your Tournament profile has been temporarily blocked. Kindly contact Team Koodo for more information..";
                }
            }
            if(!empty($message)){
                $resultarray = array('message' => $message,'redirect_type' =>'view_on_app_list','subject'=>'Tournament status');
                    
                $this->Md_database->sendPushNotification($resultarray,$target);

                //store into database typewise
                $table = "custom_notification";
                $insert_data = array(
                    'from_uid'=>'',
                    'to_user_id'=>$user_id,
                    'redirect_type' => 'view_on_app_list',
                    'subject' => 'Tournament status',
                    'message'=>$message,
                    'status' => '1',
                    'created_by ' =>$user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                );
                $result = $this->Md_database->insertData($table, $insert_data);
            } 


        //add in database
        $table = "tournaments";
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
        redirect(base_url() . 'admin/tournaments-list');
    }

    public function filterTournament(){
        $table = "sport";
        $select = "pk_id,sportname";
        $condition = array(
            'status !=' => '3',
              'type' => '1',
        );
        $sportDetails = $this->Md_database->getData($table, $select, $condition, 'sportname asc', '');
        $data['sportDetails'] = $sportDetails; 

        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';
        $venue = !empty($this->input->get('venue')) ? trim($this->input->get('venue')) : '';
           
        $data['venue']=$venue;
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
               $table = "tournaments";
        $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,u.mob,a.sportname,u.name as created_by,tournaments.entry_form,tournaments.status,tournaments.address,tournaments.email,website,time,draws_doc,entery_number";

        $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
        $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
        $condition = array(
            'tournaments.status !=' => '3',
            'u.status !=' => '3',
        );     
        if(!empty($fromdatefilter)){
            $condition['date(koodo_tournaments.start_date)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_tournaments.end_date)<=']=$todatefilter;
        }
        if(!empty($type)){
            $condition['a.pk_id']=$type;
        }
        if(!empty($venue)){
            $this->db->where("tournaments.address LIKE '%$venue%'");           
        }

        $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
        $data['tounamentsDetails'] = $tounamentsDetails;

        $total_records=!empty($tounamentsDetails) ? count($tounamentsDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "tournaments";
            $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,u.mob,a.sportname,u.name as created_by,tournaments.entry_form,tournaments.status,tournaments.address,tournaments.email,website,time,draws_doc,entery_number";

            $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
            $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
            $condition = array(
                'tournaments.status !=' => '3',
                'u.status !=' => '3',
            );     
            if(!empty($fromdatefilter)){
                $condition['date(koodo_tournaments.start_date)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_tournaments.end_date)<=']=$todatefilter;
            }
            if(!empty($type)){
               $condition['a.pk_id']=$type;
            }
            if(!empty($venue)){
                $this->db->like('tournaments.address',$venue);               
            }

            $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
            $data['tounamentsDetails'] = $tounamentsDetails;

            $params["results"] = $tounamentsDetails;             
            $config['base_url'] = base_url() . 'admin/filterTournament';
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
        $data['tounamentsDetails']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/tournaments/vw_tournaments_list',$data);
    }

    // public function view_tournaments($id) {
    //     $table = "tournaments";
    //     $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,tournaments.mob,tournaments.mob,a.sportname,tournaments.entry_form,tournaments.status,tournaments.description,tournaments.address,tournaments.entery_fees,tournaments.entery_number,tournaments.price_money,tournaments.img as tornamentImage,u.img as userImage ,u.mob,u.name as created_by,u.email,u.img";

    //     $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
    //     $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
    //     // $this->db->join('city as b', 'tournaments.city=b.pk_id');
    //     $condition = array(
    //         'tournaments.status !=' => '3',
    //         'tournaments.pk_id'=>$id,
    //     );
    //     $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
    //     $data['tounamentsDetails'] = $tounamentsDetails[0];

    //     $this->load->view('admin/tournaments/vw_view_tournaments',$data);
    // }

    public function tournaments_export_to_excel(){
        $this->load->library('Excel');
        $type = !empty($this->input->get('type')) ? $this->input->get('type') : '';
        $venue = !empty($this->input->get('venue')) ? $this->input->get('venue') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';
       
        $data['type']=$type;
        $data['venue']=$venue;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $cityName="";

        $sportName="";
        $sportN=array( );
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
        $table = "tournaments";
        $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,u.mob,tournaments.address,a.sportname,u.name as created_by,tournaments.entry_form,tournaments.status,u.email,tournaments.description,tournaments.price_money,tournaments.entery_fees,tournaments.entery_number";

        $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
        $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
        // $this->db->join('city as b', 'tournaments.city=b.pk_id');
        $condition = array(
            'tournaments.status!=' => '3',
            'u.status !=' => '3',
        );     
        if(!empty($fromdatefilter)){
            $condition['date(koodo_tournaments.start_date)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_tournaments.end_date)<=']=$todatefilter;
        }         
        if(!empty($type)){
            $condition['a.pk_id']=$type;
        }
        if(!empty($venue)){
            $this->db->like('tournaments.address',$venue);               
        }

        $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
        $data['tounamentsDetails'] = $tounamentsDetails;
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="Tournaments List";
        $date_title ="all_time";
        $user_title ="all";  
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($tounamentsDetails)) {
            $finalsArray = $tounamentsDetails;

            $this->excel->getActiveSheet()->setTitle('Tounaments List');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
              

          $counter = 1; 
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sport Type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $sportName);
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Venue');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $venue);
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'From Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $fromdatefilter);
            $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'To Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter,  $todatefilter);
           
              
          $counter = 2;
            $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Start Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'End Date');
            $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Created By');
            $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Mobile No.');
            $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Email');
            $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Tournament Name');
            $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Sport Type');
            $this->excel->setActiveSheetIndex(0)->setCellValue('I'.$counter, 'Venue');
            $this->excel->setActiveSheetIndex(0)->setCellValue('J'.$counter, 'No. Entries');
            $this->excel->setActiveSheetIndex(0)->setCellValue('K'.$counter, 'Entry Fees');
            $this->excel->setActiveSheetIndex(0)->setCellValue('L'.$counter, 'Price Money');
            $this->excel->setActiveSheetIndex(0)->setCellValue('M'.$counter, 'Description');
                             
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

            $date = date('d-m-Y g:iA');
            $cnt = count($finalsArray);
          $counter = 3;
              if (!empty($finalsArray)) {
                  $j = 1;
                  foreach ($finalsArray as $arrayUser) {
                      $start_date = !empty($arrayUser['start_date']) ? date('d-m-Y',strtotime($arrayUser['start_date'])) :'';
                      $end_date = !empty($arrayUser['end_date']) ? date('d-m-Y',strtotime($arrayUser['end_date'])) :'';
                      $tornamentName = !empty($arrayUser['tornamentName']) ? ucwords($arrayUser['tornamentName']):'-';
                      $mob = !empty($arrayUser['mob']) ? ucwords($arrayUser['mob']):'';
                      $sportname = !empty($arrayUser['sportname']) ? $arrayUser['sportname']:'';
                      $city_name = !empty($arrayUser['address']) ? $arrayUser['address']:'-';
                      $email =!empty($arrayUser['email']) ?$arrayUser['email']: '-' ;
                      $description =!empty($arrayUser['description']) ?$arrayUser['description']: '-' ;
                      $price_money =!empty($arrayUser['price_money']) ?$arrayUser['price_money']:'-' ;
                      $entery_fees =!empty($arrayUser['entery_fees']) ?$arrayUser['entery_fees']: '-' ;
                      $entery_number =!empty($arrayUser['entery_number']) ?$arrayUser['entery_number']: '-' ;
                      $created_by =!empty($arrayUser['created_by']) ?$arrayUser['created_by']: '-' ;
                         

                      $this->excel->setActiveSheetIndex(0)
                          ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                          ->setCellValue('B' . $counter, (!empty($start_date) ? $start_date : "-"))
                          ->setCellValue('C' . $counter, (!empty($end_date) ? $end_date : "-"))
                          ->setCellValue('D' . $counter, (!empty($created_by) ? $created_by : "-"))
                          ->setCellValue('E' . $counter, (!empty($mob) ? $mob : "-"))
                          ->setCellValue('F' . $counter, (!empty($email) ? $email : "-"))
                          ->setCellValue('G' . $counter, (!empty($tornamentName) ? $tornamentName : ""))
                          ->setCellValue('H' . $counter, (!empty($sportname) ? $sportname : ""))
                          ->setCellValue('I' . $counter, (!empty($city_name) ? $city_name : ""))
                          ->setCellValue('J' . $counter, (!empty($entery_number) ? $entery_number : "-"))
                          ->setCellValue('K' . $counter, (!empty($entery_fees) ? $entery_fees : "-"))
                          ->setCellValue('L' . $counter, (!empty($price_money) ? $price_money : "-"))
                          ->setCellValue('M' . $counter, (!empty($description) ? $description : "-"));
        

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

      public function view(){
        $id = $this->input->get('id');  
        $table = "tournaments";
        $select = "tournaments.pk_id,tournaments.img,tournaments.start_date,tournaments.end_date,tournaments.name as tornamentName,tournaments.mob,tournaments.mob,a.sportname,tournaments.entry_form,tournaments.status,tournaments.description,tournaments.address,tournaments.entery_fees,tournaments.entery_number,tournaments.price_money,tournaments.img as tornamentImage,u.img as userImage ,u.mob,u.name as created_by,u.email,u.img";

        $this->db->join('user as u', 'tournaments.user_id=u.pk_id');
        $this->db->join('sport as a', 'tournaments.sport=a.pk_id');
        // $this->db->join('city as b', 'tournaments.city=b.pk_id');
        $condition = array(
            'tournaments.status !=' => '3',
            'tournaments.pk_id'=>$id,
        );
        $tounamentsDetails = $this->Md_database->getData($table, $select, $condition, 'tournaments.pk_id DESC', '');
        $ArrayView = !empty($tounamentsDetails[0])?$tounamentsDetails[0]:'';
    
        echo json_encode($ArrayView);
        exit();
    }
}
