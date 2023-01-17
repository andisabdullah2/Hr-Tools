<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class verifikasi_hrd extends ApplicationBase {

    // my flow
    private $now_flow_id = '14007';
    private $next_flow_id = '14008';
    private $prev_flow_id = '14006';

    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/jaldin/m_verifikasi_hrd');
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
        //detail pegawai
        $this->pegawai = $this->m_verifikasi_hrd->get_detail_pegawai(array($this->com_user['user_id']));
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/verifikasi_hrd/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_verifikasi_hrd->get_list_tahun());
        // bulan
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_month('in'));
        // get search parameter
        $search = $this->tsession->userdata('jaldin_verifikasi_hrd_search');
        // search parameters
        $nama = empty($search['nama']) ? '%' : '%' . $search['nama'] . '%';
        $bulan = empty($search['bulan']) ? '%' : (int)$search['bulan'];
        $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/jaldin/verifikasi_hrd/index/");
        $params = array($this->pegawai['struktur_cd'], $search['tahun'], $bulan, $nama, $this->now_flow_id);
        $config['total_rows'] = $this->m_verifikasi_hrd->get_total_spt($params);
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
        $rs_id = $this->m_verifikasi_hrd->get_list_spt($params);
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
            $this->tsession->unset_userdata('jaldin_verifikasi_hrd_search');
        } else {
            $params = array(
                "nama" => $this->input->post("nama", true),
                "bulan" => $this->input->post("bulan", true),
                "tahun" => $this->input->post("tahun", true),
            );
            $this->tsession->set_userdata("jaldin_verifikasi_hrd_search", $params);
        }
        // redirect
        redirect("kepegawaian/jaldin/verifikasi_hrd/");
    }

    // approval
    public function approval($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/verifikasi_hrd/approval.html");
        //load js
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        // get detail data
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd");
        }
        $this->smarty->assign("detail", $detail);
        //get list advance
        $rs_id = $this->m_verifikasi_hrd->get_list_advance_by_spt($spt_id);
        $this->smarty->assign("rs_id", $rs_id);
        //get list lpj
        $rs_lpj = $this->m_verifikasi_hrd->get_list_lpj_by_spt($spt_id);
        $this->smarty->assign("rs_lpj", $rs_lpj);
        //jenis biaya
        $this->smarty->assign("rs_jenis", $this->m_verifikasi_hrd->get_list_jenis_biaya());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // approval process
    public function approval_process($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("U");
        //get detail data spt
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd");
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
        if ($this->m_verifikasi_hrd->update_flow($params, $where)) {
            //create new flow
            $process_id_new = $this->m_verifikasi_hrd->get_process_last_id(date('Y'));
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
            $this->m_verifikasi_hrd->insert_flow($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disubmit.");
        } else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disubmit.");
        }
        //redirect
        redirect("kepegawaian/jaldin/verifikasi_hrd/");
    }    

    // proses add data lpj
    public function lpj_add_process() {
        // set page rule
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('spt_id', 'SPT ID', 'trim|required');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Biaya', 'trim|required');
        $this->tnotification->set_rules('debit', 'Debet', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Kredit', 'trim');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim');
        //get detail spt
        $process_id = $this->input->post('process_id', true);
        $spt_id = $this->input->post('spt_id', true);
        //get detail data
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //lpj_id
            $lpj_id = $this->m_verifikasi_hrd->get_lpj_last_id(date('Y'));
            if(empty($lpj_id)){
                $this->tnotification->sent_notification("error", "ID LPJ tidak tersedia!");
                redirect("kepegawaian/jaldin/verifikasi_hrd/approval/" . $spt_id . '/' .$process_id);
            }
            //params
            $params = array(
                'lpj_id'        => $lpj_id,
                'spt_id'        => $spt_id,
                'tanggal'       => $this->input->post('tanggal', true),
                'jenis_id'      => $this->input->post('jenis_id', true),
                'keterangan'    => $this->input->post('keterangan', true),
                'debit'         => $this->input->post('debit', true),
                'kredit'        => $this->input->post('kredit', true),
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_verifikasi_hrd->insert_lpj($params)) {
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
        redirect('kepegawaian/jaldin/verifikasi_hrd/approval/' . $spt_id . '/' . $process_id);
    }

    // proses edit data lpj
    public function lpj_edit_process() {
        // set page rule
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('process_id', 'Process ID', 'trim|required');
        $this->tnotification->set_rules('spt_id', 'SPT ID', 'trim|required');
        $this->tnotification->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Biaya', 'trim|required');
        $this->tnotification->set_rules('debit', 'Debet', 'trim|required');
        $this->tnotification->set_rules('kredit', 'Kredit', 'trim');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim');
        //get detail spt
        $process_id = $this->input->post('process_id', true);
        $spt_id = $this->input->post('spt_id', true);
        $lpj_id = $this->input->post('lpj_id', true);
        //get detail data
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $params = array(
                'tanggal'       => $this->input->post('tanggal', true),
                'jenis_id'      => $this->input->post('jenis_id', true),
                'keterangan'    => $this->input->post('keterangan', true),
                'debit'         => $this->input->post('debit', true),
                'kredit'        => $this->input->post('kredit', true),
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s")
            );
            $where = array('lpj_id' => $lpj_id, 'spt_id' => $spt_id);
            // update
            if ($this->m_verifikasi_hrd->update_lpj($params, $where)) {
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
        redirect('kepegawaian/jaldin/verifikasi_hrd/approval/' . $this->input->post('spt_id') . '/' . $this->input->post('process_id'));
    }

    /// delete process
    public function delete_lpj_process($spt_id = "", $process_id = "", $lpj_id = "") {
        // set page rule
        $this->_set_page_rule("D");
        //get detail data
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd/");
        }
        // process
        $where = array('lpj_id' => $lpj_id, 'spt_id' => $spt_id);
        // delete
        if ($this->m_verifikasi_hrd->delete_lpj($where)) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // notification
        $this->tnotification->delete_last_field();
        $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        // default redirect
        redirect("kepegawaian/jaldin/verifikasi_hrd/approval/" . $spt_id . '/' . $process_id);
    }

    // download excell
    public function download($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        //get detail data
        $detail = $this->m_verifikasi_hrd->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/verifikasi_hrd/");
        }
        //get list advance
        $rs_id = $this->m_verifikasi_hrd->get_list_advance_by_spt($spt_id);
        //get list lpj
        $rs_lpj = $this->m_verifikasi_hrd->get_list_lpj_by_spt($spt_id);
        // echo "<pre>"; print_r($detail); echo "</pre>"; exit();
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/LPJ.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // parse data
        $objWorksheet->setCellValue('D9', strtoupper($detail['nama_lengkap']));
        $objWorksheet->setCellValue('D11', 'Rp. ' . number_format($detail['total_advance'],2,',','.'));
        $objWorksheet->setCellValue('G9', ': ' . $this->datetimemanipulation->get_short_date($detail['tanggal_berangkat']) . ' s/d ' . $this->datetimemanipulation->get_short_date($detail['tanggal_pulang']));
        $objWorksheet->setCellValue('G10', ': ' . strtoupper($detail['uraian_tugas']));
        $objWorksheet->setCellValue('G11', strtoupper($detail['project_name']));
        $objWorksheet->setCellValue('G71', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d')));
        $objWorksheet->setCellValue('G76', strtoupper($detail['nama_lengkap']));
        // lpj
        $no = 1;
        $row = 16;
        $saldo = 0;
        $total_debit = 0;
        $total_kredit = $detail['total_advance'];
        $total_saldo = 0;
        //penerimaan kasir
        $objWorksheet->setCellValue('F15', 'Rp. ' . number_format($detail['total_advance'],2,',','.'));
        foreach ($rs_lpj as $data) {
            //total
            $total_kredit += $data['kredit'];
            $total_debit += $data['debit'];
            $saldo = $total_kredit - $total_debit;
            //write
            $objWorksheet->setCellValue('A' . $row,  ' ' . $no);
            $objWorksheet->setCellValue('B' . $row, $data['tanggal']);
            $objWorksheet->setCellValue('C' . $row, $data['keterangan']);
            if($data['kredit'] == 0){
                $objWorksheet->setCellValue('F' . $row, '');
            }else{
                $objWorksheet->setCellValue('F' . $row, 'Rp. ' . number_format($data['kredit'],2,',','.'));
            }
            if($data['debit'] == 0){
                $objWorksheet->setCellValue('G' . $row, '');
            }else{
                $objWorksheet->setCellValue('G' . $row, 'Rp. ' . number_format($data['debit'],2,',','.'));
            }
            $objWorksheet->setCellValue('H' . $row, 'Rp. ' . number_format($saldo,2,',','.'));
            // loop
            $no++;
            $row++;
        }
        // exit();
        $objWorksheet->setCellValue('F62', 'Rp. ' . number_format($total_kredit,2,',','.'));
        $objWorksheet->setCellValue('G62', 'Rp. ' . number_format($total_debit,2,',','.'));
        $objWorksheet->setCellValue('H62', 'Rp. ' . number_format($saldo,2,',','.'));
        //total penerimaan
        $objWorksheet->setCellValue('D64', 'Rp. ' . number_format($total_kredit,2,',','.'));
        $objWorksheet->setCellValue('D65', 'Rp. ' . number_format($total_debit,2,',','.'));
        $objWorksheet->setCellValue('D66', 'Rp. ' . number_format(($total_kredit - $total_debit),2,',','.'));
        // echo $saldo .'<br>' . $total_kredit . '<br>' .$total_debit . '<br>' . $total_saldo; exit();
        // file_name
        $file_name = "LPJ_" . str_replace(' ', '_', strtoupper($detail['nama_lengkap'])) . "_" . str_replace('-', '_', $detail['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    // download excell
    public function download_tunjangan($duty_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // get detail data
        $jaldin = $this->m_verifikasi_hrd->get_detail_jaldin_by_id(array($duty_id));
        $tunjangan = array($tunjangan);
        if (!empty($jaldin)) {
            // total advance
            $tunjangan = $this->m_verifikasi_hrd->get_tunjangan_jaldin(array($jaldin['duty_id']));
        }
        // create excell
        $filepath = "resource/doc/template/JALDIN_TUNJANGAN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // parse data
        $objWorksheet->setCellValue('B8', '1');
        $objWorksheet->setCellValue('C8', strtoupper($jaldin['full_name']));
        $objWorksheet->setCellValue('D8', $jaldin['department_name']);
        $objWorksheet->setCellValue('E8', substr($this->datetimemanipulation->get_short_date($jaldin['date_start']), 2, 8) . ' - ' . substr($this->datetimemanipulation->get_short_date($jaldin['date_end']), 2, 8));
        $objWorksheet->setCellValue('F8', $jaldin['duty_location']);
        $objWorksheet->setCellValue('G8', $tunjangan['uang saku']);
        $objWorksheet->setCellValue('H8', $tunjangan['makan']);
        $objWorksheet->setCellValue('H12', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(date('Y-m-d')));
        $objWorksheet->setCellValue('H17', strtoupper($jaldin['full_name']));
        // file_name
        $file_name = "TUNJANGAN_" . str_replace(' ', '_', strtoupper($jaldin['full_name'])) . "_" . str_replace('-', '_', $jaldin['date_start']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}
