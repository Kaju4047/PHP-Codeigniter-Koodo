<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_subuser extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/sub_user/Md_sub_user');
        $this->load->library('pagination');
    }

    public function index() {
        $data = array();
        $data['title'] = "Sub User";

        /* [start]::sub user data */

        $search_term = !empty($this->input->get('search_term')) ? $this->input->get('search_term') : '';

        $params = array();
        $params['links'] = array();
        $params['results'] = array();

        $limit_per_page = 10;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) - 1) : 0;
        $total_records = "";
        $total_records = $this->Md_sub_user->getSubUserCount($search_term);

        if ($total_records > 0) {
            $params["results"] = $this->Md_sub_user->getSubUserDtls($search_term, $limit_per_page, $page * $limit_per_page);
            $config['base_url'] = base_url() . 'admin/sub-user';
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

        $data['cmpList_links'] = $params['links'];
        $data['cmpList'] = $params['results'];

        /* [end]::sub user data */

        $data['uri_page_no'] = !empty($this->uri->segment(3)) ? $this->uri->segment(3) : '';
        $this->load->view('admin/subuser/vw_sub_user', $data);
    }

    public function subuser($id = '') {
        if (!empty($id)) {
            $table = "useradmin";
            $fields = array('*');
            $condition = array("UA_pkey" => $id);
            $order = 'UA_pkey ASC';
            $data['cmpList'] = $this->Md_database->getData($table, $fields, $condition, $order, '');
        }
        $data['title'] = "Sub User";
        $this->load->view('admin/subuser/vw_view_sub_user', $data);
    }

    public function addSubUser($id = '') {
        //print_r($id);die;
        $editData = "";
        $editserData = "";
        $editcycData = "";
        $ediCycleImg = "";
        $Slist = [];
        if (!empty($id)) {
            $condition = array("UA_pkey" => $id);
            $fld = ('*');
            $editData = $this->Md_database->getData('useradmin', '*', $condition);
            $editData = !empty($editData[0]) ? $editData[0] : '';
            $cycId = !empty($editData['txtCycle']) ? $editData['txtCycle'] : "";

            if (empty($editData)) {
                $this->session->set_flashdata('error', ' Something goes wrong.');
                redirect(base_url() . 'admin/sub-user');
            }
        }
        $data['editData'] = $editData;
        $data['title'] = "Sub User";
        $this->load->view('admin/subuser/vw_add_sub_user', $data);
    }

    public function action() {
//        echo '<pre>';
//        print_r($this->input->post());
//        print_r($_FILES);
//        die;
        if (!empty($this->input->post())) {

            $this->form_validation->set_rules('txtName', 'Name', 'trim|required');
            $this->form_validation->set_rules('txtEmail', 'email', 'trim|required');
            $this->form_validation->set_rules('txtPassword', 'email', 'trim|required');


            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                $priviliges = !empty($this->input->post('priviliges')) ? implode(',', $this->input->post('priviliges')) : "";
                $pkey = '';
                $inserted_data = array(
                    'UA_userType' => 'subAdmin',
                    'UA_Name' => $this->input->post('txtName'),
                    'UA_email' => !empty($this->input->post('txtEmail')) ? $this->input->post('txtEmail') : "",
                    'UA_mobile' => !empty($this->input->post('txtMobile')) ? $this->input->post('txtMobile') : "",
                    'UA_Address' => !empty($this->input->post('txtAddress')) ? $this->input->post('txtAddress') : "",
                    'UA_City' => !empty($this->input->post('txtCity')) ? $this->input->post('txtCity') : "",
                    'UA_password' => !empty($this->input->post('txtPassword')) ? base64_encode($this->input->post('txtPassword')) : "",
                    'UA_priviliges' => $priviliges,
                );


                if (empty($this->input->post('txtPkey'))) {
                    $token = substr((uniqid(rand(), true)), 0, 6);
                    $password = $this->input->post('txtPassword');
                    $inserted_data['UA_createdBy'] = $this->session->userdata['UID'];
                    $ret = $this->Md_database->insertData('useradmin', $inserted_data);
                    $pkey = $ret;
                    $email = !empty($this->input->post('txtEmail')) ? $this->input->post('txtEmail') : "";
//                    $this->serMail($email, $password);

                    /* [start::send forgot mail] */
                    $recipeinets = $email;
                    $from = array(
                        "email" => SITE_MAIL,
                        "name" => SITE_TITLE
                    );
                    $reserved_words = array(
                        "||USER_NAME||" => $this->input->post('txtName'),
                        "||SITE_TITLE||" => SITE_TITLE,
                        "||EMAIL_ID||" => $email,
                        "||PASSWORD||" => $password,
                        "||YEAR||" => date('Y'),
                    );
                    $email_data = $this->Md_database->getEmailInfo('registration', $reserved_words);
                    $subject = SITE_TITLE . '-' . !empty($email_data['subject']) ? $email_data['subject'] : "";

                    $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']);
                    /* [end::send forgot mail] */

                    $actionMsg = 'saved';
                } else {

                    $password = $this->input->post('txtPassword');
                    $pkey = $this->input->post('txtPkey');
                    $email = !empty($this->input->post('txtEmail')) ? $this->input->post('txtEmail') : "";
                    /* [start::send forgot mail] */
                    if (!empty($pkey)) {
                        $table = "useradmin";
                        $fields = array('*');
                        $condition = array("UA_pkey" => $pkey
                            , 'UA_email' => !empty($this->input->post('txtEmail')) ? $this->input->post('txtEmail') : ""
                            , 'UA_password' => !empty($this->input->post('txtPassword')) ? base64_encode($this->input->post('txtPassword')) : ""
                        );
                        $order = 'UA_pkey ASC';
                        $retData = $this->Md_database->getData($table, $fields, $condition, $order, '');

                        if (empty($retData)) {
                            $recipeinets = $email;
                            $from = array(
                                "email" => SITE_MAIL,
                                "name" => SITE_TITLE
                            );
                            $reserved_words = array(
                                "||USER_NAME||" => $this->input->post('txtName'),
                                "||SITE_TITLE||" => SITE_TITLE,
                                "||EMAIL_ID||" => $email,
                                "||PASSWORD||" => $password,
                                "||YEAR||" => date('Y'),
                            );
                            $email_data = $this->Md_database->getEmailInfo('registration', $reserved_words);
                            $subject = SITE_TITLE . '-' . !empty($email_data['subject']) ? $email_data['subject'] : "";
                            $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']);
                            /* [end::send forgot mail] */
                        }
                    }

                    $inserted_data['UA_updatedBy'] = $this->session->userdata['UID'];


                    $condition = array("UA_pkey" => $pkey);
                    $ret = $this->Md_database->updateData('useradmin', $inserted_data, $condition);
                    $email = !empty($this->input->post('txtEmail')) ? $this->input->post('txtEmail') : "";
//                    $this->serMail($email, $password);
                    $actionMsg = 'updated';
                }
                /* [start::upload file] */

                if ($_FILES['fileCmpLogo']['size'] > 0) {  // profile picture upload
                    $path = "AdminMedia/upload/user/" . $pkey;

                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $upload_type = "jpg|png|jpeg";

                    $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "fileCmpLogo", "companyLogo", "companyLogo");

                    $update = '';
                    $update['UA_Image'] = $photoDoc;
                    $condition = array("UA_pkey" => $pkey);
                    $ret = $this->Md_database->updateData('useradmin', $update, $condition);
                }

                /* [end::upload file] */
                if (!empty($ret)) {
                    $this->session->set_flashdata('success', "User details $actionMsg successfully.");
                    redirect(base_url() . 'admin/sub-user');
                } else {
                    $this->session->set_flashdata('error', "User details $actionMsg failed, please try again.");
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Something goes wrong.');
            redirect(base_url() . 'admin/sub-user');
        }
    }

    public function serMail($fotgotemail, $password) {

        $recipeinets = $fotgotemail;
        $from = array(
            "email" => 'admin@inditech.com',
            "name" => 'Inditech'
        );
        $subject = 'Inditech-Login Details';
        $year = date('Y');
        $message = '<div  style="padding: 15px;border: 2px solid #813979;width: 100%;max-width: 600px;min-height:340px;margin: 0px auto;box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.61);">
                         <div style="border-bottom: 1px dashed #323232;height: 47px;margin: -10px;">
                                <h1 style="color:rgb(113, 112, 112);font-size:26px;float:left;margin: 0px;padding-top: 4px;">Inditech</h1>
                         </div>
                         <div style="clear:both"></div>
                             <h1 style="font-size: 14px;margin-top: 23px;color:#222">Welcome <span>  </span></h1>
                             <p style="font-size: 14px;margin-top: 30px;margin-bottom:15px;color:#222"> Your registration process has been done successfully.</p>

                              <p style="font-size: 14px;margin-bottom:5px;color:#222">Your login credentials for InditechT are :</p>
                              <p style="margin-bottom: 5px;font-size: 14px;margin-top:5px;color:#222">Username - ' . $fotgotemail . '</p>
                              <p style="margin-top: 5px;font-size: 14px;margin-bottom: 30px;">Password - ' . $password . '</p>
                              <h1 style="font-weight: 600;font-size: 14px;margin:0px;width:100%;margin-bottom: 5px;color:#222">Thanking You,</h1>
                              <p  style="font-size: 13px;margin:0px;color:#222">InditechT Administrator</p>

                              <div style="clear:both"></div>
                              <div style="height: 40px;background-color: #F5F5F5;text-align: center;margin: -15px;margin-top: 15px;padding:10px;">
                              <h1 style="font-size: 15px;margin-bottom: 6px;margin-top: 0px;color:#878787;">Inditech</h1>
                              <p style="margin: 0px;font-size: 12px;color:#878787"> © ' . $year . ' All Rights Reserved. </p>
                         </div>
                         <div style="clear:both"></div>
                             <h1 style="margin: 0px;font-size:14px;margin: 21px 0px 0px;font-size: 12px;color: #5A5858;"><span>Note:</span>This is an automated mail, please don' . "'" . 't reply.</h1>
                         </div>';
        $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $message);
    }

    public function delete($id, $page_no = "") {
        $condition = array("UA_pkey" => $id);
        $ret = $this->Md_database->deleteData('useradmin', $condition);
        $actionMsg = 'deleted';
        if (!empty($ret)) {
            $this->session->set_flashdata('success', "User details $actionMsg successfully.");
        } else {
            $this->session->set_flashdata('error', "User details $actionMsg failed, please try again.");
        }

        $search_term = "";
        $limit_per_page = 10;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $params["results"] = $this->Md_sub_user->getSubUserDtls($search_term, $limit_per_page, $page * $limit_per_page);
        $data['cmpList'] = $params['results'];

        $pre_page_no = $page_no;
        if (empty($data['cmpList'])) {
            $pre_page_no = $page_no - 1;
        }

        $redirect_to = "admin/sub-user";
        if (!empty($page_no)) {
            $redirect_to = "admin/sub-user/" . $pre_page_no;
        }
        redirect(base_url() . "" . $redirect_to);
    }

    public function check_email() {

        $id = $this->input->get('id');
        $txtEmail = $this->input->get('txtEmail');

        $condition['UA_email'] = $txtEmail;
        if (!empty($id)) {
            $condition['UA_pkey <>'] = $id;
        }
        $edi = $this->Md_database->getData('useradmin', '*', $condition);
        if (!empty($edi)) {
            echo 'false';
        } else {
            echo 'true';
        }
        die;
    }

    public function changeStatus($id, $status) {




        $condition = array("UA_pkey" => $id, 'UA_userType' => 'subAdmin');
        $upData = array('UA_status' => "$status");
        $ret = $this->Md_database->updateData('useradmin', $upData, $condition);

        $msg = ($status == 1) ? 'Activated' : 'Inactivated';

        if (!empty($ret)) {
            $this->session->set_flashdata('success', "User  $msg successfully.");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', "User  $actionMsg failed, please try again.");
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}
