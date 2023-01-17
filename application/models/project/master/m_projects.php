<?php

class M_projects extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_last_project_id($prefix, $params) {
        $sql = "SELECT RIGHT(project_id,4)'last_number'
                FROM projects
                WHERE project_id LIKE ?
                ORDER BY project_id DESC
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
            for ($i = strlen($number); $i < 10; $i++) {
                $zero .= '0';
            }
            return $prefix . $zero . $number;
        } else {
            // create new number
            return $prefix . '0001';
        }
    }

    // get list tahun
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(project_start)'tahun'
                        FROM projects
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

    // get total project data
    function get_total_project_data($params) 
    {
        $sql = "SELECT COUNT(*) 'total'
                FROM projects a 
                LEFT JOIN projects_clients b ON a.client_id = b.client_id
                WHERE (a.project_alias LIKE '%?%' OR a.project_name LIKE '%?%' )
                AND a.struktur_cd LIKE '%?%' AND YEAR(a.project_start) LIKE '%?%'";
        
        if(!empty($params[4]))
        {
            $sql .= "AND client_nm LIKE '?' ";
        }
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) 
        {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } 
        else 
        {
            return array();
        }
    }

    function get_all_project_data($params, $client_id) {
        $sql = "SELECT a.*, b.client_nm, YEAR(project_start)'tahun'
                FROM projects a
                LEFT JOIN projects_clients b ON a.client_id = b.client_id
                WHERE (project_alias LIKE ? OR project_name LIKE ? )
                AND a.struktur_cd LIKE ? AND YEAR(project_start) LIKE ?";
        if(!empty($client_id)){
            $sql .= 'AND client_nm LIKE ? ';
            $params = array($params[0], $params[1], $params[2], $params[3], $client_id, $params[4], $params[5]);
        }
        $sql .= "ORDER BY project_start DESC, client_nm ASC, project_name ASC LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    // get project data by id
    function get_project_data_by_id($params) {
        $sql = "SELECT a.*, b.client_nm, d.struktur_nama, struktur_singkatan, e.nama_kegiatan
                FROM projects a 
                LEFT JOIN projects_clients b ON a.client_id = b.client_id
                LEFT JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                LEFT JOIN data_jenis_kegiatan e ON a.jenis_kode_kegiatan = e.jenis_kode_kegiatan
                WHERE project_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all active project
    function get_all_active_projects() {
        $sql = "SELECT * FROM projects WHERE project_st <> 'closed'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all client
    function get_all_data_client() {
        $sql = "SELECT * FROM projects_clients";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // is_exist_project_code
    function is_exist_project_code($params) {
        $sql = "SELECT project_alias FROM projects WHERE project_alias = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }

    // get project leader
    function get_preferences_by_group() {
        $sql = "SELECT DISTINCT project_st FROM projects ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all department
    function get_all_department() {
        $sql = "SELECT * FROM data_struktur_organisasi ORDER BY struktur_cd ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all jenis kegiatan
    function get_all_jenis_kegiatan() {
        $sql = "SELECT * FROM data_jenis_kegiatan ORDER BY jenis_kode_kegiatan ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert project
    function insert_project($params) {
        return $this->db->insert('projects', $params);
    }

    // update project
    function update_project($params, $where) {
       return $this->db->update('projects', $params, $where);
    }

    // delete
    function delete_project($params) {
        return $this->db->delete('projects', $params);
    }
    
}