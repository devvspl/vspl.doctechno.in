<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();

?>
<div class="box-body">
    <div class="row">
        <div class="col-md-5">
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
        <form action="<?= base_url(); ?>form/Insurance_ctrl/save_insurance_policy" id="form" name="form" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Insurance Type:</label>
                        <input type="text" name="Insurance_Type" id="Insurance_Type" class="form-control" value="<?= (isset($punch_detail->File_Type)) ? $punch_detail->File_Type : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Insurance Company:</label>
                        <input type="text" name="Insurance_Company" id="Insurance_Company" class="form-control" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : ''  ?>">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="">Policy Number:</label>
                        <input type="text" name="Policy_Number" id="Policy_Number" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="">Policy Date:</label>
                        <input type="text" name="Policy_Date" id="Policy_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 from-group">
                        <label for="From_Date">From Date:</label>
                        <input type="text" name="From_Date" id="From_Date" class="form-control datepicker" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d',strtotime($punch_detail->FromDateTime)) : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">To Date:</label>
                        <input type="text" name="To_Date" id="To_Date" class="form-control datepicker" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d',strtotime($punch_detail->ToDateTime)) : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Vehicle No:</label>
                        <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Location:</label>
                        <input type="text" name="Location" id="Location" class="form-control" value="<?= (isset($punch_detail->Loc_Name)) ? $punch_detail->Loc_Name : ''  ?>">
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-3 form-group" style="float: right;">
                        <label for="">Premium Amount:</label>
                        <input type="text" name="Premium_Amount" id="Premium_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
    $(".datepicker").datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
    })
</script>