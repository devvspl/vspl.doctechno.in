<style>
   .form-group {
   margin-bottom: 4px;
   }
   th {
   text-align: center;
   }
   .form-control-sm {
   display: inline-block;
   height: auto;
   font-size: 10pt;
   line-height: 1.42857143;
   color: #555;
   background-color: #fff;
   background-image: none;
   border: 1px solid #ccc;
   }
   .d-none {
   display: none !important;
   }
</style>
<?php
   $scan_id = $this->uri->segment(2);
   $DocType_Id = $this->uri->segment(3);
   $rec = $this->customlib->getScanData($scan_id);
   $punch_detail = null;
   if (!empty($scan_id)) {
   $punch_detail = $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row();
   if ($punch_detail) {
   
   } else {
   $punch_detail = $this->db->get_where("y{$this->year_id}_scan_file", ['scan_id' => $scan_id])->row();
   }
   } 
   $firm = $this->db->get_where('master_firm', ['status' => 'A'])->result_array();
   $company_list = $this->customlib->getCompanyList();
   $department_list = $this->customlib->getDepartmentList();
   $file_list = $this->customlib->getFileList();
   $worklocation_list = $this->customlib->getWorkLocationList();
   $ledger_list = $this->customlib->getLedgerList();
   $category_list = $this->customlib->getCategoryList();
   $item_list = $this->customlib->getItemList();
   $locationlist = $this->customlib->getWorkLocationList();
   $document_number = 'CASH' . date('y-m') . '/' . str_pad($this->db->where('scan_doctype_id', 57)->where('MONTH(scan_date)', date('m'))->where('YEAR(scan_date)', date('Y'))->count_all_results("y{$this->year_id}_scan_file") + 1, 4, '0', STR_PAD_LEFT);
   $tdsJvNo = 'TDSCASH/' . date('Y-m', strtotime($this->db->select('scan_date')->where('scan_doctype_id', 57)->where('MONTH(scan_date)', date('m'))->where('YEAR(scan_date)', date('Y'))->order_by('scan_date', 'DESC')->limit(1)->get("y{$this->year_id}_scan_file")->row()->scan_date ?? date('Y-m'))) . '/' . str_pad($this->db->where('scan_doctype_id', 57)->where('MONTH(scan_date)', date('m'))->where('YEAR(scan_date)', date('Y'))->count_all_results("y{$this->year_id}_scan_file") + 1, 4, '0', STR_PAD_LEFT);
   $business_entity = $this->db->where(['status'=>'A', 'is_deleted'=> 'N'])->get('master_business_entity')->result_array();
   function fetchData($tableName, $db) {
   return $db->where('status', 'A')
      ->where('is_deleted', 'N')
      ->get($tableName)
      ->result_array();
   }
   $departments = fetchData('master_department', $this->db);
   $cost_centers = fetchData('master_cost_center', $this->db);
   
   $businessUnits = fetchData('master_business_unit', $this->db);
   $groupedUnits = [];
   foreach ($businessUnits as $unit) {
   $groupedUnits[$unit['business_unit_group']][] = $unit;
   }
   $states = fetchData('master_state', $this->db);
   $locations = fetchData('master_work_location', $this->db);
   $categories = fetchData('master_crop_category', $this->db);
   $activities = fetchData('core_activity', $this->db);
   $crop_list = fetchData('master_crop', $this->db);
   $region_list = fetchData('master_region', $this->db); 
   $cash_payment_new_items = $this->db->where(['scan_id'=>$scan_id])->get('cash_payment_new_items');
   $account_group = $this->db->select('account_group, COUNT(*) AS total_accounts')->from('master_account')->group_by('account_group')->get()->result_array();
   $groupedActivities = [];
   
   
   foreach ($activities as $activity) {
    $groupedActivities[$activity['activity_group']][] = $activity;
   }
   $regions = []; 
   $crops = [];   
   
   
   
    $subledgerOptions = [
                            ['value' => '1', 'text' => 'Advertisment Exp - Banner'],
                            ['value' => '2', 'text' => 'Advertisment Exp - Demo Board'],
                            ['value' => '3', 'text' => 'Advertisment Exp - Fixture Bunting/ Triorama/Wobbler'],
                        ];
                        
                        $debitAcOptions = [
                            ['value' => '1', 'text' => 'Debit A/C 1'],
                            ['value' => '2', 'text' => 'Debit A/C 2'],
                            ['value' => '3', 'text' => 'Debit A/C 3'],
                        ];
                        
                        $creaditAcOptions = [
                            ['value' => '1', 'text' => 'Creadit A/C 1'],
                            ['value' => '2', 'text' => 'Creadit A/C 2'],
                            ['value' => '3', 'text' => 'Creadit A/C 3'],
                        ];
   					$PTMCategory = [
   						['value' => '1', 'text' => 'Cash'],
   						['value' => '2', 'text' => 'Cheque'],
   						['value' => '3', 'text' => 'DD'],
   						['value' => '4', 'text' => 'Otrhers'],
   				  ];
   ?>
<div class="box-body">
   <div class="row">
      <div class="col-md-5">
         <?php if ($rec->file_extension == 'pdf') { ?>
         <object data="<?= $rec->file_path ?>" type="" height="490px" width="100%;"></object>
         <?php } else { ?>
         <input  required type="hidden" name="image" id="image" value="<?= $rec->file_path ?>">
         <div id="imageViewerContainer" style="width: 400px; height:490px;"></div>
         <script>
            var curect_file_path = $('#image').val();
            $("#imageViewerContainer").verySimpleImageViewer({
               imageSource: curect_file_path,
               frame: ['100%', '100%'],
               maxZoom: '900%',
               zoomFactor: '10%',
               mouse: true,
               keyboard: true,
               toolbar: true,
               rotateToolbar: true
            });
         </script>
         <?php } ?>
      </div>
      <form action="<?= base_url(); ?>form/Cashpayment_New_ctrl/create" id="journal_enty_form" name="journal_enty_form" method="post" accept-charset="utf-8">
         <div class="col-md-7">
            <input  required type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
            <input  required type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
            <div class="row">
               <div class="form-group col-md-4">
                  <label for="">Document No</label>
                  <input  required  type="text" name="document_number" id="document_number" class="form-control" readonly value="<?= $document_number ?>" >
               </div>
               <div class="form-group col-md-4">
                  <label for="">Date</label>
                  <input  required type="text" name="pucnh_date" id="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>" >
               </div>
               <div class="form-group col-md-4">
                  <label for="account_group">Account Group</label>
                  <select required name="account_group" id="account_group" class="form-control select2 state_select" onchange="fetchAccount(this.value, this)">
                     <option value="">Select Account Group</option>
                     <?php 
                        $selected = isset($punch_detail->account_group) ? $punch_detail->account_group : '';
                        foreach ($account_group as $state): ?>
                     <option 
                        value="<?= $state['account_group']; ?>" 
                        <?= ($state['account_group'] == $selected) ? 'selected' : ''; ?>>
                        <?= $state['account_group']; ?>
                     </option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label for="Account_id">Account</label>
                  <select name="account" id="Account_id" class="form-control select2">
                     <option value="">Select Account</option>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label for="">Business Entity</label>
                  <select required name="business_entity_id" id="business_entity_id" class="form-control select2">
                     <option value="">Select</option>
                     <?php
                        $selected = isset($punch_detail->business_entity_id) ? $punch_detail->business_entity_id : '';
                        
                        foreach ($business_entity as $value) {
                           $isSelected = ($value['business_entity_id'] == $selected) ? 'selected' : '';
                           echo '<option value="' . htmlspecialchars($value['business_entity_id']) . '" ' . $isSelected . '>' . htmlspecialchars($value['business_entity_name']) . '</option>';
                        }
                        ?>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label for="">Favouring</label>
                  <input  required  type="text" name="favouring" id="favouring" class="form-control" value="<?php echo isset($punch_detail->favouring) ? $punch_detail->favouring : ''; ?>" >
               </div>
               <div class="form-group col-md-12">
                  <label for="">Narration</label>
                  <textarea name="narration" id="narration"  class="form-control" ><?php echo isset($punch_detail->narration) ? $punch_detail->narration : ''; ?></textarea>
               </div>
               <div class="form-group col-md-6">
                  <label for="">Bill No</label>
                  <input  required type="text" name="bill_no" id="bill_no" class="form-control" value="<?php echo isset($punch_detail->File_No) ? $punch_detail->File_No : ''; ?>" >
               </div>
               <div class="form-group col-md-6">
                  <label for="">Bill Date</label>
                  <input  required type="date" name="BillDate" id="bill_date" class="form-control" value="<?php echo isset($punch_detail->BillDate) ? $punch_detail->BillDate : ''; ?>" >
               </div>
               <div id="rows_container">
                  <?php foreach ($cash_payment_new_items->result_array() as $entry): ?>
                  <?php if ($entry['scan_id'] == $scan_id):  ?>
                  <div class="row form-row bg-light" id="row_1" style="padding: 5px;margin-bottom: 13px;">
                                       
						<div class="form-group col-md-4">
                        <label for="cost_center">Cost Center</label>
                        <select required name="cost_center_id[]" id="cost_center_<?= $entry['id']; ?>" class="form-control select2">
                           <option value="">Select Cost Center</option>
                           <?php
                              $selected = $entry['cost_center_id'];
                              foreach ($cost_centers as $cost_center): ?>
                           <option 
                              value="<?= $cost_center['id']; ?>" 
                              <?= ($cost_center['id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $cost_center['name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
						<!-- Location -->
													<div class="form-group col-md-4">
                        <label for="location">Location</label>
                        <select required name="location_id[]" id="location" class="form-control select2">
                           <option value="">Select Location</option>
                           <?php
                              // $selectedLocation = isset($cash_payment_new_items->location_id) ? $cash_payment_new_items->location_id : '';
                              $selectedLocation = $entry['location_id'];
                              foreach ($locations as $location): ?>
                           <option value="<?= $location['location_id']; ?>" <?= ($location['location_id'] == $selectedLocation) ? 'selected' : ''; ?>>
                              <?= $location['location_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>   
 <!-- Category -->
 <div class="form-group col-md-4">
                        <label for="category">Category</label>
                        <select required name="category_id[]" class="form-control select2 category_select" onchange="fetchCrops(this.value, this)">
                           <option value="">Select Category</option>
                           <?php
                              // $selectedCategory = isset($cash_payment_new_items->category_id) ? $cash_payment_new_items->category_id : '';
                              $selectedCategory = $entry['category_id'];
                              foreach ($categories as $category): ?>
                           <option value="<?= $category['crop_category_id']; ?>" <?= ($category['crop_category_id'] == $selectedCategory) ? 'selected' : ''; ?>>
                              <?= $category['crop_category_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <!-- Crop -->
                     <div class="form-group col-md-4">
                        <label for="crop">Crop</label>
                        <select name="crop_id[]" id="crop_id" class="form-control select2 crop_id">
                           <option value="">Select Crop</option>
                           <?php
                              // $selectedCrop = isset($cash_payment_new_items->crop_id) ? $cash_payment_new_items->crop_id : '';
                              $selectedCrop = $entry['crop_id'];
                              foreach ($crop_list as $list): ?>
                           <option value="<?= $list['crop_id']; ?>" <?= ($list['crop_id'] == $selectedCrop) ? 'selected' : ''; ?>>
                              <?= $list['crop_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
							<div class="form-group col-md-4">
                        <label for="activity">Activity</label>
                        <select required name="activity_id[]" id="activity" class="form-control select2">
                           <option value="">Select Activity</option>
                           <?php
                              // $selectedActivity = isset($cash_payment_new_items->activity_id) ? $cash_payment_new_items->activity_id : '';
                              $selectedActivity = $entry['activity_id'];
                              foreach ($activities as $activity): ?>
                           <option value="<?= $activity['activity_id']; ?>" <?= ($activity['activity_id'] == $selectedActivity) ? 'selected' : ''; ?>>
                              <?= $activity['activity_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
							<div class="form-group col-md-4">
                        <label for="state">State</label>
                        <select required name="state_id[]" id="state" class="form-control select2 state_select" onchange="fetchRegions(this.value, this)">
                           <option value="">Select State</option>
                           <?php
                              // $selectedState = isset($cash_payment_new_items->state_id) ? $cash_payment_new_items->state_id : '';
                              $selectedState = $entry['state_id'];
                              foreach ($states as $state): ?>
                           <option value="<?= $state['state_id']; ?>" <?= ($state['state_id'] == $selectedState) ? 'selected' : ''; ?>>
                              <?= $state['state_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <!-- Region -->
                     <div class="form-group col-md-4">
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control select2 region_id">
                           <option value="">Select Region</option>
                           <?php
                              // $selectedRegion = isset($cash_payment_new_items->region_id) ? $cash_payment_new_items->region_id : '';
                              $selectedRegion = $entry['region_id'];
                              foreach ($region_list as $list): ?>
                           <option value="<?= $list['region_id']; ?>" <?= ($list['region_id'] == $selectedRegion) ? 'selected' : ''; ?>>
                              <?= $list['region_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="department">Department</label>
                        <select required name="DepartmentID[]" id="department_<?= $entry['id']; ?>" class="form-control select2">
                           <option value="">Select Department</option>
                           <?php
                              $selected = $entry['DepartmentID'];
                              foreach ($departments as $department): ?>
                           <option 
                              value="<?= $department['department_id']; ?>" 
                              <?= ($department['department_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $department['department_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
							<div class="form-group col-md-4">
                        <label for="ptm_category">PMT Category</label>
                        <select name="ptm_category[]" id="ptm_category" class="form-control select2">
                           <option value="">Select PMT Category</option>
                           <?php
                              $selectedPTMCategory = $entry['ptm_category']; 
                              foreach ($PTMCategory as $option): ?>
                           <option 
                              value="<?= $option['value']; ?>"
                              <?= ($option['value'] == $selectedPTMCategory) ? 'selected' : ''; ?>
                              >
                              <?= $option['text']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="business_unit">Business Unit</label>
                        <select required name="business_unit_id[]" id="business_unit_<?= $entry['id']; ?>" class="form-control select2">
                           <option value="">Select Business Unit</option>
                           <?php
                              $selected = $entry['business_unit_id'];
                              foreach ($groupedUnits['0'] as $parentUnit): ?>
                           <option 
                              value="<?= $parentUnit['business_unit_id']; ?>" 
                              <?= ($parentUnit['business_unit_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $parentUnit['business_unit_name']; ?>
                           </option>
                           <?php
                              if (isset($groupedUnits[$parentUnit['business_unit_id']])): 
                                  foreach ($groupedUnits[$parentUnit['business_unit_id']] as $childUnit): ?>
                           <option 
                              value="<?= $childUnit['business_unit_id']; ?>" 
                              <?= ($childUnit['business_unit_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              &nbsp;&nbsp;- <?= $childUnit['business_unit_name']; ?>
                           </option>
                           <?php endforeach;
                              endif;
                              endforeach;
                              ?>
                        </select>
                     </div>
							<div class="form-group col-md-4">
                        <label for="account_group">Account Group</label>
                        <select required name="account_group_items[]"  class="form-control select2 state_select" onchange="fetchAccount1(this.value, this)">
                           <option value="">Select Account Group</option>
                           <?php 
                              $selected = isset($punch_detail->account_group) ? $punch_detail->account_group : '';
                              foreach ($account_group as $state): ?>
                           <option 
                              value="<?= $state['account_group']; ?>" 
                              <?= ($state['account_group'] == $selected) ? 'selected' : ''; ?>>
                              <?= $state['account_group']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="Account_id">Account</label>
                        <select name="Account_id_item[]"  class="form-control select2 account_id">
                           <option value="">Select Account</option>
                        </select>
                     </div>
                    

                     <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input required type="number" name="Total_Amount_item[]" class="form-control amount" value="<?= $entry['Total_Amount_item']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="reference">Reference</label>
                        <input type="text" name="ReferenceNo[]" id="reference_<?= $entry['id']; ?>" class="form-control" value="<?= $entry['ReferenceNo']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="remark">Remark</label>
                        <input type="text" name="Remark[]" id="remark_<?= $entry['id']; ?>" class="form-control" value="<?= $entry['Remark']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="">&nbsp;</label>
                        <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                     </div>
                  </div>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <?php 
                     $num_row =  $cash_payment_new_items->num_rows();
                     
                     if($num_row == 0)
                     {
                        ?>
                  <div class="row form-row bg-light" id="row_1" style="padding: 5px;margin-bottom: 13px;">
                     <div class="form-group col-md-4">
                        <label for="cost_center">Cost Center</label>
                        <select required name="cost_center_id[]" id="cost_center" class="form-control select2">
                           <option value="">Select Cost Center</option>
                           <?php
                              $selected = isset($punch_detail->cost_center_id) ? $punch_detail->cost_center_id : '';
                              foreach ($cost_centers as $cost_center): ?>
                           <option 
                              value="<?= $cost_center['id']; ?>" 
                              <?= ($cost_center['id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $cost_center['name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="location">Location</label>
                        <select required name="location_id[]" id="location" class="form-control select2">
                           <option value="">Select Location</option>
                           <?php
                              $selected = isset($punch_detail->location_id) ? $punch_detail->location_id : '';
                              foreach ($locations as $location): ?>
                           <option 
                              value="<?= $location['location_id']; ?>" 
                              <?= ($location['location_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $location['location_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="category">Category</label>
                        <select required name="category_id[]" class="form-control select2 category_select" onchange="fetchCrops(this.value, this)">
                           <option value="">Select Category</option>
                           <?php
                              $selected = isset($punch_detail->category_id) ? $punch_detail->category_id : '';
                              foreach ($categories as $category): ?>
                           <option 
                              value="<?= $category['crop_category_id']; ?>" 
                              <?= ($category['crop_category_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $category['crop_category_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="crop">Crop</label>
                        <select name="crop_id[]" id="crop_id" class="form-control select2 crop_id">
                           <option value="">Select Crop</option>
                           <?php
                              $selected = isset($punch_detail->crop_id) ? $punch_detail->crop_id : '';
                              if (isset($selected) && $selected) {
                                  foreach ($crop_list as $list): ?>
                           <option 
                              value="<?= $list['crop_id']; ?>" 
                              <?= ($list['crop_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $list['crop_name']; ?>
                           </option>
                           <?php endforeach;
                              } else {
                                  foreach ($crops as $crop): ?>
                           <option 
                              value="<?= $crop['id']; ?>" 
                              <?= ($crop['id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $crop['name']; ?>
                           </option>
                           <?php endforeach;
                              }
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="activity">Activity</label>
                        <select required name="activity_id[]" id="activity" class="form-control select2">
                           <option value="">Select Activity</option>
                           <?php 
                              $selected = isset($punch_detail->activity_id) ? $punch_detail->activity_id : '';
                              if (isset($groupedActivities['0']) || isset($groupedActivities[null])) {
                                  $parentActivities = array_merge($groupedActivities['0'] ?? [], $groupedActivities[null] ?? []);
                                  foreach ($parentActivities as $parentActivity): ?>
                           <option 
                              value="<?= $parentActivity['activity_id']; ?>" 
                              <?= ($parentActivity['activity_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $parentActivity['activity_name']; ?>
                           </option>
                           <?php
                              if (isset($groupedActivities[$parentActivity['activity_id']])): 
                                  foreach ($groupedActivities[$parentActivity['activity_id']] as $childActivity): ?>
                           <option 
                              value="<?= $childActivity['activity_id']; ?>"
                              <?= ($childActivity['activity_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              &nbsp;&nbsp;- <?= $childActivity['activity_name']; ?>
                           </option>
                           <?php endforeach; 
                              endif;
                              endforeach; 
                              }
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <select required name="state_id[]" id="state" class="form-control select2  state_select" onchange="fetchRegions(this.value, this)">
                           <option value="">Select State</option>
                           <?php 
                              $selected = isset($punch_detail->state_id) ? $punch_detail->state_id : '';
                              foreach ($states as $state): ?>
                           <option 
                              value="<?= $state['state_id']; ?>" 
                              <?= ($state['state_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $state['state_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control select2 region_id">
                           <option value="">Select Region</option>
                           <?php
                              $selected = isset($punch_detail->region_id) ? $punch_detail->region_id : '';
                              if (isset($selected) && $selected) {
                                  foreach ($region_list as $list): ?>
                           <option 
                              value="<?= $list['region_id']; ?>" 
                              <?= ($list['region_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $list['region_name']; ?>
                           </option>
                           <?php endforeach;
                              } 
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="department">Department</label>
                        <select required name="DepartmentID[]" id="department" class="form-control select2">
                           <option value="">Select Department</option>
                           <?php
                              $selected = isset($punch_detail->DepartmentID) ? $punch_detail->DepartmentID : '';
                              foreach ($departments as $department): ?>
                           <option 
                              value="<?= $department['department_id']; ?>" 
                              <?= ($department['department_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $department['department_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="ptm_category">PMT Category</label>
                        <select name="ptm_category[]" id="ptm_category" class="form-control select2">
                           <option value="">Select PMT Category</option>
                           <?php
                              $selectedPTMCategory = $entry['ptm_category']; 
                              foreach ($PTMCategory as $option): ?>
                           <option 
                              value="<?= $option['value']; ?>"
                              <?= ($option['value'] == $selectedPTMCategory) ? 'selected' : ''; ?>
                              >
                              <?= $option['text']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="business_unit">Business Unit</label>
                        <select required name="business_unit_id[]" id="business_unit" class="form-control select2">
                           <option value="">Select Business Unit</option>
                           <?php
                              $selected = isset($punch_detail->business_unit_id) ? $punch_detail->business_unit_id : '';
                              if (isset($groupedUnits['0'])) {
                                  foreach ($groupedUnits['0'] as $parentUnit): ?>
                           <option 
                              value="<?= $parentUnit['business_unit_id']; ?>" 
                              <?= ($parentUnit['business_unit_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $parentUnit['business_unit_name']; ?>
                           </option>
                           <?php
                              if (isset($groupedUnits[$parentUnit['business_unit_id']])): 
                                  foreach ($groupedUnits[$parentUnit['business_unit_id']] as $childUnit): ?>
                           <option 
                              value="<?= $childUnit['business_unit_id']; ?>" 
                              <?= ($childUnit['business_unit_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              &nbsp;&nbsp;- <?= $childUnit['business_unit_name']; ?>
                           </option>
                           <?php endforeach; 
                              endif;
                              endforeach; 
                              }
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="account_group">Account Group</label>
                        <select required name="account_group_items[]"  class="form-control select2 state_select" onchange="fetchAccount1(this.value, this)">
                           <option value="">Select Account Group</option>
                           <?php 
                              $selected = isset($punch_detail->account_group) ? $punch_detail->account_group : '';
                              foreach ($account_group as $state): ?>
                           <option 
                              value="<?= $state['account_group']; ?>" 
                              <?= ($state['account_group'] == $selected) ? 'selected' : ''; ?>>
                              <?= $state['account_group']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="Account_id">Account</label>
                        <select name="Account_id_item[]"  class="form-control select2 account_id">
                           <option value="">Select Account</option>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input required type="number" name="Total_Amount_item[]" class="form-control amount" value="<?= isset($punch_detail->Total_Amount) ? $punch_detail->Total_Amount : ''; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="reference">Reference</label>
                        <input type="text" name="ReferenceNo[]" id="reference" class="form-control" value="<?= isset($punch_detail->ReferenceNo) ? $punch_detail->ReferenceNo : ''; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="remark">Remark</label>
                        <input type="text" name="Remark[]" id="remark" class="form-control" value="" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="">&nbsp;</label>
                        <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                     </div>
                  </div>
                  <?php 
                     }
                     
                        ?>
               </div>
               <div class="form-group col-md-12">
                  <button type="button" class="btn btn-success" id="add_row">Add Row</button>
                  <label style="float: right;">Total: <input required type="number" name="Total_Amount" readonly id="billAmount" class="form-control" value="<?= isset($punch_detail->Total_Amount) ? $punch_detail->Total_Amount : ''; ?>" /></label>
               </div>
               <div class="col-md-12">
                  <textarea name="main_remark" id="main_remark"  class="form-control" ><?php echo isset($punch_detail->Remark) ? $punch_detail->Remark : ''; ?></textarea>
               </div>
               <?php
                  $tdsApplicableValue = isset($punch_detail->tdsApplicable) ? $punch_detail->tdsApplicable : 'no';
                  ?>
               <div class="form-group col-md-12 tds-applicable-group" style="display: flex; gap: 15px;">
                  <label for="tdsApplicable">TDS Applicable</label>
                  <div class="form-check">
                     <input 
                        required 
                        class="form-check-input" 
                        type="radio" 
                        name="tdsApplicable" 
                        id="tdsApplicableYes" 
                        value="yes"
                        <?= ($tdsApplicableValue == 'yes') ? 'checked' : ''; ?>
                        >
                     <label class="form-check-label" for="tdsApplicableYes">Yes</label>
                  </div>
                  <div class="form-check">
                     <input 
                        required 
                        class="form-check-input" 
                        type="radio" 
                        name="tdsApplicable" 
                        id="tdsApplicableNo" 
                        value="no"
                        <?= ($tdsApplicableValue == 'no') ? 'checked' : ''; ?>
                        >
                     <label class="form-check-label" for="tdsApplicableNo">No</label>
                  </div>
               </div>
               <div id="tdsDetailsForm" class="tds-details-form" style="display: none; margin-top: 15px;">
                  <div class="form-group col-md-4">
                     <label for="tdsJvNo">TDS CASH No</label>
                     <input   type="text" id="tdsJvNo" name="TDS_CASH_no" class="form-control" readonly value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="tdsJvNo">TDS Section</label>
                     <?php
                        $selectedTdsSection = isset($punch_detail->TDS_section) ? $punch_detail->TDS_section : '';
                        ?>
                     <select id="tdsSection" name="TDS_section" class="form-control">
                        <option value="">Select Section</option>
                        <?php
                           $tdsSections = [
                               [
                                   'section' => '194R',
                                   'description' => 'Benefit or perquisite in respect of business or profession',
                                   'rate' => '10%'
                               ],
                               [
                                   'section' => '194H',
                                   'description' => 'Commission or brokerage',
                                   'rate' => '5%'
                               ],
                               [
                                   'section' => '194JB',
                                   'description' => 'Fee for professional service or royalty etc @10%',
                                   'rate' => '10%'
                               ],
                               [
                                   'section' => '194JA',
                                   'description' => 'Fees for Technical Services (not being professional services) @2%',
                                   'rate' => '2%'
                               ],
                               [
                                   'section' => '194A',
                                   'description' => 'Interest other than Interest on securities',
                                   'rate' => '10%'
                               ],
                               [
                                   'section' => '194C',
                                   'description' => 'Payment to Contractor / Subcontractor / Advertisements',
                                   'rate' => '1%'
                               ],
                               [
                                   'section' => '194C',
                                   'description' => 'Payment to Contractor / Subcontractor / Advertisements',
                                   'rate' => '2%'
                               ],
                               [
                                   'section' => '194I',
                                   'description' => 'Rent (Land, building or furniture)',
                                   'rate' => '10%'
                               ],
                               [
                                   'section' => '194Q',
                                   'description' => 'TDS on purchase of Goods',
                                   'rate' => '0.10%'
                               ]
                           ];
                           
                           foreach ($tdsSections as $section): ?>
                        <option 
                           value="<?= $section['section']; ?>" 
                           <?= ($section['section'] == $selectedTdsSection) ? 'selected' : ''; ?>
                           >
                           <?= $section['section']; ?> - <?= $section['description']; ?>
                        </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="tdsPercentage">TDS Percentage</label>
                     <input type="text" value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>" id="tdsPercentage" name="TDS_percentage" class="form-control" readonly placeholder="Enter TDS Percentage">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="tdsAmount">TDS Amount</label>
                     <input   type="text" id="tdsAmount" value="<?php echo isset($punch_detail->TDS_amount) ? $punch_detail->TDS_amount : ''; ?>" name="TDS_amount" class="form-control" readonly>
                  </div>
               </div>
            </div>
            <div class="box-footer">
               <button type="reset" class="btn btn-danger">Reset</button>
               <input  required type="submit" class="btn btn-success pull-right" name="submit" value="Final Submit"></input>
            </div>
            <?php
               if ($this->customlib->haveSupportFile($scan_id) == 1) {
               ?>
            <div class="row" style="margin-top: 20px;">
               <div class="col-md-12">
                  <label for="">Supporting Fil</label>
                  <div class="form-group">
                     <?php
                        $support_file = $this->customlib->getSupportFile($scan_id);
                        
                        foreach ($support_file as $row) {
                        ?>
                     <div class="col-md-3">
                        <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                     </div>
                     <?php
                        }
                        ?>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </form>
   </div>
</div>
<script>
   $(document).ready(function() {
   function applyOddRowClass() {
        $('#rows_container .form-row').each(function(index) {
            var isOdd = (index + 1) % 2 !== 0;
            $(this).toggleClass('odd-row', isOdd);
       
            if (isOdd) {
                $(this).css({
                    'background-color': '#f9f9f9', 
                    'border': '1px solid #ddd'
                });
            } else {
                $(this).css({
                    'background-color': '',
                    'border': ''
                });
            }
        });
    }
   
    function updateBillAmount() {
        let total = 0;
        $('.amount').each(function() {
            let value = parseFloat($(this).val()) || 0;
            total += value;
        });
        $('#billAmount').val(total.toFixed(2)); 
    }
   
    var rowCount = 1;
   
   $('#add_row').click(function() {
    rowCount++;
    var newRow = $('#row_1').clone().attr('id', 'row_' + rowCount);
   
    newRow.find('select, input').each(function() {
        var $this = $(this);
        var id = $this.attr('id');
   
        if (id) {
            var newId = id.replace(/\d+$/, '') + rowCount;
            $this.attr('id', newId);
        }
   
        
        if ($this.is('input[type="text"], input[type="number"], input[type="hidden"], select')) {
            $this.val(''); 
        }
    });
   
    newRow.find('.remove_row').show();
    $('#rows_container').append(newRow);
    applyOddRowClass();
    updateBillAmount();
   });
   
   
    $(document).on('click', '.remove_row', function() {
        $(this).closest('.form-row').remove();
        applyOddRowClass();
        updateBillAmount(); 
    });
   
    $(document).on('input', '.amount', function() {
        updateBillAmount(); 
    });
   $('input[name="tdsApplicable"]').change(function() {
        if ($('#tdsApplicableYes').is(':checked')) {
            generateTdsJvNo();  
            $('#tdsDetailsForm').show();
        } else {
            $('#tdsDetailsForm').hide();
            $('#tdsJvNo').val(''); 
        }
    });;
   
   
   function generateTdsJvNo() {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); 
   
    const jvNo = "<?php echo $tdsJvNo;?>";
    $('#tdsJvNo').val(jvNo);
   }
   const tdsSections = [
    { 'section': '194R', 'description': 'Benefit or perquisite in respect of business or profession', 'rate': '10%' },
    { 'section': '194H', 'description': 'Commission or brokerage', 'rate': '5%' },
    { 'section': '194JB', 'description': 'Fee for professional service or royalty etc @10%', 'rate': '10%' },
    { 'section': '194JA', 'description': 'Fees for Technical Services (not being professional services) @2%', 'rate': '2%' },
    { 'section': '194A', 'description': 'Interest other than Interest on securities', 'rate': '10%' },
    { 'section': '194C', 'description': 'Payment to Contractor / Subcontractor / Advertisements', 'rate': '1%' },
    { 'section': '194C', 'description': 'Payment to Contractor / Subcontractor / Advertisements', 'rate': '2%' },
    { 'section': '194I', 'description': 'Rent (Land, building or furniture)', 'rate': '10%' },
    { 'section': '194Q', 'description': 'TDS on purchase of Goods', 'rate': '0.10%' }
   ];
   
   $('#tdsSection').on('change', function() {
        var selectedSection = $(this).val();
        var sectionDetails = tdsSections.find(section => section.section === selectedSection);
   
        if (sectionDetails) {
            $('#tdsPercentage').val(sectionDetails.rate).trigger('change');
        } else {
            $('#tdsPercentage').val('');
        }
    });
   
    $('#billAmount, #tdsPercentage').on('input change', function() {
        var billAmount = parseFloat($('#billAmount').val()) || 0;
        var percentage = parseFloat($('#tdsPercentage').val()) || 0;
        var tdsAmount = (billAmount * percentage) / 100;
        $('#tdsAmount').val(tdsAmount.toFixed(2));
    });
   });
   
      function fetchRegions(stateId, stateElement) {
        $.ajax({
            url: "<?php echo base_url('Punch/fetchRegions')?>", 
            type: 'POST',
            data: { state_id: stateId },
            success: function(response) {
              
                var $currentRow = $(stateElement).closest('.row');
                var $regionDropdown = $currentRow.find('.region_id');
                
                $regionDropdown.empty().append('<option value="">Select Region</option>');
   
                var regions = JSON.parse(response);
   
                $.each(regions, function(index, region) {
                    $regionDropdown.append('<option value="' + region.region_id + '">' + region.region_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching regions:", error);
            }
        });
    }
                 function fetchCrops(categoryId, categoryElement) {
        $.ajax({
            url: "<?php echo base_url('Punch/fetchCrops');?>", 
            type: 'POST',
            data: { category_id: categoryId },
            success: function(response) {
   
                var $currentRow = $(categoryElement).closest('.row');
                var $cropDropdown = $currentRow.find('.crop_id');
                
                $cropDropdown.empty().append('<option value="">Select Crop</option>');
   
                var crops = JSON.parse(response);
   
                $.each(crops, function(index, crop) {
                    $cropDropdown.append('<option value="' + crop.crop_id + '">' + crop.crop_name + ' - ' + crop.crop_code + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching crops:", error);
            }
        });
    }
   
	 function fetchAccount1(accountGroup, element) {
    $.ajax({
        url: "<?php echo base_url('Punch/fetchAccount')?>",
        type: 'POST',
        data: { account_group: accountGroup },
        success: function(response) {
            // Corrected to use the `element` parameter
            var $currentRow = $(element).closest('.row');
            var $accountDropdown = $currentRow.find('.account_id'); // Ensure this class selector is correct

            // Clear existing options
            $accountDropdown.empty().append('<option value="">Select Account</option>');

            var accounts = JSON.parse(response);

            $.each(accounts, function(index, account) {
                $accountDropdown.append('<option value="' + account.account_id + '">' + account.account_name + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching account:", error);
        }
    });
}

   function fetchAccount(accountGroup, element) {
    $.ajax({
        url: "<?php echo base_url('Punch/fetchAccount')?>",
        type: 'POST',
        data: { account_group: accountGroup }, // Changed to account_group as per the dropdown value
        success: function(response) {
            // Find the Account dropdown within the same row or form
            var $accountDropdown = $(element).closest('form').find('#Account_id');
            
            $accountDropdown.empty().append('<option value="">Select Account</option>');
   
            var accounts = JSON.parse(response);
   
            $.each(accounts, function(index, account) {
                $accountDropdown.append('<option value="' + account.account_id + '">' + account.account_name + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching account:", error);
        }
    });
   
   }
</script>
