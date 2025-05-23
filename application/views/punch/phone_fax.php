<?php
$scan_id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
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
        <form action="<?= base_url(); ?>form/Telephone_ctrl/save_phone_fax" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Bill Date:</label>
                        <input type="date" name="Bill_Date" id="Bill_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Payment Mode:</label>
                        <input type="text" name="Payment_Mode" id="Payment_Mode" class="form-control" value="<?= (isset($punch_detail->NatureOfPayment)) ? $punch_detail->NatureOfPayment : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Due Date:</label>
                        <input type="date" name="Due_Date" id="Due_Date" class="form-control" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d', strtotime($punch_detail->DueDate)) : ''  ?>">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Billing Cycle:</label>
                        <input type="text" name="Billing_Cycle" id="Billing_Cycle" class="form-control" value="<?= (isset($punch_detail->BillingCycle)) ? $punch_detail->BillingCycle : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Billing Person:</label>
                        <input type="text" name="Billing_Person" id="Billing_Person" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Billing Address:</label>
                        <input type="text" name="Billing_Address" id="Billing_Address" class="form-control" value="<?= (isset($punch_detail->Related_Address)) ? $punch_detail->Related_Address : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Mobile Service:</label>
                        <select name="Mobile_Service" id="Mobile_Service" class="form-control">
                            <option value="">Select</option>
                            <?php
                            $type = array('Prepaid', 'Postpaid');
                            foreach ($type as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->File_Type) && $punch_detail->File_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Mobile No:</label>
                        <input type="text" name="Mobile_No" id="Mobile_No" class="form-control" value="<?= (isset($punch_detail->MobileNo)) ? $punch_detail->MobileNo : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Tariff Plan:</label>
                        <input type="text" name="Tarrif_Plan" id="Tarrif_Plan" class="form-control" value="<?= (isset($punch_detail->TariffPlan)) ? $punch_detail->TariffPlan : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Previous Balance:</label>
                        <input type="text" name="Previous_Balance" id="Previous_Balance" class="form-control" value="<?= (isset($punch_detail->PreviousBalance)) ? $punch_detail->PreviousBalance : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Charges:</label>
                        <input type="text" name="Charges" id="Charges" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Last Payment Detail:</label>
                        <input type="text" name="Last_Payment_Detail" id="Last_Payment_Detail" class="form-control" value="<?= (isset($punch_detail->LastPayement)) ? $punch_detail->LastPayement : ''  ?>">
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
    $(document).on('change', '#Type', function() {
        var type = $(this).val();
        if (type == 'Cash Deposit') {
            $('#amount_type').html('Deposit Amount:');
        } else {
            $('#amount_type').html('Withdrawal Amount:');
        }
    });
</script>