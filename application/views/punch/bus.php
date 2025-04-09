<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$employee_list = $this->customlib->getEmployeeList();
$locationlist = $this->customlib->getWorkLocationList();
?>
<div class="box-body">
	<div class="row">
		<div class="col-md-6">
			<?php if ($rec->File_Ext == 'pdf') { ?>
				<object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
			<?php } else { ?>
			<input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
				<div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #3a495e;"></div>
				<script>
					var curect_file_path = $('#image').val();
					$("#imageViewerContainer").verySimpleImageViewer({
						imageSource: curect_file_path,
						frame: ['100%', '100%'],
						maxZoom: '900%',
						zoomFactor: '10%',
						mouse: true,
						keyboard: true,
						toolbar: true,
						rotateToolbar: true
					});
				</script>
			<?php } ?>
		</div>
		<form action="<?= base_url(); ?>Form/Vehicle_ctrl/Save_Bus" id="punch_form" name="punch_form"
			  method="post" accept-charset="utf-8">
			<div class="col-md-6">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="form-group col-md-3">
						<label for="">Mode:</label>
						<input type="text" name="mode" id="mode" value="Bus" class="form-control form-control-sm"
							   readonly>
					</div>
					<div class="form-group col-md-3">
						<label for="Invoice_No">Invoice No.:</label>
						<input type="text" name="Invoice_No" id="Invoice_No" class="form-control form-control-sm" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="Invoice_Date">Invoice Date:</label>
						<input type="text" name="Invoice_Date" id="Invoice_Date"
							   class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="Agent_Name">Agent Name:</label>
						<input type="text" name="Agent_Name" id="Agent_Name" class="form-control form-control-sm" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : '' ?>">
					</div>

				</div>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="Booking_Id">Booking ID:</label>
						<input type="text" name="Booking_Id" id="Booking_Id" class="form-control form-control-sm" value="<?= (isset($punch_detail->FDRNo)) ? $punch_detail->FDRNo : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="Booking_Date">Date of Booking:</label>
						<input type="text" name="Booking_Date" id="Booking_Date"
							   class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : ''  ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="Ticket_Number">Ticket Number:</label>
						<input type="text" name="Ticket_Number" id="Ticket_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="Bus_Type">Bus Type:</label>
						<select name="Bus_Type" id="Bus_Type" class="form-control form-select form-select-sm">
							<option value="">Select</option>
							<?php
							$travel_class = array('Sleeper','AC', 'Non-AC');
							?>
							<?php foreach ($travel_class as $key => $value) { ?>
								<option value="<?= $value ?>" <?php if (isset($punch_detail->TravelClass) && $punch_detail->TravelClass == $value) {
									echo "selected";
								} ?>><?= $value ?></option>
							<?php } ?>
						</select>
					</div>

				</div>

				<div class="row">
				<div class="col-md-3">
						<label for="">Location :</label>
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
					<div class="form-group col-md-6">
						<label for="">Employee Name:</label>
						<select name="Employee" id="Employee" class="form-control select2">
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
					<div class="col-md-3">
						<label for="">Emp Code :</label>
						<input type="text" name="Emp_Code" id="Emp_Code" class="form-control" value="<?= (isset($punch_detail->EmployeeCode)) ? $punch_detail->EmployeeCode : '' ?>" readonly>
					</div>
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
										   onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</form>
	</div>

</div>

<script>

	$(document).on("change", "#Employee", function () {
		var code = $(this).find(':selected').data('code');
		$("#Emp_Code").val(code);
	});

	$(".select2").select2();
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
</script>
