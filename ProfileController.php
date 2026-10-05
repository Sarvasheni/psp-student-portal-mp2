<?php
require_once __DIR__ . '/../Models/UserModel.php';

class ProfileController {
    
    public function uploadProfilePicture() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (isset($_POST['submit']) && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];
            
            $fileName     = $file['name'];
            $fileTmpName  = $file['tmp_name'];
            $fileSize     = $file['size'];
            $fileError    = $file['error'];

            // 1. File Type Validation (.jpg, .jpeg, .png only)
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = array('jpg', 'jpeg', 'png');

            if (!in_array($fileExt, $allowedExtensions)) {
                $_SESSION['error'] = "Invalid file type! Only .jpg, .jpeg, and .png are allowed.";
                header("Location: index.php?action=profile");
                exit();
            }

            // 2. File Size Validation (2MB limit = 2,097,152 bytes)
            if ($fileSize > 2097152) {
                $_SESSION['error'] = "File size exceeds maximum limit of 2MB!";
                header("Location: index.php?action=profile");
                exit();
            }

            if ($fileError !== 0) {
                $_SESSION['error'] = "Error uploading file.";
                header("Location: index.php?action=profile");
                exit();
            }

            // 3. Secure Renaming using uniqid()
            $newFileName = uniqid('student_' . $_SESSION['user_id'] . '_', true) . '.' . $fileExt;
            $uploadDestination = __DIR__ . '/../../uploads/profile_pics/' . $newFileName;

            // Move uploaded file to uploads directory & update database
            if (move_uploaded_file($fileTmpName, $uploadDestination)) {
                $userModel = new UserModel();
                $updated = $userModel->updateProfilePicture($_SESSION['user_id'], $newFileName);

                if ($updated) {
                    $_SESSION['profile_picture'] = $newFileName;
                    $_SESSION['success'] = "Profile picture updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update database record.";
                }
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
            }

            header("Location: index.php?action=profile");
            exit();
        }
    }
}
?>