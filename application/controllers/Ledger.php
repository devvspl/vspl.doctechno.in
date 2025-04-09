<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ledger extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Ledger_model');
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
        $this->session->set_userdata('sub_menu', 'ledger');
        $this->data['main'] = 'ledger/ledgerlist';
        $this->data['ledgerlist'] = $this->Ledger_model->get_ledger_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('ledger_name', 'Ledger Name', 'trim|required');
        $this->form_validation->set_rules('ledger_code', 'Ledger Code', 'trim|required');
        $this->form_validation->set_rules('ledger_head', 'Ledger Head', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'ledger/ledgerlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['ledger_name'] = $this->input->post('ledger_name');
            $data['ledger_code'] = $this->input->post('ledger_code');
            $data['ledger_head'] = $this->input->post('ledger_head');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Ledger_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Ledger Created Successfully.</p>');
                redirect('ledger');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('ledger');
            }
        }
    }

    function delete($id)
    {
        $this->Ledger_model->delete($id);
        redirect('ledger');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'ledger');
        $this->data['ledgerlist'] = $this->Ledger_model->get_ledger_list();
        $this->data['ledger'] = $this->Ledger_model->get_ledger_list($id);
        $this->data['ledger_id'] = $id;
        $this->data['main'] = 'ledger/ledgeredit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['ledger_id'] = $id;
        $data['ledger_name'] = $this->input->post('ledger_name');
        $data['ledger_code'] = $this->input->post('ledger_code');
        $data['ledger_head'] = $this->input->post('ledger_head');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Ledger_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Ledger Updated Successfully.</p>');
            redirect('ledger');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('ledger');
        }
    }

   

}
