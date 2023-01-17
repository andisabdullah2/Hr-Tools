<?php

class m_pegawai_jabatan extends CI_Model {

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
        $sql = "SELECT user_id,nama_lengkap,struktur_nama,a.struktur_cd FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd=b.struktur_cd
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
    
    function get_total_pejabat($params) {
        $sql = "SELECT COUNT(*) 'total' FROM pegawai_jabatan_struktural a
                INNER JOIN pegawai b ON b.user_id = a.user_id
                INNER JOIN data_jabatan_struktural c ON c.jabatan_struktural_id = a.jabatan_struktural_id
                WHERE b.nama_lengkap LIKE ? AND c.jabatan_struktural_id LIKE ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    function get_all_pejabat_limit($params) {
        $sql = "SELECT a.*,c.jabatan_nama,c.jabatan_alias,b.nama_lengkap, d.struktur_nama FROM pegawai_jabatan_struktural a
                INNER JOIN pegawai b ON b.user_id = a.user_id
                INNER JOIN data_jabatan_struktural c ON c.jabatan_struktural_id = a.jabatan_struktural_id
                INNER JOIN data_struktur_organisasi d ON c.struktur_cd = d.struktur_cd
                WHERE b.nama_lengkap LIKE ? AND c.jabatan_struktural_id LIKE ?
                ORDER BY b.nama_lengkap ASC 
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

    function insert_jabatan_struktur_pegawai($params) {
        return $this->db->insert('pegawai_jabatan_struktural', $params);
    }    
    
    function set_default($table, $user_id, $data_id) {
        $sql = "UPDATE $table SET `jabatan_default` = '0' WHERE user_id = $user_id";
        $query = $this->db->query($sql);        
        $sql = "UPDATE $table SET `jabatan_default` = '1' WHERE data_id = $data_id";
        return $this->db->query($sql);
    }    
    
    
    function set_aktif_jabatan_struktural($jabatan_struktural_id, $user_id, $data_id) {
        $sql = "UPDATE pegawai_jabatan_struktural SET `jabatan_status` = '0' WHERE user_id = $user_id";
        $query = $this->db->query($sql);        
        $sql = "UPDATE pegawai_jabatan_struktural SET `jabatan_status` = '1' WHERE data_id = $data_id";
        $query = $this->db->query($sql);
        $sql = "UPDATE pegawai SET `jabatan_struktural_id` = '$jabatan_struktural_id', jabatan_struktural_st = '1' WHERE user_id = $user_id";
        return $this->db->query($sql);
    }
    
    function set_non_aktif_jabatan_struktural($user_id) {
        $sql = "UPDATE pegawai SET `jabatan_struktural_id` = NULL, jabatan_struktural_st = '0' WHERE user_id = $user_id";
        return $this->db->query($sql);
    }
    
    function check_jabatan_match_by_id($params) {
        $sql = "SELECT jabatan_struktural_id FROM pegawai WHERE jabatan_struktural_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }

    function update_lampiran_jabatan($table, $params) {
        $sql = "UPDATE " . $table . " SET lampiran_file_name = ? WHERE data_id = ?";
        return $this->db->query($sql, $params);
    }
    
    function get_detail_struktural_by_id($params) {
        $sql = "SELECT d.user_alias,c.user_id AS user_id,c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.* FROM pegawai_jabatan_struktural a 
                INNER JOIN data_jabatan_struktural b ON b.jabatan_struktural_id = a.jabatan_struktural_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                INNER JOIN com_user d ON d.user_id = c.user_id
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
    
    function get_user_alias_by_id($params) {
        $sql = "SELECT user_alias
                FROM com_user
                WHERE user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['user_alias'];
        } else {
            return '';
        }
    }
    
    function update_jabatan_struktur_pegawai($params, $where) {
        return $this->db->update('pegawai_jabatan_struktural', $params, $where);
    }
    
    function delete($params) {
        $sql = "DELETE FROM pegawai_jabatan_struktural
		WHERE data_id = ?";
        return $this->db->query($sql, $params);
    }      
}
