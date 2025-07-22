<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="ticket_form" name="ticket_form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="agent_name">Agent Name:</label>
            <input type="text" name="agent_name" id="agent_name" class="form-control" autocomplete="off"
               value="<?= (isset($punch_detail->agent_name)) ? htmlspecialchars($punch_detail->agent_name) : '' ?>">
         </div>
         <!-- <div class="form-group col-md-3">
            <label for="bill_date">Bill Date:</label>
            <input type="text" class="form-control datepicker" id="bill_date" name="bill_date" autocomplete="off"
               value="<?= (isset($punch_detail->booking_date)) ? date('Y-m-d', strtotime($punch_detail->booking_date)) : '' ?>">
         </div> -->
         <div class="form-group col-md-3">
            <label for="booking_date">Date of Booking:</label>
            <input type="text" name="booking_date" id="booking_date" class="form-control datepicker" autocomplete="off"
               value="<?= (isset($punch_detail->booking_date)) ? date('Y-m-d', strtotime($punch_detail->booking_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="cancelled_date">Cancelled Date:</label>
            <input type="text" class="form-control datepicker" id="cancelled_date" name="cancelled_date"
               autocomplete="off"
               value="<?= (isset($punch_detail->cancelled_date)) ? date('Y-m-d', strtotime($punch_detail->cancelled_date)) : '' ?>">
         </div>
      </div>
      <div class="row" style="margin-top: 10px;">
         <table class="table table-bordered">
            <thead style="text-align: center;">
               <tr style="text-align: center;">
                  <th style="width: 5%; background:blueviolet;color:white;text-align: center;">#</th>
                  <th style="width: 40%; background:blueviolet;color:white;text-align: center;">Employee</th>
                  <th style="background:blueviolet;color:white;text-align: center;">PNR Number</th>
                  <th style="background:blueviolet;color:white;text-align: center;">Amount</th>
                  <th style="background:blueviolet;color:white;text-align: center;"></th>
               </tr>
            </thead>
            <tbody id="multi_record">
               <tr>
                  <td style="text-align: center;">1</td>
                  <td>
                     <select name="employee[]" id="employee1" class="form-control select2">
                        <option value="">Select</option>
                        <?php foreach ($employee_list as $value) {
                           $id = htmlspecialchars($value['id']);
                           $name = htmlspecialchars($value['emp_name']);
                           $company = htmlspecialchars($value['company_code']);
                           $selected = (isset($punch_detail->employee) && in_array($id, (array) $punch_detail->employee)) ? 'selected' : '';
                           echo "<option value='{$id}' {$selected}>{$name} - {$company}</option>";
                        } ?>
                     </select>
                  </td>
                  <td>
                     <input type="text" name="pnr_number[]" id="pnr1" class="form-control" autocomplete="off"
                        value="<?= (isset($punch_detail->pnr_number[0])) ? htmlspecialchars($punch_detail->pnr_number[0]) : '' ?>">
                  </td>
                  <td>
                     <input type="number" step="0.01" name="amount[]" id="amount1" class="form-control amount"
                        autocomplete="off"
                        value="<?= (isset($punch_detail->amount[0])) ? htmlspecialchars($punch_detail->amount[0]) : '' ?>"
                        onchange="calculate(1)" onkeypress="return isNumberKey(event)">
                  </td>
                  <td>
                     <button type="button" name="add" id="add" class="btn btn-primary btn-xs" style="margin-top: 2px;">
                        <i class="fa fa-plus"></i>
                     </button>
                  </td>
               </tr>
            </tbody>
            <tr>
               <td></td>
               <td></td>
               <td style="text-align: right;">Sub Total:</td>
               <td>
                  <input type="number" step="0.01" class="form-control" readonly id="sub_total" name="sub_total"
                     value="<?= (isset($punch_detail->sub_total)) ? htmlspecialchars($punch_detail->sub_total) : '' ?>"
                     onkeypress="return isNumberKey(event)">
               </td>
               <td></td>
            </tr>
            <tr>
               <td></td>
               <td></td>
               <td style="text-align: right;">Cancellation Charge:</td>
               <td>
                  <input type="number" step="0.01" class="form-control" id="cancellation_charge"
                     name="cancellation_charge"
                     value="<?= (isset($punch_detail->cancellation_charge)) ? htmlspecialchars($punch_detail->cancellation_charge) : '' ?>"
                     onchange="calculate_charge()">
               </td>
               <td></td>
            </tr>
            <tr>
               <td></td>
               <td></td>
               <td style="text-align: right;">Other Charges:</td>
               <td>
                  <input type="number" step="0.01" class="form-control" id="other_charges" name="other_charges"
                     value="<?= (isset($punch_detail->other_charges)) ? htmlspecialchars($punch_detail->other_charges) : '' ?>"
                     onchange="calculate_charge()">
               </td>
               <td></td>
            </tr>
            <tr>
               <td></td>
               <td></td>
               <td style="text-align: right;">Grand Total:</td>
               <td>
                  <input type="number" step="0.01" class="form-control final_amount_column" id="grand_total" name="grand_total" readonly
                     value="<?= (isset($punch_detail->grand_total)) ? htmlspecialchars($punch_detail->grand_total) : '' ?>">
               </td>
               <td></td>
            </tr>
         </table>
      </div>
      <div class="row mt-3">
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
   $(".select2").select2();


   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      input: false
   });

   function isNumberKey(evt) {
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
         return false;

      return true;
   }


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
      var DocTypeId = $("#DocTypeId").val();
      $.ajax({
         url: '<?= base_url() ?>Punch/getTicketCancellationItems',
         type: 'POST',
         data: {
            scan_id: scan_id,
            type_id: DocTypeId
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
                  $("#pnr" + i).val(response.data[i - 1].pnr_number);
                  $("#amount" + i).val(response.data[i - 1].amount);

               }
            }
         }
      });
   }

   function multi_record(num) {
      var html = '';
      html += '<tr>';
      html += '<td>' + num + '</td>';
      html += '<td><select name="employee[]" id="employee' + num +
         '" class="form-control form-select form-select-sm select2" ><option value="">Select</option>' +
         employee_list.map(function (item) {
            return '<option value="' + item.id + '" data-code="' + item.emp_code + '">' + item
               .emp_name + ' - ' + item.company_code + '</option>';
         }).join('') +
         '</select></td>';
      html += '<td><input type="text"  name="pnr_number[]" id="pnr' + num +
         '" class="form-control"></td>';
      html += '<td><input type="text"  name="amount[]" id="amount' + num +
         '" class="form-control" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num +
         ')"></td>';
      html +=
         '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs remove" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
      html += '</tr>';
      $('#multi_record').append(html);
      $(".select2").select2();

   }

   function calculate() {
      var sub_total = 0;
      var count = 10;

      for (var i = 1; i <= count; i++) {
         var amount = parseFloat($('#amount' + i).val());
         if (!isNaN(amount)) {
            sub_total += amount;
         }
      }

      $('#sub_total').val(sub_total.toFixed(2));
   }

   function calculate_charge() {
      var subTotal = parseFloat($('#sub_total').val()) || 0;
      var cancellationCharge = parseFloat($('#cancellation_charge').val()) || 0;
      var otherCharges = parseFloat($('#other_charges').val()) || 0;
      var grandTotal = subTotal + cancellationCharge + otherCharges;
      $('#grand_total').val(grandTotal.toFixed(2));
   }
</script>