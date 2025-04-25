<?php
defined("BASEPATH") or exit("No direct script access allowed");

class PublicController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("Extract_model");
    }

    public function processQueue() {
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

}