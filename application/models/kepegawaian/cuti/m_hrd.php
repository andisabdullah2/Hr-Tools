<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_hrd extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /*CORE
    */
    
    // get total pegawai_cuti for hrd
    function get_total_permit_by_flow($params) {
        $sql = "SELECT COUNT(a.cuti_id)'total'
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN pegawai_cuti_process u ON a.cuti_id = u.cuti_id                
                WHERE u.flow_id = ? AND a.struktur_cd LIKE ? 
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
    
    // get data pegawai_cuti for hrd
    function get_all_permit_by_flow_limit($params) {
        $sql = "SELECT a.*, u.process_id, b.nama_lengkap, u.process_st,c.jenis_cuti, x.struktur_nama
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON c.jenis_id=a.jenis_id
                INNER JOIN pegawai_cuti_process u ON a.cuti_id = u.cuti_id
                INNER JOIN data_struktur_organisasi x ON a.struktur_cd = x.struktur_cd                 
                WHERE u.flow_id = ? AND a.struktur_cd LIKE ? 
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
        $sql = "SELECT b.*, a.process_id,  c.nama_lengkap, d.jenis_cuti,e.struktur_nama
                FROM pegawai_cuti_process a
                INNER JOIN pegawai_cuti b ON a.cuti_id = b.cuti_id
                INNER JOIN pegawai c ON b.user_id = c.user_id
                INNER JOIN data_struktur_organisasi e ON e.struktur_cd=b.struktur_cd
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

    // insert pegawai_cuti_process
    function insert_flow($params) {
        return $this->db->insert('pegawai_cuti_process', $params);
    }
    
    //update pegawai_cuti
    function update_cuti_st($params, $where) {
        return $this->db->update('pegawai_cuti', $params, $where);
    }       
    
    // update pegawai_cuti_process
    function update_cuti_process($params, $where) {
        return $this->db->update('pegawai_cuti_process', $params, $where);
    }       
    
    /*UTILITY
    */    
    
    // get microtime
    public function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }    
    
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
    
    // get proses catatan pimpinan
    function get_catatan_pimpinan($params) {
        $sql = "SELECT catatan FROM pegawai_cuti_process
                WHERE cuti_id = ? AND flow_id = ? AND process_st = 'approve'
                ORDER BY process_id DESC LIMIT 1
                ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['catatan'];
        } else {
            return null;
        }
    }    
    
    // get flow id
    function get_task_flow_id($params) {
        $sql = "SELECT flow_id FROM task_flow WHERE group_id = ? AND task_number LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return null;
        }
    }       
 
    // get reference id
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

    // get unit_kerja
    function get_list_unit_kerja() {
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
}