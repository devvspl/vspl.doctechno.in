<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Pending Classification List</h3>
                  
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <table id="classificationTable" class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th style="text-align:left">S No.</th>
                              <th style="text-align:left">Document Name</th>
                              <th style="text-align:center">Scanned By</th>
                              <th style="text-align:center">Scan Date</th>
                              <th style="text-align:center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($documents)): ?>
                              <?php $i = 1;
                              foreach ($documents as $doc): ?>
                                 <tr data-scan-id="<?= $doc->scan_id; ?>">
                                    <td style="text-align:left"><?= $i++ ?></td>
                                    <td style="text-align:left"><?= $doc->document_name ?? ''; ?></td>
                                    <td style="text-align:center"><?= htmlspecialchars($doc->scanned_by ?? ''); ?></td>
                                    <td style="text-align:center"><?= htmlspecialchars($doc->scan_date ?? ''); ?></td>
                                    <td style="text-align:center">
                                       <button class="btn btn-primary btn-sm view-details"
                                          data-scan-id="<?= $doc->scan_id; ?>" title="Set Document">
                                          <i class="fa fa-file-text-o"></i>
                                       </button>
                                       <button type="button" class="btn btn-sm btn-danger reject-bill"
                                          data-id="<?= $doc->scan_id ?>" title="Reject">
                                          <i class="fa fa-times"></i>
                                       </button>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           <?php else: ?>
                              <tr>
                                 <td colspan="10" class="text-center">No records found</td>
                              </tr>
                           <?php endif; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<div class="modal fade" id="documentDetailsModal" tabindex="-1" role="dialog" data-backdrop="static"
   data-keyboard="false">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Document Details</h5>
            <button type="button" class="close" data-dismiss="modal">×</button>
         </div>
         <div class="modal-body">
            <div id="documentDetailsContent">
               <p class="text-center">Loading details...</p>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Redesigned Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
   aria-hidden="true" data-backdrop="static">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg rounded">
         <div class="modal-header">
            <h5 class="modal-title" id="rejectModalLabel">Reject Scan</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" name="scan_id" id="rejectScanId">
            <div class="form-group">
               <label for="Reject_Remark" class="font-weight-bold">Rejection Reason <span
                     class="text-danger">*</span></label>
               <input type="text" name="Reject_Remark" id="Reject_Remark" class="form-control"
                  placeholder="Enter reason for rejection" required>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="reject_btn" class="btn btn-danger">Submit Rejection</button>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function () {
      $("#classificationTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Pending_Classification_List_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
      $("#location_id").select2();
      $('.select2').select2();
      $(document).on("click", ".view-details", function () {
         let scanId = $(this).data("scan-id");
         $("#documentDetailsModal").modal("show");
         $.ajax({
            url: "<?= base_url('extract/ExtractorController/getDetails'); ?>",
            type: "POST",
            data: { scan_id: scanId },
            beforeSend: function () {
               $("#documentDetailsContent").html('<p class="text-center">Loading details...</p>');
            },
            success: function (response) {
               $("#documentDetailsContent").html(response);
               $("#documentDetailsContent").find("select.select2").select2({
                  placeholder: "Select an option",
                  allowClear: true,
                  width: '100%',
                  dropdownParent: $("#documentDetailsModal")
               });

               $("#documentDetailsContent").find("input[id^='autoApprove_']").on("change", function () {
                  let scanId = $(this).attr('id').split('_')[1];
                  let $billApproverContainer = $("#billApproverContainer_" + scanId);
                  if ($(this).is(":checked")) {
                     $billApproverContainer.hide();
                  } else {
                     $billApproverContainer.show();

                     $("#bill_approver_" + scanId).select2({
                        placeholder: "Select Bill Approver",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $("#documentDetailsModal")
                     });
                  }
               });
            },
            error: function () {
               $("#documentDetailsContent").html('<p class="text-danger text-center">Error loading details.</p>');
            },
         });
      });
      $(document).on('change', 'select[id^="department_"]', function () {
         var department_id = $(this).val();
         var scan_id = $(this).attr('id').split('_')[1];
         var subdepartment_select = $('#subdepartment_' + scan_id);
         var bill_approver_select = $('#bill_approver_' + scan_id);
         subdepartment_select.html('<option value="">Select Subdepartment</option>');
         bill_approver_select.html('<option value="">Select Bill Approver</option>');
         if (department_id) {
            $.ajax({
               url: '<?= base_url("extract/ExtractorController/getSubdepartments"); ?>',
               type: 'POST',
               data: { department_id: department_id },
               dataType: 'json',
               success: function (data) {
                  $.each(data, function (index, item) {
                     subdepartment_select.append(
                        $('<option>').val(item.sub_department_id).text(item.sub_department_name)
                     );
                  });

                  subdepartment_select.select2({
                     placeholder: "Select Subdepartment",
                     allowClear: true,
                     width: '100%',
                     dropdownParent: $("#documentDetailsModal")
                  });
               },
               error: function () {
                  alert('Error fetching subdepartments');
               }
            });


            $.ajax({
               url: '<?= base_url("extract/ExtractorController/getBillApprovers"); ?>',
               type: 'POST',
               data: { department_id: department_id },
               dataType: 'json',
               success: function (data) {
                  $.each(data, function (index, item) {
                     bill_approver_select.append(
                        $('<option>')
                           .val(item.user_id)
                           .text(
                              (item.first_name && item.last_name
                                 ? item.first_name + ' ' + item.last_name
                                 : item.username
                              )
                           )
                     );
                  });

                  if ($("#billApproverContainer_" + scan_id).is(":visible")) {
                     bill_approver_select.select2({
                        placeholder: "Select Bill Approver",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $("#documentDetailsModal")
                     });
                  }
               },
               error: function () {
                  alert('Error fetching bill approvers');
               }
            });
         }
      });
      $(document).on("click", ".reject-bill", function () {
         var scanId = $(this).data('id');
         $("#rejectScanId").val(scanId);
         $("#Reject_Remark").val('');
         $("#Reject_Remark").css('border-color', '');
         $("#rejectModal").modal("show");
      });
      $(document).on("click", "#reject_btn", function () {
         var scanId = $("#rejectScanId").val();
         var remark = $("#Reject_Remark").val().trim();
         var $btn = $(this);
         if (remark === '') {
            $("#Reject_Remark").focus().css('border-color', 'red');
            return;
         }
         showLoader();
         $.ajax({
            type: "POST",
            url: "<?= base_url('reject_bill/') ?>" + scanId,
            data: { Remark: remark },
            dataType: "json",
            success: function (response) {
               hideLoader();
               if (response.status == 200) {
                  $("#rejectModal").modal('hide');
                  showToast('success', 'Bill rejected successfully.');
                  setTimeout(function () {
                     window.location.href = "<?= base_url('classification') ?>";
                  }, 2000);
               } else {
                  showToast('error', 'Rejection failed. Try again.');
               }
            },
            error: function () {
               hideLoader();
               showToast('error', 'An unexpected error occurred.');
            }
         });
      });
      $(document).on("click", ".extract-btn", function () {
         let $button = $(this);
         let scanId = $button.data("scan-id");
         let typeId = $("#docType_" + scanId).val();
         let department = $("#department_" + scanId).val();
         let subdepartment = $("#subdepartment_" + scanId).val();
         let location = $("#location_" + scanId).val();

         if (typeId === "") {
            alert("Please select a document type.");
            return;
         }
         if (department === "") {
            alert("Please select a department.");
            return;
         }

         // if (location === "") {
         //    alert("Please select a location.");
         //    return;
         // }

         $button.prop("disabled", true).text("Please wait...");
         $.ajax({
            url: "<?= base_url('extract/ExtractorController/extractDetails'); ?>",
            type: "POST",
            data: {
               scan_id: scanId,
               type_id: typeId,
               department: department,
               subdepartment: subdepartment,
               // location: location,
            },
            success: function (response) {
               let jsonResponse = JSON.parse(response);
               if (jsonResponse.status == 'success') {
                  $("#documentDetailsContent").html('<p class="text-success text-center">' + jsonResponse.message + '</p>');
                  setTimeout(function () {
                     $("#documentDetailsModal").modal("hide");
                     $('tr[data-scan-id="' + scanId + '"]').fadeOut(500, function () {
                        $(this).remove();
                        $('table tbody tr').each(function (index) {
                           $(this).find('td:first').text(index + 1);
                        });
                     });
                  }, 1000);
               } else {
                  $("#documentDetailsContent").html('<p class="text-danger text-center">' + jsonResponse.message + '</p>');
                  $button.prop("disabled", false).text("Update");
                  setTimeout(function () {
                     $("#documentDetailsModal").modal("hide");
                  }, 1000);
               }
            },
            error: function () {
               $("#documentDetailsContent").html('<p class="text-danger text-center">Error processing document.</p>');
               $button.prop("disabled", false).text("Update");
               setTimeout(function () {
                  $("#documentDetailsModal").modal("hide");
               }, 1000);
            },
         });
      });
   });
</script>