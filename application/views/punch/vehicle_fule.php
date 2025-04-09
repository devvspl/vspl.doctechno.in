<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$vendor_list = $this->customlib->getVendorList();
$worklocation_list = $this->customlib->getWorkLocationList();
$temp_punch_detail = $this->db->get_where("ext_tempdata_{$DocType_Id}", ['scan_id' => $Scan_Id])->row();
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
		<form action="<?= base_url(); ?>Form/Vehicle_ctrl/Save_Vehicle_Fule" id="punch_form" name="punch_form"
			  method="post" accept-charset="utf-8">
			<div class="col-md-6">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="form-group col-md-6">
						<label for="">Vendor Name:</label> <span class="text-danger">*</span>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->vendor_name; ?>
                        </small>
						<select name="Vendor_Name" id="Vendor_Name" class="form-control" required
								data-parsley-errors-container="#VendorError">
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
						<div id="VendorError"></div>
					</div>
					<div class="form-group col-md-6">
						<label for="">Billing To:</label> <span class="text-danger">*</span>
						<small class="text-danger">
                            <?php  echo $temp_punch_detail->billing_to; ?>
                        </small>
						<select name="Billing_To" id="Billing_To" class="form-control" required
								data-parsley-errors-container="#Billing_ToError">
							<option value="">Select</option>
							<?php
							foreach ($company_list as $key => $value) {
								$selected = '';
								if (isset($punch_detail->To_ID) && $punch_detail->To_ID == $value['firm_id']) {
									$selected = 'selected';
								}
								echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
							}
							?>
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
                            <?php  echo $temp_punch_detail->location; ?>
                        </small>
						<select name="Work_Location" id="Work_Location" class="form-control form-control-sm" required data-parsley-errors-container="#LocationError">
							<option value="">Select</option>
							<?php foreach ($worklocation_list as $key => $value) { ?>
								<option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
									if ($value['location_name'] == $punch_detail->Loc_Name) {
										echo "selected";
									}
								}  ?>><?= $value['location_name'] ?></option>
							<?php } ?>
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


		//split decimal point from total amount
		var total_amount = $("#Amount").val();
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

		$(document).on('change', '.plus_minus', function () {
		var id = $(this).attr('id');
		var Total_Discount = $('#Total_Discount').val();
		var Total = $('#Amount').val();
		//  var total_amount_array = Total.split('.');
		//  var total_amount_int = total_amount_array[0];
		if (id == 'plus') {
			var Total = parseFloat($('#Amount').val()) + parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		} else {
			var Total = parseFloat($('#Amount').val()) - parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		}
	});

</script>
