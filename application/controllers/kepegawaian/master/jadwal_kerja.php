<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jadwal_kerja extends ApplicationBase {

    //contructor
    public function __construct() {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_jadwal_kerja');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jadwal_kerja/list.html");
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/jadwal_kerja/index/");
        $config['total_rows'] = $this->m_jadwal_kerja->get_total_jadwal_kerja();
        $config['uri_segment'] = 5;
        $config['per_page'] = 20;
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
        $this->smarty->assign("total_data", $config['total_rows']);
        $this->smarty->assign("total_data_presented", 0);
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list data
        $params = array(($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_jadwal_kerja->get_jadwal_kerja_limit($params)); 
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jadwal_kerja/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('jadwal_tahun', 'Tahun', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('jadwal_nama', 'Nama Jadwal', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('jadwal_status', 'Status Jadwal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jadwal_mulai', 'Jadwal Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jadwal_selesai', 'Jadwal Selesai', 'trim|required|max_length[10]');
        //create id jadwal
        $jadwal_id = $this->m_jadwal_kerja->get_jadwal_id();
        //process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'jadwal_id'         => $jadwal_id,
                'jadwal_tahun'      => $this->input->post('jadwal_tahun', true),
                'jadwal_nama'       => $this->input->post('jadwal_nama', true),
                'jadwal_status'     => $this->input->post('jadwal_status', true),
                'jadwal_mulai'      => $this->input->post('jadwal_mulai', true),
                'jadwal_selesai'    => $this->input->post('jadwal_selesai', true),
                'mdb'               => $this->com_user['user_id'],
                'mdd'               => date('Y-m-d h:i:s'),
            );
            // insert
            if ($this->m_jadwal_kerja->insert($params)) {
                // error count
                $err = 0;
                // insert hari kerja
                for ($i = 1; $i <= 7; $i++) {
                    $params = array(
                        'jadwal_id'           => $jadwal_id,
                        'hari_kerja_id'       => $i,
                        'jam_total'           => '00:00:00',
                        'jadwal_masuk_awal'   => '00:00:00',
                        'jadwal_masuk_akhir'  => '00:00:00',
                        'jadwal_pulang_awal'  => '00:00:00',
                        'jadwal_pulang_akhir' => '00:00:00',
                        'mdb'                 => $this->com_user['user_id'],
                        'mdd'                 => date('Y-m-d H:i:s'),
                    );
                    $this->m_jadwal_kerja->insert_hari_kerja($params);
                }
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
        // default redirect
        redirect("kepegawaian/master/jadwal_kerja/");
    }

    // edit page
    public function edit($jadwal_kerja = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jadwal_kerja/edit.html");
        // detail departemen
        $this->smarty->assign("result", $this->m_jadwal_kerja->get_detail_jadwal_kerja_by_id($jadwal_kerja));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process(){
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('jadwal_tahun', 'Tahun', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('jadwal_nama', 'Nama Jadwal', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('jadwal_status', 'Status Jadwal', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jadwal_mulai', 'Jadwal Mulai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('jadwal_selesai', 'Jadwal Selesai', 'trim|required|max_length[10]');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'jadwal_tahun'      => $this->input->post('jadwal_tahun', true),
                'jadwal_nama'       => $this->input->post('jadwal_nama', true),
                'jadwal_status'     => $this->input->post('jadwal_status', true),
                'jadwal_mulai'      => $this->input->post('jadwal_mulai', true),
                'jadwal_selesai'    => $this->input->post('jadwal_selesai', true),
                'mdb'               => $this->com_user['user_id'],
                'mdd'               => date('Y-m-d h:i:s'),
            );
            $where = array(
                'jadwal_id' => $this->input->post('jadwal_id', true),
            );
            // update
            if ($this->m_jadwal_kerja->update($params, $where)) {
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
        // default redirect
        redirect("kepegawaian/master/jadwal_kerja/edit/" . $this->input->post('jadwal_id', true));
    }    
    
    //delete page
    public function delete($jadwal_kerja_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jadwal_kerja/delete.html");
        // get data
        $result = $this->m_jadwal_kerja->get_detail_jadwal_kerja_by_id($jadwal_kerja_id);
        $this->smarty->assign("result", $result);
        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/jadwal_kerja/");
        }
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process() {
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('jadwal_id', 'Jabatan ID', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'jadwal_id' => $this->input->post('jadwal_id', TRUE)
            );
            // delete
            if ($this->m_jadwal_kerja->delete_jadwal_kerja($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/jadwal_kerja");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("kepegawaian/master/jadwal_kerja/delete/" . $this->input->post('jadwal_id'));
    }      

    // edit form
    public function edit_hari_kerja($jadwal_kerja = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/jadwal_kerja/edit_hari_kerja.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/jquery-maskedinput/jquery.maskedinput.js");
        // check        
        $result = $this->m_jadwal_kerja->get_detail_jadwal_by_id(array($jadwal_kerja));
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/jadwal_kerja");
        }
        $this->smarty->assign("result", $this->m_jadwal_kerja->get_detail_jadwal_kerja_by_id($jadwal_kerja));        
        // jadwal id    
        $this->smarty->assign("jadwal_id", $jadwal_kerja);
        // get hari kerja
        $rs_hari_kerja = $this->m_jadwal_kerja->get_list_hari_kerja_by_id(array($jadwal_kerja));
        $this->smarty->assign("rs_hari_kerja", $rs_hari_kerja);
        // list hari
        $hari = array(
            '1' => 'Senin',
            '2' => 'Selasa',
            '3' => 'Rabu',
            '4' => 'Kamis',
            '5' => 'Jumat',
            '6' => 'Sabtu',
            '7' => 'Minggu',
        );
        $this->smarty->assign("rs_hari", $hari);
        // notification 
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_hari_kerja_process() {
        //set rule
        $this->_set_page_rule("U");
        // list hari
        $rs_hari = array(
            '1' => 'Senin',
            '2' => 'Selasa',
            '3' => 'Rabu',
            '4' => 'Kamis',
            '5' => 'Jumat',
            '6' => 'Sabtu',
            '7' => 'Minggu',
        );
        //cek input
        $this->tnotification->set_rules('jadwal_id', 'ID', 'trim|required');
        foreach ($rs_hari as $key => $hari) {
            $this->tnotification->set_rules("jadwal[{$key}][jadwal_masuk_awal]", "Jadwal Masuk Awal Hari {$hari}", 'trim|required');
            $this->tnotification->set_rules("jadwal[{$key}][jadwal_masuk_akhir]", "Jadwal Masuk Akhir Hari {$hari}", 'trim|required');
            $this->tnotification->set_rules("jadwal[{$key}][jadwal_pulang_awal]", "Jadwal Pulang Awal Hari {$hari}", 'trim|required');
            $this->tnotification->set_rules("jadwal[{$key}][jadwal_pulang_akhir]", "Jadwal Pulang Akhir Hari {$hari}", 'trim|required');
            $this->tnotification->set_rules("jadwal[{$key}][jam_total]", 'Total Jam Hari ' . $hari, 'trim|required');
        }
        //process
        if ($this->tnotification->run() !== FALSE) {
            // delete data lama            
            $where = array('jadwal_id' => $this->input->post('jadwal_id', TRUE));
            if ($this->m_jadwal_kerja->delete_hari_kerja($where)) {
                // insert data baru
                foreach ($this->input->post('jadwal', TRUE) as $key => $value) {
                    $params = array(
                        'jadwal_id' => $this->input->post('jadwal_id', TRUE),
                        'hari_kerja_id' => $key,
                        'jam_total' => $value['jam_total'],
                        'jadwal_masuk_awal' => $value['jadwal_masuk_awal'],
                        'jadwal_masuk_akhir' => $value['jadwal_masuk_akhir'],
                        'jadwal_pulang_awal' => $value['jadwal_pulang_awal'],
                        'jadwal_pulang_akhir' => $value['jadwal_pulang_akhir'],
                        'mdb' => $this->com_user['user_id'],
                        'mdd' => date('Y-m-d H:i:s')
                    );
                    $this->m_jadwal_kerja->insert_hari_kerja($params);
                }
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
        redirect("kepegawaian/master/jadwal_kerja/edit_hari_kerja/" . $this->input->post('jadwal_id'));
    }
}
