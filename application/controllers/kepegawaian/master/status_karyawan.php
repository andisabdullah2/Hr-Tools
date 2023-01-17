<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class Status_Karyawan extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        //load model
        $this->load->model('kepegawaian/master/M_status_karyawan');
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
        $this->smarty->assign("template_content", "kepegawaian/master/status_karyawan/list.html");
        // list department
        $this->smarty->assign("rs_department", $this->M_status_karyawan->get_list_jabatan());
        // get search parameter
        $search = $this->tsession->userdata('employee_search');
        $this->smarty->assign("search", $search);
        // search parameters /error
        if($search){
            $search['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
            $search['employee_st'] = empty($search['employee_st']) ? '%' : $search['employee_st'];
        } else {
            $search = array(
                'full_name' => '%%',
                'employee_st' => '%%'
            );
        }

        $params = array($search['full_name'], $search['employee_st']);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/status_karyawan/index/");
        $config['total_rows'] = $this->M_status_karyawan->get_total_karyawan($params);
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
        // get list data error
        $params = array($search['full_name'], $search['employee_st'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_status_karyawan->get_all_karyawan_limit($params));
        $filepath = base_url() . 'resource/doc/kepegawaian/lampiran/';
        $this->smarty->assign("filepath", $filepath);          
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
                "employee_st" => $this->input->post("employee_st")
            );
            $this->tsession->set_userdata("employee_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/status_karyawan");
    }
    
    public function edit($user_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/status_karyawan/edit.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $result = $this->M_status_karyawan->get_detail_status_karyawan_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/status_karyawan");
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
        $this->tnotification->set_rules('employee_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('tahun', 'Tahun Masuk', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('tanggal_keluar', 'Tahun Keluar', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('catatan', 'Catatan', 'trim');
       // // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'pegawai_status' => $this->input->post('employee_st', true),
                'tahun' => $this->input->post('tahun', true),
                'tanggal_keluar' => $this->input->post('tanggal_keluar', true),
                'catatan' => $this->input->post('catatan', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            $where = "user_id = " . $this->input->post('user_id', true);
            if ($this->M_status_karyawan->update_status_karyawan($params, $where)) {
               $params = array($this->input->post('employee_st', true),
                        $this->input->post('tanggal_keluar', true),date('Y-m-d H:i:s'),
                        $this->com_user['user_id'], $this->com_user['mdb_name'], $this->input->post('user_id', true)
                );                    
                $this->M_status_karyawan->update_pegawai_status($params,$this->input->post('tahun', true));
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect("kepegawaian/master/status_karyawan/edit/" . $this->input->post('user_id', true));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/Status_karyawan/edit/" . $this->input->post('user_id', true));
    }
    
}