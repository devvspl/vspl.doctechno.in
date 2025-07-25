<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="punch_form" name="punch_form"
      method="post" accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
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
            <div class="col-md-4 form-group">
               <label for="">Invoice No:</label>
               <input type="text" name="InvoiceNo" id="InvoiceNo" class="form-control" required
                  value="<?= (isset($punch_detail->invoice_no)) ? $punch_detail->invoice_no : '' ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Invoice Date:</label>
               <input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker" required
                  value="<?= (isset($punch_detail->invoice_date)) ? date('Y-m-d', strtotime($punch_detail->invoice_date)) : '' ?>">
            </div>
            <div class="form-group col-md-4">
               <label for="">Vehicle No:</label>
               <input type="text" name="VehicleRegNo" id="VehicleRegNo" class="form-control" value="<?= (isset($punch_detail->vehicle_no)) ? $punch_detail->vehicle_no : '' ?>">
            </div>
            <div class="form-group col-md-6">
               <label for="">Location:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="Work_Location" id="Work_Location" class="form-control form-control-sm" required
                  data-parsley-errors-container="#LocationError">
                  <option value="">Select</option>
                 
               </select>
               <div id="LocationError"></div>
            </div>
         </div>
         <div class="row" style="height: 300px; overflow:auto">
            <div class="" style="overflow-x:scroll;">
               <table class="table" style="width:1600px;max-width:1600px;overflow-x:scroll;">
                  <thead style="text-align: center;">
                     <th style="width: 10px;">#</th>
                     <th style="width: 220px;">Particular</th>
                     <th style="width: 100px;">HSN</th>
                     <th style="width: 100px;">Qty</th>
                     <th style="width: 150px;">Unit</th>
                     <th style="width: 150px;">MRP</th>
                     <th style="width: 100px;">Discount in MRP</th>
                     <th style="width: 150px;">Price</th>
                     <th style="width: 180px;">Amount</th>
                     <th style="width: 80px;">GST %</th>
                     <th style="width: 80px;">SGST %</th>
                     <th style="width: 80px;">IGST %</th>
                     <th style="width: 120px;">Total Amount</th>
                     <th style="width: 30px;"></th>
                  </thead>
                  <tbody id="multi_record">
                     <tr>
                        <td>1</td>
                        <td>
                           <input type="text" name="Particular[]" id="Particular1"
                              class="form-control form-control-sm">
                        </td>
                        <td>
                           <input type="text" name="HSN[]" id="HSN1" class="form-control form-control-sm">
                        </td>
                        <td>
                           <input type="text" name="Qty[]" id="Qty1" class="form-control form-control-sm"
                              onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                        </td>
                        <td>
                           <select name="Unit[]" id="Unit1" class="form-control form-control-sm">
                              <option value="">Select</option>
                              <?php
                                 $unit_list = $this->db->get_where('master_unit', array('status' => 'A', 'is_deleted' => 'N'))->result_array();
                                 foreach ($unit_list as $key => $value) {
                                 	?>
                              <option value="<?= $value['unit_id'] ?>"><?= $value['unit_name'] ?></option>
                              <?php } ?>
                           </select>
                        </td>
                        <td>
                           <input type="text" name="MRP[]" id="MRP1" class="form-control form-control-sm"
                              onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                        </td>
                        <td>
                           <input type="text" name="Discount[]" id="Discount1"
                              class="form-control form-control-sm" onKeyPress="return isNumberKey(event)"
                              onchange="calculate(1)">
                        </td>
                        <td>
                           <input type="text" name="Price[]" id="Price1" class="form-control form-control-sm"
                              readonly>
                        </td>
                        <td>
                           <input type="text" name="Amount[]" id="Amount1"
                              class="form-control form-control-sm Amount" readonly>
                        </td>
                        <td>
                           <input type="text" name="GST[]" id="GST1" class="form-control form-control-sm"
                              onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                        </td>
                        <td>
                           <input type="text" name="SGST[]" id="SGST1" class="form-control form-control-sm"
                              readonly>
                        </td>
                        <td>
                           <input type="text" name="IGST[]" id="IGST1" class="form-control form-control-sm"
                              onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                        </td>
                        <td>
                           <input type="text" name="TAmount[]" id="TAmount1"
                              class="form-control form-control-sm TAmount" readonly>
                        </td>
                        <td>
                           <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                              style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                        </td>
                     </tr>
                  </tbody>
                  <tr>
                     <td colspan="6"></td>
                     <td><b>Sub Total (₹):</b></td>
                     <td colspan="2">
                        <input type="text" name="Sub_Total" id="Sub_Total"
                           class="form-control form-control-sm" readonly
                           value="<?= (isset($punch_detail->sub_total)) ? $punch_detail->sub_total : '' ?>">
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Total (₹):</b></td>
                     <td colspan="2">
                        <input type="text" name="Total" id="Total" class="form-control form-control-sm"
                           readonly
                           value="<?= (isset($punch_detail->total)) ? $punch_detail->total : '' ?>">
                     </td>
                  </tr>
                  <tr>
                     <td colspan="7" style="text-align: right;"><b>Round Off (₹):</b></td>
                     <td colspan="6">
                        <input type="text" name="Total_Discount" id="Total_Discount"
                           class="form-control form-control-sm d-inline"
                           value="<?= (isset($punch_detail->total_discount)) ? $punch_detail->total_discount : '' ?>"
                           style="width:100px;">
                        <span><input type="radio" name="plus_minus" id="plus" class="plus_minus" <?php
                           if (isset($punch_detail->total_discount)) {
                           	if ($punch_detail->grand_total > $punch_detail->total) {
                           		echo "checked";
                           	}
                           }
                           ?>>
                        <label for="plus">Plus</label>
                        </span>
                        <span><input type="radio" name="plus_minus" id="minus" class="plus_minus" <?php
                           if (isset($punch_detail->total_discount)) {
                           	if ($punch_detail->grand_total < $punch_detail->total) {
                           		echo "checked";
                           	}
                           } else {
                           	echo "checked";
                           }
                           ?>> <label for="minus">Minus</label></span>
                     </td>
                  </tr>
                  <td colspan="7" style="text-align: right;"><b>Grand Total (₹):</b></td>
                  <td colspan="2">
                     <input type="text" name="Grand_Total" id="Grand_Total"
                        class="form-control form-control-sm final_amount_column" readonly
                        value="<?= (isset($punch_detail->grand_total)) ? $punch_detail->grand_total : '' ?>">
                  </td>
                  </tr>
               </table>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12">
               <label for="">Remark / Comment:</label>
               <textarea name="Remark" id="Remark" cols="10" rows="3"
                  class="form-control"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : '' ?></textarea>
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
                     <a href="javascript:void(0);" target="popup"
                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
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
   $(document).ready(function () {
   
   	$("#Vendor_Name").select2();
   	$("#Billing_To").select2();
   	$("#Work_Location").select2();
   	$(".datepicker").datetimepicker({
   		timepicker: false,
   		format: 'Y-m-d',
   		input: false
   	});
   
   	getUnitList();
   
   	function getUnitList() {
   		$.ajax({
   			type: "POST",
   			url: '<?= base_url() ?>master/UnitController/get_unit_list',
   			dataType: "json",
   			success: function (response) {
   
   				if (response.status == 200) {
   					var x = '<option value="" selected >Select Unit</option>';
   					$.each(response.unit_list, function (k, v) {
   						x =
   							x +
   							'<option value="' +
   							v.unit_id +
   							'">' +
   							v.unit_name +
   
   							"</option>";
   					});
   				}
   
   				unit_list = x;
   			},
   		});
   	}
   
   
   	var Count = 1;
   
   	getMultiRecord();
   
   	function getMultiRecord() {
   		var scan_id = $('#scan_id').val();
           const docTypeId = $("#DocTypeId").val();
   		$.ajax({
   			url:   "<?= base_url() ?>Punch/getPunchItems",
   			type: 'POST',
   			data: {
   				scan_id: scan_id,
         type_id: docTypeId
   			},
   			dataType: 'json',
   			success: function (response) {
   
   				if (response.status == 200) {
   					Count = (response.data).length;
   
   					for (var i = 1; i <= Count; i++) {
   						if (i >= 2) {
   							multi_record(i);
   						}
   						$("#Particular" + i).val(response.data[i - 1].particular);
   						$("#HSN" + i).val(response.data[i - 1].hsn);
   						$("#Qty" + i).val(response.data[i - 1].qty);
   						$("#Unit" + i).val(response.data[i - 1].unit);
   						$("#MRP" + i).val(response.data[i - 1].mrp);
   						$("#Discount" + i).val(response.data[i - 1].discount_in_mrp);
   						$("#GST" + i).val(response.data[i - 1].gst);
   						$("#SGST" + i).val(response.data[i - 1].sgst);
   						$("#IGST" + i).val(response.data[i - 1].igst);
   						$("#Cess" + i).val(response.data[i - 1].cess);
   						$("#Price" + i).val(response.data[i - 1].price);
   						$("#Amount" + i).val(response.data[i - 1].amount);
   						$("#TAmount" + i).val(response.data[i - 1].total_amount);
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
   
   		$(this).closest('tr').remove();
   		// Count--;
   		calculate(Count);
   
   	});
   
   	function multi_record(num) {
   		var html = '';
   		html += '<tr>';
   		html += '<td>' + num + '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Particular[]" id="Particular' + num + '" class="form-control form-control-sm">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="HSN[]" id="HSN' + num + '" class="form-control form-control-sm">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Qty[]" id="Qty' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<select  name="Unit[]" id="Unit' + num + '" class="form-control form-control-sm">' + unit_list + '</select>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="MRP[]" id="MRP' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Discount[]" id="Discount' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Price[]" id="Price' + num + '" class="form-control form-control-sm" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="Amount[]" id="Amount' + num + '" class="form-control form-control-sm Amount" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="GST[]" id="GST' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="SGST[]" id="SGST' + num + '" class="form-control form-control-sm" readonly>';
   		html += '</td>';
   		html += '<td>';
   		html += '<input type="text" name="IGST[]" id="IGST' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
   		html += '</td>';
   
   		html += '<td>';
   		html += '<input type="text" name="TAmount[]" id="TAmount' + num + '" class="form-control form-control-sm TAmount" readonly>';
   		html += '</td>';
   
   		html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
   		html += '</tr>';
   		$('#multi_record').append(html);
   		$('.datepicker').datetimepicker({});
   	}
   
   
   });
   function isNumberKey(evt) {
   	var charCode = evt.which ? evt.which : evt.keyCode;
   	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
   		return false;
   
   	return true;
   }
   function calculate(num) {
   	var Qty = $('#Qty' + num).val();
   	var MRP = $('#MRP' + num).val();
   	var Discount = $('#Discount' + num).val();
   	var GST = $('#GST' + num).val();
   	var SGST = $('#SGST' + num).val(GST);
   	var IGST = $('#IGST' + num).val();
   
   	var TAmount = 0;
   	if (isNaN(Qty) || Qty == '') {
   		Qty = 0;
   	}
   	if (isNaN(MRP) || MRP == '') {
   		MRP = 0;
   	}
   	if (isNaN(Discount) || Discount == '') {
   		Discount = 0;
   	}
   	if (isNaN(GST) || GST == '') {
   		GST = 0;
   	}
   	if (isNaN(SGST) || SGST == '') {
   		SGST = 0;
   	}
   	if (isNaN(IGST) || IGST == '') {
   		IGST = 0;
   	}
   
   
   	if (GST > 0) {
   		$('#IGST' + num).attr('readonly', true);
   		$('#IGST' + num).val();
   	} else {
   		$('#IGST' + num).attr('readonly', false);
   		$('#IGST' + num).val();
   	}
   
   	var Price = MRP - Discount;
   	$('#Price' + num).val(Price);
   	var Amount = Qty * Price;
   	$('#Amount' + num).val(Amount);
   	$('#TAmount' + num).val(Amount);
   
   	var GSTAmount = (Amount * (GST * 2)) / 100;
   	var AF_GSTAmount = Amount + GSTAmount;
   
   	$('#TAmount' + num).val(AF_GSTAmount);
   	if (GST == 0 || GST == '') {
   		var IGSTAmount = (Amount * IGST) / 100;
   		var AF_IGSTAmount = Amount + IGSTAmount;
   		$('#TAmount' + num).val(AF_IGSTAmount);
   	}
   
   
   	var SubTotal = 0;
   	var Total = 0;
   	$('.TAmount').each(function () {
   		SubTotal += +$(this).val();
   	});
   
   	$("#Sub_Total").val(SubTotal);
   	$("#Total").val(SubTotal.toFixed(2));
   
   	//split decimal point from total amount
   	var total_amount = $("#Total").val();
   	var total_amount_array = total_amount.split('.');
   	var total_amount_int = total_amount_array[0];
   	var total_amount_dec = total_amount_array[1];
   
   	//check total amount is decimal or not
   	if (total_amount_dec == undefined) {
   		total_amount_dec = 0;
   	}
   
   	$("#Total_Discount").val('0.' + total_amount_dec);
   
   	$("#Grand_Total").val(total_amount_int);
   
   }
   
   function cal_tax() {
   
   	var Sub_Total = $('#Sub_Total').val();
   
   
   
   
   
   	var Total = (parseFloat(Sub_Total) + parseFloat(TCS));
   	$('#Total').val(Total);
   	var total_amount = $("#Total").val();
   	var total_amount_array = total_amount.split('.');
   	var total_amount_int = total_amount_array[0];
   	var total_amount_dec = total_amount_array[1];
   	if (total_amount_dec == undefined) {
   		total_amount_dec = 0;
   	}
   
   	$("#Total_Discount").val('0.' + total_amount_dec);
   	var Discount = $('#Total_Discount').val();
   	var Grand_Total = Total - Discount;
   	$('#Grand_Total').val(Grand_Total);
   
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
        '<?= isset($punch_detail->billing_to) ? $punch_detail->billing_to : "" ?>'
    );


    loadDropdownOptions(
        'Vendor_Name',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->vendor_name) ? $punch_detail->vendor_name : "" ?>'
    );

     loadDropdownOptions(
        'Work_Location',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
    );
});
   
</script>