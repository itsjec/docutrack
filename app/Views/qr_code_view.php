<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
</head>
<body>
    <h1>Generate QR Code</h1>
    <form method="post" action="<?= site_url('/qr-code/generate'); ?>">
        <input type="text" name="text" placeholder="Enter text for QR code" required>
        <button type="submit">Generate QR Code</button>
    </form>
    <?php if (isset($qrCodeSvg)): ?>
        <div>
            <?= $qrCodeSvg ?>
            <a href="<?= site_url('/qr-code/download'); ?>" download="qr_code.svg">
                <button type="button">Download QR Code</button>
            </a>
        </div>
    <?php endif; ?>
</body>
</html>
