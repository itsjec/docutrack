<div class="content-wrapper">
<div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left" >Pending Documents</p>
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
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
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
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
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
                                            data-document-id="<?= esc($document->document_id) ?>"
                                            data-title="<?= esc($document->title) ?>"
                                            data-sender-id="<?= esc($document->sender_id) ?>"
                                            data-recipient-id="<?= esc($document->recipient_id) ?>"
                                            data-classification="<?= esc($document->classification) ?>"
                                            data-sub-classification="<?= esc($document->sub_classification) ?>"
                                            data-date-of-document="<?= esc($document->date_of_document) ?>"
                                            data-action="<?= esc($document->action) ?>"
                                            data-description="<?= esc($document->description) ?>"
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



