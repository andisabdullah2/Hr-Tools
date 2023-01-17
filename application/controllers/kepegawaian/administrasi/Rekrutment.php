<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

// --

class Rekrutment extends ApplicationBase
{

    // constructor
    public function __construct()
    {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/administrasi/M_rekrutment');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index()
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/list.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('files_search');
        $this->smarty->assign("search", $search);
        // search parameters /error
        if ($search) {
            $search['judul'] = empty($search['judul']) ? '%' : '%' . $search['judul'] . '%';
        } else {
            $search = array(
                'judul' => '%%',
            );
        }

        $params = array($search['judul']);

        // pagination
        $config['base_url'] = site_url("kepegawaian/administrasi/rekrutment/index/");
        $config['total_rows'] = $this->M_rekrutment->get_total_rekrutment(array($params));
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
        $params = array($search['judul'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_rekrutment->get_list_rekrutment($params));
        // $this->smarty->assign("rs_all", $this->M_files_karyawan->get_all_files());
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // search process
    public function search_process()
    {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('files_search');
        } else {
            $params = array(
                "judul" => $this->input->post("judul"),
            );
            $this->tsession->set_userdata("files_search", $params);
        }
        // redirect
        redirect("kepegawaian/administrasi/Rekrutment");
    }

    // add
    public function add()
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/add.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //
        $this->smarty->assign("rs_agenda", $this->M_rekrutment->get_list_agenda());
        $this->smarty->assign("rs_media", $this->M_rekrutment->get_list_media());

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
        $this->tnotification->set_rules('periode_start', 'Periode Start', 'trim|required');
        $this->tnotification->set_rules('periode_end', 'Periode End', 'trim|required');
        $this->tnotification->set_rules('agenda_id', 'Agenda ID', 'trim|required');
        $this->tnotification->set_rules('media_id', 'Media ID', 'trim');
        $this->tnotification->set_rules('jumlah_pelamar', 'Jumlah Pelamar', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $rekrutmen_id = $this->M_rekrutment->generate_rekrutmen_id();
            $params = array(
                'rekrutmen_id' => $rekrutmen_id,
                'periode_start' => $this->input->post('periode_start', TRUE),
                'periode_end' => $this->input->post('periode_end', TRUE),
                'agenda_id' => $this->input->post('agenda_id', TRUE),
                'media_id' => $this->input->post('media_id', TRUE),
                'jumlah_pelamar' => $this->input->post('jumlah_pelamar', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_rekrutment->insert_rekrutmen($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_administrasi/" . $rekrutmen_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/administrasi/Rekrutment");
    }
    // add
    public function edit($rekrutmen_id)
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/edit.html");
        //
        $this->smarty->assign("result", $this->M_rekrutment->get_list_agenda_by_id($rekrutmen_id));
        $this->smarty->assign("rs_agenda", $this->M_rekrutment->get_list_agenda());
        $this->smarty->assign("rs_media", $this->M_rekrutment->get_list_media());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function edit_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('periode_start', 'Periode Start', 'trim|required');
        $this->tnotification->set_rules('periode_end', 'Periode End', 'trim|required');
        $this->tnotification->set_rules('agenda_id', 'Agenda ID', 'trim|required');
        $this->tnotification->set_rules('media_id', 'Media ID', 'trim');
        $this->tnotification->set_rules('jumlah_pelamar', 'Jumlah Pelamar', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {            $params = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', TRUE),
                'periode_start' => $this->input->post('periode_start', TRUE),
                'periode_end' => $this->input->post('periode_end', TRUE),
                'agenda_id' => $this->input->post('agenda_id', TRUE),
                'media_id' => $this->input->post('media_id', TRUE),
                'jumlah_pelamar' => $this->input->post('jumlah_pelamar', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', true),
            );
            // insert
            if ($this->M_rekrutment->update_rekrutment($params,$where)) {
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
        redirect("kepegawaian/administrasi/Rekrutment/edit/" . $this->input->post('rekrutmen_id', TRUE));
    }


    // admnistrasi
    public function ubah_lolos_administrasi($rekrutmen_id)
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/administrasi.html");
        $this->smarty->assign("result", $this->M_rekrutment->get_list_rekrutment_by_id($rekrutmen_id));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function ubah_lolos_administrasi_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('rekrutmen_id', 'rekrutmen_id', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', TRUE),
                'jumlah_lolos_administrasi' => $this->input->post('jumlah_lolos_administrasi', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', true),
            );
            // update
            if ($this->M_rekrutment->update_lolos($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_tes_tulis/" . $this->input->post('rekrutmen_id', TRUE));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_administrasi/" . $this->input->post('rekrutmen_id', TRUE));
    }

    // Tes Tulis
    public function ubah_lolos_tes_tulis($rekrutmen_id)
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/tes_tulis.html");
        $this->smarty->assign("result", $this->M_rekrutment->get_list_rekrutment_by_id($rekrutmen_id));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function ubah_lolos_tes_tulis_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('jumlah_lolos_tulis', 'jumlah_lolos_tulis');
        $this->tnotification->set_rules('rekrutmen_id', 'rekrutmen_id');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', TRUE),
                'jumlah_lolos_tulis' => $this->input->post('jumlah_lolos_tulis', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', true),
            );
            // update
            if ($this->M_rekrutment->update_lolos($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_tes_wawancara/" . $this->input->post('rekrutmen_id', TRUE));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_tes_tulis/" . $this->input->post('rekrutmen_id', TRUE));
    }

    // Tes Wawancara
    public function ubah_lolos_tes_wawancara($rekrutmen_id)
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/administrasi/Rekrutment/tes_wawancara.html");
        $this->smarty->assign("result", $this->M_rekrutment->get_list_rekrutment_by_id($rekrutmen_id));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function ubah_lolos_tes_wawancara_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('rekrutmen_id', 'rekrutmen_id', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', TRUE),
                'jumlah_lolos_wawancara' => $this->input->post('jumlah_lolos_wawancara', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'rekrutmen_id' => $this->input->post('rekrutmen_id', true),
            );
            // update
            if ($this->M_rekrutment->update_lolos($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect("kepegawaian/administrasi/Rekrutment/hasil_akhir/" . $this->input->post('rekrutmen_id', TRUE));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/administrasi/Rekrutment");
        //redirect("kepegawaian/administrasi/Rekrutment/ubah_lolos_tes_wawancara/" . $this->input->post('rekrutmen_id', TRUE));
    }
}
