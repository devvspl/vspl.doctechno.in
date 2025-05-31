<div id="invoice-details" class="tab-content active">
<form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="punch_form" name="punch_form" method="post" accept-charset="utf-8">
    <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
    <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="mode">Mode:</label>
            <input type="text" name="mode" id="mode" value="Rail" class="form-control" readonly>
        </div>
        <div class="form-group col-md-3">
            <label for="train_number">Train Number:</label>
            <input type="text" name="train_number" id="train_number" class="form-control"
                   value="<?= (isset($punch_detail->train_number)) ? htmlspecialchars($punch_detail->train_number) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="agent_name">Agent Name:</label>
            <input type="text" name="agent_name" id="agent_name" class="form-control"
                   value="<?= (isset($punch_detail->agent_name)) ? htmlspecialchars($punch_detail->agent_name) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="pnr_number">PNR Number:</label>
            <input type="text" name="pnr_number" id="pnr_number" class="form-control"
                   value="<?= (isset($punch_detail->pnr_number)) ? htmlspecialchars($punch_detail->pnr_number) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="date_of_booking">Date of Booking:</label>
            <input type="text" name="date_of_booking" id="date_of_booking" class="form-control datepicker"
                   value="<?= (isset($punch_detail->date_of_booking)) ? date('Y-m-d', strtotime($punch_detail->date_of_booking)) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="journey_date">Journey Date:</label>
            <input type="text" name="journey_date" id="journey_date" class="form-control datepicker"
                   value="<?= (isset($punch_detail->journey_date)) ? date('Y-m-d', strtotime($punch_detail->journey_date)) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="booking_id">Booking ID:</label>
            <input type="text" name="booking_id" id="booking_id" class="form-control"
                   value="<?= (isset($punch_detail->booking_id)) ? htmlspecialchars($punch_detail->booking_id) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="transaction_id">Transaction ID:</label>
            <input type="text" name="transaction_id" id="transaction_id" class="form-control"
                   value="<?= (isset($punch_detail->transaction_id)) ? htmlspecialchars($punch_detail->transaction_id) : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="journey_from">Journey From:</label>
            <input type="text" name="journey_from" id="journey_from" class="form-control"
                   value="<?= (isset($punch_detail->journey_from)) ? htmlspecialchars($punch_detail->journey_from) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="journey_upto">Journey Upto:</label>
            <input type="text" name="journey_upto" id="journey_upto" class="form-control"
                   value="<?= (isset($punch_detail->journey_upto)) ? htmlspecialchars($punch_detail->journey_upto) : '' ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="travel_class">Travel Class:</label>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->travel_class) : ''; ?>
            </small>
            <select name="travel_class" id="travel_class" class="form-control">
                <option value="">Select</option>
                <?php
                $travel_class = ['Sleeper', 'III AC', 'II AC', 'I AC'];
                foreach ($travel_class as $value) {
                ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?php if (isset($punch_detail->travel_class) && $punch_detail->travel_class == $value) echo 'selected'; ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="quota">Quota:</label>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->quota) : ''; ?>
            </small>
            <select name="quota" id="quota" class="form-control">
                <option value="">Select</option>
                <?php
                $travel_quota = ['General', 'Ladies', 'Senior Citizen', 'Divyang', 'Tatkal', 'Premium Tatkal'];
                foreach ($travel_quota as $value) {
                ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?php if (isset($punch_detail->quota) && $punch_detail->quota == $value) echo 'selected'; ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="location">Location:</label>
            <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? htmlspecialchars($temp_punch_detail->location) : ''; ?>
            </small>
            <select name="location" id="location_id" class="form-control">
                <option value="">Select Location</option>
                <?php foreach ($location_list as $location) { ?>
                    <option value="<?= htmlspecialchars($location['location_name']) ?>" <?php if (isset($punch_detail->location) && $punch_detail->location == $location['location_name']) echo 'selected'; ?>>
                        <?= htmlspecialchars($location['location_name']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <table class="table">
            <thead style="text-align: center;">
                <th style="width: 10%">#</th>
                <th style="width: 50%;">Employee</th>
                <th style="width: 20%">Emp Code</th>
                <th></th>
            </thead>
            <tbody id="multi_record">
                <tr>
                    <td>1</td>
                    <td>
                        <select name="employee[]" id="employee1" class="form-control select2" onchange="getCode(1)">
                            <option value="">Select</option>
                            <?php foreach ($employee_list as $value) {
                                $id = htmlspecialchars($value['id']);
                                $code = htmlspecialchars($value['emp_code']);
                                $name = htmlspecialchars($value['emp_name']);
                                $company = htmlspecialchars($value['company_code']);
                                $selected = (isset($punch_detail->employee) && in_array($id, (array)$punch_detail->employee)) ? 'selected' : '';
                                echo "<option value='{$id}' data-code='{$code}' {$selected}>{$name} - {$company}</option>";
                            } ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" readonly name="emp_code[]" id="empcode1" class="form-control">
                    </td>
                    <td>
                        <button type="button" name="add" id="add" class="btn btn-primary btn-xs" style="margin-top: 2px;">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label for="passenger_details">Passenger Details:</label>
            <textarea name="passenger_details" id="passenger_details" rows="2" class="form-control"><?= (isset($punch_detail->passenger_details)) ? htmlspecialchars($punch_detail->passenger_details) : '' ?></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="base_fare">Base Fare:</label><span class="text-danger">*</span>
            <input type="number" step="0.01" name="base_fare" id="base_fare" class="form-control" required
                   value="<?= (isset($punch_detail->base_fare)) ? htmlspecialchars($punch_detail->base_fare) : '' ?>" onchange="calculate();">
        </div>
        <div class="form-group col-md-3">
            <label for="gst">GST (in Rs.):</label>
            <input type="number" step="0.01" name="gst" id="gst" class="form-control"
                   value="<?= (isset($punch_detail->gst)) ? htmlspecialchars($punch_detail->gst) : '' ?>" onchange="calculate();">
        </div>
        <div class="form-group col-md-3">
            <label for="fees_surcharge">Fees & fees_surcharge:</label>
            <input type="number" step="0.01" name="fees_surcharge" id="fees_surcharge" class="form-control"
                   value="<?= (isset($punch_detail->fees_surcharge)) ? htmlspecialchars($punch_detail->fees_surcharge) : '' ?>" onchange="calculate();">
        </div>
        <div class="form-group col-md-3">
            <label for="other_charges">Other Charges:</label>
            <input type="number" step="0.01" name="other_charges" id="other_charges" class="form-control"
                   value="<?= (isset($punch_detail->other_charges)) ? htmlspecialchars($punch_detail->other_charges) : '' ?>" onchange="calculate();">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8"></div>
        <div class="form-group col-md-4">
            <label for="total_fare">Total Fare:</label>
            <input type="number" step="0.01" name="total_fare" id="total_fare" class="form-control" readonly
                   value="<?= (isset($punch_detail->total_fare)) ? htmlspecialchars($punch_detail->total_fare) : '' ?>">
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
                     $("#employee" + i).val(response.data[i - 1].employee_name).trigger('change');
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
   $(".select2").select2();
   $("#location_id").select2();
   $("#travel_class").select2();
   $("#quota").select2();
   $('.datepicker').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
   });

   function calculate() {


    var base_fare = $("#base_fare").val();
    var gst = $("#gst").val();
    var fees_surcharge = $("#fees_surcharge").val();
    var other_charges = $("#other_charges").val();
    var total = 0;

    // Log input values for debugging

    if (base_fare != '') {
        total = parseFloat(base_fare);
    }
    if (gst != '') {
        total = total + parseFloat(gst);
    }
    if (fees_surcharge != '') {
        total = total + parseFloat(fees_surcharge);
    }
    if (other_charges != '') {
        total = total + parseFloat(other_charges);
    }



    $("#total_fare").val(total.toFixed(2));
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

      $cleanedlocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
      );
      ?>



      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedlocation) ?>,
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );

   });


</script>