<?php
require_once '../../PHP/services/config/db_connection.php';
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file


// Decode incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize inputs
$id = isset($data['id']) ? intval($data['id']) : 0;
$email = isset($data['email']) ? mysqli_real_escape_string($conn, $data['email']) : '';
$fname = isset($data['fname']) ? mysqli_real_escape_string($conn, $data['fname']) : '';
$lname = isset($data['lname']) ? mysqli_real_escape_string($conn, $data['lname']) : '';

if ($id && $email && $fname && $lname) {
    // Prepare the update query
    $query = "UPDATE tbl_prof SET email='$email', fname='$fname', lname='$lname' WHERE id=$id";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the user']);
    }
} else {
    // Invalid data
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>