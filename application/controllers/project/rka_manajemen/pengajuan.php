<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class pengajuan extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/rka_manajemen/m_pengajuan');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_rka_manajemen_pengajuan') ? $this->tsession->userdata('search_rka_manajemen_pengajuan') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $project_alias = empty($search['project_alias']) ? '%' : '%' . $search['project_alias'] . '%';
        $plan_status = empty($search['plan_status']) ? '%' : $search['plan_status'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_manajemen/pengajuan/index/");
        $config['total_rows'] = $this->m_pengajuan->get_total_pengajuan_rka_manajemen(array($project_alias, $plan_status));
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
        $params = array($project_alias, $plan_status, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_pengajuan_rka_manajemen($params));
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
                "project_alias" => $this->input->post('project_alias', TRUE),
                "plan_status" => $this->input->post('plan_status', TRUE)
            );
            // set session
            $this->tsession->set_userdata("search_rka_manajemen_pengajuan", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_rka_manajemen_pengajuan");
        }
        // redirect
        redirect("project/rka_manajemen/pengajuan");
    }

    // add
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/add.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $this->smarty->assign("rs_projek", $this->m_pengajuan->get_list_projek());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('project_id', 'Nama Projek', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('nilai_pendapatan', 'Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', 'Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //
            $prefix = date('Ym');
            $plan_id = $this->m_pengajuan->get_plan_id($prefix, $prefix . "%");
            // insert process
            $params = array(
                'plan_id' => $plan_id,
                'project_id' => $this->input->post('project_id', TRUE),
                'nilai_pendapatan' => $this->input->post('nilai_pendapatan', TRUE),
                'nilai_pajak' => $this->input->post('nilai_pajak', TRUE),
                'nilai_anggaran' => $this->input->post('nilai_anggaran', TRUE),
                'catatan' => $this->input->post('catatan', TRUE),
                'create_by' => $this->com_user['user_id'],
                'create_by_name' => $this->com_user['user_alias'],
                'create_date' => date("Y-m-d H:i:s"),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ( $this->m_pengajuan->insert_projects_budget_plan($params) ) {
                // flow 1
                $flow = $this->m_pengajuan->get_flow_by_params(array(15, 1));
                if ($flow) {
                    $process_id = $this->m_pengajuan->get_process_id();
                    $params = array(
                        'process_id' => $process_id,
                        'plan_id' => $plan_id,
                        'flow_id' => $flow['flow_id'],
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $this->m_pengajuan->insert_projects_budget_process($params);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/rka_manajemen/pengajuan/item/".$plan_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/rka_manajemen/pengajuan/add");
    }

    // edit
    public function edit($plan_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/edit.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_projek", $this->m_pengajuan->get_list_projek());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('project_id', 'Nama Projek', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('nilai_pendapatan', 'Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', 'Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');
        //
        $plan_id = $this->input->post('plan_id', TRUE);
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // update process
            $params = array(
                'project_id' => $this->input->post('project_id', TRUE),
                'nilai_pendapatan' => $this->input->post('nilai_pendapatan', TRUE),
                'nilai_pajak' => $this->input->post('nilai_pajak', TRUE),
                'nilai_anggaran' => $this->input->post('nilai_anggaran', TRUE),
                'catatan' => $this->input->post('catatan', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'plan_id' => $plan_id );
            // update
            if ( $this->m_pengajuan->update_projects_budget_plan($params, $where) ) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/rka_manajemen/pengajuan/item/".$plan_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/rka_manajemen/pengajuan/edit/".$plan_id);
    }

    // delete
    public function delete($plan_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/delete.html");
        //
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required|max_length[20]');
        //
        $plan_id = $this->input->post('plan_id', TRUE);
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // delete process
            $where = array( 'plan_id' => $plan_id );
            // delete
            if ( $this->m_pengajuan->delete_projects_budget_plan($where) ) {
                //
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
        redirect("project/rka_manajemen/pengajuan");
    }

    // item
    public function item($plan_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/item.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_group", $this->m_pengajuan->get_list_group());
        $this->smarty->assign("rs_perusahaan", $this->m_pengajuan->get_list_perusahaan());
        $this->smarty->assign("no_urut", $this->m_pengajuan->get_last_no($plan_id));
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_item($plan_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // item_add_process
    public function item_add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('plan_id', 'ID Pengajuan', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('group_id', 'Grup Item Pengajuan', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('perusahaan_id', 'Nama Perusahaan', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('kode_akun', 'Akun Perusahaan', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('item_no', 'Nomor', 'trim|required|numeric');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_volume', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        //
        $plan_id = $this->input->post('plan_id', TRUE);
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //
            $item_id = $this->m_pengajuan->get_item_id($plan_id, $plan_id . "%");
            // insert process
            $params = array(
                'item_id' => $item_id,
                'plan_id' => $plan_id,
                'group_id' => $this->input->post('group_id', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'perusahaan_id' => $this->input->post('perusahaan_id', TRUE),
                'item_no' => $this->input->post('item_no', TRUE),
                'item_uraian' => $this->input->post('item_uraian', TRUE),
                'item_volume' => $this->input->post('item_volume', TRUE),
                'item_satuan' => $this->input->post('item_satuan', TRUE),
                'item_harga' => $this->input->post('item_harga', TRUE),
                'item_total' => $this->input->post('item_volume', TRUE) * $this->input->post('item_harga', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ( $this->m_pengajuan->insert_projects_budget_item($params) ) {
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
        redirect("project/rka_manajemen/pengajuan/item/".$plan_id);
    }

    // item_edit_process
    public function item_edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('group_id', 'Grup Item Pengajuan', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('perusahaan_id', 'Nama Perusahaan', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('kode_akun', 'Akun Perusahaan', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('item_no', 'Nomor', 'trim|required|numeric');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_volume', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        //
        $item_id = $this->input->post('item_id', TRUE);
        $detail = $this->m_pengajuan->get_item_by_id($item_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // update process
            $params = array(
                'group_id' => $this->input->post('group_id', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'perusahaan_id' => $this->input->post('perusahaan_id', TRUE),
                'item_no' => $this->input->post('item_no', TRUE),
                'item_uraian' => $this->input->post('item_uraian', TRUE),
                'item_volume' => $this->input->post('item_volume', TRUE),
                'item_satuan' => $this->input->post('item_satuan', TRUE),
                'item_harga' => $this->input->post('item_harga', TRUE),
                'item_total' => $this->input->post('item_volume', TRUE) * $this->input->post('item_harga', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'item_id' => $item_id );
            // update
            if ( $this->m_pengajuan->update_projects_budget_item($params, $where) ) {
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
        redirect("project/rka_manajemen/pengajuan/item/".$detail['plan_id']);
    }

    // item_delete_process
    public function item_delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required|max_length[20]');
        //
        $item_id = $this->input->post('item_id', TRUE);
        $detail = $this->m_pengajuan->get_item_by_id($item_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //
            $where = array( 'item_id' => $item_id );
            // update
            if ( $this->m_pengajuan->delete_projects_budget_item($where) ) {
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
        redirect("project/rka_manajemen/pengajuan/item/".$detail['plan_id']);
    }

    // detail
    public function detail($plan_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/pengajuan/detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_list_item_and_detail($plan_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // detail_add_process
    public function detail_add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('detail_no', 'Nomor', 'trim|required|numeric');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('detail_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        //
        $item_id = $this->input->post('item_id', TRUE);
        $detail = $this->m_pengajuan->get_item_by_id($item_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //
            $detail_id = $this->m_pengajuan->get_detail_id($item_id, $item_id . "%");
            // insert process
            $params = array(
                'detail_id' => $detail_id,
                'item_id' => $item_id,
                'detail_no' => $this->input->post('detail_no', TRUE),
                'detail_uraian' => $this->input->post('detail_uraian', TRUE),
                'detail_volume' => $this->input->post('detail_volume', TRUE),
                'detail_satuan' => $this->input->post('detail_satuan', TRUE),
                'detail_harga' => $this->input->post('detail_harga', TRUE),
                'detail_sub_total' => $this->input->post('detail_volume', TRUE) * $this->input->post('detail_harga', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ( $this->m_pengajuan->insert_projects_budget_detail($params) ) {
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
        redirect("project/rka_manajemen/pengajuan/detail/".$detail['plan_id']);
    }

    // detail_edit_process
    public function detail_edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('detail_id', 'ID Detail', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('detail_no', 'Nomor', 'trim|required|numeric');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('detail_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|numeric');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        //
        $detail_id = $this->input->post('detail_id', TRUE);
        $detail = $this->m_pengajuan->get_detail_by_id($detail_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // update process
            $params = array(
                'detail_no' => $this->input->post('detail_no', TRUE),
                'detail_uraian' => $this->input->post('detail_uraian', TRUE),
                'detail_volume' => $this->input->post('detail_volume', TRUE),
                'detail_satuan' => $this->input->post('detail_satuan', TRUE),
                'detail_harga' => $this->input->post('detail_harga', TRUE),
                'detail_sub_total' => $this->input->post('detail_volume', TRUE) * $this->input->post('detail_harga', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array( 'detail_id' => $detail_id );
            // update
            if ( $this->m_pengajuan->update_projects_budget_detail($params, $where) ) {
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
        redirect("project/rka_manajemen/pengajuan/detail/".$detail['plan_id']);
    }

    // detail_delete_process
    public function detail_delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('detail_id', 'ID Detail', 'trim|required|max_length[20]');
        //
        $detail_id = $this->input->post('detail_id', TRUE);
        $detail = $this->m_pengajuan->get_detail_by_id($detail_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // delete process
            $where = array( 'detail_id' => $detail_id );
            // delete
            if ( $this->m_pengajuan->delete_projects_budget_detail($where) ) {
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
        redirect("project/rka_manajemen/pengajuan/detail/".$detail['plan_id']);
    }

    // verifikasi_process
    public function verifikasi_process($item_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        $detail = $this->m_pengajuan->get_item_by_id($item_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        $this->m_pengajuan->update_item_total($item_id);
        // notification
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "Verifikasi item " . $detail['nama_akun'] . " ( " . $detail['perusahaan_nama']. "  ) " . "berhasil");
        // default redirect
        redirect("project/rka_manajemen/pengajuan/detail/".$detail['plan_id']);
    }

    // pengajuan_process
    public function pengajuan_process($plan_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        //
        $detail = $this->m_pengajuan->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/pengajuan");
        }
        // cek flow terakhir, jika ada flow yg revisi lanjutkan dari flow revisi, jika tidak buat flow ke 2
        $last_flow = $this->m_pengajuan->get_last_process_flow($plan_id);
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
        $this->m_pengajuan->update_projects_budget_process($params, $where); 
        //
        if ( $last_flow['flow_revisi_id'] ) {
            $process_id = $this->m_pengajuan->get_process_id();
            $params = array(
                'process_id' => $process_id,
                'plan_id' => $plan_id,
                'flow_id' => $last_flow['flow_revisi_id'],
                'process_references_id' => $last_flow['process_id'],
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $this->m_pengajuan->insert_projects_budget_process($params);
        } else {
            // flow 2
            $flow_2 = $this->m_pengajuan->get_flow_by_params(array(15, 2));
            if ( $flow_2 ) {
                $process_id = $this->m_pengajuan->get_process_id();
                $params = array(
                    'process_id' => $process_id,
                    'plan_id' => $plan_id,
                    'flow_id' => $flow_2['flow_id'],
                    'process_references_id' => $last_flow['process_id'],
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                $this->m_pengajuan->insert_projects_budget_process($params); 
            }
        }
        // ubah status plan ke process
        $params = array(
            'send_status' => 'process',
            'plan_status' => 'waiting',
            'send_by' => $this->com_user['user_id'],
            'send_by_name' => $this->com_user['user_alias'],
            'send_date' => date("Y-m-d H:i:s"),
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s")
        );
        $where = array( 'plan_id' => $plan_id );
        //
        if ( $this->m_pengajuan->update_projects_budget_plan($params, $where) ) {
            //
            $this->m_pengajuan->update_nilai_biaya($plan_id);
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
        }
        // default redirect
        redirect("project/rka_manajemen/pengajuan");
    }

    /*AJAX*/

    // ajax change perusahaan
    public function ajax_change_perusahaan() {
        // set page rules
        $this->_set_page_rule("R");
        // header
        header('Content-Type: application/json');
        // cek input
        $this->tnotification->set_rules('perusahaan_id', 'Nama Perusahaan', 'trim|required|max_length[5]');
        // variable
        $perusahaan_id = $this->input->post('perusahaan_id', TRUE);
        // process
        if ($this->tnotification->run() !== FALSE) {
            $html = "";
            // get data kabupaten
            $rs_akun = $this->m_pengajuan->get_list_akun_perusahaan($perusahaan_id);
            $html .= '<option value=""></option>';
            foreach ($rs_akun as $akun) {
                $html .= '<option value="' . $akun['kode_akun'] . '">'.str_repeat("&nbsp;&nbsp;", $akun['level_akun']).'[ ' . strtoupper($akun['kode_akun_alias'] . " ] " . $akun['nama_akun']) . '</option>';
            }
            // lanjut
            $result = array(
                'status' => 'success',
                'html' => $html,
            );
        } else {
            // error
            $result = array(
                'status' => 'error',
                'html' => '',
            );
        }
        // encode
        echo json_encode($result);
        exit();
    }

    // ajax change perusahaan edit
    public function ajax_change_perusahaan_edit() {
        // set page rules
        $this->_set_page_rule("R");
        // header
        header('Content-Type: application/json');
        // cek input
        $this->tnotification->set_rules('perusahaan_id', 'Nama Perusahaan', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('kode_akun', 'Akun Perusahaan', 'trim|required|max_length[8]');
        // variable
        $perusahaan_id = $this->input->post('perusahaan_id', TRUE);
        $kode_akun = $this->input->post('kode_akun', TRUE);
        // process
        if ($this->tnotification->run() !== FALSE) {
            $html = "";
            // get data kabupaten
            $rs_akun = $this->m_pengajuan->get_list_akun_perusahaan($perusahaan_id);
            $html .= '<option value=""></option>';
            foreach ($rs_akun as $akun) {
                $html .= '<option value="' . $akun['kode_akun'] . '" '.($akun['kode_akun'] == $kode_akun ? "selected" : "").'>'.str_repeat("&nbsp;&nbsp;", $akun['level_akun']).'[ ' . strtoupper($akun['kode_akun_alias'] . " ] " . $akun['nama_akun']) . '</option>';
            }
            // lanjut
            $result = array(
                'status' => 'success',
                'html' => $html,
            );
        } else {
            // error
            $result = array(
                'status' => 'error',
                'html' => '',
            );
        }
        // encode
        echo json_encode($result);
        exit();
    }

}
