<?php

class M_media extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  //get total Media
  function get_total_media($params)
  {
    $sql = "SELECT COUNT(*) 'total' FROM data_media WHERE nama LIKE ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->row_array();
      $query->free_result();
      return $result['total'];
    } else {
      return null;
    }
  }
  // get all Data Media by limit
  function get_all_media_by_limit($params)
  {
    $sql = "SELECT * FROM data_media 
      WHERE IF(`nama` != '', `nama`, `media_id`) LIKE ?
      ORDER BY media_id ASC LIMIT ?, ?";
    $query = $this->db->query($sql, $params);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  // get all Media 
  function get_all_files()
  {

    $sql = "SELECT * FROM data_media";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result_array();
      $query->free_result();
      return $result;
    } else {
      return array();
    }
  }

  // generate media_id
  public function generate_media_id()
  {
    // cari id terakhir
    $sql = "SELECT media_id  AS last_number 
                FROM `data_media`
                ORDER BY media_id DESC LIMIT 1";
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
  function insert_media($params)
  {
    return $this->db->insert('data_media', $params);
  }

  // update
  function update_media($params, $where)
  {
    return $this->db->update('data_media', $params, $where);
  }
  // delete 
  function delete($params)
  {
    return $this->db->delete('data_media', $params);
  }


  //get get detail Media by id
  function get_detail_media_by_id($params)
  {
    $sql = "SELECT * FROM data_media WHERE media_id = ?";
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
