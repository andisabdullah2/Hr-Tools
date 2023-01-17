<?php

class M_periode  extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    // get total tahun
    function get_total_tahun() {
        $sql = "SELECT COUNT(tahun_index) AS total 
                FROM jurnal_tahun a";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }
    
    // get list kontrak project data
    function get_all_tahun_data($params) {
        $sql = "SELECT *
                FROM jurnal_tahun 
                ORDER BY tahun_index DESC
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get list termin data
    function get_all_jurnal_periode($params) {
        $sql = "SELECT a.* FROM jurnal_periode a
                INNER JOIN jurnal_tahun b ON b.tahun_index=a.tahun_index
                WHERE a.tahun_index = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }       
    
    // get detail tahun
    function get_detail_tahun($params) {
        $sql = "SELECT * FROM jurnal_tahun a WHERE tahun_index = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // insert tahun
    function insert($params) {
        return $this->db->insert('jurnal_tahun', $params);
    }
    
    // insert tahun
    function insert_periode($params) {
        return $this->db->insert('jurnal_periode', $params);
    }    

    // update tahun
    function update($params, $where) {
       return $this->db->update('jurnal_tahun', $params, $where);
    }
    
    // update periode
    function update_periode($params, $where) {
       return $this->db->update('jurnal_periode', $params, $where);
    }
    
    // delete tahun
    function delete($params) {
        return $this->db->delete('jurnal_tahun', $params);
    }    
    
    // delete periode
    function delete_periode($params) {
        return $this->db->delete('jurnal_periode', $params);
    }      
    
    // check if tahun exists
    function check_if_tahun_exists($params) {
        $sql = "SELECT tahun_index FROM jurnal_tahun 
                WHERE tahun_index = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }    

    // get tahun aktif
    function get_tahun_aktif() {
        $sql = "SELECT tahun_index
                FROM jurnal_tahun
                WHERE tahun_default = 'yes'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    } 

    // check if periode exists
    function get_periode_detail($params) {
        $sql = "SELECT * FROM jurnal_periode 
                WHERE periode_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    
    
    // get tahun periode latest
    function get_tahun_latest() {
        $sql = "SELECT tahun_index FROM jurnal_tahun 
                ORDER BY tahun_index DESC
                LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['tahun_index']+1;
        } else {
            return array();
        }
    }    
    
    // get new periode_id
    function get_new_periode_id() {
        $sql = "SELECT periode_id FROM jurnal_periode ORDER BY periode_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $periode_id = $result['periode_id'] + 1;
            $periode_id = str_pad((string)$periode_id, 5, '0', STR_PAD_LEFT);
            return $periode_id;
        } else {
            return '00001';
        }
    }    
}