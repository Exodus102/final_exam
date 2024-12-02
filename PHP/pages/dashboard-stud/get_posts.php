<?php
include '../../services/config/db_connection.php';
header('Content-Type: application/json');

// Get the data from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$classCode = $data['classCode'] ?? null;

if (!$classCode) {
    echo json_encode(['success' => false, 'message' => 'Class code is missing.']);
    exit;
}

try {
    // Get user ID from session
    session_start();
    $user_id = $_SESSION['user_id'];

    // Fetch posts for the given classCode and enrolled students
    $stmt = $conn->prepare("
        SELECT 
            p.id AS post_id,
            p.content,
            p.date_added,
            p.file_url,
            pr.fname AS prof_fname,
            pr.lname AS prof_lname,
            pr.profile_pic AS prof_profile_pic,
            cp.subject_name,
            cp.subject_code
        FROM tbl_posts p
        INNER JOIN tbl_prof pr ON p.prof_id = pr.id
        INNER JOIN class_prof cp ON cp.class_id = (
            SELECT class_id FROM class_prof WHERE class_code = ?
        )
        WHERE cp.class_id IN (
            SELECT class_id FROM tbl_enrolled_students WHERE student_id = ?
        )
        ORDER BY p.date_added DESC
    ");
    $stmt->bind_param("si", $classCode, $user_id);

    $posts = [];
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $posts[] = [
                'post_id' => $row['post_id'],
                'content' => $row['content'],
                'file_url' => $row['file_url'],
                'date_added' => date("F j, Y, g:i a", strtotime($row['date_added'])),
                'prof_name' => "{$row['prof_fname']} {$row['prof_lname']}",
                'prof_profile_pic' => $row['prof_profile_pic']
                    ? 'data:image/jpeg;base64,' . base64_encode($row['prof_profile_pic'])
                    : 'img/default-profile.jpg',
                'subject_name' => $row['subject_name'],
                'subject_code' => $row['subject_code'],
            ];
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error fetching posts."]);
        exit;
    }

    // Send the posts data as a JSON response
    echo json_encode(['success' => true, 'posts' => $posts]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "An unexpected error occurred: " . $e->getMessage()]);
}
?>