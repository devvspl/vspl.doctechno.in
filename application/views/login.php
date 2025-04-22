<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>VSPL | Login Page</title>
        <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/form-elements.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css" />
        <style type="text/css">
            body {
                background: linear-gradient(to right, #3995ca 0, #eff0f1 100%);
            }

            .top-content {
                position: relative;
            }

            .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
                background: rgb(53, 170, 71);
            }

            .bgoffsetbgno {
                background: transparent;
                border-right: 0 !important;
                box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.29);
                border-radius: 4px;
            }

            .loginradius {
                border-radius: 4px;
            }

            .login390 {
                min-height: 493px;
            }

            .s-logo {
                width: 170px;
            }

            .border1 {
                border-bottom: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <div class="top-content">
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="col-lg-8 col-md-8 col-sm-12 nopadding bgoffsetbgno col-md-offset-2">
                                <div class="row">
                                    <div class="col-md-6" style="padding-right: 0px;">
                                        <img src="<?= base_url(); ?>assets/images/login-side-img.png" />
                                    </div>
                                    <div class="col-md-6" style="padding-left: 0px;">
                                        <div class="loginbg login390">
                                            <div class="form-top" style="border: 0px;">
                                                <div class="form-top-left text-center border1">
                                                    <img class="s-logo" src="<?= base_url(); ?>assets/images/logo.png " />
                                                </div>
                                            </div>
                                            <div class="form-bottom">
                                                <h3 class="font-white">User Login</h3>
                                                <?php
											if ($this->session->flashdata('message')) { echo $this->session->flashdata('message'); } ?>
                                                <form action="<?= base_url() ?>Auth_ctrl/login" method="post">
                                                    <div class="form-group has-feedback">
                                                        <?php if (isset($financial_years)) { ?>
                                                        <select name="financial_year" class="form-control">
                                                            <option value="">Select Financial Year</option>
                                                            <?php foreach ($financial_years as $f) { ?>
                                                            <option value="<?= $f['id'] ?>"><?= $f['label'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php } ?>
                                                        <?php echo form_error('financial_year'); ?>
                                                    </div>

                                                    <div class="form-group has-feedback">
                                                        <input type="text" name="identity" placeholder="Username" value="" class="form-username form-control" id="identity" />
                                                        <span class="fa fa-envelope form-control-feedback"></span>
                                                        <?php echo form_error('identity'); ?>
                                                    </div>
                                                    <div class="form-group has-feedback">
                                                        <input type="password" value="" name="password" placeholder="Password" class="form-password form-control" id="password" />
                                                        <span class="fa fa-lock form-control-feedback"></span>
                                                        <?php echo form_error('password'); ?>
                                                    </div>
                                                    <button type="submit" class="btn">
                                                        Sign In
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.backstretch.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.mousewheel.min.js"></script>
    </body>
</html>
