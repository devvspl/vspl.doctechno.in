<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="bankstatementform" name="bankstatementform"
      method="post" accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-7">
            <label for="">Location :</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->location : ''; ?>
            </small>
            <select name="Location" id="location_id" class="form-control">
               <option value="">Select Location</option>

            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="">Payment Date :</label>
            <input type="text" name="PaymentDate" id="PaymentDate" class="form-control datepicker"
               value="<?= (isset($punch_detail->payment_date)) ? date('Y-m-d', strtotime($punch_detail->payment_date)) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="">Biller Name:</label>
            <input type="text" name="Biller_Name" id="Biller_Name" class="form-control"
               value="<?= (isset($punch_detail->biller_name)) ? $punch_detail->biller_name : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Business Partner No(BP No):</label>
            <input type="text" name="BP_No" id="BP_No" class="form-control"
               value="<?= (isset($punch_detail->business_partner_no)) ? $punch_detail->business_partner_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Bill Period:</label>
            <input type="text" name="Period" id="Period" class="form-control"
               value="<?= (isset($punch_detail->bill_period)) ? $punch_detail->bill_period : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Meter Number:</label>
            <input type="text" name="Meter_No" id="Meter_No" class="form-control"
               value="<?= (isset($punch_detail->meter_number)) ? $punch_detail->meter_number : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="">Bill Date:</label>
            <input type="text" required name="Bill_Date" id="Bill_Date" class="form-control datepicker"
               value="<?= (isset($punch_detail->bill_date)) ? date('Y-m-d', strtotime($punch_detail->bill_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Bill No:</label>
            <input type="text" required name="Bill_No" id="Bill_No" class="form-control"
               value="<?= (isset($punch_detail->bill_no)) ? $punch_detail->bill_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Previous Meter Reading:</label>
            <input type="text" name="Previous_Reading" id="Previous_Reading" class="form-control"
               value="<?= (isset($punch_detail->previous_meter_reading)) ? $punch_detail->previous_meter_reading : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Current Meter Reading:</label>
            <input type="text" name="Current_Reading" id="Current_Reading" class="form-control"
               value="<?= (isset($punch_detail->current_meter_reading)) ? $punch_detail->current_meter_reading : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="col-md-3 form-group">
            <label for="">Unit Consumed:</label>
            <input type="text" name="Unit_Consumed" id="Unit_Consumed" class="form-control"
               value="<?= (isset($punch_detail->unit_consumed)) ? $punch_detail->unit_consumed : '' ?>">
         </div>
         <div class="col-md-3 form-group">
            <label for="">Last Date of Payment:</label>
            <input type="text" name="Last_Date" id="Last_Date" class="form-control datepicker"
               value="<?= (isset($punch_detail->last_date_of_payment)) ? date('Y-m-d', strtotime($punch_detail->last_date_of_payment)) : '' ?>">
         </div>
         <div class="col-md-3 form-group">
            <label for="">Payment Mode:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->payment_mode : ''; ?>
            </small>
            <select name="Payment_Mode" id="Payment_Mode" class="form-control">
               <option value="">Select</option>
               <?php
               $payment_mode = array('Cash' => 'Cash', 'Cheque' => 'Cheque', 'RTGS' => 'RTGS', 'NEFT' => 'NEFT', 'UPI' => 'UPI', 'Net Banking' => 'Net Banking');
               foreach ($payment_mode as $key => $value) {
                  ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->payment_mode) && $punch_detail->payment_mode == $value) {
                       echo "selected";
                    } ?>><?= $value ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="col-md-3 form-group">
            <label for="">Bill Amount:</label>
            <input type="text" name="Bill_Amount" id="Bill_Amount" class="form-control"
               value="<?= (isset($punch_detail->bill_amount)) ? $punch_detail->bill_amount : '' ?>">
         </div>
         <div class="col-md-3 form-group">
            <label for="">Payment Amount:</label>
            <input type="text" name="Payment_Amount" id="Payment_Amount" class="form-control final_amount_column"
               value="<?= (isset($punch_detail->payment_amount)) ? $punch_detail->payment_amount : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="3"
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
                        <a href="javascript:void(0);" target="popup"
                           onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');">
                           <?php echo $row['file_name'] ?></a>
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
      format: 'Y-m-d'
   });
   $("#location_id").select2();
   $("#CompanyID").select2();
   $("#Payment_Mode").select2();
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
      <?php
      $cleanedlocation = cleanSearchValue(
         isset($temp_punch_detail->location) && !is_null($temp_punch_detail->location) ? $temp_punch_detail->location : ""
      );
      ?>
      loadDropdownOptions(
         'location_id',
         '<?= base_url("extract/ExtractorController/get_location_options") ?>',
         <?= json_encode($cleanedlocation) ?>,
         '<?= isset($punch_detail->location) ? $punch_detail->location : "" ?>'
      );
   });
</script>