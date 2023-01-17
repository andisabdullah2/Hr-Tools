<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pimpinan extends ApplicationBase {

    // my flow
    private $now_flow_id = 13002;
    private $next_flow_id = 13003;
    private $prev_flow_id = 13001;
    private $group_id = 13;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/lembur/m_pimpinan');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // view
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/lembur/pimpinan/list.html");
        // get tahun
        $tahun = $this->m_pimpinan->get_list_tahun();
        $this->smarty->assign("rs_tahun", $tahun);
        // bulan
        $bulan = $this->datetimemanipulation->get_month('in');
        $this->smarty->assign("rs_bulan", $bulan);
        $search = $this->tsession->userdata('pimpinan_search');
        //search parameter
        $flow_id = $this->now_flow_id;
        $struktur_cd = $this->m_pimpinan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $project_id = empty($search['project_id']) ? '%' : $search['project_id'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $tahun = $tahun;
        $bulan = $bulan;
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/lembur/pimpinan/index/");
        // params
        $params = array($flow_id, $struktur_cd, $project_id, $tahun, $bulan);
        $config['total_rows'] = $this->m_pimpinan->get_total_overtime_by_flow($params);
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
        // project list
        $this->smarty->assign('rs_project',$this->m_pimpinan->get_all_projects());
        // get list
        $params = array($struktur_cd,$flow_id, $project_id, $tahun, $bulan, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pimpinan->get_all_overtime_by_flow_limit($params));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('pimpinan_search');
        } else {
            $params = array(
                "project_id" => $this->input->post("project_id"),
                "tahun" => $this->input->post("tahun"),
                "bulan" => $this->input->post("bulan")
            );
            $this->tsession->set_userdata('pimpinan_search', $params);
        }
        // redirect
        redirect("kepegawaian/lembur/pimpinan");
    }


    // approval
    public function approval($overtime_id = "") {
        // set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pimpinan/approval.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_pimpinan->get_detail_overtime_by_process(array($overtime_id)));
        $this->smarty->assign("rs_id", $this->m_pimpinan->get_detail_personil_by_process(array($overtime_id)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // approval process
    public function approval_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'ID PROSES', 'trim|required');
        $this->tnotification->set_rules('overtime_id', 'ID LEMBUR', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            if (true) {
                // get id
                $overtime_id = $this->input->post('overtime_id',true);
                $process_st = (isset($_POST['approve']) ? 'approve' : 'reject');
                // update self
                $params = array(
                                'catatan' => $this->input->post('overtime_catatan'),
                                'action_st' => 'done',
                                'process_st' => $process_st,
                                'mdb_finish' => $this->com_user['user_id'],
                                'mdb_finish_name' => $this->com_user['user_alias'], 
                                'mdd_finish' => date('Y-m-d H:i:s')        
                                );
                $where = array ('process_id' => $this->input->post('process_id'));
                $this->m_pimpinan->update_surat_lembur_process($params,$where);
                // insert next
                if ($process_st == 'approve') {
                    // insert next
                    $flow_revisi = null;
                    $params = array(
                            'overtime_id' => $overtime_id,
                            'flow_id' => $this->now_flow_id
                            );
                    $process_references_id = $this->m_pimpinan->get_process_id($params);
                    /*
                    if($this->m_pimpinan->check_if_have_references($this->input->post('process_id', TRUE)) > 0){
                        $flow_revisi = $this->now_flow_id;
                        $process_references_id = $this->m_pimpinan->get_process_reference($this->input->post('process_id', TRUE));
                    }
                    */                    
                    $params = array(
                        'process_id' => $this->m_pimpinan->get_microtime(),
                        'flow_id' => $this->next_flow_id, 
                        'flow_revisi_id' => $flow_revisi, 
                        'process_references_id' => $process_references_id,                        
                        'overtime_id' => $overtime_id, 
                        'action_st' => 'process', 
                        'process_st' => 'waiting', 
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_pimpinan->insert_surat_lembur_process($params);
                } elseif ($process_st == 'reject') {
                    // update status pegawai_izin
                    $params = array(
                        'overtime_st' => 'rejected',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $where = array('overtime_id' => $overtime_id);
                    $this->m_pimpinan->update_surat_lembur($params, $where);
                    // update flow surat_lembur_process
                    $params = array(
                    'process_st' => 'reject',
                    'action_st' => 'done',
                    'mdb_finish' => $this->com_user['user_id'],
                    'mdb_finish_name' => $this->com_user['user_alias'], 
                    'mdd_finish' => date('Y-m-d H:i:s'));
                    $where = array('process_id' => $this->input->post('process_id',true));
                    $this->m_pimpinan->update_surat_lembur_process($params, $where);
                    // insert new flow revisian pegawai_izin_process
                    $params = array(
                        'process_id' => $this->m_pimpinan->get_microtime(),
                        'overtime_id' => $overtime_id,                         
                        'flow_id' => $this->prev_flow_id, 
                        'flow_revisi_id' => $this->now_flow_id,
                        'process_references_id' => $process_references_id,
                        'action_st' => 'process', 
                        'process_st' => 'waiting', 
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_pimpinan->insert_surat_lembur_process($params);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diproses");
                // default
                redirect('kepegawaian/lembur/pimpinan/');
            } else {
                // notification
                $this->tnotification->sent_notification("error", "Data gagal diproses");
            }
        } else {
            // notification
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        // default
        redirect('kepegawaian/lembur/pimpinan/approval/' . $this->input->post('process_id'));
    }

}