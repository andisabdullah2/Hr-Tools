<?php

class m_pengajuan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get id
    function get_id(){
    	$time = microtime(true);
        $id = str_replace('.', '', $time);
        $id = str_replace(',', '', $id);
        return $id;
    }

    // get last trx id
    function get_trx_id($params) {
        $sql = "SELECT RIGHT(trx_id,9)'last_number'
                FROM trx_advance
                WHERE LEFT(trx_id, 6) LIKE ?
                ORDER BY trx_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 999999999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 9; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '000000001';
        }
    }

    // get last data id
    function get_data_id($params) {
        $sql = "SELECT RIGHT(data_id,5)'last_number'
                FROM trx_advance_pembelian
                WHERE trx_id LIKE ?
                ORDER BY data_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 99999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 5; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '00001';
        }
    }

    // get total advance
    function get_total_advance($params){
    	$param = array($params[0], $params[1], $params[2], $params[2]);
    	$sql = "SELECT COUNT(*) as total
				FROM trx_advance a
				INNER JOIN rencana_item b ON b.`kode_item` = a.`kode_item`
				INNER JOIN data_struktur_organisasi c ON c.`struktur_cd` = a.`struktur_cd`
				INNER JOIN trx_advance_process d ON d.process_id = get_last_flow_advance_by_id(a.trx_id)
                INNER JOIN task_flow e ON e.flow_id = IFNULL(d.flow_id,d.flow_revisi_id)
				WHERE a.`group_id` = ? AND a.`struktur_cd` = ? AND (a.advance_uraian LIKE ? OR b.item_uraian LIKE ?)";
    	$query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get list advance
    function get_list_advance($params){
    	$param = array($params[0], $params[1], $params[2], $params[2], $params[3], $params[4]);
    	$sql = "SELECT a.*, b.`item_uraian`, c.`struktur_nama`, c.struktur_singkatan, e.task_desc, d.process_st,
		    	CASE WHEN d.process_st = 'waiting' THEN task_label_waiting
					WHEN d.process_st = 'approve' THEN task_label_approve
					WHEN d.process_st = 'reject' THEN task_label_reject
					END AS task_label
				FROM trx_advance a
				INNER JOIN rencana_item b ON b.`kode_item` = a.`kode_item`
				INNER JOIN data_struktur_organisasi c ON c.`struktur_cd` = a.`struktur_cd`
                INNER JOIN trx_advance_process d ON d.process_id = get_last_flow_advance_by_id(a.trx_id)
                INNER JOIN task_flow e ON e.flow_id = IFNULL(d.flow_id,d.flow_revisi_id)
				WHERE a.`group_id` = ? AND a.`struktur_cd` = ? AND (a.advance_uraian LIKE ? OR b.item_uraian LIKE ?)
				ORDER BY a.advance_tanggal DESC LIMIT ?,?";
		$query = $this->db->query($sql, $param);
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
        $sql = "SELECT a.*, b.group_name, c.kode_output, c.item_no, c.item_uraian, d.struktur_nama, d.struktur_singkatan,
                IFNULL(send_by_name,a.mdb_name) as send_name
                FROM trx_advance a
                INNER JOIN task_group b ON a.group_id = b.group_id
                INNER JOIN rencana_item c ON a.kode_item = c.kode_item
                INNER JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                WHERE a.group_id = ? AND a.trx_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list rincian item pembelian
    function get_list_rincian_item_pembelian($params){
    	$sql = "SELECT * FROM trx_advance_pembelian WHERE trx_id = ? 
    			ORDER BY data_id DESC";
    	$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }	
    }

    // get data rincian item pembelian by id
    function get_data_rincian_item_pembelian_by_id($params){
    	$sql = "SELECT * FROM trx_advance_pembelian WHERE data_id = ?";
    	$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }	
    }

    // get_pegawai_by_user_id
    function get_pegawai_by_user_id($params) {
        $sql = "SELECT * FROM pegawai
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

    // get first flow id by group id
    function get_first_flow_by_group_id($params){
    	$sql = "SELECT flow_id FROM task_flow 
    			WHERE group_id = ? AND task_number <> '' ORDER BY task_number";
    	$query = $this->db->query($sql,$params);
    	if ($query->num_rows() > 0) {
    		$result = $query->row_array();
    		$query->free_result();
    		return $result['flow_id'];
    	} else {
    		return null;
    	}
    }

    // get_last_process_flow
    function get_last_process_flow($params) {
        $sql = "SELECT b.*, IFNULL(b.`flow_id`,b.`flow_revisi_id`) AS previous_flow_id, c.flow_id as next_flow_id
                FROM task_flow a
                INNER JOIN trx_advance_process b ON IFNULL(b.`flow_id`,b.`flow_revisi_id`) = a.`flow_id`
                INNER JOIN task_flow c ON c.group_id = a.group_id AND c.task_number = (a.task_number+1)
                WHERE b.`trx_id` = ?
                ORDER BY process_id DESC LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get next flow by trx id
    function get_next_flow_id_by_trx_id($params){
    	$sql = "SELECT a.*, c.flow_id AS next_flow_id, c.task_number 
				FROM trx_advance_process a
				INNER JOIN task_flow b ON b.`flow_id` = a.`flow_id`
				INNER JOIN (
					SELECT * FROM task_flow WHERE task_number <> ''
					) c ON c.group_id = b.`group_id` AND c.task_number = (b.`task_number`+1)
				WHERE b.task_number <> '' AND trx_id = ?
                ORDER BY task_number DESC";
		$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_rencana_item
    function get_list_rencana_item() {
        $sql = "SELECT * FROM rencana_item
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

    // get last advance no
    function get_last_advance_no($params){
    	$sql = "SELECT IFNULL((advance_no+1),1) as advance_no 
    			FROM trx_advance WHERE group_id = ? 
    			ORDER BY advance_no DESC";
    	$query = $this->db->query($sql,$params);
    	if ($query->num_rows() > 0) {
    		$result = $query->row_array();
    		$query->free_result();
    		return $result['advance_no'];
    	} else {
    		return 1;
    	}
    }

    // insert_trx_advance
    function insert_trx_advance($params) {
        return $this->db->insert('trx_advance', $params);
    }

    // insert_trx_advance_pembelian
    function insert_trx_advance_pembelian($params) {
        return $this->db->insert('trx_advance_pembelian', $params);
    }

    // insert_trx_advance_process
    function insert_trx_advance_process($params) {
        return $this->db->insert('trx_advance_process', $params);
    }

    // update_trx_advance
    function update_trx_advance($params, $where) {
        return $this->db->update('trx_advance', $params, $where);
    }

    // update_trx_advance_pembelian
    function update_trx_advance_pembelian($params, $where) {
        return $this->db->update('trx_advance_pembelian', $params, $where);
    }

    // update_trx_advance_process
    function update_trx_advance_process($params,$where){
    	return $this->db->update('trx_advance_process',$params,$where);
    }

    // delete trx advance
    function delete_trx_advance($where){
    	return $this->db->delete('trx_advance',$where);
    }

    // delete trx advance pembelian
    function delete_trx_advance_pembelian($where){
    	return $this->db->delete('trx_advance_pembelian',$where);
    }
}
