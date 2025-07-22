<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i
                                class="fa fa-plus"></i><?= isset($matrix) ? 'Edit Approval Rule' : 'Create New Approval Rule' ?>
                        </h3>
                    </div>
                    <div class="box-body pb-0">
                        <form id="approvalRuleForm">
                            <input type="hidden"
                                value="<?= isset($matrix['id']) ? htmlspecialchars($matrix['id']) : '' ?>" id="id"
                                name="id"
                                value="<?= isset($matrix['rule_id']) ? htmlspecialchars($matrix['rule_id']) : '' ?>">
                            <div class="box mb-0">
                                <div class="box-header">
                                    <h4 class="box-title" style="color: #1b98ae;">Core Details: <spanclass="text-muted
                                            mb-0" style="font-size: 12px;">Core parameters defining the scope and nature
                                            of this approval rule.</span></h4>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Function <span
                                                        class="text-danger">*</span></label>
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
                                                <label style="width: 25%;font-size: 12px;">Vertical <span
                                                        class="text-danger">*</span></label>
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
                                                <label style="width: 25%;font-size: 12px;">Department <span
                                                        class="text-danger">*</span></label>
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
                                                <label style="width: 25%;font-size: 12px;">Bill Type <span
                                                        class="text-danger">*</span></label>
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
                                                    min="0"
                                                    value="<?= isset($matrix['amount_min']) ? htmlspecialchars($matrix['amount_min']) : '' ?>">
                                                <span class="error" id="minAmountError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="display: flex; align-items: center; gap: 10px;">
                                                <label style="width: 20%;font-size: 12px;">Max Amount</label>
                                                <input style="width: 80%;" type="number" class="form-control"
                                                    id="maxAmount" name="max_amount" placeholder="Enter Max Amount"
                                                    min="0"
                                                    value="<?= isset($matrix['amount_max']) ? htmlspecialchars($matrix['amount_max']) : '' ?>">
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
                                                <label style="width: 25%;font-size: 12px;">L1 Approver <span
                                                        class="text-danger">*</span></label>
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
                                                <label style="width: 25%;font-size: 12px;">Validity Option</label>
                                                <div>
                                                    <label><input type="radio" name="validity_option" value="all_time"
                                                            <?php echo isset($matrix['validity_option']) && $matrix['validity_option'] === 'all_time' ? 'checked' : ''; ?>>
                                                        All Time</label>
                                                    <label style="margin-left: 10px;"><input type="radio"
                                                            name="validity_option" value="date"
                                                            <?php echo isset($matrix['validity_option']) && $matrix['validity_option'] === 'date' ? 'checked' : ''; ?>>
                                                        Select Date</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid From</label>
                                                <input type="date" class="form-control" id="validFrom" name="valid_from"
                                                    value="<?= isset($matrix['valid_from']) ? htmlspecialchars($matrix['valid_from']) : '' ?>">
                                                <span class="error" id="validFromError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"
                                                style="align-items: center;display: flex;gap: 10px;">
                                                <label style="width: 25%;font-size: 12px;">Valid To</label>
                                                <input type="date" class="form-control" id="validTo" name="valid_to"
                                                    value="<?= isset($matrix['valid_to']) ? htmlspecialchars($matrix['valid_to']) : '' ?>">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });



function loadApproverList(elementId, level = null, selectedValue = null) {
    var department = $('#department').val();
    var billType   = $('#billType').val();
    var location   = $('#location').val();

   
    if (Array.isArray(location)) {
        location = location.join(',');
    }

    $.ajax({
        url: '<?= base_url('AdminController/getApprovers') ?>',
        type: 'POST',
        data: {
            level: level,
            department: department,
            billType: billType,
            location: location
        },
        dataType: 'json',
        success: function(response) {
            let options = `<option value="">Select ${elementId.replace(/([A-Z])/g, ' $1').toLowerCase()}</option>`;
            $.each(response, function(i, item) {
                options += `<option value="${item.user_id}">${item.full_name}</option>`;
            });
            $(`#${elementId}`).html(options);

            if (selectedValue) {
                $(`#${elementId}`).val(selectedValue).trigger('change');
            }
        },
        error: function() {
            console.error(`Failed to load ${elementId} options`);
        }
    });
}


   
    function populateDropdown(url, elementId, valueField, textField, data, selectedValue = null, multiple =
        false) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                let options =
                    `<option value="">Select ${elementId.replace(/([A-Z])/g, ' $1').toLowerCase()}</option>`;
                $.each(response, function(i, item) {
                    options +=
                        `<option value="${item[valueField]}">${item[textField]}</option>`;
                });
                $(`#${elementId}`).html(options);
                if (selectedValue) {
                    if (multiple) {
                        $(`#${elementId}`).val(selectedValue.split(',')).trigger('change');
                    } else {
                        $(`#${elementId}`).val(selectedValue).trigger('change');
                    }
                }
            },
            error: function() {
                console.error(`Failed to load ${elementId} options`);
            }
        });
    }

   
    populateDropdown('<?= base_url('AdminController/getFunction') ?>', 'function', 'api_id', 'function_name', {}, '<?= isset($matrix['function']) ? $matrix['function'] : '' ?>');
    populateDropdown('<?= base_url('AdminController/getLedger') ?>', 'ledger', 'id', 'account_name', {}, '<?= isset($matrix['ledger']) ? $matrix['ledger'] : '' ?>');
    populateDropdown('<?= base_url('AdminController/getSubledger') ?>', 'subledger', 'id', 'name', {}, '<?= isset($matrix['subledger']) ? $matrix['subledger'] : '' ?>');
    populateDropdown('<?= base_url('AdminController/getBillType') ?>', 'billType', 'type_id', 'file_type', {}, '<?= isset($matrix['bill_type']) ? $matrix['bill_type'] : '' ?>');
    populateDropdown('<?= base_url('AdminController/getLocation') ?>', 'location', 'location_id', 'location_name', {}, '<?= isset($matrix['location']) ? $matrix['location'] : '' ?>', true);

   
    $('#function').change(function() {
        let function_id = $(this).val();
        populateDropdown('<?= base_url('AdminController/getVertical') ?>', 'vertical', 'api_id',
            'vertical_name', {
                function: function_id
            }, '<?= isset($matrix['vertical']) ? $matrix['vertical'] : '' ?>');
    });

   
    $('#vertical').change(function() {
        let vertical_id = $(this).val();
        let function_id = $('#function').val();
        populateDropdown('<?= base_url('AdminController/getDepartment') ?>', 'department', 'value',
            'label', {
                vertical: vertical_id,
                function: function_id
            }, '<?= isset($matrix['department']) ? $matrix['department'] : '' ?>');
        populateDropdown('<?= base_url('AdminController/getCrop') ?>', 'crop', 'api_id', 'crop_name', {
            vertical: vertical_id
        }, '<?= isset($matrix['crop']) ? $matrix['crop'] : '' ?>', true);
        populateDropdown('<?= base_url('AdminController/getRegion') ?>', 'region', 'api_id',
            'region_name', {
                vertical: vertical_id
            }, '<?= isset($matrix['region']) ? $matrix['region'] : '' ?>');
        populateDropdown('<?= base_url('AdminController/getBusinessUnit') ?>', 'businessUnit', 'api_id',
            'business_unit_name', {
                vertical: vertical_id
            }, '<?= isset($matrix['business_unit']) ? $matrix['business_unit'] : '' ?>');
    });

   
    $('#department').change(function() {
        let department_id = $(this).val();
        populateDropdown('<?= base_url('AdminController/getSubDepartment') ?>', 'subDepartment',
            'value', 'label', {
                department: department_id
            }, '<?= isset($matrix['sub_department']) ? $matrix['sub_department'] : '' ?>', true);
        populateDropdown('<?= base_url('AdminController/getActivity') ?>', 'activity', 'value',
            'label', {
                department: department_id
            }, '<?= isset($matrix['activity']) ? $matrix['activity'] : '' ?>', true);
    });

   
    $('#region').change(function() {
        let region_id = $(this).val();
        populateDropdown('<?= base_url('AdminController/getZone') ?>', 'zone', 'api_id', 'zone_name', {
            region: region_id
        }, '<?= isset($matrix['zone']) ? $matrix['zone'] : '' ?>');
    });

   
    <?php if (isset($matrix)): ?>
   
    setTimeout(function() {
        $('#function').val('<?= $matrix['function'] ?>').trigger('change');
    }, 500);

   
    setTimeout(function() {
        $('#vertical').val('<?= $matrix['vertical'] ?>').trigger('change');
    }, 1000);

   
    setTimeout(function() {
        $('#department').val('<?= $matrix['department'] ?>').trigger('change');
    }, 1500);

   
    setTimeout(function() {
        $('#region').val('<?= $matrix['sales_region'] ?>').trigger('change');
    }, 2000);
    <?php endif; ?>

    $('#department, #billType, #location').on('change', function () {
            loadApproverList('l1Approver', 1, '<?= isset($matrix['l1_approver']) ? $matrix['l1_approver'] : '' ?>');
    loadApproverList('l2Approver', 2, '<?= isset($matrix['l2_approver']) ? $matrix['l2_approver'] : '' ?>');
    loadApproverList('l3Approver', 3, '<?= isset($matrix['l3_approver']) ? $matrix['l3_approver'] : '' ?>');
    });

   
    $('#approvalRuleForm').submit(function(e) {
        e.preventDefault();
        var function_id = $('#function').val();
        var vertical_id = $('#vertical').val();
        var department_id = $('#department').val();
        var billType = $('#billType').val();
        var l1Approver = $('#l1Approver').val();

        var missingFields = [];

        if (!function_id) missingFields.push("Function");
        if (!vertical_id) missingFields.push("Vertical");
        if (!department_id) missingFields.push("Department");
        if (!billType) missingFields.push("Bill Type");
        if (!l1Approver) missingFields.push("L1 Approver");

        if (missingFields.length > 0) {
            alert("Please fill in the following required field(s):\n- " + missingFields.join("\n- "));
            return;
        }

        if (!$('#validFrom').val() && !$('#validTo').val() && !$(
                'input[name="validity_option"]:checked').val()) {
            alert('Please select a validity option or provide valid dates.');
            return;

        }

        $.ajax({
            url: '<?= base_url('save_approval_matrix') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = '<?= base_url('approval_matrix') ?>';
                } else {
                    $('.error').text('');
                    $.each(response.errors, function(field, error) {
                        $(`#${field}Error`).text(error);
                    });
                }
            },
            error: function() {
                alert('An error occurred while saving the approval matrix.');
            }
        });
    });

    $('input[name="validity_option"]').on('change', function() {
        if ($(this).val() === 'all_time') {
            $('#validFrom, #validTo').prop('disabled', true);
        } else {
            $('#validFrom, #validTo').prop('disabled', false);
        }
    });

   
    $('input[name="validity_option"]:checked').trigger('change');

    function resetForm() {
        $('#approvalRuleForm')[0].reset();
        $('.select2').val('').trigger('change');
        $('.error').text('');
        $('#id').val('');
    }
});
</script>