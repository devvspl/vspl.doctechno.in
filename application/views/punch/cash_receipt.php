<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Bank_ctrl/save_cash_receipt" id="form" name="form" method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="">Company Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
               <?php echo $temp_punch_detail->company_name; ?>
               </small>
               <select name="CompanyID" id="CompanyID" class="form-control" required
                  data-parsley-errors-container="#CompanyError">
                  <option value="">Select</option>
                  <?php
                     foreach ($company_list as $key => $value) {
                     	$selected = '';
                     	if (isset($punch_detail->CompanyID) && $punch_detail->CompanyID == $value['firm_id']) {
                     		$selected = 'selected';
                     	}
                     	echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
                     }
                     ?>
               </select>
               <div id="CompanyError"></div>
            </div>
            <div class="form-group col-md-4">
               <label for="">Voucher No:</label>
               <input type="text" name="Receipt_No" id="Receipt_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Date:</label>
               <input type="text" name="Receipt_Date" id="Receipt_Date" class="form-control datepicker" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Location:</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->location; ?>
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
               <label for="" id="">Receiver Name:</label>
               <input type="text" name="Receiver" id="Receiver" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="" id="">Received From:</label>
               <input type="text" name="ReceivedFrom" id="ReceivedFrom" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-9 form-group">
               <label for="">Particular:</label>
               <input type="text" name="Particular" id="Particular" class="form-control" value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : ''  ?>">
            </div>
            <div class="col-md-3 form-group" style="float: right;">
               <label for="">Amount:</label>
               <input type="number" min="1" step="0.1" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   	input: false
   });
   $('#Location').select2();
</script>