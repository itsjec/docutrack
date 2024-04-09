<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Users</h4>
                        <p class="card-description">Track and update users.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Image</th>
                                <th>Office</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['first_name'] ?></td>
                                <td><?= $user['last_name'] ?></td>
                                <td><?= $user['role'] ?></td>
                                <td><img src="<?= $user['image'] ?>" alt="User Image" width="50"></td>
                                <td><?= isset($user['office_name']) ? $user['office_name'] : 'N/A' ?></td>
                                <td>
                                    <a href="<?= base_url('edit/' . $user['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="<?= base_url('delete/' . $user['id']) ?>" class="btn btn-sm btn-danger">Delete</a>
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



<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <form id="addUserForm" action="<?= site_url('users/save') ?>" method="post">
        <div class="form-group">
            <label for="officeId">Office</label>
            <select class="form-control" id="officeId" name="officeId">
                <?php foreach ($offices as $office): ?>
                    <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                </div>
                <div class="col">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    <meter max="4" id="password-strength-meter"></meter>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('password').addEventListener('input', function () {
        var password = document.getElementById('password').value;
        var result = zxcvbn(password);
        var meter = document.getElementById('password-strength-meter');

        meter.value = result.score;
        switch (result.score) {
            case 0:
                meter.style.color = 'red';
                break;
            case 1:
                meter.style.color = 'orange';
                break;
            case 2:
                meter.style.color = 'yellow';
                break;
            case 3:
                meter.style.color = 'green';
                break;
            case 4:
                meter.style.color = 'darkgreen';
                break;
        }
    });
</script>
