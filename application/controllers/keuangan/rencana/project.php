<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class project extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('keuangan/rencana/m_project');
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
        $this->smarty->assign("template_content", "keuangan/rencana/project/index.html");
        // search
        $search = $this->tsession->userdata("finance_project_search");
        // search parameter
        $tahun = empty($search['tahun']) ? '%' : $search['tahun'];
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        // assign search
        $this->smarty->assign("search", $search);

        /* start of pagination --------------------- */
        // params
        $params = array($tahun, $project, $struktur_cd);
        // pagination
        $config['base_url'] = site_url("keuangan/rencana/project/index/");
        $config['total_rows'] = $this->m_project->get_total_plan_project($params);
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
        $params = array($tahun, $project, $struktur_cd, ($start - 1), $config['per_page']);
        // get data 
        $this->smarty->assign("rs_id", $this->m_project->get_list_plan_project($params));
        // get all tahun
        $this->smarty->assign("rs_tahun", $this->m_project->get_all_tahun_project());
        // get all unit kerja
        $this->smarty->assign("rs_unit_kerja", $this->m_project->get_list_unit_kerja());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process() {
        //set page rules
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Reset") {
            // unset session search
            $this->tsession->unset_userdata("finance_project_search");
        } else {
            // params
            $params = array(
                "tahun" => $this->input->post("tahun", true),
                "project" => $this->input->post("project", true),
                "struktur_cd" => $this->input->post("struktur_cd", true)
            );
            // set session search
            $this->tsession->set_userdata("finance_project_search", $params);
        }
        // default redirect
        redirect("keuangan/rencana/project");
    }

    // add plan
    public function add(){
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/add.html");
        // get all project rka project
        $this->smarty->assign("rs_projects", $this->m_project->get_all_project_rka_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process plan
    public function add_process(){
        // set page rule
        $this->_set_page_rule("C");
        // validasi
        $this->tnotification->set_rules('project_id', 'Project', 'required');
        $this->tnotification->set_rules('nilai_pendapatan', 'Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', 'Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');

        // process
        if ($this->tnotification->run() == TRUE) {
            // get last plan id
            $plan_id = $this->m_project->get_last_plan_id(date('Ym'));
            // params
            $params = array(
                'plan_id' => $plan_id,
                'project_id' => $this->input->post('project_id',true),
                'nilai_pendapatan' => str_replace('.00', '', $this->input->post('nilai_pendapatan',true)),
                'nilai_pajak' => str_replace('.00', '', $this->input->post('nilai_pajak',true)),
                'nilai_anggaran' => str_replace('.00', '', $this->input->post('nilai_anggaran',true)),
                'nilai_biaya' => '0',
                'catatan' => $this->input->post('catatan',true),
                'send_status' => 'draft',
                'plan_status' => 'approved',
                'create_by' => $this->com_user['user_id'],
                'create_by_name' => $this->com_user['user_alias'],
                'create_date' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // insert
            if (!$this->m_project->insert_projects_budget_plan($params)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data rencana gagal diproses!");
                redirect("keuangan/rencana/project/add");
            }
            // process
            $params = array(
                'process_id' => $this->m_project->get_id(),
                'plan_id' => $plan_id,
                'flow_id' => $this->m_project->get_first_flow_by_group_id('15'),
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // insert process
            $this->m_project->insert_projects_budget_process($params);
            // success insert
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/item/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect("keuangan/rencana/project/add");
    }

    // edit plan
    public function edit($plan_id = ""){
        // set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/edit.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("result", $detail);
        $this->smarty->assign("detail", $detail);
        // get all project rka project
        $this->smarty->assign("rs_projects", $this->m_project->get_all_project_rka_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process plan
    public function edit_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'required');
        $this->tnotification->set_rules('nilai_pendapatan', 'Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', 'Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');

        // input
        $plan_id = $this->input->post('plan_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // params
            $params = array(
                'project_id' => $this->input->post('project_id',true),
                'nilai_pendapatan' => str_replace('.00', '', $this->input->post('nilai_pendapatan',true)),
                'nilai_pajak' => str_replace('.00', '', $this->input->post('nilai_pajak',true)),
                'nilai_anggaran' => str_replace('.00', '', $this->input->post('nilai_anggaran',true)),
                'nilai_biaya' => '0',
                'catatan' => $this->input->post('catatan',true),
                'send_status' => 'draft',
                'plan_status' => 'approved',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where
            $where = array('plan_id' => $plan_id);
            // update
            if (!$this->m_project->update_projects_budget_plan($params,$where)) {
                // error update
                $this->tnotification->sent_notification("error", "Data rencana gagal diproses!");
                redirect("keuangan/rencana/project/edit/" . $plan_id);
            }
            // success update
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/item/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana gagal diproses, silakan koreksi data yang dimasukan kembali!");
        }
        // default redirect
        redirect("keuangan/rencana/project/edit/" . $plan_id);
    }

    // delete plan
    public function delete($plan_id = ''){
        // set page rule
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/delete.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete plan process
    public function delete_process(){
        // set page rule
        $this->_set_page_rule("D");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // where
            $where = array('plan_id' => $plan_id);
            // delete
            if (!$this->m_project->delete_projects_budget_plan($where)) {
                // error delete
                $this->tnotification->sent_notification('error', 'Data pengajuan RKA project gagal dihapus!');
                redirect('keuangan/rencana/project/delete/' . $plan_id);
            }
            // success delete
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data pengajuan RKA project berhasil dihapus!");
            // redirect
            redirect("keuangan/rencana/project");
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data pengajuan RKA project gagal dihapus!');
        }
        // default redirect
        redirect('keuangan/rencana/project/delete/' . $plan_id);
    }

    // review
    public function review($plan_id = ""){
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/review.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rencana item
        $this->smarty->assign("rs_item", $this->m_project->get_list_rencana_item_by_plan_id(array($plan_id)));
        // get list detail
        $this->smarty->assign("rs_detail", $this->m_project->get_list_item_and_detail_by_plan_id($plan_id));
        // get list plan process
        $this->smarty->assign("rs_process", $this->m_project->get_list_plan_process_by_plan_id($plan_id));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // rencana item
    public function item($plan_id = ""){
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/item.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // list perusahaan
        $this->smarty->assign("rs_perusahaan", $this->m_project->get_list_perusahaan());
        // list group project
        $this->smarty->assign("rs_project_group", $this->m_project->get_list_project_group());
        // get list rencana item
        $this->smarty->assign("rs_id", $this->m_project->get_list_rencana_item_by_plan_id(array($plan_id)));
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
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('group_id', 'Group Item', 'required');
        $this->tnotification->set_rules('kode_akun', 'Akun', 'required');
        $this->tnotification->set_rules('perusahaan_id', 'Perusahaan', 'required');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_volume', 'Jumlah', 'trim|required|max_length[11]|integer');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        $this->tnotification->set_rules('item_total', 'Total', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // get item id
            $item_id = $this->m_project->get_last_item_id($plan_id);
            // get item no
            $item_no = $this->m_project->get_item_no_rencana_item_by_plan_id($plan_id);
            // input
            $item_total = $this->input->post('item_total',true);
            // params
            $params = array(
                'item_id' => $item_id,
                'plan_id' => $plan_id,
                'group_id' => $this->input->post('group_id',true),
                'kode_akun' => $this->input->post('kode_akun',true),
                'perusahaan_id' => $this->input->post('perusahaan_id',true),
                'item_no' => $item_no,
                'item_uraian' => $this->input->post('item_uraian',true),
                'item_volume' => $this->input->post('item_volume',true),
                'item_satuan' => $this->input->post('item_satuan',true),
                'item_harga' => $this->input->post('item_harga',true),
                'item_total' => $item_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // insert
            if (!$this->m_project->insert_projects_budget_item($params)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data rencana item gagal diproses!");
                redirect("keuangan/rencana/project/item/" . $plan_id);
            }
            // get data budget plan
            $detail = $this->m_project->get_data_plan_by_id($plan_id);
            $nilai_biaya = $detail['nilai_biaya'] + $item_total;
            // params
            $params = array(
                'nilai_biaya' => $nilai_biaya,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where
            $where = array('plan_id' => $plan_id);
            // update budget plan
            $this->m_project->update_projects_budget_plan($params,$where);

            // success insert
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana item berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/item/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana item gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect('keuangan/rencana/project/item/' . $plan_id);
    }

    // item edit process
    public function item_edit_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required');
        $this->tnotification->set_rules('group_id', 'Group Item', 'required');
        $this->tnotification->set_rules('kode_akun', 'Akun', 'required');
        $this->tnotification->set_rules('perusahaan_id', 'Perusahaan', 'required');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('item_volume', 'Jumlah', 'trim|required|max_length[11]|integer');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        $this->tnotification->set_rules('item_total', 'Total', 'trim|required');

        // input
        $item_id = $this->input->post('item_id',true);
        $plan_id = $this->input->post('plan_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // input
            $item_total = $this->input->post('item_total',true);
            // params
            $params = array(
                'group_id' => $this->input->post('group_id',true),
                'kode_akun' => $this->input->post('kode_akun',true),
                'perusahaan_id' => $this->input->post('perusahaan_id',true),
                'item_uraian' => $this->input->post('item_uraian',true),
                'item_volume' => $this->input->post('item_volume',true),
                'item_satuan' => $this->input->post('item_satuan',true),
                'item_harga' => $this->input->post('item_harga',true),
                'item_total' => $item_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where
            $where = array(
                'item_id' => $item_id,
                'plan_id' => $plan_id
            );
            // update
            if (!$this->m_project->update_projects_budget_item($params,$where)) {
                // error update
                $this->tnotification->sent_notification("error", "Data rencana item gagal diproses!");
                redirect("keuangan/rencana/project/item/" . $plan_id);
            }
            // get data 
            $detail = $this->m_project->get_data_rencana_item_by_id($item_id);
            $nilai_biaya = $detail['new_nilai_biaya'] + $item_total;
            // params
            $params = array(
                'nilai_biaya' => $nilai_biaya,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where
            $where = array('plan_id' => $plan_id);
            // update budget plan
            $this->m_project->update_projects_budget_plan($params,$where);

            // success insert
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana item berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/item/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana item gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect('keuangan/rencana/project/item/' . $plan_id);
    }

    // item delete process
    public function delete_item(){
        // set page rule
        $this->_set_page_rule("D");
        // validasi 
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        $item_id = $this->input->post('item_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // get data
            $result = $this->m_project->get_data_rencana_item_by_id($item_id);
            // params
            $params_plan = array(
                'nilai_biaya' => $result['new_nilai_biaya'],
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where projects budget plan
            $where_plan = array('plan_id' => $result['plan_id']);
            // where
            $where = array(
                'plan_id' => $plan_id,
                'item_id' => $item_id
            );
            // delete process
            if (!$this->m_project->delete_projects_budget_item($where)) {
                // error delete
                $this->tnotification->sent_notification('error', 'Data rencana item gagal dihapus!');
                redirect('keuangan/rencana/project/item/' . $plan_id);
            }
            // update projects budget plan
            $this->m_project->update_projects_budget_plan($params_plan,$where_plan);
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana item berhasil dihapus!");
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data item pengajuan gagal dihapus!');
        }
        // default redirect
        redirect('keuangan/rencana/project/item/' . $plan_id);
    }

    // rencana detail
    public function detail($plan_id = ''){
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/detail.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // get list rencana item
        $this->smarty->assign("rs_id", $this->m_project->get_list_item_and_detail_by_plan_id($plan_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();    
    }

    // detail add process
    public function detail_add_process(){
        // set page rule
        $this->_set_page_rule("C");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|integer|max_length[255]');
        $this->tnotification->set_rules('detail_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        $this->tnotification->set_rules('detail_sub_total', 'Sub Total', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        $item_id = $this->input->post('item_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // get detail id
            $detail_id = $this->m_project->get_last_detail_id($item_id);
            // get detail no
            $detail_no = $this->m_project->get_detail_no_rencana_detail_by_item_id($item_id);
            // params
            $params = array(
                'detail_id' => $detail_id,
                'item_id' => $item_id,
                'detail_no' => $detail_no,
                'detail_uraian' => $this->input->post('detail_uraian',true),
                'detail_volume' => $this->input->post('detail_volume',true),
                'detail_satuan' => $this->input->post('detail_satuan',true),
                'detail_harga' => $this->input->post('detail_harga',true),
                'detail_sub_total' => $this->input->post('detail_sub_total',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // insert
            if (!$this->m_project->insert_projects_budget_detail($params)) {
                // error insert
                $this->tnotification->sent_notification("error", "Data rencana detail item gagal diproses!");
                redirect("keuangan/rencana/project/detail/" . $plan_id);
            }           

            // success insert
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana detail item berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/detail/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana detail item gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect('keuangan/rencana/project/detail/' . $plan_id);
    }

    // detail edit process
    public function detail_edit_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('detail_id', 'ID Detail Item', 'trim|required');
        $this->tnotification->set_rules('item_id', 'ID Item', 'trim|required');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|integer|max_length[255]');
        $this->tnotification->set_rules('detail_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        $this->tnotification->set_rules('detail_sub_total', 'Sub Total', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        $detail_id = $this->input->post('detail_id',true);
        $item_id = $this->input->post('item_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // params
            $params = array(
                'detail_uraian' => $this->input->post('detail_uraian',true),
                'detail_volume' => $this->input->post('detail_volume',true),
                'detail_satuan' => $this->input->post('detail_satuan',true),
                'detail_harga' => $this->input->post('detail_harga',true),
                'detail_sub_total' => $this->input->post('detail_sub_total',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // where
            $where = array(
                'detail_id' => $detail_id,
                'item_id' => $item_id
            );
            // update
            if (!$this->m_project->update_projects_budget_detail($params,$where)) {
                // error update
                $this->tnotification->sent_notification("error", "Data rencana detail item gagal diproses!");
                redirect("keuangan/rencana/project/detail/" . $plan_id);
            }

            // success insert
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana detail item berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/detail/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana detail item gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect('keuangan/rencana/project/detail/' . $plan_id);
    }

    // detail delete process
    public function delete_detail(){
        // set page rule
        $this->_set_page_rule("D");
        // validasi 
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('detail_id', 'ID Detail', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        $detail_id = $this->input->post('detail_id',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // where rencana detail
            $where = array('detail_id' => $detail_id);
            // delete process
            if (!$this->m_project->delete_projects_budget_detail($where)) {
                // error delete
                $this->tnotification->sent_notification('error', 'Data rencana detail item gagal diproses!');
                redirect('keuangan/rencana/project/detail/' . $detail_id);
            }
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana detail item berhasil dihapus!");
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data detail item pengajuan gagal dihapus!');
        }
        // default redirect
        redirect('keuangan/rencana/project/detail/' . $plan_id);
    }

    // output
    public function output($plan_id = ''){
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "keuangan/rencana/project/output.html");
        // get data
        $detail = $this->m_project->get_data_plan_by_id($plan_id);
        // check data plan
        if (empty($detail)) {
            // no data
            $this->tnotification->sent_notification("error", "Data rencana tidak ditemukan!");
            redirect("keuangan/rencana/project");
        }
        // assign
        $this->smarty->assign("detail", $detail);
        // data jenis output
        $this->smarty->assign("rs_js_output", $this->m_project->get_list_jenis_output());
        // data jenis kegiatan
        $this->smarty->assign("rs_js_kegiatan", $this->m_project->get_list_jenis_kegiatan());
        // data kegiatan
        $rs_kegiatan = $this->m_project->get_list_rencana_program(array($detail['struktur_cd'],$detail['jenis_kode_kegiatan']));
        $this->smarty->assign("rs_kegiatan", $rs_kegiatan);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();    
    }

    // output process
    public function output_process(){
        // set page rule
        $this->_set_page_rule("C");
        $this->_set_page_rule("U");

        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Plan', 'trim|required');
        $this->tnotification->set_rules('project_id', 'ID Project', 'trim|required');
        $this->tnotification->set_rules('kode_output', 'ID Output', 'trim');
        $this->tnotification->set_rules('jenis_kode_output', 'Jenis Output', 'trim|required');
        $this->tnotification->set_rules('jenis_kode_kegiatan', 'Jenis Kegiatan', 'trim|required');
        $this->tnotification->set_rules('kode_kegiatan', 'Program', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        $kode_output = $this->input->post('kode_output',true);
        // process
        if ($this->tnotification->run() == TRUE) {
            // input
            $kode_kegiatan = $this->input->post('kode_kegiatan',true);

            // params
            $params = array(
                'kode_kegiatan' => $kode_kegiatan,
                'jenis_kode_output' => $this->input->post('jenis_kode_output',true),
                'project_id' => $this->input->post('project_id',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:I:s')
            );

            // check kode output
            if (empty($kode_output)) {
                // insert
                // get kode output new
                $kode_output = $this->m_project->get_last_kode_output($kode_kegiatan);
                // array
                $params['kode_output'] = $kode_output;
                // insert
                if (!$this->m_project->insert_rencana_output($params)) {
                    // error insert
                    $this->tnotification->sent_notification("error", "Data rencana output gagal diproses!");
                    redirect("keuangan/rencana/project/output/" . $plan_id);
                }

                // update projects budget plan
                $params_plan = array(
                    'kode_output' => $kode_output,
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date('Y-m-d H:I:s')
                );
                // where 
                $where_plan = array('plan_id' => $plan_id);
                // update projects budget plan process
                if (!$this->m_project->update_projects_budget_plan($params_plan,$where_plan)) {
                    // error insert
                    $this->tnotification->sent_notification("error", "Data rencana output gagal diproses!");
                    redirect("keuangan/rencana/project/output/" . $plan_id);
                }
            } else{
                // update
                // check data
                if (!$this->m_project->is_exist_rencana_output_by_plan_id($plan_id)) {
                    // no data
                    $this->tnotification->sent_notification('error', 'Data rencana output tidak ditemukan!');
                    redirect('keuangan/rencana/project/output/' . $plan_id);
                }
                // where
                $where = array('kode_output' => $kode_output);
                // insert
                if (!$this->m_project->update_rencana_output($params,$where)) {
                    // error insert
                    $this->tnotification->sent_notification("error", "Data rencana output gagal diproses!");
                    redirect("keuangan/rencana/project/output/" . $plan_id);
                }
            }

            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data rencana output berhasil diproses!");
            // redirect
            redirect("keuangan/rencana/project/output/" . $plan_id);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data rencana output item gagal diproses, periksa kembali data yang Anda masukan!");
        }
        // default redirect
        redirect('keuangan/rencana/project/output/' . $plan_id);
    }

    // verfikasi
    public function verifikasi_process($item_id = ''){
        // set page rule
        $this->_set_page_rule("U");
        // get nilai data
        $result = $this->m_project->get_nilai_by_item_id($item_id);
        // check data
        if (empty($result)) {
            // no data
            $this->tnotification->sent_notification('error', 'Data rencana pengajuan tidak ditemukan!');
            redirect('keuangan/rencana/project');
        }
        // params
        $params_item = array(
            'item_volume' => $result['new_item_volume'],
            'item_harga' => $result['new_item_harga'],
            'item_total' => $result['new_item_total'],
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date('Y-m-d H:i:s')
        );
        // where
        $where_item = array('item_id' => $result['item_id']);
        // update item
        $this->m_project->update_projects_budget_item($params_item,$where_item);

        // params
        $params_plan = array(
            'nilai_biaya' => $result['new_nilai_biaya'],
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date('Y-m-d H:i:s')  
        );
        // where
        $where_plan = array('plan_id' => $result['plan_id']);
        // update plan
        $this->m_project->update_projects_budget_plan($params_plan,$where_plan);

        // success
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "Verifikasi item " . $result['nama_akun'] . " ( " . $result['perusahaan_nama']. "  ) " . "berhasil!");
        // redirect
        redirect('keuangan/rencana/project/detail/' . $result['plan_id']);
    }

    // send
    public function send_process(){
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required');

        // input
        $plan_id = $this->input->post('plan_id',true);
        // check data project
        if(!$this->m_project->is_exist_plan_projects_by_plan_id($plan_id)){
            // no data
            $this->tnotification->set_error_message('Data pengajuan RKA project belum terisi secara lengkap atau masih ada total nilai yang belum sama, periksa data kembali!');
        }
        // check rencana output
        if (!$this->m_project->is_exist_rencana_output_by_plan_id($plan_id)) {
            // no data
            $this->tnotification->set_error_message('Rencana output masih kosong, silakan tambahkan terlebih dahulu!');
        }
        // proses
        if ($this->tnotification->run() == TRUE) {
            // params
            $params = array(
                'send_status' => 'process',
                'send_by' => $this->com_user['user_id'],
                'send_by_name' => $this->com_user['user_alias'],
                'send_date' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s'),
            );
            // where
            $where = array('plan_id' => $plan_id);
            // update projects plan
            if (!$this->m_project->update_projects_budget_plan($params,$where)) {
                // error update
                $this->tnotification->sent_notification('error', 'Data pengajuan RKA gagal dikirim!');
            }
            // get data process
            $process = $this->m_project->get_next_flow_id_by_plan_id($plan_id);
            // update process previous
            $params = array(
                'process_st' => 'approve',
                'action_st' => 'done',
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'],
                'mdd_finish' => date('Y-m-d H:i:s')
            );
            // where
            $where = array('process_id' => $process['process_id']);
            // update process
            $this->m_project->update_projects_budget_process($params,$where);
            // insert process next
            $params = array(
                'process_id' => $this->m_project->get_id(),
                'plan_id' => $plan_id,
                'flow_id' => $process['next_flow_id'],
                'process_references_id' => $process['process_id'],
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            // insert flow next
            $this->m_project->insert_projects_budget_process($params);

            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data pengajuan RKA project berhasil dikirim!");
            // redirect
            redirect("keuangan/rencana/project");
        } else {
            // default error
            $this->tnotification->sent_notification('error', 'Data pengajuan RKA project gagal diproses!');
        }
        // default redirect
        redirect('keuangan/rencana/project/output/' . $plan_id);
    }

    // ajax list akun by perusahaan id
    function list_akun(){
        // input
        $perusahaan_id = trim($this->input->post('perusahaan_id',true));
        // get data
        $rs_akun = $this->m_project->get_list_akun_by_perusahaan_id($perusahaan_id);
        // check
        if (!empty($rs_akun)) {
            // ada data
            header('Content-Type: application/json');
            echo json_encode($rs_akun);
        } else{
            // no data
            header('Content-Type: application/json');
            echo json_encode(array());
        }
    }

    // ajax list kegiatan by jenis_kode_kegiatan
    function list_kegiatan(){
        // input
        $jenis_kode_kegiatan = trim($this->input->post('jenis_kode_kegiatan',true));
        $struktur_cd = trim($this->input->post('struktur_cd',true));
        // get data
        $rs_kegiatan = $this->m_project->get_list_rencana_program(array($struktur_cd,$jenis_kode_kegiatan));
        // check
        if (!empty($rs_kegiatan)) {
            // ada data
            header('Content-Type: application/json');
            echo json_encode($rs_kegiatan);
        } else{
            // no data
            header('Content-Type: application/json');
            echo json_encode(array());
        }
    }
}
