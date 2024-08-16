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
            <div class="table-responsive pt-3">
                <table class="table table-striped project-orders-table">
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
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= $document->tracking_number ?></td>
                                <td><?= $document->title ?></td>
                                <td> <?php if ($document->sender_office_id === null): ?>
                                        <?= $senderDetails[$document->document_id]['sender_user'] ?>
                                    <?php else: ?>
                                        <?= $senderDetails[$document->document_id]['sender_office'] ?>
                                    <?php endif; ?></td>
                                    <td>
                                        <span class="status-badge status-<?= htmlspecialchars($document->status, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars(ucfirst($document->status), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        </td>

                                <td><?= $document->action ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3 receive-btn" data-toggle="modal" data-target="#receiveDocumentModal" data-document-id="<?= $document->document_id ?>" data-document-title="<?= $document->title ?>" data-tracking-number="<?= $document->tracking_number ?>" data-action="<?= $document->action ?>" data-description="<?= $document->description ?>">
                                        <span class="btn-icon-prepend">
                                            <i class="typcn typcn-edit"></i>
                                        </span>
                                        Receive
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary view-btn"
                                            data-toggle="modal" 
                                            data-target="#viewDocumentModal"
                                            data-document-id="<?= esc($document->document_id) ?>"
                                            data-title="<?= esc($document->title) ?>"
                                            data-tracking-number="<?= esc($document->tracking_number) ?>"
                                        >
                                            View
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


<!-- Receive Document Modal -->
<div class="modal fade" id="receiveDocumentModal" tabindex="-1" role="dialog" aria-labelledby="receiveDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiveDocumentModalLabel">Receive Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to receive the document entitled <span id="documentTitle"></span>?</p>
                <p style="text-align: center; font-weight: bold; font-size: 1.2em;">Tracking Number: <span id="trackingNumber"></span></p>
                <p style="font-weight: bold;">Action: <span id="documentAction"></span></p>
                <p style="font-weight: bold;">Description: <span id="documentDescription"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmReceiveBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
