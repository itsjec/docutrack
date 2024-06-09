<div class="banner">
        <div class="container">
            <h2>DOCUMENT TRACKING INFORMATION</h2>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div id="tracking-pre"></div>
                    <div id="tracking">
                    <div class="text-center tracking-status-intransit">
                        <p class="tracking-status text-tight"><?= $tracking_number ?></p>
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
                                    <div class="tracking-date"><?= date('F j, Y', strtotime($workflow['date_changed'])) ?><span><?= date('H:i:s', strtotime($workflow['date_changed'])) ?></span></div>
                                    <div class="tracking-content">
                                        Document with Tracking Number <?= $tracking_number ?> is <?= $workflow['status'] ?> by <?= $admins[$workflow['user_id'] - 1]['first_name'] ?? 'Unknown' ?> <?= $admins[$workflow['user_id'] - 1]['last_name'] ?? 'User' ?>
                                        on <?= date('F j, Y H:i:s', strtotime($workflow['date_changed'])) ?>
                                        <span>Current Office: <?= $office ? $office['office_name'] : 'Unknown Office' ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-4">
                    <div class="custom-control custom-checkbox mt-2 mr-3">
                        <input class="custom-control-input" type="checkbox" id="notify-me" checked>
                        <a class="btn btn-primary" href="indexloggedin" data-toggle="modal" style="background: linear-gradient(45deg, #9a2db9, #b715a6); border: none">Return Home</a>
            </div>
            </div>
        </div>
    </div>
