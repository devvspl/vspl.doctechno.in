<style>
	.form-group {
		margin-bottom: 4px;
	}

	th {
		text-align: center;
	}

	.form-control-sm {
		display: inline-block;
		height: auto;
		font-size: 10pt;
		line-height: 1.42857143;
		color: #555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
	}

	.d-none {
		display: none !important;
	}
	.loader {
  width: 48px;
  height: 48px;
  border: 5px dotted #3a495e;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  box-sizing: border-box;
  animation: rotation 2s linear infinite;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>
<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$firm = $this->db->get_where('master_firm', ['status' => 'A'])->result_array();
$company_list = $this->customlib->getCompanyList();
$department_list = $this->customlib->getDepartmentList();
$file_list = $this->customlib->getFileList();
$worklocation_list = $this->customlib->getWorkLocationList();
$ledger_list = $this->customlib->getLedgerList();
$category_list = $this->customlib->getCategoryList();
$item_list = $this->customlib->getItemList();
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
				<div id="imageViewerContainer" style="width: 400px; height:490px;"></div>
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
		<form action="<?= base_url(); ?>form/Invoice_ctrl/create" id="invoice_form" name="invoice_form" method="post"
			  accept-charset="utf-8">
			  <div style="display: flex; flex-direction: column; align-items: center;">
				<div class="loader" id="loader" style="display: none;"></div>
				<span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #3a495e;">Please Wait...</span>
				</div>

			<div class="col-md-7" id="contnetBody">
		    	
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row" style="margin-bottom: 5px;">
					<div class="form-group col-md-3">
						<label for="">Invoice No:</label>
						<input type="text" name="Bill_No" id="Bill_No" class="form-control form-control-sm"
							   value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Invoice Date:</label>
						<input type="date" name="Bill_Date" id="Bill_Date" class="form-control form-control-sm"
							   value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Mode of Payment:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->mode_of_payment; ?>
                        </small>
						<select name="Payment_Mode" id="Payment_Mode" class="form-control form-control-sm form-select form-select-sm">
							<option value="">Select</option>
							<?php
								$mode = array('Credit','Cash', 'Cheque' );
								foreach ($mode as $key => $value) {
									?>
									<option value="<?= $value ?>" <?php if (isset($punch_detail->NatureOfPayment)) {
										if ($value == $punch_detail->NatureOfPayment) {
											echo "selected";
										}
									} ?>><?= $value ?></option>
								<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="">Suppliers Reference:</label>
						<input type="text" name="Supplier_Ref" id="Supplier_Ref" class="form-control form-control-sm" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : '' ?>">
					</div>
				</div>

				<div class="row" style="margin-bottom: 5px;">
					<div class="form-gro	up col-md-6">
						<label for="">Buyer:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->buyer; ?>
                        </small>
						<select name="From" id="From" class="form-control form-control-sm" 	onchange="getFile();getDepartment();">
							<option value="">Select</option>
							<?php
							foreach ($company_list as $key => $value) {
								?>
								<option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->From_ID)) {
									if ($value['firm_id'] == $punch_detail->From_ID) {
										echo "selected";
									}
								} ?>><?= $value['firm_name'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="">Vendor:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->vendor; ?>
                        </small>
						<select name="To" id="To" class="form-control form-control-sm">
							<option value="">Select</option>
							<?php
							foreach ($firm as $key => $value) {
								?>
								<option value="<?= $value['firm_id'] ?>" data-address="<?= $value['address']?>" <?php if (isset($punch_detail->To_ID)) {
									if ($value['firm_id'] == $punch_detail->To_ID) {
										echo "selected";
									}
								} ?>><?= $value['firm_name'] ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-bottom: 5px;">
					<div class="form-group col-md-6">
						<label for="">Buyer Address :</label>
						<input type="text" name="Buyer_Address" id="Buyer_Address" class="form-control form-control-sm"
							   value="<?= (isset($punch_detail->Loc_Add)) ? $punch_detail->Loc_Add : '' ?>"
							   readonly>
					</div>
					<div class="col-md-6">
						<label for="">Vendor Address :</label>
						<input type="text" name="Vendor_Address" id="Vendor_Address" class="form-control form-control-sm"
							   value="<?= (isset($punch_detail->AgencyAddress)) ? $punch_detail->AgencyAddress : '' ?>"
							   readonly>
					</div>
				</div>

				<div class="row" style="margin-bottom: 5px;">
					<div class="form-group col-md-3">
						<label for="">Buyer's Order No.:</label>
						<input type="text" name="Buyer_Order" id="Buyer_Order" class="form-control form-control-sm" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Buyer's Order No. Date:</label>
						<input type="text" name="Buyer_Order_Date" id="Buyer_Order_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->BookingDate)) ? date('Y-m-d', strtotime($punch_detail->BookingDate)) : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Dispatch Through:</label>
						<input type="text" name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm" value="<?= (isset($punch_detail->Particular)) ? $punch_detail->Particular : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Delivery Note Date:</label>
						<input type="text" name="Delivery_Note_Date" id="Delivery_Note_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : '' ?>">
					</div>
				</div>
				<div class="row mt-2" style="margin-bottom: 5px;">
					<div class="col-md-3 form-group">
						<label for="">Department:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->department; ?>
                        </small>
						<select name="Department" id="Department" class="form-control form-control-sm">
							<?php foreach ($department_list as $key => $value) { ?>
								<option value="<?= $value['department_id'] ?>" <?php if (isset($punch_detail->DepartmentID)) {
									if ($value['department_id'] == $punch_detail->DepartmentID) {
										echo "selected";
									}
								} ?>><?= $value['department_name'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3 form-group">
						<label for="">Voucher Type/Category:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->voucher_type_category; ?>
                        </small>
						<select name="Category" id="Category" class="form-control form-control-sm">
							<option value="">Select</option>
							<?php foreach ($category_list as $key => $value) { ?>
								<option value="<?= $value['category_name'] ?>" <?php if (isset($punch_detail->Category)) {
									if ($value['category_name'] == $punch_detail->Category) {
										echo "selected";
									}
								} ?>><?= $value['category_name'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3 form-group">
						<label for="">Ledger:</label>
						<select name="Ledger" id="Ledger" class="form-control form-control-sm">
							<option value="">Select</option>
							<?php foreach ($ledger_list as $key => $value) { ?>
								<option value="<?= $value['ledger_name'] ?>" <?php if (isset($punch_detail->Ledger)) {
									if ($value['ledger_name'] == $punch_detail->Ledger) {
										echo "selected";
									}
								} ?>><?= $value['ledger_name'] ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-3 form-group">
						<label for="">File:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->file; ?>
                        </small>
						<select name="File" id="File" class="form-control form-control-sm">
							<option value="">Select</option>
							<?php foreach ($file_list as $key => $value) { ?>
								<option value="<?= $value['file_name'] ?>" <?php if (isset($punch_detail->FileName)) {
									if ($value['file_name'] == $punch_detail->FileName) {
										echo "selected";
									}
								} ?>><?= $value['file_name'] ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-bottom: 5px;">
				<div class="col-md-3 form-group">
						<label for="">Location:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->location; ?>
                        </small>
						<select name="Location" id="Location" class="form-control form-control-sm">
							<option value="">Select</option>
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
						<label for="">LR Number:</label>
						<input type="text" name="LR_Number" id="LR_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->FDRNo)) ? $punch_detail->FDRNo : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">LR Date:</label>
						<input type="text" name="LR_Date" id="LR_Date" class="form-control form-control-sm datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : '' ?>">
					</div>
					<div class="form-group col-md-3">
						<label for="">Cartoon Number:</label>
						<input type="text" name="Cartoon_Number" id="Cartoon_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->RegNo)) ? $punch_detail->RegNo : '' ?>">
					</div>
				</div>

				<div class="row" style="height: 300px; overflow:auto">
					<div class="" style="overflow-x:scroll;">
					  
						<table class="table" style="width:1600px;max-width:1600px;overflow-x:scroll;">
							<thead style="text-align: center;">
							<th style="width: 10px;">#</th>
							<th style="width: 420px;">Particular</th>
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
							<th style="width: 80px;">Cess %</th>
							<th style="width: 120px;">Total Amount</th>
							<th style="width: 30px;"></th>
							</thead>
							
							<tbody id="multi_record">
							
							<tr>
								<td>1</td>
								<td>

									<select name="Particular[]" id="Particular1"
											class="form-control form-select form-select-sm particular">
										<option value="">Select</option>
										<?php

										foreach ($item_list as $key => $value) {
											?>
											<option value="<?= $value['item_name'] ?>"><?= $value['item_name'] ?></option>
										<?php } ?>
									</select>
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
									<input type="text" name="Cess[]" id="Cess1" class="form-control form-control-sm"
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
										   value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : '' ?>">
								</td>
							</tr>

							<tr id="tcs_tr">
								<td colspan="7" style="text-align: right;"><b>TCS %:</b></td>
								<td colspan="2">
									<input type="text" name="TCS" id="TCS" class="form-control form-control-sm"
										   onKeyPress="return isNumberKey(event)" onchange="cal_tax()"
										   value="<?= (isset($punch_detail->TCS)) ? $punch_detail->TCS : '' ?>">
								</td>
							</tr>
							<tr>
								<td colspan="7" style="text-align: right;"><b>Total (₹):</b></td>
								<td colspan="2">
									<input type="text" name="Total" id="Total" class="form-control form-control-sm"
										   readonly
										   value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : '' ?>">
								</td>
							</tr>
							<tr>
								<td colspan="7" style="text-align: right;"><b>Round Off (₹):</b></td>
								<td colspan="5">
									<input type="text" name="Total_Discount" id="Total_Discount"
										   class="form-control form-control-sm d-inline"
										   value="<?= (isset($punch_detail->Total_Discount)) ? $punch_detail->Total_Discount : '' ?>"
										   style="width:100px;">
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
							<td colspan="7" style="text-align: right;"><b>Grand Total (₹):</b></td>
							<td colspan="2">
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
						<textarea name="Remark" id="Remark" cols="10" rows="2"
								  class="form-control form-control-sm"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : '' ?></textarea>
					</div>
				</div>

				<div class="box-footer">
					<button type="reset" class="btn btn-danger">Reset</button>
					<input type="submit" class="btn btn-info pull-right" style="margin-left: 20px;" name="save_as_draft" value="Save as Draft"></input>
					<input type="submit" class="btn btn-success pull-right" name="submit" value="Final Submit"></input>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
	 data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Item/Particular</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="">Item/Particular Name: </label><span class="text-danger">*</span>
							<input type="text" name="item_name" id="item_name" class="form-control">
							<div class="form-group" id="name_error"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="">Item Code:</label>
							<input type="text" name="item_code" id="item_code" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save_btn">Save changes</button>
			</div>

		</div>
	</div>
</div>
<script>


	$(document).ready(function () {
		$("#From").select2();
		$("#To").select2();
		$(document).on("change", "#From", function () {
			var address = $(this).find(':selected').data('address');
			$("#Buyer_Address").val(address);
		});
		$(document).on("change", "#To", function () {
			var address = $(this).find(':selected').data('address');
			$("#Vendor_Address").val(address);
		});
		$(".particular").select2({
			allowClear: true,
			escapeMarkup: function (markup) {
				return markup;
			},
			placeholder: "Select Item/Particular",
			language: {
				noResults: function () {
					//open modal to add new item
					return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>";
				}
			}
		});

		var unit_list;
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

		var item_list;
		getItemList();

		function getItemList() {
			$.ajax({
				type: "POST",
				url: '<?= base_url() ?>master/ItemController/get_item_list',
				dataType: "json",
				success: function (response) {

					if (response.status == 200) {
						var x = '<option value="" selected >Select Item/Particular</option>';
						$.each(response.item_list, function (k, v) {
							x =
								x +
								'<option value="' +
								v.item_name +
								'">' +
								v.item_name +

								"</option>";
						});
					}

					item_list = x;
				},
			});
		}

		$('.datepicker').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
		});


		var Count = 1;

		function toggleLoader(show, tableId) {
  const loader = document.getElementById('loader');
  const loaderText = document.getElementById('loader-text');
  const table = document.getElementById(tableId);

  if (show) {
    
    loader.style.marginTop = '230px'; 

    loader.style.display = 'inline-block';
    loaderText.style.display = 'block';
    table.style.visibility = 'hidden';
  } else {
    
    loader.style.marginTop = '0'; 

    loader.style.display = 'none';
    loaderText.style.display = 'none';
    table.style.visibility = 'visible';
  }
}


		getMultiRecord();

		function getMultiRecord() {
			var Scan_Id = $('#Scan_Id').val();
			toggleLoader(true, 'contnetBody');
			$.ajax({
				url: '<?= base_url() ?>form/Invoice_ctrl/getInvoiceItem',
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
							$("#Particular" + i).val(response.data[i - 1].Particular).trigger('change');
							$("#HSN" + i).val(response.data[i - 1].HSN);
							$("#Qty" + i).val(response.data[i - 1].Qty);
							$("#Unit" + i).val(response.data[i - 1].Unit);
							$("#MRP" + i).val(response.data[i - 1].MRP);
							$("#Discount" + i).val(response.data[i - 1].Discount);
							$("#GST" + i).val(response.data[i - 1].GST);
							$("#SGST" + i).val(response.data[i - 1].SGST);
							$("#IGST" + i).val(response.data[i - 1].IGST);
							$("#Cess" + i).val(response.data[i - 1].Cess);
							$("#Price" + i).val(response.data[i - 1].Price);
							$("#Amount" + i).val(response.data[i - 1].Amount);
							$("#TAmount" + i).val(response.data[i - 1].Total_Amount);
						}
					}
				},
				complete: function () {

					toggleLoader(false, 'contnetBody');
				},
				error: function () {
					alert('Error fetching data.');
					$('#loader').hide();
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
			html += '<select  name="Particular[]" id="Particular' + num + '" class="form-control form-control-sm particular">' + item_list + '</select>';
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
			html += '<input type="text" name="Cess[]" id="Cess' + num + '" class="form-control form-control-sm" onKeyPress="return isNumberKey(event)" onchange="calculate(' + num + ')">';
			html += '</td>';
			html += '<td>';
			html += '<input type="text" name="TAmount[]" id="TAmount' + num + '" class="form-control form-control-sm TAmount" readonly>';
			html += '</td>';

			html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 2px;"><i class="fa fa-minus"></i></button></td>';
			html += '</tr>';
			$('#multi_record').append(html);
			$('.datepicker').datetimepicker({});
			$('.particular').select2({
				allowClear: true,
				escapeMarkup: function (markup) {
					return markup;
				},
				placeholder: "Select Item/Particular",
				language: {
					noResults: function () {
						//open modal to add new item
						return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>";
					}
				}
			});
		}

	});

	function isNumberKey(evt) {
		var charCode = evt.which ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;

		return true;
	}

	//radio button checked event
	$(document).on('change', '.plus_minus', function () {
		var id = $(this).attr('id');
		var Total_Discount = $('#Total_Discount').val();
		var Total = $('#Total').val();
		//  var total_amount_array = Total.split('.');
		//  var total_amount_int = total_amount_array[0];
		if (id == 'plus') {
			var Total = parseFloat($('#Total').val()) + parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		} else {
			var Total = parseFloat($('#Total').val()) - parseFloat(Total_Discount);
			$('#Grand_Total').val(Total);
		}
	});


	function calculate(num) {
		var Qty = $('#Qty' + num).val();
		var MRP = $('#MRP' + num).val();
		var Discount = $('#Discount' + num).val();
		var GST = $('#GST' + num).val();
		var SGST = $('#SGST' + num).val(GST);
		var IGST = $('#IGST' + num).val();
		var Cess = $('#Cess' + num).val();
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
		if (isNaN(Cess) || Cess == '') {
			Cess = 0;
		}
		
		if (parseFloat(Discount) > parseFloat(MRP)) {
        alert('Discount cannot be greater than MRP');
        Discount = 0;
        $('#Discount' + num).val(Discount);
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

		if (Cess > 0) {
			var CessAmount = (Amount * Cess) / 100;
			var TAmount = $('#TAmount' + num).val();
			var FinalAmount = parseFloat(TAmount) + parseFloat(CessAmount);
			$('#TAmount' + num).val(FinalAmount);
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
		var TCS = $('#TCS').val();
		var Sub_Total = $('#Sub_Total').val();


		if (isNaN(TCS) || TCS == '') {
			TCS = 0;
		} else {
			TCS = (TCS / 100) * Sub_Total;
		}


		var Total = (parseFloat(Sub_Total) + parseFloat(TCS));
		$('#Total').val(Total);
		var total_amount = $("#Total").val();
		var total_amount_array = total_amount.split('.');
		var total_amount_int = total_amount_array[0];
		var total_amount_dec = total_amount_array[1];

		//check total amount is decimal or not
		if (total_amount_dec == undefined) {
			total_amount_dec = 0;
		}

		$("#Total_Discount").val('0.' + total_amount_dec);
		var Discount = $('#Total_Discount').val();
		var Grand_Total = Total - Discount;
		$('#Grand_Total').val(Grand_Total);

	}

	function getFile() {
		var Company = $("#From").val();
		$.ajax({
			url: '<?= base_url() ?>Punch/getFileList',
			type: 'POST',
			data: {
				Company: Company
			},
			dataType: 'json',
			success: function (response) {
				$("#File").empty();
				if (response.status == 200) {
					$("#File").append('<option value="">Select File</option>');
					$.each(response.data, function (key, value) {
						$('#File').append('<option value="' + value.file_name + '">' + value.file_name + '</option>');
					});
				} else {
					$("#File").append('<option value="">Select File</option>');
				}
			}
		});
	}

	function getDepartment() {
		var Company = $("#From").val();
		$.ajax({
			url: '<?= base_url() ?>Punch/getDepartmentList',
			type: 'POST',
			data: {
				Company: Company
			},
			dataType: 'json',
			success: function (response) {
				$("#Department").empty();
				if (response.status == 200) {
					$("#Department").append('<option value="">Select Department</option>');
					$.each(response.data, function (key, value) {
						$('#Department').append('<option value="' + value.department_id + '">' + value.department_name + '</option>');
					});
				} else {
					$("#File").append('<option value="">Select File</option>');
				}
			}
		});
	}

	$(document).on('click', "#save_btn", function () {
		var item_name = $("#item_name").val();
		var item_code = $("#item_code").val();

		if (item_name == '' || item_name == null) {
			$("#item_name").focus();
			$("#item_name").css('border-color', 'red');
			return false;
		}
		$.ajax({
			type: 'POST',
			url: '<?= base_url() ?>form/Invoice_ctrl/add_item',
			data: {
				item_name: item_name,
				item_code: item_code
			},

			async: false,
			dataType: 'json',
			beforeSend: function () {
			},
			success: function (response) {
				if (response.status == 200) {
					$(".particular").append('<option value="' + item_name + '">' + item_name + '</option>');
					$("#item_name").val('');
					$("#item_code").val('');
					//modal close
					$("#myModal").modal('hide');
				} else {
					alert(response.msg);
				}
			},

		});
	});

	$("#item_name").on('keyup', function () {
		$("#item_name").css('border-color', '');
	});
</script>
