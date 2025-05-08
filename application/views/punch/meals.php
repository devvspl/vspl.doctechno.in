<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/Save_Meals" id="Form" name="Form" method="post"
      accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="col-md-4 form-group">
               <label for="">Hotel Name:</label>
               <input type="text" name="Hotel" id="Hotel" class="form-control"
                  value="<?= (isset($punch_detail->Hotel_Name)) ? $punch_detail->Hotel_Name : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Bill No:</label>
               <input type="text" name="InvoiceNo" id="InvoiceNo" class="form-control"
                  value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Bill Date:</label>
               <input type="text" name="Date" id="Date" class="form-control datepicker"
                  value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
            </div>
            <div class="col-md-12 form-group">
               <label for="">Hotel Address:</label>
               <input type="text" name="Hotel_Address" id="Hotel_Address" class="form-control"
                  value="<?= (isset($punch_detail->Hotel_Address)) ? $punch_detail->Hotel_Address : '' ?>">
            </div>
            <div class="form-group col-md-6">
               <label for="">Employee Name:</label>
               <small class="text-danger">
               <?php  echo $temp_punch_detail->employee_name; ?>
               </small>
               <select name="Employee" id="Employee" class="form-control">
                  <option value="">Select</option>
                  <?php
                     foreach ($employee_list as $key => $value) {
                     	$selected = '';
                     	if (isset($punch_detail->EmployeeID) && $punch_detail->EmployeeID == $value['id']) {
                     		$selected = 'selected';
                     	}
                     	echo '<option value="' . $value['id'] . '" ' . $selected . ' data-code="' . $value['emp_code'] . '">' . $value['emp_name'] . ' - ' . $value['company_code'] . '</option>';
                     }
                     ?>
               </select>
            </div>
            <div class="col-md-2">
               <label for="">Emp Code :</label>
               <input type="text" name="Emp_Code" id="Emp_Code" class="form-control"
                  value="<?= (isset($punch_detail->EmployeeCode)) ? $punch_detail->EmployeeCode : '' ?>"
                  readonly>
            </div>
            <div class="form-group col-md-4">
               <label for="">Amount:</label>
               <input type="text" name="Amount" id="Amount" class="form-control"
                  value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
            </div>
            <div class="col-md-4">
               <label for="">Location :</label>
               <small class="text-danger">
               <?php  echo $temp_punch_detail->location; ?>
               </small>
               <select name="Location" id="Location" class="form-control">
                  <option value="">Select Location</option>
                  <?php foreach ($locationlist as $key => $value) { ?>
                  <option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
                     if ($value['location_name'] == $punch_detail->Loc_Name) {
                     	echo "selected";
                     }
                     }  ?>><?= $value['location_name'] ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form-group col-md-8">
               <label for="">Detail:</label>
               <input type="text" name="Detail" id="Detail" class="form-control"
                  value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="">Remark / Comment:</label>
               <textarea name="Remark" id="Remark" cols="10" rows="3"
                  class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
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
                     <a href="javascript:void(0);" target="popup"
                        onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
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
   $("#Location").select2();
   $(document).on('change', '#Type', function () {
   	var type = $(this).val();
   	if (type == 'Cash Deposit') {
   		$('#amount_type').html('Deposit Amount:');
   	} else {
   		$('#amount_type').html('Withdrawal Amount:');
   	}
   });
   $("#Employee").select2();
   $(document).on("change", "#Employee", function () {
   	var code = $(this).find(':selected').data('code');
   	$("#Emp_Code").val(code);
   });
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   	input: false
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