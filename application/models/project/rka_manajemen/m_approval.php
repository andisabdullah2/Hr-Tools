<?php

class m_approval extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* LIST DATA */

    // get_total_rka_manajemen
    function get_total_rka_manajemen($params) {
        $sql = "SELECT COUNT(b.plan_id) 'total'
                FROM projects a
                INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.plan_id
                    FROM projects_budget_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '15' AND b.task_number = ?
                    GROUP BY a.plan_id
                ) c ON b.plan_id = c.plan_id 
                INNER JOIN projects_budget_process d ON c.process_id = d.process_id 
                WHERE a.jenis_kode_kegiatan = 'A' AND a.project_alias LIKE ? AND d.process_st LIKE ? AND b.send_status != 'draft'
                ORDER BY b.send_date DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_rka_manajemen
    function get_list_rka_manajemen($params) {
        $sql = "SELECT a.project_alias, b.plan_id, b.nilai_pendapatan, b.nilai_pajak, b.nilai_anggaran, b.nilai_biaya, d.process_st, d.process_id
                FROM projects a
                INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.plan_id
                    FROM projects_budget_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '15' AND b.task_number = ?
                    GROUP BY a.plan_id
                ) c ON b.plan_id = c.plan_id 
                INNER JOIN projects_budget_process d ON c.process_id = d.process_id 
                WHERE a.jenis_kode_kegiatan = 'A' AND a.project_alias LIKE ? AND d.process_st LIKE ? AND b.send_status != 'draft'
                ORDER BY b.send_date DESC
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

    /* DETAIL */

    // get_process_by_id
    function get_process_by_id($params) {
        $sql = "SELECT a.plan_id, a.nilai_biaya, a.catatan 'pengajuan_catatan', b.project_name, b.project_alias, c.*
				FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id
                INNER JOIN projects_budget_process c ON a.plan_id = c.plan_id
				WHERE c.process_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_item_and_detail
    function get_list_item_and_detail($params) {
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title, SUM(e.detail_sub_total) 'detail_sub_total', b.kode_akun_alias
                FROM projects_budget_item a
                INNER JOIN data_akun b ON a.kode_akun = b.kode_akun
                INNER JOIN data_perusahaan c ON a.perusahaan_id = c.perusahaan_id
                INNER JOIN project_budget_group d ON a.group_id = d.group_id
                LEFT JOIN projects_budget_detail e ON a.item_id = e.item_id
                WHERE a.plan_id = ?
                GROUP BY a.item_id
                ORDER BY a.item_no ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $rs_id = $query->result_array();
            $query->free_result();
            //
            $result = array();
            foreach ($rs_id as $i => $data) {
                $data['detail'] = $this->get_list_detail($data['item_id']);
                $result[] = $data;
            }
            return $result;
        } else {
            return array();
        }
    }

    // get_list_detail
    function get_list_detail($params) {
        $sql = "SELECT *
                FROM projects_budget_detail
                WHERE item_id = ?
                ORDER BY detail_no ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_process_id
    function get_process_id() {
        return str_replace('.', '', microtime(true));
    }

    // get_flow_by_params
    function get_flow_by_params($params) {
        $sql = "SELECT *
                FROM task_flow
                WHERE group_id = ? AND task_number = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert projects budget process
    function insert_projects_budget_process($params) {
        return $this->db->insert('projects_budget_process', $params);
    }

    // update projects budget process
    function update_projects_budget_process($params, $where) {
        return $this->db->update('projects_budget_process', $params, $where);
    }

    // delete projects budget process
    function delete_projects_budget_process($where) {
        return $this->db->delete('projects_budget_process', $where);
    }

    // update projects budget plan
    function update_projects_budget_plan($params, $where) {
        return $this->db->update('projects_budget_plan', $params, $where);
    }

}
