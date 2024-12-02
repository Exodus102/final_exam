<?php
include '../../services/config/db_connection.php';
session_start();


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$student_id = $_SESSION['user_id'];

// Query to fetch enrolled classes
$sql_enrolled_classes = "
    SELECT c.class_code, c.subject_name, c.subject_code, p.fname AS professor_fname, p.lname AS professor_lname
    FROM tbl_enrolled_students es
    JOIN class_prof c ON es.class_id = c.class_id
    JOIN tbl_prof p ON c.user_id = p.id
    WHERE es.student_id = ?
";
$stmt_enrolled_classes = $conn->prepare($sql_enrolled_classes);
$stmt_enrolled_classes->bind_param("i", $student_id);
$stmt_enrolled_classes->execute();
$result_enrolled_classes = $stmt_enrolled_classes->get_result();

$enrolled_classes = [];
if ($result_enrolled_classes->num_rows > 0) {
    while ($row = $result_enrolled_classes->fetch_assoc()) {
        $enrolled_classes[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'enrolledClasses' => $enrolled_classes
]);

$stmt_enrolled_classes->close();
CloseCon($conn);
?>