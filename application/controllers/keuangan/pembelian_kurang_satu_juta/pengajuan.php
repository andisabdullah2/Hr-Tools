<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class pengajuan extends ApplicationBase {

    // set group id
    protected $group_id = 23;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/pembelian_kurang_satu_juta/m_pengajuan');
        // load library
        $this->load->library('tnotification');

        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // load library
        $this->load->library('pagination');
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/pengajuan/index.html");
        // search
        $search = $this->tsession->userdata("pembelian_kurang_satu_juta_search");
        $this->smarty->assign('search', $search);
        // search parameter
        $uraian = !empty($search['uraian']) ? '%' . $search['uraian'] . '%' : '%';

        // get data pegawai
        $pegawai = $this->m_pengajuan->get_pegawai_by_user_id($this->com_user['user_id']);
        /* start of pagination --------------------- */
        // params
        $params = array($this->group_id,$pegawai['struktur_cd'],$uraian);
        // pagination
        $config['base_url'] = site_url("keuangan/pembelian_kurang_satu_juta/pengajuan/index/");
        $config['total_rows'] = $this->m_pengajuan->get_total_advance($params);
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
        $params = array($this->group_id, $pegawai['struktur_cd'], $uraian, ($start - 1), $config['per_page']);
        $this->smarty->assign('rs_id', $this->m_pengajuan->get_list_advance($params));
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
            $this->tsession->set_userdata("pembelian_kurang_satu_juta_search", $params);
        } else{
            // unset session search
            $this->tsession->unset_userdata("pembelian_kurang_satu_juta_search");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
    }

    // add
    public function add(){
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/pengajuan/add.html");
        // get last advance nomor
        $this->smarty->assign("advance_no", $this->m_pengajuan->get_last_advance_no($this->group_id));
        // get data rencana item
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_rencana_item());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process(){
        // set page rule
        $this->_set_page_rule("C");
        // validasi
        $this->tnotification->set_rules('advance_no', 'Nomor Pengajuan', 'trim|required|numeric');
        $this->tnotification->set_rules('advance_tanggal', 'Tanggal Pengajuan', 'trim|required');
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim|required');
        $this->tnotification->set_rules('advance_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('advance_total_requested', 'Biaya Diajukan', 'trim|required');

        // get data pegawai
        $pegawai = $this->m_pengajuan->get_pegawai_by_user_id($this->com_user['user_id']);
        // check data
        if (empty($pegawai)) {
            // error
            $this->tnotification->set_error_message('Pegawai tidak ditemukan!');
        }
        // process
        if ($this->tnotification->run() == TRUE) {
            // get trx id
            $trx_id = $this->m_pengajuan->get_trx_id(date('ymd'));
            // input
            $advance_tanggal = $this->input->post('advance_tanggal',true);
            $advance = explode('-', $advance_tanggal);
            // params
            $params = array(
                'trx_id' => $trx_id,
                'group_id' => $this->group_id,
                'kode_item' => $this->input->post('kode_item',true),
                'struktur_cd' => $pegawai['struktur_cd'],
                'advance_no' => $this->input->post('advance_no',true),
                'advance_tanggal' => $advance_tanggal,
                'advance_bulan' => $advance[1],
                'advance_tahun' => $advance[0],
                'advance_uraian' => $this->input->post('advance_uraian',true),
                'advance_total_requested' => $this->input->post('advance_total_requested',true),
                'advance_status' => 'draft',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if (!$this->m_pengajuan->insert_trx_advance($params)) {
                // error insert
                $this->tnotification->sent_notification('error', 'Data pengajuan gagal disimpan');
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/add');
            }

            // get flow first
            $flow_id = $this->m_pengajuan->get_first_flow_by_group_id($this->group_id);
            // params
            $params = array(
                'process_id' => $this->m_pengajuan->get_id(),
                'trx_id' => $trx_id,
                'flow_id' => $flow_id,
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // inert trx advance process
            $this->m_pengajuan->insert_trx_advance_process($params);

            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data pengajuan berhasil disimpan");
            // redirect
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data pengajuan gagal diproses, periksa kembali data yang masukan!');
        }
        // default 
        redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/add');
    }

    // edit
    public function edit($trx_id = ''){
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/pengajuan/edit.html");
        // get data
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("result", $detail);
        // get data rencana item
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_rencana_item());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('advance_tanggal', 'Tanggal Pengajuan', 'trim|required');
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim|required');
        $this->tnotification->set_rules('advance_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('advance_total_requested', 'Biaya Diajukan', 'trim|required');

        // input
        $trx_id = $this->input->post('trx_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // input
            $advance_tanggal = $this->input->post('advance_tanggal',true);
            $advance = explode('-', $advance_tanggal);
            // params
            $params = array(
                'kode_item' => $this->input->post('kode_item',true),
                'advance_no' => $this->input->post('advance_no',true),
                'advance_tanggal' => $advance_tanggal,
                'advance_bulan' => $advance[1],
                'advance_tahun' => $advance[0],
                'advance_uraian' => $this->input->post('advance_uraian',true),
                'advance_total_requested' => $this->input->post('advance_total_requested',true),
                'advance_status' => 'draft',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // where
            $where = array('trx_id' => $trx_id);
            // update
            if (!$this->m_pengajuan->update_trx_advance($params,$where)) {
                // error update
                $this->tnotification->sent_notification('error', 'Data pengajuan gagal disimpan');
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/edit/' . $trx_id);
            }

            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data pengajuan berhasil disimpan");
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data pengajuan gagal diproses, periksa kembali data yang masukan!');
        }
        // default 
        redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/edit/' . $trx_id);
    }

    // delete
    public function delete($trx_id="") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/pengajuan/delete.html");
        // get data
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rincian item pengajuan pembelian
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_rincian_item_pembelian($trx_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process(){
        // set page rules
        $this->_set_page_rule("D");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');

        // input
        $trx_id = $this->input->post('trx_id',true);
        // prosess
        if ($this->tnotification->run() == TRUE) {
            // where
            $where = array('trx_id' => $trx_id);
            // hapus
            if (!$this->m_pengajuan->delete_trx_advance($where)) {
                // error delete
                $this->tnotification->sent_notification('error', 'Data pengajuan gagal dihapus!');
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/delete/' . $trx_id);        
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data pengajuan berhasil dihapus!");
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan');
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data pengajuan gagal dihapus!');
        }
        // default redirect
        redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/delete/' . $trx_id);
    }

    // item
    public function item($trx_id="") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/pembelian_kurang_satu_juta/pengajuan/item.html");
        // get data
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rincian item pengajuan pembelian
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_rincian_item_pembelian($trx_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // item add process
    public function item_add_process(){
        // set page rule
        $this->_set_page_rule("C");
        // validasi
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('item_uraian', 'Nama Barang', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_satuan', 'Spesifikasi Barang', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_jumlah', 'Jumlah', 'trim|required|numeric|max_length[11]');
        $this->tnotification->set_rules('item_total', 'Harga', 'trim|required');
        
        // input
        $trx_id = $this->input->post('trx_id', TRUE);
        // get data pengajuan
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // chek data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // process
        if ($this->tnotification->run() == TRUE) {
            // get data id
            $data_id = $this->m_pengajuan->get_data_id($trx_id);
            //params
            $params = array(
                'data_id' => $data_id,
                'trx_id' => $trx_id,
                'item_uraian' => $this->input->post('item_uraian', true),
                'item_jumlah' => $this->input->post('item_jumlah', true),
                'item_satuan' => $this->input->post('item_satuan', true),
                'item_total' => $this->input->post('item_total', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if (!$this->m_pengajuan->insert_trx_advance_pembelian($params)) {
                // default insert
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            // default redirect
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data item pengajuan gagal diproses, periksa kembali data yang dimasukan!");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/pengajuan/item/ " . $trx_id);
    }

    // item edit process
    public function item_edit_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('data_id', 'ID Item', 'trim|required');
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');
        $this->tnotification->set_rules('item_uraian', 'Nama Barang', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_satuan', 'Spesifikasi Barang', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_jumlah', 'Jumlah', 'trim|required|numeric|max_length[11]');
        $this->tnotification->set_rules('item_total', 'Harga', 'trim|required');
        
        // input
        $trx_id = $this->input->post('trx_id', TRUE);
        $data_id = $this->input->post('data_id', TRUE);
        // get data pengajuan
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // chek data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // process
        if ($this->tnotification->run() == TRUE) {
            //params
            $params = array(
                'item_uraian' => $this->input->post('item_uraian', true),
                'item_jumlah' => $this->input->post('item_jumlah', true),
                'item_satuan' => $this->input->post('item_satuan', true),
                'item_total' => $this->input->post('item_total', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // where
            $where = array(
                'data_id' => $data_id,
                'trx_id' => $trx_id
            );
            // update
            if (!$this->m_pengajuan->update_trx_advance_pembelian($params,$where)) {
                // default insert
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            // default redirect
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data item pengajuan gagal diproses, periksa kembali data yang dimasukan!");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/pengajuan/item/ " . $trx_id);
    }

    // item delete process
    public function item_delete_process(){
        // set page rule
        $this->_set_page_rule("D");
        // validasi
        $this->tnotification->set_rules('data_id', 'ID Item', 'trim|required');
        $this->tnotification->set_rules('trx_id', 'ID Pengajuan', 'trim|required');

        // input
        $data_id = $this->input->post('data_id', TRUE);
        $trx_id = $this->input->post('trx_id', TRUE);
        // get data rincian by id
        $detail = $this->m_pengajuan->get_data_rincian_item_pembelian_by_id($data_id);
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // process
        if ($this->tnotification->run() == TRUE) {
            // where
            $where = array(
                'data_id' => $data_id,
                'trx_id' => $trx_id
            );
            // delete
            if (!$this->m_pengajuan->delete_trx_advance_pembelian($where)) {
                // error delete
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
                // redirect
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);    
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            // redirect
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus!");
        }
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/pengajuan/item/" . $trx_id);
    }

    // pengajuan process
    public function pengajuan_process($trx_id = ''){
        // set page rule
        $this->_set_page_rule("U");

        // get data
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id,$trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }

        // check data advance pembelian
        if (empty($this->m_pengajuan->get_list_rincian_item_pembelian($trx_id))) {
            // no data
            $this->tnotification->sent_notification('error', 'Data rincian pengajuan masih kosong, tambahkan rincian pengajuan untuk dapat memproses pengajuan ini!');
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        }
        // cek flow terakhir, jika ada flow yg revisi lanjutkan dari flow revisi, jika tidak buat flow ke selanjutnya
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
        // where
        $where = array('process_id' => $last_flow['process_id']);
        // update process last flow
        $this->m_pengajuan->update_trx_advance_process($params, $where); 

        // get process id
        $process_id = $this->m_pengajuan->get_id();
        // check revisi id
        if (!empty($last_flow['flow_revisi_id'])) {
            // ada revisi
            // params
            $params = array(
                'process_id' => $process_id,
                'trx_id' => $trx_id,
                'flow_id' => $last_flow['next_flow_id'],
                'process_references_id' => $last_flow['process_id'],
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert advance process
            $this->m_pengajuan->insert_trx_advance_process($params);
        } else {
            // no revisi, lanjutkan ke step berikutnya
            $next_flow = $this->m_pengajuan->get_next_flow_id_by_trx_id($trx_id);
            // check next flow id
            if (empty($next_flow['next_flow_id'])) {
                // error
                $this->tnotification->sent_notification('error', 'Tahapan proses selanjutnya tidak ditemukan!');
                redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
            }
            // params
            $params = array(
                'process_id' => $process_id,
                'trx_id' => $trx_id,
                'flow_id' => $next_flow['next_flow_id'],
                'process_references_id' => $last_flow['process_id'],
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert 
            $this->m_pengajuan->insert_trx_advance_process($params); 
        }

        // params trx advance setelah dikirim
        $params = array(
            'advance_status' => 'waiting',
            'send_by' => $this->com_user['user_id'],
            'send_by_name' => $this->com_user['user_alias'],
            'send_date' => date("Y-m-d H:i:s"),
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s")
        );
        // where
        $where = array('trx_id' => $trx_id);
        // update trx advance
        if (!$this->m_pengajuan->update_trx_advance($params, $where) ) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
            redirect('keuangan/pembelian_kurang_satu_juta/pengajuan/item/' . $trx_id);
        }
        // notification
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
        // default redirect
        redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
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
        $detail = $this->m_pengajuan->get_trx_advance_by_id(array($this->group_id, $trx_id));
        // check data
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("keuangan/pembelian_kurang_satu_juta/pengajuan");
        }
        // get list rincian item pengajuan pembelian
        $rs_id =  $this->m_pengajuan->get_list_rincian_item_pembelian($trx_id);

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
