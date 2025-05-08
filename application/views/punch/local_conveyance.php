<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Vehicle_ctrl/save_local_conveyance" id="punch_form" name="punch_form" method="post" accept-charset="utf-8">
      <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="">Mode:</label>
            <small class="text-danger">
            <?php echo $temp_punch_detail->mode; ?>
            </small>
            <select name="Travel_Mode" id="Travel_Mode" class="form-control" required>
               <option value="">Select</option>
               <?php
                  $travel_mode = array('Sharing Taxi/Cab', 'Auto', 'Bus');
                  ?>
               <?php foreach ($travel_mode as $key => $value) { ?>
               <option value="<?= $value ?>" <?php if (isset($punch_detail->TravelMode) && $punch_detail->TravelMode == $value) {
                  echo "selected";
                  } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="col-md-3">
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
         <div class="form-group col-md-3">
            <label for="">Employee Name:</label>
            <small class="text-danger">
            <?php echo $temp_punch_detail->employee_name; ?>
            </small>
            <select name="Employee" id="Employee" class="form-control" required data-parsley-errors-container="#EmpError">
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
            <div id="EmpError"></div>
         </div>
         <div class="col-md-3">
            <label for="">Emp Code :</label>
            <input type="text" name="Emp_Code" id="Emp_Code" class="form-control" value="<?= (isset($punch_detail->EmployeeCode)) ? $punch_detail->EmployeeCode : '' ?>" readonly>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="" id="">Vehicle No:</label> <span class="text-danger">*</span>
            <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" required value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="" id="">Month:</label> <span class="text-danger">*</span>
            <small class="text-danger">
            <?php echo $temp_punch_detail->month; ?>
            </small>
            <select name="Month" id="Month" class="form-control" required>
               <option value="">Select</option>
               <?php foreach ($months as $key => $value) { ?>
               <option value="<?= $key ?>" <?php if (isset($punch_detail->Month) && $punch_detail->Month == $key) {
                  echo "selected";
                  } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="col-md-3">
            <label for="">Calculation Base:</label>
            <select name="cal_by" id="cal_by" class="form-control">
               <option value="KM_Base">K.M. Base</option>
               <option value="Fixed">Fixed</option>
            </select>
         </div>
         <div class="col-md-3" id="km_base_div">
            <label for="">Per KM Rate :</label> <span class="text-danger">*</span>
            <input type="number" name="Per_KM_Rate" id="Per_KM_Rate" class="form-control" required min="1" step="0.1" value="<?= (isset($punch_detail->VehicleRs_PerKM)) ? $punch_detail->VehicleRs_PerKM : '' ?>">
         </div>
         <div class="col-md-3" style="display: none;" id="fixed_base_div">
            <label for="">Fixed Amount :</label> <span class="text-danger">*</span>
            <input type="number" name="Fixed_Amount" id="Fixed_Amount" class="form-control" required min="1" step="0.1" value="<?= (isset($punch_detail->VehicleRs_PerKM)) ? $punch_detail->VehicleRs_PerKM : '' ?>">
         </div>
      </div>
      <div class="row" style="height: 200px; overflow:auto;margin-top:20px;">
         <table class="table">
            <thead>
               <th>Date</th>
               <th>Opening Reading</th>
               <th>Closing Reading</th>
               <th>Total Km</th>
               <th>Amount</th>
               <th></th>
            </thead>
            <tbody id="multi_record">
               <tr>
                  <td><input type="text" class="form-control datepicker" id="Date1" name="Date[]" required>
                  </td>
                  <td><input type="text" class="form-control" id="Dist_Opening1" name="Dist_Opening[]" onchange="calc_distance(1);" required></td>
                  <td><input type="text" class="form-control" id="Dist_Closing1" name="Dist_Closing[]" onchange="calc_distance(1);" required></td>
                  <td><input type="text" class="form-control" id="Km1" name="Km[]" readonly></td>
                  <td><input type="text" class="form-control" id="Amount1" name="Amount[]" readonly></td>
                  <td>
                     <button type="button" name="add" id="add" class="btn btn-primary btn-xs" style="margin-top: 5px;"><i class="fa fa-plus"></i></button>
                  </td>
               </tr>
            </tbody>
            <tr>
               <td colspan="2"></td>
               <td><b>Total:</b></td>
               <td><input type="text" class="form-control form-control-sm" id="Total_KM" name="Total_KM" readonly value="<?= (isset($punch_detail->TotalRunKM)) ? $punch_detail->TotalRunKM : '' ?>">
               </td>
               <td><input type="text" class="form-control form-control-sm" id="Total_Amount" name="Total_Amount" readonly value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
               </td>
            </tr>
         </table>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
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
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   });
   
   var Count = 1;
   getMultiRecord();
   
   function getMultiRecord() {
   	var Scan_Id = $('#Scan_Id').val();
   	$.ajax({
   		url: '<?= base_url() ?>form/Vehicle_ctrl/getTwoFourWheelerRecord',
   		type: 'POST',
   		data: {
   			Scan_Id: Scan_Id
   		},
   		dataType: 'json',
   		success: function(response) {
   
   			if (response.status == 200) {
   				Count = (response.data).length;
   
   				for (var i = 1; i <= Count; i++) {
   					if (i >= 2) {
   						multi_record(i);
   					}
   
   					$("#Date" + i).val(response.data[i - 1].JourneyStartDt);
   
   					$("#Dist_Opening" + i).val(response.data[i - 1].DistTraOpen);
   					$("#Dist_Closing" + i).val(response.data[i - 1].DistTraClose);
   					$("#Km" + i).val(response.data[i - 1].Totalkm);
   					$("#Amount" + i).val(response.data[i - 1].FilledTAmt);
   				}
   			}
   		}
   	});
   }
   
   $(document).on('click', '#add', function() {
   	Count++;
   	multi_record(Count);
   });
   
   $(document).on('click', '#remove', function() {
   	if (confirm('Are you sure you want to delete this record?')) {
   		$(this).closest('tr').remove();
   		Count--;
   	}
   });
   
   function multi_record(num) {
   	var html = '';
   	html += '<tr>';
   	html += '<td><input type="text" class="form-control datepicker" id="Trip_Started' + num + '" name="Trip_Started[]"></td>';
   
   	html += '<td><input type="text" class="form-control" id="Dist_Opening' + num + '" name="Dist_Opening[]" onchange="calc_distance(' + num + ');"></td>';
   	html += '<td><input type="text" class="form-control" id="Dist_Closing' + num + '" name="Dist_Closing[]" onchange="calc_distance(' + num + ');"></td>';
   	html += '<td><input type="text" class="form-control" id="Km' + num + '" name="Km[]" readonly></td>';
   	html += '<td><input type="text" class="form-control" id="Amount' + num + '" name="Amount[]" readonly></td>';
   	html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
   	html += '</tr>';
   	$('#multi_record').append(html);
   	$('.datepicker').datetimepicker({
   		timepicker: false,
   		format: 'Y-m-d'
   	});
   }
   
   function calc_distance(num) {
   	var Dist_Opening = $('#Dist_Opening' + num).val();
   	var Dist_Closing = $('#Dist_Closing' + num).val();
   	var cal_by = $('#cal_by').val();
   	if (Dist_Opening == '' || Dist_Opening == null) {
   		Dist_Opening = 0;
   	}
   	if (Dist_Closing == '' || Dist_Closing == null) {
   		Dist_Closing = 0;
   	}
   	var Km = Dist_Closing - Dist_Opening;
   	$('#Km' + num).val(Km);
   
   	var Rate = 0;
   	if (cal_by == 'KM_Base') {
   		Rate = $("#Per_KM_Rate").val();
   		var Amount = Km * Rate;
   		$('#Amount' + num).val(Amount);
   		var Total_KM = 0;
   		var Total_Amount = 0;
   		for (var i = 1; i <= Count; i++) {
   			var Km = $('#Km' + i).val();
   			if (Km == '') {
   				Km = 0;
   			}
   			Total_KM += parseFloat(Km);
   			var Amount = $('#Amount' + i).val();
   			if (Amount == '') {
   				Amount = 0;
   			}
   			Total_Amount += parseFloat(Amount);
   		}
   		$('#Total_KM').val(Total_KM);
   		$('#Total_Amount').val(Total_Amount.toFixed(2));
   	} else if (cal_by == 'Fixed') {
   		var Total_KM = 0;
   		var Total_Amount = $("#Fixed_Amount").val();
   		for (var i = 1; i <= Count; i++) {
   			var Km = $('#Km' + i).val();
   			if (Km == '') {
   				Km = 0;
   			}
   			Total_KM += parseFloat(Km);
   
   		}
   		$('#Total_KM').val(Total_KM);
   		$('#Total_Amount').val(Total_Amount);
   	}
   
   
   }
   
   $("#Employee").select2();
   $(document).on("change", "#Employee", function() {
   	var code = $(this).find(':selected').data('code');
   	$("#Emp_Code").val(code);
   });
   
   $(document).on("change", "#cal_by", function() {
   	var cal_by = $(this).val();
   	if (cal_by == 'KM_Base') {
   		$("#km_base_div").show();
   		$("#fixed_base_div").hide();
   		$("#Fixed_Amount").val('');
   	} else {
   		$("#km_base_div").hide();
   		$("#fixed_base_div").show();
   		$("#Per_KM_Rate").val('');
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