<?php

class m_pengajuan extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get total pengajuan rka manajemen
    function get_total_pengajuan_rka_manajemen($params) {
        $sql = "SELECT COUNT(b.plan_id) 'total'
				FROM projects a
				INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
				WHERE a.jenis_kode_kegiatan = 'A' AND b.send_status != 'process' AND a.project_alias LIKE ? AND b.plan_status LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get list pengajuan rka manajemen
    function get_list_pengajuan_rka_manajemen($params) {
        $sql = "SELECT a.project_alias, b.plan_id, b.nilai_pendapatan, b.nilai_pajak, b.nilai_anggaran, b.nilai_biaya, b.plan_status, b.send_status
				FROM projects a
				INNER JOIN projects_budget_plan b ON a.project_id = b.project_id 
				WHERE a.jenis_kode_kegiatan = 'A' AND b.send_status != 'process' AND a.project_alias LIKE ? AND b.plan_status LIKE ?
				ORDER BY b.create_date DESC
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

    // get list projek
    function get_list_projek() {
        $sql = "SELECT *
                FROM projects
                WHERE jenis_kode_kegiatan = 'A'
                ORDER BY project_alias ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get plan id
    function get_plan_id($prefix, $params) {
        $sql = "SELECT RIGHT(plan_id, 4) 'plan_id'
                FROM projects_budget_plan
                WHERE plan_id LIKE ?
                ORDER BY plan_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $last_id = $result['plan_id'] + 1;
            $add = "";
            for ($i = 4; $i > strlen($last_id); $i--) {
                $add .= "0";
            }
            return $prefix . $add . $last_id;
        } else {
            return $prefix . '0001';
        }
    }

    // get plan by id
    function get_plan_by_id($params) {
        $sql = "SELECT a.*, b.project_name, b.project_alias
				FROM projects_budget_plan a 
                INNER JOIN projects b ON a.project_id = b.project_id
				WHERE a.plan_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list group
    function get_list_group() {
        $sql = "SELECT *
                FROM project_budget_group
                ORDER BY group_number ASC";
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
    function get_list_perusahaan() {
        $sql = "SELECT perusahaan_nama, perusahaan_id
                FROM data_perusahaan 
                ORDER BY perusahaan_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list akun_perusahaan
    function get_list_akun_perusahaan($params) {
        $sql = "SELECT kode_akun, kode_akun_alias, nama_akun, level_akun
                FROM data_akun
                WHERE perusahaan_id = ?
                ORDER BY nama_akun ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_data_akun_by_kode
    function get_data_akun_by_kode($params) {
        $sql = "SELECT a.kode_akun, a.nama_akun, b.perusahaan_nama, a.perusahaan_id
                FROM data_akun a
                INNER JOIN data_perusahaan b ON a.perusahaan_id = b.perusahaan_id
                WHERE a.kode_akun = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_item_id
    function get_item_id($prefix, $params) {
        $sql = "SELECT RIGHT(item_id, 2) 'item_id'
                FROM projects_budget_item
                WHERE item_id LIKE ?
                ORDER BY item_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $last_id = $result['item_id'] + 1;
            $add = "";
            for ($i = 2; $i > strlen($last_id); $i--) {
                $add .= "0";
            }
            return $prefix . $add . $last_id;
        } else {
            return $prefix . '01';
        }
    }

    // get_last_no
    function get_last_no($params) {
        $sql = "SELECT item_no
                FROM projects_budget_item
                WHERE plan_id = ?
                ORDER BY item_no DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['item_no'] + 1;
        } else {
            return 1;
        }
    }

    // get_list_item
    function get_list_item($params) {
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title, b.kode_akun_alias, c.perusahaan_id
                FROM projects_budget_item a
                INNER JOIN data_akun b ON a.kode_akun = b.kode_akun
                INNER JOIN data_perusahaan c ON a.perusahaan_id = c.perusahaan_id
                INNER JOIN project_budget_group d ON a.group_id = d.group_id
                WHERE a.plan_id = ?
                ORDER BY a.item_no ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_item_by_id
    function get_item_by_id($params) {
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title
                FROM projects_budget_item a
                INNER JOIN data_akun b ON a.kode_akun = b.kode_akun
                INNER JOIN data_perusahaan c ON a.perusahaan_id = c.perusahaan_id
                INNER JOIN project_budget_group d ON a.group_id = d.group_id
                WHERE a.item_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_list_item_and_detail
    function get_list_item_and_detail($params) {
        $sql = "SELECT a.*, b.nama_akun, c.perusahaan_nama, d.group_title, SUM(e.detail_sub_total) 'detail_sub_total', b.kode_akun_alias
                FROM projects_budget_item a
                INNER JOIN data_akun b ON a.kode_akun = b.kode_akun
                INNER JOIN data_perusahaan c ON a.perusahaan_id = c.perusahaan_id
                INNER JOIN project_budget_group d ON a.group_id = d.group_id
                LEFT JOIN projects_budget_detail e ON a.item_id = e.item_id
                WHERE a.plan_id = ?
                GROUP BY a.item_id
                ORDER BY a.item_no ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $rs_id = $query->result_array();
            $query->free_result();
            //
            $result = array();
            foreach ($rs_id as $i => $data) {
                $data['detail'] = $this->get_list_detail($data['item_id']);
                $data['no_urut'] = $this->get_last_detail_no($data['item_id']);
                $result[] = $data;
            }
            return $result;
        } else {
            return array();
        }
    }

    // get_list_detail
    function get_list_detail($params) {
        $sql = "SELECT *
                FROM projects_budget_detail
                WHERE item_id = ?
                ORDER BY detail_no ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get_last_detail_no
    function get_last_detail_no($params) {
        $sql = "SELECT detail_no
                FROM projects_budget_detail
                WHERE item_id = ?
                ORDER BY detail_no DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['detail_no'] + 1;
        } else {
            return 1;
        }
    }

    // get_detail_id
    function get_detail_id($prefix, $params) {
        $sql = "SELECT RIGHT(detail_id, 3) 'detail_id'
                FROM projects_budget_detail
                WHERE detail_id LIKE ?
                ORDER BY detail_id DESC
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $last_id = $result['detail_id'] + 1;
            $add = "";
            for ($i = 3; $i > strlen($last_id); $i--) {
                $add .= "0";
            }
            return $prefix . $add . $last_id;
        } else {
            return $prefix . '001';
        }
    }

    // get_detail_by_id
    function get_detail_by_id($params) {
        $sql = "SELECT a.*, b.plan_id
                FROM projects_budget_detail a
                INNER JOIN projects_budget_item b ON a.item_id = b.item_id
                WHERE a.detail_id LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
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

    // check_flow_process
    function check_flow_process($params) {
        $sql = "SELECT *
                FROM projects_budget_process
                WHERE plan_id = ? AND flow_id = ?";
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
                FROM projects_budget_process
                WHERE plan_id = ? 
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

    /* CRUD */
    // insert projects budget plan
    function insert_projects_budget_plan($params) {
        return $this->db->insert('projects_budget_plan', $params);
    }

    // update projects budget plan
    function update_projects_budget_plan($params, $where) {
        return $this->db->update('projects_budget_plan', $params, $where);
    }

    // delete projects budget plan
    function delete_projects_budget_plan($where) {
        return $this->db->delete('projects_budget_plan', $where);
    }

    // insert projects budget item
    function insert_projects_budget_item($params) {
        return $this->db->insert('projects_budget_item', $params);
    }

    // update projects budget item
    function update_projects_budget_item($params, $where) {
        return $this->db->update('projects_budget_item', $params, $where);
    }

    // delete projects budget item
    function delete_projects_budget_item($where) {
        return $this->db->delete('projects_budget_item', $where);
    }

    // insert projects budget detail
    function insert_projects_budget_detail($params) {
        return $this->db->insert('projects_budget_detail', $params);
    }

    // update projects budget detail
    function update_projects_budget_detail($params, $where) {
        return $this->db->update('projects_budget_detail', $params, $where);
    }

    // delete projects budget detail
    function delete_projects_budget_detail($where) {
        return $this->db->delete('projects_budget_detail', $where);
    }

    // insert projects budget process
    function insert_projects_budget_process($params) {
        return $this->db->insert('projects_budget_process', $params);
    }

    // update projects budget process
    function update_projects_budget_process($params, $where) {
        return $this->db->update('projects_budget_process', $params, $where);
    }

    // delete projects budget process
    function delete_projects_budget_process($where) {
        return $this->db->delete('projects_budget_process', $where);
    }

    // update_nilai_biaya
    function update_nilai_biaya($params) {
        // plan
        $sql = "UPDATE projects_budget_plan a 
                LEFT JOIN (
                    SELECT SUM(item_total) 'jumlah', plan_id FROM projects_budget_item
                    GROUP BY plan_id
                ) b ON a.plan_id = b.plan_id
                SET a.nilai_biaya = b.jumlah
                WHERE a.plan_id = ?";
        $query = $this->db->query($sql, $params);
        return;
    }

    // update_item_total
    function update_item_total($params) {
        // item
        $sql = "UPDATE projects_budget_item a 
                LEFT JOIN (
                    SELECT SUM(detail_sub_total) 'jumlah', item_id FROM projects_budget_detail
                    GROUP BY item_id
                ) b ON a.item_id = b.item_id
                SET a.item_total = b.jumlah
                WHERE a.item_id = ?";
        $query = $this->db->query($sql, $params);
        return;
    }

}
