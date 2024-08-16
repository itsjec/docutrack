<?php
$trackingNumber = $_GET['trackingNumber'] ?? '';

use SimpleSoftwareIO\QrCode\Generator;

$qrCodeURL = '';
if (!empty($trackingNumber)) {
  helper('url'); 
  $qrcode = new Generator;
  $trackingNumber = urlencode($trackingNumber); 
  $url = base_url("/track?number=$trackingNumber"); 
  $qrCodeURL = $qrcode->size(200)->generate($url);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- base:css -->
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
        <div class="row flex-grow">
          <div class="col-lg-7 mx-auto text-white">
            <h3>Document Added Successfully!</h3>
            <h2 id="trackingNumber"><?= $trackingNumber ?></h2>
            <?php if (!empty($qrCodeURL)): ?>
              <div class="qr-code">
                <?= $qrCodeURL ?>
              </div>
            <?php endif; ?>
            <div class="row mt-5">
              <div class="col-12 text-center mt-xl-2">
                <button type="button" class="btn btn-primary" id="copyButton">Copy Tracking Number</button>
                <a class="btn btn-secondary" href="<?= site_url('adddepartmentdocument') ?>">Return Home</a>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 mt-xl-2">
                <p class="text-white font-weight-medium text-center">Copyright &copy; 2018  All rights reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <script>
    document.getElementById('copyButton').addEventListener('click', function() {
      var trackingNumber = document.getElementById('trackingNumber');
      var range = document.createRange();
      range.selectNode(trackingNumber);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);
      document.execCommand('copy');
      window.getSelection().removeAllRanges();
      alert('Tracking Number copied to clipboard');
    });
  </script>
</body>

</html>
