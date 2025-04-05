<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cost_center_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_cost_center', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_cost_center_list($id = null)
    {
        if ($id != null) {
            $this->db->select('*');
            $this->db->where('cost_center_id', $id);
            $result = $this->db->get('master_cost_center')->row_array();
            return $result;
        } else {
            $this->db->select('master_cost_center.*,master_region.region_name');
            $this->db->from('master_cost_center');
            $this->db->join('master_region', 'master_region.region_id = master_cost_center.region_id', 'left');
            $this->db->order_by('master_cost_center.cost_center_name', 'asc');
            $this->db->where('master_cost_center.is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('cost_center_id', $id);
        $result = $this->db->update('master_cost_center', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('cost_center_id', $data['cost_center_id']);
        $result = $this->db->update('master_cost_center', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_cost_center_by_region_id($region_id)
    {
        $this->db->select('cost_center_id,cost_center_name');
        $this->db->where('region_id', $region_id);
        $this->db->where('is_deleted', 'N');
        $result = $this->db->get('master_cost_center')->result_array();
        return $result;
        
    }
}




