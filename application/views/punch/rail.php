<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Vehicle_ctrl/Save_Rail" id="punch_form" name="punch_form"
      method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Mode:</label>
               <input type="text" name="mode" id="mode" value="Rail" class="form-control form-control-sm"
                  readonly>
            </div>
            <div class="form-group col-md-3">
               <label for="Train_Number">Train Number:</label>
               <input type="text" name="Train_Number" id="Train_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Agent_Name">Agent Name:</label>
               <input type="text" name="Agent_Name" id="Agent_Name" class="form-control form-control-sm" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="PNR_Number">PNR Number:</label>
               <input type="text" name="PNR_Number" id="PNR_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="Booking_Date">Date of Booking:</label>
               <input type="text" name="Booking_Date" id="Booking_Date"
                  class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Journey_Date">Journey Date:</label>
               <input type="text" name="Journey_Date" id="Journey_Date"
                  class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Booking_Id">Booking ID:</label>
               <input type="text" name="Booking_Id" id="Booking_Id" class="form-control form-control-sm" value="<?= (isset($punch_detail->FDRNo)) ? $punch_detail->FDRNo : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Transaction_Id">Transaction ID:</label>
               <input type="text" name="Transaction_Id" id="Transaction_Id" class="form-control form-control-sm" value="<?= (isset($punch_detail->RegNo)) ? $punch_detail->RegNo : '' ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="Journey_From">Journey From:</label>
               <input type="text" name="Journey_From" id="Journey_From" class="form-control form-control-sm" value="<?= (isset($punch_detail->TripStarted)) ? $punch_detail->TripStarted : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Journey_To">Journey Upto:</label>
               <input type="text" name="Journey_To" id="Journey_To" class="form-control form-control-sm" value="<?= (isset($punch_detail->TripEnded)) ? $punch_detail->TripEnded : '' ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="Travel_Class">Travel Class:</label>
               <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->travel_class : ''; ?>
               </small>
               <select name="Travel_Class" id="Travel_Class" class="form-control form-select form-select-sm">
                  <option value="">Select</option>
                  <?php
                     $travel_class = array('Sleeper','III AC', 'II AC','I AC');
                     ?>
                  <?php foreach ($travel_class as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->TravelClass) && $punch_detail->TravelClass == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="Travel_Quota">Quota:</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->quota : ''; ?>
               </small>
               <select name="Travel_Quota" id="Travel_Quota" class="form-control form-select form-select-sm">
                  <option value="">Select</option>
                  <?php
                     $travel_quota = array('General','Ladies', 'Senior Citizen','Divyang','Tatkal','Premium Tatkal');
                     ?>
                  <?php foreach ($travel_quota as $key => $value) { ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->TravelQuota) && $punch_detail->TravelQuota == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col-md-3">
               <label for="">Location :</label>
               <small class="text-danger">
                <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
               </small>
               <select name="Location" id="Location" class="form-control">
                  <option value="">Select Location</option>
                
               </select>
            </div>
         </div>
         <div class="row" style="margin-top: 20px;">
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
            <div class="form-group col-md-12">
               <label for="">Passenger Details :</label>
               <textarea name="Passenger_Details" id="Passenger_Details"  rows="2"
                  class="form-control"><?= (isset($punch_detail->PassengerDetail)) ? $punch_detail->PassengerDetail : '' ?></textarea>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Base Fare :</label> <span class="text-center">*</span>
               <input type="text" name="Base_Fare" id="Base_Fare" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->Base_Fare)) ? $punch_detail->Base_Fare : '' ?>" onchange="calculate();">
            </div>
            <div class="form-group col-md-3">
               <label for="">GST (in Rs.) :</label>
               <input type="text" name="GST" id="GST" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->GSTIN)) ? $punch_detail->GSTIN : '' ?>" onchange="calculate();">
            </div>
            <div class="form-group col-md-3">
               <label for="">Fees & Surcharge :</label>
               <input type="text" name="Surcharge" id="Surcharge" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->Surcharge)) ? $punch_detail->Surcharge : '' ?>" onchange="calculate();">
            </div>
            <div class="form-group col-md-3">
               <label for="">Other :</label>
               <input type="text" name="Other" id="Other" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : '' ?>" onchange="calculate();">
            </div>
         </div>
         <div class="row">
            <div class="col-md-8"></div>
            <div class="form-group col-md-4">
               <label for="">Total Fare:</label>
               <input type="text" name="Total_Amount" id="Total_Amount" class="form-control form-control-sm"
                  value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
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
   $(".select2").select2();
   $("#Location").select2();
   $("#Travel_Class").select2();
   $("#Travel_Quota").select2();
   $('.datepicker').datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   });
   
   function calculate(){
   	var base_fare = $("#Base_Fare").val();
   	var gst = $("#GST").val();
   	var surcharge = $("#Surcharge").val();
   
   	var other = $("#Other").val();
   	var total = 0;
   	if(base_fare != ''){
   		total = parseFloat(base_fare);
   	}
   	if(gst != ''){
   		total = total + parseFloat(gst);
   	}
   	if(surcharge != ''){
   		total = total + parseFloat(surcharge);
   	}
   
   	if(other != ''){
   		total = total + parseFloat(other);
   	}
   
   	$("#Total_Amount").val((total).toFixed(2));
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

     $cleanedlocation = cleanSearchValue(
        isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
    );
    ?>



     loadDropdownOptions(
        'location_id',
        '<?= base_url("extract/ExtractorController/get_location_options") ?>',
        <?= json_encode($cleanedlocation) ?>,
        '<?= isset($punch_detail->Loc_Name) ? $punch_detail->Loc_Name : "" ?>'
    );

});


</script>