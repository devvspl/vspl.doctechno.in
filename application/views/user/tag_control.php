<div class="content-wrapper">
    <style>
        .select2-container--default.select2-container--open {
            border-color: #17A2B8;
            width: 340px !important;
        }

        .action-btn {
            margin: 0 2px;
            padding: 1px 4px;
            font-size: 10px;
            line-height: 1.2;
            color: #333333;
        }

        .check-all-btn {
            background-color: #B2DFDB;
            /* Light teal to complement #17A2B8 */
            border-color: #B2DFDB;
        }

        .check-all-btn:hover {
            background-color: #A3D8D1;
            border-color: #A3D8D1;
        }

        .uncheck-all-btn {
            background-color: #F8D7DA;
            /* Light coral to complement the theme */
            border-color: #F8D7DA;
        }

        .uncheck-all-btn:hover {
            background-color: #F1C6CB;
            border-color: #F1C6CB;
        }

        .box.box-solid.box-primary {
            border-top-color: #17A2B8;
        }

        .bg-primary {
            background-color: #17A2B8 !important;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid1 box-primary">
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
                        <?php if ($this->session->flashdata('msg')): ?>
                            <div class="alert alert-success"><?= $this->session->flashdata('msg'); ?></div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table id="tagControlTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <th class="text-center" style="text-align: left;">S No.</th>
                                        <th class="text-center" style="text-align: left;">Department Name</th>
                                        <?php
                                        $fields = ['ledger', 'subledger', 'vertical', 'activity', 'crop', 'business_unit', 'zone', 'region'];
                                        foreach ($fields as $field) {
                                            ?>
                                            <th class="text-center" style="text-align: center;">
                                                <span>
                                                    <?= ucfirst(str_replace('_', ' ', $field)); ?>
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
        $('#tagControlTable').DataTable({
            "pageLength": 25,
            "ordering": false,
            "searching": false,
            "paging": true,
            "info": true,
            fixedColumns: {
                leftColumns: 1
            }
        });

        $('#document_type_select').on('change', function () {
            var docTypeId = $(this).val();
            window.location.href = '<?= base_url('master/UserController/tagControl'); ?>?doc_type=' + docTypeId;
        });

        // Function to update mapping via AJAX
        function updateMapping(departmentId, documentTypeId, field, checked) {
            $.ajax({
                url: "<?= base_url('master/UserController/updateTagMapping'); ?>",
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
                    }
                },
                error: function () {
                    alert('Error occurred while updating mapping for ' + field);
                }
            });
        }

        // Handle individual checkbox changes
        $(document).on('change', '.mapping-checkbox', function () {
            var departmentId = $(this).data('department-id');
            var documentTypeId = $(this).data('document-type-id');
            var field = $(this).data('field');
            var checked = $(this).is(':checked') ? 'Y' : 'N';
            updateMapping(departmentId, documentTypeId, field, checked);
        });

        // Handle column-wise "Check All"
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

        // Handle column-wise "Uncheck All"
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

        // Handle row-wise "Check All"
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

        // Handle row-wise "Uncheck All"
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