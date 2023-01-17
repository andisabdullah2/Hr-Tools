<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class advance extends ApplicationBase {

    // my flow
    private $now_flow_id = '14003';
    private $next_flow_id ='14004';
    private $prev_flow_id = '14002';

    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/jaldin/m_advance');
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
        //detail pegawai
        $this->pegawai = $this->m_advance->get_detail_pegawai(array($this->com_user['user_id']));
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/advance/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_advance->get_list_tahun());
        // // bulan
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_month('in'));
        // get search parameter
        $search = $this->tsession->userdata('jaldin_advance_search');
        // search parameters
        $nama = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%';
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/jaldin/advance/index/");
        $params = array($this->pegawai['struktur_cd'], $search['tahun'], $bulan, $nama, $this->now_flow_id);
        $config['total_rows'] = $this->m_advance->get_total_spt($params);
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
        $params = array($this->pegawai['struktur_cd'], $search['tahun'], $bulan, $nama, $this->now_flow_id, ($start - 1), $config['per_page']);
        $rs_id = $this->m_advance->get_list_spt($params);
        $this->smarty->assign("rs_id", $rs_id);
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
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('jaldin_advance_search');
        } else {
            $params = array(
                "nama" => $this->input->post("nama", true),
                "bulan" => $this->input->post("bulan", true),
                "tahun" => $this->input->post("tahun", true),
            );
            $this->tsession->set_userdata("jaldin_advance_search", $params);
        }
        // redirect
        redirect("kepegawaian/jaldin/advance/");
    }

    // approval
    public function detail($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/advance/approval.html");
        //load js
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //get detail data spt
        $detail = $this->m_advance->get_detail_spt_by_id(array($spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/advance");
        }
        $this->smarty->assign("detail", $detail);
        //get list advance
        $rs_id = $this->m_advance->get_list_advance_by_spt($spt_id);
        $this->smarty->assign("rs_id", $rs_id);
        //get jenis pengeluaran
        $this->smarty->assign("rs_jenis", $this->m_advance->get_list_jenis_pengeluaran());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add advance process
    public function advance_add_process() {
        // set page rule
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('spt_id', 'SPT ID', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Biaya', 'trim|required');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('jumlah', 'Jumlah', 'trim|required');
        //var
        $process_id = $this->input->post('process_id', true);
        $spt_id = $this->input->post('spt_id', true);
        if(empty($process_id) || empty($spt_id)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/advance/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //advance id
            $advance_id = $this->m_advance->get_advance_last_id(date('Y'));
            //params
            $params = array(
                'advance_id'        => $advance_id,
                'spt_id'            => $spt_id,
                'jenis_id'          => $this->input->post('jenis_id', true),
                'keterangan'        => $this->input->post('keterangan', true),
                'jumlah'            => $this->input->post('jumlah', true),
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s"),
            );
            // insert
            if ($this->m_advance->insert($params)) {
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
        redirect('kepegawaian/jaldin/advance/detail/' . $this->input->post('spt_id') .'/' .  $this->input->post('process_id'));
    }

    // proses edit
    public function advance_edit_process() {
        // set page rule
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('advance_id', 'Advance ID', 'trim|required');
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('spt_id', 'SPT ID', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Biaya', 'trim|required');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('kredit', 'Kredit', 'trim');
        $this->tnotification->set_rules('debet', 'Debet', 'trim');
        //var
        $process_id = $this->input->post('process_id', true);
        $spt_id = $this->input->post('spt_id', true);
        if(empty($process_id) || empty($spt_id)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/advance/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //set parameter
            $params = array(
                'spt_id'            => $spt_id,
                'jenis_id'          => $this->input->post('jenis_id', true),
                'keterangan'        => $this->input->post('keterangan', true),
                'jumlah'            => $this->input->post('jumlah', true),
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s"),
            );
            $where = array('advance_id' => $this->input->post('advance_id', true));
            // update
            if ($this->m_advance->update($params, $where)) {
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
        redirect('kepegawaian/jaldin/advance/detail/' . $this->input->post('spt_id') .'/' .  $this->input->post('process_id'));
    }

    // proses edit
    public function advance_delete_process($spt_id = '', $process_id = '', $advance_id = '') {
        // set page rule
        $this->_set_page_rule("D");
        // process
        $where = array('advance_id' => $advance_id, 'spt_id' => $spt_id);
        // delete
        if ($this->m_advance->delete($where)) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        
        redirect('kepegawaian/jaldin/advance/detail/' . $spt_id .'/' .  $process_id);
    }

    // approval process
    public function approval_process() {
        // set page rule
        $this->_set_page_rule("U");
        // check input
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('duty_id', 'duty ID', 'trim|required');
        // check advanced
        $total = $this->m_advance->is_exist_advance($this->input->post('duty_id'));
        if ($total == 0) {
            $this->tnotification->set_error_message("Belum ada data yang diinputkan!");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // id
            $duty_id = $this->input->post('duty_id');
            // update parameter
            $params = array('approve', $this->com_user['user_id'], $this->flow_id, $duty_id, $this->com_user['department_id']);
            // exec
            if ($this->m_advance->confirm_duty_process($params)) {
                // update self
                $this->m_advance->update_flow_by_id(array('done', 'approve', $this->com_user['user_id'], date('Y-m-d H:i:s'), $this->input->post('process_id')));
                // insert next
                $this->m_advance->insert_flow(array($this->next_flow_id, $duty_id, 'process', 'waiting', NULL, NULL));
                // --
                $this->tnotification->sent_notification("success", "Data berhasil diproses");
                $this->tnotification->delete_last_field();
                // display list
                redirect('kepegawaian/jaldin/advance/detail/' . $duty_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal diproses");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diproses");
        }
        // redirect
        redirect('kepegawaian/jaldin/advance/approval/' . $this->input->post('process_id'));
    }

    // download
    public function approve_process($spt_id = '', $process_id = '') {
        // set page rule
        $this->_set_page_rule("U");
        //get detail data spt
        $detail = $this->m_advance->get_detail_spt_by_id(array($spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/advance");
        }
        //update flow
        $params = array(
            'process_st'        => 'approve',
            'action_st'         => 'done',
            'mdb_finish'        => $this->com_user['user_id'],
            'mdb_finish_name'   => $this->com_user['user_alias'],
            'mdd_finish'        => date('Y-m-d H:m:s')
        );
        $where = array(
            'process_id'    => $process_id,
            'spt_id'        => $spt_id
        );
        if ($this->m_advance->update_flow($params, $where)) {
            //create new flow
            $process_id_new = $this->m_advance->get_process_last_id(date('Y'));
            $params = array(
                'process_id'    => $process_id_new,
                'spt_id'        => $spt_id,
                'flow_id'       => $this->next_flow_id,
                'action_st'     => 'process',
                'process_st'    => 'waiting',
                'process_references_id' => $process_id,
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s"),
            );
            $this->m_advance->insert_flow($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disubmit.");
        } else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disubmit.");
        }
        //redirect
        redirect("kepegawaian/jaldin/advance/");
    }

    public function download($spt_id = '', $process_id = ''){
        // set page rule
        $this->_set_page_rule("U");
        //get detail data spt
        $detail = $this->m_advance->get_detail_spt_by_id(array($spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/advance");
        }
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/JALDIN_ADVANCE.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        //get advance
        $rs_advance = $this->m_advance->get_list_advance_by_spt(array($spt_id));
        // echo "<pre>"; print_r($detail); echo "</pre>"; exit();
        /*
         * SET DATA EXCELL
         */
        //convert mdd to date
        $tgl_pengajuan = date('Y-m-d', strtotime($detail['mdd']));
        //page 1
        $objWorksheet->setCellValue('H13', strtoupper($this->datetimemanipulation->get_full_date($tgl_pengajuan)));
        $objWorksheet->setCellValue('H14', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('H16', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_berangkat'])));
        $objWorksheet->setCellValue('L16', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_pulang'])));
        $objWorksheet->setCellValue('H17', substr($detail['waktu_berangkat'], 0, 5));
        $objWorksheet->setCellValue('H18', substr($detail['waktu_pulang'], 0, 5));
        $objWorksheet->setCellValue('T13', strtoupper($detail['lokasi_tujuan']));
        $objWorksheet->setCellValue('T14', strtoupper($detail['client_nm']));
        $objWorksheet->setCellValue('T15', strtoupper($detail['client_address']));
        $objWorksheet->setCellValue('T16', strtoupper($detail['uraian_tugas']));
        //set cell value
        $cellno = 21;
        $no = 1;
        $totalbiaya = 0;
        foreach ($rs_advance as $result) {
            $objWorksheet->setCellValue('C' . $cellno, $no++);
            $objWorksheet->setCellValue('D' . $cellno, ucfirst(strtolower($result['jenis_biaya'])));
            $objWorksheet->setCellValue('M' . $cellno, number_format($result['jumlah'],2,',','.'));
            $objWorksheet->setCellValue('R' . $cellno, ucfirst(strtolower($result['keterangan'])));
            $totalbiaya += $result['jumlah'];
            $cellno++;
        }
        $objWorksheet->setCellValue('M26', number_format($totalbiaya,2,',','.'));
        // // page 2
        $objWorksheet->setCellValue('H53', strtoupper($this->datetimemanipulation->get_full_date($tgl_pengajuan)));
        $objWorksheet->setCellValue('H54', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('H56', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_berangkat'])));
        $objWorksheet->setCellValue('H57', substr($detail['waktu_berangkat'], 0, 5));
        $objWorksheet->setCellValue('H58', substr($detail['waktu_pulang'], 0, 5));
        $objWorksheet->setCellValue('L56', strtoupper($this->datetimemanipulation->get_full_date($detail['tanggal_pulang'])));
        $objWorksheet->setCellValue('T53', strtoupper($detail['lokasi_tujuan']));
        $objWorksheet->setCellValue('T54', strtoupper($detail['client_nm']));
        $objWorksheet->setCellValue('T55', strtoupper($detail['client_address']));
        $objWorksheet->setCellValue('T56', strtoupper($detail['uraian_tugas']));
        // set cell value
        $cellno = 61;
        $no = 1;
        foreach ($rs_advance as $result) {
            $objWorksheet->setCellValue('C' . $cellno, $no++);
            $objWorksheet->setCellValue('D' . $cellno, ucfirst(strtolower($result['jenis_biaya'])));
            $objWorksheet->setCellValue('M' . $cellno, number_format($result['jumlah'],2,',','.'));
            $objWorksheet->setCellValue('R' . $cellno, ucfirst(strtolower($result['keterangan'])));
            $cellno++;
        }
        $objWorksheet->setCellValue('M66', number_format($totalbiaya,2,',','.'));
        // // file_name
        $file_name = "ADV_" . str_replace('-', '_', strtoupper($detail['project_alias'])) . "_" . str_replace('-', '_', $detail['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}