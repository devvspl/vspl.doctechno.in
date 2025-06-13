<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
    public function get_menu_items() {
        $this->db->select('id, parent_id, name, url, icon, topmenu, submenu, order');
        $this->db->from('menus');
        $this->db->where('is_active', 1);
        $this->db->order_by('order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}