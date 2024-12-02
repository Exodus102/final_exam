<?php
include '../../services/config/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

// Check if the post_id is provided
if (isset($data['post_id'])) {
    $post_id = $data['post_id'];

    // Fetch comments for the specified post
    $stmt = $conn->prepare("SELECT c.comment, c.date_added, s.fname AS student_fname, s.lname AS student_lname, s.profile_pic 
                            FROM tbl_comments c
                            JOIN tbl_student s ON c.student_id = s.id
                            WHERE c.post_id = ?
                            ORDER BY c.date_added ASC");

    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $comments = [];

        while ($row = $result->fetch_assoc()) {
            $comments[] = [
                'student_name' => $row['student_fname'] . ' ' . $row['student_lname'],
                'profile_pic' => $row['profile_pic'] ? 'data:image/jpeg;base64,' . base64_encode($row['profile_pic']) : 'img/default-profile.jpg',
                'comment' => $row['comment'],
                'date_added' => date("F j, Y, g:i a", strtotime($row['date_added'])),
            ];
        }

        echo json_encode(['success' => true, 'comments' => $comments]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch comments']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing post_id']);
}
?>
