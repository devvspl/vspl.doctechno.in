<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Tax_ctrl/Save_Income_Tax_TDS" id="tdsform" name="tdsform" method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Section:</label>
               <input type="text" name="Section" id="Section" class="form-control" value="<?= (isset($punch_detail->Section)) ? $punch_detail->Section : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Company:</label>
               <small class="text-danger">
                
                     <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company : ''; ?>
               </small>
               <select name="Company" id="Company" class="form-control">
                  <option value="">Select</option>
                
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="">Nature of Payment:</label>
               <small class="text-danger">
          
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->nature_of_payment : ''; ?>
               
               </small>

               <select name="Payment_Nature" id="Payment_Nature" class="form-control">
                  <?php
                     $payment_nature = array('Income Tax' => 'Income Tax', 'TDS' => 'TDS', 'Advance Tax' => 'Advance Tax', 'Demand Challan' => 'Demand Challan');
                     ?>
                  <option value="">Select</option>
                  <?php foreach ($payment_nature as $key => $value) { ?>
                  <option value="<?= $value; ?>" <?php if (isset($punch_detail->NatureOfPayment)) {
                     if ($value == $punch_detail->NatureOfPayment) {
                         echo "selected";
                     }
                     }  ?>><?= $value; ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="">Assessment Year:</label>
               <small class="text-danger">
                     <?php echo isset($temp_punch_detail) ? $temp_punch_detail->assessment_year : ''; ?>
       
               </small>
            
               <select name="Assessment_Year" id="Assessment_Year" class="form-control">
                  <?php
                     foreach ($fin_year as $row) {
                     ?>
                  <option value="<?= $row['id'] ?>" <?php if (isset($punch_detail->Financial_Year)) {
                     if ($row['id'] == $punch_detail->Financial_Year) {
                         echo "selected";
                     }
                     }  ?>><?= $row['label'] ?></option>
                  <?php
                     }
                     ?>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Bank Name:</label>
               <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">BSR_Code:</label>
               <input type="text" name="BSR_Code" id="BSR_Code" class="form-control" value="<?= (isset($punch_detail->BSRCode)) ? $punch_detail->BSRCode : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Challan No:</label>
               <input type="text" name="Challan_No" id="Challan_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Challan Date:</label>
               <input type="text" name="Challan_Date" id="Challan_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 form-group">
               <label for="">Bank Reference No:</label>
               <input type="text" name="Ref_No" id="Ref_No" class="form-control" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : ''  ?>">
            </div>
            <div class="col-md-3 form-group" style="float: right;">
               <label for="">Amount:</label>
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
   $(".datepicker").datetimepicker({
       timepicker: false,
       format: "Y-m-d",
   });
   $("#Company").select2();
   $("#Payment_Nature").select2();
   $("#Assessment_Year").select2();

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
     $cleanedCompany = cleanSearchValue(
        isset($temp_punch_detail->company) && !is_null($temp_punch_detail->company) ? $temp_punch_detail->company : ""
    );
    
    ?>
    loadDropdownOptions(
        'Company',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedCompany) ?>,
        '<?= isset($punch_detail->From_ID) ? $punch_detail->From_ID : "" ?>'
    );
});
</script>