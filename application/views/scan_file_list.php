<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan File Search Results</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Scan File Search</h2>
        <form action="<?php echo base_url('ScanFileController/getScanFileList'); ?>" method="get" class="row g-3 mb-5">
            <div class="col-md-8">
                <input type="text" name="file" id="file" class="form-control" placeholder="Enter file name" value="<?php echo isset($fileName) ? $fileName : ''; ?>" required>
            </div>
            <div class="col-md-4 d-flex justify-content-between gap-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
        <?php if (!empty($success_message)): ?>
            <div style="color: green; font-weight: bold;">
                <?php echo $success_message; ?>
            </div>
            <br>
        <?php endif; ?>


        <?php if (!empty($results)): ?>
            <?php foreach ($results as $table => $rows): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo $table; ?> Table</h5>
                        <button class="btn btn-danger btn-sm" onclick="deleteAllRowsForTable('<?php echo $table; ?>', '<?php echo $rows[0]['scan_id']; ?>')">Delete Rows</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <?php
                                    $columns = array_keys($rows[0]);
                                    foreach ($columns as $column) {
                                        echo "<th>" . ucfirst($column) . "</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <?php foreach ($columns as $column): ?>
                                            <td><?php echo $row[$column]; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No results found. Please search again.</div>
        <?php endif; ?>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function deleteRow() {
            if (confirm("Are you sure you want to delete this row?")) {
                window.location.href = "<?php echo base_url('ScanFileController/deleteAllRows/'); ?>";
            }
        }

        function deleteAllRowsForTable(table, scanId) {
            if (confirm("Are you sure you want to delete all rows for the " + table + " table?")) {
                window.location.href = "<?php echo base_url('ScanFileController/deleteTableAllRow/'); ?>" + table + "/" + scanId;
            }
        }
    </script>

</body>
</html>
