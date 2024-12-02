<?php
session_start(); // Make sure the session is started to access $_SESSION

include '../../services/config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $comment = trim($_POST['comment']);
    $post_id = (int)$_POST['post_id']; // Ensuring post_id is treated as an integer
    $student_id = $_SESSION['user_id']; // Assuming the student is logged in

    // Check if comment is not empty
    if (!empty($comment)) {
        try {
            // Prepare and execute the insert query
            $stmt = $conn->prepare("INSERT INTO tbl_comments (post_id, student_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $post_id, $student_id, $comment);

            if ($stmt->execute()) {
                // Redirect back to the course page to refresh the comments
                header("Location: courses.php?classCode=" . $_GET['classCode']);
                exit;
            } else {
                // Handle error gracefully (perhaps redirect to an error page)
                $_SESSION['error_message'] = "Error submitting comment: " . $stmt->error;
                header("Location: courses.php?classCode=" . $_GET['classCode']);
                exit;
            }
        } catch (Exception $e) {
            // Log error message
            error_log("Error in comment submission: " . $e->getMessage());

            // Redirect with an error message
            $_SESSION['error_message'] = "An unexpected error occurred. Please try again later.";
            header("Location: courses.php?classCode=" . $_GET['classCode']);
            exit;
        }
    } else {
        // If the comment is empty, redirect back with an error message
        $_SESSION['error_message'] = "Comment cannot be empty.";
        header("Location: courses.php?classCode=" . $_GET['classCode']);
        exit;
    }
}
?>
