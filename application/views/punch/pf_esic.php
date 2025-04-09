<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$company_list = $this->customlib->getCompanyList();
$punch_detail = $this->db->get_where('punchfile2', ['Scan_Id' => $Scan_Id])->row();
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
        <form action="<?= base_url(); ?>Form/PF_ctrl/create" id="pfform" name="pfform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">Payment Head:</label>
                        <input type="text" name="Payment_head" id="Payment_head" class="form-control" value="<?= (isset($punch_detail->PaymentHead)) ? $punch_detail->PaymentHead : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Date:</label>
                        <input type="date" name="Date" id="Date" class="form-control" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Month:</label>
                        <?php

                        $Month_Array = array(
                            'January' => 'January',
                            'February' => 'February',
                            'March' => 'March',
                            'April' => 'April',
                            'May' => 'May',
                            'June' => 'June',
                            'July' => 'July',
                            'August' => 'August',
                            'September' => 'September',
                            'October' => 'October',
                            'November' => 'November',
                            'December' => 'December',

                        );
                        ?>
                        <select name="Month" id="Month" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($Month_Array as $key => $value) { ?>
                                <option value="<?= $key ?>" <?php if (isset($punch_detail->DocMonth)) {
                                                                if ($value == $punch_detail->DocMonth) {
                                                                    echo "selected";
                                                                }
                                                            }  ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">TRRN:</label>
                        <input type="text" name="TRRN" id="TRRN" class="form-control" value="<?= (isset($punch_detail->TRRN)) ? $punch_detail->TRRN : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">CRN:</label>
                        <input type="text" name="CRN" id="CRN" class="form-control" value="<?= (isset($punch_detail->CRN)) ? $punch_detail->CRN : ''  ?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">Amount:</label>
                        <input type="text" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->TotalAmount)) ? $punch_detail->TotalAmount : ''  ?>">
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