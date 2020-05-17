<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_setting extends CI_Controller {

    public function index() {

        $this->load->view('admin/setting/vw_password_change');
    }

    public function setting_action() {
        if (!empty($this->input->post())) {
            $this->form_validation->set_rules('txtOldPass', 'old password', 'trim|required');
            $this->form_validation->set_rules('txtNewPass', 'new password', 'trim|required');
            $this->form_validation->set_rules('txtNewConfrmPass', 'confirm password', 'trim|required');
            /* [start::check old password is valid or not] */
            if (!empty($this->input->post('txtOldPass'))) {
                if ($this->input->post('txtOldPass') != $this->session->userdata['UPD']) {
                    $this->session->set_flashdata('error', 'Old password is wrong');
                    redirect($_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            /* [end::check old password is valid or not] */
            /* [start::check old password and new is valid or not] */
            if (!empty($this->input->post('txtOldPass')) && !empty($this->input->post('txtNewPass'))) {
                if ($this->input->post('txtOldPass') == $this->input->post('txtNewPass')) {
                    $this->session->set_flashdata('error', 'Old and new password cannot not be same.');
                    redirect($_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            /* [end::check old password and new is valid or not] */
            /* [start::check conform password and new is valid or not] */
            if (!empty($this->input->post('txtNewConfrmPass')) && !empty($this->input->post('txtNewPass'))) {
                if ($this->input->post('txtNewConfrmPass') != $this->input->post('txtNewPass')) {
                    $this->session->set_flashdata('error', 'New and confirm password must be same');
                    redirect($_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            /* [end::check conform password and new is valid or not] */
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
                exit();
            } else {
                $update_data = array('UA_password' => base64_encode($this->input->post('txtNewConfrmPass')));
                $upCondn = array('UA_pkey' => $this->session->userdata['UID']);
                $ret = $this->Md_database->updateData('useradmin', $update_data, $upCondn); //update status
            }
        }

        if (!empty($ret)) {
            //unset user session data
            $this->session->unset_userdata('UID');
            $this->session->unset_userdata('UPD');
            $this->session->unset_userdata('UNAME');
            $this->session->unset_userdata('UMAIL');
            $this->session->unset_userdata('UTYPE');
            $this->session->set_flashdata('success', "Your password changed successfully. Please Login with new password.");
            redirect(base_url() . 'admin/login');
        }else {
            $this->session->set_flashdata('error', "Password change failed, please try again.");
            redirect(base_url() . 'admin/setting');
        }
    }

}
