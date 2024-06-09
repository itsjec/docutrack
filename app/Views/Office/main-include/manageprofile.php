<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <div>
                <h3>Update Profile Information</h3>
            </div>
            <div class="row">
                <div class="col-md-4">
                <div class="profile-picture" style="margin-top: 20px; width: 300px; height: 300px; background-color: #ddd; background-position: center; background-size: cover; position: relative; display: flex; justify-content: center; align-items: flex-end; border-radius: 50%; overflow: hidden;">
                        <?php if (!empty($user['picture_path'])): ?>
                            <img id="previewImage" src="/uploads/<?= $user['picture_path'] ?>" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img id="previewImage" src="/uploads/placeholder.jpg" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                        <input type="file" id="profileImageInput" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    </div>
                </div>
                <div class="col-md-8">
                    <form action="/office/updateProfile" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $user['first_name'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $user['last_name'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profileImage">Profile Picture</label>
                                    <input type="file" class="form-control-file" id="profileImageInput" name="profileImage">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#profileImageInput').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
