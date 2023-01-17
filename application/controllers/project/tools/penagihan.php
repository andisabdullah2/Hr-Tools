<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class Penagihan extends ApplicationBase {

    //constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('project/tools/M_penagihan');
        //load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
        $this->load->library('tupload');
    }

    // list penagihan
    public function index() {
        //set page rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/penagihan/list.html");
        // list bulan & tahun
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_bulan_indonesia());
        $this->smarty->assign("rs_tahun", $this->M_penagihan->get_list_tahun_penagihan());
        $this->smarty->assign("invoice", $this->M_penagihan->get_all_invoices());
        // search
        $search = $this->tsession->userdata("project_penagihan_search");
        // search parameters
        $nama_kontrak = empty($search['nama_kontrak']) ? '%' : '%' . $search['nama_kontrak'] . '%';
        $bulan = empty($search['bulan']) ? date('m') : $search['bulan'];
        $tahun = empty($search['tahun']) ? date('Y') : $search['tahun'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/tools/penagihan/index/");
        $params = array($nama_kontrak,$bulan,$tahun);
        $config['total_rows'] = $this->M_penagihan->get_total_penagihan($params);
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
        $params = array($bulan,$nama_kontrak,$bulan,$tahun, ($start - 1), $config['per_page']);
        $rs_id = $this->M_penagihan->get_all_penagihan_data($params);
        // get list data
        $this->smarty->assign("rs_id", $rs_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("project_penagihan_search");
        } else {
            $params = array(
                "nama_kontrak" => $this->input->post("penagihan"),
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun"),
            );
            $this->tsession->set_userdata("project_penagihan_search", $params);
        }
        redirect("project/tools/penagihan");
    }

    // detail
    public function detail($termin_id = "") {
        // set rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/tools/penagihan/detail.html");
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");                          
        // load css
        $this->smarty->load_style('default/css/custom.css');
        //get detail
        $result = $this->M_penagihan->get_detail_termin_by_id(array($termin_id));
        $invoices = $this->M_penagihan->new_id();
        $detail = $this->M_penagihan->get_invoices_by_termin_id($termin_id);
        if(empty($result)){
            $this->M_penagihan->get_detail_termin_by_id_if_null($termin_id);
            //default error
            // $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            // redirect("project/tools/penagihan/");
        }
        $this->smarty->assign('detail', $result);
        $this->smarty->assign('invoices', $invoices);
        $this->smarty->assign('data', $detail);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();

    }
        
    // add
    public function add()
    {
        //set rules
        $this->_set_page_rule("C");
        //set template content
        $this->smarty->assign("template_content", "project/tools/penagihan/add.html");
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric.min.js");
        //get data
        $invoices = $this->M_penagihan->new_id();
        $kontrak = $this->M_penagihan->get_all_kontrak_data();
        $rs_projects = $this->M_penagihan->get_all_data_projects();
        $rs_termin = $this->M_penagihan->get_all_termin_data();
        $this->smarty->assign("rs_tahun", $this->M_penagihan->get_list_tahun_project());

        $this->smarty->assign('invoices', $invoices);
        $this->smarty->assign('kontrak', $kontrak);
        $this->smarty->assign("rs_projects", $rs_projects);
        $this->smarty->assign("rs_termin", $rs_termin);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // add invoice process
    public function add_process() {
        // for testing data caught
        // print_r($_POST); exit;
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('invoices_id', 'ID Invoices', 'trim|max_length[50]');
        $this->tnotification->set_rules('termin_id', 'ID Termin', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('invoices_nomor', 'Nomor Invoices', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('invoices_tanggal', 'Tanggal Invoice', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('invoices_jatuh_tempo', 'Tanggal Jatuh Tempo', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('invoices_uraian', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('invoices_jumlah', 'Jumlah', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('invoices_pajak_ppn', 'Pajak PPn', 'trim|max_length[25]');
        $this->tnotification->set_rules('invoices_pajak_pph', 'Pajak PPh', 'trim|max_length[25]');
        $this->tnotification->set_rules('invoices_total', 'Total', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('invoices_status', 'Status', 'trim|required|max_length[25]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $termin_id = $this->input->post('termin_id', true);
            // $termin_data = $this->M_penagihan->get_detail_termin_by_id($termin_id);
            $invoices_id = $this->M_penagihan->new_id(); 
            $tanggal = $this->input->post('invoices_tanggal', true);
            // check if total value isnt match with termin value
            // if($termin_data['termin_nilai'] != strtok($this->input->post('invoices_total', true), '.')){
            //     $termin_nilai = number_format($termin_data['termin_nilai'], 0, ',', '.');
            //     $this->tnotification->sent_notification("error", "Total nilai tidak sama dengan nilai termin (Rp. $termin_nilai).");
            //     redirect("project/tools/penagihan");                
            // }
            //params
            $params = array(
                'invoices_id' => $invoices_id,
                'termin_id' => $termin_id,
                'invoices_nomor' => $this->input->post('invoices_nomor', true),
                'invoices_tanggal' => $tanggal,
                'invoices_jatuh_tempo' => $this->input->post('invoices_jatuh_tempo', true),
                'invoices_uraian' => $this->input->post('invoices_uraian', true),
                'invoices_jumlah' => $this->input->post('invoices_jumlah', true),
                'invoices_pajak_ppn' => $this->input->post('invoices_pajak_ppn', true),
                'invoices_pajak_pph' => $this->input->post('invoices_pajak_pph', true),
                'invoices_status' => $this->input->post('invoices_status', true),
                'invoices_total' => strtok($this->input->post('invoices_total', true), '.'),
                'invoices_bulan' => date('m', strtotime($tanggal)),
                'invoices_tahun' => date('Y', strtotime($tanggal)),
                'create_by' => $this->com_user['user_id'],
                'create_by_name' => $this->com_user['user_alias'],
                'create_date' => date("Y-m-d H:i:s"),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->M_penagihan->insert_invoice($params)) {
                // update status termin if paid / cancelled
                if($this->input->post('invoices_status', true) == 'paid'){
                    $params = array(
                        'termin_status' => 'lunas',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array(
                        'termin_id' => $termin_id
                    );
                    $this->M_penagihan->update_termin($params, $where);
                } elseif($this->input->post('invoices_status', true) == 'cancel'){
                    $params = array(
                        'termin_status' => 'cancel',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array(
                        'termin_id' => $termin_id
                    );
                    $this->M_penagihan->update_termin($params, $where);                
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                print_r($termin_nilai);
                // default redirect
                redirect("project/tools/penagihan/add");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/tools/penagihan/add");
    }
    
    // edit invoice
    public function edit($invoices_id) {
        //set rules
        $this->_set_page_rule("U");
        //set template content
        $this->smarty->assign("template_content", "project/tools/penagihan/edit.html");
        //assign data
        $data = $this->M_penagihan->get_invoices_by_id($invoices_id);
        if (empty($data)){
            $this->tnotification->sent_notification("error", "Data yang dipilih tidak ada");
            redirect("project/tools/penagihan/");            
        }
        $kontrak = $this->M_penagihan->get_all_kontrak_data();
        $rs_projects = $this->M_penagihan->get_all_data_projects();
        $rs_termin = $this->M_penagihan->get_all_termin_data();

        $this->smarty->assign('kontrak', $kontrak);
        $this->smarty->assign("rs_projects", $rs_projects);
        $this->smarty->assign("rs_termin", $rs_termin);
        $this->smarty->assign("detail", $data);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }
    
    // edit process
    public function edit_process() {
        // for testing data caught
        // print_r($_POST); exit;
        // cek input
        $this->tnotification->set_rules('termin_id', 'ID Termin', 'trim|max_length[20]');
        $this->tnotification->set_rules('invoices_id', 'ID Invoices', 'trim|max_length[50]');
        $this->tnotification->set_rules('invoices_uraian', 'Keterangan', 'trim|max_length[100]');
        $this->tnotification->set_rules('invoices_tanggal', 'Tanggal Invoice', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('invoices_jatuh_tempo', 'Tanggal Jatuh Tempo', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('invoices_jumlah', 'Jumlah', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('invoices_pajak_ppn', 'Pajak PPn', 'trim|max_length[25]');
        $this->tnotification->set_rules('invoices_pajak_pph', 'Pajak PPh', 'trim|max_length[25]');
        // process
        // $termin_id = $this->input->post('termin_id', true);
        $invoices_id = $this->input->post('invoices_id', true);
        if ($this->tnotification->run() !== FALSE) {
            $total = $this->input->post('invoices_jumlah', true) + $this->input->post('invoices_pajak_ppn', true) + $this->input->post('invoices_pajak_pph', true);            
            $termin_data = $this->M_penagihan->get_detail_termin_by_id($termin_id);
            // check if total value isnt match with termin value
            $tanggal = $this->input->post('invoices_tanggal', true);
            // if($termin_data['termin_nilai'] != $total){
            //     $termin_nilai = number_format($termin_data['termin_nilai'], 0, ',', '.');
            //     $this->tnotification->sent_notification("error", "Total nilai tidak sama dengan nilai termin (Rp. $termin_nilai).");
            //     redirect("project/tools/penagihan/detail/".$termin_id);                
            // }                        
            // //params
            $params = array(
                'invoices_uraian' => $this->input->post('invoices_uraian', true),
                'invoices_id' => $this->input->post('invoices_id', true),
                'invoices_tanggal' => $tanggal,
                'invoices_jatuh_tempo' => $this->input->post('invoices_jatuh_tempo', true),
                'invoices_jumlah' => $this->input->post('invoices_jumlah', true),
                'invoices_pajak_ppn' => $this->input->post('invoices_pajak_ppn', true),
                'invoices_pajak_pph' => $this->input->post('invoices_pajak_pph', true),
                'invoices_status' => $this->input->post('invoices_status', true),
                'invoices_total' => $total,                
                'invoices_bulan' => date('m', strtotime($tanggal)),
                'invoices_tahun' => date('Y', strtotime($tanggal)),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array('invoices_id' => $invoices_id);
            // update
            if ($this->M_penagihan->update($params, $where)) {
                // update status termin if paid / cancelled
                if($this->input->post('invoices_status', true) == 'paid'){
                    $params = array(
                        'termin_status' => 'lunas',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array(
                        'termin_id' => $termin_id
                    );
                    $this->M_penagihan->update_termin($params, $where);
                } elseif($this->input->post('invoices_status', true) == 'cancel'){
                    $params = array(
                        'termin_status' => 'cancel',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array(
                        'termin_id' => $termin_id
                    );
                    $this->M_penagihan->update_termin($params, $where);                
                }
                // notification                
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil diedit");
                redirect("project/tools/penagihan/edit");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal diedit");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diedit");
        }
        // default redirect
        redirect("project/tools/penagihan/edit");
    }
    
    // delete invoice form
    public function delete($invoices_id) {
        //set rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/penagihan/delete.html");
        //assign data
        $data = $this->M_penagihan->get_invoices_by_id($invoices_id);
        if (empty($data)){
            $this->tnotification->sent_notification("error", "Data yang dipilih tidak ada");
            redirect("project/tools/penagihan/");            
        }
        $this->smarty->assign("detail", $data);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }

    // delete process
    public function delete_process() {
        // for testing data caught
        // print_r($_POST); exit;
        // set page rules
        $this->_set_page_rule("D");
        //invoice id
        $invoices_id = $this->input->post('invoices_id', true);
        //get detail
        $result = $this->M_penagihan->get_invoices_by_id(array($invoices_id));
        if(empty($result)){
            //default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar");
            redirect("project/tools/penagihan/");
        }
        // process
        $where = array('invoices_id' => $invoices_id);
        // delete
        if ($this->M_penagihan->delete($where)) {
            // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            // redirect
            redirect("project/tools/penagihan/");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("project/tools/penagihan/");
    }
    
    // print process
    public function print_process($invoices_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // load excel
        $this->load->library('phpexcel');
        $this->load->library('datetimemanipulation');
        // create excell
        $filepath = "resource/doc/template/FM-GA-INVOICE.xls";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        $result = $this->M_penagihan->get_invoices_by_id($invoices_id);
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data tidak ditemukan");
            redirect("project/tools/penagihan/");
        }
        /*
         * SET DATA EXCELL
         */
        $total = $result['invoices_jumlah'] + $result['invoices_pajak_ppn'];
        $objWorksheet->setCellValue('K14', $this->datetimemanipulation->get_full_date($result['invoices_tanggal']));
        $objWorksheet->setCellValue('K13', $result['invoices_nomor']);
        $objWorksheet->setCellValue('B10', $result['client_desc']);
        $objWorksheet->setCellValue('B11', $result['client_address']);
        $objWorksheet->setCellValue('B13', $result['client_city']);
        $objWorksheet->setCellValue('E34', $this->datetimemanipulation->get_full_date($result['invoices_jatuh_tempo']));
        $objWorksheet->setCellValue('D20', $result['judul_kontrak']);
        $objWorksheet->setCellValue('D21', $result['termin_uraian']);
        $objWorksheet->setCellValue('K21', $result['invoices_jumlah']);
        $objWorksheet->setCellValue('K36', $result['invoices_jumlah']);
        $objWorksheet->setCellValue('K37', $result['invoices_pajak_ppn']);
        $objWorksheet->setCellValue('K38', $total);
        // file_name
        $tanggal = $this->datetimemanipulation->get_full_date($result['invoices_tanggal']);
        $file_name = "INV_" . str_replace(' ', '_', strtoupper($tanggal));
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $obj_writer->save('php://output');
    } 
    
    // public function get_project_by_tahun() {
    //     $id = trim($this->input->post('project_id', TRUE));
    //     // get data pegawai
    //     $kontrak = $this->M_penagihan->get_kontrak_by_idProjects($id);
    //     $termin = $this->M_penagihan->get_termin_by_idProjects($id);
        
    //     if (empty($kontrak) && empty($termin)) {
            
    //     } else {
    //         header('Content-Type: application/json');
    //         echo json_encode($kontrak);
    //         echo json_encode($termin);
    //     }
    // }

    // get project list by tahun
    public function get_project_by_tahun()
    {
        $id = trim($this->input->post('id', TRUE));
        // get data project
        $project = $this->M_penagihan->get_project_by_tahun($id);
        if (empty($project)) {
        } else {
            header('Content-Type: application/json');
            echo json_encode($project);
        }
    }
}