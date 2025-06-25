<?php if ($document): ?>
   <div class="row">
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
               <iframe src="<?= !empty($document->file_path) ? htmlspecialchars($document->file_path) : '#'; ?>"
                  width="100%" height="400px" frameborder="0"></iframe>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12">
                     <p style="margin-top: 5px;"><strong>Company Name:</strong>
                        <?= htmlspecialchars($document->group_name ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>File Name:</strong>
                        <?= htmlspecialchars($document->document_name ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Document Type:</strong>
                        <?= htmlspecialchars($document->file_type ?? '-'); ?>
                     </p>
                  </div>
                  <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Department:</strong>
                        <?= htmlspecialchars($document->department_name ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Sub Department:</strong>
                        <?= htmlspecialchars($document->sub_department_name ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Location:</strong>
                        <?= htmlspecialchars($document->location_name ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Document Received Date:</strong></p>
                  </div>
                  <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Scanned By:</strong>
                        <?= htmlspecialchars($document->scanned_by ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Scan Date:</strong>
                        <?= htmlspecialchars($document->scan_date ?? '-'); ?>
                     </p>
                     <p style="margin-top: 5px;"><strong>Classified Date:</strong>
                        <?= htmlspecialchars($document->classified_date ?? '-'); ?>
                     </p>
                     <?php
                     $maxDate = date('Y-m-d'); 
                     $minDate = date('Y-m-d', strtotime('-2 days'));
                     $defaultDate = $document->received_date ?? $maxDate;
                     ?>
                     <div class="form-group" style="display: flex; gap: 10px; align-items: center;">
                        <input type="date" class="form-control" style="width: 200px;"
                           id="received_date_<?= $document->scan_id; ?>" name="received_date" min="<?= $minDate; ?>"
                           max="<?= $maxDate; ?>" value="<?= htmlspecialchars($defaultDate); ?>">
                     </div>

                  </div>
                  <div class="col-md-12 text-right mt-3" style="margin-top: 20px;">
                     <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                     <button class="btn btn-success update-received-btn"
                        data-scan-id="<?= htmlspecialchars($document->scan_id); ?>">Save</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php else: ?>
   <p class="text-danger text-center">Document not found.</p>
<?php endif; ?>