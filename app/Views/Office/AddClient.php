<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- base:css -->
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  
  <!-- plugin css for this page -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        <?php include ('main-include/addclientdocument.php'); ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
</div>

  <!-- Include jQuery and Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // DataTable Initialization
    let table = new DataTable('#addclient', {
        paging: true,          
        pageLength: 5,         
        lengthMenu: [5],       
        info: true,            
        lengthChange: false,
        searching: false,
    });
    // Set current date in an element
    document.addEventListener("DOMContentLoaded", function() {
      const currentDate = new Date();
      const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();
      document.getElementById("currentDateText").textContent = formattedDate;

      // Handle calendar icon click
      document.getElementById("calendarIcon").addEventListener("click", function() {
        alert("Calendar icon clicked!");
      });
    });
  </script>

<script>
$(document).ready(function() {
    $('#classification').change(function() {
        var classification = $(this).val();
        var subClassificationSelect = $('#sub-classification');

        subClassificationSelect.html('<option value="" disabled selected>Loading...</option>');

        $.ajax({
            url: '<?= site_url('documents/getSubClassifications') ?>',
            type: 'POST',
            dataType: 'json',
            data: { classification: classification },
            success: function(response) {
                subClassificationSelect.html('<option value="" disabled selected>Select Sub-Classification</option>');
                $.each(response, function(index, subClassification) {
                    subClassificationSelect.append('<option value="' + subClassification.sub_classification + '">' + subClassification.sub_classification + '</option>');
                });
            },
            error: function() {
                subClassificationSelect.html('<option value="" disabled selected>Error loading sub-classifications</option>');
            }
        });
    });
});

    $(document).ready(function() {
        function displaySuccessModal(trackingNumber) {
            $('#trackingNumber').text(trackingNumber);
            $('#successModal').modal('show');
        }
    });
    </script>
</body>
</html>
