<?php

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Size;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once(APPPATH . 'controllers/base/OperatorBase.php');

class profile extends ApplicationBase
{

    // constructor
    public function __construct()
    {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('home/welcome/m_profile');
        $this->load->model('m_email');
        // load library
        $this->load->library('tupload');
        $this->load->library('tnotification');
        $this->load->library('encrypt');
        $this->load->library('tftp');
    }

    // view
    public function index()
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/welcome/profile/index.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // get data pegawai
        $pegawai = $this->m_profile->get_detail_pegawai_by_id($this->com_user['user_id']);
        $this->smarty->assign('pegawai', $pegawai);
        // get data files
        $this->smarty->assign("total_data_presented", 0);
        $start = $this->uri->segment(5, 0) + 1;
        $this->smarty->assign("no", $start);

        $params = array(($start - 1));
        $this->smarty->assign("rs_files", $this->m_profile->get_files_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output        
        parent::display();
    }

    // view
    public function account_settings()
    {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "home/welcome/profile/account_settings.html");
        // load javascript
        $this->smarty->load_javascript("resource/themes/default/plugins/uniform/uniform.min.js");
        // result
        $this->smarty->assign("result", $result = $this->m_account->get_user_account_by_id($this->com_user['user_id']));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit akun process
    public function edit_account_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // check input
        $this->tnotification->set_rules('old_pass', 'Password Saat Ini', 'trim|required');
        $this->tnotification->set_rules('user_pass', 'Password Baru', 'trim|required|min_length[6]|max_length[20]');
        $this->tnotification->set_rules('confirm_user_pass', 'Konfirmasi Password Baru', 'trim|required|min_length[6]|max_length[50]');
        // validate password
        $password_old = trim($this->input->post('old_pass', TRUE));
        $password_decode = $this->encrypt->decode($this->com_user['user_pass'], $this->com_user['user_key']);
        if ($password_decode <> md5($password_old)) {
            $this->tnotification->set_error_message('Password yang lama tidak cocok.');
        }
        // validate password confirmation
        $password_new = trim($this->input->post('user_pass', TRUE));
        $password_confirm = trim($this->input->post('confirm_user_pass', TRUE));
        if ($password_new <> $password_confirm) {
            $this->tnotification->set_error_message('Konfirmasi Password Baru Tidak Sesuai!');
        }
        // process        
        if ($this->tnotification->run() !== FALSE) {
            // password
            $password_key = abs(crc32($this->input->post('user_pass', TRUE)));
            $user_pass = $this->encrypt->encode(md5($this->input->post('user_pass', TRUE)), $password_key);
            // params
            $params = array(
                'user_pass' => $user_pass,
                'user_key' => $password_key,
            );
            // where
            $where = array(
                'user_id' => $this->com_user['user_id']
            );
            // update data
            if ($this->m_account->update_user($params, $where)) {
                // send email
                $send_mail = trim($this->input->post('send_mail', TRUE));
                // check mail
                if ($send_mail == 'yes') {
                    // send email
                    // config
                    $email_params['to'] = $this->com_user['user_mail'];
                    $email_params['cc'] = array();
                    $email_params['subject'] = 'Ganti Password, PT. Time Excelindo Management Tools';
                    $email_params['message']['title'] = 'Password telah diperbaharui';
                    $email_params['message']['greetings'] = 'Hi ' . $this->com_user['nama_lengkap'] . ',';
                    $email_params['message']['intro'] = 'Anda berhasil melakukan perubahan password pada user account anda. Berikut ini adalah password baru anda : ';
                    // detail
                    $message = '<table cellspacing="0" cellpadding="0" border="0">';
                    $message .= '<tbody>';
                    $message .= '<tr>';
                    $message .= '<td style="width: 100px;">Password</td>';
                    $message .= '<td><b>' . $password_confirm . '</b></td>';
                    $message .= '</tr>';
                    $message .= '</tbody>';
                    $message .= '</table>';
                    // --
                    $email_params['message']['details'] = $message;
                    $email_params['attachments'] = array();
                    // set email parameters
                    $this->m_email->set_mail($email_params);
                    // send
                    if ($this->m_email->send_mail('01')) {
                        $this->tnotification->set_error_message('Email telah dikirim!');
                    } else {
                        $this->tnotification->set_error_message('Email gagal dikirim, periksa lagi email anda!');
                    }
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
            $this->tnotification->sent_notification("error", "Data gagal diubah");
        }
        // default redirect
        redirect("home/welcome/profile/account_settings");
    }

    // edit foto process
    public function edit_foto_process()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('page', 'Halaman', 'trim|required');
        // page
        $page = $this->input->post('page', TRUE);
        // process
        if ($this->tnotification->run() !== FALSE) {
            // upload
            if (!empty($_FILES['user_img_upload']['tmp_name'])) {
                // upload config
                $config['upload_path'] = 'resource/doc/images/users/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['file_name'] = $this->com_user['user_id'] . '_' . date('Ymdhis');
                // --
                $this->tupload->initialize($config);
                // process upload images
                if ($this->tupload->do_upload_image('user_img_upload', 128, FALSE)) {
                    // --
                    $data = $this->tupload->data();
                    // --
                    $params = array(
                        'user_img_name' => $data['file_name'],
                        'user_img_path' => 'resource/doc/images/users/'
                    );
                    $where = array(
                        'user_id' => $this->com_user['user_id']
                    );
                    $this->m_account->update_user($params, $where);
                    // hapus foto lama
                    $this->tnotification->sent_notification("success", "Foto profil berhasil diupdate.");
                } else {
                    // jika gagal
                    $this->tnotification->set_error_message($this->tupload->display_errors());
                    $this->tnotification->sent_notification("error", "Foto profil gagal disimpan.");
                }
            } else {
                $this->tnotification->sent_notification("error", "Foto profil gagal disimpan.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal diubah");
        }
        // redirect
        if ($page == 'profil') {
            redirect("home/welcome/profile");
        } else {
            redirect("home/welcome/profile/account_settings");
        }
    }

    // ajax upload
    public function prosse_upload()
    {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        //  $this->tnotification->set_rules('files_id', 'ID Files', 'trim|required');
        $this->tnotification->set_rules('pegawai_files_id', 'Pegawai Files', 'trim|required');
        $this->tnotification->set_rules('file_no', 'Nomor Dokumen', 'trim|required');
        // params
        //  $files_id = $this->input->post('files_id', TRUE);
        $pegawai_files_id = $this->input->post('pegawai_files_id', TRUE);
        $file_no = $this->input->post('file_no', TRUE);
        // process
        if ($this->tnotification->run() !== FALSE) {
            $files_id = $this->m_profile->generate_files_id();


            $pegawai_files = $this->m_profile->get_files_master_by_id(array($pegawai_files_id));
            
            // upload
            if (!empty($_FILES['file']['tmp_name'])) {

                // upload config
                $config['files'] = 'file';
                $config['upload_path'] = 'resource/doc/images/karyawan/';
                $config['upload']['allowed_types'] = $pegawai_files['file_allowed'];
                $config['upload']['max_size'] = $pegawai_files['file_size'];
                $config['file_name'] = strtoupper($pegawai_files['file_field']);

                // --
                $this->tupload->initialize($config);

                // process upload images
                $this->load->library('upload', $config);
                // $this->upload->do_upload('file');
                // if ($this->tupload->do_upload_image('file', 128, FALSE)) {
                if ($this->upload->do_upload('file')) {

                    // delete terlebih dahulu

                    // $where = array(
                    //     'files_id' => $files_id,
                    //     'pegawai_files_id' => $pegawai_files['pegawai_files_id'],
                    // );
                    // $this->m_profile->delete_attachment($where);
                    // --
                    $data = $this->upload->data();
                    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $size = $_FILES['file']['size'];

                    // params
                    $params = array(
                        'files_id' => $files_id,
                        'pegawai_files_id' => $pegawai_files_id,
                        'file_no' => $file_no,
                        'file_name' => $data['file_name'],
                        'file_path' => 'resource/doc/images/karyawan/' . $this->com_user['user_id'],
                        'file_size' => $size,
                        'file_ext' => $ext,
                        'mdb' => $this->com_user['user_id'],
                        'mdb_name' => $this->com_user['nama_lengkap'],
                        'mdd' => date('Y-m-d H:i:s'),
                    );
                    $this->m_profile->insert_attachment($params);
                    $result = array(
                        'message' => $pegawai_files['file_title'],
                        'url' => site_url('home/welcome/profile/download/' . $data['file_name']),
                    );
                } else {
                    // jika gagal
                    $result = array(
                        'message' => 'File yang diupload tidak memenuhi persyaratan [ kapasitas, jenis file ]',
                        'url' => '#',
                    );
                }
            } else {

                $result = array(
                    'message' => 'Data yang anda inputkan tidak lengkap, periksa lagi inputan anda! Ex: Nomor Dokumen',
                    'url' => '#',
                );
            }
        }
        // encode
        echo json_encode($result);
        exit();
    }


    // download files
    public function download($file_name)
    {

        $this->load->helper('download');

        force_download('resource/doc/images/karyawan/' . $this->com_user['user_id'] . '/' . $file_name, NULL);
    }
}
