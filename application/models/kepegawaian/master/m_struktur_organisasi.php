<?php

class M_struktur_organisasi extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  /* CORE
     * 
     */

  //get total struktur organisasi
  function get_total_struktur($params)
  {
    $sql = "SELECT COUNT(*) 'total' FROM data_struktur_organisasi
                WHERE IF(`struktur_induk` != '', `struktur_induk`, `struktur_cd`) LIKE ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      return $result['total'];
    } else {
      return null;
    }
  }

  // get all struktur by limit
  function get_all_struktur_by_limit($params)
  {
    $sql = "SELECT * FROM data_struktur_organisasi 
                WHERE IF(`struktur_induk` != '', `struktur_induk`, `struktur_cd`) LIKE ?
                ORDER BY struktur_cd ASC LIMIT ?, ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  //get detail struktur by struktur cd
  function get_detail_struktur_by_cd($params)
  {
    $sql = "SELECT * FROM data_struktur_organisasi WHERE struktur_cd = ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  // insert struktur organisasi
  function insert_struktur_organisasi($params)
  {
    return $this->db->insert('data_struktur_organisasi', $params);
  }

  //update struktur organisasi
  function update_struktur_organisasi($params, $where)
  {
    return $this->db->update('data_struktur_organisasi', $params, $where);
  }

  // delete data struktur
  function delete_struktur($params)
  {
    return $this->db->delete('data_struktur_organisasi', $params);
  }

  /* UTILITY
     * 
     */

  // get all struktur
  function get_all_struktur()
  {

    $sql = "SELECT * FROM data_struktur_organisasi";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }
// Get Struktur Induk
  function get_struktur_induk($id){
    $hasil=$this->db->query("SELECT * FROM data_struktur_organisasi WHERE struktur_level='$id'");
    return $hasil->result();
}


  //get edit detail struktur by struktur cd
  function get_edit_detail_struktur_by_cd($params)
  {
    $sql = "SELECT * FROM data_struktur_organisasi WHERE struktur_cd = ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  //get level label
  function get_level_label()
  {
    $sql = "SELECT DISTINCT struktur_level_label FROM data_struktur_organisasi";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  //get struktur induk by cd
  function get_struktur_induk_by_cd($params)
  {
    $sql = "SELECT struktur_induk FROM data_struktur_organisasi";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  //get struktur cd by induk
  function get_last_struktur_cd_by_induk($params)
  {
    $sql = "SELECT struktur_cd FROM data_struktur_organisasi
              WHERE struktur_induk = ? 
              ORDER BY struktur_cd DESC LIMIT 1";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  //get last struktur induk
  function get_last_struktur_induk()
  {
    $sql = "SELECT struktur_cd FROM data_struktur_organisasi 
              WHERE struktur_level = '0'
              ORDER BY struktur_cd DESC LIMIT 1";
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
