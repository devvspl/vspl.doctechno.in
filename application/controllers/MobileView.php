<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MobileView extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Record_model');
    }



    public function index($Scan_Id, $DocTypeId)
    {
        $doc_type_list = array(4,5,8,10,11,18,19,30,31,32,35,36,37,41,45);
        if (in_array($DocTypeId, $doc_type_list)) {
            $this->data['file_detail'] = $this->Record_model->getRecordFile($Scan_Id);
        } else {
            $this->data['file_detail'] = $this->Record_model->getRecordFile_Accounting($Scan_Id);
        }
        $this->load->view('records/mobile_view', $this->data);
    }

}
