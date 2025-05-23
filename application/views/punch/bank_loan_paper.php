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
        <form action="<?= base_url(); ?>form/Bank_ctrl/save_bank_loan_paper" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Company:</label>
                        <select name="Company" id="Company" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($company_list as $key => $value) { ?>
                                <option value="<?= $value['firm_id'] ?>" <?php if (isset($punch_detail->CompanyID)) {
                                                                                if ($value['firm_id'] == $punch_detail->CompanyID) {
                                                                                    echo "selected";
                                                                                }
                                                                            }  ?>><?= $value['firm_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Branch:</label>
                        <input type="text" name="Branch" id="Branch" class="form-control" value="<?= (isset($punch_detail->BankAddress)) ? $punch_detail->BankAddress : ''  ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Date of Sanction:</label>
                        <input type="date" name="Sanction_Date" id="Sanction_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d',strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Sanction Amount:</label>
                        <input type="text" name="Sanction_Amount" id="Sanction_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Period:</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Due Date:</label>
                        <input type="date" name="Due_Date" id="Due_Date" class="form-control" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d',strtotime($punch_detail->DueDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Renewal Date:</label>
                        <input type="date" name="Renewal_Date" id="Renewal_Date" class="form-control" value="<?= (isset($punch_detail->RenewalDate)) ? date('Y-m-d',strtotime($punch_detail->RenewalDate)) : ''  ?>">
                    </div>
                   
                    <div class="form-group col-md-3">
                        <label for="">Type of Document:</label>
                        <input type="text" name="Type_Doc" id="Type_Doc" class="form-control" value="<?= (isset($punch_detail->File_Type)) ? $punch_detail->File_Type : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Paper Submitted:</label>
                        <input type="text" name="Paper_Submitted" id="Paper_Submitted" class="form-control" value="<?= (isset($punch_detail->PaperSubmitted)) ? $punch_detail->PaperSubmitted : ''  ?>">
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