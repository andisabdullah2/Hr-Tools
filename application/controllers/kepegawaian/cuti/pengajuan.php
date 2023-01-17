<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pengajuan extends ApplicationBase {

    // my flow
    private $now_flow_id = '11001';
    private $next_flow_id = '11002';
    private $prev_flow_id = "";

    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/cuti/m_pengajuan");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // list
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/pengajuan/list.html");
        $tahun = date('Y');
        // pagination
        $config['base_url'] = site_url("kepegawaian/cuti/pengajuan/index/");
        $flow_id = $this->now_flow_id;
        $params = array($tahun);
        $config['total_rows'] = $this->m_pengajuan->get_total_pengajuan($params);
        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
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
        $params = array($tahun, $flow_id, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pengajuan->get_all_pengajuan_by_limit($params));
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
            $this->tsession->unset_userdata('pengajuan_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name",true),
                "tahun" => $this->input->post("tahun",true),
                "bulan" => $this->input->post("bulan",true)
            );
            $this->tsession->set_userdata("pengajuan_search", $params);
        }
        // redirect
        redirect("kepegawaian/cuti/pengajuan");
    }

    // add pengajuan
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/pengajuan/add.html");
        $this->smarty->assign("rs_type", $this->m_pengajuan->get_pengajuan_type());
        // personel by department
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $struktur_cd = empty($struktur_cd) ? '%' : $struktur_cd;
        $this->smarty->assign("rs_pic", $this->m_pengajuan->get_user_by_department($struktur_cd));
        $this->smarty->assign("rs_pegawai", $this->m_pengajuan->get_list_pegawai());
        $this->smarty->assign("struktur_cd", $struktur_cd);
        $this->smarty->assign("user_id", $this->com_user['user_id']);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add pengajuan process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // check input
        $this->tnotification->set_rules('user_id', 'Personel', 'trim|required');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'trim|required');
        $this->tnotification->set_rules('cuti_tanggal_mulai', 'Tanggal Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('cuti_tanggal_selesai', 'Tanggal Selesai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Cuti', 'trim|required');
        $this->tnotification->set_rules('pic_id', 'PIC', 'trim|required');
        $this->tnotification->set_rules('pengajuan_reason', 'Perihal Cuti', 'trim|max_length[255]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //check input tgl jika kebalik
            $cuti_mulai = $this->input->post('cuti_tanggal_mulai', true);
            $cuti_selesai = $this->input->post('cuti_tanggal_selesai', true);            
            if (strtotime($cuti_mulai) > strtotime($cuti_selesai)) {
                $start_date = $this->input->post('cuti_tanggal_selesai', true);
                $end_date = $this->input->post('cuti_tanggal_mulai', true);
            } else {
                $start_date = $this->input->post('cuti_tanggal_mulai', true);
                $end_date = $this->input->post('cuti_tanggal_selesai', true);
            } 
            //check hari libur & weekend
            $params = array($start_date, $end_date);
            $data_hari_libur = $this->m_pengajuan->get_all_hari_libur_between_range_cuti($params);
            $range_cuti = $this->m_pengajuan->breakdown_tanggal_cuti_perhari($start_date, $end_date);
            $range_cuti_checked = array();
            //ambil hari kerja dari range
            foreach ($range_cuti as $key => $tanggal_cuti) {
                if(!$this->m_pengajuan->isWeekend($tanggal_cuti->format('Y-m-d'))){
                    array_push($range_cuti_checked,$tanggal_cuti->format('Y-m-d'));
                } 
            }
            //hapus hari libur dari range jika ada
            $tanggal_libur_tmp = array();
            if(!empty($data_hari_libur)){
                foreach($data_hari_libur as $key => $tanggal_libur) {
                        array_push($tanggal_libur_tmp,$tanggal_libur['libur_tanggal']);
                }
                $range_cuti_checked = array_diff( $range_cuti_checked, $tanggal_libur_tmp);
            }
            //hitung jatah cuti tersisa
            $params = array(
                $this->input->post('user_id',true),
                $this->input->post('jenis_id',true),
                date('Y', strtotime($start_date))
            );
            $total_jatah_cuti = $this->m_pengajuan->get_total_jatah_cuti($params);
            $total_cuti_terpakai = $this->m_pengajuan->get_total_cuti_terpakai($params);
            $sisa_cuti = $total_jatah_cuti - $total_cuti_terpakai;
            if (count($range_cuti_checked) > $sisa_cuti){
                //error jatah tidak cukup
                $this->tnotification->sent_notification("error", "Kuota cuti tidak cukup, kuota tersisa : ".$sisa_cuti);
                redirect("kepegawaian/cuti/pengajuan/add");
            }
            // set parameter
            $pic = explode(":", $this->input->post('pic_id',true));
            $cuti_id = $this->m_pengajuan->generate_cuti_id($start_date);
            $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->input->post('user_id',true));
            $cuti_nomor = $this->m_pengajuan->generate_nomor_cuti($cuti_id,$start_date);
            $params = array(
                'cuti_id' => $cuti_id,
                'user_id' => $this->input->post('user_id',TRUE), 
                'jenis_id' => $this->input->post('jenis_id',TRUE),                
                'struktur_cd' => $struktur_cd, 
                'cuti_nomor' => $cuti_nomor,                
                'cuti_uraian' => $this->input->post('pengajuan_reason',TRUE),
                'cuti_pic' => $pic[0],
                'cuti_pic_name' => $pic[1],
                'cuti_tanggal_mulai' => $start_date,
                'cuti_tanggal_selesai' => $end_date,
                'cuti_status' => 'draft',
                'cuti_send_by' => $this->com_user['user_id'],
                'cuti_send_by_name' => $this->com_user['user_alias'],
                'cuti_send_date' => date("Y-m-d H:i:s"),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            if ($this->m_pengajuan->insert_pengajuan($params)) {
                //insert pegawai_cuti_tanggal
                foreach($range_cuti_checked as $tanggal){
                    $params = array(
                        'cuti_id' => $cuti_id,
                        'cuti_tanggal' => $tanggal
                    );
                    $this->m_pengajuan->insert_tanggal_cuti($params);
                }
                //insert_flow
                $flow_id = $this->now_flow_id;
                $params = array(
                        'process_id' => $this->m_pengajuan->get_microtime(), 
                        'cuti_id' => $cuti_id, 
                        'flow_id' => $flow_id, 
                        'process_st' => 'waiting',
                        'action_st' => 'process', 
                        'mdb' => $this->com_user['user_id'], 
                        'mdb_name' => $this->com_user['user_alias'], 
                        'mdd' => date('Y-m-d H:i:s')
                );
                $this->m_pengajuan->insert_flow($params);
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect to print 
                redirect('kepegawaian/cuti/pengajuan/print_detail/' . $cuti_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/cuti/pengajuan/add");
    }

    // edit pengajuan
    public function edit($pengajuan_id = '') {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/pengajuan/edit.html");
        // get pengajuan_st
        $pengajuan_status = $this->m_pengajuan->get_pengajuan_st(array($pengajuan_id));
        // jika hasil nya kosong
        if (empty($pengajuan_status)) {
            $this->tnotification->sent_notification("error", "Data status cuti tidak ada");                
            redirect('kepegawaian/cuti/pengajuan');
        }
        // validasi pengajuan_st
        if ($pengajuan_status != 'draft' && $pengajuan_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Data yang sudah diajukan tidak dapat diubah");
            redirect('kepegawaian/cuti/pengajuan');
        }
        // get pengajuan type
        $this->smarty->assign("rs_type", $this->m_pengajuan->get_pengajuan_type());
        // personel by department
        $struktur_cd = $this->m_pengajuan->get_user_unit_kerja_by_id($this->com_user['user_id']);
        $this->smarty->assign("rs_pic", $this->m_pengajuan->get_user_by_department($struktur_cd));
        $this->smarty->assign("rs_pegawai", $this->m_pengajuan->get_list_pegawai());        
        // get detail data
        $result = $this->m_pengajuan->get_detail_pengajuan($pengajuan_id);
        $this->smarty->assign("detail", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit pengajuan process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // check input
        $this->tnotification->set_rules('user_id', 'Personel', 'trim|required');
        $this->tnotification->set_rules('cuti_id', 'ID Cuti', 'trim|required');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'trim|required');
        $this->tnotification->set_rules('cuti_nomor', 'Nomor Cuti', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('cuti_tanggal_mulai', 'Tanggal Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('cuti_tanggal_mulai_old', 'Tanggal Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('cuti_tanggal_selesai', 'Tanggal Selesai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('cuti_tanggal_selesai_old', 'Tanggal Selesai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jenis_id', 'Jenis Cuti', 'trim|required');
        $this->tnotification->set_rules('jenis_id_old', 'Jenis Cuti Lama', 'trim|required');
        $this->tnotification->set_rules('pic_id', 'PIC', 'trim|required');
        $this->tnotification->set_rules('pengajuan_reason', 'Perihal Cuti', 'trim|max_length[255]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $pic = explode(":", $this->input->post('pic_id',true));
            // check jika pegawai cuti dan pic tidak satu unit
            if($this->m_pengajuan->get_user_unit_kerja_by_id($pic[0]) != $this->input->post('struktur_cd',true)){
                $this->tnotification->sent_notification("error", "PIC bukan dari unit yang sama");
                redirect("kepegawaian/cuti/pengajuan/edit/" . $this->input->post('cuti_id', true));                
            }
            //check input tgl jika kebalik
            $cuti_mulai = $this->input->post('cuti_tanggal_mulai', true);
            $cuti_selesai = $this->input->post('cuti_tanggal_selesai', true);            
            if (strtotime($cuti_mulai) > strtotime($cuti_selesai)) {
                $start_date = $this->input->post('cuti_tanggal_selesai', true);
                $end_date = $this->input->post('cuti_tanggal_mulai', true);
            } else {
                $start_date = $this->input->post('cuti_tanggal_mulai', true);
                $end_date = $this->input->post('cuti_tanggal_selesai', true);
            }
            //check hari libur & weekend
            $params = array($start_date, $end_date);
            $data_hari_libur = $this->m_pengajuan->get_all_hari_libur_between_range_cuti($params);
            $range_cuti = $this->m_pengajuan->breakdown_tanggal_cuti_perhari($start_date, $end_date);
            $range_cuti_checked = array();
            //ambil hari kerja dari range
            foreach ($range_cuti as $key => $tanggal_cuti) {
                if(!$this->m_pengajuan->isWeekend($tanggal_cuti->format('Y-m-d'))){
                    array_push($range_cuti_checked,$tanggal_cuti->format('Y-m-d'));
                } 
            }
            //hapus hari libur dari range jika ada
            $tanggal_libur_tmp = array();
            if(!empty($data_hari_libur)){
                foreach($data_hari_libur as $key => $tanggal_libur) {
                        array_push($tanggal_libur_tmp,$tanggal_libur['libur_tanggal']);
                }
                $range_cuti_checked = array_diff( $range_cuti_checked, $tanggal_libur_tmp);
            }                     
            //check jika ganti tanggal
            if($this->input->post('cuti_tanggal_mulai',TRUE) != $this->input->post('cuti_tanggal_mulai_old',TRUE) || $this->input->post('cuti_tanggal_selesai',TRUE) != $this->input->post('cuti_tanggal_selesai_old',TRUE)){
                $selisih_tanggal_tambah = array();
                $selisih_tanggal_hapus = array();                
                $range_cuti_old = $this->m_pengajuan->get_data_cuti_tanggal($this->input->post('cuti_id', true));
                $range_cuti_old = array_column($range_cuti_old, 'cuti_tanggal');
                //check selisih tanggal baru
                foreach($range_cuti_checked as $key => $tanggal_baru){
                    if(!in_array($tanggal_baru, $range_cuti_old))
                        array_push($selisih_tanggal_tambah, $tanggal_baru);
                }
                //check selisih tanggal yg tidak ada
                foreach($range_cuti_old as $key => $tanggal_lama){
                    if(!in_array($tanggal_lama, $range_cuti_checked))
                        array_push($selisih_tanggal_hapus, $tanggal_lama);
                }
            }
            //check quota
            $params = array(
                $this->input->post('user_id',true),
                $this->input->post('jenis_id',true),
                date('Y', strtotime($start_date))
            );
            $total_jatah_cuti = $this->m_pengajuan->get_total_jatah_cuti($params);
            $total_cuti_terpakai = $this->m_pengajuan->get_total_cuti_terpakai($params);
            $sisa_cuti = $total_jatah_cuti - $total_cuti_terpakai;            
            if (count($range_cuti_checked) > $sisa_cuti){
                //error jatah tidak cukup
                $this->tnotification->sent_notification("error", "Kuota cuti tidak cukup, kuota tersisa : ".$sisa_cuti);
                redirect("kepegawaian/cuti/pengajuan/edit/" . $this->input->post('cuti_id', true));
            }
            $params = array(
                'user_id' => $this->input->post('user_id',TRUE),                
                'jenis_id' => $this->input->post('jenis_id',TRUE),                
                'struktur_cd' => $this->input->post('struktur_cd',TRUE), 
                'cuti_nomor' => $this->input->post('cuti_nomor',TRUE), 
                'cuti_uraian' => $this->input->post('pengajuan_reason',TRUE),
                'cuti_pic' => $pic[0],
                'cuti_pic_name' => $pic[1],
                'cuti_tanggal_mulai' => $start_date,
                'cuti_tanggal_selesai' => $end_date,
                'cuti_status' => 'draft',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'cuti_id' => $this->input->post('cuti_id',TRUE)
            );
            if ($this->m_pengajuan->update_pengajuan($params, $where)) {
                if(!empty($selisih_tanggal_tambah)){
                    foreach($selisih_tanggal_tambah as $tanggal_baru){
                        $params = array(
                            'cuti_id' => $this->input->post('cuti_id',TRUE),
                            'cuti_tanggal' => $tanggal_baru
                        );
                        $this->m_pengajuan->insert_tanggal_cuti($params);
                    }
                }
                if(!empty($selisih_tanggal_hapus)){
                    foreach($selisih_tanggal_hapus as $tanggal_hapus){
                        $params = array(
                            'cuti_id' => $this->input->post('cuti_id',TRUE),
                            'cuti_tanggal' => $tanggal_hapus
                        );
                        $this->m_pengajuan->delete_tanggal_cuti($params);
                    }
                }
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
        redirect("kepegawaian/cuti/pengajuan/edit/" . $this->input->post('cuti_id'));
    }

    // delete pengajuan
    public function delete($pengajuan_id = '') {
        // set page rules
        $this->_set_page_rule("D");
        // if pengajuan_id is empty
        if (empty($pengajuan_id)) {
            redirect('kepegawaian/cuti/pengajuan');
        }
        
        // validasi pengajuan_st        
        $pengajuan_status = $this->m_pengajuan->get_pengajuan_st(array($pengajuan_id));                
        if ($pengajuan_status != 'draft' && $pengajuan_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Data yang sudah diajukan tidak dapat dihapus");
            redirect('kepegawaian/cuti/pengajuan');
        }
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/pengajuan/delete.html");
        // get detail data
        $result = $this->m_pengajuan->get_detail_pengajuan($pengajuan_id);
        $this->smarty->assign("detail", $result);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // delete pengajuan process
    public function delete_process() {
        // set page rules
        $this->_set_page_rule("D");
        // cek pengajuan_id
        $this->tnotification->set_rules('cuti_id', 'ID Cuti', 'trim|required|max_length[20]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'cuti_id' => $this->input->post('cuti_id', TRUE)
            );            
            // insert
            if ($this->m_pengajuan->delete_pengajuan($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus.");
                //redirect
                redirect("kepegawaian/cuti/pengajuan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus.");
        }
        // default redirect
        redirect("kepegawaian/cuti/pengajuan/delete/" . $this->input->post('cuti_id', true));
    }

    // detail to print
    public function print_detail($pengajuan_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/pengajuan/print.html");
        // get detail data
        $this->smarty->assign("detail", $this->m_pengajuan->get_detail_pengajuan($pengajuan_id));
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // print process
    public function print_process($pengajuan_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/CUTI.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // ijin
        $result = $this->m_pengajuan->get_cetak_pengajuan_by_id($pengajuan_id);
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data tidak ditemukan");
            redirect("kepegawaian/cuti/pengajuan");
        }
        $dpt_lead = $this->m_pengajuan->get_dpt_lead_by_cuti_id($pengajuan_id);
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('G6', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G7', strtoupper($result['jabatan_nama']));
        $objWorksheet->setCellValue('G8', strtoupper($result['struktur_nama']));
        $objWorksheet->setCellValue('G9', "'" . strtoupper($result['nomor_telepon']));
        // // tanggal
        $arr_hari = array('1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu');
        $day_req = $arr_hari[date('N', strtotime($result['cuti_send_date']))];
        $day_req = $day_req . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_send_date']);
        $day_start = $arr_hari[date('N', strtotime($result['cuti_tanggal_mulai']))];
        $day_start = $day_start . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_tanggal_mulai']);
        $day_end = $arr_hari[date('N', strtotime($result['cuti_tanggal_selesai']))];
        $day_end = $day_end . ', ' . $this->datetimemanipulation->get_full_date($result['cuti_tanggal_selesai']);
        $objWorksheet->setCellValue('T6', strtoupper($day_req));
        $objWorksheet->setCellValue('T7', strtoupper($day_start));
        $objWorksheet->setCellValue('T8', strtoupper($day_end));
        $objWorksheet->setCellValue('T9', $result['masa_cuti']);
        $objWorksheet->setCellValue('H23', $result['masa_cuti']);
        $objWorksheet->setCellValue('G36', $dpt_lead);
        $objWorksheet->setCellValue('K31', $dpt_lead);
        $objWorksheet->setCellValue('B31', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('G18', strtoupper($result['cuti_pic_name']));
        $objWorksheet->setCellValue('G19', $result['pic_position']);
        $objWorksheet->setCellValue('L23', $this->datetimemanipulation->get_short_date($result['cuti_tanggal_mulai']));
        $objWorksheet->setCellValue('Q23', $this->datetimemanipulation->get_short_date($result['cuti_tanggal_selesai']));
        // kuota cuti
        $kuota_cuti = $this->m_pengajuan->get_total_jatah_cuti(array($result['user_id'], $result['jenis_id'],date('Y', strtotime($result['cuti_tanggal_mulai']))));
        // cuti yg terpakai
        $terpakai = $this->m_pengajuan->get_total_cuti_terpakai(array($result['user_id'], $result['jenis_id'],$result['cuti_tanggal_mulai']));
        // sisa cuti yg ada
        $sisa_cuti = $kuota_cuti - $terpakai;
        $objWorksheet->setCellValue('G25', $sisa_cuti);
        // pengajuan type
        switch ($result['jenis_cuti']) {
            case 'TAHUNAN':
                $objWorksheet->setCellValue('B13', 'V');
                break;
            case 'MELAHIRKAN':
                $objWorksheet->setCellValue('B15', 'V');
                break;
            default :
                $objWorksheet->setCellValue('H13', 'V');
                break;
        }
        // file_name
        $file_name = "SC_" . str_replace(' ', '_', strtoupper($result['nama_lengkap'])) . "_" . str_replace('-', '_', $result['cuti_tanggal_mulai']);
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }
    
    //pengajuan process
    public function pengajuan_process($pengajuan_id = '') {
        // set page rules
        $this->_set_page_rule("U");
        // if pengajuan_id is empty
        if (empty($pengajuan_id)) {
            $this->tnotification->sent_notification("error", "Data tidak ditemukan");
            redirect('kepegawaian/cuti/pengajuan');
        }
        // validasi pengajuan_st        
        $pengajuan_status = $this->m_pengajuan->get_pengajuan_st(array($pengajuan_id));                
        if ($pengajuan_status != 'draft' && $pengajuan_status != 'rejected') {
            $this->tnotification->sent_notification("error", "Cuti sudah diajukan");
            redirect('kepegawaian/cuti/pengajuan/print_detail/' . $pengajuan_id);       
        }
        //hitung jatah cuti tersisa
        //get necessary data for checking
        $data_cuti = $this->m_pengajuan->get_jenis_and_tahun_cuti_by_id(array($pengajuan_id));
        $params = array(
            $data_cuti['user_id'],
            $data_cuti['jenis_id'],
            $data_cuti['tahun'],
        );
        $total_jatah_cuti = $this->m_pengajuan->get_total_jatah_cuti($params);
        $total_cuti_terpakai = $this->m_pengajuan->get_total_cuti_terpakai($params);
        $total_cuti_pengajuan = $this->m_pengajuan->get_total_cuti_pengajuan(array($pengajuan_id));
        $sisa_cuti = $total_jatah_cuti - $total_cuti_terpakai;
        //hitung
        if ($total_cuti_pengajuan > $sisa_cuti){
            //error jatah tidak cukup
            $this->tnotification->sent_notification("error", "Kuota cuti tidak cukup, kuota tersisa : ".$sisa_cuti.". Yang diajukan : ".$total_cuti_pengajuan);
            redirect("kepegawaian/cuti/pengajuan/print_detail/" . $pengajuan_id);
        }
        //update status pegawai_cuti
        $params = array(
            'cuti_status' => 'waiting');
        $where = array(
            'cuti_id' => $pengajuan_id);
        if ($this->m_pengajuan->update_pengajuan_st($params,$where)) {
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
                'cuti_id' => $pengajuan_id, 
                'flow_id' => $flow_id
            );
            $this->m_pengajuan->update_process_by_cuti_id($params,$where);            
            //create next flow
            $flow_id = $this->next_flow_id;
            $flow_revisi = null;
            $references_id = null;
            if($this->m_pengajuan->check_if_have_references($pengajuan_id)){
                $flow_revisi = $this->now_flow_id;
                $references_id = $this->m_pengajuan->get_process_reference($pengajuan_id);
            }         
            $params = array(
                    'process_id' => $this->m_pengajuan->get_microtime(),
                    'cuti_id' => $pengajuan_id,
                    'flow_revisi_id' => $flow_revisi, 
                    'process_references_id' => $references_id,                    
                    'flow_id' => $flow_id, 
                    'process_st' => 'waiting',
                    'action_st' => 'process', 
                    'mdb' => $this->com_user['user_id'], 
                    'mdb_name' => $this->com_user['user_alias'], 
                    'mdd' => date('Y-m-d H:i:s')            
            );
            $this->m_pengajuan->insert_flow($params);
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Cuti berhasil diajukan");
             // redirect
             redirect('kepegawaian/cuti/pengajuan');
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diajukan");            
        }
        redirect('kepegawaian/cuti/pengajuan/print_detail/' . $pengajuan_id);       
    }
}
