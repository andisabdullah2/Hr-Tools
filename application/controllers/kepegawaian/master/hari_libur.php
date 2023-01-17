<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class hari_libur extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_hari_libur');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/hari_libur/list.html");
        // default start & end date
        $date_start = date("Y-01-01");
        $date_end = date("Y-12-31");
        // get search parameter
        $search = $this->tsession->userdata('hari_libur_search');
        if(isset($_SESSION['hari_libur_search'])){
            $bulan = ($search['bulan'] == '') ? "01" : $search['bulan'];
            $tahun = ($search['tahun'] == '') ? date("Y") : $search['tahun'];
            $search['date_start'] = $bulan==date("m") && $tahun == date("Y") ?
                date("$tahun-$bulan-01") : 
                (!empty($hari_libur_search['bulan']) ? date("$tahun-01-01") : date("$tahun-$bulan-01"));
            $search['date_end'] = $bulan==date("m") && $tahun == date("Y") ? 
                date("$tahun-$bulan-t",strtotime($search['date_start'])) : 
                (empty($search['bulan']) ? date("$tahun-12-31") : date("$tahun-$bulan-t",strtotime($search['date_start'])));
            $this->smarty->assign("search", $search);
            $date_start = $search['date_start'];
            $date_end = $search['date_end'];
        }
        $params = array($date_start, $date_end);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/hari_libur/index/");
        $config['total_rows'] = $this->m_hari_libur->get_total_hari_libur($params);
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
        $this->smarty->assign("total_data", $config['total_rows']);
        $this->smarty->assign("total_data_presented", 0);
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list data
        $params = array($date_start,$date_end,($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_hari_libur->get_hari_libur_limit($params));
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('hari_libur_search');
        } else {
            $params = array(
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun")
            );
            $this->tsession->set_userdata('hari_libur_search', $params);
        }
        // redirect
        redirect("kepegawaian/master/hari_libur");
    }    
    
    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/hari_libur/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('libur_tanggal', 'Tanggal Libur', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('libur_jenis', 'Jenis', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('libur_judul', 'Judul', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('libur_keterangan', 'Keterangan', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $hari_libur_id = $this->m_hari_libur->generate_hari_libur_id($this->input->post('libur_tanggal'));
            $params = array(
                'libur_id' => $hari_libur_id,
                'libur_tanggal' => $this->input->post('libur_tanggal',TRUE),
                'libur_jenis' => $this->input->post('libur_jenis',TRUE),
                'libur_judul' => $this->input->post('libur_judul',TRUE),
                'libur_keterangan' => $this->input->post('libur_keterangan',TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_hari_libur->insert_hari_libur($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/hari_libur/add");
    }

    // edit form
    public function edit($hari_libur_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/hari_libur/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->m_hari_libur->get_detail_hari_libur_by_id($hari_libur_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit proses
    public function edit_process(){
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('libur_id', 'ID Libur', 'trim|required');
        $this->tnotification->set_rules('libur_tanggal', 'Tanggal Libur', 'trim|required');
        $this->tnotification->set_rules('libur_jenis', 'Jenis Libur', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('libur_judul', 'Judul Libur', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('libur_keterangan', 'Keterangan', 'trim|required');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'libur_id'          => $this->input->post('libur_id', true),
                'libur_tanggal'     => $this->input->post('libur_tanggal', true),
                'libur_jenis'       => $this->input->post('libur_jenis', true),
                'libur_judul'       => $this->input->post('libur_judul', true),
                'libur_keterangan'  => $this->input->post('libur_keterangan', true),
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_alias'],
                'mdd'               => date('Y-m-d h:i:s'),
            );
            $where = array(
                'libur_id' => $this->input->post('libur_id', true),
            );
            // update
            if ($this->m_hari_libur->update_hari_libur($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/hari_libur/edit/" . $this->input->post('libur_id', true));
    }
	
    // delete page
    public function delete($hari_libur_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/hari_libur/delete.html");
        // get data
        $result = $this->m_hari_libur->get_detail_hari_libur_by_id($hari_libur_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/hari_libur/");
        }
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process() {
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('libur_id', 'ID Libur', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'libur_id' => $this->input->post('libur_id', TRUE)
            );
            // delete
            if ($this->m_hari_libur->delete_hari_libur($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/hari_libur");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/hari_libur/delete/" . $this->input->post('libur_id'));
    }                
}
