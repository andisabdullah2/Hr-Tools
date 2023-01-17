<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class lpj extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/advance_jaldin/m_approval');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_jaldin/lpj/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_advance_jaldin_lpj') ? $this->tsession->userdata('search_advance_jaldin_lpj') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $item_uraian = empty($search['item_uraian']) ? '%' : '%' . $search['item_uraian'] . '%';
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("keuangan/advance_jaldin/lpj/index/");
        $config['total_rows'] = $this->m_approval->get_total_advance_jaldin_lpj(array(6, $item_uraian));
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
        $params = array(6, $item_uraian, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_advance_jaldin_lpj($params));
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
            );
            // set session
            $this->tsession->set_userdata("search_advance_jaldin_lpj", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_advance_jaldin_lpj");
        }
        // redirect
        redirect("keuangan/advance_jaldin/lpj");
    }

    // detail
    public function detail($process_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_jaldin/lpj/detail.html");
        // load library
        $this->load->library('datetimemanipulation');
        $this->smarty->assign("dtm", $this->datetimemanipulation);
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_rincian_item($detail['trx_id']));
        $this->smarty->assign("rs_lpj", $this->m_approval->get_list_lpj_by_trx_id($detail['trx_id']));
        $this->smarty->assign("flow", $this->m_approval->get_flow_plan(array($detail['trx_id'], 22, 7)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // lpj add process
    public function lpj_add_process() {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('process_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('debit', 'Pengeluaran', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Penerimaan', 'trim');
        //
        $process_id = $this->input->post('process_id', TRUE);
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $lpj_id = $this->m_approval->get_lpj_id();
            //params
            $params = array(
                'trx_id' => $detail['trx_id'],
                'lpj_id' => $lpj_id,
                'uraian' => $this->input->post('uraian', true),
                'tanggal' => $this->input->post('tanggal', true),
                'debit' => $this->input->post('debit', true),
                'kredit' => $this->input->post('kredit', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_approval->insert_trx_advance_lpj($params)) {
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
        redirect("keuangan/advance_jaldin/lpj/detail/".$process_id);
    }

    // lpj edit process
    public function lpj_edit_process() {
        // set rules
        $this->_set_page_rule("U");
        //cek input
        $this->tnotification->set_rules('process_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('lpj_id', 'ID LPJ', 'trim|required');
        $this->tnotification->set_rules('uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('debit', 'Pengeluaran', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Penerimaan', 'trim');
        //
        $process_id = $this->input->post('process_id', TRUE);
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //params
            $params = array(
                'uraian' => $this->input->post('uraian', true),
                'tanggal' => $this->input->post('tanggal', true),
                'debit' => $this->input->post('debit', true),
                'kredit' => $this->input->post('kredit', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'lpj_id' => $this->input->post('lpj_id', TRUE) );
            // update
            if ($this->m_approval->update_trx_advance_lpj($params, $where)) {
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
        redirect("keuangan/advance_jaldin/lpj/detail/".$process_id);
    }

    // lpj delete process
    public function lpj_delete_process() {
        // set rules
        $this->_set_page_rule("D");
        //cek input
        $this->tnotification->set_rules('process_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('lpj_id', 'ID LPJ', 'trim|required');
        //
        $process_id = $this->input->post('process_id', TRUE);
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array( 'lpj_id' => $this->input->post('lpj_id', TRUE) );
            // delete
            if ($this->m_approval->delete_trx_advance_lpj($where)) {
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
        redirect("keuangan/advance_jaldin/lpj/detail/".$process_id);
    }

    // pengajuan_process
    public function pengajuan_process($process_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        //
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        }
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
        $where = array( 'process_id' => $process_id );
        $this->m_approval->update_trx_advance_process($params, $where);
        // flow 7
        $next_flow = $this->m_approval->get_flow_by_params(array(22, 7));
        if ( $next_flow ) {
            $new_process_id = $this->m_approval->get_process_id();
            $params = array(
                'process_id' => $new_process_id,
                'trx_id' => $detail['trx_id'],
                'flow_id' => $next_flow['flow_id'],
                'process_references_id' => $process_id,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $this->m_approval->insert_trx_advance_process($params);
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
            // default redirect
            redirect("keuangan/advance_jaldin/lpj");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
        }
        // default redirect
        redirect("keuangan/advance_jaldin/lpj/detail/".$process_id);
    }

}
