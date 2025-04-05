<style>
    .radio-inline {
        margin-right: 40px;
    }

    .box-header.with-border {
        border-bottom: 2px solid #3a495e;
        box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
    }

    .d-none {
        display: none;
    }

    .table.table-bordered.dataTable tbody th,
    table.table-bordered.dataTable tbody td {
        border: 1px solid #ddd;
    }

    .table.dataTable thead>tr>th.sorting_asc,
    table.dataTable thead>tr>th.sorting_desc,
    table.dataTable thead>tr>th.sorting,
    table.dataTable thead>tr>td.sorting_asc,
    table.dataTable thead>tr>td.sorting_desc,
    table.dataTable thead>tr>td.sorting {
        border: 1px solid #ddd;
    }
	.loader {
		width: 14px;
		height: 14px;
		border: 3px solid #FFF;
		border-bottom-color: #FF3D00;
		border-radius: 50%;
		display: inline-block;
		box-sizing: border-box;
		animation: rotation 1s linear infinite;
		display: none; /* Hide loader initially */
		margin-left: 10px;
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
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reports Filter (Only Approved Records):</h3>
                        <div class="box-tools" style="margin-top: 7px;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="date" class="filter" id="date" value="date" checked>Date
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="company" class="filter" id="company" value="company" <?= (set_value('company_wise') != '') ? 'checked' : '' ?>>Company
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="vendor" class="filter" id="vendor" value="vendor" <?= (set_value('vendor_wise') != '') ? 'checked' : '' ?>>Vendor
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="location" class="filter" id="location" value="location" <?= (set_value('work_location') != '') ? 'checked' : '' ?>> Location
                            </label>

                            <label class="checkbox-inline">
                                <input type="checkbox" name="ledger" class="filter" id="ledger" value="ledger" <?= (set_value('ledger_wise') != '') ? 'checked' : '' ?>>Ledger
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="document_type" class="filter" id="document_type" value="document_type" <?= (set_value('document_wise') != '') ? 'checked' : '' ?>> Document Type
                            </label>
                        </div>
                    </div>
                    <div class="box-body" id="form_div">
                        <form action="<?= base_url() ?>Search/search_with_filter" method="POST" id="search_form" role="form">
                            <div class="row">
                                <div class="col-sm-2 col-md-2" id="from_dt_div">
                                    <div class="form-group">
                                        <label>Punch From Date :</label>
                                        <input type="date" autocomplete="off" name="from_date" id="from_date" class="form-control" value="<?= set_value('from_date') ?>">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2" id="to_dt_div">
                                    <div class="form-group">
                                        <label>Punch To Date :</label>
                                        <input type="date" autocomplete="off" name="to_date" id="to_date" class="form-control" value="<?= set_value('to_date') ?>">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 <?= (set_value('company_wise') != '') ? '' : 'd-none' ?>" id="company_div">
                                    <div class="form-group">
                                        <label>Company :</label>
                                        <select name="company_wise" id="company_wise" class="form-control" onchange="getDepartment();">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($companylist as $key => $value) { ?>
                                                <option value="<?= $value['firm_id'] ?>" <?php if (set_value('company_wise') == $value['firm_id']) echo 'selected'; ?>> <?= $value['firm_name'] ?> </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 <?= (set_value('vendor_wise') != '') ? '' : 'd-none' ?>" id="vendor_div">
                                    <div class="form-group">
                                        <label>Vendor :</label>
                                        <select name="vendor_wise" id="vendor_wise" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($vendorlist as $key => $value) { ?>
                                                <option value="<?= $value['firm_id'] ?>" <?php if (set_value('vendor_wise') == $value['firm_id']) echo 'selected'; ?>> <?= $value['firm_name'] ?> </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 <?= (set_value('work_location') != '') ? '' : 'd-none' ?>" id="location_div">
                                    <div class="form-group">
                                        <label>Work Location :</label>
                                        <select name="work_location" id="work_location" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($locationlist as $key => $value) { ?>
                                                <option value="<?= $value['location_name'] ?>" <?= (set_value('work_location') == $value['location_name']) ? 'selected' : '' ?>><?= $value['location_name'] ?> </option>;
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-md-3 <?= (set_value('ledger_wise') != '') ? '' : 'd-none' ?>" id="ledger_div">
                                    <div class="form-group">
                                        <label>Ledger :</label>
                                        <select name="ledger_wise" id="ledger_wise" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($ledgerlist as $key => $value) { ?>
                                                <option value="<?= $value['ledger_name'] ?>" <?= (set_value('ledger_wise') == $value['ledger_name']) ? 'selected' : '' ?>><?= $value['ledger_name'] ?> </option>;
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 <?= (set_value('document_wise') != '') ? '' : 'd-none' ?>" id="document_div">
                                    <div class="form-group">
                                        <label>Document Type :</label>
                                        <select name="document_wise" id="document_wise" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($my_doctype_list as $value) { ?>
                                                <option value="<?= $value['type_id'] ?>" <?php if (set_value('document_wise') == $value['type_id']) echo 'selected'; ?>> <?= $value['file_type'] ?> </option>
                                            <?php  }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2">
                                    <div class="form-group" style="margin-top: 22px;">
										<button type="submit" id="search" name="search" value="search" class="btn btn-primary btn-sm checkbox-toggle">
											<i class="fa fa-search"></i> Search
											<span class="loader" style="display: none;"></span>
										</button>
										<button type="button" id="reset" name="reset" onclick="reloadPage();" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-refresh"></i> Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>

					</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th colspan="9" id="filter_type" style="text-align: center;">Search Result</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th style="text-align: center;">Document Name</th>
                                    <th style="text-align: center;">Location</th>
                                    <th style="text-align: center;">Company</th>
                                    <th style="text-align: center;">From</th>
                                    <th style="text-align: center;">To</th>
                                    <th style="text-align: center;">Bill Date</th>
                                    <th style="text-align: center;">PO No</th>
                                    <th style="text-align: center;">PO Date</th>
                                    
                                    <th style="text-align: center;">File No</th>
                                    <th style="text-align: center;">Amount</th>
                                    <th style="text-align: center;">Upload Date</th>
                                    <th style="text-align: center;">Remark</th>
                                </tr>
                            </thead>
							<tbody id="myTable">
							<?php
							if (!empty($result)) {
								$i = 1;
								foreach ($result as $key => $value) {
									echo "<tr>";
									echo "<td>" . $i . "</td>";
									echo "<td style='text-align:left;width:30%'><a href='" . base_url() . "file_detail/" . $value['Scan_Id'] . "/" . $value['DocTypeId'] . "' target='_blank'>" . $value['Document_Name'] . "</a></td>";
									echo "<td style='text-align:left;width:12%'>" . $value['Loc_Name'] . "</td>";
									echo "<td style='text-align:left;width:12%'>" . $value['group_name'] . "</td>";
									echo "<td style='text-align:left;width:12%'>" . $value['FromName'] . "</td>";
									echo "<td style='text-align:left;width:12%'>" . $value['ToName'] . "</td>";

									$billDate = !empty($value['BillDate']) ? date('d-m-Y', strtotime($value['BillDate'])) : '';
									echo "<td style='text-align:left;width:8%'>" . $billDate . "</td>";

									echo "<td style='text-align:left;width:12%'>" . $value['ServiceNo'] . "</td>";

									$bookingDate = !empty($value['BookingDate']) ? date('d-m-Y', strtotime($value['BookingDate'])) : '';
									echo "<td style='text-align:left;width:8%'>" . $bookingDate . "</td>";

									echo "<td style='text-align:left'>" . $value['File_No'] . "</td>";

									if ($value['DocTypeId'] == 23) {
										echo "<td style='text-align:left'>" . $value['Grand_Total'] . "</td>";
									} else {
										echo "<td style='text-align:left'>" . $value['Total_Amount'] . "</td>";
									}

									$createdDate = !empty($value['Created_Date']) ? date('d-m-Y', strtotime($value['Created_Date'])) : '';
									echo "<td style='text-align:left;width:8%'>" . $createdDate . "</td>";

									echo "<td style='text-align:left;width:20%'>" . $value['Remark'] . "</td>";
									echo "</tr>";
									$i++;
								}
							}
							?>
							</tbody>

						</table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $("#company_wise").select2();
        $("#vendor_wise").select2();
        $("#work_location").select2();
        $("#ledger_wise").select2();
        $("#document_wise").select2();

        $("#company").click(function() {
            if ($(this).is(":checked")) {
                $("#company_div").show();
            } else {
                $("#company_div").hide();
            }
        });
        $("#vendor").click(function() {
            if ($(this).is(":checked")) {
                $("#vendor_div").show();
            } else {
                $("#vendor_div").hide();
            }
        });
        $("#date").click(function() {
            if ($(this).is(":checked")) {
                $("#from_dt_div").show();
                $("#to_dt_div").show();
            } else {
                $("#from_dt_div").hide();
                $("#to_dt_div").hide();
            }
        });
        $("#location").click(function() {
            if ($(this).is(":checked")) {
                $("#location_div").show();
            } else {
                $("#location_div").hide();
            }
        });
        $("#ledger").click(function() {
            if ($(this).is(":checked")) {
                $("#ledger_div").show();
            } else {
                $("#ledger_div").hide();
            }
        });
        $("#document_type").click(function() {
            if ($(this).is(":checked")) {
                $("#document_div").show();
            } else {
                $("#document_div").hide();
            }
        });

    });



    function getDepartment() {
        var Company = $("#company_wise").val();
        $.ajax({
            url: '<?= base_url() ?>Punch/getDepartmentList',
            type: 'POST',
            data: {
                Company: Company
            },
            dataType: 'json',
            success: function(response) {
                $("#department_wise").empty();
                if (response.status == 200) {
                    $("#department_wise").append('<option value="">Select Department</option>');
                    $.each(response.data, function(key, value) {
                        $('#department_wise').append('<option value="' + value.department_id + '">' + value.department_name + '</option>');
                    });
                } else {
                    $("#department_wise").append('<option value="">Select Department</option>');
                }
            }
        });
    }

    function reloadPage() {
        window.location.href = "<?php echo base_url(); ?>search_with_filter";
    }
</script>
