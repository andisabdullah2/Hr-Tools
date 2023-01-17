<?php

class M_perusahaan extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // get id
    function get_perusahaan_id()
    {
        $sql = "SELECT struktur_cd 'last_number' FROM data_perusahaan ORDER BY struktur_cd DESC LIMIT 1";
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

    // get all company
    function get_all_perusahaan()
    {
        $sql = "SELECT * FROM data_perusahaan ORDER BY perusahaan_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function filter_perusahaan($params)
    {
        $sql = "SELECT * FROM data_perusahaan WHERE perusahaan_nama LIKE ? ORDER BY perusahaan_nama ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get data_perusahaan by id
    function get_detail_perusahaan_by_id($params)
    {
        $sql = "SELECT * FROM data_perusahaan WHERE struktur_cd = ?";
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
    function insert($params)
    {
        return $this->db->insert('data_perusahaan', $params);
    }

    // update
    function update($params, $where)
    {
        return $this->db->update('data_perusahaan', $params, $where);
    }

    // delete
    function delete($params)
    {
        return $this->db->delete('data_perusahaan', $params);
    }
}
