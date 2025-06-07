<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="form" name="form" method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
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
            <input type="text" required name="Voucher_No" id="Voucher_No" class="form-control" value="<?= (isset($punch_detail->voucher_no)) ? $punch_detail->voucher_no : ''  ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="">Voucher Date:</label>
            <input type="text" required name="Voucher_Date" id="Voucher_Date" class="form-control datepicker" value="<?= (isset($punch_detail->voucher_date)) ? date('Y-m-d', strtotime($punch_detail->voucher_date)) : ''  ?>" autocomplete="off">
         </div>
         <div class="col-md-4 form-group">
            <label for="">Location:</label>
            <small class="text-danger">
              <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="Location" id="location_id" class="form-control">
               <option value="">Select</option>
            
            </select>
         </div>
         <div class="form-group col-md-4">
            <label for="" id="">Payee:</label>
            <input type="text" name="Payee" id="Payee" class="form-control" value="<?= (isset($punch_detail->payee)) ? $punch_detail->payee : ''  ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="" id="">Payer:</label>
            <input type="text" name="Payer" id="Payer" class="form-control" value="<?= (isset($punch_detail->payer)) ? $punch_detail->payer : ''  ?>">
         </div>
      </div>
      <div class="row">
         <div class="col-md-4 form-group" >
            <label for="">Amount:</label>
            <input type="number" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->amount)) ? $punch_detail->amount : ''  ?>">
         </div>
         <div class="col-md-8 form-group">
            <label for="">Particular:</label>
            <input type="text" name="Particular" id="Particular" class="form-control" value="<?= (isset($punch_detail->particular)) ? $punch_detail->particular : ''  ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : ''  ?></textarea>
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
   $(".datepicker").datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      input: false
   });

   $('#location_id').select2();
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
         '<?= isset($punch_detail->company_name) ? $punch_detail->company_name : "" ?>'
      );

      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedLocation) ?>,
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );
   });
</script>
