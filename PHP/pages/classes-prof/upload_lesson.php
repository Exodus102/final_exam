<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Enable error logging for debugging
ini_set('display_errors', 0); // Disable displaying errors to the user
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'C:/xampp/php/logs/php-error.log'); // Update the log file path as needed

header('Content-Type: application/json');
$response = ['status' => '', 'message' => ''];

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Validate session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User session not found. Please log in again.');
    }
    $userId = $_SESSION['user_id'];

    // Validate inputs
    if (empty($_POST['lesson']) || empty($_POST['description']) || empty($_POST['course'])) {
        throw new Exception('Missing required fields.');
    }
    $lesson = htmlspecialchars($_POST['lesson']);
    $description = htmlspecialchars($_POST['description']);
    $courseName = htmlspecialchars($_POST['course']);

    // Validate file upload
    if (!isset($_FILES['fileUpload']) || $_FILES['fileUpload']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error. Please try again. Error code: ' . $_FILES['fileUpload']['error']);
    }

    $fileTmpName = $_FILES['fileUpload']['tmp_name'];
    $fileName = $_FILES['fileUpload']['name'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileType = $_FILES['fileUpload']['type'];

    // Check for empty file or upload errors
    if ($fileSize === 0) {
        throw new Exception('The file is empty.');
    }

    // More robust file type check using mime_content_type() or finfo_file()
    if (empty($fileType)) {
        $fileType = mime_content_type($fileTmpName); // Detect MIME type using PHP's mime_content_type()
    }

    // Validate file type (allow any type, but check file extension if needed)
    $allowedTypes = [
        'application/pdf',         // PDF files
        'application/msword',      // Word files
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Word (.docx) files
        'application/vnd.ms-excel', // Excel files
        'image/jpeg',              // JPEG images
        'image/png',               // PNG images
        'video/mp4',               // MP4 video files
        'video/avi',               // AVI video files
        'video/mov',               // MOV video files
        'video/mkv',               // MKV video files
        'application/octet-stream' // General binary data
    ];

    if (!in_array($fileType, $allowedTypes) && !preg_match('/\.(mp4|avi|mov|mkv)$/i', $fileName)) {
        throw new Exception('Invalid file type. Only PDF, Word, Excel, images, and video files are allowed.');
    }

    // Validate file size (limit to 50MB for videos and large files)
    $maxFileSize = 50 * 1024 * 1024; // 50 MB (for larger files like videos)
    if ($fileSize > $maxFileSize) {
        throw new Exception('File is too large. Maximum allowed size is 50 MB.');
    }

    // Ensure that the file content is captured
    $fileContent = file_get_contents($fileTmpName);
    if ($fileContent === false || empty($fileContent)) {
        throw new Exception('Failed to read the file content or the file is empty.');
    }

    // Debugging: Check file size and content
    error_log('File size: ' . $fileSize);
    error_log('File type: ' . $fileType);
    error_log('File content (base64 encoded): ' . base64_encode($fileContent));

    // Get instructor course information
    $query = "SELECT ic.id AS instructor_course_id, ic.course_name, p.id AS prof_id 
              FROM instructor_courses ic
              JOIN tbl_prof p ON p.id = ic.prof_id
              WHERE p.id = ? AND ic.course_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $courseName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('No matching course found for the instructor.');
    }

    $row = $result->fetch_assoc();
    $instructorCourseId = $row['instructor_course_id'];
    $profId = $row['prof_id'];

    // Insert lesson into the database
    $insertQuery = "INSERT INTO course_content 
    (instructor_course_id, course_name, prof_id, lesson, contents, file_name, file_type, file_size, files)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "isisssiis", // Parameter types: s = string, i = integer, b = blob
        $instructorCourseId,
        $courseName,
        $profId,
        $lesson,
        $description,
        $fileName,
        $fileType,
        $fileSize,
        $fileContent // Bind binary content here
    );

    if ($insertStmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Lesson uploaded successfully!';
    } else {
        throw new Exception('Error uploading lesson: ' . $insertStmt->error);
    }

    $insertStmt->close();
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Close database connection
$conn->close();

// Return JSON response
echo json_encode($response);
