<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UserController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model(array('User_model', 'Group_model'));
    }
    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index()
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'user');
        $this->data['main'] = 'user/userlist';
        $this->data['userlist'] = $this->User_model->get_user_list();
        $this->data['role_list'] = $this->User_model->role_list();
        $this->load->view('layout/template', $this->data);
    }
    public function create($id = null)
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('role', 'User Role', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        if ($id === null) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'user/userlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['created_by'] = $this->session->userdata('user_id');
            $data['first_name'] = ucfirst($this->input->post('first_name'));
            $data['last_name'] = ucfirst($this->input->post('last_name'));
            $data['username'] = $this->input->post('username');
            $data['password'] = md5($this->input->post('password'));
            $data['role_id'] = $this->input->post('role');
            $data['group_id'] = $this->session->userdata("group_id") ?? 16;
            $result = $this->User_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">User Created Successfully.</p>');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            }
            redirect('user');
        }
    }
    function delete($id)
    {
        $this->User_model->delete($id);
        redirect('user');
    }
    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'user');
        $this->data['userlist'] = $this->User_model->get_user_list();
        $this->data['role_list'] = $this->User_model->role_list();
        $this->data['user'] = $this->User_model->get_user_list($id);
        $this->data['id'] = $id;
        $this->data['main'] = 'user/useredit';
        $this->load->view('layout/template', $this->data);
    }
    public function update($id)
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('role', 'User Role', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'user/userlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['user_id'] = $id;
            $data['first_name'] = ucfirst($this->input->post('first_name'));
            $data['last_name'] = ucfirst($this->input->post('last_name'));
            $data['role_id'] = $this->input->post('role');
            $data['updated_by'] = $this->session->userdata('user_id');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $result = $this->User_model->update($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">User Updated Successfully.</p>');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            }
            redirect('user');
        }
    }
    function permission($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'user');
        $this->data['user'] = $this->User_model->get_user_list($id);
        $this->data['user_permission'] = $this->User_model->user_permission_list($id);
        $this->data['id'] = $id;
        $this->data['main'] = 'user/set_permission';
        $this->load->view('layout/template', $this->data);
    }
    public function get_permissions_data($user_id)
    {
        $data = ['permissions' => $this->db->select('tbl_permissions.permission_id, tbl_permissions.permission_name')->from('tbl_permissions')->join('tbl_role_permissions', 'tbl_permissions.permission_id = tbl_role_permissions.permission_id', 'left')->join('users', 'users.role_id = tbl_role_permissions.role_id', 'left')->where('users.user_id', $user_id)->where('tbl_permissions.status', 1)->get()->result_array(), 'documents' => $this->db->select('type_id, file_type')->from('master_doctype')->where_in('type_id', [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 56])->get()->result_array(), 'departments' => $this->db->select('api_id, department_name, department_code')->from('core_department')->where('is_active', 1)->get()->result_array(), 'locations' => $this->db->select('location_id, location_name')->from('master_work_location')->where('status', 'A')->get()->result_array(), 'user_permissions' => $this->User_model->user_permission_list($user_id)];
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
    function set_permission()
    {
        $user_id = $this->input->post('user_id');
        $permission_id = $this->input->post('permission');
        $result = $this->User_model->set_permission($user_id, $permission_id);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Permission Updated Successfully.</p>');
            redirect('master/UserController/permission/' . $user_id);
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('master/UserController/permission/' . $user_id);
        }
    }
    public function menuMapping()
    {
        $this->data['main'] = 'user/menu_mapping';
        $this->data['menu_list'] = $this->db->get('tbl_menus')->result_array();
        $this->data['permission_list'] = $this->db->select('permission_id, permission_name')->from('tbl_permissions')->where('status', 1)->where('permission_id !=', 8)->get()->result_array();
        $this->load->view('layout/template', $this->data);
    }

    // New method to handle AJAX updates
    public function updateMenuPermission()
    {
        $menu_id = $this->input->post('menu_id');
        $permission_id = $this->input->post('permission_id');
        $checked = $this->input->post('checked') === 'true' ? true : false;

        // Fetch current permissions for the menu
        $menu = $this->db->get_where('tbl_menus', ['id' => $menu_id])->row_array();
        $menu_permissions = json_decode($menu['permission_ids'], true);
        if (!is_array($menu_permissions)) {
            $menu_permissions = [];
        }

        if ($checked) {
            // Add permission if checked
            if (!in_array($permission_id, $menu_permissions)) {
                $menu_permissions[] = $permission_id;
            }
        } else {
            // Remove permission if unchecked
            $menu_permissions = array_filter($menu_permissions, function ($id) use ($permission_id) {
                return $id != $permission_id;
            });
            $menu_permissions = array_values($menu_permissions); // Reindex array
        }

        // Update the menu with new permissions
        $json_permissions = json_encode($menu_permissions);
        $this->db->where('id', $menu_id);
        $this->db->update('tbl_menus', ['permission_ids' => $json_permissions]);

        echo json_encode(['status' => 'success']);
    }

    public function saveMenuMapping()
    {
        // This method is no longer needed, but keeping it for reference in case you revert
        $permissions = $this->input->post('permissions');
        $all_menus = $this->db->get('tbl_menus')->result_array();

        if (!empty($all_menus)) {
            foreach ($all_menus as $menu) {
                $menu_id = $menu['id'];
                if (isset($permissions[$menu_id])) {
                    $permission_values = $permissions[$menu_id];
                    $json_permissions = json_encode($permission_values);
                } else {
                    $json_permissions = json_encode([]);
                }
                $this->db->where('id', $menu_id);
                $this->db->update('tbl_menus', ['permission_ids' => $json_permissions]);
            }
            $this->session->set_flashdata('success', 'Menu mapping updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'No menus found to update!');
        }
        redirect('menu-mapping');
    }

    public function activityDepMapping()
    {
        $data['departments'] = $this->get_departments();
        $data['activities'] = $this->get_activities();
        $data['mappings'] = $this->get_mappings();
        $data['main'] = 'user/activity_dep_mapping';
        $this->load->view('layout/template', $data);
    }

    public function get_departments()
    {
        return $this->db->get_where('core_department', ['is_active' => 1])->result_array();
    }

    public function get_activities()
    {
        return $this->db->get_where('core_activity', ['is_active' => 1])->result_array();
    }

    public function get_mappings()
    {
        return $this->db->get('tbl_department_activity_mapping')->result_array();
    }

    public function delete_mapping($department_id)
    {
        $this->db->where('department_id', $department_id);
        $this->db->delete('tbl_department_activity_mapping');
    }

    public function insert_mapping($department_id, $activity_id)
    {
        $dept = $this->db->get_where('core_department', ['api_id' => $department_id])->row_array();
        $activity = $this->db->get_where('core_activity', ['api_id' => $activity_id])->row_array();
        $data = [
            'department_id' => $department_id,
            'activity_id' => $activity_id,
            'department_name' => $dept['department_name'],
            'activity_name' => $activity['activity_name'],
        ];
        $this->db->insert('tbl_department_activity_mapping', $data);
    }

    // New method to handle AJAX updates
    public function updateMapping()
    {
        $department_id = $this->input->post('department_id');
        $activity_id = $this->input->post('activity_id');
        $checked = $this->input->post('checked') === 'true' ? true : false;

        if ($checked) {
            // Insert mapping if checked
            $this->insert_mapping($department_id, $activity_id);
        } else {
            // Delete mapping if unchecked
            $this->db->where('department_id', $department_id);
            $this->db->where('activity_id', $activity_id);
            $this->db->delete('tbl_department_activity_mapping');
        }

        echo json_encode(['status' => 'success']);
    }
}
