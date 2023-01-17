<?php

class m_kuota_cuti extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    
    function get_list_pegawai() {
        $sql = "SELECT user_id,nama_lengkap FROM pegawai
                WHERE pegawai_status = 'working'
                ORDER BY nama_lengkap ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    function get_list_jenis_cuti() {
        $sql = "SELECT jenis_id,jenis_cuti,jumlah_cuti_min,jumlah_cuti_max FROM data_jenis_cuti
                ORDER BY jenis_cuti ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    function get_min_max_jenis_cuti($params) {
        $sql = "SELECT jenis_id,jenis_cuti,jumlah_cuti_min,jumlah_cuti_max FROM data_jenis_cuti
                WHERE jenis_id = ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    
    
    function get_total_karyawan($params) {
        $sql = "SELECT count(*) AS total
                FROM pegawai_cuti_kuota d
                INNER JOIN pegawai a ON d.user_id = a.user_id
                INNER JOIN data_jenis_cuti e ON d.jenis_id = e.jenis_id
                WHERE a.nama_lengkap LIKE ? AND d.tahun LIKE ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    function get_all_karyawan_limit($params) {
        $sql = "SELECT d.user_id, a.nama_lengkap,e.jenis_cuti AS jenis,e.jenis_id,d.tahun,d.total
                FROM pegawai_cuti_kuota d
                INNER JOIN pegawai a ON d.user_id = a.user_id
                INNER JOIN data_jenis_cuti e ON d.jenis_id = e.jenis_id
                WHERE a.nama_lengkap LIKE ? AND d.tahun LIKE ?
                ORDER BY a.nama_lengkap ASC, d.tahun ASC 
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
    
    function insert_pegawai_kuota_cuti($params) {
        return $this->db->insert('pegawai_cuti_kuota', $params);
    }
    
    function get_detail_kuota_cuti_pegawai($params) {
        $sql = "SELECT d.user_id, a.nama_lengkap,e.jenis_cuti AS jenis,e.jenis_id,d.tahun,d.total
                FROM pegawai_cuti_kuota d
                INNER JOIN pegawai a ON d.user_id = a.user_id
                INNER JOIN data_jenis_cuti e ON d.jenis_id = e.jenis_id
                WHERE d.user_id = ? AND d.tahun LIKE ? AND d.jenis_id LIKE ?";   
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    function update_pegawai_cuti_kuota($params, $where) {
        return $this->db->update('pegawai_cuti_kuota', $params, $where);
    }    
    
    function delete_kuota_cuti_pegawai($params) {
        $sql = "DELETE FROM pegawai_cuti_kuota
		WHERE user_id = ? AND tahun = ? AND jenis_id LIKE ?";
        return $this->db->query($sql, $params);
    }    
    
}
