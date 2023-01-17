<?php

class m_jadwal_kerja extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */

    //get total jadwal
    public function get_total_jadwal_kerja() {
        $sql = "SELECT COUNT(*) 'total' FROM data_jadwal_kerja";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return null;
        }
    }
    
    // get data jadwal limit
    public function get_jadwal_kerja_limit($params) {
	$sql = "SELECT b.* FROM data_jadwal_kerja b
                ORDER BY jadwal_tahun ASC, jadwal_mulai ASC
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
    
    // list hari kerja by jadwal 
    public function get_list_hari_kerja_by_id($params) {
        $sql = "SELECT a.`mdb` , a.`mdd`,  
                b.jadwal_id , b.`hari_kerja_id` , b.`jam_total`, 
                b.`jadwal_masuk_awal` , b.`jadwal_pulang_awal` , 
                b.`jadwal_masuk_akhir` , b.`jadwal_pulang_akhir`
                FROM data_jadwal_kerja a
                INNER JOIN data_hari_kerja b ON a.jadwal_id = b.jadwal_id
                WHERE a.jadwal_id = ?
                ORDER BY hari_kerja_id";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    
    
    // get detail jadwal by id
    public function get_detail_jadwal_by_id($params) {
        $sql = "SELECT * FROM data_jadwal_kerja WHERE jadwal_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }      
    
    // get detail jadwal
    public function get_detail_jadwal_kerja_by_id($params) {
        $sql = "SELECT * FROM data_jadwal_kerja
                WHERE jadwal_id = ?";
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
        return $this->db->insert('data_jadwal_kerja', $params);
    }

    // insert data hari kerja
    public function insert_hari_kerja($params){
        return $this->db->insert('data_hari_kerja', $params);
    }
    
    // update jadwal
    public function update($params, $where) {
        return $this->db->update('data_jadwal_kerja', $params, $where);
    }
    // delete jadwal
    public function delete_jadwal_kerja($where) {
        return $this->db->delete('data_jadwal_kerja', $where);
    }

    // delete data hari kerja   
    public function delete_hari_kerja($where){
        return $this->db->delete('data_hari_kerja', $where);
    }    
     
    /* UTILITY
     * 
     */     
    
    // create jadwal id
    public function get_jadwal_id() {
       // variabel
        $lebar = 5;
        $awalan = '';
        // tampil id terahir
        $sql = "SELECT RIGHT(jadwal_id, $lebar)'last_number' FROM data_jadwal_kerja ORDER BY jadwal_id DESC LIMIT 1";
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
