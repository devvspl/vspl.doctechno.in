<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="labour_form" name="labour_form" method="post"
      accept-charset="utf-8">
      <div style="display: flex; flex-direction: column; align-items: center;">
         <div class="loader" id="loader" style="display: none;"></div>
         <span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #1b98ae;">Please
            Wait...</span>
      </div>
      <div class="col-md-12" id="contnetBody">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
         <div class="row">
            <div class="form-group col-md-3">
               <label for="voucher_no">Voucher No:</label>
               <input type="text" required name="voucher_no" id="voucher_no" class="form-control"
                  value="<?= (isset($punch_detail->voucher_no)) ? $punch_detail->voucher_no : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="payment_date">Payment Date:</label>
               <input type="date"  required name="payment_date" id="payment_date" class="form-control"
                  value="<?= (isset($punch_detail->payment_date)) ? date('Y-m-d', strtotime($punch_detail->payment_date)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="payee">Payee:</label>
               <input type="text" name="payee" id="payee" class="form-control"
                  value="<?= (isset($punch_detail->payee)) ? $punch_detail->payee : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="location">Location:</label>
               <input type="text" name="location" id="location_id" class="form-control"
                  value="<?= (isset($punch_detail->location)) ? $punch_detail->location : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="particular">Particular:</label>
               <input type="text" name="particular" id="particular" class="form-control"
                  value="<?= (isset($punch_detail->particular)) ? $punch_detail->particular : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="total_amount">Total Amount:</label>
               <input type="text" name="total_amount" id="total_amount" class="form-control"
                  value="<?= (isset($punch_detail->total_amount)) ? $punch_detail->total_amount : '' ?>" readonly>
            </div>
            <div class="form-group col-md-3">
               <label for="from_date">From Date:</label>
               <input type="date" name="from_date" id="from_date" class="form-control"
                  value="<?= (isset($punch_detail->from_date)) ? date('Y-m-d', strtotime($punch_detail->from_date)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="to_date">To Date:</label>
               <input type="date" name="to_date" id="to_date" class="form-control"
                  value="<?= (isset($punch_detail->to_date)) ? date('Y-m-d', strtotime($punch_detail->to_date)) : '' ?>">
            </div>
         </div>
         <div class="row" style="height: 200px; overflow:auto">
            <table class="table">
               <thead>
                  <th>Head</th>
                  <th>Amount</th>
                  <th></th>
               </thead>
               <tbody id="multi_record">
                  <tr>
                     <td>
                        <select class="form-control" id="Head1" name="head[]">
                           <option value="">Select Head</option>
                           <?php
                           $ledger_list = $this->customlib->getLedgerList();
                           foreach ($ledger_list as $key => $value) { ?>
                              <option value="<?= $value['ledger_name'] ?>" <?php if (isset($punch_detail->head) && $value['ledger_name'] == $punch_detail->head) {
                                   echo "selected";
                                } ?>><?= $value['ledger_name'] ?></option>
                           <?php } ?>
                        </select>
                     </td>
                     <td><input type="text" class="form-control" id="Amount1" name="amount[]"
                           onchange="calculate(1);"></td>
                     <td><button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                           style="margin-top: 5px;"><i class="fa fa-plus"></i></button></td>
                  </tr>
               </tbody>
               <tr>
                  <td style="text-align: right;"><b>Sub Total:</b></td>
                  <td><input type="text" class="form-control form-control-sm final_amount_column" id="sub_total" name="sub_total" readonly
                        value="<?= (isset($punch_detail->sub_total)) ? $punch_detail->sub_total : '' ?>"></td>
               </tr>
            </table>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="remark_comment">Remarks / Comments:</label>
               <textarea name="remark_comment" id="remark_comment" cols="40" rows="3"
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
                              onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"><?php echo $row['name'] ?></a>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         <?php endif ?>
      </div>
   </form>
</div>
<script>
   $('#Head1').select2();
   var Count = 1;
   getMultiRecord();

   function getMultiRecord() {
      var scan_id = $('#scan_id').val();
      var DocTypeId = $("#DocTypeId").val(); 
      $.ajax({
         url: '<?= base_url() ?>Punch/getLabourPaymentItems',
         type: 'POST',
         data: {
            scan_id: scan_id,
            type_id : DocTypeId
         },
         dataType: 'json',
         success: function (response) {


            if (response.status == 200) {
               Count = (response.data).length;
               for (var i = 1; i <= Count; i++) {
                  if (i >= 2) {
                     multi_record(i);
                  }
                  $("#Head" + i).val(response.data[i - 1].head).trigger('change');
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
         calculate(Count);
      }
   });

   function multi_record(num) {
      var html = '';
      html += '<tr>';
      html += '<td><select class="form-control" id="Head' + num + '" name="head[]">';
      html += '<option value="">Select Head</option>';

      html += `<?php foreach ($ledger_list as $key => $value) { ?>
              <option value="<?= addslashes($value['ledger_name']) ?>"><?= addslashes($value['ledger_name']) ?></option>
          <?php } ?>`;
      html += '</select></td>';
      html += '<td><input type="text" class="form-control" id="Amount' + num + '" name="amount[]" onchange="calculate(' + num + ')"></td>';
      html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
      html += '</tr>';
      $('#multi_record').append(html);
      $('#Head' + num).select2();
   }

   function calculate(num) {

      var Amount = $('#Amount' + num).val();
      var sub_total = 0;
      for (var i = 1; i <= Count; i++) {
         var Amount = $('#Amount' + i).val();
         if (Amount != '' && Amount != null) {
            sub_total += parseFloat(Amount);
         }
      }
      $('#sub_total').val(sub_total.toFixed(2));
      $('#total_amount').val(sub_total.toFixed(2));

   }

   function toggleLoader(show, tableId) {
      const loader = document.getElementById('loader');
      const loaderText = document.getElementById('loader-text');
      const table = document.getElementById(tableId);

      if (show) {

         loader.style.marginTop = '230px';

         loader.style.display = 'inline-block';
         loaderText.style.display = 'block';
         table.style.visibility = 'hidden';
      } else {

         loader.style.marginTop = '0';

         loader.style.display = 'none';
         loaderText.style.display = 'none';
         table.style.visibility = 'visible';
      }
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
   });
</script>