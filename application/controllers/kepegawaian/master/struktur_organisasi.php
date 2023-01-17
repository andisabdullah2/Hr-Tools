<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class Struktur_organisasi extends ApplicationBase
{

    //contructor
    public function __construct()
    {
        //parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/M_struktur_organisasi');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list data
    public function index()
    {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/struktur_organisasi/list.html");
        // get search parameter
        $search = $this->tsession->userdata('struktur_search');
        //search parameter
        $struktur_induk = empty($search['struktur_induk']) ? '%' : $search['struktur_induk'];
        if ($search) {
            $search['struktur_induk'] = $struktur_induk;
        } else {
            $search = array(
                'struktur_induk' => '%%',
                'struktur_induk' => '%%'
            );
        }
        $this->smarty->assign("search", $search);
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/struktur_organisasi/index/");
        $config['total_rows'] = $this->M_struktur_organisasi->get_total_struktur(array($struktur_induk));
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
        // get list data
        $params = array($struktur_induk, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->M_struktur_organisasi->get_all_struktur_by_limit($params));
        $this->smarty->assign("rs_all", $this->M_struktur_organisasi->get_all_struktur());
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // get struktur induk
    function get_struktur_induk()
    {
        $id = $this->input->post('id') - 1 ;
        $data = $this->M_struktur_organisasi->get_struktur_induk($id);
        echo json_encode($data);
    }

    // add form
    public function add()
    {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/struktur_organisasi/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //department list
        $this->smarty->assign("rs_struktur", $this->M_struktur_organisasi->get_all_struktur());


        // output
        parent::display();
    }

    // add process
    public function add_process()
    {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('struktur_cd', 'Induk Struktur', 'trim');
        $this->tnotification->set_rules('struktur_nama', 'Nama Struktur', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('struktur_singkatan', 'Singkatan Struktur', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('struktur_keterangan', 'Keterangan Struktur', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('struktur_level_label', 'Label Level Struktur', 'trim|required');
        $this->tnotification->set_rules('struktur_kode', 'Kode Struktur', 'trim|required|maxlength[3]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //get struktur cd
            $struktur_cd = $this->input->post('struktur_cd');
            //create level_struktur and struktur_cd
            $struktur_level_label = $this->input->post('struktur_level_label', true);
            if ($struktur_level_label == 'PERUSAHAAN') {
                //create level struktur
                $struktur_level = '0';
                //creating new struktur cd
                foreach ($this->M_struktur_organisasi->get_last_struktur_induk() as $data) {
                    //add last struktur induk by 1
                    $last_struktur_induk = substr($data['struktur_cd'], 0, 3) + 1;
                    if ($last_struktur_induk < 10) {
                        $struktur_cd = "00" . $last_struktur_induk;
                    } else if ($last_struktur_induk < 100) {
                        $struktur_cd = "0" . $last_struktur_induk;
                    } else {
                        $struktur_cd = $last_struktur_induk;
                    }
                }
                $new_struktur_cd = $struktur_cd . ".00" . ".00";
            } else if (($struktur_level_label == 'DEPARTEMEN') or ($struktur_level_label == 'KANTOR REPRESENTATIF') or ($struktur_level_label == 'KANTOR CABANG')) {
                //create level struktur
                $struktur_level = '1';
                //if no struktur cd
                $last_struktur_cd = "01";
                //creating new struktur_cd
                foreach ($this->M_struktur_organisasi->get_last_struktur_cd_by_induk($struktur_cd) as $data) {
                    // add last struktur cd by 1
                    $last_struktur_cd = substr($data['struktur_cd'], 4, 2) + 1;
                    if ($last_struktur_cd < 10) {
                        $last_struktur_cd = "0" . $last_struktur_cd;
                    } else {
                        $last_struktur_cd = $last_struktur_cd;
                    }
                }
                $new_struktur_cd = (substr($struktur_cd, 0, 4)) . $last_struktur_cd . ".00";
            } else if (($struktur_level_label == 'DIVISI') or ($struktur_level_label == 'SEKSI')) {
                //create level struktur
                $struktur_level = '2';
                //if no struktur cd
                $last_struktur_cd = ".01";
                //creating new struktur_cd
                foreach ($this->M_struktur_organisasi->get_last_struktur_cd_by_induk($struktur_cd) as $data) {
                    // add last struktur cd by 1
                    $last_struktur_cd = substr($data['struktur_cd'], 7, 2) + 1;

                    if ($last_struktur_cd < 10) {
                        $last_struktur_cd = ".0" . $last_struktur_cd;
                    } else {
                        $last_struktur_cd = $last_struktur_cd;
                    }
                }
                $new_struktur_cd = (substr($struktur_cd, 0, 6)) . $last_struktur_cd;
            }
            //parameter
            $params = array(
                'struktur_cd' => $new_struktur_cd,
                'struktur_induk' => $this->input->post('struktur_cd', true),
                'struktur_nama' => $this->input->post('struktur_nama', true),
                'struktur_singkatan' => $this->input->post('struktur_singkatan', true),
                'struktur_keterangan' => $this->input->post('struktur_keterangan', true),
                'struktur_level' => $struktur_level,
                'struktur_level_label' => $struktur_level_label,
                'struktur_kode' => $this->input->post('struktur_kode', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_struktur_organisasi->insert_struktur_organisasi($params)) {
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
        redirect("kepegawaian/master/struktur_organisasi/add/". $this->input->post('struktur_cd'));
    }



    // edit form
    public function edit($struktur_cd = "")
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/struktur_organisasi/edit.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // detail struktur
        
                                
        $this->smarty->assign("rs_detail_struktur", $this->M_struktur_organisasi->get_detail_struktur_by_cd($struktur_cd));
        $this->smarty->assign("rs_struktur_induk", $this->M_struktur_organisasi->get_struktur_induk_by_cd($struktur_cd));
        $this->smarty->assign("rs_all_struktur", $this->M_struktur_organisasi->get_all_struktur());
        $this->smarty->assign("rs_level_label", $this->M_struktur_organisasi->get_level_label());
        // output
        parent::display();

    }




    // edit process
    public function edit_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('struktur_cd', 'ID Struktur', 'trim|required');
        $this->tnotification->set_rules('struktur_induk', 'Induk Struktur', 'trim|maxlength[10]');
        $this->tnotification->set_rules('struktur_nama', 'Nama Struktur', 'trim|required|maxlength[100]');
        $this->tnotification->set_rules('struktur_singkatan', 'Singkatan Struktur', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('struktur_keterangan', 'Keterangan Struktur', 'trim|required|maxlength[255]');
        $this->tnotification->set_rules('struktur_level_label', 'Label Level Struktur', 'trim|required');
        $this->tnotification->set_rules('struktur_kode', 'Kode Struktur', 'trim|required|maxlength[3]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            //get struktur_cd
            $struktur_cd = $this->input->post('struktur_cd', true);
            $struktur_induk = $this->input->post('struktur_induk', true);
            $struktur_level_label = $this->input->post('struktur_level_label', true);
            //get detail struktur by struktur_cd
            foreach ($this->M_struktur_organisasi->get_edit_detail_struktur_by_cd($struktur_cd) as $data) {
                $struktur_level = $data['struktur_level'];
                if ($struktur_induk != $data['struktur_induk'] or $struktur_level_label != $data['struktur_level_label']) {
                    if ($struktur_level_label == 'PERUSAHAAN') {
                        //create level struktur
                        $lstruktur_level = '0';
                        //creating new struktur cd
                        foreach ($this->M_struktur_organisasi->get_last_struktur_induk() as $data) {
                            //add last struktur induk by 1
                            $last_struktur_induk = substr($data['struktur_cd'], 0, 3) + 1;
                            if ($last_struktur_induk < 10) {
                                $struktur_cd = "00" . $last_struktur_induk;
                            } else if ($last_struktur_induk < 100) {
                                $struktur_cd = "0" . $last_struktur_induk;
                            } else {
                                $struktur_cd = $last_struktur_induk;
                            }
                        }
                        $struktur_cd = $struktur_cd . ".00" . ".00";
                    } else if (($struktur_level_label == 'DEPARTEMEN') or ($struktur_level_label == 'KANTOR REPRESENTATIF') or ($struktur_level_label == 'KANTOR CABANG')) {
                        //create level struktur
                        $struktur_level = '1';
                        //if no struktur cd
                        $last_struktur_cd = "01";
                        //creating new struktur_cd
                        foreach ($this->M_struktur_organisasi->get_last_struktur_cd_by_induk($struktur_induk) as $data) {
                            // add last struktur cd by 1
                            $last_struktur_cd = substr($data['struktur_cd'], 4, 2) + 1;
                            if ($last_struktur_cd < 10) {
                                $last_struktur_cd = "0" . $last_struktur_cd;
                            } else {
                                $last_struktur_cd = $last_struktur_cd;
                            }
                        }
                        $struktur_cd = (substr($struktur_cd, 0, 4)) . $last_struktur_cd . ".00";
                    } else if (($struktur_level_label == 'DIVISI') or ($struktur_level_label == 'SEKSI')) {
                        //create level struktur
                        $struktur_level = '2';
                        //if no struktur cd
                        $last_struktur_cd = ".01";
                        //creating new struktur_cd
                        foreach ($this->M_struktur_organisasi->get_last_struktur_cd_by_induk($struktur_induk) as $data) {
                            // add last struktur cd by 1
                            $last_struktur_cd = substr($data['struktur_cd'], 7, 2) + 1;

                            if ($last_struktur_cd < 10) {
                                $last_struktur_cd = ".0" . $last_struktur_cd;
                            } else {
                                $last_struktur_cd = $last_struktur_cd;
                            }
                        }
                        $struktur_cd = (substr($struktur_cd, 0, 6)) . $last_struktur_cd;
                    }
                }
            }
            //parameter
            $params = array(
                'struktur_induk' => $struktur_induk,
                'struktur_nama' => $this->input->post('struktur_nama', true),
                'struktur_singkatan' => $this->input->post('struktur_singkatan', true),
                'struktur_keterangan' => $this->input->post('struktur_keterangan', true),
                'struktur_level' => $struktur_level,
                'struktur_level_label' => $struktur_level_label,
                'struktur_kode' => $this->input->post('struktur_kode', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['nama_lengkap'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'struktur_cd' => $this->input->post('struktur_cd', true)
            );
            // update
            if ($this->M_struktur_organisasi->update_struktur_organisasi($params, $where)) {
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
        //default redirect
        redirect("kepegawaian/master/struktur_organisasi/edit/" . $this->input->post('struktur_cd'));
    }


    //delete form
    public function delete($struktur_cd = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/struktur_organisasi/delete.html");
        // get data
        // $result = $this->m_settings->get_group_by_id($group_id);
        $result = $this->M_struktur_organisasi->get_detail_struktur_by_cd($struktur_cd);

        // check
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/struktur_organisasi/");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // cek input
        $this->tnotification->set_rules('struktur_cd', 'ID Struktur', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'struktur_cd' => $this->input->post('struktur_cd')
            );
            // delete
            if ($this->M_struktur_organisasi->delete_struktur($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/struktur_organisasi");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/struktur_organisasi/delete/" . $this->input->post('struktur_cd'));
    }

    //search process
    public function search_process()
    {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('struktur_search');
        } else {
            $params = array(
                "struktur_induk" => $this->input->post("struktur_induk")
            );
            $this->tsession->set_userdata("struktur_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/struktur_organisasi");
    }
}
