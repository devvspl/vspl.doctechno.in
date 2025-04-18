<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">List of Classified Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <form method="GET" action="<?= base_url('processed'); ?>" style="margin-bottom: 15px;">
                        <div class="row mb-3">
                           <div class="col-md-4">
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
                           </div>
                           <div class="col-md-4">
                              <label>Location:</label>
                              <select name="location_id" class="form-control">
                                 <option value="">All Locations</option>
                                 <?php foreach ($locations as $location): ?>
                                 <option value="<?= $location->location_id; ?>" 
                                    <?= isset($_GET['location_id']) && $_GET['location_id'] == $location->location_id ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($location->location_name); ?>
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
                              <th class="text-center">Company</th>
                              <th class="text-center">Location</th>
                              <th class="text-center">File Name</th>
                              <th class="text-center">Document</th>
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
                                <td class="text-center"><?= htmlspecialchars($doc->group_name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->location_name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Document_Name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center text-primary"><?= htmlspecialchars($doc->file_type ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Scan_By ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Scan_Date ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Bill_Approver ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($doc->Bill_Approver_Date ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center">
                                   <?php if ($doc->is_extract == 'C'): ?>
                                      <span class="text-warning font-weight-bold">Change Request Submitted</span>
                                   <?php else: ?>
                                      <button class="btn btn-sm btn-primary change-request" data-scan-id="<?= htmlspecialchars($doc->Scan_Id ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                         Change Request
                                      </button>
                                   <?php endif; ?>
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
<script>
   $(document).ready(function () {
      $(document).on("click", ".change-request", function () {
       let $button = $(this);
       let scanId = $button.data("scan-id");
       if (!confirm("Are you sure you want to submit this change request?")) {
           return;
       }
       $button.prop("disabled", true).text("Please wait...");
       $.ajax({
           url: "<?= base_url('ExtractorController/changeRequest'); ?>",
           type: "POST",
           data: { scan_id: scanId },
           success: function (response) {
               let jsonResponse = JSON.parse(response);
               if (jsonResponse.status === "success") {
                   alert(jsonResponse.message);
                   setTimeout(function () {
                       location.reload();
                   }, 1000);
               } else {
                   alert("Request failed: " + jsonResponse.message);
                   $button.prop("disabled", false).text("Change Request");
               }
           },
           error: function () {
               alert("Error fetching details.");
               $button.prop("disabled", false).text("Change Request");
           },
       });
   });
   
   });
</script>