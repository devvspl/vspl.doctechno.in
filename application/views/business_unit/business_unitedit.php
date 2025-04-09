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
                        <h3 class="box-title">Update Business Unit</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>Business_unit/update/<?= $business_unit_id ?>" id="business_unitform" name="business_unitform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Business Unit Name</label>
                                <input autofocus="" id="business_unit_name" name="business_unit_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('business_unit_name', $business_unit['business_unit_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('business_unit_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Business Unit Code</label>
                                <input autofocus="" id="business_unit_code" name="business_unit_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('business_unit_code', $business_unit['business_unit_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('business_unit_code'); ?></span>
                            </div>
                            <div class="form-group">
                        <label for="exampleInputEmail1">Busines Unit Group</label>
                        <select name="business_unit_group" id="business_unit_group" class="form-control">
                           <option value="">Select</option>
                           <?php
                              $selectedGroup = $business_unit['business_unit_group'];
                                   foreach ($business_unitlist as $key => $value) {
                                       $isSelected = ($selectedGroup == $value['business_unit_id']) ? 'selected' : '';
                                      echo "<option value='".$value['business_unit_id']."' $isSelected >".$value['business_unit_name']."</option>";
                                   }
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('business_unit_group'); ?></span>
                     </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $business_unit['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $business_unit['status']) == 'D') {
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
                        <h3 class="box-title titlefix">Business Unit List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>business_unit" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Business Unit List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Business Unit</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($business_unitlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($business_unitlist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['business_unit_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['business_unit_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">


                                                    <a href="<?= base_url(); ?>business_unit/delete/<?php echo $row['business_unit_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
</div><script>
    $(document).ready(function () {
       $('#business_unit_group').select2();
    });
</script>