<?php

$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
?>
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
   .tabs-container{margin-bottom: 10px;}
   .d-none {
   display: none !important;
   }
   .tab-content {
   display: none;
   }
   .active {
   display: block; 
   }
   #rows_container .form-row:nth-child(odd) {
   background-color: #f0f0f0; 
   }
   #rows_container .form-row:nth-child(even) {
   background-color: #d0d0d0; 
   }
   .tabs {
   cursor: pointer;
   padding: 10px;
   display: inline-block;
   background-color: #1b98aea6;
   border: 1px solid #ccc;
   color: #fff;
   }
   .tabs.active-tab {
   background-color: #1b98ae; 
   }
   .ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
    padding: 5px;
}
.ui-widget.ui-widget-content li{
	margin-bottom: 5px;
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Punch File - <?php echo $this->customlib->getDocType($doc_type_id); ?>
							<?php
							$document_name = $this->customlib->getDocumentName($scan_id);
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
					if ($doc_type_id == 4) {
						$this->load->view('vspl_forms/bank_statement');
					} elseif ($doc_type_id == 5) {
						$this->load->view('vspl_forms/boarding_pass');
					} elseif ($doc_type_id == 8) {
						$this->load->view('vspl_forms/certificate');
					} elseif ($doc_type_id == 10) {
						$this->load->view('vspl_forms/company_record');
					} elseif ($doc_type_id == 11) {
						$this->load->view('vspl_forms/confirmation_account');
					} elseif ($doc_type_id == 18) {
						$this->load->view('vspl_forms/id_address_proof');
					} elseif ($doc_type_id == 19) {
						$this->load->view('vspl_forms/import_export_paper');
					} elseif ($doc_type_id == 30) {
						$this->load->view('vspl_forms/mediclaim_history');
					} elseif ($doc_type_id == 31) {
						$this->load->view('vspl_forms/miscellaneous');
					} elseif ($doc_type_id == 32) {
						$this->load->view('vspl_forms/pf_esic');
					} elseif ($doc_type_id == 35) {
						$this->load->view('vspl_forms/property_record');
					} elseif ($doc_type_id == 36) {
						$this->load->view('vspl_forms/reting_credential');
					} elseif ($doc_type_id == 37) {
						$this->load->view('vspl_forms/registration_certificate');
					} elseif ($doc_type_id == 41) {
						$this->load->view('vspl_forms/tax_credit_document');
					} elseif ($doc_type_id == 45) {
						$this->load->view('vspl_forms/vehicle_registration_paper');
					} //--------------------------Accounting Punch-----------------------
					elseif ($doc_type_id == 1) {
						$this->load->view('vspl_forms/two_four_wheeler');
					} elseif ($doc_type_id == 2) {
						$this->load->view('vspl_forms/air_rail_bus');
					} elseif ($doc_type_id == 3) {
						$this->load->view('vspl_forms/bank_loan_paper');
					} elseif ($doc_type_id == 6) {
						$this->load->view('vspl_forms/cash_deposit_withdrawals');
					} elseif ($doc_type_id == 23) {
						$this->load->view('vspl_forms/invoice');
					} elseif ($doc_type_id == 44) {
						$this->load->view('vspl_forms/vehicle_maintenance');
					} elseif ($doc_type_id == 43) {
						$this->load->view('vspl_forms/vehicle_fule');
					} elseif ($doc_type_id == 42) {
						$this->load->view('vspl_forms/telephone_bill');
					} elseif ($doc_type_id == 40) {
						$this->load->view('vspl_forms/subsidy');
					} elseif ($doc_type_id == 39) {
						$this->load->view('vspl_forms/rtgs_neft');
					} elseif ($doc_type_id == 38) {
						$this->load->view('vspl_forms/rst_ofd');
					} elseif ($doc_type_id == 34) {
						$this->load->view('vspl_forms/postage_courier');
					} elseif ($doc_type_id == 33) {
						$this->load->view('vspl_forms/phone_fax');
					} elseif ($doc_type_id == 29) {
						$this->load->view('vspl_forms/meals');
					} elseif ($doc_type_id == 28) {
						$this->load->view('vspl_forms/lodging');
					} elseif ($doc_type_id == 27) {
						$this->load->view('vspl_forms/local_conveyance');
					} elseif ($doc_type_id == 26) {
						$this->load->view('vspl_forms/lease_rent');
					} elseif ($doc_type_id == 25) {
						$this->load->view('vspl_forms/jeep_campaign');
					} elseif ($doc_type_id == 24) {
						$this->load->view('vspl_forms/it_return');
					} elseif ($doc_type_id == 22) {
						$this->load->view('vspl_forms/insurance_policy');
					} elseif ($doc_type_id == 21) {
						$this->load->view('vspl_forms/insurance_document');
					} elseif ($doc_type_id == 20) {
						$this->load->view('vspl_forms/income_taxt_tds');
					} elseif ($doc_type_id == 17) {
						$this->load->view('vspl_forms/hired_vehicle');
					} elseif ($doc_type_id == 16) {
						$this->load->view('vspl_forms/challan');
					} elseif ($doc_type_id == 15) {
						$this->load->view('vspl_forms/fixed_deposit_receipt');
					} elseif ($doc_type_id == 14) {
						$this->load->view('vspl_forms/fd_fv');
					} elseif ($doc_type_id == 13) {
						$this->load->view('vspl_forms/electricity_bill');
					} elseif ($doc_type_id == 12) {
						$this->load->view('vspl_forms/dealer_meeting');
					} elseif ($doc_type_id == 9) {
						$this->load->view('vspl_forms/cheque');
					} elseif ($doc_type_id == 7) {
						$this->load->view('vspl_forms/cash_voucher');
					} elseif ($doc_type_id == 46) {
						$this->load->view('vspl_forms/gst_challan');
					} elseif ($doc_type_id == 47) {
						$this->load->view('vspl_forms/labour_payment');
					} elseif ($doc_type_id == 48) {
						$this->load->view('vspl_forms/cash_receipt');
					} elseif ($doc_type_id == 49) {
						$this->load->view('vspl_forms/fixed_asset');
					} elseif ($doc_type_id == 50) {
						$this->load->view('vspl_forms/machine_operation');
					} elseif ($doc_type_id == 51) {
						$this->load->view('vspl_forms/air');
					} elseif ($doc_type_id == 52) {
						$this->load->view('vspl_forms/rail');
					} elseif ($doc_type_id == 53) {
						$this->load->view('vspl_forms/bus');
					}elseif ($doc_type_id == 54) {
						$this->load->view('vspl_forms/sale_bill');
					}elseif ($doc_type_id == 55) {
						$this->load->view('vspl_forms/ticket_cancellation');
					}elseif ($doc_type_id == 56) {
						$this->load->view('vspl_forms/credit_note');
					}
					elseif ($doc_type_id == 57) {
						$this->load->view('vspl_forms/journal_entry');
					}
					elseif ($doc_type_id == 58) {
						$this->load->view('vspl_forms/cash_payment_new');
					}
					?>
				</div>
			</div>
		</div>
	</section>
</div>
