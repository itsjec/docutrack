<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h2>Update Profile Information</h2>
            <div class="row">
                <div class="col-md-4">
                <div class="profile-picture" style="width: 300px; height: 300px; background-color: #ddd; background-position: center; background-size: cover; position: relative; display: flex; justify-content: center; align-items: flex-end; border-radius: 5px; overflow: hidden;">
                    <?php if (!empty($user['picture_path'])): ?>
                        <img id="previewImage" src="/uploads/<?= $user['picture_path'] ?>" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <img id="previewImage" src="placeholder.jpg" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                    <input type="file" id="profileImage" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                </div>
                </div>
                <div class="col-md-8">
                <form action="/office/updateProfile" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $user['first_name'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $user['last_name'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="profileImage">Profile Picture</label>
                            <input type="file" class="form-control-file" id="profileImageInput" name="profileImage">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#profileImage').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#uploadBtn').click(function() {
            $('#profileImageInput').click();
        });
    });
</script>
