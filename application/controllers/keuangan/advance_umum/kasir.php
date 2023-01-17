<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class kasir extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/advance_umum/m_approval');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/kasir/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_advance_umum_kasir') ? $this->tsession->userdata('search_advance_umum_kasir') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $item_uraian = empty($search['item_uraian']) ? '%' : '%' . $search['item_uraian'] . '%';
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("keuangan/advance_umum/kasir/index/");
        $config['total_rows'] = $this->m_approval->get_total_advance_umum(array(5, $item_uraian));
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
        $params = array(5, $item_uraian, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_advance_umum($params));
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
            $this->tsession->set_userdata("search_advance_umum_kasir", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_advance_umum_kasir");
        }
        // redirect
        redirect("keuangan/advance_umum/kasir");
    }

    // detail
    public function detail($process_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/advance_umum/kasir/detail.html");
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
            redirect("keuangan/advance_umum/kasir");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_rincian_item($detail['trx_id']));
        $this->smarty->assign("rs_flow", $this->m_approval->get_list_flow_plan(array($detail['trx_id'], 21, 5)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pengajuan_process
    public function pengajuan_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'ID Pengajuan', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('process_st', 'Status Pengajuan', 'trim|required');
        //
        $process_id = $this->input->post('process_id', TRUE);
        $detail = $this->m_approval->get_trx_advance_by_process_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/advance_umum/kasir");
        }
        // jika di setujui
        if ( $this->input->post('process_st', TRUE) == "approve" && !$this->input->post('advance_total_approved', TRUE) ) {
            $this->tnotification->set_error_message("Biaya yang disetujui tidak boleh kosong!");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // update flow sekarang
            $params = array(
                'process_st' => $this->input->post('process_st', TRUE),
                'action_st' => 'done',
                'catatan' => $this->input->post('catatan', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s"),
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'],
                'mdd_finish' => date("Y-m-d H:i:s")
            );
            $where = array( 'process_id' => $detail['process_id'] );
            $this->m_approval->update_trx_advance_process($params, $where); 
            //
            switch ( $this->input->post('process_st', TRUE) ) {
                case 'approve':
                    // flow 6
                    $next_flow = $this->m_approval->get_flow_by_params(array(21, 6));
                    if ( $next_flow ) {
                        $process_references_id = $process_id;
                        $process_id = $this->m_approval->get_process_id();
                        //
                        $params = array(
                            'process_id' => $process_id,
                            'trx_id' => $detail['trx_id'],
                            'flow_id' => $next_flow['flow_id'],
                            'process_references_id' => $process_references_id,
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $this->m_approval->insert_trx_advance_process($params);
                    }
                    // update biaya yg disetujui
                    $params = array(
                        'advance_total_approved' => $this->input->post('advance_total_approved', TRUE),
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array( 'trx_id' => $detail['trx_id'] );
                    $this->m_approval->update_trx_advance($params, $where);
                    break;
                
                default:
                    // flow 6
                    $next_flow = $this->m_approval->get_flow_by_params(array(21, 6));
                    if ( $next_flow ) {
                        //
                        $process_references_id = $process_id;
                        $process_id = $this->m_approval->get_process_id();
                        //
                        $params = array(
                            'process_id' => $process_id,
                            'trx_id' => $detail['trx_id'],
                            // 'flow_id' => $next_flow['flow_id'],
                            'flow_revisi_id' => $detail['flow_id'],
                            'process_references_id' => $process_references_id,
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $this->m_approval->insert_trx_advance_process($params);
                        // update plan ke draft
                        $params = array(
                            'advance_status' => 'draft',
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $where = array( 'trx_id' => $detail['trx_id'] );
                        $this->m_approval->update_trx_advance($params, $where);
                    }
                    break;
            }
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
            // default redirect
            redirect("keuangan/advance_umum/kasir/detail/".$this->input->post('process_id', TRUE));
        }
        // default redirect
        redirect("keuangan/advance_umum/kasir");
    }

}
