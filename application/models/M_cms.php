<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_cms extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCMSByID($cmsPkey) {//get data from lj_cms by id
        $this->db->where('cms_pkey', $cmsPkey);
        $this->db->where('cms_status', 1);
        $query = $this->db->get('cms');
        return $query->row();
    }

//end of getCMSByID
//end of insert_cms
    /* End Here : php19 */
}

// END AdminModel of Class

