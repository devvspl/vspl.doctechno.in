<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$worklocation_list = $this->customlib->getWorkLocationList();
$employee_list = $this->customlib->getEmployeeList();
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
		<form action="<?= base_url(); ?>Form/Vehicle_ctrl/save_two_four_wheel_exp" id="vehicle_expense_form"
			  name="vehicle_expense_form" method="post" accept-charset="utf-8">
			<div class="col-md-7">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="form-group col-md-4">
						<label for="">Employee / Payee Name :</label> <span class="text-danger">*</span>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->employee_name; ?>
                        </small>
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
					<div class="form-group col-md-3">
						<label for="">Bill Date:</label> <span class="text-danger">*</span>
						<input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker"
							   value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>" autocomplete="off">
					</div>

					<div class="form-group col-md-3">
						<label for="">Vehicle No. :</label> <span class="text-danger">*</span>
						<input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control"
							   value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : '' ?>" autocomplete="off">
					</div>

					<div class="form-group col-md-2">
						<label for="">Vehicle Type:</label> <span class="text-danger">*</span>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->vehicle_type; ?>
                        </small>
						<select name="Vehicle_Type" id="Vehicle_Type" class="form-control">
							<?php
							$vehicle_type = array('Two Wheeler', 'Four Wheeler');
							?>
							<?php foreach ($vehicle_type as $key => $value) { ?>
								<option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
									echo "selected";
								} ?>><?= $value ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="">Location:</label>  <span class="text-danger">*</span>
						<small class="text-danger">
                            <?php echo $temp_punch_detail->location; ?>
                        </small>
						<select name="Location" id="Location" class="form-control form-control-sm" required data-parsley-errors-container="#LocationError">
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
					<div class="form-group col-md-3">
						<label for="pwd">Rs/KM :</label>
						<input type="text" name="Rate" id="Rate" class="form-control"
							   value="<?= (isset($punch_detail->VehicleRs_PerKM)) ? $punch_detail->VehicleRs_PerKM : '' ?>">
					</div>

				</div>
				<div class="row" style="height: 300px; overflow:auto">
					<table class="table">
						<thead>

						<th>Opening KM</th>
						<th>Closing KM</th>
						<th>Total Km</th>
						<th>Amount</th>
						<th></th>
						</thead>
						<tbody id="multi_record">
						<tr>

							<td><input type="text" class="form-control" id="Dist_Opening1" name="Dist_Opening[]"
									   onchange="calc_distance(1);"></td>
							<td><input type="text" class="form-control" id="Dist_Closing1" name="Dist_Closing[]"
									   onchange="calc_distance(1);"></td>
							<td><input type="text" class="form-control" id="Km1" name="Km[]" readonly></td>
							<td><input type="text" class="form-control" id="Amount1" name="Amount[]" readonly></td>
							<td>
								<button type="button" name="add" id="add" class="btn btn-primary btn-xs"
										style="margin-top: 5px;"><i class="fa fa-plus"></i></button>
							</td>
						</tr>
						</tbody>
						<tr>
							<td></td>
							<td style="text-align: right"><b>Total:</b></td>
							<td><input type="text" class="form-control form-control-sm" id="Total_KM" name="Total_KM"
									   readonly
									   value="<?= (isset($punch_detail->TotalRunKM)) ? $punch_detail->TotalRunKM : '' ?>">
							</td>
							<td><input type="text" class="form-control form-control-sm" id="Total_Amount"
									   name="Total_Amount" readonly
									   value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
							</td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td style="text-align: right"><b>Round Off:</b></td>
							<td><input type="text" name="Total_Discount" id="Total_Discount"
									   class="form-control form-control-sm d-inline"
									   value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>">
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
									?>> <label for="minus">Minus</label></span></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td style="text-align: right"><b>Grand Total:</b></td>
							<td><input type="text" id="Grand_Total" name="Grand_Total" class="form-control" value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>" readonly></td>
						</tr>
					</table>
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
	$("#Employee").select2();
	$("#Location").select2();
	$(".datepicker").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
	$(document).on("change", "#Employee", function () {
		var code = $(this).find(':selected').data('code');
		$("#Emp_Code").val(code);
	});
	var Count = 1;
	getMultiRecord();

	function getMultiRecord() {
		var Scan_Id = $('#Scan_Id').val();
		$.ajax({
			url: '<?= base_url() ?>Form/Vehicle_ctrl/getTwoFourWheelerRecord',
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
						$("#Dist_Opening" + i).val(response.data[i - 1].DistTraOpen);
						$("#Dist_Closing" + i).val(response.data[i - 1].DistTraClose);
						$("#Km" + i).val(response.data[i - 1].Totalkm);
						$("#Amount" + i).val(response.data[i - 1].FilledTAmt);
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
		if (confirm('Are you sure you want to delete this record?')) {
			$(this).closest('tr').remove();
			Count--;
		}
	});

	function multi_record(num) {
		var html = '';
		html += '<tr>';

		html += '<td><input type="text" class="form-control" id="Dist_Opening' + num + '" name="Dist_Opening[]" onchange="calc_distance(' + num + ');"></td>';
		html += '<td><input type="text" class="form-control" id="Dist_Closing' + num + '" name="Dist_Closing[]" onchange="calc_distance(' + num + ');"></td>';
		html += '<td><input type="text" class="form-control" id="Km' + num + '" name="Km[]" readonly></td>';
		html += '<td><input type="text" class="form-control" id="Amount' + num + '" name="Amount[]" readonly></td>';
		html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
		html += '</tr>';
		$('#multi_record').append(html);
		$('.datepicker').datetimepicker({});
	}

	$(document).on('change', '.plus_minus', function () {
		var id = $(this).attr('id');
		var Total_Discount = $('#Total_Discount').val();
		var Total = $('#Total_Amount').val();
		//  var total_amount_array = Total.split('.');
		//  var total_amount_int = total_amount_array[0];
		if (id == 'plus') {
			var Total = parseFloat($('#Total_Amount').val()) + parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		} else {
			var Total = parseFloat($('#Total_Amount').val()) - parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		}
	});

	function calc_distance(num) {
		var Dist_Opening = $('#Dist_Opening' + num).val();
		var Dist_Closing = $('#Dist_Closing' + num).val();
		var Km = Dist_Closing - Dist_Opening;
		$('#Km' + num).val(Km);

		var Rate = $('#Rate').val();
		if (Rate == '') {
			Rate = 0;
		}
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


		var total_amount = $("#Total_Amount").val();
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
</script>
