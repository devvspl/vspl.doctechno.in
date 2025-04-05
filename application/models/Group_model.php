<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Group_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_group', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_group_list($id = null)
    {
        if ($id != null) {
            $this->db->where('group_id', $id);
            $result = $this->db->get('master_group')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_group');
            $this->db->where('is_deleted', 'N');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('group_id', $id);
        $result = $this->db->update('master_group', array('is_deleted' => 'Y'));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('group_id', $data['group_id']);
        $result = $this->db->update('master_group', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_filetype_list($id = null)
    {
        if ($id != null) {
            $this->db->where('type_id', $id);
            $result = $this->db->get('master_doctype')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_doctype');
            $this->db->where('status', 'A');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }
}
