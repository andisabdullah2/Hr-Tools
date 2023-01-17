<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class monitoring extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/advance_jaldin/m_monitoring');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_jaldin/monitoring/index.html");
        // load library
        $this->load->library('datetimemanipulation');
        $this->smarty->assign("dtm", $this->datetimemanipulation);
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_advance_jaldin') ? $this->tsession->userdata('search_advance_jaldin') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $item_uraian = empty($search['item_uraian']) ? '%' : '%' . $search['item_uraian'] . '%';
        $advance_status = empty($search['advance_status']) ? '%' : $search['advance_status'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("keuangan/advance_jaldin/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_pengajuan_advance_jaldin(array($item_uraian, $advance_status));
        $config['uri_segment'] = 5;
        $config['per_page'] = 20;
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
        // get data
        $params = array($item_uraian, $advance_status, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_pengajuan_advance_jaldin($params));
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
        // session
        if ($this->input->post('save') == "Cari") {
            // params
            $params = array(
                "item_uraian" => $this->input->post('item_uraian', TRUE),
                "advance_status" => $this->input->post('advance_status', TRUE)
            );
            // set session
            $this->tsession->set_userdata("search_advance_jaldin", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_advance_jaldin");
        }
        // redirect
        redirect("keuangan/advance_jaldin/monitoring");
    }

    // detail
    public function detail($trx_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_jaldin/monitoring/detail.html");
        //
        $detail = $this->m_monitoring->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/monitoring");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_rincian_item($trx_id));
        $this->smarty->assign("rs_lpj", $this->m_monitoring->get_list_lpj_by_trx_id($detail['trx_id']));
        $this->smarty->assign("rs_flow", $this->m_monitoring->get_list_flow_plan(array($trx_id, 22)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // download 
    function download($trx_id='') {
        // set page rules
        $this->_set_page_rule("R");
        // load dtm
        $this->load->library('datetimemanipulation');
        $dtm = $this->datetimemanipulation;
        // load excel dan terbilang
        $this->load->library('phpexcel');
        $this->load->library('terbilang');
        //
        $detail = $this->m_monitoring->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/pengajuan");
        }
        $rs_item = $this->m_monitoring->get_list_rincian_item($trx_id);
        // load template
        switch ( strtoupper($detail['struktur_singkatan']) ) {
            case 'APT': $filepath = "resource/doc/template/ADVANCE_JALDIN_APT.xlsx"; break;
            case 'TTS': $filepath = "resource/doc/template/ADVANCE_JALDIN_TTS.xlsx"; break;
            case 'FORTIS': $filepath = "resource/doc/template/ADVANCE_JALDIN_FORTIS.xlsx"; break;
            case 'OPTIMA': $filepath = "resource/doc/template/ADVANCE_JALDIN_OPTIMA.xlsx"; break;
            default: $filepath = "resource/doc/template/ADVANCE_JALDIN_TE.xlsx";
        }
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // data awal
        $objWorksheet->setCellValue('H11', $detail['kode_output'] . "." . $detail['kode_akun'] . "." . $detail['item_no']);
        $objWorksheet->setCellValue('H53', $detail['kode_output'] . "." . $detail['kode_akun'] . "." . $detail['item_no']);
        $objWorksheet->setCellValue('H12', $dtm->get_full_date($detail['advance_tanggal']));
        $objWorksheet->setCellValue('H54', $dtm->get_full_date($detail['advance_tanggal']));
        $objWorksheet->setCellValue('H13', ($detail['send_by_name'] ? : $this->com_user['user_alias']) );
        $objWorksheet->setCellValue('H55', ($detail['send_by_name'] ? : $this->com_user['user_alias']) );
        $objWorksheet->setCellValue('H15', $dtm->get_full_date($detail['tanggal_berangkat']));
        $objWorksheet->setCellValue('H57', $dtm->get_full_date($detail['tanggal_berangkat']));
        $objWorksheet->setCellValue('H16', $dtm->get_time_only($detail['waktu_berangkat']));
        $objWorksheet->setCellValue('H58', $dtm->get_time_only($detail['waktu_berangkat']));
        $objWorksheet->setCellValue('H17', $dtm->get_time_only($detail['waktu_pulang']));
        $objWorksheet->setCellValue('H59', $dtm->get_time_only($detail['waktu_pulang']));
        $objWorksheet->setCellValue('H18', ($detail['mdd_finish'] ? $dtm->get_date_only($detail['mdd_finish']) : ''));
        $objWorksheet->setCellValue('H60', ($detail['mdd_finish'] ? $dtm->get_date_only($detail['mdd_finish']) : ''));
        $objWorksheet->setCellValue('U11', $detail['advance_no']);
        $objWorksheet->setCellValue('U53', $detail['advance_no']);
        $objWorksheet->setCellValue('U12', $detail['lokasi_tujuan']);
        $objWorksheet->setCellValue('U54', $detail['lokasi_tujuan']);
        $objWorksheet->setCellValue('U13', $detail['client_nm']);
        $objWorksheet->setCellValue('U55', $detail['client_nm']);
        $objWorksheet->setCellValue('U14', $detail['client_address']);
        $objWorksheet->setCellValue('U56', $detail['client_address']);
        $objWorksheet->setCellValue('U15', $detail['uraian_tugas']);
        $objWorksheet->setCellValue('U57', $detail['uraian_tugas']);
        // tambahkan row baru jika item lebih dari 8
        $add_row = count($rs_item) >= 5 ? count($rs_item) - 5 : 0;
        if ($add_row) {
            $objWorksheet->insertNewRowBefore(25, $add_row);
            $objWorksheet->insertNewRowBefore(67, $add_row);
        }
        for ($i = 0; $i < $add_row; $i++) {
            $objWorksheet->mergeCells('D' . (25+$i) . ':L' . (25+$i));
            $objWorksheet->mergeCells('M' . (25+$i) . ':Q' . (25+$i));
            $objWorksheet->mergeCells('R' . (25+$i) . ':Y' . (25+$i));
            $objWorksheet->mergeCells('D' . (67+$i) . ':L' . (67+$i));
            $objWorksheet->mergeCells('M' . (67+$i) . ':Q' . (67+$i));
            $objWorksheet->mergeCells('R' . (67+$i) . ':Y' . (67+$i));
        }
        // isi data item ke cell
        $row_page_1 = 21;
        $row_page_2 = 63 + $add_row;
        $total = 0;
        foreach ($rs_item as $index => $item) {
            //
            $subtotal = $item['item_jumlah'] * $item['item_total'];
            $total += $subtotal;
            //
            $objWorksheet->setCellValue('C'.$row_page_1, $index+1);
            $objWorksheet->setCellValue('C'.$row_page_2, $index+1);
            $objWorksheet->setCellValue('D'.$row_page_1, $item['item_uraian']);
            $objWorksheet->setCellValue('D'.$row_page_2, $item['item_uraian']);
            $objWorksheet->setCellValue('M'.$row_page_1, $subtotal);
            $objWorksheet->setCellValue('M'.$row_page_2, $subtotal);
            $objWorksheet->setCellValue('R'.$row_page_1, $item['item_keterangan']);
            $objWorksheet->setCellValue('R'.$row_page_2, $item['item_keterangan']);
            //
            $row_page_1++; $row_page_2++;
        }
        // total dan terbilang
        $objWorksheet->setCellValue('M'.(26 + $add_row), $total);
        $objWorksheet->setCellValue('M'.(68 + $add_row + $add_row), $total);
        // file_name
        $file_name = "ADVANCE_JALDIN_" . $trx_id;
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}
