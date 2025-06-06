<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="mode">Mode:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->mode : ''; ?>
            </small>
            <select name="mode" id="travel_mode" class="form-control" required>
               <option value="">Select</option>
               <?php
               $travel_mode = array('Sharing Taxi/Cab', 'Auto', 'Bus');
               foreach ($travel_mode as $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->mode) && $punch_detail->mode == $value) {
                       echo "selected";
                    } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
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
            <label for="employee_name">Employee Name:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->employee_name : ''; ?>
            </small>
            <select name="employee_name" id="employee" class="form-control" required
               data-parsley-errors-container="#EmpError">
               <option value="">Select</option>
               <?php
               foreach ($employee_list as $value) {
                  $selected = (isset($punch_detail->employee_name) && $punch_detail->employee_name == $value['id']) ? 'selected' : '';
                  echo '<option value="' . $value['id'] . '" ' . $selected . ' data-code="' . $value['emp_code'] . '">' . $value['emp_name'] . ' - ' . $value['company_code'] . '</option>';
               }
               ?>
            </select>
            <div id="EmpError"></div>
         </div>
         <div class="form-group col-md-3">
            <label for="emp_code">Emp Code:</label>
            <input type="text" name="emp_code" id="emp_code" class="form-control"
               value="<?= (isset($punch_detail->emp_code)) ? $punch_detail->emp_code : '' ?>" readonly>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="vehicle_no">Vehicle No:</label> <span class="text-danger">*</span>
            <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required
               value="<?= (isset($punch_detail->vehicle_no)) ? $punch_detail->vehicle_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="month">Month:</label> <span class="text-danger">*</span>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->month : ''; ?>
            </small>
            <select name="month" id="month" class="form-control" required>
               <option value="">Select</option>
               <?php foreach ($months as $key => $value) { ?>
                  <option value="<?= $key ?>" <?php if (isset($punch_detail->month) && $punch_detail->month == $key) {
                       echo "selected";
                    } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="calculation_base">Calculation Base:</label>
            <select name="calculation_base" id="cal_by" class="form-control">
               <option value="KM_Base" <?php if (isset($punch_detail->calculation_base) && $punch_detail->calculation_base == 'KM_Base')
                  echo 'selected'; ?>>K.M. Base</option>
               <option value="Fixed" <?php if (isset($punch_detail->calculation_base) && $punch_detail->calculation_base == 'Fixed')
                  echo 'selected'; ?>>Fixed</option>
            </select>
         </div>
         <div class="form-group col-md-3" id="km_base_div">
            <label for="per_km_rate">Per KM Rate:</label> <span class="text-danger">*</span>
            <input type="number" name="per_km_rate" id="per_km_rate" class="form-control" required min="1" step="0.1"
               value="<?= (isset($punch_detail->per_km_rate)) ? $punch_detail->per_km_rate : '' ?>">
         </div>
         <div class="form-group col-md-3" style="display: none;" id="fixed_base_div">
            <label for="fixed_amount">Fixed Amount:</label> <span class="text-danger">*</span>
            <input type="number" name="fixed_amount" id="fixed_amount" class="form-control" required min="1" step="0.1"
               value="<?= (isset($punch_detail->per_km_rate)) ? $punch_detail->per_km_rate : '' ?>">
         </div>
      </div>
      <div class="row" style="height: 200px; overflow:auto;margin-top:20px;">
         <table class="table">
            <thead>
               <th>Date</th>
               <th>Opening Reading</th>
               <th>Closing Reading</th>
               <th>Total Km</th>
               <th>Amount</th>
               <th></th>
            </thead>
            <tbody id="multi_record">
               <tr>
                  <td><input type="text" class="form-control datepicker" id="date1" name="date[]" required></td>
                  <td><input type="number" step="0.01" class="form-control" id="dist_opening1" name="dist_opening[]"
                        onchange="calc_distance(1);" required></td>
                  <td><input type="number" step="0.01" class="form-control" id="dist_closing1" name="dist_closing[]"
                        onchange="calc_distance(1);" required></td>
                  <td><input type="number" step="0.01" class="form-control" id="km1" name="km[]" readonly></td>
                  <td><input type="number" step="0.01" class="form-control" id="amount1" name="amount[]" readonly></td>
                  <td>
                     <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                        style="margin-top: 5px;"><i class="fa fa-plus"></i></button>
                  </td>
               </tr>
            </tbody>
            <tfoot>
               <tr>
                  <td colspan="2"></td>
                  <td><b>Total:</b></td>
                  <td><input type="number" step="0.01" class="form-control form-control-sm" id="total_km"
                        name="total_km" readonly
                        value="<?= (isset($punch_detail->total_km)) ? $punch_detail->total_km : '' ?>"></td>
                  <td><input type="number" step="0.01" class="form-control form-control-sm final_amount_column" id="total_amount"
                        name="total_amount" readonly
                        value="<?= (isset($punch_detail->total)) ? $punch_detail->total : '' ?>"></td>
                  <td></td>
               </tr>
            </tfoot>
         </table>
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
                           onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                           <?php echo $row['file_name'] ?></a>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </form>
</div>
<script>
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
   });

   $("#location_id").select2();
   $("#travel_mode").select2();
   $("#month").select2();
   $("#cal_by").select2();

   var Count = 1;
   getMultiRecord();

   function getMultiRecord() {
      var scan_id = $('#scan_id').val();
      var docTypeId = $("#DocTypeId").val();
      $.ajax({
         url: '<?= base_url() ?>Punch/getReadingItems',
         type: 'POST',
         data: {
            scan_id: scan_id,
            type_id: docTypeId
         },
         dataType: 'json',
         success: function (response) {

            if (response.status == 200) {
               Count = (response.data).length;

               for (var i = 1; i <= Count; i++) {
                  if (i >= 2) {
                     multi_record(i);
                  }

                  $("#date" + i).val(response.data[i - 1].travel_date);

                  $("#dist_opening" + i).val(response.data[i - 1].opening_reading);
                  $("#dist_closing" + i).val(response.data[i - 1].closing_reading);
                  $("#km" + i).val(response.data[i - 1].total_km);
                  $("#amount" + i).val(response.data[i - 1].amount);
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
      if (confirm('Are you sure you want to delete this record?')) {
         $(this).closest('tr').remove();
         Count--;
      }
   });

   function multi_record(num) {
      var html = '';
      html += '<tr>';
      html += '<td><input type="text" class="form-control datepicker" id="date' + num + '" name="date[]"></td>';
      html += '<td><input type="text" class="form-control" id="dist_opening' + num + '" name="dist_opening[]" onchange="calc_distance(' + num + ');"></td>';
      html += '<td><input type="text" class="form-control" id="dist_closing' + num + '" name="dist_closing[]" onchange="calc_distance(' + num + ');"></td>';
      html += '<td><input type="text" class="form-control" id="km' + num + '" name="km[]" readonly></td>';
      html += '<td><input type="text" class="form-control" id="amount' + num + '" name="amount[]" readonly></td>';
      html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
      html += '</tr>';
      $('#multi_record').append(html);
      $('.datepicker').datetimepicker({
         timepicker: false,
         format: 'Y-m-d'
      });
   }

   function calc_distance(num) {
      console.log(num);
      // Get input values
      var dist_opening = parseFloat($('#dist_opening' + num).val()) || 0;
      var dist_closing = parseFloat($('#dist_closing' + num).val()) || 0;
      var cal_by = $('#cal_by').val();

      // Calculate total kilometers
      var km = dist_closing - dist_opening;
      if (km < 0) km = 0; // Prevent negative kilometers
      $('#km' + num).val(km.toFixed(2));

      // Calculate amount based on calculation base
      var amount = 0;
      if (cal_by === 'KM_Base') {
         var rate = parseFloat($('#per_km_rate').val()) || 0;
         amount = km * rate;
         $('#amount' + num).val(amount.toFixed(2));
      } else if (cal_by === 'Fixed') {
         var fixedAmount = parseFloat($('#fixed_amount').val()) || 0;
         amount = fixedAmount;
         $('#amount' + num).val(amount.toFixed(2));
      }

      // Calculate totals
      var total_km = 0;
      var total_amount = 0;

      // Count all rows in the table
      $('#multi_record tr').each(function (index) {
         var rowKm = parseFloat($('#km' + (index + 1)).val()) || 0;
         var rowAmount = parseFloat($('#amount' + (index + 1)).val()) || 0;
         total_km += rowKm;
         if (cal_by === 'KM_Base') {
            total_amount += rowAmount;
         } else if (cal_by === 'Fixed') {
            total_amount = parseFloat($('#fixed_amount').val()) || 0;
         }
      });

      // Update total fields
      $('#total_km').val(total_km.toFixed(2));
      $('#total_amount').val(total_amount.toFixed(2));
   }

   $("#employee").select2();
   $(document).on("change", "#employee", function () {
      var code = $(this).find(':selected').data('code');
      $("#emp_code").val(code);
   });

   $(document).on("change", "#cal_by", function () {
      var cal_by = $(this).val();
      if (cal_by == 'KM_Base') {
         $("#km_base_div").show();
         $("#fixed_base_div").hide();
         $("#fixed_amount").val('');
      } else {
         $("#km_base_div").hide();
         $("#fixed_base_div").show();
         $("#per_km_rate").val('');
      }
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
      $cleanedlocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
      );
      ?>
      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedlocation) ?>,
         '<?= isset($punch_detail->Loc_Name) ? $punch_detail->Loc_Name : "" ?>'
      );
   });
</script>