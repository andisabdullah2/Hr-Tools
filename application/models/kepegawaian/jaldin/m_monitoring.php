<?php

class m_monitoring extends CI_Model {

    // construct
    public function __construct() {
        parent::__construct();
    }

    /*
     * JALDIN
     */

    // get list tahun
    function get_list_tahun_jaldin() {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(tanggal_berangkat)'tahun'
                        FROM surat_tugas
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
        $sql = "SELECT COUNT(*)'total'
                FROM surat_tugas a
                LEFT JOIN projects b ON a.project_id = b.project_id
                LEFT JOIN pegawai c ON c.user_id = a.user_id
                WHERE b.project_alias LIKE ? AND c.nama_lengkap LIKE ?
                AND YEAR(a.tanggal_berangkat) = ? AND MONTH(a.tanggal_berangkat) LIKE ?";
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
        $sql = "SELECT a.*, b.project_alias, b.project_name, b.project_desc, c.nama_lengkap, 
                get_last_flow_jaldin_by_id(a.`spt_id`)'last_process',
                SUBSTRING_INDEX(SUBSTRING_INDEX(get_last_flow_jaldin_by_id(a.`spt_id`),'[',-1),']',1)'status',
                d.process_st, d.role_name, d.task_name
                FROM surat_tugas a
                LEFT JOIN projects b ON a.project_id = b.project_id
                LEFT JOIN pegawai c ON c.user_id = a.user_id
                LEFT JOIN (
                    SELECT * FROM (
                        SELECT spt_id, process_id, process_st, a.flow_id, b.role_name, b.task_name
                        FROM surat_tugas_process a 
                        LEFT JOIN task_flow b ON a.flow_id = b.flow_id
                        ORDER BY process_id DESC
                    )e GROUP BY spt_id
                )d ON a.`spt_id` = d.spt_id
                WHERE b.project_alias LIKE ? AND c.nama_lengkap LIKE ?
                AND YEAR(a.tanggal_berangkat) = ? AND MONTH(a.tanggal_berangkat) LIKE ?
                GROUP BY a.`spt_id` ORDER BY a.tanggal_berangkat DESC
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

    // get detail jaldin by id
    function get_detail_jaldin_by_id($params) {
        $sql = "SELECT a.*, b.`pegawai_nip`, b.`nama_lengkap`, d.`project_alias`, d.`project_name`, d.`project_desc`, 
                e.item_uraian, e.kode_akun, e.kode_output, e.item_jumlah, total_hari, 
                f.`client_address`, f.`client_desc`, f.`client_nm`, total_advance, g.struktur_nama, g.struktur_singkatan, IF(b.jabatan_struktural_st = '1',h.jabatan_nama,'-')'jabatan'
                FROM surat_tugas a
                LEFT JOIN pegawai b ON a.`user_id` = b.`user_id`
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
                LEFT JOIN data_struktur_organisasi g ON a.struktur_cd = g.struktur_cd
                LEFT JOIN data_jabatan_struktural h ON b.jabatan_struktural_id = h.jabatan_struktural_id
                WHERE a.spt_id = ?";
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
        $sql = "SELECT a.*, b.`jenis_biaya`, d.`nama_lengkap`
                FROM surat_tugas_lpj a
                LEFT JOIN data_jenis_pengeluaran b ON a.`jenis_id` = b.`jenis_id`
                LEFT JOIN surat_tugas c ON a.`spt_id` = c.`spt_id`
                LEFT JOIN pegawai d ON c.`user_id` = d.`user_id`
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

    // // get list duty advance
    // function get_list_duty_advance($params) {
    //     $sql = 'SELECT a.* FROM users_duty_advance a
    //             INNER JOIN users_duty b ON a.duty_id = b.duty_id
    //             WHERE a.duty_id = ?';
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get list laporan perjalanan dinas
    // function get_list_duty_lpj($params) {
    //     $sql = 'SELECT a.*, c.full_name
    //             FROM users_duty_lpj a
    //             INNER JOIN users_duty b ON a.duty_id = b.duty_id
    //             LEFT JOIN users c ON a.mdb = c.user_id
    //             WHERE a.duty_id = ?
    //             ORDER BY tanggal ASC';
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get list duty advance
    // function get_total_duty_advance_approved($params) {
    //     $sql = "SELECT SUM(jumlah)'total' FROM users_duty_advance a
    //             INNER JOIN users_duty b ON a.duty_id = b.duty_id
    //             WHERE a.duty_id = ? AND kredit = '1'";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['total'];
    //     } else {
    //         return 0;
    //     }
    // }

    // // get tunjangan jaldin by id
    // function get_tunjangan_jaldin($params) {
    //     $sql = "SELECT * FROM users_duty_lpj 
    //             WHERE duty_id = ? AND (LOWER(uraian) LIKE '%makan%' OR LOWER(uraian) LIKE '%uang saku%')";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         $data = array('makan' => 0, 'saku' => 0);
    //         foreach ($result as $value) {
    //             $data[strtolower($value['uraian'])] = $value['debit'];
    //         }
    //         return $data;
    //     } else {
    //         return array('uang makan' => 0, 'saku' => 0);
    //     }
    // }

    // /*
    //  * OVERTIME
    //  */

    // // total
    // function get_total_overtime_by_params($params) {
    //     $sql = "SELECT COUNT(*)'total' FROM
    //             (
    //                 SELECT a.overtime_id 
    //                 FROM projects_overtime a
    //                 INNER JOIN users_overtime b ON a.overtime_id = b.overtime_id
    //                 INNER JOIN users c ON b.user_id = c.user_id
    //                 INNER JOIN projects d ON d.project_id = a.project_id
    //                 WHERE d.project_alias LIKE ? AND c.full_name LIKE ?
    //                 AND a.overtime_date BETWEEN ? AND ?
    //                 GROUP BY a.overtime_id
    //             ) result";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['total'];
    //     } else {
    //         return array();
    //     }
    // }

    // // get all data
    // function get_all_overtime_by_params($params) {
    //     $sql = "SELECT a.*, d.project_alias, c.full_name, e.process_st, e.role_nm, flow_name, COUNT(b.user_id)'total_personel'
    //             FROM projects_overtime a
    //             INNER JOIN users_overtime b ON a.overtime_id = b.overtime_id
    //             INNER JOIN users c ON b.user_id = c.user_id
    //             INNER JOIN projects d ON d.project_id = a.project_id
    //             LEFT JOIN 
    //             (
    //                 SELECT * FROM
    //                 (
    //                     SELECT u.process_id, u.overtime_id, u.action_st, u.process_st, r.role_nm, flow_name
    //                     FROM projects_overtime_process u
    //                     INNER JOIN task_flow t ON t.flow_id = u.flow_id
    //                     INNER JOIN com_role r ON r.role_id = t.role_id
    //                     ORDER BY process_id DESC
    //                 ) pr
    //                 GROUP BY overtime_id
    //             ) e ON e.overtime_id = a.overtime_id
    //             WHERE d.project_alias LIKE ? AND c.full_name LIKE ? 
    //             AND a.overtime_date BETWEEN ? AND ?
    //             GROUP BY a.overtime_id
    //             ORDER BY a.mdd DESC
    //             LIMIT ?, ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get detail overtime
    // function get_detail_overtime_by_id($params) {
    //     $sql = "SELECT a.*, b.project_alias, department_lead
    //             FROM projects_overtime a
    //             INNER JOIN projects b ON a.project_id = b.project_id
    //             INNER JOIN department c ON b.department_id = c.department_id
    //             WHERE a.overtime_id = ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get all user by process id
    // function get_all_personel_overtime_by_id($params) {
    //     $sql = "SELECT full_name
    //             FROM projects_overtime a
    //             INNER JOIN users_overtime b ON a.overtime_id = b.overtime_id
    //             INNER JOIN users c ON b.user_id = c.user_id
    //             WHERE a.overtime_id = ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // /*
    //  * IJIN
    //  */

    // // get list tahun_permit
    // function get_list_tahun_permit() {
    //     $sql = "SELECT DISTINCT tahun FROM
    //             (
    //                     SELECT YEAR(permit_date)'tahun'
    //                     FROM users_permit
    //                     UNION ALL
    //                     SELECT YEAR(CURRENT_DATE)'tahun'
    //             ) rs
    //             ORDER BY tahun ASC";
    //     $query = $this->db->query($sql);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get total permit
    // function get_total_permit($params) {
    //     $sql = "SELECT COUNT(a.permit_id)'total'
    //             FROM users_permit a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             WHERE b.full_name LIKE ? AND YEAR(a.permit_date) = ? 
    //             AND MONTH(a.permit_date) = ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['total'];
    //     } else {
    //         return 0;
    //     }
    // }

    // // get all permit by limit
    // function get_all_permit_by_limit($params) {
    //     $sql = "SELECT a.*, b.full_name, process_st, role_nm, flow_name
    //             FROM users_permit a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             LEFT JOIN 
    //             (
    //                 SELECT * FROM
    //                 (
    //                         SELECT u.process_id, u.permit_id, u.action_st, 
    //                         u.process_st, r.role_nm, flow_name
    //                         FROM users_permit_process u
    //                         INNER JOIN task_flow t ON t.flow_id = u.flow_id
    //                         INNER JOIN com_role r ON r.role_id = t.role_id
    //                         ORDER BY process_id DESC
    //                 ) pr
    //                 GROUP BY permit_id
    //             ) f ON a.permit_id = f.permit_id
    //             WHERE b.full_name LIKE ? AND YEAR(a.permit_date) = ? 
    //             AND MONTH(a.permit_date) = ?
    //             ORDER BY a.mdd DESC
    //             LIMIT ?, ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get detail permit
    // function get_detail_permit_by_id($permit_id) {
    //     $sql = "SELECT a.*, b.full_name, department_name, department_lead
    //             FROM users_permit a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             INNER JOIN department c ON b.department_id = c.department_id
    //             WHERE a.permit_id = ?";
    //     $query = $this->db->query($sql, $permit_id);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // /*
    //  * LEAVE
    //  */

    // // get list cuti
    // function get_list_tahun_leave() {
    //     $sql = "SELECT DISTINCT tahun FROM
    //             (
    //                     SELECT YEAR(leave_date_start)'tahun'
    //                     FROM users_leave
    //                     UNION ALL
    //                     SELECT YEAR(CURRENT_DATE)'tahun'
    //             ) rs
    //             ORDER BY tahun ASC";
    //     $query = $this->db->query($sql);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get total leave
    // function get_total_leave($params) {
    //     $sql = "SELECT COUNT(a.leave_id)'total'
    //             FROM users_leave a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             WHERE b.full_name LIKE ? AND YEAR(a.leave_date_start) = ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['total'];
    //     } else {
    //         return array();
    //     }
    // }

    // // get list leave limit
    // function get_all_leave_by_limit($params) {
    //     $sql = "SELECT a.*, b.full_name, c.full_name'pic_name', process_st, role_nm, flow_name
    //             FROM users_leave a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             LEFT JOIN users c ON a.leave_pic = c.user_id
    //             LEFT JOIN 
    //             (
    //                 SELECT * FROM
    //                 (
    //                         SELECT u.process_id, u.leave_id, u.action_st, u.process_st, r.role_nm, flow_name
    //                         FROM users_leave_process u
    //                         INNER JOIN task_flow t ON t.flow_id = u.flow_id
    //                         INNER JOIN com_role r ON r.role_id = t.role_id
    //                         ORDER BY process_id DESC
    //                 ) pr
    //                 GROUP BY leave_id
    //             ) f ON a.leave_id = f.leave_id
    //             WHERE b.full_name LIKE ? AND YEAR(a.leave_date_start) = ?
    //             ORDER BY a.mdd DESC
    //             LIMIT ?, ?";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->result_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get list leave limit
    // function get_detail_leave_by_id($params) {
    //     $sql = "SELECT a.*, b.full_name, c.full_name'pic_name', c.employee_position'pic_position' , department_name, department_lead,
    //             b.phone_number, COUNT(e.leave_date)'masa_cuti'
    //             FROM users_leave a
    //             INNER JOIN users b ON a.user_id = b.user_id
    //             LEFT JOIN users c ON a.leave_pic = c.user_id
    //             LEFT JOIN department d ON a.leave_department = d.department_id
    //             LEFT JOIN users_leave_date e ON a.leave_id = e.leave_id
    //             WHERE a.leave_id = ?
    //             GROUP BY a.leave_id";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    // // get kuota by type
    // function get_kuota_by_type($params) {
    //     $sql = "SELECT b.total as total_kuota
    //             FROM users_leave a
    //             INNER JOIN users_leave_quota b
    //             ON a.user_id = b.user_id
    //             WHERE a.user_id = ? AND b.leave_type = ? AND b.tahun = YEAR(CURRENT_DATE)";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['total_kuota'];
    //     } else {
    //         return 0;
    //     }
    // }

    // // get kuota terpakai
    // function get_kuota_terpakai($params) {
    //     $sql = "SELECT COUNT(*) AS kuota_terpakai
    //             FROM users_leave a
    //             INNER JOIN users_leave_date b ON a.leave_id = b.leave_id
    //             WHERE a.user_id = ? AND a.leave_type = ? AND a.leave_st <> 'rejected' AND YEAR(a.leave_date_start) = YEAR(NOW())";
    //     $query = $this->db->query($sql, $params);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['kuota_terpakai'];
    //     } else {
    //         return 0;
    //     }
    // }

}