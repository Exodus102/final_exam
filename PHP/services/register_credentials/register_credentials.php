<?php
include '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $course = trim($_POST['course']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);
    $role = strtolower(trim($_POST['role']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid Email";
        exit;
    }
    if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/', $password)) {
        echo "Password needs to be at least 8 characters with letters and numbers.";
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $checkEmailStudent = $conn->prepare('SELECT COUNT(*) FROM tbl_student WHERE email = ?');
        $checkEmailStudent->bind_param('s', $email);
        $checkEmailStudent->execute();
        $checkEmailStudent->bind_result($studentCount);
        $checkEmailStudent->fetch();
        $checkEmailStudent->close();
        $checkEmailProf = $conn->prepare('SELECT COUNT(*) FROM tbl_prof WHERE email = ?');
        $checkEmailProf->bind_param('s', $email);
        $checkEmailProf->execute();
        $checkEmailProf->bind_result($profCount);
        $checkEmailProf->fetch();
        $checkEmailProf->close();
        if ($studentCount > 0 || $profCount > 0) {
            echo "Email already exists. You cannot register again with this email.";
            exit;
        }
        $conn->begin_transaction();
        if ($role === 'student') {
            $statement = $conn->prepare('INSERT INTO tbl_student (email, password, fname, lname, course, role) VALUES (?, ?, ?, ?, ?, ?)');
            $statement->bind_param('ssssss', $email, $hashed_password, $fname, $lname, $course, $role);
        } elseif ($role === 'prof') {
            $statement = $conn->prepare('INSERT INTO tbl_prof (email, password, fname, lname, course, role) VALUES (?, ?, ?, ?, ?, ?)');
            $statement->bind_param('ssssss', $email, $hashed_password, $fname, $lname, $course, $role);
        } else {
            echo "Invalid role.";
            exit;
        }
        $statement->execute();
        $conn->commit();
        echo "Sign Up Success";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        if ($e->getCode() === 23000) {
            echo "Email already exists.";
        } else {
            error_log($e->getMessage(), 3, 'error_log.txt');
            echo "An error occurred. Please try again.";
        }
    } finally {
        $conn->close();
    }
} else {
    echo "Invalid request method.";
}
