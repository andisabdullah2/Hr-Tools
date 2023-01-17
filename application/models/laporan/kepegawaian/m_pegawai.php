<?php

class m_pegawai extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     *
     */

    //get total data karyawan
    function get_total_employee($params) {
        $sql = "SELECT COUNT(*) 'total' FROM pegawai 
                WHERE nama_lengkap LIKE ? AND struktur_cd LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get all data karyawan limit
    function get_all_karyawan_limit($params) {
        $sql = "SELECT a.*, b.struktur_nama, d.jabatan_nama,e.user_mail 
                FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                LEFT JOIN pegawai_jabatan_struktural c ON a.user_id = c.user_id AND (c.jabatan_status = 1)
                LEFT JOIN data_jabatan_struktural d ON c.jabatan_struktural_id = d.jabatan_struktural_id
                LEFT JOIN com_user e ON a.user_id = e.user_id
                WHERE nama_lengkap LIKE ? AND a.struktur_cd LIKE ?
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

    // get detail user
    function get_employee_detail_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, d.jabatan_nama,e.user_mail,g.jabatan_nama AS jabatan_fungsional
                FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                LEFT JOIN pegawai_jabatan_struktural c ON a.user_id = c.user_id
                LEFT JOIN data_jabatan_struktural d ON c.jabatan_struktural_id = d.jabatan_struktural_id
                LEFT JOIN com_user e ON a.user_id = e.user_id
                LEFT JOIN pegawai_jabatan_fungsional f ON a.user_id = f.user_id AND (f.jabatan_status = '1' AND f.jabatan_default = '1')
                LEFT JOIN data_jabatan_fungsional g ON f.jabatan_fungsional_id=g.jabatan_fungsional_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

  /* UTILITY
   *
   */
    
    // get unit kerja
    function get_unit_kerja() {
        $sql = "SELECT struktur_cd, struktur_nama
                FROM data_struktur_organisasi
                ORDER BY struktur_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
 }
