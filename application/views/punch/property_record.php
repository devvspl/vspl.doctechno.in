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
        <form action="<?= base_url(); ?>form/Property_ctrl/create" id="propertyform" name="propertyform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Purchase Date:</label>
                        <input type="date" name="Purchase_Date" id="Purchase_Date" class="form-control" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Seller Name:</label>
                        <input type="text" name="Seller" id="Seller" class="form-control" value="<?= (isset($punch_detail->FromName)) ? $punch_detail->FromName : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Purchaser Name:</label>
                        <input type="text" name="Purchaser" id="Purchaser" class="form-control" value="<?= (isset($punch_detail->ToName)) ? $punch_detail->ToName : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Purchase Value:</label>
                        <input type="text" name="Purchase_Value" id="Purchase_Value" class="form-control" value="<?= (isset($punch_detail->TotalAmount)) ? $punch_detail->TotalAmount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Market Value:</label>
                        <input type="text" name="Market_Value" id="Market_Value" class="form-control" value="<?= (isset($punch_detail->MarketValue)) ? $punch_detail->MarketValue : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Additional Payment:</label>
                        <input type="text" name="Additional_Payment" id="Additional_Payment" class="form-control" value="<?= (isset($punch_detail->ExtraCharge)) ? $punch_detail->ExtraCharge : ''  ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Location:</label>
                        <input type="text" name="Location" id="Location" class="form-control" value="<?= (isset($punch_detail->FileLoc)) ? $punch_detail->FileLoc : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Area:</label>
                        <input type="text" name="Area" id="Area" class="form-control" value="<?= (isset($punch_detail->TotalArea)) ? $punch_detail->TotalArea : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">KH No:</label>
                        <input type="text" name="KH_No" id="KH_No" class="form-control" value="<?= (isset($punch_detail->KHNo)) ? $punch_detail->KHNo : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">PH No:</label>
                        <input type="text" name="PH_No" id="PH_No" class="form-control" value="<?= (isset($punch_detail->PHNo)) ? $punch_detail->PHNo : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Unit:</label>
                        <input type="text" name="Unit" id="Unit" class="form-control" value="<?= (isset($punch_detail->Unit)) ? $punch_detail->Unit : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">RNM / Ward:</label>
                        <input type="text" name="RNM" id="RNM" class="form-control" value="<?= (isset($punch_detail->RNM_Ward)) ? $punch_detail->RNM_Ward : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">New Rin Pushtike No:</label>
                        <input type="text" name="Rin_Pushtika" id="Rin_Pushtika" class="form-control" value="<?= (isset($punch_detail->RinPushtikaNo)) ? $punch_detail->RinPushtikaNo : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">New Khasra No:</label>
                        <input type="text" name="New_Khasra" id="New_Khasra" class="form-control" value="<?= (isset($punch_detail->KhasraNo)) ? $punch_detail->KhasraNo : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Stamp Duty:</label>
                        <input type="text" name="Stamp_Duty" id="Stamp_Duty" class="form-control" value="<?= (isset($punch_detail->Stamp_Duty)) ? $punch_detail->Stamp_Duty : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Approved Map / Diversion:</label>
                        <input type="text" name="Diversion_Paper" id="Diversion_Paper" class="form-control" value="<?= (isset($punch_detail->Diversion_Paper)) ? $punch_detail->Diversion_Paper : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Map Approval Detail:</label>
                        <input type="text" name="Map_Approval_Detail" id="Map_Approval_Detail" class="form-control" value="<?= (isset($punch_detail->Map_Approval)) ? $punch_detail->Map_Approval : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Additional Exposure:</label>
                        <input type="text" name="Additional_Exposure" id="Additional_Exposure" class="form-control" value="<?= (isset($punch_detail->Additional_Exposure)) ? $punch_detail->Additional_Exposure : ''  ?>">
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