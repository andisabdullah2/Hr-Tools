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
        $this->load->model("kepegawaian/ijin/m_monitoring");
        $this->load->model("kepegawaian/ijin/m_pengajuan");        
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
        $this->smarty->assign("template_content", "kepegawaian/ijin/monitoring/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_monitoring->get_list_tahun());
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('pengajuan_search');
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
        $config['base_url'] = site_url("kepegawaian/ijin/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_permit(array($full_name, $tahun, $bulan));
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
            $this->tsession->unset_userdata('pengajuan_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "tahun" => $this->input->post("tahun"),
                "bulan" => $this->input->post("bulan")
            );
            $this->tsession->set_userdata("pengajuan_search", $params);
        }
        // redirect
        redirect("kepegawaian/ijin/monitoring");
    }
    
    public function detail($pengajuan_id = '') {
        $this->_set_page_rule("U");
        $this->smarty->assign("template_content", "kepegawaian/ijin/monitoring/detail.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_monitoring->get_detail_permit($pengajuan_id));
        $this->smarty->assign("proses", $this->m_monitoring->get_permit_proses($pengajuan_id));
        // echo $this->db->last_query();
        // echo "<pre>";print_r($this->m_monitoring->get_permit_proses($pengajuan_id));echo "</pre>";
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    
    public function print_process($permit_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/IZIN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get detail permit to print
        $result = $this->m_pengajuan->get_detail_print(array($permit_id));
        $department_leader = $this->m_pengajuan->get_unit_kerja_leader(array($result['user_id']));
        // get permit type
        $result_izin_tipe = $this->m_pengajuan->get_permit_type('permit');
        // print_r($result);
        if (empty($result)) {
            // default redirect
            redirect("home/task/permit/");
        }
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('F11', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('F13', strtoupper(($result['jabatan_struktural']!=''?$result['jabatan_struktural']:$result['jabatan_fungsional'])));
        $objWorksheet->setCellValue('F15', strtoupper($result['struktur_nama']));
        // permit type
        $tes = 19;
        $coba = 0;
        foreach ($result_izin_tipe as $key) {
            if ($key['jenis_id'] == $result['jenis_id']) {
                if ($coba < 3) {
                    $objWorksheet->setCellValue('B' . $tes, 'V');
                } else {
                    $objWorksheet->setCellValue('H' . $tes, 'V');
                }
            } else {
                if ($coba == 2) {
                    $tes = 17;
                }
                $tes = $tes + 2;
                $coba++;
            }
        }
        // get day
        $date = $this->datetimemanipulation->get_date_indonesia($result['izin_tanggal']);
        $objWorksheet->setCellValue('F25', strtoupper($date['hari']) . ', ' . strtoupper($date['tanggal']) . ' ' . strtoupper($date['bulan']) . ' ' . strtoupper($date['tahun']));
        $objWorksheet->setCellValue('F27', $result['izin_waktu_mulai']);
        $objWorksheet->setCellValue('N27', $result['izin_waktu_selesai']);
        $objWorksheet->setCellValue('F29', strtoupper(($result['izin_uraian'])));
        $objWorksheet->setCellValue('B40', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G40', $department_leader);
        $objWorksheet->setCellValue('F46', $department_leader);
        // file_name
        $file_name = "SI_" . strtoupper(str_replace(' ', '_', $result['nama_lengkap'])) . '_' . str_replace('-', '_', $result['izin_tanggal']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }    
}
