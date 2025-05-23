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
        <form action="<?= base_url(); ?>form/Bank_ctrl/save_fixed_deposit" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Deposit Account No:</label>
                        <input type="text" name="Account_No" id="Account_No" class="form-control" value="<?= (isset($punch_detail->DepositAccNo)) ? $punch_detail->DepositAccNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Deposit Amount:</label>
                        <input type="text" name="Deposit_Amount" id="Deposit_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Deposit Rate of Interest:</label>
                        <input type="text" name="Interest" id="Interest" class="form-control" value="<?= (isset($punch_detail->RateOfInterest)) ? $punch_detail->RateOfInterest : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Maturity Amount:</label>
                        <input type="text" name="Maturity_Amount" id="Maturity_Amount" class="form-control" value="<?= (isset($punch_detail->MaturityAmount)) ? $punch_detail->MaturityAmount : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Deposit Start Date:</label>
                        <input type="text" name="Start_Date" id="Start_Date" class="form-control datepicker" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Deposit End Date:</label>
                        <input type="text" name="End_Date" id="End_Date" class="form-control datepicker" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d', strtotime($punch_detail->ToDateTime)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Period:</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
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
    $('.datepicker').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
    });
</script>