<?php

class m_documentation extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    public function get_last_doc_id($project_id) {
        $sql = "SELECT RIGHT(doc_id, 10)'last_number'
                FROM projects_doc
                WHERE project_id LIKE ? ORDER BY doc_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $project_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 9999999999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 10; $i++) {
                $zero .= '0';
            }
            return $project_id . $zero . $number;
        } else {
            // create new number
            return $project_id . '0000000001';
        }
    }

    // generate microtime
    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
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
    function get_total_project_data($params) {
        $sql = "SELECT COUNT(*) AS total 
                FROM projects_doc a
                LEFT JOIN projects b ON a.project_id=b.project_id
                LEFT JOIN projects_clients c ON b.client_id = c.client_id
                WHERE (b.project_name LIKE '?' OR b.project_alias LIKE '?' ) AND a.doc_st LIKE '?' AND YEAR(b.project_start) LIKE '?'
                AND b.struktur_cd LIKE '?' ";
        if(!empty($params[5])){
            $sql .= "AND c.client_nm LIKE ?";
        }
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get list project data
    function get_all_project_data($params, $client_id) {
        $sql = "SELECT a.*,b.project_name, b.project_alias, b.project_start, b.project_end, date(a.mdd)'last_update', jumlah_file
                FROM projects_doc a
                LEFT JOIN projects b ON a.project_id = b.project_id
                LEFT JOIN projects_clients c ON b.client_id = c.client_id
                LEFT JOIN(
                    SELECT doc_id, COUNT(*)'jumlah_file' FROM projects_doc_files GROUP BY doc_id
                )d ON a.`doc_id` = d.doc_id
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ? )
                AND a.doc_st LIKE ? AND YEAR(b.project_start) LIKE ?
                AND b.struktur_cd LIKE ?";
        if(!empty($client_id)){
            $sql .= 'AND client_nm LIKE ? ';
            $params = array($params[0], $params[1], $params[2], $params[3], $params[4], $client_id, $params[5], $params[6]);
        }
        $sql .= "ORDER BY b.project_start DESC, doc_id ASC, doc_st ASC LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get project document by id
    function get_project_document_by_id($params) {
        $sql = "SELECT a.*, b.project_name, b.project_alias, b.project_start, b.project_end, date(a.mdd)'last_update', jumlah_file, c.doc_name, doc_desc, e.client_nm
                FROM projects_doc a 
                LEFT JOIN projects b ON a.project_id = b.project_id
                LEFT JOIN data_jenis_dokumen c ON a.jenis_id = c.jenis_id
                LEFT JOIN(
                    SELECT doc_id, COUNT(*)'jumlah_file' FROM projects_doc_files GROUP BY doc_id
                )d ON a.`doc_id` = d.doc_id
                LEFT JOIN projects_clients e ON b.client_id = e.client_id
                WHERE a.doc_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();

            return $result;
        } else {
            return array();
        }
    }

    // list document
    function get_list_document_by_id($params) {
        $sql = "SELECT a.*
                FROM projects_doc_files a
                WHERE doc_id = ?
                ORDER BY doc_id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // list document
    function get_file_by_id($params) {
        $sql = "SELECT a.*
                FROM projects_doc_files a
                WHERE files_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all project
    function get_all_data_projects() {
        $sql = "SELECT * FROM projects";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get project by tahun
    function get_project_by_tahun($params) {
        $sql = "SELECT * FROM projects WHERE YEAR(project_start) LIKE ? ORDER BY project_start DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all jenis dokumen
    function get_all_data_jenis_dokumen() {
        $sql = "SELECT * FROM data_jenis_dokumen";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert 
    function insert($params) {
        return $this->db->insert('projects_doc', $params);
    }

    function insert_file($params) {
        return $this->db->insert('projects_doc_files', $params);
    }

    // update 
    function update($params, $where) {
       return $this->db->update('projects_doc', $params, $where);
    }

    function update_file($params, $where) {
       return $this->db->update('projects_doc_files', $params, $where);
    }

    // delete
    function delete($params) {
        return $this->db->delete('projects_doc', $params);
    }

    function delete_files($params) {
        return $this->db->delete('projects_doc_files', $params);
    }

}