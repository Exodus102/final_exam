<?php
include '../config/db_connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); // This is the username for admin
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: ../../pages/login/loginv2.php?error=Please%20fill%20in%20both%20email%20and%20password");
        exit;
    }

    try {
        // Check if the email is in tbl_prof (professor table)
        $stmt = $conn->prepare('SELECT id, email, password FROM tbl_prof WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (!password_verify($password, $user['password'])) {
                header("Location: ../../pages/login/loginv2.php?error=Incorrect%20password");
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ../../panel-prof/panel_prof.php");
                exit;
            }
        }

        // Check if the email is in tbl_student (student table)
        $stmt = $conn->prepare('SELECT id, email, password FROM tbl_student WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (!password_verify($password, $user['password'])) {
                header("Location: ../../pages/login/loginv2.php?error=Incorrect%20password");
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ../../panel-stud/panel_stud.php");
                exit;
            }
        }

        // Check if the username is in tbl_admin (admin table)
        $stmt = $conn->prepare('SELECT id, username, password FROM tbl_admin WHERE username = ?');
        $stmt->bind_param('s', $email); // Here, we check for username (not email)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (!password_verify($password, $user['password'])) {
                header("Location: ../../pages/login/loginv2.php?error=Incorrect%20password");
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ../../panel-admin/panel_admin.php"); // Redirect to admin panel
                exit;
            }
        }

        // If no user is found in any table
        header("Location: ../../pages/login/loginv2.php?error=No%20user%20found%20with%20that%20username");
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage(), 3, 'error_log.txt');
        header("Location: ../../pages/login/loginv2.php?error=An%20error%20occurred.%20Please%20try%20again.");
        exit;
    }
}
