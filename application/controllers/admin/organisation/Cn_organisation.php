<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_organisation extends CI_Controller {

    public function index() {
        /* [start::get organization data] */
        $condn = array('om_pkey' => '1', 'om_status' => '1');
        $orgData = $this->Md_database->getData('organizationmaster', '*', $condn);
        $data['orgData'] = !empty($orgData[0]) ? $orgData[0] : '';
        /* [end::get organization data] */
        $this->load->view('admin/organisation/vw_organisation_master', $data);
    }

    public function organization_master_action() {
        $this->form_validation->set_rules('txtCmpName', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('txtCmpEmail', 'Company email id', 'trim|required');
        // $this->form_validation->set_rules('txtCmpCity', 'Company City', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(base_url() . 'admin/organisation');
            exit();
        } else {
            $inserted_data = array(
                'om_CmpName' => $this->input->post('txtCmpName'),
                //'om_CmpType' => $this->input->post('selCmpType'),
                'om_CmpAddress' => $this->input->post('txtCmpAddress'),
                'om_CmpCity' => $this->input->post('txtCmpCity'),
                'om_CmpState' => $this->input->post('txtCmpState'),
                'om_CmpEmail' => $this->input->post('txtCmpEmail'),
                'om_supportEmail' => $this->input->post('txtsupportEmail'),
                //'om_MCountyCode' => $this->input->post('selMCountyCode'),
                'om_CmpMobile' => !empty($this->input->post('txtCmpMobile')) ? $this->input->post('txtCmpMobile') : '',
                //'om_PCountyCode' => $this->input->post('selPCountyCode'),
                'om_CmpPhone' => !empty($this->input->post('txtCmpPhone')) ? $this->input->post('txtCmpPhone') : '',
                'om_CmpWebsite' => $this->input->post('txtCmpWebsite'),
                //'om_CmpFaxCountyCode' => $this->input->post('selCmpFaxCountyCode'),
                //'om_CmpFaxNo' => !empty($this->input->post('txtCmpFaxNo')) ? $this->input->post('txtCmpFaxNo') : '',
                'om_CmpFBLink' => $this->input->post('txtCmpFBLink'),
                'om_CmpTwitterLink' => $this->input->post('txtCmpTwitterLink'),
                'om_CmpLinkedInLink' => $this->input->post('txtCmpLinkedInLink'),
                'om_CmpGoogleLink' => $this->input->post('txtCmpGoogleLink'),
                'om_mapUrl' => $this->input->post('om_mapUrl'),
                'om_created_by_id' => $this->session->userdata['UID'],
                'om_clientIP' => $_SERVER['REMOTE_ADDR']
            );
            /* [start::upload file] */

            if ($_FILES['fileCmpLogo']['size'] > 0) {  // profile picture upload
                $path = "AdminMedia/upload/OrgnizationLogo";

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $upload_type = "jpg|png|jpeg";

                $photoDoc = $this->Md_database->uploadFile($path, $upload_type, "fileCmpLogo", "companyLogo", "companyLogo");

                $inserted_data['om_LogoImage'] = $photoDoc;
            }
            /* [end::upload file] */

            $condition = array("om_pkey" => '1');
            $ret = $this->Md_database->updateData('organizationmaster', $inserted_data, $condition);
            $actionMsg = 'updated';

            if (!empty($ret)) {
                $this->session->set_flashdata('success', "Organization details $actionMsg successfully.");
                redirect(base_url() . 'admin/organisation');
            } else {
                $this->session->set_flashdata('error', "Organization details $actionMsg failed, please try again.");
                redirect(base_url() . 'admin/organisation');
            }
        }
    }

    /* [End::funcation for organization_master] */
}
