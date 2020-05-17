<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_terms_condition extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    public function showTermsAddCondition(){
        $getPageId = !empty($this->input->post('page_id')) ? $this->input->post('page_id') : '';
  	    if (!empty($getPageId)) {
            $table = "cms";
            $orderby = 'cms_pkey asc';
            $condition = array('cms_pkey' =>$getPageId);
            $col = array('cms_title','cms_meta_desc','cms_text');
            $getTermsAndConditions = $this->Md_database->getData($table, $col, $condition, $orderby, '');  

            $resultarray = array('error_code' => '1', 'cms_title' => $getTermsAndConditions[0]['cms_title'], 'cms_meta_desc' =>  strip_tags($getTermsAndConditions[0]['cms_text']),'message'=>'data get succesfully');
            echo json_encode($resultarray);
                    exit();  
        }else {
            $resultarray = array('error_code' => '2', 'message' => 'getPageId is empty');
            echo json_encode($resultarray);
            exit();                     	
        }  
    }//End Function 
}//End this controller section 