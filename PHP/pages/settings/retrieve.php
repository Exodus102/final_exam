<?php
include '../../services/config/db_connection.php';


$classCode = isset($_GET['classCode']) ? $_GET['classCode'] : null;
$response = [];

if ($classCode) {
    // Fetch class, professor details, and profile picture
    $stmt = $conn->prepare("
        SELECT cp.class_code, tp.fname, tp.lname, tp.profile_pic, cp.subject_name
        FROM class_prof cp
        INNER JOIN tbl_prof tp ON cp.user_id = tp.id
        WHERE cp.class_code = ?
    ");
    $stmt->bind_param("s", $classCode);
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($class_code, $prof_fname, $prof_lname, $profile_pic_blob, $subject_name);
            $stmt->fetch();
            $response['class_code'] = $class_code;
            $response['professor_name'] = $prof_fname . ' ' . $prof_lname;
            $response['subject'] = $subject_name;
            $response['profile_pic'] = $profile_pic_blob ? base64_encode($profile_pic_blob) : 'img/default-profile.jpg';
        } else {
            $response['error'] = 'Class not found.';
        }
    } else {
        $response['error'] = 'Failed to execute query for professor details.';
    }
    $stmt->close();

    // Fetch enrolled students
    $stmt = $conn->prepare("
        SELECT s.fname, s.lname 
        FROM tbl_student s
        INNER JOIN tbl_enrolled_students es ON s.id = es.student_id
        INNER JOIN class_prof cp ON es.class_id = cp.class_id
        WHERE cp.class_code = ?
    ");
    $stmt->bind_param("s", $classCode);
    if ($stmt->execute()) {
        $stmt->store_result();
        $students = [];
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($student_fname, $student_lname);
            while ($stmt->fetch()) {
                $students[] = $student_fname . ' ' . $student_lname;
            }
        }
        $response['students'] = $students;
    } else {
        $response['error'] = 'Failed to execute query for enrolled students.';
    }
    $stmt->close();
} else {
    $response['error'] = 'Class code is missing.';
}

$comments = [];
try {
    $stmt = $conn->prepare("SELECT c.comment, s.fname, s.lname, s.profile_pic, c.created_at FROM tbl_comments c 
                            INNER JOIN tbl_student s ON c.student_id = s.id
                            WHERE c.post_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $post_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $comments[] = [
                'comment' => $row['comment'],
                'student_name' => $row['fname'] . ' ' . $row['lname'],
                'student_profile_pic' => $row['profile_pic']
                    ? 'data:image/jpeg;base64,' . base64_encode($row['profile_pic'])
                    : 'img/default-profile.jpg',
                'created_at' => date("F j, Y, g:i a", strtotime($row['created_at'])),
            ];
        }
    }
} catch (Exception $e) {
    error_log("Error fetching comments: " . $e->getMessage());
}


// Set variables for use in the HTML page
$subject = $response['subject'] ?? 'Unknown Subject';
$prof = $response['professor_name'] ?? 'Unknown Instructor';
$profilePic = $response['profile_pic'] ?? 'img/default-profile.jpg';
$students = $response['students'] ?? [];
