<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="vehicle_expense_form" name="vehicle_expense_form"
      method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-6">
            <label for="">Employee / Payee Name:</label> <span class="text-danger">*</span>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->employee_name : ''; ?>
            </small>
            <select name="Employee" id="Employee" class="form-control select2">
               <option value="">Select</option>
               <?php
               foreach ($employee_list as $key => $value) {
                  $selected = '';
                  if (isset($punch_detail->employee_name) && $punch_detail->employee_name == $value['id']) {
                     $selected = 'selected';
                  }
                  echo '<option value="' . $value['id'] . '" ' . $selected . ' data-code="' . $value['emp_code'] . '">' . $value['emp_name'] . ' - ' . $value['company_code'] . '</option>';
               }
               ?>
            </select>
         </div>
         <div class="col-md-3">
            <label for="">Emp Code:</label>
            <input type="text" name="Emp_Code" id="Emp_Code" class="form-control"
               value="<?= (isset($punch_detail->emp_code)) ? $punch_detail->emp_code : '' ?>" readonly>
         </div>
         <div class="form-group col-md-3">
            <label for="">Bill Date:</label> <span class="text-danger">*</span>
            <input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker"
               value="<?= (isset($punch_detail->bill_date)) ? date('Y-m-d', strtotime($punch_detail->bill_date)) : '' ?>"
               autocomplete="off">
         </div>
         <div class="form-group col-md-3">
            <label for="">Vehicle No.:</label> <span class="text-danger">*</span>
            <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control"
               value="<?= (isset($punch_detail->vehicle_no)) ? $punch_detail->vehicle_no : '' ?>" autocomplete="off">
         </div>
         <div class="form-group col-md-2">
            <label for="">Vehicle Type:</label> <span class="text-danger">*</span>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vehicle_type : ''; ?>

            </small>
            <select name="Vehicle_Type" id="Vehicle_Type" class="form-control">
               <?php
               $vehicle_type = array('Two Wheeler', 'Four Wheeler');
               ?>
               <?php foreach ($vehicle_type as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->vehicle_type) && $punch_detail->vehicle_type == $value) {
                       echo "selected";
                    } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-4">
            <label for="">Location:</label> <span class="text-danger">*</span>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="Location" id="location_id" class="form-control form-control-sm" required
               data-parsley-errors-container="#LocationError">
               <option value="">Select</option>

            </select>
            <div id="LocationError"></div>
         </div>
         <div class="form-group col-md-3">
            <label for="pwd">Rs/KM:</label>
            <input type="text" name="Rate" id="Rate" class="form-control"
               value="<?= (isset($punch_detail->rs_km)) ? $punch_detail->rs_km : '' ?>">
         </div>
      </div>
      <div class="row" style="height: 300px; overflow:auto">
         <table class="table">
            <thead>
               <th>Opening KM</th>
               <th>Closing KM</th>
               <th>Total Km</th>
               <th>Amount</th>
               <th></th>
            </thead>
            <tbody id="multi_record">
               <tr>
                  <td><input type="text" class="form-control" id="Dist_Opening1" name="Dist_Opening[]"
                        onchange="calc_distance(1);"></td>
                  <td><input type="text" class="form-control" id="Dist_Closing1" name="Dist_Closing[]"
                        onchange="calc_distance(1);"></td>
                  <td><input type="text" class="form-control" id="Km1" name="Km[]" readonly></td>
                  <td><input type="text" class="form-control" id="Amount1" name="Amount[]" readonly></td>
                  <td>
                     <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                        style="margin-top: 5px;"><i class="fa fa-plus"></i></button>
                  </td>
               </tr>
            </tbody>
            <tr>
               <td></td>
               <td style="text-align: right"><b>Total:</b></td>
               <td><input type="text" class="form-control form-control-sm" id="Total_KM" name="Total_KM" readonly
                     value="<?= (isset($punch_detail->total_run_km)) ? $punch_detail->total_run_km : '' ?>">
               </td>
               <td><input type="text" class="form-control form-control-sm" id="Total_Amount" name="Total_Amount"
                     readonly value="<?= (isset($punch_detail->total)) ? $punch_detail->total : '' ?>">
               </td>
            </tr>
            <tr>
               <td colspan="2"></td>
               <td style="text-align: right"><b>Round Off:</b></td>
               <td><input type="text" name="Total_Discount" id="Total_Discount"
                     class="form-control form-control-sm d-inline"
                     value="<?= (isset($punch_detail->total_discount)) ? $punch_detail->total_discount : '' ?>">
                  <span><input type="radio" name="plus_minus" id="plus" class="plus_minus" <?php
                  if (isset($punch_detail->total_discount)) {
                     if ($punch_detail->grand_total > $punch_detail->total) {
                        echo "checked";
                     }
                  }
                  ?>>
                     <label for="plus">Plus</label>
                  </span>
                  <span><input type="radio" name="plus_minus" id="minus" class="plus_minus" <?php
                  if (isset($punch_detail->total_discount)) {
                     if ($punch_detail->grand_total < $punch_detail->total) {
                        echo "checked";
                     }
                  } else {
                     echo "checked";
                  }
                  ?>> <label for="minus">Minus</label></span>
               </td>
            </tr>
            <tr>
               <td colspan="2"></td>
               <td style="text-align: right"><b>Grand Total:</b></td>
               <td><input type="text" id="Grand_Total" name="Grand_Total" class="form-control final_amount_column"
                     value="<?= (isset($punch_detail->grand_total)) ? $punch_detail->grand_total : '' ?>" readonly></td>
            </tr>
         </table>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="3"
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
   $("#Employee").select2();
   $("#location_id").select2();
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d'
   });
   $(document).on("change", "#Employee", function () {
      var code = $(this).find(':selected').data('code');
      $("#Emp_Code").val(code);
   });
   var Count = 1;
   getMultiRecord();

   function getMultiRecord() {
      var scan_id = $('#scan_id').val();
      var docTypeId = $("#DocTypeId").val();
      $.ajax({
         url: '<?= base_url() ?>Punch/getTwoFourWheelerItems',
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
                  $("#Dist_Opening" + i).val(response.data[i - 1].opening_km);
                  $("#Dist_Closing" + i).val(response.data[i - 1].closing_km);
                  $("#Km" + i).val(response.data[i - 1].total_km);
                  $("#Amount" + i).val(response.data[i - 1].amount);
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

      html += '<td><input type="text" class="form-control" id="Dist_Opening' + num + '" name="Dist_Opening[]" onchange="calc_distance(' + num + ');"></td>';
      html += '<td><input type="text" class="form-control" id="Dist_Closing' + num + '" name="Dist_Closing[]" onchange="calc_distance(' + num + ');"></td>';
      html += '<td><input type="text" class="form-control" id="Km' + num + '" name="Km[]" readonly></td>';
      html += '<td><input type="text" class="form-control" id="Amount' + num + '" name="Amount[]" readonly></td>';
      html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
      html += '</tr>';
      $('#multi_record').append(html);
      $('.datepicker').datetimepicker({});
   }

   $(document).on('change', '.plus_minus', function () {
      var id = $(this).attr('id');
      var Total_Discount = $('#Total_Discount').val();
      var Total = $('#Total_Amount').val();
      if (id == 'plus') {
         var Total = parseFloat($('#Total_Amount').val()) + parseFloat(Total_Discount);
         $('#Grand_Total').val(Total);
      } else {
         var Total = parseFloat($('#Total_Amount').val()) - parseFloat(Total_Discount);
         $('#Grand_Total').val(Total);
      }
   });

   function calc_distance(num) {
      var Dist_Opening = $('#Dist_Opening' + num).val();
      var Dist_Closing = $('#Dist_Closing' + num).val();
      var Km = Dist_Closing - Dist_Opening;
      $('#Km' + num).val(Km);

      var Rate = $('#Rate').val();
      if (Rate == '') {
         Rate = 0;
      }
      var Amount = Km * Rate;
      $('#Amount' + num).val(Amount);

      var Total_KM = 0;
      var Total_Amount = 0;
      for (var i = 1; i <= Count; i++) {
         var Km = $('#Km' + i).val();
         if (Km == '') {
            Km = 0;
         }
         Total_KM += parseFloat(Km);
         var Amount = $('#Amount' + i).val();
         if (Amount == '') {
            Amount = 0;
         }
         Total_Amount += parseFloat(Amount);
      }
      $('#Total_KM').val(Total_KM);
      $('#Total_Amount').val(Total_Amount.toFixed(2));


      var total_amount = $("#Total_Amount").val();
      var total_amount_array = total_amount.split('.');
      var total_amount_int = total_amount_array[0];
      var total_amount_dec = total_amount_array[1];

      if (total_amount_dec == undefined) {
         total_amount_dec = 0;
      }

      $("#Total_Discount").val('0.' + total_amount_dec);

      $("#Grand_Total").val(total_amount_int);
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