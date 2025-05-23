<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Bank_ctrl/save_cash_voucher" id="form" name="form" method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
      <div class="row">
         <div class="form-group col-md-4">
            <label for="">Company Name:</label> <span class="text-danger">*</span>
            <small class="text-danger">
              <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company_name : ''; ?>
            </small>
            <select name="CompanyID" id="CompanyID" class="form-control" required
               data-parsley-errors-container="#CompanyError">
               <option value="">Select</option>
            </select>
            <div id="CompanyError"></div>
         </div>
         <div class="form-group col-md-4">
            <label for="">Voucher No:</label>
            <input type="text" name="Voucher_No" id="Voucher_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="">Voucher Date:</label>
            <input type="text" name="Voucher_Date" id="Voucher_Date" class="form-control datepicker" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>" autocomplete="off">
         </div>
         <div class="col-md-4 form-group">
            <label for="">Location:</label>
            <small class="text-danger">
              <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="Location" id="Location" class="form-control">
               <option value="">Select</option>
               <?php foreach ($locationlist as $key => $value) { ?>
               <option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
                  if ($value['location_name'] == $punch_detail->Loc_Name) {
                  	echo "selected";
                  }
                  }  ?>><?= $value['location_name'] ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-4">
            <label for="" id="">Payee:</label>
            <input type="text" name="Payee" id="Payee" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="" id="">Payer:</label>
            <input type="text" name="Payer" id="Payer" class="form-control" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : ''  ?>">
         </div>
      </div>
      <div class="row">
         <div class="col-md-4 form-group" >
            <label for="">Amount:</label>
            <input type="text" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
         </div>
         <div class="col-md-8 form-group">
            <label for="">Particular:</label>
            <input type="text" name="Particular" id="Particular" class="form-control" value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : ''  ?>">
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
         <button type="submit" class="btn btn-success pull-right">Save</button>
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
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      input: false
   });

   $('#Location').select2();
   $('#CompanyID').select2();

   $(document).ready(function() {
      <?php
      $cleanedBuyer = cleanSearchValue(
         isset($temp_punch_detail->company_name) && !is_null($temp_punch_detail->company_name) 
            ? $temp_punch_detail->company_name 
            : ""
      );

      $cleanedLocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) 
            ? $temp_punch_detail->location 
            : ""
      );
      ?>

      loadDropdownOptions(
         'CompanyID',
         '<?= base_url("extract/ExtractorController/get_company_options") ?>',
         <?= json_encode($cleanedBuyer) ?>,
         '<?= isset($punch_detail->From_ID) ? $punch_detail->From_ID : "" ?>'
      );

      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedLocation) ?>,
         '<?= isset($punch_detail->Loc_Name) ? $punch_detail->Loc_Name : "" ?>'
      );
   });
</script>
