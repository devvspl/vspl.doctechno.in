<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= !empty($unit['unit_id']) ? 'Edit Unit' : 'Add Unit' ?></h3>
                    </div>
                    <form id="unit_form"
                        action="<?= base_url('save_unit' . (!empty($unit['unit_id']) ? '/' . $unit['unit_id'] : '')) ?>"
                        name="unit_form" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>
                            <div class="form-group">
                                <label for="unit_name">Unit Name <span class="text-danger">*</span></label>
                                <input autofocus id="unit_name" required name="unit_name" type="text"
                                    class="form-control"
                                    value="<?= set_value('unit_name', !empty($unit['unit_name']) ? htmlspecialchars($unit['unit_name']) : '') ?>" />
                                <span class="text-danger"><?php echo form_error('unit_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="unit_code">Unit Code <span class="text-danger">*</span></label>
                                <input id="unit_code" required name="unit_code" type="text" class="form-control"
                                    value="<?= set_value('unit_code', !empty($unit['unit_code']) ? htmlspecialchars($unit['unit_code']) : '') ?>" />
                                <span class="text-danger"><?php echo form_error('unit_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" required id="status" class="form-control">
                                    <option value="A" <?= set_select('status', 'A', !empty($unit['status']) && $unit['status'] == 'A') ?>>Active</option>
                                    <option value="D" <?= set_select('status', 'D', !empty($unit['status']) && $unit['status'] == 'D') ?>>Deactive</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Unit List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Unit List</div>
                            <table id="unitTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Unit Name</th>
                                        <th>Unit Code</th>
                                        <th style="text-align:center;">Status</th>
                                        <th style="text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($unit_list)) { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No units found.</td>
                                        </tr>
                                    <?php } else { ?>
                                        <?php foreach ($unit_list as $unit): ?>
                                            <tr>
                                                <td class="mailbox-name"><?= htmlspecialchars($unit['unit_name']) ?></td>
                                                <td class="mailbox-name"><?= htmlspecialchars($unit['unit_code']) ?></td>
                                                <td style="text-align:center;" class="mailbox-name">
                                                    <?= $unit['status'] == 'A' ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td style="text-align:center;">
                                                    <!-- <a href="javascript:void(0)" class="btn btn-xs btn-default view-unit"
                                                        data-id="<?= $unit['unit_id'] ?>">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a> -->
                                                    <a href="<?= base_url(); ?>unit/<?php echo $unit['unit_id'] ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>delete_unit/<?php echo $unit['unit_id'] ?>"
                                                        class="btn btn-default btn-xs"
                                                        onclick="return confirm('Are you sure to delete?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitModalLabel">Unit Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 25%; text-align: left;">Unit Name</td>
                        <td style="width:75%" id="modal_unit_name"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; text-align: left;">Unit Code</td>
                        <td style="width:75%" id="modal_unit_code"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; text-align: left;">Status</td>
                        <td style="width:75%" id="modal_status_label"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#unitTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            pageLength: 10,
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> Export',
                    title: 'Unit_List_' + new Date().toISOString().slice(0, 10),
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });

        $(document).on('click', '.view-unit', function () {
            var unitId = $(this).data('id');
            $.ajax({
                url: '<?= base_url("get_unit_details/") ?>' + unitId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }
                    $('#modal_unit_name').text(response.unit_name || 'N/A');
                    $('#modal_unit_code').text(response.unit_code || 'N/A');
                    $('#modal_status_label').text(response.status_label || 'N/A');
                    $('#unitModal').modal('show');
                },
                error: function () {
                    alert('Error loading unit details.');
                }
            });
        });
    });
</script>