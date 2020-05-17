<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_login extends CI_Controller {

    public function index() {
        (empty($this->session->userdata['UID'])) ? '' : redirect(base_url() . 'admin/dashboard');

        $this->load->view('admin/login/vw_loginpage');
    }

    public function login_action() {

        if (!empty($this->input->post())) {

            //Post Data
            $user_email = ($this->input->POST('txtUserName'));
            $user_pw = ($this->input->POST('txtPassword'));
            $this->form_validation->set_rules('txtUserName', 'User Name', 'required|trim');
            $this->form_validation->set_rules('txtPassword', 'Password', 'required|trim');
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect(base_url() . 'login');
                exit();
            } else {
                $condition = array('UA_email' => $user_email, 'UA_password' => base64_encode($user_pw), 'UA_status <>' => '3');
                $user_details = $this->Md_database->getData('useradmin', '*', $condition, '', '');

                if (!empty($user_details)) {
                    $user_details = $user_details[0];
                    if ($user_details['UA_status'] == '1') {
                        $this->session->set_userdata('UID', $user_details['UA_pkey']);
                        $this->session->set_userdata('UPD', $user_pw);
                        $this->session->set_userdata('UNAME', $user_details['UA_Name']);
                        $this->session->set_userdata('UMAIL', $user_details['UA_email']);
                        $this->session->set_userdata('UTYPE', $user_details['UA_userType']);


                        if ($this->input->POST('remember') == "yes") {
                            setcookie('cok_Email', $user_email, time() + (86400 * 30));
                            setcookie('cok_Password', $user_pw, time() + (86400 * 30));
                        } else {
                            setcookie('cok_Email', $user_email, time() - (86400 * 30));
                            setcookie('cok_Password', $user_pw, time() - (86400 * 30));
                        }


                        $this->session->set_flashdata('success', 'You are logged in successfully.');

                        redirect(base_url() . 'admin/dashboard');
                    } else if ($user_details['UA_status'] == '2') {
                        $this->session->set_flashdata('error', 'User inactive. Please contact admin.');
                        redirect(base_url() . 'admin/login');
                    }
                } else {
                    //Email id or password not match:
                    /* @ Redirect */
                    $this->session->set_flashdata('error', 'Please enter valid credentials to login.');
                    redirect(base_url() . 'admin/login');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Something goes wrong.');
            redirect(base_url() . 'admin/login');
        }
        redirect(base_url() . 'admin/login');
    }

    public function logout() {
        $this->session->unset_userdata('UID');
        $this->session->unset_userdata('UPD');
        $this->session->unset_userdata('UNAME');
        $this->session->unset_userdata('UMAIL');
        $this->session->unset_userdata('UTYPE');


        $this->session->set_flashdata('success', 'You are logged out successfully.');
        redirect(base_url() . 'admin/login');
    }

}
