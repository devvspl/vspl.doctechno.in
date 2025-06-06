<?php
defined("BASEPATH") or exit("No direct script access allowed");

class PublicController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Extract_model");
    }

    public function processQueue()
    {
        $pendingItems = $this->Extract_model->getAllPendingQueueItems();
        if (empty($pendingItems)) {
            echo json_encode(["status" => "success", "message" => "No pending items in queue."]);
            return;
        }

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($pendingItems as $queue) {
            try {
                $endpoint = $this->Extract_model->getApiEndpoint($queue->type_id);
                if (!$endpoint) {
                    throw new Exception("API endpoint not found.");
                }

                $fileUrl = $this->Extract_model->getFileLocation($queue->scan_id);
                if (!$fileUrl) {
                    throw new Exception("File not found.");
                }

                $apiResponse = $this->Extract_model->callExternalApi($endpoint, $fileUrl);
                if ($apiResponse["statusCode"] !== 200 || empty($apiResponse["data"])) {
                    throw new Exception("API call failed.");
                }

                $saveStatus = $this->Extract_model->storeExtractedData($queue->type_id, $queue->scan_id, $apiResponse["data"]);
                if (!$saveStatus) {
                    throw new Exception("Failed to store data.");
                }

                $this->Extract_model->updateQueueStatus($queue->id, 'completed', 'success');
                $successCount++;
            } catch (Exception $e) {
                $this->Extract_model->updateQueueStatus($queue->id, 'failed', 'error', $e->getMessage());
                $failCount++;
                $errors[] = "ID {$queue->id}: " . $e->getMessage();
            }
        }

        echo json_encode([
            "status" => $failCount > 0 ? "partial" : "success",
            "message" => "Queue processing completed. Success: $successCount, Failed: $failCount",
            "errors" => $errors
        ]);
    }

    public function importData()
    {
        $this->load->view('import_view');
    }

    public function import()
    {
        // Validate table name
        $table_name = $this->input->post('table_name');
        if (empty($table_name) || !$this->db->table_exists($table_name)) {
            $this->session->set_flashdata('error', 'Invalid or non-existent table name.');
            redirect('import-data');
        }

        // Validate match and update columns
        $match_column = $this->input->post('match_column');
        $update_column = $this->input->post('update_column');
        $columns = $this->db->list_fields($table_name);
        if (empty($match_column) || !in_array($match_column, $columns)) {
            $this->session->set_flashdata('error', "Match column '$match_column' does not exist in table '$table_name'.");
            redirect('import-data');
        }
        if (empty($update_column) || !in_array($update_column, $columns)) {
            $this->session->set_flashdata('error', "Update column '$update_column' does not exist in table '$table_name'.");
            redirect('import-data');
        }

        // Check if file is uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] == UPLOAD_ERR_NO_FILE) {
            $this->session->set_flashdata('error', 'Please upload a CSV file.');
            redirect('import-data');
        }

        // Validate and process the CSV file
        $file = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($file, 'r')) !== FALSE) {
            // Get the CSV headers
            $headers = fgetcsv($handle, 1000, ',');
            $name_index = array_search('Name', $headers);
            $code_index = array_search('Code', $headers);

            if ($name_index === FALSE || $code_index === FALSE) {
                $this->session->set_flashdata('error', 'CSV must contain "Name" and "Code" columns.');
                redirect('import-data');
            }

            // Process each row
            $updated_rows = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $name = $row[$name_index];
                $code = $row[$code_index];

                // Update the specified column in the table where the match column matches
                $this->db->where($match_column, $name);
                $update_data = [$update_column => $code];
                $this->db->update($table_name, $update_data);

                if ($this->db->affected_rows() > 0) {
                    $updated_rows++;
                }
            }
            fclose($handle);

            $this->session->set_flashdata('success', "Successfully updated $updated_rows rows in the $table_name table.");
        } else {
            $this->session->set_flashdata('error', 'Failed to read the CSV file.');
        }

        redirect('import-data');
    }
}