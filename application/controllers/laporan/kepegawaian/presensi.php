<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class presensi extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('laporan/kepegawaian/m_pegawai');
        $this->load->model('laporan/kepegawaian/m_presensi');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // view
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "laporan/kepegawaian/presensi/list.html");
        $this->smarty->assign("rs_department", $this->m_pegawai->get_unit_kerja());
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-d");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        $this->smarty->assign("search", $search);
        // working days
        $working_days = $this->m_presensi->get_total_days(array($search['date_start'], $search['date_end'], $search['date_start'], $search['date_end']));
        $sundays = $this->_total_sunday($search['date_start'], $search['date_end']);
        $this->smarty->assign("working_days", $working_days - $sundays);
        // search parameters
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'] . '%';
        $nama_lengkap = empty($search['nama_lengkap']) ? '%' : '%' . $search['nama_lengkap'] . '%';
        $date_start = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $date_end = empty($search['date_end']) ? date("Y-m-t") : $search['date_end'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("laporan/kepegawaian/presensi/index/");
        $params = array($struktur_cd, $nama_lengkap);
        $config['total_rows'] = $this->m_presensi->get_total_employee_attendance_by_params($params);
        $config['uri_segment'] = 5;
        $config['per_page'] = 15;
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
        $params = array($struktur_cd, $nama_lengkap, $date_start, $date_end, $date_start, $date_end, $date_start, $date_end, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_presensi->get_list_employee_attendance_by_params($params));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // detail
    public function detail($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template
        $this->smarty->assign("template_content", "laporan/kepegawaian/presensi/detail.html");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-t");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        $this->smarty->assign("search", $search);
        // get detail employee by id
        $params = array($user_id);
        $result = $this->m_pegawai->get_employee_detail_by_id($params);
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
        $rs_id = $this->m_presensi->get_rekapitulasi_presensi_by_user_date($params);
        foreach ($rs_id as $key => $value) {
            $params = array($value['presensi_tanggal'], $value['presensi_nip']);
            $rs_id[$key]['out_time'] = $this->m_presensi->get_rekapitulasi_presensi_pulang_by_user_date($params);
        }
        $this->smarty->assign("rs_id", $rs_id);
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
                "struktur_cd" => $this->input->post("struktur_cd"),
                "nama_lengkap" => $this->input->post("nama_lengkap"),
                "date_start" => $this->input->post("date_start"),
                "date_end" => $this->input->post("date_end")
            );
            $this->tsession->set_userdata('presensi_search', $params);
        }
        // redirect
        redirect("laporan/kepegawaian/presensi");
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

    // rekapitulasi presensi
    public function download_all() {
        //set page rule
        $this->_set_page_rule("R");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-d");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        // working days
        $working_days = $this->m_presensi->get_total_days(array($search['date_start'], $search['date_end'], $search['date_start'], $search['date_end']));
        $sundays = $this->_total_sunday($search['date_start'], $search['date_end']);
        $working_days = $working_days - $sundays;
        // search parameters
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'] . '%';
        $nama_lengkap = empty($search['nama_lengkap']) ? '%' : '%' . $search['nama_lengkap'] . '%';
        $date_start = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $date_end = empty($search['date_end']) ? date("Y-m-t") : $search['date_end'];
        // get list
        $params = array($struktur_cd, $nama_lengkap, $date_start, $date_end, $date_start, $date_end, $date_start, $date_end);
        $rs_id = $this->m_presensi->get_list_employee_attendance_all($params);
        if (empty($rs_id)) {
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect('laporan/kepegawaian/presensi');
        }        
        // excel download
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_PRESENSI.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('C5', ': ' . strtoupper($this->datetimemanipulation->get_full_date($date_start)) . ' s/d ' . strtoupper($this->datetimemanipulation->get_full_date($date_end)));
        $struktur_cd = ($struktur_cd == '%') ? '-- ALL DEPARTMENT --' : strtoupper($struktur_cd);
        $objWorksheet->setCellValue('C6', ': ' . str_replace('%', '', $struktur_cd));
        // list project
        $no = 1;
        $i = 9;
        foreach ($rs_id as $data) {
            $objWorksheet->setCellValue('A' . $i, $no++);
            $objWorksheet->setCellValue('B' . $i, $data['pegawai_nip']);
            $objWorksheet->setCellValue('C' . $i, $data['nama_lengkap']);
            $objWorksheet->setCellValue('D' . $i, $data['struktur_nama']);
            $objWorksheet->setCellValue('E' . $i, $working_days);
            $objWorksheet->setCellValue('F' . $i, $data['total_ijin']);
            $objWorksheet->setCellValue('G' . $i, $data['total_jaldin']);
            $objWorksheet->setCellValue('H' . $i, $data['total_presensi']);
            $objWorksheet->setCellValue('I' . $i, $data['otp']);
            // ap
            if ($data['total_presensi'] != 0) {
                $ap = $data['total_presensi'] / ($working_days - $data['total_ijin'] - $data['total_jaldin']);
                $objWorksheet->setCellValue('J' . $i, $ap);
            }
            // otp
            if ($data['total_presensi'] != 0) {
                $otp = $data['otp'] / $data['total_presensi'];
                $objWorksheet->setCellValue('K' . $i, $otp);
            }
            // insert
            if (($i - 8) != count($rs_id)) {
                $objWorksheet->insertNewRowBefore(($i + 1), 1);
            }
            // --
            $i++;
        }
        // file_name
        $file_name = "REKAP_PRESENSI";
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
        exit();
    }

    // rekapitulasi presensi
    public function download_detail($user_id = "") {
        //set page rule
        $this->_set_page_rule("R");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-t");
        // get search parameter
        $search = $this->tsession->userdata('presensi_search');
        $search['date_start'] = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $search['date_end'] = empty($search['date_end']) ? date("Y-m-d") : $search['date_end'];
        // get detail employee by id
        $result = $this->m_pegawai->get_employee_detail_by_id($user_id);
        if (empty($result)) {
            $this->tnotification->sent_notification("error", "Data pegawai tidak ada");
            redirect('laporan/kepegawaian/presensi');
        }
        // rekap
        $params = array($search['date_start'], $search['date_end'], $user_id);
        $rs_id = $this->m_presensi->get_rekapitulasi_presensi_by_user_date($params);
        if (empty($rs_id)) {
            $this->tnotification->sent_notification("error", "Data rekap presensi tidak ada");            
            redirect('laporan/kepegawaian/presensi');
        }        
        foreach ($rs_id as $key => $value) {
            $params = array($value['presensi_tanggal'], $value['presensi_nip']);
            $rs_id[$key]['out_time'] = $this->m_presensi->get_rekapitulasi_presensi_pulang_by_user_date($params);
        }
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_PRESENSI_DETAIL.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('C5', ': ' . $result['struktur_cd']);
        $objWorksheet->setCellValue('C6', ': ' . $result['nama_lengkap']);
        $objWorksheet->setCellValue('C7', ': ' . $result['nomor_telepon']);
        $objWorksheet->setCellValue('C8', ': ' . $result['user_mail']);
        // list project
        $no = 1;
        $i = 11;
        foreach ($rs_id as $data) {
            $objWorksheet->setCellValue('A' . $i, $no++);
            $objWorksheet->setCellValue('B' . $i, $this->datetimemanipulation->get_full_date($data['presensi_tanggal']));
            $objWorksheet->setCellValue('C' . $i, $data['presensi_waktu']);
            $objWorksheet->setCellValue('D' . $i, (($data['otp'] == '1') ? '-' : $data['keterlambatan']));
            $objWorksheet->setCellValue('E' . $i, $data['out_time']);
            // insert
            if (($i - 10) != count($rs_id)) {
                $objWorksheet->insertNewRowBefore(($i + 1), 1);
            }
            // --
            $i++;
        }
        // file_name
        $file_name = "REKAP_PRESENSI_" . strtoupper(str_replace(' ', '_', $result['nama_lengkap']));
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
        exit();
    }

}
