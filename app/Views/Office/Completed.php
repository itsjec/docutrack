<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PolluxUI Admin</title>
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
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
  <?php include ('include\partialnavbar.php'); ?>
      <!-- partial:partials/_settings-panel.html -->
      <div class="container-fluid page-body-wrapper">
        <div class="theme-setting-wrapper">
        <?php include ('include\setting.php'); ?>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <?php include ('include\partialsidebar.php'); ?>
        </nav>
        <!-- partial -->
        <div class="main-panel">
        <?php include ('main-include\completed.php'); ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
</div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
    $('.delete-btn').on('click', function() {
        var documentId = $(this).data('document-id');
        $('#confirmDeleteBtn').data('document-id', documentId);
    });

    $('#confirmDeleteBtn').on('click', function() {
        var documentId = $(this).data('document-id');
        $.ajax({
            url: '<?= site_url('documents/deleteDocument') ?>',
            method: 'POST',
            data: { document_id: documentId },
            success: function(response) {
                // Handle success response
                $('#deleteDocumentModal').modal('hide');
                // Refresh the page or update the table using JavaScript
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
});

$(document).ready(function() {
    $('.btn-send-out').on('click', function() {
        var documentId = $(this).data('document-id');
        $('#document_id').val(documentId);
    });

    $('#confirmSendOutBtn').on('click', function() {
        if (confirm('Are you sure you want to send out this document?')) {
            $.ajax({
                url: '<?= site_url('documents/sendOutDocument') ?>',
                type: 'POST',
                data: $('#sendOutForm').serialize(),
                success: function(response) {
                    alert(response); // Display success message
                    location.reload(); // Reload the page
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log the error message
                    alert('Error sending out document. Please try again.'); // Display error message
                }
            });
        }
    });
});


    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





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

