<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// Load library phpspreadsheet
require('./excel/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

class pegawai extends ApplicationBase {

    // contructor
    public function __construct() {
        // parent contructor
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/master/m_pegawai');
        $this->load->model('m_preferences');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }
    //
    // list view
    public function index() {
        //set rule
        $this->_set_page_rule("R");
        //set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/list.html");
        // list department
        $this->smarty->assign("rs_department", $this->m_pegawai->get_all_unit_kerja());
        // get search parameter
        $search = $this->tsession->userdata('pegawai_search');
        $this->smarty->assign("search", $search);
        // solved illegal string issue

        if($search){
            // search parameters
            $search['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
            $search['department_id'] = empty($search['department_id']) ? '%' : $search['department_id'];
        } else {
            $search = array(
                'full_name' => '%%',
                'departement_id' => '%%'
            );
        }
        //fix undefined index departmend_id
        $params = array(
            isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
            isset($search['department_id']) && !empty($search['department_id']) ? $search['department_id'] : '%%'
        );
        // pagination
        $config['base_url'] = site_url("kepegawaian/master/pegawai/index/");
        $config['total_rows'] = $this->m_pegawai->get_total_pegawai($params);
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
        $this->smarty->assign("total_data", $config['total_rows']);
        $this->smarty->assign("total_data_presented", 0);
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);
        /* end of pagination ---------------------- */
        // get list data
        //fix undefined index departmend_id
        $params = array(
            isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
            isset($search['department_id']) && !empty($search['department_id']) ? $search['department_id'] : '%%',
            ($start - 1), $config['per_page']
        );
        $this->smarty->assign("rs_id", $this->m_pegawai->get_all_pegawai($params));
        //notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    public function export_excel()
    {
     //set template content
     $this->smarty->assign("template_content", "kepegawaian/master/pegawai/list.html");
     // list department
     $this->smarty->assign("rs_department", $this->m_pegawai->get_all_unit_kerja());
     // get search parameter
     $search = $this->tsession->userdata('pegawai_search');
     $this->smarty->assign("search", $search);
     // solved illegal string issue
     if($search){
         // search parameters
         $search['full_name'] = empty($search['full_name']) ? '%' : '%' . $search['full_name'] . '%';
         $search['department_id'] = empty($search['department_id']) ? '%' : $search['department_id'];
     } else {
         $search = array(
             'full_name' => '%%',
             'departement_id' => '%%'
         );
     }
     //fix undefined index departmend_id
     $params = array(
         isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
         isset($search['department_id']) && !empty($search['department_id']) ? $search['department_id'] : '%%'
     );
     // pagination
     $config['base_url'] = site_url("kepegawaian/master/pegawai/index/");
     $config['total_rows'] = $this->m_pegawai->get_total_pegawai($params);
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
     // get list data
     //fix undefined index departmend_id
     $params = array(
         isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
         isset($search['department_id']) && !empty($search['department_id']) ? $search['department_id'] : '%%',
         ($start - 1), $config['per_page']
     );
     $this->smarty->assign("rs_id", $this->m_pegawai->get_all_pegawai($params));
    $data = $this->smarty->assign("rs_id", $this->m_pegawai->get_all_pegawai($params));
     //notification
     $this->tnotification->display_notification();
     $this->tnotification->display_last_field();
      
     $spreadsheet = new Spreadsheet();
    // Set document properties
    $spreadsheet->getProperties()->setCreator('Data Karyawan')
    ->setLastModifiedBy('HR ')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');
    
    // Add some data
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'NO')
    ->setCellValue('B1', 'Nama Lengkap')
    ->setCellValue('C1', 'Unit Kerja')
    ->setCellValue('D1', 'Email')
    ->setCellValue('E1', 'No Telepon')
    ->setCellValue('F1', 'Jenis Kelamin')
    ->setCellValue('G1', 'Status Pegawai');
    // Miscellaneous glyphs, UTF-8
    $i=2; 
    $no =1;
    $params = array(
        isset($search['full_name']) && !empty($search['full_name']) ? $search['full_name'] : '%%',
        isset($search['department_id']) && !empty($search['department_id']) ? $search['department_id'] : '%%'
    ); 
    
    foreach($data as $paramsexcel => $rs_id) {
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A'.$i,$no++)
    ->setCellValue('B'.$i,$paramsexcel['nama_lengkap'])
    ->setCellValue('C'.$i,$paramsexcel['struktur_nama'])
    ->setCellValue('D'.$i,$paramsexcel['user_mail'])
    ->setCellValue('E'.$i,$paramsexcel['nomor_telepon'])
    ->setCellValue('F'.$i,$paramsexcel['jenis_kelamin'])
    ->setCellValue('G'.$i,$paramsexcel['pegawai_status']);
    $i++;
    }
   
    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $spreadsheet->setActiveSheetIndex(0);
    ob_end_clean();
    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Karyawan.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
    }
    // proses pencarian
    public function proses_cari() {
        // set page rules
        $this->_set_page_rule("R");
        // data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata('pegawai_search');
        } else {
            $params = array(
                "full_name" => $this->input->post("full_name"),
                "department_id" => $this->input->post("department_id")
            );
            $this->tsession->set_userdata("pegawai_search", $params);
        }
        // redirect
        redirect("kepegawaian/master/pegawai");
    }

    // add form
    public function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/add.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        // list department
        $this->smarty->assign("rs_department", $this->m_pegawai->get_all_unit_kerja());
        // load roles
        $this->smarty->assign("rs_roles", $this->m_pegawai->get_all_roles_by_portal($this->portal));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // untuk hak akses
        $data = $this->tnotification->get_field_data();
        if (isset($data['roles[]']['postdata'])) {
            if (!empty($data['roles[]']['postdata'])) {
                // hak akses
                $this->smarty->assign('roles_selected', $data['roles[]']['postdata']);
            }
        }
        // output
        parent::display();
    }

    // add process
    public function add_process() {
        //set page rules
        $this->_set_page_rule("C");
        // user account
        $this->tnotification->set_rules('user_alias', 'User Alias', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('user_mail', 'Email', 'trim|required|valid_email|max_length[50]');
        // check username
        $email = trim($this->input->post('user_mail', true));
        if ($this->m_pegawai->is_exist_email($email)) {
            $this->tnotification->sent_notification("error", "Email sudah terdaftar.");
            redirect("kepegawaian/master/pegawai/add");
        }
        // generate user_id
        $prefix = date('ymd');
        $params = $prefix . '%';
        $user_id = $this->m_pegawai->get_user_last_id($prefix, $params);
        if (!$user_id) {
            $this->tnotification->sent_notification("error", "ID sudah ada.");
            redirect("kepegawaian/master/pegawai/add");
        }
        // proses
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $user_id,
                'user_alias' => $this->input->post('user_alias', true),
                'user_mail' => $this->input->post('user_mail', true),
                'user_st' => '0',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s"),
            );

            $params_pegawai = array(
                'user_id' => $user_id,
                'nama_lengkap' => $this->input->post('user_alias', true),
                'struktur_cd' => '0',
                'pegawai_status' => 'working',
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s"),
            );

            if ($this->m_pegawai->insert_user($params)) {
                //insert pegawai
                if ($this->m_pegawai->insert_pegawai($params_pegawai)) {
                    $params_status = array(
                        'user_id' => $user_id,
                        'tahun' => date("y"),
                        'pegawai_status' => 'working',
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['user_alias'],
                        'mdd' => date("Y-m-d H:i:s"),
                    );
                    $this->m_pegawai->insert_pegawai_status($params_status);
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan. Silakan edit user account.");
                    redirect("kepegawaian/master/pegawai/add_account/" . $user_id);
                } else {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("error", "Data gagal disimpan. Silakan edit user account.");
                    redirect("kepegawaian/master/pegawai/add_info/" . $user_id);
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        //default redirect
        redirect("kepegawaian/master/pegawai/add_account/" . $user_id);
    }

    // add user account
    public function add_account($user_id = "") {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/add_account.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai");
        }
        $this->smarty->assign("result", $result);
        $this->smarty->assign("user_id", $user_id);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add user account process
    public function add_account_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'required');
        $this->tnotification->set_rules('user_name', 'Username', 'trim|required|max_length[50]');
        if ($this->input->post('user_completed', true) == '0') {
            $this->tnotification->set_rules('user_pass', 'Password', 'trim|required|max_length[50]');
        } else {
            $this->tnotification->set_rules('user_pass', 'Password', 'trim|max_length[50]');
        }
        // check username
        $username = trim($this->input->post('user_name', true));
        if ($this->input->post('user_name') != $this->input->post('user_name_old', true)) {
            if ($this->m_pegawai->is_exist_username($username)) {
                $this->tnotification->sent_notification("error", "Username sudah ada.");
                redirect("kepegawaian/master/pegawai/add_account/" . $this->input->post('user_id'));
            }
        }
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_name' => $this->input->post('user_name', true),
            );
            if ($this->input->post('user_pass')) {
                $password_key = abs(crc32($this->input->post('user_pass', true)));
                $pass = $this->encrypt->encode(md5($this->input->post('user_pass', true)), $password_key);
                $sess_password[$this->input->post('user_id', true)] = $this->input->post('user_pass', true);
                $this->tsession->set_userdata('sess_password', $sess_password);
                $params['user_pass'] = $pass;
                $params['user_key'] = $password_key;
                $params['mdb'] = $this->com_user['user_id'];
                $params['mdd'] = date("Y-m-d H:i:s");
            }
            $where = array('user_id' => $this->input->post('user_id', true));
            if ($this->m_pegawai->update_user($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                //check next or prev
                if (isset($_POST['save_next'])) {
                    redirect("kepegawaian/master/pegawai/add_roles/" . $this->input->post('user_id'));
                } else {
                    redirect("kepegawaian/master/pegawai/add_info/" . $this->input->post('user_id'));
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
    }

    // add user info
    public function add_info($user_id = "") {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/add_info.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai");
        }
        $this->smarty->assign("result", $result);
        //rs gender
        $this->smarty->assign("rs_gender", array('L' => 'Laki-laki', 'P' => 'Perempuan'));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add user info process
    public function add_info_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('user_alias', 'User Alias', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('user_mail', 'Email', 'trim|required|valid_email|max_length[50]');
        // check email
        $email = trim($this->input->post('user_mail', true));
        if ($this->input->post('user_mail') != $this->input->post('user_mail_old', true)) {
            if ($this->m_pegawai->is_exist_email($email)) {
                $this->tnotification->set_error_message('Email tidak tersedia.');
            }
        }
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_alias' => $this->input->post('user_alias', true),
                'user_mail' => $this->input->post('user_mail', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
            );
            $where = array('user_id' => $this->input->post('user_id', true));
            if ($this->m_pegawai->update_user($params, $where)) {
                $params = array(
                    'pegawai_email' => $this->input->post('user_mail', true),
                    'mdd' => date('Y-m-d H:i:s'),
                    'mdb' => $this->com_user['user_id'],
                );
                // update pegawai email
                if ($this->m_pegawai->update_pegawai($params, $where)) {
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    redirect("kepegawaian/master/pegawai/add_account/" . $this->input->post('user_id'));
                } else {
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data gagal disimpan");
                    redirect("kepegawaian/master/pegawai/add_account/" . $this->input->post('user_id'));
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/add_info/" . $this->input->post('user_id'));
    }

    // add user roles
    public function add_roles($user_id = "") {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/add_roles.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('role_search_account');
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $group_id = empty($search['group_id']) ? '%' : $search['group_id'];
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("users/account");
        }
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai");
        }
        $this->smarty->assign("result", $result);
        //get checked role by user
        $roles_checked = array();
        $rs_roles = $this->m_pegawai->get_roles_by_user($user_id);
        foreach ($rs_roles as $role) {
            $roles_checked[] = $role["role_id"];
        }
        // get data
        $this->smarty->assign("rs_roles", $this->m_pegawai->get_roles_by_group($group_id));
        $rs_group = $this->m_pegawai->get_role_group();
        $this->smarty->assign('rs_group', $rs_group);
        //modified
        $this->smarty->assign("modified_by", $this->m_pegawai->get_user_alias_by_id($result['mdb']));
        $this->smarty->assign("mdd", $result['mdd']);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        $data = $this->tnotification->get_field_data();
        if (isset($data['roles[]']['postdata'])) {
            if (!empty($data['roles[]']['postdata'])) {
                // hak akses
                $this->smarty->assign('roles_checked', $data['roles[]']['postdata']);
            }
        } else {
            $this->smarty->assign('roles_checked', $roles_checked);
        }
        // output
        parent::display();
    }

    // search role process
    public function role_search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // session
        if ($this->input->post('save') == "Cari") {
            // params
            $params = array(
                "group_id" => $this->input->post('group_id', true),
            );
            // set session
            $this->tsession->set_userdata("role_search_account", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("role_search_account");
        }
        // redirect
        redirect("kepegawaian/master/pegawai/add_roles/" . $this->input->post('user_id', true));
    }

    // ass user roles process
    public function add_roles_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required|max_length[10]');
        // process
        if ($this->tnotification->run() !== false) {
            $where = array('user_id' => $this->input->post('user_id', true));
            $this->m_pegawai->delete_role_user($where);
            if ($this->input->post('roles')) {
                foreach ($this->input->post('roles') as $key => $val) {
                    $params = array(
                        'user_id' => $this->input->post('user_id', true),
                        'role_id' => $val,
                    );
                    $this->m_pegawai->insert_role_user($params);
                }
            } // notification
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            //check next or prev
            if ($this->input->post('save_next')) {
                redirect("kepegawaian/master/pegawai/user_activation/" . $this->input->post('user_id'));
            } else {
                redirect("kepegawaian/master/pegawai/add_account/" . $this->input->post('user_id'));
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/add_roles/" . $this->input->post('user_id'));
    }

    // set user activation
    public function user_activation($user_id = "") {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/add_user_activation.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai");
        }
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // set user activation process
    public function user_activation_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required|max_length[10]');
        $this->tnotification->set_rules('user_st', 'User Status', 'trim|required|max_length[1]');
        $this->tnotification->set_rules('sendemail', 'Kirim Email', 'trim|required|max_length[1]');
        //check password required
        $sess_password = $this->tsession->userdata('sess_password');
        if ($this->input->post('sendemail', true) == '1' && $this->input->post('user_st', true) == '1' && !isset($sess_password[$this->input->post('user_id', true)])) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("error", "Data gagal disimpan.<br/>Untuk bisa mengirim email berisi detail akun, anda harus mengisikan/mereset password terlebih dahulu.");
            redirect("kepegawaian/master/pegawai/add_account/" . $this->input->post('user_id'));
        }
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_st' => $this->input->post('user_st', true),
            );
            // set condition
            $where = array('user_id' => $this->input->post('user_id', true));
            if ($this->m_pegawai->update_user($params, $where)) {
                //send mail?
                if ($this->input->post('sendemail', true) == '1') {
                    $user = $this->m_pegawai->get_detail_user_by_id($this->input->post('user_id'));
                    if (empty($user)) {
                        // default error
                        $this->tnotification->sent_notification("error", "User tidak terdaftar!");
                        redirect("kepegawaian/master/pegawai/user_activation/" . $this->input->post('user_id'));
                    } else {
                        $this->load->model('m_email');
                        $to = $user['user_mail'];
                        $subject = 'Informasi Login User Time Excelindo';
                        $message = '<h4>Detail Informasi Login User</h4>';
                        $message .= '<strong>Nama : </strong>' . $user['user_alias'] . '<br/>';
                        $message .= '<strong>Email : </strong>' . $user['user_mail'] . '<br/>';
                        $message .= '<strong>Username : </strong>' . $user['user_name'] . '<br/>';
                        $message .= '<strong>Password : </strong>' . $sess_password[$this->input->post('user_id', true)] . '<br/>';
                        $mail = $this->m_email->sendmail($to, $subject, $message);
                        if (!$mail['status']) {
                            //jika gagal email, redirect
                            $this->tnotification->sent_notification("error", "Data berhasil disimpan, namun email gagal dikirim.");
                            redirect("kepegawaian/master/pegawai/user_activation/" . $this->input->post('user_id'));
                        }
                    }
                }
                //unset password session
                $this->tsession->unset_userdata('sess_password');
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                //check next or prev
                if (isset($_POST['save_next'])) {
                    redirect("kepegawaian/master/pegawai/");
                } else {
                    redirect("kepegawaian/master/pegawai/add_roles/" . $this->input->post('user_id'));
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/user_activation/" . $this->input->post('user_id'));
    }

    // detail pegawai
    public function detail($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/detail.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        // get images
        $filepath = 'resource/doc/images/users/' . $result['foto_name'];
        if (!is_file($filepath)) {
            $filepath = 'resource/doc/images/users/default.png';
        }
        $pegawai_img = base_url() . $filepath;
        $this->smarty->assign("employee_img", $pegawai_img);
        $this->smarty->assign("result", $result);
        // get data unit kerja
        $this->smarty->assign("rs_department", $this->m_pegawai->get_all_unit_kerja());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // form edit data pegawai
    public function edit($user_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/edit.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        // get data unit kerja
        $this->smarty->assign("rs_department", $this->m_pegawai->get_all_unit_kerja());
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit data pegawai process
    public function edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('pegawai_nip', 'NIK', 'trim|required');
        $this->tnotification->set_rules('full_name', 'Nama Lengkap', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->tnotification->set_rules('jenis_identitas', 'Jenis Identitas', 'trim|maxlength[50]');
        $this->tnotification->set_rules('nomor_identitas', 'Identitas Karyawan', 'trim|required');
        $this->tnotification->set_rules('birth_place', 'Tempat Lahir', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('birth_date', 'Tanggal Lahir', 'trim|required|maxlength[10]');
        $this->tnotification->set_rules('address_main', 'Alamat Asal', 'trim|maxlength[100]');
        $this->tnotification->set_rules('address_current', 'Alamat Sekarang', 'trim|maxlength[100]');
        $this->tnotification->set_rules('phone_number', 'Nomor Telepon', 'trim|maxlength[30]');
        $this->tnotification->set_rules('employee_st', 'Status Karyawan', 'trim|required');
        $this->tnotification->set_rules('employee_date_in', 'Tanggal Masuk', 'trim|maxlength[10]|required');
        $this->tnotification->set_rules('employee_date_out', 'Tanggal keluar', 'trim|maxlength[10]');
        // pendidikan
        $this->tnotification->set_rules('edu_instansi_nm', 'Nama Instansi', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('edu_instansi_address', 'Alamat Instansi', 'trim|maxlength[100]');
        $this->tnotification->set_rules('edu_graduation_year', 'Tahun Lulus', 'trim|required|maxlength[4]');
        $this->tnotification->set_rules('edu_grade', 'Jenjang Pendidikan', 'trim');
        $this->tnotification->set_rules('edu_spezialitation', 'Spesialisasi', 'trim');
        //check email
        $email = trim($this->input->post('email_address'));
        $email_old = trim($this->input->post('email_address_old'));
        if ($email <> $email_old) {
            if ($this->m_account->is_exist_email($email)) {
                $this->tnotification->set_error_message('Email is not available');
            }
        }
        // proses
        if ($this->tnotification->run() !== FALSE) {
            // params pegawai
            $employee_date_out = $this->input->post('employee_date_out');
            $employee_date_out = empty($employee_date_out) ? NULL : $employee_date_out;
            $params = array(
                'nomor_identitas' => $this->input->post('nomor_identitas'),
                'jenis_identitas' => $this->input->post('jenis_identitas'),
                'nama_lengkap' => $this->input->post('full_name'),
                'jenis_kelamin' => $this->input->post('gender'),
                'tanggal_lahir' => $this->input->post('birth_date'),
                'tempat_lahir' => $this->input->post('birth_place'),
                'alamat_ktp' => $this->input->post('address_main'),
                'alamat_tinggal' => $this->input->post('address_current'),
                'nomor_telepon' => $this->input->post('phone_number'),
                'pegawai_status' => $this->input->post('employee_st'),
                'tanggal_masuk' => $this->input->post('employee_date_in'),
                'tanggal_keluar' => $employee_date_out,
                'pegawai_nip' => $this->input->post('pegawai_nip'),
                'edu_instansi_nm' => $this->input->post('edu_instansi_nm'),
                'edu_instansi_address' => $this->input->post('edu_instansi_address'),
                'edu_grade' => $this->input->post('edu_grade'),
                'edu_spezialitation' => $this->input->post('edu_spezialitation'),
                'edu_graduation_year' => $this->input->post('edu_graduation_year'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'user_id' => $this->input->post('user_id')
            );
            if ($this->m_pegawai->update_pegawai($params, $where)) {
                $tahun = date("Y", strtotime($this->input->post('employee_date_in')));
                $params_status = array(
                    'pegawai_status' => $this->input->post('employee_st'),
                    'tahun' => $tahun,
                    'tanggal_keluar' => $employee_date_out,
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias'],
                    'mdd' => date("Y-m-d H:i:s")
                );
                $this->m_pegawai->update_pegawai_status($params_status, $where);
                // upload foto
                if (!empty($_FILES['employee_img']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/images/users/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = $this->input->post('user_id');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('employee_img', false, 160)) {
                        $data = $this->tupload->data();
                        $param_foto = array(
                            'foto_name' => $data['file_name'],
                            'foto_path' => $config['upload_path']
                        );
                        $this->m_pegawai->update_pegawai($param_foto, $where);
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                }
                // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            //default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/edit/" . $this->input->post('user_id'));
    }

    // delete users
    public function delete($user_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/delete.html");
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        if ($user_id == "" || empty($result)) {
            $this->tnotification->sent_notification("error", "Data tidak ada.");
            redirect("kepegawaian/master/pegawai/");
        }
        // images
        $filepath = 'resource/doc/images/users/' . $result['foto_name'];
        if (!is_file($filepath)) {
            $filepath = 'resource/doc/images/users/default-avatar.png';
        }
        $pegawai_img = base_url() . $filepath;
        $this->smarty->assign("employee_img", $pegawai_img);
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    //delete process
    public function delete_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array('user_id' => $this->input->post('user_id'));
            // update
            if ($this->m_pegawai->delete_pegawai($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
                redirect("kepegawaian/master/pegawai/");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/delete/" . $this->input->post('user_id'));
    }

    /*
     * FOTO PROFIL
     */

    // edit foto
    public function edit_foto($user_id = "") {
        // set page rules
        $this->_set_page_rule("U");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/edit_foto.html");
        // load js
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        // images
        $filepath = 'resource/doc/images/users/' . $result['foto_name'];
        if (!is_file($filepath)) {
            $filepath = 'resource/doc/images/users/default.png';
        }
        $pegawai_img = base_url() . $filepath;
        $this->smarty->assign("employee_img", $pegawai_img);
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit foto process
    public function edit_foto_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID Pegawai', 'trim|required');
        if (!empty($_FILES['employee_img']['tmp_name'])) {
            // load
            $this->load->library('tupload');
            // upload config
            $config['upload_path'] = 'resource/doc/images/users/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = $this->input->post('user_id', TRUE) . date('-mdYhi');
            $this->tupload->initialize($config);
            // process upload images
            if ($this->tupload->do_upload_image('employee_img', false, 160)) {
                $data = $this->tupload->data();
                $param_foto = array(
                    'foto_name' => $data['file_name'],
                    'foto_path' => $config['upload_path']
                );
                $where = array('user_id' => $this->input->post('user_id', TRUE));
                $this->m_pegawai->update_pegawai($param_foto, $where);
                //hapus foto lama
                $filepath = 'resource/doc/images/users/' . $this->input->post('foto_old');
                if (is_file($filepath)) {
                    unlink($filepath);
                }
                $this->tnotification->sent_notification("success", "Foto berhasil disimpan.");
            } else {
                // jika gagal
                $this->tnotification->set_error_message($this->tupload->display_errors());
            }
        } else {
            $this->tnotification->sent_notification("error", "Foto gagal disimpan.");
        }
        redirect("kepegawaian/master/pegawai/edit_foto/" . $this->input->post('user_id'));
    }

    /*
     * JABATAN STRUKTURAL
     */

    // index
    public function jabatan_struktural($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/jabatan_struktural.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        $this->smarty->assign("result", $result);
        $rs_pegawai_jabatan = $this->m_pegawai->get_pegawai_struktural_by_id($user_id);
        $this->smarty->assign("rs_pegawai_jabatan", $rs_pegawai_jabatan);
        // get data jabatan
        $rs_jabatan = $this->m_pegawai->get_all_jabatan_struktural();
        $this->smarty->assign("rs_jabatan", $rs_jabatan);
        //lampiran
        $filepath = base_url() . 'resource/doc/kepegawaian/lampiran/';
        $this->smarty->assign("filepath", $filepath);
        $this->smarty->assign("no", 1);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function jabatan_struktural_add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_struktural_id', 'Jabatan Struktural', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK', 'trim|required');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required');
        $this->tnotification->set_rules('jabatan_default', 'Set Default', 'trim');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'jabatan_default' => $this->input->post('jabatan_default', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            if ($this->m_pegawai->insert_jabatan_struktur_pegawai($params)) {
                if ($this->input->post('jabatan_default', true) == 1) {
                    $data_id = $this->db->insert_id();
                    $this->m_pegawai->set_default('pegawai_jabatan_struktural', $this->input->post('user_id', true), $data_id);
                }
                if ($this->input->post('jabatan_status', true) == 1) {
                    $data_id = $this->db->insert_id();
                    $this->m_pegawai->set_aktif_jabatan_struktural($this->input->post('jabatan_struktural_id', true), $this->input->post('user_id', true), $data_id);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/' . date('Y') . '/jabatan_struktural';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // check filepath
                    if (!$this->upload->validate_upload_path()) {
                        mkdir($config['upload_path'], DIR_WRITE_MODE);
                    }
                    // process upload images
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $param_file = array(
                            'lampiran_file_name' => $data['file_name'],
                            'lampiran_file_path' => $config['upload_path']
                        );
                        $where = array('data_id' => $this->input->post('data_id'));
                        $this->m_pegawai->update_jabatan_struktur_pegawai($param_file, $where);
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->upload->display_errors());
                    }
                } else {
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_struktural/" . $this->input->post('user_id', TRUE));
    }

    // edit process
    public function jabatan_struktural_edit_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_struktural_id', 'Jabatan Struktural', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK', 'trim|required');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Selesai', 'trim|required');
        $this->tnotification->set_rules('jabatan_default', 'Set Default', 'trim');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_struktural_id' => $this->input->post('jabatan_struktural_id', false),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $this->input->post('tanggal_sk', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'jabatan_default' => $this->input->post('jabatan_default', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
            );
            $where = array('data_id' => $this->input->post('data_id', TRUE));
            if ($this->m_pegawai->update_jabatan_struktur_pegawai($params, $where)) {
                if ($this->input->post('jabatan_default') == 1) {
                    $this->m_pegawai->set_default('pegawai_jabatan_struktural', $this->input->post('user_id', true), $this->input->post('data_id', true));
                }
                if ($this->input->post('jabatan_status') == 1) {
                    $this->m_pegawai->set_aktif_jabatan_struktural($this->input->post('jabatan_struktural_id', false), $this->input->post('user_id', true), $this->input->post('data_id', true));
                }
                if ($this->m_pegawai->check_jabatan_match_by_id($this->input->post('jabatan_struktural_id', false)) == true && $this->input->post('jabatan_status') == 0) {
                    $this->m_pegawai->set_non_aktif_jabatan_struktural($this->input->post('user_id', true));
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/' . date('Y') . '/jabatan_struktural/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // process upload file
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $param_file = array(
                            'lampiran_file_name' => $data['file_name'],
                            'lampiran_file_path' => $config['upload_path']
                        );
                        $this->m_pegawai->update_jabatan_struktur_pegawai($param_file, $where);
                        //hapus lampiran lama
                        $filepath = $config['upload_path'] . $this->input->post('lampiran_old');
                        if (is_file($filepath)) {
                            unlink($filepath);
                        }
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->upload->display_errors());
                    }
                } else {
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_struktural/" . $this->input->post('user_id'));
    }

    // delete process
    public function jabatan_struktural_delete_process($user_id = "", $data_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // process
        if (!empty($data_id)) {
            $params = array(
                'data_id' => $data_id
            );
            // update
            if ($this->m_pegawai->delete_jabatan_struktur_pegawai($params)) {
                if ($this->input->post('jabatan_status', TRUE) == 1) {
                    $param_status = array(
                        'jabatan_struktural_id' => NULL,
                        'jabatan_struktural_st' => '0'
                    );
                    $where = array(
                        'user_id' => $this->input->post('user_id', TRUE)
                    );
                    $this->m_pegawai->update_jabatan_struktur_pegawai($param_status, $where);
                }
                // get detail data jabatan
                $result = $this->m_pegawai->get_detail_struktural_by_id($data_id);
                // unlink
                $filepath = 'resource/doc/kepegawaian/lampiran/' . $result['lampiran_file_name'];
                if (is_file($filepath)) {
                    unlink($filepath);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_struktural/" . $user_id);
    }

    /*
     * JABATAN FUNGSIONAL
     */

    // index
    public function jabatan_fungsional($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/jabatan_fungsional.html");
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        $this->smarty->assign("result", $result);
        $rs_pegawai_jabatan = $this->m_pegawai->get_pegawai_fungsional_by_id($user_id);
        $this->smarty->assign("rs_pegawai_jabatan", $rs_pegawai_jabatan);
        // get list jabatan
        $rs_jabatan = $this->m_pegawai->get_all_jabatan_fungsional();
        $this->smarty->assign("rs_jabatan", $rs_jabatan);
        $this->smarty->assign("no", 1);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function jabatan_fungsional_add_process() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'trim|required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_fungsional_id', 'Jabatan fungsional', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required');
        $this->tnotification->set_rules('jabatan_default', 'Set Default', 'trim');
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_fungsional_id' => $this->input->post('jabatan_fungsional_id', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'jabatan_default' => $this->input->post('jabatan_default', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            if ($this->m_pegawai->insert_jabatan_fungsional_pegawai($params)) {
                if ($this->input->post('jabatan_default', true) == 1) {
                    $data_id = $this->db->insert_id();
                    $this->m_pegawai->set_default('pegawai_jabatan_fungsional', $this->input->post('user_id', true), $data_id);
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
            $this->tnotvification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_fungsional/" . $this->input->post('user_id'));
    }

    // edit process
    public function jabatan_fungsional_edit_process() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('jabatan_fungsional_id', 'Jabatan fungsional', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('jabatan_status', 'Status', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required');
        $this->tnotification->set_rules('tanggal_selesai', 'trim|Tanggal Selesai');
        $this->tnotification->set_rules('jabatan_default', 'Set Default', 'trim');
        // process
        if ($this->tnotification->run() !== false) {
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'jabatan_fungsional_id' => $this->input->post('jabatan_fungsional_id', true),
                'jabatan_status' => $this->input->post('jabatan_status', true),
                'tanggal_mulai' => $this->input->post('tanggal_mulai', true),
                'tanggal_selesai' => $this->input->post('tanggal_selesai', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            $where = array(
                'data_id' => $this->input->post('data_id', TRUE)
            );
            if ($this->m_pegawai->update_jabatan_fungsional_pegawai($params, $where)) {
                if ($this->input->post('jabatan_default', true) == 1) {
                    $this->m_pegawai->set_default('pegawai_jabatan_fungsional', $this->input->post('user_id', true), $this->input->post('data_id', true));
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_fungsional/" . $this->input->post('user_id'));
    }

    // delete process
    public function jabatan_fungsional_delete_process($user_id = "", $data_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // process
        if (!empty($data_id)) {
            $params = array(
                'data_id' => $data_id
            );
            // update
            if ($this->m_pegawai->delete_jabatan_fungional_pegawai($params)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/jabatan_fungsional/" . $user_id);
    }

    /*
     * UNIT KERJA
     */

    // index
    public function unit_kerja($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/unit_kerja.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_detail_by_id($user_id);
        $this->smarty->assign("result", $result);
        $rs_pegawai_unit = $this->m_pegawai->get_pegawai_unit_by_id($user_id);
        $this->smarty->assign("rs_pegawai_unit", $rs_pegawai_unit);
        // get data unit kerja
        $rs_unit = $this->m_pegawai->get_all_unit_kerja();
        $this->smarty->assign("rs_unit", $rs_unit);
        //lampiran
        $filepath = base_url() . 'resource/doc/kepegawaian/lampiran/';
        $this->smarty->assign("filepath", $filepath);
        $this->smarty->assign("no", 1);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process
    public function unit_kerja_add_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'required|max_length[15]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        $this->tnotification->set_rules('tanggal_sk', 'Tanggal SK');
        $this->tnotification->set_rules('unit_kerja_status', 'Status', 'required');
        // process
        if ($this->tnotification->run() !== false) {
            $tanggal_sk = (empty($this->input->post('tanggal_sk', true)) ? null : $this->input->post('tanggal_sk', true));
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'struktur_cd' => $this->input->post('struktur_cd', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $tanggal_sk,
                'unit_kerja_status' => $this->input->post('unit_kerja_status', true),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
                'mdd' => date('Y-m-d H:i:s')
            );
            if ($this->m_pegawai->insert_unit_kerja_pegawai($params)) {
                // notification
                $data_id = $this->db->insert_id();
                $this->m_pegawai->set_aktif_unit_kerja($this->input->post('struktur_cd', true), $this->input->post('user_id', true), $data_id);
                // default success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/' . date('Y') . '/unit_kerja/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // check filepath (harus setelah config path diinisialisasi)
                    if (!$this->upload->validate_upload_path()) {
                        mkdir($config['upload_path'], DIR_WRITE_MODE);
                    }
                    // process upload images
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $param_file = array(
                            'lampiran_file_name' => $data['file_name'],
                            'lampiran_file_path' => $config['upload_path']
                        );
                        $where = array(
                            'data_id' => $data_id
                        );
                        $this->m_pegawai->update_unit_kerja_pegawai($param_file, $where);
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->upload->display_errors());
                    }
                } else {
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/unit_kerja/" . $this->input->post('user_id'));
    }

    // edit process
    public function unit_kerja_edit_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID User', 'required|trim|max_length[10]');
        $this->tnotification->set_rules('data_id', 'Unit Kerja', 'required|max_length[11]');
        $this->tnotification->set_rules('struktur_cd', 'Unit Kerja', 'required|max_length[10]');
        $this->tnotification->set_rules('nomor_sk', 'Nomor SK', 'required|max_length[50]');
        $this->tnotification->set_rules('pejabat_sk', 'Pejabat SK', 'trim|max_length[50]');
        // process
        if ($this->tnotification->run() !== false) {
            $data_id = $this->db->insert_id();
            $this->m_pegawai->set_aktif_unit_kerja($this->input->post('struktur_cd', true), $this->input->post('user_id', true), $this->input->post('data_id', true));
            $tanggal_sk = (empty($this->input->post('tanggal_sk', true)) ? null : $this->input->post('tanggal_sk', true));
            $params = array(
                'user_id' => $this->input->post('user_id', true),
                'data_id' => $this->input->post('data_id', true),
                'struktur_cd' => $this->input->post('struktur_cd', true),
                'nomor_sk' => $this->input->post('nomor_sk', true),
                'pejabat_sk' => $this->input->post('pejabat_sk', true),
                'tanggal_sk' => $tanggal_sk,
                'unit_kerja_status' => $this->input->post('unit_kerja_status', true),
                'mdd' => date('Y-m-d H:i:s'),
                'mdb' => $this->com_user['user_id'],
                'mdb_name' => $this->com_user['user_alias'],
            );
            $where = array(
                'data_id' => $this->input->post('data_id', TRUE)
            );
            if ($this->m_pegawai->update_unit_kerja_pegawai($params, $where)) {
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                if (!empty($_FILES['lampiran_file_name']['tmp_name'])) {
                    // load
                    $this->load->library('upload');
                    // upload config
                    $config['upload_path'] = 'resource/doc/files/kepegawaian/' . date('Y') . '/unit_kerja/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|rar|zip';
                    $this->upload->initialize($config);
                    // check filepath
                    if (!$this->upload->validate_upload_path()) {
                        mkdir($config['upload_path'], DIR_WRITE_MODE);
                    }
                    // process upload images
                    if ($this->upload->do_upload('lampiran_file_name', false, 160)) {
                        $data = $this->upload->data();
                        $param_file = array(
                            'lampiran_file_name' => $data['file_name'],
                            'lampiran_file_path' => $config['upload_path']
                        );
                        $where = array(
                            'data_id' => $this->input->post('data_id', TRUE)
                        );
                        $this->m_pegawai->update_unit_kerja_pegawai($param_file, $where);
                        //hapus lampiran lama
                        $filepath = $config['upload_path'] . $this->input->post('lampiran_old');
                        if (is_file($filepath)) {
                            unlink($filepath);
                        }
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                } else {
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan.");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/unit_kerja/" . $this->input->post('user_id'));
    }

    // delete process
    public function unit_kerja_delete_process($user_id = "", $data_id = "") {
        // set page rules
        $this->_set_page_rule("D");
        // process
        if (!empty($data_id)) {
            $params = array(
                'data_id' => $data_id
            );
            // update
            if ($this->m_pegawai->delete_unit_kerja_pegawai($params)) {
                // get detail pegawai by id
                $result = $this->m_pegawai->get_detail_unit_kerja_by_id($data_id);
                // unlink
                $filepath = 'resource/doc/kepegawaian/lampiran/' . $result['lampiran_file_name'];
                if (is_file($filepath)) {
                    unlink($filepath);
                }
                // notification
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/unit_kerja/" . $user_id);
    }

    /*
     * USER ACCOUNT
     */

    // index
    public function user_account($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set templates
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/edit_user_account.html");
        // load css
        $this->smarty->load_style('default/css/custom.css');
        // get detail pegawai by id
        $result = $this->m_pegawai->get_pegawai_account_by_id($user_id);
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // update process
    public function user_account_process() {
        // cek input
        $this->tnotification->set_rules('user_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('user_mail', 'Email', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('user_alias', 'User Alias', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('user_name', 'Username', 'trim|required|maxlength[50]');
        $this->tnotification->set_rules('user_pass', 'Password', 'trim|maxlength[50]');
        $this->tnotification->set_rules('user_st', 'Status', 'trim|required');
        //check email
        $email = trim($this->input->post('email_address'));
        $email_old = trim($this->input->post('email_address_old'));
        if ($email <> $email_old) {
            if ($this->m_account->is_exist_email($email)) {
                $this->tnotification->set_error_message('Email sudah ada terdaftar.');
            }
        }
        // proses
        if ($this->tnotification->run() !== FALSE) {
            //user_pass blm
            if ($this->input->post('user_pass') != '') {
                $password_key = abs(crc32($this->input->post('user_pass', true)));
                $pass = $this->encrypt->encode(md5($this->input->post('user_pass', true)), $password_key);
                $params = array(
                    'user_pass' => $pass,
                    'user_key' => $password_key,
                    'user_mail' => $this->input->post('user_mail'),
                    'user_alias' => $this->input->post('user_alias'),
                    'user_name' => $this->input->post('user_name'),
                    'user_st' => $this->input->post('user_st'),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias']
                );
                $editWithPassword = 1;
            } else {
                $params = array(
                    'user_mail' => $this->input->post('user_mail'),
                    'user_alias' => $this->input->post('user_alias'),
                    'user_name' => $this->input->post('user_name'),
                    'user_st' => $this->input->post('user_st'),
                    'mdb' => $this->com_user['user_id'],
                    'mdb_name' => $this->com_user['user_alias']
                );
                $editWithPassword = 0;
            }
            $where = array(
                'user_id' => $this->input->post('user_id', TRUE)
            );
            if (($editWithPassword == 1 ? $this->m_pegawai->update_data_akun_wpassword($params, $where) : $this->m_pegawai->update_data_akun($params, $where))) {
                // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            //default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("kepegawaian/master/pegawai/user_account/" . $this->input->post('user_id'));
    }

    /*
     * USER ROLE
     */

    // index
    public function user_role($user_id = "") {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/master/pegawai/edit_user_role.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
        $this->smarty->load_javascript("resource/themes/default/plugins/select2/dist/js/select2.min.js");
        // get search parameter
        $search = $this->tsession->userdata('role_search_account');
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }
        // search parameters
        $group_id = empty($search['group_id']) ? '%' : $search['group_id'];
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("users/account");
        }
        //get detail
        $result = $this->m_pegawai->get_detail_user_by_id($user_id);
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Data yang anda pilih tidak terdaftar!");
            redirect("kepegawaian/master/pegawai");
        }
        $this->smarty->assign("result", $result);
        //get checked role by user
        $roles_checked = array();
        $rs_roles = $this->m_pegawai->get_roles_by_user($user_id);
        foreach ($rs_roles as $role) {
            $roles_checked[] = $role["role_id"];
        }
        // get data
        $this->smarty->assign("rs_roles", $this->m_pegawai->get_roles_by_group($group_id));
        $rs_group = $this->m_pegawai->get_role_group();
        $this->smarty->assign('rs_group', $rs_group);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        $data = $this->tnotification->get_field_data();
        if (isset($data['roles[]']['postdata'])) {
            if (!empty($data['roles[]']['postdata'])) {
                // hak akses
                $this->smarty->assign('roles_checked', $data['roles[]']['postdata']);
            }
        } else {
            $this->smarty->assign('roles_checked', $roles_checked);
        }
        // output
        parent::display();
    }

    // search process
    public function user_role_search_process() {
        // set page rules
        $this->_set_page_rule("R");
        // session
        if ($this->input->post('save') == "Cari") {
            // params
            $params = array(
                "group_id" => $this->input->post('group_id', true),
            );
            // set session
            $this->tsession->set_userdata("role_search_account", $params);
        } else {
            // unset session
            $this->tsession->unset_userdata("role_search_account");
        }
        // redirect
        redirect("kepegawaian/master/pegawai/user_role/" . $this->input->post('user_id', true));
    }

    // change roles settings
    public function change_roles_settings() {
        // check input
        $id = $this->input->post('params', true);
        $data = $this->input->post('data', true);
        $params = explode('-', $id);
        // --
        $role_id = $params[0];
        $user_id = $params[1];
        // check params
        if ($data == 0) {
            // set params for insert role
            $params = array(
                'user_id' => $user_id,
                'role_id' => $role_id,
            );
            // insert role
            $this->m_pegawai->insert_role_user($params);
            // return
            $params = array('id' => $id, 'data' => 1);
        } else {
            // set params for delete role
            $where = array(
                'user_id' => $user_id,
                'role_id' => $role_id,
            );
            // delete process
            $this->m_pegawai->delete_role_user($where);
            // return
            $params = array('id' => $id, 'data' => 0);
        }
        //return to json
        header('Content-Type: application/json');
        echo json_encode($params);
    }

}
