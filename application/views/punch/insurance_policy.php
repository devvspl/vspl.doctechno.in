<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="form" name="form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="insurance_type">Insurance Type:</label>
            <input type="text" name="insurance_type" id="insurance_type" class="form-control"
               value="<?= (isset($punch_detail->insurance_type)) ? $punch_detail->insurance_type : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="insurance_company">Insurance Company:</label>
            <input type="text" name="insurance_company" id="insurance_company" class="form-control"
               value="<?= (isset($punch_detail->insurance_company)) ? $punch_detail->insurance_company : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="policy_number">Policy Number:</label>
            <input type="text" name="policy_number" id="policy_number" class="form-control"
               value="<?= (isset($punch_detail->policy_number)) ? $punch_detail->policy_number : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="policy_date">Policy Date:</label>
            <input type="text" name="policy_date" id="policy_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->policy_date)) ? date('Y-m-d', strtotime($punch_detail->policy_date)) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="from_date">From Date:</label>
            <input type="text" name="from_date" id="from_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->from_date)) ? date('Y-m-d', strtotime($punch_detail->from_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="to_date">To Date:</label>
            <input type="text" name="to_date" id="to_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->to_date)) ? date('Y-m-d', strtotime($punch_detail->to_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="vehicle_no">Vehicle No:</label>
            <input type="text" name="vehicle_no" id="vehicle_no" class="form-control"
               value="<?= (isset($punch_detail->vehicle_no)) ? $punch_detail->vehicle_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="location">Location:</label>
            <input type="text" name="location" id="location_id" class="form-control"
               value="<?= (isset($punch_detail->location)) ? $punch_detail->location : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3" style="float: right;">
            <label for="premium_amount">Premium Amount:</label>
            <input type="text" name="premium_amount" id="premium_amount" class="form-control final_amount_column"
               value="<?= (isset($punch_detail->premium_amount)) ? $punch_detail->premium_amount : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="remark_comment">Remark / Comment:</label>
            <textarea name="remark_comment" id="remark_comment" cols="10" rows="3"
               class="form-control"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : '' ?></textarea>
         </div>
      </div>
      <div class="box-footer">
         <button type="reset" class="btn btn-danger">Reset</button>
         <?php if (!empty($user_permission) && $user_permission == 'N'): ?>
            <input type="submit" class="btn btn-success pull-right" style="margin-left: 20px;" name="submit"
               value="Final Submit"></input>
         <?php endif; ?>
         <?php if (!empty($user_permission) && ($user_permission == 'Y' || $user_permission == 'N')): ?>
            <input type="submit" class="btn btn-info pull-right" name="save_as_draft" value="Save as Draft"></input>
         <?php endif; ?>
      </div>
      <?php if ($this->customlib->haveSupportFile($scan_id) == 1): ?>
         <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
               <label for="supporting_file">Supporting File:</label>
               <div class="form-group">
                  <?php
                  $support_file = $this->customlib->getSupportFile($scan_id);
                  foreach ($support_file as $row) {
                     ?>
                     <div class="col-md-3">
                        <a href="javascript:void(0);" target="popup"
                           onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                           <?php echo $row['file_name'] ?></a>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      <?php endif; ?>
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