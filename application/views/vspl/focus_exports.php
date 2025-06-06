<div class="content-wrapper">
    <style>
        .content-wrapper {
            padding: 20px;
        }
        .info-box {
            background-color: #f4f6f9;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }
        .info-box:hover {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .info-box-content {
            padding: 15px;
            text-align: center;
        }
        .info-box-text {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 5px;
        }
        .text-decoration-none {
            text-decoration: none;
        }
        .text-dark {
            color: #343a40;
        }
        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .detail-row table {
            background-color: #f2f2f2 !important;
        }
        .detail-row {
            background-color: #f2f2f2;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            white-space: nowrap;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Scan Punch Report</h3>
                    </div>
                    <form method="get" action="<?php echo site_url('VSPL_Punch/focus_exports'); ?>" id="searchForm" class="row" style="padding: 10px;">
                        <div class="col-md-3">
                            <label for="from_date">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?php echo isset($from_date) ? htmlspecialchars($from_date) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?php echo isset($to_date) ? htmlspecialchars($to_date) : ''; ?>">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary btn-sm mt-3" style="margin-top: 23px;">Search</button>
                            <a href="<?php echo site_url('VSPL_Punch/export_csv?' . http_build_query(['from_date' => $from_date, 'to_date' => $to_date])); ?>" class="btn btn-primary btn-sm mt-3" style="margin-top: 23px;">Export CSV</a>
                        </div>
                    </form>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Focus Export</div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>DocNo</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Business Entity</th>
                                        <th>sNarration</th>
                                        <th>TDSJVNo</th>
                                        <th>ReverseCharge_Yn_</th>
                                        <th>BillNo</th>
                                        <th>BillDate</th>
                                        <th>Department</th>
                                        <th>Cost Center</th>
                                        <th>Business Unit</th>
                                        <th>Activity</th>
                                        <th>Location</th>
                                        <th>State</th>
                                        <th>Category</th>
                                        <th>Crop</th>
                                        <th>Region</th>
                                        <th>Function</th>
                                        <th>FC-Vertical</th>
                                        <th>Sub Department</th>
                                        <th>Zone</th>
                                        <th>DrAccount</th>
                                        <th>CrAccount</th>
                                        <th>Amount</th>
                                        <th>Reference</th>
                                        <th>TDSBillAmount</th>
                                        <th>TDS</th>
                                        <th>TDSPer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($results)): ?>
                                        <?php foreach ($results as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['DocNo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Date'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Time'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Business_Entity'] ?? ''); ?></td>
                                                <td><input  style="width: 160px;" class="form-control form-control-sm" value="<?php echo htmlspecialchars($row['sNarration'] ?? ''); ?>"></td>
                                                <td><?php echo htmlspecialchars($row['TDSJVNo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['ReverseCharge_Yn_'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['BillNo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['BillDate'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Department'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Cost_Center'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Business_Unit'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Activity'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Location'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['State'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Category'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Crop'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Region'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Function'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['FC_Vertical'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Sub_Department'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Zone'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['DrAccount'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['CrAccount'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Amount'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['Reference'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['TDSBillAmount'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['TDS'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['TDSPer'] ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="29" class="text-center">No data available</td>
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