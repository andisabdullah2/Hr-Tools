<?php

class m_pegawai extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    //get total pegawai
    function get_total_pegawai($params) {
        $sql = "SELECT COUNT(*) 'total' FROM pegawai
                WHERE nama_lengkap LIKE ? AND struktur_cd LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get all pegawai
    function get_all_pegawai($params) {
        $sql = "SELECT a.*,b.struktur_nama,c.user_mail 
                FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                LEFT JOIN com_user c ON a.user_id = c.user_id
                WHERE a.nama_lengkap LIKE ? AND a.struktur_cd LIKE ?
                ORDER BY a.nama_lengkap ASC 
                LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail user
    function get_pegawai_detail_by_id($params) {
        $sql = "SELECT a.*, b.`struktur_nama`, c.user_mail, d.jabatan_nama AS 'jabatan_struktural', e.jabatan_nama AS 'jabatan_fungsional',
                IFNULL(d.`jabatan_nama`, e.`jabatan_nama`) AS 'jabatan'
                FROM pegawai a
                LEFT JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                LEFT JOIN com_user c ON a.user_id = c.user_id
                LEFT JOIN data_jabatan_struktural d ON a.jabatan_struktural_id = d.jabatan_struktural_id
                LEFT JOIN (
			SELECT e.user_id, f.jabatan_nama
			FROM pegawai_jabatan_fungsional e
			LEFT JOIN data_jabatan_fungsional f ON e.`jabatan_fungsional_id` = f.`jabatan_fungsional_id`
			WHERE e.`jabatan_status` = '1' AND e.`jabatan_default` = '1'
		) e ON a.user_id = e.user_id
                WHERE a.user_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get user account info
    function get_pegawai_account_by_id($params) {
        $sql = "SELECT a.user_id, a.nama_lengkap, c.user_mail, c.user_alias, c.user_name, c.user_pass, c.user_st 
                FROM pegawai a
                LEFT JOIN com_user c ON a.user_id = c.user_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_struktural_by_id($params) {
        $sql = "SELECT c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.*,d.struktur_nama 
                FROM pegawai_jabatan_struktural a 
                INNER JOIN data_jabatan_struktural b ON b.jabatan_struktural_id = a.jabatan_struktural_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                INNER JOIN data_struktur_organisasi d ON d.struktur_cd = b.struktur_cd
                WHERE a.user_id = ?
                ORDER BY a.tanggal_sk DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_struktural_active_by_id($params) {
        $sql = "SELECT c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.*,d.struktur_nama 
                FROM pegawai_jabatan_struktural a 
                INNER JOIN data_jabatan_struktural b ON b.jabatan_struktural_id = a.jabatan_struktural_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                INNER JOIN data_struktur_organisasi d ON d.struktur_cd = b.struktur_cd                
                WHERE a.user_id = ? AND a.jabatan_status = '1' AND jabatan_default = '1'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_fungsional_by_id($params) {
        $sql = "SELECT c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.* 
                FROM pegawai_jabatan_fungsional a 
                INNER JOIN data_jabatan_fungsional b ON b.jabatan_fungsional_id = a.jabatan_fungsional_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_fungsional_active_by_id($params) {
        $sql = "SELECT c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.* 
                FROM pegawai_jabatan_fungsional a 
                INNER JOIN data_jabatan_fungsional b ON b.jabatan_fungsional_id = a.jabatan_fungsional_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                WHERE a.user_id = ? AND jabatan_status = '1' AND jabatan_default = '1'";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_unit_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, b.struktur_singkatan 
                FROM pegawai_unit_kerja a 
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_pegawai_unit_active_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, b.struktur_singkatan 
                FROM pegawai_unit_kerja a 
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                WHERE a.user_id = ? AND unit_kerja_status = 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * PEGAWAI
     */

    // insert users    
    function insert_user($params) {
        return $this->db->insert('com_user', $params);
    }

    // insert pegawai
    function insert_pegawai($params) {
        return $this->db->insert('pegawai', $params);
    }

    // update pegawai
    function update_pegawai($params, $where) {
        return $this->db->update('pegawai', $params, $where);
    }

    // delete pegawai
    function delete_pegawai($params) {
        return $this->db->delete('pegawai', $params);
    }

    function insert_pegawai_status($params) {
        return $this->db->insert('pegawai_status', $params);
    }

    function update_pegawai_status($params, $where) {
        return $this->db->update('pegawai_status', $params, $where);
    }

    /*
     * JABATAN STRUKTURAL
     */

    // insert
    function insert_jabatan_struktur_pegawai($params) {
        return $this->db->insert('pegawai_jabatan_struktural', $params);
    }

    // update
    function update_jabatan_struktur_pegawai($params, $where) {
        return $this->db->update('pegawai_jabatan_struktural', $params, $where);
    }

    // delete
    function delete_jabatan_struktur_pegawai($params) {
        return $this->db->delete('pegawai_jabatan_struktural', $params);
    }

    function set_aktif_jabatan_struktural($jabatan_struktural_id, $user_id, $data_id) {
        $sql = "UPDATE pegawai_jabatan_struktural SET jabatan_status = '0' WHERE user_id = '$user_id'";
        $query = $this->db->query($sql);
        $sql = "UPDATE pegawai_jabatan_struktural SET jabatan_status = '1' WHERE data_id = '$data_id'";
        $query = $this->db->query($sql);
        $sql = "UPDATE pegawai SET jabatan_struktural_id = '$jabatan_struktural_id', jabatan_struktural_st = '1' WHERE user_id = '$user_id'";
        return $this->db->query($sql);
    }

    function check_jabatan_match_by_id($params) {
        $sql = "SELECT jabatan_struktural_id FROM pegawai WHERE jabatan_struktural_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return true;
        } else {
            return false;
        }
    }

    function get_all_jabatan_struktural() {
        $sql = "SELECT a.*,b.struktur_singkatan 
                FROM data_jabatan_struktural a
                INNER JOIN data_struktur_organisasi b ON a.struktur_cd = b.struktur_cd
                ORDER BY jabatan_struktural_id ASC
                ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_struktural_by_id($params) {
        $sql = "SELECT d.user_alias,c.user_id AS user_id,c.nama_lengkap, b.jabatan_nama,b.jabatan_alias, a.* FROM pegawai_jabatan_struktural a 
                INNER JOIN data_jabatan_struktural b ON b.jabatan_struktural_id = a.jabatan_struktural_id
                INNER JOIN pegawai c ON c.user_id = a.user_id
                INNER JOIN com_user d ON d.user_id = c.user_id
                WHERE a.data_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * JABATAN FUNGSIONAL
     */

    // insert
    function insert_jabatan_fungsional_pegawai($params) {
        return $this->db->insert('pegawai_jabatan_fungsional', $params);
    }

    // update
    function update_jabatan_fungsional_pegawai($params, $where) {
        return $this->db->update('pegawai_jabatan_fungsional', $params, $where);
    }

    // delete
    function delete_jabatan_fungional_pegawai($params) {
        return $this->db->delete('pegawai_jabatan_fungsional', $params);
    }

    function get_all_jabatan_fungsional() {
        $sql = "SELECT a.*, b.jabatan_alias 
                FROM data_jabatan_fungsional a
                INNER JOIN data_jabatan_fungsional b ON a.jabatan_fungsional_id = b.jabatan_fungsional_id
                ORDER BY jabatan_fungsional_id ASC
                ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_fungsional_by_id($params) {
        $sql = "SELECT a.*,b.jabatan_nama,b.jabatan_alias, c.user_alias, d.nama_lengkap FROM pegawai_jabatan_fungsional a
                INNER JOIN data_jabatan_fungsional b
                INNER JOIN com_user c ON a.user_id = c.user_id
                INNER JOIN pegawai d ON a.user_id = d.user_id
                WHERE a.data_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * UNIT KERJA
     */

    // insert
    function insert_unit_kerja_pegawai($params) {
        return $this->db->insert('pegawai_unit_kerja', $params);
    }

    // update
    function update_unit_kerja_pegawai($params, $where) {
        return $this->db->update('pegawai_unit_kerja', $params, $where);
    }

    // delete
    function delete_unit_kerja_pegawai($params) {
        return $this->db->delete('pegawai_unit_kerja', $params);
    }

    function update_data_akun($params) {
        return $this->db->update('com_user', $params, $where);
    }

    function update_data_akun_wpassword($params) {
        return $this->db->update('com_user', $params, $where);
    }

    function set_aktif_unit_kerja($struktur_cd, $user_id, $data_id) {
        $sql = "UPDATE pegawai_unit_kerja SET unit_kerja_status = '0' WHERE user_id = $user_id";
        $query = $this->db->query($sql);
        $sql = "UPDATE pegawai_unit_kerja SET unit_kerja_status = '1' WHERE data_id = $data_id";
        $query = $this->db->query($sql);
        $sql = "UPDATE pegawai SET struktur_cd = '$struktur_cd' WHERE user_id = $user_id";
        return $this->db->query($sql);
    }

    function get_all_unit_kerja() {
        $sql = "SELECT * FROM data_struktur_organisasi ORDER BY struktur_nama ASC";
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_unit_kerja_by_id($params) {
        $sql = "SELECT a.*, b.struktur_nama, b.struktur_singkatan,d.nama_lengkap,c.user_alias 
                FROM pegawai_unit_kerja a 
                INNER JOIN data_struktur_organisasi b ON b.struktur_cd = a.struktur_cd
                INNER JOIN pegawai d ON d.user_id = a.user_id
                INNER JOIN com_user c ON c.user_id = a.user_id
                WHERE a.data_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * ROLES
     */

    // get list roles by portal
    function get_all_roles_by_portal($params) {
        $sql = "SELECT * FROM com_role WHERE group_id = ? ORDER BY role_nm ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list roles by user
    function get_all_roles_by_user($params) {
        $sql = "SELECT a.* FROM com_role a
                INNER JOIN com_role_user b ON a.role_id = b.role_id
                WHERE user_id = ? AND portal_id = ? 
                ORDER BY role_nm ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            $role_selected = array();
            foreach ($result as $rec) {
                $role_selected[] = $rec['role_id'];
            }
            return $role_selected;
        } else {
            return array();
        }
    }

    // insert user roles
    function insert_user_role($params) {
        $sql = "INSERT INTO com_role_user VALUES (?, ?)";
        return $this->db->query($sql, $params);
    }

    // delete user roles
    function delete_user_role($params) {
        $sql = "DELETE a.* FROM com_role_user a
                INNER JOIN com_role b ON a.role_id = b.role_id
                WHERE user_id = ? AND portal_id = ?";
        return $this->db->query($sql, $params);
    }

    //edit user
    function update_user($params, $where) {
        return $this->db->update('com_user', $params, $where);
    }

    //edit user
    function set_default($table, $user_id, $data_id) {
        $sql = "UPDATE $table SET jabatan_default = '0' WHERE user_id = $user_id";
        $query = $this->db->query($sql);
        $sql = "UPDATE $table SET jabatan_default = '1' WHERE data_id = $data_id";
        return $this->db->query($sql);
    }

    function update_com_user($params, $where) {
        return $this->db->update('com_user', $params, $where);
    }

    function get_user_last_id($prefixdate, $params) {
        $sql = "SELECT RIGHT(user_id, 4)'last_number'
                FROM com_user 
                WHERE user_id LIKE ? 
                ORDER BY user_id DESC 
                LIMIT 1";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            // create next number
            $number = intval($result['last_number']) + 1;
            if ($number > 9999) {
                return false;
            }
            $zero = '';
            for ($i = strlen($number); $i < 4; $i++) {
                $zero .= '0';
            }
            return $prefixdate . $zero . $number;
        } else {
            // create new number
            return $prefixdate . '0001';
        }
    }

    function is_exist_username($username) {
        $sql = "SELECT COUNT(*)'total' FROM com_user WHERE user_name = ?";
        $query = $this->db->query($sql, $username);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] == 0) {
                return false;
            }
        }
        return true;
    }

    function is_exist_email($email) {
        $sql = "SELECT COUNT(*)'total' FROM com_user WHERE user_mail = ?";
        $query = $this->db->query($sql, $email);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] == 0) {
                return false;
            }
        }
        return true;
    }

    // get detail user by id
    function get_detail_user_by_id($params) {
        $sql = "SELECT a.*,b.user_alias,b.user_id
                FROM pegawai a
                LEFT JOIN com_user b ON a.user_id=b.user_id
                WHERE a.user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_user_alias_by_id($params) {
        $sql = "SELECT user_alias
                FROM com_user
                WHERE user_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['user_alias'];
        } else {
            return '';
        }
    }

    // get roles by user
    function get_roles_by_user($params) {
        $sql = "SELECT a.* 
                FROM com_role_user a
                INNER JOIN com_role b ON b.role_id = a.role_id
                INNER JOIN com_group c ON c.group_id = b.group_id
                WHERE a.user_id = ?
                ORDER BY c.group_name ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all roles
    function get_roles() {
        $sql = "SELECT b.group_name, a.* 
                FROM com_role a
                INNER JOIN com_group b ON a.group_id = b.group_id
                ORDER BY a.role_nm ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get role group
    function get_role_group() {
        $sql = "SELECT * FROM com_group ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get role by group id
    function get_roles_by_group($params) {
        $sql = "SELECT * FROM com_role a 
                LEFT JOIN com_group b ON a.group_id = b.group_id
                WHERE a.group_id LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert role user
    function insert_role_user($params) {
        return $this->db->insert('com_role_user', $params);
    }

    // delete role user
    function delete_role_user($where) {
        return $this->db->delete('com_role_user', $where);
    }

}
