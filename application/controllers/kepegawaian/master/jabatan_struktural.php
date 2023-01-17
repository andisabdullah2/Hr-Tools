<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jabatan_struktural extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_jabatan_struktural');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_struktural/list.html");
        // get search parameter
        $search = $this->tsession->userdata('jabatan_search');
        $this->smarty->assign("search", $search);
        // search parameters
        if($search){
            $search['struktur_cd'] = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        } else {
            $search = array(
                'struktur_cd' => '%%',
                
            );
        }
        
        $params = array($search['struktur_cd']);        
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/jabatan_struktural/index/");
        $config['total_rows'] = $this->M_jabatan_struktural->get_total_jabatan_struktural($params);
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
        $this->smarty->assign("total_data", $config['total_rows']);
        $this->smarty->assign("total_data_presented", 0);
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list data
        $params = array($search['struktur_cd'],($start - 1), $config['per_page']);
        $data = $this->M_jabatan_struktural->get_data_jabatan_struktural_limit($params);
        $this->smarty->assign("rs_id", $data);
        $this->smarty->assign("rs_department", $this->M_jabatan_struktural->get_all_unit_kerja());
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
                "struktur_cd" => $this->input->post("struktur_cd"),
            );
            $this->tsession->set_userdata("jabatan_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/jabatan_struktural");
    }     
    
    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_struktural/add.html");
        $this->smarty->assign("rs_struktur", $this->M_jabatan_struktural->get_all_unit_kerja()); 
                 
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
        $this->tnotification->set_rules('jabatan_nama', 'Nama Struktur', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('jabatan_alias', 'Singkatan', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('jabatan_level', 'Level', 'trim|required|maxlength[3]');
        $this->tnotification->set_rules('struktur_cd', 'Induk', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('jabatan_keterangan', 'Induk', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $jabatan_struktural_id = $this->M_jabatan_struktural->generate_jabatan_struktural_id($this->input->post('struktur_cd'));
            $params = array(
                'jabatan_struktural_id' => $jabatan_struktural_id,
                'jabatan_nama' => $this->input->post('jabatan_nama',true),
                'jabatan_alias' => $this->input->post('jabatan_alias',true),
                'jabatan_level' => $this->input->post('jabatan_level',true),
                'struktur_cd' => $this->input->post('struktur_cd',true),
                'jabatan_keterangan' => $this->input->post('jabatan_keterangan',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_jabatan_struktural->insert_jabatan_struktural($params)) {
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
        redirect("kepegawaian/master/jabatan_struktural/add");
    }

    // edit form
    public function edit($jabatan_struktural_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_struktural/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->M_jabatan_struktural->get_detail_jabatan_by_id($jabatan_struktural_id));
        $this->smarty->assign("rs_struktur", $this->M_jabatan_struktural->get_all_unit_kerja());
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
        // cek input
        $this->tnotification->set_rules('jabatan_struktural_id', 'ID Jabatan', 'trim|required|maxlength[15]');
        $this->tnotification->set_rules('jabatan_nama', 'Nama Jabatan', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('jabatan_alias', 'Singkatan', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('jabatan_level', 'Level', 'trim|required|maxlength[3]');
        $this->tnotification->set_rules('struktur_cd', 'Induk', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('jabatan_keterangan', 'Induk', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // parameter
            $params = array(
                'jabatan_nama' => $this->input->post('jabatan_nama',true),
                'jabatan_alias' => $this->input->post('jabatan_alias',true),
                'jabatan_level' => $this->input->post('jabatan_level',true),
                'struktur_cd' => $this->input->post('struktur_cd',true),
                'jabatan_keterangan' => $this->input->post('jabatan_keterangan',true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id')            
            );
            // update
            if ($this->M_jabatan_struktural->update_jabatan($params, $where)) {
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
        redirect("kepegawaian/master/jabatan_struktural/edit/" . $this->input->post('jabatan_struktural_id'));
    }
	
    // delete page
    public function delete($jabatan_struktural_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jabatan_struktural/delete.html");
        // get data
        $result = $this->M_jabatan_struktural->get_detail_jabatan_by_id($jabatan_struktural_id);
        $this->smarty->assign("result", $result);
        $this->smarty->assign("rs_struktur", $this->M_jabatan_struktural->get_all_unit_kerja());
        // $induk = array(
        //     '001.00.00' => "PT. TIME EXCELINDO",
        //     '001.01.00' => "PT. TIME EXCELINDO -> SOFTWARE DEVELOPMENT",
        //     '002.00.00' => "BMT AL-MADINA",
        //     '003.00.00' => "CV. TERA TEKNO SOLUSI",
        //     '004.00.00' => "CV. ADIDAYA PERKASA TEKNOLOGI",
        //     '005.00.00' => "PT. FORTIS SOLUTION",
            
        // );
        // $this->smarty->assign("indukstruktur",$induk);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/jabatan_struktural/");
        }
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('jabatan_struktural_id', 'ID Jabatan Struktural', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id', TRUE)
            );
            // delete
            if ($this->M_jabatan_struktural->delete_jabatan_struktural($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                redirect("kepegawaian/master/jabatan_struktural");
            } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            redirect("kepegawaian/master/jabatan_struktural/delete/" . $this->input->post('jabatan_struktural_id'));
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            redirect("kepegawaian/master/jabatan_struktural/delete/" . $this->input->post('jabatan_struktural_id'));
        }
    }                
}
