<?php

class m_client extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /*
     * DATA CLIENT
     */

    // get id
    function get_client_id() {
        $sql = "SELECT client_id 'last_number' FROM projects_clients ORDER BY client_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 99999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($nomor); $i < 5; $i++) {
                $zero .= '0';
            }
            return $zero . $nomor;
        } else {
            // create new number
            return '00001';
        }
    }

    // get total data
    function get_total_client_data($params) 
    {
        $sql = "SELECT COUNT(*)'total'
                FROM projects_clients a
                WHERE client_city  LIKE ? AND client_desc LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get list data
    function get_all_data_client($params) {
        $sql = "SELECT a.*, b.pic_name, b.pic_email 
                FROM projects_clients a
                LEFT JOIN projects_clients_pic b ON a.client_id = b.client_id
                WHERE client_city  LIKE ? AND client_desc LIKE ?
                GROUP BY a.client_id
                ORDER BY a.client_city ASC, a.client_desc ASC
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail client
    function get_client_by_id($params) {
        $sql = "SELECT * FROM projects_clients
                WHERE client_id = ?";
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
    function insert_client($params) {
        return $this->db->insert('projects_clients', $params);
    }

    // update
    function update_client($params, $where) {
        return $this->db->update('projects_clients', $params, $where);
    }

    // hapus
    function delete_client($params) {
        return $this->db->delete('projects_clients', $params);
    }

    /*
     * DATA ALAMAT
     */

    // get id alamat
    function get_alamat_id($param) {
        $sql = "SELECT SUBSTR(alamat_id,6) 'last_number' FROM projects_client_alamat
                WHERE client_id = ?
                ORDER BY alamat_id DESC LIMIT 1";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 999999999999999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($nomor); $i < 15; $i++) {
                $zero .= '0';
            }
            return $param . $zero . $nomor;
        } else {
            // create new number
            return $param . '000000000000001';
        }
    }

    // insert
    function insert_alamat($params) {
        return $this->db->insert('projects_client_alamat', $params);
    }

    // update
    function update_alamat($params, $where) {
        return $this->db->update('projects_client_alamat', $params, $where);
    }

    // hapus
    function delete_alamat($params) {
        return $this->db->delete('projects_client_alamat', $params);
    }

    // get list alamat
    function get_alamat_by_client($params) {
        $sql = "SELECT * FROM projects_client_alamat WHERE client_id = ? ORDER BY alamat_id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get alamat by id
    function get_alamat_by_id($params) {
        $sql = "SELECT * FROM projects_client_alamat WHERE alamat_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * PIC
     */

    // get id pic
    function get_pic_id($param) {
        $sql = "SELECT SUBSTR(pic_id,6) 'last_number' FROM projects_clients_pic
                WHERE client_id = ?
                ORDER BY pic_id DESC LIMIT 1";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 999999999999999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($nomor); $i < 15; $i++) {
                $zero .= '0';
            }
            return $param . $zero . $nomor;
        } else {
            // create new number
            return $param . '000000000000001';
        }
    }

    // get list pic
    function get_pic_by_client($params) {
        $sql = "SELECT * FROM projects_clients_pic WHERE client_id = ? ORDER BY pic_name ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert
    function insert_pic($params) {
        return $this->db->insert('projects_clients_pic', $params);
    }

    // update
    function update_pic($params, $where) {
        return $this->db->update('projects_clients_pic', $params, $where);
    }

    // hapus
    function delete_pic($params) {
        return $this->db->delete('projects_clients_pic', $params);
    }

    // get detail pic by id
    function get_pic_by_id($params) {
        $sql = "SELECT * FROM projects_clients_pic WHERE pic_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}
