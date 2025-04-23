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
                           <!-- <div class="col-md-4">
                              <label>Company:</label>
                              <select name="group_id" class="form-control">
                                 <option value="">All Company</option>
                                 <?php foreach ($groups as $group): ?>
                                 <option value="<?= $group->group_id; ?>" 
                                    <?= isset($_GET['group_id']) && $_GET['group_id'] == $group->group_id ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($group->group_name); ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div> -->
                           <div class="col-md-4">
                              <label>Location:</label>
                              <select name="location_id" class="form-control">
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
                           <tr>
                              <td class="text-center"><?= $i++ ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->location_name ?? ''); ?></td>
                                <td class="text-center"><?= $doc->Document_Name ?? ''; ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Scan_By ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Scan_Date ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Bill_Approver ?? ''); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Bill_Approver_Date ?? ''); ?></td>

                              <td class="text-center">
                                 <button class="btn btn-primary btn-sm view-details" data-scan-id="<?= $doc->Scan_Id; ?>">
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
    $(document).on("click", ".extract-btn", function () {
        let $button = $(this);
        let scanId = $button.data("scan-id");
        let typeId = $("#docType_" + scanId).val();

        if (typeId === "") {
            alert("Please select a document type.");
            return;
        }

        $button.prop("disabled", true).text("Please wait...");

        $.ajax({
            url: "<?= base_url('extract/ExtractorController/extractDetails'); ?>",
            type: "POST",
            data: { scan_id: scanId, type_id: typeId },
           success: function (response) {
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.status == 'success') {
                    alert(jsonResponse.message); 
                    setTimeout(function () {
                        location.reload(); 
                    }, 1000);
                } else {
                    alert("Extraction failed: " + jsonResponse.message);
                }
            },

            error: function () {
                alert("Error fetching details.");
                $button.prop("disabled", false).text("Update");
            },
        });
    });
});
</script>