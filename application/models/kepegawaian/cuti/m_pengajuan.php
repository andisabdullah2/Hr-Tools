<?php

class m_pengajuan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /*CORE
    */  

    // insert pengajuan    
    public function insert_pengajuan($params) {
        return $this->db->insert('pegawai_cuti', $params);
    }
    
    // insert pengajuan date
    public function insert_tanggal_cuti($params) {
        return $this->db->insert('pegawai_cuti_tanggal', $params);
    }
    
    // insert flow
    function insert_flow($params) {
        return $this->db->insert('pegawai_cuti_process', $params);
    }

    // get total pengajuan - index
    function get_total_pengajuan($params) {
        $sql = "SELECT COUNT(a.cuti_id)'total'
                FROM pegawai_cuti a
                INNER JOIN pegawai_cuti_process u ON a.cuti_id=u.cuti_id
                WHERE YEAR(a.cuti_tanggal_mulai) = ? AND u.action_st = 'process' AND flow_id = '11001'
                AND (cuti_status = 'draft' OR cuti_status = 'rejected')";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get all pengajuan by limit - index
    function get_all_pengajuan_by_limit($params) {   
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_cuti, d.process_st, d.flow_id,d.action_st
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON a.jenis_id = c.jenis_id
                INNER JOIN 
                (
                    SELECT * FROM
                    (
                            SELECT u.process_id, u.cuti_id, u.action_st,t.*,
                            u.process_st
                            FROM pegawai_cuti_process u
                            LEFT JOIN task_flow t ON t.flow_id = u.flow_id
                            ORDER BY process_id DESC
                    ) pr
                    GROUP BY cuti_id
                ) d ON a.cuti_id = d.cuti_id
                WHERE YEAR(a.cuti_tanggal_mulai) = ?  
                AND (cuti_status = 'draft' OR cuti_status = 'rejected') AND d.flow_id = ?
                ORDER BY a.mdd ASC
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
    
    // get detail pengajuan
    function get_detail_pengajuan($pengajuan_id) {
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_cuti, d.struktur_nama
                FROM pegawai_cuti a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_cuti c ON a.jenis_id=c.jenis_id
                LEFT JOIN data_struktur_organisasi d ON a.struktur_cd=d.struktur_cd
                WHERE a.cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get cetak pengajuan 
    function get_cetak_pengajuan_by_id($params) {
        $sql = "SELECT a.*, b.nama_lengkap, b.nomor_telepon, c.struktur_nama,  cb.struktur_nama'pic_position',
                COUNT(g.cuti_id)'masa_cuti',i.jabatan_nama,j.jenis_cuti
                FROM pegawai_cuti a 
                LEFT JOIN pegawai b ON a.user_id=b.user_id
                LEFT JOIN data_struktur_organisasi c ON c.struktur_cd=a.struktur_cd
                LEFT JOIN pegawai bb ON a.cuti_pic=bb.user_id
                LEFT JOIN data_struktur_organisasi cb ON bb.struktur_cd=cb.struktur_cd
                LEFT JOIN pegawai_cuti_tanggal g ON a.cuti_id=g.cuti_id
                LEFT JOIN pegawai_jabatan_fungsional h ON h.user_id=a.user_id
                LEFT JOIN data_jabatan_fungsional i ON h.jabatan_fungsional_id=i.jabatan_fungsional_id
                LEFT JOIN data_jenis_cuti j ON a.jenis_id=j.jenis_id
                WHERE a.cuti_id = ?
                GROUP BY a.cuti_id
                ORDER BY a.mdd ASC
                ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }   
    
    // update_pengajuan 
    function update_pengajuan($params, $where) {
        return $this->db->update('pegawai_cuti', $params, $where);
    }     
    
    // update pengajuan st
    function update_pengajuan_st($params, $where) {
        return $this->db->update('pegawai_cuti', $params, $where);
    }
    
    // update flow
    function update_process_by_cuti_id($params, $where) {
        return $this->db->update('pegawai_cuti_process', $params, $where);
    }
             
    // delete pengajuan date
    function delete_tanggal_cuti($params) {
        return $this->db->delete('pegawai_cuti_tanggal', $params);
    }  

    // delete pengajuan
    function delete_pengajuan($params) {
        return $this->db->delete('pegawai_cuti', $params);
    }  
    
    /*UTILITY
    */  
    
    // get microtime
    public function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }    
    
    // generate cuti id
    function generate_cuti_id($tanggal) {
        $sql = "SELECT cuti_id AS cuti_id_latest
                FROM pegawai_cuti
                ORDER BY RIGHT(cuti_id,12) DESC
                LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $cuti_id_new = (substr($result['cuti_id_latest'],8)+1);
            $cuti_id_new = str_pad((string)$cuti_id_new, 12, '0', STR_PAD_LEFT);
            $cuti_id_new = str_replace('-','',$tanggal).$cuti_id_new;
            return $cuti_id_new;
        } else {
            return str_replace('-','',date('Y-m-d')).'000000000001';
        }
    }
    
    // generate cuti nomor
    function generate_nomor_cuti($cuti_id, $start_date) {
        $month = $this->convert_bulan_to_romawi(date("m",strtotime($start_date)));
        $nomor_cuti = substr($cuti_id, 14).'/CUTI/'.$month.'/'.date("Y",strtotime($start_date));     
        return $nomor_cuti;
    }

    // get list tahun
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(cuti_tanggal_mulai)'tahun'
                        FROM pegawai_cuti
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
    
    // get user department by id
    function get_user_unit_kerja_by_id($id) {
        $sql = "SELECT struktur_cd FROM pegawai a
                WHERE a.user_id = $id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['struktur_cd'];
        } else {
            return null;
        }
    }

    // get user by department
    function get_user_by_department($params) {
        $sql = "SELECT user_id, nama_lengkap FROM pegawai a
                WHERE a.struktur_cd LIKE ? ORDER BY nama_lengkap ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get jenis cuti
    function get_pengajuan_type() {
        $sql = "SELECT * FROM data_jenis_cuti
                ORDER BY jenis_cuti ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get pengajuan_st by pengajuan_id
    function get_pengajuan_st($pengajuan_id){
        $sql = "SELECT cuti_status
                FROM pegawai_cuti
                WHERE cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['cuti_status'];
        } else {
            return null;
        }
    }
    
    // get pengajuan_st by pengajuan_id
    function get_jenis_and_tahun_cuti_by_id($pengajuan_id){
        $sql = "SELECT cuti_id, user_id, jenis_id, YEAR(cuti_tanggal_mulai) AS tahun
                FROM pegawai_cuti
                WHERE cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    
    // get pengajuan_st by pengajuan_id
    function get_total_cuti_pengajuan($pengajuan_id){
        $sql = "SELECT COUNT(cuti_tanggal) AS total
                FROM pegawai_cuti_tanggal
                WHERE cuti_id = ?";
        $query = $this->db->query($sql, $pengajuan_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return null;
        }
    }       
    
    // convert number to roman format
    function convert_bulan_to_romawi($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 
                    'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
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

    // check if have references
    function check_if_have_references($params) {
        $sql = "SELECT process_st,process_references_id FROM pegawai_cuti_process 
                WHERE cuti_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if (!empty($result['process_references_id'])){
            return true;
            } else {
            return false;
            }
        }
    }     
    
    // get process references id
    function get_process_reference($params) {
        $sql = "SELECT process_id FROM pegawai_cuti_process WHERE cuti_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_id'];
        } else {
            return null;
        }
    }
    
    // get holiday between date
    public function get_all_hari_libur_between_range_cuti($params) {
        $sql = "SELECT libur_tanggal FROM data_hari_libur WHERE libur_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }     
    
    // get kuota cuti
    public function get_total_jatah_cuti($params) {
        $sql = "SELECT total FROM pegawai_cuti_kuota WHERE user_id = ? AND jenis_id = ? AND tahun = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }   
    
    // get kuota used
    public function get_total_cuti_terpakai($params) {
        $sql = "SELECT COUNT(a.cuti_id) AS total FROM pegawai_cuti_tanggal a
                INNER JOIN pegawai_cuti b ON a.cuti_id = b.cuti_id
                WHERE b.user_id = ? AND b.jenis_id = ? AND YEAR(b.cuti_tanggal_mulai) = ? AND cuti_status = 'approved'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }    
    
    // check if given day is weekend
    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }
    
    // breakdown range date into array
    function breakdown_tanggal_cuti_perhari($start_date,$end_date){
        $start_date_obj = new DateTime($start_date);
        $end_date_obj = new DateTime($end_date);        
        //include end date in period
        $end_date_obj->setTime(0,0,1);
        $range_cuti = new DatePeriod($start_date_obj,new DateInterval('P1D'),$end_date_obj); 
        return $range_cuti;
    }
    
    // get data cuti tanggal
    public function get_data_cuti_tanggal($params) {
        $sql = "SELECT cuti_tanggal FROM pegawai_cuti_tanggal WHERE cuti_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get dpt leader by cuti id
    function get_dpt_lead_by_cuti_id($params) {
        $sql = "SELECT f.nama_lengkap'nama_lengkap'
                FROM pegawai_cuti a 
                LEFT JOIN data_jabatan_struktural d ON d.struktur_cd=a.struktur_cd
                LEFT JOIN pegawai_jabatan_struktural e ON e.jabatan_struktural_id=d.jabatan_struktural_id
                LEFT JOIN pegawai f ON e.user_id=f.user_id
                WHERE e.jabatan_status=1 AND a.cuti_id = ?
                GROUP BY a.cuti_id
                ORDER BY a.mdd ASC
                ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['nama_lengkap'];
        } else {
            return null;
        }
    }

    // get list pegawai
    function get_list_pegawai() {
        $sql = "SELECT user_id,nama_lengkap,struktur_nama,a.struktur_cd FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd=b.struktur_cd
                WHERE pegawai_status = 'working'
                ORDER BY nama_lengkap ASC";
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
