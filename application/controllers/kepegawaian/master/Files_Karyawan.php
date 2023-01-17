<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class Files_karyawan extends ApplicationBase
{

    //contructor
    public function __construct()
    {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_files_karyawan');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index()
    {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Files_Karyawan/list.html");
        // get search parameter

        $search = $this->tsession->userdata('files_search');
        $this->smarty->assign("search", $search);
        // search parameters /error
        if ($search) {
            $search['file_title'] = empty($search['file_title']) ? '%' : '%' . $search['file_title'] . '%';
        } else {
            $search = array(
                'file_title' => '%%',
            );
        }

        $params = array($search['file_title']);

        // pagination
        $config['base_url'] = site_url("kepegawaian/master/Files_Karyawan/index/");
        $config['total_rows'] = $this->M_files_karyawan->get_total_files(array($params));
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
        $params = array($search['file_title'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_files_karyawan->get_all_files_by_limit($params));
        // $this->smarty->assign("rs_all", $this->M_files_karyawan->get_all_files());
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }
    // proses pencarian
    public function proses_cari()
    {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('files_search');
        } else {
            $params = array(
                "file_title" => $this->input->post("file_title"),
            );
            $this->tsession->set_userdata("files_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/Files_karyawan");
    }


    // add form
    public function add()
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Files_Karyawan/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //department list
        $this->smarty->assign("rs_files", $this->M_files_karyawan->get_all_files());


        // output
        parent::display();
    }

    // add process
    public function add_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('file_field', 'file field', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('file_title', 'file title', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('file_desc', 'file desc', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('file_size', 'file size', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('file_allowed', 'file allowed', 'trim|maxlength[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $pegawai_files_id = $this->M_files_karyawan->generate_pegawai_files_id();
            $params = array(
                'pegawai_files_id' => $pegawai_files_id,
                'file_field' => $this->input->post('file_field', TRUE),
                'file_title' => $this->input->post('file_title', TRUE),
                'file_desc' => $this->input->post('file_desc', TRUE),
                'file_size' => $this->input->post('file_size', TRUE),
                'file_allowed' => $this->input->post('file_allowed', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_files_karyawan->insert_files($params)) {
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
        redirect("kepegawaian/master/Files_karyawan/add/");
    }



    // edit form
    public function edit($pegawai_files_id = "")
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Files_karyawan/edit.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // detail Files
        $this->smarty->assign("result", $this->M_files_karyawan->get_detail_files_by_id($pegawai_files_id));
        // output
        parent::display();
    }

    // edit proses
    public function edit_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('pegawai_files_id', 'Field ID', 'trim|required|maxlength[30]');
        $this->tnotification->set_rules('file_field', 'file field', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('file_title', 'file title', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('file_desc', 'file desc', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('file_size', 'file size', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('file_allowed', 'file allowed', 'trim|maxlength[100]');
        // process
        if ($this->tnotification->run() !== false) {
            $config['allowed_types']        = '';
            $params = array(
                'pegawai_files_id' => $this->input->post('pegawai_files_id', TRUE),
                'file_field' => $this->input->post('file_field', TRUE),
                'file_title' => $this->input->post('file_title', TRUE),
                'file_desc' => $this->input->post('file_desc', TRUE),
                'file_size' => $this->input->post('file_size', TRUE),
                'file_allowed' => $this->input->post('file_allowed', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'pegawai_files_id' => $this->input->post('pegawai_files_id', true),
            );
            // update
            if ($this->M_files_karyawan->update_files($params, $where)) {
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
        redirect("kepegawaian/master/Files_karyawan/edit/" . $this->input->post('pegawai_files_id', TRUE));
    }

    // delete page
    public function delete($pegawai_files_id = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Files_karyawan/delete.html");
        // get data
        $result = $this->M_files_karyawan->get_detail_files_by_id($pegawai_files_id);
        $this->smarty->assign("id", $result);
        // check
        if (empty($result)) {
            // default error
            // $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/Files_karyawan/");
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
            $this->tnotification->set_rules('pegawai_files_id', 'Field ID', 'trim|required|maxlength[30]');
            // process
            if ($this->tnotification->run() !== FALSE) {
                $params = array(
                    'pegawai_files_id' => $this->input->post('pegawai_files_id', TRUE)
                );
                // delete
                if ($this->M_files_karyawan->delete($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                    // default redirect
                    redirect("kepegawaian/master/Files_karyawan/delete");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal dihapus");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
            redirect("kepegawaian/master/Files_karyawan/delete/" . $this->input->post('pegawai_files_id'));
        }
        
}
