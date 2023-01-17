<?php

class m_presensi extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
    *
    */    
    
    // get total employee report
    function get_total_employee_attendance_by_params($params) {
        $sql = "SELECT COUNT(*)'total' FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                WHERE b.struktur_cd LIKE ? AND a.nama_lengkap LIKE ? AND pegawai_status = 'working'
                ORDER BY a.pegawai_status DESC, a.nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        }
        return array();
    }
    
   // get list employee report
    function get_list_employee_attendance_by_params($params) {
        $sql = "SELECT emp.user_id, emp.nama_lengkap, emp.struktur_nama, 
                COUNT(att.presensi_nip)'total_presensi', SUM(otp)'otp', total_jaldin, total_ijin
                FROM
                (
                        SELECT a.*, struktur_nama 
                        FROM pegawai a
                        LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                        WHERE b.struktur_cd LIKE ? AND a.nama_lengkap LIKE ? AND pegawai_status = 'working'
                ) emp
                LEFT JOIN
                (
                        SELECT * FROM
                        (
                                SELECT *, 
                                IF((presensi_waktu > '08:15:00'), 0, 1)'otp'
                                FROM data_presensi 
                                WHERE presensi_tanggal BETWEEN ? AND ? AND presensi_status = 'IN'
                                ORDER BY presensi_waktu ASC
                        ) a
                        GROUP BY presensi_nip, presensi_tanggal
                ) att ON emp.pegawai_nip = att.presensi_nip
                LEFT JOIN
                (
                        SELECT user_id, COUNT(tanggal)'total_jaldin' FROM
                        (
                                SELECT a.user_id, b.*
                                FROM surat_tugas a
                                INNER JOIN surat_tugas_tanggal b ON a.spt_id = b.spt_id
                                WHERE tanggal BETWEEN ? AND ?
                                ORDER BY a.user_id ASC, tanggal ASC
                        ) a
                        GROUP BY a.user_id 
                ) jaldin ON emp.user_id = jaldin.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(izin_tanggal)'total_ijin' FROM
                        (
                                SELECT a.user_id, izin_tanggal
                                FROM pegawai_izin a
                                WHERE izin_tanggal BETWEEN ? AND ? AND jenis_id IN (1, 2, 6)
                                ORDER BY a.user_id ASC, izin_tanggal ASC
                        ) a
                        GROUP BY user_id 
                ) ijin ON emp.user_id = ijin.user_id
                GROUP BY emp.user_id
                ORDER BY emp.nama_lengkap ASC
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }
    
    // get rekap presensi by user
    function get_rekapitulasi_presensi_by_user_date($params) {
        $sql = "SELECT * FROM
                (
                        SELECT a.*, 
                        IF((presensi_waktu > '08:15:00'), 0, 1)'otp', TIMEDIFF('08:15:00', presensi_waktu)'keterlambatan'
                        FROM data_presensi a
                        INNER JOIN pegawai b ON a.presensi_nip = b.pegawai_nip 
                        WHERE presensi_tanggal BETWEEN ? AND ? AND presensi_status = 'IN'
                        AND b.user_id = ?
                        GROUP BY presensi_nip, presensi_tanggal
                        ORDER BY presensi_waktu ASC
                ) a
                ORDER BY presensi_tanggal ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }   

    // get rekap presensi by user
    function get_rekapitulasi_presensi_pulang_by_user_date($params) {
        $sql = "SELECT * FROM
                (
                        SELECT a.*
                        FROM data_presensi a
                        INNER JOIN pegawai b ON a.presensi_nip = pegawai_nip
                        WHERE presensi_tanggal = ? AND presensi_status = 'OUT'
                        AND b.user_id = ?
                        ORDER BY presensi_waktu ASC
                ) a
                ORDER BY presensi_tanggal ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['presensi_waktu'];
        }
    }   

    // get list employee report print
    function get_list_employee_attendance_all($params) {
        $sql = "SELECT emp.user_id, emp.nama_lengkap, emp.struktur_cd, struktur_nama, emp.pegawai_nip,
                COUNT(att.presensi_nip)'total_presensi', SUM(otp)'otp', total_jaldin, total_ijin
                FROM
                (
                        SELECT a.*, struktur_nama 
                        FROM pegawai a
                        LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                        WHERE b.struktur_nama LIKE ? AND a.nama_lengkap LIKE ? AND pegawai_status = 'working'
                ) emp
                LEFT JOIN
                (
                        SELECT * FROM
                        (
                                SELECT *, 
                                IF((presensi_waktu > '08:15:00'), 0, 1)'otp'
                                FROM data_presensi
                                WHERE presensi_tanggal BETWEEN ? AND ? AND presensi_status = 'IN'
                                ORDER BY presensi_waktu ASC
                        ) a
                        GROUP BY presensi_nip, presensi_tanggal
                ) att ON emp.pegawai_nip = att.presensi_nip
                LEFT JOIN
                (
                        SELECT user_id, COUNT(tanggal)'total_jaldin' FROM
                        (
                                SELECT a.user_id, b.*
                                FROM surat_tugas a
                                INNER JOIN surat_tugas_tanggal b ON a.spt_id = b.spt_id
                                WHERE tanggal BETWEEN ? AND ?
                                ORDER BY a.user_id ASC, tanggal ASC
                        ) a
                        GROUP BY user_id 
                ) jaldin ON emp.user_id = jaldin.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(izin_tanggal)'total_ijin' FROM
                        (
                                SELECT a.user_id, izin_tanggal
                                FROM pegawai_izin a
                                WHERE izin_tanggal BETWEEN ? AND ? AND jenis_id IN (1, 2, 6)
                                ORDER BY a.user_id ASC, izin_tanggal ASC
                        ) a
                        GROUP BY user_id 
                ) ijin ON emp.user_id = ijin.user_id
                GROUP BY emp.user_id
                ORDER BY emp.nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }    
    
    /* UTILITY
    *
    */    
    
    // get total days
    function get_total_days($params) {
        $sql = "SELECT ((ABS(DATEDIFF(?, ?)) + 1) - COUNT(*))'total'
                FROM data_hari_libur a
                WHERE libur_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        }
        return 0;
    }    
}
