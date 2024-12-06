<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/login.php");
    exit;
}

include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$message = ""; // Initialize message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prof_id = $_SESSION['user_id'];
    $email = trim($_POST['email']);
    $course_name = trim($_POST['course']);

    // Debug message to ensure the script is loaded
    echo "Script loaded<br>";

    // Fetch course details
    $sql_course = "SELECT id, course FROM tbl_courses WHERE course = ?";
    $stmt_course = $conn->prepare($sql_course);
    if ($stmt_course) {
        $stmt_course->bind_param("s", $course_name);
        $stmt_course->execute();
        $result_course = $stmt_course->get_result();

        if ($result_course->num_rows > 0) {
            $course = $result_course->fetch_assoc();
            $course_id = $course['id'];
            echo "Course found: $course_name (ID: $course_id)<br>";
        } else {
            $message = "Course not found.";
            echo $message;
        }
        $stmt_course->close();
    } else {
        $message = "Error preparing course query: " . $conn->error;
        echo $message;
    }

    // Fetch professor details
    if (empty($message)) {
        $sql_prof = "SELECT fname, lname, email FROM tbl_prof WHERE id = ?";
        $stmt_prof = $conn->prepare($sql_prof);
        if ($stmt_prof) {
            $stmt_prof->bind_param("i", $prof_id);
            $stmt_prof->execute();
            $result_prof = $stmt_prof->get_result();

            if ($result_prof->num_rows > 0) {
                $prof = $result_prof->fetch_assoc();
                $prof_fname = $prof['fname'];
                $prof_lname = $prof['lname'];
                $prof_email = $prof['email'];
                echo "Professor found: $prof_fname $prof_lname (Email: $prof_email)<br>";
            } else {
                $message = "Professor not found.";
                echo $message;
            }
            $stmt_prof->close();
        } else {
            $message = "Error preparing professor query: " . $conn->error;
            echo $message;
        }
    }

    // Insert into instructor_courses
    if (empty($message)) {
        $sql_insert = "INSERT INTO instructor_courses (course_id, course_name, prof_id, prof_fname, prof_lname, prof_email) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        if ($stmt_insert) {
            $stmt_insert->bind_param("isisss", $course_id, $course_name, $prof_id, $prof_fname, $prof_lname, $prof_email);
            $stmt_insert->execute();

            if ($stmt_insert->affected_rows > 0) {
                $message = "Instructor assigned to the course successfully.";
                echo $message;
            } else {
                $message = "Failed to assign instructor to the course.";
                echo $message;
            }
            $stmt_insert->close();
        } else {
            $message = "Error preparing insert query: " . $conn->error;
            echo $message;
        }
    }

    $conn->close();
}
