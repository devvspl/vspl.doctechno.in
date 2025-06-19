<?php 
$scan_id = $this->uri->segment(2);
$scan_data = $this->db->select('department_id, doc_type_id')->from('y1_scan_file')->where('scan_id', $scan_id)->get()->row_array();
$department_id = $scan_data['department_id'];
$doc_type_id = $scan_data['doc_type_id'];

$tag_control_specific = $this->db->from('tbl_tag_control')->where('document_type_id', $doc_type_id)->where('department_id', $department_id)->get()->row_array();
$has_specific_y = false;
if ($tag_control_specific) {
    $fields_to_check = ['ledger', 'subledger', 'vertical', 'activity', 'crop', 'business_unit', 'zone', 'region'];
    foreach ($fields_to_check as $field) {
        if (isset($tag_control_specific[$field]) && $tag_control_specific[$field] == 'Y') {
            $has_specific_y = true;
            break;
        }
    }
}
$tag_control = $has_specific_y ? $tag_control_specific : $this->db->from('tbl_tag_control')->where('document_type_id', 0)->where('department_id', $department_id)->get()->row_array();
$fields = [
    'ledger' => isset($tag_control['ledger']) && $tag_control['ledger'] == 'Y',
    'subledger' => isset($tag_control['subledger']) && $tag_control['subledger'] == 'Y',
    'vertical' => isset($tag_control['vertical']) && $tag_control['vertical'] == 'Y',
    'activity' => isset($tag_control['activity']) && $tag_control['activity'] == 'Y',
    'crop' => isset($tag_control['crop']) && $tag_control['crop'] == 'Y',
    'business_unit' => isset($tag_control['business_unit']) && $tag_control['business_unit'] == 'Y',
    'zone' => isset($tag_control['zone']) && $tag_control['zone'] == 'Y',
    'region' => isset($tag_control['region']) && $tag_control['region'] == 'Y'
];
?>

<style>
    .hidden-column {
        display: none;
    }
</style>

<table class="table table-bordered" id="ledgerTable">
    <thead>
        <tr>
            <th class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">Ledger</th>
            <th class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">Subledger</th>
            <th class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">Vertical</th>
            <th class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">Activity</th>
            <th class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">Crop</th>
            <th class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">Business Unit</th>
            <th class="<?= !$fields['zone'] ? 'hidden-column' : '' ?>">Zone</th>
            <th class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">Region</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="ledgerTableBody">
        <tr id="row_1">
            <td class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">
                <input type="text" name="ledger[]" id="ledger_1" class="form-control">
            </td>
            <td class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">
                <input type="text" name="subledger[]" id="subledger_1" class="form-control">
            </td>
            <td class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">
                <input type="text" name="vertical[]" id="vertical_1" class="form-control">
                <input type="hidden" name="vertical_id[]" id="vertical_id_1">
            </td>
            <td class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">
                <input type="text" name="activity[]" id="activity_1" class="form-control">
                <input type="hidden" name="activity_id[]" id="activity_id_1">
            </td>
            <td class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">
                <input type="text" name="crop[]" id="crop_1" class="form-control">
                <input type="hidden" name="crop_id[]" id="crop_id_1">
            </td>
            <td class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">
                <input type="text" name="business_unit[]" id="business_unit_1" class="form-control">
            </td>
            <td class="<?= !$fields['zone'] ? 'hidden-column' : '' ?>">
                <input type="text" name="zone[]" id="zone_1" class="form-control">
            </td>
            <td class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">
                <input type="text" name="region[]" id="region_1" class="form-control">
            </td>
            <td>
                <input type="number" name="amount[]" id="amount_1" class="form-control" min="0">
            </td>
            <td style="text-align:center">
                <button type="button" class="btn btn-success" style="margin-top: 0;padding: 0 4px;" onclick="addRow()"><i class="fa fa-plus"></i></button>
            </td>
        </tr>
    </tbody>
</table>

<script>
    let rowCount = 1;

    function addRow() {
        rowCount++;
        const newRow = `
            <tr id="row_${rowCount}">
                <td class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="ledger[]" id="ledger_${rowCount}" class="form-control">
                </td>
                <td class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="subledger[]" id="subledger_${rowCount}" class="form-control">
                </td>
                <td class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="vertical[]" id="vertical_${rowCount}" class="form-control">
                    <input type="hidden" name="vertical_id[]" id="vertical_id_${rowCount}">
                </td>
                <td class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="activity[]" id="activity_${rowCount}" class="form-control">
                    <input type="hidden" name="activity_id[]" id="activity_id_${rowCount}">
                </td>
                <td class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="crop[]" id="crop_${rowCount}" class="form-control">
                    <input type="hidden" name="crop_id[]" id="crop_id_${rowCount}">
                </td>
                <td class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="business_unit[]" id="business_unit_${rowCount}" class="form-control">
                </td>
                <td class="<?= !$fields['zone'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="zone[]" id="zone_${rowCount}" class="form-control">
                </td>
                <td class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="region[]" id="region_${rowCount}" class="form-control">
                </td>
                <td>
                    <input type="number" name="amount[]" id="amount_${rowCount}" class="form-control" min="0">
                </td>
                <td style="text-align:center">
                    <button type="button" class="btn btn-danger" style="margin-top: 0;padding: 0 4px;" onclick="removeRow(${rowCount})"><i class="fa fa-minus"></i></button>
                </td>
            </tr>
        `;
        $('#ledgerTableBody').append(newRow);
        initializeAllAutoCompleteInputs(rowCount);
        setupDependentFieldListeners(rowCount);
    }

    function removeRow(rowNo) {
        const totalRows = $('#ledgerTableBody tr').length;
        if (totalRows > 1) {
            $('#row_' + rowNo).remove();
        } else {
            alert('At least one row is required.');
        }
    }

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

    $(document).ready(function () {
        initializeAllAutoCompleteInputs(1);
        setupDependentFieldListeners(1);
    });
</script>