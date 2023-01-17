<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class kasir extends ApplicationBase {

    // set 
    protected $group_id = 24;
    protected $flow_id = 24006;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/pembelian_lebih_satu_juta/m_kasir');
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
        $this->smarty->assign("template_content", "keuangan/pembelian_lebih_satu_juta/kasir/index.html");
        // search
        $search = $this->tsession->userdata("pembelian_lebih_satu_juta_kasir_search");
        $this->smarty->assign('search', $search);
        // search parameter
        $uraian = !empty($search['uraian']) ? '%' . $search['uraian'] . '%' : '%';

        /* start of pagination --------------------- */
        // params
        $params = array($this->group_id, $this->flow_id, $uraian);
        // pagination
        $config['base_url'] = site_url("keuangan/pembelian_lebih_satu_juta/kasir/index/");
        $config['total_rows'] = $this->m_kasir->get_total_advance($params);
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
        $params = array($this->group_id, $this->flow_id, $uraian, ($start - 1), $config['per_page']);
        $this->smarty->assign('rs_id', $this->m_kasir->get_list_advance($params));

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
            $this->tsession->set_userdata("pembelian_lebih_satu_juta_kasir_search", $params);
        } else{
            // unset session search
            $this->tsession->unset_userdata("pembelian_lebih_satu_juta_kasir_search");
        }
        // default redirect
        redirect("keuangan/pembelian_lebih_satu_juta/kasir");
    }

    // detail pengajuan
    public function detail($trx_id = ''){
        // set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_lebih_satu_juta/kasir/detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");

        // get data
        $detail = $this->m_kasir->get_trx_advance_by_id(array($this->group_id, $this->flow_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_lebih_satu_juta/kasir");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("result", $detail);
        // get list rincian item pengajuan pembelian
        $this->smarty->assign("rs_id", $this->m_kasir->get_list_rincian_item_pembelian($trx_id));
        // get list advance process
        $this->smarty->assign('rs_process', $this->m_kasir->get_list_advance_process_by_trx_id(array($trx_id,$this->group_id)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pengajuan process
    public function pengajuan_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('process_id', 'ID Tahapan', 'trim|required');
        $this->tnotification->set_rules('advance_total_approved', 'Biaya yang Disetujui', 'trim|required');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');

        // input
        $trx_id = $this->input->post('trx_id',true);
        $process_id = $this->input->post('process_id',true);
        $process_st = $this->input->post('process_st',true);
        // get data process
        $now_flow = $this->m_kasir->get_trx_advance_process_by_id($process_id);
        // check process advance
        if (empty($now_flow)) {
            // no data
            $this->tnotification->set_error_message('Tahapan pengajuan tidak ditemukan!');
        }
        // prosess
        if ($this->tnotification->run() == TRUE) {
            // params
            $params = array(
                'process_st' => $process_st,
                'action_st' => 'done',
                'catatan' => $this->input->post('catatan', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s"),
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'],
                'mdd_finish' => date("Y-m-d H:i:s")
            );
            // where
            $where = array('process_id' => $process_id);
            // update advance process
            if (!$this->m_kasir->update_trx_advance_process($params, $where)) {
                // error update
                $this->tnotification->sent_notification('error', 'Persetujuan pengajuan gagal dilakukan!');
                redirect('keuangan/pembelian_lebih_satu_juta/kasir/detail/' . $trx_id);
            }

            // params
            $params = array(
                'advance_total_approved' => $this->input->post('advance_total_approved',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // where 
            $where = array('trx_id' => $trx_id);
            // update trx advance
            if (!$this->m_kasir->update_trx_advance($params,$where)) {
                // error update
                $this->tnotification->sent_notification('error', 'Persetujuan pengajuan gagal dilakukan!');
                redirect('keuangan/pembelian_lebih_satu_juta/kasir/detail/' . $trx_id);
            };

            // get new process id
            $new_process_id = $this->m_kasir->get_id();
            // check process status
            if ($process_st == 'approve') {
                // get next flow id
                $next_flow = $this->m_kasir->get_next_flow_id_by_trx_id($trx_id);
                // check flow id
                if (empty($next_flow['next_flow_id'])) {
                    // error
                    $this->tnotification->sent_notification('error', 'Tahapan proses selanjutnya tidak ditemukan!');
                    redirect('keuangan/pembelian_lebih_satu_juta/kasir/detail/' . $trx_id);
                }
                // params
                $params = array(
                    'process_id' => $new_process_id,
                    'trx_id' => $trx_id,
                    'flow_id' => $next_flow['next_flow_id'],
                    'process_references_id' => $process_id,
                    'process_st' => 'waiting',
                    'action_st' => 'process',
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // insert trx advance process
                $this->m_kasir->insert_trx_advance_process($params);
            } else {
                // params
                $params = array(
                    'process_id' => $new_process_id,
                    'trx_id' => $trx_id,
                    'flow_revisi_id' => $now_flow['previous_flow_id'],
                    'process_references_id' => $process_id,
                    'process_st' => 'waiting',
                    'action_st' => 'process',
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // insert trx advance process
                $this->m_kasir->insert_trx_advance_process($params);

                // params
                $params = array(
                    'advance_status' => 'draft',
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // where 
                $where = array('trx_id' => $trx_id);
                // update trx advance
                $this->m_kasir->update_trx_advance($params,$where);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Persetujuan pengajuan berhasil diproses");
            redirect('keuangan/pembelian_lebih_satu_juta/kasir');
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Proses persetujuan gagal diproses, silakan ulangi kembali!');
        }
        // default redirect
        redirect('keuangan/pembelian_lebih_satu_juta/kasir/detail/' . $trx_id);
    }

}
