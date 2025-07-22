<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-dashboard"></i> Approval Rules Overview</h3>
                        <div class="box-tools">
                            <a href="<?php echo site_url('add_approval_matrix'); ?>"
                                class="btn btn-primary btn-sm pull-right">Create New Rule</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-2" style="margin-bottom: 5px;">
                                            <select style="width: 100%;" class="form-control select2 mb-10"
                                                id="function" name="function">
                                                <option value="">Select function</option>
                                            </select>
                                            <span class="error" id="functionError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10"
                                                id="vertical" name="vertical">
                                                <option value="">Select vertical</option>
                                            </select>
                                            <span class="error" id="verticalError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10"
                                                id="department" name="department">
                                                <option value="">Select department</option>
                                            </select>
                                            <span class="error" id="departmentError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10" id="region"
                                                name="region">
                                                <option value="">Select region</option>
                                            </select>
                                            <span class="error" id="regionError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10" id="zone"
                                                name="zone">
                                                <option value="">Select zone</option>
                                            </select>
                                            <span class="error" id="zoneError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10"
                                                id="businessUnit" name="business_unit">
                                                <option value="">Select business unit</option>
                                            </select>
                                            <span class="error" id="businessUnitError"></span>
                                        </div>
                                        <div class="col-md-2" style="margin-bottom: 5px;">

                                            <select style="width: 100%;" class="form-control select2 mb-10"
                                                id="location" name="location">
                                                <option value="">Select location</option>
                                            </select>
                                            <span class="error" id="locationError"></span>
                                        </div>
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-sm btn-secondary">Apply Filter</button>
                                            <button type="reset" class="btn btn-sm btn-default">Reset</button>
                                        </div>

                                    </div>
                                </form>
                                <hr>
                                <table id="approvalMatrixTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">S No.</th>
                                            <th style="text-align: center;">Role Id</th>
                                            <th style="text-align: center;">Function</th>
                                            <th style="text-align: center;">Department</th>
                                            <th style="text-align: center;">Ledger</th>
                                            <th style="text-align: center;">Amount Range</th>
                                            <th style="text-align: center;">Bill Type</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($approval_matrices)) {
                                            $s_no = 1;
                                            foreach ($approval_matrices as $matrix): ?>
                                                <tr class="matrix-row">
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($s_no++) ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['rule_id'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['function_name'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['department_name'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['ledger'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['amount_range'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?= htmlspecialchars($matrix['bill_type'] ?? '') ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php
                                                        $status_value = $matrix['status'] ?? '';
                                                        $label_class = ($status_value === '1') ? 'success' : 'danger';
                                                        $label_text = ($status_value === '1') ? 'Active' : 'Inactive';
                                                        ?>
                                                        <span class="label label-<?= $label_class ?>">
                                                            <?= $label_text ?>
                                                        </span>
                                                    </td>
                                                    <td style="text-align: center;" class="text-center">
                                                        <a style="padding: 4px 8px"
                                                            href="<?= base_url('edit_approval_matrix/' . ($matrix['rule_id'] ?? '')) ?>"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        } else { ?>
                                            <tr>
                                                <td colspan="9" class="text-center">No records found.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        $("#approvalMatrixTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            pageLength: 10,
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> Export',
                    title: 'Approval_Matrix_' + new Date().toISOString().slice(0, 10),
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });
        $('.select2').select2();
        // Populate Function dropdown
        $.ajax({
            url: '<?= base_url('AdminController/getFunction') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select function</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.api_id}">${item.function_name}</option>`;
                });
                $('#function').html(options);
            }
        });

        // Populate Vertical on Function change
        $('#function').change(function () {
            let function_id = $(this).val();
            $.ajax({
                url: '<?= base_url('AdminController/getVertical') ?>',
                type: 'POST',
                data: { function: function_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select vertical</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.api_id}">${item.vertical_name}</option>`;
                    });
                    $('#vertical').html(options).trigger('change');
                }
            });
        });

        // Populate Department and Crop on Vertical change
        $('#vertical').change(function () {
            let vertical_id = $(this).val();
            let function_id = $('#function').val();
            $.ajax({
                url: '<?= base_url('AdminController/getDepartment') ?>',
                type: 'POST',
                data: { vertical: vertical_id, function: function_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select department</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.value}">${item.label}</option>`;
                    });
                    $('#department').html(options).trigger('change');
                }
            });
            $.ajax({
                url: '<?= base_url('AdminController/getCrop') ?>',
                type: 'POST',
                data: { vertical: vertical_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select crop</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.api_id}">${item.crop_name}</option>`;
                    });
                    $('#crop').html(options);
                }
            });
            $.ajax({
                url: '<?= base_url('AdminController/getRegion') ?>',
                type: 'POST',
                data: { vertical: vertical_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select region</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.api_id}">${item.region_name}</option>`;
                    });
                    $('#region').html(options);
                }
            });
            $.ajax({
                url: '<?= base_url('AdminController/getBusinessUnit') ?>',
                type: 'POST',
                data: { vertical: vertical_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select business unit</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.api_id}">${item.business_unit_name}</option>`;
                    });
                    $('#businessUnit').html(options);
                }
            });
        });

        // Populate Subdepartment and Activity on Department change
        $('#department').change(function () {
            let department_id = $(this).val();
            $.ajax({
                url: '<?= base_url('AdminController/getSubDepartment') ?>',
                type: 'POST',
                data: { department: department_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select sub-department</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.value}">${item.label}</option>`;
                    });
                    $('#subDepartment').html(options);
                }
            });
            $.ajax({
                url: '<?= base_url('AdminController/getActivity') ?>',
                type: 'POST',
                data: { department: department_id },
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select activity</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.value}">${item.label}</option>`;
                    });
                    $('#activity').html(options);
                }
            });
        });

        // Populate Location
        $.ajax({
            url: '<?= base_url('AdminController/getLocation') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select location</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.location_id}">${item.location_name}</option>`;
                });
                $('#location').html(options);
            }
        });

        // Populate Zone
        $('#region').change(function () {
            let region_id = $(this).val();
            $.ajax({
                url: '<?= base_url('AdminController/getZone') ?>',
                data: { region: region_id },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Select zone</option>';
                    $.each(data, function (i, item) {
                        options += `<option value="${item.api_id}">${item.zone_name}</option>`;
                    });
                    $('#zone').html(options);
                }
            });
        });
    });
</script>