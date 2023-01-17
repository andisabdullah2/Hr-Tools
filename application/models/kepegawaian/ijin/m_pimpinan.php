<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_pimpinan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */    
    
    // get total pegawai_izin for pimpinan
    function get_total_permit_by_flow($params) {
        $sql = "SELECT COUNT(a.izin_id)'total'
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN pegawai_izin_process u ON a.izin_id = u.izin_id                
                WHERE u.flow_id = ? AND a.struktur_cd = ? 
                AND u.action_st = 'process' AND b.nama_lengkap LIKE ?
                AND a.izin_tanggal LIKE ? AND YEAR(a.izin_tanggal) = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    } 

    // get data pegawai_izin for pimpinan
    function get_all_permit_by_flow_limit($params) {
        $sql = "SELECT a.*, u.process_id, b.nama_lengkap, u.process_st,c.jenis_izin
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_izin c ON c.jenis_id=a.jenis_id
                INNER JOIN pegawai_izin_process u ON a.izin_id = u.izin_id
                WHERE u.flow_id = ? AND a.struktur_cd = ? 
                AND u.action_st = 'process' AND b.nama_lengkap LIKE ?
                AND a.izin_tanggal LIKE ? AND YEAR(a.izin_tanggal) = ? 
                GROUP BY a.izin_id ORDER BY a.mdd ASC
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
        $sql = "SELECT b.*, a.process_id,  c.nama_lengkap, d.jenis_izin
                FROM pegawai_izin_process a
                INNER JOIN pegawai_izin b ON a.izin_id = b.izin_id
                INNER JOIN pegawai c ON b.user_id = c.user_id
                INNER JOIN data_jenis_izin d ON d.jenis_id=b.jenis_id
                WHERE a.izin_id = ? AND a.action_st = 'process'
                GROUP BY a.izin_id";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_flow($params) {
        return $this->db->insert('pegawai_izin_process', $params);
    }    

    function update_izin_st($params, $where) {
        return $this->db->update('pegawai_izin', $params, $where);
    }       
    
    function update_izin_st_izin_process($params, $where) {
        return $this->db->update('pegawai_izin_process', $params, $where);
    }    

    function update_flow_by_id($params, $where) {
        return $this->db->update('pegawai_izin_process', $params, $where);
    }       
     
    /* UTILITY
     * 
     */     
    
    // get list tahun avail in pegawai_izin
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

    // get unit kerja
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
    
    // get microtime
    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }
    
    // check if have ref
    function check_if_have_references($params) {
        $sql = "SELECT process_st,process_references_id FROM pegawai_izin_process 
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
    
    // get ref id
    function get_process_reference($params) {
        $sql = "SELECT process_id FROM pegawai_izin_process WHERE izin_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_id'];
        } else {
            return null;
        }
    }    
}