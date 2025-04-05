<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Unit_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $result = $this->db->insert('master_unit', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_unit_list($id = null)
    {
        if ($id != null) {
            $this->db->where('unit_id', $id)->order_by('unit_name', 'asc');
            $result = $this->db->get('master_unit')->row_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('master_unit');
            $this->db->where('is_deleted', 'N');
            $this->db->order_by('unit_name', 'asc');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function delete($id)
    {
        $this->db->where('unit_id', $id);
        $result = $this->db->update('master_unit', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {

        $this->db->where('unit_id', $data['unit_id']);
        $result = $this->db->update('master_unit', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
