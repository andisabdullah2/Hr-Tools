<?php

class m_presensi extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        //load global
        $this->load->library("phpdom");
        //--
        //get dom object
        $this->html = new simple_html_dom();
    }

    // get total presensi
    function get_total_presensi($params) {        
        $sql = "SELECT COUNT(*) 'total' FROM data_presensi a
                WHERE a.presensi_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        }
        return array();
    }

    // get list employee
    function get_list_employee_attendance($params) {
        $sql = "SELECT b.* FROM data_presensi b
                WHERE presensi_tanggal BETWEEN ? AND ?        
                ORDER BY b.presensi_tanggal ASC
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

    // insert data presensi
    function insert_data_presensi($params) {
        return $this->db->insert('data_presensi', $params);
    }    

     // generate id presensi
     public function generate_presensi_id($tanggal) {
        $tanggal = str_replace('-','.',$tanggal);
        // cari id terakhir
        $sql = "SELECT RIGHT(presensi_id, 2) AS last_number FROM `data_presensi` WHERE presensi_id LIKE '%$tanggal%' ORDER BY presensi_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();            
            $result['last_number'] = str_replace('.', '', $result['last_number']);
            $query->free_result();
            $nomor = intval($result['last_number']) + 1;
            if ($nomor > 99999) {
                return false;
            }
        } else {
            $nomor = 1;
        }
        return $tanggal.".".$nomor;
    }        
    

    // get total days
    function get_total_days($params) {
        $sql = "SELECT ((ABS(DATEDIFF(?, ?)) + 1) - COUNT(*))'total'
                FROM data_hari_libur a
                WHERE libur_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        }
        return 0;
    }

    // get rekap presensi by user
    function get_rekapitulasi_presensi_by_user_date($params) {
        $sql = "SELECT * FROM
                (
                        SELECT *, 
                        IF((att_time > '08:15:00'), 0, 1)'otp', TIMEDIFF('08:15:00', att_time)'keterlambatan'
                        FROM users_attendance
                        WHERE att_date BETWEEN ? AND ? AND att_status = 'IN'
                        GROUP BY user_id, att_date
                        ORDER BY att_time ASC
                ) a
                WHERE a.user_id = ?
                ORDER BY att_date ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

    // get rekap presensi by user
    function get_rekapitulasi_presensi_pulang_by_user_date($params) {
        $sql = "SELECT * FROM
                (
                        SELECT *
                        FROM users_attendance
                        WHERE att_date = ? AND att_status = 'OUT'
                        ORDER BY att_time ASC
                ) a
                WHERE a.user_id = ?
                ORDER BY att_date ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['att_time'];
        }
    }

    // import data absensi
    public function get_import_data($url, $start_date, $end_date, $total_id) {
        //assign new variable
        $content = array();
        //get url link
        $url = $this->__get_url($url, $start_date, $end_date, $total_id);
        //assign html
        if ($url !== 'url not valid') {
            //get html
            $this->html = file_get_html($url);
            //find content
            $content = $this->__find_contains($this->html, "td");
        }
        //return
        return $content;
    }

    // search elements that contains an specific text
    private function __find_contains($html, $selector) {
        $ret = array();
        $key_out = 0;
        foreach ($html->find($selector) as $key => $e) {
            if ($key >= 6) {
                if ($key % 6 == 5) {
                    $ret[$key_out][] = $e->innertext;
                    $key_out++;
                } else {
                    $ret[$key_out][] = $e->innertext;
                }
            }
        }
        return $ret;
    }

    // get url for read data
    private function __get_url($url = "", $start_date = "", $end_date = "", $total_id = "") {
        if ($this->__is_url_exists($url)) {
            $str_query = "/csl/query?action=run";
            $str_id = '';
            $str_date = "&sdate=" . (!empty($start_date) ? $start_date : date("Y-m-d")) . "&edate=" . (!empty($end_date) ? $end_date : date("Y-m-d") );

            for ($index = 1; $index <= $total_id; $index++) {
                $str_id .= '&uid=' . $index;
            }
            $str_url = $url . $str_query . $str_id . $str_date;
            return $str_url;
        } else {
            return "url not valid";
        }
    }

    // check if url is valid
    private function __is_url_exists($url = NULL) {
        if ($url == NULL) {
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }

    // jika data ditemukan
    function is_exist_data($params) {
        $sql = "SELECT COUNT(*)'total' FROM users_attendance WHERE att_status = 'IN' 
                 AND user_id = ? AND att_date = ?  AND att_time = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] > 0) {
                return true;
            }
        }
        return false;
    }

    // jika data ditemukan
    function is_exist_data_out($params) {
        $sql = "SELECT COUNT(*)'total' FROM users_attendance WHERE att_status = 'OUT' 
                 AND user_id = ? AND att_date = ?  AND att_time = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] > 0) {
                return true;
            }
        }
        return false;
    }

    // jika id di registrasikan    
    function is_registered_id($params) {
        $sql = "SELECT COUNT(*)'total' FROM users WHERE emp_cd = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] > 0) {
                return true;
            }
        }
        return false;
    }

    // ambil id attendance
    function get_id_finger_by_user_id($params) {
        $sql = "SELECT * FROM users WHERE emp_cd = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert
    function insert($params) {
        $sql = "INSERT INTO users_attendance (user_id,att_date, att_time, att_status,mdd,mdb)
                VALUES (?,?, ?, ?, ?, NOW())";
        return $this->db->query($sql, $params);
    }

    /*
     * REPORT
     */

    // get total employee report
    function get_total_employee_attendance_by_params($params) {
        $sql = "SELECT COUNT(*)'total' FROM users a
                LEFT JOIN department b ON a.department_id = b.department_id
                WHERE b.department_name LIKE ? AND a.full_name LIKE ? AND employee_st = 'working'
                ORDER BY a.employee_st DESC, a.full_name ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        }
        return array();
    }

    // get list employee report
    function get_list_employee_attendance_by_params($params) {
        $sql = "SELECT emp.user_id, emp.full_name, emp.emp_cd, 
                COUNT(att.user_id)'total_presensi', SUM(otp)'otp', total_jaldin, total_ijin
                FROM
                (
                        SELECT a.*, department_name 
                        FROM users a
                        LEFT JOIN department b ON a.department_id = b.department_id
                        WHERE b.department_name LIKE ? AND a.full_name LIKE ? AND employee_st = 'working'
                ) emp
                LEFT JOIN
                (
                        SELECT * FROM
                        (
                                SELECT *, 
                                IF((att_time > '08:15:00'), 0, 1)'otp'
                                FROM users_attendance
                                WHERE att_date BETWEEN ? AND ? AND att_status = 'IN'
                                ORDER BY att_time ASC
                        ) a
                        GROUP BY user_id, att_date
                ) att ON emp.user_id = att.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(duty_date)'total_jaldin' FROM
                        (
                                SELECT a.user_id, b.*
                                FROM users_duty a
                                INNER JOIN users_duty_date b ON a.duty_id = b.duty_id
                                WHERE duty_date BETWEEN ? AND ?
                                ORDER BY a.user_id ASC, duty_date ASC
                        ) a
                        GROUP BY user_id 
                ) jaldin ON emp.user_id = jaldin.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(permit_date)'total_ijin' FROM
                        (
                                SELECT a.user_id, permit_date
                                FROM users_permit a
                                WHERE permit_date BETWEEN ? AND ? AND permit_type IN (1, 2, 6)
                                ORDER BY a.user_id ASC, permit_date ASC
                        ) a
                        GROUP BY user_id 
                ) ijin ON emp.user_id = ijin.user_id
                GROUP BY emp.user_id
                ORDER BY emp.full_name ASC
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

    // get list employee report print
    function get_list_employee_attendance_all($params) {
        $sql = "SELECT emp.user_id, emp.full_name, emp.emp_cd, department_name,
                COUNT(att.user_id)'total_presensi', SUM(otp)'otp', total_jaldin, total_ijin
                FROM
                (
                        SELECT a.*, department_name 
                        FROM users a
                        LEFT JOIN department b ON a.department_id = b.department_id
                        WHERE b.department_name LIKE ? AND a.full_name LIKE ? AND employee_st = 'working'
                ) emp
                LEFT JOIN
                (
                        SELECT * FROM
                        (
                                SELECT *, 
                                IF((att_time > '08:15:00'), 0, 1)'otp'
                                FROM users_attendance
                                WHERE att_date BETWEEN ? AND ? AND att_status = 'IN'
                                ORDER BY att_time ASC
                        ) a
                        GROUP BY user_id, att_date
                ) att ON emp.user_id = att.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(duty_date)'total_jaldin' FROM
                        (
                                SELECT a.user_id, b.*
                                FROM users_duty a
                                INNER JOIN users_duty_date b ON a.duty_id = b.duty_id
                                WHERE duty_date BETWEEN ? AND ?
                                ORDER BY a.user_id ASC, duty_date ASC
                        ) a
                        GROUP BY user_id 
                ) jaldin ON emp.user_id = jaldin.user_id
                LEFT JOIN
                (
                        SELECT user_id, COUNT(permit_date)'total_ijin' FROM
                        (
                                SELECT a.user_id, permit_date
                                FROM users_permit a
                                WHERE permit_date BETWEEN ? AND ? AND permit_type IN (1, 2, 6)
                                ORDER BY a.user_id ASC, permit_date ASC
                        ) a
                        GROUP BY user_id 
                ) ijin ON emp.user_id = ijin.user_id
                GROUP BY emp.user_id
                ORDER BY emp.full_name ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        return array();
    }

}
