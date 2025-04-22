<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();

$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$hotel_list = $this->db->select('hotel_id,hotel_name,address')->get_where('master_hotel', ['status' => 'A', 'is_deleted' => 'N'])->result_array();
$employee_list = $this->customlib->getEmployeeList();
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
		<form action="<?= base_url(); ?>form/Ticket_ctrl/save_ticket_cancellation" id="ticket_form" name="ticket_form"
			method="post" accept-charset="utf-8">
			<div class="col-md-6">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="col-md-3">
						<label for="">Agent Name:</label>
						<input type="text" name="AgentName" id="AgentName" class="form-control" autocomplete="off"
							value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : '' ?>">
					</div>
					<div class="col-md-3">
						<label for="">Date :</label>
						<input type="text" class="form-control datepicker" id="BillDate" name="BillDate"
							autocomplete="off"
							value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
					</div>

					<div class="col-md-3">
						<label for="">Date of Booking:</label>
						<input type="text" name="BookingDate" id="BookingDate" class="form-control datepicker"
							autocomplete="off"
							value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : ''  ?>">
					</div>
					<div class="col-md-3">
						<label for="">Cancelled Date :</label>
						<input type="text" class="form-control datepicker" id="File_Date" name="File_Date"
							autocomplete="off"
							value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : ''  ?>">
					</div>
				</div>

				<div class="row" style="margin-top: 10px;">
					<table class="table table-bordered">
						<thead style="text-align: center;">
							<tr style="text-align: center;">
								<th style="width: 5%; background:blueviolet;color:white;text-align: center; ">#</th>
								<th style="width: 40%; background:blueviolet;color:white;text-align: center;">Employee
								</th>
								<th style=" background:blueviolet;color:white;text-align: center;">PNR Number</th>
								<th style=" background:blueviolet;color:white;text-align: center;">Amount</th>
								<th style=" background:blueviolet;color:white;text-align: center;"></th>
							</tr>
						</thead>
						<tbody id="multi_record">
							<tr>
								<td style="text-align: center;">1</td>
								<td>
									<select name="Employee[]" id="Employee1"
										class="form-control form-select form-select-sm select2">
										<option value="">Select</option>
										<?php
									foreach ($employee_list as $key => $value) {
										$id = htmlspecialchars($value['id']);
									
										$name = htmlspecialchars($value['emp_name']);
										$company = htmlspecialchars($value['company_code']);
										echo "<option value='{$id}' >{$name} - {$company}</option>";
									}
									?>
									</select>
								</td>
								<td><input type="text" name="PNR[]" id="PNR1" class="form-control" autocomplete="off">
								</td>
								<td><input type="text" name="Amount[]" id="Amount1" class="form-control amount"
										autocomplete="off" onchange="calculate(1)"
										onKeyPress="return isNumberKey(event)"></td>
								<td>
									<button type="button" name="add" id="add" class="btn btn-primary btn-xs"
										style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
								</td>
							</tr>
						</tbody>
						<tr>
							<td></td>
							<td></td>
							<td style="text-align: right;">Sub Total:</td>
							<td>
								<input type="number" class="form-control" readonly id="SubTotal" name="SubTotal"
									value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : '' ?>"
									onKeyPress="return isNumberKey(event)">
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style="text-align: right;">Cancellation Charge:</td>
							<td>
								
								<input type="number" class="form-control" id="Total_Discount" name="Total_Discount"
                                 onchange="calculate(); calculate_charge();"
                                value="<?= isset($punch_detail->Total_Discount) ? $punch_detail->Total_Discount : '' ?>">
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style="text-align: right;">Other Charges:</td>
							<td><input type="number" class="form-control" id="OthCharge_Amount" name="OthCharge_Amount"
									onchange="calculate();calculate_charge();" 
									value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : '' ?>">
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style="text-align: right;">Grand Total:</td>
							<td><input type="number" class="form-control" id="Grand_Total" name="Grand_Total" step="0.1"
									value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : '' ?>"
									readonly></td>
						</tr>
					</table>
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
									onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');">
									<?php echo $row['File'] ?></a>
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
	$(".select2").select2();


	$(".datepicker").datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		input: false
	});

	function isNumberKey(evt) {
		var charCode = evt.which ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;

		return true;
	}


	var employee_list = <?= json_encode($employee_list) ?> ;


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
			url: '<?= base_url() ?>form/Ticket_ctrl/get_ticket_cancel_employee_list',
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
						$("#Employee" + i).val(response.data[i - 1].Emp_Id).trigger('change');
						$("#PNR" + i).val(response.data[i - 1].PNR);
						$("#Amount" + i).val(response.data[i - 1].Amount);

					}
				}
			}
		});
	}

	function multi_record(num) {
		var html = '';
		html += '<tr>';
		html += '<td>' + num + '</td>';
		html += '<td><select name="Employee[]" id="Employee' + num +
			'" class="form-control form-select form-select-sm select2" ><option value="">Select</option>' +
			employee_list.map(function (item) {
				return '<option value="' + item.id + '" data-code="' + item.emp_code + '">' + item
					.emp_name + ' - ' + item.company_code + '</option>';
			}).join('') +
			'</select></td>';
		html += '<td><input type="text"  name="PNR[]" id="PNR' + num +
			'" class="form-control"></td>';
		html += '<td><input type="text"  name="Amount[]" id="Amount' + num +
			'" class="form-control" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num +
			')"></td>';
		html +=
			'<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs remove" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
		html += '</tr>';
		$('#multi_record').append(html);
		$(".select2").select2();

	}



function calculate() {
    var subTotal = 0;
    var count = 10; // Assuming you have a 'Count' variable defined somewhere
    
    for (var i = 1; i <= count; i++) {
        var amount = parseFloat($('#Amount' + i).val());
        if (!isNaN(amount)) {
            subTotal += amount;
        }
    }
    
    $('#SubTotal').val(subTotal.toFixed(2));
}

function calculate_charge() {
    var totalDiscount = parseFloat($("#Total_Discount").val()) || 0;
    var otherCharge = parseFloat($("#OthCharge_Amount").val()) || 0;
    var subTotal = parseFloat($("#SubTotal").val()) || 0;

    var grandTotal = subTotal - (totalDiscount + otherCharge);
    $("#Grand_Total").val(grandTotal.toFixed(2));
}


</script>
