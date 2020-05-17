<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_sports_book extends CI_Controller {

    public function sports_book() {


        $postList=array();
        $table = "sportbook";
        $orderby = 'sportbook.pk_id DESC';
        $condition = array('sportbook.status' => '1');        
     
        $col = array('sportbook.pk_id','fk_uid','text','image','name','img','city_name','state_name','DATE_FORMAT(koodo_sportbook.createdDate, "%b %d %Y %h:%i %p") AS date');
        $this->db->join('user','user.pk_id = sportbook.fk_uid');
        $this->db->join('city','user.city = city.pk_id');
        $postList = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
        foreach ($postList as $key => $value) {
            $post_id=!empty($value)?$value['pk_id']:'';
            if (!empty($post_id)){
                $post_date=$value['date'];

                $date = date("d.m.Y");
                $match_date = date('d.m.Y', strtotime($post_date));
                $time = date('H:i A', strtotime($post_date));

                if($date == $match_date) { 
                    $value['date']='Today at '.$time ;
                }elseif(date('d.m.Y',strtotime("-1 days"))==$match_date) {
                    $value['date']='Yesterday at '.$time;
                }else{
                    $value['date']=$post_date;
                }

                //Get Like count
                $table = "sportbook_post_like";
                $orderby = 'pk_id asc';
                $condition = array('like_status' => '1', 'fk_post' => $post_id);
                $col = array('pk_id');
                $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $likeCount = !empty(count($like))?count($like):'0';
                   
                    if ($likeCount >= 1000) {
                      $likeCount=number_format(($likeCount / 1000), 1) . 'k';
                    }
                    $value['likeCount'] =$likeCount; 

                    //Get Comment count
                    $table = "sportbook_post_comment";
                    $orderby = 'pk_id asc';
                    $condition = array('fk_post' => $post_id);
                    $col = array('pk_id');
                    $commentC= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $commentCount = !empty(count($commentC))?count($commentC):'0';
                   
                    if ($commentCount >= 1000) {
                      $commentCount=number_format(($commentCount / 1000), 1) . 'k';
                    }
                    $value['commentCount'] =$commentCount;             
                $commentList[] = $value;
                }
            } 
            $data['commentList']=  $commentList;
           
            //view comment list with post details
            $data['viewName']= $postList[0]['name'];
            $data['viewCity']= $postList[0]['city_name'];
            $data['viewState']= $postList[0]['state_name'];
            $data['viewPostImg']= $postList[0]['image'];
            $data['viewUserProfile']= $postList[0]['img'];
            $data['viewPostText']= $postList[0]['text'];
            $data['viewPostDate']= $commentList[0]['date'];
            $view_post_id= $postList[0]['pk_id'];
           
            $table = "sportbook_post_like";
            $orderby = 'pk_id asc';
            $condition = array('like_status' => '1', 'fk_post' => $view_post_id);
            $col = array('pk_id');
            $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
            // print_r($like);
            $likeCount = !empty(count($like))?count($like):'0';
               
            if ($likeCount >= 1000) {
                $likeCount=number_format(($likeCount / 1000), 1) . 'k';
            }
            $data['viewlikeCount'] =$likeCount;
                $table = "sportbook_post_comment";
                $orderby = 'sportbook_post_comment.pk_id desc';
                $condition = array('fk_post' => $view_post_id);
                $this->db->join('user','user.pk_id = sportbook_post_comment.fk_uid');
                $col = array('sportbook_post_comment.pk_id','comment','name','img','DATE_FORMAT(koodo_sportbook_post_comment.createdDate, "%b %d %Y %h:%i %p") AS comment_date');
                $comment1= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                foreach ($comment1 as $key => $value) {
                    $comment_date=!empty($value['comment_date'])?$value['comment_date']:'';
                    $c=array();
                    if (!empty($comment_date)) {                    
                        $last = new DateTime($comment_date);
                        $now = new DateTime( date( 'Y-m-d H:i:s', time() )) ; 

                        // Find difference
                        $interval = $last->diff($now);

                        // Store in variable to be used for calculation etc
                        $years = (int)$interval->format('%Y');
                        $months = (int)$interval->format('%m');
                        $days = (int)$interval->format('%d');
                        $hours = (int)$interval->format('%H');
                        $minutes = (int)$interval->format('%i');

                        if($years > 0){
                            $value['comment_date']=  $years.' Y ago' ;
                        }else if($months > 0){
                            $value['comment_date']=  $months.' M ago';
                        }else if($days > 0){
                            $value['comment_date']=  $days.' D ago ';
                        }else if($hours > 0){
                            $value['comment_date']=   $hours.' H ago ';
                        }else{
                            $value['comment_date']= $minutes.' min ago.' ;
                        }
                    }
                    $comment[]=$value;
                }
                $data['viewCommentsList']=!empty($comment)?$comment:'';
               
                $data['comments']=!empty($comment['comment'])? $comment['comment']:'';
                
                $commentCount =!empty($comment)?count($comment):'0';
                   
                if ($commentCount >= 1000) {
                    $commentCount=number_format(($commentCount / 1000), 1) . 'k';
                }
                $data['viewcommentCount'] =$commentCount;               

        $this->load->view('admin/sports_book/vw_sports_book',$data);
    }

    public function viewComment(){
    	$postID = !empty($this->input->post('post_id')) ? $this->input->post('post_id') : '';
    	$postList=array();
        $table = "sportbook";
        $orderby = 'sportbook.pk_id DESC';
        $condition = array('sportbook.status' => '1','sportbook.pk_id'=>$postID);
        $col = array('sportbook.pk_id','fk_uid','text','image','name','img','city_name','state_name','DATE_FORMAT(koodo_sportbook.createdDate, "%b %d %Y %h:%i %p") AS date');
        $this->db->join('user','user.pk_id = sportbook.fk_uid');
        $this->db->join('city','user.city = city.pk_id');
        $postList = $this->Md_database->getData($table, $col, $condition, $orderby, '');


         foreach ($postList as $key => $value) {
            $post_date=$value['date'];

            $date = date("d.m.Y");
            $match_date = date('d.m.Y', strtotime($post_date));
            $time = date('H:i A', strtotime($post_date));

            if($date == $match_date) { 
                $value['date']='Today at '.$time ;
            }elseif(date('d.m.Y',strtotime("-1 days"))==$match_date) {
                $value['date']='Yesterday at '.$time;
            }else{
                $value['date']=$post_date;
            }
            $post_id=!empty($value)?$value['pk_id']:'';

            $viewCommentList=array();
            if (!empty($post_id)){
                //Get Like count
                $table = "sportbook_post_like";
                $orderby = 'pk_id asc';
                $condition = array('like_status' => '1', 'fk_post' => $postID);
                $col = array('pk_id');
                $like= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $likeCount = !empty(count($like))?count($like):'0';
               
                if ($likeCount >= 1000) {
                  $likeCount=number_format(($likeCount / 1000), 1) . 'k';
                }
                $value['likeCount'] =$likeCount; 

                //Get Comment count
                $table = "sportbook_post_comment";
                $orderby = 'sportbook_post_comment.pk_id DESC';
                $condition = array('fk_post' => $post_id);
                  $this->db->join('user','user.pk_id = sportbook_post_comment.fk_uid');
                $col = array('sportbook_post_comment.pk_id','comment',"img",'name','DATE_FORMAT(koodo_sportbook_post_comment.createdDate, "%b %d %Y %h:%i %p") AS c_date');
                $commt= $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $comment =array();
                 foreach ($commt as $key => $val){
                    $comment_date=!empty($val['c_date'])?$val['c_date']:'';
                    if (!empty($comment_date)) {                    
                        $last = new DateTime($comment_date);
                        $now = new DateTime( date( 'Y-m-d H:i:s', time() )) ; 

                        // Find difference
                        $interval = $last->diff($now);
                        // Store in variable to be used for calculation etc
                        $years = (int)$interval->format('%Y');
                        $months = (int)$interval->format('%m');
                        $days = (int)$interval->format('%d');
                        $hours = (int)$interval->format('%H');
                        $minutes = (int)$interval->format('%i');

                        if($years > 0){
                            $val['comment_date']=  $years.' Y ago' ;
                        }else if($months > 0){
                            $val['comment_date']=  $months.' M ago';
                        }else if($days > 0){
                            $val['comment_date']=  $days.' D ago ';
                        }else if($hours > 0){
                            $val['comment_date']=   $hours.' H ago ';
                        }else{
                            $val['comment_date']= $minutes.' min ago.' ;
                        }
                    }
                    $comment[]=$val;            
                }              
                $value['comments'] =$comment; 

                $commentCount = !empty(count($commt))?count($commt):'0';
               
                if ($commentCount >= 1000) {
                  $commentCount=number_format(($commentCount / 1000), 1) . 'k';
                }
                $value['commentCount'] =$commentCount;             
            $viewCommentList = $value;
            }
        }

        echo json_encode($viewCommentList);
        exit(); 

    }
    public function reportUserList(){
        $fromdatefilter = !empty($this->input->get('fromdate')) ? date("Y-m-d" ,strtotime($this->input->get('fromdate')) ): '';
        $todatefilter = !empty($this->input->get('todate')) ? date("Y-m-d" ,strtotime($this->input->get('todate')) ): '';

        $data['fromdatefilter']=$fromdatefilter;
        $data['todatefilter']=$todatefilter;
           
         //start:: pagination::- 
        $params = array();
        $params['links'] = array();
        $params['results'] = array();
        $limit_per_page =10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) -1) : 0;
        $total_records = "";
        $table = "sportbook_report";
        $select="a.name as forname, b.name as givenby, sportbook.text,sportbook.image,sportbook.createdDate,fk_post_id,sportbook_report.pk_id";
            
        $this->db->join('user as a', 'a.pk_id=sportbook_report.fk_uid_for'); 
        $this->db->join('user as b', 'b.pk_id=sportbook_report.fk_uid_given_by'); 
        $this->db->join('sportbook', 'sportbook.pk_id=sportbook_report.fk_post_id'); 
        $condition = array(
            'sportbook.status !=' => '3',
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_sportbook.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_sportbook.createdDate)<=']=$todatefilter;
        }
        $postreport = $this->Md_database->getData($table, $select, $condition, 'sportbook_report.pk_id DESC', '');

        $total_records=!empty($postreport) ? count($postreport) : '0';
        $data['totalcount']=!empty($total_records) ? $total_records : '0';
        if ($total_records > 0){
            $this->db->limit($limit_per_page,$page * $limit_per_page);
            $table = "sportbook_report";
            $select="a.name as forname, b.name as givenby, sportbook.text,sportbook.image,sportbook.createdDate,sportbook.status,fk_post_id,sportbook_report.pk_id";                
            $this->db->join('user as a', 'a.pk_id=sportbook_report.fk_uid_for'); 
            $this->db->join('user as b', 'b.pk_id=sportbook_report.fk_uid_given_by'); 
            $this->db->join('sportbook', 'sportbook.pk_id=sportbook_report.fk_post_id'); 
            $condition = array(
                'sportbook.status !=' => '3',
            );
            if(!empty($fromdatefilter)){
                $condition['date(koodo_sportbook.createdDate)>=']=$fromdatefilter;
            }
            if(!empty($todatefilter)){
                $condition['date(koodo_sportbook.createdDate)<=']=$todatefilter;
            }
            $postreport = $this->Md_database->getData($table, $select, $condition, 'sportbook_report.pk_id DESC', '');
            $params["results"] = $postreport;             
            $config['base_url'] = base_url() . 'android/report-post';
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
        $data['postreport']= $params["results"] ;
       //End:: pagination::- 
        $data['totalcount']=$total_records;
           
        $this->load->view('admin/sports_book/vw_sports_book_report_users',$data);
    }

    public function reportPostStatus($id, $status) {
        $table = "sportbook";
        $sport_data = array(
            'status' => $status,
            'createdBy' => $this->session->userdata['UID'],
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
        redirect(base_url() . 'android/report-post');
    }

    public function deleteReportPost($pk_id){
        $condition = array('pk_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret = $this->Md_database->updateData('sportbook', $update_data, $condition);

        $condition = array('fk_post_id' => $pk_id);
        $update_data['status'] = '3';
       
        $ret1 = $this->Md_database->updateData('sportbook_report', $update_data, $condition);
        if (!empty($ret) && !empty($ret1)) {
            $this->session->set_flashdata('success', "Post details has been deleted successfully.");
            redirect($_SERVER['HTTP_REFERER']);    
       }
    }

    public function reportView(){
        $id = $this->input->get('id'); 
        $table = "sportbook_report";
        $select = "description";
        $condition = array(
            'pk_id'=>$id 
        );
        $ArrayView = $this->Md_database->getData($table, $select, $condition, '', '');
        $ArrayView = !empty($ArrayView[0])?$ArrayView[0]:'';
    
        echo json_encode($ArrayView);
        exit();

    }
    
    public function sportbookExportToExcel(){
        $this->load->library('Excel');
        $fromDate = !empty($this->input->get('fromdate')) ? $this->input->get('fromdate') : '';
        $toDate = !empty($this->input->get('todate')) ? $this->input->get('todate') : '';
        $data['fromdate']=$fromDate;
        $data['todate']=$toDate;
        
        /*[:: Start Collection report excel sheet  Name::]*/
        $comm_title ="Subscription List";
        $date_title ="all_time";
        $user_title ="all";
        
        $table = "sportbook_report";
        $select="a.name as forname, b.name as givenby, sportbook.text,sportbook.image,sportbook.createdDate,sportbook.status,fk_post_id,sportbook_report.pk_id,description";                
        $this->db->join('user as a', 'a.pk_id=sportbook_report.fk_uid_for'); 
        $this->db->join('user as b', 'b.pk_id=sportbook_report.fk_uid_given_by'); 
        $this->db->join('sportbook', 'sportbook.pk_id=sportbook_report.fk_post_id'); 
        $condition = array(
            'sportbook.status !=' => '3',
        );
        if(!empty($fromdatefilter)){
            $condition['date(koodo_sportbook.createdDate)>=']=$fromdatefilter;
        }
        if(!empty($todatefilter)){
            $condition['date(koodo_sportbook.createdDate)<=']=$todatefilter;
        }
        $postreport = $this->Md_database->getData($table, $select, $condition, 'sportbook_report.pk_id DESC', '');         
        /*[:: End Collection report excel sheet  Name::]*/

        if (!empty($postreport)) {
            $finalsArray = $postreport;
             
            $this->excel->getActiveSheet()->setTitle('Sportbook Post Report List');
            $date = date('d-m-Y g:iA'); // get current date time
            $cnt = count($finalsArray);
              
            $counter = 1; 
                $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'From Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter,  $fromDate);
                $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'To Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter,  $toDate);
           
              
            $counter = 2;
                $this->excel->setActiveSheetIndex(0)->setCellValue('A'.$counter, 'Sr.No.');
                $this->excel->setActiveSheetIndex(0)->setCellValue('B'.$counter, '  Reporter Name');
                $this->excel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'Post User Name');
                $this->excel->setActiveSheetIndex(0)->setCellValue('D'.$counter, 'Post');
                $this->excel->setActiveSheetIndex(0)->setCellValue('E'.$counter, 'Report Date');
                $this->excel->setActiveSheetIndex(0)->setCellValue('F'.$counter, 'Description');
              
                // set auto size for columns
                $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                          
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
                    $givenby = !empty($arrayUser['givenby']) ? ucwords($arrayUser['givenby']) : '-';
                    $forname = !empty($arrayUser['forname']) ? ucwords($arrayUser['forname']) : '-';
                    $imgdata = !empty($arrayUser['image']) ? 'uploads/sportbook/post/'.$arrayUser['image'] : '';                    
                    $text = !empty($arrayUser['text']) ? ucfirst($arrayUser['text']) : '-';
                    $createdDate= !empty($arrayUser['createdDate']) ? date('d-m-Y',strtotime($arrayUser['createdDate'])) : '-';
                    $description= !empty($arrayUser['description']) ? $arrayUser['description']: '-';
                  
                    $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $counter, (!empty($j) ? $j : ''))
                        ->setCellValue('B' . $counter, (!empty($givenby) ? $givenby : "-"))
                        ->setCellValue('C' . $counter, (!empty($forname) ? $forname : "-"))
                        ->setCellValue('D' . $counter, (!empty($imgdata) ? 'Image' : "" . !empty($text) ? $text : ""))
                        ->setCellValue('E' . $counter, (!empty($createdDate) ? $createdDate : "-"))
                        ->setCellValue('F' . $counter, (!empty($description) ? $description : "0"));

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
