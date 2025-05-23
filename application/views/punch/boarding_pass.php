<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$punch_detail = $this->db->get_where('punchfile2', ['scan_id' => $scan_id])->row();
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
        <form action="<?= base_url(); ?>form/Boarding_pass_ctrl/create" id="boardingpassform" name="boardingpassform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Trip Started On:</label>
                        <input type="date" name="Trip_Start_Date" id="Trip_Start_Date" class="form-control" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d',strtotime($punch_detail->FromDateTime)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Trip Ended On:</label>
                        <input type="date" name="Trip_End_Date" id="Trip_End_Date" class="form-control" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d',strtotime($punch_detail->ToDateTime)) : ''  ?>">
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Journey From:</label>
                        <input type="text" name="Journey_From" id="Journey_From" class="form-control" value="<?= (isset($punch_detail->From_Location)) ? $punch_detail->From_Location : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Journey Upto:</label>
                        <input type="text" name="Journey_Upto" id="Journey_Upto" class="form-control" value="<?= (isset($punch_detail->To_Location)) ? $punch_detail->To_Location : ''  ?>">
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