<div id="particles-js"></div>

<div class="banner">
    <div class="container">
        <h2>Document Tracking Information</h2>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div id="tracking-pre"></div>
                <div id="tracking">
                    <div class="text-center tracking-status-intransit">
                        <p class="tracking-status text-tight"><?= $title ?></p>
                    </div>

                    <div class="tracking-list">
                        <?php if (empty($workflow_history)): ?>
                            <div class="tracking-item">
                                <p style="color: white;">No activity to be displayed here.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($workflow_history as $workflow): ?>
                                <div class="tracking-item">
                                    <div class="tracking-icon status-intransit">
                                        <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                        </svg>
                                    </div>
                                    <div class="tracking-date"><?= date('F j, Y', strtotime($workflow['date_changed'])) ?><span><?= date('g:i A', strtotime($workflow['date_changed'])) ?></span></div>
                                    <div class="tracking-content">
                                        <?php
                                        $userId = $workflow['user_id'] ?? null;
                                        $user = $admins[$userId] ?? null;
                                        $userOfficeId = $user ? $user['office_id'] : null;
                                        $office = $userOfficeId && isset($offices[$userOfficeId]) ? $offices[$userOfficeId] : 'Unknown Office';
                                        

                                        // Format the date and time
                                        $dateChanged = isset($workflow['date_changed']) ? date('F j, Y g:i A', strtotime($workflow['date_changed'])) : 'Unknown Date';
                                        ?>
                                        <p>
                                            Your Document with Tracking Number <?= $tracking_number ?> was 
                                            <?= $workflow['status'] ?> by 
                                            <?= $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Unknown User' ?> from 
                                            <?= $office ? $office['office_name'] : 'Unknown Office' ?> 
                                            on <?= $dateChanged ?>.
                                        </p>
                                    </div>
                                    <!-- Line Separator -->
                                    <hr class="tracking-separator">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-4">
                    <div class="custom-control custom-checkbox mt-2 mr-3">
                        <input class="custom-control-input" type="checkbox" id="notify-me" checked>
                        <a class="btn btn-primary" href="adminkiosk" data-toggle="modal" style="background: linear-gradient(45deg, #9a2db9, #b715a6); border: none">Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-separator {
        border: 0;
        border-top: 1px solid #ddd;
        margin: 20px 0;
    }
</style>
