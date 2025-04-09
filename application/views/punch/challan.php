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
        <form action="<?= base_url(); ?>Form/Challan_ctrl/save_challan" id="challanform" name="challanform" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">

                    <div class="col-md-3 form-group">
                        <label for="">Date:</label>
                        <input type="date" name="Bill_Date" id="Bill_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d',strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Challan Serial No:</label>
                        <input type="text" name="Challan_No" id="Challan_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>
                    <div class="fom-group col-md-3">
                        <label for="">Challan Purpose</label>
                        <input type="text" name="Purpose" id="Purpose" class="form-control" value="<?= (isset($punch_detail->ChallanPurpose)) ? $punch_detail->ChallanPurpose : ''  ?>">
                    </div>
                    <div class="fom-group col-md-3">
                        <label for="">Perid of Payment</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
                    </div>

                </div>
                <div class="row">
                <div class="form-group col-md-3">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankNmae)) ? $punch_detail->BankNmae : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Reference Payment No:</label>
                        <input type="text" name="Ref_No" id="Ref_No" class="form-control" value="<?= (isset($punch_detail->ServiceNo)) ? $punch_detail->ServiceNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Bank BSR Code:</label>
                        <input type="text" name="BSR_Code" id="BSR_Code" class="form-control" value="<?= (isset($punch_detail->BankBSRCode)) ? $punch_detail->BankBSRCode : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Challan Amount:</label>
                        <input type="text" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
