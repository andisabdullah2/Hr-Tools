<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// load base
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --
class dashboard extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('home/welcome/m_dashboard');
        // load library
        $this->load->library('tnotification');
    }

    // dashboard
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/welcome/dashboard/index.html");
        // load javascript
        $this->smarty->load_javascript('resource/js/highchart/js/highcharts.js');
        $this->smarty->load_javascript('resource/js/highchart/js/highcharts-3d.js');
        $this->smarty->load_javascript('resource/js/highchart/js/modules/exporting.js');
        // get last login
        $last_login = $this->m_dashboard->get_last_login($this->com_user['user_id']);
        $this->smarty->assign("last_login", $last_login);
        // get summary jaldin belum dilaporkan
        $total_lpj = $this->m_dashboard->get_total_jaldin_waiting($this->com_user['user_id']);
        $this->smarty->assign("total_lpj", $total_lpj);
        // get summary cuti by tahun
        $total_cuti_tahunan = $this->m_dashboard->get_summary_cuti_by_tahun(array(date('Y'), date('Y'), $this->com_user['user_id']));
        $this->smarty->assign("total_cuti_tahunan", $total_cuti_tahunan);
        // summary ijin
        $total_ijin_tahunan = $this->m_dashboard->get_summary_ijin_by_tahun(array(date('Y'), $this->com_user['user_id']));
        $this->smarty->assign("total_ijin_tahunan", $total_ijin_tahunan);
        // get total task
        $total_task = $this->m_dashboard->get_total_task_by_user(array($this->com_user['user_id']));
        $this->smarty->assign("total_task", $total_task);
        // list total task approval - perjalanan dinas
        $total_approval['jaldin'] = $this->m_dashboard->get_total_task_approval_jaldin_by_user(array($this->com_user['user_id']));
        $total_approval['cuti'] = $this->m_dashboard->get_total_task_approval_cuti_by_user(array($this->com_user['user_id']));
        $total_approval['ijin'] = $this->m_dashboard->get_total_task_approval_ijin_by_user(array($this->com_user['user_id']));
        $total_approval['lembur'] = $this->m_dashboard->get_total_task_approval_lembur_by_user(array($this->com_user['user_id']));
        // total approval
        $this->smarty->assign("total_approval", array_sum($total_approval));
        /*
         * Kedisiplinan
         */
        // $total_hari_kerja = $this->m_dashboard->get_total_hari_kerja(array(date('Y'), date('m'), date('Y-m-t')));
        $total_hari_kerja = 0;
        $this->smarty->assign("total_hari_kerja", $total_hari_kerja);
        // keterlambatan
        $total_keterlambatan = $this->m_dashboard->get_total_keterlambatan(array($this->com_user['user_id'], date('Y'), date('m')));
        $this->smarty->assign("total_keterlambatan", $total_keterlambatan);
        // ijin
        $total_ijin = $this->m_dashboard->get_total_ijin(array($this->com_user['user_id'], date('Y'), date('m')));
        $this->smarty->assign("total_ijin", $total_ijin);
        // cuti
        $total_cuti = $this->m_dashboard->get_total_cuti(array($this->com_user['user_id'], date('Y'), date('m')));
        $this->smarty->assign("total_cuti", $total_cuti);
        // lembur
        $total_lembur = $this->m_dashboard->get_total_lembur(array($this->com_user['user_id'], date('Y'), date('m')));
        $this->smarty->assign("total_lembur", $total_lembur);
        // jaldin
        $total_jaldin = $this->m_dashboard->get_total_jaldin(array($this->com_user['user_id'], date('Y'), date('m')));
        $this->smarty->assign("total_jaldin", $total_jaldin);
        // summary jaldin
        $rs_jaldin = $this->m_dashboard->get_list_jaldin_by_user(array($this->com_user['user_id']));
        $this->smarty->assign("rs_jaldin", $rs_jaldin);
        // Personel Info
        $rs_personel_info = $this->m_dashboard->get_list_personel_info_by_today(array($this->com_user['user_id'], $this->com_user['user_id'], $this->com_user['user_id']));
        $this->smarty->assign("rs_personel_info", $rs_personel_info);
        // output
        parent::display();
    }

    // chart otp
    public function get_data_chart_otp() {
        // set page rules
        $this->_set_page_rule("R");
        // header
        header("content-type: application/json");
        // get data
        $colors = array('#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a');
        $data = array();
        // data otp
        $rs_id = $this->m_dashboard->get_chart_otp_bulanan(array($this->com_user['user_id'], date('Y')));
        $rs_bulan = $this->datetimemanipulation->arr_lang['ins'];
        // data
        $categories = array();
        $data = array();
        $colors = array();
        // get data
        foreach ($rs_bulan as $bulan_index => $bulan_label) {
            $categories[] = $bulan_label;
            $otp_value = intval(isset($rs_id[intval($bulan_index)]) ? $rs_id[intval($bulan_index)] : 0);
            $data[] = $otp_value;
            if ($otp_value < 70) {
                $color = '#c42525';
            } elseif ($otp_value < 85) {
                $color = '#f28f43';
            } else {
                $color = "#7cb5ec";
            }
            $colors[] = $color;
        }
        // assign data
        $result = array(
            "colors" => $colors,
            'categories' => $categories,
            'data_series' => array(
                array(
                    "name" => 'OTP',
                    "data" => $data,
                ),
            ),
        );
        // output
        echo json_encode($result);
    }

}
