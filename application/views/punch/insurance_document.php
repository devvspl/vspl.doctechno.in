<?php
$scan_id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$company_list = $this->customlib->getCompanyList();
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
        <form action="<?= base_url(); ?>form/Insurance_ctrl/save_insurance_document" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Policy Holder Name:</label>
                        <input type="text" name="Policy_Holder_Name" id="Policy_Holder_Name" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Policy Number:</label>
                        <input type="text" name="Policy_Number" id="Policy_Number" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Policy Type:</label>
                        <input type="text" name="Policy_Type" id="Policy_Type" class="form-control" value="<?= (isset($punch_detail->File_Type)) ? $punch_detail->File_Type : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Policy Date:</label>
                        <input type="text" name="Policy_Date" id="Policy_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Nominee:</label>
                        <input type="text" name="Nominee" id="Nominee" class="form-control" value="<?= (isset($punch_detail->NomineeDetails)) ? $punch_detail->NomineeDetails : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Sum Assured:</label>
                        <input type="text" name="Sum_Assured" id="Sum_Assured" class="form-control" value="<?= (isset($punch_detail->SumAssured)) ? $punch_detail->SumAssured : ''  ?>">
                    </div>
                    <div class="col-md-3 from-group">
                        <label for="Premium_Date">Premium Date:</label>
                        <input type="text" name="Premium_Date" id="Premium_Date" class="form-control datepicker" value="<?= (isset($punch_detail->PremiumDate)) ? date('Y-m-d',strtotime($punch_detail->PremiumDate )): ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Maturity Date:</label>
                        <input type="text" name="Maturity_Date" id="Maturity_Date" class="form-control datepicker" value="<?= (isset($punch_detail->MaturityDate)) ? date('Y-m-d',strtotime($punch_detail->MaturityDate)) : ''  ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Period:</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Due Date:</label>
                        <input type="text" name="Due_Date" id="Due_Date" class="form-control datepicker" value="<?= (isset($punch_detail->DueDate)) ? date('Y-m-d',strtotime($punch_detail->DueDate)) : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Coverage:</label>
                        <input type="text" name="Coverage" id="Coverage" class="form-control" value="<?= (isset($punch_detail->Coverage)) ? $punch_detail->Coverage : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Vehicle No:</label>
                        <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Premium Amount:</label>
                        <input type="text" name="Premium_Amount" id="Premium_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Agent Branch:</label>
                        <input type="text" name="Agent_Branch" id="Agent_Branch" class="form-control" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : ''  ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Insured Details:</label>
                        <input type="text" name="Insured_Details" id="Insured_Details" class="form-control" value="<?= (isset($punch_detail->PassengerDetails)) ? $punch_detail->PassengerDetails : ''  ?>">
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
        format: 'Y-m-d'
    })
</script>