<?php
// Include the database connection file
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';
session_start(); // Start the session

$data = []; // Default response

try {
    // Check if the user is logged in and the user_id is available in session
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id']; // Fetch the logged-in user's ID

        // Prepare SQL query to fetch quiz data
        $sql = "SELECT course_name, lesson, total_questions, correct_answers, score_percentage, score_timestamp 
                FROM user_scores 
                WHERE user_id = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the user_id parameter
            $stmt->bind_param("i", $userId);  // "i" is for integer type

            // Execute the statement
            $stmt->execute();

            // Bind result variables
            $stmt->bind_result($courseName, $lesson, $totalQuestions, $correctAnswers, $scorePercentage, $scoreTimestamp);

            // Fetch data
            while ($stmt->fetch()) {
                $data[] = [
                    'course_name' => $courseName,
                    'lesson' => $lesson,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'score_percentage' => $scorePercentage,
                    'score_timestamp' => $scoreTimestamp
                ];
            }

            // Close the statement
            $stmt->close();
        } else {
            $data = ["error" => "Failed to prepare the query."];
        }
    } else {
        // If the user is not logged in, return an error message
        $data = ["error" => "User not logged in."];
    }
} catch (Exception $e) {
    // Return error if there's an issue with the database
    $data = ["error" => "Error: " . $e->getMessage()];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Quiz Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="p-5 w-full">
        <div class="w-full max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-5 rounded-lg shadow-lg mb-6">
                <span class="text-white font-bold text-2xl">Student Assessment Results</span>
            </div>

            <!-- Quiz Results Section -->
            <div id="quiz-results" class="quiz-list space-y-6">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // This is the PHP data from the backend embedded into the frontend
        const data = <?php echo json_encode($data); ?>;

        const quizList = document.querySelector('.quiz-list');
        quizList.innerHTML = ""; // Clear previous content

        if (data.error) {
            // Display error if the user is not logged in or there's an issue
            quizList.innerHTML = `<div class="bg-red-100 p-4 rounded-lg shadow-md">
                                    <p class="text-red-600 font-semibold">${data.error}</p>
                                  </div>`;
        } else if (data.length > 0) {
            // Display quiz results
            data.forEach(quiz => {
                const quizDiv = document.createElement('div');
                quizDiv.classList.add('bg-white', 'p-6', 'rounded-lg', 'shadow-lg', 'hover:shadow-xl', 'transition-all', 'space-y-4');
                quizDiv.innerHTML = `
                    <h2 class="text-xl font-semibold text-indigo-700">${quiz.course_name}</h2>
                    <p class="text-gray-800">Lesson: <span class="font-medium">${quiz.lesson}</span></p>
                    <p class="text-gray-800">Total Questions: <span class="font-medium">${quiz.total_questions}</span></p>
                    <p class="text-gray-800">Correct Answers: <span class="font-medium">${quiz.correct_answers}</span></p>
                    <p class="text-gray-800">Score: <span class="font-medium text-green-600">${quiz.score_percentage}%</span></p>
                    <p class="text-gray-500">Timestamp: <span class="font-medium">${new Date(quiz.score_timestamp).toLocaleString()}</span></p>
                `;
                quizList.appendChild(quizDiv);
            });
        } else {
            quizList.innerHTML = `<div class="bg-yellow-100 p-4 rounded-lg shadow-md">
                                    <p class="text-yellow-700 font-semibold">No quiz results found.</p>
                                  </div>`;
        }
    </script>

</body>

</html>