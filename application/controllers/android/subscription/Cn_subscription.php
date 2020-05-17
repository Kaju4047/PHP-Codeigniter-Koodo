<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
class Cn_subscription extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }

    public function firstSubscriptionList(){
        /***Buy Subscription Plan List***/
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        if (!empty($uid)) {
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
            $table = "sport";
            $orderby = 'pk_id desc';
            $condition = array('type<>' =>'1','pk_id!='=>'1','status'=>'1');
            $this->db->where('pk_id!=','40');
            $this->db->where('pk_id!=','48');
            $this->db->where('pk_id!=','50');
            $this->db->where('pk_id!=','51');
            $this->db->where('pk_id!=','54');
            $this->db->where('pk_id!=','53');
            $col = array('pk_id,sportname','sportimg');
            $getOtherIcon = $this->Md_database->getData($table, $col, $condition,'', '');

            $table = "subscription";
            $select = array('plan_name,category,duration,desc,mrp,offer');
            $condition = array('subscription.status' => '1');
            $this->db->where('view_on_android','Yes');
            $finalsResult = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            if (!empty($finalsResult)){
               $resultarray = array('error_code' => '1', 'message' => 'record fetch.', 'first_subscription_list' => $finalsResult,'getIcon'=>$getOtherIcon, 'path' => base_url().'uploads/master/sportimage/');
               echo json_encode($resultarray);
            }else{
               $resultarray = array('error_code' => '1', 'message' => 'record empty.');
               echo json_encode($resultarray);
            }         
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'user id is  empty.');
            echo json_encode($resultarray);
        }
    }

    function mySubscriptionPlansList(){
        /***Buy Subscription Plan List***/
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : ""; 
        if (!empty($uid)) {
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
            $table = "buy_subscription";
            $select = array('buy_subscription.pk_id','plan','category','listtype','sub_id','sportname','city_name','refered_by','cost','description','DATE_FORMAT(koodo_buy_subscription.expDate, "%b, %d %Y") AS expDate',' DATE_FORMAT(koodo_buy_subscription.start_date, "%b, %d %Y") AS start_date',' DATE_FORMAT(koodo_buy_subscription.createdDate, "%d/%m/%Y  @ %h:%i %p ") AS createdDate','months','transaction_id');
            $this->db->join('city', 'city.pk_id = buy_subscription.fk_city','LEFT');
            $this->db->join('sport', 'sport.pk_id = buy_subscription.fk_sport','LEFT');
            $condition = array('buy_subscription.status' => '1');
            if (!empty($user_id)){
                $condition['user_id']=$user_id;
            }
            $this->db->limit($limit,$offset);
            $finalsResult = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            // if (!empty($finalsResult)){
               $resultarray = array('error_code' => '1', 'message' => 'record fetch.', 'buy_subscription_list' =>!empty($finalsResult) ?$finalsResult:[]);
               echo json_encode($resultarray);
            // }else{
            //    $resultarray = array('error_code' => '2', 'message' => 'record empty.');
            //    echo json_encode($resultarray);
            // }         
        }else{
            $resultarray = array('error_code' => '3', 'message' => 'user id is  empty.');
            echo json_encode($resultarray);
        }
    }
    
    public function checkAmount(){
        /*** After add Plan Check Plan amount and Plan is Exist or Not***/
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $listtype = !empty($this->input->post('listtype')) ? $this->input->post('listtype') : '';
        $category = !empty($this->input->post('category')) ? $this->input->post('category') : '';
        $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : "";
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : ""; 
        $months = !empty($this->input->post('months')) ? $this->input->post('months') : ""; 
        if (!empty($uid)) {
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
            if (!empty($category) && !empty($months)){
                $table = "subscription";
                $orderby = 'pk_id desc';
                $condition = array('duration'=>$months,'category'=>$category,'listtype'=>$listtype);
                $this->db->where('status','1');
                if (!empty($city_id)) {
                    $condition['city']=$city_id;
                }
                if (!empty($sport_id)) {
                    $condition['sport']=$sport_id;
                }
                if (!empty($listtype)) {
                    $condition['listtype']=$listtype;
                }
                $col = array('pk_id','offer','mrp','desc');
                $planDetails = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                //check tax amount
                $table = "tax";
                $orderby = 'pk_id DESC';
                $condition = array('status' => '1');
                $col = array('pk_id','tax');
                $tax = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                $amtTax=$tax[0]['tax'];
                
                //add tax in amount
                if (!empty($planDetails[0]['mrp'])) {
                    $mrpTax = $planDetails[0]['mrp'] * ($amtTax / 100);
                    $totalMrp = $planDetails[0]['mrp'] + $mrpTax;
                }
                if(!empty($planDetails[0]['offer'])){
                    $offerTax = $planDetails[0]['offer'] * ($amtTax / 100);
                    $totalOffer = $planDetails[0]['offer'] + $offerTax;
                }

                $resultarray = array('error_code' => '1', 'message' => 'Plan Details','benefits'=>!empty($planDetails[0]['desc'])?$planDetails[0]['desc']:'','mrp'=>!empty($planDetails[0]['mrp'])?$planDetails[0]['mrp']:'','offer'=>!empty($planDetails[0]['offer'])?$planDetails[0]['offer']:'','mrp_tax'=>!empty($totalMrp)?$totalMrp:'','offer_tax'=>!empty($totalOffer)?$totalOffer:'','subscription_id'=>!empty($planDetails[0]['pk_id'])?$planDetails[0]['pk_id']:'');
                echo json_encode($resultarray);
                exit();
            }else{
                $resultarray = array('error_code' => '3', 'message' => 'category or months  is  empty.');
                echo json_encode($resultarray);
                exit();
            }                  
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is  empty.');
            echo json_encode($resultarray);
            exit();
        }
    }

    public function addPlan(){
        $pk_id = !empty($this->input->post('pk_id')) ? $this->input->post('pk_id') : '';
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $listtype = !empty($this->input->post('listtype')) ? $this->input->post('listtype') : ''; // Pro-players etc... all list
        $category = !empty($this->input->post('category')) ? $this->input->post('category') : ''; // Platinum, Gold, Career , Coach
        $sport_id = !empty($this->input->post('sport_id')) ? $this->input->post('sport_id') : null;
        $city_id = !empty($this->input->post('city_id')) ? $this->input->post('city_id') : null; 
        $benefits = !empty($this->input->post('benefits')) ? $this->input->post('benefits') : ""; 
        $months = !empty($this->input->post('months')) ? $this->input->post('months') : ""; // 1,2,3,4,5,6
        $subscription_id = !empty($this->input->post('subscription_id')) ? $this->input->post('subscription_id') : ""; 
        $refered_by = !empty($this->input->post('refered_by')) ? $this->input->post('refered_by') : ""; 
        $amount = !empty($this->input->post('amount')) ? $this->input->post('amount') : ""; 
        $plan_type = !empty($this->input->post('plan_type')) ? $this->input->post('plan_type'): ""; // Contact Detail Plan-Career/Coach,   Listing Plan -Platinum/Gold
        if (!empty($uid)) {
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
            if (!empty($category) && !empty($months) && !empty($benefits) &&  !empty($subscription_id) && !empty($plan_type) && !empty($amount)){
                if (!empty($pk_id)) {
                    $table = "buy_subscription";
                    $orderby = 'pk_id desc';
                    $condition = array('status' => '2', 'pk_id' => $pk_id);
                    $col = array('pk_id','cost','expDate');
                    $checkExistPlan = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                }
              
                $table = "buy_subscription";
                $orderby = 'pk_id desc';
                $condition = array('status' => '2');
                $this->db->where('category',$category);
                $this->db->where('user_id',$uid);
                if($plan_type == 'Listing Plan'){
                    $this->db->where('listtype',$listtype);
                    $this->db->where('fk_sport',$sport_id);
                    $this->db->where('fk_city',$city_id);
                }
                $col = array('pk_id','cost','expDate');
                $dublicatePlan = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    
                if (empty($checkExistPlan)) {                    
                    $table = "buy_subscription";
                    $time = date('Y-m-d H:i:s');
                    $insert_data = array(
                        'plan' => $plan_type,
                        'category' => $category,
                        'listtype' => $listtype,
                        'user_id' => $uid,
                        'sub_id' => $subscription_id,
                        'fk_sport' => $sport_id,
                        'fk_city' => $city_id,
                        'months' => $months ,
                        'refered_by' => $refered_by,
                        'description' => $benefits,
                        'cost' => $amount,
                        'status' => '2',
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    if($plan_type == 'Listing Plan'){
                        $insert_data['plan_id']='1';
                     }elseif ($plan_type == 'Contact Detail Plan') {
                        $insert_data['plan_id']='2';
                     }
                    // if($listtype == 'Sport Dealers'){
                    //     $insert_data['reference_listtype']='sport-dealers';
                    //  }elseif ($listtype == 'Physio Therapy') {
                    //     $insert_data['reference_listtype']='Physio-Therapy';
                    //  }elseif ($listtype == 'Dietitian') {
                    //     $insert_data['reference_listtype']='Dietitian';
                    //  }elseif ($listtype == 'Tournaments') {
                    //     $insert_data['reference_listtype']='Tournaments';
                    //  }elseif ($listtype == 'Treatment And Spa') {
                    //     $insert_data['reference_listtype']='Treatment-And-Spa';
                    //  }elseif ($listtype == 'Orthopedic') {
                    //     $insert_data['reference_listtype']='Orthopedic';
                    //  }
                     if($listtype == 'Sport Dealers'){
                        $insert_data['reference_listtype']='sport-dealers';
                     }elseif ($listtype == 'Physio Therapy') {
                        $insert_data['reference_listtype']='physio-therapy';
                     }elseif ($listtype == 'Treatment And Spa') {
                        $insert_data['reference_listtype']='treatment-and-spa';
                     }

                    // if (!empty($dublicatePlan)){
                        $insert_data['start_date']=null;
                        $insert_data['expDate']=null;
                        // $insert_data['start_date']=$dublicatePlan[0]['expDate'];
                        // $insert_data['expDate']=date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime($dublicatePlan[0]['expDate'])));
                    // }else{                        
                    //     $insert_data['start_date']=date('Y-m-d H:i:s');
                    //     $insert_data['expDate'] = date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime(date('Y-m-d H:i:s'))));
                    // }
                         
                    $result = $this->Md_database->insertData($table, $insert_data);
                    $pk_id = $this->db->insert_id();
                    if (!empty($result)) {
                        $resultarray = array('error_code' => '1', 'message' => 'Added Plan','pk_id'=>$pk_id, 'plan' => $plan_type,'category' => $category,'listtype' => $listtype,'user_id' => $uid, 'sub_id' => $subscription_id,'fk_sport' => $sport_id,'fk_city' => $city_id, 'description' => $benefits,'cost' => $amount);
                        echo json_encode($resultarray); 
                        exit();
                    }                     
                }else{
                    $table = "buy_subscription";
                    $time = date('Y-m-d H:i:s');
                    // $final = date("Y-m-d H:i:s", strtotime("+".$months."month", date('Y-m-d H:i:s')));
                    $final_amount = $amount + $checkExistPlan[0]['cost'];
                    // $final_amount = $months + $checkExistPlan[0]['expDate'];
                    $update_data = array(
                        'plan' => $plan_type,
                        'category' => $category,
                        'listtype' => $listtype,
                        'user_id' => $uid,
                        'sub_id' => $subscription_id,
                        'fk_sport' => $sport_id,
                        'fk_city' => $city_id,
                        'months' => $months,
                        'refered_by' => $refered_by,
                        'description' => $benefits,
                        'cost' => $amount,
                        // 'cost' => $final_amount,
                        'status' => '2',
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),
                        'updated_ip_address' => $_SERVER['REMOTE_ADDR']
                    );
                    $condition = array(
                        'pk_id' => $checkExistPlan[0]['pk_id']
                    );
                    if($plan_type == 'Listing Plan'){
                        $update_data['plan_id']='1';
                     }elseif ($plan_type == 'Contact Detail Plan') {
                        $update_data['plan_id']='2';
                     }
                      if($listtype == 'Sport Dealers'){
                        $insert_data['reference_listtype']='sport-dealers';
                     }elseif ($listtype == 'Physio Therapy') {
                        $insert_data['reference_listtype']='physio-therapy';
                     }elseif ($listtype == 'Treatment And Spa') {
                        $insert_data['reference_listtype']='treatment-and-spa';
                     }
                    // if (!empty($dublicatePlan)){
                        $update_data['start_date']=null;
                        $update_data['expDate']=null;
                        // $update_data['start_date']=$dublicatePlan[0]['expDate'];
                        // $update_data['expDate']=date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime($dublicatePlan[0]['expDate'])));
                    // }else{                        
                    //     $update_data['start_date']=date('Y-m-d H:i:s');
                    //     $update_data['expDate'] = date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime(date('Y-m-d H:i:s'))));
                    // }
                    $update_id = $this->Md_database->updateData($table, $update_data, $condition);   
                    $table = "buy_subscription";
                    $time = date('Y-m-d H:i:s');
                    // $final = date("Y-m-d H:i:s", strtotime("+".$months."month", date('Y-m-d H:i:s')));
                    $final_amount = $amount + $checkExistPlan[0]['cost'];
                    // $final_amount = $months + $checkExistPlan[0]['expDate'];
              
                    if (!empty($update_id)) {
                        $resultarray = array('error_code' => '1', 'message' => 'Updated Plan','pk_id'=>$checkExistPlan[0]['pk_id'], 'plan' => $plan_type,'category' => $category,'listtype' => $listtype,'user_id' => $uid,'sub_id' => $subscription_id,'fk_sport' => $sport_id,'fk_city' => $city_id,'refered_by' => $refered_by,'description' => $benefits,'cost' => $final_amount,'months' => $months);
                        echo json_encode($resultarray); 
                        exit();
                    }
                }                                
            }else{
                $resultarray = array('error_code' => '3', 'message' => 'category or months or benefits or subscription_id or plan_type or amount is  empty.');
                echo json_encode($resultarray); 
                exit();
            }                  
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is  empty.');
            echo json_encode($resultarray);
            exit();
        }
    }
        
    public function reviewPlan(){
        require('config.php');
       
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';

        if (!empty($uid)) {
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

            $review=array();
            $table = "buy_subscription";
            $orderby = 'buy_subscription.pk_id asc';
            $condition = array('buy_subscription.status' => '2', 'buy_subscription.user_id' => $uid);
            $col = array('buy_subscription.pk_id,plan,category,listtype,sub_id,sport.sportname,city.city_name,refered_by,ROUND(cost ,2) as cost,COALESCE(description,"") as description,expDate','months','start_date','buy_subscription.fk_sport','buy_subscription.fk_city');
            $this->db->join('city', 'city.pk_id = buy_subscription.fk_city','LEFT');
            $this->db->join('sport', 'sport.pk_id = buy_subscription.fk_sport','LEFT');
            $review = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            $razorpayOrderId = '';
            if (!empty($review)) {
      
                $total_cost = '0';
                foreach ($review as $key => $value) {

                    $total_cost = $total_cost + $value['cost'];            
                }
                // Create the Razorpay Order
                $api = new Api($keyId, $keySecret);
                $orderData = [
                    'receipt'         => 3456,
                    'amount'          => intval($total_cost) *100, // 2000 rupees in paise
                    'currency'        => 'INR',
                    'payment_capture' => 1 // auto capture
                ];

                $razorpayOrder = $api->order->create($orderData);
                $razorpayOrderId = $razorpayOrder['id'];

                foreach ($review as $key => $value) {
                    $id = $value['pk_id'];
                    $table = "buy_subscription";
                    $update_data = array(              
                        'razorpay_order_id' => $razorpayOrderId,
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),           
                    );
                    $condition = array(
                        'pk_id' => $id,
                    );
                    $update_id = $this->Md_database->updateData($table, $update_data, $condition);                  
                }
            }
            $resultarray = array('error_code' => '1', 'message' => 'Review Plan','review_plan'=>$review,"order_id"=>$razorpayOrderId);                   
            echo json_encode($resultarray);
            exit();                           
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is  empty.');
            echo json_encode($resultarray);
            exit();
        }
    }

    public function transactionSuccessfully(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $transaction_id = !empty($this->input->post('transaction_id')) ? $this->input->post('transaction_id') : '';
        $razorpay_order_id = !empty($this->input->post('razor_pay_order_id')) ? $this->input->post('razor_pay_order_id') : '';
        $total_amount = !empty($this->input->post('total_amount')) ? $this->input->post('total_amount') : '';
        // $id = '[{"pk_id":"187"},{"pk_id":"189"}]';
        $id = !empty($this->input->post('id')) ? $this->input->post('id') : "";
        $ids=json_decode($id);
        // print_r($ids);die();
        if (!empty($uid)) {
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
            if (!empty($transaction_id) && !empty($total_amount) && !empty($id)) {            
                foreach ($ids as $key => $value){
                    $id = $value->pk_id;
                
                    $table = "buy_subscription";
                    $update_data = array(              
                        'transaction_id' => $transaction_id,
                        'updatedBy' => $uid,
                        'updatedDate' => date('Y-m-d H:i:s'),           
                    );
                    $condition = array(
                        'pk_id' => $id,
                    );
                    $update_id = $this->Md_database->updateData($table, $update_data, $condition); 

                    $table = "buy_subscription";
                    $orderby = 'buy_subscription.pk_id asc';
                    $condition = array('user_id' => $uid);
                    $this->db->where('buy_subscription.pk_id!=',$id);
                    $col = 'listtype,category,pk_id';
                    $allbuyData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    if (!empty($allbuyData)) {
                        foreach ($ids as $key => $value){
                            $nowid = $value->pk_id;
                            $table = "buy_subscription";
                            $orderby = 'buy_subscription.pk_id asc';
                            $condition = array('user_id' => $uid,'buy_subscription.pk_id'=>$nowid);
                            $col = 'listtype,category';
                            $nowbuyData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                            foreach ($allbuyData as $key => $value){
                                $allid= $value['pk_id'];                                   
                                $allcategory= $value['category'];
                                $alllisttype= $value['listtype'];

                                $table = "buy_subscription";
                                $orderby = 'buy_subscription.pk_id asc';
                                $condition = array('user_id' => $uid,'buy_subscription.pk_id'=>$allid);
                                $this->db->where('category',$allcategory);
                                $this->db->where('listtype',$alllisttype);
                                $col = 'listtype,category';
                                $idData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                                if (empty($idData)) {
                                    $table = "buy_subscription";
                                    $update_data = array(              
                                        'list_status' => '1',
                                        'updatedBy' => $uid,
                                        'updatedDate' => date('Y-m-d H:i:s'),           
                                    );
                                    $condition = array(
                                        'pk_id' => $allid,
                                    );
                                    $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                }
                            }
                        }
                    }

                    // Update order list by status

                        // $table = "buy_subscription";
                        // $orderby = 'buy_subscription.pk_id asc';
                        // $condition = array('user_id' => $uid,'buy_subscription.pk_id'=>$id);
                        // $col = 'listtype,category';
                        // $buyData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                          
                        // if (!empty($buyData)){                             
                        //     $table = "buy_subscription";
                        //     $orderby = 'pk_id asc';
                        //     $condition = array('list_status' => '1', 'user_id' => $uid,'listtype'=>$buyData[0]['listtype']);
                         
                        //     $col = array('pk_id','category','listtype');
                        //     $checkAlredyExistStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                        //     // print_r($checkAlredyExistStatus);
                        //     // die();
                        //     if (empty($checkAlredyExistStatus)){
                        //         $table = "buy_subscription";
                        //         $orderby = 'pk_id asc';
                        //         $condition = array('category' => 'Platinum','listtype'=>$buyData[0]['listtype'], 'user_id' => $uid);
                        //         // $this->db->where('listtype',);
                        //         $col = array('pk_id','category');
                        //         $checkPlatimuntStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                        //         if(!empty($checkPlatimuntStatus)){
                        //             $table = "buy_subscription";
                        //             $update_data = array(              
                        //                 'list_status' => '1',
                        //                 'updatedBy' => $uid,
                        //                 'updatedDate' => date('Y-m-d H:i:s'),           
                        //             );
                        //             $condition = array(
                        //                 'pk_id' => $checkPlatimuntStatus[0]['pk_id'],
                        //             );
                        //             $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                   
                        //         }else{
                        //             $table = "buy_subscription";
                        //             $orderby = 'pk_id asc';
                        //             $condition = array('category' => 'Gold', 'user_id' => $uid);
                        //             $col = array('pk_id','category');
                        //             $checkGoldtStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 

                        //             if (!empty($checkGoldtStatus)) {
                        //                 $table = "buy_subscription";
                        //                 $update_data = array(              
                        //                     'list_status' => '1',
                        //                     'updatedBy' => $uid,
                        //                     'updatedDate' => date('Y-m-d H:i:s'),           
                        //                 );
                        //                 $condition = array(
                        //                     'pk_id' => $checkGoldtStatus[0]['pk_id'],
                        //                 );
                        //                 $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                        
                        //             }
                        //         }                        
                        //     }
                        // }




                    // $table = "buy_subscription";
                    // $orderby = 'pk_id asc';
                    // $condition = array('list_status' => '1', 'user_id' => $uid);
                    // // $this->db->()
                    // $col = array('pk_id','category');
                    // $checkAlredyExistStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                    // if (empty($checkAlredyExistStatus)){
                    //     $table = "buy_subscription";
                    //     $orderby = 'pk_id asc';
                    //     $condition = array('category' => 'Platinum', 'user_id' => $uid);
                    //     // $this->db->where('listtype',);
                    //     $col = array('pk_id','category');
                    //     $checkPlatimuntStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                    //     if(!empty($checkPlatimuntStatus)){
                    //         $table = "buy_subscription";
                    //         $update_data = array(              
                    //             'list_status' => '1',
                    //             'updatedBy' => $uid,
                    //             'updatedDate' => date('Y-m-d H:i:s'),           
                    //         );
                    //         $condition = array(
                    //             'pk_id' => $checkPlatimuntStatus[0]['pk_id'],
                    //         );
                    //         $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                           
                    //     }else{
                    //         $table = "buy_subscription";
                    //         $orderby = 'pk_id asc';
                    //         $condition = array('category' => 'Gold', 'user_id' => $uid);
                    //         $col = array('pk_id','category');
                    //         $checkGoldtStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 

                    //         if (!empty($checkGoldtStatus)){
                    //             $table = "buy_subscription";
                    //             $update_data = array(              
                    //                 'list_status' => '1',
                    //                 'updatedBy' => $uid,
                    //                 'updatedDate' => date('Y-m-d H:i:s'),           
                    //             );
                    //             $condition = array(
                    //                 'pk_id' => $checkGoldtStatus[0]['pk_id'],
                    //             );
                    //             $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                
                    //         }
                    //     }                        
                    // }
                    // foreach ($ids as $key => $value) {
                    //     $id = $value->pk_id;

                    //     $table = "buy_subscription";
                    //     $orderby = 'buy_subscription.pk_id asc';
                    //     $condition = array('user_id' => $uid,'buy_subscription.pk_id'=>$id);
                    //     $col = 'listtype,category';
                    //     $buyData = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    //     // !empty($)
                    // // $plandata = $checkSelectedPlan;
                   
                    //     if (!empty($buyData)) {
                    //         $table = "buy_subscription";
                    //         $orderby = 'pk_id asc';
                    //         $condition = array('list_status' => '1', 'user_id' => $uid);
                    //         $this->db->where('listtype',$buyData[0]['listtype']);
                    //         $col = array('pk_id','category');
                    //         $checkAlredyExistStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                          


                    //         if (empty($checkAlredyExistStatus)){
                    //             $table = "buy_subscription";
                    //             $orderby = 'pk_id asc';
                    //             $condition = array('category' => 'Platinum', 'user_id' => $uid);
                    //             // $this->db->where('listtype',);
                    //             $col = array('pk_id','category');
                    //             $checkPlatimuntStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 
                    //             if(!empty($checkPlatimuntStatus)){
                    //                 $table = "buy_subscription";
                    //                 $update_data = array(              
                    //                     'list_status' => '1',
                    //                     'updatedBy' => $uid,
                    //                     'updatedDate' => date('Y-m-d H:i:s'),           
                    //                 );
                    //                 $condition = array(
                    //                     'pk_id' => $checkPlatimuntStatus[0]['pk_id'],
                    //                 );
                    //                 $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                   
                    //             }else{
                    //                 $table = "buy_subscription";
                    //                 $orderby = 'pk_id asc';
                    //                 $condition = array('category' => 'Gold', 'user_id' => $uid);
                    //                 $col = array('pk_id','category');
                    //                 $checkGoldtStatus = $this->Md_database->getData($table, $col, $condition, $orderby, ''); 

                    //                 if (!empty($checkGoldtStatus)) {
                    //                     $table = "buy_subscription";
                    //                     $update_data = array(              
                    //                         'list_status' => '1',
                    //                         'updatedBy' => $uid,
                    //                         'updatedDate' => date('Y-m-d H:i:s'),           
                    //                     );
                    //                     $condition = array(
                    //                         'pk_id' => $checkGoldtStatus[0]['pk_id'],
                    //                     );
                    //                     $update_id = $this->Md_database->updateData($table, $update_data, $condition);
                                        
                    //                 }
                    //             }                        
                    //         }
                    //     }
                    // }
                          

                    /***update Start Date and Expiry Date***/    
                    $table = "buy_subscription";
                    $orderby = 'pk_id asc';
                    $condition = array('status' => '2', 'user_id' => $uid);
                    $col = array('pk_id','plan_id','listtype','fk_sport','fk_city','category','months','cost');
                    $payPlans = $this->Md_database->getData($table, $col, $condition, $orderby, '');  
                    foreach ($payPlans as $key => $value){
                        $plan_id = !empty($value['plan_id'])?$value['plan_id']:''; 

                        $listtype = !empty($value['listtype'])?$value['listtype']:''; 
                        $city_id =!empty( $value['fk_city'])? $value['fk_city']:null; 
                        $sport_id = !empty($value['fk_sport'])?$value['fk_sport']:null; 
                        $category = !empty($value['category'])?$value['category']:''; 
                        $months = !empty($value['months'])?$value['months']:''; 
                        $amount =!empty($value['cost'])? $value['cost']:''; 
                        $pk_id = $value['pk_id']; 
         
                        $table = "buy_subscription";
                        $orderby = 'pk_id desc';
                        $condition = array('user_id' => $uid);
                        $col = array('pk_id','start_date','expDate');
                        $this->db->where('category',$category);
                        $this->db->where('plan_id',$plan_id);
                        $this->db->where('expDate is NOT NULL', NULL, FALSE);
                        $this->db->where('listtype',$listtype);
                        $this->db->where('fk_sport',$sport_id);
                        $this->db->where('fk_city',$city_id);
                        $checkAlreadyExist = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                        if(!empty($checkAlreadyExist)){
                
                            $existExpDate= $checkAlreadyExist[0]['expDate'];
                            $table = "buy_subscription";
                            $update_data = array(                     
                                // 'start_date' => $existExpDate,
                                // 'expDate' => date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime($existExpDate))),
                                'status' => '1', 
                                'updatedBy' => $uid, 
                                'updatedDate' => date('Y-m-d H:i:s'),
                                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array(
                                'pk_id'=>$pk_id,
                            );
                            if ($checkAlreadyExist[0]['expDate'] >date('Y-m-d H:i:s')){
                                //update from expdate
                                $update_data['start_date']=$existExpDate;
                                $update_data['expDate']=date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime($existExpDate)));                   
                            }else{
                                //update from today
                                $update_data['start_date']=date('Y-m-d H:i:s');
                                $update_data['expDate']=date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime(date('Y-m-d H:i:s'))));       
                            }
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);                                                      
                        }else{
                            //update today
                            $table = "buy_subscription";
                            $update_data = array(   
                                'status' => '1',                  
                                'start_date' => date('Y-m-d H:i:s'),
                                'expDate' => date('Y-m-d H:i:s', strtotime("+".$months." month", strtotime(date('Y-m-d H:i:s')))),
                                'updatedBy' => $uid, 
                                'updatedDate' => date('Y-m-d H:i:s'),
                                'updated_ip_address' => $_SERVER['REMOTE_ADDR']               
                            );
                            $condition = array(
                                'pk_id'=>$pk_id
                            );
                            $resultarray = $this->Md_database->updateData($table, $update_data, $condition);      
                        }
                    }
                }
                
                //Maintain in transaction history
                $table = 'transaction_history';
                $insert_data = array(
                    'fk_uid' => $uid,
                    'tran_id' => $transaction_id,
                    'tran_amount' => $total_amount,
                    'razorpay_order_id' => $razorpay_order_id,
                    'status' => '1',
                    'createdBy' => $uid,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    );                     
                    $result = $this->Md_database->insertData($table, $insert_data);
                    $insert_id = $this->db->insert_id();

                //Send Mail for invoice buy subscription plan
                    // print_r($ids);
                    // die();
                $plandata = array();
                foreach ($ids as $key => $value) {
                    $id = $value->pk_id;

                    $table = "buy_subscription";
                    $orderby = 'buy_subscription.pk_id asc';
                    $condition = array('user_id' => $uid,'buy_subscription.pk_id'=>$id);
                    $this->db->join('subscription','buy_subscription.sub_id= subscription.pk_id','LEFT');
                    $this->db->group_by('buy_subscription.user_id');
                     // $this->db->group_by('user_id'); 
                    $col = 'sub_id,buy_subscription.category,buy_subscription.listtype,cost, COUNT(user_id) as user_id,expDate,start_date';
                    $checkSelectedPlan = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                  array_push($plandata, $checkSelectedPlan[0]);
                    // $plandata = $checkSelectedPlan;
                }
                    // print_r($plandata);
                    // die();
                 // // print_r($checkSelectedPlan);
                 // die();

            $table = "user";
            $orderby = 'user.pk_id asc';
            $condition = array('user.status' => '1', 'user.pk_id' => $uid);
            $this->db->join('city','user.city=city.pk_id');
            $col = array('user.pk_id','name','city_name');
            $UserName = $this->Md_database->getData($table, $col, $condition, $orderby, '');


                $logocomimg = base_url()."AdminMedia/images/invoice_logo.png" ;
                $html='';
                $html='
                  <html>
                <head>
                    <title>Koodo Invoice</title>
                </head>
                <body style="color:#666">
                    <div style="background-color: #f1f1f1; width: 65%; margin: 40px auto; padding: 20px;">
                    <div style="background-color: #f1f1f1; padding: 20px; border:1px solid #ddd;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding: 0px 0px 10px">
                            <div style="flex: 1;">
                                <img src=' . $logocomimg . ' width="25%">
                                <div><span style="color: #d36355">Khelo With KOODO</span></div>
                            </div>
                            <div style="flex: 1; text-align: left;">                
                                <div>
                                    <h2>INVOICE</h2>
                                    
                                </div>              
                            </div>
                        </div>
                    
                        <div style="justify-content: space-between; align-items: center; text-align: left; padding: 20px 0px">
                        <div style="width: 100%; margin-bottom:5px; color: #222; display:flex; " > <b style="margin-right: 5px; width:25%">Name :</b>'.$UserName[0]['name'].' </div>
                        <div style="width: 100%; margin-bottom:5px; color: #222; display:flex; " ><b style="margin-right: 5px; width:25%">City :</b>'.$UserName[0]['city_name'].' </div>
                        <div style="width: 100%; margin-bottom:5px; color: #222; display:flex; " ><b style="margin-right: 5px; width:25%">Order ID :</b>'.$razorpay_order_id.' </div>
                        <div style="width: 100%; margin-bottom:20px; color: #222; display:flex; " ><b style="margin-right: 5px; width:25%">Transaction ID :</b>'.$transaction_id.' </div>
                            <table style="width: 100%; border:1px solid #ddd; text-align: center;">
                                <thead>
                                <tr>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Sub. Id</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Sub. Date</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Category</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Listing Plan</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Start Date</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Expiry Date</th>
                                    <th style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">Price (INR)</th>                  
                                </tr>
                            </thead>
                            <tbody>';

            //PDF Invoice 




        // $pdfFilePath =FCPATH."uploads/subscription_invoice/" .$transaction_id."_".date('Y-m-d H:i:s').".pdf";

    
        //     $this->load->library('M_pdf');
        //     $this->m_pdf->pdf->AddPage(); // margin footer
        //     // $pdfFilePath = "Order_Invoices.pdf";
        //     $this->m_pdf->pdf->WriteHTML($html);
        //     $this->m_pdf->pdf->Output($pdfFilePath, "F"); 


             $k=1;
               // foreach($checkSelectedPlan as $rows){
               foreach($plandata as $key => $rows){
               $html.= '<tr>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$k;$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['sub_id'];$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['category'];$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['listtype'];$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['start_date'];$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['expDate'];$html .='</td>';
                $html .='<td style="padding: 5px 0px; border:1px solid #ddd">';$html .=$rows['cost'];$html .='</td>';
                    // <td style="padding: 5px 0px; border:1px solid #ddd">KD123456</td>
                    // <td style="padding: 5px 0px; border:1px solid #ddd">12/12/2020</td>
                    // <td style="padding: 5px 0px; border:1px solid #ddd">Platinum</td>
                            
                    // <td style="padding: 5px 0px; border:1px solid #ddd">Players</td>
                
                    // <td style="padding: 5px 0px; border:1px solid #ddd">23/07/2021</td>
                
                    // <td style="padding: 5px 0px; border:1px solid #ddd">765</td>
                $html .='</tr>';
                $k++;
              }
                $html.='<tr>
                    <td colspan="6" style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd text-align: right;">Total</td>
                    <td style="width: 10%;padding-bottom: 10px; color: #222; padding: 10px 0px; background-color: #ddd;border:1px solid #ddd">';$html .=$total_amount; $html .='</td>';
                $html .='</tr>
            </tbody>
            </table>
            <div>
                <p style="margin: 7px 0px; font-size: 15px; color: #222"><b></b></p>
                <p style="line-height: 20px; margin: 7px 0px; text-align: justify;"></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>';
 
                
                $table = "user";
                $orderby = 'pk_id asc';
                $condition = array('status' => '1', 'pk_id' => $uid);
                $col = array('pk_id','name','email');
                $checkEmailId = $this->Md_database->getData($table, $col, $condition, $orderby, '');


                 //PDF
                $this->load->library('M_pdf');                                
                $pdfFilePath =FCPATH."uploads/subscription_invoice/" .$transaction_id."_".date('Y-m-d H:i:s').".pdf";
                $this->m_pdf->pdf->AddPage(); // margin footer
                $this->m_pdf->pdf->WriteHTML($html);
                $this->m_pdf->pdf->Output($pdfFilePath, "F"); 
            
                //Send Invoice Mail with pdf
                $subject = 'Invoice';
                $recipient = $checkEmailId[0]['email'];
                $from = array(
                    "email" => SITE_MAIL,
                    "name" => SITE_TITLE
                );
            
                    $config['protocol'] = 'mail';
                    $config['wordwrap'] = FALSE;
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['crlf'] = "\r\n";
                    $config['newline'] = "\r\n";
                    $this->load->library('email', $config);
                    $this->email->initialize($config);
             
                    $this->email->from(stripslashes($from['email']), $from['name']);
         
                    $this->email->subject($subject);
    
                    $this->email->to($recipient);
                    
                    // $this->email->cc($cc);
                    $this->email->message($html);

                    $this->email->attach($pdfFilePath);
              
                    $this->email->send();
            
 
               if (!empty($insert_id)){
                    $resultarray = array('error_code' => '1', 'message' => 'Successfully'); 
                    echo json_encode($resultarray);
                    exit();                           
               }
            }else{
                $resultarray = array('error_code' => '3', 'message' => 'transaction_id or total_amount or id is  empty.');
                echo json_encode($resultarray);
                exit();
            }
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid is  empty.');
            echo json_encode($resultarray);
            exit();
        }
    }


    public function deleteSelectedPlan(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $plan_id = !empty($this->input->post('plan_id')) ? $this->input->post('plan_id') : '';//pk_id of buy_subscription
       
        if (!empty($uid) && !empty($plan_id)){
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
            $table = "buy_subscription";
            $update_data = array(              
                'status' => '3',
                'updatedBy' => $uid,
                'updatedDate' => date('Y-m-d H:i:s'),           
            );
            $condition = array(
                'pk_id' => $plan_id,
            );
            $update_id = $this->Md_database->updateData($table, $update_data, $condition);   
            if (!empty($update_id)){
                $resultarray = array('error_code' => '1', 'message' => 'Delete Selected Plan');
                echo json_encode($resultarray);
                exit();
            }                             
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid or plan_id is  empty.');
            echo json_encode($resultarray);
            exit();
        }
    }


    public function plan_reminder_msg(){
        $this->db->select('A.pk_id,user_id,start_date,expDate,B.token');
        $this->db->from('buy_subscription as A');
        $this->db->join('user as B', 'B.pk_id=A.user_id');
        $this->db->where('B.status','1');
        $buy_subscription_list = $this->db->get()->result_array();
        foreach ($buy_subscription_list as $key => $value){
            $start_date = $value['start_date'];
            $end_date= $value['expDate'];
            $user_id= $value['user_id'];
            $token= $value['token'];

            $before_three_days = date('Y-m-d',(strtotime ( '-3 day' , strtotime ( $end_date) ) ));
            $expire_date = date('Y-m-d',(strtotime($end_date)));
            $after_one_day = date('Y-m-d',(strtotime ( '+1 day' , strtotime ( $end_date) ) ));

            //Send notification 
            $table = "privileges_notifications";
            $select = "notifications,chat_notification";
            $this->db->where('fk_uid',$user_id);
            $this->db->order_by('pk_id','ASC');
            $chechprivilege = $this->Md_database->getData($table, $select, '', 'pk_id ASC', '');

            $notification=!empty($chechprivilege[0]['notifications'])?$chechprivilege[0]['notifications']:'';
            // echo "<pre>";
        // print_r($expire_date); 
        // print_r($before_three_days);
        // print_r($user_id);
        // print_r($token);
            if (date('Y-m-d') == $before_three_days ){
                $message = "Your subscription will expire in 3 days, Please renew the paln to continue enjoying it's benefits";
                if ($notification=='1' ){
                    $table = "user";
                    $select = "token,user.pk_id,name";
                    $this->db->where('pk_id',$user_id);
                    $this->db->order_by('user.pk_id','ASC');
                    $this->db->distinct();
                    $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                    $target=$order_token[0]['token'];
                    
                    $resultarray = array('message' => $message,'redirect_type' =>'before_three_days_expire_plan_reminder','subject'=>'Subscription Plan will expire');
                        
                    $this->Md_database->sendPushNotification($resultarray,$target);
                }
                if(!empty($message)){
                    //store into database typewise
                    $table = "custom_notification";
                    $insert_data = array(
                        'from_uid'=>'',
                        'to_user_id'=>$user_id,
                        'redirect_type' => 'before_three_days_expire_plan_reminder',
                        'subject' => 'Subscription Plan will expire',
                        'message'=>$message,
                        'status' => '1',
                        'created_by ' =>$user_id,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                    );
                    $result = $this->Md_database->insertData($table, $insert_data);
                } 
            }elseif(date('Y-m-d') == $expire_date){
                $message = "Your subscription expires today, Please renew the plan to continue enjoying it's benefits";
                if ($notification=='1' ){
                    $table = "user";
                    $select = "token,user.pk_id,name";
                    $this->db->where('pk_id',$user_id);
                    $this->db->order_by('user.pk_id','ASC');
                    $this->db->distinct();
                    $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                    $target=$order_token[0]['token'];
                    
                    $resultarray = array('message' => $message,'redirect_type' =>'expire_date_reminder','subject'=>'Subscription Plan will expire');
                        
                    $this->Md_database->sendPushNotification($resultarray,$target);
                }
                if(!empty($message)){
                    //store into database typewise
                    $table = "custom_notification";
                    $insert_data = array(
                        'from_uid'=>'',
                        'to_user_id'=>$user_id,
                        'redirect_type' => 'expire_date_reminder',
                        'subject' => 'Subscription Plan is expire today',
                        'message'=>$message,
                        'status' => '1',
                        'created_by ' =>$user_id,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                    );
                    $result = $this->Md_database->insertData($table, $insert_data);
                } 

            }elseif(date('Y-m-d') == $after_one_day){
                 $message = "Your subscription has expired, Please renew the paln to continue enjoying it's benefits";
                if ($notification=='1' ){
                    $table = "user";
                    $select = "token,user.pk_id,name";
                    $this->db->where('pk_id',$user_id);
                    $this->db->order_by('user.pk_id','ASC');
                    $this->db->distinct();
                    $order_token = $this->Md_database->getData($table, $select, '', 'user.pk_id ASC', '');
                    $target=$order_token[0]['token'];
                    
                    $resultarray = array('message' => $message,'redirect_type' =>'expired','subject'=>'Subscription Plan is expired');
                        
                    $this->Md_database->sendPushNotification($resultarray,$target);
                }
                if(!empty($message)){
                    //store into database typewise
                    $table = "custom_notification";
                    $insert_data = array(
                        'from_uid'=>'',
                        'to_user_id'=>$user_id,
                        'redirect_type' => 'expired',
                        'subject' => 'Subscription Plan is expire today',
                        'message'=>$message,
                        'status' => '1',
                        'created_by ' =>$user_id,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_ip_address'=>$_SERVER['REMOTE_ADDR'] 
                    );
                    $result = $this->Md_database->insertData($table, $insert_data);
                } 
        // die();
            }                       
        }     
    }

//     public function remind_msg_pujaday() {
//         $before2hr=date('h:i A', strtotime('+2 hour', time()));
//         $pujadate=date('Y-m-d');
    
//         // echo "<pre>";print_r($puja_data);die();
//         $this->db->select('A.pk_id as puja_order_id,pooja_date,pooja_time,pooja_address,pooja_city,pooja_name,fk_purohit,fk_user_id');
//         $this->db->from('customer_pooja_order as A');
//         $this->db->join('pooja as B', 'B.pk_id=A.fk_pooja_id');
//         $this->db->where('A.status', 1);
//         $this->db->where('A.pooja_time', $before2hr);
//         $this->db->where('A.pooja_date', $pujadate);
      
//         $this->db->where('A.pooja_status', '5');
//         $this->db->where('A.fk_purohit!=', null);
//         $puja_data = $this->db->get()->result_array();
//           // echo "<pre>";print_r($this->db->last_query());die();
//          // echo "<pre>";print_r($puja_data);die();


// /*[STart]:: Remainder message to user on the day of puja event and 2hrs before the puja*/
//      if (!empty($puja_data)) {
//             foreach ($puja_data as $rows) {
//             $pooja_order_id=!empty($rows['puja_order_id'])?$rows['puja_order_id']:'';
//             $pooja_name=!empty($rows["pooja_name"])? ucwords($rows["pooja_name"]):'';
//             $pooja_address=!empty($rows["pooja_address"])? ucwords($rows["pooja_address"]):'';
//             $city=!empty($rows["pooja_city"])?$rows["pooja_city"]:'';
//             $pooja_date=!empty($rows["pooja_date"])?$rows["pooja_date"]:'';
//             $pooja_time=!empty($rows["pooja_time"])?$rows["pooja_time"]:'';

        
//                     //text message send to customer
//                     $this->db->select('customer_mobile_no');
//                     $this->db->from('customer_registration');
//                     $this->db->where('status','1');
//                     $this->db->where('pk_id',$rows['fk_user_id']);
//                     $customer_details = $this->db->get()->result_array();

//                     if (!empty($customer_details)) {
//                     foreach ($customer_details as $val) {
//                         $cust_mobile_no=!empty($val['customer_mobile_no'])?$val['customer_mobile_no']:'';
               
//                          $cust_message = 'It is a remainder message for the '.$pooja_name.' booked by you is on '.date('d-m-Y',strtotime($pooja_date)). ', time '.$pooja_time.' at your '.$pooja_address.', '.$city.'.';
//                         $this->Md_database->sendSMS($cust_message, $cust_mobile_no);
//                          }
//                     }
//                 /*[End]:: Remainder message to user on the day of puja event and 2hrs before the puja*/


//                 $this->db->select('token,pk_id,location,mobile_no,first_name,middle_name,last_name');
//                 $this->db->from('registered_purohit');
//                 $this->db->where('pk_id',$rows['fk_purohit']);
//                 $this->db->where('status','1');
//                 $purohit_data=$this->db->get()->result_array();
//                     if (!empty($purohit_data)) {
//                     foreach ($purohit_data as $val) {
//                         $mobile_no=!empty($val['mobile_no'])?$val['mobile_no']:'';
//                         $first_name=!empty($val['first_name'])?ucwords($val['first_name']):'';
//                         $middle_name=!empty($val['middle_name'])? ucwords($val['middle_name']):'';
//                         $last_name=!empty($val['last_name'])?ucwords($val['last_name']):'';
//                         $purohit_name=$first_name.' '.$middle_name.' '. $last_name;

//                         // print_r($userdata);die();
//                         if (!empty($val['token'])) {
                       
//                         $message = 'It is a remainder message for the '.$pooja_name.' accepted by you and puja on '.date('d-m-Y',strtotime($pooja_date)). ', time '.$pooja_time.' at '.$pooja_address.', '.$city.'.';
                         
//                             $subject = 'Puja Reminder';
//                             $resultarray = array('message' => $message, 'title' => $subject, 'redirecttype' => "notificationlist");
//                             $target=!empty($val['token'])?$val['token']:'';
//                             // print_r($target);die();
//                             $mobile_no=!empty($val['mobile_no'])?$val['mobile_no']:'';

//                             $this->Md_database->sendPushNotification($resultarray,$target);
//                             // $this->Md_database->sendSMS($message, $mobile_no);

//                             $table = "notifications";
//                             $insert_data = array(
                                
//                                 'fk_purohit_id' => $val['pk_id'],
//                                 'fk_pooja_order_id' => $pooja_order_id,
//                                 'title'=>$subject,
//                                 'message' => $message,
//                                 'redirecttype' => 'notificationlist',
//                                 'status' => '1',
//                                 'notification_datetime' => date('Y-m-d H:i:s'),
                                
//                             );
//                             $this->Md_database->insertData($table, $insert_data);

//                         /*End::Send push notification to purohit those have assign and accept puja*/
//                         }


//                     }
//                 }
//             }
//         }

//     } 
}