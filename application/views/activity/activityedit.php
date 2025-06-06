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
                        <h3 class="box-title">Update Activity</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>master/ActivityController/update/<?= $activity_id ?>" id="activityform" name="activityform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Activity Name</label>
                                <input autofocus="" id="activity_name" name="activity_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('activity_name', $activity['activity_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('activity_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Activity Code</label>
                                <input autofocus="" id="activity_code" name="activity_code" placeholder="" type="text" class="form-control" value="<?php echo set_value('activity_code', $activity['activity_code']); ?>" />
                                <span class="text-danger"><?php echo form_error('activity_code'); ?></span>
                            </div>
                            <div class="form-group">
                        <label for="exampleInputEmail1">Activity Group</label>
                        <select name="activity_group" id="activity_group" class="form-control">
                           <option value="">Select</option>
                           <?php
                              $selectedGroup = $activity['activity_group'];
                                   foreach ($activitylist as $key => $value) {
                                       $isSelected = ($selectedGroup == $value['activity_group']) ? 'selected' : '';
                                      echo "<option value='".$value['activity_id']."' $isSelected >".$value['activity_name']."</option>";
                                   }
                              ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('activity_group'); ?></span>
                     </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="A" <?php if (set_value('status', $activity['status']) == 'A') {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="D" <?php if (set_value('status', $activity['status']) == 'D') {
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
                        <h3 class="box-title titlefix">Activity List</h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>activity" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Activity List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Activity</th>
                                        <th>Code</th>
                                        <th>Group</th>
                                        <th>Status</th>
                                        <th class="text-right no-print">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($activitylist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($activitylist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['activity_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['activity_code'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['parent_activity_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">


                                                    <a href="<?= base_url(); ?>activity/delete/<?php echo $row['activity_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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
       $('#activity_group').select2();
    });
</script>