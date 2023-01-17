<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once(APPPATH . "controllers/base/OperatorBase.php");

class Kontrak extends ApplicationBase
{

    //constructor
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('project/tools/M_kontrak');
        //load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
        $this->load->library('tupload');
    }

    // list kontrak
    public function index()
    {
        //set page rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/kontrak/list.html");
        // list tahun
        $this->smarty->assign("rs_tahun", $this->M_kontrak->get_list_tahun_kontrak());
        // search
        $search = $this->tsession->userdata("project_kontrak_search");
        // search parameters
        $project = empty($search['project']) ? '%' : '%' . $search['project'] . '%';
        // $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        // print_r("var is = " . $tahun);
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/tools/kontrak/index/");
        $params = array($project, $project, $tahun);
        $config['total_rows'] = $this->M_kontrak->get_total_project_kontrak($params);
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
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list
        $params = array($project, $project, $tahun, ($start - 1), $config['per_page']);
        $rs_id = $this->M_kontrak->get_all_kontrak_data($params);
        // get list data
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // list termin
    public function termin($kontrak_id)
    {
        //set page rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/kontrak/termin/list.html");
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric.min.js");
        // get list
        $rs_id = $this->M_kontrak->get_all_termin_data_by_kontrak(array($kontrak_id));
        $nomor_termin = $this->M_kontrak->get_new_nomor_termin();

        // termin total
        $amountKontrak = $this->M_kontrak->get_detail_kontrak_by_id($kontrak_id);
        $amountTermin = $this->M_kontrak->get_total_termin_by_kontrak_id($kontrak_id);
        $limit = $amountTermin['total'] < $amountKontrak['jumlah_termin'] ? false : true;

        // assign data
        $this->smarty->assign("limit", $limit);
        $this->smarty->assign("rs_id", $rs_id);
        $this->smarty->assign("kontrak_id", $kontrak_id);
        $this->smarty->assign("nomor_termin", $nomor_termin);
        $this->smarty->assign("no", 1);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process()
    {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("project_kontrak_search");
        } else {
            $params = array(
                "kontrak" => $this->input->post("kontrak"),
                "project" => $this->input->post("project"),
                "tahun" => $this->input->post("tahun"),
            );
            $this->tsession->set_userdata("project_kontrak_search", $params);
        }
        if ($this->input->post("loc") == 'kontrak') {
            redirect("project/tools/kontrak");
        } else {
            redirect("project/tools/kontrak/termin");
        }
    }

    // add
    public function add()
    {
        //set rules
        $this->_set_page_rule("C");
        //set template content
        $this->smarty->assign("template_content", "project/tools/kontrak/add.html");
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric.min.js");
        //get data
        $this->smarty->assign("rs_projects", $this->M_kontrak->get_all_data_projects());
        $this->smarty->assign("rs_company", $this->M_kontrak->get_list_perusahaan());
        $this->smarty->assign("rs_tahun", $this->M_kontrak->get_list_tahun_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // add kontrak process
    public function add_process()
    {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('struktur_cd', 'Perusahaan', 'trim|required');
        $this->tnotification->set_rules('tahun', 'tahun', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('judul_kontrak', 'Judul Kontrak', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('nomor_kontrak', 'Nomor Kontrak', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('tanggal_kontrak', 'Tanggal Kontrak', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('nilai_kontrak', 'Nilai Kontrak', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('jumlah_termin', 'Jumlah Termin', 'trim|required|max_length[3]');


        // process
        if ($this->tnotification->run() !== FALSE) {
            if ($this->input->post('nilai_pajak') <= $this->input->post('nilai_kontrak')) {
                $project_id = $this->input->post('project_id', true);
                $kontrak_id = $this->M_kontrak->generate_kontrak_id($project_id);
                $params = array($this->input->post('tanggal_kontrak', true), $this->input->post('tanggal_selesai', true));
                $lama_penyelesaian = $this->M_kontrak->hitung_lama_penyelesaian($params);
                //params
                $params = array(
                    'kontrak_id' => $kontrak_id,
                    'project_id' => $project_id,
                    'struktur_cd' => $this->input->post('struktur_cd', true),
                    'judul_kontrak' => $this->input->post('judul_kontrak', true),
                    'nomor_kontrak' => $this->input->post('nomor_kontrak', true),
                    'jumlah_termin' => $this->input->post('jumlah_termin', true),
                    'tanggal_kontrak' => $this->input->post('tanggal_kontrak', true),
                    'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                    'nilai_kontrak' => $this->input->post('nilai_kontrak', true),
                    'nilai_pajak' => $this->input->post('nilai_pajak', true),
                    'lama_penyelesaian' => $lama_penyelesaian,
                    'create_by' => $this->com_user['user_id'],
                    'create_by_name' => $this->com_user['user_name'],
                    'create_date' => date("Y-m-d H:i:s"),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_name'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                // insert
                if ($this->M_kontrak->insert($params)) {
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    // default redirect
                    redirect("project/tools/kontrak/termin/" . $kontrak_id);
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    redirect("project/tools/kontrak/add");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Nilai Pajak tidak boleh lebih dari Nilai Kontrak");
                redirect("project/tools/kontrak/add");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            redirect("project/tools/kontrak/add");
        }

        // default redirect
        redirect("project/tools/kontrak/");
    }

    // add termin process
    public function add_termin_process()
    {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('kontrak_id', 'ID Kontrak', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('termin_nomor', 'Nomor Termin', 'trim|required');
        $this->tnotification->set_rules('termin_uraian', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('termin_tanggal', 'Tanggal Termin', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('termin_bulan', 'Bulan Termin', 'trim|required|max_length[2]');
        $this->tnotification->set_rules('termin_tahun', 'Tahun Termin', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('termin_nilai', 'Nilai Termin', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('termin_status', 'Status Termin', 'trim|required');
        // process

        // $amountKontrak = $this->M_kontrak->get_detail_kontrak_by_id($this->input->post('kontrak_id'));
        // $amountTermin = $this->M_kontrak->get_total_termin_by_kontrak_id($this->input->post('kontrak_id'));
        // if ($amountKontrak['jumlah'] > $amountTermin) {
        if ($this->tnotification->run() !== FALSE) {
            $kontrak_id = $this->input->post('kontrak_id', true);
            $termin_id = $this->M_kontrak->generate_termin_id($kontrak_id);
            $bulan = $this->input->post('termin_bulan', true);
            if (strlen($bulan) == 1)
                $bulan = '0' . $bulan;
            //params
            $params = array(
                'kontrak_id' => $kontrak_id,
                'termin_id' => $termin_id,
                'termin_nomor' => $this->input->post('termin_nomor', true),
                'termin_status' => $this->input->post('termin_status', true),
                'termin_uraian' => $this->input->post('termin_uraian', true),
                'termin_tanggal' => $this->input->post('termin_tanggal', true),
                'termin_bulan' => $bulan,
                'termin_tahun' => $this->input->post('termin_tahun', true),
                'termin_nilai' => $this->input->post('termin_nilai', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_name'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_kontrak->insert_termin($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id'));
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
                redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id'));
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id'));
        }
        // } else {
        //     $this->tnotification->sent_notification("error", "Data gagal disimpan");
        //     redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id'));
        // }
        // default redirect
        redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id'));
    }

    // edit
    public function edit($kontrak_id)
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "project/tools/kontrak/edit.html");
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric.min.js");
        //get detail
        $result = $this->M_kontrak->get_detail_kontrak_by_id(array($kontrak_id));
        if (empty($result)) {
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/kontrak/");
        }
        $this->smarty->assign('result', $result);
        //get data
        $this->smarty->assign("rs_projects", $this->M_kontrak->get_all_data_projects());
        $this->smarty->assign("rs_company", $this->M_kontrak->get_list_perusahaan());
        $this->smarty->assign("rs_tahun", $this->M_kontrak->get_list_tahun_project());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process
    public function edit_process()
    {
        // cek input
        $this->tnotification->set_rules('kontrak_id', 'ID Kontrak', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('struktur_cd', 'Perusahaan', 'trim|required');
        $this->tnotification->set_rules('tahun', 'tahun', 'trim|required');
        $this->tnotification->set_rules('project_id', 'Project', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('judul_kontrak', 'Judul Kontrak', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('nomor_kontrak', 'Nomor Kontrak', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('tanggal_kontrak', 'Tanggal Kontrak', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('tanggal_kontrak_old', 'Tanggal Kontrak Lama', 'trim|max_length[10]');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('tanggal_selesai_old', 'Tanggal Selesai Lama', 'trim|max_length[10]');
        $this->tnotification->set_rules('nilai_kontrak', 'Nilai Kontrak', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('nilai_pajak', 'Nilai Pajak', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('jumlah_termin', 'Jumlah Termin', 'trim|required|max_length[3]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            if ($this->input->post('nilai_pajak') <= $this->input->post('nilai_kontrak')) {
                $kontrak_id = $this->input->post('kontrak_id', true);
                $project_id = $this->input->post('project_id', true);
                //params
                $params = array(
                    'project_id' => $project_id,
                    'struktur_cd' => $this->input->post('struktur_cd', true),
                    'judul_kontrak' => $this->input->post('judul_kontrak', true),
                    'nomor_kontrak' => $this->input->post('nomor_kontrak', true),
                    'tanggal_kontrak' => $this->input->post('tanggal_kontrak', true),
                    'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                    'nilai_kontrak' => $this->input->post('nilai_kontrak', true),
                    'nilai_pajak' => $this->input->post('nilai_pajak', true),
                    'jumlah_termin' => $this->input->post('jumlah_termin', true),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_name'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                if ($this->input->post('tanggal_kontrak', true) != $this->input->post('tanggal_kontrak_old', true) || $this->input->post('tanggal_selesai', true) != $this->input->post('tanggal_selesai_old', true)) {
                    $params_date = array($this->input->post('tanggal_kontrak', true), $this->input->post('tanggal_selesai', true));
                    $lama_penyelesaian = $this->M_kontrak->hitung_lama_penyelesaian($params_date);
                    $params['lama_penyelesaian'] = $lama_penyelesaian;
                }
                $where = array('kontrak_id' => $kontrak_id);
                // update
                if ($this->M_kontrak->update($params, $where)) {
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    redirect("project/tools/kontrak/edit/" . $this->input->post('kontrak_id'));
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Nilai Pajak tidak boleh lebih dari Nilai Kontrak");
                redirect("project/tools/kontrak/edit/" . $this->input->post('kontrak_id'));
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            redirect("project/tools/kontrak/edit/" . $this->input->post('kontrak_id'));
        }
        // default redirect
        redirect("project/tools/kontrak/");
    }

    // edit termin process
    public function edit_termin_process()
    {
        // cek input
        $this->tnotification->set_rules('kontrak_id', 'ID Kontrak', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('termin_id', 'Termin ID', 'trim|required');
        $this->tnotification->set_rules('termin_uraian', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('termin_tanggal', 'Tanggal Termin', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('termin_bulan', 'Bulan Termin', 'trim|required|max_length[2]');
        $this->tnotification->set_rules('termin_tahun', 'Tahun Termin', 'trim|required|max_length[4]');
        $this->tnotification->set_rules('termin_nilai', 'Nilai Termin', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('termin_status', 'Status Termin', 'trim|required');
        // process
        $termin_id = $this->input->post('termin_id', true);
        if ($this->tnotification->run() !== FALSE) {
            //params
            $params = array(
                'termin_uraian' => $this->input->post('termin_uraian', true),
                'termin_tanggal' => $this->input->post('termin_tanggal', true),
                'termin_bulan' => $this->input->post('termin_bulan', true),
                'termin_tahun' => $this->input->post('termin_tahun', true),
                'termin_nilai' => $this->input->post('termin_nilai', true),
                'termin_status' => $this->input->post('termin_status', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_name'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array('termin_id' => $termin_id);
            // update
            if ($this->M_kontrak->update_termin($params, $where)) {
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
        redirect("project/tools/kontrak/termin/" . $this->input->post('kontrak_id', true));
    }

    // delete
    public function delete($kontrak_id)
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "project/tools/kontrak/delete.html");
        //get detail
        $result = $this->M_kontrak->get_detail_kontrak_by_id(array($kontrak_id));
        if (empty($result)) {
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/kontrak/");
        }
        $this->smarty->assign('detail', $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // delete process
    public function delete_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        //cek input
        $this->tnotification->set_rules('kontrak_id', 'ID Kontrak', 'trim|required');
        //doc id
        $kontrak_id = $this->input->post('kontrak_id', true);
        //get detail
        $result = $this->M_kontrak->get_detail_kontrak_by_id(array($kontrak_id));
        if (empty($result)) {
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/kontrak/");
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            $where = array('kontrak_id' => $kontrak_id);
            // delete
            if ($this->M_kontrak->delete($where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // redirect
                redirect("project/tools/kontrak/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/tools/kontrak/delete/" . $this->input->post('kontrak_id', true));
    }

    // delete termin process 
    public function delete_termin_process($termin_id)
    {
        // set page rules
        $this->_set_page_rule("D");
        //get detail
        $result = $this->M_kontrak->get_detail_termin_by_id(array($termin_id));
        if (empty($result)) {
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/kontrak/detail/" . $this->input->post('kontrak_id', true));
        }
        // process
        $where = array('termin_id' => $termin_id);
        // delete
        if ($this->M_kontrak->delete_termin($where)) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            // redirect
            redirect("project/tools/kontrak/termin/" . $result['kontrak_id']);
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/tools/kontrak/termin/" . $result['kontrak_id']);
    }

    // get alamat by suplier
    public function get_project_by_tahun()
    {
        $id = trim($this->input->post('id', TRUE));
        // get data pegawai
        $project = $this->M_kontrak->get_project_by_tahun($id);
        if (empty($project)) {
        } else {
            header('Content-Type: application/json');
            echo json_encode($project);
        }
    }
}
