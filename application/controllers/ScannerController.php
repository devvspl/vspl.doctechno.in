<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ScannerController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->load->Model('ScannerModel');
        $this->load->library('pagination');
        $this->load->library('csvexport');
        require_once APPPATH . 'third_party/vendor/autoload.php';
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function scanner()
    {
        $status = $this->input->get('status');
        $document_name = $this->input->get('document_name');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $page = (int) $this->input->get('page') ?: 1;
        $per_page_input = $this->input->get('per_page');
        $per_page = $per_page_input === 'all' ? 0 : 10;
        if (!getRoutePermission("scanner?status=$status")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $total_rows = $this->ScannerModel->getScannedFileCount($status, $document_name, $from_date, $to_date);
        $query_params = [];
        if (!empty($status)) {
            $query_params['status'] = $status;
        }
        if (!empty($document_name)) {
            $query_params['document_name'] = $document_name;
        }
        if (!empty($from_date)) {
            $query_params['from_date'] = $from_date;
        }
        if (!empty($to_date)) {
            $query_params['to_date'] = $to_date;
        }
        if (!empty($per_page_input)) {
            $query_params['per_page'] = $per_page_input;
        }
        if ($per_page > 0) {
            $config = ['base_url' => base_url('scanner'), 'per_page' => $per_page, 'total_rows' => $total_rows, 'use_page_numbers' => true, 'page_query_string' => true, 'query_string_segment' => 'page', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_link' => 'First', 'first_tag_open' => '<li class="page-item">', 'first_tag_close' => '</li>', 'last_link' => 'Last', 'last_tag_open' => '<li class="page-item">', 'last_tag_close' => '</li>', 'next_link' => 'Next', 'next_tag_open' => '<li class="page-item">', 'next_tag_close' => '</li>', 'prev_link' => 'Previous', 'prev_tag_open' => '<li class="page-item">', 'prev_tag_close' => '</li>', 'cur_tag_open' => '<li class="page-item active"><a class="page-link">', 'cur_tag_close' => '</a></li>', 'num_tag_open' => '<li class="page-item">', 'num_tag_close' => '</li>', 'attributes' => ['class' => 'page-link'],];
            if (!empty($query_params)) {
                $query_string = http_build_query($query_params);
                $config['suffix'] = '&' . $query_string;
                $config['first_url'] = $config['base_url'] . '?' . $query_string;
            }
            $this->pagination->initialize($config);
            $pagination_links = $this->pagination->create_links();
        } else {
            $pagination_links = '';
        }
        if ($per_page > 0) {
            $offset = ($page - 1) * $per_page;
            $scanned_files = $this->ScannerModel->getScannedFile($status, $document_name, $from_date, $to_date, $page, $per_page);
        } else {
            $offset = 0;
            $scanned_files = $this->ScannerModel->getScannedFile($status, $document_name, $from_date, $to_date);
        }
        $this->data['main'] = 'scanner/scanfile';
        $this->data['status'] = $status;
        $this->data['document_name'] = $document_name;
        $this->data['from_date'] = $from_date;
        $this->data['to_date'] = $to_date;
        $this->data['pagination'] = $pagination_links;
        $this->data['offset'] = $offset;
        $this->data['my_lastest_scan'] = $scanned_files;
        $this->load->view('layout/template', $this->data);
    }
    public function export($type)
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $status = $this->input->get('status');
        $document_name = $this->input->get('document_name');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $data = $this->ScannerModel->getScannedFile($status, $document_name, $from_date, $to_date, 1, 999999);
        if ($type === 'csv') {
            $filename = 'scan_files_' . date('YmdHis') . '.csv';
            $headers = ['S.No', 'File Name', 'Document Name', 'Scan Date', 'Final Submit'];
            if ($status === 'rejected') {
                $headers[] = 'Reject Remark';
            }
            $rows = [];
            $count = 1;
            foreach ($data as $row) {
                $row_data = [$count++, $row['file_name'], $row['document_name'], date('d-m-Y', strtotime($row['temp_scan_date'])), $row['is_final_submitted'] == 'Y' ? 'Yes' : 'No'];
                if ($status === 'rejected') {
                    $row_data[] = $row['temp_scan_reject_remark'];
                }
                $rows[] = $row_data;
            }
            $this->csvexport->export($filename, $headers, $rows);
        } elseif ($type === 'pdf') {
            $this->load->library('pdf');
            $this->data['my_lastest_scan'] = $data;
            $this->data['status'] = $status;
            $html = $this->load->view('scanner/export_pdf', $this->data, true);
            $pdf = new \Dompdf\Dompdf();
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'landscape');
            $pdf->render();
            $pdf->stream('scan_files_' . date('YmdHis') . '.pdf', ['Attachment' => true]);
        } else {
            show_404();
        }
    }
    public function upload_main()
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $temp_scan_by = $this->session->userdata('user_id');
        $location = $this->input->post('location');
        $bill_approver = $this->input->post('bill_approver');
        $file = $_FILES['main_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 70000;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('main_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            redirect('scanner?status=all');
        } else {
            $data = ['group_id' => $this->session->userdata('group_id'), 'location_id' => $location, 'bill_approver_id' => $bill_approver, 'temp_scan_by' => $temp_scan_by, 'is_temp_scan' => 'Y', 'is_scan_complete' => 'N', 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'uploads/temp/' . $var_temp_name, 'year' => $year, 'temp_scan_date' => date('Y-m-d'),];
            $insert_id = $this->BaseModel->insertDataWithLastId("y{$this->year_id}_scan_file", $data);
            if ($insert_id !== false && is_numeric($insert_id)) {
                $file_org_name = preg_replace('/[^A-Za-z0-9\s\-]/', '', pathinfo($file, PATHINFO_FILENAME));
                $file_org_name = str_replace(' ', '_', $file_org_name);
                $formatted_date = date('dmY_His');
                $document_name = $insert_id . '_' . $temp_scan_by . '_' . ucfirst($file_org_name) . '_' . $formatted_date;
                $update_result = $this->BaseModel->updateData("y{$this->year_id}_scan_file", ['document_name' => $document_name], ['scan_id' => $insert_id]);
                if ($update_result !== -1 && $update_result !== false) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                    redirect('upload_supporting_file/' . $insert_id);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to update document name</div>');
                    redirect('scanner?status=all');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload file due to database error</div>');
                redirect('scanner?status=all');
            }
        }
    }
    public function upload_supporting_file($id)
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $main_file = $this->db->where('scan_id', $id)->get("y{$this->year_id}_scan_file")->row();
        $supporting_files = $this->db->where('scan_id', $id)->get('support_file')->result();
        $this->data['scan_id'] = $id;
        $this->data['main_file'] = $main_file;
        $this->data['supporting_files'] = $supporting_files;
        $this->data['main'] = 'scanner/upload_supporting';
        $this->load->view('layout/template', $this->data);
    }
    public function upload_supporting()
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $scan_id = $this->input->post('scan_id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 70000;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            if ($_SESSION['role'] != 'super_scan') {
                redirect('upload_supporting_file/' . $scan_id);
            } else {
                redirect('/');
            }
        } else {
            $data = ['scan_id' => $scan_id, 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'uploads/temp/' . $var_temp_name,];
            $insert_result = $this->BaseModel->insertData('support_file', $data);
            if ($insert_result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                redirect('upload_supporting_file/' . $scan_id);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload file due to database error</div>');
                if ($_SESSION['role'] != 'super_scan') {
                    redirect('upload_supporting_file/' . $scan_id);
                } else {
                    redirect('/');
                }
            }
        }
    }
    public function scan_final_submit()
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $scan_id = $this->input->post('scan_id');
        $update_result = $this->BaseModel->updateData("y{$this->year_id}_scan_file", ['is_final_submitted' => 'Y'], ['scan_id' => $scan_id]);
        if ($update_result !== -1 && $update_result !== false) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    public function delete_file()
    {
        if (!getRoutePermission("scanner?status=all")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $id = $this->input->post('id');
        $delete_result = $this->BaseModel->deleteData('support_file', ['support_id' => $id]);
        if ($delete_result) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
}
