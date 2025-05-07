<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/save_lodging" id="bankstatementform"
      name="bankstatementform" method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="col-md-4">
               <label for="">Location :</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->location; ?>
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
            <div class="form-group col-md-4">
               <label for="">Bill No:</label>
               <input type="text" name="Bill_No" id="Bill_No" class="form-control"
                  value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="">Bill Date:</label>
               <input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker"
                  value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>"
                  autocomplete="off">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-6">
               <label for="">Billing Name:</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->billing_name; ?>
               </small>
               <select name="Billing_Name" id="Billing_Name" class="form-control select2">
                  <option value="">Select</option>
                  <?php
                     foreach ($company_list as $key1 => $value1) {
                     	$selected = '';
                     	if (isset($punch_detail->CompanyID) && $punch_detail->CompanyID == $value1['firm_id']) {
                     		$selected = 'selected';
                     	}
                     	echo '<option value="' . $value1['firm_id'] . '" ' . $selected . ' data-address="' . $value1['address'] . '">' . $value1['firm_name'] . '</option>';
                     }
                     ?>
               </select>
            </div>
            <div class="form-group col-md-6">
               <label for="">Billing Address:</label>
               <input type="text" name="Billing_Address" id="Billing_Address" class="form-control"
                  value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-6">
               <label for="">Hotel Name:</label>
               <small class="text-danger">
               <?php  echo $temp_punch_detail->hotel_name; ?>
               </small>
               <select name="Hotel" id="Hotel" class="form-control">
                  <option value="">Select</option>
                  <?php
                     foreach ($hotel_list as $key1 => $value1) {
                     	$selected = '';
                     	if (isset($punch_detail->Hotel) && $punch_detail->Hotel == $value1['hotel_id']) {
                     		$selected = 'selected';
                     	}
                     	echo '<option value="' . $value1['hotel_id'] . '" ' . $selected . ' data-address="' . $value1['address'] . '">' . $value1['hotel_name'] . '</option>';
                     }
                     ?>
               </select>
            </div>
            <div class="form-group col-md-6">
               <label for="">Hotel Address:</label>
               <input type="text" name="Hotel_Address" id="Hotel_Address" class="form-control"
                  value="<?= (isset($punch_detail->Hotel_Address)) ? $punch_detail->Hotel_Address : '' ?>"
                  readonly>
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 form-group">
               <label for="">Billing instruction</label>
               <select name="Billing_Instruction" id="Billing_Instruction" class="form-control">
                  <option value="">Select</option>
                  <?php
                     $Billing_Instruction = array('Direct ', 'Bill to Company');
                     foreach ($Billing_Instruction as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->Particular) && $punch_detail->Particular == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="col-md-3 form-group">
               <label for="Booking_Id"> Booking ID:</label>
               <input type="text" name="Booking_Id" id="Booking_Id" class="form-control"
                  value="<?= (isset($punch_detail->RegNo)) ? $punch_detail->RegNo : '' ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Chk. In Date/Time:</label>
               <input type="text" name="Arrival_Date" id="Arrival_Date" class="form-control datetimepicker"
                  value="<?= (isset($punch_detail->FromDateTime)) ? $punch_detail->FromDateTime : '' ?>"
                  onchange="calculate_duration();">
            </div>
            <div class="form-group col-md-3">
               <label for="">Chk. Out Date/Time:</label>
               <input type="text" name="Departure_Date" id="Departure_Date" class="form-control datetimepicker"
                  value="<?= (isset($punch_detail->ToDateTime)) ? $punch_detail->ToDateTime : '' ?>"
                  onchange="calculate_duration();">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Duration of Stay:</label>
               <input type="text" name="Duration" id="Duration" class="form-control"
                  value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : '' ?>" readonly>
            </div>
            <div class="col-md-3 form-group">
               <label for="No_Room">Number of Rooms :</label>
               <?php
                  $list = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10);
                  echo form_dropdown('No_Room', $list, (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : '', 'class="form-control" id="No_Room" onchange="calculate();"');
                  ?>
            </div>
            <div class="form-group col-md-3">
               <label for="">Room Type:</label>
               <input type="text" name="Room_Type" id="Room_Type" class="form-control"
                  value="<?= (isset($punch_detail->TravelClass)) ? $punch_detail->TravelClass : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Meal Plan:</label>
               <input type="text" name="Meal" id="Meal" class="form-control"
                  value="<?= (isset($punch_detail->Loc_Name)) ? $punch_detail->Loc_Name : '' ?>">
            </div>
         </div>
         <div class="row">
            <table class="table">
               <thead style="text-align: center;">
                  <th style="width: 10%">#</th>
                  <th style="width: 50%;">Employee</th>
                  <th style="width: 20%">Emp Cpde</th>
                  <th></th>
               </thead>
               <tbody id="multi_record">
                  <tr>
                     <td>1</td>
                     <td>
                        <select name="Employee[]" id="Employee1"
                           class="form-control form-select form-select-sm select2" onchange="getCode(1)">
                           <option value="">Select</option>
                           <?php
                              foreach ($employee_list as $key => $value) {
                              	$id = htmlspecialchars($value['id']);
                              	$code = htmlspecialchars($value['emp_code']);
                              	$name = htmlspecialchars($value['emp_name']);
                              	$company = htmlspecialchars($value['company_code']);
                              	echo "<option value='{$id}' data-code='{$code}'>{$name} - {$company}</option>";
                              }
                              ?>
                        </select>
                     </td>
                     <td>
                        <input type="text" readonly name="EmpCode[]" id="EmpCode1"
                           class="form-control form-control-sm">
                     </td>
                     <td>
                        <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                           style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="row">
            <div class="col-md-4 form-group">
               <label for="Room_Rate">Rate:</label>
               <input type="number" class="form-control" min="1" step="0.5" id="Room_Rate" name="Room_Rate" onchange="calculate();" value="<?= (isset($punch_detail->TariffPlan)) ? $punch_detail->TariffPlan : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="Amount">Amount:</label>
               <input type="number" class="form-control" readonly id="Amount" name="Amount" value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="Other_Charge">Other Charges (+):</label>
               <input type="number" class="form-control" id="Other_Charge" name="Other_Charge" onchange="calculate();" value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="Discount">Discount (-):</label>
               <input type="number" class="form-control" id="Discount" name="Discount" onchange="calculate();" value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="Gst">GST (%):</label>
               <input type="number" class="form-control" id="Gst" name="Gst" onchange="calculate();" value="<?= (isset($punch_detail->GSTIN)) ? $punch_detail->GSTIN : '' ?>">
            </div>
            <div class="col-md-4 form-group">
               <label for="Grand_Total">Grand Total:</label>
               <input type="number" class="form-control" id="Grand_Total" name="Grand_Total" step="0.1" value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>">
            </div>
         </div>
         <div class="row mt-3">
            <div class="form-group col-md-12">
               <label for="">Remark / Comment:</label>
               <textarea name="Remark" id="Remark" cols="10" rows="3"
                  class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
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
   $(".select2").select2();
   $(document).on("change", "#Billing_Name", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#Billing_Address").val(address);
   });
   $(document).on("change", "#Hotel", function () {
   	var address = $(this).find(':selected').data('address');
   	$("#Hotel_Address").val(address);
   });
   
   $(".datetimepicker").datetimepicker({
   	timepicker: true,
   	format: 'Y-m-d H:i:s'
   });
   
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d'
   });
   
   function calculate_duration() {
   	const check_in = $("#Arrival_Date").val();
   	const check_out = $("#Departure_Date").val();
   
   	if (!check_in || !check_out) {
   		return;
   	}
   
   	const timeDiff = Math.abs(new Date(check_out) - new Date(check_in));
   	const duration = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
   
   	$("#Duration").val(duration);
   }
   
   $(document).ready(function () {
   	// get employee list from php $employee_list
   	var employee_list = <?= json_encode($employee_list) ?>;
   
   
   	$(document).on('click', '#add', function () {
   		Count++;
   		multi_record(Count);
   	});
   	$(document).on('click', '#remove', function () {
   		$(this).closest('tr').remove();
   	});
   	var Count = 1;
   
   	getMultiRecord();
   	function getMultiRecord() {
   		var Scan_Id = $('#Scan_Id').val();
   		$.ajax({
   			url: '<?= base_url() ?>form/Miscellaneous_ctrl/getLodgingEmployee',
   			type: 'POST',
   			data: {
   				Scan_Id: Scan_Id
   			},
   			dataType: 'json',
   			success: function (response) {
   
   				if (response.status == 200) {
   					Count = (response.data).length;
   
   					for (var i = 1; i <= Count; i++) {
   						if (i >= 2) {
   							multi_record(i);
   						}
   						$("#Employee" + i).val(response.data[i - 1].emp_id).trigger('change');
   						$("#EmpCode" + i).val(response.data[i - 1].emp_code);
   
   					}
   				}
   			}
   		});
   	}
   
   	function multi_record(num) {
   		var html = '';
   		html += '<tr>';
   		html += '<td>' + num + '</td>';
   		html += '<td><select name="Employee[]" id="Employee' + num + '" class="form-control form-select form-select-sm select2" onchange="getCode(' + num + ')"><option value="">Select</option>' +
   			employee_list.map(function (item) {
   				return '<option value="' + item.id + '" data-code="' + item.emp_code + '">' + item.emp_name + ' - ' + item.company_code + '</option>';
   			}).join('') +
   			'</select></td>';
   		html += '<td><input type="text" readonly name="EmpCode[]" id="EmpCode' + num + '" class="form-control form-control-sm"></td>';
   		html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs remove" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
   		html += '</tr>';
   		$('#multi_record').append(html);
   		$(".select2").select2();
   
   	}
   });
   
   function getCode(num) {
   	var code = $("#Employee" + num).find(':selected').data('code');
   	$("#EmpCode" + num).val(code);
   }
   
   function calculate() {
   	const duration = parseFloat($("#Duration").val()) || 1;
   	const no_room = parseFloat($("#No_Room").val()) || 1;
   	const room_rate = parseFloat($("#Room_Rate").val()) || 1;
   	const other_charge = parseFloat($("#Other_Charge").val()) || 0;
   	const discount = parseFloat($("#Discount").val()) || 0;
   	const gst = parseFloat($("#Gst").val()) || 0;
   	let total = duration * no_room * room_rate;
   	$("#Amount").val(total);
   	let sub_total = total + other_charge - discount;
   	sub_total *= 1 + gst / 100;
   	$("#Grand_Total").val((sub_total).toFixed(2));
   }
</script>