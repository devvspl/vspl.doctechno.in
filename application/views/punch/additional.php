<div id="additional-info" class="tab-content">
      <form action="<?= base_url(); ?>form/VSPL_cash_voucher_ctrl/create" id="cash_voucher_form" name="cash_voucher_form" method="post">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row" style="background-color: #fff;">
            <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
               <label for="">Document No</label>
               <input type="text" name="document_number" id="document_number" class="form-control" readonly value="<?= $document_number ?>" >
            </div>
            <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
               <label for="">Date</label>
               <input type="text" name="finance_pucnh_date" id="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>" >
            </div>
            <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
               <label for="">Business Entity</label>
               <select  name="business_entity_id" id="business_entity_id" class="form-control">
                  <option value="">Select</option>
               </select>
            </div>
            <div class="form-group col-md-12" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">
               <label for="">Narration</label>
               <textarea name="narration" id="narration" placeholder="Enter transaction details"  class="form-control" ><?php echo isset($punch_detail->narration) ? $punch_detail->narration : $punch_detail->Remark; ?></textarea>
            </div>
            <div class="form-group col-md-12 tds-applicable-group" style="display: flex; gap: 15px;background-color: #ffffff;     margin-bottom: 0;
               padding-bottom: 5px;">
               <label for="tdsApplicable">TDS Applicable</label>
               <div class="form-check">
                  <input 
                     class="form-check-input" 
                     type="radio" 
                     name="tdsApplicable" 
                     id="tdsApplicableYes" 
                     value="yes"
                     
                     >
                  <label class="form-check-label" for="tdsApplicableYes">Yes</label>
               </div>
               <div class="form-check">
                  <input 
                     class="form-check-input" 
                     type="radio" 
                     name="tdsApplicable" 
                     id="tdsApplicableNo" 
                     value="no"
                   
                     >
                  <label class="form-check-label" for="tdsApplicableNo">No</label>
               </div>
            </div>
            <div id="tdsDetailsForm" class="tds-details-form" style="display: none; margin-top: 15px;">
               <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="tdsJvNo">TDS JV No</label>
                  <input   type="text" id="tdsJvNo" name="TDS_JV_no" class="form-control" readonly value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>">
               </div>
               <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="tdsJvNo">TDS Section</label>
                  <?php
                     $selectedTdsSection = isset($punch_detail->TDS_section) ? $punch_detail->TDS_section : '';
                     ?>
                  <select id="tdsSection" name="TDS_section" class="form-control">
                     <option value="">Select Section</option>
                  </select>
               </div>
               <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="tdsPercentage">TDS Percentage</label>
                  <input type="text" value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>" id="tdsPercentage" name="TDS_percentage" class="form-control" readonly placeholder="Enter TDS Percentage">
               </div>
               <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                  <label for="tdsAmount">TDS Amount</label>
                  <input   type="text" id="tdsAmount" value="<?php echo isset($punch_detail->TDS_amount) ? $punch_detail->TDS_amount : ''; ?>" name="TDS_amount" class="form-control" readonly>
               </div>
            </div>
            <div id="rows_container">
               <div class="row form-row" id="row_1" style="    padding: 5px;">
                  <div class="form-group col-md-4">
                     <label for="cost_center">Cost Center</label>
                     <select  name="cost_center_id[]" id="cost_center" class="form-control">
                        <option value="">Select Cost Center</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="department">Department</label>
                     <select  name="DepartmentID[]" id="department" class="form-control">
                        <option value="">Select Department</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="business_unit">Business Unit</label>
                     <select  name="business_unit_id[]" id="business_unit" class="form-control">
                        <option value="">Select Business Unit</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="region">Region</label>
                     <select name="region_id[]" id="region_id" class="form-control region_id">
                        <option value="">Select Region</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="state">State</label>
                     <select  name="state_id[]" id="state" class="form-control  state_select">
                        <option value="">Select State</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="location">Location</label>
                     <select  name="location_id[]" id="location" class="form-control">
                        <option value="">Select Location</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="category">Category</label>
                     <select  name="category_id[]" class="form-control category_select" onchange="fetchCrops(this.value, this)">
                        <option value="">Select Category</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="crop">Crop</label>
                     <select name="crop_id[]" id="crop_id" class="form-control crop_id">
                        <option value="">Select Crop</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="activity">Activity</label>
                     <select  name="activity_id[]" id="activity" class="form-control">
                        <option value="">Select Activity</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="debit_ac">Debit A/C</label>
                     <input type="text" name="debit_ac[]" class="form-control debit-ac" placeholder="Type to search Debit A/C">
                     <div class="custom-dropdown debit-ac-options" style="display:none; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; position: absolute; background: white; z-index: 1000;"></div>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="payment_method">Payment Method</label>
                     <select name="payment_method[]" id="payment_method" class="form-control payment_method">
                        <option value="">Select Payment Method</option>
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label for="amount">Amount</label>
                     <input  type="number" placeholder="0.00" name="Item_Total_Amount[]" class="form-control amount" />
                  </div>
                  <div class="form-group col-md-4">
                     <label for="reference">Reference</label>
                     <input type="text" placeholder="Enter Reference" name="Item_ReferenceNo[]" id="reference" class="form-control"  />
                  </div>
                  <div class="form-group col-md-4">
                     <label for="remark">Remark</label>
                     <input type="text" placeholder="Enter Remark" name="Item_Remark[]" id="remark" class="form-control" value="" />
                  </div>
                  <div class="form-group col-md-4">
                     <label for="amount">TDS Amount</label>
                     <input  type="number" placeholder="0.00" name="Item_TDS_Amount[]" class="form-control" />
                  </div>
                  <div class="form-group col-md-4">
                     <button type="button" style="margin-top: 20px;" class="btn btn-danger btn-sm remove_row">Remove</button>
                  </div>
               </div>
            </div>
            <div class="form-group col-md-12" style="margin-top: 5px;">
               <button type="button" class="btn btn-success" id="add_row">Add Row</button>
               <label style="float: right;">Total: <input  type="number" name="finance_total_Amount" readonly id="billAmount" value="<?php echo isset($punch_detail->finance_total_Amount) ? $punch_detail->finance_total_Amount : ''; ?>" class="form-control" /></label>
            </div>
         </div>
         <div class="box-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <input type="submit" class="btn btn-info pull-right" style="margin-left: 20px;" id="f_save_as_draft" name="f_save_as_draft" value="Save as Draft"></input>
            <input   type="submit" class="btn btn-success pull-right" name="submit" id="finalSubmitBtn" value="Final Submit"></input>
         </div>
      </form>
</div>
<script>
  $(document).ready(function () {
    let rowCount = 1;

    function fetchAccountOptions(inputType, query) {
        return $.ajax({
            url: '<?php echo base_url("journal-entry/get-account-options");?>',
            type: "GET",
            data: {
                type: inputType,
                query: query,
            },
            dataType: "json",
        });
    }

    function initializeAutocomplete(rowId) {
        $(`#${rowId} .debit-ac`).autocomplete({
            source: function (request, response) {
                fetchAccountOptions("debit", request.term).done(function (data) {
                    if (Array.isArray(data)) {
                        response(
                            data.map((account) => {
                                return {
                                    label: `${account.account_name} (${account.account_code})`,
                                    value: account.account_name,
                                    id: account.id,
                                };
                            })
                        );
                    } else {
                        console.error("Expected an array but received:", data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $(this).val(ui.item.label);
                $(this).siblings(".hidden-debit-id").remove();
                $(this).after(`<input type="hidden" class="hidden-debit-id" name="debit_ac_id[]" value="${ui.item.id}">`);

                let parentRow = $(this).closest(".row");
                let subLedgerDropdown = parentRow.find(".subledger");

                fetchSubLedgerOptions(ui.item.value, subLedgerDropdown);

                return false;
            },
        });

        function fetchSubLedgerOptions(debitAccountId, subLedgerDropdown) {
            $.ajax({
                url: '<?php echo base_url("form/JournalEntry_ctrl/getSubLedgers");?>',
                type: "POST",
                data: { debit_ac_id: debitAccountId },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    subLedgerDropdown.empty();
                    subLedgerDropdown.append('<option value="">Select Subledger</option>');
                    if (Array.isArray(data)) {
                        data.forEach(function (subLedger) {
                            subLedgerDropdown.append(`<option value="${subLedger.id}">${subLedger.sub_ledger}</option>`);
                        });
                    } else {
                        console.error("Failed to fetch sub-ledgers: Unexpected response format.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: " + error);
                },
            });
        }

        $(`#${rowId} .creadit-ac`).autocomplete({
            source: function (request, response) {
                fetchAccountOptions("credit", request.term).done(function (data) {
                    console.log("Credit Data:", data);
                    if (Array.isArray(data)) {
                        response(
                            data.map((account) => {
                                return {
                                    label: `${account.account_name} (${account.account_code})`,
                                    value: account.account_name,
                                    id: account.id,
                                };
                            })
                        );
                    } else {
                        console.error("Expected an array but received:", data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $(this).val(ui.item.label);

                $(this).siblings(".hidden-creadit-id").remove();

                $(this).after(`<input type="hidden" class="hidden-creadit-id" name="credit_ac_id[]" value="${ui.item.id}">`);

                return false;
            },
        });
    }

    initializeAutocomplete("row_1");

    function updateBillAmount() {
        let total = 0;

        $(".amount").each(function () {
            let value = parseFloat($(this).val()) || 0;
            total += value;
        });

        var Grand_Total = parseFloat("<?= isset($punch_detail->Total_Amount) && $punch_detail->Total_Amount ? $punch_detail->Total_Amount : ($punch_detail->Grand_Total ?? 0) ?>");

        let TDS_amount = $("#tdsAmount").val() || 0;

        var maxAllowedAmount = total + parseFloat(TDS_amount);

        if (maxAllowedAmount > Grand_Total) {
            alert("Total bill amount cannot exceed the Grand Total including the TDS amount!");
            $("#finalSubmitBtn").attr("disabled", "disabled");
            $("#f_save_as_draft").attr("disabled", "disabled");
        } else {
            $("#finalSubmitBtn").removeAttr("disabled");
            $("#f_save_as_draft").removeAttr("disabled");
        }

        $("#billAmount").val(maxAllowedAmount.toFixed(2));
    }

    $("#account").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo base_url('form/JournalEntry_ctrl/getAllAccountList');?>",
                type: "GET",
                dataType: "json",
                data: {
                    query: request.term,
                },
                success: function (data) {
                    if (Array.isArray(data)) {
                        response(
                            data.map((account) => {
                                return {
                                    label: `${account.account_name} (${account.account_code})`,
                                    value: account.account_name,
                                    id: account.id,
                                };
                            })
                        );
                    } else {
                        console.error("Expected an array but received:", data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", status, error);
                },
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $("#account").val(ui.item.value);
            return false;
        },
    });

    $("#add_row").click(function () {
        rowCount++;
        let newRow = $("#row_1")
            .clone()
            .attr("id", "row_" + rowCount);

        newRow.find("select, input").each(function () {
            let $this = $(this);
            let id = $this.attr("id");

            if (id) {
                let newId = id.replace(/\d+$/, "") + rowCount;
                $this.attr("id", newId);
            }

            if ($this.is('input[type="text"], input[type="number"], input[type="hidden"], select')) {
                $this.val("");
            }
        });

        newRow.find(".remove_row").show();
        $("#rows_container").append(newRow);

        initializeAutocomplete(newRow.attr("id"));

        updateBillAmount();
    });

    $(document).on("click", ".remove_row", function () {
        let totalRows = $(".form-row").length;

        if (totalRows > 1) {
            $(this).closest(".form-row").remove();
            updateBillAmount();
        } else {
            alert("At least one row must remain.");
        }
    });

    $(document).on("input", ".amount", function () {
        updateBillAmount();
    });

    $('input[name="tdsApplicable"]').change(function () {
        if ($("#tdsApplicableYes").is(":checked")) {
            generateTdsJvNo();
            $("#tdsDetailsForm").show();
        } else {
            $("#tdsDetailsForm").hide();
            $("#tdsJvNo").val("");
        }
    });

    function generateTdsJvNo() {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");

        const jvNo = "<?php echo $tdsJvNo;?>";
        $("#tdsJvNo").val(jvNo);
    }

    const tdsSections = [
        { section: "194R", description: "Benefit or perquisite in respect of business or profession", rate: "10%" },
        { section: "194H", description: "Commission or brokerage", rate: "5%" },
        { section: "194JB", description: "Fee for professional service or royalty etc @10%", rate: "10%" },
        { section: "194JA", description: "Fees for Technical Services (not being professional services) @2%", rate: "2%" },
        { section: "194A", description: "Interest other than Interest on securities", rate: "10%" },
        { section: "194C", description: "Payment to Contractor / Subcontractor / Advertisements", rate: "1%" },
        { section: "194C", description: "Payment to Contractor / Subcontractor / Advertisements", rate: "2%" },
        { section: "194I", description: "Rent (Land, building or furniture)", rate: "10%" },
        { section: "194Q", description: "TDS on purchase of Goods", rate: "0.10%" },
    ];

    $("#tdsSection").on("change", function () {
        var selectedSection = $(this).val();
        var sectionDetails = tdsSections.find((section) => section.section === selectedSection);

        if (sectionDetails) {
            $("#tdsPercentage").val(sectionDetails.rate).trigger("change");
        } else {
            $("#tdsPercentage").val("");
        }
    });

    $("#billAmount, #tdsPercentage").on("input change", function () {
        var billAmount = parseFloat("<?= isset($punch_detail->Grand_Total) ? $punch_detail->Grand_Total : 0 ?>");

        var percentage = parseFloat($("#tdsPercentage").val()) || 0;
        var tdsAmount = (billAmount * percentage) / 100;
        $("#tdsAmount").val(tdsAmount.toFixed(2));
    });
});
</script>