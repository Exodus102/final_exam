<?php
include '../../services/config/db_connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();


    // Check if file was uploaded without errors
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file_type, $allowed_types)) {
            try {
                // Read file content as binary
                $file_content = file_get_contents($file_tmp);

                // Ensure session user ID is set
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception("User not logged in.");
                }
                $user_id = $_SESSION['user_id'];

                // Update the database
                $stmt = $conn->prepare("UPDATE tbl_student SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $file_content, $user_id); // Use "s" for string since binary is sent as a string
                if ($stmt->execute()) {
                    header("Location: settings.php?success=1");
                    exit;
                } else {
                    throw new Exception("Failed to update profile_pic: " . $stmt->error);
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                header("Location: settings.php?error=1");
                exit;
            }
        } else {
            // Invalid file type
            header("Location: settings.php?error=invalid_file");
            exit;
        }
    } else {
        // No file uploaded or an error occurred
        header("Location: settings.php?error=upload_error");
        exit;
    }
}
?>