<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$temp_punch_detail = $this->db->get_where("ext_tempdata_{$DocType_Id}", ['scan_id' => $Scan_Id])->row();

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
        <form action="<?= base_url(); ?>Form/Bank_ctrl/save_cash" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Type</label>
                        	 <small class="text-danger">
                            <?php echo $temp_punch_detail->type; ?>
                        </small>
                        <select name="Type" id="Type" class="form-control">
                            <option value="">Select</option>
                            <?php
                            $type = array('Cash Deposit', 'Cash Withdrawal');
                            foreach ($type as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->File_Type) && $punch_detail->File_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Date:</label>
                        <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? $punch_detail->BillDate : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Branch:</label>
                        <input type="text" name="Branch" id="Branch" class="form-control" value="<?= (isset($punch_detail->BankAddress)) ? $punch_detail->BankAddress : ''  ?>">
                    </div>

                </div>
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Account No:</label>
                        <input type="text" name="Account_No" id="Account_No" class="form-control" value="<?= (isset($punch_detail->BankAccountNo)) ? $punch_detail->BankAccountNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Beneficiary Name:</label>
                        <input type="text" name="Beneficiary_Name" id="Beneficiary_Name" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="" id="amount_type">Amount:</label>
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
<script>
    $(document).on('change', '#Type', function() {
        var type = $(this).val();
        if (type == 'Cash Deposit') {
            $('#amount_type').html('Deposit Amount:');
        } else {
            $('#amount_type').html('Withdrawal Amount:');
        }
    });
</script>