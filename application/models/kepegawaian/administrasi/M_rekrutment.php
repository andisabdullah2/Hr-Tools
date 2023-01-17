<?php

class M_rekrutment extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // get total rekrutment agenda
    function get_total_rekrutment($params)
    {
        $sql = "SELECT COUNT(a.rekrutmen_id ) 'total'
				FROM rekrutmen a
				INNER JOIN data_agenda b ON a.agenda_id = b.agenda_id 
				WHERE b.judul LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get list rekrutment
    function get_list_rekrutment($params)
    {
        $sql = "SELECT a.rekrutmen_id, a.periode_start,a.periode_end,a.jumlah_pelamar, a.agenda_id, a.media_id, a.status,  b.judul,  c.nama 
      FROM rekrutmen a 
      LEFT JOIN data_agenda b ON a.agenda_id = b.agenda_id
      LEFT JOIN data_media c ON a.media_id = c.media_id
      WHERE IF(`judul` != '', `judul`, `rekrutmen_id`) LIKE ?
      ORDER BY rekrutmen_id ASC LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_rekrutment_by_id
    function get_list_rekrutment_by_id($params)
    {
        $sql = "SELECT a.rekrutmen_id, a.periode_start,a.periode_end, a.jumlah_lolos_administrasi, a.jumlah_lolos_tulis, a.jumlah_lolos_wawancara, b.judul
      FROM rekrutmen a 
      LEFT JOIN data_agenda b ON a.agenda_id = b.agenda_id
      WHERE a.rekrutmen_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    // get list agenda by id
    function get_list_agenda_by_id($params)
    {
        $sql = "SELECT a.rekrutmen_id, a.periode_start,a.periode_end,a.agenda_id, a.media_id, a.jumlah_pelamar, b.judul, c.nama
      FROM rekrutmen a 
      LEFT JOIN data_agenda b ON a.agenda_id = b.agenda_id
      LEFT JOIN data_media c ON a.media_id = c.media_id
      WHERE a.rekrutmen_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list agenda
    function get_list_agenda()
    {
        $sql = "SELECT * FROM data_agenda";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }
    // get list media
    function get_list_media()
    {
        $sql = "SELECT * FROM data_media";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    // generate rekrutment id
    public function generate_rekrutmen_id()
    {
        // cari id terakhir
        $sql = "SELECT rekrutmen_id  AS last_number 
                FROM `rekrutmen`
                ORDER BY rekrutmen_id DESC LIMIT 1";
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


    /* CRUD */
    // insert rekrutmen
    function insert_rekrutmen($params)
    {
        return $this->db->insert('rekrutmen', $params);
    }

    // update lolos
    function update_lolos($params, $where)
    {
        return $this->db->update('rekrutmen', $params, $where);
    }
    // update rekrutment
    function update_rekrutment($params, $where)
    {
        return $this->db->update('rekrutmen', $params, $where);
    }
}
