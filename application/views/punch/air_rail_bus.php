<?php
$Scan_Id = $this->uri->segment(2);
$DocType_Id = $this->uri->segment(3);
$rec = $this->customlib->getScanData($Scan_Id);
$punch_detail = $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row();
$locationlist = $this->customlib->getWorkLocationList();
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
        <form action="<?= base_url(); ?>form/Vehicle_ctrl/Save_Air_Bus_Fare" id="airbustrainform" name="airbustrainform" method="post" accept-charset="utf-8">
            <div class="col-md-6">
                <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
                <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Mode:</label>
                        <select name="Travel_Mode" id="Travel_Mode" class="form-control">
                            <?php
                            $travel_mode = array('Air', 'Rail', 'Bus');
                            ?>
                            <?php foreach ($travel_mode as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="" id="trainbuslabel">Flight Name:</label>
                        <input type="text" name="TrainBusName" id="TrainBusName" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Quota:</label>
                        <select name="Quota" id="Quota" class="form-control">
                            <?php
                            $quota = array('General', 'Tatkal', 'Premium Tatkal', 'Other');
                            ?>
                            <?php foreach ($quota as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Class:</label>
                        <select name="Class" id="Class" class="form-control">
                            <?php
                            $class = array('CC', 'SL', 'AC', 'Economy', 'Econmy AC', 'Business', 'Other');
                            ?>
                            <?php foreach ($class as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">Booking Date:</label>
                        <input type="text" name="Booking_Date" id="Booking_Date" class="form-control datepicker" value="<?= (isset($punch_detail->From)) ? $punch_detail->From : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Journey Date:</label>
                        <input type="text" name="Journey_Date" id="Journey_Date" class="form-control datepicker" value="<?= (isset($punch_detail->To)) ? $punch_detail->To : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Journey From:</label>
                        <input type="text" name="Journey_From" id="Journey_From" class="form-control" value="<?= (isset($punch_detail->Date)) ? $punch_detail->Date : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Journey Upto:</label>
                        <input type="text" name="Journey_Upto" id="Journey_Upto" class="form-control" value="<?= (isset($punch_detail->Time)) ? $punch_detail->Time : ''  ?>">
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="">Passenger Detail:</label>
                        <input type="text" name="Passenger" id="Passenger" class="form-control" value="<?= (isset($punch_detail->Vehicle_Type)) ? $punch_detail->Vehicle_Type : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Booking Statis:</label>
                        <select name="Booking_Status" id="Booking_Status" class="form-control">
                            <?php
                            $booking_status = array('Confirmed', 'Waiting');
                            ?>
                            <?php foreach ($booking_status as $key => $value) { ?>
                                <option value="<?= $value ?>" <?php if (isset($punch_detail->Vehicle_Type) && $punch_detail->Vehicle_Type == $value) {
                                                                    echo "selected";
                                                                } ?>><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Travel Insurance:</label>
                        <input type="text" name="Travel_Insurance" id="Travel_Insurance" class="form-control" value="<?= (isset($punch_detail->Vehicle_Type)) ? $punch_detail->Vehicle_Type : ''  ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Amount:</label>
                        <input type="text" name="Amount" id="Amount" class="form-control" value="<?= (isset($punch_detail->Vehicle_Type)) ? $punch_detail->Vehicle_Type : ''  ?>">
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
    $(document).on('change', '#Travel_Mode', function() {
        var mode = $(this).val();
        if (mode == 'Air') {
            $('#trainbuslabel').html('Flight Name:');
        } else if (mode == 'Rail') {
            $('#trainbuslabel').html('Train Name:');
        } else if (mode == 'Bus') {
            $('#trainbuslabel').html('Bus Name:');
        }
    });
    $('.datepicker').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
    });
</script>
