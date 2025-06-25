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
                            <table id="scanRejectedTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">S No.</th>
                                        <th style="text-align: left;">File Name</th>
                                        <th style="text-align: center;">Scanned By</th>
                                        <th style="text-align: center;">Scan Date</th>
                                        <th style="text-align: center;">Reject Remark</th>
                                        <th style="text-align: center;">Reject Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php $i = 1;
                                        foreach ($documents as $doc): ?>
                                            <tr data-scan-id="<?= $doc->scan_id; ?>">
                                                <td style="text-align: left;"><?= $i++ ?></td>
                                                <td style="text-align: left;"><?= $doc->document_name ?? ''; ?></td>
                                                <td style="text-align: center;"><?= htmlspecialchars($doc->scanned_by ?? ''); ?>
                                                </td>
                                                <td style="text-align: center;"><?= htmlspecialchars($doc->scan_date ?? ''); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->temp_scan_reject_remark ?? ''); ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= htmlspecialchars($doc->temp_scan_reject_date ?? ''); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No records found</td>
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
<script>
  $(document).ready(function() {
    $("#scanRejectedTable").DataTable({
        paging: true,
        searching: true,
        ordering: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: '<i class="fa fa-file-text-o"></i> Export',
                title: 'Scan_Rejected_List_' + new Date().toISOString().split('T')[0],
                className: 'btn btn-primary btn-sm',
                exportOptions: {
                    columns: ':visible:not(.noExport)'
                }
            }
        ]
    });
});</script>