<?php

class m_monitoring extends CI_Model {

    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

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
    
    function get_user_unit_kerja_by_id($id) {
        $sql = "SELECT struktur_cd FROM pegawai a
                WHERE a.user_id = $id AND 1=0";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['struktur_cd'];
        } else {
            return null;
        }
    }   

    function get_total_permit($params) {
        $sql = "SELECT COUNT(a.izin_id)'total'
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.izin_tanggal) = ? 
                AND a.izin_tanggal LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_all_permit_by_limit($params) {
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_izin, d.process_st, d.flow_id,d.action_st,get_last_flow_izin_by_id(a.izin_id) AS flow
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_izin c ON a.jenis_id = c.jenis_id
                INNER JOIN pegawai_izin_process d ON a.izin_id=d.izin_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.izin_tanggal) = ? 
                AND a.izin_tanggal LIKE ?
                GROUP BY a.izin_id
                ORDER BY a.izin_tanggal DESC, a.izin_nomor DESC
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

    function get_all_permit_personal($department, $bulan, $tahun) {
        $params = array($bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $department);
        $sql = "SELECT a.user_id, a.full_name, b.department_name,
                (
                       SELECT COUNT(permit_id)
                       FROM users_permit au
                       WHERE au.user_id = a.user_id AND permit_type = 'Datang Terlambat' AND permit_st <> 'rejected' AND MONTH(permit_date) = ? AND YEAR(permit_date) = ?
                )'total_terlambat',
                (
                       SELECT COUNT(permit_id)
                       FROM users_permit au
                       WHERE au.user_id = a.user_id AND permit_type = 'Tidak mengabsen' AND permit_st <> 'rejected' AND MONTH(permit_date) = ? AND YEAR(permit_date) = ?
                )'total_tdk_absen',
                (
                       SELECT COUNT(permit_id)
                       FROM users_permit au
                       WHERE au.user_id = a.user_id AND permit_type = 'Meninggalkan jam kerja selama jam kerja' AND permit_st <> 'rejected' AND MONTH(permit_date) = ? AND YEAR(permit_date) = ?
                )'total_tgl_kerja',
                (
                       SELECT COUNT(permit_id)
                       FROM users_permit au
                       WHERE au.user_id = a.user_id AND permit_type = 'Pulang lebih awal' AND permit_st <> 'rejected' AND MONTH(permit_date) = ? AND YEAR(permit_date) = ?
                )'total_plg_awal',
                (
                       SELECT COUNT(permit_id)
                       FROM users_permit au
                       WHERE au.user_id = a.user_id AND permit_type = 'Tidak Masuk Kerja' AND permit_st <> 'rejected' AND MONTH(permit_date) = ? AND YEAR(permit_date) = ?
                )'total_tdk_msk'
               FROM users a
               LEFT JOIN department b ON b.department_id = a.department_id
               WHERE a.employee_st = 'working' AND a.department_id LIKE ?
               ORDER BY a.full_name ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_permit($permit_id){
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_izin, a.struktur_cd, d.struktur_nama
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_izin c ON a.jenis_id=c.jenis_id
                LEFT JOIN data_struktur_organisasi d ON a.struktur_cd=d.struktur_cd
                WHERE a.izin_id = ?";
        $query = $this->db->query($sql, $permit_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_permit_proses($permit_id){
        $sql = "SELECT a.process_st,a.action_st,a.mdb,a.mdb_name,a.mdd,a.mdb_finish,a.mdb_finish_name,a.mdd_finish,a.catatan,b.*,c.izin_send_date,c.izin_send_by_name
                FROM pegawai_izin_process a
                INNER JOIN task_flow b ON a.flow_id = b.flow_id 
                LEFT JOIN pegawai_izin c ON a.izin_id=c.izin_id
                WHERE a.izin_id = ?
                ";
        $query = $this->db->query($sql, $permit_id);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    //get detail print
    function get_detail_print($params) {
        $sql = "SELECT a.* , b.nama_lengkap, b.struktur_cd, c.struktur_nama,f.jabatan_nama AS jabatan_fungsional ,z.jabatan_nama AS jabatan_struktural
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                LEFT JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                LEFT JOIN
                (
                    SELECT * FROM 
                    (
                        SELECT d.data_id,d.jabatan_fungsional_id,d.user_id,e.jabatan_nama FROM pegawai_jabatan_fungsional d
                        INNER JOIN data_jabatan_fungsional e ON d.jabatan_fungsional_id=e.jabatan_fungsional_id
                        WHERE jabatan_status='1' AND jabatan_default='1'
                        ORDER BY d.tanggal_mulai DESC
                    )
                    ps GROUP BY ps.user_id
                ) f ON f.user_id = b.user_id
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT x.data_id,x.jabatan_struktural_id,x.user_id,y.jabatan_nama FROM pegawai_jabatan_struktural x
                        INNER JOIN data_jabatan_struktural y ON x.jabatan_struktural_id=y.jabatan_struktural_id
                        WHERE jabatan_status='1' AND jabatan_default='1'
                        ORDER BY x.tanggal_mulai DESC
                    )
                    st GROUP BY st.user_id
                ) z ON z.user_id = b.user_id
                WHERE a.izin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
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
            return array();
        }
    }

    function get_permit_type($params) {
        $sql = "SELECT * FROM data_jenis_izin 
                ORDER BY jenis_izin ASC";
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
