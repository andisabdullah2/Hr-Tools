<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --

class client extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('project/master/m_client');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list client
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/index.html");
        // search
        $search = $this->tsession->userdata("client_search");
        // search parameters
        $client_city = empty($search['client_city']) ? '%' : '%' . $search['client_city'] . '%';
        $client_nm = empty($search['client_desc']) ? '%' : '%' . $search['client_desc'] . '%';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/master/client/index/");
        $params = array($client_city, $client_nm);
        $config['total_rows'] = $this->m_client->get_total_client_data($params);
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
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        
        // get list
        $params = array($client_city, $client_nm, ($start - 1), $config['per_page']);
        // get list data
        $this->smarty->assign("rs_id", $this->m_client->get_all_data_client($params));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function proses_cari() {
        //set page rules
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("client_search");
        } else {
            $params = array(
                "client_city" => $this->input->post("client_city"),
                "client_desc" => $this->input->post("client_desc")
            );
            $this->tsession->set_userdata("client_search", $params);
        }
        redirect("project/master/client");
    }

    // add client
    public function add() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // cek input
        $this->tnotification->set_rules('client_nm', 'Alias Name', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('client_desc', 'Client Name', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('client_address', 'Client Address', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('client_city', 'City', 'trim|required|max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get id
            $client_id = $this->m_client->get_client_id();
            // params
            $params = array(
                'client_id' => $client_id,
                'client_nm' => $this->input->post('client_nm', TRUE),
                'client_desc' => $this->input->post('client_desc', TRUE),
                'client_address' => $this->input->post('client_address', TRUE),
                'client_city' => $this->input->post('client_city', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_client->insert_client($params)) {
                // default success
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
        redirect("project/master/client/add");
    }

    // edit client
    public function edit($client_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/edit.html");
        // get data
        $this->smarty->assign("result", $this->m_client->get_client_by_id($client_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // cek input
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        $this->tnotification->set_rules('client_nm', 'Alias Name', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('client_desc', 'Client Name', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('client_address', 'Client Address', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('client_city', 'City', 'trim|required|max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get id
            $client_id = $this->input->post('client_id', TRUE);
            // params
            $params = array(
                'client_nm' => $this->input->post('client_nm', TRUE),
                'client_desc' => $this->input->post('client_desc', TRUE),
                'client_address' => $this->input->post('client_address', TRUE),
                'client_city' => $this->input->post('client_city', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'client_id' => $client_id
            );
            // update
            if ($this->m_client->update_client($params, $where)) {
                // default success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect("project/master/client/alamat/" . $client_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/master/client/edit/" . $client_id);
    }

    // hapus client
    public function delete($client_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/delete.html");
        // get data
        $this->smarty->assign("result", $this->m_client->get_client_by_id($client_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process() {
        // cek input
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array('client_id' => $this->input->post('client_id', TRUE));
            // insert
            if ($this->m_client->delete_client($params)) {
                // default success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("project/master/client/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/master/client/");
    }

    /*
     * ----ALAMAT----
     */

    // list alamat
    public function alamat($client_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/alamat.html");
        // get data client
        $this->smarty->assign("result", $this->m_client->get_client_by_id($client_id));
        // get data alamat
        $this->smarty->assign("rs_id", $this->m_client->get_alamat_by_client($client_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add alamat process
    public function alamat_add_process() {
        // cek input
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        $this->tnotification->set_rules('alamat_kepada', 'Kepada', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|required');
        $this->tnotification->set_rules('alamat_default', 'Alamat Default', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get id
            $alamat_id = $this->m_client->get_alamat_id($this->input->post('client_id', TRUE));
            // params
            $params = array(
                'alamat_id' => $alamat_id,
                'client_id' => $this->input->post('client_id', TRUE),
                'alamat_kepada' => $this->input->post('alamat_kepada', TRUE),
                'alamat_kantor' => $this->input->post('alamat_kantor', TRUE),
                'alamat_default' => $this->input->post('alamat_default', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_client->insert_alamat($params)) {
                // default success
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
        redirect("project/master/client/alamat/" . $this->input->post('client_id', TRUE));
    }

    // edit alamat process
    public function alamat_edit_process() {
        // cek input
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        $this->tnotification->set_rules('alamat_id', 'ID Alamat', 'required');
        $this->tnotification->set_rules('alamat_kepada', 'Kepada', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|required');
        $this->tnotification->set_rules('alamat_default', 'Alamat Default', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $params = array(
                'client_id' => $this->input->post('client_id', TRUE),
                'alamat_kepada' => $this->input->post('alamat_kepada', TRUE),
                'alamat_kantor' => $this->input->post('alamat_kantor', TRUE),
                'alamat_default' => $this->input->post('alamat_default', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'alamat_id' => $this->input->post('alamat_id', TRUE)
            );
            // insert
            if ($this->m_client->update_alamat($params, $where)) {
                // default success
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
        redirect("project/master/client/alamat/" . $this->input->post('client_id', TRUE));
    }

    // delete alamat proses
    public function alamat_delete_process($client_id = "", $alamat_id = "") {
        // set page rule
        $this->_set_page_rule("D");
        // get data
        $result = $this->m_client->get_alamat_by_id($alamat_id);
        // check data
        if (empty($result)) {
            // no data
            $this->tnotification->sent_notification('error', 'Data yang anda pilih tidak terdaftar!');
            redirect('project/master/client/alamat/' . $client_id);
        }
        // where
        $where = array('alamat_id' => $alamat_id);
        // delete
        if ($this->m_client->delete_alamat($where)) {
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // defaul error
            $this->tnotification->sent_notification('error', 'Data gagal dihapus');
        }
        // default redirect
        redirect('project/master/client/alamat/' . $client_id);
    }

    /*
     * ----CONTACT PERSON----
     */

    // list pic
    public function pic($client_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/master/client/pic.html");
        // get data client
        $this->smarty->assign("result", $this->m_client->get_client_by_id($client_id));
        // get data pic
        $this->smarty->assign("rs_id", $this->m_client->get_pic_by_client($client_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add pic process
    public function pic_add_process() {
        // cek input
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        $this->tnotification->set_rules('pic_name', 'Nama', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pic_position', 'Jabatan', 'trim|max_length[50]');
        $this->tnotification->set_rules('pic_phone_number', 'Telepon', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pic_email', 'Email', 'trim|valid_email||max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get id
            $pic_id = $this->m_client->get_pic_id($this->input->post('client_id', TRUE));
            // params
            $params = array(
                'pic_id' => $pic_id,
                'client_id' => $this->input->post('client_id', TRUE),
                'pic_name' => $this->input->post('pic_name', TRUE),
                'pic_position' => $this->input->post('pic_position', TRUE),
                'pic_phone_number' => $this->input->post('pic_phone_number', TRUE),
                'pic_email' => $this->input->post('pic_email', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_client->insert_pic($params)) {
                // default success
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
        redirect("project/master/client/pic/" . $this->input->post('client_id', TRUE));
    }

    // edit pic process
    public function pic_edit_process() {
        // cek input
        $this->tnotification->set_rules('pic_id', 'ID PIC', 'required');
        $this->tnotification->set_rules('client_id', 'ID Client', 'required');
        $this->tnotification->set_rules('pic_name', 'Nama', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pic_position', 'Jabatan', 'trim|max_length[50]');
        $this->tnotification->set_rules('pic_phone_number', 'Telepon', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pic_email', 'Email', 'trim|valid_email||max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $params = array(
                'client_id' => $this->input->post('client_id', TRUE),
                'pic_name' => $this->input->post('pic_name', TRUE),
                'pic_position' => $this->input->post('pic_position', TRUE),
                'pic_phone_number' => $this->input->post('pic_phone_number', TRUE),
                'pic_email' => $this->input->post('pic_email', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'pic_id' => $this->input->post('pic_id', TRUE)
            );
            // insert
            if ($this->m_client->update_pic($params, $where)) {
                // default success
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
        redirect("project/master/client/pic/" . $this->input->post('client_id', TRUE));
    }

    // delete pic proses
    public function pic_delete_process($client_id = "", $pic_id = "") {
        // set page rule
        $this->_set_page_rule("D");
        // get data
        $result = $this->m_client->get_pic_by_id($pic_id);
        // check data
        if (empty($result)) {
            // no data
            $this->tnotification->sent_notification('error', 'Data yang anda pilih tidak terdaftar!');
            redirect('project/master/client/pic/' . $client_id);
        }
        // where
        $where = array('pic_id' => $pic_id);
        // delete
        if ($this->m_client->delete_pic($where)) {
            // success
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // defaul error
            $this->tnotification->sent_notification('error', 'Data gagal dihapus');
        }
        // default redirect
        redirect('project/master/client/pic/' . $client_id);
    }

}
