<?php

class m_monitoring extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // ambil list pengajuan rka project
    public function get_list_pengajuan($params)
    {
        // query
        $sql = "SELECT a.*, b.project_alias 
                FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ?) AND a.plan_status LIKE ? AND b.jenis_kode_kegiatan = 'B'
                ORDER BY b.project_alias 
                LIMIT ?, ?";
        // execute
        $query = $this->db->query($sql, $params);
        // cek result
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // ambil jumlah pengajuan
    public function get_total_pengajuan($params)
    {
        $sql = "SELECT COUNT(*)'total'
                FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ?) AND a.plan_status LIKE ? AND b.jenis_kode_kegiatan = 'B'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get detail project budget plant id
    public function get_detail_plant_by_id($params)
    {
        $sql = "SELECT a.*, b.project_name, b.project_alias 
                FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id WHERE plan_id = ?";
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
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title, SUM(e.detail_sub_total) 'detail_sub_total'
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
            // get detail
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
        $sql = "SELECT a.task_number, a.task_desc, b.process_st, b.catatan
                FROM task_flow a
                LEFT JOIN projects_budget_process b ON a.flow_id = b.flow_id AND b.plan_id = ?
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
