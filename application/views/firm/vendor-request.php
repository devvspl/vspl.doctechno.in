<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">List of Vendor Request</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <table class="table table-striped table-hover example">
                        <thead>
                           <tr>
                              <th class="text-center">S No.</th>
                              <th class="text-center">Firm Name</th>
                              <th class="text-center">Firm Code</th>
                              <th class="text-center">Country</th>
                              <th class="text-center">State</th>
                              <th class="text-center">City</th>
                              <th class="text-center">GST</th>
                              <th class="text-center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($vendorRequest)) : ?>
                           <?php $i = 1; foreach ($vendorRequest as $doc) : ?>
                           <tr data-id="<?= $doc->firm_id; ?>">
                              <td class="text-center"><?= $i++ ?></td>
                              <td class="text-center"><?= $doc->firm_name; ?></td>
                              <td class="text-center"><?= $doc->firm_code; ?></td>
                              <td class="text-center"><?= $doc->country_name; ?></td>
                              <td class="text-center"><?= $doc->state_name; ?></td>
                              <td class="text-center"><?= $doc->city_name; ?></td>
                              <td class="text-center"><?= $doc->gst; ?></td>
                              <td class="text-center">
                                 <button class="btn btn-success btn-sm approve-btn" data-id="<?= $doc->firm_id; ?>">Approve</button>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                           <?php else : ?>
                           <tr>
                              <td colspan="8" class="text-center">No records found</td>
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
      $(document).on("click", ".approve-btn", function () {
         const btn = $(this);
         const firmId = btn.data("id");
         btn.prop("disabled", true).text("Approving...");
         $.ajax({
            url: "<?= base_url('approve-vendor'); ?>",
            type: "POST",
            data: { firm_id: firmId },
            dataType: "json",
            success: function (res) {
               if (res.status === true) {
                  btn.closest('tr').fadeOut(500, function () {
                     $(this).remove();
                     $('table tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                     });
                  });
                  alert(res.message);
               } else {
                  alert(res.message);
                  btn.prop("disabled", false).text("Approve");
               }
            },
            error: function () {
               alert("Something went wrong!");
               btn.prop("disabled", false).text("Approve");
            }
         });
      });
   });
</script>