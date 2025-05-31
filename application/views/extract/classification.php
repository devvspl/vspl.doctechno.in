<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">List of Scanned Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Temporary Files</div>
                     <?php if ($this->session->flashdata('message')): ?>
                     <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                     </div>
                     <?php endif; ?>
                     <form method="GET" action="<?= base_url('classification'); ?>" style="margin-bottom: 15px;">
                        <div class="row mb-3">
                           <div class="col-md-4">
                              <label>Location:</label>
                              <select name="location_id" id="location_id" class="form-control">
                                 <option value="">All Locations</option>
                                 <?php foreach ($locations as $location): ?>
                                 <option value="<?= $location->location_id; ?>" 
                                    <?= isset($_GET['location_id']) && $_GET['location_id'] == $location->location_id ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($location->location_name ?? ''); ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                           <div class="col-md-4">
                              <label>&nbsp;</label>
                              <button type="submit" style="padding: 4px 0;" class="btn btn-primary btn-block">Filter</button>
                           </div>
                        </div>
                     </form>
                     <table class="table table-striped table-hover example">
                        <thead>
                           <tr>
                              <th class="text-center">S No.</th>
                              <th class="text-center">Location</th>
                              <th class="text-center">File Name</th>
                              <th class="text-center">Scanned By</th>
                              <th class="text-center">Scan Date</th>
                              <th class="text-center">Bill Approver</th>
                              <th class="text-center">Approval Date</th>
                              <th class="text-center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($documents)) : ?>
                           <?php $i = 1; foreach ($documents as $doc) : ?>
                           <tr data-scan-id="<?= $doc->scan_id; ?>">
                              <td class="text-center"><?= $i++ ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->location_name ?? ''); ?></td>
                                <td class="text-center"><?= $doc->document_name ?? ''; ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->scanned_by ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->scan_date ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->bill_approver_id ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->bill_approved_date ?? ''); ?></td>

                              <td class="text-center">
                                 <button class="btn btn-primary btn-sm view-details" data-scan-id="<?= $doc->scan_id; ?>">
                                 Set Document
                                 </button>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                           <?php else : ?>
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
<div class="modal fade" id="documentDetailsModal" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Document Details</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <div id="documentDetailsContent">
               <p class="text-center">Loading details...</p>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
$(document).ready(function () {
    $("#location_id").select2();
    $('.select2').select2();
    $(".view-details").on("click", function () {
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
            },
            error: function () {
                $("#documentDetailsContent").html('<p class="text-danger text-center">Error loading details.</p>');
            },
        });
    });

    $(document).on('change', 'select[id^="department_"]', function() {
        var department_id = $(this).val();
        var scan_id = $(this).attr('id').split('_')[1];
        var subdepartment_select = $('#subdepartment_' + scan_id);
        var bill_approver_select = $('#bill_approver_' + scan_id);
        subdepartment_select.html('<option value="">Select Subdepartment</option>');
        bill_approver_select.html('<option value="">Select Bill Approver</option>');
        if (department_id) {
            // Fetch Subdepartments
            $.ajax({
                url: '<?= base_url("extract/ExtractorController/getSubdepartments"); ?>',
                type: 'POST',
                data: { department_id: department_id },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        subdepartment_select.append(
                            $('<option>').val(item.sub_department_id).text(item.sub_department_name)
                        );
                    });
                },
                error: function() {
                    alert('Error fetching subdepartments');
                }
            });

            // Fetch Bill Approvers
            $.ajax({
                url: '<?= base_url("extract/ExtractorController/getBillApprovers"); ?>',
                type: 'POST',
                data: { department_id: department_id },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, item) {
                        bill_approver_select.append(
                            $('<option>').val(item.user_id).text(item.name || item.username) // Adjust field name as needed
                        );
                    });
                },
                error: function() {
                    alert('Error fetching bill approvers');
                }
            });
        }
    });

    $(document).on("click", ".extract-btn", function () {
    let $button = $(this);
    let scanId = $button.data("scan-id");
    let typeId = $("#docType_" + scanId).val();
    let department = $("#department_" + scanId).val();
    let subdepartment = $("#subdepartment_" + scanId).val(); // Optional
    let bill_approver = $("#bill_approver_" + scanId).val();
    let location = $("#location_" + scanId).val();

    if (typeId === "") {
        alert("Please select a document type.");
        return;
    }
    if (department === "") {
        alert("Please select a department.");
        return;
    }
    if (bill_approver === "") {
        alert("Please select a bill approver.");
        return;
    }
    if (location === "") {
        alert("Please select a location.");
        return;
    }

    $button.prop("disabled", true).text("Please wait...");
    $.ajax({
        url: "<?= base_url('extract/ExtractorController/extractDetails'); ?>",
        type: "POST",
        data: { 
            scan_id: scanId, 
            type_id: typeId,
            department: department,
            subdepartment: subdepartment, 
            bill_approver: bill_approver,
            location: location
        },
        success: function (response) {
            let jsonResponse = JSON.parse(response);
            if (jsonResponse.status == 'success') {
                $("#documentDetailsContent").html('<p class="text-success text-center">' + jsonResponse.message + '</p>');
                setTimeout(function () {
                    $("#documentDetailsModal").modal("hide");
                    $('tr[data-scan-id="' + scanId + '"]').fadeOut(500, function() {
                        $(this).remove();
                        $('table tbody tr').each(function(index) {
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