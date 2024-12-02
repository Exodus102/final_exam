<?php
include '../../services/config/db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$response = [];

// Query to fetch enrolled courses
$sql = "
    SELECT 
        c.class_code,
        c.subject_name,
        c.subject_code,
        p.fname AS professor_fname,
        p.lname AS professor_lname
    FROM tbl_enrolled_students e
    INNER JOIN class_prof c ON e.class_id = c.class_id
    INNER JOIN tbl_prof p ON c.user_id = p.id
    WHERE e.student_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            'classCode' => $row['class_code'],
            'subjectName' => $row['subject_name'],
            'subjectCode' => $row['subject_code'],
            'professorName' => $row['professor_fname'] . ' ' . $row['professor_lname'],
        ];
    }
} else {
    error_log("Failed to fetch courses: " . $stmt->error);
}

$stmt->close();
echo json_encode($response);
?>