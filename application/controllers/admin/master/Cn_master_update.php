<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cn_master extends CI_Controller {

    public function sport() {
        $this->load->view('admin/master/vw_sport');
    }

    public function tax() {

        $this->load->view('admin/master/vw_tax');
    }

    public function city() {

        $this->load->view('admin/master/vw_city');
    }
    public function services() {

        $this->load->view('admin/master/vw_services');
    }

}
