<!DOCTYPE html>
<html>
<head>
    <title>Scan Files</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .label-success { color: green; }
        .label-danger { color: red; }
    </style>
</head>
<body>
    <h2>Scan File</h2>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>File Name</th>
                <th>Document Name</th>
                <th>Scan Date</th>
                <th>Final Submit</th>
                <?php if ($status == 'rejected') { ?>
                    <th>Reject Remark</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($my_lastest_scan)) { ?>
                <tr>
                    <td colspan="<?= $status == 'rejected' ? 6 : 5 ?>" class="text-center">No Record Found</td>
                </tr>
            <?php } else {
                $count = 1;
                foreach ($my_lastest_scan as $row) { ?>
                    <tr>
                        <td><?= $count++; ?></td>
                        <td><?= $row['file_name'] ?></td>
                        <td><?= $row['document_name'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['temp_scan_date'])); ?></td>
                        <td>
                            <?php if ($row['is_final_submitted'] == 'Y') { ?>
                                <span class="label-success">Yes</span>
                            <?php } else { ?>
                                <span class="label-danger">No</span>
                            <?php } ?>
                        </td>
                        <?php if ($status == 'rejected') { ?>
                            <td><?= $row['temp_scan_reject_remark'] ?></td>
                        <?php } ?>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
</body>
</html>