<?php
require_once '../../PHP/services/config/db_connection.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "SELECT profile_pic FROM tbl_student WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($profilePic);
        $stmt->fetch();
        $stmt->close();

        if ($profilePic) {
            header("Content-Type: image/jpeg"); // Adjust MIME type based on your image format
            echo $profilePic;
            exit;
        }
    }
}
header("HTTP/1.1 404 Not Found");
exit;
