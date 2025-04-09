<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update Cost_center</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Cost_center/update/<?= $cost_center_id ?>" id="cost_centerform" name="cost_centerform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Cost_center Name</label>
                                <input autofocus="" id="cost_center_name" name="cost_center_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('cost_center_name', $cost_center['cost_center_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('cost_center_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Cost_center Code</label>
                                <input autofocus="" id="cost_center_code" name="cost_center_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('cost_center_code', $cost_center['cost_center_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('cost_center_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="cost_center_numeric_code">Cost_center Numeric Code</label>
                                <input autofocus="" id="cost_center_numeric_code" name="cost_center_numeric_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('cost_center_numeric_code', $cost_center['cost_center_numeric_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('cost_center_numeric_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Region</label>
                                <select name="region_id" id="region_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($regionlist as $key => $value) {
                                        if ($value['region_id'] == $cost_center['region_id']) {
                                            echo "<option value='" . $value['region_id'] . "' selected>" . $value['region_name'] . "</option>";
                                        } else {
                                            echo "<option value='" . $value['region_id'] . "' >" . $value['region_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('region_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $cost_center['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $cost_center['status']) == 'D') {
                                                            echo "selected";
                                                        } ?>>Deactive</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                            </div>


                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-9">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Cost_center List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>cost_center" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Cost_center List</div>
                            <table class="table table-striped table-bordered table-hover example">
    <thead>
        <tr>
            <th>Cost_center</th>
            <th>Code</th>
            <th>Numeric Code</th>
            <th>Region</th>
            <th>Status</th>
            <th class="text-right no-print">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($cost_centerlist) && !empty($cost_centerlist)) {
            foreach ($cost_centerlist as $row) {
                // Check if $row is an array
                if (is_array($row)) {
        ?>
            <tr>
                <td class="mailbox-name">
                    <?php echo htmlspecialchars($row['cost_center_name']); ?>
                </td>
                <td class="mailbox-name">
                    <?php echo htmlspecialchars($row['cost_center_code']); ?>
                </td>
                <td class="mailbox-name">
                    <?php echo htmlspecialchars($row['cost_center_numeric_code']); ?>
                </td>
                <td class="mailbox-name">
                    <?php echo htmlspecialchars($row['region_name']); ?>
                </td>
                <td class="mailbox-name">
                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive'; ?>
                </td>
                <td class="mailbox-date pull-right no-print">
                    <a href="<?= base_url(); ?>cost_center/delete/<?php echo $row['cost_center_id']; ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        <?php
                } else {
                    // Log or handle the case where $row is not an array
                    error_log('Expected $row to be an array, but it is not.');
                }
            }
        } else {
            // Optional: Handle case where $cost_center is not an array or is empty
            echo '<tr><td colspan="6">No cost_centers found</td></tr>';
        }
        ?>
    </tbody>
</table>
<!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div> <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>