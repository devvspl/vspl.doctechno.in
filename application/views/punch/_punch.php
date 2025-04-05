<?php

$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);


?>
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Punch File - <?php echo $this->customlib->getDocType($DocType_Id); ?>
							<?php
							$document_name = $this->customlib->getDocumentName($Scan_Id);
							if (!empty($document_name) || $document_name != null) {
								echo " - (" . $document_name . ")";
							}
							?>
						</h3>
						<div class="box-tools pull-right">
							<a href="<?= base_url(); ?>punch" class="btn btn-primary btn-sm"><i
										class="fa fa-long-arrow-left"></i> Back</a>
						</div>
					</div>
					<?php

					//--------------------------Normal Punch-----------------------
					if ($DocType_Id == 4) {
						$this->load->view('punch/bank_statement');
					} elseif ($DocType_Id == 5) {
						$this->load->view('punch/boarding_pass');
					} elseif ($DocType_Id == 8) {
						$this->load->view('punch/certificate');
					} elseif ($DocType_Id == 10) {
						$this->load->view('punch/company_record');
					} elseif ($DocType_Id == 11) {
						$this->load->view('punch/confirmation_account');
					} elseif ($DocType_Id == 18) {
						$this->load->view('punch/id_address_proof');
					} elseif ($DocType_Id == 19) {
						$this->load->view('punch/import_export_paper');
					} elseif ($DocType_Id == 30) {
						$this->load->view('punch/mediclaim_history');
					} elseif ($DocType_Id == 31) {
						$this->load->view('punch/miscellaneous');
					} elseif ($DocType_Id == 32) {
						$this->load->view('punch/pf_esic');
					} elseif ($DocType_Id == 35) {
						$this->load->view('punch/property_record');
					} elseif ($DocType_Id == 36) {
						$this->load->view('punch/reting_credential');
					} elseif ($DocType_Id == 37) {
						$this->load->view('punch/registration_certificate');
					} elseif ($DocType_Id == 41) {
						$this->load->view('punch/tax_credit_document');
					} elseif ($DocType_Id == 45) {
						$this->load->view('punch/vehicle_registration_paper');
					} //--------------------------Accounting Punch-----------------------
					elseif ($DocType_Id == 1) {
						$this->load->view('punch/two_four_wheeler');
					} elseif ($DocType_Id == 2) {
						$this->load->view('punch/air_rail_bus');
					} elseif ($DocType_Id == 3) {
						$this->load->view('punch/bank_loan_paper');
					} elseif ($DocType_Id == 6) {
						$this->load->view('punch/cash_deposit_withdrawals');
					} elseif ($DocType_Id == 23) {
						$this->load->view('punch/invoice');
					} elseif ($DocType_Id == 44) {
						$this->load->view('punch/vehicle_maintenance');
					} elseif ($DocType_Id == 43) {
						$this->load->view('punch/vehicle_fule');
					} elseif ($DocType_Id == 42) {
						$this->load->view('punch/telephone_bill');
					} elseif ($DocType_Id == 40) {
						$this->load->view('punch/subsidy');
					} elseif ($DocType_Id == 39) {
						$this->load->view('punch/rtgs_neft');
					} elseif ($DocType_Id == 38) {
						$this->load->view('punch/rst_ofd');
					} elseif ($DocType_Id == 34) {
						$this->load->view('punch/postage_courier');
					} elseif ($DocType_Id == 33) {
						$this->load->view('punch/phone_fax');
					} elseif ($DocType_Id == 29) {
						$this->load->view('punch/meals');
					} elseif ($DocType_Id == 28) {
						$this->load->view('punch/lodging');
					} elseif ($DocType_Id == 27) {
						$this->load->view('punch/local_conveyance');
					} elseif ($DocType_Id == 26) {
						$this->load->view('punch/lease_rent');
					} elseif ($DocType_Id == 25) {
						$this->load->view('punch/jeep_campaign');
					} elseif ($DocType_Id == 24) {
						$this->load->view('punch/it_return');
					} elseif ($DocType_Id == 22) {
						$this->load->view('punch/insurance_policy');
					} elseif ($DocType_Id == 21) {
						$this->load->view('punch/insurance_document');
					} elseif ($DocType_Id == 20) {
						$this->load->view('punch/income_taxt_tds');
					} elseif ($DocType_Id == 17) {
						$this->load->view('punch/hired_vehicle');
					} elseif ($DocType_Id == 16) {
						$this->load->view('punch/challan');
					} elseif ($DocType_Id == 15) {
						$this->load->view('punch/fixed_deposit_receipt');
					} elseif ($DocType_Id == 14) {
						$this->load->view('punch/fd_fv');
					} elseif ($DocType_Id == 13) {
						$this->load->view('punch/electricity_bill');
					} elseif ($DocType_Id == 12) {
						$this->load->view('punch/dealer_meeting');
					} elseif ($DocType_Id == 9) {
						$this->load->view('punch/cheque');
					} elseif ($DocType_Id == 7) {
						$this->load->view('punch/cash_voucher');
					} elseif ($DocType_Id == 46) {
						$this->load->view('punch/gst_challan');
					} elseif ($DocType_Id == 47) {
						$this->load->view('punch/labour_payment');
					} elseif ($DocType_Id == 48) {
						$this->load->view('punch/cash_receipt');
					} elseif ($DocType_Id == 49) {
						$this->load->view('punch/fixed_asset');
					} elseif ($DocType_Id == 50) {
						$this->load->view('punch/machine_operation');
					} elseif ($DocType_Id == 51) {
						$this->load->view('punch/air');
					} elseif ($DocType_Id == 52) {
						$this->load->view('punch/rail');
					} elseif ($DocType_Id == 53) {
						$this->load->view('punch/bus');
					}elseif ($DocType_Id == 54) {
						$this->load->view('punch/sale_bill');
					}elseif ($DocType_Id == 55) {
						$this->load->view('punch/ticket_cancellation');
					}elseif ($DocType_Id == 56) {
						$this->load->view('punch/credit_note');
					}
					elseif ($DocType_Id == 57) {
						$this->load->view('punch/journal_entry');
					}
					elseif ($DocType_Id == 58) {
						$this->load->view('punch/cash_payment_new');
					}
					?>
				</div>
			</div>
		</div>
	</section>
</div>
