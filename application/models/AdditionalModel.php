<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdditionalModel extends CI_Model
{
    protected $year_id;
    function __construct()
    {
        $this->year_id =
            $this->session->userdata("year_id") ??
            ($this->db
                ->select("id")
                ->from("financial_years")
                ->where("is_current", 1)
                ->get()
                ->row()->id ??
                null);
    }
    public function get_autocomplete_list($table, $label_field, $value_field, $query = '', $where = [])
    {
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
                'value' => $row->$value_field,
            ];
        }
        return $data;
    }
    public function get_tds_sections_autocomplete($query = '')
    {
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

    public function get_additional_information_by_scan_id($scan_id)
    {
        $main_tbl = "y{$this->year_id}_tbl_additional_information";
        $item_tbl = "y{$this->year_id}_tbl_additional_information_items";

        $this->db->select('ai.*, be.business_entity_name, td.tds_section_name');
        $this->db->from($main_tbl . ' ai');
        $this->db->join('master_business_entity be', 'be.business_entity_id = ai.business_entity_id', 'left');
        $this->db->join('master_tds_sections td', 'td.tds_section_id = ai.tds_section_id', 'left');
        $this->db->where('ai.scan_id', $scan_id);
        $mainRecord = $this->db->get()->row_array();

        if (empty($mainRecord)) {
            return null;
        }

        $this->db->select(
            'aii.*, cc.name as cost_center_name, d.department_name, bu.business_unit_name, r.region_name, s.state_name, ' .
            'l.location_name, c.category_name, cr.crop_name, a.activity_name, da.account_name as debit_account_name, ' .
            'ca.account_name as credit_account_name, pm.payment_term_name, f.function_name, v.vertical_name, ' .
            'sd.sub_department_name, z.zone_name'
        );
        $this->db->from($item_tbl . ' aii');
        $this->db->join('master_cost_center cc', 'cc.id = aii.cost_center_id', 'left');
        $this->db->join('core_department d', 'd.department_id = aii.department_id', 'left');
        $this->db->join('core_business_unit bu', 'bu.business_unit_id = aii.business_unit_id', 'left');
        $this->db->join('core_region r', 'r.region_id = aii.region_id', 'left');
        $this->db->join('core_state s', 's.state_id = aii.state_id', 'left');
        $this->db->join('master_work_location l', 'l.location_id = aii.location_id', 'left');
        $this->db->join('master_category c', 'c.category_id = aii.category_id', 'left');
        $this->db->join('core_crop cr', 'cr.crop_id = aii.crop_id', 'left');
        $this->db->join('core_activity a', 'a.api_id = aii.activity_id', 'left');
        $this->db->join('master_account_ledger da', 'da.id = aii.debit_account_id', 'left');
        $this->db->join('master_account_ledger ca', 'ca.id = aii.credit_account_id', 'left');
        $this->db->join('payment_term_master pm', 'pm.id = aii.payment_term_id', 'left');
        $this->db->join('core_org_function f', 'f.function_id = aii.function_id', 'left');
        $this->db->join('core_vertical v', 'v.vertical_id = aii.vertical_id', 'left');
        $this->db->join('core_sub_department sd', 'sd.sub_department_id = aii.sub_department_id', 'left');
        $this->db->join('core_zone z', 'z.zone_id = aii.zone_id', 'left');
        $this->db->where('aii.scan_id', $scan_id);
        $items = $this->db->get()->result_array();

        $mainRecord['items'] = $items;
        return $mainRecord;
    }
}
?>