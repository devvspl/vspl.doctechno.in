<div class="box-body">
   <div style="display: flex; flex-direction: column; align-items: center;"> <div class="loader" id="loader" style="display: none;"></div><span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #3a495e;">Please Wait...</span></div>
   <div class="row">
      <div class="col-md-4">
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
      <?php 
       $vspl_file_entry = $this->uri->segment(1);
         if ($vspl_file_entry == 'vspl_file_entry') {
            ?>
         <div class="tabs-container">
            <div class="tabs active-tab" id="invoice-tab">Invoice Details</div>
            <div class="tabs" id="additional-info-tab">Additional Information</div>
         </div>
         <?php 
         }
     
      ?>
      <div id="invoice-details" class="tab-content active">
         <div class="col-md-8" id="contnetBody">
            <?php echo $this->session->flashdata('message'); ?>
            <form action="<?= base_url(); ?>form/InvoiceController/create" id="invoice_form" name="invoice_form" method="post"
               accept-charset="utf-8">
               <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
               <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-group col-md-3">
                     <label for="">Invoice No: <span class="text-danger">*</span></label>
                     <input type="text" name="Bill_No" id="Bill_No" required class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Invoice Date: <span class="text-danger">*</span></label>
                     <input type="date" name="Bill_Date" id="Bill_Date" required class="form-control form-control-sm"
                        value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Purchase Order No.:</label>
                     <input type="text" name="Buyer_Order" id="Buyer_Order"  class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Purchase Order Date:</label>
                     <input type="text" name="Buyer_Order_Date" id="Buyer_Order_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : '' ?>">
                  </div>

               </div>
               <div class="row" style="margin-bottom: 5px;">
                  <div class="form-gro	up col-md-6">
                     <label for="">Buyer: <span class="text-danger">*</span></label>
                     <small class="text-danger">
                     <?php echo isset($temp_punch_detail->buyer) ? $temp_punch_detail->buyer : ''; ?>

                     </small>
                     <select name="From" id="From" class="form-control form-control-sm" required>
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
                  <label for="" style="display: flex;justify-content: space-between;">
                  <div>
                  <span>Vendor:<span class="text-danger">*</span></span>
                     <small class="text-danger">
                        <?php echo isset($temp_punch_detail->vendor) ? $temp_punch_detail->vendor : ''; ?>

                     </small></div>
                     <div id="newVendorOption" style="display: <?php echo isset($punch_detail->To_ID) && $punch_detail->To_ID ? 'none' : 'block'; ?>;">
                        <a href="javascript:void(0);"  class="btn-link" id="addVendorBtn" data-toggle="modal" data-target="#addVendorModal">
                              Add New Vendor
                        </a>
                     </div>
                  </label>

                  
                     <select name="To" id="To" class="form-control form-control-sm" required>
                        <option value="">Select</option>
                        <?php
                              $isVendorSelected = false; 
                              foreach ($firm as $key => $value) {
                                 if (isset($punch_detail->To_ID) && $value['firm_id'] == $punch_detail->To_ID) {
                                    $isVendorSelected = true;
                                    echo '<option value="' . $value['firm_id'] . '" data-address="' . $value['address'] . '" selected>' . $value['firm_name'] . '</option>';
                                 } else {
                                    echo '<option value="' . $value['firm_id'] . '" data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
                                 }
                              }
                        ?>
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
                     <label for="">Dispatch Through:</label>
                     <input type="text" name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm" value="<?= (isset($punch_detail->Particular)) ? $punch_detail->Particular : '' ?>">
                  </div>
                  <div class="form-group col-md-3">
                     <label for="">Delivery Note Date:</label>
                     <input type="text" name="Delivery_Note_Date" id="Delivery_Note_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : '' ?>">
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
                                 <select name="Particular[]" id="Particular1" required
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
               
                  <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
                  <input type="submit" class="btn btn-info pull-right"  name="save_as_draft" value="Save as Draft"></input>
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
            </form>
         </div>
      </div>
      <div id="additional-info" class="tab-content">
      <div class="col-md-8">
         <form action="<?= base_url(); ?>form/VSPL_cash_voucher_ctrl/create" id="cash_voucher_form" name="cash_voucher_form" method="post">
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
                  <?php foreach ($voucher_items->result_array() as $entry): ?>
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
                              foreach ($core_department as $core_department): ?>
                           <option 
                              value="<?= $core_department['api_id']; ?>" 
                              <?= ($core_department['api_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $core_department['department_name']; ?>
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
                              foreach ($core_business_unit as $core_business_unit): ?>
                           <option 
                              value="<?= $core_business_unit['api_id']; ?>" 
                              <?= ($core_business_unit['api_id'] == $selected) ? 'selected' : ''; ?>
                              >
                              <?= $core_business_unit['business_unit_name']; ?>
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
                              foreach ($core_region as $core_region): ?>
                           <option value="<?= $core_region['api_id']; ?>" <?= ($core_region['api_id'] == $selectedRegion) ? 'selected' : ''; ?>>
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
                                foreach ($core_state as $core_state): ?>
                           <option value="<?= $core_state['api_id']; ?>" <?= ($core_state['api_id'] == $selectedState) ? 'selected' : ''; ?>>
                              <?= $core_state['state_name']; ?>
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
                              foreach ($master_payment_method as $method) { 
                           ?>
                              <option 
                                 value="<?= $method['payment_method_name']; ?>" 
                                 <?= ($method['payment_method_name'] == $selectedPaymentMethod) ? 'selected' : ''; ?>
                              >
                                 <?= $method['payment_method_name']; ?>
                              </option>
                           <?php } ?>
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
                     $num_row =  $voucher_items->num_rows();
                     
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
                              foreach ($core_department as $department): ?>
                           <option 
                              value="<?= $department['api_id']; ?>" 
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
                              foreach ($core_business_unit as $core_business_unit): ?>
                           <option 
                              value="<?= $core_business_unit['api_id']; ?>" 
                              >
                              <?= $core_business_unit['business_unit_name']; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group col-md-4">
                        <label for="region">Region</label>
                        <select name="region_id[]" id="region_id" class="form-control region_id">
                           <option value="">Select Region</option>
                           <?php
                              
                                  foreach ($core_region as $list): ?>
                           <option 
                              value="<?= $list['api_id']; ?>" 
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
                              foreach ($core_state as $core_state): ?>
                           <option 
                              value="<?= $core_state['api_id']; ?>" 
                              >
                              <?= $core_state['state_name']; ?>
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
                           <?php foreach ($master_payment_method as $method): ?>
                              <option value="<?= $method['payment_method_name']; ?>">
                                 <?= $method['payment_method_name']; ?>
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
<div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Vendor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
             <div id="formMessage" class="alert" style="display:none;"></div>
            <form id="addVendorForm">
               <div class="form-group">
                  <label for="firm_name">Vendor Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="firm_name" name="firm_name" required>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="firm_type">Vendor Type <span class="text-danger">*</span></label>
                     <select class="form-control" id="firm_type" name="firm_type" required>
                        <option value="Vendor" selected>Vendor</option>
                       
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="firm_code">Vendor Code</label>
                     <input type="text" class="form-control" id="firm_code" name="firm_code">
                  </div>
               </div>
               <div class="form-group">
                  <label for="gst">GST</label>
                  <input type="text" class="form-control" id="gst" name="gst">
               </div>
               <div class="form-group">
                  <label for="address">Address</label>
                  <textarea class="form-control" id="address" name="address"></textarea>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="country">Country  <span class="text-danger">*</span></label>
                     <select class="form-control" id="country_id" name="country_id" required>
                        <option value="">Select</option>
                        <option value="India">India</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="state">State  <span class="text-danger">*</span></label>
                     <select class="form-control" id="state_id" name="state_id" required>
                        <option value="">Select</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="city">City</label>
                     <input type="text" class="form-control" id="city" name="city">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="pin_code">Pin Code</label>
                     <input type="text" class="form-control" id="pin_code" name="pin_code">
                  </div>
               </div>
             
               <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary mt-2">Add Vendor</button>
               </div>
            
            </form>
         </div>
      </div>
   </div>
</div>
<script>
$(document).ready(function () {
    initUI();

    let unitList = "";
    let itemList = "";
    let count = 1;
    loadUnitList();
    loadItemList();
    getMultiRecord();

    $(document).on("change", "#From", updateBuyerAddress);
    $(document).on("change", "#To", updateVendorAddress);
    $(document).on("click", "#add", addItemRow);
    $(document).on("click", ".removeRow", removeItemRow);
    $(document).on("change", ".plus_minus", calculatePlusMinus);

    $("#To").on("change", function () {
        var selectedVendor = $(this).val();
        if (selectedVendor === "") {
            $("#newVendorOption").show();
        } else {
            $("#newVendorOption").hide();
        }
    });

    $("#addVendorBtn").click(function () {
        getCountry();
    });

    $("#country_id").on("change", function () {
        let countryId = $(this).val();
        if (countryId) {
            getState(countryId);
        } else {
            $("#state_id").html('<option value="">-- Select State --</option>');
        }
    });

    $("#addVendorForm").on("submit", function (e) {
        e.preventDefault();

        $("#formMessage").hide().removeClass("alert-success alert-danger").html("");

        $.ajax({
            url: '<?= base_url("new-vendor-request") ?>',
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $("#formMessage").addClass("alert alert-success").html(response.message).slideDown();

                    $("#addVendorForm")[0].reset();
                    setTimeout(function () {
                        $("#addVendorModal").modal("hide");
                    }, 1000);
                } else {
                    $("#formMessage").addClass("alert alert-danger").html(response.message).slideDown();
                }
            },
            error: function () {
                $("#formMessage").addClass("alert alert-danger").html("Something went wrong! Please try again.").slideDown();
            },
        });
    });

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
               url: '<?php echo base_url("form/JournalEntry_ctrl/getSubLedgers");?>',
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
               url: "<?php echo base_url('form/JournalEntry_ctrl/getAllAccountList');?>",
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

function getCountry() {
    $.ajax({
        url: '<?= base_url("get-country") ?>',
        type: "GET",
        dataType: "json",
        success: function (response) {
            let options = '<option value="">-- Select Country --</option>';
            $.each(response, function (index, country) {
                options += `<option value="${country.api_id}">${country.country_name} (${country.country_code})</option>`;
            });
            $("#country_id").html(options);
        },
    });
}

function getState(countryId) {
    $.ajax({
        url: '<?= base_url("get-state") ?>',
        type: "POST",
        data: { country_id: countryId },
        dataType: "json",
        success: function (response) {
            let options = '<option value="">-- Select State --</option>';
            $.each(response, function (index, state) {
                options += `<option value="${state.api_id}">${state.state_name} (${state.short_code})</option>`;
            });
            $("#state_id").html(options);
        },
    });
}

function initUI() {
    $("#From, #To").select2();
    $(".datepicker").datetimepicker({ timepicker: false, format: "Y-m-d" });
    $(".particular").select2({
        allowClear: true,
        escapeMarkup: (m) => m,
        placeholder: "Select Item/Particular",
        language: {
            noResults: () => "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>",
        },
    });
}

function updateBuyerAddress() {
    const address = $(this).find(":selected").data("address");
    $("#Buyer_Address").val(address);
}

function updateVendorAddress() {
    const address = $(this).find(":selected").data("address");
    $("#Vendor_Address").val(address);
}

function toggleLoader(show, tableId) {
    const loader = $("#loader");
    const loaderText = $("#loader-text");
    const table = $("#" + tableId);

    loader.css("marginTop", show ? "230px" : "0");
    loader.toggle(show);
    loaderText.toggle(show);
    table.css("visibility", show ? "hidden" : "visible");
}

function loadUnitList() {
    $.post(
        "<?= base_url() ?>master/UnitController/get_unit_list",
        (response) => {
            if (response.status === 200) {
                unitList = `<option value="">Select Unit</option>` + response.unit_list.map((v) => `<option value="${v.unit_id}">${v.unit_name}</option>`).join("");
            }
        },
        "json"
    );
}

function loadItemList() {
    $.post(
        "<?= base_url() ?>master/ItemController/get_item_list",
        (response) => {
            if (response.status === 200) {
                itemList = `<option value="">Select Item/Particular</option>` + response.item_list.map((v) => `<option value="${v.item_name}">${v.item_name}</option>`).join("");
            }
        },
        "json"
    );
}

function getMultiRecord() {
    const scanId = $("#Scan_Id").val();
    toggleLoader(true, "contnetBody");

    $.post(
        "<?= base_url() ?>form/InvoiceController/getInvoiceItem",
        { Scan_Id: scanId },
        (response) => {
            if (response.status === 200) {
                count = response.data.length;
                response.data.forEach((item, index) => {
                    if (index > 0) addItemRow();
                    populateRow(index + 1, item);
                });
            }
        },
        "json"
    )
        .always(() => toggleLoader(false, "contnetBody"))
        .fail(() => alert("Error fetching data."));
}

function populateRow(index, item) {
    $(`#Particular${index}`).val(item.Particular).trigger("change");
    $(`#HSN${index}`).val(item.HSN);
    $(`#Qty${index}`).val(item.Qty);
    $(`#Unit${index}`).val(item.Unit);
    $(`#MRP${index}`).val(item.MRP);
    $(`#Discount${index}`).val(item.Discount);
    $(`#GST${index}`).val(item.GST);
    $(`#SGST${index}`).val(item.SGST);
    $(`#IGST${index}`).val(item.IGST);
    $(`#Cess${index}`).val(item.Cess);
    $(`#Price${index}`).val(item.Price);
    $(`#Amount${index}`).val(item.Amount);
    $(`#TAmount${index}`).val(item.Total_Amount);
}

function addItemRow() {
    let currentRows = $("#multi_record tr").length;
    let serialNo = currentRows + 1;

    let html = `
        <tr>
           <td>${serialNo}</td>
           <td><select name="Particular[]" id="Particular${serialNo}" class="form-control form-control-sm particular">${itemList}</select></td>
           <td><input type="text" name="HSN[]" id="HSN${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="Qty[]" id="Qty${serialNo}" class="form-control form-control-sm"></td>
           <td><select name="Unit[]" id="Unit${serialNo}" class="form-control form-control-sm">${unitList}</select></td>
           <td><input type="text" name="MRP[]" id="MRP${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="Discount[]" id="Discount${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="Price[]" id="Price${serialNo}" class="form-control form-control-sm" readonly></td>
           <td><input type="text" name="Amount[]" id="Amount${serialNo}" class="form-control form-control-sm Amount" readonly></td>
           <td><input type="text" name="GST[]" id="GST${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="SGST[]" id="SGST${serialNo}" class="form-control form-control-sm" readonly></td>
           <td><input type="text" name="IGST[]" id="IGST${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="Cess[]" id="Cess${serialNo}" class="form-control form-control-sm"></td>
           <td><input type="text" name="TAmount[]" id="TAmount${serialNo}" class="form-control form-control-sm TAmount" readonly></td>
           <td><button type="button" name="remove" class="btn btn-danger btn-xs removeRow"><i class="fa fa-minus"></i></button></td>
        </tr>`;

    $("#multi_record").append(html);

   
    $("#Qty" + serialNo + ", #MRP" + serialNo + ", #Discount" + serialNo + ", #GST" + serialNo + ", #IGST" + serialNo + ", #Cess" + serialNo)
        .on("keypress", function (e) {
            return isNumberKey(e);
        })
        .on("change", function () {
            calculate(serialNo);
        });

    initUI();
}

function removeItemRow() {
    $(this).closest("tr").remove();
    calculate(count);
    updateSerialNumbers();
}

function updateSerialNumbers() {
    $("#multi_record tr").each(function (index) {
        $(this)
            .find("td:first")
            .text(index + 1);
    });
}

function isNumberKey(evt) {
    const charCode = evt.which || evt.keyCode;
    return !(charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57));
}

function calculate(num) {
    let qty = parseFloat($("#Qty" + num).val()) || 0;
    let mrp = parseFloat($("#MRP" + num).val()) || 0;
    let discount = parseFloat($("#Discount" + num).val()) || 0;
    let gst = parseFloat($("#GST" + num).val()) || 0;
    let igst = parseFloat($("#IGST" + num).val()) || 0;
    let cess = parseFloat($("#Cess" + num).val()) || 0;

    let price = mrp - (mrp * discount) / 100;
    let amount = qty * price;
    let totalGst = (amount * gst) / 100;
    let totalIgst = (amount * igst) / 100;
    let totalCess = (amount * cess) / 100;
    let totalAmount = amount + totalGst + totalIgst + totalCess;

    let sgst = igst == 0 ? gst / 2 : 0;
    let totalSgst = (amount * sgst) / 100;

    if (igst == 0) {
        totalAmount += totalSgst;
        $("#SGST" + num).val(sgst.toFixed(2));
    } else {
        $("#SGST" + num).val("0.00");
    }

    $("#Price" + num).val(price.toFixed(2));
    $("#Amount" + num).val(amount.toFixed(2));
    $("#TAmount" + num).val(totalAmount.toFixed(2));

    // Calculate Subtotal and Total
    let subTotal = 0;
    let grandTotal = 0;

    $("[id^='Amount']").each(function () {
        let val = parseFloat($(this).val()) || 0;
        subTotal += val;
    });

    $("[id^='TAmount']").each(function () {
        let val = parseFloat($(this).val()) || 0;
        grandTotal += val;
    });

    $("#Sub_Total").val(subTotal.toFixed(2));
    $("#Total").val(grandTotal.toFixed(2));

    // Apply TCS
    let tcsRate = parseFloat($("#TCS").val()) || 0;
    let tcsAmount = (grandTotal * tcsRate) / 100;
    let finalGrandTotal = grandTotal + tcsAmount;

    $("#Grand_Total").val(finalGrandTotal.toFixed(2));
}


function calculatePlusMinus() {
    const discount = parseFloat($("#Total_Discount").val()) || 0;
    let total = parseFloat($("#Total").val()) || 0;
    if ($(this).attr("id") === "plus") {
        total += discount;
    } else {
        total -= discount;
    }
    $("#Grand_Total").val(total.toFixed(2));
}

window.cal_tax = function () {
        let tcs = parseFloat($("#TCS").val()) || 0;
        let subTotal = parseFloat($("#Sub_Total").val()) || 0;
        let tcsAmount = (tcs / 100) * subTotal;
        let total = subTotal + tcsAmount;

        const decimal = (total % 1).toFixed(2).split(".")[1] || "00";
        $("#Total").val(total.toFixed(2));
        $("#Total_Discount").val("0." + decimal);
        $("#Grand_Total").val((total - parseFloat("0." + decimal)).toFixed(2));
};
</script>
