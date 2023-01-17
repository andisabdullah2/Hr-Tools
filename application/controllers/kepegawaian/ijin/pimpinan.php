<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pimpinan extends ApplicationBase {

    // my flow
    private $now_flow_id = '12002';
    private $next_flow_id = '12003';
    private $prev_flow_id = '12001';

    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/ijin/m_pimpinan");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/ijin/pimpinan/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_pimpinan->get_list_tahun());
        // bulan
        $flow_id = $this->now_flow_id;
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('pimpinan_search');
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : '%-'.$search['bulan'].'-%';
        $struktur_cd = $this->m_pimpinan->get_unit_kerja_by_user_id($this->com_user['user_id']);
        $search['full_name'] = empty($search['full_name']) ? '' : '' . $search['full_name'] . '';
        $search['tahun'] = $tahun;
        $search['bulan'] = empty($search['bulan']) ? '' : $search['bulan'];
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/ijin/pimpinan/index/");
        $params = array($flow_id, $struktur_cd, $full_name, $bulan, $tahun);
        $config['total_rows'] = $this->m_pimpinan->get_total_permit_by_flow($params);
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
        $params = array($flow_id, $struktur_cd, $full_name, $bulan, $tahun, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pimpinan->get_all_permit_by_flow_limit($params));
        // notification
        $this->tnotification->display_notification();
        //output
        parent::display();
    }

    // search process
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('pimpinan_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name",true),
                "tahun" => $this->input->post("tahun",true),
                "bulan" => $this->input->post("bulan",true)
            );
            $this->tsession->set_userdata("pimpinan_search", $params);
        }
        // redirect
        redirect("kepegawaian/ijin/pimpinan");
    }
    
    // approval form
    public function approval($process_id = "") {
        //set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/ijin/pimpinan/approval.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_pimpinan->get_detail_permit_by_process(array($process_id)));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process approval
    public function approval_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'ID PROSES', 'trim|required');
        $this->tnotification->set_rules('izin_id', 'ID Izin', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            if (true) {
                // get id
                $izin_id = $this->input->post('izin_id',true);
                $process_st = (isset($_POST['approve']) ? 'approve' : 'reject');
                //update flow_now
                $params = array(
                    'catatan' => $this->input->post('izin_catatan',true),
                    'action_st' => 'done', 
                    'process_st' => $process_st, 
                    'mdb_finish' => $this->com_user['user_id'],
                    'mdb_finish_name' => $this->com_user['user_alias'], 
                    'mdd_finish' => date('Y-m-d H:i:s')
                );
                $where = array(
                'process_id' => $this->input->post('process_id',true));
                $this->m_pimpinan->update_flow_by_id($params, $where);
                if ($process_st == 'approve') {
                    // insert next
                    $flow_revisi = null;
                    $references_id = null;
                    if($this->m_pimpinan->check_if_have_references($this->input->post('process_id',true))){
                        $flow_revisi = $this->now_flow_id;
                        $references_id = $this->m_pimpinan->get_process_reference($izin_id);
                    }                    
                    $params = array(
                        'process_id' => $this->m_pimpinan->get_microtime(),
                        'flow_id' => $this->next_flow_id, 
                        'flow_revisi_id' => $flow_revisi, 
                        'process_references_id' => $references_id,                        
                        'izin_id' => $izin_id, 
                        'action_st' => 'process', 
                        'process_st' => 'waiting', 
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_pimpinan->insert_flow($params);
                } elseif ($process_st == 'reject') {
                    // update status pegawai_izin
                    $params = array(
                        'izin_status' => 'rejected',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $where = array('izin_id' => $izin_id);
                    $this->m_pimpinan->update_izin_st($params, $where);
                    // update flow pegawai_izin_process
                    $params = array(
                    'process_st' => 'reject',
                    'action_st' => 'done',
                    'mdb_finish' => $this->com_user['user_id'],
                    'mdb_finish_name' => $this->com_user['user_alias'], 
                    'mdd_finish' => date('Y-m-d H:i:s'));
                    $where = array('process_id' => $this->input->post('process_id',true));
                    $this->m_pimpinan->update_izin_st_izin_process($params, $where);
                    // insert new flow revisian pegawai_izin_process
                    $params = array(
                        'process_id' => $this->m_pimpinan->get_microtime(),
                        'izin_id' => $izin_id,                         
                        'flow_id' => $this->prev_flow_id,
                        'flow_revisi_id' => $this->now_flow_id,
                        'process_references_id' => $this->m_pimpinan->get_process_reference($izin_id),
                        'action_st' => 'process', 
                        'process_st' => 'waiting', 
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_pimpinan->insert_flow($params);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diproses");
                // default
                redirect('kepegawaian/ijin/pimpinan/');
            } else {
                // notification
                $this->tnotification->sent_notification("error", "Data gagal diproses");
            }
        } else {
            // notification
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        // default
        redirect('kepegawaian/ijin/pimpinan/approval/' . $this->input->post('process_id'));
    }    
}
