<style>
    #previewModal .modal-body table {
        table-layout: fixed; /* Ensure columns are evenly spaced */
        word-wrap: break-word; /* Break long text to prevent overflow */
        word-break: break-word; /* Break words to fit the space */
    }

    #previewModal .modal-body th, #previewModal .modal-body td {
        text-align: center; /* Align text for better readability */
        padding: 8px; /* Add padding for spacing */
        font-size: 14px; /* Adjust font size for better fit */
        white-space: normal; /* Allow text to wrap properly */
    }

    #previewModal .modal-body td {
        word-break: break-word; /* Ensure long text breaks properly */
        hyphens: auto; /* Allow hyphenation where needed */
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Document Transactions</h4>
                            <p class="card-description">Generate customized reports by applying filters.</p>
                        </div>
                        <div class="col-12 text-right">
                        <form id="reportForm" action="<?= site_url('admin/transactions/download') ?>" method="POST">
                            <div class="row">
                                <!-- Date Range Inputs -->
                                <div class="col-md-3 mb-2">
                                    <input type="date" class="form-control" name="start_date" placeholder="Start Date">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <input type="date" class="form-control" name="end_date" placeholder="End Date">
                                </div>

                                <!-- Department Dropdown -->
                                <div class="col-md-2 mb-2">
                                    <select name="department" id="department" class="form-control">
                                        <?php foreach ($departments as $department): ?>
                                            <option value="<?= $department['office_id'] ?>"><?= $department['office_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Generate Report Button -->
                                <div class="col-md-2 mb-2">
                                <button type="submit" id="generateReport" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </form>

                        </div>
              <!-- Document Table -->
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped project-orders-table">
                            <thead>
                                <tr>
                                    <th>Tracking Number</th>
                                    <th>Title</th>
                                    <th>Sender</th>
                                    <th>Previous Office</th> <!-- Updated column -->
                                    <th>Current Office</th>
                                    <th>Processing Time (minutes)</th>
                                    <th>Date Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= $document->tracking_number ?></td>
                                    <td><?= $document->title ?></td>
                                    <td>
                                        <?php if ($document->sender_office_id === null): ?>
                                            <?= $senderDetails[$document->document_id]['sender_user'] ?>
                                        <?php else: ?>
                                            <?= $senderDetails[$document->document_id]['sender_office'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Display the previous office name -->
                                        <?= $document->completed_office_name ?? 'N/A' ?>
                                    </td>
                                    <td><?= $document->recipient_office_name ?></td>
                                    <td>
                                                <?php
                                                    $time = isset($document->processing_time_minutes) ? $document->processing_time_minutes : 0; 
                                                    $progress = 0;
                                                    $color = 'blue';

                                                    if ($time < 5) {
                                                        $progress = 20;
                                                        $color = 'blue';
                                                    } elseif ($time <= 10) {
                                                        $progress = 40;
                                                        $color = 'green';
                                                    } elseif ($time <= 30) {
                                                        $progress = 60;
                                                        $color = 'yellow';
                                                    } elseif ($time <= 60) {
                                                        $progress = 80;
                                                        $color = 'orange';
                                                    } elseif ($time > 60) {
                                                        $progress = 100;
                                                        $color = 'red';
                                                    }

                                                    $formattedTime = isset($document->formatted_time) ? $document->formatted_time : '0 min';
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2"><?= $formattedTime ?></span> 
                                                    <div class="progress flex-grow-1" style="height: 20px;">
                                                        <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%; background-color: <?= $color ?>;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        <td><?= date('F j, Y', strtotime($document->date_completed)) ?></td>
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
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Report Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeButton" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadButton">Download CSV</button>
                <button type="button" class="btn btn-info" id="printButton">Print</button>
            </div>
        </div>
    </div>
</div>

