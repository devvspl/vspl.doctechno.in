<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$fin_year = $this->customlib->getFinancial_year();
$company_list = $this->customlib->getCompanyList();
$report_type = $this->customlib->getReportType();
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
        <form action="<?= base_url(); ?>Form/CompanyRecord_ctrl/create" id="company_recordform" name="boardingpassform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Record Type:</label>
                        <select name="Record_Type" id="Record_Type" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($report_type as $key => $value) { ?>
                                <option value="<?= $value['report_alias'] ?>" <?php if (isset($punch_detail->File_Type)) {
                                                                                    if ($value['report_alias'] == $punch_detail->File_Type) {
                                                                                        echo "selected";
                                                                                    }
                                                                                }  ?>><?= $value['report_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
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

                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Auditor Name:</label>
                        <input type="text" name="Auditor_Name" id="Auditor_Name" class="form-control" value="<?= (isset($punch_detail->AuditorName)) ? $punch_detail->AuditorName : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Date of Sign:</label>
                        <input type="date" name="Sign_Date" id="Sign_Date" class="form-control" value="<?= (isset($punch_detail->DateofSign)) ? date('Y-m-d', strtotime($punch_detail->DateofSign)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Financial Year:</label>
                        <select name="Financial_Year" id="Financial_Year" class="form-control">
                            <?php
                            foreach ($fin_year as $row) {
                            ?>
                                <option value="<?= $row['Year'] ?>" <?php if (isset($punch_detail->FinYear)) {
                                                                        if ($row['Year'] == $punch_detail->FinYear) {
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