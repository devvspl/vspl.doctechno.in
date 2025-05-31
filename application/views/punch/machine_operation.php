<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Vehicle_ctrl/save_machine_operation" id="punch_form" name="punch_form" method="post"
      accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
         <div class="row">
            <div class="form-group col-md-5">
               <label for="">Company Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company_name: ''; ?>
               </small>
               <select name="CompanyID" id="CompanyID" class="form-control" required
                  data-parsley-errors-container="#CompanyError">
                  <option value="">Select</option>
                 
               </select>
               <div id="CompanyError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="">Company Address:</label>
               <input type="text" name="Related_Address" id="Related_Address" class="form-control"
                  value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : '' ?>"
                  readonly/>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-5">
               <label for="">Vendor Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vendor_name: ''; ?>
               </small>
               <select name="To_ID" id="To_ID" class="form-control" required
                  data-parsley-errors-container="#VendorError">
                  <option value="">Select</option>
                 
               </select>
               <div id="VendorError"></div>
            </div>
            <div class="form-group col-md-7">
               <label for="">Vendor Address:</label>
               <input type="text" name="AgencyAddress" id="AgencyAddress" class="form-control"
                  value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="Vehicle_No">Vehicle No.</label>
               <input type="text" name="VehicleRegNo" id="VehicleRegNo" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>"/>
            </div>
            <div class="form-group col-md-3">
               <label for="Vehicle_Type">Vehicle Type:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vehicle_type : ''; ?>
               </small>
               <select name="Vehicle_Type" id="Vehicle_Type" class="form-control form-select form-select-sm">
                  <option value="">Select</option>
                  <?php
                     $vehicle_types = array('Tractor', 'JCB');
                     ?>
                  <?php foreach ($vehicle_types as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="col-md-3 form-group">
               <label for="">Location:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="Location" id="location_id" class="form-control form-control-sm">
                  <option value="">Select</option>
                  <?php foreach ($worklocation_list as $key => $value) { ?>
                  <option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
                     if ($value['location_name'] == $punch_detail->Loc_Name) {
                     	echo "selected";
                     }
                     } ?>><?= $value['location_name'] ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form-group col-md-3">
               <label> Invoice Date:</label>
               <input type="text" id="Invoice_Date" name="Invoice_Date"
                  class="form-control form-control-sm datepicker"
                  value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 form-group">
               <label for="">Particular :</label>
               <input type="text" class="form-control form-control-sm" id="Particular" name="Particular"
                  value="<?= (isset($punch_detail->Particular)) ? $punch_detail->Particular : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Hour:</label>
               <input type="text" name="Hour" id="Hour" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Trips:</label>
               <input type="number" min="1" step="1" name="Trip" id="Trip" class="form-control form-control-sm"
                  onchange="calculate();"
                  value="<?= (isset($punch_detail->TotalRunKM)) ? $punch_detail->TotalRunKM : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Rate per Trip:</label>
               <input type="number" min="1" step="0.5" name="Rate" id="Rate"
                  class="form-control form-control-sm"
                  onchange="calculate();"
                  value="<?= (isset($punch_detail->RateOfInterest)) ? $punch_detail->RateOfInterest : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Total Amount:</label>
               <input type="text" name="Total_Amount" id="Total_Amount" class="form-control form-control-sm"
                  readonly
                  value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
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
            if ($this->customlib->haveSupportFile($scan_id) == 1) {
            	?>
         <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
               <label for="">Supporting File:</label>
               <div class="form-group">
                  <?php
                     $support_file = $this->customlib->getSupportFile($scan_id);
                     
                     foreach ($support_file as $row) {
                     	?>
                  <div class="col-md-3">
                     <a href="javascript:void(0);" target="popup"
                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                     <?php echo $row['file_name'] ?></a>
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
   $("#CompanyID").select2();
   $("#To_ID").select2();
   $("#location_id").select2();
   $(document).on("change", "#CompanyID", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#Related_Address").val(address);
   });
   
   $(document).on("change", "#To_ID", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#AgencyAddress").val(address);
   });
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d'
   });
   
   function calculate() {
   	var trip = $("#Trip").val();
   	if (trip == '' || trip == null || trip == undefined || trip == NaN) {
   		trip = 1;
   	}
   	var rate = $("#Rate").val();
   	if (rate == '' || rate == null || rate == undefined || rate == NaN) {
   		rate = 1;
   	}
   	var total = trip * rate;
   
   	$("#Total_Amount").val(parseFloat(total).toFixed(2));
   }

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
        isset($temp_punch_detail->company_name) && !is_null($temp_punch_detail->company) ? $temp_punch_detail->company : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor_name) && !is_null($temp_punch_detail->vendor_name) ? $temp_punch_detail->vendor_name : ""
    );
     $cleanedlocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>


    loadDropdownOptions(
        'CompanyID',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->CompanyID) ? $punch_detail->CompanyID : "" ?>'
    );


    loadDropdownOptions(
        'To_ID',
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