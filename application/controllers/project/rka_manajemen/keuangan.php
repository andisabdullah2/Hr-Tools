<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class keuangan extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/rka_manajemen/m_approval');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/keuangan/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_rka_manajemen_keuangan') ? $this->tsession->userdata('search_rka_manajemen_keuangan') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $project_alias = empty($search['project_alias']) ? '%' : '%' . $search['project_alias'] . '%';
        $process_st = empty($search['process_st']) ? '%' : $search['process_st'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_manajemen/keuangan/index/");
        $config['total_rows'] = $this->m_approval->get_total_rka_manajemen(array(3, $project_alias, $process_st));
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
        $params = array(3, $project_alias, $process_st, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_rka_manajemen($params));
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
                "process_st" => $this->input->post('process_st', TRUE)
            );
            // set session
            $this->tsession->set_userdata("search_rka_manajemen_keuangan", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_rka_manajemen_keuangan");
        }
        // redirect
        redirect("project/rka_manajemen/keuangan");
    }

    // detail
    public function detail($process_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/keuangan/detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        //
        $detail = $this->m_approval->get_process_by_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/keuangan");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_approval->get_list_item_and_detail($detail['plan_id']));
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
        $detail = $this->m_approval->get_process_by_id($process_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/keuangan");
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
            $this->m_approval->update_projects_budget_process($params, $where); 
            //
            switch ( $this->input->post('process_st', TRUE) ) {
                case 'approve':
                    // flow 4
                    $flow_4 = $this->m_approval->get_flow_by_params(array(15, 4));
                    if ( $flow_4 ) {
                        $process_references_id = $process_id;
                        $process_id = $this->m_approval->get_process_id();
                        //
                        $params = array(
                            'process_id' => $process_id,
                            'plan_id' => $detail['plan_id'],
                            'flow_id' => $flow_4['flow_id'],
                            'process_references_id' => $process_references_id,
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $this->m_approval->insert_projects_budget_process($params);
                    }
                    break;
                
                default:
                    // flow 3
                    $flow_4 = $this->m_approval->get_flow_by_params(array(15, 4));
                    if ( $flow_4 ) {
                        //
                        $process_references_id = $process_id;
                        $process_id = $this->m_approval->get_process_id();
                        //
                        $params = array(
                            'process_id' => $process_id,
                            'plan_id' => $detail['plan_id'],
                            // 'flow_id' => $flow_4['flow_id'],
                            'flow_revisi_id' => $detail['flow_id'],
                            'process_references_id' => $process_references_id,
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $this->m_approval->insert_projects_budget_process($params);
                        // update plan ke draft
                        $params = array(
                            'send_status' => 'draft',
                            'plan_status' => 'rejected',
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $where = array( 'plan_id' => $detail['plan_id'] );
                        $this->m_approval->update_projects_budget_plan($params, $where);
                    }
                    break;
            }
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Pengajuan berhasil diproses");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan gagal diproses");
        }
        // default redirect
        redirect("project/rka_manajemen/keuangan");
    }

}
