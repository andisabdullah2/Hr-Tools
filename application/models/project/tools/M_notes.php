<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notes extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // generate microtime
    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }

    function get_all_project() {
        $sql = "SELECT * FROM projects ORDER BY project_start DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }   

    function get_list_project($params) {
        $sql = "SELECT a.*, total FROM projects a
                LEFT JOIN (
                  SELECT project_id, COUNT(note_id)'total' 
                  FROM projects_notes GROUP BY project_id
                )b ON a.`project_id` = b.project_id
                WHERE a.project_id LIKE ?
                ORDER BY project_start DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_bulan_tahun_by_project($params) {
        $sql = "SELECT a.*
                FROM projects_notes a 
                WHERE a.project_id = ? GROUP BY YEAR(a.note_start_date), MONTH(a.note_start_date)";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_note_by_date($params) {
        $sql = "SELECT a.*
                FROM projects_notes a 
                WHERE project_id = ? AND a.note_start_date LIKE ? ORDER BY note_start_date ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }  

    function get_note_by_id($params) {
        $sql = "SELECT a.*
                FROM projects_notes a 
                WHERE note_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }   

    function get_detail_project($params) {
        $sql = "SELECT a.*, total FROM projects a
                LEFT JOIN (
                  SELECT project_id, COUNT(note_id)'total' 
                  FROM projects_notes GROUP BY project_id
                )b ON a.`project_id` = b.project_id
                WHERE a.project_id = ?
                ORDER BY project_start DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert 
    function insert($params) {
        return $this->db->insert('projects_notes', $params);
    }

    // update 
    function update($params, $where) {
       return $this->db->update('projects_notes', $params, $where);
    }

    // delete
    function delete($params) {
        return $this->db->delete('projects_notes', $params);
    }

}