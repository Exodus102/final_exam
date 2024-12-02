<?php
include 'db_connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Enable full error reporting

$conn = OpenCon();

session_start();
$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
header("Content-Type: application/json");

// Clear any previous output before sending JSON response
ob_clean();

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    error_log("Failed to decode JSON input");
}

$classCode = $data['classCode'] ?? '';
error_log("Received class code: " . $classCode);  // Log received class code

if (empty($classCode)) {
    echo json_encode(['success' => false, 'message' => 'Class code is required.']);
    exit;
}

// Query to check if the class exists and get the class_id
$sql = "
    SELECT 
        c.class_id, 
        c.subject_name, 
        c.subject_code, 
        c.class_code, 
        p.fname AS professor_fname, 
        p.lname AS professor_lname
    FROM class_prof c
    INNER JOIN tbl_prof p ON c.user_id = p.id
    WHERE c.class_code = ?
";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("s", $classCode);  // Bind class_code as string (s)
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $class_id = $row['class_id'];  // Get the class_id

    // Check if student is already enrolled
    $check_enrollment_sql = "
        SELECT enrollment_id 
        FROM tbl_enrolled_students 
        WHERE student_id = ? AND class_id = ?
    ";
    $check_enrollment_stmt = $conn->prepare($check_enrollment_sql);
    $check_enrollment_stmt->bind_param("ii", $student_id, $class_id);
    $check_enrollment_stmt->execute();
    $check_enrollment_result = $check_enrollment_stmt->get_result();

    // If student is not already enrolled, insert into tbl_enrolled_students
    if ($check_enrollment_result->num_rows == 0) {
        $insert_sql = "
            INSERT INTO tbl_enrolled_students (student_id, class_id) 
            VALUES (?, ?)
        ";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $student_id, $class_id);  // Bind class_id instead of class_code

        if ($insert_stmt->execute()) {
            // Success response
            $response = [
                'success' => true,
                'message' => 'Successfully enrolled!',
                'classCode' => $row['class_code'],
                'subjectName' => $row['subject_name'],
                'subjectCode' => $row['subject_code'],
                'professorName' => $row['professor_fname'] . ' ' . $row['professor_lname'],
            ];
        } else {
            $response = ['success' => false, 'message' => 'Failed to enroll. Please try again.'];
        }
        $insert_stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'You are already enrolled in this class.'];
    }

    $check_enrollment_stmt->close();
} else {
    $response = ['success' => false, 'message' => 'Invalid class code.'];
}

// Log the response to verify what is being returned
error_log('Response: ' . json_encode($response));  // Log full JSON response

// Send the JSON response
echo json_encode($response);

$stmt->close();
CloseCon($conn);
?>