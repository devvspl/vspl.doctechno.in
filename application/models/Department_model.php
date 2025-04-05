<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Department_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_department', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_department_list($id = null)
    {
        if ($id != null) {
            $this->db->select('*');
            $this->db->where('department_id', $id);
            $result = $this->db->get('master_department')->row_array();
            return $result;
        } else {
            $this->db->select('master_department.*,master_firm.firm_name');
            $this->db->from('master_department');
            $this->db->join('master_firm', 'master_firm.firm_id = master_department.company_id', 'left');
            $this->db->order_by('master_department.department_name', 'asc');
            $this->db->where('master_department.is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function get_companylist()
    {
        $this->db->select('*');
        $this->db->where('is_deleted', 'N');
        $this->db->where('firm_type', 'Company');
         $this->db->order_by('firm_name', 'asc');
        $result = $this->db->get('master_firm')->result_array();
        return $result;
    }

    public function delete($id)
    {
        $this->db->where('department_id', $id);
        $result = $this->db->update('master_department', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('department_id', $data['department_id']);
        $result = $this->db->update('master_department', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_department_by_company_id($company_id)
    {
        $this->db->select('department_id,department_name');
        $this->db->where('company_id', $company_id);
        $this->db->where('is_deleted', 'N');
        $result = $this->db->get('master_department')->result_array();
        return $result;
    }
}
