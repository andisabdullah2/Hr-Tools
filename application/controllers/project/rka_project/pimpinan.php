<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class pimpinan extends ApplicationBase {

    // flow kode
    private $flow_pimpinan = '16002';
    private $flow_keuangan = '16003';

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/rka_project/m_approval');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pimpinan/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // search
        $search = $this->tsession->userdata("approval_pimpinan_rka_project_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $process_st = empty($search['process_st']) ? '%' :  $search['process_st'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_project/pimpinan/index/");
        $params = array($this->flow_pimpinan, 16,$project,$project,$process_st);
        $config['total_rows'] = $this->m_approval->get_total_flow($params);
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
        $params = array($this->flow_pimpinan, 16,$project,$project,$process_st, ($start - 1), $config['per_page']);
        $rs_id = $this->m_approval->get_list_flow($params);
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
            $this->tsession->unset_userdata('approval_pimpinan_rka_project_search');
        } else {
            $params = array(
                "project" => $this->input->post("project",true),
                "process_st" => $this->input->post("process_st",true)
            );
            $this->tsession->set_userdata("approval_pimpinan_rka_project_search", $params);
        }
        // redirect
        redirect("project/rka_project/pimpinan");
    }

    // get detail process
    public function detail($process_id = '')
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/pimpinan/detail.html");
        // load style
        $this->smarty->load_style("default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/sweetalert2-7.24.4/package/dist/sweetalert2.min.js");
        // params
        $params= array($process_id,$this->flow_pimpinan, 16);
        // get detail project budget plan
        $detail = $this->m_approval->get_detail_flow($params);
        // cek data project plan
        if(empty($detail)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ditemukan.");
            //  redirect
            redirect("project/rka_project/pimpinan/");
        }
        // cek flow
        $this->smarty->assign("detail", $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses persetujuan
    public function proses_persetujuan()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'ID Proses', 'trim|required|max_length[20]');
        //proses
        if($this->tnotification->run() != false){
            // params
            $params = array($this->input->post('process_id', TRUE),$this->flow_pimpinan, 16);
            // get detail project
            $detail = $this->m_approval->get_detail_flow($params);
            // cek data project plan
            if(empty($detail)){
                // default error
                $this->tnotification->sent_notification("error", "Data tidak ditemukan.");
                //  redirect
                redirect("project/rka_project/pimpinan/");
            }
            // set params update pimpinan
            $params_pimpinan = array(
                'catatan' => $this->input->post('catatan', TRUE),
                'action_st' => 'done',
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'],
                'mdd_finish' => date("Y-m-d H:i:s")
            );
            $where_pimpinan = array(
                'process_id' => $this->input->post('process_id', TRUE)
            );
            // cek process
            if ($this->input->post('process') == "approve") {
                // update params update pimpinan
                $params_pimpinan['process_st'] = 'approve';
                // get flow keuangan
                $flow_keuangan = $this->m_approval->get_detail_flow_by_plant( array($detail['plan_id'],$this->flow_keuangan, 16));
                // cek exsist next flow
                if(!$flow_keuangan){
                    // insert new project plan
                    // get process id
                    $new_process_id = $this->m_approval->get_microtime();
                    // set params
                    $params = array(
                        'process_id' => $new_process_id,
                        'plan_id' => $detail['plan_id'],
                        'flow_id' => $this->flow_keuangan,
                        'flow_revisi_id' => $this->flow_pimpinan,
                        'process_references_id' => $this->input->post('process_id', TRUE),
                        'process_st' => 'waiting',
                        'action_st' => 'process',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    // insert
                    $this->m_approval->insert_project_budget_process($params);
                }
                // update project plan
                // set params
                $params = array(
                    'send_status' => 'process',
                    'plan_status' => 'waiting',
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                $where = array(
                    'plan_id' => $detail['plan_id']
                );
                // update
                $this->m_approval->update_projects_budget_plant($params, $where);
            }else{
                // update params update pimpinan
                $params_pimpinan['process_st'] = 'reject';
                // update project plan
                // set params
                $params = array(
                    'send_status' => 'done',
                    'plan_status' => 'rejected',
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                $where = array(
                    'plan_id' => $detail['plan_id']
                );
                // update
                $this->m_approval->update_projects_budget_plant($params, $where);
                // delete next flow exist
                $this->m_approval->delete_project_budget_process(array($detail['task_number']));
            }

            // update project proses pimpinan
            if($this->m_approval->update_project_budget_process($params_pimpinan, $where_pimpinan)){
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect
                redirect("project/rka_project/pimpinan/");
            }else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan.");
            }
        }else{
            // default error
            $this->tnotification->sent_notification('error', 'Data gagal disimpan');
        }
        // default redirect
        redirect("project/rka_project/pimpinan/detail/". $this->input->post('process_id', TRUE));
    }
}
