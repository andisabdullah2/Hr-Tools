<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_pengajuan extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    public function get_spt_last_id($tahun) {
        $sql = "SELECT RIGHT(spt_id, 16)'last_number'
                FROM surat_tugas
                WHERE LEFT(spt_id, 4) = ?
                ORDER BY spt_id DESC
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

    // get list tahun
    function get_list_tahun() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(tanggal_berangkat)'tahun'
                        FROM users_duty
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

    // get total jaldin
    function get_total_jaldin($params) {
        $sql = "SELECT COUNT(*)'total' FROM surat_tugas a
                LEFT JOIN surat_tugas_process e ON a.`spt_id` = e.`spt_id`
                WHERE a.struktur_cd = ? AND (a.spt_status = 'draft' OR e.`process_st` = 'waiting') AND e.flow_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get all jaldin by task manager
    function get_list_jaldin_by_limit($params) {
        $sql = "SELECT a.*, b.`nama_lengkap`, c.`struktur_nama`, c.`struktur_singkatan`, d.`project_name`, d.`project_desc`, d.project_alias, e.`process_id`, e.`process_st`, e.`action_st`, e.`flow_revisi_id`
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN data_struktur_organisasi c ON a.`struktur_cd` = c.`struktur_cd`
                LEFT JOIN projects d ON a.`project_id` = d.`project_id`
                LEFT JOIN surat_tugas_process e ON a.`spt_id` = e.`spt_id`
                WHERE a.`struktur_cd` = ? AND (a.spt_status = 'draft' OR e.`process_st` = 'waiting') AND e.flow_id = ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail spt
    function get_detail_spt_by_id($params) {
        $sql = "SELECT a.*, b.`nama_lengkap`, c.`struktur_nama`, c.`struktur_singkatan`, d.`project_name`, d.`project_desc`, 
                d.project_alias, e.item_uraian, b.pegawai_nip, IF(b.jabatan_struktural_st = '1',f.jabatan_nama,'-')'jabatan'
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
                LEFT JOIN data_struktur_organisasi c ON a.`struktur_cd` = c.`struktur_cd`
                LEFT JOIN projects d ON a.`project_id` = d.`project_id`
                LEFT JOIN rencana_item e ON a.kode_item = e.kode_item
                LEFT JOIN data_jabatan_struktural f ON b.jabatan_struktural_id = f.jabatan_struktural_id
                WHERE a.spt_id = ? ";
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

    // get detail pegawai
    function get_detail_gm($params) {
        $sql = "SELECT a.`user_id`, a.`struktur_cd`, a.`jabatan_struktural_id`, a.`jabatan_struktural_st`, a.`pegawai_nip`, a.`nama_lengkap`, b.`struktur_nama`, b.`struktur_singkatan`
            FROM pegawai a
            LEFT JOIN data_struktur_organisasi b ON a.`struktur_cd` = b.`struktur_cd`
            LEFT JOIN data_jabatan_struktural c ON a.jabatan_struktural_id = c.jabatan_struktural_id
            WHERE a.struktur_cd = ? AND a.jabatan_struktural_id = '001.01.00.01' AND a.jabatan_struktural_st = '1'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list project
    function get_list_project_by_unit($params) {
        $sql = "SELECT * FROM projects WHERE struktur_cd = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list user
    function get_list_user_by_unit($params) {
        $sql = "SELECT * FROM pegawai WHERE struktur_cd = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list user
    function get_list_item_anggaran() {
        $sql = "SELECT * FROM rencana_item";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get flow pengajuan
    function get_flow_pengajuan($params) {
        $sql = "SELECT * FROM task_flow WHERE group_id = '14' AND task_number = ? ORDER BY flow_id ASC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return '';
        }
    }

    // get last process
    function get_last_process($params) {
        $sql = "SELECT a.* 
                FROM surat_tugas_process a
                LEFT JOIN task_flow b ON a.flow_id = b.flow_id
                WHERE spt_id = ? AND b.flow_id = ?  ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get next flow pengajuan
    function get_next_flow_pengajuan($params) {
        $sql = "SELECT * FROM task_flow WHERE group_id = '14' AND task_number = ? ORDER BY flow_id ASC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['flow_id'];
        } else {
            return '';
        }
    }

    public function insert_spt($params) {
        return $this->db->insert('surat_tugas', $params);
    }

    public function insert_tanggal($params) {
        return $this->db->insert('surat_tugas_tanggal', $params);
    }

    public function insert_flow($params) {
        return $this->db->insert('surat_tugas_process', $params);
    }

    // update spt
    public function update_spt($params, $where) {
        return $this->db->update('surat_tugas', $params, $where);
    }

    public function update_flow($params, $where) {
        return $this->db->update('surat_tugas_process', $params, $where);
    }

    // delete tanggal
    public function delete_tanggal($params) {
        return $this->db->delete('surat_tugas_tanggal', $params);
    }

    // delete spt
    public function delete_spt($params) {
        return $this->db->delete('surat_tugas', $params);
    }
}