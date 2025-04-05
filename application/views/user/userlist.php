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
                        <h3 class="box-title">Add User</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?= base_url(); ?>User/create" id="userform" name="userform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name</label>
                                <input autofocus="" id="first_name" name="first_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('first_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name</label>
                                <input autofocus="" id="last_name" name="last_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('last_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                            </div>
                            <?php if ($_SESSION['role'] == 'super_admin') { ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Group</label>
                                    <select name="group" id="group" class="form-control">

                                        <?php foreach ($grouplist as $row) {
                                        ?>
                                            <option value="<?= $row['group_id'] ?>"><?= $row['group_name'] ?></option>
                                        <?php } ?>


                                    </select>
                                    <span class="text-danger"><?php echo form_error('group'); ?></span>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Role</label>
                                <select name="role" id="role" class="form-control">

                                    <?php
                                    if ($_SESSION['role'] == 'admin') {
                                        echo '<option value="user">User</option>';
                                    } else if ($_SESSION['role'] == 'super_admin') {
                                        echo '<option value="admin">Admin</option>';
                                         echo '<option value="super_approver">Super Approver</option>';
                                           echo '<option value="super_scan">Super Scanner</option>';
                                    }
                                    ?>

                                </select>
                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">Username</label>
                                <input id="username" name="username" placeholder="" type="text" class="form-control" value="<?php echo set_value('username'); ?>" />
                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Password</label>
                                <input id="password" name="password" placeholder="" type="password" class="form-control" value="<?php echo set_value('password'); ?>" />
                                <span class="text-danger"><?php echo form_error('password'); ?></span>
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
                            <table class="table table-striped table-bordered table-hover example1">
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

<script>
    $(document).ready(function() {
        $('.example1').DataTable({
            destroy: true,
            pageLength: 12,
        });
    });
</script>