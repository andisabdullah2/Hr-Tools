<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pengajuan extends ApplicationBase {

    // my flow
    private $now_flow_id = '14001';
    private $next_flow_id = '14002';
    private $prev_flow_id = "";

    //contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model("kepegawaian/jaldin/m_pengajuan");
        //load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
        //get detail pegawai login
        $this->pegawai = $this->m_pengajuan->get_detail_pegawai(array($this->com_user['user_id']));
    }

    // list
    public function index() {
        // set page rule
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/pengajuan/list.html");
        // pagination
        $config['base_url'] = site_url("kepegawaian/jaldin/pengajuan/index/");
        $config['total_rows'] = $this->m_pengajuan->get_total_jaldin(array($this->pegawai['struktur_cd'], $this->now_flow_id));
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
        $params = array($this->pegawai['struktur_cd'], $this->now_flow_id, ($start - 1), $config['per_page']);
        $rs_id = $this->m_pengajuan->get_list_jaldin_by_limit($params);
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // add
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/pengajuan/add.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js"); 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // project
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_list_project_by_unit($this->pegawai['struktur_cd']));
        // penanggung jawab
        $this->smarty->assign("rs_personel", $this->m_pengajuan->get_list_user_by_unit($this->pegawai['struktur_cd']));
        //list item anggaran
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_item_anggaran());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process add
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('user_id', 'Karyawan', 'trim|required');
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim');
        $this->tnotification->set_rules('tanggal_berangkat', 'Tanggal Berangkat', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('waktu_berangkat', 'Waktu Berangkat', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('tanggal_pulang', 'Tanggal Pulang', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('waktu_pulang', 'Waktu Pulang', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('lokasi_tujuan', 'Tujuan', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('uraian_tugas', 'Uraian', 'trim|required|max_length[255]');
        //spt_id
        // spt id
        $spt_id = $this->m_pengajuan->get_spt_last_id(date("Y"));
        if (!$spt_id) {
            $this->tnotification->sent_notification("error", "ID SPT Tidak Tersedia");
            redirect("kepegawaian/jaldin/pengajuan/add");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            // jika user memasukkan tanggal terbalik (start > end) dilakukan pembalikan nilai
            if (strtotime($this->input->post('tanggal_berangkat')) > strtotime($this->input->post('tanggal_pulang'))) {
                $start_date = $this->input->post('tanggal_pulang');
                $end_date = $this->input->post('tanggal_berangkat');
            } else {
                $start_date = $this->input->post('tanggal_berangkat');
                $end_date = $this->input->post('tanggal_pulang');
            }
            //insert
            $params = array(
                'spt_id'            => $spt_id,
                'user_id'           => $this->input->post('user_id', true),
                'struktur_cd'       => $this->pegawai['struktur_cd'],
                'project_id'        => $this->input->post('project_id', true),
                'kode_item'         => $this->input->post('kode_item', true),
                'tanggal_berangkat' => $this->input->post('tanggal_berangkat', true),
                'tanggal_pulang'    => $this->input->post('tanggal_pulang', true),
                'waktu_berangkat'   => $this->input->post('waktu_berangkat', true),
                'waktu_pulang'      => $this->input->post('waktu_pulang', true),
                'lokasi_tujuan'     => $this->input->post('lokasi_tujuan', true),
                'uraian_tugas'      => $this->input->post('uraian_tugas', true),
                'spt_status'        => 'draft',
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s"),
            );
            // insert
            // $this->m_pengajuan->insert_spt($params)
            if ($this->m_pengajuan->insert_spt($params)) {
                //INSERT DATE
                $this->m_pengajuan->delete_tanggal(array('spt_id' => $spt_id));
                // insert date
                $step = '+1 day';
                $format = 'Y-m-d';
                $tgl_aktif = strtotime($start_date);
                $tgl_terakhir = strtotime($end_date);
                while ($tgl_aktif <= $tgl_terakhir) {
                    $tgl = date($format, $tgl_aktif);
                    // jika tidak maka inputkan
                    $params = array(
                        'spt_id'       => $spt_id,
                        'tanggal'      => $tgl,
                        'mdb'       => $this->com_user['user_id'],
                        'mdb_name'  => $this->com_user['user_alias'],
                        'mdd'       => date("Y-m-d H:i:s"),
                    );
                    $this->m_pengajuan->insert_tanggal($params);
                    // add day after
                    $tgl_aktif = strtotime($step, $tgl_aktif);
                }
                // insert next flow
                $process_id = $this->m_pengajuan->get_process_last_id(date('Y'));
                $flow_id = $this->now_flow_id;
                $params = array(
                    'process_id'    => $process_id,
                    'spt_id'        => $spt_id,
                    'flow_id'       => $flow_id,
                    'action_st'     => 'process',
                    'process_st'    => 'waiting',
                    'mdb'       => $this->com_user['user_id'],
                    'mdb_name'  => $this->com_user['user_alias'],
                    'mdd'       => date("Y-m-d H:i:s"),
                );
                $this->m_pengajuan->insert_flow($params);
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                redirect('kepegawaian/jaldin/pengajuan/detail/' . $spt_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/jaldin/pengajuan/add");
    }

    //edit
    public function edit($spt_id = '') {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/pengajuan/edit.html");
        // load style
        $this->smarty->load_style("default/plugins/select2/dist/css/select2.min.css");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js"); 
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist//js/select2.min.js");
        // get detail data
        $result = $this->m_pengajuan->get_detail_spt_by_id($spt_id);
        if(empty($result)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan");
        }
        $this->smarty->assign("result", $result);
        // project
        $this->smarty->assign("rs_project", $this->m_pengajuan->get_list_project_by_unit($this->pegawai['struktur_cd']));
        // penanggung jawab
        $this->smarty->assign("rs_personel", $this->m_pengajuan->get_list_user_by_unit($this->pegawai['struktur_cd']));
        //list item anggaran
        $this->smarty->assign("rs_item", $this->m_pengajuan->get_list_item_anggaran());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    //proses edit
    function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('spt_id', 'ID SPT', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required');
        $this->tnotification->set_rules('user_id', 'Karyawan', 'trim|required');
        $this->tnotification->set_rules('kode_item', 'Item Anggaran', 'trim');
        $this->tnotification->set_rules('tanggal_berangkat', 'Tanggal Berangkat', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('waktu_berangkat', 'Waktu Berangkat', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('tanggal_pulang', 'Tanggal Pulang', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('waktu_pulang', 'Waktu Pulang', 'trim|required|max_length[8]');
        $this->tnotification->set_rules('lokasi_tujuan', 'Tujuan', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('uraian_tugas', 'Uraian', 'trim|required|max_length[255]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $spt_id = $this->input->post('spt_id');
            // jika user memasukkan tanggal terbalik (start > end) dilakukan pembalikan nilai
            if (strtotime($this->input->post('tanggal_berangkat')) > strtotime($this->input->post('tanggal_pulang'))) {
                $start_date = $this->input->post('tanggal_pulang');
                $end_date = $this->input->post('tanggal_berangkat');
            } else {
                $start_date = $this->input->post('tanggal_berangkat');
                $end_date = $this->input->post('tanggal_pulang');
            }
            //set value
            $params = array(
                'user_id'           => $this->input->post('user_id', true),
                'struktur_cd'       => $this->pegawai['struktur_cd'],
                'project_id'        => $this->input->post('project_id', true),
                'kode_item'         => $this->input->post('kode_item', true),
                'tanggal_berangkat' => $this->input->post('tanggal_berangkat', true),
                'tanggal_pulang'    => $this->input->post('tanggal_pulang', true),
                'waktu_berangkat'   => $this->input->post('waktu_berangkat', true),
                'waktu_pulang'      => $this->input->post('waktu_pulang', true),
                'lokasi_tujuan'     => $this->input->post('lokasi_tujuan', true),
                'uraian_tugas'      => $this->input->post('uraian_tugas', true),
                'spt_status'        => 'draft',
                'mdb'       => $this->com_user['user_id'],
                'mdb_name'  => $this->com_user['user_alias'],
                'mdd'       => date("Y-m-d H:i:s"),
            );
            $where = array('spt_id' => $spt_id);
            // update
            if ($this->m_pengajuan->update_spt($params, $where)) {
                //INSERT DATE
                $this->m_pengajuan->delete_tanggal(array('spt_id' => $spt_id));
                // insert date
                $step = '+1 day';
                $format = 'Y-m-d';
                $tgl_aktif = strtotime($start_date);
                $tgl_terakhir = strtotime($end_date);
                while ($tgl_aktif <= $tgl_terakhir) {
                    $tgl = date($format, $tgl_aktif);
                    // jika tidak maka inputkan
                    $params = array(
                        'spt_id'       => $spt_id,
                        'tanggal'      => $tgl,
                        'mdb'       => $this->com_user['user_id'],
                        'mdb_name'  => $this->com_user['user_alias'],
                        'mdd'       => date("Y-m-d H:i:s"),
                    );
                    $this->m_pengajuan->insert_tanggal($params);
                    // add day after
                    $tgl_aktif = strtotime($step, $tgl_aktif);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan.");
        }
        // default redirect
        redirect("kepegawaian/jaldin/pengajuan/edit/" . $this->input->post('spt_id'));
    }

    // delete
    public function delete($spt_id = '') {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/pengajuan/delete.html");
        // get detail data
        $result = $this->m_pengajuan->get_detail_spt_by_id($spt_id);
        if(empty($result)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    // process hapus
    public function delete_process() {
        // cek input
        $this->tnotification->set_rules('spt_id', 'ID SPT', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array('spt_id' => $this->input->post('spt_id'));
            // delete
            if ($this->m_pengajuan->delete_spt($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus.");
                //redirect
                redirect("kepegawaian/jaldin/pengajuan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus.");
        }
        // default redirect
        redirect("tools/jaldin/pengajuan/delete/" . $this->input->post('spt_id', 0));
    }

    //detail data jaldin
    public function detail($spt_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/jaldin/pengajuan/detail.html");
        // get detail data
        $result = $this->m_pengajuan->get_detail_spt_by_id($spt_id);
        if(empty($result)){
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        // output
        parent::display();
    }

    public function submit_process($spt_id = ''){
        // set page rules
        $this->_set_page_rule("U");
        // get data & validation
        $result = $this->m_pengajuan->get_detail_spt_by_id($spt_id);
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan/");
        }
        //update
        $params = array(
            'spt_status'        => 'waiting',
            'spt_send_by'       => $this->com_user['user_id'],
            'spt_send_by_name'  => $this->com_user['user_alias'],
            'spt_send_date'     => date("Y-m-d H:i:s"),
        );
        $where = array('spt_id' => $spt_id);
        //update status spt
        // $this->m_pengajuan->update_spt($params, $where)
        if ($this->m_pengajuan->update_spt($params, $where)) {
            //update flow
            $process = $this->m_pengajuan->get_last_process(array($spt_id, $this->now_flow_id));
            if(!empty($process)){
                //update flow
                $params = array(
                    'process_st'        => 'approve',
                    'action_st'         => 'done',
                    'mdb_finish'        => $this->com_user['user_id'],
                    'mdb_finish_name'   => $this->com_user['user_alias'],
                    'mdd_finish'        => date('Y-m-d H:m:s')
                );
                $where = array(
                    'process_id'    => $process['process_id'],
                    'spt_id'        => $spt_id
                );
                // echo "<pre>"; print_r($process); echo "</pre>"; exit();
                $this->m_pengajuan->update_flow($params, $where);
                //create new flow
                $process_id = $this->m_pengajuan->get_process_last_id(date('Y'));
                $flow_id = $this->next_flow_id;
                $params = array(
                    'process_id'    => $process_id,
                    'spt_id'        => $spt_id,
                    'flow_id'       => $flow_id,
                    'action_st'     => 'process',
                    'process_st'    => 'waiting',
                    'process_references_id' => $process['process_id'],
                    'mdb'       => $this->com_user['user_id'],
                    'mdb_name'  => $this->com_user['user_alias'],
                    'mdd'       => date("Y-m-d H:i:s"),
                );
                $this->m_pengajuan->insert_flow($params);
            }
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disubmit.");
            //redirect
            redirect("kepegawaian/jaldin/pengajuan");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disubmit.");
        }
        //redirect
        redirect("kepegawaian/jaldin/pengajuan/detail/" . $spt_id);
    }

    // download excel
    public function download($spt_id = '') {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        // create excell
        $filepath = "resource/doc/template/JALDIN.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        // get data & validation
        $result = $this->m_pengajuan->get_detail_spt_by_id($spt_id);
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/jaldin/pengajuan/");
        }
        $gm = $this->m_pengajuan->get_detail_gm($result['struktur_cd']);
        // echo "<pre>"; print_r($result); echo "</pre>"; exit();
        /*
         * SET DATA EXCELL
         */
        $objWorksheet->setCellValue('D11', strtoupper($result['nama_lengkap']));
        $objWorksheet->setCellValue('D12', $result['pegawai_nip']);
        $objWorksheet->setCellValue('D13', strtoupper($result['struktur_nama']) . ' / ' . strtoupper($result['jabatan']));
        $objWorksheet->setCellValue('D14', strtoupper($this->datetimemanipulation->get_full_date($result['tanggal_berangkat'])));
        $objWorksheet->setCellValue('F14', strtoupper($this->datetimemanipulation->get_full_date($result['tanggal_pulang'])));
        $objWorksheet->setCellValue('D15', strtoupper($result['lokasi_tujuan']));
        $objWorksheet->setCellValue('D16', strtoupper($result['project_alias']) . ' - ' . $result['uraian_tugas']);
        $objWorksheet->setCellValue('B30', 'Yogyakarta, ' . $this->datetimemanipulation->get_full_date(substr($result['mdd'], 0, 10)));
        $objWorksheet->setCellValue('B35', strtoupper($result['nama_lengkap']));
        if(!empty($gm)){
            $objWorksheet->setCellValue('D35', strtoupper($gm['nama_lengkap']));
        }
        // file_name
        $file_name = "ST_" . str_replace(' ', '_', strtoupper($result['project_alias'])) . "_" . str_replace('-', '_', $result['tanggal_berangkat']);
        //--
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }

}