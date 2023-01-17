<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class Media extends ApplicationBase
{

    //contructor
    public function __construct()
    {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_media');
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
        $this->smarty->assign("template_content", "kepegawaian/master/Media/list.html");
        // get search parameter

        $search = $this->tsession->userdata('files_search');
        $this->smarty->assign("search", $search);
        // search parameters /error
        if ($search) {
            $search['nama'] = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%';
        } else {
            $search = array(
                'nama' => '%%',
            );
        }

        $params = array($search['nama']);

        // pagination
        $config['base_url'] = site_url("kepegawaian/master/Media/index/");
        $config['total_rows'] = $this->M_media->get_total_media(array($params));
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
        $params = array($search['nama'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_media->get_all_media_by_limit($params));
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
                "nama" => $this->input->post("nama"),
            );
            $this->tsession->set_userdata("files_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/Media");
    }


    // add form
    public function add()
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Media/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //department list
        $this->smarty->assign("rs_files", $this->M_media->get_all_files());


        // output
        parent::display();
    }

    // add process
    public function add_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('nama', 'Nama Data ', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('url', 'url', 'trim|required ');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $media_id = $this->M_media->generate_media_id();
            $params = array(
                'media_id' => $media_id,
                'nama' => $this->input->post('nama', TRUE),
                'url' => $this->input->post('url', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_media->insert_media($params)) {
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
        redirect("kepegawaian/master/Media/add/");
    }



    // edit form
    public function edit($media_id = "")
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Media/edit.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // detail Files
        $this->smarty->assign("result", $this->M_media->get_detail_media_by_id($media_id));
        // output
        parent::display();
    }

    // edit proses
    public function edit_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('media_id', 'Media ID', 'trim|required|maxlength[30]');
        $this->tnotification->set_rules('nama', 'Nama Media', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('url', 'URL', 'trim|required|maxlength[100]');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'media_id' => $this->input->post('media_id', TRUE),
                'nama' => $this->input->post('nama', TRUE),
                'url' => $this->input->post('url', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'media_id' => $this->input->post('media_id', true),
            );
            // update
            if ($this->M_media->update_media($params, $where)) {
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
        redirect("kepegawaian/master/Media/edit/" . $this->input->post('media_id', TRUE));
    }

    // delete page
    public function delete($media_id = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/Media/delete.html");
        // get data
        $result = $this->M_media->get_detail_media_by_id($media_id);
        $this->smarty->assign("id", $result);
        // check
        if (empty($result)) {
            // default error
            // $this->tnotification->sent_notification("error", "Data tidak ada");
            redirect("kepegawaian/master/Media/");
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
            $this->tnotification->set_rules('media_id', 'Media ID', 'trim|required');
            // process
            if ($this->tnotification->run() !== FALSE) {
                $params = array(
                    'media_id' => $this->input->post('media_id', TRUE)
                );
                // delete
                if ($this->M_media->delete($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                    // default redirect
                    redirect("kepegawaian/master/media/delete");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal dihapus");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
            redirect("kepegawaian/master/media/delete/" . $this->input->post('media_id'));
        }
        
}
