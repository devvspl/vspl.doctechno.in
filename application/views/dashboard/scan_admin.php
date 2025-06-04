<div class="content-wrapper py-4">
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= $classified_by_me ?></h3>
                        <p>Total Classified</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file"></i>
                    </div>
                    <a href="<?php echo base_url('processed') ?>" class="small-box-footer">More info <i
                            class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?= $classified_rejected ?></h3>
                        <p>Classifications Rejected</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <a href="<?php echo base_url('classifications-rejected') ?>" class="small-box-footer">More info <i
                            class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3><?= $scan_rejected_by_me ?></h3>
                        <p>Total Scans Rejected by Me</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <a href="<?php echo base_url('scan-rejected-scan-admin') ?>" class="small-box-footer">More info <i
                            class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
    </section>
</div>