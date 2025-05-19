<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Vehicle_ctrl/Save_Vehicle_Fule" id="punch_form" name="punch_form"
      method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-6">
               <label for="">Vendor Name:</label> <span class="text-danger">*</span>
               <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->vendor_name: ''; ?>
               </small>
               <select name="Vendor_Name" id="Vendor_Name" class="form-control" required
                  data-parsley-errors-container="#VendorError">
                  <option value="">Select</option>
                 
               </select>
               <div id="VendorError"></div>
            </div>
            <div class="form-group col-md-6">
               <label for="">Billing To:</label> <span class="text-danger">*</span>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->billing_to: ''; ?>
               </small>
               <select name="Billing_To" id="Billing_To" class="form-control" required
                  data-parsley-errors-container="#Billing_ToError">
                  <option value="">Select</option>
                  
               </select>
               <div id="Billing_ToError"></div>
            </div>
            <div class="col-md-3 form-group">
               <label for="">Dealer Code:</label>
               <input type="text" name="Dealer_Code" id="Dealer_Code" class="form-control"
                  value="<?= (isset($punch_detail->BSRCode)) ? $punch_detail->BSRCode : '' ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Invoice No:</label>
               <input type="text" name="InvoiceNo" id="InvoiceNo" class="form-control" required
                  value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Invoice Date:</label>
               <input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker" required
                  value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Due Date:</label>
               <input type="text" name="Due_Date" id="Due_Date" class="form-control datepicker"
                  value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : '' ?>">
            </div>
            <div class="col-md-6 form-group">
               <label for="">Location:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location: ''; ?>
               </small>
               <select name="Work_Location" id="Work_Location" class="form-control form-control-sm" required data-parsley-errors-container="#LocationError">
                  <option value="">Select</option>

               </select>
               <div id="LocationError"></div>
            </div>
            <div class="col-md-6 form-group">
               <label for="">Vehicle No:</label>
               <input type="text" name="VehicleNo" id="VehicleNo" class="form-control" required
                  value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>">
            </div>
            <div class="form-group col-md-12">
               <label for="">Description:</label>
               <input type="text" name="Description" id="Description" class="form-control"
                  value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Liter:</label>
               <input type="number" min="1" name="Liter" id="Liter" class="form-control" onchange="calculate();" required
                  value="<?= (isset($punch_detail->MeterNumber)) ? $punch_detail->MeterNumber : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Per Liter Rate:</label>
               <input type="text" min="1" step="0.1" name="Rate" id="Rate" class="form-control" onchange="calculate();" 
                  value="<?= (isset($punch_detail->TariffPlan)) ? $punch_detail->TariffPlan : '' ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Amount:</label>
               <input type="text" name="Amount" id="Amount" readonly class="form-control"
                  value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
               <table class="table table-bordered">
                  <tr>
                     <td style="text-align: right;"><b>Round Off (₹):</b></td>
                     <td >
                        <input type="text" name="Total_Discount" id="Total_Discount"
                           class="form-control form-control-sm d-inline"
                           value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>"
                           style="width:200px;">
                        <span><input type="radio" name="plus_minus" id="plus" class="plus_minus" <?php
                           if (isset($punch_detail->Total_Discount)) {
                           	if ($punch_detail->Grand_Total > $punch_detail->Total_Amount) {
                           		echo "checked";
                           	}
                           }
                           ?>>
                        <label for="plus">Plus</label>
                        </span>
                        <span><input type="radio" name="plus_minus" id="minus" class="plus_minus" <?php
                           if (isset($punch_detail->Total_Discount)) {
                           	if ($punch_detail->Grand_Total < $punch_detail->Total_Amount) {
                           		echo "checked";
                           	}
                           } else {
                           	echo "checked";
                           }
                           ?>> <label for="minus">Minus</label></span>
                     </td>
                  </tr>
                  <td style="text-align: right;"><b>Grand Total (₹):</b></td>
                  <td >
                     <input type="text" name="Grand_Total" id="Grand_Total"
                        class="form-control form-control-sm" readonly
                        value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>">
                  </td>
                  </tr>
               </table>
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
   $("#Vendor_Name").select2();
   $("#Billing_To").select2();
   $("#Work_Location").select2();
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   	input: false
   });
   
   function calculate() {
   	var liter = $('#Liter').val() || 1;
   	var rate = $('#Rate').val() || 1;
   	var amount = parseFloat(liter) * parseFloat(rate);
   	$('#Amount').val(amount.toFixed(2));
   
   
   
   	var total_amount = $("#Amount").val();
   	var total_amount_array = total_amount.split('.');
   	var total_amount_int = total_amount_array[0];
   	var total_amount_dec = total_amount_array[1];
   
   	if (total_amount_dec == undefined) {
   		total_amount_dec = 0;
   	}
   
   	$("#Total_Discount").val('0.' + total_amount_dec);
   
   	$("#Grand_Total").val(total_amount_int);
   
   
   }
   
   	$(document).on('change', '.plus_minus', function () {
   	var id = $(this).attr('id');
   	var Total_Discount = $('#Total_Discount').val();
   	var Total = $('#Amount').val();
   	if (id == 'plus') {
   		var Total = parseFloat($('#Amount').val()) + parseFloat(Total_Discount);
   		$('#Grand_Total').val(Total);
   	} else {
   		var Total = parseFloat($('#Amount').val()) - parseFloat(Total_Discount);
   		$('#Grand_Total').val(Total);
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

    <?php
    $cleanedBuyer = cleanSearchValue(
        isset($temp_punch_detail->billing_to) && !is_null($temp_punch_detail->billing_to) ? $temp_punch_detail->billing_to : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor_name) && !is_null($temp_punch_detail->vendor_name) ? $temp_punch_detail->vendor_name : ""
    );
     $cleanedlocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>


    loadDropdownOptions(
        'Billing_To',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->To_ID) ? $punch_detail->To_ID : "" ?>'
    );


    loadDropdownOptions(
        'Vendor_Name',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->From_ID) ? $punch_detail->From_ID : "" ?>'
    );

     loadDropdownOptions(
        'Work_Location',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->Loc_Name) ? $punch_detail->Loc_Name : "" ?>'
    );
});
   
</script>