<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Base CSS -->
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <div class="container-scroller">
        <?php include('include/partialnavbar.php'); ?>
        <!-- partial:partials/_settings-panel.html -->
        <div class="container-fluid page-body-wrapper">
            <div class="theme-setting-wrapper">
                <?php include('include/setting.php'); ?>
                <!-- partial -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <?php include('include/partialsidebar.php'); ?>
                </nav>
                <!-- partial -->
                <div class="main-panel">
                <?php include ('main-include/ongoing.php'); ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

  <!-- Script Imports -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <!-- Inline Script -->
  <script>
    let table = new DataTable('#ongoing', {
        paging: true,
        pageLength: 5,
        lengthMenu: [5],
        info: true,
        lengthChange: false,
        searching: false,
    });

    $(document).ready(function() {
        $('.receive-btn').on('click', function() {
            const documentId = $(this).data('document-id');
            const documentTitle = $(this).data('document-title');
            const trackingNumber = $(this).data('tracking-number');

            $('#documentTitle').text(documentTitle);
            $('#trackingNumber').text(trackingNumber);
            $('#confirmReceiveBtn').data('document-id', documentId);
            $('#receiveDocumentModal').modal('show');
        });

        $("#confirmReceiveBtn").click(function(event) {
            event.preventDefault();
            const docId = $(this).data("document-id");
            console.log("Document ID:", docId);
            $.ajax({
                url: 'documents/update-document-completed-status/' + docId + '/completed',
                type: 'POST',
                success: function(response) {
                    console.log(response);
                    $("#receiveDocumentModal").modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#viewDocumentModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const trackingNumber = button.data('tracking-number');
            const modal = $(this);

            modal.find('#view-tracking-number').text(trackingNumber);
            modal.find('#qrCodeContainer').html('');

            const url = '<?= base_url("/track?number=") ?>' + encodeURIComponent(trackingNumber);
            console.log('Generating QR Code for URL:', url);

            $.ajax({
                url: '<?= site_url("generate-qr-code") ?>',
                type: 'POST',
                data: {url: url},
                dataType: 'json',
                success: function(response) {
                    console.log('QR Code Response:', response);
                    if (response.qrCode) {
                        modal.find('#qrCodeContainer').html(response.qrCode);
                    } else {
                        console.error('QR Code not received');
                    }
                },
                error: function(jqXHR, textStatus) {
                    console.error('AJAX Error:', textStatus);
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const currentDate = new Date();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();
            document.getElementById("currentDateText").textContent = formattedDate;

            document.getElementById("calendarIcon").addEventListener("click", function() {
                alert("Calendar icon clicked!");
            });
        });
    });
  </script>

  <!-- Base JS -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <script src="assets/js/dashboard.js"></script>
</body>
</html>
