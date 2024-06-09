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
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- endinject -->
</head>
<body>
  <div class="container-scroller">
  <?php include ('include/partialnavbar.php'); ?>
      <!-- partial:partials/_settings-panel.html -->
      <div class="container-fluid page-body-wrapper">
        <div class="theme-setting-wrapper">
        <?php include ('include/setting.php'); ?>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <?php include ('include/partialsidebar.php'); ?>
        </nav>
        <!-- partial -->
        <div class="main-panel">
        <?php include ('main-include/index.php'); ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
</div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>


  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get current date
    const currentDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();
    
    // Update the date in the element
    document.getElementById("currentDateText").textContent = formattedDate;

    // Add click event listener to the calendar icon
    document.getElementById("calendarIcon").addEventListener("click", function() {
      // Handle calendar icon click event here
      alert("Calendar icon clicked!");
    });
  });
  $(document).ready(function() {
    $('.view-btn').click(function(event) {
        event.preventDefault();

        var documentId = $(this).data('documentid');
        var title = $(this).data('title');
        var senderName = $(this).data('sendername') || "Office ID " + $(this).data('sender-office-id');
        var recipientOfficeId = $(this).data('recipient-office-id');
        var classification = $(this).data('classification');
        var subClassification = $(this).data('sub-classification');
        var dateOfDocument = $(this).data('date-of-document');
        var action = $(this).data('action');
        var description = $(this).data('description');
        var trackingNumber = $(this).data('tracking-number');

        $('#view-document-id').text(documentId);
        $('#view-title').text(title);
        $('#view-sender').text(senderName);
        $('#view-recipient-office-id').text(recipientOfficeId);
        $('#view-classification').text(classification);
        $('#view-sub-classification').text(subClassification);
        $('#view-date-of-document').text(dateOfDocument);
        $('#view-action').text(action);
        $('#view-description').text(description);
        $('#view-tracking-number').text(trackingNumber);
        $('#qrCodeContainer').empty(); // Clear existing QR code
        var qrcode = new QRCode(document.getElementById("qrCodeContainer"), {
            text: trackingNumber,
            width: 200,
            height: 200
        });
        $('#viewDocumentModal').modal('show');
    });
});


</script>

  <!-- base:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

