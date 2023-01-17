<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once(APPPATH . "controllers/base/OperatorBase.php");

class Notes extends ApplicationBase
{
    //constructor
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('project/tools/M_notes');
        //load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
        //helper
        $this->load->helper('date');
    }

    //index 
    public function index()
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/tools/notes/list.html");
        //get project
        $this->smarty->assign("rs_project", $this->M_notes->get_all_project());
        // search
        $search = $this->tsession->userdata("project_notes_search");
        // search parameters
        $project = empty($search['project']) ? '' : $search['project'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        $rs_id = array();
        $rs_bulan = $this->datetimemanipulation->get_month('in');
        $detail_project = $this->M_notes->get_detail_project($project);
        $this->smarty->assign("detail_project", $detail_project);
        if (!empty($project)) {
            //
            $rs_project = $this->M_notes->get_list_project(array($project));
            foreach ($rs_project as $key => $project) {
                $rs_id[$project['project_id']] = $project;
                //get list note
                $rs_bulan_tahun = $this->M_notes->get_list_bulan_tahun_by_project(array($project['project_id']));
                foreach ($rs_bulan_tahun as $key => $bulan_tahun) {
                    $temp = array();
                    $temp['bulan'] = date('m', strtotime($bulan_tahun['note_start_date']));
                    $temp['bulan_label'] = $rs_bulan[date('m', strtotime($bulan_tahun['note_start_date']))];
                    $temp['judul_id'] = date('Y-m', strtotime($bulan_tahun['note_start_date']));
                    $temp['tahun'] = date('Y', strtotime($bulan_tahun['note_start_date']));
                    $temp['judul'] = $temp['bulan_label'] . ' ' . $temp['tahun'];

                    $rs_id[$project['project_id']]['bulan_tahun'][date('Y-m', strtotime($bulan_tahun['note_start_date']))] = $temp;
                    //list note
                    $rs_notes = $this->M_notes->get_list_note_by_date(array($project['project_id'], $temp['judul_id'] . '%'));
                    foreach ($rs_notes as $key => $note) {
                        $rs_id[$project['project_id']]['bulan_tahun'][date('Y-m', strtotime($bulan_tahun['note_start_date']))]['notes'][$note['note_id']] = $note;
                    }
                }
            }
        }
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process()
    {
        //set page rule
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("project_notes_search");
        } else {
            $params = array(
                "project" => $this->input->post("project"),
            );
            $this->tsession->set_userdata("project_notes_search", $params);
        }
        redirect("project/tools/notes");
    }

    // process add
    public function add_process()
    {
        //set page rule
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('note_start_date', 'Start Date', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('note_due_date', 'Due Date', 'trim|max_length[10]');
        $this->tnotification->set_rules('note_finish_date', 'Due Date', 'trim|max_length[10]');
        $this->tnotification->set_rules('note_desc', 'Notes', 'trim|required');
        $this->tnotification->set_rules('note_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //note_id
            $note_id = $this->M_notes->get_microtime();
            if (empty($note_id)) {
                $this->tnotification->set_error_message('ID Note tidak tersedia');
            }
            //params
            $params = array(
                'note_id'           => $note_id,
                'project_id'        => $this->input->post('project_id', true),
                'note_desc'         => $this->input->post('note_desc', true),
                'note_start_date'   => $this->input->post('note_start_date', true),
                'note_due_date'     => empty($this->input->post('note_due_date', true)) ? NULL : $this->input->post('note_due_date', true),
                'note_finish_date'  => empty($this->input->post('note_finish_date', true)) ? NULL : $this->input->post('note_finish_date', true),
                'note_st'           => empty($this->input->post('note_st', true)) ? NULL : $this->input->post('note_st', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_notes->insert($params)) {
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
        //redirect
        redirect("project/tools/notes/");
    }

    // process edit
    public function edit_process()
    {
        //set page rule
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('note_id', 'ID Note', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('note_start_date', 'Start Date', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('note_due_date', 'Due Date', 'trim|max_length[10]');
        $this->tnotification->set_rules('note_finish_date', 'Due Date', 'trim|max_length[10]');
        $this->tnotification->set_rules('note_desc', 'Notes', 'trim|required');
        $this->tnotification->set_rules('note_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //get notes
            $note_id = $this->input->post('note_id', true);
            $note = $this->M_notes->get_note_by_id($note_id);
            if (empty($note)) {
                //default error
                $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
                redirect("project/tools/notes/");
            }
            //params
            $params = array(
                'project_id'        => $this->input->post('project_id', true),
                'note_desc'         => $this->input->post('note_desc', true),
                'note_start_date'   => $this->input->post('note_start_date', true),
                'note_due_date'     => empty($this->input->post('note_due_date', true)) ? NULL : $this->input->post('note_due_date', true),
                'note_finish_date'  => empty($this->input->post('note_finish_date', true)) ? NULL : $this->input->post('note_finish_date', true),
                'note_st'           => empty($this->input->post('note_st', true)) ? NULL : $this->input->post('note_st', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array('note_id' => $note_id);
            //update
            if ($this->M_notes->update($params, $where)) {
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
        //redirect
        redirect("project/tools/notes/");
    }

    // delete process
    public function delete_process($note_id = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        $note = $this->M_notes->get_note_by_id($note_id);
        if (empty($note)) {
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/notes/");
        }
        $where = array('note_id' => $note_id);
        // delete
        if ($this->M_notes->delete($where)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/tools/notes");
    }
}
