<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CommonController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper("file");
        $this->load->helper("download");
    }
    public function temp_files()
    {
        if (!getRoutePermission("temp_files")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $temp_dir = "./uploads/temp/";
        $files = get_dir_file_info($temp_dir);
        if (!is_array($files)) {
            $files = [];
        }
        foreach ($files as &$file) {
            $file["created"] = date("Y-m-d H:i:s", $file["date"]);
            $size = $file["size"];
            if ($size >= 1073741824) {
                $file["size"] = number_format($size / 1073741824, 2) . " GB";
            } elseif ($size >= 1048576) {
                $file["size"] = number_format($size / 1048576, 2) . " MB";
            } elseif ($size >= 1024) {
                $file["size"] = number_format($size / 1024, 2) . " KB";
            } else {
                $file["size"] = $size . " bytes";
            }
        }
        $data["data"] = $files;
        $data["main"] = "admin/temp_files";
        $this->load->view("layout/template", $data);
    }
    public function temp_file_delete($file_name = "")
    {
        if ($file_name != "") {
            $file_path = "./uploads/temp/" . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
                $this->session->set_flashdata("message", "File deleted successfully");
            } else {
                $this->session->set_flashdata("message", "File not found");
            }
        } else {
            $this->session->set_flashdata("message", "No file selected to delete");
        }
        redirect("temp_files");
    }
    public function temp_file_download($file_name = "")
    {
        if ($file_name != "") {
            $file_path = "./uploads/temp/" . $file_name;
            if (file_exists($file_path)) {
                force_download($file_path, null);
            } else {
                $this->session->set_flashdata("message", "File not found");
                redirect("temp_files");
            }
        } else {
            $this->session->set_flashdata("message", "No file selected to download");
            redirect("temp_files");
        }
    }
    public function temp_file_view($file_name = "")
    {
        if ($file_name != "") {
            $file_path = "./uploads/temp/" . $file_name;
            if (file_exists($file_path)) {
                $mime = mime_content_type($file_path);
                $data['file_url'] = base_url("uploads/temp/" . $file_name);
                $data['mime'] = $mime;
                $this->load->view("file_preview", $data);
            } else {
                echo "<script>alert('File not found'); window.close();</script>";
            }
        } else {
            echo "<script>alert('No file selected'); window.close();</script>";
        }
    }
    public function change_password()
    {
        $this->form_validation->set_rules('current_pass', 'Current password', 'trim|required');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'common/change_password';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['user_id'] = $this->session->userdata('user_id');
            $data['current_pass'] = md5($this->input->post('current_pass'));
            $data['new_pass'] = md5($this->input->post('new_pass'));
            $data['user_id'] = $this->session->userdata('user_id');
            $checkUser = $this->BaseModel->getData('users', array('user_id' => $data['user_id'], 'password' => $data['current_pass']));
            if (!$checkUser) {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid current password. Please try again.</p>');
                redirect('change_password');
            } else {
                $updatePassword = $this->BaseModel->updateData('users', array('password' => $data['new_pass']), array('user_id' => $data['user_id']));
                if ($updatePassword) {
                    $this->session->set_flashdata('message', '<p class="text-success text-center">Password changed successfully.</p>');
                    redirect('change_password');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid current password. Please try again.</p>');
                    redirect('change_password');
                }
            }
        }
    }
}
