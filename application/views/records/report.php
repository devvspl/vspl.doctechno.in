<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Scan Punch Report:</h3>
                    </div>
                    <form role="form" action="<?= base_url(); ?>Record/report" method="post">
                        <div class="box-body row">
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label> Scan By:</label>
                                    <select name="Scan_By" id="Scan_By" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <?php foreach ($user_list as $key => $value) { ?>
                                            <option value="<?= $value['user_id']; ?>" <?php if (set_value('scanned_by') == $value['user_id']) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $value['first_name'] . ' ' . $value['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="text-danger"><?php echo form_error('scanned_by'); ?></span>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label> Punch By:</label>
                                    <select name="Punch_By" id="Punch_By" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <?php foreach ($user_list as $key => $value) { ?>
                                            <option value="<?= $value['user_id']; ?>" <?php if (set_value('punched_by') == $value['user_id']) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $value['first_name'] . ' ' . $value['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="text-danger"><?php echo form_error('punched_by'); ?></span>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label> Approve By:</label>
                                    <select name="Approve_By" id="Approve_By" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <?php foreach ($user_list as $key => $value) { ?>
                                            <option value="<?= $value['user_id']; ?>" <?php if (set_value('approved_by') == $value['user_id']) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $value['first_name'] . ' ' . $value['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <span class="text-danger"><?php echo form_error('approved_by'); ?></span>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <div class="form-group" style="margin-top: 22px;">
                                    <button type="submit" id="search" name="search" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-search"></i> Search</button>
                                    <button type="button" id="reset" name="reset" onclick="reloadPage();" class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-refresh"></i> Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Scan Punch Report</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Document Name</th>
                                        <!-- <th>Document Type</th> -->
                                        <th>File</th>
                                        <th>Scan By</th>
                                        <th>Scan Date</th>
                                        <th>Punch By</th>
                                        <th>Punch Date</th>
                                        <th>Days to Punch</th>
                                        <th>Approve By</th>
                                        <th>Approve Date</th>
                                        <th>Days to Approve</th>
                                        <!--     <th class="text-right no-print">Support File</th> -->
                                        <td class="text-right no-print"><b>view</b></td>
                                         <td class="text-right no-print"><b>Action</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($record_list)) {
                                    ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($record_list as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['document_name']; ?>
                                                </td>
                                                <!--   <td class="mailbox-name">
                                                    <?php echo $row['doc_type']; ?>
                                                </td> -->
                                                <td class="mailbox-name">
                                                    <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $this->customlib->get_Name($row['scanned_by']); ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date('d-m-Y', strtotime($row['scan_date'])) ?>
                                                </td>
                                                <?php if ($row['is_file_punched'] == 'Y') { ?>
                                                    <td class="mailbox-name">
                                                        <?php echo $this->customlib->get_Name($row['punched_by']); ?>
                                                    </td>

                                                    <td class="mailbox-name">
                                                        <?php echo date('d-m-Y', strtotime($row['punched_date'])) ?>
                                                    </td>
                                                    <td class="mailbox-name text-center">
                                                        <?php echo $this->customlib->dateDiff($row['punched_date'], $row['scan_date']); ?>
                                                    </td>
                                                <?php } else { ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                <?php } ?>


                                                <?php if ($row['is_file_approved'] == 'Y') { ?>
                                                    <td class="mailbox-name">
                                                        <?php echo $this->customlib->get_Name($row['approved_by']); ?>
                                                    </td>

                                                    <td class="mailbox-name">
                                                        <?php echo date('d-m-Y', strtotime($row['approved_date'])) ?>
                                                    </td>
                                                    <td class="mailbox-name text-center">
                                                        <?php echo $this->customlib->dateDiff($row['approved_date'], $row['punched_date']); ?>
                                                    </td>
                                                <?php } else { ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                <?php } ?>
                                                <!--  <td class="mailbox-date text-center no-print">
                                                    <?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td> -->

                                                <td class="no-print">
                                                    <?php if ($row['is_file_punched'] == 'Y') { ?>
                                                        <a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>

                                                </td>
                                                 <td class="no-print">
                                                    <?php if ($row['is_file_approved'] == 'Y') { ?>
                                                        <button type="button" class="btn btn-xs btn-danger" title="Reject Approved File" data-id="<?= $row['scan_id'] ?>" id="reject"> <i class="fa fa-undo"></i></button>
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
    function getSupportFile(Scan_Id) {
        $.ajax({
            url: '<?php echo base_url(); ?>Punch/getSupportFile',
            type: 'POST',
            data: {
                Scan_Id: Scan_Id
            },
            dataType: 'json',
            success: function(response) {

                if (response.status == 200) {

                    var x = '';
                    $.each(response.data, function(index, value) {
                        /*  x += '<div class="col-md-4">';
                         x += '<div class="form-group">';
                         x += '<a href="javascript:void(0);" target="popup" onclick="window.open(\'' + value.File_Location + '\',\'popup\',\'width=600,height=600\');">' + value.File + '</a>';
                         x += '</div>';
                         x += '</div>'; */
                        x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';

                    });
                    $('#detail').html(x);
                    $('#SupportFileView').modal('show');
                }


            }
        });
    }

    function reloadPage() {
        window.location.href = "<?php echo base_url(); ?>report";
    }
    
    $(document).on('click', '#reject', function() {
        var Scan_Id = $(this).data('id');
        if (confirm('Are you sure you want to reject this file?')) {
            $.ajax({
                url: '<?php echo base_url(); ?>reject_approved_file/' + Scan_Id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        alert('File Rejected Successfully');
                        location.reload();
                    }
                }
            });
        }

    });
</script>