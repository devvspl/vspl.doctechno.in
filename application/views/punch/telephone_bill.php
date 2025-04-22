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
        <form action="<?= base_url(); ?>form/Telephone_ctrl/Save_Telephone_Bill" id="BillForm" name="BillForm" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Bill / Invoice Date:</label>
                        <input type="date" name="Bill_Date" id="Bill_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Invoice / Bill No:</label>
                        <input type="text" name="Invoice_No" id="Invoice_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">BIller Name:</label>
                        <input type="text" name="BIller_Name" id="BIller_Name" class="form-control" value="<?= (isset($punch_detail->FromName)) ? $punch_detail->FromName : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Telephone No:</label>
                        <input type="text" name="Phone_No" id="Phone_No" class="form-control" value="<?= (isset($punch_detail->MobileNo)) ? $punch_detail->MobileNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Invoice Period:</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Invoice Taxable Value:</label>
                        <input type="text" name="Taxable_Value" id="Taxable_Value" class="form-control" value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">CGST:</label>
                        <input type="text" name="CGST" id="CGST" class="form-control" value="<?= (isset($punch_detail->CGST_Amount)) ? $punch_detail->CGST_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">SGST:</label>
                        <input type="text" name="SGST" id="SGST" class="form-control" value="<?= (isset($punch_detail->SGST_Amount)) ? $punch_detail->SGST_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">IGST:</label>
                        <input type="text" name="IGST" id="IGST" class="form-control" value="<?= (isset($punch_detail->GST_IGST_Amount)) ? $punch_detail->GST_IGST_Amount : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Total Amount Due:</label>
                        <input type="text" name="Amount_Due" id="Amount_Due" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Total Amout Outstanding:</label>
                        <input type="text" name="Amout_Outstanding" id="Amout_Outstanding" class="form-control" value="<?= (isset($punch_detail->Grand_Total)) ? $punch_detail->Grand_Total : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Last Payment Date:</label>
                        <input type="date" name="Lst_Payment_Date" id="Lst_Payment_Date" class="form-control" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : ''  ?>">
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