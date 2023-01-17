<?php

class M_jabatan_struktural extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */
     
    //get total jabatan
    function get_total_jabatan_struktural($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_jabatan_struktural WHERE struktur_cd LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return null;
        }
    }

    // get data jabatan limit
    function get_data_jabatan_struktural_limit($params) {
        $sql = "SELECT b.*,a.struktur_nama FROM data_jabatan_struktural b
                INNER JOIN data_struktur_organisasi a ON b.struktur_cd=a.struktur_cd
                WHERE b.struktur_cd LIKE ?                
                ORDER BY b.jabatan_nama ASC
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
     
    // insert jabatan
    function insert_jabatan_struktural($params) {
        return $this->db->insert('data_jabatan_struktural', $params);        
    }

    // update jabatan
    function update_jabatan($params, $where) {
        return $this->db->update('data_jabatan_struktural', $params, $where);
    }

    // delete jabatan
    function delete_jabatan_struktural($params) {
        return $this->db->delete('data_jabatan_struktural', $params);        
    }
     
    /* UTILITY
     * 
     */    
    
    // create jadwal id
    function generate_jabatan_struktural_id($struktur_cd) {
        // cari id terakhir
        $sql = "SELECT RIGHT(jabatan_struktural_id, 2) AS last_number 
                FROM `data_jabatan_struktural` WHERE jabatan_struktural_id LIKE '%$struktur_cd%' 
                ORDER BY jabatan_struktural_id DESC LIMIT 1";
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
        return $struktur_cd . "." . $nomor;
    }

    // get all unit kerja
    function get_all_unit_kerja() {
        $sql = "SELECT * FROM data_struktur_organisasi ORDER BY struktur_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get department by id
    function get_detail_jabatan_by_id($params) {
        $sql = "SELECT a.*,b.struktur_nama FROM data_jabatan_struktural a 
                INNER JOIN data_struktur_organisasi b ON a.struktur_cd=b.struktur_cd
                WHERE a.jabatan_struktural_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
}
