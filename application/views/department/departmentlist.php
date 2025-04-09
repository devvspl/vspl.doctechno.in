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
                        <h3 class="box-title">Add Department</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Department/create" id="departmentform" name="departmentform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Department Name</label>
                                <input autofocus="" id="department_name" name="department_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('department_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('department_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Department Code</label>
                                <input autofocus="" id="department_code" name="department_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('department_code'); ?>" />
                                <span class="text-danger"><?php echo form_error('department_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Comapny</label>
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select</option>
                               <?php
                                    foreach ($companylist as $key => $value) {
                                       echo "<option value='".$value['firm_id']."'>".$value['firm_name']."</option>";
                                    }
                               ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('company_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="A">Active</option>
                                    <option value="D">Deactive</option>
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
                        <h3 class="box-title titlefix">Department List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Department List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Code</th>
                                        <th>Comapny</th>
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
                                                    
                                                    <a href="<?= base_url(); ?>department/edit/<?php echo $row['department_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
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