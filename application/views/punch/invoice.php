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
		
			  <div style="display: flex; flex-direction: column; align-items: center;">
				<div class="loader" id="loader" style="display: none;"></div>
				<span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #3a495e;">Please Wait...</span>
				</div>

			<div class="col-md-7" id="contnetBody">

			<form action="<?= base_url(); ?>form/InvoiceController/create" id="invoice_form" name="invoice_form" method="post"
			  accept-charset="utf-8">
		    	
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
						<label for="">Voucher Type:</label>
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
				</form>
			</div>
		
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
		// Initialize Select2 and Datepickers
		initUI();

		let unitList = '';
		let itemList = '';
		let count = 1;

		// Load unit and item lists
		loadUnitList();
		loadItemList();

		// Load data for edit/view
		getMultiRecord();

		// Event bindings
		$(document).on("change", "#From", updateBuyerAddress);
		$(document).on("change", "#To", updateVendorAddress);
		$(document).on("click", "#add", addItemRow);
		$(document).on("click", "#remove", removeItemRow);
		$(document).on("change", ".plus_minus", calculatePlusMinus);

		function initUI() {
			$("#From, #To").select2();
			$(".datepicker").datetimepicker({ timepicker: false, format: 'Y-m-d' });
			$(".particular").select2({
				allowClear: true,
				escapeMarkup: m => m,
				placeholder: "Select Item/Particular",
				language: {
					noResults: () => "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>"
				}
			});
		}

		function updateBuyerAddress() {
			const address = $(this).find(':selected').data('address');
			$("#Buyer_Address").val(address);
		}

		function updateVendorAddress() {
			const address = $(this).find(':selected').data('address');
			$("#Vendor_Address").val(address);
		}

		function toggleLoader(show, tableId) {
			const loader = $('#loader');
			const loaderText = $('#loader-text');
			const table = $('#' + tableId);

			loader.css('marginTop', show ? '230px' : '0');
			loader.toggle(show);
			loaderText.toggle(show);
			table.css('visibility', show ? 'hidden' : 'visible');
		}

		function loadUnitList() {
			$.post('<?= base_url() ?>master/UnitController/get_unit_list', response => {
				if (response.status === 200) {
					unitList = `<option value="">Select Unit</option>` + response.unit_list.map(v =>
						`<option value="${v.unit_id}">${v.unit_name}</option>`
					).join('');
				}
			}, 'json');
		}

		function loadItemList() {
			$.post('<?= base_url() ?>master/ItemController/get_item_list', response => {
				if (response.status === 200) {
					itemList = `<option value="">Select Item/Particular</option>` + response.item_list.map(v =>
						`<option value="${v.item_name}">${v.item_name}</option>`
					).join('');
				}
			}, 'json');
		}

		function getMultiRecord() {
			const scanId = $('#Scan_Id').val();
			toggleLoader(true, 'contnetBody');

			$.post('<?= base_url() ?>form/InvoiceController/getInvoiceItem', { Scan_Id: scanId }, response => {
				if (response.status === 200) {
					count = response.data.length;
					response.data.forEach((item, index) => {
						if (index > 0) addItemRow();
						populateRow(index + 1, item);
					});
				}
			}, 'json').always(() => toggleLoader(false, 'contnetBody'))
			  .fail(() => alert('Error fetching data.'));
		}

		function populateRow(index, item) {
			$(`#Particular${index}`).val(item.Particular).trigger('change');
			$(`#HSN${index}`).val(item.HSN);
			$(`#Qty${index}`).val(item.Qty);
			$(`#Unit${index}`).val(item.Unit);
			$(`#MRP${index}`).val(item.MRP);
			$(`#Discount${index}`).val(item.Discount);
			$(`#GST${index}`).val(item.GST);
			$(`#SGST${index}`).val(item.SGST);
			$(`#IGST${index}`).val(item.IGST);
			$(`#Cess${index}`).val(item.Cess);
			$(`#Price${index}`).val(item.Price);
			$(`#Amount${index}`).val(item.Amount);
			$(`#TAmount${index}`).val(item.Total_Amount);
		}

		function addItemRow() {
			count++;
			let html = `
				<tr>
					<td>${count}</td>
					<td><select name="Particular[]" id="Particular${count}" class="form-control form-control-sm particular">${itemList}</select></td>
					<td><input type="text" name="HSN[]" id="HSN${count}" class="form-control form-control-sm"></td>
					<td><input type="text" name="Qty[]" id="Qty${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><select name="Unit[]" id="Unit${count}" class="form-control form-control-sm">${unitList}</select></td>
					<td><input type="text" name="MRP[]" id="MRP${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><input type="text" name="Discount[]" id="Discount${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><input type="text" name="Price[]" id="Price${count}" class="form-control form-control-sm" readonly></td>
					<td><input type="text" name="Amount[]" id="Amount${count}" class="form-control form-control-sm Amount" readonly></td>
					<td><input type="text" name="GST[]" id="GST${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><input type="text" name="SGST[]" id="SGST${count}" class="form-control form-control-sm" readonly></td>
					<td><input type="text" name="IGST[]" id="IGST${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><input type="text" name="Cess[]" id="Cess${count}" class="form-control form-control-sm" onkeypress="return isNumberKey(event)" onchange="calculate(${count})"></td>
					<td><input type="text" name="TAmount[]" id="TAmount${count}" class="form-control form-control-sm TAmount" readonly></td>
					<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button></td>
				</tr>`;
			$('#multi_record').append(html);
			initUI(); // rebind select2 etc.
		}

		function removeItemRow() {
			$(this).closest('tr').remove();
			calculate(count);
		}

		function isNumberKey(evt) {
			const charCode = evt.which || evt.keyCode;
			return !(charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57));
		}

		function calculate(num) {
			let qty = parseFloat($('#Qty' + num).val()) || 0;
			let mrp = parseFloat($('#MRP' + num).val()) || 0;
			let discount = parseFloat($('#Discount' + num).val()) || 0;
			let gst = parseFloat($('#GST' + num).val()) || 0;
			let igst = parseFloat($('#IGST' + num).val()) || 0;
			let cess = parseFloat($('#Cess' + num).val()) || 0;

			if (discount > mrp) {
				alert('Discount cannot be greater than MRP');
				discount = 0;
				$('#Discount' + num).val(discount);
			}

			let price = mrp - discount;
			let amount = qty * price;
			let total = amount;

			if (gst > 0) {
				$('#IGST' + num).attr('readonly', true);
				$('#SGST' + num).val(gst);
				total += (amount * gst * 2) / 100;
			} else {
				$('#IGST' + num).attr('readonly', false);
				total += (amount * igst) / 100;
			}

			if (cess > 0) {
				total += (amount * cess) / 100;
			}

			$('#Price' + num).val(price.toFixed(2));
			$('#Amount' + num).val(amount.toFixed(2));
			$('#TAmount' + num).val(total.toFixed(2));

			let subtotal = 0;
			$('.TAmount').each(function () {
				subtotal += parseFloat($(this).val()) || 0;
			});

			const decimal = (subtotal % 1).toFixed(2).split('.')[1] || '00';
			$('#Sub_Total').val(subtotal.toFixed(2));
			$('#Total').val(subtotal.toFixed(2));
			$('#Total_Discount').val('0.' + decimal);
			$('#Grand_Total').val(Math.floor(subtotal));
		}

		function calculatePlusMinus() {
			const discount = parseFloat($('#Total_Discount').val()) || 0;
			let total = parseFloat($('#Total').val()) || 0;
			if ($(this).attr('id') === 'plus') {
				total += discount;
			} else {
				total -= discount;
			}
			$('#Grand_Total').val(total.toFixed(2));
		}

		window.cal_tax = function () {
			let tcs = parseFloat($('#TCS').val()) || 0;
			let subTotal = parseFloat($('#Sub_Total').val()) || 0;
			let tcsAmount = (tcs / 100) * subTotal;
			let total = subTotal + tcsAmount;

			const decimal = (total % 1).toFixed(2).split('.')[1] || '00';
			$('#Total').val(total.toFixed(2));
			$('#Total_Discount').val('0.' + decimal);
			$('#Grand_Total').val((total - parseFloat('0.' + decimal)).toFixed(2));
		}
	});
</script>
