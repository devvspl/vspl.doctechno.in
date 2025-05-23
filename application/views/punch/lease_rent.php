<?php
$scan_id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$company_list = $this->customlib->getCompanyList();
$fin_year = $this->customlib->getFinancial_year();
$punch_detail = $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row();

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
        <form action="<?= base_url(); ?>form/Property_ctrl/save_lease_rent" id="form" name="form" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Lessor Name:</label>
                        <input type="text" name="Lessor_Name" id="Lessor_Name" class="form-control" value="<?= (isset($punch_detail->FromName)) ? $punch_detail->FromName : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Lessee Name: </label>
                        <input type="text" name="Lessee_Name" id="Lessee_Name" class="form-control" value="<?= (isset($punch_detail->ToName)) ? $punch_detail->ToName : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Property Address:</label>
                        <input type="text" name="Property_Address" id="Property_Address" class="form-control" value="<?= (isset($punch_detail->Loc_Add)) ? $punch_detail->Loc_Add : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Propery Area:</label>
                        <input type="text" name="Property_Area" id="Property_Area" class="form-control" value="<?= (isset($punch_detail->PropertyArea)) ? $punch_detail->PropertyArea : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Other Specification:</label>
                        <input type="text" name="Other_Specification" id="Other_Specification" class="form-control" value="<?= (isset($punch_detail->OtherSpecif)) ? $punch_detail->OtherSpecif : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Lease Start Period:</label>
                        <input type="text" name="Lease_Start_Period" id="Lease_Start_Period" class="form-control datepicker" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Lease End Period:</label>
                        <input type="text" name="Lease_End_Period" id="Lease_End_Period" class="form-control datepicker" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d', strtotime($punch_detail->ToDateTime)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Payment Frequency:</label>
                        <select name="Payment_Frequency" id="Payment_Frequency" class="form-control" value="<?= (isset($punch_detail->BillingCycle)) ? $punch_detail->BillingCycle : ''  ?>">
                            <option value="">Select</option>
                            <?php
                            $type = array('Monthly', 'Quarterly', 'Half Yearly', 'Yearly');
                            foreach ($type as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->BillingCycle) && $punch_detail->BillingCycle == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Lease Rent Taxable Value:</label>
                        <input type="text" name="Taxable_Value" id="Taxable_Value" class="form-control" value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">CGST:</label>
                        <input type="text" name="CGST" id="CGST" class="form-control" value="<?= (isset($punch_detail->CGST_Amount)) ? $punch_detail->CGST_Amount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">SGST:</label>
                        <input type="text" name="SGST" id="SGST" class="form-control" value="<?= (isset($punch_detail->SGST_Amount)) ? $punch_detail->SGST_Amount : ''  ?>" onchange="calculate();">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">IGST:</label>
                        <input type="text" name="IGST" id="IGST" class="form-control" value="<?= (isset($punch_detail->GST_IGST_Amount)) ? $punch_detail->GST_IGST_Amount : ''  ?>" onchange="calculate();">
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3 form-group" style="float: right;">
                        <label for="">Total Amount:</label>
                        <input type="text" name="Total_Amount" id="Total_Amount" class="form-control" readonly value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
    $(".datepicker").datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
    });

    function calculate() {
        var Taxable_Value = $('#Taxable_Value').val();
        var CGST = $('#CGST').val();
        var SGST = $('#SGST').val();
        var IGST = $('#IGST').val();
        if (Taxable_Value == '' || Taxable_Value == null) {
            Taxable_Value = 0;
        }
        if (CGST == '' || CGST == null) {
            CGST = 0;
        }
        if (SGST == '' || SGST == null) {
            SGST = 0;
        }
        if (IGST == '' || IGST == null) {
            IGST = 0;
        }

        var Total_Amount = parseFloat(Taxable_Value) + parseFloat(CGST) + parseFloat(SGST) + parseFloat(IGST);
        $('#Total_Amount').val(Total_Amount.toFixed(2));
    }
</script>