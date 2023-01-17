<?php

class m_pembayaran  extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /* CORE
    *
    */
    
    // get total penagihan
    function get_total_penagihan($params) {
        $sql = "SELECT COUNT(a.termin_id) AS total 
                FROM projects_termin a
                INNER JOIN project_kontrak b ON a.kontrak_id=b.kontrak_id
                LEFT JOIN projects_invoices d ON a.termin_id = d.termin_id
                WHERE b.judul_kontrak LIKE ? AND MONTH(a.termin_tanggal) = ? AND YEAR(a.termin_tanggal) LIKE ?
                AND (d.invoices_status='process' OR d.invoices_status='paid')";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get data penagihan
    function get_all_penagihan_data($params) {
        $sql = "SELECT c.project_alias,b.judul_kontrak,d.*
                FROM projects_termin a
                INNER JOIN project_kontrak b ON a.kontrak_id=b.kontrak_id
                LEFT JOIN projects c ON b.project_id=c.project_id
                LEFT JOIN projects_invoices d ON a.termin_id = d.termin_id                
                WHERE b.judul_kontrak LIKE ? AND MONTH(a.termin_tanggal) = ? AND YEAR(a.termin_tanggal) LIKE ?
                AND (d.invoices_status='process' OR d.invoices_status='paid')
                ORDER BY termin_tanggal ASC
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
    
    // get detail invoice
    function get_detail_invoice_by_invoices_id($params) {
        $sql = "SELECT b.judul_kontrak,c.project_alias,d.*
                FROM projects_termin a 
                INNER JOIN project_kontrak b ON a.kontrak_id = b.kontrak_id
                LEFT JOIN projects c ON b.project_id = c.project_id
                INNER JOIN projects_invoices d ON a.termin_id = d.termin_id
                WHERE d.invoices_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }  
    
    // get invoices
    function get_kuitansi_by_invoices_id($params) {
        $sql = "SELECT *
                FROM projects_kuitansi
                WHERE invoices_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get detail kuitansi
    function get_kuitansi_by_id($params) {
        $sql = "SELECT a.invoices_jumlah, a.invoices_pajak_ppn, ab.*,b.termin_nomor,b.termin_uraian,c.judul_kontrak,e.client_desc,e.client_address,e.client_city FROM projects_kuitansi ab
                LEFT JOIN projects_invoices a ON ab.invoices_id = a.invoices_id
                LEFT JOIN projects_termin b ON a.termin_id=b.termin_id
                LEFT JOIN project_kontrak c ON b.kontrak_id=c.kontrak_id
                LEFT JOIN projects d ON c.project_id=d.project_id
                LEFT JOIN projects_clients e ON d.client_id=e.client_id
                WHERE ab.invoices_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }     

    // insert kuitansi
    function insert_kuitansi($params) {
        return $this->db->insert('projects_kuitansi', $params);
    }                        

    // update kuitansi
    function update_kuitansi($params, $where) {
       return $this->db->update('projects_kuitansi', $params, $where);
    }
    
    // update invoice
    function update_invoices($params, $where) {
       return $this->db->update('projects_invoices', $params, $where);
    }

    // update termin
    function update_termin($params, $where) {
       return $this->db->update('projects_termin', $params, $where);
    }    
    
    /* UTILITY
    *
    */
    
    // get detail invoices
    function get_invoices_by_id($params) {
        $sql = "SELECT *
                FROM projects_invoices
                WHERE invoices_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }      
    
    function is_kuitansi_exists($params){
        $sql = "SELECT invoices_id FROM projects_kuitansi
                WHERE invoices_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }        
    }
    
    // get list tahun avail 
    function get_list_tahun_penagihan() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(termin_tanggal)'tahun'
                        FROM projects_termin
                        UNION ALL
                        SELECT YEAR(CURRENT_DATE)'tahun'
                ) rs
                ORDER BY tahun ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
        
    // generate nomor kuitansi
    function generate_kuitansi_nomor($invoices_id){
        $sql = "SELECT invoices_id FROM projects_kuitansi
                ORDER BY LEFT(kuitansi_nomor,10) DESC LIMIT 1";
        $query = $this->db->query($sql);
        $bulan = $this->convert_bulan_to_romawi(date('m'));
        $tahun = date('Y');
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['invoices_id'], -10)+1;
            $nomor_urut = str_pad((string)$nomor_urut, 10, '0', STR_PAD_LEFT);
            return $nomor_urut.'/KUITANSI/'.$bulan.'/'.$tahun;
        } else {
            return '0000000001/KUITANSI/'.$bulan.'/'.$tahun;
        }       
    }    
    
    // generate invoice id
    function generate_invoice_id($termin_id){
        $termin_id = substr($termin_id,-10);
        $sql = "SELECT invoices_id FROM projects_invoices
                ORDER BY RIGHT(invoices_id,10) DESC LIMIT 1";
        $query = $this->db->query($sql);        
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['invoices_id'], -10)+1;
            $nomor_urut = str_pad((string)$nomor_urut, 10, '0', STR_PAD_LEFT);
            return $termin_id.$nomor_urut;            
        } else {
            return $termin_id . '0000000001';
        }       
    }     
        
    // convert number to roman
    private function convert_bulan_to_romawi($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
        }
        return $returnValue;
    }

}