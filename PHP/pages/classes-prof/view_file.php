<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

if (isset($_GET['file'])) {
    $fileName = basename(urldecode($_GET['file'])); // Prevent directory traversal attacks

    // Query to get the file from the database
    $query = "SELECT file_name, files, file_type FROM course_content WHERE file_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $fileName);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dbFileName, $fileData, $fileType);

    if ($stmt->fetch()) {
        // Set appropriate headers based on file type
        switch ($fileType) {
            case 'application/pdf':
                header("Content-Type: application/pdf");
                break;
            case 'video/mp4':
                header("Content-Type: video/mp4");
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': // MS Word (docx)
                header("Content-Type: application/msword");
                break;
            default:
                header("Content-Type: application/octet-stream"); // fallback for unknown file types
        }

        // Ensure the file is displayed inline, not downloaded
        header("Content-Disposition: inline; filename=\"$fileName\"");
        header("Content-Length: " . strlen($fileData));

        // Output the file content (prevent downloading)
        echo $fileData;
    } else {
        echo "File not found in the database.";
    }
    $stmt->close();
} else {
    echo "No file specified.";
}
