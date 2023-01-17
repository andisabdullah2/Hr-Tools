<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class lpj extends ApplicationBase {

    // set 
    protected $group_id = 23;
    protected $flow_id = 23006;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/pembelian_kurang_satu_juta/m_lpj');
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
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/lpj/index.html");
        // search
        $search = $this->tsession->userdata("pembelian_kurang_satu_juta_lpj_search");
        $this->smarty->assign('search', $search);
        // search parameter
        $uraian = !empty($search['uraian']) ? '%' . $search['uraian'] . '%' : '%';

        /* start of pagination --------------------- */
        // params
        $params = array($this->group_id, $this->flow_id, $uraian);
        // pagination
        $config['base_url'] = site_url("keuangan/pembelian_kurang_satu_juta/lpj/index/");
        $config['total_rows'] = $this->m_lpj->get_total_advance($params);
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
        $this->smarty->assign('rs_id', $this->m_lpj->get_list_advance($params));

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
            $this->tsession->set_userdata("pembelian_kurang_satu_juta_lpj_search", $params);
        } else{
            // unset session search
            $this->tsession->unset_userdata("pembelian_kurang_satu_juta_lpj_search");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/lpj");
    }

    // detail pengajuan
    public function detail($trx_id = ''){
        // set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/lpj/detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");

        // get data
        $detail = $this->m_lpj->get_trx_advance_by_id(array($this->group_id, $this->flow_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/lpj");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rincian item pengajuan pembelian
        $this->smarty->assign("rs_id", $this->m_lpj->get_list_rincian_item_pembelian($trx_id));
        // get list lpj
        $this->smarty->assign("rs_lpj", $this->m_lpj->get_list_lpj_by_trx_id($trx_id));
        // get list advance process
        $this->smarty->assign('rs_process', $this->m_lpj->get_list_advance_process_by_trx_id(array($trx_id,$this->group_id)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pengajuan process
    public function pengajuan_process($trx_id = ''){
        // set page rule
        $this->_set_page_rule("U");

        // get data
        $detail = $this->m_lpj->get_trx_advance_by_id(array($this->group_id, $this->flow_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/lpj");
        }
        // check data lpj
        if (empty($this->m_lpj->get_list_lpj_by_trx_id($trx_id))) {
            // default error
            $this->tnotification->sent_notification('error', 'Daftar LPJ masih kosong, silakan tambahkan LPH terlebih dahulu!');
            redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
        }
        
        // params process previous
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
        // where
        $where = array('process_id' => $detail['process_id']);
        // update advance process
        if (!$this->m_lpj->update_trx_advance_process($params, $where)) {
            // error update
            $this->tnotification->sent_notification('error', 'LPJ gagal dilakukan!');
            redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
        }

        // get next flow id
        $next_flow = $this->m_lpj->get_next_flow_id_by_trx_id($trx_id);
        // check flow id
        if (empty($next_flow['next_flow_id'])) {
            // error
            $this->tnotification->sent_notification('error', 'Tahapan proses selanjutnya tidak ditemukan!');
            redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
        }
        // params
        $params = array(
            'process_id' => $this->m_lpj->get_id(),
            'trx_id' => $trx_id,
            'flow_id' => $next_flow['next_flow_id'],
            'process_references_id' => $detail['process_id'],
            'process_st' => 'waiting',
            'action_st' => 'process',
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s")
        );
        // insert trx advance process
        if (!$this->m_lpj->insert_trx_advance_process($params)) {
            // error insert
            $this->tnotification->sent_notification('error', 'Tahapan proses selanjutnya gagal disimpan!');
            redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
        }

        // success
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "LPJ berhasil diproses");
        redirect('keuangan/pembelian_kurang_satu_juta/lpj');
    }

    // lpj add process
    public function lpj_add_process() {
        // set rules
        $this->_set_page_rule("C");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('process_id', 'ID Tahapan Pengajuan', 'trim|required');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('debit', 'Penerimaan', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Pengeluaran', 'trim');

        // input
        $trx_id = $this->input->post('trx_id', TRUE);
        $process_id = $this->input->post('process_id', TRUE);
        // get data process
        $now_flow = $this->m_lpj->get_trx_advance_process_by_id($process_id);
        // check data process
        if (empty($now_flow)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/lpj");
        }

        // process
        if ($this->tnotification->run() == TRUE) {
            //params
            $params = array(
                'lpj_id' => $this->m_lpj->get_id(),
                'trx_id' => $trx_id,
                'tanggal' => $this->input->post('tanggal', true),
                'uraian' => $this->input->post('uraian', true),
                'debit' => $this->input->post('debit', true),
                'kredit' => $this->input->post('kredit', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if (!$this->m_lpj->insert_trx_advance_lpj($params)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data gagal diproses!");
                redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data LPJ berhasil disimpan");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data LPJ gagal diproses, silakan periksa kembali!");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/lpj/detail/" . $trx_id);
    }

    // lpj edit process
    public function lpj_edit_process() {
        // set rules
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('process_id', 'ID Tahapan Pengajuan', 'trim|required');
        $this->tnotification->set_rules('lpj_id', 'ID LPJ', 'trim|required');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('debit', 'Penerimaan', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Pengeluaran', 'trim');

        // input
        $trx_id = $this->input->post('trx_id', TRUE);
        $process_id = $this->input->post('process_id', TRUE);
        // get data process
        $now_flow = $this->m_lpj->get_trx_advance_process_by_id($process_id);
        // check data process
        if (empty($now_flow)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/lpj");
        }

        // process
        if ($this->tnotification->run() == TRUE) {
            //params
            $params = array(
                'tanggal' => $this->input->post('tanggal', true),
                'uraian' => $this->input->post('uraian', true),
                'debit' => $this->input->post('debit', true),
                'kredit' => $this->input->post('kredit', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // where
            $where = array(
                'lpj_id' => $this->input->post('lpj_id',true),
                'trx_id' => $trx_id,
            );
            // update
            if (!$this->m_lpj->update_trx_advance_lpj($params,$where)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data gagal diproses!");
                redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data LPJ berhasil disimpan");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data LPJ gagal diproses, silakan periksa kembali!");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/lpj/detail/" . $trx_id);
    }

    // lpj delete process
    public function lpj_delete_process(){
        // set page rule
        $this->_set_page_rule("D");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('process_id', 'ID Tahapan Pengajuan', 'trim|required');
        $this->tnotification->set_rules('lpj_id', 'ID LPJ', 'trim|required');

        // input
        $trx_id = $this->input->post('trx_id', TRUE);
        $process_id = $this->input->post('process_id', TRUE);
        // get data process
        $now_flow = $this->m_lpj->get_trx_advance_process_by_id($process_id);
        // check data process
        if (empty($now_flow)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/lpj");
        }

        // process
        if ($this->tnotification->run() == TRUE) {
            // where
            $where = array(
                'lpj_id' => $this->input->post('lpj_id',true),
                'trx_id' => $trx_id
            );
            // delete
            if (!$this->m_lpj->delete_trx_advance_lpj($where)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data LPJ diproses!");
                redirect('keuangan/pembelian_kurang_satu_juta/lpj/detail/' . $trx_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data LPJ berhasil dihapus!");
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data LPJ gagal dihapus!');
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/lpj/detail/" . $trx_id);
    }

}
