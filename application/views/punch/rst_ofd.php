<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row();

?>
<div class="box-body">
    <div class="row">
        <div class="col-md-6">
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
        <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/save_rst_ofd" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
                <div class="row">
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
                    <div class="col-md-3 form-group">
                        <label for="">Bill Date:</label>
                        <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Crop Detail:</label>
                        <input type="text" name="Crop_Detail" id="Crop_Detail" class="form-control" value="<?= (isset($punch_detail->CropDetails)) ? $punch_detail->CropDetails : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Trial Operation Exp Amount:</label>
                        <input type="text" name="Trial_Op_Exp_Amount" id="Trial_Op_Exp_Amount" class="form-control" value="<?= (isset($punch_detail->MealsAmount)) ? $punch_detail->MealsAmount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Fertilizer Chemicals Amount:</label>
                        <input type="text" name="Fertilizer_Amount" id="Fertilizer_Amount" class="form-control" value="<?= (isset($punch_detail->HallTent_Amount)) ? $punch_detail->HallTent_Amount : ''  ?>" onchange="calculate();">
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Consumable Amount:</label>
                        <input type="text" name="Consumable_Amount" id="Consumable_Amount" class="form-control" value="<?= (isset($punch_detail->Gift_Amount)) ? $punch_detail->Gift_Amount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Miscellaneous Amount:</label>
                        <input type="text" name="Miscellaneous_Amount" id="Miscellaneous_Amount" class="form-control" value="<?= (isset($punch_detail->OthCharge_Amount)) ? $punch_detail->OthCharge_Amount : ''  ?>" onchange="calculate();">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-3" style="float: right;">
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
                if ($this->customlib->haveSupportFile($scan_id) == 1) {
                ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <label for="">Supporting File:</label>
                            <div class="form-group">

                                <?php
                                $support_file = $this->customlib->getSupportFile($scan_id);

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
        var Trial_Op_Exp_Amount = $('#Trial_Op_Exp_Amount').val();
        var Fertilizer_Amount = $('#Fertilizer_Amount').val();
        var Consumable_Amount = $('#Consumable_Amount').val();
        var Miscellaneous_Amount = $('#Miscellaneous_Amount').val();
        if (Trial_Op_Exp_Amount == '' || Trial_Op_Exp_Amount == null) {
            Trial_Op_Exp_Amount = 0;
        }
        if (Fertilizer_Amount == '' || Fertilizer_Amount == null) {
            Fertilizer_Amount = 0;
        }
        if (Consumable_Amount == '' || Consumable_Amount == null) {
            Consumable_Amount = 0;
        }
        if (Miscellaneous_Amount == '' || Miscellaneous_Amount == null) {
            Miscellaneous_Amount = 0;
        }

        var Total_Amount = parseFloat(Trial_Op_Exp_Amount) + parseFloat(Fertilizer_Amount) + parseFloat(Consumable_Amount) + parseFloat(Miscellaneous_Amount);
        $('#Total_Amount').val(Total_Amount.toFixed(2));
    }
</script>