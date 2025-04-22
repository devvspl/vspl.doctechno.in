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
        $this->data['grouplist'] = $this->Group_model->get_group_list();

        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('role', 'User Role', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'user/userlist';
            $this->load->view('layout/template', $this->data);
        } else {

            if ($_SESSION['role'] == 'super_admin') {
                $group_id = $this->input->post('group');
            } else {
                $group_id = $_SESSION['group_id'];
            }
            $data['created_by'] = $this->session->userdata('user_id');
            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['username'] = $this->input->post('username');
            $data['password'] = md5($this->input->post('password'));
            $data['role'] = $this->input->post('role');
            $data['group_id'] = $group_id;
            $result = $this->User_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">User Created Successfully.</p>');
                redirect('user');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('user');
            }
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
        $this->data['user'] = $this->User_model->get_user_list($id);
        $this->data['id'] = $id;
        $this->data['main'] = 'user/useredit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['user_id'] = $id;
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['username'] = $this->input->post('username');
        $data['role'] = $this->input->post('role');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->User_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">User Updated Successfully.</p>');
            redirect('user');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('user');
        }
    }

    function permission($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'user');
        $this->data['user'] = $this->User_model->get_user_list($id);
        $this->data['category'] = [
            '0' => 'Access',
            '1' => 'Doc Type',
        ];
        $this->data['permissionlist'] = $this->User_model->get_permission_list();
        $this->data['user_permission'] = $this->User_model->user_permission_list($id);
        $this->data['id'] = $id;
        $this->data['main'] = 'user/set_permission';
        $this->load->view('layout/template', $this->data);
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
}
