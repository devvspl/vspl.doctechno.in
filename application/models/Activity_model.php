<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Activity_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_activity', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_activity_list($id = null)
    {
        $this->db->select('a1.activity_id, a1.activity_name, a1.activity_code, a1.activity_group, 
                           IF(a1.activity_group = 0, \'No Parent\', a2.activity_name) as parent_activity_name, 
                           a1.status, a1.created_at, a1.created_by, a1.updated_at, a1.updated_by, a1.is_deleted');
        $this->db->from('master_activity a1');
        $this->db->join('master_activity a2', 'a1.activity_group = a2.activity_id', 'left');
        $this->db->where('a1.is_deleted', 'N');
    
        if ($id != null) {
            $this->db->where('a1.activity_id', $id);
            $result = $this->db->get()->row_array();
        } else {
            $result = $this->db->get()->result_array();
        }
    
        return $result;
    }
    
    public function delete($id)
    {
        $this->db->where('activity_id', $id);
        $result = $this->db->update('master_activity', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('activity_id', $data['activity_id']);
        $result = $this->db->update('master_activity', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}


