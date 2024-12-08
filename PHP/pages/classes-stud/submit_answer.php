<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$userId = $_SESSION['user_id']; // Retrieve user_id from session

// Retrieve the submitted answers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers']; // User's answers from the form
    $courseName = isset($_POST['course_name']) ? $_POST['course_name'] : '';
    $lesson = isset($_POST['lesson']) ? $_POST['lesson'] : '';

    // Validate inputs
    if (empty($courseName) || empty($lesson)) {
        die("Error: Missing course name or lesson information.");
    }

    // Initialize counters for correct answers
    $correctAnswers = 0;
    $totalQuestions = count($answers);

    // Process each answer
    foreach ($answers as $questionId => $userAnswer) {
        $query = "SELECT answers FROM quiz_courses WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $correctAnswer = $row['answers'];
            $isCorrect = (strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer))) ? 1 : 0;

            $insertQuery = "INSERT INTO user_quiz_attempts (user_id, course_name, lesson, question_id, user_answer, correct_answer, is_correct)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("isssssi", $userId, $courseName, $lesson, $questionId, $userAnswer, $correctAnswer, $isCorrect);
            $insertStmt->execute();

            if ($isCorrect) {
                $correctAnswers++;
            }
        }
    }

    // Calculate score and insert into user_scores table
    $scorePercentage = ($correctAnswers / $totalQuestions) * 100;
    $scoreQuery = "INSERT INTO user_scores (user_id, course_name, lesson, total_questions, correct_answers, score_percentage)
                   VALUES (?, ?, ?, ?, ?, ?)";
    $scoreStmt = $conn->prepare($scoreQuery);
    $scoreStmt->bind_param("issidd", $userId, $courseName, $lesson, $totalQuestions, $correctAnswers, $scorePercentage);
    $scoreStmt->execute();

    echo "Your score: $correctAnswers out of $totalQuestions. Score: $scorePercentage%";
}
