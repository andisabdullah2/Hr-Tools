<?php

class m_project extends CI_Model {

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

    // get last plan id
    function get_last_plan_id($params) {
        $sql = "SELECT RIGHT(plan_id, 4) AS 'last_number'
                FROM projects_budget_plan 
                WHERE LEFT(plan_id, 6) LIKE ? 
                ORDER BY plan_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 9999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 4; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '0001';
        }
    }

    // get last item id
    function get_last_item_id($params) {
        $sql = "SELECT RIGHT(item_id, 2) AS 'last_number'
                FROM projects_budget_item 
                WHERE plan_id LIKE ?
                ORDER BY item_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 99) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 2; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '01';
        }
    }

    // get last detail id
    function get_last_detail_id($params) {
        $sql = "SELECT RIGHT(detail_id, 3) AS 'last_number'
                FROM projects_budget_detail 
                WHERE item_id LIKE ?
                ORDER BY detail_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 3; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '001';
        }
    }

    // get last kode output
    function get_last_kode_output($params) {
        $sql = "SELECT RIGHT(kode_output, 2) AS 'last_number'
                FROM rencana_output
                WHERE kode_kegiatan = ?
                ORDER BY kode_output DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 2) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 2; $i++) {
                $zero .= '0';
            }
            return $params . $zero . $number;
        } else {
            // create new number
            return $params . '.01';
        }
    }

    // get total plan project
    function get_total_plan_project($params){
    	$params = array($params[0], $params[1], $params[1], $params[2]);
    	$sql = "SELECT COUNT(*) as total 
    			FROM `projects_budget_plan` a
				INNER JOIN `projects` b ON b.`project_id` = a.`project_id`
				INNER JOIN task_flow c ON c.`flow_id` = get_last_flow_projects_budget_by_plan_id(a.plan_id)
				INNER JOIN projects_budget_process d ON d.`plan_id` = a.`plan_id` AND d.`flow_id` = c.`flow_id`
				WHERE b.`jenis_kode_kegiatan` = 'B' AND YEAR(b.`project_start`) LIKE ?
				AND (b.`project_name` LIKE ? OR b.`project_alias` LIKE ?) AND b.`struktur_cd` LIKE ?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result['total'];
		} else {
			return 0;
		}
    }

    // get list plan project
    function get_list_plan_project($params){
    	$params = array($params[0], $params[1], $params[1], $params[2], $params[3], $params[4]);
    	$sql = "SELECT a.*, b.`project_name`, b.`project_alias`, b.`struktur_cd`, c.`task_desc`,
				CASE WHEN d.process_st = 'waiting' THEN task_label_waiting
					WHEN d.process_st = 'approve' THEN task_label_approve
					WHEN d.process_st = 'reject' THEN task_label_reject
					END AS label
				FROM `projects_budget_plan` a
				INNER JOIN `projects` b ON b.`project_id` = a.`project_id`
				INNER JOIN task_flow c ON c.`flow_id` = get_last_flow_projects_budget_by_plan_id(a.plan_id)
				INNER JOIN projects_budget_process d ON d.`plan_id` = a.`plan_id` AND d.`flow_id` = c.`flow_id`
				WHERE b.`jenis_kode_kegiatan` = 'B' AND YEAR(b.`project_start`) LIKE ?
				AND (b.`project_name` LIKE ? OR b.`project_alias` LIKE ?) AND b.`struktur_cd` LIKE ?
				ORDER BY a.`create_date` DESC, a.`send_date` DESC 
				LIMIT ?,?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get data plan by id
    function get_data_plan_by_id($params){
    	$sql = "SELECT a.*, b.`project_name`, b.project_alias, b.struktur_cd, c.kode_output, c.kode_kegiatan, 
                c.jenis_kode_output, d.*, e.tahun, e.nama_program, f.nama_kegiatan, g.nama_output 
                FROM `projects_budget_plan` a
                INNER JOIN `projects` b ON b.`project_id` = a.`project_id`
                LEFT JOIN rencana_output c ON c.kode_output = a.kode_output
                LEFT JOIN rencana_kegiatan d ON d.kode_kegiatan = c.kode_kegiatan
                LEFT JOIN rencana_program e ON e.kode_program = d.kode_program
                LEFT JOIN data_jenis_kegiatan f ON f.jenis_kode_kegiatan = d.jenis_kode_kegiatan
                LEFT JOIN data_jenis_output g ON g.jenis_kode_output = c.jenis_kode_output
				WHERE b.jenis_kode_kegiatan = 'B' AND a.`plan_id` = ?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get list rencana item by plan id
    function get_list_rencana_item_by_plan_id($params){
    	$param = array($params[0], $params[0]);
    	$sql = "SELECT a.*, b.`group_title`, c.`nama_akun`, c.`kode_akun_alias`, d.`perusahaan_nama`, 
    			IFNULL(total,0) AS total_detail_sub_total  
				FROM `projects_budget_item` a
				LEFT JOIN `project_budget_group` b ON b.`group_id` = a.`group_id`
				LEFT JOIN data_akun c ON c.`kode_akun` = a.`kode_akun`
				LEFT JOIN data_perusahaan d ON d.`perusahaan_id` = a.`perusahaan_id`
				LEFT JOIN `projects_budget_plan` e ON e.`plan_id` = a.`plan_id`
				LEFT JOIN `projects` f ON f.`project_id` = e.`project_id`
				LEFT JOIN (
					SELECT aa.`item_id`, bb.`plan_id`, SUM(detail_sub_total) AS total 
					FROM projects_budget_detail aa
					LEFT JOIN projects_budget_item bb ON bb.`item_id` = aa.`item_id`
					GROUP BY item_id
				) g ON g.item_id = a.`item_id` AND g.plan_id = ?
				WHERE f.`jenis_kode_kegiatan` = 'B' AND a.`plan_id` = ?
				ORDER BY a.item_no DESC";
		$query = $this->db->query($sql,$param);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get list rencana detail by item id
    function get_list_rencana_detail_by_item_id($params){
        $sql = "SELECT * FROM projects_budget_detail WHERE item_id = ?
                ORDER BY detail_no DESC";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list item and detail by plan id
    function get_list_item_and_detail_by_plan_id($params){
    	// set 
    	$array = array();
    	// get list rencana item
    	$rs_item = $this->get_list_rencana_item_by_plan_id(array($params));
    	foreach ($rs_item as $key => $result) {
    		// get budget detail
    		$rs_detail = $this->get_list_rencana_detail_by_item_id($result['item_id']);
    		// set
    		$array[$key] = $result;
    		$array[$key]['rs_detail'] = $rs_detail;
    	}
    	// return 
    	return $array;
    }

    // get data rencana item by id
    function get_data_rencana_item_by_id($params){
    	$sql = "SELECT a.*, b.`nilai_biaya`, d.`group_title`, e.`kode_akun_alias`, e.`nama_akun`, f.`perusahaan_nama`, 
    			(nilai_biaya-item_total) AS new_nilai_biaya
				FROM projects_budget_item a
				INNER JOIN projects_budget_plan b ON b.`plan_id` = a.`plan_id`
				INNER JOIN projects c ON c.`project_id` = b.`project_id`
				LEFT JOIN project_budget_group d ON d.`group_id` = a.`group_id`
				LEFT JOIN data_akun e ON e.`kode_akun` = a.`kode_akun`
				LEFT JOIN data_perusahaan f ON f.`perusahaan_id` = a.`perusahaan_id`
				WHERE c.`jenis_kode_kegiatan` = 'B' AND a.item_id = ?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get nilai by item id
    function get_nilai_by_item_id($params){
    	$sql = "SELECT b.item_id, c.plan_id, b.`item_id`, c.`plan_id`, c.`nilai_biaya`, b.`item_volume`, b.`item_harga`, 
		    	b.`item_total`, SUM(detail_sub_total) AS new_item_total,
				(nilai_biaya-item_total) AS delete_nilai_biaya,
				((nilai_biaya-item_total)+SUM(detail_sub_total)) AS new_nilai_biaya,
				SUM(detail_volume) AS new_item_volume,
				ROUND(SUM(detail_sub_total)/SUM(detail_volume),2) AS new_item_harga
				FROM projects_budget_detail a
				LEFT JOIN projects_budget_item b ON b.`item_id` = a.`item_id`
				LEFT JOIN projects_budget_plan c ON c.`plan_id` = b.`plan_id`
				LEFT JOIN data_akun d ON d.kode_akun = b.kode_akun
				LEFT JOIN data_perusahaan e ON e.perusahaan_id = b.perusahaan_id
				WHERE b.`item_id` = ?
					GROUP BY b.item_id";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get list rencana output by plan id
    function get_list_rencana_output_by_plan_id($params){
    	$sql = "SELECT a.*, d.`nama_output`, e.jenis_kode_kegiatan, f.`nama_program`, f.tahun, g.`nama_kegiatan` 
				FROM rencana_output a
				LEFT JOIN projects b ON b.`project_id` = a.`project_id`
				LEFT JOIN projects_budget_plan c ON c.`project_id` = b.`project_id`
				INNER JOIN data_jenis_output d ON d.`jenis_kode_output` = a.`jenis_kode_output`
				INNER JOIN rencana_kegiatan e ON e.`kode_kegiatan` = a.`kode_kegiatan`
				INNER JOIN rencana_program f ON f.`kode_program` = e.`kode_program`
				INNER JOIN data_jenis_kegiatan g ON g.`jenis_kode_kegiatan` = e.`jenis_kode_kegiatan`
				WHERE c.`plan_id` = ?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get item no rencana item by plan id
    function get_item_no_rencana_item_by_plan_id($params){
    	$sql = "SELECT item_no FROM projects_budget_item 
    			WHERE plan_id = ? ORDER BY item_no DESC";
    	$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result['item_no']+1;
		} else {
			return 1;
		}
    }

    // get detail no rencana detail item by item id
    function get_detail_no_rencana_detail_by_item_id($params){
    	$sql = "SELECT detail_no FROM projects_budget_detail 
    			WHERE item_id = ? ORDER BY detail_no DESC";
    	$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$query->free_result();
			return $result['detail_no']+1;
		} else {
			return 1;
		}
    }

    // get first flow id by group id
    function get_first_flow_by_group_id($params){
    	$sql = "SELECT flow_id FROM task_flow 
    			WHERE group_id = '15' AND task_number <> '' ORDER BY task_number";
    	$query = $this->db->query($sql,$params);
    	if ($query->num_rows() > 0) {
    		$result = $query->row_array();
    		$query->free_result();
    		return $result['flow_id'];
    	} else {
    		return null;
    	}
    }

    // get next flow id by plan id
    function get_next_flow_id_by_plan_id($params){
    	$sql = "SELECT a.*, c.flow_id AS next_flow_id, c.task_number 
				FROM projects_budget_process a
				INNER JOIN task_flow b ON b.`flow_id` = a.`flow_id`
				INNER JOIN (
					SELECT * FROM task_flow WHERE task_number <> ''
					) c ON c.group_id = b.`group_id` AND c.task_number = (b.`task_number`+1)
				WHERE b.task_number <> '' AND plan_id = ?";
		$query = $this->db->query($sql,$params);
    	if ($query->num_rows() > 0) {
    		$result = $query->row_array();
    		$query->free_result();
    		return $result;
    	} else {
    		return array();
    	}
    }

    // get list akun by perusahaan id
    function get_list_akun_by_perusahaan_id($params){
    	$sql = "SELECT a.* FROM data_akun a
				INNER JOIN data_perusahaan b ON b.`perusahaan_id` = a.`perusahaan_id`
				WHERE b.`perusahaan_id` = ? 
				ORDER BY a.`kode_akun`";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get list plan process by plan id
    function get_list_plan_process_by_plan_id($params){
    	$sql = "SELECT a.*, b.`flow_id` AS process_flow_id, b.process_st, c.create_by_name, c.create_date,
                c.catatan, b.mdb_finish_name, b.mdd_finish,
    			CASE WHEN b.process_st = 'waiting' THEN task_label_waiting
					WHEN b.process_st = 'approve' THEN task_label_approve
					WHEN b.process_st = 'reject' THEN task_label_reject
					END AS label
				FROM task_flow a
				LEFT JOIN projects_budget_process b ON b.`flow_id` = a.`flow_id` AND b.`plan_id` = ?
				LEFT JOIN projects_budget_plan c ON c.plan_id = b.plan_id
				WHERE group_id = '15'
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

    // check data keseluruhan plan
    function is_exist_plan_projects_by_plan_id($params){
    	$sql = "SELECT a.`nilai_biaya`, c.`detail_id`, item_total, detail_sub_total
                FROM projects_budget_plan a
                LEFT JOIN (
                    SELECT item_id, plan_id, SUM(item_total) AS item_total
                    FROM projects_budget_item 
                    GROUP BY plan_id
                ) b ON b.`plan_id` = a.`plan_id`
                LEFT JOIN (
                    SELECT aa.detail_id, aa.item_id, SUM(detail_sub_total) AS detail_sub_total, bb.`plan_id`
                    FROM projects_budget_detail aa
                    LEFT JOIN projects_budget_item bb ON bb.`item_id` = aa.`item_id`
                    GROUP BY plan_id
                ) c ON c.`item_id` = b.`item_id` AND c.plan_id = a.`plan_id`
                LEFT JOIN projects e ON e.`project_id` = a.`project_id`
                WHERE a.`plan_id` = ?";
		$query = $this->db->query($sql,$params);
		if ($query->num_rows() > 0) {
            $result = $query->row_array();
			$query->free_result();
			// check nilai total
            if ($result['item_total'] != $result['detail_sub_total']) {
                return FALSE;
            }
            // check detail
            if (empty($result['detail_id'])) {
                return FALSE;
            }
            return TRUE;
		} else {
			return FALSE;
		}
    }

    // check rencana output by plan id
    function is_exist_rencana_output_by_plan_id($params){
        $sql = "SELECT a.* FROM rencana_output a
                LEFT JOIN projects_budget_plan b ON b.kode_output = a.kode_output
                WHERE b.`plan_id` = ?";
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $query->free_result();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get all project rka project
    function get_all_project_rka_project(){
    	$sql = "SELECT * FROM projects 
    			WHERE jenis_kode_kegiatan = 'B'
				ORDER BY `project_start` DESC";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
    }

    // get all tahun project
    function get_all_tahun_project(){
    	$sql = "SELECT YEAR(`project_start`) as tahun FROM `projects` 
    			WHERE `jenis_kode_kegiatan` = 'B' 
    			GROUP BY YEAR(`project_start`) 
    			ORDER BY YEAR(`project_start`) DESC";
    	$query = $this->db->query($sql);
    	if ($query->num_rows() > 0) {
    		$result = $query->result_array();
    		$query->free_result();
    		return $result;
    	} else {
    		return array();
    	}
    }

    // get all unit_kerja
    function get_list_unit_kerja() {
        $sql = "SELECT * FROM data_struktur_organisasi ORDER BY struktur_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list perusahaan
    function get_list_perusahaan(){
    	$sql = "SELECT `perusahaan_id`, `perusahaan_nama` FROM data_perusahaan ORDER BY perusahaan_nama";
    	$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list group project
    function get_list_project_group(){
    	$sql = "SELECT * FROM project_budget_group ORDER BY group_number";
    	$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list jenis output
    function get_list_jenis_output(){
    	$sql = "SELECT * FROM data_jenis_output ORDER BY nama_output";
    	$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }	
    }

    // get list jenis kegiatan
    function get_list_jenis_kegiatan(){
    	$sql = "SELECT * FROM data_jenis_kegiatan ORDER BY nama_kegiatan";
    	$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list rencana program
    function get_list_rencana_program($params){
    	$sql = "SELECT a.*, b.kode_kegiatan 
    			FROM rencana_program a
				INNER JOIN rencana_kegiatan b ON b.`kode_program` = a.`kode_program`
				WHERE a.`struktur_cd` = ? AND b.`jenis_kode_kegiatan` = ?
				ORDER BY a.`tahun` DESC, a.`nama_program` ";
		$query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    // insert projects budget plan
    function insert_projects_budget_plan($params){
    	return $this->db->insert('projects_budget_plan',$params);
    }

    // insert projects_budget_process
    function insert_projects_budget_process($params){
    	return $this->db->insert('projects_budget_process',$params);
    }

    // insert projects_budget_item
    function insert_projects_budget_item($params){
    	return $this->db->insert('projects_budget_item',$params);
    }

    // insert projects budget detail
    function insert_projects_budget_detail($params){
    	return $this->db->insert('projects_budget_detail',$params);
    }

    // insert rencana output
    function insert_rencana_output($params){
    	return $this->db->insert('rencana_output',$params);
    }

    // update_projects_budget_plan
    function update_projects_budget_plan($params,$where){
    	return $this->db->update('projects_budget_plan',$params,$where);
    }

    // update_projects_budget_process
    function update_projects_budget_process($params,$where){
    	return $this->db->update('projects_budget_process',$params,$where);
    }

    // update projects budget item
    function update_projects_budget_item($params,$where){
    	return $this->db->update('projects_budget_item',$params,$where);
    }

    // update projects budget detail
    function update_projects_budget_detail($params,$where){
    	return $this->db->update('projects_budget_detail',$params,$where);
    }

    // update rencana output
    function update_rencana_output($params,$where){
    	return $this->db->update('rencana_output',$params,$where);
    }

    // delete projects budget plan
    function delete_projects_budget_plan($where){
    	return $this->db->delete('projects_budget_plan',$where);
    }

    // delete projects budget item
    function delete_projects_budget_item($where){
    	return $this->db->delete('projects_budget_item',$where);
    }

    // delete projects budget detail
    function delete_projects_budget_detail($where){
    	return $this->db->delete('projects_budget_detail',$where);
    }
}
