<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class monitoring extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/rka_manajemen/m_monitoring');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/monitoring/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('search_rka_manajemen_monitoring') ? $this->tsession->userdata('search_rka_manajemen_monitoring') : '';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $project_alias = empty($search['project_alias']) ? '%' : '%' . $search['project_alias'] . '%';
        $plan_status = empty($search['plan_status']) ? '%' : $search['plan_status'];
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_manajemen/monitoring/index/");
        $config['total_rows'] = $this->m_monitoring->get_total_monitoring_rka_manajemen(array($project_alias, $plan_status));
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
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_monitoring_rka_manajemen($params));
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
            $this->tsession->set_userdata("search_rka_manajemen_monitoring", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_rka_manajemen_monitoring");
        }
        // redirect
        redirect("project/rka_manajemen/monitoring");
    }

    // detail
    public function detail($plan_id="") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/rka_manajemen/monitoring/detail.html");
        //
        $detail = $this->m_monitoring->get_plan_by_id($plan_id);
        if (empty($detail)) {
            // default error
            $this->tnotification->sent_notification("error", "Pengajuan tidak ditemukan");
            // default redirect
            redirect("project/rka_manajemen/monitoring");
        }
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_item_and_detail($plan_id));
        $this->smarty->assign("rs_flow", $this->m_monitoring->get_list_flow_plan(array($plan_id, 15)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

}
