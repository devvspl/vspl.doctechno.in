<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="bankstatementform" name="bankstatementform"
      method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-4">
            <label for="location">Location:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="location" id="location_id" class="form-control">
               <option value="">Select Location</option>
            </select>
         </div>
         <div class="form-group col-md-4">
            <label for="bill_no">Bill No:</label>
            <input type="text" name="bill_no" id="bill_no" required class="form-control"
               value="<?= (isset($punch_detail->bill_no)) ? $punch_detail->bill_no : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="bill_date">Bill Date:</label>
            <input type="text" name="bill_date" required id="bill_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->bill_date)) ? date('Y-m-d', strtotime($punch_detail->bill_date)) : '' ?>"
               autocomplete="off">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-6">
            <label for="billing_name">Billing Name:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->billing_name : ''; ?>
            </small>
            <select name="billing_name" id="billing_name" class="form-control select2">
               <option value="">Select</option>
            </select>
         </div>
         <div class="form-group col-md-6">
            <label for="billing_address">Billing Address:</label>
            <input type="text" name="billing_address" id="billing_address" class="form-control"
               value="<?= (isset($punch_detail->billing_address)) ? $punch_detail->billing_address : '' ?>" readonly>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-6">
            <label for="hotel_name">hotel Name:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->hotel_name : ''; ?>
            </small>
            <select name="hotel_name" id="hotel" class="form-control">
               <option value="">Select</option>
            </select>
         </div>
         <div class="form-group col-md-6">
            <label for="hotel_address">hotel Address:</label>
            <input type="text" name="hotel_address" id="hotel_address" class="form-control"
               value="<?= (isset($punch_detail->hotel_address)) ? $punch_detail->hotel_address : '' ?>" readonly>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="billing_instruction">Billing Instruction:</label>
            <select name="billing_instruction" id="billing_instruction" class="form-control">
               <option value="">Select</option>
               <?php
               $billing_instruction = ['Direct', 'Bill to Company'];
               foreach ($billing_instruction as $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->billing_instruction) && $punch_detail->billing_instruction == $value) {
                       echo "selected";
                    } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="booking_id">Booking ID:</label>
            <input type="text" name="booking_id" id="booking_id" class="form-control"
               value="<?= (isset($punch_detail->booking_id)) ? $punch_detail->booking_id : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="check_in">Check In Date/Time:</label>
            <input type="text" name="check_in" id="arrival_date" class="form-control datetimepicker"
               value="<?= (isset($punch_detail->check_in)) ? $punch_detail->check_in : '' ?>"
               onchange="calculate_duration();">
         </div>
         <div class="form-group col-md-3">
            <label for="check_out">Check Out Date/Time:</label>
            <input type="text" name="check_out" id="departure_date" class="form-control datetimepicker"
               value="<?= (isset($punch_detail->check_out)) ? $punch_detail->check_out : '' ?>"
               onchange="calculate_duration();">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="duration_of_stay">duration of Stay:</label>
            <input type="text" name="duration_of_stay" id="duration" class="form-control"
               value="<?= (isset($punch_detail->duration_of_stay)) ? $punch_detail->duration_of_stay : '' ?>" readonly>
         </div>
         <div class="form-group col-md-3">
            <label for="number_of_rooms">Number of Rooms:</label>
            <?php
            $room_list = array_combine(range(1, 10), range(1, 10));
            echo form_dropdown('number_of_rooms', $room_list, (isset($punch_detail->number_of_rooms)) ? $punch_detail->number_of_rooms : '', 'class="form-control" id="number_of_rooms" onchange="calculate();"');
            ?>
         </div>
         <div class="form-group col-md-3">
            <label for="room_type">Room Type:</label>
            <input type="text" name="room_type" id="room_type" class="form-control"
               value="<?= (isset($punch_detail->room_type)) ? $punch_detail->room_type : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="meal_plan">Meal Plan:</label>
            <input type="text" name="meal_plan" id="meal" class="form-control"
               value="<?= (isset($punch_detail->meal_plan)) ? $punch_detail->meal_plan : '' ?>">
         </div>
      </div>
      <div class="row">
         <table class="table">
            <thead style="text-align: center;">
               <th style="width: 10%">#</th>
               <th style="width: 50%;">employee</th>
               <th style="width: 20%">Emp Code</th>
               <th></th>
            </thead>
            <tbody id="multi_record">
               <tr>
                  <td>1</td>
                  <td>
                     <select name="employee[]" id="employee1" class="form-control select2" onchange="getCode(1)">
                        <option value="">Select</option>
                        <?php
                        foreach ($employee_list as $value) {
                           $id = htmlspecialchars($value['id']);
                           $code = htmlspecialchars($value['emp_code']);
                           $name = htmlspecialchars($value['emp_name']);
                           $company = htmlspecialchars($value['company_code']);
                           $selected = (isset($punch_detail->employee) && $punch_detail->employee == $id) ? 'selected' : '';
                           echo "<option value='{$id}' data-code='{$code}' {$selected}>{$name} - {$company}</option>";
                        }
                        ?>
                     </select>
                  </td>
                  <td>
                     <input type="text" readonly name="emp_code[]" id="empcode1" class="form-control form-control-sm">
                  </td>
                  <td>
                     <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                        style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <div class="row">
         <div class="form-group col-md-4">
            <label for="rate">Rate:</label>
            <input type="number" class="form-control" min="1" step="0.5" id="room_rate" name="rate"
               onchange="calculate();" value="<?= (isset($punch_detail->rate)) ? $punch_detail->rate : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="amount">amount:</label>
            <input type="number" class="form-control" readonly id="amount" name="amount"
               value="<?= (isset($punch_detail->amount)) ? $punch_detail->amount : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="other_charges">Other Charges (+):</label>
            <input type="number" class="form-control" id="other_charge" name="other_charges" onchange="calculate();"
               value="<?= (isset($punch_detail->other_charges)) ? $punch_detail->other_charges : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="discount">discount (-):</label>
            <input type="number" class="form-control" id="discount" name="discount" onchange="calculate();"
               value="<?= (isset($punch_detail->discount)) ? $punch_detail->discount : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="gst">gst (%):</label>
            <input type="number" class="form-control" id="gst" name="gst" onchange="calculate();"
               value="<?= (isset($punch_detail->gst)) ? $punch_detail->gst : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="grand_total">Grand Total:</label>
            <input type="number" class="form-control" id="grand_total" name="grand_total" step="0.1"
               value="<?= (isset($punch_detail->grand_total)) ? $punch_detail->grand_total : '' ?>">
         </div>
      </div>
      <div class="row mt-3">
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
                           onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"><?php echo $row['file_name'] ?></a>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </form>
</div>
<script>
   $(".select2").select2();
   $("#location_id").select2();
   $("#hotel").select2();
   $("#billing_name").select2();
   $(document).on("change", "#billing_name", function () {
      var address = $(this).find(':selected').data('address');
      $("#billing_address").val(address);
   });
   $(document).on("change", "#hotel", function () {
      var address = $(this).find(':selected').data('address');
      $("#hotel_address").val(address);
   });

   $(".datetimepicker").datetimepicker({
      timepicker: true,
      format: 'Y-m-d H:i:s'
   });

   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d'
   });

   function calculate_duration() {
      const check_in = $("#arrival_date").val();
      const check_out = $("#departure_date").val();

      if (!check_in || !check_out) {
         return;
      }

      const timeDiff = Math.abs(new Date(check_out) - new Date(check_in));
      const duration = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

      $("#duration").val(duration);
   }

   $(document).ready(function () {

      var employee_list = <?= json_encode($employee_list) ?>;


      $(document).on('click', '#add', function () {
         Count++;
         multi_record(Count);
      });
      $(document).on('click', '#remove', function () {
         $(this).closest('tr').remove();
      });
      var Count = 1;

      getMultiRecord();
      function getMultiRecord() {
         var scan_id = $('#scan_id').val();
         const docTypeId = $("#DocTypeId").val();
         $.ajax({
            url: '<?= base_url() ?>Punch/getEmployeeItems',
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
                     $("#employee" + i).val(response.data[i - 1].emp_name).trigger('change');
                     $("#empcode" + i).val(response.data[i - 1].emp_code);

                  }
               }
            }
         });
      }

      function multi_record(num) {
         var html = '';
         html += '<tr>';
         html += '<td>' + num + '</td>';
         html += '<td><select name="employee[]" id="employee' + num + '" class="form-control form-select form-select-sm select2" onchange="getCode(' + num + ')"><option value="">Select</option>' +
            employee_list.map(function (item) {
               return '<option value="' + item.id + '" data-code="' + item.emp_code + '">' + item.emp_name + ' - ' + item.company_code + '</option>';
            }).join('') +
            '</select></td>';
         html += '<td><input type="text" readonly name="emp_code[]" id="empcode' + num + '" class="form-control form-control-sm"></td>';
         html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs remove" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
         html += '</tr>';
         $('#multi_record').append(html);
         $(`#employee${num}`).select2()
      }
   });

   function getCode(num) {
      var code = $("#employee" + num).find(':selected').data('code');
      $("#empcode" + num).val(code);
   }

   function calculate() {
      const duration = parseFloat($("#duration").val()) || 1;
      const number_of_rooms = parseFloat($("#number_of_rooms").val()) || 1;
      const room_rate = parseFloat($("#room_rate").val()) || 1;
      const other_charge = parseFloat($("#other_charge").val()) || 0;
      const discount = parseFloat($("#discount").val()) || 0;
      const gst = parseFloat($("#gst").val()) || 0;
      let total = duration * number_of_rooms * room_rate;
      $("#amount").val(total);
      let sub_total = total + other_charge - discount;
      sub_total *= 1 + gst / 100;
      $("#grand_total").val((sub_total).toFixed(2));
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
      $cleanedCompany = cleanSearchValue(isset($temp_punch_detail->billing_name) && !is_null($temp_punch_detail->billing_name) ? $temp_punch_detail->billing_name : "");
      $cleanedhotel = cleanSearchValue(isset($temp_punch_detail->hotel_name) && !is_null($temp_punch_detail->hotel_name) ? $temp_punch_detail->hotel_name : "");
      $cleanedLocation = cleanSearchValue(isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : "");
      ?>

      loadDropdownOptions(
         'billing_name',
         '<?= base_url("extract/ExtractorController/get_company_options") ?>',
         <?= json_encode($cleanedCompany) ?>,
         '<?= isset($punch_detail->billing_name) ? $punch_detail->billing_name : "" ?>'
      );
      loadDropdownOptions(
         'hotel',
         '<?= base_url("extract/ExtractorController/get_hotel_options") ?>',
         <?= json_encode($cleanedhotel) ?>,
         '<?= isset($punch_detail->hotel_name) ? $punch_detail->hotel_name : "" ?>'
      );
      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedLocation) ?>,
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );

   });
</script>