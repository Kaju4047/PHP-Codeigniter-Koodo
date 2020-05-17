<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_Default extends CI_Controller {

    function __construct() {
        parent::__construct(); //It calls the Parent constructor
    }

    public function index() {
        redirect(base_url() . 'admin/login');
        exit();
    }

    public function sessionExpire() {
        $this->session->set_flashdata('error', 'Sorry..! Session Expired Please Login.');
        redirect(base_url() . 'admin/login');
    }






}
