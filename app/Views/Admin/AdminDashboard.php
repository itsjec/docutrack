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
        <?php include ('main-include\dashboard.php'); ?>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- base:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->

  <script>
    var ctx1 = document.getElementById('status-chart').getContext('2d');
    var myChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: <?= $statusLabels ?>,
            datasets: [{
                label: 'Number of Documents',
                data: <?= $statusCounts ?>,
                backgroundColor: [
                    '#FFC234', // Color for 'pending'
                    '#36A2EB', // Color for 'on process'
                    '#FF9F40', // Color for 'received'
                    '#4BC0C0', // Color for 'completed'
                    '#e74a3b'  // Color for 'deleted'
                ],
                borderColor: 'transparent',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx2 = document.getElementById('office-chart').getContext('2d');

    // Create gradient
    var gradient = ctx2.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, '#9220b9');
    gradient.addColorStop(1, '#E8078D');

    var officeChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= $officeLabels ?>,
            datasets: [{
                label: 'Number of Documents',
                data: <?= $officeCounts ?>,
                backgroundColor: gradient,
                borderColor: 'transparent',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx3 = document.getElementById('user-chart').getContext('2d');
    var userChart = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: <?= $userLabels ?>,
            datasets: [{
                label: 'Number of Users',
                data: <?= $userCounts ?>,
                backgroundColor: [
                    '#C36EB8', 
                    '#C42D9B' 
                ],
                borderColor: [
                    'transparent',
                    'transparent'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
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

