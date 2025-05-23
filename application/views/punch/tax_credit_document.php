<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($scan_id);
$company_list = $this->customlib->getCompanyList();
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
        <form action="<?= base_url(); ?>form/Tax_ctrl/create" id="taxform" name="taxform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id?>">
                <div class="row">
                    <div class="form-group col-md-6">
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
                    <div class="form-group col-md-6">
                        <label for="">Institution Name:</label>
                        <input type="text" name="Institution_Name" id="Institution_Name" class="form-control" value="<?= (isset($punch_detail->PartyName)) ? $punch_detail->PartyName : ''  ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Form_no Type</label>
                        <select name="FormNo_Type" id="FormNo_Type" class="form-control">
                            <option value="">Select</option>
                            <option value="CA_Certificate" <?php if (isset($punch_detail->File_Type)) {
                                                            if ($punch_detail->File_Type == 'CA_Certificate') {
                                                                echo 'Selected';
                                                            }
                                                        } ?>>CA Certificate</option>
                            <option value="35(2AB)" <?php if (isset($punch_detail->File_Type)) {
                                                            if ($punch_detail->File_Type == '35(2AB)') {
                                                                echo 'Selected';
                                                            }
                                                        } ?>>35(2AB) </option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Certificate Issue Date:</label>
                        <input type="date" name="Certificate_Issue_Date" id="Certificate_Issue_Date" class="form-control" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d',strtotime($punch_detail->FromDateTime)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Valid Upto:</label>
                        <input type="date" name="Valid_Upto" id="Valid_Upto" class="form-control" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d',strtotime($punch_detail->ToDateTime)) : ''  ?>">
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