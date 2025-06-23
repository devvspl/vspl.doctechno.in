<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApprovalMatrixModel extends CI_Model
{
    public function getAllApprovalMatrices()
    {
        $this->db->select("
            tam.id AS rule_id,
            cof.function_name,
            cd.department_name,
            tam.ledger,
            CONCAT('₹', tam.amount_min, ' - ₹', tam.amount_max) AS amount_range,
            md.file_type AS bill_type,
            CONCAT(
                IFNULL(u1.first_name, ''), ' ', IFNULL(u1.last_name, ''),
                IF(tam.l2_approver, CONCAT(', ', IFNULL(u2.first_name, ''), ' ', IFNULL(u2.last_name, '')), ''),
                IF(tam.l3_approver, CONCAT(', ', IFNULL(u3.first_name, ''), ' ', IFNULL(u3.last_name, '')), '')
            ) AS approver_levels,
            CASE 
                WHEN CURDATE() BETWEEN tam.valid_from AND tam.valid_to THEN 'Active'
                ELSE 'Inactive'
            END AS status,
            tam.function,
            tam.department,
            tam.sub_department,
            tam.zone,
            tam.region,
            tam.bill_type
        ");
        $this->db->from('tbl_approval_matrix AS tam');
        $this->db->join('core_org_function AS cof', 'cof.api_id = tam.function', 'LEFT');
        $this->db->join('core_department AS cd', 'cd.api_id = tam.department', 'LEFT');
        $this->db->join('master_doctype AS md', 'md.type_id = tam.bill_type', 'LEFT');
        $this->db->join('users AS u1', 'u1.user_id = tam.l1_approver', 'LEFT');
        $this->db->join('users AS u2', 'u2.user_id = tam.l2_approver', 'LEFT');
        $this->db->join('users AS u3', 'u3.user_id = tam.l3_approver', 'LEFT');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function insertApprovalMatrix($data)
    {
        return $this->db->insert('tbl_approval_matrix', $data);
    }

    public function updateApprovalMatrix($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbl_approval_matrix', $data);
    }

    public function getApprovalMatrixById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_approval_matrix');
        return $query->row_array();
    }
}