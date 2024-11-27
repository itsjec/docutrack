<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document Management</title>

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
    <script>
  <!-- Modals -->
  <!-- Delete Document Modal -->
  <div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete the document "<span id="documentTitleDelete"></span>" with tracking number "<span id="trackingNumberDelete"></span>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Send Out Document Modal -->
  <div class="modal fade" id="sendOutModal" tabindex="-1" aria-labelledby="sendOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sendOutModalLabel">Send Out Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="sendOutForm">
            <input type="hidden" id="document_id" name="document_id">
            <div class="mb-3">
              <label for="office_id" class="form-label">Recipient Office</label>
              <select id="office_id" class="form-select">
                <option value="">Select an Office</option>
                <!-- Dynamic Options -->
              </select>
            </div>
            <div class="mb-3">
              <label for="action" class="form-label">Action</label>
              <input type="text" id="action" class="form-control">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" class="form-control"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="confirmSendOutBtn" class="btn btn-primary">Send Out</button>
        </div>
      </div>
    </div>
  </div>

  <!-- View Document Modal -->
  <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewDocumentLabel">Document Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Tracking Number: <span id="view-tracking-number"></span></p>
          <div id="qrCodeContainer" class="d-flex justify-content-center"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <script>
    // JavaScript functionality as per the code provided
    $(document).ready(function() {
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
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
          }
        });
      });
    });
  </script>
</body>
</html>
