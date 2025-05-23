<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$fin_year = $this->customlib->getFinancial_year();
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile2', ['scan_id' => $Scan_Id])->row();

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
        <form action="<?= base_url(); ?>form/Bank_ctrl/save_cheque" id="chequeform" name="chequeform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Branch Name:</label>
                        <input type="text" name="Branch_Name" id="Branch_Name" class="form-control" value="<?= (isset($punch_detail->BankAddress)) ? $punch_detail->BankAddress : ''  ?>">
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="">IFSC Code:</label>
                        <input type="text" name="IFSC_Code" id="IFSC_Code" class="form-control" value="<?= (isset($punch_detail->BankIfscCode)) ? $punch_detail->BankIfscCode : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Account No:</label>
                        <input type="text" name="Account_No" id="Account_No" class="form-control" value="<?= (isset($punch_detail->BankAccountNo)) ? $punch_detail->BankAccountNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Cheque No:</label>
                        <input type="text" name="Cheque_No" id="Cheque_No" class="form-control" value="<?= (isset($punch_detail->ChequeNo)) ? $punch_detail->ChequeNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Cheque Date:</label>
                        <input type="date" name="Cheque_Date" id="Cheque_Date" class="form-control" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Payee Name:</label>
                        <input type="text" name="Payee" id="Payee" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Cheque Amount:</label>
                        <input type="text" name="Cheque_Amount" id="Cheque_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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