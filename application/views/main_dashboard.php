<div class="content-wrapper py-4">
    <?php
    if ($this->session->userdata('role_id') == 0 || $this->session->userdata('role_id') == '') {
        ?>
        <section class="content d-flex justify-content-center align-items-center vh-100 bg-light">
            <div class="card shadow rounded-4 p-4" style="width: 100%;">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 text-danger">Role Not Assigned</h4>
                    <p class="text-muted">
                        Your role is not assigned. <br>
                        Please contact the <strong>system administrator</strong> to assign a role.
                    </p>
                    <a href="mailto:support@vspl.doctechno.in" class="btn btn-primary mt-3">
                        Contact Admin
                    </a>
                </div>
            </div>
        </section>
    <?php } else {
        ?>
        <?php if (getUserRolePermission(9)): ?>
            <section class="content">
                <div class="row box">
                    <div class="box-header">
                        <h3 class="box-title">Scanner</h3>
                        <hr />
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-aqua" onclick="window.location.href='<?= base_url('scanner?status=all') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $total_scans ?></h3>
                                <p>Total Scanned Files</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-green"
                            onclick="window.location.href='<?= base_url('scanner?status=submitted') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $final_submitted ?></h3>
                                <p>Final Submitted</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-yellow"
                            onclick="window.location.href='<?= base_url('scanner?status=pending') ?>'" style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $pending_submission ?></h3>
                                <p>Pending Submission</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-light-blue"
                            onclick="window.location.href='<?= base_url('scanner?status=rejected') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $rejected_scans ?></h3>
                                <p>Rejected Scans</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-red" onclick="window.location.href='<?= base_url('scanner?status=deleted') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $deleted_scans ?></h3>
                                <p>Deleted Scans</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-trash-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php if (getUserRolePermission(10)): ?>
            <section class="content">
                <div class="row box">
                    <div class="box-header">
                        <h3 class="box-title">Document Classifier</h3>
                        <hr />
                    </div>
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
                            onclick="window.location.href='<?php echo base_url('classifications_rejected') ?>'"
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
                            onclick="window.location.href='<?php echo base_url('rejected_scans') ?>'" style="cursor: pointer;">
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
                            onclick="window.location.href='<?php echo base_url('document_received?status=N') ?>'"
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
                            onclick="window.location.href='<?php echo base_url('document_received?status=Y') ?>'"
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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="box-header with-border">
                            <h3 class="box-title">Summary Table</h3>
                            <div class="box-tools">
                                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>"
                                    max="<?php echo date('Y-m-d') ?>" onfocus="this.showPicker()" name="scan_date"
                                    id="scan_date" />
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Classified</td>
                                    <td id="classified_count">0</td>
                                </tr>
                                <tr>
                                    <td>Classification Rejected</td>
                                    <td id="classified_rejected_count">0</td>
                                </tr>
                                <tr>
                                    <td>Total Scans Rejected by Me</td>
                                    <td id="rejected_count">0</td>
                                </tr>
                                <tr>
                                    <td>Document Received</td>
                                    <td id="document_verified_count">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        $(document).ready(function () {
                            function fetchCounts() {
                                var selectedDate = $('#scan_date').val();
                                $.ajax({
                                    url: '<?= base_url('DocClassifierController/scan_counts_by_date') ?>',
                                    type: 'POST',
                                    data: {
                                        selected_date: selectedDate,
                                    },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.error) {
                                            alert(response.error);
                                            return;
                                        }
                                        $('#classified_count').text(response.classified_by_me);
                                        $('#classified_rejected_count').text(response.classified_rejected);
                                        $('#rejected_count').text(response.scan_rejected_by_me);
                                        $('#document_verified_count').text(response.document_verified_count);
                                    },
                                    error: function () {
                                        alert('Error fetching counts');
                                    }
                                });
                            }
                            $('#scan_date').on('change', fetchCounts);
                            fetchCounts();
                        });
                    </script>
                </div>
            </section>
        <?php endif; ?>
        <?php if (getUserRolePermission(12)): ?>
            <section class="content">
                <div class="row box">
                    <div class="box-header">
                        <h3 class="box-title">Punching Summary</h3>
                        <hr />
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-aqua" onclick="window.location.href='<?= base_url('punch_file') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $total_count ?></h3>
                                <p>Total Pending Punch</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-xs-3">
                        <div class="small-box bg-green"
                            onclick="window.location.href='<?= base_url('punch/my-punched-file/all') ?>'"
                            style="cursor: pointer;">
                            <div class="inner">
                                <h3><?= $user_count ?></h3>
                                <p>Total Punched</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php
    }
    ?>
</div>