<div class="content-wrapper">
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                        <p class="mb-2 text-md-center text-lg-left">Overall Document Status</p>
                        <h1 id="total-documents"><?= $totalStatuses ?></h1>
                    </div>
                    <i class="typcn typcn-chart-bar icon-xl text-secondary"></i>
                </div>
                <canvas id="status-chart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                        <p class="mb-2 text-md-center text-lg-left">Documents In Each Offices</p>
                        <h1 id="total-documents"><?= $totalDocuments ?></h1>
                    </div>
                    <i class="typcn typcn-chart-bar icon-xl text-secondary"></i>
                </div>
                <canvas id="office-chart" height="300"></canvas>
            </div>
        </div>
    </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Total Number of Users</p>
                            <h1 class="mb-0"><?= $totalUsers ?></h1>
                        </div>
                        <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
                    </div>
                    <canvas id="user-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Document Aging</p>
                            <select id="ageUnit" class="form-control">
                                <option value="months">Months</option>
                                <option value="days">Days</option>
                            </select>
                        </div>
                    <i class="typcn typcn-chart-bar icon-xl text-secondary"></i>
                    </div>
                    <canvas id="documentAgingChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Average Processing Time of Offices</p>
                            <h1 id="total-documents"><?= $totalOffices ?></h1>
                        </div>
                        <i class="typcn typcn-chart-bar icon-xl text-secondary"></i>
                    </div>
                    <canvas id="averageProcessingTimeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>




          
<!--<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>All Documents</h4>
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
                                        <td><?= $document->sender_id ?></td>
                                        <td><?= $document->status ?></td>
                                        <td><?= $document->action ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                            <a href="#" class="btn btn-info btn-sm btn-icon-text mr-3" onclick="openPdf('http://localhost:8080/public/uploads/1712936838_67a299de09cadcf0215d.pdf')">
                                                View
                                                <i class="typcn typcn-eye btn-icon-append"></i>
                                            </a>

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
    </div>
</div>
                            -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.min.js"></script>
           
<script>


function openPdf(url) {
    window.open(url, '_blank');
}


</script>
