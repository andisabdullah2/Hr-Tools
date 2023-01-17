<?php

class M_status_karyawan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_list_jabatan() {
        $sql = "SELECT a.jabatan_struktural_id, a.jabatan_alias, a.jabatan_nama, b.struktur_nama, b.struktur_singkatan FROM data_jabatan_struktural a 
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                ORDER BY jabatan_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
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
    
    function get_total_karyawan($params) {
        $sql = "SELECT count(*) AS total
                FROM pegawai a
                INNER JOIN pegawai_status b ON a.user_id = b.user_id
                WHERE a.nama_lengkap LIKE ? AND b.pegawai_status LIKE ?";
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
        $sql = "SELECT a.user_id, a.nama_lengkap, b.tahun,b.pegawai_status,b.tanggal_keluar,b.catatan 
                FROM pegawai a
                INNER JOIN pegawai_status b ON a.user_id = b.user_id
                WHERE a.nama_lengkap LIKE ? AND b.pegawai_status LIKE ?
                ORDER BY a.nama_lengkap ASC 
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

    function get_detail_status_karyawan_by_id($params) {
        $sql = "SELECT a.user_id, a.nama_lengkap, b.*
                FROM pegawai a 
                INNER JOIN pegawai_status b ON a.user_id = b.user_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return '';
        }
    }     
    
    function update_status_karyawan($params, $where) {
        return $this->db->update('pegawai_status', $params, $where);
    }    

    function update_pegawai_status($params, $tahun){
        $sql = "UPDATE pegawai SET pegawai_status = ?,
                tanggal_masuk=DATE_FORMAT(tanggal_masuk,'$tahun-%m-%d'),
                tanggal_keluar = ?, mdd = ?, mdb = ?, mdb_name = ?
                WHERE user_id = ?";
        return $this->db->query($sql, $params);        
    }
    
}
