<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class ijin extends ApplicationBase {

    // contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('laporan/kepegawaian/m_ijin');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    public function index() {
        $this->_set_page_rule('R');
        //set template content
        $this->smarty->assign("template_content", "laporan/kepegawaian/ijin/index.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_ijin->get_list_tahun());
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('report_ijin_search');
        // search parameter
        $department = empty($search['department']) ? '%' : $search['department'];
        $month = empty($search['bulan']) ? date('m') : $search['bulan'];
        $year = empty($search['tahun']) ? date('Y') : $search['tahun'];
        //assign to search variabel
        $search['bulan'] = $month;
        $search['tahun'] = $year;
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("laporan/kepegawaian/ijin/index/");
        $config['total_rows'] = $this->m_ijin->get_total_pegawai();
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
        //get permit data
        $rs_id = $this->m_ijin->get_all_permit_personal($department, $month, $year);
        $this->smarty->assign("rs_id", $rs_id);
        //total data
        $this->smarty->assign("total_data", count($rs_id));
        //get department
        $this->smarty->assign("rs_department", $this->m_ijin->get_all_unit_kerja());
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('report_ijin_search');
        } else {
            $params = array(
                "department" => $this->input->post("struktur_cd"),
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun")
            );
            $this->tsession->set_userdata('report_ijin_search', $params);
        }
        // redirect
        redirect("laporan/kepegawaian/ijin");
    }

    //cetak
    public function cetak() {
        $this->_set_page_rule('R');
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/REKAP_IZIN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // bulan
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        // get search parameter
        $search = $this->tsession->userdata('report_ijin_search');
        // search parameter
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        $month = empty($search['bulan']) ? date('m') : $search['bulan'];
        $year = empty($search['tahun']) ? date('Y') : $search['tahun'];
        //get permit data
        $rs_id = $this->m_ijin->get_all_permit_personal($struktur_cd, $month, $year);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('D3', 'Bulan : ' . strtoupper($bulan[$month]));
        $objWorksheet->setCellValue('E3', 'Tahun : ' . $year);
        // data ijin
        $i = $mulai = 7;
        $no = 1;
        foreach ($rs_id as $data) {
            $objWorksheet->setCellValue('B' . $i, $no++);
            $objWorksheet->setCellValue('C' . $i, strtoupper($data['nama_lengkap']));
            $objWorksheet->setCellValue('D' . $i, strtoupper($data['struktur_nama']));
            $objWorksheet->setCellValue('E' . $i, $data['total_tdk_msk']);
            $objWorksheet->setCellValue('F' . $i, $data['total_tdk_absen']);
            $objWorksheet->setCellValue('G' . $i, $data['total_terlambat']);
            $objWorksheet->setCellValue('H' . $i, $data['total_plg_awal']);
            $objWorksheet->setCellValue('I' . $i, $data['total_tgl_kerja']);
            $objWorksheet->setCellValue('J' . $i, '=SUM(E' . $i . ':I' . $i . ')');
            $i++;
        }
        $akhir = $i - 1;
        //set border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
            ),
        );
        $objWorksheet->getStyle('B' . $mulai . ':J' . $akhir)->applyFromArray($styleArray);
        // file_name
        $file_name = "REKAP_CUTI_" . strtoupper($bulan[$month]) . "_" . $year;
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