<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-lock"></i> Tag Control Mapping</h3>
                        <div class="box-tools pull-right">
                            <select id="document_type_select" class="form-control" style="width: 300px;">
                                <?php
                                $selected_doc_type = isset($_GET['doc_type']) ? $_GET['doc_type'] : '0';
                                ?>
                                <option value="0" <?= $selected_doc_type == '0' ? 'selected' : ''; ?>>Common</option>
                                <?php if (!empty($document_type) && is_array($document_type)): ?>
                                    <?php foreach ($document_type as $doc): ?>
                                        <option value="<?= htmlspecialchars($doc['type_id']); ?>"
                                            <?= $selected_doc_type == $doc['type_id'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($doc['file_type']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tagControlTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center"
                                            style="text-align: left;background-color: #f1f1f1 !important;z-index:999">S No.</th>
                                        <th class="text-center"
                                            style="text-align: left;background-color: #f1f1f1 !important;z-index:999">Department
                                            Name</th>
                                        <?php
                                        $fields = [
                                            'ledger',
                                            'subledger',
                                            'vertical',
                                            'department',
                                            'sub_department',
                                            'activity',
                                            'crop',
                                            'season',
                                            'acrage',
                                            'business_unit',
                                            'sales_zone',
                                            'production_zone',
                                            'territory',
                                            'sales_region',
                                            'location'
                                        ];
                                        foreach ($fields as $field) {
                                            ?>
                                            <th class="text-center" style="text-align: center;width: 210px !important;">
                                                <span>
                                                    <?= ucwords(str_replace('_', ' ', $field)); ?>
                                                </span>
                                                <div>
                                                    <button class="btn btn-xs action-btn check-all-btn check-all-column"
                                                        data-field="<?= $field; ?>" title="Check All">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-xs action-btn uncheck-all-btn uncheck-all-column"
                                                        data-field="<?= $field; ?>" title="Uncheck All">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno = 1; ?>
                                    <?php foreach ($departments as $dept): ?>
                                        <tr>
                                            <td><?= $sno++; ?></td>
                                            <td>
                                                <button class="btn btn-xs action-btn check-all-btn check-all-row"
                                                    data-department-id="<?= $dept['api_id']; ?>" title="Check All">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-xs action-btn uncheck-all-btn uncheck-all-row"
                                                    data-department-id="<?= $dept['api_id']; ?>" title="Uncheck All">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <?= $dept['department_name'] ?? ''; ?>
                                            </td>
                                            <?php
                                            foreach ($fields as $field) {
                                                $is_mapped = 'N';
                                                foreach ($mappings as $map) {
                                                    if ($map['department_id'] == $dept['api_id'] && $map['document_type_id'] == $selected_doc_type) {
                                                        $is_mapped = $map[$field] ?? 'N';
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" style="text-align: center;">
                                                    <input type="checkbox" class="mapping-checkbox"
                                                        data-department-id="<?= $dept['api_id']; ?>"
                                                        data-document-type-id="<?= $selected_doc_type; ?>"
                                                        data-field="<?= $field; ?>" <?= ($is_mapped === 'Y') ? 'checked' : ''; ?>>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        const columnMinWidths = [
            '40px',   
            '250px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px',  
            '80px'   
        ];

        $('#tagControlTable thead th').each(function (index) {
            $(this).removeAttr('style');

            const minWidth = columnMinWidths[index] || '100px';
            $(this).css({
                'min-width': minWidth,
                'white-space': 'nowrap',
                'text-align': 'center'
            });

            
            if (index < 2) {
                $(this).addClass('fixed-column');
            }
        });

        
        const table = $('#tagControlTable').DataTable({
            pageLength: 25,
            ordering: false,
            searching: false,
            paging: true,
            info: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            }
        });

        
        table.rows().every(function () {
            const $cells = $(this.node()).find('td');
            $cells.eq(0).addClass('fixed-column');
            $cells.eq(1).addClass('fixed-column');
        });

        $('#document_type_select').on('change', function () {
            var docTypeId = $(this).val();
            window.location.href = '<?= base_url('tag_control'); ?>?doc_type=' + docTypeId;
        });

        function updateMapping(departmentId, documentTypeId, field, checked) {
            $.ajax({
                url: "<?= base_url('tag_control_update'); ?>",
                type: "POST",
                data: {
                    department_id: departmentId,
                    document_type_id: documentTypeId,
                    field: field,
                    checked: checked
                },
                dataType: "json",
                success: function (response) {
                    if (response.status !== 'success') {
                        alert('Failed to update mapping for ' + field);
                    } else {
                        
                    }
                },
                error: function () {
                    alert('Error occurred while updating mapping for ' + field);
                }
            });
        }

        $(document).on('change', '.mapping-checkbox', function () {
            var departmentId = $(this).data('department-id');
            var documentTypeId = $(this).data('document-type-id');
            var field = $(this).data('field');
            var checked = $(this).is(':checked') ? 'Y' : 'N';
            updateMapping(departmentId, documentTypeId, field, checked);
        });

        $(document).on('click', '.check-all-column', function () {
            var field = $(this).data('field');
            var $checkboxes = $('.mapping-checkbox[data-field="' + field + '"]');

            $checkboxes.prop('checked', true);

            $checkboxes.each(function () {
                var departmentId = $(this).data('department-id');
                var documentTypeId = $(this).data('document-type-id');
                updateMapping(departmentId, documentTypeId, field, 'Y');
            });
        });

        $(document).on('click', '.uncheck-all-column', function () {
            var field = $(this).data('field');
            var $checkboxes = $('.mapping-checkbox[data-field="' + field + '"]');

            $checkboxes.prop('checked', false);

            $checkboxes.each(function () {
                var departmentId = $(this).data('department-id');
                var documentTypeId = $(this).data('document-type-id');
                updateMapping(departmentId, documentTypeId, field, 'N');
            });
        });

        $(document).on('click', '.check-all-row', function () {
            var departmentId = $(this).data('department-id');
            var $checkboxes = $(this).closest('tr').find('.mapping-checkbox');

            $checkboxes.prop('checked', true);

            $checkboxes.each(function () {
                var field = $(this).data('field');
                var documentTypeId = $(this).data('document-type-id');
                updateMapping(departmentId, documentTypeId, field, 'Y');
            });
        });

        $(document).on('click', '.uncheck-all-row', function () {
            var departmentId = $(this).data('department-id');
            var $checkboxes = $(this).closest('tr').find('.mapping-checkbox');

            $checkboxes.prop('checked', false);

            $checkboxes.each(function () {
                var field = $(this).data('field');
                var documentTypeId = $(this).data('document-type-id');
                updateMapping(departmentId, documentTypeId, field, 'N');
            });
        });
    });
</script>