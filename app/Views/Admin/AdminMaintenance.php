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
        <?php include ('main-include/maintenance.php'); ?>
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
        $('#addDocumentBtn').click(function () {
            $('#addDocumentModal').modal('show');
        });

    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <script>
    let table = new DataTable('#maintenance', {
        "paging": true,          
        "pageLength": 5,         
        "lengthMenu": [5],       
        "info": true,            
        "lengthChange": false,
        "searching": false,   
    });
</script>

<script>
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    $("#openModal").click(function() {
        modal.style.display = "block";
    });

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    $("#addForm").submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "sub-classifications/save",
            data: formData,
            success: function(response) {
                alert(response);
                modal.style.display = "none";
            },
            error: function() {
                alert("Error adding subclassification.");
            }
        });
    });

    $(document).ready(function () {
    $('.delete-btn').click(function () {
        var classificationId = $(this).data('office-id');
        $('#deleteOfficeId').val(classificationId);
        $('#deleteOfficeModal').modal('show');
    });
});

$(document).ready(function () {
    $('.edit-subclassification-btn').click(function () {
        var subClassificationId = $(this).data('subclassification-id');
        var classificationName = $(this).data('classification');
        var subClassificationName = $(this).data('subclassification');

        $('#editClassificationId').val(subClassificationId);
        $('#editClassification').val(classificationName);
        $('#editSubclassification').val(subClassificationName);

        $('#editSubClassificationModal').find('#classification option').filter(function() {
            return $(this).text() == classificationName;
        }).prop('selected', true);

        $('#editSubClassificationModal').modal('show');
    });
});



</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

