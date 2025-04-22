<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();

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
        <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/Save_Dealer_Meeting" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Bill Date:</label>
                        <input type="date" name="Bill_Date" id="Bill_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Crop</label>
                        <select name="Crop" id="Crop" class="form-control">
                            <option value="">Select</option>
                            <?php
                            $type = array('Vegetable', 'Field Crop');
                            foreach ($type as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->File_Type) && $punch_detail->File_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">Crop Detail:</label>
                        <input type="text" name="Crop_Detail" id="Crop_Detail" class="form-control" value="<?= (isset($punch_detail->CropDetails)) ? $punch_detail->CropDetails : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Meals (MLS):</label>
                        <input type="text" name="Meals" id="Meals" class="form-control" onchange="calculate();" value="<?= (isset($punch_detail->MealsAmount)) ? $punch_detail->MealsAmount : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Hall/Tent (HNT):</label>
                        <input type="text" name="Tent" id="Tent" class="form-control" onchange="calculate();" value="<?= (isset($punch_detail->HallTent_Amount)) ? $punch_detail->HallTent_Amount : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Gift(GFT):</label>
                        <input type="text" name="Gift" id="Gift" class="form-control" onchange="calculate();" value="<?= (isset($punch_detail->Gift_Amount)) ? $punch_detail->Gift_Amount : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">AV (AV):</label>
                        <input type="text" name="AV" id="AV" class="form-control" onchange="calculate();" value="<?= (isset($punch_detail->AVTent_Amount)) ? $punch_detail->AVTent_Amount : ''  ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Other(OTH):</label>
                        <input type="text" name="Other" id="Other" class="form-control" onchange="calculate();" value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group" style="float: right;">
                        <label for="">Total Amount:</label>
                        <input type="text" name="Total_Amount" id="Total_Amount" class="form-control" readonly value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
    function calculate() {
        var Meals = $('#Meals').val();
        var Tent = $('#Tent').val();
        var Gift = $('#Gift').val();
        var AV = $('#AV').val();
        var Other = $('#Other').val();
        if (Meals == '' || Meals == null) {
            Meals = 0;
        }
        if (Tent == '' || Tent == null) {
            Tent = 0;
        }
        if (Gift == '' || Gift == null) {
            Gift = 0;
        }
        if (AV == '' || AV == null) {
            AV = 0;
        }
        if (Other == '' || Other == null) {
            Other = 0;
        }
        var Total = parseFloat(Meals) + parseFloat(Tent) + parseFloat(Gift) + parseFloat(AV) + parseFloat(Other);
        $('#Total_Amount').val(Total.toFixed(2));
    }
</script>