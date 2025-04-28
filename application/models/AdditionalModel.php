<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdditionalModel extends CI_Model {

    public function get_autocomplete_list($table, $label_field, $value_field, $query = '', $where = []) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($query)) {
            $this->db->like($label_field, $query);
        }
        $this->db->select($value_field . ', ' . $label_field);
        $this->db->limit(5);
        $q = $this->db->get($table);
        
        $data = [];
        foreach ($q->result() as $row) {
            $data[] = [
                'label' => $row->$label_field,
                'value' => $row->$value_field
            ];
        }
        return $data;
    }
    public function get_tds_sections_autocomplete($query = '') {
        if (!empty($query)) {
            $this->db->group_start();
            $this->db->like('section', $query);
            $this->db->or_like('description', $query);
            $this->db->group_end();
        }
        $this->db->select('id, section, description, rate');
        $this->db->limit(5);
        $q = $this->db->get('master_tds_sections');
        
        $data = [];
        foreach ($q->result() as $row) {
            $data[] = [
                'label' => $row->section . ' - ' . $row->description, 
                'value' => $row->id, 
                'section' => $row->section,
                'description' => $row->description,
                'rate' => $row->rate,
            ];
        }
        return $data;
    }
    
    
}
?>
