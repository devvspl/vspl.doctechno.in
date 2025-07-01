<?php if ($document): ?>
   <div class="row">
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
               <iframe src="<?= !empty($document->file_path) ? htmlspecialchars($document->file_path) : ''; ?>"
                  width="100%" height="400px"></iframe>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
               <div class="row">
                  <div class="col-12">
                     <p style="margin-top: 5px;"><strong>Company Name:</strong>
                        <?= htmlspecialchars($document->group_name ?? '-'); ?></p>
                     <p style="margin-top: 5px;"><strong>File Name:</strong>
                        <?= htmlspecialchars($document->document_name ?? '-'); ?></p>
                     <p style="margin-top: 5px;"><strong>Scanned By:</strong>
                        <?= htmlspecialchars($document->scanned_by ?? '-'); ?></p>
                     <p style="margin-top: 5px;"><strong>Scan Date:</strong>
                        <?= htmlspecialchars($document->scan_date ?? '-'); ?></p>
                  </div>
                  <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Document Type:</strong></p>
                     <select class="form-control doc-type select2" id="docType_<?= $document->scan_id; ?>"
                        name="doc_type_id">
                        <option value="" selected>Select Document Type</option>
                        <?php
                        $selectedDocType = $document->doc_type_id ?? '';
                        foreach ($docTypes as $type):
                           $isSelected = (!empty($selectedDocType) && $selectedDocType != 0 && $selectedDocType == $type->type_id) ? 'selected' : '';
                           ?>
                           <option value="<?= $type->type_id; ?>" <?= $isSelected; ?>>
                              <?= htmlspecialchars($type->file_type ?? 'Unknown'); ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Department:</strong></p>
                     <select class="form-control select2 department" id="department_<?= $document->scan_id; ?>"
                        name="department_id">
                        <option value="" selected>Select Department</option>
                        <?php
                        $selectedDepartment = $document->department_id ?? '';
                        foreach ($departments as $dept):
                           $isSelected = ($selectedDepartment == $dept->api_id) ? 'selected' : '';
                           ?>
                           <option value="<?= $dept->api_id; ?>" <?= $isSelected; ?>>
                              <?= htmlspecialchars($dept->department_name ?? 'Unknown'); ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Sub Department:</strong></p>
                     <select class="form-control select2" id="subdepartment_<?= $document->scan_id; ?>"
                        name="sub_department_id">
                        <option value="">Select Subdepartment</option>
                     </select>
                  </div>
                  <!-- <div class="col-md-6">
                     <p style="margin-top: 5px;"><strong>Location:</strong></p>
                     <select class="form-control select2" id="location_<?= $document->scan_id; ?>" name="location_id">
                        <option value="">Select Location</option>
                        <?php
                        $selectedLocation = $document->location_id ?? '';
                        foreach ($locations as $loc):
                           $isSelected = ($selectedLocation == $loc->location_id) ? 'selected' : '';
                           ?>
                           <option value="<?= $loc->location_id; ?>" <?= $isSelected; ?>>
                              <?= htmlspecialchars($loc->location_name ?? 'Unknown'); ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div> -->
                  <div class="col-md-12 text-right mt-3" style="margin-top:20px">
                     <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                     <button class="btn btn-success extract-btn" data-scan-id="<?= $document->scan_id; ?>">Update</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php else: ?>
   <p class="text-danger text-center">Document not found.</p>
<?php endif; ?>