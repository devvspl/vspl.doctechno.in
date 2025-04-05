<?php
defined('BASEPATH') or exit('No direct script access allowed');
class State_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_state', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_state_list($id = null)
    {
        if ($id != null) {
            $this->db->select('*');
            $this->db->where('state_id', $id);
            $result = $this->db->get('master_state')->row_array();
            return $result;
        } else {
            $this->db->select('master_state.*,master_country.country_name');
            $this->db->from('master_state');
            $this->db->join('master_country', 'master_country.country_id = master_state.country_id', 'left');
            $this->db->order_by('master_state.state_name', 'asc');
            $this->db->where('master_state.is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('state_id', $id);
        $result = $this->db->update('master_state', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('state_id', $data['state_id']);
        $result = $this->db->update('master_state', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_state_by_country_id($country_id)
    {
        $this->db->select('state_id,state_name');
        $this->db->where('country_id', $country_id);
        $this->db->where('is_deleted', 'N');
        $result = $this->db->get('master_state')->result_array();
        return $result;
        
    }
}
