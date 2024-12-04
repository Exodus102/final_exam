<?php
include '../../services/config/db_connection.php';

$errorMessage = ""; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid Email";
    }
    // Validate the password
    else if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/', $password)) {
        $errorMessage = "Password needs to be at least 8 characters with letters and numbers.";
    } else {
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
                $errorMessage = "Email already exists. You cannot register again with this email.";
            } else {
                $conn->begin_transaction();
                // Default to adding the user to the student table
                $statement = $conn->prepare('INSERT INTO tbl_student (email, password, fname, lname) VALUES (?, ?, ?, ?)');
                $statement->bind_param('ssss', $email, $hashed_password, $fname, $lname);

                $statement->execute();
                $conn->commit();
                $errorMessage = "Sign Up Success"; // Successful sign-up
            }
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            if ($e->getCode() === 23000) {
                $errorMessage = "Email already exists.";
            } else {
                error_log($e->getMessage(), 3, 'error_log.txt');
                $errorMessage = "An error occurred. Please try again.";
            }
        }
    }
}
