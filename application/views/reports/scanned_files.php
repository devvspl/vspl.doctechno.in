<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box" id="exphead">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix">Scanned Files</h3>
                        <div class="box-tools pull-right">
                            <form id="filterForm" method="GET" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="temp_scan_by" id="temp_scan_by" class="form-control">
                                                <option value="">All Scanners</option>
                                                <?php foreach ($scanner_users as $user): ?>
                                                    <option value="<?= $user['user_id'] ?>">
                                                        <?= htmlspecialchars($user['full_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" onfocus="this.showPicker()" name="from_date"
                                                id="from_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" onfocus="this.showPicker()" name="to_date" id="to_date"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-primary"> <i
                                                    class="fa fa-search"></i></button>
                                            <button type="button" class="btn btn-primary btn-sm" id="sync">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_p"></div>
                            <table id="scannedFiles" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="background-color: white;">ID</th>
                                        <th style="text-align:left">File</th>
                                        <th style="text-align:left">Document Name</th>
                                        <th style="text-align:center">Scanned By</th>
                                        <th style="text-align:center">Scan Date</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="pdfModal" tabindex="-1" aria-pledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PDF Preview</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-p="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" src="" width="100%" height="600px" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // $("#temp_scan_by").select2();
        var dataTable = $("#scannedFiles").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('scanned_files') ?>",
                "type": "GET",
                "data": function (d) {
                    d.temp_scan_by = $("#temp_scan_by").val();
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },
            "columns": [
                {
                    data: "scan_id",
                    title: "Scan ID",
                    className: "text-center"
                },
                {
                    data: "file_name",
                    title: "File Name",
                    className: "text-start",
                    render: function (data, type, row) {
                        return `<a href="#" class="view-pdf" data-file="${row.file_path}">${data}</a>`;
                    }
                },
                {
                    data: "document_name",
                    title: "Document Name",
                    className: "text-start"
                },
                {
                    data: "full_name",
                    title: "Scanned By",
                    className: "text-center"
                },
                {
                    data: "temp_scan_date",
                    title: "Scan Date",
                    className: "text-center"
                }
            ],
            "order": [],
            "pageLength": 10,
            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            }],
            "language": {
                "emptyTable": "No data available in table"
            },
            "dom": 'Bfrtip',
            buttons: [
                {
                    text: '<i class="fa fa-download"></i> Export All Filtered',
                    className: 'btn btn-primary btn-sm buttons-csv',
                    action: function () {
                        const temp_scan_by = $("#temp_scan_by").val();
                        const from_date = $("#from_date").val();
                        const to_date = $("#to_date").val();
                        const search_value = $(".dataTables_filter input").val();

                        const query = $.param({
                            temp_scan_by,
                            from_date,
                            to_date,
                            search: search_value
                        });

                        window.location.href = `<?= base_url('ReportsController/export_scanned_files') ?>?${query}`;
                    }
                }
            ]
        });
        $("#filterForm").on('submit', function (e) {
            e.preventDefault();
            dataTable.ajax.reload();
        });

        $("#sync").on('click', function () {
            dataTable.ajax.reload();
        });
        $(document).on('click', '.view-pdf', function (e) {
            e.preventDefault();
            var fileUrl = $(this).data('file');
            $("#pdfViewer").attr("src", fileUrl);
            $("#pdfModal").modal("show");
        });
    });
</script>