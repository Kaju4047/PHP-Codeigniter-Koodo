<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_payment_history extends CI_Controller {
    function __construct() {
        parent::__construct();

    }
    function paymentViewList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : "";
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : ""; 
        if (!empty($uid)) {
    	  	  $table = "user";
            $orderby = 'pk_id asc';
            $condition = array('status' => '2', 'pk_id' => $uid);
            $col = array('pk_id','name');
            $checkUser = $this->Md_database->getData($table, $col, $condition, $orderby, '');
            if (!empty($checkUser)) {
                $resultarray = array('error_code' => '10', 'message' => 'User is inactive. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit(); 
            } 
            $table = "transaction_history";
            $select = array('count(pk_id) as totalcounts');
            $condition = array('status' => '1','fk_uid' => $uid );
            $finalsCounts = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $total_count = (!empty($finalsCounts[0]['totalcounts']) ? $finalsCounts[0]['totalcounts'] : 0);
            
            $table = "transaction_history";
            $select = array('transaction_history.pk_id','fk_uid','tran_id','category',' ROUND(koodo_buy_subscription.cost ,2) as cost','DATE_FORMAT(
                koodo_transaction_history.createdDate, "%d %b %y %h:%i %p") AS date');
            $this->db->join('buy_subscription', 'buy_subscription.transaction_id = transaction_history.tran_id');
	        $condition = array('transaction_history.status' => '1','fk_uid' => $uid);
	        $this->db->limit($limit, $offset);
	        $finalsResult = $this->Md_database->getData($table, $select, $condition, 'pk_id DESC', '');
            $total_paid=0;
            foreach ($finalsResult as $key => $value) {
                $total_paid=$total_paid+$value['cost'];
            }

	        if (!empty($finalsResult)) {
	           $resultarray = array('error_code' => '1', 'total_count' => $total_count, 'total_paid'=>$total_paid,'finalsResult' => $finalsResult, 'message' => 'record fetch.');
	           echo json_encode($resultarray);
	        }else {
	           $resultarray = array('error_code' => '1', 'message' => 'record empty.');
	           echo json_encode($resultarray);
	        }         
	    }else {
        	$resultarray = array('error_code' => '3', 'message' => 'user id is  empty.');
        	echo json_encode($resultarray);
        }
    }//end function
}//end controller 