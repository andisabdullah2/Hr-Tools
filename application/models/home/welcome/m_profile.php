<?php

class m_profile extends CI_Model
{

    // constructor
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // get_detail_pegawai_by_id
    function get_detail_pegawai_by_id($params)
    {
        $sql = "SELECT a.*, b.user_mail, jabatan_struktural_st, c.struktur_nama, 
                d.jabatan_nama AS 'jabatan_fungsional', e.jabatan_nama AS 'jabatan_struktural'
                FROM pegawai a 
                INNER JOIN com_user b ON a.user_id = b.user_id
                LEFT JOIN data_struktur_organisasi c ON a.struktur_cd = c.struktur_cd
                LEFT JOIN data_jabatan_fungsional d ON a.jabatan_fungsional_id = d.jabatan_fungsional_id
                LEFT JOIN data_jabatan_struktural e ON a.jabatan_struktural_id = e.jabatan_struktural_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list files by  id
    public function get_files_by_id($params)
    {
        $sql = "SELECT p.*, t.files_id, t.file_name, t.file_no FROM pegawai_files p
        left join pegawai_files_trx t on t.pegawai_files_id = p.pegawai_files_id";
        $query = $this->db->query($sql, $params);
        // print_r($sql);die;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get files master by id
    public function get_files_master_by_id($param) {
        $sql = "SELECT a.pegawai_files_id, a.file_field, a.file_title, a.file_size, a.file_allowed
                FROM pegawai_files a
                WHERE a.pegawai_files_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // //get files trx
    // public function get_files_trx_by_id($params)
    // {
    //     $sql = "SELECT * FROM pegawai_files_trx WHERE pegawai_files_id =?";
    //     $query = $this->db->query($sql,$params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    //insert files
    public function insert_attachment($params)
    {
        return $this->db->insert('pegawai_files_trx', $params);
    }

    // Delete files
    public function delete_attachment($where)
    {
        return $this->db->delete('pegawai_files_trx', $where);
    }

    // generate files id
    public function generate_files_id()
    {
        // cari id terakhir
        $sql = "SELECT files_id  AS last_number 
                  FROM `pegawai_files_trx`
                  ORDER BY files_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            $nomor = str_pad($nomor, strlen($result['last_number']), '0', STR_PAD_LEFT);
        } else {
            $nomor = '0001';
        }
        return $nomor;
    }

    // get detail file attachment
    public function get_files_attachment_by_id($params)
    {
        $sql = "SELECT * FROM pegawai_files_trx WHERE files_id = ?";
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
