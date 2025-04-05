<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Location_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_work_location', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_location_list($id = null)
    {
        if ($id != null) {
            $this->db->where('location_id', $id);
            $result = $this->db->get('master_work_location')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_work_location');
            $this->db->where('is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('location_id', $id);
        $result = $this->db->update('master_work_location', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('location_id', $data['location_id']);
        $result = $this->db->update('master_work_location', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
