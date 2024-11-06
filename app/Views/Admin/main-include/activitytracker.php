<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Office Documents</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px; 
            text-align: center;
            text-transform: lowercase;
            color: #fff;
        }

        .status-received {
            color: #fff; 
        }

        .status-processed {
            background-color: #007bff; 
            color: #fff; 
        }

        .status-completed {
            background-color: #28a745; /* Green */
            color: #fff; /* White text for better readability */
        }

        .details-row {
            display: none; /* Hidden by default */
            background-color: #f8f9fa; /* Light gray background */
            padding: 15px; /* Padding for the details section */
            border-radius: 5px; /* Rounded corners */
            margin-top: 10px; /* Margin for separation */
        }

        .office-name {
            font-weight: bold;
            font-size: 1.5em;
            margin-bottom: 10px; /* Space below office name */
        }

        .progress-card {
            background-color: #ffffff; /* White background for cards */
            border: 1px solid #dee2e6; /* Light gray border */
            border-radius: 5px; /* Rounded corners */
            padding: 15px; /* Padding inside the card */
            margin-bottom: 10px; /* Space between cards */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Subtle shadow */
            display: flex; 
            align-items: center; 
        }

        .progress-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px; 
        }

        .progress-5min { background-color: #28a745; } 
        .progress-15min { background-color: #17a2b8; } 
        .progress-30min { background-color: #ffc107; } 

        .details-row h5 {
            margin-bottom: 5px;
        }

        .details-row p {
            margin: 0; 
        }

        .clickable-row {
            cursor: pointer; 
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            /* Oval shape */
            text-align: center;
            background-color: purple;
            text-transform: lowercase;
            color: #fff;
            /* Default font color */
        }

        .status-pending {
            background-color: #ffc107;
            /* Yellow */
            color: #212529;
            /* Dark text for better readability */
        }

        .status-received {
            background-color: #17a2b8;
            /* Teal */
            color: #fff;
            /* White text for better readability */
        }

        .status-on-process {
            background-color: #007bff;
            /* Blue */
            color: #fff;
            /* White text for better readability */
        }

        .status-completed {
            background-color: #28a745;
            /* Green */
            color: #fff;
            /* White text for better readability */
        }

        .status-deleted {
            background-color: #dc3545;
            /* Red */
            color: #fff;
            /* White text for better readability */
        }
    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Activity Tracker</h4>
                            <p class="card-description">Track your departments time processing to improve workflow efficiency.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Tracking Number</th>
                                    <th>Sender</th> 
                                    <th>Status</th>
                                    <th>Date of Document</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr class="clickable-row" data-target="#details-<?= htmlspecialchars($document['document_id']) ?>">
                                        <td><?= htmlspecialchars($document['title']) ?></td>
                                        <td><?= htmlspecialchars($document['tracking_number']) ?></td>
                                        <td>
                                            <?php
                                            if (!empty($document['sender_office_id'])) {
                                                echo htmlspecialchars($document['sender_office_name']);
                                            } elseif (!empty($document['sender_id'])) {
                                                echo htmlspecialchars($document['first_name'] . ' ' . $document['last_name']);
                                            } else {
                                                echo "No sender information available";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= strtolower($document['status']) ?>">
                                                <?= htmlspecialchars($document['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('F d, Y', strtotime($document['date_of_document'])) ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info view-btn" 
                                               data-document-id="<?= htmlspecialchars($document['document_id']) ?>" 
                                               data-title="<?= htmlspecialchars($document['title']) ?>" 
                                               data-tracking-number="<?= htmlspecialchars($document['tracking_number']) ?>" 
                                               data-sender-id="<?= htmlspecialchars($document['sender_id'] ?? '') ?>" 
                                               data-sender-name="<?= htmlspecialchars(!empty($document['sender_office_id']) ? $document['sender_office_name'] : $document['first_name'] . ' ' . $document['last_name']) ?>">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <!-- Document Details Row -->
                                <tr class="details-row" id="details-<?= htmlspecialchars($document['document_id']) ?>">
                                    <td colspan="6">
                                        <div>
                                            
                                            <h4>Processing Report</h4>
                                            <hr>
                                            <?php if (isset($document['history']) && !empty($document['history'])): ?>
                                                <?php foreach ($document['history'] as $officeName => $records): ?>
                                                    <h5><?= htmlspecialchars($officeName) ?></h5> <!-- Display office name -->
                                                    <?php 
                                                    $receivedDate = null; // Initialize variable for received date
                                                    foreach ($records as $record):
                                                        // Set the received date if the status is received
                                                        if ($record['status'] === 'received') {
                                                            $receivedDate = $record['date_changed']; // Store the date_changed for received
                                                        }

                                                        // Initialize processing time
                                                        $processingTime = 0;  
                                                        $status = htmlspecialchars($record['status']);
                                                        $dateChangedTimestamp = strtotime($record['date_changed']); // Convert date changed to timestamp
                                                        $dateOfDocument = strtotime($document['date_of_document']);
                                                        
                                                // Calculate Processing Time
                                                        if ($status === 'received') {
                                                            $processingTime = $dateChangedTimestamp - $dateOfDocument; // Time from document creation to received
                                                        } 
                                                        elseif ($status === 'on process' && isset($receivedDate)) {
                                                            $receivedDateTimestamp = strtotime($receivedDate); // Use the stored received date
                                                            $processingTime = $dateChangedTimestamp - $receivedDateTimestamp; // Time from received to on process
                                                        } 
                                                        elseif ($status === 'completed') {
                                                            $onProcessDate = null; // Initialize variable to find the last on process date
                                                            foreach ($records as $historyRecord) {
                                                                if ($historyRecord['status'] === 'on process') {
                                                                    $onProcessDate = $historyRecord['date_changed'];
                                                                    break; // Exit loop after finding the first "on process"
                                                                }
                                                            }
                                                            if ($onProcessDate) { // Ensure there's a valid on process date
                                                                $dateCompleted = strtotime($document['date_completed']);
                                                                $onProcessTimestamp = strtotime($onProcessDate);
                                                                $processingTime = $dateCompleted - $onProcessTimestamp; // Time from on process to completed
                                                            }
                                                        }

                                                        // Format processing time
                                                        $days = floor($processingTime / (60 * 60 * 24));
                                                        $hours = floor(($processingTime % (60 * 60 * 24)) / (60 * 60));
                                                        $minutes = floor(($processingTime % (60 * 60)) / 60);
                                                        $formattedTime = "{$days} days, {$hours} hours, {$minutes} minutes";
                                                        ?>

                                                        <div>
                                                            <strong><?= $status ?></strong>
                                                        </div>
                                                        <div class="progress-card">
                                                            <div class="progress-circle 
                                                                <?= htmlspecialchars($status) === 'received' ? 'progress-5min' : (htmlspecialchars($status) === 'on process' ? 'progress-15min' : 'progress-30min') ?>">
                                                            </div>
                                                            <p>
                                                                <?php
                                                                $firstName = htmlspecialchars($record['modified_first_name']);
                                                                $lastName = htmlspecialchars($record['modified_last_name']);
                                                                $dateChanged = date('h:i A', strtotime($record['date_changed'])) . ' on ' . date('F d, Y', strtotime($record['date_changed']));
                                                                ?>

                                                                <strong><?= htmlspecialchars($status) ?></strong>
                                                                <br>
                                                                Additional Details: <?= "{$firstName} {$lastName} {$status} the document at {$dateChanged}." ?>
                                                                <br>
                                                                Processing Time: <?= $formattedTime ?>
                                                            </p>
                                                        </div>
                                                        <hr> <!-- Adding a separator between statuses -->
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>No history available for this document.</p>
                                            <?php endif; ?>
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


<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function () {
            const detailsRow = document.querySelector(this.getAttribute('data-target'));
            detailsRow.style.display = detailsRow.style.display === 'none' || detailsRow.style.display === '' ? 'table-row' : 'none';
        });
    });
</script>

</body>
</html>
