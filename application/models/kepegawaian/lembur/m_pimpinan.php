<?php

class m_pimpinan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /*
     * UTILITY
     */

    // get microtime
    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }

    //get list tahun
    function get_list_tahun(){
        $sql = "SELECT DISTINCT tahun FROM
                (
                    SELECT YEAR(overtime_date) 'tahun'
                    FROM surat_lembur
                    UNION ALL
                    SELECT YEAR(CURRENT_DATE) 'tahun'
                )rs
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

    // get active project
    function get_all_projects() {
        $sql = "SELECT * FROM projects ORDER BY YEAR(project_start) DESC, project_alias ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
    PERSETUJUAN PIMPINAN
     */

    // get department by user id
    function get_user_unit_kerja_by_id($id) {
        $sql = "SELECT struktur_cd FROM pegawai a
                WHERE a.user_id = $id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['struktur_cd'];
        } else {
            return null;
        }
    }

    // get task flow id
    function get_task_flow_id($task_number) {
        $sql = "SELECT flow_id FROM task_flow WHERE group_id='13' AND task_number = ?";
        $query = $this->db->query($sql, $task_number);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return null;
        }
    }    

    // get total overtime by flow
    function get_total_overtime_by_flow($params) {
        $sql = "SELECT COUNT(a.overtime_id)'total'
                FROM surat_lembur_process a
                INNER JOIN surat_lembur b ON a.overtime_id = b.overtime_id
                WHERE a.action_st = 'process' AND a.flow_id = ? AND b.struktur_cd = ? 
                AND project_id LIKE ? AND YEAR(b.overtime_date) = ? AND MONTH(b.overtime_date) LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get all overtime by flow limit
    function get_all_overtime_by_flow_limit($params){
        $sql = "SELECT a.overtime_id, a.overtime_date, a.overtime_st, project_name, project_alias, 
                        overtime_start, overtime_end, COUNT(b.user_id)'total_personel', e.process_id,e.process_st,e.flow_revisi_id, f.*
                FROM surat_lembur a
                INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                INNER JOIN pegawai c ON b.user_id = c.user_id
                INNER JOIN projects d ON d.project_id = a.project_id
                INNER JOIN surat_lembur_process e ON a.overtime_id = e.overtime_id
                INNER JOIN task_flow f ON e.flow_id = f.flow_id
                WHERE e.process_st = 'waiting' AND a.struktur_cd = ? AND e.flow_id = ? AND a.project_id LIKE ? AND YEAR(a.overtime_date) = ? AND MONTH(a.overtime_date) LIKE ?
                GROUP BY a.overtime_id
                ORDER BY e.process_id DESC
                LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail overtime by process
    function get_detail_overtime_by_process($params){
        $sql = "SELECT a.*, b.process_id, c.project_alias, c.project_name
                FROM surat_lembur a
                INNER JOIN surat_lembur_process b ON a.overtime_id = b.overtime_id
                INNER JOIN projects c ON a.project_id = c.project_id
                WHERE a.overtime_id = ? AND b.process_st = 'waiting'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    //get detail personil by process
    function get_detail_personil_by_process($params){
        $sql = "SELECT a.user_id, a.nama_lengkap
                FROM pegawai a
                INNER JOIN pegawai_lembur b ON a.user_id = b.user_id
                INNER JOIN surat_lembur_process c ON b.overtime_id = c.overtime_id 
                WHERE b.overtime_id = ? AND c.process_st = 'waiting'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // check if have references
    function check_if_have_references($params){
        $sql = "SELECT COUNT(process_references_id)'total' FROM surat_lembur_process WHERE process_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get process id
    function get_process_id ($params){
        $sql = "SELECT process_id FROM surat_lembur_process WHERE overtime_id = ? AND flow_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_id'];
        } else {
            return array();
        }
    }

    // get process references
    function get_process_reference($params){
        $sql = "SELECT process_references_id FROM surat_lembur_process WHERE process_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_references_id'];
        } else {
            return array();
        } 
    }

    // update surat lembur process
    function update_surat_lembur_process($params,$where) {
        return $this->db->update('surat_lembur_process', $params, $where);
    }

    // insert surat lembur process
    function insert_surat_lembur_process($params) {
        return $this->db->insert('surat_lembur_process', $params);
    }

    // update surat lembur
    function update_surat_lembur($params,$where){
        return $this->db->update('surat_lembur',$params,$where);
    }

}