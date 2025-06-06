<?php
$scan_id = $this->uri->segment(2);
$doc_type_id = $this->uri->segment(3);
?>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Record Detail - <?php echo $this->customlib->getDocType($doc_type_id); ?>
                            <?php
                            $document_name = $this->customlib->getDocumentName($scan_id);
                            if (!empty($document_name) || $document_name != null) {
                                echo " - (" . $document_name . ")";
                            }
                            ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="javascript:void(0);" onclick="window.close();" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="tabs-container">
                        <div class="tabs active-tab" id="invoice-tab">Basic Details</div>
                        <div class="tabs" id="additional-info-tab">Additional Information</div>
                    </div>
                    <div id="invoice-content">
                        <?php $this->load->view('records/view_detail'); ?>
                    </div>
                    <div id="additional-info-content" style="display: none;">
                        <div class="box-body">
                            <h4>Additional Information</h4>
                            
                            <!-- Document Details Section -->
                            <div class="document-details">
                                <h5>Document Details</h5>
                                <dl class="dl-horizontal">
                                    <dt>Document Number</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['document_no']); ?></dd>
                                    <dt>Document Date</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['document_date']); ?></dd>
                                    <dt>Business Entity</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['business_entity_name']); ?></dd>
                                    <dt>Narration</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['narration']); ?></dd>
                                    <dt>TDS Applicable</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['tds_applicable']); ?></dd>
                                    <dt>Total Amount</dt>
                                    <dd><?php echo number_format($add_file_detail['total_amount'], 2); ?></dd>
                                    <dt>Created At</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['created_at']); ?></dd>
                                    <dt>Updated At</dt>
                                    <dd><?php echo htmlspecialchars($add_file_detail['updated_at']); ?></dd>
                                </dl>
                            </div>

                            <!-- Items Table -->
                            <h5>Item Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="items_table">
                                    <thead>
                                        <tr>
                                            <th>Reverse Charge</th>
                                            <th>Department</th>
                                            <th>Cost/Sub</th>
                                            <th>Business Unit</th>
                                            <th>Activity</th>
                                            <th>Location</th>
                                            <th>State</th>
                                            <th>Category</th>
                                            <th>Crop</th>
                                            <th>Region</th>
                                            <th>Function</th>
                                            <th>Vertical</th>
                                            <th>Sub Department</th>
                                            <th>Zone</th>
                                            <th>Debit A/C</th>
                                            <th>Credit A/C</th>
                                            <th>Amount</th>
                                            <th>Payment Term</th>
                                            <th>Reference</th>
                                            <th>Remark</th>
                                            <th>TDS Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($add_file_detail['items'])): ?>
                                            <?php foreach ($add_file_detail['items'] as $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['reverse_charge']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['department_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['cost_center_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['business_unit_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['activity_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['location_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['state_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['crop_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['region_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['function_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['vertical_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['sub_department_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['zone_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['debit_account']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['credit_account']); ?></td>
                                                    <td><?php echo number_format($item['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($item['payment_term']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['reference']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['remark']); ?></td>
                                                    <td><?php echo number_format($item['tds_amount'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="21" class="text-center">No additional information available.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const invoiceTab = document.getElementById('invoice-tab');
        const additionalInfoTab = document.getElementById('additional-info-tab');
        const invoiceContent = document.getElementById('invoice-content');
        const additionalInfoContent = document.getElementById('additional-info-content');

        invoiceTab.addEventListener('click', function () {
            invoiceTab.classList.add('active-tab');
            additionalInfoTab.classList.remove('active-tab');
            invoiceContent.style.display = 'block';
            additionalInfoContent.style.display = 'none';
        });

        additionalInfoTab.addEventListener('click', function () {
            additionalInfoTab.classList.add('active-tab');
            invoiceTab.classList.remove('active-tab');
            additionalInfoContent.style.display = 'block';
            invoiceContent.style.display = 'none';
        });
    });
</script>

<style>
    .tabs-container {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .tabs {
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
        color: #333;
    }

    .tabs.active-tab {
        border-bottom: 2px solid #007bff;
        color: #007bff;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: left;
    }

    .document-details {
        margin-bottom: 30px;
    }

    .dl-horizontal dt {
        float: left;
        width: 160px;
        overflow: hidden;
        clear: left;
        text-align: right;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: bold;
    }

    .dl-horizontal dd {
        margin-left: 180px;
        margin-bottom: 10px;
    }
</style>