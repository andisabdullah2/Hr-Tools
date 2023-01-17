<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jabatan_fungsional extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_jabatan_fungsional');       
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_fungsional/list.html");
        // get search parameter
        $search = $this->tsession->userdata('jabatan_search');
        $this->smarty->assign("search", $search);
        // search parameters
        //fix Message: Illegal string offset 'jabatan_fungsional_id'
        if($search){
            $search['jabatan_fungsional_id'] = empty($search['jabatan_fungsional_id']) ? '%' : '%' . $search['jabatan_fungsional_id'] . '%';
        } else {
            $search = array(
                'jabatan_fungsional_id' => '%%'
            );
        }
        $params = array($search['jabatan_fungsional_id']);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/jabatan_fungsional/index/");
        $config['total_rows'] = $this->m_jabatan_fungsional->get_total_jabatan_fungsional($params);
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
        $params = array($search['jabatan_fungsional_id'],($start - 1), $config['per_page']);
        $data = $this->m_jabatan_fungsional->get_jabatan_fungsional_limit($params);
        $this->smarty->assign("rs_jabatan", $data);
        $this->smarty->assign("rs_id", $data);
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
            $this->tsession->unset_userdata('jabatan_search');
        } else {
            $params = array(
                "jabatan_fungsional_id" => $this->input->post("jabatan_fungsional_id"),
            );
            $this->tsession->set_userdata("jabatan_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/jabatan_fungsional");
    }    

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_fungsional/add.html");
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
        $this->tnotification->set_rules('jabatan_nama', 'Nama Jabatan', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('jabatan_alias', 'Singkatan', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('jabatan_level', 'Level', 'trim|required|maxlength[3]');
        $this->tnotification->set_rules('jabatan_keterangan', 'Keterangan', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $jabatan_fungsional_id = $this->m_jabatan_fungsional->generate_jabatan_fungsional_id();
            $params = array(
                'jabatan_fungsional_id' => $jabatan_fungsional_id,
                'jabatan_nama' => $this->input->post('jabatan_nama',TRUE),
                'jabatan_alias' => $this->input->post('jabatan_alias',TRUE),
                'jabatan_level' => $this->input->post('jabatan_level',TRUE),
                'jabatan_keterangan' => $this->input->post('jabatan_keterangan',TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_jabatan_fungsional->insert_jabatan($params)) {
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
        redirect("kepegawaian/master/jabatan_fungsional/add");
    }

    // edit form
    public function edit($jabatan_fungsional_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_fungsional/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->m_jabatan_fungsional->get_detail_jabatan_by_id($jabatan_fungsional_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // // // cek input
        $this->tnotification->set_rules('jabatan_fungsional_id', 'ID Jabatan', 'trim|required|maxlength[15]');
        $this->tnotification->set_rules('jabatan_nama', 'Nama Jabatan', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('jabatan_alias', 'Singkatan', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('jabatan_level', 'Level', 'trim|required|maxlength[3]');
        $this->tnotification->set_rules('jabatan_keterangan', 'Induk', 'trim');
        // // // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'jabatan_nama' => $this->input->post('jabatan_nama'),
                'jabatan_alias' => $this->input->post('jabatan_alias'),
                'jabatan_level' => $this->input->post('jabatan_level'),
                'jabatan_keterangan' => $this->input->post('jabatan_keterangan'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array('jabatan_fungsional_id' => $this->input->post('jabatan_fungsional_id',true));            
            // update
            if ($this->m_jabatan_fungsional->update_jabatan($params, $where)) {
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
        //default redirect
        redirect("kepegawaian/master/jabatan_fungsional/edit/" . $this->input->post('jabatan_fungsional_id'));
    }
	
    // delete page
    public function delete($jabatan_fungsional_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_fungsional/delete.html");
        // get data
        $result = $this->m_jabatan_fungsional->get_detail_jabatan_by_id($jabatan_fungsional_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/jabatan_fungsional/");
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
        $this->tnotification->set_rules('jabatan_fungsional_id', 'Jabatan ID', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'jabatan_fungsional_id' => $this->input->post('jabatan_fungsional_id', TRUE)
            );
            // delete
            if ($this->m_jabatan_fungsional->delete_jabatan_fungsional($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                redirect("kepegawaian/master/jabatan_fungsional");
            } else {
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
            redirect("kepegawaian/master/jabatan_fungsional/delete/" . $this->input->post('jabatan_fungsional_id'));
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            redirect("kepegawaian/master/jabatan_fungsional/delete/" . $this->input->post('jabatan_fungsional_id'));
        }
    }                
}
