<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Deleted Documents</h4>
                        <p class="card-description">Track and manage documents.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Tracking Number</th>
                                <th>Date of Letter</th>
                                <th>Date Deleted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= $document->title ?></td>
                                    <td><?= $document->tracking_number ?></td>
                                    <td><?= $document->date_of_letter ?></td>
                                    <td><?= $document->date_deleted ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
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
