<?php
function formatNumber($num) {
   $floatNum = floatval($num);
   return $floatNum == intval($floatNum) ? intval($floatNum) : number_format($floatNum, 2);
}
?>
<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url('save_punch_details'); ?>" id="invoice_form" name="invoice_form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row" style="margin-bottom: 2px;">
         <div class="form-group col-md-3">
            <label for="">Invoice No: <span class="text-danger">*</span></label>
            <input type="text" name="Bill_No" id="Bill_No" required class="form-control form-control-sm"
               value="<?= (isset($punch_detail->invoice_no)) ? $punch_detail->invoice_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Invoice Date: <span class="text-danger">*</span></label>
            <input type="date" name="Bill_Date" id="Bill_Date" required class="form-control form-control-sm"
               value="<?= (isset($punch_detail->invoice_date)) ? date('Y-m-d', strtotime($punch_detail->invoice_date)) : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Purchase Order No.:</label>
            <input type="text" name="Buyer_Order" id="Buyer_Order" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->buyers_order_no)) ? $punch_detail->buyers_order_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Purchase Order Date:</label>
            <input type="text" name="Buyer_Order_Date" id="Buyer_Order_Date"
               class="form-control form-control-sm datepicker"
               value="<?= formatSafeDate($punch_detail->buyers_order_date ?? '') ?>">
         </div>
      </div>
      <div class="row" style="margin-bottom: 2px;">
         <div class="form-group col-md-6">
            <label for="">Buyer: <span class="text-danger">*</span></label>
            <small class="text-danger">
               <?php
               echo (isset($temp_punch_detail->buyer) && !is_null($temp_punch_detail->buyer) && $temp_punch_detail->buyer !== '')
                  ? htmlspecialchars($temp_punch_detail->buyer)
                  : '';
               ?>
            </small>
            <select name="From" id="From" class="form-control form-control-sm" required>
               <option value="">Loading...</option>
            </select>
         </div>
         <div class="form-group col-md-6">
            <label for="" style="display: flex; justify-content: space-between;">
               <div>
                  <span>Vendor: <span class="text-danger">*</span></span>
                  <small class="text-danger">
                     <?php
                     echo (isset($temp_punch_detail->vendor) && !is_null($temp_punch_detail->vendor) && $temp_punch_detail->vendor !== '')
                        ? htmlspecialchars($temp_punch_detail->vendor)
                        : '';
                     ?>
                  </small>
               </div>
            </label>
            <select name="To" id="To" class="form-control form-control-sm" required>
               <option value="">Loading...</option>
            </select>
         </div>
      </div>
      <div class="row" style="margin-bottom: 2px;">
         <div class="form-group col-md-6">
            <label for="">Address : <span id="buyerAddressView"></span></label>
            <input type="hidden" name="Buyer_Address" id="Buyer_Address" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->buyer_address)) ? $punch_detail->buyer_address : '' ?>" readonly>
         </div>
         <div class="col-md-6">
            <label for="">Address : <span id="vendorAddressView"></span></label>
            <input type="hidden" name="Vendor_Address" id="Vendor_Address" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->vendor_address)) ? $punch_detail->vendor_address : '' ?>" readonly>
         </div>
      </div>
      <div class="row" style="margin-bottom: 10px;">
         <div class="form-group col-md-2">
            <label style="width:100%;">Dispatch Details: </label>
         </div>

         <div class="form-group col-md-2">
            <input type="text" name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm"
               placeholder="Dispatch Through"
               value="<?= (isset($punch_detail->dispatch_through)) ? $punch_detail->dispatch_through : '' ?>">
         </div>

         <div class="form-group col-md-2">
            <input type="text" name="Delivery_Note_Date" id="Delivery_Note_Date"
               class="form-control form-control-sm datepicker" placeholder="Delivery Note Date"
               value="<?= formatSafeDate($punch_detail->delivery_note_date ?? '') ?>">
         </div>

         <div class="form-group col-md-2">
            <input type="text" name="LR_Number" id="LR_Number" class="form-control form-control-sm"
               placeholder="LR Number" value="<?= (isset($punch_detail->lr_number)) ? $punch_detail->lr_number : '' ?>">
         </div>

         <div class="form-group col-md-2">
            <input type="text" name="LR_Date" id="LR_Date" class="form-control form-control-sm datepicker"
               placeholder="LR Date" value="<?= formatSafeDate($punch_detail->lr_date ?? '') ?>">
         </div>
      </div>
      <div class="row">
         <div class="">
            <table class="table table-sm">
               <thead style="text-align: center;">
                  <tr>
                     <th style="width: 2%;">#</th>
                     <th style="width: 8%;">Particular</th>
                     <th style="width: 10%;">HSN</th>
                     <th style="width: 5%;">Qty</th>
                     <th style="width: 10%;">Unit</th>
                     <th style="width: 8%;">MRP</th>
                     <th style="width: 5%;">Dis. MRP</th>
                     <th style="width: 8%;">Price</th>
                     <th style="width: 13%;">Amt</th>
                     <th style="width: 4%;">GST <br> %</th>
                     <th style="width: 4%;">SGST %</th>
                     <th style="width: 4%;">IGST %</th>
                     <th style="width: 4%;">Cess %</th>
                     <th style="width: 13%;">Total Amt</th>
                     <th style="width: 2%;"></th>
                  </tr>
               </thead>
               <tbody id="multi_record">
                  <tr>
                     <td>1</td>
                     <td>
                        <select name="Particular[]" id="Particular1" required
                           class="form-control form-select form-select-sm particular force-width">
                           <option value="">Select</option>
                           <?php
                           foreach ($item_list as $key => $value) {
                              ?>
                              <option value="<?= $value['item_name'] ?>"><?= $value['item_name'] ?></option>
                           <?php } ?>
                        </select>
                     </td>
                     <td>
                        <input type="text" name="HSN[]" id="HSN1" class="form-control form-control-sm">
                     </td>
                     <td>
                        <input type="text" name="Qty[]" id="Qty1" class="form-control form-control-sm text-center"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <select name="Unit[]" id="Unit1" class="form-control form-control-sm">
                           <option value="">Select</option>
                           <?php
                           $unit_list = $this->db->get_where('master_unit', array('status' => 'A', 'is_deleted' => 'N'))->result_array();
                           foreach ($unit_list as $key => $value) {
                              ?>
                              <option value="<?= $value['unit_id'] ?>"><?= $value['unit_name'] ?></option>
                           <?php } ?>
                        </select>
                     </td>
                     <td>
                        <input type="text" name="MRP[]" id="MRP1" class="form-control form-control-sm text-right"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <input type="text" name="Discount[]" id="Discount1" class="form-control form-control-sm text-right"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <input type="text" name="Price[]" id="Price1" class="form-control form-control-sm text-right" readonly>
                     </td>
                     <td>
                        <input type="text" name="Amount[]" id="Amount1" class="form-control form-control-sm text-right Amount"
                           readonly>
                     </td>
                     <td>
                        <input type="text" name="GST[]" id="GST1" class="form-control form-control-sm text-center"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <input type="text" name="SGST[]" id="SGST1" class="form-control form-control-sm text-center" readonly>
                     </td>
                     <td>
                        <input type="text" name="IGST[]" id="IGST1" class="form-control form-control-sm text-center"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <input type="text" name="Cess[]" id="Cess1" class="form-control form-control-sm text-center"
                           onkeypress="return isNumberKey(event)">
                     </td>
                     <td>
                        <input type="text" name="TAmount[]" id="TAmount1" class="form-control form-control-sm TAmount text-right"
                           readonly>
                     </td>
                     <td>
                        <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                           style="margin-top: 0;padding: 0 4px;"><i class="fa fa-plus"></i></button>
                     </td>
                  </tr>
               </tbody>
               <tr>
                  <td colspan="7"></td>
                  <td style="text-align: right;"><b>TCS % :</b></td>
                  <td colspan="1">
                     <input type="text" name="TCS" id="TCS" class="form-control form-control-sm"
                        onkeypress="return isNumberKey(event)" onchange="cal_tax()"
                        value="<?= (isset($punch_detail->tcs_percent)) ? formatNumber($punch_detail->tcs_percent) : '' ?>">
                  </td>
                  <td colspan="2" style="text-align: right;"><b>Round Off :</b></td>
                  <td colspan="1">
                     <div style="display: flex; align-items: center;">
                        <div class="form-check form-check-inline" style="margin-right:10px; cursor:pointer;">
                           <input type="radio" name="plus_minus" id="plus" value="plus" style="display:none;"
                              <?php if (isset($punch_detail->total_discount)) {
                                 if ($punch_detail->grand_total > $punch_detail->total) {
                                    echo "checked";
                                 }
                              } ?>>
                           <label for="plus" class="form-check-label">
                              <i class="fa fa-plus-circle fa-2x" style="color: 
                              <?php 
                                 if (isset($punch_detail->total_discount) && $punch_detail->grand_total > $punch_detail->total) {
                                    echo '#007bff'; 
                                 } else {
                                    echo '#ccc'; 
                                 } 
                              ?>"></i>
                           </label>
                        </div>
                        <div class="form-check form-check-inline" style="cursor:pointer;">
                           <input type="radio" name="plus_minus" id="minus" value="minus" style="display:none;"
                              <?php if (isset($punch_detail->total_discount)) {
                                 if ($punch_detail->grand_total < $punch_detail->total) {
                                    echo "checked";
                                 }
                              } else {
                                 echo "checked";
                              } ?>>
                           <label for="minus" class="form-check-label">
                              <i class="fa fa-minus-circle fa-2x" style="color: 
                              <?php 
                                 if (isset($punch_detail->total_discount)) {
                                    if ($punch_detail->grand_total < $punch_detail->total) {
                                       echo '#dc3545'; 
                                    } else {
                                       echo '#ccc'; 
                                    }
                                 } else {
                                    echo '#dc3545'; 

                                 } 
                              ?>"></i>
                           </label>
                        </div>
                     </div>
                  </td>
                  <td colspan="1" style="text-align: right;"><b>Grand_Total</b></td>
                  <td colspan="1">
                     <input type="text" name="Grand_Total" id="Grand_Total" class="form-control form-control-sm text-right"
                        readonly value="<?= (isset($punch_detail->grand_total)) ? formatNumber($punch_detail->grand_total) : '' ?>">
                  </td>
               </tr>
            </table>
            <?php include_once (APPPATH . 'views/punch/add_feild.php') ?>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="2"
               class="form-control form-control-sm"><?= (isset($punch_detail->remark_comment)) ? $punch_detail->remark_comment : '' ?></textarea>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
   data-backdrop="static">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Item/Particular</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-8">
                  <div class="form-group">
                     <label for="">Item/Particular Name: </label><span class="text-danger">*</span>
                     <input type="text" name="item_name" id="item_name" class="form-control">
                     <div class="form-group" id="name_error"></div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="">Item Code:</label>
                     <input type="text" name="item_code" id="item_code" class="form-control">
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="save_btn">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Vendor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="formMessage" class="alert" style="display:none;"></div>
            <form id="addVendorForm">
               <div class="form-group">
                  <label for="firm_name">Vendor Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="firm_name" name="firm_name" required>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="firm_type">Vendor Type <span class="text-danger">*</span></label>
                     <select class="form-control" id="firm_type" name="firm_type" required>
                        <option value="Vendor" selected>Vendor</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="firm_code">Vendor Code</label>
                     <input type="text" class="form-control" id="firm_code" name="firm_code">
                  </div>
               </div>
               <div class="form-group">
                  <label for="gst">GST</label>
                  <input type="text" class="form-control" id="gst" name="gst">
               </div>
               <div class="form-group">
                  <label for="address">Address</label>
                  <textarea class="form-control" id="address" name="address"></textarea>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="country">Country <span class="text-danger">*</span></label>
                     <select class="form-control" id="country_id" name="country_id" required>
                        <option value="">Select</option>
                        <option value="India">India</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="state">State <span class="text-danger">*</span></label>
                     <select class="form-control" id="state_id" name="state_id" required>
                        <option value="">Select</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="city">City</label>
                     <input type="text" class="form-control" id="city" name="city">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="pin_code">Pin Code</label>
                     <input type="text" class="form-control" id="pin_code" name="pin_code">
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary mt-2">Add Vendor</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
   function calculate(num) {
      
      // Retrieve inputs with validation
      let qty = parseFloat($("#Qty" + num).val()) || 0;
      let mrp = parseFloat($("#MRP" + num).val()) || 0;
      let discount = parseFloat($("#Discount" + num).val()) || 0;

      
      // Debug inputs

      
      // Calculate discounted price (discount as fixed amount)
      let price = mrp;
      if (mrp <= 0) {
         console.warn(`Invalid MRP for num ${num}: ${mrp}`);
      } else if (discount < 0) {
         console.warn(`Invalid discount for num ${num}: ${discount}`);
      } else if (discount > mrp) {
         console.warn(`Discount exceeds MRP for num ${num}: Discount=${discount}, MRP=${mrp}`);
         price = 0;
      } else {
         price = mrp - discount;
      }

      
      // Log the discounted price

      
      // Calculate amount based on discounted price
      let amount = qty * price;

      
      // Apply taxes (GST, IGST, Cess) based on discounted amount
      let gst = parseFloat($("#GST" + num).val()) || 0;
      let igst = parseFloat($("#IGST" + num).val()) || 0;
      let cess = parseFloat($("#Cess" + num).val()) || 0;

      let totalGst = (amount * gst) / 100;
      let totalIgst = (amount * igst) / 100;
      let totalCess = (amount * cess) / 100;
      let totalAmount = amount + totalGst + totalIgst + totalCess;

      let sgst = igst == 0 ? gst / 2 : 0;
      let totalSgst = (amount * sgst) / 100;



         if (igst == 0) {
            totalAmount += totalSgst;
            $("#SGST" + num).val(formatNumber(sgst));
         } else {
            $("#SGST" + num).val("0");
         }
      
         // Update UI with formatted values
      $("#Price" + num).val(formatNumber(price.toFixed(2)));
      $("#Amount" + num).val(formatNumber(amount.toFixed(2)));
      $("#TAmount" + num).val(formatNumber(totalAmount.toFixed(2)));

      
      // Calculate totals
      let subTotal = 0;
      let grandTotal = 0;

      $("[id^='Amount']").each(function () {
         let val = parseFloat($(this).val()) || 0;
         subTotal += val;
      });

      $("[id^='TAmount']").each(function () {
         let val = parseFloat($(this).val()) || 0;
         grandTotal += val;
      });

      $("#Sub_Total").val(formatNumber(subTotal.toFixed(2)));
      $("#Total").val(formatNumber(grandTotal.toFixed(2)));

      let tcsRate = parseFloat($("#TCS").val()) || 0;
      let tcsAmount = (grandTotal * tcsRate) / 100;
      let finalGrandTotal = grandTotal + tcsAmount;

      $("#Grand_Total").val(formatNumber(finalGrandTotal.toFixed(2)));
   }

   function isNumberKey(evt) {

      const charCode = evt.which || evt.keyCode;
      return !(charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57));
   }

   function formatNumber(value) {
      if (!isNaN(value)) {
         return parseFloat(value) % 1 === 0 ? parseInt(value) : value;
      }
      return value;
   }

   $(document).ready(function () {

      function initUI() {
         $("#From, #To").select2();
         $(".datepicker").datetimepicker({
            timepicker: false,
            format: "Y-m-d"
         });
         $(".particular").select2({
            allowClear: true,
            escapeMarkup: (m) => m,
            placeholder: "Select Item/Particular",
            language: {
               noResults: () => "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>",
            },
         });
      }

      function updateBuyerAddress() {
         const address = $(this).find(":selected").data("address");
         $("#Buyer_Address").val(address);
         $("#buyerAddressView").text(address || "No address available");
         $("#buyerAddressView").toggle(!!address);
      }

      function updateVendorAddress() {
         const address = $(this).find(":selected").data("address");
         $("#Vendor_Address").val(address);
         $("#vendorAddressView").text(address || "No address available");
         $("#vendorAddressView").toggle(!!address);
      }

      function toggleLoader(show, tableId) {
         const loader = $("#loader");
         const loaderText = $("#loader-text");
         const table = $("#" + tableId);
         loader.css("marginTop", show ? "230px" : "0");
         loader.toggle(show);
         loaderText.toggle(show);
         table.css("visibility", show ? "hidden" : "visible");
      }

      function loadUnitList() {
         return new Promise((resolve, reject) => {
            $.post(
               "<?= base_url() ?>master/UnitController/get_unit_list",
               (response) => {
                  if (response.status === 200) {
                     unitList = `<option value="">Select Unit</option>` + response.unit_list.map((v) => `<option value="${v.unit_id}">${v.unit_name}</option>`).join("");
                     resolve();
                  } else {
                     reject("Failed to load unit list");
                  }
               },
               "json"
            ).fail(() => reject("Error fetching unit list"));
         });
      }

      function loadItemList() {
         return new Promise((resolve, reject) => {
            $.post(
               "<?= base_url() ?>master/ItemController/get_item_list",
               (response) => {
                  if (response.status === 200) {
                     itemList = `<option value="">Select Item/Particular</option>` + response.item_list.map((v) => `<option value="${v.item_name}">${v.item_name}</option>`).join("");
                     resolve();
                  } else {
                     reject("Failed to load item list");
                  }
               },
               "json"
            ).fail(() => reject("Error fetching item list"));
         });
      }

      function getMultiRecord() {
         const scanId = $("#scan_id").val();
         const docTypeId = $("#DocTypeId").val();
         toggleLoader(true, "contentBody");

         $.post(
            "<?= base_url() ?>Punch/getPunchItems",
            {
               scan_id: scanId,
               type_id: docTypeId
            },
            (response) => {
               if (response.status === 200) {
                  count = response.data.length;
                  if (count === 0) {
                     addItemRow();
                  } else {
                     response.data.forEach((item, index) => {
                        if (index > 0) addItemRow();
                        populateRow(index + 1, item);
                     });
                  }
               } else {
                  alert("No items found or invalid response.");
               }
            },
            "json"
         )
            .always(() => toggleLoader(false, "contentBody"))
            .fail(() => alert("Error fetching data."));
      }

      function populateRow(index, item) {
         const $particular = $(`#Particular${index}`);
         $particular.val(item.particular).trigger("change");

         $(`#HSN${index}`).val(item.hsn);
         $(`#Qty${index}`).val(formatNumber(item.qty));
         $(`#Unit${index}`).val(item.unit);
         $(`#MRP${index}`).val(formatNumber(item.mrp));
         $(`#Discount${index}`).val(formatNumber(item.discount));
         $(`#GST${index}`).val(formatNumber(item.gst));
         $(`#SGST${index}`).val(formatNumber(item.sgst));
         $(`#IGST${index}`).val(formatNumber(item.igst));
         $(`#Cess${index}`).val(formatNumber(item.cess));
         $(`#Price${index}`).val(formatNumber(item.price));
         $(`#Amount${index}`).val(formatNumber(item.amount));
         $(`#TAmount${index}`).val(formatNumber(item.total_amount));

         $particular.select2({
            allowClear: true,
            escapeMarkup: (m) => m,
            placeholder: "Select Item/Particular",
            language: {
               noResults: () => "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add Item</button>",
            },
         });
      }

      function addItemRow() {
         let currentRows = $("#multi_record tr").length;
         let serialNo = currentRows + 1;

         let html = `
                <tr>
                    <td>${serialNo}</td>
                    <td><select name="Particular[]" id="Particular${serialNo}" class="form-control form-control-sm particular">${itemList}</select></td>
                    <td><input type="text" name="HSN[]" id="HSN${serialNo}" class="form-control form-control-sm"></td>
                    <td><input type="text" name="Qty[]" id="Qty${serialNo}" class="form-control form-control-sm text-center" onkeypress="return isNumberKey(event)"></td>
                    <td><select name="Unit[]" id="Unit${serialNo}" class="form-control form-control-sm">${unitList}</select></td>
                    <td><input type="text" name="MRP[]" id="MRP${serialNo}" class="form-control form-control-sm text-right" onkeypress="return isNumberKey(event)"></td>
                    <td><input type="text" name="Discount[]" id="Discount${serialNo}" class="form-control form-control-sm text-right" onkeypress="return isNumberKey(event)"></td>
                    <td><input type="text" name="Price[]" id="Price${serialNo}" class="form-control form-control-sm text-right" readonly></td>
                    <td><input type="text" name="Amount[]" id="Amount${serialNo}" class="form-control form-control-sm text-right Amount" readonly></td>
                    <td><input type="text" name="GST[]" id="GST${serialNo}" class="form-control form-control-sm text-center" onkeypress="return isNumberKey(event)"></td>
                    <td><input type="text" name="SGST[]" id="SGST${serialNo}" class="form-control form-control-sm text-center" readonly></td>
                    <td><input type="text" name="IGST[]" id="IGST${serialNo}" class="form-control form-control-sm text-center" onkeypress="return isNumberKey(event)"></td>
                    <td><input type="text" name="Cess[]" id="Cess${serialNo}" class="form-control form-control-sm text-center" onkeypress="return isNumberKey(event)"></td>
                    <td><input type="text" name="TAmount[]" id="TAmount${serialNo}" class="form-control form-control-sm TAmount text-right" readonly></td>
                    <td><button type="button" name="remove" style="margin-top: 0;padding: 0 4px;" class="btn btn-danger btn-xs removeRow"><i class="fa fa-minus"></i></button></td>
                </tr>`;

         $("#multi_record").append(html);

         $("#Qty" + serialNo + ", #MRP" + serialNo + ", #Discount" + serialNo + ", #GST" + serialNo + ", #IGST" + serialNo + ", #Cess" + serialNo)
            .on("change", function () {
               calculate(serialNo); 
               // Rely on jQuery event handler
            });

         initUI();
      }

      function removeItemRow() {
         $(this).closest("tr").remove();
         calculate(count); 
         // Recalculate totals
         updateSerialNumbers();
      }

      function updateSerialNumbers() {
         $("#multi_record tr").each(function (index) {
            $(this).find("td:first").text(index + 1);
         });
      }

      function calculatePlusMinus() {
         const discount = parseFloat($("#Total_Discount").val()) || 0;
         let total = parseFloat($("#Total").val()) || 0;
         if ($(this).attr("id") === "plus") {
            total += discount;
         } else {
            total -= discount;
         }
         $("#Grand_Total").val(total.toFixed(2));
      }

      window.cal_tax = function () {
            let tcs = parseFloat($("#TCS").val()) || 0;
            let subTotal = parseFloat($("#Sub_Total").val()) || 0;
            let tcsAmount = (tcs / 100) * subTotal;
            let total = subTotal + tcsAmount;

            
            // Format numbers to remove .00 when decimal part is zero
            const formatNumber = (num) => {
               return num % 1 === 0 ? num.toFixed(0) : num.toFixed(2);
            };

            const totalFixed = total.toFixed(2);
            const decimal = (total % 1).toFixed(2).split(".")[1] || "00";
            
            $("#Total").val(formatNumber(total));
            $("#Total_Discount").val(formatNumber(parseFloat("0." + decimal)));
            $("#Grand_Total").val(formatNumber(total - parseFloat("0." + decimal)));
         };

      function getCountry() {
         $.ajax({
            url: '<?= base_url("get-country") ?>',
            type: "GET",
            dataType: "json",
            success: function (response) {
               let options = '<option value="">-- Select Country --</option>';
               $.each(response, function (index, country) {
                  options += `<option value="${country.api_id}">${country.country_name} (${country.country_code})</option>`;
               });
               $("#country_id").html(options);
            },
         });
      }

      function getState(countryId) {
         $.ajax({
            url: '<?= base_url("get-state") ?>',
            type: "POST",
            data: {
               country_id: countryId
            },
            dataType: "json",
            success: function (response) {
               let options = '<option value="">-- Select State --</option>';
               $.each(response, function (index, state) {
                  options += `<option value="${state.api_id}">${state.state_name} (${state.short_code})</option>`;
               });
               $("#state_id").html(options);
            },
         });
      }
      let unitList = "";
      let itemList = "";
      let count = 1;
      initUI();

      $("#Qty1, #MRP1, #Discount1, #GST1, #IGST1, #Cess1").on("change", function () {
         calculate(1);
      })

      Promise.all([loadUnitList(), loadItemList()])
         .then(() => {
            getMultiRecord();
         })
         .catch((error) => {
            console.error("Error loading initial data:", error);
            alert("Failed to load initial data. Please try again.");
         });

      $(document).on("change", "#From", updateBuyerAddress);
      $(document).on("change", "#To", updateVendorAddress);
      $(document).on("click", "#add", addItemRow);
      $(document).on("click", ".removeRow", removeItemRow);
      $(document).on("change", ".plus_minus", calculatePlusMinus);

     <?php
    $cleanedBuyer = cleanSearchValue(
        isset($temp_punch_detail->buyer) && !is_null($temp_punch_detail->buyer) ? $temp_punch_detail->buyer : ""
    );
    $cleanedVendor = cleanSearchValue(
        isset($temp_punch_detail->vendor) && !is_null($temp_punch_detail->vendor) ? $temp_punch_detail->vendor : ""
    );
    $selectedBuyer = isset($punch_detail->buyer) ? $punch_detail->buyer : "";
    $selectedVendor = isset($punch_detail->vendor) ? $punch_detail->vendor : "";
    ?>

    loadDropdownOptions(
        'From',
        <?= json_encode(base_url("extract/ExtractorController/get_company_options")) ?>,
        <?= json_encode($cleanedBuyer) ?>,
        <?= json_encode($selectedBuyer) ?>
    );

    loadDropdownOptions(
        'To',
        <?= json_encode(base_url("extract/ExtractorController/get_vendor_options")) ?>,
        <?= json_encode($cleanedVendor) ?>,
        <?= json_encode($selectedVendor) ?>
    );

      $("#To").on("change", function () {
         var selectedVendor = $(this).val();
         if (selectedVendor === "") {
            $("#newVendorOption").show();
         } else {
            $("#newVendorOption").hide();
         }
      });

      $("#addVendorBtn").click(function () {
         getCountry();
      });

      $("#country_id").on("change", function () {
         let countryId = $(this).val();
         if (countryId) {
            getState(countryId);
         } else {
            $("#state_id").html('<option value="">-- Select State --</option>');
         }
      });

      $("#addVendorForm").on("submit", function (e) {
         e.preventDefault();

         $("#formMessage").hide().removeClass("alert-success alert-danger").html("");

         $.ajax({
            url: '<?= base_url("new-vendor-request") ?>',
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
               if (response.status === true) {
                  $("#formMessage").addClass("alert alert-success").html(response.message).slideDown();

                  $("#addVendorForm")[0].reset();
                  setTimeout(function () {
                     $("#addVendorModal").modal("hide");
                  }, 1000);
               } else {
                  $("#formMessage").addClass("alert alert-danger").html(response.message).slideDown();
               }
            },
            error: function () {
               $("#formMessage").addClass("alert alert-danger").html("Something went wrong! Please try again.").slideDown();
            },
         });
      });

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
        $('input[name="plus_minus"]').change(function(){
         if($(this).val() === 'plus'){
            $('#plus + label i').css('color', '#007bff'); 
            $('#minus + label i').css('color', '#ccc');   
         } else {
            $('#minus + label i').css('color', '#dc3545'); 
            $('#plus + label i').css('color', '#ccc');     
         }
      });
   });
</script>

