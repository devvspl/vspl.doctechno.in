<div class="content-wrapper" >
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update User</h3>
                    </div>
                    <form id="form1" action="<?= base_url(); ?>master/UserController/update/<?= $id ?>" id="userform"
                        name="userform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name</label>
                                <input autofocus="" id="first_name" name="first_name" placeholder="" type="text"
                                    class="form-control"
                                    value="<?php echo set_value('first_name', $user['first_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name</label>
                                <input autofocus="" id="last_name" name="last_name" placeholder="" type="text"
                                    class="form-control"
                                    value="<?php echo set_value('last_name', $user['last_name']); ?>" />
                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">---Select Role---</option>
                                    <?php
                                    $role_id = isset($user['role_id']) ? $user['role_id'] : '';
                                    foreach ($role_list as $role) {
                                        $selected = ($role_id == $role['id']) ? 'selected' : '';
                                        echo "<option value='" . $role['id'] . "' $selected>" . $role['role_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('role'); ?></span>
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
                                                    <?php echo $row['role_name'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['username'] ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a href="<?= base_url('set_permission/') . $row['user_id']; ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    <a href="<?= base_url('master/UserController/edit/') . $row['user_id']; ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url('user/delete/') . $row['user_id']; ?>"
                                                        class="btn btn-default btn-xs"
                                                        onclick="return confirm('Are you sure to delete?');">
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