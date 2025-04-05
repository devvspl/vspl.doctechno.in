<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiSync_ctrl extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('api_sync_helper');
    }

    public function sync() {

        $result = sync_api_list();
        echo json_encode($result);
    }
}
