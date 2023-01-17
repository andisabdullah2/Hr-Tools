<?php

class m_approval extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // generate microtime
    public function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }

    // ambil list flow pengajuan
    public function get_list_flow($params)
    {
        // set query
        $sql="SELECT b.*, c.project_alias, a.process_st as 'flow_process_st', a.action_st , a.process_id
              FROM projects_budget_process a 
              INNER JOIN projects_budget_plan b ON a.plan_id = b.plan_id 
              INNER JOIN projects c ON b.project_id = c.project_id 
              INNER JOIN task_flow d ON a.flow_id = d.flow_id 
              WHERE a.flow_id = ? AND d.group_id = ? AND c.jenis_kode_kegiatan= 'B'
              AND (c.project_name LIKE ? OR c.project_alias LIKE ?) AND a.process_st like ? 
              ORDER BY a.mdd
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

    // ambil total list flow
    public function get_total_flow($params)
    {
        $sql="SELECT COUNT(*)'total'
              FROM projects_budget_process a 
              INNER JOIN projects_budget_plan b ON a.plan_id = b.plan_id 
              INNER JOIN projects c ON b.project_id = c.project_id 
              INNER JOIN task_flow d ON a.flow_id = d.flow_id 
              WHERE a.flow_id = ? AND d.group_id = ? AND c.jenis_kode_kegiatan= 'B'
              AND (c.project_name LIKE ? OR c.project_alias LIKE ?) AND a.process_st like ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get detail flow by plant
    public function get_detail_flow($params)
    {
        $sql = "SELECT b.*, c.project_alias, c.project_name, a.catatan  as 'catatan_proses', a.process_id, d.task_number FROM projects_budget_process a
                INNER JOIN projects_budget_plan b ON a.plan_id = b.plan_id
                INNER JOIN projects c ON b.project_id = c.project_id
                INNER JOIN task_flow d ON a.flow_id = d.flow_id
                WHERE a.process_id = ? AND a.flow_id = ? AND d.group_id = ?  AND c.jenis_kode_kegiatan= 'B'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // get item
            $result['item'] = $this->get_list_item_and_detail(array($result['plan_id']));
            return $result;
        } else {
            return array();
        }
    }

    // get detail flow by plant
    public function get_detail_flow_by_plant($params)
    {
        $sql = "SELECT b.*, c.project_alias, c.project_name, a.catatan  as 'catatan_proses', a.process_id FROM projects_budget_process a
                INNER JOIN projects_budget_plan b ON a.plan_id = b.plan_id
                INNER JOIN projects c ON b.project_id = c.project_id
                INNER JOIN task_flow d ON a.flow_id = d.flow_id
                WHERE a.plan_id = ? AND a.flow_id = ? AND d.group_id = ?  AND c.jenis_kode_kegiatan= 'B'";
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

    // insert project budget plan process
    public function insert_project_budget_process($params){
        return $this->db->insert('projects_budget_process', $params);
    }

    // update project budget plan process
    public function update_project_budget_process($params,$where){
        return $this->db->update('projects_budget_process', $params, $where);
    }

    // delete project budget plan process
    public function delete_project_budget_process($where){
        $sql="DELETE  a.* FROM projects_budget_process a 
              INNER JOIN task_flow b ON a.flow_id = b.flow_id
              WHERE b.task_number > ?";
        return $query = $this->db->query($sql, $where);
    }

    // update projects budget_plan
    public function update_projects_budget_plant($params, $where) {
        return $this->db->update('projects_budget_plan', $params, $where);
    }
}
