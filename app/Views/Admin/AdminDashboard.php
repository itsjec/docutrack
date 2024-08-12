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
        <?php include ('main-include/dashboard.php'); ?>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    '#e74a3b', // Color for 'deleted'
                    '#9b59b6'  // Color for 'incoming' (new color)
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

    document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('documentAgingChart').getContext('2d');
    var gradientDots = ctx.createLinearGradient(0, 0, 0, 400); 
    gradientDots.addColorStop(0, '#C36EB8');   
    gradientDots.addColorStop(1, '#C42D9B');   

    var documentLabels = <?= $documentLabels ?>; 
    var documentAgesDays = <?= $documentAgesDays ?>; 
    var documentAgesMonths = <?= $documentAgesMonths ?>;

    var documentAgingChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: documentLabels,
            datasets: [{
                label: 'Document Age (Months)',
                data: documentAgesMonths, 
                backgroundColor: 'rgba(211, 211, 211, 0.5)',
                borderColor: '#000',
                borderWidth: 1,
                pointBackgroundColor: function(context) {
                    var value = context.raw;
                    return gradientDots;
                },
                pointBorderColor: '',
                pointBorderWidth: 1,
                pointRadius: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' months';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' months'; 
                        }
                    }
                }
            }
        }
    });

    document.getElementById('ageUnit').addEventListener('change', function(event) {
        var selectedValue = event.target.value;
        var data, label;

        if (selectedValue === 'months') {
            data = documentAgesMonths;
            label = 'Document Age (Months)';
            documentAgingChart.options.scales.y.ticks.callback = function(value) {
                return value + ' months';
            };
            documentAgingChart.options.plugins.tooltip.callbacks.label = function(context) {
                return context.raw + ' months';
            };
        } else {
            data = documentAgesDays;
            label = 'Document Age (Days)';
            documentAgingChart.options.scales.y.ticks.callback = function(value) {
                return value + ' days';
            };
            documentAgingChart.options.plugins.tooltip.callbacks.label = function(context) {
                return context.raw + ' days';
            };
        }

        documentAgingChart.data.datasets[0].data = data;
        documentAgingChart.data.datasets[0].label = label;
        documentAgingChart.update();
    });
});

    var officeNames = <?= json_encode($officeNames) ?>;
    var averageProcessingTimes = <?= json_encode($averageProcessingTimes) ?>;


    var colorLight = '#C36EB8'; 
    var colorDark = '#C42D9B';  

    var barColors = averageProcessingTimes.map(function(time) {
        return time > 5 ? colorDark : colorLight;
    });

    var ctx = document.getElementById('averageProcessingTimeChart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $officeLabels ?>,
            datasets: [{
                label: 'Average Processing Time (Minutes)',
                data: averageProcessingTimes, 
                backgroundColor: barColors, 
                borderColor: ''
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Average Processing Time (Minutes)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Office'
                    }
                }
            }
        }
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

