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
                        <h3 class="box-title">Update Business Entity</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>master/BusinessEntityController/update/<?= $business_entity_id ?>" id="business_entityform" name="business_entityform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Business Entity Name</label>
                                <input autofocus="" id="business_entity_name" name="business_entity_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('business_entity_name', $business_entity['business_entity_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('business_entity_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Business Entity Code</label>
                                <input autofocus="" id="focus_code" name="focus_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('focus_code', $business_entity['focus_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('focus_code'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Business Entity Category</label>
                                <select name="business_entity_category_id" id="business_entity_category_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                $selectedCategoryId = $business_entity['business_entity_category_id'];
                                foreach ($business_entityCategorylist as $key => $value) {
                                    $isSelected = ($selectedCategoryId == $value['business_entity_category_id']) ? 'selected' : '';
                                    echo "<option value='".$value['business_entity_category_id']."' $isSelected>".$value['business_entity_category_name']."</option>";
                                }
                                ?>

                                </select>
                                <span class="text-danger"><?php echo form_error('business_entity_category_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $business_entity['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $business_entity['status']) == 'D') {
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
                        <h3 class="box-title titlefix">Business Entity List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>business_entity" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Business Entity List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Business Entity</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($business_entitylist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($business_entitylist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['business_entity_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['focus_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">


                                                    <a href="<?= base_url(); ?>business_entity/delete/<?php echo $row['business_entity_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
       $('#business_entity_category_id').select2();
    });
</script>