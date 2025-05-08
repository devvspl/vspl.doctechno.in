
<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Bank_ctrl/save_cash" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
    
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="col-md-3 form-group">
               <label for="">Type</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->type; ?>
               </small>
               <select name="Type" id="Type" class="form-control">
                  <option value="">Select</option>
                  <?php
                     $type = array('Cash Deposit', 'Cash Withdrawal');
                     foreach ($type as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->File_Type) && $punch_detail->File_Type == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="col-md-3 form-group">
               <label for="">Date:</label>
               <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? $punch_detail->BillDate : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Bank Name:</label>
               <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Branch:</label>
               <input type="text" name="Branch" id="Branch" class="form-control" value="<?= (isset($punch_detail->BankAddress)) ? $punch_detail->BankAddress : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-4">
               <label for="">Account No:</label>
               <input type="text" name="Account_No" id="Account_No" class="form-control" value="<?= (isset($punch_detail->BankAccountNo)) ? $punch_detail->BankAccountNo : ''  ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Beneficiary Name:</label>
               <input type="text" name="Beneficiary_Name" id="Beneficiary_Name" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="" id="amount_type">Amount:</label>
               <input type="text" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
            if ($this->customlib->haveSupportFile($Scan_Id) == 1) {
            ?>
         <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
               <label for="">Supporting File:</label>
               <div class="form-group">
                  <?php
                     $support_file = $this->customlib->getSupportFile($Scan_Id);
                     
                     foreach ($support_file as $row) {
                     ?>
                  <div class="col-md-3">
                     <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
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
   $(document).on('change', '#Type', function() {
       var type = $(this).val();
       if (type == 'Cash Deposit') {
           $('#amount_type').html('Deposit Amount:');
       } else {
           $('#amount_type').html('Withdrawal Amount:');
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
});
</script>