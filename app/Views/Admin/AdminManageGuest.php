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
        <?php include ('main-include/manageguest.php'); ?>
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
   
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <script>
    let table = new DataTable('#myClientTable', {
        "paging": true,          
        "pageLength": 5,         
        "lengthMenu": [5],       
        "info": true,            
        "lengthChange": false,
        "searching": false,   
    });
</script>

<script>
$('#addOfficeBtn').click(function () {
    var officeName = $('#officeName').val();
    $('#addOfficeModal').modal('hide');
});

    document.getElementById('password').addEventListener('input', function () {
        var password = document.getElementById('password').value;
        var result = zxcvbn(password);
        var meter = document.getElementById('password-strength-meter');

        meter.value = result.score;
        switch (result.score) {
            case 0:
                meter.style.color = 'red';
                break;
            case 1:
                meter.style.color = 'orange';
                break;
            case 2:
                meter.style.color = 'yellow';
                break;
            case 3:
                meter.style.color = 'green';
                break;
            case 4:
                meter.style.color = 'darkgreen';
                break;
        }
    });

    $(document).ready(function () {
    var userIdToDeactivate;

    $('.deactivate-btn').click(function () {
        userIdToDeactivate = $(this).data('userid');
        $('#deactivateUserModal').modal('show');
    });

    $('#confirmDeactivate').click(function () {
        $.ajax({
            url: 'deactivateUser',
            method: 'POST',
            data: { userId: userIdToDeactivate },
            success: function (response) {
                console.log('User deactivated successfully');
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error('Error deactivating user:', error);
            }
        });

        $('#deactivateUserModal').modal('hide');
    });
});





</script>
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

