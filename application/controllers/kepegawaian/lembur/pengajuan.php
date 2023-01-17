<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pengajuan extends ApplicationBase {

    // my flow
    private $now_flow_id = 13001;
    private $next_flow_id = 13002;
    private $prev_flow_id = "";
    private $group_id = 13;

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/lembur/m_pengajuan');
        $this->load->model('kepegawaian/master/m_pegawai_jabatan');
        // load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
    }

    // list pengajuan lembur
    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/list.html");
        //get tahun
        $this->smarty->assign("rs_tahun", $this->m_pengajuan->get_list_tahun());
        //bulan
        $bulan = $this->datetimemanipulation->get_month('in');
        $this->smarty->assign("rs_bulan", $bulan);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("kepegawaian/lembur/pengajuan/index/");
        $config['total_rows'] = $this->m_pengajuan->get_total_overtime($this->now_flow_id);
        $config['uri_segment'] = 4;
        $config['per_page'] = 50;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();
        // pagination attribute
        $start = $this->uri->segment(4, 0) + 1;
        $end = $this->uri->segment(4, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];
        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $flow_id = $this->now_flow_id;
        $params = array($flow_id, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_all_overtime_by_limit($params));
        // get revisi status
        $this->smarty->assign("rs_revisi", $this->m_pengajuan->get_revisi_overtime($flow_id));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // add pengajuan lembur
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/add.html");
        // javascript load
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");
        // get struktur cd
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        // get project by struktur cd
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_project_by_struktur($struktur_cd));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('overtime_date', 'Tanggal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('overtime_start', 'Mulai', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('overtime_end', 'Selesai', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('overtime_reason', 'Keterangan', 'trim|required|max_length[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $overtime_start = $this->input->post('overtime_start', TRUE);
            $overtime_end = $this->input->post('overtime_end', TRUE);
            if (strtotime($overtime_start) > strtotime($overtime_end)) {
               $overtime_start = $this->input->post('overtime_end', TRUE);
               $overtime_end = $this->input->post('overtime_start', TRUE);
            }else{
                $overtime_start = $this->input->post('overtime_start', TRUE);
                $overtime_end = $this->input->post('overtime_end', TRUE);
            }

            $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
            $overtime_id = $this->m_pengajuan->generate_overtime_id($this->input->post('overtime_date'));
            $overtime_st = 'draft';
            $params = array(
                            'overtime_id' => $overtime_id,
                            'struktur_cd' => $struktur_cd,
                            'project_id' => $this->input->post('project_id', TRUE),
                            'overtime_date' => $this->input->post('overtime_date', TRUE),
                            'overtime_start' => $overtime_start,
                            'overtime_end' => $overtime_end,
                            'overtime_reason' => $this->input->post('overtime_reason', TRUE),
                            'overtime_st' =>  $overtime_st,
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                            );
            // insert surat lembur
            if ($this->m_pengajuan->insert_surat_lembur($params)) {
                // surat lembur process
                $process_id = $this->m_pengajuan->get_microtime();
                $flow_id = $this->now_flow_id;
                $action_st = 'process';
                $params = array (
                                'process_id' => $process_id,
                                'overtime_id' => $overtime_id,
                                'flow_id' => $flow_id,
                                'action_st' => $action_st,
                                'mdb' => $this->com_user['user_id'],
                                'mdb_name' => $this->com_user['user_alias'],
                                'mdd' => date("Y-m-d H:i:s")
                                );
                $this->m_pengajuan->insert_surat_lembur_process($params);
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect('kepegawaian/lembur/pengajuan/personil_add/' . $overtime_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/add");
    }

    // add personil
    public function personil_add ($overtime_id = ""){
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/add_personil.html");
        // javascript load
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");
        // get struktur cd by user id
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $struktur_cd = empty($struktur_cd) ? '%' : $struktur_cd;
        // get personel by struktur cd (department)
        $this->smarty->assign("rs_user", $this->m_pengajuan->get_user_by_department($struktur_cd));
        $this->smarty->assign("rs_struktur", $this->m_pengajuan->get_struktur_by_cd($struktur_cd));
        $this->smarty->assign("user_id", $this->com_user['user_id']);
        $this->smarty->assign("struktur_cd", $struktur_cd);
        $this->smarty->assign("overtime_id", $overtime_id);
        // get detail overtime
        $this->smarty->assign("rs_overtime", $this->m_pengajuan->get_detail_overtime($overtime_id));
        // get personil overtime
        $this->smarty->assign("rs_personil", $this->m_pengajuan->get_personil_overtime($overtime_id));
        //overtime_id
        $this->smarty->assign("overtime_id", $overtime_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add personil process
    public function personil_add_process (){
        // cek input
        $this->tnotification->set_rules('user_id', 'Personil Lembur', 'trim|required');
        //process
        if ($this->tnotification->run() !== FALSE) {
            $user_id = $this->input->post('user_id');
            $overtime_id = $this->input->post('overtime_id');
            $params = array(
                            'user_id' => $user_id,
                            'overtime_id' => $overtime_id
                            );
            if ($this->m_pengajuan->insert_personil($params)){
                //notification
                $this->tnotification->sent_notification("success", "Personil Berhasil Ditambahkan");
                redirect("kepegawaian/lembur/pengajuan/personil_add/".$overtime_id);
            }else {
                //notification
                $this->tnotification->sent_notification("error", "Personil Sudah Ada");
                redirect("kepegawaian/lembur/pengajuan/personil_add/".$overtime_id);
            }
        }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/personil_add/".$overtime_id);
    }

    //delete personil
    public function personil_delete_process($overtime_id = "",$user_id = ""){
        //parameter
        $where = array(
                        'overtime_id' => $overtime_id,
                        'user_id' => $user_id
                        );
        if($this->m_pengajuan->delete_personil($where)){
            $this->tnotification->sent_notification("success", "Personil Berhasil Dihapus");
            // redirect when delete success
            redirect("kepegawaian/lembur/pengajuan/personil_add/".$overtime_id);
        }else{
            $this->tnotification->sent_notification("error", "Personil Gagal Dihapus");
            // redirect when delete success
            redirect("kepegawaian/lembur/pengajuan/personil_add/".$overtime_id);
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/add");
    }

    
    // form edit
    public function edit($overtime_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // validasi status
        $params = array(
                        'overtime_id' => $overtime_id,
                        'flow_id' => $this->now_flow_id
                        );
        $status = $this->m_pengajuan->get_overtime_st($params);
        if ($status == 'waiting') {
            $this->tnotification->sent_notification("error", "Data tidak dapat diubah karena data sudah diproses");
            redirect('/kepegawaian/lembur/pengajuan');
        }
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/edit.html");
        // detail
        $this->smarty->assign("result", $this->m_pengajuan->get_detail_overtime(array($overtime_id)));
        /// get project
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_all_projects());
        
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // untuk user
        $data = $this->tnotification->get_field_data();
        // output
        parent::display();
    }

    // process edit
    public function edit_process() {
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('overtime_date', 'Tanggal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('overtime_start', 'Mulai', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('overtime_end', 'Selesai', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('overtime_reason', 'Keterangan', 'trim|required|max_length[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $overtime_start = $this->input->post('overtime_start', TRUE);
            $overtime_end = $this->input->post('overtime_end', TRUE);
            if (strtotime($overtime_start) > strtotime($overtime_end)) {
               $overtime_start = $this->input->post('overtime_end', TRUE);
               $overtime_end = $this->input->post('overtime_start', TRUE);
            }else{
                $overtime_start = $this->input->post('overtime_start', TRUE);
                $overtime_end = $this->input->post('overtime_end', TRUE);
            }

            $overtime_id = $this->input->post('overtime_id', TRUE);
            $params = array(
                            'project_id' => $this->input->post('project_id', TRUE),
                            'overtime_date' => $this->input->post('overtime_date', TRUE),
                            'overtime_start' => $overtime_start,
                            'overtime_end' => $overtime_end,
                            'overtime_reason' => $this->input->post('overtime_reason', TRUE),
                            'mdb' => $this->com_user['user_id'],
                            'mdb_name' => $this->com_user['user_alias'],
                            'mdd' => date("Y-m-d H:i:s")
                            );
            // insert surat lembur
            $where = array ('overtime_id' => $overtime_id);
            if ($this->m_pengajuan->update_surat_lembur($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect('kepegawaian/lembur/pengajuan/personil_edit/' . $overtime_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/edit/" . $this->input->post('overtime_id'));
    }

    // edit personil
    public function personil_edit($overtime_id = ""){
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/edit_personil.html");
        // get data
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $struktur_cd = empty($struktur_cd) ? '%' : $struktur_cd;
        // get personel by struktur cd (department)
        $this->smarty->assign("rs_user", $this->m_pengajuan->get_user_by_department($struktur_cd));
        $this->smarty->assign("rs_struktur", $this->m_pengajuan->get_struktur_by_cd($struktur_cd));
        $this->smarty->assign("user_id", $this->com_user['user_id']);
        $this->smarty->assign("struktur_cd", $struktur_cd);
        $this->smarty->assign("overtime_id", $overtime_id);
        // get detail overtime
        $this->smarty->assign("rs_overtime", $this->m_pengajuan->get_detail_overtime($overtime_id));
        // get personil overtime
        $this->smarty->assign("rs_personil", $this->m_pengajuan->get_personil_overtime($overtime_id));
        //overtime_id
        $this->smarty->assign("overtime_id", $overtime_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    //edit personil process
    public function personil_edit_process(){
        // cek input
        $this->tnotification->set_rules('user_id', 'Personil Lembur', 'trim|required');
        //process
        if ($this->tnotification->run() !== FALSE) {
            $user_id = $this->input->post('user_id');
            $overtime_id = $this->input->post('overtime_id');
            $params = array(
                            'user_id' => $user_id,
                            'overtime_id' => $overtime_id
                            );
            if ($this->m_pengajuan->insert_personil($params)){
                //notification
                $this->tnotification->sent_notification("success", "Personil Berhasil Ditambahkan");
                redirect("kepegawaian/lembur/pengajuan/personil_edit/".$overtime_id);
            }else {
                //notification
                $this->tnotification->sent_notification("error", "Personil Sudah Ada");
                redirect("kepegawaian/lembur/pengajuan/personil_edit/".$overtime_id);
            }
        }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/personil_edit/".$overtime_id);
    }

    // edit (delete) process
    public function personil_edit_delete_process($overtime_id = "",$user_id = ""){
        //parameter
        $where = array(
                        'overtime_id' => $overtime_id,
                        'user_id' => $user_id
                        );
        if($this->m_pengajuan->delete_personil($where)){
            $this->tnotification->sent_notification("success", "Personil Berhasil Dihapus");
            // redirect when delete success
            redirect("kepegawaian/lembur/pengajuan/personil_edit/".$overtime_id);
        }else{
            $this->tnotification->sent_notification("error", "Personil Gagal Dihapus");
            // redirect when delete success
            redirect("kepegawaian/lembur/pengajuan/personil_edit/".$overtime_id);
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/edit/".$overtime_id);
    }

    // delete
    public function delete($overtime_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        //validasi id kosong
        if (empty($overtime_id)) {
            redirect('kepegawaian/lembur/pengajuan');
        }
        //validasi status overtime
        if ($this->m_pengajuan->get_overtime_st(array($overtime_id)) != 'draft') {
            $this->tnotification->sent_notification("error", "Data tidak dapat dihapus.");
            redirect('kepegawaian/lembur/pengajuan');
        }
        //validasi flow overtime (jumlah flow)
        if ($this->m_pengajuan->get_flow_count(array($overtime_id)) > 1) {
            $this->tnotification->sent_notification("error", "Data tidak dapat dihapus.");
            redirect('/kepegawaian/lembur/pengajuan');
        }
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/delete.html");
        // get detail data
        $result = $this->m_pengajuan->get_detail_overtime(array($overtime_id));
        $result['overtime_start'] = substr($result['overtime_start'], 0, 5);
        $result['overtime_end'] = substr($result['overtime_end'], 0, 5);
        $this->smarty->assign("result", $result);
        // get detail overtime
        $this->smarty->assign("rs_overtime", $this->m_pengajuan->get_detail_overtime($overtime_id));
        // get personil overtime
        $this->smarty->assign("rs_personil", $this->m_pengajuan->get_personil_overtime($overtime_id));
        //overtime_id
        $this->smarty->assign("overtime_id", $overtime_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process hapus
    public function delete_process() {
        // cek input
        $this->tnotification->set_rules('overtime_id', 'ID', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $overtime_id = $this->input->post('overtime_id');

            $params = array('overtime_id' => $overtime_id);

            // delete surat lembur process
            if ($this->m_pengajuan->delete_surat_lembur_process($params)){
                // delete surat lembur
                $this->m_pengajuan->delete_surat_lembur($params);
                // delete pegawai lembur
                $this->m_pengajuan->delete_personil($params);
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                redirect("kepegawaian/lembur/pengajuan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }

        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/lembur/pengajuan/delete/" . $this->input->post('overtime_id', 0));
    }

    // cetak view
    public function cetak($overtime_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/lembur/pengajuan/cetak.html");
        // get detail overtime
        $this->smarty->assign("rs_overtime", $this->m_pengajuan->get_detail_overtime($overtime_id));
        // get struktur
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $this->smarty->assign("rs_struktur", $this->m_pengajuan->get_struktur_by_cd($struktur_cd));
        // get personil overtime
        $this->smarty->assign("rs_personil", $this->m_pengajuan->get_personil_overtime($overtime_id));
        //overtime_id
        $this->smarty->assign("overtime_id", $overtime_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // cetak process
    public function cetak_process($overtime_id = "") {
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
        $objWorksheet->setCellValue('B28', strtoupper($result['mdb_name']));
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

    // proses kirim
    public function kirim_process($overtime_id = ""){
        // set page rules
        $this->_set_page_rule("U");
        $params = array(
                        'overtime_st' =>'waiting',
                        'overtime_send_by' => $this->com_user['user_id'],
                        'overtime_send_by_name' => $this->com_user['user_alias'],
                        'overtime_send_date' => date("Y-m-d H:i:s")
                        );
        $where = array('overtime_id' => $overtime_id);
        if ($this->m_pengajuan->update_surat_lembur($params,$where)){
            //update flow
            $flow_id = $this->now_flow_id;
            $params = array(
                'flow_id' => $flow_id,
                'process_st' => 'approve',
                'action_st' => 'done',
                'mdb_finish' => $this->com_user['user_id'],
                'mdb_finish_name' => $this->com_user['user_alias'], 
                'mdd_finish' => date("Y-m-d H:i:s")
            );
            $where = array(
                'overtime_id' => $overtime_id, 
                'flow_id' => $flow_id
            );
            $this->m_pengajuan->update_surat_lembur_process($params,$where);
            //create next flow
            $flow_revisi = null;
            $params = array(
                            'overtime_id' => $overtime_id,
                            'flow_id' => $this->now_flow_id
                            );
            $process_references_id = $this->m_pengajuan->get_process_id($params);
            /*
            if($this->m_pengajuan->check_if_have_references($overtime_id) > 0){
                $flow_revisi = $this->now_flow_id;
                $process_references_id = $this->m_pengajuan->get_process_reference($overtime_id);
            }
            */
            $params = array(
                    'process_id' => $this->m_pengajuan->get_microtime(),
                    'overtime_id' => $overtime_id,
                    'flow_id' => $this->next_flow_id, 
                    'flow_revisi_id' => $flow_revisi, 
                    'process_references_id' => $process_references_id,
                    'process_st' => 'waiting',
                    'action_st' => 'process', 
                    'mdb' => $this->com_user['user_id'], 
                    'mdb_name' => $this->com_user['user_alias'], 
                    'mdd' => date('Y-m-d H:i:s')            
            );
            $this->m_pengajuan->insert_surat_lembur_process($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Lembur berhasil diajukan");

        }else{
            $this->tnotification->sent_notification("error", "Data gagal dikirim");
            redirect("kepegawaian/lembur/kirim/".$overtime_id);
        }
        //default redirect
        redirect("kepegawaian/lembur/pengajuan/");
    }

}