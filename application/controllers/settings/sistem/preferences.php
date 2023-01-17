<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class preferences extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('settings/sistem/m_settings');
        // load library
        $this->load->library('tnotification');
        //page header
        $this->smarty->assign("page_header", "System Preferences");
    }

    // Level Surat
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/preferences/index.html");
        // get search parameter
        $search = $this->tsession->userdata('search_pref');
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $pref_nm = empty($search['pref_nm']) ? '%' : '%' . $search['pref_nm'] . '%';
        $pref_group = empty($search['pref_group']) ? '%' : $search['pref_group'];
        // get data
        $this->smarty->assign("no", 1);
        $rs_id = $this->m_settings->get_all_preferences_by_params(array($pref_nm, $pref_group));
        $this->smarty->assign("rs_id", $rs_id);
        $this->smarty->assign("rs_group", $this->m_settings->get_all_preferences_group());
        // echo "<pre>"; print_r($rs_id); echo"</pre>"; exit();
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // search process
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // session
        if ($this->input->post('save') == "Reset") {
            // unset session
            $this->tsession->unset_userdata("search_pref");
        } else {
            // params
            $params = array(
                "pref_nm" => $this->input->post('pref_nm', TRUE),
                "pref_group" => $this->input->post('pref_group', TRUE),
            );
            // set session
            $this->tsession->set_userdata("search_pref", $params);
        }
        // redirect
        redirect("settings/sistem/preferences");
    }

    // form add
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/preferences/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('pref_nm', 'Name', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pref_value', 'Value', 'trim|required|max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'pref_group' => $this->input->post('pref_group', TRUE),
                'pref_nm' => $this->input->post('pref_nm', TRUE),
                'pref_value' => $this->input->post('pref_value', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d H:i:s'),
            );
            // insert
            if ($this->m_settings->insert_preferences($params)) {
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
        redirect("settings/sistem/preferences/add");
    }

    // form edit
    public function edit($pref_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/preferences/edit.html");
        // get data
        $result = $this->m_settings->get_preference_by_id($pref_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("settings/sistem/preferences");
        }
        // assign
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('pref_id', 'ID', 'required');
        $this->tnotification->set_rules('pref_nm', 'Name', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pref_value', 'Value', 'trim|required|max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'pref_group' => $this->input->post('pref_group', TRUE),
                'pref_nm' => $this->input->post('pref_nm', TRUE),
                'pref_value' => $this->input->post('pref_value', TRUE),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d H:i:s'),
            );
            $where = array(
                'pref_id' => $this->input->post('pref_id', TRUE)
            );
            // insert
            if ($this->m_settings->update_preferences($params, $where)) {
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
        redirect("settings/sistem/preferences/edit/" . $this->input->post('pref_id', TRUE));
    }

    // form delete
    public function delete($pref_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "settings/sistem/preferences/delete.html");
        // get data
        $result = $this->m_settings->get_preference_by_id($pref_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("settings/sistem/preferences");
        }
        // assign
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('pref_id', 'ID', 'required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array(
                'pref_id' => $this->input->post('pref_id', TRUE)
            );
            // insert
            if ($this->m_settings->delete_preferences($where)) {
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
        redirect("settings/sistem/preferences/");
    }

}
