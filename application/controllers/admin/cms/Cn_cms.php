<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cn_cms extends CI_Controller {

    function __construct() {
        parent::__construct(); //It calls the Parent constructor

        $this->load->model('M_cms');
    }

    public function cms() {
        $data['page_title'] = 'CMS';
        if (!empty($this->input->post())) {
            $this->form_validation->set_rules('cmsTitle', 'page name', 'trim|required');
            $this->form_validation->set_rules('description', 'content', 'trim|required');
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect(base_url() . 'admin/cms');
                exit();
            }else{

                $inserted_data = array(
                    'cms_text' => $this->input->post('description'),
                    'cms_meta_title' => $this->input->post('meta_title'),
                    'cms_meta_desc' => $this->input->post('meta_desc'),
                    'cms_meta_keyword' => $this->input->post('meta_keys'),
                );
                $condition = array('cms_pkey' => $this->input->post('cmsTitle'));
                $result = $this->Md_database->updateData('cms', $inserted_data, $condition);
                if (!empty($result)) {
                    $this->session->set_flashdata('success', 'CMS details saved successfully!!');
                } else {
                    $this->session->set_flashdata('error', 'CMS details not saved.');
                }
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            /* [start::get organization data] */
            $condn = array('cms_status' => '1');
            $cmsData = $this->Md_database->getData('cms', 'cms_pkey,cms_title', $condn);
            $data['cmsData'] = !empty($cmsData) ? $cmsData : '';
           /* [end::get organization data] */
            $this->load->view('admin/cms/vw_cms', $data);
        }
    }

    //getDataCMSById
    public function getDataCMSById() {

        $this->db->where('cms_pkey', $this->input->get('cmsId'));
        $this->db->where('cms_status', 1);
        $query = $this->db->get('cms');
        $result = $query->row();

        if (!empty($result)) {
            //echo $result->cms_text;
            $data['cms_text'] = $result->cms_text;
            $data['cms_meta_title'] = $result->cms_meta_title;
            $data['cms_meta_desc'] = $result->cms_meta_desc;
            $data['cms_meta_keyword'] = $result->cms_meta_keyword;
            //$data = $result->result();
            echo json_encode($data);
        }
        exit;
    }

    /* [start::code for upload local images at editor] */
    public function ImageUpload() {
        $folderPath = 'AdminMedia/editor/upload_images/'; //change pat only

        $url = FCPATH . '' . $folderPath . '' . $_FILES['upload']['name'];
        //extensive suitability check before doing anything with the file…
        if (($_FILES['upload'] == "none" OR ( empty($_FILES['upload']['name'])))) {
            $message = "No file uploaded.";
        } else if ($_FILES['upload']["size"] == 0) {
            $message = "The file is of zero length.";
        } else if (($_FILES['upload']["type"] != "image/pjpeg") AND ( $_FILES['upload']["type"] != "image/jpeg") AND ( $_FILES['upload']["type"] != "image/png")) {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
            $message = "ou may be attempting to hack our server. We’re on to you; expect a knock on the door sometime soon.";
        } else {
            $message = "";
            $move = @ move_uploaded_file($_FILES['upload']['tmp_name'], $url);
            if (!$move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
            $url = base_url() . '' . $folderPath . '' . $_FILES['upload']['name'];
        }
        $funcNum = $_GET['CKEditorFuncNum'];
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
        exit();
    }
    /* [end::code for upload local images at editor] */
}
