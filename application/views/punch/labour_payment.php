<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>form/Labour_ctrl/save" id="labour_form" name="labour_form" method="post" accept-charset="utf-8">
      <div style="display: flex; flex-direction: column; align-items: center;">
         <div class="loader" id="loader" style="display: none;"></div>
         <span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #1b98ae;">Please Wait...</span>
      </div>
      <div class="col-md-12" id="contnetBody">
         <input type="hidden" name="Scan_Id" id="Scan_Id" value="<?= $Scan_Id ?>">
         <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $DocType_Id ?>">
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Voucher No:</label>
               <input type="text" name="Voucher_No" id="Voucher_No" class="form-control" value="<?= (isset($punch_detail->File_No)) ? $punch_detail->File_No : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Payment Date:</label>
               <input type="date" name="Payment_Date" id="Payment_Date" class="form-control" value="<?= (isset($punch_detail->BillDate)) ? date('Y-m-d', strtotime($punch_detail->BillDate)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Payee:</label>
               <input type="text" name="Payee" id="Payee" class="form-control" value="<?= (isset($punch_detail->Related_Person)) ? $punch_detail->Related_Person : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="pwd">Location :</label>
               <input type="text" name="Location" id="Location" class="form-control" value="<?= (isset($punch_detail->Loc_Name)) ? $punch_detail->Loc_Name : ''  ?>">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3">
               <label for="">Particular :</label>
               <input type="text" name="Particular" id="Particular" class="form-control" value="<?= (isset($punch_detail->FileName)) ? $punch_detail->FileName : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">Total Amount :</label>
               <input type="text" name="Total_Amount" id="Total_Amount" class="form-control" value="<?= (isset($punch_detail->Total_Amount)) ? $punch_detail->Total_Amount : ''  ?>" readonly>
            </div>
            <div class="form-group col-md-3">
               <label for="">From Date :</label>
               <input type="date" name="From_Date" id="From_Date" class="form-control" value="<?= (isset($punch_detail->FromDateTime)) ? date('Y-m-d', strtotime($punch_detail->FromDateTime)) : ''  ?>">
            </div>
            <div class="form-group col-md-3">
               <label for="">To Date :</label>
               <input type="date" name="To_Date" id="To_Date" class="form-control" value="<?= (isset($punch_detail->ToDateTime)) ? date('Y-m-d', strtotime($punch_detail->ToDateTime)) : ''  ?>">
            </div>
         </div>
         <div class="row" style="height: 200px; overflow:auto">
            <table class="table">
               <thead>
                  <th>Head</th>
                  <th>Amount</th>
                  <th></th>
               </thead>
               <tbody id="multi_record">
                  <tr>
                     <td>
                        <select class="form-control" id="Head1" name="Head[]">
                           <option value="">Select Head</option>
                           <?php
                              $ledger_list = $this->customlib->getLedgerList();
                              foreach ($ledger_list as $key => $value) { ?>
                           <option value="<?= $value['ledger_name'] ?>" <?php if (isset($punch_detail->Ledger) && $value['ledger_name'] == $punch_detail->Ledger) {
                              echo "selected";
                              } ?>><?= $value['ledger_name'] ?></option>
                           <?php } ?>
                        </select>
                     </td>
                     <td><input type="text" class="form-control" id="Amount1" name="Amount[]" onchange="calculate(1);"></td>
                     <td><button type="button" name="add" id="add" class="btn btn-primary btn-xs" style="margin-top: 5px;"><i class="fa fa-plus"></i></button></td>
                  </tr>
               </tbody>
               <tr>
                  <td style="text-align: right;"><b>Sub Total:</b></td>
                  <td><input type="text" class="form-control form-control-sm" id="Sub_Total" name="Sub_Total" readonly value="<?= (isset($punch_detail->SubTotal)) ? $punch_detail->SubTotal : ''  ?>"></td>
               </tr>
            </table>
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
<script>
   $('#Head1').select2();
   var Count = 1;
   getMultiRecord();
   
   function getMultiRecord() {
      var Scan_Id = $('#Scan_Id').val();
      $.ajax({
          url: '<?= base_url() ?>form/Labour_ctrl/getLabourRecord',
          type: 'POST',
          data: {
              Scan_Id: Scan_Id
          },
          dataType: 'json',
          success: function(response) {
              
   
              if (response.status == 200) {
                  Count = (response.data).length;
                  for (var i = 1; i <= Count; i++) {
                      if (i >= 2) {
                          multi_record(i);
                      }
                      $("#Head" + i).val(response.data[i - 1].Head).trigger('change');
                      $("#Amount" + i).val(response.data[i - 1].Amount);
                  }
              }
          }
      });
   }
   $(document).on('click', '#add', function() {
      Count++;
      multi_record(Count);
   });
   
   $(document).on('click', '#remove', function() {
      if (confirm('Are you sure you want to delete this record?')) {
          $(this).closest('tr').remove();
          Count--;
          calculate(Count);
      }
   });
   
   function multi_record(num) {
   var html = '';
   html += '<tr>';
   html += '<td><select class="form-control" id="Head' + num + '" name="Head[]">';
   html += '<option value="">Select Head</option>';
   
   html += `<?php foreach ($ledger_list as $key => $value) { ?>
              <option value="<?= addslashes($value['ledger_name']) ?>"><?= addslashes($value['ledger_name']) ?></option>
          <?php } ?>`;
   html += '</select></td>';
   html += '<td><input type="text" class="form-control" id="Amount' + num + '" name="Amount[]" onchange="calculate(' + num + ')"></td>';
   html += '<td><button type="button" name="remove" id="remove" class="btn btn-danger btn-xs" style="margin-top: 5px;"><i class="fa fa-minus"></i></button></td>';
   html += '</tr>';
   $('#multi_record').append(html);
   $('#Head' + num).select2();
   }
   
   function calculate(num) {
   
      var Amount = $('#Amount' + num).val();
      var Sub_Total = 0;
      for (var i = 1; i <= Count; i++) {
          var Amount = $('#Amount' + i).val();
          if (Amount != '' && Amount != null) {
              Sub_Total += parseFloat(Amount);
          }
      }
      $('#Sub_Total').val(Sub_Total.toFixed(2));
      $('#Total_Amount').val(Sub_Total.toFixed(2));
   
   }
   
   function toggleLoader(show, tableId) {
   const loader = document.getElementById('loader');
   const loaderText = document.getElementById('loader-text');
   const table = document.getElementById(tableId);
   
   if (show) {
   
   loader.style.marginTop = '230px'; 
   
   loader.style.display = 'inline-block';
   loaderText.style.display = 'block';
   table.style.visibility = 'hidden';
   } else {
   
   loader.style.marginTop = '0'; 
   
   loader.style.display = 'none';
   loaderText.style.display = 'none';
   table.style.visibility = 'visible';
   }
   }
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