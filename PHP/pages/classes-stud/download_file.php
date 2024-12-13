<?php
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

if (!isset($_GET['id'])) {
    die("File ID not specified.");
}

$fileId = intval($_GET['id']);
$sql = "SELECT file_name, file_type, file_size, files FROM course_content WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->bind_result($fileName, $fileType, $fileSize, $fileData);
    if ($stmt->fetch()) {
        header("Content-Type: $fileType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Length: $fileSize");
        echo $fileData;
    } else {
        die("File not found.");
    }
    $stmt->close();
} else {
    die("Query error: " . $conn->error);
}

$conn->close();
?>
