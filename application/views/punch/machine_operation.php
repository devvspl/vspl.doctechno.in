<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form" method="post" accept-charset="utf-8">
    <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
    <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
    <div class="row">
        <div class="form-group col-md-5">
            <label for="company_name">Company Name:</label> <span class="text-danger">*</span>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company_name : ''; ?>
            </small>
            <select name="company_name" id="company_id" class="form-control" required data-parsley-errors-container="#CompanyError">
                <option value="">Select</option>
                
            </select>
            <div id="CompanyError"></div>
        </div>
        <div class="form-group col-md-7">
            <label for="company_address">Company Address:</label>
            <input type="text" name="company_address" id="company_address" class="form-control"
                   value="<?= (isset($punch_detail->company_address)) ? htmlspecialchars($punch_detail->company_address) : '' ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-5">
            <label for="vendor_name">Vendor Name:</label> <span class="text-danger">*</span>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vendor_name : ''; ?>
            </small>
            <select name="vendor_name" id="vendor_id" class="form-control" required data-parsley-errors-container="#VendorError">
                <option value="">Select</option>
               
            </select>
            <div id="VendorError"></div>
        </div>
        <div class="form-group col-md-7">
            <label for="vendor_address">Vendor Address:</label>
            <input type="text" name="vendor_address" id="vendor_address" class="form-control"
                   value="<?= (isset($punch_detail->vendor_address)) ? htmlspecialchars($punch_detail->vendor_address) : '' ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="vehicle_no">Vehicle No:</label>
            <input type="text" name="vehicle_no" id="vehicle_no" class="form-control"
                   value="<?= (isset($punch_detail->vehicle_no)) ? htmlspecialchars($punch_detail->vehicle_no) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="vehicle_type">Vehicle Type:</label>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vehicle_type : ''; ?>
            </small>
            <select name="vehicle_type" id="vehicle_type" class="form-control">
                <option value="">Select</option>
                <?php
                $vehicle_types = ['Tractor', 'JCB'];
                foreach ($vehicle_types as $value) { ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?php if (isset($punch_detail->vehicle_type) && $punch_detail->vehicle_type == $value) echo 'selected'; ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="location">Location:</label>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="location" id="location_id" class="form-control">
                <option value="">Select</option>
                
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="invoice_date">Invoice Date:</label>
            <input type="text" name="invoice_date" id="invoice_date" class="form-control datepicker"
                   value="<?= (isset($punch_detail->invoice_date)) ? date('Y-m-d', strtotime($punch_detail->invoice_date)) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="particular">Particular:</label>
            <input type="text" name="particular" id="particular" class="form-control"
                   value="<?= (isset($punch_detail->particular)) ? htmlspecialchars($punch_detail->particular) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="hour">Hour:</label>
            <input type="number" step="0.01" name="hour" id="hour" class="form-control"
                   value="<?= (isset($punch_detail->hour)) ? htmlspecialchars($punch_detail->hour) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="trips">Trips:</label>
            <input type="number" min="1" step="1" name="trips" id="trips" class="form-control"
                   onchange="calculate();"
                   value="<?= (isset($punch_detail->trips)) ? htmlspecialchars($punch_detail->trips) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="rate_per_trip">Rate per Trip:</label>
            <input type="number" min="1" step="0.5" name="rate_per_trip" id="rate_per_trip" class="form-control"
                   onchange="calculate();"
                   value="<?= (isset($punch_detail->rate_per_trip)) ? htmlspecialchars($punch_detail->rate_per_trip) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="total_amount">Total Amount:</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control final_amount_column" readonly
                   value="<?= (isset($punch_detail->total_amount)) ? htmlspecialchars($punch_detail->total_amount) : '' ?>">
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
   $("#company_id").select2();
   $("#vendor_id").select2();
   $("#location_id").select2();
   $(document).on("change", "#company_id", function () {
      var address = $(this).find(':selected').data('address');
      $("#company_address").val(address);
   });

   $(document).on("change", "#vendor_id", function () {
      var address = $(this).find(':selected').data('address');
      $("#vendor_address").val(address);
   });
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d'
   });

   function calculate() {
      var trip = $("#trips").val();
      if (trip == '' || trip == null || trip == undefined || trip == NaN) {
         trip = 1;
      }
      var rate = $("#rate_per_trip").val();
      if (rate == '' || rate == null || rate == undefined || rate == NaN) {
         rate = 1;
      }
      var total = trip * rate;

      $("#total_amount").val(parseFloat(total).toFixed(2));
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
         isset($temp_punch_detail->company_name) && !is_null($temp_punch_detail->company_name) ? $temp_punch_detail->company_name : ""
      );
      $cleanedVendor = cleanSearchValue(
         isset($temp_punch_detail->vendor_name) && !is_null($temp_punch_detail->vendor_name) ? $temp_punch_detail->vendor_name : ""
      );
      $cleanedlocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
      );
      ?>


      loadDropdownOptions(
         'company_id',
         '<?= base_url("extract/ExtractorController/get_company_options") ?>',
         <?= json_encode($cleanedBuyer) ?>,
         '<?= isset($punch_detail->company_name) ? $punch_detail->company_name : "" ?>'
      );


      loadDropdownOptions(
         'vendor_id',
         '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
         <?= json_encode($cleanedVendor) ?>,
         '<?= isset($punch_detail->vendor_name) ? $punch_detail->vendor_name : "" ?>'
      );

      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedlocation) ?>,
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );

   });
</script>