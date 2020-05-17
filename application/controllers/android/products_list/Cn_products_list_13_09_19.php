<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_products_list extends CI_Controller {
    function __construct() {
        parent::__construct();

    }
function add_products_list(){
 $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
 $txtid = !empty($this->input->post('txtid')) ? $this->input->post('txtid') : '';
 $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
 $product_name = !empty($this->input->post('product_name')) ? $this->input->post('product_name') : '';
 $profilepic = !empty($this->input->post('profilepic')) ? $this->input->post('profilepic') : '';
 $mrp = !empty($this->input->post('mrp')) ? $this->input->post('mrp') : '';
 $offer_price = !empty($this->input->post('offer_price')) ? $this->input->post('offer_price') : '';
 $category = !empty($this->input->post('category')) ? $this->input->post('category') : '';
 $brand_name = !empty($this->input->post('brand_name')) ? $this->input->post('brand_name') : '';    
 $description=!empty($this->input->post('description')) ? $this->input->post('description') : ''; 
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
  if(empty($txtid)){                 
       if((empty($product_name) || empty($mrp) || empty($offer_price) || empty($category))) {
               $resultarray = array('error_code' => '2', 'message' => 'product_name or  or mrp or offer_price or category is empty');
                    echo json_encode($resultarray);
                    exit();
               }else{   
          
          $filedoc = "";
         if (!empty($_FILES['profilepic']['name'])) {
                $rename_name = uniqid(); //get file extension:
                $arr_file_info = pathinfo($_FILES['profilepic']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                $old_name = $_FILES['profilepic']['name'];
               $path = "uploads/products/img/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $upload_type = "pdf|doc|zip|jpg|png|Jpeg";
                $filedoc = $this->Md_database->uploadFile($path, $upload_type, "profilepic", "", $newname);
                if(file_exists($path))
                {
                  if (!empty($this->input->post('oldfile'))) {
                    unlink(FCPATH . 'uploads/products/img/' . $this->input->post('oldfile'));
                }
              }
            } 
                $table="dealer_product";
               	$inserted_data = array(
               		          'type' =>$type,
                            'product_name'=> $product_name,
                            'img' =>$filedoc,
                            'mrp'=> $mrp,
                            'cost'=> $offer_price,
                            'category'=> $category,
                            'brand_name'=>$brand_name,  
                            'description'=>$description,
                            'status'=>'1',                             
                            'createdBy' =>$uid,
                            'dealer_id' =>$uid,
                            'createdDate' => date('Y-m-d H:i:s')
                           
                       );                    
                    $resultarray = $this->Md_database->insertData($table, $inserted_data);           
                    $resultarray = array('error_code' => '1','message' => ' data inserted  successfully');
                    echo json_encode($resultarray);
                    exit();                     	
                  } 
               }else{
               
               if ($type==1) {          
            $table="dealer_product";
            $filedoc = "";
          if (!empty($_FILES['profilepic']['name'])) {
                $rename_name = uniqid(); 
                $arr_file_info = pathinfo($_FILES['profilepic']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                $old_name = $_FILES['profilepic']['name'];
               $path = "uploads/products/img/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
              $upload_type = "jpg|png|Jpeg";
              $filedoc = $this->Md_database->uploadFile($path, $upload_type, "profilepic", "", $newname);
                if(file_exists($path))
                {
                  if (!empty($this->input->post('oldfile'))) {
                    unlink(FCPATH . 'uploads/products/img/' . $this->input->post('oldfile'));
                }
             }
            } 
            $filedoc = !empty($filedoc) ? $filedoc : $this->input->post('oldfile');
            $updated_data = array(
                  'product_name'=> $product_name,
                  'img' =>$filedoc,
                  'mrp'=>$mrp,
                  'cost'=>$offer_price,
                  'category'=>$category,
                  'type'=> $type,
                  'updatedDate' => date('Y-m-d H:i:s')                                 
              );    
            $condition = array("dealer_id" => $txtid,'type'=>$type);                    
            $result = $this->Md_database->updateData($table, $updated_data,$condition);          
            $resultarray = array('error_code' => '1', 'message' => ' data updated  successfully');
                echo json_encode($resultarray);
                exit();  
             }elseif ($type == 2) {
           $table="dealer_product";
            $filedoc = "";
          if (!empty($_FILES['profilepic']['name'])) {
                $rename_name = uniqid(); 
                $arr_file_info = pathinfo($_FILES['profilepic']['name']);
                $file_extension = $arr_file_info['extension'];
                $newname = $rename_name . '.' . $file_extension;
                $old_name = $_FILES['profilepic']['name'];
               $path = "uploads/products/img/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
              $upload_type = "pdf|doc|zip|jpg|png|Jpeg";
              $filedoc = $this->Md_database->uploadFile($path, $upload_type, "profilepic", "", $newname);
                if(file_exists($path))
                {
                  if (!empty($this->input->post('oldfile'))) {
                    unlink(FCPATH . 'uploads/products/img/' . $this->input->post('oldfile'));
                }
             }
            } 
            $filedoc = !empty($filedoc) ? $filedoc : $this->input->post('oldfile');
            $updated_data = array(
                  'product_name'=> $product_name,
                  'img' =>$filedoc,
                  'mrp'=> $mrp,
                  'cost'=> $offer_price,
                  'category'=> $category,
                  'type'=> $type ,
                  'updatedDate' => date('Y-m-d H:i:s')                                 
              );    
            $condition = array("dealer_id" => $txtid,'type'=>$type);                    
            $result = $this->Md_database->updateData($table, $updated_data,$condition);       
            $resultarray = array('error_code' => '1', 'message' => ' data updated  successfully');
                echo json_encode($resultarray);
                exit();
          }
      }
}else {        
           	$resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                     	
            }     

}

function view_products_list(){
      $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
      $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
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
         $table = "dealer_product";
         $orderby = 'pk_id asc';
         $condition = array('status' => '1','type' =>$type);
         $col = array('img','product_name','brand_name','category','mrp','cost','description','status','createdDate','updatedBy','updatedDate');
         $getDealer = $this->Md_database->getData($table, $col, $condition, $orderby, '');
         $table = "dealer_product";
         $orderby = 'pk_id asc';
         $condition = array('status' => '1','type' =>$type);
         $col = array('img','product_name','brand_name','category','mrp','cost','description','status','createdDate','updatedBy','updatedDate');
         $getUsed = $this->Md_database->getData($table, $col, $condition, $orderby, '');
      $resultarray = array('error_code' => '1','getDealer'=> $getDealer,'getUsed'=>$getUsed,'message'=>'get data successfully');
        echo json_encode($resultarray);
         exit(); 
    }else {
            	$resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                     	
            }    
}

function delete_products_list(){
     $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
     $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';    
     if (!empty($uid)) {
	  	      $table = "dealer_product";
        	  $condition = array('pk_id' => $uid , 'type' => $type);
              $col = array('status'=>'3');
              $checkUser = $this->Md_database->updateData($table, $col, $condition);
              if (!empty($checkUser)) {
                $resultarray = array('error_code' => '3', 'message' => 'User is Deleted. Please contact to ' . SITE_TITLE);
                echo json_encode($resultarray);
                exit();
               }           
   }else {
            	$resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
                    echo json_encode($resultarray);
                    exit();                     	
            }  
       }

}//end Controller 