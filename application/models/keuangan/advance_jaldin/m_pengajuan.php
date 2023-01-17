<?php

class m_pengajuan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get_total_pengajuan_advance_jaldin
    function get_total_pengajuan_advance_jaldin() {
        $sql = "SELECT COUNT(a.trx_id) 'total'
				FROM trx_advance a 
				INNER JOIN rencana_item b ON a.kode_item = b.kode_item
				INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN surat_tugas f ON a.ref_id = f.spt_id
				WHERE a.advance_status != 'waiting' AND a.group_id = '22'
				ORDER BY a.advance_tanggal DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get_list_pengajuan_advance_jaldin
    function get_list_pengajuan_advance_jaldin($params) {
        $sql = "SELECT b.item_uraian, c.struktur_nama, c.struktur_singkatan, a.advance_tanggal, a.advance_total_requested, a.trx_id, a.advance_status, f.uraian_tugas
                FROM trx_advance a 
                INNER JOIN rencana_item b ON a.kode_item = b.kode_item
                INNER JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                INNER JOIN surat_tugas f ON a.ref_id = f.spt_id
                WHERE a.advance_status != 'waiting' AND a.group_id = '22'
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

    // get_list_rencana_item
    function get_list_rencana_item() {
        $sql = "SELECT *
                FROM rencana_item
                ORDER BY item_uraian ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_spt
    function get_list_spt() {
        $sql = "SELECT *
                FROM surat_tugas
                WHERE spt_status = 'approved'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_last_nomor
    function get_last_nomor() {
        $sql = "SELECT advance_no
				FROM trx_advance
				ORDER BY advance_no DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['advance_no']+1;
        } else {
            return 1;
        }
    }

    // get_pegawai_by_user_id
    function get_pegawai_by_user_id($params) {
        $sql = "SELECT *
                FROM pegawai
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

    // get trx id
    function get_trx_id($prefix, $params) {
        $sql = "SELECT RIGHT(trx_id, 9) 'trx_id'
                FROM trx_advance
                WHERE trx_id LIKE ?
                ORDER BY trx_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $last_id = $result['trx_id'] + 1;
            $add = "";
            for ($i = 9; $i > strlen($last_id); $i--) {
                $add .= "0";
            }
            return $prefix . $add . $last_id;
        } else {
            return $prefix . '000000001';
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

    // get_trx_advance_by_id
    function get_trx_advance_by_id($params) {
        $sql = "SELECT a.*, b.group_name, c.kode_output, c.item_no, c.item_uraian, d.struktur_nama, d.struktur_singkatan, c.kode_akun, e.uraian_tugas, e.tanggal_berangkat, e.waktu_berangkat, e.waktu_pulang, e.lokasi_tujuan, f.mdd_finish, h.client_nm, h.client_address
                FROM trx_advance a
                INNER JOIN task_group b ON a.group_id = b.group_id
                INNER JOIN rencana_item c ON a.kode_item = c.kode_item
                INNER JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                INNER JOIN surat_tugas e ON a.ref_id = e.spt_id
                LEFT JOIN trx_advance_process f ON a.trx_id = f.trx_id AND f.process_st = 'approve' AND f.flow_id = '22005'
                LEFT JOIN projects g ON e.project_id = g.project_id
                LEFT JOIN projects_clients h ON g.client_id = h.client_id
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

    // get data id
    function get_data_id($prefix, $params) {
        $sql = "SELECT RIGHT(data_id, 5) 'data_id'
                FROM trx_advance_rincian
                WHERE data_id LIKE ?
                ORDER BY data_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $last_id = $result['data_id'] + 1;
            $add = "";
            for ($i = 5; $i > strlen($last_id); $i--) {
                $add .= "0";
            }
            return $prefix . $add . $last_id;
        } else {
            return $prefix . '00001';
        }
    }

    // get_rincian_item_by_id
    function get_rincian_item_by_id($params) {
        $sql = "SELECT *
                FROM trx_advance_rincian
                WHERE data_id = ?
                ORDER BY data_id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_last_process_flow
    function get_last_process_flow($params) {
        $sql = "SELECT *
                FROM trx_advance_process
                WHERE trx_id = ? 
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

    /*CRUD*/
    // insert_trx_advance
    function insert_trx_advance($params) {
        return $this->db->insert('trx_advance', $params);
    }

    // update_trx_advance
    function update_trx_advance($params, $where) {
        return $this->db->update('trx_advance', $params, $where);
    }

    // delete_trx_advance
    function delete_trx_advance($where) {
        return $this->db->delete('trx_advance', $where);
    }

    // insert_trx_advance_rincian
    function insert_trx_advance_rincian($params) {
        return $this->db->insert('trx_advance_rincian', $params);
    }

    // update_trx_advance_rincian
    function update_trx_advance_rincian($params, $where) {
        return $this->db->update('trx_advance_rincian', $params, $where);
    }

    // delete_trx_advance_rincian
    function delete_trx_advance_rincian($where) {
        return $this->db->delete('trx_advance_rincian', $where);
    }

    // insert_trx_advance_process
    function insert_trx_advance_process($params) {
        return $this->db->insert('trx_advance_process', $params);
    }

    // update_trx_advance_process
    function update_trx_advance_process($params, $where) {
        return $this->db->update('trx_advance_process', $params, $where);
    }

}
