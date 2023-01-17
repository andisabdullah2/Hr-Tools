<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class monitoring extends ApplicationBase {

    //contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/jaldin/m_monitoring");
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/monitoring/list.html");
        // tahun
        $this->smarty->assign("rs_tahun", $this->m_monitoring->get_list_tahun_jaldin());
        // bulan
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_month('in'));
        // get search parameter
        $search = $this->tsession->userdata('monitoring_jaldin_search');
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $nama = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%';
        $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $this->smarty->assign("search", $search);
        //  start of pagination --------------------- 
        // // pagination
        $config['base_url'] = site_url("kepegawaian/jaldin/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_jaldin(array($project, $nama, $search['tahun'], $bulan));
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
        $params = array($project, $nama, $search['tahun'], $bulan, ($start - 1), $config['per_page']);
        $rs_id = $this->m_monitoring->get_list_jaldin_by_limit($params);
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // search process
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('monitoring_jaldin_search');
        } else {
            $params = array(
                "project" => $this->input->post("project", true),
                "nama" => $this->input->post("nama", true),
                "bulan" => $this->input->post("bulan", true),
                "tahun" => $this->input->post("tahun", true),
            );
            $this->tsession->set_userdata("monitoring_jaldin_search", $params);
        }
        // redirect
        redirect("kepegawaian/jaldin/monitoring/");
    }

    // detail data jaldin
    public function detail($spt_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/monitoring/detail.html");
        // get detail data
        $detail = $this->m_monitoring->get_detail_jaldin_by_id(array($spt_id, $spt_id, $spt_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/monitoring");
        }
        $this->smarty->assign("detail", $detail);
        //get list advance
        $rs_advance = $this->m_monitoring->get_list_advance_by_spt($spt_id);
        $this->smarty->assign("rs_advance", $rs_advance);
        //get list lpj
        $rs_lpj = $this->m_monitoring->get_list_lpj_by_spt($spt_id);
        $this->smarty->assign("rs_lpj", $rs_lpj);
        // output
        parent::display();
    }

    // download excel
    public function surat_tugas($spt_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/JALDIN.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get data & validation
        $result = $this->m_monitoring->get_detail_jaldin_by_id(array($spt_id, $spt_id, $spt_id));
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan/");
        }
        $gm = $this->m_monitoring->get_detail_gm($result['struktur_cd']);
        // echo "<pre>"; print_r($result); echo "</pre>"; exit();
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('D11', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('D12', $result['pegawai_nip']);
        $objWorksheet->setCellValue('D13', strtoupper($result['struktur_nama']) . ' / ' . strtoupper($result['jabatan']));
        $objWorksheet->setCellValue('D14', strtoupper($this->datetimemanipulation->get_full_date($result['tanggal_berangkat'])));
        $objWorksheet->setCellValue('F14', strtoupper($this->datetimemanipulation->get_full_date($result['tanggal_pulang'])));
        $objWorksheet->setCellValue('D15', strtoupper($result['lokasi_tujuan']));
        $objWorksheet->setCellValue('D16', strtoupper($result['project_alias']) . ' - ' . $result['uraian_tugas']);
        $objWorksheet->setCellValue('B30', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(substr($result['mdd'], 0, 10)));
        $objWorksheet->setCellValue('B35', strtoupper($result['nama_lengkap']));
        if(!empty($gm)){
            $objWorksheet->setCellValue('D35', strtoupper($gm['nama_lengkap']));
        }
        // file_name
        $file_name = "ST_" . str_replace(' ', '_', strtoupper($result['project_alias'])) . "_" . str_replace('-', '_', $result['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    public function advance($spt_id = ''){
        // set page rule
        $this->_set_page_rule("R");
        //get detail data spt
        $detail = $this->m_monitoring->get_detail_jaldin_by_id(array($spt_id, $spt_id, $spt_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/monitoring");
        }
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/JALDIN_ADVANCE.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        //get advance
        $rs_advance = $this->m_monitoring->get_list_advance_by_spt(array($spt_id));
        // echo "<pre>"; print_r($detail); echo "</pre>"; exit();
        /*
         * SET DATA EXCELL
         */
        //convert mdd to date
        $tgl_pengajuan = date('Y-m-d', strtotime($detail['mdd']));
        //page 1
        $objWorksheet->setCellValue('H13', strtoupper($this->datetimemanipulation->get_full_date($tgl_pengajuan)));
        $objWorksheet->setCellValue('H14', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('H16', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_berangkat'])));
        $objWorksheet->setCellValue('L16', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_pulang'])));
        $objWorksheet->setCellValue('H17', substr($detail['waktu_berangkat'], 0, 5));
        $objWorksheet->setCellValue('H18', substr($detail['waktu_pulang'], 0, 5));
        $objWorksheet->setCellValue('T13', strtoupper($detail['lokasi_tujuan']));
        $objWorksheet->setCellValue('T14', strtoupper($detail['client_nm']));
        $objWorksheet->setCellValue('T15', strtoupper($detail['client_address']));
        $objWorksheet->setCellValue('T16', strtoupper($detail['uraian_tugas']));
        //set cell value
        $cellno = 21;
        $no = 1;
        $totalbiaya = 0;
        foreach ($rs_advance as $result) {
            $objWorksheet->setCellValue('C' . $cellno, $no++);
            $objWorksheet->setCellValue('D' . $cellno, ucfirst(strtolower($result['jenis_biaya'])));
            if($result['kredit_status'] == '1'){
                $objWorksheet->setCellValue('M' . $cellno, number_format($result['jumlah'],2,',','.'));
                $totalbiaya += $result['jumlah'];
            }else{
                $objWorksheet->setCellValue('M' . $cellno, '(' . number_format($result['jumlah'],2,',','.') . ')');
                $totalbiaya -= $result['jumlah'];
            }
            $objWorksheet->setCellValue('R' . $cellno, ucfirst(strtolower($result['keterangan'])));
            $cellno++;
        }
        $objWorksheet->setCellValue('M26', number_format($totalbiaya,2,',','.'));
        // // page 2
        $objWorksheet->setCellValue('H53', strtoupper($this->datetimemanipulation->get_full_date($tgl_pengajuan)));
        $objWorksheet->setCellValue('H54', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('H56', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_berangkat'])));
        $objWorksheet->setCellValue('H57', substr($detail['waktu_berangkat'], 0, 5));
        $objWorksheet->setCellValue('H58', substr($detail['waktu_pulang'], 0, 5));
        $objWorksheet->setCellValue('L56', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_pulang'])));
        $objWorksheet->setCellValue('T53', strtoupper($detail['lokasi_tujuan']));
        $objWorksheet->setCellValue('T54', strtoupper($detail['client_nm']));
        $objWorksheet->setCellValue('T55', strtoupper($detail['client_address']));
        $objWorksheet->setCellValue('T56', strtoupper($detail['uraian_tugas']));
        // set cell value
        $cellno = 61;
        $no = 1;
        foreach ($rs_advance as $result) {
            $objWorksheet->setCellValue('C' . $cellno, $no++);
            $objWorksheet->setCellValue('D' . $cellno, ucfirst(strtolower($result['jenis_biaya'])));
            if($result['kredit_status'] == '1'){
                $objWorksheet->setCellValue('M' . $cellno, number_format($result['jumlah'],2,',','.'));
            }else{
                $objWorksheet->setCellValue('M' . $cellno, '(' . number_format($result['jumlah'],2,',','.') . ')');
            }
            $objWorksheet->setCellValue('R' . $cellno, ucfirst(strtolower($result['keterangan'])));
            $cellno++;
        }
        $objWorksheet->setCellValue('M66', number_format($totalbiaya,2,',','.'));
        // // file_name
        $file_name = "ADV_" . str_replace('-', '_', strtoupper($detail['project_alias'])) . "_" . str_replace('-', '_', $detail['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    // download excell
    public function lpj($spt_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        //get detail data
        $detail = $this->m_monitoring->get_detail_jaldin_by_id(array($spt_id, $spt_id, $spt_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/monitoring");
        }
        //get list advance
        $rs_id = $this->m_monitoring->get_list_advance_by_spt($spt_id);
        //get list lpj
        $rs_lpj = $this->m_monitoring->get_list_lpj_by_spt($spt_id);
        // echo "<pre>"; print_r($detail); echo "</pre>"; exit();
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/LPJ.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // parse data
        $objWorksheet->setCellValue('D9', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('D11', 'Rp. ' . number_format($detail['total_advance'],2,',','.'));
        $objWorksheet->setCellValue('G9', ': ' . $this->datetimemanipulation->get_short_date($detail['tanggal_berangkat']) . ' s/d ' . $this->datetimemanipulation->get_short_date($detail['tanggal_pulang']));
        $objWorksheet->setCellValue('G10', ': ' . strtoupper($detail['uraian_tugas']));
        $objWorksheet->setCellValue('G11', strtoupper($detail['project_name']));
        $objWorksheet->setCellValue('G71', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d')));
        $objWorksheet->setCellValue('G76', strtoupper($detail['nama_lengkap']));
        // lpj
        $no = 1;
        $row = 16;
        $saldo = 0;
        $total_debit = 0;
        $total_kredit = $detail['total_advance'];
        $total_saldo = 0;
        //penerimaan kasir
        $objWorksheet->setCellValue('F15', 'Rp. ' . number_format($detail['total_advance'],2,',','.'));
        foreach ($rs_lpj as $data) {
            //total
            $total_kredit += $data['kredit'];
            $total_debit += $data['debit'];
            $saldo = $total_kredit - $total_debit;
            //write
            $objWorksheet->setCellValue('A' . $row,  ' ' . $no);
            $objWorksheet->setCellValue('B' . $row, $data['tanggal']);
            $objWorksheet->setCellValue('C' . $row, $data['keterangan']);
            if($data['kredit'] == 0){
                $objWorksheet->setCellValue('F' . $row, '');
            }else{
                $objWorksheet->setCellValue('F' . $row, 'Rp. ' . number_format($data['kredit'],2,',','.'));
            }
            if($data['debit'] == 0){
                $objWorksheet->setCellValue('G' . $row, '');
            }else{
                $objWorksheet->setCellValue('G' . $row, 'Rp. ' . number_format($data['debit'],2,',','.'));
            }
            $objWorksheet->setCellValue('H' . $row, 'Rp. ' . number_format($saldo,2,',','.'));
            // loop
            $no++;
            $row++;
        }
        // exit();
        $objWorksheet->setCellValue('F62', 'Rp. ' . number_format($total_kredit,2,',','.'));
        $objWorksheet->setCellValue('G62', 'Rp. ' . number_format($total_debit,2,',','.'));
        $objWorksheet->setCellValue('H62', 'Rp. ' . number_format($saldo,2,',','.'));
        //total penerimaan
        $objWorksheet->setCellValue('D64', 'Rp. ' . number_format($total_kredit,2,',','.'));
        $objWorksheet->setCellValue('D65', 'Rp. ' . number_format($total_debit,2,',','.'));
        $objWorksheet->setCellValue('D66', 'Rp. ' . number_format(($total_kredit - $total_debit),2,',','.'));
        // echo $saldo .'<br>' . $total_kredit . '<br>' .$total_debit . '<br>' . $total_saldo; exit();
        // file_name
        $file_name = "LPJ_" . str_replace(' ', '_', strtoupper($detail['nama_lengkap'])) . "_" . str_replace('-', '_', $detail['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    // download lpj
 //    public function lpj($duty_id = '') {
 //        // set page rule
 //        $this->_set_page_rule("R");
 //        // load excel
 //        $this->load->library('phpexcel');
 //        // get detail data
 //        $jaldin = $this->m_monitoring->get_detail_jaldin_by_id(array($duty_id));
 //        if (!empty($jaldin)) {
 //            // total advance
 //            $total_advance = $this->m_monitoring->get_total_duty_advance_approved(array($jaldin['duty_id']));
 //            // get laporan jaldin
 //            $rs_lpj = $this->m_monitoring->get_list_duty_lpj(array($jaldin['duty_id']));
 //        }
 //        // create excell
 //        $filepath = "resource/doc/template/LPJ_norumus.xls";
 //        // $objReader = PHPExcel_IOFactory::createReader('Excel2007');
 //        $objReader = PHPExcel_IOFactory::createReader('Excel5');
 //        $this->phpexcel = $objReader->load($filepath);
 //        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
 //        // parse data
 //        $objWorksheet->setCellValue('D9', strtoupper($jaldin['full_name']));
 //        $objWorksheet->setCellValue('D11', $total_advance);
	// $objWorksheet->setCellValue('F15', $total_advance);	
 //        $objWorksheet->setCellValue('G9', ': ' . $this->datetimemanipulation->get_short_date($jaldin['date_start']) . ' s/d ' . $this->datetimemanipulation->get_short_date($jaldin['date_end']));
 //        $objWorksheet->setCellValue('G10', ': ' . strtoupper($jaldin['duty_desc']));
 //        $objWorksheet->setCellValue('G11', strtoupper($jaldin['project_alias']));
 //        $objWorksheet->setCellValue('G71', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d')));
 //        $objWorksheet->setCellValue('G76', strtoupper($jaldin['full_name']));
 //        $objWorksheet->setCellValueExplicit('D10', $jaldin['advance_number'], PHPExcel_Cell_DataType::TYPE_STRING);
 //        // lpj
 //        $no = 1;
 //        $row = 16;
	// $saldo = $total_advance;
	// $total_kredit = 0;
	// $total_debit = 0;
 //        foreach ($rs_lpj as $data) {
	//     $saldo = $saldo + $data['kredit'] - $data['debit'];
 //            $objWorksheet->setCellValue('A' . $row, $no);
 //            $objWorksheet->setCellValue('B' . $row, $data['tanggal']);
 //            $objWorksheet->setCellValue('C' . $row, $data['uraian']);
 //            $objWorksheet->setCellValue('F' . $row, (empty($data['kredit']) ? '' : $data['kredit']));
 //            $objWorksheet->setCellValue('G' . $row, $data['debit']);
 //            $objWorksheet->setCellValue('H' . $row, $saldo);
	//     $total_kredit = $total_kredit + $data['kredit'];
	//     $total_debit = $total_debit + $data['debit'];
 //            // loop
 //            $no++;
 //            $row++;
 //        }
	// $total_kredit += $total_advance;
	// $objWorksheet->setCellValue('F62', $total_kredit);		
	// $objWorksheet->setCellValue('D64', $total_kredit);		
	// $objWorksheet->setCellValue('G62', $total_debit);
	// $objWorksheet->setCellValue('D65', $total_debit);
	// $objWorksheet->setCellValue('D66', $saldo);
	// $objWorksheet->setCellValue('H62', $saldo);
 //        // file_name
 //        $file_name = "LPJ_" . str_replace(' ', '_', strtoupper($jaldin['full_name'])) . "_" . str_replace('-', '_', $jaldin['date_start']);
 //        //--
 //        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 //        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
 //        header('Cache-Control: max-age=0');
 //        // output
 //        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
 //        $obj_writer->save('php://output');
 //    }

    // download tunjangan
    public function tunjangan($duty_id = '') {
        // set page rule
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // get detail data
        $jaldin = $this->m_monitoring->get_detail_jaldin_by_id(array($duty_id));
	$tunjangan = array();
        $tunjangan = array($tunjangan);
        if (!empty($jaldin)) {
            // total advance
            $tunjangan = $this->m_monitoring->get_tunjangan_jaldin(array($jaldin['duty_id']));
        }
	// print_r($tunjangan);
        // create excell
        $filepath = "resource/doc/template/JALDIN_TUNJANGAN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // parse data
        $objWorksheet->setCellValue('B8', '1');
        $objWorksheet->setCellValue('C8', strtoupper($jaldin['full_name']));
        $objWorksheet->setCellValue('D8', $jaldin['department_name']);
        $objWorksheet->setCellValue('E8', substr($this->datetimemanipulation->get_short_date($jaldin['date_start']), 2, 8) . ' - ' . substr($this->datetimemanipulation->get_short_date($jaldin['date_end']), 2, 8));
        $objWorksheet->setCellValue('F8', $jaldin['duty_location']);
	if(isset($tunjangan['uang_saku']))
        $objWorksheet->setCellValue('G8', $tunjangan['uang saku']);
	if(isset($tunjangan['makan']))
        $objWorksheet->setCellValue('H8', $tunjangan['makan']);
        $objWorksheet->setCellValue('H12', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d')));
        $objWorksheet->setCellValue('H17', strtoupper($jaldin['full_name']));
        // file_name
        $file_name = "TUNJANGAN_" . str_replace(' ', '_', strtoupper($jaldin['full_name'])) . "_" . str_replace('-', '_', $jaldin['date_start']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}