<div id="invoice-details" class="tab-content active">
<form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form"
      method="post" accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
         <div class="row">
            <div class="form-group col-md-5">
               <label for="agency_name">Agency Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->agency_name : ''; ?>
               </small>
               <select name="agency_name" id="agency_name" class="form-control" required
                  data-parsley-errors-container="#AgencyError">
                  <option value="">Select</option>
               </select>
               <div id="AgencyError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="agency_address">Agency Address:</label>
               <input type="text" name="agency_address" id="agency_address" class="form-control"
                  value="<?= (isset($punch_detail->agency_address)) ? $punch_detail->agency_address : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-5">
               <label for="billing_name">Billing Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->billing_name : ''; ?>
               </small>
               <select name="billing_name" id="billing_name" class="form-control" required
                  data-parsley-errors-container="#BillingNameError">
                  <option value="">Select</option>
               </select>
               <div id="BillingNameError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="billing_address">Billing Address:</label>
               <input type="text" name="billing_address" id="billing_address" class="form-control" 
                  value="<?= (isset($punch_detail->billing_address)) ? $punch_detail->billing_address : '' ?>" readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-4">
               <label for="employee_name">Employee Name:</label>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->employee_name : ''; ?>
               </small>
               <select name="employee_name" id="employee_name" class="form-control">
                  <option value="">Select</option>
                  <?php
                     foreach ($employee_list as $value) {
                        $selected = (isset($punch_detail->employee_name) && $punch_detail->employee_name == $value['id']) ? 'selected' : '';
                        echo '<option value="' . $value['id'] . '" ' . $selected . ' data-code="' . $value['emp_code'] . '">' . $value['emp_name'] . ' - ' . $value['company_code'] . '</option>';
                     }
                  ?>
               </select>
            </div>
            <div class="form-group col-md-4">
               <label for="emp_code">Emp Code:</label>
               <input type="text" name="emp_code" id="emp_code" class="form-control" 
                  value="<?= (isset($punch_detail->emp_code)) ? $punch_detail->emp_code : '' ?>" readonly>
            </div>
            <div class="form-group col-md-4">
               <label for="vehicle_no">Vehicle No:</label> <span class="text-danger">*</span>
               <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required
                  value="<?= (isset($punch_detail->vehicle_no)) ? $punch_detail->vehicle_no : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="location">Location:</label>
               <small class="text-danger">
                 <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="location" id="location_id" class="form-control">
                  <option value="">Select Location</option>
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="invoice_no">Invoice No:</label> <span class="text-danger">*</span>
               <input type="text" name="invoice_no" id="invoice_no" class="form-control" required
                  value="<?= (isset($punch_detail->invoice_no)) ? $punch_detail->invoice_no : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="invoice_date">Invoice Date:</label> <span class="text-danger">*</span>
               <input type="text" name="invoice_date" id="invoice_date" class="form-control datepicker" 
                  value="<?= (isset($punch_detail->invoice_date)) ? date('Y-m-d', strtotime($punch_detail->invoice_date)) : '' ?>" required>
            </div>
            <div class="form-group col-md-3">
               <label for="per_km_rate">Per KM Rate:</label> <span class="text-danger">*</span>
               <input type="number" name="per_km_rate" id="per_km_rate" class="form-control" required 
                  onchange="calculate();" min="1" step="0.1" 
                  value="<?= (isset($punch_detail->per_km_rate)) ? $punch_detail->per_km_rate : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="booking_date">Booking Date:</label> <span class="text-danger">*</span>
               <input type="text" name="booking_date" id="booking_date" class="form-control datepicker" required
                  value="<?= (isset($punch_detail->booking_date)) ? date('Y-m-d', strtotime($punch_detail->booking_date)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="end_date">End Date:</label> <span class="text-danger">*</span>
               <input type="text" name="end_date" id="end_date" class="form-control datepicker" required
                  value="<?= (isset($punch_detail->end_date)) ? date('Y-m-d', strtotime($punch_detail->end_date)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="start_reading">Start Reading:</label> <span class="text-danger">*</span>
               <input type="text" name="start_reading" id="start_reading" class="form-control" required
                  value="<?= (isset($punch_detail->start_reading)) ? $punch_detail->start_reading : '' ?>" 
                  onchange="calculate();">
            </div>
            <div class="form-group col-md-3">
               <label for="closing_reading">Closing Reading:</label> <span class="text-danger">*</span>
               <input type="text" name="closing_reading" id="closing_reading" class="form-control" required
                  value="<?= (isset($punch_detail->closing_reading)) ? $punch_detail->closing_reading : '' ?>" 
                  onchange="calculate();">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-4">
               <label for="total_km">Total KM:</label>
               <input type="text" name="total_km" id="total_km" class="form-control" readonly 
                  value="<?= (isset($punch_detail->total_km)) ? $punch_detail->total_km : '' ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="other_charges">Other Charges:</label>
               <input type="text" name="other_charges" id="other_charges" class="form-control"
                  value="<?= (isset($punch_detail->other_charges)) ? $punch_detail->other_charges : '' ?>" 
                  onchange="calculate();">
            </div>
            <div class="form-group col-md-4" style="float: right;">
               <label for="total_amount">Total Amount:</label>
               <input type="text" name="total_amount" id="total_amount" class="form-control final_amount_column"
                  value="<?= (isset($punch_detail->total_amount)) ? $punch_detail->total_amount : '' ?>" 
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="remark_comment">Remark / Comment:</label>
               <textarea name="remark_comment" id="remark_comment" cols="10" rows="3" 
                  class="form-control"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : '' ?></textarea>
            </div>
         </div>
         <div class="box-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <?php if (!empty($user_permission) && $user_permission == 'N') : ?>
               <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
            <?php endif; ?>
            <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')) : ?>
            <input type="submit" class="btn btn-info pull-right" name="save_as_draft" value="Save as Draft"></input>
            <?php endif; ?>
         </div>
         <?php if ($this->customlib->haveSupportFile($scan_id) == 1) : ?>
         <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
               <label for="supporting_file">Supporting File:</label>
               <div class="form-group">
                  <?php
                     $support_file = $this->customlib->getSupportFile($scan_id);
                     foreach ($support_file as $row) {
                  ?>
                  <div class="col-md-3">
                     <a href="javascript:void(0);" target="popup" 
                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> 
                        <?php echo $row['file_name'] ?>
                     </a>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
         <?php endif; ?>
   </form>
</div>
<script>
   $("#agency_name").select2();
   $("#billing_name").select2();
   $("#employee_name").select2();
   $("#location_id").select2();
   
   $(document).on("change", "#agency_name", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#agency_address").val(address);
   });
   
   $(document).on("change", "#billing_name", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#billing_address").val(address);
   });
   
   $(document).on("change", "#employee_name", function () {
   	var code = $(this).find(':selected').data('code');
   	$("#emp_code").val(code);
   });
   
   function calculate() {
   
   	var per_km_rate = $('#per_km_rate').val();
   	var start_reading = $('#start_reading').val();
   	var closing_reading = $('#closing_reading').val();
   	var other_charges = $('#other_charges').val();
   
   	if (per_km_rate == '' || per_km_rate == null) {
   		per_km_rate = 0;
   	}
   	if (start_reading == '' || start_reading == null) {
   		start_reading = 0;
   	}
   	if (closing_reading == '' || closing_reading == null) {
   		closing_reading = 0;
   	}
   	if (other_charges == '' || other_charges == null) {
   		other_charges = 0;
   	}
   	var total_km = parseFloat(closing_reading) - parseFloat(start_reading);
   	$('#total_km').val(total_km.toFixed(2));
   
   
   	var total_amount =  (parseFloat(total_km) * parseFloat(per_km_rate) )+ parseFloat(other_charges);
   	$('#total_amount').val(total_amount.toFixed(2));
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
        'billing_name',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->billing_name) ? $punch_detail->billing_name : "" ?>'
    );


    loadDropdownOptions(
        'agency_name',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->agency_name) ? $punch_detail->agency_name : "" ?>'
    );

     loadDropdownOptions(
        'location_id',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
    );


});
</script>