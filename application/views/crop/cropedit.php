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
                        <h3 class="box-title">Update Crop</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Crop/update/<?= $crop_id ?>" id="cropform" name="cropform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Name</label>
                                <input autofocus="" id="crop_name" name="crop_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('crop_name', $crop['crop_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('crop_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Code</label>
                                <input autofocus="" id="crop_code" name="crop_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('crop_code', $crop['crop_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('crop_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Crop Category</label>
                                <select name="crop_category_id" id="crop_category_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                $selectedCategoryId = $crop['crop_category_id'];
                                foreach ($cropCategorylist as $key => $value) {
                                    // Check if the current category is selected
                                    $isSelected = ($selectedCategoryId == $value['crop_category_id']) ? 'selected' : '';
                                    echo "<option value='".$value['crop_category_id']."' $isSelected>".$value['crop_category_name']."</option>";
                                }
                                ?>

                                </select>
                                <span class="text-danger"><?php echo form_error('crop_category_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $crop['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $crop['status']) == 'D') {
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
                        <h3 class="box-title titlefix">Crop List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>crop" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Crop List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Crop</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Delete</th>
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
                                                    <?php echo $row['crop_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['crop_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">


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