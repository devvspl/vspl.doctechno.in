<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$department_list = $this->customlib->getDepartmentList();
$punch_detail = $this->db->get_where('punchfile2', ['Scan_Id' => $Scan_Id])->row();
?>
<div class="box-body">
    <div class="row">
        <div class="col-md-6">
        <?php if ($rec->File_Ext == 'pdf') { ?>
                <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
            <?php } else { ?>
                <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
                <div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #3a495e;"></div>
                <script>
                    var curect_file_path = $('#image').val();
                    $("#imageViewerContainer").verySimpleImageViewer({
                        imageSource: curect_file_path,
                        frame: ['100%', '100%'],
                        maxZoom: '900%',
                        zoomFactor: '10%',
                        mouse: true,
                        keyboard: true,
                        toolbar: true,
                        rotateToolbar: true
                    });
                </script>
            <?php } ?>
        </div>
        <form action="<?= base_url(); ?>Form/RegCert_ctrl/create" id="registrationform" name="registrationform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Certificate Name:</label>
                        <input type="text" name="Certificate_Name" id="Certificate_Name" class="form-control" value="<?= (isset($punch_detail->CertiType)) ? $punch_detail->CertiType : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Certificate No:</label>
                        <input type="text" name="Certificate_No" id="Certificate_No" class="form-control" value="<?= (isset($punch_detail->CertiNo)) ? $punch_detail->CertiNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Certificate Date:</label>
                        <input type="date" name="Certificate_Date" id="Certificate_Date" class="form-control" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Company:</label>
                        <select name="Company" id="Company" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($company_list as $key => $value) { ?>
                                <option value="<?= $value['firm_id'] ?>" <?php if (isset($punch_detail->CompanyID)) {
                                                                                if ($value['firm_id'] == $punch_detail->CompanyID) {
                                                                                    echo "selected";
                                                                                }
                                                                            }  ?>><?= $value['firm_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Department:</label>
                        <select name="Department" id="Department" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($department_list as $key => $value) { ?>
                                <option value="<?= $value['department_id'] ?>" <?php if (isset($punch_detail->DepartmentID)) {
                                                                                if ($value['department_id'] == $punch_detail->DepartmentID) {
                                                                                    echo "selected";
                                                                                }
                                                                            }  ?>><?= $value['department_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Valid From:</label>
                        <input type="date" name="Valid_From" id="Valid_From" class="form-control" value="<?= (isset($punch_detail->ValidFrom)) ? date('Y-m-d', strtotime($punch_detail->ValidFrom)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Valid Upto:</label>
                        <input type="date" name="Valid_Upto" id="Valid_Upto" class="form-control" value="<?= (isset($punch_detail->Validto)) ? date('Y-m-d', strtotime($punch_detail->Validto)) : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="">Remark / Comment:</label>
                        <textarea name="Remark" id="Remark" cols="10" rows="3" class="form-control"><?= (isset($punch_detail->Remark)) ? $punch_detail->Remark : ''  ?></textarea>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success pull-right">Save</button>
                </div>
                <?php
                if ($this->customlib->haveSupportFile($Scan_Id) == 1) {
                ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <label for="">Supporting File:</label>
                            <div class="form-group">

                                <?php
                                $support_file = $this->customlib->getSupportFile($Scan_Id);

                                foreach ($support_file as $row) {
                                ?>
                                    <div class="col-md-3">
                                        <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location'] ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </form>
    </div>

</div>

<script>
    $(document).on('change', '#Company', function() {
        var company_id = $(this).val();
        $.ajax({
            url: "<?= base_url(); ?>Form/RegCert_ctrl/getDepartment",
            type: "post",
            dataType: "json",
            data: {
                company_id: company_id
            },
            beforeSend: function() {
                $('#Department').html('<option value="">Select</option>');
            },
            success: function(response) {
                $.each(response, function(index, value) {
                    $('#Department').append('<option value="' + value.department_id + '">' + value.department_name + '</option>');
                });
            }
        });
    });
</script>