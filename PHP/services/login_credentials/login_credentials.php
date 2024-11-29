<?php
include '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        echo "Please fill in both email and password.";
        exit;
    }
    $stmt = $conn->prepare('SELECT id, email, password, role FROM tbl_student WHERE email = ? UNION SELECT id, email, password, role FROM tbl_prof WHERE email = ?');
    $stmt->bind_param('ss', $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            if ($user['role'] == 'prof') {
                $_SESSION['user_id'] = $user['id'];
            }
            if ($user['role'] == 'student') {
                header("Location: ../../pages/dashboard-stud/dashboard-Finals/frontEnd.php");
            } elseif ($user['role'] == 'prof') {
                header("Location: ../../panel-prof/panel_prof.php");
            } else {
                echo "Invalid role.";
            }
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that email.";
    }
    $stmt->close();
    $conn->close();
}
