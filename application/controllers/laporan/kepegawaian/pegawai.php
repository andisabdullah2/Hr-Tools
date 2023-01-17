<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");
// load base class if needed
require_once( APPPATH . "controllers/base/OperatorBase.php" );

class pegawai extends ApplicationBase {

    // contructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model("laporan/kepegawaian/m_pegawai");
        // load library
        $this->load->library("tnotification");
        $this->load->library("pagination");
    }

    // index
    public function index() {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "laporan/kepegawaian/pegawai/index.html");
        // get list department
        $this->smarty->assign("rs_department", $this->m_pegawai->get_unit_kerja());
        // get search parameter
        $search = $this->tsession->userdata('report_task_search');
        // search parameters
        $full_name = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
        $struktur_cd = empty($search['struktur_cd']) ? '%' : '%' . $search['struktur_cd'] . '%';
        $this->smarty->assign("search", $search);
        /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("laporan/kepegawaian/pegawai/index/");
        $config['total_rows'] = $this->m_pegawai->get_total_employee(array($full_name, $struktur_cd));
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
        $params = array($full_name, $struktur_cd, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $result=$this->m_pegawai->get_all_karyawan_limit($params));
        //output
        parent::display();
    }

    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('report_task_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "struktur_cd" => $this->input->post("struktur_cd")
            );
            $this->tsession->set_userdata("report_task_search", $params);
        }
        // redirect
        redirect("laporan/kepegawaian/pegawai");
    }

    // cetak laporan
    public function cetak($user_id = "") {
        //set page rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "laporan/kepegawaian/pegawai/cetak.html");
        // get user detail
        $result = $this->m_pegawai->get_employee_detail_by_id($user_id);
        // lokasi image
        $dir_path = './resource/doc/images/users/';
        // set default image
        if (file_exists($dir_path.$result['foto_name']) AND !empty($result['foto_name'])) {
                $this->smarty->assign('default_image','resource/doc/images/users/'.$result['foto_name']);
        }
        else {
                $this->smarty->assign('default_image','resource/doc/images/users/default.png');
        }
        // assign result
        $this->smarty->assign("result", $result);
        //output
        parent::display();
    }

    // download report
    public function download($user_id = '') {
        //set page rule
        $this->_set_page_rule("R");
        // process
        if (!empty($user_id)) {
            // load excel
            $this->load->library('phpexcel');
            // create excell
            $filepath = "resource/doc/template/DATA_KARYAWAN.xls";
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $this->phpexcel = $objReader->load($filepath);
            $objWorksheet = $this->phpexcel->setActiveSheetIndex(0);
            // get user detail
            $result = $this->m_pegawai->get_employee_detail_by_id($user_id);
            /*
             * SET DATA EXCELL
             */
            $objWorksheet->setCellValue('G8', strtoupper($result['nama_lengkap']));
            $objWorksheet->setCellValue('G9', strtoupper($result['struktur_nama']));
            $objWorksheet->setCellValue('G10', strtoupper("'".$result['pegawai_nip']));
            $objWorksheet->setCellValue('G11', strtoupper($result['tempat_lahir'].', '.$this->datetimemanipulation->get_full_date($result['tanggal_lahir'])));
            if ($result['jenis_kelamin'] == 'L') {
                $gender = 'LAKI-LAKI';
            }
            else {
                $gender = 'PEREMPUAN';
            }
            $objWorksheet->setCellValue('G12', strtoupper($gender));
            $objWorksheet->setCellValue('G13', strtoupper('-'));
            $objWorksheet->setCellValue('G14', strtoupper($result['alamat_ktp']));
            $objWorksheet->setCellValue('G16', strtoupper($result['alamat_tinggal']));
            $objWorksheet->setCellValue('G18', "'".strtoupper($result['nomor_telepon']));
            // tanggal masuk
            $tgl = explode('-',$result['tanggal_masuk']);
            $objWorksheet->setCellValue('G19', strtoupper($tgl[0]));
            $objWorksheet->setCellValue('B39', strtoupper('1'));
            $objWorksheet->setCellValue('C39', strtoupper($result['edu_grade'].'/'.$result['edu_spezialitation']));
            $objWorksheet->setCellValue('F39', strtoupper($result['edu_instansi_nm']));
            $objWorksheet->setCellValue('G39', strtoupper($result['edu_graduation_year']));
            $objWorksheet->setCellValue('J39', strtoupper('Berijazah'));            
            // Drawing an image
            $dir_path = './resource/doc/images/users/';
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setWorksheet($objWorksheet);
            $objDrawing->setName("PHOTO");
            $objDrawing->setDescription("PHOTO CV");
            // Berikan gambar default jika kosong
            if (empty($result['foto_name']))
                $result['foto_name'] = 'default.png';
            if (file_exists($dir_path.$result['foto_name'])) {
                $objDrawing->setPath(FCPATH."resource\doc\images\users\\".$result['foto_name']);
            }
            else {
                $objDrawing->setPath(FCPATH.'resource\doc\images\users\default.png');
            }
            $objDrawing->setCoordinates('B8');
            $objDrawing->setHeight(250);
            $objDrawing->setWidth(150);
            $objDrawing->setOffsetX(1);
            $objDrawing->setOffsetY(5);
            // // file_name
            $file_name = "CURRICULUM_VITAE_" . str_replace(' ', '_', strtoupper($result['nama_lengkap']));
            // //--
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=' . $file_name . '.xls');
            header('Cache-Control: max-age=0');
            // // output
            $obj_writer = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
            $obj_writer->save('php://output');
            exit();
        } else {
            // default redirect
            redirect("laporan/kepegawaian/pegawai");
        }
    }

}
