<style>
    .radio-inline {
        margin-right: 40px;
    }

    .box-header.with-border {
        border-bottom: 2px solid #1b98ae;
        box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
    }

    .d-none {
        display: none;
    }

    .table.table-bordered.dataTable tbody th,
    table.table-bordered.dataTable tbody td {
        border: 1px solid #ddd;
    }

    .table.dataTable thead>tr>th.sorting_asc,
    table.dataTable thead>tr>th.sorting_desc,
    table.dataTable thead>tr>th.sorting,
    table.dataTable thead>tr>td.sorting_asc,
    table.dataTable thead>tr>td.sorting_desc,
    table.dataTable thead>tr>td.sorting {
        border: 1px solid #ddd;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-body" id="form_div">
                        <form action="<?= base_url() ?>Search/search_global" method="POST" id="search_form" role="form">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="searchbartext" id="searchbartext" class="form-control" placeholder="Type Here ..." value="<?= set_value('searchbartext') ?>">
                                        <span class="text-danger"><?php echo form_error('searchbartext'); ?></span>
                                    </div>
                                    
                                </div>

                                <div class="col-sm-2 col-md-2">
                                    <div class="form-group">
                                        <button type="submit" id="search" name="search" value="search" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-search"></i> Search</button>
                                        <button type="button" id="reset" name="reset" onclick="reloadPage();" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-refresh"></i> Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover " id="mytable">
                            <thead>
                                <tr>
                                    <th colspan="9" id="filter_type" style="text-align: center;">Search Result (Only Approved Records)</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th style="text-align: center;">Document Name</th>
                                    <th style="text-align: center;">From</th>
                                    <th style="text-align: center;">To</th>
                                    <th style="text-align: center;">Bill Date</th>
                                    <th style="text-align: center;">File No</th>
                                    <th style="text-align: center;">Amount</th>
                                    <th style="text-align: center;">Upload Date</th>
                                    <th style="text-align: center;">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php
                                if (!empty($result)) {
                                    $i = 1;
                                    foreach ($result as $key => $value) {
                                        echo "<tr>";
                                        echo "<td>" . $i . "</td>";
                                        echo "<td style='text-align:left;width:30%'><a href=" . base_url() . "file_detail/" . $value['scan_id'] . "/" . $value['DocTypeId'] . " target='_blank'>" . $value['document_name'] . "</a></td>";
                                        echo "<td style='text-align:left;width:12%'>" . $value['FromName'] . "</td>";
                                        echo "<td style='text-align:left;width:12%'>" . $value['ToName'] . "</td>";
                                        echo "<td style='text-align:left;width:8%'>" . date('d-m-Y', strtotime($value['BillDate']?? '')) . "</td>";
                                        echo "<td style='text-align:left'>" . $value['File_No'] . "</td>";
                                        if ($value['DocTypeId'] == 23) {
                                            echo "<td style='text-align:left'>" . $value['Grand_Total'] . "</td>";
                                        } else {
                                            echo "<td style='text-align:left'>" . $value['Total_Amount'] . "</td>";
                                        }

                                        echo "<td style='text-align:left;width:8%'>" . date('d-m-Y', strtotime($value['Created_Date']?? '')) . "</td>";
                                        echo "<td style='text-align:left;width:20%'>" . $value['Remark'] . "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $("#mytable").DataTable({
            pageLength: 20,
            searching:false,
            aaSorting: [],
            rowReorder: {
                selector: "td:nth-child(2)",
            },
            //responsive: 'false',
            dom: "Bfrtip",
            buttons: [{
                    extend: "copyHtml5",
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: "Copy",
                    title: $(".download_label").html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"],
                    },
                },

                {
                    extend: "excelHtml5",
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: "Excel",

                    title: $(".download_label").html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"],
                    },
                },

                {
                    extend: "csvHtml5",
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: "CSV",
                    title: $(".download_label").html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"],
                    },
                },

                {
                    extend: "pdfHtml5",
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: "PDF",
                    title: $(".download_label").html(),
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"],
                    },
                },

                {
                    extend: "print",
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: "Print",
                    title: $(".download_label").html(),
                    customize: function(win) {
                        $(win.document.body)
                            .find("th")
                            .addClass("display")
                            .css("text-align", "center");
                        $(win.document.body)
                            .find("td")
                            .addClass("display")
                            .css("text-align", "left");
                        $(win.document.body)
                            .find("table")
                            .addClass("display")
                            .css("font-size", "14px");
                        // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
                        $(win.document.body).find("h1").css("text-align", "center");
                    },
                    exportOptions: {
                        columns: ["thead th:not(.noExport)"],
                    },
                },

                {
                    extend: "colvis",
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: "Columns",
                    title: $(".download_label").html(),
                    postfixButtons: ["colvisRestore"],
                },
            ],
        });
    });

    function reloadPage() {
        window.location.href = "<?php echo base_url(); ?>search_global";
    }
</script>