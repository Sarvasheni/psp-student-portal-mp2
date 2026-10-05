<form action="index.php?action=upload_profile" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="profile_picture" class="form-label">Choose Profile Picture (.jpg, .jpeg, .png, max 2MB):</label>
        <input type="file" class="form-control" name="profile_picture" id="profile_picture" accept=".jpg,.jpeg,.png" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Upload Picture</button>