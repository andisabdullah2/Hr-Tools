<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// load base class if needed
require_once APPPATH . 'controllers/base/LoginBase.php';

// --

class welcome extends ApplicationBase {

    // constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('m_email');
        // load notification
        $this->load->library('tnotification');
        // load helper
        $this->load->helper("captcha");
    }

    // login form
    public function index() {
        // set template content
        $this->smarty->assign("template_content", "login/welcome/form.html");
        // load helper
        $this->load->helper("captcha");
        // unset session
        $this->tsession->unset_userdata('session_id');
        // clear captcha
        $captcha_data = $this->tsession->userdata('captcha_data');
        if (isset($captcha_data['captcha_time'])) {
            $capctha_path = 'resource/doc/captcha/' . $captcha_data['captcha_time'] . '.jpg';
            if (is_file($capctha_path)) {
                unlink($capctha_path);
            }
        }
        // set captcha
        $vals = array(
            'img_path' => FCPATH . '/resource/doc/captcha/',
            'img_url' => base_url() . '/resource/doc' . '/captcha/',
            'font_path' => FCPATH . '/resource/doc/font/COURIER.TTF',
            'font_size' => 96,
            'pool' => '0123456789',
			'word_length' => 4,
            'img_height' => 32,
            'img_width' => 96,
            'expiration' => 7200,
        );
        $captcha = create_captcha($vals);
        $captcha_data = array(
            // 'captcha_time' => $captcha['time'],
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            // 'word' => $captcha['word'],
        );
        $this->tsession->set_userdata("captcha_data", $captcha_data);
        // assign captcha
        $this->smarty->assign("captcha", $captcha);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // reload captcha
    public function ajax_reload_captcha() {
        // header
        header('Content-Type: application/json');
        // clear captcha
        $captcha_data = $this->tsession->userdata('captcha_data');
        if (isset($captcha_data['captcha_time'])) {
            $capctha_path = 'resource/doc/captcha/' . $captcha_data['captcha_time'] . '.jpg';
            if (is_file($capctha_path)) {
                unlink($capctha_path);
            }
        }
        // set captcha
        $vals = array(
            'img_path' => FCPATH . '/resource/doc/captcha/',
            'img_url' => base_url() . '/resource/doc' . '/captcha/',
            'font_path' => FCPATH . '/resource/doc/font/COURIER.TTF',
            'font_size' => 96,
            'img_height' => 32,
            'img_width' => 96,
            'expiration' => 7200,
        );
        $captcha = create_captcha($vals);
        $captcha_data = array(
            'captcha_time' => $captcha['time'],
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'word' => $captcha['word'],
        );
        $this->tsession->set_userdata("captcha_data", $captcha_data);
        // encode
        echo json_encode($captcha);
        exit();
    }

    // login process
    public function login_process() {
        // cek input
        $this->tnotification->set_rules('username', 'Username', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('password', 'Password', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('captcha', 'Kode Captcha', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // captcha
            $captcha = trim($this->input->post('captcha', true));
            $captcha_data = $this->tsession->userdata('captcha_data');
            $expiration = time() - 7200;
            if ($captcha_data['word'] == $captcha and $captcha_data['ip_address'] == $_SERVER["REMOTE_ADDR"] and $captcha_data['captcha_time'] > $expiration OR true) {
                
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Kode Captcha tidak sesuai!");
                // output
                redirect('login/welcome');
            }
            // params
            $username = trim($this->input->post('username', true));
            $password = trim($this->input->post('password', true));
            // get user detail
            $result = $this->m_account->get_user_login_all_roles($username, $password, $this->portal_id);
            // print_r($result);die;
            // check
            if (!empty($result)) {
                // cek lock status
                if ($result['user_st'] == '0') {
                    // default error
                    $this->tnotification->sent_notification("error", "Status pengguna anda telah di non aktifkan, hubungi administrator sistem.");
                    // output
                    redirect('login/welcome');
                }
                // set session
                $this->tsession->set_userdata('session_id', array(
                    'user_id' => $result['user_id'],
                    'role_id' => $result['role_id'],
                    'role_nm' => $result['role_nm'],
                    'default_page' => $result['default_page'],
                ));
                // insert login time
                $this->m_account->save_user_login($result['user_id'], $_SERVER['REMOTE_ADDR']);
                // redirect
                redirect($result['default_page']);
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Account anda tidak ditemukan.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Isikan username dan password anda.");
        }
        // output
        redirect('login/welcome');
    }

    // logout process
    public function logout_process() {
        // user id
        $user_id = $this->tsession->userdata('session_id');
        // insert logout time
        $this->m_account->update_user_logout($user_id);
        // unset session
        $this->tsession->unset_userdata('session_id');
        // output
        redirect('login/welcome');
    }

    // lupa password
    public function lupa_password() {
        // set template content
        $this->smarty->assign("template_content", "login/welcome/lupa_password.html");

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // lupa password process
    public function lupa_password_process() {
        // cek input
        $this->tnotification->set_rules('user_mail', 'Email', 'trim|required|max_length[50]');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // params
            $user_mail = trim($this->input->post('user_mail', TRUE));
            // get user by mail
            $result = $this->m_account->get_pegawai_by_email($user_mail);
            if ($result) {
                // reset password
                $time = microtime(true);
                $data_id = str_replace('.', '', $time);
                $request_key = $this->m_account->rand_password(50);
                $params = array(
                    'data_id' => $data_id,
                    'email' => $user_mail,
                    'nama_lengkap' => $result['nama_lengkap'],
                    'nomor_telepon' => $result['nomor_telepon'],
                    'request_st' => 'waiting',
                    'request_date' => date('Y-m-d H:i:s'),
                    'request_key' => $request_key,
                );
                // insert
                if ($this->m_account->insert_reset($params)) {
                    // config
                    $email_params['to'] = $user_mail;
                    $email_params['cc'] = array();
                    $email_params['subject'] = 'Reset Password, PT. Time Excelindo Manajemen Tools';
                    $email_params['message']['title'] = 'Permintaan untuk reset password';
                    $email_params['message']['greetings'] = 'Hi ' . $result['nama_lengkap'] . ',';
                    $email_params['message']['intro'] = 'Anda ingin melakukan reset password pada aplikasi ini. Gunakan link berikut ini dan ikuti langkah selanjutnya : ';
                    $email_params['message']['details'] = '<a href="' . site_url('login/welcome/reset_password/' . md5($request_key)) . '">Ya, Saya akan melakukan reset password.</a>';
                    $email_params['attachments'] = array();
                    // set email parameters
                    $this->m_email->set_mail($email_params);
                    // send
                    if ($this->m_email->send_mail('01')) {
                        // default success
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Link reset password telah dikirimkan.");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Mohon maaf, email gagal dikirimkan.");
                    }
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Mohon maaf, permintaan anda gagal diproses.");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Mohon maaf, email anda tidak terdaftar.");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Isikan Email anda.");
        }
        // output
        redirect('login/welcome/lupa_password');
    }

    // proses reset
    public function reset_password($id_encoded = '') {
        // get request
        $result = $this->m_account->get_reset_data_by_id(array($id_encoded));
        if (empty($result)) {
            // default error
            $this->tnotification->sent_notification("error", "Permintaan reset password anda sudah kadaluarsa!");
            // default redirect
            redirect("login/welcome");
        } else {
            // get detail
            $detail = $this->m_account->get_pegawai_by_email(array($result['email']));
            if (empty($detail)) {
                // default error
                $this->tnotification->sent_notification("error", "Maaf, permintaan anda tidak dapat diproses. User Account yang anda minta tidak terdaftar!");
                // default redirect
                redirect("login/welcome");
            } else {
                // generate new password
                $new_password = $this->m_account->rand_password();
                $password_key = abs(crc32($new_password));
                $password = $this->encrypt->encode(md5($new_password), $password_key);
                // params
                $params = array(
                    'user_key' => $password_key,
                    'user_pass' => $password,
                );
                $where = array(
                    'user_id' => $detail['user_id'],
                );
                if ($this->m_account->update_user($params, $where)) {
                    // update request
                    $params = array(
                        'request_st' => 'done',
                        'response_by' => 'BY EMAIL',
                        'response_date' => date('Y-m-d H:i:s'),
                    );
                    $where = array(
                        'data_id' => $result['data_id'],
                    );
                    $this->m_account->update_reset($params, $where);
                    // send email
                    // config
                    $email_params['to'] = $detail['user_mail'];
                    $email_params['cc'] = array();
                    $email_params['subject'] = 'Reset Password, PT. Time Excelindo Management Tools';
                    $email_params['message']['title'] = 'Password telah diperbaharui';
                    $email_params['message']['greetings'] = 'Hi ' . $detail['nama_lengkap'] . ',';
                    $email_params['message']['intro'] = 'Anda berhasil melakukan reset password pada user account anda. Berikut ini adalah password baru anda : ';
                    // detail
                    $message = '<table cellspacing="0" cellpadding="0" border="0">';
                    $message .= '<tbody>';
                    $message .= '<tr>';
                    $message .= '<td style="width: 100px;">Password</td>';
                    $message .= '<td><b>' . $new_password . '</b></td>';
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
                    // notification
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Password berhasil direset!");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Maaf, data tidak dapat diproses. Ulangi reset dan akitifasi pada email anda!");
                }
            }
        }
        // default redirect
        redirect("login/welcome");
    }

}
