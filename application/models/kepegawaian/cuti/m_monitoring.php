<?php

class m_monitoring extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /*CORE
    */
    
    // get total pegawai_cuti
    function get_total_permit($params) {
        $sql = "SELECT COUNT(a.cuti_id)'total'
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.cuti_tanggal_mulai) = ? 
                AND a.cuti_tanggal_mulai LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }
    
    // get data pegawai_cuti
    function get_all_permit_by_limit($params) {
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_cuti, d.process_st, 
                d.flow_id,d.action_st, get_last_flow_cuti_by_id(a.cuti_id) AS flow
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON a.jenis_id = c.jenis_id
                INNER JOIN pegawai_cuti_process d ON a.cuti_id=d.cuti_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.cuti_tanggal_mulai) = ?
                AND a.cuti_tanggal_mulai LIKE ? 
                GROUP BY a.cuti_id
                ORDER BY a.cuti_tanggal_mulai DESC, a.cuti_nomor DESC
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
    
    // get detail data pegawai_cuti summary
    function get_detail_permit($permit_id){
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_cuti, a.struktur_cd, d.struktur_nama
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON a.jenis_id=c.jenis_id
                LEFT JOIN data_struktur_organisasi d ON a.struktur_cd=d.struktur_cd
                WHERE a.cuti_id = ?";
        $query = $this->db->query($sql, $permit_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get data pegawai_cuti_process
    function get_permit_proses($permit_id){
        $sql = "SELECT a.process_st,a.action_st,a.mdb,a.mdb_name,a.mdd,a.mdb_finish,a.mdb_finish_name,
                a.mdd_finish,a.catatan,b.*,c.cuti_send_date,c.cuti_send_by_name
                FROM pegawai_cuti_process a
                INNER JOIN task_flow b ON a.flow_id = b.flow_id 
                LEFT JOIN pegawai_cuti c ON a.cuti_id=c.cuti_id
                WHERE a.cuti_id = ?
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
                FROM pegawai_cuti a
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
                WHERE a.cuti_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    
    
    /*UTILITY
    */
    
    // get list tahun avail in pegawai_cuti
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
    
    // get struktur_cd by user id
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

    // get unit kerja leader aktif
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

    // get jenis cuti
    function get_permit_type($params) {
        $sql = "SELECT * FROM data_jenis_cuti 
                ORDER BY jenis_cuti ASC";
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
