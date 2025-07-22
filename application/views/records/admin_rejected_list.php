<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Rejected Punch Files</h3>
                        <?php if ($this->session->flashdata('message')) { ?>
                            <?php echo $this->session->flashdata('message') ?>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Rejected Punch Files</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Document Name</th>
                                        <th>Document Type</th>
                                        <th>File</th>
                                        <th>Punch By</th>
                                        <th>Punch Date</th>
                                      <!--   <th class="no-print">Support</th> -->
                                        <th class="no-print">view</th>
                                        <th>Rejection Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($rejected_list)) {
                                    ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($rejected_list as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['document_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['doc_type']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $this->customlib->get_Name($row['punched_by']); ?>
                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo date('d-m-Y', strtotime($row['punched_date'])) ?>
                                                </td>

                                                <!-- <td class="mailbox-name text-center no-print">
                                                    <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td> -->
                                                <td>
                                                    <a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['reject_remark']; ?>
                                                </td>
                                                <td>
												<a href="<?php echo base_url(); ?>delete_record/<?= $row['scan_id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                                                    <a href="<?php echo base_url(); ?>approve_record/<?= $row['scan_id'] ?>" class="btn btn-success btn-xs" onclick="return confirm('Are you sure you want to approve this file?');">Approve</a>
                                                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" data-id="<?= $row['scan_id'] ?>" id="give_edit_permission">Edit Permission</a>
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

    $(document).on('click', '#give_edit_permission', function() {
        var scan_id = $(this).data('id');
        if(confirm('Are you sure you want to give permission to edit this file?')){
            $.ajax({
            url: '<?php echo base_url(); ?>give_edit_permission/' + scan_id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    alert('Edit Permission Given Successfully');
                    location.reload();
                }
            }
        });
        }
       
    });
</script>
