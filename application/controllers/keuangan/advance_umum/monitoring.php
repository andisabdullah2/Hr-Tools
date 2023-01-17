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
        $this->load->model('keuangan/advance_umum/m_monitoring');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/monitoring/index.html");
        // load library
        $this->load->library('datetimemanipulation');
        $this->smarty->assign("dtm", $this->datetimemanipulation);
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_advance_umum') ? $this->tsession->userdata('search_advance_umum') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $item_uraian = empty($search['item_uraian']) ? '%' : '%' . $search['item_uraian'] . '%';
        $advance_status = empty($search['advance_status']) ? '%' : $search['advance_status'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("keuangan/advance_umum/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_pengajuan_advance_umum(array($item_uraian, $advance_status));
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
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_pengajuan_advance_umum($params));
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
            $this->tsession->set_userdata("search_advance_umum", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_advance_umum");
        }
        // redirect
        redirect("keuangan/advance_umum/monitoring");
    }

    // detail
    public function detail($trx_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/monitoring/detail.html");
        //
        $detail = $this->m_monitoring->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/monitoring");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_rincian_item($trx_id));
        $this->smarty->assign("rs_lpj", $this->m_monitoring->get_list_lpj_by_trx_id($detail['trx_id']));
        $this->smarty->assign("rs_flow", $this->m_monitoring->get_list_flow_plan(array($trx_id, 21)));
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
            redirect("keuangan/advance_umum/pengajuan");
        }
        $rs_item = $this->m_monitoring->get_list_rincian_item($trx_id);
        // persetujuan proses
        $pimpinan = $this->m_monitoring->get_persetujuan_process( array($trx_id, '21002') );
        $keuangan = $this->m_monitoring->get_persetujuan_process( array($trx_id, '21003') );
        $dirut = $this->m_monitoring->get_persetujuan_process( array($trx_id, '21004') );
        $kasir = $this->m_monitoring->get_persetujuan_process( array($trx_id, '21005') );
        $sisa = $this->m_monitoring->get_persetujuan_process( array($trx_id, '21007') );
        // load template
        switch ( strtoupper($detail['struktur_singkatan']) ) {
            case 'APT': $filepath = "resource/doc/template/ADVANCE_UMUM_APT.xlsx"; break;
            case 'TTS': $filepath = "resource/doc/template/ADVANCE_UMUM_TTS.xlsx"; break;
            case 'FORTIS': $filepath = "resource/doc/template/ADVANCE_UMUM_FORTIS.xlsx"; break;
            case 'OPTIMA': $filepath = "resource/doc/template/ADVANCE_UMUM_OPTIMA.xlsx"; break;
            default: $filepath = "resource/doc/template/ADVANCE_UMUM_TE.xlsx";
        }
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // data awal
        $objWorksheet->setCellValue('J12', $detail['kode_output'] . "." . $detail['kode_akun'] . "." . $detail['item_no']);
        $objWorksheet->setCellValue('J51', $detail['kode_output'] . "." . $detail['kode_akun'] . "." . $detail['item_no']);
        $objWorksheet->setCellValue('W12', $detail['advance_no']);
        $objWorksheet->setCellValue('W51', $detail['advance_no']);
        $objWorksheet->setCellValue('J14', $dtm->get_full_date($detail['advance_tanggal']));
        $objWorksheet->setCellValue('J53', $dtm->get_full_date($detail['advance_tanggal']));
        $objWorksheet->setCellValue('J15', ($detail['send_by_name'] ? : $this->com_user['user_alias']) );
        $objWorksheet->setCellValue('J54', ($detail['send_by_name'] ? : $this->com_user['user_alias']) );
        $objWorksheet->setCellValue('E33', ($pimpinan ? $dtm->get_date_only($pimpinan['mdd_finish']) : '') );
        $objWorksheet->setCellValue('E72', ($pimpinan ? $dtm->get_date_only($pimpinan['mdd_finish']) : '') );
        $objWorksheet->setCellValue('I33', ($keuangan ? $dtm->get_date_only($keuangan['mdd_finish']) : '') );
        $objWorksheet->setCellValue('I72', ($keuangan ? $dtm->get_date_only($keuangan['mdd_finish']) : '') );
        $objWorksheet->setCellValue('O33', ($dirut ? $dtm->get_date_only($dirut['mdd_finish']) : '') );
        $objWorksheet->setCellValue('O72', ($dirut ? $dtm->get_date_only($dirut['mdd_finish']) : '') );
        $objWorksheet->setCellValue('T37', ($kasir ? $dtm->get_date_only($kasir['mdd_finish']) : '') );
        $objWorksheet->setCellValue('T76', ($kasir ? $dtm->get_date_only($kasir['mdd_finish']) : '') );
        $objWorksheet->setCellValue('W37', ($sisa ? $dtm->get_date_only($sisa['mdd_finish']) : '') );
        $objWorksheet->setCellValue('W76', ($sisa ? $dtm->get_date_only($sisa['mdd_finish']) : '') );
        $objWorksheet->setCellValue('D36', ($pimpinan ? $pimpinan['mdb_finish_name'] : '') );
        $objWorksheet->setCellValue('D75', ($pimpinan ? $pimpinan['mdb_finish_name'] : '') );
        $objWorksheet->setCellValue('H36', ($keuangan ? $keuangan['mdb_finish_name'] : '') );
        $objWorksheet->setCellValue('H75', ($keuangan ? $keuangan['mdb_finish_name'] : '') );
        // tambahkan row baru jika item lebih dari 8
        $add_row = count($rs_item) >= 8 ? count($rs_item) - 8 : 0;
        if ($add_row) {
            $objWorksheet->insertNewRowBefore(29, $add_row);
            $objWorksheet->insertNewRowBefore(68, $add_row);
        }
        for ($i = 0; $i < $add_row; $i++) {
            $objWorksheet->mergeCells('E' . (29+$i) . ':N' . (29+$i));
            $objWorksheet->mergeCells('O' . (29+$i) . ':S' . (29+$i));
            $objWorksheet->mergeCells('T' . (29+$i) . ':AA' . (29+$i));
            $objWorksheet->mergeCells('E' . (68+$i) . ':N' . (68+$i));
            $objWorksheet->mergeCells('O' . (68+$i) . ':S' . (68+$i));
            $objWorksheet->mergeCells('T' . (68+$i) . ':AA' . (68+$i));
        }
        // isi data item ke cell
        $row_page_1 = 20;
        $row_page_2 = 59 + $add_row;
        $total = 0;
        foreach ($rs_item as $index => $item) {
            //
            $subtotal = $item['item_jumlah'] * $item['item_total'];
            $total += $subtotal;
            //
            $objWorksheet->setCellValue('D'.$row_page_1, $index+1);
            $objWorksheet->setCellValue('D'.$row_page_2, $index+1);
            $objWorksheet->setCellValue('E'.$row_page_1, $item['item_uraian']);
            $objWorksheet->setCellValue('E'.$row_page_2, $item['item_uraian']);
            $objWorksheet->setCellValue('O'.$row_page_1, $subtotal);
            $objWorksheet->setCellValue('O'.$row_page_2, $subtotal);
            $objWorksheet->setCellValue('T'.$row_page_1, $item['item_keterangan']);
            $objWorksheet->setCellValue('T'.$row_page_2, $item['item_keterangan']);
            //
            $row_page_1++; $row_page_2++;
        }
        // total dan terbilang
        $objWorksheet->setCellValue('O'.(30 + $add_row), $total);
        $objWorksheet->setCellValue('O'.(69 + $add_row + $add_row), $total);
        $objWorksheet->setCellValue('T'.(30 + $add_row), $this->terbilang->rupiah($total));
        $objWorksheet->setCellValue('T'.(69 + $add_row + $add_row), $this->terbilang->rupiah($total));
        $objWorksheet->getStyle('T'.(30 + $add_row))->getAlignment()->setWrapText(true);
        $objWorksheet->getStyle('T'.(69 + $add_row + $add_row))->getAlignment()->setWrapText(true);
        // file_name
        $file_name = "ADVANCE_UMUM_" . $trx_id;
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}
