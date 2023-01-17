<?php

class M_files_karyawan extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  //get total files karyawan
  function get_total_files($params)
  {
    $sql = "SELECT COUNT(*) 'total' FROM pegawai_files WHERE file_title LIKE ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      return $result['total'];
    } else {
      return null;
    }
  }
  // get all files by limit
  function get_all_files_by_limit($params)
  {
    $sql = "SELECT * FROM pegawai_files 
      WHERE IF(`file_title` != '', `file_title`, `pegawai_files_id`) LIKE ?
      ORDER BY pegawai_files_id ASC LIMIT ?, ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  // get all files 
  function get_all_files()
  {

    $sql = "SELECT * FROM pegawai_files";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  // generate pegawai_files_id 
  public function generate_pegawai_files_id()
  {
    // cari id terakhir
    $sql = "SELECT pegawai_files_id  AS last_number 
                FROM `pegawai_files`
                ORDER BY pegawai_files_id DESC LIMIT 1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      $nomor = intval($result['last_number']) + 1;
      $nomor = str_pad($nomor, strlen($result['last_number']), '0', STR_PAD_LEFT);
    } else {
      $nomor = '00001';
    }
    return $nomor;
  }


  // insert
  function insert_files($params)
  {
    return $this->db->insert('pegawai_files', $params);
  }

  // update
  function update_files($params, $where)
  {
    return $this->db->update('pegawai_files', $params, $where);
  }
  // delete hari libur
  function delete($params)
  {
    return $this->db->delete('pegawai_files', $params);
  }


  //get get detail files by id
  function get_detail_files_by_id($params)
  {
    $sql = "SELECT * FROM pegawai_files WHERE pegawai_files_id = ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }
  
  function get_allowed_files($params)
  {
    $sql = "SELECT file_allowed FROM pegawai_files";
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
