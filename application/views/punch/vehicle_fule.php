<div id="invoice-details" class="tab-content active">
    <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form" method="post" accept-charset="utf-8">
        <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
        <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="vendor_name">Vendor Name:</label> <span class="text-danger">*</span>
                <small class="text-danger">
                    <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->vendor_name) : ''; ?>
                </small>
                <select name="vendor_name" id="vendor_name" class="form-control" required data-parsley-errors-container="#VendorError">
                    <option value="">Select</option>
                    <?php foreach ($vendor_list as $vendor) { ?>
                        <option value="<?= htmlspecialchars($vendor['id']) ?>" <?php if (isset($punch_detail->vendor_name) && $punch_detail->vendor_name == $vendor['id']) echo 'selected'; ?>>
                            <?= htmlspecialchars($vendor['vendor_name']) ?>
                        </option>
                    <?php } ?>
                </select>
                <div id="VendorError"></div>
            </div>
            <div class="form-group col-md-6">
                <label for="billing_to">Billing To:</label> <span class="text-danger">*</span>
                <small class="text-danger">
                    <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->billing_to) : ''; ?>
                </small>
                <select name="billing_to" id="billing_to" class="form-control" required data-parsley-errors-container="#BillingToError">
                    <option value="">Select</option>
                    <?php foreach ($company_list as $company) { ?>
                        <option value="<?= htmlspecialchars($company['id']) ?>" <?php if (isset($punch_detail->billing_to) && $punch_detail->billing_to == $company['id']) echo 'selected'; ?>>
                            <?= htmlspecialchars($company['company_name']) ?>
                        </option>
                    <?php } ?>
                </select>
                <div id="BillingToError"></div>
            </div>
            <div class="form-group col-md-3">
                <label for="dealer_code">Dealer Code:</label>
                <input type="text" name="dealer_code" id="dealer_code" class="form-control"
                       value="<?= (isset($punch_detail->dealer_code)) ? htmlspecialchars($punch_detail->dealer_code) : '' ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="invoice_no">Invoice No:</label> <span class="text-danger">*</span>
                <input type="text" name="invoice_no" id="invoice_no" class="form-control" required
                       value="<?= (isset($punch_detail->invoice_no)) ? htmlspecialchars($punch_detail->invoice_no) : '' ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="invoice_date">Invoice Date:</label> <span class="text-danger">*</span>
                <input type="text" name="invoice_date" id="invoice_date" class="form-control datepicker" required
                       value="<?= (isset($punch_detail->invoice_date)) ? date('Y-m-d', strtotime($punch_detail->invoice_date)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="due_date">Due Date:</label>
                <input type="text" name="due_date" id="due_date" class="form-control datepicker"
                       value="<?= (isset($punch_detail->due_date)) ? date('Y-m-d', strtotime($punch_detail->due_date)) : '' ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="location">Location:</label> <span class="text-danger">*</span>
                <small class="text-danger">
                    <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->location) : ''; ?>
                </small>
                <select name="location" id="location" class="form-control" required data-parsley-errors-container="#LocationError">
                    <option value="">Select</option>
                    <?php foreach ($location_list as $loc) { ?>
                        <option value="<?= htmlspecialchars($loc['location_name']) ?>" <?php if (isset($punch_detail->location) && $punch_detail->location == $loc['location_name']) echo 'selected'; ?>>
                            <?= htmlspecialchars($loc['location_name']) ?>
                        </option>
                    <?php } ?>
                </select>
                <div id="LocationError"></div>
            </div>
            <div class="form-group col-md-6">
                <label for="vehicle_no">Vehicle No:</label> <span class="text-danger">*</span>
                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required
                       value="<?= (isset($punch_detail->vehicle_no)) ? htmlspecialchars($punch_detail->vehicle_no) : '' ?>">
            </div>
            <div class="form-group col-md-12">
                <label for="description">Description:</label>
                <input type="text" name="description" id="description" class="form-control"
                       value="<?= (isset($punch_detail->description)) ? htmlspecialchars($punch_detail->description) : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="liters">Liters:</label> <span class="text-danger">*</span>
                <input type="number" min="0" step="0.001" name="liters" id="liters" class="form-control" onchange="calculate();" required
                       value="<?= (isset($punch_detail->liters)) ? htmlspecialchars($punch_detail->liters) : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="per_liter_rate">Per Liter Rate:</label> <span class="text-danger">*</span>
                <input type="number" min="0" step="0.01" name="per_liter_rate" id="per_liter_rate" class="form-control" onchange="calculate();" required
                       value="<?= (isset($punch_detail->per_liter_rate)) ? htmlspecialchars($punch_detail->per_liter_rate) : '' ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" name="amount" id="amount" readonly class="form-control"
                       value="<?= (isset($punch_detail->amount)) ? htmlspecialchars($punch_detail->amount) : '' ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <td style="text-align: right;"><b>Round Off (₹):</b></td>
                        <td>
                            <input type="number" step="0.01" name="round_off_value" id="round_off_value" class="form-control d-inline"
                                   value="<?= (isset($punch_detail->round_off_value)) ? htmlspecialchars($punch_detail->round_off_value) : '0.00' ?>" style="width:200px;">
                            <span>
                                <input type="radio" name="round_off_type" id="plus" value="Plus" class="plus_minus" <?php
                                    if (isset($punch_detail->round_off_type) && $punch_detail->round_off_type == 'Plus') echo 'checked';
                                    else if (!isset($punch_detail->round_off_type)) echo 'checked';
                                ?>>
                                <label for="plus">Plus</label>
                            </span>
                            <span>
                                <input type="radio" name="round_off_type" id="minus" value="Minus" class="plus_minus" <?php
                                    if (isset($punch_detail->round_off_type) && $punch_detail->round_off_type == 'Minus') echo 'checked';
                                ?>>
                                <label for="minus">Minus</label>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><b>Grand Total (₹):</b></td>
                        <td>
                            <input type="number" step="0.01" name="grand_total" id="grand_total" class="form-control" readonly
                                   value="<?= (isset($punch_detail->grand_total)) ? htmlspecialchars($punch_detail->grand_total) : '' ?>">
                        </td>
                    </tr>
                </table>
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
  $("#vendor_name").select2();
$("#billing_to").select2();
$("#location").select2();
$(".datepicker").datetimepicker({
    timepicker: false,
    format: 'Y-m-d'
});

function calculate() {
    var liters = parseFloat($('#liters').val()) || 0;
    var rate = parseFloat($('#per_liter_rate').val()) || 0;
    var amount = liters * rate;
    $('#amount').val(amount.toFixed(2));

    var roundOffValue = parseFloat($('#round_off_value').val()) || 0;
    var roundOffType = $('input[name="round_off_type"]:checked').val() || 'Plus';
    var grandTotal = roundOffType === 'Plus' ? amount + roundOffValue : amount - roundOffValue;

    $('#grand_total').val(grandTotal.toFixed(2));
}

$(document).on('change', '.plus_minus, #round_off_value', function() {
    calculate();
});

$(document).ready(function() {
    $("#invoice-tab").click(function() {
        $("#additional-info").removeClass("active");
        $("#invoice-details").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });

    $("#additional-info-tab").click(function() {
        $("#invoice-details").removeClass("active");
        $("#additional-info").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });

    <?php
    $cleanedBuyer = cleanSearchValue(
        isset($temp_punch_detail->billing_to) && !is_null($temp_punch_detail->billing_to) ? $temp_punch_detail->billing_to : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor_name) && !is_null($temp_punch_detail->vendor_name) ? $temp_punch_detail->vendor_name : ""
    );
    $cleanedLocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>

    loadDropdownOptions(
        'billing_to',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->billing_to) ? $punch_detail->billing_to : "" ?>'
    );

    loadDropdownOptions(
        'vendor_name',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->vendor_name) ? $punch_detail->vendor_name : "" ?>'
    );

    loadDropdownOptions(
        'location',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedLocation) ?>,
        '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
    );

    // Initialize calculation
    calculate();
});
</script>