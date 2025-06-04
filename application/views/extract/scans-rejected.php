<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Scan File Rejected By Me</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Temporary Files</div>
                            <?php if ($this->session->flashdata('message')): ?>
                                <div class="alert alert-info">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>
                            <table class="table table-striped table-hover example">
                                <thead>
                                    <tr>
                                        <th class="text-center">S No.</th>
                                        <th class="text-center">File Name</th>
                                        <th class="text-center">Scanned By</th>
                                        <th class="text-center">Scan Date</th>
                                        <th class="text-center">Reject Remark</th>
                                        <th class="text-center">Reject Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php $i = 1;
                                        foreach ($documents as $doc): ?>
                                            <tr data-scan-id="<?= $doc->scan_id; ?>">
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td class="text-center"><?= $doc->document_name ?? ''; ?></td>
                                                <td class="text-center"><?= htmlspecialchars($doc->scanned_by ?? ''); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($doc->scan_date ?? ''); ?></td>
                                                <td class="text-center">
                                                    <?= htmlspecialchars($doc->temp_scan_reject_remark ?? ''); ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($doc->temp_scan_reject_date ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No records found</td>
                                        </tr>
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