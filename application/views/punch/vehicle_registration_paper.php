<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
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
        <form action="<?= base_url(); ?>Form/Vehicle_ctrl/create" id="vehicleform" name="vehicleform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Vehicle No:</label>
                        <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" value="<?= (isset($punch_detail->VehicleNo)) ? $punch_detail->VehicleNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Vehicle Type:</label>
                        <input type="text" name="Vehicle_Type" id="Vehicle_Type" class="form-control" value="<?= (isset($punch_detail->VehicleType)) ? $punch_detail->VehicleType : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Vehicle Company:</label>
                        <input type="text" name="Vehicle_Company" id="Vehicle_Company" class="form-control" value="<?= (isset($punch_detail->VehicleCompany)) ? $punch_detail->VehicleCompany : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Registered:</label>
                        <input type="text" name="Registered" id="Registered" class="form-control" value="<?= (isset($punch_detail->Registered)) ? $punch_detail->Registered : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Registration Date:</label>
                        <input type="date" name="Registration_Date" id="Registration_Date" class="form-control" value="<?= (isset($punch_detail->RegPurDate)) ? date('Y-m-d', strtotime($punch_detail->RegPurDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Clearance Date:</label>
                        <input type="date" name="Clearance_Date" id="Clearance_Date" class="form-control" value="<?= (isset($punch_detail->ClearanceDate)) ? date('Y-m-d', strtotime($punch_detail->ClearanceDate)) : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="">Custody Name</label>
                        <input type="text" name="Custody_Name" id="Custody_Name" class="form-control" value="<?= (isset($punch_detail->CustomerName)) ? $punch_detail->CustomerName : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Hypothecation</label>
                        <input type="text" name="Hypothecation" id="Hypothecation" class="form-control" value="<?= (isset($punch_detail->Hypothecation)) ? $punch_detail->Hypothecation : ''  ?>">
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