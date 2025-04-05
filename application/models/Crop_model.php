<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Crop_model extends CI_Model
{

    public function create($data)
    {
        $result = $this->db->insert('master_crop', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_crop_list($id = null)
    {
        $this->db->select('master_crop.*, master_crop_category.crop_category_name');
        $this->db->from('master_crop');
        $this->db->join('master_crop_category', 'master_crop.crop_category_id = master_crop_category.crop_category_id', 'left');
        if ($id != null) {
            $this->db->where('master_crop.crop_id', $id);
            $result = $this->db->get()->row_array();
        } else {
            $this->db->where('master_crop.is_deleted', 'N');
            $result = $this->db->get()->result_array();
        }
    
        return $result;
    }
    
    public function get_crop_category_list($id = null)
    {
        if ($id != null) {
            $this->db->where('crop_category_id', $id);
            $result = $this->db->get('master_crop_category')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_crop_category');
            $this->db->where('is_deleted', 'N');
            $this->db->where('status', 'A');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('crop_id', $id);
        $result = $this->db->update('master_crop', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
      
        $this->db->where('crop_id', $data['crop_id']);
        $result = $this->db->update('master_crop', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}



