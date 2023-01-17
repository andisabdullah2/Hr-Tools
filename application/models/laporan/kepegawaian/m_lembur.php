<?php

class m_lembur extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    
    /* CORE 
     * 
     */
     
    // total
    function get_total_overtime_personel_by_params($params) {
        $sql = "SELECT COUNT(*)'total' FROM
                (
                        SELECT c.user_id, c.nama_lengkap
                        FROM surat_lembur a
                        INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                        INNER JOIN pegawai c ON b.user_id = c.user_id
                        WHERE nama_lengkap LIKE ? AND c.struktur_cd LIKE ? AND (a.overtime_date BETWEEN ? AND ?)    
                        GROUP BY c.user_id
                ) result
                ORDER BY nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get all data
    function get_all_overtime_personel_by_params($params) {
        $sql = "SELECT * FROM
                (
                        SELECT c.user_id, c.nama_lengkap, COUNT(overtime_date)'total_days',
                        HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(overtime_end, overtime_start)))))'total_hours',
                        g.struktur_nama, e.jabatan_nama
                        FROM surat_lembur a
                        INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                        INNER JOIN pegawai c ON b.user_id = c.user_id
                        LEFT JOIN data_struktur_organisasi g ON g.struktur_cd = c.struktur_cd
                        LEFT JOIN pegawai_jabatan_struktural d ON c.user_id = d.user_id AND (d.jabatan_status = 1)
                        LEFT JOIN data_jabatan_struktural e ON d.jabatan_struktural_id = e.jabatan_struktural_id
                        WHERE nama_lengkap LIKE ? AND c.struktur_cd LIKE ? AND (a.overtime_date BETWEEN ? AND ?) AND a.overtime_st <> 'rejected'    
                        GROUP BY c.user_id
                ) result
                ORDER BY nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get user detail    
   function get_user_account_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, e.jabatan_nama
                FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                LEFT JOIN pegawai_jabatan_struktural d ON a.user_id = d.user_id AND (d.jabatan_status = 1)
                LEFT JOIN data_jabatan_struktural e ON d.jabatan_struktural_id = e.jabatan_struktural_id
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
   
    // get all data detail by user
    function get_all_overtime_personel_by_id($params) {
        $sql = "SELECT *, 
                IF(day_num = 2, 'SENIN', IF(day_num = 3, 'SELASA', IF(day_num = 4, 'RABU', IF(day_num = 5, 'KAMIS', IF(day_num = 6, 'JUMAT', IF(day_num = 7, 'SABTU', 'MINGGU'))))))'hari'
                FROM
                (
                        SELECT a.overtime_id, c.user_id, c.nama_lengkap, overtime_start, overtime_end, overtime_date, 
                        DAYOFWEEK(overtime_date)'day_num', rekap_st,
                        HOUR(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(overtime_end, overtime_start))))'total_hours',
                        a.overtime_reason, p.project_name
                        FROM surat_lembur a
                        INNER JOIN projects p ON a.project_id = p.project_id
                        INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                        INNER JOIN pegawai c ON b.user_id = c.user_id
                        WHERE c.user_id = ? AND (a.overtime_date BETWEEN ? AND ?) AND a.overtime_st <> 'rejected'  
                ) result
                ORDER BY overtime_date, overtime_start ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }   

    /* UTILITY
     * 
     */
     
    //ambil data unit_kerja
    function get_all_unit_kerja() {
        $sql = 'SELECT struktur_cd, struktur_nama FROM data_struktur_organisasi';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get dpt leader
    function get_unit_kerja_leader($params){
        $sql = "SELECT d.user_id,e.nama_lengkap
                FROM `pegawai_unit_kerja` a
                INNER JOIN data_struktur_organisasi b ON a.struktur_cd=b.struktur_cd
                INNER JOIN data_jabatan_struktural c ON b.struktur_cd=c.struktur_cd
                INNER JOIN pegawai_jabatan_struktural d ON c.jabatan_struktural_id=d.jabatan_struktural_id
                INNER JOIN pegawai e ON d.user_id=e.user_id
                WHERE a.user_id = ? AND unit_kerja_status='1' AND d.jabatan_status='1'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['nama_lengkap'];
        } else {
            return null;
        }
    }    
}
