<?php

class m_pengajuan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }

    /*
     * UTILITY
     */

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

    // get project by struktur
    function get_project_by_struktur($params){
        $sql = "SELECT * 
                FROM projects a
                INNER JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                WHERE a.struktur_cd = ?
                ORDER BY YEAR(project_start) DESC, project_alias ASC";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }       
    }

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

    // get department leader
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
            return null;
        }
    }

    // get user by department
    function get_user_by_department($params) {
        $sql = "SELECT user_id, nama_lengkap 
                FROM pegawai
                WHERE struktur_cd LIKE ? ORDER BY nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get struktur by cd
    function get_struktur_by_cd($params){
        $sql = "SELECT struktur_nama FROM data_struktur_organisasi
                WHERE struktur_cd = ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['struktur_nama'];
        } else {
            return null;
        }   
    }

    // generate overtime id
    function generate_overtime_id($tanggal) {
        $sql = "SELECT overtime_id AS overtime_id_latest
                FROM surat_lembur
                ORDER BY RIGHT(overtime_id,12) DESC
                LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            // $query->free_result();
            $overtime_id_new = (substr($result['overtime_id_latest'],8)+1);
            $overtime_id_new = str_pad((string)$overtime_id_new, 12, '0', STR_PAD_LEFT);
            $overtime_id_new = str_replace('-','',$tanggal).$overtime_id_new;
            return $overtime_id_new;
        } else {            
            return str_replace('-','',date('Y-m-d')).'000000000001';
        }
    }

    /*
     * OVERTIME
     */

    // total
    function get_total_overtime($params) {
        $sql = "SELECT COUNT(a.overtime_id)'total'
                FROM surat_lembur_process a
                INNER JOIN surat_lembur b ON a.overtime_id = b.overtime_id
                WHERE b.overtime_st = 'draft' OR a.process_st = 'waiting' AND a.flow_id = ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }   
    }

    // get all data
    function get_all_overtime_by_limit($params) {
        $sql = "SELECT a.overtime_id, a.overtime_date, a.overtime_st,a.overtime_start, a.overtime_end, d.project_name, 
                d.project_alias, COUNT(b.user_id)'total_personel', e.process_id,e.action_st,e.process_st, e.flow_revisi_id
                FROM surat_lembur a
                INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                INNER JOIN pegawai c ON b.user_id = c.user_id
                INNER JOIN projects d ON d.project_id = a.project_id
                INNER JOIN surat_lembur_process e ON a.overtime_id = e.overtime_id
                INNER JOIN task_flow f ON e.flow_id = f.flow_id
                WHERE a.overtime_st = 'draft' OR e.process_st = 'waiting' AND e.flow_id = ?
                GROUP BY a.overtime_id
                ORDER BY a.overtime_date ASC
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

    // cek status pengajuan (revisi)
    function get_revisi_overtime($params){
        $sql = "SELECT a.process_id, a.overtime_id, a.flow_id, a.flow_revisi_id, b.*
                FROM surat_lembur_process a 
                INNER JOIN task_flow b ON a.flow_revisi_id = b.flow_id
                WHERE a.process_st = 'waiting' AND a.flow_id = ?
                ORDER BY process_id DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get detail overtime
    function get_detail_overtime($params){
        $sql = "SELECT a.overtime_id, a.project_id, b.project_alias, b.project_name, a.overtime_reason, a.overtime_date, a.overtime_start, a.overtime_end, a.mdd, a.mdb_name
                FROM surat_lembur a
                INNER JOIN projects b ON a.project_id = b.project_id
                WHERE a.overtime_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get personil overtime
    function get_id_personil_overtime($params){
        $sql = "SELECT a.user_id
                FROM pegawai a
                INNER JOIN pegawai_lembur b ON a.user_id = b.user_id 
                WHERE b.overtime_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            $role_selected = array();
            foreach ($result as $rec) {
                $role_selected[] = $rec['user_id'];
            }
            return $role_selected;
        } else {
            return array();
        }

    }

    // get personil overtime
    function get_personil_overtime($params){
        $sql = "SELECT a.user_id, a.nama_lengkap
                FROM pegawai a
                INNER JOIN pegawai_lembur b ON a.user_id = b.user_id 
                WHERE b.overtime_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
       
    // get task flow id
    function get_task_flow_id($task_number) {
        $sql = "SELECT flow_id FROM task_flow WHERE group_id='13' AND task_number LIKE ?";
        $query = $this->db->query($sql, $task_number);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return null;
        }
    }

    // get flow count by overtime_id
    function get_flow_count($params) {
        $sql = "SELECT COUNT('flow_id') as jumlah
            FROM surat_lembur_process
            WHERE overtime_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jumlah'];
        } else {
            return $result['jumlah'];
        }
    }

    // get overtime status
    function get_overtime_st($params){
        $sql = "SELECT a.overtime_st 
                FROM surat_lembur a
                INNER JOIN surat_lembur_process b ON a.overtime_id = b.overtime_id
                WHERE a.overtime_id = ? AND b.flow_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['overtime_st'];
        } else {
            return array();
        }
    }

    // check if have references
    function check_if_have_references($params){
        $sql = "SELECT COUNT(process_references_id)'total' FROM surat_lembur_process WHERE overtime_id = ?";
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
        $sql = "SELECT process_references_id FROM surat_lembur_process WHERE overtime_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        } 
    }

    //insert surat lembur
    function insert_surat_lembur($params) {
        return $this->db->insert('surat_lembur',$params);
    }

    //update surat lembur
    function update_surat_lembur($params,$where) {
        return $this->db->update('surat_lembur', $params, $where);
    }

    //delete surat lembur
    function delete_surat_lembur($where) {
        return $this->db->delete('surat_lembur', $where);
    }

    //insert surat lembur process
    function insert_surat_lembur_process($params) {
        return $this->db->insert('surat_lembur_process',$params);
    }

    //update surat lembur process
    function update_surat_lembur_process($params,$where) {
        return $this->db->update('surat_lembur_process', $params, $where);
    }

    //delete surat lembur process
    function delete_surat_lembur_process($where) {
        return $this->db->delete('surat_lembur_process', $where);
    }

    //insert personil
    function insert_personil($params) {
        return $this->db->insert('pegawai_lembur',$params);
    }

    //update personil
    function update_personil($params,$where) {
        return $this->db->update('pegawai_lembur',$params,$where);
    }

    //delete personil
    function delete_personil($where) {
        return $this->db->delete('pegawai_lembur',$where);
    }
}