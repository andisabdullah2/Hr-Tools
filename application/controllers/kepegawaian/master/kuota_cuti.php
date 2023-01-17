<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class kuota_cuti extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        //load model
        $this->load->model('kepegawaian/master/m_kuota_cuti');
        $this->load->model('m_preferences');
        //load library
        $this->load->library('encrypt');
        $this->load->library('pagination');
        $this->load->library('tnotification');
        $this->load->library('tcpdf');
    }

    // list view
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/kuota_cuti/list.html");
        // get search parameter
        $search = $this->tsession->userdata('employee_search');
        $this->smarty->assign("search", $search);
        // search parameters
        $search_param['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $search_param['tahun'] = empty($search['tahun']) ? '%' : $search['tahun'];
        $params = array($search_param['full_name'], $search_param['tahun']);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/kuota_cuti/index/");
        $config['total_rows'] = $this->m_kuota_cuti->get_total_karyawan($params);
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
        $params = array($search_param['full_name'], $search_param['tahun'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_kuota_cuti->get_all_karyawan_limit($params));
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
            $this->tsession->unset_userdata('employee_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "tahun" => $this->input->post("tahun")
            );
            $this->tsession->set_userdata("employee_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/kuota_cuti");
    }
    
    public function add() {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/kuota_cuti/add.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $this->smarty->assign("rs_pegawai", $this->m_kuota_cuti->get_list_pegawai());        
        $this->smarty->assign("rs_cuti", $this->m_kuota_cuti->get_list_jenis_cuti());        
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    
    public function add_process($user_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('tahun', 'Tahun', 'required|max_length[4]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Cuti', 'required|trim');
        $this->tnotification->set_rules('total', 'Total Kuota', 'required|max_length[3]|trim');
        // // process
        if ($this->tnotification->run() !== false) {
            $kuota_set_cuti = $this->m_kuota_cuti->get_min_max_jenis_cuti($this->input->post('jenis_id', true));
            $min_kuota = $kuota_set_cuti['jumlah_cuti_min']; $max_kuota = $kuota_set_cuti['jumlah_cuti_max'];
            if($this->input->post('total', true) < $min_kuota || $this->input->post('total', true) > $max_kuota){
                if($this->input->post('total', true) < $min_kuota){
                        $this->tnotification->sent_notification("error", "Kuota minimal cuti ".$kuota_set_cuti['jenis_cuti']." adalah ".$min_kuota);
                } else {
                        $this->tnotification->sent_notification("error", "Kuota maksimal cuti ".$kuota_set_cuti['jenis_cuti']." adalah ".$max_kuota);
                }
                redirect("kepegawaian/master/kuota_cuti/add/");
            }
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'tahun' => $this->input->post('tahun', true),
                'jenis_id' => $this->input->post('jenis_id', true),
                'total' => $this->input->post('total', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            if ($this->m_kuota_cuti->insert_pegawai_kuota_cuti($params)) {
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
        redirect("kepegawaian/master/kuota_cuti/add/");        
    }        
    
    public function edit($user_id = "",$tahun = "",$jenis = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/kuota_cuti/edit.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $params = array($user_id, $tahun, $jenis);
        $result = $this->m_kuota_cuti->get_detail_kuota_cuti_pegawai($params);
        $this->smarty->assign("rs_pegawai", $this->m_kuota_cuti->get_list_pegawai());        
        $this->smarty->assign("rs_cuti", $this->m_kuota_cuti->get_list_jenis_cuti());                
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/kuota_cuti");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function edit_process($user_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('tahun', 'Tahun', 'required|max_length[4]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Cuti', 'required|trim');
        $this->tnotification->set_rules('total', 'Total Kuota', 'required|max_length[3]|trim');
        // // process
        if ($this->tnotification->run() !== false) {
            $kuota_set_cuti = $this->m_kuota_cuti->get_min_max_jenis_cuti($this->input->post('jenis_id', true));
            $min_kuota = $kuota_set_cuti['jumlah_cuti_min']; $max_kuota = $kuota_set_cuti['jumlah_cuti_max'];
            if($this->input->post('total', true) < $min_kuota || $this->input->post('total', true) > $max_kuota){
                if($this->input->post('total', true) < $min_kuota){
                        $this->tnotification->sent_notification("error", "Kuota minimal cuti ".$kuota_set_cuti['jenis_cuti']." adalah ".$min_kuota);
                } else {
                        $this->tnotification->sent_notification("error", "Kuota maksimal cuti ".$kuota_set_cuti['jenis_cuti']." adalah ".$max_kuota);
                }
                redirect("kepegawaian/master/kuota_cuti/edit/".$this->input->post('user_id')."/".$this->input->post('tahun')."/".$this->input->post('jenis_id'));
            }
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'tahun' => $this->input->post('tahun', true),
                'jenis_id' => $this->input->post('jenis_id', true),
                'total' => $this->input->post('total', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            $where = array('user_id'=>$this->input->post('user_id', true),'tahun'=>$this->input->post('tahun_old', true),'jenis_id'=>$this->input->post('jenis_id_old', true),);
            if ($this->m_kuota_cuti->update_pegawai_cuti_kuota($params, $where)) {
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
        redirect("kepegawaian/master/kuota_cuti/edit/".$this->input->post('user_id')."/".$this->input->post('tahun')."/".$this->input->post('jenis_id'));
    }            
    
    public function delete($user_id = "", $tahun = "", $jenis = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/kuota_cuti/delete.html");
        // get detail employee by id
        $params = array($user_id, $tahun, $jenis);        
        $result = $this->m_kuota_cuti->get_detail_kuota_cuti_pegawai($params);
        if($user_id == "" || empty($result)) {
                $this->tnotification->sent_notification("error", "Data tidak ada.");
                redirect("kepegawaian/master/kuota_cuti/");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function delete_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array($this->input->post('user_id'),$this->input->post('tahun'),$this->input->post('jenis_id'), );
            // update
            if ($this->m_kuota_cuti->delete_kuota_cuti_pegawai($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/kuota_cuti/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/kuota_cuti/");
    }    
    
}