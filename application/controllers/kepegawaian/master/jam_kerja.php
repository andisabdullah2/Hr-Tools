<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jam_kerja extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_jam_kerja');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jam_kerja/list.html");
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/jam_kerja/index/");
        $config['total_rows'] = $this->M_jam_kerja->get_total_jam_kerja();
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
        $params = array(($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_jam_kerja->get_jam_kerja_limit($params)); 
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jam_kerja/add.html");
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
        $this->tnotification->set_rules('nama', 'Nama', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('status', 'Status', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jam_mulai', 'Jam Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jam_selesai', 'Jam Selesai', 'trim|required|max_length[10]');
        //create id jam
        $jam_kerja_id = $this->M_jam_kerja->get_jam_kerja_id();
        //process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'jam_kerja_id'         => $jam_kerja_id,
                'nama'                 => $this->input->post('nama', true),
                'keterangan'           => $this->input->post('keterangan', true),
                'status'               => $this->input->post('status', true),
                'jam_mulai'            => $this->input->post('jam_mulai', true),
                'jam_selesai'          => $this->input->post('jam_selesai', true),
                'mdb'                 => $this->com_user['user_id'],
                'mdb_name'              => $this->com_user['user_alias'],
                'mdd'                 => date('Y-m-d h:i:s'),
            );
            $this->M_jam_kerja->insert($params);
           
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/jam_kerja/");
    }

    // edit page
    public function edit($jam_kerja = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jam_kerja/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->M_jam_kerja->get_detail_jam_kerja_by_id($jam_kerja));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process(){
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('nama', 'Nama Jam', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('keterangan', 'Keterangan Jam', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('status', 'Status Jam', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jam_mulai', 'Jam Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jam_selesai', 'Jam Selesai', 'trim|required|max_length[10]');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'nama'                 => $this->input->post('nama', true),
                'keterangan'           => $this->input->post('keterangan', true),
                'status'               => $this->input->post('status', true),
                'jam_mulai'            => $this->input->post('jam_mulai', true),
                'jam_selesai'          => $this->input->post('jam_selesai', true),
                'mdb'                     => $this->com_user['user_id'],
                'mdb_name'               => $this->com_user['user_alias'],
                'mdd'                 => date('Y-m-d h:i:s'),
            );
            $where = array(
                'jam_kerja_id' => $this->input->post('jam_kerja_id', true),
            );
            // update
            if ($this->M_jam_kerja->update($params, $where)) {
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
        redirect("kepegawaian/master/jam_kerja/edit/" . $this->input->post('jam_kerja_id', true));
    }    
    
    //delete page
    public function delete_jam_kerja($jam_kerja_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jam_kerja/delete.html");
        // get data
        $result = $this->M_jam_kerja->get_detail_jam_kerja_by_id($jam_kerja_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/jam_kerja/");
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
        $this->tnotification->set_rules('jam_kerja_id', 'Jabatan ID', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'jam_kerja_id' => $this->input->post('jam_kerja_id', TRUE)
            );
            // delete
            if ($this->M_jam_kerja->delete_jam_kerja($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/jam_kerja");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/jam_kerja/delete/" . $this->input->post('jam_kerja_id'));
    }      
}
