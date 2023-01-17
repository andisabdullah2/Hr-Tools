<?php

class m_lpj extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    public function get_lpj_last_id($tahun) {
        $sql = "SELECT RIGHT(lpj_id, 16)'last_number'
                FROM surat_tugas_lpj
                WHERE LEFT(lpj_id, 4) = ?
                ORDER BY lpj_id DESC
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

    function get_list_spt($params) {
        $sql = "SELECT a.*, b.`pegawai_nip`, b.`nama_lengkap`, d.`project_alias`, d.`project_name`, d.`project_desc`, 
                c.process_id, c.flow_id
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                WHERE a.user_id = ? AND c.`flow_id` = ? AND c.`action_st` = 'process' AND c.process_st = 'waiting'
                GROUP BY a.`spt_id`";
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
                f.`client_address`, f.`client_desc`, f.`client_nm`, total_advance
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN surat_tugas_process c ON a.spt_id = c.`spt_id`
                LEFT JOIN projects d ON a.`project_id` = d.project_id
                LEFT JOIN rencana_item e ON a.kode_item = e.kode_item
                LEFT JOIN (
                    SELECT spt_id, COUNT(tanggal)'total_hari' FROM surat_tugas_tanggal 
                    WHERE spt_id = ? GROUP BY spt_id
                )f ON a.`spt_id` = f.spt_id
                LEFT JOIN (
                    SELECT spt_id, SUM(jumlah)'total_advance' FROM surat_tugas_advance 
                    WHERE spt_id = ? GROUP BY spt_id
                )g ON a.`spt_id` = g.spt_id
                LEFT JOIN projects_clients f ON d.`client_id` = f.`client_id`
                WHERE a.spt_id = ? AND c.process_id = ? AND a.user_id = ?";
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

    function get_list_lpj_by_spt($params) {
        $sql = "SELECT a.*, b.`jenis_biaya`
                FROM surat_tugas_lpj a
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

    function get_list_jenis_biaya() {
        $sql = "SELECT * FROM data_jenis_pengeluaran";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function insert_lpj($params) {
        return $this->db->insert('surat_tugas_lpj', $params);
    }

    public function insert_flow($params) {
        return $this->db->insert('surat_tugas_process', $params);
    }

    public function update_lpj($params, $where) {
        return $this->db->update('surat_tugas_lpj', $params, $where);
    }

    public function update_flow($params, $where) {
        return $this->db->update('surat_tugas_process', $params, $where);
    }

    public function delete_lpj($params) {
        return $this->db->delete('surat_tugas_lpj', $params);
    }

}