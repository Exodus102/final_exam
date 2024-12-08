<?php
/*
require_once '../services/config/db_connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];

    // Fetch current data
    $stmt = $conn->prepare("SELECT fname, lname FROM tbl_prof WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_fname = $row['fname'];
    $current_lname = $row['lname'];

    // Get new values (use current values as fallback)
    $new_fname = $_POST['fname'] ?? $current_fname;
    $new_lname = $_POST['lname'] ?? $current_lname;

    // Prevent overwriting with blank values
    $new_fname = !empty(trim($new_fname)) ? trim($new_fname) : $current_fname;
    $new_lname = !empty(trim($new_lname)) ? trim($new_lname) : $current_lname;

    // Update query
    $update_stmt = $conn->prepare("UPDATE tbl_student SET fname = ?, lname = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_fname, $new_lname, $user_id);

    if ($update_stmt->execute()) {
        header("Location: settings_prof.php?success=1");
    } else {
        header("Location: settings_prof.php?error=1");
    }
    exit;
}
?>