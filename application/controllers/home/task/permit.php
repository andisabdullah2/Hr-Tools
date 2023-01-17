<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class permit extends ApplicationBase {

    // my flow
    private $now_flow_id = '12001';
    private $next_flow_id = '12002';
    private $prev_flow_id = "";

    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("home/task/m_permit");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "home/task/permit/list.html");
        // get tahun
        $this->smarty->assign("rs_tahun", $this->m_permit->get_list_tahun());
        // bulan
        $bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );
        $this->smarty->assign("rs_bulan", $bulan);
        // get search parameter
        $search = $this->tsession->userdata('permit_search');
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $bulan = empty($search['bulan']) ? date('m') : $search['bulan'];
        $department = empty($this->com_user['struktur_cd']) ? '%' : $this->com_user['struktur_cd'];
        $search['tahun'] = $tahun;
        $search['bulan'] = $bulan;
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("home/task/permit/index/");
        $config['total_rows'] = $this->m_permit->get_total_permit(array($full_name, $tahun, $this->com_user['user_id']));
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
        $params = array($full_name, $tahun, $this->com_user['user_id'], ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_permit->get_all_permit_by_limit($params));
        // notification
        $this->tnotification->display_notification();
        //output
        parent::display();
    }

    // search process
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('permit_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name",true),
                "tahun" => $this->input->post("tahun",true),
                "bulan" => $this->input->post("bulan",true)
            );
            $this->tsession->set_userdata("permit_search", $params);
        }
        // redirect
        redirect("home/task/permit");
    }

    // add permit
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "home/task/permit/add.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");        
        // get permit type
        $this->smarty->assign("rs_type", $this->m_permit->get_permit_type('permit'));
        // personel by department
        $struktur_cd = $this->m_permit->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $struktur_cd = empty($struktur_cd) ? '%' : $struktur_cd;
        $this->smarty->assign("rs_pic", $this->m_permit->get_user_by_department($struktur_cd));
        $this->smarty->assign("user_id", $this->com_user['user_id']);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add permit process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // check input
        $this->tnotification->set_rules('user_id', 'Personel', 'trim|required');
        $this->tnotification->set_rules('permit_date', 'Tanggal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('permit_time_start', 'Waktu Mulai', 'trim|max_length[8]');
        $this->tnotification->set_rules('permit_time_end', 'Waktu Selesai', 'trim|max_length[8]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Ijin', 'trim|required');
        $this->tnotification->set_rules('permit_reason', 'Perihal Ijin', 'trim|required|max_length[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $start_time = (empty($this->input->post('permit_time_start', true)) ? null : $this->input->post('permit_time_start', true));
            $end_time = (empty($this->input->post('permit_time_end', true)) ? null : $this->input->post('permit_time_end', true));
            // set parameter
            $izin_id = $this->m_permit->generate_izin_id($this->input->post('permit_date'));
            $struktur_cd = $this->m_permit->get_user_unit_kerja_by_id($this->input->post('user_id'));
            $month = $this->m_permit->convert_bulan_to_romawi(date("m",strtotime($this->input->post('permit_date'))));
            $nomor_izin = substr($izin_id, 14).'/IJIN/'.$month.'/'.date("Y",strtotime($this->input->post('permit_date')));                
            $params = array(
                'izin_id' => $izin_id,
                'user_id' => $this->input->post('user_id',TRUE), 
                'struktur_cd' => $struktur_cd, 
                'izin_nomor' => $nomor_izin,                
                'jenis_id' => $this->input->post('jenis_id',TRUE),
                'izin_uraian' => $this->input->post('permit_reason',TRUE),
                'izin_tanggal' => $this->input->post('permit_date',TRUE),
                'izin_waktu_mulai' => $start_time,
                'izin_waktu_selesai' => $end_time,
                'izin_status' => 'draft',
                'izin_send_by' => $this->com_user['user_id'],
                'izin_send_by_name' => $this->com_user['user_alias'],
                'izin_send_date' => date("Y-m-d H:i:s"),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            if ($this->m_permit->insert_permit($params)) {
                $flow_id = $this->now_flow_id;
                $params = array(
                        'process_id' => $this->m_permit->get_microtime(), 
                        'izin_id' => $izin_id, 
                        'flow_id' => $flow_id, 
                        'process_st' => 'waiting',
                        'action_st' => 'process', 
                        'mdb' => $this->com_user['user_id'], 
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                );
                $this->m_permit->insert_flow($params);
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect to print 
                redirect('home/task/permit/print_detail/' . $izin_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // // default redirect
        redirect("home/task/permit/add");
    }

    // edit permit
    public function edit($permit_id = '') {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "home/task/permit/edit.html");
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");                
        // get permit_st
        $permit_status = $this->m_permit->get_permit_st(array($permit_id));
        // jika hasil nya kosong
        if (empty($permit_status)) {
            $this->tnotification->sent_notification("error", "Data status izin tidak ada");                
            redirect('home/task/permit');
        }
        // validasi permit_st
        if ($permit_status != 'draft' && $permit_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Data yang sudah diajukan tidak dapat diubah");
            redirect('home/task/permit');
        }
        // get permit type
        $this->smarty->assign("rs_type", $this->m_permit->get_permit_type('permit'));
        // personel by department
        $struktur_cd = $this->m_permit->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $this->smarty->assign("rs_pic", $this->m_permit->get_user_by_department($struktur_cd));
        // get detail data
        $result = $this->m_permit->get_detail_permit($permit_id);
        $this->smarty->assign("result", $result);
        $this->smarty->assign("detail", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit permit process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // check input
        $this->tnotification->set_rules('user_id', 'Personel', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('izin_id', 'ID Ijin', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('permit_date', 'Tanggal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('permit_time_start', 'Waktu Mulai', 'trim|max_length[8]');
        $this->tnotification->set_rules('permit_time_end', 'Waktu Selesai', 'trim|max_length[8]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Ijin', 'trim|required');
        $this->tnotification->set_rules('permit_reason', 'Perihal Ijin', 'trim|required|max_length[100]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $start_time = (empty($this->input->post('permit_time_start', true)) ? null : $this->input->post('permit_time_start', true));
            $end_time = (empty($this->input->post('permit_time_end', true)) ? null : $this->input->post('permit_time_end', true));            
            // set parameter
            $params = array(
                'user_id' => $this->input->post('user_id',TRUE), 
                'jenis_id' => $this->input->post('jenis_id',TRUE),
                'izin_uraian' => $this->input->post('permit_reason',TRUE), 
                'izin_tanggal' => $this->input->post('permit_date',TRUE), 
                'izin_waktu_mulai' => $start_time,
                'izin_waktu_selesai' => $end_time,
                'mdb' => $this->com_user['user_id'], 
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")

            );
            $where = array(
                'izin_id' => $this->input->post('izin_id',TRUE)
            );
            if ($this->m_permit->update_permit($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diubah");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal diubah");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diubah");
        }
        // default redirect
        redirect("home/task/permit/edit/" . $this->input->post('izin_id'));
    }

    // delete permit
    public function delete($permit_id = '') {
        // set page rules
        $this->_set_page_rule("D");
        // if permit_id is empty
        if (empty($permit_id)) {
            redirect('home/task/permit');
        }
        
        // validasi permit_st        
        $permit_status = $this->m_permit->get_permit_st(array($permit_id));                
        if ($permit_status != 'draft' && $permit_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Data yang sudah diajukan tidak dapat dihapus");
            redirect('home/task/permit');
        }
        // set template content
        $this->smarty->assign("template_content", "home/task/permit/delete.html");
        // get detail data
        $result = $this->m_permit->get_detail_permit($permit_id);
        $this->smarty->assign("detail", $result);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // delete permit process
    public function delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek permit_id
        $this->tnotification->set_rules('izin_id', 'ID Izin', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'izin_id' => $this->input->post('izin_id', TRUE)
            );            
            // delete
            if ($this->m_permit->delete_permit($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus.");
                //redirect
                redirect("home/task/permit");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus.");
        }
        // default redirect
        redirect("home/task/permit/delete/" . $this->input->post('izin_id', 0));
    }

    // detail to print
    public function print_detail($permit_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/task/permit/print.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_permit->get_detail_permit($permit_id));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // print process
    public function print_process($permit_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/IZIN.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get detail permit to print
        $result = $this->m_permit->get_detail_print(array($permit_id));
        $department_leader = $this->m_permit->get_unit_kerja_leader(array($result['user_id']));
        // get permit type
        $result_izin_tipe = $this->m_permit->get_permit_type('permit');
        // print_r($result);
        if (empty($result)) {
            // default redirect
            redirect("home/task/permit/");
        }
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('F11', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('F13', strtoupper(($result['jabatan_struktural']!=''?$result['jabatan_struktural']:$result['jabatan_fungsional'])));
        $objWorksheet->setCellValue('F15', strtoupper($result['struktur_nama']));
        // permit type
        $tes = 19;
        $coba = 0;
        foreach ($result_izin_tipe as $key) {
            if ($key['jenis_id'] == $result['jenis_id']) {
                if ($coba < 3) {
                    $objWorksheet->setCellValue('B' . $tes, 'V');
                } else {
                    $objWorksheet->setCellValue('H' . $tes, 'V');
                }
            } else {
                if ($coba == 2) {
                    $tes = 17;
                }
                $tes = $tes + 2;
                $coba++;
            }
        }
        // get day
        $date = $this->datetimemanipulation->get_date_indonesia($result['izin_tanggal']);
        $objWorksheet->setCellValue('F25', strtoupper($date['hari']) . ', ' . strtoupper($date['tanggal']) . ' ' . strtoupper($date['bulan']) . ' ' . strtoupper($date['tahun']));
        $objWorksheet->setCellValue('F27', $result['izin_waktu_mulai']);
        $objWorksheet->setCellValue('N27', $result['izin_waktu_selesai']);
        $objWorksheet->setCellValue('F29', strtoupper(($result['izin_uraian'])));
        $objWorksheet->setCellValue('B40', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G40', $department_leader);
        // $objWorksheet->setCellValue('F46', strtoupper($result['department_lead']));
        // file_name
        $file_name = "SI_" . strtoupper(str_replace(' ', '_', $result['nama_lengkap'])) . '_' . str_replace('-', '_', $result['izin_tanggal']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

    public function pengajuan_process($permit_id = '') {
        // set page rules
        $this->_set_page_rule("U");
        // if permit_id is empty
        if (empty($permit_id)) {
            redirect('home/task/permit');
        }
        // validasi permit_st        
        $permit_status = $this->m_permit->get_permit_st(array($permit_id));                
        if ($permit_status != 'draft' && $permit_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Izin sudah diajukan");
            redirect('kepegawaian/ijin/pengajuan/print_detail/'.$pengajuan_id);
        }
        //izin_status, izin_id
        $params = array(
            'izin_status' => 'waiting');
        $where = array(
            'izin_id' => $permit_id);
        if ($this->m_permit->update_permit_st($params,$where)) {
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
                'izin_id' => $permit_id, 
                'flow_id' => $flow_id
            );
            $this->m_permit->update_process_by_izin_id($params,$where);            
            //create next flow
            $flow_id = $this->next_flow_id;
            $flow_revisi = null;
            $references_id = null;
            if($this->m_permit->check_if_have_references($permit_id)){
                $flow_revisi = $this->now_flow_id;
                $references_id = $this->m_permit->get_process_reference($permit_id);
            }         
            $params = array(
                    'process_id' => $this->m_permit->get_microtime(),
                    'izin_id' => $permit_id,
                    'flow_revisi_id' => $flow_revisi, 
                    'process_references_id' => $references_id,                    
                    'flow_id' => $flow_id, 
                    'process_st' => 'waiting',
                    'action_st' => 'process', 
                    'mdb' => $this->com_user['user_id'], 
                    'mdb_name' => $this->com_user['user_alias'], 
                    'mdd' => date('Y-m-d H:i:s')            
            );
            $this->m_permit->insert_flow($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Izin berhasil diajukan");
             // redirect
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");            
        }
        redirect('home/task/permit/');       
    }
}
