<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class Perusahaan extends ApplicationBase
{

    //contructor
    public function __construct()
    {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('project/master/M_perusahaan');
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
        $this->smarty->assign("template_content", "project/master/perusahaan/list.html");

        $search = $this->tsession->userdata('search_kata_kunci');
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }

        // search parameters
        // $kata_kunci = empty($search['perusahaan_nama']) ? NULL : '%' . $search['perusahaan_nama'] . '%';
        $kata_kunci = empty($search['perusahaan_nama']) ? '%' : '%'.$search['perusahaan_nama'].'%';
        // print_r("Kata_kunci: " . $kata_kunci);

        // get list company
        // if (!empty($kata_kunci)) {
        //     $rs_id = $this->M_perusahaan->filter_perusahaan($kata_kunci);
        // } else {
        //     $rs_id = $this->M_perusahaan->get_all_perusahaan();
        // }
        $rs_id = $this->M_perusahaan->filter_perusahaan($kata_kunci);

        $this->smarty->assign("rs_id", $rs_id);
        $this->smarty->assign("total", count($rs_id));


        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add form
    public function add()
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "project/master/perusahaan/add.html");
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('perusahaan_nama', 'Nama Perusahaan', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('perusahaan_alamat', 'Alamat', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('perusahaan_kota', 'Kota', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_propinsi', 'Propinsi', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_email', 'Email', 'trim|required|valid_email|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_telepon', 'Telepon', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('npwp_nomor', 'Nomor NPWP', 'trim|maxlength[50]');
        $this->tnotification->set_rules('npwp_tanggal', 'Tanggal NPWP', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $struktur_cd = $this->M_perusahaan->get_perusahaan_id();
            $params = array(
                'struktur_cd' => $struktur_cd,
                'perusahaan_nama' => $this->input->post('perusahaan_nama', TRUE),
                'perusahaan_alamat' => $this->input->post('perusahaan_alamat', TRUE),
                'perusahaan_kota' => $this->input->post('perusahaan_kota', TRUE),
                'perusahaan_propinsi' => $this->input->post('perusahaan_propinsi', TRUE),
                'perusahaan_email' => $this->input->post('perusahaan_email', TRUE),
                'perusahaan_telepon' => $this->input->post('perusahaan_telepon', TRUE),
                'npwp_nomor' => $this->input->post('npwp_nomor', TRUE),
                'npwp_tanggal' => $this->input->post('npwp_tanggal', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_id'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // update
            if ($this->M_perusahaan->insert($params)) {
                // upload foto
                if (!empty($_FILES['logo_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/images/perusahaan/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = $struktur_cd;
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('logo_file_name')) {
                        $data = $this->tupload->data();
                        $params_logo = array(
                            'logo_file_name' => $data['file_name'],
                            'logo_file_path' => $data['file_path']
                        );
                        $where = array('struktur_cd' => $struktur_cd);
                        $this->M_perusahaan->update($params_logo, $where);
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                }
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
        redirect("project/master/perusahaan/add");
    }

    // edit form
    public function edit($struktur_cd = "")
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/master/perusahaan/edit.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // get data perusahaan by id
        $result = $this->M_perusahaan->get_detail_perusahaan_by_id($struktur_cd);
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('struktur_cd', 'ID Perusahaan', 'trim|required|maxlength[5]');
        $this->tnotification->set_rules('perusahaan_nama', 'Nama Perusahaan', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('perusahaan_alamat', 'Alamat', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('perusahaan_kota', 'Kota', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_propinsi', 'Propinsi', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_email', 'Email', 'trim|required|valid_email|maxlength[50]');
        $this->tnotification->set_rules('perusahaan_telepon', 'Telepon', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('npwp_nomor', 'Nomor NPWP', 'trim|maxlength[50]');
        $this->tnotification->set_rules('npwp_tanggal', 'Tanggal NPWP', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $struktur_cd = $this->input->post('struktur_cd', TRUE);
            $params = array(
                'perusahaan_nama' => $this->input->post('perusahaan_nama', TRUE),
                'perusahaan_alamat' => $this->input->post('perusahaan_alamat', TRUE),
                'perusahaan_kota' => $this->input->post('perusahaan_kota', TRUE),
                'perusahaan_propinsi' => $this->input->post('perusahaan_propinsi', TRUE),
                'perusahaan_email' => $this->input->post('perusahaan_email', TRUE),
                'perusahaan_telepon' => $this->input->post('perusahaan_telepon', TRUE),
                'npwp_nomor' => $this->input->post('npwp_nomor', TRUE),
                'npwp_tanggal' => $this->input->post('npwp_tanggal', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_id'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'struktur_cd' => $struktur_cd
            );
            if ($this->M_perusahaan->update($params, $where)) {
                // upload foto
                if (!empty($_FILES['logo_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/images/perusahaan/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = $struktur_cd;
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('logo_file_name')) {
                        $data = $this->tupload->data();
                        $params_logo = array(
                            'logo_file_name' => $data['file_name'],
                            'logo_file_path' => $data['file_path']
                        );
                        $this->M_perusahaan->update($params_logo, $where);
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                }
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
        redirect("project/master/perusahaan/edit/" . $struktur_cd);
    }

    // delete
    public function delete($struktur_cd = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/master/perusahaan/delete.html");
        // get data
        $result = $this->M_perusahaan->get_detail_perusahaan_by_id($struktur_cd);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("project/master/perusahaan/");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('struktur_cd', 'Perusahaan ID', 'trim|required|max_length[5]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'struktur_cd' => $this->input->post('struktur_cd', TRUE)
            );
            // delete
            if ($this->M_perusahaan->delete($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("project/master/perusahaan/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/master/perusahaan/delete/" . $this->input->post('struktur_cd'));
    }

    //without session
    // public function search()
    // {
    //     //set rule
    //     $this->_set_page_rule("R");
    //     $this->tnotification->set_rules('kata_kunci', 'kata kunci', 'trim|required|maxlength[255]');

    //     //set template content
    //     $this->smarty->assign("template_content", "project/master/perusahaan/list.html");

    //     //set kata kunci
    //     // $params = array(
    //     //     'kata_kunci' => $this->input->post('kata_kunci')
    //     // );
    //     $params = $this->input->post('kata_kunci');

    //     // print_r($kata_kunci);
    //     // print_r($params);

    //     // get list company
    //     // $rs_id = $this->M_perusahaan->get_all_perusahaan_filter($kata_kunci);
    //     $rs_id = $this->M_perusahaan->filter_perusahaan($params);
    //     // print_r($rs_id);

    //     //notif
    //     // if (empty($rs_id)) {
    //     //     // default error
    //     //     $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
    //     //     redirect("project/master/perusahaan/");
    //     // }

    //     $this->smarty->assign("rs_id", $rs_id);
    //     $this->smarty->assign("total", count($rs_id));
    //     //notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    public function search_process()
    {
        // set page rules
        $this->_set_page_rule("R");
        // session
        if ($this->input->post('save') == "Cari") {
            // params
            $params = array(
                "perusahaan_nama" => $this->input->post('perusahaan_nama', TRUE),
            );
            // set session
            $this->tsession->set_userdata("search_kata_kunci", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("search_kata_kunci");
        }
        // redirect
        redirect("project/master/perusahaan/");
    }
}
