<div class="content-wrapper py-4">
    <section class="content">
        <div class="row">
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-orange"
                    onclick="window.location.href='<?php echo base_url('classification') ?>'" style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $pending_for_classification ?></h3>
                        <p>Pending for Classification</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-aqua" onclick="window.location.href='<?php echo base_url('processed') ?>'"
                    style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $classified_by_me ?></h3>
                        <p>Total Classified</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-red"
                    onclick="window.location.href='<?php echo base_url('classifications-rejected') ?>'"
                    style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $classified_rejected ?></h3>
                        <p>Classification Rejected</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-light-blue"
                    onclick="window.location.href='<?php echo base_url('scan-rejected-scan-admin') ?>'"
                    style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $scan_rejected_by_me ?></h3>
                        <p>Total Scans Rejected by Me</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-yellow"
                    onclick="window.location.href='<?php echo base_url('doc-received?is_verified=N') ?>'"
                    style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $document_not_verified_count ?></h3>
                        <p>Document Not Received</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-2 col-xs-3">
                <div class="small-box bg-green"
                    onclick="window.location.href='<?php echo base_url('doc-received?is_verified=Y') ?>'"
                    style="cursor: pointer;">
                    <div class="inner">
                        <h3><?= $document_verified_count ?></h3>
                        <p>Document Received</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row box box-primary">
            <div class="col-md-12">
                <div class="box-header with-border">
                    <h3 class="box-title">Scan Summary by User</h3>
                </div>
                <table class="table table-striped table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Scanned By</th>
                            <th>Total Scan</th>
                            <th>Final Submitted</th>
                            <th>Pending Submission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scan_summary as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['scanned_by']) ?></td>
                                <td><?= htmlspecialchars($row['total_scan']) ?></td>
                                <td><?= htmlspecialchars($row['final_submitted_count']) ?></td>
                                <td><?= htmlspecialchars($row['not_final_submitted_count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>