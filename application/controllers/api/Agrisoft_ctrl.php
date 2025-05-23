<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Agrisoft_ctrl extends CI_Controller
{
    	function __construct()
	{
		parent::__construct();
		$this->load->database();
	
	}
	
	public function scan_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);

         $query = $secondaryDb->select('Scan_Id, Group_Id, Doc_Type, DocType_Id, Document_Name, File_Location,Punch_Date,Missing_Data')
        ->from('y{$this->year_id}_scan_file')
        ->where('Import_Flag', '0')
        ->get();
         $list = $query->result_array();
         $query->free_result();
         echo json_encode(array('scan_files' => $list));
	}


	public function punch_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$punchfile = $secondaryDb->select('*')->from('punchfile')->where_in('scan_id', $scan_ids)->get()->result_array();
		$punchfile2 = $secondaryDb->select('*')->from('punchfile2')->where_in('scan_id', $scan_ids)->get()->result_array();
		$response = [
			'punchfile' => $punchfile,
			'punchfile2' => $punchfile2
		];
		echo json_encode($response);
	}


	public function invoice_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$invoice_detail = $secondaryDb->select('*')
			->from('invoice_detail')
			->where_in('scan_id', $scan_ids)
			->get()
			->result_array();
		echo json_encode(array('invoice_detail' => $invoice_detail));
	}

	public function vehicle_traveling_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$vehicle_traveling_detail = $secondaryDb->select('*')->from('vehicle_traveling')->where_in('scan_id', $scan_ids)->get()->result_array();
		echo json_encode(array('vehicle_traveling_detail' => $vehicle_traveling_detail));
	}

	public function labour_payment_detail_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$labour_payment_detail_detail = $secondaryDb->select('*')->from('labour_payment_detail')->where_in('scan_id', $scan_ids)->get()->result_array();
		echo json_encode(array('labour_payment_detail_detail' => $labour_payment_detail_detail));
	}

	public function lodging_employee_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$lodging_employee_detail = $secondaryDb->select('*')->from('lodging_employee')->where_in('scan_id', $scan_ids)->get()->result_array();
		echo json_encode(array('lodging_employee_detail' => $lodging_employee_detail));
	}
	
	public function ticket_cancellation_detail()
	{
		$secondaryDb = $this->load->database('secondary', TRUE);
		$scan_ids = $secondaryDb->select('scan_id')
			->from('y{$this->year_id}_scan_file')
			->where('import_flag', 0)
			->get()
			->result_array();
		$scan_ids = array_column($scan_ids, 'scan_id');
		$ticket_cancellation_detail = $secondaryDb->select('*')->from('ticket_cancellation')->where_in('scan_id', $scan_ids)->get()->result_array();
		echo json_encode(array('ticket_cancellation_detail' => $ticket_cancellation_detail));
	}

	public function master_detail()
	{
		$master_category = $this->db->select('category_id,category_name,category_code')->from('master_category')->get()->result_array();
		$master_country = $this->db->select('country_id,country_name,country_code')->from('master_country')->get()->result_array();
		$master_department = $this->db->select('department_id,company_id,department_name,department_code')->from('master_department')->get()->result_array();
		$master_employee = $this->db->select('id,emp_vspl,emp_code,emp_name,company_id,company_code,status')->from('master_employee')->get()->result_array();
		$master_doctype = $this->db->select('type_id,file_type,alias')->from('master_doctype')->get()->result_array();
		$master_file = $this->db->select('file_id,file_name,file_code,company_id')->from('master_file')->get()->result_array();
		$master_group = $this->db->select('group_id,group_name')->from('master_group')->get()->result_array();
		$master_hotel = $this->db->select('hotel_id,hotel_name,state_id,address,city_name')->from('master_hotel')->get()->result_array();
		$master_ledger = $this->db->select('ledger_id,ledger_name,ledger_code,ledger_head')->from('master_ledger')->get()->result_array();
		$master_report_type = $this->db->select('report_id,report_name,report_alias')->from('master_report_type')->get()->result_array();
		$master_state = $this->db->select('state_id,country_id,state_name,state_code')->from('master_state')->get()->result_array();
		$master_unit = $this->db->select('unit_id,unit_name,unit_code')->from('master_unit')->get()->result_array();
		$master_work_location = $this->db->select('location_id,location_name')->from('master_work_location')->get()->result_array();

		$response = [
			'master_category' => $master_category,
			'master_country' => $master_country,
			'master_department' => $master_department,
			'master_doctype' => $master_doctype,
			'master_employee' => $master_employee,
			'master_file' => $master_file,
			'master_group' => $master_group,
			'master_hotel' => $master_hotel,
			'master_ledger' => $master_ledger,
			'master_report_type' => $master_report_type,
			'master_state' => $master_state,
			'master_unit' => $master_unit,
			'master_work_location' => $master_work_location,

		];

		echo json_encode($response);
	}

	public function master_firm_detail()
	{

		$master_firm_detail = $this->db->select('firm_id,firm_type,firm_name,firm_code,country_id,state_id,city_name,pin_code,address,gst')->from('master_firm')->where('Import_Flag', '0')->get()->result_array();
	    echo json_encode(array('master_firm_detail' => $master_firm_detail));
	
		
	}

	public function master_item_detail()
	{

		$master_item_detail = $this->db->select('item_id,item_name,item_code')->from('master_item')->where('Import_Flag', '0')->get()->result_array();
     	echo json_encode(array('master_item_detail' => $master_item_detail));
		
	}



	
	
	
	public function transfer_result()
    {
        header("Content-Type:application/json");
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        // Access the Scan_Id values using a loop
        foreach ($data['scan_files'] as $scanFile) {
            $scanId = $scanFile['scan_id'];
            $secondaryDb = $this->load->database('secondary', TRUE);
            $secondaryDb->update('y{$this->year_id}_scan_file', array('Import_Flag' => 1), array('scan_id' =>$scanId));
        }
        
        foreach($data['firm_files'] as $row){
            $firm_id = $row['firm_id'];
            $this->db->update('master_firm',array('Import_Flag'=>1),array('firm_id'=>$firm_id));
        }
        
        foreach($data['item_files'] as $row1){
            $item_id = $row1['item_id'];
            $this->db->update('master_item',array('Import_Flag'=>1),array('item_id'=>$item_id));
        }
        
        echo json_encode(array("status"=>200,"message"=>"Successfully Updated"));
    }


	public function get_punch_date()
	{

		$scan_detail = $this->db->select('Scan_Id,Punch_Date')->from('y{$this->year_id}_scan_file')->where('is_file_approved', 'Y')->get()->result_array();
     	echo json_encode(array('scan_detail' => $scan_detail));
		
	}

	
	public function set_data()
	{
	   $this->customlib->set_missing_data();
       
    }

	
}
