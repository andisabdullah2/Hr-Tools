<?php

class m_jabatan_fungsional extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */

    // get total jabatan
    function get_total_jabatan_fungsional($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_jabatan_fungsional WHERE jabatan_fungsional_id LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get data jabatan limit
    function get_jabatan_fungsional_limit($params) {
        $sql = "SELECT * FROM data_jabatan_fungsional
                WHERE jabatan_fungsional_id LIKE ?
                ORDER BY jabatan_nama ASC                
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

    // get detail jabatan by id
    function get_detail_jabatan_by_id($params) {
        $sql = "SELECT a.* FROM data_jabatan_fungsional a 
                WHERE a.jabatan_fungsional_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    // insert
    function insert_jabatan($params) {
        return $this->db->insert('data_jabatan_fungsional', $params);
    }

    // update
    function update_jabatan($params, $where) {
        return $this->db->update('data_jabatan_fungsional', $params, $where);
    }

    // delete
    function delete_jabatan_fungsional($params) {
        return $this->db->delete('data_jabatan_fungsional', $params);
    }

    /* UTILITY
     * 
     */     
    
    // generate id
    public function generate_jabatan_fungsional_id() {
        // cari id terakhir
        $sql = "SELECT jabatan_fungsional_id AS last_number 
                FROM `data_jabatan_fungsional`
                ORDER BY jabatan_fungsional_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            $nomor = str_pad($nomor, strlen($result['last_number']), '0', STR_PAD_LEFT);
        } else {
            $nomor = '00001';
        }
        return $nomor;
    }


}
