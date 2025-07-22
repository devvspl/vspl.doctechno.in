<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="airbustrainform" name="airbustrainform"
      method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-4">
            <label for="mode">Mode:</label>
            <input type="text" name="mode" id="mode" value="Air" class="form-control form-control-sm"
               readonly>
         </div>
         <div class="form-group col-md-4">
            <label for="agent_name">Agent Name:</label>
            <input type="text" name="agent_name" id="agent_name" class="form-control form-control-sm" value="<?= (isset($punch_detail->agent_name)) ? $punch_detail->agent_name : '' ?>">
         </div>
         <div class="form-group col-md-4">
            <label for="pnr_number">PNR Number:</label>
            <input type="text" name="pnr_number" id="pnr_number" class="form-control form-control-sm" value="<?= (isset($punch_detail->pnr_number)) ? $punch_detail->pnr_number : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="date_of_booking">Date of Booking:</label>
            <input type="text" name="date_of_booking" id="date_of_booking"
               class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->date_of_booking)) ? date('Y-m-d', strtotime($punch_detail->date_of_booking)) : ''  ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="journey_date">Journey Date:</label>
            <input type="text" name="journey_date" id="journey_date"
               class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->journey_date)) ? date('Y-m-d', strtotime($punch_detail->journey_date)) : ''  ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="air_line">Airline:</label>
            <input type="text" name="air_line" id="air_line" class="form-control form-control-sm" value="<?= (isset($punch_detail->air_line)) ? $punch_detail->air_line : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="ticket_number">Ticket Number:</label>
            <input type="text" name="ticket_number" id="ticket_number" class="form-control form-control-sm" value="<?= (isset($punch_detail->ticket_number)) ? $punch_detail->ticket_number : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="journey_from">Journey From:</label>
            <input type="text" name="journey_from" id="journey_from" class="form-control form-control-sm" value="<?= (isset($punch_detail->journey_from)) ? $punch_detail->journey_from : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="journey_upto">Journey Upto:</label>
            <input type="text" name="journey_upto" id="journey_upto" class="form-control form-control-sm" value="<?= (isset($punch_detail->journey_upto)) ? $punch_detail->journey_upto : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="travel_class">Travel Class:</label>
            <small class="text-danger">
            <?php echo isset($temp_punch_detail) ? $temp_punch_detail->travel_class : ''; ?>
            </small>
            <select name="travel_class" id="travel_class" class="form-control form-select form-select-sm">
               <option value="">Select</option>
               <?php
                  $travel_class = array('Economy', 'Premium Economy', 'Business', 'First');
                  foreach ($travel_class as $value) {
                  ?>
               <option value="<?= $value ?>" <?php if (isset($punch_detail->travel_class) && $punch_detail->travel_class == $value) {
                  echo "selected";
                  } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="">Location :</label>
            <small class="text-danger">
            <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="Location" id="location_id" class="form-control">
               <option value="">Select Location</option>
            </select>
         </div>
      </div>
      <div class="row">
         <table class="table">
            <thead style="text-align: center;">
               <th style="width: 10%">#</th>
               <th style="width: 50%;">Employee</th>
               <th style="width: 20%">Emp Code</th>
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
                           foreach ($employee_list as $value) {
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
            <label for="passenger_details">Passenger Details:</label>
            <textarea name="passenger_details" id="passenger_details" rows="2"
               class="form-control"><?= (isset($punch_detail->passenger_details)) ? $punch_detail->passenger_details : '' ?></textarea>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-4">
            <label for="base_fare">Base Fare:</label> <span class="text-center">*</span>
            <input type="text" name="base_fare" id="base_fare" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->base_fare)) ? $punch_detail->base_fare : '' ?>" onchange="calculate();">
         </div>
         <div class="form-group col-md-4">
            <label for="gst">GST (in Rs.):</label>
            <input type="text" name="gst" id="gst" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->gst)) ? $punch_detail->gst : '' ?>" onchange="calculate();">
         </div>
         <div class="form-group col-md-4">
            <label for="fees_surcharge">Fees & Surcharge:</label>
            <input type="text" name="fees_surcharge" id="fees_surcharge" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->fees_surcharge)) ? $punch_detail->fees_surcharge : '' ?>" onchange="calculate();">
         </div>
         <div class="form-group col-md-4">
            <label for="cute_charge">CUTE Charge:</label>
            <input type="text" name="cute_charge" id="cute_charge" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->cute_charge)) ? $punch_detail->cute_charge : '' ?>" onchange="calculate();">
         </div>
         <div class="form-group col-md-4">
            <label for="extra_luggage">Extra Luggage:</label>
            <input type="text" name="extra_luggage" id="extra_luggage" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->extra_luggage)) ? $punch_detail->extra_luggage : '' ?>" onchange="calculate();">
         </div>
         <div class="form-group col-md-4">
            <label for="other">Other:</label>
            <input type="text" name="other" id="other" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->other)) ? $punch_detail->other : '' ?>" onchange="calculate();">
         </div>
      </div>
      <div class="row">
         <div class="col-md-8"></div>
         <div class="form-group col-md-4">
            <label for="total_fare">Total Fare:</label>
            <input type="text" name="total_fare" id="total_fare" class="form-control form-control-sm final_amount_column"
               value="<?= (isset($punch_detail->total_fare)) ? $punch_detail->total_fare : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="remark_comment">Remark / Comment:</label>
            <textarea name="remark_comment" id="remark_comment" cols="10" rows="3"
               class="form-control"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : '' ?></textarea>
         </div>
      </div>
      <div class="box-footer">
         <button type="reset" class="btn btn-danger">Reset</button>
         <?php if (!empty($user_permission) && $user_permission == 'N') : ?>
         <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
         <?php endif; ?>
         <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')) : ?>
         <input type="submit" class="btn btn-info pull-right" name="save_as_draft" value="Save as Draft"></input>
         <?php endif; ?>
      </div>
      <?php
         if ($this->customlib->haveSupportFile($scan_id) == 1) {
         ?>
      <div class="row" style="margin-top: 20px;">
         <div class="col-md-12">
            <label for="supporting_file">Supporting File:</label>
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
   		var scan_id = $('#scan_id').val();
         var docTypeId = $("#DocTypeId").val();
   		$.ajax({
   			url: '<?= base_url() ?>Punch/getEmployeeItems',
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
   						$("#Employee" + i).val(response.data[i - 1].emp_name).trigger('change');
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
   // $("#location_id").select2();
   $('.datepicker').datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d',
   });
   
   function calculate(){
   	var base_fare = $("#Base_Fare").val();
   	var gst = $("#GST").val();
   	var surcharge = $("#Surcharge").val();
   	var cute_charge = $("#Cute_Charge").val();
   	var extra_luggage = $("#Extra_Luggage").val();
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
   	if(cute_charge != ''){
   		total = total + parseFloat(cute_charge);
   	}
   	if(extra_luggage != ''){
   		total = total + parseFloat(extra_luggage);
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
        '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
    );
   
   });
   
</script>