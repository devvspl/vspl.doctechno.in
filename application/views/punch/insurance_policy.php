<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Insurance_ctrl/save_insurance_policy" id="form" name="form" method="post" accept-charset="utf-8">
         <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="col-md-3 form-group">
               <label for="">Insurance Type:</label>
               <input type="text" name="Insurance_Type" id="Insurance_Type" class="form-control" value="<?= (isset($punch_detail->File_Type)) ? $punch_detail->File_Type : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Insurance Company:</label>
               <input type="text" name="Insurance_Company" id="Insurance_Company" class="form-control" value="<?= (isset($punch_detail->AgentName)) ? $punch_detail->AgentName : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Policy Number:</label>
               <input type="text" name="Policy_Number" id="Policy_Number" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Policy Date:</label>
               <input type="text" name="Policy_Date" id="Policy_Date" class="form-control datepicker" value="<?= (isset($punch_detail->File_Date)) ? date('Y-m-d',strtotime($punch_detail->File_Date)) : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 from-group">
               <label for="From_Date">From Date:</label>
               <input type="text" name="From_Date" id="From_Date" class="form-control datepicker" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d',strtotime($punch_detail->FromDateTime)) : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">To Date:</label>
               <input type="text" name="To_Date" id="To_Date" class="form-control datepicker" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d',strtotime($punch_detail->ToDateTime)) : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Vehicle No:</label>
               <input type="text" name="Vehicle_No" id="Vehicle_No" class="form-control" value="<?= (isset($punch_detail->VehicleRegNo)) ? $punch_detail->VehicleRegNo : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Location:</label>
               <input type="text" name="Location" id="Location" class="form-control" value="<?= (isset($punch_detail->Loc_Name)) ? $punch_detail->Loc_Name : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 form-group" style="float: right;">
               <label for="">Premium Amount:</label>
               <input type="text" name="Premium_Amount" id="Premium_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
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
            <?php if (!empty($user_permission) &&  $user_permission == 'N') : ?>
               <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit" value="Final Submit"></input>
            <?php endif; ?>
          
            <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')) : ?>
            <input type="submit" class="btn btn-info pull-right"  name="save_as_draft" value="Save as Draft"></input>
            <?php endif; ?>
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
   </form>
</div>
<script>
   $(".datepicker").datetimepicker({
       timepicker: false,
       format: 'Y-m-d',
   })
   $(document).ready(function () {
   $("#invoice-tab").click(function () {
        $("#additional-info").removeClass("active");
        $("#invoice-details").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });

    $("#additional-info-tab").click(function () {
        $("#invoice-details").removeClass("active");
        $("#additional-info").addClass("active");
        $(".tabs").removeClass("active-tab");
        $(this).addClass("active-tab");
    });
});
</script>