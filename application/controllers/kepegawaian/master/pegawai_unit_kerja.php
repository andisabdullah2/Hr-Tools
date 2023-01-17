<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class pegawai_unit_kerja extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        //load model
        $this->load->model('kepegawaian/master/m_pegawai_unit_kerja');
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
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_unit_kerja/list.html");
        // list department
        $this->smarty->assign("rs_department", $this->m_pegawai_unit_kerja->get_list_department());
        // get search parameter
        $search = $this->tsession->userdata('employee_search');
        $this->smarty->assign("search", $search);
        //fix Message: Illegal string offset 'struktur_cd' and 'full_name'
        // search parameters
        if($search){
            // search parameters
            $search['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
            $search['struktur_cd'] = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        } else {
            $search = array(
                'full_name' => '%%',
                'struktur_cd' => '%%'
            );
        }
        //fix Message: Illegal string offset 'struktur_cd' and 'full_name'
        $params = array(
            isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
            isset($search['struktur_cd']) && !empty($search['struktur_cd']) ? $search['struktur_cd'] : '%%'
        );
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/pegawai_unit_kerja/index/");
        $config['total_rows'] = $this->m_pegawai_unit_kerja->get_total_karyawan($params);
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
        //fix Message: Illegal string offset 'struktur_cd' and 'full_name'
        $params = array(
            isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
            isset($search['struktur_cd']) && !empty($search['struktur_cd']) ? $search['struktur_cd'] : '%%',
            ($start - 1), $config['per_page']
        );
        $this->smarty->assign("rs_id", $this->m_pegawai_unit_kerja->get_all_karyawan_limit($params));
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
                "struktur_cd" => $this->input->post("struktur_cd")
            );
            $this->tsession->set_userdata("employee_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/pegawai_unit_kerja");
    }
    
    public function add() {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_unit_kerja/add.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $list_unit = $this->m_pegawai_unit_kerja->get_list_department();
        $this->smarty->assign("rs_pegawai", $this->m_pegawai_unit_kerja->get_list_pegawai());        
        $this->smarty->assign("list_jabatan", $list_unit);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    
    public function add_process($user_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK');
        $this->tnotification->set_rules('unit_kerja_status', 'Status', 'required');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'struktur_cd' => $this->input->post('struktur_cd', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'unit_kerja_status' => $this->input->post('unit_kerja_status', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            if ($this->m_pegawai_unit_kerja->insert_unit_kerja_pegawai($params)) {
                        // notification
                        $data_id = $this->db->insert_id();
                        $this->m_pegawai_unit_kerja->set_aktif_unit_kerja($this->input->post('struktur_cd', true),$this->input->post('user_id',true), $data_id);
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                        if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                            // load
                            $this->load->library('upload');
                            // upload config
                            $config['upload_path'] = 'resource/doc/files/kepegawaian/'.date('Y').'/unit_kerja/';
                            $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                            $this->upload->initialize($config);
                            // check filepath
                            if(!$this->upload->validate_upload_path()){
                                mkdir($config['upload_path'], DIR_WRITE_MODE);
                            }                            
                            // process upload images
                            if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                                $data = $this->upload->data();
                                $this->m_pegawai_unit_kerja->update_lampiran_unit_kerja(array($data['file_name'], $data_id));
                                $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                            } else {
                                // jika gagal
                                $this->tnotification->set_error_message($this->tupload->display_errors());
                            }
                        } else {
                                $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                        }
                    redirect("kepegawaian/master/pegawai_unit_kerja/add/" . $this->input->post('user_id'));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_unit_kerja/add/" . $this->input->post('user_id'));        
    }        
    
    public function edit($data_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_unit_kerja/edit.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        //get detail
        $result = $this->m_pegawai_unit_kerja->get_detail_unit_kerja_by_id($data_id);
        $list_unit = $this->m_pegawai_unit_kerja->get_list_department();
        $this->smarty->assign("rs_pegawai", $this->m_pegawai_unit_kerja->get_list_pegawai());        
        $this->smarty->assign("list_jabatan", $list_unit);        
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai_unit_kerja");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function edit_process($data_id = "") {
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('data_id', 'Unit Kerja', 'required|max_length[11]');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'required|max_length[10]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
       // process
        if ($this->tnotification->run() !== false) {
            $data_id = $this->db->insert_id();
            $this->m_pegawai_unit_kerja->set_aktif_unit_kerja($this->input->post('struktur_cd', true),$this->input->post('user_id',true), $this->input->post('data_id', true));
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'data_id' => $this->input->post('data_id', true),
                'struktur_cd' => $this->input->post('struktur_cd', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'unit_kerja_status' => $this->input->post('unit_kerja_status', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['mdb_name'],
            );
            $where = "data_id = " . $this->input->post('data_id', true);
            if ($this->m_pegawai_unit_kerja->update_unit_kerja_pegawai($params, $where)) {
                        // notification
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                        if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                            // load
                            $this->load->library('upload');
                            // upload config
                            $config['upload_path'] = 'resource/doc/files/kepegawaian/'.date('Y').'/unit_kerja/';
                            $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                            $this->upload->initialize($config);
                            // check filepath
                            if(!$this->upload->validate_upload_path()){
                                mkdir($config['upload_path'], DIR_WRITE_MODE);
                            }    
                            // process upload images
                            if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                                $data = $this->upload->data();
                                $this->m_pegawai_unit_kerja->update_lampiran_unit_kerja(array($data['file_name'], $this->input->post('data_id')));
                                //hapus lampiran lama
                                $filepath = $config['upload_path'] . $this->input->post('lampiran_old');
                                if (is_file($filepath)) {
                                        unlink($filepath);
                                }
                                $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                            } else {
                                // jika gagal
                                $this->tnotification->set_error_message($this->tupload->display_errors());
                            }
                        } else {
                               $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                        }
                    redirect("kepegawaian/master/pegawai_unit_kerja/edit/" . $this->input->post('data_id'));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_unit_kerja/edit/" . $this->input->post('data_id'));        
    }            
    
    public function delete($data_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai_unit_kerja/delete.html");
        // get detail employee by id
        $result = $this->m_pegawai_unit_kerja->get_detail_unit_kerja_by_id($data_id);
        if($data_id == "" || empty($result)) {
                $this->tnotification->sent_notification("error", "Data tidak ada.");
                redirect("kepegawaian/master/pegawai/delete_unit_kerja/" . $data_id);          
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
            if ($this->m_pegawai_unit_kerja->delete_unit_kerja_pegawai($params)) {
                // unlink
                $filepath = 'resource/doc/kepegawaian/lampiran/' . $this->input->post('lampiran');
                if (is_file($filepath)) {
                        unlink($filepath);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/pegawai_unit_kerja/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai_unit_kerja/");
    }    
    
}