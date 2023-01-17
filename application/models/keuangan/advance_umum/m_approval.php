<?php

class m_approval extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* LIST DATA */

    // get_total_advance_umum
    function get_total_advance_umum($params) {
        $sql = "SELECT COUNT(a.trx_id) 'total'
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.trx_id 
                    FROM trx_advance_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '21' AND b.task_number = ?
                    GROUP BY a.trx_id
                ) d ON a.trx_id = d.trx_id 
                INNER JOIN trx_advance_process e ON d.process_id = e.process_id
                WHERE b.item_uraian LIKE ? AND e.process_st = 'waiting' AND a.group_id = '21'
                ORDER BY a.send_date DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_advance_umum
    function get_list_advance_umum($params) {
        $sql = "SELECT b.item_uraian, c.struktur_nama, c.struktur_singkatan, a.advance_tanggal, a.advance_total_requested, a.trx_id, e.process_st, e.process_id, a.advance_total_approved
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.trx_id
                    FROM trx_advance_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '21' AND b.task_number = ?
                    GROUP BY a.trx_id
                ) d ON a.trx_id = d.trx_id 
                INNER JOIN trx_advance_process e ON d.process_id = e.process_id
                WHERE b.item_uraian LIKE ? AND e.process_st = 'waiting' AND a.group_id = '21'
                ORDER BY a.send_date DESC
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

    /* LPJ PENGAJUAN */

    // get_total_advance_umum_lpj
    function get_total_advance_umum_lpj($params) {
        $sql = "SELECT COUNT(a.trx_id) 'total'
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.trx_id 
                    FROM trx_advance_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '21' AND b.task_number = ?
                    GROUP BY a.trx_id
                ) d ON a.trx_id = d.trx_id 
                INNER JOIN trx_advance_process e ON d.process_id = e.process_id
                WHERE b.item_uraian LIKE ? AND ((e.process_st = 'waiting' AND a.advance_status = 'waiting') OR a.advance_status = 'approved') AND a.group_id = '21'
                ORDER BY a.send_date DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_advance_umum_lpj
    function get_list_advance_umum_lpj($params) {
        $sql = "SELECT b.item_uraian, c.struktur_nama, c.struktur_singkatan, a.advance_tanggal, a.advance_total_requested, a.trx_id, e.process_st, e.process_id, a.advance_total_approved
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN (
                    SELECT MAX(a.process_id) 'process_id', a.trx_id
                    FROM trx_advance_process a 
                    INNER JOIN task_flow b ON a.flow_id = b.flow_id
                    WHERE b.group_id = '21' AND b.task_number = ?
                    GROUP BY a.trx_id
                ) d ON a.trx_id = d.trx_id 
                INNER JOIN trx_advance_process e ON d.process_id = e.process_id
                WHERE b.item_uraian LIKE ? AND ((e.process_st = 'waiting' AND a.advance_status = 'waiting') OR a.advance_status = 'approved') AND a.group_id = '21'
                ORDER BY a.send_date DESC
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

    // ---

    // get_trx_advance_by_process_id
    function get_trx_advance_by_process_id($params) {
        $sql = "SELECT a.*, b.group_name, c.kode_output, c.item_no, c.item_uraian, d.struktur_nama, d.struktur_singkatan, e.process_id, e.process_st, e.catatan, e.flow_id
                FROM trx_advance a
                INNER JOIN task_group b ON a.group_id = b.group_id
                INNER JOIN rencana_item c ON a.kode_item = c.kode_item
                INNER JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                INNER JOIN trx_advance_process e ON a.trx_id = e.trx_id
                WHERE e.process_id = ?";
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

    // get_process_id
    function get_process_id() {
        return str_replace('.', '', microtime(true));
    }

    // get_flow_by_params
    function get_flow_by_params($params) {
        $sql = "SELECT *
                FROM task_flow
                WHERE group_id = ? AND task_number = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
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

    // get_lpj_id
    function get_lpj_id() {
        return str_replace('.', '', microtime(true));
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
                WHERE a.group_id = ? AND a.task_number < ?
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

    // get_list_catatan_flow
    function get_list_catatan_flow($params) {
        $sql = "SELECT b.catatan
                FROM task_flow a
                INNER JOIN trx_advance_process b ON a.flow_id = b.flow_id AND b.trx_id = ?
                WHERE a.group_id = ? AND a.task_number = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_flow_plan
    function get_flow_plan($params) {
        $sql = "SELECT c.process_st, c.catatan
                FROM task_flow a
                LEFT JOIN (
                    SELECT MAX(process_id) 'process_id', flow_id, trx_id
                    FROM trx_advance_process
                    GROUP BY flow_id, trx_id
                ) b ON a.flow_id = b.flow_id AND b.trx_id = ?
                LEFT JOIN trx_advance_process c ON b.process_id = c.process_id
                WHERE a.group_id = ? AND a.task_number = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*CRUD*/

    // update_trx_advance
    function update_trx_advance($params, $where) {
        return $this->db->update('trx_advance', $params, $where);
    }

    // insert_trx_advance_process
    function insert_trx_advance_process($params) {
        return $this->db->insert('trx_advance_process', $params);
    }

    // update_trx_advance_process
    function update_trx_advance_process($params, $where) {
        return $this->db->update('trx_advance_process', $params, $where);
    }

    // insert_trx_advance_lpj
    function insert_trx_advance_lpj($params) {
        return $this->db->insert('trx_advance_lpj', $params);
    }

    // update_trx_advance_lpj
    function update_trx_advance_lpj($params, $where) {
        return $this->db->update('trx_advance_lpj', $params, $where);
    }

    // delete_trx_advance_lpj
    function delete_trx_advance_lpj($where) {
        return $this->db->delete('trx_advance_lpj', $where);
    }

}
