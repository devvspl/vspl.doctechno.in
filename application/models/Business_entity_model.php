<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Business_entity_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_business_entity', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_business_entity_list($id = null)
    {
        if ($id != null) {
            $this->db->where('business_entity_id', $id);
            $result = $this->db->get('master_business_entity')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_business_entity');
            $this->db->where('is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('business_entity_id', $id);
        $result = $this->db->update('master_business_entity', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('business_entity_id', $data['business_entity_id']);
        $result = $this->db->update('master_business_entity', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}


