<style>
    .form-control-sm {
        display: inline-block;
        width: 70%;
        /* height: 0px; */
        /* padding: 0px 12px; */
        font-size: 10pt;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Punch File</h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('message')) { ?>
                            <?php echo $this->session->flashdata('message') ?>
                        <?php } ?>

                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Latest Scan File</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Location</th>
                                        <th>Document Name</th>
                                        <th>File</th>
                                        <th>Scanned By</th>
                                        <th>Scan Date</th>
                                        <th>Bill Approve Date</th>
                                        <th>Support File</th>
                                        <th>Doc Type</th>
                                        <th>Action</th>
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
                                                <td><?php echo $count++; ?></td>
                                                <td><?= $this->customlib->get_Location_Name($row['Location'])?></td>
                                                <td class="mailbox-name">
                                                    <?php echo $row['Document_Name']; ?>
                                                    <span class="fa fa-pencil edit_doc_name" style="cursor: pointer;display:none" data-id="<?= $row['Scan_Id'] ?>" data-val="<?= $row['Document_Name']; ?>"></span>
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
                                                </td>
                                               	<td class="mailbox-name">
													<?php
															if($row['Temp_Scan']=='Y'){
																$scan_by = $row['Temp_Scan_By'];
																$scan_date = $row['Temp_Scan_Date'];
															}else{
																$scan_by = $row['Scan_By'];
																$scan_date = $row['Scan_Date'];
															}
													?>
													<?php echo $this->customlib->get_Name($scan_by); ?>
												</td>
												<td class="mailbox-name">
												<?= !empty($scan_date) ? date('d-m-Y', strtotime($scan_date)) : ''; ?>
												</td>
                                                <td class="mailbox-name">
													<?= !empty($row['Bill_Approver_Date']) ? date('d-m-Y', strtotime($row['Bill_Approver_Date'])) : ''; ?>
												</td>
                                                <td class="mailbox-name">

                                                    <?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
                                                        <a href="#" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name no-print">
                                                    <select name="DocType_Id" id="DocType_Id_<?= $row['Scan_Id'] ?>" class="form-control-sm DocType_Id" disabled onchange="changeDocType(<?= $row['Scan_Id'] ?>,this.value)">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($my_doctype_list as $value) {
                                                            if ($value['type_id'] == $row['DocType_Id']) {
                                                                echo "<option value='" . $value['type_id'] . "' selected>" . $value['file_type'] . "</option>";
                                                            } else {
                                                                echo "<option value='" . $value['type_id'] . "'>" . $value['file_type'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--<i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" id="doctype_edit_<?= $row['Scan_Id'] ?>" onclick="editDocType(<?= $row['Scan_Id'] ?>,this)" style="font-size: 16px;cursor: pointer;"></i>-->
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" id="resend_scan" data-id="<?= $row['Scan_Id']?>"> <i class="fa fa-undo"></i> Resend</a>
                                                    <?php if ($row['DocType_Id'] != 0) { ?>
                                                        <a href="<?php echo base_url(); ?>file_entry/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-success btn-xs" data-toggle="tooltip" title="Punch File"><i class="fa fa-pencil"></i> Punch</a>
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
   /*   $(document).ready(function() {
        $('.DocType_Id').select2();
    }); */

    function editDocType(Scan_Id, th) {
        $("#DocType_Id_" + Scan_Id).prop('disabled', false);
    }

    function changeDocType(Scan_Id, DocType_Id) {
        $.ajax({
            url: '<?php echo base_url(); ?>Punch/changeDocType',
            type: 'POST',
            data: {
                Scan_Id: Scan_Id,
                DocType_Id: DocType_Id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    window.location.reload();
                } else {
                    alert('Something Went Wrong');
                    window.location.reload();
                }
            }
        });
    }

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
                        x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';

                    });
                    $('#detail').html(x);
                    $('#SupportFileView').modal('show');
                }


            }
        });
    }

    $(document).on('click', '#resend_scan', function() {
        var Scan_Id = $(this).data('id');
        var Remark = prompt("Please enter remark to resend this file");
        if (Remark == null) {
            window.location.reload();
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>resend_scan/' + Scan_Id,
                type: 'POST',
                data: {
                    Remark: Remark
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        alert('Record Resend Successfully');
                        location.reload();
                    }
                }
            });
        }
    });
    
    $(document).on('click', '.edit_doc_name', function() {
        var Scan_Id = $(this).data('id');
        var DocName = prompt("Please enter new document name", $(this).data('val'));
        if (DocName == null) {
            window.location.reload();
        } else {
            $.ajax({
                url: '<?php echo base_url(); ?>/Punch/edit_doc_name',
                data: {
                    Scan_Id: Scan_Id,
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