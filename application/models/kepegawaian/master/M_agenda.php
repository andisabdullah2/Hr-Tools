<?php

class M_agenda extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    //get total agenda presensi
    function get_total_agenda($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_agenda 
                WHERE judul LIKE ? ";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $query->free_result();
                return $result['total'];
        } else {
                return array();
        }
    }

    // get data agenda presensi limit
    function get_agenda_limit($params) {
        $sql = "SELECT * FROM data_agenda 
                WHERE judul LIKE ?                    
                ORDER BY judul ASC 
                LIMIT ?, ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail agenda presensi
    function get_detail_agenda_by_id($params) {
        $sql = "SELECT * FROM data_agenda
                WHERE agenda_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list agenda
    function get_list_agenda() {
        $sql = "SELECT * FROM data_agenda                 
                ORDER BY judul ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    // generate id agenda presensi
    public function generate_agenda_id() {
        // cari id     
        $sql = "SELECT RIGHT(agenda_id,2) AS last_number FROM data_agenda ORDER BY agenda_id DESC LIMIT 1 ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 99999) {
                return false;
            }
        } else {
            $nomor = '01';
        }
        if (strlen($nomor) == 1) {
            $nomor = "0" . $nomor;
        }
        return $nomor;
    }        
    
    // insert mesin presensi
    function insert_agenda($params) {                                
        return $this->db->insert('data_agenda', $params);
    }    

    // update mesin presensi
    function update_agenda($params, $where) {
        return $this->db->update('data_agenda', $params, $where);
    }
    
    // delete mesin presensi
    function delete_agenda($params) {
        return $this->db->delete('data_agenda', $params);
    }       

}
