<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ApprovalMatrixModel');
        $this->load->library('form_validation');
        $this->logged_in();
        $this->check_role();
    }

    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }

    private function check_role()
    {
        $allowed_roles = [1];
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, $allowed_roles)) {
            show_error('You are not authorized to access this page.', 403);
        }
    }

    public function approvalMatrix()
    {
        // Extract filter parameters from GET
        $filters = [
            'function' => $this->input->get('function', TRUE),
            'vertical' => $this->input->get('vertical', TRUE),
            'department' => $this->input->get('department', TRUE),
            'region' => $this->input->get('region', TRUE),
            'zone' => $this->input->get('zone', TRUE),
            'business_unit' => $this->input->get('business_unit', TRUE),
            'bill_type' => $this->input->get('bill_type', TRUE),
            'location' => $this->input->get('location', TRUE)
        ];

        $this->data['main'] = 'admin/approval-matrix';
        $this->data['approval_matrices'] = $this->ApprovalMatrixModel->getAllApprovalMatrices($filters);
        $this->data['filters'] = $filters; // Pass filters to view for pre-selection
        $this->load->view('layout/template', $this->data);
    }

    public function addApprovalMatrix()
    {
        $this->data['main'] = 'admin/add-approval-matrix';
        $this->load->view('layout/template', $this->data);
    }

    public function editApprovalMatrix($id)
    {
        $this->data['main'] = 'admin/add-approval-matrix';
        $this->data['matrix'] = $this->ApprovalMatrixModel->getApprovalMatrixById($id);
        // echo "<pre>";
        // print_r($this->data['matrix']);
        // echo "</pre>";
        // exit;
        if (!$this->data['matrix']) {
            show_error('Approval matrix not found.', 404);
        }
        $this->load->view('layout/template', $this->data);
    }

    public function getUniqueLedgers()
    {
        $this->db->select('ledger');
        $this->db->from('tbl_approval_matrix');
        $this->db->group_by('ledger');
        $query = $this->db->get();
        echo json_encode($query->result());
    }

    public function getFunction()
    {
        $query = $this->input->post('query');
        $this->db->select('core_org_function.api_id, core_org_function.function_name');
        $this->db->from('core_org_function');
        if (!empty($query)) {
            $this->db->like('core_org_function.function_name', $query);
        }
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function getVertical()
    {
        $function_id = $this->input->post('function');
        if (!empty($function_id)) {
            $this->db->select('core_vertical.api_id, core_vertical.vertical_name');
            $this->db->from('core_function_vertical_mapping');
            $this->db->join('core_vertical', 'core_vertical.api_id = core_function_vertical_mapping.vertical_id', 'LEFT');
            if ($function_id) {
                $this->db->where('core_function_vertical_mapping.org_function_id', $function_id);
            }
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getDepartment()
    {
        $vertical_id = $this->input->post('vertical');
        $function_id = $this->input->post('function');
        if (!empty($vertical_id)) {
            $this->db->select('d.api_id AS value, d.department_name AS label');
            $this->db->from('core_department AS d');
            if ($vertical_id) {
                $subquery = "(SELECT vfm.api_id FROM core_function_vertical_mapping AS vfm WHERE vfm.vertical_id = " . (int) $vertical_id . " AND vfm.org_function_id = " . (int) $function_id . ")";
                $this->db->join('core_fun_vertical_dept_mapping AS vdm', 'd.api_id = vdm.department_id', 'INNER');
                $this->db->where_in('vdm.function_vertical_id', $subquery, false);
            }
            $this->db->group_by(['d.api_id', 'd.department_name']);
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getSubDepartment()
    {
        $department_id = $this->input->post('department');
        if (!empty($department_id)) {
            $this->db->select('sd.api_id AS value, sd.sub_department_name AS label');
            $this->db->from('core_sub_department AS sd');
            if ($department_id) {
                $this->db->join('core_department_subdepartment_mapping AS sdm', 'sd.api_id = sdm.sub_department_id', 'INNER');
                $subquery = "(SELECT vdm.api_id FROM core_fun_vertical_dept_mapping AS vdm WHERE vdm.department_id = " . (int) $department_id . ")";
                $this->db->where_in('sdm.fun_vertical_dept_id', $subquery, false);
            }
            $this->db->group_by(['sd.api_id', 'sd.sub_department_name']);
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getCrop()
    {
        $vertical = $this->input->post('vertical');
        if (!empty($vertical)) {
            $this->db->select('api_id, crop_name');
            $this->db->from('core_crop');
            if (!empty($vertical)) {
                $this->db->where('vertical_id', $vertical);
            }
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getActivity()
    {
        $department_id = $this->input->post('department');
        if (!empty($department_id)) {
            $this->db->select('core_activity.api_id AS value, core_activity.activity_name AS label');
            $this->db->from('core_activity');
            $this->db->join('tbl_department_activity_mapping', 'tbl_department_activity_mapping.activity_id = core_activity.api_id', 'left');
            $this->db->where('core_activity.is_active', 1);
            if (!empty($department_id)) {
                $this->db->where('tbl_department_activity_mapping.department_id', $department_id);
            }
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getLocation()
    {
        $this->db->select('location_id, location_name');
        $this->db->from('master_work_location');
        $this->db->where('is_deleted', 'N');
        $this->db->where('status', 'A');
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function getRegion()
    {
        $vertical = $this->input->post('vertical');
        if (!empty($vertical)) {
            $this->db->select('api_id, region_name');
            $this->db->from('core_region');
            $this->db->where('vertical_id', $vertical);
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getZone()
    {
        $region_id = $this->input->post('region');
        if (!empty($region_id)) {
            $this->db->select('core_zone.api_id, core_zone.zone_name');
            $this->db->from('core_zone_region_mapping');
            $this->db->join('core_zone', 'core_zone.api_id = core_zone_region_mapping.zone_id', 'LEFT');
            if ($region_id) {
                $this->db->where('region_id', $region_id);
            }
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getBusinessUnit()
    {
        $vertical = $this->input->post('vertical');
        if (!empty($vertical)) {
            $this->db->select('api_id, business_unit_name');
            $this->db->from('core_business_unit');
            $this->db->where('vertical_id', $vertical);
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }

    public function getLedger()
    {
        $this->db->select('account_name');
        $this->db->from('master_account_ledger');
        $this->db->where('status', 'Y');
        $this->db->where('is_deleted', 'N');
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function getSubledger()
    {
        $this->db->select('id, name');
        $this->db->from('master_cost_center');
        $this->db->where('status', 1);
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function getBillType()
    {
        $this->db->select('type_id, file_type');
        $this->db->from('master_doctype');
        $this->db->where('status', 'A');
        $this->db->where_in('type_id', [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 56]);
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function getApprovers()
    {
        $this->db->select('user_id, CONCAT(first_name, " ", last_name) AS full_name');
        $this->db->from('users');
        $this->db->where('role_id', 4);
        $q = $this->db->get();
        echo json_encode($q->result());
    }

    public function saveApprovalMatrix()
    {
        $this->form_validation->set_rules('min_amount', 'Minimum Amount', 'numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('max_amount', 'Maximum Amount', 'numeric|greater_than_equal_to[0]|callback_check_amount_range');
        $this->form_validation->set_rules('valid_from', 'Valid From', 'required');
        $this->form_validation->set_rules('valid_to', 'Valid To', 'callback_check_date_range');
        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => 'error',
                'errors' => $this->form_validation->error_array()
            ];
            echo json_encode($response);
            return;
        }
        // Prepare data
        $data = [
            'function' => $this->input->post('function'),
            'vertical' => $this->input->post('vertical'),
            'department' => $this->input->post('department'),
            'sub_department' => implode(',', $this->input->post('sub_department') ?? []),
            'ledger' => $this->input->post('ledger'),
            'subledger' => $this->input->post('subledger'),
            'crop' => implode(',', $this->input->post('crop') ?? []),
            'activity' => implode(',', $this->input->post('activity') ?? []),
            'location' => implode(',', $this->input->post('location') ?? []),
            'zone' => $this->input->post('zone'),
            'region' => $this->input->post('region'),
            'business_unit' => $this->input->post('business_unit'),
            'amount_min' => $this->input->post('min_amount'),
            'amount_max' => $this->input->post('max_amount'),
            'bill_type' => $this->input->post('bill_type'),
            'l1_approver' => $this->input->post('l1_approver'),
            'l2_approver' => $this->input->post('l2_approver'),
            'l3_approver' => $this->input->post('l3_approver'),
            'valid_from' => $this->input->post('valid_from'),
            'valid_to' => $this->input->post('valid_to'),
            'updated_by' => $this->session->userdata('user_id')
        ];
        if (!$this->input->post('id')) {
            $data['created_by'] = $this->session->userdata('user_id');
        }
        $id = $this->input->post('id');
        if ($id) {
            $result = $this->ApprovalMatrixModel->updateApprovalMatrix($id, $data);
            $message = 'Approval matrix updated successfully.';
        } else {
            $result = $this->ApprovalMatrixModel->insertApprovalMatrix($data);
            $message = 'Approval matrix created successfully.';
        }

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save approval matrix.']);
        }
    }

    public function check_amount_range($max_amount)
    {
        $min_amount = $this->input->post('min_amount');
        if ($max_amount < $min_amount) {
            $this->form_validation->set_message('check_amount_range', 'Maximum Amount must be greater than or equal to Minimum Amount.');
            return FALSE;
        }
        return TRUE;
    }

    public function check_date_range($valid_to)
    {
        $valid_from = $this->input->post('valid_from');
        if (strtotime($valid_to) < strtotime($valid_from)) {
            $this->form_validation->set_message('check_date_range', 'Valid To date must be greater than or equal to Valid From date.');
            return FALSE;
        }
        return TRUE;
    }
}