<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-4">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Upload - Supporting File</h3>
               </div>
               <form id="form1" action="<?= base_url('upload_supporting'); ?>" id="scan_support" name="scan_support"
                  method="post" accept-charset="utf-8" enctype="multipart/form-data">
                  <div class="box-body">
                     <?php if ($this->session->flashdata('message')) { ?>
                        <?php echo $this->session->flashdata('message') ?>
                     <?php } ?>
                     <div class="form-group">
                        <input type="hidden" name="scan_id" id="scan_id" value="<?= $scan_id; ?>">
                        <input class="filestyle form-control" type='file' name='support_file' id="support_file"
                           accept="image/*,application/pdf">
                     </div>
                  </div>
                  <div class="box-footer">
                     <button type="submit" id="upload_support" class="btn btn-warning pull-right">Upload</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-8">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title"><?= $this->customlib->getDocumentName($scan_id); ?>
                  </h3>
               </div>
               <div class="bx-body">
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>File</th>
                              <th>File Type</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           if (!empty($main_file)): ?>
                              <tr>
                                 <td>1</td>
                                 <td>
                                    <a href="javascript:void(0);" target="popup"
                                       onclick="window.open('<?= $main_file->file_path; ?>','popup','width=600,height=600');">
                                       <?= $main_file->file_name; ?>
                                    </a>
                                 </td>
                                 <td><?= ($main_file->is_main_file == 'Y') ? 'Main File' : 'Support File'; ?></td>
                                 <td></td>
                              </tr>
                           <?php endif; ?>
                           <?php
                           $i = 2;
                           foreach ($supporting_files as $supporting_file):
                              ?>
                              <tr>
                                 <td><?= $i++; ?></td>
                                 <td>
                                    <a href="javascript:void(0);" target="popup"
                                       onclick="window.open('<?= $supporting_file->file_path; ?>','popup','width=600,height=600');">
                                       <?= $supporting_file->file_name; ?>
                                    </a>
                                 </td>
                                 <td><?= ($supporting_file->is_main_file == 'Y') ? 'Main File' : 'Support File'; ?></td>
                                 <td>
                                    <a href="javascript:void(0);" data-support_id="<?= $supporting_file->support_id ?>"
                                       class="btn btn-danger btn-xs delete_file">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="box-footer">
                  <button type="submit" id="final_submit" class="btn btn-success pull-right">Final Submit</button>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script type="text/javascript">
   $(document).ready(function () {
      
      $(document).on("click", "#delete_all", function () {
         var scan_id = $("#scan_id").val();
         var url = "<?= base_url() ?>Scan/delete_all";
         if (confirm("Are you sure to delete all ?")) {
            $.ajax({
               url: url,
               type: "POST",
               data: {
                  scan_id: scan_id,
               },
               dataType: "json",
               success: function (data) {
                  if (data.status == 200) {
                     window.location.href = "<?= base_url() ?>Scan";
                  }
               },
            });
         }
      });

      $(document).on("click", "#final_submit", function () {
         var scan_id = $("#scan_id").val();
         var url = "<?= base_url('scan_final_submit') ?>";
         if (confirm("Are you sure to final submit ?")) {
            $.ajax({
               url: url,
               type: "POST",
               data: {
                  scan_id: scan_id,
               },
               dataType: "json",
               success: function (data) {
                  if (data.status == 200) {
                     window.location.href = "<?= base_url('scanner?status=submitted');?>";
                  }
               },
            });
         }
      });

      $(document).on("click", ".delete_file", function () {
         var support_id = $(this).data('support_id')
         var url = "<?= base_url('delete_supporting_file') ?>";
         if (confirm("Are you sure to delete?")) {
            $.ajax({
               url: url,
               type: "POST",
               data: {
                  id: support_id,
               },
               dataType: "json",
               success: function (data) {
                  if (data.status == 200) {
                     window.location.href = "<?= base_url('upload_supporting_file/') . $scan_id ?>";
                  }
               },
            });
         }
      });
   });
</script>