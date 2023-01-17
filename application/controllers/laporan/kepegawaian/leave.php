<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class leave extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('laporan/kepegawaian/m_cuti');
        $this->load->model('m_account');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // view
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template
        $this->smarty->assign("template_content", "laporan/kepegawaian/leave/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_cuti->get_list_tahun());
        // get search parameter
        $search = $this->tsession->userdata('leave_search');
        // search parameter
        $nama_lengkap = empty($search['nama_lengkap']) ? '%' : '%' . $search['nama_lengkap'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $struktur_cd = empty($search['struktur_cd']) ? '%' : '%' . $search['struktur_cd'] . '%';
        $search['tahun'] = $tahun;
        $this->smarty->assign("search", $search);
        // pagination
        /* start of pagination --------------------- */
        $config['base_url'] = site_url("laporan/kepegawaian/leave/index/");
        $config['total_rows'] = $this->m_cuti->get_total_leave(array($nama_lengkap, $tahun, $struktur_cd));
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
        //parameter
        $params = array($nama_lengkap, $tahun, $struktur_cd, ($start - 1), $config['per_page']);
        //get list
        $rs_user = $this->m_cuti->get_all_leave_by_user_id($params);
        foreach ($rs_user as $user) {
            $data = array();
            $data['user_id'] = $user['user_id'];
            $data['nama_lengkap'] = $user['nama_lengkap'];
            $data['struktur_nama'] = $user['struktur_nama'];
            // $data['total_kuota'] = $user['total_kuota'];
	    $params = array($data['user_id']);
            $data['total_kuota'] = $this->m_cuti->get_total_kuota_by_user_id($params);
            $data['total_cuti'] = $user['total_cuti'];
            // get jumlah cuti dalam bulan
            for ($a = 1; $a <= 12; $a++) {
                $data['bulan' . $a] = $this->m_cuti->get_kuota_terpakai_perbulan(array($user['user_id'], $a, $tahun));
            }
            // parse again
            $rs_user_data[] = $data;
        }
	if(empty($rs_user_data)) $rs_user_data = array();
	// print_r($rs_user_data[0]);
        $this->smarty->assign("rs_id", $rs_user_data);
        // get department
        $this->smarty->assign("rs_department", $this->m_cuti->get_all_unit_kerja());
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // proses pencarian
    public function proses_search() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('leave_search');
        } else {
            $params = array(
                "nama_lengkap" => $this->input->post("nama_lengkap"),
                "tahun" => $this->input->post("tahun"),
                "struktur_cd" => $this->input->post("struktur_cd"),
            );
            $this->tsession->set_userdata('leave_search', $params);
        }
        // redirect
        redirect("laporan/kepegawaian/leave");
    }

    // download report tahunan
    public function download_tahunan() {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_CUTI_TAHUNAN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get search parameter
        $search = $this->tsession->userdata('leave_search');
        // search parameter
        $nama_lengkap = empty($search['nama_lengkap']) ? '%' : '%' . $search['nama_lengkap'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $department = empty($search['department']) ? '%' : '%' . $search['department'] . '%';
        $search['tahun'] = $tahun;
        // parameter
        $params = array($nama_lengkap, $tahun, $department);
        $rs_user = $this->m_cuti->get_all_leave_by_user_id_no_limit($params);
        // get list
        foreach ($rs_user as $user) {
            $data = array();
            $data['user_id'] = $user['user_id'];
            $data['nama_lengkap'] = $user['nama_lengkap'];
            $data['struktur_nama'] = $user['struktur_nama'];
	    $params = array($data['user_id']);
            $data['total_kuota'] = $this->m_cuti->get_total_kuota_by_user_id($params);
            $data['total_cuti'] = $user['total_cuti'];
            // get jumlah cuti dalam bulan
            for ($a = 1; $a <= 12; $a++) {
                $data['bulan' . $a] = $this->m_cuti->get_kuota_terpakai_perbulan(array($user['user_id'], $a, $tahun));
            }
            // parse again
            $rs_user_data[] = $data;
        }
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('C3', 'TAHUN : ' . $tahun);
        // list cuti
        $no = 1;
        $i = 9;
        foreach ($rs_user_data as $data) {
            $objWorksheet->setCellValue('A' . $i, $no++);
            $objWorksheet->setCellValue('B' . $i, strtoupper($data['nama_lengkap']));
            $objWorksheet->setCellValue('C' . $i, strtoupper($data['struktur_nama']));
            $objWorksheet->setCellValue('D' . $i, $data['bulan1']);
            $objWorksheet->setCellValue('E' . $i, $data['bulan2']);
            $objWorksheet->setCellValue('F' . $i, $data['bulan3']);
            $objWorksheet->setCellValue('G' . $i, $data['bulan4']);
            $objWorksheet->setCellValue('H' . $i, $data['bulan5']);
            $objWorksheet->setCellValue('I' . $i, $data['bulan6']);
            $objWorksheet->setCellValue('J' . $i, $data['bulan7']);
            $objWorksheet->setCellValue('K' . $i, $data['bulan8']);
            $objWorksheet->setCellValue('L' . $i, $data['bulan9']);
            $objWorksheet->setCellValue('M' . $i, $data['bulan10']);
            $objWorksheet->setCellValue('N' . $i, $data['bulan11']);
            $objWorksheet->setCellValue('O' . $i, $data['bulan12']);
            $objWorksheet->setCellValue('P' . $i, $data['total_cuti']);
            $objWorksheet->setCellValue('Q' . $i, (empty($data['total_kuota']) ? 12 : $data['total_kuota']) - $data['total_cuti']);
            // insert
            if (($i - 8) != count($rs_user_data)) {
                $objWorksheet->insertNewRowBefore(($i + 1), 1);
            }
            // --
            $i++;
        }
        // file_name
        $file_name = "REKAP_CUTI_TAHUNAN_" . $tahun;
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    //--ceklist
    public function checklist($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template
        $this->smarty->assign("template_content", "laporan/kepegawaian/leave/checklist.html");
        // --
        $search = $this->tsession->userdata('leave_search');
        $this->smarty->assign("search", $search);
        // get user detail
        $result = $this->m_cuti->get_user_profile_by_id($user_id);
        $this->smarty->assign("result", $result);
        // tahun
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $search['tahun'] = $tahun;
        $year = date("Y", strtotime($search['tahun']));
        $this->smarty->assign("year", $year);
        $this->smarty->assign("no", 1);
        // get list
        $rs_tahun = $this->m_cuti->get_leave_year_by_user_id($user_id);
        foreach ($rs_tahun as $tahun) {
            $data = array();
            $data['tahun'] = $tahun['tahun'];
	    $params = array($user_id);
            $data['total_kuota'] = $this->m_cuti->get_total_kuota_by_user_id($params);
            $data['total_cuti'] = $tahun['total_cuti'];
            // get jumlah cuti dalam bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $data['bulan' . $bulan] = $this->m_cuti->get_kuota_terpakai_perbulan(array($user_id, $bulan, $tahun['tahun']));
            }
            // parse again
            $rs_user_data[] = $data;
        }
        $this->smarty->assign("rs_id", $rs_user_data);
        $this->smarty->assign("user_id", $user_id);
        // set foto
        $dir_path = './resource/doc/images/users/';
        // set default image
        if (file_exists($dir_path.$result['foto_name']) AND !empty($result['foto_name'])) {
                $this->smarty->assign('default_image','resource/doc/images/users/'.$result['foto_name']);
        }
        else {
                $this->smarty->assign('default_image','resource/doc/images/users/default.png');
        }        
        // output
        parent::display();
    }

    // download report
    public function download($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_CUTI_PERTAHUN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // --
        $search = $this->tsession->userdata('leave_search');
        // get user detail
        $result = $this->m_cuti->get_user_profile_by_id($user_id);
        // tahun
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $search['tahun'] = $tahun;
        // get list
        $rs_tahun = $this->m_cuti->get_leave_year_by_user_id($user_id);
        foreach ($rs_tahun as $tahun) {
            $data = array();
            $data['tahun'] = $tahun['tahun'];
	    $params = array($user_id);
            $data['total_kuota'] = $this->m_cuti->get_total_kuota_by_user_id($params);	    
            $data['total_cuti'] = $tahun['total_cuti'];
            // get jumlah cuti dalam bulan
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $data['bulan' . $bulan] = $this->m_cuti->get_kuota_terpakai_perbulan(array($user_id, $bulan, $tahun['tahun']));
            }
            // parse again
            $rs_user_data[] = $data;
        }
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('C8', ': ' . strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('C9', ': ' . strtoupper($result['jabatan_nama']));
        $objWorksheet->setCellValue('C10', ': ' . strtoupper($result['struktur_nama']));
        // list cuti
        $i = 14;
        foreach ($rs_user_data as $data) {
            $objWorksheet->setCellValue('A' . $i, strtoupper($data['tahun']));
            $objWorksheet->setCellValue('B' . $i, $data['bulan1']);
            $objWorksheet->setCellValue('C' . $i, $data['bulan2']);
            $objWorksheet->setCellValue('D' . $i, $data['bulan3']);
            $objWorksheet->setCellValue('E' . $i, $data['bulan4']);
            $objWorksheet->setCellValue('F' . $i, $data['bulan5']);
            $objWorksheet->setCellValue('G' . $i, $data['bulan6']);
            $objWorksheet->setCellValue('H' . $i, $data['bulan7']);
            $objWorksheet->setCellValue('I' . $i, $data['bulan8']);
            $objWorksheet->setCellValue('J' . $i, $data['bulan9']);
            $objWorksheet->setCellValue('K' . $i, $data['bulan10']);
            $objWorksheet->setCellValue('L' . $i, $data['bulan11']);
            $objWorksheet->setCellValue('M' . $i, $data['bulan12']);
            $objWorksheet->setCellValue('N' . $i, $data['total_cuti']);
            // insert
            if (($i - 13) != count($rs_user_data)) {
                $objWorksheet->insertNewRowBefore(($i + 1), 1);
            }
            // --
            $i++;
        }
        // file_name
        $file_name = "REKAP_CUTI_PERTAHUN_" . str_replace(' ', '_', strtoupper($result['nama_lengkap']));
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}