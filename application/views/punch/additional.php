<div id="additional-info" class="tab-content">
   <form action="<?= base_url('store-additional-detail'); ?>"  method="post">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $Scan_Id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
      <div class="row" style="background-color: #fff;">
         <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
            <label for="">Document No <span class="text-danger">*</span></label>
            <input type="text" name="document_number" id="document_number" class="form-control" readonly value="<?= $document_number ?>" >
         </div>
         <div class="form-group col-md-4" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
            <label for="">Date <span class="text-danger">*</span></label>
            <input type="text" name="finance_pucnh_date" id="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>" >
         </div>
         <div class="form-group col-md-4" style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
            <label for="">Business Entity</label>
            <input type="text" id="business_entity" name="business_entity" class="form-control" placeholder="Select Business Entity">
            <input type="hidden" id="business_entity_id" name="business_entity_id">
         </div>
         <div class="form-group col-md-12" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">
            <label for="">Narration <span class="text-danger">*</span></label>
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
                  checked
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
               <input type="text" id="tds_section" class="form-control" placeholder="Select TDS Section">
               <input type="hidden" id="tds_section_id">
               <input type="hidden" id="tds_rate">
            </div>
            <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
               <label for="tdsPercentage">TDS Percentage</label>
               <input type="text" value="<?php echo isset($punch_detail->TDS_percentage) ? $punch_detail->TDS_percentage : ''; ?>" id="tds_percentage" name="tds_percentage" class="form-control" readonly placeholder="Enter TDS Percentage">
            </div>
            <div class="form-group col-md-3" style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
               <label for="tdsAmount">TDS Amount</label>
               <input   type="text" id="tds_amount" value="<?php echo isset($punch_detail->TDS_amount) ? $punch_detail->TDS_amount : ''; ?>" name="tds_amount" class="form-control" readonly>
            </div>
         </div>
         <div id="rows_container">
            <div class="row form-row" id="row_1" style="padding: 5px;">
              <!-- Cost Center -->
<div class="form-group col-md-3">
    <label for="cost_center">Cost Center</label>
    <input type="text" name="cost_center_name[]" id="cost_center_1" class="form-control autocomplete-cost-center">
    <input type="hidden" name="cost_center_id[]" id="cost_center_id_1" value="">
</div>

<!-- Department -->
<div class="form-group col-md-3">
    <label for="department">Department</label>
    <input type="text" name="department_name[]" id="department_1" class="form-control autocomplete-department">
    <input type="hidden" name="department_id[]" id="department_id_1" value="">
</div>

<!-- Business Unit -->
<div class="form-group col-md-3">
    <label for="business_unit">Business Unit</label>
    <input type="text" name="business_unit_name[]" id="business_unit_1" class="form-control autocomplete-business-unit">
    <input type="hidden" name="business_unit_id[]" id="business_unit_id_1" value="">
</div>

<!-- Region -->
<div class="form-group col-md-3">
    <label for="region">Region</label>
    <input type="text" name="region_name[]" id="region_1" class="form-control autocomplete-region">
    <input type="hidden" name="region_id[]" id="region_id_1" value="">
</div>

<!-- State -->
<div class="form-group col-md-3">
    <label for="state">State</label>
    <input type="text" name="state_name[]" id="state_1" class="form-control autocomplete-state">
    <input type="hidden" name="state_id[]" id="state_id_1" value="">
</div>

<!-- Location -->
<div class="form-group col-md-3">
    <label for="location">Location</label>
    <input type="text" name="location_name[]" id="location_1" class="form-control autocomplete-location">
    <input type="hidden" name="location_id[]" id="location_id_1" value="">
</div>

<!-- Category -->
<div class="form-group col-md-3">
    <label for="category">Category</label>
    <input type="text" name="category_name[]" id="category_1" class="form-control autocomplete-category">
    <input type="hidden" name="category_id[]" id="category_id_1" value="">
</div>

<!-- Crop -->
<div class="form-group col-md-3">
    <label for="crop">Crop</label>
    <input type="text" name="crop_name[]" id="crop_1" class="form-control autocomplete-crop">
    <input type="hidden" name="crop_id[]" id="crop_id_1" value="">
</div>

<!-- Activity -->
<div class="form-group col-md-3">
    <label for="activity">Activity</label>
    <input type="text" name="activity_name[]" id="activity_1" class="form-control autocomplete-activity">
    <input type="hidden" name="activity_id[]" id="activity_id_1" value="">
</div>

<!-- Debit A/C -->
<div class="form-group col-md-3">
    <label for="debit_ac">Debit A/C</label>
    <input type="text" name="debit_ac[]" id="debit_ac_1" class="form-control autocomplete-debit-ac">
    <input type="hidden" name="debit_ac_id[]" id="debit_ac_id_1" value="">
</div>

<!-- Payment Method -->
<div class="form-group col-md-3">
    <label for="payment_method">Payment Method</label>
    <input type="text" name="payment_method_name[]" id="payment_method_1" class="form-control autocomplete-payment-method">
    <input type="hidden" name="payment_method_id[]" id="payment_method_id_1" value="">
</div>

               <div class="form-group col-md-3">
                  <label for="amount">Amount</label>
                  <input type="number" name="item_total_amount[]" class="form-control amount" >
               </div>
               <div class="form-group col-md-3">
                  <label for="reference">Reference</label>
                  <input type="text" name="reference_no[]" id="reference_1" class="form-control" >
               </div>
               <div class="form-group col-md-3">
                  <label for="remark">Remark</label>
                  <input type="text" name="item_remark[]" id="remark_1" class="form-control" >
               </div>
               <div class="form-group col-md-3">
                  <label for="tds_amount">TDS Amount</label>
                  <input type="number" name="item_tds_amount[]" class="form-control" >
               </div>
               <div class="form-group col-md-3">
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
    initializeAllAutoCompleteInputs(rowCount);
    initAutoCompleteInput("#business_entity", "<?= site_url('get-business-entities') ?>");
    initAutoCompleteInput("#tds_section", "<?= site_url('get-tds-section') ?>", function (item) {
        $("#tds_rate").val(item.rate);
        $("#tds_section").trigger("change");
    });
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
        let newRow = $("#row_1").clone();
        newRow.attr("id", "row_" + rowCount);

        newRow.find("input").each(function () {
            let $this = $(this);
            let oldId = $this.attr("id");
            if (oldId) {
                let newId = oldId.replace(/\d+$/, "") + rowCount;
                $this.attr("id", newId);
            }
            $this.val("");
        });

        newRow.find(".remove_row").show();
        $("#rows_container").append(newRow);
        initializeAllAutoCompleteInputs(rowCount);
    });
    $(document).on("click", ".remove_row", function () {
        if ($(".form-row").length > 1) {
            $(this).closest(".form-row").remove();
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
    $("#tds_section").change(function () {
        var tds_section_id = $("#tds_section_id").val();
        var grand_total = parseFloat($("#Grand_Total").val()) || 0;
        var tds_rate = parseFloat($("#tds_rate").val()) || 0;
        $("#tds_percentage").val(tds_rate);
        var tds_amount = (grand_total * tds_rate) / 100;
        $("#tds_amount").val(tds_amount.toFixed(2));
    });
});

function initializeAllAutoCompleteInputs(rowNo) {
    initAutoCompleteInput("#cost_center_" + rowNo, "<?= site_url('get-cost-centers') ?>");
    initAutoCompleteInput("#department_" + rowNo, "<?= site_url('get-departments') ?>");
    initAutoCompleteInput("#business_unit_" + rowNo, "<?= site_url('get-business-units') ?>");
    initAutoCompleteInput("#region_" + rowNo, "<?= site_url('get-regions') ?>");
    initAutoCompleteInput("#state_" + rowNo, "<?= site_url('get-states') ?>");
    initAutoCompleteInput("#location_" + rowNo, "<?= site_url('get-locations') ?>");
    initAutoCompleteInput("#category_" + rowNo, "<?= site_url('get-categories') ?>");
    initAutoCompleteInput("#crop_" + rowNo, "<?= site_url('get-crops') ?>");
    initAutoCompleteInput("#activity_" + rowNo, "<?= site_url('get-activities') ?>");
    initAutoCompleteInput("#debit_ac_" + rowNo, "<?= site_url('get-debit-accounts') ?>");
    initAutoCompleteInput("#payment_method_" + rowNo, "<?= site_url('get-payment-methods') ?>");
}

function initAutoCompleteInput(selector, url, onSelectCallback = null) {
    $(selector)
        .autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: { term: request.term },
                    success: function (data) {
                        response(data);
                    },
                });
            },
            minLength: 0,
            appendTo: "body",
            select: function (event, ui) {
                $(this).val(ui.item.label);
                let hiddenField = $(this).attr("id") + "_id";
                $("#" + hiddenField).val(ui.item.value);

                if (typeof onSelectCallback === "function") {
                    onSelectCallback(ui.item);
                }

                return false;
            },
            focus: function (event, ui) {
                $(this).val(ui.item.label);
                return false;
            },
        })
        .focus(function () {
            $(this).autocomplete("search", "");
        });
}

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

function generateTdsJvNo() {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");

    const jvNo = "<?php echo $tdsJvNo;?>";
    $("#tdsJvNo").val(jvNo);
}

</script>