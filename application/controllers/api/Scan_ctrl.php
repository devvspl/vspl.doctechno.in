<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Scan_ctrl extends REST_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
        $this->jwt = new JWT();
        $this->header = ($this->input->request_headers());
        if (isset($this->header['Token']) &&  $this->header['Token'] != null) {
            $this->user_data = $this->customlib->is_valid($this->header['Token']);
            if (is_null($this->user_data)) {
                http_response_code(401);
                echo json_encode(array('msg' => 'Invalid token.', 'status' => '401'));
                die;
            }
        } else { //token not set
            http_response_code(400);
            echo json_encode(array('errors' => 'Token not set.', 'status' => 400));
            die;
        }
    }

    function myscan_list_get()
    {
        $user_id = $this->jwt->decode($this->header['Token'])->user_id;
        $result = $this->Scan_model->get_myscan_list($user_id);
        if (!empty($result)) {
            $this->response(array('data' => $result, 'status' => 200), 200);
        } else {
            $this->response(array('data' => $result, 'status' => 400), 400);
        }
    }

    function myscan_punched_list_get()
    {
        $user_id = $this->jwt->decode($this->header['Token'])->user_id;
        $result = $this->Scan_model->get_myscan_punched_list($user_id);
        if (!empty($result)) {
            $this->response(array('data' => $result, 'status' => 200), 200);
        } else {
            $this->response(array('data' => $result, 'status' => 400), 400);
        }
    }

    function upload_main_post()
    {
        $user_id = $this->jwt->decode($this->header['Token'])->user_id;
        $document_name = $this->post('document_name');
        $file = $_FILES['main_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 2084;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('main_file')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response($error, 400);
        } else {
            $query = $this->db->insert('scan_file', array(
                'Group_Id' => $this->customlib->getGroupID($user_id),
                'Scan_By' => $user_id,
                'Document_name' => $document_name,
                'File' => $var_temp_name,
                'File_Ext' => $file_ext,
                'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                'File_Location1' => 'uploads/temp/' . $var_temp_name,
                'Year' => $year,
                'Scan_Date' => date('Y-m-d'),
            ));
            if ($query) {
                $data = array();
                $data['Scan_Id'] = $this->db->insert_id();
                $this->response(array('data' => $data, 'msg' => 'File uploaded successfully.',  'status' => 200), 200);
            } else {
                $data = array();
                $this->response(array('data' => $data, 'msg' => 'File not uploaded.', 'status' => 400), 400);
            }
        }
    }

    function upload_support_post()
    {
        $user_id = $this->jwt->decode($this->header['Token'])->user_id;
        $Scan_Id = $this->post('Scan_Id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 2084;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response($error, 400);
        } else {
            $query = $this->db->insert('support_file', array(

                'Scan_Id' => $Scan_Id,
                'File' => $var_temp_name,
                'File_Ext' => $file_ext,
                'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                'File_Location1' => 'uploads/temp/' . $var_temp_name,

            ));
            if ($query) {
                $data = array();
                $this->response(array('data' => $data, 'msg' => 'File not uploaded.', 'status' => 400), 400);
            } else {
                $data = array();
                $this->response(array('data' => $data, 'msg' => 'File not uploaded.', 'status' => 400), 400);
            }
        }
    }

    function final_submit_post()
    {

        $Scan_Id = $this->post('Scan_Id');
        $query = $this->db->update('scan_file', array(
            'Final_Submit' => 'Y',
        ), array(
            'Scan_Id' => $Scan_Id,
        ));
        if ($query) {
            $this->response(array('msg' => 'File Submitted successfully.', 'status' => 200), 200);
        } else {
            $this->response(array('msg' => 'File not Submitted.', 'status' => 400), 400);
        }
    }

    function delete_support_post()
    {
        $Support_Id = $this->post('Support_Id');
        $query = $this->db->delete('support_file', array(
            'Support_Id' => $Support_Id,
        ));
        if ($query) {
            $this->response(array('msg' => 'File Deleted successfully.', 'status' => 200), 200);
        } else {
            $this->response(array('msg' => 'File not Deleted.', 'status' => 400), 400);
        }
    }

    function delete_all_post()
    {
        $Scan_Id = $this->post('Scan_Id');
        $query = $this->db->delete('support_file', array(
            'Scan_Id' => $Scan_Id,
        ));
        $query = $this->db->delete('scan_file', array(
            'Scan_Id' => $Scan_Id,
        ));
        if ($query) {
            $this->response(array('msg' => 'File Deleted successfully.', 'status' => 200), 200);
        } else {
            $this->response(array('msg' => 'File not Deleted.', 'status' => 400), 400);
        }
    }

    function support_file_list_post()
    {
        $Scan_Id = $this->post('Scan_Id');
        $result['support_file'] = $this->Scan_model->get_support_file($Scan_Id);

        if (!empty($result)) {
            $this->response(array('data' => $result, 'status' => 200), 200);
        } else {
            $res = array();
            $this->response(array('data' => $res, 'status' => 400), 400);
        }
    }
    
    function search_document_post()
    {
        $user_id = $this->jwt->decode($this->header['Token'])->user_id;
        $document_name = $this->post('document_name');
        $result = $this->Scan_model->search_record($user_id,$document_name);
        if (!empty($result)) {
            $this->response(array('data' => $result, 'status' => 200), 200);
        } else {
            $this->response(array('data' => $result, 'status' => 400), 400);
        }
    }
}
