<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to access the current user
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

// Include the database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = $_POST['course'] ?? '';
    $lesson = $_POST['lesson'] ?? '';
    $questions = $_POST['questions'] ?? [];
    $answers = $_POST['answers'] ?? [];
    $profId = $_SESSION['user_id'];

    if (empty($courseName) || empty($lesson) || empty($questions) || empty($answers)) {
        die("All fields are required.");
    }

    $query = "SELECT id AS instructor_courses_id, course_name FROM instructor_courses WHERE course_name = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $courseName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid course name.");
    }

    $courseData = $result->fetch_assoc();
    $instructorCoursesId = $courseData['instructor_courses_id'];

    // Insert the main quiz course information once
    $query = "INSERT INTO quiz_courses (instructor_course_id, course_name, prof_id, lesson)
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $instructorCoursesId, $courseName, $profId, $lesson);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload assessment.']);
        exit;
    }

    // Get the inserted quiz_course_id
    $quizCourseId = $stmt->insert_id;
    $stmt->close();

    // Now, insert each question and answer into separate rows in the same quiz_courses table
    $query = "INSERT INTO quiz_courses (instructor_course_id, course_name, prof_id, lesson, questions, answers)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    foreach ($questions as $index => $question) {
        $answer = isset($answers[$index]) ? $answers[$index] : '';
        $stmt->bind_param("isssss", $instructorCoursesId, $courseName, $profId, $lesson, $question, $answer);
        if (!$stmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload question and answer.']);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Assessment uploaded successfully!']);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
