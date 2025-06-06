<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import CSV to Update Focus Code</title>
    <style>
        body {precated: true; font-family: Arial, sans-serif; margin: 50px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="file"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .message { margin-top: 15px; color: green; }
        .error { margin-top: 15px; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Import CSV to Update Focus Code</h2>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="message"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php echo form_open_multipart('PublicController/import', ['id' => 'import_form']); ?>
            <div class="form-group">
                <label for="table_name">Table Name:</label>
                <input type="text" name="table_name" id="table_name" required placeholder="Enter table name (e.g., users)">
            </div>
            <div class="form-group">
                <label for="match_column">Match Column (in Database):</label>
                <input type="text" name="match_column" id="match_column" required placeholder="Enter column to match (e.g., name)">
            </div>
            <div class="form-group">
                <label for="update_column">Update Column (in Database):</label>
                <input type="text" name="update_column" id="update_column" required placeholder="Enter column to update (e.g., focus_code)">
            </div>
            <div class="form-group">
                <label for="csv_file">Upload CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>
            <button type="submit">Import & Update</button>
        <?php echo form_close(); ?>
    </div>
</body>
</html>