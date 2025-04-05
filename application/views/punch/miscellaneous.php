<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$vendor_list = $this->db->get_where('master_firm', ['status' => 'A'])->result_array();
$punch_detail = $this->db->get_where('punchfile2', ['Scan_Id' => $Scan_Id])->row();
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
		<form action="<?= base_url(); ?>form/Miscellaneous_ctrl/create" id="punch_form" name="punch_form" method="post"
			  accept-charset="utf-8">
			<div class="col-md-6">
				<input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
				<input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
				<div class="row">
					<div class="col-md-4 form-group ">
						<label for="">Company:</label><span class="text-danger">*</span>
						
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->company; ?>
                        </small>
						<select name="Company" id="Company" class="form-control" required
								data-parsley-errors-container="#companyError">
							<option value="">Select</option>
							<?php
							foreach ($company_list as $key => $value) {
								$selected = '';
								if (isset($punch_detail->CompanyID) && $punch_detail->CompanyID == $value['firm_id']) {
									$selected = 'selected';
								}
								echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
							}
							?>
						</select>
						<div id="companyError"></div>
					</div>
					<div class="col-md-4 form-group ">
						<label for="">Voucher No:</label><span class="text-danger">*</span>
						<input type="text" name="VoucherNo" id="VoucherNo" class="form-control"
							   value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : '' ?>" required>
					</div>
					<div class="col-md-4 form-group ">
						<label for="">Voucher Date:</label><span class="text-danger">*</span>
						<input type="text" name="Voucher_Date" id="Voucher_Date" class="form-control datepicker"
							   value="<?= (isset($punch_detail->RegPurDate)) ? date('Y-m-d', strtotime($punch_detail->RegPurDate)) : '' ?>"
							   required>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 form-group">
						<label for="">Location:</label>
						 <small class="text-danger">
                            <?php echo $temp_punch_detail->location; ?>
                        </small>
						<select name="Location" id="Location" class="form-control form-control-sm" required
								data-parsley-errors-container="#LocationError">
							<option value="">Select</option>
							<?php foreach ($worklocation_list as $key => $value) { ?>
								<option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Location)) {
									if ($value['location_name'] == $punch_detail->Location) {
										echo "selected";
									}
								}  ?>><?= $value['location_name'] ?></option>
							<?php } ?>
						</select>
						<div id="LocationError"></div>
					</div>

					<div class="col-md-6 form-group ">
						<label for="">Venodr:</label><span class="text-danger">*</span>
						 <small class="text-danger">
                            <?php  echo $temp_punch_detail->vendor; ?>
                        </small>
						<select name="Vendor" id="Vendor" class="form-control" 
								data-parsley-errors-container="#vendorError">
							<option value="">Select</option>
							<?php
							foreach ($vendor_list as $key => $value) {
								$selected = '';
								if (isset($punch_detail->VendorID) && $punch_detail->VendorID == $value['firm_id']) {
									$selected = 'selected';
								}
								echo '<option value="' . $value['firm_id'] . '" ' . $selected . ' data-address="' . $value['address'] . '">' . $value['firm_name'] . '</option>';
							}
							?>
						</select>
						<div id="vendorError"></div>
					</div>
					<div class="col-md-2 form-group">
						<label for="">Amount:</label><span class="text-danger">*</span>
						<input type="text" name="Amount" id="Amount" class="form-control"
							   value="<?= (isset($punch_detail->TotalAmount)) ? $punch_detail->TotalAmount : '' ?>"
							   required>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label for="">Particular:</label><span class="text-danger">*</span>
						<input type="text" name="Particular" id="Particular" class="form-control"
							   value="<?= (isset($punch_detail->Additional_Exposure)) ? $punch_detail->Additional_Exposure : '' ?>"
							   required>
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
	$(document).ready(function () {
		$("#Company").select2();
		$("#Vendor").select2();
		$("#Location").select2();
		$(".datepicker").datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			input: false
		});
	});
</script>
