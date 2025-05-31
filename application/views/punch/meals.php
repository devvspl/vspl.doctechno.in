<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="Form" name="Form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-4">
            <label for="hotel_name">Hotel Name:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->hotel_name) : ''; ?>
            </small>
            <select name="hotel_name" id="hotel" class="form-control">
               <option value="">Select</option>
               <?php foreach ($hotel_list as $hotel) { ?>
                  <option value="<?= htmlspecialchars($hotel['id']) ?>" <?php if (isset($punch_detail->hotel_name) && $punch_detail->hotel_name == $hotel['id'])
                       echo 'selected'; ?>>
                     <?= htmlspecialchars($hotel['hotel_name']) ?>
                  </option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-4">
            <label for="bill_no">Bill No:</label>
            <input type="text" name="bill_no" id="bill_no" class="form-control"
               value="<?= (isset($punch_detail->bill_no)) ? htmlspecialchars($punch_detail->bill_no) : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="bill_date">Bill Date:</label>
            <input type="text" name="bill_date" id="bill_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->bill_date)) ? date('Y-m-d', strtotime($punch_detail->bill_date)) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="hotel_address">Hotel Address:</label>
            <input type="text" name="hotel_address" id="hotel_address" class="form-control"
               value="<?= (isset($punch_detail->hotel_address)) ? htmlspecialchars($punch_detail->hotel_address) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-6">
            <label for="employee_name">Employee Name:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->employee_name) : ''; ?>
            </small>
            <select name="employee_name" id="employee" class="form-control" >
               <option value="">Select</option>
               <?php foreach ($employee_list as $employee) {
                  $selected = (isset($punch_detail->employee_name) && $punch_detail->employee_name == $employee['id']) ? 'selected' : '';
                  echo '<option value="' . htmlspecialchars($employee['id']) . '" ' . $selected . ' data-code="' . htmlspecialchars($employee['emp_code']) . '">' . htmlspecialchars($employee['emp_name']) . ' - ' . htmlspecialchars($employee['company_code']) . '</option>';
               } ?>
            </select>
         </div>
         <div class="form-group col-md-2">
            <label for="emp_code">Emp Code:</label>
            <input type="text" name="emp_code" id="emp_code" class="form-control"
               value="<?= (isset($punch_detail->emp_code)) ? htmlspecialchars($punch_detail->emp_code) : '' ?>"
               readonly>
         </div>
         <div class="form-group col-md-4">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control"
               value="<?= (isset($punch_detail->amount)) ? htmlspecialchars($punch_detail->amount) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-4">
            <label for="location">Location:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->location) : ''; ?>
            </small>
            <select name="location" id="location_id" class="form-control">
               <option value="">Select Location</option>
               <?php foreach ($location_list as $location) { ?>
                  <option value="<?= htmlspecialchars($location['location_name']) ?>" <?php if (isset($punch_detail->location) && $punch_detail->location == $location['location_name'])
                       echo 'selected'; ?>>
                     <?= htmlspecialchars($location['location_name']) ?>
                  </option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-8">
            <label for="detail">Detail:</label>
            <input type="text" name="detail" id="detail" class="form-control"
               value="<?= (isset($punch_detail->detail)) ? htmlspecialchars($punch_detail->detail) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="remark_comment">Remark / Comment:</label>
            <textarea name="remark_comment" id="remark_comment" cols="10" rows="3"
               class="form-control"><?= (isset($punch_detail->remark_comment)) ? htmlspecialchars($punch_detail->remark_comment) : '' ?></textarea>
         </div>
      </div>
      <div class="box-footer">
         <button type="reset" class="btn btn-danger">Reset</button>
         <?php if (!empty($user_permission) && $user_permission == 'N'): ?>
            <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit"
               value="Final Submit"></input>
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
   $("#location_id").select2();
   $("#hotel").select2();
   $(document).on('change', '#Type', function () {
      var type = $(this).val();
      if (type == 'Cash Deposit') {
         $('#amount_type').html('Deposit Amount:');
      } else {
         $('#amount_type').html('Withdrawal Amount:');
      }
   });
   $("#employee").select2();
   $(document).on("change", "#employee", function () {
      var code = $(this).find(':selected').data('code');
      $("#emp_code").val(code);
   });
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      input: false
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

      $(document).on("change", "#hotel", function () {
         var address = $(this).find(':selected').data('address');
         $("#hotel_address").val(address);
      });

      <?php
      $cleanedlocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
      );
      $cleanedHotel = cleanSearchValue(
         isset($temp_punch_detail->hotel_name) && !is_null($temp_punch_detail->hotel_name) ? $temp_punch_detail->hotel_name : ""
      );
      ?>

      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         '<?= $cleanedlocation ?>',
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );

      loadDropdownOptions(
         'hotel',
         '<?= base_url("extract/ExtractorController/get_hotel_options") ?>',
         '<?= $cleanedHotel ?>',
         '<?= isset($punch_detail->hotel_name) ? $punch_detail->hotel_name : "" ?>'

      );




   });
</script>