<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Punched Files <span class="text-danger">(Rejected)</span></h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">My Punched Files</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Document Name</th>
                                        <th>Document Type</th>
                                        <th>File</th>

                                        <th>Punch Date</th>
                                        <th>Reject By</th>
                                        <th>Reject Date</th>
                                        <th>Reject Remark</th>
                                        <th class="no-print">Support File</th>
                                        <th class="no-print">view</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($finance_rejected_punch_list)) {
                                    ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($finance_rejected_punch_list as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['document_name']; ?>
                                                    <span class="fa fa-pencil edit_doc_name" style="cursor: pointer;" data-id="<?= $row['scan_id'] ?>" data-val="<?= $row['document_name']; ?>"></span>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['doc_type']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo date('d-m-Y', strtotime($row['punched_date'])) ?>
                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo $this->customlib->get_Name($row['Finance_Resend_By']); ?>
                                                </td>

                                                <td class="mailbox-name">
                                                <?php echo !empty($row['Finance_Resend_Date']) ? date('d-m-Y', strtotime($row['Finance_Resend_Date'])) : ''; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['Finance_Resend_Remark']; ?>
                                                </td>

                                                <td class="mailbox-date text-center no-print">
                                                    <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                                                </td>
                                                <td>
                                                    <?php if ($row['has_edit_permission'] == 'Y') { ?>
                                                        <a href="<?php echo base_url(); ?>file_entry/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-pencil"></i></a>
                                                    <?php } ?>
                                                </td>
                                        <?php
                                        }
                                        $count++;
                                    }
                                        ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
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
    function getSupportFile(scan_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>Punch/getSupportFile',
            type: 'POST',
            data: {
                scan_id: scan_id
            },
            dataType: 'json',
            success: function(response) {

                if (response.status == 200) {

                    var x = '';
                    $.each(response.data, function(index, value) {
                        x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';

                    });
                    $('#detail').html(x);
                    $('#SupportFileView').modal('show');
                }


            }
        });
    }

    function reloadPage() {
        window.location.href = "<?php echo base_url(); ?>my_punched_file";
    }
    
    $(document).on('click', '.edit_doc_name', function() {
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
                success: function(response) {
                    if (response.status == 200) {
                        alert('Record Updated Successfully');
                        location.reload();
                    }
                }
            });
        }
    });
</script>