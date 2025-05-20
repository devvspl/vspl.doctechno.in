<?php

$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
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
						$this->load->view('vspl_forms/bank_statement');
					} elseif ($DocType_Id == 5) {
						$this->load->view('vspl_forms/boarding_pass');
					} elseif ($DocType_Id == 8) {
						$this->load->view('vspl_forms/certificate');
					} elseif ($DocType_Id == 10) {
						$this->load->view('vspl_forms/company_record');
					} elseif ($DocType_Id == 11) {
						$this->load->view('vspl_forms/confirmation_account');
					} elseif ($DocType_Id == 18) {
						$this->load->view('vspl_forms/id_address_proof');
					} elseif ($DocType_Id == 19) {
						$this->load->view('vspl_forms/import_export_paper');
					} elseif ($DocType_Id == 30) {
						$this->load->view('vspl_forms/mediclaim_history');
					} elseif ($DocType_Id == 31) {
						$this->load->view('vspl_forms/miscellaneous');
					} elseif ($DocType_Id == 32) {
						$this->load->view('vspl_forms/pf_esic');
					} elseif ($DocType_Id == 35) {
						$this->load->view('vspl_forms/property_record');
					} elseif ($DocType_Id == 36) {
						$this->load->view('vspl_forms/reting_credential');
					} elseif ($DocType_Id == 37) {
						$this->load->view('vspl_forms/registration_certificate');
					} elseif ($DocType_Id == 41) {
						$this->load->view('vspl_forms/tax_credit_document');
					} elseif ($DocType_Id == 45) {
						$this->load->view('vspl_forms/vehicle_registration_paper');
					} //--------------------------Accounting Punch-----------------------
					elseif ($DocType_Id == 1) {
						$this->load->view('vspl_forms/two_four_wheeler');
					} elseif ($DocType_Id == 2) {
						$this->load->view('vspl_forms/air_rail_bus');
					} elseif ($DocType_Id == 3) {
						$this->load->view('vspl_forms/bank_loan_paper');
					} elseif ($DocType_Id == 6) {
						$this->load->view('vspl_forms/cash_deposit_withdrawals');
					} elseif ($DocType_Id == 23) {
						$this->load->view('vspl_forms/invoice');
					} elseif ($DocType_Id == 44) {
						$this->load->view('vspl_forms/vehicle_maintenance');
					} elseif ($DocType_Id == 43) {
						$this->load->view('vspl_forms/vehicle_fule');
					} elseif ($DocType_Id == 42) {
						$this->load->view('vspl_forms/telephone_bill');
					} elseif ($DocType_Id == 40) {
						$this->load->view('vspl_forms/subsidy');
					} elseif ($DocType_Id == 39) {
						$this->load->view('vspl_forms/rtgs_neft');
					} elseif ($DocType_Id == 38) {
						$this->load->view('vspl_forms/rst_ofd');
					} elseif ($DocType_Id == 34) {
						$this->load->view('vspl_forms/postage_courier');
					} elseif ($DocType_Id == 33) {
						$this->load->view('vspl_forms/phone_fax');
					} elseif ($DocType_Id == 29) {
						$this->load->view('vspl_forms/meals');
					} elseif ($DocType_Id == 28) {
						$this->load->view('vspl_forms/lodging');
					} elseif ($DocType_Id == 27) {
						$this->load->view('vspl_forms/local_conveyance');
					} elseif ($DocType_Id == 26) {
						$this->load->view('vspl_forms/lease_rent');
					} elseif ($DocType_Id == 25) {
						$this->load->view('vspl_forms/jeep_campaign');
					} elseif ($DocType_Id == 24) {
						$this->load->view('vspl_forms/it_return');
					} elseif ($DocType_Id == 22) {
						$this->load->view('vspl_forms/insurance_policy');
					} elseif ($DocType_Id == 21) {
						$this->load->view('vspl_forms/insurance_document');
					} elseif ($DocType_Id == 20) {
						$this->load->view('vspl_forms/income_taxt_tds');
					} elseif ($DocType_Id == 17) {
						$this->load->view('vspl_forms/hired_vehicle');
					} elseif ($DocType_Id == 16) {
						$this->load->view('vspl_forms/challan');
					} elseif ($DocType_Id == 15) {
						$this->load->view('vspl_forms/fixed_deposit_receipt');
					} elseif ($DocType_Id == 14) {
						$this->load->view('vspl_forms/fd_fv');
					} elseif ($DocType_Id == 13) {
						$this->load->view('vspl_forms/electricity_bill');
					} elseif ($DocType_Id == 12) {
						$this->load->view('vspl_forms/dealer_meeting');
					} elseif ($DocType_Id == 9) {
						$this->load->view('vspl_forms/cheque');
					} elseif ($DocType_Id == 7) {
						$this->load->view('vspl_forms/cash_voucher');
					} elseif ($DocType_Id == 46) {
						$this->load->view('vspl_forms/gst_challan');
					} elseif ($DocType_Id == 47) {
						$this->load->view('vspl_forms/labour_payment');
					} elseif ($DocType_Id == 48) {
						$this->load->view('vspl_forms/cash_receipt');
					} elseif ($DocType_Id == 49) {
						$this->load->view('vspl_forms/fixed_asset');
					} elseif ($DocType_Id == 50) {
						$this->load->view('vspl_forms/machine_operation');
					} elseif ($DocType_Id == 51) {
						$this->load->view('vspl_forms/air');
					} elseif ($DocType_Id == 52) {
						$this->load->view('vspl_forms/rail');
					} elseif ($DocType_Id == 53) {
						$this->load->view('vspl_forms/bus');
					}elseif ($DocType_Id == 54) {
						$this->load->view('vspl_forms/sale_bill');
					}elseif ($DocType_Id == 55) {
						$this->load->view('vspl_forms/ticket_cancellation');
					}elseif ($DocType_Id == 56) {
						$this->load->view('vspl_forms/credit_note');
					}
					elseif ($DocType_Id == 57) {
						$this->load->view('vspl_forms/journal_entry');
					}
					elseif ($DocType_Id == 58) {
						$this->load->view('vspl_forms/cash_payment_new');
					}
					?>
				</div>
			</div>
		</div>
	</section>
</div>
