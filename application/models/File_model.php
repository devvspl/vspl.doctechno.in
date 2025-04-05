<?php
defined('BASEPATH') or exit('No direct script access allowed');
class File_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_file', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_file_list($id = null)
    {
        if ($id != null) {
            $this->db->select('*');
            $this->db->where('file_id', $id);
            $result = $this->db->get('master_file')->row_array();
            return $result;
        } else {
            $this->db->select('master_file.*,master_firm.firm_name');
            $this->db->from('master_file');
            $this->db->join('master_firm', 'master_firm.firm_id = master_file.company_id', 'left');
            $this->db->order_by('master_file.file_name', 'asc');
            $this->db->where('master_file.is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }



    public function delete($id)
    {
        $this->db->where('file_id', $id);
        $result = $this->db->update('master_file', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('file_id', $data['file_id']);
        $result = $this->db->update('master_file', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_file_by_company_id($company_id)
    {
        $this->db->select('file_id,file_name');
        $this->db->where('company_id', $company_id);
        $this->db->where('is_deleted', 'N');
        $result = $this->db->get('master_file')->result_array();
        return $result;
    }
}
