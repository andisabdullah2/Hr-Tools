<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class pengajuan extends ApplicationBase {

    private $group_id = "21";

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/advance_umum/m_pengajuan');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/pengajuan/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("keuangan/advance_umum/pengajuan/index/");
        $config['total_rows'] = $this->m_pengajuan->get_total_pengajuan_advance_umum();
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
        $params = array(($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_pengajuan_advance_umum($params));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/pengajuan/add.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_rencana_item());
        $this->smarty->assign("advance_no", $this->m_pengajuan->get_last_nomor());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim|required');
        $this->tnotification->set_rules('advance_no', 'Nomor Pengajuan', 'trim|required|numeric');
        $this->tnotification->set_rules('advance_tanggal', 'Tanggal Pengajuan', 'trim|required');
        $this->tnotification->set_rules('advance_total_requested', 'Biaya Diajukan', 'trim|required');
        $this->tnotification->set_rules('advance_uraian', 'Uraian', 'trim|required|max_length[255]');
        //
        $pegawai = $this->m_pengajuan->get_pegawai_by_user_id($this->com_user['user_id']);
        if (empty($pegawai)) {
            // default error
            $this->tnotification->sent_notification("error", "Pegawai tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $prefix = date('ymd');
            $trx_id = $this->m_pengajuan->get_trx_id( $prefix, $prefix . "%" );
            $tgl = explode('-', $this->input->post('advance_tanggal', true));
            //params
            $params = array(
                'trx_id' => $trx_id,
                'group_id' => $this->group_id,
                'kode_item' => $this->input->post('kode_item', true),
                'struktur_cd' => $pegawai['struktur_cd'],
                'advance_no' => $this->input->post('advance_no', true),
                'advance_tanggal' => $this->input->post('advance_tanggal', true),
                'advance_bulan' => $tgl[1],
                'advance_tahun' => $tgl[0],
                'advance_total_requested' => $this->input->post('advance_total_requested', true),
                'advance_uraian' => $this->input->post('advance_uraian', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_pengajuan->insert_trx_advance($params)) {
                // flow 1
                $flow = $this->m_pengajuan->get_flow_by_params(array(21, 1));
                if ($flow) {
                    $process_id = $this->m_pengajuan->get_process_id();
                    $params = array(
                        'process_id' => $process_id,
                        'trx_id' => $trx_id,
                        'flow_id' => $flow['flow_id'],
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $this->m_pengajuan->insert_trx_advance_process($params);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("keuangan/advance_umum/pengajuan/item/".$trx_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan/add");
    }

    // edit
    public function edit($trx_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/pengajuan/edit.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_rencana_item());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // set rules
        $this->_set_page_rule("U");
        //cek input
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim|required');
        $this->tnotification->set_rules('advance_no', 'Nomor Pengajuan', 'trim|required|numeric');
        $this->tnotification->set_rules('advance_tanggal', 'Tanggal Pengajuan', 'trim|required');
        $this->tnotification->set_rules('advance_total_requested', 'Biaya Diajukan', 'trim|required');
        $this->tnotification->set_rules('advance_uraian', 'Uraian', 'trim|required|max_length[255]');
        //
        $trx_id = $this->input->post('trx_id', TRUE);
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $tgl = explode('-', $this->input->post('advance_tanggal', true));
            //params
            $params = array(
                'kode_item' => $this->input->post('kode_item', true),
                'advance_no' => $this->input->post('advance_no', true),
                'advance_tanggal' => $this->input->post('advance_tanggal', true),
                'advance_bulan' => $tgl[1],
                'advance_tahun' => $tgl[0],
                'advance_total_requested' => $this->input->post('advance_total_requested', true),
                'advance_uraian' => $this->input->post('advance_uraian', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'trx_id' => $trx_id );
            // update
            if ($this->m_pengajuan->update_trx_advance($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("keuangan/advance_umum/pengajuan/item/".$trx_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan/edit/".$trx_id);
    }

    // delete
    public function delete($trx_id="") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/pengajuan/delete.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        //
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_rincian_item($trx_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process() {
        // set rules
        $this->_set_page_rule("D");
        //cek input
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required|max_length[15]');
        //
        $trx_id = $this->input->post('trx_id', TRUE);
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array( 'trx_id' => $trx_id );
            // delete
            if ($this->m_pengajuan->delete_trx_advance($where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan");
    }

    // item
    public function item($trx_id="") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/pengajuan/item.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        //
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_rincian_item($trx_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // item add process
    public function item_add_process() {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_jumlah', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('item_total', 'Total Rincian', 'trim|required');
        $this->tnotification->set_rules('item_keterangan', 'Keterangan', 'trim');
        //
        $trx_id = $this->input->post('trx_id', TRUE);
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $data_id = $this->m_pengajuan->get_data_id( $trx_id, $trx_id . "%" );
            //params
            $params = array(
                'trx_id' => $trx_id,
                'data_id' => $data_id,
                'item_uraian' => $this->input->post('item_uraian', true),
                'item_jumlah' => $this->input->post('item_jumlah', true),
                'item_total' => $this->input->post('item_total', true),
                'item_keterangan' => $this->input->post('item_keterangan', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_pengajuan->insert_trx_advance_rincian($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan/item/".$trx_id);
    }

    // item edit process
    public function item_edit_process() {
        // set rules
        $this->_set_page_rule("U");
        //cek input
        $this->tnotification->set_rules('data_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_jumlah', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('item_total', 'Total Rincian', 'trim|required');
        $this->tnotification->set_rules('item_keterangan', 'Keterangan', 'trim');
        //
        $data_id = $this->input->post('data_id', TRUE);
        $detail = $this->m_pengajuan->get_rincian_item_by_id($data_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //params
            $params = array(
                'item_uraian' => $this->input->post('item_uraian', true),
                'item_jumlah' => $this->input->post('item_jumlah', true),
                'item_total' => $this->input->post('item_total', true),
                'item_keterangan' => $this->input->post('item_keterangan', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'data_id' => $data_id );
            // update
            if ($this->m_pengajuan->update_trx_advance_rincian($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan/item/".$detail['trx_id']);
    }

    // item delete process
    public function item_delete_process() {
        // set rules
        $this->_set_page_rule("D");
        //cek input
        $this->tnotification->set_rules('data_id', 'ID Pengajuan', 'trim|required');
        //
        $data_id = $this->input->post('data_id', TRUE);
        $detail = $this->m_pengajuan->get_rincian_item_by_id($data_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array( 'data_id' => $data_id );
            // update
            if ($this->m_pengajuan->delete_trx_advance_rincian($where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan/item/".$detail['trx_id']);
    }

    // pengajuan_process
    public function pengajuan_process($trx_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        //
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        if (count($this->m_pengajuan->get_list_rincian_item($trx_id)) < 1) {
            // default error
            $this->tnotification->sent_notification("error", "Rincian pengajuan belum ada");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan/item/".$trx_id);
        }
        // cek flow terakhir, jika ada flow yg revisi lanjutkan dari flow revisi, jika tidak buat flow ke 2
        $last_flow = $this->m_pengajuan->get_last_process_flow($trx_id);
        // update flow terakhir
        $params = array(
            'process_st' => 'approve',
            'action_st' => 'done',
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s"),
            'mdb_finish' => $this->com_user['user_id'],
            'mdb_finish_name' => $this->com_user['user_alias'],
            'mdd_finish' => date("Y-m-d H:i:s")
        );
        $where = array( 'process_id' => $last_flow['process_id'] );
        $this->m_pengajuan->update_trx_advance_process($params, $where); 
        //
        if ( $last_flow['flow_revisi_id'] ) {
            $process_id = $this->m_pengajuan->get_process_id();
            $params = array(
                'process_id' => $process_id,
                'trx_id' => $trx_id,
                'flow_id' => $last_flow['flow_revisi_id'],
                'process_references_id' => $last_flow['process_id'],
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $this->m_pengajuan->insert_trx_advance_process($params);
        } else {
            // flow 2
            $next_flow = $this->m_pengajuan->get_flow_by_params(array(21, 2));
            if ( $next_flow ) {
                $process_id = $this->m_pengajuan->get_process_id();
                $params = array(
                    'process_id' => $process_id,
                    'trx_id' => $trx_id,
                    'flow_id' => $next_flow['flow_id'],
                    'process_references_id' => $last_flow['process_id'],
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                $this->m_pengajuan->insert_trx_advance_process($params); 
            }
        }
        // ubah status rencana ke process
        $params = array(
            'advance_status' => 'waiting',
            'send_by' => $this->com_user['user_id'],
            'send_by_name' => $this->com_user['user_alias'],
            'send_date' => date("Y-m-d H:i:s"),
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s")
        );
        $where = array( 'trx_id' => $trx_id );
        //
        if ( $this->m_pengajuan->update_trx_advance($params, $where) ) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
        }
        // default redirect
        redirect("keuangan/advance_umum/pengajuan");
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
        $detail = $this->m_pengajuan->get_trx_advance_by_id($trx_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/pengajuan");
        }
        $rs_item = $this->m_pengajuan->get_list_rincian_item($trx_id);
        // persetujuan proses
        $pimpinan = $this->m_pengajuan->get_persetujuan_process( array($trx_id, '21002') );
        $keuangan = $this->m_pengajuan->get_persetujuan_process( array($trx_id, '21003') );
        $dirut = $this->m_pengajuan->get_persetujuan_process( array($trx_id, '21004') );
        $kasir = $this->m_pengajuan->get_persetujuan_process( array($trx_id, '21005') );
        $sisa = $this->m_pengajuan->get_persetujuan_process( array($trx_id, '21007') );
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
        $objWorksheet->setCellValue('S37', ($kasir ? $dtm->get_date_only($kasir['mdd_finish']) : '') );
        $objWorksheet->setCellValue('S76', ($kasir ? $dtm->get_date_only($kasir['mdd_finish']) : '') );
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
