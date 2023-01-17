<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class presensi extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/administrasi/m_presensi');
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
        $this->smarty->assign("template_content", "kepegawaian/administrasi/presensi/list.html");
        // default date
        $date_start = date("Y-01-01");
        $date_end = date("Y-12-31");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        $this->smarty->assign("search", $search);
        // search parameters                       
        if(isset($_SESSION['presensi_search'])){
            $bulan = ($search['bulan'] == '') ? "01" : $search['bulan'];
            $tahun = ($search['tahun'] == '') ? date("Y") : $search['tahun'];
            $search['date_start'] = $bulan==date("m") && $tahun == date("Y") ?
                date("$tahun-$bulan-01") : 
                (!empty($presensi_search['bulan']) ? date("$tahun-01-01") : date("$tahun-$bulan-01"));
            $search['date_end'] = $bulan==date("m") && $tahun == date("Y") ? 
                date("$tahun-$bulan-t",strtotime($search['date_start'])) : 
                (empty($search['bulan']) ? date("$tahun-12-31") : date("$tahun-$bulan-t",strtotime($search['date_start'])));
            $this->smarty->assign("search", $search);
            $date_start = $search['date_start'];
            $date_end = $search['date_end'];
        }
        $params = array($date_start, $date_end);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/administrasi/presensi/index/");
        //$params = array($user_alias);
        $config['total_rows'] = $this->m_presensi->get_total_presensi($params);
        $config['uri_segment'] = 5;
        $config['per_page'] = 25;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();
        // pagination attribute
        $start = $this->uri->segment(5, 0) + 1;
        $end = $this->uri->segment(5, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];
        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list                
        $params = array($date_start,$date_end,($start - 1), $config['per_page']);
        $this->smarty->assign("rs_presensi", $this->m_presensi->get_list_employee_attendance($params));
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
            $this->tsession->unset_userdata('presensi_search');
        } else {
            $params = array(
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun")
            );
            $this->tsession->set_userdata('presensi_search', $params);
        }
        // redirect
        redirect("kepegawaian/administrasi/presensi");
    }

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/presensi/add.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");  
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('presensi_nip', 'NIP', 'trim|required|maxlength[10]');        
        $this->tnotification->set_rules('presensi_tanggal', 'Tanggal', 'trim|required|maxlength[10]');    
        $this->tnotification->set_rules('presensi_waktu', 'Waktu', 'trim|required|maxlength[8]');       
        $this->tnotification->set_rules('presensi_status', 'Status', 'trim|required|maxlength[2]'); 
        // process
        if ($this->tnotification->run() !== FALSE) {               
            $presensi_id = $this->m_presensi->generate_presensi_id($this->input->post('presensi_tanggal'));
            $mesin_id = '01';
            $mesin_ip = '127.0.01';            
            $params = array(
                'presensi_id'       => $presensi_id,
                'mesin_id'          => $mesin_id,
                'mesin_ip'          => $mesin_ip,
                'presensi_nip'      => $this->input->post('presensi_nip',TRUE),
                'presensi_tanggal'  => $this->input->post('presensi_tanggal',TRUE),
                'presensi_waktu'    => $this->input->post('presensi_waktu',TRUE),                
                'presensi_status'   => $this->input->post('presensi_status',TRUE),
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_presensi->insert_data_presensi($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/administrasi/presensi/add");
    }

    // import data
    public function import() {
        set_time_limit(0);
        //default value date
        $current_first_date = date("Y-m") . "-01";
        $current_last_date = date("Y-m-t");
        $search = $this->tsession->userdata('presensi_search');
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
            $res_import = $this->m_presensi->get_import_data($url, $start_date, $end_date, $total_id);
            $i = 0;
            foreach ($res_import as $import) {
                if ($this->m_presensi->is_registered_id($import[1])) {
                    if ($import[4] == "IN") {
                        $rs_user = $this->m_presensi->get_id_finger_by_user_id($import[1]);
                        $user_id = $rs_user['user_id'];
                        if (!$this->m_presensi->is_exist_data(array(intval($user_id), $import[0], $import[3]))) {
                            $this->m_presensi->insert(array(intval($user_id), $import[0], $import[3], $import[4], 0));
                            $i++;
                        }
                    }
                    if ($import[4] == "OUT") {
                        $rs_user = $this->m_presensi->get_id_finger_by_user_id($import[1]);
                        $user_id = $rs_user['user_id'];
                        if (!$this->m_presensi->is_exist_data_out(array(intval($user_id), $import[0], $import[3]))) {
                            $this->m_presensi->insert(array(intval($user_id), $import[0], $import[3], $import[4], 0));
                            $i++;
                        }
                    }
                }
            }
            $this->tnotification->sent_notification("success", $i . " data berhasil didownload");
            // default redirect
            redirect("kepegawaian/administrasi/presensi/index");
        } else {
            //echo "Tanggal masih kosong!!!";
            $this->tnotification->sent_notification("error", "Data gagal didownload");
        }
    }

    // get total sunday
    function _total_sunday($start = '', $end = '') {
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
            if ($hari == "Sunday") {
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
        $this->smarty->assign("template_content", "kepegawaian/administrasi/presensi/detail.html");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-t");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        //
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        $this->smarty->assign("search", $search);
        // get detail employee by id
        $result = $this->m_pegawai->get_employee_detail_by_id($user_id);
        // images
        $filepath = 'resource/doc/images/users/' . $result['employee_img'];
        if (!is_file($filepath)) {
            $filepath = 'resource/doc/images/users/default.png';
        }
        $employee_img = base_url() . $filepath;
        $this->smarty->assign("employee_img", $employee_img);
        $this->smarty->assign("result", $result);
        $this->smarty->assign("no", 1);
        // rekap
        $params = array($search['date_start'], $search['date_end'], $user_id);
        $rs_id = $this->m_presensi->get_rekapitulasi_presensi_by_user_date($params);

        foreach ($rs_id as $key => $value) {
            $params = array($value['att_date'], $value['user_id']);
            $rs_id[$key]['out_time'] = $this->m_presensi->get_rekapitulasi_presensi_pulang_by_user_date($params);
        }
        $this->smarty->assign("rs_id", $rs_id);
        // output
        parent::display();
    }

}
