<div class="content-wrapper" id="pass-content-wrapper">
   <div id="password-form-container">
      <div id="form-header">
         <h3>Change Password</h3>
      </div>
      <form style="padding: 15px;" action="<?= base_url('change_password'); ?>" id="passwordform" name="passwordform"
         method="post" data-parsley-validate id="form-body" novalidate>
         <?php if ($this->session->flashdata('message')): ?>
         <div class="alert alert-info">
            <?= $this->session->flashdata('message'); ?>
         </div>
         <?php endif; ?>
         <?php if (isset($error_message)): ?>
         <div class="alert alert-danger">
            <?= $error_message; ?>
         </div>
         <?php endif; ?>
         <div id="form-group-current">
            <label style="margin-top: 5px;" for="current_pass">Current Password</label>
            <input type="password" id="current_pass" name="current_pass" class="form-control" required>
            <span id="error-current"><?= form_error('current_pass'); ?></span>
         </div>
         <div id="form-group-new">
            <label style="margin-top: 5px;" for="new_pass">New Password</label>
            <input type="password" id="new_pass" name="new_pass" class="form-control" required>
            <span id="error-new"><?= form_error('new_pass'); ?></span>
         </div>
         <div id="form-group-confirm">
            <label style="margin-top: 5px;" for="confirm_pass">Confirm Password</label>
            <input type="password" id="confirm_pass" name="confirm_pass" class="form-control" required>
            <span id="error-confirm"><?= form_error('confirm_pass'); ?></span>
         </div>
         <div id="form-footer">
            <button type="submit" class="btn btn-info">
            <i class="fa fa-lock" aria-hidden="true"></i> Change Password
            </button>
         </div>
      </form>
   </div>
</div>