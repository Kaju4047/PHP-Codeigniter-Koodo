<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_products_list extends CI_Controller {
    function __construct() {
        parent::__construct();

    }
    public function addProductsList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $insert_id = !empty($this->input->post('insert_id')) ? $this->input->post('insert_id') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
        $product_name = !empty($this->input->post('product_name')) ? $this->input->post('product_name') : '';
        $profilepic = !empty($this->input->post('profilepic')) ? $this->input->post('profilepic') : '';
        $mrp = !empty($this->input->post('mrp')) ? $this->input->post('mrp') : '';
        $offer_price = !empty($this->input->post('offer_price')) ? $this->input->post('offer_price') : NULL;
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
            if(empty($insert_id)){                 
                if((empty($product_name) || empty($mrp) || empty($category)  || empty($type))) {
                    $resultarray = array('error_code' => '4', 'message' => 'product_name or  or mrp  or category or type is empty');
                    echo json_encode($resultarray);
                    exit();
                }else{   
                    $table="dealer_product";
                    $inserted_data = array(
                        'type' =>$type,
                        'product_name'=> $product_name,
                        'mrp'=> $mrp,
                        'cost'=> $offer_price,
                        'category'=> $category,
                        'brand_name'=>$brand_name,  
                        'description'=>$description,
                        'status'=>'1',
                        'dealer_id' =>$uid,
                        'createdBy' => $uid,
                        'createdDate' => date('Y-m-d H:i:s'),                
                        'created_ip_address' => $_SERVER['REMOTE_ADDR']
                    ); 
          
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
                        $inserted_data['img']=$filedoc;                   
                    } 
                    
                    $resultarray = $this->Md_database->insertData($table, $inserted_data);
                    $insert_id = $this->db->insert_id(); 
                    if (!empty($insert_id)) {
                    //admin notification
                        $table = "user";
                        $orderby = 'pk_id asc';
                        $condition = array('status' => '1', 'pk_id' => $uid);
                        $col = array('pk_id','name');
                        $userName = $this->Md_database->getData($table, $col, $condition, $orderby, '');

                        $table = "admin_notifications";
                        $insert_data = array(
                            'notifications'=> $userName[0]['name'].' added product as name '.$product_name.'.', 
                            'status' => '1',
                            'createdBy' => $uid,
                            'createdDate' => date('Y-m-d H:i:s'),                
                            'created_ip_address' => $_SERVER['REMOTE_ADDR']               
                        );
                        $resultarray = $this->Md_database->insertData($table, $insert_data);
                    }

                    $resultarray = array('error_code' => '1','insert_id'=>$insert_id ,'message' => ' data inserted  successfully');
                    echo json_encode($resultarray);
                    exit();                     	
                } 
            }else{                                  
                $table="dealer_product";
                $updated_data = array(
                    'product_name'=> $product_name,
                    'mrp'=>$mrp,
                    'cost'=>$offer_price,
                    'category'=>$category,
                    'type'=> $type,
                    'updatedBy' => $uid, 
                    'updatedDate' => date('Y-m-d H:i:s'),
                    'updated_ip_address' => $_SERVER['REMOTE_ADDR']                
                );
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
                    $upload_type = "jpg|png|jpeg";
                    $filedoc = $this->Md_database->uploadFile($path, $upload_type, "profilepic", "", $newname); 
                     $olddoc=!empty($this->input->post('fileold')) ? $this->input->post('fileold') : '';
                    $file='uploads/products/img/'.$olddoc;
                    if(is_file($file)){
                        unlink($file); // delete file
                    }
                $updated_data['img']=$filedoc;    
                }
                $condition = array("pk_id" => $insert_id,'type'=>$type,'dealer_id'=>$uid);
                $result = $this->Md_database->updateData($table, $updated_data,$condition);
                if (!empty($result)) {
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

    public function deleteProductsList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';    
        $insert_id = !empty($this->input->post('insert_id')) ? $this->input->post('insert_id') : '';    
        if (!empty($uid) ){
	  	    $table = "dealer_product";
            $condition = array('pk_id' => $insert_id,'dealer_id'=>$uid , 'type' => $type);
            $col = array(
                'status'=>'3', 
                'updatedBy' => $uid, 
                'updatedDate' => date('Y-m-d H:i:s'),
                'updated_ip_address' => $_SERVER['REMOTE_ADDR']);

            $checkUser = $this->Md_database->updateData($table, $col, $condition);
            if (!empty($checkUser)){
                $resultarray = array('error_code' => '1', 'message' => 'product is Deleted');
                echo json_encode($resultarray);
                exit();
            }           
        }else{
            $resultarray = array('error_code' => '2', 'message' => 'Uid  or type or insert_id is empty');
            echo json_encode($resultarray);
            exit();                     	
        }  
    }

  //List of all dealer product List
    public  function viewProductsList(){
        $uid = !empty($this->input->post('uid')) ? $this->input->post('uid') : '';
        $type = !empty($this->input->post('type')) ? $this->input->post('type') : '';
        $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : '';
    
        $category_id = !empty($this->input->post('category_id')) ? $this->input->post('category_id') : '';
        $limit = !empty($this->input->post('limit')) ? $this->input->post('limit') : '';
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : '';
        $search = !empty($this->input->post('search')) ? $this->input->post('search') : '';
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
            if ($type=='1'){
                $getDealer=array();
                $table = "dealer_product";
                $orderby = 'dealer_product.pk_id desc';
                $condition = array(
                    'dealer_product.status' => '1',
                    'category'=>$category_id,
                    'dealer_product.type' =>'1',
                    // 'dealer_id'=>$user_id,
                    'user.status' => '1'
                );
                if (!empty($user_id)) {
                    $this->db->where('dealer_id' ,$user_id);
                    // 'dealer_id'=>$user_id,
                }else{
                    $this->db->where('dealer_id!=' ,$uid);

                }
                $col = array('dealer_product.img','product_name','brand_name','sport.sportname','mrp','cost','description','dealer_product.pk_id,category,dealer_id');
                $this->db->limit($limit, $offset);
                $this->db->join('sport', 'dealer_product.category = sport.pk_id');
                $this->db->join('user', 'user.pk_id = dealer_product.dealer_id');

                if (!empty($search)) {
                    // $this->db->like('dealer_product.product_name',$search); 
                    $this->db->where("dealer_product.product_name LIKE '%$search%'");  
                }
                $getDealer = $this->Md_database->getData($table, $col, $condition, $orderby, '' ); 
                $resultarray = array('error_code' => '1','path' => base_url().'uploads/products/img/','getDealer'=> $getDealer,'message'=>'get data successfully');
                echo json_encode($resultarray);
                exit();                 
            }elseif($type=='2'){

                $getUsed=array();
                $table = "dealer_product";
                $orderby = 'dealer_product.pk_id desc';
                $condition = array(
                    'dealer_product.status' => '1',
                    'category'=>$category_id,
                    'dealer_product.type' =>'2',
                    'user.status' => '1'
                );
                if (!empty($user_id)) {
                    $this->db->where('dealer_id' ,$user_id);
                    // 'dealer_id'=>$user_id,
                }else{
                    $this->db->where('dealer_id!=' ,$uid);

                }
                $this->db->join('sport', 'dealer_product.category = sport.pk_id');
                $this->db->join('user', 'user.pk_id = dealer_product.dealer_id');
                $col = array('dealer_product.img','product_name','brand_name','sport.sportname','mrp','cost','description','dealer_product.pk_id,category,dealer_id');
                $this->db->limit($limit, $offset);
                if (!empty($search)){
                    // $this->db->like('dealer_product.product_name',$search); 
                    $this->db->where("dealer_product.product_name LIKE '%$search%'");  
                }
                $getUsed = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                foreach ($getUsed as $key => $value) {
                    $id = $value['dealer_id'];

                    $table = "user";
                    $orderby = 'user.pk_id asc';
                    $condition = array('user.pk_id' => $id);
                    $this->db->join('buy_subscription','user.pk_id = buy_subscription.user_id','LEFT');
                    if (!empty($sportid)) {
                        $this->db->where('fk_sport',$sportid);
                    }
                    $col = array('user.pk_id','latitude','longitude','online_status','doc_verify','COALESCE(GROUP_CONCAT(DISTINCT koodo_buy_subscription.category),"") as category','email','address');
                    $latlong = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['online_status']=$latlong[0]['online_status'];
                    $value['doc_verify']=$latlong[0]['doc_verify'];
                    $value['category']=$latlong[0]['category'];
                    $value['email']=$latlong[0]['email'];
                    $value['address']=$latlong[0]['address'];

                    $table = "user";
                    $orderby = 'pk_id asc';
                    $condition = array('pk_id' => $uid);
                    $col = array('pk_id','latitude','longitude');
                    $latlong_from = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                  
                    $latitudeFrom =  !empty($latlong_from[0]['latitude'])?$latlong_from[0]['latitude']:'';
                    $longitudeFrom = !empty($latlong_from[0]['longitude'])?$latlong_from[0]['longitude']:'';

                    $latitudeTo = !empty($latlong[0]['latitude'])?$latlong[0]['latitude']:'';
                    $longitudeTo = !empty($latlong[0]['longitude'])? $latlong[0]['longitude']:'';

                    //Calculate distance from latitude and longitude
                    $theta = $longitudeFrom - $longitudeTo;
                    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;

                    $distance = ($miles * 1.609344);
                    $value['distance'] =!empty(round($distance,2))?(round($distance,2)):'0';

                    $table = "friends_favourite";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('favourite_status');
                    $favourite_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['favourite_status'] =!empty($favourite_status[0]['favourite_status'])?$favourite_status[0]['favourite_status']:'2';

                    $table = "privileges_notifications";
                    $orderby = 'pk_id DESC';
                    $condition = array('fk_uid' => $id);
                    $col = array('display_profile','available','notifications','chat_notification','location');
                    $setting_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['available_status'] =!empty($setting_status[0]['available'])?$setting_status[0]['available']:'';
                    $value['chat_notification_status'] =!empty($setting_status[0]['chat_notification'])?$setting_status[0]['chat_notification']:'';
                    $value['location_status'] =!empty($setting_status[0]['location'])?$setting_status[0]['location']:'';

                    $table = "friends";
                    $orderby = 'pk_id DESC';
                    $condition = array('user_id' => $id,'uid'=>$uid);
                    $col = array('request_status');
                    $request_status = $this->Md_database->getData($table, $col, $condition, $orderby, '');
                    $value['request_status'] =!empty($request_status[0]['request_status'])?$request_status[0]['request_status']:'2';

                   
                    $new_array[] = $value;  
                }

                // print_r($)

                $resultarray = array('error_code' => '1','path' => base_url().'uploads/products/img/','getUsed'=>!empty($new_array)?$new_array:[],'message'=>'get data successfully');
                echo json_encode($resultarray);
               exit(); 
            }      
        }else{
          $resultarray = array('error_code' => '2', 'message' => 'Uid is empty');
          echo json_encode($resultarray);
          exit();                       
        }    
    }
}