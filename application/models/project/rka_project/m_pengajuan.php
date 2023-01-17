<?php

class m_pengajuan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // <editor-fold defaultstate="collapsed" desc="CRUD Project Budget Plan">
    // get last plant id
    public function get_last_plant_id($prefix, $params) {
        $sql = "SELECT RIGHT(plan_id,4)'last_number'
                FROM projects_budget_plan
                WHERE LEFT(plan_id, 6) LIKE ?
                ORDER BY plan_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 9999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 4; $i++) {
                $zero .= '0';
            }
            return $prefix . $zero . $number;
        } else {
            // create new number
            return $prefix . '0001';
        }
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

    // get projek id by jenis kode kegiatan
    public function get_all_project(){
        // query
        $sql = "SELECT * FROM projects WHERE jenis_kode_kegiatan = 'B'";
        // execute
        $query = $this->db->query($sql);
        // cek result
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
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

    // tambah projects budget plan
    public function insert_projects_budget_plan($params)
    {
        return $this->db->insert('projects_budget_plan', $params);
    }

    // update projects budget_plan
    public function update_projects_budget_plant($params, $where) {
        return $this->db->update('projects_budget_plan', $params, $where);
    }

    // delete projects budget plan
    function delete_projects_budget_plan($where) {
        return $this->db->delete('projects_budget_plan', $where);
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="CRUD Project Budget Proses">
    // generate microtime
    public function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }

    // insert project budget plan process
    public function insert_project_budget_process($params){
        return $this->db->insert('projects_budget_process', $params);
    }

    // update project budget plan process
    public function update_project_budget_process($params,$where){
        return $this->db->update('projects_budget_process', $params,$where);
    }

    // get detail data project budget plan process
    public function get_detail_project_budget_process_by_id($params)
    {
        $sql = "SELECT * FROM projects_budget_process 
                WHERE plan_id = ? AND flow_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="CRUD Project Budget Item">
    // get last plant id
    public function get_last_item_id($prefix, $params) {
        $sql = "SELECT RIGHT(item_id,2)'last_number'
                FROM projects_budget_item
                WHERE LEFT(item_id, 10) LIKE ?
                ORDER BY item_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 99) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 2; $i++) {
                $zero .= '0';
            }
            return $prefix . $zero . $number;
        } else {
            // create new number
            return $prefix . '01';
        }
    }

    // ambil item no terakhir
    public function get_last_item_no($params)
    {
        $sql = "SELECT RIGHT(item_id,2)'last_number'
                FROM projects_budget_item
                WHERE LEFT(item_id, 10) LIKE ?
                ORDER BY item_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            return $number;
        } else {
            // create new number
            return 1;
        }
    }
    // ambil list group project budget
    public function get_project_budget_group()
    {
        $sql = "SELECT * FROM project_budget_group";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // ambil data perusahaan dan akun
    public function get_all_perusahaan()
    {
        $sql = "SELECT * FROM  data_perusahaan ORDER BY perusahaan_nama";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // ambil list project budget item
    public function get_list_project_budget_item($params)
    {
        $sql = "SELECT a.*, b.group_title, c.kode_akun_alias, c.nama_akun, d.perusahaan_nama FROM projects_budget_item a
                INNER JOIN project_budget_group b ON a.group_id = b.group_id
                INNER JOIN data_akun c ON a.kode_akun = c.kode_akun
                INNER JOIN data_perusahaan d ON a.perusahaan_id = d.perusahaan_id
                WHERE a.plan_id= ?
                ORDER BY item_no";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get  project budget item berdasarkan id
    public function get_project_budget_item_by_id($params)
    {
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title
                FROM projects_budget_item a
                INNER JOIN data_akun b ON a.kode_akun = b.kode_akun
                INNER JOIN data_perusahaan c ON a.perusahaan_id = c.perusahaan_id
                INNER JOIN project_budget_group d ON a.group_id = d.group_id
                WHERE a.item_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get sum item total berdasarkan plan id
    public function get_sum_item_total_by_plan_id($params)
    {
        $sql = "SELECT SUM(item_total) AS 'total' FROM projects_budget_item 
                WHERE plan_id  = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // tambah projects budget item
    public function insert_projects_budget_item($params)
    {
        return $this->db->insert('projects_budget_item', $params);
    }

    // ubah projects budget item
    public function update_projects_budget_item($params, $where)
    {
        return $this->db->update('projects_budget_item', $params, $where);
    }

    // delete project budget item
    public function delete_project_budget_item($where)
    {
        return $this->db->delete('projects_budget_item', $where);
    }

    // update_item_total
    public function update_item_total($params) {
        // item
        $sql = "UPDATE projects_budget_item a 
                LEFT JOIN (
                    SELECT SUM(detail_sub_total) 'jumlah', item_id FROM projects_budget_detail
                    GROUP BY item_id
                ) b ON a.item_id = b.item_id
                SET a.item_total = b.jumlah
                WHERE a.item_id = ?";
        $query = $this->db->query($sql, $params);
        return;
    }

    // get list akun_perusahaan
    public function get_list_akun_perusahaan($params) {
        $sql = "SELECT kode_akun, kode_akun_alias, nama_akun, group_kode
                FROM data_akun
                WHERE perusahaan_id = ?
                ORDER BY nama_akun ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list akun_perusahaan
    public function get_list_akun_perusahaan_by_level($params) {
        $sql = "SELECT kode_akun, kode_akun_alias, nama_akun, group_kode
                FROM data_akun
                WHERE perusahaan_id = ? AND level_akun = ?
                ORDER BY nama_akun ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_akun_by_level($params)
    {
        $sql = "SELECT kode_akun, kode_akun_alias, nama_akun, group_kode
                FROM data_akun
                WHERE group_kode = ? AND level_akun = ?
                ORDER BY nama_akun ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="CRUD Project Budget Detail">
    // get last detail id
    public function get_last_detail_id($prefix, $params) {
        $sql = "SELECT RIGHT(detail_id,3)'last_number'
                FROM projects_budget_detail
                WHERE LEFT(detail_id, 12) LIKE ?
                ORDER BY detail_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 99) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 2; $i++) {
                $zero .= '0';
            }
            return $prefix . $zero . $number;
        } else {
            // create new number
            return $prefix . '01';
        }
    }

    // ambil list project budget item dengan detail no terbaru
    public function get_list_project_budget_item_with_detail_no($params)
    {
        $sql = "SELECT a.*, b.group_title, c.kode_akun_alias, c.nama_akun, d.perusahaan_nama 
                FROM 
                (
                  SELECT *,
                  IFNULL((
                      SELECT detail_no AS detail_no FROM projects_budget_detail 
                      WHERE item_id = pbi.item_id ORDER BY detail_no DESC LIMIT 1
                  ),0) + 1 AS detail_no,
                  (
                      SELECT SUM(detail_sub_total)  
                      FROM projects_budget_detail 
                      WHERE item_id = pbi.item_id 
                  ) AS total_detail
                 FROM projects_budget_item pbi
                ) AS a
                INNER JOIN project_budget_group b ON a.group_id = b.group_id
                INNER JOIN data_akun c ON a.kode_akun = c.kode_akun
                INNER JOIN data_perusahaan d ON a.perusahaan_id = d.perusahaan_id
                WHERE a.plan_id= ?
                ORDER BY item_no";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // ambil list project budget detail berdasarkan item id
    public function get_list_project_budget_detail($params)
    {
        $sql = "SELECT * FROM projects_budget_detail WHERE item_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // tambah projects budget detail
    public function insert_projects_budget_detail($params)
    {
        return $this->db->insert('projects_budget_detail', $params);
    }

    // ubah projects budget detail
    public function update_projects_budget_detail($params, $where)
    {
        return $this->db->update('projects_budget_detail', $params, $where);
    }

    // delete project budget detail
    public function delete_project_budget_detail($where)
    {
        return $this->db->delete('projects_budget_detail', $where);
    }
    // </editor-fold>

}
