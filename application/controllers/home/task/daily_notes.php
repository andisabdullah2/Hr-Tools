<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class daily_notes extends ApplicationBase {

    //contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model("home/task/m_daily_notes");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // task overview
    public function index($year = '', $month = '') {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "home/task/daily_notes/list.html");
        // calender variable
        $calendar = array();
        // This puts the day, month, and year in seperate variables
        if (empty($year) || empty($month)) {
            $month = date('m');
            $year = date('Y');
        }
        // navigation
        $last['bulan'] = date('m', strtotime("$year-$month-01 -1 day"));
        $last['tahun'] = date('Y', strtotime("$year-$month-01 -1 day"));
        $next['bulan'] = date('m', strtotime("$year-$month-31 +1 day"));
        $next['tahun'] = date('Y', strtotime("$year-$month-31 +1 day"));
        $now['bulan'] = date('m', mktime(0, 0, 0, $month, 1, $year));
        $now['tahun'] = date('Y', mktime(0, 0, 0, $month, 1, $year));
        $this->smarty->assign('last', $last);
        $this->smarty->assign('next', $next);
        $this->smarty->assign('now', $now);
        // Here we generate the first day of the month
        $first_day = mktime(0, 0, 0, $month, 1, $year);
        // Here we find out what day of the week the first day of the month falls on 
        $day_of_week = date('D', $first_day);
        // Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
        switch ($day_of_week) {
            case "Sun": $blank = 0;
                break;
            case "Mon": $blank = 1;
                break;
            case "Tue": $blank = 2;
                break;
            case "Wed": $blank = 3;
                break;
            case "Thu": $blank = 4;
                break;
            case "Fri": $blank = 5;
                break;
            case "Sat": $blank = 6;
                break;
        }
        $calendar['blank'] = $blank;
        // We then determine how many days are in the current month
        $calendar['days_in_month'] = cal_days_in_month(0, $month, $year);
        $calendar['day_count'] = 1;
        $calendar['day_num'] = 1;
        // assign calendar variables
        $this->smarty->assign("calendar", $calendar);
        $this->smarty->assign("date_now", date('Y-m-d'));
        // get notes
        $data = array();
        $rs_task = $this->m_daily_notes->get_task_by_year_month(array($this->com_user['user_id'], $year, $month));
        foreach ($rs_task as $task) {
            $data[$task['task_date']][$task['task_id']] = $task;
        }
        $this->smarty->assign("tasks", $data);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // add task
    public function add() {
        //set page rule
        $this->_set_page_rule("C");
        //set template content
        $this->smarty->assign("template_content", "home/task/daily_notes/add.html");
        // get project by user
        $rs_project = $this->m_daily_notes->get_list_project();
        $this->smarty->assign("rs_project", $rs_project);
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // process add
    public function add_process() {
        //set page rule
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('project_modul', 'Modul', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('task_desc', 'Uraian Tugas', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('task_link', 'Link', 'trim|maxlength[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // get id
            $time = microtime(true);
            $id = str_replace('.', '', $time) . $this->com_user['user_id'];
            // params
            $params = array($id, $this->com_user['user_id'], $this->input->post('project_id'),
                $this->input->post('project_modul'),
                $this->input->post('task_desc'), $this->input->post('task_link'),
                date('Y-m-d'));
            // insert
            if ($this->m_daily_notes->add_task($params)) {
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
        redirect("home/task/daily_notes/add/");
    }

    // edit task
    public function edit($task_id = "") {
        //set page rule
        $this->_set_page_rule("U");
        //set template content
        $this->smarty->assign("template_content", "home/task/daily_notes/edit.html");
        // get project by user
        $rs_project = $this->m_daily_notes->get_list_project();
        $this->smarty->assign("rs_project", $rs_project);
        // get detail
        $result = $this->m_daily_notes->get_detail_task_by_id(array($task_id, $this->com_user['user_id']));
        $this->smarty->assign("result", $result);
        $this->smarty->assign("detail", $detail);
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // process edit
    public function edit_process() {
        //set page rule
        $this->_set_page_rule("U");
        // get id
        $task_id = $this->input->post('task_id');
        // action        
        $action = $this->input->post('save');
        if ($action == 'Delete') {
            // delete data
            $params = array($task_id, $this->com_user['user_id']);
            $this->m_daily_notes->delete_task($params);
            // --
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            // default redirect
            redirect("home/task/daily_notes/");
        }
        // cek input
        $this->tnotification->set_rules('task_id', 'Task ID', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('project_modul', 'Modul', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('task_desc', 'Uraian Tugas', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('task_link', 'Link', 'trim|maxlength[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $params = array($this->input->post('project_id'), $this->input->post('project_modul'),
                $this->input->post('task_desc'), $this->input->post('task_link'),
                $task_id, $this->com_user['user_id']);
            // insert
            if ($this->m_daily_notes->update_task($params)) {
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
        redirect("home/task/daily_notes/edit/" . $task_id);
    }

}