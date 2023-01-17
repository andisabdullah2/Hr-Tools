<?php

class m_pengajuan extends CI_Model {


    /* CORE
     * 
     */

    // get total permit
    function get_total_permit($params) {
        $sql = "SELECT COUNT(a.izin_id)'total'
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN 
                (
                    SELECT * FROM
                    (
                            SELECT u.process_id, u.izin_id, u.action_st,t.*,
                            u.process_st
                            FROM pegawai_izin_process u
                            LEFT JOIN task_flow t ON t.flow_id = u.flow_id
                            ORDER BY process_id DESC
                    ) pr
                    GROUP BY izin_id
                ) f ON a.izin_id = f.izin_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.izin_tanggal) = ?
                AND (izin_status = 'draft' OR izin_status = 'rejected') AND f.flow_id = '12001'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }
     
    // get all permit by limit
    function get_all_permit_by_limit($params) {
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_izin, f.process_st, f.flow_id,f.action_st
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_izin c ON a.jenis_id = c.jenis_id
                INNER JOIN 
                (
                    SELECT * FROM
                    (
                            SELECT u.process_id, u.izin_id, u.action_st,t.*,
                            u.process_st
                            FROM pegawai_izin_process u
                            LEFT JOIN task_flow t ON t.flow_id = u.flow_id
                            ORDER BY process_id DESC
                    ) pr
                    GROUP BY izin_id
                ) f ON a.izin_id = f.izin_id
                WHERE b.nama_lengkap LIKE ? AND YEAR(a.izin_tanggal) = ? AND (izin_status = 'draft' OR izin_status = 'rejected') AND f.flow_id = '12001'
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
    
    // get detail permit
    function get_detail_permit($permit_id){
        $sql = "SELECT a.*, b.nama_lengkap, c.jenis_izin, a.struktur_cd, d.struktur_nama
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                INNER JOIN data_jenis_izin c ON a.jenis_id=c.jenis_id
                LEFT JOIN data_struktur_organisasi d ON a.struktur_cd=d.struktur_cd
                WHERE a.izin_id = ?";
        $query = $this->db->query($sql, $permit_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    //get detail print
    function get_detail_print($params) {
        $sql = "SELECT a.* , b.nama_lengkap, b.struktur_cd, c.struktur_nama,f.jabatan_nama AS jabatan_fungsional ,z.jabatan_nama AS jabatan_struktural
                FROM pegawai_izin a
                INNER JOIN pegawai b ON a.user_id = b.user_id
                LEFT JOIN data_struktur_organisasi c ON b.struktur_cd = c.struktur_cd
                LEFT JOIN
                (
                    SELECT * FROM 
                    (
                        SELECT d.data_id,d.jabatan_fungsional_id,d.user_id,e.jabatan_nama FROM pegawai_jabatan_fungsional d
                        INNER JOIN data_jabatan_fungsional e ON d.jabatan_fungsional_id=e.jabatan_fungsional_id
                        WHERE jabatan_status='1' AND jabatan_default='1'
                        ORDER BY d.tanggal_mulai DESC
                    )
                    ps GROUP BY ps.user_id
                ) f ON f.user_id = b.user_id
                LEFT JOIN
                (
                    SELECT * FROM
                    (
                        SELECT x.data_id,x.jabatan_struktural_id,x.user_id,y.jabatan_nama FROM pegawai_jabatan_struktural x
                        INNER JOIN data_jabatan_struktural y ON x.jabatan_struktural_id=y.jabatan_struktural_id
                        WHERE jabatan_status='1' AND jabatan_default='1'
                        ORDER BY x.tanggal_mulai DESC
                    )
                    st GROUP BY st.user_id
                ) z ON z.user_id = b.user_id
                WHERE a.izin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }    

    // insert pegawai_izin
    function insert_permit($params) {
        return $this->db->insert('pegawai_izin', $params);
    }

    // insert pegawai_izin_process
    function insert_flow($params) {
        return $this->db->insert('pegawai_izin_process', $params);
    }      

    // update permit st    
    function update_permit_st($params, $where) {
        return $this->db->update('pegawai_izin', $params, $where);
    }
    
    //update proses pegawai_izin_proses
    function update_process_by_izin_id($params, $where) {
        return $this->db->update('pegawai_izin_process', $params, $where);
    }

    // update pegawai_izin
    function update_permit($params, $where) {
        return $this->db->update('pegawai_izin', $params, $where);
    }   
    
    // delete
    function delete_permit($params) {
        return $this->db->delete('pegawai_izin', $params);
    }     
    
    /* UTILITY
     * 
     */

     // generate microtime
    function get_microtime() {
        $time = microtime(true);
        $id = str_replace('.', '', $time);
        return $id;
    }
    
    // generate izin id
    function generate_izin_id($tanggal) {
        $sql = "SELECT izin_id AS izin_id_latest
                FROM pegawai_izin
                ORDER BY RIGHT(izin_id,12) DESC
                LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            // $query->free_result();
            $izin_id_new = (substr($result['izin_id_latest'],8)+1);
            $izin_id_new = str_pad((string)$izin_id_new, 12, '0', STR_PAD_LEFT);
            $izin_id_new = str_replace('-','',$tanggal).$izin_id_new;
            return $izin_id_new;
        } else {            
            return str_replace('-','',date('Y-m-d')).'000000000001';
        }
    }        
    
    // get list tahun
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(izin_tanggal)'tahun'
                        FROM pegawai_izin
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
    
    // get process reference
    function get_process_reference($params) {
        $sql = "SELECT process_id FROM pegawai_izin_process WHERE izin_id = ? ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['process_id'];
        } else {
            return null;
        }
    }

    // get user department by id
    function get_user_unit_kerja_by_id($id) {
        $sql = "SELECT struktur_cd FROM pegawai a
                WHERE a.user_id = $id AND 1=0";
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

    // get jenis ijin
    function get_permit_type($params) {
        $sql = "SELECT * FROM data_jenis_izin 
                ORDER BY jenis_izin ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get dpt leader
    function get_unit_kerja_leader($params){
        $sql = "SELECT d.user_id,e.nama_lengkap
                FROM `pegawai_unit_kerja` a
                INNER JOIN data_struktur_organisasi b ON a.struktur_cd=b.struktur_cd
                INNER JOIN data_jabatan_struktural c ON b.struktur_cd=c.struktur_cd
                INNER JOIN pegawai_jabatan_struktural d ON c.jabatan_struktural_id=d.jabatan_struktural_id
                INNER JOIN pegawai e ON d.user_id=e.user_id
                WHERE a.user_id = ? AND unit_kerja_status='1' AND d.jabatan_status='1'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['nama_lengkap'];
        } else {
            return null;
        }
    }

   // get permit_st by permit_id
    function get_permit_st($permit_id){
        $sql = "SELECT izin_status
                FROM pegawai_izin
                WHERE izin_id = ?";
        $query = $this->db->query($sql, $permit_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['izin_status'];
        } else {
            return array();
        }
    }
    
    // convert number to roman
    function convert_bulan_to_romawi($number) {
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
    
    // check if have references
    function check_if_have_references($params) {
        $sql = "SELECT process_st,process_references_id FROM pegawai_izin_process 
                WHERE izin_id = ? ORDER BY process_id DESC LIMIT 1";
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

    //get list pegawai
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
