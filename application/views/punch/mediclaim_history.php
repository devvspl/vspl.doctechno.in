<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$punch_detail = $this->db->get_where('punchfile2', ['Scan_Id' => $Scan_Id])->row();
?>
<div class="box-body">
    <div class="row">
        <div class="col-md-6">
        <?php if ($rec->File_Ext == 'pdf') { ?>
                <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
            <?php } else { ?>
                <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
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
        <form action="<?= base_url(); ?>form/Mediclaim_ctrl/create" id="mediclaimform" name="mediclaimform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Date:</label>
                        <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Problem / Issue:</label>
                        <input type="text" name="Problem" id="Problem" class="form-control" value="<?= (isset($punch_detail->ProblemIssue)) ? $punch_detail->ProblemIssue : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Company:</label>
                        <input type="text" name="Company" id="Company" class="form-control" value="<?= (isset($punch_detail->Company)) ? $punch_detail->Company : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Policy Holde Name:</label>
                        <input type="text" name="Policy_Holder" id="Policy_Holder" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Period:</label>
                        <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->PeriodDuration)) ? $punch_detail->PeriodDuration : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Hospital:</label>
                        <input type="text" name="Hospital" id="Hospital" class="form-control" value="<?= (isset($punch_detail->Hospital)) ? $punch_detail->Hospital : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    
                    <div class="form-group col-md-6">
                        <label for="">Doctor:</label>
                        <input type="text" name="Doctor" id="Doctor" class="form-control" value="<?= (isset($punch_detail->Doctor)) ? $punch_detail->Doctor : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Medicine:</label>
                        <input type="text" name="Medicine" id="Medicine" class="form-control" value="<?= (isset($punch_detail->Medicine)) ? $punch_detail->Medicine : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="">Remedy:</label>
                        <input type="text" id="Remedy" name="Remedy" class="form-control" value="<?= (isset($punch_detail->Remedy)) ? $punch_detail->Remedy : ''  ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Treatment Taken :</label>
                        <input type="text" id="Treatment" name="Treatment" class="form-control" value="<?= (isset($punch_detail->TreatmentTaken)) ? $punch_detail->TreatmentTaken : ''  ?>">
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