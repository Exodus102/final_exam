<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session and check if the user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

// Include the database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Get the lesson name from the query string
$lesson = isset($_GET['lesson']) ? htmlspecialchars($_GET['lesson']) : null;

// Fetch assessment details
$assessmentDetails = [];
$questions = [];

if ($lesson) {
    // Fetch assessment description
    $query = "SELECT course_name FROM quiz_courses WHERE lesson = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $lesson);
    $stmt->execute();
    $result = $stmt->get_result();
    $assessmentDetails = $result->fetch_assoc();
    $stmt->close();

    // Fetch questions and answers
    $query = "SELECT questions, answers FROM quiz_courses WHERE lesson = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $lesson);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    $stmt->close();
} else {
    header("Location: ../PHP/portal/portal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>View Assessment</title>
</head>

<body class="p-5 h-screen bg-[#F3F3F3]">
    <div class="flex flex-col h-full gap-5">
        <!-- Assessment Header -->
        <div class="bg-white shadow-lg rounded-lg p-5">
            <h1 class="text-3xl font-bold">Assessment for <?php echo htmlspecialchars($lesson); ?></h1>
            <?php if ($assessmentDetails): ?>
                <p class="text-lg text-gray-700">Course: <?php echo htmlspecialchars($assessmentDetails['course_name']); ?></p>
            <?php else: ?>
                <p class="text-lg text-red-600">No assessment details available.</p>
            <?php endif; ?>
        </div>

        <!-- Questions and Answers -->
        <div class="bg-white shadow-lg rounded-lg p-5">
            <h2 class="text-2xl font-bold">Questions</h2>
            <?php if (!empty($questions)): ?>
                <ol class="list-decimal pl-5 mt-5">
                    <?php foreach ($questions as $index => $qa): ?>
                        <li class="mb-3">
                            <p class="text-lg font-semibold">Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($qa['questions']); ?></p>
                            <p class="text-gray-700 ml-4">Answer: <?php echo htmlspecialchars($qa['answers']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p class="text-lg text-gray-600">No questions available for this assessment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>