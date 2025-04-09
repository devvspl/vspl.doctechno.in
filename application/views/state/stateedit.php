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
                        <h3 class="box-title">Update State</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>State/update/<?= $state_id ?>" id="stateform" name="stateform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">State Name</label>
                                <input autofocus="" id="state_name" name="state_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('state_name', $state['state_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('state_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">State Code</label>
                                <input autofocus="" id="state_code" name="state_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('state_code', $state['state_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('state_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="state_numeric_code">State Numeric Code</label>
                                <input autofocus="" id="state_numeric_code" name="state_numeric_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('state_numeric_code', $state['state_numeric_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('state_numeric_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($countrylist as $key => $value) {
                                        if ($value['country_id'] == $state['country_id']) {
                                            echo "<option value='" . $value['country_id'] . "' selected>" . $value['country_name'] . "</option>";
                                        } else {
                                            echo "<option value='" . $value['country_id'] . "' >" . $value['country_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $state['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $state['status']) == 'D') {
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
                        <h3 class="box-title titlefix">State List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>state" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">State List</div>
                            <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Code</th>
                                        <th>Numeric Code</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($statelist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($statelist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['state_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['state_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['state_numeric_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['country_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                             
                                                    <a href="<?= base_url(); ?>state/delete/<?php echo $row['state_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                        $count++;
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div> <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>