<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
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
        <form action="<?= base_url(); ?>form/Tax_ctrl/Save_IT_Return" id="form" name="form" method="post" accept-charset="utf-8">
            <div class="col-md-7">
                <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
                <div class="row">
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
                        <label for="">Financial Year:</label>
                        <select name="Financial_Year" id="Financial_Year" class="form-control">
                            <?php
                            foreach ($fin_year as $row) {
                            ?>
                                <option value="<?= $row['year'] ?>" <?php if (isset($punch_detail->Financial_Year)) {
                                                                        if ($row['year'] == $punch_detail->Financial_Year) {
                                                                            echo "selected";
                                                                        }
                                                                    }  ?>><?= $row['year'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Assessment Year:</label>
                        <select name="Assessment_Year" id="Assessment_Year" class="form-control">
                            <?php
                            foreach ($fin_year as $row) {
                            ?>
                                <option value="<?= $row['year'] ?>" <?php if (isset($punch_detail->BillYear)) {
                                                                        if ($row['year'] == $punch_detail->BillYear) {
                                                                            echo "selected";
                                                                        }
                                                                    }  ?>><?= $row['year'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Filling Date:</label>
                        <input type="text" class="form-control datepicker" name="Filling_Date" id="Filling_Date" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Amount:</label>
                        <input type="text" class="form-control" name="Amount" id="Amount" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Acknowledge No:</label>
                        <input type="text" class="form-control" name="Acknowledge_No" id="Acknowledge_No" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
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
        format: "Y-m-d",
    })
</script>