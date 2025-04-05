<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <form action="<?= base_url(); ?>changepass" id="passwordform" name="passwordform" method="post" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                        <?php if ($this->session->flashdata('message')) { ?>
                            <?php echo $this->session->flashdata('message') ?>
                        <?php } ?>
                        <?php
                        if (isset($error_message)) {
                            echo $error_message;
                        }
                        ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Current Password<span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="current_pass" required="required" class="form-control col-md-7 col-xs-12" type="password" value="">
                                <span class="text-danger"><?= form_error('current_pass');?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">New Password<span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input required="required" class="form-control col-md-7 col-xs-12" name="new_pass" placeholder="" type="password" value="">
                                <span class="text-danger"><?php echo form_error('new_pass'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Confirm Password<span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="confirm_pass" name="confirm_pass" placeholder="" type="password" value="" class="form-control col-md-7 col-xs-12">
                                <span class="text-danger"><?php echo form_error('confirm_pass'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-info">Change Password</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>