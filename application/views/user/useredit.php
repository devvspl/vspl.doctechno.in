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
                        <h3 class="box-title">Update User</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>User/update/<?= $id ?>" id="userform" name="userform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name</label>
                                <input autofocus="" id="first_name" name="first_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('first_name', $user['first_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name</label>
                                <input autofocus="" id="last_name" name="last_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('last_name', $user['last_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Select</option>
                                    <option value="admin" <?php if (set_value('role', $user['role']) == 'admin') {
                                                                echo "selected";
                                                            } ?>>Admin</option>
                                    <option value="user" <?php if (set_value('role', $user['role']) == 'user') {
                                                                echo "selected";
                                                            } ?>>User</option>
                                                            <?php if (set_value('role', $user['role']) == 'super_approver') {
                                                                echo "selected";
                                                            } ?>>Super Approver</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username</label>
                                <input id="username" name="username" placeholder="" type="text" class="form-control" value="<?php echo set_value('username', $user['username']); ?>" />
                                <span class="text-danger"><?php echo form_error('username'); ?></span>
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
                        <h3 class="box-title titlefix">User List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">User List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Username</th>
                                        <th class="text-right no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($userlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($userlist as $row) {
                                        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['role'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['username'] ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a href="<?= base_url(); ?>user/permission/<?php echo $row['user_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>user/edit/<?php echo $row['user_id'] ?>" class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>user/delete/<?php echo $row['user_id'] ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure to delete');">
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