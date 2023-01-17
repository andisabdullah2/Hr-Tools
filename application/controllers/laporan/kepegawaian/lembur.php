<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class lembur extends ApplicationBase {

    // contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('laporan/kepegawaian/m_lembur');
        $this->load->model('m_account');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // index
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "laporan/kepegawaian/lembur/index.html");
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-d");
        // get search parameter
        $search = $this->tsession->userdata('report_lembur_search');
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        } else {
            $search['date_start'] = $date_start;
            $search['date_end'] = $date_end;
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $department = empty($search['department']) ? '%' : '%' . $search['department'] . '%';
        $date_start = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $date_end = empty($search['date_end']) ? date("Y-m-t") : $search['date_end'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("laporan/kepegawaian/lembur/index/");
        $config['total_rows'] = $this->m_lembur->get_total_overtime_personel_by_params(array($full_name, $department, $date_start, $date_end));
        $config['uri_segment'] = 5;
        $config['per_page'] = 20;
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
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $params = array($full_name, $department, $date_start, $date_end, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_lembur->get_all_overtime_personel_by_params($params));
        //get department
        $this->smarty->assign("rs_department", $this->m_lembur->get_all_unit_kerja());
        //output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('report_lembur_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "date_start" => $this->input->post("date_start"),
                "date_end" => $this->input->post("date_end"),
                "struktur_cd" => $this->input->post("struktur_cd")
            );
            $this->tsession->set_userdata('report_lembur_search', $params);
        }
        // redirect
        redirect("laporan/kepegawaian/lembur");
    }

    // checklist laporan
    public function checklist($user_id = "") {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "laporan/kepegawaian/lembur/checklist.html");
        // get user detail
        $result = $this->m_lembur->get_user_account_by_id($user_id);
        $this->smarty->assign("user_id", $user_id);
        $this->smarty->assign("result", $result);
        // default date
        $date_start = date("Y-m-01");
        $date_end = date("Y-m-d");
        // get search parameter
        $search = $this->tsession->userdata('report_lembur_search');
        if (empty($search)) {
            $search['date_start'] = $date_start;
            $search['date_end'] = $date_end;
        }
        // search parameters
        $rekap_st = empty($search['rekap_st']) ? '%' : '%' . $search['rekap_st'] . '%';
        $date_start = empty($search['date_start']) ? date("Y-m-01") : $search['date_start'];
        $date_end = empty($search['date_end']) ? date("Y-m-t") : $search['date_end'];
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $month = date("m", strtotime($date_end));
        $this->smarty->assign("month", $bulan[$month]);
        // tahun
        $year = date("Y", strtotime($date_end));
        $this->smarty->assign("year", $year);
        // date start
        $this->smarty->assign("date_start", $date_start);
        // date end
        $this->smarty->assign("date_end", $date_end);
        // get list data
        $this->smarty->assign("no", 1);
        $params = array($user_id, $date_start, $date_end);
        $rs_id = $this->m_lembur->get_all_overtime_personel_by_id($params);
        $this->smarty->assign("rs_id", $rs_id);
        // set foto
        $dir_path = './resource/doc/images/users/';
        // set default image
        if (file_exists($dir_path.$result['foto_name']) AND !empty($result['foto_name'])) {
                $this->smarty->assign('default_image','resource/doc/images/users/'.$result['foto_name']);
        }
        else {
                $this->smarty->assign('default_image','resource/doc/images/users/default.png');
        }                
        //output
        parent::display();
    }

    // download report
    public function download($user_id = '') {
        //jika user id kosong
        if (empty($user_id))
            redirect('laporan/kepegawaian/lembur');
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        // process
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_LEMBUR.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        //set default date
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-d');
        // -- get start date and end date from session
        $search = $this->tsession->userdata('report_lembur_search');
        if (!empty($search)) {
            $date_start = $search['date_start'];
            $date_end = $search['date_end'];
        }
        // parameter
        $month = date("m", strtotime($date_end));
        $year = date("Y", strtotime($date_end));
        // get list lembur
        $params = array($user_id, $date_start, $date_end);
        $rs_id = $this->m_lembur->get_all_overtime_personel_by_id($params);
        if (empty($rs_id)) {
            // default redirect
            redirect("laporan/kepegawaian/lembur/");
        }
        // get user detail
        $result = $this->m_lembur->get_user_account_by_id($user_id);
        // get dpt leader
        $department_leader = $this->m_lembur->get_unit_kerja_leader(array($result['user_id']));
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('E3', 'TAHUN : ' . $year);
        $objWorksheet->setCellValue('D6', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('B31', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('F31', $department_leader);
        $objWorksheet->setCellValue('D7', strtoupper($result['struktur_nama']));
        $objWorksheet->setCellValue('D8', strtoupper($result['jabatan_nama']));
        $objWorksheet->setCellValue('D9', strtoupper($bulan[$month]));
        // list lembur
        $no = 1;
        $i = 12;
        foreach ($rs_id as $data) {
            $objWorksheet->setCellValue('A' . $i, $no++);
            $objWorksheet->setCellValue('B' . $i, $this->datetimemanipulation->get_full_date($data['overtime_date']));
            $objWorksheet->setCellValue('E' . $i, $data['hari']);
            $objWorksheet->setCellValue('F' . $i, substr($data['overtime_start'], 0, 5) . ' - ' . substr($data['overtime_end'], 0, 5));
            $objWorksheet->setCellValue('G' . $i, $data['total_hours']);
            $objWorksheet->setCellValue('I' . $i, $data['project_name'] . ' - ' . $data['overtime_reason']);
            $i++;
        }
        // file_name
        $file_name = "REKAP_LEMBUR_" . str_replace(' ', '_', strtoupper($result['nama_lengkap']) . '_' . $year . '_' . $month);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
        exit();
    }

    // cetak detail
    public function cetak_detail($user_id = '', $overtime_id = "") {
        if (empty($user_id))
            redirect('laporan/kepegawaian/lembur');
        if (empty($overtime_id))
            redirect('laporan/kepegawaian/lembur');
        // load excel
        $this->load->library('phpexcel');
        //load model
        $this->load->model('m_lembur_rizki');
        // create excell
        $filepath = "resource/doc/template/LEMBUR.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // overtime
        $result = $this->m_lembur_rizki->get_detail_overtime_cetak(array($overtime_id));
        if (empty($result)) {
            // default redirect
            redirect("task/overtime/");
        }
        // personel
        $personel = $this->m_lembur_rizki->get_all_users_by_id($overtime_id);
        //PIC
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('B23', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d', strtotime($result['mdd']))));
        $objWorksheet->setCellValue('B28', strtoupper($this->com_user['full_name']));
        $objWorksheet->setCellValue('F5', $this->datetimemanipulation->get_full_date(date('Y-m-d h:i:s', strtotime($result['mdd']))));
        $objWorksheet->setCellValue('D11', strtoupper($result['project_name']));
        $objWorksheet->setCellValue('D12', strtoupper($result['overtime_reason']));
        $objWorksheet->setCellValue('D13', $this->datetimemanipulation->get_full_date($result['overtime_date']));
        $objWorksheet->setCellValue('D14', substr($result['overtime_start'], 0, 5));
        $objWorksheet->setCellValue('D15', substr($result['overtime_end'], 0, 5));
        $objWorksheet->setCellValue('D28', strtoupper($result['department_lead']));
        // personel
        $i = 16;
        foreach ($personel as $person) {
            $objWorksheet->setCellValue('D' . $i, strtoupper($person['full_name']));
            $i++;
        }
        // file_name
        $file_name = "SL_" . str_replace(' ', '_', strtoupper($result['project_name'])) . "_" . str_replace('-', '_', $result['overtime_date']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}
