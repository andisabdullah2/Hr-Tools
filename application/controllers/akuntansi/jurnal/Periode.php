<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class Periode extends ApplicationBase
{

    // constructor
    public function __construct()
    {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('akuntansi/jurnal/M_periode');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    // list
    public function index()
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/jurnal/periode/list.html");

        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("akuntansi/jurnal/periode/index/");
        $config['total_rows'] = $this->M_periode->get_total_tahun();
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

        // get data
        $params = array(($start - 1), $config['per_page']);
        // get data tahun
<<<<<<< HEAD
        $this->smarty->assign("rs_id", $this->M_periode->get_all_tahun_data($params));
=======
        $this->smarty->assign("data", $this->M_periode->get_all_tahun_data());
>>>>>>> 8f8bde41a5bd67febd5a1be3b8e4906159eacf47
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add periode
    public function add()
    {
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/jurnal/periode/add.html");
        // tahun terakhir
        $this->smarty->assign("year", $this->M_periode->get_tahun_latest());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process periode
    public function add_process()
    {
        // set page rule
        $this->_set_page_rule("C");
        // validasi input
        $this->tnotification->set_rules('tahun_index', 'Tahun', 'trim|required');
        $this->tnotification->set_rules('tahun_label', 'Label', 'trim|required');
        $this->tnotification->set_rules('periode_awal', 'Periode Awal', 'required');
        $this->tnotification->set_rules('periode_akhir', 'Periode Akhir', 'required');
        $this->tnotification->set_rules('tahun_default', 'Status', 'trim|required');


        // process
        if ($this->tnotification->run() !== false) {
            // ambil input
            $tahun_index = $this->input->post('tahun_index');
            $tahun_default_input = $this->input->post('tahun_default');

            // cek duplicate entry tahun_index
            if ($this->M_periode->check_if_tahun_exists($tahun_index)) {
                $this->tnotification->sent_notification("error", "Tahun yang anda masukkan sudah ada");
                // redirect
                redirect("akuntansi/jurnal/periode/add");
            } else {
                $params = [
                    'tahun_index' => $this->input->post('tahun_index'),
                    'tahun_label' => strtoupper($this->input->post('tahun_label')),
                    'periode_awal' => $this->input->post('periode_awal'),
                    'periode_akhir' => $this->input->post('periode_akhir'),
                    'tahun_default' => $this->input->post('tahun_default'),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date('Y-m-d H:i:s')
                ];

                // jika inputan tahun_default pilih aktif
                if ($tahun_default_input == 'yes') {
                    $params_update = [
                        'tahun_default' => 'no'
                    ];

                    // ambil semua data tahun aktif
                    $where = [
                        'tahun_index' => $this->M_periode->get_tahun_aktif()
                    ];

                    // hitung semua data tahun aktif
                    $jumlah = count($this->M_periode->get_tahun_aktif());

                    // update semua data tahun aktif sebelumnya menjadi tidak aktif
                    for ($i = 0; $i < $jumlah; $i++) {
                        $this->M_periode->update($params_update, $where[$i]["tahun_index"]);
                    }
                }

                // insert
                if ($this->M_periode->insert($params)) {
                    // notification success
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    // redirect
                    redirect("akuntansi/jurnal/periode");
                } else {
                    // notification error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    // redirect
                    redirect("akuntansi/jurnal/periode/add");
                }
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // redirect
            redirect("akuntansi/jurnal/periode/add");
        }
    }

    // edit tahun
    public function edit($tahun_index = "")
    {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/jurnal/periode/edit.html");
        // get data tahun by id
        $detail = $this->M_periode->get_detail_tahun($tahun_index);
        // cek data kosong atau tidak
        if (empty($detail)) {
            // notification error
            $this->tnotification->sent_notification("error", "Data tidak ditemukan!");
            redirect("akuntansi/jurnal/periode");
        }
        // parsing data
        $this->smarty->assign("result", $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function edit_process()
    {
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('tahun_index', 'Tahun', 'trim|required');
        $this->tnotification->set_rules('tahun_label', 'Label', 'trim|required');
        $this->tnotification->set_rules('periode_awal', 'Periode Awal', 'required');
        $this->tnotification->set_rules('periode_akhir', 'Periode Akhir', 'required');
        $this->tnotification->set_rules('tahun_default', 'Status', 'trim|required');

        // proses 
        if ($this->tnotification->run() !== false) {
            // ambil inpit
            $tahun_default_input = $this->input->post('tahun_default');

            // jika inputan tahun_default pilih aktif
            if ($tahun_default_input == 'yes') {
                $params_update = [
                    'tahun_default' => 'no'
                ];

                // ambil semua data tahun aktif
                $where_update = [
                    'tahun_index' => $this->M_periode->get_tahun_aktif()
                ];

                // hitung data tahun aktif
                $jumlah = count($this->M_periode->get_tahun_aktif());


                // update semua data tahun aktif sebelumnya menjadi tidak aktif
                for ($i = 0; $i < $jumlah; $i++) {
                    $this->M_periode->update($params_update, $where_update[$i]["tahun_index"]);
                }
            }


            $params = [
                'tahun_label' => strtoupper($this->input->post('tahun_label')),
                'periode_awal' => $this->input->post('periode_awal'),
                'periode_akhir' => $this->input->post('periode_akhir'),
                'tahun_default' => $this->input->post('tahun_default'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            ];

            $where = [
                'tahun_index' => $this->input->post('tahun_index')
            ];

            // update
            if ($this->M_periode->update($params, $where)) {
                // notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diedit");
                // redirect
                redirect("akuntansi/jurnal/periode");
            } else {
                // notification error
                $this->tnotification->sent_notification("error", "Data gagal diedit");
                redirect("akuntansi/jurnal/periode/edit/" . $this->input->post('tahun_index_old'));
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal diedit");
            redirect("akuntansi/jurnal/periode/edit/" . $this->input->post('tahun_index_old'));
        }
    }

    public function delete($tahun_index = "")
    {
        // set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/jurnal/periode/delete.html");
        // get data tahun by id
        $detail = $this->M_periode->get_detail_tahun($tahun_index);
        // cek data kosong atau tidak
        if (empty($detail)) {
            // notification error
            $this->tnotification->sent_notification("error", "Data tidak ditemukan!");
            redirect("akuntansi/jurnal/periode");
        }
        // parsing data
        $this->smarty->assign("result", $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function delete_process()
    {
        // set page rules
        $this->_set_page_rule("D");
        // validasi input
        $this->tnotification->set_rules('tahun_index', 'Tahun', 'trim|required');

        // process
        if ($this->tnotification->run() !== false) {
            $params = [
                'tahun_index' => $this->input->post('tahun_index')
            ];

            // delete
            if ($this->M_periode->delete($params)) {
                // notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("akuntansi/jurnal/periode");
            } else {
                // notification error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
                // default redirect
                redirect("akuntansi/jurnal/periode/" . $this->input->post('tahun_index'));
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            // default redirect
            redirect("akuntansi/jurnal/periode/" . $this->input->post('tahun_index'));
        }
    }

    public function detail($tahun_index = "")
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/jurnal/periode/detail/list.html");
        // get data tahun
        $this->smarty->assign("rs_id", $this->M_periode->get_all_jurnal_periode($tahun_index));
        $this->smarty->assign("tahun_index", $tahun_index);

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function add_periode_process()
    {
        // set page rule
        $this->_set_page_rule("C");
        // validasi input
        $this->tnotification->set_rules('tahun_index', 'Tahun', 'trim|required|is_unique[jurnal_tahun.tahun_index]');
        $this->tnotification->set_rules('periode_jenis', 'Jenis', 'trim|required');
        $this->tnotification->set_rules('periode_label', 'Label', 'trim|required');
        $this->tnotification->set_rules('periode_awal', 'Periode Awal', 'required');
        $this->tnotification->set_rules('periode_akhir', 'Periode Akhir', 'required');


        // process
        if ($this->tnotification->run() !== false) {
            // input
            $tahun_index = $this->input->post('tahun_index');
            $periode_id = $this->M_periode->get_new_periode_id();
            $params = [
                'periode_id' => $periode_id,
                'tahun_index' => $this->input->post('tahun_index'),
                'periode_jenis' => $this->input->post('periode_jenis'),
                'periode_label' => strtoupper($this->input->post('periode_label')),
                'periode_awal' => $this->input->post('periode_awal'),
                'periode_akhir' => $this->input->post('periode_akhir'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            ];

            // insert
            if ($this->M_periode->insert_periode($params)) {
                // notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect
                redirect("akuntansi/jurnal/periode/detail/" . $tahun_index);
            } else {
                // notification error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
                // redirect
                redirect("akuntansi/jurnal/periode/detail/" . $tahun_index);
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // redirect
            redirect("akuntansi/jurnal/periode/detail");
        }
    }

    public function edit_periode_process()
    {
        // set page rule
        $this->_set_page_rule("U");
        // validasi
        $this->tnotification->set_rules('tahun_index', 'Tahun', 'trim|required|is_unique[jurnal_tahun.tahun_index]');
        $this->tnotification->set_rules('periode_jenis', 'Jenis', 'trim|required');
        $this->tnotification->set_rules('periode_label', 'Label', 'trim|required');
        $this->tnotification->set_rules('periode_awal', 'Periode Awal', 'required');
        $this->tnotification->set_rules('periode_akhir', 'Periode Akhir', 'required');

        // proses 
        if ($this->tnotification->run() !== false) {
            $tahun_index = $this->input->post('tahun_index');

            $params = [
                'tahun_index' => $this->input->post('tahun_index'),
                'periode_jenis' => $this->input->post('periode_jenis'),
                'periode_label' => strtoupper($this->input->post('periode_label')),
                'periode_awal' => $this->input->post('periode_awal'),
                'periode_akhir' => $this->input->post('periode_akhir'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            ];

            $where = [
                'periode_id' => $this->input->post('periode_id'),
            ];

            // insert
            if ($this->M_periode->update_periode($params, $where)) {
                // notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // redirect
                redirect("akuntansi/jurnal/periode/detail/" . $tahun_index);
            } else {
                // notification error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
                // redirect
                redirect("akuntansi/jurnal/periode/detail/" . $tahun_index);
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // redirect
            redirect("akuntansi/jurnal/periode/detail");
        }
    }

    public function delete_periode_process($periode_id = "")
    {
        // set page rules
        $this->_set_page_rule("D");

        // process & validasi
        if (!empty($periode_id)) {
            $params = [
                'periode_id' => $periode_id,
            ];
            // delete
            if ($this->M_periode->delete_periode($params)) {
                // notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("akuntansi/jurnal/periode/detail");
            } else {
                // notification error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
                // default redirect
                redirect("akuntansi/jurnal/periode/detail" . $this->input->post('tahun_index'));
            }
        } else {
            // notification error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            // default redirect
            redirect("akuntansi/jurnal/periode/detail" . $this->input->post('tahun_index'));
        }
    }
}
