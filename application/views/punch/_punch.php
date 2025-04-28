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
                     <a href="<?= base_url('punch'); ?>" class="btn btn-primary btn-sm">
                     <i class="fa fa-long-arrow-left"></i> Back
                     </a>
                  </div>
               </div>
               <div class="box-body">
                  <div style="display: flex; flex-direction: column; align-items: center;">
                     <div class="loader" id="loader" style="display: none;"></div>
                     <span id="loader-text" style="display: none; margin-top: 10px; font-size: 14px; color: #3a495e;">Please Wait...</span>
                  </div>
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
                     <?php echo $this->session->flashdata('message'); ?>
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
                        <?php $this->load->view('punch/additional'); ?>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>