<?php

class Md_sub_user extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* [start]::Sub user data */

    public function getSubUserCount($search_term = "") {
        $this->db->select('UA_pkey');

        $this->db->from('useradmin');

        $this->db->where('UA_userType', "subAdmin");
        $this->db->where('UA_status !=', "3");

        if (!empty($search_term)) {
            $this->db->group_start();
            $this->db->where("UA_Name LIKE '%$search_term%'");
            $this->db->or_where("UA_email LIKE '%$search_term%'");
            $this->db->or_where("UA_mobile LIKE '%$search_term%'");
            $this->db->or_where("UA_City LIKE '%$search_term%'");
            $this->db->group_end();
        }

        $query = $this->db->get();
        $Array = $query->num_rows();
        return $Array;
    }

    public function getSubUserDtls($search_term = "", $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select('*');

        $this->db->from('useradmin');

        $this->db->where('UA_userType', "subAdmin");
        $this->db->where('UA_status !=', "3");

        if (!empty($search_term)) {
            $this->db->group_start();
            $this->db->where("UA_Name LIKE '%$search_term%'");
            $this->db->or_where("UA_email LIKE '%$search_term%'");
            $this->db->or_where("UA_mobile LIKE '%$search_term%'");
            $this->db->or_where("UA_City LIKE '%$search_term%'");
            $this->db->group_end();
        }

        $this->db->order_by('UA_pkey desc');

        $query = $this->db->get();
        $Array = $query->result_array();
        return $Array;
    }

    /* [end]::Sub user data */
}

?>
