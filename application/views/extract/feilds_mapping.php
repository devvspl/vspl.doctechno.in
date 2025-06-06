<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">List of API's</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Temporary Files</div>
                            <?php if ($this->session->flashdata('message')): ?>
                                <div class="alert alert-info"><?php echo $this->session->flashdata('message'); ?></div>
                            <?php endif; ?>
                            <style>
                                tbody tr:hover {
                                    background-color: #3496ff30 !important;
                                    transition: background-color 0.3sease-in-out;
                                    cursor: pointer;
                                    border-radius: 5px;
                                }
                            </style>
                            <table class="table example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Doc Type</th>
                                        <th>Endpoint</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($api_list)): ?>
                                        <?php foreach ($api_list as $api): ?>
                                            <tr>
                                                <td><?= $api['id'] ?></td>
                                                <td><?= $api['doctype_id'] ?></td>
                                                <td><?= $api['endpoint'] ?></td>
                                                <td><?= $api['description'] ?? 'N/A' ?></td>
                                                <td><?= ($api['status'] == 1) ? 'Active' : 'Inactive' ?></td>
                                                <td class="text-center">
                                                    <button class="view-details"
                                                        style="background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;"
                                                        data-has_items_feild="N" title="Column Mapping"
                                                        data-doctype_id="<?= $api['doctype_id']; ?>">
                                                        <i class="fa fa-link"></i>
                                                    </button>

                                                    <?php if ($api['has_items']): ?>
                                                        <button class="view-details"
                                                            style="background-color: #17a2b8; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;"
                                                            data-has_items_feild="Y" title="Items Column Mapping"
                                                            data-doctype_id="<?= $api['doctype_id']; ?>">
                                                            <i class="fa fa-link"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">No records found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="documentDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Field Mapping [<span id="docTypeName"></span>]</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalMessage">

                </div>
                <div id="documentDetailsContent" class="table-responsive">
                    <p class="text-center text-muted">Please select a document to view details.</p>
                </div>
                <div class="text-center">
                    <button id="saveMappings" class="btn btn-primary">Save Mappings</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".view-details").on("click", function () {
            let doctypeId = $(this).data("doctype_id");
            let has_items_feild = $(this).data("has_items_feild");
            let year_id = <?php echo $this->session->userdata("year_id"); ?>;
            if (!doctypeId || !year_id) {
                console.error("doctypeId or year_id is missing.");
                return;
            }

            $("#saveMappings").attr("data-value", doctypeId);
            $("#saveMappings").attr("data-has_items_feild", has_items_feild);
            $("#documentDetailsModal").modal("show");
            $("#docTypeName").text(doctypeId);

            $.ajax({
                url: "<?= base_url('extract/ExtractorController/getFieldDetails/'); ?>" + has_items_feild,
                type: "POST",
                data: { doctype_id: doctypeId },
                beforeSend: function () {
                    $("#documentDetailsContent").html('<p class="text-center text-primary">Loading details...</p>');
                },
                success: function (response) {
                    let fieldData = JSON.parse(response);
                    let tableHTML = `
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Temp Column</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Input Type</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Select Table</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Search Column</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Return Column</th>
                                <th colspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Punch Table</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Punch Column</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Condition</th>
                                <th rowspan="2" style="text-align: center;align-content: center;border: 1px solid #3e596d45;background-color: #3496ff30;">Final Amt</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    fieldData.forEach((field, index) => {
                        tableHTML += `
                        <tr data-id="${field.id || index}">
                            <td>${field.temp_column || ''}</td>
                            <td>
                                <select class="form-control input-type">
                                    <option value="input" ${field.input_type === "input" ? "selected" : ""}>Input</option>
                                    <option value="select" ${field.input_type === "select" ? "selected" : ""}>Select</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select-table" ${field.input_type === "select" ? "" : "disabled"}>
                                    <option value="">-- Select Table --</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control relation-column" disabled>
                                    <option value="">-- Select Column --</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control relation-value" disabled>
                                    <option value="">-- Select Column --</option>
                                </select>
                            </td>
                            <td colspan="2">
                                <select class="form-control punch-table">
                                    <option value="">-- Select Table --</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control punch-column" disabled>
                                    <option value="">-- Select Column --</option>
                                </select>
                            </td>
                            <td><input value="${field.add_condition || ''}" class="form-control add-condition"></td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input final-amount-radio" type="radio" name="final_amount_column_${doctypeId}" value="Yes" ${field.final_amount_column === "Yes" ? "checked" : ""}>
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input final-amount-radio" type="radio" name="final_amount_column_${doctypeId}" value="No" ${field.final_amount_column !== "Yes" ? "checked" : ""}>
                                    <label class="form-check-label">No</label>
                                </div>
                                <span class="update-message text-muted small d-block mt-1"></span>
                            </td>
                        </tr>`;
                    });

                    tableHTML += `</tbody></table>`;
                    $("#documentDetailsContent").html(tableHTML);

                    fieldData.forEach((field, idx) => {
                        let row = $("#documentDetailsContent tbody tr").eq(idx);
                        if (!row.length) {
                            console.warn("Table row not found for index:", idx);
                            return;
                        }

                        let punchTable = field.punch_table || "";
                        if (punchTable === null || punchTable.trim() === "") {
                            if (has_items_feild === 'Y') {
                                punchTable = `y${year_id}_punchdata_${doctypeId}_details`;
                            } else {
                                punchTable = `y${year_id}_punchdata_${doctypeId}`;
                            }
                        }

                        let column = field.punch_column || "";
                        if (column === null || column.trim() === "") {
                            column = field.temp_column || "";
                            if (column === null || column.trim() === "") {
                                console.warn("Both punch_column and temp_column are null or empty at index:", idx);
                            }
                        }

                        if (field.select_table) {
                            let selectTableElement = row.find(".select-table");
                            fetchSelectTable(field.select_table, 'N', selectTableElement);
                        }

                        if (field.relation_column) {
                            let relationColumnElement = row.find(".relation-column");
                            fetchSelectTableColumns(field.select_table, relationColumnElement, field.relation_column);
                        }

                        if (field.relation_value) {
                            let relationValueElement = row.find(".relation-value");
                            fetchSelectTableValue(field.select_table, relationValueElement, field.relation_value);
                        }

                        let punchTableElement = row.find(".punch-table");
                        fetchSelectTable(punchTable, 'Y', punchTableElement);

                        if (column !== null && column.trim() !== "") {
                            let punchColumnElement = row.find(".punch-column");
                            fetchPunchColumns(punchTable, punchColumnElement, column);
                        }
                    });

                    // Event listener for final_amount_column radio button changes
                    $(".final-amount-radio").on("change", function () {
                        let radio = $(this);
                        let row = radio.closest("tr");
                        let rowId = row.data("id");
                        let messageSpan = row.find(".update-message");
                        let newValue = radio.val();
                        let allRadios = $(`input[name="final_amount_column_${doctypeId}"]`);
                        let punchColumnElement = row.find(".punch-column");

                        // Clear previous messages in all rows
                        $("#documentDetailsContent .update-message").text("").removeClass("text-success text-danger");

                        // If "Yes" is selected, set all other rows to "No" locally and prepare punch column update
                        let punchColumnValue = newValue === "Yes" ? `Y${doctypeId}` : punchColumnElement.data("original-value") || "";

                        if (newValue === "Yes") {
                            allRadios.each(function () {
                                if ($(this).is(radio)) return; // Skip the current radio
                                $(this).closest("tr").find(`.final-amount-radio[value="No"]`).prop("checked", true);
                            });
                        }

                        // Update punch column dropdown in UI
                        punchColumnElement.html(`<option value="${punchColumnValue}">${punchColumnValue}</option>`);
                        punchColumnElement.data("original-value", punchColumnValue);

                        // AJAX call to update final_amount_column and punch_column
                        $.ajax({
                            url: "<?= base_url('extract/ExtractorController/update_field_mapping') ?>",
                            type: "POST",
                            data: {
                                doctype_id: doctypeId,
                                id: rowId,
                                final_amount_column: newValue,
                                punch_column: punchColumnValue
                            },
                            dataType: "json",
                            beforeSend: function () {
                                messageSpan.text("Updating...").addClass("text-muted");
                            },
                            success: function (updateResponse) {
                                if (updateResponse.status === "success") {
                                    messageSpan.text("Final amount updated successfully!").addClass("text-success").removeClass("text-muted");
                                    // If "Yes" was selected, update other rows to "No" in the backend
                                    if (newValue === "Yes") {
                                        let otherRows = $("#documentDetailsContent tbody tr").not(row);
                                        otherRows.each(function () {
                                            let otherRowId = $(this).data("id");
                                            if (otherRowId !== rowId) {
                                                $.ajax({
                                                    url: "<?= base_url('extract/ExtractorController/update_field_mapping') ?>",
                                                    type: "POST",
                                                    data: {
                                                        doctype_id: doctypeId,
                                                        id: otherRowId,
                                                        final_amount_column: "No"
                                                    },
                                                    dataType: "json",
                                                    success: function () {
                                                        // No UI update needed since radio buttons are already set
                                                    },
                                                    error: function () {
                                                        console.error("Failed to update other row:", otherRowId);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                } else {
                                    messageSpan.text("Failed to update: " + (updateResponse.message || "Unknown error")).addClass("text-danger").removeClass("text-muted");
                                    // Revert radio button and punch column on failure
                                    radio.prop("checked", false);
                                    radio.closest("tr").find(`.final-amount-radio[value=${newValue === "Yes" ? "No" : "Yes"}]`).prop("checked", true);
                                    fetchPunchColumns(punchColumnElement.closest("tr").find(".punch-table").val(), punchColumnElement, punchColumnElement.data("original-value"));
                                }
                            },
                            error: function () {
                                messageSpan.text("Error updating final amount.").addClass("text-danger").removeClass("text-muted");
                                // Revert radio button and punch column on failure
                                radio.prop("checked", false);
                                radio.closest("tr").find(`.final-amount-radio[value=${newValue === "Yes" ? "No" : "Yes"}]`).prop("checked", true);
                                fetchPunchColumns(punchColumnElement.closest("tr").find(".punch-table").val(), punchColumnElement, punchColumnElement.data("original-value"));
                            },
                            complete: function () {
                                // Clear message after 5 seconds
                                setTimeout(() => {
                                    messageSpan.text("").removeClass("text-success text-danger");
                                }, 5000);
                            }
                        });
                    });
                },
                error: function () {
                    $("#documentDetailsContent").html('<p class="text-danger text-center">Error loading details.</p>');
                },
            });
        }); $(document).on("change", ".input-type", function () {
            let selectedType = $(this).val();
            let row = $(this).closest("tr");
            let tableDropdown = row.find(".select-table");

            if (selectedType === "select") {
                tableDropdown.prop("disabled", false);
                fetchSelectTable("", 'N', tableDropdown);
            } else {
                tableDropdown.prop("disabled", true).val("");
                row.find(".relation-column, .relation-value").prop("disabled", true).html('<option value="">-- Select Column --</option>');
            }
        });
        $(document).on("change", ".select-table", function () {
            let selectedTable = $(this).val();
            let row = $(this).closest("tr");
            let relationColumn = row.find(".relation-column");
            let relationValue = row.find(".relation-value");

            if (selectedTable) {
                fetchSelectTableColumns(selectedTable, relationColumn);
                fetchSelectTableValue(selectedTable, relationValue);
            } else {
                relationColumn.html('<option value="">-- Select Column --</option>').prop("disabled", true);
                relationValue.html('<option value="">-- Select Column --</option>').prop("disabled", true);
            }
        });
        $(document).on("change", ".punch-table", function () {
            let selectedTable = $(this).val();
            let columnDropdown = $(this).closest("tr").find(".punch-column");
            if (selectedTable) {
                fetchPunchColumns(selectedTable, columnDropdown);
            } else {
                columnDropdown.html('<option value="">-- Select Column --</option>').prop("disabled", true);
            }
        });
        function fetchSelectTable(selectedTable = "", punchOnly, tableDropdown) {
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/getAllTables'); ?>" + '?punchOnly=' + punchOnly,
                type: "POST",
                success: function (response) {
                    let tables = JSON.parse(response);
                    let options = '<option value="">-- Select Table --</option>';
                    tables.forEach((table) => {
                        options += `<option value="${table}" ${selectedTable === table ? "selected" : ""}>${table}</option>`;
                    });

                    tableDropdown.html(options).prop("disabled", false);
                },
                error: function () {
                    tableDropdown.html('<option value="">Error loading tables</option>').prop("disabled", true);
                },
            });
        }
        function fetchSelectTableColumns(tableName, columnDropdown, selectedColumn = "") {
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/getTableColumns'); ?>",
                type: "POST",
                data: { table: tableName },
                success: function (response) {
                    let columns = JSON.parse(response);
                    let options = '<option value="">-- Select Column --</option>';
                    columns.forEach((col) => {
                        options += `<option value="${col}" ${selectedColumn === col ? "selected" : ""}>${col}</option>`;
                    });

                    columnDropdown.html(options).prop("disabled", false);
                },
                error: function () {
                    columnDropdown.html('<option value="">Error loading columns</option>').prop("disabled", true);
                },
            });
        }
        function fetchSelectTableValue(tableName, columnDropdown, selectedColumn = "") {
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/getTableColumns'); ?>",
                type: "POST",
                data: { table: tableName },
                success: function (response) {
                    let columns = JSON.parse(response);
                    let options = '<option value="">-- Select Value --</option>';
                    columns.forEach((col) => {
                        options += `<option value="${col}" ${selectedColumn === col ? "selected" : ""}>${col}</option>`;
                    });

                    columnDropdown.html(options).prop("disabled", false);
                },
                error: function () {
                    columnDropdown.html('<option value="">Error loading values</option>').prop("disabled", true);
                },
            });
        }
        function fetchPunchColumns(tableName, columnDropdown, selectedColumn = "") {
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/getPunchTableColumns'); ?>",
                type: "POST",
                data: { table: tableName },
                success: function (response) {
                    let columns = JSON.parse(response);
                    let options = '<option value="">-- Select Column --</option>';
                    columns.forEach((col) => {
                        options += `<option value="${col}" ${selectedColumn === col ? "selected" : ""}>${col}</option>`;
                    });

                    columnDropdown.html(options).prop("disabled", false);
                },
                error: function () {
                    columnDropdown.html('<option value="">Error loading columns</option>').prop("disabled", true);
                },
            });
        }
        $(document).on("click", "#saveMappings", function () {
            let fieldMappings = [];
            let saveButton = $("#saveMappings");
            let originalText = saveButton.text();
            $("#documentDetailsContent tbody tr").each(function () {
                let row = $(this);
                let tempColumn = row.find("td:first").text();
                let inputType = row.find(".input-type").val();
                let selectTable = row.find(".select-table").val();
                let relationColumn = row.find(".relation-column").val();
                let relationValue = row.find(".relation-value").val();
                let punchTable = row.find(".punch-table").val();
                let punchColumn = row.find(".punch-column").val();
                let addCondition = row.find(".add-condition").val();
                fieldMappings.push({
                    temp_column: tempColumn,
                    input_type: inputType,
                    select_table: selectTable || "",
                    relation_column: relationColumn || "",
                    relation_value: relationValue || "",
                    punch_table: punchTable || "",
                    punch_column: punchColumn || "",
                    add_condition: addCondition || "",
                });
            });
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/saveFieldMappings'); ?>",
                type: "POST",
                data: {
                    doctype_id: saveButton.data("value"),
                    has_items_feild: saveButton.data("has_items_feild"),
                    fieldMappings: fieldMappings,
                },
                beforeSend: function () {
                    saveButton.text("Please wait...").prop("disabled", true);
                },
                success: function (response) {
                    let result = JSON.parse(response);
                    let messageDiv = $("#modalMessage");
                    messageDiv.html(result.message).removeClass("alert-danger").addClass("alert alert-success").fadeIn();
                    if (result.status === "success") {
                        setTimeout(function () {
                            messageDiv.fadeOut();
                            location.reload();
                        }, 500);
                    } else {
                        messageDiv.html(result.message).removeClass("alert-success").addClass("alert alert-danger").fadeIn();
                    }
                },
                error: function () {
                    $("#modalMessage").html("Error saving mappings. Please try again.").removeClass("alert-success").addClass("alert alert-danger").fadeIn();
                },
                complete: function () {
                    saveButton.text(originalText).prop("disabled", false);
                },
            });
        });
        $(document).on("change", ".applyAllPunch", function () {
            let selectedTable = $(this).val();
            $(".punch-table").val(selectedTable).trigger("change");
            $(".punch-table").each(function () {
                let columnDropdown = $(this).closest("tr").find(".punch-column");
                $.ajax({
                    url: "<?= base_url('extract/ExtractorController/getPunchTableColumns'); ?>",
                    type: "POST",
                    data: { table: selectedTable },
                    success: function (response) {
                        let columns = JSON.parse(response);
                        let options = '<option value="">-- Select Column --</option>';
                        columns.forEach((col) => {
                            options += `<option value="${col}">${col}</option>`;
                        });
                        columnDropdown.html(options);
                    },
                    error: function () {
                        columnDropdown.html('<option value="">Error loading columns</option>');
                    },
                });
            });
        });
    });
</script>