<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class kehadiran extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_kehadiran');
        $this->load->model('kepegawaian/master/m_pegawai');
        $this->load->model('m_preferences');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // view
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template
        $this->smarty->assign("template_content", "kepegawaian/master/kehadiran/list.html");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-d");
        // get search parameter
        $search = $this->tsession->userdata('kehadiran_search');
        $bulan = !empty($search['bulan']) ? $search['bulan'] : date("m");
        $tahun = !empty($search['tahun']) ? $search['tahun'] : date("Y");
        $search['date_start'] = $bulan==date("m") && $tahun == date("Y") ? date("Y-m-01") : date("$tahun-$bulan-01");
        $search['date_end'] = $bulan==date("m") && $tahun == date("Y") ? date("Y-m-t",strtotime($search['date_start'])) : date("$tahun-$bulan-t",strtotime($search['date_start']));
        $this->smarty->assign("search", $search);
        // working days
        $working_days = $this->m_kehadiran->get_total_days(array($search['date_start'], $search['date_end'], $search['date_start'], $search['date_end']));
        $sundays = $this->_total_weekend_day($search['date_start'], $search['date_end']);
        $this->smarty->assign("working_days", $working_days - $sundays);
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $date_start = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $date_end = empty($search['date_end']) ? date("Y-m-t") : $search['date_end'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/kehadiran/index/");
        $params = array($full_name);
        $config['total_rows'] = $this->m_kehadiran->get_total_employee($params);
        $config['uri_segment'] = 4;
        $config['per_page'] = 50;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();
        // pagination attribute
        $start = $this->uri->segment(4, 0) + 1;
        $end = $this->uri->segment(4, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];
        // pagination assign value
        $this->smarty->assign("total_data", $config['total_rows']);
        $this->smarty->assign("total_data_presented", 0);        
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $params = array($full_name, $date_start, $date_end, $date_start, $date_end, $date_start, $date_end, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_kehadiran", $this->m_kehadiran->get_list_employee_attendance($params));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('kehadiran_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun")
            );
            $this->tsession->set_userdata('kehadiran_search', $params);
        }
        // redirect
        redirect("kepegawaian/master/kehadiran");
    }

    // import data
    public function import() {
        set_time_limit(0);
        //default value date
        $current_first_date = date("Y-m") . "-01";
        $current_last_date = date("Y-m-t");
        $search = $this->tsession->userdata('kehadiran_search');
        if (empty($search)) {
            $search['date_start'] = $current_first_date;
            $search['date_end'] = $current_last_date;
        }
        $start_date = empty($search['date_start']) ? $current_first_date : $search['date_start'];
        $end_date = empty($search['date_end']) ? $current_last_date : $search['date_end'];
        $rs_ip_fingerprint = $this->m_preferences->get_preferences_by_group_and_name(array("connection", "ip_fingerprint"));
        $ip_fingerprint = $rs_ip_fingerprint['pref_value'];
        // post data
        $url = "http://" . $ip_fingerprint;
        $total_id = 200;
        if (!empty($start_date) && !empty($end_date)) {
            //get attendance from finger print
            $res_import = $this->m_kehadiran->get_import_data($url, $start_date, $end_date, $total_id);
            $i = 0;
            foreach ($res_import as $import) {
                if ($this->m_kehadiran->is_registered_id($import[1])) {
                    if ($import[4] == "IN") {
                        $rs_user = $this->m_kehadiran->get_id_finger_by_user_id($import[1]);
                        $user_id = $rs_user['user_id'];
                        if (!$this->m_kehadiran->is_exist_data(array(intval($user_id), $import[0], $import[3]))) {
                            $this->m_kehadiran->insert(array(intval($user_id), $import[0], $import[3], $import[4], 0));
                            $i++;
                        }
                    }
                    if ($import[4] == "OUT") {
                        $rs_user = $this->m_kehadiran->get_id_finger_by_user_id($import[1]);
                        $user_id = $rs_user['user_id'];
                        if (!$this->m_kehadiran->is_exist_data_out(array(intval($user_id), $import[0], $import[3]))) {
                            $this->m_kehadiran->insert(array(intval($user_id), $import[0], $import[3], $import[4], 0));
                            $i++;
                        }
                    }
                }
            }
            $this->tnotification->sent_notification("success", $i . " data berhasil didownload");
            // default redirect
            redirect("kepegawaian/master/kehadiran/index");
        } else {
            //echo "Tanggal masih kosong!!!";
            $this->tnotification->sent_notification("error", "Data gagal didownload");
        }
    }

    // get total sunday
    function _total_weekend_day($start = '', $end = '') {
        $first = $start;
        $last = $end;
        $step = '+1 day';
        $format = 'Y-m-d';
        $tgl_aktif = strtotime($first);
        $tgl_terakhir = strtotime($last);
        $holiday = 0;
        while ($tgl_aktif <= $tgl_terakhir) {
            $tgl = date($format, $tgl_aktif);
            $hari = date("l", strtotime($tgl));
            if ($hari == "Sunday" || $hari == "Saturday") {
                $holiday++;
            }
            //add day after
            $tgl_aktif = strtotime($step, $tgl_aktif);
        }
        // return the number of working days
        return $holiday;
    }

    // detail
    public function detail($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template
        $this->smarty->assign("template_content", "kepegawaian/master/kehadiran/detail.html");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-t");
        // get search parameter
        $search = $this->tsession->userdata('kehadiran_search');
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        $this->smarty->assign("search", $search);
        // get detail employee by id
        $result = $this->m_kehadiran->get_employee_detail_by_id($user_id);
        // images
        $filepath = 'resource/doc/images/users/' . $result['foto_name'];
        if (!is_file($filepath)) {
            $filepath = 'resource/doc/images/users/default.png';
        }
        $employee_img = base_url() . $filepath;
        $this->smarty->assign("employee_img", $employee_img);
        $this->smarty->assign("result", $result);
        $this->smarty->assign("no", 1);
        // rekap
        $params = array($search['date_start'], $search['date_end'], $user_id);
        $rs_id = $this->m_kehadiran->get_rekapitulasi_presensi_by_user_date($params);

        foreach ($rs_id as $key => $value) {
            $params = array($value['kehadiran_tanggal'], $value['user_id']);
            $rs_id[$key]['kehadiran_jam_pulang'] = $this->m_kehadiran->get_rekapitulasi_presensi_pulang_by_user_date($params);
        }
        $this->smarty->assign("rs_id", $rs_id);
        // output
        parent::display();
    }

}

