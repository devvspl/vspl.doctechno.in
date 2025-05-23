<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Vehicle_ctrl/save_hired_vehicle" id="punch_form" name="punch_form"
      method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-5">
               <label for="">Agency Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->agency_name : ''; ?>
               </small>
               <select name="Agency_Name" id="Agency_Name" class="form-control" required
                  data-parsley-errors-container="#AgencyError">
                  <option value="">Select</option>
                 
               </select>
               <div id="AgencyError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="">Agency Address:</label>
               <input type="text" name="Agency_Address" id="Agency_Address" class="form-control"
                  value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-5">
               <label for="">Billing Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->billing_name : ''; ?>
               </small>
               <select name="Billing_Name" id="Billing_Name" class="form-control" required
                  data-parsley-errors-container="#BillingNameError" >
                  <option value="">Select</option>
                 
               </select>
               <div id="BillingNameError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="">Billing Address:</label>
               <input type="text" name="Billing_Address" id="Billing_Address" class="form-control" value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : '' ?>" readonly/>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-4">
               <label for="">Employee Name:</label>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->employee_name : ''; ?>
               </small>
               <select name="Employee" id="Employee" class="form-control">
                  <option value="">Select</option>
                  <?php
                     foreach ($employee_list as $key => $value) {
                     	$selected = '';
                     	if (isset($punch_detail->EmployeeID) && $punch_detail->EmployeeID == $value['id']) {
                     		$selected = 'selected';
                     	}
                     	echo '<option value="' . $value['id'] . '" ' . $selected . ' data-code="' . $value['emp_code'] . '">' . $value['emp_name'] . ' - ' . $value['company_code'] . '</option>';
                     }
                     ?>
               </select>
            </div>
            <div class="col-md-4">
               <label for="">Emp Code :</label>
               <input type="text" name="Emp_Code" id="Emp_Code" class="form-control" value="<?= (isset($punch_detail->EmployeeCode)) ? $punch_detail->EmployeeCode : '' ?>" readonly>
            </div>
            <div class="form-group col-md-4">
               <label for="" id="">Vehicle No:</label> <span class="text-danger">*</span>
               <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" required
                  value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-3">
               <label for="">Location :</label>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="Location" id="Location" class="form-control">
                  <option value="">Select Location</option>
                
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="">Invoice No:</label> <span class="text-danger">*</span>
               <input type="text" name="Invoice_No" id="Invoice_No" class="form-control " required
                  value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Invoice Date:</label> <span class="text-danger">*</span>
               <input type="text" name="Invoice_Date" id="Invoice_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : '' ?>" required>
            </div>
            <div class="col-md-3">
               <label for="">Per KM Rate :</label> <span class="text-danger">*</span>
               <input type="number" name="Per_KM_Rate" id="Per_KM_Rate" class="form-control"
                  required onchange="calculate();" min="1" step="0.1" value="<?= (isset($punch_detail->VehicleRs_PerKM)) ? $punch_detail->VehicleRs_PerKM : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Booking Date:</label> <span class="text-danger">*</span>
               <input type="text" name="Journey_Start" id="Journey_Start" class="form-control datepicker"
                  required
                  value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">End Date:</label> <span class="text-danger">*</span>
               <input type="text" name="Journey_End" id="Journey_End" class="form-control datepicker" required
                  value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d', strtotime($punch_detail->ToDateTime)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Start Reading:</label> <span class="text-danger">*</span>
               <input type="text" name="Opening_Reading" id="Opening_Reading" class="form-control" required
                  value="<?= (isset($punch_detail->OpeningKm)) ? $punch_detail->OpeningKm : '' ?>"
                  onchange="calculate();">
            </div>
            <div class="form-group col-md-3">
               <label for="">Closing Reading:</label> <span class="text-danger">*</span>
               <input type="text" name="Closing_Reading" id="Closing_Reading" class="form-control" required
                  value="<?= (isset($punch_detail->ClosingKm)) ? $punch_detail->ClosingKm : '' ?>"
                  onchange="calculate();">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-4">
               <label for="">Total KM:</label>
               <input type="text" name="Total_KM" id="Total_KM" class="form-control" readonly  value="<?= (isset($punch_detail->TotalRunKM)) ? $punch_detail->TotalRunKM : '' ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Other Charges:</label>
               <input type="text" name="Other_Charge" id="Other_Charge" class="form-control"
                  value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : '' ?>"
                  onchange="calculate();">
            </div>
            <div class="col-md-4 form-group" style="float: right;">
               <label for="">Total Amount</label>
               <input type="text" name="Total_Amount" id="Total_Amount" class="form-control"
                  value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="">Remark / Comment:</label>
               <textarea name="Remark" id="Remark" cols="10" rows="3"
                  class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
            </div>
         </div>
         <div class="box-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <?php if (!empty($user_permission) &&  $user_permission == 'N') : ?>
               <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
            <?php endif; ?>
          
            <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')) : ?>
            <input type="submit" class="btn btn-info pull-right"  name="save_as_draft" value="Save as Draft"></input>
            <?php endif; ?>
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
                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
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
<script>
   $("#Agency_Name").select2();
   $("#Billing_Name").select2();
   $("#Employee").select2();
   $("#Location").select2();
   
   $(document).on("change", "#Agency_Name", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#Agency_Address").val(address);
   });
   
   $(document).on("change", "#Billing_Name", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#Billing_Address").val(address);
   });
   
   $(document).on("change", "#Employee", function () {
   	var code = $(this).find(':selected').data('code');
   	$("#Emp_Code").val(code);
   });
   
   function calculate() {
   
   	var Per_KM_Rate = $('#Per_KM_Rate').val();
   	var Opening_Reading = $('#Opening_Reading').val();
   	var Closing_Reading = $('#Closing_Reading').val();
   	var Other_Charge = $('#Other_Charge').val();
   
   	if (Per_KM_Rate == '' || Per_KM_Rate == null) {
   		Per_KM_Rate = 0;
   	}
   	if (Opening_Reading == '' || Opening_Reading == null) {
   		Opening_Reading = 0;
   	}
   	if (Closing_Reading == '' || Closing_Reading == null) {
   		Closing_Reading = 0;
   	}
   	if (Other_Charge == '' || Other_Charge == null) {
   		Other_Charge = 0;
   	}
   	var Total_KM = parseFloat(Closing_Reading) - parseFloat(Opening_Reading);
   	$('#Total_KM').val(Total_KM.toFixed(2));
   
   
   	var Total_Amount =  (parseFloat(Total_KM) * parseFloat(Per_KM_Rate) )+ parseFloat(Other_Charge);
   	$('#Total_Amount').val(Total_Amount.toFixed(2));
   }
   
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d'
   });
   $(document).ready(function () {
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
    
<?php
    $cleanedBuyer = cleanSearchValue(
        isset($temp_punch_detail->buyer) && !is_null($temp_punch_detail->buyer) ? $temp_punch_detail->buyer : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor) && !is_null($temp_punch_detail->vendor) ? $temp_punch_detail->vendor : ""
    );
     $cleanedlocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>


    loadDropdownOptions(
        'Billing_Name',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->From_ID) ? $punch_detail->From_ID : "" ?>'
    );


    loadDropdownOptions(
        'Agency_Name',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->To_ID) ? $punch_detail->To_ID : "" ?>'
    );

     loadDropdownOptions(
        'location_id',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->Loc_Name) ? $punch_detail->Loc_Name : "" ?>'
    );


});
</script>