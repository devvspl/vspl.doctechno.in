<div id="invoice-details" class="tab-content active">
<form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form" method="post" accept-charset="utf-8">
    <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
    <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="company">Company:</label><span class="text-danger">*</span>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->company) : ''; ?>
            </small>
            <select name="company" id="company" class="form-control" required data-parsley-errors-container="#companyError">
                <option value="">Select</option>
               
            </select>
            <div id="companyError"></div>
        </div>
        <div class="form-group col-md-4">
            <label for="voucher_no">Voucher No:</label><span class="text-danger">*</span>
            <input type="text" name="voucher_no" id="voucher_no" class="form-control"
                   value="<?= (isset($punch_detail->voucher_no)) ? htmlspecialchars($punch_detail->voucher_no) : '' ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label for="voucher_date">Voucher Date:</label><span class="text-danger">*</span>
            <input type="text" name="voucher_date" id="voucher_date" class="form-control datepicker"
                   value="<?= (isset($punch_detail->voucher_date)) ? date('Y-m-d', strtotime($punch_detail->voucher_date)) : '' ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label for="location">Location:</label><span class="text-danger">*</span>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->location) : ''; ?>
            </small>
            <select name="location" id="location_id" class="form-control" required data-parsley-errors-container="#LocationError">
                <option value="">Select</option>
              
            </select>
            <div id="LocationError"></div>
        </div>
        <div class="form-group col-md-6">
            <label for="vendor">Vendor:</label><span class="text-danger">*</span>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->vendor) : ''; ?>
            </small>
            <select name="vendor" id="vendor" class="form-control" required data-parsley-errors-container="#vendorError">
                <option value="">Select</option>
             
            </select>
            <div id="vendorError"></div>
        </div>
        <div class="form-group col-md-2">
            <label for="amount">Amount:</label><span class="text-danger">*</span>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control final_amount_column"
                   value="<?= (isset($punch_detail->amount)) ? htmlspecialchars($punch_detail->amount) : '' ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="particular">Particular:</label><span class="text-danger">*</span>
            <input type="text" name="particular" id="particular" class="form-control"
                   value="<?= (isset($punch_detail->particular)) ? htmlspecialchars($punch_detail->particular) : '' ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="remark_comment">Remark / Comment:</label>
            <textarea name="remark_comment" id="remark_comment" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->remark_comment)) ? htmlspecialchars($punch_detail->remark_comment) : '' ?></textarea>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-danger">Reset</button>
        <?php if (!empty($user_permission) && $user_permission == 'N'): ?>
            <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
        <?php endif; ?>
        <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')): ?>
            <input type="submit" class="btn btn-info pull-right" name="save_as_draft" value="Save as Draft"></input>
        <?php endif; ?>
    </div>
    <?php if ($this->customlib->haveSupportFile($scan_id) == 1): ?>
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
                               onclick="window.open('<?= htmlspecialchars($row['file_path']) ?>','popup','width=600,height=600');">
                                <?= htmlspecialchars($row['file_name']) ?>
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
   $(document).ready(function () {
   	$("#company").select2();
   	$("#vendor").select2();
   	$("#location_id").select2();
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
        'company',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->company) ? $punch_detail->company : "" ?>'
    );


    loadDropdownOptions(
        'vendor',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->vendor) ? $punch_detail->vendor : "" ?>'
    );

     loadDropdownOptions(
        'location_id',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
    );

});
</script>