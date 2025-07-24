<div class="content-wrapper" >
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
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
                <div class="box" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">User List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">User List</div>
                            <table id="usersTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">S No.</th>
                                        <th style="text-align: left;">Name</th>
                                        <th style="text-align: center;">Role</th>
                                        <th style="text-align: center;">Username</th>
                                        <th style="text-align: center;">Action</th>
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
                                                <td style="text-align: center;" class="mailbox-name">
                                                    <?php echo $count++; ?>
                                                </td>
                                                <td style="text-align: left;" class="mailbox-name">
                                                    <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>
                                                </td>
                                                <td class="mailbox-name" style="text-align: center;">
                                                    <?php echo $row['role_name'] ?>
                                                </td>
                                                <td class="mailbox-name" style="text-align: center;">
                                                    <?php echo $row['username'] ?>
                                                </td>
                                                <td class="mailbox-name" style="text-align: center;">
                                                    <a href="<?= base_url('set_permission/') . $row['user_id']; ?>"
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
        $('#usersTable').DataTable({
            "ordering": false,
            "columnDefs": [
                { "orderable": false, "targets": 1 }
            ],
            dom: 'Bfrtip',
            pageLength: 10,
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> Export',
                    title: 'Users_List_' + new Date().toISOString().slice(0, 10),
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });
        $(document).ready(function () {
            $('#department').select2({
                placeholder: ""
            });
        });
    });
</script>