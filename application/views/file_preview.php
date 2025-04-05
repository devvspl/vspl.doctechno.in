<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Preview</title>
</head>
<body style="margin:0; padding:0; text-align:center; background:#f4f4f4;">
    <?php if (strpos($mime, "image") !== false): ?>
        <img src="<?php echo $file_url; ?>" style="max-width:100%; height:auto;">
    <?php elseif (strpos($mime, "pdf") !== false): ?>
        <embed src="<?php echo $file_url; ?>" width="100%" height="600px" type="application/pdf">
    <?php elseif (strpos($mime, "text") !== false): ?>
        <iframe src="<?php echo $file_url; ?>" width="100%" height="600px"></iframe>
    <?php else: ?>
        <p>Preview not available. <a href="<?php echo $file_url; ?>" download>Download File</a></p>
    <?php endif; ?>
</body>
</html>
