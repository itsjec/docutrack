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
        <?php include ('main-include/viewtransactions.php'); ?>
            </div>
        </div>
        <!-- main-panel ends -->
        </div>
</div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
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

  $(document).ready(function() {
    // Report Form Submission
    $('#reportForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = response;

                var table = '<table class="table table-bordered">';
                table += '<thead><tr>';
                table += '<th>Tracking Number</th>';
                table += '<th>Title</th>';
                table += '<th>Sender</th>';
                table += '<th>Current Office</th>';
                table += '<th>Processing Time (minutes)</th>';
                table += '<th>Date Completed</th>';
                table += '</tr></thead>';
                table += '<tbody>';

                for (var i = 0; i < data.length; i++) {
                    var time = data[i].processing_time || 0;
                    var formattedTime = '';

                    if (time >= 1440) {
                        var days = Math.floor(time / 1440);
                        formattedTime = days + ' day' + (days > 1 ? 's' : '');
                    } else if (time >= 60) {
                        var hours = Math.floor(time / 60);
                        formattedTime = hours + ' hr' + (hours > 1 ? 's' : '');
                    } else {
                        formattedTime = time + ' min';
                    }

                    var progress = 0;
                    var color = 'blue';

                    if (time < 5) {
                        progress = 20;
                        color = 'blue';
                    } else if (time <= 10) {
                        progress = 40;
                        color = 'green';
                    } else if (time <= 30) {
                        progress = 60;
                        color = 'yellow';
                    } else if (time <= 60) {
                        progress = 80;
                        color = 'orange';
                    } else if (time > 60) {
                        progress = 100;
                        color = 'red';
                    }

                    var progressHtml = `
                        <div class="d-flex align-items-center">
                            <span class="mr-2">${formattedTime || '0 min'}</span>
                            <div class="progress flex-grow-1" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" style="width: ${progress}%; background-color: ${color};"></div>
                            </div>
                        </div>
                    `;

                    table += '<tr>';
                    table += '<td>' + data[i].tracking_number + '</td>';
                    table += '<td>' + data[i].title + '</td>';
                    table += '<td>' + data[i].sender + '</td>';
                    table += '<td>' + data[i].current_office + '</td>';
                    table += '<td>' + progressHtml + '</td>';
                    table += '<td>' + data[i].date_completed + '</td>';
                    table += '</tr>';
                }

                table += '</tbody></table>';

                $('#previewModal .modal-body').html(table);
                $('#previewModal').modal('show');

                // Handle download button click
                $('#downloadButton').off('click').on('click', function() {
                    window.location.href = 'https://docutrack.calapancityapps.com/admin/transactions/download';
                });

                // Handle print button click
                $('#printButton').off('click').on('click', function() {
                    var printContents = $('#previewModal .modal-body').html();
                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Print Report</title>');
                    printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(printContents);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                });

                // Close modal event handler to reset modal content
                $('#previewModal').on('hidden.bs.modal', function () {
                    $('#previewModal .modal-body').empty(); // Optional: reset the modal body content
                });

            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });

    // Manual close button (if needed)
    $('#closeButton').on('click', function() {
        $('#previewModal').modal('hide'); // This explicitly hides the modal
    });
});


</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <script>
    let table = new DataTable('#dataTable', {
        "paging": true,          
        "pageLength": 5,         
        "lengthMenu": [5],       
        "info": true,            
        "lengthChange": false,
        "searching": false,   
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