<?php

class m_monitoring extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get_total_monitoring_rka_manajemen
    function get_total_monitoring_rka_manajemen($params) {
        $sql = "SELECT COUNT(b.plan_id) 'total'
				FROM projects a
				INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
				WHERE a.jenis_kode_kegiatan = 'A' AND a.project_alias LIKE ? AND b.plan_status LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_monitoring_rka_manajemen
    function get_list_monitoring_rka_manajemen($params) {
        $sql = "SELECT a.project_alias, b.plan_id, b.nilai_pendapatan, b.nilai_pajak, b.nilai_anggaran, b.nilai_biaya, b.plan_status
				FROM projects a
				INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
				WHERE a.jenis_kode_kegiatan = 'A' AND a.project_alias LIKE ? AND b.plan_status LIKE ?
				ORDER BY b.create_date DESC
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

    // get plan by id
    function get_plan_by_id($params) {
        $sql = "SELECT a.*, b.project_name, b.project_alias
				FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id
				WHERE a.plan_id = ?";
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

    // get_list_flow_plan
    function get_list_flow_plan($params) {
        $sql = "SELECT a.task_desc, c.process_st, c.catatan
                FROM task_flow a
                LEFT JOIN (
                    SELECT MAX(process_id) 'process_id', flow_id, plan_id
                    FROM projects_budget_process
                    GROUP BY flow_id, plan_id
                ) b ON a.flow_id = b.flow_id AND b.plan_id = ?
                LEFT JOIN projects_budget_process c ON b.process_id = c.process_id
                WHERE a.group_id = ?
                ORDER BY a.task_number ASC";
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
