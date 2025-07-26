<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Document Received Report</h3>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <form method="GET" action="<?= base_url('processed'); ?>" style="margin-bottom: 15px;">
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <p style="margin-top: 5px;"><strong>Document Type:</strong></p>
                                        <select class="form-control doc-type select2" id="docType" name="doc_type_id">
                                            <option value="" selected>Select Document Type</option>
                                            <?php
                                            $selectedDocType = $this->input->get('doc_type_id') ?? '';
                                            foreach ($docTypes as $type):
                                                $isSelected = ($selectedDocType == $type->type_id) ? 'selected' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($type->type_id); ?>" <?= $isSelected; ?>>
                                                    <?= htmlspecialchars($type->file_type ?? 'Unknown'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <p style="margin-top: 5px;"><strong>Department:</strong></p>
                                        <select class="form-control select2 department" id="department"
                                            name="department_id">
                                            <option value="" selected>Select Department</option>
                                            <?php
                                            $selectedDepartment = $this->input->get('department_id') ?? '';
                                            foreach ($departments as $dept):
                                                $isSelected = ($selectedDepartment == $dept->api_id) ? 'selected' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($dept->api_id); ?>" <?= $isSelected; ?>>
                                                    <?= htmlspecialchars($dept->department_name ?? 'Unknown'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <p style="margin-top: 5px;"><strong>Sub Department:</strong></p>
                                        <select class="form-control select2" id="subdepartment"
                                            name="sub_department_id">
                                            <option value="">Select Subdepartment</option>
                                            <?php
                                            $selectedSubDepartment = $this->input->get('sub_department_id') ?? '';
                                            foreach ($subdepartments as $subdept):
                                                $isSelected = ($selectedSubDepartment == $subdept->sub_department_id) ? 'selected' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($subdept->sub_department_id); ?>"
                                                    <?= $isSelected; ?>>
                                                    <?= htmlspecialchars($subdept->sub_department_name ?? 'Unknown'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <p style="margin-top: 5px;"><strong>Location:</strong></p>
                                        <select class="form-control select2" id="location" name="location_id">
                                            <option value="">Select Location</option>
                                            <?php
                                            $selectedLocation = $this->input->get('location_id') ?? '';
                                            foreach ($locations as $loc):
                                                $isSelected = ($selectedLocation == $loc->location_id) ? 'selected' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($loc->location_id); ?>" <?= $isSelected; ?>>
                                                    <?= htmlspecialchars($loc->location_name ?? 'Unknown'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2"
                                        style="display: flex; flex-direction: row; justify-content: left; gap: 5px; margin-top: 32px;">
                                        <input type="hidden" name="group_id" value="16">
                                        <button type="submit" class="btn btn-primary btn-sm"
                                            style="padding: 4px 12px;">Apply Filters</button>
                                        <button type="reset" class="btn btn-default btn-sm" style="padding: 4px 12px;"
                                            onclick="window.location.href='<?php echo base_url('document_received') ?>';">Clear
                                            Filters</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <table id="processedTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">S No.</th>
                                        <th style="text-align: left;">Document Name</th>
                                        <th style="text-align: center;">Document Type</th>
                                        <th style="text-align: center;">Department</th>
                                        <th style="text-align: center;">Sub Department</th>
                                        <th style="text-align: center;">Location</th>
                                        <th style="text-align: center;">Classified Date</th>
                                        <th style="text-align: center;">Received Fill Date</th>
                                        <th style="text-align: center;">Document Received Date</th>
                                        <th style="text-align: center;">File Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php $i = 1;
                                        foreach ($documents as $doc): ?>
                                            <tr>
                                                <td style="text-align: left;"><?= $i++ ?></td>
                                                <td style="text-align: left;"><?= htmlspecialchars($doc->document_name ?? '') ?>
                                                </td>
                                                <td style="text-align: center;"><?= htmlspecialchars($doc->file_type ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->department_name ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->sub_department_name ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->location_name ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->classified_date ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->verified_date ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->document_received_date ?? '') ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?php
                                                    if ($doc->is_document_verified == 'Y') {
                                                        echo '<span class="label label-success">Received</span>';
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-primary btn-sm view-details"
                                                            data-scan-id="<?= $doc->scan_id; ?>" title="View Details">
                                                            <i class="fa fa-check-square-o"></i>
                                                        </button>
                                                    <?php }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No records found</td>
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
<div class="modal fade" id="documentDetailsModal" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Details</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div id="documentDetailsContent">
                    <p class="text-center">Loading details...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#location").select2();
        $("#docType").select2();
        $("#department").select2();
        $("#subdepartment").select2();
        $("#processedTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> Export',
                    title: 'Classified_List_' + new Date().toISOString().slice(0, 10),
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });
        $(document).on('change', '#department', function (e) {
            var department_id = $(this).val();
            console.log("Selected Department ID:", department_id);
            var subdepartment_select = $('#subdepartment');
            subdepartment_select.html('<option value="">Select Subdepartment</option>');
            if (department_id) {
                $.ajax({
                    url: '<?= base_url("extract/ExtractorController/getSubdepartments"); ?>',
                    type: 'POST',
                    data: { department_id: department_id },
                    dataType: 'json',
                    success: function (data) {
                        $.each(data, function (index, item) {
                            subdepartment_select.append(
                                $('<option>').val(item.sub_department_id).text(item.sub_department_name)
                            );
                        });
                    },
                    error: function () {
                        alert('Error fetching subdepartments');
                    }
                });
            }

        });
        $(document).on("click", ".change-request", function () {
            let $button = $(this);
            let scanId = $button.data("scan-id");
            if (!confirm("Are you sure you want to submit this change request?")) {
                return;
            }
            $button.prop("disabled", true).text("Please wait...");
            $.ajax({
                url: "<?= base_url('extract/ExtractorController/changeRequest'); ?>",
                type: "POST",
                data: { scan_id: scanId },
                success: function (response) {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === "success") {
                        alert(jsonResponse.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        alert("Request failed: " + jsonResponse.message);
                        $button.prop("disabled", false).text("Change Request");
                    }
                },
                error: function () {
                    alert("Error fetching details.");
                    $button.prop("disabled", false).text("Change Request");
                },
            });
        });
        $(".view-details").on("click", function () {
            let scanId = $(this).data("scan-id");
            $("#documentDetailsModal").modal("show");
            $.ajax({
                url: "<?= base_url('scan_document_details'); ?>",
                type: "POST",
                data: { scan_id: scanId },
                beforeSend: function () {
                    $("#documentDetailsContent").html('<p class="text-center">Loading details...</p>');
                },
                success: function (response) {
                    $("#documentDetailsContent").html(response);
                },
                error: function () {
                    $("#documentDetailsContent").html('<p class="text-danger text-center">Error loading details.</p>');
                },
            });
        });
        $(document).on("click", ".update-received-btn", function () {
            let $button = $(this);
            let scanId = $button.data("scan-id");
            let received_date = $("#received_date_" + scanId).val();
            if (!received_date) {
                alert("Please select a received date.");
                return;
            }
            $button.prop("disabled", true).text("Please wait...");
            $.ajax({
                url: "<?= base_url('update_received_status'); ?>",
                type: "POST",
                data: {
                    scan_id: scanId,
                    received_date: received_date
                },
                success: function (response) {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === "success") {
                        alert(jsonResponse.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        alert("Request failed: " + jsonResponse.message);
                        $button.prop("disabled", false).text("Update");
                    }
                },
                error: function () {

                },
            });
        });
    });
</script>