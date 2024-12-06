<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['status' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lesson = htmlspecialchars($_POST['lesson']);
    $description = htmlspecialchars($_POST['description']);
    $courseName = htmlspecialchars($_POST['course']);
    $userId = $_SESSION['user_id'];

    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['fileUpload']['tmp_name'];
        $fileName = $_FILES['fileUpload']['name'];
        $fileSize = $_FILES['fileUpload']['size'];
        $fileType = $_FILES['fileUpload']['type'];
        $fileContent = file_get_contents($fileTmpName);

        // Get instructor course information
        $query = "SELECT ic.id AS instructor_course_id, ic.course_name, p.prof_id 
                  FROM instructor_courses ic
                  JOIN tbl_prof p ON p.prof_id = ic.prof_id
                  WHERE p.user_id = ? AND ic.course_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $userId, $courseName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $instructorCourseId = $row['instructor_course_id'];
            $profId = $row['prof_id'];

            // Insert lesson into the database
            $insertQuery = "INSERT INTO course_content (instructor_course_id, course_name, prof_id, lesson, description, file_name, file_type, file_size, file_content)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("isisssbss", $instructorCourseId, $courseName, $profId, $lesson, $description, $fileName, $fileType, $fileSize, $fileContent);

            if ($insertStmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Lesson uploaded successfully!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error uploading lesson: ' . $insertStmt->error;
            }
            $insertStmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No matching course found for the instructor.';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No file uploaded or an error occurred during file upload.';
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
