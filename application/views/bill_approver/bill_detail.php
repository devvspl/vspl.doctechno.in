<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Punch File - <?= $bill_detail->document_name ?></h3>
                  <div class="box-tools pull-right">
                     <a href="<?= base_url('pending_bill_approve'); ?>" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-4">
                        <?php if ($bill_detail->file_extension == 'pdf') : ?>
                        <object data="<?= $bill_detail->file_path ?>" type="" height="490px" width="100%;"></object>
                        <?php else : ?>
                        <input type="hidden" name="image" id="image" value="<?= $bill_detail->file_path ?>">
                        <div id="imageViewerContainer" style="width: 400px; height:490px;"></div>
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
                        <?php endif; ?>
                     </div>
                     <div class="col-md-8">
                        <p><strong>Location:</strong> <?= $bill_detail->location_name ?></p>
                        <?php if (!empty($bill_detail->scan_date)) : ?>
                        <p>
                           <strong>Scanned By:</strong>
                           <?= $bill_detail->scanned_by_name ?> (<?= $bill_detail->scan_date ?>)
                        </p>
                        <?php endif; ?>
                        <?php if (!empty($bill_detail->temp_scan_date)) : ?>
                        <p>
                           <strong>Temp Scanned By:</strong>
                           <?= $bill_detail->temp_scanned_by_name ?> (<?= $bill_detail->temp_scan_date ?>)
                        </p>
                        <?php endif; ?>
                        <a href="<?= base_url('approve_bill/' . $bill_detail->scan_id) ?>"
                           class="btn btn-success"
                           onclick="return confirm('Are you sure you want to approve this bill?');">
                        <i class="fa fa-check"></i> Approve
                        </a>
                        <button type="button" class="btn btn-danger" data-id="<?= $bill_detail->scan_id ?>" id="reject_bill_btn">
                        <i class="fa fa-times"></i> Reject
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<!-- Reject Modal -->
<div id="rejectModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <div class="modal-body">
            <input type="hidden" name="Scan_Id" id="rejectScanId">
            <div class="form-group">
               <label for="Reject_Remark">Rejection Reason <span class="text-danger">*</span></label>
               <input type="text" name="Reject_Remark" id="Reject_Remark" class="form-control">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" id="reject_btn" class="btn btn-danger">Submit Rejection</button>
         </div>
      </div>
   </div>
</div>
<!-- JS Handling -->
<script>
   $(document).on("click", "#reject_bill_btn", function () {
      var scanId = $(this).data('id');
      $("#rejectScanId").val(scanId);
      $("#Reject_Remark").val('');
      $("#Reject_Remark").css('border-color', '');
      $("#rejectModal").modal("show");
   });
   $(document).on("click", "#reject_btn", function () {
        var scanId = $("#rejectScanId").val();
        var remark = $("#Reject_Remark").val().trim();
        
        if (remark === '') {
            $("#Reject_Remark").focus().css('border-color', 'red');
            return;
        }
        
        $.ajax({
            type: "POST",
            url: "<?= base_url('reject_bill/') ?>" + scanId,
            data: { Remark: remark },
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $("#rejectModal").modal('hide');
                    $('body').prepend(`
                    <div class="alert alert-success text-center" id="rejectSuccessMsg" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto;">
                        Bill Rejected Successfully.
                    </div>
                    `);
                    setTimeout(function () {
                    $("#rejectSuccessMsg").fadeOut(300, function () {
                        $(this).remove();
                        window.location.href = "<?= base_url('pending_bill_approve') ?>";
                    });
                    }, 2000); 
                } else {
                    alert("Rejection failed. Try again.");
                }
            },
            error: function () {
                alert("An error occurred. Please try again.");
            }
        });
        });
</script>