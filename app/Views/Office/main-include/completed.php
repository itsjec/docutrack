<style>
  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 12px; /* Oval shape */
    text-transform: lowercase;
    text-align: center;
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
                                <td> <?php if ($document->sender_office_id != null): ?>
                        <?= $senderDetails[$document->document_id]['sender_office'] ?>
                    <?php else: ?>
                        <?= $senderDetails[$document->document_id]['sender_user'] ?>
                    <?php endif; ?></td>
                    <td>
  <span class="status-badge status-<?= htmlspecialchars($document->status, ENT_QUOTES, 'UTF-8') ?>">
    <?= htmlspecialchars(ucfirst($document->status), ENT_QUOTES, 'UTF-8') ?>
  </span>
</td>

                                <td><?= $document->action ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3 btn-send-out"
                                            data-toggle="modal"
                                            data-target="#sendOutModal"
                                            data-document-id="<?= $document->document_id ?>"
                                            data-document-title="<?= $document->title ?>"
                                            data-tracking-number="<?= $document->tracking_number ?>"
                                            onclick="setDocumentId(this)">
                                        Send Out
                                        <i class="typcn typcn-arrow-sorted-down btn-icon-append"></i>
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
                                    
                                        <button type="button" class="btn btn-danger btn-sm btn-icon-text delete-btn" data-toggle="modal" data-target="#deleteDocumentModal" data-document-id="<?= $document->document_id ?>" data-document-title="<?= $document->title ?>" data-tracking-number="<?= $document->tracking_number ?>">
                                            Delete
                                            <i class="typcn typcn-delete-outline btn-icon-append"></i>
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


<!-- Delete Document Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDocumentModalLabel">Delete Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the document entitled <span id="documentTitleDelete"></span>?
                <br>
                Tracking Number: <span id="trackingNumberDelete"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Send Out Document Modal -->
<div class="modal fade" id="sendOutModal" tabindex="-1" role="dialog" aria-labelledby="sendOutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendOutModalLabel">Send Out Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sendOutForm" method="post" action="<?= site_url('documents/sendOutDocument') ?>">
                    <input type="hidden" id="document_id" name="document_id" value="">
                    <input type="hidden" id="recipient_id" name="recipient_id" value="">
                    <div class="form-group">
                        <label for="office_id">Select Office:</label>
                        <select class="form-control" id="office_id" name="office_id" onchange="updateRecipientId()">
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="action">Action:</label>
                        <input type="text" class="form-control" id="action" name="action">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSendOutBtn">Send Out</button>
            </div>
        </div>
    </div>
</div>


