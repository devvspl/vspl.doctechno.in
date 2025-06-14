<?php
defined("BASEPATH") or exit("No direct script access allowed");
class AdditionalController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->model("AdditionalModel");
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    public function get_business_entities()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('master_business_entity', 'business_entity_name', 'business_entity_id', $query);
        echo json_encode($result);
    }
    public function get_tds_section()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_tds_sections_autocomplete($query);
        echo json_encode($result);
    }
    public function get_cost_centers()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('master_cost_center', 'name', 'id', $query);
        echo json_encode($result);
    }
    public function get_business_units()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_business_unit', 'business_unit_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_regions()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_region', 'region_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_states()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_state', 'state_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_locations()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('master_work_location', 'location_name', 'location_id', $query);
        echo json_encode($result);
    }
    public function get_categories()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_category', 'category_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_crops()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_crop', 'crop_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_activities()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_activity', 'activity_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_credit_accounts()
    {
        $query = $this->input->post('term');
        $where = ['ledger_type' => 'Cr Ledger'];
        $result = $this->AdditionalModel->get_autocomplete_list('master_account_ledger', 'account_name', 'id', $query, $where);
        echo json_encode($result);
    }
    public function get_debit_accounts()
    {
        $query = $this->input->post('term');
        $where = ['ledger_type' => 'Dr Ledger'];
        $result = $this->AdditionalModel->get_autocomplete_list('master_account_ledger', 'account_name', 'id', $query, $where);
        echo json_encode($result);
    }
    public function get_payment_term()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('payment_term_master', 'payment_term_name', 'id', $query);
        echo json_encode($result);
    }
    public function get_function()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_org_function', 'function_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_zone()
    {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('core_zone', 'zone_name', 'api_id', $query);
        echo json_encode($result);
    }
    public function get_vertical()
    {
        $query = $this->input->post('term');
        $function_id = $this->input->post('function');
        $this->db->select('core_vertical.api_id, core_vertical.vertical_name');
        $this->db->from('core_function_vertical_mapping');
        $this->db->join('core_vertical', 'core_vertical.api_id = core_function_vertical_mapping.vertical_id', 'LEFT');
        if ($function_id) {
            $this->db->where('core_function_vertical_mapping.org_function_id', $function_id);
        }
        if (!empty($query)) {
            $this->db->like('core_vertical.vertical_name', $query);
        }
        $this->db->limit(5);
        $q = $this->db->get();
        $data = [];
        foreach ($q->result() as $row) {
            $data[] = ['label' => $row->vertical_name, 'value' => $row->api_id,];
        }
        echo json_encode($data);
    }
    public function get_departments()
    {
        $query = $this->input->post('term');
        $vertical_id = $this->input->post('vertical');

        $this->db->select('d.api_id AS value, d.department_name AS label');
        $this->db->from('core_department AS d');

        if ($vertical_id) {
            $subquery = "(SELECT vfm.api_id FROM core_function_vertical_mapping AS vfm WHERE vfm.vertical_id = " . (int) $vertical_id . ")";
            $this->db->join('core_fun_vertical_dept_mapping AS vdm', 'd.api_id = vdm.department_id', 'INNER');
            $this->db->where_in('vdm.function_vertical_id', $subquery, false); // 'false' disables escaping
        }

        if (!empty($query)) {
            $this->db->like('d.department_name', $query);
        }

        $this->db->group_by(['d.api_id', 'd.department_name']);
        $this->db->limit(5);

        $q = $this->db->get();


        $data = [];
        foreach ($q->result() as $row) {
            $data[] = [
                'label' => $row->label,
                'value' => $row->value,
            ];
        }

        echo json_encode($data);
    }

    public function get_sub_department()
    {
        $query = $this->input->post('term');
        $department_id = $this->input->post('department');

        $this->db->select('sd.api_id AS value, sd.sub_department_name AS label');
        $this->db->from('core_sub_department AS sd');

        if ($department_id) {
            $this->db->join('core_department_subdepartment_mapping AS sdm', 'sd.api_id = sdm.sub_department_id', 'INNER');

            // Build subquery as string
            $subquery = "(SELECT vdm.api_id FROM core_fun_vertical_dept_mapping AS vdm WHERE vdm.department_id = " . (int) $department_id . ")";

            $this->db->where_in('sdm.fun_vertical_dept_id', $subquery, false); // false: don't escape
        }

        if (!empty($query)) {
            $this->db->like('sd.sub_department_name', $query);
        }

        $this->db->group_by(['sd.api_id', 'sd.sub_department_name']);
        $this->db->limit(5);

        $q = $this->db->get();

        $data = [];
        foreach ($q->result() as $row) {
            $data[] = [
                'label' => $row->label,
                'value' => $row->value,
            ];
        }

        echo json_encode($data);
    }

    public function store_update()
    {
        $main_tbl = "y{$this->year_id}_tbl_additional_information";
        $item_tbl = "y{$this->year_id}_tbl_additional_information_items";
        $this->db->trans_start();
        try {
            $this->form_validation->set_rules('document_number', 'Document Number', 'required');
            $this->form_validation->set_rules('finance_pucnh_date', 'Document Date', 'required');
            $this->form_validation->set_rules('narration', 'Narration', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $post = $this->input->post();
                $scan_id = $post['scan_id'];
                $document_no = $post['document_number'];
                $document_date = $post['finance_pucnh_date'];
                $narration = $post['narration'];
                $business_entity_id = $post['business_entity_id'];
                $tds_applicable = $post['tdsApplicable'] ?? 'No';
                if ($tds_applicable === 'Yes') {
                    $tds_jv_no = $post['TDS_JV_no'] ?? null;
                    $tds_section_id = $post['tds_section_id'] ?? null;
                    $tds_percentage = $post['tds_percentage'] ?? null;
                    $tds_amount = $post['tds_amount'] ?? null;
                } else {
                    $tds_jv_no = null;
                    $tds_section_id = null;
                    $tds_percentage = null;
                    $tds_amount = null;
                }
                $total_amount = $post['finance_total_Amount'] ?? null;
                $mainData = ['scan_id' => $scan_id, 'document_no' => $document_no, 'document_date' => $document_date, 'business_entity_id' => $business_entity_id, 'narration' => $narration, 'tds_applicable' => ucfirst(strtolower($tds_applicable)), 'tds_jv_no' => $tds_jv_no, 'tds_section_id' => $tds_section_id, 'tds_percentage' => $tds_percentage, 'tds_amount' => $tds_amount, 'total_amount' => $total_amount];
                $existing = $this->db->get_where($main_tbl, ['scan_id' => $scan_id])->row();
                if ($existing) {
                    $this->db->where('scan_id', $scan_id)->update($main_tbl, $mainData);
                    $mainId = $existing->id;
                } else {
                    $this->db->insert($main_tbl, $mainData);
                    $mainId = $this->db->insert_id();
                }
                if (isset($post['final_submit'])) {
                    $updateData = ['finance_punch_action_status' => 'N', 'finance_punch_status' => 'Y', 'finance_punched_by' => $this->session->userdata('user_id'), 'finance_punched_date' => date('Y-m-d')];
                    $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", $updateData);
                }
                if (!empty($post['cost_center_id']) && is_array($post['cost_center_id'])) {
                    $this->db->where('scan_id', $scan_id)->delete($item_tbl);
                    foreach ($post['cost_center_id'] as $index => $cost_center_id) {
                        if (empty($cost_center_id)) {
                            continue;
                        }
                        $itemData = ['scan_id' => $scan_id, 'cost_center_id' => $cost_center_id, 'department_id' => $post['department_id'][$index] ?? null, 'business_unit_id' => $post['business_unit_id'][$index] ?? null, 'region_id' => $post['region_id'][$index] ?? null, 'state_id' => $post['state_id'][$index] ?? null, 'location_id' => $post['location_id'][$index] ?? null, 'category_id' => $post['category_id'][$index] ?? null, 'crop_id' => $post['crop_id'][$index] ?? null, 'activity_id' => $post['activity_id'][$index] ?? null, 'debit_account_id' => $post['debit_ac_id'][$index] ?? null, 'credit_account_id' => $post['credit_ac_id'][$index] ?? null, 'payment_term_id' => $post['payment_term_id'][$index] ?? null, 'reference' => $post['reference_no'][$index] ?? null, 'remark' => $post['item_remark'][$index] ?? null, 'amount' => $post['item_total_amount'][$index] ?? null, 'tds_amount' => $post['item_tds_amount'][$index] ?? null, 'reverse_charge' => $post['reverse_charge'][$index] ?? null, 'function_id' => $post['function_id'][$index] ?? null, 'vertical_id' => $post['vertical_id'][$index] ?? null, 'sub_department_id' => $post['sub_department_id'][$index] ?? null, 'zone_id' => $post['zone_id'][$index] ?? null];
                        $this->db->insert($item_tbl, $itemData);
                    }
                }
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata(['alert_type' => 'danger', 'message' => 'There was an error saving the data.']);
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    if (isset($post['final_submit'])) {
                        $this->session->set_flashdata(['alert_type' => 'success', 'message' => 'File punched successfully!']);
                        redirect('finance/my-punched-file/all');
                    } else {
                        $this->session->set_flashdata(['alert_type' => 'info', 'message' => 'Data saved as draft successfully.']);
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata(['alert_type' => 'danger', 'message' => 'There was an error saving the data.']);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
?>