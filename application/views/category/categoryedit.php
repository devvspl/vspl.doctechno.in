<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" >
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Horizontal Form -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update Category</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Category/update/<?= $category_id ?>" id="categoryform" name="categoryform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Category Name</label>
                                <input autofocus="" id="category_name" name="category_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('category_name', $category['category_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('category_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country Code</label>
                                <input autofocus="" id="category_code" name="category_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('category_code', $category['category_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('category_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $category['status']) == 'A') {
                                                                echo "selected";
                                                            } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $category['status']) == 'D') {
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
                        <h3 class="box-title titlefix">Category List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url();?>category" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Category List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($categorylist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($categorylist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['category_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['category_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    
                                                   
                                                    <a href="<?= base_url(); ?>category/delete/<?php echo $row['category_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete?');">
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