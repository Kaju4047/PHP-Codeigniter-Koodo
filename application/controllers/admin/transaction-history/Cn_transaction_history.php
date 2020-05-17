<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_transaction_history extends CI_Controller {

    public function transaction_history() {

         $table = "user";
        $select = "name,pk_id,status";
        $condition = array(
            // 'status!= ' => '3'
        );
        $userDetails = $this->Md_database->getData($table, $select, $condition, 'name asc', '');
        $data['userDetails'] = $userDetails;


         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
              $table ="transaction_history";
        $select = " transaction_history.pk_id,transaction_history.fk_uid,transaction_history.sub_category,transaction_history.tran_id,transaction_history.tran_amount,transaction_history.tran_plan,transaction_history.createdDate,transaction_history.status,u.name,u.mob,u.pk_id as userid";
        $this->db->join('user as u','transaction_history.fk_uid = u.pk_id'); 
        $condition = array(
            'transaction_history.status !=' => '3',
        );
        $transactionDetails = $this->Md_database->getData($table, $select, $condition, 'transaction_history.pk_id DESC', '');


        $total_records=!empty($transactionDetails) ? count($transactionDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) 
        {
          $this->db->limit($limit_per_page,$page * $limit_per_page);

    	$table ="transaction_history";
        $select = " transaction_history.pk_id,transaction_history.fk_uid,transaction_history.sub_category,transaction_history.tran_id,transaction_history.tran_amount,transaction_history.tran_plan,transaction_history.createdDate,transaction_history.status,u.name,u.mob,u.pk_id as userid";
        $this->db->join('user as u','transaction_history.fk_uid = u.pk_id'); 
        $condition = array(
            'transaction_history.status !=' => '3',
        );
        $transactionDetails = $this->Md_database->getData($table, $select, $condition, 'transaction_history.pk_id DESC', '');
        $data['transactionDetails'] = $transactionDetails;
        // echo "<pre>";
        // print_r($data['transactionDetails']);
        // die();

         $params["results"] = $transactionDetails;             
            $config['base_url'] = base_url() . 'admin/transaction-history';
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
        $data['transactionDetails']= $params["results"] ;
        //End:: pagination::- 
        $data['totalcount']=$total_records;
        $this->load->view('admin/transaction-history/vw_transaction_history',$data);
    }
    public function filterTransaction(){
        $table = "user";
        $select = "name,pk_id,status";
        $condition = array(
            'status!= ' => '3'
        );
        $userDetails = $this->Md_database->getData($table, $select, $condition, 'name asc', '');
        $data['userDetails'] = $userDetails; 


        $plan = !empty($this->input->get('plan')) ? $this->input->get('plan') : '';
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
        $userName  = !empty($this->input->get('userName')) ? $this->input->get('userName') : '';

        $data['plan']=$plan;
        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
        $data['userName']=$userName;
 
        //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
             $table ="transaction_history";
        $select = " transaction_history.pk_id,transaction_history.fk_uid,transaction_history.sub_category,transaction_history.tran_id,transaction_history.tran_amount,transaction_history.tran_plan,transaction_history.createdDate,transaction_history.status,u.name,u.mob";
        $this->db->join('user as u','transaction_history.fk_uid = u.pk_id'); 
        $condition = array(
            'transaction_history.status !=' => '3',
        );
                
         if(!empty($fromdatefilter)){
           // echo $fromdatefilter;
           //  exit();
            $condition['date(koodo_transaction_history.createdDate)>=']=$fromdatefilter;
          }
          if(!empty($todatefilter)){
             $condition['date(koodo_transaction_history.createdDate)<=']=$todatefilter;
          }
          if(!empty($plan)){
            $condition['transaction_history.tran_plan']=$plan;
          }
           if(!empty($userName)){
            $condition['u.pk_id']=$userName;
           }

        $transactionDetails = $this->Md_database->getData($table, $select, $condition, 'transaction_history.pk_id DESC', '');
        $data['transactionDetails'] = $transactionDetails;


        $total_records=!empty($transactionDetails) ? count($transactionDetails) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0) 
        {
          $this->db->limit($limit_per_page,$page * $limit_per_page);

        $table ="transaction_history";
        $select = " transaction_history.pk_id,transaction_history.fk_uid,transaction_history.sub_category,transaction_history.tran_id,transaction_history.tran_amount,transaction_history.tran_plan,transaction_history.createdDate,transaction_history.status,u.name,u.mob";
        $this->db->join('user as u','transaction_history.fk_uid = u.pk_id'); 
        $condition = array(
            'transaction_history.status !=' => '3',
        );

         if(!empty($fromdatefilter)){
            $condition['date(koodo_transaction_history.createdDate)>=']=$fromdatefilter;
          }
          if(!empty($todatefilter)){
             $condition['date(koodo_transaction_history.createdDate)<=']=$todatefilter;
          }
          if(!empty($plan)){
            $condition['transaction_history.tran_plan']=$plan;
          }
           if(!empty($userName)){
            $condition['u.pk_id']=$userName;
           }

        $transactionDetails = $this->Md_database->getData($table, $select, $condition, 'transaction_history.pk_id DESC', '');
        $data['transactionDetails'] = $transactionDetails;

        $params["results"] = $transactionDetails;             
            $config['base_url'] = base_url() . 'admin/filter-transacation';
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
        $data['transactionDetails']= $params["results"] ;
       //End:: pagination::- 
       $data['totalcount']=$total_records; 

        $this->load->view('admin/transaction-history/vw_transaction_history',$data);

    }

     public function transaction_export_to_excel(){
        $this->load->library('Excel');
           $plan = !empty($this->input->get('plan')) ? $this->input->get('plan') : '';
           $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
           $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate') )): '';
           $userName  = !empty($this->input->get('userName')) ? $this->input->get('userName') : '';
          $data['plan']=$plan;
          $data['fromdatefilter']=$fromdatefilter;
          $data['todatefilter']=$todatefilter;
          $data['userName']=$userName;
             $tran_plan="";
            if (!empty($plan)) {
                if ($plan==1) {
                 $tran_plan ="Platinum";
                }elseif ($plan==2) {
                  $tran_plan ="Gold";
                }
            }
      $uname="";
      if (!empty($userName)) {        
          $table ="user";
          $select = "";
          $condition = array(
              'status !=' => '3',
              'pk_id' => $userName,
          );

        $name = $this->Md_database->getData($table, $select, $condition, '', '');
         $uname = $name[0]['name'];
      }
      $table ="transaction_history";
      $select = " transaction_history.pk_id,transaction_history.fk_uid,transaction_history.sub_category,transaction_history.tran_id,transaction_history.tran_amount,transaction_history.tran_plan,transaction_history.createdDate,transaction_history.status,u.name,u.mob";
      $this->db->join('user as u','transaction_history.fk_uid = u.pk_id'); 
      $condition = array(
          'transaction_history.status' => '1',
      );         
      if(!empty($fromdatefilter)){
          $condition['date(koodo_transaction_history.createdDate)>=']=$fromdatefilter;
      }
      if(!empty($todatefilter)){
           $condition['date(koodo_transaction_history.createdDate)<=']=$todatefilter;
      }
      if(!empty($plan)){
          $condition['transaction_history.tran_plan']=$plan;
      }
      if(!empty($userName)){
          $condition['u.pk_id']=$userName;
      }

      $transactionDetails = $this->Md_database->getData($table, $select, $condition, 'transaction_history.pk_id DESC', '');
      $data['transactionDetails'] = $transactionDetails;
      /*[:: Start Collection report excel sheet  Name::]*/
      $comm_title ="Transaction List";
      $date_title ="all_time";
      $user_title ="all";

      /*[:: End Collection report excel sheet  Name::]*/
      if (!empty($transactionDetails)) {
          $finalsArray = $transactionDetails;         
          $this->excel->getActiveSheet()->setTitle('Transaction List');
          $date = date('d-m-Y g:i A'); // get current date time
          $cnt = count($finalsArray);
              
          $counter = 1; 
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Plan');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $tran_plan);
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'From Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $fromdatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'To Date');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter,  $todatefilter);
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'User Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter,  $uname);
           
              
          $counter = 2;
              $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
              $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 'Date Time');
              $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Transaction Amount (Rs.)');
              $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Transaction Id');
              $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Subscription Plan');
              $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'User Name');
              $this->excel->setActiveSheetIndex(0)->setCellValue('G'.$counter, 'Subscription Plan category');
              $this->excel->setActiveSheetIndex(0)->setCellValue('H'.$counter, 'Mobile No.');
                                  
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
                        $createdDate = !empty($arrayUser['createdDate']) ? date('d-m-Y H:i a',strtotime($arrayUser['createdDate'])) :'';
                        $name = !empty($arrayUser['name']) ? ucwords($arrayUser['name']):'-';
                        $tran_id = !empty($arrayUser['tran_id']) ? ucwords($arrayUser['tran_id']):'';
                        $tran_amount = !empty($arrayUser['tran_amount']) ? $arrayUser['tran_amount']:'';
                        $sub_category = !empty($arrayUser['sub_category']) ? $arrayUser['sub_category']:'-';
                        $mob =!empty($arrayUser['mob']) ?$arrayUser['mob']: '-' ;
                        if ($arrayUser['tran_plan']==1) {
                            $tran_plan ="Platinum";
                        }elseif ($arrayUser['tran_plan']==2) {
                            $tran_plan ="Gold";
                        }                      
                        $this->excel->setActiveSheetIndex(0)
                              ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                              ->setCellValue('B' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                              ->setCellValue('C' . $counter, (!empty($tran_amount) ? $tran_amount : "-"))
                              ->setCellValue('D' . $counter, (!empty($tran_id) ? $tran_id : "-"))
                              ->setCellValue('E' . $counter, (!empty($tran_plan) ? $tran_plan : "-"))
                              ->setCellValue('F' . $counter, (!empty($name) ? $name : "-"))
                              ->setCellValue('G' . $counter, (!empty($sub_category) ? $sub_category : ""))
                              ->setCellValue('H' . $counter, ($mob));
                              

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
