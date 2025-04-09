<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$fin_year = $this->customlib->getFinancial_year();
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
        <form action="<?= base_url(); ?>Form/Tax_ctrl/Save_Income_Tax_TDS" id="tdsform" name="tdsform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Section:</label>
                        <input type="text" name="Section" id="Section" class="form-control" value="<?= (isset($punch_detail->Section)) ? $punch_detail->Section : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Company:</label>

                        <small class="text-danger">
                            <?php echo $temp_punch_detail->company; ?>
                        </small>
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
                    <div class="form-group col-md-3">
                        <label for="">Nature of Payment:</label>
                         <small class="text-danger">
                            <?php echo $temp_punch_detail->nature_of_payment; ?>
                        </small>
                        <small class="text-danger">
                            <?php if(empty($punch_detail->NatureOfPayment)) echo $temp_punch_detail->nature_of_payment; ?>
                        </small>
                        <select name="Payment_Nature" id="Payment_Nature" class="form-control">
                            <?php
                            $payment_nature = array('Income Tax' => 'Income Tax', 'TDS' => 'TDS', 'Advance Tax' => 'Advance Tax', 'Demand Challan' => 'Demand Challan');
                            ?>
                            <option value="">Select</option>
                            <?php foreach ($payment_nature as $key => $value) { ?>
                                <option value="<?= $value; ?>" <?php if (isset($punch_detail->NatureOfPayment)) {
                                                                    if ($value == $punch_detail->NatureOfPayment) {
                                                                        echo "selected";
                                                                    }
                                                                }  ?>><?= $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Assessment Year:</label>
                         <small class="text-danger">
                            <?php echo $temp_punch_detail->assessment_year; ?>
                        </small>
                         <small class="text-danger">
                            <?php if(empty($punch_detail->Financial_Year)) echo $temp_punch_detail->assessment_year; ?>
                        </small>
                        <select name="Assessment_Year" id="Assessment_Year" class="form-control">
                            <?php
                            foreach ($fin_year as $row) {
                            ?>
                                <option value="<?= $row['Year'] ?>" <?php if (isset($punch_detail->Financial_Year)) {
                                                                        if ($row['Year'] == $punch_detail->Financial_Year) {
                                                                            echo "selected";
                                                                        }
                                                                    }  ?>><?= $row['Year'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Bank Name:</label>
                        <input type="text" name="Bank_Name" id="Bank_Name" class="form-control" value="<?= (isset($punch_detail->BankName)) ? $punch_detail->BankName : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">BSR_Code:</label>
                        <input type="text" name="BSR_Code" id="BSR_Code" class="form-control" value="<?= (isset($punch_detail->BSRCode)) ? $punch_detail->BSRCode : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Challan No:</label>
                        <input type="text" name="Challan_No" id="Challan_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Challan Date:</label>
                        <input type="text" name="Challan_Date" id="Challan_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d', strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Bank Reference No:</label>
                        <input type="text" name="Ref_No" id="Ref_No" class="form-control" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group" style="float: right;">
                        <label for="">Amount:</label>
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
    $(".datepicker").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });
</script>