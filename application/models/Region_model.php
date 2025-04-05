<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Region_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_region', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_region_list($id = null)
    {
        if ($id != null) {
            $this->db->select('*');
            $this->db->where('region_id', $id);
            $result = $this->db->get('master_region')->row_array();
            return $result;
        } else {
            $this->db->select('master_region.*,master_state.state_name');
            $this->db->from('master_region');
            $this->db->join('master_state', 'master_state.state_id = master_region.state_id', 'left');
            $this->db->order_by('master_region.region_name', 'asc');
            $this->db->where('master_region.is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('region_id', $id);
        $result = $this->db->update('master_region', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('region_id', $data['region_id']);
        $result = $this->db->update('master_region', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_region_by_state_id($state_id)
    {
        $this->db->select('region_id,region_name');
        $this->db->where('state_id', $state_id);
        $this->db->where('is_deleted', 'N');
        $result = $this->db->get('master_region')->result_array();
        return $result;
        
    }
}


