<div class="content-wrapper" style="min-height: 946px;">
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     Punch File - <?= $doc_type_name; ?>
                     <?php if (!empty($document_name)) : ?>
                     - (<?= $document_name; ?>)
                     <?php endif; ?>
                  </h3>
                  <div class="box-tools pull-right">
                  <?php if (!empty($user_permission) && $user_permission == 'N') : ?>
                     <a href="<?= base_url('punch'); ?>" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  <?php endif; ?>
                  <?php if (!empty($user_permission) && $user_permission == 'Y') : ?>
                     <a href="<?= base_url('finance_punch'); ?>" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  <?php endif; ?>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-4">
                        <?php if ($rec->File_Ext == 'pdf') : ?>
                        <object data="<?= $rec->File_Location ?>" type="" height="490px" width="100%;"></object>
                        <?php else : ?>
                        <input type="hidden" name="image" id="image" value="<?= $rec->File_Location ?>">
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
               
                        <?php if ($this->session->flashdata('message')): ?>
                           <div class="custom-alert alert-<?= $this->session->flashdata('alert_type') ?>">
                              <?= $this->session->flashdata('message') ?>
                              <span class="custom-alert-close" onclick="this.parentElement.style.display='none';">&times;</span>
                           </div>
                        <?php endif; ?>


                     <?php if (!empty($user_permission) && $user_permission == 'Y') : ?>
                        <div class="tabs-container">
                           <div class="tabs active-tab" id="invoice-tab">Basic Details</div>
                           <div class="tabs" id="additional-info-tab">Additional Information</div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($doc_config['view'])) : ?>
                        <?php $this->load->view('punch/' . $doc_config['view']); ?>
                        <?php endif; ?>
                        <?php if (!empty($user_permission) && $user_permission == 'Y') : ?>
                        <?php $this->load->view('punch/additional', $main_record); ?>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>