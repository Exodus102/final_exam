<?php

include "../config/db_connection.php";

$data = json_decode(file_get_contents('php://input'), true);
$class_code = $data['class_code'];

$query = "SELECT COUNT(*) AS count FROM classes WHERE class_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $class_code);  
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}

$stmt->close();
$conn->close();