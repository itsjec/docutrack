<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Material Design Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
</head>
<body>
<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Client Users</h4>
                        <p class="card-description">Track and update users.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                            <i class="mdi mdi-account-plus" style="font-size: 20px; margin-right: 8px;"></i> Add Client
                        </button>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="myClientTable">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($guestUsers as $user): ?>
                            <tr>
                                <td><?= $user['first_name'] ?></td>
                                <td><?= $user['last_name'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary edit-btn" 
                                        data-toggle="modal" 
                                        data-target="#editUserModal" 
                                        data-userid="<?= $user['user_id'] ?>" 
                                        data-firstname="<?= $user['first_name'] ?>" 
                                        data-lastname="<?= $user['last_name'] ?>" 
                                        data-email="<?= $user['email'] ?>">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'btn-success' : 'btn-danger' ?> deactivate-btn" 
                                        data-toggle="modal" 
                                        data-target="#<?= isset($user['status']) && $user['status'] == 'deactivate' ? 'activateUserModal_'.$user['user_id'] : 'deactivateUserModal_'.$user['user_id']?>"
                                        data-userid="<?= $user['user_id'] ?>">
                                            <i class="mdi <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'mdi-check' : 'mdi-close' ?>"></i>
                                            <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'Activate' : 'Deactivate' ?>
                                    </a>
                                </td>
                            </tr>

                            <!-- Activate User Modal -->
                            <div class="modal fade" id="activateUserModal_<?= $user['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="activateUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="activateUserModalLabel">Activate User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to activate this user?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-success" id="confirmActivate_<?= $user['user_id'] ?>" data-userid="<?= $user['user_id'] ?>">Activate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deactivate User Modal -->
                            <div class="modal fade" id="deactivateUserModal_<?= $user['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deactivateUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deactivateUserModalLabel">Deactivate User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to deactivate this user?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmDeactivate_<?= $user['user_id'] ?>" data-userid="<?= $user['user_id'] ?>">Deactivate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>





<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= site_url('updateguest') ?>" method="post">
                <input type="hidden" id="editUserId" name="userId">
                    <div class="form-group">
                        <label for="editFirstName">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="firstName" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label for="editLastName">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="lastName" placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Password</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Enter password">
                        <meter max="4" id="editPassword-strength-meter"></meter>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal HTML -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add Client Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>
                
                <form id="addUserForm" method="post">
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
        </div>
    </div>
</div>

<!-- Generic Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="/delete-user/<?= $user['user_id'] ?>" class="btn btn-danger delete-confirm-btn">Delete</a>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

<!-- JavaScript for password strength meter -->
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


    document.getElementById('editPassword').addEventListener('input', function () {
        var password = document.getElementById('editPassword').value;
        var result = zxcvbn(password);
        var meter = document.getElementById('editPassword-strength-meter');

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

    $(document).ready(function() {
        $('#addUserForm').on('submit', function(event) {
            event.preventDefault(); 

            $.ajax({
                url: '<?= site_url('saveguest') ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.error) {
                        $('#errorMessages').text(response.error).show();
                    } else {
                        $('#errorMessages').hide();
                        $('#addUserModal').modal('hide');
                        window.location.href = '<?= site_url('manageguest') ?>';
                    }
                },
                error: function() {
                    $('#errorMessages').text('An unexpected error occurred. Please try again.').show();
                }
            });
        });
    });

    $(document).ready(function() {
        $('body').on('click', '[id^=confirmActivate_]', function() {
            var userId = $(this).data('userid');

            $.ajax({
                url: '<?= site_url('user/activate') ?>',
                type: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to activate user.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    });

    $(document).ready(function() {
        // Handle deactivate button click
        $('body').on('click', '[id^=confirmDeactivate_]', function() {
            var userId = $(this).data('userid');

            $.ajax({
                url: '<?= site_url('user/deactivate') ?>',
                type: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        // Optionally, refresh the user list or update the UI
                        location.reload();
                    } else {
                        alert('Failed to deactivate user.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    });

    $(document).on('click', '.delete-btn', function() {
    var userId = $(this).data('userid');
    var userFirstName = $(this).data('firstname');
    var userLastName = $(this).data('lastname');

    $('#deleteUserModal .modal-body').html(
        'Are you sure you want to delete the user <strong>' + userFirstName + ' ' + userLastName + '</strong>? This action cannot be undone.'
    );

    $('#deleteUserModal .delete-confirm-btn').attr('href', '/delete-user/' + userId);

    $('#deleteUserModal').modal('show');
});


</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.edit-btn').on('click', function(){
            var userId = $(this).data('userid');
            var firstName = $(this).data('firstname');
            var lastName = $(this).data('lastname');
            var email = $(this).data('email');

            $('#editUserId').val(userId);
            $('#editFirstName').val(firstName);
            $('#editLastName').val(lastName);
            $('#editEmail').val(email);
        });
    });
</script>

</body>
</html>
