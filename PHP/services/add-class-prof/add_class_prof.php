<?php
include "../config/db_connection.php";

session_start(); 
$user_id = $_SESSION['user_id']; 

if (!isset($user_id)) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$subject_code = $data['subject_code'];
$subject_name = $data['subject_name'];
$section = $data['section'];
$class_code = $data['class_code'];

$query = "INSERT INTO class_prof (subject_name, subject_code, section, class_code, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssi", $subject_name, $subject_code, $section, $class_code, $user_id);

if ($stmt->execute()) {

    echo json_encode(['success' => true, 'message' => 'Class added successfully']);
} else {

    echo json_encode(['success' => false, 'message' => 'Failed to add class']);
}

$stmt->close();
$conn->close();
