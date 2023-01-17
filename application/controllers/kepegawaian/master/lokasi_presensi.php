<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class lokasi_presensi extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_lokasi_presensi');
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
        $this->smarty->assign("template_content", "kepegawaian/master/lokasi_presensi/list.html");
        // get search parameter
        $search = $this->tsession->userdata('lokasi_presensi_search');         
        $this->smarty->assign("search", $search);
        // search parameters                
        $search_param['nama'] = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%' ;                     
        $params = array($search_param['nama']);          
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/lokasi_presensi/index/");
        $config['total_rows'] = $this->m_lokasi_presensi->get_total_lokasi_presensi($params);        
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
        $this->smarty->assign("rs_lokasi", $this->m_lokasi_presensi->get_lokasi_presensi_limit($params));                    
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
            $this->tsession->unset_userdata('lokasi_presensi_search');            
        } else {
            $params = array(                
                "nama" => $this->input->post("nama")                
            );
            $this->tsession->set_userdata('lokasi_presensi_search', $params);                                    
        }        
        // redirect
        redirect("kepegawaian/master/lokasi_presensi");
    }    

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/lokasi_presensi/add.html");
        // list lokasi
        $this->smarty->assign("rs_lokasi", $this->m_lokasi_presensi->get_list_lokasi());
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
        $this->tnotification->set_rules('nama', 'Lokasi', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('latitude', 'Latitude', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('longitude', 'Longitude', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('maks_jarak', 'Maks Jarak', 'trim|required|maxlength[20]');                              
        // process
        if ($this->tnotification->run() !== FALSE) {
            $lokasi_presensi_id = $this->m_lokasi_presensi->generate_lokasi_presensi_id();                        
            $params = array(
                'lokasi_id'         => $lokasi_presensi_id,                
                'nama'              => $this->input->post('nama',TRUE),
                'deskripsi'         => $this->input->post('deskripsi',TRUE),                
                'latitude'          => $this->input->post('latitude',TRUE),                
                'longitude'         => $this->input->post('longitude',TRUE),                
                'maks_jarak'        => $this->input->post('maks_jarak',TRUE),                
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_lokasi_presensi->insert_lokasi_presensi($params)) {
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
        redirect("kepegawaian/master/lokasi_presensi/add");
    }

    // edit form
    public function edit($lokasi_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/lokasi_presensi/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->m_lokasi_presensi->get_detail_lokasi_presensi_by_id($lokasi_id));
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
        $this->tnotification->set_rules('nama', 'Lokasi', 'trim|required|maxlength[20]');
        $this->tnotification->set_rules('deskripsi', 'Deskripsi', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('latitude', 'Latitude', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('longitude', 'Longitude', 'trim|required|maxlength[20]');                              
        $this->tnotification->set_rules('maks_jarak', 'Maks Jarak', 'trim|required|maxlength[20]');             
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'lokasi_id'         => $this->input->post('lokasi_id', TRUE),
                'nama'              => $this->input->post('nama',TRUE),
                'deskripsi'         => $this->input->post('deskripsi',TRUE),                
                'latitude'          => $this->input->post('latitude',TRUE),                
                'longitude'         => $this->input->post('longitude',TRUE),                
                'maks_jarak'        => $this->input->post('maks_jarak',TRUE),                
                'mdb'               => $this->com_user['user_id'],
                'mdb_name'          => $this->com_user['user_name'],
                'mdd'               => date("Y-m-d H:i:s")
            );
            $where = array(
                'lokasi_id' => $this->input->post('lokasi_id', true),
            );
            // update
            if ($this->m_lokasi_presensi->update_lokasi_presensi($params, $where)) {
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
        redirect("kepegawaian/master/lokasi_presensi/edit/" . $this->input->post('lokasi_id', true));
    }
	
    // delete page
    public function delete($lokasi_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/lokasi_presensi/delete.html");
        // get data
        $result = $this->m_lokasi_presensi->get_detail_lokasi_presensi_by_id($lokasi_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/lokasi_presensi/");
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
        $this->tnotification->set_rules('lokasi_id', 'ID Lokasi', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'lokasi_id' => $this->input->post('lokasi_id', TRUE)
            );
            // delete
            if ($this->m_lokasi_presensi->delete_lokasi_presensi($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/lokasi_presensi");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/lokasi_presensi/delete/" . $this->input->post('lokasi_id'));
    }                
 }
