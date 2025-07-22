<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="tdsform" name="tdsform" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row">
         <div class="form-group col-md-3">
            <label for="section">Section:</label>
            <input type="text" name="section" id="section" class="form-control"
               value="<?= (isset($punch_detail->section)) ? $punch_detail->section : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="company">Company:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->company : ''; ?>
            </small>
            <select name="company" id="company" class="form-control">
               <option value="">Select</option>
            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="nature_of_payment">Nature of Payment:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->nature_of_payment : ''; ?>
            </small>
            <select name="nature_of_payment" id="nature_of_payment" class="form-control">
               <?php
               $payment_nature = array('Income Tax' => 'Income Tax', 'TDS' => 'TDS', 'Advance Tax' => 'Advance Tax', 'Demand Challan' => 'Demand Challan');
               ?>
               <option value="">Select</option>
               <?php foreach ($payment_nature as $key => $value) { ?>
                  <option value="<?= $value; ?>" <?php if (isset($punch_detail->nature_of_payment) && $value == $punch_detail->nature_of_payment) {
                       echo "selected";
                    } ?>><?= $value; ?></option>
               <?php } ?>
            </select>
         </div>
         <div class="form-group col-md-3">
            <label for="assessment_year">Assessment Year:</label>
            <small class="text-danger">
               <?php echo isset($temp_punch_detail) ? $temp_punch_detail->assessment_year : ''; ?>
            </small>
            <select name="assessment_year" id="assessment_year" class="form-control">
               <?php foreach ($fin_year as $row) { ?>
                  <option value="<?= $row['id'] ?>" <?php if (isset($punch_detail->assessment_year) && $row['id'] == $punch_detail->assessment_year) {
                       echo "selected";
                    } ?>><?= $row['label'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="bank_name">Bank Name:</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control"
               value="<?= (isset($punch_detail->bank_name)) ? $punch_detail->bank_name : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="bsr_code">BSR Code:</label>
            <input type="text" name="bsr_code" id="bsr_code" class="form-control"
               value="<?= (isset($punch_detail->bsr_code)) ? $punch_detail->bsr_code : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="challan_no">Challan No:</label>
            <input required type="text" name="challan_no" id="challan_no" class="form-control"
               value="<?= (isset($punch_detail->challan_no)) ? $punch_detail->challan_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="challan_date">Challan Date:</label>
            <input required type="text" name="challan_date" id="challan_date" class="form-control datepicker"
               value="<?= (isset($punch_detail->challan_date)) ? date('Y-m-d', strtotime($punch_detail->challan_date)) : '' ?>">
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-3">
            <label for="bank_reference_no">Bank Reference No:</label>
            <input type="text" name="bank_reference_no" id="bank_reference_no" class="form-control"
               value="<?= (isset($punch_detail->bank_reference_no)) ? $punch_detail->bank_reference_no : '' ?>">
         </div>
         <div class="form-group col-md-3" style="float: right;">
            <label for="amount">Amount:</label>
            <input type="text" name="amount" id="amount" class="form-control final_amount_column"
               value="<?= (isset($punch_detail->amount)) ? $punch_detail->amount : '' ?>">
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
      format: "Y-m-d",
   });
   $("#company").select2();
   $("#nature_of_payment").select2();
   $("#assessment_year").select2();

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
      $cleanedCompany = cleanSearchValue(
         isset($temp_punch_detail->company) && !is_null($temp_punch_detail->company) ? $temp_punch_detail->company : ""
      );

      ?>
      loadDropdownOptions(
         'company',
         '<?= base_url("extract/ExtractorController/get_company_options") ?>',
         <?= json_encode($cleanedCompany) ?>,
         '<?= isset($punch_detail->company_name) ? $punch_detail->company_name : "" ?>'
      );
   });
</script>