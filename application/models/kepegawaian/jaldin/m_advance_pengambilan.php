<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_advance_pengambilan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_process_last_id($tahun) {
        $sql = "SELECT RIGHT(process_id, 16)'last_number'
                FROM surat_tugas_process
                WHERE LEFT(process_id, 4) = ?
                ORDER BY process_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $tahun);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 9999999999999999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 16; $i++) {
                $zero .= '0';
            }
            return $tahun . $zero . $number;
        } else {
            // create new number
            return $tahun . '0000000000000001';
        }
    }

    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(tanggal_berangkat)'tahun'
                        FROM surat_tugas
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

    function get_total_spt($params) {
        $sql = "SELECT COUNT(a.`spt_id`)'total'
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                WHERE a.struktur_cd = ? AND YEAR(a.`tanggal_berangkat`) = ? AND MONTH(a.`tanggal_berangkat`) LIKE ? AND b.`nama_lengkap` LIKE ?
                AND c.`flow_id` = ? AND c.`action_st` = 'process' AND c.process_st = 'waiting'
                GROUP BY a.`spt_id`";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_list_spt($params) {
        $sql = "SELECT a.*, b.`pegawai_nip`, b.`nama_lengkap`, d.`project_alias`, d.`project_name`, d.`project_desc`, 
                c.process_id, c.flow_id, c.flow_revisi_id, c.process_st, c.action_st
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                WHERE a.`struktur_cd` = ? AND YEAR(a.`tanggal_berangkat`) = ? AND MONTH(a.`tanggal_berangkat`) LIKE ? AND b.`nama_lengkap` LIKE ?
                AND c.`flow_id` = ? AND c.`action_st` = 'process' AND c.process_st = 'waiting'
                GROUP BY a.`spt_id` LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_spt_by_id($params) {
        $sql = "SELECT a.*, b.`pegawai_nip`, b.`nama_lengkap`, d.`project_alias`, d.`project_name`, d.`project_desc`, 
                c.process_id, c.flow_id, c.catatan, e.item_uraian, e.kode_akun, e.kode_output, e.item_jumlah, total_hari,
                f.`client_address`, f.`client_desc`, f.`client_nm`
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                LEFT JOIN rencana_item e ON a.kode_item = e.kode_item
                LEFT JOIN (
                    SELECT spt_id, COUNT(tanggal)'total_hari' FROM surat_tugas_tanggal 
                    WHERE spt_id = ? GROUP BY spt_id
                )f ON a.`spt_id` = f.spt_id
                LEFT JOIN projects_clients f ON d.`client_id` = f.`client_id`
                WHERE a.spt_id = ? AND c.process_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_advance_by_spt($params) {
        $sql = "SELECT a.*, b.`jenis_biaya`
                FROM surat_tugas_advance a
                LEFT JOIN data_jenis_pengeluaran b ON a.`jenis_id` = b.`jenis_id`
                WHERE a.`spt_id` = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail pegawai
    function get_detail_pegawai($params) {
        $sql = "SELECT a.`user_id`, a.`struktur_cd`, a.`jabatan_struktural_id`, a.`jabatan_struktural_st`, a.`pegawai_nip`, a.`nama_lengkap`, b.`struktur_nama`, b.`struktur_singkatan`
            FROM pegawai a
            LEFT JOIN data_struktur_organisasi b ON a.`struktur_cd` = b.`struktur_cd`
            WHERE user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    } 

    public function insert_flow($params) {
        return $this->db->insert('surat_tugas_process', $params);
    } 

    public function update_advance($params, $where) {
        return $this->db->update('surat_tugas_advance', $params, $where);
    }

    public function update_flow($params, $where) {
        return $this->db->update('surat_tugas_process', $params, $where);
    }

    public function update_spt($params, $where) {
        return $this->db->update('surat_tugas', $params, $where);
    }

    

    // // update advance all
    // function update_advance_by_duty($params) {
    //     $sql = 'UPDATE users_duty_advance SET kredit = ? WHERE duty_id = ?';
    //     return $this->db->query($sql, $params);
    // }

    // // update advance detail
    // function update_advance_by_id($params) {
    //     $sql = 'UPDATE users_duty_advance SET kredit = ? WHERE advance_id = ?';
    //     return $this->db->query($sql, $params);
    // }

    // // update duty by id
    // function update_duty($params, $where) {
    //     return $this->db->update('users_duty', $params, $where);
    // }

    // // update flow
    // function update_flow_by_id($params) {
    //     $sql = "UPDATE users_duty_process SET action_st = ?, process_st = ?, process_by = ?, process_date = ? 
    //             WHERE process_id = ?";
    //     return $this->db->query($sql, $params);
    // }

    // // insert flow
    // function insert_flow($params) {
    //     $sql = "INSERT INTO users_duty_process (flow_id, duty_id, action_st, process_st, process_by, process_date) 
    //             VALUES(?, ?, ?, ?, ?, ?)";
    //     return $this->db->query($sql, $params);
    // }

}