<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CommonController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('BaseModel');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function getCountry()
    {
        $result = $this->BaseModel->getData('core_country');
        $countries = $result ? $result->result() : [];
        echo json_encode($countries);
    }
    public function getState()
    {
        $country_id = $this->input->post('country_id');
        $result = $this->BaseModel->getData('core_state', ['country_id' => $country_id]);
        $states = $result ? $result->result() : [];
        echo json_encode($states);
    }
}