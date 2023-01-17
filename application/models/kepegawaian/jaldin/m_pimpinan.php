<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_pimpinan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
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

    function get_task_flow_id($params) {
        $sql = "SELECT flow_id FROM task_flow WHERE group_id='14' AND task_number LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return '';
        }
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
                GROUP BY a.`spt_id`
                ORDER BY a.tanggal_berangkat DESC LIMIT ?,?";
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
                c.process_id, c.flow_id, c.catatan, e.item_uraian, e.kode_akun, e.kode_output, e.item_jumlah
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                LEFT JOIN rencana_item e ON a.kode_item = e.kode_item
                WHERE a.spt_id = ? AND c.process_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
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

    public function update_flow($params, $where) {
        return $this->db->update('surat_tugas_process', $params, $where);
    }

    // // get all jaldin (dept leader - approval)
    // function get_all_jaldin_by_flow($params) {
    //     $sql = "SELECT a.*, process_id, b.project_name, project_alias, c.full_name, u.process_st
    //             FROM users_duty a
    //             INNER JOIN projects b ON a.project_id = b.project_id
    //             INNER JOIN users c ON c.user_id = a.user_id
    //             INNER JOIN users_duty_process u ON a.duty_id = u.duty_id
    //             WHERE u.action_st = 'process' AND u.flow_id = ? AND a.department_id = ?
    //             ORDER BY a.date_start ASC";
    //     $query = $this->db->query($sql, $params);
    //     // echo $this->db->last_query();
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get detail jaldin by process
    // function get_detail_jaldin_by_process($params) {
    //     $sql = "SELECT b.*, a.process_id, c.full_name, project_name, project_alias, 
    //             (DATEDIFF(date_end, date_start) + 1)'total_hari'
    //             FROM users_duty_process a
    //             INNER JOIN users_duty b ON a.duty_id = b.duty_id
    //             INNER JOIN users c ON b.user_id = c.user_id
    //             INNER JOIN projects d ON b.project_id = d.project_id
    //             WHERE a.process_id = ? AND a.action_st = 'process'
    //             GROUP BY a.duty_id";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
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

    // // update duty status
    // function update_duty_st($params) {
    //     $sql = "UPDATE users_duty SET duty_st = ? WHERE duty_id = ?";
    //     return $this->db->query($sql, $params);
    // }


}