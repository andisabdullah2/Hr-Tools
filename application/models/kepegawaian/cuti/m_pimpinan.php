<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_pimpinan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /*CORE
    */   
    
    // get total permit in pimpinan
    function get_total_permit_by_flow($params) {        
        $sql = "SELECT COUNT(a.cuti_id)'total'
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN pegawai_cuti_process u ON a.cuti_id = u.cuti_id                
                WHERE u.flow_id = ? AND a.struktur_cd = ? 
                AND u.action_st = 'process' AND b.nama_lengkap LIKE ?
                AND a.cuti_tanggal_mulai LIKE ? AND YEAR(a.cuti_tanggal_mulai) = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }
    
    // get list permit in pimpinan
    function get_all_permit_by_flow_limit($params) {
        $sql = "SELECT a.*, u.process_id, b.nama_lengkap, u.process_st,c.jenis_cuti
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON c.jenis_id=a.jenis_id
                INNER JOIN pegawai_cuti_process u ON a.cuti_id = u.cuti_id
                WHERE u.flow_id = ? AND a.struktur_cd = ? 
                AND u.action_st = 'process' AND b.nama_lengkap LIKE ?
                AND a.cuti_tanggal_mulai LIKE ? AND YEAR(a.cuti_tanggal_mulai) = ? 
                GROUP BY a.cuti_id ORDER BY a.mdd ASC
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
    
    // get permit by process
    function get_detail_permit_by_process($params) {
        $sql = "SELECT b.*, a.process_id,  c.nama_lengkap, d.jenis_cuti
                FROM pegawai_cuti_process a
                INNER JOIN pegawai_cuti b ON a.cuti_id = b.cuti_id
                INNER JOIN pegawai c ON b.user_id = c.user_id
                INNER JOIN data_jenis_cuti d ON d.jenis_id=b.jenis_id
                WHERE a.cuti_id = ? AND a.action_st = 'process'
                GROUP BY a.cuti_id";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    
    
    // create new flow
    function insert_flow($params) {
        return $this->db->insert('pegawai_cuti_process', $params);
    }        
    
    // update flow
    function update_flow_by_id($params, $where) {
        return $this->db->update('pegawai_cuti_process', $params, $where);
    }
    
    // update pegawai_cuti status
    function update_cuti_st($params, $where) {
        return $this->db->update('pegawai_cuti', $params, $where);
    }       
    
    // update pegawai_cuti_process status
    function update_cuti_st_cuti_process($params, $where) {
        return $this->db->update('pegawai_cuti_process', $params, $where);
    }       
        
    /*UTILITY
    */
    
    // get list tahun avail in permit
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
            
    // get microtime 
    public function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }
    
    // check references
    function check_if_have_references($params) {
        $sql = "SELECT process_st,process_references_id FROM pegawai_cuti_process 
                WHERE process_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if (!empty($result['process_references_id'])){
            return true;
            } else {
            return false;
            }
        }
    }       
        
    // get references
    function get_process_reference($params) {
        $sql = "SELECT process_id FROM pegawai_cuti_process WHERE cuti_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_id'];
        } else {
            return null;
        }
    }   

    // get struktur_cd by user id
    function get_unit_kerja_by_user_id($params) {
        $sql = "SELECT struktur_cd FROM pegawai
                WHERE user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['struktur_cd'];
        } else {
            return null;
        }
    }

    // get pengajuan_st by pengajuan_id
    function get_jenis_and_tahun_cuti_by_id($pengajuan_id){
        $sql = "SELECT cuti_id, user_id, jenis_id, YEAR(cuti_tanggal_mulai) AS tahun
                FROM pegawai_cuti
                WHERE cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get pengajuan_st by pengajuan_id
    function get_total_cuti_pengajuan($pengajuan_id){
        $sql = "SELECT COUNT(cuti_tanggal) AS total
                FROM pegawai_cuti_tanggal
                WHERE cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return null;
        }
    }

   public function get_total_jatah_cuti($params) {
        $sql = "SELECT total FROM pegawai_cuti_kuota WHERE user_id = ? AND jenis_id = ? AND tahun = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }   
    
    // get kuota used
    public function get_total_cuti_terpakai($params) {
        $sql = "SELECT COUNT(a.cuti_id) AS total FROM pegawai_cuti_tanggal a
                INNER JOIN pegawai_cuti b ON a.cuti_id = b.cuti_id
                WHERE b.user_id = ? AND b.jenis_id = ? AND YEAR(b.cuti_tanggal_mulai) = ? AND cuti_status = 'approved'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }    
}