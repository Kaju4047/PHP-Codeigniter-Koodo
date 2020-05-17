<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_forgotpsw extends CI_Controller {

    public function index() {
        (empty($this->session->userdata['UID'])) ? '' : redirect(base_url() . 'admin/dashboard');

        $this->load->view('admin/forgotpsw/vw_forgot_password');
    }

    public function forget_password_action() {
        //Post Data
        $fotgotemail = $this->input->POST('email');

        if (!empty($this->input->POST('email'))) {
            // SQL:: Get Admin User Details
            $table = "useradmin";

            $condition = array('UA_email' => $fotgotemail, 'ua_UserType <>' => 'superAdmin', 'UA_status' => '1');
            $user_details = $this->Md_database->getData($table, '*', $condition, '', '');

            if (!empty($user_details)) {
                $token = substr((uniqid(rand(), true)), 0, 6);
//                    $token = '123';
                $password = PASSWORD_PREFIX . $token;

                //$password= $pass;
                //SQL:: Update db
                $table = 'useradmin';
                $data = array("UA_password" => base64_encode($password));
                $condition = array("UA_email" => $fotgotemail);
                $this->Md_database->updateData($table, $data, $condition);

                /* [start::send forgot mail] */
                $recipeinets = $this->input->POST('email');
                $from = array(
                    "email" => SITE_MAIL,
                    "name" => SITE_TITLE
                );
                $reserved_words = array(
                    "||USER_NAME||" => '',
                    "||SITE_TITLE||" => SITE_TITLE,
                    "||EMAIL_ID||" => $fotgotemail,
                    "||PASSWORD||" => $password,
                    "||YEAR||" => date('Y'),
                );
                $email_data = $this->Md_database->getEmailInfo('forgot_password', $reserved_words);
                $subject = SITE_TITLE . '-' . !empty($email_data['subject']) ? $email_data['subject'] : "";


                $ml = $this->Md_database->sendEmail($recipeinets, $from, $subject, $email_data['content']);
                /* [end::send forgot mail] */
                //SQL:: update your password:-
                /* @ Redirect */
                if (!empty($ml)) {
                    $this->session->set_flashdata('success', ' Your temporary password has been sent to your email successfully.');
                    redirect(base_url() . 'admin/login');
                } else {
                    $this->session->set_flashdata('error', "Your temporary password sending failed.");
                    redirect(base_url() . 'admin/login');
                }
            } else {
                //Email id or password not match:
                /* @ Redirect */
                $this->session->set_flashdata('error', 'Please enter correct email.');
                redirect(base_url() . 'admin/forgot');
            }
        } else {
            /* @ Redirect */
            $this->session->set_flashdata('error', 'We got wrong response to proceed your request.');
            redirect(base_url() . 'admin/forgot');
        }
    }

//end of adminForgotPasswordAction()
}
