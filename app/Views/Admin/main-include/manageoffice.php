<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Offices</h4>
                        <p class="card-description">Track and update offices.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary">Your Button</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Office Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offices as $office): ?>
                                <tr>
                                    <td><?= $office['office_name'] ?></td>
                                    <td>
                                        <a href="<?= base_url('edit/' . $office['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('delete/' . $office['id']) ?>" class="btn btn-sm btn-danger">Delete</a>
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
