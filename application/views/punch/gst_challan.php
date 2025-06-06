<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="bankstatementform" name="bankstatementform"
      method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="cpin">CPIN:</label>
            <input type="text" name="cpin" id="cpin" class="form-control"
               value="<?= (isset($punch_detail->cpin)) ? $punch_detail->cpin : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="deposit_date">Deposit Date:</label>
            <input type="text" name="deposit_date" id="deposit_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->deposit_date)) ? date('Y-m-d', strtotime($punch_detail->deposit_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="cin">CIN:</label>
            <input type="text" name="cin" id="cin" class="form-control"
               value="<?= (isset($punch_detail->cin)) ? $punch_detail->cin : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="bank_name">Bank Name:</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control"
               value="<?= (isset($punch_detail->bank_name)) ? $punch_detail->bank_name : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="brn">BRN:</label>
            <input type="text" name="brn" id="brn" class="form-control"
               value="<?= (isset($punch_detail->brn)) ? $punch_detail->brn : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="gstin">GSTIN:</label>
            <input type="text" name="gstin" id="gstin" class="form-control"
               value="<?= (isset($punch_detail->gstin)) ? $punch_detail->gstin : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="email_id">Email ID:</label>
            <input type="text" name="email_id" id="email_id" class="form-control"
               value="<?= (isset($punch_detail->email_id)) ? $punch_detail->email_id : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="mobile_no">Mobile No:</label>
            <input type="text" name="mobile_no" id="mobile_no" class="form-control"
               value="<?= (isset($punch_detail->mobile_no)) ? $punch_detail->mobile_no : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="col-md-6 form-group">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" class="form-control"
               value="<?= (isset($punch_detail->company_name)) ? $punch_detail->company_name : '' ?>">
         </div>
         <div class="col-md-6 form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" class="form-control"
               value="<?= (isset($punch_detail->address)) ? $punch_detail->address : '' ?>">
         </div>
      </div>
      <div class="row table-responsive">
         <div class="col-md-12 mt-2">
            <table class="table-bordered" border="1">
               <thead>
                  <th style="text-align: center;">Particular</th>
                  <th style="text-align: center;">Tax(₹)</th>
                  <th style="text-align: center;">Interest(₹)</th>
                  <th style="text-align: center;">Penalty(₹)</th>
                  <th style="text-align: center;">Fees(₹)</th>
                  <th style="text-align: center;">Other(₹)</th>
                  <th style="text-align: center;">Total(₹)</th>
               </thead>
               <tbody>
                  <tr>
                     <td><input type="text" name="Particular[]" id="Particular1" class="form-control form-control-sm"
                           value="CGST" ></td>
                     <td><input type="number" name="Tax[]" id="Tax1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_tax)) ? $punch_detail->cgst_tax : '' ?>"
                           onchange="calculate_subtotal(1)"></td>
                     <td><input type="number" name="Interest[]" id="Interest1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_interest)) ? $punch_detail->cgst_interest : '' ?>"
                           onchange="calculate_subtotal(1)"></td>
                     <td><input type="number" name="Penalty[]" id="Penalty1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_penalty)) ? $punch_detail->cgst_penalty : '' ?>"
                           onchange="calculate_subtotal(1)"></td>
                     <td><input type="number" name="Fees[]" id="Fees1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_fees)) ? $punch_detail->cgst_fees : '' ?>"
                           onchange="calculate_subtotal(1)"></td>
                     <td><input type="number" name="Other[]" id="Other1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_other)) ? $punch_detail->cgst_other : '' ?>"
                           onchange="calculate_subtotal(1)"></td>
                     <td><input type="number" name="Total[]" id="Total1" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cgst_total)) ? $punch_detail->cgst_total : '' ?>" readonly>
                     </td>
                  </tr>
                  <tr>
                     <td><input type="text" name="Particular[]" id="Particular2" class="form-control form-control-sm"
                           value="SGST" ></td>
                     <td><input type="number" name="Tax[]" id="Tax2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_tax)) ? $punch_detail->sgst_tax : '' ?>"
                           onchange="calculate_subtotal(2)"></td>
                     <td><input type="number" name="Interest[]" id="Interest2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_interest)) ? $punch_detail->sgst_interest : '' ?>"
                           onchange="calculate_subtotal(2)"></td>
                     <td><input type="number" name="Penalty[]" id="Penalty2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_penalty)) ? $punch_detail->sgst_penalty : '' ?>"
                           onchange="calculate_subtotal(2)"></td>
                     <td><input type="number" name="Fees[]" id="Fees2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_fees)) ? $punch_detail->sgst_fees : '' ?>"
                           onchange="calculate_subtotal(2)"></td>
                     <td><input type="number" name="Other[]" id="Other2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_other)) ? $punch_detail->sgst_other : '' ?>"
                           onchange="calculate_subtotal(2)"></td>
                     <td><input type="number" name="Total[]" id="Total2" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->sgst_total)) ? $punch_detail->sgst_total : '' ?>" readonly>
                     </td>
                  </tr>
                  <tr>
                     <td><input type="text" name="Particular[]" id="Particular3" class="form-control form-control-sm"
                           value="IGST" ></td>
                     <td><input type="number" name="Tax[]" id="Tax3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_tax)) ? $punch_detail->igst_tax : '' ?>"
                           onchange="calculate_subtotal(3)"></td>
                     <td><input type="number" name="Interest[]" id="Interest3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_interest)) ? $punch_detail->igst_interest : '' ?>"
                           onchange="calculate_subtotal(3)"></td>
                     <td><input type="number" name="Penalty[]" id="Penalty3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_penalty)) ? $punch_detail->igst_penalty : '' ?>"
                           onchange="calculate_subtotal(3)"></td>
                     <td><input type="number" name="Fees[]" id="Fees3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_fees)) ? $punch_detail->igst_fees : '' ?>"
                           onchange="calculate_subtotal(3)"></td>
                     <td><input type="number" name="Other[]" id="Other3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_other)) ? $punch_detail->igst_other : '' ?>"
                           onchange="calculate_subtotal(3)"></td>
                     <td><input type="number" name="Total[]" id="Total3" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->igst_total)) ? $punch_detail->igst_total : '' ?>" readonly>
                     </td>
                  </tr>
                  <tr>
                     <td><input type="text" name="Particular[]" id="Particular4" class="form-control form-control-sm"
                           value="Cess" ></td>
                     <td><input type="number" name="Tax[]" id="Tax4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_tax)) ? $punch_detail->cess_tax : '' ?>"
                           onchange="calculate_subtotal(4)"></td>
                     <td><input type="number" name="Interest[]" id="Interest4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_interest)) ? $punch_detail->cess_interest : '' ?>"
                           onchange="calculate_subtotal(4)"></td>
                     <td><input type="number" name="Penalty[]" id="Penalty4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_penalty)) ? $punch_detail->cess_penalty : '' ?>"
                           onchange="calculate_subtotal(4)"></td>
                     <td><input type="number" name="Fees[]" id="Fees4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_fees)) ? $punch_detail->cess_fees : '' ?>"
                           onchange="calculate_subtotal(4)"></td>
                     <td><input type="number" name="Other[]" id="Other4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_other)) ? $punch_detail->cess_other : '' ?>"
                           onchange="calculate_subtotal(4)"></td>
                     <td><input type="number" name="Total[]" id="Total4" class="form-control form-control-sm"
                           value="<?= (isset($punch_detail->cess_total)) ? $punch_detail->cess_total : '' ?>" readonly>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="6" style="text-align: right;">Total Challan Amount:</td>
                     <td><input type="number" id="total_challan_amount" name="total_challan_amount" class="form-control final_amount_column"
                           readonly
                           value="<?= (isset($punch_detail->total_challan_amount)) ? $punch_detail->total_challan_amount : '' ?>">
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
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
      <?php
      if ($this->customlib->haveSupportFile($scan_id) == 1) {
         ?>
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
   $('.datepicker').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
   });
   getMultiRecord();
   function getMultiRecord() {
      var scan_id = $('#scan_id').val();
      var DocTypeId = $('#DocTypeId').val();
      $.ajax({
         url: '<?= base_url() ?>Punch/getGSTChalanItems',
         type: 'POST',
         data: {
            scan_id: scan_id,
            type_id: DocTypeId
         },
         dataType: 'json',
         success: function (response) {
            if (response.status == 200) {
               for (var i = 1; i <= 4; i++) {
                  $("#Particular" + i).val(response.data[i - 1].particular);
                  $("#Tax" + i).val(response.data[i - 1].tax);
                  $("#Interest" + i).val(response.data[i - 1].interest);
                  $("#Penalty" + i).val(response.data[i - 1].penalty);
                  $("#Fees" + i).val(response.data[i - 1].fees);
                  $("#Other" + i).val(response.data[i - 1].other);
                  $("#Total" + i).val(response.data[i - 1].total);
               }
            }
         }
      });
   }
   function calculate_subtotal(id) {
      var Tax = $('#Tax' + id).val();
      var Interest = $('#Interest' + id).val();
      var Penalty = $('#Penalty' + id).val();
      var Fees = $('#Fees' + id).val();
      var Other = $('#Other' + id).val();
      if (Tax == '' || isNaN(Tax) || Tax == undefined) {
         Tax = 0;
      }
      if (Interest == '' || isNaN(Interest) || Interest == undefined) {
         Interest = 0;
      }
      if (Penalty == '' || isNaN(Penalty) || Penalty == undefined) {
         Penalty = 0;
      }
      if (Fees == '' || isNaN(Fees) || Fees == undefined) {
         Fees = 0;
      }
      if (Other == '' || isNaN(Other) || Other == undefined) {
         Other = 0;
      }

      var Total = parseFloat(Tax) + parseFloat(Interest) + parseFloat(Penalty) + parseFloat(Fees) + parseFloat(Other);
      $('#Total' + id).val(Total);
      calculate_total(); //calculate total
   }
   function calculate_total() {
      var Total1 = $('#Total1').val();
      var Total2 = $('#Total2').val();
      var Total3 = $('#Total3').val();
      var Total4 = $('#Total4').val();
      if (Total1 == '' || isNaN(Total1) || Total1 == undefined) {
         Total1 = 0;
      }
      if (Total2 == '' || isNaN(Total2) || Total2 == undefined) {
         Total2 = 0;
      }
      if (Total3 == '' || isNaN(Total3) || Total3 == undefined) {
         Total3 = 0;
      }
      if (Total4 == '' || isNaN(Total4) || Total4 == undefined) {
         Total4 = 0;
      }

      var Total_Amount = Math.round(parseFloat(Total1) + parseFloat(Total2) + parseFloat(Total3) + parseFloat(Total4));
      $('#total_challan_amount').val(Total_Amount.toFixed(2));
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