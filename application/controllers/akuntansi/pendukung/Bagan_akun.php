<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class Bagan_akun extends ApplicationBase {
	// constructor
    public function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('akuntansi/pendukung/M_bagan_akun');
        // load library
        $this->load->library('pagination');
        $this->load->library('tnotification');
    }

    public function index() {
    	// set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/pendukung/bagan_akun/list.html");
        // search
        $search = $this->tsession->userdata("akun_search");
        // search parameters
        $nama_akun = empty($search['nama_akun']) ? '%' : '%' . $search['nama_akun'] . '%';
        if (!empty($search)) {
            $this->smarty->assign("search", $search);
        }

         /* start of pagination --------------------- */
        // pagination
        $config['base_url'] = site_url("akuntansi/pendukung/bagan_akun/index/");
        $params = $nama_akun;
        $config['total_rows'] = $this->M_bagan_akun->get_total_akun($params);
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
        $params = array($nama_akun, ($start - 1), $config['per_page']);
        // get data akun
        $this->smarty->assign("rs_id", $this->M_bagan_akun->get_all_data_akun($params));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // searching
    public function proses_cari() {
        //set page rules
        $this->_set_page_rule("R");
        //data
        if ($this->input->post('save') == "Reset") {
            $this->tsession->unset_userdata("akun_search");
        } else {
            $params = array(
                "nama_akun" => $this->input->post("nama_akun")
            );
            $this->tsession->set_userdata("akun_search", $params);
        }
        redirect("akuntansi/pendukung/bagan_akun");
    }

    // add data akun
    public function add(){
        // set page rule
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/pendukung/bagan_akun/add.html");
         // list data akun
        $this->smarty->assign("rs_id", $this->M_bagan_akun->get_all_data());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // add process data akun
    public function add_process() {
    	// set page rule
        $this->_set_page_rule("C");
        // validasi input
        $this->tnotification->set_rules('level_akun', 'Level Akun', 'trim|required');
        if ($this->input->post('level_akun') != 1) {
        	$this->tnotification->set_rules('induk_akun', 'Induk Bagan Akun', 'trim|required');
        }
        $this->tnotification->set_rules('nama_akun', 'Penjelasan', 'required');
        $this->tnotification->set_rules('penjelasan', 'Penjelasan', 'required');


        // process
        if ($this->tnotification->run() !== false) {
		    // ambil inputan
		    $level_akun_input = $this->input->post('level_akun');
		    $induk_akun_input = $this->input->post('induk_akun');

	        // proses level 1
	        if ($level_akun_input == 1) {
	        	// cek apakah level_akun sudah ada atau belum
	        	if ($this->M_bagan_akun->check_if_level_akun_exists($level_akun_input)) {
		        	// mengambil kode_akun terakhir by level
		        	$last_kode_akun = $this->M_bagan_akun->get_last_kode_akun_by_level($level_akun_input);
		        	// mengambil 1 digit pertama kode_akun
		        	$satu_digit_pertama = substr($last_kode_akun[0]['kode_akun'], 0, 1) + 1;
		        	// kode_akun yang akan diinsert
		        	$kode_akun_baru = $satu_digit_pertama."0000000";
		        } else {
		        	$kode_akun_baru = "10000000";
		        }
	        // proses level 2
	        } else if ($level_akun_input == 2) {
	        	// params
	        	$params = [1, 1, $induk_akun_input, $level_akun_input];
	        	// get last kode_akun by input induk_akun
	    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

	    		// cek nomor urut kode_akun sudah ada atau tidak
	    		if (empty($cek)) {
	    			$nomor_urut = $induk_akun_input."1";
	    		} else {
		        	// set nomor urut
		        	$nomor_urut = substr($cek[0]["kode_akun"],0, 2)+1;
	    		}

	        	// kode akun baru yang akan diinsert
	        	$kode_akun_baru = $nomor_urut."000000";

	        // proses level 3
	        } else if ($level_akun_input == 3) {
	        	// params
	        	$params = [1, 2, $induk_akun_input, $level_akun_input];
	        	// get last kode_akun by input induk_akun
	    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

	    		// cek nomor urut kode_akun sudah ada atau tidak
	    		if (empty($cek)) {
	    			$nomor_urut = $induk_akun_input."01";
	    		} else {
		        	// set nomor urut
		        	$nomor_urut = substr($cek[0]["kode_akun"],0, 4)+1;
	    		}

	        	// kode akun baru yang akan diinsert
	        	$kode_akun_baru = $nomor_urut."0000";
	        // proses level 4
	        } else if ($level_akun_input == 4) {
	        	// params
	        	$params = [1, 4, $induk_akun_input, $level_akun_input];
	        	// get last kode_akun by input induk_akun
	    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

	    		// cek nomor urut kode_akun sudah ada atau tidak
	    		if (empty($cek)) {
	    			$nomor_urut = $induk_akun_input."01";
	    		} else {
		        	// set nomor urut
		        	$nomor_urut = substr($cek[0]["kode_akun"],0, 6)+1;
	    		}

	        	// kode akun baru yang akan diinsert
	        	$kode_akun_baru = $nomor_urut."00";
	        // proses level 5
	        } else if ($level_akun_input == 5) {
	        	// params
	        	$params = [1, 6, $induk_akun_input, $level_akun_input];
	        	// get last kode_akun by input induk_akun
	    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

	    		// cek nomor urut kode_akun sudah ada atau tidak
	    		if (empty($cek)) {
	    			$nomor_urut = $induk_akun_input."01";
	    		} else {
		        	// set nomor urut
		        	$nomor_urut = substr($cek[0]["kode_akun"],0, 8)+1;
	    		}

	        	// kode akun baru yang akan diinsert
	        	$kode_akun_baru = $nomor_urut;
	        // proses jika level tidak valid
	        } else {
	        	// notification error
		        $this->tnotification->sent_notification("error", "Level yang anda masukkan tidak valid");
		        // redirect
		        redirect("akuntansi/pendukung/bagan_akun/add");
	        }

	        $params = [
	           	'kode_akun' => $kode_akun_baru,
	           	'nama_akun' => $this->input->post('nama_akun'), 
	           	'level_akun' => $this->input->post('level_akun'),
	           	'penjelasan' => $this->input->post('penjelasan'),
	           	'mdb' => $this->com_user['user_id'],
	            'mdb_name' => $this->com_user['user_alias'],
	            'mdd' => date('Y-m-d H:i:s')
	        ];

	        // insert
	        if ($this->M_bagan_akun->insert($params)) {
	         	// notification success
	            $this->tnotification->delete_last_field();
	            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
	            // redirect
	            redirect("akuntansi/pendukung/bagan_akun");
	        } else {
	         	// notification error
	            $this->tnotification->sent_notification("error", "Data gagal disimpan");
	            // redirect
	            redirect("akuntansi/pendukung/bagan_akun/add");
	        }
        } else {
        	// notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // redirect
	        redirect("akuntansi/pendukung/bagan_akun/add");
        }
    }

    // edit data akun
    public function edit($kode_akun = "") {
    	// set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/pendukung/bagan_akun/edit.html");
        // get data akun by kode_akun
        $detail = $this->M_bagan_akun->get_detail_data_akun($kode_akun);
        // cek data kosong atau tidak
        if (empty($detail)) {
            // notification error
            $this->tnotification->sent_notification("error", "Data tidak ditemukan!");
            redirect("akuntansi/pendukung/bagan_akun");
        }
        // parsing data
        $this->smarty->assign("result", $detail);
        // get all data akun
        $detail2 = $this->M_bagan_akun->get_data_akun_by_level($detail["level_akun"]-1);
        $this->smarty->assign("rs_id", $detail2);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // edit process data akun
    public function edit_process() {
    	// set page rule
        $this->_set_page_rule("U");
        // validasi input
        $this->tnotification->set_rules('kode_akun', 'Kode Akun', 'trim|required');
        $this->tnotification->set_rules('level_akun', 'Level Akun', 'trim|required');
        if ($this->input->post('level_akun') != 1) {
        	$this->tnotification->set_rules('induk_akun', 'Induk Bagan Akun', 'trim|required');
        }
        $this->tnotification->set_rules('nama_akun', 'Penjelasan', 'required');
        $this->tnotification->set_rules('penjelasan', 'Penjelasan', 'required');


        // process
        if ($this->tnotification->run() !== false) {
        	// ambil input
        	$kode_akun_input = $this->input->post('kode_akun');
        	$induk_akun_old_input = $this->input->post('induk_akun_old');
        	$level_akun_input = $this->input->post('level_akun');

        	// cek level_akun input
        	if ($level_akun_input == 1) {
        		$induk_akun_input = $this->input->post('induk_akun');
        	} else if ($level_akun_input == 2) {
        		$induk_akun_input = substr($this->input->post('induk_akun'), 0, 1);
        	} else if ($level_akun_input == 3) {
        		$induk_akun_input = substr($this->input->post('induk_akun'), 0, 2);
        	} else if ($level_akun_input == 4) {
        		$induk_akun_input = substr($this->input->post('induk_akun'), 0, 4);
        	} else if ($level_akun_input == 5) {
        		$induk_akun_input = substr($this->input->post('induk_akun'), 0, 6);
        	}

        	// cek apakah level diganti atau tidK
        	if ($level_akun_input !== $this->M_bagan_akun->get_detail_data_akun($kode_akun_input)["level_akun"]) {
        		// default error
	            $this->tnotification->sent_notification("error", "Level Akun tidak bisa diubah!");
	            // redirect
	      		redirect("akuntansi/pendukung/bagan_akun/edit/".$this->input->post('kode_akun'));
        	} else if ($induk_akun_old_input !== $induk_akun_input) {
        		// proses level 1
		        if ($level_akun_input == 1) {
		        	// cek apakah level_akun sudah ada atau belum
		        	if ($this->M_bagan_akun->check_if_level_akun_exists($level_akun_input)) {
			        	// mengambil kode_akun terakhir by level
			        	$last_kode_akun = $this->M_bagan_akun->get_last_kode_akun_by_level($level_akun_input);
			        	// mengambil 1 digit pertama kode_akun
			        	$satu_digit_pertama = substr($last_kode_akun[0]['kode_akun'], 0, 1) + 1;
			        	// kode_akun yang akan diinsert
			        	$kode_akun_baru = $satu_digit_pertama."0000000";
			        } else {
			        	$kode_akun_baru = "10000000";
			        }
		        // proses level 2
		        } else if ($level_akun_input == 2) {
		        	// params
		        	$params = [1, 1, $induk_akun_input, $level_akun_input];
		        	// get last kode_akun by input induk_akun
		    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

		    		// cek nomor urut kode_akun sudah ada atau tidak
		    		if (empty($cek)) {
		    			$nomor_urut = $induk_akun_input."1";
		    		} else {
			        	// set nomor urut
			        	$nomor_urut = substr($cek[0]["kode_akun"],0, 2)+1;
		    		}

		        	// kode akun baru yang akan diinsert
		        	$kode_akun_baru = $nomor_urut."000000";

		        // proses level 3
		        } else if ($level_akun_input == 3) {
		        	// params
		        	$params = [1, 2, $induk_akun_input, $level_akun_input];
		        	// get last kode_akun by input induk_akun
		    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

		    		// cek nomor urut kode_akun sudah ada atau tidak
		    		if (empty($cek)) {
		    			$nomor_urut = $induk_akun_input."01";
		    		} else {
			        	// set nomor urut
			        	$nomor_urut = substr($cek[0]["kode_akun"],0, 4)+1;
		    		}

		        	// kode akun baru yang akan diinsert
		        	$kode_akun_baru = $nomor_urut."0000";
		        // proses level 4
		        } else if ($level_akun_input == 4) {
		        	// params
		        	$params = [1, 4, $induk_akun_input, $level_akun_input];
		        	// get last kode_akun by input induk_akun
		    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

		    		// cek nomor urut kode_akun sudah ada atau tidak
		    		if (empty($cek)) {
		    			$nomor_urut = $induk_akun_input."01";
		    		} else {
			        	// set nomor urut
			        	$nomor_urut = substr($cek[0]["kode_akun"],0, 6)+1;
		    		}

		        	// kode akun baru yang akan diinsert
		        	$kode_akun_baru = $nomor_urut."00";
		        // proses level 5
		        } else if ($level_akun_input == 5) {
		        	// params
		        	$params = [1, 6, $induk_akun_input, $level_akun_input];
		        	// get last kode_akun by input induk_akun
		    		$cek = $this->M_bagan_akun->get_last_kode_akun_by_substr($params);

		    		// cek nomor urut kode_akun sudah ada atau tidak
		    		if (empty($cek)) {
		    			$nomor_urut = $induk_akun_input."01";
		    		} else {
			        	// set nomor urut
			        	$nomor_urut = substr($cek[0]["kode_akun"],0, 8)+1;
		    		}

		        	// kode akun baru yang akan diinsert
		        	$kode_akun_baru = $nomor_urut;
		        // proses jika level tidak valid
		        } else {
		        	// notification error
			        $this->tnotification->sent_notification("error", "Level yang anda masukkan tidak valid");
			        // redirect
			        redirect("akuntansi/pendukung/bagan_akun/edit".$this->input->post('kode_akun'));
		        }

		        $params = [
		           	'kode_akun' => $kode_akun_baru,
		           	'nama_akun' => $this->input->post('nama_akun'), 
		           	'level_akun' => $this->input->post('level_akun'),
		           	'penjelasan' => $this->input->post('penjelasan'),
		           	'mdb' => $this->com_user['user_id'],
		            'mdb_name' => $this->com_user['user_alias'],
		            'mdd' => date('Y-m-d H:i:s')
		        ];

		        // insert
		        if ($this->M_bagan_akun->insert($params)) {
		        	// params untuk hapus data lama
		        	$params = [
		        		'kode_akun' => $kode_akun_input
		        	];

		        	// hapus data lama
		        	if ($this->M_bagan_akun->delete($params)) {
			         	// notification success
			            $this->tnotification->delete_last_field();
			            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
			            // redirect
			            redirect("akuntansi/pendukung/bagan_akun");
		        	} else {
		        		// notification error
		            	$this->tnotification->sent_notification("error", "Data gagal disimpan");
		            	// redirect
		            	redirect("akuntansi/pendukung/bagan_akun/edit/".$this->input->post('kode_akun'));
		        	}
		        } else {
		         	// notification error
		            $this->tnotification->sent_notification("error", "Data gagal disimpan");
		            // redirect
		            redirect("akuntansi/pendukung/bagan_akun/add".$this->input->post('kode_akun'));
		        }
        	} else {
	        	$params = [
	        		'nama_akun' => $this->input->post('nama_akun'),
	        		'level_akun' => $this->input->post('level_akun'),
	        		'penjelasan' => $this->input->post('penjelasan'),
	        		'mdb' => $this->com_user['user_id'],
		            'mdb_name' => $this->com_user['user_alias'],
		            'mdd' => date('Y-m-d H:i:s')
	        	];

	        	$where = [
	        		'kode_akun' => $this->input->post('kode_akun')
	        	];

	        	// update
		        if ($this->M_bagan_akun->update($params, $where)) {
		            // notification
		            $this->tnotification->delete_last_field();
		            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
		            // redirect
		      		redirect("akuntansi/pendukung/bagan_akun");
		        } else {
		            // default error
		            $this->tnotification->sent_notification("error", "Data gagal disimpan");
		            // redirect
		      		redirect("akuntansi/pendukung/bagan_akun/edit/".$this->input->post('kode_akun'));
	        	}
        	}
        } else {
        	// notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // redirect
	        redirect("akuntansi/pendukung/bagan_akun/edit/".$this->input->post('kode_akun'));
        }
    }

    // hapus data akun
    public function delete($kode_akun = "") {
    	// set page rules
        $this->_set_page_rule("D");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/pendukung/bagan_akun/delete.html");
        // get data akun by kode_akun
        $detail = $this->M_bagan_akun->get_detail_data_akun($kode_akun);
        // cek data kosong atau tidak
        if (empty($detail)) {
            // notification error
            $this->tnotification->sent_notification("error", "Data tidak ditemukan!");
            redirect("akuntansi/pendukung/bagan_akun");
        }
        // parsing data
        $this->smarty->assign("result", $detail);
        // get all data akun by level
        $detail2 = $this->M_bagan_akun->get_data_akun_by_level($detail["level_akun"]-1);
        $this->smarty->assign("rs_id", $detail2);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // hapus process
    public function delete_process() {
    	// set page rules
        $this->_set_page_rule("D");
        // validasi input
        $this->tnotification->set_rules('kode_akun', 'Kode Akun', 'trim|required');

        // process
        if ($this->tnotification->run() !== false) {
            $params = [
            	'kode_akun' => $this->input->post('kode_akun')
            ];

            // delete
            if ($this->M_bagan_akun->delete($params)) {
            	// notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
        		redirect("akuntansi/pendukung/bagan_akun");
            } else {
            	// notification error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
                // default redirect
        		redirect("akuntansi/pendukung/bagan_akun/".$this->input->post('kode_akun'));
            }
        } else {
			// notification error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            // default redirect
        	redirect("akuntansi/pendukung/bagan_akun/".$this->input->post('kode_akun'));
        }
    }

    // get data by form select name=level_akun
    public function get_data_by_form_select_level() {
    	// ambil inputan user
    	$level_input = $this->input->post('level_akun');

    	// query
    	$this->db->select('kode_akun, nama_akun');
    	$this->db->from('data_akun');
    	$this->db->where('level_akun', $level_input-1);
    	$this->db->order_by('kode_akun', 'ASC');

    	// run query
    	$query = $this->db->get()->result();

    	// tampilkan dalam bentuk json
    	echo json_encode($query);
    }

    // detail data akun perusahaan
    public function detail($kode_akun = "") {
    	// set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "akuntansi/pendukung/bagan_akun/detail/list.html");
        // get data akun perusahaan
        $this->smarty->assign("rs_id", $this->M_bagan_akun->get_detail_akun_perusahaan($kode_akun));
        // get all data struktur
        $this->smarty->assign("data", $this->M_bagan_akun->get_all_data_struktur());
        // get kode akun
        $this->smarty->assign("kode_akun", $kode_akun);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah data akun perusahaan
    public function add_perusahaan_process() {
    	// set page rule
        $this->_set_page_rule("C");
        // validasi input
        $this->tnotification->set_rules('kode_akun', 'Kode Bagan Akun', 'trim|required');
        $this->tnotification->set_rules('struktur_cd[]', 'Kode Induk Bagan Akun', 'trim|required');

        // process
        if ($this->tnotification->run() !== false) {
        	// ambil input
        	$kode_akun_input = $this->input->post('kode_akun');
        	$struktur_cd_input = $this->input->post('struktur_cd[]');

        	for ($i = 0; $i < count($struktur_cd_input); $i++) {
        		$params = [$kode_akun_input, $struktur_cd_input[$i]];

        		if($this->M_bagan_akun->check_if_struktur_cd_exists($params)) {
        			// notification error
		            $this->tnotification->sent_notification("error", "Pilihan Perusahaan sudah ada");
		            // default redirect
		        	redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
        		}
        	}

        	for ($i = 0; $i < count($struktur_cd_input); $i++) { 
	        	// ambil data_id terakhir
	        	$last_data_id = $this->M_bagan_akun->get_last_data_id()[0]["data_id"];

	        	// cek apakah data_id sudah ata atau belum
	        	if (empty($last_data_id)) {
	        		$data_id_baru = date("Y")."000001";
	        	} else {
	        		$data_id_baru = $last_data_id+1;
	        	}

	        	$params = [
	        		'data_id' => $data_id_baru,
	        		'kode_akun' => $this->input->post('kode_akun'),
	        		'struktur_cd' => $this->input->post('struktur_cd')[$i]
	        	];

	        	$this->M_bagan_akun->insert_perusahaan($params);
        	}
        	// notification
		    $this->tnotification->delete_last_field();
		    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
		    // redirect
		    redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
        } else {
        	// notification error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
            // default redirect
        	redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
        }
    }

    public function delete_perusahaan_process() {
    	// set page rule
        $this->_set_page_rule("U");
        // validasi input
        $this->tnotification->set_rules('data_id', 'Data ID', 'trim|required');

        // process
        if ($this->tnotification->run() !== false) {
            $params = [
            	'data_id' => $this->input->post('data_id')
            ];

            // delete
            if ($this->M_bagan_akun->delete_perusahaan($params)) {
            	// notification success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
                // default redirect
        		redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
            } else {
            	// notification error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
                // default redirect
        		redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
            }
        } else {
			// notification error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
            // default redirect
        	redirect("akuntansi/pendukung/bagan_akun/detail/".$this->input->post('kode_akun'));
        }
    }
}