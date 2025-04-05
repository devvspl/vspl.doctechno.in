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
                        <h3 class="box-title">Add Region</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Region/create" id="regionform" name="regionform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Region Name</label>
                                <input autofocus="" id="region_name" name="region_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('region_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('region_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Region Code</label>
                                <input autofocus="" id="region_code" name="region_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('region_code'); ?>" />
                                <span class="text-danger"><?php echo form_error('region_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="region_numeric_code">Region Numeric Code</label>
                                <input autofocus="" id="region_numeric_code" name="region_numeric_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('region_numeric_code'); ?>" />
                                <span class="text-danger"><?php echo form_error('region_numeric_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">State</label>
                                <select name="state_id" id="state_id" class="form-control">
                                    <option value="">Select</option>
                               <?php
                                    foreach ($statelist as $key => $value) {
                                       echo "<option value='".$value['state_id']."'>".$value['state_name']."</option>";
                                    }
                               ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('state_id'); ?></span>
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
                        <h3 class="box-title titlefix">Region List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">State List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Region ID</th>
                                        <th>Region</th>
                                        <th>Code</th>
                                        <th>Numeric Code</th>
                                        <th>State</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($regionlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($regionlist as $row) {
                                        ?>
                                            <tr>
                                            <td class="mailbox-name">
                                                    <?php echo $row['region_id']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['region_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['region_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['region_numeric_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['state_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                                    <a href="<?= base_url(); ?>region/edit/<?php echo $row['region_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>region/delete/<?php echo $row['region_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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