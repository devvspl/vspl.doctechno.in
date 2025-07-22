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
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update File</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>master/FileController/update/<?= $file_id ?>" id="fileform" name="fileform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">File Name</label>
                                <input autofocus="" id="file_name" name="file_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('file_name', $file['file_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('file_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">File Code</label>
                                <input autofocus="" id="file_code" name="file_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('file_code', $file['file_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('file_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company</label>
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($companylist as $key => $value) {
                                        if ($value['firm_id'] == $file['company_id']) {
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
                                    <option value="A" <?php if (set_value('status', $file['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $file['status']) == 'D') {
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
                <div class="box" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">File List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>File" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">department List</div>
                            <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Code</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($filelist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($filelist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['file_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['file_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['firm_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                             
                                                    <a href="<?= base_url(); ?>file/delete/<?php echo $row['file_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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