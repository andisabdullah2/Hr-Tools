<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class presensi extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_presensi');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }
    
    // list data
    public function index() {
        // set rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/presensi/list.html");
        // get search parameter
        $search = $this->tsession->userdata('mesin_presensi_search');         
        $this->smarty->assign("search", $search);
        // search parameters        
        $search_param['mesin_ip'] = empty($search['mesin_ip']) ? '%' : '%' . $search['mesin_ip'] . '%' ;        
        $search_param['mesin_lokasi'] = empty($search['mesin_lokasi']) ? '%' : '%' . $search['mesin_lokasi'] . '%' ;                     
        $params = array($search_param['mesin_ip'], $search_param['mesin_lokasi']);          
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/presensi/index/");
        $config['total_rows'] = $this->m_presensi->get_total_mesin_presensi($params);        
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
        $params = array($search_param['mesin_ip'], $search_param['mesin_lokasi'], ($start - 1), $config['per_page']);        
        $this->smarty->assign("rs_mesin", $this->m_presensi->get_mesin_presensi_limit($params));                    
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
            $this->tsession->unset_userdata('mesin_presensi_search');            
        } else {
            $params = array(                
                "mesin_ip" => $this->input->post("mesin_ip"),
                "mesin_lokasi" => $this->input->post("mesin_lokasi")
            );
            $this->tsession->set_userdata('mesin_presensi_search', $params);                                    
        }        
        // redirect
        redirect("kepegawaian/master/presensi");
    }    

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/presensi/add.html");
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
        $this->tnotification->set_rules('mesin_ip', 'IP Mesin', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('mesin_lokasi', 'Lokasi Mesin', 'trim|required|maxlength[20]');                              
        // process
        if ($this->tnotification->run() !== FALSE) {
            $mesin_presensi_id = $this->m_presensi->generate_mesin_presensi_id();            
            $params = array(
                'mesin_id'          => $mesin_presensi_id,
                'mesin_ip'          => $this->input->post('mesin_ip',TRUE),
                'mesin_lokasi'      => $this->input->post('mesin_lokasi',TRUE),                
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_presensi->insert_mesin_presensi($params)) {
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
        redirect("kepegawaian/master/presensi/add");
    }

    // edit form
    public function edit($mesin_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/presensi/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->m_presensi->get_detail_mesin_presensi_by_id($mesin_id));
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
        $this->tnotification->set_rules('mesin_id', 'ID Mesin', 'trim|required');
        $this->tnotification->set_rules('mesin_ip', 'IP Mesin', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('mesin_lokasi', 'Lokasi Mesin', 'trim|required|maxlength[20]');         
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'mesin_id'          => $this->input->post('mesin_id', true),
                'mesin_ip'          => $this->input->post('mesin_ip',TRUE),
                'mesin_lokasi'      => $this->input->post('mesin_lokasi',TRUE),                
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")                
            );
            $where = array(
                'mesin_id' => $this->input->post('mesin_id', true),
            );
            // update
            if ($this->m_presensi->update_mesin_presensi($params, $where)) {
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
        redirect("kepegawaian/master/presensi/edit/" . $this->input->post('mesin_id', true));
    }
	
    // delete page
    public function delete($mesin_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/presensi/delete.html");
        // get data
        $result = $this->m_presensi->get_detail_mesin_presensi_by_id($mesin_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/presensi/");
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
        $this->tnotification->set_rules('mesin_id', 'ID Mesin', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'mesin_id' => $this->input->post('mesin_id', TRUE)
            );
            // delete
            if ($this->m_presensi->delete_mesin_presensi($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/presensi");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/presensi/delete/" . $this->input->post('mesin_id'));
    }                
 }
