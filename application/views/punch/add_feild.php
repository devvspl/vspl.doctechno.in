<?php
$scan_id = $this->uri->segment(2);
$scan_data = $this->db->select('department_id, doc_type_id')->from('y1_scan_file')->where('scan_id', $scan_id)->get()->row_array();
$department_id = $scan_data['department_id'];
$doc_type_id = $scan_data['doc_type_id'];

$tag_control_specific = $this->db->from('tbl_tag_control')->where('document_type_id', $doc_type_id)->where('department_id', $department_id)->get()->row_array();
$has_specific_y = false;
if ($tag_control_specific) {
    $fields_to_check = [
        'ledger',
        'subledger',
        'vertical',
        'department',
        'sub_department',
        'activity',
        'crop',
        'business_unit',
        'region',
        'sales_region',
        'sales_zone',
        'production_zone',
        'season',
        'acrage',
        'location'
    ];
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
    'department' => isset($tag_control['department']) && $tag_control['department'] == 'Y',
    'sub_department' => isset($tag_control['sub_department']) && $tag_control['sub_department'] == 'Y',
    'activity' => isset($tag_control['activity']) && $tag_control['activity'] == 'Y',
    'crop' => isset($tag_control['crop']) && $tag_control['crop'] == 'Y',
    'business_unit' => isset($tag_control['business_unit']) && $tag_control['business_unit'] == 'Y',
    'territory' => isset($tag_control['territory']) && $tag_control['territory'] == 'Y',
    'region' => isset($tag_control['region']) && $tag_control['region'] == 'Y',
    'sales_region' => isset($tag_control['sales_region']) && $tag_control['sales_region'] == 'Y',
    'sales_zone' => isset($tag_control['sales_zone']) && $tag_control['sales_zone'] == 'Y',
    'production_zone' => isset($tag_control['production_zone']) && $tag_control['production_zone'] == 'Y',
    'season' => isset($tag_control['season']) && $tag_control['season'] == 'Y',
    'acrage' => isset($tag_control['acrage']) && $tag_control['acrage'] == 'Y',
    'location' => isset($tag_control['location']) && $tag_control['location'] == 'Y'
];


$this->db->select('aid.*, mad.account_name, mcc.name AS subledger, cv.vertical_name, cd.department_name, csd.sub_department_name, ca.activity_name, cc.crop_name, cbu.business_unit_name, cr.region_name as sales_region_name, mwl.location_name, scz.zone_name as sales_zone_name, pcz.zone_name as production_zone_name, ct.territory_name');
$this->db->from('y1_tbl_additional_information_details aid');
$this->db->join('master_account_ledger mad', 'mad.id = aid.ledger_id', 'left');
$this->db->join('master_cost_center mcc', 'mcc.id = aid.subledger_id', 'left');
$this->db->join('core_vertical cv', 'cv.api_id = aid.vertical_id', 'left');
$this->db->join('core_department cd', 'cd.api_id = aid.department_id', 'left');
$this->db->join('core_sub_department csd', 'csd.api_id = aid.sub_department_id', 'left');
$this->db->join('core_activity ca', 'ca.api_id = aid.activity_id', 'left');
$this->db->join('core_crop cc', 'cc.api_id = aid.crop_id', 'left');
$this->db->join('core_business_unit cbu', 'cbu.api_id = aid.business_unit_id', 'left');
$this->db->join('core_region cr', 'cr.api_id = aid.sales_region_id', 'left');
$this->db->join('core_zone scz', 'scz.api_id = aid.sales_zone_id', 'left');
$this->db->join('core_zone pcz', 'pcz.api_id = aid.production_zone_id', 'left');
$this->db->join('core_territory ct', 'ct.api_id = aid.territory_id', 'left');
$this->db->join('master_work_location mwl', 'mwl.location_id = aid.location_id', 'left');
$this->db->where('scan_id', $scan_id);
$query = $this->db->get();
$existing_data = $query->result_array();
$rowCount = 1;
?>
<table class="table table-bordered" id="ledgerTable">
    <thead>
        <tr>
            <th>Sr No</th>
            <th class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">Ledger</th>
            <th class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">Subledger</th>
            <th class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">Vertical</th>
            <th class="<?= !$fields['department'] ? 'hidden-column' : '' ?>">Department</th>
            <th class="<?= !$fields['sub_department'] ? 'hidden-column' : '' ?>">Sub Department</th>
            <th class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">Activity</th>
            <th class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">Crop</th>
            <th class="<?= !$fields['season'] ? 'hidden-column' : '' ?>">Season</th>
            <th class="<?= !$fields['acrage'] ? 'hidden-column' : '' ?>">Acrage</th>
            <th class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">Business Unit</th>
            <th class="<?= !$fields['sales_zone'] ? 'hidden-column' : '' ?>">Sales Zone</th>
            <th class="<?= !$fields['production_zone'] ? 'hidden-column' : '' ?>">Production Zone</th>
            <th class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">Region</th>
            <th class="<?= !$fields['sales_region'] ? 'hidden-column' : '' ?>">Sales Region</th>
            <th class="<?= !$fields['territory'] ? 'hidden-column' : '' ?>">Territory</th>
            <th class="<?= !$fields['location'] ? 'hidden-column' : '' ?>">Location</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="ledgerTableBody">
        <?php if (empty($existing_data)): ?>
            <tr id="row_1">
                <td class="sr-no">1</td>
                <td class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="ledger[]" id="ledger_1" class="form-control">
                    <input type="hidden" name="ledger_id[]" id="ledger_id_1">
                </td>
                <td class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="subledger[]" id="subledger_1" class="form-control">
                    <input type="hidden" name="subledger_id[]" id="subledger_id_1">
                </td>
                <td class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="vertical[]" id="vertical_1" class="form-control">
                    <input type="hidden" name="vertical_id[]" id="vertical_id_1">
                </td>
                <td class="<?= !$fields['department'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="department[]" id="department_1" class="form-control">
                    <input type="hidden" name="department_id[]" id="department_id_1">
                </td>
                <td class="<?= !$fields['sub_department'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sub_department[]" id="sub_department_1" class="form-control">
                    <input type="hidden" name="sub_department_id[]" id="sub_department_id_1">
                </td>
                <td class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="activity[]" id="activity_1" class="form-control">
                    <input type="hidden" name="activity_id[]" id="activity_id_1">
                </td>
                <td class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="crop[]" id="crop_1" class="form-control">
                    <input type="hidden" name="crop_id[]" id="crop_id_1">
                </td>
                <td class="<?= !$fields['season'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="season[]" id="season_1" class="form-control">
                    <input type="hidden" name="season_id[]" id="season_id_1">
                </td>
                <td class="<?= !$fields['acrage'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="acrage[]" id="acrage_1" class="form-control">
                    <input type="hidden" name="acrage_id[]" id="acrage_id_1">
                </td>
                <td class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="business_unit[]" id="business_unit_1" class="form-control">
                    <input type="hidden" name="business_unit_id[]" id="business_unit_id_1">
                </td>
                <td class="<?= !$fields['sales_zone'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sales_zone[]" id="sales_zone_1" class="form-control">
                    <input type="hidden" name="sales_zone_id[]" id="sales_zone_id_1">
                </td>
                <td class="<?= !$fields['production_zone'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="production_zone[]" id="production_zone_1" class="form-control">
                    <input type="hidden" name="production_zone_id[]" id="production_zone_id_1">
                </td>

                <td class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="region[]" id="region_1" class="form-control">
                    <input type="hidden" name="region_id[]" id="region_id_1">
                </td>
                <td class="<?= !$fields['sales_region'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sales_region[]" id="sales_region_1" class="form-control">
                    <input type="hidden" name="sales_region_id[]" id="sales_region_id_1">
                </td>

                <td class="<?= !$fields['territory'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="territory[]" id="territory_1" class="form-control">
                    <input type="hidden" name="territory_id[]" id="territory_id_1">
                </td>

                <td class="<?= !$fields['location'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="location[]" id="location_1" class="form-control">
                    <input type="hidden" name="location_id[]" id="location_id_1">
                </td>
                <td>
                    <input type="number" name="add_amount[]" id="amount_1" class="form-control" min="0">
                </td>
                <td style="text-align:center; display: flex; justify-content: center;">

                    <button type="button" class="btn btn-success" style="margin-top: 0; padding: 0 4px;"
                        onclick="addRow()"><i class="fa fa-plus" style="font-size:8px"></i></button>
                    <button type="button" class="btn btn-primary" style="margin-top: 0; padding: 0 4px; margin-left: 4px;"
                        onclick="cloneRow(1)"><i class="fa fa-copy" style="font-size:8px"></i></button>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($existing_data as $index => $row): ?>
                <?php $rowCount = $index + 1; ?>
                <tr id="row_<?php echo $rowCount; ?>">
                    <td class="sr-no"><?php echo $rowCount; ?></td>
                    <td class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="ledger[]" id="ledger_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['account_name'] ?? ''); ?>">
                        <input type="hidden" name="ledger_id[]" id="ledger_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['ledger_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="subledger[]" id="subledger_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['subledger'] ?? ''); ?>">
                        <input type="hidden" name="subledger_id[]" id="subledger_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['subledger_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="vertical[]" id="vertical_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['vertical_name'] ?? ''); ?>">
                        <input type="hidden" name="vertical_id[]" id="vertical_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['vertical_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['department'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="department[]" id="department_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['department_name'] ?? ''); ?>">
                        <input type="hidden" name="department_id[]" id="department_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['department_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['sub_department'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="sub_department[]" id="sub_department_<?php echo $rowCount; ?>"
                            class="form-control" value="<?php echo htmlspecialchars($row['sub_department_name'] ?? ''); ?>">
                        <input type="hidden" name="sub_department_id[]" id="sub_department_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['sub_department_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="activity[]" id="activity_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['activity_name'] ?? ''); ?>">
                        <input type="hidden" name="activity_id[]" id="activity_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['activity_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="crop[]" id="crop_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['crop_name'] ?? ''); ?>">
                        <input type="hidden" name="crop_id[]" id="crop_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['crop_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['season'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="season[]" id="season_<?php echo $rowCount; ?>" class="form-control" value="">
                        <input type="hidden" name="season_id[]" id="season_id_<?php echo $rowCount; ?>" value="">
                    </td>
                    <td class="<?= !$fields['acrage'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="acrage[]" id="acrage_<?php echo $rowCount; ?>" class="form-control" value="">
                        <input type="hidden" name="acrage_id[]" id="acrage_id_<?php echo $rowCount; ?>" value="">
                    </td>
                    <td class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="business_unit[]" id="business_unit_<?php echo $rowCount; ?>"
                            class="form-control" value="<?php echo htmlspecialchars($row['business_unit_name'] ?? ''); ?>">
                        <input type="hidden" name="business_unit_id[]" id="business_unit_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['business_unit_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['sales_zone'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="sales_zone[]" id="sales_zone_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['sales_zone_name'] ?? ''); ?>">
                        <input type="hidden" name="sales_zone_id[]" id="sales_zone_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['sales_zone_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['production_zone'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="production_zone[]" id="production_zone_<?php echo $rowCount; ?>"
                            class="form-control" value="<?php echo htmlspecialchars($row['production_zone_name'] ?? ''); ?>">
                        <input type="hidden" name="production_zone_id[]" id="production_zone_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['production_zone_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="region[]" id="region_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['region_name'] ?? ''); ?>">
                        <input type="hidden" name="region_id[]" id="region_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['region_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['sales_region'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="sales_region[]" id="sales_region_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['sales_region_name'] ?? ''); ?>">
                        <input type="hidden" name="sales_region_id[]" id="sales_region_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['sales_region_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['territory'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="territory[]" id="territory_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['territory_name'] ?? ''); ?>">
                        <input type="hidden" name="territory_id[]" id="territory_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['territory_id'] ?? ''); ?>">
                    </td>
                    <td class="<?= !$fields['location'] ? 'hidden-column' : '' ?>">
                        <input type="text" name="location[]" id="location_<?php echo $rowCount; ?>" class="form-control"
                            value="<?php echo htmlspecialchars($row['location_name'] ?? ''); ?>">
                        <input type="hidden" name="location_id[]" id="location_id_<?php echo $rowCount; ?>"
                            value="<?php echo htmlspecialchars($row['location_id'] ?? ''); ?>">
                    </td>
                    <td>
                        <input type="number" name="add_amount[]" id="amount_<?php echo $rowCount; ?>" class="form-control"
                            min="0" value="<?php echo htmlspecialchars($row['amount'] ?? ''); ?>">
                    </td>
                    <td style="text-align:center; display: flex; justify-content: center;">
                        <?php if ($rowCount == 1): ?>
                            <button type="button" class="btn btn-success" style="margin-top: 0; padding: 0 4px;" onclick="addRow()">
                                <i class="fa fa-plus" style="font-size:8px"></i>
                            </button>
                            <button type="button" class="btn btn-primary" style="margin-top: 0; padding: 0 4px; margin-left: 4px;"
                                onclick="cloneRow(<?php echo $rowCount; ?>)">
                                <i class="fa fa-copy" style="font-size:8px"></i>
                            </button>

                        <?php else: ?>
                            <button type="button" class="btn btn-danger" style="margin-top: 0; padding: 0 4px;"
                                onclick="removeRow(<?php echo $rowCount; ?>)">
                                <i class="fa fa-minus" style="font-size:8px"></i>
                            </button>
                            <button type="button" class="btn btn-primary" style="margin-top: 0; padding: 0 4px; margin-left: 4px;"
                                onclick="cloneRow(<?php echo $rowCount; ?>)">
                                <i class="fa fa-copy" style="font-size:8px"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $rowCount++; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
    let rowCount = <?php echo $rowCount; ?>;

    function addRow() {
        rowCount++;
        const newRow = `
            <tr id="row_${rowCount}">
                <td class="sr-no">${rowCount}</td>
                <td class="<?= !$fields['ledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="ledger[]" id="ledger_${rowCount}" class="form-control">
                    <input type="hidden" name="ledger_id[]" id="ledger_id_${rowCount}">
                </td>
                <td class="<?= !$fields['subledger'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="subledger[]" id="subledger_${rowCount}" class="form-control">
                    <input type="hidden" name="subledger_id[]" id="subledger_id_${rowCount}">
                </td>
                <td class="<?= !$fields['vertical'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="vertical[]" id="vertical_${rowCount}" class="form-control">
                    <input type="hidden" name="vertical_id[]" id="vertical_id_${rowCount}">
                </td>
                <td class="<?= !$fields['department'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="department[]" id="department_${rowCount}" class="form-control">
                    <input type="hidden" name="department_id[]" id="department_id_${rowCount}">
                </td>
                <td class="<?= !$fields['sub_department'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sub_department[]" id="sub_department_${rowCount}" class="form-control">
                    <input type="hidden" name="sub_department_id[]" id="sub_department_id_${rowCount}">
                </td>
                <td class="<?= !$fields['activity'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="activity[]" id="activity_${rowCount}" class="form-control">
                    <input type="hidden" name="activity_id[]" id="activity_id_${rowCount}">
                </td>
                <td class="<?= !$fields['crop'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="crop[]" id="crop_${rowCount}" class="form-control">
                    <input type="hidden" name="crop_id[]" id="crop_id_${rowCount}">
                </td>
                <td class="<?= !$fields['season'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="season[]" id="season_${rowCount}" class="form-control">
                    <input type="hidden" name="season_id[]" id="season_id_${rowCount}">
                </td>
                <td class="<?= !$fields['acrage'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="acrage[]" id="acrage_${rowCount}" class="form-control">
                    <input type="hidden" name="acrage_id[]" id="acrage_id_${rowCount}">
                </td>
                <td class="<?= !$fields['business_unit'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="business_unit[]" id="business_unit_${rowCount}" class="form-control">
                    <input type="hidden" name="business_unit_id[]" id="business_unit_id_${rowCount}">
                </td>
                <td class="<?= !$fields['sales_zone'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sales_zone[]" id="sales_zone_${rowCount}" class="form-control">
                    <input type="hidden" name="sales_zone_id[]" id="sales_zone_id_${rowCount}">
                </td>
                <td class="<?= !$fields['production_zone'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="production_zone[]" id="production_zone_${rowCount}" class="form-control">
                    <input type="hidden" name="production_zone_id[]" id="production_zone_id_${rowCount}">
                </td>
                <td class="<?= !$fields['region'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="region[]" id="region_${rowCount}" class="form-control">
                    <input type="hidden" name="region_id[]" id="region_id_${rowCount}">
                </td>
                <td class="<?= !$fields['sales_region'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="sales_region[]" id="sales_region_${rowCount}" class="form-control">
                    <input type="hidden" name="sales_region_id[]" id="sales_region_id_${rowCount}">
                </td>
                <td class="<?= !$fields['territory'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="territory[]" id="territory_${rowCount}" class="form-control">
                    <input type="hidden" name="territory_id[]" id="territory_id_${rowCount}">
                </td>
                <td class="<?= !$fields['location'] ? 'hidden-column' : '' ?>">
                    <input type="text" name="location[]" id="location_${rowCount}" class="form-control">
                    <input type="hidden" name="location_id[]" id="location_id_${rowCount}">
                </td>
                <td>
                    <input type="number" name="add_amount[]" id="amount_${rowCount}" class="form-control" min="0">
                </td>
                <td style="text-align:center; display: flex; justify-content: center;">
                    <button type="button" class="btn btn-danger" style="margin-top: 0; padding: 0 4px;"
                        onclick="removeRow(${rowCount})"><i class="fa fa-minus" style="font-size:8px"></i></button>
                    <button type="button" class="btn btn-primary" style="margin-top: 0; padding: 0 4px;margin-left: 4px;"
                        onclick="cloneRow(${rowCount})"><i class="fa fa-copy" style="font-size:8px"></i></button>
                </td>
            </tr>`;

        $('#ledgerTableBody').append(newRow);
        initializeAllAutoCompleteInputs(rowCount);
        setupDependentFieldListeners(rowCount);
    }

    function cloneRow(rowNo) {
        addRow();
        const $originalRow = $('#row_' + rowNo);
        const $newRow = $('#row_' + rowCount);


        $newRow.find('input[type="text"], input[type="number"], input[type="hidden"]').each(function (index) {
            const $originalInput = $originalRow.find('input[type="text"], input[type="number"], input[type="hidden"]').eq(index);
            $(this).val($originalInput.val());
        });


        initializeAllAutoCompleteInputs(rowCount);
        setupDependentFieldListeners(rowCount);
        updateSerialNumbers();
    }

    function updateSerialNumbers() {
        $('#ledgerTableBody tr').each(function (index) {
            $(this).find('.sr-no').text(index + 1);
        });
    }

    function removeRow(rowNo) {
        const totalRows = $('#ledgerTableBody tr').length;
        if (totalRows > 1) {
            if (rowNo === 1) {
                alert('Cannot remove the first row.');
                return;
            }
            $('#row_' + rowNo).remove();
            updateSerialNumbers();
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

        $(document).on('change', `#business_unit_${rowNo}`, function () {
            const business_unit_id = $(`#business_unit_id_${rowNo}`).val();
            initAutoCompleteInput(
                `#sales_zone_${rowNo}`,
                "<?= site_url('get-zone') ?>",
                null,
                `business_unit_id_${rowNo}`,
                business_unit_id
            );
            clearField(`#sales_zone_${rowNo}`, `#sales_zone_id_${rowNo}`);

            initAutoCompleteInput(
                `#production_zone_${rowNo}`,
                "<?= site_url('get-zone') ?>",
                null,
                `business_unit_id_${rowNo}`,
                business_unit_id
            );
            clearField(`#production_zone_${rowNo}`, `#production_zone_id_${rowNo}`);
        });

        $(document).on('change', `#sales_zone_${rowNo}`, function () {
            const sales_zone_id = $(`#sales_zone_id_${rowNo}`).val();
            initAutoCompleteInput(
                `#sales_region_${rowNo}`,
                "<?= site_url('get-regions') ?>",
                null,
                `sales_zone_id_${rowNo}`,
                sales_zone_id
            );
            clearField(`#sales_region_${rowNo}`, `#sales_region_id_${rowNo}`);
        });

        $(document).on('change', `#production_zone_${rowNo}`, function () {
            const production_zone_id = $(`#production_zone_id_${rowNo}`).val();
            initAutoCompleteInput(
                `#sales_region_${rowNo}`,
                "<?= site_url('get-regions') ?>",
                null,
                `production_zone_id_${rowNo}`,
                production_zone_id
            );
            clearField(`#sales_region_${rowNo}`, `#sales_region_id_${rowNo}`);
        });

        $(document).on('change', `#sales_region_${rowNo}`, function () {
            const sales_region_id = $(`#sales_region_id_${rowNo}`).val();
            initAutoCompleteInput(
                `#territory_${rowNo}`,
                "<?= site_url('get-territory') ?>",
                null,
                `sales_region_id_${rowNo}`,
                sales_region_id
            );
            clearField(`#territory_${rowNo}`, `#territory_id_${rowNo}`);
        });
    }

    function clearField(inputSelector, hiddenSelector) {
        $(inputSelector).val('');
        $(hiddenSelector).val('');
    }

    function initializeAllAutoCompleteInputs(rowNo) {
        initAutoCompleteInput(`#ledger_${rowNo}`, "<?= site_url('get-ledger') ?>");
        initAutoCompleteInput(`#subledger_${rowNo}`, "<?= site_url('get-subledger') ?>");
        initAutoCompleteInput(`#cost_center_${rowNo}`, "<?= site_url('get-cost-centers') ?>");
        initAutoCompleteInput(`#business_unit_${rowNo}`, "<?= site_url('get-business-units') ?>");
        initAutoCompleteInput(`#state_${rowNo}`, "<?= site_url('get-states') ?>");
        initAutoCompleteInput(`#location_${rowNo}`, "<?= site_url('get-locations') ?>");
        initAutoCompleteInput(`#category_${rowNo}`, "<?= site_url('get-categories') ?>");
        initAutoCompleteInput(`#debit_ac_${rowNo}`, "<?= site_url('get-debit-accounts') ?>");
        initAutoCompleteInput(`#credit_ac_${rowNo}`, "<?= site_url('get-credit-accounts') ?>");
        initAutoCompleteInput(`#payment_term_${rowNo}`, "<?= site_url('get-payment-term') ?>");
        initAutoCompleteInput(`#function_${rowNo}`, "<?= site_url('get-function') ?>");

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

        const business_unit_id = $(`#business_unit_id_${rowNo}`).val();
        if (business_unit_id) {
            initAutoCompleteInput(`#sales_zone_${rowNo}`, "<?= site_url('get-zone') ?>", null, `business_unit_id_${rowNo}`, business_unit_id);
            initAutoCompleteInput(`#production_zone_${rowNo}`, "<?= site_url('get-zone') ?>", null, `business_unit_id_${rowNo}`, business_unit_id);
        } else {
            initAutoCompleteInput(`#sales_zone_${rowNo}`, "<?= site_url('get-zone') ?>");
            initAutoCompleteInput(`#production_zone_${rowNo}`, "<?= site_url('get-zone') ?>");
        }

        const sales_zone_id = $(`#sales_zone_id_${rowNo}`).val();
        if (sales_zone_id) {
            initAutoCompleteInput(`#sales_region_${rowNo}`, "<?= site_url('get-regions') ?>", null, `sales_zone_id_${rowNo}`, sales_zone_id);
        } else {
            initAutoCompleteInput(`#sales_region_${rowNo}`, "<?= site_url('get-regions') ?>");
        }

        const production_zone_id = $(`#production_zone_id_${rowNo}`).val();
        if (production_zone_id) {
            initAutoCompleteInput(`#sales_region_${rowNo}`, "<?= site_url('get-regions') ?>", null, `production_zone_id_${rowNo}`, production_zone_id);
        } else {
            initAutoCompleteInput(`#sales_region_${rowNo}`, "<?= site_url('get-regions') ?>");
        }

        const sales_region_id = $(`#sales_region_id_${rowNo}`).val();
        if (sales_region_id) {
            initAutoCompleteInput(`#territory_${rowNo}`, "<?= site_url('get-territory') ?>", null, `sales_region_id_${rowNo}`, sales_region_id);
        } else {
            initAutoCompleteInput(`#territory_${rowNo}`, "<?= site_url('get-territory') ?>");
        }
    }

    function initAutoCompleteInput(selector, url, onSelectCallback = null, dependentFieldId = null,

        dependentFieldValue = null, staticData = null) {
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
        document.querySelectorAll('#ledgerTableBody tr').forEach((row) => {
            const rowId = row.id;
            const match = rowId.match(/row_(\d+)/);
            if (match) {
                const index = parseInt(match[1]);
                initializeAllAutoCompleteInputs(index);
                setupDependentFieldListeners(index);
            }
        });
        $(document).on('mouseenter', '#ledgerTableBody input.form-control', function () {
            $(this).attr('title', $(this).val() || 'No value');
            $(this).css({
                'background-color': '#e6f3ff',
                'border-color': '#007bff'
            });
        }).on('mouseleave', '#ledgerTableBody input.form-control', function () {
            $(this).removeAttr('title');
            $(this).css({
                'background-color': '',
                'border-color': ''
            });
        });

        $(document).on('input', '#ledgerTableBody input.form-control', function () {
            $(this).attr('title', $(this).val() || 'No value');
        });

        $(document).on('keydown', '#ledgerTableBody input.form-control', function (e) {
            const key = e.which;
            const leftArrow = 37;
            const rightArrow = 39;
            const tabKey = 9;

            if (key === leftArrow || key === rightArrow || key === tabKey) {
                e.preventDefault();

                const $currentInput = $(this);
                const $row = $currentInput.closest('tr');


                const $inputs = $row.find('td:not(.hidden-column) input.form-control');

                const $allRows = $('#ledgerTableBody tr');
                const currentRowIndex = $allRows.index($row);
                const currentIndex = $inputs.index(this);

                $currentInput.css({ 'background-color': '', 'border-color': '' }).removeAttr('title');

                let $nextInput, nextIndex;

                if (key === rightArrow || key === tabKey) {
                    nextIndex = currentIndex + 1;
                    if (nextIndex < $inputs.length) {
                        $nextInput = $inputs.eq(nextIndex);
                    } else {
                        const nextRowIndex = (currentRowIndex + 1) % $allRows.length;
                        $nextInput = $allRows.eq(nextRowIndex).find('td:not(.hidden-column) input.form-control').first();
                    }
                } else if (key === leftArrow) {
                    nextIndex = currentIndex - 1;
                    if (nextIndex >= 0) {
                        $nextInput = $inputs.eq(nextIndex);
                    } else {
                        const prevRowIndex = currentRowIndex - 1 >= 0 ? currentRowIndex - 1 : $allRows.length - 1;
                        $nextInput = $allRows.eq(prevRowIndex).find('td:not(.hidden-column) input.form-control').last();
                    }
                }

                if ($nextInput && $nextInput.length) {
                    $nextInput.focus();
                    $nextInput.css({
                        'background-color': '#e6f3ff',
                        'border-color': '#007bff'
                    });
                    $nextInput.attr('title', $nextInput.val() || 'No value');
                }
            }
        });

        $(document).on('blur', '#ledgerTableBody input.form-control', function () {
            $(this).css({
                'background-color': '',
                'border-color': ''
            });
            $(this).removeAttr('title');
        });
    });
</script>