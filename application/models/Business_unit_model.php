<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Business_unit_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_business_unit', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_business_unit_list($id = null)
    {
        $this->db->select('b1.business_unit_id, b1.business_unit_name, b1.business_unit_code, b1.business_unit_group, 
                           IF(b1.business_unit_group = 0, \'No Parent\', b2.business_unit_name) as parent_business_unit_name, 
                           b1.status, b1.created_at, b1.created_by, b1.updated_at, b1.updated_by, b1.is_deleted');
        $this->db->from('master_business_unit b1');
        $this->db->join('master_business_unit b2', 'b1.business_unit_group = b2.business_unit_id', 'left');
        $this->db->where('b1.is_deleted', 'N');
    
        if ($id != null) {
            $this->db->where('b1.business_unit_id', $id);
            $result = $this->db->get()->row_array();
        } else {
            $result = $this->db->get()->result_array();
        }
    
        return $result;
    }
    
    public function delete($id)
    {
        $this->db->where('business_unit_id', $id);
        $result = $this->db->update('master_business_unit', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('business_unit_id', $data['business_unit_id']);
        $result = $this->db->update('master_business_unit', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}


