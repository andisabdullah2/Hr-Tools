<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class hrd extends ApplicationBase {

    // my flow
    private $now_flow_id = '11003';
    private $next_flow_id = "";
    private $prev_flow_id = '11002';

    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/cuti/m_hrd");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/hrd/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_hrd->get_list_tahun());
        $this->smarty->assign("rs_department", $this->m_hrd->get_list_unit_kerja());        
        // bulan
        $flow_id = $this->now_flow_id;
        $bulan = $this->datetimemanipulation->get_bulan_indonesia();
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('hrd_search');
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : '%-'.$search['bulan'].'-%';
        $struktur_cd = empty($search['struktur_cd']) ? '%' : '' . $search['struktur_cd'] . '';
        $search['full_name'] = empty($search['full_name']) ? '' : '' . $search['full_name'] . '';
        $search['struktur_cd'] = empty($search['struktur_cd']) ? '' : '' . $search['struktur_cd'] . '';
        $search['tahun'] = $tahun;
        $search['bulan'] = empty($search['bulan']) ? '' : $search['bulan'];
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/cuti/hrd/index/");
        $params = array($flow_id, $struktur_cd, $full_name, $bulan, $tahun);
        $config['total_rows'] = $this->m_hrd->get_total_permit_by_flow($params);
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
        $this->smarty->assign("rs_id", $this->m_hrd->get_all_permit_by_flow_limit($params));
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
            $this->tsession->unset_userdata('hrd_search');
        } else {
            $params = array(
                "struktur_cd" => $this->input->post("struktur_cd",true),
                "full_name" => $this->input->post("full_name",true),
                "tahun" => $this->input->post("tahun",true),
                "bulan" => $this->input->post("bulan",true)
            );
            $this->tsession->set_userdata("hrd_search", $params);
        }
        // redirect
        redirect("kepegawaian/cuti/hrd");
    }
    
    // approval form
    public function approval($process_id = "") {
        //set page rule
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/hrd/approval.html");
        // get detail data
        $detail = $this->m_hrd->get_detail_permit_by_process(array($process_id));
        $this->smarty->assign("detail", $detail);
        $this->smarty->assign("catatan", $this->m_hrd->get_catatan_pimpinan(array($detail['cuti_id'],'11002')));
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
        $this->tnotification->set_rules('cuti_id', 'ID Cuti', 'trim|required');
        $this->tnotification->set_rules('cuti_catatan', 'Catatan Cuti', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            if (true) {
                // get id
                $cuti_id = $this->input->post('cuti_id',true);
                $process_st = (isset($_POST['approve']) ? 'approve' : 'reject');
                if ($process_st == 'approve') {
                    // update pegawai_cuti
                    $params = array(
                        'cuti_status' => 'approved',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $where = array('cuti_id' => $cuti_id);
                    $this->m_hrd->update_cuti_st($params, $where);
                    // update pegawai_cuti_process
                    $params = array(
                        'catatan' => $this->input->post('cuti_catatan',true),
                        'process_st' => 'approve',
                        'action_st' => 'done',
                        'mdb_finish' => $this->com_user['user_id'],
                        'mdb_finish_name' => $this->com_user['user_alias'], 
                        'mdd_finish' => date('Y-m-d H:i:s'));
                    $where = array('process_id' => $this->input->post('process_id'));
                    $this->m_hrd->update_cuti_process($params, $where);                    
                } elseif ($process_st == 'reject') {
                    // update status pegawai_cuti
                    $params = array(
                        'cuti_status' => 'rejected',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $where = array('cuti_id' => $cuti_id);
                    $this->m_hrd->update_cuti_st($params, $where);
                    // update status pegawai_cuti_process
                    $params = array(
                        'catatan' => $this->input->post('cuti_catatan',true),
                        'process_st' => 'reject',
                        'action_st' => 'done',
                        'mdb_finish' => $this->com_user['user_id'],
                        'mdb_finish_name' => $this->com_user['user_alias'], 
                        'mdd_finish' => date('Y-m-d H:i:s'));
                    $where = array('process_id' => $this->input->post('process_id'));
                    $this->m_hrd->update_cuti_process($params, $where);                    
                    // insert new flow revisian pegawai_cuti_process
                    $params = array(
                        'process_id' => $this->m_hrd->get_microtime(),
                        'cuti_id' => $cuti_id,
                        'flow_id' => $this->prev_flow_id,
                        'flow_revisi_id' => $this->now_flow_id,
                        'process_references_id' => $this->m_hrd->get_process_reference($cuti_id),
                        'action_st' => 'process', 
                        'process_st' => 'waiting', 
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_hrd->insert_flow($params);                    
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diproses");
                // default
                redirect('kepegawaian/cuti/hrd/');
            } else {
                // notification
                $this->tnotification->sent_notification("error", "Data gagal diproses");
            }
        } else {
            // notification
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        // default
        redirect('kepegawaian/cuti/hrd/approval/' . $this->input->post('process_id'));
    }    
}
