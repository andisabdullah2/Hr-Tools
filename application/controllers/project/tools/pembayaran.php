<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pembayaran extends ApplicationBase {

    //constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('project/tools/m_pembayaran');
        //load library
        $this->load->library('tnotification');
        $this->load->library('pagination');
        $this->load->library('tupload');
    }

    // list pembayaran
    public function index() {
        //set page rules
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "project/tools/pembayaran/list.html");
        // list bulan & tahun
        $this->smarty->assign("rs_bulan", $this->datetimemanipulation->get_bulan_indonesia());
        $this->smarty->assign("rs_tahun", $this->m_pembayaran->get_list_tahun_penagihan());
        // search
        $search = $this->tsession->userdata("project_pembayaran_search");
        // search parameters
        $nama_kontrak = empty($search['nama_kontrak']) ? '%' : '%' . $search['nama_kontrak'] . '%';
        $search['bulan'] = empty($search['bulan']) ? date('m') : $search['bulan'];
        $search['tahun'] = empty($search['tahun']) ? date('Y') : $search['tahun'];
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("project/tools/pembayaran/index/");
        $params = array($nama_kontrak,$search['bulan'],$search['tahun']);
        $config['total_rows'] = $this->m_pembayaran->get_total_penagihan($params);
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
        $params = array($nama_kontrak,$search['bulan'],$search['tahun'], ($start - 1), $config['per_page']);
        $rs_id = $this->m_pembayaran->get_all_penagihan_data($params);
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
            $this->tsession->unset_userdata("project_pembayaran_search");
        } else {
            $params = array(
                "nama_kontrak" => $this->input->post("penagihan"),
                "bulan" => $this->input->post("bulan"),
                "tahun" => $this->input->post("tahun"),
            );
            $this->tsession->set_userdata("project_pembayaran_search", $params);
        }
        redirect("project/tools/pembayaran");
    }
    
    // detail
    public function detail($invoices_id = "") {
        // set rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "project/tools/pembayaran/detail.html");
        // load js
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");                
        // get detail
        $result = $this->m_pembayaran->get_detail_invoice_by_invoices_id(array($invoices_id));
        // check if invoices doesnt exists
        $invoices = $this->m_pembayaran->get_kuitansi_by_invoices_id(array($invoices_id));
        if(empty($result)){
            $this->tnotification->sent_notification("error", "Data yang dipilih tidak ada");
            redirect("project/tools/pembayaran/");                        
        }
        // check if kuitansi isnt exists then redir to add
        $data = $this->m_pembayaran->get_kuitansi_by_id(array($invoices_id));
        if (empty($data)){
            redirect("project/tools/pembayaran/add_kuitansi/" . $invoices_id);
        }
        $this->smarty->assign("result", $data);        
        $this->smarty->assign('detail', $result);
        $this->smarty->assign('invoices', $invoices);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }
    
    // add kuitansi
    public function add_kuitansi($invoices_id = "") {
        //set rules
        $this->_set_page_rule("C");
        $result = $this->m_pembayaran->get_detail_invoice_by_invoices_id(array($invoices_id));
        if(empty($result)){
            $this->tnotification->sent_notification("error", "Data invoice yang dipilih tidak ada");
            redirect("project/tools/pembayaran/");                        
        }
        //set template content
        $this->smarty->assign("template_content", "project/tools/pembayaran/add_kuitansi.html");
        $kuitansi_nomor = $this->m_pembayaran->generate_kuitansi_nomor($invoices_id); 
        //load javascript
        $this->smarty->load_javascript("resource/js/autonumeric/autoNumeric-2.0-BETA.js");              
        $this->smarty->assign("invoices_id", $invoices_id);
        $this->smarty->assign('detail', $result);
        $this->smarty->assign('kuitansi_nomor', $kuitansi_nomor);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        //output
        parent::display();
    }
    
    // add kuitansi process
    public function add_kuitansi_process() {
        // set rules
        $this->_set_page_rule("C");
        //cek input
        $this->tnotification->set_rules('invoices_id', 'ID Invoices', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('jenis_pembayaran', 'Jenis Pembayaran', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('kuitansi_tanggal', 'Tanggal Kuitansi', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('kuitansi_dari_bank', 'Bank Pengirim', 'trim|max_length[25]');
        $this->tnotification->set_rules('kuitansi_dari_rekening', 'Rekening Pengirim', 'trim|max_length[30]');
        $this->tnotification->set_rules('kuitansi_ke_bank', 'Bank Penerima', 'trim|max_length[25]');
        $this->tnotification->set_rules('kuitansi_ke_rekening', 'Bank Penerima', 'trim|max_length[30]');
        $this->tnotification->set_rules('kuitansi_status', 'Status', 'trim|required');
        $jenis = $this->input->post('jenis_pembayaran', true);
        $invoices_id = $this->input->post('invoices_id', true);
        if ($jenis == 'transfer'){
            $this->tnotification->set_rules('kuitansi_dari_bank', 'Bank Pengirim', 'trim|required|max_length[25]');
            $this->tnotification->set_rules('kuitansi_dari_rekening', 'Rekening Pengirim', 'trim|required|max_length[30]');
            $this->tnotification->set_rules('kuitansi_ke_bank', 'Bank Penerima', 'trim|required|max_length[25]');
            $this->tnotification->set_rules('kuitansi_ke_rekening', 'Bank Penerima', 'trim|required|max_length[30]');            
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            if($this->m_pembayaran->is_kuitansi_exists($invoices_id)){
                $this->tnotification->sent_notification("error", "Sudah ada data kuitansi.");
                redirect("project/tools/pembayaran/detail/".$invoices_id);
            }
            $kuitansi_nomor = $this->m_pembayaran->generate_kuitansi_nomor($invoices_id); 
            //params
            $params = array(
                'invoices_id' => $invoices_id,
                'jenis_pembayaran' => $jenis,
                'kuitansi_nomor' => $kuitansi_nomor,
                'kuitansi_status' => $this->input->post('kuitansi_status', true),
                'kuitansi_tanggal' => $this->input->post('kuitansi_tanggal', true),
                'kuitansi_dari_bank' => $this->input->post('kuitansi_dari_bank', true),
                'kuitansi_dari_rekening' => $this->input->post('kuitansi_dari_rekening', true),
                'kuitansi_ke_bank' => $this->input->post('kuitansi_ke_bank', true),
                'kuitansi_ke_rekening' => $this->input->post('kuitansi_ke_rekening', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_pembayaran->insert_kuitansi($params)) {
                if($this->input->post('kuitansi_status', true) == 'lunas'){
                    // update invoice
                    $params = array(
                        'invoices_status' => 'paid',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('invoices_id' => $invoices_id);  
                    $this->m_pembayaran->update_invoices($params, $where);
                    $data_invoice = $this->m_pembayaran->get_invoices_by_id($invoices_id);
                    $termin_id = $data_invoice['termin_id'];
                    // update termin            
                    $params = array(
                        'termin_status' => 'lunas',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('termin_id' => $termin_id);  
                    $this->m_pembayaran->update_termin($params, $where);
                }          
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                // default redirect
                redirect("project/tools/pembayaran/detail/".$invoices_id);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("project/tools/pembayaran/add_kuitansi/".$invoices_id);
    }
    
    // edit kuitansi process
    public function edit_kuitansi_process() {
        // cek input
        $this->tnotification->set_rules('invoices_id', 'ID Invoices', 'trim|required|max_length[20]');
        $this->tnotification->set_rules('jenis_pembayaran', 'Jenis Pembayaran', 'trim|required|max_length[25]');
        $this->tnotification->set_rules('kuitansi_tanggal', 'Tanggal Kuitansi', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('kuitansi_dari_bank', 'Bank Pengirim', 'trim|max_length[25]');
        $this->tnotification->set_rules('kuitansi_dari_rekening', 'Rekening Pengirim', 'trim|max_length[30]');
        $this->tnotification->set_rules('kuitansi_ke_bank', 'Bank Penerima', 'trim|max_length[25]');
        $this->tnotification->set_rules('kuitansi_ke_rekening', 'Bank Penerima', 'trim|max_length[30]');
        $this->tnotification->set_rules('kuitansi_status', 'Status', 'trim|required');
        $jenis = $this->input->post('jenis_pembayaran', true);
        $invoices_id = $this->input->post('invoices_id', true);
        if ($jenis == 'transfer'){
            $this->tnotification->set_rules('kuitansi_dari_bank', 'Bank Pengirim', 'trim|required|max_length[25]');
            $this->tnotification->set_rules('kuitansi_dari_rekening', 'Rekening Pengirim', 'trim|required|max_length[30]');
            $this->tnotification->set_rules('kuitansi_ke_bank', 'Bank Penerima', 'trim|required|max_length[25]');
            $this->tnotification->set_rules('kuitansi_ke_rekening', 'Bank Penerima', 'trim|required|max_length[30]');            
        }
        // process
        if ($this->tnotification->run() !== FALSE) {
            //params
            if ($jenis == 'transfer'){
                $params = array(
                    'jenis_pembayaran' => $jenis,
                    'kuitansi_tanggal' => $this->input->post('kuitansi_tanggal', true),
                    'kuitansi_status' => $this->input->post('kuitansi_status', true),
                    'kuitansi_dari_bank' => $this->input->post('kuitansi_dari_bank', true),
                    'kuitansi_dari_rekening' => $this->input->post('kuitansi_dari_rekening', true),
                    'kuitansi_ke_bank' => $this->input->post('kuitansi_ke_bank', true),
                    'kuitansi_ke_rekening' => $this->input->post('kuitansi_ke_rekening', true),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
            } else {
                $params = array(
                    'jenis_pembayaran' => $jenis,
                    'kuitansi_tanggal' => $this->input->post('kuitansi_tanggal', true),
                    'kuitansi_status' => $this->input->post('kuitansi_status', true),
                    'kuitansi_dari_bank' => null,
                    'kuitansi_dari_rekening' => null,
                    'kuitansi_ke_bank' => null,
                    'kuitansi_ke_rekening' => null,
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );                
            }
            $where = array('invoices_id' => $invoices_id);
            // update
            if ($this->m_pembayaran->update_kuitansi($params, $where)) {
                if($this->input->post('kuitansi_status', true) == 'lunas'){
                    // update kuitansi
                    $params = array(
                        'kuitansi_status' => 'lunas',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('invoices_id' => $invoices_id);        
                    $this->m_pembayaran->update_kuitansi($params, $where);
                    // update invoice
                    $params = array(
                        'invoices_status' => 'paid',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('invoices_id' => $invoices_id);  
                    $this->m_pembayaran->update_invoices($params, $where);
                    $data_invoice = $this->m_pembayaran->get_invoices_by_id($invoices_id);
                    $termin_id = $data_invoice['termin_id'];
                    // update termin            
                    $params = array(
                        'termin_status' => 'lunas',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('termin_id' => $termin_id);  
                    $this->m_pembayaran->update_termin($params, $where);
                } else {
                    // update kuitansi
                    $params = array(
                        'kuitansi_status' => 'draft',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('invoices_id' => $invoices_id);        
                    $this->m_pembayaran->update_kuitansi($params, $where);
                    // update invoice
                    $params = array(
                        'invoices_status' => 'process',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('invoices_id' => $invoices_id);  
                    $this->m_pembayaran->update_invoices($params, $where);
                    $data_invoice = $this->m_pembayaran->get_invoices_by_id($invoices_id);
                    $termin_id = $data_invoice['termin_id'];
                    // update termin            
                    $params = array(
                        'termin_status' => 'waiting',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s")
                    );
                    $where = array('termin_id' => $termin_id);  
                    $this->m_pembayaran->update_termin($params, $where);                    
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
        redirect("project/tools/pembayaran/detail/". $invoices_id);
    }
    
 // print process
    public function print_process($invoices_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // load library & excel
        $this->load->library('phpexcel');
        $this->load->library('datetimemanipulation');
        $this->load->helper('terbilang_helper');
        // create excell
        $filepath = "resource/doc/template/FM-GA-KUITANSI.xlsx";
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $this->phpexcel = $objReader->load($filepath);
        $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
        $result = $this->m_pembayaran->get_kuitansi_by_id($invoices_id);
        if (empty($result)) {
            // default redirect
            $this->tnotification->sent_notification("error", "Data tidak ditemukan");
            redirect("project/tools/pembayaran/detail/". $invoices_id);
        }
        /*
         * SET DATA EXCELL
         */
        $total = $result['invoices_jumlah'] + $result['invoices_pajak_ppn'];
        $terbilang = numb_to_alphabet($total) . "RUPIAH";
        $objWorksheet->setCellValue('F22', $this->datetimemanipulation->get_full_date($result['kuitansi_tanggal']));
        $objWorksheet->setCellValue('M19', $this->datetimemanipulation->get_full_date($result['kuitansi_tanggal']));
        $objWorksheet->setCellValue('H10', $result['kuitansi_nomor']);
        $objWorksheet->setCellValue('H11', $result['client_desc']);
        $objWorksheet->setCellValue('H12', $total);
        $objWorksheet->setCellValue('H13', strtoupper($terbilang));
        $objWorksheet->setCellValue('H14', $result['judul_kontrak']);
        $objWorksheet->setCellValue('H15', $result['termin_uraian']);
        $objWorksheet->setCellValue('F20', $result['kuitansi_ke_bank']);
        if($result['jenis_pembayaran'] == 'transfer'){
            $objWorksheet->setCellValue('J17', 'V');
        } else {
            $objWorksheet->setCellValue('C17', 'V');
        }
        // file_name
        $tanggal = $this->datetimemanipulation->get_full_date($result['kuitansi_tanggal']);
        $file_name = "INV_" . str_replace(' ', '_', strtoupper($tanggal));
        // --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $file_name . '.xlsx');
        header('Cache-Control: max-age=0');
        // output
        $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $obj_writer->save('php://output');
    }        
    
}