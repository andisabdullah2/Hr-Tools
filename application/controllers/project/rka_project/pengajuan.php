<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class pengajuan extends ApplicationBase {

    // flow id pengajuan
    var $flow_id_pengajuan = '16001';
    var $flow_id_persetujuan_pimpinan = '16002';

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/rka_project/m_pengajuan');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // search
        $search = $this->tsession->userdata("pengajuan_rka_project_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $plan_status = empty($search['plan_status']) ? '%' :  $search['plan_status'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_project/pengajuan/index/");
        $params = array($project, $project, $plan_status);
        $config['total_rows'] = $this->m_pengajuan->get_total_pengajuan($params);
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
        // get list
        $params = array($project, $project, $plan_status, ($start - 1), $config['per_page']);
        $rs_id = $this->m_pengajuan->get_list_pengajuan($params);
        // get list data
        $this->smarty->assign("rs_id", $rs_id);
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
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('pengajuan_rka_project_search');
        } else {
            $params = array(
                "project" => $this->input->post("project",true),
                "plan_status" => $this->input->post("plan_status",true)
            );
            $this->tsession->set_userdata("pengajuan_rka_project_search", $params);
        }
        // redirect
        redirect("project/rka_project/pengajuan");
    }

    // form add rencana
    public function add_rencana($plan_id = '') {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/add_rencana.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        // cek plan id
        if(! empty($plan_id)){
            // get data project budget plan
            $project_budget_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek status proses
            if($project_budget_plant['send_status'] == 'process'){
                // default error
                $this->tnotification->sent_notification("warning", "Data sedang di proses");
                // redirect
                redirect("project/rka_project/pengajuan/");
            }
            // cek status sukses
            if($project_budget_plant['send_status'] == 'done'){
                // default error
                $this->tnotification->sent_notification("warning", "Data sudah di proses");
                // redirect
                redirect("project/rka_project/pengajuan/");
            }
            // assign
            $this->smarty->assign("result", $project_budget_plant);
        }
        // get data project
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_all_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses add rencana
    public function add_rencana_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('nilai_pendapatan', ' Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', ' Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', ' Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', ' Catatan', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // plant id
            if(empty($this->input->post('plan_id'))){
                // proses insert data project budget plan
                // tanggal
                $prefix = date('Ym');
                // get id
                $plan_id = $this->m_pengajuan->get_last_plant_id($prefix, $prefix);
                if(empty($plan_id)){
                    $this->tnotification->set_error_message('ID Plan tidak tersedia');
                }
                // params
                $params = array(
                    'plan_id' => $plan_id,
                    'project_id' => $this->input->post('project_id', TRUE),
                    'nilai_pendapatan' => $this->input->post('nilai_pendapatan', TRUE),
                    'nilai_pajak' => $this->input->post('nilai_pajak', TRUE),
                    'nilai_anggaran' => $this->input->post('nilai_anggaran', TRUE),
                    'nilai_biaya' => 0,
                    'catatan' => $this->input->post('catatan', TRUE),
                    'send_status' => 'draft',
                    'create_by' => $this->com_user['user_id'],
                    'create_by_name' => $this->com_user['user_alias'],
                    'create_date' => date("Y-m-d H:i:s"),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // insert
                if ($this->m_pengajuan->insert_projects_budget_plan($params)) {
                    // set params
                    $params = array(
                        'process_id' => $this->m_pengajuan->get_microtime(),
                        'flow_id' => $this->flow_id_pengajuan,
                        'plan_id' => $plan_id,
                        'process_st' => 'waiting',
                        'action_st' => 'process',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    // add project budget process data
                    if($this->m_pengajuan->insert_project_budget_process($params)){
                        // redirect
                        redirect("project/rka_project/pengajuan/rencana_item/".$plan_id);
                    }else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            }else{
                // update pengajuan budget plan
                // get nilai biaya
                $nilai_biaya = floatval($this->input->post('nilai_pendapatan', TRUE)) + floatval($this->input->post('nilai_pajak', TRUE)) + floatval($this->input->post('nilai_anggaran', TRUE));
                // params
                $params = array(
                    'project_id' => $this->input->post('project_id', TRUE),
                    'nilai_pendapatan' => $this->input->post('nilai_pendapatan', TRUE),
                    'nilai_pajak' => $this->input->post('nilai_pajak', TRUE),
                    'nilai_anggaran' => $this->input->post('nilai_anggaran', TRUE),
                    'nilai_biaya' => $nilai_biaya,
                    'catatan' => $this->input->post('catatan', TRUE),
                    'send_status' => 'draft',
                    'create_by' => $this->com_user['user_id'],
                    'create_by_name' => $this->com_user['user_alias'],
                    'create_date' => date("Y-m-d H:i:s"),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // condition
                $where = array(
                    'plan_id' => $this->input->post('plan_id')
                );
                // update
                if ($this->m_pengajuan->update_projects_budget_plant($params, $where)) {
                    // redirect
                    redirect("project/rka_project/pengajuan/rencana_item/" . $this->input->post('plan_id'));
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/rka_project/pengajuan/add_rencana");
    }

    // form edit rencana
    public function update_rencana($plan_id  = '')
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/edit_rencana.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        // get detail project budget plan
        $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if(empty($rs_project_plant)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status proses
        if($rs_project_plant['send_status'] == 'process'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sedang di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status sukses
        if($rs_project_plant['send_status'] == 'done'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sudah di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // get data project
        $this->smarty->assign("result", $rs_project_plant);
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_all_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses update rencana
    public function update_rencana_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Plant', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('nilai_pendapatan', ' Nilai Pendapatan', 'trim|required');
        $this->tnotification->set_rules('nilai_pajak', ' Nilai Pajak', 'trim|required');
        $this->tnotification->set_rules('nilai_anggaran', ' Nilai Anggaran', 'trim|required');
        $this->tnotification->set_rules('catatan', ' Catatan', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get nilai biaya
            $nilai_biaya = floatval($this->input->post('nilai_pendapatan', TRUE)) + floatval($this->input->post('nilai_pajak', TRUE)) + floatval($this->input->post('nilai_anggaran', TRUE));
            // params
            $params = array(
                'project_id' => $this->input->post('project_id', TRUE),
                'nilai_pendapatan' => $this->input->post('nilai_pendapatan', TRUE),
                'nilai_pajak' => $this->input->post('nilai_pajak', TRUE),
                'nilai_anggaran' => $this->input->post('nilai_anggaran', TRUE),
                'nilai_biaya' => $nilai_biaya,
                'catatan' => $this->input->post('catatan', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // condition
            $where = array(
                'plan_id' => $this->input->post('plan_id')
            );
            // update
            if ($this->m_pengajuan->update_projects_budget_plant($params, $where)) {
                // redirect
                redirect("project/rka_project/pengajuan/rencana_item/" . $this->input->post('plan_id'));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/rka_project/pengajuan/update_rencana");
    }

    // form delete rencana
    public function delete_rencana($plan_id  = '')
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/delete_rencana.html");
        // get detail project budget plan
        $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if(empty($rs_project_plant)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        // get data project
        $this->smarty->assign("detail", $rs_project_plant);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses delete rencana
    public function delete_rencana_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('plan_id', 'ID Rencana', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $plan_id = $this->input->post('plan_id', TRUE);
            $detail = $this->m_pengajuan->get_detail_plant_by_id($plan_id);
            if (empty($detail)) {
                // default error
                $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
                // default redirect
                redirect("project/rka_project/pengajuan");
            }
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
        redirect("project/rka_project/pengajuan");
    }

    // form tambah rencana item
    public function rencana_item($plan_id = '')
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/rencana_item.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        // get detail project budget plan
        $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if(empty($rs_project_plant)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status proses
        if($rs_project_plant['send_status'] == 'process'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sedang di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status sukses
        if($rs_project_plant['send_status'] == 'done'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sudah di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // get data & assign
        $this->smarty->assign("rs_project_plant", $rs_project_plant);
        $this->smarty->assign("rs_perusahaan", $this->m_pengajuan->get_all_perusahaan());
        $this->smarty->assign("rs_item_no", $this->m_pengajuan->get_last_item_no(array($plan_id)));
        $this->smarty->assign("rs_project_item", $this->m_pengajuan->get_list_project_budget_item(array($plan_id)));
        $this->smarty->assign("rs_project_group", $this->m_pengajuan->get_project_budget_group());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses tambah rencana item
    public function add_rencana_item_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Plant', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('group_id', 'Group', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('kode_akun', 'Akun', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('perusahaan_id', 'Akun', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('item_no', 'No Item', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('item_volume', 'Volume', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get plan id
            $plan_id =  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // get id
            $item_id = $this->m_pengajuan->get_last_item_id($plan_id, $plan_id);
            if(empty($item_id)){
                $this->tnotification->set_error_message('ID item tidak tersedia');
            }
            // get nilai biaya
            $item_total = floatval($this->input->post('item_harga', TRUE)) * floatval($this->input->post('item_volume', TRUE));
            // params
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
                'item_total' => $item_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_pengajuan->insert_projects_budget_item($params)) {
                // get sum nilai item total
                $sum_item_total = $this->m_pengajuan->get_sum_item_total_by_plan_id(array($plan_id));
                // update nilai biaya
                $params = array(
                    'nilai_biaya' => $sum_item_total
                );
                $where = array(
                    'plan_id' => $plan_id
                );
                // update
                if($this->m_pengajuan->update_projects_budget_plant($params, $where)){
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                }else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal di proses");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        //  default redirect
        redirect("project/rka_project/pengajuan/rencana_item/". $this->input->post('plan_id', TRUE));
    }

    // proses ubah rencana item
    public function update_rencana_item_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('item_id', 'Item ID', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('plan_id', 'Plant', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('group_id', 'Group', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('kode_akun', 'Akun', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('perusahaan_id', 'Akun', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('item_no', 'No Item', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('item_uraian', 'Uraian', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('item_volume', 'Volume', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('item_satuan', 'Satuan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('item_harga', 'Harga', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $plan_id=  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // get nilai biaya
            $item_total = floatval($this->input->post('item_harga', TRUE)) * floatval($this->input->post('item_volume', TRUE));
            // params
            $params = array(
                'plan_id' => $plan_id,
                'group_id' => $this->input->post('group_id', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'perusahaan_id' => $this->input->post('perusahaan_id', TRUE),
                'item_no' => $this->input->post('item_no', TRUE),
                'item_uraian' => $this->input->post('item_uraian', TRUE),
                'item_volume' => $this->input->post('item_volume', TRUE),
                'item_satuan' => $this->input->post('item_satuan', TRUE),
                'item_harga' => $this->input->post('item_harga', TRUE),
                'item_total' => $item_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'item_id' => $this->input->post('item_id', TRUE),
            );
            // insert
            if ($this->m_pengajuan->update_projects_budget_item($params, $where)) {
                // get sum nilai item total
                $sum_item_total = $this->m_pengajuan->get_sum_item_total_by_plan_id(array($this->input->post('plan_id', TRUE)));
                // update nilai biaya
                $params = array(
                    'nilai_biaya' => $sum_item_total
                );
                $where = array(
                    'plan_id' => $this->input->post('plan_id', TRUE)
                );
                // update
                if($this->m_pengajuan->update_projects_budget_plant($params, $where)){
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                }else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal di proses");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        //  default redirect
        redirect("project/rka_project/pengajuan/rencana_item/". $this->input->post('plan_id', TRUE));
    }

    // proses hapus rencana item
    public function delete_rencana_item_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Plan ID', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('item_id', 'Item ID', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $plan_id=  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // params
            $where = array(
                'item_id' => $this->input->post('item_id', TRUE),
            );
            // insert
            if ($this->m_pengajuan->delete_project_budget_item($where)) {
                // get sum nilai item total
                $sum_item_total = $this->m_pengajuan->get_sum_item_total_by_plan_id(array($this->input->post('plan_id', TRUE)));
                // update nilai biaya
                $params = array(
                    'nilai_biaya' => $sum_item_total
                );
                $where = array(
                    'plan_id' => $this->input->post('plan_id', TRUE)
                );
                // update
                if($this->m_pengajuan->update_projects_budget_plant($params, $where)){
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                }else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal di proses");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        //  default redirect
        redirect("project/rka_project/pengajuan/rencana_item/". $this->input->post('plan_id', TRUE));
    }

    // form tambah rencana detail
    public function rencana_detail($plan_id = '')
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pengajuan/rencana_detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        // get detail project budget plan
        $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if(empty($rs_project_plant)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status proses
        if($rs_project_plant['send_status'] == 'process'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sedang di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // cek status sukses
        if($rs_project_plant['send_status'] == 'done'){
            // default error
            $this->tnotification->sent_notification("warning", "Data sudah di proses");
            // redirect
            redirect("project/rka_project/pengajuan/");
        }
        // get project item
        $rs_project_item =  $this->m_pengajuan->get_list_project_budget_item_with_detail_no(array($plan_id));
        // get project item detail
        foreach ($rs_project_item as $key => $project_item) {
            $rs_project_item[$key]['detail_item'] = $this->m_pengajuan->get_list_project_budget_detail(array($project_item['item_id']));
        }
        // get data & assign
        $this->smarty->assign("rs_project_plant", $rs_project_plant);
        $this->smarty->assign("rs_project_item", $rs_project_item);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    
    // proses tambah rencama detail
    public function add_rencana_detail_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Plant', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('item_id', 'ID item', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('detail_no', 'No detail', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('detail_satuan', 'Harga', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        //proses
        if($this->tnotification->run() != false){
            $plan_id=  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // get id detail
            $item_id = $this->input->post('item_id');
            $detail_id = $this->m_pengajuan->get_last_detail_id($item_id, $item_id);
            // get detail sub total
            $detail_sub_total = $this->input->post('detail_volume', true) * $this->input->post('detail_harga', true);
            // set parameter
            $params = array(
                'detail_id' => $detail_id,
                'item_id' => $item_id,
                'detail_no' => $this->input->post('detail_no', true),
                'detail_uraian' => $this->input->post('detail_uraian', true),
                'detail_volume' => $this->input->post('detail_volume', true),
                'detail_satuan' => $this->input->post('detail_satuan', true),
                'detail_harga' => $this->input->post('detail_harga', true),
                'detail_sub_total' => $detail_sub_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // proses insert
            if($this->m_pengajuan->insert_projects_budget_detail($params)){
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            }else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan.");
            }
        }else{
          // default error
            $this->tnotification->set_notification('error', 'Data gagal disimpan');
        }
        // default redirect
        redirect("project/rka_project/pengajuan/rencana_detail/". $this->input->post('plan_id', TRUE));
    }

    // proses ubah detail item 
    public function update_rencana_detail_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Plant', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('detail_id', 'Plant', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('detail_no', 'No detail', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('detail_uraian', 'Uraian', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('detail_volume', 'Jumlah', 'trim|required|max_length[11]');
        $this->tnotification->set_rules('detail_satuan', 'Harga', 'trim|required|max_length[225]');
        $this->tnotification->set_rules('detail_harga', 'Harga', 'trim|required');
        //proses
        if($this->tnotification->run() != false){
            $plan_id=  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // get detail sub total
            $detail_sub_total = $this->input->post('detail_volume', true) * $this->input->post('detail_harga', true);
            // set parameter
            $params = array(
                'detail_no' => $this->input->post('detail_no', true),
                'detail_uraian' => $this->input->post('detail_uraian', true),
                'detail_volume' => $this->input->post('detail_volume', true),
                'detail_satuan' => $this->input->post('detail_satuan', true),
                'detail_harga' => $this->input->post('detail_harga', true),
                'detail_sub_total' => $detail_sub_total,
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // condition
            $where = array(
                'detail_id' => $this->input->post('detail_id', true)
            );
            // proses update
            if($this->m_pengajuan->update_projects_budget_detail($params, $where)){
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            }else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan.");
            }
        }else{
            // default error
            $this->tnotification->sent_notification('error', 'Data gagal disimpan');
        }
        // default redirect
        redirect("project/rka_project/pengajuan/rencana_detail/". $this->input->post('plan_id', TRUE));
    }

    // proses hapus rencana detail
    public function delete_rencana_detail_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('plan_id', 'Item ID', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('detail_id', 'Item ID', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $plan_id=  $this->input->post('plan_id', TRUE);
            // get detail project budget plan
            $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
            // cek data project plan
            if(empty($rs_project_plant)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak tersedia");
                //  redirect
                redirect("project/rka_project/pengajuan/");
            }
            // params
            $where = array(
                'detail_id' => $this->input->post('detail_id', TRUE),
            );
            // insert
            if ($this->m_pengajuan->delete_project_budget_detail($where)) {
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
        //  default redirect
        redirect("project/rka_project/pengajuan/rencana_detail/". $this->input->post('plan_id', TRUE));
    }

    // proses verifikasi
    public function verifikasi_process($item_id)
    {
        // set page rules
        $this->_set_page_rule("U");
        $detail = $this->m_pengajuan->get_project_budget_item_by_id($item_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_project/pengajuan");
        }
        $this->m_pengajuan->update_item_total($item_id);
        // notification
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "Verifikasi item " . $detail['nama_akun'] . " ( " . $detail['perusahaan_nama']. "  ) " . "berhasil");
        // default redirect
        redirect("project/rka_project/pengajuan/rencana_detail/".$detail['plan_id']);
    }

    // proses kirim pengajuan
    public function pengajuan_process($plan_id = '')
    {
        // set page rules
        $this->_set_page_rule("U");
        // get detail project budget plan
        $rs_project_plant = $this->m_pengajuan->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if (empty($rs_project_plant)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        // update flow pengajuan
        $flow_pengajuan = $this->m_pengajuan->get_detail_project_budget_process_by_id(array($plan_id, $this->flow_id_pengajuan));
        if (!empty($flow_pengajuan)) {
            // set params
            $params = array(
                'process_st' => 'approve',
                'action_st' => 'done',
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'],
                'mdd_finish' => date("Y-m-d H:i:s"),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'process_id' => $flow_pengajuan['process_id']
            );
            // update flow
            $this->m_pengajuan->update_project_budget_process($params, $where);
        } else {
            // get process id
            $process_id = $this->m_pengajuan->get_microtime();
            // set params
            $params = array(
                'process_id' => $process_id,
                'plan_id' => $plan_id,
                'flow_id' => $this->flow_id_pengajuan,
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            $this->m_pengajuan->insert_project_budget_process($params);
        }
        // insert flow persetujuan pimpinan
        $flow_persetujuan = $this->m_pengajuan->get_detail_project_budget_process_by_id(array($plan_id, $this->flow_id_persetujuan_pimpinan));
        if (!$flow_persetujuan) {
            // get flow pengajuan
            $flow_pengajuan = $this->m_pengajuan->get_detail_project_budget_process_by_id(array($plan_id, $this->flow_id_pengajuan));
            // get process id
            $process_id = $this->m_pengajuan->get_microtime();
            // set params
            $params = array(
                'process_id' => $process_id,
                'plan_id' => $plan_id,
                'flow_id' => $this->flow_id_persetujuan_pimpinan,
                'flow_revisi_id' => $this->flow_id_pengajuan,
                'process_references_id' => $flow_pengajuan['process_id'],
                'process_st' => 'waiting',
                'action_st' => 'process',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            $this->m_pengajuan->insert_project_budget_process($params);
        }
        // update project plan status
        $params = array(
            'send_status' => 'process',
            'send_by' => $this->com_user['user_id'],
            'send_by_name' => $this->com_user['user_alias'],
            'send_date' => date("Y-m-d H:i:s"),
            'mdb' => $this->com_user['user_id'],
            'mdb_name' => $this->com_user['user_alias'],
            'mdd' => date("Y-m-d H:i:s")
        );
        $where = array(
            'plan_id' => $plan_id
        );
        if ($this->m_pengajuan->update_projects_budget_plant($params, $where)) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil diproses");
            //  default redirect
            redirect("project/rka_project/pengajuan/");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        //  default redirect
        redirect("project/rka_project/pengajuan/rencana_detail/" . $plan_id);
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
            $rs_akun = $this->m_pengajuan->get_list_akun_perusahaan_by_level(array($perusahaan_id, 1));
            $html .= '<option value=""></option>';
            foreach ($rs_akun as $akun) {
                $html .= '<option value="' . $akun['kode_akun'] . '">' . "[".strtoupper($akun['kode_akun_alias'] . "] " . $akun['nama_akun']) . '</option>';
                $max_akun = 6;
                $inden = '';
                for($i = 2; $i <= $max_akun; $i++){
                    $child_akun = $this->m_pengajuan->get_list_akun_by_level(array($akun['group_kode'], $i));
                    if(!empty($child_akun)){
                        $inden .= '&nbsp;&nbsp;&nbsp;';
                        foreach ($child_akun as $child) {
                            $html .= '<option value="' . $child['kode_akun'] . '">'.$inden. "[".strtoupper($child['kode_akun_alias'] . "] " . $child['nama_akun']) . '</option>';
                        }
                    }
                }
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
            $rs_akun = $this->m_pengajuan->get_list_akun_perusahaan_by_level(array($perusahaan_id, 1));
            $html .= '<option value=""></option>';
            foreach ($rs_akun as $akun) {
                $html .= '<option value="' . $akun['kode_akun'] . '" '.($akun['kode_akun'] == $kode_akun ? "selected" : "").'>' . "[".strtoupper($akun['kode_akun_alias'] . "] " . $akun['nama_akun']) . '</option>';
                $max_akun = 6;
                $inden = '';
                for($i = 2; $i <= $max_akun; $i++){
                    $child_akun = $this->m_pengajuan->get_list_akun_by_level(array($akun['group_kode'], $i));
                    if(!empty($child_akun)){
                        $inden .= '&nbsp;&nbsp;&nbsp;';
                        foreach ($child_akun as $child) {
                            $html .= '<option value="' . $child['kode_akun'] . '"'.($child['kode_akun'] == $kode_akun ? "selected" : "").'>'.$inden. "[".strtoupper($child['kode_akun_alias'] . "] " . $child['nama_akun']) . '</option>';
                        }
                    }
                }
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
