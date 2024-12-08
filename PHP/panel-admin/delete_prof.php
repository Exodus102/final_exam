<?php
require_once '../../PHP/services/config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    if ($id) {
        // Perform the delete query
        $query = "DELETE FROM tbl_prof WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        // Execute and check if successful
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
}
?>