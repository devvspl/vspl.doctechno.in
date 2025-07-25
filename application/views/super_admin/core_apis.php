<div class="content-wrapper" >
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Core API's</h3>
                        <div class="box-tools">
                            <button id="fetchApis" class="btn btn-sm btn-primary">Fetch APIs</button>
                            <button id="syncApis" class="btn btn-sm btn-success">Sync APIs</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div id="syncedDataList"
                                style="display:none; margin-bottom: 10px; margin-top: 10px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9;">
                                <strong>Synced Data:</strong>
                                <div id="syncList"></div>
                            </div>
                            <table id="coreAPITable" class="table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>API ID</th>
                                        <th>API Name</th>
                                        <th>Endpoint</th>
                                        <th>Description</th>
                                        <th>Table Name</th>
                                        <th>Parameters</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($api_list)): ?>
                                        <?php $i = 1;
                                        foreach ($api_list as $api): ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo htmlspecialchars($api["api_id"]); ?></td>
                                                <td><?php echo htmlspecialchars($api["api_name"] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($api["api_end_point"]); ?></td>
                                                <td><?php echo htmlspecialchars($api["description"] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($api["table_name"]); ?></td>
                                                <td><?php echo $api["parameters"]; ?></td>
                                                <td style="display: flex;justify-content: center;gap: 2px;">
                                                    <button class="btn btn-info btn-sm sync-api-btn"
                                                        data-parameters="<?php echo htmlspecialchars($api['parameters'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-api="<?php echo htmlspecialchars($api['api_end_point'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-table="<?php echo htmlspecialchars($api['table_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                        title="Sync API Data">
                                                        <i class="fa fa fa-refresh"></i>
                                                    </button>

                                                    <button class="btn btn-secondary btn-sm view-data-btn"
                                                        data-table="<?php echo htmlspecialchars($api['table_name']); ?>"
                                                        data-api="<?php echo htmlspecialchars($api['api_end_point']); ?>"
                                                        title="View API Data" style="background-color: #1873e3;color: white;">
                                                        <i class="fa fa-eye"></i>
                                                    </button>

                                                    <!-- <button class="btn btn-warning btn-sm empty-data-btn"
                                                            data-table="<?php echo htmlspecialchars($api["table_name"]); ?>"
                                                            title="Empty API Table">
                                                        <i class="fa fa-paint-brush"></i> 
                                                    </button>
                                                    <button class="btn btn-danger btn-sm drop-api-btn"
                                                            data-table="<?php echo htmlspecialchars($api["table_name"]); ?>"
                                                            title="Drop API Table">
                                                        <i class="fa fa-times-circle"></i> 
                                                    </button> -->
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr class="text-center">
                                            <td colspan="6">No API data found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <div id="apiDataModal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">API Data (<span id="dataTitle"></span>)</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered" id="apiDataTable">
                                                <thead>
                                                    <tr id="apiTableHead"></tr>
                                                </thead>
                                                <tbody id="apiTableBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            function showLoading(button) {
                // button.prop("disabled", true).data("original-text", button.text()).text("Wait...");
            }

            function hideLoading(button) {
                // button.prop("disabled", false).text(button.data("original-text"));
            }

            $("#coreAPITable").DataTable({
                paging: true,
                searching: true,
                ordering: true,
                dom: 'Bfrtip',
                pageLength: 10,
                buttons: [
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-text-o"></i> Export',
                        title: 'Core_API_' + new Date().toISOString().slice(0, 10),
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            });

            $("#fetchApis").click(function () {
                var button = $(this);
                showLoading(button);

                $.ajax({
                    url: "<?= base_url('fetch_apis') ?>",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function () {
                        alert("Failed to fetch API list.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $("#syncApis").click(function () {
                var button = $(this);
                showLoading(button);

                $.ajax({
                    url: "<?= base_url('sync_apis') ?>",
                    type: "GET",
                    dataType: "text",
                    success: function (responseText) {
                        try {

                            let jsonMatches = responseText.match(/{[^}]+}/g);
                            let syncedData = [];

                            if (jsonMatches) {
                                jsonMatches.forEach(jsonString => {
                                    let response = JSON.parse(jsonString);
                                    if (response.status === "success") {
                                        syncedData.push(response.message);
                                    }
                                });
                            }

                            if (syncedData.length > 0) {
                                let syncList = "<ul>";
                                syncedData.forEach(item => {
                                    syncList += `<li>${item}</li>`;
                                });
                                syncList += "</ul>";

                                $("#syncList").html(syncList);
                                $("#syncedDataList").fadeIn();

                                setTimeout(function () {
                                    $("#syncedDataList").fadeOut();
                                }, 10000);
                            } else {
                                alert("No data was synced.");
                            }
                        } catch (error) {
                            console.error("JSON Parsing Error:", error);
                            alert("Failed to parse API response.");
                        }
                    },
                    error: function () {
                        alert("Failed to sync API data.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $(document).on("click", ".sync-api-btn", function () {
                var button = $(this);
                var apiEndPoint = button.data("api");
                var tableName = button.data("table");
                var parameters = button.data("parameters");

                var requestData = {
                    api_end_point: apiEndPoint,
                    table_name: tableName,
                    params: {}
                };


                if (parameters) {
                    var paramArray = parameters.split(",").map(param => param.trim());

                    for (var i = 0; i < paramArray.length; i++) {
                        var key = paramArray[i];
                        var userValue = prompt(`Enter value for ${key}:`);

                        if (userValue !== null && userValue !== "") {
                            requestData.params[key] = userValue;
                        } else {
                            alert("Sync cancelled. Parameter is required.");
                            return;
                        }
                    }
                }

                showLoading(button);

                $.ajax({
                    url: "<?= base_url('sync_single_api') ?>",
                    type: "POST",
                    data: JSON.stringify(requestData),
                    contentType: "application/json",
                    dataType: "json",
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function () {
                        alert("Failed to sync API.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $(document).on("click", ".view-data-btn", function () {
                var button = $(this);
                showLoading(button);
                $("#dataTitle").text(button.data("table"));
                $.ajax({
                    url: "<?= base_url('get_api_data') ?>",
                    type: "POST",
                    data: { table_name: button.data("table") },
                    dataType: "json",
                    beforeSend: function () {
                        $("#apiTableHead, #apiTableBody").html("");
                    },
                    success: function (response) {
                        if (response.status === "success" && response.data.length > 0) {
                            var data = response.data;
                            var headers = Object.keys(data[0]);
                            var headerRow = headers.map((h) => `<th>${h}</th>`).join("");
                            $("#apiTableHead").html(headerRow);


                            var tableName = button.data("table");


                            data.sort((a, b) => {
                                const aIsEmpty = a.focus_code === null || a.focus_code === "";
                                const bIsEmpty = b.focus_code === null || b.focus_code === "";
                                if (aIsEmpty && !bIsEmpty) return -1;
                                if (!aIsEmpty && bIsEmpty) return 1;
                                return 0;
                            });


                            var emptyFocusCodeCount = data.filter(row => row.focus_code === null || row.focus_code === "").length;


                            if (emptyFocusCodeCount > 0) {
                                $("#dataTitle").append(` <small style="color: red; background: white; padding: 3px; border-radius: 5px;">Empty/Null focus_code count: ${emptyFocusCodeCount}</small>`);
                            }

                            var rows = data
                                .map((row, index) => {
                                    return `<tr data-id="${row.id || index}">` + headers.map((h) => {
                                        if (h === "focus_code") {

                                            let displayValue = (row[h] !== null && row[h] !== "") ? row[h] : "";
                                            return `<td><input type="text" class="form-control focus-code-input" data-id="${row.id || index}" data-column="${h}" value="${displayValue}"><span class="update-message text-muted small d-block mt-1"></span></td>`;
                                        }
                                        return `<td>${row[h] !== null ? row[h] : ""}</td>`;
                                    }).join("") + `</tr>`;
                                })
                                .join("");
                            $("#apiTableBody").html(rows);


                            if ($.fn.DataTable.isDataTable("#apiDataTable")) {
                                $("#apiDataTable").DataTable().clear().destroy();
                            }

                            setTimeout(function () {
                                $("#apiDataTable").DataTable({
                                    responsive: true,
                                    autoWidth: false,
                                    pageLength: 10,
                                    order: [],
                                });
                            }, 100);


                            $(".focus-code-input").on("change", function () {
                                var input = $(this);
                                var messageSpan = input.next(".update-message");
                                var newValue = input.val();
                                var rowId = input.closest("tr").data("id");
                                var column = input.data("column");


                                messageSpan.text("").removeClass("text-success text-danger");


                                $.ajax({
                                    url: "<?= base_url('update_api_data') ?>",
                                    type: "POST",
                                    data: {
                                        table_name: tableName,
                                        id: rowId,
                                        column: column,
                                        value: newValue
                                    },
                                    dataType: "json",
                                    beforeSend: function () {
                                        showLoading(input);
                                        messageSpan.text("Updating...").addClass("text-muted");
                                    },
                                    success: function (updateResponse) {
                                        if (updateResponse.status === "success") {
                                            messageSpan.text("Focus code updated successfully!").addClass("text-success").removeClass("text-muted");

                                            if (newValue !== "" && !$("#dataTitle").find("small").data("original-count")) {
                                                emptyFocusCodeCount--;
                                                $("#dataTitle").find("small").text(`Empty/Null focus_code count: ${emptyFocusCodeCount}`)
                                                    .css({
                                                        color: emptyFocusCodeCount > 0 ? "red" : "",
                                                        background: emptyFocusCodeCount > 0 ? "white" : "",
                                                        padding: emptyFocusCodeCount > 0 ? "3px" : "",
                                                        "border-radius": emptyFocusCodeCount > 0 ? "5px" : ""
                                                    });
                                            } else if (newValue === "" && !$("#dataTitle").find("small").data("original-count")) {
                                                emptyFocusCodeCount++;
                                                $("#dataTitle").find("small").text(`Empty/Null focus_code count: ${emptyFocusCodeCount}`)
                                                    .css({
                                                        color: "red",
                                                        background: "white",
                                                        padding: "3px",
                                                        "border-radius": "5px"
                                                    });
                                            }
                                        } else {
                                            messageSpan.text("Failed to update: " + (updateResponse.message || "Unknown error")).addClass("text-danger").removeClass("text-muted");
                                        }
                                    },
                                    error: function () {
                                        messageSpan.text("Error updating focus code.").addClass("text-danger").removeClass("text-muted");
                                    },
                                    complete: function () {
                                        hideLoading(input);

                                        setTimeout(() => {
                                            messageSpan.text("").removeClass("text-success text-danger");
                                        }, 5000);
                                    }
                                });
                            });

                            $("#apiDataModal").modal("show");
                        } else {

                            $("#dataTitle").append(` <small>Empty/Null focus_code count: 0</small>`);
                            alert("No data found for this API.");
                        }
                    },
                    error: function () {
                        alert("Failed to fetch API data.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $(document).on("click", ".empty-data-btn", function () {
                var button = $(this);
                if (!confirm("Are you sure you want to delete all data from this table?")) return;
                showLoading(button);

                $.ajax({
                    url: "<?= base_url('empty_table') ?>",
                    type: "POST",
                    data: { table_name: button.data("table") },
                    dataType: "json",
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function () {
                        alert("Failed to empty table.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $(document).on("click", ".drop-api-btn", function () {
                var button = $(this);
                if (!confirm("Are you sure you want to delete this API table? This action cannot be undone.")) return;
                showLoading(button);

                $.ajax({
                    url: "<?= base_url('drop_table') ?>",
                    type: "POST",
                    data: { table_name: button.data("table") },
                    dataType: "json",
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function () {
                        alert("Failed to drop API table.");
                    },
                    complete: function () {
                        hideLoading(button);
                    },
                });
            });

            $("#apiDataModal").on("hidden.bs.modal", function () {
                if ($.fn.DataTable.isDataTable("#apiDataTable")) {
                    $("#apiDataTable").DataTable().clear().destroy();
                }
                $("#apiTableHead, #apiTableBody").html("");
            });
        });
    </script>
</div>