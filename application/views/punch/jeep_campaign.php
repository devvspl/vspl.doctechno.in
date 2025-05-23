<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['scan_id' => $Scan_Id])->row();

?>
<div class="box-body">
    <div class="row">
        <div class="col-md-5">
            <?php if ($rec->file_extension == 'pdf') { ?>
                <object data="<?= $rec->file_path ?>" type="" height="490px" width="100%;"></object>
            <?php } else { ?>
                <input type="hidden" name="image" id="image" value="<?= $rec->file_path ?>">
                <div id="imageViewerContainer" style=" width: 450px; height:490px; border:2px solid #1b98ae;"></div>
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
        <form action="<?= base_url(); ?>form/Vehicle_ctrl/save_jeep_campaign" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">

                    <div class="col-md-3 form-group">
                        <label for="">Bill Date:</label>
                        <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Vegetable:</label>
                        <input type="text" name="Vegetable" id="Vegetable" class="form-control" value="<?= (isset($punch_detail->CropDetails)) ? $punch_detail->CropDetails : ''  ?>">
                    </div>

                </div>
                <div class="row">

                    <div class="form-group col-md-3">
                        <label for="">Hired Vehicle (HVC):</label>
                        <input type="text" name="HVC" id="HVC" class="form-control" value="<?= (isset($punch_detail->HiredVehicle_Amount)) ? $punch_detail->HiredVehicle_Amount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">AV (AVT):</label>
                        <input type="text" name="AV" id="AV" class="form-control" value="<?= (isset($punch_detail->AVTent_Amount)) ? $punch_detail->AVTent_Amount : ''  ?>" onchange="calculate();">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="">Other (OTH):</label>
                        <input type="text" name="Other" id="Other" class="form-control" value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Total Amount:</label>
                        <input type="text" name="Total_Amount" id="Total_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>" readonly>
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
                                        <a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
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
    function calculate() {
        var HVC = $('#HVC').val();
        var AV = $('#AV').val();
        var Other = $('#Other').val();
        if (HVC == '' || HVC == null) {
            HVC = 0;
        }
        if (AV == '' || AV == null) {
            AV = 0;
        }
        if (Other == '' || Other == null) {
            Other = 0;
        }

        var Total_Amount = parseFloat(HVC) + parseFloat(AV) + parseFloat(Other);
        $('#Total_Amount').val(Total_Amount.toFixed(2));
    }
</script>