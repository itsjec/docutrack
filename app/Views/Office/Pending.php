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
        <?php include ('main-include/pending.php'); ?>
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
  <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
  <script>
  $(document).ready(function() {
    $('.receive-btn').on('click', function() {
      var documentId = $(this).data('document-id');
      var documentTitle = $(this).data('document-title');
      var trackingNumber = $(this).data('tracking-number');
      var documentAction = $(this).data('action');
      var documentDescription = $(this).data('description');

      $('#documentTitle').text(documentTitle);
      $('#trackingNumber').text(trackingNumber);
      $('#documentAction').text(documentAction);
      $('#documentDescription').text(documentDescription);

      $('#confirmReceiveBtn').data('document-id', documentId);
      $('#receiveDocumentModal').modal('show');
  });

    $("#confirmReceiveBtn").click(function(event) {
        event.preventDefault(); 

        let docId = $(this).data("document-id");
        console.log("Document ID:", docId);
        $.ajax({
            url: 'documents/update-document-status/' + docId + '/received',
            type: 'POST',
            success: function(response) {
                console.log(response);
                $("#receiveDocumentModal").modal('hide');
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    const currentDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();

    document.getElementById("currentDateText").textContent = formattedDate;

    const calendarIcon = document.getElementById("calendarIcon");
    if (calendarIcon) {
        calendarIcon.addEventListener("click", function() {
            alert("Calendar icon clicked!");
        });
    }
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

  <!-- container-scroller -->

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

