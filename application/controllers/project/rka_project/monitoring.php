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
        $this->load->model('project/rka_project/m_monitoring');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index() {
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/monitoring/index.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // search
        $search = $this->tsession->userdata("monitoring_rka_project_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $plan_status = empty($search['plan_status']) ? '%' :  $search['plan_status'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/rka_project/monitoring/index/");
        $params = array($project, $project, $plan_status);
        $config['total_rows'] = $this->m_monitoring->get_total_pengajuan($params);
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
        $rs_id = $this->m_monitoring->get_list_pengajuan($params);
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
            $this->tsession->unset_userdata('monitoring_rka_project_search');
        } else {
            $params = array(
                "project" => $this->input->post("project",true),
                "plan_status" => $this->input->post("plan_status",true)
            );
            $this->tsession->set_userdata("monitoring_rka_project_search", $params);
        }
        // redirect
        redirect("project/rka_project/monitoring");
    }

    // get detail pengajuan
    public function detail($plan_id = '')
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/rka_project/monitoring/detail.html");
        // get detail project budget plan
        $rs_project_plant = $this->m_monitoring->get_detail_plant_by_id(array($plan_id));
        // cek data project plan
        if(empty($rs_project_plant)){
            // default error
            $this->tnotification->sent_notification("error", "Data tidak tersedia");
            //  redirect
            redirect("project/rka_project/pengajuan/");
        }
        $this->smarty->assign("detail", $rs_project_plant);
        $this->smarty->assign("rs_id", $this->m_monitoring->get_list_item_and_detail($plan_id));
        $this->smarty->assign("rs_flow", $this->m_monitoring->get_list_flow_plan(array($plan_id, 16)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
}
