<style>
    .form-check {
        position: relative;
        display: block;
        padding-left: 1.25rem;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .form-check-input {
        position: absolute;
        margin-top: 0.3rem;
        margin-left: -1.25rem;
    }

    .form-check-label {
        margin-bottom: 0;
        margin-left: 15px;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid1 box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-commenting-o"></i> Set Permission - <?= $user['first_name'] . ' ' . $user['last_name']; ?>
                            <?php if ($this->session->flashdata('message')) { ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php } ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?= base_url(); ?>user" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                    <form id="form1" action="<?= base_url(); ?>User/set_permission" id="userform" name="userform" method="post" accept-charset="utf-8">
                        <div class="box-group" id="accordion">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $id; ?>">
                            <?php
                            foreach ($category as $key => $value) { ?>
                                <div class="font-weight-bold text-center" style="background-color:#173e43;color:#3dcd34; padding:5px;"><?= $value; ?></div>
                                <div class="row">
                                    <?php

                                    foreach ($permissionlist as $list) {
                                        if ($list['category'] == $value) {
                                            $flag = true;
                                            foreach ($user_permission as $up) {
                                                if ($up['permission_id'] == $list['permission_id']) {
                                                    $flag = false; ?>

                                                    <div class="col-md-2 form-check mb-3"><input type="checkbox" checked id="<?= $list['permission_id']; ?>" name="permission[]" class="form-check-input select_permission" value="<?= $list['permission_id']; ?>"><label class="form-check-label" for="<?= $list['permission_id']; ?>"> <?= $list['permission_name']; ?></label></div>
                                            <?php }
                                            } ?>

                                            <?php if ($flag == true) { ?>
                                                <div class="col-md-2 form-check mb-3"><input type="checkbox" id="<?= $list['permission_id']; ?>" name="permission[]" class="form-check-input select_permission" value="<?= $list['permission_id']; ?>"><label class="form-check-label" for="<?= $list['permission_id']; ?>"> <?= $list['permission_name']; ?></label></div>
                                            <?php } ?>
                                    <?php
                                        }
                                    } ?>
                                </div>
                                <br>
                            <?php  } ?>
                            <div class="box-footer">
                                <button type="submit" id="save" class="btn btn-info pull-right">Save</button>
                            </div>

                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

