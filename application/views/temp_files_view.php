<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Temporary Files</h3>
               </div>
               <div class="box-body">
                  <div class="table-responsive mailbox-messages">
                     <div class="download_label">Temporary Files</div>
                     <?php if ($this->session->flashdata('message')): ?>
                     <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                     </div>
                     <?php endif; ?>
                     <table class="table table-striped  table-hover example" >
                        <thead>
                           <tr >
                              <th class="text-center">File Name</th>
                              <th class="text-center">Created</th>
                              <th class="text-center">Size</th>
                              <th class="text-center">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if(is_array($data) && count($data) > 0): ?>
                           <?php foreach ($data as $file): ?>
                           <tr>
                              <td><a href="javascript:void(0)" onclick="openFilePopup('<?php echo site_url('TempFilesController/view/' . $file['name']); ?>')"><?php echo $file['name']; ?></a></td>
                              <td><?php echo $file['created']; ?></td>
                              <td><?php echo $file['size']; ?></td>
                              <td style="display: flex; gap: 10px; justify-content: center">
                                    <button class="btn btn-sm btn-success" onclick="openFilePopup('<?php echo site_url('TempFilesController/view/' . $file['name']); ?>')">View</button>
                                    <a class="btn btn-sm btn-primary" href="<?php echo site_url('TempFilesController/download/' . $file['name']); ?>">Download</a> 
                                    <a class="btn btn-sm btn-danger" href="<?php echo site_url('TempFilesController/delete/' . $file['name']); ?>" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
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
   <script>
       function openFilePopup(url) {
            let popup = window.open(url, "FilePreview", "width=800,height=600,scrollbars=yes,resizable=yes");
            if (!popup) {
                alert("Popup blocked! Please allow popups for this site.");
            }
        }

   </script>
</div>
