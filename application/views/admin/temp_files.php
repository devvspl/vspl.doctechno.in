<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box">
               <div class="box-header with-border">
                  <h3 class="box-title">Temporary Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-success">
                           <?php echo $this->session->flashdata('message'); ?>
                        </div>
                     <?php endif; ?>
                     <table id="tempFilesTable" class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th style="text-align: left;">File Name</th>
                              <th style="text-align: center;">Created</th>
                              <th style="text-align: center;">Size</th>
                              <th style="text-align: center;">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (is_array($data) && count($data) > 0): ?>
                              <?php foreach ($data as $file): ?>
                                 <tr>
                                    <td style="text-align: center;"><a href="javascript:void(0)" style="text-align: center;"
                                          onclick="openFilePopup('<?php echo site_url('temp_files/view/' . $file['name']); ?>')"><?php echo $file['name']; ?></a>
                                    </td>
                                    <td style="text-align: center;"><?php echo $file['created']; ?></td>
                                    <td style="text-align: center;"><?php echo $file['size']; ?></td>
                                    <td style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                       <button class="btn btn-sm btn-success"
                                          onclick="openFilePopup('<?php echo site_url('temp_files/view/' . $file['name']); ?>')"
                                          title="View">
                                          <i class="fa fa-eye"></i>
                                       </button>

                                       <a class="btn btn-sm btn-primary"
                                          href="<?php echo site_url('temp_files/download/' . $file['name']); ?>"
                                          title="Download">
                                          <i class="fa fa-download"></i>
                                       </a>

                                       <a class="btn btn-sm btn-danger"
                                          href="<?php echo site_url('temp_files/delete/' . $file['name']); ?>"
                                          onclick="return confirm('Are you sure you want to delete this file?')"
                                          title="Delete">
                                          <i class="fa fa-trash"></i>
                                       </a>
                                    </td>

                                 </tr>
                              <?php endforeach; ?>
                           <?php else: ?>
                              <tr>
                                 <td colspan="4" class="text-center">No temporary files found.</td>
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
<div class="modal fade" id="documentPreviewModal" tabindex="-1" role="dialog" data-backdrop="static"
   data-keyboard="false">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Document Preview</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body p-0">
            <iframe id="documentPreviewIframe" src="" width="100%" height="500px" frameborder="0"
               style="border:0;"></iframe>
         </div>
      </div>
   </div>
</div>
<script>
   function openFilePopup(url) {
      $('#documentPreviewIframe').attr('src', url);
      $('#documentPreviewModal').modal('show');
   }
   $(document).ready(function () {
      $("#tempFilesTable").DataTable({
         paging: true,
         searching: true,
         ordering: true,
         dom: 'Bfrtip',
         buttons: [
            {
               extend: 'csv',
               text: '<i class="fa fa-file-text-o"></i> Export',
               title: 'Temporary_Files_' + new Date().toISOString().slice(0, 10),
               className: 'btn btn-primary btn-sm',
               exportOptions: {
                  columns: ':not(:last-child)'
               }
            }
         ]
      });
   });
</script>