<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Firm_model extends CI_Model {
    public function create($data) {
        $result = $this->db->insert('master_firm', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function get_firm_list($id = null) {
        if ($id != null) {
            $this->db->where('firm_id', $id);
            $result = $this->db->get('master_firm')->row_array();
            return $result;
        } else {
            $this->db->select('master_firm.*,master_country.country_name,master_state.state_code');
            $this->db->from('master_firm');
            $this->db->join('master_country', 'master_country.country_id = master_firm.country_id', 'left');
            $this->db->join('master_state', 'master_state.state_id = master_firm.state_id', 'left');
            $this->db->where('master_firm.is_deleted', 'N');
            $this->db->order_by('firm_type', 'asc');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }
    public function delete($id) {
        $this->db->where('firm_id', $id);
        $result = $this->db->update('master_firm', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function update($data) {
        $this->db->where('firm_id', $data['firm_id']);
        $result = $this->db->update('master_firm', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function vendor_list() {
        $this->db->select('*');
        $this->db->where('is_deleted', 'N');
        $this->db->where('firm_type', 'Vendor');
        $this->db->order_by('firm_name', 'asc');
        $result = $this->db->get('master_firm')->result_array();
        return $result;
    }
    public function get_firm_by_name_and_code_type($firmName, $firmCode, $firmType) {
        $this->db->where('firm_name', $firmName);
        $this->db->where('firm_code', $firmCode);
        $this->db->where('firm_type', $firmType);
        $query = $this->db->get('master_firm');
        return $query->row(); 
        
    }
    public function firm_import($data) {
        if (!empty($data)) {
            $insert = $this->db->insert('master_firm', $data);
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }
    public function getVendorRequests() {
        try {
            $this->db->select('master_firm.firm_id, master_firm.firm_name, master_firm.firm_code, core_country.country_name, core_country.country_code, core_state.state_name, core_state.short_code, master_firm.city_name, master_firm.pin_code, master_firm.address, master_firm.gst');
            $this->db->from('master_firm');
            $this->db->join('core_country', 'core_country.api_id = master_firm.country_id', 'left');
            $this->db->join('core_state', 'core_state.api_id = master_firm.state_id', 'left');
            $this->db->where('master_firm.status', 'R');
            return $this->db->get()->result();
        }
        catch(Exception $e) {
            return null;
        }
    }
    
}
