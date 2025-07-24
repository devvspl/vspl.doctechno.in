<div class="content-wrapper" >
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Punch Files Pending</h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('message')) { ?>
                            <?php echo $this->session->flashdata('message') ?>
                        <?php } ?>
                        <div class="table-responsive mailbox-messages">
                            <table id="punchPendingTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">S.No</th>
                                        <th style="text-align: left;">Document Name</th>
                                        <th style="text-align: center;">Location</th>
                                        <th style="text-align: center;">File</th>
                                        <th style="text-align: center;">Support File</th>
                                        <th style="text-align: center;">Document Type</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($scanfile_list)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($scanfile_list as $row) {
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $count++; ?></td>
                                                <td style="text-align: left;" class="mailbox-name">
                                                    <?php echo $row['document_name']; ?>
                                                    <span class="fa fa-pencil edit_doc_name"
                                                        style="cursor: pointer;display:none" data-id="<?= $row['scan_id'] ?>"
                                                        data-val="<?= $row['document_name']; ?>"></span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= $this->customlib->get_Location_Name($row['location_id']) ?></td>

                                                <td style="text-align: center;" class="mailbox-name">
                                                    <a href="javascript:void(0);" target="popup"
                                                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                                                        <?php echo $row['file_name'] ?></a>
                                                </td>
                                                <td style="text-align: center;" class="mailbox-name">
                                                    <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                                        <a href="#" class="btn btn-link btn-xs"
                                                            onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i
                                                                class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-align: center;" class="mailbox-name no-print">
                                                    <?php
                                                    $docTypeClasses = [
                                                        1 => 'doc-type-1',
                                                        6 => 'doc-type-6',
                                                        7 => 'doc-type-7',
                                                        13 => 'doc-type-13',
                                                        17 => 'doc-type-17',
                                                        20 => 'doc-type-20',
                                                        22 => 'doc-type-22',
                                                        23 => 'doc-type-23',
                                                        27 => 'doc-type-27',
                                                        28 => 'doc-type-28',
                                                        29 => 'doc-type-29',
                                                        31 => 'doc-type-31',
                                                        42 => 'doc-type-42',
                                                        43 => 'doc-type-43',
                                                        44 => 'doc-type-44',
                                                        46 => 'doc-type-46',
                                                        47 => 'doc-type-47',
                                                        48 => 'doc-type-48',
                                                        50 => 'doc-type-50',
                                                        56 => 'doc-type-56'
                                                    ];

                                                    $docTypeId = $row['doc_type_id'];
                                                    $badgeClass = isset($docTypeClasses[$docTypeId]) ? $docTypeClasses[$docTypeId] : 'doc-type-default';
                                                    ?>

                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= $row['doc_type']; ?>
                                                    </span>
                                                </td>

                                                <td style="text-align: center;" class="mailbox-name">
                                                    <?php if ($row['doc_type_id'] != 0) { ?>
                                                        <a href="<?php echo base_url(); ?>file_entry/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>"
                                                            class="btn btn-success btn-xs" data-toggle="tooltip"
                                                            title="Punch File"><i class="fa fa-pencil"></i></a>
                                                    <?php } ?>
                                                </td>

                                                <?php
                                        }
                                        $count++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="SupportFileView" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modalwrapwidth">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
            <div class="scroll-area">
                <div class="modal-body paddbtop">
                    <div id="detail">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#punchPendingTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o"></i> Export',
                    title: 'Punch_Files_Pending_' + new Date().toISOString().slice(0, 10),
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });
    });
    function editDocType(scan_id, th) {
        $("#DocType_Id_" + scan_id).prop('disabled', false);
    }

    function changeDocType(scan_id, doc_type_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>Punch/changeDocType',
            type: 'POST',
            data: {
                scan_id: scan_id,
                doc_type_id: doc_type_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    window.location.reload();
                } else {
                    alert('Something Went Wrong');
                    window.location.reload();
                }
            }
        });
    }

    function getSupportFile(scan_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>Punch/getSupportFile',
            type: 'POST',
            data: {
                scan_id: scan_id
            },
            dataType: 'json',
            success: function (response) {

                if (response.status == 200) {

                    var x = '';
                    $.each(response.data, function (index, value) {
                        x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';

                    });
                    $('#detail').html(x);
                    $('#SupportFileView').modal('show');
                }


            }
        });
    }

    $(document).on('click', '#resend_scan', function () {
        var scan_id = $(this).data('id');
        var Remark = prompt("Please enter remark to resend this file");
        if (Remark == null) {
            window.location.reload();
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>resend_scan/' + scan_id,
                type: 'POST',
                data: {
                    Remark: Remark
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 200) {
                        alert('Record Resend Successfully');
                        location.reload();
                    }
                }
            });
        }
    });

    $(document).on('click', '.edit_doc_name', function () {
        var scan_id = $(this).data('id');
        var DocName = prompt("Please enter new document name", $(this).data('val'));
        if (DocName == null) {
            window.location.reload();
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>/Punch/edit_doc_name',
                data: {
                    scan_id: scan_id,
                    DocName: DocName
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 200) {
                        alert('Record Updated Successfully');
                        location.reload();
                    }
                }
            });
        }
    });
</script>