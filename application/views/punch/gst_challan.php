<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Challan_ctrl/Save_GST_Challan" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">CPIN:</label>
               <input type="text" name="CPIN" id="CPIN" class="form-control" value="<?= (isset($punch_detail->CPIN)) ? $punch_detail->CPIN : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Deposit Date:</label>
               <input type="text" name="Deposit_Date" id="Deposit_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">CIN:</label>
               <input type="text" name="CIN" id="CIN" class="form-control" value="<?= (isset($punch_detail->CIN)) ? $punch_detail->CIN : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Bank_Name:</label>
               <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">BRN:</label>
               <input type="text" name="BRN" id="BRN" class="form-control" value="<?= (isset($punch_detail->BankBSRCode)) ? $punch_detail->BankBSRCode : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">GSTIN:</label>
               <input type="text" name="GSTIN" id="GSTIN" class="form-control" value="<?= (isset($punch_detail->GSTIN)) ? $punch_detail->GSTIN : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Email ID:</label>
               <input type="text" name="Email" id="Email" class="form-control" value="<?= (isset($punch_detail->Email)) ? $punch_detail->Email : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Mobile No:</label>
               <input type="text" name="Mobile" id="Mobile" class="form-control" value="<?= (isset($punch_detail->MobileNo)) ? $punch_detail->MobileNo : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 form-group">
               <label for="">Company Name:</label>
               <input type="text" name="Company" id="Company" class="form-control" value="<?= (isset($punch_detail->Company)) ? $punch_detail->Company : ''  ?>">
            </div>
            <div class="col-md-6 form-group">
               <label for="">Address:</label>
               <input type="text" name="Address" id="Address" class="form-control" value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : ''  ?>">
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
                        <td><input type="text" name="Particular[]" id="Particular1" class="form-control form-control-sm" value="CGST" readonly></td>
                        <td><input type="text" name="Tax[]" id="Tax1" class="form-control form-control-sm" onchange="calculate_subtotal(1)"></td>
                        <td><input type="text" name="Interest[]" id="Interest1" class="form-control form-control-sm" onchange="calculate_subtotal(1)"></td>
                        <td><input type="text" name="Penalty[]" id="Penalty1" class="form-control form-control-sm" onchange="calculate_subtotal(1)"></td>
                        <td><input type="text" name="Fees[]" id="Fees1" class="form-control form-control-sm" onchange="calculate_subtotal(1)"></td>
                        <td><input type="text" name="Other[]" id="Other1" class="form-control form-control-sm" onchange="calculate_subtotal(1)"></td>
                        <td><input type="text" name="Total[]" id="Total1" class="form-control form-control-sm" readonly></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="Particular[]" id="Particular2" class="form-control form-control-sm" value="SGST" readonly></td>
                        <td><input type="text" name="Tax[]" id="Tax2" class="form-control form-control-sm" onchange="calculate_subtotal(2)"></td>
                        <td><input type="text" name="Interest[]" id="Interest2" class="form-control form-control-sm" onchange="calculate_subtotal(2)"></td>
                        <td><input type="text" name="Penalty[]" id="Penalty2" class="form-control form-control-sm" onchange="calculate_subtotal(2)"></td>
                        <td><input type="text" name="Fees[]" id="Fees2" class="form-control form-control-sm" onchange="calculate_subtotal(2)"></td>
                        <td><input type="text" name="Other[]" id="Other2" class="form-control form-control-sm" onchange="calculate_subtotal(2)"></td>
                        <td><input type="text" name="Total[]" id="Total2" class="form-control form-control-sm" readonly></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="Particular[]" id="Particular3" class="form-control form-control-sm" value="IGST" readonly></td>
                        <td><input type="text" name="Tax[]" id="Tax3" class="form-control form-control-sm" onchange="calculate_subtotal(3)"></td>
                        <td><input type="text" name="Interest[]" id="Interest3" class="form-control form-control-sm" onchange="calculate_subtotal(3)"></td>
                        <td><input type="text" name="Penalty[]" id="Penalty3" class="form-control form-control-sm" onchange="calculate_subtotal(3)"></td>
                        <td><input type="text" name="Fees[]" id="Fees3" class="form-control form-control-sm" onchange="calculate_subtotal(3)"></td>
                        <td><input type="text" name="Other[]" id="Other3" class="form-control form-control-sm" onchange="calculate_subtotal(3)"></td>
                        <td><input type="text" name="Total[]" id="Total3" class="form-control form-control-sm" readonly></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="Particular[]" id="Particular4" class="form-control form-control-sm" value="Cess" readonly></td>
                        <td><input type="text" name="Tax[]" id="Tax4" class="form-control form-control-sm" onchange="calculate_subtotal(4)"></td>
                        <td><input type="text" name="Interest[]" id="Interest4" class="form-control form-control-sm" onchange="calculate_subtotal(4)"></td>
                        <td><input type="text" name="Penalty[]" id="Penalty4" class="form-control form-control-sm" onchange="calculate_subtotal(4)"></td>
                        <td><input type="text" name="Fees[]" id="Fees4" class="form-control form-control-sm" onchange="calculate_subtotal(4)"></td>
                        <td><input type="text" name="Other[]" id="Other4" class="form-control form-control-sm" onchange="calculate_subtotal(4)"></td>
                        <td><input type="text" name="Total[]" id="Total4" class="form-control form-control-sm" readonly></td>
                     </tr>
                     <tr>
                        <td colspan="6" style="text-align: right;">Total Challan Amount:</td>
                        <td><input type="text" id="Total_Amount" name="Total_Amount" class="form-control" readonly value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>"></td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="">Remark / Comment:</label>
               <textarea name="Remark" id="Remark" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : ''  ?></textarea>
            </div>
         </div>
         <div class="box-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <?php if (!empty($user_permission) &&  $user_permission == 'N') : ?>
               <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
            <?php endif; ?>
          
            <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')) : ?>
            <input type="submit" class="btn btn-info pull-right"  name="save_as_draft" value="Save as Draft"></input>
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
                     <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
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
       $.ajax({
           url: '<?= base_url() ?>form/Challan_ctrl/getGstItem',
           type: 'POST',
           data: {
               scan_id: scan_id
           },
           dataType: 'json',
           success: function(response) {
               if (response.status == 200) {
                   for (var i = 1; i <= 4; i++) {
                       $("#Particular" + i).val(response.data[i - 1].Particular);
                       $("#Tax" + i).val(response.data[i - 1].Tax);
                       $("#Interest" + i).val(response.data[i - 1].Interest);
                       $("#Penalty" + i).val(response.data[i - 1].Penalty);
                       $("#Fees" + i).val(response.data[i - 1].Fees);
                       $("#Other" + i).val(response.data[i - 1].Other);
                       $("#Total" + i).val(response.data[i - 1].Total);
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
       $('#Total_Amount').val(Total_Amount.toFixed(2));
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