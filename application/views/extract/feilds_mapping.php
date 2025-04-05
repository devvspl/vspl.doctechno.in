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
                                    <?php if (!empty($api_list)) : ?>
                                        <?php foreach ($api_list as $api) : ?>
                                            <tr>
                                                <td><?= $api['id'] ?></td>
                                                <td><?= $api['doctype_id'] ?></td>
                                                <td><?= $api['endpoint'] ?></td>
                                                <td><?= $api['description'] ?? 'N/A' ?></td>
                                                <td><?= ($api['status'] == 1) ? 'Active' : 'Inactive' ?></td>
                                                <td class="text-center">
                                             
                                                    <button class="view-details" 
                                                        style="background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;" 
                                                        data-has_items_feild="N"  
                                                        data-doctype_id="<?= $api['doctype_id']; ?>">
                                                        Mapping
                                                    </button>
                            
                              
                                                    <?php if ($api['has_items']) : ?>
                                                        <button class="view-details" 
                                                            style="background-color: #17a2b8; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;" 
                                                            data-has_items_feild="Y" 
                                                            data-doctype_id="<?= $api['doctype_id']; ?>">
                                                            Has Items
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
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
        $("#saveMappings").attr("data-value", doctypeId);
        $("#saveMappings").attr("data-has_items_feild", has_items_feild);
        $("#documentDetailsModal").modal("show");
        $("#docTypeName").text(doctypeId);

        $.ajax({
            url: "<?= base_url('ExtractorController/getFieldDetails/'); ?>" + has_items_feild,
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
                    </tr>
                </thead>
               <tbody>`;
                fieldData.forEach((field, index) => {
                    tableHTML += `
               <tr>
                   <td>${field.temp_column}</td>
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
                   <td><input value="${field.add_condition}" class="form-control add-condition"></td>
               </tr>`;
                });
                tableHTML += `</tbody></table>`;
                $("#documentDetailsContent").html(tableHTML);
                fieldData.forEach((field, idx) => {
                    if (field.select_table) {
                        let selectTableRow = $("#documentDetailsContent tbody tr").eq(idx);
                        fetchSelectTable(field.select_table, selectTableRow.find(".select-table"));
                    }
                });
                fieldData.forEach((field, idx) => {
                    if (field.relation_column) {
                        let relationColumnRow = $("#documentDetailsContent tbody tr").eq(idx);
                        fetchSelectTableColumns(field.select_table, relationColumnRow.find(".relation-column"), field.relation_column);
                    }
                });
                fieldData.forEach((field, idx) => {
                    if (field.relation_value) {
                        let relationValueRow = $("#documentDetailsContent tbody tr").eq(idx);
                        fetchSelectTableValue(field.select_table, relationValueRow.find(".relation-value"), field.relation_value);
                    }
                });
                fieldData.forEach((field, idx) => {
                    let selectTableRow = $("#documentDetailsContent tbody tr").eq(idx);
                    let punchTable = field.punch_table ? field.punch_table : ""; 
                    fetchSelectTable(punchTable, selectTableRow.find(".punch-table"));
                });
                fieldData.forEach((field, idx) => {
                    if (field.punch_column) {
                        let punchRow = $("#documentDetailsContent tbody tr").eq(idx);
                        fetchPunchColumns(field.punch_table, punchRow.find(".punch-column"), field.punch_column);
                    }
                });
            },
            error: function () {
                $("#documentDetailsContent").html('<p class="text-danger text-center">Error loading details.</p>');
            },
        });
    });
    $(document).on("change", ".input-type", function () {
        let selectedType = $(this).val();
        let row = $(this).closest("tr");
        let tableDropdown = row.find(".select-table");

        if (selectedType === "select") {
            tableDropdown.prop("disabled", false);
            fetchSelectTable("", tableDropdown);
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
    function fetchSelectTable(selectedTable = "", tableDropdown) {
        $.ajax({
            url: "<?= base_url('ExtractorController/getAllTables'); ?>",
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
            url: "<?= base_url('ExtractorController/getTableColumns'); ?>",
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
            url: "<?= base_url('ExtractorController/getTableColumns'); ?>",
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
            url: "<?= base_url('ExtractorController/getPunchTableColumns'); ?>",
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
            url: "<?= base_url('ExtractorController/saveFieldMappings'); ?>",
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
                    }, 10000);
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
                url: "<?= base_url('ExtractorController/getPunchTableColumns'); ?>",
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
