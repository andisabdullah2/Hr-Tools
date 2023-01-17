<?php

class M_kontrak  extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /* CORE
    *
    */

    // get total kontrak
    function get_total_project_kontrak($params)
    {
        $sql = "SELECT COUNT(kontrak_id) AS total 
                FROM project_kontrak a
                LEFT JOIN projects b ON a.project_id=b.project_id
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ? ) AND YEAR(a.tanggal_kontrak) LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get total termin
    function get_total_termin($params)
    {
        $sql = "SELECT COUNT(c.termin_id) AS total 
                FROM projects_termin c
                LEFT JOIN project_kontrak a ON a.kontrak_id=c.kontrak_id
                LEFT JOIN projects b ON a.project_id=b.project_id
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ? ) AND YEAR(a.tanggal_kontrak) LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get list kontrak project data
    function get_all_kontrak_data($params)
    {
        $sql = "SELECT kontrak_id,c.perusahaan_nama,judul_kontrak, nomor_kontrak,
                tanggal_selesai,nilai_kontrak, jumlah_termin,b.project_alias
                FROM project_kontrak a
                LEFT JOIN projects b ON a.project_id=b.project_id
                LEFT JOIN data_perusahaan c ON a.struktur_cd=c.struktur_cd
                WHERE (b.project_name LIKE ? OR b.project_alias LIKE ? ) AND YEAR(a.tanggal_kontrak) LIKE ?
                ORDER BY nomor_kontrak DESC
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

    // get list termin data
    function get_all_termin_data_by_kontrak($params)
    {
        $sql = "SELECT a.*,ab.judul_kontrak
                FROM projects_termin a
                INNER JOIN project_kontrak ab ON a.kontrak_id=ab.kontrak_id
                LEFT JOIN projects b ON ab.project_id=b.project_id
                LEFT JOIN data_perusahaan c ON ab.struktur_cd=c.struktur_cd
                WHERE a.kontrak_id = ?
                ORDER BY termin_tanggal DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail kontrak
    function get_detail_kontrak_by_id($params)
    {
        $sql = "SELECT a.*, YEAR(b.project_start) AS tahun, b.project_name, b.project_alias
                FROM project_kontrak a
                LEFT JOIN projects b ON a.project_id=b.project_id
                WHERE kontrak_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail termin
    function get_detail_termin_by_id($params)
    {
        $sql = "SELECT * FROM projects_termin a WHERE termin_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list termin by kontrak
    function get_termin_by_kontrak_id($params)
    {
        $sql = "SELECT * FROM projects_termin a WHERE kontrak_id = ? ORDER BY termin_tanggal DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_termin_by_kontrak_id($params)
    {
        $sql = "SELECT COUNT(termin_id) AS total FROM projects_termin a WHERE kontrak_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert kontrak
    function insert($params)
    {
        return $this->db->insert('project_kontrak', $params);
    }

    // insert termin
    function insert_termin($params)
    {
        return $this->db->insert('projects_termin', $params);
    }

    // update kontrak
    function update($params, $where)
    {
        return $this->db->update('project_kontrak', $params, $where);
    }

    // update termin
    function update_termin($params, $where)
    {
        return $this->db->update('projects_termin', $params, $where);
    }

    // delete
    function delete($params)
    {
        return $this->db->delete('project_kontrak', $params);
    }

    // delete termin
    function delete_termin($params)
    {
        return $this->db->delete('projects_termin', $params);
    }

    /* UTILITY
    *
    */

    // get list tahun avail in kontrak
    function get_list_tahun_kontrak()
    {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(tanggal_kontrak)'tahun'
                        FROM project_kontrak
                        UNION ALL
                        SELECT YEAR(CURRENT_DATE)'tahun'
                ) rs
                ORDER BY tahun DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list tahun avail in project
    function get_list_tahun_project()
    {
        $sql = "SELECT DISTINCT tahun FROM
                (
                        SELECT YEAR(project_start)'tahun'
                        FROM projects
                        UNION ALL
                        SELECT YEAR(CURRENT_DATE)'tahun'
                ) rs
                ORDER BY tahun DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all project
    function get_all_data_projects()
    {
        $sql = "SELECT * FROM projects";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get project by tahun
    function get_project_by_tahun($params)
    {
        $sql = "SELECT * FROM projects WHERE YEAR(project_start) LIKE ? ORDER BY project_start DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list perusahaan
    function get_list_perusahaan()
    {
        $sql = "SELECT struktur_cd,perusahaan_nama FROM data_perusahaan ORDER BY perusahaan_nama ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get new nomor termin
    function get_new_nomor_termin()
    {
        $sql = "SELECT termin_nomor FROM projects_termin ORDER BY termin_nomor DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['termin_nomor'] + 1;
        } else {
            return 1;
        }
    }

    // generate kontrak id
    function generate_kontrak_id($project_id)
    {
        $sql = "SELECT kontrak_id FROM project_kontrak
                ORDER BY create_date DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['kontrak_id'], -10) + 1;
            $nomor_urut = str_pad((string)$nomor_urut, 10, '0', STR_PAD_LEFT);
            return $project_id . $nomor_urut;
        } else {
            return $project_id . '0000000001';
        }
    }

    // generate termin id
    function generate_termin_id($kontrak_id)
    {
        $kontrak_id = substr($kontrak_id, -10);
        $sql = "SELECT termin_id FROM projects_termin
                ORDER BY RIGHT(termin_id,10) DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['termin_id'], -10) + 1;
            $nomor_urut = str_pad((string)$nomor_urut, 10, '0', STR_PAD_LEFT);
            return $kontrak_id . $nomor_urut;
        } else {
            return $kontrak_id . '0000000001';
        }
    }

    // generate invoice id
    function generate_invoice_id($termin_id)
    {
        $termin_id = substr($termin_id, -10);
        $sql = "SELECT invoices_id FROM projects_invoices
                ORDER BY RIGHT(invoices_id,10) DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $nomor_urut = substr($result['invoices_id'], -10) + 1;
            $nomor_urut = str_pad((string)$nomor_urut, 10, '0', STR_PAD_LEFT);
            return $termin_id . $nomor_urut;
        } else {
            return $termin_id . '0000000001';
        }
    }

    // hitung lama penyelesaian
    public function hitung_lama_penyelesaian($params)
    {
        // hitung total hari kerja
        $tanggal_kontrak = strtotime($params[0]);
        $tanggal_selesai = strtotime($params[1]);
        $working_days = $this->get_total_working_day($tanggal_kontrak, $tanggal_selesai);
        $total_hari_libur = $this->get_total_hari_libur_between_range_tanggal($params);
        // lama penyelesaian = hari kerja - total hari libur
        return $working_days - $total_hari_libur;
    }

    // hitung total hari kerja
    private function get_total_working_day($startDate, $endDate)
    {
        if ($startDate > $endDate) {
            return 0;
        } else {
            $no_days  = 0;
            $weekendDates = 0;
            while ($startDate <= $endDate) {
                $no_days++; // 
                $what_day = date("N", $startDate);
                // saturday & sunday = weekendDate
                if ($what_day > 5) {
                    $weekendDates++;
                };
                $startDate += 86400;
            };
            $working_days = $no_days - $weekendDates;
            return $working_days;
        }
    }

    // get holiday between date
    private function get_total_hari_libur_between_range_tanggal($params)
    {
        $sql = "SELECT COUNT(libur_tanggal) AS 'total' FROM data_hari_libur WHERE libur_tanggal BETWEEN ? AND ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }
}
