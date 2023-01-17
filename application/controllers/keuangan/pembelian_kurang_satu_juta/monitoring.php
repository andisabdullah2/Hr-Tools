<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class monitoring extends ApplicationBase {

    // set group id
    protected $group_id = 23;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/pembelian_kurang_satu_juta/m_monitoring');
        // load library
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // load library
        $this->load->library('pagination');
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/monitoring/index.html");
        // search
        $search = $this->tsession->userdata("pembelian_kurang_satu_juta_monitoring_search");
        $this->smarty->assign('search', $search);
        // search parameter
        $uraian = !empty($search['uraian']) ? '%' . $search['uraian'] . '%' : '%';

        /* start of pagination --------------------- */
        // params
        $params = array($this->group_id, $uraian);
        // pagination
        $config['base_url'] = site_url("keuangan/pembelian_kurang_satu_juta/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_advance($params);
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

        // params
        $params = array($this->group_id, $uraian, ($start - 1), $config['per_page']);
        $this->smarty->assign('rs_id', $this->m_monitoring->get_list_advance($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // search process
    public function search_process(){
        // set page rule
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Cari") {
            // params
            $params = array(
                "uraian" => $this->input->post("uraian", true)
            );
            // set session search
            $this->tsession->set_userdata("pembelian_kurang_satu_juta_monitoring_search", $params);
        } else{
            // unset session search
            $this->tsession->unset_userdata("pembelian_kurang_satu_juta_monitoring_search");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/monitoring");
    }

    // detail pengajuan
    public function detail($trx_id = ''){
        // set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/monitoring/detail.html");

        // get data
        $detail = $this->m_monitoring->get_trx_advance_by_id(array($this->group_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/monitoring");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rincian item pengajuan pembelian
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_rincian_item_pembelian($trx_id));
        // get list lpj
        $this->smarty->assign("rs_lpj", $this->m_monitoring->get_list_lpj_by_trx_id($trx_id));
        // get list advance process
        $this->smarty->assign('rs_process', $this->m_monitoring->get_list_advance_process_by_trx_id(array($trx_id,$this->group_id)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // download excel
    public function download($trx_id = ''){
        // set page rule
        $this->_set_page_rule("R");
        // load library
        $this->load->library('phpexcel');
        // new dtm
        $dtm = new $this->datetimemanipulation;

        // get data
        $detail = $this->m_monitoring->get_trx_advance_by_id(array($this->group_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/monitoring");
        }
        // get list rincian item pengajuan pembelian
        $rs_id =  $this->m_monitoring->get_list_rincian_item_pembelian($trx_id);

        // load template excel
        $filepath = "resource/doc/template/FM_GA_03_FORM_PERMINTAAN_PEMBELIAN_BARANG_DIBAWAH1JUTA.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);

        // set worksheet
        $objWorksheet->setCellValue('D6', ': ' . $detail['send_name']);
        $objWorksheet->setCellValue('D7', ': ' . $detail['struktur_singkatan']);
        $objWorksheet->setCellValue('D9', $detail['advance_uraian']);
        // 
        $objWorksheet->setCellValue('F5', ': ' . $detail['item_uraian']);
        $objWorksheet->setCellValue('F7', ': ' . $dtm->get_date_short_only($detail['advance_tanggal']));
        
        // set
        $row = 11;
        $row_end = 18;
        foreach ($rs_id as $key => $result) {
            // 
            $objWorksheet->setCellValue('A' . $row, ($key+1));
            $objWorksheet->setCellValue('B' . $row, ($result['item_uraian'] ?: ''));
            $objWorksheet->setCellValue('D' . $row, ($result['item_jumlah'] ?: ''));
            $objWorksheet->setCellValue('E' . $row, ($result['item_satuan'] ?: ''));
            $objWorksheet->setCellValue('F' . $row, ($result['item_total'] ?: ''));

            // merge cell
            $objWorksheet->mergeCells('B' . $row . ':C' . $row);
            $objWorksheet->mergeCells('F' . $row . ':H' . $row);
            //insert new row
            if (($row >= $row_end) && (count($rs_id) != ($key+1))) {
                $objWorksheet->insertNewRowBefore(($row + 1), 1);
            }
            // 
            $row++;
        }
        // 
        $row_ttd = (count($rs_id) < 8) ? ($row + (26 - $row)) : ($row + 7);
        // set
        $objWorksheet->setCellValue('B' . $row_ttd, $detail['send_name']);
        // file_name
        $file_name = "PERMINTAAN_PEMBELIAN_BARANG_DIBAWAH_1_JUTA_" . str_replace('-', '_', strtoupper($detail['advance_tanggal'])) . '_' . str_replace(' ', '_', $detail['struktur_singkatan']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}
