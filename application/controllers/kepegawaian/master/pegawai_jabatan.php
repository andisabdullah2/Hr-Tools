<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class pegawai_jabatan extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        //load model
        $this->load->model('kepegawaian/master/m_pegawai_jabatan');
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
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_jabatan/list.html");
        // list department
        $this->smarty->assign("rs_department", $this->m_pegawai_jabatan->get_list_jabatan());
        // get search parameter
        $search = $this->tsession->userdata('employee_search');
        $this->smarty->assign("search", $search);
        // search parameters        
        $search_param['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';        
        $search_param['jabatan_struktural_id'] = empty($search['jabatan_struktural_id']) ? '%' : $search['jabatan_struktural_id'];                
        $params = array($search_param['full_name'], $search_param['jabatan_struktural_id']);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/pegawai_jabatan/index/");
        $config['total_rows'] = $this->m_pegawai_jabatan->get_total_pejabat($params);
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
        $params = array($search_param['full_name'], $search_param['jabatan_struktural_id'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pegawai_jabatan->get_all_pejabat_limit($params));
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
                "jabatan_struktural_id" => $this->input->post("jabatan_struktural_id")
            );
            $this->tsession->set_userdata("employee_search", $params);                                
        }
        // redirect
        redirect("kepegawaian/master/pegawai_jabatan");
    }
    
    public function add() {	
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_jabatan/add.html");
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");                
        // list department
        $this->smarty->assign("rs_jabatan", $this->m_pegawai_jabatan->get_list_jabatan());
        $this->smarty->assign("rs_pegawai", $this->m_pegawai_jabatan->get_list_pegawai());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function add_process($user_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_struktural_id', 'Jabatan Struktural', 'required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai');
        $this->tnotification->set_rules('jabatan_default', 'Set Default');
       // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'jabatan_default' => $this->input->post('jabatan_default', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            if ($this->m_pegawai_jabatan->insert_jabatan_struktur_pegawai($params)) {
                $data_id = $this->db->insert_id();                    
                if($this->input->post('jabatan_default', true) == 1){
                    $this->m_pegawai_jabatan->set_default('pegawai_jabatan_struktural',$this->input->post('user_id',true), $data_id);
                }                
                if($this->input->post('jabatan_status', true) == 1){
                    $this->m_pegawai_jabatan->set_aktif_jabatan_struktural($this->input->post('jabatan_struktural_id', true),$this->input->post('user_id',true), $data_id);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/'.date('Y').'/jabatan_struktural/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // check filepath
                    if(!$this->upload->validate_upload_path()){
                        mkdir($config['upload_path'], DIR_WRITE_MODE);
                    }
                    // process upload images
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $this->m_pegawai_jabatan->update_lampiran_jabatan('pegawai_jabatan_struktural',array($data['file_name'], $this->input->post('data_id')));
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->upload->display_errors());
                    }
                } else {
                       $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                }
                redirect("kepegawaian/master/pegawai_jabatan/add");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_jabatan/add");
    }

    public function edit($data_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_jabatan/edit.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $result = $this->m_pegawai_jabatan->get_detail_struktural_by_id($data_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai_jabatan");
        }
        $this->smarty->assign("rs_jabatan", $this->m_pegawai_jabatan->get_list_jabatan());
        $this->smarty->assign("rs_pegawai", $this->m_pegawai_jabatan->get_list_pegawai());        
        $this->smarty->assign("result", $result);
        //modified
        $this->smarty->assign("modified_by", $this->m_pegawai_jabatan->get_user_alias_by_id($result['mdb']));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function edit_process($data_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_struktural_id', 'Jabatan Struktural', 'required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai');
        $this->tnotification->set_rules('jabatan_default', 'Set Default');
       // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id', false),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'jabatan_default' => $this->input->post('jabatan_default', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            $where = "data_id = " . $this->input->post('data_id', true);
            if ($this->m_pegawai_jabatan->update_jabatan_struktur_pegawai($params, $where)) {
                if($this->input->post('jabatan_default') == 1){
                    $this->m_pegawai_jabatan->set_default('pegawai_jabatan_struktural',$this->input->post('user_id',true), $this->input->post('data_id', true));
                }
                if($this->input->post('jabatan_status') == 1){                        
                    $this->m_pegawai_jabatan->set_aktif_jabatan_struktural($this->input->post('jabatan_struktural_id', false),$this->input->post('user_id',true),$this->input->post('data_id', true));
                }
                if($this->m_pegawai_jabatan->check_jabatan_match_by_id($this->input->post('jabatan_struktural_id', false)) == true && $this->input->post('jabatan_status') == 0){
                    $this->m_pegawai_jabatan->set_non_aktif_jabatan_struktural($this->input->post('user_id', true));
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/'.date('Y').'/jabatan_struktural/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // check filepath
                    if(!$this->upload->validate_upload_path()){
                        mkdir($config['upload_path'], DIR_WRITE_MODE);
                    }                                        
                    // process upload images
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $this->m_pegawai_jabatan->update_lampiran_jabatan('pegawai_jabatan_struktural',array($data['file_name'], $this->input->post('data_id')));
                        //hapus lampiran lama
                        $filepath = $config['upload_path'] . $this->input->post('lampiran_old');
                        if (is_file($filepath)) {
                                unlink($filepath);
                        }
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->upload->display_errors());
                    }
                } else {
                       $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                }
                redirect("kepegawaian/master/pegawai_jabatan/edit/" . $this->input->post('data_id', true));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_jabatan/edit/" . $this->input->post('data_id', true));
    }

    public function delete($data_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_jabatan/delete.html");
        // get detail employee by id
        $result = $this->m_pegawai_jabatan->get_detail_struktural_by_id($data_id);
        if($data_id == "" || empty($result)) {
                $this->tnotification->sent_notification("error", "Data tidak ada.");
                redirect("kepegawaian/master/pegawai_jabatan/");          
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
        $this->tnotification->set_rules('data_id', 'ID Data', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array($this->input->post('data_id'));
            // update
            if ($this->m_pegawai_jabatan->delete($params)) {
                if($this->input->post('jabatan_status', true) == 1){                        
                    $this->m_pegawai_jabatan->set_non_aktif_jabatan_struktural($this->input->post('user_id',true));
                }                       
                // unlink
                $filepath = 'resource/doc/kepegawaian/lampiran/' . $this->input->post('lampiran');
                if (is_file($filepath)) {
                        unlink($filepath);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/pegawai_jabatan/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_jabatan/");
    }    
    
}