<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Country_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_country', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_country_list($id = null)
    {
        if ($id != null) {
            $this->db->where('country_id', $id);
            $result = $this->db->get('master_country')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_country');
            $this->db->where('is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('country_id', $id);
        $result = $this->db->update('master_country', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('country_id', $data['country_id']);
        $result = $this->db->update('master_country', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
