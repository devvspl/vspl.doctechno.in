<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/create" id="punch_form" name="punch_form" method="post"
      accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
         <div class="row">
            <div class="col-md-4 form-group ">
               <label for="">Company:</label><span class="text-danger">*</span>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company : ''; ?>
               </small>
               <select name="Company" id="Company" class="form-control" required
                  data-parsley-errors-container="#companyError">
                  <option value="">Select</option>
                
               </select>
               <div id="companyError"></div>
            </div>
            <div class="col-md-4 form-group ">
               <label for="">Voucher No:</label><span class="text-danger">*</span>
               <input type="text" name="VoucherNo" id="VoucherNo" class="form-control"
                  value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>" required>
            </div>
            <div class="col-md-4 form-group ">
               <label for="">Voucher Date:</label><span class="text-danger">*</span>
               <input type="text" name="Voucher_Date" id="Voucher_Date" class="form-control datepicker"
                  value="<?= (isset($punch_detail->RegPurDate)) ? date('Y-m-d', strtotime($punch_detail->RegPurDate)) : '' ?>"
                  required>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 form-group">
               <label for="">Location:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="Location" id="Location" class="form-control form-control-sm" required
                  data-parsley-errors-container="#LocationError">
                  <option value="">Select</option>
                 
               </select>
               <div id="LocationError"></div>
            </div>
            <div class="col-md-6 form-group ">
               <label for="">Vendor:</label><span class="text-danger">*</span>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vendor: ''; ?>
               </small>
               <select name="Vendor" id="Vendor" class="form-control" 
                  data-parsley-errors-container="#vendorError">
                  <option value="">Select</option>
                 
               </select>
               <div id="vendorError"></div>
            </div>
            <div class="col-md-2 form-group">
               <label for="">Amount:</label><span class="text-danger">*</span>
               <input type="text" name="Amount" id="Amount" class="form-control"
                  value="<?= (isset($punch_detail->TotalAmount)) ? $punch_detail->TotalAmount : '' ?>"
                  required>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 form-group">
               <label for="">Particular:</label><span class="text-danger">*</span>
               <input type="text" name="Particular" id="Particular" class="form-control"
                  value="<?= (isset($punch_detail->Additional_Exposure)) ? $punch_detail->Additional_Exposure : '' ?>"
                  required>
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
   $(document).ready(function () {
   	$("#Company").select2();
   	$("#Vendor").select2();
   	$("#Location").select2();
   	$(".datepicker").datetimepicker({
   		timepicker: false,
   		format: 'Y-m-d',
   		input: false
   	});
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
        isset($temp_punch_detail->company) && !is_null($temp_punch_detail->company) ? $temp_punch_detail->company : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor) && !is_null($temp_punch_detail->vendor) ? $temp_punch_detail->vendor : ""
    );
     $cleanedlocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>


    loadDropdownOptions(
        'Company',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->CompanyID) ? $punch_detail->CompanyID : "" ?>'
    );


    loadDropdownOptions(
        'Vendor',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->VendorID) ? $punch_detail->VendorID : "" ?>'
    );

     loadDropdownOptions(
        'location_id',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->location_id) ? $punch_detail->location_id : "" ?>'
    );

});
</script>