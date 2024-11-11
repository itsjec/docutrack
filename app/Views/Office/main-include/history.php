<style>
  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 12px; /* Oval shape */
    text-transform: lowercase;
    text-align: center;
    background-color: purple; /* Default color */
    color: #fff; /* Default font color */
  }

  .status-pending {
    background-color: #ffc107; /* Yellow */
    color: #212529; /* Dark text for better readability */
  }

  .status-received {
    background-color: #17a2b8; /* Teal */
    color: #fff; /* White text for better readability */
  }

  .status-on-process {
    background-color: #007bff; /* Blue */
    color: #fff; /* White text for better readability */
  }

  .status-completed {
    background-color: #28a745; /* Green */
    color: #fff; /* White text for better readability */
  }

  .status-deleted {
    background-color: #dc3545; /* Red */
    color: #fff; /* White text for better readability */
  }
</style>

<div class="content-wrapper">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Deleted Documents</h4>
                            <p class="card-description">Track and manage documents.</p>
                        </div>
                    </div>
                <div class="table-responsive pt-3">
                <table class="table table-striped project-orders-table" id="history">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Title</th>
                            <th>Sender</th>
                            <th>Current Office</th>
                            <th>Completed By</th>
                            <th>Date Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= $document->tracking_number ?></td>
                                <td><?= $document->title ?></td>
                                <td>
                                    <?php if ($document->sender_office_id !== null): ?>
                                        <?= $senderDetails[$document->document_id]['sender_office'] ?>
                                    <?php else: ?>
                                        <?= $senderDetails[$document->document_id]['sender_user'] ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $document->recipient_office_name ?></td>
                                <td><?= $completedByDetails[$document->document_id] ?></td>
                                <td><?= date('F j, Y', strtotime($document->date_completed)) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary view-btn"
                                        data-toggle="modal" data-target="#viewDocumentModal"
                                        data-documentid="<?= esc($document->document_id) ?>"
                                        data-title="<?= esc($document->title) ?>"
                                        data-tracking-number="<?= esc($document->tracking_number) ?>">View
                                        <i class="typcn typcn-eye btn-icon-append"></i>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Document Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div id="qrCodeContainer"></div>
                        <h4><strong><span id="view-tracking-number"></span></strong></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
