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
                        <h3 class="box-title">Update Department</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Department/update/<?= $department_id ?>" id="departmentform" name="departmentform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Department Name</label>
                                <input autofocus="" id="department_name" name="department_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('department_name', $department['department_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('department_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Department Code</label>
                                <input autofocus="" id="department_code" name="department_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('department_code', $department['department_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('department_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company</label>
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($companylist as $key => $value) {
                                        if ($value['firm_id'] == $department['company_id']) {
                                            echo "<option value='" . $value['firm_id'] . "' selected>" . $value['firm_name'] . "</option>";
                                        } else {
                                            echo "<option value='" . $value['firm_id'] . "' >" . $value['firm_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('company_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $department['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $department['status']) == 'D') {
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
                        <h3 class="box-title titlefix">department List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>department" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">department List</div>
                            <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                    <tr>
                                        <th>department</th>
                                        <th>Code</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($departmentlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($departmentlist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['department_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['department_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['firm_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                             
                                                    <a href="<?= base_url(); ?>department/delete/<?php echo $row['department_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
<script>
    $(document).ready(function () {
       $('#company_id').select2();
    });
</script>