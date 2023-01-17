<?php

class m_hari_libur extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
     * 
     */

    //get total hari libur
    function get_total_hari_libur($params) {
        $sql = "SELECT COUNT(*) 'total' FROM data_hari_libur WHERE libur_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get data hari libur limit
    function get_hari_libur_limit($params) {
	$sql = "SELECT b.* FROM data_hari_libur b
                WHERE libur_tanggal BETWEEN ? AND ?        
                ORDER BY b.libur_tanggal ASC
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
    
    // get detail hari libur
    function get_detail_hari_libur_by_id($params) {
        $sql = "SELECT * FROM data_hari_libur
                WHERE libur_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // insert hari libur
    function insert_hari_libur($params) {
        return $this->db->insert('data_hari_libur', $params);
    }    

    // update hari libur
    function update_hari_libur($params, $where) {
        return $this->db->update('data_hari_libur', $params, $where);
    }
    
    // delete hari libur
    function delete_hari_libur($params) {
        return $this->db->delete('data_hari_libur', $params);
    }    
     
     /* UTILITY
     * 
     */
    
    // generate id hari libur
    public function generate_hari_libur_id($tanggal) {
        $tanggal = str_replace('-','.',$tanggal);
        // cari id terakhir
        $sql = "SELECT RIGHT(libur_id, 2) AS last_number FROM `data_hari_libur` WHERE libur_id LIKE '%$tanggal%' ORDER BY libur_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();            
            $result['last_number'] = str_replace('.', '', $result['last_number']);
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 99999) {
                return false;
            }
        } else {
            $nomor = 1;
        }
        return $tanggal.".".$nomor;
    }        
}
