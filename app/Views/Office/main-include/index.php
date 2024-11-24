<style>
  #qrCodeContainer img {
    width: 250px; /* Adjust this to make the QR code larger */
    height: 250px; /* Adjust the height accordingly */
}

</style>

<div class="content-wrapper">
  <div class="row">
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">Pending Documents</p>
              <h1 class="mb-0"><?= $pending_documents_count ?></h1>
            </div>
            <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">On Process Documents</p>
              <h1 class="mb-0"><?= $received_documents_count ?></h1>
            </div>
            <i class="typcn typcn-chart-pie icon-xl text-secondary"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">Total Documents</p>
              <h1 class="mb-0"><?= $total_documents_count ?></h1>
            </div>
            <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="table-responsive pt-3">
          <table class="table table-striped project-orders-table" id="index">
            <thead>
              <tr>
                <th>Tracking Number</th>
                <th>Title</th>
                <th>Sender</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (is_array($documents) && !empty($documents)): ?>
                <?php foreach ($documents as $document): ?>
                  <tr>
                    <td><?= $document->tracking_number ?></td>
                    <td><?= $document->title ?></td>
                    <td><?= $document->sender ?></td>
                    <td><?= $document->status ?></td>
                    <td><?= $document->action ?></td>
                    <td>
                      <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-primary view-btn"
                                data-toggle="modal"
                                data-target="#viewDocumentModal"
                                data-tracking-number="<?= esc($document->tracking_number) ?>"
                        >
                          View
                          <i class="typcn typcn-eye btn-icon-append"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6">No documents found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDocumentModalLabel">Document Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div id="qrCodeContainer"></div>
                        <h4><strong><span id="view-tracking-number"></span></strong></h4>
                        <!-- You can add more document details here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script>
  $(document).ready(function () {
    // View Document Modal: Populate data on open
    $('#viewDocumentModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var trackingNumber = button.data('tracking-number');
      var title = button.data('title');
      var sender = button.data('sender');
      var status = button.data('status');
      var action = button.data('action');
      
      var modal = $(this);
      modal.find('#view-tracking-number').text(trackingNumber);
      
      // Clear QR code container and generate a new QR code
      modal.find('#qrCodeContainer').html('');
      var url = '<?= base_url('/track?number=') ?>' + encodeURIComponent(trackingNumber);
      
      // Generate QR Code
      var qrCode = new QRCode(document.getElementById("qrCodeContainer"), {
        text: url,
        width: 128,
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
    });
  });
</script>
