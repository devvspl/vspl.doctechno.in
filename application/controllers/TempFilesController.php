<?php
defined("BASEPATH") or exit("No direct script access allowed");

class TempFilesController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->helper("file");

        $this->load->helper("download");
    }

    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }

    public function temp_files()
    {
        $data["data"] = $this->get_temp_files();
        $this->data["main"] = "temp_files_view";
        $this->data["data"] = $data["data"];
        $this->load->view("layout/template", $this->data);
    }

    private function get_temp_files()
    {
        $temp_dir = "./uploads/temp/";
        $files = get_dir_file_info($temp_dir);
        foreach ($files as &$file) {
            $file["created"] = date("Y-m-d H:i:s", $file["date"]);
            $file["size"] = $this->format_file_size($file["size"]);
        }
        return $files;
    }

    private function format_file_size($size)
    {
        if ($size >= 1073741824) {
            $size = number_format($size / 1073741824, 2) . " GB";
        } elseif ($size >= 1048576) {
            $size = number_format($size / 1048576, 2) . " MB";
        } elseif ($size >= 1024) {
            $size = number_format($size / 1024, 2) . " KB";
        } else {
            $size = $size . " bytes";
        }
        return $size;
    }

    public function delete($file_name = "")
    {
        if ($file_name != "") {
            $file_path = "./uploads/temp/" . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
                $this->session->set_flashdata(
                    "message",
                    "File deleted successfully"
                );
            } else {
                $this->session->set_flashdata("message", "File not found");
            }
        } else {
            $this->session->set_flashdata(
                "message",
                "No file selected to delete"
            );
        }
        redirect("temp-files");
    }

    public function download($file_name = "")
    {
        if ($file_name != "") {
            $file_path = "./uploads/temp/" . $file_name;
            if (file_exists($file_path)) {
                force_download($file_path, null);
            } else {
                $this->session->set_flashdata("message", "File not found");
                redirect("temp-files");
            }
        } else {
            $this->session->set_flashdata(
                "message",
                "No file selected to download"
            );
            redirect("temp-files");
        }
    }
    public function view($file_name = "")
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

}
