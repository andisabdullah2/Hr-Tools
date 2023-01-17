<?php

class m_lokasi_presensi extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    //get total lokasi presensi
    function get_total_lokasi_presensi($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_lokasi_presensi 
                WHERE nama LIKE ? ";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $query->free_result();
                return $result['total'];
        } else {
                return array();
        }
    }

    // get data lokasi presensi limit
    function get_lokasi_presensi_limit($params) {
        $sql = "SELECT * FROM data_lokasi_presensi 
                WHERE nama LIKE ?                    
                ORDER BY nama ASC 
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

    // get detail lokasi presensi
    function get_detail_lokasi_presensi_by_id($params) {
        $sql = "SELECT * FROM data_lokasi_presensi
                WHERE lokasi_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list lokasi
    function get_list_lokasi() {
        $sql = "SELECT * FROM data_lokasi_presensi                 
                ORDER BY nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    // generate id lokasi presensi
    public function generate_lokasi_presensi_id() {
        // cari id     
        $sql = "SELECT RIGHT(lokasi_id,2) AS last_number FROM data_lokasi_presensi ORDER BY lokasi_id DESC LIMIT 1 ";
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
    function insert_lokasi_presensi($params) {                                
        return $this->db->insert('data_lokasi_presensi', $params);
    }    

    // update mesin presensi
    function update_lokasi_presensi($params, $where) {
        return $this->db->update('data_lokasi_presensi', $params, $where);
    }
    
    // delete mesin presensi
    function delete_lokasi_presensi($params) {
        return $this->db->delete('data_lokasi_presensi', $params);
    }       

}
