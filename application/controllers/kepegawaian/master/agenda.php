<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class agenda extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_agenda');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');

        //load js and css leaflet
        $this->smarty->load_style("default/css/leaflet/leaflet.css");
        $this->smarty->load_javascript("resource/themes/default/plugins/leaflet/leaflet.js");
        
    }
    
    // list data
    public function index() {
        // set rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/agenda/list.html");
        // get search parameter
        $search = $this->tsession->userdata('agenda_search');         
        $this->smarty->assign("search", $search);
        // search parameters                
        $search_param['nama'] = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%' ;                     
        $params = array($search_param['nama']);          
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/agenda/index/");
        $config['total_rows'] = $this->M_agenda->get_total_agenda($params);        
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
        $params = array($search_param['nama'], ($start - 1), $config['per_page']);        
        $this->smarty->assign("rs_agenda", $this->M_agenda->get_agenda_limit($params));                    
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
            $this->tsession->unset_userdata('agenda_search');            
        } else {
            $params = array(                
                "nama" => $this->input->post("nama")                
            );
            $this->tsession->set_userdata('agenda_search', $params);                                    
        }        
        // redirect
        redirect("kepegawaian/master/agenda");
    }    

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/agenda/add.html");
        // list agenda
        $this->smarty->assign("rs_agenda", $this->M_agenda->get_list_agenda());
        // load javascript
        //$this->smarty->load_javascript("");        
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
        $this->tnotification->set_rules('judul', 'Judul', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim|required|maxlength[20]');                              
        // process
        if ($this->tnotification->run() !== FALSE) {
            $agenda_id = $this->M_agenda->generate_agenda_id();                        
            $params = array(
                'agenda_id'         => $agenda_id,                
                'judul'              => $this->input->post('judul',TRUE),
                'deskripsi'         => $this->input->post('deskripsi',TRUE),                 
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_agenda->insert_agenda($params)) {
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
        redirect("kepegawaian/master/agenda/add");
    }

    // edit form
    public function edit($agenda_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/agenda/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->M_agenda->get_detail_agenda_by_id($agenda_id));
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
        $this->tnotification->set_rules('judul', 'Judul Agenda', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim|required|maxlength[20]');             
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'agenda_id'         => $this->input->post('agenda_id', TRUE),
                'judul'              => $this->input->post('judul',TRUE),               
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            $where = array(
                'agenda_id' => $this->input->post('agenda_id', true),
            );
            // update
            if ($this->M_agenda->update_agenda($params, $where)) {
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
        redirect("kepegawaian/master/agenda/edit/" . $this->input->post('agenda_id', true));
    }
	
    // delete page
    public function delete($agenda_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/agenda/delete.html");
        // get data
        $result = $this->M_agenda->get_detail_agenda_by_id($agenda_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/agenda/");
        }
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process() {
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('agenda_id', 'ID agenda', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'agenda_id' => $this->input->post('agenda_id', TRUE)
            );
            // delete
            if ($this->M_agenda->delete_agenda($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/agenda");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/agenda/delete/" . $this->input->post('agenda_id'));
    }                
 }
