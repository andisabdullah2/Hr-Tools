<?php
class m_monitoring extends CI_Model {

    // construct
    public function __construct() {
        parent::__construct();
    }

    /*
     * UTILITY
     */

    //get list tahun
    function get_list_tahun(){
        $sql = "SELECT DISTINCT tahun FROM
                (
                    SELECT YEAR(overtime_date) 'tahun'
                    FROM surat_lembur
                    UNION ALL
                    SELECT YEAR(CURRENT_DATE) 'tahun'
                )rs
                ORDER BY tahun ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get active project
    function get_all_projects() {
        $sql = "SELECT * FROM projects ORDER BY YEAR(project_start) DESC, project_alias ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
    * OVERTIME
    */

    // get total overtime
    function get_total_overtime($params){
        $sql = "SELECT COUNT(overtime_id)'total'
                FROM surat_lembur
                WHERE project_id LIKE ? AND YEAR(overtime_date) = ? AND MONTH(overtime_date) LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get all overtime by limit
    function get_all_overtime_by_limit($params){
        $sql = "SELECT * 
                FROM
                (
                        SELECT get_last_flow_lembur_by_id(a.overtime_id)'last_process', 
                        a.overtime_id, a.overtime_date, a.overtime_st, project_name, project_alias, 
                        overtime_start, overtime_end, COUNT(b.user_id)'total_personel'
                        FROM surat_lembur a
                        INNER JOIN pegawai_lembur b ON a.overtime_id = b.overtime_id
                        INNER JOIN pegawai c ON b.user_id = c.user_id
                        INNER JOIN projects d ON d.project_id = a.project_id
                        WHERE a.project_id LIKE ? AND YEAR(a.overtime_date) = ? AND MONTH(a.overtime_date) LIKE ?
                        GROUP BY a.overtime_id
                ) result
                LEFT JOIN 
                (
                    SELECT * FROM
                    (
                            SELECT e.process_id, e.overtime_id, e.action_st, e.process_st, f.*
                            FROM surat_lembur_process e
                            INNER JOIN task_flow f ON e.flow_id = f.flow_id
                            ORDER BY process_id DESC
                    ) pr
                    GROUP BY overtime_id
                ) flows ON result.overtime_id = flows.overtime_id
                ORDER BY overtime_date ASC
                LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail overtime
    function get_detail_overtime($params){
        $sql = "SELECT a.overtime_id, a.project_id, b.project_alias, b.project_name, a.overtime_reason, a.overtime_date, a.overtime_start, a.overtime_end, a.mdd
                FROM surat_lembur a
                INNER JOIN projects b ON a.project_id = b.project_id
                WHERE a.overtime_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get personil overtime
    function get_personil_overtime($params){
        $sql = "SELECT a.user_id, a.nama_lengkap
                FROM pegawai a
                INNER JOIN pegawai_lembur b ON a.user_id = b.user_id 
                WHERE b.overtime_id = ? ";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}
?>