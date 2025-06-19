<div class="content-wrapper">
    <style>
        .select2-container--default.select2-container--open {
            border-color: #3c8dbc;
            width: 340px !important;
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
                                    <tr>
                                        <th>S No.</th>
                                        <th>Department Name</th>
                                        <th>Ledger</th>
                                        <th>Subledger</th>
                                        <th>Vertical</th>
                                        <th>Activity</th>
                                        <th>Crop</th>
                                        <th>Business Unit</th>
                                        <th>Zone</th>
                                        <th>Region</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno = 1; ?>
                                    <?php foreach ($departments as $dept): ?>
                                        <tr>
                                            <td><?= $sno++; ?></td>
                                            <td><?= $dept['department_name'] ?? ''; ?></td>
                                            <?php
                                            $fields = ['ledger', 'subledger', 'vertical', 'activity', 'crop', 'business_unit', 'zone', 'region'];
                                            foreach ($fields as $field) {
                                                $is_mapped = 'N'; 
                                                foreach ($mappings as $map) {
                                                    if ($map['department_id'] == $dept['api_id'] && $map['document_type_id'] == $selected_doc_type) {
                                                        $is_mapped = $map[$field] ?? 'N'; 
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <td>
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
            "searching": true,
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
        $(document).on('change', '.mapping-checkbox', function () {
            var departmentId = $(this).data('department-id');
            var documentTypeId = $(this).data('document-type-id');
            var field = $(this).data('field');
            var checked = $(this).is(':checked') ? 'Y' : 'N'; 
            console.log('Department ID:', departmentId);
            console.log('Document Type ID:', documentTypeId);
            console.log('Field:', field);
            console.log('Checked:', checked);

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
                    if (response.status === 'success') {
                        
                    } else {
                        alert('Failed to update mapping');
                    }
                },
                error: function () {
                    alert('Error occurred while updating mapping');
                }
            });
        });

    });
</script>