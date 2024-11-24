<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- base:css -->
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- Single jQuery version -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <!-- inject:css -->
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
                    <?php include('main-include/pending.php'); ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <script>
        // Modal script for 'Receive Document' button
        $(document).ready(function () {
            // Show modal when clicking 'Receive' button
            $('.receive-btn').on('click', function () {
                var documentId = $(this).data('document-id');
                var documentTitle = $(this).data('document-title');
                var trackingNumber = $(this).data('tracking-number');
                var documentAction = $(this).data('action');
                var documentDescription = $(this).data('description');

                // Update modal content
                $('#documentTitle').text(documentTitle);
                $('#trackingNumber').text(trackingNumber);
                $('#documentAction').text(documentAction);
                $('#documentDescription').text(documentDescription);

                $('#confirmReceiveBtn').data('document-id', documentId);
                $('#receiveDocumentModal').modal('show');
            });

            // Confirm receive action
            $("#confirmReceiveBtn").click(function (event) {
                event.preventDefault();

                let docId = $(this).data("document-id");
                console.log("Document ID:", docId);

                $.ajax({
                    url: 'documents/update-document-status/' + docId + '/received',
                    type: 'POST',
                    success: function (response) {
                        console.log(response);
                        $("#receiveDocumentModal").modal('hide');
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // View Document Modal (for QR code generation)
            $('#viewDocumentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var trackingNumber = button.data('tracking-number');
                var modal = $(this);

                modal.find('#view-tracking-number').text(trackingNumber);
                modal.find('#qrCodeContainer').html(''); // Clear QR code container

                var url = '<?= base_url('/track?number=') ?>' + encodeURIComponent(trackingNumber);

                console.log('Generating QR Code for URL:', url);

                // AJAX to generate and display QR code
                $.ajax({
                    url: '<?= site_url('generate-qr-code') ?>',
                    type: 'POST',
                    data: { url: url },
                    dataType: 'json',
                    success: function (response) {
                        console.log('QR Code Response:', response);
                        if (response.qrCode) {
                            modal.find('#qrCodeContainer').html(response.qrCode);
                        } else {
                            console.error('QR Code not received');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                    }
                });
            });

            // DataTable initialization
            let table = new DataTable('#pending', {
                "paging": true,
                "pageLength": 5,
                "lengthMenu": [5],
                "info": true,
                "lengthChange": false,
                "searching": false,
            });

            // Display current date in header
            const currentDate = new Date();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();
            document.getElementById("currentDateText").textContent = formattedDate;

            // Calendar icon click event (example)
            document.getElementById("calendarIcon").addEventListener("click", function () {
                alert("Calendar icon clicked!");
            });
        });
    </script>

    <!-- Custom JS and Bootstrap JS -->
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
