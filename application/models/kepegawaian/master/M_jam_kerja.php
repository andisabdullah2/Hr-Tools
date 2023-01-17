<?php

class M_jam_kerja extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */

    //get total jam
    public function get_total_jam_kerja() {
        $sql = "SELECT COUNT(*) 'total' FROM data_jam_kerja";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return null;
        }
    }
    
    // get data jam limit
    public function get_jam_kerja_limit($params) {
	$sql = "SELECT b.* FROM data_jam_kerja b
                ORDER BY nama ASC, jam_mulai ASC
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
    
    
    // get detail jam by id
    public function get_detail_jam_kerja_by_id($params) {
        $sql = "SELECT * FROM data_jam_kerja WHERE jam_kerja_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }      
    
   
    
    //insert
    public function insert($params) {
        return $this->db->insert('data_jam_kerja', $params);
    }
    // update jam
    public function update($params, $where) {
        return $this->db->update('data_jam_kerja', $params, $where);
    }
    // delete jam
    public function delete_jam_kerja($where) {
        return $this->db->delete('data_jam_kerja', $where);
    }

    
    // create jam id
    public function get_jam_kerja_id() {
       // variabel
        $lebar = 5;
        $awalan = '';
        // tampil id terahir
        $sql = "SELECT RIGHT(jam_kerja_id, $lebar)'last_number' FROM data_jam_kerja ORDER BY jam_kerja_id DESC LIMIT 5";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 99999) {
                return false;
            }
        } else {
            $nomor = 1;
        }
        //gabung angka
        if ($lebar > 0)
            $angka = $awalan . str_pad($nomor, $lebar, "0", STR_PAD_LEFT);
        else
            $angka = $awalan . $nomor;
        return $angka;
    }  
}
