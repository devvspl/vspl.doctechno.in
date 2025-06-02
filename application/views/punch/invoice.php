<div id="invoice-details" class="tab-content active">
   <form action="<?= base_url(); ?>Punch/savePunchToDatabase" id="invoice_form" name="invoice_form" method="post"
      accept-charset="utf-8">
      <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id ?>">
      <input type="hidden" name="DocTypeId" id="DocTypeId" value="<?= $doc_type_id ?>">
      <div class="row" style="margin-bottom: 5px;">
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
            <input type="text" name="Buyer_Order" id="Buyer_Order"  class="form-control form-control-sm" value="<?= (isset($punch_detail->buyers_order_no)) ? $punch_detail->buyers_order_no : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Purchase Order Date:</label>
            <input type="text" name="Buyer_Order_Date" id="Buyer_Order_Date" class="form-control form-control-sm datepicker" 
               value="<?= formatSafeDate($punch_detail->buyers_order_date ?? '') ?>">
         </div>
      </div>
    <div class="row" style="margin-bottom: 5px;">
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
                  <div id="newVendorOption">
                     <a href="javascript:void(0);" class="btn-link" id="addVendorBtn" data-toggle="modal" data-target="#addVendorModal">
                        Add New Vendor
                     </a>
                  </div>
            </label>
            <select name="To" id="To" class="form-control form-control-sm" required>
                  <option value="">Loading...</option>
            </select>
         </div>
      </div>
      <div class="row" style="margin-bottom: 5px;">
         <div class="form-group col-md-6">
            <label for="">Buyer Address :</label>
            <input type="text" name="Buyer_Address" id="Buyer_Address" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->buyer_address)) ? $punch_detail->buyer_address : '' ?>"
               readonly>
         </div>
         <div class="col-md-6">
            <label for="">Vendor Address :</label>
            <input type="text" name="Vendor_Address" id="Vendor_Address" class="form-control form-control-sm"
               value="<?= (isset($punch_detail->vendor_address)) ? $punch_detail->vendor_address : '' ?>"
               readonly>
         </div>
      </div>
      <div class="row" style="margin-bottom: 5px;">
         <div class="form-group col-md-3">
            <label for="">Dispatch Through:</label>
            <input type="text" name="Dispatch_Trough" id="Dispatch_Trough" class="form-control form-control-sm" value="<?= (isset($punch_detail->dispatch_through)) ? $punch_detail->dispatch_through : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">Delivery Note Date:</label>
            <input type="text" name="Delivery_Note_Date" id="Delivery_Note_Date" class="form-control form-control-sm datepicker" value="<?= formatSafeDate($punch_detail->delivery_note_date ?? '') ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">LR Number:</label>
            <input type="text" name="LR_Number" id="LR_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->lr_number)) ? $punch_detail->lr_number : '' ?>">
         </div>
         <div class="form-group col-md-3">
            <label for="">LR Date:</label>
            <input type="text" name="LR_Date" id="LR_Date" class="form-control form-control-sm datepicker" value="<?= formatSafeDate($punch_detail->lr_date ?? '') ?>" >
         </div>
         <div class="form-group col-md-3">
            <label for="">Cartoon Number:</label>
            <input type="text" name="Cartoon_Number" id="Cartoon_Number" class="form-control form-control-sm" value="<?= (isset($punch_detail->cartoon_number)) ? $punch_detail->cartoon_number : '' ?>">
         </div>
      </div>
      <div class="row" style="height: 300px; overflow:auto">
         <div class="" style="overflow-x:scroll;">
            <table class="table" style="width:1600px;max-width:1600px;overflow-x:scroll;">
               <thead style="text-align: center;">
                  <th style="width: 10px;">#</th>
                  <th style="width: 420px;">Particular</th>
                  <th style="width: 100px;">HSN</th>
                  <th style="width: 100px;">Qty</th>
                  <th style="width: 150px;">Unit</th>
                  <th style="width: 150px;">MRP</th>
                  <th style="width: 100px;">Discount in MRP</th>
                  <th style="width: 150px;">Price</th>
                  <th style="width: 180px;">Amount</th>
                  <th style="width: 80px;">GST %</th>
                  <th style="width: 80px;">SGST %</th>
                  <th style="width: 80px;">IGST %</th>
                  <th style="width: 80px;">Cess %</th>
                  <th style="width: 120px;">Total Amount</th>
                  <th style="width: 30px;"></th>
               </thead>
               <tbody id="multi_record">
                  <tr>
                     <td>1</td>
                     <td>
                        <select name="Particular[]" id="Particular1" required
                           class="form-control form-select form-select-sm particular">
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
                        <input type="text" name="Qty[]" id="Qty1" class="form-control form-control-sm"
                           onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
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
                        <input type="text" name="MRP[]" id="MRP1" class="form-control form-control-sm"
                           onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                     </td>
                     <td>
                        <input type="text" name="Discount[]" id="Discount1"
                           class="form-control form-control-sm" onKeyPress="return isNumberKey(event)"
                           onchange="calculate(1)">
                     </td>
                     <td>
                        <input type="text" name="Price[]" id="Price1" class="form-control form-control-sm"
                           readonly>
                     </td>
                     <td>
                        <input type="text" name="Amount[]" id="Amount1"
                           class="form-control form-control-sm Amount" readonly>
                     </td>
                     <td>
                        <input type="text" name="GST[]" id="GST1" class="form-control form-control-sm"
                           onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                     </td>
                     <td>
                        <input type="text" name="SGST[]" id="SGST1" class="form-control form-control-sm"
                           readonly>
                     </td>
                     <td>
                        <input type="text" name="IGST[]" id="IGST1" class="form-control form-control-sm"
                           onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                     </td>
                     <td>
                        <input type="text" name="Cess[]" id="Cess1" class="form-control form-control-sm"
                           onKeyPress="return isNumberKey(event)" onchange="calculate(1)">
                     </td>
                     <td>
                        <input type="text" name="TAmount[]" id="TAmount1"
                           class="form-control form-control-sm TAmount" readonly>
                     </td>
                     <td>
                        <button type="button" name="add" id="add" class="btn btn-primary btn-xs"
                           style="margin-top: 2px;"><i class="fa fa-plus"></i></button>
                     </td>
                  </tr>
               </tbody>
               <tr>
                  <td colspan="6"></td>
                  <td><b>Sub Total (₹):</b></td>
                  <td colspan="2">
                     <input type="text" name="Sub_Total" id="Sub_Total"
                        class="form-control form-control-sm" readonly
                        value="<?= (isset($punch_detail->sub_total)) ? $punch_detail->sub_total : '' ?>">
                  </td>
               </tr>
               <tr id="tcs_tr">
                  <td colspan="7" style="text-align: right;"><b>TCS %:</b></td>
                  <td colspan="2">
                     <input type="text" name="TCS" id="TCS" class="form-control form-control-sm"
                        onKeyPress="return isNumberKey(event)" onchange="cal_tax()"
                        value="<?= (isset($punch_detail->tcs_percent)) ? $punch_detail->tcs_percent : '' ?>">
                  </td>
               </tr>
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Total (₹):</b></td>
                  <td colspan="2">
                     <input type="text" name="Total" id="Total" class="form-control form-control-sm"
                        readonly
                        value="<?= (isset($punch_detail->total)) ? $punch_detail->total : '' ?>">
                  </td>
               </tr>
               <tr>
                  <td colspan="7" style="text-align: right;"><b>Round Off (₹):</b></td>
                  <td colspan="5">
                     <input type="text" name="Total_Discount" id="Total_Discount"
                        class="form-control form-control-sm d-inline"
                        value="<?= (isset($punch_detail->total_discount)) ? $punch_detail->total_discount : '' ?>"
                        style="width:100px;">
                     <span><input type="radio" name="plus_minus" id="plus" class="plus_minus" <?php
                        if (isset($punch_detail->Total_Discount)) {
                           if ($punch_detail->grand_total > $punch_detail->total) {
                              echo "checked";
                           }
                        }
                        ?>>
                     <label for="plus">Plus</label>
                     </span>
                     <span><input type="radio" name="plus_minus" id="minus" class="plus_minus" <?php
                        if (isset($punch_detail->Total_Discount)) {
                           if ($punch_detail->grand_total < $punch_detail->total) {
                              echo "checked";
                           }
                        } else {
                           echo "checked";
                        }
                        ?>> <label for="minus">Minus</label></span>
                  </td>
               </tr>
               <td colspan="7" style="text-align: right;"><b>Grand Total (₹):</b></td>
               <td colspan="2">
                  <input type="text" name="Grand_Total" id="Grand_Total"
                     class="form-control form-control-sm" readonly
                     value="<?= (isset($punch_detail->grand_total)) ? $punch_detail->grand_total : '' ?>">
               </td>
               </tr>
            </table>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-md-12">
            <label for="">Remark / Comment:</label>
            <textarea name="Remark" id="Remark" cols="10" rows="2"
               class="form-control form-control-sm"><?= (isset($punch_detail->remark)) ? $punch_detail->remark : '' ?></textarea>
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
                     <a href="javascript:void(0);" target="popup"
                        onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
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
            <span aria-hidden="true">&times;</span>
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
<div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Vendor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
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
                     <label for="country">Country  <span class="text-danger">*</span></label>
                     <select class="form-control" id="country_id" name="country_id" required>
                        <option value="">Select</option>
                        <option value="India">India</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="state">State  <span class="text-danger">*</span></label>
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
$(document).ready(function () {
   initUI();

   let unitList = "";
   let itemList = "";
   let count = 1;
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
    ?>


    loadDropdownOptions(
        'From',
        '<?= base_url("extract/ExtractorController/get_company_options") ?>',
        <?= json_encode($cleanedBuyer) ?>,
        '<?= isset($punch_detail->buyer) ? $punch_detail->buyer : "" ?>'
    );


    loadDropdownOptions(
        'To',
        '<?= base_url("extract/ExtractorController/get_vendor_options") ?>',
        <?= json_encode($cleanedVendor) ?>,
        '<?= isset($punch_detail->vendor) ? $punch_detail->vendor : "" ?>'
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
}

function updateVendorAddress() {
   const address = $(this).find(":selected").data("address");
   $("#Vendor_Address").val(address);
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
}function loadItemList() {
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
         console.log("getMultiRecord response:", response); 
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
   $(`#Qty${index}`).val(item.qty);
   $(`#Unit${index}`).val(item.unit);
   $(`#MRP${index}`).val(item.mrp);
   $(`#Discount${index}`).val(item.discount);
   $(`#GST${index}`).val(item.gst);
   $(`#SGST${index}`).val(item.sgst);
   $(`#IGST${index}`).val(item.igst);
   $(`#Cess${index}`).val(item.cess);
   $(`#Price${index}`).val(item.price);
   $(`#Amount${index}`).val(item.amount);
   $(`#TAmount${index}`).val(item.total_amount);

   
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
                 <td><input type="text" name="Qty[]" id="Qty${serialNo}" class="form-control form-control-sm"></td>
                 <td><select name="Unit[]" id="Unit${serialNo}" class="form-control form-control-sm">${unitList}</select></td>
                 <td><input type="text" name="MRP[]" id="MRP${serialNo}" class="form-control form-control-sm"></td>
                 <td><input type="text" name="Discount[]" id="Discount${serialNo}" class="form-control form-control-sm"></td>
                 <td><input type="text" name="Price[]" id="Price${serialNo}" class="form-control form-control-sm" readonly></td>
                 <td><input type="text" name="Amount[]" id="Amount${serialNo}" class="form-control form-control-sm Amount" readonly></td>
                 <td><input type="text" name="GST[]" id="GST${serialNo}" class="form-control form-control-sm"></td>
                 <td><input type="text" name="SGST[]" id="SGST${serialNo}" class="form-control form-control-sm" readonly></td>
                 <td><input type="text" name="IGST[]" id="IGST${serialNo}" class="form-control form-control-sm"></td>
                 <td><input type="text" name="Cess[]" id="Cess${serialNo}" class="form-control form-control-sm"></td>
                 <td><input type="text" name="TAmount[]" id="TAmount${serialNo}" class="form-control form-control-sm TAmount" readonly></td>
                 <td><button type="button" name="remove" class="btn btn-danger btn-xs removeRow"><i class="fa fa-minus"></i></button></td>
              </tr>`;

   $("#multi_record").append(html);

   $("#Qty" + serialNo + ", #MRP" + serialNo + ", #Discount" + serialNo + ", #GST" + serialNo + ", #IGST" + serialNo + ", #Cess" + serialNo)
      .on("keypress", function (e) {
         return isNumberKey(e);
      })
      .on("change", function () {
         calculate(serialNo);
      });

   initUI();
}

function removeItemRow() {
   $(this).closest("tr").remove();
   calculate(count);
   updateSerialNumbers();
}

function updateSerialNumbers() {
   $("#multi_record tr").each(function (index) {
      $(this)
         .find("td:first")
         .text(index + 1);
   });
}

function isNumberKey(evt) {
   const charCode = evt.which || evt.keyCode;
   return !(charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57));
}

function calculate(num) {
   let qty = parseFloat($("#Qty" + num).val()) || 0;
   let mrp = parseFloat($("#MRP" + num).val()) || 0;
   let discount = parseFloat($("#Discount" + num).val()) || 0;
   let gst = parseFloat($("#GST" + num).val()) || 0;
   let igst = parseFloat($("#IGST" + num).val()) || 0;
   let cess = parseFloat($("#Cess" + num).val()) || 0;

   let price = mrp - (mrp * discount) / 100;
   let amount = qty * price;
   let totalGst = (amount * gst) / 100;
   let totalIgst = (amount * igst) / 100;
   let totalCess = (amount * cess) / 100;
   let totalAmount = amount + totalGst + totalIgst + totalCess;

   let sgst = igst == 0 ? gst / 2 : 0;
   let totalSgst = (amount * sgst) / 100;

   if (igst == 0) {
      totalAmount += totalSgst;
      $("#SGST" + num).val(sgst.toFixed(2));
   } else {
      $("#SGST" + num).val("0.00");
   }

   $("#Price" + num).val(price.toFixed(2));
   $("#Amount" + num).val(amount.toFixed(2));
   $("#TAmount" + num).val(totalAmount.toFixed(2));

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

   $("#Sub_Total").val(subTotal.toFixed(2));
   $("#Total").val(grandTotal.toFixed(2));

   let tcsRate = parseFloat($("#TCS").val()) || 0;
   let tcsAmount = (grandTotal * tcsRate) / 100;
   let finalGrandTotal = grandTotal + tcsAmount;

   $("#Grand_Total").val(finalGrandTotal.toFixed(2));
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

   const decimal = (total % 1).toFixed(2).split(".")[1] || "00";
   $("#Total").val(total.toFixed(2));
   $("#Total_Discount").val("0." + decimal);
   $("#Grand_Total").val((total - parseFloat("0." + decimal)).toFixed(2));
};
});
</script>