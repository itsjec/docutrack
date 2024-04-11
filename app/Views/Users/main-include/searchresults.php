
    <div class="banner">
            <div class="content">
                <h1>Document: <?= $document['tracking_number'] ?></h1>
                <div class="row mb-3">
                    <div class="col-sm-4 mb-2">
                        <div class="bg-secondary p-4 text-dark text-center"><span class="font-weight-semibold mr-2">Received by:</span><?= $office['office_name'] ?></div>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div class="bg-secondary p-4 text-dark text-center"><span class="font-weight-semibold mr-2">Status:</span><?= $document['status'] ?></div>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div class="bg-secondary p-4 text-dark text-center"><span class="font-weight-semibold mr-2">Expected date:</span><?= $document['deadline'] ?></div>
                    </div>
                </div>

                <div class="steps">
                    <div class="steps-header">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 
                            <?php 
                            if($document['status'] == 'pending') { 
                                echo '25%'; 
                            } elseif($document['status'] == 'received') { 
                                echo '50%'; 
                            } elseif($document['status'] == 'on process') { 
                                echo '75%'; 
                            } elseif($document['status'] == 'completed') { 
                                echo '100%'; 
                            } ?>;" aria-valuenow="<?= $progressPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="steps-body">
                        <div class="step <?= $document['status'] == 'pending' ? 'step-active' : '' ?>">
                            <span class="step-indicator"><i class="fas fa-check fa"></i></span>
                            <span class="step-icon <?= $document['status'] != 'pending' ? 'step-icon-done' : '' ?>"><i class="fas fa-file-signature fa-3x"></i></span>
                            <div class="step-text">Document Registered</div>
                        </div>
                        <div class="step <?= $document['status'] == 'received' ? 'step-active' : ($document['status'] == 'received' ? 'step-completed' : '') ?>">
                            <span class="step-indicator"><i class="fas fa-check fa"></i></span>
                            <span class="step-icon <?= $document['status'] != 'pending' ? 'step-icon-done' : '' ?>"><i class="fas fa-inbox fa-3x"></i></span>
                            <div class="step-text">Document Received</div>
                        </div>
                        <div class="step <?= $document['status'] == 'on process' ? 'step-active' : '' ?>">
                            <span class="step-indicator"><i class="fas fa-check fa"></i></span>
                            <span class="step-icon <?= $document['status'] != 'pending' ? 'step-icon-done' : '' ?>"><i class="fas fa-cog fa-3x"></i></span>
                            <div class="step-text">Processing Document</div>
                        </div>
                        <div class="step <?= $document['status'] == 'completed' ? 'step-active' : '' ?>">
                            <span class="step-indicator"><i class="fas fa-check fa"></i></span>
                            <span class="step-icon <?= $document['status'] != 'pending' ? 'step-icon-done' : '' ?>"><i class="fas fa-check-circle fa-3x"></i></span>
                            <div class="step-text">Document Finished</div>
                        </div>
                    </div>
                </div>

                <div class="d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-4">
                    <div class="custom-control custom-checkbox mt-2 mr-3">
                        <input class="custom-control-input" type="checkbox" id="notify-me" checked>
                    </div>
                    <a class="btn btn-primary btn-sm mt-2" href="viewdetails?tracking_number=<?= $document['tracking_number'] ?>" data-toggle="modal">View Document Details</a>
                </div>

            </div>
        </div>