<?php
defined("BASEPATH") or exit("No direct script access allowed");

class AdditionalController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->model("BaseModel");
    }

    private function logged_in() {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }


}