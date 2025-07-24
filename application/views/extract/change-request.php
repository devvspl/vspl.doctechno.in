<div class="content-wrapper" >
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">List of Classified Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <form method="GET" action="<?= base_url('change-request'); ?>" style="margin-bottom: 15px;">
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
                              <!-- <th class="text-center">Group Name</th> -->
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
                              <!-- <td class="text-center"><?= htmlspecialchars($doc->group_name); ?></td> -->
                              <td class="text-center"><?= htmlspecialchars($doc->location_name); ?></td>
                              <td class="text-center"><?= $doc->document_name ?? ''; ?></td>
                              <td class="text-center text-primary"><?= $doc->file_type ?? ''; ?></td>
                              <td class="text-center"><?= htmlspecialchars($doc->scanned_by); ?></td>
                              <td class="text-center"><?= htmlspecialchars($doc->scan_date); ?></td>
                              <td class="text-center"><?= htmlspecialchars($doc->bill_approver_id); ?></td>
                              <td class="text-center"><?= htmlspecialchars($doc->bill_approved_date); ?></td>
                              <td class="text-center">
                                  <button class="btn btn-sm btn-primary approve-change-request" data-scan-id="<?= $doc->scan_id; ?>">
                                 Approve Request
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
<script>
   $(document).ready(function () {
      $(document).on("click", ".approve-change-request", function () {
       let $button = $(this);
       let scanId = $button.data("scan-id");
       if (!confirm("Are you sure you want to approve this change request?")) {
           return;
       }
       $button.prop("disabled", true).text("Please wait...");
       $.ajax({
           url: "<?= base_url('extract/ExtractorController/approveChangeRequest'); ?>",
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
                   $button.prop("disabled", false).text("Approve Request");
               }
           },
           error: function () {
               alert("Error fetching details.");
               $button.prop("disabled", false).text("Approve Request");
           },
       });
      });
   });
</script>