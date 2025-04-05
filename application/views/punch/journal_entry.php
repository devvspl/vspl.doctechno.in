<?php
   $Scan_Id = $this->uri->segment(2);
   $DocType_Id = $this->uri->segment(3);
   $rec = $this->customlib->getScanData($Scan_Id);
   $punch_detail = null;
   if (!empty($Scan_Id)) {
   $punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
   if ($punch_detail) {
   
   } else {
   $punch_detail = $this->db->get_where('scan_file', ['Scan_Id' => $Scan_Id])->row();
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
   $document_number = 'JV/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 57)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT);
   $tdsJvNo = 'TDSJV/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 57)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date ?? date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 57)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT);
   $business_entity = $this->db->where(['status'=>'A', 'is_deleted'=> 'N'])->get('master_business_entity')->result_array();
   function fetchData($tableName, $db) {
   return $db->where('status', 'A')
      ->where('is_deleted', 'N')
      ->get($tableName)
      ->result_array();
   }
   $departments = fetchData('master_department', $this->db);
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
   $journal_entry_items = $this->db->where(['Scan_Id'=>$Scan_Id])->get('journal_entry_items');
   $groupedActivities = [];
   
   
   foreach ($activities as $activity) {
    $groupedActivities[$activity['activity_group']][] = $activity;
   }
   $regions = []; 
   $crops = [];   
   
   
   
    $subledgerOptions = $this->db->get('master_sub_ledger')->result_array();
   
   
   ?>
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
   .tabs-container{margin-bottom: 10px;}
   .d-none {
   display: none !important;
   }
   .tab-content {
   display: none;
   }
   .active {
   display: block; 
   }
   #rows_container .form-row:nth-child(odd) {
   background-color: #f0f0f0; /* Light color */
   }
   #rows_container .form-row:nth-child(even) {
   background-color: #d0d0d0; /* Dark color */
   }
   .tabs {
   cursor: pointer;
   padding: 10px;
   display: inline-block;
   background-color: #425458a6;
   border: 1px solid #ccc;
   color: #fff;
   }
   .tabs.active-tab {
   background-color: #3a495e; 
   }
</style>
<?php
   $Scan_Id = $this->uri->segment(2);
   $DocType_Id = $this->uri->segment(3);
   $rec = $this->customlib->getScanData($Scan_Id);
   $punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
   $firm = $this->db->get_where('master_firm', ['status' => 'A'])->result_array();
   $company_list = $this->customlib->getCompanyList();
   $department_list = $this->customlib->getDepartmentList();
   $file_list = $this->customlib->getFileList();
   $worklocation_list = $this->customlib->getWorkLocationList();
   $ledger_list = $this->customlib->getLedgerList();
   $category_list = $this->customlib->getCategoryList();
   $item_list = $this->customlib->getItemList();
   $locationlist = $this->customlib->getWorkLocationList();
   ?>
<div class="box-body">
<div class="row">
   <div class="col-md-5">
      <?php if ($rec->File_Ext == 'pdf') { ?>
      <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
      <?php } else { ?>
      <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
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
   <form action="<?= base_url(); ?>form/JournalEntry_ctrl/create" id="invoice_form" name="invoice_form" method="post"
      accept-charset="utf-8">
      
      <?php 
         if ($_SESSION['role'] == 'user') {  
            if ($this->customlib->has_permission('Finance')) { 
                if ($rec->at_finance == 'N') { 
                    ?>
      <style>.select2-container--default .select2-selection--single {
         background-color: #e9ecef !important;
         border: 1px solid #ccc;
         border-radius: 0px;
         }
      </style>
   <div class="col-md-7">
   <div  style="padding: 10px;">
      <?php if ($this->session->flashdata('message')) { ?>
                            <?php echo $this->session->flashdata('message') ?>
                        <?php } ?>
      </div>
   </div>
      <div class="tabs-container">
      
         <div class="tabs active-tab" id="invoice-tab">Invoice Details</div>
         <div class="tabs" id="additional-info-tab">Additional Information</div>
      </div>
      <div id="invoice-details" class="tab-content active">
         <div class="col-md-7">
            <input type="hidden" readonly name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
            <input type="hidden" readonly name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
            <div class="row" style="margin-bottom: 5px;">
               <div class="form-group col-md-3">
                  <label for="">Invoice No:</label>
                  <input type="text" readonly name="Bill_No" id="Bill_No" class="form-control form-control-sm"
                     value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Invoice Date:</label>
                  <input type="date" readonly name="Bill_Date" id="Bill_Date" class="form-control form-control-sm"
                     value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Mode of Payment:</label>
                  <select readonly name="Payment_Mode" id="Payment_Mode" class="form-control form-control-sm form-select form-select-sm">
                     <option value="">Select</option>
                     <?php
                        $mode = array('Credit','Cash', 'Cheque' );
                        foreach ($mode as $key => $value) {
                        	?>
                     <option value="<?= $value ?>" <?php if (isset($punch_detail->NatureOfPayment)) {
                        if ($value == $punch_detail->NatureOfPayment) {
                        	echo "selected";
                        }
                        } ?>><?= $value ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-3">
                  <label for="">Suppliers Reference:</label>
                  <input type="text" readonly name="Supplier_Ref" id="Supplier_Ref" class="form-control form-control-sm" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : '' ?>">
               </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
               <div class="form-gro	up col-md-6">
                  <label for="">Buyer:</label>
                  <select readonly name="From" id="From" class="form-control form-control-sm" 	onchange="getFile();getDepartment();">
                     <option value="">Select</option>
                     <?php
                        foreach ($company_list as $key => $value) {
                        	?>
                     <option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->From_ID)) {
                        if ($value['firm_id'] == $punch_detail->From_ID) {
                        	echo "selected";
                        }
                        } ?>><?= $value['firm_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-6">
                  <label for="">Vendor:</label>
                  <select readonly name="To" id="To" class="form-control form-control-sm">
                     <option value="">Select</option>
                     <?php
                        foreach ($firm as $key => $value) {
                        	?>
                     <option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->To_ID)) {
                        if ($value['firm_id'] == $punch_detail->To_ID) {
                        	echo "selected";
                        }
                        } ?>><?= $value['firm_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
               <div class="form-group col-md-6">
                  <label for="">Buyer Address :</label>
                  <input type="text" readonly name="Buyer_Address" id="Buyer_Address" class="form-control form-control-sm"
                     value="<?= (isset($punch_detail->Loc_Add)) ? $punch_detail->Loc_Add : '' ?>"
                     readonly>
               </div>
               <div class="col-md-6">
                  <label for="">Vendor Address :</label>
                  <input type="text" readonly name="Vendor_Address" id="Vendor_Address" class="form-control form-control-sm"
                     value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
                     readonly>
               </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
               <div class="form-group col-md-3">
                  <label for="">Buyer's Order No.:</label>
                  <input type="text" readonly name="Buyer_Order" id="Buyer_Order" class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Buyer's Order No. Date:</label>
                  <input type="text" readonly name="Buyer_Order_Date" id="Buyer_Order_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Dispatch Through:</label>
                  <input type="text" readonly name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm" value="<?= (isset($punch_detail->Particular)) ? $punch_detail->Particular : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Delivery Note Date:</label>
                  <input type="text" readonly name="Delivery_Note_Date" id="Delivery_Note_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : '' ?>">
               </div>
            </div>
            <div class="row mt-2" style="margin-bottom: 5px;">
               <div class="col-md-3 form-group">
                  <label for="">Department:</label>
                  <select readonly name="Department" id="Department" class="form-control form-control-sm">
                     <?php foreach ($department_list as $key => $value) { ?>
                     <option value="<?= $value['department_id'] ?>" <?php if (isset($punch_detail->DepartmentID)) {
                        if ($value['department_id'] == $punch_detail->DepartmentID) {
                        	echo "selected";
                        }
                        } ?>><?= $value['department_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="col-md-3 form-group">
                  <label for="">Voucher Type/Category:</label>
                  <select readonly name="Category" id="Category" class="form-control form-control-sm">
                     <option value="">Select</option>
                     <?php foreach ($category_list as $key => $value) { ?>
                     <option value="<?= $value['category_name'] ?>" <?php if (isset($punch_detail->Category)) {
                        if ($value['category_name'] == $punch_detail->Category) {
                        	echo "selected";
                        }
                        } ?>><?= $value['category_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="col-md-3 form-group">
                  <label for="">Ledger:</label>
                  <select readonly name="Ledger" id="Ledger" class="form-control form-control-sm">
                     <option value="">Select</option>
                     <?php foreach ($ledger_list as $key => $value) { ?>
                     <option value="<?= $value['ledger_name'] ?>" <?php if (isset($punch_detail->Ledger)) {
                        if ($value['ledger_name'] == $punch_detail->Ledger) {
                        	echo "selected";
                        }
                        } ?>><?= $value['ledger_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="col-md-3 form-group">
                  <label for="">File:</label>
                  <select readonly name="File" id="File" class="form-control form-control-sm">
                     <option value="">Select</option>
                     <?php foreach ($file_list as $key => $value) { ?>
                     <option value="<?= $value['file_name'] ?>" <?php if (isset($punch_detail->FileName)) {
                        if ($value['file_name'] == $punch_detail->FileName) {
                        	echo "selected";
                        }
                        } ?>><?= $value['file_name'] ?></option>
                     <?php } ?>
                  </select>
               </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
               <div class="col-md-3 form-group">
                  <label for="">Location:</label>
                  <select readonly name="Location" id="Location" class="form-control form-control-sm">
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
               <div class="form-group col-md-3">
                  <label for="">LR Number:</label>
                  <input type="text" readonly name="LR_Number" id="LR_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->FDRNo)) ? $punch_detail->FDRNo : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">LR Date:</label>
                  <input type="text" readonly name="LR_Date" id="LR_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : '' ?>">
               </div>
               <div class="form-group col-md-3">
                  <label for="">Cartoon Number:</label>
                  <input type="text" readonly name="Cartoon_Number" id="Cartoon_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->RegNo)) ? $punch_detail->RegNo : '' ?>">
               </div>
            </div>
            <div class="row" style="height: 300px; overflow:auto">
               <div class="" style="overflow-x:scroll;">
                  <table class="table" style="width:1600px;max-width:1600px;overflow-x:scroll;">
                     <thead style="text-align: center;">
                        <th style="width: 10px;">#</th>
                        <th style="width: 420px;">Particular</th>
                        <th style="width: 100px;">HSN</th>
                        <th style="width: 100px;">Qty</th>
                        <th style="width: 150px;">Unit</th>
                        <th style="width: 150px;">MRP</th>
                        <th style="width: 100px;">Discount in MRP</th>
                        <th style="width: 150px;">Price</th>
                        <th style="width: 180px;">Amount</th>
                        <th style="width: 80px;">GST %</th>
                        <th style="width: 80px;">SGST %</th>
                        <th style="width: 80px;">IGST %</th>
                        <th style="width: 80px;">Cess %</th>
                        <th style="width: 120px;">Total Amount</th>
                        <th style="width: 30px;"></th>
                     </thead>
                     <tbody id="multi_record">
                        <tr>
                           <td>1</td>
                           <td>
                              <select readonly name="Particular[]" id="Particular1"
                                 class="form-control form-select form-select-sm particular">
                                 <option value="">Select</option>
                                 <?php
                                    foreach ($item_list as $key => $value) {
                                    	?>
                                 <option value="<?= $value['item_name'] ?>"><?= $value['item_name'] ?></option>
                                 <?php } ?>
                              </select>
                           </td>
                           <td>
                              <input type="text" readonly name="HSN[]" id="HSN1" class="form-control form-control-sm">
                           </td>
                           <td>
                              <input type="text" readonly name="Qty[]" id="Qty1" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                           </td>
                           <td>
                              <select readonly name="Unit[]" id="Unit1" class="form-control form-control-sm">
                                 <option value="">Select</option>
                                 <?php
                                    $unit_list = $this->db->get_where('master_unit', array('status' => 'A', 'is_deleted' => 'N'))->result_array();
                                    foreach ($unit_list as $key => $value) {
                                    	?>
                                 <option value="<?= $value['unit_id'] ?>"><?= $value['unit_name'] ?></option>
                                 <?php } ?>
                              </select>
                           </td>
                           <td>
                              <input type="text" readonly name="MRP[]" id="MRP1" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                           </td>
                           <td>
                              <input type="text" readonly name="Discount[]" id="Discount1"
                                 class="form-control form-control-sm" onKeyPress="return isNumberKey(event)"
                                 onchange="calculate(1)">
                           </td>
                           <td>
                              <input type="text" readonly name="Price[]" id="Price1" class="form-control form-control-sm"
                                 readonly>
                           </td>
                           <td>
                              <input type="text" readonly name="Amount[]" id="Amount1"
                                 class="form-control form-control-sm Amount" readonly>
                           </td>
                           <td>
                              <input type="text" readonly name="GST[]" id="GST1" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                           </td>
                           <td>
                              <input type="text" readonly name="SGST[]" id="SGST1" class="form-control form-control-sm"
                                 readonly>
                           </td>
                           <td>
                              <input type="text" readonly name="IGST[]" id="IGST1" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                           </td>
                           <td>
                              <input type="text" readonly name="Cess[]" id="Cess1" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                           </td>
                           <td>
                              <input type="text" readonly name="TAmount[]" id="TAmount1"
                                 class="form-control form-control-sm TAmount" readonly>
                           </td>
                           <td>
                              <button type="button" disabled readonly name="add" id="add" class="btn btn-primary btn-xs"
                                 style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                           </td>
                        </tr>
                     </tbody>
                     <tr>
                        <td colspan="6"></td>
                        <td><b>Sub Total (₹):</b></td>
                        <td colspan="2">
                           <input type="text" readonly name="Sub_Total" id="Sub_Total"
                              class="form-control form-control-sm" readonly
                              value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : '' ?>">
                        </td>
                     </tr>
                     <tr id="tcs_tr">
                        <td colspan="7" style="text-align: right;"><b>TCS %:</b></td>
                        <td colspan="2">
                           <input type="text" readonly name="TCS" id="TCS" class="form-control form-control-sm"
                              onKeyPress="return isNumberKey(event)" onchange="cal_tax()"
                              value="<?= (isset($punch_detail->TCS)) ? $punch_detail->TCS : '' ?>">
                        </td>
                     </tr>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>Total (₹):</b></td>
                        <td colspan="2">
                           <input type="text" readonly name="Total" id="Total" class="form-control form-control-sm"
                              readonly
                              value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
                        </td>
                     </tr>
                     <tr>
                        <td colspan="7" style="text-align: right;"><b>Round Off (₹):</b></td>
                        <td colspan="5">
                           <input type="text" readonly name="Total_Discount" id="Total_Discount"
                              class="form-control form-control-sm d-inline"
                              value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>"
                              style="width:100px;">
                           <span><input type="radio" readonly name="plus_minus" id="plus" class="plus_minus" <?php
                              if (isset($punch_detail->Total_Discount)) {
                              	if ($punch_detail->Grand_Total > $punch_detail->Total_Amount) {
                              		echo "checked";
                              	}
                              }
                              ?>>
                           <label for="plus">Plus</label>
                           </span>
                           <span><input type="radio" readonly name="plus_minus" id="minus" class="plus_minus" <?php
                              if (isset($punch_detail->Total_Discount)) {
                              	if ($punch_detail->Grand_Total < $punch_detail->Total_Amount) {
                              		echo "checked";
                              	}
                              } else {
                              	echo "checked";
                              }
                              ?>> <label for="minus">Minus</label></span>
                        </td>
                     </tr>
                     <td colspan="7" style="text-align: right;"><b>Grand Total (₹):</b></td>
                     <td colspan="2">
                        <input type="text" readonly name="Grand_Total" id="Grand_Total"
                           class="form-control form-control-sm" readonly
                           value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>">
                     </td>
                     </tr>
                  </table>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-md-12">
                  <label for="">Remark / Comment:</label>
                  <textarea readonly name="Remark" id="Remark" cols="10" rows="2"
                     class="form-control form-control-sm"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
               </div>
            </div>
            <div class="box-footer d-none">
               <button type="reset" class="btn btn-danger">Reset</button>
               <input type="submit" class="btn btn-info pull-right" style="margin-left: 20px;" readonly name="save_as_draft" value="Save as Draft"></input>
               <input type="submit" class="btn btn-success pull-right" readonly name="submit_punch" value="Final Submit"></input>
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
                        <a href="javascript:void(0);" target="popup"
                           onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
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
            <input   type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
            <input   type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
            <div class="row" style="background-color: #fff;">
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Document No</label>
                  <input    type="text" name="document_number" id="document_number" class="form-control" readonly value="<?= $document_number ?>" >
               </div>
               <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Date</label>
                  <input   type="text" name="finance_pucnh_date" id="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>" >
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
               <div class="form-group col-md-12" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="">Narration</label>
                  <textarea name="narration" id="narration"  class="form-control" ><?php echo isset($punch_detail->narration) ? $punch_detail->narration : ''; ?></textarea>
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
               <?php foreach ($journal_entry_items->result_array() as $entry): ?>
                  <?php if ($entry['Scan_Id'] == $Scan_Id):  ?>
                  <div class="row form-row" id="row_1" style="padding: 5px;">
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
                        <label for="state">State</label>
                        <select  name="state_id[]" id="state" class="form-control state_select" onchange="fetchRegions(this.value, this)">
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
                        <input type="hidden" name="debit_ac_id" value="<?= $entry['debit_ac_id']; ?>">
                        <div class="custom-dropdown debit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="creadit_ac">Creadit A/C</label>
                        <input type="text" name="credit_ac[]"  value="<?= $entry['credit_ac']; ?>" class="form-control creadit-ac" placeholder="Type to search Creadit A/C">
                        <input type="hidden" name="credit_ac_id[]" value="<?= $entry['credit_ac_id']; ?>">
                        <div class="custom-dropdown creadit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="subledger">Subledger</label>
                        <select  name="subledger[]" class="subledger form-control" id="subledger_<?= $entry['id']; ?>"  >
                           <option value="">Select Subledger</option>
                           <?php
                              $selectedSubledger = $entry['subledger']; 
                              foreach ($subledgerOptions as $option): ?>
                           <option 
                              value="<?= $option['id']; ?>"
                              <?= ($option['id'] == $selectedSubledger) ? 'selected' : ''; ?>
                              >
                              <?= $option['sub_ledger']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input  type="number" name="Item_amount[]" class="form-control amount" value="<?= $entry['Total_Amount']; ?>" />
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
                        <label for="">&nbsp;</label>
                        <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                     </div>
                  </div>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <?php 
                     $num_row =  $journal_entry_items->num_rows();
                     
                     if($num_row == 0)
                     {
                        ?>
                  <div class="row form-row" id="row_1" style="    padding: 5px;">
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
                        <label for="state">State</label>
                        <select  name="state_id[]" id="state" class="form-control  state_select" onchange="fetchRegions(this.value, this)">
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
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control region_id">
                           <option value="">Select Region</option>
                           <?php
                              if (isset($selected) && $selected) {
                                  foreach ($region_list as $list): ?>
                           <option 
                              value="<?= $list['region_id']; ?>" 
                              >
                              <?= $list['region_name']; ?>
                           </option>
                           <?php endforeach;
                              } 
                              ?>
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
                        <label for="creadit_ac">Creadit A/C</label>
                        <input type="text" name="credit_ac[]" class="form-control creadit-ac" placeholder="Type to search Creadit A/C">
                        <div class="custom-dropdown creadit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="subledger">Subledger</label>
                        <select  name="subledger[]" id="subledger" class="form-control subledger">
                           <option value="">Select Subledger</option>
                           <?php
                              foreach ($subledgerOptions as $option): ?>
                           <option 
                              value="<?= $option['id']; ?>"
                              >
                              <?= $option['sub_ledger']; ?>
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
                        <label for="">&nbsp;</label>
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
         </div>
         <?php
            }
            }
            else{
            ?>
         <div id="invoice-details" class="tab-content active">
            <div class="col-md-7">
               <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
               <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-group col-md-3">
                     <label for="">Invoice No:</label>
                     <input type="text" name="Bill_No" id="Bill_No" class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Invoice Date:</label>
                     <input type="date" name="Bill_Date" id="Bill_Date" class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Mode of Payment:</label>
                     <select name="Payment_Mode" id="Payment_Mode" class="form-control form-control-sm form-select form-select-sm">
                        <option value="">Select</option>
                        <?php
                           $mode = array('Credit','Cash', 'Cheque' );
                           foreach ($mode as $key => $value) {
                           	?>
                        <option value="<?= $value ?>" <?php if (isset($punch_detail->NatureOfPayment)) {
                           if ($value == $punch_detail->NatureOfPayment) {
                           	echo "selected";
                           }
                           } ?>><?= $value ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Suppliers Reference:</label>
                     <input type="text" name="Supplier_Ref" id="Supplier_Ref" class="form-control form-control-sm" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : '' ?>">
                  </div>
               </div>
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-gro	up col-md-6">
                     <label for="">Buyer:</label>
                     <select name="From" id="From" class="form-control form-control-sm" 	onchange="getFile();getDepartment();">
                        <option value="">Select</option>
                        <?php
                           foreach ($company_list as $key => $value) {
                           	?>
                        <option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->From_ID)) {
                           if ($value['firm_id'] == $punch_detail->From_ID) {
                           	echo "selected";
                           }
                           } ?>><?= $value['firm_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="">Vendor:</label>
                     <select name="To" id="To" class="form-control form-control-sm">
                        <option value="">Select</option>
                        <?php
                           foreach ($firm as $key => $value) {
                           	?>
                        <option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->To_ID)) {
                           if ($value['firm_id'] == $punch_detail->To_ID) {
                           	echo "selected";
                           }
                           } ?>><?= $value['firm_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-group col-md-6">
                     <label for="">Buyer Address :</label>
                     <input type="text" name="Buyer_Address" id="Buyer_Address" class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->Loc_Add)) ? $punch_detail->Loc_Add : '' ?>"
                        readonly>
                  </div>
                  <div class="col-md-6">
                     <label for="">Vendor Address :</label>
                     <input type="text" name="Vendor_Address" id="Vendor_Address" class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
                        readonly>
                  </div>
               </div>
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-group col-md-3">
                     <label for="">Buyer's Order No.:</label>
                     <input type="text" name="Buyer_Order" id="Buyer_Order" class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Buyer's Order No. Date:</label>
                     <input type="text" name="Buyer_Order_Date" id="Buyer_Order_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Dispatch Through:</label>
                     <input type="text" name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm" value="<?= (isset($punch_detail->Particular)) ? $punch_detail->Particular : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Delivery Note Date:</label>
                     <input type="text" name="Delivery_Note_Date" id="Delivery_Note_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : '' ?>">
                  </div>
               </div>
               <div class="row mt-2" style="margin-bottom: 5px;">
                  <div class="col-md-3 form-group">
                     <label for="">Department:</label>
                     <select name="Department" id="Department" class="form-control form-control-sm">
                        <?php foreach ($department_list as $key => $value) { ?>
                        <option value="<?= $value['department_id'] ?>" <?php if (isset($punch_detail->DepartmentID)) {
                           if ($value['department_id'] == $punch_detail->DepartmentID) {
                           	echo "selected";
                           }
                           } ?>><?= $value['department_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="">Voucher Type/Category:</label>
                     <select name="Category" id="Category" class="form-control form-control-sm">
                        <option value="">Select</option>
                        <?php foreach ($category_list as $key => $value) { ?>
                        <option value="<?= $value['category_name'] ?>" <?php if (isset($punch_detail->Category)) {
                           if ($value['category_name'] == $punch_detail->Category) {
                           	echo "selected";
                           }
                           } ?>><?= $value['category_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="">Ledger:</label>
                     <select name="Ledger" id="Ledger" class="form-control form-control-sm">
                        <option value="">Select</option>
                        <?php foreach ($ledger_list as $key => $value) { ?>
                        <option value="<?= $value['ledger_name'] ?>" <?php if (isset($punch_detail->Ledger)) {
                           if ($value['ledger_name'] == $punch_detail->Ledger) {
                           	echo "selected";
                           }
                           } ?>><?= $value['ledger_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="">File:</label>
                     <select name="File" id="File" class="form-control form-control-sm">
                        <option value="">Select</option>
                        <?php foreach ($file_list as $key => $value) { ?>
                        <option value="<?= $value['file_name'] ?>" <?php if (isset($punch_detail->FileName)) {
                           if ($value['file_name'] == $punch_detail->FileName) {
                           	echo "selected";
                           }
                           } ?>><?= $value['file_name'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="row" style="margin-bottom: 5px;">
                  <div class="col-md-3 form-group">
                     <label for="">Location:</label>
                     <select name="Location" id="Location" class="form-control form-control-sm">
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
                  <div class="form-group col-md-3">
                     <label for="">LR Number:</label>
                     <input type="text" name="LR_Number" id="LR_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->FDRNo)) ? $punch_detail->FDRNo : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">LR Date:</label>
                     <input type="text" name="LR_Date" id="LR_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Cartoon Number:</label>
                     <input type="text" name="Cartoon_Number" id="Cartoon_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->RegNo)) ? $punch_detail->RegNo : '' ?>">
                  </div>
               </div>
               <div class="row" style="height: 300px; overflow:auto">
                  <div class="" style="overflow-x:scroll;">
                     <table class="table" style="width:1600px;max-width:1600px;overflow-x:scroll;">
                        <thead style="text-align: center;">
                           <th style="width: 10px;">#</th>
                           <th style="width: 420px;">Particular</th>
                           <th style="width: 100px;">HSN</th>
                           <th style="width: 100px;">Qty</th>
                           <th style="width: 150px;">Unit</th>
                           <th style="width: 150px;">MRP</th>
                           <th style="width: 100px;">Discount in MRP</th>
                           <th style="width: 150px;">Price</th>
                           <th style="width: 180px;">Amount</th>
                           <th style="width: 80px;">GST %</th>
                           <th style="width: 80px;">SGST %</th>
                           <th style="width: 80px;">IGST %</th>
                           <th style="width: 80px;">Cess %</th>
                           <th style="width: 120px;">Total Amount</th>
                           <th style="width: 30px;"></th>
                        </thead>
                        <tbody id="multi_record">
                           <tr>
                              <td>1</td>
                              <td>
                                 <select name="Particular[]" id="Particular1"
                                    class="form-control form-select form-select-sm particular">
                                    <option value="">Select</option>
                                    <?php
                                       foreach ($item_list as $key => $value) {
                                       	?>
                                    <option value="<?= $value['item_name'] ?>"><?= $value['item_name'] ?></option>
                                    <?php } ?>
                                 </select>
                              </td>
                              <td>
                                 <input type="text" name="HSN[]" id="HSN1" class="form-control form-control-sm">
                              </td>
                              <td>
                                 <input type="text" name="Qty[]" id="Qty1" class="form-control form-control-sm"
                                    onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                              </td>
                              <td>
                                 <select name="Unit[]" id="Unit1" class="form-control form-control-sm">
                                    <option value="">Select</option>
                                    <?php
                                       $unit_list = $this->db->get_where('master_unit', array('status' => 'A', 'is_deleted' => 'N'))->result_array();
                                       foreach ($unit_list as $key => $value) {
                                       	?>
                                    <option value="<?= $value['unit_id'] ?>"><?= $value['unit_name'] ?></option>
                                    <?php } ?>
                                 </select>
                              </td>
                              <td>
                                 <input type="text" name="MRP[]" id="MRP1" class="form-control form-control-sm"
                                    onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                              </td>
                              <td>
                                 <input type="text" name="Discount[]" id="Discount1"
                                    class="form-control form-control-sm" onKeyPress="return isNumberKey(event)"
                                    onchange="calculate(1)">
                              </td>
                              <td>
                                 <input type="text" name="Price[]" id="Price1" class="form-control form-control-sm"
                                    readonly>
                              </td>
                              <td>
                                 <input type="text" name="Amount[]" id="Amount1"
                                    class="form-control form-control-sm Amount" readonly>
                              </td>
                              <td>
                                 <input type="text" name="GST[]" id="GST1" class="form-control form-control-sm"
                                    onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                              </td>
                              <td>
                                 <input type="text" name="SGST[]" id="SGST1" class="form-control form-control-sm"
                                    readonly>
                              </td>
                              <td>
                                 <input type="text" name="IGST[]" id="IGST1" class="form-control form-control-sm"
                                    onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                              </td>
                              <td>
                                 <input type="text" name="Cess[]" id="Cess1" class="form-control form-control-sm"
                                    onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                              </td>
                              <td>
                                 <input type="text" name="TAmount[]" id="TAmount1"
                                    class="form-control form-control-sm TAmount" readonly>
                              </td>
                              <td>
                                 <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                                    style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                              </td>
                           </tr>
                        </tbody>
                        <tr>
                           <td colspan="6"></td>
                           <td><b>Sub Total (₹):</b></td>
                           <td colspan="2">
                              <input type="text" name="Sub_Total" id="Sub_Total"
                                 class="form-control form-control-sm" readonly
                                 value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : '' ?>">
                           </td>
                        </tr>
                        <tr id="tcs_tr">
                           <td colspan="7" style="text-align: right;"><b>TCS %:</b></td>
                           <td colspan="2">
                              <input type="text" name="TCS" id="TCS" class="form-control form-control-sm"
                                 onKeyPress="return isNumberKey(event)" onchange="cal_tax()"
                                 value="<?= (isset($punch_detail->TCS)) ? $punch_detail->TCS : '' ?>">
                           </td>
                        </tr>
                        <tr>
                           <td colspan="7" style="text-align: right;"><b>Total (₹):</b></td>
                           <td colspan="2">
                              <input type="text" name="Total" id="Total" class="form-control form-control-sm"
                                 readonly
                                 value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
                           </td>
                        </tr>
                        <tr>
                           <td colspan="7" style="text-align: right;"><b>Round Off (₹):</b></td>
                           <td colspan="5">
                              <input type="text" name="Total_Discount" id="Total_Discount"
                                 class="form-control form-control-sm d-inline"
                                 value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>"
                                 style="width:100px;">
                              <span><input type="radio" name="plus_minus" id="plus" class="plus_minus" <?php
                                 if (isset($punch_detail->Total_Discount)) {
                                 	if ($punch_detail->Grand_Total > $punch_detail->Total_Amount) {
                                 		echo "checked";
                                 	}
                                 }
                                 ?>>
                              <label for="plus">Plus</label>
                              </span>
                              <span><input type="radio" name="plus_minus" id="minus" class="plus_minus" <?php
                                 if (isset($punch_detail->Total_Discount)) {
                                 	if ($punch_detail->Grand_Total < $punch_detail->Total_Amount) {
                                 		echo "checked";
                                 	}
                                 } else {
                                 	echo "checked";
                                 }
                                 ?>> <label for="minus">Minus</label></span>
                           </td>
                        </tr>
                        <td colspan="7" style="text-align: right;"><b>Grand Total (₹):</b></td>
                        <td colspan="2">
                           <input type="text" name="Grand_Total" id="Grand_Total"
                              class="form-control form-control-sm" readonly
                              value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>">
                        </td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-12">
                     <label for="">Remark / Comment:</label>
                     <textarea name="Remark" id="Remark" cols="10" rows="2"
                        class="form-control form-control-sm"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
                  </div>
               </div>
               <div class="box-footer">
                  <button type="reset" class="btn btn-danger">Reset</button>
                  <input type="submit" class="btn btn-info pull-right" style="margin-left: 20px;" name="save_as_draft" value="Save as Draft"></input>
                  <input type="submit" class="btn btn-success pull-right" name="submit_punch" value="Final Submit"></input>
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
                           <a href="javascript:void(0);" target="popup"
                              onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
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
         <?php 
            }
            }
            
            ?>
   </form>
   </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
   data-backdrop="static">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Item/Particular</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group">
                     <label for="">Item/Particular Name: </label><span class="text-danger">*</span>
                     <input type="text" name="item_name" id="item_name" class="form-control">
                     <div class="form-group" id="name_error"></div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="">Item Code:</label>
                     <input type="text" name="item_code" id="item_code" class="form-control">
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="save_btn">Save changes</button>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function () {
      $(".select2").select2();
   	$("#From").select2();
   	$("#To").select2();
    $('#invoice-tab').click(function() {
               
                $('#additional-info').removeClass('active');
            
                $('#invoice-details').addClass('active');
                
            
                $('.tabs').removeClass('active-tab');
                $(this).addClass('active-tab');
            });
   
            $('#additional-info-tab').click(function() {
        
                $('#invoice-details').removeClass('active');
        
                $('#additional-info').addClass('active');
   
                $('.tabs').removeClass('active-tab');
                $(this).addClass('active-tab');
            });
   	$(document).on("change", "#From", function () {
   		var address = $(this).find(':selected').data('address');
   		$("#Buyer_Address").val(address);
   	});
   	$(document).on("change", "#To", function () {
   		var address = $(this).find(':selected').data('address');
   		$("#Vendor_Address").val(address);
   	});
   	$(".particular").select2({
   		allowClear: true,
   		escapeMarkup: function (markup) {
   			return markup;
   		},
   		placeholder: "Select Item/Particular",
   		language: {
   			noResults: function () {
   	
   				return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>";
   			}
   		}
   	});
   
   	var unit_list;
   	getUnitList();
   
   	function getUnitList() {
   		$.ajax({
   			type: "POST",
   			url: '<?= base_url() ?>Unit/get_unit_list',
   			dataType: "json",
   			success: function (response) {
   
   				if (response.status == 200) {
   					var x = '<option value="" selected >Select Unit</option>';
   					$.each(response.unit_list, function (k, v) {
   						x =
   							x +
   							'<option value="' +
   							v.unit_id +
   							'">' +
   							v.unit_name +
   
   							"</option>";
   					});
   				}
   
   				unit_list = x;
   			},
   		});
   	}
   
   	var item_list;
   	getItemList();
   
   	function getItemList() {
   		$.ajax({
   			type: "POST",
   			url: '<?= base_url() ?>Item/get_item_list',
   			dataType: "json",
   			success: function (response) {
   
   				if (response.status == 200) {
   					var x = '<option value="" selected >Select Item/Particular</option>';
   					$.each(response.item_list, function (k, v) {
   						x =
   							x +
   							'<option value="' +
   							v.item_name +
   							'">' +
   							v.item_name +
   
   							"</option>";
   					});
   				}
   
   				item_list = x;
   			},
   		});
   	}
   
   	$('.datepicker').datetimepicker({
   		timepicker: false,
   		format: 'Y-m-d',
   	});
   
   
   	var Count = 1;
   
   	getMultiRecord();
   
   	function getMultiRecord() {
   		var Scan_Id = $('#Scan_Id').val();
   		$.ajax({
   			url: '<?= base_url() ?>form/Invoice_ctrl/getInvoiceItem',
   			type: 'POST',
   			data: {
   				Scan_Id: Scan_Id
   			},
   			dataType: 'json',
   			success: function (response) {
   
   				if (response.status == 200) {
   					Count = (response.data).length;
   
   					for (var i = 1; i <= Count; i++) {
   						if (i >= 2) {
   							multi_record(i);
   						}
   						$("#Particular" + i).val(response.data[i - 1].Particular).trigger('change');
   						$("#HSN" + i).val(response.data[i - 1].HSN);
   						$("#Qty" + i).val(response.data[i - 1].Qty);
   						$("#Unit" + i).val(response.data[i - 1].Unit);
   						$("#MRP" + i).val(response.data[i - 1].MRP);
   						$("#Discount" + i).val(response.data[i - 1].Discount);
   						$("#GST" + i).val(response.data[i - 1].GST);
   						$("#SGST" + i).val(response.data[i - 1].SGST);
   						$("#IGST" + i).val(response.data[i - 1].IGST);
   						$("#Cess" + i).val(response.data[i - 1].Cess);
   						$("#Price" + i).val(response.data[i - 1].Price);
   						$("#Amount" + i).val(response.data[i - 1].Amount);
   						$("#TAmount" + i).val(response.data[i - 1].Total_Amount);
   					}
   				}
   			}
   		});
   	}
   
   	$(document).on('click', '#add', function () {
   		Count++;
   		multi_record(Count);
   	});
   
   	$(document).on('click', '#remove', function () {
   
   		$(this).closest('tr').remove();
   		calculate(Count);
   
   	});
   
   	function multi_record(num) {
   		var html = '';
   		html += '<tr>';
   		html += '<td>' + num + '</td>';
   		html += '<td>';
   		html += '<select  name="Particular[]" id="Particular' + num + '" class="form-control form-control-sm particular">' + item_list + '</select>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="HSN[]" id="HSN' + num + '" class="form-control form-control-sm">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Qty[]" id="Qty' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<select  name="Unit[]" id="Unit' + num + '" class="form-control form-control-sm">' + unit_list + '</select>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="MRP[]" id="MRP' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Discount[]" id="Discount' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Price[]" id="Price' + num + '" class="form-control form-control-sm" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Amount[]" id="Amount' + num + '" class="form-control form-control-sm Amount" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="GST[]" id="GST' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="SGST[]" id="SGST' + num + '" class="form-control form-control-sm" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="IGST[]" id="IGST' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Cess[]" id="Cess' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="TAmount[]" id="TAmount' + num + '" class="form-control form-control-sm TAmount" readonly>';
   		html += '</td>';
   
   		html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
   		html += '</tr>';
   		$('#multi_record').append(html);
   		$('.datepicker').datetimepicker({});
   		$('.particular').select2({
   			allowClear: true,
   			escapeMarkup: function (markup) {
   				return markup;
   			},
   			placeholder: "Select Item/Particular",
   			language: {
   				noResults: function () {
   	
   					return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>";
   				}
   			}
   		});
   	}
   
   });
   
   function isNumberKey(evt) {
   	var charCode = evt.which ? evt.which : evt.keyCode;
   	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
   		return false;
   
   	return true;
   }
   
   $(document).on('change', '.plus_minus', function () {
   	var id = $(this).attr('id');
   	var Total_Discount = $('#Total_Discount').val();
   	var Total = $('#Total').val();
   	if (id == 'plus') {
   		var Total = parseFloat($('#Total').val()) + parseFloat(Total_Discount);
   		$('#Grand_Total').val(Total);
   	} else {
   		var Total = parseFloat($('#Total').val()) - parseFloat(Total_Discount);
   		$('#Grand_Total').val(Total);
   	}
   });
   
   
   function calculate(num) {
   	var Qty = $('#Qty' + num).val();
   	var MRP = $('#MRP' + num).val();
   	var Discount = $('#Discount' + num).val();
   	var GST = $('#GST' + num).val();
   	var SGST = $('#SGST' + num).val(GST);
   	var IGST = $('#IGST' + num).val();
   	var Cess = $('#Cess' + num).val();
   	var TAmount = 0;
   	if (isNaN(Qty) || Qty == '') {
   		Qty = 0;
   	}
   	if (isNaN(MRP) || MRP == '') {
   		MRP = 0;
   	}
   	if (isNaN(Discount) || Discount == '') {
   		Discount = 0;
   	}
   	if (isNaN(GST) || GST == '') {
   		GST = 0;
   	}
   	if (isNaN(SGST) || SGST == '') {
   		SGST = 0;
   	}
   	if (isNaN(IGST) || IGST == '') {
   		IGST = 0;
   	}
   	if (isNaN(Cess) || Cess == '') {
   		Cess = 0;
   	}
   	
   	if (parseFloat(Discount) > parseFloat(MRP)) {
          alert('Discount cannot be greater than MRP');
          Discount = 0;
          $('#Discount' + num).val(Discount);
           }
   
   	if (GST > 0) {
   		$('#IGST' + num).attr('readonly', true);
   		$('#IGST' + num).val();
   	} else {
   		$('#IGST' + num).attr('readonly', false);
   		$('#IGST' + num).val();
   	}
   
   	var Price = MRP - Discount;
   	$('#Price' + num).val(Price);
   	var Amount = Qty * Price;
   	$('#Amount' + num).val(Amount);
   	$('#TAmount' + num).val(Amount);
   
   	var GSTAmount = (Amount * (GST * 2)) / 100;
   	var AF_GSTAmount = Amount + GSTAmount;
   
   	$('#TAmount' + num).val(AF_GSTAmount);
   	if (GST == 0 || GST == '') {
   		var IGSTAmount = (Amount * IGST) / 100;
   		var AF_IGSTAmount = Amount + IGSTAmount;
   		$('#TAmount' + num).val(AF_IGSTAmount);
   	}
   
   	if (Cess > 0) {
   		var CessAmount = (Amount * Cess) / 100;
   		var TAmount = $('#TAmount' + num).val();
   		var FinalAmount = parseFloat(TAmount) + parseFloat(CessAmount);
   		$('#TAmount' + num).val(FinalAmount);
   	}
   	var SubTotal = 0;
   	var Total = 0;
   	$('.TAmount').each(function () {
   		SubTotal += +$(this).val();
   	});
   
   	$("#Sub_Total").val(SubTotal);
   	$("#Total").val(SubTotal.toFixed(2));
   
   	//split decimal point from total amount
   	var total_amount = $("#Total").val();
   	var total_amount_array = total_amount.split('.');
   	var total_amount_int = total_amount_array[0];
   	var total_amount_dec = total_amount_array[1];
   
   	//check total amount is decimal or not
   	if (total_amount_dec == undefined) {
   		total_amount_dec = 0;
   	}
   
   	$("#Total_Discount").val('0.' + total_amount_dec);
   
   	$("#Grand_Total").val(total_amount_int);
   
   }
   
   function cal_tax() {
   	var TCS = $('#TCS').val();
   	var Sub_Total = $('#Sub_Total').val();
   
   
   	if (isNaN(TCS) || TCS == '') {
   		TCS = 0;
   	} else {
   		TCS = (TCS / 100) * Sub_Total;
   	}
   
   
   	var Total = (parseFloat(Sub_Total) + parseFloat(TCS));
   	$('#Total').val(Total);
   	var total_amount = $("#Total").val();
   	var total_amount_array = total_amount.split('.');
   	var total_amount_int = total_amount_array[0];
   	var total_amount_dec = total_amount_array[1];
   
   	//check total amount is decimal or not
   	if (total_amount_dec == undefined) {
   		total_amount_dec = 0;
   	}
   
   	$("#Total_Discount").val('0.' + total_amount_dec);
   	var Discount = $('#Total_Discount').val();
   	var Grand_Total = Total - Discount;
   	$('#Grand_Total').val(Grand_Total);
   
   }
   
   function getFile() {
   	var Company = $("#From").val();
   	$.ajax({
   		url: '<?= base_url() ?>Punch/getFileList',
   		type: 'POST',
   		data: {
   			Company: Company
   		},
   		dataType: 'json',
   		success: function (response) {
   			$("#File").empty();
   			if (response.status == 200) {
   				$("#File").append('<option value="">Select File</option>');
   				$.each(response.data, function (key, value) {
   					$('#File').append('<option value="' + value.file_name + '">' + value.file_name + '</option>');
   				});
   			} else {
   				$("#File").append('<option value="">Select File</option>');
   			}
   		}
   	});
   }
   
   function getDepartment() {
   	var Company = $("#From").val();
   	$.ajax({
   		url: '<?= base_url() ?>Punch/getDepartmentList',
   		type: 'POST',
   		data: {
   			Company: Company
   		},
   		dataType: 'json',
   		success: function (response) {
   			$("#Department").empty();
   			if (response.status == 200) {
   				$("#Department").append('<option value="">Select Department</option>');
   				$.each(response.data, function (key, value) {
   					$('#Department').append('<option value="' + value.department_id + '">' + value.department_name + '</option>');
   				});
   			} else {
   				$("#File").append('<option value="">Select File</option>');
   			}
   		}
   	});
   }
   
   $(document).on('click', "#save_btn", function () {
   	var item_name = $("#item_name").val();
   	var item_code = $("#item_code").val();
   
   	if (item_name == '' || item_name == null) {
   		$("#item_name").focus();
   		$("#item_name").css('border-color', 'red');
   		return false;
   	}
   	$.ajax({
   		type: 'POST',
   		url: '<?= base_url() ?>form/Invoice_ctrl/add_item',
   		data: {
   			item_name: item_name,
   			item_code: item_code
   		},
   
   		async: false,
   		dataType: 'json',
   		beforeSend: function () {
   		},
   		success: function (response) {
   			if (response.status == 200) {
   				$(".particular").append('<option value="' + item_name + '">' + item_name + '</option>');
   				$("#item_name").val('');
   				$("#item_code").val('');
   				$("#myModal").modal('hide');
   			} else {
   				alert(response.msg);
   			}
   		},
   
   	});
   });
   
   $("#item_name").on('keyup', function () {
   	$("#item_name").css('border-color', '');
   });
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
   $(document).ready(function() {
      
      let rowCount = 1;
   
   function fetchAccountOptions(inputType, query) {
    return $.ajax({
        url: '<?php echo base_url("journal-entry/get-account-options");?>',
        type: 'GET',
        data: {
            type: inputType, 
            query: query
        },
        dataType: 'json'
    });
   }
   
   
   function initializeAutocomplete(rowId) {
    
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

   
   
   
   $(`#${rowId} .debit-ac`).autocomplete({
    source: function(request, response) {
        fetchAccountOptions('debit', request.term).done(function(data) {
            if (Array.isArray(data)) {
                response(data.map(account => {
                    return {
                        label: account.account_name,
                        value: account.account_name,
                        id: account.id
                    };
                }));
            } else {
                console.error('Expected an array but received:', data);
            }
        });
    },
    minLength: 2,
    select: function(event, ui) {
        $(this).val(ui.item.label);
        $(this).siblings('.hidden-debit-id').remove();
        $(this).after(`<input type="hidden" class="hidden-debit-id" name="debit_ac_id[]" value="${ui.item.id}">`);
        
        
        let parentRow = $(this).closest('.row'); 
        let subLedgerDropdown = parentRow.find('.subledger');
      
        
        fetchSubLedgerOptions(ui.item.value, subLedgerDropdown);
        
        return false; 
    }
});


function fetchSubLedgerOptions(debitAccountId, subLedgerDropdown) {
    $.ajax({
        url: '<?php echo base_url("form/JournalEntry_ctrl/getSubLedgers");?>',
        type: 'POST',
        data: { debit_ac_id: debitAccountId },
        dataType: 'json',
        success: function(data) {
         console.log(data);
            subLedgerDropdown.empty(); 
            subLedgerDropdown.append('<option value="">Select Subledger</option>');
            if (Array.isArray(data)) {
                data.forEach(function(subLedger) {
                    subLedgerDropdown.append(`<option value="${subLedger.id}">${subLedger.sub_ledger}</option>`);
                });
            } else {
                console.error('Failed to fetch sub-ledgers: Unexpected response format.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + error);
        }
    });
}


   
    
    $(`#${rowId} .creadit-ac`).autocomplete({
        source: function(request, response) {
            fetchAccountOptions('credit', request.term).done(function(data) {
                console.log('Credit Data:', data);
                if (Array.isArray(data)) {
                    response(data.map(account => {
                        return {
                            label: account.account_name,
                            value: account.account_name,
                            id: account.id
                        };
                    }));
                } else {
                    console.error('Expected an array but received:', data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            
            $(this).val(ui.item.label);
   
            
            $(this).siblings('.hidden-creadit-id').remove();
   
            
            $(this).after(`<input type="hidden" class="hidden-creadit-id" name="credit_ac_id[]" value="${ui.item.id}">`);
   
            return false; 
        }
    });
   }
   
   
   
   initializeAutocomplete('row_1');
   
   function updateBillAmount() {
    let total = 0;

    
    $('.amount').each(function() {
        let value = parseFloat($(this).val()) || 0;
        total += value;
    });

    
    var Grand_Total = parseFloat("<?= isset($punch_detail->Grand_Total) ? $punch_detail->Grand_Total : 0 ?>");

    let TDS_amount = $('#tdsAmount').val() || 0;

    
    var maxAllowedAmount = total + parseFloat(TDS_amount);

  
    if (maxAllowedAmount > Grand_Total) {
        alert("Total bill amount cannot exceed the Grand Total including the TDS amount!");

        
        $('#finalSubmitBtn').attr('disabled', 'disabled');
        $('#f_save_as_draft').attr('disabled', 'disabled');
    } else {
        
        $('#finalSubmitBtn').removeAttr('disabled');
        $('#f_save_as_draft').removeAttr('disabled');
    }

    $('#billAmount').val(maxAllowedAmount.toFixed(2));
}


   
   
   $('#add_row').click(function() {
    rowCount++;
    let newRow = $('#row_1').clone().attr('id', 'row_' + rowCount);
    
    
    newRow.find('select, input').each(function() {
        let $this = $(this);
        let id = $this.attr('id');
   
        if (id) {
            let newId = id.replace(/\d+$/, '') + rowCount;
            $this.attr('id', newId);
        }
   
        if ($this.is('input[type="text"], input[type="number"], input[type="hidden"], select')) {
            $this.val(''); 
        }
    });
   
    newRow.find('.remove_row').show(); 
    $('#rows_container').append(newRow); 
   
    
    initializeAutocomplete(newRow.attr('id'));
   
    updateBillAmount(); 
   });
   
   
   $(document).on('click', '.remove_row', function() {
    let totalRows = $('.form-row').length;
   
    if (totalRows > 1) {
        $(this).closest('.form-row').remove();
        updateBillAmount(); 
    } else {
        alert('At least one row must remain.');
    }
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
   });
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
   
   var billAmount = parseFloat("<?= isset($punch_detail->Total_Amount) ? $punch_detail->Total_Amount : 0 ?>");

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
</script>