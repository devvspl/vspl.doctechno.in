<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add User</h3>
                    </div>
                    <form id="form1" action="<?= base_url(); ?>master/UserController/create" method="post"
                        accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name" type="text" class="form-control"
                                    value="<?php echo set_value('first_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name" type="text" class="form-control"
                                    value="<?php echo set_value('last_name'); ?>" />
                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">---Select Role---</option>
                                    <?php
                                    foreach ($role_list as $role) {
                                        echo "<option value=" . $role['id'] . ">" . $role['role_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" name="username" type="text" class="form-control"
                                    value="<?php echo set_value('username'); ?>" />
                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" name="password" type="password" class="form-control"
                                    value="<?php echo set_value('password'); ?>" />
                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">User List</h3>
                    </div>
                    <div class="box-body">
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
                                                    <?php echo $row['role_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['username'] ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a href="<?= base_url(); ?>master/UserController/permission/<?php echo $row['user_id'] ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>master/UserController/edit/<?php echo $row['user_id'] ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>user/delete/<?php echo $row['user_id'] ?>"
                                                        class="btn btn-default btn-xs"
                                                        onclick="return confirm('Are you sure to delete');">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        $('.example1').DataTable({
            destroy: true,
            pageLength: 12,
        });
        $(document).ready(function () {
            $('#department').select2({
                placeholder: ""
            });
        });
    });
</script>