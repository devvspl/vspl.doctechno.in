<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('sync_api_list')) {
    function sync_api_list() {

        $CI =& get_instance();
        

        $CI->load->database();

  
        $CI->db->select('base_url, api_key, api_value');
        $CI->db->from('core_api');
        $query = $CI->db->get();
        $result = $query->row();
        
        if (!$result || !$result->base_url || !$result->api_key || !$result->api_value) {
            return ['status' => 'error', 'message' => 'Base URL or API Key/Value not found in database'];
        }

        $api_url = $result->base_url."api/project/apis";
        $api_key = $result->api_key;
        $api_value = $result->api_value;


        $headers = [
            'Content-Type: application/json',
            "$api_key: $api_value",  
            'Accept: application/json'
        ];

   
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);  
        $response = curl_exec($curl);
        curl_close($curl);


        $data = json_decode($response, true);

        if ($data['status'] !== 200) {
            return ['status' => 'error', 'message' => 'Failed to fetch API list'];
        }

        $api_list = $data['api_list'];
        $updated_count = 0;
        $skipped_count = 0;

        
        foreach ($api_list as $api) {
       
            $api_data = [
                'id' => $api['id'],
                'api_name' => $api['api_name'],
                'api_end_point' => $api['api_end_point'],
                'description' => $api['description'],
                'parameters' => $api['parameters']
            ];

       
            $CI->db->where('id', $api['id']);
            $existing_record = $CI->db->get('core_api_list')->row();

            if ($existing_record) {
              
                if (
                    $existing_record->api_name == $api['api_name'] &&
                    $existing_record->api_end_point == $api['api_end_point'] &&
                    $existing_record->description == $api['description'] &&
                    $existing_record->parameters == $api['parameters']
                ) {
             
                    $skipped_count++;
                    continue;
                }
            }

  
            $CI->db->replace('core_api_list', $api_data);
            $updated_count++;
        }

        return [
            'status' => 'success',
            'message' => 'API list synced successfully',
            'updated' => $updated_count,
            'skipped' => $skipped_count
        ];
    }
}
