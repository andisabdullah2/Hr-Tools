<?php

class m_monitoring extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get_total_pengajuan_advance_umum
    function get_total_pengajuan_advance_umum($params) {
        $sql = "SELECT COUNT(a.trx_id) 'total'
				FROM trx_advance a 
				INNER JOIN rencana_item b ON a.kode_item = b.kode_item
				INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
				WHERE b.item_uraian LIKE ? AND a.advance_status LIKE ? AND a.group_id = '21'
				ORDER BY a.advance_tanggal DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_pengajuan_advance_umum
    function get_list_pengajuan_advance_umum($params) {
        $sql = "SELECT b.item_uraian, c.struktur_nama, c.struktur_singkatan, a.advance_tanggal, a.advance_total_requested, a.trx_id, a.advance_status
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                WHERE b.item_uraian LIKE ? AND a.advance_status LIKE ? AND a.group_id = '21'
                ORDER BY a.advance_tanggal DESC
                LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_trx_advance_by_id
    function get_trx_advance_by_id($params) {
        $sql = "SELECT a.*, b.group_name, c.kode_output, c.item_no, c.item_uraian, d.struktur_nama, d.struktur_singkatan, c.kode_akun
                FROM trx_advance a
                INNER JOIN task_group b ON a.group_id = b.group_id
                INNER JOIN rencana_item c ON a.kode_item = c.kode_item
                INNER JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                WHERE a.trx_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_rincian_item
    function get_list_rincian_item($params) {
        $sql = "SELECT *
                FROM trx_advance_rincian
                WHERE trx_id = ?
                ORDER BY data_id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_lpj_by_trx_id
    function get_list_lpj_by_trx_id($params) {
        $sql = "SELECT *
                FROM trx_advance_lpj
                WHERE trx_id = ?
                ORDER BY lpj_id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_flow_plan
    function get_list_flow_plan($params) {
        $sql = "SELECT a.task_desc, c.process_st, c.catatan
                FROM task_flow a
                LEFT JOIN (
                    SELECT MAX(process_id) 'process_id', flow_id, trx_id
                    FROM trx_advance_process
                    GROUP BY flow_id, trx_id
                ) b ON a.flow_id = b.flow_id AND b.trx_id = ?
                LEFT JOIN trx_advance_process c ON b.process_id = c.process_id
                WHERE a.group_id = ?
                ORDER BY a.task_number ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_persetujuan_process
    function get_persetujuan_process($params) {
        $sql = "SELECT *
                FROM trx_advance_process
                WHERE trx_id = ? AND flow_id = ? AND process_st = 'approve'
                ORDER BY process_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}
