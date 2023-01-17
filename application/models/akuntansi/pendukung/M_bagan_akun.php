<?php

class M_bagan_akun extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /* CORE
    *
    */

    // get total akun
    function get_total_akun($params)
    {
        $sql = "SELECT COUNT(*)'total'
                FROM data_akun a
                WHERE nama_akun LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get total akun perusahaan
    function get_total_akun_perusahaan() {
        $sql = "SELECT COUNT(data_id) AS total 
                FROM data_akun_perusahaan";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get list data akun with limit
    function get_all_data_akun($params) {
        $sql = "SELECT *
                FROM data_akun
                WHERE nama_akun LIKE ?
                ORDER BY kode_akun ASC
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

    // get list data perusahaan
    function get_data_akun_perusahaan($params) {
        $sql = "SELECT *
                FROM data_akun_perusahaan
                WHERE data_id LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    // get list data perusahaan
    function get_detail_akun_perusahaan($params) {
        $sql = "SELECT a.*,b.struktur_nama,b.struktur_cd,c.kode_akun,c.nama_akun
                FROM data_akun_perusahaan a
                INNER JOIN data_struktur_organisasi b
                ON a.struktur_cd = b.struktur_cd
                INNER JOIN data_akun c
                ON a.kode_akun = c.kode_akun
                WHERE a.kode_akun = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list data struktur organisasi
    function get_all_data_struktur() {
        $sql = "SELECT struktur_cd, struktur_nama, struktur_level
                FROM data_struktur_organisasi
                WHERE struktur_level = 0
                ORDER BY struktur_cd ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }     
    }


    // get list data akun
    function get_all_data() {
        $sql = "SELECT * FROM data_akun";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert data akun
    function insert($params) {
        return $this->db->insert('data_akun', $params);
    }

    // insert data akun perusahaan
    function insert_perusahaan($params) {
        return $this->db->insert('data_akun_perusahaan', $params);
    }

    // get last kode akun by level
    function get_last_kode_akun_by_level($params){
      $sql = "SELECT kode_akun FROM data_akun
              WHERE level_akun = ? 
              ORDER BY kode_akun DESC LIMIT 1";
      $query = $this->db->query($sql, $params);
      if ($query->num_rows() > 0) {
        $result = $query->result_array();
        $query->free_result();
        return $result;
      } else {
        return array();
      }
    }


    function get_last_kode_akun_by_substr($params){
      $sql = "SELECT kode_akun FROM data_akun
              WHERE substr(kode_akun, ?, ?) = ? AND level_akun = ?
              ORDER BY kode_akun DESC LIMIT 1";
      $query = $this->db->query($sql, $params);
      if ($query->num_rows() > 0) {
        $result = $query->result_array();
        $query->free_result();
        return $result;
      } else {
        return array();
      }
    }

    // get data akun by level
    function get_data_akun_by_level($params){
      $sql = "SELECT kode_akun, nama_akun, level_akun
                FROM data_akun
              WHERE level_akun = ? 
              ORDER BY mdd ASC";
      $query = $this->db->query($sql, $params);
      if ($query->num_rows() > 0) {
        $result = $query->result_array();
        $query->free_result();
        return $result;
      } else {
        return array();
      }
    }

    // check if level_akun exists
    function check_if_level_akun_exists($params) {
        $sql = "SELECT level_akun FROM data_akun 
                WHERE level_akun = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }

    // check if level_akun exists
    function check_if_struktur_cd_exists($params) {
        $sql = "SELECT struktur_cd FROM data_akun_perusahaan 
                WHERE kode_akun = ? AND struktur_cd = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }

    // get detail data akun
    function get_detail_data_akun($params) {
        $sql = "SELECT *
                FROM data_akun
                WHERE kode_akun LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // update data akun
    function update($params, $where) {
       return $this->db->update('data_akun', $params, $where);
    }

    // update data akun perusahaan
    function update_perusahaan($params, $where) {
       return $this->db->update('data_akun_perusahaan', $params, $where);
    }
    
    // delete data akun
    function delete($params) {
        return $this->db->delete('data_akun', $params);
    }

    // delete data akun perusahaan
    function delete_perusahaan($params) {
        return $this->db->delete('data_akun_perusahaan', $params);
    }

    // get last data akun perusahaan
    function get_last_data_id() {
      $sql = "SELECT data_id FROM data_akun_perusahaan
              ORDER BY data_id DESC LIMIT 1";
      $query = $this->db->query($sql);
      if ($query->num_rows() > 0) {
        $result = $query->result_array();
        $query->free_result();
        return $result;
      } else {
        return array();
      }
    }
}