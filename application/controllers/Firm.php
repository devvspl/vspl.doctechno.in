<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Firm extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Firm_model');
        $this->load->model('Country_model');
        $this->load->model('State_model');
        $this->load->library('form_validation');
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
        $this->session->set_userdata('sub_menu', 'firm');
        $this->data['main'] = 'firm/firmlist';
        $this->data['firmlist'] = $this->Firm_model->get_firm_list();
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {
        $this->form_validation->set_rules('firm_type', 'Firm Type', 'trim|required');
        $this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'firm/firmlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['firm_type'] = $this->input->post('firm_type');
            $data['firm_name'] = $this->input->post('firm_name');
            $data['firm_code'] = $this->input->post('firm_code');
            $data['country_id'] = $this->input->post('country_id');
            $data['state_id'] = $this->input->post('state_id');
            $data['city_name'] = $this->input->post('city_name');
            $data['pin_code'] = $this->input->post('pin_code');
            $data['address'] = $this->input->post('address');
            $data['gst'] = $this->input->post('gst');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->Firm_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Firm Created Successfully.</p>');
                redirect('firm');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('firm');
            }
        }
    }

    function delete($id)
    {
        $this->Firm_model->delete($id);
        redirect('firm');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'firm');
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->data['firmlist'] = $this->Firm_model->get_firm_list();
        $this->data['firm'] = $this->Firm_model->get_firm_list($id);
        $this->data['firm_id'] = $id;
        $this->data['main'] = 'firm/firmedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['firm_id'] = $id;
        $data['firm_type'] = $this->input->post('firm_type');
        $data['firm_name'] = $this->input->post('firm_name');
        $data['firm_code'] = $this->input->post('firm_code');
        $data['country_id'] = $this->input->post('country_id');
        $data['state_id'] = $this->input->post('state_id');
        $data['city_name'] = $this->input->post('city_name');
        $data['pin_code'] = $this->input->post('pin_code');
        $data['address'] = $this->input->post('address');
        $data['gst'] = $this->input->post('gst');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Firm_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Firm Updated Successfully.</p>');
            redirect('firm');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('firm');
        }
    }


    public function firm_import()
	{
		if ($this->input->post('importSubmit')) {
			$insertCount = $rowCount = $notAddCount = 0;
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$this->load->library('CSVReader');
				$csvData = $this->csvreader->parse_file($_FILES['file']['tmp_name']);
				if (!empty($csvData)) {
					foreach ($csvData as $row) {
						$rowCount++;
						$firmData = array(
							'firm_type' => $row['firm_type'],
							'firm_name' => $row['firm_name'],
							'firm_code' => $row['firm_code'],
							'country_id' => $row['country_id'],
							'state_id' => $row['state_id'],
							'city_name' => $row['city_name'],
							'pin_code' => $row['pin_code'],
							'address' => $row['address'],
							'gst' => $row['gst'],
							'status' => 'A',
						);
						$insert = $this->Firm_model->get_firm_by_name_and_code_type($firmData['firm_name'], $firmData['firm_code'],$firmData['firm_type']);
						if (!$insert) {
							$this->Firm_model->firm_import($firmData);
							$insertCount++;
						}
					}
					$notAddCount = $rowCount - $insertCount;
					$successMsg = '<p class="text-center">Firm imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ')  | <span class="text-danger">Not Inserted (' . $notAddCount . ')</span></p>';
					$this->session->set_flashdata('message', $successMsg);
					redirect('firm');
				}
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('firm');
			}
		}
	}
}
