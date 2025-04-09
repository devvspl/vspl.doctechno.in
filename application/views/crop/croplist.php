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
                        <h3 class="box-title">Add Crop</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Crop/create" id="cropform" name="cropform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Name</label>
                                <input autofocus="" id="crop_name" name="crop_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('crop_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('crop_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Code</label>
                                <input autofocus="" id="crop_code" name="crop_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('crop_code'); ?>" />
                                <span class="text-danger"><?php echo form_error('crop_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Category</label>
                                <select name="crop_category_id" id="crop_category_id" class="form-control">
                                    <option value="">Select</option>
                               <?php
                                    foreach ($cropCategorylist as $key => $value) {
                                       echo "<option value='".$value['crop_category_id']."'>".$value['crop_category_name']."</option>";
                                    }
                               ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('crop_category_id'); ?></span>
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
                        <h3 class="box-title titlefix">Crop List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Crop List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Crop Id</th>
                                        <th>Crop</th>
                                        <th>Code</th>
                                        <th>Crop Category </th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($croplist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($croplist as $row) {
                                        ?>
                                            <tr>
                                            <td class="mailbox-name">
                                                    <?php echo $row['crop_id']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['crop_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['crop_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['crop_category_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                                    <a href="<?= base_url(); ?>crop/edit/<?php echo $row['crop_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>crop/delete/<?php echo $row['crop_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
       $('#crop_category_id').select2();
    });
</script>