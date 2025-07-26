<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= !empty($user['user_id']) ? 'Edit User' : 'Add User' ?></h3>
                    </div>
                    <form id="user_form"
                        action="<?= base_url('save_user' . (!empty($user['user_id']) ? '/' . $user['user_id'] : '')) ?>"
                        method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>
                            <div class="form-group">
                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                <input id="first_name" name="first_name" type="text" class="form-control"
                                    value="<?= set_value('first_name', !empty($user['first_name']) ? htmlspecialchars($user['first_name']) : '') ?>" />
                                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input id="last_name" name="last_name" type="text" class="form-control"
                                    value="<?= set_value('last_name', !empty($user['last_name']) ? htmlspecialchars($user['last_name']) : '') ?>" />
                                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="role_id">Role <span class="text-danger">*</span></label>
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="">-- Select Role --</option>
                                    <?php foreach ($role_list as $role): ?>
                                        <option value="<?= $role['id'] ?>" <?= set_select('role_id', $role['id'], !empty($user['role_id']) && $user['role_id'] == $role['id']) ?>>
                                            <?= htmlspecialchars($role['role_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('role_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input id="username" name="username" type="text" class="form-control"
                                    value="<?= set_value('username', !empty($user['username']) ? htmlspecialchars($user['username']) : '') ?>" />
                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password
                                    <?= isset($user['user_id']) && $user['user_id'] ? '(Leave blank to keep unchanged)' : '<span class="text-danger">*</span>' ?>
                                </label>
                                <input id="password" name="password" type="password" class="form-control" />
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
                                    <?php if (empty($users_list)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $count = 1; ?>
                                        <?php foreach ($users_list as $user): ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $count++ ?></td>
                                                <td style="text-align: left;">
                                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($user['role_name'] ?: 'N/A') ?>
                                                </td>
                                                <td style="text-align: center;"><?= htmlspecialchars($user['username']) ?></td>
                                                <td style="text-align: center;">
                                                    <a href="<?= base_url('set_permission/') . $user['user_id']; ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    <a href="<?= base_url('user/' . $user['user_id']) ?>"
                                                        class="btn btn-default btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('delete_user/' . $user['user_id']) ?>"
                                                        class="btn btn-default btn-xs"
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-default btn-xs view-user"
                                                        data-id="<?= $user['user_id'] ?>">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 25%; text-align: left;">Full Name</td>
                        <td style="width:75%" id="modal_full_name"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; text-align: left;">Username</td>
                        <td style="width:75%" id="modal_username"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; text-align: left;">Role</td>
                        <td style="width:75%" id="modal_role_name"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; text-align: left;">Status</td>
                        <td style="width:75%" id="modal_status_label"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            ordering: true,
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

        $(document).on('click', '.view-user', function () {
            var userId = $(this).data('id');
            $.ajax({
                url: '<?= base_url("get_user_details/") ?>' + userId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }
                    $('#modal_full_name').text(response.full_name || 'N/A');
                    $('#modal_username').text(response.username || 'N/A');
                    $('#modal_role_name').text(response.role_name || 'N/A');
                    $('#modal_status_label').text(response.status_label || 'N/A');
                    $('#userModal').modal('show');
                },
                error: function () {
                    alert('Error loading user details.');
                }
            });
        });
    });
</script>