<?php

class m_cuti extends CI_Model {

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    /* CORE
     * 
     */

    // get total leave
    function get_total_leave($params) {
        $sql = "SELECT COUNT(*)'total'
                FROM (
			SELECT a.cuti_id FROM pegawai_cuti a
			INNER JOIN pegawai b ON a.user_id = b.user_id
			WHERE b.nama_lengkap LIKE ? AND YEAR(a.cuti_tanggal_mulai) = ? AND b.struktur_cd LIKE ? AND a.cuti_status='approved'
			GROUP BY a.user_id
                ) tmp";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }
    
    // get list leave limit
    function get_all_leave_by_user_id($params) {
        $sql = "SELECT a.user_id, nama_lengkap, struktur_nama, COUNT(e.cuti_tanggal)'total_cuti'
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                LEFT JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                LEFT JOIN pegawai_cuti_tanggal e ON a.cuti_id = e.cuti_id
                WHERE nama_lengkap LIKE ? AND YEAR(cuti_tanggal_mulai) = ? AND b.struktur_cd LIKE ? 
                AND a.jenis_id = 'CT.01' AND a.cuti_status='approved' 
                GROUP BY a.user_id
                ORDER BY nama_lengkap ASC
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
    
    // get list leave has no limit (print)
    function get_all_leave_by_user_id_no_limit($params) {
        $sql = "SELECT a.user_id, nama_lengkap, struktur_nama, COUNT(e.cuti_tanggal)'total_cuti'
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                LEFT JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                LEFT JOIN pegawai_cuti_tanggal e ON a.cuti_id = e.cuti_id
                WHERE nama_lengkap LIKE ? AND YEAR(cuti_tanggal_mulai) = ? AND b.struktur_cd LIKE ? 
		AND a.jenis_id = 'CT.01' AND a.cuti_status='approved'
                GROUP BY a.user_id
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
    
    // get detail user profile
   function get_user_profile_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama,d.jabatan_nama
                FROM pegawai a
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                LEFT JOIN pegawai_jabatan_struktural c ON c.jabatan_struktural_id=a.jabatan_struktural_id
                LEFT JOIN data_jabatan_struktural d ON d.jabatan_struktural_id=c.jabatan_struktural_id
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
     
    // get list tahun avail
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(cuti_tanggal_mulai)'tahun'
                        FROM pegawai_cuti
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
    
    // get kuota cuti user
    function get_total_kuota_by_user_id($params) {
        $sql = "SELECT a.total AS total
                FROM pegawai_cuti_kuota a
                WHERE a.user_id = ? 
		ORDER BY a.tahun DESC
		LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result[0]['total'];
        } else {
            return 12;
        }
    }
    
    // get kuota terpakai perbulan
    function get_kuota_terpakai_perbulan($params) {
        $sql = "SELECT COUNT(*)'total' 
                FROM pegawai_cuti a
                INNER JOIN pegawai_cuti_tanggal b ON a.cuti_id = b.cuti_id
                WHERE a.jenis_id = 'CT.01' AND a.user_id = ? AND MONTH(b.cuti_tanggal) = ? 
                AND YEAR(b.cuti_tanggal) = ? AND a.cuti_status='approved'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }    

    // get leave by leave id
    function get_leave_year_by_user_id($params) {
        $sql = "SELECT YEAR(a.cuti_tanggal_mulai)'tahun', COUNT(c.cuti_tanggal)'total_cuti'
                FROM pegawai_cuti a
                LEFT JOIN pegawai_cuti_tanggal c ON a.cuti_id = c.cuti_id
                WHERE a.user_id = ? AND a.jenis_id = 'CT.01' AND a.cuti_status='approved'
                GROUP BY YEAR(cuti_tanggal_mulai)";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
}