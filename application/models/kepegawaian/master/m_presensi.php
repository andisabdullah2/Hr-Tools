<?php

class m_presensi extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    //get total mesin presensi
    function get_total_mesin_presensi($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_mesin_presensi 
                WHERE mesin_ip LIKE ? AND mesin_lokasi LIKE ? ";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
                $result = $query->row_array();
                $query->free_result();
                return $result['total'];
        } else {
                return array();
        }
    }

    // get data mesin presensi limit
    function get_mesin_presensi_limit($params) {
        $sql = "SELECT * FROM data_mesin_presensi 
                WHERE mesin_ip LIKE ? AND mesin_lokasi LIKE ?                    
                ORDER BY mesin_ip ASC, mesin_lokasi ASC 
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

    // get detail presensi
    function get_detail_mesin_presensi_by_id($params) {
        $sql = "SELECT * FROM data_mesin_presensi
                WHERE mesin_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // generate id mesin presensi
    public function generate_mesin_presensi_id() {
        // cari id     
        $sql = "SELECT RIGHT(mesin_id,2) AS last_number FROM data_mesin_presensi ORDER BY mesin_id DESC LIMIT 1 ";
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
    function insert_mesin_presensi($params) {                                
        return $this->db->insert('data_mesin_presensi', $params);
    }    

    // update mesin presensi
    function update_mesin_presensi($params, $where) {
        return $this->db->update('data_mesin_presensi', $params, $where);
    }
    
    // delete mesin presensi
    function delete_mesin_presensi($params) {
        return $this->db->delete('data_mesin_presensi', $params);
    }       

}
