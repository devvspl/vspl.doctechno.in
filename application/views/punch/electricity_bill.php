<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Miscellaneous_ctrl/Save_Electricity" id="bankstatementform" name="bankstatementform" method="post" accept-charset="utf-8">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-7">
               <label for="">Location :</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->location; ?>
               </small>
               <select name="Location" id="Location" class="form-control">
                  <option value="">Select Location</option>
                  <?php foreach ($locationlist as $key => $value) { ?>
                  <option value="<?= $value['location_name'] ?>" <?php if (isset($punch_detail->Loc_Name)) {
                     if ($value['location_name'] == $punch_detail->Loc_Name) {
                     	echo "selected";
                     }
                     }  ?>><?= $value['location_name'] ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="form-group col-md-3">
               <label for="">Payment Date :</label>
               <input type="text" name="PaymentDate" id="PaymentDate" class="form-control datepicker" value="<?= (isset($punch_detail->PremiumDate)) ? date('Y-m-d', strtotime($punch_detail->PremiumDate)) : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Biller Name:</label>
               <input type="text" name="Biller_Name" id="Biller_Name" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Business Partner No(BP No):</label>
               <input type="text" name="BP_No" id="BP_No" class="form-control" value="<?= (isset($punch_detail->ReferenceNo)) ? $punch_detail->ReferenceNo : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Bill Period:</label>
               <input type="text" name="Period" id="Period" class="form-control" value="<?= (isset($punch_detail->Period)) ? $punch_detail->Period : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Meter Number:</label>
               <input type="text" name="Meter_No" id="Meter_No" class="form-control" value="<?= (isset($punch_detail->MeterNumber)) ? $punch_detail->MeterNumber : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Bill Date:</label>
               <input type="text" name="Bill_Date" id="Bill_Date" class="form-control datepicker" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Bill No:</label>
               <input type="text" name="Bill_No" id="Bill_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Previous Meter Reading:</label>
               <input type="text" name="Previous_Reading" id="Previous_Reading" class="form-control" value="<?= (isset($punch_detail->PreviousReading)) ? $punch_detail->PreviousReading : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Current Meter Reading:</label>
               <input type="text" name="Current_Reading" id="Current_Reading" class="form-control" value="<?= (isset($punch_detail->CurrentReading)) ? $punch_detail->CurrentReading : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 form-group">
               <label for="">Unit Consumed:</label>
               <input type="text" name="Unit_Consumed" id="Unit_Consumed" class="form-control" value="<?= (isset($punch_detail->UnitsConsumed)) ? $punch_detail->UnitsConsumed : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Last Date of Payment:</label>
               <input type="text" name="Last_Date" id="Last_Date" class="form-control datepicker" value="<?= (isset($punch_detail->LastDateOfPayment)) ? date('Y-m-d', strtotime($punch_detail->LastDateOfPayment)) : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Payment Mode:</label>
               <small class="text-danger">
               <?php echo $temp_punch_detail->payment_mode; ?>
               </small>
               <select name="Payment_Mode" id="Payment_Mode" class="form-control">
                  <option value="">Select</option>
                  <?php
                     $payment_mode = array('Cash' => 'Cash', 'Cheque' => 'Cheque', 'RTGS' => 'RTGS', 'NEFT' => 'NEFT', 'UPI' => 'UPI', 'Net Banking' => 'Net Banking');
                     foreach ($payment_mode as $key => $value) {
                     ?>
                  <option value="<?= $value ?>" <?php if (isset($punch_detail->NatureOfPayment) && $punch_detail->NatureOfPayment == $value) {
                     echo "selected";
                     } ?>><?= $value ?></option>
                  <?php } ?>
               </select>
            </div>
            <div class="col-md-3 form-group">
               <label for="">Bill Amount:</label>
               <input type="text" name="Bill_Amount" id="Bill_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>">
            </div>
            <div class="col-md-3 form-group">
               <label for="">Payment Amount:</label>
               <input type="text" name="Payment_Amount" id="Payment_Amount" class="form-control" value="<?= (isset($punch_detail->Payment_Amount)) ? $punch_detail->Payment_Amount : ''  ?>">
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
   </form>
</div>
<script>
   $(".datepicker").datetimepicker({
   	timepicker: false,
   	format: 'Y-m-d'
   });
</script>