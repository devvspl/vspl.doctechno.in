<?php
   $Scan_Id = $this->uri->segment(2);
   $DocType_Id = $this->uri->segment(3);
   $rec = $this->customlib->getScanData($Scan_Id);
   $company_list = $this->customlib->getCompanyList();
   $punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
   $locationlist = $this->customlib->getWorkLocationList();
   $company_list = $this->customlib->getCompanyList();
   $department_list = $this->customlib->getDepartmentList();
   $file_list = $this->customlib->getFileList();
   $worklocation_list = $this->customlib->getWorkLocationList();
   $ledger_list = $this->customlib->getLedgerList();
   $category_list = $this->customlib->getCategoryList();
   $item_list = $this->customlib->getItemList();
   $locationlist = $this->customlib->getWorkLocationList();
   $document_number = 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT);
   $tdsJvNo = 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date ?? date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT);
   $business_entity = $this->db->where(['status'=>'A', 'is_deleted'=> 'N'])->get('master_business_entity')->result_array();
   $headquarter = $this->db->get('master_headquarter')->result_array();
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
   $activities = fetchData('master_activity', $this->db);
   $crop_list = fetchData('master_crop', $this->db);
   $region_list = fetchData('master_region', $this->db); 
   $cash_voucher_items = $this->db->where(['Scan_Id'=>$Scan_Id])->get('cash_voucher_items');
   $groupedActivities = [];
   foreach ($activities as $activity) {
    $groupedActivities[$activity['activity_group']][] = $activity;
   }
   $regions = []; 
   $crops = [];   
    $subledgerOptions = $this->db->get('master_sub_ledger')->result_array();
    $paymentMethods = [
      "Cash",
      "Debit card",
      "Credit card",
      "NetBanking",
      "Check",
      "Wire transfer",
      "Automated Clearing House (ACH) transfers",
      "Paper checks",
      "eChecks",
      "Digital payments",
      "Other"
   ];
   
   ?>
<div class="box-body">

   <div class="row">
      <div class="col-md-5">
         <?php if ($rec->File_Ext == 'pdf') { ?>
         <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
         <?php } else { ?>
         <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
         <div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #3a495e;"></div>
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
      <div class="tabs-container">
         <div class="tabs active-tab" id="invoice-tab">Invoice Details</div>
         <div class="tabs" id="additional-info-tab">Additional Information</div>
      </div>
      <div id="invoice-details" class="tab-content active">
         
         <div class="col-md-7">
         <?php if ($this->session->flashdata('message')) { ?>
                  <?php echo $this->session->flashdata('message') ?>
                  <?php } ?>
            <input type="hidden" readonly name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
            <input type="hidden" readonly name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
            <div class="row">
               <div class="form-group col-md-4">
                  <label for="">Company Name:</label> <span class="text-danger">*</span>
                  <select readonly name="CompanyID" id="CompanyID" class="form-control" required
                     data-parsley-errors-container="#CompanyError">
                     <option value="">Select</option>
                     <?php
                        foreach ($company_list as $key => $value) {
                        	$selected = '';
                        	if (isset($punch_detail->CompanyID) && $punch_detail->CompanyID == $value['firm_id']) {
                        		$selected = 'selected';
                        	}
                        	echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
                        }
                        ?>
                  </select>
                  <div id="CompanyError"></div>
               </div>
               <div class="form-group col-md-4">
                  <label for="">Voucher No:</label>
                  <input type="text" readonly name="Voucher_No" id="Voucher_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
               </div>
               <div class="form-group col-md-4">
                  <label for="">Voucher Date:</label>
                  <input type="text" readonly name="Voucher_Date" id="Voucher_Date" class="form-control datepicker" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>" autocomplete="off">
               </div>
               <div class="col-md-4 form-group">
                  <label for="">Location:</label>
                  <select readonly name="Location" id="Location" class="form-control">
                     <option value="">Select</option>
                     <?php foreach ($locationlist as $key => $value) { ?>
                     <option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
                        if ($value['location_name'] == $punch_detail->Loc_Name) {
                        	echo "selected";
                        }
                        }  ?>><?= $value['location_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-4">
                  <label for="" id="">Payee:</label>
                  <input type="text" readonly name="Payee" id="Payee" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
               </div>
               <div class="form-group col-md-4">
                  <label for="" id="">Payer:</label>
                  <input type="text" readonly name="Payer" id="Payer" class="form-control" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : ''  ?>">
               </div>
            </div>
            <div class="row">
               <div class="col-md-4 form-group" >
                  <label for="">Amount:</label>
                  <input type="text" readonly name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : ''  ?>">
               </div>
               <div class="col-md-8 form-group">
                  <label for="">Particular:</label>
                  <input type="text" readonly name="Particular" id="Particular" class="form-control" value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : ''  ?>">
               </div>
            </div>
            <div class="row">
               <div class="form-group col-md-12">
                  <label for="">Remark / Comment:</label>
                  <textarea readonly name="Remark" id="Remark" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : ''  ?></textarea>
               </div>
            </div>
            <?php
               if ($this->customlib->haveSupportFile($Scan_Id) == 1) {
               ?>
            <div class="row" style="margin-top: 20px;">
               <div class="col-md-12">
                  <label for="">Supporting File:</label>
                  <div class="form-group">
                     <?php
                        $support_file = $this->customlib->getSupportFile($Scan_Id);
                        foreach ($support_file as $row) {
                        ?>
                     <div class="col-md-3">
                        <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
                     </div>
                     <?php
                        }
                        ?>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
      <div id="additional-info" class="tab-content">
         <div class="col-md-7">
         <form action="<?= base_url(); ?>Form/VSPL_cash_voucher_ctrl/create" id="cash_voucher_form" name="cash_voucher_form" method="post">
            <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
            <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
            <div class="row" style="background-color: #fff;">
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Document No</label>
                  <input type="text" name="document_number" id="document_number" class="form-control" readonly value="<?= $document_number ?>" >
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Date</label>
                  <input type="text" name="finance_pucnh_date" id="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>" >
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Business Entity</label>
                  <select  name="business_entity_id" id="business_entity_id" class="form-control">
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
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Head Quarter </label>
                  <select  name="headquarter_id" id="headquarter_id" class="form-control">
                     <option value="">Select</option>
                     <?php
                        $selected = isset($punch_detail->headquarter_id) ? $punch_detail->headquarter_id : '';
                        
                        foreach ($headquarter as $value) {
                           $isSelected = ($value['headquarter_id'] == $selected) ? 'selected' : '';
                           echo '<option value="' . htmlspecialchars($value['headquarter_id']) . '" ' . $isSelected . '>' . htmlspecialchars($value['name']) . '</option>';
                        }
                        ?>
                  </select>
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Account (Cash/Bank A/C)</label>
                  <input type="text" name="account" id="account" placeholder="Select Cash or Bank Account" class="form-control"  value="<?php echo isset($punch_detail->account) ? $punch_detail->account : ''; ?>" >
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Favouring</label>
                  <input type="text" name="favouring" id="favouring" placeholder="Enter the favouring" class="form-control"  value="<?php echo isset($punch_detail->favouring) ? $punch_detail->favouring : ''; ?>" >
               </div>
               <div class="form-group col-md-12" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Narration</label>
                  <textarea name="narration" id="narration" placeholder="Enter transaction details"  class="form-control" ><?php echo isset($punch_detail->narration) ? $punch_detail->narration : $punch_detail->Remark; ?></textarea>
               </div>
               <?php
                  $tdsApplicableValue = isset($punch_detail->tdsApplicable) ? $punch_detail->tdsApplicable : 'no';
                  ?>
               <div class="form-group col-md-12 tds-applicable-group" style="display: flex; gap: 15px;background-color: #ffffff;     margin-bottom: 0;
                  padding-bottom: 5px;">
                  <label for="tdsApplicable">TDS Applicable</label>
                  <div class="form-check">
                     <input 
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
                  <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                     <label for="tdsJvNo">TDS JV No</label>
                     <input   type="text" id="tdsJvNo" name="TDS_JV_no" class="form-control" readonly value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>">
                  </div>
                  <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
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
                  <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                     <label for="tdsPercentage">TDS Percentage</label>
                     <input type="text" value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>" id="tdsPercentage" name="TDS_percentage" class="form-control" readonly placeholder="Enter TDS Percentage">
                  </div>
                  <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                     <label for="tdsAmount">TDS Amount</label>
                     <input   type="text" id="tdsAmount" value="<?php echo isset($punch_detail->TDS_amount) ? $punch_detail->TDS_amount : ''; ?>" name="TDS_amount" class="form-control" readonly>
                  </div>
               </div>
               <div id="rows_container">
                  <?php foreach ($cash_voucher_items->result_array() as $entry): ?>
                  <?php if ($entry['Scan_Id'] == $Scan_Id):  ?>
                  <div class="row form-row" id="row_1" style="padding: 5px;">
                     <div class="form-group col-md-4">
                        <label for="cost_center">Cost Center</label>
                        <select  name="cost_center_id[]" id="cost_center_<?= $entry['id']; ?>" class="form-control">
                           <option value="">Select Cost Center</option>
                           <?php
                              $selected = $entry['cost_center_id'];
                              foreach ($cost_centers as $cost_center): ?>
                           <option 
                              value="<?= $cost_center['cost_center_id']; ?>" 
                              <?= ($cost_center['cost_center_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $cost_center['cost_center_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="department">Department</label>
                        <select  name="DepartmentID[]" id="department_<?= $entry['id']; ?>" class="form-control">
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
                        <label for="business_unit">Business Unit</label>
                        <select  name="business_unit_id[]" id="business_unit_<?= $entry['id']; ?>" class="form-control">
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
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control region_id">
                           <option value="">Select Region</option>
                           <?php
                              $selectedRegion = $entry['region_id'];
                              foreach ($region_list as $list): ?>
                           <option value="<?= $list['region_id']; ?>" <?= ($list['region_id'] == $selectedRegion) ? 'selected' : ''; ?>>
                              <?= $list['region_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <!-- <select  name="state_id[]" id="state" class="form-control state_select" onchange="fetchRegions(this.value, this)"> -->
                        <select  name="state_id[]" id="state" class="form-control state_select">
                           <option value="">Select State</option>
                           <?php
                              $selectedState = $entry['state_id'];
                                foreach ($states as $state): ?>
                           <option value="<?= $state['state_id']; ?>" <?= ($state['state_id'] == $selectedState) ? 'selected' : ''; ?>>
                              <?= $state['state_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="location">Location</label>
                        <select  name="location_id[]" id="location" class="form-control">
                           <option value="">Select Location</option>
                           <?php
                              $selectedLocation = $entry['location_id'];
                              foreach ($locations as $location): ?>
                           <option value="<?= $location['location_id']; ?>" <?= ($location['location_id'] == $selectedLocation) ? 'selected' : ''; ?>>
                              <?= $location['location_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="category">Category</label>
                        <select  name="category_id[]" class="form-control category_select" onchange="fetchCrops(this.value, this)">
                           <option value="">Select Category</option>
                           <?php
                              $selectedCategory = $entry['category_id'];
                              foreach ($categories as $category): ?>
                           <option value="<?= $category['crop_category_id']; ?>" <?= ($category['crop_category_id'] == $selectedCategory) ? 'selected' : ''; ?>>
                              <?= $category['crop_category_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="crop">Crop</label>
                        <select name="crop_id[]" id="crop_id" class="form-control crop_id">
                           <option value="">Select Crop</option>
                           <?php
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
                        <select  name="activity_id[]" id="activity" class="form-control">
                           <option value="">Select Activity</option>
                           <?php
                              $selectedActivity = $entry['activity_id'];
                              foreach ($activities as $activity): ?>
                           <option value="<?= $activity['activity_id']; ?>" <?= ($activity['activity_id'] == $selectedActivity) ? 'selected' : ''; ?>>
                              <?= $activity['activity_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="debit_ac">Debit A/C</label>
                        <input type="text" name="debit_ac[]"  value="<?= $entry['debit_ac']; ?>" class="form-control debit-ac" placeholder="Type to search Debit A/C">
                        <input type="hidden" name="debit_ac_id[]" value="<?= $entry['debit_ac_id']; ?>">
                        <div class="custom-dropdown debit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method[]" class="payment_method form-control" id="payment_method_<?= $entry['id']; ?>">
                           <option value="">Select Payment Method</option>
                           <?php
                              $selectedPaymentMethod = $entry['payment_method'];
                              foreach ($paymentMethods as $method): ?>
                           <option 
                              value="<?= $method; ?>"
                              <?= ($method == $selectedPaymentMethod) ? 'selected' : ''; ?>
                              >
                              <?= $method; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input  type="number" name="Item_Total_Amount[]" class="form-control amount" value="<?= $entry['Total_Amount']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">TDS Amount</label>
                        <input  type="number" name="Item_TDS_Amount[]" class="form-control" value="<?= $entry['TDS_Amount']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="reference">Reference</label>
                        <input type="text" name="Item_ReferenceNo[]" id="reference_<?= $entry['id']; ?>" class="form-control" value="<?= $entry['ReferenceNo']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="remark">Remark</label>
                        <input type="text" name="Item_Remark[]" id="remark_<?= $entry['id']; ?>" class="form-control" value="<?= $entry['Remark']; ?>" />
                     </div>
                     <div class="form-group col-md-4">
                        <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                     </div>
                  </div>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <?php 
                     $num_row =  $cash_voucher_items->num_rows();
                     
                     if($num_row == 0)
                     {
                        ?>
                  <div class="row form-row" id="row_1" style="    padding: 5px;">
                     <div class="form-group col-md-4">
                        <label for="cost_center">Cost Center</label>
                        <select  name="cost_center_id[]" id="cost_center" class="form-control">
                           <option value="">Select Cost Center</option>
                           <?php
                              foreach ($cost_centers as $cost_center): ?>
                           <option 
                              value="<?= $cost_center['cost_center_id']; ?>" 
                              >
                              <?= $cost_center['cost_center_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="department">Department</label>
                        <select  name="DepartmentID[]" id="department" class="form-control">
                           <option value="">Select Department</option>
                           <?php
                              foreach ($departments as $department): ?>
                           <option 
                              value="<?= $department['department_id']; ?>" 
                              >
                              <?= $department['department_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="business_unit">Business Unit</label>
                        <select  name="business_unit_id[]" id="business_unit" class="form-control">
                           <option value="">Select Business Unit</option>
                           <?php
                              if (isset($groupedUnits['0'])) {
                                  foreach ($groupedUnits['0'] as $parentUnit): ?>
                           <option 
                              value="<?= $parentUnit['business_unit_id']; ?>" 
                              >
                              <?= $parentUnit['business_unit_name']; ?>
                           </option>
                           <?php
                              if (isset($groupedUnits[$parentUnit['business_unit_id']])): 
                                  foreach ($groupedUnits[$parentUnit['business_unit_id']] as $childUnit): ?>
                           <option 
                              value="<?= $childUnit['business_unit_id']; ?>" 
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
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control region_id">
                           <option value="">Select Region</option>
                           <?php
                              
                                  foreach ($region_list as $list): ?>
                           <option 
                              value="<?= $list['region_id']; ?>" 
                              >
                              <?= $list['region_name']; ?>
                           </option>
                           <?php endforeach;
                             
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <!-- <select  name="state_id[]" id="state" class="form-control  state_select" onchange="fetchRegions(this.value, this)"> -->
                        <select  name="state_id[]" id="state" class="form-control  state_select">

                        <option value="">Select State</option>
                           <?php 
                              $selected = isset($punch_detail->state_id) ? $punch_detail->state_id : '';
                              foreach ($states as $state): ?>
                           <option 
                              value="<?= $state['state_id']; ?>" 
                              >
                              <?= $state['state_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="location">Location</label>
                        <select  name="location_id[]" id="location" class="form-control">
                           <option value="">Select Location</option>
                           <?php
                              $selected = isset($punch_detail->location_id) ? $punch_detail->location_id : '';
                              foreach ($locations as $location): ?>
                           <option 
                              value="<?= $location['location_id']; ?>" 
                              >
                              <?= $location['location_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="category">Category</label>
                        <select  name="category_id[]" class="form-control category_select" onchange="fetchCrops(this.value, this)">
                           <option value="">Select Category</option>
                           <?php
                              $selected = isset($punch_detail->category_id) ? $punch_detail->category_id : '';
                              foreach ($categories as $category): ?>
                           <option 
                              value="<?= $category['crop_category_id']; ?>" 
                              >
                              <?= $category['crop_category_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="crop">Crop</label>
                        <select name="crop_id[]" id="crop_id" class="form-control crop_id">
                           <option value="">Select Crop</option>
                           <?php
                              if (!empty($crops)) {
                                 foreach ($crops as $crop): ?>
                           <option 
                              value="<?= $crop['id']; ?>" 
                              >
                              <?= $crop['name']; ?>
                           </option>
                           <?php endforeach;
                              } else {
                                 echo '<option value="">No crops available</option>';
                              }
                              ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="activity">Activity</label>
                        <select  name="activity_id[]" id="activity" class="form-control">
                           <option value="">Select Activity</option>
                           <?php 
                              if (isset($groupedActivities['0']) || isset($groupedActivities[null])) {
                                  $parentActivities = array_merge($groupedActivities['0'] ?? [], $groupedActivities[null] ?? []);
                                  foreach ($parentActivities as $parentActivity): ?>
                           <option 
                              value="<?= $parentActivity['activity_id']; ?>" >
                              <?= $parentActivity['activity_name']; ?>
                           </option>
                           <?php
                              if (isset($groupedActivities[$parentActivity['activity_id']])): 
                                  foreach ($groupedActivities[$parentActivity['activity_id']] as $childActivity): ?>
                           <option 
                              value="<?= $childActivity['activity_id']; ?>"
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
                        <label for="debit_ac">Debit A/C</label>
                        <input type="text" name="debit_ac[]" class="form-control debit-ac" placeholder="Type to search Debit A/C">
                        <div class="custom-dropdown debit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method[]" id="payment_method" class="form-control payment_method">
                           <option value="">Select Payment Method</option>
                           <?php
                              foreach ($paymentMethods as $method): ?>
                           <option value="<?= $method; ?>">
                              <?= $method; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input  type="number" placeholder="0.00" name="Item_Total_Amount[]" class="form-control amount" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="reference">Reference</label>
                        <input type="text" placeholder="Enter Reference" name="Item_ReferenceNo[]" id="reference" class="form-control"  />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="remark">Remark</label>
                        <input type="text" placeholder="Enter Remark" name="Item_Remark[]" id="remark" class="form-control" value="" />
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">TDS Amount</label>
                        <input  type="number" placeholder="0.00" name="Item_TDS_Amount[]" class="form-control" />
                     </div>
                     <div class="form-group col-md-4">
                        <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                     </div>
                  </div>
                  <?php 
                     }
                     ?>
               </div>
               <div class="form-group col-md-12" style="margin-top: 5px;">
                  <button type="button" class="btn btn-success" id="add_row">Add Row</button>
                  <label style="float: right;">Total: <input  type="number" name="finance_total_Amount" readonly id="billAmount" value="<?php echo isset($punch_detail->finance_total_Amount) ? $punch_detail->finance_total_Amount : ''; ?>" class="form-control" /></label>
               </div>
            </div>
            <div class="box-footer">
               <button type="reset" class="btn btn-danger">Reset</button>
               <input type="submit" class="btn btn-info pull-right" style="margin-left: 20px;" id="f_save_as_draft" name="f_save_as_draft" value="Save as Draft"></input>
               <input   type="submit" class="btn btn-success pull-right" name="submit" id="finalSubmitBtn" value="Final Submit"></input>
            </div>
         </form>
         </div>
      </div>
   </div>
</div>
<script>
   function fetchRegions(stateId, stateElement) {
       $.ajax({
           url: "<?php echo base_url('Punch/fetchRegions')?>",
           type: "POST",
           data: { state_id: stateId },
           success: function (response) {
               var $currentRow = $(stateElement).closest(".row");
               var $regionDropdown = $currentRow.find(".region_id");
   
               $regionDropdown.empty().append('<option value="">Select Region</option>');
   
               var regions = JSON.parse(response);
   
               $.each(regions, function (index, region) {
                   $regionDropdown.append('<option value="' + region.region_id + '">' + region.region_name + "</option>");
               });
           },
           error: function (xhr, status, error) {
               console.error("Error fetching regions:", error);
           },
       });
   }
   function fetchCrops(categoryId, categoryElement) {
       $.ajax({
           url: "<?php echo base_url('Punch/fetchCrops');?>",
           type: "POST",
           data: { category_id: categoryId },
           success: function (response) {
               var $currentRow = $(categoryElement).closest(".row");
               var $cropDropdown = $currentRow.find(".crop_id");
   
               $cropDropdown.empty().append('<option value="">Select Crop</option>');
   
               var crops = JSON.parse(response);
   
               $.each(crops, function (index, crop) {
                   $cropDropdown.append('<option value="' + crop.crop_id + '">' + crop.crop_name + " - " + crop.crop_code + "</option>");
               });
           },
           error: function (xhr, status, error) {
               console.error("Error fetching crops:", error);
           },
       });
   }
   $(".datepicker").datetimepicker({
       timepicker: false,
       format: "Y-m-d",
       input: false,
   });
   $("#Location").select2();
   $("#invoice-tab").click(function () {
       $("#additional-info").removeClass("active");
       $("#invoice-details").addClass("active");
       $(".tabs").removeClass("active-tab");
       $(this).addClass("active-tab");
   });
   $("#additional-info-tab").click(function () {
       $("#invoice-details").removeClass("active");
       $("#additional-info").addClass("active");
       $(".tabs").removeClass("active-tab");
       $(this).addClass("active-tab");
   });
   if ($("#Location").is("[readonly]")) {
       $(".select2-container--default .select2-selection--single").css({
           "background-color": "#f0f0f0",
           "border-color": "#ccc",
       });
   }
   $(document).ready(function () {
       let rowCount = 1;
   
       function fetchAccountOptions(inputType, query) {
           return $.ajax({
               url: '<?php echo base_url("journal-entry/get-account-options");?>',
               type: "GET",
               data: {
                   type: inputType,
                   query: query,
               },
               dataType: "json",
           });
       }
   
       function initializeAutocomplete(rowId) {
           $(`#${rowId} .debit-ac`).autocomplete({
               source: function (request, response) {
                   fetchAccountOptions("debit", request.term).done(function (data) {
                       if (Array.isArray(data)) {
                           response(
                               data.map((account) => {
                                   return {
                                        label: `${account.account_name} (${account.account_code})`,
                                       value: account.account_name,
                                       id: account.id,
                                   };
                               })
                           );
                       } else {
                           console.error("Expected an array but received:", data);
                       }
                   });
               },
               minLength: 2,
               select: function (event, ui) {
                   $(this).val(ui.item.label);
                   $(this).siblings(".hidden-debit-id").remove();
                   $(this).after(`<input type="hidden" class="hidden-debit-id" name="debit_ac_id[]" value="${ui.item.id}">`);
   
                   let parentRow = $(this).closest(".row");
                   let subLedgerDropdown = parentRow.find(".subledger");
   
                   fetchSubLedgerOptions(ui.item.value, subLedgerDropdown);
   
                   return false;
               },
           });
   
           function fetchSubLedgerOptions(debitAccountId, subLedgerDropdown) {
               $.ajax({
                   url: '<?php echo base_url("Form/JournalEntry_ctrl/getSubLedgers");?>',
                   type: "POST",
                   data: { debit_ac_id: debitAccountId },
                   dataType: "json",
                   success: function (data) {
                       console.log(data);
                       subLedgerDropdown.empty();
                       subLedgerDropdown.append('<option value="">Select Subledger</option>');
                       if (Array.isArray(data)) {
                           data.forEach(function (subLedger) {
                               subLedgerDropdown.append(`<option value="${subLedger.id}">${subLedger.sub_ledger}</option>`);
                           });
                       } else {
                           console.error("Failed to fetch sub-ledgers: Unexpected response format.");
                       }
                   },
                   error: function (xhr, status, error) {
                       console.error("AJAX Error: " + error);
                   },
               });
           }
   
           $(`#${rowId} .creadit-ac`).autocomplete({
               source: function (request, response) {
                   fetchAccountOptions("credit", request.term).done(function (data) {
                       console.log("Credit Data:", data);
                       if (Array.isArray(data)) {
                           response(
                               data.map((account) => {
                                   return {
                                        label: `${account.account_name} (${account.account_code})`,
                                       value: account.account_name,
                                       id: account.id,
                                   };
                               })
                           );
                       } else {
                           console.error("Expected an array but received:", data);
                       }
                   });
               },
               minLength: 2,
               select: function (event, ui) {
                   $(this).val(ui.item.label);
   
                   $(this).siblings(".hidden-creadit-id").remove();
   
                   $(this).after(`<input type="hidden" class="hidden-creadit-id" name="credit_ac_id[]" value="${ui.item.id}">`);
   
                   return false;
               },
           });
       }
   
       initializeAutocomplete("row_1");
   
       function updateBillAmount() {
           let total = 0;
   
           $(".amount").each(function () {
               let value = parseFloat($(this).val()) || 0;
               total += value;
           });
   
           var Grand_Total = parseFloat("<?= isset($punch_detail->Total_Amount) && $punch_detail->Total_Amount ? $punch_detail->Total_Amount : ($punch_detail->Grand_Total ?? 0) ?>");
   
           let TDS_amount = $("#tdsAmount").val() || 0;
   
           var maxAllowedAmount = total + parseFloat(TDS_amount);
   
           if (maxAllowedAmount > Grand_Total) {
               alert("Total bill amount cannot exceed the Grand Total including the TDS amount!");
               $("#finalSubmitBtn").attr("disabled", "disabled");
               $("#f_save_as_draft").attr("disabled", "disabled");
           } else {
               $("#finalSubmitBtn").removeAttr("disabled");
               $("#f_save_as_draft").removeAttr("disabled");
           }
   
           $("#billAmount").val(maxAllowedAmount.toFixed(2));
       }
   
       $("#account").autocomplete({
           source: function (request, response) {
               $.ajax({
                   url: "<?php echo base_url('Form/JournalEntry_ctrl/getAllAccountList');?>",
                   type: "GET",
                   dataType: "json",
                   data: {
                       query: request.term,
                   },
                   success: function (data) {
                       if (Array.isArray(data)) {
                           response(
                               data.map((account) => {
                                   return {
                                       label: `${account.account_name} (${account.account_code})`,
                                       value: account.account_name,
                                       id: account.id,
                                   };
                               })
                           );
                       } else {
                           console.error("Expected an array but received:", data);
                       }
                   },
                   error: function (xhr, status, error) {
                       console.error("AJAX error:", status, error);
                   }
               });
           },
           minLength: 2,
           select: function (event, ui) {
               $("#account").val(ui.item.value);
               return false;
           },
       });
   
   
       $("#add_row").click(function () {
           rowCount++;
           let newRow = $("#row_1")
               .clone()
               .attr("id", "row_" + rowCount);
   
           newRow.find("select, input").each(function () {
               let $this = $(this);
               let id = $this.attr("id");
   
               if (id) {
                   let newId = id.replace(/\d+$/, "") + rowCount;
                   $this.attr("id", newId);
               }
   
               if ($this.is('input[type="text"], input[type="number"], input[type="hidden"], select')) {
                   $this.val("");
               }
           });
   
           newRow.find(".remove_row").show();
           $("#rows_container").append(newRow);
   
           initializeAutocomplete(newRow.attr("id"));
   
           updateBillAmount();
       });
   
       $(document).on("click", ".remove_row", function () {
           let totalRows = $(".form-row").length;
   
           if (totalRows > 1) {
               $(this).closest(".form-row").remove();
               updateBillAmount();
           } else {
               alert("At least one row must remain.");
           }
       });
   
       $(document).on("input", ".amount", function () {
           updateBillAmount();
       });
       $('input[name="tdsApplicable"]').change(function () {
           if ($("#tdsApplicableYes").is(":checked")) {
               generateTdsJvNo();
               $("#tdsDetailsForm").show();
           } else {
               $("#tdsDetailsForm").hide();
               $("#tdsJvNo").val("");
           }
       });
       function generateTdsJvNo() {
           const date = new Date();
           const year = date.getFullYear();
           const month = String(date.getMonth() + 1).padStart(2, "0");
   
           const jvNo = "<?php echo $tdsJvNo;?>";
           $("#tdsJvNo").val(jvNo);
       }
       const tdsSections = [
           { section: "194R", description: "Benefit or perquisite in respect of business or profession", rate: "10%" },
           { section: "194H", description: "Commission or brokerage", rate: "5%" },
           { section: "194JB", description: "Fee for professional service or royalty etc @10%", rate: "10%" },
           { section: "194JA", description: "Fees for Technical Services (not being professional services) @2%", rate: "2%" },
           { section: "194A", description: "Interest other than Interest on securities", rate: "10%" },
           { section: "194C", description: "Payment to Contractor / Subcontractor / Advertisements", rate: "1%" },
           { section: "194C", description: "Payment to Contractor / Subcontractor / Advertisements", rate: "2%" },
           { section: "194I", description: "Rent (Land, building or furniture)", rate: "10%" },
           { section: "194Q", description: "TDS on purchase of Goods", rate: "0.10%" },
       ];
       $("#tdsSection").on("change", function () {
           var selectedSection = $(this).val();
           var sectionDetails = tdsSections.find((section) => section.section === selectedSection);
   
           if (sectionDetails) {
               $("#tdsPercentage").val(sectionDetails.rate).trigger("change");
           } else {
               $("#tdsPercentage").val("");
           }
       });
   
       $("#billAmount, #tdsPercentage").on("input change", function () {
           var billAmount = parseFloat("<?= isset($punch_detail->Grand_Total) ? $punch_detail->Grand_Total : 0 ?>");
   
           var percentage = parseFloat($("#tdsPercentage").val()) || 0;
           var tdsAmount = (billAmount * percentage) / 100;
           $("#tdsAmount").val(tdsAmount.toFixed(2));
       });
   });
   
</script>