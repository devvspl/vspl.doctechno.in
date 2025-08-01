<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AdminController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ApprovalMatrixModel');
        $this->load->model('AdminModel');
    }
    public function approval_matrix()
    {
        if (!getRoutePermission("approval_matrix")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $filters = ['function' => $this->input->get('function', true), 'vertical' => $this->input->get('vertical', true), 'department' => $this->input->get('department', true), 'region' => $this->input->get('region', true), 'zone' => $this->input->get('zone', true), 'business_unit' => $this->input->get('business_unit', true), 'bill_type' => $this->input->get('bill_type', true), 'location' => $this->input->get('location', true),];
        $this->data['main'] = 'super_admin/approval_matrix';
        $this->data['approval_matrices'] = $this->ApprovalMatrixModel->getAllApprovalMatrices($filters);
        $this->data['filters'] = $filters;
        $this->load->view('layout/template', $this->data);
    }
    public function add_approval_matrix()
    {
        $this->data['main'] = 'super_admin/add_approval_matrix';
        $this->load->view('layout/template', $this->data);
    }
    public function edit_approval_matrix($id)
    {
        $this->data['main'] = 'super_admin/add_approval_matrix';
        $this->data['matrix'] = $this->ApprovalMatrixModel->getApprovalMatrixById($id);
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
            $this->db->where('tbl_department_activity_mapping.department_id', $department_id);
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
        $zone_id = $this->input->post('zone');
        if (!empty($zone_id)) {
            $this->db->select('core_region.api_id, core_region.region_name');
            $this->db->from('core_zone_region_mapping');
            $this->db->join('core_region', 'core_region.api_id = core_zone_region_mapping.zone_id', 'LEFT');
            $this->db->where('core_zone_region_mapping.zone_id', $zone_id);
            $q = $this->db->get();
            echo json_encode($q->result());
        } else {
            echo json_encode([]);
        }
    }
    public function getZone()
    {
        $business_unit_id = $this->input->post('business_unit');
        if (!empty($business_unit_id)) {
            $this->db->select('core_zone.api_id, core_zone.zone_name');
            $this->db->from('core_bu_zone_mapping');
            $this->db->join('core_zone', 'core_zone.api_id = core_bu_zone_mapping.zone_id', 'LEFT');
            $this->db->where('business_unit_id', $business_unit_id);
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
        $term = $this->input->post('term');
        $page = $this->input->post('page') ? (int) $this->input->post('page') : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $this->db->select("CONCAT(account_name, ' - ', focus_code) AS account_name, id");
        $this->db->from('master_account_ledger');
        $this->db->where('status', 'Y');
        $this->db->where('is_deleted', 'N');
        if (!empty($term)) {
            $this->db->group_start();
            $this->db->like('account_name', $term);
            $this->db->or_like('focus_code', $term);
            $this->db->group_end();
        }
        if (!empty($this->input->post('id'))) {
            $this->db->where('id', $this->input->post('id'));
        }
        $this->db->limit($perPage, $offset);
        $q = $this->db->get();
        echo json_encode($q->result());
    }
    public function getSubledger()
    {
        $ledger_id = $this->input->post('ledger');
        $term = $this->input->post('term');
        $id = $this->input->post('id');
        $results = [];
        if (!empty($ledger_id)) {
            $this->db->select("id, CONCAT(name, ' - ', focus_code) AS name");
            $this->db->from('master_cost_center');
            $this->db->where('status', 1);
            $this->db->where('parent_id', $ledger_id);
            if (!empty($id)) {
                $this->db->where('id', $id);
            } elseif (!empty($term)) {
                $this->db->group_start();
                $this->db->like('name', $term);
                $this->db->or_like('focus_code', $term);
                $this->db->group_end();
            }
            $this->db->limit(10);
            $q = $this->db->get();
            $results = $q->result();
        }
        echo json_encode($results);
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
        $department = $this->input->post('department');
        $billType = $this->input->post('billType');
        $location = $this->input->post('location');
        $locations = !empty($location) ? array_filter(explode(',', $location), 'strlen') : [];
        $this->db->select('u.user_id, CONCAT(u.first_name, " ", u.last_name) AS full_name');
        $this->db->from('tbl_user_permissions p');
        $this->db->join('users u', 'u.user_id = p.user_id', 'inner');
        $this->db->where('u.role_id', '4');
        $this->db->where('p.permission_type !=', 'Permission');
        $has_conditions = !empty($department) || !empty($billType) || !empty($locations);
        if ($has_conditions) {
            $this->db->group_start();
            if (!empty($department)) {
                $this->db->where('p.permission_type', 'Department');
                $this->db->where('p.permission_value', $department);
            }
            if (!empty($billType)) {
                $this->db->or_where('p.permission_type', 'Document');
                $this->db->where('p.permission_value', $billType);
            }
            if (!empty($locations)) {
                $this->db->or_where('p.permission_type', 'Location');
                $this->db->where_in('p.permission_value', $locations);
            }
            $this->db->group_end();
        }
        $this->db->group_by('u.user_id');
        $query = $this->db->get();
        echo json_encode($query->result());
    }
    public function save_approval_matrix()
    {
        $this->form_validation->set_rules('min_amount', 'Minimum Amount', 'numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('max_amount', 'Maximum Amount', 'numeric|greater_than_equal_to[0]|callback_check_amount_range');
        if ($this->form_validation->run() == false) {
            $response = ['status' => 'error', 'errors' => $this->form_validation->error_array()];
            echo json_encode($response);
            return;
        }
        $data = ['function' => $this->input->post('function'), 'validity_option' => $this->input->post('validity_option'), 'vertical' => $this->input->post('vertical'), 'department' => $this->input->post('department'), 'sub_department' => implode(',', $this->input->post('sub_department') ?? []), 'ledger' => $this->input->post('ledger'), 'subledger' => $this->input->post('subledger'), 'crop' => implode(',', $this->input->post('crop') ?? []), 'activity' => implode(',', $this->input->post('activity') ?? []), 'location' => implode(',', $this->input->post('location') ?? []), 'zone' => $this->input->post('zone'), 'sales_region' => $this->input->post('region'), 'business_unit' => $this->input->post('business_unit'), 'amount_min' => $this->input->post('min_amount'), 'amount_max' => $this->input->post('max_amount'), 'bill_type' => $this->input->post('bill_type'), 'l1_approver' => $this->input->post('l1_approver'), 'l2_approver' => $this->input->post('l2_approver'), 'l3_approver' => $this->input->post('l3_approver'), 'valid_from' => $this->input->post('valid_from'), 'valid_to' => $this->input->post('valid_to'), 'updated_by' => $this->session->userdata('user_id'),];
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
            return false;
        }
        return true;
    }
    public function check_date_range($valid_to)
    {
        $valid_from = $this->input->post('valid_from');
        if (strtotime($valid_to) < strtotime($valid_from)) {
            $this->form_validation->set_message('check_date_range', 'Valid To date must be greater than or equal to Valid From date.');
            return false;
        }
        return true;
    }
    public function employee()
    {
        if (!getRoutePermission("employee")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['employeelist'] = $this->BaseModel->getData('master_employee', ['is_deleted' => 'N'])->result_array();
        $this->data['main'] = 'super_admin/employee';
        $this->load->view('layout/template', $this->data);
    }
    public function sync_employee()
    {
        $is_nova_access = [1];
        $url = 'https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Employee';
        $json = @file_get_contents($url);
        if ($json === false) {
            echo json_encode(['status' => 400, 'message' => 'Failed to fetch data from remote server.',]);
            return;
        }
        $data = json_decode($json, true);
        if (!isset($data['employee_list']) || !is_array($data['employee_list'])) {
            echo json_encode(['status' => 400, 'message' => 'Invalid or empty data received from remote server.',]);
            return;
        }
        foreach ($data['employee_list'] as $value) {
            if ($value['CompanyId'] != 4) {
                $query = $this->BaseModel->getData('master_employee', ['company_id' => $value['CompanyId'], 'emp_code' => $value['EmpCode'], 'emp_vspl' => 'Y',]);
                $employee_data = ['emp_name' => trim($value['Fname'] . ' ' . $value['Sname'] . ' ' . $value['Lname']), 'status' => $value['EmpStatus'], 'is_nova_access' => in_array($value['EmpCode'], $is_nova_access) ? '1' : '0',];
                if ($query->num_rows() > 0) {
                    $this->BaseModel->updateData('master_employee', $employee_data, ['company_id' => $value['CompanyId'], 'emp_code' => $value['EmpCode'], 'emp_vspl' => 'Y',]);
                } else {
                    $user_id = $this->session->userdata('user_id');
                    $employee_data['emp_vspl'] = 'Y';
                    $employee_data['emp_code'] = $value['EmpCode'];
                    $employee_data['company_id'] = $value['CompanyId'];
                    $employee_data['created_by'] = $user_id;
                    $employee_data['is_nova_access'] = in_array($value['EmpCode'], $is_nova_access) ? '1' : '0';
                    $this->BaseModel->insertData('master_employee', $employee_data);
                }
                $is_nova = in_array($value['EmpCode'], $is_nova_access) ? '1' : '0';
                $user_query = $this->BaseModel->getData('users', ['username' => $value['EmpCode']]);
                $user_data = ['group_id' => $this->session->userdata('group_id'), 'first_name' => trim($value['Fname'] . ' ' . $value['Sname']), 'last_name' => $value['Lname'], 'username' => $value['EmpCode'], 'password' => md5($value['EmpCode']), 'status' => $is_nova === '1' ? 'A' : 'D', 'created_by' => $this->session->userdata('user_id'),];
                if ($user_query->num_rows() > 0) {
                    $user_data['updated_at'] = date('Y-m-d H:i:s');
                    $user_data['updated_by'] = $this->session->userdata('user_id');
                    $this->BaseModel->updateData('users', $user_data, ['username' => $value['EmpCode'],]);
                } elseif ($is_nova === '1') {
                    $user_data['created_at'] = date('Y-m-d H:i:s');
                    $this->BaseModel->insertData('users', $user_data);
                }
            }
        }
        echo json_encode(['status' => 200, 'message' => 'Employee and user sync completed']);
    }
    public function tag_control()
    {
        if (!getRoutePermission("tag_control")) {
            last_query();
            show_error('You do not have permission to access this page.', 403);
        }
        $data['departments'] = $this->db->get_where('core_department', ['is_active' => 1])->result_array();
        $data['document_type'] = $this->db->select('type_id, file_type')->from('master_doctype')->where_in('type_id', [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 56])->get()->result_array();
        $selected_doc_type = $this->input->get('doc_type') ? $this->input->get('doc_type') : 0;
        $data['selected_doc_type'] = $selected_doc_type;
        $data['mappings'] = $this->db->from('tbl_tag_control')->where('document_type_id', $selected_doc_type)->get()->result_array();
        $data['main'] = 'super_admin/tag_control';
        $this->load->view('layout/template', $data);
    }
    public function tag_control_update()
    {
        $department_id = $this->input->post('department_id');
        $document_type_id = $this->input->post('document_type_id');
        $field = $this->input->post('field');
        $checked = $this->input->post('checked');
        $this->db->trans_start();
        $data = ['updated_by' => $this->session->userdata('user_id'), 'updated_time' => date('Y-m-d H:i:s'), $field => $checked,];
        $existing = $this->db->from('tbl_tag_control')->where('document_type_id', $document_type_id == '0' ? 0 : $document_type_id)->where('department_id', $department_id)->get()->row_array();
        if ($existing) {
            $this->db->where('document_type_id', $document_type_id == '0' ? 0 : $document_type_id);
            $this->db->where('department_id', $department_id);
            $this->db->update('tbl_tag_control', $data);
        } else {
            $data['document_type_id'] = $document_type_id == '0' ? 0 : $document_type_id;
            $data['department_id'] = $department_id;
            $all_fields = ['ledger', 'subledger', 'vertical', 'activity', 'crop', 'business_unit', 'zone', 'region'];
            foreach ($all_fields as $f) {
                if ($f !== $field) {
                    $data[$f] = 'N';
                }
            }
            $this->db->insert('tbl_tag_control', $data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            echo json_encode(['status' => 'error']);
        } else {
            echo json_encode(['status' => 'success']);
        }
    }
    function set_permission($id)
    {
        if (!getRoutePermission("set_permission")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['user'] = $this->BaseModel->getData('users', ['user_id' => $id])->row_array();
        $this->data['user_permission'] = $this->BaseModel->getData('tbl_user_permissions', ['user_id' => $id])->result_array();
        $this->data['main'] = 'super_admin/set_permission';
        $this->load->view('layout/template', $this->data);
    }
    public function permissions_data($user_id)
    {
        $data = [
            'permissions' => $this->db->select('tbl_permissions.permission_id, tbl_permissions.permission_name')->from('tbl_permissions')->join('tbl_role_permissions', 'tbl_permissions.permission_id = tbl_role_permissions.permission_id', 'left')->join('users', 'users.role_id = tbl_role_permissions.role_id', 'left')->where('users.user_id', $user_id)->where('tbl_permissions.status', 1)->get()->result_array(),
            'documents' => $this->BaseModel->getData('master_doctype', ['type_id IN (1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 56)'])->result_array(),
            'departments' => $this->BaseModel->getData('core_department', ['is_active' => 1])->result_array(),
            'locations' => $this->BaseModel->getData('master_work_location', ['status' => 'A'])->result_array(),
            'user_permissions' => $this->BaseModel->getData('tbl_user_permissions', ['user_id' => $user_id])->result_array()
        ];
        echo json_encode($data);
    }
    public function save_permissions()
    {
        $user_id = $this->input->post('user_id');
        $permissions = $this->input->post('permissions');
        $documents = $this->input->post('documents');
        $departments = $this->input->post('departments');
        $locations = $this->input->post('locations');
        $created_by = $this->session->userdata('user_id');
        $this->db->where('user_id', $user_id)->delete('tbl_user_permissions');
        $insert_data = [];
        if (!empty($permissions)) {
            foreach ($permissions as $perm_id) {
                $insert_data[] = ['user_id' => $user_id, 'permission_type' => 'Permission', 'permission_value' => $perm_id, 'created_by' => $created_by];
            }
        }
        if (!empty($documents)) {
            foreach ($documents as $doc_id) {
                $insert_data[] = ['user_id' => $user_id, 'permission_type' => 'Document', 'permission_value' => $doc_id, 'created_by' => $created_by];
            }
        }
        if (!empty($departments)) {
            foreach ($departments as $dept_id) {
                $insert_data[] = ['user_id' => $user_id, 'permission_type' => 'Department', 'permission_value' => $dept_id, 'created_by' => $created_by];
            }
        }
        if (!empty($locations)) {
            foreach ($locations as $loc_id) {
                $insert_data[] = ['user_id' => $user_id, 'permission_type' => 'Location', 'permission_value' => $loc_id, 'created_by' => $created_by];
            }
        }
        if (!empty($insert_data)) {
            $this->db->insert_batch('tbl_user_permissions', $insert_data);
        }
        echo json_encode(['status' => 'success', 'message' => 'Permissions saved successfully']);
    }
    public function ledger()
    {
        if ($this->input->is_ajax_request()) {
            $draw = $this->input->post('draw');
            $start = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search')['value'] ?? '';
            $group = $this->input->post('group') ?? '';
            $total_rows = $this->AdminModel->get_ledger_count();
            $filtered_rows = $this->AdminModel->get_ledger_count($search, $group);
            $list = $this->AdminModel->get_ledger_list($length, $start, $search, $group);
            $response = ["draw" => intval($draw), "recordsTotal" => $total_rows, "recordsFiltered" => $filtered_rows, "data" => $list];
            echo json_encode($response);
            exit();
        }
        $this->data['main'] = 'super_admin/ledger';
        $this->load->view('layout/template', $this->data);
    }
    public function sub_ledger()
    {
        if ($this->input->is_ajax_request()) {
            $draw = $this->input->post('draw');
            $start = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search')['value'] ?? '';
            $group = $this->input->post('group') ?? '';
            $total_rows = $this->AdminModel->get_sub_ledger_count();
            $filtered_rows = $this->AdminModel->get_sub_ledger_count($search, $group);
            $list = $this->AdminModel->get_sub_ledger_list($length, $start, $search, $group);
            $response = ["draw" => intval($draw), "recordsTotal" => $total_rows, "recordsFiltered" => $filtered_rows, "data" => $list];
            echo json_encode($response);
            exit;
        }
        $this->data['main'] = 'super_admin/sub_ledger';
        $this->load->view('layout/template', $this->data);
    }
    public function update_sub_ledger()
    {
        $id = $this->input->post('id');
        $data = [];
        if ($this->input->post('focus_code') !== null) {
            $data['focus_code'] = $this->input->post('focus_code');
        }
        if ($this->input->post('parent_id') !== null) {
            $data['parent_id'] = $this->input->post('parent_id');
            $data['parent_name'] = $this->input->post('parent_name');
        }
        if (!empty($data) && $id) {
            $this->BaseModel->updateData('master_cost_center', $data, ['id' => $id]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'fail', 'message' => 'Invalid data']);
        }
    }
    public function business_entity($id = null)
    {
        if (!getRoutePermission("business_entity")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if ($id) {
            $data['business_entity'] = $this->BaseModel->getData('master_business_entity', ['business_entity_id' => $id])->row_array();
        } else {
            $data['business_entity'] = [];
        }
        $data['main'] = 'super_admin/business_entity';
        $data['business_entity_list'] = $this->BaseModel->getData('master_business_entity', ['is_deleted' => 'N'])->result_array();
        $this->load->view('layout/template', $data);
    }
    public function save_business_entity($id = null)
    {
        $this->form_validation->set_rules('business_entity_name', 'Business Entity Name', 'trim|required');
        $this->form_validation->set_rules('focus_code', 'Business Entity Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'super_admin/business_entity';
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['business_entity_name' => $this->input->post('business_entity_name'), 'focus_code' => $this->input->post('focus_code'), 'status' => $this->input->post('status')];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_business_entity', $data, ['business_entity_id' => $id]);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Business Entity Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->insertData('master_business_entity', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Business Entity Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create business entity.</p>');
                }
            }
            redirect('business_entity');
        }
    }
    public function delete_business_entity($id = null)
    {
        if (!getRoutePermission("business_entity")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid business entity ID.</p>');
            redirect('business_entity');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_business_entity', $data, ['business_entity_id' => $id, 'is_deleted' => 'N']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Business Entity Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete business entity or entity not found.</p>');
        }
        redirect('business_entity');
    }
    public function roles()
    {
        if (!getRoutePermission("roles")) {
            show_error('You do not have permission to access this page.', 403);
        }

        // Fetch active permissions
        $data['permissions'] = $this->BaseModel->getData('tbl_permissions', ['status' => 1])->result_array();

        // Fetch active roles from tbl_roles
        $data['roles'] = $this->BaseModel->getData('tbl_roles', ['status' => 1])->result_array();

        // Fetch role-permission mappings
        $this->db->select('role_id, permission_id');
        $this->db->from('tbl_role_permissions');
        $query = $this->db->get();
        $result = $query->result_array();
        $role_permissions = [];
        foreach ($result as $row) {
            $role_permissions[$row['role_id']][] = $row;
        }
        $data['role_permissions'] = $role_permissions;
        $data['main'] = 'super_admin/roles';
        $this->load->view('layout/template', $data);
    }

    public function assign_role_permission($role_id, $permission_id)
    {
        $data = [
            'role_id' => $role_id,
            'permission_id' => $permission_id,
            'assigned_at' => date('Y-m-d H:i:s'),
            'assigned_by' => $this->session->userdata('user_id')
        ];
        return $this->db->insert('tbl_role_permissions', $data);
    }

    public function remove_role_permission($role_id, $permission_id)
    {
        $this->db->where('role_id', $role_id);
        $this->db->where('permission_id', $permission_id);
        return $this->db->delete('tbl_role_permissions');
    }
    public function assign_permission()
    {
        $role_id = $this->input->post('role_id');
        $permission_id = $this->input->post('permission_id');
        $action = $this->input->post('action');

        if ($action === 'assign') {
            $result = $this->assign_role_permission($role_id, $permission_id);
        } else {
            $result = $this->remove_role_permission($role_id, $permission_id);
        }

        if ($result) {
            $response = ['status' => 'success', 'message' => 'Permission updated successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to update permission'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    public function user($id = null)
    {
        if (!getRoutePermission("user")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['user'] = $id ? $this->BaseModel->getData('users', ['user_id' => $id, 'status' => 'A'])->row_array() : [];
        if ($id && empty($this->data['user'])) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">User not found.</p>');
            redirect('user');
        }
        $this->data['users_list'] = $this->BaseModel->getJoinData('users u', ['table' => 'tbl_roles r', 'condition' => 'u.role_id = r.id', 'type' => 'left'], ['u.status' => 'A', 'u.user_id !=' => 1], 'u.*, r.role_name, u.user_id AS ID', 'u.user_id')->result_array();
        $this->data['role_list'] = $this->BaseModel->getData('tbl_roles')->result_array();
        $this->data['main'] = 'super_admin/user';
        $this->load->view('layout/template', $this->data);
    }
    public function save_user($id = null)
    {
        if (!getRoutePermission("user")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[60]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[60]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[60]' . ($id ? '' : '|is_unique[users.username]'));
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        if (!$id) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        }
        if ($this->form_validation->run() == false) {
            $this->user($id);
        } else {
            $data = [
                'first_name' => $this->input->post('first_name', true),
                'last_name' => $this->input->post('last_name', true),
                'username' => $this->input->post('username', true),
                'role_id' => $this->input->post('role_id', true),
                'status' => 'A',
                'password' => md5($this->input->post('password'))
            ];
            if ($id) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('users', $data, ['user_id' => $id, 'status' => 'A']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">User Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->insertData('users', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">User Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create user.</p>');
                }
            }
            redirect('user');
        }
    }
    public function delete_user($id = null)
    {
        if (!getRoutePermission("user")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id) || $id == 1) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid user ID or cannot delete default user.</p>');
            redirect('user');
        }
        $data = ['status' => 'D', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('users', $data, ['user_id' => $id, 'status' => 'A']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">User Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete user or user not found.</p>');
        }
        redirect('user');
    }
    public function get_user_details($id = null)
    {
        if (!getRoutePermission("user")) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['error' => 'You do not have permission to access this resource.']));
            return;
        }
        if (empty($id) || !is_numeric($id)) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid user ID.']));
            return;
        }
        $user = $this->BaseModel->getJoinData('users u', ['table' => 'tbl_roles r', 'condition' => 'u.role_id = r.id', 'type' => 'left'], ['u.user_id' => $id, 'u.status' => 'A'], 'u.*, r.role_name')->row_array();
        if (!$user) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error' => 'User not found.']));
            return;
        }
        $user['status_label'] = $user['status'] == 'A' ? 'Active' : 'Deactive';
        $user['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $this->output->set_content_type('application/json')->set_output(json_encode($user));
    }
    public function menu_mapping()
    {
        if (!getRoutePermission("menu_mapping")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['main'] = 'super_admin/menu_mapping';
        $this->data['menu_list'] = $this->BaseModel->getData('tbl_menus', ['is_active' => 1])->result_array();
        $this->data['permission_list'] = $this->BaseModel->getData('tbl_permissions', ['status' => 1])->result_array();
        $this->load->view('layout/template', $this->data);
    }
    public function activity_dep_mapping()
    {
        $data['departments'] = $this->BaseModel->getData('core_department', ['is_active' => 1])->result_array();
        $data['activities'] = $this->BaseModel->getData('core_activity', ['is_active' => 1])->result_array();
        $data['mappings'] = $this->BaseModel->getData('tbl_department_activity_mapping')->result_array();
        $data['main'] = 'super_admin/activity_dep_mapping';
        $this->load->view('layout/template', $data);
    }
    public function location($id = null)
    {
        if (!getRoutePermission("location")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if ($id) {
            $data['location'] = $this->BaseModel->getData('master_work_location', ['location_id' => $id])->row_array();
        } else {
            $data['location'] = [];
        }
        $data['main'] = 'super_admin/location';
        $data['location_list'] = $this->BaseModel->getData('master_work_location', ['is_deleted' => 'N'])->result_array();
        $this->load->view('layout/template', $data);
    }
    public function save_location($id = null)
    {
        $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required');
        $this->form_validation->set_rules('focus_code', 'Location Code', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[A,D]');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'super_admin/location';
            if ($id) {
                $this->data['location'] = $this->BaseModel->getData('master_work_location', ['location_id' => $id, 'is_deleted' => 'N'])->row_array();
            } else {
                $this->data['location'] = [];
            }
            $this->data['location_list'] = $this->BaseModel->getData('master_work_location', ['is_deleted' => 'N'])->result_array();
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['location_name' => $this->input->post('location_name', true), 'focus_code' => $this->input->post('focus_code', true), 'status' => $this->input->post('status', true)];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_work_location', $data, ['location_id' => $id, 'is_deleted' => 'N']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Location Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_deleted'] = 'N';
                $result = $this->BaseModel->insertData('master_work_location', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Location Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create business entity.</p>');
                }
            }
            redirect('location');
        }
    }
    public function delete_location($id = null)
    {
        if (!getRoutePermission("location")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid location ID.</p>');
            redirect('location');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_work_location', $data, ['location_id' => $id, 'is_deleted' => 'N']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Location Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete Location or location not found.</p>');
        }
        redirect('location');
    }
    public function vendors($id = null)
    {
        if (!getRoutePermission("vendors")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if ($id) {
            $data['vendor'] = $this->BaseModel->getData('master_firm', ['firm_id' => $id, 'firm_type' => 'Vendor'])->row_array();
        } else {
            $data['vendor'] = [];
        }
        $data['main'] = 'super_admin/vendors';
        $data['vendor_list'] = $this->BaseModel->getData('master_firm', ['is_deleted' => 'N', 'firm_type' => 'Vendor'])->result_array();
        $this->load->view('layout/template', $data);
    }
    public function save_vendor($id = null)
    {
        if (!getRoutePermission("vendors")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('firm_type', 'Firm Type', 'trim|required|in_list[Company,Vendor,Farmer]');
        $this->form_validation->set_rules('focus_code', 'Focus Code', 'trim');
        $this->form_validation->set_rules('gst', 'GST', 'trim|max_length[30]');
        $this->form_validation->set_rules('address', 'Address', 'trim|max_length[65535]');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required|integer');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required|integer');
        $this->form_validation->set_rules('city_name', 'City', 'trim|max_length[60]');
        $this->form_validation->set_rules('pin_code', 'Pin Code', 'trim|max_length[10]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[A,D,R]');
        if ($this->form_validation->run() == false) {
            $this->data['vendor'] = $id ? $this->BaseModel->getData('master_firm', ['firm_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
            $this->data['vendor_list'] = $this->BaseModel->getData('master_firm', ['is_deleted' => 'N', 'firm_type' => 'Vendor'])->result_array();
            $this->data['countries'] = $this->BaseModel->getData('master_country', ['is_deleted' => 'N'])->result_array();
            $this->data['states'] = $this->BaseModel->getData('master_state', ['is_deleted' => 'N'])->result_array();
            $this->data['main'] = 'super_admin/vendor';
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['firm_name' => $this->input->post('firm_name', true), 'firm_type' => $this->input->post('firm_type', true), 'focus_code' => $this->input->post('focus_code', true) ?: null, 'gst' => $this->input->post('gst', true) ?: null, 'address' => $this->input->post('address', true) ?: null, 'country_id' => $this->input->post('country_id', true), 'state_id' => $this->input->post('state_id', true), 'city_name' => $this->input->post('city_name', true) ?: null, 'pin_code' => $this->input->post('pin_code', true) ?: null, 'status' => $this->input->post('status', true), 'focus_data' => 'N', 'msme_status' => 'N'];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_firm', $data, ['firm_id' => $id, 'is_deleted' => 'N']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Firm Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_deleted'] = 'N';
                $result = $this->BaseModel->insertData('master_firm', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Firm Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create firm.</p>');
                }
            }
            redirect('vendors');
        }
    }
    public function delete_vendor($id = null)
    {
        if (!getRoutePermission("vendors")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid vendor ID.</p>');
            redirect('vendors');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_firm', $data, ['firm_id' => $id, 'is_deleted' => 'N', 'firm_type' => 'Vendor']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Vendor Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete vendor or vendor not found.</p>');
        }
        redirect('vendors');
    }
    public function get_vendor_details($id = null)
    {
        if (!getRoutePermission("vendors")) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['error' => 'You do not have permission to access this resource.']));
            return;
        }
        if (empty($id) || !is_numeric($id)) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid vendor ID.']));
            return;
        }
        $vendor = $this->BaseModel->getData('master_firm', ['firm_id' => $id, 'is_deleted' => 'N', 'firm_type' => 'Vendor'])->row_array();
        if (!$vendor) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error' => 'Vendor not found.']));
            return;
        }
        $country = $this->BaseModel->getData('core_country', ['api_id' => $vendor['country_id']])->row_array();
        $state = $this->BaseModel->getData('core_state', ['api_id' => $vendor['state_id']])->row_array();
        $vendor['country_name'] = $country ? $country['country_name'] : 'N/A';
        $vendor['state_name'] = $state ? $state['state_name'] : 'N/A';
        $vendor['status_label'] = $vendor['status'] == 'A' ? 'Active' : ($vendor['status'] == 'D' ? 'Deactive' : 'Rejected');
        $this->output->set_content_type('application/json')->set_output(json_encode($vendor));
    }
    public function hotel($id = null)
    {
        if (!getRoutePermission("hotel")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['hotel'] = $id ? $this->BaseModel->getData('master_hotel', ['hotel_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
        if ($id && empty($this->data['hotel'])) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Hotel not found.</p>');
            redirect('hotel');
        }
        $this->data['hotel_list'] = $this->BaseModel->getData('master_hotel', ['is_deleted' => 'N'])->result_array();
        $this->data['main'] = 'super_admin/hotel';
        $this->load->view('layout/template', $this->data);
    }
    public function save_hotel($id = null)
    {
        if (!getRoutePermission("hotel")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('hotel_name', 'Hotel Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'trim|max_length[65535]');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|integer');
        $this->form_validation->set_rules('state_id', 'State', 'trim|integer');
        $this->form_validation->set_rules('city_name', 'City', 'trim|required|max_length[70]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[A,D]');
        if ($this->form_validation->run() == false) {
            $this->data['hotel'] = $id ? $this->BaseModel->getData('master_hotel', ['hotel_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
            $this->data['hotel_list'] = $this->BaseModel->getData('master_hotel', ['is_deleted' => 'N'])->result_array();
            $this->data['main'] = 'super_admin/hotel';
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['hotel_name' => $this->input->post('hotel_name', true), 'address' => $this->input->post('address', true) ?: null, 'country_id' => $this->input->post('country_id', true) ?: null, 'state_id' => $this->input->post('state_id', true) ?: null, 'city_name' => $this->input->post('city_name', true), 'status' => $this->input->post('status', true)];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_hotel', $data, ['hotel_id' => $id, 'is_deleted' => 'N']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Hotel Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_deleted'] = 'N';
                $result = $this->BaseModel->insertData('master_hotel', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Hotel Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create hotel.</p>');
                }
            }
            redirect('hotel');
        }
    }
    public function delete_hotel($id = null)
    {
        if (!getRoutePermission("hotel")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid hotel ID.</p>');
            redirect('hotel');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_hotel', $data, ['hotel_id' => $id, 'is_deleted' => 'N']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Hotel Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete hotel or hotel not found.</p>');
        }
        redirect('hotel');
    }
    public function get_hotel_details($id = null)
    {
        if (!getRoutePermission("hotel")) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['error' => 'You do not have permission to access this resource.']));
            return;
        }
        if (empty($id) || !is_numeric($id)) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid hotel ID.']));
            return;
        }
        $hotel = $this->BaseModel->getData('master_hotel', ['hotel_id' => $id, 'is_deleted' => 'N'])->row_array();
        if (!$hotel) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error' => 'Hotel not found.']));
            return;
        }
        if ($hotel['state_id']) {
            $state = $this->BaseModel->getData('master_state', ['state_id' => $hotel['state_id'], 'is_deleted' => 'N'])->row_array();
            $hotel['state_name'] = $state ? $state['state_name'] : 'N/A';
            $country = $state ? $this->BaseModel->getData('master_country', ['country_id' => $state['country_id'], 'is_deleted' => 'N'])->row_array() : null;
            $hotel['country_name'] = $country ? $country['country_name'] : 'N/A';
        } else {
            $hotel['state_name'] = 'N/A';
            $hotel['country_name'] = 'N/A';
        }
        $hotel['status_label'] = $hotel['status'] == 'A' ? 'Active' : 'Deactive';
        $this->output->set_content_type('application/json')->set_output(json_encode($hotel));
    }
    public function item($id = null)
    {
        if (!getRoutePermission("item")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['item'] = $id ? $this->BaseModel->getData('master_item', ['item_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
        if ($id && empty($this->data['item'])) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Item not found.</p>');
            redirect('item');
        }
        $this->data['item_list'] = $this->BaseModel->getData('master_item', ['is_deleted' => 'N'])->result_array();
        $this->data['main'] = 'super_admin/item';
        $this->load->view('layout/template', $this->data);
    }
    public function save_item($id = null)
    {
        if (!getRoutePermission("item")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('item_code', 'Item Code', 'trim|required|max_length[60]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[A,D]');
        if ($this->form_validation->run() == false) {
            $this->data['item'] = $id ? $this->BaseModel->getData('master_item', ['item_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
            $this->data['item_list'] = $this->BaseModel->getData('master_item', ['is_deleted' => 'N'])->result_array();
            $this->data['main'] = 'super_admin/item';
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['item_name' => $this->input->post('item_name', true), 'item_code' => $this->input->post('item_code', true), 'focus_data' => 'N', 'status' => $this->input->post('status', true), 'Import_Flag' => '0'];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_item', $data, ['item_id' => $id, 'is_deleted' => 'N']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Item Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_deleted'] = 'N';
                $result = $this->BaseModel->insertData('master_item', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Item Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create item.</p>');
                }
            }
            redirect('item');
        }
    }
    public function delete_item($id = null)
    {
        if (!getRoutePermission("item")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid item ID.</p>');
            redirect('item');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_item', $data, ['item_id' => $id, 'is_deleted' => 'N']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Item Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete item or item not found.</p>');
        }
        redirect('item');
    }
    public function get_item_details($id = null)
    {
        if (!getRoutePermission("item")) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['error' => 'You do not have permission to access this resource.']));
            return;
        }
        if (empty($id) || !is_numeric($id)) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid item ID.']));
            return;
        }
        $item = $this->BaseModel->getData('master_item', ['item_id' => $id, 'is_deleted' => 'N'])->row_array();
        if (!$item) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error' => 'Item not found.']));
            return;
        }
        $item['status_label'] = $item['status'] == 'A' ? 'Active' : 'Deactive';
        $item['focus_data_label'] = $item['focus_data'] == 'Y' ? 'Yes' : 'No';
        $item['import_flag_label'] = $item['Import_Flag'] == '1' ? 'Yes' : 'No';
        $this->output->set_content_type('application/json')->set_output(json_encode($item));
    }
    public function unit($id = null)
    {
        if (!getRoutePermission("unit")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data['unit'] = $id ? $this->BaseModel->getData('master_unit', ['unit_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
        if ($id && empty($this->data['unit'])) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Unit not found.</p>');
            redirect('unit');
        }
        $this->data['unit_list'] = $this->BaseModel->getData('master_unit', ['is_deleted' => 'N'])->result_array();
        $this->data['main'] = 'super_admin/unit';
        $this->load->view('layout/template', $this->data);
    }
    public function save_unit($id = null)
    {
        if (!getRoutePermission("unit")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required|max_length[40]');
        $this->form_validation->set_rules('unit_code', 'Unit Code', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[A,D]');
        if ($this->form_validation->run() == false) {
            $this->data['unit'] = $id ? $this->BaseModel->getData('master_unit', ['unit_id' => $id, 'is_deleted' => 'N'])->row_array() : [];
            $this->data['unit_list'] = $this->BaseModel->getData('master_unit', ['is_deleted' => 'N'])->result_array();
            $this->data['main'] = 'super_admin/unit';
            $this->load->view('layout/template', $this->data);
        } else {
            $data = ['unit_name' => $this->input->post('unit_name', true), 'unit_code' => $this->input->post('unit_code', true), 'status' => $this->input->post('status', true)];
            if (!empty($id)) {
                $data['updated_by'] = $this->session->userdata('user_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = $this->BaseModel->updateData('master_unit', $data, ['unit_id' => $id, 'is_deleted' => 'N']);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Unit Updated Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-warning text-center">No changes made or update failed.</p>');
                }
            } else {
                $data['created_by'] = $this->session->userdata('user_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_deleted'] = 'N';
                $result = $this->BaseModel->insertData('master_unit', $data);
                if ($result) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Unit Created Successfully.</p>');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to create unit.</p>');
                }
            }
            redirect('unit');
        }
    }
    public function delete_unit($id = null)
    {
        if (!getRoutePermission("unit")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid unit ID.</p>');
            redirect('unit');
        }
        $data = ['is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')];
        $result = $this->BaseModel->updateData('master_unit', $data, ['unit_id' => $id, 'is_deleted' => 'N']);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Unit Deleted Successfully.</p>');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Failed to delete unit or unit not found.</p>');
        }
        redirect('unit');
    }
    public function get_unit_details($id = null)
    {
        if (!getRoutePermission("unit")) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['error' => 'You do not have permission to access this resource.']));
            return;
        }
        if (empty($id) || !is_numeric($id)) {
            $this->output->set_status_header(400)->set_content_type('application/json')->set_output(json_encode(['error' => 'Invalid unit ID.']));
            return;
        }
        $unit = $this->BaseModel->getData('master_unit', ['unit_id' => $id, 'is_deleted' => 'N'])->row_array();
        if (!$unit) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error' => 'Unit not found.']));
            return;
        }
        $unit['status_label'] = $unit['status'] == 'A' ? 'Active' : 'Deactive';
        $this->output->set_content_type('application/json')->set_output(json_encode($unit));
    }
}
