<?php
defined("BASEPATH") or exit("No direct script access allowed");

class AdditionalController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->model("AdditionalModel");
    }

    private function logged_in() {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }

    public function get_business_entities() {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_autocomplete_list('master_business_entity', 'business_entity_name', 'business_entity_id', $query);
        echo json_encode($result);
    }
    public function get_tds_section() {
        $query = $this->input->post('term');
        $result = $this->AdditionalModel->get_tds_sections_autocomplete($query);
        echo json_encode($result);
    }
    
}
?>
