<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class lpj extends ApplicationBase {

    // my flow
    private $now_flow_id = '14006';
    private $next_flow_id = '14007';
    private $prev_flow_id = '14005';

    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('home/task/m_lpj');
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/task/lpj/list.html");
        // get list
        $rs_id = $this->m_lpj->get_list_spt(array($this->com_user['user_id'], $this->now_flow_id));
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // approval
    public function approval($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/task/lpj/lpj.html");
        //load js
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");
        //get detail data
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id,$spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj/");
        }
        $this->smarty->assign("detail", $detail);
        //get list advance
        $rs_id = $this->m_lpj->get_list_advance_by_spt($spt_id);
        $this->smarty->assign("rs_id", $rs_id);
        //get list lpj
        $rs_lpj = $this->m_lpj->get_list_lpj_by_spt($spt_id);
        $this->smarty->assign("rs_lpj", $rs_lpj);
        //jenis biaya
        $this->smarty->assign("rs_jenis", $this->m_lpj->get_list_jenis_biaya());
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
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
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //lpj_id
            $lpj_id = $this->m_lpj->get_lpj_last_id(date('Y'));
            if(empty($lpj_id)){
                $this->tnotification->sent_notification("error", "ID LPJ tidak tersedia!");
                redirect("home/task/lpj/approval/" . $spt_id . '/' .$process_id);
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
            if ($this->m_lpj->insert_lpj($params)) {
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
        redirect('home/task/lpj/approval/' . $this->input->post('spt_id') .'/' . $this->input->post('process_id'));
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
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj/");
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
            if ($this->m_lpj->update_lpj($params, $where)) {
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
        redirect('home/task/lpj/approval/' . $this->input->post('spt_id') . '/' . $this->input->post('process_id'));
    }

    // delete process
    public function delete_lpj_process($spt_id = "", $process_id = "", $lpj_id = "") {
        // set page rule
        $this->_set_page_rule("D");
        //get detail data
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj/");
        }
        // process
        $where = array('lpj_id' => $lpj_id, 'spt_id' => $spt_id);
        // delete
        if ($this->m_lpj->delete_lpj($where)) {
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
        redirect("home/task/lpj/approval/" . $spt_id . '/' . $process_id);
    }

    // approval process
    public function approval_process($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("U");
        //get detail data spt
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj");
        }
        // echo "<pre>"; print_r($detail); echo "</pre>"; exit();
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
        if ($this->m_lpj->update_flow($params, $where)) {
            //create new flow
            $process_id_new = $this->m_lpj->get_process_last_id(date('Y'));
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
            $this->m_lpj->insert_flow($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disubmit.");
        } else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disubmit.");
        }
        //redirect
        redirect("home/task/lpj/");
    }

    // download excell
    public function download($spt_id = "", $process_id = "") {
        // set page rule
        $this->_set_page_rule("R");
        //get detail data
        $detail = $this->m_lpj->get_detail_spt_by_id(array($spt_id, $spt_id, $spt_id, $process_id, $this->com_user['user_id']));
        if(empty($detail)){
            $this->tnotification->sent_notification("error", "data yang anda pilih tidak terdaftar!");
            redirect("home/task/lpj/");
        }
        //get list advance
        $rs_id = $this->m_lpj->get_list_advance_by_spt($spt_id);
        //get list lpj
        $rs_lpj = $this->m_lpj->get_list_lpj_by_spt($spt_id);
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

}
