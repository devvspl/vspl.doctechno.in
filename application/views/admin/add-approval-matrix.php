<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-plus"></i> Create New Approval Rule</h3>
                    </div>
                    <div class="box-body pb-0">
                        <form id="approvalRuleForm">
                            <input type="hidden" id="id" name="id">
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Core Details: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Core parameters defining
                                            the scope and nature of this approval rule.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Function</label>
                                                <select style="width:75%" class="form-control select2" id="function"
                                                    name="function">
                                                    <option value="">Select function</option>
                                                </select>
                                                <span class="error" id="functionError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Vertical</label>
                                                <select style="width:75%" class="form-control select2" id="vertical"
                                                    name="vertical">
                                                    <option value="">Select vertical</option>
                                                </select>
                                                <span class="error" id="verticalError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Department</label>
                                                <select style="width:75%" class="form-control select2" id="department"
                                                    name="department">
                                                    <option value="">Select department</option>
                                                </select>
                                                <span class="error" id="departmentError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Sub Dept.</label>
                                                <select style="width:75%" class="form-control select2"
                                                    id="subDepartment" name="sub_department[]" multiple>
                                                    <option value="">Select sub-department</option>
                                                </select>
                                                <span class="error" id="subDepartmentError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Scope Parameters: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Define the operational
                                            context for which this rule applies.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Crop</label>
                                                <select style="width:75%" class="form-control select2" id="crop"
                                                    name="crop[]" multiple>
                                                    <option value="">Select crop</option>
                                                </select>
                                                <span class="error" id="cropError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Activity</label>
                                                <select style="width:75%" class="form-control select2" id="activity"
                                                    name="activity[]" multiple>
                                                    <option value="">Select activity</option>
                                                </select>
                                                <span class="error" id="activityError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Location</label>
                                                <select style="width:75%" class="form-control select2" id="location"
                                                    name="location[]" multiple>
                                                    <option value="">Select location</option>
                                                </select>
                                                <span class="error" id="locationError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Organizational Location: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Specify the regional and
                                            business unit applicability.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Region</label>
                                                <select style="width:75%" class="form-control select2" id="region"
                                                    name="region">
                                                    <option value="">Select region</option>
                                                </select>
                                                <span class="error" id="regionError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Zone</label>
                                                <select style="width:75%" class="form-control select2" id="zone"
                                                    name="zone">
                                                    <option value="">Select zone</option>
                                                </select>
                                                <span class="error" id="zoneError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Business Unit</label>
                                                <select style="width:75%" class="form-control select2" id="businessUnit"
                                                    name="business_unit">
                                                    <option value="">Select business unit</option>
                                                </select>
                                                <span class="error" id="businessUnitError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Financial Parameters & Bill Type:
                                        <span class="text-muted mb-0" style="font-size: 12px;">Set financial thresholds
                                            and relevant document types.</span>
                                    </h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Ledger</label>
                                                <select style="width:75%" class="form-control select2" id="ledger"
                                                    name="ledger">
                                                    <option value="">Select ledger</option>
                                                </select>
                                                <span class="error" id="ledgerError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Subledger</label>
                                                <select style="width:75%" class="form-control select2" id="subledger"
                                                    name="subledger">
                                                    <option value="">Select subledger</option>
                                                </select>
                                                <span class="error" id="subledgerError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Bill Type</label>
                                                <select style="width:75%" class="form-control select2" id="billType"
                                                    name="bill_type">
                                                    <option value="">Select bill type</option>
                                                </select>
                                                <span class="error" id="billTypeError"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <label style="width: 20%;font-size: 12px;">Min Amount</label>
                                                <input style="width: 80%;" type="number" class="form-control"
                                                    id="minAmount" name="min_amount" placeholder="Enter Min Amount"
                                                    min="0">
                                                <span class="error" id="minAmountError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <label style="width: 20%;font-size: 12px;">Max Amount</label>
                                                <input style="width: 80%;" type="number" class="form-control"
                                                    id="maxAmount" name="max_amount" placeholder="Enter Max Amount"
                                                    min="0">
                                                <span class="error" id="maxAmountError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Approval Levels: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Assign the sequence of
                                            approvers for this rule.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L1 Approver</label>
                                                <select style="width:75%" class="form-control select2" id="l1Approver"
                                                    name="l1_approver">
                                                    <option value="">Select L1 Approver</option>
                                                </select>
                                                <span class="error" id="l1ApproverError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L2 Approver</label>
                                                <select style="width:75%" class="form-control select2" id="l2Approver"
                                                    name="l2_approver">
                                                    <option value="">Select L2 Approver</option>
                                                </select>
                                                <span class="error" id="l2ApproverError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">L3 Approver</label>
                                                <select style="width:75%" class="form-control select2" id="l3Approver"
                                                    name="l3_approver">
                                                    <option value="">Select L3 Approver</option>
                                                </select>
                                                <span class="error" id="l3ApproverError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Validity Period: <span
                                            class="text-muted mb-0" style="font-size: 12px;">Specify the dates when this
                                            approval rule will be active.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid From</label>
                                                <input type="date" class="form-control" id="validFrom"
                                                    name="valid_from">
                                                <span class="error" id="validFromError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid To</label>
                                                <input type="date" class="form-control" id="validTo" name="valid_to">
                                                <span class="error" id="validToError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default" onclick="resetForm()">Reset</button>
                                <button type="submit" class="btn btn-primary pull-right">Save Rule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2({ placeholder: "Select an option", allowClear: true });

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

        // Populate Ledger
        $.ajax({
            url: '<?= base_url('AdminController/getLedger') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select ledger</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.account_name}">${item.account_name}</option>`;
                });
                $('#ledger').html(options);
            }
        });

        // Populate Subledger
        $.ajax({
            url: '<?= base_url('AdminController/getSubledger') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select subledger</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#subledger').html(options);
            }
        });

        // Populate Bill Type
        $.ajax({
            url: '<?= base_url('AdminController/getBillType') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select bill type</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.type_id}">${item.file_type}</option>`;
                });
                $('#billType').html(options);
            }
        });

        // Populate Approvers
        $.ajax({
            url: '<?= base_url('AdminController/getApprovers') ?>',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                let options = '<option value="">Select approver</option>';
                $.each(data, function (i, item) {
                    options += `<option value="${item.user_id}">${item.full_name}</option>`;
                });
                $('#l1Approver, #l2Approver, #l3Approver').html(options);
            }
        });

        // Form submission
        $('#approvalRuleForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('AdminController/saveApprovalMatrix') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        resetForm();
                    } else {
                        $('.error').text('');
                        $.each(response.errors, function (field, error) {
                            $(`#${field}Error`).text(error);
                        });
                    }
                },
                error: function () {
                    alert('An error occurred while saving the approval matrix.');
                }
            });
        });

        function resetForm() {
            $('#approvalRuleForm')[0].reset();
            $('.select2').val('').trigger('change');
            $('.error').text('');
        }
    });
</script>