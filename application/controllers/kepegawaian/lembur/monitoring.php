<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class monitoring extends ApplicationBase {

    // my flow
    private $now_flow_id = 1;
    private $next_flow_id = 2;
    private $prev_flow_id = "";
    private $group_id = 13;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/lembur/m_monitoring');
        $this->load->model("kepegawaian/lembur/m_pengajuan");        
        $this->load->model('kepegawaian/master/m_pegawai_jabatan');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // view
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/lembur/monitoring/list.html");
        // get tahun
        $tahun = $this->m_monitoring->get_list_tahun();
        $this->smarty->assign("rs_tahun", $tahun);
        // bulan
        $bulan = $this->datetimemanipulation->get_month('in');
        $this->smarty->assign("rs_bulan", $bulan);
        $search = $this->tsession->userdata('monitoring_search');
        //search parameter
        $project_id = empty($search['project_id']) ? '%' : $search['project_id'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $tahun = $tahun;
        $bulan = $bulan;
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/lembur/monitoring/index/");
        // params
        $config['total_rows'] = $this->m_monitoring->get_total_overtime(array($project_id, $tahun, $bulan));
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
        // project list
        $this->smarty->assign('rs_project',$this->m_monitoring->get_all_projects());
        // get list
        $params = array($project_id, $tahun, $bulan,  ($start - 1), $config['per_page']);
        $rs_id = $this->m_monitoring->get_all_overtime_by_limit($params);
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('monitoring_search');
        } else {
            $params = array(
                "project_id" => $this->input->post("project_id"),
                "tahun" => $this->input->post("tahun"),
                "bulan" => $this->input->post("bulan")
            );
            $this->tsession->set_userdata('monitoring_search', $params);
        }
        // redirect
        redirect("kepegawaian/lembur/monitoring");
    }

    // detail
    public function detail($overtime_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/monitoring/detail.html");
        // get detail data
        $result = $this->m_monitoring->get_detail_overtime(array($overtime_id));
        $result['overtime_start'] = substr($result['overtime_start'], 0, 5);
        $result['overtime_end'] = substr($result['overtime_end'], 0, 5);
        $this->smarty->assign("result", $result);
        // personel
        $this->smarty->assign("rs_personel", $this->m_monitoring->get_personil_overtime($overtime_id));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // download 
    public function download($overtime_id = "") {
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/LEMBUR.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get data
        $result = $this->m_pengajuan->get_detail_overtime(array($overtime_id));
        $department_leader = $this->m_pengajuan->get_unit_kerja_leader(array($this->com_user['user_id']));
        if (empty($result)) {
            // default redirect
            redirect("kepegawaian/lembur/pengajuan/");
        }
        $result['overtime_start'] = substr($result['overtime_start'], 0, 5);
        $result['overtime_end'] = substr($result['overtime_end'], 0, 5);
        // personel
        $personil = $this->m_pengajuan->get_personil_overtime($overtime_id);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('B23', 'Yogyakarta, ' . strtoupper($this->datetimemanipulation->get_full_date(substr($result['mdd'], 0, 10))));
        $objWorksheet->setCellValue('B28', strtoupper($this->com_user['user_alias']));
        $objWorksheet->setCellValue('F5', strtoupper($this->datetimemanipulation->get_full_date(substr($result['mdd'], 0, 10))));
        $objWorksheet->setCellValue('D11', strtoupper($result['project_alias']));
        $objWorksheet->setCellValue('D12', strtoupper($result['overtime_reason']));
        $objWorksheet->setCellValue('D13', strtoupper($this->datetimemanipulation->get_full_date($result['overtime_date'])));
        $objWorksheet->setCellValue('D14', strtoupper($result['overtime_start']));
        $objWorksheet->setCellValue('D15', strtoupper($result['overtime_end']));
        $objWorksheet->setCellValue('D28', strtoupper($department_leader));
        // personel
        $i = 16;
        foreach ($personil as $person) {
            $objWorksheet->setCellValue('D' . $i, strtoupper($person['nama_lengkap']));
            $i++;
        }
        // file_name
        $file_name = "SL_" . str_replace(' ', '_', strtoupper($result['project_alias'])) . "_" . str_replace('-', '_', $result['overtime_date']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}