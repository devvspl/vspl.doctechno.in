<div class="content-wrapper py-4">
    <section class="content">
        <div class="row">
            <!-- Table Column -->
            <div class="col-lg-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Bill Approval Summary by Department</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Department</th>
                                    <th>Pending</th>
                                    <th>Approved</th>
                                    <th>Rejected</th>
                                    <th>Total</th>
                                    <th>Progress</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $labels = [];
                                $pendingData = [];
                                $approvedData = [];
                                $rejectedData = [];

                                $totalPending = 0;
                                $totalApproved = 0;
                                $totalRejected = 0;

                                $i = 1;
                                foreach ($dept_summary as $row):
                                    $department = $row->department_name ?: 'Unknown';
                                    $pending = (int) $row->total_pending;
                                    $approved = (int) $row->total_approved;
                                    $rejected = (int) $row->total_rejected;
                                    $total = $approved + $rejected + $pending;
                                    $percent = $total > 0 ? round(($approved / $total) * 100) : 0;

                                    if ($percent >= 80) {
                                        $bar = 'progress-bar-success';
                                        $badge = 'bg-green';
                                    } elseif ($percent >= 50) {
                                        $bar = 'progress-bar-primary';
                                        $badge = 'bg-light-blue';
                                    } elseif ($percent >= 30) {
                                        $bar = 'progress-bar-warning';
                                        $badge = 'bg-yellow';
                                    } else {
                                        $bar = 'progress-bar-danger';
                                        $badge = 'bg-red';
                                    }

                                    // For chart
                                    $labels[] = $department;
                                    $pendingData[] = $pending;
                                    $approvedData[] = $approved;
                                    $rejectedData[] = $rejected;

                                    // Aggregate totals
                                    $totalPending += $pending;
                                    $totalApproved += $approved;
                                    $totalRejected += $rejected;
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?>.</td>
                                        <td><?= $department ?></td>
                                        <td><?= $pending ?></td>
                                        <td><?= $approved ?></td>
                                        <td><?= $rejected ?></td>
                                        <td><?= $total ?></td>
                                        <td>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar <?= $bar ?>" style="width: <?= $percent ?>%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge <?= $badge ?>"><?= $percent ?>%</span></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php
                                // Final summary totals
                                $grandTotal = $totalPending + $totalApproved + $totalRejected;
                                $overallPercent = $grandTotal > 0 ? round(($totalApproved / $grandTotal) * 100) : 0;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-weight: bold;">
                                    <td colspan="2">Total</td>
                                    <td><?= $totalPending ?></td>
                                    <td><?= $totalApproved ?></td>
                                    <td><?= $totalRejected ?></td>
                                    <td><?= $grandTotal ?></td>
                                    <td colspan="2">
                                        <?= $overallPercent ?>% Approved
                                        <div class="progress progress-xs progress-striped active"
                                            style="margin-top: 5px;">
                                            <div class="progress-bar progress-bar-info"
                                                style="width: <?= $overallPercent ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>

            <!-- Chart Column -->
            <div class="col-lg-6 col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Approval vs Rejection by Department</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="departmentChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Equalize box heights after load
    window.addEventListener('load', function () {
        let boxes = document.querySelectorAll('.box');
        let maxHeight = 0;

        boxes.forEach(box => {
            maxHeight = Math.max(maxHeight, box.offsetHeight);
        });

        boxes.forEach(box => {
            box.style.height = maxHeight + 'px';
        });
    });

    // Chart.js: Approval vs Rejection vs Pending
    const ctx = document.getElementById('departmentChart').getContext('2d');
    const departmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Pending',
                    backgroundColor: '#ffc107', // Yellow
                    data: <?= json_encode($pendingData) ?>
                },
                {
                    label: 'Approved',
                    backgroundColor: '#28a745', // Green
                    data: <?= json_encode($approvedData) ?>
                },
                {
                    label: 'Rejected',
                    backgroundColor: '#dc3545', // Red
                    data: <?= json_encode($rejectedData) ?>
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Helps with custom height if needed
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Approval vs Rejection vs Pending by Department'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bills'
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
</script>
