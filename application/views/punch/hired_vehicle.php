<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$vendor_list = $this->customlib->getVendorList();
$employee_list = $this->customlib->getEmployeeList();
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$locationlist = $this->customlib->getWorkLocationList();
$temp_punch_detail = $this->db->get_where("ext_tempdata_{$DocType_Id}", ['scan_id' => $Scan_Id])->row();
?>
<div class="box-body">
	<div class="row">
		<div class="col-md-5">
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
		<form action="<?= base_url(); ?>Form/Vehicle_ctrl/save_hired_vehicle" id="punch_form" name="punch_form"
			  method="post" accept-charset="utf-8">
			<div class="col-md-7">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="form-group col-md-5">
						<label for="">Agency Name:</label> <span class="text-danger">*</span>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->agency_name; ?>
                        </small>
						<select name="Agency_Name" id="Agency_Name" class="form-control" required
								data-parsley-errors-container="#AgencyError">
							<option value="">Select</option>
							<?php
							foreach ($vendor_list as $key => $value) {
								$selected = '';
								if (isset($punch_detail->From_ID) && $punch_detail->From_ID == $value['firm_id']) {
									$selected = 'selected';
								}
								echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
							}
							?>
						</select>
						<div id="AgencyError"></div>
					</div>
					<div class="form-group col-md-7">
						<label for="">Agency Address:</label>
						<input type="text" name="Agency_Address" id="Agency_Address" class="form-control"
							   value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
							   readonly>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-5">
						<label for="">Billing Name:</label> <span class="text-danger">*</span>
						<small class="text-danger">
                            <?php echo $temp_punch_detail->billing_name; ?>
                        </small>
						<select name="Billing_Name" id="Billing_Name" class="form-control" required
								data-parsley-errors-container="#BillingNameError" >
							<option value="">Select</option>
							<?php
							foreach ($company_list as $key1 => $value1) {
								$selected = '';
								if (isset($punch_detail->To_ID) && $punch_detail->To_ID == $value1['firm_id']) {
									$selected = 'selected';
								}
								echo '<option value="' . $value1['firm_id'] . '" ' . $selected . ' data-address="' . $value1['address'] . '">' . $value1['firm_name'] . '</option>';
							}
							?>
						</select>
						<div id="BillingNameError"></div>
					</div>
					<div class="form-group col-md-7">
						<label for="">Billing Address:</label>
						<input type="text" name="Billing_Address" id="Billing_Address" class="form-control" value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : '' ?>" readonly/>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-4">
						<label for="">Employee Name:</label>
							<small class="text-danger">
                            <?php echo $temp_punch_detail->employee_name; ?>
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
					<div class="col-md-4">
						<label for="">Emp Code :</label>
						<input type="text" name="Emp_Code" id="Emp_Code" class="form-control" value="<?= (isset($punch_detail->EmployeeCode)) ? $punch_detail->EmployeeCode : '' ?>" readonly>
					</div>
					<div class="form-group col-md-4">
						<label for="" id="">Vehicle No:</label> <span class="text-danger">*</span>
						<input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" required
							   value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>">
					</div>
				</div>
				<div class="row">
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
						<label for="">Invoice No:</label> <span class="text-danger">*</span>
						<input type="text" name="Invoice_No" id="Invoice_No" class="form-control " required
							   value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Invoice Date:</label> <span class="text-danger">*</span>
						<input type="text" name="Invoice_Date" id="Invoice_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : '' ?>" required>
					</div>

					<div class="col-md-3">
						<label for="">Per KM Rate :</label> <span class="text-danger">*</span>
						<input type="number" name="Per_KM_Rate" id="Per_KM_Rate" class="form-control"
							    required onchange="calculate();" min="1" step="0.1" value="<?= (isset($punch_detail->VehicleRs_PerKM)) ? $punch_detail->VehicleRs_PerKM : '' ?>">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-3">
						<label for="">Booking Date:</label> <span class="text-danger">*</span>
						<input type="text" name="Journey_Start" id="Journey_Start" class="form-control datepicker"
							   required
							   value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">End Date:</label> <span class="text-danger">*</span>
						<input type="text" name="Journey_End" id="Journey_End" class="form-control datepicker" required
							   value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d', strtotime($punch_detail->ToDateTime)) : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Start Reading:</label> <span class="text-danger">*</span>
						<input type="text" name="Opening_Reading" id="Opening_Reading" class="form-control" required
							   value="<?= (isset($punch_detail->OpeningKm)) ? $punch_detail->OpeningKm : '' ?>"
							   onchange="calculate();">
					</div>
					<div class="form-group col-md-3">
						<label for="">Closing Reading:</label> <span class="text-danger">*</span>
						<input type="text" name="Closing_Reading" id="Closing_Reading" class="form-control" required
							   value="<?= (isset($punch_detail->ClosingKm)) ? $punch_detail->ClosingKm : '' ?>"
							   onchange="calculate();">
					</div>
				</div>
				<div class="row">

					<div class="form-group col-md-4">
						<label for="">Total KM:</label>
						<input type="text" name="Total_KM" id="Total_KM" class="form-control" readonly  value="<?= (isset($punch_detail->TotalRunKM)) ? $punch_detail->TotalRunKM : '' ?>">

					</div>

					<div class="form-group col-md-4">
						<label for="">Other Charges:</label>
						<input type="text" name="Other_Charge" id="Other_Charge" class="form-control"
							   value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : '' ?>"
							   onchange="calculate();">
					</div>
					<div class="col-md-4 form-group" style="float: right;">
						<label for="">Total Amount</label>
						<input type="text" name="Total_Amount" id="Total_Amount" class="form-control"
							   value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>"
							   readonly>
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
	$("#Agency_Name").select2();
	$("#Billing_Name").select2();
	$("#Employee").select2();

	$(document).on("change", "#Agency_Name", function () {
		var address = $(this).find(':selected').data('address');
		$("#Agency_Address").val(address);
	});

	$(document).on("change", "#Billing_Name", function () {
		var address = $(this).find(':selected').data('address');
		$("#Billing_Address").val(address);
	});

	$(document).on("change", "#Employee", function () {
		var code = $(this).find(':selected').data('code');
		$("#Emp_Code").val(code);
	});

	function calculate() {

		var Per_KM_Rate = $('#Per_KM_Rate').val();
		var Opening_Reading = $('#Opening_Reading').val();
		var Closing_Reading = $('#Closing_Reading').val();
		var Other_Charge = $('#Other_Charge').val();

		if (Per_KM_Rate == '' || Per_KM_Rate == null) {
			Per_KM_Rate = 0;
		}
		if (Opening_Reading == '' || Opening_Reading == null) {
			Opening_Reading = 0;
		}
		if (Closing_Reading == '' || Closing_Reading == null) {
			Closing_Reading = 0;
		}
		if (Other_Charge == '' || Other_Charge == null) {
			Other_Charge = 0;
		}
		//calculate total km
		var Total_KM = parseFloat(Closing_Reading) - parseFloat(Opening_Reading);
		$('#Total_KM').val(Total_KM.toFixed(2));


		var Total_Amount =  (parseFloat(Total_KM) * parseFloat(Per_KM_Rate) )+ parseFloat(Other_Charge);
		$('#Total_Amount').val(Total_Amount.toFixed(2));
	}

	$(".datepicker").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
</script>
