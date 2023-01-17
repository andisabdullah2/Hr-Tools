<?php

class m_ijin extends CI_Model {


    /* CORE
     * 
     */

    function get_all_permit_personal($data_struktur_organisasi, $bulan, $tahun) {
        $params = array($bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $data_struktur_organisasi);
        $sql = "SELECT a.user_id, a.nama_lengkap, b.struktur_nama,
                (
                       SELECT COUNT(izin_id)
                       FROM pegawai_izin au
                       WHERE au.user_id = a.user_id AND jenis_id = 'IZ.03' AND izin_status <> 'rejected' AND MONTH(izin_tanggal) = ? AND YEAR(izin_tanggal) = ?
                )'total_terlambat',
                (
                       SELECT COUNT(izin_id)
                       FROM pegawai_izin au
                       WHERE au.user_id = a.user_id AND jenis_id = 'IZ.02' AND izin_status <> 'rejected' AND MONTH(izin_tanggal) = ? AND YEAR(izin_tanggal) = ?
                )'total_tdk_absen',
                (
                       SELECT COUNT(izin_id)
                       FROM pegawai_izin au
                       WHERE au.user_id = a.user_id AND jenis_id = 'IZ.05' AND izin_status <> 'rejected' AND MONTH(izin_tanggal) = ? AND YEAR(izin_tanggal) = ?
                )'total_tgl_kerja',
                (
                       SELECT COUNT(izin_id)
                       FROM pegawai_izin au
                       WHERE au.user_id = a.user_id AND jenis_id = 'IZ.04' AND izin_status <> 'rejected' AND MONTH(izin_tanggal) = ? AND YEAR(izin_tanggal) = ?
                )'total_plg_awal',
                (
                       SELECT COUNT(izin_id)
                       FROM pegawai_izin au
                       WHERE au.user_id = a.user_id AND jenis_id = 'IZ.01' AND izin_status <> 'rejected' AND MONTH(izin_tanggal) = ? AND YEAR(izin_tanggal) = ?
                )'total_tdk_msk'
               FROM pegawai a
               LEFT JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
               WHERE a.pegawai_status = 'working' AND a.struktur_cd LIKE ?
               ORDER BY a.nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // total
    function get_total_pegawai() {
        $sql = "SELECT COUNT(user_id)'total' FROM
                pegawai WHERE pegawai_status = 'working'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }    

    /* UTILITY
     * 
     */

    //ambil data data_struktur_organisasi
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
    
    // get list tahun avail
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(izin_tanggal)'tahun'
                        FROM pegawai_izin
                        UNION ALL
                        SELECT YEAR(CURRENT_DATE)'tahun'
                ) rs
                ORDER BY tahun ASC";
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