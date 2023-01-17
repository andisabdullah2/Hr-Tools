<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class monitoring extends ApplicationBase {


    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/cuti/m_monitoring");
        $this->load->model("kepegawaian/cuti/m_pengajuan");        
        $this->load->model('kepegawaian/master/m_pegawai_jabatan');
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/monitoring/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_monitoring->get_list_tahun());
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('monitoring_search');
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : '%-'.$search['bulan'].'-%';        
        $department = empty($this->com_user['struktur_cd']) ? '%' : $this->com_user['struktur_cd'];
        $search['full_name'] = empty($search['full_name']) ? '' : '' . $search['full_name'] . '';
        $search['tahun'] = $tahun;
        $search['bulan'] = empty($search['bulan']) ? '' : $search['bulan'];        
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/cuti/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_permit(array($full_name, $tahun, $bulan));
        $config['uri_segment'] = 5;
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
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $params = array($full_name, $tahun, $bulan, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_all_permit_by_limit($params));
        // notification
        $this->tnotification->display_notification();
        //output
        parent::display();
    }

    // search process
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('monitoring_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "tahun" => $this->input->post("tahun"),
                "bulan" => $this->input->post("bulan")
            );
            $this->tsession->set_userdata("monitoring_search", $params);
        }
        // redirect
        redirect("kepegawaian/cuti/monitoring");
    }
    
    // detail
    public function detail($pengajuan_id = '') {
        $this->_set_page_rule("U");
        $this->smarty->assign("template_content", "kepegawaian/cuti/monitoring/detail.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_monitoring->get_detail_permit($pengajuan_id));
        $this->smarty->assign("proses", $this->m_monitoring->get_permit_proses($pengajuan_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    
    // print_process
    public function print_process($pengajuan_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/CUTI.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // ijin
        $result = $this->m_pengajuan->get_cetak_pengajuan_by_id($pengajuan_id);
        if (empty($result)) {
            // default redirect
            redirect("kepegawaian/cuti/monitoring");
        }
        $dpt_lead = $this->m_pengajuan->get_dpt_lead_by_cuti_id($pengajuan_id);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('G6', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G7', strtoupper($result['jabatan_nama']));
        $objWorksheet->setCellValue('G8', strtoupper($result['struktur_nama']));
        $objWorksheet->setCellValue('G9', "'" . strtoupper($result['nomor_telepon']));
        // // tanggal
        $arr_hari = array('1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu');
        $day_req = $arr_hari[date('N', strtotime($result['cuti_send_date']))];
        $day_req = $day_req . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_send_date']);
        $day_start = $arr_hari[date('N', strtotime($result['cuti_tanggal_mulai']))];
        $day_start = $day_start . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_tanggal_mulai']);
        $day_end = $arr_hari[date('N', strtotime($result['cuti_tanggal_selesai']))];
        $day_end = $day_end . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_tanggal_selesai']);
        $objWorksheet->setCellValue('T6', strtoupper($day_req));
        $objWorksheet->setCellValue('T7', strtoupper($day_start));
        $objWorksheet->setCellValue('T8', strtoupper($day_end));
        $objWorksheet->setCellValue('T9', $result['masa_cuti']);
        $objWorksheet->setCellValue('H23', $result['masa_cuti']);
        $objWorksheet->setCellValue('G36', $dpt_lead['nama_lengkap']);
        $objWorksheet->setCellValue('K31', $dpt_lead['nama_lengkap']);
        $objWorksheet->setCellValue('B31', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G18', strtoupper($result['cuti_pic_name']));
        $objWorksheet->setCellValue('G19', $result['pic_position']);
        $objWorksheet->setCellValue('L23', $this->datetimemanipulation->get_short_date($result['cuti_tanggal_mulai']));
        $objWorksheet->setCellValue('Q23', $this->datetimemanipulation->get_short_date($result['cuti_tanggal_selesai']));
        // kuota cuti
        $kuota_cuti = $this->m_pengajuan->get_total_jatah_cuti(array($result['user_id'], $result['jenis_id'],date('Y', strtotime($result['cuti_tanggal_mulai']))));
        // cuti yg terpakai
        $terpakai = $this->m_pengajuan->get_total_cuti_terpakai(array($result['user_id'], $result['jenis_id'],$result['cuti_tanggal_mulai']));
        // sisa cuti yg ada
        $sisa_cuti = $kuota_cuti - $terpakai;
        $objWorksheet->setCellValue('G25', $sisa_cuti);
        // pengajuan type
        switch ($result['jenis_cuti']) {
            case 'TAHUNAN':
                $objWorksheet->setCellValue('B13', 'V');
                break;
            case 'MELAHIRKAN':
                $objWorksheet->setCellValue('B15', 'V');
                break;
            default :
                $objWorksheet->setCellValue('H13', 'V');
                break;
        }
        // file_name
        $file_name = "SC_" . str_replace(' ', '_', strtoupper($result['nama_lengkap'])) . "_" . str_replace('-', '_', $result['cuti_tanggal_mulai']);
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }
}
