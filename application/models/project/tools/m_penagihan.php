<?php

class M_penagihan  extends CI_Model {

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
                LEFT JOIN project_kontrak b ON a.kontrak_id=b.kontrak_id
                WHERE b.judul_kontrak LIKE ? AND MONTH(a.termin_tanggal) = ? AND YEAR(a.termin_tanggal) LIKE ? AND (termin_status='waiting' || termin_status='lunas')";
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
        $sql = "SELECT a.*,c.project_alias,b.judul_kontrak,b.nomor_kontrak,d.invoices_status
                FROM projects_termin a
                INNER JOIN project_kontrak b ON a.kontrak_id=b.kontrak_id
                LEFT JOIN projects c ON b.project_id=c.project_id
                LEFT JOIN projects_invoices d ON a.termin_id = d.termin_id AND (invoices_bulan) = ?
                WHERE b.judul_kontrak LIKE ? AND MONTH(a.termin_tanggal) = ? AND YEAR(a.termin_tanggal) LIKE ? AND (termin_status='waiting' || termin_status='lunas')
                GROUP BY b.kontrak_id
                ORDER BY nomor_kontrak ASC, termin_tanggal ASC
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

    // get detail termin if data exist
    function get_detail_termin_by_id($params) {
        $sql = "SELECT a.*,b.judul_kontrak,c.project_alias,d.*
                FROM projects_termin a 
                INNER JOIN project_kontrak b ON a.kontrak_id = b.kontrak_id
                LEFT JOIN projects c ON b.project_id = c.project_id
                INNER JOIN projects_invoices d ON a.termin_id = d.termin_id
                WHERE a.termin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get detail termin if data null
    function get_detail_termin_by_id_if_null($params) {
        $sql = "SELECT a.*,b.judul_kontrak,c.project_alias
                FROM projects_termin a 
                INNER JOIN project_kontrak b ON a.kontrak_id = b.kontrak_id
                LEFT JOIN projects c ON b.project_id = c.project_id
                WHERE a.termin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get data kontrak
    function get_all_kontrak_data()
    {
        $sql = "SELECT a.*, c.*
                FROM project_kontrak a
                INNER JOIN projects c ON a.project_id = c.project_id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all data termin
    function get_all_termin_data()
    {
        $sql = "SELECT a.kontrak_id, a.termin_id, a.termin_nilai, b.nomor_kontrak
                FROM projects_termin a
                INNER JOIN project_kontrak b ON a.kontrak_id = b.kontrak_id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all project
    function get_all_data_projects()
    {
        $sql = "SELECT * FROM projects";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get invoices
    function get_invoices_by_termin_id($params) {
        $sql = "SELECT *
                FROM projects_invoices
                WHERE termin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all invoices
    function get_all_invoices() {
        $sql = "SELECT a.*, b.*, c.*
                FROM projects_invoices a
                INNER JOIN projects_termin b ON a.termin_id = b.termin_id
                INNER JOIN project_kontrak c ON b.kontrak_id = c.kontrak_id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get detail invoices
    function get_invoices_by_id($params) {
        $sql = "SELECT a.*,b.termin_nomor,b.termin_uraian,c.*,e.client_desc,e.client_address,e.client_city,d.* FROM projects_invoices a
                LEFT JOIN projects_termin b ON a.termin_id=b.termin_id
                LEFT JOIN project_kontrak c ON b.kontrak_id=c.kontrak_id
                LEFT JOIN projects d ON c.project_id=d.project_id
                LEFT JOIN projects_clients e ON d.client_id=e.client_id
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

    // insert invoice
    function insert_invoice($params) {
        return $this->db->insert('projects_invoices', $params);
    }                        

    // update invoice
    function update($params, $where) {
       return $this->db->update('projects_invoices', $params, $where);
    }
    
    // update termin
    function update_termin($params, $where) {
       return $this->db->update('projects_termin', $params, $where);
    }   

    // delete
    function delete($params) {
        return $this->db->delete('projects_invoices', $params);
    }    
    
    
    /* UTILITY
    *
    */    
    
    // get new nomor invoices
    function get_new_nomor_invoices() {
        $sql = "SELECT invoices_nomor FROM projects_invoices
                ORDER BY invoices_nomor DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['invoices_nomor'] + 1;
        } else {
            return 1;
        }
    }
    
    public function new_id() {
        $now = date('Ym');
        $sql = "SELECT RIGHT(invoices_id, 6)'last_number'
                FROM projects_invoices
                WHERE LEFT(invoices_id,6) = '$now'
                ORDER BY invoices_id DESC
                LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 999999) {
                return FALSE;
            }
        } else {
            $nomor = 1;
        }
        // return
        return $now . str_pad($nomor, 6, "0", STR_PAD_LEFT);
    }
    
    function is_invoice_exists($params){
        $sql = "SELECT invoices_id FROM projects_invoices
                WHERE termin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }        
    }
    
    // get list tahun avail in termin
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
        
    // generate kontrak id
    function generate_invoice_nomor($invoices_id){
        $sql = "SELECT invoices_id FROM projects_invoices
                ORDER BY create_date DESC LIMIT 1";
        $query = $this->db->query($sql);
        $bulan = $this->convert_bulan_to_romawi(date('m'));
        $tahun = date('Y');
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['invoices_id'], -7)+1;
            $nomor_urut = str_pad((string)$nomor_urut, 7, '0', STR_PAD_LEFT);
            return $nomor_urut.'/INVOICE/'.$bulan.'/'.$tahun;
        } else {
            return '000001/INVOICE/'.$bulan.'/'.$tahun;
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
            return $termin_id . '000001';
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

    function get_kontrak_by_idProjects($params) {
        $sql = "SELECT * FROM project_kontrak WHERE project_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_termin_by_idProjects($params) {
        $sql = "SELECT * FROM projects_termin WHERE kontrak_id=?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_tahun_project()
    {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(project_start)'tahun'
                        FROM projects
                        UNION ALL
                        SELECT YEAR(CURRENT_DATE)'tahun'
                ) rs
                ORDER BY tahun DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get project by tahun
    function get_project_by_tahun($params)
    {
        $sql = "SELECT * FROM projects WHERE YEAR(project_start) LIKE ? ORDER BY project_start DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}