<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_user_invitation extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    public function sendInvitation(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        // $mobile_no = !empty($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : '';
        // $invitations=[{"mobile_no":"" ,"name":""},{"mobile_no":"" ,"name":""}];
        $invitations = !empty($this->input->post('invitations')) ? $this->input->post('invitations') : "";
        $invitationList=json_decode($invitations);

        if (!empty($uid) ){
            $table = "user";
            $orderby = 'pk_id desc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "user";
            $orderby = 'pk_id desc';
            $condition = array('status' => '1', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $name = $this->Md_database->getData($table, $col, $condition, $orderby, '');

            foreach ($invitationList as $key => $value){
                $mobile_no = trim($value->mobile_no);
                $invitee_name = $value->name;
                if (!empty($mobile_no)){                
                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('mob' => $mobile_no,'status<>'=>3);
                    $col = array('pk_id','createdDate','mob');
                    $checkAlredyReg = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    if (empty($checkAlredyReg)){
                        $table = "invitation";
                        $orderby = 'pk_id desc';
                        $condition = array('mobile' => $mobile_no);
                        $col = array('pk_id','to_date','mobile');
                        $checkInvitation = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        //  print_r($checkInvitation);
                        // die();
                        //if first time send status is 1 active  else 2 inactive
                       if (count($checkInvitation) == 0 ){ 
                            $table="invitation";
                            $insert_data = array(
                                'mobile'=> $mobile_no,
                                'from_date'=> date('Y-m-d H:i:s'),
                                'to_date'=> date( "Y-m-d H:i:s", strtotime("+7 day")),
                                // 'to_date'=> date( "Y-m-d H:i:s", strtotime("+2 minutes")),
                                'fk_uid'=> $uid,
                                'invitee_name'=> $invitee_name,
                                'status'=> '1',
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                            );
                            $invitation = $this->Md_database->insertData($table,$insert_data);        
                            $message = 'Invitation in koodo from  '. $name[0]['name'] ;
                            $this->Md_database->sendSMS($message, $mobile_no);
                        
                        }else{
                            $lastdate=  $checkInvitation[0]['to_date'];
                            $table="invitation";
                            $insert_data = array(
                                'mobile'=> $mobile_no,
                                'fk_uid'=> $uid,
                                'status'=> '2',
                                'invitee_name'=> $invitee_name,
                                'createdBy' => $uid,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'created_ip_address' => $_SERVER['REMOTE_ADDR']             
                            );
                            if (date($lastdate) > date('Y-m-d H:i:s')) {
                                $insert_data['from_date'] = $lastdate;
                                $newDate = date("Y-m-d H:i:s",strtotime($lastdate."+7 day"));
                                // $newDate = date("Y-m-d H:i:s",strtotime($lastdate."+2 minutes"));
                                $insert_data['to_date']=$newDate;
                                 $invitation = $this->Md_database->insertData($table,$insert_data);
                            }else{
                                $insert_data['from_date'] = date('Y-m-d H:i:s');
                                $insert_data['to_date']=date( "Y-m-d H:i:s", strtotime("+7 day"));
                                //$insert_data['to_date']=date( "Y-m-d H:i:s", strtotime("+2 minutes"));
                                $message = 'Invitation in koodo from  '. $name[0]['name'];
                                $this->Md_database->sendSMS($message, $mobile_no);
                                $invitation = $this->Md_database->insertData($table,$insert_data);
                            }                              
                        }                        
                    }else{
                        $resultarray = array('error_code' => '4','message'=>$checkAlredyReg[0]['mob'].'is already registered and invitation sended successfully to other numbers.');
                        echo json_encode($resultarray);
                        // exit(); 
                    } 
                }else{
                    $resultarray = array('error_code' => '3','message'=>'mobile no is empty');
                    echo json_encode($resultarray);
                    exit(); 
                }
            }  
            $resultarray = array('error_code' => '1','message'=>'Invitation send successfully');
            echo json_encode($resultarray);
            exit();   
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }

    public function updateInvitationStatus(){
        $table = "invitation";
        $orderby = 'pk_id desc';
        $condition = array();
        $col = array('pk_id','to_date','status','mobile');
        $checkInvitation = $this->Md_database->getData($table, $col, $condition, $orderby, '');
        
        foreach ($checkInvitation as $key => $value){
            $to_date = $value['to_date'];
            $mobile = $value['mobile'];
            $pk_id = $value['pk_id'];
            
            $table = "invitation";
            $orderby = 'pk_id desc';
            $condition = array('mobile'=>$mobile);
            $col = array('pk_id','to_date','status','mobile','fk_uid');
            $count = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $id = $count[0]['pk_id'];

            if ($to_date <= date("Y-m-d H:i:s") && $pk_id != $id){          
                    $table = "invitation";
                    $updated_data = array(                                
                        'status'=>'3', 
                        // 'updatedBy' => $createdBy,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                    );
                    $condition = array(); 
                    $this->db->where('to_date<=', date("Y-m-d H:i:s")) ;   
                    $this->db->where('pk_id !=', $id) ;  
                    $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);
             
            }else{
                foreach ($count as $k => $v){
                    $s= $v['status'];
                    $d= $v['to_date']; 
                    $p= $v['pk_id']; 
                    $fk_uid= $v['fk_uid']; 

                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array( 'pk_id'=>$fk_uid);
                    $col = array('pk_id','name');
                    $userName = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                    if ($s == 2 && $d >=date("Y-m-d H:i:s")){
                        if (!empty($p)){
                            $table = "invitation";
                            $orderby = 'pk_id desc';
                            $condition = array('mobile'=>$mobile, 'status'=>1, 'to_date' >=date("Y-m-d H:i:s"));
                            $col = array('pk_id','to_date','status','mobile');
                            $checkactive = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                            $table = "invitation";
                            $orderby = 'pk_id asc';
                            $condition = array('mobile'=>$mobile, 'status'=>2, 'to_date' >=date("Y-m-d H:i:s"));
                            $col = array('pk_id','to_date','status','mobile');
                            $checkainvitationasc = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                            $table = "invitation";
                            $updated_data = array(                                
                                'status'=>'1', 
                                'updatedDate' => date('Y-m-d H:i:s'),
                                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array(); 
                            if (empty($checkactive)) {
                                $this->db->where('to_date>=', date("Y-m-d H:i:s")) ;   
                                $this->db->where('status ','2') ;  
                                $this->db->where('pk_id ',$checkainvitationasc[0]['pk_id']) ;  
                            }else{
                                $this->db->where('to_date>=', date("Y-m-d H:i:s")) ;   
                                $this->db->where('status ','2') ;  
                                $this->db->where('pk_id ',$p) ; 
                                $this->db->where('mobile!= ',$mobile) ; 
                            }
                            $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);
                            if (empty($checkactive) && $resultarray== 1 ){
                                $message = 'You have invitation in koodo from  '.$userName[0]['name'];
                                $this->Md_database->sendSMS($message, $mobile);                                
                            }
                        }                    
                    }elseif ( $pk_id == $id && $s == 2){
                        if (!empty($p)) {
                            $table = "invitation";
                            $updated_data = array(                                
                                'status'=>'1', 
                                'updatedDate' => date('Y-m-d H:i:s'),
                                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array();   
                            $this->db->where('pk_id ',$id);  
                            $this->db->where('status ','2') ;  
                            $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);
                            if ($resultarray == 1) {
                                $message = 'You have invitation in koodo from  '.$userName[0]['name'];
                                $this->Md_database->sendSMS($message, $mobile);
                            }
                        }                    
                    }
                }        
            }                    
        }         
        // check registrartion status and update
        $table = "invitation";
        $orderby = 'pk_id desc';
        $condition = array('status'=>1);
        $col = array('pk_id','to_date','status','mobile');
        $checkActiveInvitation = $this->Md_database->getData($table, $col, $condition, $orderby, '');
        foreach ($checkActiveInvitation as $key => $value) {
            $mobileNo=$value['mobile'];            
            $table = "invitation"; 
            $orderby = 'pk_id desc';
            $condition = array('status'=>'1');
            $this->db->where('mobile ',$mobileNo) ; 
            $col = array('pk_id','to_date','status','mobile','createdDate');
            $checkA = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $invitation_date=$checkA[0]['createdDate'];
            $invitation_mob=$checkA[0]['mobile'];

            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('mob' => $mobileNo,'status<>'=>3);
             $this->db->where('createdDate>= ',date($invitation_date)) ; 
            $col = array('pk_id','createdDate','mob');
            $register = $this->Md_database->getData($table, $col, $condition, $orderby,'');

            if (!empty($checkA) &&  !empty($register) && $invitation_mob == $register[0]['mob']) {
                $table = "invitation";
                $updated_data = array(                                
                    'reg_status'=>'registered', 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                );
                $condition = array();   
                $this->db->where('mobile ',$register[0]['mob']) ;  
                $this->db->where('status ','1') ;  
                $resultarray = $this->Md_database->updateData($table, $updated_data,$condition);

            }
        }
        $resultarray = array('error_code' => '1','message'=>'Invitation send successfully');
        echo json_encode($resultarray);
        exit();   
    } 

    public function invitationList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';

        if (!empty($uid) ){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            $table = "invitation";
            $orderby = 'pk_id asc';
            $condition = array('fk_uid'=>$uid);
            $col = array('pk_id','createdDate', 'COALESCE(invitee_name," ") as invitee_name','mobile','reg_status');
            $List = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $invitationCount=count($List);

        
            $table = "invitation";
            $select="pk_id";
            $condition = array(
                'reg_status' => 'registered',
                'fk_uid' => $uid,
            );
            $registered= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $registerCount=count($registered);

            $table = "buy_subscription";
            $select="pk_id";
            $condition = array(
                'cost' => '0',
                'user_id' => $uid,
                'status' => '1',
            );
            $redeemPlan= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $redeemPlanCount=count($redeemPlan);
            // print_r($redeemPlanCount);
            // die();
    
            $resultarray = array('error_code' => '1','message'=>'Invitation List','invitation_list'=>$List,'invitation_count'=>!empty($invitationCount)?$invitationCount:'0','registered_count'=>!empty($registerCount)?$registerCount-' 2'*$redeemPlanCount:'0');
            // $resultarray = array('error_code' => '1','message'=>'Invitation List','invitation_list'=>$List,'invitation_count'=>!empty($invitationCount)?$invitationCount:'0','registered_count'=>!empty($registerCount)?$registerCount-' 50'*$redeemPlanCount:'0');
        
            echo json_encode($resultarray);
            exit();   
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 
    }
    // public function historyInvitattion(){
    //     $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';

    //     if (!empty($uid) ){
    //         $table = "user";
    //         $orderby = 'pk_id asc';
    //         $condition = array('status' => '2', 'pk_id' => $uid);
    //         $col = array('pk_id','name');
    //         $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
    //         if (!empty($checkUser)){
    //             $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
    //             echo json_encode($resultarray);
    //             exit();
    //         }
    //         $table = "invitation";
    //         $select="pk_id";
    //         $condition = array(
    //             'fk_uid' => $uid,
    //         );
    //         $invitation = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
    //         $invitationCount=count($invitation);

    //         $table = "invitation";
    //         $select="pk_id";
    //         $condition = array(
    //             'reg_status' => 'registered',
    //             'fk_uid' => $uid,
    //         );
    //         $registered= $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
    //         $registerCount=count($registered);
        

    //         $resultarray = array('error_code' => '1','invitationCount'=>!empty($invitationCount)?$invitationCount:'0','registerCount'=>!empty($registerCount)?$registerCount:'0');
    //         echo json_encode($resultarray);
    //         exit();   
    //     }else {
    //         $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
    //         echo json_encode($resultarray);
    //         exit();                       
    //     } 

    // }

    public function saveFreeSubscriptionPlan(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $listtype = !empty($this->input->post('listtype')) ? $this->input->post('listtype') : '';
        $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : '';
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : '';
        $benefits = !empty($this->input->post('benefits')) ? $this->input->post('benefits') : ""; 
        $subscription_id = !empty($this->input->post('subscription_id')) ? $this->input->post('subscription_id') : ""; 

        if (!empty($uid) ){
            $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
            }
            if (!empty($listtype) && !empty($sport_id) && !empty($city_id) && !empty($benefits) && !empty($subscription_id)) {
                $table = "buy_subscription";
                $orderby = 'pk_id desc';
                $condition = array('status' => '1');
                $this->db->where('category','Gold');
                $this->db->where('user_id',$uid);
                $this->db->where('listtype',$listtype);
                $this->db->where('fk_sport',$sport_id);
                $this->db->where('fk_city',$city_id);
                $col = array('pk_id','cost','expDate');
                $checkActivePlan = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                $table = "buy_subscription";
                $time = date('Y-m-d H:i:s');
                $insert_data = array(
                    'plan' => ' Listing Plan',
                    'plan_id' => '1',
                    'category' => 'Gold',
                    'listtype' => $listtype,
                    'user_id' => $uid,
                    'sub_id' => $subscription_id,
                    'fk_sport' => $sport_id,
                    'fk_city' => $city_id,
                    'months' => 1 ,
                    'description' => $benefits,
                    'cost' => '00',
                    'status' => '1',
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']
                );
                if (!empty($checkActivePlan[0]['expDate']) && $checkActivePlan[0]['expDate'] >date('Y-m-d H:i:s')){
                    //update from expdate
                    $insert_data['start_date']=$checkActivePlan[0]['expDate'];
                    $insert_data['expDate']=date('Y-m-d H:i:s', strtotime("+"."1 month", strtotime($checkActivePlan[0]['expDate'])));                   
                }else{
                    //update from today
                    $insert_data['start_date']=date('Y-m-d H:i:s');
                    $insert_data['expDate']=date('Y-m-d H:i:s', strtotime("+"."1 month", strtotime(date('Y-m-d H:i:s'))));       
                }
                     
                $result = $this->Md_database->insertData($table, $insert_data);
                $insert_id = $this->db->insert_id();
                if(!empty($insert_id)){               
                    $resultarray = array('error_code' => '1','message'=>'Plan added successfully');
                    echo json_encode($resultarray);
                    exit();   
                }else{
                    $resultarray = array('error_code' => '1','message'=>'Somthing is Wrong');
                    echo json_encode($resultarray);
                    exit(); 
                }               
            }else{
                $resultarray = array('error_code' => '3','message'=>'listtype or sport_id or city_id or benefits or subscription_id is empty');
                echo json_encode($resultarray);
                exit(); 
            }
            
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
            echo json_encode($resultarray);
            exit();                       
        } 

    }

}