<div id="additional-info" class="tab-content">
    <form action="<?= base_url('store-additional-detail'); ?>" method="post">
        <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
        <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
        <div class="row" style="background-color: #fff;">
            <div class="form-group col-md-2"
                style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                <label for="">Document No <span class="text-danger">*</span></label>
                <input type="text" name="document_number" id="document_number" class="form-control" readonly
                    value="<?= isset($main_record) && isset($main_record['document_no']) ? $main_record['document_no'] : (isset($document_number) ? $document_number : ''); ?>">

            </div>
            <div class="form-group col-md-2"
                style="background-color: #ffffff;     margin-bottom: 0;padding-bottom: 5px;">
                <label for="">Date <span class="text-danger">*</span></label>
                <input type="text" name="finance_pucnh_date" id="date" class="form-control" readonly value="<?=
                    isset($main_record) && !empty($main_record['document_date']) ? $main_record['document_date'] :
                    (isset($punch_detail) && !empty($punch_detail->document_date) ? $punch_detail->document_date :
                        date('Y-m-d'))
                    ?>">

            </div>
            <div class="form-group col-md-2" style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                <label for="">Business Entity</label>
                <input type="hidden" id="business_entity_id" name="business_entity_id"
                    value="<?= isset($main_record) ? $main_record['business_entity_id'] : ''; ?>">
                <input type="text" id="business_entity" name="business_entity" class="form-control"
                    placeholder="Select Business Entity"
                    value="<?= isset($main_record) ? $main_record['business_entity_name'] : ''; ?>">
            </div>
            <div class="form-group col-md-3 tds-applicable-group"
                style="display: flex; flex-direction: column; background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                <label for="tdsApplicable" style="margin-bottom: 5px;">TDS Applicable</label>

                <div style="display: flex; gap: 15px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tdsApplicable" id="tdsApplicableYes"
                            value="Yes" <?= (isset($main_record['tds_applicable']) && $main_record['tds_applicable'] === 'Yes') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="tdsApplicableYes">Yes</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tdsApplicable" id="tdsApplicableNo"
                            value="No" <?= (isset($main_record['tds_applicable']) && $main_record['tds_applicable'] === 'No') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="tdsApplicableNo">No</label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12" style="background-color: #ffffff; margin-bottom: 0;padding-bottom: 5px;">

                <label for="">Narration <span class="text-danger">*</span></label>
                <textarea name="narration" id="narration" placeholder="Enter transaction details"
                    class="form-control"><?= isset($main_record) && isset($main_record['narration']) ? $main_record['narration'] : (isset($punch_detail) && isset($punch_detail->remark_comment) ? $punch_detail->remark_comment : ''); ?></textarea>

            </div>



            <?php $tds_display = (isset($main_record) && isset($main_record['tds_applicable']) && $main_record['tds_applicable'] == 'Yes') ? 'block' : 'none'; ?>
            <div id="tdsDetailsForm" class="tds-details-form" style="display: <?= $tds_display ?>; margin-top: 15px;">
                <div class="form-group col-md-3"
                    style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                    <label for="tdsJvNo">TDS JV No</label>
                    <input type="text" id="tdsJvNo" name="TDS_JV_no" class="form-control" readonly
                        value="<?= (!empty($main_record['tds_jv_no'])) ? $main_record['tds_jv_no'] : (isset($punch_detail->tdsJvNo) ? $punch_detail->tdsJvNo : ''); ?>">
                </div>


                <div class="form-group col-md-3"
                    style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                    <label for="tds_section">TDS Section</label>
                    <input type="text" id="tds_section" name="tds_section" class="form-control"
                        placeholder="Select TDS Section"
                        value="<?= isset($main_record['section']) ? $main_record['section'] : ''; ?>">

                    <input type="hidden" id="tds_section_id" name="tds_section_id"
                        value="<?= isset($main_record['tds_section_id']) ? $main_record['tds_section_id'] : ''; ?>">

                    <input type="hidden" id="tds_rate" name="tds_rate"
                        value="<?= isset($main_record['tds_rate']) ? $main_record['tds_rate'] : ''; ?>">
                </div>

                <div class="form-group col-md-3"
                    style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                    <label for="tds_percentage">TDS Percentage</label>
                    <input type="text" id="tds_percentage" name="tds_percentage" class="form-control" readonly
                        placeholder="Enter TDS Percentage"
                        value="<?= isset($main_record['tds_percentage']) ? $main_record['tds_percentage'] : ''; ?>">
                </div>
                <div class="form-group col-md-3"
                    style="background-color: #ffffff; margin-bottom: 0; padding-bottom: 5px;">
                    <label for="tds_amount">TDS Amount</label>
                    <input type="text" id="tds_amount" name="tds_amount" class="form-control" readonly
                        value="<?= isset($main_record['tds_amount']) ? $main_record['tds_amount'] : ''; ?>">
                </div>
            </div>
            <div id="rows_container" style="overflow-y: auto;float: left;width: 100%;padding:0 10px;">
                <?php if (!empty($items)): ?>
                    <table class="table table-bordered" id="items_table">
                        <thead>
                            <tr>
                                <th>Reverse Charge</th>
                                <th>Cost/Sub</th>
                                <th>Business Unit</th>
                                <th>Activity</th>
                                <th>Location</th>
                                <th>State</th>
                                <th>Category</th>
                                <th>Crop</th>
                                <th>Region</th>
                                <th>Function</th>
                                <th>Vertical</th>
                                <th>Department</th>
                                <th>Sub Department</th>
                                <th>Zone</th>
                                <th>Debit A/C</th>
                                <th>Credit A/C</th>
                                <th>Amount</th>
                                <th>Payment Term</th>
                                <th>Reference</th>
                                <th>Remark</th>
                                <th>TDS Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr class="form-row" id="row_<?= $index + 1 ?>">
                                    <td>
                                        <input type="text" name="reverse_charge[<?= $index ?>]"
                                            id="reverse_charge_<?= $index ?>" class="form-control"
                                            value="<?= isset($item['reverse_charge']) ? htmlspecialchars($item['reverse_charge']) : '' ?>">
                                        <input type="hidden" name="reverse_charge_id[<?= $index ?>]"
                                            id="reverse_charge_id_<?= $index ?>"
                                            value="<?= isset($item['reverse_charge']) && $item['reverse_charge'] === 'Yes' ? '1' : (isset($item['reverse_charge']) && $item['reverse_charge'] === 'No' ? '0' : '') ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="cost_center_name[]" id="cost_center_<?= $index + 1 ?>"
                                            class="form-control autocomplete-cost-center"
                                            value="<?= isset($item['cost_center_name']) ? $item['cost_center_name'] : ''; ?>">
                                        <input type="hidden" name="cost_center_id[]" id="cost_center_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['cost_center_id']) ? $item['cost_center_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="business_unit_name[]" id="business_unit_<?= $index + 1 ?>"
                                            class="form-control autocomplete-business-unit"
                                            value="<?= isset($item['business_unit_name']) ? $item['business_unit_name'] : ''; ?>">
                                        <input type="hidden" name="business_unit_id[]" id="business_unit_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['business_unit_id']) ? $item['business_unit_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="activity_name[]" id="activity_<?= $index + 1 ?>"
                                            class="form-control autocomplete-activity"
                                            value="<?= isset($item['activity_name']) ? $item['activity_name'] : ''; ?>">
                                        <input type="hidden" name="activity_id[]" id="activity_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['activity_id']) ? $item['activity_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="location_name[]" id="location_<?= $index + 1 ?>"
                                            class="form-control autocomplete-location"
                                            value="<?= isset($item['location_name']) ? $item['location_name'] : ''; ?>">
                                        <input type="hidden" name="location_id[]" id="location_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['location_id']) ? $item['location_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="state_name[]" id="state_<?= $index + 1 ?>"
                                            class="form-control autocomplete-state"
                                            value="<?= isset($item['state_name']) ? $item['state_name'] : ''; ?>">
                                        <input type="hidden" name="state_id[]" id="state_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['state_id']) ? $item['state_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="category_name[]" id="category_<?= $index + 1 ?>"
                                            class="form-control autocomplete-category"
                                            value="<?= isset($item['category_name']) ? $item['category_name'] : ''; ?>">
                                        <input type="hidden" name="category_id[]" id="category_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['category_id']) ? $item['category_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="crop_name[]" id="crop_<?= $index + 1 ?>"
                                            class="form-control autocomplete-crop"
                                            value="<?= isset($item['crop_name']) ? $item['crop_name'] : ''; ?>">
                                        <input type="hidden" name="crop_id[]" id="crop_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['crop_id']) ? $item['crop_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="region_name[]" id="region_<?= $index + 1 ?>"
                                            class="form-control autocomplete-region"
                                            value="<?= isset($item['region_name']) ? $item['region_name'] : ''; ?>">
                                        <input type="hidden" name="region_id[]" id="region_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['region_id']) ? $item['region_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="function_name[]" id="function_<?= $index + 1 ?>"
                                            class="form-control"
                                            value="<?= isset($item['function_name']) ? $item['function_name'] : ''; ?>">
                                        <input type="hidden" name="function_id[]" id="function_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['function_id']) ? $item['function_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="vertical_name[]" id="vertical_<?= $index + 1 ?>"
                                            class="form-control"
                                            value="<?= isset($item['vertical_name']) ? $item['vertical_name'] : ''; ?>">
                                        <input type="hidden" name="vertical_id[]" id="vertical_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['vertical_id']) ? $item['vertical_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="department_name[]" id="department_<?= $index + 1 ?>"
                                            class="form-control autocomplete-department"
                                            value="<?= isset($item['department_name']) ? $item['department_name'] : ''; ?>">
                                        <input type="hidden" name="department_id[]" id="department_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['department_id']) ? $item['department_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="sub_department[]" id="sub_department_<?= $index + 1 ?>"
                                            class="form-control"
                                            value="<?= isset($item['sub_department_name']) ? $item['sub_department_name'] : ''; ?>">
                                        <input type="hidden" name="sub_department_id[]" id="sub_department_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['sub_department_id']) ? $item['sub_department_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="zone[]" id="zone_<?= $index + 1 ?>" class="form-control"
                                            value="<?= isset($item['zone_name']) ? $item['zone_name'] : ''; ?>">
                                        <input type="hidden" name="zone_id[]" id="zone_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['zone_id']) ? $item['zone_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="debit_ac[]" id="debit_ac_<?= $index + 1 ?>"
                                            class="form-control autocomplete-debit-ac"
                                            value="<?= isset($item['debit_account']) ? $item['debit_account'] : ''; ?>">
                                        <input type="hidden" name="debit_ac_id[]" id="debit_ac_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['debit_account_id']) ? $item['debit_account_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="credit_ac[]" id="credit_ac_<?= $index + 1 ?>"
                                            class="form-control autocomplete-credit-ac"
                                            value="<?= isset($item['credit_account']) ? $item['credit_account'] : ''; ?>">
                                        <input type="hidden" name="credit_ac_id[]" id="credit_ac_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['credit_account_id']) ? $item['credit_account_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="item_total_amount[]" id="item_total_amount_<?= $index + 1 ?>"
                                            class="form-control amount"
                                            value="<?= isset($item['amount']) ? $item['amount'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="payment_term_name[]" id="payment_term_<?= $index + 1 ?>"
                                            class="form-control autocomplete-payment-term"
                                            value="<?= isset($item['payment_term']) ? $item['payment_term'] : ''; ?>">
                                        <input type="hidden" name="payment_term_id[]" id="payment_term_id_<?= $index + 1 ?>"
                                            value="<?= isset($item['payment_term_id']) ? $item['payment_term_id'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="reference_no[]" id="reference_no_<?= $index + 1 ?>"
                                            class="form-control"
                                            value="<?= isset($item['reference']) ? $item['reference'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="item_remark[]" id="item_remark_<?= $index + 1 ?>"
                                            class="form-control" value="<?= isset($item['remark']) ? $item['remark'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="item_tds_amount[]" id="item_tds_amount_<?= $index + 1 ?>"
                                            class="form-control"
                                            value="<?= isset($item['tds_amount']) ? $item['tds_amount'] : ''; ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove_row" style="padding: 0px 5px;"
                                            title="Remove">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <table class="table table-bordered" id="items_table">
                        <thead>
                            <tr>
                                <th>Reverse Charge</th>
                                <th>Cost/Sub</th>
                                <th>Business Unit</th>
                                <th>Activity</th>
                                <th>Location</th>
                                <th>State</th>
                                <th>Category</th>
                                <th>Crop</th>
                                <th>Region</th>
                                <th>Function</th>
                                <th>Vertical</th>
                                <th>Department</th>
                                <th>Sub Department</th>
                                <th>Zone</th>
                                <th>Debit A/C</th>
                                <th>Credit A/C</th>
                                <th>Amount</th>
                                <th>Payment Term</th>
                                <th>Reference</th>
                                <th>Remark</th>
                                <th>TDS Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="form-row" id="row_1">
                                <td>
                                    <input type="text" name="reverse_charge[]" id="reverse_charge_1" class="form-control">
                                    <input type="hidden" name="reverse_charge_id[]" id="reverse_charge_id_1">
                                </td>
                                <td>
                                    <input type="text" name="cost_center_name[]" id="cost_center_1"
                                        class="form-control autocomplete-cost-center">
                                    <input type="hidden" name="cost_center_id[]" id="cost_center_id_1">
                                </td>
                                <td>
                                    <input type="text" name="business_unit_name[]" id="business_unit_1"
                                        class="form-control autocomplete-business-unit">
                                    <input type="hidden" name="business_unit_id[]" id="business_unit_id_1">
                                </td>
                                <td>
                                    <input type="text" name="activity_name[]" id="activity_1"
                                        class="form-control autocomplete-activity">
                                    <input type="hidden" name="activity_id[]" id="activity_id_1">
                                </td>
                                <td>
                                    <input type="text" name="location_name[]" id="location_1"
                                        class="form-control autocomplete-location">
                                    <input type="hidden" name="location_id[]" id="location_id_1">
                                </td>
                                <td>
                                    <input type="text" name="state_name[]" id="state_1"
                                        class="form-control autocomplete-state">
                                    <input type="hidden" name="state_id[]" id="state_id_1">
                                </td>
                                <td>
                                    <input type="text" name="category_name[]" id="category_1"
                                        class="form-control autocomplete-category">
                                    <input type="hidden" name="category_id[]" id="category_id_1">
                                </td>
                                <td>
                                    <input type="text" name="crop_name[]" id="crop_1"
                                        class="form-control autocomplete-crop">
                                    <input type="hidden" name="crop_id[]" id="crop_id_1">
                                </td>
                                <td>
                                    <input type="text" name="region_name[]" id="region_1"
                                        class="form-control autocomplete-region">
                                    <input type="hidden" name="region_id[]" id="region_id_1">
                                </td>
                                <td>
                                    <input type="text" name="function_name[]" id="function_1" class="form-control">
                                    <input type="hidden" name="function_id[]" id="function_id_1">
                                </td>
                                <td>
                                    <input type="text" name="vertical_name[]" id="vertical_1" class="form-control">
                                    <input type="hidden" name="vertical_id[]" id="vertical_id_1">
                                </td>
                                <td>
                                    <input type="text" name="department_name[]" id="department_1"
                                        class="form-control autocomplete-department">
                                    <input type="hidden" name="department_id[]" id="department_id_1">
                                </td>
                                <td>
                                    <input type="text" name="sub_department[]" id="sub_department_1" class="form-control">
                                    <input type="hidden" name="sub_department_id[]" id="sub_department_id_1">
                                </td>
                                <td>
                                    <input type="text" name="zone[]" id="zone_1" class="form-control">
                                    <input type="hidden" name="zone_id[]" id="zone_id_1">
                                </td>
                                <td>
                                    <input type="text" name="debit_ac[]" id="debit_ac_1"
                                        class="form-control autocomplete-debit-ac">
                                    <input type="hidden" name="debit_ac_id[]" id="debit_ac_id_1">
                                </td>
                                <td>
                                    <input type="text" name="credit_ac[]" id="credit_ac_1"
                                        class="form-control autocomplete-credit-ac">
                                    <input type="hidden" name="credit_ac_id[]" id="credit_ac_id_1">
                                </td>
                                <td>
                                    <input type="text" name="item_total_amount[]" id="item_total_amount_1"
                                        class="form-control amount">
                                </td>
                                <td>
                                    <input type="text" name="payment_term_name[]" id="payment_term_1"
                                        class="form-control autocomplete-payment-term">
                                    <input type="hidden" name="payment_term_id[]" id="payment_term_id_1">
                                </td>
                                <td>
                                    <input type="text" name="reference_no[]" id="reference_no_1" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="item_remark[]" id="item_remark_1" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="item_tds_amount[]" id="item_tds_amount_1" class="form-control">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove_row" style="padding: 0px 5px;"
                                        title="Remove">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="form-group col-md-12" style="margin-top: 5px;">
                <button type="button" class="btn btn-success btn-sm" id="add_row"
                    style="margin-top: 10px;padding:0px 5px;" title="Add Row">
                    <i class="fa fa-plus"></i>
                </button>

                <label style="float: right;">Total: <input type="number" name="finance_total_Amount" readonly
                        id="billAmount" value="<?= isset($main_record) ? $main_record['total_amount'] : ''; ?>"
                        class="form-control" /></label>
                <div id="amount_variation_msg"></div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <input type="submit" class="btn btn-info pull-right" id="fin_save_draft" style="margin-left: 20px;"
                name="save_draft" value="Save as Draft"></input>
            <input type="submit" class="btn btn-success pull-right" id="fin_final_submit" name="final_submit"
                value="Final Submit"></input>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        updateBillAmount();
        let rowCount = $("#rows_container .form-row").length;
        for (let rowNo = 1; rowNo <= rowCount; rowNo++) {
            initializeAllAutoCompleteInputs(rowNo);
            setupDependentFieldListeners(rowNo);
        }

        initAutoCompleteInput("#business_entity", "<?= site_url('get-business-entities') ?>", function (item) {
            $("#business_entity").val(item.label);
        });

        initAutoCompleteInput("#tds_section", "<?= site_url('get-tds-section') ?>", function (item) {
            $("#tds_section").val(item.label);
            $("#tds_rate").val(item.rate);
            $("#tds_section").trigger("change");
        });

        $("#account").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo base_url('form/JournalEntry_ctrl/getAllAccountList'); ?>",
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
                                        label: `${account.account_name} (${account.focus_code})`,
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
            let rowCount = $("#items_table tbody tr").length + 1;
            let newRow = $("#row_1").clone();
            newRow.attr("id", "row_" + rowCount);

            newRow.find("input").each(function () {
                let $this = $(this);
                let oldId = $this.attr("id");
                let oldName = $this.attr("name");

                if (oldId) {
                    let newId = oldId.replace(/\d+$/, "") + rowCount;
                    $this.attr("id", newId);
                }

                if (oldName) {
                    let newName = oldName.replace(/\[\d+\]/, "[" + rowCount + "]");
                    $this.attr("name", newName);
                }

                $this.val("");
            });

            newRow.find(".remove_row").show();
            $("#items_table tbody").append(newRow);

            initializeAllAutoCompleteInputs(rowCount);
            setupDependentFieldListeners(rowCount);
        });

        $(document).on("click", ".remove_row", function () {
            if ($("#items_table tbody tr").length > 1) {
                $(this).closest("tr").remove();
                updateBillAmount();
            } else {
                alert("At least one row must remain.");
            }
        });

        $(document).on("input", ".amount", function () {
            updateBillAmount();
        });

        $(document).on('mouseenter', '#rows_container input.form-control', function () {
            $(this).attr('title', $(this).val() || 'No value');
            $(this).css({
                'background-color': '#e6f3ff',
                'border-color': '#007bff'
            });
        }).on('mouseleave', '#rows_container input.form-control', function () {
            $(this).removeAttr('title');
            $(this).css({
                'background-color': '',
                'border-color': ''
            });
        });

        $(document).on('input', '#rows_container input.form-control', function () {
            $(this).attr('title', $(this).val() || 'No value');
        });

        $(document).on('keydown', '#rows_container input.form-control', function (e) {
            const key = e.which;
            const leftArrow = 37;
            const rightArrow = 39;
            const tabKey = 9;

            if (key === leftArrow || key === rightArrow || key === tabKey) {
                e.preventDefault();
                const $inputs = $(this).closest('tr').find('input.form-control');
                const $allRows = $('#items_table tbody tr');
                const currentRowIndex = $allRows.index($(this).closest('tr'));
                const currentIndex = $inputs.index(this);

                $(this).css({
                    'background-color': '',
                    'border-color': ''
                });
                $(this).removeAttr('title');

                let $nextInput, nextIndex;
                if (key === rightArrow || key === tabKey) {
                    nextIndex = currentIndex + 1;
                    if (nextIndex < $inputs.length) {
                        $nextInput = $inputs.eq(nextIndex);
                    } else {
                        const nextRowIndex = (currentRowIndex + 1) % $allRows.length;
                        $nextInput = $allRows.eq(nextRowIndex).find('input.form-control').first();
                    }
                } else if (key === leftArrow) {
                    nextIndex = currentIndex - 1;
                    if (nextIndex >= 0) {
                        $nextInput = $inputs.eq(nextIndex);
                    } else {
                        const prevRowIndex = currentRowIndex - 1 >= 0 ? currentRowIndex - 1 : $allRows.length - 1;
                        $nextInput = $allRows.eq(prevRowIndex).find('input.form-control').last();
                    }
                }

                $nextInput.focus();
                $nextInput.css({
                    'background-color': '#e6f3ff',
                    'border-color': '#007bff'
                });
                $nextInput.attr('title', $nextInput.val() || 'No value');
            }
        });

        $(document).on('blur', '#rows_container input.form-control', function () {
            $(this).css({
                'background-color': '',
                'border-color': ''
            });
            $(this).removeAttr('title');
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
            var billAmount = parseFloat($(".final_amount_column").val()) || 0;
            var percentage = parseFloat($("#tdsPercentage").val()) || 0;
            var tds_amount = (billAmount * percentage) / 100;
            $("#tds_amount").val(tds_amount.toFixed(2));
        });

        $("#tds_section").change(function () {
            var tds_section_id = $("#tds_section_id").val();
            var grand_total = parseFloat($("#Grand_Total").val()) || 0;
            var tds_rate = parseFloat($("#tds_rate").val()) || 0;
            $("#tds_percentage").val(tds_rate);
            var tds_amount = (grand_total * tds_rate) / 100;
            $("#tds_amount").val(tds_amount.toFixed(2));
        });

        function setupDependentFieldListeners(rowNo) {
            $(document).on('change', `#function_${rowNo}`, function () {
                const functionId = $(`#function_id_${rowNo}`).val();

                initAutoCompleteInput(
                    `#vertical_${rowNo}`,
                    "<?= site_url('get-vertical') ?>",
                    null,
                    `function_id_${rowNo}`,
                    functionId
                );
                clearField(`#vertical_${rowNo}`, `#vertical_id_${rowNo}`);
                clearField(`#department_${rowNo}`, `#department_id_${rowNo}`);
                clearField(`#sub_department_${rowNo}`, `#sub_department_id_${rowNo}`);
                clearField(`#activity_${rowNo}`, `#activity_id_${rowNo}`);
                clearField(`#crop_${rowNo}`, `#crop_id_${rowNo}`);
            });

            $(document).on('change', `#vertical_${rowNo}`, function () {
                const verticalId = $(`#vertical_id_${rowNo}`).val();

                initAutoCompleteInput(
                    `#department_${rowNo}`,
                    "<?= site_url('get-departments') ?>",
                    null,
                    `vertical_id_${rowNo}`,
                    verticalId
                );
                initAutoCompleteInput(
                    `#crop_${rowNo}`,
                    "<?= site_url('get-crops') ?>",
                    null,
                    `vertical_id_${rowNo}`,
                    verticalId
                );
                clearField(`#department_${rowNo}`, `#department_id_${rowNo}`);
                clearField(`#sub_department_${rowNo}`, `#sub_department_id_${rowNo}`);
                clearField(`#activity_${rowNo}`, `#activity_id_${rowNo}`);
                clearField(`#crop_${rowNo}`, `#crop_id_${rowNo}`);
            });

            $(document).on('change', `#department_${rowNo}`, function () {
                const departmentId = $(`#department_id_${rowNo}`).val();

                initAutoCompleteInput(
                    `#sub_department_${rowNo}`,
                    "<?= site_url('get-sub-department') ?>",
                    null,
                    `department_id_${rowNo}`,
                    departmentId
                );
                initAutoCompleteInput(
                    `#activity_${rowNo}`,
                    "<?= site_url('get-activities') ?>",
                    null,
                    `department_id_${rowNo}`,
                    departmentId
                );
                clearField(`#sub_department_${rowNo}`, `#sub_department_id_${rowNo}`);
                clearField(`#activity_${rowNo}`, `#activity_id_${rowNo}`);
            });

            $(document).on('change', `#category_${rowNo}`, function () {
                const categoryId = $(`#category_id_${rowNo}`).val();

                initAutoCompleteInput(
                    `#crop_${rowNo}`,
                    "<?= site_url('get-crops') ?>",
                    null,
                    `category_id_${rowNo}`,
                    categoryId
                );
                clearField(`#crop_${rowNo}`, `#crop_id_${rowNo}`);
            });
        }
        function clearField(inputSelector, hiddenSelector) {
            $(inputSelector).val('');
            $(hiddenSelector).val('');
        }

        function initializeAllAutoCompleteInputs(rowNo) {
            initAutoCompleteInput(`#cost_center_${rowNo}`, "<?= site_url('get-cost-centers') ?>");
            initAutoCompleteInput(`#business_unit_${rowNo}`, "<?= site_url('get-business-units') ?>");
            initAutoCompleteInput(`#region_${rowNo}`, "<?= site_url('get-regions') ?>");
            initAutoCompleteInput(`#state_${rowNo}`, "<?= site_url('get-states') ?>");
            initAutoCompleteInput(`#location_${rowNo}`, "<?= site_url('get-locations') ?>");
            initAutoCompleteInput(`#category_${rowNo}`, "<?= site_url('get-categories') ?>");
            initAutoCompleteInput(`#debit_ac_${rowNo}`, "<?= site_url('get-debit-accounts') ?>");
            initAutoCompleteInput(`#credit_ac_${rowNo}`, "<?= site_url('get-credit-accounts') ?>");
            initAutoCompleteInput(`#payment_term_${rowNo}`, "<?= site_url('get-payment-term') ?>");
            initAutoCompleteInput(`#function_${rowNo}`, "<?= site_url('get-function') ?>");
            initAutoCompleteInput(`#zone_${rowNo}`, "<?= site_url('get-zone') ?>");
            initAutoCompleteInput(`#reverse_charge_${rowNo}`, null, null, null, null, [
                { value: "1", label: "Yes" },
                { value: "0", label: "No" }
            ]);


            const functionId = $(`#function_id_${rowNo}`).val();
            if (functionId) {
                initAutoCompleteInput(`#vertical_${rowNo}`, "<?= site_url('get-vertical') ?>", null, `function_id_${rowNo}`, functionId);
            } else {
                initAutoCompleteInput(`#vertical_${rowNo}`, "<?= site_url('get-vertical') ?>");
            }

            const verticalId = $(`#vertical_id_${rowNo}`).val();
            if (verticalId) {
                initAutoCompleteInput(`#department_${rowNo}`, "<?= site_url('get-departments') ?>", null, `vertical_id_${rowNo}`, verticalId);
                initAutoCompleteInput(`#crop_${rowNo}`, "<?= site_url('get-crops') ?>", null, `vertical_id_${rowNo}`, verticalId);
            } else {
                initAutoCompleteInput(`#department_${rowNo}`, "<?= site_url('get-departments') ?>");
                initAutoCompleteInput(`#crop_${rowNo}`, "<?= site_url('get-crops') ?>");
            }

            const departmentId = $(`#department_id_${rowNo}`).val();
            if (departmentId) {
                initAutoCompleteInput(`#sub_department_${rowNo}`, "<?= site_url('get-sub-department') ?>", null, `department_id_${rowNo}`, departmentId);
                initAutoCompleteInput(`#activity_${rowNo}`, "<?= site_url('get-activities') ?>", null, `department_id_${rowNo}`, departmentId);
            } else {
                initAutoCompleteInput(`#sub_department_${rowNo}`, "<?= site_url('get-sub-department') ?>");
                initAutoCompleteInput(`#activity_${rowNo}`, "<?= site_url('get-activities') ?>");
            }
        }

        function initAutoCompleteInput(selector, url, onSelectCallback = null, dependentFieldId = null, dependentFieldValue = null, staticData = null) {
            $(selector).autocomplete({
                source: function (request, response) {
                    if (staticData) {
                        response(staticData.filter(item =>
                            item.label.toLowerCase().includes(request.term.toLowerCase())
                        ));
                    } else {
                        let data = { term: request.term };
                        if (dependentFieldId && dependentFieldValue) {
                            data[dependentFieldId.replace(/_id_\d+$/, '')] = dependentFieldValue;
                        }
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: data,
                            success: function (data) {
                                console.log(`Response from ${url}:`, data);
                                response(data);
                            },
                            error: function (xhr, status, error) {
                                console.error(`AJAX error for ${url}:`, status, error);
                            }
                        });
                    }
                },
                minLength: 0,
                appendTo: "body",
                select: function (event, ui) {
                    console.log(`Selected item for ${selector}:`, ui.item);
                    $(this).val(ui.item.label);
                    let currentId = $(this).attr("id");
                    let hiddenFieldId = currentId;

                    if (/\d+$/.test(currentId)) {
                        hiddenFieldId = currentId.replace(/_(\d+)$/, "_id_$1");
                    } else {
                        hiddenFieldId = currentId + "_id";
                    }

                    $(`#${hiddenFieldId}`).val(ui.item.value);
                    console.log(`Updated hidden field ${hiddenFieldId} with value:`, ui.item.value);

                    if (typeof onSelectCallback === "function") {
                        onSelectCallback(ui.item);
                    }

                    $(this).trigger('change');
                    return false;
                },
                focus: function (event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                }
            }).focus(function () {
                $(this).autocomplete("search", "");
            });
        }

        function updateBillAmount() {
            let total = 0;
            var final_amount = parseFloat($(".final_amount_column").val()) || 0;


            $(".amount").each(function () {
                let value = parseFloat($(this).val()) || 0;
                total += value;
            });


            let TDS_amount = parseFloat($("#tds_amount").val()) || 0;
            var maxAllowedAmount = total + TDS_amount;


            $("#billAmount").val(maxAllowedAmount.toFixed(2));

            var submitButton = $("#fin_final_submit");
            var draftButton = $("#fin_save_draft");


            var variation = (final_amount - maxAllowedAmount).toFixed(2);
            var variationText = (variation >= 0 ? "+" : "") + variation;


            $("#amount_variation_msg").remove();


            $("#billAmount").after(`<div id="amount_variation_msg" style="color: red; font-size: 12px;">Amount variation: ${variationText}</div>`);


            if (final_amount.toFixed(2) === maxAllowedAmount.toFixed(2)) {
                submitButton.removeAttr('disabled');
            } else {
                submitButton.attr('disabled', 'disabled');
            }


            if (maxAllowedAmount > final_amount || maxAllowedAmount < 0) {
                draftButton.attr('disabled', 'disabled');
            } else {
                draftButton.removeAttr('disabled');
            }
        }


        function generateTdsJvNo() {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const jvNo = "<?php echo $tdsJvNo; ?>";
            $("#tdsJvNo").val(jvNo);
        }
    });
</script>