<?php
$scan_id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
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
        <form action="<?= base_url(); ?>form/ImportExport_ctrl/create" id="importexportform" name="importexportform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Document Type:</label>
                        <select name="Document_Type" id="Document_Type" class="form-control">
                            <option value="">Select</option>
                            <option value="LandingBill" <?php if (isset($punch_detail->File_Type)) {
                                                            if ($punch_detail->File_Type == 'LandingBill') {
                                                                echo 'Selected';
                                                            }
                                                        } ?>>Bill of Landing</option>
                            <option value="OriginCertificate" <?php if (isset($punch_detail->File_Type)) {
                                                                    if ($punch_detail->File_Type == 'OriginCertificate') {
                                                                        echo 'Selected';
                                                                    }
                                                                } ?>>Certificate of Origin</option>
                            <option value="BankGaurantee" <?php if (isset($punch_detail->File_Type)) {
                                                                if ($punch_detail->File_Type == 'BankGaurantee') {
                                                                    echo 'Selected';
                                                                }
                                                            } ?>>Bank Gaurantee</option>
                        </select>
                    </div>
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
                        <label for="">Type:</label>
                        <select name="Type" id="Type" class="form-control">
                            <option value="">Select</option>
                            <option value="Import" <?php if (isset($punch_detail->CertiType)) {
                                                        if ($punch_detail->CertiType == 'Import') {
                                                            echo 'Selected';
                                                        }
                                                    } ?>>Import</option>
                            <option value="Export" <?php if (isset($punch_detail->CertiType)) {
                                                        if ($punch_detail->CertiType == 'Export') {
                                                            echo 'Selected';
                                                        }
                                                    } ?>>Export</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Invoice Number:</label>
                        <input type="text" name="Invoice_Number" id="Invoice_Number" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Packing List:</label>
                        <input type="text" name="Packing_List" id="Packing_List" class="form-control" value="<?= (isset($punch_detail->PackingList)) ? $punch_detail->PackingList : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">L/c Advance:</label>
                        <input type="text" name="LC_Advance" id="LC_Advance" class="form-control" value="<?= (isset($punch_detail->LcAdvance)) ? $punch_detail->LcAdvance : ''  ?>">
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