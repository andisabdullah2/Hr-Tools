<?php

class m_daily_notes extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    /*
     * MY TASK
     */

    // get my task today
    function get_my_task_today($params) {
        $sql = "SELECT a.*, b.full_name, c.project_alias
                FROM users_target a
                LEFT JOIN users b ON a.post_by = b.user_id
                LEFT JOIN projects c ON a.project_id = c.project_id
                WHERE a.user_id = ? AND a.target_st LIKE ? AND a.target_level LIKE ? AND a.target_date = CURRENT_DATE
                ORDER BY target_date ASC";
        $query = $this->db->query($sql, $params);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get my task weeks
    function get_my_task_weeks($params) {
        $sql = "SELECT a.*, b.full_name, c.project_alias
                FROM users_target a
                LEFT JOIN users b ON a.post_by = b.user_id
                LEFT JOIN projects c ON a.project_id = c.project_id
                WHERE a.user_id = ? AND target_st LIKE ? AND target_level LIKE ? 
                AND target_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 6 DAY)
                AND target_date <> CURRENT_DATE
                ORDER BY target_date ASC";
        $query = $this->db->query($sql, $params);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get my task others
    function get_my_task_others($params) {
        $sql = "SELECT a.*, b.full_name, c.project_alias, IF(target_date < CURRENT_DATE, 1, 0)'expired'
                FROM users_target a
                LEFT JOIN users b ON a.post_by = b.user_id
                LEFT JOIN projects c ON a.project_id = c.project_id
                WHERE a.user_id = ? AND target_st LIKE ? AND target_level LIKE ?
                AND (target_date > DATE_ADD(CURRENT_DATE, INTERVAL 6 DAY) OR target_date < CURRENT_DATE)
                ORDER BY target_date ASC";
        $query = $this->db->query($sql, $params);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * DAILY NOTES
     */

    // get project by user
    function get_list_project() {
        $sql = "SELECT a.*, client_nm
                FROM projects a
                INNER JOIN clients b ON a.client_id = b.client_id
                ORDER BY YEAR(project_start) DESC, a.project_alias ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get task by user year month
    function get_task_by_year_month($params) {
        $sql = "SELECT a.*, b.project_alias
                FROM users_task a
                INNER JOIN projects b ON a.project_id = b.project_id
                WHERE user_id = ? AND YEAR(task_date) = ? AND MONTH(task_date) = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail task
    function get_detail_task_by_id($params) {
        $sql = "SELECT * FROM users_task
                WHERE task_id = ? AND user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // add task
    function add_task($params) {
        // sql
        $sql = "INSERT INTO users_task (task_id, user_id, project_id, project_modul, task_desc, task_link, task_date, mdd) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        return $this->db->query($sql, $params);
    }

    // update
    function update_task($params) {
        // sql
        $sql = "UPDATE users_task SET project_id = ?, project_modul = ?, 
                task_desc = ?, task_link = ?, mdd = NOW()
                WHERE task_id = ? AND user_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete
    function delete_task($params) {
        // sql
        $sql = "DELETE FROM users_task  WHERE task_id = ? AND user_id = ?";
        return $this->db->query($sql, $params);
    }

    /*
     * UTILITY
     */

    // get list department
    function get_list_department() {
        $sql = "SELECT * FROM department ORDER BY department_name ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

    // get list users
    function get_list_users_working() {
        $sql = "SELECT * FROM users WHERE employee_st = 'working' ORDER BY full_name ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

    // get detail assignment by id
    function get_detail_assignments_by_id($params) {
        $sql = "SELECT a.*, full_name, project_name, client_desc
                FROM users_target a
                INNER JOIN users b ON a.user_id = b.user_id
                INNER JOIN projects c ON a.project_id = c.project_id
                INNER JOIN clients d ON c.client_id = d.client_id
                WHERE target_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // update laporan target kerja
    function update_target_kerja($params) {
        $sql = "UPDATE users_target SET result_st = ?, result_date = ?, result_notes = ? 
                WHERE target_id = ? AND user_id = ?";
        return $this->db->query($sql, $params);
    }

}