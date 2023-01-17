<?php

class m_pegawai_unit_kerja extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_list_department() {
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
                INNER JOIN pegawai_unit_kerja b ON a.user_id = b.user_id
                INNER JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                WHERE a.nama_lengkap LIKE ? AND b.struktur_cd LIKE ?";
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
        $sql = "SELECT b.data_id, b.user_id, a.nama_lengkap, c.struktur_nama, b.unit_kerja_status, b.nomor_sk FROM pegawai a
                INNER JOIN pegawai_unit_kerja b ON a.user_id = b.user_id
                INNER JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                WHERE a.nama_lengkap LIKE ? AND b.struktur_cd LIKE ?
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
    
    function insert_unit_kerja_pegawai($params) {
        return $this->db->insert('pegawai_unit_kerja', $params);
    }
    
    function update_lampiran_unit_kerja($params) {
        $sql = "UPDATE pegawai_unit_kerja SET lampiran_file_name = ? WHERE data_id = ?";
        return $this->db->query($sql, $params);
    }    

    function update_status_karyawan($params, $where) {
        return $this->db->update('pegawai_status', $params, $where);
    }    
    
    function update_unit_kerja_pegawai($params, $where) {
        return $this->db->update('pegawai_unit_kerja', $params, $where);
    }    

    function update_pegawai_status($params, $tahun){
        $sql = "UPDATE pegawai SET pegawai_status = ?,
                tanggal_masuk=DATE_FORMAT(tanggal_masuk,'$tahun-%m-%d'),
                tanggal_keluar = ?, mdd = ?, mdb = ?, mdb_name = ?
                WHERE user_id = ?";
        return $this->db->query($sql, $params);        
    }
    
    function set_aktif_unit_kerja($struktur_cd, $user_id, $data_id) {
        $sql = "UPDATE pegawai_unit_kerja SET `unit_kerja_status` = '0' WHERE user_id = $user_id";
        $query = $this->db->query($sql);        
        $sql = "UPDATE pegawai_unit_kerja SET `unit_kerja_status` = '1' WHERE data_id = $data_id";
        $query = $this->db->query($sql);        
        $sql = "UPDATE pegawai SET `struktur_cd` = '$struktur_cd' WHERE user_id = $user_id";
        return $this->db->query($sql);
    }        
        
    function get_detail_unit_kerja_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, b.struktur_singkatan,d.nama_lengkap,c.user_alias FROM `pegawai_unit_kerja` a 
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                INNER JOIN pegawai d ON d.user_id = a.user_id
                INNER JOIN com_user c ON c.user_id = a.user_id
                WHERE a.data_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    function delete_unit_kerja_pegawai($params) {
        $sql = "DELETE FROM pegawai_unit_kerja
		WHERE data_id = ?";
        return $this->db->query($sql, $params);
    }    
    
}
