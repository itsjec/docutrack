<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Base CSS -->
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/style.css">
  
  <!-- Plugin CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
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
                <?php include('main-include/completed.php'); ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

  <!-- JS Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // DataTable Initialization
    $(document).ready(function() {
      new DataTable('#completed', {
        paging: true,
        pageLength: 5,
        lengthMenu: [5],
        info: true,
        lengthChange: false,
        searching: false,
      });

      // Delete Document Modal
      $('.delete-btn').on('click', function() {
        const documentId = $(this).data('document-id');
        const documentTitle = $(this).data('document-title');
        const trackingNumber = $(this).data('tracking-number');

        $('#documentTitleDelete').text(documentTitle);
        $('#trackingNumberDelete').text(trackingNumber);
        $('#confirmDeleteBtn').data('document-id', documentId);

        $('#deleteDocumentModal').modal('show');
      });

      $('#confirmDeleteBtn').click(function(event) {
        event.preventDefault();
        const docId = $(this).data('document-id');

        $.ajax({
          url: `documents/update-document-deleted-status/${docId}/deleted`,
          type: 'POST',
          success: function(response) {
            console.log(response);
            $('#deleteDocumentModal').modal('hide');
            location.reload();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
          }
        });
      });

      // Send Out Document Modal
      $('.btn-send-out').on('click', function() {
        const documentId = $(this).data('document-id');
        $('#document_id').val(documentId);
        $('#sendOutModal').modal('show');
      });

      $('#confirmSendOutBtn').click(function() {
        const documentId = $('#document_id').val();
        const recipientId = $('#office_id').val();
        const action = $('#action').val();
        const description = $('#description').val();

        $.ajax({
          url: `<?= site_url('documents/update-document-recipient-and-status') ?>/${documentId}/${recipientId}/pending`,
          type: 'POST',
          data: { action, description },
          success: function(response) {
            console.log(response);
            $('#sendOutModal').modal('hide');
            location.reload();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
          }
        });
      });

      // View Document Modal
      $('#viewDocumentModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const trackingNumber = button.data('tracking-number');
        const modal = $(this);

        modal.find('#view-tracking-number').text(trackingNumber);
        modal.find('#qrCodeContainer').html('');

        const url = '<?= base_url('/track?number=') ?>' + encodeURIComponent(trackingNumber);

        $.ajax({
          url: '<?= site_url('generate-qr-code') ?>',
          type: 'POST',
          data: { url },
          dataType: 'json',
          success: function(response) {
            if (response.qrCode) {
              modal.find('#qrCodeContainer').html(response.qrCode);
            } else {
              console.error('QR Code not received');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
          }
        });
      });

      // Calendar Interaction
      const currentDate = new Date();
      const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      const formattedDate = `${monthNames[currentDate.getMonth()]} ${currentDate.getDate()}`;

      $('#currentDateText').text(formattedDate);

      $('#calendarIcon').click(function() {
        alert('Calendar icon clicked!');
      });
    });
  </script>

  <!-- Additional JS -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
</body>
</html>
