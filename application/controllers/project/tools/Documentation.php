<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class Documentation extends ApplicationBase {

    //constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('project/tools/M_documentation');
        //load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
        $this->load->library('tupload');
    }

    // list projects
    public function index($doc_id = "", $files_id = '') {
        //set page rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/documentation/list.html");
        // list tahun
        $this->smarty->assign("rs_tahun", $this->M_documentation->get_list_tahun());
        //list file
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        $this->smarty->assign('result', $result);
        $this->smarty->assign('detail', $result);

        $rs_file = $this->M_documentation->get_list_document_by_id(array($doc_id));
        $this->smarty->assign('rs_file', $rs_file);
        // search
        $search = $this->tsession->userdata("project_document_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $client = empty($search['client']) ? '' : '%' . $search['client'] . '%';
        $status = empty($search['status']) ? '%' : '%' . $search['status'] . '%';
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/tools/documentation/index/");
        $params = array($project, $project, $status, $struktur_cd, $client);
        $config['total_rows'] = $this->M_documentation->get_total_project_data($params);
        $config['uri_segment'] = 5;
        $config['per_page'] = 50;
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
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $params = array($project, $project, $status, $tahun, $struktur_cd, ($start - 1), $config['per_page']);
        $rs_id = $this->M_documentation->get_all_project_data($params, $client);
        // get list data
        $this->smarty->assign("rs_id", $rs_id);
        // status project
        // $this->smarty->assign("rs_status", $this->m_preferences->get_preferences_by_group('project'));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("project_document_search");
        } else {
            $params = array(
                "project" => $this->input->post("project"),
                "client" => $this->input->post("client"),
                "tahun" => $this->input->post("tahun"),
                "status" => $this->input->post("status")
            );
            $this->tsession->set_userdata("project_document_search", $params);
        }
        redirect("project/tools/documentation");
    }

    // add
    public function add($project_id = "") {
        //set rules
        $this->_set_page_rule("C");
        //set template content
        $this->smarty->assign("template_content", "project/tools/documentation/add.html");
        //get data
        $this->smarty->assign("rs_projects", $this->M_documentation->get_all_data_projects());
        $this->smarty->assign("rs_jenis", $this->M_documentation->get_all_data_jenis_dokumen());
        $this->smarty->assign("rs_tahun", $this->M_documentation->get_list_tahun());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // add process
    public function add_process() {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('tahun', 'Tahun', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Jenis Dokumen', 'trim|required');
        $this->tnotification->set_rules('doc_notes', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('doc_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $project_id = $this->input->post('project_id', true);
            $doc_id = $this->M_documentation->get_last_doc_id($project_id);
            if(empty($doc_id)){
                $this->tnotification->set_error_message('ID Dokumen tidak tersedia');
            }
            //params
            $params = array(
                'doc_id'        => $doc_id,
                'project_id'    => $this->input->post('project_id', true),
                'jenis_id'      => $this->input->post('jenis_id', true),
                'doc_notes'     => $this->input->post('doc_notes', true),
                'doc_st'        => $this->input->post('doc_st', true),
                'mdb'           => $this->com_user['user_id'],
                'mdb_name'      => $this->com_user['user_name'],
                'mdd'           => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_documentation->insert($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/tools/documentation/detail/" . $doc_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/tools/documentation/add");
    }

     // add file in detail process
    public function add_file_process() {
        // set rules
        $this->_set_page_rule("C");
        // process
        if ($this->tnotification->run() !== FALSE) {
            $project_id = $this->input->post('project_id', true);
            $doc_id = $this->M_documentation->get_last_doc_id($project_id);
            if(empty($doc_id)){
                $this->tnotification->set_error_message('ID Dokumen tidak tersedia');
            }
            //params
            $params = array(
                'doc_id'        => $doc_id,
                'project_id'    => $this->input->post('project_id', true),
                'jenis_id'      => $this->input->post('jenis_id', true),
                'doc_notes'     => $this->input->post('doc_notes', true),
                'doc_st'        => $this->input->post('doc_st', true),
                'mdb'           => $this->com_user['user_id'],
                'mdb_name'      => $this->com_user['user_alias'],
                'mdd'           => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_documentation->insert_file($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/tools/documentation/detail/" . $doc_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/tools/documentation/detail/" . $doc_id);
    }

    // detail
    public function detail($doc_id = "") {
        // set rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/tools/documentation/detail.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        //get detail
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        $this->smarty->assign('result', $result);
        $this->smarty->assign('detail', $result);
        //list file 
        $rs_file = $this->M_documentation->get_list_document_by_id(array($doc_id));
        $this->smarty->assign('rs_file', $rs_file);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    public function file_update_process(){
        // set rules
        $this->_set_page_rule("U");
        //cek input
        $this->tnotification->set_rules('doc_id', 'ID Dokumen', 'trim|required');
        $this->tnotification->set_rules('idlampiran', 'ID Lampiran', 'trim|required');
        $this->tnotification->set_rules('lampiran', 'File', 'trim|required');
        //
        $idlampiran = $this->input->post('idlampiran', true);
        $doc_id = $this->input->post('doc_id', true);
        $files_id = $this->input->post('files_id', true);
        $detail = $this->M_documentation->get_project_document_by_id(array($doc_id));
        //get detail
        if(empty($detail)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        if(!empty($files_id)){
            //delete file
            $files_list = implode(",", $files_id);
            $this->M_documentation->delete_files('files_id NOT IN ('.$files_list.')');
        }
        // echo "<pre>"; print_r($files_id); echo "</pre>"; exit();
        foreach ($idlampiran as $key => $value) {
            // echo "<pre>"; print_r($_FILES[$name]); echo "</pre>";
            $name = 'lampiran'.$value;
            // upload files
            if(!empty($files_id[$value])){
                if (!empty($_FILES[$name]['tmp_name'])) {
                    //delete file lama
                    $file = $this->M_documentation->get_file_by_id($files_id[$value]);
                    if(!empty($file)){
                        $files = $file['file_path'] . $file["file_name"];
                        if (is_file($files)) {
                            unlink($files);
                        }  
                    }
                    // upload config
                    $config['upload_path'] = 'resources/files/project/' . date('Y', strtotime($detail['project_start'])) . '/documentation/' . $doc_id .'/';
                    $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png';
                    $config['max_size'] = '5000';
                    $config['overwrite'] = true;
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload($name)) {
                        $data = $this->tupload->data();
                        $params = array(
                            'file_name' => $data['file_name'],
                            'file_path' => $config['upload_path'],
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $where = array('files_id' => $files_id[$value], 'doc_id' => $doc_id);
                        // print_r($params); exit();
                        $this->M_documentation->update_file($params, $where);
                    } else {
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "File Berhasil Diupload");
                }
            } else {
                if (!empty($_FILES[$name]['tmp_name'])) {
                    // upload config
                    $config['upload_path'] = 'resources/files/project/' . date('Y', strtotime($detail['project_start'])) . '/documentation/' . $doc_id . '/';
                    $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png';
                    $config['max_size'] = '5000';
                    $config['overwrite'] = true;
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload($name)) {
                        $data = $this->tupload->data();
                        // update
                        $files_id = $this->M_documentation->get_microtime();
                        $params = array(
                            'files_id'      => $files_id,
                            'doc_id'        => $doc_id,
                            'file_name' => $data['file_name'],
                            'file_path' => $config['upload_path'],
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                        );
                        $this->M_documentation->insert_file($params);
                    } else {
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "File Berhasil Diupload");
                }else{
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("error", "File gagal Diupload");
                }
            }
        }
        // exit();
        //default redirect
        redirect("project/tools/documentation/detail/" . $doc_id);
    }

    // edit
    public function edit($doc_id) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/tools/documentation/edit.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        //get detail
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        $this->smarty->assign('result', $result);
        $this->smarty->assign('detail', $result);
        //get data
        $this->smarty->assign("rs_projects", $this->M_documentation->get_all_data_projects());
        $this->smarty->assign("rs_jenis", $this->M_documentation->get_all_data_jenis_dokumen());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('doc_id', 'ID Dokumen', 'trim|required');
        $this->tnotification->set_rules('doc_notes', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Dokumen', 'trim|required');
        //doc id
        $doc_id = $this->input->post('doc_id', true);
        //get detail
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //params
            $params = array(
                'project_id'    => $this->input->post('project_id', true),
                'jenis_id'      => $this->input->post('jenis_id', true),
                'doc_notes'     => $this->input->post('doc_notes', true),
                'doc_st'        => $this->input->post('doc_st', true),
                'mdb'           => $this->com_user['user_id'],
                'mdb_name'      => $this->com_user['user_name'],
                'mdd'           => date("Y-m-d H:i:s")
            );
            $where = array('doc_id' => $doc_id);
            // update
            if ($this->M_documentation->update($params, $where)) {
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
        redirect("project/tools/documentation/edit/". $this->input->post('doc_id', true));
    }

    // delete
    public function delete($doc_id) {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/tools/documentation/delete.html");
        //get detail
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        $this->smarty->assign('result', $result);
        $this->smarty->assign('detail', $result);
        //list file 
        $rs_file = $this->M_documentation->get_list_document_by_id(array($doc_id));
        $this->smarty->assign('rs_file', $rs_file);
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
        //cek input
        $this->tnotification->set_rules('doc_id', 'ID Dokumen', 'trim|required');
        //doc id
        $doc_id = $this->input->post('doc_id', true);
        //get detail
        $result = $this->M_documentation->get_project_document_by_id(array($doc_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/documentation/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array('doc_id' => $doc_id);
            // delete
            if ($this->M_documentation->delete($where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // redirect
                redirect("project/tools/documentation/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/tools/documentation/delete/". $this->input->post('doc_id', true));
    }

    // download file
    public function download($doc_id = "", $files_id = '') 
    {
        // set page rules
        $this->_set_page_rule("R");
        // get detail data
        $result = $this->M_documentation->get_file_by_id($files_id);
        if (!empty($result)) {
            $file_path = $result['file_path'] . $result['file_name'];
            if (is_file($file_path)) {
                header('Content-Description: Download File');
                header('Content-Type: application/octet-stream');
                header('Content-Length: ' . filesize($file_path));
                header('Content-Disposition: attachment; filename="' . $result['file_name'] . '"');
                readfile($file_path);
                exit();
            } else {
                $this->tnotification->sent_notification("error", "File tidak ditemukan");
                // default redirect
                redirect("project/tools/documentation/detail/" . $doc_id);
            }
        } else {
            $this->tnotification->sent_notification("error", "File tidak ditemukan");
            // default redirect
            redirect("project/tools/documentation/detail/" . $doc_id);
        }
    }

    // get alamat by suplier
    public function get_project_by_tahun() {
        $id = trim($this->input->post('id', TRUE));
        // get data pegawai
        $project = $this->M_documentation->get_project_by_tahun($id);
        if (empty($project)) {
            
        } else {
            header('Content-Type: application/json');
            echo json_encode($project);
        }
    }

}