<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class Project extends ApplicationBase {

    // constructor
    public function __construct() 
    {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("project/master/M_projects");
        // $this->load->model("m_document");
        $this->load->model("m_preferences");
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/master/project/list.html");
        // list tahun
        $this->smarty->assign("rs_tahun", $this->M_projects->get_list_tahun());
        // search
        $search = $this->tsession->userdata("project_data_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        $client = empty($search['client']) ? '' : '%' . $search['client'] . '%';
        $struktur_cd = empty($search['struktur_cd']) ? '%' : $search['struktur_cd'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/master/project/index/");
        $params = array($project, $project, $struktur_cd, $tahun, $client);
        $config['total_rows'] = $this->M_projects->get_total_project_data($params);
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
        $params = array($project, $project, $struktur_cd, $tahun, ($start - 1), $config['per_page']);
        $rs_id = $this->M_projects->get_all_project_data($params, $client);
        // get list data
        $this->smarty->assign("rs_id", $rs_id);
        // status project
        $this->smarty->assign("rs_unit", $this->M_projects->get_all_department());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process() 
    {
        //set page rules
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Reset") 
        {
            $this->tsession->unset_userdata("project_data_search");
        } 
        else 
        {
            $params = array(
                "project"       => $this->input->post("project", TRUE),
                "client"        => $this->input->post("client", TRUE),
                "tahun"         => $this->input->post("tahun", TRUE),
                "struktur_cd"   => $this->input->post("struktur_cd", TRUE)
            );
            // set session
            $this->tsession->set_userdata("project_data_search", $params);
        }
        // redirect
        redirect("project/master/project");
    }

    // add
    public function add() {
        //set page rules
        $this->_set_page_rule("C");
        //set template content
        $this->smarty->assign("template_content", "project/master/project/add.html");
        // get data
        $this->smarty->assign("rs_clients", $this->M_projects->get_all_data_client());
        $this->smarty->assign("rs_status", $this->M_projects->get_preferences_by_group());
        $this->smarty->assign("rs_unit", $this->M_projects->get_all_department());
        $this->smarty->assign("rs_jenis", $this->M_projects->get_all_jenis_kegiatan());
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules("client_id", "Client", "trim|required");
        $this->tnotification->set_rules("project_name", "Project Name", "trim|required|max_length[255]");
        $this->tnotification->set_rules("project_alias", "Project Alias", "trim|required|max_length[50]");
        $this->tnotification->set_rules("project_desc", "Project Desc", "trim|required|max_length[255]");
        $this->tnotification->set_rules("project_start", "Project Start", "trim|required|max_length[10]");
        $this->tnotification->set_rules("project_end", "Project End", "trim|max_length[10]");
        $this->tnotification->set_rules("project_st", "Project Status", "trim|required");
        $this->tnotification->set_rules("struktur_cd", "Unit Kerja", "trim|required");
        $this->tnotification->set_rules("jenis_kode_kegiatan", "Jenis Kegiatan", "trim|required");
        // validate project_alias
        if ($this->M_projects->is_exist_project_code($this->input->post("project_alias"))) {
            $this->tnotification->set_error_message('Project Code is not available');
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // tanggal
            $prefix = date('ymd');
            $params = $prefix . '%';
            $project_id = $this->M_projects->get_last_project_id($prefix, $params);
            if(empty($project_id)){
                $this->tnotification->set_error_message('ID Project tidak tersedia');
            }
            //params
            $params = array(
                'project_id'            => $project_id,
                'client_id'             => $this->input->post('client_id', true),
                'struktur_cd'           => $this->input->post('struktur_cd', true),
                'jenis_kode_kegiatan'   => $this->input->post('jenis_kode_kegiatan', true),
                'project_name'          => $this->input->post('project_name', true),
                'project_alias'         => $this->input->post('project_alias', true),
                'project_desc'          => $this->input->post('project_desc', true),
                'project_start'         => empty($this->input->post('project_start', true))?NULL:$this->input->post('project_start', true),
                'project_end'           => empty($this->input->post('project_end', true))?NULL:$this->input->post('project_end', true),
                'project_st'            => $this->input->post('project_st', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")

            );
            // echo "<pre>"; print_r($params); echo "</pre>"; exit();
            // insert
            if ($this->M_projects->insert_project($params)) {
                // sent notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                //default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            //default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/master/project/add");
    }

    // edit
    public function edit($project_id = "") {
        //set rules
        $this->_set_page_rule("U");
        //set template content
        $this->smarty->assign("template_content", "project/master/project/edit.html");
        //get detail data
        $detail = $this->M_projects->get_project_data_by_id(array($project_id));
        if(empty($detail)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/master/project");
        }
        $detail['project_alias_old'] = $detail['project_alias'];
        $this->smarty->assign("result", $detail);
        $this->smarty->assign("detail", $detail);
        // get data
        $this->smarty->assign("rs_clients", $this->M_projects->get_all_data_client());
        $this->smarty->assign("rs_status", $this->M_projects->get_preferences_by_group());
        $this->smarty->assign("rs_unit", $this->M_projects->get_all_department());
        $this->smarty->assign("rs_jenis", $this->M_projects->get_all_jenis_kegiatan());
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // edit process
    public function edit_process() {
        // set page rule
        $this->_set_page_rule("U");
        // check input
        $this->tnotification->set_rules("project_id", "ID Project", "trim|required");
        $this->tnotification->set_rules("client_id", "Client", "trim|required");
        $this->tnotification->set_rules("project_name", "Project Name", "trim|required|max_length[255]");
        $this->tnotification->set_rules("project_alias", "Project Alias", "trim|required|max_length[50]");
        $this->tnotification->set_rules("project_alias_old", "Project Alias Lama", "trim|required|max_length[50]");
        $this->tnotification->set_rules("project_desc", "Project Desc", "trim|required|max_length[255]");
        $this->tnotification->set_rules("project_start", "Project Start", "trim|required|max_length[10]");
        $this->tnotification->set_rules("project_end", "Project End", "trim|max_length[10]");
        $this->tnotification->set_rules("project_st", "Project Status", "trim|required");
        $this->tnotification->set_rules("struktur_cd", "Unit Kerja", "trim|required");
        $this->tnotification->set_rules("jenis_kode_kegiatan", "Jenis Kegiatan", "trim|required");
        // validate project_alias
        $project_alias = $this->input->post("project_alias");
        $project_alias_old = $this->input->post("project_alias_old");
        if ($project_alias <> $project_alias_old) {
            if ($this->M_projects->is_exist_project_code($project_alias)) {
                $this->tnotification->set_error_message('Project Code is not available');
            }
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $project_id = $this->input->post("project_id", true);
            //get detail data
            $detail = $this->M_projects->get_project_data_by_id(array($project_id));
            if(empty($detail)){
                //default error
                $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
                redirect("project/master/project");
            }
            //params
            $params = array(
                'client_id'             => $this->input->post('client_id', true),
                'struktur_cd'           => $this->input->post('struktur_cd', true),
                'jenis_kode_kegiatan'   => $this->input->post('jenis_kode_kegiatan', true),
                'project_name'          => $this->input->post('project_name', true),
                'project_alias'         => $this->input->post('project_alias', true),
                'project_desc'          => $this->input->post('project_desc', true),
                'project_start'         => empty($this->input->post('project_start', true))?NULL:$this->input->post('project_start', true),
                'project_end'           => empty($this->input->post('project_end', true))?NULL:$this->input->post('project_end', true),
                'project_st'            => $this->input->post('project_st', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")

            );
            $where = array('project_id' => $project_id);
            if ($this->M_projects->update_project($params, $where)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diupdate");
            } else {
                //default error
                $this->tnotification->sent_notification("error", "Data gagal diupdate");
            }
        } else {
            //default error
            $this->tnotification->sent_notification("error", "Data gagal diupdate");
        }
        //default redirect
        redirect("project/master/project/edit/" . $this->input->post("project_id", true));
    }

    // delete
    public function delete($project_id = "") {
        //set rule
        $this->_set_page_rule("D");
        //set template content
        $this->smarty->assign("template_content", "project/master/project/delete.html");
        // get data
        $this->smarty->assign("result", $this->M_projects->get_project_data_by_id(array($project_id)));
        // notification
        $this->tnotification->display_notification();
        //output
        parent::display();
    }

    // hapus process
    public function hapus_process() {
        //set rule
        $this->_set_page_rule("D");
        //cek input
        $this->tnotification->set_rules("project_id", "trim|required");
        //process
        if ($this->tnotification->run() !== FALSE) {
            $project_id = $this->input->post("project_id", true);
            //get detail data
            $detail = $this->M_projects->get_project_data_by_id(array($project_id));
            if(empty($detail)){
                //default error
                $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
                redirect("project/master/project");
            }

            $params = array('project_id' => $this->input->post("project_id", true));
            //delete
            if ($this->M_projects->delete_project($params)) {
                // --
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("project/master/project");
            } else {
                //default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            //default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        //default redirect
        redirect("project/master/project/delete/" . $this->input->post("project_id", true));
    }

    // /*
    //  * Notes
    //  */

    // // view
    // public function notes($project_id = "") {
    //     // set page rules
    //     $this->_set_page_rule("R");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/notes/list.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // list notes
    //     $this->smarty->assign("rs_id", $this->M_projects->get_list_project_notes_by_id(array($project_id)));
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // add 
    // public function notes_add($project_id = "") {
    //     // set page rules
    //     $this->_set_page_rule("C");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/notes/add.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));

    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // process add
    // public function notes_add_process() {
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('note_date', 'Tanggal', 'trim|required');
    //     $this->tnotification->set_rules('note_desc', 'Keterangan', 'trim|required|maxlength[100]');
    //     $this->tnotification->set_rules('note_st', 'Status', 'trim|required');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         $params = array($this->input->post('project_id'), $this->input->post('note_date'),
    //             $this->input->post('note_desc'), $this->input->post('note_st'),
    //             $this->com_user['user_id']);
    //         // insert
    //         if ($this->m_notes->insert_notes($params)) {
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/notes_add/" . $this->input->post('project_id'));
    // }

    // // edit 
    // public function notes_edit($project_id = "", $note_id = '') {
    //     // set page rules
    //     $this->_set_page_rule("U");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/notes/edit.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // get detail data
    //     $result = $this->M_projects->get_notes_by_id(array($project_id, $note_id));
    //     $this->smarty->assign('result', $result);
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // process edit
    // public function notes_edit_process() {
    //     // set page rules
    //     $this->_set_page_rule("U");
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('note_id', 'ID', 'trim|required');
    //     $this->tnotification->set_rules('note_date', 'Tanggal', 'trim|required');
    //     $this->tnotification->set_rules('note_desc', 'Keterangan', 'trim|required|maxlength[100]');
    //     $this->tnotification->set_rules('note_st', 'Status', 'trim|required');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         $params = array($this->input->post('note_date'), $this->input->post('note_desc'), $this->input->post('note_st'),
    //             $this->com_user['user_id'], $this->input->post('project_id'),
    //             $this->com_user['department_id'], $this->input->post('note_id'));
    //         // update
    //         if ($this->m_notes->update_notes($params)) {
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/notes_edit/" . $this->input->post('project_id') . '/' . $this->input->post('note_id'));
    // }

    // // process hapus
    // public function notes_hapus_process($project_id = "", $note_id = '') {
    //     // set page rules
    //     $this->_set_page_rule("D");
    //     // exec
    //     $params = array($project_id, $note_id);
    //     // update
    //     if ($this->m_notes->delete_notes($params)) {
    //         $this->tnotification->delete_last_field();
    //         $this->tnotification->sent_notification("success", "Data berhasil dihapus");
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal dihapus");
    //     }
    //     // default redirect
    //     redirect("project/master/project/notes/" . $project_id);
    // }

    // /*
    //  * Budgeting
    //  */

    // // view
    // public function budgeting($project_id = "") {
    //     // set rules
    //     $this->_set_page_rule("R");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/budget/list.html");
    //     // detail project
    //     $this->smarty->assign("project", $this->m_budget->get_project_data_by_id(array($project_id)));
    //     // list biaya
    //     $this->smarty->assign("rs_id", $this->m_budget->get_list_budget_by_id(array($project_id)));
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     //output
    //     parent::display();
    // }

    // // add 
    // public function budgeting_add($project_id = "") {
    //     // set page rules
    //     $this->_set_page_rule("C");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/budget/add.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // list kategori
    //     $this->smarty->assign("rs_cat", $this->m_budget->get_list_kategori());
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // add process
    // public function budgeting_add_process() {
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('cat_id', 'Kategori', 'trim|required');
    //     $this->tnotification->set_rules('budget_desc', 'Uraian Biaya', 'trim|required|max_length[100]');
    //     $this->tnotification->set_rules('budget_plan', 'Rencana Biaya', 'trim|required|max_length[20]');
    //     $this->tnotification->set_rules('budget_real', 'Realisasi Anggaran', 'trim|max_length[20]');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         // project_id, cat_id, budget_desc, budget_plan, budget_real, mdb, mdd
    //         $params = array($this->input->post('project_id'), $this->input->post('cat_id'),
    //             $this->input->post('budget_desc'), $this->input->post('budget_plan'),
    //             $this->input->post('budget_real'), $this->com_user['user_id']);
    //         // insert
    //         if ($this->m_budget->insert_budget($params)) {
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/budgeting_add/" . $this->input->post('project_id'));
    // }

    // // process hapus
    // public function budgeting_delete_process($project_id = "", $budget_id = '') {
    //     // set page rules
    //     $this->_set_page_rule("D");
    //     // exec
    //     $params = array($project_id, $budget_id);
    //     // update
    //     if ($this->m_budget->delete_budget($params)) {
    //         $this->tnotification->delete_last_field();
    //         $this->tnotification->sent_notification("success", "Data berhasil dihapus");
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal dihapus");
    //     }
    //     // default redirect
    //     redirect("project/master/project/budgeting/" . $project_id);
    // }

    // // edit 
    // public function budgeting_edit($project_id = "", $budget_id = '') {
    //     // set page rules
    //     $this->_set_page_rule("U");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/budget/edit.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // get detail data
    //     $result = $this->M_projects->get_budget_by_id(array($project_id, $budget_id));
    //     $this->smarty->assign('result', $result);
    //     // list kategori
    //     $this->smarty->assign("rs_cat", $this->m_budget->get_list_kategori());
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // process edit
    // public function budgeting_edit_process() {
    //     // set page rules
    //     $this->_set_page_rule("U");
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('budget_id', 'ID', 'trim|required');
    //     $this->tnotification->set_rules('cat_id', 'Kategori', 'trim|required');
    //     $this->tnotification->set_rules('budget_desc', 'Uraian Biaya', 'trim|required|max_length[100]');
    //     $this->tnotification->set_rules('budget_plan', 'Rencana Biaya', 'trim|required|max_length[20]');
    //     $this->tnotification->set_rules('budget_real', 'Realisasi Anggaran', 'trim|max_length[20]');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         $params = array($this->input->post('project_id'), $this->input->post('cat_id'),
    //             $this->input->post('budget_desc'), $this->input->post('budget_plan'),
    //             $this->input->post('budget_real'), $this->com_user['user_id'],
    //             $this->input->post('budget_id'));
    //         // update
    //         if ($this->m_budget->update_budget($params)) {
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/budgeting_edit/" . $this->input->post('project_id') . '/' . $this->input->post('budget_id'));
    // }

    // /*
    //  * DOCUMENT
    //  */

    // // view
    // public function dokumentasi($project_id = "") {
    //     // set rules
    //     $this->_set_page_rule("R");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/dokumentasi/list.html");
    //     // detail project
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // list dokumen persyaratan
    //     $this->smarty->assign("rs_id", $this->m_document->get_list_document_by_id(array($project_id)));
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     //output
    //     parent::display();
    // }

    // // add 
    // public function dokumentasi_add($project_id = "") {
    //     // set page rules
    //     $this->_set_page_rule("C");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/dokumentasi/add.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // add process
    // public function dokumentasi_add_process() {
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('doc_title', 'Judul', 'trim|required|max_length[45]');
    //     $this->tnotification->set_rules('doc_notes', 'Keterangan', 'trim|max_length[100]');
    //     $this->tnotification->set_rules('doc_due_date', 'Target Tanggal', 'trim|required|max_length[10]');
    //     $this->tnotification->set_rules('doc_st', 'Status', 'trim|required');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         $params = array($this->input->post('project_id'), $this->input->post('doc_title'),
    //             $this->input->post('doc_notes'), $this->input->post('doc_due_date'),
    //             $this->input->post('doc_st'), $this->com_user['user_id']);
    //         // insert
    //         if ($this->m_document->insert_document($params)) {
    //             // load
    //             $this->load->library('tupload');
    //             // last id
    //             $doc_id = $this->m_document->get_last_inserted_id();
    //             // upload file
    //             if (!empty($_FILES['doc_attachment']['tmp_name'])) {
    //                 // upload config
    //                 $config['upload_path'] = 'resource/doc/projects/files/' . $this->input->post('project_id') . '/';
    //                 $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip';
    //                 $config['file_name'] = $doc_id;
    //                 $this->tupload->initialize($config);
    //                 // process upload images
    //                 if ($this->tupload->do_upload('doc_attachment')) {
    //                     $data = $this->tupload->data();
    //                     $this->m_document->update_file(array($data['file_name'], $_FILES['doc_attachment']['name'], $doc_id));
    //                 } else {
    //                     // jika gagal
    //                     $this->tnotification->set_error_message($this->tupload->display_errors());
    //                 }
    //             }
    //             // notification
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/dokumentasi_add/" . $this->input->post('project_id'));
    // }

    // // edit 
    // public function dokumentasi_edit($project_id = "", $doc_id = "") {
    //     // set page rules
    //     $this->_set_page_rule("U");
    //     // set template content
    //     $this->smarty->assign("template_content", "project/dokumentasi/edit.html");
    //     // get project detail
    //     $this->smarty->assign("project", $this->M_projects->get_project_data_by_id(array($project_id)));
    //     // get detail data
    //     $result = $this->m_document->get_project_document_by_id(array($project_id, $doc_id));
    //     $this->smarty->assign('result', $result);
    //     // notification
    //     $this->tnotification->display_notification();
    //     $this->tnotification->display_last_field();
    //     // output
    //     parent::display();
    // }

    // // edit process
    // public function dokumentasi_edit_process() {
    //     // cek input
    //     $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
    //     $this->tnotification->set_rules('doc_id', 'ID Dokumen', 'trim|required');
    //     $this->tnotification->set_rules('doc_title', 'Judul', 'trim|required|max_length[45]');
    //     $this->tnotification->set_rules('doc_notes', 'Keterangan', 'trim|max_length[100]');
    //     $this->tnotification->set_rules('doc_due_date', 'Target Tanggal', 'trim|required|max_length[10]');
    //     $this->tnotification->set_rules('doc_st', 'Status', 'trim|required');
    //     // process
    //     if ($this->tnotification->run() !== FALSE) {
    //         $params = array($this->input->post('project_id'), $this->input->post('doc_title'),
    //             $this->input->post('doc_notes'), $this->input->post('doc_due_date'),
    //             $this->input->post('doc_st'), $this->com_user['user_id'], $this->input->post('doc_id'));
    //         // update
    //         if ($this->m_document->update_document($params)) {
    //             // load
    //             $this->load->library('tupload');
    //             // last id
    //             $doc_id = $this->input->post('doc_id');
    //             // upload file
    //             if (!empty($_FILES['doc_attachment']['tmp_name'])) {
    //                 // upload config
    //                 $config['upload_path'] = 'resource/doc/projects/files/' . $this->input->post('project_id') . '/';
    //                 $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip';
    //                 $config['file_name'] = $doc_id;
    //                 $this->tupload->initialize($config);
    //                 // process upload images
    //                 if ($this->tupload->do_upload('doc_attachment')) {
    //                     $data = $this->tupload->data();
    //                     $this->m_document->update_file(array($data['file_name'], $_FILES['doc_attachment']['name'], $doc_id));
    //                 } else {
    //                     // jika gagal
    //                     $this->tnotification->set_error_message($this->tupload->display_errors());
    //                 }
    //             }
    //             // notification
    //             $this->tnotification->delete_last_field();
    //             $this->tnotification->sent_notification("success", "Data berhasil disimpan");
    //         } else {
    //             // default error
    //             $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //         }
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal disimpan");
    //     }
    //     // default redirect
    //     redirect("project/master/project/dokumentasi_edit/" . $this->input->post('project_id') . '/' . $this->input->post('doc_id'));
    // }

    // // process hapus
    // public function dokumentasi_delete_process($project_id = "", $doc_id = '') {
    //     // set page rules
    //     $this->_set_page_rule("D");
    //     // exec
    //     $params = array($project_id, $doc_id);
    //     // update
    //     if ($this->m_document->delete_document($params)) {
    //         $this->tnotification->delete_last_field();
    //         $this->tnotification->sent_notification("success", "Data berhasil dihapus");
    //     } else {
    //         // default error
    //         $this->tnotification->sent_notification("error", "Data gagal dihapus");
    //     }
    //     // default redirect
    //     redirect("project/master/project/dokumentasi/" . $project_id);
    // }

    // // download
    // public function download_doc($project_id = "", $doc_id = "") {
    //     // get detail data
    //     $result = $this->m_document->get_project_document_by_id(array($project_id, $doc_id));
    //     if (empty($result)) {
    //         redirect('project/master/project/dokumentasi/' . $project_id);
    //     }
    //     // filepath
    //     $file_path = 'resource/doc/projects/files//' . $result['project_id'] . '/' . $result['doc_path'];
    //     if (is_file($file_path)) {
    //         header('Content-Description: Download File Project');
    //         header('Content-Type: application/octet-stream');
    //         header('Content-Length: ' . filesize($file_path));
    //         header('Content-Disposition: attachment; filename="' . $result['doc_attachment'] . '"');
    //         readfile($file_path);
    //         exit();
    //     } else {
    //         redirect('project/master/project/dokumentasi/' . $project_id);
    //     }
    // }

}
