<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
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
        $this->data['main'] = 'admin/approval-matrix';
        $this->load->view('layout/template', $this->data);
    }

    public function addApprovalMatrix()
    {
        $this->data['main'] = 'admin/add-approval-matrix';
        $this->load->view('layout/template', $this->data);
    }

}
