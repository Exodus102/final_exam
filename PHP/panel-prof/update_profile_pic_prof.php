<?php
/*
require_once '../services/config/db_connection.php';

session_start(); // Ensure session is started
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login/loginv2.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file_type, $allowed_types)) {
            try {
                $file_content = file_get_contents($file_tmp); // Read binary data
                $user_id = $_SESSION['user_id'];

                // Update the database
                $stmt = $conn->prepare("UPDATE tbl_prof SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $file_content, $user_id); // "s" for blob, "i" for integer

                if ($stmt->execute()) {
                    header("Location: ../panel_prof.php?page=settings_prof&success=1");
                    exit;
                } else {
                    throw new Exception("Database update failed: " . $stmt->error);
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                header("Location: ../panel_prof.php?page=settings_prof.php?error=1");
                exit;
            }
        } else {
            header("Location: ../panel_prof.php?page=settings_prof.php?error=invalid_file");
            exit;
        }
    } else {
        header("Location: ../panel_prof.php?page=settings_prof.php?error=upload_error");
        exit;
    }
} else {
    header("Location: ../panel_prof.php?page=settings_prof.php");
    exit;
}
?>