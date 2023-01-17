<?php

class m_approval extends CI_Model {

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

    // get total advance
    function get_total_advance($params){
    	$param = array($params[0], $params[1], $params[2], $params[2]);
    	$sql = "SELECT COUNT(*) as total
				FROM trx_advance a
				INNER JOIN rencana_item b ON b.`kode_item` = a.`kode_item`
				INNER JOIN data_struktur_organisasi c ON c.`struktur_cd` = a.`struktur_cd`
				INNER JOIN trx_advance_process d ON d.process_id = get_last_flow_advance_by_id(a.trx_id)
                INNER JOIN task_flow e ON e.flow_id = d.flow_id
				WHERE a.`group_id` = ? AND e.flow_id = ? AND (a.advance_uraian LIKE ? OR b.item_uraian LIKE ?)";
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
                INNER JOIN task_flow e ON e.flow_id = d.flow_id
				WHERE a.`group_id` = ? AND e.flow_id = ? AND (a.advance_uraian LIKE ? OR b.item_uraian LIKE ?)
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
        		IFNULL(total,0) AS total_rincian, e.process_id, e.`process_st`, e.`catatan`
                FROM trx_advance a
                INNER JOIN task_group b ON a.group_id = b.group_id
                INNER JOIN rencana_item c ON a.kode_item = c.kode_item
                INNER JOIN data_struktur_organisasi d ON a.struktur_cd = d.struktur_cd
                INNER JOIN trx_advance_process e ON e.process_id = get_last_flow_advance_by_id(a.trx_id)
                INNER JOIN task_flow f ON f.flow_id = IFNULL(e.flow_id,e.flow_revisi_id)
                LEFT JOIN (
                	SELECT trx_id, SUM(item_total) AS total 
                	FROM trx_advance_pembelian GROUP BY trx_id
                ) g ON g.trx_id = a.`trx_id`
                WHERE a.group_id = ? AND f.flow_id = ? AND a.trx_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get trx advance process by id
    function get_trx_advance_process_by_id($params){
    	$sql = "SELECT a.*, IFNULL(b.flow_id,b.flow_revisi_id) AS previous_flow_id 
                FROM trx_advance_process a
                LEFT JOIN trx_advance_process b ON b.process_id = a.process_references_id
                WHERE a.process_id = ?";
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

    // get list advance process by trx id
    function get_list_advance_process_by_trx_id($params){
    	$sql = "SELECT a.*, b.`flow_id` AS process_flow_id, b.process_st, c.send_by_name, c.send_date, 
    			b.catatan, b.mdb_finish_name, b.mdd_finish,
    			CASE WHEN b.process_st = 'waiting' THEN task_label_waiting
					WHEN b.process_st = 'approve' THEN task_label_approve
					WHEN b.process_st = 'reject' THEN task_label_reject
					END AS label
				FROM task_flow a
				INNER JOIN trx_advance_process b ON b.`flow_id` = a.`flow_id` AND b.`trx_id` = ?
				INNER JOIN trx_advance c ON c.trx_id = b.trx_id
				WHERE a.group_id = ?
				ORDER BY task_number";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
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

    // insert_trx_advance_process
    function insert_trx_advance_process($params){
    	return $this->db->insert('trx_advance_process',$params);
    }

    // update_trx_advance
    function update_trx_advance($params,$where){
    	return $this->db->update('trx_advance',$params,$where);
    }

    // update_trx_advance_process
    function update_trx_advance_process($params,$where){
    	return $this->db->update('trx_advance_process',$params,$where);
    }

}
