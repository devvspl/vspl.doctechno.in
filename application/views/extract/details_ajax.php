<?php if ($document) : ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <iframe src="<?= !empty($document->File_Location) ? htmlspecialchars($document->File_Location) : ''; ?>" width="100%" height="400px"></iframe>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <p><strong>Company Name:</strong> <?= htmlspecialchars($document->group_name ?? '-'); ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($document->location_name ?? '-'); ?></p>
                    <p><strong>File Name:</strong> <?= htmlspecialchars($document->Document_Name ?? '-'); ?></p>
                    <p><strong>Scanned By:</strong> <?= htmlspecialchars($document->Scan_By ?? '-'); ?></p>
                    <p><strong>Scan Date:</strong> <?= htmlspecialchars($document->Scan_Date ?? '-'); ?></p>
                    <p><strong>Bill Approver:</strong> <?= htmlspecialchars($document->Bill_Approver ?? '-'); ?></p>
                    <p><strong>Approval Date:</strong> <?= htmlspecialchars($document->Bill_Approver_Date ?? '-'); ?></p>
                    <p><strong>Document Type:</strong></p>
                    <select class="form-control doc-type select2" id="docType_<?= $document->Scan_Id; ?>">
                        <option value="">Select Document Type</option>
                        <?php 
                        $selectedDocType = $document->DocType_Id ?? ''; 
                        foreach ($docTypes as $type) : 
                            $isSelected = (!empty($selectedDocType) && $selectedDocType != 0 && $selectedDocType == $type->type_id) ? 'selected' : '';
                        ?>
                            <option value="<?= $type->type_id; ?>" <?= $isSelected; ?>>
                                <?= htmlspecialchars($type->file_type ?? 'Unknown'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-right mt-3" style="margin-top: 15px;">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        <button class="btn btn-success extract-btn" data-scan-id="<?= $document->Scan_Id; ?>">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <p class="text-danger text-center">Document not found.</p>
<?php endif; ?>
