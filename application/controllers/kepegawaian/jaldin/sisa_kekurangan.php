<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class sisa_kekurangan extends ApplicationBase {

    // my flow
    private $now_flow_id = '14009';
    private $next_flow_id = "";
    private $prev_flow_id = '14008';

    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/jaldin/m_sisa_kekurangan');
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
        //detail pegawai
        $this->pegawai = $this->m_sisa_kekurangan->get_detail_pegawai(array($this->com_user['user_id']));
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/sisa_kekurangan/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_sisa_kekurangan->get_list_tahun());
        // bulan
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_month('in'));
        // get search parameter
        $search = $this->tsession->userdata('jaldin_sisa_kekurangan_search');
        // search parameters
        $nama = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%';
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/jaldin/sisa_kekurangan/index/");
        $params = array($this->pegawai['struktur_cd'], $search['tahun'], $bulan, $nama, $this->now_flow_id);
        $config['total_rows'] = $this->m_sisa_kekurangan->get_total_spt($params);
        $config['uri_segment'] = 5;
        $config['per_page'] = 50;
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
        $params = array($this->pegawai['struktur_cd'], $search['tahun'], $bulan, $nama, $this->now_flow_id, ($start - 1), $config['per_page']);
        $rs_id = $this->m_sisa_kekurangan->get_list_spt($params);
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
            $this->tsession->unset_userdata('jaldin_sisa_kekurangan_search');
        } else {
            $params = array(
                "nama" => $this->input->post("nama", true),
                "bulan" => $this->input->post("bulan", true),
                "tahun" => $this->input->post("tahun", true),
            );
            $this->tsession->set_userdata("jaldin_sisa_kekurangan_search", $params);
        }
        // redirect
        redirect("kepegawaian/jaldin/sisa_kekurangan/");
    }

    // approval
    public function approval($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/sisa_kekurangan/approval.html");
         // get detail data
        $detail = $this->m_sisa_kekurangan->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/sisa_kekurangan");
        }
        $this->smarty->assign("detail", $detail);
        //get list advance
        $rs_id = $this->m_sisa_kekurangan->get_list_advance_by_spt($spt_id);
        $this->smarty->assign("rs_id", $rs_id);
        //get list lpj
        $rs_lpj = $this->m_sisa_kekurangan->get_list_lpj_by_spt($spt_id);
        $this->smarty->assign("rs_lpj", $rs_lpj);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // approval process
    public function approval_process() {
        // set page rule
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('spt_id', 'SPT ID', 'trim|required');
        // process
        if ($this->tnotification->run() !== false) {
            $process_id = $this->input->post('process_id', true);
            $spt_id = $this->input->post('spt_id', true);
             // update flow
            $params = array(
                'process_st'        => 'approve',
                'action_st'         => 'done',
                'mdb_finish'        => $this->com_user['user_id'],
                'mdb_finish_name'   => $this->com_user['user_alias'],
                'mdd_finish'        => date('Y-m-d H:m:s')
            );
            $where = array(
                'process_id'    => $process_id,
                'spt_id'        => $spt_id
            );
            if ($this->m_sisa_kekurangan->update_flow($params, $where)) {
                //update status spt
                $params = array(
                    'spt_status'    => 'approved',
                    'mdb'       => $this->com_user['user_id'],
                    'mdb_name'  => $this->com_user['user_alias'],
                    'mdd'       => date("Y-m-d H:i:s"),
                );
                $where = array('spt_id' => $this->input->post('spt_id'));
                $this->m_sisa_kekurangan->update_spt($params, $where);
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disubmit.");
                // redirect
                redirect('kepegawaian/jaldin/sisa_kekurangan');
            } else{
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disubmit.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        // default
        redirect('kepegawaian/jaldin/sisa_kekurangan/approval/' . $this->input->post('spt_id',true) . '/' . $this->input->post('process_id',true));
    }

}
